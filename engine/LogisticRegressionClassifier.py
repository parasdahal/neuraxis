import os
from pyspark.ml.classification import LogisticRegression
from pyspark.ml.feature import VectorAssembler
from pyspark.sql import Row,SQLContext
from pyspark.sql.functions import *
from pyspark.ml.evaluation import RegressionEvaluator
import pandas as pd

from algorithm import Algorithm
from dataset import sanitizer

import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class LogisticRegressionClassifier(Algorithm):

    def __init__(self, sc, datasets, parameters):

        logger.info("Starting up the LR Classification Engine")

        self.sc = sc
        self.sql = SQLContext(self.sc)
        self.parameters = parameters
        logger.info("Loading Traning data")
        training = datasets[0]

        self.training_df = self.sql.createDataFrame(training.to_pandas())
    
    def _assemble_features(self,dataframe,excluded=None):

        features_cols =dataframe.toPandas().columns.values.tolist()
        excluded_cols = [self.parameters['label_col']]
        if(excluded != None):
            excluded_cols = excluded_cols + excluded
        features_cols = [item for item in features_cols if item not in excluded_cols]
        vecAssembler = VectorAssembler(inputCols=features_cols, outputCol="features")
        dataframe = vecAssembler.transform(dataframe)
        return dataframe

    def train(self):
        self.training_df = self._assemble_features(self.training_df,excluded=self.parameters['feature_col'])

        (training,test) = self.training_df.randomSplit([0.8,0.2])
        logger.info("Training the LR model...")
        model = LogisticRegression(regParam=self.parameters['regParam'],labelCol=self.parameters['label_col'])
        self.model = model.fit(training)

        logger.info("LR model built!")

    def predict(self,params):
        
        # convert dictionary of parameters to dataframe
        prediction_df = pd.DataFrame()
        for item in params.items():
            prediction_df[item[0]] = pd.Series([item[1]])
        
        # perform sanitization pipeline 
        prediction_df = sanitizer.Sanitizer(prediction_df).pipeline(["replace_missing_values","encode_labels","normalize_features","int_to_double"])
        
        # create vector of features using vector assembler
        prediction_df = self._assemble_features(self.sql.createDataFrame(prediction_df),excluded=self.parameters['feature_col'])

        prediction = self.model.transform(prediction_df)
        
        return prediction.toPandas().reset_index().to_json(orient='records')