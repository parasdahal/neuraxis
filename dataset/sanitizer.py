import os
import numpy as np
import pandas as pd
from sklearn.preprocessing import LabelEncoder, Imputer, RobustScaler
from sklearn.base import TransformerMixin

import logging
logging.basicConfig(level=logging.INFO,format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("DatasetSanitizer")

class Sanitizer():

    def __init__(self,dataset):
        
        self.dataset = dataset
    
    def replace_missing_values(self):

        # Source: http://stackoverflow.com/questions/8420143/valueerror-could-not-convert-string-to-float-id
        class DataFrameImputer(TransformerMixin):

            def __init__(self):
                pass

            def fit(self, X, y=None):

                self.fill = pd.Series([X[c].value_counts().index[0]
                    if X[c].dtype == np.dtype('O') else X[c].mean() for c in X],
                    index=X.columns)

                return self

            def transform(self, X, y=None):
                return X.fillna(self.fill)


        self.dataset = DataFrameImputer().fit_transform(self.dataset)

        logger.info("Missing values imputed")

    def encode_labels(self):

        encoder = LabelEncoder()

        for column in self.dataset.columns.values:
            if self.dataset[column].dtypes == 'object':

                logger.info("Encoding labels of column "+column)
                
                data = self.dataset[column]
                encoder.fit(data.values)
                self.dataset[column] = encoder.transform(self.dataset[column])
        
        logger.info("Categorical columns encoded")
    
    def int_to_double(self):
        numerics = ['int16', 'int32', 'int64']
        int_col = []
        for column in self.dataset.columns.values:
                if self.dataset[column].dtypes in numerics:
                    int_col.append(column)
        
        self.dataset[int_col] = self.dataset[int_col].astype(float)
        logger.info("Integers converted to float")
                    

    def normalize_features(self):

        scaler = RobustScaler()
        
        numerics = ['float16', 'float32', 'float64']
        columns = []
        for column in self.dataset.columns.values:
                if self.dataset[column].dtypes in numerics:
                    columns.append(column)
                    
        self.dataset[columns] = scaler.fit_transform(self.dataset[columns])
        logger.info("Float numeric features normalized")

    def pipeline(self,tasks):
        """Calls the sanitization methods in the input array in given order

        Parameters
        ----------
        tasks : array
               An array of sanitization methods to perfrom in that order
               Example: ['replace_missing_values','encode_labels','normalize_features']
        Returns
        -------
        val : pandas.Dataframe
            Pandas dataframe after sanitization task has been applied
        
        """
        for task in tasks:
            getattr(self, task)()

        return self.dataset