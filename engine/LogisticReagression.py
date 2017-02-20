import os
from pyspark.ml.recommendation import ALS
from pyspark.sql import Row,SQLContext
from pyspark.sql.functions import *
from pyspark.ml.evaluation import RegressionEvaluator
import pandas as pd

import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class LogisticRegression:

    def __init__(self, sc, dataset_path):

        logger.info("Starting up the LR Classification Engine")

        self.sc = sc
        self.sql = SQLContext(self.sc)

        logger.info("Loading Traning data")
        # label, weight, features=[vectors]
        training_file_path = os.path.join(dataset_path,'lr.csv')
        pandas_training_df = pd.read_csv(ratings_file_path)
        self.training_df = self.sql.createDataFrame(pandas_training_df)

    def parse(self,dataset_path):
        training_file_path = os.path.join(dataset_path,'lr.csv')


    def train(self):

        (training,test) = self.ratings_df.randomSplit([0.8,0.2])
        logger.info("Training the LR model...")
        als = LogisticRegression(maxIter=5, regParam=0.01, weightCol="weight")
        self.model = als.fit(training)
        logger.info("LR model built!")

    def predict(self,user_item_df):
        """
        Takes a dataframe of structure [userId,moviesId] and returns prediction of [userId,ratings,moviesId,title,genre]
        """
        prediction = self.model.transform(user_item_df)
        return prediction