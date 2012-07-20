-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2012 at 01:44 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.3-7+squeeze13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `tali`
--

-- --------------------------------------------------------

--
-- Table structure for table `building`
--

CREATE TABLE `building` (
  `buildingID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `infoLink` varchar(100) DEFAULT NULL,
  `description` text,
  `movieID` int(11) DEFAULT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `categoryID` int(11) DEFAULT NULL,
  `adres` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`buildingID`),
  KEY `movieID` (`movieID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `device`
--

CREATE TABLE `device` (
  `deviceID` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(50) NOT NULL,
  PRIMARY KEY (`deviceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

-- --------------------------------------------------------

--
-- Table structure for table `movie`
--

CREATE TABLE `movie` (
  `movieID` int(11) NOT NULL AUTO_INCREMENT,
  `movie` varchar(50) NOT NULL,
  `dateTime` datetime NOT NULL,
  `qrID` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`movieID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

-- --------------------------------------------------------

--
-- Table structure for table `must_sees`
--

CREATE TABLE `must_sees` (
  `buildingID` int(11) NOT NULL,
  `deviceID` int(11) NOT NULL,
  PRIMARY KEY (`buildingID`,`deviceID`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `building`
--
ALTER TABLE `building`
  ADD CONSTRAINT `building_ibfk_1` FOREIGN KEY (`movieID`) REFERENCES `movie` (`movieID`),
  ADD CONSTRAINT `building_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE SET NULL;
