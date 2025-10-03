/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.14-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: StudyPlatform
-- ------------------------------------------------------
-- Server version	10.11.14-MariaDB-0+deb12u2

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `Article`
--

DROP TABLE IF EXISTS `Article`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Article` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `author` int(9) DEFAULT NULL,
  `type` enum('news','lesson') NOT NULL DEFAULT 'news',
  `publishment_date` date NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  CONSTRAINT `Article_ibfk_1` FOREIGN KEY (`author`) REFERENCES `User` (`id`) ON DELETE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Article`
--

LOCK TABLES `Article` WRITE;
/*!40000 ALTER TABLE `Article` DISABLE KEYS */;
INSERT INTO `Article` VALUES
(1,1,'lesson','2025-09-22','Hello world','<p>Quest&#39;oggi vediamo il primo programma:</p>\r\n\r\n<pre>\r\n#include \r\n\r\nint main() {\r\n    printf(&quot;Hello, world!&quot;);\r\n    return 0;\r\n}\r\n</pre>\r\n'),
(2,2,'news','2025-09-23','Mia iscrizione','<p>Sono fiero di essermi iscritto al sito</p>\r\n'),
(3,2,'lesson','2025-09-23','Esempi di strutture dati','<p>Pila</p>\r\n\r\n<p>Coda</p>\r\n\r\n<p>Albero</p>\r\n\r\n<p>Grafo</p>\r\n');
/*!40000 ALTER TABLE `Article` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ArticleTags`
--

DROP TABLE IF EXISTS `ArticleTags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ArticleTags` (
  `article` int(9) NOT NULL,
  `tag` int(9) NOT NULL,
  PRIMARY KEY (`article`,`tag`),
  KEY `tag` (`tag`),
  CONSTRAINT `ArticleTags_ibfk_1` FOREIGN KEY (`article`) REFERENCES `Article` (`id`) ON DELETE CASCADE,
  CONSTRAINT `ArticleTags_ibfk_2` FOREIGN KEY (`tag`) REFERENCES `Tag` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ArticleTags`
--

LOCK TABLES `ArticleTags` WRITE;
/*!40000 ALTER TABLE `ArticleTags` DISABLE KEYS */;
INSERT INTO `ArticleTags` VALUES
(1,1),
(2,2),
(3,3);
/*!40000 ALTER TABLE `ArticleTags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Comment`
--

DROP TABLE IF EXISTS `Comment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Comment` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `article` int(9) DEFAULT NULL,
  `author` int(9) DEFAULT NULL,
  `content` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `author` (`author`),
  KEY `article` (`article`),
  CONSTRAINT `Comment_ibfk_1` FOREIGN KEY (`author`) REFERENCES `User` (`id`) ON DELETE NO ACTION,
  CONSTRAINT `Comment_ibfk_2` FOREIGN KEY (`article`) REFERENCES `Article` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Comment`
--

LOCK TABLES `Comment` WRITE;
/*!40000 ALTER TABLE `Comment` DISABLE KEYS */;
INSERT INTO `Comment` VALUES
(1,3,1,'Bella lezione, mi è piaciuta');
/*!40000 ALTER TABLE `Comment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Lessons`
--

DROP TABLE IF EXISTS `Lessons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Lessons` (
  `subject` int(9) NOT NULL,
  `article` int(9) NOT NULL,
  `nlesson` int(9) NOT NULL,
  PRIMARY KEY (`subject`,`article`),
  UNIQUE KEY `subject` (`subject`,`nlesson`),
  KEY `article` (`article`),
  CONSTRAINT `Lessons_ibfk_1` FOREIGN KEY (`subject`) REFERENCES `Subject` (`id`) ON DELETE CASCADE,
  CONSTRAINT `Lessons_ibfk_2` FOREIGN KEY (`article`) REFERENCES `Article` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Lessons`
--

LOCK TABLES `Lessons` WRITE;
/*!40000 ALTER TABLE `Lessons` DISABLE KEYS */;
INSERT INTO `Lessons` VALUES
(1,1,1),
(2,3,1);
/*!40000 ALTER TABLE `Lessons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `News`
--

DROP TABLE IF EXISTS `News`;
/*!50001 DROP VIEW IF EXISTS `News`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `News` AS SELECT
 1 AS `id`,
  1 AS `author`,
  1 AS `type`,
  1 AS `publishment_date`,
  1 AS `title`,
  1 AS `content` */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `Subject`
--

DROP TABLE IF EXISTS `Subject`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Subject` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  `description` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Subject`
--

LOCK TABLES `Subject` WRITE;
/*!40000 ALTER TABLE `Subject` DISABLE KEYS */;
INSERT INTO `Subject` VALUES
(1,'Linguaggio C','Corso inerente il linguaggio C'),
(2,'Algoritmi e strutture dati','Corso di algoritmi e strutture dati');
/*!40000 ALTER TABLE `Subject` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `Tag`
--

DROP TABLE IF EXISTS `Tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `Tag` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `name` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `Tag`
--

LOCK TABLES `Tag` WRITE;
/*!40000 ALTER TABLE `Tag` DISABLE KEYS */;
INSERT INTO `Tag` VALUES
(1,'Linguaggio C'),
(2,'iscrizione'),
(3,'Strutture dati');
/*!40000 ALTER TABLE `Tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `User` (
  `id` int(9) NOT NULL AUTO_INCREMENT,
  `type` enum('user','admin') NOT NULL DEFAULT 'user',
  `nickname` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `registration_date` date NOT NULL,
  `avatar` tinytext NOT NULL,
  `name` tinytext NOT NULL,
  `surname` tinytext NOT NULL,
  `description` tinytext DEFAULT NULL,
  `birth_date` date NOT NULL,
  `password` tinytext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES
(1,'admin','adgialluisi','adgialluisi@mail.com','2025-09-22','/avatars/default_avatar.png','Antonio','Gialluisi',NULL,'1993-02-09','$2a$10$uo28QljqCScxSJs5/aG/H..u8NPEJXwtMByyMPm1p96rpNmNE4gpy'),
(2,'user','jules','giulio@mail.com','2025-09-23','/avatars/default_avatar.png','Giulio','Cesare','Il grande condottiero romano','1930-03-15','$2a$10$jHg3U6AHcZIwcl7VONIAxORILGAelTbk3fDt2D.SK/V1LsKx1T6GW');
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `News`
--

/*!50001 DROP VIEW IF EXISTS `News`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = latin1 */;
/*!50001 SET character_set_results     = latin1 */;
/*!50001 SET collation_connection      = latin1_swedish_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `News` AS select `Article`.`id` AS `id`,`Article`.`author` AS `author`,`Article`.`type` AS `type`,`Article`.`publishment_date` AS `publishment_date`,`Article`.`title` AS `title`,`Article`.`content` AS `content` from `Article` where `Article`.`type` = 'news' */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-23  0:41:57
