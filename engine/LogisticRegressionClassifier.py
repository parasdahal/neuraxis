import numpy as np
import random
import math,sys
import datetime
import json
import logging
import pandas as pd
from dataset import sanitizer
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class LogisticRegressionClassifier:
    """
    Classification using logistic regression
    """

    def __init__(self,sc,datasets,parameters):
        """Initializes Class for Logistic Regression
        
        Parameters
        ----------
        table : ndarray(n-rows,m-features + 1)
            Numerical training data, last column as training labels
        reg : Boolean
            Set True to enable regularization, false by default
        degree: int
            Degree of polynomial to fit to the data. Default is 1.
            
        """
        self.parameters = parameters
        self.lamda=self.parameters['regParam']
        self.degree=1
        

        self.dataset = datasets[0]
        selected_features = self.dataset.to_pandas()[self.parameters['feature_col']]
        label = self.dataset.to_pandas()[self.parameters['label_col']]
        self.X = selected_features.as_matrix()
        self.y = label.as_matrix()
        
        self.num_training = np.shape(self.X)[0] 
        self.X = self.map_features()
        
        self.num_features = np.shape(self.X)[1]

        # craete an array of parameters initialzing them to 1
        self.theta = np.zeros(self.num_features)
        
    def map_features(self):
        """
        Generates polynomial features based on the degree
        """
        # First column is ones for calculation of intercept
        return self.X
        
    @staticmethod
    def sigmoid(val):
        """Computes sigmoid function of input value

        Parameters
        ----------
        val : float
              Value of which sigmoid is to calculate
        Returns
        -------
        val : float
            Sigmoid value of the parameter
        
        """
        return float(1) / (1 + np.exp(-val))

    def compute_cost(self):
        """Computes cost based on the current values of the parameters
        
        Returns
        -------
        cost : float
            Cost of the selection of current set of parameters
        
        """
        hypothesis = LogisticRegressionClassifier.sigmoid(np.dot(self.X, self.theta))
        #new ndarray to prevent intercept from theta array to be changed
        theta=np.delete(self.theta,0)
        #regularization term
        reg = (self.lamda/2*self.num_training)*np.sum(np.power(theta,2)) 
        cost = -(np.sum(self.y * np.log(hypothesis) + (1 - self.y) * (np.log(1 - hypothesis)))) / self.num_training
        #if regularization is true, add regularization term and return cost
        return cost + reg

    def train(self):
        """Runs the gradient descent algorithm
        """
        num_iters = self.parameters['num_iters']
        alpha = self.parameters['alpha']
        old_cost=0
        for i in range(0, num_iters):
            hypothesis = LogisticRegressionClassifier.sigmoid(np.dot(self.X, self.theta))
            loss = hypothesis - self.y
            cost = self.compute_cost()
            old_cost = cost
            gradient = np.dot(self.X.T, loss) / self.num_training
            reg = (self.lamda/self.num_training)*self.theta
            self.theta = self.theta - alpha * gradient
        return self.plot()
        

    def predict(self,params):
        
        data = np.asarray(params['features'])
        print(data)
        print(self.theta)
        hypothesis = LogisticRegressionClassifier.sigmoid(np.dot(data, self.theta))
        return str(np.where(hypothesis >= .5, 1, 0))

    def accuracy(self):
        """Calculates percentage of correct predictions by the model on training data
        
        Returns
        -------
        accuracy : float
            Percentage of correct predictions on the features of training data
        """
        #delete extra ones column that was added
        x = np.delete(self.X, 0, 1)
        predicted = self.predict(x)
        match = float(np.sum(self.y == predicted))
        return (match / self.num_training) * 100

    def plot(self):
        """Plot the training data in X array along with decision boundary
        """
        import matplotlib.pyplot as plt, mpld3
        x1 = np.linspace(self.X.min(), self.X.max(), 100)
        #reverse self.theta as it requires coeffs from highest degree to constant term
        x2 = np.polyval(np.poly1d(self.theta[::-1]),x1)
        plt.plot(x1, x2, color='r', label='decision boundary');
        plt.scatter(self.X[:, 1], self.X[:, 2], s=40, c=self.y, cmap=plt.cm.Spectral)
        plt.legend()
        return json.dumps(mpld3.fig_to_dict(plt.figure()))

    def save(self,filename):
        f = open(filename,"w")
        json.dump(self.theta.tolist(),f)
        f.close()
        logger.info("Model saved")

    def load(self,model):
        f = open(model,"r")
        data = json.load(f)
        self.theta = np.array(data)