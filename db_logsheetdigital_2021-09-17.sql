# ************************************************************
# Sequel Ace SQL dump
# Version 3038
#
# https://sequel-ace.com/
# https://github.com/Sequel-Ace/Sequel-Ace
#
# Host: 66.42.48.15 (MySQL 5.5.5-10.4.8-MariaDB-1:10.4.8+maria~bionic-log)
# Database: db_logsheetdigital
# Generation Time: 2021-09-17 00:59:04 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
SET NAMES utf8mb4;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE='NO_AUTO_VALUE_ON_ZERO', SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table tblm_asset
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_asset`;

CREATE TABLE `tblm_asset` (
  `assetId` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `assetStatusId` char(36) NOT NULL,
  `assetName` varchar(150) NOT NULL,
  `assetNumber` varchar(150) NOT NULL,
  `description` tinytext NOT NULL,
  `schType` enum('Daily','Weekly','Monthly') NOT NULL DEFAULT 'Daily',
  `schFrequency` tinyint(4) NOT NULL DEFAULT 1,
  `schWeeks` set('First','Second','Third','Fourth','Last') DEFAULT NULL,
  `schWeekDays` set('Su','Mo','Tu','We','Th','Fr','Sa') DEFAULT NULL,
  `schDays` set('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','Last') DEFAULT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`assetId`),
  KEY `tblm_asset_assetStatusId_IDX` (`assetStatusId`) USING BTREE,
  CONSTRAINT `tblm_asset_FK` FOREIGN KEY (`assetStatusId`) REFERENCES `tblm_assetStatus` (`assetStatusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_asset` WRITE;
/*!40000 ALTER TABLE `tblm_asset` DISABLE KEYS */;

INSERT INTO `tblm_asset` (`assetId`, `userId`, `assetStatusId`, `assetName`, `assetNumber`, `description`, `schType`, `schFrequency`, `schWeeks`, `schWeekDays`, `schDays`, `latitude`, `longitude`, `createdAt`, `updatedAt`, `deletedAt`)
VALUES
	('01c0ac2b-13cc-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','afabeda9-0fab-11ec-95b6-5600026457d1','Asset 8','008','Desc 8','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:18:59','2021-09-16 10:32:33',NULL),
	('5aec17b1-13cc-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','770b03d8-8fd9-4c15-ae50-8e60d5c0adaf','Asset 9','009','desc 9 updated','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:21:35','2021-09-16 11:29:42',NULL),
	('639cbd79-13cc-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 10','010','Desc 10','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:21:48','2021-09-15 09:51:16','2021-09-14 21:51:18'),
	('6c0eb680-13cc-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 11','011','Desc 11','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:22:00','2021-09-15 09:55:47','2021-09-14 21:55:54'),
	('afd1f7a2-13ca-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 1','001','Desc 1','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:09:33','2021-09-15 09:48:05','2021-09-14 21:48:10'),
	('ba282f35-13cb-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 3','003','Desc 3','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:17:06','2021-09-12 20:17:06',NULL),
	('c8cba71a-13cb-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','afabeda9-0fab-11ec-95b6-5600026457d1','Asset 4','004','Desc 4','Weekly',1,NULL,'Su,Mo,Tu,We,Th,Fr,Sa',NULL,-7.38574,109.36,'2021-09-12 20:17:27','2021-09-16 16:14:49',NULL),
	('d5882613-13cb-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 5','005','Desc 5 updated','Monthly',1,'First,Last','Mo,Tu,We,Th,Fr',NULL,-7.38574,109.36,'2021-09-12 20:17:46','2021-09-16 16:14:49',NULL),
	('d81d95bf-13ca-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 2','002','Desc 2','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:10:41','2021-09-12 20:10:41',NULL),
	('e4be5671-13cb-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','ad0ec7f9-0fab-11ec-95b6-5600026457d1','Asset 6','006','Desc 6','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:18:11','2021-09-12 20:18:11',NULL),
	('f27d0814-13cb-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','b2118358-0fab-11ec-95b6-5600026457d1','Asset 7','007','Desc 7','Daily',1,NULL,NULL,NULL,-7.38574,109.36,'2021-09-12 20:18:32','2021-09-12 20:18:32',NULL);

/*!40000 ALTER TABLE `tblm_asset` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_assetId` BEFORE INSERT ON `tblm_asset` FOR EACH ROW BEGIN
  IF new.assetId IS NULL THEN
    SET new.assetId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblm_assetStatus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_assetStatus`;

CREATE TABLE `tblm_assetStatus` (
  `assetStatusId` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `assetStatusName` varchar(100) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`assetStatusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_assetStatus` WRITE;
/*!40000 ALTER TABLE `tblm_assetStatus` DISABLE KEYS */;

INSERT INTO `tblm_assetStatus` (`assetStatusId`, `userId`, `assetStatusName`, `createdAt`, `updatedAt`, `deletedAt`)
VALUES
	('770b03d8-8fd9-4c15-ae50-8e60d5c0adaf','','dalam pengerjaan','2021-09-16 11:29:42','2021-09-16 11:29:42',NULL),
	('ad0ec7f9-0fab-11ec-95b6-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Running','2021-09-07 14:17:37','2021-09-07 14:17:37',NULL),
	('afabeda9-0fab-11ec-95b6-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Standby','2021-09-07 14:17:41','2021-09-07 14:17:41',NULL),
	('b2118358-0fab-11ec-95b6-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Repair','2021-09-07 14:17:45','2021-09-07 14:17:45',NULL);

/*!40000 ALTER TABLE `tblm_assetStatus` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_assetStatusId` BEFORE INSERT ON `tblm_assetStatus` FOR EACH ROW BEGIN
  IF new.assetStatusId IS NULL THEN
    SET new.assetStatusId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblm_assetTagging
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_assetTagging`;

CREATE TABLE `tblm_assetTagging` (
  `assetTaggingId` char(36) NOT NULL,
  `assetId` char(36) NOT NULL,
  `assetTaggingValue` varchar(100) NOT NULL,
  `assetTaggingtype` enum('rfid','coordinat','uhf') NOT NULL DEFAULT 'rfid',
  `description` tinytext DEFAULT NULL,
  PRIMARY KEY (`assetTaggingId`),
  KEY `tblm_assetTagging_assetId_IDX` (`assetId`) USING BTREE,
  KEY `tblm_assetTagging_assetTaggingtype_IDX` (`assetTaggingtype`) USING BTREE,
  CONSTRAINT `tblm_assetTagging_FK` FOREIGN KEY (`assetId`) REFERENCES `tblm_asset` (`assetId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_assetTagging` WRITE;
/*!40000 ALTER TABLE `tblm_assetTagging` DISABLE KEYS */;

INSERT INTO `tblm_assetTagging` (`assetTaggingId`, `assetId`, `assetTaggingValue`, `assetTaggingtype`, `description`)
VALUES
	('ef4e5b1e-160a-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','tagging value updated','','desc updated');

/*!40000 ALTER TABLE `tblm_assetTagging` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_assetTaggingId` BEFORE INSERT ON `tblm_assetTagging` FOR EACH ROW BEGIN
  IF new.assetTaggingId IS NULL THEN
    SET new.assetTaggingId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblm_parameter
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_parameter`;

CREATE TABLE `tblm_parameter` (
  `parameterId` char(36) NOT NULL,
  `assetId` char(36) NOT NULL,
  `sortId` smallint(6) DEFAULT NULL,
  `parameterName` varchar(150) NOT NULL,
  `photo` varchar(100) DEFAULT NULL,
  `description` tinytext DEFAULT NULL,
  `uom` varchar(50) DEFAULT NULL,
  `min` float DEFAULT NULL,
  `max` float DEFAULT NULL,
  `normal` varchar(255) DEFAULT NULL,
  `abnormal` varchar(255) DEFAULT NULL,
  `option` varchar(255) DEFAULT NULL,
  `inputType` enum('input','select','checkbox','textarea') NOT NULL DEFAULT 'textarea',
  `showOn` set('Running','Standby','Repair') NOT NULL DEFAULT 'Running',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  `updatedAt` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deletedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`parameterId`),
  KEY `tblm_parameter_assetId_IDX` (`assetId`) USING BTREE,
  CONSTRAINT `tblm_parameter_FK` FOREIGN KEY (`assetId`) REFERENCES `tblm_asset` (`assetId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_parameter` WRITE;
/*!40000 ALTER TABLE `tblm_parameter` DISABLE KEYS */;

INSERT INTO `tblm_parameter` (`parameterId`, `assetId`, `sortId`, `parameterName`, `photo`, `description`, `uom`, `min`, `max`, `normal`, `abnormal`, `option`, `inputType`, `showOn`, `createdAt`, `updatedAt`, `deletedAt`)
VALUES
	('007448df-16aa-11ec-95ab-5600026457d1','ba282f35-13cb-11ec-9be9-5600026457d1',NULL,'Test Add Parameter 4','','Test Add Parameter 4 Desc','',NULL,NULL,'','','','textarea','Running,Standby','2021-09-16 11:53:14','2021-09-16 11:53:14',NULL),
	('0a8c5b31-1575-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',NULL,'Test Parameter updated','','desc param','',NULL,NULL,'good','good','good,bad','select','Running,Standby','2021-09-14 23:01:37','2021-09-16 16:46:08',NULL),
	('2178aaad-16a9-11ec-95ab-5600026457d1','ba282f35-13cb-11ec-9be9-5600026457d1',NULL,'Test Add Parameter 3','','Test Add Parameter 3 Desc','',NULL,NULL,'','','option checkbox','checkbox','Running,Standby','2021-09-16 11:47:00','2021-09-16 11:47:00',NULL),
	('491d3bd3-13e1-11ec-a49b-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',53,'Parameter 1','photo1.jpg','Parameter Desc','ms',35,56,NULL,NULL,NULL,'input','Running','2021-09-12 22:51:26','2021-09-12 22:51:26',NULL),
	('5fd0faf1-13e1-11ec-a49b-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',53,'Parameter 2','photo2.jpg','Parameter Desc','ms',35,56,NULL,NULL,NULL,'input','Running','2021-09-12 22:52:04','2021-09-12 22:52:04',NULL),
	('6441079c-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 1',NULL,'desc 1','ms',34,56,NULL,NULL,NULL,'input','Running','2021-09-15 22:48:37','2021-09-15 22:48:37',NULL),
	('64484539-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 2',NULL,'desc 2',NULL,NULL,NULL,'good','bad','good, bad','select','Running','2021-09-15 22:48:37','2021-09-15 22:48:37',NULL),
	('64844659-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 3',NULL,'desc 3',NULL,NULL,NULL,'good','bad','good, bad','select','Running','2021-09-15 22:48:38','2021-09-15 22:48:38',NULL),
	('648987ff-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 4',NULL,'desc 4','ms',34,56,NULL,NULL,NULL,'input','Running','2021-09-15 22:48:38','2021-09-15 22:48:38',NULL),
	('648f1476-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 5',NULL,'desc 5',NULL,NULL,NULL,'good','bad','good, bad','select','Running','2021-09-15 22:48:38','2021-09-15 22:48:38',NULL),
	('649410d3-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 6',NULL,'desc 6','ms',34,56,NULL,NULL,NULL,'input','Running','2021-09-15 22:48:38','2021-09-15 22:48:38',NULL),
	('649bff63-163c-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'parameter 7',NULL,'desc 7','ms',34,56,NULL,NULL,NULL,'input','Running','2021-09-15 22:48:38','2021-09-15 22:48:38',NULL),
	('649efa07-13e1-11ec-a49b-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',53,'Parameter 3','photo3.jpg','Parameter Desc','ms',35,56,NULL,NULL,NULL,'input','Running','2021-09-12 22:52:12','2021-09-12 22:52:12',NULL),
	('7092ab75-1703-11ec-95ab-5600026457d1','c8cba71a-13cb-11ec-9be9-5600026457d1',0,'Parameter 1 updated','','Parameter description updated','ms',23,89,'good','bad','good,bad','select','Repair','2021-09-16 22:33:28','2021-09-16 22:39:33',NULL),
	('7e0271fb-146b-11ec-ab58-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',NULL,'Test Add Parameter 2','','descc','ms',554,444,'','','','input','Repair','2021-09-13 15:20:44','2021-09-13 15:20:44',NULL),
	('8ddf1fbd-145c-11ec-ab58-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1',NULL,'Test Add Parameter 2 Updated','C:\\fakepath\\Screen Shot 2021-08-21 at 23.00.00.png','desc','ms',23,89,'','','','input','Running','2021-09-13 13:33:49','2021-09-13 15:14:04','2021-09-13 03:14:07'),
	('abaa191e-146c-11ec-ab58-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1',NULL,'test add','','desc','ms',23,89,'','','','input','Standby','2021-09-13 15:29:11','2021-09-13 15:29:11',NULL),
	('d4444967-16a8-11ec-95ab-5600026457d1','ba282f35-13cb-11ec-9be9-5600026457d1',NULL,'Test Add Parameter','','Test Add Parameter Desc','',NULL,NULL,'good','bad','good,bad','select','Running,Standby','2021-09-16 11:44:51','2021-09-16 11:44:51',NULL),
	('e948ee94-16a8-11ec-95ab-5600026457d1','ba282f35-13cb-11ec-9be9-5600026457d1',NULL,'Test Add Parameter 2 updated','','Test Add Parameter2 Desc','ms',31,56,'','','','input','Running,Standby','2021-09-16 11:45:26','2021-09-16 12:05:00',NULL);

/*!40000 ALTER TABLE `tblm_parameter` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_parameterId` BEFORE INSERT ON `tblm_parameter` FOR EACH ROW BEGIN
  IF new.parameterId IS NULL THEN
    SET new.parameterId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblm_tag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_tag`;

CREATE TABLE `tblm_tag` (
  `tagId` char(36) NOT NULL,
  `userId` char(36) DEFAULT NULL,
  `tagName` varchar(50) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_tag` WRITE;
