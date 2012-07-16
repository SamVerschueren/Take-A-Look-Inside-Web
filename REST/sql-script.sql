-- phpMyAdmin SQL Dump
-- version 3.3.8.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 16, 2012 at 06:40 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.3-7+squeeze13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

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
  `picture` varchar(50) NOT NULL,
  `infoLink` varchar(100) DEFAULT NULL,
  `description` text,
  `openingHours` text,
  `mustSee` int(11) DEFAULT '0',
  `seen` int(11) DEFAULT '0',
  `movieID` int(11) NOT NULL,
  `longitude` decimal(10,7) NOT NULL,
  `latitude` decimal(10,7) NOT NULL,
  `categoryID` int(11) DEFAULT NULL,
  PRIMARY KEY (`buildingID`),
  KEY `movieID` (`movieID`),
  KEY `categoryID` (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `building`
--

INSERT INTO `building` VALUES(2, 'Stadhuis', 'http://www.gent.be/pics/Monumenten/stadhuis.JPG', 'http://www.gent.be/eCache/THE/1/464.cmVjPTQ0NTUx.html', 'Sinds 1301 was de Gentse magistraat samengesteld uit enerzijds 13 schepenen van de Keure die belast waren met het effectieve bestuur van de stad en anderzijds 13 schepenen van Gedele die zich bezighielden met erfenis- en voogdijkwesties. Kort daarop besliste het stadsbestuur drie panden langs de Hoogpoort aan te kopen; het begin van wat zou uitgroeien tot het huidig stadhuiscomplex.', NULL, 0, 0, 1, 3.7254270, 51.0544520, 1);
INSERT INTO `building` VALUES(3, 'Belfort', 'http://upload.wikimedia.org/wikipedia/commons/thum', 'http://www.belfortgent.be/', 'Het belfort van Gent is een 95-meter hoge belforttoren in het centrum van de Belgische stad Gent. De toren is de middelste toren van de beroemde Gentse torenrij, samen met de Sint-Niklaaskerk en de Sint-Baafskathedraal. Tegen het belfort staat ook de Gentse lakenhal.', NULL, 0, 0, 1, 3.7252200, 51.0538500, 2);
INSERT INTO `building` VALUES(4, 'Sint-Baafskathedraal', 'http://upload.wikimedia.org/wikipedia/commons/thum', 'http://www.sintbaafskathedraal.be/', 'De Sint-Baafskathedraal in de Vlaamse stad Gent is de titelkerk van het bisdom Gent en het Sint-Baafskapittel.', NULL, 0, 0, 1, 3.7266800, 51.0528300, 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `categoryID` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  PRIMARY KEY (`categoryID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data for table `category`
--

INSERT INTO `category` VALUES(1, 'Cultuur');
INSERT INTO `category` VALUES(2, 'Historisch');

-- --------------------------------------------------------

--
-- Table structure for table `devices`
--

CREATE TABLE `devices` (
  `deviceID` int(11) NOT NULL AUTO_INCREMENT,
  `device` varchar(50) NOT NULL,
  PRIMARY KEY (`deviceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `devices`
--


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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `movie`
--

INSERT INTO `movie` VALUES(1, 'kerstballen.3gp', '2012-07-10 00:00:00', 'MjAxMjA3MTYxNTQ4LXRlc3QubXA0');

-- --------------------------------------------------------

--
-- Table structure for table `must_sees`
--

CREATE TABLE `must_sees` (
  `buildingID` int(11) NOT NULL,
  `deviceID` int(11) NOT NULL,
  PRIMARY KEY (`buildingID`,`deviceID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1;

--
-- Dumping data for table `must_sees`
--


--
-- Constraints for dumped tables
--

--
-- Constraints for table `building`
--
ALTER TABLE `building`
  ADD CONSTRAINT `building_ibfk_1` FOREIGN KEY (`movieID`) REFERENCES `movie` (`movieID`),
  ADD CONSTRAINT `building_ibfk_2` FOREIGN KEY (`categoryID`) REFERENCES `category` (`categoryID`) ON DELETE SET NULL;

