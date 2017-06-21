/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50547
Source Host           : localhost:3306
Source Database       : test

Target Server Type    : MYSQL
Target Server Version : 50547
File Encoding         : 65001

Date: 2017-06-21 17:39:35
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for admin_user
-- ----------------------------
DROP TABLE IF EXISTS `admin_user`;
CREATE TABLE `admin_user` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(200) NOT NULL COMMENT '用户名',
  `password` varchar(255) NOT NULL COMMENT '密码 md5',
  `nick_name` varchar(100) NOT NULL COMMENT '真实姓名',
  `is_root` tinyint(1) unsigned NOT NULL COMMENT '是否超级权限1是0否',
  `permissions` text COMMENT '权限节点',
  `isdelete` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '0不删除1删除',
  `addtime` datetime NOT NULL COMMENT '添加时间',
  `salt` char(6) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员表';

-- ----------------------------
-- Records of admin_user
-- ----------------------------
INSERT INTO `admin_user` VALUES ('1', 'admin', '1cc1416003fad3d09211fa052bd908fb', '超级管理员', '1', 'ALL PRIVILEGES', '0', '2017-05-08 12:44:52', 'a0227b');
INSERT INTO `admin_user` VALUES ('4', 'lansha', 'a3116d687c6e58e8a133fa682dfa4435', '蓝莎', '0', 'a:1:{s:5:\"Admin\";a:3:{i:0;s:5:\"index\";i:1;s:6:\"center\";i:2;s:6:\"logout\";}}', '0', '2017-06-21 17:35:34', '31db38');
