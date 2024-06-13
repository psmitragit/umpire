-- MySQL dump 10.13  Distrib 8.3.0, for Win64 (x86_64)
--
-- Host: localhost    Database: umpire_demo
-- ------------------------------------------------------
-- Server version	8.3.0

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
-- Table structure for table `absent_report`
--

DROP TABLE IF EXISTS `absent_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `absent_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `umpid` int NOT NULL,
  `report_col` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `absent_report`
--

LOCK TABLES `absent_report` WRITE;
/*!40000 ALTER TABLE `absent_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `absent_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `apply_to_league`
--

DROP TABLE IF EXISTS `apply_to_league`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `apply_to_league` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `leagueid` int NOT NULL,
  `status` int NOT NULL COMMENT '0=pending,1=approved,2=denied,3=interview',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `apply_to_league`
--

LOCK TABLES `apply_to_league` WRITE;
/*!40000 ALTER TABLE `apply_to_league` DISABLE KEYS */;
/*!40000 ALTER TABLE `apply_to_league` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockdates`
--

DROP TABLE IF EXISTS `blockdates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blockdates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `blockdate` date NOT NULL,
  `blocktime` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Unavailable "time" to be kept as "13:00,14:00,17:00,18:00".\\r\\nEmpty "time" means unavailable for full day.',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blockdates_fk0` (`umpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockdates`
--

LOCK TABLES `blockdates` WRITE;
/*!40000 ALTER TABLE `blockdates` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockdates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockdivision`
--

DROP TABLE IF EXISTS `blockdivision`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blockdivision` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `divid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockdivision`
--

LOCK TABLES `blockdivision` WRITE;
/*!40000 ALTER TABLE `blockdivision` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockdivision` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blocklocations`
--

DROP TABLE IF EXISTS `blocklocations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blocklocations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `locid` int NOT NULL,
  `leagueid` int NOT NULL COMMENT 'leagueid = 0 (umpire has blacklisted the ground)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blocklocations`
--

LOCK TABLES `blocklocations` WRITE;
/*!40000 ALTER TABLE `blocklocations` DISABLE KEYS */;
/*!40000 ALTER TABLE `blocklocations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockteams`
--

DROP TABLE IF EXISTS `blockteams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blockteams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `teamid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockteams`
--

LOCK TABLES `blockteams` WRITE;
/*!40000 ALTER TABLE `blockteams` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockteams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blockumpires`
--

DROP TABLE IF EXISTS `blockumpires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `blockumpires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blockumpires`
--

LOCK TABLES `blockumpires` WRITE;
/*!40000 ALTER TABLE `blockumpires` DISABLE KEYS */;
/*!40000 ALTER TABLE `blockumpires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gamereports`
--

DROP TABLE IF EXISTS `gamereports`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gamereports` (
  `grid` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `umpid` int NOT NULL,
  `rqid` int NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`grid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gamereports`
--

LOCK TABLES `gamereports` WRITE;
/*!40000 ALTER TABLE `gamereports` DISABLE KEYS */;
/*!40000 ALTER TABLE `gamereports` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `games` (
  `gameid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `gamedate` datetime NOT NULL,
  `gamedate_toDisplay` datetime NOT NULL,
  `playersage` int NOT NULL,
  `hometeamid` int NOT NULL,
  `awayteamid` int NOT NULL,
  `locid` int NOT NULL,
  `umpreqd` int NOT NULL,
  `ump1` int DEFAULT NULL,
  `ump2` int DEFAULT NULL,
  `ump3` int DEFAULT NULL,
  `ump4` int DEFAULT NULL,
  `report` tinyint NOT NULL COMMENT 'report = 0 (not required)\\nreport = 1 (required)\\n** If "report = 1" in "leagues" table, then "report" would be 1 by default, and disabled at settings.',
  `report1` varchar(255) DEFAULT NULL,
  `report2` varchar(255) DEFAULT NULL,
  `report3` varchar(255) DEFAULT NULL,
  `report4` varchar(255) DEFAULT NULL,
  `ump1pay` varchar(255) NOT NULL,
  `ump1bonus` varchar(255) DEFAULT NULL,
  `ump234pay` varchar(255) DEFAULT NULL,
  `ump234bonus` varchar(255) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0=open,1=closed',
  `paid_umpires` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`gameid`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `games`
--

LOCK TABLES `games` WRITE;
/*!40000 ALTER TABLE `games` DISABLE KEYS */;
INSERT INTO `games` VALUES (1,1,'2024-12-03 18:00:00','2024-12-03 18:30:00',10,1,2,1,2,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'80','10','50','5',0,NULL,'2024-06-13 08:02:58','2024-06-13 09:25:19'),(2,1,'2024-12-04 20:00:00','2024-12-04 20:30:00',10,3,4,2,4,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,'80','0','60','0',0,NULL,'2024-06-13 08:05:00','2024-06-13 09:25:19');
/*!40000 ALTER TABLE `games` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `highlighted_report`
--

DROP TABLE IF EXISTS `highlighted_report`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `highlighted_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `report_col` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `highlighted_report`
--

LOCK TABLES `highlighted_report` WRITE;
/*!40000 ALTER TABLE `highlighted_report` DISABLE KEYS */;
/*!40000 ALTER TABLE `highlighted_report` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `icons`
--

DROP TABLE IF EXISTS `icons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `icons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `icons`
--

LOCK TABLES `icons` WRITE;
/*!40000 ALTER TABLE `icons` DISABLE KEYS */;
INSERT INTO `icons` VALUES (1,'<i class=\"fa-solid fa-envelope\"></i>'),(2,'<i class=\"fa-solid fa-trophy\"></i>'),(3,'<i class=\"fa-solid fa-sack-dollar\"></i>'),(4,'<i class=\"fa-solid fa-baseball\"></i>'),(5,'<i class=\"fa-solid fa-user-tie\"></i>'),(6,'<i class=\"fa-solid fa-triangle-exclamation\"></i>');
/*!40000 ALTER TABLE `icons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_age`
--

DROP TABLE IF EXISTS `lapreset_age`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_age` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_age`
--

LOCK TABLES `lapreset_age` WRITE;
/*!40000 ALTER TABLE `lapreset_age` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_age` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_day`
--

DROP TABLE IF EXISTS `lapreset_day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_day` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `dayname` varchar(3) DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_day`
--

LOCK TABLES `lapreset_day` WRITE;
/*!40000 ALTER TABLE `lapreset_day` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_ground`
--

DROP TABLE IF EXISTS `lapreset_ground`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_ground` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `locid` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_ground`
--

LOCK TABLES `lapreset_ground` WRITE;
/*!40000 ALTER TABLE `lapreset_ground` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_ground` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_pay`
--

DROP TABLE IF EXISTS `lapreset_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_pay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_pay`
--

LOCK TABLES `lapreset_pay` WRITE;
/*!40000 ALTER TABLE `lapreset_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_schedule`
--

DROP TABLE IF EXISTS `lapreset_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_schedule`
--

LOCK TABLES `lapreset_schedule` WRITE;
/*!40000 ALTER TABLE `lapreset_schedule` DISABLE KEYS */;
INSERT INTO `lapreset_schedule` VALUES (1,1,'-',2000,'2024-06-13 07:55:11','2024-06-13 07:55:11');
/*!40000 ALTER TABLE `lapreset_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_time`
--

DROP TABLE IF EXISTS `lapreset_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_time` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_time`
--

LOCK TABLES `lapreset_time` WRITE;
/*!40000 ALTER TABLE `lapreset_time` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_time` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_umpdur`
--

DROP TABLE IF EXISTS `lapreset_umpdur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_umpdur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_umpdur`
--

LOCK TABLES `lapreset_umpdur` WRITE;
/*!40000 ALTER TABLE `lapreset_umpdur` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_umpdur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_umpgames`
--

DROP TABLE IF EXISTS `lapreset_umpgames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_umpgames` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_umpgames`
--

LOCK TABLES `lapreset_umpgames` WRITE;
/*!40000 ALTER TABLE `lapreset_umpgames` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_umpgames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lapreset_umppos`
--

DROP TABLE IF EXISTS `lapreset_umppos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lapreset_umppos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `position` int DEFAULT NULL COMMENT 'position = 1,2 (2 means 2,3,4)',
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lapreset_umppos`
--

LOCK TABLES `lapreset_umppos` WRITE;
/*!40000 ALTER TABLE `lapreset_umppos` DISABLE KEYS */;
/*!40000 ALTER TABLE `lapreset_umppos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagueapplications`
--

DROP TABLE IF EXISTS `leagueapplications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagueapplications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `umpid` int NOT NULL,
  `lqid` int NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagueapplications`
--

LOCK TABLES `leagueapplications` WRITE;
/*!40000 ALTER TABLE `leagueapplications` DISABLE KEYS */;
/*!40000 ALTER TABLE `leagueapplications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagueemailsettings`
--

DROP TABLE IF EXISTS `leagueemailsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagueemailsettings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `leave_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `join_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `apply` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagueemailsettings`
--

LOCK TABLES `leagueemailsettings` WRITE;
/*!40000 ALTER TABLE `leagueemailsettings` DISABLE KEYS */;
INSERT INTO `leagueemailsettings` VALUES (1,1,1,1,1,'2024-06-13 07:53:54','2024-06-13 07:53:54');
/*!40000 ALTER TABLE `leagueemailsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leaguequestions`
--

DROP TABLE IF EXISTS `leaguequestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leaguequestions` (
  `lqid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `question` varchar(200) NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lqid`),
  KEY `leaguequestions_fk0` (`leagueid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leaguequestions`
--

LOCK TABLES `leaguequestions` WRITE;
/*!40000 ALTER TABLE `leaguequestions` DISABLE KEYS */;
/*!40000 ALTER TABLE `leaguequestions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagues` (
  `leagueid` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `leaguename` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `report` int DEFAULT '1' COMMENT 'report = 0 (no report need)\\r\\nreport = 1 (needed for all games)\\r\\nreport = 2 (needed for selected games)\\r\\n',
  `joiningpoint` int DEFAULT '100',
  `assignbefore` int DEFAULT '7',
  `leavebefore` int DEFAULT '5',
  `defaultpay` int DEFAULT '80',
  `mainumpage` int DEFAULT '5',
  `otherumpage` int DEFAULT '2',
  `status` int NOT NULL COMMENT '0=NOT ACTIVE, 1=ACTIVE',
  `umpire_joining_status` int NOT NULL DEFAULT '1' COMMENT '1=can join,0=closed',
  `cc` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`leagueid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagues`
--

LOCK TABLES `leagues` WRITE;
/*!40000 ALTER TABLE `leagues` DISABLE KEYS */;
INSERT INTO `leagues` VALUES (1,'Demo Umpirecentral League Owner','1234567890','Demo Umpirecentral League',1,100,365,1,80,5,2,1,1,'#f3a05c','2024-06-13 07:53:54','2024-06-13 07:55:00',NULL);
/*!40000 ALTER TABLE `leagues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `leagueumpires`
--

DROP TABLE IF EXISTS `leagueumpires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `leagueumpires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `leagueid` int NOT NULL,
  `points` int NOT NULL,
  `payout` float DEFAULT NULL COMMENT 'profile payout or game payout, the umpire will get paid whichever is higher.',
  `owed` float DEFAULT NULL,
  `received` float DEFAULT NULL,
  `bonus` float DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0' COMMENT '0 = active, 1 = blocked\\r\\n',
  `notes` longtext,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `leagueumpires`
--

LOCK TABLES `leagueumpires` WRITE;
/*!40000 ALTER TABLE `leagueumpires` DISABLE KEYS */;
/*!40000 ALTER TABLE `leagueumpires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `locations` (
  `locid` int NOT NULL AUTO_INCREMENT,
  `ground` varchar(100) NOT NULL,
  `latitude` varchar(255) NOT NULL,
  `longitude` varchar(255) NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`locid`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `locations`
--

LOCK TABLES `locations` WRITE;
/*!40000 ALTER TABLE `locations` DISABLE KEYS */;
INSERT INTO `locations` VALUES (1,'American Family Field','43.0282068','-87.97126759999999',1,'2024-06-13 08:00:45','2024-06-13 08:00:45',NULL),(2,'Angel Stadium','33.7998135','-117.8824162',1,'2024-06-13 08:01:03','2024-06-13 08:01:03',NULL),(3,'Busch Stadium','38.6226188','-90.1928209',1,'2024-06-13 08:01:20','2024-06-13 08:01:20',NULL),(4,'Chase Field','33.4453344','-112.0667091',1,'2024-06-13 08:01:41','2024-06-13 08:01:41',NULL),(5,'Citi Field','40.7570877','-73.8458213',1,'2024-06-13 08:01:56','2024-06-13 08:01:56',NULL);
/*!40000 ALTER TABLE `locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `umpid` int DEFAULT NULL,
  `umpmsg` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `leaguemsg` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci,
  `type` int NOT NULL DEFAULT '0' COMMENT '0=auto,1=custom',
  `iconid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payouts`
--

DROP TABLE IF EXISTS `payouts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payouts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `umpid` int NOT NULL,
  `paydate` date NOT NULL,
  `payamt` float NOT NULL,
  `pmttype` varchar(10) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'pmttype = "Game" or "Bonus" or "payout"',
  `gameid` int DEFAULT NULL,
  `owe` float NOT NULL,
  `ump_pending` float NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payouts`
--

LOCK TABLES `payouts` WRITE;
/*!40000 ALTER TABLE `payouts` DISABLE KEYS */;
/*!40000 ALTER TABLE `payouts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pointpresets`
--

DROP TABLE IF EXISTS `pointpresets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pointpresets` (
  `presetid` int NOT NULL AUTO_INCREMENT,
  `presetname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`presetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pointpresets`
--

LOCK TABLES `pointpresets` WRITE;
/*!40000 ALTER TABLE `pointpresets` DISABLE KEYS */;
/*!40000 ALTER TABLE `pointpresets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `refundpoints`
--

DROP TABLE IF EXISTS `refundpoints`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `refundpoints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueumpires_id` int NOT NULL,
  `game_id` int NOT NULL,
  `addless` varchar(255) NOT NULL,
  `point` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `refundpoints`
--

LOCK TABLES `refundpoints` WRITE;
/*!40000 ALTER TABLE `refundpoints` DISABLE KEYS */;
/*!40000 ALTER TABLE `refundpoints` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reportquestions`
--

DROP TABLE IF EXISTS `reportquestions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `reportquestions` (
  `rqid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `question` varchar(200) NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`rqid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reportquestions`
--

LOCK TABLES `reportquestions` WRITE;
/*!40000 ALTER TABLE `reportquestions` DISABLE KEYS */;
/*!40000 ALTER TABLE `reportquestions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `site_meta_data`
--

DROP TABLE IF EXISTS `site_meta_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `site_meta_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `site_meta_data`
--

LOCK TABLES `site_meta_data` WRITE;
/*!40000 ALTER TABLE `site_meta_data` DISABLE KEYS */;
INSERT INTO `site_meta_data` VALUES (1,'SHOW_FEEDBACK_OPTION','1');
/*!40000 ALTER TABLE `site_meta_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `team_division`
--

DROP TABLE IF EXISTS `team_division`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `team_division` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `team_division`
--

LOCK TABLES `team_division` WRITE;
/*!40000 ALTER TABLE `team_division` DISABLE KEYS */;
/*!40000 ALTER TABLE `team_division` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `teams` (
  `teamid` int NOT NULL AUTO_INCREMENT,
  `teamname` varchar(100) NOT NULL,
  `leagueid` int NOT NULL,
  `divid` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`teamid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `teams`
--

LOCK TABLES `teams` WRITE;
/*!40000 ALTER TABLE `teams` DISABLE KEYS */;
INSERT INTO `teams` VALUES (1,'Team One',1,NULL,'2024-06-13 07:56:16','2024-06-13 07:56:16',NULL),(2,'Team Two',1,NULL,'2024-06-13 07:56:24','2024-06-13 07:56:24',NULL),(3,'Team Three',1,NULL,'2024-06-13 07:56:31','2024-06-13 07:56:31',NULL),(4,'Team Four',1,NULL,'2024-06-13 07:56:40','2024-06-13 07:56:40',NULL),(5,'Team Five',1,NULL,'2024-06-13 07:56:47','2024-06-13 07:56:47',NULL),(6,'Team Six',1,NULL,'2024-06-13 07:56:56','2024-06-13 07:56:56',NULL),(7,'Team Seven',1,NULL,'2024-06-13 07:57:08','2024-06-13 07:57:08',NULL),(8,'Team Eight',1,NULL,'2024-06-13 07:57:18','2024-06-13 07:57:18',NULL),(9,'Team Nine',1,NULL,'2024-06-13 07:57:25','2024-06-13 07:57:25',NULL),(10,'Team Ten',1,NULL,'2024-06-13 07:57:33','2024-06-13 07:57:33',NULL);
/*!40000 ALTER TABLE `teams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_age`
--

DROP TABLE IF EXISTS `ucpreset_age`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_age` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '-' COMMENT 'addless = "+" or "-"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_age`
--

LOCK TABLES `ucpreset_age` WRITE;
/*!40000 ALTER TABLE `ucpreset_age` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_age` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_day`
--

DROP TABLE IF EXISTS `ucpreset_day`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_day` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `dayname` varchar(3) DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_day`
--

LOCK TABLES `ucpreset_day` WRITE;
/*!40000 ALTER TABLE `ucpreset_day` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_day` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_ground`
--

DROP TABLE IF EXISTS `ucpreset_ground`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_ground` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `locid` int DEFAULT NULL,
  `addless` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '-' COMMENT 'addless = "+" or "-"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_ground`
--

LOCK TABLES `ucpreset_ground` WRITE;
/*!40000 ALTER TABLE `ucpreset_ground` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_ground` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_pay`
--

DROP TABLE IF EXISTS `ucpreset_pay`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_pay` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_pay`
--

LOCK TABLES `ucpreset_pay` WRITE;
/*!40000 ALTER TABLE `ucpreset_pay` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_pay` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_schedule`
--

DROP TABLE IF EXISTS `ucpreset_schedule`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `addless` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '-' COMMENT 'addless = "+" or "-"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_schedule`
--

LOCK TABLES `ucpreset_schedule` WRITE;
/*!40000 ALTER TABLE `ucpreset_schedule` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_schedule` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_time`
--

DROP TABLE IF EXISTS `ucpreset_time`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_time` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_time`
--

LOCK TABLES `ucpreset_time` WRITE;
/*!40000 ALTER TABLE `ucpreset_time` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_time` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_umpdur`
--

DROP TABLE IF EXISTS `ucpreset_umpdur`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_umpdur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_umpdur`
--

LOCK TABLES `ucpreset_umpdur` WRITE;
/*!40000 ALTER TABLE `ucpreset_umpdur` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_umpdur` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_umpgames`
--

DROP TABLE IF EXISTS `ucpreset_umpgames`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_umpgames` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `from` int DEFAULT NULL,
  `to` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_umpgames`
--

LOCK TABLES `ucpreset_umpgames` WRITE;
/*!40000 ALTER TABLE `ucpreset_umpgames` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_umpgames` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ucpreset_umppos`
--

DROP TABLE IF EXISTS `ucpreset_umppos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ucpreset_umppos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `position` int DEFAULT NULL COMMENT 'position = 1,2 (2 means 2,3,4)',
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ucpreset_umppos`
--

LOCK TABLES `ucpreset_umppos` WRITE;
/*!40000 ALTER TABLE `ucpreset_umppos` DISABLE KEYS */;
/*!40000 ALTER TABLE `ucpreset_umppos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umpireemailsettings`
--

DROP TABLE IF EXISTS `umpireemailsettings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `umpireemailsettings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `schedule_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `cancel_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `payment` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `message` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `application` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umpireemailsettings`
--

LOCK TABLES `umpireemailsettings` WRITE;
/*!40000 ALTER TABLE `umpireemailsettings` DISABLE KEYS */;
/*!40000 ALTER TABLE `umpireemailsettings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umpirepref`
--

DROP TABLE IF EXISTS `umpirepref`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `umpirepref` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `slno` int NOT NULL,
  `leagueid` int NOT NULL COMMENT 'leagueid = 0 (highest paid game)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umpirepref`
--

LOCK TABLES `umpirepref` WRITE;
/*!40000 ALTER TABLE `umpirepref` DISABLE KEYS */;
/*!40000 ALTER TABLE `umpirepref` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `umpires`
--

DROP TABLE IF EXISTS `umpires`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `umpires` (
  `umpid` int NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `phone` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `dob` date NOT NULL,
  `zip` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `profilepic` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `bio` longtext,
  `email_verify_status` int NOT NULL DEFAULT '0',
  `status` int NOT NULL DEFAULT '1' COMMENT '0=not active, 1=active',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`umpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `umpires`
--

LOCK TABLES `umpires` WRITE;
/*!40000 ALTER TABLE `umpires` DISABLE KEYS */;
/*!40000 ALTER TABLE `umpires` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `uid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `otp` varchar(255) DEFAULT NULL COMMENT 'OTP is for Umpires only',
  `usertype` int DEFAULT NULL COMMENT '1 = super admin\\n2 = league admin\\n3 = umpire',
  `status` int NOT NULL DEFAULT '1' COMMENT '0=not active,1=active',
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  `isLeagueOwner` int NOT NULL DEFAULT '0' COMMENT ' 	0=normalAdmin,1=owner ',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=132 DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,NULL,'admin','$2y$10$X26LJfrsieR2Gc0nIBq7Gecu4ApLQNqKwGEY0t/5b.GtFiWRSlfym',NULL,1,1,'2023-09-16 11:16:33','2023-12-14 21:33:26',NULL,0),(2,NULL,'wg-admin','$2y$10$Ac88wH1AngbPFUuienrdPOY0wzVkhu2Vgy4SLHiHmhC2ncQN1G.x2',NULL,1,1,'2023-09-16 11:16:33','2023-11-13 15:20:17',NULL,0),(131,1,'demoumpirecentralleague@yopmail.com','$2y$10$1ckZKh3MQ4T79JPZWObMxeSxvSlUL6hD2OspcI74LEFzO4r3GbetS',NULL,2,1,'2024-06-13 07:53:54','2024-06-13 07:53:54',NULL,1);
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

-- Dump completed on 2024-06-13 18:55:19
