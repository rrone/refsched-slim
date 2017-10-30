-- phpMyAdmin SQL Dump
-- version 4.7.5
-- https://www.phpmyadmin.net/
--
-- Host: 10.0.2.2:3307
-- Generation Time: Oct 28, 2017 at 03:42 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.11-1+ubuntu14.04.1+deb.sury.org+1

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
(457, '2016U16U19Chino', 1, '2016-11-19', '08:00:00', 'Ayala 7', 'G19U', '1', 'R1', '', 'C2', '', 'Area 1B', '', '', '', '', 0, 1),
(458, '2016U16U19Chino', 2, '2016-11-19', '08:00:00', 'Ayala 8', 'G19U', '2', 'N1', '', 'B2', '', 'Area 1F', 'Herb Countee', 'Michael Feder', 'Won Song', '', 0, 1),
(459, '2016U16U19Chino', 3, '2016-11-19', '08:00:00', 'Ayala 9', 'G19U', '3', 'B1', '', 'D1', '', 'Area 1H', 'Chris Salmon', 'Jose Macias', 'Alfred Medina', '', 0, 1),
(460, '2016U16U19Chino', 4, '2016-11-19', '08:00:00', 'Ayala 10', 'G19U', '4', 'G1', '', 'P1', '', 'Area 1D', 'Craig Breitman', 'Merit Shoucri', 'Scott Jarus', '', 0, 1),
(461, '2016U16U19Chino', 5, '2016-11-19', '08:00:00', 'Ayala 11', 'G16U', '1', 'D1', '', 'U1', '', 'Area 1C', 'Lincoln Wallen', 'Kareem Badaruddin', 'Will Hardy', '', 0, 1),
(462, '2016U16U19Chino', 6, '2016-11-19', '08:00:00', 'Ayala 12', 'G16U', '2', 'P1', '', 'N1', '', 'Area 1R', 'Ramon Guzman', 'Joseph Marconi', 'Lee Lombard', '', 0, 1),
(463, '2016U16U19Chino', 7, '2016-11-19', '08:00:00', 'Ayala 13', 'G16U', '3', 'B1', '', 'N2', '', 'Area 1P', 'John Burgee', 'Chris Nevil', 'Scott Karlan', '', 0, 1),
(464, '2016U16U19Chino', 8, '2016-11-19', '08:00:00', 'Ayala 14', 'G16U', '4', 'H1', '', 'R2', '', 'Area 1G', 'Glenn Schwartzberg', 'Sandy Wright', 'Michael Sanchez', '', 0, 1),
(465, '2016U16U19Chino', 9, '2016-11-19', '09:20:00', 'Ayala 7', 'B19U', '1', 'G2', '', 'R1', '', 'Area 1B', '', '', '', '', 0, 1),
(466, '2016U16U19Chino', 10, '2016-11-19', '09:20:00', 'Ayala 8', 'B19U', '2', 'N1', '', 'H1', '', 'Area 1F', 'Michael Feder', 'Herb Countee', 'Won Song', '', 0, 1),
(467, '2016U16U19Chino', 11, '2016-11-19', '09:20:00', 'Ayala 9', 'B19U', '3', 'C1', '', 'B1', '', 'Area 1H', 'Jose Macias', 'Chris Salmon', 'Alfred Medina', '', 0, 1),
(468, '2016U16U19Chino', 12, '2016-11-19', '09:20:00', 'Ayala 10', 'B19U', '4', 'U1', '', 'G1', '', 'Area 1D', 'Merit Shoucri', 'Craig Breitman', 'Scott Jarus', '', 0, 1),
(469, '2016U16U19Chino', 13, '2016-11-19', '09:20:00', 'Ayala 11', 'B16U', '1', 'R1', '', 'H2', '', 'Area 1C', 'Will Hardy', 'Lincoln Wallen', 'Kareem Badaruddin', '', 0, 1),
(470, '2016U16U19Chino', 14, '2016-11-19', '09:20:00', 'Ayala 12', 'B16U', '2', 'G1', '', 'U1', '', 'Area 1R', 'Stefan Larson', 'Joseph Marconi', 'James Affinito', '', 0, 1),
(471, '2016U16U19Chino', 15, '2016-11-19', '09:20:00', 'Ayala 13', 'B16U', '3', 'B1', '', 'C1', '', 'Area 1P', 'Chris Nevil', 'Scott Karlan', 'John Burgee', '', 0, 1),
(472, '2016U16U19Chino', 16, '2016-11-19', '09:20:00', 'Ayala 14', 'B16U', '4', 'H1', '', 'N1', '', 'Area 1B', '', '', '', '', 0, 1),
(473, '2016U16U19Chino', 17, '2016-11-19', '10:40:00', 'Ayala 7', 'G19U', '1', 'D2', '', 'R1', '', 'Area 1B', '', '', '', '', 0, 0),
(474, '2016U16U19Chino', 18, '2016-11-19', '10:40:00', 'Ayala 8', 'G19U', '2', 'C1', '', 'N1', '', 'Area 1G', 'Jeff Johnston', 'Lealon Watts', 'Michael Hays', '', 0, 1),
(475, '2016U16U19Chino', 19, '2016-11-19', '10:40:00', 'Ayala 9', 'G19U', '3', 'F1', '', 'B1', '', 'Area 1R', 'Steven Chandler', 'Lee Lombard', 'Joseph Marconi', '', 0, 1),
(476, '2016U16U19Chino', 20, '2016-11-19', '10:40:00', 'Ayala 10', 'G19U', '4', 'U1', '', 'G1', '', 'Area 1D', 'Scott Jarus', 'Merit Shoucri', 'Craig Breitman', '', 0, 1),
(477, '2016U16U19Chino', 21, '2016-11-19', '10:40:00', 'Ayala 11', 'G16U', '1', 'F1', '', 'D1', '', 'Area 1N', 'Matt Hurlbert', 'Gilberto Maldonado', 'Jon Swasey', '', 0, 1),
(478, '2016U16U19Chino', 22, '2016-11-19', '10:40:00', 'Ayala 12', 'G16U', '2', 'R1', '', 'P1', '', 'Area 1U', 'Javier Chagolla', 'Mars Ramage', 'Rob Owen', '', 0, 1),
(479, '2016U16U19Chino', 23, '2016-11-19', '10:40:00', 'Ayala 13', 'G16U', '3', 'G1', '', 'B1', '', 'Area 1R', 'Ed Williams', 'Dawn Hlavac', 'James Affinito', '', 0, 1),
(480, '2016U16U19Chino', 24, '2016-11-19', '10:40:00', 'Ayala 14', 'G16U', '4', 'C1', '', 'H1', '', 'Area 1G', 'Joe Bernier', 'Ramon Guzman', 'Steven Caro', '', 0, 1),
(481, '2016U16U19Chino', 25, '2016-11-19', '12:00:00', 'Ayala 7', 'B19U', '1', 'F2', '', 'G2', '', 'Area 1P', 'Scott Karlan', 'John Burgee', 'Chris Nevil', '', 0, 1),
(482, '2016U16U19Chino', 26, '2016-11-19', '12:00:00', 'Ayala 8', 'B19U', '2', 'D1', '', 'N1', '', 'Area 1G', 'Lealon Watts', 'Jeff Johnston', 'Michael Hays', '', 0, 1),
(483, '2016U16U19Chino', 27, '2016-11-19', '12:00:00', 'Ayala 9', 'B19U', '3', 'F1', '', 'C1', '', 'Area 1R', 'James Hodge', 'Stefan Larson', 'Steven Chandler', '', 0, 1),
(484, '2016U16U19Chino', 28, '2016-11-19', '12:00:00', 'Ayala 10', 'B19U', '4', 'P1', '', 'U1', '', 'Area 1C', 'John Mass', 'Al Prado', 'Scott Davis', '', 0, 1),
(485, '2016U16U19Chino', 29, '2016-11-19', '12:00:00', 'Ayala 11', 'B16U', '1', 'D1', '', 'R1', '', 'Area 1N', 'Gilberto Maldonado', 'Joe Bernier', 'Jon Swasey', '', 0, 1),
(486, '2016U16U19Chino', 30, '2016-11-19', '12:00:00', 'Ayala 12', 'B16U', '2', 'F1', '', 'G1', '', 'Area 1H', 'Manuel Del Rio', 'John Hampson', 'Jose Macias', '', 0, 1),
(487, '2016U16U19Chino', 31, '2016-11-19', '12:00:00', 'Ayala 13', 'B16U', '3', 'P1', '', 'B1', '', 'Area 1U', 'Rob Owen', 'Mars Ramage', 'Javier Chagolla', '', 0, 1),
(488, '2016U16U19Chino', 32, '2016-11-19', '12:00:00', 'Ayala 14', 'B16U', '4', 'C2', '', 'H1', '', 'Area 1F', 'Alan Siegel', 'Gregg Ferguson', 'Michael Wolff', '', 0, 1),
(489, '2016U16U19Chino', 33, '2016-11-19', '13:20:00', 'Ayala 7', 'G19U', '1', 'C2', '', 'D2', '', 'Area 1P', 'Michael Feder', 'Robert Osborne', 'Tim Reynolds', '', 0, 1),
(490, '2016U16U19Chino', 34, '2016-11-19', '13:20:00', 'Ayala 8', 'G19U', '2', 'B2', '', 'C1', '', 'Area 1N', 'Chris Call', 'Forrest Pitts', 'Rob Hurt', '', 0, 1),
(491, '2016U16U19Chino', 35, '2016-11-19', '13:20:00', 'Ayala 9', 'G19U', '3', 'D1', '', 'F1', '', 'Area 1U', 'Mike Rodewald', 'Steve Manriquez', 'Ramon Villar', '', 0, 1),
(492, '2016U16U19Chino', 36, '2016-11-19', '13:20:00', 'Ayala 10', 'G19U', '4', 'P1', '', 'U1', '', 'Area 1C', 'Scott Davis', 'John Mass', 'Al Prado', '', 0, 1),
(493, '2016U16U19Chino', 37, '2016-11-19', '13:20:00', 'Ayala 11', 'G16U', '1', 'U1', '', 'F1', '', '', '', '', '', '', 0, 1),
(494, '2016U16U19Chino', 38, '2016-11-19', '13:20:00', 'Ayala 12', 'G16U', '2', 'N1', '', 'R1', '', 'Area 1H', 'Albert Blanco', 'John Hampson', 'Manuel Del Rio', '', 0, 1),
(495, '2016U16U19Chino', 39, '2016-11-19', '13:20:00', 'Ayala 13', 'G16U', '3', 'N2', '', 'G1', '', 'Area 1D', 'Jamie Stewart', 'Peter Lindborg', 'Greg Power', '', 0, 1),
(496, '2016U16U19Chino', 40, '2016-11-19', '13:20:00', 'Ayala 14', 'G16U', '4', 'R2', '', 'C1', '', 'Area 1F', 'Michael Wolff', 'Gregg Ferguson', 'Alan Siegel', '', 0, 1),
(497, '2016U16U19Chino', 41, '2016-11-19', '14:40:00', 'Ayala 7', 'B19U', '1', 'R1', '', 'F2', '', 'Area 1P', 'Robert Osborne', 'Tim Reynolds', 'Michael Feder', '', 0, 1),
(498, '2016U16U19Chino', 42, '2016-11-19', '14:40:00', 'Ayala 8', 'B19U', '2', 'H1', '', 'D1', '', 'Area 1N', 'Chris Call', 'Forrest Pitts', 'Rob Hurt', '', 0, 1),
(499, '2016U16U19Chino', 43, '2016-11-19', '14:40:00', 'Ayala 9', 'B19U', '3', 'B1', '', 'F1', '', 'Area 1U', 'Ramon Villar', 'Steve Manriquez', 'Mike Rodewald', '', 0, 1),
(500, '2016U16U19Chino', 44, '2016-11-19', '14:40:00', 'Ayala 10', 'B19U', '4', 'G1', '', 'P1', '', 'Area 1C', 'Al Prado', 'Scott Davis', 'John Mass', '', 0, 1),
(501, '2016U16U19Chino', 45, '2016-11-19', '14:40:00', 'Ayala 11', 'B16U', '1', 'H2', '', 'D1', '', '', '', '', '', '', 0, 1),
(502, '2016U16U19Chino', 46, '2016-11-19', '14:40:00', 'Ayala 12', 'B16U', '2', 'U1', '', 'F1', '', 'Area 1H', 'John Hampson', 'Manuel Del Rio', 'Albert Blanco', '', 0, 1),
(503, '2016U16U19Chino', 47, '2016-11-19', '14:40:00', 'Ayala 13', 'B16U', '3', 'C1', '', 'P1', '', 'Area 1D', 'Peter Lindborg', 'Jamie Stewart', 'Greg Power', '', 0, 1),
(504, '2016U16U19Chino', 48, '2016-11-19', '14:40:00', 'Ayala 14', 'B16U', '4', 'N1', '', 'C2', '', 'Area 1F', 'Gregg Ferguson', 'Michael Wolff', 'Alan Siegel', '', 0, 1),
(505, '2016U16U19Chino', 49, '2016-11-20', '08:30:00', 'Ayala 7', 'G16U', 'SF', 'F1 16 North Torrance', '', 'P1 1031 So. Los Angeles', '', '', '', '', '', '', 1, 1),
(506, '2016U16U19Chino', 50, '2016-11-20', '08:30:00', 'Ayala 8', 'G16U', 'SF', 'B1 31 Diamond Bar', '', 'C1 98 Temple City', '', 'Area 1R', 'Steven Chandler', 'Ed Williams', 'James Hodge', '', 1, 1),
(507, '2016U16U19Chino', 51, '2016-11-20', '08:30:00', 'Ayala 9', 'B16U', 'SF', 'D1 21 Hawthorne', '', 'G1 65 Rancho Cucamonga', '', '', '', '', '', '', 1, 1),
(508, '2016U16U19Chino', 52, '2016-11-20', '08:30:00', 'Ayala 10', 'B16U', 'SF', 'P1 1031 So Los Angeles', '', 'N1 641 Pass Area', '', 'Area 1D', 'Phil Ockelmann', 'Craig Breitman', 'Merit Shoucri', '', 1, 1),
(509, '2016U16U19Chino', 53, '2016-11-20', '10:30:00', 'Ayala 7', 'G19U', 'SF', 'D2 18 Manhattan/Hermosa', '', 'C1 2 Arcadia', '', '', '', '', '', '', 1, 1),
(510, '2016U16U19Chino', 54, '2016-11-20', '10:30:00', 'Ayala 8', 'G19U', 'SF', 'F1 16 North Torrance', '', 'P1 20 Santa Monica', '', 'Area 1D', 'Merit Shoucri', 'Craig Breitman', 'Phil Ockelmann', '', 1, 1),
(511, '2016U16U19Chino', 55, '2016-11-20', '10:30:00', 'Ayala 9', 'B19U', 'SF', 'F2 16 No Torrance', '', 'D1 18 Manhattan/Hermosa', '', 'Area 1G', 'Lealon Watts', 'Greg Hood', 'Jeff Johnston', '', 1, 1),
(512, '2016U16U19Chino', 56, '2016-11-20', '10:30:00', 'Ayala 10', 'B19U', 'SF', 'B1 31 Diamond Bar', '', 'G1 65 Rancho Cucamonga', '', 'Area 1R', 'Ed Williams', 'James Hodge', 'Steven Chandler', '', 1, 1),
(513, '2016U16U19Chino', 57, '2016-11-20', '12:30:00', 'Ayala 7', 'G16U', 'FIN', 'F1 16 North Torrance', '', 'C1 98 Temple City', '', 'Area 1H', 'Patrick Alles', 'Manuel Del Rio', 'Amer Hassouneh', '', 1, 1),
(514, '2016U16U19Chino', 58, '2016-11-20', '12:30:00', 'Ayala 8', 'G16U', 'CON', 'P1 1031 So. Los Angeles', '', 'B1 31 Diamond Bar', '', 'Area 1C', 'Scott Davis', 'Al Prado', 'Steve Hawkins', '', 1, 1),
(515, '2016U16U19Chino', 59, '2016-11-20', '12:30:00', 'Ayala 9', 'B16U', 'FIN', 'D1 21 Hawthorne', '', 'P1 1031 So Los Angeles', '', 'Area 1F', 'Michael Feder', 'Herb Countee', 'Tim Reynolds', '', 1, 1),
(516, '2016U16U19Chino', 60, '2016-11-20', '12:30:00', 'Ayala 10', 'B16U', 'CON', 'G1 65 Rancho Cucamonga', '', 'N1 641 Pass Area', '', 'Area 1P', 'Achikam Shapira', 'Howard Chait', 'Tony Robinson', '', 1, 1),
(517, '2016U16U19Chino', 61, '2016-11-20', '14:30:00', 'Ayala 7', 'G19U', 'FIN', 'D2 18 Manhattan/Hermosa', '', 'F1 16 North Torrance', '', 'Area 1P', 'Howard Chait', 'Achikam Shapira', 'Tony Robinson', '', 1, 1),
(518, '2016U16U19Chino', 62, '2016-11-20', '14:30:00', 'Ayala 8', 'G19U', 'CON', 'C1 2 Arcadia', '', 'P1 20 Santa Monica', '', 'Area 1F', 'Herb Countee', 'David', 'Michael Feder', '', 1, 1),
(519, '2016U16U19Chino', 63, '2016-11-20', '14:30:00', 'Ayala 9', 'B19U', 'FIN', 'D1 18 Manhattan/Hermosa', '', 'G1 65 Rancho Cucamonga', '', 'Area 1C', 'Al Prado', 'Scott Davis', 'Steve Hawkins', '', 1, 1),
(520, '2016U16U19Chino', 64, '2016-11-20', '14:30:00', 'Ayala 10', 'B19U', 'CON', 'F2 16 No Torrance', '', 'B1 31 Diamond Bar', '', 'Area 1H', 'Manuel Del Rio', 'Patrick Alles', 'Amer Hassouneh', '', 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `rs_games`
--
ALTER TABLE `rs_games`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `rs_games`
--
ALTER TABLE `rs_games`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=641;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
