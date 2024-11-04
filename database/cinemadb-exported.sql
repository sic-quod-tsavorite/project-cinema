-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 31, 2024 at 09:38 AM
-- Server version: 8.3.0
-- PHP Version: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cinemadb`
--

DROP DATABASE IF EXISTS CinemaDB;
CREATE DATABASE CinemaDB;
USE CinemaDB;

-- --------------------------------------------------------

--
-- Table structure for table `actorrole`
--

DROP TABLE IF EXISTS `actorrole`;
CREATE TABLE IF NOT EXISTS `actorrole` (
  `actor_roleID` int NOT NULL AUTO_INCREMENT,
  `actorID` int NOT NULL,
  `movieID` int NOT NULL,
  `role` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`actor_roleID`),
  KEY `actorID` (`actorID`),
  KEY `movieID` (`movieID`)
) ENGINE=InnoDB AUTO_INCREMENT=72 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `actorrole`
--

INSERT INTO `actorrole` (`actor_roleID`, `actorID`, `movieID`, `role`) VALUES
(1, 1, 1, 'Jim Hawkins'),
(2, 8, 1, 'Captain Amelia'),
(68, 1, 28, 'asdf'),
(69, 4, 28, 'is it finally working? :\')'),
(70, 8, 29, 'sfda'),
(71, 4, 29, '32');

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

DROP TABLE IF EXISTS `actors`;
CREATE TABLE IF NOT EXISTS `actors` (
  `actorID` int NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`actorID`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`actorID`, `first_name`, `last_name`) VALUES
(1, 'Joseph', 'Gordon-Levitt'),
(4, 'WAH!', '?WAH!'),
(8, 'Emma', 'Thompson');

-- --------------------------------------------------------

--
-- Stand-in structure for view `movieactors`
-- (See below for the actual view)
--
DROP VIEW IF EXISTS `movieactors`;
CREATE TABLE IF NOT EXISTS `movieactors` (
`movie_title` varchar(255)
,`movie_role` varchar(255)
,`first_name` varchar(255)
,`last_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

