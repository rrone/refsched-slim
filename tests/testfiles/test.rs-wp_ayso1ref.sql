CREATE DATABASE  IF NOT EXISTS `wp_ayso1ref` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `wp_ayso1ref`;
-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.2.2:3307
-- Generation Time: Oct 25, 2017 at 05:52 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.10-1+ubuntu14.04.1+deb.sury.org+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
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

DROP TABLE IF EXISTS `rs_events`;
CREATE TABLE `rs_events` (
  `id` int(11) NOT NULL,
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
  `field_map` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `rs_events`
--

TRUNCATE TABLE `rs_events`;
--
-- Dumping data for table `rs_events`
--

INSERT INTO `rs_events` (`id`, `projectKey`, `name`, `dates`, `location`, `infoLink`, `locked`, `view`, `enabled`, `show_medal_round`, `label`, `num_refs`, `start_date`, `end_date`, `field_map`) VALUES
(3, '2016U16U19Chino', 'U16/U19 Playoffs', 'November 19-20, 2016', 'Ayala Park, Chino', 'https://ayso.bluesombrero.com/Default.aspx?tabid=862607', 0, 1, 0, 0, 'U16/U19 Playoffs: November 19-20, 2016', 3, '2016-11-19', '2016-11-20', 'Ayala_layout.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `rs_games`
--

DROP TABLE IF EXISTS `rs_games`;
CREATE TABLE `rs_games` (
  `id` int(11) NOT NULL,
  `projectKey` varchar(45) NOT NULL,
  `game_number` int(11) NOT NULL,
  `date` date NOT NULL,
  `time` time DEFAULT NULL,
  `field` varchar(45) DEFAULT NULL,
  `division` varchar(45) DEFAULT NULL,
  `pool` varchar(3) DEFAULT NULL,
  `home` varchar(45) DEFAULT NULL,
  `home_team` varchar(45) DEFAULT NULL,
  `away` varchar(45) DEFAULT NULL,
  `away_team` varchar(45) DEFAULT NULL,
  `assignor` varchar(45) DEFAULT NULL,
  `cr` varchar(45) DEFAULT NULL,
  `ar1` varchar(45) DEFAULT NULL,
  `ar2` varchar(45) DEFAULT NULL,
  `r4th` varchar(45) DEFAULT NULL,
  `medalRound` tinyint(1) DEFAULT '0',
  `locked` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `rs_games`
--

TRUNCATE TABLE `rs_games`;
--
-- Dumping data for table `rs_games`
--

INSERT INTO `rs_games` (`id`, `projectKey`, `game_number`, `date`, `time`, `field`, `division`, `pool`, `home`, `home_team`, `away`, `away_team`, `assignor`, `cr`, `ar1`, `ar2`, `r4th`, `medalRound`, `locked`) VALUES
(457, '2016U16U19Chino', 1, '0000-00-00', '00:00:00', 'Ayala 7', 'U19G', '1', 'R1', '', 'C2', '', '', '', '', '', '', 0, 1),
(458, '2016U16U19Chino', 2, '0000-00-00', '08:00:00', 'Ayala 8', 'U19G', '2', 'N1', '', 'B2', '', 'Area 1F', 'Herb Countee', 'Michael Feder', 'Won Song', '', 0, 1),
(459, '2016U16U19Chino', 3, '0000-00-00', '08:00:00', 'Ayala 9', 'U19G', '3', 'B1', '', 'D1', '', 'Area 1H', 'Chris Salmon', 'Jose Macias', 'Alfred Medina', '', 0, 1),
(460, '2016U16U19Chino', 4, '0000-00-00', '08:00:00', 'Ayala 10', 'U19G', '4', 'G1', '', 'P1', '', 'Area 1D', 'Craig Breitman', 'Merit Shoucri', 'Scott Jarus', '', 0, 1),
(461, '2016U16U19Chino', 5, '0000-00-00', '08:00:00', 'Ayala 11', 'U16G', '1', 'D1', '', 'U1', '', 'Area 1C', 'Lincoln Wallen', 'Kareem Badaruddin', 'Will Hardy', '', 0, 1),
(462, '2016U16U19Chino', 6, '0000-00-00', '08:00:00', 'Ayala 12', 'U16G', '2', 'P1', '', 'N1', '', 'Area 1R', 'Ramon Guzman', 'Joseph Marconi', 'Lee Lombard', '', 0, 1),
(463, '2016U16U19Chino', 7, '0000-00-00', '08:00:00', 'Ayala 13', 'U16G', '3', 'B1', '', 'N2', '', 'Area 1P', 'John Burgee', 'Chris Nevil', 'Scott Karlan', '', 0, 1),
(464, '2016U16U19Chino', 8, '0000-00-00', '08:00:00', 'Ayala 14', 'U16G', '4', 'H1', '', 'R2', '', 'Area 1G', 'Glenn Schwartzberg', 'Sandy Wright', 'Michael Sanchez', '', 0, 1),
(465, '2016U16U19Chino', 9, '0000-00-00', '09:20:00', 'Ayala 7', 'U19B', '1', 'G2', '', 'R1', '', 'Area 1B', 'John Meehan', 'Pat Cary', 'Eloy Loera', '', 0, 1),
(466, '2016U16U19Chino', 10, '0000-00-00', '09:20:00', 'Ayala 8', 'U19B', '2', 'N1', '', 'H1', '', 'Area 1F', 'Michael Feder', 'Herb Countee', 'Won Song', '', 0, 1),
(467, '2016U16U19Chino', 11, '0000-00-00', '09:20:00', 'Ayala 9', 'U19B', '3', 'C1', '', 'B1', '', 'Area 1H', 'Jose Macias', 'Chris Salmon', 'Alfred Medina', '', 0, 1),
(468, '2016U16U19Chino', 12, '0000-00-00', '09:20:00', 'Ayala 10', 'U19B', '4', 'U1', '', 'G1', '', 'Area 1D', 'Merit Shoucri', 'Craig Breitman', 'Scott Jarus', '', 0, 1),
(469, '2016U16U19Chino', 13, '0000-00-00', '09:20:00', 'Ayala 11', 'U16B', '1', 'R1', '', 'H2', '', 'Area 1C', 'Will Hardy', 'Lincoln Wallen', 'Kareem Badaruddin', '', 0, 1),
(470, '2016U16U19Chino', 14, '0000-00-00', '09:20:00', 'Ayala 12', 'U16B', '2', 'G1', '', 'U1', '', 'Area 1R', 'Stefan Larson', 'Joseph Marconi', 'James Affinito', '', 0, 1),
(471, '2016U16U19Chino', 15, '0000-00-00', '09:20:00', 'Ayala 13', 'U16B', '3', 'B1', '', 'C1', '', 'Area 1P', 'Chris Nevil', 'Scott Karlan', 'John Burgee', '', 0, 1),
(472, '2016U16U19Chino', 16, '0000-00-00', '09:20:00', 'Ayala 14', 'U16B', '4', 'H1', '', 'N1', '', 'Area 1B', '', '', '', '', 0, 1),
(473, '2016U16U19Chino', 17, '0000-00-00', '10:40:00', 'Ayala 7', 'U19G', '1', 'D2', '', 'R1', '', 'Area 1B', '', '', '', '', 0, 0),
(474, '2016U16U19Chino', 18, '0000-00-00', '10:40:00', 'Ayala 8', 'U19G', '2', 'C1', '', 'N1', '', 'Area 1G', 'Jeff Johnston', 'Lealon Watts', 'Michael Hays', '', 0, 1),
(475, '2016U16U19Chino', 19, '0000-00-00', '10:40:00', 'Ayala 9', 'U19G', '3', 'F1', '', 'B1', '', 'Area 1R', 'Steven Chandler', 'Lee Lombard', 'Joseph Marconi', '', 0, 1),
(476, '2016U16U19Chino', 20, '0000-00-00', '10:40:00', 'Ayala 10', 'U19G', '4', 'U1', '', 'G1', '', 'Area 1D', 'Scott Jarus', 'Merit Shoucri', 'Craig Breitman', '', 0, 1),
(477, '2016U16U19Chino', 21, '0000-00-00', '10:40:00', 'Ayala 11', 'U16G', '1', 'F1', '', 'D1', '', 'Area 1N', 'Matt Hurlbert', 'Gilberto Maldonado', 'Jon Swasey', '', 0, 1),
(478, '2016U16U19Chino', 22, '0000-00-00', '10:40:00', 'Ayala 12', 'U16G', '2', 'R1', '', 'P1', '', 'Area 1U', 'Javier Chagolla', 'Mars Ramage', 'Rob Owen', '', 0, 1),
(479, '2016U16U19Chino', 23, '0000-00-00', '10:40:00', 'Ayala 13', 'U16G', '3', 'G1', '', 'B1', '', 'Area 1R', 'Ed Williams', 'Dawn Hlavac', 'James Affinito', '', 0, 1),
(480, '2016U16U19Chino', 24, '0000-00-00', '10:40:00', 'Ayala 14', 'U16G', '4', 'C1', '', 'H1', '', 'Area 1G', 'Joe Bernier', 'Ramon Guzman', 'Steven Caro', '', 0, 1),
(481, '2016U16U19Chino', 25, '0000-00-00', '12:00:00', 'Ayala 7', 'U19B', '1', 'F2', '', 'G2', '', 'Area 1P', 'Scott Karlan', 'John Burgee', 'Chris Nevil', '', 0, 1),
(482, '2016U16U19Chino', 26, '0000-00-00', '12:00:00', 'Ayala 8', 'U19B', '2', 'D1', '', 'N1', '', 'Area 1G', 'Lealon Watts', 'Jeff Johnston', 'Michael Hays', '', 0, 1),
(483, '2016U16U19Chino', 27, '0000-00-00', '12:00:00', 'Ayala 9', 'U19B', '3', 'F1', '', 'C1', '', 'Area 1R', 'James Hodge', 'Stefan Larson', 'Steven Chandler', '', 0, 1),
(484, '2016U16U19Chino', 28, '0000-00-00', '12:00:00', 'Ayala 10', 'U19B', '4', 'P1', '', 'U1', '', 'Area 1C', 'John Mass', 'Al Prado', 'Scott Davis', '', 0, 1),
(485, '2016U16U19Chino', 29, '0000-00-00', '12:00:00', 'Ayala 11', 'U16B', '1', 'D1', '', 'R1', '', 'Area 1N', 'Gilberto Maldonado', 'Joe Bernier', 'Jon Swasey', '', 0, 1),
(486, '2016U16U19Chino', 30, '0000-00-00', '12:00:00', 'Ayala 12', 'U16B', '2', 'F1', '', 'G1', '', 'Area 1H', 'Manuel Del Rio', 'John Hampson', 'Jose Macias', '', 0, 1),
(487, '2016U16U19Chino', 31, '0000-00-00', '12:00:00', 'Ayala 13', 'U16B', '3', 'P1', '', 'B1', '', 'Area 1U', 'Rob Owen', 'Mars Ramage', 'Javier Chagolla', '', 0, 1),
(488, '2016U16U19Chino', 32, '0000-00-00', '12:00:00', 'Ayala 14', 'U16B', '4', 'C2', '', 'H1', '', 'Area 1F', 'Alan Siegel', 'Gregg Ferguson', 'Michael Wolff', '', 0, 1),
(489, '2016U16U19Chino', 33, '0000-00-00', '13:20:00', 'Ayala 7', 'U19G', '1', 'C2', '', 'D2', '', 'Area 1P', 'Michael Feder', 'Robert Osborne', 'Tim Reynolds', '', 0, 1),
(490, '2016U16U19Chino', 34, '0000-00-00', '13:20:00', 'Ayala 8', 'U19G', '2', 'B2', '', 'C1', '', 'Area 1N', 'Chris Call', 'Forrest Pitts', 'Rob Hurt', '', 0, 1),
(491, '2016U16U19Chino', 35, '0000-00-00', '13:20:00', 'Ayala 9', 'U19G', '3', 'D1', '', 'F1', '', 'Area 1U', 'Mike Rodewald', 'Steve Manriquez', 'Ramon Villar', '', 0, 1),
(492, '2016U16U19Chino', 36, '0000-00-00', '13:20:00', 'Ayala 10', 'U19G', '4', 'P1', '', 'U1', '', 'Area 1C', 'Scott Davis', 'John Mass', 'Al Prado', '', 0, 1),
(493, '2016U16U19Chino', 37, '0000-00-00', '13:20:00', 'Ayala 11', 'U16G', '1', 'U1', '', 'F1', '', 'Area 1B', 'Michel Larcheveque', 'Greg Olsen', 'Chris Koh', '', 0, 1),
(494, '2016U16U19Chino', 38, '0000-00-00', '13:20:00', 'Ayala 12', 'U16G', '2', 'N1', '', 'R1', '', 'Area 1H', 'Albert Blanco', 'John Hampson', 'Manuel Del Rio', '', 0, 1),
(495, '2016U16U19Chino', 39, '0000-00-00', '13:20:00', 'Ayala 13', 'U16G', '3', 'N2', '', 'G1', '', 'Area 1D', 'Jamie Stewart', 'Peter Lindborg', 'Greg Power', '', 0, 1),
(496, '2016U16U19Chino', 40, '0000-00-00', '13:20:00', 'Ayala 14', 'U16G', '4', 'R2', '', 'C1', '', 'Area 1F', 'Michael Wolff', 'Gregg Ferguson', 'Alan Siegel', '', 0, 1),
(497, '2016U16U19Chino', 41, '0000-00-00', '14:40:00', 'Ayala 7', 'U19B', '1', 'R1', '', 'F2', '', 'Area 1P', 'Robert Osborne', 'Tim Reynolds', 'Michael Feder', '', 0, 1),
(498, '2016U16U19Chino', 42, '0000-00-00', '14:40:00', 'Ayala 8', 'U19B', '2', 'H1', '', 'D1', '', 'Area 1N', 'Chris Call', 'Forrest Pitts', 'Rob Hurt', '', 0, 1),
(499, '2016U16U19Chino', 43, '0000-00-00', '14:40:00', 'Ayala 9', 'U19B', '3', 'B1', '', 'F1', '', 'Area 1U', 'Ramon Villar', 'Steve Manriquez', 'Mike Rodewald', '', 0, 1),
(500, '2016U16U19Chino', 44, '0000-00-00', '14:40:00', 'Ayala 10', 'U19B', '4', 'G1', '', 'P1', '', 'Area 1C', 'Al Prado', 'Scott Davis', 'John Mass', '', 0, 1),
(501, '2016U16U19Chino', 45, '0000-00-00', '14:40:00', 'Ayala 11', 'U16B', '1', 'H2', '', 'D1', '', '', '', '', '', '', 0, 1),
(502, '2016U16U19Chino', 46, '0000-00-00', '14:40:00', 'Ayala 12', 'U16B', '2', 'U1', '', 'F1', '', 'Area 1H', 'John Hampson', 'Manuel Del Rio', 'Albert Blanco', '', 0, 1),
(503, '2016U16U19Chino', 47, '0000-00-00', '14:40:00', 'Ayala 13', 'U16B', '3', 'C1', '', 'P1', '', 'Area 1D', 'Peter Lindborg', 'Jamie Stewart', 'Greg Power', '', 0, 1),
(504, '2016U16U19Chino', 48, '0000-00-00', '14:40:00', 'Ayala 14', 'U16B', '4', 'N1', '', 'C2', '', 'Area 1F', 'Gregg Ferguson', 'Michael Wolff', 'Alan Siegel', '', 0, 1),
(505, '2016U16U19Chino', 49, '0000-00-00', '08:30:00', 'Ayala 7', 'U16G', 'SF', 'F1 16 North Torrance', '', 'P1 1031 So. Los Angeles', '', '', '', '', '', '', 1, 1),
(506, '2016U16U19Chino', 50, '0000-00-00', '08:30:00', 'Ayala 8', 'U16G', 'SF', 'B1 31 Diamond Bar', '', 'C1 98 Temple City', '', 'Area 1R', 'Steven Chandler', 'Ed Williams', 'James Hodge', '', 1, 1),
(507, '2016U16U19Chino', 51, '0000-00-00', '08:30:00', 'Ayala 9', 'U16B', 'SF', 'D1 21 Hawthorne', '', 'G1 65 Rancho Cucamonga', '', '', '', '', '', '', 1, 1),
(508, '2016U16U19Chino', 52, '0000-00-00', '08:30:00', 'Ayala 10', 'U16B', 'SF', 'P1 1031 So Los Angeles', '', 'N1 641 Pass Area', '', 'Area 1D', 'Phil Ockelmann', 'Craig Breitman', 'Merit Shoucri', '', 1, 1),
(509, '2016U16U19Chino', 53, '0000-00-00', '10:30:00', 'Ayala 7', 'U19G', 'SF', 'D2 18 Manhattan/Hermosa', '', 'C1 2 Arcadia', '', '', '', '', '', '', 1, 1),
(510, '2016U16U19Chino', 54, '0000-00-00', '10:30:00', 'Ayala 8', 'U19G', 'SF', 'F1 16 North Torrance', '', 'P1 20 Santa Monica', '', 'Area 1D', 'Merit Shoucri', 'Craig Breitman', 'Phil Ockelmann', '', 1, 1),
(511, '2016U16U19Chino', 55, '0000-00-00', '10:30:00', 'Ayala 9', 'U19B', 'SF', 'F2 16 No Torrance', '', 'D1 18 Manhattan/Hermosa', '', 'Area 1G', 'Lealon Watts', 'Greg Hood', 'Jeff Johnston', '', 1, 1),
(512, '2016U16U19Chino', 56, '0000-00-00', '10:30:00', 'Ayala 10', 'U19B', 'SF', 'B1 31 Diamond Bar', '', 'G1 65 Rancho Cucamonga', '', 'Area 1R', 'Ed Williams', 'James Hodge', 'Steven Chandler', '', 1, 1),
(513, '2016U16U19Chino', 57, '0000-00-00', '12:30:00', 'Ayala 7', 'U16G', 'FIN', 'F1 16 North Torrance', '', 'C1 98 Temple City', '', 'Area 1H', 'Patrick Alles', 'Manuel Del Rio', 'Amer Hassouneh', '', 1, 1),
(514, '2016U16U19Chino', 58, '0000-00-00', '12:30:00', 'Ayala 8', 'U16G', 'CON', 'P1 1031 So. Los Angeles', '', 'B1 31 Diamond Bar', '', 'Area 1C', 'Scott Davis', 'Al Prado', 'Steve Hawkins', '', 1, 1),
(515, '2016U16U19Chino', 59, '0000-00-00', '12:30:00', 'Ayala 9', 'U16B', 'FIN', 'D1 21 Hawthorne', '', 'P1 1031 So Los Angeles', '', 'Area 1F', 'Michael Feder', 'Herb Countee', 'Tim Reynolds', '', 1, 1),
(516, '2016U16U19Chino', 60, '0000-00-00', '12:30:00', 'Ayala 10', 'U16B', 'CON', 'G1 65 Rancho Cucamonga', '', 'N1 641 Pass Area', '', 'Area 1P', 'Achikam Shapira', 'Howard Chait', 'Tony Robinson', '', 1, 1),
(517, '2016U16U19Chino', 61, '0000-00-00', '14:30:00', 'Ayala 7', 'U19G', 'FIN', 'D2 18 Manhattan/Hermosa', '', 'F1 16 North Torrance', '', 'Area 1P', 'Howard Chait', 'Achikam Shapira', 'Tony Robinson', '', 1, 1),
(518, '2016U16U19Chino', 62, '0000-00-00', '14:30:00', 'Ayala 8', 'U19G', 'CON', 'C1 2 Arcadia', '', 'P1 20 Santa Monica', '', 'Area 1F', 'Herb Countee', 'David', 'Michael Feder', '', 1, 1),
(519, '2016U16U19Chino', 63, '0000-00-00', '14:30:00', 'Ayala 9', 'U19B', 'FIN', 'D1 18 Manhattan/Hermosa', '', 'G1 65 Rancho Cucamonga', '', 'Area 1C', 'Al Prado', 'Scott Davis', 'Steve Hawkins', '', 1, 1),
(520, '2016U16U19Chino', 64, '0000-00-00', '14:30:00', 'Ayala 10', 'U19B', 'CON', 'F2 16 No Torrance', '', 'B1 31 Diamond Bar', '', 'Area 1H', 'Manuel Del Rio', 'Patrick Alles', 'Amer Hassouneh', '', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `rs_limits`
--

DROP TABLE IF EXISTS `rs_limits`;
CREATE TABLE `rs_limits` (
  `id` int(11) NOT NULL,
  `projectKey` varchar(45) NOT NULL,
  `division` varchar(10) NOT NULL,
  `limit` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `rs_limits`
--

TRUNCATE TABLE `rs_limits`;
--
-- Dumping data for table `rs_limits`
--

INSERT INTO `rs_limits` (`id`, `projectKey`, `division`, `limit`) VALUES
(23, '2016U16U19Chino~', 'U16', '3'),
(24, '2016U16U19Chino', 'U19', '4'),
(44, '2017U10U14LeaguePlayoffs', 'U12', '7'),
(45, '2017U10U14LeaguePlayoffs', 'U13', '7'),
(46, '2017U10U14LeaguePlayoffs', 'U14', '7'),
(49, '2017U9U14ExtraPlayoffs', 'U09', '7'),
(50, '2017WSC', 'U10', '7'),
(51, '2017WSC', 'U11', '7'),
(52, '2017WSC', 'U12', '7'),
(53, '2017WSC', 'U13', '7'),
(54, '2017WSC', 'U14', '7'),
(57, '2016LeaguePlayoffs', 'all', '999');

-- --------------------------------------------------------

--
-- Table structure for table `rs_log`
--

DROP TABLE IF EXISTS `rs_log`;
CREATE TABLE `rs_log` (
  `id` int(11) NOT NULL,
  `timestamp` datetime NOT NULL,
  `projectKey` varchar(45) CHARACTER SET utf8 NOT NULL,
  `note` varchar(1024) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `rs_log`
--

TRUNCATE TABLE `rs_log`;
--
-- Dumping data for table `rs_log`
--

INSERT INTO `rs_log` (`id`, `timestamp`, `projectKey`, `note`) VALUES
(1804, '2017-10-25 17:49:45', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm dispatched'),
(1805, '2017-10-25 17:49:45', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/log dispatched'),
(1806, '2017-10-25 17:49:45', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/template dispatched'),
(1807, '2017-10-25 17:49:45', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1808, '2017-10-25 17:49:45', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1809, '2017-10-25 17:49:46', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1810, '2017-10-25 17:49:47', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1811, '2017-10-25 17:49:47', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1812, '2017-10-25 17:49:47', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1813, '2017-10-25 17:49:47', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1814, '2017-10-25 17:49:47', '2016U16U19Chino', 'Area 1B: Scheduler /editref dispatched with updated ref assignments'),
(1815, '2017-10-25 17:49:47', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1816, '2017-10-25 17:49:47', '2016U16U19Chino', 'Area 1B: Scheduler /editref dispatched with updated ref assignments'),
(1817, '2017-10-25 17:49:47', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1818, '2017-10-25 17:49:47', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editref dispatched with updated ref assignments'),
(1819, '2017-10-25 17:49:48', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1820, '2017-10-25 17:49:48', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editref dispatched with updated ref assignments'),
(1821, '2017-10-25 17:49:48', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1822, '2017-10-25 17:49:48', '2016U16U19Chino', 'Area 1B: Scheduler log off'),
(1823, '2017-10-25 17:49:48', '2016U16U19Chino', 'Area 1B: Scheduler /full dispatched'),
(1824, '2017-10-25 17:49:48', '2016U16U19Chino', 'Area 1B: Scheduler /full no referees view dispatched'),
(1825, '2017-10-25 17:49:48', '2016U16U19Chino', 'Section 1 Admin: Scheduler /full dispatched'),
(1826, '2017-10-25 17:49:48', '2016U16U19Chino', 'Section 1 Admin: Scheduler /full no referees view dispatched'),
(1827, '2017-10-25 17:49:48', '2016U16U19Chino', 'Area 1B: Scheduler /fullexport dispatched'),
(1828, '2017-10-25 17:49:49', '2016U16U19Chino', 'Section 1 Admin: Scheduler /fullexport dispatched'),
(1829, '2017-10-25 17:49:51', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1830, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1831, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1832, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1833, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1834, '2017-10-25 17:49:51', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1835, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1836, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1837, '2017-10-25 17:49:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1838, '2017-10-25 17:49:51', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1839, '2017-10-25 17:49:52', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1840, '2017-10-25 17:49:52', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1841, '2017-10-25 17:49:52', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1842, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1843, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1844, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /hidemr dispatched'),
(1845, '2017-10-25 17:49:53', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1846, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1847, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1848, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1849, '2017-10-25 17:49:53', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1850, '2017-10-25 17:49:53', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1851, '2017-10-25 17:49:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1852, '2017-10-25 17:49:53', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1853, '2017-10-25 17:49:54', '2016U16U19Chino', 'Section 1 Admin: Scheduler /sched dispatched'),
(1854, '2017-10-25 17:49:54', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1855, '2017-10-25 17:49:54', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1856, '2017-10-25 17:49:54', '2016U16U19Chino', 'Area 1B: Scheduler /sched for U16 dispatched'),
(1857, '2017-10-25 17:49:54', '2016U16U19Chino', 'Area 1P: Scheduler /sched dispatched'),
(1858, '2017-10-25 17:50:50', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm dispatched'),
(1859, '2017-10-25 17:50:50', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/log dispatched'),
(1860, '2017-10-25 17:50:50', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/template dispatched'),
(1861, '2017-10-25 17:50:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1862, '2017-10-25 17:50:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1863, '2017-10-25 17:50:51', '2016U16U19Chino', 'Section 1 Admin: Scheduler /adm/import dispatched'),
(1864, '2017-10-25 17:50:52', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1865, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1866, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editgame dispatched'),
(1867, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1868, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /editref dispatched with updated ref assignments'),
(1869, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1870, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /editref dispatched with updated ref assignments'),
(1871, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1872, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editref dispatched with updated ref assignments'),
(1873, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1874, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /editref dispatched with updated ref assignments'),
(1875, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1876, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler log off'),
(1877, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /full dispatched'),
(1878, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /full no referees view dispatched'),
(1879, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /full dispatched'),
(1880, '2017-10-25 17:50:53', '2016U16U19Chino', 'Section 1 Admin: Scheduler /full no referees view dispatched'),
(1881, '2017-10-25 17:50:53', '2016U16U19Chino', 'Area 1B: Scheduler /fullexport dispatched'),
(1882, '2017-10-25 17:50:54', '2016U16U19Chino', 'Section 1 Admin: Scheduler /fullexport dispatched'),
(1883, '2017-10-25 17:50:57', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1884, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1885, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1886, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1887, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1888, '2017-10-25 17:50:57', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1889, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1890, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1891, '2017-10-25 17:50:57', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1892, '2017-10-25 17:50:57', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1893, '2017-10-25 17:50:57', '2016U16U19Chino', 'Area 1B: Scheduler logon'),
(1894, '2017-10-25 17:50:57', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1895, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1896, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1897, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /master dispatched'),
(1898, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /lock dispatched'),
(1899, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1900, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /hidemr dispatched'),
(1901, '2017-10-25 17:50:58', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1902, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1903, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /greet dispatched'),
(1904, '2017-10-25 17:50:58', '2016U16U19Chino', 'Section 1 Admin: Scheduler /unlock dispatched'),
(1905, '2017-10-25 17:50:58', '2016U16U19Chino', 'Area 1B: Scheduler /greet dispatched'),
(1906, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1B: Scheduler /refs dispatched'),
(1907, '2017-10-25 17:50:59', '2016U16U19Chino', 'Section 1 Admin: Scheduler /refs dispatched'),
(1908, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1909, '2017-10-25 17:50:59', '2016U16U19Chino', 'Section 1 Admin: Scheduler /sched dispatched'),
(1910, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1911, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1B: Scheduler /sched dispatched'),
(1912, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1B: Scheduler /sched for U16 dispatched'),
(1913, '2017-10-25 17:50:59', '2016U16U19Chino', 'Area 1P: Scheduler /sched dispatched');

-- --------------------------------------------------------

--
-- Table structure for table `rs_messages`
--

DROP TABLE IF EXISTS `rs_messages`;
CREATE TABLE `rs_messages` (
  `id` int(11) NOT NULL,
  `message` varchar(4096) CHARACTER SET utf8 DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `rs_messages`
--

TRUNCATE TABLE `rs_messages`;
--
-- Dumping data for table `rs_messages`
--

INSERT INTO `rs_messages` (`id`, `message`, `enabled`) VALUES
(2, '<h2>Rest easy...there are no events available to schedule.</h2><h2>Go referee some games yourself.</h2>', 1),
(3, '<h2 style=\"color:red\"><strong><em>26 Jan Update: &nbsp;Columbia Park is closed this weekend due to rains this week<br>\n\nExtra Playoffs will be rescheduled</em></strong></h2>\n\n<div style=\"width: 60%; margin: 0px auto; text-align:justify\">\n<h5>We received notification this morning that, as a result of last week’s unprecedented rainfall, the City of Torrance has closed all the fields at Columbia Park for this weekend. Unfortunately, the EXTRA Playoff matches scheduled for Saturday and Sunday are cancelled.&nbsp;</h5>\n<h5>Please thank your Referees for their service and let them know they are released from their assignments this weekend.</hr> \n<h5>We anticipate rescheduling the games for a later date and will notify the Area Referee Administrators when fields are confirmed.</h5>\n</div>', 0),
(4, '<h2 style=\"color:red\"><strong><em>26 Jan Update: &nbsp;Columbia Park is closed this weekend due to rains this week<br>\n\nExtra Playoffs have been rescheduled</em></strong></h2>\n\n<div style=\"width: 60%; margin: 0px auto; text-align:justify\">\n<h5>We received notification this morning that, as a result of last week’s unprecedented rainfall, the City of Torrance has closed all the fields at Columbia Park for this weekend. Unfortunately, the EXTRA Playoff matches scheduled for Saturday and Sunday are cancelled.&nbsp;</h5>\n<h5>Please thank your Referees for their service and let them know they are released from their assignments this weekend.</hr> \n<h5>The EXTRA PLAYOFFS have been rescheduled for the weekend of March 11 & 12 at Ab Brown in Riverside.  Updated match schedules & assignments will be posted when available.</h5>\n</div>', 0),
(5, '<div style=\"width: 60%; margin: 0px auto; text-align:justify\">\n<h5>Area Assignors, watch your email for your assignments at the Section 1 League Playoffs (25-26 Feb).</h5>\n<h5>All-Star/Extra Playoffs coming up 11-12 Mar.\n</div>', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rs_sar`
--

DROP TABLE IF EXISTS `rs_sar`;
CREATE TABLE `rs_sar` (
  `portalName` varchar(32) NOT NULL,
  `section` varchar(32) NOT NULL,
  `area` varchar(32) NOT NULL,
  `region` varchar(32) NOT NULL,
  `state` varchar(2) NOT NULL,
  `communities` varchar(1024) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Truncate table before insert `rs_sar`
--

TRUNCATE TABLE `rs_sar`;
--
-- Dumping data for table `rs_sar`
--

INSERT INTO `rs_sar` (`portalName`, `section`, `area`, `region`, `state`, `communities`) VALUES
('Region 1', '2', 'N', '1', 'Ca', 'REDWOOD CITY'),
('Region 10', '1', 'F', '10', 'Ca', 'PALOS VERDES ESTATES, PALOS VERDES PENINS., RANCHO PALOS VERDES, ROLLING HILLS, ROLLINGS HILLS EST., TORRANCE, SAN PEDRO'),
('Region 100', '7', 'O', '100', 'Ha', 'KAILUA, WAIMANALO, KANEOHE'),
('Region 1001', '2', 'E', '1001', 'Ne', 'WELLS'),
('Region 1003', '13', 'A', '1003', 'Pe', 'RIDGE-HYNDMAN'),
('Region 1004', '13', 'A', '1004', 'Pe', 'BAKERS SUMMIT, CURRYVILLE, HOPEWELL, LOYSBURG, MARTINSBURG, NEW ENTERPRISE, NORTHERN BEDFORD, ROARING SPRING, WATERSIDE, WOODBURY'),
('Region 1005', '6', 'B', '1005', 'Wi', 'AMERY, DRESSER, NEW RICHMOND, OSCEOLA, SOMERSET, ST CROIX FALLS, STAR PRAIRIE, SHAFER, FRANCONIA, SCANDIA, LINDSTROM, CENTER CITY'),
('Region 1007', '6', 'D', '1007', 'Il', 'DEERFIELD, BANNOCKBURN, RIVERWOODS, LINCOLNSHIRE, HIGHLAND PARK, NORTHBROOK, GLENVIEW'),
('Region 1008', '3', 'G', '1008', 'Ne', 'GOUVERNEUR'),
('Region 1011', '5', 'F', '1011', 'Al', 'GUNTERSVILLE'),
('Region 1012', '7', 'A', '1012', 'Ha', 'KAU  - DISTRICT OF, NAALEHU, OCEAN VIEW, PAHALA, WAIOHINU'),
('Region 1015', '3', 'G', '1015', 'Ne', 'AMES, CANAJOHARIE, PALATINE BRIDGE, SPRAKERS'),
('Region 102', '9', 'I', '102', 'Id', 'AMMON, IDAHO FALLS, IONA, UCON, SHELLEY'),
('Region 1020', '12', 'B', '1020', 'Ar', 'LAKESIDE, PINETOP, WHITE RIVER'),
('Region 1021', '3', 'G', '1021', 'Ne', 'SHARON SPRINGS'),
('Region 1026', '13', 'D', '1026', 'Oh', 'AURORA, MACEDONIA, NORTHFIELD, REMINDERVILLE, SAGAMORE HILLS, SOLON, TWINSBURG'),
('Region 1028', '4', 'C', '1028', 'Ar', 'ALTUS, MULBERRY, OZARK, PLEASANT VIEW'),
('Region 1031', '1', 'P', '1031', 'Ca', 'BALDWIN HILLS, LADERA HEIGHTS, VIEW PARK, INGLEWOOD, WEST ADAMS, FOX HILLS, MID WILSHIRE, WESTCHESTER, SOUTH LOS ANGELES, JEFFERSON PARK, LOS ANGELES'),
('Region 1032', '14', 'B', '1032', 'Ca', 'MOSCOW, RUSSIA'),
('Region 104', '12', 'C', '104', 'Ne', 'ALBUQUERQUE'),
('Region 1046', '12', 'D', '1046', 'Ar', 'AHWATUKEE, AHWATUKEE FOOTHILLS, CHANDLER, PHOENIX, SOUTH TEMPE, TEMPE, WEST CHANDLER, MARICOPA, GILBERT, MESA'),
('Region 105', '6', 'U', '105', 'Ka', 'BELLE PLAINE, HAYSVILLE, MULVANE, PECK, SOUTH WICHITA'),
('Region 1052', '13', 'G', '1052', 'Vi', 'HALIFAX COUNTY, SOUTH BOSTON'),
('Region 1053', '6', 'N', '1053', 'Ne', 'CALLAWAY, COZAD, EUSTIS, FARNAM, GOTHENBURG, LEXINGTON'),
('Region 1056', '6', 'B', '1056', 'Mi', 'BESSEMER, HURLEY - WI, IRONWOOD-MI, WAKEFIELD-MI'),
('Region 106', '11', 'Z', '106', 'Ca', 'BELLFLOWER, LAKEWOOD'),
('Region 1065', '11', 'Z', '1065', 'Ca', 'EAST LOS ANGELES, MONTEBELLO, MONTEREY PARK, PICO RIVERA, WHITTIER, ALHAMBRA'),
('Region 107', '2', 'N', '107', 'Ca', 'SAN CARLOS'),
('Region 1073', '3', 'T', '1073', 'Ne', 'MONROE, WOODBURY SCH DIST'),
('Region 1075', '12', 'C', '1075', 'Ne', 'SIERRA COUNTY, TRUTH OR CONSEQUENCE'),
('Region 1077', '5', 'D', '1077', 'Te', 'ATWOOD, BRUCETON, BRUCETON/HOLLOW ROCK, CAMDEN, CLARKSBURG, HUNTINGDON, MCKENZIE, MCLEMORESVILLE, TREZEVANT, YUMA'),
('Region 1079', '12', 'D', '1079', 'Ar', 'APACHE JUNCTION, GILBERT, GOLD CANYON, MESA, QUEEN CREEK, LAS SENDAS, RED MOUNTAIN, ARBOLEDA, THE GROVES, ALTA MESA, SAGUARO MOUNTAIN, MOUNTAIN BRIDGE, BOULDER MOUNTAIN, SUPERSTITION SPRINGS, AUGUSTA RANCH, KENSINGTON GROVE, LEHI, HIGLEY, EAST MESA, NORTH EAST MESA, THUNDER MOUNTAIN, GRANDVIEW ESTATES, HACIENDAS DEL ESTE, DESERT VISTA, GRANITE REEF, SPYGLASS ESTATES, LINDSAY PARK, HOMESTEAD, CYPRESS ESTATES, LEMONTREE, HACIENDA, MONTE VISTA, EASTRIDGE VILLAGES, CRISMON CREEK, SANTA RITA RANCH, DESERT SANDS, STONEGATE ESTATES, DESERT HARMONY, CARRIAGE MANOR, MOUNTAIN VIEW, SIERRA HEIGHTS, SONORAN PARK, SALERNO RANCH, SOUTH EAST MESA, A MESA'),
('Region 108', '2', 'N', '108', 'Ca', 'BELMONT, FOSTER CITY, HALF MOON BAY, REDWOOD CITY, REDWOOD SHORES, SAN MATEO'),
('Region 1084', '3', 'E', '1084', 'Ne', 'ALABAMA, BASOM, ELBA, OAKFIELD'),
('Region 1088', '13', 'D', '1088', 'Pe', 'ARMAGH, BLACKLICK, BLAIRSVILLE, BOLIVAR, BRUSHVALLEY, HOMER CITY, NEW FLORENCE, SEWARD'),
('Region 1089', '3', 'A', '1089', 'Ne', 'BOGOTA, CLIFFSIDE PARK, LEONIA, PALISADES PARK, EDGEWATER'),
('Region 109', '2', 'A', '109', 'Ca', 'ATHERTON, MENLO PARK'),
('Region 1090', '13', 'D', '1090', 'Pe', 'MT. SAVAGE, MIDLAND, LONACONING, BARTON, WESTERNPORT, GRANTSVILLE, CUMBERLAND, SPRINGS, SALISBURY, RIDGELEY, RAWLINGS, LAVALE, FINZEL, FROSTBURG, ECKHART MINES, MIDLOTHIAN, ELLERSLIE, CRESAPTOWN, LUKE, MEYERSDALE'),
('Region 1091', '13', 'G', '1091', 'No', 'CAMDEN COUNTY, ELIZABETH CITY, HERTFORD, PASQUOTANK COUNTY, PERQUIMANS COUNTY'),
('Region 1092', '7', 'A', '1092', 'Ha', 'KEAUKAHA, PANAGEWA'),
('Region 1096', '5', 'F', '1096', 'Al', 'ANDERSON, FLORENCE, KILLEN, LEXINGTON, ROGERSVILLE'),
('Region 1097', '8', 'D', '1097', 'Mi', 'MARSHALL'),
('Region 1099', '2', 'N', '1099', 'Ca', 'EL GRANADA, HALF MOON BAY, MONTARA, MOSS BEACH, PESCADERO'),
('Region 110', '2', 'N', '110', 'Ca', 'BURLINGAME, HILLSBOROUGH, SAN MATEO'),
('Region 1100', '13', 'Y', '1100', 'Pe', 'LIBERTY'),
('Region 1101', '3', 'G', '1101', 'Ne', 'BIG MOOSE, INLET &AMP; EAGLE BAY, OLD FORGE, OTTER LAKE, WOODGATE'),
('Region 1103', '6', 'E', '1103', 'Io', 'AMANA COLONIES, ATKINS, BELLE PLAINE, BLAIRSTOWN, BROOKLYN, GRINNELL, GUERNSEY, HARTWICK, LADORA, MALCOM, MARENGO, MONTEZUMA, NORWAY, OXFORD, TIFFIN, VAN HORNE, VICTOR, WILLAMSBURG'),
('Region 1104', '3', 'L', '1104', 'Ne', 'ELLENVILLE'),
('Region 1106', '8', 'F', '1106', 'Mi', 'CLIFFORD, KINGSTON, MARLETTE'),
('Region 1109', '6', 'B', '1109', 'Wi', 'ALMENA, CUMBERLAND, LUCK, TURTLELAKE, BARRONETT, BARRON'),
('Region 111', '11', 'L', '111', 'Ca', 'ALTA CAPISTRANO, CAPISTRANO BEACH, PALISADES, SAN CLEMENTE'),
('Region 1111', '2', 'D', '1111', 'Ca', 'ELVERTA, NATOMAS, NORTH HIGHLANDS, NORTH SACRAMENTO, RIO LINDA, ROBLA'),
('Region 1112', '6', 'E', '1112', 'Io', 'CEDAR RAPIDS, MARION, ROBINS, MOUNT VERNON, VINTON, HIAWATHA, ANAMOSA, LISBON, PALO, SPRINGVILLE, CENTER POINT, TODDVILLE, ALBURNETT, CENTRAL CITY, SHELLSBURG, SOLON, ELY, SWISHER, MARTELLE, GARRISON, MONTICELLO'),
('Region 1117', '13', 'C', '1117', 'We', 'HAMPSHIRE COUNTY'),
('Region 112', '1', 'U', '112', 'Ca', 'LA VERNE, SAN DIMAS'),
('Region 1120', '9', 'M', '1120', 'Mo', 'TOWNSEND'),
('Region 1123', '6', 'N', '1123', 'Ne', 'BEAVER CITY, ORLEANS, OXFORD'),
('Region 113', '7', 'O', '113', 'Ha', 'KANEOHE'),
('Region 1132', '12', 'A', '1132', 'Ar', 'BENSON, POMERENE, ST DAVID, TOMBSTONE'),
('Region 1136', '5', 'H', '1136', 'Fl', 'EAST MILTON, GULF BREEZE, HOLLY, NAVARRE, TIGER POINT'),
('Region 1138', '13', 'Y', '1138', 'Pe', 'BLOSSBURG, CHERRY FLATS, COVINGTON, MORRIS, MORRIS RUN'),
('Region 114', '11', 'Z', '114', 'Ca', 'LAKEWOOD, LONG BEACH, SIGNAL HILL, LOS ALAMITOS'),
('Region 1143', '3', 'B', '1143', 'Co', 'BRIDGEPORT'),
('Region 1145', '9', 'B', '1145', 'Ut', 'DRAPER, SANDY - SOUTH'),
('Region 1149', '2', 'A', '1149', 'Ca', 'EAST PALO ALTO, EASTERN MENLO PARK'),
('Region 115', '6', 'H', '115', 'Wi', 'BELOIT, BELVIDERE, CALEDONIA, CHERRY VALLEY, DAVIS, DURAND, LOVES PARK, MACHESNEY PARK, PECATONICA, POPLAR GROVE, ROCKFORD, ROCKTON, ROSCOE, STILLMAN VALLEY, WINNEBAGO, BYRON, JUDA'),
('Region 1156', '3', 'G', '1156', 'Ne', 'REMSEN'),
('Region 1157', '5', 'E', '1157', 'So', 'CROSS ANCHOR, ENOREE, MOORE, WOODRUFF'),
('Region 1159', '5', 'B', '1159', 'Te', 'ALCOA, BLOUNT CO, FRIENDSVILLE, LOUISVILLE, MARYVILLE, TOWNSEND, ROCKFORD, WALLAND'),
('Region 116', '11', 'S', '116', 'Ca', 'BONITA, EAST CHULA VISTA, EASTLAKE, OTAY RANCH, ROLLING HILLS'),
('Region 1161', '6', 'H', '1161', 'Mi', 'CLAYCOMO, EXCELSIOR SPRINGS, GLADSTONE, HOLT, KANSAS CITY, KEARNEY, LIBERTY, PLATTE CITY, PLEASANT VALLEY, RIVERSIDE, SMITHVILLE'),
('Region 1162', '8', 'F', '1162', 'Mi', 'AKRON, CARO, CASS CITY, CLIFFORD, COLLING, COLUMBIAVILLE, DECKER, DEFORD, EAST DAYTON, ELLINGTON, FAIRGROVE, FOSTORIA, GAGETOWN, GILFORD, JUNIATA, KINGSTON, MARLETTE, MAYVILLE, MILLINGTON, NORTH BRANCH, NORTH GROVE, OWENDALE, REESE, RICHVILLE, SEBAWAING, SILVERWOOD, SNOVER, TUSCOLA, UNIONVILLE, VASSAR, WAHJAMEGA, WATROUSVILLE, WILMONT, WISNER'),
('Region 1163', '8', 'A', '1163', 'Mi', 'ALMA, ASHLEY, BRECKENRIDGE, CARSON CITY, CRYSTAL, ITHACA, MAPLE RAPIDS, MIDDLETON, PERRINTON, ST. LOUIS, VESTABURG, WHEELER, BANNISTER, EDMORE, ELM HALL, ELWELL, MOUNT PLEASANT, NORTH STAR, POMPEII, RIVERDALE, SHEPHERD, SUMNER'),
('Region 1165', '5', 'I', '1165', 'Te', 'COCKE COUNTY, COSBY, DANDRIDGE, NEWPORT, GREENEVILLE, MOSHIEM, CHUCKEY'),
('Region 1167', '2', 'S', '1167', 'Wa', 'ARIEL, COUGAR, KALAMA, LA CENTER, WOODLAND, YALE, GREEN MOUNTAIN, AMBOY, YACOLT, BATTLE GROUND'),
('Region 117', '11', 'K', '117', 'Ca', 'COSTA MESA, FOUNTAIN VALLEY, SOU, GARDEN GROVE, HUNTINGTON BEACH'),
('Region 1174', '5', 'C', '1174', 'Te', 'BIG M FARMS AREA, CENTRAL AREA, CHASE AREA, ELORA COMMUNITY, GURLEY, HAZEL GREEN, KILLINGSWORTH COVE, LINCOLN COUNTY, MAYSVILLE, MERIDIANVILLE, MOORES MILL AREA, MOUNT CARMEL, NEW MARKET, RIVERTON, TONEY'),
('Region 118', '7', 'E', '118', 'Ha', 'AIEA, HONOLULU, MOANALUA, SALT LAKE, HALAWA, KALIHI, WAIMALU'),
('Region 1184', '13', 'C', '1184', 'Vi', 'ATLANTIC, CHINCOTEAGUE ISL., GREENBACKVILLE, NEW CHURCH, OAK HALL, PARKSLEY, TEMPERANCEVILLE, WALLOPS ISLAND, WATTSVILLE'),
('Region 1187', '2', 'C', '1187', 'Ca', 'AMERICAN CANYON, NAPA, VALLEJO'),
('Region 119', '7', 'E', '119', 'Ha', 'MILILANI, WAHIAWA'),
('Region 1197', '8', 'E', '1197', 'Mi', 'CENTREVILLE, CONSTANTINE, MENDON, THREE RIVERS, WHITE PIGEON'),
('Region 12', '1', 'F', '12', 'Ca', 'LOMITA, RANCHO PALOS VERDES, REDONDO BEACH, TORRANCE, HARBOR CITY, SAN PEDRO, COMPTON, LONG BEACH'),
('Region 120', '11', 'Q', '120', 'Ca', 'COSTA MESA, HUNTINGTON BEACH, NEWPORT BEACH, SANTA ANA, FOUNTAIN VALLEY'),
('Region 1200', '1', 'H', '1200', 'Ca', 'CATHEDRAL CITY, RANCHO MIRAGE, THOUSAND PALMS'),
('Region 1201', '9', 'M', '1201', 'Mo', 'ABSAROKEE, COLUMBUS, DEAN, FISHTAIL, MOLT, NYE, PARK CITY, RAPELJE, REED POINT, BIG TIMBER'),
('Region 1203', '13', 'A', '1203', 'Pe', 'CARROLLTOWN, HASTINGS, PATTON'),
('Region 1204', '6', 'C', '1204', 'Il', 'AURORA OAKHURST, AURORA FORESTVIEW, AURORA BRENTWOOD, AURORA LAKEWOOD, AURORA REBA STECK ES, AURORA MCCARTY ES'),
('Region 1206', '6', 'D', '1206', 'Il', 'CHICAGO-FAR NORTH, ROGERS PARK - EAST, ROGERS PARK - WEST, WEST RIDGE, UPTOWN, EDGEWATER, SKOKIE, EVANSTON, NORTH PARK, ALBANY PARK'),
('Region 121', '10', 'E', '121', 'Ca', 'SIMI VALLEY'),
('Region 1210', '6', 'H', '1210', 'Mi', 'ELLINGTON, ELLSNORE, FREMONT, VAN BUREN'),
('Region 1212', '14', 'L', '1212', 'Fl', 'BUNNELL, FLAGLER BCH, PALM COAST, ORMOND BEACH'),
('Region 122', '10', 'W', '122', 'Ca', 'GOLETA, MONTECITO, SANTA BARBARA'),
('Region 1224', '8', 'A', '1224', 'Mi', 'AMBLE, CORAL, LAKEVIEW, LANGSTON, STANTON, TRUFANT, MORLEY STANWOOD'),
('Region 1225', '9', 'B', '1225', 'Co', 'FOUNTAIN, WIDEFIELD, HANOVER, SECURITY, FORT CARSON, PETERSON AFB, COLORADO SPRINGS'),
('Region 1231', '12', 'A', '1231', 'Ar', 'WILLCOX'),
('Region 1236', '6', 'B', '1236', 'Wi', 'AMERY, CLAYTON, CLEAR LAKE, DEER PARK, TURTLE LAKE'),
('Region 124', '5', 'B', '124', 'Te', 'KNOXVILLE NORTHWEST, POWELL, HEISKELL, WEST HAVEN, FOUNTAIN CITY, HALLS, KARNS, NORTH KNOXVILLE, WEST HILLS, UT FORT SANDERS'),
('Region 1247', '13', 'B', '1247', 'Pe', 'CLARION, KNOX'),
('Region 125', '8', 'C', '125', 'Mi', 'CLINTON TOWNSHIP, MACOMB TOWNSHIP, MT CLEMENS, STERLING HEIGHTS, FRASER'),
('Region 1256', '8', 'F', '1256', 'Mi', 'AKRON, FAIRGROVE, GAGETOWN, OWENDALE, SEBEWAING, UNIONVILLE, WISNER'),
('Region 1258', '1', 'S', '1258', 'Ne', 'LAS VEGAS, SUMMERLIN'),
('Region 126', '9', 'B', '126', 'Ut', 'ALTA, COTTONWOOD HEIGHTS, HOLLADAY, MIDVALE, MURRAY, SALT LAKE CITY, SANDY'),
('Region 1262', '6', 'N', '1262', 'Ne', 'ARAPAHOE, CAMBRIDGE, HOLBROOK'),
('Region 1264', '6', 'H', '1264', 'Mi', 'BROOKSIDE'),
('Region 1265', '9', 'M', '1265', 'Mo', 'CLANCY, HELENA, JEFFERSON CITY, LEWIS &AMP; CLARK COUNTY'),
('Region 1266', '5', 'B', '1266', 'Te', 'BLOUNT COUNTY, BOYDS CREEK, KIMBERLIN HEIGHTS, KODAK, SEVIER COUNTY, SEYMOUR, JOHNSON UNIVERSITY'),
('Region 1267', '14', 'I', '1267', 'Fl', 'CLEWISTON'),
('Region 127', '11', 'R', '127', 'Ca', 'CARLSBAD, ESCONDIDO, OCEANSIDE, SAN MARCOS, VISTA'),
('Region 1273', '6', 'B', '1273', 'Wi', 'BURNETT COUNTY, GRANTSBURG, PINE COUNTY, SIREN, WEBSTER'),
('Region 1275', '6', 'U', '1275', 'Ka', 'AGRA, KENSINGTON, KIRWIN, LOGAN, LONG ISLAND, PHILLIPSBURG, PRAIRIE VIEW, SPEED'),
('Region 1277', '5', 'G', '1277', 'Te', 'MONROE COUNTY'),
('Region 1278', '8', 'C', '1278', 'Mi', 'ALMONT, DRYDEN, IMLAY CITY'),
('Region 128', '5', 'B', '128', 'Te', 'CEDAR BLUFF, CONCORD, FARRAGUT, HARDIN VALLEY, NORTHSHORE AREA, WEST KNOXVILLE'),
('Region 1282', '11', 'R', '1282', 'Ca', 'CARLSBAD, ELFIN FOREST, ENCINITAS, LA COSTA, OLIVENHAIN, SAN ELIJO, SAN MARCOS, OCEANSIDE, VISTA, SOLANA BEACH, CARDIFF'),
('Region 1284', '6', 'H', '1284', 'Il', 'BELVIDERE, GENOA, HERBERT, KINGSTON, VALLEY VIEW'),
('Region 1285', '8', 'F', '1285', 'Mi', 'CROSWELL, LEXINGTON'),
('Region 1288', '11', 'Z', '1288', 'Ca', 'COMPTON, LYNWOOD, PARAMOUNT, SOUTH GATE'),
('Region 1289', '8', 'D', '1289', 'Mi', 'BRANCH COUNTY'),
('Region 129', '10', 'O', '129', 'Ca', 'EXETER, FARMERSVILLE, IVANHOE, TULARE, VISALIA, WOODLAKE'),
('Region 1294', '8', 'E', '1294', 'Mi', 'CASSOPOLIS, JONES, MARCELLUS, VANDALIA'),
('Region 1296', '8', 'C', '1296', 'Mi', 'ALLENTON, BERLIN TOWNSHIP, BERVILLE, BROWN CITY, CAPAC, EMMETT, GOODELLS, LYNN TOWNSHIP, RILEY TOWNSHIP, YALE'),
('Region 1298', '8', 'A', '1298', 'Mi', 'HOWARD CITY, PIERSON, SAND LAKE'),
('Region 13', '1', 'C', '13', 'Ca', 'ALTADENA, FLINTRIDGE, LA CANADA, PASADENA, SIERRA MADRE'),
('Region 130', '1', 'N', '130', 'Ca', 'EAST HIGHLAND, HIGHLAND, SAN BERNARDINO'),
('Region 1303', '6', 'F', '1303', 'Il', 'ELMWOOD PARK, GALEWOOD, OAK PARK, RIVER FOREST, RIVER GROVE'),
('Region 1304', '1', 'C', '1304', 'Ca', 'BOYLE HEIGHTS, EAST LOS ANGELES, HOLLENBECK'),
('Region 1308', '8', 'A', '1308', 'Mi', 'MASON COUNTY'),
('Region 1310', '13', 'G', '1310', 'Vi', 'HAMPTON, NEWPORT NEWS'),
('Region 1311', '6', 'H', '1311', 'Mi', 'BIRCH TREE, EMINENCE, MOUNTAIN VIEW, SALEM'),
('Region 1312', '2', 'D', '1312', 'Ca', 'CAPAY, ESPARTO, MADISON'),
('Region 1315', '1', 'S', '1315', 'Ne', 'LAS VEGAS, MOUNTIANS EDGE'),
('Region 1319', '11', 'R', '1319', 'Ca', 'CITY HEIGHTS, SAN DIEGO'),
('Region 1328', '3', 'A', '1328', 'Ne', 'ASTORIA, FOREST HILLS, GREENPOINT, LONG ISLAND CITY, MASPETH, MIDDLE VILLAGE, RIDGEWOOD, SUNNYSIDE, WILLIAMSBURG, WOODHAVEN'),
('Region 1329', '6', 'B', '1329', 'Wi', 'BRULE, HAWTHORNE, IRON RIVER, LAKE NEBAGAMON, MAPLE, POPLAR, SOLON SPRINGS, SOUTH RANGE, SUPERIOR'),
('Region 1332', '13', 'D', '1332', 'Pe', 'CENTRAL CITY, SHADE, SHANKSVILLE, WILBUR'),
('Region 1335', '11', 'K', '1335', 'Ca', 'ANAHEIM HILLS, EAST ANAHEIM, ORANGE, ORANGE PARK ACRES, VILLA PARK, SANTA ANA'),
('Region 1336', '5', 'I', '1336', 'Te', 'BULLS GAP, MOORESBURG, ROGERSVILLE, SURGOINSVILLE, KYLES FORD, EIDSON, ST CLAIR'),
('Region 1337', '8', 'F', '1337', 'Mi', 'HARBOR BEACH'),
('Region 1339', '2', 'C', '1339', 'Ca', 'WALNUT CREEK'),
('Region 134', '3', 'E', '134', 'Ne', 'CLARK MILLS, CLINTON, DEANSBORO, FRANKLIN SPRINGS, NEW HARTFORD, SAUQUOIT, WHITESBORO'),
('Region 1341', '12', 'C', '1341', 'Ne', 'ELDORADO, MADRID, PECOS, POJOAQUE, SANTA FE'),
('Region 1343', '9', 'B', '1343', 'Ut', 'BLOOMINGTON, BLOOMINGTON HILLS, DAMERON/DIAMOND/WINC, DIXIE DOWNS/GREEN VA, LAVERKIN/HURRICANE, SANTA CLARA/IVINS, ST. GEORGE, WASHINGTON, WASHINGTON FIELDS, LITTLE VALLEY, CORAL CANYON'),
('Region 1344', '2', 'B', '1344', 'Ca', 'BRISBANE, COLMA, DALY CITY, SAN FRANCISCO, SOUTH SAN FRANCISCO'),
('Region 1345', '1', 'D', '1345', 'Ca', 'INGLEWOOD, LENNOX, LOS ANGELES, HAWTHORNE'),
('Region 1347', '11', 'Z', '1347', 'Ca', 'EAST LOS ANGELES, MONTEREY PARK, BOYLE HEIGHTS, LOS ANGELES, MONTEBELLO'),
('Region 1350', '8', 'B', '1350', 'Mi', 'DORR, HOPKINS'),
('Region 1352', '2', 'C', '1352', 'Ca', 'STOCKTON'),
('Region 1354', '8', 'A', '1354', 'Mi', 'FREMONT, HESPERIA, NEWAYGO'),
('Region 136', '1', 'G', '136', 'Ca', 'ALTA LOMA, BLOOMINGTON, ETIWANDA, FONTANA, ONTARIO, RANCHO CUCAMONGA, RIALTO, UPLAND'),
('Region 1360', '2', 'E', '1360', 'Ca', 'ALTURAS, CANBY, LIKELY, CEDARVILLE'),
('Region 1363', '12', 'B', '1363', 'Ar', 'HEBER, OVERGAARD'),
('Region 1365', '6', 'U', '1365', 'Ka', 'ALMENA, LENORA, NORTON, HILL CITY'),
('Region 1367', '6', 'B', '1367', 'Wi', 'CLAM FALLS, FREDERIC, LUCK, MILLTOWN'),
('Region 1368', '9', 'R', '1368', 'Ut', 'ENTERPRISE, HENEFER, MORGAN, MOUNTAIN GREEN, PETERSON, PORTERVILLE'),
('Region 137', '1', 'N', '137', 'Ca', 'HEMET, HOMELAND, IDYLLWILD, ROMOLAND, SAN JACINTO, WINCHESTER'),
('Region 1370', '14', 'I', '1370', 'Fl', 'BOCA RATON, BOYNTON BEACH, DELRAY BEACH, GREENACRES, LAKE WORTH, LANTANA, PALM BEACH, WELLINGTON, WEST PALM BEACH'),
('Region 1373', '5', 'F', '1373', 'Al', 'CLAYSVILLE, GRANT, NEW HOPE, SWEARENGIN, WOODVILLE'),
('Region 1378', '3', 'A', '1378', 'Ne', 'MANHATTAN EAST SIDE'),
('Region 138', '9', 'R', '138', 'Ut', 'BEAR RIVER CITY, BRIGHAM CITY, CORINNE, HONEYVILLE, MANTUA, PERRY, WILLARD, ELWOOD, TREMONTON'),
('Region 1380', '9', 'B', '1380', 'Ut', 'BLUEBELL, DUCHESNE, FORT DUCHESNE, MYTON, NEOLA, ROOSEVELT, LAPOINT'),
('Region 1382', '5', 'H', '1382', 'Mi', 'ANGIE, ENON, FRANKLINTON, PINE, THOMAS, TYLERTOWN'),
('Region 1383', '14', 'B', '1383', 'Vi', 'ST. CROIX, ST. JOHN, ST. THOMAS'),
('Region 1386', '6', 'N', '1386', 'So', 'BISON, LEMMON'),
('Region 1388', '13', 'A', '1388', 'Pe', 'BREEZEWOOD, CLEARVILLE, EVERETT'),
('Region 1389', '13', 'I', '1389', 'Pe', 'HANOVER, LITTLESTOWN'),
('Region 139', '3', 'T', '139', 'Ne', 'CHAPPAQUA, MILLWOOD'),
('Region 1390', '5', 'B', '1390', 'Te', 'EAST KNOX COUNTY, KNOXVILLE-EAST, SOUTH KNOXVILLE'),
('Region 1391', '8', 'J', '1391', 'Mi', 'ALLENDALE, COOPERSVILLE, GRAND HAVEN, HUDSONVILLE, STANDALE, WEST OLIVE, ZEELAND'),
('Region 1392', '13', 'A', '1392', 'Pe', 'ALTOONA, BALD EAGLE, PORT MATILDA, SPRUCE CREEK, TYRONE, WARRIORS MARK, BELLWOOD'),
('Region 1393', '12', 'E', '1393', 'Te', 'UTOPIA, VANDERPOOL LAKEY'),
('Region 1395', '3', 'G', '1395', 'Ne', 'DOLGEVILLE, MANHEIM, OPPENHEIM, SALISBURY, STRAFFORD'),
('Region 1398', '11', 'Q', '1398', 'Ca', 'ANAHEIM, BREA, FULLERTON, PLACENTIA, YORBA LINDA'),
('Region 14', '1', 'F', '14', 'Ca', 'TORRANCE WEST'),
('Region 140', '2', 'F', '140', 'Ne', 'CARSON CITY'),
('Region 1403', '9', 'M', '1403', 'Mo', 'CUT BANK'),
('Region 1406', '9', 'M', '1406', 'Mo', 'CHOTEAU'),
('Region 1408', '14', 'L', '1408', 'Fl', 'BUSHNELL, OXFORD, SUMTER COUNTY, WEBSTER, WILDWOOD'),
('Region 141', '3', 'T', '141', 'Ne', 'MAHOPAC, PEEKSKILL, PUTNAM VALLEY, TOWN OF COURTLAND, YORKTOWN HEIGHTS, SHRUB OAK'),
('Region 1410', '12', 'B', '1410', 'Ar', 'ASHFORK, CHINO VALLEY, DRAKE, PAULDEN, WILLIAMSON VALLEY'),
('Region 1411', '8', 'B', '1411', 'Mi', 'FENNVILLE, GLENN, PULLMAN, SPRING GROVE, PIER COVE'),
('Region 1415', '12', 'G', '1415', 'Ok', 'BOISE CITY'),
('Region 1416', '10', 'A', '1416', 'Ca', 'MCFARLAND, DELANO, RICHGROVE'),
('Region 142', '13', 'D', '142', 'Pe', 'SOMERSET COUNTY'),
('Region 1421', '10', 'D', '1421', 'Ca', 'BARSTOW, HELENDALE, ORO GRANDE'),
('Region 1422', '11', 'L', '1422', 'Ca', 'LAGUNA HILLS'),
('Region 1426', '2', 'E', '1426', 'Or', 'ADEL, BLY, LAKEVIEW, PAISLEY, PLUSH'),
('Region 1429', '10', 'O', '1429', 'Ca', 'CUTLER, OROSI'),
('Region 143', '11', 'K', '143', 'Ca', 'HUNTINGTON BCH WEST, SUNSET BEACH, WESTMINSTER'),
('Region 1430', '10', 'V', '1430', 'Ca', 'ARLETA, LAKE VIEW TERRACE, MISSION HILLS, NORTH HILLS, PACOIMA, PANORAMA CITY, SAN FERNANDO, SUN VALLEY, SYLMAR'),
('Region 1434', '12', 'E', '1434', 'Te', 'GAINESVILLE, MARIETTA, MUENSTER, SAINT JO, SANGER, THACKERVILLE, VALLEY VIEW, WHITESBORO'),
('Region 1435', '12', 'G', '1435', 'Ok', 'CHECOTAH, EUFAULA, FORT GIBSON, HASKELL, MUSKOGEE, OKTAHA, SUMMIT, WAGONER, WARNER'),
('Region 1437', '6', 'B', '1437', 'Wi', 'ST CROIX FALLS'),
('Region 1438', '6', 'F', '1438', 'Il', 'WESTCHESTER, HILLSIDE, MAYWOOD, BROADVIEW, BERKELEY, BELLWOOD, OAK BROOK'),
('Region 144', '11', 'Q', '144', 'Ca', 'IRVINE SOUTH'),
('Region 1440', '12', 'B', '1440', 'Ar', 'MOENKOPI, TUBA CITY'),
('Region 1441', '10', 'S', '1441', 'Ca', 'CASTAIC, VAL VERDE'),
('Region 1443', '12', 'E', '1443', 'Te', 'LAREDO'),
('Region 1447', '12', 'C', '1447', 'Ne', 'CORRALES, RIO RANCHO, WEST ALBUQUERQUE'),
('Region 1449', '2', 'S', '1449', 'Wa', 'GOLDENDALE'),
('Region 145', '2', 'B', '145', 'Ca', 'MILLBRAE'),
('Region 1450', '14', 'B', '1450', 'TT', 'MALABAR ARIMA, SANGRE GRANDE, SAMAROO VILLAGE, SANTA ROSA, PORT OF SPAIN, DABADIE, AROUCA, VALENCIA, FIVE RIVERS, SAN FERNANDO, TABAQUITE, BROTHERS ROAD'),
('Region 1451', '13', 'A', '1451', 'Pe', 'ASHVILLE, CRESSON, DYASRT, GALLITIZIN, LILLY, LORETTO, PORTAGE'),
('Region 1452', '14', 'I', '1452', 'Fl', 'LAKE PARK'),
('Region 1454', '3', 'G', '1454', 'Ne', 'BRASHER FALLS'),
('Region 1455', '11', 'L', '1455', 'Ca', 'LADERA RANCH, LAS FLORES, WAGON WHEEL, COTO DE CAZA, SENDERO, ESENCIA'),
('Region 1457', '6', 'N', '1457', 'Ne', 'IMPERIAL'),
('Region 1458', '1', 'S', '1458', 'Ne', 'AMARGOSA VALLEY'),
('Region 1459', '5', 'D', '1459', 'Lo', 'PARRISH, VERNON'),
('Region 146', '2', 'B', '146', 'Ca', 'COLMA, DALY CITY, SAN FRANCISCO, SAN FRANCISCO SOUTH, SAN BRUNO, MILLBRAE, PACIFICA, ANTIOCH'),
('Region 1461', '2', 'C', '1461', 'Ca', 'RICHMOND'),
('Region 1463', '1', 'R', '1463', 'Ca', 'FRENCH VALLEY, LAKE ELSINORE, MURRIETA, TEMECULA, WILDOMAR, WINCHESTER'),
('Region 1466', '6', 'A', '1466', 'Wi', 'ANTIOCH, BRISTOL, GURNEE, INGLESIDE, LAKE VILLA, LINDERHURST, MILBOURN, PELL LAKE, RICHMOND, SALEM, SILVER LAKE, SPRING GROVE, TREVOR'),
('Region 1468', '6', 'E', '1468', 'Io', 'CENTER POINT, URBANA, WALKER'),
('Region 1469', '6', 'B', '1469', 'Mi', 'BEROUN, BRAHAM, BROOK PARK, GRASSTON, PINE CITY, ROCK CREEK, RUSH CITY, STANCHFIELD'),
('Region 147', '10', 'W', '147', 'Ca', 'CASITAS SPRINGS, MEINERS OAKS, MIRA MONTE, OAK VIEW, OJAI'),
('Region 1471', '8', 'A', '1471', 'Mi', 'CRYSTAL, GOWEN, GREENVILLE, SHERIDAN, TURK LAKE'),
('Region 1472', '8', 'A', '1472', 'Mi', 'BAILEY, CASNOVIA, GRANT, KENT CITY, RAVENNA'),
('Region 1475', '13', 'G', '1475', 'Vi', 'CHESAPEAKE'),
('Region 1476', '3', 'B', '1476', 'Ne', 'GREENVILLE, MASON, NEW IPSWICH'),
('Region 148', '10', 'W', '148', 'Ca', 'OXNARD, PORT HUENEME, OXNARD SOUTH'),
('Region 1481', '8', 'G', '1481', 'Mi', 'ALBEE, BIRCH RUN, BRIDGEPORT, BURT, CHESANING'),
('Region 1482', '6', 'H', '1482', 'Mi', 'WILLOW SPRINGS'),
('Region 1487', '14', 'L', '1487', 'Fl', 'APOLLO BEACH, BALM, GIBSONTON, RIVERVIEW, RUSKIN, SUN CITY CENTER, SUNDANCE, WIMAUMA'),
('Region 149', '2', 'S', '149', 'Or', 'CORVALLIS, PHILOMATH, NEWPORT, DALLAS'),
('Region 1494', '12', 'E', '1494', 'Te', 'BROWNSVILLE, HARLINGEN, LOS FRESNOS, OLMITO, PORT ISABEL, SOUTH PORT ISABEL'),
('Region 1499', '13', 'A', '1499', 'Pe', 'BROADTOP AREA, SAXTON AREA'),
('Region 15', '1', 'F', '15', 'Ca', 'CARSON, HARBOR CITY, LOMITA, TORRANCE, WILMINGTON, GARDENA'),
('Region 150', '2', 'F', '150', 'Ca', 'BENTON, BIG PINE, BISHOP, ROUND VALLEY'),
('Region 1505', '11', 'R', '1505', 'Ca', 'EAST CARLSBAD, SAN ELIJO, SAN MARCOS WEST, OLD CREEK RANCH'),
('Region 1508', '1', 'R', '1508', 'Ca', 'FRENCH VALLEY, MURRIETA, RANCHO CALIFORNIA, RED HAWK, TEMECULA, WILDOMAR, WINCHESTER, MENIFEE'),
('Region 1509', '12', 'G', '1509', 'Ok', 'CHECOTAH, EUFAULA, STIDHAM'),
('Region 151', '9', 'R', '151', 'Ut', 'RIVERDALE, SOUTH OGDEN, SOUTH WEBER, UINTAH, WASHINGTON TERRACE, SOUTH WEBER COUNTY'),
('Region 1510', '10', 'A', '1510', 'Ca', 'ALLENSWORTH, DELANO, EARLIMART, MCFARLAND, RICHGROVE'),
('Region 1511', '12', 'A', '1511', 'Ar', 'PATAGONIA'),
('Region 1512', '6', 'B', '1512', 'Mi', 'ELK RIVER, OTSEGO, ROGERS, ST. MICHAEL, ZIMMERMAN, DAYTON, MAPLE GROVE, ALBERTVILLE, CHAMPLIN, CORCORAN'),
('Region 1514', '3', 'L', '1514', 'Ne', 'FERNDALE, LEW BEACH, LIBERTY, LIVINGSTON MANOR, LOCH SHELDRAKE, PARKSVILLE, SWAN LAKE, WHITE SULPHUR SPRING'),
('Region 1516', '6', 'F', '1516', 'Il', 'CHIACTOWN, GAF, SOUTH LOOP'),
('Region 1517', '3', 'L', '1517', 'Ne', 'BETHEL, BLOOMINGBURG, BURLINGHAM, ELLENVILLE, FORESTBURGH, HARRIS, KAUNEONGA LAKE, KENOZA LAKE, KIAMESHA LAKE, MONGAUP VALLEY, MONTICELLO, PHILLIPSPORT, PINE BUSH, PORT JERVIS, SMALLWOOD, SUMMITVILLE, THOMPSON, WESTBROOKVILLE, WHITE LAKE, WURTSBORO'),
('Region 152', '11', 'R', '152', 'Ca', 'TIERRASANTA, MURPHY CANYON, MISSION VALLEY, ALLIED GARDENS, DEL CERRO, KENSINGTON, CITY HEIGHTS, TALMADGE, CLAIREMONT, KEARNY MESA, LINDA VISTA, UNIVERSITY CITY, GRANTVILLE'),
('Region 1520', '9', 'B', '1520', 'Ut', 'ALTAMONT, ALTONAH, BLUEBELL, DUCHESNE, FRUITLAND, MTN. HOME, TABIONA, TALMAGE'),
('Region 1521', '14', 'I', '1521', 'Fl', 'ACREAGE, LOXAHATCHEE, ROYAL PALM BEACH, WEST PALM BEACH'),
('Region 1522', '12', 'C', '1522', 'Ne', 'GILMAN, JEMEZ PUEBLO, JEMEZ SPRINGS, LA CUEVA, PONDEROSA, SAN YSIDRO, ZIA PUEBLO'),
('Region 1523', '8', 'A', '1523', 'Mi', 'FREMONT, HESPERIA, WHITE CLOUD, BITELY'),
('Region 1525', '5', 'B', '1525', 'Ke', 'SOMERSET'),
('Region 1526', '10', 'A', '1526', 'Ca', 'BAKERSFIELD'),
('Region 1527', '11', 'Z', '1527', 'Ca', 'COMPTON, LOS ANGELES'),
('Region 153', '12', 'A', '153', 'Ar', 'TUCSON NORTHEAST, TUCSON SOUTHEAST, VAIL, CORONA DE TUCSON'),
('Region 1531', '14', 'I', '1531', 'Fl', 'BELLE GLADE'),
('Region 1533', '9', 'R', '1533', 'Ut', 'SYRACUSE, WEST POINT'),
('Region 1534', '2', 'C', '1534', 'Ca', 'PLEASANTON'),
('Region 1535', '5', 'D', '1535', 'Te', 'BRIGHTON, MUNFORD, MILLINGTON, COVINGTON, MASON, DRUMMONDS, ATOKA'),
('Region 1536', '3', 'L', '1536', 'Ne', 'BRADLEY, FALLSBURG, GRAHAMSVILLE, LIBERTY, LOCH SHELDRAKE, NAPANOCH, NEVERSINK, WOODBOURNE'),
('Region 1537', '12', 'G', '1537', 'Ok', 'BURNS FLAT, CANUTE, ELK CITY, SAYRE, WHEELER, CHEYENNE, REYDON, HAMMON, LEEDEY'),
('Region 1538', '9', 'M', '1538', 'Mo', 'WHITEHALL'),
('Region 1539', '13', 'A', '1539', 'Pe', 'CLAYSBURG, DUNCANSVILLE, EAST FREEDOM, MARTINSBURG, ROARING SPRING, WILLIAMSBURG'),
('Region 154', '11', 'E', '154', 'Ca', 'ANAHEIM WEST, BUENA PARK, CYPRESS, LA PALMA, STANTON'),
('Region 1543', '12', 'E', '1543', 'Te', 'COOPERAS COVE, FT HOOD, HARKER HEIGHTS, KILLEEN'),
('Region 1546', '3', 'G', '1546', 'Ne', 'DAY, HADLEY, LUZERNE, STONY CREEK'),
('Region 1547', '3', 'G', '1547', 'Ne', 'CHARLESTON, DUANESBURG, FLORIDA, KNOX, PRINCETOWN, SCHOHARIE, WRIGHT, DELANSON'),
('Region 1548', '14', 'I', '1548', 'Fl', 'WELLINGTON'),
('Region 155', '7', 'E', '155', 'Ha', 'WAHIAWA'),
('Region 1551', '3', 'L', '1551', 'Ne', 'ELDRED, NARROWSBURG'),
('Region 1553', '9', 'B', '1553', 'Co', 'COLORADO SPRINGS'),
('Region 1555', '3', 'B', '1555', 'Rh', 'COVENTRY, GREENE, WEST GREENWICH, WEST WARWICK'),
('Region 1556', '6', 'B', '1556', 'Wi', 'BALSAM LAKE, CENTURIA, MILLTOWN, SAINT CROIX'),
('Region 1557', '13', 'A', '1557', 'Pe', 'BARR TWP, CHERRY TREE, NORTHERN CAMBRIA, SUSQUEHANA TWP, WEST CARROL TWP, WESTOUEN'),
('Region 1559', '6', 'N', '1559', 'Ne', 'ALDA, AURORA, CAIRO, DONIPHAN, GRAND ISLAND, ST PAUL, ST. LIBERTY, WOOD RIVER'),
('Region 1560', '14', 'I', '1560', 'Fl', 'LAKE PLACID'),
('Region 1561', '5', 'G', '1561', 'Te', 'BENTON, DELANO, OCOEE, OLD FORT, POLK COUNTY'),
('Region 1563', '3', 'E', '1563', 'Ne', 'CANASTOTA, OTHER, PERRYVILLE, SOUTH BAY, WHITELAW, CHITTENANGO, MORRISVILLE'),
('Region 1564', '9', 'R', '1564', 'Wy', 'LYMAN, MOUNTAIN VIEW, ROBERTSON, FORT BRIDGER, URIE'),
('Region 1566', '14', 'A', '1566', 'Fl', 'AVENTURA, NORTH MIAMI, SOUTH MIAMI, SUNNY ISLES BEACH, SURFSIDE'),
('Region 1567', '1', 'P', '1567', 'Ca', 'ECHO PARK, LOS FELIZ, SILVER LAKE, METRO LOS ANGELES, HANCOCK PARK, ATWATER VILLAGE'),
('Region 157', '2', 'B', '157', 'Ca', 'PACIFICA'),
('Region 1572', '3', 'B', '1572', 'Ma', 'GREENDALE, WORCESTER'),
('Region 1574', '14', 'L', '1574', 'Fl', 'DONA VISTA, LEESBURG, UMATILLA'),
('Region 1575', '14', 'A', '1575', 'Fl', 'DORAL, MEDLEY, SO. MIAMI'),
('Region 158', '8', 'C', '158', 'Mi', 'ST. CLAIR SHORES'),
('Region 1580', '14', 'A', '1580', 'Fl', 'DORAL'),
('Region 1581', '6', 'H', '1581', 'Il', 'AMBOY, SUBLETTE, HARMON, ELDENA, MENDOTA, LAMOILLE, VAN ORIN, OHIO, FRANKLIN GROVE, ASHTON, DIXON'),
('Region 1582', '6', 'B', '1582', 'Wi', 'SPOONER, SHELL LAKE, MINOG, TREGO'),
('Region 1584', '7', 'A', '1584', 'Ha', 'HAWAIIAN OCEAN EST, NAALEHU, WALOHINO, PAHALA'),
('Region 1585', '12', 'E', '1585', 'Te', 'YOAKUM COUNTY, PLAINS, DENVER CITY, SEMINOLE, SEAGRAVES'),
('Region 1586', '5', 'H', '1586', 'Al', 'ECLECTIC, MILLBROOK, WETUMPKA, TALLASSEE, DEATSVILLE, ELMORE COUNTY, HOLTVILLE, COOSADA'),
('Region 1587', '8', 'F', '1587', 'Mi', 'HAWKS, MILLERSBURG, OCQUEOC, ONAWAY, OSSINEKE, PRESQUE ISLE, POSEN, ROGERS CITY'),
('Region 1589', '9', 'M', '1589', 'Mo', 'ROUNDUP, MELSTONE, LAVINA, DELPHIA'),
('Region 159', '11', 'E', '159', 'Ca', 'LOS ALAMITOS, ROSSMOOR, SEAL BEACH'),
('Region 1590', '2', 'J', '1590', 'Ca', 'MT HAMILTON, EVERGREEN, SAN JOSE, ALUM ROCK, EAST SAN JOSE, SOUTH SAN JOSE'),
('Region 1592', '13', 'I', '1592', 'Ma', 'HANOVER, WESTMINSTER, HAMPSTEAD, MANCHESTER, GLENVILLE, ABBOTTSTOWN, SPRING GROVE'),
('Region 1593', '2', 'D', '1593', 'Ca', 'WILLIAMS, COLUSA, ARBUKLE, MAXWELL'),
('Region 1594', '2', 'J', '1594', 'Ca', 'SANTA CRUZ'),
('Region 1595', '1', 'P', '1595', 'Ca', 'WATTS, MANCHESTER SQUARE, ATHENS, EXPOSITION PARK, LOS ANGELES, VERNON, COMPTON'),
('Region 1596', '11', 'V', '1596', 'Ca', 'ALLIED GARDENS, SAN DIEGO'),
('Region 1597', '8', 'A', '1597', 'Mi', 'REMUS, MECOSTA, BARRYTON, CANADIAN LAKES, WEIDMAN, CHIPPEWA LAKE, LAKE ISABELLA, RODNEY, EVART, LAKE STATION, LAKE, SEARS'),
('Region 1598', '12', 'E', '1598', 'Te', 'WALLIS, ORCHARD, SIMONTON, FULSHEAR'),
('Region 16', '1', 'F', '16', 'Ca', 'GARDENA, LAWNDALE, NORTH REDONDO, TORRANCE NORTH, CARSON, HAWTHORNE, E INGLEWOOD'),
('Region 160', '5', 'C', '160', 'Al', 'BROWNSBORO, GURLEY, HAMPTON COVE, HUNTSVILLE, LACEYS SPRINGS, NEW HOPE, OWENS CROSS ROADS, TRIANA, MADISON'),
('Region 1600', '3', 'A', '1600', 'Ne', 'ENGLEWOOD, ENGLEWOOD CLIFFS, TENAFLY, TEANECK, BERGENFIELD'),
('Region 1602', '9', 'I', '1602', 'Id', 'REXBURG'),
('Region 1603', '5', 'H', '1603', 'Fl', 'PENSACOLA, BAKER, CRESTVIEW, FLORALA, NICEVILLE, LAUREL HILL, DEFUNIAK SPGS'),
('Region 1604', '2', 'C', '1604', 'Ca', 'MANTECA'),
('Region 1605', '1', 'R', '1605', 'Ca', 'EASTVALE, NORCO, CORONA'),
('Region 1607', '9', 'M', '1607', 'Mo', 'BRADY, GORDON, PENDROY, CONRAD'),
('Region 1608', '12', 'C', '1608', 'Ne', 'PORTALES, ARCH, CAUSEY, DORA, FLOYD, ELIDA'),
('Region 1609', '80', 'Z', '1609', 'Wa', 'SEA TAC, SEATTLE, NORMANDY PARK, BURIEN, DES MOINES, MCMIKEN HEIGHTS, RIVERTON, TUKWILA, KENT'),
('Region 161', '8', 'C', '161', 'Mi', 'CLYDE TWP, FORT GRATIOT, GOODELLS, KIMBALL, LAKEPORT, LEXINGTON, MARYSVILLE, NORTH STREET, PORT HURON, SMITHS CREEK, ST. CLAIR, AVOCA, EMMETT, JEDDO'),
('Region 1610', '10', 'Q', '1610', 'Ca', 'ATASCADERO, SANTA MARGARITA, CRESTON, TEMPELTON'),
('Region 1612', '5', 'G', '1612', 'Ge', 'ATLANTA, GRANT PARK, SAND NEIGHBORHOODS, EAST ATLANTA, EAST LAKE, KIRKWOOD, CABBAGETOWN, OLD FOURTH WARD, ORMEWOOD PARK, CANDLER PARK, INMAN PARK, SUMMERHILL, PEOPLESTOWN'),
('Region 1613', '5', 'B', '1613', 'Te', 'JOHNSON CITY, ESTU, GRAY'),
('Region 1614', '13', 'I', '1614', 'Pe', 'BLAIRS MILLS, SHADY GAP, EAST WATERFORD, NEELYTON'),
('Region 1615', '5', 'G', '1615', 'Te', 'SPRING CITY'),
('Region 1617', '2', 'E', '1617', 'Ne', 'WHITE PINE COUNTY, CHERRY CREEK, CURRIE, ELY, ELY COLONY, PRESTON, LUND, LANE, MCGILL, STEPTOE'),
('Region 1618', '12', 'E', '1618', 'Te', 'HIGHLAND, BAYTOWN, CROSBY'),
('Region 1624', '14', 'I', '1624', 'Fl', 'DEERFIELD BEACH, DEERFIELD BCH'),
('Region 1625', '8', 'D', '1625', 'Mi', 'ALBION, SPRINGPORT, HOMER, CONCORD, MARSHALL'),
('Region 1626', '14', 'L', '1626', 'Fl', 'KISSIMMEE, ORLANDO, FOUR CORNERS, WINDERMERE, ST CLOUD, OVIEDO, HUNTERS CREEK, DOCTOR PHILLIPS, LAKE NONA, CELEBRATION, ORLANDA'),
('Region 163', '6', 'A', '163', 'Il', 'FORT SHERIDAN, GREAT LAKES NAVAL BA, HIGHWOOD, LAKE BLUFF, LAKE FOREST'),
('Region 1630', '14', 'I', '1630', 'Fl', 'PARRISH, BRADENTON, ELLENTON, WIMAUMA, DUETTE'),
('Region 1631', '2', 'J', '1631', 'Ca', 'SAN JOSE, ALUM ROCK, MILIPITAS, SANTA CLARA, WILLOW GLEN, DOWNTOWN SAN JOSE, ALVISO, EAST SAN JOSE, BERRYESSA, NORTH SAN JOSE'),
('Region 1632', '3', 'T', '1632', 'Ne', 'WHITE PLAINS, SCARSDALE, HARTSDALE, PORT CHESTER, RYE, YONKERS'),
('Region 1634', '8', 'J', '1634', 'Mi', 'GRAND RAPIDS, WYOMING, STANDALE, CUTLERVILLE, KENTWOOD'),
('Region 1635', '14', 'B', '1635', 'TT', 'TABAQUITE, BROTHERS ROAD'),
('Region 1637', '2', 'F', '1637', 'Ne', 'MOUND HOUSE, SILVER SPRINGS, VIRGINIA CITY, DAYTON'),
('Region 1638', '1', 'G', '1638', 'Ca', 'SAN BERNARDINO, DEVORE, LYTLE CREEK, MUSCOY, FONTANA, ROSENA RANCH'),
('Region 1639', '14', 'I', '1639', 'Fl', 'JUPITER'),
('Region 1640', '12', 'D', '1640', 'Ar', 'SCOTTSDALE'),
('Region 1641', '1', 'N', '1641', 'Ca', 'AGUANGA, ANZA'),
('Region 1642', '5', 'H', '1642', 'Al', 'CLANTON, JEMISON, THORSBY, VERBENA, MAPLESVILLE, STANTON'),
('Region 1643', '14', 'L', '1643', 'Fl', 'POLK COUNTY, HAINES CITY, DUNDEE, LAKE ALFRED, DAVENPORT, CYPRESS GARDENS, WINTER HAVEN, KISSIMMEE, CLEMONT, CELEBRATION, REUNION'),
('Region 1644', '3', 'L', '1644', 'Ne', 'FALLSBURG, LOCH SHELDRAKE, MOUNTAIN DALE, MOUNTAINDALE, SOUTH FALLSBURG, S FALLSBURG'),
('Region 1645', '2', 'C', '1645', 'Ca', 'STANISLAUS COUNTY, SAN JOAQUIN COUNTY, EMPIRE, ESCALON, MODESTO, OAKDALE, KNIGHTS FERRY, VALLEY HOME, RIVERBANK, SALIDA'),
('Region 1646', '6', 'A', '1646', 'Wi', 'RACINE, S MILWAUKEE, SOUTH MILWAUKEE, OAK CREEK, FRANKLIN, NORTH BAY, WINDPOINT, STURTEVANT, UNION GROVE, YORKVILLE, CALEDONIA, FRANKSVILLE, RAYMOND, NORTH CAPE, KENOSHA, SOMERS'),
('Region 1647', '1', 'P', '1647', 'Ca', 'LOS ANGELES, WEST ADAMS, MID CITY, MID WILSHIRE'),
('Region 1648', '8', 'F', '1648', 'Mi', 'WHITTEMORE'),
('Region 1649', '5', 'G', '1649', 'Ge', 'SOUTH FULTON COUNTY, METRO ATLANTA, FAIRBURN, COLLEGE PARK, UNION CITY, DOUGLASVILLE, CLAYTON COUNTY'),
('Region 165', '1', 'N', '165', 'Ca', 'CRESTLINE, GREEN VALLEY LAKE, LAKE ARROWHEAD, MOUNTAIN RESORTS, RUNNING SPRINGS'),
('Region 1650', '2', 'J', '1650', 'Ca', 'FREEMONT'),
('Region 1651', '80', 'Z', '1651', 'Ca', 'COACHELLA'),
('Region 1652', '5', 'G', '1652', 'Te', 'TELLICO PLAINS, TELLICO PLNS'),
('Region 1653', '10', 'O', '1653', 'Ca', 'HANFORD'),
('Region 1654', '12', 'D', '1654', 'Ar', 'PHOENIZ, GOODYEAR, AVONDALE, SURPRISE'),
('Region 1656', '11', 'R', '1656', 'Ca', 'CARLSBAD, OCEANSIDE'),
('Region 166', '2', 'E', '166', 'Ne', 'RENO, SPARKS'),
('Region 168', '11', 'V', '168', 'Ca', 'ALPINE, EL CAJON, JAMUL, LA MESA, LAKESIDE, RANCHO SAN DIEGO, SANTEE, SPRING VALLEY'),
('Region 169', '8', 'G', '169', 'Mi', 'BURTON, FENTON, FLINT, GOODRICH, GRAND BLANC AREA, HOLLY'),
('Region 17', '1', 'D', '17', 'Ca', 'HAWTHORNE, HERMOSA BEACH, INGLEWOOD, LAWNDALE, MANHATTAN BEACH, REDONDO BEACH NORTH, TORRANCE NORTH'),
('Region 172', '12', 'B', '172', 'Ar', 'DEWEY, HUMBOLDT, KIRKLAND, MAYER, PAULDEN, PRESCOTT, PRESCOTT VALLEY'),
('Region 174', '10', 'V', '174', 'Ca', 'ARLETA, CHATSWORTH, GRANADA HILLS, LAKE VIEW TERR, MISSION HILLS, NORTH HILLS, NORTHRIDGE, PACOIMA, SAN FERNANDO, SHADOW HILLS, SUN VALLEY, SYLMAR, VAN NUYS, PORTER RANCH'),
('Region 176', '8', 'D', '176', 'Mi', 'CHARLOTTE, EATON RAPIDS, GRAND LEDGE, LANSING, OLIVET, POTTERVILLE, WAVERLY'),
('Region 177', '11', 'E', '177', 'Ca', 'BELMONT SHORE, LONG BEACH, LOS ALTOS'),
('Region 178', '7', 'O', '178', 'Ha', 'HONOLULU, KAIMUKI, KALIHI, KAPAHULU, KAPALAMA, KAPIOLANI, MAKIKI, MANOA, MOILIILI, PALAMA, PALOLO, WAIKIKI'),
('Region 18', '1', 'D', '18', 'Ca', 'HERMOSA BEACH, MANHATTAN BEACH, REDONDO BEACH'),
('Region 180', '10', 'Q', '180', 'Ca', 'BALLARD, BUELLTON, GAVIOTA, LOS ALAMOS, LOS OLIVOS, SANTA YNEZ, SOLVANG'),
('Region 181', '10', 'A', '181', 'Ca', 'BAKERSFIELD, KERN  COUNTY, EDISON, OILDALE, BAKERSFIELD NE, LAMONT, ARVIN, NORTHEAST BAKERSFLD'),
('Region 183', '6', 'D', '183', 'Il', 'CHICAGO NORTHWEST, EUGENE FIELD PARK, GOMPERS PARK, GREEN BRIAR PARK, HARWOOD HEIGHTS, HOLLYWOOD PARK, LINCOLNWOOD, NORWOOD PARK, PETERSON PARK, RIVER PARK, SKOKIE, SAUGANASH, EDGEBROOK, WILDWOOD, NORTH PARK, JEFFERSON PARK'),
('Region 184', '6', 'C', '184', 'Il', 'BATAVIA, CAROL STREAM, ST. CHARLES, WARRENVILLE, WAYNE, WEST CHICAGO, WINFIELD, BARTLETT'),
('Region 186', '3', 'B', '186', 'Co', 'TRUMBULL'),
('Region 187', '1', 'R', '187', 'Ca', 'MORENO VALLEY, RANCHO BELAGO, SUNNYMEAD RANCH, MORENO VALLEY RANCH, EDGEMONT'),
('Region 188', '7', 'E', '188', 'Ha', 'AMR, CAMP SMITH, FT SHAFTER, MCGREW POINT, PEARL CITY PENINSULA, PEARL HARBOR, RED HILL, AIEA, HICKAM FIELD'),
('Region 19', '1', 'P', '19', 'Ca', 'BALDWIN HILLS, CULVER CITY, MAR VISTA, PALMS, WEST LOS ANGELES, PLAYA VISTA'),
('Region 190', '8', 'C', '190', 'Mi', 'CLINTON TOWNSHIP, MACOMB, SHELBY TOWNSHIP, TROY, UTICA, STERLING HEIGHTS'),
('Region 191', '14', 'A', '191', 'Fl', 'SUNSET PARK'),
('Region 193', '6', 'H', '193', 'Il', 'CORTLAND, CRESTON, DE KALB, EARLVILLE, HINCKLEY, LEE, MALTA, PAW PAW, SHABBONA, SOMONAUK, STEWARD, WATERMAN'),
('Region 194', '13', 'I', '194', 'Pe', 'GETTYSBURG, FAIRFIELD, BIGLERVILLE, LITTLESTOWN, NEW OXFORD, HANOVER, EMMITSBURG'),
('Region 195', '8', 'C', '195', 'Mi', 'CHESTERFIELD, CHESTERFIELD TWP, NEW BALTIMORE, NEW HAVEN'),
('Region 198', '9', 'R', '198', 'Ut', 'CLEARFIELD, CLINTON, HILL AIR FORCE BASE, SUNSET, WEST POINT'),
('Region 199', '3', 'T', '199', 'Ne', 'PLEASANTVILLE'),
('Region 2', '1', 'C', '2', 'Ca', 'ARCADIA, DUARTE, MONROVIA, PASADENA - EAST, SIERRA MADRE'),
('Region 20', '1', 'P', '20', 'Ca', 'MAR VISTA, MARINA DEL REY, SANTA MONICA, VENICE'),
('Region 200', '3', 'T', '200', 'Ne', 'BREWSTER, NORTH SALEM, SOMERS, SOUTH SALEM, CROTON FALLS'),
('Region 201', '3', 'T', '201', 'Ne', 'OSSINING'),
('Region 202', '3', 'T', '202', 'Ne', 'BRIARCLIFF MANOR'),
('Region 204', '3', 'T', '204', 'Ne', 'BANKSVILLE, BEDFORD CORNERS, MT. KISCO, NEW CASTLE, NORTH CASTLE, NORTH WHITE PLAINS, PLEASANTVILLE, THORNWOOD'),
('Region 205', '8', 'C', '205', 'Mi', 'BROWNSTOWN, FLATROCK, GROSSE ILE, RIVERVIEW, ROCKWOOD, ROMULUS, TRENTON, WOODHAVEN, WYANDOTTE, ALLEN PARK, LINCOLN PARK, TAYLOR, NEW BOSTON, GIBRALTER'),
('Region 206', '12', 'A', '206', 'Ar', 'MARANA, TUCSON'),
('Region 208', '6', 'U', '208', 'Ka', 'ANDALE, COLWICH, GODDARD, MAIZE, WICHITA WEST'),
('Region 21', '1', 'D', '21', 'Ca', 'HAWTHORNE, LAWNDALE, LENNOX'),
('Region 210', '6', 'C', '210', 'Il', 'BURR RIDGE, CLARENDON HILLS, HINSDALE, OAK BROOK, WESTMONT, WILLOWBROOK'),
('Region 211', '8', 'B', '211', 'Mi', 'KALAMAZOO, OSHTEMO, PARCHMENT'),
('Region 212', '8', 'B', '212', 'Mi', 'AUGUSTA, CLIMAX, COMSTOCK, DELTON, GALESBURG, RICHLAND, SCOTTS'),
('Region 213', '11', 'Q', '213', 'Ca', 'IRVINE NORTH, TUSTIN, TUSTIN RANCH, PORTOLA SPRINGS, WOODBURY, STONEGATE'),
('Region 214', '1', 'C', '214', 'Ca', 'PASADENA, SAN MARINO, SOUTH PASADENA, LOS ANGELES, SAN GABRIEL'),
('Region 215', '1', 'U', '215', 'Ca', 'HACIENDA HEIGHTS, LA PUENTE, ROWLAND HEIGHTS, WALNUT'),
('Region 216', '12', 'A', '216', 'Ar', 'FT. THOMAS, GRAHAM COUNTY, PIMA, SAFFORD, SOLOMON, THATCHER'),
('Region 217', '12', 'A', '217', 'Ar', 'DAVIS MONTHAN AFB, TUCSON SOUTHEAST, VAIL'),
('Region 218', '2', 'D', '218', 'Ca', 'DAVIS'),
('Region 220', '3', 'T', '220', 'Ne', 'SLEEPY HOLLOW, TARRYTOWN, ELMSFORD'),
('Region 221', '3', 'T', '221', 'Ne', 'MT PLEASANT, NORTH WHITE PLAINS, THORNWOOD, POCANTICO HILLS, MT PLEASANT SCHOOLS, VALHALLA SCHOOLS'),
('Region 222', '3', 'E', '222', 'Ne', 'NEW HARTFORD, NEW YORK MILLS'),
('Region 223', '2', 'C', '223', 'Ca', 'BAY POINT, CLAYTON, CONCORD, PITTSBURG, WALNUT CREEK, PLEASANT HILL, MARTINEZ'),
('Region 224', '12', 'A', '224', 'Ar', 'TUCSON SOUTHWEST, TUCSON'),
('Region 225', '12', 'D', '225', 'Ar', 'AVONDALE, BUCKEYE, EL MIRAGE, ESTRELLA MOUNTAIN, GOODYEAR, LITCHFIELD PARK, SURPRISE, TOLLESON, TONOPAH, VERRADO, WADDELL, WEST PHX'),
('Region 23', '1', 'U', '23', 'Ca', 'BALDWIN PARK, BASSETT, EAST WHITTIER, HACIENDA HEIGHTS, LA PUENTE, ROWLAND HEIGHTS, WHITTIER'),
('Region 232', '12', 'B', '232', 'Ar', 'VERDE VALLEY'),
('Region 234', '11', 'V', '234', 'Ca', 'ALPINE, EL CAJON, LAKESIDE, SANTEE, RAMONA'),
('Region 239', '9', 'R', '239', 'Ut', 'LAYTON'),
('Region 24', '11', 'Z', '24', 'Ca', 'DOWNEY'),
('Region 240', '3', 'T', '240', 'Ne', 'ARDSLEY, IRVINGTON, TARRYTOWN'),
('Region 242', '10', 'W', '242', 'Ca', 'FILLMORE, PIRU, RANCHO SESPE'),
('Region 244', '6', 'D', '244', 'Il', 'ARLINGTON HEIGHTS, HOFFMAN ESTATES, PALATINE, ROLLING MEADOWS, SCHAUMBURG'),
('Region 248', '6', 'N', '248', 'Ne', 'ALBION, BELLWOOD, COLUMBUS, DUNCAN, GENOA, HUMPHREY, MONROE, OSCEOLA, PLATTE CENTER, RICHLAND, RISING CITY, SHELBY, STROMSBURG'),
('Region 249', '2', 'B', '249', 'Ca', 'SAN BRUNO, SOUTH SAN FRANCISCO, DALY CITY, PACIFICA, MILLBRAE'),
('Region 25', '2', 'A', '25', 'Ca', 'LA HONDA, PORTOLA VALLEY, WOODSIDE'),
('Region 250', '8', 'E', '250', 'Mi', 'DECATUR, GOBLES, LAWTON, PAW PAW, LAWRENCE, BLOOMINGDALE'),
('Region 251', '3', 'T', '251', 'Ne', 'BEDFORD HILLS, BEDFORD VILLAGE, KATONAH, MT KISCO, POUND RIDGE, CHAPPAQUA, BREWSTER, LINCOLNDALE'),
('Region 253', '6', 'U', '253', 'Ka', 'BENTLEY, HALSTEAD, KECHI, NEWTON, PARK CITY, SEDGWICK, VALLEY CENTER, WHITEWATER'),
('Region 254', '10', 'V', '254', 'Ca', 'BURBANK, GLENDALE, NORTH HOLLYWOOD, SHADOW HILLS, SUN VALLEY, SUNLAND, TOLUCA LAKE, TUJUNGA'),
('Region 255', '10', 'O', '255', 'Ca', 'TIPTON, TULARE, VISALIA'),
('Region 256', '2', 'J', '256', 'Ca', 'AROMAS, CASTROVILLE, HOLISTER, MARINA, MONTEREY NORTH, MOSS LANDING, NORTH MONTEREY COUNT, PAJARO, PRUNEDALE, ROYAL OAKS, SALINAS'),
('Region 257', '12', 'B', '257', 'Ar', 'FLAGSTAFF, GRAND CANYON, WILLIAMS, BELLEMONT, PARKS, MUNDS PARK'),
('Region 258', '11', 'S', '258', 'Ca', 'LA MESA, LEMON GROVE, SPRING VALLEY'),
('Region 26', '2', 'A', '26', 'Ca', 'PALO ALTO, EAST PALO ALTO, LOS ALTO HILLS, STANFORD UNIVERSITY'),
('Region 266', '3', 'A', '266', 'Ne', 'BROOKLYN-SOUTH, QUEENS, MARINE PARK, MILL BASIN, MADISON, MIDWOOD, BRIGHTON BEACH, MANHATTAN BEACH, CONEY ISLAND, GRAVESEND, BENSONHURST, HOMECREST, GERRITSEN BEACH, OCEAN PARKWAY, FLATBUSH, FLATLANDS, ROCKAWAY, BAY RIDGE, SHEEPSHEAD BAY, FARRAGUT, SUNSET PARK, STARRETT CITY, CANARSIE'),
('Region 269', '7', 'E', '269', 'Ha', 'BARBERS POINT, HONOKAI HALE, IROQUOIS POINT, KALAELOA, KAPOLEI, MAILI, MAKAHA, MAKAKILO, NANAKULI, WAIANAE'),
('Region 27', '2', 'J', '27', 'Ca', 'CAMPBELL, CUPERTINO, LOS GATOS, MONTE SERENO, SARATOGA, WEST SAN JOSE'),
('Region 272', '12', 'E', '272', 'Te', 'ANTHONY, CANUTILLO, UNIVERSITY AREA, WEST SIDE EL PASO'),
('Region 273', '12', 'B', '273', 'Ar', 'ALPINE, EAGAR, GREER, NUTRIOSO, RESERVE, SPRINGERVILLE'),
('Region 274', '7', 'A', '274', 'Ha', 'HILO, HONOMU, KEAAU, KURTISTOWN, LAUPAHOEHOE, MT. VIEW, PAHOA, PAPAIKOU, PEPEEKEO, VOLCANO'),
('Region 275', '5', 'B', '275', 'Te', 'CEDAR BLUFF, KARNS, KNOX COUNTY WEST, HARDIN VALLEY, BALL CAMP'),
('Region 277', '8', 'B', '277', 'Mi', 'FREEPORT, MIDDLEVILLE, T.K. SCHOOLS, HASTINGS, WAYLAND, DELTON'),
('Region 279', '5', 'B', '279', 'Te', 'BEARDEN, KNOXVILLE W, LAKESHORE, LYONS BEND, NORTHSHORE, ROCKY HILL, SEQUOYAH HILLS, UT &AMP; FORT SANDERS, WEST HILLS'),
('Region 28', '11', 'E', '28', 'Ca', 'ANAHEIM, GARDEN GROVE, MIDWAY CITY, SANTA ANA, STANTON, WESTMINSTER, FULLERTON'),
('Region 281', '2', 'C', '281', 'Ca', 'CONCORD, MARTINEZ, PACHECO, PLEASANT HILL, WALNUT CREEK'),
('Region 282', '2', 'F', '282', 'Ca', 'LAKE TAHOE SOUTH, ZEPHYR COVE, STATELINE'),
('Region 283', '8', 'G', '283', 'Mi', 'CORUNNA, FLINT, FLUSHING, MOUNT MORRIS, NEW LOTHROP'),
('Region 285', '11', 'R', '285', 'Ca', 'CARMEL MTN. RANCH, MIRA MESA, MIRAMAR, POWAY, RANCHO PENASQUITOS, SABER SPRING, SCRIPPS, SORRENTO MESA'),
('Region 286', '9', 'R', '286', 'Ut', 'ROY'),
('Region 287', '9', 'R', '287', 'Ut', 'HARRISVILLE, PLEASANT VIEW, WEBER COUNTY NORTH, WEBER HIGH AREA, NORTH OGDEN'),
('Region 289', '7', 'E', '289', 'Ha', 'HALEIWA, NORTH SHORE, SUNSET BEACH, WAIALUA'),
('Region 29', '10', 'V', '29', 'Ca', 'CANOGA PARK, RESEDA, WINNETKA'),
('Region 290', '11', 'S', '290', 'Ca', 'CHULA VISTA, NATIONAL CITY, SAN DIEGO (SOUTH), BONITA'),
('Region 292', '13', 'H', '292', 'Pe', 'DANVILLE'),
('Region 293', '13', 'Y', '293', 'Pe', 'COGAN STATION, LINDEN, WILLIAMSPORT, TROUT RUN'),
('Region 294', '10', 'A', '294', 'Ca', 'CALIENTE, KERN RIVER VALLEY, LAKE ISABELLA'),
('Region 295', '11', 'V', '295', 'Ca', 'ALPINE, DESCANSO, EL CAJON EAST, GUATAY, LAKESIDE, PINE VALLEY'),
('Region 297', '5', 'H', '297', 'Al', 'HOPE HULL, MAXWELL AFB, MAXWELL AFB-GUNTER A, MILLBROOK, MONTGOMERY, PIKE ROAD, PRATTVILLE, SHORTER, TUSKEGEE, WETUMPKA, GUNTER AFB, ECLECTIC, TALLASSEE'),
('Region 298', '8', 'E', '298', 'Mi', 'DOWAGIAC, MARCELLUS, SISTER LAKES, EAU CLAIRE, NILES, DECATUR, CASSOPOLIS'),
('Region 3', '1', 'B', '3', 'Ca', 'CLAREMONT, POMONA, MONTCLAIR, UPLAND, LA VERNE, GLENDORA, SAN DIMAS, ONTARIO, RANCHO CUCMONGA, MT BALDY'),
('Region 300', '6', 'C', '300', 'Il', 'BROOKFIELD, COUNTRYSIDE, INDIAN HEAD PARK, LA GRANGE, LA GRANGE HIGHLANDS, LA GRANGE PARK, WESTERN SPRINGS'),
('Region 304', '10', 'W', '304', 'Ca', 'EL RIO, OXNARD, OXNARD NORTH, PORT HUENEME'),
('Region 305', '2', 'C', '305', 'Ca', 'ALAMO, ANTIOCH, BAY POINT, BENECIA, CLAYTON, CLYDE, CONCORD, DANVILLE, LAFAYETTE, ORINDA, PACHECO, PITTSBURG, PLEASANT HILL, WALNUT CREEK'),
('Region 309', '12', 'C', '309', 'Ne', 'BAYARD, CLIFF, SILVER CITY, TYRONE, LORDSBURG'),
('Region 31', '1', 'B', '31', 'Ca', 'CHINO, CHINO HILLS, DIAMOND BAR, PHILLIPS RANCH, POMONA, ROWLAND HEIGHTS, WALNUT'),
('Region 310', '12', 'D', '310', 'Ar', 'ANTHEM, EL MIRAGE, GLENDALE, PEORIA, PHOENIX, SURPRISE, YOUNGTOWN'),
('Region 314', '6', 'A', '314', 'Il', 'ALDEN, CAPRON, HARVARD, HEBRON, WONDERLAKE, WOODSTOCK, SHARON, POPLAR GROVE'),
('Region 315', '10', 'O', '315', 'Ca', 'COTTON CENTER, DUCOR, PLAINVIEW, PORTERVILLE, SPRINGVILLE, STRATHMORE, TERRA BELLA, WOODVILLE'),
('Region 316', '2', 'F', '316', 'Ca', 'MAMMOTH LAKES, MONO COUNTY'),
('Region 318', '2', 'F', '318', 'Ne', 'CARSON CITY, GARDNERVILLE, TOPAZ, WELLINGTON, MINDEN'),
('Region 319', '12', 'C', '319', 'Te', 'BOVINA, CANNON AFB, CLOVIS, ELIDA, FARWELL, FRIONA, FT. SUMNER, GRADY, MELROSE, MULESHOE, TEXICO, TUCUMCARI, PORTALES, DORA'),
('Region 32', '1', 'G', '32', 'Ca', 'MT. BALDY, UPLAND, SAN ANTONIO HEIGHTS'),
('Region 322', '13', 'C', '322', 'Vi', 'JEFFERSON COUNTY, BERKELEY COUNTY, CLARK COUNTY, FREDERICK COUNTY'),
('Region 324', '3', 'T', '324', 'Ne', 'ARDSLEY, DOBBS FERRY, GREENBERG, HARTSDALE, HASTINGS, SCARSDALE'),
('Region 325', '5', 'G', '325', 'Te', 'GREENBACK, LENOIR CITY, LOUDON, PHILADELPHIA, SWEETWATER, VONORE'),
('Region 327', '3', 'E', '327', 'Ne', 'MARCY, NEW YORK MILLS, WHITESBORO, YORKVILLE'),
('Region 328', '2', 'C', '328', 'Ca', 'BIRDS LANDING, ISLETON, RIO VISTA, WALNUT GROVE'),
('Region 329', '12', 'A', '329', 'Ar', 'NOGALES, NOGALES-MEXICO, RIO RICO'),
('Region 33', '10', 'V', '33', 'Ca', 'ENCINO, NORTHRIDGE, RESEDA, SHERMAN OAKS, TARZANA, VAN NUYS, WINNEKTA, WOODLAND HILLS, LAKE BALBOA, NORTH HILLS, GRANADA HILLS, WEST HILLS'),
('Region 332', '2', 'S', '332', 'Or', 'THE DALLES, DUFUR, MOSIER'),
('Region 333', '8', 'B', '333', 'Mi', 'MARTIN, OTSEGO, PLAINWELL'),
('Region 335', '5', 'I', '335', 'Te', 'BULLS GAP, DANDRIDGE, JEFFERSON CITY, JEFFERSON CO, MORRISTOWN, NEW MARKET, TALBOTT, GRAINGER CO, GREENVILLE'),
('Region 337', '5', 'B', '337', 'Te', 'CORRYTON, FOUNTAIN CITY, GIBBS, HALLS, KNOXVILLE NORTH, UNION CO.'),
('Region 34', '1', 'D', '34', 'Ca', 'REDONDO BEACH, TORRANCE, HERMOSA BEACH'),
('Region 341', '11', 'V', '341', 'Ca', 'EL CAJON, LA MESA, LAKESIDE, SANTEE'),
('Region 343', '9', 'R', '343', 'Ut', 'FARR WEST, HARRISVILLE, HOOPER, PLAIN CITY, TAYLOR, WARREN, WEBER COUNTY WEST, WEST HAVEN, WEST WARREN, WEST WEBER'),
('Region 344', '3', 'E', '344', 'Ne', 'DEERFIELD, MARCY, SCHUYLER,NY, UTICA - NORTH'),
('Region 345', '14', 'I', '345', 'Fl', 'PALM BEACH WEST, WEST PALM BEACH'),
('Region 346', '8', 'D', '346', 'Mi', 'JACKSON, PARMA, SPRING ARBOR'),
('Region 35', '2', 'J', '35', 'Ca', 'CUPERTINO, SAN JOSE, SANTA CLARA, SARATOGA, SUNNYVALE, CAMPBELL'),
('Region 350', '12', 'A', '350', 'Ar', 'TUCSON, TUCSON NORTH CENTRAL'),
('Region 351', '9', 'R', '351', 'Ut', 'OGDEN CITY, SOUTH OGDEN, WASHINGTON HEIGHTS, UINTAH, NORTH OGDEN, SOUTH WEBER, ROY, RIVERDALE, CLEARFIELD, WILLARD, FRUIT HEIGHTS, PLAIN CITY'),
('Region 354', '9', 'B', '354', 'Ut', 'ALTA, BLUFFDALE, DRAPER, EAGLE MOUNTAIN, HERRIMAN, LEHI, MIDVALE, MURRAY, RIVERTON, SALT LAKE CITY, SANDY, SARATOGA SPRINGS, SOUTH JORDAN, WEST JORDAN, WEST VALLEY CITY'),
('Region 358', '7', 'E', '358', 'Ha', 'HAUULA, KAAAWA, KAHUKU, LAIE, PUNALUU'),
('Region 359', '10', 'A', '359', 'Ca', 'BAKERSFIELD, BAKERSFIELD NW, SHAFTER, OILDALE'),
('Region 36', '2', 'N', '36', 'Ca', 'BURLINGAME, BELMONT, FOSTER CITY, HILLSBOROUGH, SAN MATEO'),
('Region 360', '2', 'E', '360', 'Ne', 'FALLON'),
('Region 362', '6', 'D', '362', 'Il', 'DES PLAINES, GLENVIEW, GOLF, MORTON GROVE, NORTHBROOK, NORTHFIELD, WILMETTE'),
('Region 363', '10', 'E', '363', 'Ca', 'MOORPARK'),
('Region 364', '12', 'C', '364', 'Ne', 'LEMITAR, MAGDALENA, POLVADERA, SAN ACACIA, SAN ANTONIO, SOCORRO'),
('Region 367', '6', 'C', '367', 'Il', 'BOLINGBROOK, CREST HILL, JOLIET, LEMONT, LOCKPORT, PLAINFIELD, ROMEOVILLE'),
('Region 368', '12', 'E', '368', 'Ne', 'EL PASO, EL PASO CENTRAL, EL PASO NORTHEAST, EL PASO  EAST, EL PASO LOWER VALLEY, CHAPARRAL, FT BLISS, HORIZON CITY'),
('Region 37', '1', 'R', '37', 'Ca', 'CORONA, NORCO, EASTVALE'),
('Region 370', '2', 'E', '370', 'Ne', 'FERNLEY, SILVER SPRINGS, WADSWORTH'),
('Region 372', '6', 'A', '372', 'Il', 'HAWTHORN WOODS, IVANHOE, LIBERTYVILLE, LONG GROVE, MUNDELEIN, VERNON HILLS, WAUCONDA, LAKE ZURICH, FREMONT'),
('Region 373', '9', 'I', '373', 'Id', 'RIGBY'),
('Region 374', '3', 'E', '374', 'Ne', 'BARNEVELD, FLOYD, HINCKLEY, HOLLAND PATENT, PROSPECT, STITTVILLE'),
('Region 376', '12', 'A', '376', 'Ar', 'CLIFTON, DUNCAN, MORENCI'),
('Region 377', '3', 'G', '377', 'Ne', 'FRANKFORT, ILION, MOHAWK'),
('Region 381', '7', 'E', '381', 'Ha', 'AIEA, PEARL CITY'),
('Region 382', '10', 'A', '382', 'Ca', 'FRAZIER PARK, GORMAN, LEBEC, PINE MOUNTAIN CLUB, LANCASTER'),
('Region 383', '3', 'G', '383', 'Ne', 'FRANKFORT, HERKIMER, ILION, MOHAWK'),
('Region 384', '9', 'B', '384', 'Ut', 'DELTA, DESERT, HINCKLEY, LEAMINGTON, LYNNDYL, OAK CITY, OASIS'),
('Region 385', '5', 'I', '385', 'Te', 'SCOTT COUNTY'),
('Region 39', '10', 'W', '39', 'Ca', 'VENTURA'),
('Region 390', '5', 'G', '390', 'Te', 'CLINTON, OAK RIDGE, OLIVER SPRINGS'),
('Region 393', '10', 'D', '393', 'Ca', 'ACTON, LAKE LOS ANGELES, LANCASTER, LEONA VALLEY, LITTLEROCK, PALMDALE, QUARTZ HILL'),
('Region 396', '6', 'A', '396', 'Il', 'GRAYSLAKE, GURNEE, HAINESVILLE, LAKE VILLA, MCHENRY, THIRD LAKE, WILDWOOD');
INSERT INTO `rs_sar` (`portalName`, `section`, `area`, `region`, `state`, `communities`) VALUES
('Region 397', '1', 'S', '397', 'Ar', 'BULLHEAD CITY, FORT MOHAVE, LAUGHLIN, NEEDLES'),
('Region 399', '6', 'F', '399', 'Il', 'ELMHURST'),
('Region 4', '10', 'E', '4', 'Ca', 'AGOURA, AGOURA HILLS, CALABASAS, LAKE SHERWOOD, NORTH RANCH, OAK PARK, WESTLAKE VILLAGE'),
('Region 40', '1', 'C', '40', 'Ca', 'ROSEMEAD, SAN GABRIEL'),
('Region 400', '10', 'O', '400', 'Ca', 'FRESNO'),
('Region 403', '7', 'A', '403', 'Ha', 'HONOKAA, KOHALA, WAIKOLOA, KAMUELAWAIMEA'),
('Region 404', '12', 'A', '404', 'Ar', 'BISBEE, NACO'),
('Region 405', '3', 'E', '405', 'Ne', 'LEE (ONEIDA COUNTY), ROME'),
('Region 407', '3', 'G', '407', 'Ne', 'HERKIMER'),
('Region 41', '11', 'L', '41', 'Ca', 'LAGUNA NIGUEL, ALISO VIEJO, DANA POINT, MONARCH BEACH'),
('Region 412', '6', 'N', '412', 'Ne', 'BRADY, HERSHEY, MAXWELL, MAYWOOD, NORTH PLATTE, STAPLETON, WELLFLEET'),
('Region 414', '5', 'F', '414', 'Al', 'CULLMAN'),
('Region 415', '13', 'D', '415', 'Pe', 'CONFLUENCE, MARKLETON, ROCKWOOD, SOMERSET'),
('Region 416', '13', 'D', '416', 'Pe', 'BERLIN, BROTHERS VALLEY TWP'),
('Region 417', '8', 'G', '417', 'Mi', 'FENTON, GAINES, GRAND BLANC, HOLLY, LAKE FENTON, LINDEN, SWARTZ CREEK'),
('Region 418', '6', 'D', '418', 'Il', 'BUCKTOWN, CHICAGO LAKEFRONT, DEARBORN PARK, GOLD COAST, LAKEVIEW, LINCOLN PARK, LOGAN SQUARE, RAVENSWOOD, RIVER EAST, RIVER NORTH, ROSCOE VILLAGE, STREETERVILLE'),
('Region 419', '6', 'E', '419', 'Il', 'BELLEVUE, DUBUQUE, EAST DUBUQUE, EPWORTH, FARLEY, HAZEL GREEN, PEOSTA, SHERRILL, GALENA'),
('Region 42', '10', 'E', '42', 'Ca', 'NEWBURY PARK, OAK PARK, THOUSAND OAKS, WESTLAKE VILLAGE'),
('Region 420', '6', 'N', '420', 'Ne', 'NORFOLK'),
('Region 422', '9', 'R', '422', 'Ut', 'OGDEN VALLEY'),
('Region 423', '6', 'F', '423', 'Il', 'CHICAGO (BEVERLY), CHICAGO (HYDE PARK), EVERGREEN PARK, MERRIONETTE PARK, OAK LAWN'),
('Region 424', '13', 'K', '424', 'Pe', 'LEWISBURG, WEST MILTON, WINFIELD'),
('Region 425', '6', 'D', '425', 'Il', 'GLENCOE, KENILWORTH, WINNETKA, NORTHFIELD, WILMETTE'),
('Region 427', '12', 'B', '427', 'Ar', 'JOSEPH CITY, LEUPP, WINSLOW'),
('Region 428', '6', 'A', '428', 'Il', 'FOX LAKE, GRAYSLAKE, INGLESIDE, LAKE VILLA, ROUND LAKE, ROUND LAKE BCH, ROUND LAKE HGTS, ROUND LAKE PK, VOLO, HAINESVILLE, LAKEMOOR, MCHENRY, LINDENHURST, THIRD LAKE, GURNEE'),
('Region 429', '3', 'G', '429', 'Ne', 'COLD BROOK, POLAND, TOWN OF RUSSIA'),
('Region 43', '2', 'A', '43', 'Ca', 'LOS ALTOS (NORTH), LOS ALTOS HILLS, LOS ALTOS SCH DIST, MT VIEW (IN LASD), PALO ALTO IN LASD'),
('Region 430', '13', 'D', '430', 'Pe', 'NORTH STAR'),
('Region 432', '3', 'G', '432', 'Ne', 'CARTHAGE'),
('Region 434', '9', 'I', '434', 'Id', 'BLACKFOOT'),
('Region 436', '2', 'D', '436', 'Ca', 'ESPARTO, WINTERS'),
('Region 44', '2', 'A', '44', 'Ca', 'SUNNYVALE'),
('Region 440', '5', 'B', '440', 'Te', 'CATON\'S CHAPEL, GATLINBURG, KODAK, NEW CENTER, NORTHVIEW, PIGEON FORGE, PITTMAN CENTER, SEVIERVILLE, WSCC'),
('Region 441', '2', 'E', '441', 'Ca', 'BECKWOURTH, BLAIRSDEN, CALPINE, CHILCOOT, CLIO, CROMBERG, DOWNIEVILLE, GOODYEARS BAR, GRAEAGLE, JOHNSVILLE, LOYALTON, PORTOLA, SIERRA CITY, SIERRAVILLE, VINTON'),
('Region 443', '1', 'H', '443', 'Ca', 'BERMUDA DUNES, COACHELLA, INDIO, LA QUINTA, PALM DESERT'),
('Region 445', '13', 'B', '445', 'Pe', 'CASTANEA, FARRANDSVILLE, LOCK HAVEN, MC ELHATTEN, WOOLRICH'),
('Region 446', '12', 'B', '446', 'Ar', 'HOLBROOK, WOODRUFF, JOSEPH CITY'),
('Region 448', '8', 'C', '448', 'Mi', 'CASCO, COLUMBUS, LENOX, RICHMOND, RICHMOND TOWNSHIP, NEW HAVEN, ARMADA'),
('Region 449', '2', 'E', '449', 'Ne', 'HAWTHORNE'),
('Region 45', '2', 'A', '45', 'Ca', 'LOS ALTOS SOUTH, MOUNTAIN VIEW'),
('Region 450', '14', 'A', '450', 'Fl', 'BIRD ROAD, HAMMOCKS, HORSE COUNTRY, KENDALL-W, LKS OF KENDALE, LKS OF THE MEADOW, MILLER POND, OLYMPIA VILLAGE, ROYALE GREEN, WESTWOOD, WINSTON PARK, WESTCHESTER, KENDALL'),
('Region 451', '6', 'E', '451', 'Io', 'HONEY CREEK, LOGAN, MISSOURI VALLEY'),
('Region 452', '13', 'A', '452', 'Pe', 'ALTOONA, BELLWOOD, LOGAN TOWNSHIP'),
('Region 455', '3', 'T', '455', 'Ne', 'BUCHANAN, CORTLANDT MANOR, CROTON-ON-HUDSON, GARRISON, MONTROSE, VERPLANK'),
('Region 457', '6', 'N', '457', 'Ne', 'SCHUYLER'),
('Region 458', '6', 'C', '458', 'Il', 'LEMONT'),
('Region 459', '8', 'C', '459', 'Mi', 'MACOMB, SHELBY TOWNSHIP, UTICA, STERLING HEIGHTS, CLINTON TTOWNSHIP, WASHINGTON TOWNSHIP, RAY TOWNSHIP, ROMEO, CHESTERFIELD'),
('Region 46', '10', 'S', '46', 'Ca', 'SANTA CLARITA, SAUGUS, VALENCIA'),
('Region 462', '1', 'R', '462', 'Ca', 'PEDLEY, RUBIDOUX, JURUPA, SOUTH FONTANA, RIVERSIDE, MIRA LOMA, COLTON'),
('Region 469', '3', 'G', '469', 'Ne', 'BOONVILLE, FORESTPORT, WEST LEYDEN'),
('Region 47', '1', 'R', '47', 'Ca', 'RIVERSIDE &AMP; SURROUND, ORANGECREST, WOODCREST'),
('Region 470', '12', 'B', '470', 'Ar', 'CLAY SPRINGS, LINDEN, PINEDALE, SHOW LOW, HEBER, VERNON, OVERGAARD, SNOWFLAKE, WHITE MOUNTAIN LAKE'),
('Region 471', '3', 'E', '471', 'Ne', 'BROOKFIELD, EARLVILLE, HAMILTON, HUBBARDSVILLE, LEBANON, MADISON, POOLVILLE, RANDALLSVILLE, SHERBURNE'),
('Region 473', '3', 'A', '473', 'Ne', 'BROOKLYN, BROOKLYN-CENTRAL'),
('Region 475', '13', 'H', '475', 'Pe', 'MILLVILLE'),
('Region 476', '8', 'G', '476', 'Mi', 'CLIO'),
('Region 479', '10', 'A', '479', 'Ca', 'HART FLAT, KEENE, MOJAVE, TEHACHAPI'),
('Region 48', '7', 'O', '48', 'Ha', 'DIAMOND HEAD, KAIMUKI, AINA HAINA, NIU VALLEY, EAST HONOLULU, KAHALA, HAWAII KAI'),
('Region 482', '13', 'C', '482', 'Ma', 'WASHINGTON COUNTY, JEFFERSON COUNTY'),
('Region 483', '9', 'B', '483', 'Ut', 'DINOSAUR, JENSEN, LA POINT, MAESER, NAPLES, VERNAL, RANGLEY, MANILA, DUTCH JOHN'),
('Region 488', '2', 'E', '488', 'Ne', 'LOVELOCK'),
('Region 49', '6', 'U', '49', 'Ka', 'ANDOVER, AUGUSTA, BEL AIRE, BENTON, DERBY, ELDORADO, PARK CITY, ROSE HILL, TOWANDA, WICHITA EAST'),
('Region 491', '6', 'U', '491', 'Ka', 'CLEARWATER'),
('Region 492', '3', 'E', '492', 'Ne', 'WATERVILLE AREA'),
('Region 493', '10', 'O', '493', 'Ca', 'COALINGA, HURON, AVENAL, HANFORD'),
('Region 498', '5', 'C', '498', 'Al', 'MADISON, HUNTSVILLE, TRIANA, HARVEST, TONEY'),
('Region 5', '11', 'K', '5', 'Ca', 'FOUNTAIN VALLEY, GARDEN GROVE, HUNTINGTON BEACH, SANTA ANA, WESTMINSTER NORTHEST'),
('Region 50', '1', 'N', '50', 'Ca', 'HIGHLAND, LOMA LINDA, MENTONE, REDLANDS, YUCAIPA'),
('Region 500', '8', 'D', '500', 'Mi', 'JACKSON, JACKSON-NORTHWEST, PLEASANT LAKE, RIVES JUNCTION, LESLIE'),
('Region 5001', '99', 'C', '5001', 'Fl', 'MIAMI, WESTON, MIRAMAR, GREENACRES, HIALEAH'),
('Region 5010', '99', 'C', '5010', 'Ar', 'GILBERT, TEMPE, CHANDLER, MESA, MARICOPA'),
('Region 503', '12', 'D', '503', 'Ar', 'CHANDLER, GILBERT, MESA, TEMPE, SCOTTSDALE, SAN TAN VALLEY, SOUTH PHOENIX, GUADALUPE'),
('Region 504', '13', 'H', '504', 'Pe', 'ALMEDIA, BLOOMSBURG, BUCKHORN, CATAWISSA, ESPY, LIGHTSTREET, LIME RIDGE, MAINVILLE, MIFFLINVILLE, ORANGEVILLE'),
('Region 506', '13', 'K', '506', 'Pe', 'BEAVER SPRINGS, BEAVERTOWN, MCCLURE, TROXELVILLE'),
('Region 507', '1', 'S', '507', 'Ar', 'CHEMEHUEVI, FT MOHAVE, GOLDEN SHORES, MOHAVE VALLEY, NEEDLES, TOPOCK'),
('Region 508', '12', 'D', '508', 'Ar', 'AGUILA, CIRCLE CITY, CONGRESS, MORRISTOWN, PEEPLES VALLEY, SURPRISE, WICKENBURG, WITTMANN, YARNELL'),
('Region 509', '13', 'K', '509', 'Pe', 'ALLENWOOD, DEWART, MCEWENSVILLE, TURBOTVILLE, WARRIOR RUN, WASHINGTONVILLE, WATSONTOWN, WHITE DEER, EXCHANGE'),
('Region 51', '9', 'R', '51', 'Ut', 'BOUNTIFUL, CENTERVILLE, FARMINGTON, FRUIT HEIGHTS, KAYSVILLE, LAYTON'),
('Region 510', '13', 'H', '510', 'Pe', 'COAL TWP, ELSYBURG, GOWEN CITY, KULPMONT, MARION HEIGHTS, MT. CARMEL, PAXINOS, SHAMOKIN'),
('Region 511', '11', 'V', '511', 'Ca', 'EL CAJON, JAMUL, SPRING VALLEY'),
('Region 5111', '99', 'C', '5111', 'Te', 'EL PASO'),
('Region 5118', '99', 'C', '5118', 'Ca', 'CORONA, NORCO, EASTVALE, RIVERSIDE'),
('Region 5119', '99', 'C', '5119', 'Ar', 'BENSON, POMERENE, ST DAVID'),
('Region 512', '8', 'G', '512', 'Mi', 'FLINT (SOUTHWEST), FLINT TOWNSHIP (SOUT, GAINES, LENNON, SWARTZ CREEK'),
('Region 514', '1', 'P', '514', 'Ca', 'CENTRAL LOS ANGELES'),
('Region 515', '12', 'C', '515', 'Ne', 'EL CERRO, LAS MARAVILLAS, LOS CHAVEZ, RIO COMMUNITIES, CYPRUS GARDENS, TOME VISTA, LOS LUNAS, ALBUQUERQUE, BELEN, BOSQUE FARMS, ISLETA, PERALTA, MEADOW LAKE, TOME, PACITOS DEL CIELO, JARALES, VEGUITA'),
('Region 516', '13', 'Y', '516', 'Pe', 'ANTES FORT, AVIS, JERSEY SHORE, LINDEN, SALLADASBURG, WILLIAMSPORT'),
('Region 517', '11', 'K', '517', 'Ca', 'SANTA ANA, COSTA MESA, GARDEN GROVE, WESTMINSTER, TUSTIN, ORANGE, FOUNTAIN VALLEY'),
('Region 518', '2', 'E', '518', 'Ne', 'YERINGTON'),
('Region 519', '13', 'Y', '519', 'Pe', 'LOYALSOCK'),
('Region 52', '8', 'C', '52', 'Mi', 'EASTPOINTE, HARPER WOODS, ROSEVILLE, DETROIT'),
('Region 522', '13', 'K', '522', 'Pe', 'NORTHUMBERLAND, PAXINOS, SNYDERTOWN, SUNBURY'),
('Region 525', '2', 'S', '525', 'Wa', 'CARSON, NORTH BONNEVILLE, SKAMANIA, STEVENSON, CASCADE LOCKS'),
('Region 526', '11', 'Z', '526', 'Ca', 'BELL, CUDAHY, HUNTINGTON PARK, MAYWOOD'),
('Region 527', '7', 'A', '527', 'Ha', 'KONA NORTH, KONA SOUTH'),
('Region 528', '12', 'E', '528', 'Te', 'CENTER POINT, COMFORT, HARPER, HUNT, INGRAM, KERRVILLE'),
('Region 53', '2', 'S', '53', 'Or', 'COBURG, CRESWELL, ELMIRA, EUGENE, HARRISBURG, JUNCTION CITY, MONROE, VENETA, WEST SPRINGFIELD'),
('Region 535', '2', 'F', '535', 'Ne', 'KINGS BEACH, TAHOE CITY'),
('Region 536', '9', 'M', '536', 'Mo', 'AMSTERDAM/ CHURCH HI, BELGRADE, BOZEMAN, MANHATTAN, THREE FORKS'),
('Region 538', '10', 'D', '538', 'Ca', 'BALDY MESA, EL MIRAGE, OAK HILLS, PHELAN, PINON HILLS, WRIGHTWOOD'),
('Region 54', '11', 'E', '54', 'Ca', 'ARTESIA, BUENA PARK, CERRITOS, E. LAKEWOOD, HAWAIIAN GARDENS, LA PALMA, NORWALK'),
('Region 543', '12', 'D', '543', 'Ar', 'BYLAS, GLOBE, KEARNY, MIAMI, SAN CARLOS'),
('Region 544', '1', 'R', '544', 'Ca', 'HOMELAND, MEAD VALLEY, MORENO VALLEY, NUEVO, PERRIS, ROMOLAND'),
('Region 546', '2', 'E', '546', 'Ne', 'GOLCONDA, GRASS VALLEY, OROVADA, PARADISE VALLEY, WINNEMUCCA'),
('Region 55', '11', 'K', '55', 'Ca', 'FOUNTAIN VALLEY, HUNTINGTON BEACH-CEN, WESTMINSTER'),
('Region 550', '9', 'I', '550', 'Id', 'CHALLIS'),
('Region 551', '5', 'I', '551', 'Te', 'HARROGATE,TN, LA FOLLETTE,TN, MIDDLESBORO,KY, PINEVILLE,KY, TAZEWELL,TN, NEW TAZEWELL, ROSE HILL, SHARPS CHAPEL, CUMBERLAND GAP, SPEEDWELL, HARLAN, EWING'),
('Region 552', '5', 'D', '552', 'Lo', 'BAYOU VISTA, BERWICK, MORGAN CITY, PATTERSON, ST MARY PARISH, AMELIA'),
('Region 556', '2', 'E', '556', 'Ne', 'CARLIN, ELKO, SPRING CREEK, WELLS'),
('Region 557', '5', 'C', '557', 'Te', 'FAYETTEVILLE-TN, PARK CITY-TN, PETERSBURG, MOORE COUNTY, LINCOLN COUNTY'),
('Region 56', '11', 'K', '56', 'Ca', 'HUNTINGTON BEACH-SO'),
('Region 567', '13', 'D', '567', 'Pe', 'CONEMAUGH TWP, DAVIDSVILLE, JEROME, JOHNSTOWN, HOOVERSVILLE, BOSWELL, WINDBER'),
('Region 568', '6', 'D', '568', 'Il', 'CHICAGO-EDGEBROOK, CHICAGO-SAUGANASH, EVANSTON 60203, LINCOLNWOOD, MORTON GROVE, NILES, SKOKIE, WILMETTE'),
('Region 569', '6', 'E', '569', 'Io', 'WOODBINE'),
('Region 57', '11', 'Q', '57', 'Ca', 'BALBOA ISLAND, CORONA DEL MAR, NEWPORT BEACH - EAST, NEWPORT COAST, EASTBLUFF'),
('Region 571', '8', 'J', '571', 'Mi', 'ADA, CASCADE, EAST GRAND RAPIDS, FOREST HILLS, GRAND RAPIDS, LOWELL'),
('Region 572', '5', 'E', '572', 'No', 'HAYWOOD COUNTY'),
('Region 574', '8', 'E', '574', 'Mi', 'BENTON HARBOR, BERRIEN SPRGS, EAU CLAIRE, RIVERSIDE, SODUS, ST JOSEPH, STEVENSVILLE'),
('Region 575', '8', 'B', '575', 'Mi', 'ALLEGAN, FENNVILLE'),
('Region 576', '13', 'H', '576', 'Pe', 'BENTON SCHOOL DIST., STILLWATER, ORANGEVILLE, HUNTINGTON MILLS, MILLVILLE'),
('Region 577', '13', 'K', '577', 'Pe', 'KREAMER, MIDDLEBURG, MT. PLEASANT MILLS, PENNS CREEK, RICHFIELD, PAXTONVILLE'),
('Region 578', '13', 'Y', '578', 'Pe', 'DUBOISTOWN, NISBET, SOUTH WILLIAMSPORT'),
('Region 58', '10', 'V', '58', 'Ca', 'ENCINO, LOS ANGELES, NORTH HOLLYWOOD, SHERMAN OAKS, STUDIO CITY, TOLUCA LAKE, VAN NUYS'),
('Region 581', '11', 'S', '581', 'Ca', 'ENCANTO, NATIONAL CITY, PARADISE HILLS, SAN DIEGO, SKYLINE'),
('Region 583', '1', 'B', '583', 'Ca', 'CORONA, ONTARIO, SOUTH ONTARIO, CHINO, EASTVALE'),
('Region 584', '8', 'F', '584', 'Mi', 'BARTON CITY, BLACK RIVER, GLENNIE, GREENBUSH, HARRISVILLE, HUBBARD LK, LINCOLN, MIKADO, OSSINEKE, SPRUCE'),
('Region 587', '6', 'F', '587', 'Il', 'BERKELEY, HILLSIDE'),
('Region 588', '1', 'H', '588', 'Ca', 'DESERT HOT SPRINGS, MORONGO VALLEY'),
('Region 59', '11', 'E', '59', 'Ca', 'CYPRESS, FOUNTAIN VALLEY, GARDEN GROVE, HUNTINGTON BEACH, LOS ALAMITOS, SEAL BEACH, STANTON, WESTMINISTER'),
('Region 595', '3', 'L', '595', 'Ne', 'CATSKILL, GLASCO, KINGSTON, MALDEN, PALENVILLE, SAUGERTIES, WEST CAMP, MOUNT MARION'),
('Region 599', '10', 'Q', '599', 'Ca', 'SAN LUIS OBISPO'),
('Region 6', '1', 'F', '6', 'Ca', 'SAN PEDRO, RANCHO PALOS VERDES, WILMINGTON, LOMITA'),
('Region 60', '1', 'C', '60', 'Ca', 'EL SERENO, ALHAMBRA, MONTEREY PARK'),
('Region 601', '7', 'A', '601', 'Ha', 'CENTRAL MAUI, HANA, WAILUKU, KAHULUI'),
('Region 602', '1', 'U', '602', 'Ca', 'BALDWIN PARK, COVINA, GLENDORA, WEST COVINA'),
('Region 603', '11', 'Z', '603', 'Ca', 'CITY OF COMMERCE, PICO RIVERA, DOWNEY, MONTEBELLO, WHITTIER'),
('Region 605', '5', 'E', '605', 'No', 'CHARLOTTE, CONCORD, HARRISBURG, HUNTERSVILLE'),
('Region 607', '13', 'K', '607', 'Pe', 'HARTLETON, LAURELTON, MIFFLINBURG, MILLMONT, NEW BERLIN, VICKSBURG, LEWISBURG'),
('Region 609', '8', 'B', '609', 'Mi', 'WAYLAND'),
('Region 610', '8', 'D', '610', 'Mi', 'EAST JACKSON, GRASS LAKE, MICHIGAN CENTER, NAPOLEAN, VANDERCOOK LAKE, CHELSEA'),
('Region 611', '3', 'A', '611', 'Ne', 'MANHATTAN, MANHATTAN ISLAND, UPPER WEST SIDE'),
('Region 612', '5', 'G', '612', 'Te', 'MCMINN, MEIGS'),
('Region 613', '8', 'E', '613', 'Mi', 'BANGOR, BENTON HARBOR, COLOMA, DOWAGIAC, EAU CLAIRE, HARTFORD, LAWRENCE, PAW PAW LAKE, SOUTH HAVEN, WATERVLIET'),
('Region 614', '14', 'A', '614', 'Fl', 'CALUSA, CROSSINGS, HAMMOCKS, MIAMI, WEST KENDALL'),
('Region 615', '13', 'K', '615', 'Pe', 'FREEBURG, HUMMELS WHARF, MIDDLEBURG, MT. PLEASANT MILLS, PORT TREVERTON, SELINSGROVE, SHAMOKIN DAM, WINFIELD'),
('Region 62', '2', 'N', '62', 'Ca', 'FOSTER CITY, SAN MATEO'),
('Region 622', '5', 'C', '622', 'Al', 'CEDAR BLUFF, CENTRE, LEESBURG, SAND ROCK'),
('Region 624', '1', 'U', '624', 'Ca', 'WALNUT, LA PUENTE, HACIENDA HEIGHTS, WEST COVINA, DIAMOND BAR'),
('Region 627', '13', 'C', '627', 'Ma', 'BIG POOL, BIG SPRING, CEARFOSS, CHEWSVILLE, CLEAR SPRING, DOWNSVILLE, FAIRVIEW, FALLING WATERS, FUNKSTOWN, GREENCASTLE, HAGERSTOWN, HALFWAY, HUYETT, LEITERSBURG, MARLOWE, MAUGANSVILLE, PARAMOUNT, PEN MAR, PINESBURG, RINGGOLD, ROUZERVILLE, SMITHSBURG, STATE LINE, WAYNESBORO, WILLIAMSPORT'),
('Region 629', '6', 'N', '629', 'Ne', 'ATKINSON, CHAMBERS, EMMETT, EWING, INMAN, LYNCH, O\'NEILL, STUART, CLEARWATER ORCHARD'),
('Region 63', '2', 'N', '63', 'Ca', 'BURLINGAME'),
('Region 630', '11', 'L', '630', 'Ca', 'COTO DE CAZA, DOVE CANYON, LADERA RANCH, LAS FLORES, MISSION VIEJO, RANCHO CIELO, RANCHO SANTA MARGRTA, ROBINSON RANCH, TRABUCO CANYON, TRABUCO HIGHLANDS, WAGON WHEEL, LAKE FOREST, FOOTHILL RANCH'),
('Region 633', '13', 'K', '633', 'Pe', 'MILTON, MILTON W, MONTANDON, MONTARDON, NEW COLUMBIA, POTTSGROVE, WHITE DEER'),
('Region 638', '10', 'D', '638', 'Ca', 'ANTELOPE ACRES, GREEN VALLEY, LAKE ELIZABETH, LAKE HUGHES, LEONA VALLEY, LANCASTER, PALMDALE, QUARTZ HILL'),
('Region 64', '2', 'J', '64', 'Ca', 'CAMPBELL, CUPERTINO, SAN JOSE W, SANTA CLARA, SARATOGA, SUNNYVALE'),
('Region 641', '1', 'N', '641', 'Ca', 'BANNING, BEAUMONT, CABAZON, CALIMESA, CHERRY VALLEY, YUCAIPA'),
('Region 643', '2', 'E', '643', 'Ne', 'BATTLE MOUNTAIN, CRESCENT VALLEY, VALMY, BEOWAWE'),
('Region 644', '14', 'I', '644', 'Fl', 'COOPER CITY, DAVIE, FT LAUDERDALE COUNTY, FT. LAUDERDALE, PEMBROKE, PINES, SOUTHWEST RANCHES, SUNRISE, WESTON'),
('Region 646', '3', 'G', '646', 'Ne', 'FAIRFIELD, MIDDLEVILLE, NEWPORT'),
('Region 647', '3', 'L', '647', 'Pe', 'BETHEL, CALLICOON, CALLICOON CENTER, COCHECTON, DAMASCUS, EQUINUNK, FREMONT CENTER, GALILEE, HANKINS, HORTONVILLE, HURLEYVILLE, JEFFERSONVILLE, KAUNEONGA LAKE, KENOZA LAKE, KIAMESHA LAKE, LAKE HUNTINGTON, LIVINGSTON MANOR, LONG EDDY, MILANVILLE, MOUNTAIN DALE, NORTH BRANCH, OBERNBURG, PARKSVILLE, ROSCOE, SMALLWOOD, STARLIGHT, SWAN LAKE, TYLER HILL, WHITE LAKE, WHITE SULPHUR SPRING, WOODRIDGE, YOUNGSVILLE'),
('Region 649', '5', 'F', '649', 'Al', 'BLOUNT COUNTY, ONEONTA COUNTY'),
('Region 65', '1', 'G', '65', 'Ca', 'ALTA LOMA, ETIWANDA, FONTANA, RANCHO CUCAMONGA, UPLAND'),
('Region 652', '11', 'Z', '652', 'Ca', 'SOUTH GATE'),
('Region 654', '12', 'E', '654', 'Te', 'BANDERA COUNTY, LAKEHILLS, MEDINA, PIPE CREEK, TARPLEY'),
('Region 657', '10', 'A', '657', 'Ca', 'BAKERSFIELD'),
('Region 658', '3', 'G', '658', 'Ne', 'MOHAWK, VAN HORNESVILLE, LITTLE FALLS'),
('Region 659', '13', 'A', '659', 'Pe', 'BEDFORD COUNTY'),
('Region 66', '1', 'G', '66', 'Ca', 'MONTCLAIR, ONTARIO, POMONA, RANCHO CUCAMONGA, UPLAND, CHINO, FONTANA'),
('Region 660', '14', 'J', '660', 'Fl', 'BIG COPIT, BIG PINE, BIG TORCH KEY, CUDJOE KEY, KEY WEST, LITTLE TORCH KEY, LOWER SUGARLOAF KEY, MIDDLE TORCH KEY, RAMROD KEY, SUGARLOAF KEY, SUMMERLAND KEY'),
('Region 661', '1', 'B', '661', 'Ca', 'DIAMOND BAR, PHILLIPS RANCH, POMONA, WESTMONT'),
('Region 663', '13', 'H', '663', 'Pe', 'CATAWISSA, ELYSBURG, NUMIDIA, PAXINOS'),
('Region 664', '3', 'E', '664', 'Ne', 'CENTRAL UTICA'),
('Region 665', '10', 'D', '665', 'Ca', 'APPLE VALLEY, VICTORVILLE, ADELANTO'),
('Region 669', '8', 'E', '669', 'Mi', 'BANGOR, BLOOMINGDALE, COVERT, GLENN, GRAND JUNCTION, LACOTA, LAWRENCE, PULLMAN, SOUTH HAVEN'),
('Region 67', '1', 'B', '67', 'Ca', 'CHINO, FRONTERA, MONCLAIR, ONTARIO, POMONA'),
('Region 670', '13', 'D', '670', 'Pe', 'GARRETT, MEYERSDALE, WELLERSBURG'),
('Region 671', '12', 'C', '671', 'Ne', 'CEDAR CREST, MORIARTY, SAN ANTONITO, TIJERAS, EDGEWOOD, SANDIA PARK, ESTANCIA VALLEY'),
('Region 673', '8', 'C', '673', 'Mi', 'ALGONAC, CHINA E, CHINA TWP, CHINA TWP E, CLAY TWP, FAIR HAVEN, HARSEN ISLAND, MARINE CITY, MARYSVILLE, ST. CLAIR'),
('Region 674', '9', 'M', '674', 'Mo', 'CASCADE COUNTY, GREAT FALLS'),
('Region 675', '8', 'J', '675', 'Mi', 'CALEDONIA, DUTTON'),
('Region 676', '8', 'J', '676', 'Mi', 'GEORGETOWN, GRANDVILLE, HUDSONVILLE, JAMESTOWN, JENISON, ZEELAND'),
('Region 677', '10', 'S', '677', 'Ca', 'ACTON, AQUA DULCE, CANYON COUNTRY, FAIR OAKS RANCH, NEWHALL, SANTA CLARITA'),
('Region 678', '10', 'S', '678', 'Ca', 'CANYON COUNTRY, CASTAIC, NEWHALL, SANTA CLARITA, SAUGUS, STEVENSON RANCH, VAL VERDE, VALENCIA, WEST RANCH'),
('Region 68', '10', 'W', '68', 'Ca', 'CAMARILLO, SANTA ROSA VALLEY, SOMIS'),
('Region 681', '13', 'I', '681', 'Pe', 'ARENDTSVILLE, ASPERS, BIGLERVILLE, GETTYSBURG, UPPER ADAMS COUNTY, YORK SPRINGS'),
('Region 683', '10', 'W', '683', 'Ca', 'CARPINTERIA, LA CONCHITA, MONTECITO, SANTA BARBARA, SUMMERLAND'),
('Region 684', '13', 'Y', '684', 'Pe', 'CLARKSTOWN, HUGHESVILLE, LAIRDSVILLE, MUNCY, PENNSDALE, PICTURE ROCKS, TIVOLI'),
('Region 687', '13', 'Y', '687', 'Pe', 'DUSHORE, EAGLES MERE, FORKSVILLE, HILLSGROVE, LAPORTE, LOPEZ, MILDRED, MUNCY VALLEY, SHUNK, SONESTOWN, NORDMONT'),
('Region 688', '12', 'D', '688', 'Ar', 'APACHE JUNCTION, CHANDLER, GILBERT, MESA, QUEEN CREEK, SAN TAN VALLEY'),
('Region 689', '8', 'F', '689', 'Mi', 'ALGER, GLADWIN, LUPTON, PRESCOTT, ROSE CITY, ST. HELEN, WEST BRANCH'),
('Region 69', '1', 'P', '69', 'Ca', 'BRENTWOOD, MALIBU, PACIFIC PALISADES, TOPANGA, SANTA MONICA, BEL AIR, WEST LOS ANGELES'),
('Region 690', '13', 'Y', '690', 'Pe', 'COGAN STATION, MONTOURSVILLE, TROUT RUN, WILLIAMSPORT, LOYALSOCK'),
('Region 692', '13', 'Y', '692', 'Pe', 'ELIMSPORT, MONTGOMERY'),
('Region 697', '6', 'F', '697', 'Il', 'RIVER FOREST, OAK PARK'),
('Region 698', '2', 'S', '698', 'Or', 'PAULINA, POST, POWELL BUTTE, PRINEVILLE, REDMOND'),
('Region 7', '1', 'D', '7', 'Ca', 'INGLEWOOD, LADERA HEIGHTS, MARINA DEL REY, PLAYA DEL REY, PLAYA VISTA, WESTCHESTER, MAR VISTA'),
('Region 70', '1', 'P', '70', 'Ca', 'CENTURY CITY, CHEVIOT HILLS, MAR VISTA, PALMS, RANCHO PARK, WEST LOS ANGELES, WESTWOOD'),
('Region 702', '6', 'B', '702', 'Wi', 'CABLE, HAYWARD, SEELEY, STONELAKE, WINTER'),
('Region 703', '13', 'A', '703', 'Pe', 'COLVER, CRESSON, EBENSBURG, LORETTO, MUNDYS CORNER, NANTY-GLO, NEW GERMANY, PORTAGE, REVLOC, SOUTH FORK, SUMMERHILL, VINCO, VINTONDALE, WILMORE'),
('Region 705', '14', 'J', '705', 'Fl', 'KEY COLONY BEACH, LAYTON, MARATHON'),
('Region 708', '8', 'C', '708', 'Mi', 'CENTER LINE, FRASER, HAZEL PARK, MADISON HEIGHTS, ROSEVILLE, ROYAL OAK, STERLING HEIGHTS, TROY, WARREN'),
('Region 709', '13', 'I', '709', 'Pe', 'FAIRFIELD, EMMITSBURG, BLUE RIDGE SUMMIT, ORTANNA, TANEYTOWN, HAMILTON TOWNSHIP, GETTYSBURG, CARROLL VALLEY'),
('Region 71', '10', 'V', '71', 'Ca', 'BELL CANYON, CALABASAS, CANOGA PARK, CHATSWORTH, ENCINO, HIDDEN HILLS, RESEDA, TARZANA, WEST HILLS, WINNETKA, WOODLAND HILLS'),
('Region 710', '2', 'E', '710', 'Ne', 'NORTH VALLEY, RENO, RENO'),
('Region 711', '13', 'H', '711', 'Pe', 'BERWICK, NESCOPECK'),
('Region 712', '11', 'S', '712', 'Ca', 'IMPERIAL BEACH, SAN DIEGO-SOUTH, SAN YSIDRO'),
('Region 714', '12', 'E', '714', 'Te', 'FREDERICKSBURG, STONEWALL, HARPER'),
('Region 715', '3', 'B', '715', 'Rh', 'EAST PROVIDENCE, PAWTUCKET, RIVERSIDE, RUMFORD, PROVIDENCE'),
('Region 716', '10', 'Q', '716', 'Ca', 'NIPOMO'),
('Region 717', '6', 'C', '717', 'Il', 'LOCKPORT, CREST HILL, HOMER GLEN, ROMEOVILLE, LEMONT, JOLIET, PLAINFIELD'),
('Region 718', '6', 'H', '718', 'Il', 'SYCAMORE, CORTLAND'),
('Region 721', '8', 'G', '721', 'Mi', 'DAVISON AREA, GOODRICH, LAPEER, BURTON'),
('Region 722', '5', 'E', '722', 'So', 'CAMPOBELLO, CHESNEE, COWPENS, FAIRFOREST, FINGERVILLE, INMAN, MAYO, SPARTANBURG, SPARTANBURG NORTH, STARTEX, WELLFORD, BOILING SPRINGS, LANDRUM, TRYON, CANNONS CAMPGROUND, WOODRUFF, GAFFNEY, LYMAN'),
('Region 723', '5', 'E', '723', 'So', 'SPARTANBURG EAST'),
('Region 724', '13', 'H', '724', 'Pe', 'HUNLOCK CREEK, HUNTINGTON MILLS, N.W. SCHOOL DISTRICT, SHICKSHINNY, SWEET VALLEY'),
('Region 725', '8', 'D', '725', 'Mi', 'ATHENS, BATTLE CREEK, CERESCO, HARPER CREEK, PENNFIELD, SPRINGFIELD'),
('Region 727', '5', 'G', '727', 'Te', 'ROANE COUNTY'),
('Region 728', '13', 'D', '728', 'Pe', 'GREATER JOHNSTOWN'),
('Region 73', '10', 'A', '73', 'Ca', 'BAKERSFIELD, BAKERSFIELD E, BAKERSFIELD NW, BAKERSFIELD S, BAKERSFIELD S W, BAKERSFIELD CENTRAL, BAKERSFIELD GREATER, BAKERSFIELD WEST, BAKERSFIELD NORTH, OILDALE, SHAFTER, KERN COUNTY, TAFT, ARVIN, LAMONT'),
('Region 731', '6', 'B', '731', 'Wi', 'ARBOR VITAE, BOULDER JUNCTION, HARSHAW, HAZELHURST, LAC DU FLAMBEAU, LAKE TOMAHAWK, MANITOWISH WATERS, MINOCQUA, PRESQUE ISLE, WINCHESTER, WOODRUFF, MERCER'),
('Region 732', '3', 'L', '732', 'Ne', 'WOODSTOCK, SHANDAKEN, OLIVE'),
('Region 733', '6', 'C', '733', 'Il', 'ORLAND PARK, PALOS HEIGHTS, PALOS PARK, PALOS HILLS'),
('Region 734', '13', 'D', '734', 'Pe', 'WINDBER'),
('Region 735', '6', 'D', '735', 'Il', 'EVANSTON, NE CHICAGO(ROGERS PA, SKOKIE, WILMETTE'),
('Region 738', '7', 'A', '738', 'Ha', 'HILO, KEAAU, KURTISTOWN, MT. VIEW, PAHOA, PUNA, VOLCANO'),
('Region 739', '1', 'S', '739', 'Ne', 'LAUGHLIN'),
('Region 74', '8', 'E', '74', 'Mi', 'PORTAGE, SCHOOLCRAFT, VICKSBURG'),
('Region 740', '2', 'F', '740', 'Ca', 'SIERRAVILLE, SODA SPRINGS, TRUCKEE'),
('Region 741', '10', 'Q', '741', 'Ca', 'ADELAIDE, CRESTON, HERITAGE RANCH, PASO ROBLES, SAN MIGUEL, SHANDON, TEMPLETON'),
('Region 742', '8', 'D', '742', 'Mi', 'ADDISON, ALLEN, CAMDEN, FRONTIER, HANOVER-HORTON, HILLSDALE, HOMER, JEROME, JONESVILLE, LITCHFIELD, MONTGOMERY, MOSCOW, NORTH ADAMS, QUINCY, READING, SOMERSET, WALDRON'),
('Region 746', '8', 'J', '746', 'Mi', 'CLARKSVILLE, FENWICK, IONIA, LAKE ODESSA, LOWELL, SARANAC, SURROUNDING AREAS, MAPLE VALLEY'),
('Region 747', '3', 'G', '747', 'Ne', 'ST. JOHNSVILLE'),
('Region 75', '11', 'E', '75', 'Ca', 'WHITTIER, SANTA FE SPRINGS, NORWALK'),
('Region 750', '6', 'E', '750', 'Io', 'HARLAN'),
('Region 751', '6', 'F', '751', 'Il', 'CHICAGO SOUTH, HYDE PARK, KENWOOD, SOUTH LOOP, SOUTH SHORE'),
('Region 752', '1', 'S', '752', 'Ar', 'GOLDEN VALLEY, HACKBERRY, KINGMAN, PEACH SPRINGS, VALENTINE, WIEKIEUP, YUCCA'),
('Region 753', '8', 'F', '753', 'Mi', 'ELKTON, PIGEON'),
('Region 757', '12', 'C', '757', 'Co', 'AGUILAR, HOEHNE, PRIMERO, RATON, TRINIDAD, BRANSON, WALSENBURG'),
('Region 759', '10', 'E', '759', 'Ca', 'AGOURA, CALABASAS, MALIBU, MONTE NIDO, SANTA MONICA, TOPANGA, PACIFIC PALISADES'),
('Region 76', '1', 'P', '76', 'Ca', 'BEL AIR, BEVERLY HILLS, BRENTWOOD, LOS ANGELES, WEST HOLLYWOOD, WESTWOOD'),
('Region 763', '13', 'I', '763', 'Pe', 'FANNETT, METAL TOWNSHIPS, SHADE GAP, EAST WATERFORD'),
('Region 766', '8', 'G', '766', 'Mi', 'AINSWORTH, CARMAN, FLINT, FLUSHING, SWARTZ CREEK, GRAND BLANC, BURTON'),
('Region 767', '8', 'J', '767', 'Mi', 'KENTWOOD, GAINES TOWNSHIP, CALEDONIA, WYOMING, GRAND RAPIDS, BYRON CENTER, GRANDVILLE, BAILEYS GROVE'),
('Region 769', '7', 'E', '769', 'Ha', 'EWA BEACH, VILLAGE PARK, WAIKELE'),
('Region 77', '10', 'Q', '77', 'Ca', 'LOMPOC'),
('Region 771', '8', 'G', '771', 'Mi', 'FRANKENMUTH'),
('Region 773', '5', 'F', '773', 'Al', 'HARTSELLE'),
('Region 775', '13', 'A', '775', 'Pe', 'CLAYSBURG, DUNCANSVILLE, EAST FREEDOM, HOLLIDAYSBURG, LAKEMONT, MARTINSBURG, NEWRY, ROARING SPRING, WILLIAMSBURG'),
('Region 778', '5', 'F', '778', 'Al', 'ARAB, BAILEYTON, BLOUNTSVILLE, GRASSY, GUNTERSVILLE, JOPPA, LACEY\'S SPRG, MORGAN CITY, UNION GRV'),
('Region 779', '1', 'B', '779', 'Ca', 'CHINO, CHINO HILLS, DIAMOND BAR, ONTARIO, PHILLIPS RANCH, POMONA'),
('Region 78', '1', 'P', '78', 'Ca', 'EAST HOLLYWOOD, HANCOCK PARK, KOREATOWN, MID-CITY, PICO UNION, WEST ADAMS, WEST HOLLYWOOD, LOS FELIZ, WILSHIRE, SILVER LAKE, HOLLYWOOD, FAIRFAX, PARK LA BREA'),
('Region 780', '11', 'R', '780', 'Ca', 'CLAIREMONT, KEARNY MESA, LINDA VISTA, MISSION VALLEY, SERRA MESA, LA JOLLA, UNIVERSITY CITY'),
('Region 781', '13', 'I', '781', 'Pe', 'SHIPPENSBURG AREA'),
('Region 782', '3', 'E', '782', 'Ne', 'ORISKANY, WESTMORELAND'),
('Region 783', '6', 'F', '783', 'Il', 'BLUE ISLAND'),
('Region 785', '11', 'R', '785', 'Ca', 'GOLDEN HILL, HILLCREST, NORMAL HEIGHTS, NORTH PARK, SOUTH PARK'),
('Region 789', '10', 'D', '789', 'Ca', 'AERIAL ACRES, BORON, CALIFORNIA CITY, CANTIL, FREMONT VALLEY, MOJAVE, NORTH EDWARDS'),
('Region 79', '3', 'B', '79', 'Co', 'MONROE'),
('Region 790', '3', 'T', '790', 'Ne', 'HARRINGTON PARK'),
('Region 792', '3', 'E', '792', 'Ne', 'SAUQUOIT'),
('Region 794', '6', 'D', '794', 'Il', 'MORTON GROVE, NILES, SKOKIE, DES PLAINES, GLENVIEW, EDISON PARK, EDGEBROOK, SAUGANASH, CHICAGO NORTHWEST, GOLF, LINCOLNWOOD, NORTHBROOK, PARK RIDGE, NORRIDGE, GOLFMAINE'),
('Region 795', '10', 'V', '795', 'Ca', 'CANOGA PARK, CHATSWORTH, NORTHRIDGE, PORTER RANCH, WEST HILLS'),
('Region 796', '5', 'B', '796', 'Te', 'ANDERSONVILLE, CLINTON, FAIRVIEW, JACKSBORO, LAFOLLETTE, NORRIS, ROCKY TOP'),
('Region 797', '1', 'N', '797', 'Ca', 'HOMELAND, NUEVO, PERRIS, ROMOLAND'),
('Region 8', '10', 'V', '8', 'Ca', 'ARLETA, CHATSWORTH, GRANADA HILLS, MISSION HILLS, NORTH HILLS, NORTHRIDGE, PACOIMA, RESEDA, SAN FERNANDO, SEPULVEDA, SYLMAR, VAN NUYS, SUNLAND, PANORAMA CITY, LAKE VIEW TERRACE, SUN VALLEY'),
('Region 80', '1', 'H', '80', 'Ca', 'PALM SPRINGS, RANCHO MIRAGE, THOUSAND PALMS'),
('Region 800', '12', 'C', '800', 'Ne', 'GRANTS, LAGUNA, MILAN, SAN RAFAEL, PUEBLO OF ACOMA, BACA'),
('Region 8002', '80', 'Z', '8002', 'No', 'CHARLOTTE'),
('Region 801', '13', 'I', '801', 'Pe', 'EAST BERLIN, YORK SPRINGS'),
('Region 803', '6', 'B', '803', 'Wi', 'EAU CLAIRE, ALTOONA, CHIPPEWA FALLS, TOWN OF WASHINGTON, CLEGHORN, ELEVA, FALL CREEK, ELK MOUND, OSSEO, CADOTT, COLFAX'),
('Region 805', '14', 'J', '805', 'Fl', 'FLORIDA CITY, HOMESTEAD, LEISURE CITY, MIAMI, NARANJA, PRINCETON, REDLANDS, CUTLER BAY'),
('Region 808', '1', 'S', '808', 'Ne', 'PAHRUMP'),
('Region 809', '12', 'A', '809', 'Ar', 'DOUGLAS, ELFRIDA, MCNEAL, PIRTLEVILLE'),
('Region 813', '1', 'H', '813', 'Ca', 'INDIO, COACHELLA, MECCA, LA QUINTA'),
('Region 814', '8', 'G', '814', 'Mi', 'BANCROFT, BYRON, CORUNNA, DURAND, GAINES, LENNON, MORRICE, PERRY, SWARTZ CREEK, VERNON, OWOSSO'),
('Region 815', '8', 'J', '815', 'Mi', 'BYRON CENTER, CALEDONIA, CUTLERVILLE, DORR, GAINES, JAMESTOWN, WAYLAND, WYOMING'),
('Region 816', '13', 'C', '816', 'Vi', 'AUGUSTA COUNTY, STAUNTON, WAYNESBORO'),
('Region 817', '8', 'G', '817', 'Mi', 'BENTLEY, GENESEE, KEARSLEY, MOUNT MORRIS'),
('Region 818', '7', 'A', '818', 'Ha', 'UPCOUNTRY MAUI'),
('Region 82', '10', 'W', '82', 'Ca', 'SANTA PAULA'),
('Region 820', '1', 'R', '820', 'Ca', 'CANYON LAKE, HOMELAND-PARTS OF, LAKE ELSINORE-PARTS , MENIFEE, QUAIL VALLEY, ROMOLAND-PARTS OF, SUN CITY, WILDOMAR-PARTS OF, WINCHESTER'),
('Region 821', '8', 'G', '821', 'Mi', 'MONTROSE, NEW LOTHROP'),
('Region 823', '8', 'G', '823', 'Mi', 'DAVISBURG, HOLLY, WHITE LAKE, FENTON, LAKE FENTON, GRAND BLANC, SPRINGFIELD TOWNSHIP, ROSE TOWNSHIP, GAINS, HIGHLAND, CLARKSTON, GROVELAND, GOODRICH'),
('Region 825', '8', 'F', '825', 'Mi', 'BAY CITY, BENTLEY, KAWKAWLIN, LINWOOD, PINCONNING, RHODES, STANDISH'),
('Region 826', '8', 'J', '826', 'Mi', 'GRANDVILLE, WALKER, WYOMING'),
('Region 827', '10', 'D', '827', 'Ca', 'MOJAVE, ROSAMOND'),
('Region 829', '3', 'G', '829', 'Ne', 'CHERRY VALLEY, JORDANVILLE, OWEN D. YOUNG C.S., PAINES HOLLOW, RICHFIELD SPRINGS, SPRINGFIELD, STARKVILLE, VANHORNESVILLE, WARREN'),
('Region 83', '10', 'Q', '83', 'Ca', 'ARROYO GRANDE, AVILA BEACH, FIVE CITIES, GROVER BEACH, GUADALUPE, HALCYON, NIPOMO, OCEANO, ORCUTT, PISMO BEACH, SAN LUIS OBISPO, SANTA MARIA, SHELL BEACH, SISQUOC'),
('Region 834', '3', 'B', '834', 'Co', 'EASTON'),
('Region 835', '9', 'R', '835', 'Wy', 'EVANSTON'),
('Region 837', '12', 'A', '837', 'Ar', 'AMADO, ARIVACA, CONTINENTAL, GREEN VALLEY, MCGEE RANCH, RANCHO SAHUARITA, SAHUARITA, SAHUARITA HEIGHTS, TUBAC, TUCSON, VAIL'),
('Region 838', '14', 'A', '838', 'Fl', 'CORAL GABLES, DORAL, DORAL BRANCH, KENDALL, KENDALL LAKES, MIAMI, MIAMI-SOUTH, MIAMI-WEST, OLYMPIA HEIGHTS, PINECRES, SWEETWATER, WESTCHESTER, WESTWOOD LAKE'),
('Region 839', '11', 'V', '839', 'Ca', 'JULIAN, SANTA YSABEL, SHELTER VALLEY, SPENCER VALLEY, WARNER SPRINGS, WYNOLA'),
('Region 84', '11', 'L', '84', 'Ca', 'MISSION VIEJO'),
('Region 840', '3', 'E', '840', 'Ne', 'CANASTOTA, DURHAMVILLE, MUNNSVILLE, ONEIDA, ONEIDA CASTLE, SHERRILL, STOCKBRIDGE, VERNON, VERNON CENTER, VERONA'),
('Region 842', '10', 'A', '842', 'Ca', 'TAFT'),
('Region 843', '2', 'F', '843', 'Ne', 'SMITH VALLEY, WELLINGTON'),
('Region 846', '11', 'Z', '846', 'Ca', 'CARSON, WILMINGTON'),
('Region 85', '11', 'L', '85', 'Ca', 'EL TORO, FOOTHILL RANCH, LAGUNA HILLS, LAKE FOREST, PORTOLA HILLS, TRABUCO CANYON, SILVERADO CANYON, MODJESKA CANYON, ROBINSON RANCH'),
('Region 854', '13', 'I', '854', 'Pe', 'CHAMBERSBURG, FAYETTEVILLE, MARION, SCOTLAND'),
('Region 855', '12', 'A', '855', 'Ar', 'MAMMOTH, ORACLE, SAN MANUEL'),
('Region 859', '13', 'I', '859', 'Pe', 'GREENCASTLE, MARION, SHADY GROVE, STATE LINE'),
('Region 86', '11', 'L', '86', 'Ca', 'LAGUNA BEACH'),
('Region 860', '13', 'I', '860', 'Pe', 'MERCERSBURG, ST THOMAS, FORT LOUDON'),
('Region 861', '13', 'D', '861', 'Pe', 'ACCIDENT, BITTINGER, FINZEL, FRIENDSVILLE, FROSTBURG, GRANTSVILLE, JENNINGS, KITZMILLER, MCHENRY, OAKLAND, SALISBURY, SPRINGS, SWANTON'),
('Region 862', '8', 'D', '862', 'Mi', 'BATH, ELSIE, LAINGSBURG, OVID, ST. JOHNS, PORTLAND, DEWITT'),
('Region 863', '6', 'H', '863', 'Il', 'ASHTON, CHANA, CRESTON, DAVIS JUNCTION, ESMOND, KINGS, LINDENWOOD, MONROE CENTER, ROCHELLE, STEWARD'),
('Region 864', '14', 'J', '864', 'Fl', 'ISLAMORADA, KEY LARGO, LAYTON, OCEAN REEF, PLANTATION KEY, TAVERNIER'),
('Region 867', '10', 'D', '867', 'Ca', 'LANCASTER, PALMDALE, LAKE LOS ANGELES'),
('Region 87', '11', 'L', '87', 'Ca', 'CAPISTRANO BEACH, DANA POINT, SAN JUAN CAPISTRANO, MONARCH BEACH'),
('Region 870', '2', 'S', '870', 'Or', 'ALBANY, BROWNSVILLE, HALSEY, JEFFERSON, LEBANON, SCIO, SHEDD, SWEETHOME, TANGENT, CORVALLIS'),
('Region 872', '8', 'A', '872', 'Mi', 'ASHTON, BRISTOL, CHASE, DIGHTON, EVART, HERSEY, LEROY, LUTHER, REED CITY, TUSTIN, MARION'),
('Region 873', '9', 'B', '873', 'Ut', 'MILFORD, KANARRAVILLE, CEDAR CITY, PAROWAN, ENOCH, NEW HARMONY'),
('Region 875', '6', 'N', '875', 'Ne', 'GERING, SCOTTSBLUFF'),
('Region 878', '10', 'D', '878', 'Ca', 'APPLE VALLEY, BALDY MESA, HESPERIA, OAK HILLS, SUMMIT VALLEY, CAJON PASS, OAK HILLS ESTATE, SUMMIT ESTATES'),
('Region 879', '13', 'A', '879', 'Pe', 'GLENDALE'),
('Region 88', '1', 'C', '88', 'Ca', 'ATWATER, EAGLE ROCK, GLENDALE, LA CANADA, LA CRESCENTA, MONTROSE, SHADOW HILLS, SUNLAND, TUJUNGA'),
('Region 880', '8', 'C', '880', 'Mi', 'BRUCE TOWNSHIP, LEONARD, OAKLAND TOWNSHIP, RAY TOWNSHIP, ROCHESTER, ROMEO, SHELBY TOWNSHIP, WASHINGTON, OXFORD'),
('Region 884', '2', 'D', '884', 'Ca', 'ANTELOPE, CARMICHAEL, CITRUS HEIGHTS, ELVERTA, NORTH HIGHLANDS, RIO LINDA, ROSEVILLE, SACRAMENTO'),
('Region 887', '2', 'S', '887', 'Or', 'ECHO, HEPPNER, HERMISTON, IRRIGON, PLYMOUTH, STANFIELD, UMATILLA'),
('Region 889', '11', 'L', '889', 'Ca', 'ALISO VIEJO, LAGUNA HILLS, LAGUNA NIGUEL, SOUTH LAGUNA BEACH'),
('Region 89', '11', 'V', '89', 'Ca', 'ALLIED GARDENS, CASA DE ORO, FLETCHER HILLS, LA MESA, SAN CARLOS'),
('Region 890', '5', 'D', '890', 'Te', 'MARSHALL COUNTY'),
('Region 891', '6', 'D', '891', 'Il', 'FT. SHERIDAN, HIGHLAND PARK, HIGHWOOD'),
('Region 892', '7', 'A', '892', 'Ha', 'HONOKOWAI, KAHANA, KAPALUA, LAHAINA, MAUI WEST, NAPILI, WEST MAUI'),
('Region 894', '5', 'C', '894', 'Al', 'HARVEST, MONROVIA, TONEY, NORTH OF HWY 72, EAST OF COUNTY LINE, MADISON, HUNTSVILLE, WEST OF RESEARCH PK'),
('Region 895', '7', 'A', '895', 'Ha', 'KIHEI, WAILEA'),
('Region 9', '10', 'E', '9', 'Ca', 'THOUSAND OAKS'),
('Region 90', '13', 'Y', '90', 'Pe', 'MUNCY, PENNSDALE'),
('Region 901', '14', 'J', '901', 'Fl', 'BIG COPPITT KEY, BIG PINE KEY, BIG TORCH KEY, CUDJOE KEY, KEY WEST, LITTLE TORCH KEY, MIDDLE TORCH KEY, NO NAME KEY, RAMROD KEY, STOCK ISLAND, SUGARLOAF KEY, SUGARLOAF SHORES, SUMMERLAND KEY'),
('Region 902', '8', 'A', '902', 'Mi', 'CEDAR SPRINGS, ROCKFORD, SAND LAKE, SPARTA'),
('Region 908', '1', 'C', '908', 'Ca', 'BALDWIN PARK, CITY OF INDUSTRY, EL MONTE, LA PUENTE, SOUTH EL MONTE'),
('Region 909', '13', 'I', '909', 'Pe', 'FORBES RD SCH DIST.'),
('Region 91', '10', 'D', '91', 'Ca', 'LANCASTER'),
('Region 913', '6', 'B', '913', 'Wi', 'ALMA CENTER, BLACK RIVER FALLS, BLAIR, HIXTON, MELROSE, MERRILLAN, MINDORO, TAYLOR'),
('Region 914', '5', 'C', '914', 'Al', 'LIMESTONE CNTY EAST'),
('Region 918', '3', 'E', '918', 'Ne', 'CAMDEN, MCCONNELLSVILLE, NORTHBAY, SYLVAN BEACH, TABERG, BLOSSVALE, WESTDALE, CLEVELAND'),
('Region 919', '8', 'C', '919', 'Mi', 'HARRISTOWN TOWNSHIP, ST CLAIR SHORES, EASTPOINTE, CLINTON TOWNSHIP, MOUNT CLEMENS'),
('Region 92', '1', 'D', '92', 'Ca', 'EL SEGUNDO'),
('Region 922', '12', 'A', '922', 'Ar', 'ORO VALLEY, TUCSON, CATALINA'),
('Region 923', '9', 'I', '923', 'Id', 'SALMON'),
('Region 928', '12', 'C', '928', 'Ne', 'LOS LUNAS, LOS CHAVEZ, LAS MARAVILLAS, BOSQUE, JARALES, VEGUITA, LAS NUTRIAS, MOUNTAINAIR, TIERRA GRANDE, MANZANO, BELEN, TOME, EL CERRO, RIO COMMUNITIES, MEADOW LAKE'),
('Region 929', '13', 'K', '929', 'Pe', 'DALMATIA, HERNDON, LECKKILL, LINE MTN AREA, SHAMOKIN, TREVORTON, WEST CAMERON'),
('Region 93', '2', 'S', '93', 'Or', 'COTTAGE GROVE, CRESWELL, DEXTER, GOSHEN, LEABURG, LOWELL, MARCOLA, MCKENZIE BRIDGE, OAKRIDGE, PLEASANT HILL, SPRINGFIELD, THURSTON, VIDA, WALTERVILLE, WEST FIR, JASPER, FOOTBALL PLAYERS'),
('Region 930', '11', 'V', '930', 'Ca', 'BOULEVARD, CAMPO, DESCANSO, JACUMBA, PINE VALLEY, POTRERO'),
('Region 933', '8', 'E', '933', 'Mi', 'KALAMAZOO, LAWTON, SCHOOLCRAFT, PORTAGE, MATTAWAN, PAW PAW, OTHER'),
('Region 94', '11', 'E', '94', 'Ca', 'BREA-WEST, FULLERTON, LA HABRA'),
('Region 940', '7', 'A', '940', 'Ha', 'ELEELE-LIHUE-WAIMEA, HANAMAULU-HANAPEPE, KALAHEO-LAWAI, KEKAHA-MAKAWELI, KOLOA-KAUMAKANI, PUHI'),
('Region 941', '7', 'A', '941', 'Ha', 'HANALEI, KILAUEA, WAILUA, KAPAHI'),
('Region 942', '8', 'F', '942', 'Mi', 'AUGRES, TAWAS'),
('Region 944', '6', 'N', '944', 'Ne', 'HERSHEY, PAXTON, SUTHERLAND, WALLACE, NORTH PLATTE, DICKENS'),
('Region 95', '3', 'T', '95', 'Ne', 'SOMERS'),
('Region 955', '8', 'C', '955', 'Mi', 'EMMETT, MEMPHIS, SMITH CREEK, YALE, ARMADA'),
('Region 956', '10', 'O', '956', 'Ca', 'MARIPOSA COUNTY'),
('Region 957', '8', 'F', '957', 'Mi', 'AU SABLE, GREENBUSH, MIKADO, OSCODA'),
('Region 96', '11', 'Q', '96', 'Ca', 'IRVINE, NORTH TUSTIN, ORANGE, SANTA ANA, TUSTIN, TUSTIN RANCH'),
('Region 965', '3', 'L', '965', 'Ne', 'ACCORD, COTTEKILL, HIGH FLS, KERHONKSON, KINGSTON, MARBLETOWN, RONDOUT VLY, ROSENDALE, STONE RDG, TILLSON'),
('Region 968', '13', 'B', '968', 'Pe', 'LAMAR, MACKEYVILLE, MILL HALL, ROTE, SALONA, LOCK HAVEN, BEECH CREEK'),
('Region 969', '12', 'G', '969', 'Ok', 'CHOCTAW, DEL CITY, FOREST PARK, HARRAH, MIDWEST CITY, MOORE, OKLAHOMA CITY, SPENCER, TINKER AFB, NEWALLA'),
('Region 9695', '90', 'C', '9695', 'Ca', 'NSTC STAFF ONLY'),
('Region 9696', '90', 'A', '9696', 'Ca', 'CAMP STAFF ONLY'),
('Region 9697', '99', 'C', '9697', 'Ca', 'TORRANCE'),
('Region 97', '11', 'Q', '97', 'Ca', 'BALBOA PENINSULA, COSTA MESA - EAST, LIDO ISLAND, NEWPORT BEACH, NEWPORT BEACH (WEST)'),
('Region 971', '13', 'H', '971', 'Pe', 'ATLAS, DOOLEYVILLE, KULPMONT, LOCUST GAP, MARION HGTS, MT CARMEL, STRONG, WILBURTON'),
('Region 975', '13', 'B', '975', 'Pe', 'AARONSBURG, COBURN, COLYER, GREGG TOWNSHIP, HAINES TOWNSHIP, LIVONIA, MADISONBURG, PENN TOWNSHIP, POTTER TOWNSHIP, WOODWARD, CENTRE HALL, MILLHEIM, PENNS VALLEY, REBERSBURG, SPRING MILLS'),
('Region 976', '13', 'B', '976', 'Pe', 'LOGANTON, SUGAR VALLEY, TYLERSVILLE'),
('Region 979', '13', 'B', '979', 'Pe', 'BEECH CREEK, BLANCHARD, HOWARD, LIBERTY TWP, MARSH CREEK, MILL HALL, ORVISTON, ROMOLA'),
('Region 98', '1', 'C', '98', 'Ca', 'TEMPLE CITY'),
('Region 980', '6', 'A', '980', 'Il', 'BEACH PARK, GURNEE, KENOSHA, PLEASANT PRAIRIE, RUSSELL, WADSWORTH, WAUKEGAN, WINTHROP HARBOR, ZION'),
('Region 981', '9', 'B', '981', 'Ut', 'FILLMORE'),
('Region 985', '7', 'O', '985', 'Ha', 'MOLOKAI'),
('Region 989', '5', 'D', '989', 'Te', 'FULTON -SOUTH, MARTIN, OBION, TROY, UNION CITY, WOODLAND MILLS, KENTON, HORNBEAK'),
('Region 991', '5', 'C', '991', 'Te', 'ALTO, MONTEAGLE, SEWANEE, ST. ANDREWS, TRACY CITY'),
('Region 994', '6', 'U', '994', 'Ka', 'BREWSTER, COLBY, GRINNELL, MONUMENT, OAKLEY/GEM, REXFORD, WINONA, ATWOOD'),
('Region 995', '2', 'F', '995', 'Ne', 'CRYSTAL BAY, INCLINE VILLAGE'),
('Region 996', '13', 'D', '996', 'Pe', 'ADAMS TOWNSHIP, BEAVERDALE, CROYLE TOWNSHIP, DUNLO, ELTON, JOHNSTOWN, MINERAL POINT, PORTAGE, PORTAGE TOWNSHIP, RICHLAND TOWNSHIP, SALIX, SIDMAN, SOUTH FORK, ST. MICHAEL, SUMMERHILL, SUMMERHILL TOWNSHIP, WILMORE, WINDBER'),
('Region 998', '13', 'I', '998', 'Pe', 'MCCONNELLSBURG'),
('RegionString', 'Section', 'Area', 'Region', 'St', 'COMMUNITIES');

-- --------------------------------------------------------

--
-- Table structure for table `rs_session`
--

DROP TABLE IF EXISTS `rs_session`;
CREATE TABLE `rs_session` (
  `id_token` varchar(16) NOT NULL,
  `event_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `target_id` int(11) DEFAULT NULL,
  `token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `rs_session`
--

TRUNCATE TABLE `rs_session`;
--
-- Dumping data for table `rs_session`
--

INSERT INTO `rs_session` (`id_token`, `event_id`, `user_id`, `target_id`, `token_expires`) VALUES
('7f2ef12c06e73a7a', NULL, NULL, NULL, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `rs_users`
--

DROP TABLE IF EXISTS `rs_users`;
CREATE TABLE `rs_users` (
  `id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `for_events` varchar(1024) DEFAULT NULL,
  `hash` varchar(255) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Truncate table before insert `rs_users`
--

TRUNCATE TABLE `rs_users`;
--
-- Dumping data for table `rs_users`
--

INSERT INTO `rs_users` (`id`, `name`, `enabled`, `for_events`, `hash`, `admin`) VALUES
(1, 'Area 1B', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$NeD02z/uz8u8JtyJR/58jeFVWCiU94wytH5E76J5y8NPpZMZvimz2', 0),
(2, 'Area 1C', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$UzypGyBnLT4uzuu/k0Uz.uTwF08Kujp25Xeb24sQzKskFUwCtpH0O', 0),
(3, 'Area 1D', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$fQUU95kCwpkpXIh000JzzumMm2E4nWlp0yNbLU11iSdcufkXg0ChW', 0),
(4, 'Area 1F', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$IJWFZbu3I1l5Kt2FK7mElO2qWFnG4QZfHS5PfWvaw1y/rS59SSOyK', 0),
(5, 'Area 1G', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$kzZfLJ1Ls/ey1VFa62oF5u2SZihG.WnvPUcxUQKIGyf/Gfqwd5awu', 0),
(6, 'Area 1H', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$KzL4b5PRwkulH16spHhOZu2FvXwTQSA7KDHGF.C3xhL5ZBV6WOyGO', 0),
(7, 'Area 1N', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$aSaw9HfijsbzKaAGzJrsv.2f6K5LyFpkD9j9EhzYH2aLzbKQVzqVy', 0),
(8, 'Area 1P', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$Je3X6j5s1lVG6RYMjF1QYOXsP.BApCv98segZNl4UuiB0dHVQk3cq', 0),
(9, 'Area 1R', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$f6kXD2d16RnbT2VYnAeHj..BYGXD0aN7KnCGrH6hq0uBlASJF4cWm', 0),
(10, 'Area 1S', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$.zmtRAh1yycyTv4a4RTHHOoEwXb516Z3owUOyHmxZIQZxM1KtnVY.', 0),
(11, 'Area 1U', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$9pzmAR4RwkOO0Xsc2/SyHuwiY60JDcI5V74pRJZfokSXhu6tlmgJ.', 0),
(12, 'Section 1', 1, '', '$2y$10$Xh9lVQZm4Efa9b4ku1gpWOFdld.o934fkFxozm/Yjn/9dhh1OsjNq', 0),
(13, 'Section 2', 1, '', '$2y$10$lQoq3elPgWWmV9NW4MZIduv53oiRF366lZb2icbqgh53KQaHkXdWS', 0),
(14, 'Section 10', 1, '', '$2y$10$bcIPQntM.Hhn7NKEAkQ2pe2ZX97JQX118xMzCoReVAlasiIvm7Or2', 0),
(15, 'Section 11', 1, '', '$2y$10$FhJAsQoJMkhYGObrTwYa1.Yq30wHrMrKJWzsfq2MvdY6RKsWPJSTW', 0),
(16, 'Section 1 Admin', 1, 'a:1:{i:0;s:15:\"2016U16U19Chino\";}', '$2y$10$T.J164vr5jvwbC0hSCzr2.iOZNEqsMC0OQqBQ4R5ju0KX1415KRQi', 1),
(20, 'Admin', 0, NULL, '$2y$10$rkMqCQHA1V9hM1aKmiOttewOc7Sk6nBWmWnir5oXJtlP.KIDXNYFu', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rs_events`
--
ALTER TABLE `rs_events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `rs_games`
--
ALTER TABLE `rs_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `rs_limits`
--
ALTER TABLE `rs_limits`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- Indexes for table `rs_log`
--
ALTER TABLE `rs_log`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rs_messages`
--
ALTER TABLE `rs_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rs_sar`
--
ALTER TABLE `rs_sar`
  ADD PRIMARY KEY (`portalName`),
  ADD UNIQUE KEY `regionStr` (`portalName`);

--
-- Indexes for table `rs_session`
--
ALTER TABLE `rs_session`
  ADD PRIMARY KEY (`id_token`);

--
-- Indexes for table `rs_users`
--
ALTER TABLE `rs_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rs_events`
--
ALTER TABLE `rs_events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `rs_games`
--
ALTER TABLE `rs_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=641;

--
-- AUTO_INCREMENT for table `rs_limits`
--
ALTER TABLE `rs_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `rs_log`
--
ALTER TABLE `rs_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1914;

--
-- AUTO_INCREMENT for table `rs_messages`
--
ALTER TABLE `rs_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `rs_users`
--
ALTER TABLE `rs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
