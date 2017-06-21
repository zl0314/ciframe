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
CREATE TABLE `tools_url` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) NOT NULL COMMENT '链接名称',
  `link_url` varchar(255) NOT NULL COMMENT '链接',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `hook` char(20) NOT NULL COMMENT '工具连接的关键字， 惟一',
  `is_index` tinyint(1) DEFAULT '0',
  `content` varchar(255) DEFAULT NULL,
  `atime` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `hook` (`hook`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of tools_url
-- ----------------------------
INSERT INTO `tools_url` VALUES ('10', '国际汇率计算', 'http://www.baidu.com', '2017-06-02 11:59:11', 'gjhl', '0', null, null);
