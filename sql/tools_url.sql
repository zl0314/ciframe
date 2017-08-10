/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-06-21 17:39:44
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for tools_url
-- ----------------------------
DROP TABLE IF EXISTS `tools_url`;

CREATE TABLE `ci_tools_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `title` varchar(100) NOT NULL COMMENT '链接名称',
      `link_url` varchar(255) NOT NULL COMMENT '链接',
        `addtime` datetime NOT NULL COMMENT '添加时间',
	  `hook` char(20) NOT NULL COMMENT '工具连接的关键字， 惟一',
	    PRIMARY KEY (`id`),
	      UNIQUE KEY `hook` (`hook`) USING BTREE
	      ) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='友情链接'
