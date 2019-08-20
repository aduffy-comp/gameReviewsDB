-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 18, 2018 at 05:34 PM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `modreviews2`
--
CREATE DATABASE IF NOT EXISTS `modreviews2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `modreviews2`;

-- --------------------------------------------------------

--
-- Table structure for table `developer`
--

CREATE TABLE `developer` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nationality` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `founder` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dateEstablished` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `developer`
--

INSERT INTO `developer` (`id`, `name`, `nationality`, `founder`, `dateEstablished`) VALUES
(1, 'Epic Games', 'United States', 'Tim Sweeney', '1991-10-01'),
(10, 'Valve Corporation', 'United States', 'Gabe Newell', '1996-08-24'),
(11, 'id Software', 'United States', 'Tom Hall', '1991-02-01'),
(12, 'UnrealSP CoW Team', 'Worldwide', 'Z-enzyme at UnrealSP', '2011-06-18'),
(13, 'Bethesda Game Studios', 'United States', 'Todd Howard', '2001-01-01'),
(14, 'Mojang', 'Sweden', 'Markus Perrson', '2009-05-20'),
(15, 'Gabe\'s Love Tub', 'Unknown', 'Unknown', '2007-01-01'),
(16, 'FAKEFACTORY', 'Germany', 'Unknown', '2009-08-24'),
(17, 'Black Widow Games', 'Unknown', 'Unknown', '1997-01-01');

--
-- Triggers `developer`
--
DELIMITER $$
CREATE TRIGGER `developer_insert_test` BEFORE INSERT ON `developer` FOR EACH ROW BEGIN
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	/*not sure why YY-MM-DD rejection is not working*/
	IF NEW.dateEstablished LIKE '__-__-__' OR CAST(NEW.dateEstablished AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'developer.dateEstablished is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `developer_update_test` BEFORE UPDATE ON `developer` FOR EACH ROW BEGIN
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	/*not sure why YY-MM-DD rejection is not working*/
	IF NEW.dateEstablished LIKE '__-__-__' OR CAST(NEW.dateEstablished AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'developer.dateEstablished is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `game`
--

CREATE TABLE `game` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `releaseDate` date NOT NULL,
  `operatingSys` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `diskSpace` int(6) DEFAULT NULL,
  `minCPU` int(4) DEFAULT NULL,
  `minRAM` int(6) DEFAULT NULL,
  `minGPU` int(6) DEFAULT NULL,
  `classification` enum('U','3','7','PG','12','15','16','18') CHARACTER SET latin1 DEFAULT NULL,
  `myReview` int(3) DEFAULT NULL,
  `criticReview` int(3) DEFAULT NULL,
  `developerID` int(11) DEFAULT NULL,
  `publisherID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `game`
--

INSERT INTO `game` (`id`, `name`, `releaseDate`, `operatingSys`, `diskSpace`, `minCPU`, `minRAM`, `minGPU`, `classification`, `myReview`, `criticReview`, `developerID`, `publisherID`) VALUES
(1, 'Unreal', '1998-05-22', 'Windows 9x', 500, 166, 16, 2, '16', 90, 88, 1, 1),
(49, 'Half-Life', '1998-11-19', 'Windows 9x', 450, 133, 24, 2, '16', 88, 96, 10, 2),
(50, 'Half-Life 2', '2004-11-16', 'Windows XP', 6500, 1700, 512, 32, '15', 91, 96, 10, 2),
(51, 'Portal 2', '2011-04-19', 'Windows XP', 8192, 2000, 2048, 128, '12', 90, 95, 10, 3),
(52, 'DOOM', '2016-05-13', 'Windows 7 64-Bit', 55000, 3100, 8192, 2048, '18', 82, 85, 11, 4),
(53, 'Unreal Tournament', '1999-11-30', 'Windows 9x', 200, 233, 64, 2, '16', 90, 92, 1, 1),
(54, 'Unreal Tournament 2004', '2004-03-16', 'Windows 2000', 5600, 1000, 128, 32, '16', 85, 93, 1, 1),
(55, 'Unreal Tournament 3', '2007-11-19', 'Windows XP', 8192, 2000, 512, 128, '18', 78, 83, 1, 5),
(56, 'Doom II', '1994-09-30', 'MS-DOS', 20, 66, 4, 1, '15', 90, 83, 11, 1),
(57, 'Counter-Strike: Global Offensive', '2012-08-21', 'Windows XP', 15000, 2400, 2048, 256, '18', NULL, 83, 10, 3),
(58, 'The Elder Scrolls: Skyrim Special Edition', '2016-10-28', 'Windows 7', 12000, 2666, 8192, 1024, '18', NULL, 74, 13, 4),
(59, 'Minecraft', '2011-11-18', 'Universal (Java-Based)', 1024, 3100, 2048, 256, '7', 80, 93, 14, 7);