/*!40000 ALTER TABLE `tblm_tag` DISABLE KEYS */;

INSERT INTO `tblm_tag` (`tagId`, `userId`, `tagName`, `description`, `createdAt`)
VALUES
	('04ed79b6-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Tag 2','Tag 2 desc','2021-09-12 18:31:49'),
	('070c2518-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Tag 3','Tag 3 desc','2021-09-12 18:31:52'),
	('08d5d61b-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Tag 4','Tag 4 desc','2021-09-12 18:31:55'),
	('0ac82cd0-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Tag 5','Tag 5 desc','2021-09-12 18:31:59'),
	('95bb94b7-144b-11ec-ab58-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','cadcca','dcadc','2021-09-13 11:32:20'),
	('fd495ac7-13bc-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Tag 1','Tag 1 desc','2021-09-12 18:31:36');

/*!40000 ALTER TABLE `tblm_tag` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_tagId` BEFORE INSERT ON `tblm_tag` FOR EACH ROW BEGIN
  IF new.tagId IS NULL THEN
    SET new.tagId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblm_tagLocation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblm_tagLocation`;

CREATE TABLE `tblm_tagLocation` (
  `tagLocationId` char(36) NOT NULL,
  `userId` char(36) NOT NULL,
  `tagLocationName` varchar(50) NOT NULL,
  `latitude` float DEFAULT NULL,
  `longitude` float DEFAULT NULL,
  `description` mediumtext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`tagLocationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblm_tagLocation` WRITE;
/*!40000 ALTER TABLE `tblm_tagLocation` DISABLE KEYS */;

