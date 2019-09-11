-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Sep 11, 2019 at 06:23 PM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `shopping`
--
CREATE DATABASE IF NOT EXISTS `shopping` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `shopping`;

-- --------------------------------------------------------

--
-- Table structure for table `branddistribution_products`
--

DROP TABLE IF EXISTS `branddistribution_products`;
CREATE TABLE `branddistribution_products` (
  `record_type` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `product_id` int(50) NOT NULL,
  `brand` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `product_quantity` int(50) DEFAULT NULL,
  `street_price` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suggested_price` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `price_novat` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `plain_description` text COLLATE utf8_unicode_ci,
  `weight` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture 1` text COLLATE utf8_unicode_ci,
  `picture 2` text COLLATE utf8_unicode_ci,
  `picture 3` text COLLATE utf8_unicode_ci,
  `madein` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Firme` text COLLATE utf8_unicode_ci,
  `heel` text COLLATE utf8_unicode_ci,
  `lenght` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mainmaterial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Categorie` text COLLATE utf8_unicode_ci,
  `Produzione` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sottocategorie` text COLLATE utf8_unicode_ci,
  `Promo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Discount Percentage` text COLLATE utf8_unicode_ci,
  `season` text COLLATE utf8_unicode_ci,
  `7dayssale` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` text COLLATE utf8_unicode_ci,
  `partner` text COLLATE utf8_unicode_ci,
  `service` text COLLATE utf8_unicode_ci,
  `Warehouse2` text COLLATE utf8_unicode_ci,
  `Sunglasses` text COLLATE utf8_unicode_ci,
  `Watches` text COLLATE utf8_unicode_ci,
  `bicolors` text COLLATE utf8_unicode_ci,
  `Genere` text COLLATE utf8_unicode_ci,
  `Print` text COLLATE utf8_unicode_ci,
  `productname` text COLLATE utf8_unicode_ci,
  `model_id` text COLLATE utf8_unicode_ci,
  `barcode` text COLLATE utf8_unicode_ci,
  `model_size` text COLLATE utf8_unicode_ci,
  `model_quantity` text COLLATE utf8_unicode_ci,
  `insert_flag` int(1) NOT NULL DEFAULT '0',
  `income` int(10) NOT NULL DEFAULT '100'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(20) NOT NULL,
  `bigcommerce_cat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bigcommerce_Sottocategorie` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bigcommerce_service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branddistribution_service` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branddistribution_cat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `branddistribution_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `bigcommerce_cat`, `bigcommerce_Sottocategorie`, `bigcommerce_service`, `branddistribution_service`, `branddistribution_cat`, `branddistribution_name`) VALUES
