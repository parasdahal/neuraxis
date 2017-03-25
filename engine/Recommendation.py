import os
from pyspark.ml.recommendation import ALS,ALSModel
from pyspark.sql import Row,SQLContext
from pyspark.sql.functions import *
from pyspark.ml.evaluation import RegressionEvaluator
import pandas as pd


import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class Recommendation():

    def __init__(self, sc, datasets, parameters):

        logger.info("Initializing the Recommendation Engine")
        self.sc = sc
        self.sql = SQLContext(self.sc)
        self.parameters = parameters
        ratings , meta = datasets
        logger.info("Loading Ratings data")
        self.ratings_df = self.sql.createDataFrame(ratings.to_pandas())

        logger.info("Loading Meta data...")
        self.meta_df = self.sql.createDataFrame(meta.to_pandas())

    def train(self):

        (training,test) = self.ratings_df.randomSplit([0.8,0.2])

        logger.info("Training the ALS model...")
        
        als = ALS(maxIter=1,regParam=0.1,userCol=self.parameters['userCol'],itemCol=self.parameters['itemCol'],ratingCol=self.parameters['ratingCol'])
        self.model = als.fit(training)
        
        logger.info("ALS model built!")
        return "{}"

    def _predict_ratings(self,user_item_df):
        """
        Takes a dataframe of structure [userId,moviesId] and returns prediction of [userId,ratings,moviesId,title,genre]
        """
        prediction = self.model.transform(user_item_df)
        prediction=prediction.join(self.meta_df,prediction.movieId==self.meta_df.movieId).drop(self.meta_df.movieId)
        
        return prediction

    def _get_user_unrated(self,user_id):
        """
        Takes user_id as input and generates [userId,movieId] dataframe for all movies unrated by user
        """
        logger.info("Getting user's unrated items...")
        
        all_items = self.meta_df.select(self.meta_df.movieId).dropDuplicates() # get all movie ids in a single column
        user_rated_items = self.ratings_df.filter(self.ratings_df.userId == user_id).select(self.ratings_df.movieId).dropDuplicates() # get the movie ids that a user has rated by filtering userid and dropping other columns
        user_unrated_items = all_items.subtract(user_rated_items) # subtract user rated movies from all movies to get a single column of user unrated movie
        user_item_df = user_unrated_items.withColumn("userId",lit(user_id)) # add a column of user id to the unrated movie column
        user_item_df = user_item_df.select(user_item_df.userId,user_item_df.movieId) # swap the columns to generate [userId, movieId] dataframe
        
        return user_item_df

    def top_recommendation(self,params):
        user_id = params['user_id']
        count = params['count']

        logger.info("Predicting top recommendations...")
        
        user_unrated_items = self._get_user_unrated(user_id)
        predictions = self._predict_ratings(user_unrated_items)
        
        p = predictions.sort(desc("prediction")).filter(predictions.prediction != "NaN").limit(count)
        
        return p.toPandas().reset_index().to_json(orient='records')
    
    def save(self,filename):
        self.model.save(filename)
    
    def load(self,filename):
        self.model = ALSModel.load(filename)
