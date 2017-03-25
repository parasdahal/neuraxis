import os
import json
import argparse
import importlib
from dataset import dataset,sanitizer
from pyspark import SparkContext, SparkConf
from jsonschema import validate
import time, sys, cherrypy
from paste.translogger import TransLogger
from peewee import *
from models import Instance,TrainedModel
import logging
logging.basicConfig(level=logging.INFO,format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("Instance")

class InstanceController:
    
    ALGO_PATH = "engine"
    DATASETS_PATH = "storage"
    CONFIG_PATH = "storage/config"
    MODEL_PATH = os.path.join(os.path.dirname(__file__),"storage\models")
    PORT = 1111
    
    def __init__(self,config):

        self.config = config
    
    def init_spark_context(self):

        conf = SparkConf().setAppName("Neuraxis")
        context = SparkContext(conf=conf)
        logger.info("Spark Context initialized")
        return context

    def start(self):

        algo_path = InstanceController.ALGO_PATH+'.' + self.config['algorithm']

        module = importlib.import_module(algo_path)
        algorithm = getattr(module,self.config['algorithm'])
        
        logger.info("Imported module " + self.config['algorithm'])
        
        datasets = []
        for item in self.config['datasets']:
            data = dataset.Dataset()
            path = os.path.join(InstanceController.DATASETS_PATH,item['path'])
            if('type' in item.keys()):
                if(item['type'] == "tsv"):
                    data.load_from_tsv(path)
                if(item['type'] == "csv"):
                    data.load_from_csv(path)
                if(item['type'] == 'pkl'):
                    data.load_from_pkl(path)
            
            if('sanitizer' in item.keys()):
                clean = sanitizer.Sanitizer(data.to_pandas())
                data.load_from_pandas(clean.pipeline(item['sanitizer']))
            datasets.append(data)
        
        # TODO: check if schema of algo and dataset is valid

        context = self.init_spark_context()
        self.algo_instance = algorithm(context,tuple(datasets),self.config['parameters'])
        logger.info("A new instance of " + self.config['algorithm'] + "engine started")
        
    def train(self):

        visualization = self.algo_instance.train()
        logger.info("Instance trained")
        return visualization

    def serve(self):

        from flask import Flask, Blueprint,request

        app = Flask(self.config['algorithm'])
        main = Blueprint('main', __name__)
        app.register_blueprint(main)
 
        for route in self.config['routes']:
            def handle():
                return getattr(self.algo_instance,route['name'])(request.get_json())
            app.add_url_rule(route['route'],route['name'],handle,methods=['POST'])

        def run_server(app):
            app_logged = TransLogger(app)
            cherrypy.tree.graft(app_logged, '/')
            cherrypy.config.update({
                'engine.autoreload.on':True,
                'log.screen': True,
                'server.socket_port': InstanceController.PORT,
                'server.socket_host': '0.0.0.0'
            })
            cherrypy.engine.start()
            logger.info("Server Started on port "+str(InstanceController.PORT))
            cherrypy.engine.block()
        
        run_server(app)
    
    def load_model(self,model):
        model = os.path.join(model)
        self.algo_instance.load(model)
        logger.info("Model loaded")
    
    def save_model(self,filename):
        model = os.path.join(InstanceController.MODEL_PATH,filename)
        self.algo_instance.save(model)
        logger.info("Model saved")
        return model

if __name__ == "__main__":
    parser = argparse.ArgumentParser()
    parser.add_argument("name", help="Name of instance")
    parser.add_argument("model_path", help="Command: stop, serve, train")
    parser.add_argument("command", help="Command: stop, serve, train")
    args = parser.parse_args()
    if(args.command == "serve"):
        i = Instance.select().where(Instance.name == args.name).get()
        ins = InstanceController(json.loads(i.config))
        ins.start()
        ins.load_model(args.model_path)
        logger.info("Model: "+args.model_path)
        ins.serve()
    if(args.command == "train"):
        i = Instance.select().where(Instance.name == args.name).get()
        ins = InstanceController(json.loads(i.config))
        ins.start()
        viz = ins.train()
        path = ins.save_model(args.model_path)
        m = TrainedModel(instance_id=i.id,path=path,visualization=viz)
        m.save()
        i.state = "TRAINED"
        i.pid = 0
        i.save()