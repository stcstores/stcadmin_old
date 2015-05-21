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

--
-- Truncate table before insert `new_product_form_field`
--

TRUNCATE TABLE `new_product_form_field`;
--
-- Dumping data for table `new_product_form_field`
--

INSERT INTO `new_product_form_field` (`id`, `field_title`, `field_type`, `field_name`, `csv`, `field_description`, `position`, `page`, `size`, `default`, `required`, `regex`, `tooltip`) VALUES
(1, 'Item Title', 'text', 'item_title', 'basic', 'Required. Between 5 and 50 characters. Must not contain <a href="/new_product/specialcharacters.php">special characters</a>.', 1, 'basic', 50, NULL, 1, NULL, NULL),
(3, 'Department', 'table(departments)', 'department', 'basic', 'Department to which this product will belong', 3, 'basic', NULL, NULL, 0, NULL, NULL),
(5, 'Manufacturer', 'text', 'manufacturer', 'extended', 'The manufacturer of the product', 6, 'basic', 20, 'Unknown', 0, NULL, NULL),
(6, 'Brand', 'text', 'brand', 'extended', 'The brand of the product', 5, 'basic', 20, 'Unbranded', 0, NULL, NULL),
(7, 'Variation Name', 'text', 'var_name', 'basic', 'The name of the variation', 1, 'var_setup', 50, NULL, 1, NULL, NULL),
(8, 'Variations', 'checkbox', 'var_type', 'none', 'Does the product have variations?', 2, 'basic', NULL, NULL, 0, NULL, NULL),
(10, 'Weight', 'text', 'weight', 'basic', 'Weight of the product in kg', 10, 'extended_properties', 6, NULL, 0, NULL, NULL),
(11, 'Retail Price', 'text', 'retail_price', 'basic', 'Price at which the item will be sold. This is a base price and can be overridden on a per-channel basis.', 2, 'extended_properties', 6, NULL, 1, NULL, NULL),
(12, 'Purchase Price', 'text', 'purchase_price', 'basic', 'Purchase price for item.', 1, 'extended_properties', 6, NULL, 1, NULL, NULL),
(13, 'Barcode', 'text', 'barcode', 'basic', 'Barcode. EAN or UPC.', 5, 'extended_properties', 13, NULL, 0, NULL, NULL),
(14, 'Shipping Method', 'table(shipping_methods)', 'shipping_method', 'basic', 'Select the method by which the item will be posted. Select courier for items too large to send by Royal Mail.', 6, 'basic', NULL, NULL, 0, NULL, NULL),
(15, 'Height', 'text', 'height', 'basic', 'Height of item.', 10, 'extended_properties', 6, NULL, 0, NULL, NULL),
(16, 'Width', 'text', 'width', 'basic', 'Width of item.', 11, 'extended_properties', 6, NULL, 0, NULL, NULL),
(17, 'Depth', 'text', 'depth', 'basic', 'Depth of item.', 12, 'extended_properties', 6, NULL, 0, NULL, NULL),
(18, 'Material', 'text', 'material', 'extended', 'The material from which the item is made.', 9, 'extended_properties', 15, NULL, 0, NULL, NULL),
(19, 'Style', 'text', 'style', 'extended', 'The style of the item.', 8, 'extended_properties', 15, NULL, 0, NULL, NULL),
(20, 'Colour', 'text', 'colour', 'extended', 'The colour of the item.', 7, 'extended_properties', 15, NULL, 0, NULL, NULL),
(21, 'Size', 'text', 'size', 'extended', 'Size of item.', 6, 'extended_properties', 25, NULL, 0, NULL, NULL),
(22, 'eBay Title', 'text', 'ebay_title', 'ebay', 'Title for listing on eBay. Must not contain special characters. Up to 80 characters.', 1, 'chn_ebay', 50, NULL, 0, NULL, NULL),
(24, 'eBay Description', 'textarea', 'ebay_description', 'ebay', 'Description for eBay.', 3, 'chn_ebay', NULL, NULL, 0, NULL, NULL),
(25, 'Amazon Title', 'text', 'am_title', 'amazon', 'Title for listings on Amazon', 1, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(26, 'Amazon Bullet 1', 'text', 'am_bullet_1', 'amazon', 'Bullet point description for amazon.', 3, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(27, 'Amazon Bullet 2', 'text', 'am_bullet_2', 'amazon', 'Bullet point description for amazon.', 4, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(28, 'Amazon Bullet 3', 'text', 'am_bullet_3', 'amazon', 'Bullet point description for amazon.', 5, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(29, 'Amazon Bullet 4', 'text', 'am_bullet_4', 'amazon', 'Bullet point description for amazon.', 6, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(30, 'Amazon Bullet 5', 'text', 'am_bullet_5', 'amazon', 'Bullet point description for amazon.', 7, 'chn_amazon', 50, NULL, 0, NULL, NULL),
(32, 'Amazon Description', 'textarea', 'am_description', 'amazon', 'Body of description on Amazon', 8, 'chn_amazon', NULL, NULL, 0, NULL, NULL),
(33, 'Shopify Title', 'text', 'shopify_title', 'ekm', 'Title for listing on stcstores.co.uk', 1, 'chn_shopify', 50, NULL, 0, NULL, NULL),
(35, 'Shopify Description', 'textarea', 'shopify_description', 'ekm', 'Description on stcstores.co.uk.', 3, 'chn_shopify', NULL, NULL, 0, NULL, NULL),
(36, 'Images', 'file multiple', 'images[]', 'images', 'Select as many images as necessary. Multiple images can be selected with Ctrl-click.\r\nImages will be stored in alphabetical order with the first as the main image.', 14, 'Upload Images', NULL, NULL, 0, NULL, NULL),
(37, 'Short Description', 'textarea', 'short_description', 'basic', 'A brief description of the product.\r\nPrimarily used for identification within Linnworks', 7, 'basic', NULL, NULL, 1, NULL, NULL),
(40, 'Title Append', 'text', 'var_append', 'None', 'Text in this field will be appended to the variation title', 2, 'var_setup', 50, NULL, 0, NULL, NULL),
(41, 'Shipping Price', 'text', 'shipping_price', 'extended', 'Basic price for second class shipping', 4, 'extended_properties', 4, NULL, 1, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `new_product_form_field`
--
ALTER TABLE `new_product_form_field`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `new_product_form_field`
--
ALTER TABLE `new_product_form_field`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
