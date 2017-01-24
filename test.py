from recommendation import Recommendation
from pyspark import SparkContext, SparkConf

conf = SparkConf().setAppName("recommendation-test")
sc = SparkContext(conf=conf, pyFiles=['neuraxis/recommendation.py'])

r = Recommendation(sc,'neuraxis/dataset')