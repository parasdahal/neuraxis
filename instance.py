import os
import json
import importlib
from dataset import dataset
from pyspark import SparkContext, SparkConf
from jsonschema import validate
import time, sys, cherrypy
from paste.translogger import TransLogger


import logging
logging.basicConfig(level=logging.INFO,format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("Instance")

class Instance:
    
    ALGO_PATH = "engine"
    DATASETS_PATH = "neuraxis"
    
    def __init__(self,path):

        self.config = self.load_config(path)
    
    def init_spark_context(self):

        conf = SparkConf().setAppName("Neuraxis")
        context = SparkContext(conf=conf)
        logger.info("Spark Context initialized")
        return context
 
    def load_config(self,path):
        
        config_schema = {
            "algorithm":{"type" : "string"},
            "datasets":[{"name":{"type" : "string"},"path":{"type" : "string"}}],
            "routes":[{"name":{"type" : "string"},"route":{"type" : "string"}}]
        }

        with open(path) as json_data:
            config = json.load(json_data)
            validate(config,config_schema)
            return config
        
        logger.info("Config file loaded")

    def start(self):

        algo_path = Instance.ALGO_PATH+'.' + self.config['algorithm']

        module = importlib.import_module(algo_path)
        algorithm = getattr(module,self.config['algorithm'])
        
        logger.info("Imported module " + self.config['algorithm'])
        
        datasets = []
        for item in self.config['datasets']:
            data = dataset.Dataset()
            path = os.path.join(Instance.DATASETS_PATH,item['path'])
            data.load_from_csv(path)
            datasets.append(data)
        
        # TODO: check if schema of algo and dataset is valid

        context = self.init_spark_context()
        self.algo_instance = algorithm(context,tuple(datasets))

        logger.info("A new instance of " + self.config['algorithm'] + "engine started")
        
    def train(self):

        self.algo_instance.train()
        logger.info("Training the instance")

    def serve(self):

        from flask import Flask, Blueprint,request

        app = Flask(self.config['algorithm'])
        main = Blueprint('main', __name__)
        app.register_blueprint(main)
 
        for route in self.config['routes']:
            def handle():
                return getattr(self.algo_instance,route['name'])(request.get_json())
            app.add_url_rule(
                route['route'],
                route['name'],
                handle,
                methods=['POST'])

        def run_server(app):
            app_logged = TransLogger(app)
            cherrypy.tree.graft(app_logged, '/')
            cherrypy.config.update({
                'engine.autoreload.on':True,
                'log.screen': True,
                'server.socket_port': 9992,
                'server.socket_host': '0.0.0.0'
            })
            cherrypy.engine.start()
            print("Server Started")
            cherrypy.engine.block()
        
        run_server(app)


ins = Instance('neuraxis/instance.json')
ins.start()
ins.train()
ins.serve()