INSERT INTO `tblm_tagLocation` (`tagLocationId`, `userId`, `tagLocationName`, `latitude`, `longitude`, `description`, `createdAt`)
VALUES
	('16a8b993-1a2b-4fe2-9c70-91eb2a181b55','','coba',898989,898989,'coba desc','2021-09-16 17:15:58'),
	('322dd31d-4fc8-4c0d-a9ed-d18c7ad38a4b','','avadv updated',-7.72799,109.006,'updated','2021-09-16 16:48:54'),
	('9aec7223-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Location 1',-7.38574,109.36,'Location 1 desc','2021-09-12 18:36:01'),
	('9f930c9b-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Location Test update',-7.38574,109.36,'Location 2 desc update','2021-09-12 18:36:08'),
	('a3979dfd-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Location 3',-7.72799,109.006,'Location 3 desc','2021-09-12 18:36:15'),
	('a5c404b3-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Location 4',-7.38574,109.36,'Location 4 desc','2021-09-12 18:36:19'),
	('a7d21074-13bd-11ec-9be9-5600026457d1','3f0857bf-0fab-11ec-95b6-5600026457d1','Location 5',-7.38574,109.36,'Location 5 desc','2021-09-12 18:36:22'),
	('cc918679-919a-4daa-8560-0eaf99ccccdb','','Cek',89898,89898,'cek desc','2021-09-16 15:52:20');

