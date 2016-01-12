-- phpMyAdmin SQL Dump
-- version 4.2.12deb2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 22, 2015 at 11:39 PM
-- Server version: 5.5.44-0+deb8u1
-- PHP Version: 5.6.12-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `grivel_l_my_weblog`
--
CREATE DATABASE IF NOT EXISTS `grivel_l_my_weblog` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `grivel_l_my_weblog`;

-- --------------------------------------------------------

--
-- Table structure for table `allTags`
--

CREATE TABLE IF NOT EXISTS `allTags` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `idTicket` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `allTags`
--

INSERT INTO `allTags` (`id`, `name`, `idTicket`) VALUES
(48, 'Lorem', 47),
(49, 'nature', 47),
(52, 'nature', 48),
(54, 'Test', 49),
(55, 'Nature', 50),
(56, 'Video', 50),
(57, 'nature', 51),
(58, 'liens', 51),
(60, 'nature', 52),
(61, 'test', 53),
(62, 'test', 54),
(63, 'test', 55),
(64, 'Nature', 56),
(65, 'Naturel', 57),
(66, 'Nature', 58);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE IF NOT EXISTS `category` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `id_category` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `name`, `id_category`) VALUES
(2, 'yoooooooooooooooo', 2),
(4, 'Cinema', 4),
(5, 'Musique', 5),
(6, 'Informatique', 6),
(16, 'yo', 8),
(17, 'Loisirs', 9);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
`id` int(11) NOT NULL,
  `login` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `idTicket` int(11) NOT NULL,
  `createDate` datetime NOT NULL,
  `moderated` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `login`, `content`, `idTicket`, `createDate`, `moderated`) VALUES
(4, 'Lgm6', 'test', 42, '2015-11-20 12:47:30', 0),
(10, 'valbis', 'Superbe paysage !', 49, '2015-11-22 23:34:10', 0);

-- --------------------------------------------------------

--
-- Table structure for table `rights`
--

