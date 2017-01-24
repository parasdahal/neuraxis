from flask import Flask, request
import json
from recommendation import Recommendation
from flask import Blueprint
main = Blueprint('main', __name__)

@main.route("/recommendations/<int:user_id>/<int:count>",methods=["GET"])
def get_top_recommendation(user_id,count):
	return recommendation.top_recommendation(user_id,count)
	

def create_app(sc,dataset_path):
	global recommendation
	recommendation = Recommendation(sc,dataset_path)
	app = Flask(__name__)
	app.register_blueprint(main)
	return app