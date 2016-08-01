/*
Navicat MySQL Data Transfer

Source Server         : Localhost
Source Server Version : 50505
Source Host           : localhost:3306
Source Database       : tracker

Target Server Type    : MYSQL
Target Server Version : 50505
File Encoding         : 65001

Date: 2016-07-31 20:40:22
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for click
-- ----------------------------
DROP TABLE IF EXISTS `click`;
CREATE TABLE `click` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `offer` int(11) NOT NULL,
  `type` enum('CLICK','IMPRESSION') DEFAULT 'CLICK',
  `ip` varchar(16) DEFAULT NULL,
  `useragent` varchar(255) DEFAULT NULL,
  `ts1` varchar(50) DEFAULT NULL,
  `ts2` varchar(50) DEFAULT NULL,
  `ts3` varchar(50) DEFAULT NULL,
  `ts4` varchar(50) DEFAULT NULL,
  `referer` varchar(500) DEFAULT NULL,
  `revenue` float(8,2) DEFAULT NULL,
  `conversion` datetime DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=31038 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for log
-- ----------------------------
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `request` varchar(500) DEFAULT NULL,
  `ip` varchar(16) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2425 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for network
-- ----------------------------
DROP TABLE IF EXISTS `network`;
CREATE TABLE `network` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `def` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `affiliate_id` varchar(255) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for network_def
-- ----------------------------
DROP TABLE IF EXISTS `network_def`;
CREATE TABLE `network_def` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `network_url` varchar(255) DEFAULT NULL,
  `type` enum('CAKE','HASOFFERS','MAXBOUNTY') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for offer
-- ----------------------------
DROP TABLE IF EXISTS `offer`;
CREATE TABLE `offer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `network` int(11) DEFAULT NULL,
  `network_offer` int(11) DEFAULT NULL,
  `description` text,
  `restrictions` text,
  `payout` varchar(255) DEFAULT NULL,
  `preview` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `offer_user` (`user`),
  CONSTRAINT `offer_user` FOREIGN KEY (`user`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=1619 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for postback
-- ----------------------------
DROP TABLE IF EXISTS `postback`;
CREATE TABLE `postback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `offer` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for split
-- ----------------------------
DROP TABLE IF EXISTS `split`;
CREATE TABLE `split` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` enum('OFFER','SOURCE') DEFAULT NULL,
  `randomization` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for split_item
-- ----------------------------
DROP TABLE IF EXISTS `split_item`;
CREATE TABLE `split_item` (
  `id` int(11) NOT NULL,
  `split` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `score` float(9,8) DEFAULT '1.00000000',
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fname` varchar(255) DEFAULT NULL,
  `lname` varchar(255) DEFAULT NULL,
  `login` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `status` enum('FREE','ACTIVE','SUSPENDED') DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

-- ----------------------------
-- Table structure for user_log
-- ----------------------------
DROP TABLE IF EXISTS `user_log`;
CREATE TABLE `user_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `ip` varchar(255) DEFAULT NULL,
  `date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=latin1;
