-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jun 13, 2024 at 12:28 PM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `umpire_demo`
--

-- --------------------------------------------------------

--
-- Table structure for table `absent_report`
--

DROP TABLE IF EXISTS `absent_report`;
CREATE TABLE IF NOT EXISTS `absent_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `umpid` int NOT NULL,
  `report_col` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `apply_to_league`
--

DROP TABLE IF EXISTS `apply_to_league`;
CREATE TABLE IF NOT EXISTS `apply_to_league` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `leagueid` int NOT NULL,
  `status` int NOT NULL COMMENT '0=pending,1=approved,2=denied,3=interview',
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `blockdates`
--

DROP TABLE IF EXISTS `blockdates`;
CREATE TABLE IF NOT EXISTS `blockdates` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `blockdate` date NOT NULL,
  `blocktime` text CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL COMMENT 'Unavailable "time" to be kept as "13:00,14:00,17:00,18:00".\\r\\nEmpty "time" means unavailable for full day.',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `blockdates_fk0` (`umpid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `blockdivision`
--

DROP TABLE IF EXISTS `blockdivision`;
CREATE TABLE IF NOT EXISTS `blockdivision` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `divid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `blocklocations`
--

DROP TABLE IF EXISTS `blocklocations`;
CREATE TABLE IF NOT EXISTS `blocklocations` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `locid` int NOT NULL,
  `leagueid` int NOT NULL COMMENT 'leagueid = 0 (umpire has blacklisted the ground)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `blockteams`
--

DROP TABLE IF EXISTS `blockteams`;
CREATE TABLE IF NOT EXISTS `blockteams` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `teamid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `blockumpires`
--

DROP TABLE IF EXISTS `blockumpires`;
CREATE TABLE IF NOT EXISTS `blockumpires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `leagueid` int NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `gamereports`
--

DROP TABLE IF EXISTS `gamereports`;
CREATE TABLE IF NOT EXISTS `gamereports` (
  `grid` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `umpid` int NOT NULL,
  `rqid` int NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`grid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `games`
--

