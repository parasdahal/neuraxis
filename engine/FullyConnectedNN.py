import numpy as np
import random
import math
import datetime
from algorithm import Algorithm

import logging
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class CrossEntropyCost:

    @staticmethod
    def fn(a,y):
        return np.sum( np.nan_to_num( -y * np.log(a) - (1-y) * np.log(1-a) ) )
    
    @staticmethod
    def delta(a,y):
        return (a-y)

class FullyConnectedNN(Algorithm):

    def __init__(self, sc, datasets, parameters):
        # TODO: Get datasets here and parallelize them into RDD
        logger.info("Starting up network")
        self.sizes = parameters['sizes']
        self.dataset = datasets[0].dataset
        self.num_layers = len(self.sizes)
        self.parameters = parameters
        self.initialize_weights()
        self.cost = CrossEntropyCost
    
    def initialize_weights(self):
        """Initializing weights as Gaussian random variables with mean
        0 and standard deviation 1/sqrt(n) where n is the number
        of weights connecting to the same neuron.

        """
        self.biases = [np.random.randn(y,1) for y in self.sizes[1:]]
        self.weights = [np.random.randn(y,x)/np.sqrt(x) for x,y in zip(self.sizes[:-1],self.sizes[1:])]

    def feed_forward(self,a):
        
        for b,w in zip(self.biases,self.weights):
            a = self.sigmoid(np.dot(w,a)+b)
        return a
    
    def backprop(self,x,y):
        
        # biases and weights calculated by backprop
        b = [np.zeros(bias.shape) for bias in self.biases]
        w = [np.zeros(weight.shape) for weight in self.weights]
        
        # forward pass
        activation = x
        activations = [x]
        zs = []
        for bias,weight in zip(self.biases,self.weights):
            z = np.dot(weight, activation) + bias
            zs.append(z)
            activation = self.sigmoid(z)
            activations.append(activation)
        # output error
        delta = (self.cost).delta(activations[-1],y)
        b[-1] = delta
        w[-1] = np.dot(delta,activations[-2].transpose())

        # backpropagate
        for l in range(2,self.num_layers):
            z = zs[-l]
            sp = self.sigmoid_prime(z)
            delta = np.dot(self.weights[-l+1].transpose(),delta) * sp
            # store the derrivative terms in the bias and weight list
            b[-l] = delta
            w[-l] = np.dot(delta,activations[-l-1].transpose())
        
        return (b,w)
    
    def gd_mini_batch(self,mini_batch,n):
        """Update the weights and biases of the netwrok by applying
        gradient descent on each mini batch. Mini batch is a list
        of tuple (x,y)

        """
        biases = [np.zeros(b.shape) for b in self.biases]
        weights = [np.zeros(w.shape) for w in self.weights]
        
        for x, y in mini_batch:
            # get derrivative terms using backprop
            # store these deltas and accumulate weights and biases

            # TODO: delta_b,delta_w = mini_batch.map(lambda x,y: self.backprop(x,y))
            delta_b, delta_w = self.backprop(x,y)
            # accumulate the weights and biases
            biases = [nb + db for nb, db in zip(biases,delta_b)]
            weights = [nw + dw for nw, dw in zip(weights,delta_w)]
        
        # update network using gradient descent update rule 
        self.biases = [b - (self.parameters['alpha']/len(mini_batch))*nb 
                        for b, nb in zip(self.biases, biases)]
        self.weights = [(1 - (self.parameters['alpha']*self.parameters['regParam']/n))*w - (self.parameters['alpha']/len(mini_batch))*nw
                        for w,nw in zip(self.weights, weights)]
    
    def train(self,use_validation=False):
        """Train the network using mini-batch stochastic gradient descent

        """
        training_data,validation_data,test_data = self.parse_dataset()
        training_data = training_data[1:10000]
        if(use_validation):
            evaluation_data = validation_data[1:2000]
        else:
            evaluation_data = test_data[1:2000]
        
        n = len(training_data)
        n_data = len(evaluation_data)

        evaluation_cost = []
        evaluation_accuracy = []
        training_cost = []
        training_accuracy = []
        for i in range(self.parameters['epochs']):
            random.shuffle(training_data)
            mini_batches = [training_data[k:(k+self.parameters['mini_batch_size'])]
                for k in range(0,n,self.parameters['mini_batch_size'])]
            logger.info("Epoch "+ str(i) +" training started")
            for mini_batch in mini_batches:
                self.gd_mini_batch(mini_batch,n)
            logger.info("Epoch "+ str(i) +" training complete")
            # training cost and accuracy
            cost = self.total_cost(training_data)
            training_cost.append(cost)
            logger.info("Cost on training data: "+str(cost))
            accuracy = self.accuracy(training_data)
            accuracy_percent = (float(accuracy)*100)/n
            training_accuracy.append(accuracy_percent)
            logger.info("Accuracy on training data: "+str(accuracy)+"/"+str(n)+" ("+str(accuracy_percent)+"%)")
            # evaluation cost and accuracy
            cost = self.total_cost(evaluation_data)
            logger.info("Cost on evaluation data: "+str(cost))
            evaluation_cost.append(cost)
            accuracy = self.accuracy(evaluation_data)
            accuracy_percent = (float(accuracy)*100)/n_data
            evaluation_accuracy.append(accuracy_percent)
            logger.info("Accuracy on evaluation data: "+str(accuracy)+"/"+str(n_data)+" ("+str(accuracy_percent)+"%)")
        
        self.plot(evaluation_cost,evaluation_accuracy,training_cost,training_accuracy)

    def accuracy(self,data):
        """Returns the number of input in data for which neural network 
        outputs the correct result.
        """
        results = [(np.argmax(self.feed_forward(x)),np.argmax(y)) for(x, y) in data]
        return sum( int(x == y) for(x,y) in results)

    def total_cost(self,data):
        """Return the total cost of the network for dataset
        """
        cost = 0.0
        for x, y in data:
            a = self.feed_forward(x)
            cost += self.cost.fn(a,y)/len(data)
        # add regularization
        cost += 0.5*(self.parameters['regParam']/len(data))*sum( np.linalg.norm(w)**2 for w in self.weights )
        return cost

    def vector_result(self,j):
        """Convert output value into network output vector
        """
        vec = np.zeros((self.sizes[-1],1))
        vec[j] = 1.0
        return vec
    
    def parse_dataset(self):

        tr_d, va_d, te_d = self.dataset
        
        training_inputs = [np.reshape(x, (784, 1)) for x in tr_d[0]]
        training_results = [self.vector_result(y) for y in tr_d[1]]
        training_data = zip(training_inputs, training_results)
        
        validation_inputs = [np.reshape(x, (784, 1)) for x in va_d[0]]
        validation_results = [self.vector_result(y) for y in va_d[1]]
        validation_data = zip(validation_inputs, validation_results)
        
        test_inputs = [np.reshape(x, (784, 1)) for x in te_d[0]]
        test_results = [self.vector_result(y) for y in te_d[1]]
        test_data = zip(test_inputs, test_results)
        
        return (list(training_data), list(validation_data), list(test_data))
    
    def sigmoid(self,z):
        return 1.0/(1.0+np.exp(-z))
    
    def sigmoid_prime(self,z):
        return self.sigmoid(z)*(1-self.sigmoid(z))
    
    def plot(self,evaluation_cost,evaluation_accuracy,training_cost,training_accuracy):
        
        import matplotlib.pyplot as plt, mpld3
        from matplotlib.ticker import MaxNLocator

        train_cost,eval_cost = [],[]
        train_acc,eval_acc = [],[]
        for i,cost in enumerate(training_cost):
            train_cost.append((cost,i))
        for i,cost in enumerate(evaluation_cost):
            eval_cost.append((cost,i))
        for i,acc in enumerate(training_accuracy):
            train_acc.append((acc,i))
        for i,acc in enumerate(evaluation_accuracy):
            eval_acc.append((acc,i))
        
        np_train_cost = np.asarray(train_cost)
        np_eval_cost = np.asarray(eval_cost)
        np_train_acc = np.asarray(train_acc)
        np_eval_acc = np.asarray(eval_acc)

        plt.subplot(221)
        plt.plot(np_train_cost[:,1],np_train_cost[:,0],linewidth=2)
        ax = plt.gca()
        ax.xaxis.set_major_locator(MaxNLocator(integer=True))
        plt.title("Cost on training data")
        plt.xlabel("No of epochs")
        plt.ylabel("Cost")
        plt.subplot(222)
        plt.plot(np_eval_cost[:,1],np_eval_cost[:,0],linewidth=2)
        ax = plt.gca()
        ax.xaxis.set_major_locator(MaxNLocator(integer=True))
        plt.title("Cost on evaluation data")
        plt.xlabel("No of epochs")
        plt.ylabel("Cost")
        plt.subplot(223)
        plt.plot(np_train_acc[:,1],np_train_acc[:,0],linewidth=2)
        plt.title("Accuracy on training data")
        ax = plt.gca()
        ax.xaxis.set_major_locator(MaxNLocator(integer=True))
        ax.set_ylim([80,100])
        plt.xlabel("No of epochs")
        plt.ylabel("Accuracy")
        plt.subplot(224)
        plt.plot(np_eval_acc[:,1],np_eval_acc[:,0],linewidth=2)
        plt.title("Accuracy on evaluation data")
        ax = plt.gca()
        ax.xaxis.set_major_locator(MaxNLocator(integer=True))
        ax.set_ylim([80,100])
        plt.xlabel("No of epochs")
        plt.ylabel("Accuracy")
        plt.tight_layout()
        mpld3.show()





