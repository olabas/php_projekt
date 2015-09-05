-- MySQL dump 10.13  Distrib 5.5.43, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: php_projekt
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Matematyka'),(2,'J?zyk polski'),(3,'J?zyk angielski'),(4,'Historia'),(5,'Fizyka'),(6,'Chemia'),(7,'Geografia'),(8,'J?zyk niemiecki'),(9,'Wiedza o spo?ecze?stwie'),(10,'Biologia');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cities` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
INSERT INTO `cities` VALUES (1,'Krakow'),(2,'Katowice'),(3,'Warszawa');
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comments`
--

DROP TABLE IF EXISTS `comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comments` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `content` varchar(255) COLLATE utf8_bin NOT NULL,
  `comment_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_comments_1` (`post_id`),
  KEY `FK_comments_2` (`user_id`),
  CONSTRAINT `FK_comments_1` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_comments_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comments`
--

LOCK TABLES `comments` WRITE;
/*!40000 ALTER TABLE `comments` DISABLE KEYS */;
INSERT INTO `comments` VALUES (1,10,11,'Content','0000-00-00 00:00:00'),(2,10,11,'Content','0000-00-00 00:00:00'),(3,10,11,'Content','2015-08-22 00:00:00'),(4,10,11,'joÅ‚ ziomale','2015-08-22 00:00:00'),(5,10,8,'Contentjj','2015-08-24 14:05:01'),(6,10,8,'Contentjj','2015-08-24 14:29:32'),(7,10,8,'Contentjj','2015-08-24 14:29:34'),(8,10,8,'Contentjj','2015-08-24 14:29:36'),(9,10,8,'Contentjj','2015-08-24 14:32:44'),(10,10,8,'Contentjj','2015-08-24 14:37:00'),(11,10,8,'Contentjj','2015-08-24 14:38:03'),(12,10,8,'Contentjj','2015-08-24 14:38:08'),(13,10,8,'Contentjj','2015-08-24 14:41:13'),(14,12,8,'Contentgjjhg','2015-08-25 01:25:14'),(15,15,8,'Zapraszam ! W sierpniu zniżka 15% !','2015-08-28 11:27:56'),(16,17,8,'komentarz','2015-09-03 21:14:50');
/*!40000 ALTER TABLE `comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(60) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `date` datetime NOT NULL,
  `is_read` int(11) NOT NULL,
  `sender_id` int(10) unsigned NOT NULL,
  `recipient_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_messages_1` (`sender_id`),
  KEY `FK_messages_2` (`recipient_id`),
  CONSTRAINT `FK_messages_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`),
  CONSTRAINT `FK_messages_2` FOREIGN KEY (`recipient_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,'bla','blabla','2015-08-21 23:15:39',1,11,10),(2,'fjsh nviufshbvoudis','fuihnvaefhvbuijfsbaoier','0000-00-00 00:00:00',0,8,10),(3,'fvnsejnvwjorfnd','fhnvuijahevuihbreauignvuwi','2015-08-21 23:33:56',0,8,10),(4,'Siema!','PiszÄ™ do Ciebie w sprawie jamnikow','2015-08-21 23:39:14',0,8,10),(5,'Korepetycje','Sprawdzam czy dziala','2015-08-28 11:28:19',1,8,8),(6,'dfghj','fghjkl;kjhg','2015-08-28 15:14:09',0,8,1),(7,'Wiadomość','Treść wiadomości','2015-08-28 15:17:55',1,8,8),(8,'Wiadomość','Treść wiadomości','2015-08-28 15:17:55',1,8,8),(9,'kupa bardzo duża','gówna','2015-08-31 02:36:34',1,8,8),(10,'wysylam wiadomosc','wiaodmosc','2015-09-03 21:15:27',1,8,8),(11,'odpowiadam','dfghjyujkl','2015-09-03 21:15:56',0,8,8);
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `city_id` int(10) unsigned NOT NULL,
  `category_id` int(10) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  `title` char(45) COLLATE utf8_bin NOT NULL,
  `content` text COLLATE utf8_bin NOT NULL,
  `post_date` datetime DEFAULT NULL,
  `price` int(10) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_posts_2` (`city_id`),
  KEY `FK_posts_3` (`category_id`),
  KEY `FK_posts_4` (`user_id`),
  CONSTRAINT `FK_posts_2` FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`),
  CONSTRAINT `FK_posts_3` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  CONSTRAINT `FK_posts_4` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (10,1,1,1,'Matematyka - korepetycje','Witam! Jestem studentem matematyki na UJ, ch?tnie udziel? korepetycji, pomog? w przygotowaniu do matury! Zapraszam!','2015-12-03 00:00:00',20),(11,2,2,1,'J. Polski - korepetycje','Witam! Jestem studentem filologii polskiej na UJ, ch?tnie udziel? korepetycji, pomog? w przygotowaniu do matury! Zapraszam!','2015-08-03 00:00:00',25),(12,2,1,8,'Korepetycje - MATEMATYKA','kąpa','2015-08-20 00:00:00',20),(13,1,1,11,'Titlesdfghjkl','Contentasdfghjkdfghj','2015-08-21 22:31:51',20),(14,1,1,11,'Titlesdfghjkl','Contentasdfghjkdfghj','2015-08-21 22:31:56',20),(15,2,3,8,'POMOC  ANGIELSKI','Witam!\r\nChetnie udziele korepetycji z jezyka angielskiego. Pomogę w przygotowaniach do matury jak i w codziennych lekcjach. Chetnie dojadę do ucznia.','2015-08-28 11:27:22',20),(16,2,1,8,'Kraków joł joł','kąpa joł','2015-09-02 20:47:40',20),(17,1,1,8,'Korepetycje','kąpać się','2015-09-02 21:04:20',20),(18,1,1,8,'Kraków joł joł','kąpa joł joł joł','2015-09-02 21:04:54',20),(19,1,1,8,'Matematyka !!!','blebleble','2015-09-02 21:08:27',20),(20,1,1,8,'Oby edytowało','Witam! Jestem studentem matematyki na UJ, ch?tnie udziel? korepetycji, pomog? w przygotowaniu do matury! Zapraszam!','2015-09-02 21:11:18',20),(21,1,1,8,'Matematyka -','Witam! Jestem studentem matematyki na UJ, chętnie udziel? korepetycji, pomog? w przygotowaniu do matury! Zapraszam!','2015-09-03 21:17:27',20);
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(32) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'ROLE_ADMIN'),(2,'ROLE_USER'),(3,'ROLE_MOD');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL,
  `login` varchar(32) COLLATE utf8_bin NOT NULL,
  `password` varchar(128) COLLATE utf8_bin NOT NULL,
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `surname` varchar(45) COLLATE utf8_bin NOT NULL,
  `address` varchar(500) COLLATE utf8_bin NOT NULL,
  `email` varchar(128) COLLATE utf8_bin NOT NULL,
  `phone_number` varchar(30) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_users_1` (`role_id`),
  CONSTRAINT `FK_users_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,2,'admin','nhDr7OyKlXQju+Ge/WKGrPQ9lPBSUFfpK+B1xqx/+8zLZqRNX0+5G1zBQklXUFy86lCpkAofsExlXiorUcKSNQ==','','','','',''),(8,1,'reksio','yu6AfgZckO0D9eCigkbj5yfggSMpEBMP9djyRIJzfBATDGhQsjzCtLIYaxy5m7oXV/v6pfodpFO0DzT5k4ZPwg==','Reksio','Pies','ulica Reksiowa 2/45 31-369 Reksiowo','reksio@buda.pl','123456789'),(9,1,'moddsda','7jaUtgipmk4cu1QHv5kOq70m9r9LNX3+tqet+ShzbpYphj7RAkVE1WPsq3IOFie7QxuNs2Gx+30eYOH9GBl5Aw==','dsadasdadas','dasdadasd','dsadadada','dsadadada','123456789'),(10,1,'maciekb','lnWp380upcQoaTJjrlpeTP8DFOpN+c6gGOUDbfyGVdyKZj8Zw3NBi0PMC7BfKRMwY08D6J18pTEA46iE6GQZVA==','mac','mac','123456789','123456789','123456789'),(11,1,'kotekkoteczek','7jaUtgipmk4cu1QHv5kOq70m9r9LNX3+tqet+ShzbpYphj7RAkVE1WPsq3IOFie7QxuNs2Gx+30eYOH9GBl5Aw==','Kotek','Koteczek','kotekkoteczek 2','kotek@koteczek.com','123456789');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-09-03 21:45:44
