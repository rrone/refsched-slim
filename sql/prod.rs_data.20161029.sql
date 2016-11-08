-- phpMyAdmin SQL Dump
-- version 4.6.3
-- https://www.phpmyadmin.net/
--
-- Host: pod-100136.wpengine.com
-- Generation Time: Oct 29, 2016 at 04:33 PM
-- Server version: 5.6.32-78.1-log
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

-- --------------------------------------------------------

--
-- Table structure for table `rs_ajax_example`
--

DROP TABLE IF EXISTS `rs_ajax_example`;
CREATE TABLE `rs_ajax_example` (
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `age` int(11) NOT NULL,
  `gender` varchar(1) COLLATE utf8_unicode_ci NOT NULL,
  `wpm` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `rs_ajax_example`
--

INSERT INTO `rs_ajax_example` (`name`, `age`, `gender`, `wpm`) VALUES
('Frank', 45, 'm', 87),
('Jerry', 120, 'm', 20),
('Jill', 22, 'f', 72),
('Julie', 35, 'f', 90),
('Regis', 75, 'm', 44),
('Tracy', 27, 'f', 0);

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
  `locked` tinyint(1) DEFAULT '1',
  `view` tinyint(1) DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  `label` varchar(255) DEFAULT NULL,
  `num_refs` int(11) DEFAULT '3',
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rs_events`
--

INSERT INTO `rs_events` (`id`, `projectKey`, `name`, `dates`, `location`, `locked`, `view`, `enabled`, `label`, `num_refs`, `start_date`, `end_date`) VALUES
(1, '2015U16U19Chino', 'Section Upper Division Playoffs, U16 and U19', 'November 21-22, 2015', 'Ayala Park, Chino', 1, 0, 0, 'Nov 21 and 22, 2015:Section Upper Division Playoffs, U16 and U19', 3, '2015-11-21', '2015-11-22'),
(2, '2016AllStarExtraPlayoffs', 'Section All Star and Extra Playoffs', 'February 20-21, 2016', 'Ab Brown Soccer Complex, Riverside', 1, 0, 0, 'Feb 20 and 21, 2016:Section All Star and Extra Playoffs', 3, '2016-02-20', '2016-02-21'),
(3, '2016U16U19Chino', 'U16/U19 Playoffs', 'November 19-20, 2016', 'Ayala Park, Chino', 1, 1, 1, 'November 19-20, 2016:U16/U19 Playoffs', 3, '2016-11-19', '2016-11-20'),
(4, '2016WSC', 'Western States Championships', 'March 19-20, 2016', 'Bullhead City, AZ', 1, 0, 0, 'Mar 19 and 20, 2016:Western States Championships', 4, '2016-03-19', '2016-03-20'),
(5, '2017AllStarPlayoffs', 'U10-U14 All-Star Playoffs', 'March 11-12, 2017', 'Ab Brown Soccer Complex, Riverside', 0, 1, 0, 'March 11-12, 2017:U10-U14 All-Star Playoffs', 3, '2017-03-11', '2017-03-12'),
(6, '2017LeaguePlayoffs', 'U10-U14 League Playoffs', 'February 25-26, 2017', 'Ab Brown Soccer Complex, Riverside', 0, 1, 0, 'February 25-26, 2017:U10-U14 League Playoffs', 3, '2017-02-25', '2017-02-25'),
(7, '2017ExtraPlayoffs', 'U09-U14 Extra Playoffs', 'January 28-29, 2017', 'Columbia Park, Torrance', 0, 1, 0, 'January 28-29, 2017:U09-U14 Extra Playoffs', 3, '2017-01-28', '2017-01-29'),
(8, '2017WSC', 'Western States Championships', 'March 25-26, 2017', 'Carson City, NV', 0, 1, 0, 'March 25-26, 2017:Western States Championships', 4, '2017-03-25', '2017-03-26'),
(9, '2016LeaguePlayoffs', 'Section League Playoffs', 'February 27-28, 2016', 'Ab Brown Soccer Complex, Riverside', 1, 0, 0, 'Feb 27 and 28, 2016:Section League Playoffs', 3, '2016-02-27', '2016-02-28');

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
  `medalRound` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rs_games`
--

INSERT INTO `rs_games` (`id`, `projectKey`, `game_number`, `date`, `field`, `time`, `division`, `pool`, `home`, `home_team`, `away`, `away_team`, `assignor`, `cr`, `ar1`, `ar2`, `r4th`, `medalRound`) VALUES
(1, '2016AllStarExtraPlayoffs', 201, '2016-02-20', 'White 1', '08:00:00', 'U14G', NULL, 'U1', '', 'B1', '', 'Area 1B', 'P.AGRAWAL', 'J.PARGMAN', 'S.DYKSTRA', '', 0),
(2, '2016AllStarExtraPlayoffs', 202, '2016-02-20', 'White 2', '08:00:00', 'U14G', NULL, 'P2', '', 'S1', '', 'Area 1C', 'Bruce Hancock', 'Peter Smock', 'Craig Dunkin', '', 0),
(3, '2016AllStarExtraPlayoffs', 203, '2016-02-20', 'White 3', '08:00:00', 'U14G', NULL, 'C1', '', 'P1', '', 'Area 1D', 'Sandee Wilson', 'Ramon Pulido', 'Mike Torrey', '', 0),
(4, '2016AllStarExtraPlayoffs', 204, '2016-02-20', 'White 4', '08:00:00', 'U14G', NULL, 'D1', '', 'U2', '', 'Area 1P', 'ASCENZI', 'KLEIMAN', '', '', 0),
(5, '2016AllStarExtraPlayoffs', 205, '2016-02-20', 'Blue 2', '08:00:00', 'U12G', NULL, 'S1', '', 'D2', '', 'Area 1B', 'Renteria', 'Machado', 'Machado', '', 0),
(6, '2016AllStarExtraPlayoffs', 206, '2016-02-20', 'Blue 3', '08:00:00', 'U12G', NULL, 'U1', '', 'B1', '', 'Area 1S', 'T.SMITH', 'R.JACKOWIAK', 'Z.HARRIS', '', 0),
(7, '2016AllStarExtraPlayoffs', 207, '2016-02-20', 'Blue 4', '08:00:00', 'U12G', NULL, 'D1', '', 'P1', '', 'Area 1U', 'Mars Ramage', 'Nick Ruffle', 'Alfred Zambrano', '', 0),
(8, '2016AllStarExtraPlayoffs', 208, '2016-02-20', 'Blue 5', '08:00:00', 'U12G', NULL, 'C1', '', 'B2', '', 'Area 1D', 'Michael Grosvenor', 'Mark Parrot', 'Manny Hernandez', '', 0),
(9, '2016AllStarExtraPlayoffs', 209, '2016-02-20', 'Green 3', '08:00:00', 'U10G', NULL, 'B1', '', 'U2', '', 'Area 1C', 'Brian Bonham', 'Larry Abelson', 'Don Gibbs', '', 0),
(10, '2016AllStarExtraPlayoffs', 210, '2016-02-20', 'Yellow 1', '08:00:00', 'U10G', NULL, 'C1', '', 'S1', '', 'Area 1B', 'Nieto', 'Rodrigues', 'Rodriguez', '', 0),
(11, '2016AllStarExtraPlayoffs', 211, '2016-02-20', 'Yellow 2', '08:00:00', 'U10G', NULL, 'P1', '', 'D1', '', 'Area 1U', 'Tony Iacobacci', 'Aeby Perez', 'Hugo Gonzalez', '', 0),
(12, '2016AllStarExtraPlayoffs', 212, '2016-02-20', 'Yellow 3', '08:00:00', 'U10G', NULL, 'U1', '', 'B2', '', 'Area 1P', 'MARTINEZ', 'RAMIREZ', 'GATES', '', 0),
(13, '2016AllStarExtraPlayoffs', 213, '2016-02-20', 'White 1', '09:00:00', 'U14B', NULL, 'C1', '', 'B1', '', 'Area 1S', 'P. Agrawal', 'J. Pargman', 'S. Dykstra', '', 0),
(14, '2016AllStarExtraPlayoffs', 214, '2016-02-20', 'White 2', '09:00:00', 'U14B', NULL, 'U1', '', 'D2', '', 'Area 1C', 'Craig Dunkin', 'Bruce Hancock', 'Peter Smock', '', 0),
(15, '2016AllStarExtraPlayoffs', 215, '2016-02-20', 'White 3', '09:00:00', 'U14B', NULL, 'C2', '', 'P1', '', 'Area 1D', 'Ramon Pulido', 'Mike Torrey', 'Sandee Wilson', '', 0),
(16, '2016AllStarExtraPlayoffs', 216, '2016-02-20', 'White 4', '09:00:00', 'U14B', NULL, 'S1', '', 'D1', '', 'Area 1P', 'RAMIREZ', 'GATES', 'MARTINEZ', '', 0),
(17, '2016AllStarExtraPlayoffs', 217, '2016-02-20', 'Blue 2', '09:00:00', 'U12B', NULL, 'S1', '', 'P2', '', 'Area 1B', 'Machado', 'Machado', 'Renteria', '', 0),
(18, '2016AllStarExtraPlayoffs', 218, '2016-02-20', 'Blue 3', '09:00:00', 'U12B', NULL, 'B1', '', 'C1', '', 'Area 1S', 'T. Smith', 'R.Jackowiak', 'Z. Harris', '', 0),
(19, '2016AllStarExtraPlayoffs', 219, '2016-02-20', 'Blue 4', '09:00:00', 'U12B', NULL, 'D1', '', 'C2', '', 'Area 1U', 'Nick Ruffle', 'Mars Ramage', 'Aeby Perez', '', 0),
(20, '2016AllStarExtraPlayoffs', 220, '2016-02-20', 'Blue 5', '09:00:00', 'U12B', NULL, 'U1', '', 'P1', '', 'Area 1D', 'Mark Parrot', 'Manny Hernandez ', 'Michael Grosvenor', '', 0),
(21, '2016AllStarExtraPlayoffs', 221, '2016-02-20', 'Green 3', '09:00:00', 'U10B', NULL, 'B2', '', 'P1', '', 'Area 1C', 'Don Gibbs', 'Brian Bonham', 'Larry Abelson', '', 0),
(22, '2016AllStarExtraPlayoffs', 222, '2016-02-20', 'Yellow 1', '09:00:00', 'U10B', NULL, 'U1', '', 'C1', '', 'Area 1B', 'Jimenez', 'Jimenez', 'Abadilla', '', 0),
(23, '2016AllStarExtraPlayoffs', 223, '2016-02-20', 'Yellow 2', '09:00:00', 'U10B', NULL, 'D1', '', 'C2', '', 'Area 1B', 'Alfred Zambrano', 'Tony Iacobacci', 'John Zhou', '', 0),
(24, '2016AllStarExtraPlayoffs', 224, '2016-02-20', 'Yellow 3', '09:00:00', 'U10B', NULL, 'B1', '', 'S1', '', 'Area 1P', 'KLEIMAN', 'ASCENZI', '', '', 0),
(25, '2016AllStarExtraPlayoffs', 225, '2016-02-20', 'White 1', '11:00:00', 'U14G', NULL, 'B1', '', 'P2', '', 'Area 1C', 'Peter Smock', 'Al Prado', 'Bruce Hancock', '', 0),
(26, '2016AllStarExtraPlayoffs', 226, '2016-02-20', 'White 2', '11:00:00', 'U14G', NULL, 'S1', '', 'U1', '', 'Area 1B', 'Udo', 'Flores', 'Baker', '', 0),
(27, '2016AllStarExtraPlayoffs', 227, '2016-02-20', 'White 3', '11:00:00', 'U14G', NULL, 'P1', '', 'D1', '', 'Area 1U', 'Bob Hernandez', 'Nick Ruffle', 'Mars Ramage', '', 0),
(28, '2016AllStarExtraPlayoffs', 228, '2016-02-20', 'White 4', '11:00:00', 'U14G', NULL, 'U2', '', 'C1', '', 'Area 1P', 'GATES', 'MARTINEZ', 'RAMIREZ', '', 0),
(29, '2016AllStarExtraPlayoffs', 229, '2016-02-20', 'Blue 2', '11:00:00', 'U12G', NULL, 'D2', '', 'U1', '', 'Area 1C', 'Dave Jones', 'Don Gibbs', 'Brian Bonham', '', 0),
(30, '2016AllStarExtraPlayoffs', 230, '2016-02-20', 'Blue 3', '11:00:00', 'U12G', NULL, 'B1', '', 'S1', '', 'Area 1P', 'MARTIN', 'REYNOLDS', 'FINEBERG', '', 0),
(31, '2016AllStarExtraPlayoffs', 231, '2016-02-20', 'Blue 4', '11:00:00', 'U12G', NULL, 'P1', '', 'C1', '', 'Area 1D', 'Mike Torrey', 'Sandee Wilson', 'Ramon Pulido', '', 0),
(32, '2016AllStarExtraPlayoffs', 232, '2016-02-20', 'Blue 5', '11:00:00', 'U12G', NULL, 'B2', '', 'D1', '', 'Area 1S', 'Larry Abelson (C)', 'Krista Wesner', 'Curtis Wesner', '', 0),
(33, '2016AllStarExtraPlayoffs', 233, '2016-02-20', 'Green 3', '11:00:00', 'U10G', NULL, 'U2', '', 'C1', '', 'Area 1B', 'Gaumer', 'Cornejo', 'Garcia', '', 0),
(34, '2016AllStarExtraPlayoffs', 234, '2016-02-20', 'Yellow 1', '11:00:00', 'U10G', NULL, 'S1', '', 'B1', '', 'Area 1D', 'Manny Hernandez ', 'Mark Parrot', 'Michael Grosvenor', '', 0),
(35, '2016AllStarExtraPlayoffs', 235, '2016-02-20', 'Yellow 2', '11:00:00', 'U10G', NULL, 'D1', '', 'U1', '', 'Area 1S', 'Todd Gallemore', 'Colin Lyon', '', '', 0),
(36, '2016AllStarExtraPlayoffs', 236, '2016-02-20', 'Yellow 3', '11:00:00', 'U10G', NULL, 'B2', '', 'P1', '', 'Area 1U', 'Jin Chun', 'Tony Koo', 'Aeby Perez', '', 0),
(37, '2016AllStarExtraPlayoffs', 237, '2016-02-20', 'White 1', '12:00:00', 'U14B', NULL, 'B1', '', 'U1', '', 'Area 1C', 'Joe Small', 'Dave Alvarez', 'Adrian Backer', '', 0),
(38, '2016AllStarExtraPlayoffs', 238, '2016-02-20', 'White 2', '12:00:00', 'U14B', NULL, 'D2', '', 'C1', '', 'Area 1B', 'Baker', 'Udo', 'Flores', '', 0),
(39, '2016AllStarExtraPlayoffs', 239, '2016-02-20', 'White 3', '12:00:00', 'U14B', NULL, 'P1', '', 'S1', '', 'Area 1U', 'Nick Ruffle', 'Mars Ramage', 'Bob Hernandez', '', 0),
(40, '2016AllStarExtraPlayoffs', 240, '2016-02-20', 'White 4', '12:00:00', 'U14B', NULL, 'D1', '', 'C2', '', 'Area 1C', '', 'ASCENZI', 'KLEIMAN', '', 0),
(41, '2016AllStarExtraPlayoffs', 241, '2016-02-20', 'Blue 2', '12:00:00', 'U12B', NULL, 'P2', '', 'B1', '', 'Area 1C', 'Al Prado', 'Tom Regan', 'Dave Jones', '', 0),
(42, '2016AllStarExtraPlayoffs', 242, '2016-02-20', 'Blue 3', '12:00:00', 'U12B', NULL, 'C1', '', 'S1', '', 'Area 1P', 'REYNOLDS', 'FINEBERG', 'MARTIN', '', 0),
(43, '2016AllStarExtraPlayoffs', 243, '2016-02-20', 'Blue 4', '12:00:00', 'U12B', NULL, 'C2', '', 'U1', '', 'Area 1D', 'Steve Resnick', 'Brian Holt', 'Greg Power', '', 0),
(44, '2016AllStarExtraPlayoffs', 244, '2016-02-20', 'Blue 5', '12:00:00', 'U12B', NULL, 'P1', '', 'D1', '', 'Area 1S', 'John Mass (C)', 'Roman Ogawa (C)', 'Todd Gallemore (G)', '', 0),
(45, '2016AllStarExtraPlayoffs', 245, '2016-02-20', 'Green 3', '12:00:00', 'U10B', NULL, 'P1', '', 'U1', '', 'Area 1B', 'Cornejo', 'Garcia', 'Gaumer', '', 0),
(46, '2016AllStarExtraPlayoffs', 246, '2016-02-20', 'Yellow 1', '12:00:00', 'U10B', NULL, 'C1', '', 'B2', '', 'Area 1D', 'Mark Parrot', 'Manny Hernandez ', 'Michael Grosvenor', '', 0),
(47, '2016AllStarExtraPlayoffs', 247, '2016-02-20', 'Yellow 2', '12:00:00', 'U10B', NULL, 'C2', '', 'B1', '', 'Area 1S', 'Colin Lyon', 'Krista Wesner', 'Curtis Wesner', '', 0),
(48, '2016AllStarExtraPlayoffs', 248, '2016-02-20', 'Yellow 3', '12:00:00', 'U10B', NULL, 'S1', '', 'D1', '', 'Area 1B', 'Rudy Ramirez', 'Kevin Aie', 'Eddie Chavez', '', 0),
(49, '2016AllStarExtraPlayoffs', 249, '2016-02-20', 'White 1', '14:00:00', 'U14G', NULL, 'U1', '', 'P2', '', 'Area 1D', 'Brian Holt', 'Steve Resnick', 'Greg Power', '', 0),
(50, '2016AllStarExtraPlayoffs', 250, '2016-02-20', 'White 2', '14:00:00', 'U14G', NULL, 'B1', '', 'S1', '', 'Area 1U', 'Tony Koo', 'Jin Chun', 'Ray Villar', '', 0),
(51, '2016AllStarExtraPlayoffs', 251, '2016-02-20', 'White 3', '14:00:00', 'U14G', NULL, 'C1', '', 'D1', '', 'Area 1B', 'Ellis', 'Koh', 'Olson', '', 0),
(52, '2016AllStarExtraPlayoffs', 252, '2016-02-20', 'White 4', '14:00:00', 'U14G', NULL, 'P1', '', 'U2', '', 'Area 1S', 'T.SMITH', 'R.JACKOWIAK', 'Z.HARRIS', '', 0),
(53, '2016AllStarExtraPlayoffs', 253, '2016-02-20', 'Blue 2', '14:00:00', 'U12G', NULL, 'S1', '', 'U1', '', 'Area 1P', 'TOWNSEND', 'SIMMONS', '', '', 0),
(54, '2016AllStarExtraPlayoffs', 254, '2016-02-20', 'Blue 3', '14:00:00', 'U12G', NULL, 'D2', '', 'B1', '', 'Area 1U', 'Jose Alcaraz', 'Tom West', 'Eric Mikkelson', '', 0),
(55, '2016AllStarExtraPlayoffs', 255, '2016-02-20', 'Blue 4', '14:00:00', 'U12G', NULL, 'D1', '', 'C1', '', 'Area 1B', 'Carlton', 'Cerda', 'Urias', '', 0),
(56, '2016AllStarExtraPlayoffs', 256, '2016-02-20', 'Blue 5', '14:00:00', 'U12G', NULL, 'P1', '', 'B2', '', 'Area 1C', 'Adrian Backer', 'Joe Small', 'Dave Alvarez', '', 0),
(57, '2016AllStarExtraPlayoffs', 257, '2016-02-20', 'Green 3', '14:00:00', 'U10G', NULL, 'B1', '', 'C1', '', 'Area 1S', 'S.DYKSTRA', 'J.PARGMAN', 'P.AGRAWAL', '', 0),
(58, '2016AllStarExtraPlayoffs', 258, '2016-02-20', 'Yellow 1', '14:00:00', 'U10G', NULL, 'U2', '', 'S1', '', 'Area 1D', 'Jack Desemone', 'Vera Horoschak', 'Sam Kabbani', '', 0),
(59, '2016AllStarExtraPlayoffs', 259, '2016-02-20', 'Yellow 2', '14:00:00', 'U10G', NULL, 'P1', '', 'U1', '', 'Area 1C', 'Ricky Gonzalez', 'Al Prado', 'Tom Regan', '', 0),
(60, '2016AllStarExtraPlayoffs', 260, '2016-02-20', 'Yellow 3', '14:00:00', 'U10G', NULL, 'D1', '', 'B2', '', 'Area 1P', 'FINEBERG', 'MARTIN', 'REYNOLDS', '', 0),
(61, '2016AllStarExtraPlayoffs', 261, '2016-02-20', 'White 1', '15:00:00', 'U14B', NULL, 'C1', '', 'U1', '', 'Area 1D', 'Greg Power', 'Brian Holt', 'Steve Resnick', '', 0),
(62, '2016AllStarExtraPlayoffs', 262, '2016-02-20', 'White 2', '15:00:00', 'U14B', NULL, 'B1', '', 'D2', '', 'Area 1U', 'Jin Chun', 'Tony Koo', 'Ray Villar', '', 0),
(63, '2016AllStarExtraPlayoffs', 263, '2016-02-20', 'White 3', '15:00:00', 'U14B', NULL, 'C2', '', 'S1', '', 'Area 1B', 'Koh', 'Olson', 'Ellis', '', 0),
(64, '2016AllStarExtraPlayoffs', 264, '2016-02-20', 'White 4', '15:00:00', 'U14B', NULL, 'P1', '', 'D1', '', 'Area 1S', 'P.Agrawal', 'J.Pargman', 'S.Dykstra', '', 0),
(65, '2016AllStarExtraPlayoffs', 265, '2016-02-20', 'Blue 2', '15:00:00', 'U12B', NULL, 'S1', '', 'B1', '', 'Area 1P', 'KOZINSKI', 'COOPER', 'FEDER', '', 0),
(66, '2016AllStarExtraPlayoffs', 266, '2016-02-20', 'Blue 3', '15:00:00', 'U12B', NULL, 'P2', '', 'C1', '', 'Area 1U', 'Rudy Ramirez', 'Kevin Aie', 'Eddie Chavez', '', 0),
(67, '2016AllStarExtraPlayoffs', 267, '2016-02-20', 'Blue 4', '15:00:00', 'U12B', NULL, 'D1', '', 'U1', '', 'Area 1C', 'Dave Alvarez', 'Adrian Backer', 'Joe Small', '', 0),
(68, '2016AllStarExtraPlayoffs', 268, '2016-02-20', 'Blue 5', '15:00:00', 'U12B', NULL, 'C2', '', 'P1', '', 'Area 1B', 'Region 67 TBA', 'Region 67 TBA', 'Region 67 TBA', '', 0),
(69, '2016AllStarExtraPlayoffs', 269, '2016-02-20', 'Green 3', '15:00:00', 'U10B', NULL, 'B2', '', 'U1', '', 'Area 1S', 'T.SMITH', 'R.JACKOWIAC', 'Z.HARRIS', '', 0),
(70, '2016AllStarExtraPlayoffs', 270, '2016-02-20', 'Yellow 1', '15:00:00', 'U10B', NULL, 'P1', '', 'C1', '', 'Area 1D', 'Vera Horoschak', 'Jack Desemone', 'Sam Kabbani', '', 0),
(71, '2016AllStarExtraPlayoffs', 271, '2016-02-20', 'Yellow 2', '15:00:00', 'U10B', NULL, 'D1', '', 'B1', '', 'Area 1C', 'Tom Regan', 'Ricky Gonzalez', 'Dave Jones', '', 0),
(72, '2016AllStarExtraPlayoffs', 272, '2016-02-20', 'Yellow 3', '15:00:00', 'U10B', NULL, 'C2', '', 'S1', '', 'Area 1P', 'MARTIN', 'REYNOLDS', 'FINEBERG', '', 0),
(73, '2016AllStarExtraPlayoffs', 301, '2016-02-20', 'Murphy 1', '09:00:00', 'U14G', NULL, 'E-1', '', 'W-2', '', 'Area 1R', 'Ramon Guzman', 'Junior Mora', 'Jennifer Diaz', '', 0),
(74, '2016AllStarExtraPlayoffs', 302, '2016-02-20', 'Murphy 2', '09:00:00', 'U14G', NULL, 'W-1', '', 'E-2', '', 'Area 1P', '', 'SIMMONS', 'TOWNSEND', '', 0),
(75, '2016AllStarExtraPlayoffs', 303, '2016-02-20', 'Blue 6', '09:00:00', 'U12G', NULL, 'E-1', '', 'W-2', '', 'Area 1U', 'Jose Alcaraz', 'Tom West', 'Eric Mikkelson', '', 0),
(76, '2016AllStarExtraPlayoffs', 304, '2016-02-20', 'Blue 1', '09:00:00', 'U12G', NULL, 'W-1', '', 'E-2', '', 'Area 1F', 'Joe T', 'Michael W', 'Herb C', '', 0),
(77, '2016AllStarExtraPlayoffs', 305, '2016-02-20', 'Green 1', '09:00:00', 'U10G', NULL, 'E-1', '', 'W-2', '', 'Area 1P', 'COOPER', 'FEDER', 'KOZINSKI', '', 0),
(78, '2016AllStarExtraPlayoffs', 306, '2016-02-20', 'Green 2', '09:00:00', 'U10G', NULL, 'W-1', '', 'E-2', '', 'Area 1R', 'Ed Williams', 'David Schlesinger', 'Lee Lombard', '', 0),
(79, '2016AllStarExtraPlayoffs', 307, '2016-02-20', 'Murphy 1', '11:00:00', 'U14B', NULL, 'E-1', '', 'W-2', '', 'Area 1R', 'Ramon Guzman', 'Junior Mora', 'Jennifer Diaz', '', 0),
(80, '2016AllStarExtraPlayoffs', 308, '2016-02-20', 'Murphy 2', '11:00:00', 'U14B', NULL, 'W-1', '', 'E-2', '', 'Area 1P', 'FEDER', 'KOZINSKI', 'COOPER', '', 0),
(81, '2016AllStarExtraPlayoffs', 309, '2016-02-20', 'Blue 6', '11:00:00', 'U12B', NULL, 'E-1', '', 'W-2', '', 'Area 1N', 'Gilbert Maldonado', 'Manuel Merlos', 'Bill Hinds', '', 0),
(82, '2016AllStarExtraPlayoffs', 310, '2016-02-20', 'Blue 1', '11:00:00', 'U12B', NULL, 'W-1', '', 'E-2', '', 'Area 1F', 'Joe T', 'Michael W', '-', '', 0),
(83, '2016AllStarExtraPlayoffs', 311, '2016-02-20', 'Green 1', '11:00:00', 'U10B', NULL, 'E-1', '', 'W-2', '', 'Area 1P', 'SIMMONS', 'TOWNSEND', '', '', 0),
(84, '2016AllStarExtraPlayoffs', 312, '2016-02-20', 'Green 2', '11:00:00', 'U10B', NULL, 'W-1', '', 'E-2', '', 'Area 1R', 'Lalo Chaidez', 'Cruz Chaidez', 'Cole Chaidez', '', 0),
(85, '2016AllStarExtraPlayoffs', 325, '2016-02-21', 'White 3', '09:00:00', 'U13G', NULL, 'E-1', '', 'W-2', '', 'Area 1D', 'Scott Jarus', 'Bill Raventos', 'Craig Breitman', '', 0),
(86, '2016AllStarExtraPlayoffs', 326, '2016-02-21', 'White 4', '09:00:00', 'U13G', NULL, 'W-1', '', 'E-2', '', 'Area 1R', 'Ed Williams', 'Lee Lombard', 'Ozzy Castro', '', 0),
(87, '2016AllStarExtraPlayoffs', 327, '2016-02-21', 'Blue 4', '09:00:00', 'U11G', NULL, 'E-1', '', 'W-2', '', 'Area 1F', 'Joe T', '-', 'Geoff F', '', 0),
(88, '2016AllStarExtraPlayoffs', 328, '2016-02-21', 'Blue 5', '09:00:00', 'U11G', NULL, 'W-1', '', 'E-2', '', 'Area 1G', 'Paul Mikusky', 'Mitch Graham', 'Lealon Watts', '', 0),
(89, '2016AllStarExtraPlayoffs', 329, '2016-02-21', 'Green 3', '09:00:00', 'U09G', NULL, 'E-1', '', 'W-2', '', 'Area 1B', 'Skalsky', 'Purdy', 'Churchill', '', 0),
(90, '2016AllStarExtraPlayoffs', 330, '2016-02-21', 'Yellow 3', '09:00:00', 'U09G', NULL, 'W-1', '', 'E-2', '', 'Area 1P', '', 'KLEIMAN', 'MARTIN', '', 0),
(91, '2016AllStarExtraPlayoffs', 331, '2016-02-21', 'White 3', '11:00:00', 'U13B', NULL, 'E-1', '', 'W-2', '', 'Area 1D', 'Bill Raventos', 'Scott Jarus', 'Craig Breitman', '', 0),
(92, '2016AllStarExtraPlayoffs', 332, '2016-02-21', 'White 4', '11:00:00', 'U13B', NULL, 'W-1', '', 'E-2', '', 'Area 1R', 'Stefan Larson', 'Jason Gailliot', 'Jody Richardson', '', 0),
(93, '2016AllStarExtraPlayoffs', 333, '2016-02-21', 'Blue 4', '11:00:00', 'U11B', NULL, 'E-1', '', 'W-2', '', 'Area 1B', 'Churchill', 'Skalsky', 'Purdy', '', 0),
(94, '2016AllStarExtraPlayoffs', 334, '2016-02-21', 'Blue 5', '11:00:00', 'U11B', NULL, 'W-1', '', 'E-2', '', 'Area 1G', 'Lealon Watts', 'Paul Mikusky', 'Mitch Graham', '', 0),
(95, '2016AllStarExtraPlayoffs', 335, '2016-02-21', 'Green 3', '11:00:00', 'U09B', NULL, 'E-1', '', 'W-2', '', 'Area 1P', 'KLEIMAN', 'MARTIN', '', '', 0),
(96, '2016AllStarExtraPlayoffs', 336, '2016-02-21', 'Yellow 3', '11:00:00', 'U09B', NULL, 'W-1', '', 'E-2', '', 'Area 1F', 'Joe T', '-', 'Geoff F', '', 0),
(97, '2016LeaguePlayoffs', 1, '2016-02-27', 'White 1', '08:00:00', 'U14B', NULL, 'R1', '', 'N1', '', 'Area 1D', 'Brian Holt', 'Steve Resnick', 'Michael Grosvenor', '', 0),
(98, '2016LeaguePlayoffs', 2, '2016-02-27', 'White 2', '08:00:00', 'U14B', NULL, 'C2', '', 'U2', '', 'Area 1G', 'Lealon Watts', 'Mitch Graham', 'Edman Urias', '', 0),
(99, '2016LeaguePlayoffs', 3, '2016-02-27', 'White 3', '08:00:00', 'U14B', NULL, 'B1', '', 'G1', '', 'Area 1C', 'Dave Alvarez', 'Dave Jones', 'Bruce Hancock', '', 0),
(100, '2016LeaguePlayoffs', 4, '2016-02-27', 'White 4', '08:00:00', 'U14B', NULL, 'R2', '', 'N2', '', 'Area 1B', 'Meehan ', 'Flores', 'Eddings', '', 0),
(101, '2016LeaguePlayoffs', 5, '2016-02-27', 'Blue 1', '08:00:00', 'U12B', NULL, 'N2', '', 'H2', '', 'Area 1F', 'Joe T', 'Michael W', 'Alan S', '', 0),
(102, '2016LeaguePlayoffs', 6, '2016-02-27', 'Blue 2', '08:00:00', 'U12B', NULL, 'G1', '', 'B1', '', 'Area 1H', 'Andrew Laule', 'Essie Sebti', 'Dave Derrin', '', 0),
(103, '2016LeaguePlayoffs', 7, '2016-02-27', 'Blue 3', '08:00:00', 'U12B', NULL, 'R1', '', 'N1', '', 'Area 1U', 'Alfred Zambrano', 'Aeby Perez', 'Mars Ramage', '', 0),
(104, '2016LeaguePlayoffs', 8, '2016-02-27', 'Blue 4', '08:00:00', 'U12B', NULL, 'H1', '', 'U2', '', 'Area 1N', 'Nick Herrin', 'Doug Herrin', 'Mark Slobom', '', 0),
(105, '2016LeaguePlayoffs', 9, '2016-02-27', 'Green 1', '08:00:00', 'U10B', NULL, 'N2', '', 'B1', '', 'Area 1P', 'KLEIMAN', 'YOKOGAWA', 'REYNOLDS', '', 0),
(106, '2016LeaguePlayoffs', 10, '2016-02-27', 'Green 2', '08:00:00', 'U10B', NULL, 'R1', '', 'H2', '', 'Area 1B', 'Jimenez', 'Jimenez', 'Rodriguez', '', 0),
(107, '2016LeaguePlayoffs', 11, '2016-02-27', 'Green 3', '08:00:00', 'U10B', NULL, 'U1', '', 'N1', '', 'Area 1R', 'Junior Mora', 'Keith Backus', 'Jose Alcaraz', '', 0),
(108, '2016LeaguePlayoffs', 12, '2016-02-27', 'Yellow 1', '08:00:00', 'U10B', NULL, 'G2', '', 'C1', '', 'Area 1D', 'Bruce Anderson', 'Mark Parrot', 'Chad Gordon', '', 0),
(109, '2016LeaguePlayoffs', 13, '2016-02-27', 'White 1', '09:00:00', 'U14G', NULL, 'C1', '', 'U1', '', 'Area 1D', 'Michael Grosvenor', 'Brian Holt', 'Steve Resnick', '', 0),
(110, '2016LeaguePlayoffs', 14, '2016-02-27', 'White 2', '09:00:00', 'U14G', NULL, 'H2', '', 'B1', '', 'Area 1G', 'Rafael Arellano ', 'Monte Stone ', 'Fransico Perez', '', 0),
(111, '2016LeaguePlayoffs', 15, '2016-02-27', 'White 3', '09:00:00', 'U14G', NULL, 'G1', '', 'B2', '', 'Area 1C', 'Dave Jones', 'Bruce Hancock', 'Dave Alvarez', '', 0),
(112, '2016LeaguePlayoffs', 16, '2016-02-27', 'White 4', '09:00:00', 'U14G', NULL, 'H1', '', 'N1', '', 'Area 1B', 'Flores', 'Eddings', 'Meehan', '', 0),
(113, '2016LeaguePlayoffs', 17, '2016-02-27', 'Murphy 1', '09:00:00', 'U14B', NULL, 'P1', '', 'U1', '', 'Area 1R', 'Jeff Johnson', 'Rick Polmonter', 'Dawn Hlavac', '', 0),
(114, '2016LeaguePlayoffs', 18, '2016-02-27', 'Murphy 2', '09:00:00', 'U14B', NULL, 'F1', '', 'B2', '', 'Area 1G', 'Bev Reyes', 'Rolando Prado', 'Cesar Quintero', '', 0),
(115, '2016LeaguePlayoffs', 19, '2016-02-27', 'Blue 1', '09:00:00', 'U12G', NULL, 'U1', '', 'G1', '', 'Area 1F', 'Joe T', 'Michael W', 'Alan S', '', 0),
(116, '2016LeaguePlayoffs', 20, '2016-02-27', 'Blue 2', '09:00:00', 'U12G', NULL, 'B1', '', 'C2', '', 'Area 1H', 'Paul Suhkram', 'Andrew Laule', 'Dave Derrin', '', 0),
(117, '2016LeaguePlayoffs', 21, '2016-02-27', 'Blue 3', '09:00:00', 'U12G', NULL, 'G2', '', 'P1', '', 'Area 1U', 'aeby Perez', 'Alfred Zambrano', 'Arturo Barrera', '', 0),
(118, '2016LeaguePlayoffs', 22, '2016-02-27', 'Blue 4', '09:00:00', 'U12G', NULL, 'R1', '', 'H2', '', 'Area 1N', 'Mark Slobom', 'Doug Herrin', 'Nick Herrin', '', 0),
(119, '2016LeaguePlayoffs', 23, '2016-02-27', 'Blue 5', '09:00:00', 'U12B', NULL, 'B2', '', 'P1', '', 'Area 1F', 'David P', 'David R.', 'Steve W.', '', 0),
(120, '2016LeaguePlayoffs', 24, '2016-02-27', 'Blue 6', '09:00:00', 'U12B', NULL, 'C1', '', 'F1', '', 'Area 1G', 'Fernando Cobos', 'Gregory Langhorst', 'Heather Freeman', '', 0),
(121, '2016LeaguePlayoffs', 25, '2016-02-27', 'Green 1', '09:00:00', 'U10G', NULL, 'B1', '', 'C1', '', 'Area 1P', 'YOKOGAWA', 'REYNOLDS', 'KLEIMAN', '', 0),
(122, '2016LeaguePlayoffs', 26, '2016-02-27', 'Green 2', '09:00:00', 'U10G', NULL, 'G2', '', 'U1', '', 'Area 1B', 'Nieto', 'Rodriguez', 'Abadilla', '', 0),
(123, '2016LeaguePlayoffs', 27, '2016-02-27', 'Green 3', '09:00:00', 'U10G', NULL, 'H1', '', 'G1', '', 'Area 1R', 'Stefan Larson', 'Jason Gailliot', 'Jody Richardson', '', 0),
(124, '2016LeaguePlayoffs', 28, '2016-02-27', 'Yellow 1', '09:00:00', 'U10G', NULL, 'N1', '', 'R2', '', 'Area 1D', 'Mark Parrot', 'Bruce Anderson', 'Chad Gordon', '', 0),
(125, '2016LeaguePlayoffs', 29, '2016-02-27', 'Yellow 2', '09:00:00', 'U10B', NULL, 'G1', '', 'P2', '', 'Area 1N', 'Dennis Raymond', 'Les Berkey', 'Jose Serrano', '', 0),
(126, '2016LeaguePlayoffs', 30, '2016-02-27', 'Yellow 3', '09:00:00', 'U10B', NULL, 'F1', '', 'D1', '', 'Area 1C', 'Brian Bonham', 'Stephanie Dote', 'Michael Ball', '', 0),
(127, '2016LeaguePlayoffs', 31, '2016-02-27', 'White 1', '10:00:00', 'U14B', NULL, 'H1', '', 'C1', '', 'Area 1D', 'Steve Resnick', 'Brian Holt', 'Michael Grosvenor', '', 0),
(128, '2016LeaguePlayoffs', 32, '2016-02-27', 'White 2', '10:00:00', 'U14B', NULL, 'P2', '', 'D1', '', 'Area 1G', 'Rolando Prado', 'Rafael Arellano ', 'Nick farmer', '', 0),
(129, '2016LeaguePlayoffs', 33, '2016-02-27', 'White 3', '10:00:00', 'U14G', NULL, 'D1', '', 'F2', '', 'Area 1C', 'Bruce Hancock', 'Dave Alvarez', 'Dave Jones', '', 0),
(130, '2016LeaguePlayoffs', 34, '2016-02-27', 'White 4', '10:00:00', 'U14G', NULL, 'P1', '', 'G2', '', 'Area 1B', 'Eddings', 'Meehan', 'Flores', '', 0),
(131, '2016LeaguePlayoffs', 35, '2016-02-27', 'Murphy 1', '10:00:00', 'U14G', NULL, 'N2', '', 'F1', '', 'Area 1R', 'Luis Bueno', 'Jennifer Diaz', 'Doug Covey', '', 0),
(132, '2016LeaguePlayoffs', 36, '2016-02-27', 'Murphy 2', '10:00:00', 'U14G', NULL, 'D2', '', 'R1', '', 'Area 1G', 'Mitch Graham', 'Lealon Watts', 'Fernando Cobos', '', 0),
(133, '2016LeaguePlayoffs', 37, '2016-02-27', 'Blue 1', '10:00:00', 'U12B', NULL, 'U1', '', 'R2', '', 'Area 1F', 'Joe T', 'Michael W', 'Alan S', '', 0),
(134, '2016LeaguePlayoffs', 38, '2016-02-27', 'Blue 2', '10:00:00', 'U12B', NULL, 'D1', '', 'P2', '', 'Area 1H', 'Essie Sebti', 'Andrew Laule', 'Paul Suhkram', '', 0),
(135, '2016LeaguePlayoffs', 39, '2016-02-27', 'Blue 3', '10:00:00', 'U12G', NULL, 'R2', '', 'D1', '', 'Area 1U', 'Arturo Barrera', 'Robert Lliteras', 'Aeby Perez', '', 0),
(136, '2016LeaguePlayoffs', 40, '2016-02-27', 'Blue 4', '10:00:00', 'U12G', NULL, 'C1', '', 'F1', '', 'Area 1N', 'Nick Herrin', 'Doug Herrin', 'Mark Slobom', '', 0),
(137, '2016LeaguePlayoffs', 41, '2016-02-27', 'Blue 5', '10:00:00', 'U12G', NULL, 'F2', '', 'D2', '', 'Area 1G', 'Gregory Langhorst', 'Kyle Watson', 'Don Kennard', '', 0),
(138, '2016LeaguePlayoffs', 42, '2016-02-27', 'Blue 6', '10:00:00', 'U12G', NULL, 'N1', '', 'H1', '', 'Area 1F', 'Neville M.', 'Valente M.', 'Ken B.', '', 0),
(139, '2016LeaguePlayoffs', 43, '2016-02-27', 'Green 1', '10:00:00', 'U10B', NULL, 'B2', '', 'H1', '', 'Area 1P', 'REYNOLDS', 'KLEIMAN', 'YOKOGAWA', '', 0),
(140, '2016LeaguePlayoffs', 44, '2016-02-27', 'Green 2', '10:00:00', 'U10B', NULL, 'F2', '', 'P1', '', 'Area 1B', 'Rodriguez', 'Abadilla', 'Nieto', '', 0),
(141, '2016LeaguePlayoffs', 45, '2016-02-27', 'Green 3', '10:00:00', 'U10G', NULL, 'D2', '', 'F2', '', 'Area 1R', 'Pete Magana', 'Pete Soto', 'Ruben Rendon', '', 0),
(142, '2016LeaguePlayoffs', 46, '2016-02-27', 'Yellow 1', '10:00:00', 'U10G', NULL, 'R1', '', 'P1', '', 'Area 1D', 'Chad Gordon', 'Mark Parrot', 'Bruce Anderson', '', 0),
(143, '2016LeaguePlayoffs', 47, '2016-02-27', 'Yellow 2', '10:00:00', 'U10G', NULL, 'U2', '', 'C2', '', 'Area 1N', 'James Stubbs', 'Mike Hamilton', 'Sergio Olivera', '', 0),
(144, '2016LeaguePlayoffs', 48, '2016-02-27', 'Yellow 3', '10:00:00', 'U10G', NULL, 'D1', '', 'F1', '', 'Area 1C', 'Michael Ball', 'Brian Bonham', 'Stephanie Dote', '', 0),
(145, '2016LeaguePlayoffs', 49, '2016-02-27', 'White 1', '11:00:00', 'U14B', NULL, 'N1', '', 'C2', '', 'Area 1H', 'Manuel Del Rio', 'Mike Mineo', 'Amer Hassouen', '', 0),
(146, '2016LeaguePlayoffs', 50, '2016-02-27', 'White 2', '11:00:00', 'U14B', NULL, 'U2', '', 'R1', '', 'Area 1P', 'GATES', 'KOZINSKI', 'SIMMONS', '', 0),
(147, '2016LeaguePlayoffs', 51, '2016-02-27', 'White 3', '11:00:00', 'U14B', NULL, 'G1', '', 'R2', '', 'Area 1U', 'Tony Koo', 'Ron Ong', 'David Asada', '', 0),
(148, '2016LeaguePlayoffs', 52, '2016-02-27', 'White 4', '11:00:00', 'U14B', NULL, 'N2', '', 'B1', '', 'Area 1C', 'John Mass', 'Al Prado', 'Scott Davis', '', 0),
(149, '2016LeaguePlayoffs', 53, '2016-02-27', 'Blue 1', '11:00:00', 'U12B', NULL, 'H2', '', 'G1', '', 'Area 1U', 'Mars or Joe Santana', 'Bob Kiss', 'Nathan Thomas', '', 0),
(150, '2016LeaguePlayoffs', 54, '2016-02-27', 'Blue 2', '11:00:00', 'U12B', NULL, 'B1', '', 'N2', '', 'Area 1H', 'John Hampson', 'Albert Blanco', 'Henry Ybarra', '', 0),
(151, '2016LeaguePlayoffs', 55, '2016-02-27', 'Blue 3', '11:00:00', 'U12B', NULL, 'N1', '', 'H1', '', 'Area 1R', 'Bryan DeLoss', 'Glen Christoffersen', 'Lalo Chaidez', '', 0),
(152, '2016LeaguePlayoffs', 56, '2016-02-27', 'Blue 4', '11:00:00', 'U12B', NULL, 'U2', '', 'R1', '', 'Area 1P', 'KLEIMAN', 'YOKOGAWA', 'MARTIN', '', 0),
(153, '2016LeaguePlayoffs', 57, '2016-02-27', 'Green 1', '11:00:00', 'U10B', NULL, 'B1', '', 'R1', '', 'Area 1U', 'Mars Ramage', 'Enrique Zatarain', 'Manny Alvarado', '', 0),
(154, '2016LeaguePlayoffs', 58, '2016-02-27', 'Green 2', '11:00:00', 'U10B', NULL, 'H2', '', 'N2', '', 'Area 1F', 'Joe T', 'Michael W', 'Alan S', '', 0),
(155, '2016LeaguePlayoffs', 59, '2016-02-27', 'Green 3', '11:00:00', 'U10B', NULL, 'N1', '', 'G2', '', 'Area 1D', 'Krista Skinner', 'Harry Andreas', 'Eric Knudson', '', 0),
(156, '2016LeaguePlayoffs', 60, '2016-02-27', 'Yellow 1', '11:00:00', 'U10B', NULL, 'C1', '', 'U1', '', 'Area 1N', 'Chris Burden', 'Jose Juerta', 'Julio Camarena', '', 0),
(157, '2016LeaguePlayoffs', 61, '2016-02-27', 'White 1', '12:00:00', 'U14G', NULL, 'U1', '', 'H2', '', 'Area 1H', 'Mike Mineo', 'Manuel Del Rio', 'Amer Hassouen', '', 0),
(158, '2016LeaguePlayoffs', 62, '2016-02-27', 'White 2', '12:00:00', 'U14G', NULL, 'B1', '', 'C1', '', 'Area 1P', 'KOZINSKI', 'SIMMONS', 'GATES', '', 0),
(159, '2016LeaguePlayoffs', 63, '2016-02-27', 'White 3', '12:00:00', 'U14G', NULL, 'B2', '', 'H1', '', 'Area 1U', 'Ron Ong', 'David Asada', 'Tony Koo', '', 0),
(160, '2016LeaguePlayoffs', 64, '2016-02-27', 'White 4', '12:00:00', 'U14G', NULL, 'N1', '', 'G1', '', 'Area 1C', 'Scott Davis', 'John Mass', 'Al Prado', '', 0),
(161, '2016LeaguePlayoffs', 65, '2016-02-27', 'Murphy 1', '12:00:00', 'U14B', NULL, 'U1', '', 'F1', '', 'Area 1N', 'Dennis Raymond', 'Les Berkey', 'Jose Serrano', '', 0),
(162, '2016LeaguePlayoffs', 66, '2016-02-27', 'Murphy 2', '12:00:00', 'U14B', NULL, 'B2', '', 'P1', '', 'Area 1F', 'Greg Hood', 'Ruben Nieto', 'Fernando Rangel', '', 0),
(163, '2016LeaguePlayoffs', 67, '2016-02-27', 'Blue 1', '12:00:00', 'U12G', NULL, 'G1', '', 'B1', '', 'Area 1U', 'Arturo Barrera', 'Robert Lliteras', 'Jeremy Davis', '', 0),
(164, '2016LeaguePlayoffs', 68, '2016-02-27', 'Blue 2', '12:00:00', 'U12G', NULL, 'C2', '', 'U1', '', 'Area 1R', 'Stefan Larson', 'Jason Gailliot', 'Fernando Gutierrez', '', 0),
(165, '2016LeaguePlayoffs', 69, '2016-02-27', 'Blue 3', '12:00:00', 'U12G', NULL, 'P1', '', 'R1', '', 'Area 1H', 'Henry Ybarra', 'John Hampson', 'Albert Blanco', '', 0),
(166, '2016LeaguePlayoffs', 70, '2016-02-27', 'Blue 4', '12:00:00', 'U12G', NULL, 'H2', '', 'G2', '', 'Area 1P', 'MERCHAN', 'WADE', 'MARTIN', '', 0),
(167, '2016LeaguePlayoffs', 71, '2016-02-27', 'Blue 5', '12:00:00', 'U12B', NULL, 'P1', '', 'C1', '', 'Area 1F', 'Chris Levy (G)', 'Randy Abulon', 'Moe Alami', '', 0),
(168, '2016LeaguePlayoffs', 72, '2016-02-27', 'Blue 6', '12:00:00', 'U12B', NULL, 'F1', '', 'B2', '', 'Area 1G', 'Helge Lubmann', 'Juan Barragan', 'Pablo Mejia', '', 0),
(169, '2016LeaguePlayoffs', 73, '2016-02-27', 'Green 1', '12:00:00', 'U10G', NULL, 'C1', '', 'G2', '', 'Area 1U', 'Todd Flink', 'Bob Kiss', 'Chris Garcia', '', 0),
(170, '2016LeaguePlayoffs', 74, '2016-02-27', 'Green 2', '12:00:00', 'U10G', NULL, 'U1', '', 'B1', '', 'Area 1F', 'Michael W', 'Don D', 'Steve Caro', '', 0),
(171, '2016LeaguePlayoffs', 75, '2016-02-27', 'Green 3', '12:00:00', 'U10G', NULL, 'G1', '', 'N1', '', 'Area 1D', 'Region 21', 'Region 21', 'Region 21', '', 0),
(172, '2016LeaguePlayoffs', 76, '2016-02-27', 'Yellow 1', '12:00:00', 'U10G', NULL, 'R2', '', 'H1', '', 'Area 1N', 'James Stubbs', 'Jackie Quintana', 'Daniel Diez', '', 0),
(173, '2016LeaguePlayoffs', 77, '2016-02-27', 'Yellow 2', '12:00:00', 'U10B', NULL, 'P2', '', 'F1', '', 'Area 1B', 'Kent Large', 'Luis Mariscal', 'Diane Flowers', '', 0),
(174, '2016LeaguePlayoffs', 78, '2016-02-27', 'Yellow 3', '12:00:00', 'U10B', NULL, 'D1', '', 'G1', '', 'Area 1N', 'Chris Burden', 'Jose Juerta', 'Julio Camarena', '', 0),
(175, '2016LeaguePlayoffs', 79, '2016-02-27', 'White 1', '13:00:00', 'U14B', NULL, 'C1', '', 'P2', '', 'Area 1H', 'Amer Hassouen', 'Manuel Del Rio', 'Mike  Mineo', '', 0),
(176, '2016LeaguePlayoffs', 80, '2016-02-27', 'White 2', '13:00:00', 'U14B', NULL, 'D1', '', 'H1', '', 'Area 1P', 'SIMMONS', 'GATES', 'KOZINSKI', '', 0),
(177, '2016LeaguePlayoffs', 81, '2016-02-27', 'White 3', '13:00:00', 'U14G', NULL, 'F2', '', 'P1', '', 'Area 1U', 'David Asada', 'Tony Koo', 'Ron Ong', '', 0),
(178, '2016LeaguePlayoffs', 82, '2016-02-27', 'White 4', '13:00:00', 'U14G', NULL, 'G2', '', 'D1', '', 'Area 1C', 'Al Prado', 'Scott Davis', 'John Mass', '', 0),
(179, '2016LeaguePlayoffs', 83, '2016-02-27', 'Murphy 1', '13:00:00', 'U14G', NULL, 'F1', '', 'D2', '', 'Area 1N', 'Dennis Raymond', 'Les Berkey', 'Jose Serrano', '', 0),
(180, '2016LeaguePlayoffs', 84, '2016-02-27', 'Murphy 2', '13:00:00', 'U14G', NULL, 'R1', '', 'N2', '', 'Area 1F', 'Greg Hood', 'Ruben Nieto', 'Fernando Rangel', '', 0),
(181, '2016LeaguePlayoffs', 85, '2016-02-27', 'Blue 1', '13:00:00', 'U12B', NULL, 'R2', '', 'D1', '', 'Area 1U', 'Mars Ramage', 'Manny Alvarado', 'Eric Mikkelson', '', 0),
(182, '2016LeaguePlayoffs', 86, '2016-02-27', 'Blue 2', '13:00:00', 'U12B', NULL, 'P2', '', 'U1', '', 'Area 1H', 'Albert Blanco', 'John Hampson', 'Henry Ybarra', '', 0),
(183, '2016LeaguePlayoffs', 87, '2016-02-27', 'Blue 3', '13:00:00', 'U12G', NULL, 'D1', '', 'C1', '', 'Area 1R', 'Greg Goebel', 'David Schlesinger', 'T.J. McCree', '', 0),
(184, '2016LeaguePlayoffs', 88, '2016-02-27', 'Blue 4', '13:00:00', 'U12G', NULL, 'F1', '', 'R2', '', 'Area 1P', 'BRENA', 'MARTIN', 'REYNOLDS', '', 0),
(185, '2016LeaguePlayoffs', 89, '2016-02-27', 'Blue 5', '13:00:00', 'U12G', NULL, 'D2', '', 'N1', '', 'Area 1F', 'Joe Bernier. ', 'Chris Levy (G)', 'Don. D.', '', 0),
(186, '2016LeaguePlayoffs', 90, '2016-02-27', 'Blue 6', '13:00:00', 'U12G', NULL, 'H1', '', 'F2', '', 'Area 1G', 'Juan Barragan', 'Helge Lubmann', 'Pablo Mejia', '', 0),
(187, '2016LeaguePlayoffs', 91, '2016-02-27', 'Green 1', '13:00:00', 'U10B', NULL, 'H1', '', 'F2', '', 'Area 1U', 'Todd Flink', 'Chris Hickman', 'Angel Benevides', '', 0),
(188, '2016LeaguePlayoffs', 92, '2016-02-27', 'Green 2', '13:00:00', 'U10B', NULL, 'P1', '', 'B2', '', 'Area 1F', 'Ken B.', 'JJ. Arredondo', 'P. Arredondo', '', 0),
(189, '2016LeaguePlayoffs', 93, '2016-02-27', 'Green 3', '13:00:00', 'U10G', NULL, 'F2', '', 'R1', '', 'Area 1D', 'Harry Andreas', 'Krista Skinner', 'Eric Knudson', '', 0),
(190, '2016LeaguePlayoffs', 94, '2016-02-27', 'Yellow 1', '13:00:00', 'U10G', NULL, 'P1', '', 'D2', '', 'Area 1N', 'Natasha Palmer', 'Daniel Diez', 'Jackie Quintana', '', 0),
(191, '2016LeaguePlayoffs', 95, '2016-02-27', 'Yellow 2', '13:00:00', 'U10G', NULL, 'C2', '', 'D1', '', 'Area 1B', 'Kent Large', 'Luis Mariscal', 'Diane Flowers', '', 0),
(192, '2016LeaguePlayoffs', 96, '2016-02-27', 'Yellow 3', '13:00:00', 'U10G', NULL, 'F1', '', 'U2', '', 'Area 1N', 'Chris Burden', 'Jose Juerta', 'Julio Camarena', '', 0),
(193, '2016LeaguePlayoffs', 97, '2016-02-27', 'White 1', '14:00:00', 'U14B', NULL, 'N1', '', 'U2', '', 'Area 1P', 'CHARLES', 'ANDREWS', 'MERCHAN', '', 0),
(194, '2016LeaguePlayoffs', 98, '2016-02-27', 'White 2', '14:00:00', 'U14B', NULL, 'R1', '', 'C2', '', 'Area 1F', 'Neville M.', 'Valente M.', 'Ken B.', '', 0),
(195, '2016LeaguePlayoffs', 99, '2016-02-27', 'White 3', '14:00:00', 'U14B', NULL, 'G1', '', 'N2', '', 'Area 1R', 'Pete Magana', 'Pete Soto', 'Ruben Rendon', '', 0),
(196, '2016LeaguePlayoffs', 100, '2016-02-27', 'White 4', '14:00:00', 'U14B', NULL, 'B1', '', 'R2', '', 'Area 1N', 'Gilbert Maldonado', 'Gordon Campbell', 'John Swasey', '', 0),
(197, '2016LeaguePlayoffs', 101, '2016-02-27', 'Blue 1', '14:00:00', 'U12B', NULL, 'H2', '', 'B1', '', 'Area 1D', 'Region 21', 'Region 21', 'Region 21', '', 0),
(198, '2016LeaguePlayoffs', 102, '2016-02-27', 'Blue 2', '14:00:00', 'U12B', NULL, 'N2', '', 'G1', '', 'Area 1C', 'Joe Small', 'Bill Ketel', 'Steve D\'Amico', '', 0),
(199, '2016LeaguePlayoffs', 103, '2016-02-27', 'Blue 3', '14:00:00', 'U12B', NULL, 'N1', '', 'U2', '', 'Area 1G', 'Rafael Arellano ', 'Mark Correa', 'Nick farmer', '', 0),
(200, '2016LeaguePlayoffs', 104, '2016-02-27', 'Blue 4', '14:00:00', 'U12B', NULL, 'R1', '', 'H1', '', 'Area 1B', 'Resendez', 'Newman', 'Newman', '', 0),
(201, '2016LeaguePlayoffs', 105, '2016-02-27', 'Green 1', '14:00:00', 'U10B', NULL, 'B1', '', 'H2', '', 'Area 1G', 'Pablo Mejia', 'Juan Barragan', 'Helge Lubmann', '', 0),
(202, '2016LeaguePlayoffs', 106, '2016-02-27', 'Green 2', '14:00:00', 'U10B', NULL, 'N2', '', 'R1', '', 'Area 1C', 'Craig Dunkin', 'Tom Regan', 'Brian Bonham', '', 0),
(203, '2016LeaguePlayoffs', 107, '2016-02-27', 'Green 3', '14:00:00', 'U10B', NULL, 'N1', '', 'C1', '', 'Area 1H', 'Greg Jackson', 'Orlando Lomeli', 'Anthony Padilla', '', 0),
(204, '2016LeaguePlayoffs', 108, '2016-02-27', 'Yellow 1', '14:00:00', 'U10B', NULL, 'U1', '', 'G2', '', 'Area 1R', 'Greg Hood', 'Ruben Nieto', 'Fernando Rangel', '', 0),
(205, '2016LeaguePlayoffs', 109, '2016-02-27', 'White 1', '15:00:00', 'U14G', NULL, 'U1', '', 'B1', '', 'Area 1P', 'ANDREWS', 'WADE', '', '', 0),
(206, '2016LeaguePlayoffs', 110, '2016-02-27', 'White 2', '15:00:00', 'U14G', NULL, 'C1', '', 'H2', '', 'Area 1F', 'Chris Levy (G)', 'Bill Manes', '', '', 0),
(207, '2016LeaguePlayoffs', 111, '2016-02-27', 'White 3', '15:00:00', 'U14G', NULL, 'B2', '', 'N1', '', 'Area 1R', 'Jeff Johnson', 'Rick Polmonter', 'Lee Lombard', '', 0),
(208, '2016LeaguePlayoffs', 112, '2016-02-27', 'White 4', '15:00:00', 'U14G', NULL, 'G1', '', 'H1', '', 'Area 1N', 'Gordon Campbell', 'John Swasey', 'Gilbert Maldonado', '', 0),
(209, '2016LeaguePlayoffs', 113, '2016-02-27', 'Murphy 1', '15:00:00', 'U14B', NULL, 'U1', '', 'B2', '', 'Area 1D', 'Eric Knudson', 'Krista Skinner', 'Harry Andreas', '', 0),
(210, '2016LeaguePlayoffs', 114, '2016-02-27', 'Murphy 2', '15:00:00', 'U14B', NULL, 'P1', '', 'F1', '', 'Area 1B', 'Widner', 'Evans', 'Meza', '', 0),
(211, '2016LeaguePlayoffs', 115, '2016-02-27', 'Blue 1', '15:00:00', 'U12G', NULL, 'G1', '', 'C2', '', 'Area 1D', 'Region 21', 'Region 21', 'Region 21', '', 0),
(212, '2016LeaguePlayoffs', 116, '2016-02-27', 'Blue 2', '15:00:00', 'U12G', NULL, 'U1', '', 'B1', '', 'Area 1C', 'Steve D\'Amico', 'Joe Small', 'Bill Ketel', '', 0),
(213, '2016LeaguePlayoffs', 117, '2016-02-27', 'Blue 3', '15:00:00', 'U12G', NULL, 'P1', '', 'H2', '', 'Area 1G', 'Mark Correa', 'Nick farmer', 'Rafael Arellano', '', 0),
(214, '2016LeaguePlayoffs', 118, '2016-02-27', 'Blue 4', '15:00:00', 'U12G', NULL, 'G2', '', 'R1', '', 'Area 1B', 'Resendez', 'Newman', 'Newman', '', 0),
(215, '2016LeaguePlayoffs', 119, '2016-02-27', 'Blue 5', '15:00:00', 'U12B', NULL, 'P1', '', 'F1', '', 'Area 1B', 'Mark Goodfellow', 'Terry Wolff', 'Jennifer Nesslar', '', 0),
(216, '2016LeaguePlayoffs', 120, '2016-02-27', 'Blue 6', '15:00:00', 'U12B', NULL, 'B2', '', 'C1', '', 'Area 1P', 'GATES', 'KOZINSKI', 'SIMMONS', '', 0),
(217, '2016LeaguePlayoffs', 121, '2016-02-27', 'Green 1', '15:00:00', 'U10G', NULL, 'C1', '', 'U1', '', 'Area 1G', 'Jon Harris', 'Don Nelson ', 'Todd Gallemore', '', 0),
(218, '2016LeaguePlayoffs', 122, '2016-02-27', 'Green 2', '15:00:00', 'U10G', NULL, 'B1', '', 'G2', '', 'Area 1C', 'Stephanie Dote', 'Craig Dunkin', 'Tom Regan', '', 0),
(219, '2016LeaguePlayoffs', 123, '2016-02-27', 'Green 3', '15:00:00', 'U10G', NULL, 'G1', '', 'R2', '', 'Area 1H', 'Anthony Padilla', 'Orlando Lomeli', 'Greg Jackson', '', 0),
(220, '2016LeaguePlayoffs', 124, '2016-02-27', 'Yellow 1', '15:00:00', 'U10G', NULL, 'H1', '', 'N1', '', 'Area 1R', 'Ruben Nieto', 'Greg Hood', 'Fernando Rangel', '', 0),
(221, '2016LeaguePlayoffs', 125, '2016-02-27', 'Yellow 2', '15:00:00', 'U10B', NULL, 'P2', '', 'D1', '', 'Area 1U', 'Mars Ramage', 'Todd Flink', 'Chris Garcia', '', 0),
(222, '2016LeaguePlayoffs', 126, '2016-02-27', 'Yellow 3', '15:00:00', 'U10B', NULL, 'G1', '', 'F1', '', 'Area 1H', 'Angel Gonzalez', 'Greg Eastman', 'Mark Nicacio', '', 0),
(223, '2016LeaguePlayoffs', 127, '2016-02-27', 'White 1', '16:00:00', 'U14B', NULL, 'C1', '', 'D1', '', 'Area 1P', 'Bill Manes', '', '', '', 0),
(224, '2016LeaguePlayoffs', 128, '2016-02-27', 'White 2', '16:00:00', 'U14B', NULL, 'H1', '', 'P2', '', 'Area 1F', 'Joe Bernier. ', 'Chris Levy (G)', 'Moe Alami', '', 0),
(225, '2016LeaguePlayoffs', 129, '2016-02-27', 'White 3', '16:00:00', 'U14G', NULL, 'F2', '', 'G2', '', 'Area 1R', 'Ed Williams', 'David Schlesinger', 'Lee Lombard', '', 0),
(226, '2016LeaguePlayoffs', 130, '2016-02-27', 'White 4', '16:00:00', 'U14G', NULL, 'D1', '', 'P1', '', 'Area 1N', 'Gilbert Malsonado', 'John Swasey', 'Gordon Campbell', '', 0),
(227, '2016LeaguePlayoffs', 131, '2016-02-27', 'Murphy 1', '16:00:00', 'U14G', NULL, 'F1', '', 'R1', '', 'Area 1D', 'Krista Skinner', 'Harry Andreas', 'Eric Knudson', '', 0),
(228, '2016LeaguePlayoffs', 132, '2016-02-27', 'Murphy 2', '16:00:00', 'U14G', NULL, 'N2', '', 'D2', '', 'Area 1B', 'Meza', 'Evans', 'Widner', '', 0),
(229, '2016LeaguePlayoffs', 133, '2016-02-27', 'Blue 1', '16:00:00', 'U12B', NULL, 'R2', '', 'P2', '', 'Area 1D', 'Region 21', 'Region 21', 'Region 21', '', 0),
(230, '2016LeaguePlayoffs', 134, '2016-02-27', 'Blue 2', '16:00:00', 'U12B', NULL, 'U1', '', 'D1', '', 'Area 1C', 'Bill Ketel', 'Steve D\'Amico', 'Joe Small', '', 0),
(231, '2016LeaguePlayoffs', 135, '2016-02-27', 'Blue 3', '16:00:00', 'U12G', NULL, 'D1', '', 'F1', '', 'Area 1G', 'Kyle Watson', 'Todd Gallemore', 'Ralph Gaona', '', 0),
(232, '2016LeaguePlayoffs', 136, '2016-02-27', 'Blue 4', '16:00:00', 'U12G', NULL, 'R2', '', 'C1', '', 'Area 1B', 'Resendez', 'Newman', 'Newman', '', 0),
(233, '2016LeaguePlayoffs', 137, '2016-02-27', 'Blue 5', '16:00:00', 'U12G', NULL, 'D2', '', 'H1', '', 'Area 1B', 'Jennifer Nesslar', 'Mark Goodfellow', 'Terry Wolff', '', 0),
(234, '2016LeaguePlayoffs', 138, '2016-02-27', 'Blue 6', '16:00:00', 'U12G', NULL, 'F2', '', 'N1', '', 'Area 1P', '', '', '', '', 0),
(235, '2016LeaguePlayoffs', 139, '2016-02-27', 'Green 1', '16:00:00', 'U10B', NULL, 'H1', '', 'P1', '', 'Area 1G', 'Mark Correa', 'Jon Harris', 'Don Nelson', '', 0),
(236, '2016LeaguePlayoffs', 140, '2016-02-27', 'Green 2', '16:00:00', 'U10B', NULL, 'B2', '', 'F2', '', 'Area 1C', 'Tom Regan', 'Al Prado', 'Craig Dunkin', '', 0),
(237, '2016LeaguePlayoffs', 141, '2016-02-27', 'Green 3', '16:00:00', 'U10G', NULL, 'F2', '', 'P1', '', 'Area 1H', 'Greg Jackson', 'Orlando Lomeli', 'Anthony Padilla', '', 0),
(238, '2016LeaguePlayoffs', 142, '2016-02-27', 'Yellow 1', '16:00:00', 'U10G', NULL, 'D2', '', 'R1', '', 'Area 1U', 'Todd Flink', 'Mars Ramage', 'Chris Garcia', '', 0),
(239, '2016LeaguePlayoffs', 143, '2016-02-27', 'Yellow 2', '16:00:00', 'U10G', NULL, 'C2', '', 'F1', '', 'Area 1R', 'Greg Hood', 'Ruben Nieto', 'Fernando Rangel', '', 0),
(240, '2016LeaguePlayoffs', 144, '2016-02-27', 'Yellow 3', '16:00:00', 'U10G', NULL, 'U2', '', 'D1', '', 'Area 1H', 'Greg Eastman', 'Angel Gonzalez', 'Mark Nicacio', '', 0),
(241, '2016WSC', 1, '2016-03-19', 'Field 1', '09:00:00', 'U14G', NULL, '2', '', '10/O/1429 Cutler/Orosi', '', 'Section 11', '', '', '', '', 0),
(242, '2016WSC', 2, '2016-03-19', 'Field 1', '11:00:00', 'U14B', NULL, '10/O/1429 Cutler/Orosi', '', '1/C/908 El Monte', '', 'Section 11', '', '', '', '', 0),
(243, '2016WSC', 3, '2016-03-19', 'Field 1', '13:00:00', 'U14G CHAMPIONSHIP', NULL, 'Winner Field 1 @ 9', '', 'Winner Field 2 @ 9', '', 'Area 1B', '', '', '', '', 0),
(244, '2016WSC', 4, '2016-03-19', 'Field 1', '15:00:00', 'U14B CHAMPIONSHIP', NULL, 'Winner Field 1 @ 11', '', 'Winner Field 2 @ 11', '', 'Area 1B', '', '', '', '', 0),
(245, '2016WSC', 5, '2016-03-19', 'Field 2', '09:00:00', 'U14G', NULL, '1/P/1031 So. LA/ Ladera Heights', '', '11/L/86 Laguna Beach', '', 'Area 1B', '', '', '', '', 0),
(246, '2016WSC', 6, '2016-03-19', 'Field 2', '11:00:00', 'U14B', NULL, '11/Z/652 South Gate', '', '1WC/B/31 Diamond Bar', '', 'Area 1B', '', '', '', '', 0),
(247, '2016WSC', 7, '2016-03-19', 'Field 2', '13:00:00', 'U14G CONSOLATION', NULL, 'Loser Field 1 @ 9', '', 'Loser Field 2 @ 9', '', 'Area 1B', '', '', '', '', 0),
(248, '2016WSC', 8, '2016-03-19', 'Field 2', '15:00:00', 'U14B CONSOLATION', NULL, 'Loser Field 1 @ 11', '', 'Loser Field 2 @ 11', '', 'Area 1B', '', '', '', '', 0),
(249, '2016WSC', 9, '2016-03-19', 'Field 5', '09:00:00', 'U12G', NULL, '11/Z/1065 Montebello', '', '2', '', 'Section One', '', '', '', '', 0),
(250, '2016WSC', 10, '2016-03-19', 'Field 5', '11:00:00', 'U12B', NULL, '11/Z/1347 East Los Angeles', '', '2', '', 'Section One', '', '', '', '', 0),
(251, '2016WSC', 11, '2016-03-19', 'Field 5', '13:00:00', 'U12G CHAMPIONSHIP', NULL, 'Winner Field 5 @ 9', '', 'Winner Field 6 @ 9', '', 'Area 1B', '', '', '', '', 0),
(252, '2016WSC', 12, '2016-03-19', 'Field 5', '15:00:00', 'U12B  CHAMPIONSHIP', NULL, 'Winner Field 5 @ 11', '', 'Winner Field 6 @ 11', '', 'Area 1B', '', '', '', '', 0),
(253, '2016WSC', 13, '2016-03-19', 'Field 6', '09:00:00', 'U12G', NULL, '1/D/18 Manhattan/Hermosa Beach', '', '10/A/359 Bakers Field', '', 'Section 11', '', '', '', '', 0),
(254, '2016WSC', 14, '2016-03-19', 'Field 6', '11:00:00', 'U12B', NULL, '10/Q/741 Paso Robles', '', '1/P/1595 Watts', '', 'Section 11', '', '', '', '', 0),
(255, '2016WSC', 15, '2016-03-19', 'Field 6', '13:00:00', 'U12G CONSOLATION', NULL, 'Loser Field 5 @ 9', '', 'Loser Field 6 @ 9', '', 'Area 1B', '', '', '', '', 0),
(256, '2016WSC', 16, '2016-03-19', 'Field 6', '15:00:00', 'U12B CONSOLATION', NULL, 'Loser Field 5 @ 11', '', 'Loser Field 6 @ 11', '', 'Area 1B', '', '', '', '', 0),
(257, '2016WSC', 17, '2016-03-19', 'Field 9', '09:00:00', 'U10G', NULL, '11/Z/24 Downey', '', '10/W/122 Santa Barbara', '', 'Section One', '', '', '', '', 0),
(258, '2016WSC', 18, '2016-03-19', 'Field 9', '11:00:00', 'U10B', NULL, '11/K/143 West Huntington Beach', '', '10/Q/180 Santa Ynez Valley', '', 'Section One', '', '', '', '', 0),
(259, '2016WSC', 19, '2016-03-19', 'Field 9', '13:00:00', 'U10G CHAMPIONSHIP', NULL, 'Winner Field 9 @ 9', '', 'Winner Field 10 @ 9', '', 'Area 1B', '', '', '', '', 0),
(260, '2016WSC', 20, '2016-03-19', 'Field 9', '15:00:00', 'U10B CHAMPIONSHIP', NULL, 'Winner Field 9 @ 11', '', 'Winner Field 10 @ 11', '', 'Area 1B', '', '', '', '', 0),
(261, '2016WSC', 21, '2016-03-19', 'Field 10', '09:00:00', 'U10G', NULL, '2', '', '1/D/21 Hawthorne', '', 'Section 11', '', '', '', '', 0),
(262, '2016WSC', 22, '2016-03-19', 'Field 10', '11:00:00', 'U10B', NULL, '10WC/D/665 Victorville', '', '1/P/1595 Watts', '', 'Section 11', '', '', '', '', 0),
(263, '2016WSC', 23, '2016-03-19', 'Field 10', '13:00:00', 'U10G CONSOLATION', NULL, 'Loser Field 9 @ 9', '', 'Loser Field 10 @ 9', '', 'Area 1B', '', '', '', '', 0),
(264, '2016WSC', 24, '2016-03-19', 'Field 10', '15:00:00', 'U10B CONSOLATION', NULL, 'Loser Field 9 @ 11', '', 'Loser Field 10 @ 11', '', 'Area 1B', '', '', '', '', 0),
(265, '2016WSC', 25, '2016-03-19', 'Field 11', '09:00:00', 'U09B Extra', NULL, '2', '', '1/D/7 Westchester', '', 'Area 1B', '', '', '', '', 0),
(266, '2016WSC', 26, '2016-03-19', 'Field 11', '11:00:00', 'U10B Extra', NULL, '1/F/6 San Pedro', '', '11WC/L/84 Mission Viejo', '', 'Section 10', '', '', '', '', 0),
(267, '2016WSC', 27, '2016-03-19', 'Field 11', '13:00:00', 'U09B Extra CHAMPIONSHIP', NULL, 'Winner Field 12 @ 9', '', 'Winner Field 11 @ 9', '', 'Area 1B', '', '', '', '', 0),
(268, '2016WSC', 28, '2016-03-19', 'Field 11', '15:00:00', 'U10B Extra CHAMPIONSHIP', NULL, 'Winner Field 12 @ 11', '', 'Winner Field 11 @ 11', '', 'Area 1B', '', '', '', '', 0),
(269, '2016WSC', 29, '2016-03-19', 'Field 12', '09:00:00', 'U09B Extra', NULL, '10/E/9 Thousand Oaks', '', '11/Z/652 South Gate', '', 'Section One', '', '', '', '', 0),
(270, '2016WSC', 30, '2016-03-19', 'Field 12', '11:00:00', 'U10B Extra', NULL, '10/E/4 Agoura/Westlake/Oak Park', '', '11/K/117 Central Huntington Beach', '', 'Section One', '', '', '', '', 0),
(271, '2016WSC', 31, '2016-03-19', 'Field 12', '13:00:00', 'U09B Extra CONSOLATION', NULL, 'Loser Field 12 @ 9', '', 'Loser Field 11 @ 9', '', 'Area 1B', '', '', '', '', 0),
(272, '2016WSC', 32, '2016-03-19', 'Field 12', '15:00:00', 'U10B Extra CONSOLATION', NULL, 'Loser Field 12 @ 11', '', 'Loser Field 11 @ 11', '', 'Area 1B', '', '', '', '', 0),
(273, '2016WSC', 33, '2016-03-19', 'Field 7', '09:00:00', 'U11B Extra', NULL, '1WC/F/14 West Torrance', '', '11/L/85 Lake Forest', '', 'Section 10', '', '', '', '', 0),
(274, '2016WSC', 34, '2016-03-19', 'Field 7', '11:00:00', 'U12B Extra', NULL, '11/Q/1398 Yorba Linda', '', '1/P/19 Culver City', '', 'Section 10', '', '', '', '', 0),
(275, '2016WSC', 35, '2016-03-19', 'Field 7', '13:00:00', 'U11B Extra CHAMPIONSHIP', NULL, 'Winner Field 7 @ 9', '', 'Winner Field 8 @ 9', '', 'Area 1B', '', '', '', '', 0),
(276, '2016WSC', 36, '2016-03-19', 'Field 7', '15:00:00', 'U12B Extra CHAMPIONSHIP', NULL, 'Winner Field 7 @ 11', '', 'Winner Field 8 @ 11', '', 'Area 1B', '', '', '', '', 0),
(277, '2016WSC', 37, '2016-03-19', 'Field 8', '09:00:00', 'U11B Extra', NULL, '10/E/4 Agoura/Westlake/Oak Park', '', '1/D/34 So. Redondo Beach', '', 'Section 11', '', '', '', '', 0),
(278, '2016WSC', 38, '2016-03-19', 'Field 8', '11:00:00', 'U12B Extra', NULL, '10/W/68 Camarillo', '', '2', '', 'Section 11', '', '', '', '', 0),
(279, '2016WSC', 39, '2016-03-19', 'Field 8', '13:00:00', 'U11B Extra CONSOLATION', NULL, 'Loser Field 7 @ 9', '', 'Loser Field 8 @ 9', '', 'Area 1B', '', '', '', '', 0),
(280, '2016WSC', 40, '2016-03-19', 'Field 8', '15:00:00', 'U12B Extra CONSOLATION', NULL, 'Loser Field 7 @ 11', '', 'Loser Field 8 @ 11', '', 'Area 1B', '', '', '', '', 0),
(281, '2016WSC', 41, '2016-03-19', 'Field 3', '09:00:00', 'U13B Extra', NULL, '11/Z/603 Pico Rivera', '', '10/W/39 Ventura', '', 'Section 2', '', '', '', '', 0),
(282, '2016WSC', 42, '2016-03-19', 'Field 3', '11:00:00', 'U14B Extra', NULL, '10/V/71 Woodland Hills', '', '1/B/67 Chino', '', 'Section 2', '', '', '', '', 0),
(283, '2016WSC', 43, '2016-03-19', 'Field 3', '13:00:00', 'U13B Extra CHAMPIONSHIP', NULL, 'Winner Field 3 @ 9', '', 'Winner Field 4 @ 9', '', 'Area 1B', '', '', '', '', 0),
(284, '2016WSC', 44, '2016-03-19', 'Field 3', '15:00:00', 'U14B Extra CHAMPIONSHIP', NULL, 'Winner Field 3 @ 11', '', 'Winner Field 4 @ 11', '', 'Area 1B', '', '', '', '', 0),
(285, '2016WSC', 45, '2016-03-19', 'Field 4', '09:00:00', 'U13B Extra', NULL, '1/F/16 North Torrance', '', '11WC/Q/57 Corona del Mar', '', 'Section 10', '', '', '', '', 0),
(286, '2016WSC', 46, '2016-03-19', 'Field 4', '11:00:00', 'U14B Extra', NULL, '1WC/F/15 Central Torrance', '', '11/Q/120 Costa Mesa', '', 'Section 10', '', '', '', '', 0),
(287, '2016WSC', 47, '2016-03-19', 'Field 4', '13:00:00', 'U13B Extra CONSOLATION', NULL, 'Loser Field 3 @ 9', '', 'Loser Field 4 @ 9', '', 'Area 1B', '', '', '', '', 0),
(288, '2016WSC', 48, '2016-03-19', 'Field 4', '15:00:00', 'U14B Extra CONSOLATION', NULL, 'Loser Field 3 @ 11', '', 'Loser Field 4 @ 11', '', 'Area 1B', '', '', '', '', 0),
(289, '2016WSC', 49, '2016-03-20', 'Field 1', '09:00:00', 'U14G', NULL, '11/E/94 La Habra', '', '2', '', 'Section One', '', '', '', '', 0),
(290, '2016WSC', 50, '2016-03-20', 'Field 1', '11:00:00', 'U14B', NULL, '1/C/2 Arcadia', '', '11/R/785 North Park', '', 'Section 10', '', '', '', '', 0),
(291, '2016WSC', 51, '2016-03-20', 'Field 1', '13:00:00', 'U14G CHAMPIONSHIP', NULL, 'Winner Field 1 @ 9', '', 'Winner Field 2 @ 9', '', 'Area 1B', '', '', '', '', 0),
(292, '2016WSC', 52, '2016-03-20', 'Field 1', '15:00:00', 'U14B CHAMPIONSHIP', NULL, 'Winner Field 1 @ 11', '', 'Winner Field 2 @ 11', '', 'Area 1B', '', '', '', '', 0),
(293, '2016WSC', 53, '2016-03-20', 'Field 2', '09:00:00', 'U14G', NULL, '1/D/18 Manhattan/Hermosa Beach', '', '10/O/1429 Cutler/Orosi', '', 'Section 11', '', '', '', '', 0),
(294, '2016WSC', 54, '2016-03-20', 'Field 2', '11:00:00', 'U14B', NULL, '11/Z/652 South Gate', '', '10/D/665 Victorville', '', 'Section One', '', '', '', '', 0),
(295, '2016WSC', 55, '2016-03-20', 'Field 2', '13:00:00', 'U14G CONSOLATION', NULL, 'Loser Field 1 @ 9', '', 'Loser Field 2 @ 9', '', 'Area 1B', '', '', '', '', 0),
(296, '2016WSC', 56, '2016-03-20', 'Field 2', '15:00:00', 'U14B CONSOLATION', NULL, 'Loser Field 1 @ 11', '', 'Loser Field 2 @ 11', '', 'Area 1B', '', '', '', '', 0),
(297, '2016WSC', 57, '2016-03-20', 'Field 5', '09:00:00', 'U12G', NULL, '11/K/56 South Huntington Beach', '', '10/D/638 Quartz Hill', '', 'Section One', '', '', '', '', 0),
(298, '2016WSC', 58, '2016-03-20', 'Field 5', '11:00:00', 'U12B', NULL, '2', '', '1/D/18 Manhattan/Hermosa Beach', '', 'Section 11', '', '', '', '', 0),
(299, '2016WSC', 59, '2016-03-20', 'Field 5', '13:00:00', 'U12G CHAMPIONSHIP', NULL, 'Winner Field 5 @ 9', '', 'Winner Field 6 @ 9', '', 'Area 1B', '', '', '', '', 0),
(300, '2016WSC', 60, '2016-03-20', 'Field 5', '15:00:00', 'U12B CHAMPIONSHIP', NULL, 'Winner Field 5 @ 11', '', 'Winner Field 6 @ 11', '', 'Area 1B', '', '', '', '', 0),
(301, '2016WSC', 61, '2016-03-20', 'Field 6', '09:00:00', 'U12G', NULL, '1/D/18 Manhattan/Hermosa Beach', '', '11/R/785 North Park', '', 'Section 2', '', '', '', '', 0);
INSERT INTO `rs_games` (`id`, `projectKey`, `game_number`, `date`, `field`, `time`, `division`, `pool`, `home`, `home_team`, `away`, `away_team`, `assignor`, `cr`, `ar1`, `ar2`, `r4th`, `medalRound`) VALUES
(302, '2016WSC', 62, '2016-03-20', 'Field 6', '11:00:00', 'U12B', NULL, '10/O/129 Visalia', '', '11/R/785 North Park', '', 'Section 2', '', '', '', '', 0),
(303, '2016WSC', 63, '2016-03-20', 'Field 6', '13:00:00', 'U12G CONSOLATION', NULL, 'Loser Field 5 @ 9', '', 'Loser Field 6 @ 9', '', 'Area 1B', '', '', '', '', 0),
(304, '2016WSC', 64, '2016-03-20', 'Field 6', '15:00:00', 'U12B CONSOLATION', NULL, 'Loser Field 5 @ 11', '', 'Loser Field 6 @ 11', '', 'Area 1B', '', '', '', '', 0),
(305, '2016WSC', 65, '2016-03-20', 'Field 9', '09:00:00', 'U10G', NULL, '11/Z/24 Downey', '', '10/Q/741 Paso Robles', '', 'Section One', '', '', '', '', 0),
(306, '2016WSC', 66, '2016-03-20', 'Field 9', '11:00:00', 'U10B', NULL, '10/Q/83 Five Cities', '', '1/C/40 San Gabriel/Rosemead', '', 'Section 11', '', '', '', '', 0),
(307, '2016WSC', 67, '2016-03-20', 'Field 9', '13:00:00', 'U10G CHAMPIONSHIP', NULL, 'Winner Field 9 @ 9', '', 'Winner Field 10 @ 9', '', 'Area 1B', '', '', '', '', 0),
(308, '2016WSC', 68, '2016-03-20', 'Field 9', '15:00:00', 'U10B CHAMPIONSHIP', NULL, 'Winner Field 9 @ 11', '', 'Winner Field 10 @ 11', '', 'Area 1B', '', '', '', '', 0),
(309, '2016WSC', 69, '2016-03-20', 'Field 10', '09:00:00', 'U10G', NULL, '1/S/808 Pahrump', '', '2', '', 'Section 11', '', '', '', '', 0),
(310, '2016WSC', 70, '2016-03-20', 'Field 10', '11:00:00', 'U10B', NULL, '11/Z/652 South Gate', '', '1WC/P/1595 Watts', '', 'Section 10', '', '', '', '', 0),
(311, '2016WSC', 71, '2016-03-20', 'Field 10', '13:00:00', 'U10G CONSOLATION', NULL, 'Loser Field 9 @ 9', '', 'Loser Field 10 @ 9', '', 'Area 1B', '', '', '', '', 0),
(312, '2016WSC', 72, '2016-03-20', 'Field 10', '15:00:00', 'U10B CONSOLATION', NULL, 'Loser Field 9 @ 11', '', 'Loser Field 10 @ 11', '', 'Area 1B', '', '', '', '', 0),
(313, '2016WSC', 73, '2016-03-20', 'Field 11', '09:00:00', 'U09G EXTRA', NULL, '10/S/678 Valencia', '', '1WC/F/16 North Torrance', '', 'Section 11', '', '', '', '', 0),
(314, '2016WSC', 74, '2016-03-20', 'Field 11', '11:00:00', 'U10G EXTRA', NULL, '1/F/16 North Torrance', '', '10/W/68 Camarillo', '', 'Section 11', '', '', '', '', 0),
(315, '2016WSC', 75, '2016-03-20', 'Field 11', '13:00:00', 'U09G Extra CHAMPIONSHIP', NULL, 'Winner Field 12 @ 9', '', 'Winner Field 11 @ 9', '', 'Area 1B', '', '', '', '', 0),
(316, '2016WSC', 76, '2016-03-20', 'Field 11', '15:00:00', 'U10G Extra CHAMPIONSHIP', NULL, 'Winner Field 12 @ 11', '', 'Winner Field 11 @ 11', '', 'Area 1B', '', '', '', '', 0),
(317, '2016WSC', 77, '2016-03-20', 'Field 12', '09:00:00', 'U09G EXTRA', NULL, '1/F/10 Palos Verdes', '', '11/L/87 San Juan Capistrano', '', 'Section 10', '', '', '', '', 0),
(318, '2016WSC', 78, '2016-03-20', 'Field 12', '11:00:00', 'U10G EXTRA', NULL, '10WC/E/4 Agoura/Westlake/Oak Park', '', '11/E/159 Los Alamitos', '', 'Section One', '', '', '', '', 0),
(319, '2016WSC', 79, '2016-03-20', 'Field 12', '13:00:00', 'U09G Extra CONSOLATION', NULL, 'Loser Field 12 @ 9', '', 'Loser Field 11 @ 9', '', 'Area 1B', '', '', '', '', 0),
(320, '2016WSC', 80, '2016-03-20', 'Field 12', '15:00:00', 'U10G Extra CONSOLATION', NULL, 'Loser Field 12 @ 11', '', 'Loser Field 11 @ 11', '', 'Area 1B', '', '', '', '', 0),
(321, '2016WSC', 81, '2016-03-20', 'Field 7', '09:00:00', 'U11G EXTRA', NULL, '11/Q/120 Costa Mesa', '', '10/E/363 Moorpark', '', 'Section One', '', '', '', '', 0),
(322, '2016WSC', 82, '2016-03-20', 'Field 7', '11:00:00', 'U12G EXTRA', NULL, '10/D/665 Victorville', '', '11/E/154 Cypress', '', 'Section 10', '', '', '', '', 0),
(323, '2016WSC', 83, '2016-03-20', 'Field 7', '13:00:00', 'U11G Extra CHAMPIONSHIP', NULL, 'Winner Field 7 @ 9', '', 'Winner Field 8 @ 9', '', 'Area 1B', '', '', '', '', 0),
(324, '2016WSC', 84, '2016-03-20', 'Field 7', '15:00:00', 'U12G Extra CHAMPIONSHIP', NULL, 'Winner Field 7 @ 11', '', 'Winner Field 8 @ 11', '', 'Area 1B', '', '', '', '', 0),
(325, '2016WSC', 85, '2016-03-20', 'Field 8', '09:00:00', 'U11G EXTRA', NULL, '11WC/L/84 Mission Viejo', '', '1/F/16 North Torrance', '', 'Section 10', '', '', '', '', 0),
(326, '2016WSC', 86, '2016-03-20', 'Field 8', '11:00:00', 'U12G EXTRA', NULL, '100/E/9 Thousand Oaks', '', '1WC/F/14 West Torrance', '', 'Section 11', '', '', '', '', 0),
(327, '2016WSC', 87, '2016-03-20', 'Field 8', '13:00:00', 'U11G Extra CONSOLATION', NULL, 'Loser Field 7 @ 9', '', 'Loser Field 8 @ 9', '', 'Area 1B', '', '', '', '', 0),
(328, '2016WSC', 88, '2016-03-20', 'Field 8', '15:00:00', 'U12G Extra CONSOLATION', NULL, 'Loser Field 7 @ 11', '', 'Loser Field 8 @ 11', '', 'Area 1B', '', '', '', '', 0),
(329, '2016WSC', 89, '2016-03-20', 'Field 3', '09:00:00', 'U13G EXTRA', NULL, '11/K/55 North Huntington Beach', '', '1WC/U/112 La Verne/San Dimas', '', 'Section One', '', '', '', '', 0),
(330, '2016WSC', 90, '2016-03-20', 'Field 3', '11:00:00', 'U14G EXTRA', NULL, '2', '', '10/V/71 Woodland Hills', '', 'Section One', '', '', '', '', 0),
(331, '2016WSC', 91, '2016-03-20', 'Field 3', '13:00:00', 'U13G Extra CHAMPIONSHIP', NULL, 'Winner Field 3 @ 9', '', 'Winner Field 4 @ 9', '', 'Area 1B', '', '', '', '', 0),
(332, '2016WSC', 92, '2016-03-20', 'Field 3', '15:00:00', 'U14G Extra CHAMPIONSHIP', NULL, 'Winner Field 3 @ 11', '', 'Winner Field 4 @ 11', '', 'Area 1B', '', '', '', '', 0),
(333, '2016WSC', 93, '2016-03-20', 'Field 4', '09:00:00', 'U13G EXTRA', NULL, '10/V/254 Burbank', '', '1/P/20 Santa Monica', '', 'Section 11', '', '', '', '', 0),
(334, '2016WSC', 94, '2016-03-20', 'Field 4', '11:00:00', 'U14G EXTRA', NULL, '11/Z/114 Long Beach', '', '1/D/34 South Redondo Beach', '', 'Section 11', '', '', '', '', 0),
(335, '2016WSC', 95, '2016-03-20', 'Field 4', '13:00:00', 'U13G Extra CONSOLATION', NULL, 'Loser Field 3 @ 9', '', 'Loser Field 4 @ 9', '', 'Area 1B', '', '', '', '', 0),
(336, '2016WSC', 96, '2016-03-20', 'Field 4', '15:00:00', 'U14G Extra CONSOLATION', NULL, 'Loser Field 3 @ 11', '', 'Loser Field 4 @ 11', '', 'Area 1B', '', '', '', '', 0),
(337, '2015U16U19Chino', 1, '2015-11-21', 'Ayala 7', '08:00:00', 'U19G', NULL, 'H1', '', 'R1', '', 'Area 1G', 'Mitch Graham', 'Nick Ruffly', 'Ku\'ulei Reyes', '', 0),
(338, '2015U16U19Chino', 2, '2015-11-21', 'Ayala 8', '08:00:00', 'U19G', NULL, 'G1', '', 'U1', '', 'Area 1P', 'Michael Feder', 'Rick Ramirez', 'Robert Flutie', '', 0),
(339, '2015U16U19Chino', 3, '2015-11-21', 'Ayala 9', '08:00:00', 'U19G', NULL, 'G2', '', 'C1', '', 'Area 1R', 'Ed Williams', 'Josh Chavez', 'Victor Valverde', '', 0),
(340, '2015U16U19Chino', 4, '2015-11-21', 'Ayala 10', '08:00:00', 'U19G', NULL, 'B1', '', 'N1', '', 'Area 1C', 'Dave Alvarez', 'Adrian Backer', 'Joe Small', '', 0),
(341, '2015U16U19Chino', 5, '2015-11-21', 'Ayala 11', '08:00:00', 'U16G', NULL, 'C2', '', 'U1', '', 'Area 1N', 'Chris Lingenfelter', 'Adam Arentz', 'Rocky Rackow', '', 0),
(342, '2015U16U19Chino', 6, '2015-11-21', 'Ayala 12', '08:00:00', 'U16G', NULL, 'B1', '', 'H1', '', 'Area 1F', 'Gary Ramaley', 'Gregg Ferguson', 'Alan Siegel', '', 0),
(343, '2015U16U19Chino', 7, '2015-11-21', 'Ayala 13', '08:00:00', 'U16G', NULL, 'N1', '', 'G1', '', 'Area 1U', 'Steve Manookian', 'Adam Phipps', 'Mars Ramage', '', 0),
(344, '2015U16U19Chino', 8, '2015-11-21', 'Ayala 14', '08:00:00', 'U16G', NULL, 'C1', '', 'R1', '', 'Area 1H', 'Chris Salmon', 'Manuel Del Rio', 'Patrick Alles', '', 0),
(345, '2015U16U19Chino', 9, '2015-11-21', 'Ayala 7', '09:20:00', 'U19B', NULL, 'P1', '', 'B1', '', 'Area 1G', 'Nick Ruffly', 'Nick Gonzalez', 'Fernando Cobos', '', 0),
(346, '2015U16U19Chino', 10, '2015-11-21', 'Ayala 8', '09:20:00', 'U19B', NULL, 'R1', '', 'F1', '', 'Area 1P', 'Ramirez', 'Flutie', 'Kean', '', 0),
(347, '2015U16U19Chino', 11, '2015-11-21', 'Ayala 9', '09:20:00', 'U19B', NULL, 'P2', '', 'C1', '', 'Area 1R', 'Josh Chavez', 'Ed Williams', 'Victor Valverde', '', 0),
(348, '2015U16U19Chino', 12, '2015-11-21', 'Ayala 10', '09:20:00', 'U19B', NULL, 'U1', '', 'H1', '', 'Area 1C', 'Al Prado', 'David Alvarez', 'Adrian Backer', '', 0),
(349, '2015U16U19Chino', 13, '2015-11-21', 'Ayala 11', '09:20:00', 'U16B', NULL, 'F2', '', 'U1', '', 'Area 1N', 'Chris Lingenfelter', 'Adam Arentz', 'Rocky Rackow', '', 0),
(350, '2015U16U19Chino', 14, '2015-11-21', 'Ayala 12', '09:20:00', 'U16B', NULL, 'B2', '', 'P1', '', 'Area 1F', 'Gary', 'Gregg', 'Alan', '', 0),
(351, '2015U16U19Chino', 15, '2015-11-21', 'Ayala 13', '09:20:00', 'U16B', NULL, 'R1', '', 'D1', '', 'Area 1U', 'Adam Phipps', 'Todd Flink', 'Steve Manookian', '', 0),
(352, '2015U16U19Chino', 16, '2015-11-21', 'Ayala 14', '09:20:00', 'U16B', NULL, 'F1', '', 'B1', '', 'Area 1H', 'Manuel Del Rio', 'Patrick Alles', 'Chris Salmon', '', 0),
(353, '2015U16U19Chino', 17, '2015-11-21', 'Ayala 7', '10:40:00', 'U19G', NULL, 'F1', '', 'H1', '', 'Area 1N', 'Chris Call', 'Vince Murillo', 'Mike Hamilton', '', 0),
(354, '2015U16U19Chino', 18, '2015-11-21', 'Ayala 8', '10:40:00', 'U19G', NULL, 'F2', '', 'G1', '', 'Area 1F', 'Geoff Falk', 'Craig Gilbert', 'Mike Feder', '', 0),
(355, '2015U16U19Chino', 19, '2015-11-21', 'Ayala 9', '10:40:00', 'U19G', NULL, 'P1', '', 'G2', '', 'Area 1R', 'Victor Valverde', 'Warren Lucio', 'Dawn Hlavac', '', 0),
(356, '2015U16U19Chino', 20, '2015-11-21', 'Ayala 10', '10:40:00', 'U19G', NULL, 'D1', '', 'B1', '', 'Area 1C', 'Adrian Backer', 'Joe Small', 'David Alvarez', '', 0),
(357, '2015U16U19Chino', 21, '2015-11-21', 'Ayala 11', '10:40:00', 'U16G', NULL, 'P1', '', 'C2', '', 'Area 1B', 'Tyler Kleier', 'Mike Sanchez', 'Brian Brady', '', 0),
(358, '2015U16U19Chino', 22, '2015-11-21', 'Ayala 12', '10:40:00', 'U16G', NULL, 'D1', '', 'B1', '', 'Area 1F', 'Gary', 'Gregg', 'Alan', '', 0),
(359, '2015U16U19Chino', 23, '2015-11-21', 'Ayala 13', '10:40:00', 'U16G', NULL, 'D2', '', 'N1', '', 'Area 1U', 'Rob Garcia', 'Tom Larson ?', 'Mars Ramage', '', 0),
(360, '2015U16U19Chino', 24, '2015-11-21', 'Ayala 14', '10:40:00', 'U16G', NULL, 'F1', '', 'C1', '', 'Area 1D', 'Craig Breitman', 'Sam Kabbani', 'Greg Hough', '', 0),
(361, '2015U16U19Chino', 25, '2015-11-21', 'Ayala 7', '12:00:00', 'U19B', NULL, 'U2', '', 'P1', '', 'Area 1N', 'Chris Call', 'Vince Murillo', 'Mike Hamilton', '', 0),
(362, '2015U16U19Chino', 26, '2015-11-21', 'Ayala 8', '12:00:00', 'U19B', NULL, 'G1', '', 'R1', '', 'Area 1F', 'Geoff', 'Craig', 'Tim Reynolds', '', 0),
(363, '2015U16U19Chino', 27, '2015-11-21', 'Ayala 9', '12:00:00', 'U19B', NULL, 'N1', '', 'P2', '', 'Area 1B', '', '', '', '', 0),
(364, '2015U16U19Chino', 28, '2015-11-21', 'Ayala 10', '12:00:00', 'U19B', NULL, 'D1', '', 'U1', '', 'Area 1P', 'John Burgee', 'Jon Kean', 'Darius Simmons', '', 0),
(365, '2015U16U19Chino', 29, '2015-11-21', 'Ayala 11', '12:00:00', 'U16B', NULL, 'H1', '', 'F2', '', 'Area 1B', 'Mike Sanchez', 'Tyler Kleier', 'Brian Brady', '', 0),
(366, '2015U16U19Chino', 30, '2015-11-21', 'Ayala 12', '12:00:00', 'U16B', NULL, 'N1', '', 'B2', '', 'Area 1G', 'Lealon Watts', 'Fernando Cobos', 'Nick Gonzalez', '', 0),
(367, '2015U16U19Chino', 31, '2015-11-21', 'Ayala 13', '12:00:00', 'U16B', NULL, 'C1', '', 'R1', '', 'Area 1H', 'Patrick Alles', 'Chris Salmon', 'Manuel Del Rio', '', 0),
(368, '2015U16U19Chino', 32, '2015-11-21', 'Ayala 14', '12:00:00', 'U16B', NULL, 'G1', '', 'F1', '', 'Area 1D', 'Sam Kabbani', 'Greg Hough', 'Craig Breitman', '', 0),
(369, '2015U16U19Chino', 33, '2015-11-21', 'Ayala 7', '13:20:00', 'U19G', NULL, 'R1', '', 'F1', '', 'Area 1B', 'John Ellis', 'Kevin Widner', 'Michel Larcheveque', '', 0),
(370, '2015U16U19Chino', 34, '2015-11-21', 'Ayala 8', '13:20:00', 'U19G', NULL, 'U1', '', 'F2', '', 'Area 1D', 'Sandee Wilson', 'Roger Stevenson', 'Merit Shoucri', '', 0),
(371, '2015U16U19Chino', 35, '2015-11-21', 'Ayala 9', '13:20:00', 'U19G', NULL, 'C1', '', 'P1', '', 'Area 1H', 'Albert Blanco', 'Orlando Lomeli', 'Angel Gonzalez', '', 0),
(372, '2015U16U19Chino', 36, '2015-11-21', 'Ayala 10', '13:20:00', 'U19G', NULL, 'N1', '', 'D1', '', 'Area 1U', 'Frank Leotti', 'Dan White', 'Miguel Tapia', '', 0),
(373, '2015U16U19Chino', 37, '2015-11-21', 'Ayala 11', '13:20:00', 'U16G', NULL, 'U1', '', 'P1', '', 'Area 1G', 'Glenn Schwartzberg', 'Lealon Watts', 'Jeff Johnston', '', 0),
(374, '2015U16U19Chino', 38, '2015-11-21', 'Ayala 12', '13:20:00', 'U16G', NULL, 'H1', '', 'D1', '', 'Area 1R', 'Rick Walsh', 'Warren Lucio', 'Jeff Johnson', '', 0),
(375, '2015U16U19Chino', 39, '2015-11-21', 'Ayala 13', '13:20:00', 'U16G', NULL, 'G1', '', 'D2', '', 'Area 1P', 'Simmons', 'Feder', 'Burgee', '', 0),
(376, '2015U16U19Chino', 40, '2015-11-21', 'Ayala 14', '13:20:00', 'U16G', NULL, 'R1', '', 'F1', '', 'Area 1C', 'Bruce Hancock', 'Al Prado', 'Kareem Badaruddin', '', 0),
(377, '2015U16U19Chino', 41, '2015-11-21', 'Ayala 7', '14:40:00', 'U19B', NULL, 'B1', '', 'U2', '', 'Area 1D', 'Roger Stevenson', 'Sandee Wilson', 'Merit Shukri', '', 0),
(378, '2015U16U19Chino', 42, '2015-11-21', 'Ayala 8', '14:40:00', 'U19B', NULL, 'F1', '', 'G1', '', 'Area 1B', 'Kevin Widner', 'John Ellis', 'Michel Larcheveque', '', 0),
(379, '2015U16U19Chino', 43, '2015-11-21', 'Ayala 9', '14:40:00', 'U19B', NULL, 'C1', '', 'N1', '', 'Area 1H', 'Alfred Medina', 'Albert Blanco', 'Orlando Lomeli', '', 0),
(380, '2015U16U19Chino', 44, '2015-11-21', 'Ayala 10', '14:40:00', 'U19B', NULL, 'H1', '', 'D1', '', 'Area 1U', 'Mars Ramage', 'Todd Flink', 'Dan White', '', 0),
(381, '2015U16U19Chino', 45, '2015-11-21', 'Ayala 11', '14:40:00', 'U16B', NULL, 'U1', '', 'H1', '', 'Area 1G', 'Jeff Johnston', 'Glenn Schwartzberg', 'Lealon Watts', '', 0),
(382, '2015U16U19Chino', 46, '2015-11-21', 'Ayala 12', '14:40:00', 'U16B', NULL, 'P1', '', 'N1', '', 'Area 1R', 'Mark Tatum', '(Moreno Valley)', 'Jeff Johnson', '', 0),
(383, '2015U16U19Chino', 47, '2015-11-21', 'Ayala 13', '14:40:00', 'U16B', NULL, 'D1', '', 'C1', '', 'Area 1P', ' Tim Reynolds', 'David Martin', 'Michael Feder', '', 0),
(433, '2017ExtraPlayoffs', 1, '2017-01-28', 'Murphy 1', '09:00:00', 'U14G', NULL, 'E-1', '', 'W-2', '', 'Area 1R', '', '', '', '', 0),
(434, '2017ExtraPlayoffs', 2, '2017-01-28', 'Murphy 2', '09:00:00', 'U14G', NULL, 'W-1', '', 'E-2', '', 'Area 1P', '', '', '', '', 0),
(435, '2017ExtraPlayoffs', 3, '2017-01-28', 'Blue 6', '09:00:00', 'U12G', NULL, 'E-1', '', 'W-2', '', 'Area 1U', '', '', '', '', 0),
(436, '2017ExtraPlayoffs', 4, '2017-01-28', 'Blue 1', '09:00:00', 'U12G', NULL, 'W-1', '', 'E-2', '', 'Area 1F', '', '', '', '', 0),
(437, '2017ExtraPlayoffs', 5, '2017-01-28', 'Green 1', '09:00:00', 'U10G', NULL, 'E-1', '', 'W-2', '', 'Area 1P', '', '', '', '', 0),
(438, '2017ExtraPlayoffs', 6, '2017-01-28', 'Green 2', '09:00:00', 'U10G', NULL, 'W-1', '', 'E-2', '', 'Area 1R', '', '', '', '', 0),
(439, '2017ExtraPlayoffs', 7, '2017-01-28', 'Murphy 1', '11:00:00', 'U14B', NULL, 'E-1', '', 'W-2', '', 'Area 1R', '', '', '', '', 0),
(440, '2017ExtraPlayoffs', 8, '2017-01-28', 'Murphy 2', '11:00:00', 'U14B', NULL, 'W-1', '', 'E-2', '', 'Area 1P', '', '', '', '', 0),
(441, '2017ExtraPlayoffs', 9, '2017-01-28', 'Blue 6', '11:00:00', 'U12B', NULL, 'E-1', '', 'W-2', '', 'Area 1N', '', '', '', '', 0),
(442, '2017ExtraPlayoffs', 10, '2017-01-28', 'Blue 1', '11:00:00', 'U12B', NULL, 'W-1', '', 'E-2', '', 'Area 1F', '', '', '', '', 0),
(443, '2017ExtraPlayoffs', 11, '2017-01-28', 'Green 1', '11:00:00', 'U10B', NULL, 'E-1', '', 'W-2', '', 'Area 1P', '', '', '', '', 0),
(444, '2017ExtraPlayoffs', 12, '2017-01-29', 'Green 2', '11:00:00', 'U10B', NULL, 'W-1', '', 'E-2', '', 'Area 1R', '', '', '', '', 0),
(445, '2017ExtraPlayoffs', 13, '2017-01-29', 'White 3', '09:00:00', 'U13G', NULL, 'E-1', '', 'W-2', '', 'Area 1D', '', '', '', '', 0),
(446, '2017ExtraPlayoffs', 14, '2017-01-29', 'White 4', '09:00:00', 'U13G', NULL, 'W-1', '', 'E-2', '', 'Area 1R', '', '', '', '', 0),
(447, '2017ExtraPlayoffs', 15, '2017-01-29', 'Blue 4', '09:00:00', 'U11G', NULL, 'E-1', '', 'W-2', '', 'Area 1F', '', '', '', '', 0),
(448, '2017ExtraPlayoffs', 16, '2017-01-29', 'Blue 5', '09:00:00', 'U11G', NULL, 'W-1', '', 'E-2', '', 'Area 1G', '', '', '', '', 0),
(449, '2017ExtraPlayoffs', 17, '2017-01-29', 'Green 3', '09:00:00', 'U09G', NULL, 'E-1', '', 'W-2', '', 'Area 1B', '', '', '', '', 0),
(450, '2017ExtraPlayoffs', 18, '2017-01-29', 'Yellow 3', '09:00:00', 'U09G', NULL, 'W-1', '', 'E-2', '', 'Area 1P', '', '', '', '', 0),
(451, '2017ExtraPlayoffs', 19, '2017-01-29', 'White 3', '11:00:00', 'U13B', NULL, 'E-1', '', 'W-2', '', 'Area 1D', '', '', '', '', 0),
(452, '2017ExtraPlayoffs', 20, '2017-01-29', 'White 4', '11:00:00', 'U13B', NULL, 'W-1', '', 'E-2', '', 'Area 1R', '', '', '', '', 0),
(453, '2017ExtraPlayoffs', 21, '2017-01-29', 'Blue 4', '11:00:00', 'U11B', NULL, 'E-1', '', 'W-2', '', 'Area 1B', '', '', '', '', 0),
(454, '2017ExtraPlayoffs', 22, '2017-01-29', 'Blue 5', '11:00:00', 'U11B', NULL, 'W-1', '', 'E-2', '', 'Area 1G', '', '', '', '', 0),
(455, '2017ExtraPlayoffs', 23, '2017-01-29', 'Green 3', '11:00:00', 'U09B', NULL, 'E-1', '', 'W-2', '', 'Area 1P', '', '', '', '', 0),
(456, '2017ExtraPlayoffs', 24, '2017-01-29', 'Yellow 3', '11:00:00', 'U09B', NULL, 'W-1', '', 'E-2', '', 'Area 1F', '', '', '', '', 0),
(457, '2016U16U19Chino', 1, '2016-11-19', 'Ayala 7', '08:00:00', 'U19G', '1', 'R1', '', 'C2', '', 'Area 1B', '', '', '', '', 0),
(458, '2016U16U19Chino', 2, '2016-11-19', 'Ayala 8', '08:00:00', 'U19G', '1', 'N1', '', 'B2', '', 'Area 1F', '', '', '', '', 0),
(459, '2016U16U19Chino', 3, '2016-11-19', 'Ayala 9', '08:00:00', 'U19G', '1', 'B1', '', 'D1', '', 'Area 1H', '', '', '', '', 0),
(460, '2016U16U19Chino', 4, '2016-11-19', 'Ayala 10', '08:00:00', 'U19G', '1', 'G1', '', 'P1', '', 'Area 1D', '', '', '', '', 0),
(461, '2016U16U19Chino', 5, '2016-11-19', 'Ayala 11', '08:00:00', 'U16G', '3', 'D1', '', 'U1', '', 'Area 1C', '', '', '', '', 0),
(462, '2016U16U19Chino', 6, '2016-11-19', 'Ayala 12', '08:00:00', 'U16G', '3', 'P1', '', 'N1', '', 'Area 1R', '', '', '', '', 0),
(463, '2016U16U19Chino', 7, '2016-11-19', 'Ayala 13', '08:00:00', 'U16G', '3', 'B1', '', 'N2', '', 'Area 1P', '', '', '', '', 0),
(464, '2016U16U19Chino', 8, '2016-11-19', 'Ayala 14', '08:00:00', 'U16G', '3', 'H1', '', 'R2', '', 'Area 1G', '', '', '', '', 0),
(465, '2016U16U19Chino', 9, '2016-11-19', 'Ayala 7', '09:20:00', 'U19B', '2', 'G2', '', 'R1', '', 'Area 1B', '', '', '', '', 0),
(466, '2016U16U19Chino', 10, '2016-11-19', 'Ayala 8', '09:20:00', 'U19B', '2', 'N1', '', 'H1', '', 'Area 1F', '', '', '', '', 0),
(467, '2016U16U19Chino', 11, '2016-11-19', 'Ayala 9', '09:20:00', 'U19B', '2', 'C1', '', 'B1', '', 'Area 1H', '', '', '', '', 0),
(468, '2016U16U19Chino', 12, '2016-11-19', 'Ayala 10', '09:20:00', 'U19B', '2', 'U1', '', 'G1', '', 'Area 1D', '', '', '', '', 0),
(469, '2016U16U19Chino', 13, '2016-11-19', 'Ayala 11', '09:20:00', 'U16B', '3', 'R1', '', 'H2', '', 'Area 1C', '', '', '', '', 0),
(470, '2016U16U19Chino', 14, '2016-11-19', 'Ayala 12', '09:20:00', 'U16B', '3', 'G1', '', 'U1', '', 'Area 1R', '', '', '', '', 0),
(471, '2016U16U19Chino', 15, '2016-11-19', 'Ayala 13', '09:20:00', 'U16B', '3', 'B1', '', 'C1', '', 'Area 1P', '', '', '', '', 0),
(472, '2016U16U19Chino', 16, '2016-11-19', 'Ayala 14', '09:20:00', 'U16B', '3', 'H1', '', 'N1', '', 'Area 1G', '', '', '', '', 0),
(473, '2016U16U19Chino', 17, '2016-11-19', 'Ayala 7', '10:40:00', 'U19G', '1', 'D2', '', 'R1', '', 'Area 1B', '', '', '', '', 0),
(474, '2016U16U19Chino', 18, '2016-11-19', 'Ayala 8', '10:40:00', 'U19G', '1', 'C1', '', 'N1', '', 'Area 1G', '', '', '', '', 0),
(475, '2016U16U19Chino', 19, '2016-11-19', 'Ayala 9', '10:40:00', 'U19G', '1', 'F1', '', 'B1', '', 'Area 1R', '', '', '', '', 0),
(476, '2016U16U19Chino', 20, '2016-11-19', 'Ayala 10', '10:40:00', 'U19G', '1', 'U1', '', 'G1', '', 'Area 1D', '', '', '', '', 0),
(477, '2016U16U19Chino', 21, '2016-11-19', 'Ayala 11', '10:40:00', 'U16G', '3', 'F1', '', 'D1', '', 'Area 1N', '', '', '', '', 0),
(478, '2016U16U19Chino', 22, '2016-11-19', 'Ayala 12', '10:40:00', 'U16G', '3', 'R1', '', 'P1', '', 'Area 1R', '', '', '', '', 0),
(479, '2016U16U19Chino', 23, '2016-11-19', 'Ayala 13', '10:40:00', 'U16G', '3', 'G1', '', 'B1', '', 'Area 1U', '', '', '', '', 0),
(480, '2016U16U19Chino', 24, '2016-11-19', 'Ayala 14', '10:40:00', 'U16G', '3', 'C1', '', 'H1', '', 'Area 1G', '', '', '', '', 0),
(481, '2016U16U19Chino', 25, '2016-11-19', 'Ayala 7', '12:00:00', 'U19B', '2', 'F2', '', 'G2', '', 'Area 1P', '', '', '', '', 0),
(482, '2016U16U19Chino', 26, '2016-11-19', 'Ayala 8', '12:00:00', 'U19B', '2', 'D1', '', 'N1', '', 'Area 1G', '', '', '', '', 0),
(483, '2016U16U19Chino', 27, '2016-11-19', 'Ayala 9', '12:00:00', 'U19B', '2', 'F1', '', 'C1', '', 'Area 1R', '', '', '', '', 0),
(484, '2016U16U19Chino', 28, '2016-11-19', 'Ayala 10', '12:00:00', 'U19B', '2', 'P1', '', 'U1', '', 'Area 1C', '', '', '', '', 0),
(485, '2016U16U19Chino', 29, '2016-11-19', 'Ayala 11', '12:00:00', 'U16B', '3', 'D1', '', 'R1', '', 'Area 1N', '', '', '', '', 0),
(486, '2016U16U19Chino', 30, '2016-11-19', 'Ayala 12', '12:00:00', 'U16B', '3', 'F1', '', 'G1', '', 'Area 1H', '', '', '', '', 0),
(487, '2016U16U19Chino', 31, '2016-11-19', 'Ayala 13', '12:00:00', 'U16B', '3', 'P1', '', 'B1', '', 'Area 1U', '', '', '', '', 0),
(488, '2016U16U19Chino', 32, '2016-11-19', 'Ayala 14', '12:00:00', 'U16B', '3', 'C2', '', 'H1', '', 'Area 1F', '', '', '', '', 0),
(489, '2016U16U19Chino', 33, '2016-11-19', 'Ayala 7', '13:20:00', 'U19G', '1', 'C2', '', 'D2', '', 'Area 1P', '', '', '', '', 0),
(490, '2016U16U19Chino', 34, '2016-11-19', 'Ayala 8', '13:20:00', 'U19G', '1', 'B2', '', 'C1', '', 'Area 1N', '', '', '', '', 0),
(491, '2016U16U19Chino', 35, '2016-11-19', 'Ayala 9', '13:20:00', 'U19G', '1', 'D1', '', 'F1', '', 'Area 1U', '', '', '', '', 0),
(492, '2016U16U19Chino', 36, '2016-11-19', 'Ayala 10', '13:20:00', 'U19G', '1', 'P1', '', 'U1', '', 'Area 1C', '', '', '', '', 0),
(493, '2016U16U19Chino', 37, '2016-11-19', 'Ayala 11', '13:20:00', 'U16G', '3', 'U1', '', 'F1', '', 'Area 1B', '', '', '', '', 0),
(494, '2016U16U19Chino', 38, '2016-11-19', 'Ayala 12', '13:20:00', 'U16G', '3', 'N1', '', 'R1', '', 'Area 1H', '', '', '', '', 0),
(495, '2016U16U19Chino', 39, '2016-11-19', 'Ayala 13', '13:20:00', 'U16G', '3', 'N2', '', 'G1', '', 'Area 1D', '', '', '', '', 0),
(496, '2016U16U19Chino', 40, '2016-11-19', 'Ayala 14', '13:20:00', 'U16G', '3', 'R2', '', 'C1', '', 'Area 1F', '', '', '', '', 0),
(497, '2016U16U19Chino', 41, '2016-11-19', 'Ayala 7', '14:40:00', 'U19B', '2', 'R1', '', 'F2', '', 'Area 1P', '', '', '', '', 0),
(498, '2016U16U19Chino', 42, '2016-11-19', 'Ayala 8', '14:40:00', 'U19B', '2', 'H1', '', 'D1', '', 'Area 1N', '', '', '', '', 0),
(499, '2016U16U19Chino', 43, '2016-11-19', 'Ayala 9', '14:40:00', 'U19B', '2', 'B1', '', 'F1', '', 'Area 1U', '', '', '', '', 0),
(500, '2016U16U19Chino', 44, '2016-11-19', 'Ayala 10', '14:40:00', 'U19B', '2', 'G1', '', 'P1', '', 'Area 1C', '', '', '', '', 0),
(501, '2016U16U19Chino', 45, '2016-11-19', 'Ayala 11', '14:40:00', 'U16B', '3', 'H2', '', 'D1', '', 'Area 1B', '', '', '', '', 0),
(502, '2016U16U19Chino', 46, '2016-11-19', 'Ayala 12', '14:40:00', 'U16B', '3', 'U1', '', 'F1', '', 'Area 1H', '', '', '', '', 0),
(503, '2016U16U19Chino', 47, '2016-11-19', 'Ayala 13', '14:40:00', 'U16B', '3', 'C1', '', 'P1', '', 'Area 1D', '', '', '', '', 0),
(504, '2016U16U19Chino', 48, '2016-11-19', 'Ayala 14', '14:40:00', 'U16B', '3', 'N1', '', 'C2', '', 'Area 1F', '', '', '', '', 0),
(505, '2016U16U19Chino', 49, '2016-11-20', 'Ayala 7', '08:30:00', 'U16G', '', 'Winning Team Pool 1', '', 'Winning Team Pool 2', '', 'Section 1', '', '', '', '', 1),
(506, '2016U16U19Chino', 50, '2016-11-20', 'Ayala 8', '08:30:00', 'U16G', '', 'Winning Team Pool 3', '', 'Winning Team Pool 4', '', 'Section 1', '', '', '', '', 1),
(507, '2016U16U19Chino', 51, '2016-11-20', 'Ayala 9', '08:30:00', 'U16B', '', 'Winning Team Pool 1', '', 'Winning Team Pool 2', '', 'Section 1', '', '', '', '', 1),
(508, '2016U16U19Chino', 52, '2016-11-20', 'Ayala 10', '08:30:00', 'U16B', '', 'Winning Team Pool 3', '', 'Winning Team Pool 4', '', 'Section 1', '', '', '', '', 1),
(509, '2016U16U19Chino', 53, '2016-11-20', 'Ayala 7', '10:30:00', 'U19G', '', 'Winning Team Pool 1', '', 'Winning Team Pool 2', '', 'Section 1', '', '', '', '', 1),
(510, '2016U16U19Chino', 54, '2016-11-20', 'Ayala 8', '10:30:00', 'U19G', '', 'Winning Team Pool 3', '', 'Winning Team Pool 4', '', 'Section 1', '', '', '', '', 1),
(511, '2016U16U19Chino', 55, '2016-11-20', 'Ayala 9', '10:30:00', 'U19B', '', 'Winning Team Pool 1', '', 'Winning Team Pool 2', '', 'Section 1', '', '', '', '', 1),
(512, '2016U16U19Chino', 56, '2016-11-20', 'Ayala 10', '10:30:00', 'U19B', '', 'Winning Team Pool 3', '', 'Winning Team Pool 4', '', 'Section 1', '', '', '', '', 1),
(513, '2016U16U19Chino', 57, '2016-11-20', 'Ayala 7', '12:30:00', 'U16G', '', 'Championship-Winning Team #49', '', 'Winning Team Game #50', '', 'Section 1', '', '', '', '', 1),
(514, '2016U16U19Chino', 58, '2016-11-20', 'Ayala 8', '12:30:00', 'U16G', '', 'Consolation-Losing team #49', '', 'Losing team Game #50', '', 'Section 1', '', '', '', '', 1),
(515, '2016U16U19Chino', 59, '2016-11-20', 'Ayala 9', '12:30:00', 'U16B', '', 'Championship-Winning Team #51', '', 'Winning Team Game #52', '', 'Section 1', '', '', '', '', 1),
(516, '2016U16U19Chino', 60, '2016-11-20', 'Ayala 10', '12:30:00', 'U16B', '', 'Consolation-Losing Team #51', '', 'Losing Team Game #52', '', 'Section 1', '', '', '', '', 1),
(517, '2016U16U19Chino', 61, '2016-11-20', 'Ayala 7', '14:30:00', 'U19G', '', 'Championship-Winning Team #53 ', '', 'Winning Team Game #54', '', 'Section 1', '', '', '', '', 1),
(518, '2016U16U19Chino', 62, '2016-11-20', 'Ayala 8', '14:30:00', 'U19G', '', 'Consolation-Losing Team #53', '', 'Losing Team Game #54', '', 'Section 1', '', '', '', '', 1),
(519, '2016U16U19Chino', 63, '2016-11-20', 'Ayala 9', '14:30:00', 'U19B', '', 'Championship-Winning Team #55', '', 'Winning Team Game #56', '', 'Section 1', '', '', '', '', 1),
(520, '2016U16U19Chino', 64, '2016-11-20', 'Ayala 10', '14:30:00', 'U19B', '', 'Consolation-Losing Team #55', '', 'Losing Team Game #56', '', 'Section 1', '', '', '', '', 1);

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
-- Dumping data for table `rs_limits`
--

INSERT INTO `rs_limits` (`id`, `projectKey`, `division`, `limit`) VALUES
(7, '2015U16U19Chino', 'U16', '7'),
(8, '2015U16U19Chino', 'U19', '7'),
(9, '2016AllStarExtraPlayoffs', 'U09', '7'),
(10, '2016AllStarExtraPlayoffs', 'U10', '7'),
(11, '2016AllStarExtraPlayoffs', 'U11', '7'),
(12, '2016AllStarExtraPlayoffs', 'U12', '7'),
(13, '2016AllStarExtraPlayoffs', 'U13', '7'),
(14, '2016AllStarExtraPlayoffs', 'U14', '7'),
(23, '2016U16U19Chino~', 'U16', '3'),
(24, '2016U16U19Chino', 'U19', '4'),
(25, '2016WSC', 'U09', '7'),
(26, '2016WSC', 'U10', '7'),
(27, '2016WSC', 'U11', '7'),
(28, '2016WSC', 'U12', '7'),
(29, '2016WSC', 'U13', '7'),
(30, '2016WSC', 'U14', '7'),
(33, '2017U10U14AllStarPlayoffs', 'U09', '7'),
(34, '2017U10U14AllStarPlayoffs', 'U10', '7'),
(35, '2017U10U14AllStarPlayoffs', 'U11', '7'),
(36, '2017U10U14AllStarPlayoffs', 'U12', '7'),
(37, '2017U10U14AllStarPlayoffs', 'U13', '7'),
(38, '2017U10U14AllStarPlayoffs', 'U14', '7'),
(41, '2017U10U14LeaguePlayoffs', 'U09', '7'),
(42, '2017U10U14LeaguePlayoffs', 'U10', '7'),
(43, '2017U10U14LeaguePlayoffs', 'U11', '7'),
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
-- Table structure for table `rs_users`
--

DROP TABLE IF EXISTS `rs_users`;
CREATE TABLE `rs_users` (
  `id` int(11) NOT NULL,
  `name` char(255) NOT NULL,
  `enabled` tinyint(1) DEFAULT '1',
  `hash` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `rs_users`
--

INSERT INTO `rs_users` (`id`, `name`, `enabled`, `hash`) VALUES
(1, 'Area 1B', 1, '$2y$10$NeD02z/uz8u8JtyJR/58jeFVWCiU94wytH5E76J5y8NPpZMZvimz2'),
(2, 'Area 1C', 1, '$2y$10$UzypGyBnLT4uzuu/k0Uz.uTwF08Kujp25Xeb24sQzKskFUwCtpH0O'),
(3, 'Area 1D', 1, '$2y$10$fQUU95kCwpkpXIh000JzzumMm2E4nWlp0yNbLU11iSdcufkXg0ChW'),
(4, 'Area 1F', 1, '$2y$10$IJWFZbu3I1l5Kt2FK7mElO2qWFnG4QZfHS5PfWvaw1y/rS59SSOyK'),
(5, 'Area 1G', 1, '$2y$10$kzZfLJ1Ls/ey1VFa62oF5u2SZihG.WnvPUcxUQKIGyf/Gfqwd5awu'),
(6, 'Area 1H', 1, '$2y$10$KzL4b5PRwkulH16spHhOZu2FvXwTQSA7KDHGF.C3xhL5ZBV6WOyGO'),
(7, 'Area 1N', 1, '$2y$10$aSaw9HfijsbzKaAGzJrsv.2f6K5LyFpkD9j9EhzYH2aLzbKQVzqVy'),
(8, 'Area 1P', 1, '$2y$10$Je3X6j5s1lVG6RYMjF1QYOXsP.BApCv98segZNl4UuiB0dHVQk3cq'),
(9, 'Area 1R', 1, '$2y$10$f6kXD2d16RnbT2VYnAeHj..BYGXD0aN7KnCGrH6hq0uBlASJF4cWm'),
(10, 'Area 1S', 1, '$2y$10$.zmtRAh1yycyTv4a4RTHHOoEwXb516Z3owUOyHmxZIQZxM1KtnVY.'),
(11, 'Area 1U', 1, '$2y$10$9pzmAR4RwkOO0Xsc2/SyHuwiY60JDcI5V74pRJZfokSXhu6tlmgJ.'),
(12, 'Section 1', 1, '$2y$10$nNfSphBpJ/kloMiB6tChi.VOUcjjqQjRkskpifvcR6SQE4oBQWvCe'),
(13, 'Section One', 0, '$2y$10$Xh9lVQZm4Efa9b4ku1gpWOFdld.o934fkFxozm/Yjn/9dhh1OsjNq'),
(14, 'Section 2', 0, '$2y$10$lQoq3elPgWWmV9NW4MZIduv53oiRF366lZb2icbqgh53KQaHkXdWS'),
(15, 'Section 10', 0, '$2y$10$bcIPQntM.Hhn7NKEAkQ2pe2ZX97JQX118xMzCoReVAlasiIvm7Or2'),
(16, 'Section 11', 0, '$2y$10$FhJAsQoJMkhYGObrTwYa1.Yq30wHrMrKJWzsfq2MvdY6RKsWPJSTW'),
(17, 'Admin', 0, '$2y$10$5bHtR/setncXx3VesXXtxuHYp0BJ12ZpBBZVPr4.D6VovAMvw2XEq');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rs_ajax_example`
--
ALTER TABLE `rs_ajax_example`
  ADD PRIMARY KEY (`name`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=521;
--
-- AUTO_INCREMENT for table `rs_limits`
--
ALTER TABLE `rs_limits`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;
--
-- AUTO_INCREMENT for table `rs_users`
--
ALTER TABLE `rs_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
