import os
from pyspark.ml.recommendation import ALS
from pyspark.sql import Row,SQLContext
from pyspark.sql.functions import *
from pyspark.ml.clustering import KMeans
from pyspark.ml.feature import VectorAssembler
import pandas as pd

from algorithm import Algorithm

import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class KMeans(Algorithm):

    def __init__(self, sc, datasets, parameters):

        logger.info("Initializing the Recommendation Engine")
        self.sc = sc
        self.sql = SQLContext(self.sc)

        data = datasets[0]
        self.parameters = parameters
        logger.info("Loading data")
        self.data_df = self.sql.createDataFrame(data.to_pandas())

    def _assemble_features(self,dataframe,excluded=None):

        features_cols =dataframe.toPandas().columns.values.tolist()
        if(excluded != None):
            excluded_cols = excluded
        features_cols = [item for item in features_cols if item not in excluded_cols]
        vecAssembler = VectorAssembler(inputCols=features_cols, outputCol="features")
        dataframe = vecAssembler.transform(dataframe)
        return dataframe

    def train(self):

        self.dataset = self._assemble_features(self.data_df,excluded=self.parameters['feature_col'])

        logger.info("Clustering the model...")
        
        kmeans = KMeans().setK(self.parameters['k'])
        model = kmeans.fit(dataset)
        wssse = model.computeCost(dataset)
        centers = model.clusterCenters()
        print("Cluster Centers: ")
        for center in centers:
            print(center)
        logger.info("KMeans model built!")

    def predict(self):
        pass