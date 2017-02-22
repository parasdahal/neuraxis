import re
import os
import pandas as pd
from pyspark.sql import Row,SQLContext
from pyspark import SparkContext, SparkConf
from pyspark.ml.feature import CountVectorizer,Tokenizer,StopWordsRemover 
# from pyspark.ml.linalg import SparseVector, DenseVector
from linalg import SparseVector, DenseVector
from pyspark.sql.functions import lit
from pyspark.ml.classification import NaiveBayes

from algorithm import Algorithm

import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class SentimentClassifier(Algorithm):

	def __init__(self, sc ,datasets, parameters):

		logger.info("Starting up the Sentiment Classifier Engine")
		
		self.sc = sc
		self.sql = SQLContext(self.sc)
		self.parameters = parameters
		logger.info("Loading training dataset")

		training , document = datasets
		self.train_df=self.sql.createDataFrame(training.to_pandas())
		
		logger.info("Loading document datasets")

		self.test_df=self.sql.createDataFrame(document.to_pandas()).withColumn(self.parameters['label_col'], lit("NaN"))
		
		self.combined_df=self.train_df.unionAll(self.test_df)


	def remove_punc(self,df):
		rdd = df.rdd.map(lambda x: Row(id=x[0],review=re.sub('[^a-zA-Z| |0-9]', '',x[1]),label=x[2]))
		df=self.sql.createDataFrame(rdd)
		return df

	def tokenize(self,df):
		tokenizer=Tokenizer(inputCol="review", outputCol="words")
		tokenized = tokenizer.transform(df)
		remover = StopWordsRemover(inputCol="words", outputCol="filtered_words")
		filtered = remover.transform(tokenized)
		return filtered

	def vectorize(self,df):
		cv = CountVectorizer(inputCol="filtered_words", outputCol="features")
		model = cv.fit(df)
		result = model.transform(df)

		rdd = result.rdd.map(lambda x: Row(id=x[0],label=x[1],features=DenseVector(x[5].toArray())))
		vectorized=self.sql.createDataFrame(rdd)
		return vectorized

	def splitvec(self,vector):
		train_vec=vector.filter(vector.label != "NaN")
		train_vec=train_vec.select(train_vec.id,train_vec.features,train_vec.label.cast('float'))
		test_vec=vector.filter(vector.label == "NaN")
		return train_vec,test_vec

	def train(self):
		logger.info("Starting Training")
		remove_punc=self.remove_punc(self.combined_df)
		filtered_df=self.tokenize(remove_punc)
		vector=self.vectorize(filtered_df)
		train_vec,test_vec=self.splitvec(vector)

		nb = NaiveBayes(smoothing=self.parameters['smoothing'], modelType="multinomial")
		model = nb.fit(train_vec)
		logger.info("Model Created")
		self.result = model.transform(test_vec)

	def predict(self,params):
		id = params['id']
		query_df=self.result.select(self.result.id,self.result.prediction).where(self.result.id == id)
		json=query_df.toPandas().reset_index().to_json(orient='records')
		return json