DROP TABLE IF EXISTS `games`;
CREATE TABLE IF NOT EXISTS `games` (
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

--
-- Dumping data for table `games`
--

INSERT INTO `games` (`gameid`, `leagueid`, `gamedate`, `gamedate_toDisplay`, `playersage`, `hometeamid`, `awayteamid`, `locid`, `umpreqd`, `ump1`, `ump2`, `ump3`, `ump4`, `report`, `report1`, `report2`, `report3`, `report4`, `ump1pay`, `ump1bonus`, `ump234pay`, `ump234bonus`, `status`, `paid_umpires`, `created_at`, `updated_at`) VALUES
(1, 1, '2024-12-01 18:00:00', '2024-12-01 18:30:00', 10, 1, 2, 1, 2, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '80', '10', '50', '5', 0, NULL, '2024-06-13 08:02:58', '2024-06-13 08:02:58'),
(2, 1, '2024-12-02 20:00:00', '2024-12-02 20:30:00', 10, 3, 4, 2, 4, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, '80', '0', '60', '0', 0, NULL, '2024-06-13 08:05:00', '2024-06-13 08:05:00');

-- --------------------------------------------------------

--
-- Table structure for table `highlighted_report`
--

DROP TABLE IF EXISTS `highlighted_report`;
CREATE TABLE IF NOT EXISTS `highlighted_report` (
  `id` int NOT NULL AUTO_INCREMENT,
  `gameid` int NOT NULL,
  `report_col` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `icons`
--

DROP TABLE IF EXISTS `icons`;
CREATE TABLE IF NOT EXISTS `icons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `icons`
--

INSERT INTO `icons` (`id`, `code`) VALUES
(1, '<i class=\"fa-solid fa-envelope\"></i>'),
(2, '<i class=\"fa-solid fa-trophy\"></i>'),
(3, '<i class=\"fa-solid fa-sack-dollar\"></i>'),
(4, '<i class=\"fa-solid fa-baseball\"></i>'),
(5, '<i class=\"fa-solid fa-user-tie\"></i>'),
(6, '<i class=\"fa-solid fa-triangle-exclamation\"></i>');

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_age`
--

DROP TABLE IF EXISTS `lapreset_age`;
CREATE TABLE IF NOT EXISTS `lapreset_age` (
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

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_day`
--

DROP TABLE IF EXISTS `lapreset_day`;
CREATE TABLE IF NOT EXISTS `lapreset_day` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `dayname` varchar(3) DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_ground`
--

DROP TABLE IF EXISTS `lapreset_ground`;
CREATE TABLE IF NOT EXISTS `lapreset_ground` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `locid` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_pay`
--

DROP TABLE IF EXISTS `lapreset_pay`;
CREATE TABLE IF NOT EXISTS `lapreset_pay` (
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

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_schedule`
--

DROP TABLE IF EXISTS `lapreset_schedule`;
CREATE TABLE IF NOT EXISTS `lapreset_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `lapreset_schedule`
--

INSERT INTO `lapreset_schedule` (`id`, `leagueid`, `addless`, `point`, `created_at`, `updated_at`) VALUES
(1, 1, '-', 2000, '2024-06-13 07:55:11', '2024-06-13 07:55:11');

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_time`
--

DROP TABLE IF EXISTS `lapreset_time`;
CREATE TABLE IF NOT EXISTS `lapreset_time` (
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

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_umpdur`
--

DROP TABLE IF EXISTS `lapreset_umpdur`;
CREATE TABLE IF NOT EXISTS `lapreset_umpdur` (
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

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_umpgames`
--

DROP TABLE IF EXISTS `lapreset_umpgames`;
CREATE TABLE IF NOT EXISTS `lapreset_umpgames` (
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

-- --------------------------------------------------------

--
-- Table structure for table `lapreset_umppos`
--

DROP TABLE IF EXISTS `lapreset_umppos`;
CREATE TABLE IF NOT EXISTS `lapreset_umppos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int DEFAULT NULL,
  `position` int DEFAULT NULL COMMENT 'position = 1,2 (2 means 2,3,4)',
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `leagueapplications`
--

DROP TABLE IF EXISTS `leagueapplications`;
CREATE TABLE IF NOT EXISTS `leagueapplications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `umpid` int NOT NULL,
  `lqid` int NOT NULL,
  `answer` text NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `leagueemailsettings`
--

DROP TABLE IF EXISTS `leagueemailsettings`;
CREATE TABLE IF NOT EXISTS `leagueemailsettings` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `leave_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `join_game` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `apply` int NOT NULL DEFAULT '1' COMMENT '0=off,1=on',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `leagueemailsettings`
--

INSERT INTO `leagueemailsettings` (`id`, `leagueid`, `leave_game`, `join_game`, `apply`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, 1, '2024-06-13 07:53:54', '2024-06-13 07:53:54');

-- --------------------------------------------------------

--
-- Table structure for table `leaguequestions`
--

DROP TABLE IF EXISTS `leaguequestions`;
CREATE TABLE IF NOT EXISTS `leaguequestions` (
  `lqid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `question` varchar(200) NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`lqid`),
  KEY `leaguequestions_fk0` (`leagueid`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `leagues`
--

DROP TABLE IF EXISTS `leagues`;
CREATE TABLE IF NOT EXISTS `leagues` (
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

--
-- Dumping data for table `leagues`
--

INSERT INTO `leagues` (`leagueid`, `name`, `phone`, `leaguename`, `report`, `joiningpoint`, `assignbefore`, `leavebefore`, `defaultpay`, `mainumpage`, `otherumpage`, `status`, `umpire_joining_status`, `cc`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Demo Umpirecentral League Owner', '1234567890', 'Demo Umpirecentral League', 1, 100, 365, 1, 80, 5, 2, 1, 1, '#f3a05c', '2024-06-13 07:53:54', '2024-06-13 07:55:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `leagueumpires`
--

DROP TABLE IF EXISTS `leagueumpires`;
CREATE TABLE IF NOT EXISTS `leagueumpires` (
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

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

DROP TABLE IF EXISTS `locations`;
CREATE TABLE IF NOT EXISTS `locations` (
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

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`locid`, `ground`, `latitude`, `longitude`, `leagueid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'American Family Field', '43.0282068', '-87.97126759999999', 1, '2024-06-13 08:00:45', '2024-06-13 08:00:45', NULL),
(2, 'Angel Stadium', '33.7998135', '-117.8824162', 1, '2024-06-13 08:01:03', '2024-06-13 08:01:03', NULL),
(3, 'Busch Stadium', '38.6226188', '-90.1928209', 1, '2024-06-13 08:01:20', '2024-06-13 08:01:20', NULL),
(4, 'Chase Field', '33.4453344', '-112.0667091', 1, '2024-06-13 08:01:41', '2024-06-13 08:01:41', NULL),
(5, 'Citi Field', '40.7570877', '-73.8458213', 1, '2024-06-13 08:01:56', '2024-06-13 08:01:56', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
CREATE TABLE IF NOT EXISTS `notifications` (
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

-- --------------------------------------------------------

--
-- Table structure for table `payouts`
--

DROP TABLE IF EXISTS `payouts`;
CREATE TABLE IF NOT EXISTS `payouts` (
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

-- --------------------------------------------------------

--
-- Table structure for table `pointpresets`
--

DROP TABLE IF EXISTS `pointpresets`;
CREATE TABLE IF NOT EXISTS `pointpresets` (
  `presetid` int NOT NULL AUTO_INCREMENT,
  `presetname` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`presetid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `refundpoints`
--

DROP TABLE IF EXISTS `refundpoints`;
CREATE TABLE IF NOT EXISTS `refundpoints` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueumpires_id` int NOT NULL,
  `game_id` int NOT NULL,
  `addless` varchar(255) NOT NULL,
  `point` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reportquestions`
--

DROP TABLE IF EXISTS `reportquestions`;
CREATE TABLE IF NOT EXISTS `reportquestions` (
  `rqid` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `question` varchar(200) NOT NULL,
  `order` int NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`rqid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `site_meta_data`
--

DROP TABLE IF EXISTS `site_meta_data`;
CREATE TABLE IF NOT EXISTS `site_meta_data` (
  `id` int NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(255) NOT NULL,
  `meta_value` longtext NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `site_meta_data`
--

INSERT INTO `site_meta_data` (`id`, `meta_key`, `meta_value`) VALUES
(1, 'SHOW_FEEDBACK_OPTION', '1');

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

DROP TABLE IF EXISTS `teams`;
CREATE TABLE IF NOT EXISTS `teams` (
  `teamid` int NOT NULL AUTO_INCREMENT,
  `teamname` varchar(100) NOT NULL,
  `leagueid` int NOT NULL,
  `divid` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`teamid`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `teams`
--

INSERT INTO `teams` (`teamid`, `teamname`, `leagueid`, `divid`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Team One', 1, NULL, '2024-06-13 07:56:16', '2024-06-13 07:56:16', NULL),
(2, 'Team Two', 1, NULL, '2024-06-13 07:56:24', '2024-06-13 07:56:24', NULL),
(3, 'Team Three', 1, NULL, '2024-06-13 07:56:31', '2024-06-13 07:56:31', NULL),
(4, 'Team Four', 1, NULL, '2024-06-13 07:56:40', '2024-06-13 07:56:40', NULL),
(5, 'Team Five', 1, NULL, '2024-06-13 07:56:47', '2024-06-13 07:56:47', NULL),
(6, 'Team Six', 1, NULL, '2024-06-13 07:56:56', '2024-06-13 07:56:56', NULL),
(7, 'Team Seven', 1, NULL, '2024-06-13 07:57:08', '2024-06-13 07:57:08', NULL),
(8, 'Team Eight', 1, NULL, '2024-06-13 07:57:18', '2024-06-13 07:57:18', NULL),
(9, 'Team Nine', 1, NULL, '2024-06-13 07:57:25', '2024-06-13 07:57:25', NULL),
(10, 'Team Ten', 1, NULL, '2024-06-13 07:57:33', '2024-06-13 07:57:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `team_division`
--

DROP TABLE IF EXISTS `team_division`;
CREATE TABLE IF NOT EXISTS `team_division` (
  `id` int NOT NULL AUTO_INCREMENT,
  `leagueid` int NOT NULL,
  `name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_age`
--

DROP TABLE IF EXISTS `ucpreset_age`;
CREATE TABLE IF NOT EXISTS `ucpreset_age` (
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

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_day`
--

DROP TABLE IF EXISTS `ucpreset_day`;
CREATE TABLE IF NOT EXISTS `ucpreset_day` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `dayname` varchar(3) DEFAULT NULL,
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_ground`
--

DROP TABLE IF EXISTS `ucpreset_ground`;
CREATE TABLE IF NOT EXISTS `ucpreset_ground` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `locid` int DEFAULT NULL,
  `addless` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '-' COMMENT 'addless = "+" or "-"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_pay`
--

DROP TABLE IF EXISTS `ucpreset_pay`;
CREATE TABLE IF NOT EXISTS `ucpreset_pay` (
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

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_schedule`
--

DROP TABLE IF EXISTS `ucpreset_schedule`;
CREATE TABLE IF NOT EXISTS `ucpreset_schedule` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `addless` varchar(4) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL DEFAULT '-' COMMENT 'addless = "+" or "-"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_time`
--

DROP TABLE IF EXISTS `ucpreset_time`;
CREATE TABLE IF NOT EXISTS `ucpreset_time` (
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

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_umpdur`
--

DROP TABLE IF EXISTS `ucpreset_umpdur`;
CREATE TABLE IF NOT EXISTS `ucpreset_umpdur` (
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

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_umpgames`
--

DROP TABLE IF EXISTS `ucpreset_umpgames`;
CREATE TABLE IF NOT EXISTS `ucpreset_umpgames` (
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

-- --------------------------------------------------------

--
-- Table structure for table `ucpreset_umppos`
--

DROP TABLE IF EXISTS `ucpreset_umppos`;
CREATE TABLE IF NOT EXISTS `ucpreset_umppos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `presetid` int DEFAULT NULL,
  `position` int DEFAULT NULL COMMENT 'position = 1,2 (2 means 2,3,4)',
  `addless` varchar(4) NOT NULL DEFAULT 'less' COMMENT 'addless = "add" or "less"',
  `point` int DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `umpireemailsettings`
--

DROP TABLE IF EXISTS `umpireemailsettings`;
CREATE TABLE IF NOT EXISTS `umpireemailsettings` (
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

-- --------------------------------------------------------

--
-- Table structure for table `umpirepref`
--

DROP TABLE IF EXISTS `umpirepref`;
CREATE TABLE IF NOT EXISTS `umpirepref` (
  `id` int NOT NULL AUTO_INCREMENT,
  `umpid` int NOT NULL,
  `slno` int NOT NULL,
  `leagueid` int NOT NULL COMMENT 'leagueid = 0 (highest paid game)',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `umpires`
--

DROP TABLE IF EXISTS `umpires`;
CREATE TABLE IF NOT EXISTS `umpires` (
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

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`uid`, `leagueid`, `email`, `password`, `otp`, `usertype`, `status`, `created_at`, `updated_at`, `deleted_at`, `isLeagueOwner`) VALUES
(1, NULL, 'admin', '$2y$10$X26LJfrsieR2Gc0nIBq7Gecu4ApLQNqKwGEY0t/5b.GtFiWRSlfym', NULL, 1, 1, '2023-09-16 11:16:33', '2023-12-14 21:33:26', NULL, 0),
(2, NULL, 'wg-admin', '$2y$10$Ac88wH1AngbPFUuienrdPOY0wzVkhu2Vgy4SLHiHmhC2ncQN1G.x2', NULL, 1, 1, '2023-09-16 11:16:33', '2023-11-13 15:20:17', NULL, 0),
(131, 1, 'demoumpirecentralleague@yopmail.com', '$2y$10$1ckZKh3MQ4T79JPZWObMxeSxvSlUL6hD2OspcI74LEFzO4r3GbetS', NULL, 2, 1, '2024-06-13 07:53:54', '2024-06-13 07:53:54', NULL, 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