--
-- Triggers `game`
--
DELIMITER $$
CREATE TRIGGER `game_insert_test` BEFORE INSERT ON `game` FOR EACH ROW BEGIN
	/*casting allows for the value being stored to be compared*/
	IF CAST(NEW.diskSpace AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.diskSpace is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minCPU AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minCPU is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minRAM AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minRAM is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minGPU AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minGPU is <= 0 or invalid';
	END IF;
	
	
	/*cast overwrites NEW.columnName, so we do this last*/
	IF NEW.myReview > 100 OR CAST(NEW.myReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.myReview is out of bounds or invalid';
	END IF;
	IF NEW.criticReview > 100 OR CAST(NEW.criticReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.criticReview is out of bounds or invalid';
	END IF;
	
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	IF NEW.releaseDate LIKE '__-__-__' OR CAST(NEW.releaseDate AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.releaseDate is of incorrect format';
	END IF;
	
	/*this is the only way to check before committing to the database*/
	IF NOT (NEW.classification = 'U'
		OR NEW.classification = 'PG'
		OR NEW.classification = '3'
		OR NEW.classification = '7'
		OR NEW.classification = '12'
		OR NEW.classification = '15'
		OR NEW.classification = '16'
		OR NEW.classification = '18') THEN
			SIGNAL SQLSTATE '12345'
				SET MESSAGE_TEXT = 'game.classification is not a supported value';
	END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `game_update_test` BEFORE UPDATE ON `game` FOR EACH ROW BEGIN
	/*casting allows for the value being stored to be compared*/
	IF CAST(NEW.diskSpace AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.diskSpace is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minCPU AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minCPU is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minRAM AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minRAM is <= 0 or invalid';
	END IF;
	IF CAST(NEW.minGPU AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.minGPU is <= 0 or invalid';
	END IF;
	
	
	/*cast overwrites NEW.columnName, so we do this last*/
	IF NEW.myReview > 100 OR CAST(NEW.myReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.myReview is out of bounds or invalid';
	END IF;
	IF NEW.criticReview > 100 OR CAST(NEW.criticReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.criticReview is out of bounds or invalid';
	END IF;
	
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	IF NEW.releaseDate LIKE '__-__-__' OR CAST(NEW.releaseDate AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'game.releaseDate is of incorrect format';
	END IF;
	
	/*this is the only way to check before committing to the database*/
	IF NOT (NEW.classification = 'U'
		OR NEW.classification = 'PG'
		OR NEW.classification = '3'
		OR NEW.classification = '7'
		OR NEW.classification = '12'
		OR NEW.classification = '15'
		OR NEW.classification = '16'
		OR NEW.classification = '18') THEN
			SIGNAL SQLSTATE '12345'
				SET MESSAGE_TEXT = 'game.classification is not a supported value';
	END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `mods`
--

CREATE TABLE `mods` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `releaseDate` date NOT NULL,
  `contentType` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `diskSpace` int(6) DEFAULT NULL,
  `myReview` int(3) DEFAULT NULL,
  `modLength` time NOT NULL,
  `modLink` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `developerID` int(11) DEFAULT NULL,
  `gameID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `mods`
--

INSERT INTO `mods` (`id`, `name`, `releaseDate`, `contentType`, `diskSpace`, `myReview`, `modLength`, `modLink`, `developerID`, `gameID`) VALUES
(1, 'Chronicles of Weedrow', '2011-08-30', 'Maps, Gameplay', 57, 80, '01:20:00', 'http://www.moddb.com/mods/chronicles-of-weedrow', 12, 1),
(74, 'Point of View', '2003-08-20', 'Maps, Gameplay, Sound, Graphics', 30, 75, '01:40:00', 'http://www.example.com/pov-mod', 1, 49),
(75, 'Azure Sheep', '2002-01-20', 'Maps, Gameplay, Sound, Graphics', 30, 63, '01:10:00', 'http://www.example.com/azure', 1, 49),
(76, 'A really horrible HL mod', '2015-09-11', 'Maps, Gameplay, Sound, Graphics, Joke Mod', 30, 78, '01:10:00', 'link-removed', 1, 49),
(77, 'GoldenEye: Source', '2005-12-20', 'Maps, Gameplay, Sound, Graphics, Multiplayer', 30, 77, '10:00:00', 'http://www.moddb.com/mods/goldeneye-source', 1, 50),
(78, 'Portal Stories: Mel', '2015-06-25', 'Maps, Gameplay, Sound, Graphics', 11000, 88, '03:00:00', 'http://store.steampowered.com/app/317400/Portal_Stories_Mel/', 1, 51),
(79, 'Hell\'s Space', '2016-12-20', 'Maps, Snapmap', 100, 70, '00:20:00', 'https://doom.com/en-gb/snapmap/Nrtpmqu3', 1, 52),
(80, 'Missing Information', '2011-04-01', 'Maps, Gameplay, Sound, Textures, Story', 1000, 80, '01:15:00', 'http://www.moddb.com/mods/missing-information', 15, 50),
(81, 'FakeFactory Cinematic Mod', '2005-12-12', 'Textures, Models', 5000, NULL, '10:00:00', 'http://www.moddb.com/mods/fakefactory-cinematic-mod', 16, 50),
(82, 'They Hunger', '1999-11-29', 'Maps, Gameplay, Models, Sounds, Textures', 100, 85, '02:00:00', 'http://www.moddb.com/mods/they-hunger', 17, 49);

--
-- Triggers `mods`
--
DELIMITER $$
CREATE TRIGGER `mods_insert_test` BEFORE INSERT ON `mods` FOR EACH ROW BEGIN
	/*casting allows for the value being stored to be compared*/
	IF CAST(NEW.diskSpace AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.diskSpace is <= 0 or invalid';
	END IF;
	/*cast overwrites NEW.columnName, so we do this last*/
	IF NEW.myReview > 100 OR CAST(NEW.myReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.myReview is out of bounds or invalid';
	END IF;
	/*only accept http and https links*/
	IF NEW.modLink NOT LIKE 'http://%.%' AND NEW.modLink NOT LIKE 'https://%.%' THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.modLink URL format invalid';
	END IF;
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	IF NEW.releaseDate LIKE '__-__-__' OR CAST(NEW.releaseDate AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.releaseDate is of incorrect format';
	END IF;
	/*reject invalid times*/
	IF CAST(NEW.modLength AS TIME) = "00:00:00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.modLength is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `mods_update_test` BEFORE UPDATE ON `mods` FOR EACH ROW BEGIN
	/*casting allows for the value being stored to be compared*/
	IF CAST(NEW.diskSpace AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.diskSpace is <= 0 or invalid';
	END IF;
	/*cast overwrites NEW.columnName, so we do this last*/
	IF NEW.myReview > 100 OR CAST(NEW.myReview AS INT) <= 0 THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.myReview is out of bounds or invalid';
	END IF;
	/*only accept http and https links*/
	IF NEW.modLink NOT LIKE 'http://%.%' AND NEW.modLink NOT LIKE 'https://%.%' THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.modLink URL format invalid';
	END IF;
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	IF NEW.releaseDate LIKE '__-__-__' OR CAST(NEW.releaseDate AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.releaseDate is of incorrect format';
	END IF;
	/*reject invalid times*/
	IF CAST(NEW.modLength AS TIME) = "00:00:00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'mods.modLength is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nationality` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `founder` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `dateEstablished` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`id`, `name`, `nationality`, `founder`, `dateEstablished`) VALUES
(1, 'Atari, Inc.', 'United States', 'Ron Chaimowitz', '1993-02-01'),
(2, 'Sierra Entertainment, Inc.', 'United States', 'Ken Williams', '1979-01-01'),
(3, 'Valve Corporation', 'United States', 'Gabe Newell', '1996-08-24'),
(4, 'Bethesda Softworks', 'United States', 'Christopher Weaver', '1986-06-28'),
(5, 'Midway Games', 'United States', 'Henry Ross', '1988-01-01'),
(7, 'Mojang', 'Sweden', 'Markus Perrson', '2009-05-20');

--
-- Triggers `publisher`
--
DELIMITER $$
CREATE TRIGGER `publisher_insert_test` BEFORE INSERT ON `publisher` FOR EACH ROW BEGIN
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	/*not sure why YY-MM-DD rejection is not working*/
	IF NEW.dateEstablished LIKE '__-__-__' OR CAST(NEW.dateEstablished AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'publisher.dateEstablished is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `publisher_update_test` BEFORE UPDATE ON `publisher` FOR EACH ROW BEGIN
	/*reject YY-MM-DD due to abiguity, as well as all unsupported formats*/
	/*not sure why YY-MM-DD rejection is not working*/
	IF NEW.dateEstablished LIKE '__-__-__' OR CAST(NEW.dateEstablished AS DATE) = "0000-00-00" THEN
		SIGNAL SQLSTATE '12345'
			SET MESSAGE_TEXT = 'publisher.dateEstablished is of incorrect format';
	END IF;
	
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `reviewtext` text NOT NULL,
  `reviewimg` varchar(255) DEFAULT NULL,
  `rating` tinyint(1) UNSIGNED NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `gameid` int(11) DEFAULT NULL,
  `publisherid` int(11) DEFAULT NULL,
  `modid` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `title`, `reviewtext`, `reviewimg`, `rating`, `userid`, `gameid`, `publisherid`, `modid`) VALUES
(1, 'Test review 001', 'This is a test for the review display function. It should appear on the website when using the search function for the mod \"Portal Stories: Mel\" or the game \"Portal 2\". An image should appear below this text.', 'images/review/test001.png', 9, 1, 51, 3, 78),
(2, 'Test Review 002', 'This is the second test review. Not much to look at, but it is useful to test website layout.\r\n\r\nThis review should appear as two paragraphs.', 'images/review/test002.png', 4, 4, 49, 2, 76),
(3, 'Test Review 3', 'This is a third review test, made to check layout wrapping on smaller screens. Again, this is a dummy review.\r\n\r\nParagraph 2\r\n\r\nParagraph 3', 'images/review/test003.png', 6, 6, 50, 3, 77),
(5, 'Unique mod, but mediocre execution', 'Point of View is a unique take on the Half-Life universe. You play as a Vortigaunt from Xen who needs to rescue his experiment from the hands of researchers in orange suits (sound familiar?). I won&#39;t spoil the details, but the story is great.\r\n\r\nMap design is annoying: most spaces, particularly the Xen research lab, are very tight and maze-like. This makes orientation and combat difficult. Your main method of attacks are striking with your clawed arms or charging electric attacks. The latter is the only way of healing yourself in the game: HEV chargers and health packs are off-limits, as are all human weapons. You can, however, pick up some of the Xen wildlife (including some cut from Half-Life), and use them against your enemies.\r\n\r\nOverall, Point of View is a mod I&#39;d recommend, not because it has great gameplay, but because of the unique insight into the world of Half-Life, one not yet explored officially.', 'images/review/35724.jpg', 7, 9, 49, 2, 74),
(6, 'Test4', 'Testing the review page to ensure it works', 'images/review/4million-2.png', 1, 9, 1, 1, 1),
(7, 'Funny if you are immature', 'Over the top, meme references, hilarious traps... and it was released on a certain date. Need I say more?\r\n\r\nAll right, I will: you&#39;re staring at the game&#39;s title screen... or at least would be, if it was not censored.', 'images/review/hl_2013-12-15_14-27-15-33.png', 9, 9, 49, 2, 76),
(10, 'JScript injection test', 'This review contains embedded JavaScript, which should not be executed on the client. If it does, string sanitation is not working. alert(&#34;JScript inject successful!&#34;);', 'images/review/modern-art.png', 2, 9, 50, 2, 77),
(13, 'A unique mod for an underappreciated game', 'Unreal is one of those games that everyone knows because of its spin-offs and related game engine technology, yet few have played the game itself. Perhaps it is for this reason that mods for this game are much less common than its competition at the time, Half-Life. Despite this, there are still a few gems under its belt, and Chronicles of Weedrow is certainly one of them. Created by collaboration on the UnrealSP forums, each room or corridor of a map was created by one person, and then stitched together. Whilst this sounds chaotic, the results are stunning.\r\n\r\nThe mod primarily involves storytelling rather than combat: only a few inventory items from Unreal make it into this game. In a sense it plays more like a point-and-click adventure game rather than a first-person shooter. The emphasis is on the environment, rather than the action; the assets from Unreal lend themselves to this marvellously.\r\n\r\nIt got a sequel a few years later, albeit unfinished. It is an ambitious departure from the previous mod, sporting a new user interface, journal system and new puzzle solving mechanics. The story is more enthralling than before (unfortunately the unfinished nature leaves the player on a cliffhanger). Unfortunately, it does not run well on slower computers: the age of Unreal Engine 1 clearly shows here, struggling with complex, detailed environments.\r\n\r\nOne last note: you&#39;ll need the excellent unofficial 227i patch to run both mods.', 'images/review/5ANq98s.jpg', 9, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `userpass` varchar(255) NOT NULL,
  `creationdate` datetime NOT NULL,
  `userbanned` tinyint(1) NOT NULL DEFAULT '0',
  `userIsAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `profileimg` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `userpass`, `creationdate`, `userbanned`, `userIsAdmin`, `profileimg`) VALUES
(1, 'TheBigBoss', 'thebigboss@example.co.uk', '$2y$10$lqqHTAMhlLtT0uQvtI/GjebBfhhMahPiccB.KlVb5zctQNfHyKS1q', '2018-04-24 16:17:15', 0, 1, '20170209_110839.jpg'),
(2, 's', 's@s', '$2y$10$cbuyTb3CSXWtWn0hKKYR.uvcAvmG6HQQSI.jEs2bV6.jb8Zmjft52', '2018-05-01 16:50:15', 1, 0, ''),
(4, 'IsleOfRatchet', 'varkhar@example.se', '$2y$10$d9XHTWqJ0rTinQ15s/HxN.FY8lx4/PpB8Fs1YfRpeBjWqcw6zkb8q', '2018-05-01 17:04:19', 1, 0, 'wut.png'),
(6, 'PeachySnake', 'goodbuoy@example.ca', '$2y$10$L6OWaUaazg03BWR.kZvQoOuumMiU2TmIa4pvtzYAwgS84Q0dB2Jtq', '2018-05-01 17:15:42', 0, 0, ''),
(7, 'Chance62', 'bigboymeme@example.net', '$2y$10$WF2T1hCx1sVuGBa/mmDMHO2nClqhxkXGcmpZi4FzC5Rcq2NIU1aJS', '2018-05-01 17:17:10', 0, 0, ''),
(8, 'Test007', 'bond@example.co.uk', '$2y$10$crQ.dSkSZB4YN1UGyM6sVOBkkSj6LosMBeTioU0eTGEmJZbUOpWgK', '2018-05-01 17:20:26', 0, 0, 'posthell.gif'),
(9, 'TheRealBoss', 'therealboss@example.ru', '$2y$10$Ll0kI1AuZXEKDQVSW.r9m.lcbs/BcT5zyAbUCjYGiIoE2qLmv4Uhq', '2018-05-13 16:20:03', 0, 0, 'images/user/1520150361.sofiespangenberg_ratchet___angela.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `developer`
--
ALTER TABLE `developer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `game`
--
ALTER TABLE `game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developerID` (`developerID`),
  ADD KEY `publisherID` (`publisherID`);

--
-- Indexes for table `mods`
--
ALTER TABLE `mods`
  ADD PRIMARY KEY (`id`),
  ADD KEY `developerID` (`developerID`),
  ADD KEY `gameID` (`gameID`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviewtext` (`reviewtext`(10)),
  ADD KEY `userid` (`userid`),
  ADD KEY `gameid` (`gameid`),
  ADD KEY `publisherid` (`publisherid`),
  ADD KEY `modid` (`modid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `developer`
--
ALTER TABLE `developer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `game`
--
ALTER TABLE `game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT for table `mods`
--
ALTER TABLE `mods`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=83;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `game`
--
ALTER TABLE `game`
  ADD CONSTRAINT `game_ibfk_1` FOREIGN KEY (`developerID`) REFERENCES `developer` (`id`),
  ADD CONSTRAINT `game_ibfk_2` FOREIGN KEY (`publisherID`) REFERENCES `publisher` (`id`);

--
-- Constraints for table `mods`
--
ALTER TABLE `mods`
  ADD CONSTRAINT `mods_ibfk_1` FOREIGN KEY (`developerID`) REFERENCES `developer` (`id`),
  ADD CONSTRAINT `mods_ibfk_2` FOREIGN KEY (`gameID`) REFERENCES `game` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`userid`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`gameid`) REFERENCES `game` (`id`),
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`publisherid`) REFERENCES `publisher` (`id`),
  ADD CONSTRAINT `reviews_ibfk_4` FOREIGN KEY (`modid`) REFERENCES `mods` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