/*!40000 ALTER TABLE `tblm_tagLocation` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_tagLocationId` BEFORE INSERT ON `tblm_tagLocation` FOR EACH ROW BEGIN
  IF new.tagLocationId IS NULL THEN
    SET new.tagLocationId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblmb_assetTag
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblmb_assetTag`;

CREATE TABLE `tblmb_assetTag` (
  `assetTagId` char(36) NOT NULL,
  `assetId` char(36) NOT NULL,
  `tagId` char(36) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assetTagId`),
  KEY `tblm_assetTag_assetId_IDX` (`assetId`) USING BTREE,
  KEY `tblm_assetTag_tagId_IDX` (`tagId`) USING BTREE,
  KEY `tblm_assetTag_assetId_tagId_IDX` (`assetId`,`tagId`) USING BTREE,
  KEY `tblm_assetTag_tagId_assetId_IDX` (`tagId`,`assetId`) USING BTREE,
  CONSTRAINT `tblm_assetTag_FK` FOREIGN KEY (`assetId`) REFERENCES `tblm_asset` (`assetId`),
  CONSTRAINT `tblm_assetTag_tagId_FK` FOREIGN KEY (`tagId`) REFERENCES `tblm_tag` (`tagId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblmb_assetTag` WRITE;
/*!40000 ALTER TABLE `tblmb_assetTag` DISABLE KEYS */;

