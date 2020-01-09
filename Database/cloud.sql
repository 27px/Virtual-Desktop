-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 09, 2020 at 04:52 AM
-- Server version: 5.6.16
-- PHP Version: 5.5.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `cloud`
--

-- --------------------------------------------------------

--
-- Table structure for table `apps`
--

CREATE TABLE IF NOT EXISTS `apps` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(30) NOT NULL COMMENT 'App Name',
  `IndexURL` varchar(150) DEFAULT NULL COMMENT 'Source Location',
  `User` varchar(35) NOT NULL COMMENT 'Owner of App',
  `Status` varchar(30) DEFAULT 'In Development' COMMENT 'App Approval Status',
  `Description` varchar(200) DEFAULT NULL COMMENT 'App Description',
  `Keyword1` varchar(30) DEFAULT NULL COMMENT 'Search Keyword 1',
  `Keyword2` varchar(30) DEFAULT NULL COMMENT 'Search Keyword 2',
  `Keyword3` varchar(30) DEFAULT NULL COMMENT 'Search Keyword 3',
  `icon` varchar(150) DEFAULT 'icon_default.svg',
  `Category` varchar(30) DEFAULT NULL,
  `width` int(11) DEFAULT '800',
  `height` int(11) DEFAULT '500',
  `min-width` int(11) DEFAULT '800',
  `min-height` int(11) DEFAULT '500',
  `max-width` int(11) DEFAULT '0',
  `max-height` int(11) DEFAULT '0',
  `Version` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 COMMENT='App Store' AUTO_INCREMENT=12 ;

--
-- Dumping data for table `apps`
--

INSERT INTO `apps` (`ID`, `Name`, `IndexURL`, `User`, `Status`, `Description`, `Keyword1`, `Keyword2`, `Keyword3`, `icon`, `Category`, `width`, `height`, `min-width`, `min-height`, `max-width`, `max-height`, `Version`) VALUES
(1, 'Dictionary', '', 'rahulr0047@gmail.com', 'Approved', 'Simple English Dictionary', 'English Meaning', 'English', 'Meaning', 'icon_Dictionary_1.png', 'Education', 800, 400, 725, 400, 0, 0, 1),
(2, 'Editor', '', 'rahulr0047@gmail.com', 'Approved', 'Text Based File Editor : TXT HTM HTML JS CSS XML JSON SVG PHP', 'Reader', 'Editor', 'Preview Text Editor', 'icon_Editor_2.png', 'Education', 1000, 400, 1000, 400, 0, 0, 2),
(3, 'Memory Game', '', 'rahulr0047@gmail.com', 'Approved', 'Increase Mind & Memory Power', 'Mind Strength', 'Memory Game', 'Increase Memory', 'icon_Memory_Game_3.svg', 'Game', 1100, 500, 1100, 500, 0, 0, 2),
(4, 'Snake Game', '', 'rahulr0047@gmail.com', 'Requested', 'Classic Snake Game', 'Puzzle', 'Snake Game', 'Gaming', 'icon_Snake_Game_4.png', 'Game', 402, 405, 402, 405, 450, 450, 1),
(6, 'Quick Note', '', 'twentysevenpixels@gmail.com', 'Requested', 'New Description Test', 'Keyword test1', 'Edit', 'Text TXT', 'icon_Quick_Note_6.svg', 'System', 500, 250, 300, 100, 800, 500, 1),
(7, 'Image Viewer', '', 'rahulr0047@gmail.com', 'Approved', 'Image Viewer', 'Gallery', 'Picture', 'JPG SVG PNG GIF', 'icon_image.svg', 'System', 800, 500, 400, 400, 0, 0, 4),
(11, 'Calendar', NULL, 'rahulr0047@gmail.com', 'Requested', 'Calendar', 'Calendar', 'Date', 'Year', 'icon_Calendar_11.png', 'System', 300, 400, 350, 495, 350, 525, 8);

-- --------------------------------------------------------

--
-- Table structure for table `editor`
--

CREATE TABLE IF NOT EXISTS `editor` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `User` varchar(50) NOT NULL,
  `MenuBar` varchar(50) NOT NULL,
  `Editor` varchar(50) NOT NULL,
  `AccentColor` varchar(50) NOT NULL,
  `Theme` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `editor`
--

INSERT INTO `editor` (`id`, `User`, `MenuBar`, `Editor`, `AccentColor`, `Theme`) VALUES
(1, 'rahulr0047@gmail.com', 'Serif', 'Monospace', 'Cyan', 'Dark');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE IF NOT EXISTS `feedback` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(35) DEFAULT NULL,
  `EMail` varchar(35) DEFAULT NULL,
  `Message` varchar(500) NOT NULL,
  `Status` varchar(5) NOT NULL DEFAULT 'false',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`ID`, `Name`, `EMail`, `Message`, `Status`) VALUES
(1, 'Ajay V J', 'ajayvj72@gmail.com', 'Nice Environment, Great Concept', 'false'),
(2, 'Suvin', 'suvinskumar@gmail.com', 'Cool Concept', 'false'),
(3, 'Christy', 'christyj23@gmail.com', 'Best Cloud Storage Application', 'false'),
(4, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(5, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(6, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(7, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(8, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(9, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(10, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(11, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(12, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(13, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(14, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(15, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(16, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(17, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(18, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(19, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(20, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(21, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(22, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(23, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(24, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(25, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(26, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(27, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(28, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(29, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false'),
(30, 'Abi S John', 'abistephenjohn34@gmail.com', 'Nice App, Very Cool Application, Loved it...', 'false');

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

CREATE TABLE IF NOT EXISTS `login` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `EMail` varchar(35) NOT NULL,
  `Password` varchar(20) NOT NULL,
  `User` varchar(10) DEFAULT NULL,
  `Profile` varchar(5) NOT NULL DEFAULT 'jpg',
  `Block` varchar(10) DEFAULT 'Allowed' COMMENT 'Temporary/Permanent',
  `FName` varchar(30) DEFAULT NULL,
  `LName` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `login`
--

INSERT INTO `login` (`ID`, `EMail`, `Password`, `User`, `Profile`, `Block`, `FName`, `LName`) VALUES
(1, 'rahulr0047@gmail.com', '1234567890', 'Manager', 'jpg', 'Allowed', 'Rahul', 'R'),
(2, 'ronin@gmail.com', '1234567890', 'User', 'jpg', 'Allowed', 'Ronin', 'R'),
(3, 'administrator@gmail.com', '1234567890', 'Admin', 'svg', 'Allowed', 'Admin', 'Admin'),
(4, 'misteriogreen34@gmail.com', '1234567890', NULL, '1.jpg', 'Allowed', 'Misterio', 'Green');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE IF NOT EXISTS `notification` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User` varchar(35) NOT NULL,
  `Notification` varchar(500) NOT NULL,
  `Status` varchar(5) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `verification`
--

CREATE TABLE IF NOT EXISTS `verification` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `User` varchar(35) NOT NULL,
  `OTP` varchar(5) NOT NULL,
  `Type` varchar(10) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `verification`
--

INSERT INTO `verification` (`ID`, `User`, `OTP`, `Type`) VALUES
(1, 'misteriogreen34@gmail.com', 'uBdfx', 'verify');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
