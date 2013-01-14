-- MySQL dump 10.13  Distrib 5.1.41, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: dj_info
-- ------------------------------------------------------
-- Server version	5.1.41-3ubuntu12.10-log

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
-- Current Database: `dj_info`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `dj_info` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `dj_info`;

--
-- Table structure for table `DISCIPLINE`
--

DROP TABLE IF EXISTS `DISCIPLINE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DISCIPLINE` (
  `ID` int(10) unsigned NOT NULL,
  `DJ_ID` int(10) unsigned NOT NULL,
  `DDAY` int(10) unsigned NOT NULL,
  `DMONTH` int(10) unsigned NOT NULL,
  `DYEAR` int(10) unsigned NOT NULL,
  `DESCRIPTION` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `DJ`
--

DROP TABLE IF EXISTS `DJ`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `DJ` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `F_NAME` text NOT NULL,
  `L_NAME` text NOT NULL,
  `YEAR_JOINED` int(10) unsigned NOT NULL,
  `SENIORITY_OFFSET` int(10) unsigned NOT NULL,
  `EMAIL` text NOT NULL,
  `PHONE` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SEMESTER`
--

DROP TABLE IF EXISTS `SEMESTER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SEMESTER` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `TITLE` text NOT NULL,
  `CREATION_TIME` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `SHOW`
--

DROP TABLE IF EXISTS `SHOW`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `SHOW` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SEMESTER_ID` int(10) unsigned NOT NULL,
  `DJ_ID` int(10) unsigned NOT NULL,
  `NAME` text NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `VOLUNTEER`
--

DROP TABLE IF EXISTS `VOLUNTEER`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `VOLUNTEER` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `SEMESTER_ID` int(10) unsigned NOT NULL,
  `DJ_ID` int(10) unsigned NOT NULL,
  `NUM_HOURS` int(10) unsigned NOT NULL,
  `DDAY` int(10) unsigned NOT NULL,
  `DMONTH` int(10) unsigned NOT NULL,
  `DYEAR` int(10) unsigned NOT NULL,
  `DESCRIPTION` text NOT NULL,
  `SUB_BOOL` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2012-09-25 19:24:36