INSERT INTO `tblmb_assetTag` (`assetTagId`, `assetId`, `tagId`, `createdAt`)
VALUES
	('23c37021-164a-11ec-95ab-5600026457d1','e4be5671-13cb-11ec-9be9-5600026457d1','04ed79b6-13bd-11ec-9be9-5600026457d1','2021-09-16 00:27:02'),
	('5be57049-169a-11ec-95ab-5600026457d1','d5882613-13cb-11ec-9be9-5600026457d1','0ac82cd0-13bd-11ec-9be9-5600026457d1','2021-09-16 10:01:16'),
	('bd7df970-15f3-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','08d5d61b-13bd-11ec-9be9-5600026457d1','2021-09-15 14:08:34'),
	('bd8a72e9-15f3-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','04ed79b6-13bd-11ec-9be9-5600026457d1','2021-09-15 14:08:34'),
	('bd96203a-15f3-11ec-95ab-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','070c2518-13bd-11ec-9be9-5600026457d1','2021-09-15 14:08:34'),
	('c4c90cb5-13cd-11ec-9be9-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','08d5d61b-13bd-11ec-9be9-5600026457d1','2021-09-12 20:31:43'),
	('c99572f8-13cd-11ec-9be9-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','08d5d61b-13bd-11ec-9be9-5600026457d1','2021-09-12 20:31:51'),
	('ca610a24-1693-11ec-95ab-5600026457d1','c8cba71a-13cb-11ec-9be9-5600026457d1','070c2518-13bd-11ec-9be9-5600026457d1','2021-09-16 09:14:15'),
	('d21fe369-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','04ed79b6-13bd-11ec-9be9-5600026457d1','2021-09-16 17:14:03'),
	('d36a3f79-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','070c2518-13bd-11ec-9be9-5600026457d1','2021-09-16 17:14:04'),
	('d47dcd9e-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','08d5d61b-13bd-11ec-9be9-5600026457d1','2021-09-16 17:14:08'),
	('d5deca36-13cd-11ec-9be9-5600026457d1','639cbd79-13cc-11ec-9be9-5600026457d1','08d5d61b-13bd-11ec-9be9-5600026457d1','2021-09-12 20:32:11'),
	('e03cadff-13cd-11ec-9be9-5600026457d1','639cbd79-13cc-11ec-9be9-5600026457d1','fd495ac7-13bc-11ec-9be9-5600026457d1','2021-09-12 20:32:29'),
	('ee2a7b5f-13cd-11ec-9be9-5600026457d1','afd1f7a2-13ca-11ec-9be9-5600026457d1','fd495ac7-13bc-11ec-9be9-5600026457d1','2021-09-12 20:32:51');

