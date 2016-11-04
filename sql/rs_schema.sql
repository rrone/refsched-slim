-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 04, 2016 at 05:55 PM
-- Server version: 5.6.33-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wp_ayso1ref`
--
CREATE DATABASE IF NOT EXISTS `wp_ayso1ref` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `wp_ayso1ref`;

-- --------------------------------------------------------

--
-- Table structure for table `rs_ajax_example`
--

CREATE TABLE IF NOT EXISTS `rs_ajax_example` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `wpm` int(11) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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
  `locked` tinyint(1) DEFAULT '1',
  `view` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `num_refs` int(11) DEFAULT '3',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `id` (`id`),
  UNIQUE KEY `id_2` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rs_games`
--

CREATE TABLE IF NOT EXISTS `rs_games` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectKey` varchar(45) NOT NULL,
  `game_number` int(11) NOT NULL,
  `date` date NOT NULL,
  `field` varchar(45) DEFAULT NULL,
  `time` time DEFAULT NULL,
  `division` varchar(45) DEFAULT NULL,
  `pool` varchar(3) DEFAULT NULL,
  `home` varchar(45) DEFAULT NULL,
  `home_team` varchar(45) NOT NULL,
  `away` varchar(45) DEFAULT NULL,
  `away_team` varchar(45) NOT NULL,
  `assignor` varchar(45) DEFAULT NULL,
  `cr` varchar(45) DEFAULT NULL,
  `ar1` varchar(45) DEFAULT NULL,
  `ar2` varchar(45) DEFAULT NULL,
  `r4th` varchar(45) DEFAULT NULL,
  `medalRound` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=521 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rs_limits`
--

CREATE TABLE IF NOT EXISTS `rs_limits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectKey` varchar(45) NOT NULL,
  `division` varchar(10) NOT NULL,
  `limit` varchar(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `rs_log`
--

CREATE TABLE IF NOT EXISTS `rs_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` datetime NOT NULL,
  `projectKey` varchar(45) CHARACTER SET utf8mb4 NOT NULL,
  `note` varchar(4096) CHARACTER SET utf8mb4 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_2` (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `rs_users`
--

CREATE TABLE IF NOT EXISTS `rs_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` char(255) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `hash` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
