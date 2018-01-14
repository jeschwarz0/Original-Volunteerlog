-- MySQL dump 10.13  Distrib 5.1.41, for Win32 (ia32)
--
-- Host: .    Database: VolunteerLog
-- ------------------------------------------------------
-- Server version	5.1.41

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
-- Table structure for table `checkin`
--

DROP TABLE IF EXISTS `checkin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `checkin` (
  `CheckID` int(11) NOT NULL,
  `VolunteerID` int(11) NOT NULL,
  `TimeIn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Active` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`CheckID`),
  KEY `VolunteerID` (`VolunteerID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Temporary table structure for view `vcheckins`
--

DROP TABLE IF EXISTS `vcheckins`;
/*!50001 DROP VIEW IF EXISTS `vcheckins`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vcheckins` (
  `FirstName` varchar(45),
  `LastName` varchar(45),
  `TimeIn` timestamp,
  `Active` tinyint(1)
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Temporary table structure for view `vlogs`
--

DROP TABLE IF EXISTS `vlogs`;
/*!50001 DROP VIEW IF EXISTS `vlogs`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE TABLE `vlogs` (
  `FirstName` varchar(45),
  `LastName` varchar(45),
  `Date` date,
  `TimeIn` time,
  `TimeOut` time,
  `TotalHours` decimal(5,2),
  `Comment` mediumtext,
  `Class` varchar(5),
  `Office` varchar(5),
  `Maintenance` varchar(5),
  `Conditioning` varchar(5),
  `HorseCare` varchar(5),
  `Committee` varchar(5),
  `Board` varchar(5),
  `JrVolunteer` varchar(5),
  `SpecialOlympics` varchar(5),
  `Other` varchar(5),
  `OtherDescription` mediumtext
) ENGINE=MyISAM */;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `volunteer`
--