/*!40000 ALTER TABLE `tblmb_assetTag` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_assetTagId` BEFORE INSERT ON `tblmb_assetTag` FOR EACH ROW BEGIN
  IF new.assetTagId IS NULL THEN
    SET new.assetTagId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblmb_assetTagLocation
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblmb_assetTagLocation`;

CREATE TABLE `tblmb_assetTagLocation` (
  `assetTagLocationId` char(36) NOT NULL,
  `assetId` char(36) NOT NULL,
  `tagLocationId` char(36) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`assetTagLocationId`),
  KEY `tblm_assetTagLocation_assetId_IDX` (`assetId`) USING BTREE,
  KEY `tblm_assetTagLocation_tagLocationId_IDX` (`tagLocationId`) USING BTREE,
  KEY `tblm_assetTagLocation_assetId_tagLocationId_IDX` (`assetId`,`tagLocationId`) USING BTREE,
  KEY `tblm_assetTagLocation_tagLocationId_assetId_IDX` (`tagLocationId`,`assetId`) USING BTREE,
  CONSTRAINT `tblm_assetTagLocation_FK` FOREIGN KEY (`assetId`) REFERENCES `tblm_asset` (`assetId`),
  CONSTRAINT `tblm_assetTagLocation_FK_1` FOREIGN KEY (`tagLocationId`) REFERENCES `tblm_tagLocation` (`tagLocationId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

LOCK TABLES `tblmb_assetTagLocation` WRITE;
/*!40000 ALTER TABLE `tblmb_assetTagLocation` DISABLE KEYS */;

INSERT INTO `tblmb_assetTagLocation` (`assetTagLocationId`, `assetId`, `tagLocationId`, `createdAt`)
VALUES
	('216aaae1-13cf-11ec-9be9-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','a7d21074-13bd-11ec-9be9-5600026457d1','2021-09-12 20:41:28'),
	('2e4eee9b-13cf-11ec-9be9-5600026457d1','5aec17b1-13cc-11ec-9be9-5600026457d1','a5c404b3-13bd-11ec-9be9-5600026457d1','2021-09-12 20:41:49'),
	('4ad4d3eb-169a-11ec-95ab-5600026457d1','d5882613-13cb-11ec-9be9-5600026457d1','9f930c9b-13bd-11ec-9be9-5600026457d1','2021-09-16 10:00:47'),
	('bd2cfc32-13ce-11ec-9be9-5600026457d1','afd1f7a2-13ca-11ec-9be9-5600026457d1','9f930c9b-13bd-11ec-9be9-5600026457d1','2021-09-12 20:38:37'),
	('e1031413-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','322dd31d-4fc8-4c0d-a9ed-d18c7ad38a4b','2021-09-16 17:14:29'),
	('e10a1d50-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','9aec7223-13bd-11ec-9be9-5600026457d1','2021-09-16 17:14:29'),
	('e113963b-16d6-11ec-95ab-5600026457d1','01c0ac2b-13cc-11ec-9be9-5600026457d1','a5c404b3-13bd-11ec-9be9-5600026457d1','2021-09-16 17:14:29');

