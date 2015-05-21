-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2015 at 04:53 PM
-- Server version: 5.6.21
-- PHP Version: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `stcadministration`
--

-- --------------------------------------------------------

--
-- Table structure for table `special_characters`
--

DROP TABLE IF EXISTS `special_characters`;
CREATE TABLE IF NOT EXISTS `special_characters` (
`id` int(11) NOT NULL,
  `sc` char(1) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(20) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Truncate table before insert `special_characters`
--

TRUNCATE TABLE `special_characters`;
--
-- Dumping data for table `special_characters`
--

INSERT INTO `special_characters` (`id`, `sc`, `name`) VALUES
(1, '"', 'Double Quote'),
(2, '''', 'Single Quote'),
(3, ';', 'Semicolon'),
(4, '~', 'Tilde'),
(5, '<', 'Less Than'),
(6, '>', 'Greater Than'),
(7, '!', 'Exclaimation Mark'),
(8, '?', 'Question Mark'),
(9, '/', 'Forward Slash'),
(10, '\\', 'Backslash');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `special_characters`
--
ALTER TABLE `special_characters`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `special_characters`
--
ALTER TABLE `special_characters`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
