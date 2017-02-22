from abc import ABCMeta,abstractmethod

class Algorithm(metaclass = ABCMeta):
    
    @abstractmethod
    def __init__(self,sc,datasets):
        pass
    
    @abstractmethod
    def train(self):
        pass

    def save_model(self):
        pass
        
    def load_model(self):
        pass