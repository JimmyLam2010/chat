/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 80012
Source Host           : localhost:3306
Source Database       : chat

Target Server Type    : MYSQL
Target Server Version : 80012
File Encoding         : 65001

Date: 2019-09-09 13:23:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for pre_admin
-- ----------------------------
DROP TABLE IF EXISTS `pre_admin`;
CREATE TABLE `pre_admin` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `salt` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `register_time` int(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of pre_admin
-- ----------------------------

-- ----------------------------
-- Table structure for pre_chat
-- ----------------------------
DROP TABLE IF EXISTS `pre_chat`;
CREATE TABLE `pre_chat` (
  `id` int(11) NOT NULL COMMENT '主键',
  `content` text COMMENT '聊天内容',
  `createtime` int(11) DEFAULT '0' COMMENT '发送时间',
  `fromid` int(10) unsigned DEFAULT NULL COMMENT '接收人id',
  `toid` int(10) unsigned DEFAULT NULL COMMENT '发送人id',
  `status` varchar(255) DEFAULT NULL COMMENT '1已读 0未读',
  PRIMARY KEY (`id`),
  KEY `keychat_from` (`fromid`) USING BTREE,
  KEY `keychat_toid` (`toid`) USING BTREE,
  CONSTRAINT `forignchat_fromid` FOREIGN KEY (`fromid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `forignchat_toid` FOREIGN KEY (`toid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='聊天记录表';

-- ----------------------------
-- Records of pre_chat
-- ----------------------------

-- ----------------------------
-- Table structure for pre_user
-- ----------------------------
DROP TABLE IF EXISTS `pre_user`;
CREATE TABLE `pre_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(150) DEFAULT NULL COMMENT '密码',
  `salt` varchar(100) DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `createtime` int(11) DEFAULT NULL COMMENT '注册时间',
  `status` int(11) DEFAULT '0' COMMENT '0邮箱未验证，1邮箱已验证',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户表';

-- ----------------------------
-- Records of pre_user
-- ----------------------------
INSERT INTO `pre_user` VALUES ('1', 'demo', 'd84b346696530378a9851c6ad24ed678', 'bNYcjGrJsA8n6tpYnhFd', '/uploads/z4nCr2qzb9Dvna0PI0sWx5nK.jpg', '2925712507@qq.com', '1567044588', '1');
INSERT INTO `pre_user` VALUES ('2', 'demo123123', 'd84b346696530378a9851c6ad24ed678', 'bNYcjGrJsA8n6tpYnhFd', '/uploads/z4nCr2qzb9Dvna0PI0sWx5nK.jpg', '2925712507@qq.com', '1567044588', '1');

-- ----------------------------
-- Table structure for pre_user_friends
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_friends`;
CREATE TABLE `pre_user_friends` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `friend` int(10) unsigned DEFAULT NULL COMMENT '好友id',
  `userid` int(10) unsigned DEFAULT NULL COMMENT '所属用户id',
  `groupid` int(10) unsigned DEFAULT NULL COMMENT '所属的分组',
  `createtime` int(11) DEFAULT '0' COMMENT '添加时间',
  `content` varchar(255) DEFAULT NULL COMMENT '验证信息',
  `status` int(255) DEFAULT NULL COMMENT '0未通过 1已通过 2已拒绝',
  PRIMARY KEY (`id`),
  KEY `keyfriends_groupid` (`groupid`) USING BTREE,
  CONSTRAINT `forignfriends_groupid` FOREIGN KEY (`groupid`) REFERENCES `pre_user_group` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='好友表';

-- ----------------------------
-- Records of pre_user_friends
-- ----------------------------
INSERT INTO `pre_user_friends` VALUES ('1', '2', '1', '1', '0', null, '1');

-- ----------------------------
-- Table structure for pre_user_group
-- ----------------------------
DROP TABLE IF EXISTS `pre_user_group`;
CREATE TABLE `pre_user_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(255) DEFAULT NULL COMMENT '分组名称',
  `userid` int(10) unsigned DEFAULT NULL COMMENT '所属用户',
  PRIMARY KEY (`id`),
  KEY `keygroup_userid` (`userid`) USING BTREE,
  CONSTRAINT `forigngroup_userid` FOREIGN KEY (`userid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='分组表';

-- ----------------------------
-- Records of pre_user_group
-- ----------------------------
INSERT INTO `pre_user_group` VALUES ('1', '朋友', '1');
INSERT INTO `pre_user_group` VALUES ('2', '家人', '1');
INSERT INTO `pre_user_group` VALUES ('3', '同学', '1');