(81, 'clothing', 'outerwear-jackets', 'women', 'Women', 'Clothing', 'Coats'),
(107, 'clothing', 'jeans', 'women', 'Women', 'Clothing', 'Jeans'),
(104, 'clothing', 'Lingerie, nightwear & underwear', 'woman', 'Women', 'Underwear', 'G-strings'),
(108, 'clothing', 'pants-leggings', 'women', 'Women', 'Clothing', 'Short'),
(117, 'clothing', 'sell-offers', 'women', 'Women', 'Clothing', ''),
(106, 'clothing', 'sportswear', 'women', 'Women', 'Clothing', 'Tracksuit pants'),
(103, 'clothing', 'tops-sets', 'women', 'Women', 'Clothing', 'Shirts'),
(105, 'clothing', 'swimwear-and-beachwear', 'women', 'Women', 'Clothing', ''),
(134, 'clothing', 'socks-tights', 'women', 'Women', 'Clothing', ''),
(475, 'clothing', 'dresses', 'women', 'Women', 'Clothing', 'Dresses'),
(476, 'clothing', 'skirts', 'women', 'Women', 'Clothing', 'skirts'),
(86, 'clothing', 'jeans', 'men', 'Men', 'Clothing', 'Jeans'),
(79, 'clothing', 'activewear', 'men', 'Men', '', ''),
(93, 'clothing', 't-shirts-tops', 'men', 'Men', 'Clothing', 'Polo'),
(85, 'clothing', 'coats-jackets', 'men', 'Men', 'Clothing', 'Jackets'),
(483, 'clothing', 'suits', 'men', 'Men', 'Clothing', 'Suits'),
(92, 'clothing', 'trousers', 'men', 'Men', 'Clothing', 'Trousers'),
(88, 'clothing', 'shirts', 'men', 'Men', 'Clothing', 'Shirts'),
(89, 'clothing', 'shorts', 'men', 'Men', 'Clothing', 'Short'),
(94, 'clothing', 'underwear-socks', 'men', 'Men', 'Clothing', 'Boxers'),
(91, 'clothing', 'sweaters-knitwear', 'men', 'Men', 'Clothing', 'Sweatshirts'),
(78, 'clothing', 'beachwear', 'men', 'Men', 'Clothing', ''),
(96, 'clothing', 'sale-clothing', 'men', NULL, '', ''),
(481, 'shoes', 'pumps-heels', 'women', 'Women', 'Shoes', 'Pumps & Heels'),
(482, 'shoes', 'moccasins', 'women', 'Women', 'Shoes', 'Moccasins'),
(127, 'shoes', 'boots', 'women', 'Women', 'Shoes', 'Boots'),
(131, 'shoes', 'sandals', 'women', 'Women', 'Shoes', 'Sandals'),
(480, 'shoes', 'slip-on', 'women', 'Women', 'Shoes', 'Slip-on'),
(132, 'shoes', 'sliders-flip-flops', 'women', 'Women', 'Shoes', 'Flip Flops'),
(133, 'shoes', 'sneakers', 'women', 'Women', 'Shoes', 'Sneakers'),
(129, 'shoes', 'flat-shoes', 'women', 'Women', 'Shoes', 'Ballet flats'),
(477, 'shoes', 'wedges', 'women', 'Women', 'Shoes', 'Wedges'),
(136, 'shoes', 'boots', 'men', 'Men', 'Shoes', 'Ankle boots'),
(137, 'shoes', 'espadrilles', 'men', 'Men', 'Shoes', ''),
(138, 'shoes', 'shoes', 'men', 'Men', 'Shoes', 'Lace up'),
(139, 'shoes', 'sliders-flip-flops', 'men', 'Men', 'Shoes', 'Flip Flops'),
(140, 'shoes', 'sneakers', 'men', 'Men', 'Shoes', 'Sneakers'),
(478, 'shoes', 'moccasins', 'men', 'Men', 'Shoes', 'Moccasins'),
(479, 'shoes', 'slip-on', 'men', 'Men', 'Shoes', 'Slip-on'),
(152, 'bags', 'bags', 'men', 'Men', 'Bags', 'Rucksacks'),
(474, 'bags', 'bags', 'women', 'Women', 'Bags', 'Handbags'),
(121, 'bijouterie', 'pendants-beads', 'women', 'Women', '', ''),
(114, 'bijouterie', 'rings', 'women', 'Women', '', ''),
(113, 'bijouterie', 'earrings', 'women', 'Women', '', ''),
(120, 'bijouterie', 'other', 'women', 'Women', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

DROP TABLE IF EXISTS `keys`;
CREATE TABLE `keys` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `key` varchar(40) NOT NULL,
  `level` int(2) NOT NULL,
  `ignore_limits` tinyint(1) NOT NULL DEFAULT '0',
  `is_private_key` tinyint(1) NOT NULL DEFAULT '0',
  `ip_addresses` text,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `logs`
--

DROP TABLE IF EXISTS `logs`;
CREATE TABLE `logs` (
  `id` int(11) NOT NULL,
  `uri` varchar(255) NOT NULL,
  `method` varchar(6) NOT NULL,
  `params` text,
  `api_key` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `time` int(11) NOT NULL,
  `rtime` float DEFAULT NULL,
  `authorized` varchar(1) NOT NULL,
  `response_code` smallint(3) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branddistribution_products`
--
ALTER TABLE `branddistribution_products`
  ADD PRIMARY KEY (`code`);
