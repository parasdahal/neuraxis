from peewee import *
import datetime

db = MySQLDatabase('neuraxis2',user='root',passwd='')

class BaseModel(Model):
    class Meta:
        database = db

class Instance(BaseModel):
    user_id = IntegerField()
    name = CharField(unique=True)
    config = TextField()
    state = CharField()
    pid = IntegerField()
    created_date = DateTimeField(default=datetime.datetime.now)

class TrainedModel(BaseModel):
    instance = ForeignKeyField(Instance, related_name='models')
    path = CharField()
    visualization = TextField()
    created_date = DateTimeField(default=datetime.datetime.now)

def create_tables():
    db.connect()
    db.create_tables([Instance, TrainedModel])