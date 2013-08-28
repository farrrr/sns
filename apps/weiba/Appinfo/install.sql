/*
Navicat MySQL Data Transfer

Source Server         : 127.0.0.1
Source Server Version : 50516
Source Host           : 127.0.0.1:3306
Source Database       : sociax_team

Target Server Type    : MYSQL
Target Server Version : 50516
File Encoding         : 65001

Date: 2012-06-06 16:10:35
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `ts_weiba`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba`;
CREATE TABLE `ts_weiba` (
  `weiba_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '微吧ID',
  `weiba_name` varchar(255) NOT NULL DEFAULT '微吧名稱',
  `uid` int(11) NOT NULL COMMENT '創建者ID',
  `ctime` int(11) NOT NULL COMMENT '創建時間',
  `logo` varchar(255) DEFAULT NULL COMMENT '微吧logo',
  `intro` text COMMENT '微吧簡介',
  `who_can_post` tinyint(1) NOT NULL DEFAULT '0' COMMENT '發帖許可權 0-所有人 1-僅成員',
  `who_can_reply` tinyint(1) NOT NULL DEFAULT '0' COMMENT '回帖許可權 0-所有人 1-僅成員',
  `follower_count` int(10) DEFAULT '0' COMMENT '成員數',
  `thread_count` int(10) DEFAULT '0' COMMENT '帖子數',
  `admin_uid` int(11) NOT NULL COMMENT '超級吧主uid',
  `recommend` tinyint(1) DEFAULT '0' COMMENT '是否設為推薦（熱門）0-否，1-是',
  `status` tinyint(1) DEFAULT '0' COMMENT '是否通過稽覈：0-未通過，1-已通過',
  `is_del` int(2) DEFAULT '0' COMMENT '是否刪除 默認為0',
  `notify` varchar(255) DEFAULT NULL COMMENT '微吧公告',
  PRIMARY KEY (`weiba_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_weiba_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_follow`;
CREATE TABLE `ts_weiba_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `weiba_id` int(11) NOT NULL COMMENT '微吧ID',
  `follower_uid` int(11) NOT NULL COMMENT '成員ID',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等級 1-粉絲 2-小吧 3-吧主',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- ----------------------------
-- Table structure for `ts_weiba_post`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_post`;
CREATE TABLE `ts_weiba_post` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '帖子ID',
  `weiba_id` int(11) NOT NULL COMMENT '所屬微吧ID',
  `post_uid` int(11) NOT NULL COMMENT '發表者uid',
  `title` varchar(255) NOT NULL COMMENT '帖子標題',
  `content` text NOT NULL COMMENT '帖子內容',
  `post_time` int(11) NOT NULL COMMENT '發表時間',
  `reply_count` int(10) DEFAULT '0' COMMENT '回複數',
  `read_count` int(10) DEFAULT '0' COMMENT '瀏覽數',
  `last_reply_uid` int(11) DEFAULT '0' COMMENT '最後回覆人',
  `last_reply_time` int(11) DEFAULT '0' COMMENT '最後回覆時間',
  `digest` tinyint(1) DEFAULT '0' COMMENT '全局精華 0-否 1-是',
  `top` tinyint(1) DEFAULT '0' COMMENT '置頂帖 0-否 1-吧內 2-全局',
  `lock` tinyint(1) DEFAULT '0' COMMENT '鎖帖（不允許回覆）0-否 1-是',
  `recommend` tinyint(1) DEFAULT '0' COMMENT '是否設為推薦',
  `recommend_time` int(11) DEFAULT '0' COMMENT '設為推薦的時間',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已刪除 0-否 1-是',
  `feed_id` int(11) NOT NULL COMMENT '對應的微博ID',
  `reply_all_count` int(11) NOT NULL DEFAULT '0' COMMENT '全部評論數目',
  PRIMARY KEY (`post_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_weiba_reply`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_reply`;
CREATE TABLE `ts_weiba_reply` (
  `reply_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '回覆ID',
  `weiba_id` int(11) NOT NULL COMMENT '所屬微吧',
  `post_id` int(11) NOT NULL COMMENT '所屬帖子ID',
  `post_uid` int(11) NOT NULL COMMENT '帖子作者UID',
  `uid` int(11) NOT NULL COMMENT '回覆者ID',
  `to_reply_id` int(11) NOT NULL DEFAULT '0' COMMENT '回覆的評論id',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被回覆的評論的作者的uid',
  `ctime` int(11) NOT NULL COMMENT '回覆時間',
  `content` text NOT NULL COMMENT '回覆內容',
  `is_del` tinyint(2) DEFAULT '0' COMMENT '是否已刪除 0-否 1-是',
  `comment_id` int(11) NOT NULL COMMENT '對應的微博評論ID',
  `storey` int(11) NOT NULL DEFAULT '0' COMMENT '絕對樓層',
  PRIMARY KEY (`reply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of sociax_contact_user
-- ----------------------------

-- /* 插入system_data資料表資料 */
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_index', 'a:6:{s:3:\"key\";a:1:{s:13:\"contact_field\";s:13:\"contact_field\";}s:8:\"key_name\";a:1:{s:13:\"contact_field\";s:12:\"顯示欄位\";}s:8:\"key_type\";a:1:{s:13:\"contact_field\";s:8:\"checkbox\";}s:11:\"key_default\";a:1:{s:13:\"contact_field\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:13:\"contact_field\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:13:\"contact_field\";s:0:\"\";}}', '2012-06-07 00:16:18');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_getContactList', 'a:4:{s:3:\"key\";a:4:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:3:\"uid\";s:9:\"使用者UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"郵箱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:5:\"email\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-07 00:17:33');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('searchPageKey', 'S_contact_Admin_getContactList', 'a:5:{s:3:\"key\";a:3:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";}s:8:\"key_name\";a:3:{s:3:\"uid\";s:9:\"使用者UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"郵件\";}s:8:\"key_type\";a:3:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";}}', '2012-06-07 00:17:58');
-- INSERT INTO ts_system_data (`list`, `key`, `value`, `mtime`) VALUES ('pageKey', 'contact_Admin_editPersonProfile', 'a:6:{s:3:\"key\";a:11:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:5:\"intro\";s:5:\"intro\";s:6:\"mobile\";s:6:\"mobile\";s:3:\"tel\";s:3:\"tel\";s:5:\"weibo\";s:5:\"weibo\";s:13:\"work_director\";s:13:\"work_director\";s:15:\"work_experience\";s:15:\"work_experience\";s:13:\"work_position\";s:13:\"work_position\";s:12:\"work_project\";s:12:\"work_project\";}s:8:\"key_name\";a:11:{s:3:\"uid\";s:9:\"使用者UID\";s:5:\"uname\";s:6:\"姓名\";s:5:\"email\";s:6:\"郵箱\";s:5:\"intro\";s:12:\"個人簡介\";s:6:\"mobile\";s:6:\"手機\";s:3:\"tel\";s:6:\"座機\";s:5:\"weibo\";s:12:\"新浪微博\";s:13:\"work_director\";s:12:\"直接主管\";s:15:\"work_experience\";s:12:\"工作經歷\";s:13:\"work_position\";s:6:\"職位\";s:12:\"work_project\";s:12:\"項目經驗\";}s:8:\"key_type\";a:11:{s:3:\"uid\";s:6:\"hidden\";s:5:\"uname\";s:4:\"word\";s:5:\"email\";s:4:\"word\";s:5:\"intro\";s:8:\"textarea\";s:6:\"mobile\";s:4:\"text\";s:3:\"tel\";s:4:\"text\";s:5:\"weibo\";s:4:\"text\";s:13:\"work_director\";s:4:\"text\";s:15:\"work_experience\";s:8:\"textarea\";s:13:\"work_position\";s:4:\"text\";s:12:\"work_project\";s:8:\"textarea\";}s:11:\"key_default\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}s:9:\"key_tishi\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}s:14:\"key_javascript\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:6:\"mobile\";s:0:\"\";s:3:\"tel\";s:0:\"\";s:5:\"weibo\";s:0:\"\";s:13:\"work_director\";s:0:\"\";s:15:\"work_experience\";s:0:\"\";s:13:\"work_position\";s:0:\"\";s:12:\"work_project\";s:0:\"\";}}', '2012-06-28 18:56:39');
