import os
import numpy as np
import pandas as pd
import _pickle as cPickle
import gzip

import logging
logging.basicConfig(level=logging.INFO,format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("Dataset")

class Dataset:
     
    def load_from_csv(self,path):

        self.dataset = pd.read_csv(path)
        logger.info("Dataset loaded from "+path)
        
    def load_from_tsv(self,path):

        self.dataset = pd.read_csv(path, delimiter="\t", quoting=3)
        logger.info("Dataset loaded from "+path)
    
    def load_from_pandas(self,dataframe):

        self.dataset = dataframe
        logger.info("Dataset loaded")
    
    def load_from_pkl(self,path):
        f = gzip.open(path,'rb')
        training_data, validation_data, test_data = cPickle.load(f,encoding='iso-8859-1')
        f.close()
        self.dataset = (training_data, validation_data, test_data)
        logger.info("Dataset loaded from "+path)

    def save_to_csv(self,path):

        self.dataset.to_csv(path)

    def to_pandas(self):

        return self.dataset
    
    def to_numpy(self):
        
        return self.dataset.as_matrix()
        
# if __name__ == "__main__":

#     from Sanitizer import Sanitizer
#     from Schema import Schema
    
#     d = Dataset()

#     d.load_from_csv("datasets/sample.csv")

#     schema = Schema.make_schema(d.to_pandas())

#     valid = Schema.validate(schema,schema)
#     print(valid)   

#     print(d.test_set(numpy=True))
    
#     sanitizer = Sanitizer(d.to_pandas())
    
#     df = sanitizer.pipeline(['replace_missing_values','encode_labels','normalize_features'])
    
#     d.load_from_pandas(df)

#     # print(d.to_numpy())

#     print(d.to_pandas())