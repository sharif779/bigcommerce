-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 21, 2019 at 04:08 PM
-- Server version: 5.7.27-0ubuntu0.16.04.1
-- PHP Version: 7.0.33-0ubuntu0.16.04.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `shopping`
--

-- --------------------------------------------------------

--
-- Table structure for table `branddistribution_products`
--

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
  `picture1` text COLLATE utf8_unicode_ci,
  `picture2` text COLLATE utf8_unicode_ci,
  `picture3` text COLLATE utf8_unicode_ci,
  `madein` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Firme` text COLLATE utf8_unicode_ci,
  `heel` text COLLATE utf8_unicode_ci,
  `lenght` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mainmaterial` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Categorie` text COLLATE utf8_unicode_ci,
  `Produzione` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Sottocategorie` text COLLATE utf8_unicode_ci,
  `Promo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `DiscountPercentage` text COLLATE utf8_unicode_ci,
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
  `income` int(10) NOT NULL DEFAULT '100',
  `bigcommerce_product_id` varchar(50) COLLATE utf8_unicode_ci DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(20) NOT NULL,
  `bigcommerce_cat` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bigcommerce_Sottocategorie` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bigcommerce_service` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branddistribution_service` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `branddistribution_cat` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `branddistribution_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `keys`
--

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
-- Table structure for table `models`
--

CREATE TABLE `models` (
  `id` int(50) DEFAULT NULL,
  `product_id` int(50) DEFAULT NULL,
  `availability` int(50) DEFAULT NULL,
  `backorder` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `barcode` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `bestTaxable` varchar(30) COLLATE utf8_unicode_ci DEFAULT NULL,
  `code` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color` text COLLATE utf8_unicode_ci,
  `lastUpdate` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `model` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `size` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `streetPrice` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `suggestedPrice` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `taxable` text COLLATE utf8_unicode_ci,
  `insert_flag` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branddistribution_products`
--
ALTER TABLE `branddistribution_products`
  ADD PRIMARY KEY (`code`);
