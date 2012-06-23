-- phpMyAdmin SQL Dump
-- version 3.4.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 23, 2012 at 11:12 PM
-- Server version: 5.5.24
-- PHP Version: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `curatorio`
--

-- --------------------------------------------------------

--
-- Table structure for table `collections`
--

DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

DROP TABLE IF EXISTS `items`;
CREATE TABLE IF NOT EXISTS `items` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `collection_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text,
  `image` varchar(255) DEFAULT NULL,
  `price` float DEFAULT NULL,
  `like_count` int(11) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `items_properties`
--

DROP TABLE IF EXISTS `items_properties`;
CREATE TABLE IF NOT EXISTS `items_properties` (
  `item_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `value` varchar(255) NOT NULL,
  `sequence` int(11) NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) NOT NULL,
  `facebook_id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_on` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `users_sessions`
--

DROP TABLE IF EXISTS `users_sessions`;
CREATE TABLE IF NOT EXISTS `users_sessions` (
  `session_id` varchar(255) CHARACTER SET utf8 NOT NULL,
  `user_id` int(11) NOT NULL,
  `edited_on` datetime NOT NULL,
  PRIMARY KEY (`session_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