/*!40000 ALTER TABLE `tblmb_assetTagLocation` ENABLE KEYS */;
UNLOCK TABLES;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_assetTagLocationId` BEFORE INSERT ON `tblmb_assetTagLocation` FOR EACH ROW BEGIN
  IF new.assetTagLocationId IS NULL THEN
    SET new.assetTagLocationId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblt_attachmentTrx
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblt_attachmentTrx`;

CREATE TABLE `tblt_attachmentTrx` (
  `attachmentTrxId` char(36) NOT NULL,
  `scheduleTrxId` char(36) NOT NULL,
  `trxId` char(36) DEFAULT NULL,
  `attachment` varchar(100) DEFAULT NULL,
  `notes` tinytext DEFAULT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`attachmentTrxId`),
  KEY `tblt_attachmentTrx_scheduleTrxId_IDX` (`scheduleTrxId`) USING BTREE,
  KEY `tblt_attachmentTrx_trxId_IDX` (`trxId`) USING BTREE,
  KEY `tblt_attachmentTrx_scheduleTrxId_trxId_IDX` (`scheduleTrxId`,`trxId`) USING BTREE,
  CONSTRAINT `tblt_attachmentTrx_FK` FOREIGN KEY (`scheduleTrxId`) REFERENCES `tblt_schduleTrx` (`scheduleTrxId`),
  CONSTRAINT `tblt_attachmentTrx_FK_1` FOREIGN KEY (`trxId`) REFERENCES `tblt_transaction` (`trxId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_attachmentTrxId` BEFORE INSERT ON `tblt_attachmentTrx` FOR EACH ROW BEGIN
  IF new.attachmentTrxId IS NULL THEN
    SET new.attachmentTrxId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblt_finding
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblt_finding`;

CREATE TABLE `tblt_finding` (
  `findingId` char(36) NOT NULL,
  `trxId` char(36) NOT NULL,
  `condition` enum('Open','Closed') NOT NULL DEFAULT 'Open',
  `openedBy` varchar(50) NOT NULL,
  `openedAt` datetime NOT NULL DEFAULT current_timestamp(),
  `closedBy` varchar(100) DEFAULT NULL,
  `closedAt` datetime DEFAULT NULL,
  `findingPriority` enum('Low','Medium','High') NOT NULL DEFAULT 'Low',
  PRIMARY KEY (`findingId`),
  KEY `tblt_finding_trxId_IDX` (`trxId`) USING BTREE,
  KEY `tblt_finding_condition_IDX` (`condition`) USING BTREE,
  KEY `tblt_finding_openedBy_IDX` (`openedBy`) USING BTREE,
  KEY `tblt_finding_closedBy_IDX` (`closedBy`) USING BTREE,
  CONSTRAINT `tblt_finding_FK` FOREIGN KEY (`trxId`) REFERENCES `tblt_transaction` (`trxId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_findingId` BEFORE INSERT ON `tblt_finding` FOR EACH ROW BEGIN
  IF new.findingId IS NULL THEN
    SET new.findingId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblt_findingLog
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblt_findingLog`;

CREATE TABLE `tblt_findingLog` (
  `findingLogId` char(36) NOT NULL,
  `findingId` char(36) NOT NULL,
  `notes` tinytext NOT NULL,
  `attachment` varchar(100) DEFAULT NULL,
  `createdBy` varchar(100) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`findingLogId`),
  KEY `tblt_findingLog_findingId_IDX` (`findingId`) USING BTREE,
  CONSTRAINT `tblt_findingLog_FK` FOREIGN KEY (`findingId`) REFERENCES `tblt_finding` (`findingId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_findingLogId` BEFORE INSERT ON `tblt_findingLog` FOR EACH ROW BEGIN
  IF new.findingLogId IS NULL THEN
    SET new.findingLogId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblt_schduleTrx
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblt_schduleTrx`;

CREATE TABLE `tblt_schduleTrx` (
  `scheduleTrxId` char(36) NOT NULL,
  `assetId` char(36) NOT NULL,
  `assetStatusId` char(36) NOT NULL,
  `schType` enum('Daily','Weekly','Monthly') NOT NULL DEFAULT 'Daily',
  `schFrequency` int(11) NOT NULL DEFAULT 1,
  `schWeeks` set('First','Second','Third','Fourth','Last') DEFAULT NULL,
  `schWeekDays` set('Su','Mo','Tu','We','Th','Fr','Sa') DEFAULT NULL,
  `schDays` set('1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31','Last') DEFAULT NULL,
  `scheduleFrom` datetime NOT NULL,
  `scheduleTo` datetime NOT NULL,
  `syncAt` datetime DEFAULT NULL,
  `scannedAt` datetime DEFAULT NULL,
  `scannedEnd` datetime DEFAULT NULL,
  `scannedBy` varchar(100) DEFAULT NULL,
  `scannedWith` enum('qrcode','rfid') DEFAULT NULL,
  `scannedNotes` tinytext DEFAULT NULL,
  `scannedAccuration` tinyint(4) NOT NULL DEFAULT 0,
  `approvedAt` datetime DEFAULT NULL,
  `approvedNotes` tinytext DEFAULT NULL,
  `condition` enum('Normal','Finding','Open','Closed') NOT NULL DEFAULT 'Normal',
  `createdAt` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`scheduleTrxId`),
  KEY `tblt_schduleTrx_assetId_IDX` (`assetId`) USING BTREE,
  KEY `tblt_schduleTrx_condition_IDX` (`condition`) USING BTREE,
  KEY `tblt_schduleTrx_assetStatus_IDX` (`assetStatusId`) USING BTREE,
  KEY `tblt_schduleTrx_assetStatusId_IDX` (`assetStatusId`) USING BTREE,
  CONSTRAINT `tblt_schduleTrx_FK` FOREIGN KEY (`assetId`) REFERENCES `tblm_asset` (`assetId`),
  CONSTRAINT `tblt_schduleTrx_assetStatus_FK` FOREIGN KEY (`assetStatusId`) REFERENCES `tblm_assetStatus` (`assetStatusId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_scheduleTrxId` BEFORE INSERT ON `tblt_schduleTrx` FOR EACH ROW BEGIN
  IF new.scheduleTrxId IS NULL THEN
    SET new.scheduleTrxId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;


# Dump of table tblt_transaction
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tblt_transaction`;

CREATE TABLE `tblt_transaction` (
  `trxId` char(36) NOT NULL,
  `scheduleTrxId` char(36) NOT NULL,
  `parameterId` char(36) NOT NULL,
  `value` varchar(50) NOT NULL,
  `condition` enum('Normal','Finding','Open','Closed') NOT NULL DEFAULT 'Normal',
  PRIMARY KEY (`trxId`),
  KEY `tblt_transaction_scheduleTrxId_IDX` (`scheduleTrxId`) USING BTREE,
  KEY `tblt_transaction_parameterId_IDX` (`parameterId`) USING BTREE,
  CONSTRAINT `tblt_transaction_FK` FOREIGN KEY (`scheduleTrxId`) REFERENCES `tblt_schduleTrx` (`scheduleTrxId`),
  CONSTRAINT `tblt_transaction_parameter_FK` FOREIGN KEY (`parameterId`) REFERENCES `tblm_parameter` (`parameterId`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="STRICT_TRANS_TABLES,ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`admin`@`%` */ /*!50003 TRIGGER `uuid_transactionId` BEFORE INSERT ON `tblt_transaction` FOR EACH ROW BEGIN
  IF new.trxId IS NULL THEN
    SET new.trxId = uuid();
  END IF;
END */;;
DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE */;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
