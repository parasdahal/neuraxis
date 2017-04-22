import numpy as np
import logging
import json
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class Clustering():
    
    def __init__(self, sc, datasets, parameters):
        
        logger.info("Starting up the clustering Engine")
        self.sc = sc
        self.parameters = parameters
        logger.info("Loading Traning data")
        training = datasets[0]
        selected_features = training.to_pandas()[self.parameters['feature_col']]
        self.training = selected_features.as_matrix()
    
    def train(self):
        logger.info("Training the Clustering model")
        
        data = self.training
        k = self.parameters['k']
        centroids = []
        centroids = self.randomize_centroids(data, centroids, k)  
        
        old_centroids = [[] for i in range(k)] 
        iterations = 0
        
        while not (self.has_converged(centroids, old_centroids, iterations)):
            iterations += 1
            clusters = [[] for i in range(k)]
            # assign data points to clusters
            clusters = self.euclidean_dist(data, centroids, clusters)
            # recalculate centroids
            index = 0
            for cluster in clusters:
                old_centroids[index] = centroids[index]
                centroids[index] = np.mean(cluster, axis=0).tolist()
                index += 1
        self.centroids = centroids
        self.clusters = clusters
        logger.info("The total number of data instances is: " + str(len(data)))
        logger.info("The total number of iterations necessary is: " + str(iterations))
        logger.info("The means of each cluster are: " + str(centroids))
        logger.info("Number of clusters: " + str(len(clusters)))
        return self.plot()

    def euclidean_dist(self,data, centroids, clusters):
        for instance in data:  
            # Find which centroid is the closest
            # to the given data point.
            mu_index = min([(i[0], np.linalg.norm(instance-centroids[i[0]])) \
                                for i in enumerate(centroids)], key=lambda t:t[1])[0]
            try:
                clusters[mu_index].append(instance)
            except KeyError:
                clusters[mu_index] = [instance]

        # If any cluster is empty then assign one point
        # from data set randomly so as to not have empty
        # clusters and 0 means.        
        for cluster in clusters:
            if not cluster:
                cluster.append(data[np.random.randint(0, len(data), size=1)].flatten().tolist())

        return clusters
    
    def randomize_centroids(self,data, centroids, k):
        for cluster in range(0, k):
            centroids.append(data[np.random.randint(0, len(data), size=1)].flatten().tolist())
        return centroids

    def has_converged(self,centroids, old_centroids, iterations):
        MAX_ITERATIONS = 1000
        if iterations > MAX_ITERATIONS:
            return True
        return old_centroids == centroids
    
    def predict(self,params):
        X =np.asarray(params['data'])
        distances = {}
        for i,centroid in enumerate(self.centroids):
            distances[i] = np.linalg.norm(X-centroid)
        return min(distances,key=distances.get)
    
    def plot(self,x=0,y=1):
        import matplotlib.pyplot as plt, mpld3
        points, label = self.clusterize()
        plt.title("Clusters")
        plt.xlabel(self.parameters['feature_col'][x])
        plt.ylabel(self.parameters['feature_col'][y])
        plt.scatter(points[:,x], points[:,y],c=label,s=10)
        return mpld3.fig_to_html(plt.figure(),'/assets/d3.js','/assets/mpld3.js')
    
    def clusterize(self):
        points = []
        label = []
        for i,cluster in enumerate(self.clusters):
            for data in cluster:
                points.append(data)
                label.append(i)
        return np.asarray(points),np.asarray(label)

    def save(self,filename):
        f = open(filename,"w")
        json.dump(self.centroids,f)
        f.close()
        logger.info("Model saved")

    def load(self,model):
        f = open(model,"r")
        data = json.load(f)
        self.centroids = data