-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 06, 2024 at 04:05 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CinemaDB`
--

-- --------------------------------------------------------

--
-- Table structure for table `actorrole`
--

USE caypw6z0e_cinemadb;

CREATE TABLE `actorrole` (
  `actor_roleID` int(11) NOT NULL,
  `actorID` int(11) NOT NULL,
  `movieID` int(11) NOT NULL,
  `role` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actorrole`
--

INSERT INTO `actorrole` (`actor_roleID`, `actorID`, `movieID`, `role`) VALUES
(1, 1, 1, 'Jim Hawkins'),
(2, 8, 1, 'Captain Amelia'),
(74, 4, 29, '32'),
(75, 8, 29, 'sfda'),
(92, 4, 32, 'fads'),
(93, 10, 32, 'asfd'),
(96, 4, 33, 'asfd'),
(97, 10, 33, 'sadffsda'),
(103, 10, 34, 'asfd'),
(104, 4, 35, '34dfg');

-- --------------------------------------------------------

--
-- Table structure for table `actors`
--

CREATE TABLE `actors` (
  `actorID` int(11) NOT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `last_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `actors`
--

INSERT INTO `actors` (`actorID`, `first_name`, `last_name`) VALUES
(1, 'Joseph', 'Gordon-Levitt'),
(4, 'WAH!', '?WAH!'),
(8, 'Emma', 'Thompson'),
(10, 'test', 'test');

-- --------------------------------------------------------

--
-- Stand-in structure for view `movieactors`
-- (See below for the actual view)
--
CREATE TABLE `movieactors` (
`movie_title` varchar(255)
,`movie_role` varchar(255)
,`first_name` varchar(255)
,`last_name` varchar(255)
);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movieID` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `length` int(11) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `poster` varchar(255) DEFAULT NULL,
  `heroimg` varchar(255) DEFAULT NULL,
  `trailer` varchar(255) DEFAULT NULL,
  `released` date DEFAULT NULL,
  `director` varchar(255) DEFAULT NULL,
  `isNews` int(11) NOT NULL,
  `now_upcoming` int(11) NOT NULL,
  `actor` int(11) DEFAULT NULL,
  `tag` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movieID`, `title`, `length`, `description`, `poster`, `heroimg`, `trailer`, `released`, `director`, `isNews`, `now_upcoming`, `actor`, `tag`) VALUES
(1, 'Treasure Planet', 95, 'Jim Hawkins is a teenager who finds the map of a great treasure hidden by a space pirate. Together with some friends, he sets off in a large spaceship, shaped like a caravel, on his quest.', 'includes/assets/uploads/posters/treasure-planet.jpg', 'includes/assets/uploads/heroimgs/treasure-planet-bg.jpg', 'includes/assets/uploads/trailers/TreasurePlanetTrailer.mp4', '2002-11-29', 'Ron Clements', 1, 1, 1, 1),
(16, 'The Nightmare Before Christmas', 76, 'Jack Skellington, king of Halloween Town, discovers Christmas Town, but his attempts to bring Christmas to his home causes confusion.', 'includes/assets/uploads/posters/tnbc.jpg', 'includes/assets/uploads/heroimgs/Nightmare-Before-Christmas-2-release-date-story-details.jpg', 'includes/assets/uploads/trailers/TheNightmareBeforeChristmasTrailer.mp4', '1994-12-02', 'Henry Selick', 1, 2, NULL, NULL),
(17, 'Shrek', 90, 'A mean lord exiles fairytale creatures to the swamp of a grumpy ogre, who must go on a quest and rescue a princess for the lord in order to get his land back.', 'includes/assets/uploads/posters/shrek.jpg', 'includes/assets/uploads/heroimgs/shrek-heroimg.jpg', 'includes/assets/uploads/trailers/ShrekTrailer.mp4', '2001-09-07', 'Andrew Adamson & Vicky Jenson', 1, 2, NULL, NULL),
(18, 'The Witcher: Nightmare of the Wolf', 83, 'Escaping from poverty to become a witcher, Vesemir slays monsters for coin and glory, but when a new menace rises, he must face the demons of his past.', 'includes/assets/uploads/posters/twnotw.jpg', 'includes/assets/uploads/heroimgs/wolf-heroimg.jpg', 'includes/assets/uploads/trailers/wolfTrailer.mp4', '2021-08-23', 'Kwang Il Han', 1, 1, NULL, NULL),
(21, 'vbnc', 435, 'fgds', 'includes/assets/uploads/posters/21_1728022765550.jpg', 'includes/assets/uploads/heroimgs/1693228314234.png', '', '2024-11-06', 'fdgs', 0, 2, NULL, NULL),
(29, 'still works? pls yes', 234, 'asdf edit', 'includes/assets/uploads/posters/1693228314234.png', 'includes/assets/uploads/heroimgs/1722510859817.png', 'includes/assets/uploads/trailers/ssstwitter.com_1683137401324.mp4', '2024-09-30', 'fads', 0, 2, NULL, NULL),
(32, 'test', 32, 'sadf', 'includes/assets/uploads/posters/32_logo.png', 'includes/assets/uploads/heroimgs/32_logo-step1.png', 'includes/assets/uploads/trailers/32_santest.mp4', '2024-10-27', 'fdsa', 0, 2, NULL, NULL),
(33, 'test', 4, 'sdra', 'includes/assets/uploads/posters/33_Brother.mp3', 'includes/assets/uploads/heroimgs/33_crush_me_fire.opus', 'includes/assets/uploads/trailers/33__7c4d17e0-4b91-4222-8c68-852576c97d92.jpeg', '2024-10-27', 'asfd', 0, 2, NULL, NULL),
(34, 'new test', 32, 'sdfa', 'includes/assets/uploads/posters/34_logo.png', 'includes/assets/uploads/heroimgs/34_3840x2160.png', '', '2024-10-27', 'sdfa', 0, 1, NULL, NULL),
(35, 'dfsg', 43, 'gdsf', 'includes/assets/uploads/posters/_7c4d17e0-4b91-4222-8c68-852576c97d92.jpeg', 'includes/assets/uploads/heroimgs/logo.png', 'includes/assets/uploads/trailers/santest.mp4', '2024-12-02', 'sgdf', 0, 2, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movietags`
--

CREATE TABLE `movietags` (
  `movie_tagID` int(11) NOT NULL,
  `movieID` int(11) NOT NULL,
  `tagID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movietags`
--

INSERT INTO `movietags` (`movie_tagID`, `movieID`, `tagID`) VALUES
(1, 1, 1),
(38, 29, 6),
(63, 32, 2),
(64, 32, 6),
(65, 32, 8),
(72, 33, 1),
(73, 33, 2),
(74, 33, 6),
(75, 33, 8),
(76, 33, 9),
(77, 33, 10),
(83, 34, 6),
(84, 35, 8),
(85, 35, 2),
(86, 35, 6);

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE `tag` (
  `tagID` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tagType` int(11) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`tagID`, `name`, `tagType`, `short_description`) VALUES
(1, 'Hand-Drawn', 1, 'Hand-drawn animation, also known as traditional animation, is a technique in which each frame is created by hand. It involves the process of drawing individual images, known as frames or cels, by skilled animators and then photographing or filming them in'),
(2, 'name-test-tag', 2, 'A short description for this test tag'),
(6, 'washtest', 2, 'asdfasfd432423'),
(8, 'Stop Motion', 1, 'Stop motion animation is a technique in which physical objects or puppets are manipulated and photographed one frame at a time to create the illusion of movement when played back at a regular speed. This process involves posing and photographing the objec'),
(9, 'Computer 3D', 1, '3D Computer animation is a technique in which computers are used to generate animated sequences digitally. Unlike traditional hand-drawn animation, which involves creating individual frames by hand, computer animation relies on digital processes to genera'),
(10, 'Modern 2D', 1, 'tbd');

-- --------------------------------------------------------

--
-- Table structure for table `useraccounts`
--

CREATE TABLE `useraccounts` (
  `userID` int(11) NOT NULL,
  `accountRank` int(11) DEFAULT 1,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `useraccounts`
--

INSERT INTO `useraccounts` (`userID`, `accountRank`, `username`, `password`) VALUES
(1, 0, 'admin', '$2y$15$nm1n/tNyw6.Os0QL5IsQ9.8twjXF1kbbGU.N23.MlWaHzTaNK50py'),
(2, 1, 'user', '$2y$15$2BR2./R3hUQQwRdIu8ZJmug0NPKmaNgS4cG3GiXHs7GO4MtV0qJvu'),
(5, 1, 'testspechars', '$2y$15$Z796f5H4ayEBIQOfI5xe5uVQgkMqIse5GcStcnFks51ysBR/IxyBK'),
(6, 1, 'newtest', '$2y$15$sR9NgFQTFzn6GhyfFWzPt.oHNphFp0BkfC4/vAERB2pWH2xzaQaFG');

-- --------------------------------------------------------

--
-- Structure for view `movieactors`
--
DROP TABLE IF EXISTS `movieactors`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `movieactors`  AS SELECT `m`.`title` AS `movie_title`, `ar`.`role` AS `movie_role`, `a`.`first_name` AS `first_name`, `a`.`last_name` AS `last_name` FROM ((`movies` `m` join `actorrole` `ar` on(`m`.`movieID` = `ar`.`movieID`)) join `actors` `a` on(`ar`.`actorID` = `a`.`actorID`)) ORDER BY `m`.`title` ASC ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `actorrole`
--
ALTER TABLE `actorrole`
  ADD PRIMARY KEY (`actor_roleID`),
  ADD KEY `actorID` (`actorID`),
  ADD KEY `movieID` (`movieID`);

--
-- Indexes for table `actors`
--
ALTER TABLE `actors`
  ADD PRIMARY KEY (`actorID`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movieID`),
  ADD KEY `actor` (`actor`),
  ADD KEY `tag` (`tag`);

--
-- Indexes for table `movietags`
--
ALTER TABLE `movietags`
  ADD PRIMARY KEY (`movie_tagID`),
  ADD KEY `movieID` (`movieID`),
  ADD KEY `tagID` (`tagID`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
  ADD PRIMARY KEY (`tagID`);

--
-- Indexes for table `useraccounts`
--
ALTER TABLE `useraccounts`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `actorrole`
--
ALTER TABLE `actorrole`
  MODIFY `actor_roleID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `actors`
--
ALTER TABLE `actors`
  MODIFY `actorID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movieID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `movietags`
--
ALTER TABLE `movietags`
  MODIFY `movie_tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
  MODIFY `tagID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `useraccounts`
--
ALTER TABLE `useraccounts`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
