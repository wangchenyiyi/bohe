/*
Navicat MySQL Data Transfer

Source Server         : www.ejoyo.cn
Source Server Version : 50537
Source Host           : www.ejoyo.cn:3306
Source Database       : db_yhf

Target Server Type    : MYSQL
Target Server Version : 50537
File Encoding         : 65001

Date: 2016-05-09 11:41:41
*/

SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `cmf_album`;
CREATE TABLE `cmf_album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `cover` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cmf_photos`;
CREATE TABLE `cmf_photos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `alt` varchar(50) DEFAULT NULL,
  `description` varchar(200) DEFAULT NULL,
  `url` varchar(100) NOT NULL DEFAULT '',
  `thumb_url` varchar(100) DEFAULT NULL,
  `up` int(8) NOT NULL DEFAULT '0',
  `down` int(8) NOT NULL DEFAULT '0',
  `album_id` int(11) DEFAULT '0',
  `user_id` int(11) NOT NULL DEFAULT '0',
  `ctime` int(11) DEFAULT NULL,
  `ext` char(6) DEFAULT NULL,
  `name` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`,`url`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;