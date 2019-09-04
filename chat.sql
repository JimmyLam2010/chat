-- phpMyAdmin SQL Dump
-- version 4.9.0.1
-- https://www.phpmyadmin.net/
--
-- 主机： localhost:3306
-- 生成日期： 2019-09-02 12:34:54
-- 服务器版本： 5.7.26
-- PHP 版本： 5.6.40

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 数据库： `chat`
--
CREATE DATABASE IF NOT EXISTS `chat` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `chat`;

-- --------------------------------------------------------

--
-- 表的结构 `pre_chat`
--

CREATE TABLE `pre_chat` (
  `id` int(11) NOT NULL COMMENT '主键',
  `content` text COMMENT '聊天内容',
  `createtime` int(11) DEFAULT '0' COMMENT '发送时间',
  `fromid` int(10) UNSIGNED DEFAULT NULL COMMENT '接收人id',
  `toid` int(10) UNSIGNED DEFAULT NULL COMMENT '发送人id',
  `status` varchar(255) DEFAULT NULL COMMENT '1已读 0未读'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='聊天记录表';

-- --------------------------------------------------------

--
-- 表的结构 `pre_user`
--

CREATE TABLE `pre_user` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `username` varchar(255) DEFAULT NULL COMMENT '用户名',
  `password` varchar(150) DEFAULT NULL COMMENT '密码',
  `salt` varchar(100) DEFAULT NULL COMMENT '密码盐',
  `avatar` varchar(255) DEFAULT NULL COMMENT '头像',
  `email` varchar(255) DEFAULT NULL COMMENT '邮箱',
  `createtime` int(11) DEFAULT NULL COMMENT '注册时间',
  `status` int(11) DEFAULT '0' COMMENT '0邮箱未验证，1邮箱已验证'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='用户表';

--
-- 转存表中的数据 `pre_user`
--

INSERT INTO `pre_user` (`id`, `username`, `password`, `salt`, `avatar`, `email`, `createtime`, `status`) VALUES
(1, 'demo', 'd84b346696530378a9851c6ad24ed678', 'bNYcjGrJsA8n6tpYnhFd', '/uploads/z4nCr2qzb9Dvna0PI0sWx5nK.jpg', '2925712507@qq.com', 1567044588, 1),
(2, 'demo123123', 'd84b346696530378a9851c6ad24ed678', 'bNYcjGrJsA8n6tpYnhFd', '/uploads/z4nCr2qzb9Dvna0PI0sWx5nK.jpg', '2925712507@qq.com', 1567044588, 1);

-- --------------------------------------------------------

--
-- 表的结构 `pre_user_friends`
--

CREATE TABLE `pre_user_friends` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `friend` int(10) UNSIGNED DEFAULT NULL COMMENT '好友id',
  `userid` int(10) UNSIGNED DEFAULT NULL COMMENT '所属用户id',
  `groupid` int(10) UNSIGNED DEFAULT NULL COMMENT '所属的分组',
  `createtime` int(11) DEFAULT '0' COMMENT '添加时间',
  `content` varchar(255) DEFAULT NULL COMMENT '验证信息',
  `status` int(255) DEFAULT NULL COMMENT '0未通过 1已通过 2已拒绝'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='好友表';

--
-- 转存表中的数据 `pre_user_friends`
--

INSERT INTO `pre_user_friends` (`id`, `friend`, `userid`, `groupid`, `createtime`, `content`, `status`) VALUES
(1, 2, 1, 1, 0, NULL, 1);

-- --------------------------------------------------------

--
-- 表的结构 `pre_user_group`
--

CREATE TABLE `pre_user_group` (
  `id` int(10) UNSIGNED NOT NULL COMMENT '主键',
  `name` varchar(255) DEFAULT NULL COMMENT '分组名称',
  `userid` int(10) UNSIGNED DEFAULT NULL COMMENT '所属用户'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='分组表';

--
-- 转存表中的数据 `pre_user_group`
--

INSERT INTO `pre_user_group` (`id`, `name`, `userid`) VALUES
(1, '朋友', 1),
(2, '家人', 1),
(3, '同学', 1);

--
-- 转储表的索引
--

--
-- 表的索引 `pre_chat`
--
ALTER TABLE `pre_chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keychat_from` (`fromid`) USING BTREE,
  ADD KEY `keychat_toid` (`toid`) USING BTREE;

--
-- 表的索引 `pre_user`
--
ALTER TABLE `pre_user`
  ADD PRIMARY KEY (`id`);

--
-- 表的索引 `pre_user_friends`
--
ALTER TABLE `pre_user_friends`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keyfriends_groupid` (`groupid`) USING BTREE;

--
-- 表的索引 `pre_user_group`
--
ALTER TABLE `pre_user_group`
  ADD PRIMARY KEY (`id`),
  ADD KEY `keygroup_userid` (`userid`) USING BTREE;

--
-- 在导出的表使用AUTO_INCREMENT
--

--
-- 使用表AUTO_INCREMENT `pre_user`
--
ALTER TABLE `pre_user`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=3;

--
-- 使用表AUTO_INCREMENT `pre_user_friends`
--
ALTER TABLE `pre_user_friends`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=2;

--
-- 使用表AUTO_INCREMENT `pre_user_group`
--
ALTER TABLE `pre_user_group`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键', AUTO_INCREMENT=4;

--
-- 限制导出的表
--

--
-- 限制表 `pre_chat`
--
ALTER TABLE `pre_chat`
  ADD CONSTRAINT `forignchat_fromid` FOREIGN KEY (`fromid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `forignchat_toid` FOREIGN KEY (`toid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- 限制表 `pre_user_friends`
--
ALTER TABLE `pre_user_friends`
  ADD CONSTRAINT `forignfriends_groupid` FOREIGN KEY (`groupid`) REFERENCES `pre_user_group` (`id`) ON DELETE SET NULL ON UPDATE NO ACTION;

--
-- 限制表 `pre_user_group`
--
ALTER TABLE `pre_user_group`
  ADD CONSTRAINT `forigngroup_userid` FOREIGN KEY (`userid`) REFERENCES `pre_user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