DROP TABLE IF EXISTS `movies`;
CREATE TABLE IF NOT EXISTS `movies` (
  `movieID` int NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `length` int DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `heroimg` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `released` date DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `isNews` int NOT NULL,
  `now_upcoming` int NOT NULL,
  `actor` int DEFAULT NULL,
  `tag` int DEFAULT NULL,
  PRIMARY KEY (`movieID`),
  KEY `actor` (`actor`),
  KEY `tag` (`tag`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movieID`, `title`, `length`, `description`, `poster`, `heroimg`, `trailer`, `released`, `director`, `isNews`, `now_upcoming`, `actor`, `tag`) VALUES
(1, 'Treasure Planet', 95, 'Jim Hawkins is a teenager who finds the map of a great treasure hidden by a space pirate. Together with some friends, he sets off in a large spaceship, shaped like a caravel, on his quest.', 'includes/assets/uploads/posters/treasure-planet.jpg', 'includes/assets/uploads/heroimgs/treasure-planet-bg.jpg', 'includes/assets/uploads/trailers/TreasurePlanetTrailer.mp4', '2002-11-29', 'Ron Clements', 1, 1, 1, 1),
(16, 'The Nightmare Before Christmas', 76, 'Jack Skellington, king of Halloween Town, discovers Christmas Town, but his attempts to bring Christmas to his home causes confusion.', 'includes/assets/uploads/posters/tnbc.jpg', 'includes/assets/uploads/heroimgs/Nightmare-Before-Christmas-2-release-date-story-details.jpg', 'includes/assets/uploads/trailers/TheNightmareBeforeChristmasTrailer.mp4', '1994-12-02', 'Henry Selick', 1, 2, NULL, NULL),
(17, 'Shrek', 90, 'A mean lord exiles fairytale creatures to the swamp of a grumpy ogre, who must go on a quest and rescue a princess for the lord in order to get his land back.', 'includes/assets/uploads/posters/shrek.jpg', 'includes/assets/uploads/heroimgs/shrek-heroimg.jpg', 'includes/assets/uploads/trailers/ShrekTrailer.mp4', '2001-09-07', 'Andrew Adamson & Vicky Jenson', 1, 2, NULL, NULL),
(18, 'The Witcher: Nightmare of the Wolf', 83, 'Escaping from poverty to become a witcher, Vesemir slays monsters for coin and glory, but when a new menace rises, he must face the demons of his past.', 'includes/assets/uploads/posters/twnotw.jpg', 'includes/assets/uploads/heroimgs/wolf-heroimg.jpg', 'includes/assets/uploads/trailers/wolfTrailer.mp4', '2021-08-23', 'Kwang Il Han', 1, 1, NULL, NULL),
(21, 'vbnc', 435, 'fgds', 'includes/assets/uploads/posters/21_1728022765550.jpg', 'includes/assets/uploads/heroimgs/1693228314234.png', '', '2024-11-06', 'fdgs', 0, 2, NULL, NULL),
(28, 'artest', 3, 'wer', '', '', '', '2024-10-09', '', 0, 1, NULL, NULL),
(29, 'still works? pls yes', 234, 'asdf', 'includes/assets/uploads/posters/1693228314234.png', 'includes/assets/uploads/heroimgs/1722510859817.png', 'includes/assets/uploads/trailers/ssstwitter.com_1683137401324.mp4', '2024-09-30', 'fads', 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movietags`
--

DROP TABLE IF EXISTS `movietags`;
CREATE TABLE IF NOT EXISTS `movietags` (
  `movie_tagID` int NOT NULL AUTO_INCREMENT,
  `movieID` int NOT NULL,
  `tagID` int NOT NULL,
  PRIMARY KEY (`movie_tagID`),
  KEY `movieID` (`movieID`),
  KEY `tagID` (`tagID`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `movietags`
--

INSERT INTO `movietags` (`movie_tagID`, `movieID`, `tagID`) VALUES
(1, 1, 1),
(35, 28, 1),
(36, 29, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `tagID` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `tagType` int NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`tagID`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`tagID`, `name`, `tagType`, `short_description`) VALUES
(1, 'Hand-Drawn', 1, 'Hand-drawn animation, also known as traditional animation, is a technique in which each frame is created by hand. It involves the process of drawing individual images, known as frames or cels, by skilled animators and then photographing or filming them in'),
(2, 'name-test-tag', 2, 'A short description for this test tag'),
(6, '5467', 3, 'bvnmb'),
(8, 'Stop Motion', 1, 'Stop motion animation is a technique in which physical objects or puppets are manipulated and photographed one frame at a time to create the illusion of movement when played back at a regular speed. This process involves posing and photographing the objec'),
(9, 'Computer 3D', 1, '3D Computer animation is a technique in which computers are used to generate animated sequences digitally. Unlike traditional hand-drawn animation, which involves creating individual frames by hand, computer animation relies on digital processes to genera'),
(10, 'Modern 2D', 1, 'tbd');

-- --------------------------------------------------------

--
-- Table structure for table `useraccounts`
--

DROP TABLE IF EXISTS `useraccounts`;
CREATE TABLE IF NOT EXISTS `useraccounts` (
  `userID` int NOT NULL AUTO_INCREMENT,
  `accountRank` int DEFAULT '1',
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`userID`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `useraccounts`
--

INSERT INTO `useraccounts` (`userID`, `accountRank`, `username`, `password`) VALUES
(1, 0, 'admin', '$2y$15$nm1n/tNyw6.Os0QL5IsQ9.8twjXF1kbbGU.N23.MlWaHzTaNK50py'),
(2, 1, 'user', '$2y$15$2BR2./R3hUQQwRdIu8ZJmug0NPKmaNgS4cG3GiXHs7GO4MtV0qJvu');

-- --------------------------------------------------------

--
-- Structure for view `movieactors`
--
DROP TABLE IF EXISTS `movieactors`;

DROP VIEW IF EXISTS `movieactors`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `movieactors`  AS SELECT `m`.`title` AS `movie_title`, `ar`.`role` AS `movie_role`, `a`.`first_name` AS `first_name`, `a`.`last_name` AS `last_name` FROM ((`movies` `m` join `actorrole` `ar` on((`m`.`movieID` = `ar`.`movieID`))) join `actors` `a` on((`ar`.`actorID` = `a`.`actorID`))) ORDER BY `m`.`title` ASC ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `actorrole`
--
ALTER TABLE `actorrole`
  ADD CONSTRAINT `actorrole_ibfk_1` FOREIGN KEY (`actorID`) REFERENCES `actors` (`actorID`),
  ADD CONSTRAINT `actorrole_ibfk_2` FOREIGN KEY (`movieID`) REFERENCES `movies` (`movieID`);

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`actor`) REFERENCES `actors` (`actorID`),
  ADD CONSTRAINT `movies_ibfk_2` FOREIGN KEY (`tag`) REFERENCES `tag` (`tagID`);

--
-- Constraints for table `movietags`
--
ALTER TABLE `movietags`
  ADD CONSTRAINT `movietags_ibfk_1` FOREIGN KEY (`movieID`) REFERENCES `movies` (`movieID`),
  ADD CONSTRAINT `movietags_ibfk_2` FOREIGN KEY (`tagID`) REFERENCES `tag` (`tagID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
