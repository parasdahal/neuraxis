from engine import FullyConnectedNN
from engine import mnist_loader
network = FullyConnectedNN.FullyConnectedNN([784,256,10])
training_data, validation_data, test_data = mnist_loader.load_data_wrapper()
network.SGD(training_data,5,10,2.0,2.0,test_data)