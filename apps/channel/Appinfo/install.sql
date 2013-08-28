/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : thinksns

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2012-10-23 16:57:34
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ts_channel`
-- ----------------------------
DROP TABLE IF EXISTS `ts_channel`;
CREATE TABLE `ts_channel` (
  `feed_channel_link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `feed_id` int(11) NOT NULL COMMENT '微博ID',
  `channel_category_id` int(11) NOT NULL COMMENT '頻道分類ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '審核狀態 1稽覈 0未稽覈',
  `width` int(11) NOT NULL DEFAULT '0' COMMENT '圖片寬度',
  `height` int(11) NOT NULL DEFAULT '0' COMMENT '圖片高度',
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  PRIMARY KEY (`feed_channel_link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_channel
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_channel_category`
-- ----------------------------
DROP TABLE IF EXISTS `ts_channel_category`;
CREATE TABLE `ts_channel_category` (
  `channel_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '頻道分類ID',
  `title` varchar(225) NOT NULL COMMENT '頻道分類名稱',
  `pid` int(11) NOT NULL COMMENT '父分類ID',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序欄位',
  `ext` text COMMENT '分類配置相關資訊序列化',
  PRIMARY KEY (`channel_category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_channel_category
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_channel_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ts_channel_follow`;
CREATE TABLE `ts_channel_follow` (
  `channel_follow_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '頻道關注主鍵',
  `uid` int(11) NOT NULL COMMENT '關注使用者ID',
  `channel_category_id` int(11) NOT NULL COMMENT '頻道分類ID',
  PRIMARY KEY (`channel_follow_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='頻道關注表';

-- ----------------------------
-- Records of ts_channel_follow
-- ----------------------------


-- ----------------------------
-- 語言包
-- ----------------------------
DELETE FROM `ts_lang` WHERE `key` = 'PUBLIC_APPNAME_CHANNEL';
INSERT INTO `ts_lang` (`key`, `appname`, `filetype`, `zh-cn`, `en`, `zh-tw`) VALUES ('PUBLIC_APPNAME_CHANNEL', 'PUBLIC', '0', '頻道', 'Channel', '頻道');