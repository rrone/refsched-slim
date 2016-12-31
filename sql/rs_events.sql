-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 31, 2016 at 10:13 PM
-- Server version: 5.6.33-0ubuntu0.14.04.1
-- PHP Version: 7.0.13-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp_ayso1ref`
--

-- --------------------------------------------------------

--
-- Table structure for table `rs_events`
--

CREATE TABLE IF NOT EXISTS `rs_events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectKey` varchar(45) NOT NULL,
  `name` varchar(255) NOT NULL,
  `dates` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `infoLink` varchar(255) DEFAULT NULL,
  `locked` tinyint(1) DEFAULT '1',
  `view` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `show_medal_round` tinyint(1) DEFAULT '0',
  `label` varchar(255) DEFAULT NULL,
  `num_refs` int(11) DEFAULT '3',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `field_map` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rs_events`
--

INSERT INTO `rs_events` (`id`, `projectKey`, `name`, `dates`, `location`, `infoLink`, `locked`, `view`, `enabled`, `show_medal_round`, `label`, `num_refs`, `start_date`, `end_date`, `field_map`) VALUES
(1, '2015U16U19Chino', 'Section Upper Division Playoffs, U16 and U19', 'November 21-22, 2015', 'Ayala Park, Chino', NULL, 1, 0, 0, 0, 'Nov 21 and 22, 2015:Section Upper Division Playoffs, U16 and U19', 3, '2015-11-21', '2015-11-22', 'Ayala_layout.pdf'),
(2, '2016AllStarExtraPlayoffs', 'Section All Star and Extra Playoffs', 'February 20-21, 2016', 'Ab Brown Soccer Complex, Riverside', NULL, 1, 0, 0, 0, 'Feb 20 and 21, 2016:Section All Star and Extra Playoffs', 3, '2016-02-20', '2016-02-21', 'ab_brown_field_map.pdf'),
(3, '2016U16U19Chino', 'U16/U19 Playoffs', 'November 19-20, 2016', 'Ayala Park, Chino', 'https://ayso.bluesombrero.com/Default.aspx?tabid=862607', 1, 1, 0, 0, 'November 19-20, 2016:U16/U19 Playoffs', 3, '2016-11-19', '2016-11-20', 'Ayala_layout.pdf'),
(4, '2016WSC', 'Western States Championships', 'March 19-20, 2016', 'Bullhead City, AZ', NULL, 1, 0, 0, 0, 'Mar 19 and 20, 2016:Western States Championships', 4, '2016-03-19', '2016-03-20', '2016_wsc_fields.pdf'),
(5, '2017AllStarPlayoffs', 'U10-U14 All-Star Playoffs', 'March 11-12, 2017', 'Ab Brown Soccer Complex, Riverside', 'https://ayso.bluesombrero.com/Default.aspx?tabid=863119', 0, 1, 0, 0, 'March 11-12, 2017:U10-U14 All-Star Playoffs', 3, '2017-03-11', '2017-03-12', 'ab_brown_field_map.pdf'),
(6, '2017LeaguePlayoffs', 'U10-U14 League Playoffs', 'February 25-26, 2017', 'Ab Brown Soccer Complex, Riverside', 'https://ayso.bluesombrero.com/Default.aspx?tabid=863118', 0, 1, 0, 0, 'February 25-26, 2017:U10-U14 League Playoffs', 3, '2017-02-25', '2017-02-25', 'ab_brown_field_map.pdf'),
(7, '2017ExtraPlayoffs', 'U09-U14 Extra Playoffs', 'January 28-29, 2017', 'Columbia Park, Torrance', 'https://ayso.bluesombrero.com/Default.aspx?tabid=862961', 1, 1, 1, 1, 'January 28-29, 2017:U09-U14 Extra Playoffs', 3, '2017-01-28', '2017-01-29', '2017_extra_map.pdf'),
(8, '2017WSC', 'Western States Championships', 'March 25-26, 2017', 'Carson City, NV', NULL, 0, 1, 0, 0, 'March 25-26, 2017:Western States Championships', 4, '2017-03-25', '2017-03-26', NULL),
(9, '2016LeaguePlayoffs', 'Section League Playoffs', 'February 27-28, 2016', 'Ab Brown Soccer Complex, Riverside', NULL, 1, 0, 0, 0, 'Feb 27 and 28, 2016:Section League Playoffs', 3, '2016-02-27', '2016-02-28', 'ab_brown_field_map.pdf');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
