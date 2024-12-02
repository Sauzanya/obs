-- MySQL dump 10.13  Distrib 8.0.39, for Linux (x86_64)
--
-- Host: localhost    Database: obs_db
-- ------------------------------------------------------
-- Server version	8.0.39-0ubuntu0.22.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `name` varchar(20) NOT NULL,
  `pass` varchar(40) NOT NULL,
  PRIMARY KEY (`name`,`pass`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES ('admin','f865b53623b121fd34ee5426c792e5c33af8c227');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `books`
--

DROP TABLE IF EXISTS `books`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `books` (
  `book_isbn` varchar(20) NOT NULL,
  `book_title` varchar(60) DEFAULT NULL,
  `book_author` varchar(60) DEFAULT NULL,
  `book_image` varchar(40) DEFAULT NULL,
  `book_descr` text,
  `book_price` decimal(6,2) NOT NULL,
  `publisherid` int unsigned NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sales_count` int DEFAULT '0',
  PRIMARY KEY (`book_isbn`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `books`
--

LOCK TABLES `books` WRITE;
/*!40000 ALTER TABLE `books` DISABLE KEYS */;
INSERT INTO `books` VALUES ('978-99933-527-3','Basain','Shankar B. Thapa','basain.jpg','A poignant story of migration, loss, and the emotional struggles faced by people who leave their homes in rural Nepal for better opportunities.',20.00,2,'2024-11-15 00:00:00',0),('978-99933-527-4','Muna Madan','Laxmi Prasad Devkota','muna_madan.jpg','A timeless Nepali classic that portrays the love story of Muna and Madan, and their emotional and cultural journey.',20.00,1,'2024-11-15 00:00:00',0),('978-99933-527-6','Shirishko Phool','Parijat','shirishko_phool.jpg','This is a psychological novel about the struggles and alienation faced by a woman in a patriarchal society in Nepal.',20.00,1,'2024-11-15 00:00:00',0),('978-99933-527-7','Seto Dharti','Jhamak Ghimire','seto_dharti.jpg','Seto Dharti tells the story of a woman’s struggles against the societal norms and her fight for identity and independence in a male-dominated society.',20.00,3,'2024-11-15 00:00:00',0),('978-99933-527-8','Palpasa Cafe','Narayan Wagle','palpasa_cafe.jpg','Palpasa Cafe is a gripping tale set against the backdrop of the Maoist insurgency in Nepal, with love, politics, and personal loss interwoven throughout.',20.00,2,'2024-11-15 00:00:00',0),('978-99933-527-9','Karnali Blues','Shankar Lamichhane','karnali_blues.jpg','A powerful and emotional story about the relationship between a father and son, set in the backdrop of the Karnali region of Nepal.',20.00,1,'2024-11-15 00:00:00',0),('978-99933-528-0','Data Structures and Algorithms','R.K. Gupta','data_structures_algorithms.jpg','This book provides a comprehensive introduction to data structures and algorithms for BCA students.',25.00,6,'2024-11-15 00:00:00',0),('978-99933-528-1','Computer Networks','James Kurose','computer_networks.jpg','This book is an introduction to computer networks, providing detailed coverage of various networking technologies and protocols.',30.00,6,'2024-11-15 00:00:00',0),('978-99933-528-2','Operating System Concepts','Abraham Silberschatz','operating_system_concepts.jpg','A widely used textbook that covers the fundamental concepts of operating systems for BCA students.',28.00,3,'2024-11-15 00:00:00',0),('978-99933-528-4','Software Engineering','I. Sommerville','software_engineering.jpg','A detailed guide to the software engineering process, covering topics such as software development models, project management, and software testing.',35.00,3,'2024-11-15 00:00:00',0),('9789993302100','Basanti','Diamond Shamsher Rana','vasanti.jpg','Basanti is a historical love story based on the novel by Diamond Shamsher.It depicts the story of a girl along with the accounts of the historical events during the time of Junga Bahadur Rana, the first Rana Prime Minister of Nepal.',350.00,3,'2024-11-30 12:12:24',0);
/*!40000 ALTER TABLE `books` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `customers` (
  `customerid` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `address` varchar(80) NOT NULL,
  `contact` varchar(20) NOT NULL,
  PRIMARY KEY (`customerid`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `order_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int unsigned NOT NULL,
  `book_isbn` varchar(20) NOT NULL,
  `book_price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `book_isbn` (`book_isbn`),
  CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`orderid`),
  CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`book_isbn`) REFERENCES `books` (`book_isbn`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_items`
--

LOCK TABLES `order_items` WRITE;
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `orderid` int unsigned NOT NULL AUTO_INCREMENT,
  `customerid` int unsigned NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `payment_method` varchar(50) NOT NULL,
  `status` smallint NOT NULL DEFAULT '0',
  `remarks` varchar(254) DEFAULT NULL,
  PRIMARY KEY (`orderid`),
  KEY `customerid` (`customerid`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customerid`) REFERENCES `customers` (`customerid`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `publisher`
--

DROP TABLE IF EXISTS `publisher`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `publisher` (
  `publisherid` int unsigned NOT NULL AUTO_INCREMENT,
  `publisher_name` varchar(60) NOT NULL,
  PRIMARY KEY (`publisherid`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `publisher`
--

LOCK TABLES `publisher` WRITE;
/*!40000 ALTER TABLE `publisher` DISABLE KEYS */;
INSERT INTO `publisher` VALUES (1,'Publisher One'),(2,'Publisher Two'),(3,'Publisher Three');
/*!40000 ALTER TABLE `publisher` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'obs_db'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-02 14:01:08
