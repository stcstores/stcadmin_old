-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: May 21, 2015 at 04:49 PM
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
CREATE DATABASE IF NOT EXISTS `stcadministration` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `stcadministration`;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

DROP TABLE IF EXISTS `departments`;
CREATE TABLE IF NOT EXISTS `departments` (
`id` int(11) NOT NULL,
  `value` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `is_default` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `images`
--

DROP TABLE IF EXISTS `images`;
CREATE TABLE IF NOT EXISTS `images` (
`id` int(11) NOT NULL,
  `sku` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `is_primary` tinyint(1) NOT NULL,
  `extension` varchar(4) COLLATE utf8_unicode_ci NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `login`
--

DROP TABLE IF EXISTS `login`;
CREATE TABLE IF NOT EXISTS `login` (
`user_id` int(11) NOT NULL,
  `username` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `password_hash` char(60) COLLATE utf8_unicode_ci NOT NULL,
  `admin` tinyint(1) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `new_product_form_field`
--

DROP TABLE IF EXISTS `new_product_form_field`;
CREATE TABLE IF NOT EXISTS `new_product_form_field` (
`id` int(11) NOT NULL,
  `field_title` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `field_type` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `field_name` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `csv` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `field_description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` int(11) NOT NULL,
  `page` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) DEFAULT NULL,
  `default` varchar(25) COLLATE utf8_unicode_ci DEFAULT NULL,
  `required` tinyint(1) NOT NULL,
  `regex` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tooltip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

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

-- --------------------------------------------------------

--
-- Table structure for table `skus`
--

DROP TABLE IF EXISTS `skus`;
CREATE TABLE IF NOT EXISTS `skus` (
`id` int(11) NOT NULL,
  `sku` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=17790 DEFAULT CHARSET=utf8;

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
-- Indexes for dumped tables
--

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `department_name` (`value`);

--
-- Indexes for table `images`
--
ALTER TABLE `images`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `login`
--
ALTER TABLE `login`
 ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `new_product_form_field`
--
ALTER TABLE `new_product_form_field`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
 ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `method` (`value`);

--
-- Indexes for table `skus`
--
ALTER TABLE `skus`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `special_characters`
--
ALTER TABLE `special_characters`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `images`
--
ALTER TABLE `images`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `login`
--
ALTER TABLE `login`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `new_product_form_field`
--
ALTER TABLE `new_product_form_field`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `skus`
--
ALTER TABLE `skus`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=17790;
--
-- AUTO_INCREMENT for table `special_characters`
--
ALTER TABLE `special_characters`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=11;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
