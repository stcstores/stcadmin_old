-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2015 at 04:52 PM
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
-- Table structure for table `shipping_methods`
--

DROP TABLE IF EXISTS `shipping_methods`;
CREATE TABLE IF NOT EXISTS `shipping_methods` (
`id` int(11) NOT NULL,
  `value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Truncate table before insert `shipping_methods`
--

TRUNCATE TABLE `shipping_methods`;
--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `value`, `is_default`) VALUES
(1, 'Large Letter', 0),
(2, 'Tracked', 1),
(3, 'Courier', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `method` (`value`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
