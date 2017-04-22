-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 26, 2017 at 12:15 PM
-- Server version: 5.6.20
-- PHP Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `neuraxis`
--

-- --------------------------------------------------------

--
-- Table structure for table `instance`
--

CREATE TABLE IF NOT EXISTS `instance` (
`id` int(11) NOT NULL,
  `user_id` int(100) NOT NULL,
  `name` varchar(255) NOT NULL,
  `config` longtext NOT NULL,
  `state` varchar(255) NOT NULL,
  `pid` int(11) NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `instance`
--

INSERT INTO `instance` (`id`, `user_id`, `name`, `config`, `state`, `pid`, `created_date`) VALUES
(1, 1, 'nn_1', '{	"algorithm": "FullyConnectedNN",		"parameters" : {		"sizes":[784,256,10],		"mini_batch_size":10,		"alpha":0.1,		"regParam":10.0,		"epochs":5	},		"datasets": [{			"name": "training",			"path": "mnist.pkl.gz",			"sanitizer": [],			"type": "pkl"		}	],		"routes": [{		"name": "predict",		"route": "/predict"	}]}', 'STOPPED', 0, '2017-03-24 00:00:00'),
(2, 1, 'clustering_1', '{\r\n	"algorithm": "Clustering",\r\n	\r\n	"parameters" : {\r\n		"k":3,\r\n		"feature_col":["orig_destination_distance","user_location_city","is_mobile","is_package","srch_adults_cnt","srch_children_cnt","srch_rm_cnt"]\r\n	},\r\n	\r\n	"datasets": [{\r\n			"name": "data",\r\n			"path": "hotel.csv",\r\n			"sanitizer": ["replace_missing_values","encode_labels","int_to_double"],\r\n			"type": "csv"\r\n		}\r\n	],\r\n	\r\n	"routes": [{\r\n		"name": "predict",\r\n		"route": "/predict"\r\n	}]\r\n\r\n}', 'STOPPED', 0, '2017-03-01 00:00:00'),
(3, 1, 'recommendation_1', '{\r\n	"algorithm" : "Recommendation",\r\n	"parameters" : {\r\n			"userCol":"userId",\r\n			"itemCol":"movieId",\r\n			"ratingCol":"rating"\r\n	},\r\n	"datasets": [{\r\n			"name": "ratings",\r\n			"path": "ratings.csv",\r\n			"type": "csv"\r\n		}, {\r\n			"name": "meta",\r\n			"path": "movies.csv",\r\n			"type": "csv"\r\n		}\r\n\r\n	],\r\n	"routes": [{\r\n		"name": "top_recommendation",\r\n		"route": "/best"\r\n	}]\r\n\r\n}', 'STOPPED', 0, '2017-03-15 00:00:00'),
(4, 0, 'lr_1', '{\r\n	"algorithm": "LogisticRegressionClassifier",\r\n	\r\n	"parameters" : {\r\n		"feature_col":["Pclass","Age","SibSp","Parch","Fare"],\r\n		"label_col":"Survived",\r\n		"regParam":10.0,\r\n		"alpha":1.0,\r\n		"num_iters":1000\r\n	},\r\n	\r\n	"datasets": [{\r\n			"name": "training",\r\n			"path": "titanic.csv",\r\n			"sanitizer": ["replace_missing_values","encode_labels","int_to_double"],\r\n			"type": "csv"\r\n		}\r\n	],\r\n	\r\n	"routes": [{\r\n		"name": "predict",\r\n		"route": "/predict"\r\n	}]\r\n\r\n}', 'STOPPED', 0, '2017-03-15 00:00:00'),
(5, 1, 'rcom1', '{"algorithm":"Recommendation","parameters":{"userCol":"userId","itemCol":"movieId","ratingCol":"rating","metaIdCol":"movieId","metaTitleCol":"title"},"datasets":[{"name":"ratings","path":"1_rec_rat_rcom1.csv"},{"name":"meta","path":"1_rec_meta_rcom1.csv"}],"routes":{"name":"top_recommendation","route":"/best"}}', 'STOPPED', 0, '2017-03-26 13:16:22'),
(6, 1, 'sen_1', '{"algorithm":"SentimentClassifier","parameters":{"reviewCol":"review","labelCol":"label"},"datasets":[{"name":"training","path":"1_sen_sen_1.tsv"}],"routes":{"name":"predict","route":"/predict"}}', 'STOPPED', 0, '2017-03-26 13:28:04'),
(7, 1, 'log_1', '{"algorithm":"LogisticRegressionClassifier","parameters":{"feature_col":["Name ","Age ","Parch ","Fare "],"label_col":"Pclass","regParam":"1","alpha":"1","num_iters":"1"},"datasets":[{"name":"training","path":"1_log_log_1.csv","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"csv"}],"routes":{"name":"predict","route":"/best"}}', 'STOPPED', 0, '2017-03-26 13:29:01'),
(8, 1, 'nn_11', '{"algorithm":"FullyConnectedNN","parameters":{"sizes":[2,3,4],"mini_batch_size":"1","alpha":"1","regParam":"1","epoches":"1"},"datasets":[{"name":"training","path":"1_neural_nn_11.gz","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"pkl"}],"routes":{"name":"predict","route":"/best"}}', 'STOPPED', 0, '2017-03-26 13:30:09'),
(9, 1, 's', '{"algorithm":"Clustering","parameters":{"k":"2","feature_col":["date_time ","user_location_country ","user_location_region "]},"datasets":[{"name":"data","path":"1_cluster_s.csv","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"csv"}],"routes":{"name":"predict","route":"s"}}', 'STOPPED', 0, '2017-03-26 13:53:32'),
(10, 1, 'new', '{"algorithm":"Recommendation","parameters":{"userCol":"userId","itemCol":"userId","ratingCol":"userId","metaIdCol":"movieId","metaTitleCol":"movieId"},"datasets":[{"name":"ratings","path":"1_rec_rat_new.csv"},{"name":"meta","path":"1_rec_meta_new.csv"}],"routes":{"name":"top_recommendation","route":"new"}}', 'STOPPED', 0, '2017-03-26 14:10:16'),
(11, 1, 'sas', '{"algorithm":"Clustering","parameters":{"k":"1","feature_col":["channel ","srch_co "]},"datasets":[{"name":"data","path":"1_cluster_sas.csv","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"csv"}],"routes":{"name":"predict","route":"as"}}', 'STOPPED', 0, '2017-03-26 14:46:00'),
(12, 1, 'xc', '{"algorithm":"Clustering","parameters":{"k":"1","feature_col":["srch_co ","srch_adults_cnt "]},"datasets":[{"name":"data","path":"1_cluster_xc.csv","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"csv"}],"routes":{"name":"predict","route":"zx"}}', 'STOPPED', 0, '2017-03-26 14:50:26'),
(13, 1, 'sen', '{"algorithm":"SentimentClassifier","parameters":{"reviewCol":"id","labelCol":"id"},"datasets":[{"name":"training","path":"1_sen_sen.tsv"}],"routes":{"name":"predict","route":"/best"}}', 'STOPPED', 0, '2017-03-26 14:54:04'),
(14, 1, 'as', '{"algorithm":"Recommendation","parameters":{"userCol":"userId","itemCol":"userId","ratingCol":"userId","metaIdCol":"movieId","metaTitleCol":"movieId"},"datasets":[{"name":"ratings","path":"1_rec_rat_as.csv"},{"name":"meta","path":"1_rec_meta_as.csv"}],"routes":{"name":"top_recommendation","route":"as"}}', 'STOPPED', 0, '2017-03-26 14:54:45'),
(17, 1, 's', '{"algorithm":"FullyConnectedNN","parameters":{"sizes":[1,2,3],"mini_batch_size":"1","alpha":"1","regParam":"1","epoches":"1"},"datasets":[{"name":"training","path":"1_neural_s.gz","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"pkl"}],"routes":{"name":"predict","route":"s"}}', 'STOPPED', 0, '2017-03-26 14:58:12'),
(18, 1, 'nn11', '{"algorithm":"FullyConnectedNN","parameters":{"sizes":[784,1562,10],"mini_batch_size":"10","alpha":"0.1","regParam":"0.1","epoches":"50"},"datasets":[{"name":"training","path":"1_neural_nn11.gz","sanitizer":["encode_labels","int_to_double","normalize_features"],"type":"pkl"}],"routes":{"name":"predict","route":"/predict"}}', 'STOPPED', 0, '2017-03-26 15:03:14'),
(19, 1, 'asas', '{"algorithm":"Recommendation","parameters":{"userCol":"userId","itemCol":"userId","ratingCol":"userId","metaIdCol":"movieId","metaTitleCol":"movieId"},"datasets":[{"name":"ratings","path":"1_rec_rat_asas.csv"},{"name":"meta","path":"1_rec_meta_asas.csv"}],"routes":{"name":"top_recommendation","route":"asas"}}', 'STOPPED', 0, '2017-03-26 15:36:32'),
(20, 1, 'asa', '{"algorithm":"Recommendation","parameters":{"userCol":"userId","itemCol":"userId","ratingCol":"userId","metaIdCol":"movieId","metaTitleCol":"movieId"},"datasets":[{"name":"ratings","path":"1_rec_rat_asa.csv"},{"name":"meta","path":"1_rec_meta_asa.csv"}],"routes":{"name":"top_recommendation","route":"as"}}', 'STOPPED', 0, '2017-03-26 15:41:09');

-- --------------------------------------------------------

--
-- Table structure for table `trainedmodel`
--

CREATE TABLE IF NOT EXISTS `trainedmodel` (
`id` int(11) NOT NULL,
  `instance_id` int(11) NOT NULL,
  `path` varchar(255) NOT NULL,
  `visualization` longtext NOT NULL,
  `created_date` datetime NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Dumping data for table `trainedmodel`
--

INSERT INTO `trainedmodel` (`id`, `instance_id`, `path`, `visualization`, `created_date`) VALUES
(5, 1, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\nn_1_2017-03-25_12-31-46.json', '{"id": "el77684681739680", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 12:32:33'),
(6, 1, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\nn_1_2017-03-25_12-36-43.json', '{"id": "el6660176276418008", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 12:37:37'),
(7, 2, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\clustering_1_2017-03-25_13-10-18.json', '{"id": "el3200933284182504", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 13:12:47'),
(8, 3, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\recommendation_1_2017-03-25_14-21-20.json', '{}', '2017-03-25 14:21:57'),
(9, 4, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\lr_1_2017-03-25_16-02-27.json', '{"id": "el24448279564472", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 16:02:37'),
(10, 4, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\lr_1_2017-03-25_16-38-42.json', '{"id": "el8636644118945976", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 16:38:51'),
(11, 1, 'C:/Spark/spark/bin/neuraxis\\storage\\models\\nn_1_2017-03-25_19-11-38.json', '{"id": "el11564235765594096", "data": {}, "axes": [], "height": 480.0, "plugins": [{"type": "reset"}, {"button": true, "type": "zoom", "enabled": false}, {"button": true, "type": "boxzoom", "enabled": false}], "width": 640.0}', '2017-03-25 19:14:27');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(100) NOT NULL,
  `name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(1, 'Sushant Adhikari', 'sushantadh@gmail.com', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'Paras', 'paras@gmail.com', '202cb962ac59075b964b07152d234b70');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `instance`
--
ALTER TABLE `instance`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trainedmodel`
--
ALTER TABLE `trainedmodel`
 ADD PRIMARY KEY (`id`), ADD KEY `trainedmodel_instance_id` (`instance_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `instance`
--
ALTER TABLE `instance`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `trainedmodel`
--
ALTER TABLE `trainedmodel`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(100) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `trainedmodel`
--
ALTER TABLE `trainedmodel`
ADD CONSTRAINT `trainedmodel_ibfk_1` FOREIGN KEY (`instance_id`) REFERENCES `instance` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
