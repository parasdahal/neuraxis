import numpy as np
import pandas as pd

import logging
logging.basicConfig(level=logging.INFO,format='%(asctime)s - %(name)s - %(levelname)s - %(message)s')
logger = logging.getLogger("Schema")

class Schema:

    @staticmethod
    def make_schema(dataset):

        # TODO: if label==True, last column is label and exclude it in features array
        
        schema = {
            'column_num' : dataset.shape[1],
            'row_num': dataset.shape[0] -1,
            'features' : [],
            'label': ''
        }
        
        integers = ['int16', 'int32', 'int64']
        floats = ['float16', 'float32', 'float64']

        for column in dataset.columns.values:

            if dataset[column].dtypes in integers:
                col_data = {
                    'type':'int',
                }
                schema['features'].append({column:col_data}) 
            
            elif dataset[column].dtypes in floats:
                col_data = {
                    'type':'float',
                }
                schema['features'].append({column:col_data}) 
                
            else:
                col_data = {
                    'type':'string'
                }
                schema['features'].append({column:col_data}) 
        
        return schema



    @staticmethod
    def validate(schema, other_schema, num_col=True, num_row=False):

        if(num_col == True and schema['column_num'] != other_schema['column_num']):
            
            logger.critical("Expected columns "+schema['column_num']+" but recieved "+other_schema['column_num'])
            return False

        if(num_row == True and schema['row_num'] != other_schema['row_num']):
            
            logger.critical("Expected rows "+schema['row_num']+" but recieved "+other_schema['row_num'])
            return False
        
        for feature,self_feature in zip(schema['features'],other_schema['features']):
            
            other_feature = [f for f in feature.values()][0]
            own_feature = [f for f in self_feature.values()][0]

            if other_feature['type'] != own_feature['type']:
                
                logger.critical("Expected "+ other_feature['type']+ " but given "+ own_feature['type'])
                return False
        
        return True