DROP TABLE IF EXISTS `volunteer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteer` (
  `VolunteerID` int(11) NOT NULL,
  `FirstName` varchar(45) NOT NULL,
  `LastName` varchar(45) NOT NULL,
  PRIMARY KEY (`VolunteerID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tDeleteVolunteer AFTER DELETE ON Volunteer
FOR EACH ROW
BEGIN
 DELETE FROM VolunteerLog WHERE VolunteerID=OLD.VolunteerID;
 DELETE FROM CheckIn WHERE VolunteerID=OLD.VolunteerID;
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `volunteerlog`
--

DROP TABLE IF EXISTS `volunteerlog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteerlog` (
  `LogID` int(11) NOT NULL,
  `VolunteerID` int(11) NOT NULL,
  `TaskID` int(11) NOT NULL,
  `Date` date NOT NULL,
  `TimeIn` time NOT NULL,
  `TimeOut` time NOT NULL,
  `TotalHours` decimal(5,2) DEFAULT NULL,
  `Comment` mediumtext,
  PRIMARY KEY (`LogID`),
  KEY `VolunteerID` (`VolunteerID`),
  KEY `TaskID` (`TaskID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tInsertVolunteerLog
BEFORE INSERT ON volunteerlog
FOR EACH ROW
BEGIN
 SET NEW.TotalHours = (HOUR(SUBTIME(NEW.TimeOut,NEW.TimeIn))+(CAST(MINUTE(SUBTIME(NEW.TimeOut,NEW.TimeIn))/60 AS DECIMAL(2,2))));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;
/*!50003 SET @saved_cs_client      = @@character_set_client */ ;
/*!50003 SET @saved_cs_results     = @@character_set_results */ ;
/*!50003 SET @saved_col_connection = @@collation_connection */ ;
/*!50003 SET character_set_client  = utf8 */ ;
/*!50003 SET character_set_results = utf8 */ ;
/*!50003 SET collation_connection  = utf8_general_ci */ ;
/*!50003 SET @saved_sql_mode       = @@sql_mode */ ;
/*!50003 SET sql_mode              = '' */ ;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`localhost`*/ /*!50003 TRIGGER tUpdateVolunteerLog
BEFORE UPDATE ON volunteerlog
FOR EACH ROW
BEGIN
 SET NEW.TotalHours = (HOUR(SUBTIME(NEW.TimeOut,NEW.TimeIn))+(CAST(MINUTE(SUBTIME(NEW.TimeOut,NEW.TimeIn))/60 AS DECIMAL(2,2))));
END */;;
DELIMITER ;
/*!50003 SET sql_mode              = @saved_sql_mode */ ;
/*!50003 SET character_set_client  = @saved_cs_client */ ;
/*!50003 SET character_set_results = @saved_cs_results */ ;
/*!50003 SET collation_connection  = @saved_col_connection */ ;

--
-- Table structure for table `volunteertask`
--

DROP TABLE IF EXISTS `volunteertask`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `volunteertask` (
  `TaskID` int(11) NOT NULL,
  `Class` tinyint(1) NOT NULL,
  `Office` tinyint(1) NOT NULL,
  `Maintenance` tinyint(1) NOT NULL,
  `Conditioning` tinyint(1) NOT NULL,
  `HorseCare` tinyint(1) NOT NULL,
  `Committee` tinyint(1) NOT NULL,
  `Board` tinyint(1) NOT NULL,
  `JrVolunteer` tinyint(1) NOT NULL,
  `SpecialOlympics` tinyint(1) NOT NULL,
  `Other` tinyint(1) NOT NULL,
  `OtherDescription` mediumtext,
  PRIMARY KEY (`TaskID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Final view structure for view `vcheckins`
--

/*!50001 DROP TABLE IF EXISTS `vcheckins`*/;
/*!50001 DROP VIEW IF EXISTS `vcheckins`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vcheckins` AS select `v`.`FirstName` AS `FirstName`,`v`.`LastName` AS `LastName`,`c`.`TimeIn` AS `TimeIn`,`c`.`Active` AS `Active` from (`volunteer` `v` join `checkin` `c` on((`c`.`VolunteerID` = `v`.`VolunteerID`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `vlogs`
--

/*!50001 DROP TABLE IF EXISTS `vlogs`*/;
/*!50001 DROP VIEW IF EXISTS `vlogs`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vlogs` AS select `v`.`FirstName` AS `FirstName`,`v`.`LastName` AS `LastName`,`vl`.`Date` AS `Date`,`vl`.`TimeIn` AS `TimeIn`,`vl`.`TimeOut` AS `TimeOut`,`vl`.`TotalHours` AS `TotalHours`,`vl`.`Comment` AS `Comment`,if(`vt`.`Class`,'TRUE','FALSE') AS `Class`,if(`vt`.`Office`,'TRUE','FALSE') AS `Office`,if(`vt`.`Maintenance`,'TRUE','FALSE') AS `Maintenance`,if(`vt`.`Conditioning`,'TRUE','FALSE') AS `Conditioning`,if(`vt`.`HorseCare`,'TRUE','FALSE') AS `HorseCare`,if(`vt`.`Committee`,'TRUE','FALSE') AS `Committee`,if(`vt`.`Board`,'TRUE','FALSE') AS `Board`,if(`vt`.`JrVolunteer`,'TRUE','FALSE') AS `JrVolunteer`,if(`vt`.`SpecialOlympics`,'TRUE','FALSE') AS `SpecialOlympics`,if(`vt`.`Other`,'TRUE','FALSE') AS `Other`,`vt`.`OtherDescription` AS `OtherDescription` from ((`volunteerlog` `vl` join `volunteer` `v` on((`v`.`VolunteerID` = `vl`.`VolunteerID`))) join `volunteertask` `vt` on((`vt`.`TaskID` = `vl`.`TaskID`))) */;
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

-- Dump completed on 2013-04-03 19:12:00
