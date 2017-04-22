import pandas as pd
import numpy as np
import re
import logging
import json
logging.basicConfig(level=logging.INFO)
logger = logging.getLogger(__name__)

class SentimentClassifier():

	def __init__(self,sc,datasets,parameters):
		
		logger.info("Starting up the Sentiment Classifier Engine")
		self.sc = sc
		self.dataset = datasets[0]
		self.parameters=parameters

	def getindex(self):
		reviewCol=self.parameters['reviewCol']
		labelCol=self.parameters['labelCol']
		reviewIndex=self.col.index(reviewCol)
		labelIndex=self.col.index(labelCol)
		return reviewIndex,labelIndex 
		

	def loadmodel(self):
		logger.info("Loading training dataset")
		train = self.dataset.to_pandas() #0 - pos 1- neg
		columns = train.columns.values
		self.col=columns.tolist()
		self.rev,self.lab=self.getindex()
		num_reviews = train[columns[self.rev]].size
		for i in range(0,num_reviews):
			filtered = self.sanitize(train[columns[self.rev]][i])
			train.set_value(i,columns[self.rev], filtered)

		reviews,labels= [train[columns[self.rev]]],[train[columns[self.lab]]]
		print(reviews)
		self.freq_tbl = self.create_frequency_table(reviews, labels)
	
	def sanitize(self,text):	
		token=text.split()
		stop_words = ['a','about','above','after','again','against', 'all', 'am', 'an', 'and', 'any', 'are', "aren't", 'as', 'at', 'be', 'because', 'been', 'before', 'being', 'below', 'between', 'both', 'but', 'by', "can't", 'cannot', 'could', "couldn't", 'did', "didn't", 'do', 'does', "doesn't", 'doing', "don't", 'down', 'during', 'each', 'few', 'for', 'from', 'further', 'had', "hadn't", 'has', "hasn't", 'have', "haven't", 'having', 'he', "he'd", "he'll", "he's", 'her', 'here', "here's", 'hers', 'herself', 'him', 'himself', 'his', 'how', "how's", 'i', "i'd", "i'll", "i'm", "i've", 'if', 'in', 'into', 'is', "isn't", 'it', "it's", 'its', 'itself', "let's", 'me', 'more', 'most', "mustn't", 'my', 'myself', 'no', 'nor', 'not', 'of', 'off', 'on', 'once', 'only', 'or', 'other', 'ought', 'our', 'ours', 'ourselves', 'out', 'over', 'own', 'same', "shan't", 'she', "she'd", "she'll", "she's", 'should', "shouldn't", 'so', 'some', 'such', 'than', 'that', "that's", 'the', 'their', 'theirs', 'them', 'themselves', 'then', 'there', "there's", 'these', 'they', "they'd", "they'll", "they're", "they've", 'this', 'those', 'through', 'to', 'too', 'under', 'until', 'up', 'very', 'was', "wasn't", 'we', "we'd", "we'll", "we're", "we've", 'were', "weren't", 'what', "what's", 'when', "when's", 'where', "where's", 'which', 'while', 'who', "who's", 'whom', 'why', "why's", 'with', "won't", 'would', "wouldn't", 'you', "you'd", "you'll", "you're", "you've", 'your', 'yours', 'yourself', 'yorselves']
		html_removed=re.sub(r'<.*?>','',text)
		dots_removed=re.sub(r'[\.\-\_\,]+',' ',html_removed)
		token=dots_removed.split()
		filtered=list()

		for word in token:
			if word  not in stop_words:
				word=re.sub('[^a-zA-Z|0-9]','',word)
				filtered.append(word)
		filtered=filter(None, filtered)

		return filtered

	def create_frequency_table(self,texts, labels=None, parse=False):
		freq_tbl = pd.DataFrame([])
		for idx, t in enumerate(texts):
			print(t)
			vocab = set(t)
			d = pd.Series({ v : t.count(v) for v in vocab})
			if labels != None:
				d['*class*'] = labels[idx]
			freq_tbl = freq_tbl.append(d, ignore_index=True)
		freq_tbl=freq_tbl.fillna(0)
		return freq_tbl.fillna(0)

	def train(self):
		
		logger.info("Starting Training")
		self.loadmodel()
		frequencies = self.freq_tbl.iloc[:, 1:]
		labels = self.freq_tbl.iloc[:, 0]
		vocab = list(frequencies.columns.values)
		l0, l1 = pd.DataFrame([]), pd.DataFrame([])
		for idx, row in frequencies.iterrows():
			if labels[idx] == 1:
				l1 = l1.append(row)
			else:
				l0 = l0.append(row)
		l0_probs, l1_probs = {}, {}
		l0_word_count = sum([word for word in l0.sum()])
		l1_word_count = sum([word for word in l1.sum()])
		alpha = 1
		for word in vocab:
			word_occurences_l0 = int(l0[word].sum())
	        
			word_occurences_l1 = int(l1[word].sum())
			bayesian_prob_l0 = (word_occurences_l0 + alpha) / (l0_word_count + len(vocab))
			bayesian_prob_l1 = (word_occurences_l1 + alpha) / (l1_word_count + len(vocab))
			l0_probs[word], l1_probs[word] = bayesian_prob_l0, bayesian_prob_l1 
			
		prob={}
		prob['l0']=l0_probs
		prob['l1']=l1_probs
		self.prob = prob
		self.save(prob)
		self.l0_probs = l0_probs
		self.l1_probs = l1_probs
		logger.info("Model Built")
		return {}

	def predict(self,params):
		text = params
		l0_pr=self.nb_l0
		l1_pr=self.nb_l1    
		prsd_text=self.sanitize(text)
		txt_table = self.create_frequency_table(texts=prsd_text)
		vocab = txt_table.columns.values
		l0_likelihood = 0
		l1_likelihood = 0
		for wrd in vocab:
			if wrd in l0_pr:
				l0_likelihood += l0_pr[wrd]
			if wrd in l1_pr:
				l1_likelihood += l1_pr[wrd]

		# print 'l0:',l0_likelihood
		# print 'l1:',l1_likelihood

		if l0_likelihood>l1_likelihood:
			res=0 
		else:
			res=1

		result={}
		result['text']=text
		result['prediction']=res
		return json.dumps(result)
		# print op

	def save(self,filename):
		f=open(filename,'w')
		json.dump(self.prob,f)
		f.close()

	def load (self,file):
		json1_file = open(file,"r")
		json1_str = json1_file.read()
		probability=json.loads(json1_str)
		self.nb_l0=probability['l0']
		self.nb_l1=probability['l1']

		