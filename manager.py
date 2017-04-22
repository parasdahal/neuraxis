import os,sys
import multiprocessing,argparse,json,psutil,datetime
from subprocess import *
from peewee import *
from models import Instance,TrainedModel


MODELS_PATH ="storage/models"

def load_instance(instance_name):
    ins = Instance.select().where(Instance.name == instance_name)
    return ins.first()

def get_args():
    parser = argparse.ArgumentParser()
    parser.add_argument("instance_name", help="Command: stop, serve, train, state")
    parser.add_argument("command", help="Command: stop, serve, train")
    args = parser.parse_args()
    return args

def instance_state(ins):
    process = psutil.Process(ins.pid)
    print(ins.state)

def serve_instance(ins):
    model = ins.models.order_by(TrainedModel.id.desc()).get()
    command ='spark-submit instance.py '+ins.name+' '+model.path+' serve'
    log = open('log.txt', 'a')
    process = Popen(command,shell=True,stdout=log, stderr=log)
    ins.state = "SERVING"
    ins.pid = process.pid
    ins.save()
    print(str(ins.pid))

def stop_instance(ins):
    ins= load_instance(ins.name)
    if ins.pid == 0:
        print("STOPPED")
        return
    try:    
        parent = psutil.Process(ins.pid)
        children = parent.children(recursive=True)
        for child in children:
            child.kill()
        psutil.wait_procs(children, timeout=5)
    except(psutil.NoSuchProcess):
        print("STOPPED")
    ins.state ="STOPPED"
    ins.pid =0
    ins.save()
    print("STOPPED")

def train_instance(ins):
    ins = load_instance(ins.name)
    model_name = ins.name+"_"+datetime.datetime.now().strftime("%Y-%m-%d_%H-%M-%S")+'.json'
    command ='spark-submit instance.py '+ins.name+' '+model_name+' train'
    log = open('log.txt', 'a')
    process = Popen(command,shell=True,stdout=log, stderr=log)
    ins.state = "TRAINING"
    ins.pid = process.pid
    ins.save()
    print(str(ins.pid))

if __name__ == "__main__":
    
    args = get_args()
    ins = load_instance(args.instance_name)
    
    if(args.command == "state"):
        instance_state(ins)
    
    if(args.command == "serve"):
        if ins.state == "TRAINING":
            print("Instance is training")
        elif ins.state =="SERVING":
            print("Instance is serving already")
        else:
            serve_instance(ins)

    if(args.command == "stop"):
        stop_instance(ins)

    if(args.command == "train"):
        train_instance(ins)
    sys.exit()