CREATE TABLE IF NOT EXISTS `rights` (
`id` int(11) NOT NULL,
  `whichRight` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `rights`
--

INSERT INTO `rights` (`id`, `whichRight`) VALUES
(1, 'Reader'),
(2, 'Author'),
(3, 'Admin');

-- --------------------------------------------------------

--
-- Table structure for table `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tag`
--

INSERT INTO `tag` (`id`, `name`) VALUES
(3, 'ahahah'),
(5, 'test'),
(6, 'okidoki'),
(22, 'Lorem'),
(23, 'nature'),
(24, 'Test'),
(25, 'Nature'),
(26, 'Video'),
(27, 'liens'),
(28, 'Naturel');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE IF NOT EXISTS `tickets` (
`id_ticket` int(11) NOT NULL,
  `contents` text NOT NULL,
  `login` varchar(255) NOT NULL,
  `create_date` datetime NOT NULL,
  `publish_date` datetime NOT NULL,
  `title` varchar(255) NOT NULL,
  `validation` int(11) NOT NULL DEFAULT '0',
  `id_category` int(11) NOT NULL,
  `chapo` text NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=59 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id_ticket`, `contents`, `login`, `create_date`, `publish_date`, `title`, `validation`, `id_category`, `chapo`) VALUES
(47, '<img alt="Nature" src="http://plantenaturel.p.l.pic.centerblog.net/o/d50297c4.jpg" height="123" width="164"><br>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \r\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \r\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \r\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \r\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \r\nmollit anim id est laborum.', 'Lgm6', '2015-11-22 22:26:40', '0000-00-00 00:00:00', 'Lorem', 1, 2, '<img alt="Nature" src="http://plantenaturel.p.l.pic.centerblog.net/o/d50297c4.jpg" height="123" width="164"><br>Lorem ipsum dolor sit amet, consectetu...'),
(48, '<p></p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim \r\nveniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea \r\ncommodo consequat. Duis aute irure dolor in reprehenderit in voluptate \r\nvelit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint \r\noccaecat cupidatat non proident, sunt in culpa qui officia deserunt \r\nmollit anim id est laborum.</p><p><img alt="Nature" src="http://3.bp.blogspot.com/_KU3zHcdckOE/TNg9RyoM_QI/AAAAAAAAABQ/EyCcXeysh-U/s1600/Nice-Nature-wallpaper-12.jpg" height="244" width="326"></p><p></p>', 'Lgm6', '2015-11-22 22:28:04', '0000-00-00 00:00:00', 'Lorem', 1, 4, '<p></p><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod \r\ntempor incididunt ut labore et dolore magna aliqua. Ut enim ad min...'),
(49, 'Lorem ipsum<font color="green"> dolor sit amet, consectetur </font>adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n <font color="blue">mollit </font>anim id est laborum.<br><p><img alt="Nature" src="http://3.bp.blogspot.com/_1qDeCH5KoGE/TSgfQSVE2MI/AAAAAAAAABQ/z7t867RfHdY/s1600/beautiful_nature_16196362.jpg" height="296" width="475"></p><br><p></p>', 'Lgm6', '2015-11-22 22:49:39', '0000-00-00 00:00:00', 'Couleurs', 1, 6, 'Lorem ipsum<font color="green"> dolor sit amet, consectetur </font>adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqu...'),
(50, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><img alt="Nature" src="http://4.bp.blogspot.com/-sjmSKwBglJk/T1HuHIK_jBI/AAAAAAAACkQ/9Y629MG8gl4/s1600/Nature-Desktop-Wallpaper.jpg" height="166" width="222"><br><iframe src="https://www.youtube.com/embed/rOZL8y_zvG8" style="width: 420px; height: 315px;"></iframe>', 'Lgm6', '2015-11-22 22:54:44', '0000-00-00 00:00:00', 'Video', 1, 9, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(51, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\n<a href="http://www.qwant.com">ex ea commodo co</a>nsequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="Nature" src="http://www.slowfamilyonline.com/wordpress/wp-content/uploads/2010/01/nature1.jpg" height="158" width="211"><br><br>', 'Lgm6', '2015-11-22 22:56:47', '0000-00-00 00:00:00', 'Lien', 1, 8, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(52, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.<img alt="Nature" src="http://3.bp.blogspot.com/_1qDeCH5KoGE/TSgfQSVE2MI/AAAAAAAAABQ/z7t867RfHdY/s1600/beautiful_nature_16196362.jpg" height="308" width="494"></p>', 'Lgm6', '2015-11-22 23:11:19', '0000-00-00 00:00:00', 'Nature', 1, 4, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(53, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="nature" src="http://4.bp.blogspot.com/-sjmSKwBglJk/T1HuHIK_jBI/AAAAAAAACkQ/9Y629MG8gl4/s1600/Nature-Desktop-Wallpaper.jpg" height="384" width="514">', 'Lgm6', '2015-11-22 23:15:20', '0000-00-00 00:00:00', 'Nature', 1, 5, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(54, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="nature" src="http://3.bp.blogspot.com/_KU3zHcdckOE/TNg9RyoM_QI/AAAAAAAAABQ/EyCcXeysh-U/s1600/Nice-Nature-wallpaper-12.jpg" height="341" width="455">', 'Lgm6', '2015-11-22 23:16:24', '0000-00-00 00:00:00', 'Nature', 1, 5, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(55, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="nature" src="http://3.bp.blogspot.com/_KU3zHcdckOE/TNg9RyoM_QI/AAAAAAAAABQ/EyCcXeysh-U/s1600/Nice-Nature-wallpaper-12.jpg" height="341" width="455">', 'Lgm6', '2015-11-22 23:17:01', '0000-00-00 00:00:00', 'Nature', 1, 5, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(56, '<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum."</p><img alt="Nature" src="http://3.bp.blogspot.com/_1qDeCH5KoGE/TSgfQSVE2MI/AAAAAAAAABQ/z7t867RfHdY/s1600/beautiful_nature_16196362.jpg" height="86" width="140">', 'Lgm6', '2015-11-22 23:17:25', '0000-00-00 00:00:00', 'Nature', 1, 5, '<p>"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim v...'),
(57, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="Nature" src="http://plantenaturel.p.l.pic.centerblog.net/o/d50297c4.jpg" height="318" width="424">', 'Lgm6', '2015-11-22 23:18:01', '0000-00-00 00:00:00', 'Nature', 1, 2, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...'),
(58, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip \r\nex ea commodo consequat. Duis aute irure dolor in reprehenderit in \r\nvoluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur \r\nsint occaecat cupidatat non proident, sunt in culpa qui officia deserunt\r\n mollit anim id est laborum.</p><p><br></p><img alt="Natuer" src="http://3.bp.blogspot.com/_1qDeCH5KoGE/TSgfQSVE2MI/AAAAAAAAABQ/z7t867RfHdY/s1600/beautiful_nature_16196362.jpg" height="294" width="471">', 'Lgm6', '2015-11-22 23:18:33', '0000-00-00 00:00:00', 'Nature', 1, 4, '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do \r\neiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad \r\nminim ve...');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `login` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `adress` varchar(255) NOT NULL,
  `age` int(11) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `rights` int(11) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password`, `firstname`, `lastname`, `login`, `email`, `avatar`, `adress`, `age`, `phone`, `rights`) VALUES
(14, '35ed5c4cb267ccccf2659d660afddb32', 'axel', 'bruneaux', 'Gazh', 'gazhrot@gmail.com', 'http://seeklogo.com/images/B/bmw-motorsport-logo-E35F07991E-seeklogo.com.gif', '11 rue st just', 20, '0609879097', 3),
(15, '66b74ec1c007227179270701c0500ffe', 'lajoie', 'ducon', 'bataclan', 'blabla@blalba.com', '', '', 0, '', 1),
(17, '5f4dcc3b5aa765d61d8327deb882cf99', 'hebdo', 'charlie', 'charlie', 'blabla@bla.com', 'images/avatar/default.png', '', 0, '', 2),
(18, '66b74ec1c007227179270701c0500ffe', 'Léo', 'Grivel', 'Lgm6', 'leo@grivel.com', '', '', 75, '3615', 3),
(19, 'e41d16afddb849ec572d5e9ab5697951', 'val', 'bensam', 'valbis', 'valentin.bensamon@epitech.eu', 'images/avatar/19.jpeg', '', 0, '', 3),
(20, '5f4dcc3b5aa765d61d8327deb882cf99', 'Charles', 'Dupont', 'kevin', 'oef@lfdcds.com', 'images/avatar/default.png', '', 0, '', 1),
(21, '66b74ec1c007227179270701c0500ffe', 'blablabla', 'blablabla', 'bensam', 'okok@ok.com', 'images/avatar/default.png', '42 route des poney', 42, '424242424242', 1),
(22, '66b74ec1c007227179270701c0500ffe', 'ducon', 'ducon', 'ducon', 'ducon@ducon.com', 'images/avatar/22.jpeg', '42 route des poney', 42, '424242424242', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `allTags`
--
ALTER TABLE `allTags`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rights`
--
ALTER TABLE `rights`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tag`
--
ALTER TABLE `tag`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
 ADD PRIMARY KEY (`id_ticket`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `allTags`
--
ALTER TABLE `allTags`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=67;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `rights`
--
ALTER TABLE `rights`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tag`
--
ALTER TABLE `tag`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
MODIFY `id_ticket` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=59;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=23;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
