/*
MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50527
Source Host           : localhost:3306
Source Database       : thinksns

Target Server Type    : MYSQL
Target Server Version : 50527
File Encoding         : 65001

Date: 2013-05-09 17:54:32
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ts_ucenter_user_link
-- ----------------------------
DROP TABLE IF EXISTS `ts_ucenter_user_link`;
CREATE TABLE `ts_ucenter_user_link` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `uc_uid` mediumint(8) NOT NULL,
  `uc_username` char(15) NOT NULL,
  `uc_email` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_system_config`
-- ----------------------------
DROP TABLE IF EXISTS `ts_system_config`;
CREATE TABLE `ts_system_config` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list` char(30) DEFAULT 'default' COMMENT '列表名',
  `key` char(50) DEFAULT 'default' COMMENT '鍵名',
  `value` text COMMENT '鍵值',
  `mtime` timestamp NULL DEFAULT NULL COMMENT '修改時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `list_key` (`list`,`key`)
) ENGINE=MyISAM AUTO_INCREMENT=6517 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_system_config
-- ----------------------------
INSERT INTO `ts_system_config` VALUES ('810', 'searchPageKey', 'S_admin_Home_logs', 'a:5:{s:3:\"key\";a:5:{s:5:\"uname\";s:5:\"uname\";s:8:\"app_name\";s:8:\"app_name\";s:5:\"ctime\";s:5:\"ctime\";s:7:\"isAdmin\";s:7:\"isAdmin\";s:7:\"keyword\";s:7:\"keyword\";}s:8:\"key_name\";a:5:{s:5:\"uname\";s:12:\"使用者帳號\";s:8:\"app_name\";s:12:\"操作詳情\";s:5:\"ctime\";s:12:\"時間範圍\";s:7:\"isAdmin\";s:12:\"日誌類型\";s:7:\"keyword\";s:15:\"查詢關鍵字\";}s:8:\"key_type\";a:5:{s:5:\"uname\";s:4:\"text\";s:8:\"app_name\";s:6:\"select\";s:5:\"ctime\";s:4:\"date\";s:7:\"isAdmin\";s:8:\"checkbox\";s:7:\"keyword\";s:4:\"text\";}s:9:\"key_tishi\";a:5:{s:5:\"uname\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:7:\"isAdmin\";s:0:\"\";s:7:\"keyword\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:5:\"uname\";s:0:\"\";s:8:\"app_name\";s:27:\"admin.selectLog(this.value)\";s:5:\"ctime\";s:0:\"\";s:7:\"isAdmin\";s:0:\"\";s:7:\"keyword\";s:0:\"\";}}', '2012-07-03 11:44:51');
INSERT INTO `ts_system_config` VALUES ('2406', 'pageKey', 'admin_Config_invite', 'a:6:{s:3:\"key\";a:3:{s:14:\"send_email_num\";s:14:\"send_email_num\";s:13:\"send_link_num\";s:13:\"send_link_num\";s:12:\"apply_credit\";s:12:\"apply_credit\";}s:8:\"key_name\";a:3:{s:14:\"send_email_num\";s:21:\"郵件邀請碼數量\";s:13:\"send_link_num\";s:21:\"連結邀請碼數量\";s:12:\"apply_credit\";s:27:\"申請邀請碼扣除積分\";}s:8:\"key_type\";a:3:{s:14:\"send_email_num\";s:4:\"text\";s:13:\"send_link_num\";s:4:\"text\";s:12:\"apply_credit\";s:4:\"text\";}s:11:\"key_default\";a:3:{s:14:\"send_email_num\";s:0:\"\";s:13:\"send_link_num\";s:0:\"\";s:12:\"apply_credit\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:14:\"send_email_num\";s:0:\"\";s:13:\"send_link_num\";s:0:\"\";s:12:\"apply_credit\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:14:\"send_email_num\";s:0:\"\";s:13:\"send_link_num\";s:0:\"\";s:12:\"apply_credit\";s:0:\"\";}}', '2012-12-21 15:10:26');
INSERT INTO `ts_system_config` VALUES ('4709', 'pageKey', 'admin_Config_email', 'a:6:{s:3:\"key\";a:9:{s:14:\"email_sendtype\";s:14:\"email_sendtype\";s:10:\"email_host\";s:10:\"email_host\";s:9:\"email_ssl\";s:9:\"email_ssl\";s:10:\"email_port\";s:10:\"email_port\";s:13:\"email_account\";s:13:\"email_account\";s:14:\"email_password\";s:14:\"email_password\";s:17:\"email_sender_name\";s:17:\"email_sender_name\";s:18:\"email_sender_email\";s:18:\"email_sender_email\";s:10:\"email_test\";s:10:\"email_test\";}s:8:\"key_name\";a:9:{s:14:\"email_sendtype\";s:18:\"郵件發送方式\";s:10:\"email_host\";s:10:\"SMTP地址\";s:9:\"email_ssl\";s:21:\"是否啟用SSL連線\";s:10:\"email_port\";s:6:\"埠\";s:13:\"email_account\";s:12:\"郵箱地址\";s:14:\"email_password\";s:12:\"郵箱密碼\";s:17:\"email_sender_name\";s:15:\"發送者姓名\";s:18:\"email_sender_email\";s:15:\"發送者郵箱\";s:10:\"email_test\";s:12:\"測試郵件\";}s:8:\"key_type\";a:9:{s:14:\"email_sendtype\";s:6:\"select\";s:10:\"email_host\";s:4:\"text\";s:9:\"email_ssl\";s:5:\"radio\";s:10:\"email_port\";s:4:\"text\";s:13:\"email_account\";s:4:\"text\";s:14:\"email_password\";s:4:\"text\";s:17:\"email_sender_name\";s:4:\"text\";s:18:\"email_sender_email\";s:4:\"text\";s:10:\"email_test\";s:4:\"text\";}s:11:\"key_default\";a:9:{s:14:\"email_sendtype\";s:4:\"smtp\";s:10:\"email_host\";s:12:\"smtp.163.com\";s:9:\"email_ssl\";s:1:\"0\";s:10:\"email_port\";s:2:\"25\";s:13:\"email_account\";s:18:\"_12345678_@163.com\";s:14:\"email_password\";s:8:\"uwycitfk\";s:17:\"email_sender_name\";s:10:\"SociaxTeam\";s:18:\"email_sender_email\";s:21:\"sociax@zhishisoft.com\";s:10:\"email_test\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:14:\"email_sendtype\";s:0:\"\";s:10:\"email_host\";s:61:\"發送郵箱的smtp地址。如: smtp.gmail.com或smtp.qq.com\";s:9:\"email_ssl\";s:88:\"此選項需要伺服器環境支援SSL（如果使用Gmail或QQ郵箱，請選擇是）\";s:10:\"email_port\";s:135:\"smtp的埠。默認為25。具體請參看各STMP服務商的設定說明 （如果使用Gmail或QQ郵箱，請將埠設為465）\";s:13:\"email_account\";s:54:\"郵箱地址請輸入完整地址email@email.com格式\";s:14:\"email_password\";s:12:\"郵箱密碼\";s:17:\"email_sender_name\";s:33:\"郵件中顯示的發送者姓名\";s:18:\"email_sender_email\";s:33:\"郵件中顯示的發送者郵箱\";s:10:\"email_test\";s:53:\"<a onclick=\"admin.testEmail()\">點選測試郵件</a>\";}s:14:\"key_javascript\";a:9:{s:14:\"email_sendtype\";s:0:\"\";s:10:\"email_host\";s:0:\"\";s:9:\"email_ssl\";s:0:\"\";s:10:\"email_port\";s:0:\"\";s:13:\"email_account\";s:0:\"\";s:14:\"email_password\";s:0:\"\";s:17:\"email_sender_name\";s:0:\"\";s:18:\"email_sender_email\";s:0:\"\";s:10:\"email_test\";s:0:\"\";}}', '2013-03-11 14:40:02');
INSERT INTO `ts_system_config` VALUES ('4249', 'pageKey', 'admin_Config_attach', 'a:6:{s:3:\"key\";a:3:{s:16:\"attach_path_rule\";s:16:\"attach_path_rule\";s:15:\"attach_max_size\";s:15:\"attach_max_size\";s:22:\"attach_allow_extension\";s:22:\"attach_allow_extension\";}s:8:\"key_name\";a:3:{s:16:\"attach_path_rule\";s:12:\"目錄規則\";s:15:\"attach_max_size\";s:15:\"最大允許值\";s:22:\"attach_allow_extension\";s:15:\"副檔名限定\";}s:8:\"key_type\";a:3:{s:16:\"attach_path_rule\";s:4:\"text\";s:15:\"attach_max_size\";s:4:\"text\";s:22:\"attach_allow_extension\";s:10:\"stringText\";}s:11:\"key_default\";a:3:{s:16:\"attach_path_rule\";s:7:\"Y/md/H/\";s:15:\"attach_max_size\";s:1:\"2\";s:22:\"attach_allow_extension\";s:59:\"jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf\";}s:9:\"key_tishi\";a:3:{s:16:\"attach_path_rule\";s:21:\"注：不建議修改\";s:15:\"attach_max_size\";s:56:\"單位：兆(M) 允許使用小數點。如：0.5或2等\";s:22:\"attach_allow_extension\";s:55:\"按回車添加，多個輸入後用英文逗號,分割\";}s:14:\"key_javascript\";a:3:{s:16:\"attach_path_rule\";s:0:\"\";s:15:\"attach_max_size\";s:0:\"\";s:22:\"attach_allow_extension\";s:0:\"\";}}', '2013-02-28 17:30:57');
INSERT INTO `ts_system_config` VALUES ('1345', 'searchPageKey', 'S_admin_Home_tag', 'a:5:{s:3:\"key\";a:2:{s:4:\"name\";s:4:\"name\";s:5:\"table\";s:5:\"table\";}s:8:\"key_name\";a:2:{s:4:\"name\";s:9:\"標簽名\";s:5:\"table\";s:12:\"標籤類型\";}s:8:\"key_type\";a:2:{s:4:\"name\";s:4:\"text\";s:5:\"table\";s:6:\"select\";}s:9:\"key_tishi\";a:2:{s:4:\"name\";s:0:\"\";s:5:\"table\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:4:\"name\";s:0:\"\";s:5:\"table\";s:0:\"\";}}', '2012-07-23 16:23:54');
INSERT INTO `ts_system_config` VALUES ('1516', 'pageKey', 'admin_Home_addFeedbackType', 'a:6:{s:3:\"key\";a:2:{s:7:\"type_id\";s:7:\"type_id\";s:9:\"type_name\";s:9:\"type_name\";}s:8:\"key_name\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:12:\"反饋類型\";}s:8:\"key_type\";a:2:{s:7:\"type_id\";s:6:\"hidden\";s:9:\"type_name\";s:4:\"text\";}s:11:\"key_default\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}}', '2012-07-25 09:28:24');
INSERT INTO `ts_system_config` VALUES ('1683', 'searchPageKey', 'S_admin_User_online', 'a:5:{s:3:\"key\";a:6:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:3:\"sex\";s:3:\"sex\";s:10:\"user_group\";s:10:\"user_group\";s:5:\"ctime\";s:5:\"ctime\";}s:8:\"key_name\";a:6:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:12:\"使用者昵稱\";s:5:\"email\";s:5:\"Email\";s:3:\"sex\";s:6:\"性別\";s:10:\"user_group\";s:9:\"使用者組\";s:5:\"ctime\";s:12:\"註冊時間\";}s:8:\"key_type\";a:6:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:10:\"user_group\";s:6:\"select\";s:5:\"ctime\";s:4:\"date\";}s:9:\"key_tishi\";a:6:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}}', '2012-08-17 01:07:44');
INSERT INTO `ts_system_config` VALUES ('2102', 'pageKey', 'admin_Home_feedback_list', 'a:6:{s:3:\"key\";a:4:{s:12:\"feedbacktype\";s:12:\"feedbacktype\";s:3:\"uid\";s:3:\"uid\";s:8:\"feedback\";s:8:\"feedback\";s:4:\"cTme\";s:4:\"cTme\";}s:8:\"key_name\";a:4:{s:12:\"feedbacktype\";s:12:\"反饋類型\";s:3:\"uid\";s:9:\"提交者\";s:8:\"feedback\";s:12:\"反饋內容\";s:4:\"cTme\";s:12:\"反饋時間\";}s:8:\"key_type\";a:4:{s:12:\"feedbacktype\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:8:\"feedback\";s:4:\"text\";s:4:\"cTme\";s:4:\"text\";}s:11:\"key_default\";a:4:{s:12:\"feedbacktype\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"feedback\";s:0:\"\";s:4:\"cTme\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:12:\"feedbacktype\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"feedback\";s:0:\"\";s:4:\"cTme\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:12:\"feedbacktype\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"feedback\";s:0:\"\";s:4:\"cTme\";s:0:\"\";}}', '2012-10-26 18:20:00');
INSERT INTO `ts_system_config` VALUES ('1734', 'searchPageKey', 'S_admin_User_dellist', 'a:5:{s:3:\"key\";a:7:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:3:\"sex\";s:3:\"sex\";s:10:\"user_group\";s:10:\"user_group\";s:13:\"user_category\";s:13:\"user_category\";s:5:\"ctime\";s:5:\"ctime\";}s:8:\"key_name\";a:7:{s:3:\"uid\";s:8:\"使用者ID\";s:5:\"uname\";s:12:\"使用者帳號\";s:5:\"email\";s:5:\"Email\";s:3:\"sex\";s:6:\"性別\";s:10:\"user_group\";s:9:\"使用者組\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:12:\"註冊時間\";}s:8:\"key_type\";a:7:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:10:\"user_group\";s:6:\"select\";s:13:\"user_category\";s:4:\"text\";s:5:\"ctime\";s:4:\"date\";}s:9:\"key_tishi\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}}', '2012-08-30 09:29:17');
INSERT INTO `ts_system_config` VALUES ('4248', 'pageKey', 'admin_Config_audit', 'a:6:{s:3:\"key\";a:3:{s:4:\"open\";s:4:\"open\";s:8:\"keywords\";s:8:\"keywords\";s:7:\"replace\";s:7:\"replace\";}s:8:\"key_name\";a:3:{s:4:\"open\";s:27:\"是否開啟敏感詞過濾\";s:8:\"keywords\";s:9:\"敏感詞\";s:7:\"replace\";s:18:\"敏感詞替換為\";}s:8:\"key_type\";a:3:{s:4:\"open\";s:5:\"radio\";s:8:\"keywords\";s:10:\"stringText\";s:7:\"replace\";s:4:\"text\";}s:11:\"key_default\";a:3:{s:4:\"open\";s:1:\"1\";s:8:\"keywords\";s:0:\"\";s:7:\"replace\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:4:\"open\";s:0:\"\";s:8:\"keywords\";s:55:\"按回車添加，多個輸入後用英文逗號,分割\";s:7:\"replace\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:4:\"open\";s:0:\"\";s:8:\"keywords\";s:0:\"\";s:7:\"replace\";s:0:\"\";}}', '2013-02-28 17:30:31');
INSERT INTO `ts_system_config` VALUES ('1346', 'pageKey', 'admin_Home_tag', 'a:4:{s:3:\"key\";a:4:{s:6:\"tag_id\";s:6:\"tag_id\";s:5:\"table\";s:5:\"table\";s:4:\"name\";s:4:\"name\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:6:\"tag_id\";s:8:\"標籤ID\";s:5:\"table\";s:12:\"標籤類型\";s:4:\"name\";s:9:\"標簽名\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:6:\"tag_id\";s:1:\"0\";s:5:\"table\";s:1:\"0\";s:4:\"name\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:6:\"tag_id\";s:0:\"\";s:5:\"table\";s:0:\"\";s:4:\"name\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-23 16:24:03');
INSERT INTO `ts_system_config` VALUES ('811', 'pageKey', 'admin_Home_logs', 'a:4:{s:3:\"key\";a:10:{s:2:\"id\";s:2:\"id\";s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:8:\"app_name\";s:8:\"app_name\";s:2:\"ip\";s:2:\"ip\";s:4:\"data\";s:4:\"data\";s:5:\"ctime\";s:5:\"ctime\";s:7:\"isAdmin\";s:7:\"isAdmin\";s:9:\"type_info\";s:9:\"type_info\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:2:\"id\";s:2:\"ID\";s:3:\"uid\";s:8:\"使用者ID\";s:5:\"uname\";s:12:\"使用者帳號\";s:8:\"app_name\";s:12:\"操作詳情\";s:2:\"ip\";s:2:\"IP\";s:4:\"data\";s:12:\"日誌資料\";s:5:\"ctime\";s:12:\"記錄時間\";s:7:\"isAdmin\";s:6:\"類型\";s:9:\"type_info\";s:12:\"日誌類型\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:2:\"id\";s:1:\"1\";s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:8:\"app_name\";s:1:\"0\";s:2:\"ip\";s:1:\"0\";s:4:\"data\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:7:\"isAdmin\";s:1:\"0\";s:9:\"type_info\";s:1:\"1\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:2:\"id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:2:\"ip\";s:0:\"\";s:4:\"data\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:7:\"isAdmin\";s:0:\"\";s:9:\"type_info\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-03 11:45:09');
INSERT INTO `ts_system_config` VALUES ('119', 'pageKey', 'admin_Home_logsArchive', 'a:4:{s:3:\"key\";a:10:{s:4:\"Name\";s:4:\"Name\";s:6:\"Engine\";s:6:\"Engine\";s:7:\"Version\";s:7:\"Version\";s:4:\"Rows\";s:4:\"Rows\";s:11:\"Data_length\";s:11:\"Data_length\";s:9:\"Data_free\";s:9:\"Data_free\";s:11:\"Create_time\";s:11:\"Create_time\";s:11:\"Update_time\";s:11:\"Update_time\";s:9:\"Collation\";s:9:\"Collation\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:4:\"Name\";s:6:\"表名\";s:6:\"Engine\";s:12:\"資料引擎\";s:7:\"Version\";s:6:\"版本\";s:4:\"Rows\";s:6:\"條數\";s:11:\"Data_length\";s:12:\"資料大小\";s:9:\"Data_free\";s:12:\"資料空閒\";s:11:\"Create_time\";s:12:\"創建時間\";s:11:\"Update_time\";s:12:\"最後紀錄\";s:9:\"Collation\";s:9:\"字符集\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:4:\"Name\";s:1:\"0\";s:6:\"Engine\";s:1:\"0\";s:7:\"Version\";s:1:\"1\";s:4:\"Rows\";s:1:\"0\";s:11:\"Data_length\";s:1:\"0\";s:9:\"Data_free\";s:1:\"1\";s:11:\"Create_time\";s:1:\"0\";s:11:\"Update_time\";s:1:\"0\";s:9:\"Collation\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:4:\"Name\";s:0:\"\";s:6:\"Engine\";s:0:\"\";s:7:\"Version\";s:0:\"\";s:4:\"Rows\";s:0:\"\";s:11:\"Data_length\";s:0:\"\";s:9:\"Data_free\";s:0:\"\";s:11:\"Create_time\";s:0:\"\";s:11:\"Update_time\";s:0:\"\";s:9:\"Collation\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-03-28 18:49:33');
INSERT INTO `ts_system_config` VALUES ('2096', 'pageKey', 'admin_Config_creditType', 'a:4:{s:3:\"key\";a:3:{s:10:\"CreditType\";s:10:\"CreditType\";s:10:\"CreditName\";s:10:\"CreditName\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:3:{s:10:\"CreditType\";s:12:\"積分類型\";s:10:\"CreditName\";s:6:\"別名\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:3:{s:10:\"CreditType\";s:1:\"0\";s:10:\"CreditName\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:3:{s:10:\"CreditType\";s:0:\"\";s:10:\"CreditName\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-10-26 00:22:56');
INSERT INTO `ts_system_config` VALUES ('1891', 'pageKey', 'admin_Config_addCreditType', 'a:6:{s:3:\"key\";a:2:{s:10:\"CreditType\";s:10:\"CreditType\";s:10:\"CreditName\";s:10:\"CreditName\";}s:8:\"key_name\";a:2:{s:10:\"CreditType\";s:12:\"積分類型\";s:10:\"CreditName\";s:6:\"別名\";}s:8:\"key_type\";a:2:{s:10:\"CreditType\";s:4:\"text\";s:10:\"CreditName\";s:4:\"text\";}s:11:\"key_default\";a:2:{s:10:\"CreditType\";s:0:\"\";s:10:\"CreditName\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:10:\"CreditType\";s:0:\"\";s:10:\"CreditName\";s:29:\"如\"積分，經驗，威望\"\";}s:14:\"key_javascript\";a:2:{s:10:\"CreditType\";s:0:\"\";s:10:\"CreditName\";s:0:\"\";}}', '2012-09-14 15:23:16');
INSERT INTO `ts_system_config` VALUES ('3536', 'pageKey', 'admin_Config_creditSet', 'a:6:{s:3:\"key\";a:6:{s:9:\"uid_chose\";s:9:\"uid_chose\";s:4:\"uids\";s:4:\"uids\";s:9:\"userGroup\";s:9:\"userGroup\";s:10:\"creditType\";s:10:\"creditType\";s:4:\"todo\";s:4:\"todo\";s:4:\"nums\";s:4:\"nums\";}s:8:\"key_name\";a:6:{s:9:\"uid_chose\";s:12:\"指定使用者\";s:4:\"uids\";s:6:\"使用者\";s:9:\"userGroup\";s:9:\"使用者組\";s:10:\"creditType\";s:12:\"積分類型\";s:4:\"todo\";s:6:\"操作\";s:4:\"nums\";s:6:\"數量\";}s:8:\"key_type\";a:6:{s:9:\"uid_chose\";s:5:\"radio\";s:4:\"uids\";s:4:\"user\";s:9:\"userGroup\";s:6:\"select\";s:10:\"creditType\";s:6:\"select\";s:4:\"todo\";s:5:\"radio\";s:4:\"nums\";s:4:\"text\";}s:11:\"key_default\";a:6:{s:9:\"uid_chose\";s:0:\"\";s:4:\"uids\";s:0:\"\";s:9:\"userGroup\";s:0:\"\";s:10:\"creditType\";s:0:\"\";s:4:\"todo\";s:1:\"1\";s:4:\"nums\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:9:\"uid_chose\";s:0:\"\";s:4:\"uids\";s:0:\"\";s:9:\"userGroup\";s:0:\"\";s:10:\"creditType\";s:0:\"\";s:4:\"todo\";s:0:\"\";s:4:\"nums\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:9:\"uid_chose\";s:27:\"admin.setCredit(this.value)\";s:4:\"uids\";s:0:\"\";s:9:\"userGroup\";s:0:\"\";s:10:\"creditType\";s:0:\"\";s:4:\"todo\";s:0:\"\";s:4:\"nums\";s:0:\"\";}}', '2013-02-20 00:29:51');
INSERT INTO `ts_system_config` VALUES ('1071', 'pageKey', 'admin_Department_index', 'a:6:{s:3:\"key\";a:6:{s:13:\"department_id\";s:13:\"department_id\";s:5:\"title\";s:5:\"title\";s:14:\"parent_dept_id\";s:14:\"parent_dept_id\";s:13:\"display_order\";s:13:\"display_order\";s:5:\"ctime\";s:5:\"ctime\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:13:\"department_id\";s:8:\"部門ID\";s:5:\"title\";s:12:\"部門名稱\";s:14:\"parent_dept_id\";s:12:\"上級部門\";s:13:\"display_order\";s:6:\"順序\";s:5:\"ctime\";s:12:\"添加時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:8:\"key_type\";a:6:{s:13:\"department_id\";s:6:\"hidden\";s:5:\"title\";s:4:\"text\";s:14:\"parent_dept_id\";s:6:\"select\";s:13:\"display_order\";s:6:\"hidden\";s:5:\"ctime\";s:6:\"hidden\";s:8:\"DOACTION\";s:6:\"hidden\";}s:11:\"key_default\";a:6:{s:13:\"department_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:14:\"parent_dept_id\";s:0:\"\";s:13:\"display_order\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:13:\"department_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:14:\"parent_dept_id\";s:0:\"\";s:13:\"display_order\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:13:\"department_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:14:\"parent_dept_id\";s:38:\"admin.selectDepart(this.value,$(this))\";s:13:\"display_order\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-13 12:50:00');
INSERT INTO `ts_system_config` VALUES ('4752', 'pageKey', 'admin_UserGroup_index', 'a:4:{s:3:\"key\";a:7:{s:13:\"user_group_id\";s:13:\"user_group_id\";s:8:\"app_name\";s:8:\"app_name\";s:15:\"user_group_name\";s:15:\"user_group_name\";s:15:\"user_group_type\";s:15:\"user_group_type\";s:15:\"user_group_icon\";s:15:\"user_group_icon\";s:15:\"is_authenticate\";s:15:\"is_authenticate\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:13:\"user_group_id\";s:11:\"使用者組ID\";s:8:\"app_name\";s:9:\"應用名\";s:15:\"user_group_name\";s:15:\"使用者組名稱\";s:15:\"user_group_type\";s:15:\"使用者組類型\";s:15:\"user_group_icon\";s:15:\"使用者組圖示\";s:15:\"is_authenticate\";s:18:\"是否為認證組\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:13:\"user_group_id\";s:1:\"1\";s:8:\"app_name\";s:1:\"0\";s:15:\"user_group_name\";s:1:\"0\";s:15:\"user_group_type\";s:1:\"1\";s:15:\"user_group_icon\";s:1:\"0\";s:15:\"is_authenticate\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:13:\"user_group_id\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:15:\"user_group_name\";s:0:\"\";s:15:\"user_group_type\";s:0:\"\";s:15:\"user_group_icon\";s:0:\"\";s:15:\"is_authenticate\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-11 16:53:44');
INSERT INTO `ts_system_config` VALUES ('4754', 'pageKey', 'admin_UserGroup_addUsergroup', 'a:6:{s:3:\"key\";a:5:{s:13:\"user_group_id\";s:13:\"user_group_id\";s:15:\"user_group_name\";s:15:\"user_group_name\";s:15:\"user_group_icon\";s:15:\"user_group_icon\";s:15:\"user_group_type\";s:15:\"user_group_type\";s:15:\"is_authenticate\";s:15:\"is_authenticate\";}s:8:\"key_name\";a:5:{s:13:\"user_group_id\";s:11:\"使用者組ID\";s:15:\"user_group_name\";s:15:\"使用者組名稱\";s:15:\"user_group_icon\";s:15:\"使用者組圖示\";s:15:\"user_group_type\";s:15:\"使用者組類型\";s:15:\"is_authenticate\";s:18:\"是否為認證組\";}s:8:\"key_type\";a:5:{s:13:\"user_group_id\";s:6:\"hidden\";s:15:\"user_group_name\";s:4:\"text\";s:15:\"user_group_icon\";s:5:\"radio\";s:15:\"user_group_type\";s:6:\"hidden\";s:15:\"is_authenticate\";s:5:\"radio\";}s:11:\"key_default\";a:5:{s:13:\"user_group_id\";s:0:\"\";s:15:\"user_group_name\";s:0:\"\";s:15:\"user_group_icon\";s:2:\"-1\";s:15:\"user_group_type\";s:1:\"0\";s:15:\"is_authenticate\";s:1:\"0\";}s:9:\"key_tishi\";a:5:{s:13:\"user_group_id\";s:0:\"\";s:15:\"user_group_name\";s:0:\"\";s:15:\"user_group_icon\";s:108:\"可自由上傳圖示到addons/theme/stv1/_stastic/image/usergroup，選無則表示該使用者組沒有圖示\";s:15:\"user_group_type\";s:0:\"\";s:15:\"is_authenticate\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:13:\"user_group_id\";s:0:\"\";s:15:\"user_group_name\";s:0:\"\";s:15:\"user_group_icon\";s:0:\"\";s:15:\"user_group_type\";s:0:\"\";s:15:\"is_authenticate\";s:0:\"\";}}', '2013-03-11 16:59:08');
INSERT INTO `ts_system_config` VALUES ('126', 'pageKey', 'admin_Home_newschedule', 'a:6:{s:3:\"key\";a:8:{s:11:\"task_to_run\";s:11:\"task_to_run\";s:13:\"schedule_type\";s:13:\"schedule_type\";s:8:\"modifier\";s:8:\"modifier\";s:7:\"dirlist\";s:7:\"dirlist\";s:5:\"month\";s:5:\"month\";s:14:\"start_datetime\";s:14:\"start_datetime\";s:12:\"end_datetime\";s:12:\"end_datetime\";s:4:\"info\";s:4:\"info\";}s:8:\"key_name\";a:8:{s:11:\"task_to_run\";s:12:\"執行函數\";s:13:\"schedule_type\";s:12:\"任務類型\";s:8:\"modifier\";s:12:\"執行頻率\";s:7:\"dirlist\";s:7:\"dirlist\";s:5:\"month\";s:5:\"month\";s:14:\"start_datetime\";s:12:\"開始時間\";s:12:\"end_datetime\";s:12:\"結束時間\";s:4:\"info\";s:6:\"簡介\";}s:8:\"key_type\";a:8:{s:11:\"task_to_run\";s:4:\"text\";s:13:\"schedule_type\";s:4:\"text\";s:8:\"modifier\";s:4:\"text\";s:7:\"dirlist\";s:4:\"text\";s:5:\"month\";s:4:\"text\";s:14:\"start_datetime\";s:4:\"date\";s:12:\"end_datetime\";s:4:\"date\";s:4:\"info\";s:4:\"text\";}s:11:\"key_default\";a:8:{s:11:\"task_to_run\";s:0:\"\";s:13:\"schedule_type\";s:0:\"\";s:8:\"modifier\";s:0:\"\";s:7:\"dirlist\";s:0:\"\";s:5:\"month\";s:0:\"\";s:14:\"start_datetime\";s:0:\"\";s:12:\"end_datetime\";s:0:\"\";s:4:\"info\";s:0:\"\";}s:9:\"key_tishi\";a:8:{s:11:\"task_to_run\";s:58:\"計劃任務執行的函數，格式為：app/Model/method\";s:13:\"schedule_type\";s:56:\"ONCE、MINUTE、HOURLY、DAILY、WEEKLY、MONTHLY 之一\";s:8:\"modifier\";s:75:\"類型為MONTHLY時必須；ONCE時無效；其他時為可選，默認為1\";s:7:\"dirlist\";s:100:\"指定周或月的一天或多天。只與WEEKLY和MONTHLY共同使用時有效，其他時忽略。\";s:5:\"month\";s:191:\"指定一年中的一個月或多個月.只在schedule_type=MONTHLY時有效，其他時忽略。當modifier=LASTDAY時必須，其他時可選。有效值：Jan - Dec，默認為*(每個月)\";s:14:\"start_datetime\";s:50:\"任務啟動時間，使用“Y-m-d H:i:s”格式\";s:12:\"end_datetime\";s:44:\"失效時間，使用“Y-m-d H:i:s”格式\";s:4:\"info\";s:30:\"對計劃任務的簡要描述\";}s:14:\"key_javascript\";a:8:{s:11:\"task_to_run\";s:0:\"\";s:13:\"schedule_type\";s:0:\"\";s:8:\"modifier\";s:0:\"\";s:7:\"dirlist\";s:0:\"\";s:5:\"month\";s:0:\"\";s:14:\"start_datetime\";s:0:\"\";s:12:\"end_datetime\";s:0:\"\";s:4:\"info\";s:0:\"\";}}', '2012-04-07 18:41:19');
INSERT INTO `ts_system_config` VALUES ('127', 'pageKey', 'admin_Home_schedule', 'a:4:{s:3:\"key\";a:10:{s:2:\"id\";s:2:\"id\";s:6:\"method\";s:6:\"method\";s:13:\"schedule_type\";s:13:\"schedule_type\";s:8:\"modifier\";s:8:\"modifier\";s:7:\"dirlist\";s:7:\"dirlist\";s:5:\"month\";s:5:\"month\";s:14:\"start_datetime\";s:14:\"start_datetime\";s:12:\"end_datetime\";s:12:\"end_datetime\";s:13:\"last_run_time\";s:13:\"last_run_time\";s:4:\"info\";s:4:\"info\";}s:8:\"key_name\";a:10:{s:2:\"id\";s:2:\"ID\";s:6:\"method\";s:12:\"執行函數\";s:13:\"schedule_type\";s:6:\"類型\";s:8:\"modifier\";s:12:\"執行頻率\";s:7:\"dirlist\";s:7:\"dirlist\";s:5:\"month\";s:5:\"month\";s:14:\"start_datetime\";s:12:\"開始時間\";s:12:\"end_datetime\";s:12:\"失效時間\";s:13:\"last_run_time\";s:12:\"上次執行\";s:4:\"info\";s:6:\"簡介\";}s:10:\"key_hidden\";a:10:{s:2:\"id\";s:1:\"0\";s:6:\"method\";s:1:\"0\";s:13:\"schedule_type\";s:1:\"0\";s:8:\"modifier\";s:1:\"0\";s:7:\"dirlist\";s:1:\"0\";s:5:\"month\";s:1:\"0\";s:14:\"start_datetime\";s:1:\"0\";s:12:\"end_datetime\";s:1:\"0\";s:13:\"last_run_time\";s:1:\"0\";s:4:\"info\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:2:\"id\";s:0:\"\";s:6:\"method\";s:0:\"\";s:13:\"schedule_type\";s:0:\"\";s:8:\"modifier\";s:0:\"\";s:7:\"dirlist\";s:0:\"\";s:5:\"month\";s:0:\"\";s:14:\"start_datetime\";s:0:\"\";s:12:\"end_datetime\";s:0:\"\";s:13:\"last_run_time\";s:0:\"\";s:4:\"info\";s:0:\"\";}}', '2012-04-07 18:52:36');
INSERT INTO `ts_system_config` VALUES ('1448', 'pageKey', 'admin_Apps_index', 'a:4:{s:3:\"key\";a:6:{s:6:\"app_id\";s:6:\"app_id\";s:8:\"icon_url\";s:8:\"icon_url\";s:8:\"app_name\";s:8:\"app_name\";s:9:\"app_alias\";s:9:\"app_alias\";s:6:\"status\";s:6:\"status\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:6:\"app_id\";s:8:\"應用ID\";s:8:\"icon_url\";s:6:\"圖示\";s:8:\"app_name\";s:9:\"應用名\";s:9:\"app_alias\";s:6:\"別名\";s:6:\"status\";s:6:\"狀態\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:6:\"app_id\";s:1:\"1\";s:8:\"icon_url\";s:1:\"0\";s:8:\"app_name\";s:1:\"0\";s:9:\"app_alias\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:6:\"app_id\";s:0:\"\";s:8:\"icon_url\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:9:\"app_alias\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-24 14:14:29');
INSERT INTO `ts_system_config` VALUES ('652', 'pageKey', 'admin_Apps_install', 'a:4:{s:3:\"key\";a:7:{s:8:\"icon_url\";s:8:\"icon_url\";s:8:\"app_name\";s:8:\"app_name\";s:9:\"app_alias\";s:9:\"app_alias\";s:11:\"description\";s:11:\"description\";s:15:\"host_type_alias\";s:15:\"host_type_alias\";s:12:\"company_name\";s:12:\"company_name\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:8:\"icon_url\";s:12:\"圖示地址\";s:8:\"app_name\";s:12:\"應用名稱\";s:9:\"app_alias\";s:12:\"應用別名\";s:11:\"description\";s:12:\"應用描述\";s:15:\"host_type_alias\";s:12:\"託管類型\";s:12:\"company_name\";s:12:\"公司名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:8:\"icon_url\";s:1:\"0\";s:8:\"app_name\";s:1:\"0\";s:9:\"app_alias\";s:1:\"0\";s:11:\"description\";s:1:\"0\";s:15:\"host_type_alias\";s:1:\"0\";s:12:\"company_name\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:8:\"icon_url\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:9:\"app_alias\";s:0:\"\";s:11:\"description\";s:0:\"\";s:15:\"host_type_alias\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-08 03:18:30');
INSERT INTO `ts_system_config` VALUES ('6517', 'pageKey', 'admin_Apps_preinstall', 'a:6:{s:3:\"key\";a:19:{s:6:\"app_id\";s:6:\"app_id\";s:8:\"app_name\";s:8:\"app_name\";s:9:\"app_alias\";s:9:\"app_alias\";s:9:\"app_entry\";s:9:\"app_entry\";s:11:\"description\";s:11:\"description\";s:6:\"status\";s:6:\"status\";s:9:\"host_type\";s:9:\"host_type\";s:8:\"icon_url\";s:8:\"icon_url\";s:14:\"large_icon_url\";s:14:\"large_icon_url\";s:11:\"admin_entry\";s:11:\"admin_entry\";s:16:\"statistics_entry\";s:16:\"statistics_entry\";s:12:\"company_name\";s:12:\"company_name\";s:13:\"display_order\";s:13:\"display_order\";s:7:\"version\";s:7:\"version\";s:7:\"api_key\";s:7:\"api_key\";s:10:\"secure_key\";s:10:\"secure_key\";s:13:\"add_front_top\";s:13:\"add_front_top\";s:17:\"add_front_applist\";s:17:\"add_front_applist\";s:9:\"add_tonav\";s:9:\"add_tonav\";}s:8:\"key_name\";a:19:{s:6:\"app_id\";s:8:\"應用ID\";s:8:\"app_name\";s:15:\"應用英文名\";s:9:\"app_alias\";s:12:\"應用名稱\";s:9:\"app_entry\";s:12:\"前臺入口\";s:11:\"description\";s:12:\"應用描述\";s:6:\"status\";s:12:\"應用狀態\";s:9:\"host_type\";s:12:\"託管類別\";s:8:\"icon_url\";s:12:\"圖示地址\";s:14:\"large_icon_url\";s:15:\"大圖示地址\";s:11:\"admin_entry\";s:12:\"後臺入口\";s:16:\"statistics_entry\";s:12:\"統計入口\";s:12:\"company_name\";s:12:\"公司名稱\";s:13:\"display_order\";s:6:\"排序\";s:7:\"version\";s:6:\"版本\";s:7:\"api_key\";s:7:\"API_KEY\";s:10:\"secure_key\";s:10:\"SECURE_KEY\";s:13:\"add_front_top\";s:24:\"添加到前臺應用框\";s:17:\"add_front_applist\";s:27:\"添加到前臺應用列表\";s:9:\"add_tonav\";s:15:\"添加到導航\";}s:8:\"key_type\";a:19:{s:6:\"app_id\";s:6:\"hidden\";s:8:\"app_name\";s:6:\"hidden\";s:9:\"app_alias\";s:4:\"text\";s:9:\"app_entry\";s:6:\"hidden\";s:11:\"description\";s:8:\"textarea\";s:6:\"status\";s:5:\"radio\";s:9:\"host_type\";s:6:\"hidden\";s:8:\"icon_url\";s:6:\"hidden\";s:14:\"large_icon_url\";s:6:\"hidden\";s:11:\"admin_entry\";s:6:\"hidden\";s:16:\"statistics_entry\";s:6:\"hidden\";s:12:\"company_name\";s:4:\"text\";s:13:\"display_order\";s:6:\"hidden\";s:7:\"version\";s:4:\"text\";s:7:\"api_key\";s:6:\"hidden\";s:10:\"secure_key\";s:6:\"hidden\";s:13:\"add_front_top\";s:5:\"radio\";s:17:\"add_front_applist\";s:5:\"radio\";s:9:\"add_tonav\";s:5:\"radio\";}s:11:\"key_default\";a:19:{s:6:\"app_id\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:9:\"app_alias\";s:0:\"\";s:9:\"app_entry\";s:0:\"\";s:11:\"description\";s:0:\"\";s:6:\"status\";s:1:\"1\";s:9:\"host_type\";s:0:\"\";s:8:\"icon_url\";s:0:\"\";s:14:\"large_icon_url\";s:0:\"\";s:11:\"admin_entry\";s:0:\"\";s:16:\"statistics_entry\";s:0:\"\";s:12:\"company_name\";s:12:\"智士軟體\";s:13:\"display_order\";s:0:\"\";s:7:\"version\";s:0:\"\";s:7:\"api_key\";s:0:\"\";s:10:\"secure_key\";s:0:\"\";s:13:\"add_front_top\";s:1:\"1\";s:17:\"add_front_applist\";s:1:\"1\";s:9:\"add_tonav\";s:1:\"1\";}s:9:\"key_tishi\";a:19:{s:6:\"app_id\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:9:\"app_alias\";s:39:\"前臺展示的應用名稱（必填）\";s:9:\"app_entry\";s:0:\"\";s:11:\"description\";s:27:\"前臺展示的應用簡介\";s:6:\"status\";s:0:\"\";s:9:\"host_type\";s:0:\"\";s:8:\"icon_url\";s:0:\"\";s:14:\"large_icon_url\";s:0:\"\";s:11:\"admin_entry\";s:0:\"\";s:16:\"statistics_entry\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:13:\"display_order\";s:0:\"\";s:7:\"version\";s:0:\"\";s:7:\"api_key\";s:0:\"\";s:10:\"secure_key\";s:0:\"\";s:13:\"add_front_top\";s:0:\"\";s:17:\"add_front_applist\";s:0:\"\";s:9:\"add_tonav\";s:0:\"\";}s:14:\"key_javascript\";a:19:{s:6:\"app_id\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:9:\"app_alias\";s:0:\"\";s:9:\"app_entry\";s:0:\"\";s:11:\"description\";s:0:\"\";s:6:\"status\";s:0:\"\";s:9:\"host_type\";s:0:\"\";s:8:\"icon_url\";s:0:\"\";s:14:\"large_icon_url\";s:0:\"\";s:11:\"admin_entry\";s:0:\"\";s:16:\"statistics_entry\";s:0:\"\";s:12:\"company_name\";s:0:\"\";s:13:\"display_order\";s:0:\"\";s:7:\"version\";s:0:\"\";s:7:\"api_key\";s:0:\"\";s:10:\"secure_key\";s:0:\"\";s:13:\"add_front_top\";s:0:\"\";s:17:\"add_front_applist\";s:0:\"\";s:9:\"add_tonav\";s:0:\"\";}}', '2013-05-10 13:49:36');
INSERT INTO `ts_system_config` VALUES ('732', 'pageKey', 'admin_Config_nav', 'a:4:{s:3:\"key\";a:12:{s:7:\"navi_id\";s:7:\"navi_id\";s:9:\"navi_name\";s:9:\"navi_name\";s:8:\"app_name\";s:8:\"app_name\";s:3:\"url\";s:3:\"url\";s:6:\"target\";s:6:\"target\";s:6:\"status\";s:6:\"status\";s:8:\"position\";s:8:\"position\";s:5:\"guest\";s:5:\"guest\";s:11:\"is_app_navi\";s:11:\"is_app_navi\";s:9:\"parent_id\";s:9:\"parent_id\";s:10:\"order_sort\";s:10:\"order_sort\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:12:{s:7:\"navi_id\";s:8:\"導航ID\";s:9:\"navi_name\";s:12:\"導航名稱\";s:8:\"app_name\";s:12:\"英文名稱\";s:3:\"url\";s:12:\"連結地址\";s:6:\"target\";s:12:\"開啟方式\";s:6:\"status\";s:6:\"狀態\";s:8:\"position\";s:12:\"導航位置\";s:5:\"guest\";s:12:\"遊客可見\";s:11:\"is_app_navi\";s:18:\"應用內部導航\";s:9:\"parent_id\";s:9:\"父導航\";s:10:\"order_sort\";s:12:\"應用排序\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:12:{s:7:\"navi_id\";s:1:\"0\";s:9:\"navi_name\";s:1:\"0\";s:8:\"app_name\";s:1:\"0\";s:3:\"url\";s:1:\"0\";s:6:\"target\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"position\";s:1:\"0\";s:5:\"guest\";s:1:\"1\";s:11:\"is_app_navi\";s:1:\"1\";s:9:\"parent_id\";s:1:\"1\";s:10:\"order_sort\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:12:{s:7:\"navi_id\";s:0:\"\";s:9:\"navi_name\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:3:\"url\";s:0:\"\";s:6:\"target\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:11:\"is_app_navi\";s:0:\"\";s:9:\"parent_id\";s:0:\"\";s:10:\"order_sort\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-21 15:41:00');
INSERT INTO `ts_system_config` VALUES ('6516', 'pageKey', 'admin_Config_site', 'a:6:{s:3:\"key\";a:19:{s:11:\"site_closed\";s:11:\"site_closed\";s:9:\"site_name\";s:9:\"site_name\";s:11:\"site_slogan\";s:11:\"site_slogan\";s:20:\"site_header_keywords\";s:20:\"site_header_keywords\";s:23:\"site_header_description\";s:23:\"site_header_description\";s:12:\"site_company\";s:12:\"site_company\";s:11:\"site_footer\";s:11:\"site_footer\";s:9:\"site_logo\";s:9:\"site_logo\";s:8:\"login_bg\";s:8:\"login_bg\";s:18:\"site_closed_reason\";s:18:\"site_closed_reason\";s:10:\"sys_domain\";s:10:\"sys_domain\";s:12:\"sys_nickname\";s:12:\"sys_nickname\";s:9:\"sys_email\";s:9:\"sys_email\";s:9:\"home_page\";s:9:\"home_page\";s:15:\"site_theme_name\";s:15:\"site_theme_name\";s:11:\"sys_version\";s:11:\"sys_version\";s:17:\"site_online_count\";s:17:\"site_online_count\";s:15:\"site_rewrite_on\";s:15:\"site_rewrite_on\";s:19:\"site_analytics_code\";s:19:\"site_analytics_code\";}s:8:\"key_name\";a:19:{s:11:\"site_closed\";s:12:\"站點狀態\";s:9:\"site_name\";s:12:\"站點名稱\";s:11:\"site_slogan\";s:12:\"站點口號\";s:20:\"site_header_keywords\";s:24:\"頁面頭資訊關鍵字\";s:23:\"site_header_description\";s:21:\"頁面頭資訊描述\";s:12:\"site_company\";s:12:\"公司名稱\";s:11:\"site_footer\";s:12:\"版權資訊\";s:9:\"site_logo\";s:10:\"站點logo\";s:8:\"login_bg\";s:21:\"登入頁面背景圖\";s:18:\"site_closed_reason\";s:18:\"站點關閉理由\";s:10:\"sys_domain\";s:18:\"站點預留域名\";s:12:\"sys_nickname\";s:18:\"站點預留昵稱\";s:9:\"sys_email\";s:12:\"客服郵箱\";s:9:\"home_page\";s:12:\"默認首頁\";s:15:\"site_theme_name\";s:15:\"站點風格包\";s:11:\"sys_version\";s:8:\"JS版本\";s:17:\"site_online_count\";s:12:\"站點統計\";s:15:\"site_rewrite_on\";s:15:\"偽靜態開關\";s:19:\"site_analytics_code\";s:21:\"第三方統計程式碼\";}s:8:\"key_type\";a:19:{s:11:\"site_closed\";s:5:\"radio\";s:9:\"site_name\";s:4:\"text\";s:11:\"site_slogan\";s:4:\"text\";s:20:\"site_header_keywords\";s:4:\"text\";s:23:\"site_header_description\";s:4:\"text\";s:12:\"site_company\";s:6:\"hidden\";s:11:\"site_footer\";s:4:\"text\";s:9:\"site_logo\";s:5:\"image\";s:8:\"login_bg\";s:5:\"image\";s:18:\"site_closed_reason\";s:8:\"textarea\";s:10:\"sys_domain\";s:10:\"stringText\";s:12:\"sys_nickname\";s:10:\"stringText\";s:9:\"sys_email\";s:4:\"text\";s:9:\"home_page\";s:6:\"select\";s:15:\"site_theme_name\";s:6:\"select\";s:11:\"sys_version\";s:4:\"text\";s:17:\"site_online_count\";s:5:\"radio\";s:15:\"site_rewrite_on\";s:5:\"radio\";s:19:\"site_analytics_code\";s:8:\"textarea\";}s:11:\"key_default\";a:19:{s:11:\"site_closed\";s:1:\"1\";s:9:\"site_name\";s:0:\"\";s:11:\"site_slogan\";s:0:\"\";s:20:\"site_header_keywords\";s:0:\"\";s:23:\"site_header_description\";s:0:\"\";s:12:\"site_company\";s:0:\"\";s:11:\"site_footer\";s:96:\"Copyright 2012 ZhishiSoft All Rights Reserved. 智士軟體（北京）有限公司 版權所有\";s:9:\"site_logo\";s:0:\"\";s:8:\"login_bg\";s:0:\"\";s:18:\"site_closed_reason\";s:30:\"抱歉，本站暫停訪問。\";s:10:\"sys_domain\";s:0:\"\";s:12:\"sys_nickname\";s:0:\"\";s:9:\"sys_email\";s:0:\"\";s:9:\"home_page\";s:0:\"\";s:15:\"site_theme_name\";s:0:\"\";s:11:\"sys_version\";s:0:\"\";s:17:\"site_online_count\";s:1:\"0\";s:15:\"site_rewrite_on\";s:1:\"0\";s:19:\"site_analytics_code\";s:0:\"\";}s:9:\"key_tishi\";a:19:{s:11:\"site_closed\";s:0:\"\";s:9:\"site_name\";s:0:\"\";s:11:\"site_slogan\";s:44:\"會顯示在默認登入頁、默認title中\";s:20:\"site_header_keywords\";s:0:\"\";s:23:\"site_header_description\";s:0:\"\";s:12:\"site_company\";s:0:\"\";s:11:\"site_footer\";s:51:\"如:Copyright 2012 ZhishiSoft All Rights Reserved. \";s:9:\"site_logo\";s:0:\"\";s:8:\"login_bg\";s:0:\"\";s:18:\"site_closed_reason\";s:0:\"\";s:10:\"sys_domain\";s:55:\"按回車添加，多個輸入後用英文逗號,分割\";s:12:\"sys_nickname\";s:55:\"按回車添加，多個輸入後用英文逗號,分割\";s:9:\"sys_email\";s:0:\"\";s:9:\"home_page\";s:0:\"\";s:15:\"site_theme_name\";s:0:\"\";s:11:\"sys_version\";s:89:\"JS、CSS等靜態檔案修改後，可以修改此版本號，重新整理使用者瀏覽器快取\";s:17:\"site_online_count\";s:0:\"\";s:15:\"site_rewrite_on\";s:76:\"請拷貝URLRewrite目錄下檔案到根目錄，根據Web伺服器來配置\";s:19:\"site_analytics_code\";s:0:\"\";}s:14:\"key_javascript\";a:19:{s:11:\"site_closed\";s:28:\"admin.siteConfig(this.value)\";s:9:\"site_name\";s:0:\"\";s:11:\"site_slogan\";s:0:\"\";s:20:\"site_header_keywords\";s:0:\"\";s:23:\"site_header_description\";s:0:\"\";s:12:\"site_company\";s:0:\"\";s:11:\"site_footer\";s:0:\"\";s:9:\"site_logo\";s:0:\"\";s:8:\"login_bg\";s:0:\"\";s:18:\"site_closed_reason\";s:0:\"\";s:10:\"sys_domain\";s:0:\"\";s:12:\"sys_nickname\";s:0:\"\";s:9:\"sys_email\";s:0:\"\";s:9:\"home_page\";s:0:\"\";s:15:\"site_theme_name\";s:0:\"\";s:11:\"sys_version\";s:0:\"\";s:17:\"site_online_count\";s:0:\"\";s:15:\"site_rewrite_on\";s:0:\"\";s:19:\"site_analytics_code\";s:0:\"\";}}', '2013-05-09 16:54:13');
INSERT INTO `ts_system_config` VALUES ('4252', 'pageKey', 'admin_Config_register', 'a:6:{s:3:\"key\";a:16:{s:13:\"register_type\";s:13:\"register_type\";s:12:\"email_suffix\";s:12:\"email_suffix\";s:7:\"captcha\";s:7:\"captcha\";s:14:\"register_audit\";s:14:\"register_audit\";s:11:\"need_active\";s:11:\"need_active\";s:10:\"photo_open\";s:10:\"photo_open\";s:10:\"need_photo\";s:10:\"need_photo\";s:8:\"tag_open\";s:8:\"tag_open\";s:7:\"tag_num\";s:7:\"tag_num\";s:15:\"interester_open\";s:15:\"interester_open\";s:15:\"interester_rule\";s:15:\"interester_rule\";s:20:\"interester_recommend\";s:20:\"interester_recommend\";s:14:\"default_follow\";s:14:\"default_follow\";s:11:\"each_follow\";s:11:\"each_follow\";s:18:\"default_user_group\";s:18:\"default_user_group\";s:13:\"welcome_email\";s:13:\"welcome_email\";}s:8:\"key_name\";a:16:{s:13:\"register_type\";s:12:\"註冊方式\";s:12:\"email_suffix\";s:12:\"郵箱字尾\";s:7:\"captcha\";s:15:\"註冊驗證碼\";s:14:\"register_audit\";s:12:\"需要稽覈\";s:11:\"need_active\";s:12:\"需要啟用\";s:10:\"photo_open\";s:18:\"上傳頭像步驟\";s:10:\"need_photo\";s:18:\"強制上傳頭像\";s:8:\"tag_open\";s:24:\"設定個人標籤步驟\";s:7:\"tag_num\";s:24:\"允許設定標籤數量\";s:15:\"interester_open\";s:27:\"關注感興趣的人步驟\";s:15:\"interester_rule\";s:12:\"推薦規則\";s:20:\"interester_recommend\";s:18:\"推薦關注使用者\";s:14:\"default_follow\";s:18:\"默認關注使用者\";s:11:\"each_follow\";s:18:\"雙向關注使用者\";s:18:\"default_user_group\";s:15:\"默認使用者組\";s:13:\"welcome_email\";s:18:\"發送歡迎郵件\";}s:8:\"key_type\";a:16:{s:13:\"register_type\";s:5:\"radio\";s:12:\"email_suffix\";s:10:\"stringText\";s:7:\"captcha\";s:6:\"hidden\";s:14:\"register_audit\";s:5:\"radio\";s:11:\"need_active\";s:5:\"radio\";s:10:\"photo_open\";s:5:\"radio\";s:10:\"need_photo\";s:5:\"radio\";s:8:\"tag_open\";s:5:\"radio\";s:7:\"tag_num\";s:4:\"text\";s:15:\"interester_open\";s:5:\"radio\";s:15:\"interester_rule\";s:8:\"checkbox\";s:20:\"interester_recommend\";s:4:\"user\";s:14:\"default_follow\";s:4:\"user\";s:11:\"each_follow\";s:4:\"user\";s:18:\"default_user_group\";s:8:\"checkbox\";s:13:\"welcome_email\";s:5:\"radio\";}s:11:\"key_default\";a:16:{s:13:\"register_type\";s:4:\"open\";s:12:\"email_suffix\";s:0:\"\";s:7:\"captcha\";s:0:\"\";s:14:\"register_audit\";s:0:\"\";s:11:\"need_active\";s:0:\"\";s:10:\"photo_open\";s:0:\"\";s:10:\"need_photo\";s:0:\"\";s:8:\"tag_open\";s:0:\"\";s:7:\"tag_num\";s:0:\"\";s:15:\"interester_open\";s:0:\"\";s:15:\"interester_rule\";s:0:\"\";s:20:\"interester_recommend\";s:0:\"\";s:14:\"default_follow\";s:0:\"\";s:11:\"each_follow\";s:0:\"\";s:18:\"default_user_group\";s:0:\"\";s:13:\"welcome_email\";s:0:\"\";}s:9:\"key_tishi\";a:16:{s:13:\"register_type\";s:0:\"\";s:12:\"email_suffix\";s:129:\"只允許以上字尾的郵箱註冊站點。按回車添加，多個輸入後用英文逗號,分割，如“@qq.com,@sina.com”\";s:7:\"captcha\";s:0:\"\";s:14:\"register_audit\";s:0:\"\";s:11:\"need_active\";s:0:\"\";s:10:\"photo_open\";s:0:\"\";s:10:\"need_photo\";s:0:\"\";s:8:\"tag_open\";s:0:\"\";s:7:\"tag_num\";s:0:\"\";s:15:\"interester_open\";s:0:\"\";s:15:\"interester_rule\";s:0:\"\";s:20:\"interester_recommend\";s:0:\"\";s:14:\"default_follow\";s:0:\"\";s:11:\"each_follow\";s:0:\"\";s:18:\"default_user_group\";s:0:\"\";s:13:\"welcome_email\";s:0:\"\";}s:14:\"key_javascript\";a:16:{s:13:\"register_type\";s:0:\"\";s:12:\"email_suffix\";s:0:\"\";s:7:\"captcha\";s:0:\"\";s:14:\"register_audit\";s:0:\"\";s:11:\"need_active\";s:0:\"\";s:10:\"photo_open\";s:0:\"\";s:10:\"need_photo\";s:0:\"\";s:8:\"tag_open\";s:0:\"\";s:7:\"tag_num\";s:0:\"\";s:15:\"interester_open\";s:0:\"\";s:15:\"interester_rule\";s:0:\"\";s:20:\"interester_recommend\";s:0:\"\";s:14:\"default_follow\";s:0:\"\";s:11:\"each_follow\";s:0:\"\";s:18:\"default_user_group\";s:0:\"\";s:13:\"welcome_email\";s:0:\"\";}}', '2013-02-28 17:33:52');
INSERT INTO `ts_system_config` VALUES ('1073', 'pageKey', 'admin_Config_announcement', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:3:\"uid\";s:3:\"uid\";s:4:\"sort\";s:4:\"sort\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:2:\"ID\";s:5:\"title\";s:12:\"公告標題\";s:3:\"uid\";s:9:\"釋出者\";s:4:\"sort\";s:6:\"排序\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:4:\"sort\";s:1:\"1\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:4:\"sort\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-13 13:50:15');
INSERT INTO `ts_system_config` VALUES ('137', 'pageKey', 'admin_Config_addannoun', 'a:6:{s:3:\"key\";a:4:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:7:\"content\";s:7:\"content\";s:6:\"attach\";s:6:\"attach\";}s:8:\"key_name\";a:4:{s:2:\"id\";s:2:\"ID\";s:5:\"title\";s:12:\"公告標題\";s:7:\"content\";s:12:\"公告內容\";s:6:\"attach\";s:6:\"附件\";}s:8:\"key_type\";a:4:{s:2:\"id\";s:6:\"hidden\";s:5:\"title\";s:4:\"text\";s:7:\"content\";s:8:\"textarea\";s:6:\"attach\";s:4:\"file\";}s:11:\"key_default\";a:4:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";}}', '2012-04-12 17:39:54');
INSERT INTO `ts_system_config` VALUES ('138', 'pageKey', 'admin_Home_systemdata', 'a:4:{s:3:\"key\";a:4:{s:4:\"name\";s:4:\"name\";s:3:\"key\";s:3:\"key\";s:5:\"value\";s:5:\"value\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:4:\"name\";s:9:\"中文名\";s:3:\"key\";s:3:\"KEY\";s:5:\"value\";s:12:\"儲存的值\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:4:\"name\";s:1:\"0\";s:3:\"key\";s:1:\"0\";s:5:\"value\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";s:5:\"value\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-04-13 11:42:22');
INSERT INTO `ts_system_config` VALUES ('139', 'pageKey', 'admin_Home_addsystemdata', 'a:6:{s:3:\"key\";a:4:{s:2:\"id\";s:2:\"id\";s:4:\"name\";s:4:\"name\";s:3:\"key\";s:3:\"key\";s:5:\"value\";s:5:\"value\";}s:8:\"key_name\";a:4:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:9:\"中文名\";s:3:\"key\";s:3:\"KEY\";s:5:\"value\";s:12:\"儲存的值\";}s:8:\"key_type\";a:4:{s:2:\"id\";s:6:\"hidden\";s:4:\"name\";s:4:\"text\";s:3:\"key\";s:4:\"text\";s:5:\"value\";s:8:\"textarea\";}s:11:\"key_default\";a:4:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";s:5:\"value\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";s:5:\"value\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:3:\"key\";s:0:\"\";s:5:\"value\";s:0:\"\";}}', '2012-04-13 11:42:57');
INSERT INTO `ts_system_config` VALUES ('396', 'pageKey', 'admin_User_addProfileCategory', 'a:6:{s:3:\"key\";a:4:{s:4:\"type\";s:4:\"type\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:10:\"field_type\";s:10:\"field_type\";}s:8:\"key_name\";a:4:{s:4:\"type\";s:6:\"類型\";s:9:\"field_key\";s:12:\"分類鍵值\";s:10:\"field_name\";s:12:\"分類名稱\";s:10:\"field_type\";s:12:\"上級分類\";}s:8:\"key_type\";a:4:{s:4:\"type\";s:6:\"hidden\";s:9:\"field_key\";s:4:\"text\";s:10:\"field_name\";s:4:\"text\";s:10:\"field_type\";s:6:\"hidden\";}s:11:\"key_default\";a:4:{s:4:\"type\";s:1:\"1\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:1:\"0\";}s:9:\"key_tishi\";a:4:{s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";}}', '2012-05-28 20:33:18');
INSERT INTO `ts_system_config` VALUES ('1535', 'pageKey', 'admin_Config_addArticle', 'a:6:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:7:\"content\";s:7:\"content\";s:6:\"attach\";s:6:\"attach\";s:4:\"type\";s:4:\"type\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:2:\"ID\";s:5:\"title\";s:6:\"標題\";s:7:\"content\";s:6:\"內容\";s:6:\"attach\";s:6:\"附件\";s:4:\"type\";s:6:\"類型\";}s:8:\"key_type\";a:5:{s:2:\"id\";s:6:\"hidden\";s:5:\"title\";s:4:\"text\";s:7:\"content\";s:6:\"editor\";s:6:\"attach\";s:4:\"file\";s:4:\"type\";s:6:\"hidden\";}s:11:\"key_default\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";s:4:\"type\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";s:4:\"type\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"attach\";s:0:\"\";s:4:\"type\";s:0:\"\";}}', '2012-07-25 11:59:55');
INSERT INTO `ts_system_config` VALUES ('150', 'pageKey', 'admin_Config_footer', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:3:\"uid\";s:3:\"uid\";s:4:\"sort\";s:4:\"sort\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:2:\"ID\";s:5:\"title\";s:6:\"標題\";s:3:\"uid\";s:9:\"釋出者\";s:4:\"sort\";s:6:\"排序\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:4:\"sort\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:4:\"sort\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-04-13 14:07:15');
INSERT INTO `ts_system_config` VALUES ('4710', 'pageKey', 'admin_Home_message', 'a:6:{s:3:\"key\";a:2:{s:13:\"user_group_id\";s:13:\"user_group_id\";s:7:\"content\";s:7:\"content\";}s:8:\"key_name\";a:2:{s:13:\"user_group_id\";s:9:\"使用者組\";s:7:\"content\";s:12:\"發送內容\";}s:8:\"key_type\";a:2:{s:13:\"user_group_id\";s:6:\"select\";s:7:\"content\";s:6:\"editor\";}s:11:\"key_default\";a:2:{s:13:\"user_group_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:13:\"user_group_id\";s:0:\"\";s:7:\"content\";s:39:\"內容將在前臺系統訊息中展示\";}s:14:\"key_javascript\";a:2:{s:13:\"user_group_id\";s:0:\"\";s:7:\"content\";s:0:\"\";}}', '2013-03-11 14:57:33');
INSERT INTO `ts_system_config` VALUES ('5026', 'pageKey', 'admin_Home_invatecount', 'a:4:{s:3:\"key\";a:8:{s:16:\"invite_record_id\";s:16:\"invite_record_id\";s:12:\"receiver_uid\";s:12:\"receiver_uid\";s:11:\"inviter_uid\";s:11:\"inviter_uid\";s:8:\"is_audit\";s:8:\"is_audit\";s:9:\"is_active\";s:9:\"is_active\";s:7:\"is_init\";s:7:\"is_init\";s:5:\"ctime\";s:5:\"ctime\";s:13:\"recived_email\";s:13:\"recived_email\";}s:8:\"key_name\";a:8:{s:16:\"invite_record_id\";s:8:\"邀請ID\";s:12:\"receiver_uid\";s:12:\"被邀請人\";s:11:\"inviter_uid\";s:9:\"邀請人\";s:8:\"is_audit\";s:12:\"是否稽覈\";s:9:\"is_active\";s:12:\"是否啟用\";s:7:\"is_init\";s:15:\"是否初始化\";s:5:\"ctime\";s:12:\"註冊時間\";s:13:\"recived_email\";s:17:\"被邀請人email\";}s:10:\"key_hidden\";a:8:{s:16:\"invite_record_id\";s:1:\"0\";s:12:\"receiver_uid\";s:1:\"0\";s:11:\"inviter_uid\";s:1:\"0\";s:8:\"is_audit\";s:1:\"0\";s:9:\"is_active\";s:1:\"0\";s:7:\"is_init\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:13:\"recived_email\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:16:\"invite_record_id\";s:0:\"\";s:12:\"receiver_uid\";s:0:\"\";s:11:\"inviter_uid\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:7:\"is_init\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:13:\"recived_email\";s:0:\"\";}}', '2013-03-13 15:26:04');
INSERT INTO `ts_system_config` VALUES ('536', 'searchPageKey', 'S_admin_Home_invatecount', 'a:5:{s:3:\"key\";a:2:{s:11:\"inviter_uid\";s:11:\"inviter_uid\";s:12:\"receiver_uid\";s:12:\"receiver_uid\";}s:8:\"key_name\";a:2:{s:11:\"inviter_uid\";s:11:\"邀請人ID\";s:12:\"receiver_uid\";s:14:\"被邀請人ID\";}s:8:\"key_type\";a:2:{s:11:\"inviter_uid\";s:4:\"text\";s:12:\"receiver_uid\";s:4:\"text\";}s:9:\"key_tishi\";a:2:{s:11:\"inviter_uid\";s:0:\"\";s:12:\"receiver_uid\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:11:\"inviter_uid\";s:0:\"\";s:12:\"receiver_uid\";s:0:\"\";}}', '2012-05-31 21:55:45');
INSERT INTO `ts_system_config` VALUES ('327', 'pageKey', 'admin_Home_invateTop', 'a:4:{s:3:\"key\";a:4:{s:4:\"sort\";s:4:\"sort\";s:11:\"inviter_uid\";s:11:\"inviter_uid\";s:4:\"nums\";s:4:\"nums\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:4:\"sort\";s:6:\"名次\";s:11:\"inviter_uid\";s:9:\"邀請人\";s:4:\"nums\";s:12:\"邀請數量\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:4:\"sort\";s:1:\"0\";s:11:\"inviter_uid\";s:1:\"0\";s:4:\"nums\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:4:\"sort\";s:0:\"\";s:11:\"inviter_uid\";s:0:\"\";s:4:\"nums\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-05-25 15:19:53');
INSERT INTO `ts_system_config` VALUES ('537', 'searchPageKey', 'S_admin_Home_invateTop', 'a:5:{s:3:\"key\";a:1:{s:11:\"inviter_uid\";s:11:\"inviter_uid\";}s:8:\"key_name\";a:1:{s:11:\"inviter_uid\";s:11:\"邀請人ID\";}s:8:\"key_type\";a:1:{s:11:\"inviter_uid\";s:4:\"text\";}s:9:\"key_tishi\";a:1:{s:11:\"inviter_uid\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:11:\"inviter_uid\";s:0:\"\";}}', '2012-05-31 21:56:38');
INSERT INTO `ts_system_config` VALUES ('5027', 'pageKey', 'admin_Home_invateDetail', 'a:4:{s:3:\"key\";a:7:{s:16:\"invite_record_id\";s:16:\"invite_record_id\";s:12:\"receiver_uid\";s:12:\"receiver_uid\";s:8:\"is_audit\";s:8:\"is_audit\";s:9:\"is_active\";s:9:\"is_active\";s:7:\"is_init\";s:7:\"is_init\";s:5:\"ctime\";s:5:\"ctime\";s:13:\"recived_email\";s:13:\"recived_email\";}s:8:\"key_name\";a:7:{s:16:\"invite_record_id\";s:2:\"ID\";s:12:\"receiver_uid\";s:12:\"被邀請人\";s:8:\"is_audit\";s:12:\"是否稽覈\";s:9:\"is_active\";s:12:\"是否啟用\";s:7:\"is_init\";s:15:\"是否初始化\";s:5:\"ctime\";s:12:\"註冊時間\";s:13:\"recived_email\";s:17:\"被邀請人email\";}s:10:\"key_hidden\";a:7:{s:16:\"invite_record_id\";s:1:\"0\";s:12:\"receiver_uid\";s:1:\"0\";s:8:\"is_audit\";s:1:\"0\";s:9:\"is_active\";s:1:\"0\";s:7:\"is_init\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:13:\"recived_email\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:16:\"invite_record_id\";s:0:\"\";s:12:\"receiver_uid\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:7:\"is_init\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:13:\"recived_email\";s:0:\"\";}}', '2013-03-13 15:26:34');
INSERT INTO `ts_system_config` VALUES ('157', 'searchPageKey', 'S_admin_Content_feed', 'a:5:{s:3:\"key\";a:4:{s:7:\"feed_id\";s:7:\"feed_id\";s:3:\"uid\";s:3:\"uid\";s:4:\"type\";s:4:\"type\";s:3:\"rec\";s:3:\"rec\";}s:8:\"key_name\";a:4:{s:7:\"feed_id\";s:8:\"動態ID\";s:3:\"uid\";s:8:\"使用者ID\";s:4:\"type\";s:12:\"動態類型\";s:3:\"rec\";s:9:\"回收站\";}s:8:\"key_type\";a:4:{s:7:\"feed_id\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:4:\"type\";s:6:\"select\";s:3:\"rec\";s:6:\"hidden\";}s:9:\"key_tishi\";a:4:{s:7:\"feed_id\";s:35:\"多個id之間用英文的\",\"隔開\";s:3:\"uid\";s:35:\"多個id之間用英文的\",\"隔開\";s:4:\"type\";s:0:\"\";s:3:\"rec\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:7:\"feed_id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:4:\"type\";s:0:\"\";s:3:\"rec\";s:0:\"\";}}', '2012-04-14 05:37:35');
INSERT INTO `ts_system_config` VALUES ('158', 'pageKey', 'admin_Content_feed', 'a:4:{s:3:\"key\";a:8:{s:7:\"feed_id\";s:7:\"feed_id\";s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:4:\"data\";s:4:\"data\";s:12:\"publish_time\";s:12:\"publish_time\";s:4:\"type\";s:4:\"type\";s:4:\"from\";s:4:\"from\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:7:\"feed_id\";s:8:\"動態ID\";s:3:\"uid\";s:8:\"使用者ID\";s:5:\"uname\";s:9:\"使用者名\";s:4:\"data\";s:6:\"資料\";s:12:\"publish_time\";s:12:\"釋出時間\";s:4:\"type\";s:12:\"微博類型\";s:4:\"from\";s:6:\"來自\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:7:\"feed_id\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:4:\"data\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:4:\"type\";s:1:\"0\";s:4:\"from\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:7:\"feed_id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:4:\"data\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:4:\"type\";s:0:\"\";s:4:\"from\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-04-14 06:36:36');
INSERT INTO `ts_system_config` VALUES ('159', 'searchPageKey', 'S_admin_Content_comment', 'a:5:{s:3:\"key\";a:3:{s:10:\"comment_id\";s:10:\"comment_id\";s:3:\"uid\";s:3:\"uid\";s:7:\"app_uid\";s:7:\"app_uid\";}s:8:\"key_name\";a:3:{s:10:\"comment_id\";s:8:\"評論ID\";s:3:\"uid\";s:11:\"評論者ID\";s:7:\"app_uid\";s:8:\"作者ID\";}s:8:\"key_type\";a:3:{s:10:\"comment_id\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:7:\"app_uid\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:10:\"comment_id\";s:35:\"多個id之間用英文的\",\"隔開\";s:3:\"uid\";s:35:\"多個id之間用英文的\",\"隔開\";s:7:\"app_uid\";s:35:\"多個id之間用英文的\",\"隔開\";}s:14:\"key_javascript\";a:3:{s:10:\"comment_id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:7:\"app_uid\";s:0:\"\";}}', '2012-04-14 06:56:52');
INSERT INTO `ts_system_config` VALUES ('569', 'pageKey', 'admin_Content_comment', 'a:4:{s:3:\"key\";a:8:{s:10:\"comment_id\";s:10:\"comment_id\";s:3:\"uid\";s:3:\"uid\";s:7:\"app_uid\";s:7:\"app_uid\";s:11:\"source_type\";s:11:\"source_type\";s:7:\"content\";s:7:\"content\";s:5:\"ctime\";s:5:\"ctime\";s:11:\"client_type\";s:11:\"client_type\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:10:\"comment_id\";s:8:\"評論ID\";s:3:\"uid\";s:9:\"評論者\";s:7:\"app_uid\";s:12:\"資源作者\";s:11:\"source_type\";s:12:\"資源類型\";s:7:\"content\";s:12:\"評論內容\";s:5:\"ctime\";s:12:\"評論時間\";s:11:\"client_type\";s:12:\"來源類型\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:10:\"comment_id\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:7:\"app_uid\";s:1:\"0\";s:11:\"source_type\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:11:\"client_type\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:10:\"comment_id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:7:\"app_uid\";s:0:\"\";s:11:\"source_type\";s:0:\"\";s:7:\"content\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:11:\"client_type\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-01 18:51:50');
INSERT INTO `ts_system_config` VALUES ('4744', 'searchPageKey', 'S_admin_User_index', 'a:5:{s:3:\"key\";a:7:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:3:\"sex\";s:3:\"sex\";s:10:\"user_group\";s:10:\"user_group\";s:13:\"user_category\";s:13:\"user_category\";s:5:\"ctime\";s:5:\"ctime\";}s:8:\"key_name\";a:7:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:9:\"使用者名\";s:5:\"email\";s:5:\"Email\";s:3:\"sex\";s:6:\"性別\";s:10:\"user_group\";s:9:\"使用者組\";s:13:\"user_category\";s:6:\"標籤\";s:5:\"ctime\";s:12:\"註冊時間\";}s:8:\"key_type\";a:7:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:10:\"user_group\";s:6:\"select\";s:13:\"user_category\";s:6:\"select\";s:5:\"ctime\";s:4:\"date\";}s:9:\"key_tishi\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:1:\"0\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}}', '2013-03-11 16:21:46');
INSERT INTO `ts_system_config` VALUES ('1681', 'pageKey', 'admin_User_index', 'a:4:{s:3:\"key\";a:10:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:10:\"user_group\";s:10:\"user_group\";s:8:\"location\";s:8:\"location\";s:8:\"is_audit\";s:8:\"is_audit\";s:9:\"is_active\";s:9:\"is_active\";s:7:\"is_init\";s:7:\"is_init\";s:5:\"ctime\";s:5:\"ctime\";s:6:\"reg_ip\";s:6:\"reg_ip\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:9:\"使用者名\";s:10:\"user_group\";s:9:\"使用者組\";s:8:\"location\";s:6:\"地區\";s:8:\"is_audit\";s:12:\"是否稽覈\";s:9:\"is_active\";s:12:\"是否啟用\";s:7:\"is_init\";s:15:\"是否初始化\";s:5:\"ctime\";s:12:\"註冊時間\";s:6:\"reg_ip\";s:8:\"註冊IP\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:10:\"user_group\";s:1:\"0\";s:8:\"location\";s:1:\"0\";s:8:\"is_audit\";s:1:\"0\";s:9:\"is_active\";s:1:\"0\";s:7:\"is_init\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:6:\"reg_ip\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:8:\"location\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:7:\"is_init\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:6:\"reg_ip\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-16 00:20:52');
INSERT INTO `ts_system_config` VALUES ('1682', 'pageKey', 'admin_User_online', 'a:4:{s:3:\"key\";a:6:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:10:\"user_group\";s:10:\"user_group\";s:8:\"location\";s:8:\"location\";s:5:\"ctime\";s:5:\"ctime\";s:17:\"last_operating_ip\";s:17:\"last_operating_ip\";}s:8:\"key_name\";a:6:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:12:\"使用者昵稱\";s:10:\"user_group\";s:9:\"使用者組\";s:8:\"location\";s:6:\"地區\";s:5:\"ctime\";s:12:\"註冊時間\";s:17:\"last_operating_ip\";s:14:\"最後操作IP\";}s:10:\"key_hidden\";a:6:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:10:\"user_group\";s:1:\"0\";s:8:\"location\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:17:\"last_operating_ip\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:8:\"location\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:17:\"last_operating_ip\";s:0:\"\";}}', '2012-08-17 01:06:38');
INSERT INTO `ts_system_config` VALUES ('4883', 'searchPageKey', 'S_admin_User_pending', 'a:5:{s:3:\"key\";a:7:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"email\";s:5:\"email\";s:3:\"sex\";s:3:\"sex\";s:10:\"user_group\";s:10:\"user_group\";s:13:\"user_category\";s:13:\"user_category\";s:5:\"ctime\";s:5:\"ctime\";}s:8:\"key_name\";a:7:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:9:\"使用者名\";s:5:\"email\";s:5:\"Email\";s:3:\"sex\";s:6:\"性別\";s:10:\"user_group\";s:9:\"使用者組\";s:13:\"user_category\";s:12:\"使用者標籤\";s:5:\"ctime\";s:12:\"註冊時間\";}s:8:\"key_type\";a:7:{s:3:\"uid\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:5:\"email\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:10:\"user_group\";s:6:\"hidden\";s:13:\"user_category\";s:6:\"select\";s:5:\"ctime\";s:4:\"date\";}s:9:\"key_tishi\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"email\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";s:5:\"ctime\";s:0:\"\";}}', '2013-03-12 16:47:19');
INSERT INTO `ts_system_config` VALUES ('1680', 'pageKey', 'admin_User_pending', 'a:4:{s:3:\"key\";a:6:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:8:\"location\";s:8:\"location\";s:5:\"ctime\";s:5:\"ctime\";s:6:\"reg_ip\";s:6:\"reg_ip\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:9:\"使用者名\";s:8:\"location\";s:6:\"地區\";s:5:\"ctime\";s:12:\"註冊時間\";s:6:\"reg_ip\";s:8:\"註冊IP\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:8:\"location\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:6:\"reg_ip\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"location\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:6:\"reg_ip\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-16 00:20:44');
INSERT INTO `ts_system_config` VALUES ('4596', 'pageKey', 'admin_User_addProfileField', 'a:6:{s:3:\"key\";a:13:{s:8:\"field_id\";s:8:\"field_id\";s:4:\"type\";s:4:\"type\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:10:\"field_type\";s:10:\"field_type\";s:8:\"visiable\";s:8:\"visiable\";s:8:\"editable\";s:8:\"editable\";s:8:\"required\";s:8:\"required\";s:7:\"privacy\";s:7:\"privacy\";s:9:\"form_type\";s:9:\"form_type\";s:18:\"form_default_value\";s:18:\"form_default_value\";s:10:\"validation\";s:10:\"validation\";s:4:\"tips\";s:4:\"tips\";}s:8:\"key_name\";a:13:{s:8:\"field_id\";s:8:\"欄位ID\";s:4:\"type\";s:6:\"類型\";s:9:\"field_key\";s:12:\"欄位鍵值\";s:10:\"field_name\";s:12:\"欄位名稱\";s:10:\"field_type\";s:12:\"欄位類型\";s:8:\"visiable\";s:12:\"是否可見\";s:8:\"editable\";s:15:\"是否可修改\";s:8:\"required\";s:12:\"是否必填\";s:7:\"privacy\";s:18:\"默認隱私設定\";s:9:\"form_type\";s:12:\"表單類型\";s:18:\"form_default_value\";s:15:\"表單預設值\";s:10:\"validation\";s:18:\"表單驗證方法\";s:4:\"tips\";s:18:\"欄位填寫說明\";}s:8:\"key_type\";a:13:{s:8:\"field_id\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:9:\"field_key\";s:4:\"text\";s:10:\"field_name\";s:4:\"text\";s:10:\"field_type\";s:6:\"select\";s:8:\"visiable\";s:5:\"radio\";s:8:\"editable\";s:5:\"radio\";s:8:\"required\";s:5:\"radio\";s:7:\"privacy\";s:6:\"hidden\";s:9:\"form_type\";s:6:\"select\";s:18:\"form_default_value\";s:8:\"textarea\";s:10:\"validation\";s:6:\"hidden\";s:4:\"tips\";s:6:\"hidden\";}s:11:\"key_default\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:1:\"2\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:1:\"1\";s:8:\"editable\";s:1:\"1\";s:8:\"required\";s:1:\"1\";s:7:\"privacy\";s:1:\"0\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:0:\"\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}s:9:\"key_tishi\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:0:\"\";s:8:\"editable\";s:0:\"\";s:8:\"required\";s:0:\"\";s:7:\"privacy\";s:0:\"\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:29:\"以豎線分隔 例：男|女\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}s:14:\"key_javascript\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:0:\"\";s:8:\"editable\";s:0:\"\";s:8:\"required\";s:0:\"\";s:7:\"privacy\";s:0:\"\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:0:\"\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}}', '2013-03-07 17:08:04');
INSERT INTO `ts_system_config` VALUES ('168', 'pageKey', 'admin_User_profileCategory', 'a:4:{s:3:\"key\";a:4:{s:8:\"field_id\";s:8:\"field_id\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:8:\"field_id\";s:8:\"分類ID\";s:9:\"field_key\";s:12:\"分類鍵值\";s:10:\"field_name\";s:12:\"分類名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:8:\"field_id\";s:1:\"0\";s:9:\"field_key\";s:1:\"0\";s:10:\"field_name\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:8:\"field_id\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-04-16 00:43:42');
INSERT INTO `ts_system_config` VALUES ('4762', 'pageKey', 'admin_User_editProfileCategory', 'a:6:{s:3:\"key\";a:5:{s:8:\"field_id\";s:8:\"field_id\";s:4:\"type\";s:4:\"type\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:10:\"field_type\";s:10:\"field_type\";}s:8:\"key_name\";a:5:{s:8:\"field_id\";s:8:\"欄位ID\";s:4:\"type\";s:13:\"欄位/分類\";s:9:\"field_key\";s:12:\"分類鍵值\";s:10:\"field_name\";s:12:\"分類名稱\";s:10:\"field_type\";s:12:\"上級分類\";}s:8:\"key_type\";a:5:{s:8:\"field_id\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:9:\"field_key\";s:6:\"hidden\";s:10:\"field_name\";s:4:\"text\";s:10:\"field_type\";s:6:\"hidden\";}s:11:\"key_default\";a:5:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";}}', '2013-03-11 17:39:16');
INSERT INTO `ts_system_config` VALUES ('1670', 'pageKey', 'admin_User_profile', 'a:4:{s:3:\"key\";a:8:{s:8:\"field_id\";s:8:\"field_id\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:10:\"field_type\";s:10:\"field_type\";s:8:\"visiable\";s:8:\"visiable\";s:8:\"editable\";s:8:\"editable\";s:8:\"required\";s:8:\"required\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:8:\"field_id\";s:8:\"欄位ID\";s:9:\"field_key\";s:12:\"欄位鍵值\";s:10:\"field_name\";s:12:\"欄位名稱\";s:10:\"field_type\";s:12:\"欄位分類\";s:8:\"visiable\";s:18:\"是否空間可見\";s:8:\"editable\";s:15:\"是否可編輯\";s:8:\"required\";s:15:\"是否必填項\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:8:\"field_id\";s:1:\"1\";s:9:\"field_key\";s:1:\"0\";s:10:\"field_name\";s:1:\"0\";s:10:\"field_type\";s:1:\"0\";s:8:\"visiable\";s:1:\"0\";s:8:\"editable\";s:1:\"0\";s:8:\"required\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:8:\"field_id\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:0:\"\";s:8:\"editable\";s:0:\"\";s:8:\"required\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-10 19:10:33');
INSERT INTO `ts_system_config` VALUES ('2343', 'pageKey', 'admin_User_addUser', 'a:6:{s:3:\"key\";a:9:{s:5:\"email\";s:5:\"email\";s:5:\"uname\";s:5:\"uname\";s:8:\"password\";s:8:\"password\";s:3:\"sex\";s:3:\"sex\";s:8:\"is_audit\";s:8:\"is_audit\";s:9:\"is_active\";s:9:\"is_active\";s:8:\"identity\";s:8:\"identity\";s:10:\"user_group\";s:10:\"user_group\";s:13:\"user_category\";s:13:\"user_category\";}s:8:\"key_name\";a:9:{s:5:\"email\";s:5:\"Email\";s:5:\"uname\";s:9:\"使用者名\";s:8:\"password\";s:6:\"密碼\";s:3:\"sex\";s:6:\"性別\";s:8:\"is_audit\";s:12:\"是否稽覈\";s:9:\"is_active\";s:12:\"是否啟用\";s:8:\"identity\";s:6:\"身份\";s:10:\"user_group\";s:9:\"使用者組\";s:13:\"user_category\";s:12:\"職業資訊\";}s:8:\"key_type\";a:9:{s:5:\"email\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:8:\"password\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:8:\"is_audit\";s:5:\"radio\";s:9:\"is_active\";s:5:\"radio\";s:8:\"identity\";s:5:\"radio\";s:10:\"user_group\";s:8:\"checkbox\";s:13:\"user_category\";s:8:\"checkbox\";}s:11:\"key_default\";a:9:{s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:1:\"1\";s:8:\"is_audit\";s:1:\"1\";s:9:\"is_active\";s:1:\"1\";s:8:\"identity\";s:1:\"1\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:8:\"identity\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";}s:14:\"key_javascript\";a:9:{s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:8:\"identity\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:27:\"admin.checkBoxNums(this, 3)\";}}', '2012-12-14 01:19:07');
INSERT INTO `ts_system_config` VALUES ('2626', 'pageKey', 'admin_User_editUser', 'a:6:{s:3:\"key\";a:7:{s:3:\"uid\";s:3:\"uid\";s:5:\"email\";s:5:\"email\";s:5:\"uname\";s:5:\"uname\";s:8:\"password\";s:8:\"password\";s:3:\"sex\";s:3:\"sex\";s:10:\"user_group\";s:10:\"user_group\";s:13:\"user_category\";s:13:\"user_category\";}s:8:\"key_name\";a:7:{s:3:\"uid\";s:3:\"UID\";s:5:\"email\";s:5:\"Email\";s:5:\"uname\";s:9:\"使用者名\";s:8:\"password\";s:9:\"新密碼\";s:3:\"sex\";s:6:\"性別\";s:10:\"user_group\";s:9:\"使用者組\";s:13:\"user_category\";s:12:\"職業資訊\";}s:8:\"key_type\";a:7:{s:3:\"uid\";s:6:\"hidden\";s:5:\"email\";s:4:\"text\";s:5:\"uname\";s:4:\"text\";s:8:\"password\";s:4:\"text\";s:3:\"sex\";s:5:\"radio\";s:10:\"user_group\";s:8:\"checkbox\";s:13:\"user_category\";s:8:\"checkbox\";}s:11:\"key_default\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";}s:9:\"key_tishi\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:3:\"uid\";s:0:\"\";s:5:\"email\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:8:\"password\";s:0:\"\";s:3:\"sex\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:13:\"user_category\";s:0:\"\";}}', '2013-01-09 17:45:58');
INSERT INTO `ts_system_config` VALUES ('4598', 'pageKey', 'admin_User_editProfileField', 'a:6:{s:3:\"key\";a:13:{s:8:\"field_id\";s:8:\"field_id\";s:4:\"type\";s:4:\"type\";s:9:\"field_key\";s:9:\"field_key\";s:10:\"field_name\";s:10:\"field_name\";s:10:\"field_type\";s:10:\"field_type\";s:8:\"visiable\";s:8:\"visiable\";s:8:\"editable\";s:8:\"editable\";s:8:\"required\";s:8:\"required\";s:7:\"privacy\";s:7:\"privacy\";s:9:\"form_type\";s:9:\"form_type\";s:18:\"form_default_value\";s:18:\"form_default_value\";s:10:\"validation\";s:10:\"validation\";s:4:\"tips\";s:4:\"tips\";}s:8:\"key_name\";a:13:{s:8:\"field_id\";s:8:\"欄位ID\";s:4:\"type\";s:12:\"欄位類型\";s:9:\"field_key\";s:12:\"欄位鍵值\";s:10:\"field_name\";s:12:\"欄位名稱\";s:10:\"field_type\";s:12:\"欄位類型\";s:8:\"visiable\";s:12:\"是否可見\";s:8:\"editable\";s:15:\"是否可修改\";s:8:\"required\";s:12:\"是否必填\";s:7:\"privacy\";s:18:\"默認隱私設定\";s:9:\"form_type\";s:12:\"表單類型\";s:18:\"form_default_value\";s:15:\"表單預設值\";s:10:\"validation\";s:18:\"表單驗證方法\";s:4:\"tips\";s:18:\"欄位填寫說明\";}s:8:\"key_type\";a:13:{s:8:\"field_id\";s:6:\"hidden\";s:4:\"type\";s:6:\"hidden\";s:9:\"field_key\";s:6:\"hidden\";s:10:\"field_name\";s:4:\"text\";s:10:\"field_type\";s:6:\"select\";s:8:\"visiable\";s:5:\"radio\";s:8:\"editable\";s:5:\"radio\";s:8:\"required\";s:5:\"radio\";s:7:\"privacy\";s:6:\"hidden\";s:9:\"form_type\";s:6:\"select\";s:18:\"form_default_value\";s:8:\"textarea\";s:10:\"validation\";s:6:\"hidden\";s:4:\"tips\";s:6:\"hidden\";}s:11:\"key_default\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:1:\"2\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:1:\"1\";s:8:\"editable\";s:1:\"1\";s:8:\"required\";s:1:\"1\";s:7:\"privacy\";s:1:\"0\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:0:\"\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}s:9:\"key_tishi\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:0:\"\";s:8:\"editable\";s:0:\"\";s:8:\"required\";s:0:\"\";s:7:\"privacy\";s:0:\"\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:29:\"以豎線分隔 例：男|女\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}s:14:\"key_javascript\";a:13:{s:8:\"field_id\";s:0:\"\";s:4:\"type\";s:0:\"\";s:9:\"field_key\";s:0:\"\";s:10:\"field_name\";s:0:\"\";s:10:\"field_type\";s:0:\"\";s:8:\"visiable\";s:0:\"\";s:8:\"editable\";s:0:\"\";s:8:\"required\";s:0:\"\";s:7:\"privacy\";s:0:\"\";s:9:\"form_type\";s:0:\"\";s:18:\"form_default_value\";s:0:\"\";s:10:\"validation\";s:0:\"\";s:4:\"tips\";s:0:\"\";}}', '2013-03-07 17:09:02');
INSERT INTO `ts_system_config` VALUES ('1078', 'pageKey', 'admin_Home_addTag', 'a:6:{s:3:\"key\";a:1:{s:4:\"tags\";s:4:\"tags\";}s:8:\"key_name\";a:1:{s:4:\"tags\";s:6:\"標籤\";}s:8:\"key_type\";a:1:{s:4:\"tags\";s:10:\"stringText\";}s:11:\"key_default\";a:1:{s:4:\"tags\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:4:\"tags\";s:21:\"按回車添加標籤\";}s:14:\"key_javascript\";a:1:{s:4:\"tags\";s:0:\"\";}}', '2012-07-14 12:28:28');
INSERT INTO `ts_system_config` VALUES ('553', 'pageKey', 'admin_Home_feedback', 'a:4:{s:3:\"key\";a:7:{s:2:\"id\";s:2:\"id\";s:12:\"feedbacktype\";s:12:\"feedbacktype\";s:8:\"feedback\";s:8:\"feedback\";s:3:\"uid\";s:3:\"uid\";s:5:\"cTime\";s:5:\"cTime\";s:4:\"type\";s:4:\"type\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:2:\"id\";s:2:\"ID\";s:12:\"feedbacktype\";s:12:\"反饋類型\";s:8:\"feedback\";s:12:\"反饋內容\";s:3:\"uid\";s:9:\"提交者\";s:5:\"cTime\";s:12:\"提交時間\";s:4:\"type\";s:12:\"處理狀態\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:2:\"id\";s:1:\"0\";s:12:\"feedbacktype\";s:1:\"0\";s:8:\"feedback\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:5:\"cTime\";s:1:\"0\";s:4:\"type\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:2:\"id\";s:0:\"\";s:12:\"feedbacktype\";s:0:\"\";s:8:\"feedback\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"cTime\";s:0:\"\";s:4:\"type\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-05-31 23:14:12');
INSERT INTO `ts_system_config` VALUES ('1163', 'pageKey', 'admin_Home_feedbackType', 'a:4:{s:3:\"key\";a:3:{s:7:\"type_id\";s:7:\"type_id\";s:9:\"type_name\";s:9:\"type_name\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:3:{s:7:\"type_id\";s:14:\"反饋類型ID\";s:9:\"type_name\";s:12:\"反饋類型\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:3:{s:7:\"type_id\";s:1:\"1\";s:9:\"type_name\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:3:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-17 18:22:18');
INSERT INTO `ts_system_config` VALUES ('311', 'pageKey', 'admin_Config_setCreditLevel', 'a:6:{s:3:\"key\";a:5:{s:5:\"level\";s:5:\"level\";s:4:\"name\";s:4:\"name\";s:5:\"image\";s:5:\"image\";s:5:\"start\";s:5:\"start\";s:3:\"end\";s:3:\"end\";}s:8:\"key_name\";a:5:{s:5:\"level\";s:6:\"等級\";s:4:\"name\";s:12:\"等級名稱\";s:5:\"image\";s:12:\"等級圖示\";s:5:\"start\";s:15:\"積分開始值\";s:3:\"end\";s:15:\"積分結束值\";}s:8:\"key_type\";a:5:{s:5:\"level\";s:4:\"word\";s:4:\"name\";s:4:\"text\";s:5:\"image\";s:6:\"hidden\";s:5:\"start\";s:4:\"text\";s:3:\"end\";s:4:\"text\";}s:11:\"key_default\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}}', '2012-05-25 11:21:43');
INSERT INTO `ts_system_config` VALUES ('3321', 'pageKey', 'admin_Config_feed', 'a:6:{s:3:\"key\";a:6:{s:10:\"weibo_nums\";s:10:\"weibo_nums\";s:10:\"weibo_type\";s:10:\"weibo_type\";s:16:\"weibo_premission\";s:16:\"weibo_premission\";s:15:\"weibo_send_info\";s:15:\"weibo_send_info\";s:19:\"weibo_default_topic\";s:19:\"weibo_default_topic\";s:11:\"weibo_at_me\";s:11:\"weibo_at_me\";}s:8:\"key_name\";a:6:{s:10:\"weibo_nums\";s:12:\"微博字數\";s:10:\"weibo_type\";s:12:\"微博類型\";s:16:\"weibo_premission\";s:12:\"微博設定\";s:15:\"weibo_send_info\";s:15:\"微博提示語\";s:19:\"weibo_default_topic\";s:21:\"微博框默認話題\";s:11:\"weibo_at_me\";s:13:\"@範圍配置\";}s:8:\"key_type\";a:6:{s:10:\"weibo_nums\";s:4:\"text\";s:10:\"weibo_type\";s:8:\"checkbox\";s:16:\"weibo_premission\";s:8:\"checkbox\";s:15:\"weibo_send_info\";s:4:\"text\";s:19:\"weibo_default_topic\";s:4:\"text\";s:11:\"weibo_at_me\";s:5:\"radio\";}s:11:\"key_default\";a:6:{s:10:\"weibo_nums\";s:0:\"\";s:10:\"weibo_type\";s:0:\"\";s:16:\"weibo_premission\";s:0:\"\";s:15:\"weibo_send_info\";s:0:\"\";s:19:\"weibo_default_topic\";s:0:\"\";s:11:\"weibo_at_me\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:10:\"weibo_nums\";s:0:\"\";s:10:\"weibo_type\";s:0:\"\";s:16:\"weibo_premission\";s:0:\"\";s:15:\"weibo_send_info\";s:0:\"\";s:19:\"weibo_default_topic\";s:42:\"不需輸入＃號，如：倫敦奧運會\";s:11:\"weibo_at_me\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:10:\"weibo_nums\";s:0:\"\";s:10:\"weibo_type\";s:0:\"\";s:16:\"weibo_premission\";s:0:\"\";s:15:\"weibo_send_info\";s:0:\"\";s:19:\"weibo_default_topic\";s:0:\"\";s:11:\"weibo_at_me\";s:0:\"\";}}', '2013-02-01 09:49:52');
INSERT INTO `ts_system_config` VALUES ('1679', 'pageKey', 'admin_User_dellist', 'a:4:{s:3:\"key\";a:10:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:10:\"user_group\";s:10:\"user_group\";s:8:\"location\";s:8:\"location\";s:8:\"is_audit\";s:8:\"is_audit\";s:9:\"is_active\";s:9:\"is_active\";s:7:\"is_init\";s:7:\"is_init\";s:5:\"ctime\";s:5:\"ctime\";s:6:\"reg_ip\";s:6:\"reg_ip\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:3:\"uid\";s:3:\"UID\";s:5:\"uname\";s:12:\"使用者名稱\";s:10:\"user_group\";s:9:\"使用者組\";s:8:\"location\";s:6:\"地區\";s:8:\"is_audit\";s:6:\"稽覈\";s:9:\"is_active\";s:6:\"啟用\";s:7:\"is_init\";s:9:\"初始化\";s:5:\"ctime\";s:12:\"註冊時間\";s:6:\"reg_ip\";s:8:\"註冊IP\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:10:\"user_group\";s:1:\"0\";s:8:\"location\";s:1:\"0\";s:8:\"is_audit\";s:1:\"0\";s:9:\"is_active\";s:1:\"0\";s:7:\"is_init\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:6:\"reg_ip\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:10:\"user_group\";s:0:\"\";s:8:\"location\";s:0:\"\";s:8:\"is_audit\";s:0:\"\";s:9:\"is_active\";s:0:\"\";s:7:\"is_init\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:6:\"reg_ip\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-16 00:20:35');
INSERT INTO `ts_system_config` VALUES ('566', 'pageKey', 'admin_Content_message', 'a:4:{s:3:\"key\";a:7:{s:10:\"message_id\";s:10:\"message_id\";s:4:\"fuid\";s:4:\"fuid\";s:8:\"from_uid\";s:8:\"from_uid\";s:7:\"mix_man\";s:7:\"mix_man\";s:7:\"content\";s:7:\"content\";s:5:\"mtime\";s:5:\"mtime\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:10:\"message_id\";s:8:\"私信ID\";s:4:\"fuid\";s:15:\"私信發送者\";s:8:\"from_uid\";s:15:\"對話發起者\";s:7:\"mix_man\";s:12:\"對話成員\";s:7:\"content\";s:12:\"私信內容\";s:5:\"mtime\";s:12:\"發送時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:10:\"message_id\";s:1:\"0\";s:4:\"fuid\";s:1:\"0\";s:8:\"from_uid\";s:1:\"0\";s:7:\"mix_man\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:5:\"mtime\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:10:\"message_id\";s:0:\"\";s:4:\"fuid\";s:0:\"\";s:8:\"from_uid\";s:0:\"\";s:7:\"mix_man\";s:0:\"\";s:7:\"content\";s:0:\"\";s:5:\"mtime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-01 18:50:01');
INSERT INTO `ts_system_config` VALUES ('568', 'searchPageKey', 'S_admin_Content_message', 'a:5:{s:3:\"key\";a:3:{s:8:\"from_uid\";s:8:\"from_uid\";s:7:\"mix_man\";s:7:\"mix_man\";s:7:\"content\";s:7:\"content\";}s:8:\"key_name\";a:3:{s:8:\"from_uid\";s:17:\"私信發送者ID\";s:7:\"mix_man\";s:14:\"私信成員ID\";s:7:\"content\";s:12:\"私信內容\";}s:8:\"key_type\";a:3:{s:8:\"from_uid\";s:4:\"text\";s:7:\"mix_man\";s:4:\"text\";s:7:\"content\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:8:\"from_uid\";s:0:\"\";s:7:\"mix_man\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:8:\"from_uid\";s:0:\"\";s:7:\"mix_man\";s:0:\"\";s:7:\"content\";s:0:\"\";}}', '2012-06-01 18:51:29');
INSERT INTO `ts_system_config` VALUES ('571', 'pageKey', 'admin_Content_attach', 'a:4:{s:3:\"key\";a:7:{s:9:\"attach_id\";s:9:\"attach_id\";s:4:\"name\";s:4:\"name\";s:4:\"size\";s:4:\"size\";s:3:\"uid\";s:3:\"uid\";s:5:\"ctime\";s:5:\"ctime\";s:4:\"from\";s:4:\"from\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:9:\"attach_id\";s:8:\"附件ID\";s:4:\"name\";s:12:\"附件名稱\";s:4:\"size\";s:12:\"附件大小\";s:3:\"uid\";s:9:\"上傳者\";s:5:\"ctime\";s:12:\"上傳時間\";s:4:\"from\";s:12:\"來源類型\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:9:\"attach_id\";s:1:\"0\";s:4:\"name\";s:1:\"0\";s:4:\"size\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:4:\"from\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:9:\"attach_id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"size\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:4:\"from\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-01 19:15:50');
INSERT INTO `ts_system_config` VALUES ('4255', 'searchPageKey', 'S_admin_Content_attach', 'a:5:{s:3:\"key\";a:3:{s:9:\"attach_id\";s:9:\"attach_id\";s:4:\"name\";s:4:\"name\";s:4:\"from\";s:4:\"from\";}s:8:\"key_name\";a:3:{s:9:\"attach_id\";s:8:\"附件ID\";s:4:\"name\";s:12:\"附件名稱\";s:4:\"from\";s:12:\"來源類型\";}s:8:\"key_type\";a:3:{s:9:\"attach_id\";s:4:\"text\";s:4:\"name\";s:4:\"text\";s:4:\"from\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:9:\"attach_id\";s:35:\"多個id之間用英文的\",\"隔開\";s:4:\"name\";s:0:\"\";s:4:\"from\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:9:\"attach_id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"from\";s:0:\"\";}}', '2013-02-28 17:44:32');
INSERT INTO `ts_system_config` VALUES ('2687', 'pageKey', 'admin_User_verifyGroup', 'a:4:{s:3:\"key\";a:11:{s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:8:\"category\";s:8:\"category\";s:7:\"company\";s:7:\"company\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:10:\"attachment\";s:10:\"attachment\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:11:{s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:8:\"category\";s:12:\"認證分類\";s:7:\"company\";s:12:\"企業名稱\";s:8:\"realname\";s:12:\"法人姓名\";s:6:\"idcard\";s:15:\"營業執照號\";s:5:\"phone\";s:12:\"聯繫方式\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:10:\"attachment\";s:12:\"認證附件\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:11:{s:5:\"uname\";s:1:\"0\";s:12:\"usergroup_id\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:7:\"company\";s:1:\"0\";s:8:\"realname\";s:1:\"0\";s:6:\"idcard\";s:1:\"0\";s:5:\"phone\";s:1:\"0\";s:6:\"reason\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:10:\"attachment\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:11:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:8:\"category\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:10:\"attachment\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-01-10 11:41:47');
INSERT INTO `ts_system_config` VALUES ('636', 'pageKey', 'admin_Apps_setCreditNode', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:6:\"action\";s:6:\"action\";s:4:\"info\";s:4:\"info\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:2:\"ID\";s:7:\"appname\";s:6:\"應用\";s:6:\"action\";s:6:\"動作\";s:4:\"info\";s:12:\"動作別名\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"1\";s:7:\"appname\";s:1:\"0\";s:6:\"action\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:6:\"action\";s:0:\"\";s:4:\"info\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-07 06:38:59');
INSERT INTO `ts_system_config` VALUES ('637', 'pageKey', 'admin_Apps_setPermNode', 'a:4:{s:3:\"key\";a:7:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:7:\"appinfo\";s:7:\"appinfo\";s:6:\"module\";s:6:\"module\";s:4:\"rule\";s:4:\"rule\";s:8:\"ruleinfo\";s:8:\"ruleinfo\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:6:\"應用\";s:7:\"appinfo\";s:12:\"應用別名\";s:6:\"module\";s:6:\"模組\";s:4:\"rule\";s:12:\"許可權節點\";s:8:\"ruleinfo\";s:12:\"節點別名\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:2:\"id\";s:1:\"1\";s:7:\"appname\";s:1:\"0\";s:7:\"appinfo\";s:1:\"0\";s:6:\"module\";s:1:\"0\";s:4:\"rule\";s:1:\"0\";s:8:\"ruleinfo\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:7:\"appinfo\";s:0:\"\";s:6:\"module\";s:0:\"\";s:4:\"rule\";s:0:\"\";s:8:\"ruleinfo\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-07 06:39:46');
INSERT INTO `ts_system_config` VALUES ('638', 'pageKey', 'admin_Apps_setFeedNode', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:8:\"nodetype\";s:8:\"nodetype\";s:8:\"nodeinfo\";s:8:\"nodeinfo\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:12:\"應用名稱\";s:8:\"nodetype\";s:12:\"微博類型\";s:8:\"nodeinfo\";s:12:\"類型別名\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"1\";s:7:\"appname\";s:1:\"0\";s:8:\"nodetype\";s:1:\"0\";s:8:\"nodeinfo\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"nodetype\";s:0:\"\";s:8:\"nodeinfo\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-06-07 06:41:11');
INSERT INTO `ts_system_config` VALUES ('639', 'pageKey', 'admin_Apps_editCreditNode', 'a:6:{s:3:\"key\";a:4:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:6:\"action\";s:6:\"action\";s:4:\"info\";s:4:\"info\";}s:8:\"key_name\";a:4:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:6:\"應用\";s:6:\"action\";s:6:\"動作\";s:4:\"info\";s:12:\"動作別名\";}s:8:\"key_type\";a:4:{s:2:\"id\";s:6:\"hidden\";s:7:\"appname\";s:4:\"text\";s:6:\"action\";s:4:\"text\";s:4:\"info\";s:4:\"text\";}s:11:\"key_default\";a:4:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:6:\"action\";s:0:\"\";s:4:\"info\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:6:\"action\";s:0:\"\";s:4:\"info\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:6:\"action\";s:0:\"\";s:4:\"info\";s:0:\"\";}}', '2012-06-07 06:41:39');
INSERT INTO `ts_system_config` VALUES ('740', 'pageKey', 'admin_Apps_editPermNode', 'a:6:{s:3:\"key\";a:6:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:7:\"appinfo\";s:7:\"appinfo\";s:6:\"module\";s:6:\"module\";s:4:\"rule\";s:4:\"rule\";s:8:\"ruleinfo\";s:8:\"ruleinfo\";}s:8:\"key_name\";a:6:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:6:\"應用\";s:7:\"appinfo\";s:12:\"應用別名\";s:6:\"module\";s:6:\"模組\";s:4:\"rule\";s:12:\"許可權節點\";s:8:\"ruleinfo\";s:12:\"節點別名\";}s:8:\"key_type\";a:6:{s:2:\"id\";s:6:\"hidden\";s:7:\"appname\";s:4:\"text\";s:7:\"appinfo\";s:4:\"text\";s:6:\"module\";s:5:\"radio\";s:4:\"rule\";s:4:\"text\";s:8:\"ruleinfo\";s:4:\"text\";}s:11:\"key_default\";a:6:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:7:\"appinfo\";s:0:\"\";s:6:\"module\";s:0:\"\";s:4:\"rule\";s:0:\"\";s:8:\"ruleinfo\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:7:\"appinfo\";s:0:\"\";s:6:\"module\";s:0:\"\";s:4:\"rule\";s:0:\"\";s:8:\"ruleinfo\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:7:\"appinfo\";s:0:\"\";s:6:\"module\";s:0:\"\";s:4:\"rule\";s:0:\"\";s:8:\"ruleinfo\";s:0:\"\";}}', '2012-06-27 13:43:43');
INSERT INTO `ts_system_config` VALUES ('641', 'pageKey', 'admin_Apps_editFeedNode', 'a:6:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:7:\"appname\";s:7:\"appname\";s:8:\"nodetype\";s:8:\"nodetype\";s:8:\"nodeinfo\";s:8:\"nodeinfo\";s:3:\"xml\";s:3:\"xml\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:6:\"應用\";s:8:\"nodetype\";s:6:\"類型\";s:8:\"nodeinfo\";s:12:\"類型別名\";s:3:\"xml\";s:12:\"模板內容\";}s:8:\"key_type\";a:5:{s:2:\"id\";s:6:\"hidden\";s:7:\"appname\";s:4:\"text\";s:8:\"nodetype\";s:4:\"text\";s:8:\"nodeinfo\";s:4:\"text\";s:3:\"xml\";s:8:\"textarea\";}s:11:\"key_default\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"nodetype\";s:0:\"\";s:8:\"nodeinfo\";s:0:\"\";s:3:\"xml\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"nodetype\";s:0:\"\";s:8:\"nodeinfo\";s:0:\"\";s:3:\"xml\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"nodetype\";s:0:\"\";s:8:\"nodeinfo\";s:0:\"\";s:3:\"xml\";s:0:\"\";}}', '2012-06-07 06:43:09');
INSERT INTO `ts_system_config` VALUES ('3340', 'pageKey', 'admin_Config_navAdd', 'a:6:{s:3:\"key\";a:9:{s:9:\"navi_name\";s:9:\"navi_name\";s:8:\"app_name\";s:8:\"app_name\";s:3:\"url\";s:3:\"url\";s:6:\"target\";s:6:\"target\";s:6:\"status\";s:6:\"status\";s:8:\"position\";s:8:\"position\";s:5:\"guest\";s:5:\"guest\";s:11:\"is_app_navi\";s:11:\"is_app_navi\";s:10:\"order_sort\";s:10:\"order_sort\";}s:8:\"key_name\";a:9:{s:9:\"navi_name\";s:12:\"導航名稱\";s:8:\"app_name\";s:12:\"英文名稱\";s:3:\"url\";s:12:\"連結地址\";s:6:\"target\";s:12:\"開啟方式\";s:6:\"status\";s:6:\"狀態\";s:8:\"position\";s:12:\"導航位置\";s:5:\"guest\";s:18:\"是否遊客可見\";s:11:\"is_app_navi\";s:24:\"是否應用內部導航\";s:10:\"order_sort\";s:12:\"應用排序\";}s:8:\"key_type\";a:9:{s:9:\"navi_name\";s:4:\"text\";s:8:\"app_name\";s:4:\"text\";s:3:\"url\";s:4:\"text\";s:6:\"target\";s:6:\"select\";s:6:\"status\";s:5:\"radio\";s:8:\"position\";s:6:\"select\";s:5:\"guest\";s:6:\"hidden\";s:11:\"is_app_navi\";s:6:\"hidden\";s:10:\"order_sort\";s:4:\"text\";}s:11:\"key_default\";a:9:{s:9:\"navi_name\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:3:\"url\";s:0:\"\";s:6:\"target\";s:0:\"\";s:6:\"status\";s:1:\"1\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:1:\"1\";s:11:\"is_app_navi\";s:1:\"0\";s:10:\"order_sort\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:9:\"navi_name\";s:0:\"\";s:8:\"app_name\";s:46:\"如核心就是public，通訊錄就是contact\";s:3:\"url\";s:36:\"使用{website}表示網站根地址\";s:6:\"target\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:11:\"is_app_navi\";s:0:\"\";s:10:\"order_sort\";s:0:\"\";}s:14:\"key_javascript\";a:9:{s:9:\"navi_name\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:3:\"url\";s:0:\"\";s:6:\"target\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:11:\"is_app_navi\";s:0:\"\";s:10:\"order_sort\";s:0:\"\";}}', '2013-02-01 17:29:04');
INSERT INTO `ts_system_config` VALUES ('4264', 'pageKey', 'admin_User_verified', 'a:4:{s:3:\"key\";a:10:{s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:8:\"category\";s:8:\"category\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:10:\"attachment\";s:10:\"attachment\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:8:\"category\";s:12:\"認證分類\";s:8:\"realname\";s:12:\"真實姓名\";s:6:\"idcard\";s:15:\"身份證號碼\";s:5:\"phone\";s:12:\"手機號碼\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:10:\"attachment\";s:12:\"認證附件\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:5:\"uname\";s:1:\"0\";s:12:\"usergroup_id\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:8:\"realname\";s:1:\"0\";s:6:\"idcard\";s:1:\"0\";s:5:\"phone\";s:1:\"0\";s:6:\"reason\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:10:\"attachment\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:33:\"admin.addVerifyConfig(this.value)\";s:8:\"category\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:10:\"attachment\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 09:28:41');
INSERT INTO `ts_system_config` VALUES ('4691', 'pageKey', 'admin_Content_editTopic', 'a:6:{s:3:\"key\";a:9:{s:8:\"topic_id\";s:8:\"topic_id\";s:10:\"topic_name\";s:10:\"topic_name\";s:4:\"note\";s:4:\"note\";s:6:\"domain\";s:6:\"domain\";s:3:\"des\";s:3:\"des\";s:3:\"pic\";s:3:\"pic\";s:10:\"topic_user\";s:10:\"topic_user\";s:7:\"outlink\";s:7:\"outlink\";s:9:\"recommend\";s:9:\"recommend\";}s:8:\"key_name\";a:9:{s:8:\"topic_id\";s:8:\"話題ID\";s:10:\"topic_name\";s:12:\"話題名稱\";s:4:\"note\";s:12:\"話題註釋\";s:6:\"domain\";s:12:\"話題域名\";s:3:\"des\";s:12:\"詳細說明\";s:3:\"pic\";s:12:\"話題圖片\";s:10:\"topic_user\";s:18:\"話題人物推薦\";s:7:\"outlink\";s:12:\"添加外鏈\";s:9:\"recommend\";s:27:\"設定為熱門話題推薦\";}s:8:\"key_type\";a:9:{s:8:\"topic_id\";s:6:\"hidden\";s:10:\"topic_name\";s:4:\"word\";s:4:\"note\";s:8:\"textarea\";s:6:\"domain\";s:6:\"define\";s:3:\"des\";s:8:\"textarea\";s:3:\"pic\";s:5:\"image\";s:10:\"topic_user\";s:4:\"user\";s:7:\"outlink\";s:4:\"text\";s:9:\"recommend\";s:5:\"radio\";}s:11:\"key_default\";a:9:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:0:\"\";s:10:\"topic_user\";s:0:\"\";s:7:\"outlink\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:28:\"最佳圖片大小為620*200\";s:10:\"topic_user\";s:41:\"前臺最多顯示12個話題推薦人物\";s:7:\"outlink\";s:27:\"以http://或https://開頭\";s:9:\"recommend\";s:35:\"前臺最多顯示10個熱門話題\";}s:14:\"key_javascript\";a:9:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:0:\"\";s:10:\"topic_user\";s:0:\"\";s:7:\"outlink\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}}', '2013-03-08 18:03:05');
INSERT INTO `ts_system_config` VALUES ('2596', 'pageKey', 'admin_User_addVerify', 'a:6:{s:3:\"key\";a:10:{s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:25:\"user_verified_category_id\";s:25:\"user_verified_category_id\";s:7:\"company\";s:7:\"company\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:6:\"attach\";s:6:\"attach\";}s:8:\"key_name\";a:10:{s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:25:\"user_verified_category_id\";s:12:\"認證分類\";s:7:\"company\";s:12:\"企業名稱\";s:8:\"realname\";s:12:\"真實姓名\";s:6:\"idcard\";s:15:\"身份證號碼\";s:5:\"phone\";s:12:\"手機號碼\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:6:\"attach\";s:12:\"認證附件\";}s:8:\"key_type\";a:10:{s:5:\"uname\";s:7:\"oneUser\";s:12:\"usergroup_id\";s:5:\"radio\";s:25:\"user_verified_category_id\";s:6:\"select\";s:7:\"company\";s:4:\"text\";s:8:\"realname\";s:4:\"text\";s:6:\"idcard\";s:4:\"text\";s:5:\"phone\";s:4:\"text\";s:6:\"reason\";s:8:\"textarea\";s:4:\"info\";s:8:\"textarea\";s:6:\"attach\";s:4:\"file\";}s:11:\"key_default\";a:10:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:1:\"5\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:9:\"key_tishi\";a:10:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:14:\"key_javascript\";a:10:{s:5:\"uname\";s:1:\"1\";s:12:\"usergroup_id\";s:33:\"admin.addVerifyConfig(this.value)\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}}', '2013-01-07 10:23:50');
INSERT INTO `ts_system_config` VALUES ('5025', 'pageKey', 'admin_Content_topic', 'a:4:{s:3:\"key\";a:9:{s:8:\"topic_id\";s:8:\"topic_id\";s:10:\"topic_name\";s:10:\"topic_name\";s:4:\"note\";s:4:\"note\";s:6:\"domain\";s:6:\"domain\";s:3:\"des\";s:3:\"des\";s:3:\"pic\";s:3:\"pic\";s:10:\"topic_user\";s:10:\"topic_user\";s:7:\"outlink\";s:7:\"outlink\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:9:{s:8:\"topic_id\";s:8:\"話題ID\";s:10:\"topic_name\";s:12:\"話題名稱\";s:4:\"note\";s:12:\"話題註釋\";s:6:\"domain\";s:12:\"話題域名\";s:3:\"des\";s:12:\"詳細說明\";s:3:\"pic\";s:12:\"話題圖片\";s:10:\"topic_user\";s:18:\"話題人物推薦\";s:7:\"outlink\";s:6:\"外鏈\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:9:{s:8:\"topic_id\";s:1:\"0\";s:10:\"topic_name\";s:1:\"0\";s:4:\"note\";s:1:\"0\";s:6:\"domain\";s:1:\"0\";s:3:\"des\";s:1:\"0\";s:3:\"pic\";s:1:\"0\";s:10:\"topic_user\";s:1:\"0\";s:7:\"outlink\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:9:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:0:\"\";s:10:\"topic_user\";s:0:\"\";s:7:\"outlink\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-13 13:39:16');
INSERT INTO `ts_system_config` VALUES ('1517', 'pageKey', 'admin_Home_addfeedbackType_edit', 'a:6:{s:3:\"key\";a:2:{s:7:\"type_id\";s:7:\"type_id\";s:9:\"type_name\";s:9:\"type_name\";}s:8:\"key_name\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:12:\"反饋類型\";}s:8:\"key_type\";a:2:{s:7:\"type_id\";s:6:\"hidden\";s:9:\"type_name\";s:4:\"text\";}s:11:\"key_default\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:7:\"type_id\";s:0:\"\";s:9:\"type_name\";s:0:\"\";}}', '2012-07-25 09:28:38');
INSERT INTO `ts_system_config` VALUES ('1304', 'pageKey', 'admin_Config_lang', 'a:4:{s:3:\"key\";a:8:{s:7:\"lang_id\";s:7:\"lang_id\";s:3:\"key\";s:3:\"key\";s:7:\"appname\";s:7:\"appname\";s:8:\"filetype\";s:8:\"filetype\";s:5:\"zh-cn\";s:5:\"zh-cn\";s:5:\"zh-tw\";s:5:\"zh-tw\";s:2:\"en\";s:2:\"en\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:7:\"lang_id\";s:2:\"ID\";s:3:\"key\";s:9:\"語言KEY\";s:7:\"appname\";s:12:\"應用名稱\";s:8:\"filetype\";s:12:\"檔案類型\";s:5:\"zh-cn\";s:6:\"簡體\";s:5:\"zh-tw\";s:6:\"繁體\";s:2:\"en\";s:6:\"英文\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:7:\"lang_id\";s:1:\"0\";s:3:\"key\";s:1:\"0\";s:7:\"appname\";s:1:\"0\";s:8:\"filetype\";s:1:\"0\";s:5:\"zh-cn\";s:1:\"0\";s:5:\"zh-tw\";s:1:\"0\";s:2:\"en\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:7:\"lang_id\";s:0:\"\";s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:5:\"zh-cn\";s:0:\"\";s:5:\"zh-tw\";s:0:\"\";s:2:\"en\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-07-22 21:42:30');
INSERT INTO `ts_system_config` VALUES ('4800', 'searchPageKey', 'S_admin_Config_lang', 'a:5:{s:3:\"key\";a:4:{s:3:\"key\";s:3:\"key\";s:7:\"appname\";s:7:\"appname\";s:8:\"filetype\";s:8:\"filetype\";s:7:\"content\";s:7:\"content\";}s:8:\"key_name\";a:4:{s:3:\"key\";s:9:\"語言KEY\";s:7:\"appname\";s:12:\"應用名稱\";s:8:\"filetype\";s:12:\"檔案類型\";s:7:\"content\";s:12:\"語言內容\";}s:8:\"key_type\";a:4:{s:3:\"key\";s:4:\"text\";s:7:\"appname\";s:4:\"text\";s:8:\"filetype\";s:5:\"radio\";s:7:\"content\";s:4:\"text\";}s:9:\"key_tishi\";a:4:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:7:\"content\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:7:\"content\";s:0:\"\";}}', '2013-03-12 10:17:24');
INSERT INTO `ts_system_config` VALUES ('1312', 'pageKey', 'admin_Config_updateLangContent', 'a:6:{s:3:\"key\";a:6:{s:3:\"key\";s:3:\"key\";s:7:\"appname\";s:7:\"appname\";s:8:\"filetype\";s:8:\"filetype\";s:5:\"zh-cn\";s:5:\"zh-cn\";s:5:\"zh-tw\";s:5:\"zh-tw\";s:2:\"en\";s:2:\"en\";}s:8:\"key_name\";a:6:{s:3:\"key\";s:9:\"語言KEY\";s:7:\"appname\";s:12:\"應用名稱\";s:8:\"filetype\";s:12:\"檔案類型\";s:5:\"zh-cn\";s:12:\"簡體中文\";s:5:\"zh-tw\";s:12:\"繁體中文\";s:2:\"en\";s:6:\"英文\";}s:8:\"key_type\";a:6:{s:3:\"key\";s:4:\"text\";s:7:\"appname\";s:4:\"text\";s:8:\"filetype\";s:5:\"radio\";s:5:\"zh-cn\";s:8:\"textarea\";s:5:\"zh-tw\";s:8:\"textarea\";s:2:\"en\";s:8:\"textarea\";}s:11:\"key_default\";a:6:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:5:\"zh-cn\";s:0:\"\";s:5:\"zh-tw\";s:0:\"\";s:2:\"en\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:5:\"zh-cn\";s:0:\"\";s:5:\"zh-tw\";s:0:\"\";s:2:\"en\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:5:\"zh-cn\";s:0:\"\";s:5:\"zh-tw\";s:0:\"\";s:2:\"en\";s:0:\"\";}}', '2012-07-22 21:47:21');
INSERT INTO `ts_system_config` VALUES ('1684', 'pageKey', 'admin_User_viewIP', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:3:\"day\";s:3:\"day\";s:6:\"action\";s:6:\"action\";s:2:\"ip\";s:2:\"ip\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:8:\"日誌ID\";s:3:\"day\";s:6:\"時間\";s:6:\"action\";s:6:\"動作\";s:2:\"ip\";s:8:\"IP記錄\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"0\";s:3:\"day\";s:1:\"0\";s:6:\"action\";s:1:\"0\";s:2:\"ip\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:3:\"day\";s:0:\"\";s:6:\"action\";s:0:\"\";s:2:\"ip\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-18 01:08:24');
INSERT INTO `ts_system_config` VALUES ('1685', 'pageKey', 'admin_User_loginLog', 'a:4:{s:3:\"key\";a:4:{s:13:\"login_logs_id\";s:13:\"login_logs_id\";s:2:\"ip\";s:2:\"ip\";s:5:\"ctime\";s:5:\"ctime\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:13:\"login_logs_id\";s:8:\"日誌ID\";s:2:\"ip\";s:8:\"IP操作\";s:5:\"ctime\";s:6:\"時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:13:\"login_logs_id\";s:1:\"0\";s:2:\"ip\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:13:\"login_logs_id\";s:0:\"\";s:2:\"ip\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-08-18 01:08:52');
INSERT INTO `ts_system_config` VALUES ('2688', 'pageKey', 'admin_User_verify', 'a:4:{s:3:\"key\";a:10:{s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:8:\"category\";s:8:\"category\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:10:\"attachment\";s:10:\"attachment\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:8:\"category\";s:12:\"認證分類\";s:8:\"realname\";s:12:\"真實姓名\";s:6:\"idcard\";s:15:\"身份證號碼\";s:5:\"phone\";s:12:\"手機號碼\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:10:\"attachment\";s:12:\"認證附件\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:5:\"uname\";s:1:\"0\";s:12:\"usergroup_id\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:8:\"realname\";s:1:\"0\";s:6:\"idcard\";s:1:\"0\";s:5:\"phone\";s:1:\"0\";s:6:\"reason\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:10:\"attachment\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:8:\"category\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:10:\"attachment\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-01-10 11:42:03');
INSERT INTO `ts_system_config` VALUES ('2685', 'pageKey', 'admin_User_verifiedGroup', 'a:4:{s:3:\"key\";a:11:{s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:8:\"category\";s:8:\"category\";s:7:\"company\";s:7:\"company\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:10:\"attachment\";s:10:\"attachment\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:11:{s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:8:\"category\";s:12:\"認證分類\";s:7:\"company\";s:12:\"企業名稱\";s:8:\"realname\";s:12:\"法人姓名\";s:6:\"idcard\";s:15:\"營業執照號\";s:5:\"phone\";s:12:\"聯繫方式\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:10:\"attachment\";s:12:\"認證附件\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:11:{s:5:\"uname\";s:1:\"0\";s:12:\"usergroup_id\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:7:\"company\";s:1:\"0\";s:8:\"realname\";s:1:\"0\";s:6:\"idcard\";s:1:\"0\";s:5:\"phone\";s:1:\"0\";s:6:\"reason\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:10:\"attachment\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:11:{s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:8:\"category\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:10:\"attachment\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-01-10 11:41:01');
INSERT INTO `ts_system_config` VALUES ('2888', 'pageKey', 'admin_User_editVerify', 'a:6:{s:3:\"key\";a:11:{s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:12:\"usergroup_id\";s:12:\"usergroup_id\";s:25:\"user_verified_category_id\";s:25:\"user_verified_category_id\";s:7:\"company\";s:7:\"company\";s:8:\"realname\";s:8:\"realname\";s:6:\"idcard\";s:6:\"idcard\";s:5:\"phone\";s:5:\"phone\";s:6:\"reason\";s:6:\"reason\";s:4:\"info\";s:4:\"info\";s:6:\"attach\";s:6:\"attach\";}s:8:\"key_name\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:9:\"使用者名\";s:12:\"usergroup_id\";s:12:\"認證類型\";s:25:\"user_verified_category_id\";s:12:\"認證分類\";s:7:\"company\";s:12:\"企業名稱\";s:8:\"realname\";s:12:\"真實姓名\";s:6:\"idcard\";s:15:\"身份證號碼\";s:5:\"phone\";s:12:\"手機號碼\";s:6:\"reason\";s:12:\"認證理由\";s:4:\"info\";s:12:\"認證資料\";s:6:\"attach\";s:12:\"認證附件\";}s:8:\"key_type\";a:11:{s:3:\"uid\";s:6:\"hidden\";s:5:\"uname\";s:4:\"word\";s:12:\"usergroup_id\";s:5:\"radio\";s:25:\"user_verified_category_id\";s:6:\"select\";s:7:\"company\";s:4:\"text\";s:8:\"realname\";s:4:\"text\";s:6:\"idcard\";s:4:\"text\";s:5:\"phone\";s:4:\"text\";s:6:\"reason\";s:8:\"textarea\";s:4:\"info\";s:8:\"textarea\";s:6:\"attach\";s:4:\"file\";}s:11:\"key_default\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:9:\"key_tishi\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:0:\"\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}s:14:\"key_javascript\";a:11:{s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:12:\"usergroup_id\";s:33:\"admin.addVerifyConfig(this.value)\";s:25:\"user_verified_category_id\";s:0:\"\";s:7:\"company\";s:0:\"\";s:8:\"realname\";s:0:\"\";s:6:\"idcard\";s:0:\"\";s:5:\"phone\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:4:\"info\";s:0:\"\";s:6:\"attach\";s:0:\"\";}}', '2013-01-22 17:46:45');
INSERT INTO `ts_system_config` VALUES ('4690', 'pageKey', 'admin_Content_addTopic', 'a:6:{s:3:\"key\";a:8:{s:10:\"topic_name\";s:10:\"topic_name\";s:4:\"note\";s:4:\"note\";s:6:\"domain\";s:6:\"domain\";s:3:\"des\";s:3:\"des\";s:3:\"pic\";s:3:\"pic\";s:10:\"topic_user\";s:10:\"topic_user\";s:7:\"outlink\";s:7:\"outlink\";s:9:\"recommend\";s:9:\"recommend\";}s:8:\"key_name\";a:8:{s:10:\"topic_name\";s:12:\"話題名稱\";s:4:\"note\";s:12:\"話題註釋\";s:6:\"domain\";s:12:\"話題域名\";s:3:\"des\";s:12:\"詳細說明\";s:3:\"pic\";s:6:\"圖片\";s:10:\"topic_user\";s:18:\"話題人物推薦\";s:7:\"outlink\";s:6:\"外鏈\";s:9:\"recommend\";s:12:\"設為推薦\";}s:8:\"key_type\";a:8:{s:10:\"topic_name\";s:4:\"text\";s:4:\"note\";s:8:\"textarea\";s:6:\"domain\";s:6:\"define\";s:3:\"des\";s:8:\"textarea\";s:3:\"pic\";s:5:\"image\";s:10:\"topic_user\";s:4:\"user\";s:7:\"outlink\";s:4:\"text\";s:9:\"recommend\";s:5:\"radio\";}s:11:\"key_default\";a:8:{s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:0:\"\";s:10:\"topic_user\";s:0:\"\";s:7:\"outlink\";s:0:\"\";s:9:\"recommend\";s:1:\"0\";}s:9:\"key_tishi\";a:8:{s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:28:\"最佳圖片大小為620*200\";s:10:\"topic_user\";s:41:\"前臺最多顯示12個話題推薦人物\";s:7:\"outlink\";s:27:\"以http://或https://開頭\";s:9:\"recommend\";s:35:\"前臺最多顯示10個熱門話題\";}s:14:\"key_javascript\";a:8:{s:10:\"topic_name\";s:0:\"\";s:4:\"note\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:3:\"des\";s:0:\"\";s:3:\"pic\";s:0:\"\";s:10:\"topic_user\";s:0:\"\";s:7:\"outlink\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}}', '2013-03-08 18:02:28');
INSERT INTO `ts_system_config` VALUES ('2554', 'searchPageKey', 'S_admin_Content_topic', 'a:5:{s:3:\"key\";a:5:{s:8:\"topic_id\";s:8:\"topic_id\";s:10:\"topic_name\";s:10:\"topic_name\";s:9:\"recommend\";s:9:\"recommend\";s:7:\"essence\";s:7:\"essence\";s:4:\"lock\";s:4:\"lock\";}s:8:\"key_name\";a:5:{s:8:\"topic_id\";s:8:\"話題ID\";s:10:\"topic_name\";s:12:\"話題名稱\";s:9:\"recommend\";s:12:\"是否推薦\";s:7:\"essence\";s:12:\"是否精華\";s:4:\"lock\";s:12:\"是否遮蔽\";}s:8:\"key_type\";a:5:{s:8:\"topic_id\";s:4:\"text\";s:10:\"topic_name\";s:4:\"text\";s:9:\"recommend\";s:5:\"radio\";s:7:\"essence\";s:5:\"radio\";s:4:\"lock\";s:5:\"radio\";}s:9:\"key_tishi\";a:5:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:7:\"essence\";s:0:\"\";s:4:\"lock\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:8:\"topic_id\";s:0:\"\";s:10:\"topic_name\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:7:\"essence\";s:0:\"\";s:4:\"lock\";s:0:\"\";}}', '2012-12-27 12:06:39');
INSERT INTO `ts_system_config` VALUES ('2146', 'searchPageKey', 'S_weiba_Admin_postList', 'a:5:{s:3:\"key\";a:7:{s:7:\"post_id\";s:7:\"post_id\";s:5:\"title\";s:5:\"title\";s:8:\"post_uid\";s:8:\"post_uid\";s:9:\"recommend\";s:9:\"recommend\";s:6:\"digest\";s:6:\"digest\";s:3:\"top\";s:3:\"top\";s:8:\"weiba_id\";s:8:\"weiba_id\";}s:8:\"key_name\";a:7:{s:7:\"post_id\";s:8:\"帖子ID\";s:5:\"title\";s:12:\"帖子標題\";s:8:\"post_uid\";s:11:\"發帖人ID\";s:9:\"recommend\";s:12:\"是否推薦\";s:6:\"digest\";s:12:\"是否精華\";s:3:\"top\";s:12:\"是否置頂\";s:8:\"weiba_id\";s:12:\"所屬微吧\";}s:8:\"key_type\";a:7:{s:7:\"post_id\";s:4:\"text\";s:5:\"title\";s:4:\"text\";s:8:\"post_uid\";s:4:\"text\";s:9:\"recommend\";s:5:\"radio\";s:6:\"digest\";s:5:\"radio\";s:3:\"top\";s:5:\"radio\";s:8:\"weiba_id\";s:6:\"select\";}s:9:\"key_tishi\";a:7:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:6:\"digest\";s:0:\"\";s:3:\"top\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:6:\"digest\";s:0:\"\";s:3:\"top\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";}}', '2012-11-07 01:17:16');
INSERT INTO `ts_system_config` VALUES ('2156', 'searchPageKey', 'S_weiba_Admin_index', 'a:5:{s:3:\"key\";a:5:{s:8:\"weiba_id\";s:8:\"weiba_id\";s:10:\"weiba_name\";s:10:\"weiba_name\";s:3:\"uid\";s:3:\"uid\";s:9:\"admin_uid\";s:9:\"admin_uid\";s:9:\"recommend\";s:9:\"recommend\";}s:8:\"key_name\";a:5:{s:8:\"weiba_id\";s:8:\"微吧ID\";s:10:\"weiba_name\";s:12:\"微吧名稱\";s:3:\"uid\";s:12:\"創建者UID\";s:9:\"admin_uid\";s:9:\"吧主UID\";s:9:\"recommend\";s:12:\"是否推薦\";}s:8:\"key_type\";a:5:{s:8:\"weiba_id\";s:4:\"text\";s:10:\"weiba_name\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:9:\"admin_uid\";s:4:\"text\";s:9:\"recommend\";s:5:\"radio\";}s:9:\"key_tishi\";a:5:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}}', '2012-11-07 02:38:51');
INSERT INTO `ts_system_config` VALUES ('2154', 'pageKey', 'weiba_Admin_index', 'a:4:{s:3:\"key\";a:8:{s:8:\"weiba_id\";s:8:\"weiba_id\";s:10:\"weiba_name\";s:10:\"weiba_name\";s:4:\"logo\";s:4:\"logo\";s:3:\"uid\";s:3:\"uid\";s:5:\"ctime\";s:5:\"ctime\";s:9:\"admin_uid\";s:9:\"admin_uid\";s:27:\"follower_count/thread_count\";s:27:\"follower_count/thread_count\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:8:\"weiba_id\";s:8:\"微吧ID\";s:10:\"weiba_name\";s:12:\"微吧名稱\";s:4:\"logo\";s:4:\"logo\";s:3:\"uid\";s:9:\"創建者\";s:5:\"ctime\";s:12:\"創建時間\";s:9:\"admin_uid\";s:6:\"吧主\";s:27:\"follower_count/thread_count\";s:19:\"成員數/帖子數\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:8:\"weiba_id\";s:1:\"0\";s:10:\"weiba_name\";s:1:\"0\";s:4:\"logo\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:9:\"admin_uid\";s:1:\"0\";s:27:\"follower_count/thread_count\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:27:\"follower_count/thread_count\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-11-07 02:38:25');
INSERT INTO `ts_system_config` VALUES ('4590', 'pageKey', 'weiba_Admin_addWeiba', 'a:6:{s:3:\"key\";a:6:{s:10:\"weiba_name\";s:10:\"weiba_name\";s:4:\"logo\";s:4:\"logo\";s:5:\"intro\";s:5:\"intro\";s:12:\"who_can_post\";s:12:\"who_can_post\";s:9:\"admin_uid\";s:9:\"admin_uid\";s:9:\"recommend\";s:9:\"recommend\";}s:8:\"key_name\";a:6:{s:10:\"weiba_name\";s:12:\"微吧名稱\";s:4:\"logo\";s:4:\"logo\";s:5:\"intro\";s:12:\"微吧簡介\";s:12:\"who_can_post\";s:12:\"發帖許可權\";s:9:\"admin_uid\";s:6:\"吧主\";s:9:\"recommend\";s:12:\"是否推薦\";}s:8:\"key_type\";a:6:{s:10:\"weiba_name\";s:4:\"text\";s:4:\"logo\";s:5:\"image\";s:5:\"intro\";s:8:\"textarea\";s:12:\"who_can_post\";s:5:\"radio\";s:9:\"admin_uid\";s:7:\"oneUser\";s:9:\"recommend\";s:5:\"radio\";}s:11:\"key_default\";a:6:{s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:1:\"0\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:1:\"0\";}s:9:\"key_tishi\";a:6:{s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:73:\"附件格式：gif，jpg，jpeg，png，bmp； 附件大小：不超過2M\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}}', '2013-03-07 16:37:16');
INSERT INTO `ts_system_config` VALUES ('4593', 'pageKey', 'weiba_Admin_editWeiba', 'a:6:{s:3:\"key\";a:7:{s:8:\"weiba_id\";s:8:\"weiba_id\";s:10:\"weiba_name\";s:10:\"weiba_name\";s:4:\"logo\";s:4:\"logo\";s:5:\"intro\";s:5:\"intro\";s:12:\"who_can_post\";s:12:\"who_can_post\";s:9:\"admin_uid\";s:9:\"admin_uid\";s:9:\"recommend\";s:9:\"recommend\";}s:8:\"key_name\";a:7:{s:8:\"weiba_id\";s:8:\"微吧ID\";s:10:\"weiba_name\";s:12:\"微吧名稱\";s:4:\"logo\";s:4:\"logo\";s:5:\"intro\";s:12:\"微吧簡介\";s:12:\"who_can_post\";s:12:\"發帖許可權\";s:9:\"admin_uid\";s:6:\"吧主\";s:9:\"recommend\";s:12:\"是否推薦\";}s:8:\"key_type\";a:7:{s:8:\"weiba_id\";s:6:\"hidden\";s:10:\"weiba_name\";s:4:\"text\";s:4:\"logo\";s:5:\"image\";s:5:\"intro\";s:8:\"textarea\";s:12:\"who_can_post\";s:5:\"radio\";s:9:\"admin_uid\";s:7:\"oneUser\";s:9:\"recommend\";s:5:\"radio\";}s:11:\"key_default\";a:7:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}s:9:\"key_tishi\";a:7:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:73:\"附件格式：gif，jpg，jpeg，png，bmp； 附件大小：不超過2M\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:8:\"weiba_id\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:4:\"logo\";s:0:\"\";s:5:\"intro\";s:0:\"\";s:12:\"who_can_post\";s:0:\"\";s:9:\"admin_uid\";s:0:\"\";s:9:\"recommend\";s:0:\"\";}}', '2013-03-07 16:51:55');
INSERT INTO `ts_system_config` VALUES ('6076', 'pageKey', 'channel_Admin_index', 'a:6:{s:3:\"key\";a:3:{s:8:\"is_audit\";s:8:\"is_audit\";s:16:\"default_category\";s:16:\"default_category\";s:9:\"show_type\";s:9:\"show_type\";}s:8:\"key_name\";a:3:{s:8:\"is_audit\";s:12:\"是否稽覈\";s:16:\"default_category\";s:12:\"默認分類\";s:9:\"show_type\";s:12:\"展示風格\";}s:8:\"key_type\";a:3:{s:8:\"is_audit\";s:5:\"radio\";s:16:\"default_category\";s:6:\"select\";s:9:\"show_type\";s:5:\"radio\";}s:11:\"key_default\";a:3:{s:8:\"is_audit\";s:0:\"\";s:16:\"default_category\";s:0:\"\";s:9:\"show_type\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:8:\"is_audit\";s:0:\"\";s:16:\"default_category\";s:0:\"\";s:9:\"show_type\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:8:\"is_audit\";s:0:\"\";s:16:\"default_category\";s:0:\"\";s:9:\"show_type\";s:0:\"\";}}', '2013-03-27 14:39:14');
INSERT INTO `ts_system_config` VALUES ('2180', 'pageKey', 'page_Admin_editCanvas', 'a:6:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:11:\"canvas_name\";s:11:\"canvas_name\";s:11:\"description\";s:11:\"description\";s:4:\"data\";s:4:\"data\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:6:\"名稱\";s:11:\"canvas_name\";s:12:\"畫布名稱\";s:11:\"description\";s:6:\"說明\";s:4:\"data\";s:12:\"畫布內容\";}s:8:\"key_type\";a:5:{s:2:\"id\";s:6:\"hidden\";s:5:\"title\";s:4:\"text\";s:11:\"canvas_name\";s:4:\"text\";s:11:\"description\";s:4:\"text\";s:4:\"data\";s:8:\"textarea\";}s:11:\"key_default\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}}', '2012-11-08 18:24:39');
INSERT INTO `ts_system_config` VALUES ('2182', 'pageKey', 'page_Admin_editPage', 'a:6:{s:3:\"key\";a:9:{s:2:\"id\";s:2:\"id\";s:9:\"page_name\";s:9:\"page_name\";s:6:\"domain\";s:6:\"domain\";s:6:\"canvas\";s:6:\"canvas\";s:6:\"status\";s:6:\"status\";s:5:\"guest\";s:5:\"guest\";s:9:\"seo_title\";s:9:\"seo_title\";s:12:\"seo_keywords\";s:12:\"seo_keywords\";s:15:\"seo_description\";s:15:\"seo_description\";}s:8:\"key_name\";a:9:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:6:\"名稱\";s:6:\"domain\";s:6:\"域名\";s:6:\"canvas\";s:6:\"畫布\";s:6:\"status\";s:6:\"狀態\";s:5:\"guest\";s:6:\"遊客\";s:9:\"seo_title\";s:9:\"SEO title\";s:12:\"seo_keywords\";s:12:\"SEO keywords\";s:15:\"seo_description\";s:15:\"SEO description\";}s:8:\"key_type\";a:9:{s:2:\"id\";s:6:\"hidden\";s:9:\"page_name\";s:4:\"text\";s:6:\"domain\";s:4:\"text\";s:6:\"canvas\";s:6:\"select\";s:6:\"status\";s:5:\"radio\";s:5:\"guest\";s:5:\"radio\";s:9:\"seo_title\";s:4:\"text\";s:12:\"seo_keywords\";s:4:\"text\";s:15:\"seo_description\";s:4:\"text\";}s:11:\"key_default\";a:9:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}s:14:\"key_javascript\";a:9:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}}', '2012-11-08 18:25:13');
INSERT INTO `ts_system_config` VALUES ('2556', 'pageKey', 'page_Admin_addCanvas', 'a:6:{s:3:\"key\";a:4:{s:5:\"title\";s:5:\"title\";s:11:\"canvas_name\";s:11:\"canvas_name\";s:11:\"description\";s:11:\"description\";s:4:\"data\";s:4:\"data\";}s:8:\"key_name\";a:4:{s:5:\"title\";s:6:\"名稱\";s:11:\"canvas_name\";s:12:\"畫布名稱\";s:11:\"description\";s:6:\"說明\";s:4:\"data\";s:12:\"畫布內容\";}s:8:\"key_type\";a:4:{s:5:\"title\";s:4:\"text\";s:11:\"canvas_name\";s:4:\"text\";s:11:\"description\";s:4:\"text\";s:4:\"data\";s:8:\"textarea\";}s:11:\"key_default\";a:4:{s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:4:\"data\";s:0:\"\";}}', '2012-12-28 16:01:48');
INSERT INTO `ts_system_config` VALUES ('6514', 'pageKey', 'page_Admin_addPage', 'a:6:{s:3:\"key\";a:8:{s:9:\"page_name\";s:9:\"page_name\";s:6:\"domain\";s:6:\"domain\";s:6:\"canvas\";s:6:\"canvas\";s:6:\"status\";s:6:\"status\";s:5:\"guest\";s:5:\"guest\";s:9:\"seo_title\";s:9:\"seo_title\";s:12:\"seo_keywords\";s:12:\"seo_keywords\";s:15:\"seo_description\";s:15:\"seo_description\";}s:8:\"key_name\";a:8:{s:9:\"page_name\";s:6:\"名稱\";s:6:\"domain\";s:6:\"域名\";s:6:\"canvas\";s:6:\"畫布\";s:6:\"status\";s:6:\"狀態\";s:5:\"guest\";s:6:\"遊客\";s:9:\"seo_title\";s:9:\"SEO標題\";s:12:\"seo_keywords\";s:12:\"SEO關鍵詞\";s:15:\"seo_description\";s:9:\"SEO描述\";}s:8:\"key_type\";a:8:{s:9:\"page_name\";s:4:\"text\";s:6:\"domain\";s:4:\"text\";s:6:\"canvas\";s:6:\"select\";s:6:\"status\";s:5:\"radio\";s:5:\"guest\";s:5:\"radio\";s:9:\"seo_title\";s:4:\"text\";s:12:\"seo_keywords\";s:4:\"text\";s:15:\"seo_description\";s:4:\"text\";}s:11:\"key_default\";a:8:{s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}s:9:\"key_tishi\";a:8:{s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}s:14:\"key_javascript\";a:8:{s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:6:\"status\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:9:\"seo_title\";s:0:\"\";s:12:\"seo_keywords\";s:0:\"\";s:15:\"seo_description\";s:0:\"\";}}', '2013-04-11 16:17:53');
INSERT INTO `ts_system_config` VALUES ('6513', 'pageKey', 'page_Admin_index', 'a:4:{s:3:\"key\";a:7:{s:2:\"id\";s:2:\"id\";s:9:\"page_name\";s:9:\"page_name\";s:6:\"domain\";s:6:\"domain\";s:6:\"canvas\";s:6:\"canvas\";s:7:\"manager\";s:7:\"manager\";s:11:\"visit_count\";s:11:\"visit_count\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:9:\"頁面名\";s:6:\"domain\";s:6:\"域名\";s:6:\"canvas\";s:6:\"畫布\";s:7:\"manager\";s:9:\"管理員\";s:11:\"visit_count\";s:9:\"訪問量\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:2:\"id\";s:1:\"1\";s:9:\"page_name\";s:1:\"0\";s:6:\"domain\";s:1:\"0\";s:6:\"canvas\";s:1:\"0\";s:7:\"manager\";s:1:\"0\";s:11:\"visit_count\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:2:\"id\";s:0:\"\";s:9:\"page_name\";s:0:\"\";s:6:\"domain\";s:0:\"\";s:6:\"canvas\";s:0:\"\";s:7:\"manager\";s:0:\"\";s:11:\"visit_count\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-04-11 16:15:48');
INSERT INTO `ts_system_config` VALUES ('2179', 'pageKey', 'page_Admin_canvas', 'a:4:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:11:\"canvas_name\";s:11:\"canvas_name\";s:11:\"description\";s:11:\"description\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:6:\"名稱\";s:11:\"canvas_name\";s:12:\"畫布名稱\";s:11:\"description\";s:6:\"說明\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:2:\"id\";s:1:\"1\";s:5:\"title\";s:1:\"0\";s:11:\"canvas_name\";s:1:\"0\";s:11:\"description\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:11:\"canvas_name\";s:0:\"\";s:11:\"description\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-11-08 18:24:26');
INSERT INTO `ts_system_config` VALUES ('2147', 'pageKey', 'weiba_Admin_postList', 'a:4:{s:3:\"key\";a:8:{s:7:\"post_id\";s:7:\"post_id\";s:5:\"title\";s:5:\"title\";s:8:\"post_uid\";s:8:\"post_uid\";s:9:\"post_time\";s:9:\"post_time\";s:15:\"last_reply_time\";s:15:\"last_reply_time\";s:22:\"read_count/reply_count\";s:22:\"read_count/reply_count\";s:8:\"weiba_id\";s:8:\"weiba_id\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:7:\"post_id\";s:8:\"帖子ID\";s:5:\"title\";s:12:\"帖子標題\";s:8:\"post_uid\";s:9:\"發帖人\";s:9:\"post_time\";s:12:\"發帖時間\";s:15:\"last_reply_time\";s:18:\"最後回覆時間\";s:22:\"read_count/reply_count\";s:19:\"瀏覽數/評論數\";s:8:\"weiba_id\";s:12:\"所屬微吧\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:7:\"post_id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:8:\"post_uid\";s:1:\"0\";s:9:\"post_time\";s:1:\"0\";s:15:\"last_reply_time\";s:1:\"0\";s:22:\"read_count/reply_count\";s:1:\"0\";s:8:\"weiba_id\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:9:\"post_time\";s:0:\"\";s:15:\"last_reply_time\";s:0:\"\";s:22:\"read_count/reply_count\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-11-07 01:18:23');
INSERT INTO `ts_system_config` VALUES ('2518', 'pageKey', 'weiba_Admin_editPost', 'a:6:{s:3:\"key\";a:6:{s:7:\"post_id\";s:7:\"post_id\";s:5:\"title\";s:5:\"title\";s:7:\"content\";s:7:\"content\";s:9:\"recommend\";s:9:\"recommend\";s:6:\"digest\";s:6:\"digest\";s:3:\"top\";s:3:\"top\";}s:8:\"key_name\";a:6:{s:7:\"post_id\";s:8:\"帖子ID\";s:5:\"title\";s:12:\"帖子標題\";s:7:\"content\";s:12:\"帖子內容\";s:9:\"recommend\";s:12:\"是否推薦\";s:6:\"digest\";s:12:\"是否精華\";s:3:\"top\";s:12:\"是否置頂\";}s:8:\"key_type\";a:6:{s:7:\"post_id\";s:6:\"hidden\";s:5:\"title\";s:4:\"text\";s:7:\"content\";s:6:\"editor\";s:9:\"recommend\";s:5:\"radio\";s:6:\"digest\";s:5:\"radio\";s:3:\"top\";s:5:\"radio\";}s:11:\"key_default\";a:6:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:6:\"digest\";s:0:\"\";s:3:\"top\";s:0:\"\";}s:9:\"key_tishi\";a:6:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:6:\"digest\";s:0:\"\";s:3:\"top\";s:0:\"\";}s:14:\"key_javascript\";a:6:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:7:\"content\";s:0:\"\";s:9:\"recommend\";s:0:\"\";s:6:\"digest\";s:0:\"\";s:3:\"top\";s:0:\"\";}}', '2012-12-25 15:35:56');
INSERT INTO `ts_system_config` VALUES ('2149', 'searchPageKey', 'S_weiba_Admin_postRecycle', 'a:5:{s:3:\"key\";a:4:{s:7:\"post_id\";s:7:\"post_id\";s:5:\"title\";s:5:\"title\";s:8:\"post_uid\";s:8:\"post_uid\";s:8:\"weiba_id\";s:8:\"weiba_id\";}s:8:\"key_name\";a:4:{s:7:\"post_id\";s:8:\"帖子ID\";s:5:\"title\";s:12:\"帖子標題\";s:8:\"post_uid\";s:11:\"發帖人ID\";s:8:\"weiba_id\";s:12:\"所屬微吧\";}s:8:\"key_type\";a:4:{s:7:\"post_id\";s:4:\"text\";s:5:\"title\";s:4:\"text\";s:8:\"post_uid\";s:4:\"text\";s:8:\"weiba_id\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";}}', '2012-11-07 01:21:11');
INSERT INTO `ts_system_config` VALUES ('2150', 'pageKey', 'weiba_Admin_postRecycle', 'a:4:{s:3:\"key\";a:8:{s:7:\"post_id\";s:7:\"post_id\";s:5:\"title\";s:5:\"title\";s:8:\"post_uid\";s:8:\"post_uid\";s:9:\"post_time\";s:9:\"post_time\";s:15:\"last_reply_time\";s:15:\"last_reply_time\";s:22:\"read_count/reply_count\";s:22:\"read_count/reply_count\";s:8:\"weiba_id\";s:8:\"weiba_id\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:7:\"post_id\";s:8:\"帖子ID\";s:5:\"title\";s:12:\"帖子標題\";s:8:\"post_uid\";s:9:\"發帖人\";s:9:\"post_time\";s:12:\"發帖時間\";s:15:\"last_reply_time\";s:18:\"最後回覆時間\";s:22:\"read_count/reply_count\";s:19:\"瀏覽數/評論數\";s:8:\"weiba_id\";s:12:\"所屬微吧\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:7:\"post_id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:8:\"post_uid\";s:1:\"0\";s:9:\"post_time\";s:1:\"0\";s:15:\"last_reply_time\";s:1:\"0\";s:22:\"read_count/reply_count\";s:1:\"0\";s:8:\"weiba_id\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:7:\"post_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"post_uid\";s:0:\"\";s:9:\"post_time\";s:0:\"\";s:15:\"last_reply_time\";s:0:\"\";s:22:\"read_count/reply_count\";s:0:\"\";s:8:\"weiba_id\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-11-07 01:22:19');
INSERT INTO `ts_system_config` VALUES ('4868', 'pageKey', 'admin_User_official', 'a:6:{s:3:\"key\";a:1:{s:8:\"top_user\";s:8:\"top_user\";}s:8:\"key_name\";a:1:{s:8:\"top_user\";s:12:\"置頂使用者\";}s:8:\"key_type\";a:1:{s:8:\"top_user\";s:4:\"user\";}s:11:\"key_default\";a:1:{s:8:\"top_user\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:8:\"top_user\";s:43:\"前臺最多只能展示3個已推薦使用者\";}s:14:\"key_javascript\";a:1:{s:8:\"top_user\";s:0:\"\";}}', '2013-03-12 14:58:42');
INSERT INTO `ts_system_config` VALUES ('2220', 'pageKey', 'admin_User_officialList', 'a:4:{s:3:\"key\";a:6:{s:11:\"official_id\";s:11:\"official_id\";s:3:\"uid\";s:3:\"uid\";s:5:\"uname\";s:5:\"uname\";s:5:\"title\";s:5:\"title\";s:4:\"info\";s:4:\"info\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:11:\"official_id\";s:6:\"主鍵\";s:3:\"uid\";s:8:\"使用者ID\";s:5:\"uname\";s:12:\"使用者昵稱\";s:5:\"title\";s:12:\"分類名稱\";s:4:\"info\";s:12:\"相關資訊\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:11:\"official_id\";s:1:\"1\";s:3:\"uid\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:4:\"info\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:11:\"official_id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:5:\"title\";s:0:\"\";s:4:\"info\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-12-10 22:46:08');
INSERT INTO `ts_system_config` VALUES ('2221', 'pageKey', 'admin_User_officialAddUser', 'a:6:{s:3:\"key\";a:3:{s:4:\"uids\";s:4:\"uids\";s:8:\"category\";s:8:\"category\";s:4:\"info\";s:4:\"info\";}s:8:\"key_name\";a:3:{s:4:\"uids\";s:6:\"使用者\";s:8:\"category\";s:6:\"分類\";s:4:\"info\";s:12:\"相關資訊\";}s:8:\"key_type\";a:3:{s:4:\"uids\";s:4:\"user\";s:8:\"category\";s:6:\"select\";s:4:\"info\";s:8:\"textarea\";}s:11:\"key_default\";a:3:{s:4:\"uids\";s:0:\"\";s:8:\"category\";s:0:\"\";s:4:\"info\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:4:\"uids\";s:0:\"\";s:8:\"category\";s:0:\"\";s:4:\"info\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:4:\"uids\";s:0:\"\";s:8:\"category\";s:0:\"\";s:4:\"info\";s:0:\"\";}}', '2012-12-10 22:46:39');
INSERT INTO `ts_system_config` VALUES ('4867', 'pageKey', 'admin_User_verifyConfig', 'a:6:{s:3:\"key\";a:1:{s:8:\"top_user\";s:8:\"top_user\";}s:8:\"key_name\";a:1:{s:8:\"top_user\";s:12:\"置頂使用者\";}s:8:\"key_type\";a:1:{s:8:\"top_user\";s:4:\"user\";}s:11:\"key_default\";a:1:{s:8:\"top_user\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:8:\"top_user\";s:43:\"前臺最多只能展示3個已認證使用者\";}s:14:\"key_javascript\";a:1:{s:8:\"top_user\";s:0:\"\";}}', '2013-03-12 14:58:09');
INSERT INTO `ts_system_config` VALUES ('2500', 'pageKey', 'admin_Task_index', 'a:4:{s:3:\"key\";a:7:{s:9:\"task_name\";s:9:\"task_name\";s:9:\"step_name\";s:9:\"step_name\";s:9:\"step_desc\";s:9:\"step_desc\";s:5:\"count\";s:5:\"count\";s:6:\"reward\";s:6:\"reward\";s:5:\"medal\";s:5:\"medal\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:9:\"task_name\";s:12:\"任務類型\";s:9:\"step_name\";s:12:\"任務名稱\";s:9:\"step_desc\";s:12:\"任務說明\";s:5:\"count\";s:12:\"完成人數\";s:6:\"reward\";s:12:\"積分獎勵\";s:5:\"medal\";s:12:\"勳章獎勵\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:9:\"task_name\";s:1:\"0\";s:9:\"step_name\";s:1:\"0\";s:9:\"step_desc\";s:1:\"0\";s:5:\"count\";s:1:\"0\";s:6:\"reward\";s:1:\"0\";s:5:\"medal\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:9:\"task_name\";s:0:\"\";s:9:\"step_name\";s:0:\"\";s:9:\"step_desc\";s:0:\"\";s:5:\"count\";s:0:\"\";s:6:\"reward\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-12-25 10:27:34');
INSERT INTO `ts_system_config` VALUES ('5031', 'pageKey', 'admin_Task_editTask', 'a:6:{s:3:\"key\";a:11:{s:2:\"id\";s:2:\"id\";s:9:\"task_name\";s:9:\"task_name\";s:9:\"step_name\";s:9:\"step_name\";s:9:\"step_desc\";s:9:\"step_desc\";s:9:\"task_type\";s:9:\"task_type\";s:9:\"condition\";s:9:\"condition\";s:3:\"num\";s:3:\"num\";s:3:\"exp\";s:3:\"exp\";s:5:\"score\";s:5:\"score\";s:6:\"action\";s:6:\"action\";s:5:\"medal\";s:5:\"medal\";}s:8:\"key_name\";a:11:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:12:\"任務類型\";s:9:\"step_name\";s:12:\"任務名稱\";s:9:\"step_desc\";s:12:\"任務說明\";s:9:\"task_type\";s:0:\"\";s:9:\"condition\";s:0:\"\";s:3:\"num\";s:12:\"任務數值\";s:3:\"exp\";s:15:\"經驗獎勵值\";s:5:\"score\";s:15:\"財富獎勵值\";s:6:\"action\";s:0:\"\";s:5:\"medal\";s:12:\"勳章獎勵\";}s:8:\"key_type\";a:11:{s:2:\"id\";s:6:\"hidden\";s:9:\"task_name\";s:4:\"text\";s:9:\"step_name\";s:4:\"text\";s:9:\"step_desc\";s:4:\"text\";s:9:\"task_type\";s:6:\"hidden\";s:9:\"condition\";s:6:\"hidden\";s:3:\"num\";s:4:\"text\";s:3:\"exp\";s:4:\"text\";s:5:\"score\";s:4:\"text\";s:6:\"action\";s:6:\"hidden\";s:5:\"medal\";s:6:\"select\";}s:11:\"key_default\";a:11:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"step_name\";s:0:\"\";s:9:\"step_desc\";s:0:\"\";s:9:\"task_type\";s:0:\"\";s:9:\"condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:6:\"action\";s:0:\"\";s:5:\"medal\";s:0:\"\";}s:9:\"key_tishi\";a:11:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"step_name\";s:0:\"\";s:9:\"step_desc\";s:0:\"\";s:9:\"task_type\";s:0:\"\";s:9:\"condition\";s:0:\"\";s:3:\"num\";s:27:\"可修改任務裡的數值\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:6:\"action\";s:0:\"\";s:5:\"medal\";s:0:\"\";}s:14:\"key_javascript\";a:11:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"step_name\";s:0:\"\";s:9:\"step_desc\";s:0:\"\";s:9:\"task_type\";s:0:\"\";s:9:\"condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:6:\"action\";s:0:\"\";s:5:\"medal\";s:0:\"\";}}', '2013-03-13 16:08:29');
INSERT INTO `ts_system_config` VALUES ('2502', 'pageKey', 'admin_Task_reward', 'a:4:{s:3:\"key\";a:4:{s:9:\"task_name\";s:9:\"task_name\";s:6:\"reward\";s:6:\"reward\";s:5:\"medal\";s:5:\"medal\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:9:\"task_name\";s:12:\"任務類型\";s:6:\"reward\";s:12:\"積分獎勵\";s:5:\"medal\";s:12:\"勳章獎勵\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:9:\"task_name\";s:1:\"0\";s:6:\"reward\";s:1:\"0\";s:5:\"medal\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:9:\"task_name\";s:0:\"\";s:6:\"reward\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-12-25 10:32:26');
INSERT INTO `ts_system_config` VALUES ('5033', 'pageKey', 'admin_Task_editReward', 'a:6:{s:3:\"key\";a:5:{s:9:\"task_type\";s:9:\"task_type\";s:10:\"task_level\";s:10:\"task_level\";s:3:\"exp\";s:3:\"exp\";s:5:\"score\";s:5:\"score\";s:5:\"medal\";s:5:\"medal\";}s:8:\"key_name\";a:5:{s:9:\"task_type\";s:0:\"\";s:10:\"task_level\";s:0:\"\";s:3:\"exp\";s:15:\"經驗獎勵值\";s:5:\"score\";s:15:\"財富獎勵值\";s:5:\"medal\";s:12:\"勳章獎勵\";}s:8:\"key_type\";a:5:{s:9:\"task_type\";s:6:\"hidden\";s:10:\"task_level\";s:6:\"hidden\";s:3:\"exp\";s:4:\"text\";s:5:\"score\";s:4:\"text\";s:5:\"medal\";s:6:\"select\";}s:11:\"key_default\";a:5:{s:9:\"task_type\";s:0:\"\";s:10:\"task_level\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:5:\"medal\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:9:\"task_type\";s:0:\"\";s:10:\"task_level\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:5:\"medal\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:9:\"task_type\";s:0:\"\";s:10:\"task_level\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:5:\"medal\";s:0:\"\";}}', '2013-03-13 16:21:44');
INSERT INTO `ts_system_config` VALUES ('2255', 'pageKey', 'admin_Config_cloudimage', 'a:6:{s:3:\"key\";a:7:{s:16:\"cloud_image_open\";s:16:\"cloud_image_open\";s:19:\"cloud_image_api_url\";s:19:\"cloud_image_api_url\";s:18:\"cloud_image_bucket\";s:18:\"cloud_image_bucket\";s:24:\"cloud_image_form_api_key\";s:24:\"cloud_image_form_api_key\";s:23:\"cloud_image_prefix_urls\";s:23:\"cloud_image_prefix_urls\";s:17:\"cloud_image_admin\";s:17:\"cloud_image_admin\";s:20:\"cloud_image_password\";s:20:\"cloud_image_password\";}s:8:\"key_name\";a:7:{s:16:\"cloud_image_open\";s:21:\"開啟又拍雲圖片\";s:19:\"cloud_image_api_url\";s:15:\"默認API地址\";s:18:\"cloud_image_bucket\";s:12:\"圖片bucket\";s:24:\"cloud_image_form_api_key\";s:15:\"表單API金鑰\";s:23:\"cloud_image_prefix_urls\";s:12:\"域名字首\";s:17:\"cloud_image_admin\";s:15:\"操作員帳號\";s:20:\"cloud_image_password\";s:15:\"操作員密碼\";}s:8:\"key_type\";a:7:{s:16:\"cloud_image_open\";s:5:\"radio\";s:19:\"cloud_image_api_url\";s:4:\"text\";s:18:\"cloud_image_bucket\";s:4:\"text\";s:24:\"cloud_image_form_api_key\";s:4:\"text\";s:23:\"cloud_image_prefix_urls\";s:8:\"textarea\";s:17:\"cloud_image_admin\";s:4:\"text\";s:20:\"cloud_image_password\";s:8:\"password\";}s:11:\"key_default\";a:7:{s:16:\"cloud_image_open\";s:1:\"1\";s:19:\"cloud_image_api_url\";s:23:\"http://v0.api.upyun.com\";s:18:\"cloud_image_bucket\";s:0:\"\";s:24:\"cloud_image_form_api_key\";s:0:\"\";s:23:\"cloud_image_prefix_urls\";s:0:\"\";s:17:\"cloud_image_admin\";s:0:\"\";s:20:\"cloud_image_password\";s:0:\"\";}s:9:\"key_tishi\";a:7:{s:16:\"cloud_image_open\";s:0:\"\";s:19:\"cloud_image_api_url\";s:0:\"\";s:18:\"cloud_image_bucket\";s:0:\"\";s:24:\"cloud_image_form_api_key\";s:0:\"\";s:23:\"cloud_image_prefix_urls\";s:75:\"需要在又拍雲繫結過的域名，多個域名請用半形逗號分隔\";s:17:\"cloud_image_admin\";s:0:\"\";s:20:\"cloud_image_password\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:16:\"cloud_image_open\";s:0:\"\";s:19:\"cloud_image_api_url\";s:0:\"\";s:18:\"cloud_image_bucket\";s:0:\"\";s:24:\"cloud_image_form_api_key\";s:0:\"\";s:23:\"cloud_image_prefix_urls\";s:0:\"\";s:17:\"cloud_image_admin\";s:0:\"\";s:20:\"cloud_image_password\";s:0:\"\";}}', '2012-12-12 22:32:27');
INSERT INTO `ts_system_config` VALUES ('2254', 'pageKey', 'admin_Config_cloudattach', 'a:6:{s:3:\"key\";a:7:{s:17:\"cloud_attach_open\";s:17:\"cloud_attach_open\";s:20:\"cloud_attach_api_url\";s:20:\"cloud_attach_api_url\";s:19:\"cloud_attach_bucket\";s:19:\"cloud_attach_bucket\";s:25:\"cloud_attach_form_api_key\";s:25:\"cloud_attach_form_api_key\";s:24:\"cloud_attach_prefix_urls\";s:24:\"cloud_attach_prefix_urls\";s:18:\"cloud_attach_admin\";s:18:\"cloud_attach_admin\";s:21:\"cloud_attach_password\";s:21:\"cloud_attach_password\";}s:8:\"key_name\";a:7:{s:17:\"cloud_attach_open\";s:21:\"開啟又拍雲附件\";s:20:\"cloud_attach_api_url\";s:15:\"默認API地址\";s:19:\"cloud_attach_bucket\";s:12:\"圖片bucket\";s:25:\"cloud_attach_form_api_key\";s:15:\"表單API金鑰\";s:24:\"cloud_attach_prefix_urls\";s:12:\"域名字首\";s:18:\"cloud_attach_admin\";s:15:\"操作員帳號\";s:21:\"cloud_attach_password\";s:15:\"操作員密碼\";}s:8:\"key_type\";a:7:{s:17:\"cloud_attach_open\";s:5:\"radio\";s:20:\"cloud_attach_api_url\";s:4:\"text\";s:19:\"cloud_attach_bucket\";s:4:\"text\";s:25:\"cloud_attach_form_api_key\";s:4:\"text\";s:24:\"cloud_attach_prefix_urls\";s:8:\"textarea\";s:18:\"cloud_attach_admin\";s:4:\"text\";s:21:\"cloud_attach_password\";s:8:\"password\";}s:11:\"key_default\";a:7:{s:17:\"cloud_attach_open\";s:1:\"1\";s:20:\"cloud_attach_api_url\";s:23:\"http://v0.api.upyun.com\";s:19:\"cloud_attach_bucket\";s:0:\"\";s:25:\"cloud_attach_form_api_key\";s:0:\"\";s:24:\"cloud_attach_prefix_urls\";s:0:\"\";s:18:\"cloud_attach_admin\";s:0:\"\";s:21:\"cloud_attach_password\";s:0:\"\";}s:9:\"key_tishi\";a:7:{s:17:\"cloud_attach_open\";s:0:\"\";s:20:\"cloud_attach_api_url\";s:0:\"\";s:19:\"cloud_attach_bucket\";s:0:\"\";s:25:\"cloud_attach_form_api_key\";s:0:\"\";s:24:\"cloud_attach_prefix_urls\";s:75:\"需要在又拍雲繫結過的域名，多個域名請用半形逗號分隔\";s:18:\"cloud_attach_admin\";s:0:\"\";s:21:\"cloud_attach_password\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:17:\"cloud_attach_open\";s:0:\"\";s:20:\"cloud_attach_api_url\";s:0:\"\";s:19:\"cloud_attach_bucket\";s:0:\"\";s:25:\"cloud_attach_form_api_key\";s:0:\"\";s:24:\"cloud_attach_prefix_urls\";s:0:\"\";s:18:\"cloud_attach_admin\";s:0:\"\";s:21:\"cloud_attach_password\";s:0:\"\";}}', '2012-12-12 22:32:18');
INSERT INTO `ts_system_config` VALUES ('6077', 'pageKey', 'admin_Task_addTask', 'a:6:{s:3:\"key\";a:15:{s:9:\"task_name\";s:9:\"task_name\";s:9:\"task_desc\";s:9:\"task_desc\";s:9:\"end_time1\";s:9:\"end_time1\";s:9:\"userlevel\";s:9:\"userlevel\";s:9:\"usergroup\";s:9:\"usergroup\";s:9:\"reg_time1\";s:9:\"reg_time1\";s:5:\"topic\";s:5:\"topic\";s:14:\"task_condition\";s:14:\"task_condition\";s:3:\"num\";s:3:\"num\";s:3:\"exp\";s:3:\"exp\";s:5:\"score\";s:5:\"score\";s:9:\"attach_id\";s:9:\"attach_id\";s:12:\"attach_small\";s:12:\"attach_small\";s:10:\"medal_name\";s:10:\"medal_name\";s:10:\"medal_desc\";s:10:\"medal_desc\";}s:8:\"key_name\";a:15:{s:9:\"task_name\";s:12:\"任務名稱\";s:9:\"task_desc\";s:12:\"任務描述\";s:9:\"end_time1\";s:12:\"領取時間\";s:9:\"userlevel\";s:12:\"使用者等級\";s:9:\"usergroup\";s:9:\"使用者組\";s:9:\"reg_time1\";s:12:\"註冊時間\";s:5:\"topic\";s:6:\"話題\";s:14:\"task_condition\";s:12:\"任務條件\";s:3:\"num\";s:12:\"領取數量\";s:3:\"exp\";s:6:\"經驗\";s:5:\"score\";s:6:\"財富\";s:9:\"attach_id\";s:18:\"上傳勳章大圖\";s:12:\"attach_small\";s:18:\"上傳勳章小圖\";s:10:\"medal_name\";s:12:\"勳章名稱\";s:10:\"medal_desc\";s:12:\"勳章描述\";}s:8:\"key_type\";a:15:{s:9:\"task_name\";s:4:\"text\";s:9:\"task_desc\";s:8:\"textarea\";s:9:\"end_time1\";s:4:\"date\";s:9:\"userlevel\";s:8:\"checkbox\";s:9:\"usergroup\";s:8:\"checkbox\";s:9:\"reg_time1\";s:4:\"date\";s:5:\"topic\";s:4:\"text\";s:14:\"task_condition\";s:6:\"select\";s:3:\"num\";s:4:\"text\";s:3:\"exp\";s:4:\"text\";s:5:\"score\";s:4:\"text\";s:9:\"attach_id\";s:5:\"image\";s:12:\"attach_small\";s:5:\"image\";s:10:\"medal_name\";s:4:\"text\";s:10:\"medal_desc\";s:4:\"text\";}s:11:\"key_default\";a:15:{s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:9:\"attach_id\";s:0:\"\";s:12:\"attach_small\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";}s:9:\"key_tishi\";a:15:{s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:9:\"attach_id\";s:7:\"100x100\";s:12:\"attach_small\";s:5:\"30x30\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";}s:14:\"key_javascript\";a:15:{s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:5:\"score\";s:0:\"\";s:9:\"attach_id\";s:0:\"\";s:12:\"attach_small\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";}}', '2013-03-27 14:39:14');
INSERT INTO `ts_system_config` VALUES ('2499', 'pageKey', 'admin_Task_mainIndex', 'a:4:{s:3:\"key\";a:7:{s:9:\"task_name\";s:9:\"task_name\";s:9:\"step_name\";s:9:\"step_name\";s:9:\"step_desc\";s:9:\"step_desc\";s:5:\"count\";s:5:\"count\";s:6:\"reward\";s:6:\"reward\";s:5:\"medal\";s:5:\"medal\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:9:\"task_name\";s:12:\"任務類型\";s:9:\"step_name\";s:12:\"任務名稱\";s:9:\"step_desc\";s:12:\"任務描述\";s:5:\"count\";s:12:\"完成人數\";s:6:\"reward\";s:12:\"積分獎勵\";s:5:\"medal\";s:12:\"勳章獎勵\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:9:\"task_name\";s:1:\"0\";s:9:\"step_name\";s:1:\"0\";s:9:\"step_desc\";s:1:\"0\";s:5:\"count\";s:1:\"0\";s:6:\"reward\";s:1:\"0\";s:5:\"medal\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:9:\"task_name\";s:0:\"\";s:9:\"step_name\";s:0:\"\";s:9:\"step_desc\";s:0:\"\";s:5:\"count\";s:0:\"\";s:6:\"reward\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-12-25 10:26:53');
INSERT INTO `ts_system_config` VALUES ('2501', 'pageKey', 'admin_Task_customIndex', 'a:4:{s:3:\"key\";a:7:{s:9:\"task_name\";s:9:\"task_name\";s:9:\"task_desc\";s:9:\"task_desc\";s:7:\"condesc\";s:7:\"condesc\";s:5:\"count\";s:5:\"count\";s:6:\"reward\";s:6:\"reward\";s:5:\"medal\";s:5:\"medal\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:9:\"task_name\";s:12:\"任務名稱\";s:9:\"task_desc\";s:12:\"任務描述\";s:7:\"condesc\";s:6:\"條件\";s:5:\"count\";s:12:\"完成人數\";s:6:\"reward\";s:12:\"積分獎勵\";s:5:\"medal\";s:12:\"勳章獎勵\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:9:\"task_name\";s:1:\"0\";s:9:\"task_desc\";s:1:\"0\";s:7:\"condesc\";s:1:\"0\";s:5:\"count\";s:1:\"0\";s:6:\"reward\";s:1:\"0\";s:5:\"medal\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:7:\"condesc\";s:0:\"\";s:5:\"count\";s:0:\"\";s:6:\"reward\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2012-12-25 10:28:15');
INSERT INTO `ts_system_config` VALUES ('2514', 'pageKey', 'admin_Task_editCustomTask', 'a:6:{s:3:\"key\";a:15:{s:2:\"id\";s:2:\"id\";s:9:\"task_name\";s:9:\"task_name\";s:9:\"task_desc\";s:9:\"task_desc\";s:9:\"end_time1\";s:9:\"end_time1\";s:9:\"userlevel\";s:9:\"userlevel\";s:9:\"usergroup\";s:9:\"usergroup\";s:9:\"reg_time1\";s:9:\"reg_time1\";s:5:\"topic\";s:5:\"topic\";s:14:\"task_condition\";s:14:\"task_condition\";s:3:\"num\";s:3:\"num\";s:3:\"exp\";s:3:\"exp\";s:8:\"medal_id\";s:8:\"medal_id\";s:10:\"medal_name\";s:10:\"medal_name\";s:9:\"medal_src\";s:9:\"medal_src\";s:5:\"score\";s:5:\"score\";}s:8:\"key_name\";a:15:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:12:\"任務名稱\";s:9:\"task_desc\";s:12:\"任務描述\";s:9:\"end_time1\";s:12:\"領取時間\";s:9:\"userlevel\";s:12:\"使用者等級\";s:9:\"usergroup\";s:9:\"使用者組\";s:9:\"reg_time1\";s:12:\"註冊時間\";s:5:\"topic\";s:6:\"話題\";s:14:\"task_condition\";s:12:\"前置任務\";s:3:\"num\";s:12:\"任務數量\";s:3:\"exp\";s:12:\"經驗獎勵\";s:8:\"medal_id\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:9:\"medal_src\";s:0:\"\";s:5:\"score\";s:12:\"財富獎勵\";}s:8:\"key_type\";a:15:{s:2:\"id\";s:6:\"hidden\";s:9:\"task_name\";s:4:\"text\";s:9:\"task_desc\";s:8:\"textarea\";s:9:\"end_time1\";s:4:\"date\";s:9:\"userlevel\";s:8:\"checkbox\";s:9:\"usergroup\";s:8:\"checkbox\";s:9:\"reg_time1\";s:4:\"date\";s:5:\"topic\";s:4:\"text\";s:14:\"task_condition\";s:6:\"select\";s:3:\"num\";s:4:\"text\";s:3:\"exp\";s:4:\"text\";s:8:\"medal_id\";s:6:\"hidden\";s:10:\"medal_name\";s:6:\"hidden\";s:9:\"medal_src\";s:6:\"hidden\";s:5:\"score\";s:4:\"text\";}s:11:\"key_default\";a:15:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:9:\"medal_src\";s:0:\"\";s:5:\"score\";s:0:\"\";}s:9:\"key_tishi\";a:15:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:9:\"medal_src\";s:0:\"\";s:5:\"score\";s:0:\"\";}s:14:\"key_javascript\";a:15:{s:2:\"id\";s:0:\"\";s:9:\"task_name\";s:0:\"\";s:9:\"task_desc\";s:0:\"\";s:9:\"end_time1\";s:0:\"\";s:9:\"userlevel\";s:0:\"\";s:9:\"usergroup\";s:0:\"\";s:9:\"reg_time1\";s:0:\"\";s:5:\"topic\";s:0:\"\";s:14:\"task_condition\";s:0:\"\";s:3:\"num\";s:0:\"\";s:3:\"exp\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:9:\"medal_src\";s:0:\"\";s:5:\"score\";s:0:\"\";}}', '2012-12-25 13:46:06');
INSERT INTO `ts_system_config` VALUES ('5034', 'pageKey', 'admin_Medal_index', 'a:4:{s:3:\"key\";a:6:{s:2:\"id\";s:2:\"id\";s:4:\"name\";s:4:\"name\";s:4:\"desc\";s:4:\"desc\";s:3:\"src\";s:3:\"src\";s:9:\"small_src\";s:9:\"small_src\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:6:\"名稱\";s:4:\"desc\";s:6:\"描述\";s:3:\"src\";s:12:\"勳章大圖\";s:9:\"small_src\";s:12:\"勳章小圖\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:2:\"id\";s:1:\"0\";s:4:\"name\";s:1:\"0\";s:4:\"desc\";s:1:\"0\";s:3:\"src\";s:1:\"0\";s:9:\"small_src\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-13 16:29:37');
INSERT INTO `ts_system_config` VALUES ('2618', 'pageKey', 'admin_Medal_editMedal', 'a:6:{s:3:\"key\";a:5:{s:2:\"id\";s:2:\"id\";s:4:\"name\";s:4:\"name\";s:4:\"desc\";s:4:\"desc\";s:3:\"src\";s:3:\"src\";s:9:\"small_src\";s:9:\"small_src\";}s:8:\"key_name\";a:5:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:12:\"勳章名稱\";s:4:\"desc\";s:12:\"勳章描述\";s:3:\"src\";s:18:\"上傳勳章大圖\";s:9:\"small_src\";s:18:\"上傳勳章小圖\";}s:8:\"key_type\";a:5:{s:2:\"id\";s:6:\"hidden\";s:4:\"name\";s:4:\"text\";s:4:\"desc\";s:4:\"text\";s:3:\"src\";s:5:\"image\";s:9:\"small_src\";s:5:\"image\";}s:11:\"key_default\";a:5:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:2:\"id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";}}', '2013-01-09 17:09:41');
INSERT INTO `ts_system_config` VALUES ('2600', 'searchPageKey', 'S_admin_Medal_userMedal', 'a:5:{s:3:\"key\";a:2:{s:4:\"user\";s:4:\"user\";s:5:\"medal\";s:5:\"medal\";}s:8:\"key_name\";a:2:{s:4:\"user\";s:6:\"使用者\";s:5:\"medal\";s:6:\"勳章\";}s:8:\"key_type\";a:2:{s:4:\"user\";s:4:\"user\";s:5:\"medal\";s:6:\"select\";}s:9:\"key_tishi\";a:2:{s:4:\"user\";s:0:\"\";s:5:\"medal\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:4:\"user\";s:0:\"\";s:5:\"medal\";s:0:\"\";}}', '2013-01-07 11:05:47');
INSERT INTO `ts_system_config` VALUES ('2597', 'pageKey', 'admin_Medal_userMedal', 'a:4:{s:3:\"key\";a:6:{s:2:\"id\";s:2:\"id\";s:5:\"uname\";s:5:\"uname\";s:9:\"medalname\";s:9:\"medalname\";s:8:\"medalsrc\";s:8:\"medalsrc\";s:4:\"desc\";s:4:\"desc\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:2:\"id\";s:0:\"\";s:5:\"uname\";s:9:\"使用者名\";s:9:\"medalname\";s:12:\"勳章名稱\";s:8:\"medalsrc\";s:6:\"勳章\";s:4:\"desc\";s:12:\"頒發描述\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:2:\"id\";s:1:\"0\";s:5:\"uname\";s:1:\"0\";s:9:\"medalname\";s:1:\"0\";s:8:\"medalsrc\";s:1:\"0\";s:4:\"desc\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:2:\"id\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:9:\"medalname\";s:0:\"\";s:8:\"medalsrc\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-01-07 10:51:48');
INSERT INTO `ts_system_config` VALUES ('6079', 'pageKey', 'admin_Medal_addUserMedal', 'a:6:{s:3:\"key\";a:7:{s:4:\"user\";s:4:\"user\";s:5:\"medal\";s:5:\"medal\";s:9:\"attach_id\";s:9:\"attach_id\";s:12:\"attach_small\";s:12:\"attach_small\";s:10:\"medal_name\";s:10:\"medal_name\";s:10:\"medal_desc\";s:10:\"medal_desc\";s:4:\"desc\";s:4:\"desc\";}s:8:\"key_name\";a:7:{s:4:\"user\";s:9:\"使用者名\";s:5:\"medal\";s:12:\"選擇勳章\";s:9:\"attach_id\";s:18:\"上傳勳章大圖\";s:12:\"attach_small\";s:18:\"上傳勳章小圖\";s:10:\"medal_name\";s:12:\"勳章名稱\";s:10:\"medal_desc\";s:12:\"勳章描述\";s:4:\"desc\";s:12:\"頒發描述\";}s:8:\"key_type\";a:7:{s:4:\"user\";s:4:\"user\";s:5:\"medal\";s:6:\"select\";s:9:\"attach_id\";s:5:\"image\";s:12:\"attach_small\";s:4:\"text\";s:10:\"medal_name\";s:4:\"text\";s:10:\"medal_desc\";s:4:\"text\";s:4:\"desc\";s:4:\"text\";}s:11:\"key_default\";a:7:{s:4:\"user\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:9:\"attach_id\";s:0:\"\";s:12:\"attach_small\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";s:4:\"desc\";s:0:\"\";}s:9:\"key_tishi\";a:7:{s:4:\"user\";s:0:\"\";s:5:\"medal\";s:0:\"\";s:9:\"attach_id\";s:7:\"100x100\";s:12:\"attach_small\";s:5:\"30x30\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";s:4:\"desc\";s:0:\"\";}s:14:\"key_javascript\";a:7:{s:4:\"user\";s:0:\"\";s:5:\"medal\";s:26:\"admin.addmedal(this.value)\";s:9:\"attach_id\";s:0:\"\";s:12:\"attach_small\";s:0:\"\";s:10:\"medal_name\";s:0:\"\";s:10:\"medal_desc\";s:0:\"\";s:4:\"desc\";s:0:\"\";}}', '2013-03-27 14:39:14');
INSERT INTO `ts_system_config` VALUES ('6078', 'pageKey', 'admin_Medal_addMedal', 'a:6:{s:3:\"key\";a:4:{s:4:\"name\";s:4:\"name\";s:4:\"desc\";s:4:\"desc\";s:3:\"src\";s:3:\"src\";s:9:\"small_src\";s:9:\"small_src\";}s:8:\"key_name\";a:4:{s:4:\"name\";s:12:\"勳章名稱\";s:4:\"desc\";s:12:\"勳章描述\";s:3:\"src\";s:18:\"上傳勳章大圖\";s:9:\"small_src\";s:18:\"上傳勳章小圖\";}s:8:\"key_type\";a:4:{s:4:\"name\";s:4:\"text\";s:4:\"desc\";s:4:\"text\";s:3:\"src\";s:5:\"image\";s:9:\"small_src\";s:5:\"image\";}s:11:\"key_default\";a:4:{s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:7:\"100x100\";s:9:\"small_src\";s:5:\"30x30\";}s:14:\"key_javascript\";a:4:{s:4:\"name\";s:0:\"\";s:4:\"desc\";s:0:\"\";s:3:\"src\";s:0:\"\";s:9:\"small_src\";s:0:\"\";}}', '2013-03-27 14:39:14');
INSERT INTO `ts_system_config` VALUES ('5240', 'pageKey', 'weiba_Admin_weibaAdminAudit', 'a:4:{s:3:\"key\";a:7:{s:2:\"id\";s:2:\"id\";s:12:\"follower_uid\";s:12:\"follower_uid\";s:14:\"follower_uname\";s:14:\"follower_uname\";s:10:\"weiba_name\";s:10:\"weiba_name\";s:4:\"type\";s:4:\"type\";s:6:\"reason\";s:6:\"reason\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:2:\"id\";s:2:\"ID\";s:12:\"follower_uid\";s:8:\"使用者ID\";s:14:\"follower_uname\";s:6:\"昵稱\";s:10:\"weiba_name\";s:12:\"微吧名稱\";s:4:\"type\";s:12:\"申請類型\";s:6:\"reason\";s:12:\"申請原因\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:2:\"id\";s:1:\"0\";s:12:\"follower_uid\";s:1:\"0\";s:14:\"follower_uname\";s:1:\"0\";s:10:\"weiba_name\";s:1:\"0\";s:4:\"type\";s:1:\"0\";s:6:\"reason\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:2:\"id\";s:0:\"\";s:12:\"follower_uid\";s:0:\"\";s:14:\"follower_uname\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";s:4:\"type\";s:0:\"\";s:6:\"reason\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-14 12:15:07');
INSERT INTO `ts_system_config` VALUES ('2548', 'searchPageKey', 'S_admin_Config_getInviteAdminList', 'a:5:{s:3:\"key\";a:1:{s:11:\"invite_type\";s:11:\"invite_type\";}s:8:\"key_name\";a:1:{s:11:\"invite_type\";s:12:\"邀請類型\";}s:8:\"key_type\";a:1:{s:11:\"invite_type\";s:5:\"radio\";}s:9:\"key_tishi\";a:1:{s:11:\"invite_type\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:11:\"invite_type\";s:0:\"\";}}', '2012-12-27 10:02:44');
INSERT INTO `ts_system_config` VALUES ('2549', 'pageKey', 'admin_Config_getInviteAdminList', 'a:4:{s:3:\"key\";a:7:{s:4:\"face\";s:4:\"face\";s:14:\"receiver_uname\";s:14:\"receiver_uname\";s:14:\"receiver_email\";s:14:\"receiver_email\";s:5:\"ctime\";s:5:\"ctime\";s:11:\"invite_type\";s:11:\"invite_type\";s:11:\"invite_code\";s:11:\"invite_code\";s:13:\"inviter_uname\";s:13:\"inviter_uname\";}s:8:\"key_name\";a:7:{s:4:\"face\";s:18:\"被邀請人頭像\";s:14:\"receiver_uname\";s:18:\"被邀請人昵稱\";s:14:\"receiver_email\";s:24:\"被邀請人註冊郵箱\";s:5:\"ctime\";s:24:\"被邀請人註冊時間\";s:11:\"invite_type\";s:12:\"邀請類型\";s:11:\"invite_code\";s:9:\"邀請碼\";s:13:\"inviter_uname\";s:15:\"邀請人昵稱\";}s:10:\"key_hidden\";a:7:{s:4:\"face\";s:1:\"0\";s:14:\"receiver_uname\";s:1:\"0\";s:14:\"receiver_email\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:11:\"invite_type\";s:1:\"0\";s:11:\"invite_code\";s:1:\"0\";s:13:\"inviter_uname\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:4:\"face\";s:0:\"\";s:14:\"receiver_uname\";s:0:\"\";s:14:\"receiver_email\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:11:\"invite_type\";s:0:\"\";s:11:\"invite_code\";s:0:\"\";s:13:\"inviter_uname\";s:0:\"\";}}', '2012-12-27 10:04:44');
INSERT INTO `ts_system_config` VALUES ('2633', 'pageKey', 'admin_Medal_editUserMedal', 'a:6:{s:3:\"key\";a:4:{s:2:\"id\";s:2:\"id\";s:3:\"uid\";s:3:\"uid\";s:8:\"medal_id\";s:8:\"medal_id\";s:4:\"desc\";s:4:\"desc\";}s:8:\"key_name\";a:4:{s:2:\"id\";s:0:\"\";s:3:\"uid\";s:9:\"使用者名\";s:8:\"medal_id\";s:12:\"選擇勳章\";s:4:\"desc\";s:12:\"頒發描述\";}s:8:\"key_type\";a:4:{s:2:\"id\";s:6:\"hidden\";s:3:\"uid\";s:4:\"word\";s:8:\"medal_id\";s:6:\"select\";s:4:\"desc\";s:4:\"text\";}s:11:\"key_default\";a:4:{s:2:\"id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:4:\"desc\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:2:\"id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:4:\"desc\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:2:\"id\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:8:\"medal_id\";s:0:\"\";s:4:\"desc\";s:0:\"\";}}', '2013-01-09 18:05:45');
INSERT INTO `ts_system_config` VALUES ('3342', 'pageKey', 'admin_User_findPeopleConfig', 'a:6:{s:3:\"key\";a:1:{s:10:\"findPeople\";s:10:\"findPeople\";}s:8:\"key_name\";a:1:{s:10:\"findPeople\";s:12:\"全局配置\";}s:8:\"key_type\";a:1:{s:10:\"findPeople\";s:8:\"checkbox\";}s:11:\"key_default\";a:1:{s:10:\"findPeople\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:10:\"findPeople\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:10:\"findPeople\";s:0:\"\";}}', '2013-02-03 11:00:01');
INSERT INTO `ts_system_config` VALUES ('3344', 'pageKey', 'admin_User_verifyCategory', 'a:4:{s:3:\"key\";a:4:{s:25:\"user_verified_category_id\";s:25:\"user_verified_category_id\";s:5:\"title\";s:5:\"title\";s:9:\"pCategory\";s:9:\"pCategory\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:4:{s:25:\"user_verified_category_id\";s:8:\"分類ID\";s:5:\"title\";s:12:\"分類名稱\";s:9:\"pCategory\";s:18:\"所屬認證類型\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:4:{s:25:\"user_verified_category_id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:9:\"pCategory\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:4:{s:25:\"user_verified_category_id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:9:\"pCategory\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-02-03 11:00:51');
INSERT INTO `ts_system_config` VALUES ('4355', 'searchPageKey', 'S_tipoff_Admin_bonus', 'a:5:{s:3:\"key\";a:3:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";}s:8:\"key_name\";a:3:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";}s:8:\"key_type\";a:3:{s:9:\"tipoff_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";}}', '2013-03-01 17:21:07');
INSERT INTO `ts_system_config` VALUES ('5668', 'searchPageKey', 'S_weiba_Admin_weibaAdminAudit', 'a:5:{s:3:\"key\";a:2:{s:12:\"follower_uid\";s:12:\"follower_uid\";s:10:\"weiba_name\";s:10:\"weiba_name\";}s:8:\"key_name\";a:2:{s:12:\"follower_uid\";s:8:\"使用者ID\";s:10:\"weiba_name\";s:12:\"微吧名稱\";}s:8:\"key_type\";a:2:{s:12:\"follower_uid\";s:4:\"text\";s:10:\"weiba_name\";s:4:\"text\";}s:9:\"key_tishi\";a:2:{s:12:\"follower_uid\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:12:\"follower_uid\";s:0:\"\";s:10:\"weiba_name\";s:0:\"\";}}', '2013-03-15 21:40:18');
INSERT INTO `ts_system_config` VALUES ('6080', 'pageKey', 'admin_Home_cacheConfig', 'a:6:{s:3:\"key\";a:3:{s:9:\"cachetype\";s:9:\"cachetype\";s:12:\"cachesetting\";s:12:\"cachesetting\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:3:{s:9:\"cachetype\";s:12:\"快取類型\";s:12:\"cachesetting\";s:12:\"快取配置\";s:6:\"status\";s:12:\"運行狀態\";}s:8:\"key_type\";a:3:{s:9:\"cachetype\";s:5:\"radio\";s:12:\"cachesetting\";s:4:\"text\";s:6:\"status\";s:5:\"radio\";}s:11:\"key_default\";a:2:{s:9:\"cachetype\";s:0:\"\";s:12:\"cachesetting\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:9:\"cachetype\";s:0:\"\";s:12:\"cachesetting\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:9:\"cachetype\";s:0:\"\";s:12:\"cachesetting\";s:0:\"\";}}', '2013-03-27 14:39:14');
INSERT INTO `ts_system_config` VALUES ('4001', 'pageKey', 'admin_Task_taskConfig', 'a:6:{s:3:\"key\";a:1:{s:11:\"task_switch\";s:11:\"task_switch\";}s:8:\"key_name\";a:1:{s:11:\"task_switch\";s:12:\"任務狀態\";}s:8:\"key_type\";a:1:{s:11:\"task_switch\";s:5:\"radio\";}s:11:\"key_default\";a:1:{s:11:\"task_switch\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:11:\"task_switch\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:11:\"task_switch\";s:0:\"\";}}', '2013-02-25 23:14:21');
INSERT INTO `ts_system_config` VALUES ('6399', 'pageKey', 'vtask_Admin_index', 'a:6:{s:3:\"key\";a:2:{s:11:\"notify_conf\";s:11:\"notify_conf\";s:9:\"deal_uids\";s:9:\"deal_uids\";}s:8:\"key_name\";a:2:{s:11:\"notify_conf\";s:12:\"通知設定\";s:9:\"deal_uids\";s:15:\"處理人設定\";}s:8:\"key_type\";a:2:{s:11:\"notify_conf\";s:8:\"checkbox\";s:9:\"deal_uids\";s:4:\"user\";}s:11:\"key_default\";a:2:{s:11:\"notify_conf\";s:0:\"\";s:9:\"deal_uids\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:11:\"notify_conf\";s:0:\"\";s:9:\"deal_uids\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:11:\"notify_conf\";s:0:\"\";s:9:\"deal_uids\";s:0:\"\";}}', '2013-03-29 16:31:16');
INSERT INTO `ts_system_config` VALUES ('4055', 'pageKey', 'tipoff_Admin_dealUser', 'a:6:{s:3:\"key\";a:1:{s:8:\"dealUser\";s:8:\"dealUser\";}s:8:\"key_name\";a:1:{s:8:\"dealUser\";s:15:\"處理人設定\";}s:8:\"key_type\";a:1:{s:8:\"dealUser\";s:4:\"user\";}s:11:\"key_default\";a:1:{s:8:\"dealUser\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:8:\"dealUser\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:8:\"dealUser\";s:0:\"\";}}', '2013-02-27 00:15:50');
INSERT INTO `ts_system_config` VALUES ('4356', 'searchPageKey', 'S_tipoff_Admin_recycle', 'a:5:{s:3:\"key\";a:4:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"tipoff_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:22:19');
INSERT INTO `ts_system_config` VALUES ('4357', 'searchPageKey', 'S_tipoff_Admin_archive', 'a:5:{s:3:\"key\";a:4:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"tipoff_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:23:03');
INSERT INTO `ts_system_config` VALUES ('4354', 'searchPageKey', 'S_tipoff_Admin_open', 'a:5:{s:3:\"key\";a:4:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"tipoff_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:20:10');
INSERT INTO `ts_system_config` VALUES ('4351', 'pageKey', 'tipoff_Admin_status', 'a:4:{s:3:\"key\";a:3:{s:6:\"status\";s:6:\"status\";s:5:\"title\";s:5:\"title\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:3:{s:6:\"status\";s:6:\"狀態\";s:5:\"title\";s:6:\"名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:3:{s:6:\"status\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:3:{s:6:\"status\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:23:53');
INSERT INTO `ts_system_config` VALUES ('4352', 'pageKey', 'tipoff_Admin_tipoffSet', 'a:6:{s:3:\"key\";a:1:{s:11:\"tipoff_nums\";s:11:\"tipoff_nums\";}s:8:\"key_name\";a:1:{s:11:\"tipoff_nums\";s:12:\"微博字數\";}s:8:\"key_type\";a:1:{s:11:\"tipoff_nums\";s:4:\"text\";}s:11:\"key_default\";a:1:{s:11:\"tipoff_nums\";s:1:\"0\";}s:9:\"key_tishi\";a:1:{s:11:\"tipoff_nums\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:11:\"tipoff_nums\";s:0:\"\";}}', '2013-03-04 11:06:22');
INSERT INTO `ts_system_config` VALUES ('4353', 'searchPageKey', 'S_tipoff_Admin_index', 'a:5:{s:3:\"key\";a:4:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"tipoff_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:20:52');
INSERT INTO `ts_system_config` VALUES ('4349', 'pageKey', 'tipoff_Admin_archive', 'a:4:{s:3:\"key\";a:8:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"tipoff_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:23:31');
INSERT INTO `ts_system_config` VALUES ('4350', 'pageKey', 'tipoff_Admin_anonyuser', 'a:6:{s:3:\"key\";a:1:{s:9:\"anonyuser\";s:9:\"anonyuser\";}s:8:\"key_name\";a:1:{s:9:\"anonyuser\";s:12:\"匿名者UID\";}s:8:\"key_type\";a:1:{s:9:\"anonyuser\";s:7:\"oneUser\";}s:11:\"key_default\";a:1:{s:9:\"anonyuser\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:9:\"anonyuser\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:9:\"anonyuser\";s:0:\"\";}}', '2013-03-01 17:24:15');
INSERT INTO `ts_system_config` VALUES ('4348', 'pageKey', 'tipoff_Admin_recycle', 'a:4:{s:3:\"key\";a:8:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"tipoff_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:22:45');
INSERT INTO `ts_system_config` VALUES ('4345', 'pageKey', 'tipoff_Admin_index', 'a:4:{s:3:\"key\";a:8:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"tipoff_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:19:23');
INSERT INTO `ts_system_config` VALUES ('4346', 'pageKey', 'tipoff_Admin_open', 'a:4:{s:3:\"key\";a:8:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"tipoff_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:20:42');
INSERT INTO `ts_system_config` VALUES ('4347', 'pageKey', 'tipoff_Admin_bonus', 'a:4:{s:3:\"key\";a:10:{s:9:\"tipoff_id\";s:9:\"tipoff_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:5:\"bonus\";s:5:\"bonus\";s:8:\"is_bonus\";s:8:\"is_bonus\";s:9:\"bonus_uid\";s:9:\"bonus_uid\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:9:\"tipoff_id\";s:8:\"爆料ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:5:\"bonus\";s:6:\"獎金\";s:8:\"is_bonus\";s:12:\"是否發放\";s:9:\"bonus_uid\";s:9:\"發放者\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:9:\"tipoff_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:5:\"bonus\";s:1:\"0\";s:8:\"is_bonus\";s:1:\"0\";s:9:\"bonus_uid\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:9:\"tipoff_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:5:\"bonus\";s:0:\"\";s:8:\"is_bonus\";s:0:\"\";s:9:\"bonus_uid\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:21:49');
INSERT INTO `ts_system_config` VALUES ('4302', 'pageKey', 'admin_Content_template', 'a:4:{s:3:\"key\";a:11:{s:6:\"tpl_id\";s:6:\"tpl_id\";s:4:\"name\";s:4:\"name\";s:5:\"alias\";s:5:\"alias\";s:5:\"title\";s:5:\"title\";s:4:\"body\";s:4:\"body\";s:4:\"lang\";s:4:\"lang\";s:4:\"type\";s:4:\"type\";s:5:\"type2\";s:5:\"type2\";s:8:\"is_cache\";s:8:\"is_cache\";s:5:\"ctime\";s:5:\"ctime\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:11:{s:6:\"tpl_id\";s:8:\"模板ID\";s:4:\"name\";s:6:\"名稱\";s:5:\"alias\";s:6:\"別名\";s:5:\"title\";s:12:\"標題模板\";s:4:\"body\";s:12:\"內容模板\";s:4:\"lang\";s:6:\"語言\";s:4:\"type\";s:12:\"模板類型\";s:5:\"type2\";s:13:\"模板類型2\";s:8:\"is_cache\";s:18:\"是否默認快取\";s:5:\"ctime\";s:12:\"創建時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:11:{s:6:\"tpl_id\";s:1:\"0\";s:4:\"name\";s:1:\"0\";s:5:\"alias\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:4:\"body\";s:1:\"0\";s:4:\"lang\";s:1:\"0\";s:4:\"type\";s:1:\"0\";s:5:\"type2\";s:1:\"0\";s:8:\"is_cache\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:11:{s:6:\"tpl_id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"alias\";s:0:\"\";s:5:\"title\";s:0:\"\";s:4:\"body\";s:0:\"\";s:4:\"lang\";s:0:\"\";s:4:\"type\";s:0:\"\";s:5:\"type2\";s:0:\"\";s:8:\"is_cache\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-02 13:54:10');
INSERT INTO `ts_system_config` VALUES ('4303', 'pageKey', 'admin_Content_upTemplate', 'a:6:{s:3:\"key\";a:9:{s:6:\"tpl_id\";s:6:\"tpl_id\";s:4:\"name\";s:4:\"name\";s:5:\"alias\";s:5:\"alias\";s:5:\"title\";s:5:\"title\";s:4:\"body\";s:4:\"body\";s:4:\"lang\";s:4:\"lang\";s:4:\"type\";s:4:\"type\";s:5:\"type2\";s:5:\"type2\";s:8:\"is_cache\";s:8:\"is_cache\";}s:8:\"key_name\";a:9:{s:6:\"tpl_id\";s:8:\"模板ID\";s:4:\"name\";s:6:\"名稱\";s:5:\"alias\";s:6:\"別名\";s:5:\"title\";s:12:\"標題模板\";s:4:\"body\";s:12:\"內容模板\";s:4:\"lang\";s:6:\"語言\";s:4:\"type\";s:12:\"模板類型\";s:5:\"type2\";s:13:\"模板類型2\";s:8:\"is_cache\";s:18:\"是否默認快取\";}s:8:\"key_type\";a:9:{s:6:\"tpl_id\";s:6:\"hidden\";s:4:\"name\";s:4:\"text\";s:5:\"alias\";s:4:\"text\";s:5:\"title\";s:4:\"text\";s:4:\"body\";s:8:\"textarea\";s:4:\"lang\";s:4:\"text\";s:4:\"type\";s:4:\"text\";s:5:\"type2\";s:4:\"text\";s:8:\"is_cache\";s:5:\"radio\";}s:11:\"key_default\";a:9:{s:6:\"tpl_id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"alias\";s:0:\"\";s:5:\"title\";s:0:\"\";s:4:\"body\";s:0:\"\";s:4:\"lang\";s:0:\"\";s:4:\"type\";s:0:\"\";s:5:\"type2\";s:0:\"\";s:8:\"is_cache\";s:0:\"\";}s:9:\"key_tishi\";a:9:{s:6:\"tpl_id\";s:0:\"\";s:4:\"name\";s:105:\"使用英文，保證唯一性。建議：“類型_動作”，如“blog_add”或“credit_blog_add”\";s:5:\"alias\";s:36:\"簡單達意，如“發表部落格”\";s:5:\"title\";s:71:\"使用“{”和“}”包含變數名，如“{actor}做了{action}”\";s:4:\"body\";s:104:\"使用“{”和“}”包含變數名，如“{actor}做了{action}增加了{volume}個{credit_type}”\";s:4:\"lang\";s:52:\"與全局語言包一致，如“en”、“zh”等\";s:4:\"type\";s:31:\"如“blog”、“credit”等\";s:5:\"type2\";s:50:\"備用類型，可留空。如“credit_blog”等\";s:8:\"is_cache\";s:39:\"是否使用默認的模板快取系統\";}s:14:\"key_javascript\";a:9:{s:6:\"tpl_id\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"alias\";s:0:\"\";s:5:\"title\";s:0:\"\";s:4:\"body\";s:0:\"\";s:4:\"lang\";s:0:\"\";s:4:\"type\";s:0:\"\";s:5:\"type2\";s:0:\"\";s:8:\"is_cache\";s:0:\"\";}}', '2013-03-02 14:34:11');
INSERT INTO `ts_system_config` VALUES ('5038', 'pageKey', 'category_conf_channel_category', 'a:6:{s:3:\"key\";a:4:{s:6:\"attach\";s:6:\"attach\";s:9:\"show_type\";s:9:\"show_type\";s:9:\"user_bind\";s:9:\"user_bind\";s:10:\"topic_bind\";s:10:\"topic_bind\";}s:8:\"key_name\";a:4:{s:6:\"attach\";s:12:\"分類圖片\";s:9:\"show_type\";s:18:\"默認展示方式\";s:9:\"user_bind\";s:12:\"使用者繫結\";s:10:\"topic_bind\";s:12:\"話題繫結\";}s:8:\"key_type\";a:4:{s:6:\"attach\";s:5:\"image\";s:9:\"show_type\";s:5:\"radio\";s:9:\"user_bind\";s:4:\"user\";s:10:\"topic_bind\";s:10:\"stringText\";}s:11:\"key_default\";a:4:{s:6:\"attach\";s:0:\"\";s:9:\"show_type\";s:0:\"\";s:9:\"user_bind\";s:0:\"\";s:10:\"topic_bind\";s:0:\"\";}s:9:\"key_tishi\";a:4:{s:6:\"attach\";s:0:\"\";s:9:\"show_type\";s:0:\"\";s:9:\"user_bind\";s:0:\"\";s:10:\"topic_bind\";s:42:\"不需輸入＃號，如：倫敦奧運會\";}s:14:\"key_javascript\";a:4:{s:6:\"attach\";s:0:\"\";s:9:\"show_type\";s:0:\"\";s:9:\"user_bind\";s:0:\"\";s:10:\"topic_bind\";s:0:\"\";}}', '2013-03-13 18:08:09');
INSERT INTO `ts_system_config` VALUES ('4717', 'pageKey', 'admin_Global_setCreditLevel', 'a:6:{s:3:\"key\";a:5:{s:5:\"level\";s:5:\"level\";s:4:\"name\";s:4:\"name\";s:5:\"image\";s:5:\"image\";s:5:\"start\";s:5:\"start\";s:3:\"end\";s:3:\"end\";}s:8:\"key_name\";a:5:{s:5:\"level\";s:6:\"等級\";s:4:\"name\";s:12:\"等級名稱\";s:5:\"image\";s:12:\"等級圖示\";s:5:\"start\";s:15:\"積分開始值\";s:3:\"end\";s:15:\"積分結束值\";}s:8:\"key_type\";a:5:{s:5:\"level\";s:4:\"word\";s:4:\"name\";s:4:\"text\";s:5:\"image\";s:5:\"image\";s:5:\"start\";s:4:\"text\";s:3:\"end\";s:4:\"text\";}s:11:\"key_default\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:5:\"level\";s:0:\"\";s:4:\"name\";s:0:\"\";s:5:\"image\";s:0:\"\";s:5:\"start\";s:0:\"\";s:3:\"end\";s:0:\"\";}}', '2013-03-11 15:26:24');
INSERT INTO `ts_system_config` VALUES ('4377', 'pageKey', 'admin_Config_setSeo', 'a:4:{s:3:\"key\";a:5:{s:4:\"name\";s:4:\"name\";s:5:\"title\";s:5:\"title\";s:8:\"keywords\";s:8:\"keywords\";s:3:\"des\";s:3:\"des\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:5:{s:4:\"name\";s:12:\"頁面名稱\";s:5:\"title\";s:6:\"標題\";s:8:\"keywords\";s:9:\"關鍵字\";s:3:\"des\";s:12:\"頁面描述\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:4:\"name\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:8:\"keywords\";s:1:\"0\";s:3:\"des\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:4:\"name\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"keywords\";s:0:\"\";s:3:\"des\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-06 11:35:03');
INSERT INTO `ts_system_config` VALUES ('6000', 'pageKey', 'vtask_Admin_open', 'a:4:{s:3:\"key\";a:8:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"vtask_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:20:42');
INSERT INTO `ts_system_config` VALUES ('6001', 'pageKey', 'vtask_Admin_bonus', 'a:4:{s:3:\"key\";a:10:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:5:\"bonus\";s:5:\"bonus\";s:8:\"is_bonus\";s:8:\"is_bonus\";s:9:\"bonus_uid\";s:9:\"bonus_uid\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:10:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:5:\"bonus\";s:6:\"獎金\";s:8:\"is_bonus\";s:12:\"是否發放\";s:9:\"bonus_uid\";s:9:\"發放者\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:10:{s:9:\"vtask_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:5:\"bonus\";s:1:\"0\";s:8:\"is_bonus\";s:1:\"0\";s:9:\"bonus_uid\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:10:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:5:\"bonus\";s:0:\"\";s:8:\"is_bonus\";s:0:\"\";s:9:\"bonus_uid\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:21:49');
INSERT INTO `ts_system_config` VALUES ('6002', 'pageKey', 'vtask_Admin_recycle', 'a:4:{s:3:\"key\";a:8:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"vtask_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:22:45');
INSERT INTO `ts_system_config` VALUES ('6003', 'pageKey', 'vtask_Admin_archive', 'a:4:{s:3:\"key\";a:8:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:4:\"open\";s:4:\"open\";s:3:\"uid\";s:3:\"uid\";s:11:\"create_time\";s:11:\"create_time\";s:12:\"publish_time\";s:12:\"publish_time\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:8:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"狀態\";s:4:\"open\";s:12:\"是否公開\";s:3:\"uid\";s:9:\"釋出者\";s:11:\"create_time\";s:12:\"創建時間\";s:12:\"publish_time\";s:12:\"公開時間\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:8:{s:9:\"vtask_id\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:4:\"open\";s:1:\"0\";s:3:\"uid\";s:1:\"0\";s:11:\"create_time\";s:1:\"0\";s:12:\"publish_time\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:8:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:4:\"open\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:11:\"create_time\";s:0:\"\";s:12:\"publish_time\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:23:31');
INSERT INTO `ts_system_config` VALUES ('5919', 'pageKey', 'channel_Admin_unauditList', 'a:4:{s:3:\"key\";a:6:{s:2:\"id\";s:2:\"id\";s:5:\"uname\";s:5:\"uname\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:8:\"category\";s:8:\"category\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:2:\"id\";s:2:\"ID\";s:5:\"uname\";s:6:\"昵稱\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"稽覈\";s:8:\"category\";s:12:\"分類名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:2:\"id\";s:1:\"1\";s:5:\"uname\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:2:\"id\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"category\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-19 12:39:07');
INSERT INTO `ts_system_config` VALUES ('5920', 'pageKey', 'channel_Admin_auditList', 'a:4:{s:3:\"key\";a:6:{s:2:\"id\";s:2:\"id\";s:5:\"uname\";s:5:\"uname\";s:7:\"content\";s:7:\"content\";s:6:\"status\";s:6:\"status\";s:8:\"category\";s:8:\"category\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:6:{s:2:\"id\";s:2:\"ID\";s:5:\"uname\";s:6:\"昵稱\";s:7:\"content\";s:6:\"內容\";s:6:\"status\";s:6:\"稽覈\";s:8:\"category\";s:12:\"分類名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:6:{s:2:\"id\";s:1:\"1\";s:5:\"uname\";s:1:\"0\";s:7:\"content\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"category\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:6:{s:2:\"id\";s:0:\"\";s:5:\"uname\";s:0:\"\";s:7:\"content\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"category\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-19 12:39:45');
INSERT INTO `ts_system_config` VALUES ('6004', 'pageKey', 'vtask_Admin_anonyuser', 'a:6:{s:3:\"key\";a:1:{s:9:\"anonyuser\";s:9:\"anonyuser\";}s:8:\"key_name\";a:1:{s:9:\"anonyuser\";s:12:\"匿名者UID\";}s:8:\"key_type\";a:1:{s:9:\"anonyuser\";s:7:\"oneUser\";}s:11:\"key_default\";a:1:{s:9:\"anonyuser\";s:0:\"\";}s:9:\"key_tishi\";a:1:{s:9:\"anonyuser\";s:0:\"\";}s:14:\"key_javascript\";a:1:{s:9:\"anonyuser\";s:0:\"\";}}', '2013-03-01 17:24:15');
INSERT INTO `ts_system_config` VALUES ('6005', 'pageKey', 'vtask_Admin_status', 'a:4:{s:3:\"key\";a:3:{s:6:\"status\";s:6:\"status\";s:5:\"title\";s:5:\"title\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:3:{s:6:\"status\";s:6:\"狀態\";s:5:\"title\";s:6:\"名稱\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:3:{s:6:\"status\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:3:{s:6:\"status\";s:0:\"\";s:5:\"title\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-01 17:23:53');
INSERT INTO `ts_system_config` VALUES ('6006', 'pageKey', 'vtask_Admin_vtaskSet', 'a:6:{s:3:\"key\";a:3:{s:11:\"vtask_nums\";s:11:\"vtask_nums\";s:16:\"vtask_auto_open\";s:16:\"vtask_auto_open\";s:9:\"time_pass\";s:9:\"time_pass\";}s:8:\"key_name\";a:3:{s:11:\"vtask_nums\";s:12:\"微博字數\";s:16:\"vtask_auto_open\";s:21:\"使用者觸發時間段\";s:9:\"time_pass\";s:12:\"公開時間\";}s:8:\"key_type\";a:3:{s:11:\"vtask_nums\";s:4:\"text\";s:16:\"vtask_auto_open\";s:4:\"text\";s:9:\"time_pass\";s:4:\"text\";}s:11:\"key_default\";a:3:{s:11:\"vtask_nums\";s:1:\"0\";s:16:\"vtask_auto_open\";s:0:\"\";s:9:\"time_pass\";s:0:\"\";}s:9:\"key_tishi\";a:3:{s:11:\"vtask_nums\";s:0:\"\";s:16:\"vtask_auto_open\";s:50:\"如6:00-18:00，多個時間段之間用,號隔開\";s:9:\"time_pass\";s:39:\"幾個小時後使用者觸發自動公開\";}s:14:\"key_javascript\";a:3:{s:11:\"vtask_nums\";s:0:\"\";s:16:\"vtask_auto_open\";s:0:\"\";s:9:\"time_pass\";s:0:\"\";}}', '2013-03-15 14:29:44');
INSERT INTO `ts_system_config` VALUES ('6007', 'pageKey', 'vtask_Admin_vtaskSource', 'a:4:{s:3:\"key\";a:9:{s:2:\"id\";s:2:\"id\";s:5:\"title\";s:5:\"title\";s:10:\"source_url\";s:10:\"source_url\";s:10:\"source_key\";s:10:\"source_key\";s:9:\"source_ip\";s:9:\"source_ip\";s:5:\"ctime\";s:5:\"ctime\";s:5:\"ftime\";s:5:\"ftime\";s:6:\"status\";s:6:\"status\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:9:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:12:\"來源名稱\";s:10:\"source_url\";s:12:\"來源網址\";s:10:\"source_key\";s:6:\"密匙\";s:9:\"source_ip\";s:8:\"來源IP\";s:5:\"ctime\";s:12:\"創建時間\";s:5:\"ftime\";s:12:\"失效時間\";s:6:\"status\";s:12:\"是否有效\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:9:{s:2:\"id\";s:1:\"0\";s:5:\"title\";s:1:\"0\";s:10:\"source_url\";s:1:\"0\";s:10:\"source_key\";s:1:\"0\";s:9:\"source_ip\";s:1:\"0\";s:5:\"ctime\";s:1:\"0\";s:5:\"ftime\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:9:{s:2:\"id\";s:0:\"\";s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ctime\";s:0:\"\";s:5:\"ftime\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-03-12 16:17:41');
INSERT INTO `ts_system_config` VALUES ('6008', 'pageKey', 'vtask_Admin_addSource', 'a:6:{s:3:\"key\";a:5:{s:5:\"title\";s:5:\"title\";s:10:\"source_url\";s:10:\"source_url\";s:10:\"source_key\";s:10:\"source_key\";s:9:\"source_ip\";s:9:\"source_ip\";s:5:\"ftime\";s:5:\"ftime\";}s:8:\"key_name\";a:5:{s:5:\"title\";s:12:\"來源名稱\";s:10:\"source_url\";s:12:\"來源網址\";s:10:\"source_key\";s:6:\"密匙\";s:9:\"source_ip\";s:8:\"來源IP\";s:5:\"ftime\";s:12:\"失效時間\";}s:8:\"key_type\";a:5:{s:5:\"title\";s:4:\"text\";s:10:\"source_url\";s:4:\"text\";s:10:\"source_key\";s:4:\"text\";s:9:\"source_ip\";s:4:\"text\";s:5:\"ftime\";s:4:\"date\";}s:11:\"key_default\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:39:\"留空則根據網站規則自動生成\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}}', '2013-03-13 11:47:56');
INSERT INTO `ts_system_config` VALUES ('6009', 'pageKey', 'vtask_Admin_editSource', 'a:6:{s:3:\"key\";a:5:{s:5:\"title\";s:5:\"title\";s:10:\"source_url\";s:10:\"source_url\";s:10:\"source_key\";s:10:\"source_key\";s:9:\"source_ip\";s:9:\"source_ip\";s:5:\"ftime\";s:5:\"ftime\";}s:8:\"key_name\";a:5:{s:5:\"title\";s:12:\"來源名稱\";s:10:\"source_url\";s:12:\"來源網址\";s:10:\"source_key\";s:6:\"密匙\";s:9:\"source_ip\";s:8:\"來源IP\";s:5:\"ftime\";s:12:\"失效時間\";}s:8:\"key_type\";a:5:{s:5:\"title\";s:4:\"text\";s:10:\"source_url\";s:4:\"text\";s:10:\"source_key\";s:4:\"text\";s:9:\"source_ip\";s:4:\"text\";s:5:\"ftime\";s:4:\"date\";}s:11:\"key_default\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}s:9:\"key_tishi\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}s:14:\"key_javascript\";a:5:{s:5:\"title\";s:0:\"\";s:10:\"source_url\";s:0:\"\";s:10:\"source_key\";s:0:\"\";s:9:\"source_ip\";s:0:\"\";s:5:\"ftime\";s:0:\"\";}}', '2013-03-13 11:53:10');
INSERT INTO `ts_system_config` VALUES ('6010', 'searchPageKey', 'S_vtask_Admin_index', 'a:5:{s:3:\"key\";a:4:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"vtask_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:20:52');
INSERT INTO `ts_system_config` VALUES ('6011', 'searchPageKey', 'S_vtask_Admin_open', 'a:5:{s:3:\"key\";a:4:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"vtask_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:20:10');
INSERT INTO `ts_system_config` VALUES ('6012', 'searchPageKey', 'S_vtask_Admin_bonus', 'a:5:{s:3:\"key\";a:3:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";}s:8:\"key_name\";a:3:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";}s:8:\"key_type\";a:3:{s:9:\"vtask_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";}s:9:\"key_tishi\";a:3:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";}s:14:\"key_javascript\";a:3:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";}}', '2013-03-01 17:21:07');
INSERT INTO `ts_system_config` VALUES ('6013', 'searchPageKey', 'S_vtask_Admin_recycle', 'a:5:{s:3:\"key\";a:4:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"vtask_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:22:19');
INSERT INTO `ts_system_config` VALUES ('6014', 'searchPageKey', 'S_vtask_Admin_archive', 'a:5:{s:3:\"key\";a:4:{s:9:\"vtask_id\";s:9:\"vtask_id\";s:7:\"content\";s:7:\"content\";s:3:\"uid\";s:3:\"uid\";s:6:\"status\";s:6:\"status\";}s:8:\"key_name\";a:4:{s:9:\"vtask_id\";s:8:\"微事務ID\";s:7:\"content\";s:6:\"內容\";s:3:\"uid\";s:9:\"釋出者\";s:6:\"status\";s:6:\"狀態\";}s:8:\"key_type\";a:4:{s:9:\"vtask_id\";s:4:\"text\";s:7:\"content\";s:4:\"text\";s:3:\"uid\";s:4:\"text\";s:6:\"status\";s:6:\"select\";}s:9:\"key_tishi\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}s:14:\"key_javascript\";a:4:{s:9:\"vtask_id\";s:0:\"\";s:7:\"content\";s:0:\"\";s:3:\"uid\";s:0:\"\";s:6:\"status\";s:0:\"\";}}', '2013-03-01 17:23:03');
INSERT INTO `ts_system_config` VALUES ('6397', 'pageKey', 'category_conf_vtask_status', 'a:6:{s:3:\"key\";a:2:{s:5:\"color\";s:5:\"color\";s:4:\"font\";s:4:\"font\";}s:8:\"key_name\";a:2:{s:5:\"color\";s:0:\"\";s:4:\"font\";s:0:\"\";}s:8:\"key_type\";a:2:{s:5:\"color\";s:5:\"color\";s:4:\"font\";s:5:\"color\";}s:11:\"key_default\";a:2:{s:5:\"color\";s:0:\"\";s:4:\"font\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:5:\"color\";s:0:\"\";s:4:\"font\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:5:\"color\";s:0:\"\";s:4:\"font\";s:0:\"\";}}', '2013-03-29 16:02:16');
INSERT INTO `ts_system_config` VALUES ('6507', 'pageKey', 'admin_Config_footNav', 'a:4:{s:3:\"key\";a:12:{s:7:\"navi_id\";s:7:\"navi_id\";s:9:\"navi_name\";s:9:\"navi_name\";s:8:\"app_name\";s:8:\"app_name\";s:3:\"url\";s:3:\"url\";s:6:\"target\";s:6:\"target\";s:6:\"status\";s:6:\"status\";s:8:\"position\";s:8:\"position\";s:5:\"guest\";s:5:\"guest\";s:11:\"is_app_navi\";s:11:\"is_app_navi\";s:9:\"parent_id\";s:9:\"parent_id\";s:10:\"order_sort\";s:10:\"order_sort\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:12:{s:7:\"navi_id\";s:8:\"導航ID\";s:9:\"navi_name\";s:12:\"導航名稱\";s:8:\"app_name\";s:12:\"英文名稱\";s:3:\"url\";s:12:\"連結地址\";s:6:\"target\";s:12:\"開啟方式\";s:6:\"status\";s:6:\"狀態\";s:8:\"position\";s:12:\"導航位置\";s:5:\"guest\";s:12:\"遊客可見\";s:11:\"is_app_navi\";s:18:\"應用內容導航\";s:9:\"parent_id\";s:9:\"父導航\";s:10:\"order_sort\";s:12:\"應用排序\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:12:{s:7:\"navi_id\";s:1:\"0\";s:9:\"navi_name\";s:1:\"0\";s:8:\"app_name\";s:1:\"0\";s:3:\"url\";s:1:\"0\";s:6:\"target\";s:1:\"0\";s:6:\"status\";s:1:\"0\";s:8:\"position\";s:1:\"0\";s:5:\"guest\";s:1:\"1\";s:11:\"is_app_navi\";s:1:\"1\";s:9:\"parent_id\";s:1:\"1\";s:10:\"order_sort\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:12:{s:7:\"navi_id\";s:0:\"\";s:9:\"navi_name\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:3:\"url\";s:0:\"\";s:6:\"target\";s:0:\"\";s:6:\"status\";s:0:\"\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:0:\"\";s:11:\"is_app_navi\";s:0:\"\";s:9:\"parent_id\";s:0:\"\";s:10:\"order_sort\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-04-10 15:40:51');
INSERT INTO `ts_system_config` VALUES ('6508', 'pageKey', 'admin_Config_compareLangFile', 'a:4:{s:3:\"key\";a:7:{s:3:\"key\";s:3:\"key\";s:7:\"appname\";s:7:\"appname\";s:8:\"filetype\";s:8:\"filetype\";s:5:\"zh-cn\";s:5:\"zh-cn\";s:2:\"en\";s:2:\"en\";s:5:\"zh-tw\";s:5:\"zh-tw\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:7:{s:3:\"key\";s:9:\"語言KEY\";s:7:\"appname\";s:12:\"應用名稱\";s:8:\"filetype\";s:12:\"檔案類型\";s:5:\"zh-cn\";s:6:\"簡體\";s:2:\"en\";s:6:\"英文\";s:5:\"zh-tw\";s:6:\"繁體\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:7:{s:3:\"key\";s:1:\"0\";s:7:\"appname\";s:1:\"0\";s:8:\"filetype\";s:1:\"0\";s:5:\"zh-cn\";s:1:\"0\";s:2:\"en\";s:1:\"0\";s:5:\"zh-tw\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:7:{s:3:\"key\";s:0:\"\";s:7:\"appname\";s:0:\"\";s:8:\"filetype\";s:0:\"\";s:5:\"zh-cn\";s:0:\"\";s:2:\"en\";s:0:\"\";s:5:\"zh-tw\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-04-10 15:49:13');
INSERT INTO `ts_system_config` VALUES ('6509', 'pageKey', 'admin_Config_updateAdminTab', 'a:4:{s:3:\"key\";a:2:{s:3:\"key\";s:3:\"key\";s:8:\"DOACTION\";s:8:\"DOACTION\";}s:8:\"key_name\";a:2:{s:3:\"key\";s:9:\"頁面KEY\";s:8:\"DOACTION\";s:6:\"操作\";}s:10:\"key_hidden\";a:2:{s:3:\"key\";s:1:\"0\";s:8:\"DOACTION\";s:1:\"0\";}s:14:\"key_javascript\";a:2:{s:3:\"key\";s:0:\"\";s:8:\"DOACTION\";s:0:\"\";}}', '2013-04-10 15:50:27');
INSERT INTO `ts_system_config` VALUES ('6510', 'pageKey', 'admin_Apps_onLineApp', 'a:4:{s:3:\"key\";a:5:{s:5:\"title\";s:5:\"title\";s:4:\"type\";s:4:\"type\";s:14:\"download_count\";s:14:\"download_count\";s:4:\"user\";s:4:\"user\";s:6:\"option\";s:6:\"option\";}s:8:\"key_name\";a:5:{s:5:\"title\";s:9:\"應用名\";s:4:\"type\";s:6:\"類型\";s:14:\"download_count\";s:12:\"下載次數\";s:4:\"user\";s:9:\"開發者\";s:6:\"option\";s:6:\"操作\";}s:10:\"key_hidden\";a:5:{s:5:\"title\";s:1:\"0\";s:4:\"type\";s:1:\"0\";s:14:\"download_count\";s:1:\"0\";s:4:\"user\";s:1:\"0\";s:6:\"option\";s:1:\"0\";}s:14:\"key_javascript\";a:5:{s:5:\"title\";s:0:\"\";s:4:\"type\";s:0:\"\";s:14:\"download_count\";s:0:\"\";s:4:\"user\";s:0:\"\";s:6:\"option\";s:0:\"\";}}', '2013-04-10 15:52:16');
INSERT INTO `ts_system_config` VALUES ('6515', 'pageKey', 'admin_Config_setUcenter', 'a:6:{s:3:\"key\";a:2:{s:12:\"ucenter_open\";s:12:\"ucenter_open\";s:14:\"ucenter_config\";s:14:\"ucenter_config\";}s:8:\"key_name\";a:2:{s:12:\"ucenter_open\";s:20:\"Ucenter 同步開關\";s:14:\"ucenter_config\";s:20:\"Ucenter 配置資訊\";}s:8:\"key_type\";a:2:{s:12:\"ucenter_open\";s:5:\"radio\";s:14:\"ucenter_config\";s:8:\"textarea\";}s:11:\"key_default\";a:2:{s:12:\"ucenter_open\";s:0:\"\";s:14:\"ucenter_config\";s:0:\"\";}s:9:\"key_tishi\";a:2:{s:12:\"ucenter_open\";s:0:\"\";s:14:\"ucenter_config\";s:0:\"\";}s:14:\"key_javascript\";a:2:{s:12:\"ucenter_open\";s:0:\"\";s:14:\"ucenter_config\";s:0:\"\";}}', '2013-05-04 19:01:19');

-- ----------------------------
-- Table structure for `ts_lang`
-- ----------------------------
DROP TABLE IF EXISTS `ts_lang`;
CREATE TABLE `ts_lang` (
  `lang_id` int(10) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT 'key',
  `appname` varchar(50) NOT NULL COMMENT '所屬應用名稱',
  `filetype` tinyint(2) DEFAULT '0' COMMENT '針對的檔案類型，0:php,1:js',
  `zh-cn` text NOT NULL COMMENT '中文',
  `en` text NOT NULL COMMENT '英文',
  `zh-tw` text NOT NULL COMMENT '繁體',
  PRIMARY KEY (`lang_id`),
  UNIQUE KEY `app` (`appname`,`key`,`filetype`)
) ENGINE=MyISAM AUTO_INCREMENT=1810 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `ts_area`
-- ----------------------------
DROP TABLE IF EXISTS `ts_area`;
CREATE TABLE `ts_area` (
  `area_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '地區id',
  `title` varchar(255) NOT NULL COMMENT '地區名稱',
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父級地區ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`area_id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=910011 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_area
-- ----------------------------
INSERT INTO `ts_area` VALUES ('110000', '北京市', '0', '1');
INSERT INTO `ts_area` VALUES ('120000', '天津市', '0', '2');
INSERT INTO `ts_area` VALUES ('130000', '河北省', '0', '3');
INSERT INTO `ts_area` VALUES ('140000', '山西省', '0', '4');
INSERT INTO `ts_area` VALUES ('150000', '內蒙古', '0', '5');
INSERT INTO `ts_area` VALUES ('210000', '遼寧省', '0', '6');
INSERT INTO `ts_area` VALUES ('220000', '吉林省', '0', '7');
INSERT INTO `ts_area` VALUES ('230000', '黑龍江', '0', '8');
INSERT INTO `ts_area` VALUES ('310000', '上海市', '0', '9');
INSERT INTO `ts_area` VALUES ('320000', '江蘇省', '0', '10');
INSERT INTO `ts_area` VALUES ('330000', '浙江省', '0', '11');
INSERT INTO `ts_area` VALUES ('340000', '安徽省', '0', '12');
INSERT INTO `ts_area` VALUES ('350000', '福建省', '0', '13');
INSERT INTO `ts_area` VALUES ('360000', '江西省', '0', '14');
INSERT INTO `ts_area` VALUES ('370000', '山東省', '0', '15');
INSERT INTO `ts_area` VALUES ('410000', '河南省', '0', '16');
INSERT INTO `ts_area` VALUES ('420000', '湖北省', '0', '17');
INSERT INTO `ts_area` VALUES ('430000', '湖南省', '0', '18');
INSERT INTO `ts_area` VALUES ('440000', '廣東省', '0', '19');
INSERT INTO `ts_area` VALUES ('450000', '廣西省', '0', '20');
INSERT INTO `ts_area` VALUES ('460000', '海南省', '0', '21');
INSERT INTO `ts_area` VALUES ('500000', '重慶市', '0', '22');
INSERT INTO `ts_area` VALUES ('510000', '四川省', '0', '23');
INSERT INTO `ts_area` VALUES ('520000', '貴州省', '0', '24');
INSERT INTO `ts_area` VALUES ('530000', '雲南省', '0', '25');
INSERT INTO `ts_area` VALUES ('540000', '西　藏', '0', '26');
INSERT INTO `ts_area` VALUES ('610000', '陝西省', '0', '27');
INSERT INTO `ts_area` VALUES ('620000', '甘肅省', '0', '28');
INSERT INTO `ts_area` VALUES ('630000', '青海省', '0', '29');
INSERT INTO `ts_area` VALUES ('640000', '寧　夏', '0', '30');
INSERT INTO `ts_area` VALUES ('650000', '新　疆', '0', '31');
INSERT INTO `ts_area` VALUES ('710000', '臺灣省', '0', '32');
INSERT INTO `ts_area` VALUES ('810000', '香　港', '0', '33');
INSERT INTO `ts_area` VALUES ('820000', '澳　門', '0', '34');
INSERT INTO `ts_area` VALUES ('110100', '北京市', '110000', '1');
INSERT INTO `ts_area` VALUES ('110200', '縣', '110000', '2');
INSERT INTO `ts_area` VALUES ('120100', '市轄區', '120000', '1');
INSERT INTO `ts_area` VALUES ('120200', '縣', '120000', '2');
INSERT INTO `ts_area` VALUES ('130100', '石家莊市', '130000', '1');
INSERT INTO `ts_area` VALUES ('130200', '唐山市', '130000', '2');
INSERT INTO `ts_area` VALUES ('130300', '秦皇島市', '130000', '3');
INSERT INTO `ts_area` VALUES ('130400', '邯鄲市', '130000', '4');
INSERT INTO `ts_area` VALUES ('130500', '邢臺市', '130000', '5');
INSERT INTO `ts_area` VALUES ('130600', '保定市', '130000', '6');
INSERT INTO `ts_area` VALUES ('130700', '張家口市', '130000', '7');
INSERT INTO `ts_area` VALUES ('130800', '承德市', '130000', '8');
INSERT INTO `ts_area` VALUES ('130900', '滄州市', '130000', '9');
INSERT INTO `ts_area` VALUES ('131000', '廊坊市', '130000', '10');
INSERT INTO `ts_area` VALUES ('131100', '衡水市', '130000', '11');
INSERT INTO `ts_area` VALUES ('140100', '太原市', '140000', '1');
INSERT INTO `ts_area` VALUES ('140200', '大同市', '140000', '2');
INSERT INTO `ts_area` VALUES ('140300', '陽泉市', '140000', '3');
INSERT INTO `ts_area` VALUES ('140400', '長治市', '140000', '4');
INSERT INTO `ts_area` VALUES ('140500', '晉城市', '140000', '5');
INSERT INTO `ts_area` VALUES ('140600', '朔州市', '140000', '6');
INSERT INTO `ts_area` VALUES ('140700', '晉中市', '140000', '7');
INSERT INTO `ts_area` VALUES ('140800', '運城市', '140000', '8');
INSERT INTO `ts_area` VALUES ('140900', '忻州市', '140000', '9');
INSERT INTO `ts_area` VALUES ('141000', '臨汾市', '140000', '10');
INSERT INTO `ts_area` VALUES ('141100', '呂梁市', '140000', '11');
INSERT INTO `ts_area` VALUES ('150100', '呼和浩特市', '150000', '1');
INSERT INTO `ts_area` VALUES ('150200', '包頭市', '150000', '2');
INSERT INTO `ts_area` VALUES ('150300', '烏海市', '150000', '3');
INSERT INTO `ts_area` VALUES ('150400', '赤峰市', '150000', '4');
INSERT INTO `ts_area` VALUES ('150500', '通遼市', '150000', '5');
INSERT INTO `ts_area` VALUES ('150600', '鄂爾多斯市', '150000', '6');
INSERT INTO `ts_area` VALUES ('150700', '呼倫貝爾市', '150000', '7');
INSERT INTO `ts_area` VALUES ('150800', '巴彥淖爾市', '150000', '8');
INSERT INTO `ts_area` VALUES ('150900', '烏蘭察布市', '150000', '9');
INSERT INTO `ts_area` VALUES ('152200', '興安盟', '150000', '10');
INSERT INTO `ts_area` VALUES ('152500', '錫林郭勒盟', '150000', '11');
INSERT INTO `ts_area` VALUES ('152900', '阿拉善盟', '150000', '12');
INSERT INTO `ts_area` VALUES ('210100', '瀋陽市', '210000', '1');
INSERT INTO `ts_area` VALUES ('210200', '大連市', '210000', '2');
INSERT INTO `ts_area` VALUES ('210300', '鞍山市', '210000', '3');
INSERT INTO `ts_area` VALUES ('210400', '撫順市', '210000', '4');
INSERT INTO `ts_area` VALUES ('210500', '本溪市', '210000', '5');
INSERT INTO `ts_area` VALUES ('210600', '丹東市', '210000', '6');
INSERT INTO `ts_area` VALUES ('210700', '錦州市', '210000', '7');
INSERT INTO `ts_area` VALUES ('210800', '營口市', '210000', '8');
INSERT INTO `ts_area` VALUES ('210900', '阜新市', '210000', '9');
INSERT INTO `ts_area` VALUES ('211000', '遼陽市', '210000', '10');
INSERT INTO `ts_area` VALUES ('211100', '盤錦市', '210000', '11');
INSERT INTO `ts_area` VALUES ('211200', '鐵嶺市', '210000', '12');
INSERT INTO `ts_area` VALUES ('211300', '朝陽市', '210000', '13');
INSERT INTO `ts_area` VALUES ('211400', '葫蘆島市', '210000', '14');
INSERT INTO `ts_area` VALUES ('220100', '長春市', '220000', '1');
INSERT INTO `ts_area` VALUES ('220200', '吉林市', '220000', '2');
INSERT INTO `ts_area` VALUES ('220300', '四平市', '220000', '3');
INSERT INTO `ts_area` VALUES ('220400', '遼源市', '220000', '4');
INSERT INTO `ts_area` VALUES ('220500', '通化市', '220000', '5');
INSERT INTO `ts_area` VALUES ('220600', '白山市', '220000', '6');
INSERT INTO `ts_area` VALUES ('220700', '松原市', '220000', '7');
INSERT INTO `ts_area` VALUES ('220800', '白城市', '220000', '8');
INSERT INTO `ts_area` VALUES ('222400', '延邊朝鮮族自治州', '220000', '9');
INSERT INTO `ts_area` VALUES ('230100', '哈爾濱市', '230000', '1');
INSERT INTO `ts_area` VALUES ('230200', '齊齊哈爾市', '230000', '2');
INSERT INTO `ts_area` VALUES ('230300', '雞西市', '230000', '3');
INSERT INTO `ts_area` VALUES ('230400', '鶴崗市', '230000', '4');
INSERT INTO `ts_area` VALUES ('230500', '雙鴨山市', '230000', '5');
INSERT INTO `ts_area` VALUES ('230600', '大慶市', '230000', '6');
INSERT INTO `ts_area` VALUES ('230700', '伊春市', '230000', '7');
INSERT INTO `ts_area` VALUES ('230800', '佳木斯市', '230000', '8');
INSERT INTO `ts_area` VALUES ('230900', '七臺河市', '230000', '9');
INSERT INTO `ts_area` VALUES ('231000', '牡丹江市', '230000', '10');
INSERT INTO `ts_area` VALUES ('231100', '黑河市', '230000', '11');
INSERT INTO `ts_area` VALUES ('231200', '綏化市', '230000', '12');
INSERT INTO `ts_area` VALUES ('232700', '大興安嶺地區', '230000', '13');
INSERT INTO `ts_area` VALUES ('310100', '市轄區', '310000', '1');
INSERT INTO `ts_area` VALUES ('310200', '縣', '310000', '2');
INSERT INTO `ts_area` VALUES ('320100', '南京市', '320000', '1');
INSERT INTO `ts_area` VALUES ('320200', '無錫市', '320000', '2');
INSERT INTO `ts_area` VALUES ('320300', '徐州市', '320000', '3');
INSERT INTO `ts_area` VALUES ('320400', '常州市', '320000', '4');
INSERT INTO `ts_area` VALUES ('320500', '蘇州市', '320000', '5');
INSERT INTO `ts_area` VALUES ('320600', '南通市', '320000', '6');
INSERT INTO `ts_area` VALUES ('320700', '連雲港市', '320000', '7');
INSERT INTO `ts_area` VALUES ('320800', '淮安市', '320000', '8');
INSERT INTO `ts_area` VALUES ('320900', '鹽城市', '320000', '9');
INSERT INTO `ts_area` VALUES ('321000', '揚州市', '320000', '10');
INSERT INTO `ts_area` VALUES ('321100', '鎮江市', '320000', '11');
INSERT INTO `ts_area` VALUES ('321200', '泰州市', '320000', '12');
INSERT INTO `ts_area` VALUES ('321300', '宿遷市', '320000', '13');
INSERT INTO `ts_area` VALUES ('330100', '杭州市', '330000', '1');
INSERT INTO `ts_area` VALUES ('330200', '寧波市', '330000', '2');
INSERT INTO `ts_area` VALUES ('330300', '溫州市', '330000', '3');
INSERT INTO `ts_area` VALUES ('330400', '嘉興市', '330000', '4');
INSERT INTO `ts_area` VALUES ('330500', '湖州市', '330000', '5');
INSERT INTO `ts_area` VALUES ('330600', '紹興市', '330000', '6');
INSERT INTO `ts_area` VALUES ('330700', '金華市', '330000', '7');
INSERT INTO `ts_area` VALUES ('330800', '衢州市', '330000', '8');
INSERT INTO `ts_area` VALUES ('330900', '舟山市', '330000', '9');
INSERT INTO `ts_area` VALUES ('331000', '台州市', '330000', '10');
INSERT INTO `ts_area` VALUES ('331100', '麗水市', '330000', '11');
INSERT INTO `ts_area` VALUES ('340100', '合肥市', '340000', '1');
INSERT INTO `ts_area` VALUES ('340200', '蕪湖市', '340000', '2');
INSERT INTO `ts_area` VALUES ('340300', '蚌埠市', '340000', '3');
INSERT INTO `ts_area` VALUES ('340400', '淮南市', '340000', '4');
INSERT INTO `ts_area` VALUES ('340500', '馬鞍山市', '340000', '5');
INSERT INTO `ts_area` VALUES ('340600', '淮北市', '340000', '6');
INSERT INTO `ts_area` VALUES ('340700', '銅陵市', '340000', '7');
INSERT INTO `ts_area` VALUES ('340800', '安慶市', '340000', '8');
INSERT INTO `ts_area` VALUES ('341000', '黃山市', '340000', '9');
INSERT INTO `ts_area` VALUES ('341100', '滁州市', '340000', '10');
INSERT INTO `ts_area` VALUES ('341200', '阜陽市', '340000', '11');
INSERT INTO `ts_area` VALUES ('341300', '宿州市', '340000', '12');
INSERT INTO `ts_area` VALUES ('341500', '六安市', '340000', '13');
INSERT INTO `ts_area` VALUES ('341600', '亳州市', '340000', '14');
INSERT INTO `ts_area` VALUES ('341700', '池州市', '340000', '15');
INSERT INTO `ts_area` VALUES ('341800', '宣城市', '340000', '16');
INSERT INTO `ts_area` VALUES ('350100', '福州市', '350000', '1');
INSERT INTO `ts_area` VALUES ('350200', '廈門市', '350000', '2');
INSERT INTO `ts_area` VALUES ('350300', '莆田市', '350000', '3');
INSERT INTO `ts_area` VALUES ('350400', '三明市', '350000', '4');
INSERT INTO `ts_area` VALUES ('350500', '泉州市', '350000', '5');
INSERT INTO `ts_area` VALUES ('350600', '漳州市', '350000', '6');
INSERT INTO `ts_area` VALUES ('350700', '南平市', '350000', '7');
INSERT INTO `ts_area` VALUES ('350800', '龍巖市', '350000', '8');
INSERT INTO `ts_area` VALUES ('350900', '寧德市', '350000', '9');
INSERT INTO `ts_area` VALUES ('360100', '南昌市', '360000', '1');
INSERT INTO `ts_area` VALUES ('360200', '景德鎮市', '360000', '2');
INSERT INTO `ts_area` VALUES ('360300', '萍鄉市', '360000', '3');
INSERT INTO `ts_area` VALUES ('360400', '九江市', '360000', '4');
INSERT INTO `ts_area` VALUES ('360500', '新餘市', '360000', '5');
INSERT INTO `ts_area` VALUES ('360600', '鷹潭市', '360000', '6');
INSERT INTO `ts_area` VALUES ('360700', '贛州市', '360000', '7');
INSERT INTO `ts_area` VALUES ('360800', '吉安市', '360000', '8');
INSERT INTO `ts_area` VALUES ('360900', '宜春市', '360000', '9');
INSERT INTO `ts_area` VALUES ('361000', '撫州市', '360000', '10');
INSERT INTO `ts_area` VALUES ('361100', '上饒市', '360000', '11');
INSERT INTO `ts_area` VALUES ('370100', '濟南市', '370000', '1');
INSERT INTO `ts_area` VALUES ('370200', '青島市', '370000', '2');
INSERT INTO `ts_area` VALUES ('370300', '淄博市', '370000', '3');
INSERT INTO `ts_area` VALUES ('370400', '棗莊市', '370000', '4');
INSERT INTO `ts_area` VALUES ('370500', '東營市', '370000', '5');
INSERT INTO `ts_area` VALUES ('370600', '煙臺市', '370000', '6');
INSERT INTO `ts_area` VALUES ('370700', '濰坊市', '370000', '7');
INSERT INTO `ts_area` VALUES ('370800', '濟寧市', '370000', '8');
INSERT INTO `ts_area` VALUES ('370900', '泰安市', '370000', '9');
INSERT INTO `ts_area` VALUES ('371000', '威海市', '370000', '10');
INSERT INTO `ts_area` VALUES ('371100', '日照市', '370000', '11');
INSERT INTO `ts_area` VALUES ('371200', '萊蕪市', '370000', '12');
INSERT INTO `ts_area` VALUES ('371300', '臨沂市', '370000', '13');
INSERT INTO `ts_area` VALUES ('371400', '德州市', '370000', '14');
INSERT INTO `ts_area` VALUES ('371500', '聊城市', '370000', '15');
INSERT INTO `ts_area` VALUES ('371600', '濱州市', '370000', '16');
INSERT INTO `ts_area` VALUES ('371700', '菏澤市', '370000', '17');
INSERT INTO `ts_area` VALUES ('410100', '鄭州市', '410000', '1');
INSERT INTO `ts_area` VALUES ('410200', '開封市', '410000', '2');
INSERT INTO `ts_area` VALUES ('410300', '洛陽市', '410000', '3');
INSERT INTO `ts_area` VALUES ('410400', '平頂山市', '410000', '4');
INSERT INTO `ts_area` VALUES ('410500', '安陽市', '410000', '5');
INSERT INTO `ts_area` VALUES ('410600', '鶴壁市', '410000', '6');
INSERT INTO `ts_area` VALUES ('410700', '新鄉市', '410000', '7');
INSERT INTO `ts_area` VALUES ('410800', '焦作市', '410000', '8');
INSERT INTO `ts_area` VALUES ('410900', '濮陽市', '410000', '9');
INSERT INTO `ts_area` VALUES ('411000', '許昌市', '410000', '10');
INSERT INTO `ts_area` VALUES ('411100', '漯河市', '410000', '11');
INSERT INTO `ts_area` VALUES ('411200', '三門峽市', '410000', '12');
INSERT INTO `ts_area` VALUES ('411300', '南陽市', '410000', '13');
INSERT INTO `ts_area` VALUES ('411400', '商丘市', '410000', '14');
INSERT INTO `ts_area` VALUES ('411500', '信陽市', '410000', '15');
INSERT INTO `ts_area` VALUES ('411600', '周口市', '410000', '16');
INSERT INTO `ts_area` VALUES ('411700', '駐馬店市', '410000', '17');
INSERT INTO `ts_area` VALUES ('420100', '武漢市', '420000', '1');
INSERT INTO `ts_area` VALUES ('420200', '黃石市', '420000', '2');
INSERT INTO `ts_area` VALUES ('420300', '十堰市', '420000', '3');
INSERT INTO `ts_area` VALUES ('420500', '宜昌市', '420000', '4');
INSERT INTO `ts_area` VALUES ('420600', '襄樊市', '420000', '5');
INSERT INTO `ts_area` VALUES ('420700', '鄂州市', '420000', '6');
INSERT INTO `ts_area` VALUES ('420800', '荊門市', '420000', '7');
INSERT INTO `ts_area` VALUES ('420900', '孝感市', '420000', '8');
INSERT INTO `ts_area` VALUES ('421000', '荊州市', '420000', '9');
INSERT INTO `ts_area` VALUES ('421100', '黃岡市', '420000', '10');
INSERT INTO `ts_area` VALUES ('421200', '咸寧市', '420000', '11');
INSERT INTO `ts_area` VALUES ('421300', '隨州市', '420000', '12');
INSERT INTO `ts_area` VALUES ('422800', '恩施土家族苗族自治州', '420000', '13');
INSERT INTO `ts_area` VALUES ('429000', '省直轄行政單位', '420000', '14');
INSERT INTO `ts_area` VALUES ('430100', '長沙市', '430000', '1');
INSERT INTO `ts_area` VALUES ('430200', '株洲市', '430000', '2');
INSERT INTO `ts_area` VALUES ('430300', '湘潭市', '430000', '3');
INSERT INTO `ts_area` VALUES ('430400', '衡陽市', '430000', '4');
INSERT INTO `ts_area` VALUES ('430500', '邵陽市', '430000', '5');
INSERT INTO `ts_area` VALUES ('430600', '岳陽市', '430000', '6');
INSERT INTO `ts_area` VALUES ('430700', '常德市', '430000', '7');
INSERT INTO `ts_area` VALUES ('430800', '張家界市', '430000', '8');
INSERT INTO `ts_area` VALUES ('430900', '益陽市', '430000', '9');
INSERT INTO `ts_area` VALUES ('431000', '郴州市', '430000', '10');
INSERT INTO `ts_area` VALUES ('431100', '永州市', '430000', '11');
INSERT INTO `ts_area` VALUES ('431200', '懷化市', '430000', '12');
INSERT INTO `ts_area` VALUES ('431300', '婁底市', '430000', '13');
INSERT INTO `ts_area` VALUES ('433100', '湘西土家族苗族自治州', '430000', '14');
INSERT INTO `ts_area` VALUES ('440100', '廣州市', '440000', '1');
INSERT INTO `ts_area` VALUES ('440200', '韶關市', '440000', '2');
INSERT INTO `ts_area` VALUES ('440300', '深圳市', '440000', '3');
INSERT INTO `ts_area` VALUES ('440400', '珠海市', '440000', '4');
INSERT INTO `ts_area` VALUES ('440500', '汕頭市', '440000', '5');
INSERT INTO `ts_area` VALUES ('440600', '佛山市', '440000', '6');
INSERT INTO `ts_area` VALUES ('440700', '江門市', '440000', '7');
INSERT INTO `ts_area` VALUES ('440800', '湛江市', '440000', '8');
INSERT INTO `ts_area` VALUES ('440900', '茂名市', '440000', '9');
INSERT INTO `ts_area` VALUES ('441200', '肇慶市', '440000', '10');
INSERT INTO `ts_area` VALUES ('441300', '惠州市', '440000', '11');
INSERT INTO `ts_area` VALUES ('441400', '梅州市', '440000', '12');
INSERT INTO `ts_area` VALUES ('441500', '汕尾市', '440000', '13');
INSERT INTO `ts_area` VALUES ('441600', '河源市', '440000', '14');
INSERT INTO `ts_area` VALUES ('441700', '陽江市', '440000', '15');
INSERT INTO `ts_area` VALUES ('441800', '清遠市', '440000', '16');
INSERT INTO `ts_area` VALUES ('441900', '東莞市', '440000', '17');
INSERT INTO `ts_area` VALUES ('442000', '中山市', '440000', '18');
INSERT INTO `ts_area` VALUES ('445100', '潮州市', '440000', '19');
INSERT INTO `ts_area` VALUES ('445200', '揭陽市', '440000', '20');
INSERT INTO `ts_area` VALUES ('445300', '雲浮市', '440000', '21');
INSERT INTO `ts_area` VALUES ('450100', '南寧市', '450000', '1');
INSERT INTO `ts_area` VALUES ('450200', '柳州市', '450000', '2');
INSERT INTO `ts_area` VALUES ('450300', '桂林市', '450000', '3');
INSERT INTO `ts_area` VALUES ('450400', '梧州市', '450000', '4');
INSERT INTO `ts_area` VALUES ('450500', '北海市', '450000', '5');
INSERT INTO `ts_area` VALUES ('450600', '防城港市', '450000', '6');
INSERT INTO `ts_area` VALUES ('450700', '欽州市', '450000', '7');
INSERT INTO `ts_area` VALUES ('450800', '貴港市', '450000', '8');
INSERT INTO `ts_area` VALUES ('450900', '玉林市', '450000', '9');
INSERT INTO `ts_area` VALUES ('451000', '百色市', '450000', '10');
INSERT INTO `ts_area` VALUES ('451100', '賀州市', '450000', '11');
INSERT INTO `ts_area` VALUES ('451200', '河池市', '450000', '12');
INSERT INTO `ts_area` VALUES ('451300', '來賓市', '450000', '13');
INSERT INTO `ts_area` VALUES ('451400', '崇左市', '450000', '14');
INSERT INTO `ts_area` VALUES ('460100', '海口市', '460000', '1');
INSERT INTO `ts_area` VALUES ('460200', '三亞市', '460000', '2');
INSERT INTO `ts_area` VALUES ('469000', '省直轄縣級行政單位', '460000', '3');
INSERT INTO `ts_area` VALUES ('500100', '市轄區', '500000', '1');
INSERT INTO `ts_area` VALUES ('500200', '縣', '500000', '2');
INSERT INTO `ts_area` VALUES ('500300', '市', '500000', '3');
INSERT INTO `ts_area` VALUES ('510100', '成都市', '510000', '1');
INSERT INTO `ts_area` VALUES ('510300', '自貢市', '510000', '2');
INSERT INTO `ts_area` VALUES ('510400', '攀枝花市', '510000', '3');
INSERT INTO `ts_area` VALUES ('510500', '瀘州市', '510000', '4');
INSERT INTO `ts_area` VALUES ('510600', '德陽市', '510000', '5');
INSERT INTO `ts_area` VALUES ('510700', '綿陽市', '510000', '6');
INSERT INTO `ts_area` VALUES ('510800', '廣元市', '510000', '7');
INSERT INTO `ts_area` VALUES ('510900', '遂寧市', '510000', '8');
INSERT INTO `ts_area` VALUES ('511000', '內江市', '510000', '9');
INSERT INTO `ts_area` VALUES ('511100', '樂山市', '510000', '10');
INSERT INTO `ts_area` VALUES ('511300', '南充市', '510000', '11');
INSERT INTO `ts_area` VALUES ('511400', '眉山市', '510000', '12');
INSERT INTO `ts_area` VALUES ('511500', '宜賓市', '510000', '13');
INSERT INTO `ts_area` VALUES ('511600', '廣安市', '510000', '14');
INSERT INTO `ts_area` VALUES ('511700', '達州市', '510000', '15');
INSERT INTO `ts_area` VALUES ('511800', '雅安市', '510000', '16');
INSERT INTO `ts_area` VALUES ('511900', '巴中市', '510000', '17');
INSERT INTO `ts_area` VALUES ('512000', '資陽市', '510000', '18');
INSERT INTO `ts_area` VALUES ('513200', '阿壩藏族羌族自治州', '510000', '19');
INSERT INTO `ts_area` VALUES ('513300', '甘孜藏族自治州', '510000', '20');
INSERT INTO `ts_area` VALUES ('513400', '涼山彝族自治州', '510000', '21');
INSERT INTO `ts_area` VALUES ('520100', '貴陽市', '520000', '1');
INSERT INTO `ts_area` VALUES ('520200', '六盤水市', '520000', '2');
INSERT INTO `ts_area` VALUES ('520300', '遵義市', '520000', '3');
INSERT INTO `ts_area` VALUES ('520400', '安順市', '520000', '4');
INSERT INTO `ts_area` VALUES ('522200', '銅仁地區', '520000', '5');
INSERT INTO `ts_area` VALUES ('522300', '黔西南布依族苗族自治州', '520000', '6');
INSERT INTO `ts_area` VALUES ('522400', '畢節地區', '520000', '7');
INSERT INTO `ts_area` VALUES ('522600', '黔東南苗族侗族自治州', '520000', '8');
INSERT INTO `ts_area` VALUES ('522700', '黔南布依族苗族自治州', '520000', '9');
INSERT INTO `ts_area` VALUES ('530100', '昆明市', '530000', '1');
INSERT INTO `ts_area` VALUES ('530300', '曲靖市', '530000', '2');
INSERT INTO `ts_area` VALUES ('530400', '玉溪市', '530000', '3');
INSERT INTO `ts_area` VALUES ('530500', '保山市', '530000', '4');
INSERT INTO `ts_area` VALUES ('530600', '昭通市', '530000', '5');
INSERT INTO `ts_area` VALUES ('530700', '麗江市', '530000', '6');
INSERT INTO `ts_area` VALUES ('530800', '思茅市', '530000', '7');
INSERT INTO `ts_area` VALUES ('530900', '臨滄市', '530000', '8');
INSERT INTO `ts_area` VALUES ('532300', '楚雄彝族自治州', '530000', '9');
INSERT INTO `ts_area` VALUES ('532500', '紅河哈尼族彝族自治州', '530000', '10');
INSERT INTO `ts_area` VALUES ('532600', '文山壯族苗族自治州', '530000', '11');
INSERT INTO `ts_area` VALUES ('532800', '西雙版納傣族自治州', '530000', '12');
INSERT INTO `ts_area` VALUES ('532900', '大理白族自治州', '530000', '13');
INSERT INTO `ts_area` VALUES ('533100', '德宏傣族景頗族自治州', '530000', '14');
INSERT INTO `ts_area` VALUES ('533300', '怒江傈僳族自治州', '530000', '15');
INSERT INTO `ts_area` VALUES ('533400', '迪慶藏族自治州', '530000', '16');
INSERT INTO `ts_area` VALUES ('540100', '拉薩市', '540000', '1');
INSERT INTO `ts_area` VALUES ('542100', '昌都地區', '540000', '2');
INSERT INTO `ts_area` VALUES ('542200', '山南地區', '540000', '3');
INSERT INTO `ts_area` VALUES ('542300', '日喀則地區', '540000', '4');
INSERT INTO `ts_area` VALUES ('542400', '那曲地區', '540000', '5');
INSERT INTO `ts_area` VALUES ('542500', '阿里地區', '540000', '6');
INSERT INTO `ts_area` VALUES ('542600', '林芝地區', '540000', '7');
INSERT INTO `ts_area` VALUES ('610100', '西安市', '610000', '1');
INSERT INTO `ts_area` VALUES ('610200', '銅川市', '610000', '2');
INSERT INTO `ts_area` VALUES ('610300', '寶雞市', '610000', '3');
INSERT INTO `ts_area` VALUES ('610400', '咸陽市', '610000', '4');
INSERT INTO `ts_area` VALUES ('610500', '渭南市', '610000', '5');
INSERT INTO `ts_area` VALUES ('610600', '延安市', '610000', '6');
INSERT INTO `ts_area` VALUES ('610700', '漢中市', '610000', '7');
INSERT INTO `ts_area` VALUES ('610800', '榆林市', '610000', '8');
INSERT INTO `ts_area` VALUES ('610900', '安康市', '610000', '9');
INSERT INTO `ts_area` VALUES ('611000', '商洛市', '610000', '10');
INSERT INTO `ts_area` VALUES ('620100', '蘭州市', '620000', '1');
INSERT INTO `ts_area` VALUES ('620200', '嘉峪關市', '620000', '2');
INSERT INTO `ts_area` VALUES ('620300', '金昌市', '620000', '3');
INSERT INTO `ts_area` VALUES ('620400', '白銀市', '620000', '4');
INSERT INTO `ts_area` VALUES ('620500', '天水市', '620000', '5');
INSERT INTO `ts_area` VALUES ('620600', '武威市', '620000', '6');
INSERT INTO `ts_area` VALUES ('620700', '張掖市', '620000', '7');
INSERT INTO `ts_area` VALUES ('620800', '平涼市', '620000', '8');
INSERT INTO `ts_area` VALUES ('620900', '酒泉市', '620000', '9');
INSERT INTO `ts_area` VALUES ('621000', '慶陽市', '620000', '10');
INSERT INTO `ts_area` VALUES ('621100', '定西市', '620000', '11');
INSERT INTO `ts_area` VALUES ('621200', '隴南市', '620000', '12');
INSERT INTO `ts_area` VALUES ('622900', '臨夏回族自治州', '620000', '13');
INSERT INTO `ts_area` VALUES ('623000', '甘南藏族自治州', '620000', '14');
INSERT INTO `ts_area` VALUES ('630100', '西寧市', '630000', '1');
INSERT INTO `ts_area` VALUES ('632100', '海東地區', '630000', '2');
INSERT INTO `ts_area` VALUES ('632200', '海北藏族自治州', '630000', '3');
INSERT INTO `ts_area` VALUES ('632300', '黃南藏族自治州', '630000', '4');
INSERT INTO `ts_area` VALUES ('632500', '海南藏族自治州', '630000', '5');
INSERT INTO `ts_area` VALUES ('632600', '果洛藏族自治州', '630000', '6');
INSERT INTO `ts_area` VALUES ('632700', '玉樹藏族自治州', '630000', '7');
INSERT INTO `ts_area` VALUES ('632800', '海西蒙古族藏族自治州', '630000', '8');
INSERT INTO `ts_area` VALUES ('640100', '銀川市', '640000', '1');
INSERT INTO `ts_area` VALUES ('640200', '石嘴山市', '640000', '2');
INSERT INTO `ts_area` VALUES ('640300', '吳忠市', '640000', '3');
INSERT INTO `ts_area` VALUES ('640400', '固原市', '640000', '4');
INSERT INTO `ts_area` VALUES ('640500', '中衛市', '640000', '5');
INSERT INTO `ts_area` VALUES ('650100', '烏魯木齊市', '650000', '1');
INSERT INTO `ts_area` VALUES ('650200', '克拉瑪依市', '650000', '2');
INSERT INTO `ts_area` VALUES ('652100', '吐魯番地區', '650000', '3');
INSERT INTO `ts_area` VALUES ('652200', '哈密地區', '650000', '4');
INSERT INTO `ts_area` VALUES ('652300', '昌吉回族自治州', '650000', '5');
INSERT INTO `ts_area` VALUES ('652700', '博爾塔拉蒙古自治州', '650000', '6');
INSERT INTO `ts_area` VALUES ('652800', '巴音郭楞蒙古自治州', '650000', '7');
INSERT INTO `ts_area` VALUES ('652900', '阿克蘇地區', '650000', '8');
INSERT INTO `ts_area` VALUES ('653000', '克孜勒蘇柯爾克孜自治州', '650000', '9');
INSERT INTO `ts_area` VALUES ('653100', '喀什地區', '650000', '10');
INSERT INTO `ts_area` VALUES ('653200', '和田地區', '650000', '11');
INSERT INTO `ts_area` VALUES ('654000', '伊犁哈薩克自治州', '650000', '12');
INSERT INTO `ts_area` VALUES ('654200', '塔城地區', '650000', '13');
INSERT INTO `ts_area` VALUES ('654300', '阿勒泰地區', '650000', '14');
INSERT INTO `ts_area` VALUES ('659000', '省直轄行政單位', '650000', '15');
INSERT INTO `ts_area` VALUES ('110101', '東城區', '110100', '1');
INSERT INTO `ts_area` VALUES ('110102', '西城區', '110100', '2');
INSERT INTO `ts_area` VALUES ('110103', '崇文區', '110100', '3');
INSERT INTO `ts_area` VALUES ('110104', '宣武區', '110100', '4');
INSERT INTO `ts_area` VALUES ('110105', '朝陽區', '110100', '5');
INSERT INTO `ts_area` VALUES ('110106', '豐臺區', '110100', '6');
INSERT INTO `ts_area` VALUES ('110107', '石景山區', '110100', '7');
INSERT INTO `ts_area` VALUES ('110108', '海淀區', '110100', '8');
INSERT INTO `ts_area` VALUES ('110109', '門頭溝區', '110100', '9');
INSERT INTO `ts_area` VALUES ('110111', '房山區', '110100', '10');
INSERT INTO `ts_area` VALUES ('110112', '通州區', '110100', '11');
INSERT INTO `ts_area` VALUES ('110113', '順義區', '110100', '12');
INSERT INTO `ts_area` VALUES ('110114', '昌平區', '110100', '13');
INSERT INTO `ts_area` VALUES ('110115', '大興區', '110100', '14');
INSERT INTO `ts_area` VALUES ('110116', '懷柔區', '110100', '15');
INSERT INTO `ts_area` VALUES ('110117', '平谷區', '110100', '16');
INSERT INTO `ts_area` VALUES ('110228', '密雲縣', '110200', '1');
INSERT INTO `ts_area` VALUES ('110229', '延慶縣', '110200', '2');
INSERT INTO `ts_area` VALUES ('120101', '和平區', '120100', '1');
INSERT INTO `ts_area` VALUES ('120102', '河東區', '120100', '2');
INSERT INTO `ts_area` VALUES ('120103', '河西區', '120100', '3');
INSERT INTO `ts_area` VALUES ('120104', '南開區', '120100', '4');
INSERT INTO `ts_area` VALUES ('120105', '河北區', '120100', '5');
INSERT INTO `ts_area` VALUES ('120106', '紅橋區', '120100', '6');
INSERT INTO `ts_area` VALUES ('120107', '塘沽區', '120100', '7');
INSERT INTO `ts_area` VALUES ('120108', '漢沽區', '120100', '8');
INSERT INTO `ts_area` VALUES ('120109', '大港區', '120100', '9');
INSERT INTO `ts_area` VALUES ('120110', '東麗區', '120100', '10');
INSERT INTO `ts_area` VALUES ('120111', '西青區', '120100', '11');
INSERT INTO `ts_area` VALUES ('120112', '津南區', '120100', '12');
INSERT INTO `ts_area` VALUES ('120113', '北辰區', '120100', '13');
INSERT INTO `ts_area` VALUES ('120114', '武清區', '120100', '14');
INSERT INTO `ts_area` VALUES ('120115', '寶坻區', '120100', '15');
INSERT INTO `ts_area` VALUES ('120221', '寧河縣', '120200', '1');
INSERT INTO `ts_area` VALUES ('120223', '靜海縣', '120200', '2');
INSERT INTO `ts_area` VALUES ('120225', '薊　縣', '120200', '3');
INSERT INTO `ts_area` VALUES ('130101', '市轄區', '130100', '1');
INSERT INTO `ts_area` VALUES ('130102', '長安區', '130100', '2');
INSERT INTO `ts_area` VALUES ('130103', '橋東區', '130100', '3');
INSERT INTO `ts_area` VALUES ('130104', '橋西區', '130100', '4');
INSERT INTO `ts_area` VALUES ('130105', '新華區', '130100', '5');
INSERT INTO `ts_area` VALUES ('130107', '井陘礦區', '130100', '6');
INSERT INTO `ts_area` VALUES ('130108', '裕華區', '130100', '7');
INSERT INTO `ts_area` VALUES ('130121', '井陘縣', '130100', '8');
INSERT INTO `ts_area` VALUES ('130123', '正定縣', '130100', '9');
INSERT INTO `ts_area` VALUES ('130124', '欒城縣', '130100', '10');
INSERT INTO `ts_area` VALUES ('130125', '行唐縣', '130100', '11');
INSERT INTO `ts_area` VALUES ('130126', '靈壽縣', '130100', '12');
INSERT INTO `ts_area` VALUES ('130127', '高邑縣', '130100', '13');
INSERT INTO `ts_area` VALUES ('130128', '深澤縣', '130100', '14');
INSERT INTO `ts_area` VALUES ('130129', '贊皇縣', '130100', '15');
INSERT INTO `ts_area` VALUES ('130130', '無極縣', '130100', '16');
INSERT INTO `ts_area` VALUES ('130131', '平山縣', '130100', '17');
INSERT INTO `ts_area` VALUES ('130132', '元氏縣', '130100', '18');
INSERT INTO `ts_area` VALUES ('130133', '趙　縣', '130100', '19');
INSERT INTO `ts_area` VALUES ('130181', '辛集市', '130100', '20');
INSERT INTO `ts_area` VALUES ('130182', '藁城市', '130100', '21');
INSERT INTO `ts_area` VALUES ('130183', '晉州市', '130100', '22');
INSERT INTO `ts_area` VALUES ('130184', '新樂市', '130100', '23');
INSERT INTO `ts_area` VALUES ('130185', '鹿泉市', '130100', '24');
INSERT INTO `ts_area` VALUES ('130201', '市轄區', '130200', '1');
INSERT INTO `ts_area` VALUES ('130202', '路南區', '130200', '2');
INSERT INTO `ts_area` VALUES ('130203', '路北區', '130200', '3');
INSERT INTO `ts_area` VALUES ('130204', '古冶區', '130200', '4');
INSERT INTO `ts_area` VALUES ('130205', '開平區', '130200', '5');
INSERT INTO `ts_area` VALUES ('130207', '豐南區', '130200', '6');
INSERT INTO `ts_area` VALUES ('130208', '豐潤區', '130200', '7');
INSERT INTO `ts_area` VALUES ('130223', '灤　縣', '130200', '8');
INSERT INTO `ts_area` VALUES ('130224', '灤南縣', '130200', '9');
INSERT INTO `ts_area` VALUES ('130225', '樂亭縣', '130200', '10');
INSERT INTO `ts_area` VALUES ('130227', '遷西縣', '130200', '11');
INSERT INTO `ts_area` VALUES ('130229', '玉田縣', '130200', '12');
INSERT INTO `ts_area` VALUES ('130230', '唐海縣', '130200', '13');
INSERT INTO `ts_area` VALUES ('130281', '遵化市', '130200', '14');
INSERT INTO `ts_area` VALUES ('130283', '遷安市', '130200', '15');
INSERT INTO `ts_area` VALUES ('130301', '市轄區', '130300', '1');
INSERT INTO `ts_area` VALUES ('130302', '海港區', '130300', '2');
INSERT INTO `ts_area` VALUES ('130303', '山海關區', '130300', '3');
INSERT INTO `ts_area` VALUES ('130304', '北戴河區', '130300', '4');
INSERT INTO `ts_area` VALUES ('130321', '青龍滿族自治縣', '130300', '5');
INSERT INTO `ts_area` VALUES ('130322', '昌黎縣', '130300', '6');
INSERT INTO `ts_area` VALUES ('130323', '撫寧縣', '130300', '7');
INSERT INTO `ts_area` VALUES ('130324', '盧龍縣', '130300', '8');
INSERT INTO `ts_area` VALUES ('130401', '市轄區', '130400', '1');
INSERT INTO `ts_area` VALUES ('130402', '邯山區', '130400', '2');
INSERT INTO `ts_area` VALUES ('130403', '叢臺區', '130400', '3');
INSERT INTO `ts_area` VALUES ('130404', '復興區', '130400', '4');
INSERT INTO `ts_area` VALUES ('130406', '峰峰礦區', '130400', '5');
INSERT INTO `ts_area` VALUES ('130421', '邯鄲縣', '130400', '6');
INSERT INTO `ts_area` VALUES ('130423', '臨漳縣', '130400', '7');
INSERT INTO `ts_area` VALUES ('130424', '成安縣', '130400', '8');
INSERT INTO `ts_area` VALUES ('130425', '大名縣', '130400', '9');
INSERT INTO `ts_area` VALUES ('130426', '涉　縣', '130400', '10');
INSERT INTO `ts_area` VALUES ('130427', '磁　縣', '130400', '11');
INSERT INTO `ts_area` VALUES ('130428', '肥鄉縣', '130400', '12');
INSERT INTO `ts_area` VALUES ('130429', '永年縣', '130400', '13');
INSERT INTO `ts_area` VALUES ('130430', '邱　縣', '130400', '14');
INSERT INTO `ts_area` VALUES ('130431', '雞澤縣', '130400', '15');
INSERT INTO `ts_area` VALUES ('130432', '廣平縣', '130400', '16');
INSERT INTO `ts_area` VALUES ('130433', '館陶縣', '130400', '17');
INSERT INTO `ts_area` VALUES ('130434', '魏　縣', '130400', '18');
INSERT INTO `ts_area` VALUES ('130435', '曲周縣', '130400', '19');
INSERT INTO `ts_area` VALUES ('130481', '武安市', '130400', '20');
INSERT INTO `ts_area` VALUES ('130501', '市轄區', '130500', '1');
INSERT INTO `ts_area` VALUES ('130502', '橋東區', '130500', '2');
INSERT INTO `ts_area` VALUES ('130503', '橋西區', '130500', '3');
INSERT INTO `ts_area` VALUES ('130521', '邢臺縣', '130500', '4');
INSERT INTO `ts_area` VALUES ('130522', '臨城縣', '130500', '5');
INSERT INTO `ts_area` VALUES ('130523', '內丘縣', '130500', '6');
INSERT INTO `ts_area` VALUES ('130524', '柏鄉縣', '130500', '7');
INSERT INTO `ts_area` VALUES ('130525', '隆堯縣', '130500', '8');
INSERT INTO `ts_area` VALUES ('130526', '任　縣', '130500', '9');
INSERT INTO `ts_area` VALUES ('130527', '南和縣', '130500', '10');
INSERT INTO `ts_area` VALUES ('130528', '寧晉縣', '130500', '11');
INSERT INTO `ts_area` VALUES ('130529', '鉅鹿縣', '130500', '12');
INSERT INTO `ts_area` VALUES ('130530', '新河縣', '130500', '13');
INSERT INTO `ts_area` VALUES ('130531', '廣宗縣', '130500', '14');
INSERT INTO `ts_area` VALUES ('130532', '平鄉縣', '130500', '15');
INSERT INTO `ts_area` VALUES ('130533', '威　縣', '130500', '16');
INSERT INTO `ts_area` VALUES ('130534', '清河縣', '130500', '17');
INSERT INTO `ts_area` VALUES ('130535', '臨西縣', '130500', '18');
INSERT INTO `ts_area` VALUES ('130581', '南宮市', '130500', '19');
INSERT INTO `ts_area` VALUES ('130582', '沙河市', '130500', '20');
INSERT INTO `ts_area` VALUES ('130601', '市轄區', '130600', '1');
INSERT INTO `ts_area` VALUES ('130602', '新市區', '130600', '2');
INSERT INTO `ts_area` VALUES ('130603', '北市區', '130600', '3');
INSERT INTO `ts_area` VALUES ('130604', '南市區', '130600', '4');
INSERT INTO `ts_area` VALUES ('130621', '滿城縣', '130600', '5');
INSERT INTO `ts_area` VALUES ('130622', '清苑縣', '130600', '6');
INSERT INTO `ts_area` VALUES ('130623', '淶水縣', '130600', '7');
INSERT INTO `ts_area` VALUES ('130624', '阜平縣', '130600', '8');
INSERT INTO `ts_area` VALUES ('130625', '徐水縣', '130600', '9');
INSERT INTO `ts_area` VALUES ('130626', '定興縣', '130600', '10');
INSERT INTO `ts_area` VALUES ('130627', '唐　縣', '130600', '11');
INSERT INTO `ts_area` VALUES ('130628', '高陽縣', '130600', '12');
INSERT INTO `ts_area` VALUES ('130629', '容城縣', '130600', '13');
INSERT INTO `ts_area` VALUES ('130630', '淶源縣', '130600', '14');
INSERT INTO `ts_area` VALUES ('130631', '望都縣', '130600', '15');
INSERT INTO `ts_area` VALUES ('130632', '安新縣', '130600', '16');
INSERT INTO `ts_area` VALUES ('130633', '易　縣', '130600', '17');
INSERT INTO `ts_area` VALUES ('130634', '曲陽縣', '130600', '18');
INSERT INTO `ts_area` VALUES ('130635', '蠡　縣', '130600', '19');
INSERT INTO `ts_area` VALUES ('130636', '順平縣', '130600', '20');
INSERT INTO `ts_area` VALUES ('130637', '博野縣', '130600', '21');
INSERT INTO `ts_area` VALUES ('130638', '雄　縣', '130600', '22');
INSERT INTO `ts_area` VALUES ('130681', '涿州市', '130600', '23');
INSERT INTO `ts_area` VALUES ('130682', '定州市', '130600', '24');
INSERT INTO `ts_area` VALUES ('130683', '安國市', '130600', '25');
INSERT INTO `ts_area` VALUES ('130684', '高碑店市', '130600', '26');
INSERT INTO `ts_area` VALUES ('130701', '市轄區', '130700', '1');
INSERT INTO `ts_area` VALUES ('130702', '橋東區', '130700', '2');
INSERT INTO `ts_area` VALUES ('130703', '橋西區', '130700', '3');
INSERT INTO `ts_area` VALUES ('130705', '宣化區', '130700', '4');
INSERT INTO `ts_area` VALUES ('130706', '下花園區', '130700', '5');
INSERT INTO `ts_area` VALUES ('130721', '宣化縣', '130700', '6');
INSERT INTO `ts_area` VALUES ('130722', '張北縣', '130700', '7');
INSERT INTO `ts_area` VALUES ('130723', '康保縣', '130700', '8');
INSERT INTO `ts_area` VALUES ('130724', '沽源縣', '130700', '9');
INSERT INTO `ts_area` VALUES ('130725', '尚義縣', '130700', '10');
INSERT INTO `ts_area` VALUES ('130726', '蔚　縣', '130700', '11');
INSERT INTO `ts_area` VALUES ('130727', '陽原縣', '130700', '12');
INSERT INTO `ts_area` VALUES ('130728', '懷安縣', '130700', '13');
INSERT INTO `ts_area` VALUES ('130729', '萬全縣', '130700', '14');
INSERT INTO `ts_area` VALUES ('130730', '懷來縣', '130700', '15');
INSERT INTO `ts_area` VALUES ('130731', '涿鹿縣', '130700', '16');
INSERT INTO `ts_area` VALUES ('130732', '赤城縣', '130700', '17');
INSERT INTO `ts_area` VALUES ('130733', '崇禮縣', '130700', '18');
INSERT INTO `ts_area` VALUES ('130801', '市轄區', '130800', '1');
INSERT INTO `ts_area` VALUES ('130802', '雙橋區', '130800', '2');
INSERT INTO `ts_area` VALUES ('130803', '雙灤區', '130800', '3');
INSERT INTO `ts_area` VALUES ('130804', '鷹手營子礦區', '130800', '4');
INSERT INTO `ts_area` VALUES ('130821', '承德縣', '130800', '5');
INSERT INTO `ts_area` VALUES ('130822', '興隆縣', '130800', '6');
INSERT INTO `ts_area` VALUES ('130823', '平泉縣', '130800', '7');
INSERT INTO `ts_area` VALUES ('130824', '灤平縣', '130800', '8');
INSERT INTO `ts_area` VALUES ('130825', '隆化縣', '130800', '9');
INSERT INTO `ts_area` VALUES ('130826', '豐寧滿族自治縣', '130800', '10');
INSERT INTO `ts_area` VALUES ('130827', '寬城滿族自治縣', '130800', '11');
INSERT INTO `ts_area` VALUES ('130828', '圍場滿族蒙古族自治縣', '130800', '12');
INSERT INTO `ts_area` VALUES ('130901', '市轄區', '130900', '1');
INSERT INTO `ts_area` VALUES ('130902', '新華區', '130900', '2');
INSERT INTO `ts_area` VALUES ('130903', '運河區', '130900', '3');
INSERT INTO `ts_area` VALUES ('130921', '滄　縣', '130900', '4');
INSERT INTO `ts_area` VALUES ('130922', '青　縣', '130900', '5');
INSERT INTO `ts_area` VALUES ('130923', '東光縣', '130900', '6');
INSERT INTO `ts_area` VALUES ('130924', '海興縣', '130900', '7');
INSERT INTO `ts_area` VALUES ('130925', '鹽山縣', '130900', '8');
INSERT INTO `ts_area` VALUES ('130926', '肅寧縣', '130900', '9');
INSERT INTO `ts_area` VALUES ('130927', '南皮縣', '130900', '10');
INSERT INTO `ts_area` VALUES ('130928', '吳橋縣', '130900', '11');
INSERT INTO `ts_area` VALUES ('130929', '獻　縣', '130900', '12');
INSERT INTO `ts_area` VALUES ('130930', '孟村回族自治縣', '130900', '13');
INSERT INTO `ts_area` VALUES ('130981', '泊頭市', '130900', '14');
INSERT INTO `ts_area` VALUES ('130982', '任丘市', '130900', '15');
INSERT INTO `ts_area` VALUES ('130983', '黃驊市', '130900', '16');
INSERT INTO `ts_area` VALUES ('130984', '河間市', '130900', '17');
INSERT INTO `ts_area` VALUES ('131001', '市轄區', '131000', '1');
INSERT INTO `ts_area` VALUES ('131002', '安次區', '131000', '2');
INSERT INTO `ts_area` VALUES ('131003', '廣陽區', '131000', '3');
INSERT INTO `ts_area` VALUES ('131022', '固安縣', '131000', '4');
INSERT INTO `ts_area` VALUES ('131023', '永清縣', '131000', '5');
INSERT INTO `ts_area` VALUES ('131024', '香河縣', '131000', '6');
INSERT INTO `ts_area` VALUES ('131025', '大城縣', '131000', '7');
INSERT INTO `ts_area` VALUES ('131026', '文安縣', '131000', '8');
INSERT INTO `ts_area` VALUES ('131028', '大廠回族自治縣', '131000', '9');
INSERT INTO `ts_area` VALUES ('131081', '霸州市', '131000', '10');
INSERT INTO `ts_area` VALUES ('131082', '三河市', '131000', '11');
INSERT INTO `ts_area` VALUES ('131101', '市轄區', '131100', '1');
INSERT INTO `ts_area` VALUES ('131102', '桃城區', '131100', '2');
INSERT INTO `ts_area` VALUES ('131121', '棗強縣', '131100', '3');
INSERT INTO `ts_area` VALUES ('131122', '武邑縣', '131100', '4');
INSERT INTO `ts_area` VALUES ('131123', '武強縣', '131100', '5');
INSERT INTO `ts_area` VALUES ('131124', '饒陽縣', '131100', '6');
INSERT INTO `ts_area` VALUES ('131125', '安平縣', '131100', '7');
INSERT INTO `ts_area` VALUES ('131126', '故城縣', '131100', '8');
INSERT INTO `ts_area` VALUES ('131127', '景　縣', '131100', '9');
INSERT INTO `ts_area` VALUES ('131128', '阜城縣', '131100', '10');
INSERT INTO `ts_area` VALUES ('131181', '冀州市', '131100', '11');
INSERT INTO `ts_area` VALUES ('131182', '深州市', '131100', '12');
INSERT INTO `ts_area` VALUES ('140101', '市轄區', '140100', '1');
INSERT INTO `ts_area` VALUES ('140105', '小店區', '140100', '2');
INSERT INTO `ts_area` VALUES ('140106', '迎澤區', '140100', '3');
INSERT INTO `ts_area` VALUES ('140107', '杏花嶺區', '140100', '4');
INSERT INTO `ts_area` VALUES ('140108', '尖草坪區', '140100', '5');
INSERT INTO `ts_area` VALUES ('140109', '萬柏林區', '140100', '6');
INSERT INTO `ts_area` VALUES ('140110', '晉源區', '140100', '7');
INSERT INTO `ts_area` VALUES ('140121', '清徐縣', '140100', '8');
INSERT INTO `ts_area` VALUES ('140122', '陽曲縣', '140100', '9');
INSERT INTO `ts_area` VALUES ('140123', '婁煩縣', '140100', '10');
INSERT INTO `ts_area` VALUES ('140181', '古交市', '140100', '11');
INSERT INTO `ts_area` VALUES ('140201', '市轄區', '140200', '1');
INSERT INTO `ts_area` VALUES ('140202', '城　區', '140200', '2');
INSERT INTO `ts_area` VALUES ('140203', '礦　區', '140200', '3');
INSERT INTO `ts_area` VALUES ('140211', '南郊區', '140200', '4');
INSERT INTO `ts_area` VALUES ('140212', '新榮區', '140200', '5');
INSERT INTO `ts_area` VALUES ('140221', '陽高縣', '140200', '6');
INSERT INTO `ts_area` VALUES ('140222', '天鎮縣', '140200', '7');
INSERT INTO `ts_area` VALUES ('140223', '廣靈縣', '140200', '8');
INSERT INTO `ts_area` VALUES ('140224', '靈丘縣', '140200', '9');
INSERT INTO `ts_area` VALUES ('140225', '渾源縣', '140200', '10');
INSERT INTO `ts_area` VALUES ('140226', '左雲縣', '140200', '11');
INSERT INTO `ts_area` VALUES ('140227', '大同縣', '140200', '12');
INSERT INTO `ts_area` VALUES ('140301', '市轄區', '140300', '1');
INSERT INTO `ts_area` VALUES ('140302', '城　區', '140300', '2');
INSERT INTO `ts_area` VALUES ('140303', '礦　區', '140300', '3');
INSERT INTO `ts_area` VALUES ('140311', '郊　區', '140300', '4');
INSERT INTO `ts_area` VALUES ('140321', '平定縣', '140300', '5');
INSERT INTO `ts_area` VALUES ('140322', '盂　縣', '140300', '6');
INSERT INTO `ts_area` VALUES ('140401', '市轄區', '140400', '1');
INSERT INTO `ts_area` VALUES ('140402', '城　區', '140400', '2');
INSERT INTO `ts_area` VALUES ('140411', '郊　區', '140400', '3');
INSERT INTO `ts_area` VALUES ('140421', '長治縣', '140400', '4');
INSERT INTO `ts_area` VALUES ('140423', '襄垣縣', '140400', '5');
INSERT INTO `ts_area` VALUES ('140424', '屯留縣', '140400', '6');
INSERT INTO `ts_area` VALUES ('140425', '平順縣', '140400', '7');
INSERT INTO `ts_area` VALUES ('140426', '黎城縣', '140400', '8');
INSERT INTO `ts_area` VALUES ('140427', '壺關縣', '140400', '9');
INSERT INTO `ts_area` VALUES ('140428', '長子縣', '140400', '10');
INSERT INTO `ts_area` VALUES ('140429', '武鄉縣', '140400', '11');
INSERT INTO `ts_area` VALUES ('140430', '沁　縣', '140400', '12');
INSERT INTO `ts_area` VALUES ('140431', '沁源縣', '140400', '13');
INSERT INTO `ts_area` VALUES ('140481', '潞城市', '140400', '14');
INSERT INTO `ts_area` VALUES ('140501', '市轄區', '140500', '1');
INSERT INTO `ts_area` VALUES ('140502', '城　區', '140500', '2');
INSERT INTO `ts_area` VALUES ('140521', '沁水縣', '140500', '3');
INSERT INTO `ts_area` VALUES ('140522', '陽城縣', '140500', '4');
INSERT INTO `ts_area` VALUES ('140524', '陵川縣', '140500', '5');
INSERT INTO `ts_area` VALUES ('140525', '澤州縣', '140500', '6');
INSERT INTO `ts_area` VALUES ('140581', '高平市', '140500', '7');
INSERT INTO `ts_area` VALUES ('140601', '市轄區', '140600', '1');
INSERT INTO `ts_area` VALUES ('140602', '朔城區', '140600', '2');
INSERT INTO `ts_area` VALUES ('140603', '平魯區', '140600', '3');
INSERT INTO `ts_area` VALUES ('140621', '山陰縣', '140600', '4');
INSERT INTO `ts_area` VALUES ('140622', '應　縣', '140600', '5');
INSERT INTO `ts_area` VALUES ('140623', '右玉縣', '140600', '6');
INSERT INTO `ts_area` VALUES ('140624', '懷仁縣', '140600', '7');
INSERT INTO `ts_area` VALUES ('140701', '市轄區', '140700', '1');
INSERT INTO `ts_area` VALUES ('140702', '榆次區', '140700', '2');
INSERT INTO `ts_area` VALUES ('140721', '榆社縣', '140700', '3');
INSERT INTO `ts_area` VALUES ('140722', '左權縣', '140700', '4');
INSERT INTO `ts_area` VALUES ('140723', '和順縣', '140700', '5');
INSERT INTO `ts_area` VALUES ('140724', '昔陽縣', '140700', '6');
INSERT INTO `ts_area` VALUES ('140725', '壽陽縣', '140700', '7');
INSERT INTO `ts_area` VALUES ('140726', '太谷縣', '140700', '8');
INSERT INTO `ts_area` VALUES ('140727', '祁　縣', '140700', '9');
INSERT INTO `ts_area` VALUES ('140728', '平遙縣', '140700', '10');
INSERT INTO `ts_area` VALUES ('140729', '靈石縣', '140700', '11');
INSERT INTO `ts_area` VALUES ('140781', '介休市', '140700', '12');
INSERT INTO `ts_area` VALUES ('140801', '市轄區', '140800', '1');
INSERT INTO `ts_area` VALUES ('140802', '鹽湖區', '140800', '2');
INSERT INTO `ts_area` VALUES ('140821', '臨猗縣', '140800', '3');
INSERT INTO `ts_area` VALUES ('140822', '萬榮縣', '140800', '4');
INSERT INTO `ts_area` VALUES ('140823', '聞喜縣', '140800', '5');
INSERT INTO `ts_area` VALUES ('140824', '稷山縣', '140800', '6');
INSERT INTO `ts_area` VALUES ('140825', '新絳縣', '140800', '7');
INSERT INTO `ts_area` VALUES ('140826', '絳　縣', '140800', '8');
INSERT INTO `ts_area` VALUES ('140827', '垣曲縣', '140800', '9');
INSERT INTO `ts_area` VALUES ('140828', '夏　縣', '140800', '10');
INSERT INTO `ts_area` VALUES ('140829', '平陸縣', '140800', '11');
INSERT INTO `ts_area` VALUES ('140830', '芮城縣', '140800', '12');
INSERT INTO `ts_area` VALUES ('140881', '永濟市', '140800', '13');
INSERT INTO `ts_area` VALUES ('140882', '河津市', '140800', '14');
INSERT INTO `ts_area` VALUES ('140901', '市轄區', '140900', '1');
INSERT INTO `ts_area` VALUES ('140902', '忻府區', '140900', '2');
INSERT INTO `ts_area` VALUES ('140921', '定襄縣', '140900', '3');
INSERT INTO `ts_area` VALUES ('140922', '五臺縣', '140900', '4');
INSERT INTO `ts_area` VALUES ('140923', '代　縣', '140900', '5');
INSERT INTO `ts_area` VALUES ('140924', '繁峙縣', '140900', '6');
INSERT INTO `ts_area` VALUES ('140925', '寧武縣', '140900', '7');
INSERT INTO `ts_area` VALUES ('140926', '靜樂縣', '140900', '8');
INSERT INTO `ts_area` VALUES ('140927', '神池縣', '140900', '9');
INSERT INTO `ts_area` VALUES ('140928', '五寨縣', '140900', '10');
INSERT INTO `ts_area` VALUES ('140929', '岢嵐縣', '140900', '11');
INSERT INTO `ts_area` VALUES ('140930', '河曲縣', '140900', '12');
INSERT INTO `ts_area` VALUES ('140931', '保德縣', '140900', '13');
INSERT INTO `ts_area` VALUES ('140932', '偏關縣', '140900', '14');
INSERT INTO `ts_area` VALUES ('140981', '原平市', '140900', '15');
INSERT INTO `ts_area` VALUES ('141001', '市轄區', '141000', '1');
INSERT INTO `ts_area` VALUES ('141002', '堯都區', '141000', '2');
INSERT INTO `ts_area` VALUES ('141021', '曲沃縣', '141000', '3');
INSERT INTO `ts_area` VALUES ('141022', '翼城縣', '141000', '4');
INSERT INTO `ts_area` VALUES ('141023', '襄汾縣', '141000', '5');
INSERT INTO `ts_area` VALUES ('141024', '洪洞縣', '141000', '6');
INSERT INTO `ts_area` VALUES ('141025', '古　縣', '141000', '7');
INSERT INTO `ts_area` VALUES ('141026', '安澤縣', '141000', '8');
INSERT INTO `ts_area` VALUES ('141027', '浮山縣', '141000', '9');
INSERT INTO `ts_area` VALUES ('141028', '吉　縣', '141000', '10');
INSERT INTO `ts_area` VALUES ('141029', '鄉寧縣', '141000', '11');
INSERT INTO `ts_area` VALUES ('141030', '大寧縣', '141000', '12');
INSERT INTO `ts_area` VALUES ('141031', '隰　縣', '141000', '13');
INSERT INTO `ts_area` VALUES ('141032', '永和縣', '141000', '14');
INSERT INTO `ts_area` VALUES ('141033', '蒲　縣', '141000', '15');
INSERT INTO `ts_area` VALUES ('141034', '汾西縣', '141000', '16');
INSERT INTO `ts_area` VALUES ('141081', '侯馬市', '141000', '17');
INSERT INTO `ts_area` VALUES ('141082', '霍州市', '141000', '18');
INSERT INTO `ts_area` VALUES ('141101', '市轄區', '141100', '1');
INSERT INTO `ts_area` VALUES ('141102', '離石區', '141100', '2');
INSERT INTO `ts_area` VALUES ('141121', '文水縣', '141100', '3');
INSERT INTO `ts_area` VALUES ('141122', '交城縣', '141100', '4');
INSERT INTO `ts_area` VALUES ('141123', '興　縣', '141100', '5');
INSERT INTO `ts_area` VALUES ('141124', '臨　縣', '141100', '6');
INSERT INTO `ts_area` VALUES ('141125', '柳林縣', '141100', '7');
INSERT INTO `ts_area` VALUES ('141126', '石樓縣', '141100', '8');
INSERT INTO `ts_area` VALUES ('141127', '嵐　縣', '141100', '9');
INSERT INTO `ts_area` VALUES ('141128', '方山縣', '141100', '10');
INSERT INTO `ts_area` VALUES ('141129', '中陽縣', '141100', '11');
INSERT INTO `ts_area` VALUES ('141130', '交口縣', '141100', '12');
INSERT INTO `ts_area` VALUES ('141181', '孝義市', '141100', '13');
INSERT INTO `ts_area` VALUES ('141182', '汾陽市', '141100', '14');
INSERT INTO `ts_area` VALUES ('150101', '市轄區', '150100', '1');
INSERT INTO `ts_area` VALUES ('150102', '新城區', '150100', '2');
INSERT INTO `ts_area` VALUES ('150103', '回民區', '150100', '3');
INSERT INTO `ts_area` VALUES ('150104', '玉泉區', '150100', '4');
INSERT INTO `ts_area` VALUES ('150105', '賽罕區', '150100', '5');
INSERT INTO `ts_area` VALUES ('150121', '土默特左旗', '150100', '6');
INSERT INTO `ts_area` VALUES ('150122', '托克托縣', '150100', '7');
INSERT INTO `ts_area` VALUES ('150123', '和林格爾縣', '150100', '8');
INSERT INTO `ts_area` VALUES ('150124', '清水河縣', '150100', '9');
INSERT INTO `ts_area` VALUES ('150125', '武川縣', '150100', '10');
INSERT INTO `ts_area` VALUES ('150201', '市轄區', '150200', '1');
INSERT INTO `ts_area` VALUES ('150202', '東河區', '150200', '2');
INSERT INTO `ts_area` VALUES ('150203', '昆都侖區', '150200', '3');
INSERT INTO `ts_area` VALUES ('150204', '青山區', '150200', '4');
INSERT INTO `ts_area` VALUES ('150205', '石柺區', '150200', '5');
INSERT INTO `ts_area` VALUES ('150206', '白雲礦區', '150200', '6');
INSERT INTO `ts_area` VALUES ('150207', '九原區', '150200', '7');
INSERT INTO `ts_area` VALUES ('150221', '土默特右旗', '150200', '8');
INSERT INTO `ts_area` VALUES ('150222', '固陽縣', '150200', '9');
INSERT INTO `ts_area` VALUES ('150223', '達爾罕茂明安聯合旗', '150200', '10');
INSERT INTO `ts_area` VALUES ('150301', '市轄區', '150300', '1');
INSERT INTO `ts_area` VALUES ('150302', '海勃灣區', '150300', '2');
INSERT INTO `ts_area` VALUES ('150303', '海南區', '150300', '3');
INSERT INTO `ts_area` VALUES ('150304', '烏達區', '150300', '4');
INSERT INTO `ts_area` VALUES ('150401', '市轄區', '150400', '1');
INSERT INTO `ts_area` VALUES ('150402', '紅山區', '150400', '2');
INSERT INTO `ts_area` VALUES ('150403', '元寶山區', '150400', '3');
INSERT INTO `ts_area` VALUES ('150404', '松山區', '150400', '4');
INSERT INTO `ts_area` VALUES ('150421', '阿魯科爾沁旗', '150400', '5');
INSERT INTO `ts_area` VALUES ('150422', '巴林左旗', '150400', '6');
INSERT INTO `ts_area` VALUES ('150423', '巴林右旗', '150400', '7');
INSERT INTO `ts_area` VALUES ('150424', '林西縣', '150400', '8');
INSERT INTO `ts_area` VALUES ('150425', '克什克騰旗', '150400', '9');
INSERT INTO `ts_area` VALUES ('150426', '翁牛特旗', '150400', '10');
INSERT INTO `ts_area` VALUES ('150428', '喀喇沁旗', '150400', '11');
INSERT INTO `ts_area` VALUES ('150429', '寧城縣', '150400', '12');
INSERT INTO `ts_area` VALUES ('150430', '敖漢旗', '150400', '13');
INSERT INTO `ts_area` VALUES ('150501', '市轄區', '150500', '1');
INSERT INTO `ts_area` VALUES ('150502', '科爾沁區', '150500', '2');
INSERT INTO `ts_area` VALUES ('150521', '科爾沁左翼中旗', '150500', '3');
INSERT INTO `ts_area` VALUES ('150522', '科爾沁左翼後旗', '150500', '4');
INSERT INTO `ts_area` VALUES ('150523', '開魯縣', '150500', '5');
INSERT INTO `ts_area` VALUES ('150524', '庫倫旗', '150500', '6');
INSERT INTO `ts_area` VALUES ('150525', '奈曼旗', '150500', '7');
INSERT INTO `ts_area` VALUES ('150526', '扎魯特旗', '150500', '8');
INSERT INTO `ts_area` VALUES ('150581', '霍林郭勒市', '150500', '9');
INSERT INTO `ts_area` VALUES ('150602', '東勝區', '150600', '1');
INSERT INTO `ts_area` VALUES ('150621', '達拉特旗', '150600', '2');
INSERT INTO `ts_area` VALUES ('150622', '準格爾旗', '150600', '3');
INSERT INTO `ts_area` VALUES ('150623', '鄂托克前旗', '150600', '4');
INSERT INTO `ts_area` VALUES ('150624', '鄂托克旗', '150600', '5');
INSERT INTO `ts_area` VALUES ('150625', '杭錦旗', '150600', '6');
INSERT INTO `ts_area` VALUES ('150626', '烏審旗', '150600', '7');
INSERT INTO `ts_area` VALUES ('150627', '伊金霍洛旗', '150600', '8');
INSERT INTO `ts_area` VALUES ('150701', '市轄區', '150700', '1');
INSERT INTO `ts_area` VALUES ('150702', '海拉爾區', '150700', '2');
INSERT INTO `ts_area` VALUES ('150721', '阿榮旗', '150700', '3');
INSERT INTO `ts_area` VALUES ('150722', '莫力達瓦達斡爾族自治旗', '150700', '4');
INSERT INTO `ts_area` VALUES ('150723', '鄂倫春自治旗', '150700', '5');
INSERT INTO `ts_area` VALUES ('150724', '鄂溫克族自治旗', '150700', '6');
INSERT INTO `ts_area` VALUES ('150725', '陳巴爾虎旗', '150700', '7');
INSERT INTO `ts_area` VALUES ('150726', '新巴爾虎左旗', '150700', '8');
INSERT INTO `ts_area` VALUES ('150727', '新巴爾虎右旗', '150700', '9');
INSERT INTO `ts_area` VALUES ('150781', '滿洲裡市', '150700', '10');
INSERT INTO `ts_area` VALUES ('150782', '牙克石市', '150700', '11');
INSERT INTO `ts_area` VALUES ('150783', '扎蘭屯市', '150700', '12');
INSERT INTO `ts_area` VALUES ('150784', '額爾古納市', '150700', '13');
INSERT INTO `ts_area` VALUES ('150785', '根河市', '150700', '14');
INSERT INTO `ts_area` VALUES ('150801', '市轄區', '150800', '1');
INSERT INTO `ts_area` VALUES ('150802', '臨河區', '150800', '2');
INSERT INTO `ts_area` VALUES ('150821', '五原縣', '150800', '3');
INSERT INTO `ts_area` VALUES ('150822', '磴口縣', '150800', '4');
INSERT INTO `ts_area` VALUES ('150823', '烏拉特前旗', '150800', '5');
INSERT INTO `ts_area` VALUES ('150824', '烏拉特中旗', '150800', '6');
INSERT INTO `ts_area` VALUES ('150825', '烏拉特後旗', '150800', '7');
INSERT INTO `ts_area` VALUES ('150826', '杭錦後旗', '150800', '8');
INSERT INTO `ts_area` VALUES ('150901', '市轄區', '150900', '1');
INSERT INTO `ts_area` VALUES ('150902', '集寧區', '150900', '2');
INSERT INTO `ts_area` VALUES ('150921', '卓資縣', '150900', '3');
INSERT INTO `ts_area` VALUES ('150922', '化德縣', '150900', '4');
INSERT INTO `ts_area` VALUES ('150923', '商都縣', '150900', '5');
INSERT INTO `ts_area` VALUES ('150924', '興和縣', '150900', '6');
INSERT INTO `ts_area` VALUES ('150925', '涼城縣', '150900', '7');
INSERT INTO `ts_area` VALUES ('150926', '察哈爾右翼前旗', '150900', '8');
INSERT INTO `ts_area` VALUES ('150927', '察哈爾右翼中旗', '150900', '9');
INSERT INTO `ts_area` VALUES ('150928', '察哈爾右翼後旗', '150900', '10');
INSERT INTO `ts_area` VALUES ('150929', '四子王旗', '150900', '11');
INSERT INTO `ts_area` VALUES ('150981', '豐鎮市', '150900', '12');
INSERT INTO `ts_area` VALUES ('152201', '烏蘭浩特市', '152200', '1');
INSERT INTO `ts_area` VALUES ('152202', '阿爾山市', '152200', '2');
INSERT INTO `ts_area` VALUES ('152221', '科爾沁右翼前旗', '152200', '3');
INSERT INTO `ts_area` VALUES ('152222', '科爾沁右翼中旗', '152200', '4');
INSERT INTO `ts_area` VALUES ('152223', '扎賚特旗', '152200', '5');
INSERT INTO `ts_area` VALUES ('152224', '突泉縣', '152200', '6');
INSERT INTO `ts_area` VALUES ('152501', '二連浩特市', '152500', '1');
INSERT INTO `ts_area` VALUES ('152502', '錫林浩特市', '152500', '2');
INSERT INTO `ts_area` VALUES ('152522', '阿巴嘎旗', '152500', '3');
INSERT INTO `ts_area` VALUES ('152523', '蘇尼特左旗', '152500', '4');
INSERT INTO `ts_area` VALUES ('152524', '蘇尼特右旗', '152500', '5');
INSERT INTO `ts_area` VALUES ('152525', '東烏珠穆沁旗', '152500', '6');
INSERT INTO `ts_area` VALUES ('152526', '西烏珠穆沁旗', '152500', '7');
INSERT INTO `ts_area` VALUES ('152527', '太僕寺旗', '152500', '8');
INSERT INTO `ts_area` VALUES ('152528', '鑲黃旗', '152500', '9');
INSERT INTO `ts_area` VALUES ('152529', '正鑲白旗', '152500', '10');
INSERT INTO `ts_area` VALUES ('152530', '正藍旗', '152500', '11');
INSERT INTO `ts_area` VALUES ('152531', '多倫縣', '152500', '12');
INSERT INTO `ts_area` VALUES ('152921', '阿拉善左旗', '152900', '1');
INSERT INTO `ts_area` VALUES ('152922', '阿拉善右旗', '152900', '2');
INSERT INTO `ts_area` VALUES ('152923', '額濟納旗', '152900', '3');
INSERT INTO `ts_area` VALUES ('210101', '市轄區', '210100', '1');
INSERT INTO `ts_area` VALUES ('210102', '和平區', '210100', '2');
INSERT INTO `ts_area` VALUES ('210103', '瀋河區', '210100', '3');
INSERT INTO `ts_area` VALUES ('210104', '大東區', '210100', '4');
INSERT INTO `ts_area` VALUES ('210105', '皇姑區', '210100', '5');
INSERT INTO `ts_area` VALUES ('210106', '鐵西區', '210100', '6');
INSERT INTO `ts_area` VALUES ('210111', '甦家屯區', '210100', '7');
INSERT INTO `ts_area` VALUES ('210112', '東陵區', '210100', '8');
INSERT INTO `ts_area` VALUES ('210113', '新城子區', '210100', '9');
INSERT INTO `ts_area` VALUES ('210114', '于洪區', '210100', '10');
INSERT INTO `ts_area` VALUES ('210122', '遼中縣', '210100', '11');
INSERT INTO `ts_area` VALUES ('210123', '康平縣', '210100', '12');
INSERT INTO `ts_area` VALUES ('210124', '法庫縣', '210100', '13');
INSERT INTO `ts_area` VALUES ('210181', '新民市', '210100', '14');
INSERT INTO `ts_area` VALUES ('210201', '市轄區', '210200', '1');
INSERT INTO `ts_area` VALUES ('210202', '中山區', '210200', '2');
INSERT INTO `ts_area` VALUES ('210203', '西崗區', '210200', '3');
INSERT INTO `ts_area` VALUES ('210204', '沙河口區', '210200', '4');
INSERT INTO `ts_area` VALUES ('210211', '甘井子區', '210200', '5');
INSERT INTO `ts_area` VALUES ('210212', '旅順口區', '210200', '6');
INSERT INTO `ts_area` VALUES ('210213', '金州區', '210200', '7');
INSERT INTO `ts_area` VALUES ('210224', '長海縣', '210200', '8');
INSERT INTO `ts_area` VALUES ('210281', '瓦房店市', '210200', '9');
INSERT INTO `ts_area` VALUES ('210282', '普蘭店市', '210200', '10');
INSERT INTO `ts_area` VALUES ('210283', '莊河市', '210200', '11');
INSERT INTO `ts_area` VALUES ('210301', '市轄區', '210300', '1');
INSERT INTO `ts_area` VALUES ('210302', '鐵東區', '210300', '2');
INSERT INTO `ts_area` VALUES ('210303', '鐵西區', '210300', '3');
INSERT INTO `ts_area` VALUES ('210304', '立山區', '210300', '4');
INSERT INTO `ts_area` VALUES ('210311', '千山區', '210300', '5');
INSERT INTO `ts_area` VALUES ('210321', '檯安縣', '210300', '6');
INSERT INTO `ts_area` VALUES ('210323', '岫巖滿族自治縣', '210300', '7');
INSERT INTO `ts_area` VALUES ('210381', '海城市', '210300', '8');
INSERT INTO `ts_area` VALUES ('210401', '市轄區', '210400', '1');
INSERT INTO `ts_area` VALUES ('210402', '新撫區', '210400', '2');
INSERT INTO `ts_area` VALUES ('210403', '東洲區', '210400', '3');
INSERT INTO `ts_area` VALUES ('210404', '望花區', '210400', '4');
INSERT INTO `ts_area` VALUES ('210411', '順城區', '210400', '5');
INSERT INTO `ts_area` VALUES ('210421', '撫順縣', '210400', '6');
INSERT INTO `ts_area` VALUES ('210422', '新賓滿族自治縣', '210400', '7');
INSERT INTO `ts_area` VALUES ('210423', '清原滿族自治縣', '210400', '8');
INSERT INTO `ts_area` VALUES ('210501', '市轄區', '210500', '1');
INSERT INTO `ts_area` VALUES ('210502', '平山區', '210500', '2');
INSERT INTO `ts_area` VALUES ('210503', '溪湖區', '210500', '3');
INSERT INTO `ts_area` VALUES ('210504', '明山區', '210500', '4');
INSERT INTO `ts_area` VALUES ('210505', '南芬區', '210500', '5');
INSERT INTO `ts_area` VALUES ('210521', '本溪滿族自治縣', '210500', '6');
INSERT INTO `ts_area` VALUES ('210522', '桓仁滿族自治縣', '210500', '7');
INSERT INTO `ts_area` VALUES ('210601', '市轄區', '210600', '1');
INSERT INTO `ts_area` VALUES ('210602', '元寶區', '210600', '2');
INSERT INTO `ts_area` VALUES ('210603', '振興區', '210600', '3');
INSERT INTO `ts_area` VALUES ('210604', '振安區', '210600', '4');
INSERT INTO `ts_area` VALUES ('210624', '寬甸滿族自治縣', '210600', '5');
INSERT INTO `ts_area` VALUES ('210681', '東港市', '210600', '6');
INSERT INTO `ts_area` VALUES ('210682', '鳳城市', '210600', '7');
INSERT INTO `ts_area` VALUES ('210701', '市轄區', '210700', '1');
INSERT INTO `ts_area` VALUES ('210702', '古塔區', '210700', '2');
INSERT INTO `ts_area` VALUES ('210703', '淩河區', '210700', '3');
INSERT INTO `ts_area` VALUES ('210711', '太和區', '210700', '4');
INSERT INTO `ts_area` VALUES ('210726', '黑山縣', '210700', '5');
INSERT INTO `ts_area` VALUES ('210727', '義　縣', '210700', '6');
INSERT INTO `ts_area` VALUES ('210781', '凌海市', '210700', '7');
INSERT INTO `ts_area` VALUES ('210782', '北寧市', '210700', '8');
INSERT INTO `ts_area` VALUES ('210801', '市轄區', '210800', '1');
INSERT INTO `ts_area` VALUES ('210802', '站前區', '210800', '2');
INSERT INTO `ts_area` VALUES ('210803', '西市區', '210800', '3');
INSERT INTO `ts_area` VALUES ('210804', '鮁魚圈區', '210800', '4');
INSERT INTO `ts_area` VALUES ('210811', '老邊區', '210800', '5');
INSERT INTO `ts_area` VALUES ('210881', '蓋州市', '210800', '6');
INSERT INTO `ts_area` VALUES ('210882', '大石橋市', '210800', '7');
INSERT INTO `ts_area` VALUES ('210901', '市轄區', '210900', '1');
INSERT INTO `ts_area` VALUES ('210902', '海州區', '210900', '2');
INSERT INTO `ts_area` VALUES ('210903', '新邱區', '210900', '3');
INSERT INTO `ts_area` VALUES ('210904', '太平區', '210900', '4');
INSERT INTO `ts_area` VALUES ('210905', '清河門區', '210900', '5');
INSERT INTO `ts_area` VALUES ('210911', '細河區', '210900', '6');
INSERT INTO `ts_area` VALUES ('210921', '阜新蒙古族自治縣', '210900', '7');
INSERT INTO `ts_area` VALUES ('210922', '彰武縣', '210900', '8');
INSERT INTO `ts_area` VALUES ('211001', '市轄區', '211000', '1');
INSERT INTO `ts_area` VALUES ('211002', '白塔區', '211000', '2');
INSERT INTO `ts_area` VALUES ('211003', '文聖區', '211000', '3');
INSERT INTO `ts_area` VALUES ('211004', '宏偉區', '211000', '4');
INSERT INTO `ts_area` VALUES ('211005', '弓長嶺區', '211000', '5');
INSERT INTO `ts_area` VALUES ('211011', '太子河區', '211000', '6');
INSERT INTO `ts_area` VALUES ('211021', '遼陽縣', '211000', '7');
INSERT INTO `ts_area` VALUES ('211081', '燈塔市', '211000', '8');
INSERT INTO `ts_area` VALUES ('211101', '市轄區', '211100', '1');
INSERT INTO `ts_area` VALUES ('211102', '雙臺子區', '211100', '2');
INSERT INTO `ts_area` VALUES ('211103', '興隆臺區', '211100', '3');
INSERT INTO `ts_area` VALUES ('211121', '大窪縣', '211100', '4');
INSERT INTO `ts_area` VALUES ('211122', '盤山縣', '211100', '5');
INSERT INTO `ts_area` VALUES ('211201', '市轄區', '211200', '1');
INSERT INTO `ts_area` VALUES ('211202', '銀州區', '211200', '2');
INSERT INTO `ts_area` VALUES ('211204', '清河區', '211200', '3');
INSERT INTO `ts_area` VALUES ('211221', '鐵嶺縣', '211200', '4');
INSERT INTO `ts_area` VALUES ('211223', '西豐縣', '211200', '5');
INSERT INTO `ts_area` VALUES ('211224', '昌圖縣', '211200', '6');
INSERT INTO `ts_area` VALUES ('211281', '調兵山市', '211200', '7');
INSERT INTO `ts_area` VALUES ('211282', '開原市', '211200', '8');
INSERT INTO `ts_area` VALUES ('211301', '市轄區', '211300', '1');
INSERT INTO `ts_area` VALUES ('211302', '雙塔區', '211300', '2');
INSERT INTO `ts_area` VALUES ('211303', '龍城區', '211300', '3');
INSERT INTO `ts_area` VALUES ('211321', '朝陽縣', '211300', '4');
INSERT INTO `ts_area` VALUES ('211322', '建平縣', '211300', '5');
INSERT INTO `ts_area` VALUES ('211324', '喀喇沁左翼蒙古族自治縣', '211300', '6');
INSERT INTO `ts_area` VALUES ('211381', '北票市', '211300', '7');
INSERT INTO `ts_area` VALUES ('211382', '凌源市', '211300', '8');
INSERT INTO `ts_area` VALUES ('211401', '市轄區', '211400', '1');
INSERT INTO `ts_area` VALUES ('211402', '連山區', '211400', '2');
INSERT INTO `ts_area` VALUES ('211403', '龍港區', '211400', '3');
INSERT INTO `ts_area` VALUES ('211404', '南票區', '211400', '4');
INSERT INTO `ts_area` VALUES ('211421', '綏中縣', '211400', '5');
INSERT INTO `ts_area` VALUES ('211422', '建昌縣', '211400', '6');
INSERT INTO `ts_area` VALUES ('211481', '興城市', '211400', '7');
INSERT INTO `ts_area` VALUES ('220101', '市轄區', '220100', '1');
INSERT INTO `ts_area` VALUES ('220102', '南關區', '220100', '2');
INSERT INTO `ts_area` VALUES ('220103', '寬城區', '220100', '3');
INSERT INTO `ts_area` VALUES ('220104', '朝陽區', '220100', '4');
INSERT INTO `ts_area` VALUES ('220105', '二道區', '220100', '5');
INSERT INTO `ts_area` VALUES ('220106', '綠園區', '220100', '6');
INSERT INTO `ts_area` VALUES ('220112', '雙陽區', '220100', '7');
INSERT INTO `ts_area` VALUES ('220122', '農安縣', '220100', '8');
INSERT INTO `ts_area` VALUES ('220181', '九臺市', '220100', '9');
INSERT INTO `ts_area` VALUES ('220182', '榆樹市', '220100', '10');
INSERT INTO `ts_area` VALUES ('220183', '德惠市', '220100', '11');
INSERT INTO `ts_area` VALUES ('220201', '市轄區', '220200', '1');
INSERT INTO `ts_area` VALUES ('220202', '昌邑區', '220200', '2');
INSERT INTO `ts_area` VALUES ('220203', '龍潭區', '220200', '3');
INSERT INTO `ts_area` VALUES ('220204', '船營區', '220200', '4');
INSERT INTO `ts_area` VALUES ('220211', '豐滿區', '220200', '5');
INSERT INTO `ts_area` VALUES ('220221', '永吉縣', '220200', '6');
INSERT INTO `ts_area` VALUES ('220281', '蛟河市', '220200', '7');
INSERT INTO `ts_area` VALUES ('220282', '樺甸市', '220200', '8');
INSERT INTO `ts_area` VALUES ('220283', '舒蘭市', '220200', '9');
INSERT INTO `ts_area` VALUES ('220284', '磐石市', '220200', '10');
INSERT INTO `ts_area` VALUES ('220301', '市轄區', '220300', '1');
INSERT INTO `ts_area` VALUES ('220302', '鐵西區', '220300', '2');
INSERT INTO `ts_area` VALUES ('220303', '鐵東區', '220300', '3');
INSERT INTO `ts_area` VALUES ('220322', '梨樹縣', '220300', '4');
INSERT INTO `ts_area` VALUES ('220323', '伊通滿族自治縣', '220300', '5');
INSERT INTO `ts_area` VALUES ('220381', '公主嶺市', '220300', '6');
INSERT INTO `ts_area` VALUES ('220382', '雙遼市', '220300', '7');
INSERT INTO `ts_area` VALUES ('220401', '市轄區', '220400', '1');
INSERT INTO `ts_area` VALUES ('220402', '龍山區', '220400', '2');
INSERT INTO `ts_area` VALUES ('220403', '西安區', '220400', '3');
INSERT INTO `ts_area` VALUES ('220421', '東豐縣', '220400', '4');
INSERT INTO `ts_area` VALUES ('220422', '東遼縣', '220400', '5');
INSERT INTO `ts_area` VALUES ('220501', '市轄區', '220500', '1');
INSERT INTO `ts_area` VALUES ('220502', '東昌區', '220500', '2');
INSERT INTO `ts_area` VALUES ('220503', '二道江區', '220500', '3');
INSERT INTO `ts_area` VALUES ('220521', '通化縣', '220500', '4');
INSERT INTO `ts_area` VALUES ('220523', '輝南縣', '220500', '5');
INSERT INTO `ts_area` VALUES ('220524', '柳河縣', '220500', '6');
INSERT INTO `ts_area` VALUES ('220581', '梅河口市', '220500', '7');
INSERT INTO `ts_area` VALUES ('220582', '集安市', '220500', '8');
INSERT INTO `ts_area` VALUES ('220601', '市轄區', '220600', '1');
INSERT INTO `ts_area` VALUES ('220602', '八道江區', '220600', '2');
INSERT INTO `ts_area` VALUES ('220621', '撫松縣', '220600', '3');
INSERT INTO `ts_area` VALUES ('220622', '靖宇縣', '220600', '4');
INSERT INTO `ts_area` VALUES ('220623', '長白朝鮮族自治縣', '220600', '5');
INSERT INTO `ts_area` VALUES ('220625', '江源縣', '220600', '6');
INSERT INTO `ts_area` VALUES ('220681', '臨江市', '220600', '7');
INSERT INTO `ts_area` VALUES ('220701', '市轄區', '220700', '1');
INSERT INTO `ts_area` VALUES ('220702', '寧江區', '220700', '2');
INSERT INTO `ts_area` VALUES ('220721', '前郭爾羅斯蒙古族自治縣', '220700', '3');
INSERT INTO `ts_area` VALUES ('220722', '長嶺縣', '220700', '4');
INSERT INTO `ts_area` VALUES ('220723', '乾安縣', '220700', '5');
INSERT INTO `ts_area` VALUES ('220724', '扶余縣', '220700', '6');
INSERT INTO `ts_area` VALUES ('220801', '市轄區', '220800', '1');
INSERT INTO `ts_area` VALUES ('220802', '洮北區', '220800', '2');
INSERT INTO `ts_area` VALUES ('220821', '鎮賚縣', '220800', '3');
INSERT INTO `ts_area` VALUES ('220822', '通榆縣', '220800', '4');
INSERT INTO `ts_area` VALUES ('220881', '洮南市', '220800', '5');
INSERT INTO `ts_area` VALUES ('220882', '大安市', '220800', '6');
INSERT INTO `ts_area` VALUES ('222401', '延吉市', '222400', '1');
INSERT INTO `ts_area` VALUES ('222402', '圖們市', '222400', '2');
INSERT INTO `ts_area` VALUES ('222403', '敦化市', '222400', '3');
INSERT INTO `ts_area` VALUES ('222404', '琿春市', '222400', '4');
INSERT INTO `ts_area` VALUES ('222405', '龍井市', '222400', '5');
INSERT INTO `ts_area` VALUES ('222406', '和龍市', '222400', '6');
INSERT INTO `ts_area` VALUES ('222424', '汪清縣', '222400', '7');
INSERT INTO `ts_area` VALUES ('222426', '安圖縣', '222400', '8');
INSERT INTO `ts_area` VALUES ('230101', '市轄區', '230100', '1');
INSERT INTO `ts_area` VALUES ('230102', '道里區', '230100', '2');
INSERT INTO `ts_area` VALUES ('230103', '南崗區', '230100', '3');
INSERT INTO `ts_area` VALUES ('230104', '道外區', '230100', '4');
INSERT INTO `ts_area` VALUES ('230106', '香坊區', '230100', '5');
INSERT INTO `ts_area` VALUES ('230107', '動力區', '230100', '6');
INSERT INTO `ts_area` VALUES ('230108', '平房區', '230100', '7');
INSERT INTO `ts_area` VALUES ('230109', '松北區', '230100', '8');
INSERT INTO `ts_area` VALUES ('230111', '呼蘭區', '230100', '9');
INSERT INTO `ts_area` VALUES ('230123', '依蘭縣', '230100', '10');
INSERT INTO `ts_area` VALUES ('230124', '方正縣', '230100', '11');
INSERT INTO `ts_area` VALUES ('230125', '賓　縣', '230100', '12');
INSERT INTO `ts_area` VALUES ('230126', '巴彥縣', '230100', '13');
INSERT INTO `ts_area` VALUES ('230127', '木蘭縣', '230100', '14');
INSERT INTO `ts_area` VALUES ('230128', '通河縣', '230100', '15');
INSERT INTO `ts_area` VALUES ('230129', '延壽縣', '230100', '16');
INSERT INTO `ts_area` VALUES ('230181', '阿城市', '230100', '17');
INSERT INTO `ts_area` VALUES ('230182', '雙城市', '230100', '18');
INSERT INTO `ts_area` VALUES ('230183', '尚志市', '230100', '19');
INSERT INTO `ts_area` VALUES ('230184', '五常市', '230100', '20');
INSERT INTO `ts_area` VALUES ('230201', '市轄區', '230200', '1');
INSERT INTO `ts_area` VALUES ('230202', '龍沙區', '230200', '2');
INSERT INTO `ts_area` VALUES ('230203', '建華區', '230200', '3');
INSERT INTO `ts_area` VALUES ('230204', '鐵鋒區', '230200', '4');
INSERT INTO `ts_area` VALUES ('230205', '昂昂溪區', '230200', '5');
INSERT INTO `ts_area` VALUES ('230206', '富拉爾基區', '230200', '6');
INSERT INTO `ts_area` VALUES ('230207', '碾子山區', '230200', '7');
INSERT INTO `ts_area` VALUES ('230208', '梅里斯達斡爾族區', '230200', '8');
INSERT INTO `ts_area` VALUES ('230221', '龍江縣', '230200', '9');
INSERT INTO `ts_area` VALUES ('230223', '依安縣', '230200', '10');
INSERT INTO `ts_area` VALUES ('230224', '泰來縣', '230200', '11');
INSERT INTO `ts_area` VALUES ('230225', '甘南縣', '230200', '12');
INSERT INTO `ts_area` VALUES ('230227', '富裕縣', '230200', '13');
INSERT INTO `ts_area` VALUES ('230229', '克山縣', '230200', '14');
INSERT INTO `ts_area` VALUES ('230230', '克東縣', '230200', '15');
INSERT INTO `ts_area` VALUES ('230231', '拜泉縣', '230200', '16');
INSERT INTO `ts_area` VALUES ('230281', '訥河市', '230200', '17');
INSERT INTO `ts_area` VALUES ('230301', '市轄區', '230300', '1');
INSERT INTO `ts_area` VALUES ('230302', '雞冠區', '230300', '2');
INSERT INTO `ts_area` VALUES ('230303', '恆山區', '230300', '3');
INSERT INTO `ts_area` VALUES ('230304', '滴道區', '230300', '4');
INSERT INTO `ts_area` VALUES ('230305', '梨樹區', '230300', '5');
INSERT INTO `ts_area` VALUES ('230306', '城子河區', '230300', '6');
INSERT INTO `ts_area` VALUES ('230307', '麻山區', '230300', '7');
INSERT INTO `ts_area` VALUES ('230321', '雞東縣', '230300', '8');
INSERT INTO `ts_area` VALUES ('230381', '虎林市', '230300', '9');
INSERT INTO `ts_area` VALUES ('230382', '密山市', '230300', '10');
INSERT INTO `ts_area` VALUES ('230401', '市轄區', '230400', '1');
INSERT INTO `ts_area` VALUES ('230402', '向陽區', '230400', '2');
INSERT INTO `ts_area` VALUES ('230403', '工農區', '230400', '3');
INSERT INTO `ts_area` VALUES ('230404', '南山區', '230400', '4');
INSERT INTO `ts_area` VALUES ('230405', '興安區', '230400', '5');
INSERT INTO `ts_area` VALUES ('230406', '東山區', '230400', '6');
INSERT INTO `ts_area` VALUES ('230407', '興山區', '230400', '7');
INSERT INTO `ts_area` VALUES ('230421', '蘿北縣', '230400', '8');
INSERT INTO `ts_area` VALUES ('230422', '綏濱縣', '230400', '9');
INSERT INTO `ts_area` VALUES ('230501', '市轄區', '230500', '1');
INSERT INTO `ts_area` VALUES ('230502', '尖山區', '230500', '2');
INSERT INTO `ts_area` VALUES ('230503', '嶺東區', '230500', '3');
INSERT INTO `ts_area` VALUES ('230505', '四方臺區', '230500', '4');
INSERT INTO `ts_area` VALUES ('230506', '寶山區', '230500', '5');
INSERT INTO `ts_area` VALUES ('230521', '集賢縣', '230500', '6');
INSERT INTO `ts_area` VALUES ('230522', '友誼縣', '230500', '7');
INSERT INTO `ts_area` VALUES ('230523', '寶清縣', '230500', '8');
INSERT INTO `ts_area` VALUES ('230524', '饒河縣', '230500', '9');
INSERT INTO `ts_area` VALUES ('230601', '市轄區', '230600', '1');
INSERT INTO `ts_area` VALUES ('230602', '薩爾圖區', '230600', '2');
INSERT INTO `ts_area` VALUES ('230603', '龍鳳區', '230600', '3');
INSERT INTO `ts_area` VALUES ('230604', '讓胡路區', '230600', '4');
INSERT INTO `ts_area` VALUES ('230605', '紅崗區', '230600', '5');
INSERT INTO `ts_area` VALUES ('230606', '大同區', '230600', '6');
INSERT INTO `ts_area` VALUES ('230621', '肇州縣', '230600', '7');
INSERT INTO `ts_area` VALUES ('230622', '肇源縣', '230600', '8');
INSERT INTO `ts_area` VALUES ('230623', '林甸縣', '230600', '9');
INSERT INTO `ts_area` VALUES ('230624', '杜爾伯特蒙古族自治縣', '230600', '10');
INSERT INTO `ts_area` VALUES ('230701', '市轄區', '230700', '1');
INSERT INTO `ts_area` VALUES ('230702', '伊春區', '230700', '2');
INSERT INTO `ts_area` VALUES ('230703', '南岔區', '230700', '3');
INSERT INTO `ts_area` VALUES ('230704', '友好區', '230700', '4');
INSERT INTO `ts_area` VALUES ('230705', '西林區', '230700', '5');
INSERT INTO `ts_area` VALUES ('230706', '翠巒區', '230700', '6');
INSERT INTO `ts_area` VALUES ('230707', '新青區', '230700', '7');
INSERT INTO `ts_area` VALUES ('230708', '美溪區', '230700', '8');
INSERT INTO `ts_area` VALUES ('230709', '金山屯區', '230700', '9');
INSERT INTO `ts_area` VALUES ('230710', '五營區', '230700', '10');
INSERT INTO `ts_area` VALUES ('230711', '烏馬河區', '230700', '11');
INSERT INTO `ts_area` VALUES ('230712', '湯旺河區', '230700', '12');
INSERT INTO `ts_area` VALUES ('230713', '帶嶺區', '230700', '13');
INSERT INTO `ts_area` VALUES ('230714', '烏伊嶺區', '230700', '14');
INSERT INTO `ts_area` VALUES ('230715', '紅星區', '230700', '15');
INSERT INTO `ts_area` VALUES ('230716', '上甘嶺區', '230700', '16');
INSERT INTO `ts_area` VALUES ('230722', '嘉蔭縣', '230700', '17');
INSERT INTO `ts_area` VALUES ('230781', '鐵力市', '230700', '18');
INSERT INTO `ts_area` VALUES ('230801', '市轄區', '230800', '1');
INSERT INTO `ts_area` VALUES ('230802', '永紅區', '230800', '2');
INSERT INTO `ts_area` VALUES ('230803', '向陽區', '230800', '3');
INSERT INTO `ts_area` VALUES ('230804', '前進區', '230800', '4');
INSERT INTO `ts_area` VALUES ('230805', '東風區', '230800', '5');
INSERT INTO `ts_area` VALUES ('230811', '郊　區', '230800', '6');
INSERT INTO `ts_area` VALUES ('230822', '樺南縣', '230800', '7');
INSERT INTO `ts_area` VALUES ('230826', '樺川縣', '230800', '8');
INSERT INTO `ts_area` VALUES ('230828', '湯原縣', '230800', '9');
INSERT INTO `ts_area` VALUES ('230833', '撫遠縣', '230800', '10');
INSERT INTO `ts_area` VALUES ('230881', '同江市', '230800', '11');
INSERT INTO `ts_area` VALUES ('230882', '富錦市', '230800', '12');
INSERT INTO `ts_area` VALUES ('230901', '市轄區', '230900', '1');
INSERT INTO `ts_area` VALUES ('230902', '新興區', '230900', '2');
INSERT INTO `ts_area` VALUES ('230903', '桃山區', '230900', '3');
INSERT INTO `ts_area` VALUES ('230904', '茄子河區', '230900', '4');
INSERT INTO `ts_area` VALUES ('230921', '勃利縣', '230900', '5');
INSERT INTO `ts_area` VALUES ('231001', '市轄區', '231000', '1');
INSERT INTO `ts_area` VALUES ('231002', '東安區', '231000', '2');
INSERT INTO `ts_area` VALUES ('231003', '陽明區', '231000', '3');
INSERT INTO `ts_area` VALUES ('231004', '愛民區', '231000', '4');
INSERT INTO `ts_area` VALUES ('231005', '西安區', '231000', '5');
INSERT INTO `ts_area` VALUES ('231024', '東寧縣', '231000', '6');
INSERT INTO `ts_area` VALUES ('231025', '林口縣', '231000', '7');
INSERT INTO `ts_area` VALUES ('231081', '綏芬河市', '231000', '8');
INSERT INTO `ts_area` VALUES ('231083', '海林市', '231000', '9');
INSERT INTO `ts_area` VALUES ('231084', '寧安市', '231000', '10');
INSERT INTO `ts_area` VALUES ('231085', '穆稜市', '231000', '11');
INSERT INTO `ts_area` VALUES ('231101', '市轄區', '231100', '1');
INSERT INTO `ts_area` VALUES ('231102', '愛輝區', '231100', '2');
INSERT INTO `ts_area` VALUES ('231121', '嫩江縣', '231100', '3');
INSERT INTO `ts_area` VALUES ('231123', '遜克縣', '231100', '4');
INSERT INTO `ts_area` VALUES ('231124', '孫吳縣', '231100', '5');
INSERT INTO `ts_area` VALUES ('231181', '北安市', '231100', '6');
INSERT INTO `ts_area` VALUES ('231182', '五大連池市', '231100', '7');
INSERT INTO `ts_area` VALUES ('231201', '市轄區', '231200', '1');
INSERT INTO `ts_area` VALUES ('231202', '北林區', '231200', '2');
INSERT INTO `ts_area` VALUES ('231221', '望奎縣', '231200', '3');
INSERT INTO `ts_area` VALUES ('231222', '蘭西縣', '231200', '4');
INSERT INTO `ts_area` VALUES ('231223', '青岡縣', '231200', '5');
INSERT INTO `ts_area` VALUES ('231224', '慶安縣', '231200', '6');
INSERT INTO `ts_area` VALUES ('231225', '明水縣', '231200', '7');
INSERT INTO `ts_area` VALUES ('231226', '綏稜縣', '231200', '8');
INSERT INTO `ts_area` VALUES ('231281', '安達市', '231200', '9');
INSERT INTO `ts_area` VALUES ('231282', '肇東市', '231200', '10');
INSERT INTO `ts_area` VALUES ('231283', '海倫市', '231200', '11');
INSERT INTO `ts_area` VALUES ('232721', '呼瑪縣', '232700', '1');
INSERT INTO `ts_area` VALUES ('232722', '塔河縣', '232700', '2');
INSERT INTO `ts_area` VALUES ('232723', '漠河縣', '232700', '3');
INSERT INTO `ts_area` VALUES ('310101', '黃浦區', '310100', '1');
INSERT INTO `ts_area` VALUES ('310103', '盧灣區', '310100', '2');
INSERT INTO `ts_area` VALUES ('310104', '徐彙區', '310100', '3');
INSERT INTO `ts_area` VALUES ('310105', '長寧區', '310100', '4');
INSERT INTO `ts_area` VALUES ('310106', '靜安區', '310100', '5');
INSERT INTO `ts_area` VALUES ('310107', '普陀區', '310100', '6');
INSERT INTO `ts_area` VALUES ('310108', '閘北區', '310100', '7');
INSERT INTO `ts_area` VALUES ('310109', '虹口區', '310100', '8');
INSERT INTO `ts_area` VALUES ('310110', '楊浦區', '310100', '9');
INSERT INTO `ts_area` VALUES ('310112', '閔行區', '310100', '10');
INSERT INTO `ts_area` VALUES ('310113', '寶山區', '310100', '11');
INSERT INTO `ts_area` VALUES ('310114', '嘉定區', '310100', '12');
INSERT INTO `ts_area` VALUES ('310115', '浦東新區', '310100', '13');
INSERT INTO `ts_area` VALUES ('310116', '金山區', '310100', '14');
INSERT INTO `ts_area` VALUES ('310117', '松江區', '310100', '15');
INSERT INTO `ts_area` VALUES ('310118', '青浦區', '310100', '16');
INSERT INTO `ts_area` VALUES ('310119', '南匯區', '310100', '17');
INSERT INTO `ts_area` VALUES ('310120', '奉賢區', '310100', '18');
INSERT INTO `ts_area` VALUES ('310230', '崇明縣', '310200', '1');
INSERT INTO `ts_area` VALUES ('320101', '市轄區', '320100', '1');
INSERT INTO `ts_area` VALUES ('320102', '玄武區', '320100', '2');
INSERT INTO `ts_area` VALUES ('320103', '白下區', '320100', '3');
INSERT INTO `ts_area` VALUES ('320104', '秦淮區', '320100', '4');
INSERT INTO `ts_area` VALUES ('320105', '建鄴區', '320100', '5');
INSERT INTO `ts_area` VALUES ('320106', '鼓樓區', '320100', '6');
INSERT INTO `ts_area` VALUES ('320107', '下關區', '320100', '7');
INSERT INTO `ts_area` VALUES ('320111', '浦口區', '320100', '8');
INSERT INTO `ts_area` VALUES ('320113', '棲霞區', '320100', '9');
INSERT INTO `ts_area` VALUES ('320114', '雨花臺區', '320100', '10');
INSERT INTO `ts_area` VALUES ('320115', '江寧區', '320100', '11');
INSERT INTO `ts_area` VALUES ('320116', '六合區', '320100', '12');
INSERT INTO `ts_area` VALUES ('320124', '溧水縣', '320100', '13');
INSERT INTO `ts_area` VALUES ('320125', '高淳縣', '320100', '14');
INSERT INTO `ts_area` VALUES ('320201', '市轄區', '320200', '1');
INSERT INTO `ts_area` VALUES ('320202', '崇安區', '320200', '2');
INSERT INTO `ts_area` VALUES ('320203', '南長區', '320200', '3');
INSERT INTO `ts_area` VALUES ('320204', '北塘區', '320200', '4');
INSERT INTO `ts_area` VALUES ('320205', '錫山區', '320200', '5');
INSERT INTO `ts_area` VALUES ('320206', '惠山區', '320200', '6');
INSERT INTO `ts_area` VALUES ('320211', '濱湖區', '320200', '7');
INSERT INTO `ts_area` VALUES ('320281', '江陰市', '320200', '8');
INSERT INTO `ts_area` VALUES ('320282', '宜興市', '320200', '9');
INSERT INTO `ts_area` VALUES ('320301', '市轄區', '320300', '1');
INSERT INTO `ts_area` VALUES ('320302', '鼓樓區', '320300', '2');
INSERT INTO `ts_area` VALUES ('320303', '雲龍區', '320300', '3');
INSERT INTO `ts_area` VALUES ('320304', '九里區', '320300', '4');
INSERT INTO `ts_area` VALUES ('320305', '賈汪區', '320300', '5');
INSERT INTO `ts_area` VALUES ('320311', '泉山區', '320300', '6');
INSERT INTO `ts_area` VALUES ('320321', '豐　縣', '320300', '7');
INSERT INTO `ts_area` VALUES ('320322', '沛　縣', '320300', '8');
INSERT INTO `ts_area` VALUES ('320323', '銅山縣', '320300', '9');
INSERT INTO `ts_area` VALUES ('320324', '睢寧縣', '320300', '10');
INSERT INTO `ts_area` VALUES ('320381', '新沂市', '320300', '11');
INSERT INTO `ts_area` VALUES ('320382', '邳州市', '320300', '12');
INSERT INTO `ts_area` VALUES ('320401', '市轄區', '320400', '1');
INSERT INTO `ts_area` VALUES ('320402', '天寧區', '320400', '2');
INSERT INTO `ts_area` VALUES ('320404', '鐘樓區', '320400', '3');
INSERT INTO `ts_area` VALUES ('320405', '慼墅堰區', '320400', '4');
INSERT INTO `ts_area` VALUES ('320411', '新北區', '320400', '5');
INSERT INTO `ts_area` VALUES ('320412', '武進區', '320400', '6');
INSERT INTO `ts_area` VALUES ('320481', '溧陽市', '320400', '7');
INSERT INTO `ts_area` VALUES ('320482', '金壇市', '320400', '8');
INSERT INTO `ts_area` VALUES ('320501', '市轄區', '320500', '1');
INSERT INTO `ts_area` VALUES ('320502', '滄浪區', '320500', '2');
INSERT INTO `ts_area` VALUES ('320503', '平江區', '320500', '3');
INSERT INTO `ts_area` VALUES ('320504', '金閶區', '320500', '4');
INSERT INTO `ts_area` VALUES ('320505', '虎丘區', '320500', '5');
INSERT INTO `ts_area` VALUES ('320506', '吳中區', '320500', '6');
INSERT INTO `ts_area` VALUES ('320507', '相城區', '320500', '7');
INSERT INTO `ts_area` VALUES ('320581', '常熟市', '320500', '8');
INSERT INTO `ts_area` VALUES ('320582', '張家港市', '320500', '9');
INSERT INTO `ts_area` VALUES ('320583', '崑山市', '320500', '10');
INSERT INTO `ts_area` VALUES ('320584', '吳江市', '320500', '11');
INSERT INTO `ts_area` VALUES ('320585', '太倉市', '320500', '12');
INSERT INTO `ts_area` VALUES ('320601', '市轄區', '320600', '1');
INSERT INTO `ts_area` VALUES ('320602', '崇川區', '320600', '2');
INSERT INTO `ts_area` VALUES ('320611', '港閘區', '320600', '3');
INSERT INTO `ts_area` VALUES ('320621', '海安縣', '320600', '4');
INSERT INTO `ts_area` VALUES ('320623', '如東縣', '320600', '5');
INSERT INTO `ts_area` VALUES ('320681', '啟東市', '320600', '6');
INSERT INTO `ts_area` VALUES ('320682', '如皋市', '320600', '7');
INSERT INTO `ts_area` VALUES ('320683', '通州市', '320600', '8');
INSERT INTO `ts_area` VALUES ('320684', '海門市', '320600', '9');
INSERT INTO `ts_area` VALUES ('320701', '市轄區', '320700', '1');
INSERT INTO `ts_area` VALUES ('320703', '連雲區', '320700', '2');
INSERT INTO `ts_area` VALUES ('320705', '新浦區', '320700', '3');
INSERT INTO `ts_area` VALUES ('320706', '海州區', '320700', '4');
INSERT INTO `ts_area` VALUES ('320721', '贛榆縣', '320700', '5');
INSERT INTO `ts_area` VALUES ('320722', '東海縣', '320700', '6');
INSERT INTO `ts_area` VALUES ('320723', '灌雲縣', '320700', '7');
INSERT INTO `ts_area` VALUES ('320724', '灌南縣', '320700', '8');
INSERT INTO `ts_area` VALUES ('320801', '市轄區', '320800', '1');
INSERT INTO `ts_area` VALUES ('320802', '清河區', '320800', '2');
INSERT INTO `ts_area` VALUES ('320803', '楚州區', '320800', '3');
INSERT INTO `ts_area` VALUES ('320804', '淮陰區', '320800', '4');
INSERT INTO `ts_area` VALUES ('320811', '清浦區', '320800', '5');
INSERT INTO `ts_area` VALUES ('320826', '漣水縣', '320800', '6');
INSERT INTO `ts_area` VALUES ('320829', '洪澤縣', '320800', '7');
INSERT INTO `ts_area` VALUES ('320830', '盱眙縣', '320800', '8');
INSERT INTO `ts_area` VALUES ('320831', '金湖縣', '320800', '9');
INSERT INTO `ts_area` VALUES ('320901', '市轄區', '320900', '1');
INSERT INTO `ts_area` VALUES ('320902', '亭湖區', '320900', '2');
INSERT INTO `ts_area` VALUES ('320903', '鹽都區', '320900', '3');
INSERT INTO `ts_area` VALUES ('320921', '響水縣', '320900', '4');
INSERT INTO `ts_area` VALUES ('320922', '濱海縣', '320900', '5');
INSERT INTO `ts_area` VALUES ('320923', '阜寧縣', '320900', '6');
INSERT INTO `ts_area` VALUES ('320924', '射陽縣', '320900', '7');
INSERT INTO `ts_area` VALUES ('320925', '建湖縣', '320900', '8');
INSERT INTO `ts_area` VALUES ('320981', '東臺市', '320900', '9');
INSERT INTO `ts_area` VALUES ('320982', '大豐市', '320900', '10');
INSERT INTO `ts_area` VALUES ('321001', '市轄區', '321000', '1');
INSERT INTO `ts_area` VALUES ('321002', '廣陵區', '321000', '2');
INSERT INTO `ts_area` VALUES ('321003', '邗江區', '321000', '3');
INSERT INTO `ts_area` VALUES ('321011', '郊　區', '321000', '4');
INSERT INTO `ts_area` VALUES ('321023', '寶應縣', '321000', '5');
INSERT INTO `ts_area` VALUES ('321081', '儀征市', '321000', '6');
INSERT INTO `ts_area` VALUES ('321084', '高郵市', '321000', '7');
INSERT INTO `ts_area` VALUES ('321088', '江都市', '321000', '8');
INSERT INTO `ts_area` VALUES ('321101', '市轄區', '321100', '1');
INSERT INTO `ts_area` VALUES ('321102', '京口區', '321100', '2');
INSERT INTO `ts_area` VALUES ('321111', '潤州區', '321100', '3');
INSERT INTO `ts_area` VALUES ('321112', '丹徒區', '321100', '4');
INSERT INTO `ts_area` VALUES ('321181', '丹陽市', '321100', '5');
INSERT INTO `ts_area` VALUES ('321182', '揚中市', '321100', '6');
INSERT INTO `ts_area` VALUES ('321183', '句容市', '321100', '7');
INSERT INTO `ts_area` VALUES ('321201', '市轄區', '321200', '1');
INSERT INTO `ts_area` VALUES ('321202', '海陵區', '321200', '2');
INSERT INTO `ts_area` VALUES ('321203', '高港區', '321200', '3');
INSERT INTO `ts_area` VALUES ('321281', '興化市', '321200', '4');
INSERT INTO `ts_area` VALUES ('321282', '靖江市', '321200', '5');
INSERT INTO `ts_area` VALUES ('321283', '泰興市', '321200', '6');
INSERT INTO `ts_area` VALUES ('321284', '姜堰市', '321200', '7');
INSERT INTO `ts_area` VALUES ('321301', '市轄區', '321300', '1');
INSERT INTO `ts_area` VALUES ('321302', '宿城區', '321300', '2');
INSERT INTO `ts_area` VALUES ('321311', '宿豫區', '321300', '3');
INSERT INTO `ts_area` VALUES ('321322', '沭陽縣', '321300', '4');
INSERT INTO `ts_area` VALUES ('321323', '泗陽縣', '321300', '5');
INSERT INTO `ts_area` VALUES ('321324', '泗洪縣', '321300', '6');
INSERT INTO `ts_area` VALUES ('330101', '市轄區', '330100', '1');
INSERT INTO `ts_area` VALUES ('330102', '上城區', '330100', '2');
INSERT INTO `ts_area` VALUES ('330103', '下城區', '330100', '3');
INSERT INTO `ts_area` VALUES ('330104', '江乾區', '330100', '4');
INSERT INTO `ts_area` VALUES ('330105', '拱墅區', '330100', '5');
INSERT INTO `ts_area` VALUES ('330106', '西湖區', '330100', '6');
INSERT INTO `ts_area` VALUES ('330108', '濱江區', '330100', '7');
INSERT INTO `ts_area` VALUES ('330109', '蕭山區', '330100', '8');
INSERT INTO `ts_area` VALUES ('330110', '餘杭區', '330100', '9');
INSERT INTO `ts_area` VALUES ('330122', '桐廬縣', '330100', '10');
INSERT INTO `ts_area` VALUES ('330127', '淳安縣', '330100', '11');
INSERT INTO `ts_area` VALUES ('330182', '建德市', '330100', '12');
INSERT INTO `ts_area` VALUES ('330183', '富陽市', '330100', '13');
INSERT INTO `ts_area` VALUES ('330185', '臨安市', '330100', '14');
INSERT INTO `ts_area` VALUES ('330201', '市轄區', '330200', '1');
INSERT INTO `ts_area` VALUES ('330203', '海曙區', '330200', '2');
INSERT INTO `ts_area` VALUES ('330204', '江東區', '330200', '3');
INSERT INTO `ts_area` VALUES ('330205', '江北區', '330200', '4');
INSERT INTO `ts_area` VALUES ('330206', '北侖區', '330200', '5');
INSERT INTO `ts_area` VALUES ('330211', '鎮海區', '330200', '6');
INSERT INTO `ts_area` VALUES ('330212', '鄞州區', '330200', '7');
INSERT INTO `ts_area` VALUES ('330225', '象山縣', '330200', '8');
INSERT INTO `ts_area` VALUES ('330226', '寧海縣', '330200', '9');
INSERT INTO `ts_area` VALUES ('330281', '餘姚市', '330200', '10');
INSERT INTO `ts_area` VALUES ('330282', '慈溪市', '330200', '11');
INSERT INTO `ts_area` VALUES ('330283', '奉化市', '330200', '12');
INSERT INTO `ts_area` VALUES ('330301', '市轄區', '330300', '1');
INSERT INTO `ts_area` VALUES ('330302', '鹿城區', '330300', '2');
INSERT INTO `ts_area` VALUES ('330303', '龍灣區', '330300', '3');
INSERT INTO `ts_area` VALUES ('330304', '甌海區', '330300', '4');
INSERT INTO `ts_area` VALUES ('330322', '洞頭縣', '330300', '5');
INSERT INTO `ts_area` VALUES ('330324', '永嘉縣', '330300', '6');
INSERT INTO `ts_area` VALUES ('330326', '平陽縣', '330300', '7');
INSERT INTO `ts_area` VALUES ('330327', '蒼南縣', '330300', '8');
INSERT INTO `ts_area` VALUES ('330328', '文成縣', '330300', '9');
INSERT INTO `ts_area` VALUES ('330329', '泰順縣', '330300', '10');
INSERT INTO `ts_area` VALUES ('330381', '瑞安市', '330300', '11');
INSERT INTO `ts_area` VALUES ('330382', '樂清市', '330300', '12');
INSERT INTO `ts_area` VALUES ('330401', '市轄區', '330400', '1');
INSERT INTO `ts_area` VALUES ('330402', '秀城區', '330400', '2');
INSERT INTO `ts_area` VALUES ('330411', '秀洲區', '330400', '3');
INSERT INTO `ts_area` VALUES ('330421', '嘉善縣', '330400', '4');
INSERT INTO `ts_area` VALUES ('330424', '海鹽縣', '330400', '5');
INSERT INTO `ts_area` VALUES ('330481', '海寧市', '330400', '6');
INSERT INTO `ts_area` VALUES ('330482', '平湖市', '330400', '7');
INSERT INTO `ts_area` VALUES ('330483', '桐鄉市', '330400', '8');
INSERT INTO `ts_area` VALUES ('330501', '市轄區', '330500', '1');
INSERT INTO `ts_area` VALUES ('330502', '吳興區', '330500', '2');
INSERT INTO `ts_area` VALUES ('330503', '南潯區', '330500', '3');
INSERT INTO `ts_area` VALUES ('330521', '德清縣', '330500', '4');
INSERT INTO `ts_area` VALUES ('330522', '長興縣', '330500', '5');
INSERT INTO `ts_area` VALUES ('330523', '安吉縣', '330500', '6');
INSERT INTO `ts_area` VALUES ('330601', '市轄區', '330600', '1');
INSERT INTO `ts_area` VALUES ('330602', '越城區', '330600', '2');
INSERT INTO `ts_area` VALUES ('330621', '紹興縣', '330600', '3');
INSERT INTO `ts_area` VALUES ('330624', '新昌縣', '330600', '4');
INSERT INTO `ts_area` VALUES ('330681', '諸暨市', '330600', '5');
INSERT INTO `ts_area` VALUES ('330682', '上虞市', '330600', '6');
INSERT INTO `ts_area` VALUES ('330683', '嵊州市', '330600', '7');
INSERT INTO `ts_area` VALUES ('330701', '市轄區', '330700', '1');
INSERT INTO `ts_area` VALUES ('330702', '婺城區', '330700', '2');
INSERT INTO `ts_area` VALUES ('330703', '金東區', '330700', '3');
INSERT INTO `ts_area` VALUES ('330723', '武義縣', '330700', '4');
INSERT INTO `ts_area` VALUES ('330726', '浦江縣', '330700', '5');
INSERT INTO `ts_area` VALUES ('330727', '磐安縣', '330700', '6');
INSERT INTO `ts_area` VALUES ('330781', '蘭溪市', '330700', '7');
INSERT INTO `ts_area` VALUES ('330782', '義烏市', '330700', '8');
INSERT INTO `ts_area` VALUES ('330783', '東陽市', '330700', '9');
INSERT INTO `ts_area` VALUES ('330784', '永康市', '330700', '10');
INSERT INTO `ts_area` VALUES ('330801', '市轄區', '330800', '1');
INSERT INTO `ts_area` VALUES ('330802', '柯城區', '330800', '2');
INSERT INTO `ts_area` VALUES ('330803', '衢江區', '330800', '3');
INSERT INTO `ts_area` VALUES ('330822', '常山縣', '330800', '4');
INSERT INTO `ts_area` VALUES ('330824', '開化縣', '330800', '5');
INSERT INTO `ts_area` VALUES ('330825', '龍遊縣', '330800', '6');
INSERT INTO `ts_area` VALUES ('330881', '江山市', '330800', '7');
INSERT INTO `ts_area` VALUES ('330901', '市轄區', '330900', '1');
INSERT INTO `ts_area` VALUES ('330902', '定海區', '330900', '2');
INSERT INTO `ts_area` VALUES ('330903', '普陀區', '330900', '3');
INSERT INTO `ts_area` VALUES ('330921', '岱山縣', '330900', '4');
INSERT INTO `ts_area` VALUES ('330922', '嵊泗縣', '330900', '5');
INSERT INTO `ts_area` VALUES ('331001', '市轄區', '331000', '1');
INSERT INTO `ts_area` VALUES ('331002', '椒江區', '331000', '2');
INSERT INTO `ts_area` VALUES ('331003', '黃巖區', '331000', '3');
INSERT INTO `ts_area` VALUES ('331004', '路橋區', '331000', '4');
INSERT INTO `ts_area` VALUES ('331021', '玉環縣', '331000', '5');
INSERT INTO `ts_area` VALUES ('331022', '三門縣', '331000', '6');
INSERT INTO `ts_area` VALUES ('331023', '天台縣', '331000', '7');
INSERT INTO `ts_area` VALUES ('331024', '仙居縣', '331000', '8');
INSERT INTO `ts_area` VALUES ('331081', '溫嶺市', '331000', '9');
INSERT INTO `ts_area` VALUES ('331082', '臨海市', '331000', '10');
INSERT INTO `ts_area` VALUES ('331101', '市轄區', '331100', '1');
INSERT INTO `ts_area` VALUES ('331102', '蓮都區', '331100', '2');
INSERT INTO `ts_area` VALUES ('331121', '青田縣', '331100', '3');
INSERT INTO `ts_area` VALUES ('331122', '縉雲縣', '331100', '4');
INSERT INTO `ts_area` VALUES ('331123', '遂昌縣', '331100', '5');
INSERT INTO `ts_area` VALUES ('331124', '松陽縣', '331100', '6');
INSERT INTO `ts_area` VALUES ('331125', '雲和縣', '331100', '7');
INSERT INTO `ts_area` VALUES ('331126', '慶元縣', '331100', '8');
INSERT INTO `ts_area` VALUES ('331127', '景寧畲族自治縣', '331100', '9');
INSERT INTO `ts_area` VALUES ('331181', '龍泉市', '331100', '10');
INSERT INTO `ts_area` VALUES ('340101', '市轄區', '340100', '1');
INSERT INTO `ts_area` VALUES ('340102', '瑤海區', '340100', '2');
INSERT INTO `ts_area` VALUES ('340103', '廬陽區', '340100', '3');
INSERT INTO `ts_area` VALUES ('340104', '蜀山區', '340100', '4');
INSERT INTO `ts_area` VALUES ('340111', '包河區', '340100', '5');
INSERT INTO `ts_area` VALUES ('340121', '長豐縣', '340100', '6');
INSERT INTO `ts_area` VALUES ('340122', '肥東縣', '340100', '7');
INSERT INTO `ts_area` VALUES ('340123', '肥西縣', '340100', '8');
INSERT INTO `ts_area` VALUES ('340201', '市轄區', '340200', '1');
INSERT INTO `ts_area` VALUES ('340202', '鏡湖區', '340200', '2');
INSERT INTO `ts_area` VALUES ('340203', '馬塘區', '340200', '3');
INSERT INTO `ts_area` VALUES ('340204', '新蕪區', '340200', '4');
INSERT INTO `ts_area` VALUES ('340207', '鳩江區', '340200', '5');
INSERT INTO `ts_area` VALUES ('340221', '蕪湖縣', '340200', '6');
INSERT INTO `ts_area` VALUES ('340222', '繁昌縣', '340200', '7');
INSERT INTO `ts_area` VALUES ('340223', '南陵縣', '340200', '8');
INSERT INTO `ts_area` VALUES ('340301', '市轄區', '340300', '1');
INSERT INTO `ts_area` VALUES ('340302', '龍子湖區', '340300', '2');
INSERT INTO `ts_area` VALUES ('340303', '蚌山區', '340300', '3');
INSERT INTO `ts_area` VALUES ('340304', '禹會區', '340300', '4');
INSERT INTO `ts_area` VALUES ('340311', '淮上區', '340300', '5');
INSERT INTO `ts_area` VALUES ('340321', '懷遠縣', '340300', '6');
INSERT INTO `ts_area` VALUES ('340322', '五河縣', '340300', '7');
INSERT INTO `ts_area` VALUES ('340323', '固鎮縣', '340300', '8');
INSERT INTO `ts_area` VALUES ('340401', '市轄區', '340400', '1');
INSERT INTO `ts_area` VALUES ('340402', '大通區', '340400', '2');
INSERT INTO `ts_area` VALUES ('340403', '田家庵區', '340400', '3');
INSERT INTO `ts_area` VALUES ('340404', '謝家集區', '340400', '4');
INSERT INTO `ts_area` VALUES ('340405', '八公山區', '340400', '5');
INSERT INTO `ts_area` VALUES ('340406', '潘集區', '340400', '6');
INSERT INTO `ts_area` VALUES ('340421', '鳳臺縣', '340400', '7');
INSERT INTO `ts_area` VALUES ('340501', '市轄區', '340500', '1');
INSERT INTO `ts_area` VALUES ('340502', '金家莊區', '340500', '2');
INSERT INTO `ts_area` VALUES ('340503', '花山區', '340500', '3');
INSERT INTO `ts_area` VALUES ('340504', '雨山區', '340500', '4');
INSERT INTO `ts_area` VALUES ('340521', '當塗縣', '340500', '5');
INSERT INTO `ts_area` VALUES ('340601', '市轄區', '340600', '1');
INSERT INTO `ts_area` VALUES ('340602', '杜集區', '340600', '2');
INSERT INTO `ts_area` VALUES ('340603', '相山區', '340600', '3');
INSERT INTO `ts_area` VALUES ('340604', '烈山區', '340600', '4');
INSERT INTO `ts_area` VALUES ('340621', '濉溪縣', '340600', '5');
INSERT INTO `ts_area` VALUES ('340701', '市轄區', '340700', '1');
INSERT INTO `ts_area` VALUES ('340702', '銅官山區', '340700', '2');
INSERT INTO `ts_area` VALUES ('340703', '獅子山區', '340700', '3');
INSERT INTO `ts_area` VALUES ('340711', '郊　區', '340700', '4');
INSERT INTO `ts_area` VALUES ('340721', '銅陵縣', '340700', '5');
INSERT INTO `ts_area` VALUES ('340801', '市轄區', '340800', '1');
INSERT INTO `ts_area` VALUES ('340802', '迎江區', '340800', '2');
INSERT INTO `ts_area` VALUES ('340803', '大觀區', '340800', '3');
INSERT INTO `ts_area` VALUES ('340811', '郊　區', '340800', '4');
INSERT INTO `ts_area` VALUES ('340822', '懷寧縣', '340800', '5');
INSERT INTO `ts_area` VALUES ('340823', '樅陽縣', '340800', '6');
INSERT INTO `ts_area` VALUES ('340824', '潛山縣', '340800', '7');
INSERT INTO `ts_area` VALUES ('340825', '太湖縣', '340800', '8');
INSERT INTO `ts_area` VALUES ('340826', '宿松縣', '340800', '9');
INSERT INTO `ts_area` VALUES ('340827', '望江縣', '340800', '10');
INSERT INTO `ts_area` VALUES ('340828', '嶽西縣', '340800', '11');
INSERT INTO `ts_area` VALUES ('340881', '桐城市', '340800', '12');
INSERT INTO `ts_area` VALUES ('341001', '市轄區', '341000', '1');
INSERT INTO `ts_area` VALUES ('341002', '屯溪區', '341000', '2');
INSERT INTO `ts_area` VALUES ('341003', '黃山區', '341000', '3');
INSERT INTO `ts_area` VALUES ('341004', '徽州區', '341000', '4');
INSERT INTO `ts_area` VALUES ('341021', '歙　縣', '341000', '5');
INSERT INTO `ts_area` VALUES ('341022', '休寧縣', '341000', '6');
INSERT INTO `ts_area` VALUES ('341023', '黟　縣', '341000', '7');
INSERT INTO `ts_area` VALUES ('341024', '祁門縣', '341000', '8');
INSERT INTO `ts_area` VALUES ('341101', '市轄區', '341100', '1');
INSERT INTO `ts_area` VALUES ('341102', '琅琊區', '341100', '2');
INSERT INTO `ts_area` VALUES ('341103', '南譙區', '341100', '3');
INSERT INTO `ts_area` VALUES ('341122', '來安縣', '341100', '4');
INSERT INTO `ts_area` VALUES ('341124', '全椒縣', '341100', '5');
INSERT INTO `ts_area` VALUES ('341125', '定遠縣', '341100', '6');
INSERT INTO `ts_area` VALUES ('341126', '鳳陽縣', '341100', '7');
INSERT INTO `ts_area` VALUES ('341181', '天長市', '341100', '8');
INSERT INTO `ts_area` VALUES ('341182', '明光市', '341100', '9');
INSERT INTO `ts_area` VALUES ('341201', '市轄區', '341200', '1');
INSERT INTO `ts_area` VALUES ('341202', '潁州區', '341200', '2');
INSERT INTO `ts_area` VALUES ('341203', '潁東區', '341200', '3');
INSERT INTO `ts_area` VALUES ('341204', '潁泉區', '341200', '4');
INSERT INTO `ts_area` VALUES ('341221', '臨泉縣', '341200', '5');
INSERT INTO `ts_area` VALUES ('341222', '太和縣', '341200', '6');
INSERT INTO `ts_area` VALUES ('341225', '阜南縣', '341200', '7');
INSERT INTO `ts_area` VALUES ('341226', '潁上縣', '341200', '8');
INSERT INTO `ts_area` VALUES ('341282', '界首市', '341200', '9');
INSERT INTO `ts_area` VALUES ('341301', '市轄區', '341300', '1');
INSERT INTO `ts_area` VALUES ('341302', '墉橋區', '341300', '2');
INSERT INTO `ts_area` VALUES ('341321', '碭山縣', '341300', '3');
INSERT INTO `ts_area` VALUES ('341322', '蕭　縣', '341300', '4');
INSERT INTO `ts_area` VALUES ('341323', '靈璧縣', '341300', '5');
INSERT INTO `ts_area` VALUES ('341324', '泗　縣', '341300', '6');
INSERT INTO `ts_area` VALUES ('341401', '廬江縣', '340100', '9');
INSERT INTO `ts_area` VALUES ('341402', '巢湖市', '340100', '10');
INSERT INTO `ts_area` VALUES ('341422', '無為縣', '340200', '9');
INSERT INTO `ts_area` VALUES ('341423', '含山縣', '340500', '6');
INSERT INTO `ts_area` VALUES ('341424', '和　縣', '340500', '7');
INSERT INTO `ts_area` VALUES ('341501', '市轄區', '341500', '1');
INSERT INTO `ts_area` VALUES ('341502', '金安區', '341500', '2');
INSERT INTO `ts_area` VALUES ('341503', '裕安區', '341500', '3');
INSERT INTO `ts_area` VALUES ('341521', '壽　縣', '341500', '4');
INSERT INTO `ts_area` VALUES ('341522', '霍邱縣', '341500', '5');
INSERT INTO `ts_area` VALUES ('341523', '舒城縣', '341500', '6');
INSERT INTO `ts_area` VALUES ('341524', '金寨縣', '341500', '7');
INSERT INTO `ts_area` VALUES ('341525', '霍山縣', '341500', '8');
INSERT INTO `ts_area` VALUES ('341601', '市轄區', '341600', '1');
INSERT INTO `ts_area` VALUES ('341602', '譙城區', '341600', '2');
INSERT INTO `ts_area` VALUES ('341621', '渦陽縣', '341600', '3');
INSERT INTO `ts_area` VALUES ('341622', '蒙城縣', '341600', '4');
INSERT INTO `ts_area` VALUES ('341623', '利辛縣', '341600', '5');
INSERT INTO `ts_area` VALUES ('341701', '市轄區', '341700', '1');
INSERT INTO `ts_area` VALUES ('341702', '貴池區', '341700', '2');
INSERT INTO `ts_area` VALUES ('341721', '東至縣', '341700', '3');
INSERT INTO `ts_area` VALUES ('341722', '石臺縣', '341700', '4');
INSERT INTO `ts_area` VALUES ('341723', '青陽縣', '341700', '5');
INSERT INTO `ts_area` VALUES ('341801', '市轄區', '341800', '1');
INSERT INTO `ts_area` VALUES ('341802', '宣州區', '341800', '2');
INSERT INTO `ts_area` VALUES ('341821', '郎溪縣', '341800', '3');
INSERT INTO `ts_area` VALUES ('341822', '廣德縣', '341800', '4');
INSERT INTO `ts_area` VALUES ('341823', '涇　縣', '341800', '5');
INSERT INTO `ts_area` VALUES ('341824', '績溪縣', '341800', '6');
INSERT INTO `ts_area` VALUES ('341825', '旌德縣', '341800', '7');
INSERT INTO `ts_area` VALUES ('341881', '寧國市', '341800', '8');
INSERT INTO `ts_area` VALUES ('350101', '市轄區', '350100', '1');
INSERT INTO `ts_area` VALUES ('350102', '鼓樓區', '350100', '2');
INSERT INTO `ts_area` VALUES ('350103', '臺江區', '350100', '3');
INSERT INTO `ts_area` VALUES ('350104', '倉山區', '350100', '4');
INSERT INTO `ts_area` VALUES ('350105', '馬尾區', '350100', '5');
INSERT INTO `ts_area` VALUES ('350111', '晉安區', '350100', '6');
INSERT INTO `ts_area` VALUES ('350121', '閩侯縣', '350100', '7');
INSERT INTO `ts_area` VALUES ('350122', '連江縣', '350100', '8');
INSERT INTO `ts_area` VALUES ('350123', '羅源縣', '350100', '9');
INSERT INTO `ts_area` VALUES ('350124', '閩清縣', '350100', '10');
INSERT INTO `ts_area` VALUES ('350125', '永泰縣', '350100', '11');
INSERT INTO `ts_area` VALUES ('350128', '平潭縣', '350100', '12');
INSERT INTO `ts_area` VALUES ('350181', '福清市', '350100', '13');
INSERT INTO `ts_area` VALUES ('350182', '長樂市', '350100', '14');
INSERT INTO `ts_area` VALUES ('350201', '市轄區', '350200', '1');
INSERT INTO `ts_area` VALUES ('350203', '思明區', '350200', '2');
INSERT INTO `ts_area` VALUES ('350205', '海滄區', '350200', '3');
INSERT INTO `ts_area` VALUES ('350206', '湖裡區', '350200', '4');
INSERT INTO `ts_area` VALUES ('350211', '集美區', '350200', '5');
INSERT INTO `ts_area` VALUES ('350212', '同安區', '350200', '6');
INSERT INTO `ts_area` VALUES ('350213', '翔安區', '350200', '7');
INSERT INTO `ts_area` VALUES ('350301', '市轄區', '350300', '1');
INSERT INTO `ts_area` VALUES ('350302', '城廂區', '350300', '2');
INSERT INTO `ts_area` VALUES ('350303', '涵江區', '350300', '3');
INSERT INTO `ts_area` VALUES ('350304', '荔城區', '350300', '4');
INSERT INTO `ts_area` VALUES ('350305', '秀嶼區', '350300', '5');
INSERT INTO `ts_area` VALUES ('350322', '仙遊縣', '350300', '6');
INSERT INTO `ts_area` VALUES ('350401', '市轄區', '350400', '1');
INSERT INTO `ts_area` VALUES ('350402', '梅列區', '350400', '2');
INSERT INTO `ts_area` VALUES ('350403', '三元區', '350400', '3');
INSERT INTO `ts_area` VALUES ('350421', '明溪縣', '350400', '4');
INSERT INTO `ts_area` VALUES ('350423', '清流縣', '350400', '5');
INSERT INTO `ts_area` VALUES ('350424', '寧化縣', '350400', '6');
INSERT INTO `ts_area` VALUES ('350425', '大田縣', '350400', '7');
INSERT INTO `ts_area` VALUES ('350426', '尤溪縣', '350400', '8');
INSERT INTO `ts_area` VALUES ('350427', '沙　縣', '350400', '9');
INSERT INTO `ts_area` VALUES ('350428', '將樂縣', '350400', '10');
INSERT INTO `ts_area` VALUES ('350429', '泰寧縣', '350400', '11');
INSERT INTO `ts_area` VALUES ('350430', '建寧縣', '350400', '12');
INSERT INTO `ts_area` VALUES ('350481', '永安市', '350400', '13');
INSERT INTO `ts_area` VALUES ('350501', '市轄區', '350500', '1');
INSERT INTO `ts_area` VALUES ('350502', '鯉城區', '350500', '2');
INSERT INTO `ts_area` VALUES ('350503', '豐澤區', '350500', '3');
INSERT INTO `ts_area` VALUES ('350504', '洛江區', '350500', '4');
INSERT INTO `ts_area` VALUES ('350505', '泉港區', '350500', '5');
INSERT INTO `ts_area` VALUES ('350521', '惠安縣', '350500', '6');
INSERT INTO `ts_area` VALUES ('350524', '安溪縣', '350500', '7');
INSERT INTO `ts_area` VALUES ('350525', '永春縣', '350500', '8');
INSERT INTO `ts_area` VALUES ('350526', '德化縣', '350500', '9');
INSERT INTO `ts_area` VALUES ('350527', '金門縣', '350500', '10');
INSERT INTO `ts_area` VALUES ('350581', '石獅市', '350500', '11');
INSERT INTO `ts_area` VALUES ('350582', '晉江市', '350500', '12');
INSERT INTO `ts_area` VALUES ('350583', '南安市', '350500', '13');
INSERT INTO `ts_area` VALUES ('350601', '市轄區', '350600', '1');
INSERT INTO `ts_area` VALUES ('350602', '薌城區', '350600', '2');
INSERT INTO `ts_area` VALUES ('350603', '龍文區', '350600', '3');
INSERT INTO `ts_area` VALUES ('350622', '雲霄縣', '350600', '4');
INSERT INTO `ts_area` VALUES ('350623', '漳浦縣', '350600', '5');
INSERT INTO `ts_area` VALUES ('350624', '詔安縣', '350600', '6');
INSERT INTO `ts_area` VALUES ('350625', '長泰縣', '350600', '7');
INSERT INTO `ts_area` VALUES ('350626', '東山縣', '350600', '8');
INSERT INTO `ts_area` VALUES ('350627', '南靖縣', '350600', '9');
INSERT INTO `ts_area` VALUES ('350628', '平和縣', '350600', '10');
INSERT INTO `ts_area` VALUES ('350629', '華安縣', '350600', '11');
INSERT INTO `ts_area` VALUES ('350681', '龍海市', '350600', '12');
INSERT INTO `ts_area` VALUES ('350701', '市轄區', '350700', '1');
INSERT INTO `ts_area` VALUES ('350702', '延平區', '350700', '2');
INSERT INTO `ts_area` VALUES ('350721', '順昌縣', '350700', '3');
INSERT INTO `ts_area` VALUES ('350722', '浦城縣', '350700', '4');
INSERT INTO `ts_area` VALUES ('350723', '光澤縣', '350700', '5');
INSERT INTO `ts_area` VALUES ('350724', '松溪縣', '350700', '6');
INSERT INTO `ts_area` VALUES ('350725', '政和縣', '350700', '7');
INSERT INTO `ts_area` VALUES ('350781', '邵武市', '350700', '8');
INSERT INTO `ts_area` VALUES ('350782', '武夷山市', '350700', '9');
INSERT INTO `ts_area` VALUES ('350783', '建甌市', '350700', '10');
INSERT INTO `ts_area` VALUES ('350784', '建陽市', '350700', '11');
INSERT INTO `ts_area` VALUES ('350801', '市轄區', '350800', '1');
INSERT INTO `ts_area` VALUES ('350802', '新羅區', '350800', '2');
INSERT INTO `ts_area` VALUES ('350821', '長汀縣', '350800', '3');
INSERT INTO `ts_area` VALUES ('350822', '永定縣', '350800', '4');
INSERT INTO `ts_area` VALUES ('350823', '上杭縣', '350800', '5');
INSERT INTO `ts_area` VALUES ('350824', '武平縣', '350800', '6');
INSERT INTO `ts_area` VALUES ('350825', '連城縣', '350800', '7');
INSERT INTO `ts_area` VALUES ('350881', '漳平市', '350800', '8');
INSERT INTO `ts_area` VALUES ('350901', '市轄區', '350900', '1');
INSERT INTO `ts_area` VALUES ('350902', '蕉城區', '350900', '2');
INSERT INTO `ts_area` VALUES ('350921', '霞浦縣', '350900', '3');
INSERT INTO `ts_area` VALUES ('350922', '古田縣', '350900', '4');
INSERT INTO `ts_area` VALUES ('350923', '屏南縣', '350900', '5');
INSERT INTO `ts_area` VALUES ('350924', '壽寧縣', '350900', '6');
INSERT INTO `ts_area` VALUES ('350925', '周寧縣', '350900', '7');
INSERT INTO `ts_area` VALUES ('350926', '柘榮縣', '350900', '8');
INSERT INTO `ts_area` VALUES ('350981', '福安市', '350900', '9');
INSERT INTO `ts_area` VALUES ('350982', '福鼎市', '350900', '10');
INSERT INTO `ts_area` VALUES ('360101', '市轄區', '360100', '1');
INSERT INTO `ts_area` VALUES ('360102', '東湖區', '360100', '2');
INSERT INTO `ts_area` VALUES ('360103', '西湖區', '360100', '3');
INSERT INTO `ts_area` VALUES ('360104', '青雲譜區', '360100', '4');
INSERT INTO `ts_area` VALUES ('360105', '灣裡區', '360100', '5');
INSERT INTO `ts_area` VALUES ('360111', '青山湖區', '360100', '6');
INSERT INTO `ts_area` VALUES ('360121', '南昌縣', '360100', '7');
INSERT INTO `ts_area` VALUES ('360122', '新建縣', '360100', '8');
INSERT INTO `ts_area` VALUES ('360123', '安義縣', '360100', '9');
INSERT INTO `ts_area` VALUES ('360124', '進賢縣', '360100', '10');
INSERT INTO `ts_area` VALUES ('360201', '市轄區', '360200', '1');
INSERT INTO `ts_area` VALUES ('360202', '昌江區', '360200', '2');
INSERT INTO `ts_area` VALUES ('360203', '珠山區', '360200', '3');
INSERT INTO `ts_area` VALUES ('360222', '浮樑縣', '360200', '4');
INSERT INTO `ts_area` VALUES ('360281', '樂平市', '360200', '5');
INSERT INTO `ts_area` VALUES ('360301', '市轄區', '360300', '1');
INSERT INTO `ts_area` VALUES ('360302', '安源區', '360300', '2');
INSERT INTO `ts_area` VALUES ('360313', '湘東區', '360300', '3');
INSERT INTO `ts_area` VALUES ('360321', '蓮花縣', '360300', '4');
INSERT INTO `ts_area` VALUES ('360322', '上栗縣', '360300', '5');
INSERT INTO `ts_area` VALUES ('360323', '蘆溪縣', '360300', '6');
INSERT INTO `ts_area` VALUES ('360401', '市轄區', '360400', '1');
INSERT INTO `ts_area` VALUES ('360402', '廬山區', '360400', '2');
INSERT INTO `ts_area` VALUES ('360403', '潯陽區', '360400', '3');
INSERT INTO `ts_area` VALUES ('360421', '九江縣', '360400', '4');
INSERT INTO `ts_area` VALUES ('360423', '武寧縣', '360400', '5');
INSERT INTO `ts_area` VALUES ('360424', '修水縣', '360400', '6');
INSERT INTO `ts_area` VALUES ('360425', '永修縣', '360400', '7');
INSERT INTO `ts_area` VALUES ('360426', '德安縣', '360400', '8');
INSERT INTO `ts_area` VALUES ('360427', '星子縣', '360400', '9');
INSERT INTO `ts_area` VALUES ('360428', '都昌縣', '360400', '10');
INSERT INTO `ts_area` VALUES ('360429', '湖口縣', '360400', '11');
INSERT INTO `ts_area` VALUES ('360430', '彭澤縣', '360400', '12');
INSERT INTO `ts_area` VALUES ('360481', '瑞昌市', '360400', '13');
INSERT INTO `ts_area` VALUES ('360501', '市轄區', '360500', '1');
INSERT INTO `ts_area` VALUES ('360502', '渝水區', '360500', '2');
INSERT INTO `ts_area` VALUES ('360521', '分宜縣', '360500', '3');
INSERT INTO `ts_area` VALUES ('360601', '市轄區', '360600', '1');
INSERT INTO `ts_area` VALUES ('360602', '月湖區', '360600', '2');
INSERT INTO `ts_area` VALUES ('360622', '餘江縣', '360600', '3');
INSERT INTO `ts_area` VALUES ('360681', '貴溪市', '360600', '4');
INSERT INTO `ts_area` VALUES ('360701', '市轄區', '360700', '1');
INSERT INTO `ts_area` VALUES ('360702', '章貢區', '360700', '2');
INSERT INTO `ts_area` VALUES ('360721', '贛　縣', '360700', '3');
INSERT INTO `ts_area` VALUES ('360722', '信豐縣', '360700', '4');
INSERT INTO `ts_area` VALUES ('360723', '大餘縣', '360700', '5');
INSERT INTO `ts_area` VALUES ('360724', '上猶縣', '360700', '6');
INSERT INTO `ts_area` VALUES ('360725', '崇義縣', '360700', '7');
INSERT INTO `ts_area` VALUES ('360726', '安遠縣', '360700', '8');
INSERT INTO `ts_area` VALUES ('360727', '龍南縣', '360700', '9');
INSERT INTO `ts_area` VALUES ('360728', '定南縣', '360700', '10');
INSERT INTO `ts_area` VALUES ('360729', '全南縣', '360700', '11');
INSERT INTO `ts_area` VALUES ('360730', '寧都縣', '360700', '12');
INSERT INTO `ts_area` VALUES ('360731', '於都縣', '360700', '13');
INSERT INTO `ts_area` VALUES ('360732', '興國縣', '360700', '14');
INSERT INTO `ts_area` VALUES ('360733', '會昌縣', '360700', '15');
INSERT INTO `ts_area` VALUES ('360734', '尋烏縣', '360700', '16');
INSERT INTO `ts_area` VALUES ('360735', '石城縣', '360700', '17');
INSERT INTO `ts_area` VALUES ('360781', '瑞金市', '360700', '18');
INSERT INTO `ts_area` VALUES ('360782', '南康市', '360700', '19');
INSERT INTO `ts_area` VALUES ('360801', '市轄區', '360800', '1');
INSERT INTO `ts_area` VALUES ('360802', '吉州區', '360800', '2');
INSERT INTO `ts_area` VALUES ('360803', '青原區', '360800', '3');
INSERT INTO `ts_area` VALUES ('360821', '吉安縣', '360800', '4');
INSERT INTO `ts_area` VALUES ('360822', '吉水縣', '360800', '5');
INSERT INTO `ts_area` VALUES ('360823', '峽江縣', '360800', '6');
INSERT INTO `ts_area` VALUES ('360824', '新干縣', '360800', '7');
INSERT INTO `ts_area` VALUES ('360825', '永豐縣', '360800', '8');
INSERT INTO `ts_area` VALUES ('360826', '泰和縣', '360800', '9');
INSERT INTO `ts_area` VALUES ('360827', '遂川縣', '360800', '10');
INSERT INTO `ts_area` VALUES ('360828', '萬安縣', '360800', '11');
INSERT INTO `ts_area` VALUES ('360829', '安福縣', '360800', '12');
INSERT INTO `ts_area` VALUES ('360830', '永新縣', '360800', '13');
INSERT INTO `ts_area` VALUES ('360881', '井岡山市', '360800', '14');
INSERT INTO `ts_area` VALUES ('360901', '市轄區', '360900', '1');
INSERT INTO `ts_area` VALUES ('360902', '袁州區', '360900', '2');
INSERT INTO `ts_area` VALUES ('360921', '奉新縣', '360900', '3');
INSERT INTO `ts_area` VALUES ('360922', '萬載縣', '360900', '4');
INSERT INTO `ts_area` VALUES ('360923', '上高縣', '360900', '5');
INSERT INTO `ts_area` VALUES ('360924', '宜豐縣', '360900', '6');
INSERT INTO `ts_area` VALUES ('360925', '靖安縣', '360900', '7');
INSERT INTO `ts_area` VALUES ('360926', '銅鼓縣', '360900', '8');
INSERT INTO `ts_area` VALUES ('360981', '豐城市', '360900', '9');
INSERT INTO `ts_area` VALUES ('360982', '樟樹市', '360900', '10');
INSERT INTO `ts_area` VALUES ('360983', '高安市', '360900', '11');
INSERT INTO `ts_area` VALUES ('361001', '市轄區', '361000', '1');
INSERT INTO `ts_area` VALUES ('361002', '臨川區', '361000', '2');
INSERT INTO `ts_area` VALUES ('361021', '南城縣', '361000', '3');
INSERT INTO `ts_area` VALUES ('361022', '黎川縣', '361000', '4');
INSERT INTO `ts_area` VALUES ('361023', '南豐縣', '361000', '5');
INSERT INTO `ts_area` VALUES ('361024', '崇仁縣', '361000', '6');
INSERT INTO `ts_area` VALUES ('361025', '樂安縣', '361000', '7');
INSERT INTO `ts_area` VALUES ('361026', '宜黃縣', '361000', '8');
INSERT INTO `ts_area` VALUES ('361027', '金溪縣', '361000', '9');
INSERT INTO `ts_area` VALUES ('361028', '資溪縣', '361000', '10');
INSERT INTO `ts_area` VALUES ('361029', '東鄉縣', '361000', '11');
INSERT INTO `ts_area` VALUES ('361030', '廣昌縣', '361000', '12');
INSERT INTO `ts_area` VALUES ('361101', '市轄區', '361100', '1');
INSERT INTO `ts_area` VALUES ('361102', '信州區', '361100', '2');
INSERT INTO `ts_area` VALUES ('361121', '上饒縣', '361100', '3');
INSERT INTO `ts_area` VALUES ('361122', '廣豐縣', '361100', '4');
INSERT INTO `ts_area` VALUES ('361123', '玉山縣', '361100', '5');
INSERT INTO `ts_area` VALUES ('361124', '鉛山縣', '361100', '6');
INSERT INTO `ts_area` VALUES ('361125', '橫峰縣', '361100', '7');
INSERT INTO `ts_area` VALUES ('361126', '弋陽縣', '361100', '8');
INSERT INTO `ts_area` VALUES ('361127', '餘干縣', '361100', '9');
INSERT INTO `ts_area` VALUES ('361128', '鄱陽縣', '361100', '10');
INSERT INTO `ts_area` VALUES ('361129', '萬年縣', '361100', '11');
INSERT INTO `ts_area` VALUES ('361130', '婺源縣', '361100', '12');
INSERT INTO `ts_area` VALUES ('361181', '德興市', '361100', '13');
INSERT INTO `ts_area` VALUES ('370101', '市轄區', '370100', '1');
INSERT INTO `ts_area` VALUES ('370102', '歷下區', '370100', '2');
INSERT INTO `ts_area` VALUES ('370103', '市中區', '370100', '3');
INSERT INTO `ts_area` VALUES ('370104', '槐蔭區', '370100', '4');
INSERT INTO `ts_area` VALUES ('370105', '天橋區', '370100', '5');
INSERT INTO `ts_area` VALUES ('370112', '歷城區', '370100', '6');
INSERT INTO `ts_area` VALUES ('370113', '長清區', '370100', '7');
INSERT INTO `ts_area` VALUES ('370124', '平陰縣', '370100', '8');
INSERT INTO `ts_area` VALUES ('370125', '濟陽縣', '370100', '9');
INSERT INTO `ts_area` VALUES ('370126', '商河縣', '370100', '10');
INSERT INTO `ts_area` VALUES ('370181', '章丘市', '370100', '11');
INSERT INTO `ts_area` VALUES ('370201', '市轄區', '370200', '1');
INSERT INTO `ts_area` VALUES ('370202', '市南區', '370200', '2');
INSERT INTO `ts_area` VALUES ('370203', '市北區', '370200', '3');
INSERT INTO `ts_area` VALUES ('370205', '四方區', '370200', '4');
INSERT INTO `ts_area` VALUES ('370211', '黃島區', '370200', '5');
INSERT INTO `ts_area` VALUES ('370212', '嶗山區', '370200', '6');
INSERT INTO `ts_area` VALUES ('370213', '李滄區', '370200', '7');
INSERT INTO `ts_area` VALUES ('370214', '城陽區', '370200', '8');
INSERT INTO `ts_area` VALUES ('370281', '膠州市', '370200', '9');
INSERT INTO `ts_area` VALUES ('370282', '即墨市', '370200', '10');
INSERT INTO `ts_area` VALUES ('370283', '平度市', '370200', '11');
INSERT INTO `ts_area` VALUES ('370284', '膠南市', '370200', '12');
INSERT INTO `ts_area` VALUES ('370285', '萊西市', '370200', '13');
INSERT INTO `ts_area` VALUES ('370301', '市轄區', '370300', '1');
INSERT INTO `ts_area` VALUES ('370302', '淄川區', '370300', '2');
INSERT INTO `ts_area` VALUES ('370303', '張店區', '370300', '3');
INSERT INTO `ts_area` VALUES ('370304', '博山區', '370300', '4');
INSERT INTO `ts_area` VALUES ('370305', '臨淄區', '370300', '5');
INSERT INTO `ts_area` VALUES ('370306', '周村區', '370300', '6');
INSERT INTO `ts_area` VALUES ('370321', '桓臺縣', '370300', '7');
INSERT INTO `ts_area` VALUES ('370322', '高青縣', '370300', '8');
INSERT INTO `ts_area` VALUES ('370323', '沂源縣', '370300', '9');
INSERT INTO `ts_area` VALUES ('370401', '市轄區', '370400', '1');
INSERT INTO `ts_area` VALUES ('370402', '市中區', '370400', '2');
INSERT INTO `ts_area` VALUES ('370403', '薛城區', '370400', '3');
INSERT INTO `ts_area` VALUES ('370404', '嶧城區', '370400', '4');
INSERT INTO `ts_area` VALUES ('370405', '臺兒莊區', '370400', '5');
INSERT INTO `ts_area` VALUES ('370406', '山亭區', '370400', '6');
INSERT INTO `ts_area` VALUES ('370481', '滕州市', '370400', '7');
INSERT INTO `ts_area` VALUES ('370501', '市轄區', '370500', '1');
INSERT INTO `ts_area` VALUES ('370502', '東營區', '370500', '2');
INSERT INTO `ts_area` VALUES ('370503', '河口區', '370500', '3');
INSERT INTO `ts_area` VALUES ('370521', '墾利縣', '370500', '4');
INSERT INTO `ts_area` VALUES ('370522', '利津縣', '370500', '5');
INSERT INTO `ts_area` VALUES ('370523', '廣饒縣', '370500', '6');
INSERT INTO `ts_area` VALUES ('370601', '市轄區', '370600', '1');
INSERT INTO `ts_area` VALUES ('370602', '芝罘區', '370600', '2');
INSERT INTO `ts_area` VALUES ('370611', '福山區', '370600', '3');
INSERT INTO `ts_area` VALUES ('370612', '牟平區', '370600', '4');
INSERT INTO `ts_area` VALUES ('370613', '萊山區', '370600', '5');
INSERT INTO `ts_area` VALUES ('370634', '長島縣', '370600', '6');
INSERT INTO `ts_area` VALUES ('370681', '龍口市', '370600', '7');
INSERT INTO `ts_area` VALUES ('370682', '萊陽市', '370600', '8');
INSERT INTO `ts_area` VALUES ('370683', '萊州市', '370600', '9');
INSERT INTO `ts_area` VALUES ('370684', '蓬萊市', '370600', '10');
INSERT INTO `ts_area` VALUES ('370685', '招遠市', '370600', '11');
INSERT INTO `ts_area` VALUES ('370686', '棲霞市', '370600', '12');
INSERT INTO `ts_area` VALUES ('370687', '海陽市', '370600', '13');
INSERT INTO `ts_area` VALUES ('370701', '市轄區', '370700', '1');
INSERT INTO `ts_area` VALUES ('370702', '濰城區', '370700', '2');
INSERT INTO `ts_area` VALUES ('370703', '寒亭區', '370700', '3');
INSERT INTO `ts_area` VALUES ('370704', '坊子區', '370700', '4');
INSERT INTO `ts_area` VALUES ('370705', '奎文區', '370700', '5');
INSERT INTO `ts_area` VALUES ('370724', '臨朐縣', '370700', '6');
INSERT INTO `ts_area` VALUES ('370725', '昌樂縣', '370700', '7');
INSERT INTO `ts_area` VALUES ('370781', '青州市', '370700', '8');
INSERT INTO `ts_area` VALUES ('370782', '諸城市', '370700', '9');
INSERT INTO `ts_area` VALUES ('370783', '壽光市', '370700', '10');
INSERT INTO `ts_area` VALUES ('370784', '安丘市', '370700', '11');
INSERT INTO `ts_area` VALUES ('370785', '高密市', '370700', '12');
INSERT INTO `ts_area` VALUES ('370786', '昌邑市', '370700', '13');
INSERT INTO `ts_area` VALUES ('370801', '市轄區', '370800', '1');
INSERT INTO `ts_area` VALUES ('370802', '市中區', '370800', '2');
INSERT INTO `ts_area` VALUES ('370811', '任城區', '370800', '3');
INSERT INTO `ts_area` VALUES ('370826', '微山縣', '370800', '4');
INSERT INTO `ts_area` VALUES ('370827', '魚臺縣', '370800', '5');
INSERT INTO `ts_area` VALUES ('370828', '金鄉縣', '370800', '6');
INSERT INTO `ts_area` VALUES ('370829', '嘉祥縣', '370800', '7');
INSERT INTO `ts_area` VALUES ('370830', '汶上縣', '370800', '8');
INSERT INTO `ts_area` VALUES ('370831', '泗水縣', '370800', '9');
INSERT INTO `ts_area` VALUES ('370832', '梁山縣', '370800', '10');
INSERT INTO `ts_area` VALUES ('370881', '曲阜市', '370800', '11');
INSERT INTO `ts_area` VALUES ('370882', '兗州市', '370800', '12');
INSERT INTO `ts_area` VALUES ('370883', '鄒城市', '370800', '13');
INSERT INTO `ts_area` VALUES ('370901', '市轄區', '370900', '1');
INSERT INTO `ts_area` VALUES ('370902', '泰山區', '370900', '2');
INSERT INTO `ts_area` VALUES ('370903', '岱嶽區', '370900', '3');
INSERT INTO `ts_area` VALUES ('370921', '寧陽縣', '370900', '4');
INSERT INTO `ts_area` VALUES ('370923', '東平縣', '370900', '5');
INSERT INTO `ts_area` VALUES ('370982', '新泰市', '370900', '6');
INSERT INTO `ts_area` VALUES ('370983', '肥城市', '370900', '7');
INSERT INTO `ts_area` VALUES ('371001', '市轄區', '371000', '1');
INSERT INTO `ts_area` VALUES ('371002', '環翠區', '371000', '2');
INSERT INTO `ts_area` VALUES ('371081', '文登市', '371000', '3');
INSERT INTO `ts_area` VALUES ('371082', '榮成市', '371000', '4');
INSERT INTO `ts_area` VALUES ('371083', '乳山市', '371000', '5');
INSERT INTO `ts_area` VALUES ('371101', '市轄區', '371100', '1');
INSERT INTO `ts_area` VALUES ('371102', '東港區', '371100', '2');
INSERT INTO `ts_area` VALUES ('371103', '嵐山區', '371100', '3');
INSERT INTO `ts_area` VALUES ('371121', '五蓮縣', '371100', '4');
INSERT INTO `ts_area` VALUES ('371122', '莒　縣', '371100', '5');
INSERT INTO `ts_area` VALUES ('371201', '市轄區', '371200', '1');
INSERT INTO `ts_area` VALUES ('371202', '萊城區', '371200', '2');
INSERT INTO `ts_area` VALUES ('371203', '鋼城區', '371200', '3');
INSERT INTO `ts_area` VALUES ('371301', '市轄區', '371300', '1');
INSERT INTO `ts_area` VALUES ('371302', '蘭山區', '371300', '2');
INSERT INTO `ts_area` VALUES ('371311', '羅莊區', '371300', '3');
INSERT INTO `ts_area` VALUES ('371312', '河東區', '371300', '4');
INSERT INTO `ts_area` VALUES ('371321', '沂南縣', '371300', '5');
INSERT INTO `ts_area` VALUES ('371322', '郯城縣', '371300', '6');
INSERT INTO `ts_area` VALUES ('371323', '沂水縣', '371300', '7');
INSERT INTO `ts_area` VALUES ('371324', '蒼山縣', '371300', '8');
INSERT INTO `ts_area` VALUES ('371325', '費　縣', '371300', '9');
INSERT INTO `ts_area` VALUES ('371326', '平邑縣', '371300', '10');
INSERT INTO `ts_area` VALUES ('371327', '莒南縣', '371300', '11');
INSERT INTO `ts_area` VALUES ('371328', '蒙陰縣', '371300', '12');
INSERT INTO `ts_area` VALUES ('371329', '臨沭縣', '371300', '13');
INSERT INTO `ts_area` VALUES ('371401', '市轄區', '371400', '1');
INSERT INTO `ts_area` VALUES ('371402', '德城區', '371400', '2');
INSERT INTO `ts_area` VALUES ('371421', '陵　縣', '371400', '3');
INSERT INTO `ts_area` VALUES ('371422', '寧津縣', '371400', '4');
INSERT INTO `ts_area` VALUES ('371423', '慶雲縣', '371400', '5');
INSERT INTO `ts_area` VALUES ('371424', '臨邑縣', '371400', '6');
INSERT INTO `ts_area` VALUES ('371425', '齊河縣', '371400', '7');
INSERT INTO `ts_area` VALUES ('371426', '平原縣', '371400', '8');
INSERT INTO `ts_area` VALUES ('371427', '夏津縣', '371400', '9');
INSERT INTO `ts_area` VALUES ('371428', '武城縣', '371400', '10');
INSERT INTO `ts_area` VALUES ('371481', '樂陵市', '371400', '11');
INSERT INTO `ts_area` VALUES ('371482', '禹城市', '371400', '12');
INSERT INTO `ts_area` VALUES ('371501', '市轄區', '371500', '1');
INSERT INTO `ts_area` VALUES ('371502', '東昌府區', '371500', '2');
INSERT INTO `ts_area` VALUES ('371521', '陽谷縣', '371500', '3');
INSERT INTO `ts_area` VALUES ('371522', '莘　縣', '371500', '4');
INSERT INTO `ts_area` VALUES ('371523', '茌平縣', '371500', '5');
INSERT INTO `ts_area` VALUES ('371524', '東阿縣', '371500', '6');
INSERT INTO `ts_area` VALUES ('371525', '冠　縣', '371500', '7');
INSERT INTO `ts_area` VALUES ('371526', '高唐縣', '371500', '8');
INSERT INTO `ts_area` VALUES ('371581', '臨清市', '371500', '9');
INSERT INTO `ts_area` VALUES ('371601', '市轄區', '371600', '1');
INSERT INTO `ts_area` VALUES ('371602', '濱城區', '371600', '2');
INSERT INTO `ts_area` VALUES ('371621', '惠民縣', '371600', '3');
INSERT INTO `ts_area` VALUES ('371622', '陽信縣', '371600', '4');
INSERT INTO `ts_area` VALUES ('371623', '無棣縣', '371600', '5');
INSERT INTO `ts_area` VALUES ('371624', '沾化縣', '371600', '6');
INSERT INTO `ts_area` VALUES ('371625', '博興縣', '371600', '7');
INSERT INTO `ts_area` VALUES ('371626', '鄒平縣', '371600', '8');
INSERT INTO `ts_area` VALUES ('371701', '市轄區', '371700', '1');
INSERT INTO `ts_area` VALUES ('371702', '牡丹區', '371700', '2');
INSERT INTO `ts_area` VALUES ('371721', '曹　縣', '371700', '3');
INSERT INTO `ts_area` VALUES ('371722', '單　縣', '371700', '4');
INSERT INTO `ts_area` VALUES ('371723', '成武縣', '371700', '5');
INSERT INTO `ts_area` VALUES ('371724', '鉅野縣', '371700', '6');
INSERT INTO `ts_area` VALUES ('371725', '鄆城縣', '371700', '7');
INSERT INTO `ts_area` VALUES ('371726', '鄄城縣', '371700', '8');
INSERT INTO `ts_area` VALUES ('371727', '定陶縣', '371700', '9');
INSERT INTO `ts_area` VALUES ('371728', '東明縣', '371700', '10');
INSERT INTO `ts_area` VALUES ('410101', '市轄區', '410100', '1');
INSERT INTO `ts_area` VALUES ('410102', '中原區', '410100', '2');
INSERT INTO `ts_area` VALUES ('410103', '二七區', '410100', '3');
INSERT INTO `ts_area` VALUES ('410104', '管城回族區', '410100', '4');
INSERT INTO `ts_area` VALUES ('410105', '金水區', '410100', '5');
INSERT INTO `ts_area` VALUES ('410106', '上街區', '410100', '6');
INSERT INTO `ts_area` VALUES ('410108', '邙山區', '410100', '7');
INSERT INTO `ts_area` VALUES ('410122', '中牟縣', '410100', '8');
INSERT INTO `ts_area` VALUES ('410181', '鞏義市', '410100', '9');
INSERT INTO `ts_area` VALUES ('410182', '滎陽市', '410100', '10');
INSERT INTO `ts_area` VALUES ('410183', '新密市', '410100', '11');
INSERT INTO `ts_area` VALUES ('410184', '新鄭市', '410100', '12');
INSERT INTO `ts_area` VALUES ('410185', '登封市', '410100', '13');
INSERT INTO `ts_area` VALUES ('410201', '市轄區', '410200', '1');
INSERT INTO `ts_area` VALUES ('410202', '龍亭區', '410200', '2');
INSERT INTO `ts_area` VALUES ('410203', '順河回族區', '410200', '3');
INSERT INTO `ts_area` VALUES ('410204', '鼓樓區', '410200', '4');
INSERT INTO `ts_area` VALUES ('410205', '南關區', '410200', '5');
INSERT INTO `ts_area` VALUES ('410211', '郊　區', '410200', '6');
INSERT INTO `ts_area` VALUES ('410221', '杞　縣', '410200', '7');
INSERT INTO `ts_area` VALUES ('410222', '通許縣', '410200', '8');
INSERT INTO `ts_area` VALUES ('410223', '尉氏縣', '410200', '9');
INSERT INTO `ts_area` VALUES ('410224', '開封縣', '410200', '10');
INSERT INTO `ts_area` VALUES ('410225', '蘭考縣', '410200', '11');
INSERT INTO `ts_area` VALUES ('410301', '市轄區', '410300', '1');
INSERT INTO `ts_area` VALUES ('410302', '老城區', '410300', '2');
INSERT INTO `ts_area` VALUES ('410303', '西工區', '410300', '3');
INSERT INTO `ts_area` VALUES ('410304', '廛河回族區', '410300', '4');
INSERT INTO `ts_area` VALUES ('410305', '澗西區', '410300', '5');
INSERT INTO `ts_area` VALUES ('410306', '吉利區', '410300', '6');
INSERT INTO `ts_area` VALUES ('410307', '洛龍區', '410300', '7');
INSERT INTO `ts_area` VALUES ('410322', '孟津縣', '410300', '8');
INSERT INTO `ts_area` VALUES ('410323', '新安縣', '410300', '9');
INSERT INTO `ts_area` VALUES ('410324', '欒川縣', '410300', '10');
INSERT INTO `ts_area` VALUES ('410325', '嵩　縣', '410300', '11');
INSERT INTO `ts_area` VALUES ('410326', '汝陽縣', '410300', '12');
INSERT INTO `ts_area` VALUES ('410327', '宜陽縣', '410300', '13');
INSERT INTO `ts_area` VALUES ('410328', '洛寧縣', '410300', '14');
INSERT INTO `ts_area` VALUES ('410329', '伊川縣', '410300', '15');
INSERT INTO `ts_area` VALUES ('410381', '偃師市', '410300', '16');
INSERT INTO `ts_area` VALUES ('410401', '市轄區', '410400', '1');
INSERT INTO `ts_area` VALUES ('410402', '新華區', '410400', '2');
INSERT INTO `ts_area` VALUES ('410403', '衛東區', '410400', '3');
INSERT INTO `ts_area` VALUES ('410404', '石龍區', '410400', '4');
INSERT INTO `ts_area` VALUES ('410411', '湛河區', '410400', '5');
INSERT INTO `ts_area` VALUES ('410421', '寶豐縣', '410400', '6');
INSERT INTO `ts_area` VALUES ('410422', '葉　縣', '410400', '7');
INSERT INTO `ts_area` VALUES ('410423', '魯山縣', '410400', '8');
INSERT INTO `ts_area` VALUES ('410425', '郟　縣', '410400', '9');
INSERT INTO `ts_area` VALUES ('410481', '舞鋼市', '410400', '10');
INSERT INTO `ts_area` VALUES ('410482', '汝州市', '410400', '11');
INSERT INTO `ts_area` VALUES ('410501', '市轄區', '410500', '1');
INSERT INTO `ts_area` VALUES ('410502', '文峰區', '410500', '2');
INSERT INTO `ts_area` VALUES ('410503', '北關區', '410500', '3');
INSERT INTO `ts_area` VALUES ('410505', '殷都區', '410500', '4');
INSERT INTO `ts_area` VALUES ('410506', '龍安區', '410500', '5');
INSERT INTO `ts_area` VALUES ('410522', '安陽縣', '410500', '6');
INSERT INTO `ts_area` VALUES ('410523', '湯陰縣', '410500', '7');
INSERT INTO `ts_area` VALUES ('410526', '滑　縣', '410500', '8');
INSERT INTO `ts_area` VALUES ('410527', '內黃縣', '410500', '9');
INSERT INTO `ts_area` VALUES ('410581', '林州市', '410500', '10');
INSERT INTO `ts_area` VALUES ('410601', '市轄區', '410600', '1');
INSERT INTO `ts_area` VALUES ('410602', '鶴山區', '410600', '2');
INSERT INTO `ts_area` VALUES ('410603', '山城區', '410600', '3');
INSERT INTO `ts_area` VALUES ('410611', '淇濱區', '410600', '4');
INSERT INTO `ts_area` VALUES ('410621', '浚　縣', '410600', '5');
INSERT INTO `ts_area` VALUES ('410622', '淇　縣', '410600', '6');
INSERT INTO `ts_area` VALUES ('410701', '市轄區', '410700', '1');
INSERT INTO `ts_area` VALUES ('410702', '紅旗區', '410700', '2');
INSERT INTO `ts_area` VALUES ('410703', '衛濱區', '410700', '3');
INSERT INTO `ts_area` VALUES ('410704', '鳳泉區', '410700', '4');
INSERT INTO `ts_area` VALUES ('410711', '牧野區', '410700', '5');
INSERT INTO `ts_area` VALUES ('410721', '新鄉縣', '410700', '6');
INSERT INTO `ts_area` VALUES ('410724', '獲嘉縣', '410700', '7');
INSERT INTO `ts_area` VALUES ('410725', '原陽縣', '410700', '8');
INSERT INTO `ts_area` VALUES ('410726', '延津縣', '410700', '9');
INSERT INTO `ts_area` VALUES ('410727', '封丘縣', '410700', '10');
INSERT INTO `ts_area` VALUES ('410728', '長垣縣', '410700', '11');
INSERT INTO `ts_area` VALUES ('410781', '衛輝市', '410700', '12');
INSERT INTO `ts_area` VALUES ('410782', '輝縣市', '410700', '13');
INSERT INTO `ts_area` VALUES ('410801', '市轄區', '410800', '1');
INSERT INTO `ts_area` VALUES ('410802', '解放區', '410800', '2');
INSERT INTO `ts_area` VALUES ('410803', '中站區', '410800', '3');
INSERT INTO `ts_area` VALUES ('410804', '馬村區', '410800', '4');
INSERT INTO `ts_area` VALUES ('410811', '山陽區', '410800', '5');
INSERT INTO `ts_area` VALUES ('410821', '修武縣', '410800', '6');
INSERT INTO `ts_area` VALUES ('410822', '博愛縣', '410800', '7');
INSERT INTO `ts_area` VALUES ('410823', '武陟縣', '410800', '8');
INSERT INTO `ts_area` VALUES ('410825', '溫　縣', '410800', '9');
INSERT INTO `ts_area` VALUES ('410881', '濟源市', '410800', '10');
INSERT INTO `ts_area` VALUES ('410882', '沁陽市', '410800', '11');
INSERT INTO `ts_area` VALUES ('410883', '孟州市', '410800', '12');
INSERT INTO `ts_area` VALUES ('410901', '市轄區', '410900', '1');
INSERT INTO `ts_area` VALUES ('410902', '華龍區', '410900', '2');
INSERT INTO `ts_area` VALUES ('410922', '清豐縣', '410900', '3');
INSERT INTO `ts_area` VALUES ('410923', '南樂縣', '410900', '4');
INSERT INTO `ts_area` VALUES ('410926', '範　縣', '410900', '5');
INSERT INTO `ts_area` VALUES ('410927', '臺前縣', '410900', '6');
INSERT INTO `ts_area` VALUES ('410928', '濮陽縣', '410900', '7');
INSERT INTO `ts_area` VALUES ('411001', '市轄區', '411000', '1');
INSERT INTO `ts_area` VALUES ('411002', '魏都區', '411000', '2');
INSERT INTO `ts_area` VALUES ('411023', '許昌縣', '411000', '3');
INSERT INTO `ts_area` VALUES ('411024', '鄢陵縣', '411000', '4');
INSERT INTO `ts_area` VALUES ('411025', '襄城縣', '411000', '5');
INSERT INTO `ts_area` VALUES ('411081', '禹州市', '411000', '6');
INSERT INTO `ts_area` VALUES ('411082', '長葛市', '411000', '7');
INSERT INTO `ts_area` VALUES ('411101', '市轄區', '411100', '1');
INSERT INTO `ts_area` VALUES ('411102', '源匯區', '411100', '2');
INSERT INTO `ts_area` VALUES ('411103', '郾城區', '411100', '3');
INSERT INTO `ts_area` VALUES ('411104', '召陵區', '411100', '4');
INSERT INTO `ts_area` VALUES ('411121', '舞陽縣', '411100', '5');
INSERT INTO `ts_area` VALUES ('411122', '臨潁縣', '411100', '6');
INSERT INTO `ts_area` VALUES ('411201', '市轄區', '411200', '1');
INSERT INTO `ts_area` VALUES ('411202', '湖濱區', '411200', '2');
INSERT INTO `ts_area` VALUES ('411221', '澠池縣', '411200', '3');
INSERT INTO `ts_area` VALUES ('411222', '陝　縣', '411200', '4');
INSERT INTO `ts_area` VALUES ('411224', '盧氏縣', '411200', '5');
INSERT INTO `ts_area` VALUES ('411281', '義馬市', '411200', '6');
INSERT INTO `ts_area` VALUES ('411282', '靈寶市', '411200', '7');
INSERT INTO `ts_area` VALUES ('411301', '市轄區', '411300', '1');
INSERT INTO `ts_area` VALUES ('411302', '宛城區', '411300', '2');
INSERT INTO `ts_area` VALUES ('411303', '臥龍區', '411300', '3');
INSERT INTO `ts_area` VALUES ('411321', '南召縣', '411300', '4');
INSERT INTO `ts_area` VALUES ('411322', '方城縣', '411300', '5');
INSERT INTO `ts_area` VALUES ('411323', '西峽縣', '411300', '6');
INSERT INTO `ts_area` VALUES ('411324', '鎮平縣', '411300', '7');
INSERT INTO `ts_area` VALUES ('411325', '內鄉縣', '411300', '8');
INSERT INTO `ts_area` VALUES ('411326', '淅川縣', '411300', '9');
INSERT INTO `ts_area` VALUES ('411327', '社旗縣', '411300', '10');
INSERT INTO `ts_area` VALUES ('411328', '唐河縣', '411300', '11');
INSERT INTO `ts_area` VALUES ('411329', '新野縣', '411300', '12');
INSERT INTO `ts_area` VALUES ('411330', '桐柏縣', '411300', '13');
INSERT INTO `ts_area` VALUES ('411381', '鄧州市', '411300', '14');
INSERT INTO `ts_area` VALUES ('411401', '市轄區', '411400', '1');
INSERT INTO `ts_area` VALUES ('411402', '梁園區', '411400', '2');
INSERT INTO `ts_area` VALUES ('411403', '睢陽區', '411400', '3');
INSERT INTO `ts_area` VALUES ('411421', '民權縣', '411400', '4');
INSERT INTO `ts_area` VALUES ('411422', '睢　縣', '411400', '5');
INSERT INTO `ts_area` VALUES ('411423', '寧陵縣', '411400', '6');
INSERT INTO `ts_area` VALUES ('411424', '柘城縣', '411400', '7');
INSERT INTO `ts_area` VALUES ('411425', '虞城縣', '411400', '8');
INSERT INTO `ts_area` VALUES ('411426', '夏邑縣', '411400', '9');
INSERT INTO `ts_area` VALUES ('411481', '永城市', '411400', '10');
INSERT INTO `ts_area` VALUES ('411501', '市轄區', '411500', '1');
INSERT INTO `ts_area` VALUES ('411502', '師河區', '411500', '2');
INSERT INTO `ts_area` VALUES ('411503', '平橋區', '411500', '3');
INSERT INTO `ts_area` VALUES ('411521', '羅山縣', '411500', '4');
INSERT INTO `ts_area` VALUES ('411522', '光山縣', '411500', '5');
INSERT INTO `ts_area` VALUES ('411523', '新　縣', '411500', '6');
INSERT INTO `ts_area` VALUES ('411524', '商城縣', '411500', '7');
INSERT INTO `ts_area` VALUES ('411525', '固始縣', '411500', '8');
INSERT INTO `ts_area` VALUES ('411526', '潢川縣', '411500', '9');
INSERT INTO `ts_area` VALUES ('411527', '淮濱縣', '411500', '10');
INSERT INTO `ts_area` VALUES ('411528', '息　縣', '411500', '11');
INSERT INTO `ts_area` VALUES ('411601', '市轄區', '411600', '1');
INSERT INTO `ts_area` VALUES ('411602', '川匯區', '411600', '2');
INSERT INTO `ts_area` VALUES ('411621', '扶溝縣', '411600', '3');
INSERT INTO `ts_area` VALUES ('411622', '西華縣', '411600', '4');
INSERT INTO `ts_area` VALUES ('411623', '商水縣', '411600', '5');
INSERT INTO `ts_area` VALUES ('411624', '沈丘縣', '411600', '6');
INSERT INTO `ts_area` VALUES ('411625', '鄲城縣', '411600', '7');
INSERT INTO `ts_area` VALUES ('411626', '淮陽縣', '411600', '8');
INSERT INTO `ts_area` VALUES ('411627', '太康縣', '411600', '9');
INSERT INTO `ts_area` VALUES ('411628', '鹿邑縣', '411600', '10');
INSERT INTO `ts_area` VALUES ('411681', '項城市', '411600', '11');
INSERT INTO `ts_area` VALUES ('411701', '市轄區', '411700', '1');
INSERT INTO `ts_area` VALUES ('411702', '驛城區', '411700', '2');
INSERT INTO `ts_area` VALUES ('411721', '西平縣', '411700', '3');
INSERT INTO `ts_area` VALUES ('411722', '上蔡縣', '411700', '4');
INSERT INTO `ts_area` VALUES ('411723', '平輿縣', '411700', '5');
INSERT INTO `ts_area` VALUES ('411724', '正陽縣', '411700', '6');
INSERT INTO `ts_area` VALUES ('411725', '確山縣', '411700', '7');
INSERT INTO `ts_area` VALUES ('411726', '泌陽縣', '411700', '8');
INSERT INTO `ts_area` VALUES ('411727', '汝南縣', '411700', '9');
INSERT INTO `ts_area` VALUES ('411728', '遂平縣', '411700', '10');
INSERT INTO `ts_area` VALUES ('411729', '新蔡縣', '411700', '11');
INSERT INTO `ts_area` VALUES ('420101', '市轄區', '420100', '1');
INSERT INTO `ts_area` VALUES ('420102', '江岸區', '420100', '2');
INSERT INTO `ts_area` VALUES ('420103', '江漢區', '420100', '3');
INSERT INTO `ts_area` VALUES ('420104', '喬口區', '420100', '4');
INSERT INTO `ts_area` VALUES ('420105', '漢陽區', '420100', '5');
INSERT INTO `ts_area` VALUES ('420106', '武昌區', '420100', '6');
INSERT INTO `ts_area` VALUES ('420107', '青山區', '420100', '7');
INSERT INTO `ts_area` VALUES ('420111', '洪山區', '420100', '8');
INSERT INTO `ts_area` VALUES ('420112', '東西湖區', '420100', '9');
INSERT INTO `ts_area` VALUES ('420113', '漢南區', '420100', '10');
INSERT INTO `ts_area` VALUES ('420114', '蔡甸區', '420100', '11');
INSERT INTO `ts_area` VALUES ('420115', '江夏區', '420100', '12');
INSERT INTO `ts_area` VALUES ('420116', '黃陂區', '420100', '13');
INSERT INTO `ts_area` VALUES ('420117', '新洲區', '420100', '14');
INSERT INTO `ts_area` VALUES ('420201', '市轄區', '420200', '1');
INSERT INTO `ts_area` VALUES ('420202', '黃石港區', '420200', '2');
INSERT INTO `ts_area` VALUES ('420203', '西塞山區', '420200', '3');
INSERT INTO `ts_area` VALUES ('420204', '下陸區', '420200', '4');
INSERT INTO `ts_area` VALUES ('420205', '鐵山區', '420200', '5');
INSERT INTO `ts_area` VALUES ('420222', '陽新縣', '420200', '6');
INSERT INTO `ts_area` VALUES ('420281', '大冶市', '420200', '7');
INSERT INTO `ts_area` VALUES ('420301', '市轄區', '420300', '1');
INSERT INTO `ts_area` VALUES ('420302', '茅箭區', '420300', '2');
INSERT INTO `ts_area` VALUES ('420303', '張灣區', '420300', '3');
INSERT INTO `ts_area` VALUES ('420321', '鄖　縣', '420300', '4');
INSERT INTO `ts_area` VALUES ('420322', '鄖西縣', '420300', '5');
INSERT INTO `ts_area` VALUES ('420323', '竹山縣', '420300', '6');
INSERT INTO `ts_area` VALUES ('420324', '竹溪縣', '420300', '7');
INSERT INTO `ts_area` VALUES ('420325', '房　縣', '420300', '8');
INSERT INTO `ts_area` VALUES ('420381', '丹江口市', '420300', '9');
INSERT INTO `ts_area` VALUES ('420501', '市轄區', '420500', '1');
INSERT INTO `ts_area` VALUES ('420502', '西陵區', '420500', '2');
INSERT INTO `ts_area` VALUES ('420503', '伍家崗區', '420500', '3');
INSERT INTO `ts_area` VALUES ('420504', '點軍區', '420500', '4');
INSERT INTO `ts_area` VALUES ('420505', '猇亭區', '420500', '5');
INSERT INTO `ts_area` VALUES ('420506', '夷陵區', '420500', '6');
INSERT INTO `ts_area` VALUES ('420525', '遠安縣', '420500', '7');
INSERT INTO `ts_area` VALUES ('420526', '興山縣', '420500', '8');
INSERT INTO `ts_area` VALUES ('420527', '秭歸縣', '420500', '9');
INSERT INTO `ts_area` VALUES ('420528', '長陽土家族自治縣', '420500', '10');
INSERT INTO `ts_area` VALUES ('420529', '五峰土家族自治縣', '420500', '11');
INSERT INTO `ts_area` VALUES ('420581', '宜都市', '420500', '12');
INSERT INTO `ts_area` VALUES ('420582', '當陽市', '420500', '13');
INSERT INTO `ts_area` VALUES ('420583', '枝江市', '420500', '14');
INSERT INTO `ts_area` VALUES ('420601', '市轄區', '420600', '1');
INSERT INTO `ts_area` VALUES ('420602', '襄城區', '420600', '2');
INSERT INTO `ts_area` VALUES ('420606', '樊城區', '420600', '3');
INSERT INTO `ts_area` VALUES ('420607', '襄陽區', '420600', '4');
INSERT INTO `ts_area` VALUES ('420624', '南漳縣', '420600', '5');
INSERT INTO `ts_area` VALUES ('420625', '谷城縣', '420600', '6');
INSERT INTO `ts_area` VALUES ('420626', '保康縣', '420600', '7');
INSERT INTO `ts_area` VALUES ('420682', '老河口市', '420600', '8');
INSERT INTO `ts_area` VALUES ('420683', '棗陽市', '420600', '9');
INSERT INTO `ts_area` VALUES ('420684', '宜城市', '420600', '10');
INSERT INTO `ts_area` VALUES ('420701', '市轄區', '420700', '1');
INSERT INTO `ts_area` VALUES ('420702', '梁子湖區', '420700', '2');
INSERT INTO `ts_area` VALUES ('420703', '華容區', '420700', '3');
INSERT INTO `ts_area` VALUES ('420704', '鄂城區', '420700', '4');
INSERT INTO `ts_area` VALUES ('420801', '市轄區', '420800', '1');
INSERT INTO `ts_area` VALUES ('420802', '東寶區', '420800', '2');
INSERT INTO `ts_area` VALUES ('420804', '掇刀區', '420800', '3');
INSERT INTO `ts_area` VALUES ('420821', '京山縣', '420800', '4');
INSERT INTO `ts_area` VALUES ('420822', '沙洋縣', '420800', '5');
INSERT INTO `ts_area` VALUES ('420881', '鐘祥市', '420800', '6');
INSERT INTO `ts_area` VALUES ('420901', '市轄區', '420900', '1');
INSERT INTO `ts_area` VALUES ('420902', '孝南區', '420900', '2');
INSERT INTO `ts_area` VALUES ('420921', '孝昌縣', '420900', '3');
INSERT INTO `ts_area` VALUES ('420922', '大悟縣', '420900', '4');
INSERT INTO `ts_area` VALUES ('420923', '雲夢縣', '420900', '5');
INSERT INTO `ts_area` VALUES ('420981', '應城市', '420900', '6');
INSERT INTO `ts_area` VALUES ('420982', '安陸市', '420900', '7');
INSERT INTO `ts_area` VALUES ('420984', '漢川市', '420900', '8');
INSERT INTO `ts_area` VALUES ('421001', '市轄區', '421000', '1');
INSERT INTO `ts_area` VALUES ('421002', '沙市區', '421000', '2');
INSERT INTO `ts_area` VALUES ('421003', '荊州區', '421000', '3');
INSERT INTO `ts_area` VALUES ('421022', '公安縣', '421000', '4');
INSERT INTO `ts_area` VALUES ('421023', '監利縣', '421000', '5');
INSERT INTO `ts_area` VALUES ('421024', '江陵縣', '421000', '6');
INSERT INTO `ts_area` VALUES ('421081', '石首市', '421000', '7');
INSERT INTO `ts_area` VALUES ('421083', '洪湖市', '421000', '8');
INSERT INTO `ts_area` VALUES ('421087', '鬆滋市', '421000', '9');
INSERT INTO `ts_area` VALUES ('421101', '市轄區', '421100', '1');
INSERT INTO `ts_area` VALUES ('421102', '黃州區', '421100', '2');
INSERT INTO `ts_area` VALUES ('421121', '團風縣', '421100', '3');
INSERT INTO `ts_area` VALUES ('421122', '紅安縣', '421100', '4');
INSERT INTO `ts_area` VALUES ('421123', '羅田縣', '421100', '5');
INSERT INTO `ts_area` VALUES ('421124', '英山縣', '421100', '6');
INSERT INTO `ts_area` VALUES ('421125', '浠水縣', '421100', '7');
INSERT INTO `ts_area` VALUES ('421126', '蘄春縣', '421100', '8');
INSERT INTO `ts_area` VALUES ('421127', '黃梅縣', '421100', '9');
INSERT INTO `ts_area` VALUES ('421181', '麻城市', '421100', '10');
INSERT INTO `ts_area` VALUES ('421182', '武穴市', '421100', '11');
INSERT INTO `ts_area` VALUES ('421201', '市轄區', '421200', '1');
INSERT INTO `ts_area` VALUES ('421202', '咸安區', '421200', '2');
INSERT INTO `ts_area` VALUES ('421221', '嘉魚縣', '421200', '3');
INSERT INTO `ts_area` VALUES ('421222', '通城縣', '421200', '4');
INSERT INTO `ts_area` VALUES ('421223', '崇陽縣', '421200', '5');
INSERT INTO `ts_area` VALUES ('421224', '通山縣', '421200', '6');
INSERT INTO `ts_area` VALUES ('421281', '赤壁市', '421200', '7');
INSERT INTO `ts_area` VALUES ('421301', '市轄區', '421300', '1');
INSERT INTO `ts_area` VALUES ('421302', '曾都區', '421300', '2');
INSERT INTO `ts_area` VALUES ('421381', '廣水市', '421300', '3');
INSERT INTO `ts_area` VALUES ('422801', '恩施市', '422800', '1');
INSERT INTO `ts_area` VALUES ('422802', '利川市', '422800', '2');
INSERT INTO `ts_area` VALUES ('422822', '建始縣', '422800', '3');
INSERT INTO `ts_area` VALUES ('422823', '巴東縣', '422800', '4');
INSERT INTO `ts_area` VALUES ('422825', '宣恩縣', '422800', '5');
INSERT INTO `ts_area` VALUES ('422826', '咸豐縣', '422800', '6');
INSERT INTO `ts_area` VALUES ('422827', '來鳳縣', '422800', '7');
INSERT INTO `ts_area` VALUES ('422828', '鶴峰縣', '422800', '8');
INSERT INTO `ts_area` VALUES ('429004', '仙桃市', '429000', '1');
INSERT INTO `ts_area` VALUES ('429005', '潛江市', '429000', '2');
INSERT INTO `ts_area` VALUES ('429006', '天門市', '429000', '3');
INSERT INTO `ts_area` VALUES ('429021', '神農架林區', '429000', '4');
INSERT INTO `ts_area` VALUES ('430101', '市轄區', '430100', '1');
INSERT INTO `ts_area` VALUES ('430102', '芙蓉區', '430100', '2');
INSERT INTO `ts_area` VALUES ('430103', '天心區', '430100', '3');
INSERT INTO `ts_area` VALUES ('430104', '嶽麓區', '430100', '4');
INSERT INTO `ts_area` VALUES ('430105', '開福區', '430100', '5');
INSERT INTO `ts_area` VALUES ('430111', '雨花區', '430100', '6');
INSERT INTO `ts_area` VALUES ('430121', '長沙縣', '430100', '7');
INSERT INTO `ts_area` VALUES ('430122', '望城縣', '430100', '8');
INSERT INTO `ts_area` VALUES ('430124', '寧鄉縣', '430100', '9');
INSERT INTO `ts_area` VALUES ('430181', '瀏陽市', '430100', '10');
INSERT INTO `ts_area` VALUES ('430201', '市轄區', '430200', '1');
INSERT INTO `ts_area` VALUES ('430202', '荷塘區', '430200', '2');
INSERT INTO `ts_area` VALUES ('430203', '蘆淞區', '430200', '3');
INSERT INTO `ts_area` VALUES ('430204', '石峰區', '430200', '4');
INSERT INTO `ts_area` VALUES ('430211', '天元區', '430200', '5');
INSERT INTO `ts_area` VALUES ('430221', '株洲縣', '430200', '6');
INSERT INTO `ts_area` VALUES ('430223', '攸　縣', '430200', '7');
INSERT INTO `ts_area` VALUES ('430224', '茶陵縣', '430200', '8');
INSERT INTO `ts_area` VALUES ('430225', '炎陵縣', '430200', '9');
INSERT INTO `ts_area` VALUES ('430281', '醴陵市', '430200', '10');
INSERT INTO `ts_area` VALUES ('430301', '市轄區', '430300', '1');
INSERT INTO `ts_area` VALUES ('430302', '雨湖區', '430300', '2');
INSERT INTO `ts_area` VALUES ('430304', '嶽塘區', '430300', '3');
INSERT INTO `ts_area` VALUES ('430321', '湘潭縣', '430300', '4');
INSERT INTO `ts_area` VALUES ('430381', '湘鄉市', '430300', '5');
INSERT INTO `ts_area` VALUES ('430382', '韶山市', '430300', '6');
INSERT INTO `ts_area` VALUES ('430401', '市轄區', '430400', '1');
INSERT INTO `ts_area` VALUES ('430405', '珠暉區', '430400', '2');
INSERT INTO `ts_area` VALUES ('430406', '雁峰區', '430400', '3');
INSERT INTO `ts_area` VALUES ('430407', '石鼓區', '430400', '4');
INSERT INTO `ts_area` VALUES ('430408', '蒸湘區', '430400', '5');
INSERT INTO `ts_area` VALUES ('430412', '南嶽區', '430400', '6');
INSERT INTO `ts_area` VALUES ('430421', '衡陽縣', '430400', '7');
INSERT INTO `ts_area` VALUES ('430422', '衡南縣', '430400', '8');
INSERT INTO `ts_area` VALUES ('430423', '衡山縣', '430400', '9');
INSERT INTO `ts_area` VALUES ('430424', '衡東縣', '430400', '10');
INSERT INTO `ts_area` VALUES ('430426', '祁東縣', '430400', '11');
INSERT INTO `ts_area` VALUES ('430481', '耒陽市', '430400', '12');
INSERT INTO `ts_area` VALUES ('430482', '常寧市', '430400', '13');
INSERT INTO `ts_area` VALUES ('430501', '市轄區', '430500', '1');
INSERT INTO `ts_area` VALUES ('430502', '雙清區', '430500', '2');
INSERT INTO `ts_area` VALUES ('430503', '大祥區', '430500', '3');
INSERT INTO `ts_area` VALUES ('430511', '北塔區', '430500', '4');
INSERT INTO `ts_area` VALUES ('430521', '邵東縣', '430500', '5');
INSERT INTO `ts_area` VALUES ('430522', '新邵縣', '430500', '6');
INSERT INTO `ts_area` VALUES ('430523', '邵陽縣', '430500', '7');
INSERT INTO `ts_area` VALUES ('430524', '隆回縣', '430500', '8');
INSERT INTO `ts_area` VALUES ('430525', '洞口縣', '430500', '9');
INSERT INTO `ts_area` VALUES ('430527', '綏寧縣', '430500', '10');
INSERT INTO `ts_area` VALUES ('430528', '新寧縣', '430500', '11');
INSERT INTO `ts_area` VALUES ('430529', '城步苗族自治縣', '430500', '12');
INSERT INTO `ts_area` VALUES ('430581', '武岡市', '430500', '13');
INSERT INTO `ts_area` VALUES ('430601', '市轄區', '430600', '1');
INSERT INTO `ts_area` VALUES ('430602', '岳陽樓區', '430600', '2');
INSERT INTO `ts_area` VALUES ('430603', '云溪區', '430600', '3');
INSERT INTO `ts_area` VALUES ('430611', '君山區', '430600', '4');
INSERT INTO `ts_area` VALUES ('430621', '岳陽縣', '430600', '5');
INSERT INTO `ts_area` VALUES ('430623', '華容縣', '430600', '6');
INSERT INTO `ts_area` VALUES ('430624', '湘陰縣', '430600', '7');
INSERT INTO `ts_area` VALUES ('430626', '平江縣', '430600', '8');
INSERT INTO `ts_area` VALUES ('430681', '汨羅市', '430600', '9');
INSERT INTO `ts_area` VALUES ('430682', '臨湘市', '430600', '10');
INSERT INTO `ts_area` VALUES ('430701', '市轄區', '430700', '1');
INSERT INTO `ts_area` VALUES ('430702', '武陵區', '430700', '2');
INSERT INTO `ts_area` VALUES ('430703', '鼎城區', '430700', '3');
INSERT INTO `ts_area` VALUES ('430721', '安鄉縣', '430700', '4');
INSERT INTO `ts_area` VALUES ('430722', '漢壽縣', '430700', '5');
INSERT INTO `ts_area` VALUES ('430723', '澧　縣', '430700', '6');
INSERT INTO `ts_area` VALUES ('430724', '臨澧縣', '430700', '7');
INSERT INTO `ts_area` VALUES ('430725', '桃源縣', '430700', '8');
INSERT INTO `ts_area` VALUES ('430726', '石門縣', '430700', '9');
INSERT INTO `ts_area` VALUES ('430781', '津市市', '430700', '10');
INSERT INTO `ts_area` VALUES ('430801', '市轄區', '430800', '1');
INSERT INTO `ts_area` VALUES ('430802', '永定區', '430800', '2');
INSERT INTO `ts_area` VALUES ('430811', '武陵源區', '430800', '3');
INSERT INTO `ts_area` VALUES ('430821', '慈利縣', '430800', '4');
INSERT INTO `ts_area` VALUES ('430822', '桑植縣', '430800', '5');
INSERT INTO `ts_area` VALUES ('430901', '市轄區', '430900', '1');
INSERT INTO `ts_area` VALUES ('430902', '資陽區', '430900', '2');
INSERT INTO `ts_area` VALUES ('430903', '赫山區', '430900', '3');
INSERT INTO `ts_area` VALUES ('430921', '南　縣', '430900', '4');
INSERT INTO `ts_area` VALUES ('430922', '桃江縣', '430900', '5');
INSERT INTO `ts_area` VALUES ('430923', '安化縣', '430900', '6');
INSERT INTO `ts_area` VALUES ('430981', '沅江市', '430900', '7');
INSERT INTO `ts_area` VALUES ('431001', '市轄區', '431000', '1');
INSERT INTO `ts_area` VALUES ('431002', '北湖區', '431000', '2');
INSERT INTO `ts_area` VALUES ('431003', '甦仙區', '431000', '3');
INSERT INTO `ts_area` VALUES ('431021', '桂陽縣', '431000', '4');
INSERT INTO `ts_area` VALUES ('431022', '宜章縣', '431000', '5');
INSERT INTO `ts_area` VALUES ('431023', '永興縣', '431000', '6');
INSERT INTO `ts_area` VALUES ('431024', '嘉禾縣', '431000', '7');
INSERT INTO `ts_area` VALUES ('431025', '臨武縣', '431000', '8');
INSERT INTO `ts_area` VALUES ('431026', '汝城縣', '431000', '9');
INSERT INTO `ts_area` VALUES ('431027', '桂東縣', '431000', '10');
INSERT INTO `ts_area` VALUES ('431028', '安仁縣', '431000', '11');
INSERT INTO `ts_area` VALUES ('431081', '資興市', '431000', '12');
INSERT INTO `ts_area` VALUES ('431101', '市轄區', '431100', '1');
INSERT INTO `ts_area` VALUES ('431102', '芝山區', '431100', '2');
INSERT INTO `ts_area` VALUES ('431103', '冷水灘區', '431100', '3');
INSERT INTO `ts_area` VALUES ('431121', '祁陽縣', '431100', '4');
INSERT INTO `ts_area` VALUES ('431122', '東安縣', '431100', '5');
INSERT INTO `ts_area` VALUES ('431123', '雙牌縣', '431100', '6');
INSERT INTO `ts_area` VALUES ('431124', '道　縣', '431100', '7');
INSERT INTO `ts_area` VALUES ('431125', '江永縣', '431100', '8');
INSERT INTO `ts_area` VALUES ('431126', '寧遠縣', '431100', '9');
INSERT INTO `ts_area` VALUES ('431127', '藍山縣', '431100', '10');
INSERT INTO `ts_area` VALUES ('431128', '新田縣', '431100', '11');
INSERT INTO `ts_area` VALUES ('431129', '江華瑤族自治縣', '431100', '12');
INSERT INTO `ts_area` VALUES ('431201', '市轄區', '431200', '1');
INSERT INTO `ts_area` VALUES ('431202', '鶴城區', '431200', '2');
INSERT INTO `ts_area` VALUES ('431221', '中方縣', '431200', '3');
INSERT INTO `ts_area` VALUES ('431222', '沅陵縣', '431200', '4');
INSERT INTO `ts_area` VALUES ('431223', '辰溪縣', '431200', '5');
INSERT INTO `ts_area` VALUES ('431224', '漵浦縣', '431200', '6');
INSERT INTO `ts_area` VALUES ('431225', '會同縣', '431200', '7');
INSERT INTO `ts_area` VALUES ('431226', '麻陽苗族自治縣', '431200', '8');
INSERT INTO `ts_area` VALUES ('431227', '新晃侗族自治縣', '431200', '9');
INSERT INTO `ts_area` VALUES ('431228', '芷江侗族自治縣', '431200', '10');
INSERT INTO `ts_area` VALUES ('431229', '靖州苗族侗族自治縣', '431200', '11');
INSERT INTO `ts_area` VALUES ('431230', '通道侗族自治縣', '431200', '12');
INSERT INTO `ts_area` VALUES ('431281', '洪江市', '431200', '13');
INSERT INTO `ts_area` VALUES ('431301', '市轄區', '431300', '1');
INSERT INTO `ts_area` VALUES ('431302', '婁星區', '431300', '2');
INSERT INTO `ts_area` VALUES ('431321', '雙峰縣', '431300', '3');
INSERT INTO `ts_area` VALUES ('431322', '新化縣', '431300', '4');
INSERT INTO `ts_area` VALUES ('431381', '冷水江市', '431300', '5');
INSERT INTO `ts_area` VALUES ('431382', '漣源市', '431300', '6');
INSERT INTO `ts_area` VALUES ('433101', '吉首市', '433100', '1');
INSERT INTO `ts_area` VALUES ('433122', '瀘溪縣', '433100', '2');
INSERT INTO `ts_area` VALUES ('433123', '鳳凰縣', '433100', '3');
INSERT INTO `ts_area` VALUES ('433124', '花垣縣', '433100', '4');
INSERT INTO `ts_area` VALUES ('433125', '保靖縣', '433100', '5');
INSERT INTO `ts_area` VALUES ('433126', '古丈縣', '433100', '6');
INSERT INTO `ts_area` VALUES ('433127', '永順縣', '433100', '7');
INSERT INTO `ts_area` VALUES ('433130', '龍山縣', '433100', '8');
INSERT INTO `ts_area` VALUES ('440101', '市轄區', '440100', '1');
INSERT INTO `ts_area` VALUES ('440102', '東山區', '440100', '2');
INSERT INTO `ts_area` VALUES ('440103', '荔灣區', '440100', '3');
INSERT INTO `ts_area` VALUES ('440104', '越秀區', '440100', '4');
INSERT INTO `ts_area` VALUES ('440105', '海珠區', '440100', '5');
INSERT INTO `ts_area` VALUES ('440106', '天河區', '440100', '6');
INSERT INTO `ts_area` VALUES ('440107', '芳村區', '440100', '7');
INSERT INTO `ts_area` VALUES ('440111', '白雲區', '440100', '8');
INSERT INTO `ts_area` VALUES ('440112', '黃埔區', '440100', '9');
INSERT INTO `ts_area` VALUES ('440113', '番禺區', '440100', '10');
INSERT INTO `ts_area` VALUES ('440114', '花都區', '440100', '11');
INSERT INTO `ts_area` VALUES ('440183', '增城市', '440100', '12');
INSERT INTO `ts_area` VALUES ('440184', '從化市', '440100', '13');
INSERT INTO `ts_area` VALUES ('440201', '市轄區', '440200', '1');
INSERT INTO `ts_area` VALUES ('440203', '武江區', '440200', '2');
INSERT INTO `ts_area` VALUES ('440204', '湞江區', '440200', '3');
INSERT INTO `ts_area` VALUES ('440205', '曲江區', '440200', '4');
INSERT INTO `ts_area` VALUES ('440222', '始興縣', '440200', '5');
INSERT INTO `ts_area` VALUES ('440224', '仁化縣', '440200', '6');
INSERT INTO `ts_area` VALUES ('440229', '翁源縣', '440200', '7');
INSERT INTO `ts_area` VALUES ('440232', '乳源瑤族自治縣', '440200', '8');
INSERT INTO `ts_area` VALUES ('440233', '新豐縣', '440200', '9');
INSERT INTO `ts_area` VALUES ('440281', '樂昌市', '440200', '10');
INSERT INTO `ts_area` VALUES ('440282', '南雄市', '440200', '11');
INSERT INTO `ts_area` VALUES ('440301', '市轄區', '440300', '1');
INSERT INTO `ts_area` VALUES ('440303', '羅湖區', '440300', '2');
INSERT INTO `ts_area` VALUES ('440304', '福田區', '440300', '3');
INSERT INTO `ts_area` VALUES ('440305', '南山區', '440300', '4');
INSERT INTO `ts_area` VALUES ('440306', '寶安區', '440300', '5');
INSERT INTO `ts_area` VALUES ('440307', '龍崗區', '440300', '6');
INSERT INTO `ts_area` VALUES ('440308', '鹽田區', '440300', '7');
INSERT INTO `ts_area` VALUES ('440401', '市轄區', '440400', '1');
INSERT INTO `ts_area` VALUES ('440402', '香洲區', '440400', '2');
INSERT INTO `ts_area` VALUES ('440403', '斗門區', '440400', '3');
INSERT INTO `ts_area` VALUES ('440404', '金灣區', '440400', '4');
INSERT INTO `ts_area` VALUES ('440501', '市轄區', '440500', '1');
INSERT INTO `ts_area` VALUES ('440507', '龍湖區', '440500', '2');
INSERT INTO `ts_area` VALUES ('440511', '金平區', '440500', '3');
INSERT INTO `ts_area` VALUES ('440512', '濠江區', '440500', '4');
INSERT INTO `ts_area` VALUES ('440513', '潮陽區', '440500', '5');
INSERT INTO `ts_area` VALUES ('440514', '潮南區', '440500', '6');
INSERT INTO `ts_area` VALUES ('440515', '澄海區', '440500', '7');
INSERT INTO `ts_area` VALUES ('440523', '南澳縣', '440500', '8');
INSERT INTO `ts_area` VALUES ('440601', '市轄區', '440600', '1');
INSERT INTO `ts_area` VALUES ('440604', '禪城區', '440600', '2');
INSERT INTO `ts_area` VALUES ('440605', '南海區', '440600', '3');
INSERT INTO `ts_area` VALUES ('440606', '順德區', '440600', '4');
INSERT INTO `ts_area` VALUES ('440607', '三水區', '440600', '5');
INSERT INTO `ts_area` VALUES ('440608', '高明區', '440600', '6');
INSERT INTO `ts_area` VALUES ('440701', '市轄區', '440700', '1');
INSERT INTO `ts_area` VALUES ('440703', '蓬江區', '440700', '2');
INSERT INTO `ts_area` VALUES ('440704', '江海區', '440700', '3');
INSERT INTO `ts_area` VALUES ('440705', '新會區', '440700', '4');
INSERT INTO `ts_area` VALUES ('440781', '臺山市', '440700', '5');
INSERT INTO `ts_area` VALUES ('440783', '開平市', '440700', '6');
INSERT INTO `ts_area` VALUES ('440784', '鶴山市', '440700', '7');
INSERT INTO `ts_area` VALUES ('440785', '恩平市', '440700', '8');
INSERT INTO `ts_area` VALUES ('440801', '市轄區', '440800', '1');
INSERT INTO `ts_area` VALUES ('440802', '赤坎區', '440800', '2');
INSERT INTO `ts_area` VALUES ('440803', '霞山區', '440800', '3');
INSERT INTO `ts_area` VALUES ('440804', '坡頭區', '440800', '4');
INSERT INTO `ts_area` VALUES ('440811', '麻章區', '440800', '5');
INSERT INTO `ts_area` VALUES ('440823', '遂溪縣', '440800', '6');
INSERT INTO `ts_area` VALUES ('440825', '徐聞縣', '440800', '7');
INSERT INTO `ts_area` VALUES ('440881', '廉江市', '440800', '8');
INSERT INTO `ts_area` VALUES ('440882', '雷州市', '440800', '9');
INSERT INTO `ts_area` VALUES ('440883', '吳川市', '440800', '10');
INSERT INTO `ts_area` VALUES ('440901', '市轄區', '440900', '1');
INSERT INTO `ts_area` VALUES ('440902', '茂南區', '440900', '2');
INSERT INTO `ts_area` VALUES ('440903', '茂港區', '440900', '3');
INSERT INTO `ts_area` VALUES ('440923', '電白縣', '440900', '4');
INSERT INTO `ts_area` VALUES ('440981', '高州市', '440900', '5');
INSERT INTO `ts_area` VALUES ('440982', '化州市', '440900', '6');
INSERT INTO `ts_area` VALUES ('440983', '信宜市', '440900', '7');
INSERT INTO `ts_area` VALUES ('441201', '市轄區', '441200', '1');
INSERT INTO `ts_area` VALUES ('441202', '端州區', '441200', '2');
INSERT INTO `ts_area` VALUES ('441203', '鼎湖區', '441200', '3');
INSERT INTO `ts_area` VALUES ('441223', '廣寧縣', '441200', '4');
INSERT INTO `ts_area` VALUES ('441224', '懷集縣', '441200', '5');
INSERT INTO `ts_area` VALUES ('441225', '封開縣', '441200', '6');
INSERT INTO `ts_area` VALUES ('441226', '德慶縣', '441200', '7');
INSERT INTO `ts_area` VALUES ('441283', '高要市', '441200', '8');
INSERT INTO `ts_area` VALUES ('441284', '四會市', '441200', '9');
INSERT INTO `ts_area` VALUES ('441301', '市轄區', '441300', '1');
INSERT INTO `ts_area` VALUES ('441302', '惠城區', '441300', '2');
INSERT INTO `ts_area` VALUES ('441303', '惠陽區', '441300', '3');
INSERT INTO `ts_area` VALUES ('441322', '博羅縣', '441300', '4');
INSERT INTO `ts_area` VALUES ('441323', '惠東縣', '441300', '5');
INSERT INTO `ts_area` VALUES ('441324', '龍門縣', '441300', '6');
INSERT INTO `ts_area` VALUES ('441401', '市轄區', '441400', '1');
INSERT INTO `ts_area` VALUES ('441402', '梅江區', '441400', '2');
INSERT INTO `ts_area` VALUES ('441421', '梅　縣', '441400', '3');
INSERT INTO `ts_area` VALUES ('441422', '大埔縣', '441400', '4');
INSERT INTO `ts_area` VALUES ('441423', '豐順縣', '441400', '5');
INSERT INTO `ts_area` VALUES ('441424', '五華縣', '441400', '6');
INSERT INTO `ts_area` VALUES ('441426', '平遠縣', '441400', '7');
INSERT INTO `ts_area` VALUES ('441427', '蕉嶺縣', '441400', '8');
INSERT INTO `ts_area` VALUES ('441481', '興寧市', '441400', '9');
INSERT INTO `ts_area` VALUES ('441501', '市轄區', '441500', '1');
INSERT INTO `ts_area` VALUES ('441502', '城　區', '441500', '2');
INSERT INTO `ts_area` VALUES ('441521', '海豐縣', '441500', '3');
INSERT INTO `ts_area` VALUES ('441523', '陸河縣', '441500', '4');
INSERT INTO `ts_area` VALUES ('441581', '陸豐市', '441500', '5');
INSERT INTO `ts_area` VALUES ('441601', '市轄區', '441600', '1');
INSERT INTO `ts_area` VALUES ('441602', '源城區', '441600', '2');
INSERT INTO `ts_area` VALUES ('441621', '紫金縣', '441600', '3');
INSERT INTO `ts_area` VALUES ('441622', '龍川縣', '441600', '4');
INSERT INTO `ts_area` VALUES ('441623', '連平縣', '441600', '5');
INSERT INTO `ts_area` VALUES ('441624', '和平縣', '441600', '6');
INSERT INTO `ts_area` VALUES ('441625', '東源縣', '441600', '7');
INSERT INTO `ts_area` VALUES ('441701', '市轄區', '441700', '1');
INSERT INTO `ts_area` VALUES ('441702', '江城區', '441700', '2');
INSERT INTO `ts_area` VALUES ('441721', '陽西縣', '441700', '3');
INSERT INTO `ts_area` VALUES ('441723', '陽東縣', '441700', '4');
INSERT INTO `ts_area` VALUES ('441781', '陽春市', '441700', '5');
INSERT INTO `ts_area` VALUES ('441801', '市轄區', '441800', '1');
INSERT INTO `ts_area` VALUES ('441802', '清城區', '441800', '2');
INSERT INTO `ts_area` VALUES ('441821', '佛岡縣', '441800', '3');
INSERT INTO `ts_area` VALUES ('441823', '陽山縣', '441800', '4');
INSERT INTO `ts_area` VALUES ('441825', '連山壯族瑤族自治縣', '441800', '5');
INSERT INTO `ts_area` VALUES ('441826', '連南瑤族自治縣', '441800', '6');
INSERT INTO `ts_area` VALUES ('441827', '清新縣', '441800', '7');
INSERT INTO `ts_area` VALUES ('441881', '英德市', '441800', '8');
INSERT INTO `ts_area` VALUES ('441882', '連州市', '441800', '9');
INSERT INTO `ts_area` VALUES ('445101', '市轄區', '445100', '1');
INSERT INTO `ts_area` VALUES ('445102', '湘橋區', '445100', '2');
INSERT INTO `ts_area` VALUES ('445121', '潮安縣', '445100', '3');
INSERT INTO `ts_area` VALUES ('445122', '饒平縣', '445100', '4');
INSERT INTO `ts_area` VALUES ('445201', '市轄區', '445200', '1');
INSERT INTO `ts_area` VALUES ('445202', '榕城區', '445200', '2');
INSERT INTO `ts_area` VALUES ('445221', '揭東縣', '445200', '3');
INSERT INTO `ts_area` VALUES ('445222', '揭西縣', '445200', '4');
INSERT INTO `ts_area` VALUES ('445224', '惠來縣', '445200', '5');
INSERT INTO `ts_area` VALUES ('445281', '普寧市', '445200', '6');
INSERT INTO `ts_area` VALUES ('445301', '市轄區', '445300', '1');
INSERT INTO `ts_area` VALUES ('445302', '云城區', '445300', '2');
INSERT INTO `ts_area` VALUES ('445321', '新興縣', '445300', '3');
INSERT INTO `ts_area` VALUES ('445322', '鬱南縣', '445300', '4');
INSERT INTO `ts_area` VALUES ('445323', '雲安縣', '445300', '5');
INSERT INTO `ts_area` VALUES ('445381', '羅定市', '445300', '6');
INSERT INTO `ts_area` VALUES ('450101', '市轄區', '450100', '1');
INSERT INTO `ts_area` VALUES ('450102', '興寧區', '450100', '2');
INSERT INTO `ts_area` VALUES ('450103', '青秀區', '450100', '3');
INSERT INTO `ts_area` VALUES ('450105', '江南區', '450100', '4');
INSERT INTO `ts_area` VALUES ('450107', '西鄉塘區', '450100', '5');
INSERT INTO `ts_area` VALUES ('450108', '良慶區', '450100', '6');
INSERT INTO `ts_area` VALUES ('450109', '邕寧區', '450100', '7');
INSERT INTO `ts_area` VALUES ('450122', '武鳴縣', '450100', '8');
INSERT INTO `ts_area` VALUES ('450123', '隆安縣', '450100', '9');
INSERT INTO `ts_area` VALUES ('450124', '馬山縣', '450100', '10');
INSERT INTO `ts_area` VALUES ('450125', '上林縣', '450100', '11');
INSERT INTO `ts_area` VALUES ('450126', '賓陽縣', '450100', '12');
INSERT INTO `ts_area` VALUES ('450127', '橫　縣', '450100', '13');
INSERT INTO `ts_area` VALUES ('450201', '市轄區', '450200', '1');
INSERT INTO `ts_area` VALUES ('450202', '城中區', '450200', '2');
INSERT INTO `ts_area` VALUES ('450203', '魚峰區', '450200', '3');
INSERT INTO `ts_area` VALUES ('450204', '柳南區', '450200', '4');
INSERT INTO `ts_area` VALUES ('450205', '柳北區', '450200', '5');
INSERT INTO `ts_area` VALUES ('450221', '柳江縣', '450200', '6');
INSERT INTO `ts_area` VALUES ('450222', '柳城縣', '450200', '7');
INSERT INTO `ts_area` VALUES ('450223', '鹿寨縣', '450200', '8');
INSERT INTO `ts_area` VALUES ('450224', '融安縣', '450200', '9');
INSERT INTO `ts_area` VALUES ('450225', '融水苗族自治縣', '450200', '10');
INSERT INTO `ts_area` VALUES ('450226', '三江侗族自治縣', '450200', '11');
INSERT INTO `ts_area` VALUES ('450301', '市轄區', '450300', '1');
INSERT INTO `ts_area` VALUES ('450302', '秀峰區', '450300', '2');
INSERT INTO `ts_area` VALUES ('450303', '疊彩區', '450300', '3');
INSERT INTO `ts_area` VALUES ('450304', '象山區', '450300', '4');
INSERT INTO `ts_area` VALUES ('450305', '七星區', '450300', '5');
INSERT INTO `ts_area` VALUES ('450311', '雁山區', '450300', '6');
INSERT INTO `ts_area` VALUES ('450321', '陽朔縣', '450300', '7');
INSERT INTO `ts_area` VALUES ('450322', '臨桂縣', '450300', '8');
INSERT INTO `ts_area` VALUES ('450323', '靈川縣', '450300', '9');
INSERT INTO `ts_area` VALUES ('450324', '全州縣', '450300', '10');
INSERT INTO `ts_area` VALUES ('450325', '興安縣', '450300', '11');
INSERT INTO `ts_area` VALUES ('450326', '永福縣', '450300', '12');
INSERT INTO `ts_area` VALUES ('450327', '灌陽縣', '450300', '13');
INSERT INTO `ts_area` VALUES ('450328', '龍勝各族自治縣', '450300', '14');
INSERT INTO `ts_area` VALUES ('450329', '資源縣', '450300', '15');
INSERT INTO `ts_area` VALUES ('450330', '平樂縣', '450300', '16');
INSERT INTO `ts_area` VALUES ('450331', '荔蒲縣', '450300', '17');
INSERT INTO `ts_area` VALUES ('450332', '恭城瑤族自治縣', '450300', '18');
INSERT INTO `ts_area` VALUES ('450401', '市轄區', '450400', '1');
INSERT INTO `ts_area` VALUES ('450403', '萬秀區', '450400', '2');
INSERT INTO `ts_area` VALUES ('450404', '蝶山區', '450400', '3');
INSERT INTO `ts_area` VALUES ('450405', '長洲區', '450400', '4');
INSERT INTO `ts_area` VALUES ('450421', '蒼梧縣', '450400', '5');
INSERT INTO `ts_area` VALUES ('450422', '藤　縣', '450400', '6');
INSERT INTO `ts_area` VALUES ('450423', '蒙山縣', '450400', '7');
INSERT INTO `ts_area` VALUES ('450481', '岑溪市', '450400', '8');
INSERT INTO `ts_area` VALUES ('450501', '市轄區', '450500', '1');
INSERT INTO `ts_area` VALUES ('450502', '海城區', '450500', '2');
INSERT INTO `ts_area` VALUES ('450503', '銀海區', '450500', '3');
INSERT INTO `ts_area` VALUES ('450512', '鐵山港區', '450500', '4');
INSERT INTO `ts_area` VALUES ('450521', '合浦縣', '450500', '5');
INSERT INTO `ts_area` VALUES ('450601', '市轄區', '450600', '1');
INSERT INTO `ts_area` VALUES ('450602', '港口區', '450600', '2');
INSERT INTO `ts_area` VALUES ('450603', '防城區', '450600', '3');
INSERT INTO `ts_area` VALUES ('450621', '上思縣', '450600', '4');
INSERT INTO `ts_area` VALUES ('450681', '東興市', '450600', '5');
INSERT INTO `ts_area` VALUES ('450701', '市轄區', '450700', '1');
INSERT INTO `ts_area` VALUES ('450702', '欽南區', '450700', '2');
INSERT INTO `ts_area` VALUES ('450703', '欽北區', '450700', '3');
INSERT INTO `ts_area` VALUES ('450721', '靈山縣', '450700', '4');
INSERT INTO `ts_area` VALUES ('450722', '浦北縣', '450700', '5');
INSERT INTO `ts_area` VALUES ('450801', '市轄區', '450800', '1');
INSERT INTO `ts_area` VALUES ('450802', '港北區', '450800', '2');
INSERT INTO `ts_area` VALUES ('450803', '港南區', '450800', '3');
INSERT INTO `ts_area` VALUES ('450804', '覃塘區', '450800', '4');
INSERT INTO `ts_area` VALUES ('450821', '平南縣', '450800', '5');
INSERT INTO `ts_area` VALUES ('450881', '桂平市', '450800', '6');
INSERT INTO `ts_area` VALUES ('450901', '市轄區', '450900', '1');
INSERT INTO `ts_area` VALUES ('450902', '玉州區', '450900', '2');
INSERT INTO `ts_area` VALUES ('450921', '容　縣', '450900', '3');
INSERT INTO `ts_area` VALUES ('450922', '陸川縣', '450900', '4');
INSERT INTO `ts_area` VALUES ('450923', '博白縣', '450900', '5');
INSERT INTO `ts_area` VALUES ('450924', '興業縣', '450900', '6');
INSERT INTO `ts_area` VALUES ('450981', '北流市', '450900', '7');
INSERT INTO `ts_area` VALUES ('451001', '市轄區', '451000', '1');
INSERT INTO `ts_area` VALUES ('451002', '右江區', '451000', '2');
INSERT INTO `ts_area` VALUES ('451021', '田陽縣', '451000', '3');
INSERT INTO `ts_area` VALUES ('451022', '田東縣', '451000', '4');
INSERT INTO `ts_area` VALUES ('451023', '平果縣', '451000', '5');
INSERT INTO `ts_area` VALUES ('451024', '德保縣', '451000', '6');
INSERT INTO `ts_area` VALUES ('451025', '靖西縣', '451000', '7');
INSERT INTO `ts_area` VALUES ('451026', '那坡縣', '451000', '8');
INSERT INTO `ts_area` VALUES ('451027', '凌雲縣', '451000', '9');
INSERT INTO `ts_area` VALUES ('451028', '樂業縣', '451000', '10');
INSERT INTO `ts_area` VALUES ('451029', '田林縣', '451000', '11');
INSERT INTO `ts_area` VALUES ('451030', '西林縣', '451000', '12');
INSERT INTO `ts_area` VALUES ('451031', '隆林各族自治縣', '451000', '13');
INSERT INTO `ts_area` VALUES ('451101', '市轄區', '451100', '1');
INSERT INTO `ts_area` VALUES ('451102', '八步區', '451100', '2');
INSERT INTO `ts_area` VALUES ('451121', '昭平縣', '451100', '3');
INSERT INTO `ts_area` VALUES ('451122', '鐘山縣', '451100', '4');
INSERT INTO `ts_area` VALUES ('451123', '富川瑤族自治縣', '451100', '5');
INSERT INTO `ts_area` VALUES ('451201', '市轄區', '451200', '1');
INSERT INTO `ts_area` VALUES ('451202', '金城江區', '451200', '2');
INSERT INTO `ts_area` VALUES ('451221', '南丹縣', '451200', '3');
INSERT INTO `ts_area` VALUES ('451222', '天峨縣', '451200', '4');
INSERT INTO `ts_area` VALUES ('451223', '鳳山縣', '451200', '5');
INSERT INTO `ts_area` VALUES ('451224', '東蘭縣', '451200', '6');
INSERT INTO `ts_area` VALUES ('451225', '羅城仫佬族自治縣', '451200', '7');
INSERT INTO `ts_area` VALUES ('451226', '環江毛南族自治縣', '451200', '8');
INSERT INTO `ts_area` VALUES ('451227', '巴馬瑤族自治縣', '451200', '9');
INSERT INTO `ts_area` VALUES ('451228', '都安瑤族自治縣', '451200', '10');
INSERT INTO `ts_area` VALUES ('451229', '大化瑤族自治縣', '451200', '11');
INSERT INTO `ts_area` VALUES ('451281', '宜州市', '451200', '12');
INSERT INTO `ts_area` VALUES ('451301', '市轄區', '451300', '1');
INSERT INTO `ts_area` VALUES ('451302', '興賓區', '451300', '2');
INSERT INTO `ts_area` VALUES ('451321', '忻城縣', '451300', '3');
INSERT INTO `ts_area` VALUES ('451322', '象州縣', '451300', '4');
INSERT INTO `ts_area` VALUES ('451323', '武宣縣', '451300', '5');
INSERT INTO `ts_area` VALUES ('451324', '金秀瑤族自治縣', '451300', '6');
INSERT INTO `ts_area` VALUES ('451381', '合山市', '451300', '7');
INSERT INTO `ts_area` VALUES ('451401', '市轄區', '451400', '1');
INSERT INTO `ts_area` VALUES ('451402', '江洲區', '451400', '2');
INSERT INTO `ts_area` VALUES ('451421', '扶綏縣', '451400', '3');
INSERT INTO `ts_area` VALUES ('451422', '寧明縣', '451400', '4');
INSERT INTO `ts_area` VALUES ('451423', '龍州縣', '451400', '5');
INSERT INTO `ts_area` VALUES ('451424', '大新縣', '451400', '6');
INSERT INTO `ts_area` VALUES ('451425', '天等縣', '451400', '7');
INSERT INTO `ts_area` VALUES ('451481', '憑祥市', '451400', '8');
INSERT INTO `ts_area` VALUES ('460101', '市轄區', '460100', '1');
INSERT INTO `ts_area` VALUES ('460105', '秀英區', '460100', '2');
INSERT INTO `ts_area` VALUES ('460106', '龍華區', '460100', '3');
INSERT INTO `ts_area` VALUES ('460107', '瓊山區', '460100', '4');
INSERT INTO `ts_area` VALUES ('460108', '美蘭區', '460100', '5');
INSERT INTO `ts_area` VALUES ('460201', '市轄區', '460200', '1');
INSERT INTO `ts_area` VALUES ('469001', '五指山市', '469000', '1');
INSERT INTO `ts_area` VALUES ('469002', '瓊海市', '469000', '2');
INSERT INTO `ts_area` VALUES ('469003', '儋州市', '469000', '3');
INSERT INTO `ts_area` VALUES ('469005', '文昌市', '469000', '4');
INSERT INTO `ts_area` VALUES ('469006', '萬寧市', '469000', '5');
INSERT INTO `ts_area` VALUES ('469007', '東方市', '469000', '6');
INSERT INTO `ts_area` VALUES ('469025', '定安縣', '469000', '7');
INSERT INTO `ts_area` VALUES ('469026', '屯昌縣', '469000', '8');
INSERT INTO `ts_area` VALUES ('469027', '澄邁縣', '469000', '9');
INSERT INTO `ts_area` VALUES ('469028', '臨高縣', '469000', '10');
INSERT INTO `ts_area` VALUES ('469030', '白沙黎族自治縣', '469000', '11');
INSERT INTO `ts_area` VALUES ('469031', '昌江黎族自治縣', '469000', '12');
INSERT INTO `ts_area` VALUES ('469033', '樂東黎族自治縣', '469000', '13');
INSERT INTO `ts_area` VALUES ('469034', '陵水黎族自治縣', '469000', '14');
INSERT INTO `ts_area` VALUES ('469035', '保亭黎族苗族自治縣', '469000', '15');
INSERT INTO `ts_area` VALUES ('469036', '瓊中黎族苗族自治縣', '469000', '16');
INSERT INTO `ts_area` VALUES ('469037', '西沙群島', '469000', '17');
INSERT INTO `ts_area` VALUES ('469038', '南沙群島', '469000', '18');
INSERT INTO `ts_area` VALUES ('469039', '中沙群島的島礁及其海域', '469000', '19');
INSERT INTO `ts_area` VALUES ('500101', '萬州區', '500100', '1');
INSERT INTO `ts_area` VALUES ('500102', '涪陵區', '500100', '2');
INSERT INTO `ts_area` VALUES ('500103', '渝中區', '500100', '3');
INSERT INTO `ts_area` VALUES ('500104', '大渡口區', '500100', '4');
INSERT INTO `ts_area` VALUES ('500105', '江北區', '500100', '5');
INSERT INTO `ts_area` VALUES ('500106', '沙坪壩區', '500100', '6');
INSERT INTO `ts_area` VALUES ('500107', '九龍坡區', '500100', '7');
INSERT INTO `ts_area` VALUES ('500108', '南岸區', '500100', '8');
INSERT INTO `ts_area` VALUES ('500109', '北碚區', '500100', '9');
INSERT INTO `ts_area` VALUES ('500110', '萬盛區', '500100', '10');
INSERT INTO `ts_area` VALUES ('500111', '雙橋區', '500100', '11');
INSERT INTO `ts_area` VALUES ('500112', '渝北區', '500100', '12');
INSERT INTO `ts_area` VALUES ('500113', '巴南區', '500100', '13');
INSERT INTO `ts_area` VALUES ('500114', '黔江區', '500100', '14');
INSERT INTO `ts_area` VALUES ('500115', '長壽區', '500100', '15');
INSERT INTO `ts_area` VALUES ('500222', '綦江縣', '500200', '1');
INSERT INTO `ts_area` VALUES ('500223', '潼南縣', '500200', '2');
INSERT INTO `ts_area` VALUES ('500224', '銅梁縣', '500200', '3');
INSERT INTO `ts_area` VALUES ('500225', '大足縣', '500200', '4');
INSERT INTO `ts_area` VALUES ('500226', '榮昌縣', '500200', '5');
INSERT INTO `ts_area` VALUES ('500227', '璧山縣', '500200', '6');
INSERT INTO `ts_area` VALUES ('500228', '梁平縣', '500200', '7');
INSERT INTO `ts_area` VALUES ('500229', '城口縣', '500200', '8');
INSERT INTO `ts_area` VALUES ('500230', '豐都縣', '500200', '9');
INSERT INTO `ts_area` VALUES ('500231', '墊江縣', '500200', '10');
INSERT INTO `ts_area` VALUES ('500232', '武隆縣', '500200', '11');
INSERT INTO `ts_area` VALUES ('500233', '忠　縣', '500200', '12');
INSERT INTO `ts_area` VALUES ('500234', '開　縣', '500200', '13');
INSERT INTO `ts_area` VALUES ('500235', '雲陽縣', '500200', '14');
INSERT INTO `ts_area` VALUES ('500236', '奉節縣', '500200', '15');
INSERT INTO `ts_area` VALUES ('500237', '巫山縣', '500200', '16');
INSERT INTO `ts_area` VALUES ('500238', '巫溪縣', '500200', '17');
INSERT INTO `ts_area` VALUES ('500240', '石柱土家族自治縣', '500200', '18');
INSERT INTO `ts_area` VALUES ('500241', '秀山土家族苗族自治縣', '500200', '19');
INSERT INTO `ts_area` VALUES ('500242', '酉陽土家族苗族自治縣', '500200', '20');
INSERT INTO `ts_area` VALUES ('500243', '彭水苗族土家族自治縣', '500200', '21');
INSERT INTO `ts_area` VALUES ('500381', '江津市', '500300', '1');
INSERT INTO `ts_area` VALUES ('500382', '合川市', '500300', '2');
INSERT INTO `ts_area` VALUES ('500383', '永川市', '500300', '3');
INSERT INTO `ts_area` VALUES ('500384', '南川市', '500300', '4');
INSERT INTO `ts_area` VALUES ('510101', '市轄區', '510100', '1');
INSERT INTO `ts_area` VALUES ('510104', '錦江區', '510100', '2');
INSERT INTO `ts_area` VALUES ('510105', '青羊區', '510100', '3');
INSERT INTO `ts_area` VALUES ('510106', '金牛區', '510100', '4');
INSERT INTO `ts_area` VALUES ('510107', '武侯區', '510100', '5');
INSERT INTO `ts_area` VALUES ('510108', '成華區', '510100', '6');
INSERT INTO `ts_area` VALUES ('510112', '龍泉驛區', '510100', '7');
INSERT INTO `ts_area` VALUES ('510113', '青白江區', '510100', '8');
INSERT INTO `ts_area` VALUES ('510114', '新都區', '510100', '9');
INSERT INTO `ts_area` VALUES ('510115', '溫江區', '510100', '10');
INSERT INTO `ts_area` VALUES ('510121', '金堂縣', '510100', '11');
INSERT INTO `ts_area` VALUES ('510122', '雙流縣', '510100', '12');
INSERT INTO `ts_area` VALUES ('510124', '郫　縣', '510100', '13');
INSERT INTO `ts_area` VALUES ('510129', '大邑縣', '510100', '14');
INSERT INTO `ts_area` VALUES ('510131', '蒲江縣', '510100', '15');
INSERT INTO `ts_area` VALUES ('510132', '新津縣', '510100', '16');
INSERT INTO `ts_area` VALUES ('510181', '都江堰市', '510100', '17');
INSERT INTO `ts_area` VALUES ('510182', '彭州市', '510100', '18');
INSERT INTO `ts_area` VALUES ('510183', '邛崍市', '510100', '19');
INSERT INTO `ts_area` VALUES ('510184', '崇州市', '510100', '20');
INSERT INTO `ts_area` VALUES ('510301', '市轄區', '510300', '1');
INSERT INTO `ts_area` VALUES ('510302', '自流井區', '510300', '2');
INSERT INTO `ts_area` VALUES ('510303', '貢井區', '510300', '3');
INSERT INTO `ts_area` VALUES ('510304', '大安區', '510300', '4');
INSERT INTO `ts_area` VALUES ('510311', '沿灘區', '510300', '5');
INSERT INTO `ts_area` VALUES ('510321', '榮　縣', '510300', '6');
INSERT INTO `ts_area` VALUES ('510322', '富順縣', '510300', '7');
INSERT INTO `ts_area` VALUES ('510401', '市轄區', '510400', '1');
INSERT INTO `ts_area` VALUES ('510402', '東　區', '510400', '2');
INSERT INTO `ts_area` VALUES ('510403', '西　區', '510400', '3');
INSERT INTO `ts_area` VALUES ('510411', '仁和區', '510400', '4');
INSERT INTO `ts_area` VALUES ('510421', '米易縣', '510400', '5');
INSERT INTO `ts_area` VALUES ('510422', '鹽邊縣', '510400', '6');
INSERT INTO `ts_area` VALUES ('510501', '市轄區', '510500', '1');
INSERT INTO `ts_area` VALUES ('510502', '江陽區', '510500', '2');
INSERT INTO `ts_area` VALUES ('510503', '納溪區', '510500', '3');
INSERT INTO `ts_area` VALUES ('510504', '龍馬潭區', '510500', '4');
INSERT INTO `ts_area` VALUES ('510521', '瀘　縣', '510500', '5');
INSERT INTO `ts_area` VALUES ('510522', '合江縣', '510500', '6');
INSERT INTO `ts_area` VALUES ('510524', '敘永縣', '510500', '7');
INSERT INTO `ts_area` VALUES ('510525', '古藺縣', '510500', '8');
INSERT INTO `ts_area` VALUES ('510601', '市轄區', '510600', '1');
INSERT INTO `ts_area` VALUES ('510603', '旌陽區', '510600', '2');
INSERT INTO `ts_area` VALUES ('510623', '中江縣', '510600', '3');
INSERT INTO `ts_area` VALUES ('510626', '羅江縣', '510600', '4');
INSERT INTO `ts_area` VALUES ('510681', '廣漢市', '510600', '5');
INSERT INTO `ts_area` VALUES ('510682', '什邡市', '510600', '6');
INSERT INTO `ts_area` VALUES ('510683', '綿竹市', '510600', '7');
INSERT INTO `ts_area` VALUES ('510701', '市轄區', '510700', '1');
INSERT INTO `ts_area` VALUES ('510703', '涪城區', '510700', '2');
INSERT INTO `ts_area` VALUES ('510704', '遊仙區', '510700', '3');
INSERT INTO `ts_area` VALUES ('510722', '三臺縣', '510700', '4');
INSERT INTO `ts_area` VALUES ('510723', '鹽亭縣', '510700', '5');
INSERT INTO `ts_area` VALUES ('510724', '安　縣', '510700', '6');
INSERT INTO `ts_area` VALUES ('510725', '梓潼縣', '510700', '7');
INSERT INTO `ts_area` VALUES ('510726', '北川羌族自治縣', '510700', '8');
INSERT INTO `ts_area` VALUES ('510727', '平武縣', '510700', '9');
INSERT INTO `ts_area` VALUES ('510781', '江油市', '510700', '10');
INSERT INTO `ts_area` VALUES ('510801', '市轄區', '510800', '1');
INSERT INTO `ts_area` VALUES ('510802', '市中區', '510800', '2');
INSERT INTO `ts_area` VALUES ('510811', '元壩區', '510800', '3');
INSERT INTO `ts_area` VALUES ('510812', '朝天區', '510800', '4');
INSERT INTO `ts_area` VALUES ('510821', '旺蒼縣', '510800', '5');
INSERT INTO `ts_area` VALUES ('510822', '青川縣', '510800', '6');
INSERT INTO `ts_area` VALUES ('510823', '劍閣縣', '510800', '7');
INSERT INTO `ts_area` VALUES ('510824', '蒼溪縣', '510800', '8');
INSERT INTO `ts_area` VALUES ('510901', '市轄區', '510900', '1');
INSERT INTO `ts_area` VALUES ('510903', '船山區', '510900', '2');
INSERT INTO `ts_area` VALUES ('510904', '安居區', '510900', '3');
INSERT INTO `ts_area` VALUES ('510921', '蓬溪縣', '510900', '4');
INSERT INTO `ts_area` VALUES ('510922', '射洪縣', '510900', '5');
INSERT INTO `ts_area` VALUES ('510923', '大英縣', '510900', '6');
INSERT INTO `ts_area` VALUES ('511001', '市轄區', '511000', '1');
INSERT INTO `ts_area` VALUES ('511002', '市中區', '511000', '2');
INSERT INTO `ts_area` VALUES ('511011', '東興區', '511000', '3');
INSERT INTO `ts_area` VALUES ('511024', '威遠縣', '511000', '4');
INSERT INTO `ts_area` VALUES ('511025', '資中縣', '511000', '5');
INSERT INTO `ts_area` VALUES ('511028', '隆昌縣', '511000', '6');
INSERT INTO `ts_area` VALUES ('511101', '市轄區', '511100', '1');
INSERT INTO `ts_area` VALUES ('511102', '市中區', '511100', '2');
INSERT INTO `ts_area` VALUES ('511111', '沙灣區', '511100', '3');
INSERT INTO `ts_area` VALUES ('511112', '五通橋區', '511100', '4');
INSERT INTO `ts_area` VALUES ('511113', '金口河區', '511100', '5');
INSERT INTO `ts_area` VALUES ('511123', '犍為縣', '511100', '6');
INSERT INTO `ts_area` VALUES ('511124', '井研縣', '511100', '7');
INSERT INTO `ts_area` VALUES ('511126', '夾江縣', '511100', '8');
INSERT INTO `ts_area` VALUES ('511129', '沐川縣', '511100', '9');
INSERT INTO `ts_area` VALUES ('511132', '峨邊彝族自治縣', '511100', '10');
INSERT INTO `ts_area` VALUES ('511133', '馬邊彝族自治縣', '511100', '11');
INSERT INTO `ts_area` VALUES ('511181', '峨眉山市', '511100', '12');
INSERT INTO `ts_area` VALUES ('511301', '市轄區', '511300', '1');
INSERT INTO `ts_area` VALUES ('511302', '順慶區', '511300', '2');
INSERT INTO `ts_area` VALUES ('511303', '高坪區', '511300', '3');
INSERT INTO `ts_area` VALUES ('511304', '嘉陵區', '511300', '4');
INSERT INTO `ts_area` VALUES ('511321', '南部縣', '511300', '5');
INSERT INTO `ts_area` VALUES ('511322', '營山縣', '511300', '6');
INSERT INTO `ts_area` VALUES ('511323', '蓬安縣', '511300', '7');
INSERT INTO `ts_area` VALUES ('511324', '儀隴縣', '511300', '8');
INSERT INTO `ts_area` VALUES ('511325', '西充縣', '511300', '9');
INSERT INTO `ts_area` VALUES ('511381', '閬中市', '511300', '10');
INSERT INTO `ts_area` VALUES ('511401', '市轄區', '511400', '1');
INSERT INTO `ts_area` VALUES ('511402', '東坡區', '511400', '2');
INSERT INTO `ts_area` VALUES ('511421', '仁壽縣', '511400', '3');
INSERT INTO `ts_area` VALUES ('511422', '彭山縣', '511400', '4');
INSERT INTO `ts_area` VALUES ('511423', '洪雅縣', '511400', '5');
INSERT INTO `ts_area` VALUES ('511424', '丹稜縣', '511400', '6');
INSERT INTO `ts_area` VALUES ('511425', '青神縣', '511400', '7');
INSERT INTO `ts_area` VALUES ('511501', '市轄區', '511500', '1');
INSERT INTO `ts_area` VALUES ('511502', '翠屏區', '511500', '2');
INSERT INTO `ts_area` VALUES ('511521', '宜賓縣', '511500', '3');
INSERT INTO `ts_area` VALUES ('511522', '南溪縣', '511500', '4');
INSERT INTO `ts_area` VALUES ('511523', '江安縣', '511500', '5');
INSERT INTO `ts_area` VALUES ('511524', '長寧縣', '511500', '6');
INSERT INTO `ts_area` VALUES ('511525', '高　縣', '511500', '7');
INSERT INTO `ts_area` VALUES ('511526', '珙　縣', '511500', '8');
INSERT INTO `ts_area` VALUES ('511527', '筠連縣', '511500', '9');
INSERT INTO `ts_area` VALUES ('511528', '興文縣', '511500', '10');
INSERT INTO `ts_area` VALUES ('511529', '屏山縣', '511500', '11');
INSERT INTO `ts_area` VALUES ('511601', '市轄區', '511600', '1');
INSERT INTO `ts_area` VALUES ('511602', '廣安區', '511600', '2');
INSERT INTO `ts_area` VALUES ('511621', '嶽池縣', '511600', '3');
INSERT INTO `ts_area` VALUES ('511622', '武勝縣', '511600', '4');
INSERT INTO `ts_area` VALUES ('511623', '鄰水縣', '511600', '5');
INSERT INTO `ts_area` VALUES ('511681', '華瑩市', '511600', '6');
INSERT INTO `ts_area` VALUES ('511701', '市轄區', '511700', '1');
INSERT INTO `ts_area` VALUES ('511702', '通川區', '511700', '2');
INSERT INTO `ts_area` VALUES ('511721', '達　縣', '511700', '3');
INSERT INTO `ts_area` VALUES ('511722', '宣漢縣', '511700', '4');
INSERT INTO `ts_area` VALUES ('511723', '開江縣', '511700', '5');
INSERT INTO `ts_area` VALUES ('511724', '大竹縣', '511700', '6');
INSERT INTO `ts_area` VALUES ('511725', '渠　縣', '511700', '7');
INSERT INTO `ts_area` VALUES ('511781', '萬源市', '511700', '8');
INSERT INTO `ts_area` VALUES ('511801', '市轄區', '511800', '1');
INSERT INTO `ts_area` VALUES ('511802', '雨城區', '511800', '2');
INSERT INTO `ts_area` VALUES ('511821', '名山縣', '511800', '3');
INSERT INTO `ts_area` VALUES ('511822', '滎經縣', '511800', '4');
INSERT INTO `ts_area` VALUES ('511823', '漢源縣', '511800', '5');
INSERT INTO `ts_area` VALUES ('511824', '石棉縣', '511800', '6');
INSERT INTO `ts_area` VALUES ('511825', '天全縣', '511800', '7');
INSERT INTO `ts_area` VALUES ('511826', '蘆山縣', '511800', '8');
INSERT INTO `ts_area` VALUES ('511827', '寶興縣', '511800', '9');
INSERT INTO `ts_area` VALUES ('511901', '市轄區', '511900', '1');
INSERT INTO `ts_area` VALUES ('511902', '巴州區', '511900', '2');
INSERT INTO `ts_area` VALUES ('511921', '通江縣', '511900', '3');
INSERT INTO `ts_area` VALUES ('511922', '南江縣', '511900', '4');
INSERT INTO `ts_area` VALUES ('511923', '平昌縣', '511900', '5');
INSERT INTO `ts_area` VALUES ('512001', '市轄區', '512000', '1');
INSERT INTO `ts_area` VALUES ('512002', '雁江區', '512000', '2');
INSERT INTO `ts_area` VALUES ('512021', '安嶽縣', '512000', '3');
INSERT INTO `ts_area` VALUES ('512022', '樂至縣', '512000', '4');
INSERT INTO `ts_area` VALUES ('512081', '簡陽市', '512000', '5');
INSERT INTO `ts_area` VALUES ('513221', '汶川縣', '513200', '1');
INSERT INTO `ts_area` VALUES ('513222', '理　縣', '513200', '2');
INSERT INTO `ts_area` VALUES ('513223', '茂　縣', '513200', '3');
INSERT INTO `ts_area` VALUES ('513224', '松潘縣', '513200', '4');
INSERT INTO `ts_area` VALUES ('513225', '九寨溝縣', '513200', '5');
INSERT INTO `ts_area` VALUES ('513226', '金川縣', '513200', '6');
INSERT INTO `ts_area` VALUES ('513227', '小金縣', '513200', '7');
INSERT INTO `ts_area` VALUES ('513228', '黑水縣', '513200', '8');
INSERT INTO `ts_area` VALUES ('513229', '馬爾康縣', '513200', '9');
INSERT INTO `ts_area` VALUES ('513230', '壤塘縣', '513200', '10');
INSERT INTO `ts_area` VALUES ('513231', '阿壩縣', '513200', '11');
INSERT INTO `ts_area` VALUES ('513232', '若爾蓋縣', '513200', '12');
INSERT INTO `ts_area` VALUES ('513233', '紅原縣', '513200', '13');
INSERT INTO `ts_area` VALUES ('513321', '康定縣', '513300', '1');
INSERT INTO `ts_area` VALUES ('513322', '瀘定縣', '513300', '2');
INSERT INTO `ts_area` VALUES ('513323', '丹巴縣', '513300', '3');
INSERT INTO `ts_area` VALUES ('513324', '九龍縣', '513300', '4');
INSERT INTO `ts_area` VALUES ('513325', '雅江縣', '513300', '5');
INSERT INTO `ts_area` VALUES ('513326', '道孚縣', '513300', '6');
INSERT INTO `ts_area` VALUES ('513327', '爐霍縣', '513300', '7');
INSERT INTO `ts_area` VALUES ('513328', '甘孜縣', '513300', '8');
INSERT INTO `ts_area` VALUES ('513329', '新龍縣', '513300', '9');
INSERT INTO `ts_area` VALUES ('513330', '德格縣', '513300', '10');
INSERT INTO `ts_area` VALUES ('513331', '白玉縣', '513300', '11');
INSERT INTO `ts_area` VALUES ('513332', '石渠縣', '513300', '12');
INSERT INTO `ts_area` VALUES ('513333', '色達縣', '513300', '13');
INSERT INTO `ts_area` VALUES ('513334', '理塘縣', '513300', '14');
INSERT INTO `ts_area` VALUES ('513335', '巴塘縣', '513300', '15');
INSERT INTO `ts_area` VALUES ('513336', '鄉城縣', '513300', '16');
INSERT INTO `ts_area` VALUES ('513337', '稻城縣', '513300', '17');
INSERT INTO `ts_area` VALUES ('513338', '得榮縣', '513300', '18');
INSERT INTO `ts_area` VALUES ('513401', '西昌市', '513400', '1');
INSERT INTO `ts_area` VALUES ('513422', '木裡藏族自治縣', '513400', '2');
INSERT INTO `ts_area` VALUES ('513423', '鹽源縣', '513400', '3');
INSERT INTO `ts_area` VALUES ('513424', '德昌縣', '513400', '4');
INSERT INTO `ts_area` VALUES ('513425', '會理縣', '513400', '5');
INSERT INTO `ts_area` VALUES ('513426', '會東縣', '513400', '6');
INSERT INTO `ts_area` VALUES ('513427', '寧南縣', '513400', '7');
INSERT INTO `ts_area` VALUES ('513428', '普格縣', '513400', '8');
INSERT INTO `ts_area` VALUES ('513429', '布拖縣', '513400', '9');
INSERT INTO `ts_area` VALUES ('513430', '金陽縣', '513400', '10');
INSERT INTO `ts_area` VALUES ('513431', '昭覺縣', '513400', '11');
INSERT INTO `ts_area` VALUES ('513432', '喜德縣', '513400', '12');
INSERT INTO `ts_area` VALUES ('513433', '冕寧縣', '513400', '13');
INSERT INTO `ts_area` VALUES ('513434', '越西縣', '513400', '14');
INSERT INTO `ts_area` VALUES ('513435', '甘洛縣', '513400', '15');
INSERT INTO `ts_area` VALUES ('513436', '美姑縣', '513400', '16');
INSERT INTO `ts_area` VALUES ('513437', '雷波縣', '513400', '17');
INSERT INTO `ts_area` VALUES ('520101', '市轄區', '520100', '1');
INSERT INTO `ts_area` VALUES ('520102', '南明區', '520100', '2');
INSERT INTO `ts_area` VALUES ('520103', '雲巖區', '520100', '3');
INSERT INTO `ts_area` VALUES ('520111', '花溪區', '520100', '4');
INSERT INTO `ts_area` VALUES ('520112', '烏當區', '520100', '5');
INSERT INTO `ts_area` VALUES ('520113', '白雲區', '520100', '6');
INSERT INTO `ts_area` VALUES ('520114', '小河區', '520100', '7');
INSERT INTO `ts_area` VALUES ('520121', '開陽縣', '520100', '8');
INSERT INTO `ts_area` VALUES ('520122', '息烽縣', '520100', '9');
INSERT INTO `ts_area` VALUES ('520123', '修文縣', '520100', '10');
INSERT INTO `ts_area` VALUES ('520181', '清鎮市', '520100', '11');
INSERT INTO `ts_area` VALUES ('520201', '鐘山區', '520200', '1');
INSERT INTO `ts_area` VALUES ('520203', '六枝特區', '520200', '2');
INSERT INTO `ts_area` VALUES ('520221', '水城縣', '520200', '3');
INSERT INTO `ts_area` VALUES ('520222', '盤　縣', '520200', '4');
INSERT INTO `ts_area` VALUES ('520301', '市轄區', '520300', '1');
INSERT INTO `ts_area` VALUES ('520302', '紅花崗區', '520300', '2');
INSERT INTO `ts_area` VALUES ('520303', '匯川區', '520300', '3');
INSERT INTO `ts_area` VALUES ('520321', '遵義縣', '520300', '4');
INSERT INTO `ts_area` VALUES ('520322', '桐梓縣', '520300', '5');
INSERT INTO `ts_area` VALUES ('520323', '綏陽縣', '520300', '6');
INSERT INTO `ts_area` VALUES ('520324', '正安縣', '520300', '7');
INSERT INTO `ts_area` VALUES ('520325', '道真仡佬族苗族自治縣', '520300', '8');
INSERT INTO `ts_area` VALUES ('520326', '務川仡佬族苗族自治縣', '520300', '9');
INSERT INTO `ts_area` VALUES ('520327', '鳳岡縣', '520300', '10');
INSERT INTO `ts_area` VALUES ('520328', '湄潭縣', '520300', '11');
INSERT INTO `ts_area` VALUES ('520329', '餘慶縣', '520300', '12');
INSERT INTO `ts_area` VALUES ('520330', '習水縣', '520300', '13');
INSERT INTO `ts_area` VALUES ('520381', '赤水市', '520300', '14');
INSERT INTO `ts_area` VALUES ('520382', '仁懷市', '520300', '15');
INSERT INTO `ts_area` VALUES ('520401', '市轄區', '520400', '1');
INSERT INTO `ts_area` VALUES ('520402', '西秀區', '520400', '2');
INSERT INTO `ts_area` VALUES ('520421', '平壩縣', '520400', '3');
INSERT INTO `ts_area` VALUES ('520422', '普定縣', '520400', '4');
INSERT INTO `ts_area` VALUES ('520423', '鎮寧布依族苗族自治縣', '520400', '5');
INSERT INTO `ts_area` VALUES ('520424', '關嶺布依族苗族自治縣', '520400', '6');
INSERT INTO `ts_area` VALUES ('520425', '紫云苗族布依族自治縣', '520400', '7');
INSERT INTO `ts_area` VALUES ('522201', '銅仁市', '522200', '1');
INSERT INTO `ts_area` VALUES ('522222', '江口縣', '522200', '2');
INSERT INTO `ts_area` VALUES ('522223', '玉屏侗族自治縣', '522200', '3');
INSERT INTO `ts_area` VALUES ('522224', '石阡縣', '522200', '4');
INSERT INTO `ts_area` VALUES ('522225', '思南縣', '522200', '5');
INSERT INTO `ts_area` VALUES ('522226', '印江土家族苗族自治縣', '522200', '6');
INSERT INTO `ts_area` VALUES ('522227', '德江縣', '522200', '7');
INSERT INTO `ts_area` VALUES ('522228', '沿河土家族自治縣', '522200', '8');
INSERT INTO `ts_area` VALUES ('522229', '松桃苗族自治縣', '522200', '9');
INSERT INTO `ts_area` VALUES ('522230', '萬山特區', '522200', '10');
INSERT INTO `ts_area` VALUES ('522301', '興義市', '522300', '1');
INSERT INTO `ts_area` VALUES ('522322', '興仁縣', '522300', '2');
INSERT INTO `ts_area` VALUES ('522323', '普安縣', '522300', '3');
INSERT INTO `ts_area` VALUES ('522324', '晴隆縣', '522300', '4');
INSERT INTO `ts_area` VALUES ('522325', '貞豐縣', '522300', '5');
INSERT INTO `ts_area` VALUES ('522326', '望謨縣', '522300', '6');
INSERT INTO `ts_area` VALUES ('522327', '冊亨縣', '522300', '7');
INSERT INTO `ts_area` VALUES ('522328', '安龍縣', '522300', '8');
INSERT INTO `ts_area` VALUES ('522401', '畢節市', '522400', '1');
INSERT INTO `ts_area` VALUES ('522422', '大方縣', '522400', '2');
INSERT INTO `ts_area` VALUES ('522423', '黔西縣', '522400', '3');
INSERT INTO `ts_area` VALUES ('522424', '金沙縣', '522400', '4');
INSERT INTO `ts_area` VALUES ('522425', '織金縣', '522400', '5');
INSERT INTO `ts_area` VALUES ('522426', '納雍縣', '522400', '6');
INSERT INTO `ts_area` VALUES ('522427', '威寧彝族回族苗族自治縣', '522400', '7');
INSERT INTO `ts_area` VALUES ('522428', '赫章縣', '522400', '8');
INSERT INTO `ts_area` VALUES ('522601', '凱裡市', '522600', '1');
INSERT INTO `ts_area` VALUES ('522622', '黃平縣', '522600', '2');
INSERT INTO `ts_area` VALUES ('522623', '施秉縣', '522600', '3');
INSERT INTO `ts_area` VALUES ('522624', '三穗縣', '522600', '4');
INSERT INTO `ts_area` VALUES ('522625', '鎮遠縣', '522600', '5');
INSERT INTO `ts_area` VALUES ('522626', '岑鞏縣', '522600', '6');
INSERT INTO `ts_area` VALUES ('522627', '天柱縣', '522600', '7');
INSERT INTO `ts_area` VALUES ('522628', '錦屏縣', '522600', '8');
INSERT INTO `ts_area` VALUES ('522629', '劍河縣', '522600', '9');
INSERT INTO `ts_area` VALUES ('522630', '臺江縣', '522600', '10');
INSERT INTO `ts_area` VALUES ('522631', '黎平縣', '522600', '11');
INSERT INTO `ts_area` VALUES ('522632', '榕江縣', '522600', '12');
INSERT INTO `ts_area` VALUES ('522633', '從江縣', '522600', '13');
INSERT INTO `ts_area` VALUES ('522634', '雷山縣', '522600', '14');
INSERT INTO `ts_area` VALUES ('522635', '麻江縣', '522600', '15');
INSERT INTO `ts_area` VALUES ('522636', '丹寨縣', '522600', '16');
INSERT INTO `ts_area` VALUES ('522701', '都勻市', '522700', '1');
INSERT INTO `ts_area` VALUES ('522702', '福泉市', '522700', '2');
INSERT INTO `ts_area` VALUES ('522722', '荔波縣', '522700', '3');
INSERT INTO `ts_area` VALUES ('522723', '貴定縣', '522700', '4');
INSERT INTO `ts_area` VALUES ('522725', '甕安縣', '522700', '5');
INSERT INTO `ts_area` VALUES ('522726', '獨山縣', '522700', '6');
INSERT INTO `ts_area` VALUES ('522727', '平塘縣', '522700', '7');
INSERT INTO `ts_area` VALUES ('522728', '羅甸縣', '522700', '8');
INSERT INTO `ts_area` VALUES ('522729', '長順縣', '522700', '9');
INSERT INTO `ts_area` VALUES ('522730', '龍裡縣', '522700', '10');
INSERT INTO `ts_area` VALUES ('522731', '惠水縣', '522700', '11');
INSERT INTO `ts_area` VALUES ('522732', '三都水族自治縣', '522700', '12');
INSERT INTO `ts_area` VALUES ('530101', '市轄區', '530100', '1');
INSERT INTO `ts_area` VALUES ('530102', '五華區', '530100', '2');
INSERT INTO `ts_area` VALUES ('530103', '盤龍區', '530100', '3');
INSERT INTO `ts_area` VALUES ('530111', '官渡區', '530100', '4');
INSERT INTO `ts_area` VALUES ('530112', '西山區', '530100', '5');
INSERT INTO `ts_area` VALUES ('530113', '東川區', '530100', '6');
INSERT INTO `ts_area` VALUES ('530121', '呈貢縣', '530100', '7');
INSERT INTO `ts_area` VALUES ('530122', '晉寧縣', '530100', '8');
INSERT INTO `ts_area` VALUES ('530124', '富民縣', '530100', '9');
INSERT INTO `ts_area` VALUES ('530125', '宜良縣', '530100', '10');
INSERT INTO `ts_area` VALUES ('530126', '石林彝族自治縣', '530100', '11');
INSERT INTO `ts_area` VALUES ('530127', '嵩明縣', '530100', '12');
INSERT INTO `ts_area` VALUES ('530128', '祿勸彝族苗族自治縣', '530100', '13');
INSERT INTO `ts_area` VALUES ('530129', '尋甸回族彝族自治縣', '530100', '14');
INSERT INTO `ts_area` VALUES ('530181', '安寧市', '530100', '15');
INSERT INTO `ts_area` VALUES ('530301', '市轄區', '530300', '1');
INSERT INTO `ts_area` VALUES ('530302', '麒麟區', '530300', '2');
INSERT INTO `ts_area` VALUES ('530321', '馬龍縣', '530300', '3');
INSERT INTO `ts_area` VALUES ('530322', '陸良縣', '530300', '4');
INSERT INTO `ts_area` VALUES ('530323', '師宗縣', '530300', '5');
INSERT INTO `ts_area` VALUES ('530324', '羅平縣', '530300', '6');
INSERT INTO `ts_area` VALUES ('530325', '富源縣', '530300', '7');
INSERT INTO `ts_area` VALUES ('530326', '會澤縣', '530300', '8');
INSERT INTO `ts_area` VALUES ('530328', '沾益縣', '530300', '9');
INSERT INTO `ts_area` VALUES ('530381', '宣威市', '530300', '10');
INSERT INTO `ts_area` VALUES ('530401', '市轄區', '530400', '1');
INSERT INTO `ts_area` VALUES ('530402', '紅塔區', '530400', '2');
INSERT INTO `ts_area` VALUES ('530421', '江川縣', '530400', '3');
INSERT INTO `ts_area` VALUES ('530422', '澄江縣', '530400', '4');
INSERT INTO `ts_area` VALUES ('530423', '通海縣', '530400', '5');
INSERT INTO `ts_area` VALUES ('530424', '華寧縣', '530400', '6');
INSERT INTO `ts_area` VALUES ('530425', '易門縣', '530400', '7');
INSERT INTO `ts_area` VALUES ('530426', '峨山彝族自治縣', '530400', '8');
INSERT INTO `ts_area` VALUES ('530427', '新平彝族傣族自治縣', '530400', '9');
INSERT INTO `ts_area` VALUES ('530428', '元江哈尼族彝族傣族自治縣', '530400', '10');
INSERT INTO `ts_area` VALUES ('530501', '市轄區', '530500', '1');
INSERT INTO `ts_area` VALUES ('530502', '隆陽區', '530500', '2');
INSERT INTO `ts_area` VALUES ('530521', '施甸縣', '530500', '3');
INSERT INTO `ts_area` VALUES ('530522', '騰衝縣', '530500', '4');
INSERT INTO `ts_area` VALUES ('530523', '龍陵縣', '530500', '5');
INSERT INTO `ts_area` VALUES ('530524', '昌寧縣', '530500', '6');
INSERT INTO `ts_area` VALUES ('530601', '市轄區', '530600', '1');
INSERT INTO `ts_area` VALUES ('530602', '昭陽區', '530600', '2');
INSERT INTO `ts_area` VALUES ('530621', '魯甸縣', '530600', '3');
INSERT INTO `ts_area` VALUES ('530622', '巧家縣', '530600', '4');
INSERT INTO `ts_area` VALUES ('530623', '鹽津縣', '530600', '5');
INSERT INTO `ts_area` VALUES ('530624', '大關縣', '530600', '6');
INSERT INTO `ts_area` VALUES ('530625', '永善縣', '530600', '7');
INSERT INTO `ts_area` VALUES ('530626', '綏江縣', '530600', '8');
INSERT INTO `ts_area` VALUES ('530627', '鎮雄縣', '530600', '9');
INSERT INTO `ts_area` VALUES ('530628', '彝良縣', '530600', '10');
INSERT INTO `ts_area` VALUES ('530629', '威信縣', '530600', '11');
INSERT INTO `ts_area` VALUES ('530630', '水富縣', '530600', '12');
INSERT INTO `ts_area` VALUES ('530701', '市轄區', '530700', '1');
INSERT INTO `ts_area` VALUES ('530702', '古城區', '530700', '2');
INSERT INTO `ts_area` VALUES ('530721', '玉龍納西族自治縣', '530700', '3');
INSERT INTO `ts_area` VALUES ('530722', '永勝縣', '530700', '4');
INSERT INTO `ts_area` VALUES ('530723', '華坪縣', '530700', '5');
INSERT INTO `ts_area` VALUES ('530724', '寧蒗彝族自治縣', '530700', '6');
INSERT INTO `ts_area` VALUES ('530801', '市轄區', '530800', '1');
INSERT INTO `ts_area` VALUES ('530802', '翠雲區', '530800', '2');
INSERT INTO `ts_area` VALUES ('530821', '普洱哈尼族彝族自治縣', '530800', '3');
INSERT INTO `ts_area` VALUES ('530822', '墨江哈尼族自治縣', '530800', '4');
INSERT INTO `ts_area` VALUES ('530823', '景東彝族自治縣', '530800', '5');
INSERT INTO `ts_area` VALUES ('530824', '景谷傣族彝族自治縣', '530800', '6');
INSERT INTO `ts_area` VALUES ('530825', '鎮沅彝族哈尼族拉祜族自治縣', '530800', '7');
INSERT INTO `ts_area` VALUES ('530826', '江城哈尼族彝族自治縣', '530800', '8');
INSERT INTO `ts_area` VALUES ('530827', '孟連傣族拉祜族佤族自治縣', '530800', '9');
INSERT INTO `ts_area` VALUES ('530828', '瀾滄拉祜族自治縣', '530800', '10');
INSERT INTO `ts_area` VALUES ('530829', '西盟佤族自治縣', '530800', '11');
INSERT INTO `ts_area` VALUES ('530901', '市轄區', '530900', '1');
INSERT INTO `ts_area` VALUES ('530902', '臨翔區', '530900', '2');
INSERT INTO `ts_area` VALUES ('530921', '鳳慶縣', '530900', '3');
INSERT INTO `ts_area` VALUES ('530922', '雲　縣', '530900', '4');
INSERT INTO `ts_area` VALUES ('530923', '永德縣', '530900', '5');
INSERT INTO `ts_area` VALUES ('530924', '鎮康縣', '530900', '6');
INSERT INTO `ts_area` VALUES ('530925', '雙江拉祜族佤族布朗族傣族自治縣', '530900', '7');
INSERT INTO `ts_area` VALUES ('530926', '耿馬傣族佤族自治縣', '530900', '8');
INSERT INTO `ts_area` VALUES ('530927', '滄源佤族自治縣', '530900', '9');
INSERT INTO `ts_area` VALUES ('532301', '楚雄市', '532300', '1');
INSERT INTO `ts_area` VALUES ('532322', '雙柏縣', '532300', '2');
INSERT INTO `ts_area` VALUES ('532323', '牟定縣', '532300', '3');
INSERT INTO `ts_area` VALUES ('532324', '南華縣', '532300', '4');
INSERT INTO `ts_area` VALUES ('532325', '姚安縣', '532300', '5');
INSERT INTO `ts_area` VALUES ('532326', '大姚縣', '532300', '6');
INSERT INTO `ts_area` VALUES ('532327', '永仁縣', '532300', '7');
INSERT INTO `ts_area` VALUES ('532328', '元謀縣', '532300', '8');
INSERT INTO `ts_area` VALUES ('532329', '武定縣', '532300', '9');
INSERT INTO `ts_area` VALUES ('532331', '祿豐縣', '532300', '10');
INSERT INTO `ts_area` VALUES ('532501', '箇舊市', '532500', '1');
INSERT INTO `ts_area` VALUES ('532502', '開遠市', '532500', '2');
INSERT INTO `ts_area` VALUES ('532522', '蒙自縣', '532500', '3');
INSERT INTO `ts_area` VALUES ('532523', '屏邊苗族自治縣', '532500', '4');
INSERT INTO `ts_area` VALUES ('532524', '建水縣', '532500', '5');
INSERT INTO `ts_area` VALUES ('532525', '石屏縣', '532500', '6');
INSERT INTO `ts_area` VALUES ('532526', '彌勒縣', '532500', '7');
INSERT INTO `ts_area` VALUES ('532527', '瀘西縣', '532500', '8');
INSERT INTO `ts_area` VALUES ('532528', '元陽縣', '532500', '9');
INSERT INTO `ts_area` VALUES ('532529', '紅河縣', '532500', '10');
INSERT INTO `ts_area` VALUES ('532530', '金平苗族瑤族傣族自治縣', '532500', '11');
INSERT INTO `ts_area` VALUES ('532531', '綠春縣', '532500', '12');
INSERT INTO `ts_area` VALUES ('532532', '河口瑤族自治縣', '532500', '13');
INSERT INTO `ts_area` VALUES ('532621', '文山縣', '532600', '1');
INSERT INTO `ts_area` VALUES ('532622', '硯山縣', '532600', '2');
INSERT INTO `ts_area` VALUES ('532623', '西疇縣', '532600', '3');
INSERT INTO `ts_area` VALUES ('532624', '麻栗坡縣', '532600', '4');
INSERT INTO `ts_area` VALUES ('532625', '馬關縣', '532600', '5');
INSERT INTO `ts_area` VALUES ('532626', '丘北縣', '532600', '6');
INSERT INTO `ts_area` VALUES ('532627', '廣南縣', '532600', '7');
INSERT INTO `ts_area` VALUES ('532628', '富寧縣', '532600', '8');
INSERT INTO `ts_area` VALUES ('532801', '景洪市', '532800', '1');
INSERT INTO `ts_area` VALUES ('532822', '勐海縣', '532800', '2');
INSERT INTO `ts_area` VALUES ('532823', '勐臘縣', '532800', '3');
INSERT INTO `ts_area` VALUES ('532901', '大理市', '532900', '1');
INSERT INTO `ts_area` VALUES ('532922', '漾濞彝族自治縣', '532900', '2');
INSERT INTO `ts_area` VALUES ('532923', '祥雲縣', '532900', '3');
INSERT INTO `ts_area` VALUES ('532924', '賓川縣', '532900', '4');
INSERT INTO `ts_area` VALUES ('532925', '彌渡縣', '532900', '5');
INSERT INTO `ts_area` VALUES ('532926', '南澗彝族自治縣', '532900', '6');
INSERT INTO `ts_area` VALUES ('532927', '巍山彝族回族自治縣', '532900', '7');
INSERT INTO `ts_area` VALUES ('532928', '永平縣', '532900', '8');
INSERT INTO `ts_area` VALUES ('532929', '雲龍縣', '532900', '9');
INSERT INTO `ts_area` VALUES ('532930', '洱源縣', '532900', '10');
INSERT INTO `ts_area` VALUES ('532931', '劍川縣', '532900', '11');
INSERT INTO `ts_area` VALUES ('532932', '鶴慶縣', '532900', '12');
INSERT INTO `ts_area` VALUES ('533102', '瑞麗市', '533100', '1');
INSERT INTO `ts_area` VALUES ('533103', '潞西市', '533100', '2');
INSERT INTO `ts_area` VALUES ('533122', '梁河縣', '533100', '3');
INSERT INTO `ts_area` VALUES ('533123', '盈江縣', '533100', '4');
INSERT INTO `ts_area` VALUES ('533124', '隴川縣', '533100', '5');
INSERT INTO `ts_area` VALUES ('533321', '瀘水縣', '533300', '1');
INSERT INTO `ts_area` VALUES ('533323', '福貢縣', '533300', '2');
INSERT INTO `ts_area` VALUES ('533324', '貢山獨龍族怒族自治縣', '533300', '3');
INSERT INTO `ts_area` VALUES ('533325', '蘭坪白族普米族自治縣', '533300', '4');
INSERT INTO `ts_area` VALUES ('533421', '香格裡拉縣', '533400', '1');
INSERT INTO `ts_area` VALUES ('533422', '德欽縣', '533400', '2');
INSERT INTO `ts_area` VALUES ('533423', '維西傈僳族自治縣', '533400', '3');
INSERT INTO `ts_area` VALUES ('540101', '市轄區', '540100', '1');
INSERT INTO `ts_area` VALUES ('540102', '城關區', '540100', '2');
INSERT INTO `ts_area` VALUES ('540121', '林周縣', '540100', '3');
INSERT INTO `ts_area` VALUES ('540122', '當雄縣', '540100', '4');
INSERT INTO `ts_area` VALUES ('540123', '尼木縣', '540100', '5');
INSERT INTO `ts_area` VALUES ('540124', '曲水縣', '540100', '6');
INSERT INTO `ts_area` VALUES ('540125', '堆龍德慶縣', '540100', '7');
INSERT INTO `ts_area` VALUES ('540126', '達孜縣', '540100', '8');
INSERT INTO `ts_area` VALUES ('540127', '墨竹工卡縣', '540100', '9');
INSERT INTO `ts_area` VALUES ('542121', '昌都縣', '542100', '1');
INSERT INTO `ts_area` VALUES ('542122', '江達縣', '542100', '2');
INSERT INTO `ts_area` VALUES ('542123', '貢覺縣', '542100', '3');
INSERT INTO `ts_area` VALUES ('542124', '類烏齊縣', '542100', '4');
INSERT INTO `ts_area` VALUES ('542125', '丁青縣', '542100', '5');
INSERT INTO `ts_area` VALUES ('542126', '察雅縣', '542100', '6');
INSERT INTO `ts_area` VALUES ('542127', '八宿縣', '542100', '7');
INSERT INTO `ts_area` VALUES ('542128', '左貢縣', '542100', '8');
INSERT INTO `ts_area` VALUES ('542129', '芒康縣', '542100', '9');
INSERT INTO `ts_area` VALUES ('542132', '洛隆縣', '542100', '10');
INSERT INTO `ts_area` VALUES ('542133', '邊壩縣', '542100', '11');
INSERT INTO `ts_area` VALUES ('542221', '乃東縣', '542200', '1');
INSERT INTO `ts_area` VALUES ('542222', '扎囊縣', '542200', '2');
INSERT INTO `ts_area` VALUES ('542223', '貢嘎縣', '542200', '3');
INSERT INTO `ts_area` VALUES ('542224', '桑日縣', '542200', '4');
INSERT INTO `ts_area` VALUES ('542225', '瓊結縣', '542200', '5');
INSERT INTO `ts_area` VALUES ('542226', '曲松縣', '542200', '6');
INSERT INTO `ts_area` VALUES ('542227', '措美縣', '542200', '7');
INSERT INTO `ts_area` VALUES ('542228', '洛扎縣', '542200', '8');
INSERT INTO `ts_area` VALUES ('542229', '加查縣', '542200', '9');
INSERT INTO `ts_area` VALUES ('542231', '隆子縣', '542200', '10');
INSERT INTO `ts_area` VALUES ('542232', '錯那縣', '542200', '11');
INSERT INTO `ts_area` VALUES ('542233', '浪卡子縣', '542200', '12');
INSERT INTO `ts_area` VALUES ('542301', '日喀則市', '542300', '1');
INSERT INTO `ts_area` VALUES ('542322', '南木林縣', '542300', '2');
INSERT INTO `ts_area` VALUES ('542323', '江孜縣', '542300', '3');
INSERT INTO `ts_area` VALUES ('542324', '定日縣', '542300', '4');
INSERT INTO `ts_area` VALUES ('542325', '薩迦縣', '542300', '5');
INSERT INTO `ts_area` VALUES ('542326', '拉孜縣', '542300', '6');
INSERT INTO `ts_area` VALUES ('542327', '昂仁縣', '542300', '7');
INSERT INTO `ts_area` VALUES ('542328', '謝通門縣', '542300', '8');
INSERT INTO `ts_area` VALUES ('542329', '白朗縣', '542300', '9');
INSERT INTO `ts_area` VALUES ('542330', '仁布縣', '542300', '10');
INSERT INTO `ts_area` VALUES ('542331', '康馬縣', '542300', '11');
INSERT INTO `ts_area` VALUES ('542332', '定結縣', '542300', '12');
INSERT INTO `ts_area` VALUES ('542333', '仲巴縣', '542300', '13');
INSERT INTO `ts_area` VALUES ('542334', '亞東縣', '542300', '14');
INSERT INTO `ts_area` VALUES ('542335', '吉隆縣', '542300', '15');
INSERT INTO `ts_area` VALUES ('542336', '聶拉木縣', '542300', '16');
INSERT INTO `ts_area` VALUES ('542337', '薩嘎縣', '542300', '17');
INSERT INTO `ts_area` VALUES ('542338', '崗巴縣', '542300', '18');
INSERT INTO `ts_area` VALUES ('542421', '那曲縣', '542400', '1');
INSERT INTO `ts_area` VALUES ('542422', '嘉黎縣', '542400', '2');
INSERT INTO `ts_area` VALUES ('542423', '比如縣', '542400', '3');
INSERT INTO `ts_area` VALUES ('542424', '聶榮縣', '542400', '4');
INSERT INTO `ts_area` VALUES ('542425', '安多縣', '542400', '5');
INSERT INTO `ts_area` VALUES ('542426', '申扎縣', '542400', '6');
INSERT INTO `ts_area` VALUES ('542427', '索　縣', '542400', '7');
INSERT INTO `ts_area` VALUES ('542428', '班戈縣', '542400', '8');
INSERT INTO `ts_area` VALUES ('542429', '巴青縣', '542400', '9');
INSERT INTO `ts_area` VALUES ('542430', '尼瑪縣', '542400', '10');
INSERT INTO `ts_area` VALUES ('542521', '普蘭縣', '542500', '1');
INSERT INTO `ts_area` VALUES ('542522', '札達縣', '542500', '2');
INSERT INTO `ts_area` VALUES ('542523', '噶爾縣', '542500', '3');
INSERT INTO `ts_area` VALUES ('542524', '日土縣', '542500', '4');
INSERT INTO `ts_area` VALUES ('542525', '革吉縣', '542500', '5');
INSERT INTO `ts_area` VALUES ('542526', '改則縣', '542500', '6');
INSERT INTO `ts_area` VALUES ('542527', '措勤縣', '542500', '7');
INSERT INTO `ts_area` VALUES ('542621', '林芝縣', '542600', '1');
INSERT INTO `ts_area` VALUES ('542622', '工布江達縣', '542600', '2');
INSERT INTO `ts_area` VALUES ('542623', '米林縣', '542600', '3');
INSERT INTO `ts_area` VALUES ('542624', '墨脫縣', '542600', '4');
INSERT INTO `ts_area` VALUES ('542625', '波密縣', '542600', '5');
INSERT INTO `ts_area` VALUES ('542626', '察隅縣', '542600', '6');
INSERT INTO `ts_area` VALUES ('542627', '朗　縣', '542600', '7');
INSERT INTO `ts_area` VALUES ('610101', '市轄區', '610100', '1');
INSERT INTO `ts_area` VALUES ('610102', '新城區', '610100', '2');
INSERT INTO `ts_area` VALUES ('610103', '碑林區', '610100', '3');
INSERT INTO `ts_area` VALUES ('610104', '蓮湖區', '610100', '4');
INSERT INTO `ts_area` VALUES ('610111', '灞橋區', '610100', '5');
INSERT INTO `ts_area` VALUES ('610112', '未央區', '610100', '6');
INSERT INTO `ts_area` VALUES ('610113', '雁塔區', '610100', '7');
INSERT INTO `ts_area` VALUES ('610114', '閻良區', '610100', '8');
INSERT INTO `ts_area` VALUES ('610115', '臨潼區', '610100', '9');
INSERT INTO `ts_area` VALUES ('610116', '長安區', '610100', '10');
INSERT INTO `ts_area` VALUES ('610122', '藍田縣', '610100', '11');
INSERT INTO `ts_area` VALUES ('610124', '周至縣', '610100', '12');
INSERT INTO `ts_area` VALUES ('610125', '戶　縣', '610100', '13');
INSERT INTO `ts_area` VALUES ('610126', '高陵縣', '610100', '14');
INSERT INTO `ts_area` VALUES ('610201', '市轄區', '610200', '1');
INSERT INTO `ts_area` VALUES ('610202', '王益區', '610200', '2');
INSERT INTO `ts_area` VALUES ('610203', '印臺區', '610200', '3');
INSERT INTO `ts_area` VALUES ('610204', '耀州區', '610200', '4');
INSERT INTO `ts_area` VALUES ('610222', '宜君縣', '610200', '5');
INSERT INTO `ts_area` VALUES ('610301', '市轄區', '610300', '1');
INSERT INTO `ts_area` VALUES ('610302', '渭濱區', '610300', '2');
INSERT INTO `ts_area` VALUES ('610303', '金臺區', '610300', '3');
INSERT INTO `ts_area` VALUES ('610304', '陳倉區', '610300', '4');
INSERT INTO `ts_area` VALUES ('610322', '鳳翔縣', '610300', '5');
INSERT INTO `ts_area` VALUES ('610323', '岐山縣', '610300', '6');
INSERT INTO `ts_area` VALUES ('610324', '扶風縣', '610300', '7');
INSERT INTO `ts_area` VALUES ('610326', '眉　縣', '610300', '8');
INSERT INTO `ts_area` VALUES ('610327', '隴　縣', '610300', '9');
INSERT INTO `ts_area` VALUES ('610328', '千陽縣', '610300', '10');
INSERT INTO `ts_area` VALUES ('610329', '麟遊縣', '610300', '11');
INSERT INTO `ts_area` VALUES ('610330', '鳳　縣', '610300', '12');
INSERT INTO `ts_area` VALUES ('610331', '太白縣', '610300', '13');
INSERT INTO `ts_area` VALUES ('610401', '市轄區', '610400', '1');
INSERT INTO `ts_area` VALUES ('610402', '秦都區', '610400', '2');
INSERT INTO `ts_area` VALUES ('610403', '楊凌區', '610400', '3');
INSERT INTO `ts_area` VALUES ('610404', '渭城區', '610400', '4');
INSERT INTO `ts_area` VALUES ('610422', '三原縣', '610400', '5');
INSERT INTO `ts_area` VALUES ('610423', '涇陽縣', '610400', '6');
INSERT INTO `ts_area` VALUES ('610424', '乾　縣', '610400', '7');
INSERT INTO `ts_area` VALUES ('610425', '禮泉縣', '610400', '8');
INSERT INTO `ts_area` VALUES ('610426', '永壽縣', '610400', '9');
INSERT INTO `ts_area` VALUES ('610427', '彬　縣', '610400', '10');
INSERT INTO `ts_area` VALUES ('610428', '長武縣', '610400', '11');
INSERT INTO `ts_area` VALUES ('610429', '旬邑縣', '610400', '12');
INSERT INTO `ts_area` VALUES ('610430', '淳化縣', '610400', '13');
INSERT INTO `ts_area` VALUES ('610431', '武功縣', '610400', '14');
INSERT INTO `ts_area` VALUES ('610481', '興平市', '610400', '15');
INSERT INTO `ts_area` VALUES ('610501', '市轄區', '610500', '1');
INSERT INTO `ts_area` VALUES ('610502', '臨渭區', '610500', '2');
INSERT INTO `ts_area` VALUES ('610521', '華　縣', '610500', '3');
INSERT INTO `ts_area` VALUES ('610522', '潼關縣', '610500', '4');
INSERT INTO `ts_area` VALUES ('610523', '大荔縣', '610500', '5');
INSERT INTO `ts_area` VALUES ('610524', '合陽縣', '610500', '6');
INSERT INTO `ts_area` VALUES ('610525', '澄城縣', '610500', '7');
INSERT INTO `ts_area` VALUES ('610526', '蒲城縣', '610500', '8');
INSERT INTO `ts_area` VALUES ('610527', '白水縣', '610500', '9');
INSERT INTO `ts_area` VALUES ('610528', '富平縣', '610500', '10');
INSERT INTO `ts_area` VALUES ('610581', '韓城市', '610500', '11');
INSERT INTO `ts_area` VALUES ('610582', '華陰市', '610500', '12');
INSERT INTO `ts_area` VALUES ('610601', '市轄區', '610600', '1');
INSERT INTO `ts_area` VALUES ('610602', '寶塔區', '610600', '2');
INSERT INTO `ts_area` VALUES ('610621', '延長縣', '610600', '3');
INSERT INTO `ts_area` VALUES ('610622', '延川縣', '610600', '4');
INSERT INTO `ts_area` VALUES ('610623', '子長縣', '610600', '5');
INSERT INTO `ts_area` VALUES ('610624', '安塞縣', '610600', '6');
INSERT INTO `ts_area` VALUES ('610625', '志丹縣', '610600', '7');
INSERT INTO `ts_area` VALUES ('610626', '吳旗縣', '610600', '8');
INSERT INTO `ts_area` VALUES ('610627', '甘泉縣', '610600', '9');
INSERT INTO `ts_area` VALUES ('610628', '富　縣', '610600', '10');
INSERT INTO `ts_area` VALUES ('610629', '洛川縣', '610600', '11');
INSERT INTO `ts_area` VALUES ('610630', '宜川縣', '610600', '12');
INSERT INTO `ts_area` VALUES ('610631', '黃龍縣', '610600', '13');
INSERT INTO `ts_area` VALUES ('610632', '黃陵縣', '610600', '14');
INSERT INTO `ts_area` VALUES ('610701', '市轄區', '610700', '1');
INSERT INTO `ts_area` VALUES ('610702', '漢臺區', '610700', '2');
INSERT INTO `ts_area` VALUES ('610721', '南鄭縣', '610700', '3');
INSERT INTO `ts_area` VALUES ('610722', '城固縣', '610700', '4');
INSERT INTO `ts_area` VALUES ('610723', '洋　縣', '610700', '5');
INSERT INTO `ts_area` VALUES ('610724', '西鄉縣', '610700', '6');
INSERT INTO `ts_area` VALUES ('610725', '勉　縣', '610700', '7');
INSERT INTO `ts_area` VALUES ('610726', '寧強縣', '610700', '8');
INSERT INTO `ts_area` VALUES ('610727', '略陽縣', '610700', '9');
INSERT INTO `ts_area` VALUES ('610728', '鎮巴縣', '610700', '10');
INSERT INTO `ts_area` VALUES ('610729', '留壩縣', '610700', '11');
INSERT INTO `ts_area` VALUES ('610730', '佛坪縣', '610700', '12');
INSERT INTO `ts_area` VALUES ('610801', '市轄區', '610800', '1');
INSERT INTO `ts_area` VALUES ('610802', '榆陽區', '610800', '2');
INSERT INTO `ts_area` VALUES ('610821', '神木縣', '610800', '3');
INSERT INTO `ts_area` VALUES ('610822', '府谷縣', '610800', '4');
INSERT INTO `ts_area` VALUES ('610823', '橫山縣', '610800', '5');
INSERT INTO `ts_area` VALUES ('610824', '靖邊縣', '610800', '6');
INSERT INTO `ts_area` VALUES ('610825', '定邊縣', '610800', '7');
INSERT INTO `ts_area` VALUES ('610826', '綏德縣', '610800', '8');
INSERT INTO `ts_area` VALUES ('610827', '米脂縣', '610800', '9');
INSERT INTO `ts_area` VALUES ('610828', '佳　縣', '610800', '10');
INSERT INTO `ts_area` VALUES ('610829', '吳堡縣', '610800', '11');
INSERT INTO `ts_area` VALUES ('610830', '清澗縣', '610800', '12');
INSERT INTO `ts_area` VALUES ('610831', '子洲縣', '610800', '13');
INSERT INTO `ts_area` VALUES ('610901', '市轄區', '610900', '1');
INSERT INTO `ts_area` VALUES ('610902', '漢濱區', '610900', '2');
INSERT INTO `ts_area` VALUES ('610921', '漢陰縣', '610900', '3');
INSERT INTO `ts_area` VALUES ('610922', '石泉縣', '610900', '4');
INSERT INTO `ts_area` VALUES ('610923', '寧陝縣', '610900', '5');
INSERT INTO `ts_area` VALUES ('610924', '紫陽縣', '610900', '6');
INSERT INTO `ts_area` VALUES ('610925', '嵐皋縣', '610900', '7');
INSERT INTO `ts_area` VALUES ('610926', '平利縣', '610900', '8');
INSERT INTO `ts_area` VALUES ('610927', '鎮坪縣', '610900', '9');
INSERT INTO `ts_area` VALUES ('610928', '旬陽縣', '610900', '10');
INSERT INTO `ts_area` VALUES ('610929', '白河縣', '610900', '11');
INSERT INTO `ts_area` VALUES ('611001', '市轄區', '611000', '1');
INSERT INTO `ts_area` VALUES ('611002', '商州區', '611000', '2');
INSERT INTO `ts_area` VALUES ('611021', '洛南縣', '611000', '3');
INSERT INTO `ts_area` VALUES ('611022', '丹鳳縣', '611000', '4');
INSERT INTO `ts_area` VALUES ('611023', '商南縣', '611000', '5');
INSERT INTO `ts_area` VALUES ('611024', '山陽縣', '611000', '6');
INSERT INTO `ts_area` VALUES ('611025', '鎮安縣', '611000', '7');
INSERT INTO `ts_area` VALUES ('611026', '柞水縣', '611000', '8');
INSERT INTO `ts_area` VALUES ('620101', '市轄區', '620100', '1');
INSERT INTO `ts_area` VALUES ('620102', '城關區', '620100', '2');
INSERT INTO `ts_area` VALUES ('620103', '七里河區', '620100', '3');
INSERT INTO `ts_area` VALUES ('620104', '西固區', '620100', '4');
INSERT INTO `ts_area` VALUES ('620105', '安寧區', '620100', '5');
INSERT INTO `ts_area` VALUES ('620111', '紅古區', '620100', '6');
INSERT INTO `ts_area` VALUES ('620121', '永登縣', '620100', '7');
INSERT INTO `ts_area` VALUES ('620122', '皋蘭縣', '620100', '8');
INSERT INTO `ts_area` VALUES ('620123', '榆中縣', '620100', '9');
INSERT INTO `ts_area` VALUES ('620201', '市轄區', '620200', '1');
INSERT INTO `ts_area` VALUES ('620301', '市轄區', '620300', '1');
INSERT INTO `ts_area` VALUES ('620302', '金川區', '620300', '2');
INSERT INTO `ts_area` VALUES ('620321', '永昌縣', '620300', '3');
INSERT INTO `ts_area` VALUES ('620401', '市轄區', '620400', '1');
INSERT INTO `ts_area` VALUES ('620402', '白銀區', '620400', '2');
INSERT INTO `ts_area` VALUES ('620403', '平川區', '620400', '3');
INSERT INTO `ts_area` VALUES ('620421', '靖遠縣', '620400', '4');
INSERT INTO `ts_area` VALUES ('620422', '會寧縣', '620400', '5');
INSERT INTO `ts_area` VALUES ('620423', '景泰縣', '620400', '6');
INSERT INTO `ts_area` VALUES ('620501', '市轄區', '620500', '1');
INSERT INTO `ts_area` VALUES ('620502', '秦城區', '620500', '2');
INSERT INTO `ts_area` VALUES ('620503', '北道區', '620500', '3');
INSERT INTO `ts_area` VALUES ('620521', '清水縣', '620500', '4');
INSERT INTO `ts_area` VALUES ('620522', '秦安縣', '620500', '5');
INSERT INTO `ts_area` VALUES ('620523', '甘谷縣', '620500', '6');
INSERT INTO `ts_area` VALUES ('620524', '武山縣', '620500', '7');
INSERT INTO `ts_area` VALUES ('620525', '張家川回族自治縣', '620500', '8');
INSERT INTO `ts_area` VALUES ('620601', '市轄區', '620600', '1');
INSERT INTO `ts_area` VALUES ('620602', '涼州區', '620600', '2');
INSERT INTO `ts_area` VALUES ('620621', '民勤縣', '620600', '3');
INSERT INTO `ts_area` VALUES ('620622', '古浪縣', '620600', '4');
INSERT INTO `ts_area` VALUES ('620623', '天祝藏族自治縣', '620600', '5');
INSERT INTO `ts_area` VALUES ('620701', '市轄區', '620700', '1');
INSERT INTO `ts_area` VALUES ('620702', '甘州區', '620700', '2');
INSERT INTO `ts_area` VALUES ('620721', '肅南裕固族自治縣', '620700', '3');
INSERT INTO `ts_area` VALUES ('620722', '民樂縣', '620700', '4');
INSERT INTO `ts_area` VALUES ('620723', '臨澤縣', '620700', '5');
INSERT INTO `ts_area` VALUES ('620724', '高臺縣', '620700', '6');
INSERT INTO `ts_area` VALUES ('620725', '山丹縣', '620700', '7');
INSERT INTO `ts_area` VALUES ('620801', '市轄區', '620800', '1');
INSERT INTO `ts_area` VALUES ('620802', '崆峒區', '620800', '2');
INSERT INTO `ts_area` VALUES ('620821', '涇川縣', '620800', '3');
INSERT INTO `ts_area` VALUES ('620822', '靈臺縣', '620800', '4');
INSERT INTO `ts_area` VALUES ('620823', '崇信縣', '620800', '5');
INSERT INTO `ts_area` VALUES ('620824', '華亭縣', '620800', '6');
INSERT INTO `ts_area` VALUES ('620825', '莊浪縣', '620800', '7');
INSERT INTO `ts_area` VALUES ('620826', '靜寧縣', '620800', '8');
INSERT INTO `ts_area` VALUES ('620901', '市轄區', '620900', '1');
INSERT INTO `ts_area` VALUES ('620902', '肅州區', '620900', '2');
INSERT INTO `ts_area` VALUES ('620921', '金塔縣', '620900', '3');
INSERT INTO `ts_area` VALUES ('620922', '安西縣', '620900', '4');
INSERT INTO `ts_area` VALUES ('620923', '肅北蒙古族自治縣', '620900', '5');
INSERT INTO `ts_area` VALUES ('620924', '阿克塞哈薩克族自治縣', '620900', '6');
INSERT INTO `ts_area` VALUES ('620981', '玉門市', '620900', '7');
INSERT INTO `ts_area` VALUES ('620982', '敦煌市', '620900', '8');
INSERT INTO `ts_area` VALUES ('621001', '市轄區', '621000', '1');
INSERT INTO `ts_area` VALUES ('621002', '西峰區', '621000', '2');
INSERT INTO `ts_area` VALUES ('621021', '慶城縣', '621000', '3');
INSERT INTO `ts_area` VALUES ('621022', '環　縣', '621000', '4');
INSERT INTO `ts_area` VALUES ('621023', '華池縣', '621000', '5');
INSERT INTO `ts_area` VALUES ('621024', '合水縣', '621000', '6');
INSERT INTO `ts_area` VALUES ('621025', '正寧縣', '621000', '7');
INSERT INTO `ts_area` VALUES ('621026', '寧　縣', '621000', '8');
INSERT INTO `ts_area` VALUES ('621027', '鎮原縣', '621000', '9');
INSERT INTO `ts_area` VALUES ('621101', '市轄區', '621100', '1');
INSERT INTO `ts_area` VALUES ('621102', '安定區', '621100', '2');
INSERT INTO `ts_area` VALUES ('621121', '通渭縣', '621100', '3');
INSERT INTO `ts_area` VALUES ('621122', '隴西縣', '621100', '4');
INSERT INTO `ts_area` VALUES ('621123', '渭源縣', '621100', '5');
INSERT INTO `ts_area` VALUES ('621124', '臨洮縣', '621100', '6');
INSERT INTO `ts_area` VALUES ('621125', '漳　縣', '621100', '7');
INSERT INTO `ts_area` VALUES ('621126', '岷　縣', '621100', '8');
INSERT INTO `ts_area` VALUES ('621201', '市轄區', '621200', '1');
INSERT INTO `ts_area` VALUES ('621202', '武都區', '621200', '2');
INSERT INTO `ts_area` VALUES ('621221', '成　縣', '621200', '3');
INSERT INTO `ts_area` VALUES ('621222', '文　縣', '621200', '4');
INSERT INTO `ts_area` VALUES ('621223', '宕昌縣', '621200', '5');
INSERT INTO `ts_area` VALUES ('621224', '康　縣', '621200', '6');
INSERT INTO `ts_area` VALUES ('621225', '西和縣', '621200', '7');
INSERT INTO `ts_area` VALUES ('621226', '禮　縣', '621200', '8');
INSERT INTO `ts_area` VALUES ('621227', '徽　縣', '621200', '9');
INSERT INTO `ts_area` VALUES ('621228', '兩當縣', '621200', '10');
INSERT INTO `ts_area` VALUES ('622901', '臨夏市', '622900', '1');
INSERT INTO `ts_area` VALUES ('622921', '臨夏縣', '622900', '2');
INSERT INTO `ts_area` VALUES ('622922', '康樂縣', '622900', '3');
INSERT INTO `ts_area` VALUES ('622923', '永靖縣', '622900', '4');
INSERT INTO `ts_area` VALUES ('622924', '廣河縣', '622900', '5');
INSERT INTO `ts_area` VALUES ('622925', '和政縣', '622900', '6');
INSERT INTO `ts_area` VALUES ('622926', '東鄉族自治縣', '622900', '7');
INSERT INTO `ts_area` VALUES ('622927', '積石山保安族東鄉族撒拉族自治縣', '622900', '8');
INSERT INTO `ts_area` VALUES ('623001', '合作市', '623000', '1');
INSERT INTO `ts_area` VALUES ('623021', '臨潭縣', '623000', '2');
INSERT INTO `ts_area` VALUES ('623022', '卓尼縣', '623000', '3');
INSERT INTO `ts_area` VALUES ('623023', '舟曲縣', '623000', '4');
INSERT INTO `ts_area` VALUES ('623024', '迭部縣', '623000', '5');
INSERT INTO `ts_area` VALUES ('623025', '瑪曲縣', '623000', '6');
INSERT INTO `ts_area` VALUES ('623026', '碌曲縣', '623000', '7');
INSERT INTO `ts_area` VALUES ('623027', '夏河縣', '623000', '8');
INSERT INTO `ts_area` VALUES ('630101', '市轄區', '630100', '1');
INSERT INTO `ts_area` VALUES ('630102', '城東區', '630100', '2');
INSERT INTO `ts_area` VALUES ('630103', '城中區', '630100', '3');
INSERT INTO `ts_area` VALUES ('630104', '城西區', '630100', '4');
INSERT INTO `ts_area` VALUES ('630105', '城北區', '630100', '5');
INSERT INTO `ts_area` VALUES ('630121', '大通回族土族自治縣', '630100', '6');
INSERT INTO `ts_area` VALUES ('630122', '湟中縣', '630100', '7');
INSERT INTO `ts_area` VALUES ('630123', '湟源縣', '630100', '8');
INSERT INTO `ts_area` VALUES ('632121', '平安縣', '632100', '1');
INSERT INTO `ts_area` VALUES ('632122', '民和回族土族自治縣', '632100', '2');
INSERT INTO `ts_area` VALUES ('632123', '樂都縣', '632100', '3');
INSERT INTO `ts_area` VALUES ('632126', '互助土族自治縣', '632100', '4');
INSERT INTO `ts_area` VALUES ('632127', '化隆回族自治縣', '632100', '5');
INSERT INTO `ts_area` VALUES ('632128', '循化撒拉族自治縣', '632100', '6');
INSERT INTO `ts_area` VALUES ('632221', '門源回族自治縣', '632200', '1');
INSERT INTO `ts_area` VALUES ('632222', '祁連縣', '632200', '2');
INSERT INTO `ts_area` VALUES ('632223', '海晏縣', '632200', '3');
INSERT INTO `ts_area` VALUES ('632224', '剛察縣', '632200', '4');
INSERT INTO `ts_area` VALUES ('632321', '同仁縣', '632300', '1');
INSERT INTO `ts_area` VALUES ('632322', '尖扎縣', '632300', '2');
INSERT INTO `ts_area` VALUES ('632323', '澤庫縣', '632300', '3');
INSERT INTO `ts_area` VALUES ('632324', '河南蒙古族自治縣', '632300', '4');
INSERT INTO `ts_area` VALUES ('632521', '共和縣', '632500', '1');
INSERT INTO `ts_area` VALUES ('632522', '同德縣', '632500', '2');
INSERT INTO `ts_area` VALUES ('632523', '貴德縣', '632500', '3');
INSERT INTO `ts_area` VALUES ('632524', '興海縣', '632500', '4');
INSERT INTO `ts_area` VALUES ('632525', '貴南縣', '632500', '5');
INSERT INTO `ts_area` VALUES ('632621', '瑪沁縣', '632600', '1');
INSERT INTO `ts_area` VALUES ('632622', '班瑪縣', '632600', '2');
INSERT INTO `ts_area` VALUES ('632623', '甘德縣', '632600', '3');
INSERT INTO `ts_area` VALUES ('632624', '達日縣', '632600', '4');
INSERT INTO `ts_area` VALUES ('632625', '久治縣', '632600', '5');
INSERT INTO `ts_area` VALUES ('632626', '瑪多縣', '632600', '6');
INSERT INTO `ts_area` VALUES ('632721', '玉樹縣', '632700', '1');
INSERT INTO `ts_area` VALUES ('632722', '雜多縣', '632700', '2');
INSERT INTO `ts_area` VALUES ('632723', '稱多縣', '632700', '3');
INSERT INTO `ts_area` VALUES ('632724', '治多縣', '632700', '4');
INSERT INTO `ts_area` VALUES ('632725', '囊謙縣', '632700', '5');
INSERT INTO `ts_area` VALUES ('632726', '曲麻萊縣', '632700', '6');
INSERT INTO `ts_area` VALUES ('632801', '格爾木市', '632800', '1');
INSERT INTO `ts_area` VALUES ('632802', '德令哈市', '632800', '2');
INSERT INTO `ts_area` VALUES ('632821', '烏蘭縣', '632800', '3');
INSERT INTO `ts_area` VALUES ('632822', '都蘭縣', '632800', '4');
INSERT INTO `ts_area` VALUES ('632823', '天峻縣', '632800', '5');
INSERT INTO `ts_area` VALUES ('640101', '市轄區', '640100', '1');
INSERT INTO `ts_area` VALUES ('640104', '興慶區', '640100', '2');
INSERT INTO `ts_area` VALUES ('640105', '西夏區', '640100', '3');
INSERT INTO `ts_area` VALUES ('640106', '金鳳區', '640100', '4');
INSERT INTO `ts_area` VALUES ('640121', '永寧縣', '640100', '5');
INSERT INTO `ts_area` VALUES ('640122', '賀蘭縣', '640100', '6');
INSERT INTO `ts_area` VALUES ('640181', '靈武市', '640100', '7');
INSERT INTO `ts_area` VALUES ('640201', '市轄區', '640200', '1');
INSERT INTO `ts_area` VALUES ('640202', '大武口區', '640200', '2');
INSERT INTO `ts_area` VALUES ('640205', '惠農區', '640200', '3');
INSERT INTO `ts_area` VALUES ('640221', '平羅縣', '640200', '4');
INSERT INTO `ts_area` VALUES ('640301', '市轄區', '640300', '1');
INSERT INTO `ts_area` VALUES ('640302', '利通區', '640300', '2');
INSERT INTO `ts_area` VALUES ('640323', '鹽池縣', '640300', '3');
INSERT INTO `ts_area` VALUES ('640324', '同心縣', '640300', '4');
INSERT INTO `ts_area` VALUES ('640381', '青銅峽市', '640300', '5');
INSERT INTO `ts_area` VALUES ('640401', '市轄區', '640400', '1');
INSERT INTO `ts_area` VALUES ('640402', '原州區', '640400', '2');
INSERT INTO `ts_area` VALUES ('640422', '西吉縣', '640400', '3');
INSERT INTO `ts_area` VALUES ('640423', '隆德縣', '640400', '4');
INSERT INTO `ts_area` VALUES ('640424', '涇源縣', '640400', '5');
INSERT INTO `ts_area` VALUES ('640425', '彭陽縣', '640400', '6');
INSERT INTO `ts_area` VALUES ('640501', '市轄區', '640500', '1');
INSERT INTO `ts_area` VALUES ('640502', '沙坡頭區', '640500', '2');
INSERT INTO `ts_area` VALUES ('640521', '中寧縣', '640500', '3');
INSERT INTO `ts_area` VALUES ('640522', '海原縣', '640500', '4');
INSERT INTO `ts_area` VALUES ('650101', '市轄區', '650100', '1');
INSERT INTO `ts_area` VALUES ('650102', '天山區', '650100', '2');
INSERT INTO `ts_area` VALUES ('650103', '沙依巴克區', '650100', '3');
INSERT INTO `ts_area` VALUES ('650104', '新市區', '650100', '4');
INSERT INTO `ts_area` VALUES ('650105', '水磨溝區', '650100', '5');
INSERT INTO `ts_area` VALUES ('650106', '頭屯河區', '650100', '6');
INSERT INTO `ts_area` VALUES ('650107', '達阪城區', '650100', '7');
INSERT INTO `ts_area` VALUES ('650108', '東山區', '650100', '8');
INSERT INTO `ts_area` VALUES ('650121', '烏魯木齊縣', '650100', '9');
INSERT INTO `ts_area` VALUES ('650201', '市轄區', '650200', '1');
INSERT INTO `ts_area` VALUES ('650202', '獨山子區', '650200', '2');
INSERT INTO `ts_area` VALUES ('650203', '克拉瑪依區', '650200', '3');
INSERT INTO `ts_area` VALUES ('650204', '白鹼灘區', '650200', '4');
INSERT INTO `ts_area` VALUES ('650205', '烏爾禾區', '650200', '5');
INSERT INTO `ts_area` VALUES ('652101', '吐魯番市', '652100', '1');
INSERT INTO `ts_area` VALUES ('652122', '鄯善縣', '652100', '2');
INSERT INTO `ts_area` VALUES ('652123', '托克遜縣', '652100', '3');
INSERT INTO `ts_area` VALUES ('652201', '哈密市', '652200', '1');
INSERT INTO `ts_area` VALUES ('652222', '巴裡坤哈薩克自治縣', '652200', '2');
INSERT INTO `ts_area` VALUES ('652223', '伊吾縣', '652200', '3');
INSERT INTO `ts_area` VALUES ('652301', '昌吉市', '652300', '1');
INSERT INTO `ts_area` VALUES ('652302', '阜康市', '652300', '2');
INSERT INTO `ts_area` VALUES ('652303', '米泉市', '652300', '3');
INSERT INTO `ts_area` VALUES ('652323', '呼圖壁縣', '652300', '4');
INSERT INTO `ts_area` VALUES ('652324', '瑪納斯縣', '652300', '5');
INSERT INTO `ts_area` VALUES ('652325', '奇臺縣', '652300', '6');
INSERT INTO `ts_area` VALUES ('652327', '吉木薩爾縣', '652300', '7');
INSERT INTO `ts_area` VALUES ('652328', '木壘哈薩克自治縣', '652300', '8');
INSERT INTO `ts_area` VALUES ('652701', '博樂市', '652700', '1');
INSERT INTO `ts_area` VALUES ('652722', '精河縣', '652700', '2');
INSERT INTO `ts_area` VALUES ('652723', '溫泉縣', '652700', '3');
INSERT INTO `ts_area` VALUES ('652801', '庫爾勒市', '652800', '1');
INSERT INTO `ts_area` VALUES ('652822', '輪臺縣', '652800', '2');
INSERT INTO `ts_area` VALUES ('652823', '尉犁縣', '652800', '3');
INSERT INTO `ts_area` VALUES ('652824', '若羌縣', '652800', '4');
INSERT INTO `ts_area` VALUES ('652825', '且末縣', '652800', '5');
INSERT INTO `ts_area` VALUES ('652826', '焉耆回族自治縣', '652800', '6');
INSERT INTO `ts_area` VALUES ('652827', '和靜縣', '652800', '7');
INSERT INTO `ts_area` VALUES ('652828', '和碩縣', '652800', '8');
INSERT INTO `ts_area` VALUES ('652829', '博湖縣', '652800', '9');
INSERT INTO `ts_area` VALUES ('652901', '阿克蘇市', '652900', '1');
INSERT INTO `ts_area` VALUES ('652922', '溫宿縣', '652900', '2');
INSERT INTO `ts_area` VALUES ('652923', '庫車縣', '652900', '3');
INSERT INTO `ts_area` VALUES ('652924', '沙雅縣', '652900', '4');
INSERT INTO `ts_area` VALUES ('652925', '新和縣', '652900', '5');
INSERT INTO `ts_area` VALUES ('652926', '拜城縣', '652900', '6');
INSERT INTO `ts_area` VALUES ('652927', '烏什縣', '652900', '7');
INSERT INTO `ts_area` VALUES ('652928', '阿瓦提縣', '652900', '8');
INSERT INTO `ts_area` VALUES ('652929', '柯坪縣', '652900', '9');
INSERT INTO `ts_area` VALUES ('653001', '阿圖什市', '653000', '1');
INSERT INTO `ts_area` VALUES ('653022', '阿克陶縣', '653000', '2');
INSERT INTO `ts_area` VALUES ('653023', '阿合奇縣', '653000', '3');
INSERT INTO `ts_area` VALUES ('653024', '烏恰縣', '653000', '4');
INSERT INTO `ts_area` VALUES ('653101', '喀什市', '653100', '1');
INSERT INTO `ts_area` VALUES ('653121', '疏附縣', '653100', '2');
INSERT INTO `ts_area` VALUES ('653122', '疏勒縣', '653100', '3');
INSERT INTO `ts_area` VALUES ('653123', '英吉沙縣', '653100', '4');
INSERT INTO `ts_area` VALUES ('653124', '澤普縣', '653100', '5');
INSERT INTO `ts_area` VALUES ('653125', '莎車縣', '653100', '6');
INSERT INTO `ts_area` VALUES ('653126', '葉城縣', '653100', '7');
INSERT INTO `ts_area` VALUES ('653127', '麥蓋提縣', '653100', '8');
INSERT INTO `ts_area` VALUES ('653128', '嶽普湖縣', '653100', '9');
INSERT INTO `ts_area` VALUES ('653129', '伽師縣', '653100', '10');
INSERT INTO `ts_area` VALUES ('653130', '巴楚縣', '653100', '11');
INSERT INTO `ts_area` VALUES ('653131', '塔什庫爾干塔吉克自治縣', '653100', '12');
INSERT INTO `ts_area` VALUES ('653201', '和田市', '653200', '1');
INSERT INTO `ts_area` VALUES ('653221', '和田縣', '653200', '2');
INSERT INTO `ts_area` VALUES ('653222', '墨玉縣', '653200', '3');
INSERT INTO `ts_area` VALUES ('653223', '皮山縣', '653200', '4');
INSERT INTO `ts_area` VALUES ('653224', '洛浦縣', '653200', '5');
INSERT INTO `ts_area` VALUES ('653225', '策勒縣', '653200', '6');
INSERT INTO `ts_area` VALUES ('653226', '于田縣', '653200', '7');
INSERT INTO `ts_area` VALUES ('653227', '民豐縣', '653200', '8');
INSERT INTO `ts_area` VALUES ('654002', '伊寧市', '654000', '1');
INSERT INTO `ts_area` VALUES ('654003', '奎屯市', '654000', '2');
INSERT INTO `ts_area` VALUES ('654021', '伊寧縣', '654000', '3');
INSERT INTO `ts_area` VALUES ('654022', '察布查爾錫伯自治縣', '654000', '4');
INSERT INTO `ts_area` VALUES ('654023', '霍城縣', '654000', '5');
INSERT INTO `ts_area` VALUES ('654024', '鞏留縣', '654000', '6');
INSERT INTO `ts_area` VALUES ('654025', '新源縣', '654000', '7');
INSERT INTO `ts_area` VALUES ('654026', '昭蘇縣', '654000', '8');
INSERT INTO `ts_area` VALUES ('654027', '特克斯縣', '654000', '9');
INSERT INTO `ts_area` VALUES ('654028', '尼勒克縣', '654000', '10');
INSERT INTO `ts_area` VALUES ('654201', '塔城市', '654200', '1');
INSERT INTO `ts_area` VALUES ('654202', '烏蘇市', '654200', '2');
INSERT INTO `ts_area` VALUES ('654221', '額敏縣', '654200', '3');
INSERT INTO `ts_area` VALUES ('654223', '沙灣縣', '654200', '4');
INSERT INTO `ts_area` VALUES ('654224', '托裡縣', '654200', '5');
INSERT INTO `ts_area` VALUES ('654225', '裕民縣', '654200', '6');
INSERT INTO `ts_area` VALUES ('654226', '和布克賽爾蒙古自治縣', '654200', '7');
INSERT INTO `ts_area` VALUES ('654301', '阿勒泰市', '654300', '1');
INSERT INTO `ts_area` VALUES ('654321', '布爾津縣', '654300', '2');
INSERT INTO `ts_area` VALUES ('654322', '富蘊縣', '654300', '3');
INSERT INTO `ts_area` VALUES ('654323', '福海縣', '654300', '4');
INSERT INTO `ts_area` VALUES ('654324', '哈巴河縣', '654300', '5');
INSERT INTO `ts_area` VALUES ('654325', '青河縣', '654300', '6');
INSERT INTO `ts_area` VALUES ('654326', '吉木乃縣', '654300', '7');
INSERT INTO `ts_area` VALUES ('659001', '石河子市', '659000', '1');
INSERT INTO `ts_area` VALUES ('659002', '阿拉爾市', '659000', '2');
INSERT INTO `ts_area` VALUES ('659003', '圖木舒克市', '659000', '3');
INSERT INTO `ts_area` VALUES ('659004', '五家渠市', '659000', '4');
INSERT INTO `ts_area` VALUES ('810001', '香港', '810000', '1');
INSERT INTO `ts_area` VALUES ('810002', '中西區', '810001', '1');
INSERT INTO `ts_area` VALUES ('810003', '九龍城區', '810001', '2');
INSERT INTO `ts_area` VALUES ('810004', '南區', '810001', '3');
INSERT INTO `ts_area` VALUES ('810005', '黃大仙區', '810001', '4');
INSERT INTO `ts_area` VALUES ('810006', '油尖旺區', '810001', '5');
INSERT INTO `ts_area` VALUES ('810007', '葵青區', '810001', '6');
INSERT INTO `ts_area` VALUES ('810008', '西貢區', '810001', '7');
INSERT INTO `ts_area` VALUES ('810009', '屯門區', '810001', '8');
INSERT INTO `ts_area` VALUES ('810010', '荃灣區', '810001', '9');
INSERT INTO `ts_area` VALUES ('810011', '東區', '810001', '10');
INSERT INTO `ts_area` VALUES ('810012', '觀塘區', '810001', '11');
INSERT INTO `ts_area` VALUES ('810013', '深水步區', '810001', '12');
INSERT INTO `ts_area` VALUES ('810014', '灣仔區', '810001', '13');
INSERT INTO `ts_area` VALUES ('810015', '離島區', '810001', '14');
INSERT INTO `ts_area` VALUES ('810016', '北區', '810001', '15');
INSERT INTO `ts_area` VALUES ('810017', '沙田區', '810001', '16');
INSERT INTO `ts_area` VALUES ('810018', '大埔區', '810001', '17');
INSERT INTO `ts_area` VALUES ('810019', '元朗區', '810001', '18');
INSERT INTO `ts_area` VALUES ('820001', '澳門', '820000', '1');
INSERT INTO `ts_area` VALUES ('820002', '澳門', '820001', '1');
INSERT INTO `ts_area` VALUES ('710001', '臺北市', '710000', '1');
INSERT INTO `ts_area` VALUES ('710002', '臺北縣', '710001', '1');
INSERT INTO `ts_area` VALUES ('710003', '基隆市', '710000', '2');
INSERT INTO `ts_area` VALUES ('910005', '中山市', '442000', '1');
INSERT INTO `ts_area` VALUES ('710004', '花蓮縣', '710003', '1');
INSERT INTO `ts_area` VALUES ('910006', '東莞市', '441900', '1');
INSERT INTO `ts_area` VALUES ('910010', '1111', '910007', '1');

-- ----------------------------
-- Table structure for `ts_system_data`
-- ----------------------------
DROP TABLE IF EXISTS `ts_system_data`;
CREATE TABLE `ts_system_data` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list` char(30) DEFAULT 'default' COMMENT '列表名',
  `key` char(50) DEFAULT 'default' COMMENT '鍵名',
  `value` text COMMENT '鍵值',
  `mtime` timestamp NULL DEFAULT NULL COMMENT '修改時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `list_key` (`list`,`key`),
  KEY `list_id` (`list`,`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2665 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_system_data
-- ----------------------------
INSERT INTO `ts_system_data` VALUES ('95', 'admin_Home', 'newschedule', 'a:8:{s:11:\"task_to_run\";s:19:\"admin/Home/schedule\";s:13:\"schedule_type\";s:5:\"DAILY\";s:8:\"modifier\";s:1:\"1\";s:7:\"dirlist\";s:0:\"\";s:5:\"month\";s:0:\"\";s:14:\"start_datetime\";s:19:\"2012-04-10 18:41:54\";s:12:\"end_datetime\";s:19:\"2012-04-26 18:41:56\";s:4:\"info\";s:6:\"簡介\";}', '2012-04-07 18:47:56');
INSERT INTO `ts_system_data` VALUES ('177', 'permissiongroup', '', 'a:4:{s:4:\"core\";a:5:{i:0;i:1;i:1;i:2;i:2;i:3;i:3;i:12;i:4;i:15;}s:5:\"group\";a:3:{i:0;i:1;i:1;i:2;i:2;i:15;}s:5:\"weibo\";a:0:{}s:3:\"bbs\";a:0:{}}', '2012-04-19 18:24:46');
INSERT INTO `ts_system_data` VALUES ('680', 'permission', '25', 'a:1:{s:4:\"core\";a:2:{s:6:\"normal\";a:1:{s:9:\"feed_view\";s:1:\"1\";}s:5:\"admin\";a:1:{s:11:\"comment_del\";s:1:\"1\";}}}', '2012-06-11 21:48:57');
INSERT INTO `ts_system_data` VALUES ('2573', 'admin_nav', 'top', 'a:1:{s:4:\"page\";s:16:\"page/Admin/index\";}', '2013-03-12 21:53:18');
INSERT INTO `ts_system_data` VALUES ('2342', 'admin_Config', 'invite', 'a:2:{s:14:\"send_email_num\";s:1:\"3\";s:13:\"send_link_num\";s:1:\"3\";}', '2012-12-26 15:07:06');
INSERT INTO `ts_system_data` VALUES ('115', 'admin_Config', 'announcement', 'a:2:{s:7:\"is_open\";s:1:\"1\";s:7:\"content\";s:22:\"歡迎使用SociaxTeam\";}', '2012-03-26 03:06:29');
INSERT INTO `ts_system_data` VALUES ('2568', 'admin_Config', 'email', 'a:9:{s:14:\"email_sendtype\";s:4:\"smtp\";s:10:\"email_host\";s:18:\"smtp.exmail.qq.com\";s:9:\"email_ssl\";s:1:\"0\";s:10:\"email_port\";s:2:\"25\";s:13:\"email_account\";s:0:\"\";s:14:\"email_password\";s:0:\"\";s:17:\"email_sender_name\";s:20:\"ThinkSNS官方社羣\";s:18:\"email_sender_email\";s:0:\"\";s:10:\"email_test\";s:0:\"\";}', '2013-01-21 17:19:20');
INSERT INTO `ts_system_data` VALUES ('2311', 'admin_Config', 'attach', 'a:3:{s:16:\"attach_path_rule\";s:7:\"Y/md/H/\";s:15:\"attach_max_size\";s:2:\"15\";s:22:\"attach_allow_extension\";s:59:\"gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf,jpg\";}', '2012-12-19 13:40:53');
INSERT INTO `ts_system_data` VALUES ('1470', 'permission', '99', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 14:23:59');
INSERT INTO `ts_system_data` VALUES ('1471', 'permission', '100', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-24 14:23:59');
INSERT INTO `ts_system_data` VALUES ('2646', 'admin_Config', 'site', 'a:18:{s:11:\"site_closed\";s:1:\"1\";s:9:\"site_name\";s:11:\"ThinkSNS V3\";s:11:\"site_slogan\";s:36:\"最有價值的社會化網路產品\";s:20:\"site_header_keywords\";s:18:\"Sociax企業社羣\";s:23:\"site_header_description\";s:18:\"Sociax企業社羣\";s:12:\"site_company\";s:0:\"\";s:11:\"site_footer\";s:39:\"©2012 ZhishiSoft All Rights Reserved. \";s:9:\"site_logo\";s:0:\"\";s:8:\"login_bg\";s:0:\"\";s:18:\"site_closed_reason\";s:30:\"抱歉，本站暫停訪問。\";s:10:\"sys_domain\";s:5:\"admin\";s:12:\"sys_nickname\";s:25:\"管理員,超級管理員\";s:9:\"sys_email\";s:23:\"thinksns@zhishisoft.com\";s:9:\"home_page\";s:1:\"0\";s:11:\"sys_version\";s:8:\"20130523\";s:17:\"site_online_count\";s:1:\"1\";s:15:\"site_rewrite_on\";s:1:\"0\";s:19:\"site_analytics_code\";s:0:\"\";}', '2013-03-25 18:09:50');
INSERT INTO `ts_system_data` VALUES ('2641', 'admin_config', 'register', 'a:17:{s:13:\"register_type\";s:4:\"open\";s:12:\"email_suffix\";s:0:\"\";s:7:\"captcha\";s:1:\"1\";s:14:\"register_audit\";s:1:\"0\";s:11:\"need_active\";s:1:\"0\";s:10:\"photo_open\";s:1:\"1\";s:10:\"need_photo\";s:1:\"0\";s:8:\"tag_open\";s:1:\"1\";s:7:\"tag_num\";s:1:\"5\";s:15:\"interester_open\";s:1:\"1\";s:15:\"interester_rule\";a:2:{i:0;s:4:\"area\";i:1;s:3:\"tag\";}s:19:\"avoidSubmitByReturn\";s:0:\"\";s:20:\"interester_recommend\";s:0:\"\";s:14:\"default_follow\";s:0:\"\";s:11:\"each_follow\";s:0:\"\";s:18:\"default_user_group\";a:1:{i:0;s:1:\"3\";}s:13:\"welcome_email\";s:1:\"0\";}', '2013-03-25 18:09:16');
INSERT INTO `ts_system_data` VALUES ('2651', 'login', 'sina_wb_akey', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2634', 'admin_Config', 'audit', 'a:2:{s:4:\"open\";s:1:\"1\";s:7:\"replace\";s:2:\"**\";}', '2013-03-25 18:09:16');
INSERT INTO `ts_system_data` VALUES ('141', 'dict', 'nihao', 'a:2:{s:4:\"name\";s:6:\"你好\";s:5:\"value\";s:12:\"你好嗎？\";}', '2012-04-13 11:48:39');
INSERT INTO `ts_system_data` VALUES ('1877', 'permission', '17', 'a:0:{}', '2012-09-11 15:40:42');
INSERT INTO `ts_system_data` VALUES ('2599', 'permission', '3', 'a:2:{s:4:\"core\";a:1:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}}s:5:\"weiba\";a:1:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}}}', '2013-03-12 22:16:23');
INSERT INTO `ts_system_data` VALUES ('455', 'deFeedSet', '', 'a:6:{i:0;s:4:\"post\";i:1;s:6:\"repost\";i:2;s:9:\"postimage\";i:3;s:8:\"postfile\";i:4;s:6:\"follow\";i:5;s:14:\"profile_update\";}', '2012-05-31 13:34:26');
INSERT INTO `ts_system_data` VALUES ('1165', 'creditType', 'experience', 'a:2:{s:10:\"CreditType\";s:10:\"experience\";s:10:\"CreditName\";s:6:\"經驗\";}', '2012-07-18 08:32:06');
INSERT INTO `ts_system_data` VALUES ('1179', 'creditType', 'gold', 'a:2:{s:10:\"CreditType\";s:4:\"gold\";s:10:\"CreditName\";s:6:\"財富\";}', '2012-07-18 13:20:30');
INSERT INTO `ts_system_data` VALUES ('698', 'admin_Config', 'nav', 'a:11:{s:9:\"navi_name\";s:3:\"123\";s:6:\"attach\";s:0:\"\";s:8:\"app_name\";s:0:\"\";s:3:\"url\";s:3:\"123\";s:6:\"target\";s:7:\"appoint\";s:6:\"status\";s:7:\"appoint\";s:8:\"position\";s:0:\"\";s:5:\"guest\";s:7:\"appoint\";s:11:\"is_app_navi\";s:7:\"appoint\";s:9:\"parent_id\";s:3:\"123\";s:10:\"order_sort\";s:3:\"123\";}', '2012-06-15 11:38:13');
INSERT INTO `ts_system_data` VALUES ('2597', 'permission', '1', 'a:3:{s:4:\"core\";a:2:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}s:5:\"admin\";a:4:{s:11:\"admin_login\";s:1:\"1\";s:11:\"message_del\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";}}s:5:\"weiba\";a:2:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}s:5:\"admin\";a:6:{s:10:\"weiba_edit\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:16:\"weiba_global_top\";s:1:\"1\";s:12:\"weiba_marrow\";s:1:\"1\";s:9:\"weiba_top\";s:1:\"1\";s:15:\"weiba_recommend\";s:1:\"1\";}}s:7:\"channel\";a:1:{s:5:\"admin\";a:1:{s:17:\"channel_recommend\";s:1:\"1\";}}}', '2013-03-12 22:15:23');
INSERT INTO `ts_system_data` VALUES ('316', 'permission', '18', 'a:1:{s:4:\"core\";a:1:{s:6:\"normal\";a:1:{s:9:\"feed_post\";s:1:\"1\";}}}', '2012-05-25 11:27:11');
INSERT INTO `ts_system_data` VALUES ('2598', 'permission', '2', 'a:3:{s:4:\"core\";a:2:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}s:5:\"admin\";a:3:{s:11:\"message_del\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";}}s:5:\"weiba\";a:1:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}}s:7:\"channel\";a:1:{s:5:\"admin\";a:1:{s:17:\"channel_recommend\";s:1:\"1\";}}}', '2013-03-12 22:15:49');
INSERT INTO `ts_system_data` VALUES ('2253', 'permission', '4', 'a:1:{s:4:\"core\";a:1:{s:6:\"normal\";a:1:{s:9:\"feed_view\";s:1:\"1\";}}}', '2012-11-14 15:30:23');
INSERT INTO `ts_system_data` VALUES ('2452', 'admin_Credit', 'level', 'a:10:{i:0;a:5:{s:5:\"level\";i:1;s:4:\"name\";s:12:\"初學乍練\";s:5:\"image\";s:10:\"level1.png\";s:5:\"start\";s:1:\"0\";s:3:\"end\";s:4:\"1000\";}i:1;a:5:{s:5:\"level\";i:2;s:4:\"name\";s:12:\"登堂入室\";s:5:\"image\";s:10:\"level2.png\";s:5:\"start\";s:4:\"1001\";s:3:\"end\";s:4:\"2500\";}i:2;a:5:{s:5:\"level\";i:3;s:4:\"name\";s:12:\"略有小成\";s:5:\"image\";s:10:\"level3.png\";s:5:\"start\";s:4:\"2501\";s:3:\"end\";s:4:\"4500\";}i:3;a:5:{s:5:\"level\";i:4;s:4:\"name\";s:12:\"漸入佳境\";s:5:\"image\";s:10:\"level4.png\";s:5:\"start\";s:4:\"4501\";s:3:\"end\";s:4:\"7000\";}i:4;a:5:{s:5:\"level\";i:5;s:4:\"name\";s:12:\"爐火純青\";s:5:\"image\";s:10:\"level5.png\";s:5:\"start\";s:4:\"7501\";s:3:\"end\";s:5:\"10000\";}i:5;a:5:{s:5:\"level\";i:6;s:4:\"name\";s:12:\"自成一派\";s:5:\"image\";s:10:\"level6.png\";s:5:\"start\";s:5:\"10001\";s:3:\"end\";s:5:\"13500\";}i:6;a:5:{s:5:\"level\";i:7;s:4:\"name\";s:12:\"功行圓滿\";s:5:\"image\";s:10:\"level7.png\";s:5:\"start\";s:5:\"13501\";s:3:\"end\";s:5:\"17500\";}i:7;a:5:{s:5:\"level\";i:8;s:4:\"name\";s:12:\"登峰造極\";s:5:\"image\";s:10:\"level8.png\";s:5:\"start\";s:5:\"17501\";s:3:\"end\";s:5:\"22000\";}i:8;a:5:{s:5:\"level\";i:9;s:4:\"name\";s:12:\"出神入化\";s:5:\"image\";s:10:\"level9.png\";s:5:\"start\";s:5:\"22001\";s:3:\"end\";s:5:\"27000\";}i:9;a:5:{s:5:\"level\";i:10;s:4:\"name\";s:12:\"神功蓋世\";s:5:\"image\";s:11:\"level10.png\";s:5:\"start\";s:5:\"27000\";s:3:\"end\";s:5:\"99999\";}}', '2013-01-09 16:39:24');
INSERT INTO `ts_system_data` VALUES ('2620', 'keywordConfig', '', 's:39:\"共產黨,法輪功,習近平,操你媽\";', '2013-03-15 21:31:55');
INSERT INTO `ts_system_data` VALUES ('2250', 'admin_Credit', 'set', 'a:1:{s:5:\"weibo\";a:3:{s:3:\"add\";a:2:{s:10:\"experience\";s:1:\"5\";s:4:\"gold\";s:1:\"2\";}s:5:\"share\";a:2:{s:10:\"experience\";s:1:\"5\";s:4:\"gold\";s:1:\"2\";}s:5:\"reply\";a:2:{s:10:\"experience\";s:1:\"5\";s:4:\"gold\";s:1:\"2\";}}}', '2012-11-13 16:11:09');
INSERT INTO `ts_system_data` VALUES ('2635', 'admin_Config', 'feed', 'a:6:{s:10:\"weibo_nums\";s:3:\"140\";s:10:\"weibo_type\";a:7:{i:0;s:4:\"face\";i:1;s:2:\"at\";i:2;s:5:\"image\";i:3;s:5:\"video\";i:4;s:4:\"file\";i:5;s:5:\"topic\";i:6;s:10:\"contribute\";}s:16:\"weibo_premission\";a:2:{i:0;s:6:\"repost\";i:1;s:7:\"comment\";}s:15:\"weibo_send_info\";s:30:\"記錄，就是一種態度！\";s:19:\"weibo_default_topic\";s:0:\"\";s:11:\"weibo_at_me\";s:1:\"0\";}', '2013-03-25 18:09:16');
INSERT INTO `ts_system_data` VALUES ('575', 'permission', '12', 'a:1:{s:4:\"core\";a:1:{s:6:\"normal\";a:5:{s:9:\"feed_view\";s:1:\"1\";s:9:\"feed_post\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";}}}', '2012-06-01 20:35:29');
INSERT INTO `ts_system_data` VALUES ('1472', 'permission', '101', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 14:23:59');
INSERT INTO `ts_system_data` VALUES ('1457', 'permission', '96', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 14:17:28');
INSERT INTO `ts_system_data` VALUES ('860', 'permission', '40', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 12:04:46');
INSERT INTO `ts_system_data` VALUES ('872', 'permission', '39', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:4:{s:14:\"support_create\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 17:13:55');
INSERT INTO `ts_system_data` VALUES ('1458', 'permission', '97', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-24 14:17:28');
INSERT INTO `ts_system_data` VALUES ('1459', 'permission', '98', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 14:17:28');
INSERT INTO `ts_system_data` VALUES ('982', 'permission', '47', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 09:07:16');
INSERT INTO `ts_system_data` VALUES ('1408', 'permission', '93', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:55:38');
INSERT INTO `ts_system_data` VALUES ('806', 'permission', '26', 'a:2:{s:6:\"manage\";a:2:{s:6:\"normal\";a:1:{s:5:\"enter\";s:1:\"1\";}s:5:\"admin\";a:1:{s:7:\"support\";s:1:\"1\";}}s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:1:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";}}}', '2012-07-02 19:09:40');
INSERT INTO `ts_system_data` VALUES ('805', 'permission', '27', 'a:2:{s:6:\"manage\";a:2:{s:6:\"normal\";a:1:{s:5:\"enter\";s:1:\"1\";}s:5:\"admin\";a:1:{s:7:\"support\";s:1:\"1\";}}s:7:\"support\";a:1:{s:5:\"admin\";a:4:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-02 19:09:30');
INSERT INTO `ts_system_data` VALUES ('807', 'permission', '28', 'a:2:{s:6:\"manage\";a:2:{s:6:\"normal\";a:1:{s:5:\"enter\";s:1:\"1\";}s:5:\"admin\";a:1:{s:7:\"support\";s:1:\"1\";}}s:7:\"support\";a:1:{s:5:\"admin\";a:6:{s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";}}}', '2012-07-02 19:10:07');
INSERT INTO `ts_system_data` VALUES ('858', 'permission', '38', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 12:04:46');
INSERT INTO `ts_system_data` VALUES ('1214', 'permission', '81', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-18 19:43:11');
INSERT INTO `ts_system_data` VALUES ('1035', 'permission', '56', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:36:12');
INSERT INTO `ts_system_data` VALUES ('823', 'permission', '29', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 09:49:25');
INSERT INTO `ts_system_data` VALUES ('824', 'permission', '30', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 09:49:25');
INSERT INTO `ts_system_data` VALUES ('825', 'permission', '31', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 09:49:25');
INSERT INTO `ts_system_data` VALUES ('834', 'permission', '32', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:15:18');
INSERT INTO `ts_system_data` VALUES ('835', 'permission', '33', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:15:18');
INSERT INTO `ts_system_data` VALUES ('836', 'permission', '34', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:15:18');
INSERT INTO `ts_system_data` VALUES ('845', 'permission', '35', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:26:40');
INSERT INTO `ts_system_data` VALUES ('846', 'permission', '36', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:26:40');
INSERT INTO `ts_system_data` VALUES ('847', 'permission', '37', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-04 10:26:40');
INSERT INTO `ts_system_data` VALUES ('1395', 'permission', '90', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:55:15');
INSERT INTO `ts_system_data` VALUES ('881', 'permission', '41', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 17:14:37');
INSERT INTO `ts_system_data` VALUES ('882', 'permission', '42', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 17:14:37');
INSERT INTO `ts_system_data` VALUES ('883', 'permission', '43', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-05 17:14:37');
INSERT INTO `ts_system_data` VALUES ('914', 'permission', '44', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-06 11:25:24');
INSERT INTO `ts_system_data` VALUES ('963', 'permission', '45', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:6:{s:14:\"support_create\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-06 15:53:52');
INSERT INTO `ts_system_data` VALUES ('969', 'permission', '46', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:7:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";}}}', '2012-07-06 16:13:26');
INSERT INTO `ts_system_data` VALUES ('1018', 'permission', '53', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:33:17');
INSERT INTO `ts_system_data` VALUES ('983', 'permission', '48', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 09:07:16');
INSERT INTO `ts_system_data` VALUES ('984', 'permission', '49', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 09:07:16');
INSERT INTO `ts_system_data` VALUES ('1007', 'permission', '50', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:20:38');
INSERT INTO `ts_system_data` VALUES ('1008', 'permission', '51', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:20:38');
INSERT INTO `ts_system_data` VALUES ('1009', 'permission', '52', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:20:38');
INSERT INTO `ts_system_data` VALUES ('1019', 'permission', '54', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:33:17');
INSERT INTO `ts_system_data` VALUES ('1020', 'permission', '55', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-10 10:33:17');
INSERT INTO `ts_system_data` VALUES ('1036', 'permission', '57', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:36:12');
INSERT INTO `ts_system_data` VALUES ('1037', 'permission', '58', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:36:12');
INSERT INTO `ts_system_data` VALUES ('1046', 'permission', '59', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:58:57');
INSERT INTO `ts_system_data` VALUES ('1047', 'permission', '60', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:58:57');
INSERT INTO `ts_system_data` VALUES ('1048', 'permission', '61', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-11 16:58:57');
INSERT INTO `ts_system_data` VALUES ('1160', 'permission', '75', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 16:53:11');
INSERT INTO `ts_system_data` VALUES ('1090', 'permission', '62', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-14 16:11:37');
INSERT INTO `ts_system_data` VALUES ('1096', 'permission', '63', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:5:{s:14:\"support_create\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-14 16:30:05');
INSERT INTO `ts_system_data` VALUES ('1102', 'permission', '64', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";}}}', '2012-07-14 16:35:22');
INSERT INTO `ts_system_data` VALUES ('1127', 'permission', '69', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 08:53:45');
INSERT INTO `ts_system_data` VALUES ('1128', 'permission', '70', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-17 08:53:45');
INSERT INTO `ts_system_data` VALUES ('1129', 'permission', '71', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 08:53:45');
INSERT INTO `ts_system_data` VALUES ('1143', 'permission', '72', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 09:04:02');
INSERT INTO `ts_system_data` VALUES ('1151', 'permission', '73', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:14:\"support_create\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 16:49:52');
INSERT INTO `ts_system_data` VALUES ('1145', 'permission', '74', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 09:04:02');
INSERT INTO `ts_system_data` VALUES ('1161', 'permission', '76', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-17 16:53:11');
INSERT INTO `ts_system_data` VALUES ('1162', 'permission', '77', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-17 16:53:11');
INSERT INTO `ts_system_data` VALUES ('1203', 'permission', '78', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-18 19:41:41');
INSERT INTO `ts_system_data` VALUES ('1204', 'permission', '79', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-18 19:41:41');
INSERT INTO `ts_system_data` VALUES ('1205', 'permission', '80', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-18 19:41:41');
INSERT INTO `ts_system_data` VALUES ('1215', 'permission', '82', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-18 19:43:11');
INSERT INTO `ts_system_data` VALUES ('1216', 'permission', '83', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-18 19:43:11');
INSERT INTO `ts_system_data` VALUES ('1529', 'permission', '105', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-25 11:46:01');
INSERT INTO `ts_system_data` VALUES ('1235', 'permission', '84', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-19 17:47:26');
INSERT INTO `ts_system_data` VALUES ('1275', 'permission', '85', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:14:\"support_create\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-20 15:18:09');
INSERT INTO `ts_system_data` VALUES ('1349', 'permission', '86', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";}}}', '2012-07-23 16:46:09');
INSERT INTO `ts_system_data` VALUES ('1382', 'permission', '87', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:54:07');
INSERT INTO `ts_system_data` VALUES ('1383', 'permission', '88', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-24 09:54:07');
INSERT INTO `ts_system_data` VALUES ('1384', 'permission', '89', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:54:07');
INSERT INTO `ts_system_data` VALUES ('1396', 'permission', '91', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-24 09:55:15');
INSERT INTO `ts_system_data` VALUES ('1397', 'permission', '92', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:55:15');
INSERT INTO `ts_system_data` VALUES ('1409', 'permission', '94', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-24 09:55:38');
INSERT INTO `ts_system_data` VALUES ('1410', 'permission', '95', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 09:55:38');
INSERT INTO `ts_system_data` VALUES ('1550', 'permission', '108', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-25 12:33:31');
INSERT INTO `ts_system_data` VALUES ('1483', 'permission', '102', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 14:33:12');
INSERT INTO `ts_system_data` VALUES ('1514', 'permission', '103', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:7:{s:19:\"support_viewversion\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 18:32:28');
INSERT INTO `ts_system_data` VALUES ('1508', 'permission', '104', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-24 18:22:42');
INSERT INTO `ts_system_data` VALUES ('2206', 'addons', 'default_style', 's:4:\"gray\";', '2012-11-09 16:37:09');
INSERT INTO `ts_system_data` VALUES ('1530', 'permission', '106', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-25 11:46:01');
INSERT INTO `ts_system_data` VALUES ('1531', 'permission', '107', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:9:{s:14:\"support_update\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-25 11:46:01');
INSERT INTO `ts_system_data` VALUES ('1565', 'permission', '109', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:14:\"support_create\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-25 14:08:39');
INSERT INTO `ts_system_data` VALUES ('1561', 'permission', '110', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:7:{s:14:\"support_submit\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";}}}', '2012-07-25 13:56:17');
INSERT INTO `ts_system_data` VALUES ('1576', 'permission', '111', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-25 14:38:08');
INSERT INTO `ts_system_data` VALUES ('1577', 'permission', '112', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:8:{s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:14:\"support_update\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";}}}', '2012-07-25 14:38:08');
INSERT INTO `ts_system_data` VALUES ('1580', 'permission', '113', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:6:{s:19:\"support_viewversion\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";}}}', '2012-07-25 14:40:05');
INSERT INTO `ts_system_data` VALUES ('1615', 'permission', '114', 'a:1:{s:7:\"support\";a:2:{s:6:\"normal\";a:1:{s:20:\"support_viewfeedback\";s:1:\"1\";}s:5:\"admin\";a:2:{s:25:\"support_setfeedbackstatus\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";}}}', '2012-07-27 10:15:32');
INSERT INTO `ts_system_data` VALUES ('1616', 'permission', '115', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:11:{s:14:\"support_update\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:14:\"support_create\";s:1:\"1\";s:14:\"support_submit\";s:1:\"1\";s:16:\"support_deldraft\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:17:\"support_seereject\";s:1:\"1\";s:18:\"support_seesuccess\";s:1:\"1\";s:18:\"support_seewaiting\";s:1:\"1\";}}}', '2012-07-27 10:15:32');
INSERT INTO `ts_system_data` VALUES ('1617', 'permission', '116', 'a:1:{s:7:\"support\";a:1:{s:5:\"admin\";a:12:{s:14:\"support_update\";s:1:\"1\";s:13:\"support_audit\";s:1:\"1\";s:15:\"support_viewlog\";s:1:\"1\";s:21:\"support_updateversion\";s:1:\"1\";s:19:\"support_viewversion\";s:1:\"1\";s:17:\"support_setstatus\";s:1:\"1\";s:21:\"support_changeversion\";s:1:\"1\";s:6:\"manage\";s:1:\"1\";s:18:\"support_seerelease\";s:1:\"1\";s:17:\"support_seereject\";s:1:\"1\";s:18:\"support_seesuccess\";s:1:\"1\";s:18:\"support_seewaiting\";s:1:\"1\";}}}', '2012-07-27 10:15:32');
INSERT INTO `ts_system_data` VALUES ('1910', 'permission', '', 'a:0:{}', '2012-09-14 15:36:22');
INSERT INTO `ts_system_data` VALUES ('2591', 'admin_Config', 'cloudimage', 'a:7:{s:16:\"cloud_image_open\";s:1:\"0\";s:19:\"cloud_image_api_url\";s:23:\"http://v0.api.upyun.com\";s:18:\"cloud_image_bucket\";s:0:\"\";s:24:\"cloud_image_form_api_key\";s:0:\"\";s:23:\"cloud_image_prefix_urls\";s:0:\"\";s:17:\"cloud_image_admin\";s:0:\"\";s:20:\"cloud_image_password\";s:0:\"\";}', '2013-03-12 22:07:24');
INSERT INTO `ts_system_data` VALUES ('2592', 'admin_Config', 'cloudattach', 'a:7:{s:17:\"cloud_attach_open\";s:1:\"0\";s:20:\"cloud_attach_api_url\";s:23:\"http://v0.api.upyun.com\";s:19:\"cloud_attach_bucket\";s:0:\"\";s:25:\"cloud_attach_form_api_key\";s:0:\"\";s:24:\"cloud_attach_prefix_urls\";s:0:\"\";s:18:\"cloud_attach_admin\";s:0:\"\";s:21:\"cloud_attach_password\";s:0:\"\";}', '2013-03-12 22:07:34');
INSERT INTO `ts_system_data` VALUES ('2600', 'permission', '5', 'a:2:{s:4:\"core\";a:1:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}}s:5:\"weiba\";a:1:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}}}', '2013-03-12 22:16:38');
INSERT INTO `ts_system_data` VALUES ('2601', 'permission', '6', 'a:2:{s:4:\"core\";a:1:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}}s:5:\"weiba\";a:1:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}}}', '2013-03-12 22:17:10');
INSERT INTO `ts_system_data` VALUES ('2602', 'permission', '7', 'a:2:{s:4:\"core\";a:1:{s:6:\"normal\";a:10:{s:9:\"feed_post\";s:1:\"1\";s:9:\"read_data\";s:1:\"1\";s:11:\"invite_user\";s:1:\"1\";s:12:\"send_message\";s:1:\"1\";s:11:\"search_info\";s:1:\"1\";s:11:\"comment_del\";s:1:\"1\";s:12:\"feed_comment\";s:1:\"1\";s:11:\"feed_report\";s:1:\"1\";s:10:\"feed_share\";s:1:\"1\";s:8:\"feed_del\";s:1:\"1\";}}s:5:\"weiba\";a:1:{s:6:\"normal\";a:6:{s:10:\"weiba_post\";s:1:\"1\";s:11:\"weiba_reply\";s:1:\"1\";s:9:\"weiba_del\";s:1:\"1\";s:15:\"weiba_del_reply\";s:1:\"1\";s:10:\"weiba_edit\";s:1:\"1\";s:18:\"weiba_apply_manage\";s:1:\"1\";}}}', '2013-03-12 22:17:22');
INSERT INTO `ts_system_data` VALUES ('2309', 'admin_User', 'verifyConfig', 'a:2:{s:19:\"avoidSubmitByReturn\";s:0:\"\";s:8:\"top_user\";s:0:\"\";}', '2012-12-18 09:29:25');
INSERT INTO `ts_system_data` VALUES ('2652', 'login', 'sina_wb_skey', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2655', 'login', 'qq_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2656', 'login', 'qq_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2659', 'login', 'douban_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2660', 'login', 'douban_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2653', 'login', 'qzone_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2654', 'login', 'qzone_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2649', 'login', 'open', 'a:7:{i:0;s:4:\"sina\";i:1;s:5:\"qzone\";i:2;s:2:\"qq\";i:3;s:6:\"renren\";i:4;s:6:\"douban\";i:5;s:5:\"baidu\";i:6;s:6:\"taobao\";}', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2657', 'login', 'renren_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2658', 'login', 'renren_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2661', 'login', 'baidu_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2662', 'login', 'baidu_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2663', 'login', 'taobao_key', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2664', 'login', 'taobao_secret', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('2536', 'login', 'publish', 'a:2:{i:0;s:4:\"sina\";i:1;s:2:\"qq\";}', '2013-01-14 11:00:46');
INSERT INTO `ts_system_data` VALUES ('2579', 'cacheconfig', 'cachetype', 's:4:\"File\";', '2013-03-12 21:57:32');
INSERT INTO `ts_system_data` VALUES ('2580', 'cacheconfig', 'cachesetting', 'N;', '2013-03-12 21:57:32');
INSERT INTO `ts_system_data` VALUES ('2594', 'admin_Config', 'seo_feed_topic', 'a:6:{s:4:\"name\";s:9:\"話題頁\";s:5:\"title\";s:11:\"{topicName}\";s:8:\"keywords\";s:11:\"{topicName}\";s:3:\"des\";s:34:\"{topicNote}-{topicDes}-{lastTopic}\";s:4:\"node\";s:0:\"\";s:3:\"sub\";s:6:\"儲存\";}', '2013-03-12 22:14:20');
INSERT INTO `ts_system_data` VALUES ('2595', 'admin_Config', 'seo_feed_detail', 'a:6:{s:4:\"name\";s:15:\"微博詳情頁\";s:5:\"title\";s:16:\"{uname}的微博\";s:8:\"keywords\";s:16:\"{uname}的微博\";s:3:\"des\";s:28:\"{uname}的微博：{content}\";s:4:\"node\";s:0:\"\";s:3:\"sub\";s:6:\"儲存\";}', '2013-03-12 22:14:40');
INSERT INTO `ts_system_data` VALUES ('2596', 'admin_Config', 'seo_user_profile', 'a:6:{s:4:\"name\";s:12:\"個人主頁\";s:5:\"title\";s:16:\"{uname}的主頁\";s:8:\"keywords\";s:16:\"{uname}的主頁\";s:3:\"des\";s:10:\"{lastFeed}\";s:4:\"node\";s:0:\"\";s:3:\"sub\";s:6:\"儲存\";}', '2013-03-12 22:14:52');
INSERT INTO `ts_system_data` VALUES ('2609', 'admin_User', 'findPeopleConfig', 'a:1:{s:10:\"findPeople\";a:4:{i:0;s:3:\"tag\";i:1;s:4:\"area\";i:2;s:6:\"verify\";i:3;s:8:\"official\";}}', '2013-03-12 22:19:37');
INSERT INTO `ts_system_data` VALUES ('2615', 'task_config', 'task_switch', 's:1:\"1\";', '2013-03-12 22:23:41');
INSERT INTO `ts_system_data` VALUES ('2650', 'login', 'platformMeta', 's:0:\"\";', '2013-03-25 18:31:17');
INSERT INTO `ts_system_data` VALUES ('3596', 'update', 'version', 's:10:\"2013052318\";', '2013-05-23 15:26:38');

-- ----------------------------
-- Table structure for `ts_feed_node`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed_node`;
CREATE TABLE `ts_feed_node` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `nodetype` varchar(50) NOT NULL COMMENT '動態類型',
  `nodeinfo` varchar(50) NOT NULL COMMENT '動態名稱',
  `xml` text NOT NULL COMMENT '模板內容',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feed_node
-- ----------------------------
INSERT INTO `ts_feed_node` VALUES ('1', 'public', 'post', '原創微博', '&lt;title comment=&quot;feed標題&quot;&gt; \r\n			&lt;![CDATA[ {$actor}  ]]&gt;\r\n		&lt;/title&gt;\r\n		&lt;body comment=&quot;feed詳細內容/引用的內容&quot;&gt;&lt;a href=&quot;&quot;&gt;詳情&lt;/a&gt;\r\n			&lt;![CDATA[{$body} ]]&gt;&lt;a href=&quot;&quot;&gt;詳情&lt;/a&gt;\r\n		&lt;/body&gt;\r\n		&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot;  delete=&quot;true&quot; /&gt;');
INSERT INTO `ts_feed_node` VALUES ('2', 'public', 'repost', '轉發微博', '&lt;title&gt;&lt;![CDATA[{$actor} ]]&gt;&lt;/title&gt;\r\n&lt;body&gt;\r\n	&lt;![CDATA[\r\n	&lt;eq name=\'body\' value=\'\'&gt; 微博分享 &lt;/eq&gt; \r\n	{$body}\r\n	&lt;dl class=&quot;comment&quot;&gt;\r\n		&lt;dt class=&quot;arrow bgcolor_arrow&quot;&gt;&lt;em class=&quot;arrline&quot;&gt;◆&lt;/em&gt;&lt;span class=&quot;downline&quot;&gt;◆&lt;/span&gt;&lt;/dt&gt;\r\n		&lt;php&gt;if($sourceInfo[\'is_del\'] == 0):&lt;/php&gt;\r\n		&lt;dd class=&quot;name&quot;&gt;\r\n			@{$sourceInfo.source_user_info.uname}\r\n			&lt;volist name=&quot;sourceInfo[\'groupData\'][$sourceInfo[\'source_user_info\'][\'uid\']]&quot; id=&quot;v2&quot;&gt;\r\n				&lt;img style=&quot;width:auto;height:auto;display:inline;cursor:pointer&quot; src=&quot;{$v2[\'user_group_icon_url\']}&quot; title=&quot;{$v2[\'user_group_name\']}&quot; /&gt; \r\n			&lt;/volist&gt;\r\n		&lt;/dd&gt;\r\n		&lt;dd&gt;\r\n			{$sourceInfo.source_content}\r\n			&lt;php&gt;if(!empty($sourceInfo[\'attach\'])):&lt;/php&gt;\r\n			&lt;eq name=\'sourceInfo.feedType\' value=\'postfile\'&gt;\r\n			&lt;ul class=&quot;feed_file_list&quot;&gt;\r\n				&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n				&lt;li&gt;\r\n					&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot; class=&quot;current right&quot; target=&quot;_blank&quot;&gt;&lt;i class=&quot;ico-down&quot;&gt;&lt;/i&gt;&lt;/a&gt;\r\n					&lt;i class=&quot;ico-{$vo.extension}-small&quot;&gt;&lt;/i&gt;\r\n					&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot;&gt;{$vo.attach_name}&lt;/a&gt;\r\n					&lt;span class=&quot;tips&quot;&gt;({$vo.size|byte_format})&lt;/span&gt;\r\n				&lt;/li&gt;\r\n				&lt;/volist&gt;			\r\n			&lt;/ul&gt;		\r\n			&lt;/eq&gt;\r\n\r\n			&lt;eq name=\'sourceInfo.feedType\' value=\'postimage\'&gt;\r\n			&lt;div class=&quot;feed_img_lists&quot; rel=\'small\' &gt;\r\n				&lt;ul class=&quot;small&quot;&gt;\r\n					&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n					&lt;li&gt;&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_small\'&gt;&lt;img class=&quot;imgicon&quot; src=\'{$vo.attach_small}\' title=\'點選放大\'&gt;&lt;/a&gt;&lt;/li&gt;\r\n					&lt;/volist&gt;\r\n				&lt;/ul&gt;\r\n			&lt;/div&gt;\r\n			&lt;div class=&quot;feed_img_lists&quot; rel=\'big\' style=\'display:none\'&gt;\r\n				&lt;ul class=&quot;feed_img_list big&quot;&gt;\r\n					&lt;p class=\'tools\'&gt;&lt;a href=&quot;javascript:void(0);&quot; event-node=\'img_big\' class=&quot;ico-pack-up&quot;&gt;收起&lt;/a&gt;&lt;/p&gt;\r\n					&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n					&lt;php&gt;$imgInfo = getThumbImage($vo[\'attach_url\'],425);&lt;/php&gt;\r\n					&lt;li style=&quot;width:{$imgInfo.width}px;&quot; title=\'{$vo[\'attach_url\']}\'&gt;\r\n						&lt;i class=&quot;check-big&quot;&gt;&lt;a href=\'{$vo[\'attach_url\']}\' target=&quot;_blank&quot; class=&quot;ico-show-big&quot; title=&quot;檢視大圖&quot; &gt;&lt;/a&gt;&lt;/i&gt;\r\n						&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_big\'&gt;&lt;img class=&quot;imgsmall&quot; src=\'{$imgInfo.src}\' title=\'點選縮小\' /&gt;&lt;/a&gt;\r\n					&lt;/li&gt;\r\n					&lt;/volist&gt;\r\n				&lt;/ul&gt;\r\n			&lt;/div&gt;\r\n			&lt;/eq&gt;	\r\n			&lt;php&gt;endif;&lt;/php&gt;	\r\n		&lt;/dd&gt;\r\n&lt;!--\r\n		&lt;p class=&quot;info&quot;&gt;\r\n			&lt;span class=&quot;right&quot;&gt;\r\n				&lt;a href=&quot;{:U(\'public/Profile/feed\',array(\'uid\'=&gt;$sourceInfo[\'uid\'],\'feed_id\'=&gt;$sourceInfo[\'feed_id\']))}&quot;&gt;原文轉發&lt;neq name=&quot;sourceInfo.repost_count&quot; value=&quot;0&quot;&gt;({$sourceInfo.repost_count})&lt;/neq&gt;&lt;/a&gt;&lt;i class=&quot;vline&quot;&gt;|&lt;/i&gt;\r\n				&lt;a href=&quot;{:U(\'public/Profile/feed\',array(\'uid\'=&gt;$sourceInfo[\'uid\'],\'feed_id\'=&gt;$sourceInfo[\'feed_id\']))}&quot;&gt;原文收藏&lt;/a&gt;&lt;i class=&quot;vline&quot;&gt;|&lt;/i&gt;\r\n				&lt;a href=&quot;{:U(\'public/Profile/feed\',array(\'uid\'=&gt;$sourceInfo[\'uid\'],\'feed_id\'=&gt;$sourceInfo[\'feed_id\']))}&quot;&gt;原文評論&lt;neq name=&quot;sourceInfo.comment_count&quot; value=&quot;0&quot;&gt;({$sourceInfo.comment_count})&lt;/neq&gt;&lt;/a&gt;\r\n			&lt;/span&gt;\r\n			&lt;span&gt;&lt;a href=&quot;{:U(\'public/Profile/feed\',array(\'uid\'=&gt;$sourceInfo[\'uid\'],\'feed_id\'=&gt;$sourceInfo[\'feed_id\']))}&quot; class=&quot;date&quot;&gt;{$sourceInfo[\'ctime\']|friendlyDate}&lt;/a&gt;&lt;span&gt;來自網站&lt;/span&gt;&lt;/span&gt;\r\n		&lt;/p&gt;\r\n--&gt;\r\n		&lt;php&gt;else:&lt;/php&gt;\r\n		&lt;dd class=&quot;name&quot;&gt;內容已被刪除&lt;/dd&gt;\r\n		&lt;php&gt;endif;&lt;/php&gt;\r\n	&lt;/dl&gt;\r\n	]]&gt;\r\n&lt;/body&gt;\r\n&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot;  delete=&quot;true&quot; /&gt;');
INSERT INTO `ts_feed_node` VALUES ('6', 'weiba', 'weiba_repost', '微吧轉發', '	&lt;title&gt; \r\n			&lt;![CDATA[{$actor} ]]&gt;\r\n		&lt;/title&gt;\r\n		&lt;body&gt;\r\n			&lt;![CDATA[\r\n				&lt;eq name=\'body\' value=\'\'&gt; 微博分享 &lt;/eq&gt; \r\n				{$body}\r\n				&lt;dl class=&quot;comment&quot;&gt;&lt;dt class=&quot;arrow bgcolor_arrow&quot;&gt;&lt;em class=&quot;arrline&quot;&gt;◆&lt;/em&gt;&lt;span class=&quot;downline&quot;&gt;◆&lt;/span&gt;&lt;/dt&gt;\r\n				&lt;dd class=&quot;name&quot;&gt;\r\n						&lt;php&gt; if($sourceInfo[\'source_user_info\'][\'uid\'] == $GLOBALS[\'ts\'][\'mid\']){&lt;/php&gt;\r\n					@{$sourceInfo.source_user_info.uname}\r\n					&lt;php&gt; }else{&lt;/php&gt;\r\n					@{$sourceInfo.source_user_info.uname}\r\n					&lt;php&gt; }&lt;/php&gt;\r\n&lt;volist name=&quot;sourceInfo[\'groupData\'][$sourceInfo[\'source_user_info\'][\'uid\']]&quot; id=&quot;v2&quot;&gt;\r\n        				&lt;img style=&quot;width:auto;height:auto;display:inline;cursor:pointer&quot; src=&quot;{$v2[\'user_group_icon_url\']}&quot; title=&quot;{$v2[\'user_group_name\']}&quot; /&gt; \r\n    				&lt;/volist&gt;\r\n				&lt;/dd&gt;\r\n				&lt;dd&gt;\r\n					{$sourceInfo.source_content}\r\n&lt;php&gt;if(APP_NAME == \'public\' || APP_NAME == \'widget\'){&lt;/php&gt;\r\n&lt;a href=&quot;javascript:void(0)&quot; class=&quot;ico-details&quot; event-node =\'loadPost\' event-args=\'feed_id={$feedid}&amp;post_id={$app_row_id}\' id=&quot;{$app_row_id}&quot;&gt;&lt;i class=&quot;triangle-l&quot;&gt;&lt;/i&gt;檢視全文&lt;/a&gt;&lt;div class=&quot;feed_img_lists&quot; style=&quot;display:none;&quot; id=&quot;post_{$feedid}_{$app_row_id}&quot;&gt;\r\n&lt;/div&gt;\r\n&lt;php&gt;}else{&lt;/php&gt;\r\n&lt;a href=&quot;{:U(\'weiba/Index/postDetail\',array(\'post_id\'=&gt;$app_row_id))}&quot; class=&quot;ico-details&quot; target=&quot;_blank&quot;&gt;&lt;/a&gt;\r\n&lt;php&gt;}&lt;/php&gt;\r\n					&lt;php&gt;if(!empty($sourceInfo[\'attach\'])):&lt;/php&gt;\r\n					\r\n					&lt;eq name=\'sourceInfo.feedtype\' value=\'postfile\'&gt;\r\n						&lt;ul class=&quot;feed_file_list&quot;&gt;\r\n						&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n							&lt;li&gt;&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot; class=&quot;current right&quot; target=&quot;_blank&quot;&gt;&lt;i class=&quot;ico-down&quot;&gt;&lt;/i&gt;&lt;/a&gt;&lt;i class=&quot;ico-{$vo.extension}-small&quot;&gt;&lt;/i&gt;&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot;&gt;\r\n							{$vo.attach_name}&lt;/a&gt; &lt;span class=&quot;tips&quot;&gt;({$vo.size|byte_format})&lt;/span&gt;&lt;/li&gt;\r\n						&lt;/volist&gt;			\r\n						&lt;/ul&gt;		\r\n					&lt;/eq&gt;\r\n\r\n					&lt;eq name=\'sourceInfo.feedtype\' value=\'postimage\'&gt;\r\n					 &lt;div class=&quot;feed_img_lists&quot; rel=\'small\' &gt;\r\n						&lt;ul class=&quot;small&quot;&gt;\r\n						&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n							&lt;li &gt;&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_small\'&gt;&lt;img class=&quot;imgicon&quot; src=\'{$vo.attach_small}\' title=\'點選放大\'&gt;&lt;/a&gt;&lt;/li&gt;\r\n						&lt;/volist&gt;\r\n						&lt;/ul&gt;\r\n						&lt;/div&gt;\r\n						&lt;div class=&quot;feed_img_lists&quot; rel=\'big\' style=\'display:none\'&gt;\r\n						&lt;ul class=&quot;feed_img_list big&quot; &gt;\r\n						&lt;p class=\'tools\'&gt;&lt;a href=&quot;javascript:void(0);&quot; event-node=\'img_big\' class=&quot;ico-pack-up&quot;&gt;收起&lt;/a&gt;&lt;/p&gt;\r\n						&lt;volist name=\'sourceInfo.attach\' id=\'vo\'&gt;\r\n							&lt;php&gt; $imgInfo = getThumbImage($vo[\'attach_url\'],425);&lt;/php&gt;\r\n							&lt;li style=&quot;width:{$imgInfo.width}px;&quot; title=\'{$vo[\'attach_url\']}\'&gt;\r\n							&lt;i class=&quot;check-big&quot;&gt;&lt;a href=\'{$vo[\'attach_url\']}\' target=&quot;_blank&quot; class=&quot;ico-show-big&quot; title=&quot;檢視大圖&quot; &gt;&lt;/a&gt;&lt;/i&gt;\r\n							&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_big\'&gt;&lt;img class=&quot;imgsmall&quot; src=\'{$imgInfo.src}\' title=\'點選縮小\'&gt;&lt;/a&gt;&lt;/li&gt;\r\n						&lt;/volist&gt;\r\n						&lt;/ul&gt;\r\n						&lt;/div&gt;\r\n					&lt;/eq&gt;	\r\n					&lt;php&gt;endif;&lt;/php&gt;	\r\n				&lt;/dd&gt;&lt;/dl&gt;\r\n			]]&gt;\r\n		&lt;/body&gt;\r\n		&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot;  delete=&quot;true&quot; /&gt;');
INSERT INTO `ts_feed_node` VALUES ('3', 'public', 'postimage', '發圖片微博', '		&lt;title&gt; \r\n			&lt;![CDATA[\r\n				{$actor}\r\n			]]&gt;\r\n		&lt;/title&gt;\r\n		&lt;body&gt;\r\n			&lt;![CDATA[ \r\n				{$body}&lt;br/&gt;\r\n				&lt;div class=&quot;feed_img_lists&quot; rel=\'small\' &gt;\r\n				&lt;ul class=&quot;small&quot;&gt;\r\n				&lt;volist name=\'attachInfo\' id=\'vo\'&gt;\r\n					&lt;li &gt;&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_small\'&gt;\r\n						&lt;img class=&quot;imgicon&quot; src=\'{$vo.attach_small}\' title=\'點選放大\' &gt;&lt;/a&gt;\r\n					&lt;/li&gt; \r\n				&lt;/volist&gt;\r\n				&lt;/ul&gt;\r\n				&lt;/div&gt;\r\n				&lt;div class=&quot;feed_img_lists&quot; rel=\'big\' style=\'display:none\'&gt;\r\n				&lt;ul class=&quot;feed_img_list big&quot; &gt;\r\n				&lt;p class=\'tools\'&gt;&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_big\' class=&quot;ico-pack-up&quot;&gt;收起&lt;/a&gt;&lt;/p&gt;\r\n				&lt;volist name=\'attachInfo\' id=\'vo\'&gt;\r\n					&lt;li title=\'{$vo.attach_url}\'&gt;\r\n					&lt;i class=&quot;check-big&quot;&gt;&lt;a href=\'{$vo.attach_url}\' target=&quot;_blank&quot; class=&quot;ico-show-big&quot; title=&quot;檢視大圖&quot; &gt;&lt;/a&gt;&lt;/i&gt;\r\n					&lt;a href=&quot;javascript:void(0)&quot; event-node=\'img_big\'&gt;&lt;img class=&quot;imgsmall&quot; src=\'{$vo.attach_middle}\' title=\'點選縮小\' &gt;&lt;/a&gt;&lt;/li&gt;\r\n				&lt;/volist&gt;\r\n				&lt;/ul&gt;\r\n				&lt;/div&gt;\r\n			 ]]&gt;\r\n		&lt;/body&gt;\r\n		&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot; delete=&quot;true&quot; /&gt;  ');
INSERT INTO `ts_feed_node` VALUES ('4', 'public', 'postfile', '發附件微博', '&lt;title&gt; \r\n			&lt;![CDATA[\r\n				{$actor}\r\n			]]&gt;\r\n		&lt;/title&gt;\r\n		&lt;body&gt;\r\n			&lt;![CDATA[ \r\n				{$body}\r\n				&lt;div&gt;\r\n					&lt;ul class=&quot;feed_file_list&quot;&gt;\r\n						&lt;volist name=\'attachInfo\' id=\'vo\'&gt;\r\n							&lt;li&gt;&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot; class=&quot;current right&quot; target=&quot;_blank&quot; title=&quot;下載&quot;&gt;&lt;i class=&quot;ico-down&quot;&gt;&lt;/i&gt;&lt;/a&gt;&lt;i class=&quot;ico-{$vo.extension}-small&quot;&gt;&lt;/i&gt;&lt;a href=&quot;{:U(\'widget/Upload/down\',array(\'attach_id\'=&gt;$vo[\'attach_id\']))}&quot;&gt;{$vo.attach_name}&lt;/a&gt; &lt;span class=&quot;tips&quot;&gt;({$vo.size|byte_format})&lt;/span&gt;&lt;/li&gt;\r\n						&lt;/volist&gt;			\r\n						&lt;/ul&gt;\r\n				&lt;/div&gt;\r\n			 ]]&gt;\r\n		&lt;/body&gt;\r\n		&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot;  delete=&quot;true&quot; /&gt;\r\n');
INSERT INTO `ts_feed_node` VALUES ('5', 'weiba', 'weiba_post', '微吧原創', '&lt;title comment=&quot;feed標題&quot;&gt; \r\n			&lt;![CDATA[ {$actor}  ]]&gt;\r\n		&lt;/title&gt;\r\n		&lt;body comment=&quot;feed詳細內容/引用的內容&quot;&gt;\r\n			&lt;![CDATA[ {$body}\r\n&lt;php&gt;if(APP_NAME != \'channel\'){&lt;/php&gt;\r\n&lt;a href=&quot;javascript:void(0)&quot; class=&quot;ico-details&quot; event-node =\'loadPost\' event-args=\'feed_id={$feedid}&amp;post_id={$app_row_id}\' id=&quot;{$app_row_id}&quot;&gt;&lt;i class=&quot;triangle-l&quot;&gt;&lt;/i&gt;檢視全文&lt;/a&gt;&lt;div class=&quot;feed_img_lists&quot; style=&quot;display:none;&quot; id=&quot;post_{$feedid}_{$app_row_id}&quot;&gt;\r\n&lt;/div&gt;\r\n&lt;php&gt;}else{&lt;/php&gt;\r\n&lt;a href=&quot;{:U(\'weiba/Index/postDetail\',array(\'post_id\'=&gt;$app_row_id))}&quot; class=&quot;ico-details&quot; target=&quot;_blank&quot;&gt;&lt;/a&gt;\r\n&lt;php&gt;}&lt;/php&gt;\r\n ]]&gt;\r\n		&lt;/body&gt;\r\n		&lt;feedAttr  comment=&quot;true&quot; repost=&quot;true&quot; favor=&quot;true&quot;  delete=&quot;true&quot; /&gt; ');

-- ----------------------------
-- Table structure for `ts_attach`
-- ----------------------------
DROP TABLE IF EXISTS `ts_attach`;
CREATE TABLE `ts_attach` (
  `attach_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '附件ID',
  `app_name` char(15) DEFAULT 'attach' COMMENT '應用名稱',
  `table` char(15) DEFAULT NULL COMMENT '表名',
  `row_id` int(11) DEFAULT NULL COMMENT '管理的內容ID',
  `attach_type` varchar(20) NOT NULL COMMENT '附件所屬類型',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '使用者UID',
  `ctime` int(11) NOT NULL COMMENT '上傳時間',
  `name` varchar(255) DEFAULT NULL COMMENT '附件名稱',
  `type` varchar(255) DEFAULT NULL COMMENT '附件格式',
  `size` varchar(20) DEFAULT NULL COMMENT '附件大小',
  `extension` varchar(20) DEFAULT NULL COMMENT '附件副檔名',
  `hash` varchar(32) DEFAULT NULL COMMENT '附件雜湊值',
  `private` tinyint(1) DEFAULT '0' COMMENT '是否私有（即對其他人不可見）',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '統一的is_del',
  `save_path` varchar(255) DEFAULT NULL COMMENT '儲存路徑',
  `save_name` varchar(255) DEFAULT NULL COMMENT '儲存名稱',
  `save_domain` tinyint(3) DEFAULT '0' COMMENT '附件儲存的域ID（用於拆分附件存儲到不同的伺服器）',
  `from` tinyint(3) NOT NULL COMMENT '來源類型，0：網站；1：手機網頁版；2：android；3：iphone',
  PRIMARY KEY (`attach_id`),
  KEY `userId` (`uid`)
) ENGINE=MyISAM AUTO_INCREMENT=73 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_attach
-- ----------------------------
INSERT INTO `ts_attach` VALUES ('1', '', '', '0', '', '1', '1358736084', '突出貢獻100.png', 'image/png', '17071', 'png', '5ce3f245de5b835bcc968f94274e1f3b', '0', '0', '2013/0121/10/', '50fcaad40bc68.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('2', '', '', '0', '', '1', '1358736087', '突出貢獻30.png', 'image/png', '3500', 'png', '0284befe8d300742dc3e046869c18823', '0', '0', '2013/0121/10/', '50fcaad74c194.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('3', '', '', '0', '', '1', '1358736140', '感謝有你100.png', 'image/png', '14541', 'png', '0ddbf7479a45e4bddea1721e70b2efdf', '0', '0', '2013/0121/10/', '50fcab0cb9b30.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('4', '', '', '0', '', '1', '1358736143', '感謝有你30.png', 'image/png', '3299', 'png', 'bdbcb9550f92773a9211d818e96104c9', '0', '0', '2013/0121/10/', '50fcab0fee736.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('5', '', '', '0', '', '1', '1358736213', '新年快樂100.png', 'image/png', '18203', 'png', 'c46874625444098eeba858cb56c2a4c4', '0', '0', '2013/0121/10/', '50fcab55c5b49.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('6', '', '', '0', '', '1', '1358736216', '新年快樂30.png', 'image/png', '3607', 'png', '8d1223afda7b4b36569b52753916d92a', '0', '0', '2013/0121/10/', '50fcab58c2985.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('7', '', '', '0', '', '1', '1358736281', '微吧神人100.png', 'image/png', '16008', 'png', '13a76b2cb737199f826cad5a4e9b8bbc', '0', '0', '2013/0121/10/', '50fcab9980cfb.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('8', '', '', '0', '', '1', '1358736284', '微吧神人30.png', 'image/png', '3169', 'png', 'fe0f00fe6604c223796cc3344f6eb84b', '0', '0', '2013/0121/10/', '50fcab9c94476.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('9', '', '', '0', '', '1', '1358736318', '御用發言人100.png', 'image/png', '14853', 'png', '3059ba1ff2a91310b58e57ba56a6c1bf', '0', '0', '2013/0121/10/', '50fcabbe9dae4.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('10', '', '', '0', '', '1', '1358736321', '御用發言人30.png', 'image/png', '3211', 'png', '1aa57937a546ef93f6fd224527734854', '0', '0', '2013/0121/10/', '50fcabc1832a5.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('11', '', '', '0', '', '1', '1358736353', '最焦點100.png', 'image/png', '15900', 'png', 'cdc60d619b8fa9c6b3e2a7546db60052', '0', '0', '2013/0121/10/', '50fcabe10ec23.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('12', '', '', '0', '', '1', '1358736355', '最焦點30.png', 'image/png', '3263', 'png', '79456eecc305b3108676fabf0d1a0268', '0', '0', '2013/0121/10/', '50fcabe3b5ec9.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('13', '', '', '0', '', '1', '1358736391', '操盤手100.png', 'image/png', '16033', 'png', '11bc172ce7a06afd864258f81b93fba6', '0', '0', '2013/0121/10/', '50fcac074d87a.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('14', '', '', '0', '', '1', '1358736394', '操盤手30.png', 'image/png', '3411', 'png', '058ec862e61b1e5aabb3aabd9622fbb6', '0', '0', '2013/0121/10/', '50fcac0a3cc8f.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('15', '', '', '0', '', '1', '1358736429', '微博至尊100.png', 'image/png', '17007', 'png', '2bd3f7d0cf57135713ccdaf53e1377af', '0', '0', '2013/0121/10/', '50fcac2d8c0c1.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('16', '', '', '0', '', '1', '1358736432', '微博至尊30.png', 'image/png', '3258', 'png', 'a851928ea80d2c8e2b1470fff2265611', '0', '0', '2013/0121/10/', '50fcac3064ff3.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('17', '', '', '0', '', '1', '1358736464', '萬人迷100.png', 'image/png', '14775', 'png', 'f8a7b93f9cab6f644538789a59ee1b3a', '0', '0', '2013/0121/10/', '50fcac509f57a.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('18', '', '', '0', '', '1', '1358736467', '萬人迷30.png', 'image/png', '3174', 'png', 'b907a256e6d9d4475e4fbf3abac1d944', '0', '0', '2013/0121/10/', '50fcac539dd7d.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('19', '', '', '0', '', '1', '1358736508', '簽到神人100.png', 'image/png', '16181', 'png', '391065bbf4d1a8257f6e86581524b7b8', '0', '0', '2013/0121/10/', '50fcac7c7d014.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('20', '', '', '0', '', '1', '1358736511', '簽到神人30.png', 'image/png', '3209', 'png', 'f48bdb2546a94cdb20e56c10d70452e7', '0', '0', '2013/0121/10/', '50fcac7fce2cf.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('21', '', '', '0', '', '1', '1358736553', '微吧牛人100.png', 'image/png', '16012', 'png', '4223b3a4693af7ad8a2691722756338c', '0', '0', '2013/0121/10/', '50fcaca99174e.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('22', '', '', '0', '', '1', '1358736556', '微吧牛人30.png', 'image/png', '3285', 'png', 'd4550944cc8e6dff0585f85092c7bfc0', '0', '0', '2013/0121/10/', '50fcacac7ffec.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('23', '', '', '0', '', '1', '1358736593', '頻道勞模100.png', 'image/png', '14537', 'png', '035db6c56a4066fd5e0b21101e1da23d', '0', '0', '2013/0121/10/', '50fcacd15426a.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('24', '', '', '0', '', '1', '1358736596', '頻道勞模30.png', 'image/png', '3292', 'png', '87636309060c3a11c251642fc414bc96', '0', '0', '2013/0121/10/', '50fcacd4a8dd4.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('25', '', '', '0', '', '1', '1358736629', '焦點人物100.png', 'image/png', '16549', 'png', '65962b61dcefd39e9b97d97480a20ab4', '0', '0', '2013/0121/10/', '50fcacf5dc2af.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('26', '', '', '0', '', '1', '1358736633', '焦點人物30.png', 'image/png', '3253', 'png', '5630a1742c187845880de2be825ccde4', '0', '0', '2013/0121/10/', '50fcacf9323bc.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('27', '', '', '0', '', '1', '1358736667', '意見領袖100.png', 'image/png', '14177', 'png', '35ff66e290911c1cc8c71c7bfc175996', '0', '0', '2013/0121/10/', '50fcad1be6612.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('28', '', '', '0', '', '1', '1358736673', '意見領袖30.png', 'image/png', '3018', 'png', '1a4dede1dfbbb3caad9092def6a1db63', '0', '0', '2013/0121/10/', '50fcad21073db.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('29', '', '', '0', '', '1', '1358736705', '微名遠揚100.png', 'image/png', '14394', 'png', '477cbdf2d7c33e3f45cdda8238f0e8cf', '0', '0', '2013/0121/10/', '50fcad41d21e4.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('30', '', '', '0', '', '1', '1358736710', '微名遠揚30.png', 'image/png', '3173', 'png', 'b574768536b539c1f6054f59099b8e01', '0', '0', '2013/0121/10/', '50fcad466e156.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('31', '', '', '0', '', '1', '1358736732', '微名遠揚100.png', 'image/png', '14394', 'png', '477cbdf2d7c33e3f45cdda8238f0e8cf', '0', '0', '2013/0121/10/', '50fcad5c83400.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('32', '', '', '0', '', '1', '1358736736', '微名遠揚30.png', 'image/png', '3173', 'png', 'b574768536b539c1f6054f59099b8e01', '0', '0', '2013/0121/10/', '50fcad60d607d.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('33', '', '', '0', '', '1', '1358736755', '頻道勞模100.png', 'image/png', '14537', 'png', '035db6c56a4066fd5e0b21101e1da23d', '0', '0', '2013/0121/10/', '50fcad73071a7.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('34', '', '', '0', '', '1', '1358736758', '頻道勞模30.png', 'image/png', '3292', 'png', '87636309060c3a11c251642fc414bc96', '0', '0', '2013/0121/10/', '50fcad7653b3e.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('35', '', '', '0', '', '1', '1358736832', '簽到牛人100.png', 'image/png', '15821', 'png', '4b841ba7db505c7cc82f8d1295fa99c6', '0', '0', '2013/0121/10/', '50fcadc03ac71.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('36', '', '', '0', '', '1', '1358736835', '簽到牛人30.png', 'image/png', '3247', 'png', '02e276ded1db6fd68893964368f755c5', '0', '0', '2013/0121/10/', '50fcadc31f48c.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('37', '', '', '0', '', '1', '1358736922', '微博勞模100.png', 'image/png', '14985', 'png', 'c47947f11a9165fb8dae7c21eebbedf6', '0', '0', '2013/0121/10/', '50fcae1a844aa.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('38', '', '', '0', '', '1', '1358736928', '微博勞模30.png', 'image/png', '3018', 'png', '1a4dede1dfbbb3caad9092def6a1db63', '0', '0', '2013/0121/10/', '50fcae2007d83.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('39', '', '', '0', '', '1', '1358736962', '微吧達人100.png', 'image/png', '15872', 'png', '3bbac1df642fceeb9c6dc88b4f73fdf8', '0', '0', '2013/0121/10/', '50fcae42c6f85.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('40', '', '', '0', '', '1', '1358736965', '微吧達人30.png', 'image/png', '3245', 'png', '55eb39468fe298b439e3450a8999a343', '0', '0', '2013/0121/10/', '50fcae45c50bc.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('41', '', '', '0', '', '1', '1358736996', '頻道先鋒100.png', 'image/png', '14479', 'png', '1b766f225396182bd1b627313a0ba603', '0', '0', '2013/0121/10/', '50fcae64a3461.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('42', '', '', '0', '', '1', '1358736999', '頻道先鋒30.png', 'image/png', '3284', 'png', '32be175f35a8264728010dc01f94dccc', '0', '0', '2013/0121/10/', '50fcae675c040.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('43', '', '', '0', '', '1', '1358737031', '微博控100.png', 'image/png', '13810', 'png', 'bef6b7df24bdfd4f2b1426812a360473', '0', '0', '2013/0121/10/', '50fcae87315e3.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('44', '', '', '0', '', '1', '1358737036', '微博控30.png', 'image/png', '3062', 'png', '48868feefe614e555addc7f23b6f5ff6', '0', '0', '2013/0121/10/', '50fcae8c2c0d9.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('45', '', '', '0', '', '1', '1358737078', '最佳人緣100.png', 'image/png', '17214', 'png', '1ffc50eb528935c6c4f28b1864756832', '0', '0', '2013/0121/10/', '50fcaeb6a33e1.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('46', '', '', '0', '', '1', '1358737083', '最佳人緣30.png', 'image/png', '3378', 'png', '2b4c8d9ab1508a3354bcd763190f56f1', '0', '0', '2013/0121/10/', '50fcaebb8beb5.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('47', '', '', '0', '', '1', '1358737144', '小有名氣100.png', 'image/png', '14564', 'png', '6fcff8fb4aa470e0732cf4e80c866b93', '0', '0', '2013/0121/10/', '50fcaef801623.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('48', '', '', '0', '', '1', '1358737148', '小有名氣30.png', 'image/png', '3114', 'png', '41e255fea2079f4bfc41d16901d40727', '0', '0', '2013/0121/10/', '50fcaefc44e85.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('49', '', '', '0', '', '1', '1358737186', '簽到達人100.png', 'image/png', '15755', 'png', '0eb8888c1546f506d42ae950cbdcb850', '0', '0', '2013/0121/10/', '50fcaf2264740.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('50', '', '', '0', '', '1', '1358737189', '簽到達人30.png', 'image/png', '3218', 'png', '4570359d04aed10ecd4ab716518732ca', '0', '0', '2013/0121/10/', '50fcaf256df86.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('51', '', '', '0', '', '1', '1358737227', '微吧先鋒100.png', 'image/png', '15568', 'png', 'dbfd7610b788ae6141667d32be594cc4', '0', '0', '2013/0121/11/', '50fcaf4b75701.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('52', '', '', '0', '', '1', '1358737232', '微吧先鋒30.png', 'image/png', '3186', 'png', '214c81d43dfaf28f516f36fe3ee0aa16', '0', '0', '2013/0121/11/', '50fcaf50bb581.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('53', '', '', '0', '', '1', '1358737265', '魅力初現100.png', 'image/png', '14308', 'png', 'd72e21505f62770e754d762901e49164', '0', '0', '2013/0121/11/', '50fcaf710b89e.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('54', '', '', '0', '', '1', '1358737268', '魅力初現30.png', 'image/png', '3115', 'png', 'a15347ae308354318366915b8e571ae1', '0', '0', '2013/0121/11/', '50fcaf740bcd8.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('55', '', '', '0', '', '1', '1358737310', '我愛簽到100.png', 'image/png', '15931', 'png', '010d94c1a424e72c0dc0d1793fef5915', '0', '0', '2013/0121/11/', '50fcaf9e58432.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('56', '', '', '0', '', '1', '1358737314', '我愛簽到30.png', 'image/png', '3233', 'png', '916a7b33f565af066749d3d082823ea1', '0', '0', '2013/0121/11/', '50fcafa273e94.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('57', '', '', '0', '', '1', '1358737347', '微博先鋒100.png', 'image/png', '16929', 'png', 'fb34eb7451f41acbf072d4f0e398a3ff', '0', '0', '2013/0121/11/', '50fcafc30a9bc.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('58', '', '', '0', '', '1', '1358737351', '微博先鋒30.png', 'image/png', '3070', 'png', '82623f339c1ad7dde568fa37cc16849a', '0', '0', '2013/0121/11/', '50fcafc7153df.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('59', '', '', '0', '', '1', '1358737391', '簽到先鋒100.png', 'image/png', '15699', 'png', '6f2ac6156305508577bea2c8830fd04e', '0', '0', '2013/0121/11/', '50fcafefac4bd.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('60', '', '', '0', '', '1', '1358737397', '簽到先鋒30.png', 'image/png', '3239', 'png', '018904fe547c25491aa24ec86d948cd7', '0', '0', '2013/0121/11/', '50fcaff50a604.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('61', '', '', '0', '', '1', '1358737436', '有頭有臉100.png', 'image/png', '13781', 'png', '3efd604658256fd551379b21b286149e', '0', '0', '2013/0121/11/', '50fcb01ce9d19.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('62', '', '', '0', '', '1', '1358737440', '有頭有臉30.png', 'image/png', '3075', 'png', '1156bd22a8a1cc2b731f63967dc0202c', '0', '0', '2013/0121/11/', '50fcb020af27a.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('63', '', '', '0', '', '1', '1358737474', '獨孤求敗100.png', 'image/png', '16835', 'png', 'cf9fb5e52b7e2c274b24d9b562018c39', '0', '0', '2013/0121/11/', '50fcb0426d7df.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('64', '', '', '0', '', '1', '1358737477', '獨孤求敗30.png', 'image/png', '3222', 'png', '7af7e22e00525b2f06c1230cf8f18c59', '0', '0', '2013/0121/11/', '50fcb04563a81.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('65', '', '', '0', '', '1', '1358737506', '武林高手100.png', 'image/png', '17209', 'png', '1f9cea9b8c4c574ce557807b2d8fb935', '0', '0', '2013/0121/11/', '50fcb062ed874.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('66', '', '', '0', '', '1', '1358737511', '武林高手30.png', 'image/png', '3302', 'png', 'd0ed0a36b65371a951812c02d9a17f02', '0', '0', '2013/0121/11/', '50fcb0670fb6e.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('67', '', '', '0', '', '1', '1358737547', '微博達人100.png', 'image/png', '14966', 'png', 'd16dc4877a6a4f3ec62ac4f93a421769', '0', '0', '2013/0121/11/', '50fcb08b08634.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('68', '', '', '0', '', '1', '1358737551', '微博達人30.png', 'image/png', '3217', 'png', 'afb9b889dc85b7b46aef6166d2401446', '0', '0', '2013/0121/11/', '50fcb08f3d1e4.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('69', '', '', '0', '', '1', '1358737602', '小有進步100.png', 'image/png', '14843', 'png', '5d852275a079b320bab5441120a8152e', '0', '0', '2013/0121/11/', '50fcb0c2a4fa2.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('70', '', '', '0', '', '1', '1358737607', '小有進步30.png', 'image/png', '3261', 'png', 'faff46a317353cd6aa1fd3d1fc372a2e', '0', '0', '2013/0121/11/', '50fcb0c77c4b6.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('71', '', '', '0', '', '1', '1358737622', '新手上路100.png', 'image/png', '14881', 'png', '02d326dd1218002916a8d87e524aa6c7', '0', '0', '2013/0121/11/', '50fcb0d64a404.png', '0', '0');
INSERT INTO `ts_attach` VALUES ('72', '', '', '0', '', '1', '1358737626', '新手上路30.png', 'image/png', '3139', 'png', '85196d60385399a4e985c46e5dd495e5', '0', '0', '2013/0121/11/', '50fcb0da765d8.png', '0', '0');

-- ----------------------------
-- Table structure for `ts_task`
-- ----------------------------
DROP TABLE IF EXISTS `ts_task`;
CREATE TABLE `ts_task` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_level` int(11) DEFAULT NULL COMMENT '任務等級',
  `task_name` varchar(255) DEFAULT NULL COMMENT '任務名稱',
  `task_type` int(11) DEFAULT NULL COMMENT '任務類型',
  `step_name` varchar(255) DEFAULT NULL COMMENT '任務步驟名稱',
  `step_desc` varchar(500) DEFAULT NULL COMMENT '任務步驟說明',
  `condition` varchar(255) DEFAULT NULL COMMENT '任務條件',
  `action` varchar(255) DEFAULT NULL COMMENT '動作',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  `reward` varchar(255) DEFAULT NULL COMMENT '任務獎勵',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=54 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_task
-- ----------------------------
INSERT INTO `ts_task` VALUES ('1', '1', '每日任務', '1', '釋出1條原創微博', '在我的首頁釋出1條原創微博', '{\"weibopost\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('2', '1', '每日任務', '1', '轉發1條微博', '在我的首頁轉發1條他人的微博', '{\"weiborepost\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('3', '1', '每日任務', '1', '評論1條微博', '在我的首頁評論1條他人的微博', '{\"weibocomment\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('4', '1', '新手任務', '2', '上傳頭像', '在帳號-設定-頭像設定裡上傳頭像', '{\"uploadface\":1}', '', null, '{\"exp\":2,\"score\":2,\"medal\":{\"id\":75,\"name\":\"\\u6709\\u5934\\u6709\\u8138\",\"src\":\"2013\\/0121\\/11\\/50fcb01ce9d19.png\"}}');
INSERT INTO `ts_task` VALUES ('5', '1', '新手任務', '2', '轉發1條微博', '在微博列表中轉發1條微博', '{\"weiborepost\":1}', '', null, '{\"exp\":2,\"score\":2,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('6', '1', '新手任務', '2', '評論1條微博', '在微博列表中評論1條微博', '{\"weibocomment\":1}', '', null, '{\"exp\":2,\"score\":2,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('8', '2', '進階任務', '2', '擁有30個以上的粉絲', '將自己的粉絲數擴充到30個以上', '{\"following\":30}', '', null, '{\"exp\":5,\"score\":5,\"medal\":{\"id\":79,\"name\":\"\\u9b45\\u529b\\u521d\\u73b0\",\"src\":\"2013\\/0121\\/11\\/50fcaf710b89e.png\"}}');
INSERT INTO `ts_task` VALUES ('10', '1', '每日任務', '1', '簽到1次', '在我的首頁中簽到1次', '{\"checkin\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":{\"id\":76,\"name\":\"\\u7b7e\\u5230\\u5148\\u950b\",\"src\":\"2013\\/0121\\/11\\/50fcafefac4bd.png\"}}');
INSERT INTO `ts_task` VALUES ('11', '3', '達人任務', '2', '釋出100條以上的原創微博', '釋出100條以上的原創微博', '{\"weibopost\":100}', '', null, '{\"exp\":10,\"score\":10,\"medal\":{\"id\":84,\"name\":\"\\u5fae\\u535a\\u63a7\",\"src\":\"2013\\/0121\\/10\\/50fcae87315e3.png\"}}');
INSERT INTO `ts_task` VALUES ('12', '3', '達人任務', '2', '擁有100個以上的粉絲', '擁有100個以上的粉絲', '{\"following\":100}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":82,\"name\":\"\\u5c0f\\u6709\\u540d\\u6c14\",\"src\":\"2013\\/0121\\/10\\/50fcaef801623.png\"}}');
INSERT INTO `ts_task` VALUES ('13', '4', '高手任務', '2', '釋出1000條以上原創微博', '釋出1000條以上原創微博', '{\"weiborepost\":1000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":89,\"name\":\"\\u5fae\\u535a\\u52b3\\u6a21\",\"src\":\"2013\\/0121\\/10\\/50fcae1a844aa.png\"}}');
INSERT INTO `ts_task` VALUES ('14', '4', '高手任務', '2', '擁有1000個以上的粉絲', '擁有1000個以上的粉絲', '{\"following\":1000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":88,\"name\":\"\\u5fae\\u540d\\u8fdc\\u626c\",\"src\":\"2013\\/0121\\/10\\/50fcad5c83400.png\"}}');
INSERT INTO `ts_task` VALUES ('15', '5', '終極任務', '2', '擁有10000個以上的粉絲', '擁有10000個以上的粉絲', '{\"following\":10000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":95,\"name\":\"\\u4e07\\u4eba\\u8ff7\",\"src\":\"2013\\/0121\\/10\\/50fcac509f57a.png\"}}');
INSERT INTO `ts_task` VALUES ('16', '5', '終極任務', '2', '釋出10000以上的原創微博', '釋出10000以上的原創微博', '{\"weiborepost\":10000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":96,\"name\":\"\\u5fae\\u535a\\u81f3\\u5c0a\",\"src\":\"2013\\/0121\\/10\\/50fcac2d8c0c1.png\"}}');
INSERT INTO `ts_task` VALUES ('17', '1', '新手任務', '2', '完善個人資料', '在帳號-設定-基本資訊裡完善你的個人資料', '{\"userinfo\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('18', '1', '新手任務', '2', '關注1個感興趣的人', '在可能感興趣的人或者找人裡關注1個感興趣的人', '{\"followinterest\":1}', '', null, '{\"exp\":2,\"score\":2,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('19', '1', '新手任務', '2', '釋出1條微博並@提到你的好友', '釋出1條原創微博並@提到你的好友', '{\"weibotofriend\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":{\"id\":77,\"name\":\"\\u5fae\\u535a\\u5148\\u950b\",\"src\":\"2013\\/0121\\/11\\/50fcafc30a9bc.png\"}}');
INSERT INTO `ts_task` VALUES ('20', '2', '進階任務', '2', '連續簽到3次', '連續簽到3次以上', '{\"checkin\":3}', '', null, '{\"exp\":5,\"score\":5,\"medal\":{\"id\":78,\"name\":\"\\u6211\\u7231\\u7b7e\\u5230\",\"src\":\"2013\\/0121\\/11\\/50fcaf9e58432.png\"}}');
INSERT INTO `ts_task` VALUES ('21', '2', '進階任務', '2', '使用者等級T2以上', '僅限使用者等級T2以上的使用者領取', '{\"userlevel\":2}', '', null, '{\"exp\":3,\"score\":3,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('22', '2', '進階任務', '2', '微博被轉發5次以上', '全部微博總共被轉發5次以上', '{\"weibotranspost\":5}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('23', '2', '進階任務', '2', '關注1個微吧', '關注1個微吧', '{\"weibafollow\":1}', '', null, '{\"exp\":5,\"score\":5,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('24', '2', '進階任務', '2', '在微吧發表1篇帖子', '在微吧發表1篇帖子', '{\"weibapost\":1}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":80,\"name\":\"\\u5fae\\u5427\\u5148\\u950b\",\"src\":\"2013\\/0121\\/11\\/50fcaf4b75701.png\"}}');
INSERT INTO `ts_task` VALUES ('29', '3', '達人任務', '2', '連續簽到20次', '連續簽到20次以上，連續簽到非累計簽到次數', '{\"checkin\":20}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":81,\"name\":\"\\u7b7e\\u5230\\u8fbe\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcaf2264740.png\"}}');
INSERT INTO `ts_task` VALUES ('30', '3', '達人任務', '2', '使用者等級T4以上', '僅限使用者等級T4以上的使用者領取', '{\"userlevel\":4}', '', null, '{\"exp\":1,\"score\":1,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('31', '3', '達人任務', '2', '與30個以上的使用者互相關注', '與30個以上的使用者互相關注', '{\"followmutual\":30}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":83,\"name\":\"\\u6700\\u4f73\\u4eba\\u7f18\",\"src\":\"2013\\/0121\\/10\\/50fcaeb6a33e1.png\"}}');
INSERT INTO `ts_task` VALUES ('32', '3', '達人任務', '2', '微博被轉發15次以上', '全部微博總共被轉發15次以上', '{\"weibotranspost\":15}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('33', '3', '達人任務', '2', '收到15條以上的微博評論', '收到15條以上的微博評論', '{\"weiboreceivecomment\":15}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('34', '3', '達人任務', '2', '向頻道投稿並收錄2條以上', '向頻道投稿並至少有2條收錄到頻道', '{\"channelcontribute\":2}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":85,\"name\":\"\\u9891\\u9053\\u5148\\u950b\",\"src\":\"2013\\/0121\\/10\\/50fcae64a3461.png\"}}');
INSERT INTO `ts_task` VALUES ('35', '3', '達人任務', '2', '至少有1篇精華帖子', '在微吧中至少有1篇帖子被管理員設定為精華帖', '{\"weibamarrow\":1}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":86,\"name\":\"\\u5fae\\u5427\\u8fbe\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcae42c6f85.png\"}}');
INSERT INTO `ts_task` VALUES ('37', '4', '高手任務', '2', '連續簽到130次以上', '連續簽到130次以上', '{\"checkin\":130}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":87,\"name\":\"\\u7b7e\\u5230\\u725b\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcadc03ac71.png\"}}');
INSERT INTO `ts_task` VALUES ('38', '4', '高手任務', '2', '使用者等級T6以上', '僅限使用者等級T6以上的使用者領取', '{\"userlevel\":6}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('39', '4', '高手任務', '2', '通過個人認證', '僅限個人認證使用者領取', '{\"manager\":5}', '', null, '{\"exp\":1,\"score\":1,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('40', '4', '高手任務', '2', '全部微博共計被轉發100次以上', '全部微博共計被轉發100次以上', '{\"weiboonetranspost\":100}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":90,\"name\":\"\\u610f\\u89c1\\u9886\\u8896\",\"src\":\"2013\\/0121\\/10\\/50fcad1be6612.png\"}}');
INSERT INTO `ts_task` VALUES ('41', '4', '高手任務', '2', '全部微博被評論100次以上', '全部微博被評論100次以上', '{\"_empty_\":100}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":91,\"name\":\"\\u7126\\u70b9\\u4eba\\u7269\",\"src\":\"2013\\/0121\\/10\\/50fcacf5dc2af.png\"}}');
INSERT INTO `ts_task` VALUES ('42', '4', '高手任務', '2', '向頻道投稿有100條以上被收錄', '向頻道投稿並有100條以上收錄到頻道', '{\"channelcontribute\":100}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":92,\"name\":\"\\u9891\\u9053\\u52b3\\u6a21\",\"src\":\"2013\\/0121\\/10\\/50fcacd15426a.png\"}}');
INSERT INTO `ts_task` VALUES ('43', '4', '高手任務', '2', '至少有10篇精華帖子', '在微吧發表10篇以上的精華帖子', '{\"weibamarrow\":10}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":93,\"name\":\"\\u5fae\\u5427\\u725b\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcaca99174e.png\"}}');
INSERT INTO `ts_task` VALUES ('46', '5', '終極任務', '2', '連續簽到365天以上', '連續簽到365天以上', '{\"checkin\":365}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":94,\"name\":\"\\u7b7e\\u5230\\u795e\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcac7c7d014.png\"}}');
INSERT INTO `ts_task` VALUES ('47', '5', '終極任務', '2', '使用者等級T9以上', '僅限使用者等級T9以上的使用者領取', '{\"userlevel\":9}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('48', '5', '終極任務', '2', '通過個人認證', '僅限個人認證使用者領取', '{\"manager\":5}', '', null, '{\"exp\":6,\"score\":6,\"medal\":null}');
INSERT INTO `ts_task` VALUES ('49', '5', '終極任務', '2', '單條微博被轉發1000次', '單條微博被轉發1000次', '{\"weiboonetranspost\":1000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":97,\"name\":\"\\u64cd\\u76d8\\u624b\",\"src\":\"2013\\/0121\\/10\\/50fcac074d87a.png\"}}');
INSERT INTO `ts_task` VALUES ('50', '5', '終極任務', '2', '單條微博被評論1000次', '單條微博被評論1000次', '{\"weiboonecomment\":1000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":98,\"name\":\"\\u6700\\u7126\\u70b9\",\"src\":\"2013\\/0121\\/10\\/50fcabe10ec23.png\"}}');
INSERT INTO `ts_task` VALUES ('51', '5', '終極任務', '2', '向頻道投稿被收錄1000條以上', '向頻道投稿並有1000條以上收錄到頻道', '{\"channelcontribute\":1000}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":99,\"name\":\"\\u5fa1\\u7528\\u53d1\\u8a00\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcabbe9dae4.png\"}}');
INSERT INTO `ts_task` VALUES ('53', '5', '終極任務', '2', '發表100篇以上的精華帖子', '在微吧發表100篇以上的精華帖子', '{\"weibamarrow\":100}', '', null, '{\"exp\":6,\"score\":6,\"medal\":{\"id\":100,\"name\":\"\\u5fae\\u5427\\u795e\\u4eba\",\"src\":\"2013\\/0121\\/10\\/50fcab9980cfb.png\"}}');

-- ----------------------------
-- Table structure for `ts_medal`
-- ----------------------------
DROP TABLE IF EXISTS `ts_medal`;
CREATE TABLE `ts_medal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '勳章名稱',
  `desc` varchar(255) DEFAULT NULL COMMENT '勳章描述',
  `src` varchar(255) NOT NULL COMMENT '大圖地址',
  `small_src` varchar(255) DEFAULT NULL COMMENT '小圖地址',
  `type` int(11) DEFAULT NULL COMMENT '勳章類型',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=104 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_medal
-- ----------------------------
INSERT INTO `ts_medal` VALUES ('81', '簽到達人', '連續簽到20次以上後獲得的獎勵勳章', '49|2013/0121/10/50fcaf2264740.png', '50|2013/0121/10/50fcaf256df86.png', null);
INSERT INTO `ts_medal` VALUES ('72', '微博達人', '完成達人任務後獲得的獎勵勳章', '67|2013/0121/11/50fcb08b08634.png', '68|2013/0121/11/50fcb08f3d1e4.png', null);
INSERT INTO `ts_medal` VALUES ('71', '小有進步', '完成進階任務後獲得的獎勵勳章', '69|2013/0121/11/50fcb0c2a4fa2.png', '70|2013/0121/11/50fcb0c77c4b6.png', null);
INSERT INTO `ts_medal` VALUES ('70', '新手上路', '完成新手任務後獲得的獎勵勳章', '71|2013/0121/11/50fcb0d64a404.png', '72|2013/0121/11/50fcb0da765d8.png', null);
INSERT INTO `ts_medal` VALUES ('89', '微博勞模', '發表1000條以上原創微博的使用者獲得的獎勵勳章', '37|2013/0121/10/50fcae1a844aa.png', '38|2013/0121/10/50fcae2007d83.png', null);
INSERT INTO `ts_medal` VALUES ('88', '微名遠揚', '擁有1000個以上粉絲的使用者獲得的獎勵勳章', '31|2013/0121/10/50fcad5c83400.png', '32|2013/0121/10/50fcad60d607d.png', null);
INSERT INTO `ts_medal` VALUES ('87', '簽到牛人', '連續簽到130次以上獲得的獎勵勳章', '35|2013/0121/10/50fcadc03ac71.png', '36|2013/0121/10/50fcadc31f48c.png', null);
INSERT INTO `ts_medal` VALUES ('86', '微吧達人', '在微吧有1篇以上的精華帖子獲得的勳章獎勵', '39|2013/0121/10/50fcae42c6f85.png', '40|2013/0121/10/50fcae45c50bc.png', null);
INSERT INTO `ts_medal` VALUES ('85', '頻道先鋒', '向頻道投稿並有2條以上被收錄', '41|2013/0121/10/50fcae64a3461.png', '42|2013/0121/10/50fcae675c040.png', null);
INSERT INTO `ts_medal` VALUES ('84', '微博控', '發表原創微博超過100條使用者獲得的獎勵勳章', '43|2013/0121/10/50fcae87315e3.png', '44|2013/0121/10/50fcae8c2c0d9.png', null);
INSERT INTO `ts_medal` VALUES ('83', '最佳人緣', '與30以上的使用者互相關注獲得的獎勵勳章', '45|2013/0121/10/50fcaeb6a33e1.png', '46|2013/0121/10/50fcaebb8beb5.png', null);
INSERT INTO `ts_medal` VALUES ('82', '小有名氣', '擁有100個以上粉絲的使用者獲得的獎勵勳章', '47|2013/0121/10/50fcaef801623.png', '48|2013/0121/10/50fcaefc44e85.png', null);
INSERT INTO `ts_medal` VALUES ('95', '萬人迷', '擁有10000個以上粉絲的使用者獲得的獎勵勳章', '17|2013/0121/10/50fcac509f57a.png', '18|2013/0121/10/50fcac539dd7d.png', null);
INSERT INTO `ts_medal` VALUES ('94', '簽到神人', '連續簽到365次以上獲得的獎勵勳章', '19|2013/0121/10/50fcac7c7d014.png', '20|2013/0121/10/50fcac7fce2cf.png', null);
INSERT INTO `ts_medal` VALUES ('93', '微吧牛人', '在微吧至少有10篇以上的精華帖子使用者獲得獎勵勳章', '21|2013/0121/10/50fcaca99174e.png', '22|2013/0121/10/50fcacac7ffec.png', null);
INSERT INTO `ts_medal` VALUES ('92', '頻道勞模', '向頻道投稿並有100條以上的微博被收錄', '23|2013/0121/10/50fcacd15426a.png', '24|2013/0121/10/50fcacd4a8dd4.png', null);
INSERT INTO `ts_medal` VALUES ('78', '我愛簽到', '連續簽到3次以上獲得的獎勵勳章', '55|2013/0121/11/50fcaf9e58432.png', '56|2013/0121/11/50fcafa273e94.png', null);
INSERT INTO `ts_medal` VALUES ('79', '魅力初現', '擁有30個以上粉絲的使用者獲得的獎勵勳章', '53|2013/0121/11/50fcaf710b89e.png', '54|2013/0121/11/50fcaf740bcd8.png', null);
INSERT INTO `ts_medal` VALUES ('80', '微吧先鋒', '在微吧發表1篇帖子後獲得的獎勵勳章', '51|2013/0121/11/50fcaf4b75701.png', '52|2013/0121/11/50fcaf50bb581.png', null);
INSERT INTO `ts_medal` VALUES ('91', '焦點人物', '單條微博被評論100次以上的使用者獲得的獎勵勳章', '25|2013/0121/10/50fcacf5dc2af.png', '26|2013/0121/10/50fcacf9323bc.png', null);
INSERT INTO `ts_medal` VALUES ('77', '微博先鋒', '首次釋出微博並@提到好友後獲得的獎勵勳章', '57|2013/0121/11/50fcafc30a9bc.png', '58|2013/0121/11/50fcafc7153df.png', null);
INSERT INTO `ts_medal` VALUES ('90', '意見領袖', '單條微博被轉發100次以上的使用者獲得的獎勵勳章', '27|2013/0121/10/50fcad1be6612.png', '28|2013/0121/10/50fcad21073db.png', null);
INSERT INTO `ts_medal` VALUES ('73', '武林高手', '完成高手任務後獲得的獎勵勳章', '65|2013/0121/11/50fcb062ed874.png', '66|2013/0121/11/50fcb0670fb6e.png', null);
INSERT INTO `ts_medal` VALUES ('74', '獨孤求敗', '完成終極任務後獲得的獎勵勳章', '63|2013/0121/11/50fcb0426d7df.png', '64|2013/0121/11/50fcb04563a81.png', null);
INSERT INTO `ts_medal` VALUES ('75', '有頭有臉', '上傳頭像後獲得的獎勵勳章', '61|2013/0121/11/50fcb01ce9d19.png', '62|2013/0121/11/50fcb020af27a.png', null);
INSERT INTO `ts_medal` VALUES ('76', '簽到先鋒', '首次簽到後獲得的獎勵勳章', '59|2013/0121/11/50fcafefac4bd.png', '60|2013/0121/11/50fcaff50a604.png', null);
INSERT INTO `ts_medal` VALUES ('96', '微博至尊', '發表原創微博超過100000條使用者獲得的獎勵勳章', '15|2013/0121/10/50fcac2d8c0c1.png', '16|2013/0121/10/50fcac3064ff3.png', null);
INSERT INTO `ts_medal` VALUES ('97', '操盤手', '單條微博被轉發1000次以上的使用者獲得的獎勵勳章', '13|2013/0121/10/50fcac074d87a.png', '14|2013/0121/10/50fcac0a3cc8f.png', null);
INSERT INTO `ts_medal` VALUES ('98', '最焦點', '單條微博被評論1000次以上的使用者獲得的獎勵勳章', '11|2013/0121/10/50fcabe10ec23.png', '12|2013/0121/10/50fcabe3b5ec9.png', null);
INSERT INTO `ts_medal` VALUES ('99', '御用發言人', '向頻道投稿並有1000條以上的微博被收錄', '9|2013/0121/10/50fcabbe9dae4.png', '10|2013/0121/10/50fcabc1832a5.png', null);
INSERT INTO `ts_medal` VALUES ('100', '微吧神人', '在微吧至少有100篇以上的精華帖子使用者獲得獎勵勳章', '7|2013/0121/10/50fcab9980cfb.png', '8|2013/0121/10/50fcab9c94476.png', null);
INSERT INTO `ts_medal` VALUES ('101', '新年快樂', '限時發放“新年快樂”勳章，限量100枚', '5|2013/0121/10/50fcab55c5b49.png', '6|2013/0121/10/50fcab58c2985.png', '3');
INSERT INTO `ts_medal` VALUES ('102', '感謝有你', '感謝所有ThinkSNS使用者四年的相伴', '3|2013/0121/10/50fcab0cb9b30.png', '4|2013/0121/10/50fcab0fee736.png', '3');
INSERT INTO `ts_medal` VALUES ('103', '突出貢獻', '特別頒發給為ThinkSNS的發展做出了突出貢獻的使用者', '1|2013/0121/10/50fcaad40bc68.png', '2|2013/0121/10/50fcaad74c194.png', null);

-- ----------------------------
-- Table structure for `ts_notify_node`
-- ----------------------------
DROP TABLE IF EXISTS `ts_notify_node`;
CREATE TABLE `ts_notify_node` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `node` varchar(50) NOT NULL COMMENT '節點名稱',
  `nodeinfo` varchar(50) NOT NULL COMMENT '節點描述',
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `content_key` varchar(50) NOT NULL COMMENT '內容key',
  `title_key` varchar(50) NOT NULL COMMENT '標題key',
  `send_email` tinyint(2) NOT NULL COMMENT '是否發送郵件',
  `send_message` tinyint(2) NOT NULL COMMENT '是否發送訊息',
  `type` tinyint(2) NOT NULL COMMENT '資訊類型：1 表示使用者發送的 2表示是系統發送的',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=57 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_notify_node
-- ----------------------------
INSERT INTO `ts_notify_node` VALUES ('1', 'register_active', '註冊啟用', 'public', 'NOTIFY_REGISTER_ACTIVE_CONTENT', 'NOTIFY_REGISTER_ACTIVE_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('30', 'register_audit', '註冊稽覈', 'public', 'NOTIFY_REGISTER_AUDIT_CONTENT', 'NOTIFY_REGISTER_AUDIT_TITLE', '1', '0', '2');
INSERT INTO `ts_notify_node` VALUES ('31', 'verify_audit', '認證稽覈', 'public', 'NOTIFY_VERIFY_AUDIT_CONTENT', 'NOTIFY_VERIFY_AUDIT_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('32', 'denouce_audit', '舉報稽覈', 'public', 'NOTIFY_DENOUCE_AUDIT_CONTENT', 'NOTIFY_DENOUCE_AUDIT_TITLE', '1', '0', '2');
INSERT INTO `ts_notify_node` VALUES ('3', 'audit_ok', '通過稽覈', 'public', 'NOTIFY_AUDIT_OK_CONTENT', 'NOTIFY_AUDIT_OK_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('4', 'password_reset', '密碼重置', 'public', 'NOTIFY_PASSWORD_RESET_CONTENT', 'NOTIFY_PASSWORD_RESET_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('5', 'password_setok', '密碼重置成功', 'public', 'NOTIFY_PASSWORD_SETOK_CONTENT', 'NOTIFY_PASSWORD_SETOK_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('6', 'user_lock', '帳號鎖定', 'public', 'NOTIFY_USER_LOCK_CONTENT', 'NOTIFY_USER_LOCK_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('7', 'atme', '提到我的', 'public', 'NOTIFY_ATME_CONTENT', 'NOTIFY_ATME_TITLE', '0', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('8', 'comment', '評論我的', 'public', 'NOTIFY_COMMENT_CONTENT', 'NOTIFY_COMMENT_TITLE', '0', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('9', 'new_message', '收到私信', 'public', 'NOTIFY_NEW_MESSAGE_CONTENT', 'NOTIFY_NEW_MESSAGE_TITLE', '0', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('11', 'register_invate', '邀請註冊', 'public', 'NOTIFY_REGISTER_INVATE_CONTENT', 'NOTIFY_REGISTER_INVATE_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('12', 'register_invate_ok', '邀請註冊成功', 'public', 'NOTIFY_REGISTER_INVATE_OK_CONTENT', 'NOTIFY_REGISTER_INVATE_OK_TITLE', '1', '0', '1');
INSERT INTO `ts_notify_node` VALUES ('33', 'feedback_audit', '意見反饋稽覈', 'public', 'NOTIFY_FEEDBACK_AUDIT_CONTENT', 'NOTIFY_FEEDBACK_AUDIT_TITLE', '1', '0', '2');
INSERT INTO `ts_notify_node` VALUES ('29', 'public_account_delverify', '登出認證', 'public', 'NOTIFY_DEL_VERIFY_CONTENT', 'NOTIFY_DEL_VERIFY_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('28', 'admin_user_doverify_reject', '後臺認證駁回', 'public', 'NOTIFY_AUTHENTICATE_DOVERIFY_REJECT_CONTENT', 'NOTIFY_AUTHENTICATE_DOVERIFY_REJECT_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('26', 'public_account_doAuthenticate', '申請認證', 'public', 'NOTIFY_AUTHENTICATE_SUBMIT_CONTENT', 'NOTIFY_AUTHENTICATE_SUBMIT_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('27', 'admin_user_doverify_ok', '後臺認證通過', 'public', 'NOTIFY_AUTHENTICATE_DOVERIFY_OK_CONTENT', 'NOTIFY_AUTHENTICATE_DOVERIFY_OK_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('41', 'weiba_apply_reject', '駁回吧主申請', 'weiba', 'NOTIFY_WEIBA_APPLY_REJECT_CONTENT', 'NOTIFY_WEIBA_APPLY_REJECT_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('40', 'weiba_apply_ok', '通過吧主申請', 'weiba', 'NOTIFY_WEIBA_APPLY_OK_CONTENT', 'NOTIFY_WEIBA_APPLY_OK_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('39', 'weiba_apply', '申請吧主', 'weiba', 'NOTIFY_WEIBA_APPLY_CONTENT', 'NOTIFY_WEIBA_APPLY_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('42', 'admin_add_user_medal', '頒發勳章', 'public', 'NOTIFY_ADD_USER_MEDAL_CONTENT', 'NOTIFY_ADD_USER_MEDAL_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('43', 'weiba_post_set', '設定帖子', 'weiba', 'NOTIFY_WEIBA_POST_SET_CONTENT', 'NOTIFY_WEIBA_POST_SET_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('44', 'channel_add_feed', '推薦到頻道', 'channel', 'NOTIFY_CHANNEL_ADD_FEED_CONTENT', 'NOTIFY_CHANNEL_ADD_FEED_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('45', 'channel_audit', '頻道投稿稽覈通過', 'channel', 'NOTIFY_CHANNEL_AUDIT_CONTENT', 'NOTIFY_CHANNEL_AUDIT_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('46', 'feed_audit', '微博稽覈', 'public', 'NOTIFY_FEED_AUDIT_CONTENT', 'NOTIFY_FEED_AUDIT_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('47', 'comment_audit', '評論稽覈', 'public', 'NOTIFY_COMMENT_AUDIT_CONTENT', 'NOTIFY_COMMENT_AUDIT_TITLE', '0', '1', '2');
INSERT INTO `ts_notify_node` VALUES ('48', 'register_welcome', '註冊歡迎', 'public', 'NOTIFY_REGISTER_WELCOME_CONTENT', 'NOTIFY_REGISTER_WELCOME_TITLE', '1', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('52', 'tipoff_bonus', '爆料發獎', 'tipoff', 'NOTIFY_TIPOFF_BONUS_CONTENT', 'NOTIFY_TIPOFF_BONUS_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('51', 'tipoff_deal', '爆料處理提醒', 'tipoff', 'NOTIFY_TIPOFF_DEAL_CONTENT', 'NOTIFY_TIPOFF_DEAL_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('56', 'gift_send', '贈送禮物', 'gift', 'NOTIFY_GIFT_SEND_CONTENT', 'NOTIFY_GIFT_SEND_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('54', 'sitelist_approve', '站點通過', 'develop', 'NOTIFY_SITELIST_APPROVE_CONTENT', 'NOTIFY_SITELIST_APPROVE_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('55', 'sitelist_deny', '站點拒絕', 'develop', 'NOTIFY_SITELIST_DENY_CONTENT', 'NOTIFY_SITELIST_DENY_TITLE', '0', '1', '1');
INSERT INTO `ts_notify_node` VALUES ('64', 'digg', '微博的贊', 'public', 'digg_message_content', 'digg_message_title', '0', '1', '1');

-- ----------------------------
-- Table structure for `ts_credit_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ts_credit_setting`;
CREATE TABLE `ts_credit_setting` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '積分動作',
  `alias` varchar(255) NOT NULL COMMENT '積分名稱',
  `type` varchar(30) NOT NULL DEFAULT 'user' COMMENT '積分類型',
  `info` text NOT NULL COMMENT '積分說明',
  `score` int(11) DEFAULT NULL COMMENT '積分值',
  `experience` int(11) DEFAULT NULL COMMENT '經驗值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=216 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_credit_setting
-- ----------------------------
INSERT INTO `ts_credit_setting` VALUES ('37', 'invite_friend', '邀請好友', 'register', '{action}{sign}了{score}{typecn}', '8', '2');
INSERT INTO `ts_credit_setting` VALUES ('39', 'add_weibo', '釋出微博', 'weibo', '{action}{sign}了{score}{typecn}', '5', '5');
INSERT INTO `ts_credit_setting` VALUES ('40', 'user_login', '使用者登入', 'user', '{action}{sign}了{score}{typecn}', '2', '1');
INSERT INTO `ts_credit_setting` VALUES ('42', 'space_visited', '空間被訪問', 'user', '{action}{sign}了{score}{typecn}', '2', '1');
INSERT INTO `ts_credit_setting` VALUES ('92', 'init_default', '註冊積分', 'register', '{action}{sign}了{score}{typecn}', '200', '0');
INSERT INTO `ts_credit_setting` VALUES ('59', 'add_comment', '評論別人', 'comment', '{action}{sign}了{score}{typecn}', '6', '4');
INSERT INTO `ts_credit_setting` VALUES ('60', 'reply_comment', '回覆別人的評論', 'comment', '{action}{sign}了{score}{typecn}', '2', '1');
INSERT INTO `ts_credit_setting` VALUES ('61', 'replied_comment', '評論被回覆', 'comment', '{action}{sign}了{score}{typecn}', '3', '1');
INSERT INTO `ts_credit_setting` VALUES ('63', 'reply_weibo', '回覆微博', 'weibo', '{action}{sign}了{score}{typecn}', '3', '2');
INSERT INTO `ts_credit_setting` VALUES ('64', 'commented_weibo', '微博被評論', 'weibo', '{action}{sign}了{score}{typecn}', '2', '3');
INSERT INTO `ts_credit_setting` VALUES ('81', 'is_commented', '被別人評論', 'comment', '{action}{sign}了{score}{typecn}', '2', '1');
INSERT INTO `ts_credit_setting` VALUES ('83', 'share_to_weibo', '分享到微博', 'weibo', '{action}{sign}了{score}{typecn}', '4', '1');
INSERT INTO `ts_credit_setting` VALUES ('88', 'delete_comment', '刪除評論', 'comment', '{action}{sign}了{score}{typecn}', '-3', '1');
INSERT INTO `ts_credit_setting` VALUES ('89', 'delete_weibo', '刪除微博', 'weibo', '{action}{sign}了{score}{typecn}', '-1', '1');
INSERT INTO `ts_credit_setting` VALUES ('90', 'forward_weibo', '轉發微博', 'weibo', '{action}{sign}了{score}{typecn}', '1', '2');
INSERT INTO `ts_credit_setting` VALUES ('91', 'forwarded_weibo', '微博被轉發', 'weibo', '{action}{sign}了{score}{typecn}', '3', '2');
INSERT INTO `ts_credit_setting` VALUES ('93', 'delete_weibo_comment', '刪除微博評論', 'weibo', '{action}{sign}了{score}{typecn}', '-2', '1');
INSERT INTO `ts_credit_setting` VALUES ('94', 'add_medal', '獲得勳章', 'medal', '', '6', '2');
INSERT INTO `ts_credit_setting` VALUES ('103', 'delete_medal', '丟失勳章', 'medal', '{action}{sign}了{score}{typecn}', '-5', '0');
INSERT INTO `ts_credit_setting` VALUES ('179', 'core_code', '申請邀請碼', 'register', '', '-1', '-2');
INSERT INTO `ts_credit_setting` VALUES ('185', 'user_login', '使用者登入', 'core', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('186', 'space_access', '空間被訪問', 'core', '', '2', '1');
INSERT INTO `ts_credit_setting` VALUES ('187', 'comment_weibo', '評論微博', 'weibo', '', '3', '2');
INSERT INTO `ts_credit_setting` VALUES ('188', 'collect_weibo', '收藏微博', 'weibo', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('189', 'report_weibo', '舉報微博', 'weibo', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('190', 'collected_weibo', '微博被收藏', 'weibo', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('191', 'reported_weibo', '微博被舉報', 'weibo', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('192', 'recommend_to_channel', '推薦至頻道', 'channel', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('193', 'unrecommend_to_channel', '取消推薦至頻道', 'channel', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('194', 'publish_topic', '發表帖子', 'weiba', '', '10', '10');
INSERT INTO `ts_credit_setting` VALUES ('195', 'forward_topic', '轉發帖子', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('196', 'comment_topic', '評論帖子', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('197', 'collect_topic', '收藏帖子', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('198', 'top_topic_all', '帖子被設定全局置頂', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('199', 'top_topic_weiba', '帖子被設定吧內建頂', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('200', 'dist_topic', '帖子被設定精華', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('201', 'undist_topic', '帖子被取消精華', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('202', 'untop_topic_all', '帖子被取消全局置頂', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('203', 'untop_topic_weiba', '帖子被取消吧內建頂', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('204', 'forwarded_topic', '帖子被轉發', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('205', 'commented_topic', '帖子被評論', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('206', 'collected_topic', '帖子被收藏', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('207', 'recommend_topic', '帖子被推薦', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('208', 'delete_topic', '刪除帖子', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('209', 'delete_topic_comment', '刪除帖子回覆', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('210', 'follow_weiba', '關注微吧', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('211', 'unfollow_weiba', '取消關注微吧', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('212', 'out_weiba', '踢出微吧', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('213', 'appointed_weiba', '被任命為吧主', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('214', 'unappointed_weiba', '取消任命吧主', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('215', 'recommended_weiba', '微吧被推薦', 'weiba', '', '0', '0');
INSERT INTO `ts_credit_setting` VALUES ('260', 'digg_weibo', '頂微博', 'weibo', '', '1', '1');
INSERT INTO `ts_credit_setting` VALUES ('261', 'digged_weibo', '微博被頂', 'weibo', '', '6', '5');

-- ----------------------------
-- Table structure for `ts_permission_node`
-- ----------------------------
DROP TABLE IF EXISTS `ts_permission_node`;
CREATE TABLE `ts_permission_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `appinfo` varchar(50) NOT NULL COMMENT '應用說明',
  `module` varchar(50) NOT NULL COMMENT '模組名稱',
  `rule` varchar(50) NOT NULL COMMENT '許可權類型',
  `ruleinfo` varchar(50) NOT NULL COMMENT '許可權名稱',
  PRIMARY KEY (`id`),
  KEY `rule` (`rule`)
) ENGINE=MyISAM AUTO_INCREMENT=433 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_permission_node
-- ----------------------------
INSERT INTO `ts_permission_node` VALUES ('2', 'core', '核心', 'normal', 'feed_post', '發表微博');
INSERT INTO `ts_permission_node` VALUES ('3', 'core', '核心', 'normal', 'feed_comment', '評論微博');
INSERT INTO `ts_permission_node` VALUES ('4', 'core', '核心', 'normal', 'feed_report', '舉報微博');
INSERT INTO `ts_permission_node` VALUES ('5', 'core', '核心', 'normal', 'feed_share', '分享微博');
INSERT INTO `ts_permission_node` VALUES ('6', 'core', '核心', 'admin', 'feed_del', '刪除微博');
INSERT INTO `ts_permission_node` VALUES ('7', 'core', '核心', 'admin', 'comment_del', '刪除評論');
INSERT INTO `ts_permission_node` VALUES ('8', 'core', '核心', 'admin', 'message_del', '刪除私信');
INSERT INTO `ts_permission_node` VALUES ('9', 'core', '核心', 'admin', 'admin_login', '登入後臺');
INSERT INTO `ts_permission_node` VALUES ('415', 'core', '核心', 'normal', 'feed_del', '前臺刪除微博');
INSERT INTO `ts_permission_node` VALUES ('416', 'core', '核心', 'normal', 'comment_del', '刪除評論');
INSERT INTO `ts_permission_node` VALUES ('417', 'core', '核心', 'normal', 'search_info', '大搜索');
INSERT INTO `ts_permission_node` VALUES ('418', 'core', '核心', 'normal', 'send_message', '發私信');
INSERT INTO `ts_permission_node` VALUES ('419', 'core', '核心', 'normal', 'read_data', '瀏覽資料');
INSERT INTO `ts_permission_node` VALUES ('420', 'core', '核心', 'normal', 'invite_user', '邀請使用者');
INSERT INTO `ts_permission_node` VALUES ('421', 'weiba', '微吧', 'normal', 'weiba_post', '微吧發帖');
INSERT INTO `ts_permission_node` VALUES ('422', 'weiba', '微吧', 'normal', 'weiba_reply', '微吧回帖');
INSERT INTO `ts_permission_node` VALUES ('423', 'weiba', '微吧', 'normal', 'weiba_del', '微吧刪帖');
INSERT INTO `ts_permission_node` VALUES ('424', 'weiba', '微吧', 'normal', 'weiba_del_reply', '微吧刪除回帖');
INSERT INTO `ts_permission_node` VALUES ('425', 'weiba', '微吧', 'normal', 'weiba_edit', '微吧編輯帖子');
INSERT INTO `ts_permission_node` VALUES ('426', 'weiba', '微吧', 'normal', 'weiba_apply_manage', '申請吧主');
INSERT INTO `ts_permission_node` VALUES ('427', 'weiba', '微吧', 'admin', 'weiba_recommend', '微吧推薦帖子');
INSERT INTO `ts_permission_node` VALUES ('428', 'weiba', '微吧', 'admin', 'weiba_top', '微吧置頂');
INSERT INTO `ts_permission_node` VALUES ('429', 'weiba', '微吧', 'admin', 'weiba_marrow', '微吧精華');
INSERT INTO `ts_permission_node` VALUES ('430', 'weiba', '微吧', 'admin', 'weiba_global_top', '微吧全局置頂');
INSERT INTO `ts_permission_node` VALUES ('431', 'weiba', '微吧', 'admin', 'weiba_del', '微吧刪除帖子');
INSERT INTO `ts_permission_node` VALUES ('432', 'weiba', '微吧', 'admin', 'weiba_edit', '微吧編輯帖子');
INSERT INTO `ts_permission_node` VALUES ('10', 'channel', '頻道', 'admin', 'channel_recommend', '推薦頻道');
INSERT INTO `ts_permission_node` VALUES ('11', 'core', '核心', 'normal', 'feed_audit', '先審後發');

-- ----------------------------
-- Table structure for `ts_expression`
-- ----------------------------
DROP TABLE IF EXISTS `ts_expression`;
CREATE TABLE `ts_expression` (
  `expression_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '標題',
  `type` varchar(255) NOT NULL DEFAULT 'miniblog',
  `emotion` varchar(255) NOT NULL COMMENT '文字編碼，格式：[文字]，如[擁抱]、[示愛]、[呲牙]等',
  `filename` varchar(255) NOT NULL COMMENT '表情圖片檔名',
  PRIMARY KEY (`expression_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_expression
-- ----------------------------
INSERT INTO `ts_expression` VALUES ('1', '擁抱', 'miniblog', '[擁抱]', 'hug.gif');
INSERT INTO `ts_expression` VALUES ('2', '示愛', 'miniblog', '[示愛]', 'kiss.gif');
INSERT INTO `ts_expression` VALUES ('3', '呲牙', 'miniblog', '[呲牙]', 'lol.gif');
INSERT INTO `ts_expression` VALUES ('4', '可愛', 'miniblog', '[可愛]', 'loveliness.gif');
INSERT INTO `ts_expression` VALUES ('5', '折磨', 'miniblog', '[折磨]', 'mad.gif');
INSERT INTO `ts_expression` VALUES ('6', '難過', 'miniblog', '[難過]', 'sad.gif');
INSERT INTO `ts_expression` VALUES ('7', '流汗', 'miniblog', '[流汗]', 'sweat.gif');
INSERT INTO `ts_expression` VALUES ('8', '憨笑', 'miniblog', '[憨笑]', 'biggrin.gif');
INSERT INTO `ts_expression` VALUES ('9', '大哭', 'miniblog', '[大哭]', 'cry.gif');
INSERT INTO `ts_expression` VALUES ('11', '握手', 'miniblog', '[握手]', 'handshake.gif');
INSERT INTO `ts_expression` VALUES ('12', '發怒', 'miniblog', '[發怒]', 'huffy.gif');
INSERT INTO `ts_expression` VALUES ('13', '驚訝', 'miniblog', '[驚訝]', 'shocked.gif');
INSERT INTO `ts_expression` VALUES ('14', '害羞', 'miniblog', '[害羞]', 'shy.gif');
INSERT INTO `ts_expression` VALUES ('15', '微笑', 'miniblog', '[微笑]', 'smile.gif');
INSERT INTO `ts_expression` VALUES ('16', '偷笑', 'miniblog', '[偷笑]', 'titter.gif');
INSERT INTO `ts_expression` VALUES ('17', '調皮', 'miniblog', '[調皮]', 'tongue.gif');
INSERT INTO `ts_expression` VALUES ('19', '啤酒', 'miniblog', '[啤酒]', 'beer.gif');
INSERT INTO `ts_expression` VALUES ('20', '蛋糕', 'miniblog', '[蛋糕]', 'cake.gif');
INSERT INTO `ts_expression` VALUES ('21', '奮鬥', 'miniblog', '[奮鬥]', 'fendou.gif');
INSERT INTO `ts_expression` VALUES ('22', '出錯了', 'miniblog', '[出錯了]', 'funk.gif');
INSERT INTO `ts_expression` VALUES ('23', '哈欠', 'miniblog', '[哈欠]', 'ha.gif');
INSERT INTO `ts_expression` VALUES ('24', '晚安', 'miniblog', '[晚安]', 'moon.gif');
INSERT INTO `ts_expression` VALUES ('25', '歐克', 'miniblog', '[歐克]', 'ok.gif');
INSERT INTO `ts_expression` VALUES ('26', '豬頭', 'miniblog', '[豬頭]', 'pig.gif');
INSERT INTO `ts_expression` VALUES ('31', '暈', 'miniblog', '[暈]', 'yun.gif');
INSERT INTO `ts_expression` VALUES ('32', '偶也 ', 'miniblog', '[偶也]', 'victory.gif');

-- ----------------------------
-- Table structure for `ts_diy_canvas`
-- ----------------------------
DROP TABLE IF EXISTS `ts_diy_canvas`;
CREATE TABLE `ts_diy_canvas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL COMMENT 'DIY畫布標題',
  `canvas_name` varchar(255) DEFAULT NULL COMMENT 'DIY畫布名稱--對應模板檔名',
  `data` text COMMENT '模板資料',
  `description` varchar(500) DEFAULT NULL COMMENT '畫布說明',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_diy_canvas
-- ----------------------------
INSERT INTO `ts_diy_canvas` VALUES ('1', '首頁', 'index.html', 'PGluY2x1ZGUgZmlsZT0iX19USEVNRV9fL3B1YmxpY19oZWFkZXIiIC8+DQo8ZGl2IHN0eWxlPSJoZWlnaHQ6NjBweCI+PC9kaXY+DQo8aW5jbHVkZSBmaWxlPSJfX1RIRU1FX18vZGl5X2hlYWRlciIgLz4NCjxsaW5rIGhyZWY9Il9fQVBQX18vUHVibGljL2Nzcy9kaXlfYWRhcHRhYmxlLmNzcyIgcmVsPSJzdHlsZXNoZWV0IiB0eXBlPSJ0ZXh0L2NzcyIgLz4NCjxsaW5rIGhyZWY9Il9fQVBQX18vUHVibGljL2Nzcy9pbmRleC5jc3MiIHJlbD0ic3R5bGVzaGVldCIgdHlwZT0idGV4dC9jc3MiIC8+DQo8bGluayBocmVmPSJfX0FQUF9fL1B1YmxpYy9jc3MvcG9wX3VwLmNzcyIgcmVsPSJzdHlsZXNoZWV0IiB0eXBlPSJ0ZXh0L2NzcyIgLz4NCjxkaXYgY2xhc3M9ImRpeV9jb250ZW50IGJnX2RpeSBib3hTaGFkb3ciIHN0eWxlPSJ3aWR0aDo5NjBweCI+DQp7JGRhdGF9DQogICAgPGRpdiBjbGFzcz0iQyI+DQogICAgPC9kaXY+DQoNCg0KPC9kaXY+DQoNCjxpbmNsdWRlIGZpbGU9Il9fVEhFTUVfXy9wdWJsaWNfZm9vdGVyIiAvPg==', '首頁');

-- ----------------------------
-- Table structure for `ts_task_reward`
-- ----------------------------
DROP TABLE IF EXISTS `ts_task_reward`;
CREATE TABLE `ts_task_reward` (
  `task_type` int(11) DEFAULT NULL COMMENT '任務類型',
  `task_level` int(11) DEFAULT NULL COMMENT '任務等級',
  `reward` varchar(255) DEFAULT NULL COMMENT '任務獎勵',
  UNIQUE KEY `index_type_level` (`task_type`,`task_level`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_task_reward
-- ----------------------------
INSERT INTO `ts_task_reward` VALUES ('2', '1', '{\"exp\":50,\"score\":50,\"medal\":{\"id\":70,\"name\":\"\\u65b0\\u624b\\u4e0a\\u8def\",\"src\":\"2013\\/0121\\/11\\/50fcb0d64a404.png\"}}');
INSERT INTO `ts_task_reward` VALUES ('1', '1', '{\"exp\":10,\"score\":10,\"medal\":null}');
INSERT INTO `ts_task_reward` VALUES ('2', '5', '{\"exp\":1000,\"score\":1000,\"medal\":{\"id\":74,\"name\":\"\\u72ec\\u5b64\\u6c42\\u8d25\",\"src\":\"2013\\/0121\\/11\\/50fcb0426d7df.png\"}}');
INSERT INTO `ts_task_reward` VALUES ('2', '2', '{\"exp\":100,\"score\":100,\"medal\":{\"id\":71,\"name\":\"\\u5c0f\\u6709\\u8fdb\\u6b65\",\"src\":\"2013\\/0121\\/11\\/50fcb0c2a4fa2.png\"}}');
INSERT INTO `ts_task_reward` VALUES ('2', '3', '{\"exp\":200,\"score\":200,\"medal\":{\"id\":72,\"name\":\"\\u5fae\\u535a\\u8fbe\\u4eba\",\"src\":\"2013\\/0121\\/11\\/50fcb08b08634.png\"}}');
INSERT INTO `ts_task_reward` VALUES ('2', '4', '{\"exp\":400,\"score\":400,\"medal\":{\"id\":73,\"name\":\"\\u6b66\\u6797\\u9ad8\\u624b\",\"src\":\"2013\\/0121\\/11\\/50fcb062ed874.png\"}}');

-- ----------------------------
-- Table structure for `ts_app`
-- ----------------------------
DROP TABLE IF EXISTS `ts_app`;
CREATE TABLE `ts_app` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(255) NOT NULL COMMENT 'app名稱',
  `app_alias` varchar(255) NOT NULL COMMENT 'app別名',
  `description` text COMMENT '描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0:''關閉'',1:開啟',
  `host_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0，1',
  `app_entry` varchar(255) DEFAULT NULL COMMENT '前臺入口，例：''Index/index''',
  `icon_url` varchar(255) DEFAULT NULL COMMENT '圖示',
  `large_icon_url` varchar(255) DEFAULT NULL COMMENT '圖示地址',
  `admin_entry` varchar(255) DEFAULT NULL COMMENT '後臺管理地址',
  `statistics_entry` varchar(255) DEFAULT NULL COMMENT '介面地址',
  `display_order` smallint(5) NOT NULL DEFAULT '0' COMMENT '排序',
  `ctime` int(11) DEFAULT NULL COMMENT '安裝時間戳',
  `version` varchar(255) DEFAULT NULL COMMENT '版本號',
  `api_key` varchar(255) DEFAULT NULL COMMENT '使用者api_key',
  `secure_key` varchar(255) DEFAULT NULL COMMENT 'API金鑰',
  `company_name` varchar(255) NOT NULL COMMENT '公司名稱',
  `has_mobile` tinyint(2) NOT NULL DEFAULT '0' COMMENT '是否有移動客戶端.0:無，1有',
  `child_menu` text NOT NULL COMMENT '子導航資料',
  `add_front_top` tinyint(1) DEFAULT '1',
  `add_front_applist` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`app_id`),
  KEY `app_name` (`app_name`),
  KEY `status_id` (`app_id`,`status`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_app
-- ----------------------------
INSERT INTO `ts_app` VALUES ('5', 'channel', '頻道', '頻道', '1', '0', 'Index/index', '', '', 'channel/Admin/index', 'Statistics/statistics', '5', '1352520034', '', '', '', '智士軟體', '1', 'a:1:{s:7:\"channel\";a:2:{s:3:\"url\";s:19:\"channel/Index/index\";s:6:\"public\";i:0;}}', '1', '1');
INSERT INTO `ts_app` VALUES ('3', 'weiba', '微吧', '微吧', '1', '0', 'Index/index', '', '', 'weiba/Admin/index', 'Statistics/statistics', '3', '1352256442', '1.0', '', '', '智士軟體', '1', 'a:1:{s:5:\"weiba\";a:2:{s:3:\"url\";s:19:\"weiba/Index/myWeiba\";s:6:\"public\";i:0;}}', '1', '1');
INSERT INTO `ts_app` VALUES ('6', 'people', '找人', '按各種維度展示使用者', '1', '0', 'Index/index', '', '', '', '', '6', '1363096393', '', '', '', '智士軟體', '0', 'a:0:{}', '1', '1');

-- ----------------------------
-- Table structure for `ts_addons`
-- ----------------------------
DROP TABLE IF EXISTS `ts_addons`;
CREATE TABLE `ts_addons` (
  `addonId` int(4) unsigned NOT NULL AUTO_INCREMENT COMMENT '插件id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '插件資料夾名',
  `pluginName` varchar(255) NOT NULL DEFAULT '' COMMENT '插件在後臺顯示的名字',
  `author` varchar(255) NOT NULL DEFAULT '' COMMENT '插件作者',
  `info` tinytext COMMENT '插件資訊',
  `version` varchar(50) DEFAULT NULL COMMENT '插件版本',
  `status` enum('0','1') NOT NULL DEFAULT '1' COMMENT '插件狀態。0為未啟用，1為啟用',
  `lastupdate` varchar(255) DEFAULT '' COMMENT '最後更新時間',
  `site` varchar(255) DEFAULT NULL COMMENT '插件作者的網站',
  `tsVersion` varchar(11) NOT NULL DEFAULT '2.5' COMMENT '依賴ts的版本。預留。必填',
  PRIMARY KEY (`addonId`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_addons
-- ----------------------------
INSERT INTO `ts_addons` VALUES ('1', 'SpaceStyle', '空間換膚 - 官方優化版', '智士軟體', '使用者自定義風格官方優化版', '2.0', '1', '', '', '3.0');
INSERT INTO `ts_addons` VALUES ('2', 'Weather', '天氣預報', '程式_小時代', '天氣預報，根據IP獲取該城市3天內天氣資訊', '2.0', '0', '', '', '3.0');
INSERT INTO `ts_addons` VALUES ('3', 'RelatedUser', '可能感興趣的人', 't3', '根據當前使用者推薦可能感興趣的人', '3.0', '0', '', '', '3.0');
INSERT INTO `ts_addons` VALUES ('4', 'Login', '微博同步V3', '智士軟體', '第三方賬號登入插件', '3.0', '0', '', '', '3.0');

-- ----------------------------
-- Table structure for `ts_navi`
-- ----------------------------
DROP TABLE IF EXISTS `ts_navi`;
CREATE TABLE `ts_navi` (
  `navi_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '導航ID',
  `navi_name` varchar(255) DEFAULT NULL COMMENT '導航名稱',
  `app_name` varchar(255) DEFAULT NULL COMMENT '應用標誌，如index、home、public等',
  `url` varchar(255) DEFAULT NULL COMMENT '連結地址',
  `target` varchar(10) DEFAULT NULL COMMENT '開啟方式',
  `status` int(1) DEFAULT NULL COMMENT '狀態（0關閉，1開啟）',
  `position` varchar(10) DEFAULT NULL COMMENT '導航位置',
  `guest` int(1) DEFAULT NULL COMMENT '是否遊客可見（0否，1是，默認1）',
  `is_app_navi` int(1) DEFAULT NULL COMMENT '是否應用內部導航 （0否，1是，默認1）',
  `parent_id` int(11) DEFAULT NULL COMMENT '（父導航，默認為0）',
  `order_sort` int(11) DEFAULT NULL COMMENT '應用排序 默認255',
  PRIMARY KEY (`navi_id`),
  KEY `status_postion` (`status`,`position`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_navi
-- ----------------------------
INSERT INTO `ts_navi` VALUES ('1', '首頁', 'public', '{website}', '_self', '1', '0', '1', '0', '0', '1');
INSERT INTO `ts_navi` VALUES ('2', '頻道', 'channel', '{website}/index.php?app=channel&mod=Index&act=index', '_self', '1', '0', '1', '0', '0', '3');
INSERT INTO `ts_navi` VALUES ('3', '找人', 'people', '{website}/index.php?app=people&mod=Index&act=index', '_self', '1', '0', '1', '0', '0', '2');
INSERT INTO `ts_navi` VALUES ('4', '微吧', 'weiba', '{website}/index.php?app=weiba&mod=Index&act=index', '_self', '1', '0', '1', '0', '0', '4');

-- ----------------------------
-- Table structure for `ts_user_group`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_group`;
CREATE TABLE `ts_user_group` (
  `user_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `user_group_name` varchar(255) NOT NULL COMMENT '使用者組名稱',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  `user_group_icon` varchar(120) NOT NULL COMMENT '使用者組圖示名稱',
  `user_group_type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '組類型、0：普通組，1:特殊組，',
  `app_name` varchar(20) NOT NULL DEFAULT 'public' COMMENT '應用名稱',
  `is_authenticate` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否為認證組',
  PRIMARY KEY (`user_group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_group
-- ----------------------------
INSERT INTO `ts_user_group` VALUES ('1', '管理員', '1354605105', '-1', '0', 'public', '0');
INSERT INTO `ts_user_group` VALUES ('2', '巡邏員', '1363097759', 'v_02.gif', '0', 'public', '0');
INSERT INTO `ts_user_group` VALUES ('3', '正常使用者', '1354605704', '-1', '0', 'public', '0');
INSERT INTO `ts_user_group` VALUES ('4', '禁言使用者', '1354605046', 'v_04.png', '0', 'public', '0');
INSERT INTO `ts_user_group` VALUES ('5', '個人認證', '1350012209', 'v_01.gif', '0', 'public', '1');
INSERT INTO `ts_user_group` VALUES ('6', '企業/組織認證', '1350012483', 'v_06.gif', '0', 'public', '1');
INSERT INTO `ts_user_group` VALUES ('7', '達人使用者', '1354605062', 'v_01.png', '0', 'public', '1');

-- ----------------------------
-- Table structure for `ts_user_category`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_category`;
CREATE TABLE `ts_user_category` (
  `user_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '使用者分類ID - 主鍵',
  `title` varchar(255) NOT NULL COMMENT '使用者分類名稱',
  `pid` int(11) NOT NULL COMMENT '父級ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`user_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_category
-- ----------------------------
INSERT INTO `ts_user_category` VALUES ('1', '測試', '0', '1');
INSERT INTO `ts_user_category` VALUES ('2', '測試使用者1', '1', '1');
INSERT INTO `ts_user_category` VALUES ('3', '測試使用者2', '1', '2');
INSERT INTO `ts_user_category` VALUES ('4', '測試使用者3', '1', '3');

-- ----------------------------
-- Table structure for `ts_user_verified_category`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_verified_category`;
CREATE TABLE `ts_user_verified_category` (
  `user_verified_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '認證分類主鍵',
  `title` varchar(225) NOT NULL COMMENT '認證分類名稱',
  `pid` int(11) NOT NULL COMMENT '父分類ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`user_verified_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_verified_category
-- ----------------------------
INSERT INTO `ts_user_verified_category` VALUES ('1', '認證分類1', '5', '1');
INSERT INTO `ts_user_verified_category` VALUES ('2', '認證分類2', '6', '2');
INSERT INTO `ts_user_verified_category` VALUES ('3', '認證分類3', '7', '3');

-- ----------------------------
-- Table structure for `ts_user_official_category`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_official_category`;
CREATE TABLE `ts_user_official_category` (
  `user_official_category_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '官方使用者分類',
  `title` varchar(255) NOT NULL COMMENT '官方使用者分類名稱',
  `pid` int(11) NOT NULL COMMENT '父級分類ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`user_official_category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_official_category
-- ----------------------------
INSERT INTO `ts_user_official_category` VALUES ('1', '官方分類1', '0', '1');
INSERT INTO `ts_user_official_category` VALUES ('2', '官方分類2', '0', '2');
INSERT INTO `ts_user_official_category` VALUES ('3', '官方分類3', '0', '3');

-- ----------------------------
-- Table structure for `ts_permission_group`
-- ----------------------------
DROP TABLE IF EXISTS `ts_permission_group`;
CREATE TABLE `ts_permission_group` (
  `id` int(5) NOT NULL AUTO_INCREMENT,
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `appgroup` varchar(50) NOT NULL COMMENT '應用組名稱',
  `appgroup_name` varchar(50) NOT NULL COMMENT '組別名',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_permission_group
-- ----------------------------
INSERT INTO `ts_permission_group` VALUES ('1', 'admin', 'admin', '普通管理員');
INSERT INTO `ts_permission_group` VALUES ('2', 'admin', 'superadmin', '超級管理員');

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
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_channel_category
-- ----------------------------
INSERT INTO `ts_channel_category` VALUES ('1', '頻道1', '0', '1', null);
INSERT INTO `ts_channel_category` VALUES ('2', '頻道2', '0', '2', null);
INSERT INTO `ts_channel_category` VALUES ('3', '頻道3', '0', '3', null);

-- ----------------------------
-- Table structure for `ts_credit_type`
-- ----------------------------
DROP TABLE IF EXISTS `ts_credit_type`;
CREATE TABLE `ts_credit_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT '積分名',
  `alias` varchar(50) NOT NULL COMMENT '積分中文名',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_credit_type
-- ----------------------------
INSERT INTO `ts_credit_type` VALUES ('1', 'experience', '經驗');
INSERT INTO `ts_credit_type` VALUES ('6', 'score', '財富');

-- ----------------------------
-- Table structure for `ts_search_select`
-- ----------------------------
DROP TABLE IF EXISTS `ts_search_select`;
CREATE TABLE `ts_search_select` (
  `id` int(4) NOT NULL AUTO_INCREMENT,
  `app_name` varchar(20) NOT NULL COMMENT '應用名稱',
  `app_id` tinyint(3) NOT NULL COMMENT '應用ID',
  `type` varchar(20) NOT NULL COMMENT '類型名稱',
  `type_id` tinyint(3) NOT NULL COMMENT '類型在應用內定義的ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_search_select
-- ----------------------------
INSERT INTO `ts_search_select` VALUES ('1', 'public', '0', '使用者', '1');
INSERT INTO `ts_search_select` VALUES ('2', 'public', '0', '微博', '2');

-- ----------------------------
-- Table structure for `ts_x_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ts_x_logs`;
CREATE TABLE `ts_x_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '使用者ID',
  `uname` varchar(50) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '帳號\r\n',
  `app_name` char(80) NOT NULL COMMENT '日誌所屬應用',
  `group` char(80) DEFAULT NULL COMMENT '日志分組',
  `action` char(80) NOT NULL COMMENT '日志行為',
  `ip` varchar(80) DEFAULT NULL COMMENT 'IP地址',
  `data` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '序列化儲存的模板變數',
  `url` varchar(250) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '記錄日誌時的URL地址',
  `ctime` int(11) NOT NULL COMMENT '創建時間',
  `isAdmin` tinyint(2) NOT NULL COMMENT '是否是管理員日誌',
  `keyword` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '模板變數值',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_x_logs
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_x_article`
-- ----------------------------
DROP TABLE IF EXISTS `ts_x_article`;
CREATE TABLE `ts_x_article` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(200) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '標題',
  `uid` int(10) NOT NULL COMMENT '釋出者ID',
  `mtime` int(11) NOT NULL COMMENT '修改時間',
  `sort` tinyint(5) NOT NULL COMMENT '排序',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '內容',
  `attach` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '附件資訊',
  `type` tinyint(3) NOT NULL COMMENT '類型:1公告，2頁尾配置文章',
  PRIMARY KEY (`id`),
  UNIQUE KEY `type` (`type`,`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_x_article
-- ----------------------------

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
-- Records of ts_weiba_reply
-- ----------------------------

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
  PRIMARY KEY (`post_id`),
  KEY `id_recommend` (`recommend_time`,`weiba_id`,`recommend`),
  KEY `post_time` (`post_time`,`weiba_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba_post
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_weiba_log`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_log`;
CREATE TABLE `ts_weiba_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weiba_id` int(11) NOT NULL COMMENT '微吧ID',
  `uid` int(11) NOT NULL COMMENT '操作者UID',
  `type` varchar(10) NOT NULL COMMENT '操作類型',
  `content` text NOT NULL COMMENT '管理內容',
  `ctime` int(11) NOT NULL COMMENT '操作時間',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba_log
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_weiba_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_follow`;
CREATE TABLE `ts_weiba_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `weiba_id` int(11) NOT NULL COMMENT '微吧ID',
  `follower_uid` int(11) NOT NULL COMMENT '成員ID',
  `level` tinyint(1) NOT NULL DEFAULT '1' COMMENT '等級 1-粉絲 2-小吧 3-吧主',
  PRIMARY KEY (`id`),
  KEY `uid` (`follower_uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba_follow
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_weiba_favorite`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_favorite`;
CREATE TABLE `ts_weiba_favorite` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '收藏者UID',
  `post_id` int(11) NOT NULL COMMENT '帖子ID',
  `weiba_id` int(11) NOT NULL COMMENT '微吧ID',
  `post_uid` int(11) NOT NULL COMMENT '釋出者UID',
  `favorite_time` int(11) NOT NULL COMMENT '收藏時間',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba_favorite
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_weiba_apply`
-- ----------------------------
DROP TABLE IF EXISTS `ts_weiba_apply`;
CREATE TABLE `ts_weiba_apply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `follower_uid` int(11) NOT NULL COMMENT '申請者UID',
  `weiba_id` int(11) NOT NULL COMMENT '微吧id',
  `type` tinyint(2) NOT NULL COMMENT '申請類型 1-吧主 2-小吧',
  `reason` varchar(255) DEFAULT NULL COMMENT '申請原因',
  `status` tinyint(2) NOT NULL COMMENT '狀態 0-待稽覈 1-稽覈通過 -1-駁回',
  `manager_uid` int(11) NOT NULL COMMENT '操作者UID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba_apply
-- ----------------------------

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
  PRIMARY KEY (`weiba_id`),
  KEY `recommend` (`recommend`,`is_del`),
  KEY `count` (`is_del`,`follower_count`,`thread_count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_weiba
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_verified`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_verified`;
CREATE TABLE `ts_user_verified` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用戶UID',
  `usergroup_id` int(11) NOT NULL COMMENT '認證類型，即所申請的認證組的ID',
  `user_verified_category_id` int(11) NOT NULL DEFAULT '0' COMMENT '認證分類ID',
  `company` varchar(255) NOT NULL COMMENT '公司名稱',
  `realname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '真實姓名',
  `idcard` varchar(50) NOT NULL COMMENT '證件號碼',
  `phone` varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '聯繫方式',
  `info` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '證認資訊',
  `verified` tinyint(2) NOT NULL DEFAULT '0' COMMENT '認證狀態，0：否；1：是',
  `attach_id` varchar(255) NOT NULL COMMENT '認證資料，存儲使用者上傳的ID',
  `reason` varchar(255) DEFAULT NULL COMMENT '證認理由',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_verified
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_profile_setting`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_profile_setting`;
CREATE TABLE `ts_user_profile_setting` (
  `field_id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL DEFAULT '2' COMMENT '資料類型：1、分組，2、欄位',
  `field_key` varchar(120) NOT NULL COMMENT '欄位鍵值',
  `field_name` varchar(120) NOT NULL COMMENT '欄位名稱',
  `field_type` int(5) NOT NULL DEFAULT '0' COMMENT '欄位類型ID，值為上一級欄位ID，值為0時代表根分類',
  `visiable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否空間展示：默認1=可展示',
  `editable` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可修改：默認1=可修改',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否必填項：默認0=非必填',
  `privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：所有人，1：好友， 2：互相關注，3：關注我的，4：我關注的',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT '欄位排序符號值',
  `form_type` varchar(120) DEFAULT NULL COMMENT '欄位表單類型：input、textarea、select、radio、checkbox、timeinput',
  `form_default_value` text COMMENT '默認選擇的資料項',
  `validation` varchar(120) DEFAULT NULL COMMENT '前臺表單驗證的方法名',
  `tips` varchar(255) DEFAULT NULL COMMENT '提示說明',
  `is_system` int(2) NOT NULL DEFAULT '0' COMMENT '是否系統配置0不是，1是，系統的配置項不能刪除，不能改key',
  PRIMARY KEY (`field_id`),
  KEY `type` (`type`,`field_key`,`display_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_profile_setting
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_profile`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_profile`;
CREATE TABLE `ts_user_profile` (
  `uid` int(11) unsigned NOT NULL COMMENT '用戶UID',
  `field_id` smallint(8) unsigned NOT NULL COMMENT '資料資料ID',
  `field_data` text NOT NULL COMMENT '資料資料欄位名',
  `privacy` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0：所有人，1：好友， 2：互相關注，3：關注我的，4：我關注的',
  UNIQUE KEY `uid` (`uid`,`field_id`),
  KEY `uid_2` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_profile
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_privacy`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_privacy`;
CREATE TABLE `ts_user_privacy` (
  `uid` int(11) NOT NULL COMMENT '用戶UID',
  `key` varchar(120) NOT NULL COMMENT '配置鍵名，如weibo_comment（評論）,message（私信）',
  `value` varchar(120) NOT NULL COMMENT '配置值，0：所有人(不包括你的黑名單使用者)；1：我關注的人'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_privacy
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_online`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_online`;
CREATE TABLE `ts_user_online` (
  `uid` int(11) NOT NULL COMMENT '用戶UID',
  `ctime` int(11) NOT NULL COMMENT '最後一次操作動作時間戳，與當前時間相隔五分鐘之內為線上',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_online
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_official`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_official`;
CREATE TABLE `ts_user_official` (
  `official_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `uid` int(11) NOT NULL COMMENT '使用者ID',
  `user_official_category_id` int(11) NOT NULL COMMENT '官方分類ID',
  `info` varchar(255) DEFAULT NULL COMMENT '官方使用者資訊',
  PRIMARY KEY (`official_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_official
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_group_link`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_group_link`;
CREATE TABLE `ts_user_group_link` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `uid` int(10) NOT NULL COMMENT '用戶UID',
  `user_group_id` int(10) NOT NULL COMMENT '用戶組ID',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`),
  KEY `user_group_id` (`user_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_group_link
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_follow_group_link`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_follow_group_link`;
CREATE TABLE `ts_user_follow_group_link` (
  `follow_group_link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '關注組聯表ID',
  `follow_group_id` int(11) NOT NULL COMMENT '關注組ID',
  `follow_id` int(11) NOT NULL COMMENT 'user_follow  表中follow_id',
  `fid` int(11) NOT NULL COMMENT '被關注人ID',
  `uid` int(11) NOT NULL COMMENT '關注人ID',
  PRIMARY KEY (`follow_group_link_id`),
  UNIQUE KEY `follow_group_id` (`uid`,`fid`,`follow_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_follow_group_link
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_follow_group`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_follow_group`;
CREATE TABLE `ts_user_follow_group` (
  `follow_group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '關注組ID',
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  `title` varchar(255) NOT NULL COMMENT '組名稱',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  PRIMARY KEY (`follow_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_follow_group
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_follow`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_follow`;
CREATE TABLE `ts_user_follow` (
  `follow_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `uid` int(11) NOT NULL COMMENT '關注者ID',
  `fid` int(11) NOT NULL COMMENT '被關注者ID',
  `remark` varchar(50) NOT NULL COMMENT '備註',
  `ctime` int(11) NOT NULL COMMENT '關注時間',
  PRIMARY KEY (`follow_id`),
  UNIQUE KEY `uid-fid` (`uid`,`fid`),
  UNIQUE KEY `fid-uid` (`fid`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_follow
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_department`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_department`;
CREATE TABLE `ts_user_department` (
  `uid` int(10) NOT NULL COMMENT '使用者ID',
  `department_id` int(10) NOT NULL COMMENT '部門ID',
  KEY `department_id` (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_department
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_data`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_data`;
CREATE TABLE `ts_user_data` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `uid` int(11) NOT NULL COMMENT '用戶UID',
  `key` varchar(50) NOT NULL COMMENT 'Key',
  `value` text COMMENT '對應Key的 值',
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '前當時間戳',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user-key` (`uid`,`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_data
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_credit_history`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_credit_history`;
CREATE TABLE `ts_user_credit_history` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '鍵主ID',
  `uid` int(11) NOT NULL COMMENT '操作使用者UID',
  `info` varchar(255) DEFAULT NULL COMMENT '動作描述',
  `action` char(30) DEFAULT NULL COMMENT '作動',
  `type` char(10) NOT NULL DEFAULT 'credit' COMMENT '類型:（experience:經驗 gold:財富）',
  `credit` mediumint(3) NOT NULL DEFAULT '0' COMMENT '富財或者經驗的曾減值',
  `mtime` int(11) NOT NULL COMMENT '操作時間戳',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`,`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_credit_history
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_count`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_count`;
CREATE TABLE `ts_user_count` (
  `uid` int(11) NOT NULL,
  `atme` mediumint(6) NOT NULL,
  `comment` mediumint(6) NOT NULL,
  PRIMARY KEY (`uid`),
  KEY `atme` (`uid`,`atme`),
  KEY `comment` (`uid`,`comment`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of ts_user_count
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_change_style`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_change_style`;
CREATE TABLE `ts_user_change_style` (
  `uid` int(11) unsigned NOT NULL COMMENT '用戶UID',
  `classname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '面板的樣式表名稱',
  `background` text CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT '膚的皮背景圖片地址',
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_change_style
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_category_link`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_category_link`;
CREATE TABLE `ts_user_category_link` (
  `user_category_link_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '使用者分類關聯表ID - 主鍵',
  `uid` int(11) NOT NULL COMMENT '使用者ID',
  `user_category_id` int(11) NOT NULL COMMENT '使用者分類ID',
  `sort` int(11) NOT NULL COMMENT '排序值',
  PRIMARY KEY (`user_category_link_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_category_link
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_blacklist`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_blacklist`;
CREATE TABLE `ts_user_blacklist` (
  `uid` int(11) NOT NULL COMMENT '用戶UID',
  `fid` int(11) NOT NULL COMMENT '被遮蔽的使用者UID',
  `ctime` int(11) NOT NULL COMMENT '操作時間戳',
  UNIQUE KEY `uid` (`uid`,`fid`),
  KEY `fid` (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_blacklist
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user_app`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user_app`;
CREATE TABLE `ts_user_app` (
  `user_app_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '鍵主ID',
  `app_id` int(11) NOT NULL COMMENT '用應ID',
  `uid` int(11) NOT NULL COMMENT '安裝者UID',
  `display_order` int(5) NOT NULL DEFAULT '0' COMMENT '裝安的應用排序',
  `ctime` int(11) DEFAULT NULL COMMENT '裝安時間戳',
  `type` varchar(100) DEFAULT NULL COMMENT '用應分類',
  `oauth_token` varchar(255) DEFAULT NULL COMMENT 'API的口令',
  `oauth_token_secret` varchar(255) DEFAULT NULL COMMENT 'API的金鑰',
  `inweb` tinyint(2) NOT NULL DEFAULT '1' COMMENT '是否網頁端，1是，0不是',
  PRIMARY KEY (`user_app_id`),
  KEY `app_id` (`app_id`,`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user_app
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_user`
-- ----------------------------
DROP TABLE IF EXISTS `ts_user`;
CREATE TABLE `ts_user` (
  `uid` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵UID',
  `login` varchar(255) DEFAULT NULL COMMENT '登入emial',
  `password` varchar(255) DEFAULT NULL COMMENT '使用者密碼的md5摘要',
  `login_salt` char(5) DEFAULT NULL COMMENT '10000 到 99999之間的隨機數，加密密碼時使用',
  `uname` varchar(255) DEFAULT NULL COMMENT '使用者名',
  `email` varchar(255) DEFAULT NULL COMMENT '使用者email',
  `sex` tinyint(1) NOT NULL DEFAULT '0' COMMENT '性別 1：男、2：女',
  `location` varchar(255) DEFAULT NULL COMMENT '所在省市的字元串',
  `is_audit` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否通過稽覈：0-未通過，1-已通過',
  `is_active` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已啟用 1：啟用、0：未啟用',
  `is_init` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否初始化使用者資料 1：初始化、0：未初始化',
  `ctime` int(11) DEFAULT NULL COMMENT '註冊時間',
  `identity` tinyint(1) NOT NULL DEFAULT '1' COMMENT '身份標識（1：使用者，2：組織）',
  `api_key` varchar(255) DEFAULT NULL COMMENT '使用者的api_key用於移動端',
  `domain` char(80) NOT NULL COMMENT '保留欄位，用於使用者分表',
  `province` mediumint(6) NOT NULL DEFAULT '0' COMMENT '省ID、關聯ts_area表',
  `city` int(5) NOT NULL COMMENT '城市ID，關聯ts_area表',
  `area` int(5) NOT NULL COMMENT '地區ID，關聯ts_area表',
  `reg_ip` varchar(64) DEFAULT '127.0.0.1' COMMENT '冊注IP',
  `lang` varchar(64) DEFAULT 'zh-tw' COMMENT '言語',
  `timezone` varchar(10) DEFAULT 'ROC' COMMENT '時區',
  `is_del` tinyint(2) NOT NULL COMMENT '是否禁用，0不禁用，1：禁用',
  `first_letter` char(1) DEFAULT NULL COMMENT '使用者名稱的首字母',
  `intro` varchar(255) DEFAULT NULL COMMENT '用戶簡介',
  `last_login_time` int(11) DEFAULT '0' COMMENT '用戶最後一次登入時間',
  `last_feed_id` int(11) DEFAULT '0' COMMENT '用戶最後發表的微博ID',
  `last_post_time` int(11) NOT NULL DEFAULT '0' COMMENT '用戶最後發表微博的時間',
  `search_key` varchar(500) DEFAULT NULL COMMENT '搜索欄位',
  `invite_code` varchar(120) DEFAULT NULL COMMENT '邀請註冊碼',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `email` (`email`),
  KEY `login` (`login`),
  KEY `uname` (`uname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_tips`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tips`;
CREATE TABLE `ts_tips` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `source_id` int(10) NOT NULL COMMENT '資源ID',
  `source_table` varchar(20) NOT NULL COMMENT '資源所在表',
  `uid` int(10) NOT NULL COMMENT '使用者ID',
  `type` tinyint(2) NOT NULL COMMENT '類型（0表示支援。1表示反對）',
  `ctime` int(11) NOT NULL COMMENT '添加時間',
  `ip` varchar(20) NOT NULL COMMENT '操作者IP',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_tips
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_tipoff_status`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tipoff_status`;
CREATE TABLE `ts_tipoff_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(11) DEFAULT NULL COMMENT '狀態值',
  `title` varchar(255) DEFAULT NULL COMMENT '狀態名稱',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_tipoff_status
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_template_record`
-- ----------------------------
DROP TABLE IF EXISTS `ts_template_record`;
CREATE TABLE `ts_template_record` (
  `tpl_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '模板使用者UID',
  `tpl_name` varchar(255) NOT NULL DEFAULT '' COMMENT '模板名',
  `tpl_alias` varchar(255) DEFAULT NULL COMMENT '模板別名',
  `type` varchar(255) DEFAULT NULL COMMENT '模板類型',
  `type2` varchar(255) DEFAULT NULL COMMENT '模板類型2',
  `data` text COMMENT '記錄模板變數資料',
  `ctime` int(11) DEFAULT NULL COMMENT '模板呼叫時間戳',
  PRIMARY KEY (`tpl_record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_template_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_template`
-- ----------------------------
DROP TABLE IF EXISTS `ts_template`;
CREATE TABLE `ts_template` (
  `tpl_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL COMMENT '模板名，使用英文，保證唯一性。格式建議：“類型_動作”，如“blog_add”或“credit_blog_add”',
  `alias` varchar(255) DEFAULT NULL COMMENT '模板別名，如“發表部落格”',
  `title` text COMMENT '標題模板，使用“{”和“}”包含變數名，如“{actor}做了{action}”',
  `body` text COMMENT '內容模板，使用“{”和“}”包含變數名，如“{actor}做了{action}增加了{volume}個{credit_type}”',
  `lang` varchar(255) NOT NULL DEFAULT 'zh' COMMENT '語言，與全局語言包一致，如“en”、“zh”等，目前只支援“zh”',
  `type` varchar(255) DEFAULT NULL COMMENT '模板類型，如blog,credit等',
  `type2` varchar(255) DEFAULT NULL COMMENT '模板類型2，備用類型，可留空。如“credit_blog”等',
  `is_cache` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否使用默認的模板快取系統進行快取，0：否；1：是',
  `ctime` int(11) DEFAULT NULL COMMENT '模板建立時間戳',
  PRIMARY KEY (`tpl_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_template
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_task_user`
-- ----------------------------
DROP TABLE IF EXISTS `ts_task_user`;
CREATE TABLE `ts_task_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `tid` int(11) DEFAULT NULL COMMENT '任務ID',
  `task_level` int(11) DEFAULT NULL COMMENT '任務等級',
  `task_type` varchar(255) DEFAULT NULL COMMENT '任務類型',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  `status` int(11) DEFAULT NULL COMMENT '完成狀態',
  `desc` varchar(255) DEFAULT NULL COMMENT '說明',
  `receive` int(11) DEFAULT '0' COMMENT '是否領取獎勵',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_task_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_task_receive`
-- ----------------------------
DROP TABLE IF EXISTS `ts_task_receive`;
CREATE TABLE `ts_task_receive` (
  `task_level` int(11) DEFAULT NULL COMMENT '任務等級',
  `task_type` int(11) DEFAULT NULL COMMENT '任務類型：每日任務 ，新手任務等等',
  `uid` int(11) DEFAULT NULL COMMENT '使用者ID',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_task_receive
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_task_custom`
-- ----------------------------
DROP TABLE IF EXISTS `ts_task_custom`;
CREATE TABLE `ts_task_custom` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自定義任務ID',
  `task_name` varchar(255) DEFAULT NULL COMMENT '任務名稱',
  `task_desc` varchar(255) DEFAULT NULL COMMENT '任務說明',
  `num` int(11) DEFAULT NULL COMMENT '剩餘領取數',
  `condition` varchar(255) DEFAULT NULL COMMENT '任務完成條件',
  `task_condition` varchar(255) DEFAULT NULL COMMENT '前置任務',
  `reward` varchar(255) DEFAULT NULL COMMENT '任務獎勵',
  `medal_id` int(11) DEFAULT NULL COMMENT '勳章ID',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_task_custom
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_tag`
-- ----------------------------
DROP TABLE IF EXISTS `ts_tag`;
CREATE TABLE `ts_tag` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵，標籤編號',
  `name` varchar(255) NOT NULL COMMENT '標簽名',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `tag_name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_system_update`
-- ----------------------------
DROP TABLE IF EXISTS `ts_system_update`;
CREATE TABLE `ts_system_update` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `version` varchar(100) NOT NULL,
  `package` varchar(100) NOT NULL,
  `description` text,
  `status` tinyint(4) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ts_system_update
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_search_key`
-- ----------------------------
DROP TABLE IF EXISTS `ts_search_key`;
CREATE TABLE `ts_search_key` (
  `kid` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL COMMENT '搜索的關鍵詞',
  `searchCount` int(11) unsigned NOT NULL DEFAULT '1' COMMENT '搜索次數',
  `resultCount` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '結果數',
  `suggest` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否顯示在建議列表中',
  `data` text COMMENT '擴展欄位',
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`kid`),
  UNIQUE KEY `key` (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_search_key
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_search`
-- ----------------------------
DROP TABLE IF EXISTS `ts_search`;
CREATE TABLE `ts_search` (
  `doc_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `app` varchar(30) DEFAULT NULL COMMENT '應用名',
  `type` varchar(50) DEFAULT NULL COMMENT '搜索類型',
  `string01` varchar(255) DEFAULT NULL COMMENT '文字擴展欄位',
  `string02` varchar(255) DEFAULT NULL COMMENT '文字擴展欄位',
  `string03` varchar(255) DEFAULT NULL COMMENT '文字擴展欄位',
  `string04` varchar(255) DEFAULT NULL COMMENT '文字擴展欄位',
  `string05` varchar(255) DEFAULT NULL COMMENT '文字擴展欄位',
  `int01` int(11) DEFAULT NULL COMMENT '數字擴展欄位',
  `int02` int(11) DEFAULT NULL COMMENT '數字擴展欄位',
  `int03` int(11) DEFAULT NULL COMMENT '數字擴展欄位',
  `int04` int(11) DEFAULT NULL COMMENT '數字擴展欄位',
  `int05` int(11) DEFAULT NULL COMMENT '數字擴展欄位',
  `int06` int(11) NOT NULL COMMENT '數字擴展欄位',
  `int07` int(11) NOT NULL COMMENT '數字擴展欄位',
  `int08` int(11) NOT NULL COMMENT '數字擴展欄位',
  `int09` int(11) NOT NULL COMMENT '數字擴展欄位',
  `int10` int(11) NOT NULL COMMENT '數字擴展欄位',
  `file_path` varchar(255) DEFAULT NULL COMMENT '附件路徑',
  `content` text COMMENT '搜索內容',
  `mtime` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `data` text NOT NULL COMMENT '序列化存儲的資料',
  PRIMARY KEY (`doc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_search
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_schedule`
-- ----------------------------
DROP TABLE IF EXISTS `ts_schedule`;
CREATE TABLE `ts_schedule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `task_to_run` varchar(255) NOT NULL COMMENT '計劃任務執行方法',
  `schedule_type` varchar(255) NOT NULL COMMENT '執行頻率',
  `modifier` varchar(255) DEFAULT NULL COMMENT '執行頻率,類型為MONTHLY時必須；ONCE時無效；其他時為可選，默認為1',
  `dirlist` varchar(255) DEFAULT NULL COMMENT '指定周或月的一天或多天。只與WEEKLY和MONTHLY共同使用時有效，其他時忽略。',
  `month` varchar(255) DEFAULT NULL COMMENT '指定一年中的一個月或多個月.只在schedule_type=MONTHLY時有效，其他時忽略。當modifier=LASTDAY時必須，其他時可選。有效值：Jan - Dec，默認為*(每個月)',
  `start_datetime` datetime NOT NULL COMMENT '開始時間',
  `end_datetime` datetime DEFAULT NULL COMMENT '結束時間',
  `last_run_time` datetime DEFAULT NULL COMMENT '最近執行時間',
  `info` varchar(255) DEFAULT NULL COMMENT '對計劃任務的簡要描述',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_schedule
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_pic_show`
-- ----------------------------
DROP TABLE IF EXISTS `ts_pic_show`;
CREATE TABLE `ts_pic_show` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(255) DEFAULT NULL COMMENT '照片URL地址',
  `title` varchar(255) DEFAULT NULL COMMENT '照片標題',
  `desc` varchar(255) DEFAULT NULL COMMENT '照片摘要',
  `target` varchar(20) DEFAULT NULL COMMENT '開啟方式',
  `ctime` int(11) NOT NULL COMMENT '創建時間',
  `attachId` int(10) DEFAULT NULL COMMENT '照片ID',
  `status` int(2) NOT NULL DEFAULT '0' COMMENT '狀態',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_pic_show
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_online_stats`
-- ----------------------------
DROP TABLE IF EXISTS `ts_online_stats`;
CREATE TABLE `ts_online_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL COMMENT '日期',
  `total_users` int(11) NOT NULL DEFAULT '0' COMMENT '總使用者數',
  `total_guests` int(11) NOT NULL DEFAULT '0' COMMENT '總遊客數',
  `total_pageviews` int(11) NOT NULL DEFAULT '0' COMMENT '頁面訪問次數',
  `most_online_users` int(11) NOT NULL DEFAULT '0' COMMENT '最多線上使用者數',
  `most_online_guests` int(11) NOT NULL DEFAULT '0' COMMENT '最多遊客線上數',
  `most_online_time` int(11) DEFAULT NULL COMMENT '最大線上時間',
  `most_online` int(11) NOT NULL COMMENT '最大線上人數',
  PRIMARY KEY (`id`),
  KEY `day` (`day`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_online_stats
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_online_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ts_online_logs`;
CREATE TABLE `ts_online_logs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `day` date NOT NULL COMMENT '日期',
  `uid` int(11) NOT NULL COMMENT '使用者ID',
  `uname` varchar(50) NOT NULL COMMENT '使用者名稱',
  `action` varchar(255) NOT NULL COMMENT '訪問地址',
  `refer` text NOT NULL COMMENT '來源頁面',
  `isGuest` tinyint(3) NOT NULL COMMENT '是否遊客',
  `isIntranet` tinyint(3) NOT NULL COMMENT '是否內部使用者',
  `ip` varchar(20) NOT NULL COMMENT 'IP',
  `agent` varchar(50) NOT NULL COMMENT '瀏覽器',
  `ext` varchar(20) NOT NULL COMMENT '擴展欄位',
  `statsed` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否已經統計過',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_online_logs
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_online`
-- ----------------------------
DROP TABLE IF EXISTS `ts_online`;
CREATE TABLE `ts_online` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `uname` varchar(50) NOT NULL COMMENT '使用者名',
  `app` char(20) DEFAULT NULL COMMENT '應用',
  `ip` varchar(20) DEFAULT NULL COMMENT 'IP地址',
  `agent` char(20) DEFAULT NULL COMMENT '訪問的瀏覽器',
  `activeTime` int(11) DEFAULT NULL COMMENT '訪問時間',
  PRIMARY KEY (`id`),
  KEY `active_time` (`activeTime`),
  KEY `uid_ip` (`uid`,`ip`),
  KEY `uid_activeTime` (`uid`,`activeTime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_online
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_notify_message`
-- ----------------------------
DROP TABLE IF EXISTS `ts_notify_message`;
CREATE TABLE `ts_notify_message` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT 'UID',
  `node` varchar(50) NOT NULL COMMENT '節點名稱',
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `title` varchar(250) NOT NULL COMMENT '標題',
  `body` text NOT NULL COMMENT '內容',
  `ctime` int(11) NOT NULL COMMENT '添加時間',
  `is_read` tinyint(2) NOT NULL COMMENT '是否已讀',
  PRIMARY KEY (`id`),
  KEY `uid_read` (`uid`,`is_read`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_notify_message
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_notify_email`
-- ----------------------------
DROP TABLE IF EXISTS `ts_notify_email`;
CREATE TABLE `ts_notify_email` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `uid` int(10) NOT NULL COMMENT 'UiD',
  `node` varchar(50) NOT NULL COMMENT '節點名稱',
  `appname` varchar(50) NOT NULL COMMENT '應用名稱',
  `email` varchar(250) NOT NULL COMMENT '郵件接受地址',
  `is_send` tinyint(2) NOT NULL COMMENT '是否已經發送',
  `title` varchar(250) NOT NULL COMMENT '郵件標題',
  `body` text NOT NULL COMMENT '郵件內容',
  `ctime` int(11) NOT NULL COMMENT '添加時間',
  `sendtime` int(11) NOT NULL COMMENT '發送時間',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_notify_email
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_mobile_user`
-- ----------------------------
DROP TABLE IF EXISTS `ts_mobile_user`;
CREATE TABLE `ts_mobile_user` (
  `uid` int(11) unsigned NOT NULL COMMENT '用戶ID',
  `iphone_device_token` varchar(255) DEFAULT NULL COMMENT 'iPhone的機器碼（用於推送訊息）',
  `ipad_device_token` varchar(255) DEFAULT NULL COMMENT 'iPad的機器碼（用於推送訊息）',
  `android_device_token` varchar(255) DEFAULT NULL COMMENT 'Android的機器碼（用於推送訊息）',
  `iphone_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'iPhone否是開啟推送',
  `ipad_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'iPad是開啟推送',
  `android_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT 'Android否是開啟推送',
  `last_latitude` float(10,6) DEFAULT NULL COMMENT '經度',
  `last_longitude` float(10,6) DEFAULT NULL COMMENT '緯度',
  `last_checkin` int(11) unsigned DEFAULT NULL COMMENT '後最簽到時間（訪問即簽到）',
  `nickname` varchar(255) DEFAULT NULL COMMENT '使用者昵稱，預留匿名功能',
  `infomation` varchar(255) DEFAULT NULL COMMENT '使用者簡介，預留',
  `checkin_count` int(11) unsigned DEFAULT '0' COMMENT '簽到次數',
  `sex` tinyint(1) unsigned DEFAULT '1' COMMENT '性別：1男、2女',
  PRIMARY KEY (`uid`),
  UNIQUE KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ts_mobile_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_mobile_token`
-- ----------------------------
DROP TABLE IF EXISTS `ts_mobile_token`;
CREATE TABLE `ts_mobile_token` (
  `uid` int(11) DEFAULT NULL,
  `token` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `device_type` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `mtime` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ts_mobile_token
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_mobile_message`
-- ----------------------------
DROP TABLE IF EXISTS `ts_mobile_message`;
CREATE TABLE `ts_mobile_message` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL,
  `message` text,
  `device_type` varchar(255) DEFAULT NULL,
  `ctime` int(11) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of ts_mobile_message
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_message_member`
-- ----------------------------
DROP TABLE IF EXISTS `ts_message_member`;
CREATE TABLE `ts_message_member` (
  `list_id` int(11) unsigned NOT NULL COMMENT '私信ID',
  `member_uid` int(11) unsigned NOT NULL COMMENT '參與私信的使用者UID',
  `new` smallint(8) unsigned NOT NULL DEFAULT '0' COMMENT '未讀訊息數',
  `message_num` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '訊息總數',
  `ctime` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '該參與者最後會話時間',
  `list_ctime` int(11) unsigned NOT NULL COMMENT '私信最後會話時間',
  `is_del` int(11) NOT NULL COMMENT '是否刪除（假的刪除）',
  PRIMARY KEY (`list_id`,`member_uid`),
  KEY `new` (`new`),
  KEY `ctime` (`ctime`),
  KEY `list_ctime` (`list_ctime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_message_member
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_message_list`
-- ----------------------------
DROP TABLE IF EXISTS `ts_message_list`;
CREATE TABLE `ts_message_list` (
  `list_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '私信ID',
  `from_uid` int(11) unsigned NOT NULL COMMENT '私信發起者UID',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '私信類別，1：一對一；2：多人',
  `title` varchar(255) DEFAULT NULL COMMENT '標題',
  `member_num` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '參與者數量',
  `min_max` varchar(255) DEFAULT NULL COMMENT '參與者UID正序排列，以下劃線“_”連結',
  `mtime` int(11) unsigned NOT NULL COMMENT '發起時間戳',
  `last_message` text NOT NULL COMMENT '最新的一條會話',
  PRIMARY KEY (`list_id`),
  KEY `type` (`type`),
  KEY `min_max` (`min_max`),
  KEY `from_uid` (`from_uid`,`mtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_message_list
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_message_content`
-- ----------------------------
DROP TABLE IF EXISTS `ts_message_content`;
CREATE TABLE `ts_message_content` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '私信內對話ID',
  `list_id` int(11) unsigned NOT NULL COMMENT '私信ID',
  `from_uid` int(11) unsigned NOT NULL COMMENT '會話釋出者UID',
  `content` text COMMENT '會話內容',
  `is_del` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否刪除，0：否；1：是',
  `mtime` int(11) unsigned NOT NULL COMMENT '會話釋出時間',
  PRIMARY KEY (`message_id`),
  KEY `list_id` (`list_id`,`is_del`,`mtime`),
  KEY `list_id_2` (`list_id`,`mtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_message_content
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_medal_user`
-- ----------------------------
DROP TABLE IF EXISTS `ts_medal_user`;
CREATE TABLE `ts_medal_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  `medal_id` int(11) NOT NULL COMMENT '勳章ID',
  `desc` varchar(255) DEFAULT NULL COMMENT '勳章獲得說明',
  `ctime` int(11) DEFAULT NULL COMMENT '獲取勳章時間',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uid_medal_id` (`uid`,`medal_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_medal_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_login_record`
-- ----------------------------
DROP TABLE IF EXISTS `ts_login_record`;
CREATE TABLE `ts_login_record` (
  `login_record_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `ip` varchar(15) DEFAULT NULL COMMENT 'IP',
  `place` varchar(255) DEFAULT NULL COMMENT '地點',
  `ctime` int(11) DEFAULT NULL COMMENT '時間',
  `locktime` int(11) NOT NULL COMMENT '賬號鎖定截至日期',
  PRIMARY KEY (`login_record_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_login_record
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_login_logs`
-- ----------------------------
DROP TABLE IF EXISTS `ts_login_logs`;
CREATE TABLE `ts_login_logs` (
  `login_logs_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '登入日誌ID - 主鍵',
  `uid` int(11) DEFAULT NULL COMMENT '使用者ID',
  `ip` varchar(15) DEFAULT NULL COMMENT '登入IP',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  PRIMARY KEY (`login_logs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_login_logs
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_login`
-- ----------------------------
DROP TABLE IF EXISTS `ts_login`;
CREATE TABLE `ts_login` (
  `login_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  `type_uid` varchar(255) NOT NULL COMMENT '授權登陸使用者名',
  `type` char(80) NOT NULL COMMENT '登陸類型',
  `oauth_token` varchar(150) DEFAULT NULL COMMENT '授權賬號',
  `oauth_token_secret` varchar(150) DEFAULT NULL COMMENT '授權密碼',
  `is_sync` tinyint(1) NOT NULL COMMENT '是否同步動態',
  PRIMARY KEY (`login_id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_login
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_invite_code`
-- ----------------------------
DROP TABLE IF EXISTS `ts_invite_code`;
CREATE TABLE `ts_invite_code` (
  `invite_code_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主鍵ID',
  `inviter_uid` int(11) unsigned NOT NULL COMMENT '邀請人UID',
  `code` varchar(120) NOT NULL COMMENT '邀請碼',
  `is_used` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已使用',
  `is_admin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否為管理員邀請',
  `type` char(40) NOT NULL COMMENT '邀請碼類型',
  `is_received` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否已接受邀請',
  `receiver_uid` int(11) NOT NULL DEFAULT '0' COMMENT '邀請人UID',
  `receiver_email` varchar(50) DEFAULT NULL COMMENT '邀請人註冊郵箱',
  `ctime` int(11) NOT NULL COMMENT '註冊時間',
  PRIMARY KEY (`invite_code_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_invite_code
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_find_password`
-- ----------------------------
DROP TABLE IF EXISTS `ts_find_password`;
CREATE TABLE `ts_find_password` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '使用者ID',
  `email` varchar(100) CHARACTER SET latin1 NOT NULL COMMENT '使用者email',
  `code` varchar(255) CHARACTER SET latin1 NOT NULL COMMENT '改密字元串',
  `is_used` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否已使用',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_find_password
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feedback_type`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feedback_type`;
CREATE TABLE `ts_feedback_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '反饋類型ID',
  `type_name` varchar(255) NOT NULL COMMENT '反饋類型名稱',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feedback_type
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feedback`;
CREATE TABLE `ts_feedback` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `feedbacktype` int(11) DEFAULT NULL COMMENT '反饋類型',
  `feedback` varchar(255) DEFAULT NULL COMMENT '反饋內容',
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `cTime` int(11) DEFAULT NULL COMMENT '創建時間',
  `mTime` int(11) DEFAULT NULL COMMENT '修改時間',
  `type` int(1) DEFAULT NULL COMMENT '狀態',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feed_topic_link`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed_topic_link`;
CREATE TABLE `ts_feed_topic_link` (
  `feed_topic_id` int(11) NOT NULL AUTO_INCREMENT,
  `feed_id` int(11) NOT NULL COMMENT '動態ID',
  `topic_id` int(11) NOT NULL COMMENT '話題ID',
  `type` varchar(255) NOT NULL DEFAULT '0' COMMENT '動態類型ID',
  PRIMARY KEY (`feed_topic_id`),
  KEY `topic_type` (`topic_id`,`type`),
  KEY `weibo` (`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feed_topic_link
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feed_topic`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed_topic`;
CREATE TABLE `ts_feed_topic` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '話題ID',
  `topic_name` varchar(150) NOT NULL COMMENT '話題標題',
  `count` int(11) NOT NULL COMMENT '關聯的動態數',
  `ctime` int(11) NOT NULL COMMENT '創建時間',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '狀態',
  `lock` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否鎖定',
  `domain` varchar(100) NOT NULL COMMENT '個性化地址',
  `recommend` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推薦',
  `recommend_time` int(11) DEFAULT '0' COMMENT '推薦時間',
  `des` text COMMENT '詳細內容',
  `outlink` varchar(100) DEFAULT NULL COMMENT '關聯連結',
  `pic` varchar(255) DEFAULT NULL COMMENT '關聯圖片',
  `essence` tinyint(1) DEFAULT '0' COMMENT '是否精華',
  `note` varchar(255) DEFAULT NULL COMMENT '摘要',
  `topic_user` varchar(255) DEFAULT NULL COMMENT '話題人物推薦',
  PRIMARY KEY (`topic_id`),
  KEY `count` (`count`),
  KEY `name` (`topic_name`,`count`),
  KEY `recommend` (`recommend`,`lock`,`count`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feed_topic
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feed_digg`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed_digg`;
CREATE TABLE `ts_feed_digg` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL,
  `feed_id` int(11) NOT NULL,
  `cTime` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Records of ts_feed_digg
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feed_data`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed_data`;
CREATE TABLE `ts_feed_data` (
  `feed_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '關聯ts_feed表，feed_id',
  `feed_data` text COMMENT '關聯ts_feed表，動態資料，序列化儲存',
  `client_ip` char(15) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL COMMENT '客戶端IP',
  `feed_content` text COMMENT '純微博內容',
  `from_data` text COMMENT '微博來源',
  PRIMARY KEY (`feed_id`),
  KEY `feed_id` (`feed_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feed_data
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_feed`
-- ----------------------------
DROP TABLE IF EXISTS `ts_feed`;
CREATE TABLE `ts_feed` (
  `feed_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '動態ID',
  `uid` int(11) NOT NULL COMMENT '產生動態的使用者UID',
  `type` char(50) DEFAULT NULL COMMENT 'feed類型.由發表feed的程式控制',
  `app` char(30) DEFAULT 'public' COMMENT 'feed來源的appname',
  `app_row_table` varchar(50) NOT NULL DEFAULT 'feed' COMMENT '關聯資源所在的表',
  `app_row_id` int(11) NOT NULL DEFAULT '0' COMMENT '關聯的來源ID（如部落格的id）',
  `publish_time` int(11) NOT NULL COMMENT '產生時間戳',
  `is_del` int(2) NOT NULL DEFAULT '0' COMMENT '是否刪除 默認為0',
  `from` tinyint(2) NOT NULL DEFAULT '0' COMMENT '客戶端類型，0：網站；1：手機網頁版；2：android；3：iphone',
  `comment_count` int(10) DEFAULT '0' COMMENT '評論數',
  `repost_count` int(10) DEFAULT '0' COMMENT '分享數',
  `comment_all_count` int(10) DEFAULT '0' COMMENT '全部評論數目',
  `digg_count` int(11) DEFAULT '0',
  `is_repost` int(2) DEFAULT '0' COMMENT '是否轉發 0-否  1-是',
  `is_audit` int(2) DEFAULT '1' COMMENT '是否已稽覈 0-未稽覈 1-已稽覈',
  PRIMARY KEY (`feed_id`),
  KEY `is_del` (`is_del`,`publish_time`),
  KEY `uid` (`uid`,`is_del`,`publish_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_feed
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_diy_widget`
-- ----------------------------
DROP TABLE IF EXISTS `ts_diy_widget`;
CREATE TABLE `ts_diy_widget` (
  `widgetId` int(11) NOT NULL AUTO_INCREMENT COMMENT '模組ID',
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  `pluginId` varchar(255) NOT NULL COMMENT 'DIY中關聯的ID',
  `pageId` int(11) NOT NULL COMMENT '頁面ID',
  `channelId` int(11) NOT NULL COMMENT '頻道ID',
  `taglib` text COMMENT '標籤欄內容',
  `content` text COMMENT '模組內容',
  `ext` text COMMENT '模組參數',
  `cache` text COMMENT '快取內容',
  `cacheTime` int(11) NOT NULL DEFAULT '0' COMMENT '快取時間',
  `cTime` int(11) DEFAULT NULL COMMENT '創建時間',
  `mTime` int(11) DEFAULT NULL COMMENT '修改時間',
  `status` tinyint(1) DEFAULT '0' COMMENT '狀態',
  PRIMARY KEY (`widgetId`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_diy_widget
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_diy_page`
-- ----------------------------
DROP TABLE IF EXISTS `ts_diy_page`;
CREATE TABLE `ts_diy_page` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `domain` varchar(100) NOT NULL COMMENT '頁面域名',
  `page_name` varchar(30) NOT NULL COMMENT '頁面名稱',
  `layout_data` text COMMENT '頁面佈局資料',
  `widget_data` text COMMENT '頁面裡面模組資料',
  `canvas` varchar(255) DEFAULT NULL COMMENT '應用畫布',
  `lock` tinyint(1) DEFAULT '0' COMMENT '是否鎖定不可以刪除',
  `status` tinyint(1) DEFAULT '1' COMMENT '是否開放使用者訪問',
  `guest` tinyint(1) DEFAULT '1' COMMENT '遊客是否可以訪問',
  `visit_count` int(11) unsigned DEFAULT '0' COMMENT '瀏覽次數',
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `manager` varchar(255) DEFAULT '' COMMENT '管理員',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  `seo_title` varchar(255) DEFAULT NULL COMMENT '頁面seo標題',
  `seo_keywords` varchar(255) DEFAULT NULL COMMENT '頁面seo關鍵字',
  `seo_description` varchar(500) DEFAULT NULL COMMENT '頁面seo簡介',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_diy_page
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_department`
-- ----------------------------
DROP TABLE IF EXISTS `ts_department`;
CREATE TABLE `ts_department` (
  `department_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL COMMENT '部門名',
  `parent_dept_id` int(11) NOT NULL DEFAULT '0' COMMENT '上級部門的ID',
  `display_order` int(11) NOT NULL DEFAULT '0' COMMENT '在同級部門中的排序',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  PRIMARY KEY (`department_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_department
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_denounce`
-- ----------------------------
DROP TABLE IF EXISTS `ts_denounce`;
CREATE TABLE `ts_denounce` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `from` varchar(20) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '資源來源位置',
  `aid` int(10) NOT NULL COMMENT '資源ID',
  `state` tinyint(3) NOT NULL COMMENT '狀態',
  `uid` int(10) NOT NULL COMMENT '舉報人',
  `fuid` int(10) NOT NULL COMMENT '被舉報人',
  `reason` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '舉報原因',
  `content` text CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '舉報內容',
  `ctime` int(10) NOT NULL COMMENT '舉報時間',
  `source_url` varchar(255) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL COMMENT '資源來源頁面',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_denounce
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_credit_user`
-- ----------------------------
DROP TABLE IF EXISTS `ts_credit_user`;
CREATE TABLE `ts_credit_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL COMMENT '使用者UID',
  `score` int(11) DEFAULT NULL COMMENT '積分總值',
  `experience` int(11) DEFAULT NULL COMMENT '經驗總值',
  PRIMARY KEY (`id`),
  KEY `uid` (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_credit_user
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_comment`
-- ----------------------------
DROP TABLE IF EXISTS `ts_comment`;
CREATE TABLE `ts_comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵，評論編號',
  `app` char(15) NOT NULL COMMENT '所屬應用',
  `table` char(15) NOT NULL COMMENT '被評論的內容所存儲的表',
  `row_id` int(11) NOT NULL COMMENT '應用進行評論的內容的編號',
  `app_uid` int(11) NOT NULL DEFAULT '0' COMMENT '應用內進行評論的內容的作者的編號',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '評論者編號',
  `content` text NOT NULL COMMENT '評論內容',
  `to_comment_id` int(11) NOT NULL DEFAULT '0' COMMENT '被回覆的評論的編號',
  `to_uid` int(11) NOT NULL DEFAULT '0' COMMENT '被回覆的評論的作者的編號',
  `data` text NOT NULL COMMENT '所評論的內容的相關參數（序列化存儲）',
  `ctime` int(11) NOT NULL COMMENT '評論釋出的時間',
  `is_del` tinyint(1) NOT NULL DEFAULT '0' COMMENT '標記刪除（0：沒刪除，1：已刪除）',
  `client_type` tinyint(2) NOT NULL COMMENT '客戶端類型，0：網站；1：手機網頁版；2：android；3：iphone',
  `is_audit` tinyint(1) DEFAULT '1' COMMENT '是否已稽覈 0-未稽覈 1-已稽覈',
  `storey` int(11) DEFAULT '0' COMMENT '評論絕對樓層',
  PRIMARY KEY (`comment_id`),
  KEY `app` (`table`,`is_del`,`row_id`),
  KEY `app_3` (`app_uid`,`to_uid`,`is_del`,`table`),
  KEY `app_2` (`uid`,`is_del`,`table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_comment
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_collection`
-- ----------------------------
DROP TABLE IF EXISTS `ts_collection`;
CREATE TABLE `ts_collection` (
  `collection_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` int(5) NOT NULL COMMENT '使用者ID',
  `source_id` int(11) NOT NULL COMMENT '資源ID\r\n',
  `source_table_name` varchar(255) NOT NULL COMMENT '資源所在表',
  `source_app` varchar(255) NOT NULL COMMENT '資源所在應用',
  `ctime` int(11) NOT NULL COMMENT '收藏時間',
  PRIMARY KEY (`collection_id`),
  UNIQUE KEY `cacheId` (`uid`,`source_id`,`source_table_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_collection
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_check_info`
-- ----------------------------
DROP TABLE IF EXISTS `ts_check_info`;
CREATE TABLE `ts_check_info` (
  `uid` int(11) DEFAULT NULL COMMENT '使用者UID',
  `con_num` int(11) DEFAULT '1' COMMENT '連續簽到次數',
  `total_num` int(11) DEFAULT '1' COMMENT '總簽到次數',
  `ctime` int(11) DEFAULT '0' COMMENT '簽到時間'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_check_info
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_channel_follow
-- ----------------------------

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
-- Table structure for `ts_atme`
-- ----------------------------
DROP TABLE IF EXISTS `ts_atme`;
CREATE TABLE `ts_atme` (
  `atme_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵，@我的編號',
  `app` char(15) NOT NULL COMMENT '所屬應用',
  `table` char(15) NOT NULL COMMENT '存儲應用內容的表名',
  `row_id` int(11) NOT NULL COMMENT '應用含有@的內容的編號',
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '被@的使用者的編號',
  PRIMARY KEY (`atme_id`),
  KEY `app_2` (`uid`,`table`),
  KEY `app_3` (`table`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_atme
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_app_tag`
-- ----------------------------
DROP TABLE IF EXISTS `ts_app_tag`;
CREATE TABLE `ts_app_tag` (
  `app` char(15) NOT NULL COMMENT '所屬應用',
  `table` char(15) NOT NULL COMMENT '所屬表名',
  `row_id` int(11) DEFAULT '0' COMMENT '所屬應用的內容的編號或者使用者編號',
  `tag_id` int(11) NOT NULL COMMENT 'Tag 編號',
  UNIQUE KEY `app` (`table`,`row_id`,`tag_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_app_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `ts_ad`
-- ----------------------------
DROP TABLE IF EXISTS `ts_ad`;
CREATE TABLE `ts_ad` (
  `ad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '廣告ID，主鍵',
  `title` varchar(255) DEFAULT NULL COMMENT '廣告標題',
  `place` tinyint(1) NOT NULL DEFAULT '0' COMMENT '廣告位置：0-中部；1-頭部；2-左下；3-右下；4-底部；5-右上；',
  `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效；0-無效；1-有效；',
  `is_closable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否關閉，目前沒有使用。',
  `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
  `mtime` int(11) DEFAULT NULL COMMENT '更新時間',
  `display_order` smallint(2) NOT NULL DEFAULT '0' COMMENT '排序值',
  `display_type` tinyint(1) unsigned DEFAULT '1' COMMENT '廣告類型：1 - HTML；2 - 程式碼；3 - 輪播',
  `content` text COMMENT '廣告位內容',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ts_ad
-- ----------------------------

-- ----------------------------
-- Records of ts_lang
-- ----------------------------
INSERT INTO `ts_lang` VALUES ('1', 'NOTIFY_REGISTER_ACTIVE_CONTENT', 'PUBLIC', '0', 'Hi，{name}，欢迎加入 <a href=\"{site_url}\"  style=\"text-decoration:none;color:#3366cc\">{site}</a>，请立即点击下列按钮激活您的帐号。<br /><br />\r\n<a href=\"{activeurl}\" target=\"_blank\" style=\"background:#0F8CA8;padding:5px 10px;color:#fff;text-decoration:none; width:80px;text-align:center;\">激活帐号</a>', 'Hi, {name}, Welcome to {site}, please click the button to activate your account.<br />\r\n<a href=\"{activeurl}\" target=\"_blank\">Click here to activate your account</a>', 'Hi，{name}，歡迎加入{site}，請立即點擊下列按鈕激活您的帳號。<br />\r\n<a href=\"{activeurl}\" target=\"_blank\">點此激活帳號</a>。');
INSERT INTO `ts_lang` VALUES ('2', 'NOTIFY_REGISTER_ACTIVE_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜您成功注册，请激活您的帐号', '[{site}] Congratulations,you have successfully registered, please activate your account', '[ {site} ]恭喜您成功註冊，請激活您的帳號');
INSERT INTO `ts_lang` VALUES ('3', 'NOTIFY_AUDIT_ERROR_CONTENT', 'PUBLIC', '0', '抱歉，您的帐号注册申请未能通过审核，感谢您对[ {site} ]的支持。若对审核结果有疑问，请联系<a href=\"{kfemail}\" >客服邮箱</a>。', 'Sorry, your account  registered application is not approved, thank you for your support. Any questions, please contact <a href=\"{kfemail}\" >Service mailbox</a> ', '抱歉，您的帳號註冊申請未能通過審核，感謝您對[ {site} ]的支持。若對審核結果有疑問，請聯繫<a href=\"{kfemail}\" >客服郵箱</a>。');
INSERT INTO `ts_lang` VALUES ('4', 'NOTIFY_AUDIT_ERROR_TITLE', 'PUBLIC', '0', '[ {site} ]抱歉，您的帐号注册申请被驳回。', '[{site}] Sorry, your account registration application was rejected.', '[ {site} ]抱歉，您的帳號註冊申請被駁回。');
INSERT INTO `ts_lang` VALUES ('5', 'NOTIFY_AUDIT_OK_CONTENT', 'PUBLIC', '0', 'Hi，{name}，欢迎加入 {site}，请立即点击下列按钮激活您的帐号。<br /><br />\r\n<a href=\"{activeurl}\" target=\"_blank\" style=\"background:#0F8CA8;padding:5px 10px;color:#fff;text-decoration:none; width:80px;text-align:center;\">点此激活帐号</a>', 'Hi, {name}, Welcome to {site},please click the button to activate your account.<br />\r\n<a href=\"{activeurl}\" target=\"_blank\">Click here to activate your account</a>', 'Hi，{name}，歡迎加入{site}，請立即點擊下列按鈕激活您的帳號。<br />\r\n<a href=\"{activeurl}\" target=\"_blank\">點此激活帳號</a>。');
INSERT INTO `ts_lang` VALUES ('6', 'NOTIFY_AUDIT_OK_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜您成功注册，请激活您的帐号', '[{site}] Congratulations,you have successfully registered, please activate your account', '[ {site} ]恭喜您成功註冊，請激活您的帳號');
INSERT INTO `ts_lang` VALUES ('7', 'NOTIFY_PASSWORD_RESET_CONTENT', 'PUBLIC', '0', '您刚刚提交了重置密码的申请，请点击下列按钮重置您的登录密码。<br /><br />\r\n<a href=\"{reseturl}\" target=\"_blank\" style=\"background:#0F8CA8;padding:5px 10px;color:#fff;text-decoration:none; width:80px;text-align:center;\">重置密码</a><br /><br />\r\n<font color=\"grey\">若该申请不是由您本人发起，请忽略该邮件。</font>', 'You just submitted applications for resetting your password, please click to reset your login password. \r\n【<a href=\"{reseturl}\" target=\"_blank\">Click here to reset your password</a>】<br />\r\n<font color=\"grey\">If the application is not submitted by yoursel', '您剛剛提交了重置密碼的申請，請點擊下列按鈕重置您的登錄密碼。<br /> \r\n【<a href=\"{reseturl}\" target=\"_blank\">點此重置密碼</a>】<br />\r\n<font color=\"grey\">若該申請不是由您本人發起，請忽略該郵件。</font>');
INSERT INTO `ts_lang` VALUES ('8', 'NOTIFY_PASSWORD_RESET_TITLE', 'PUBLIC', '0', '[ {site} ]请重置您的登录密码', '[{site}] Please reset your login password', '[ {site} ]請重置您的登錄密碼');
INSERT INTO `ts_lang` VALUES ('9', 'NOTIFY_PASSWORD_SETOK_CONTENT', 'PUBLIC', '0', '您的密码重置成功，请记住您的新密码。新密码：{newpass}。\r\n<br /><br /><br />\r\n<a href=\"{site_url}\" target=\"_blank\" style=\"text-decoration:none;color:#3366cc\">去网站看看>></a>', 'Your password successfully reset, please remember your new password. New password: {newpass}.<a href=\"{site_url}\" target=\"_blank\">Go to the website>></a>', '您的密碼重置成功，請記住您的新密碼。新密碼：{newpass}。<a href=\"{site_url}\" target=\"_blank\">去網站看看>></a>');
INSERT INTO `ts_lang` VALUES ('10', 'NOTIFY_PASSWORD_SETOK_TITLE', 'PUBLIC', '0', '[ {site} ]重置密码成功，请记住您的新密码', '[{site}] Reset the password successfully, remember your new password', '[ {site} ]重置密碼成功，請記住您的新密碼');
INSERT INTO `ts_lang` VALUES ('11', 'NOTIFY_USER_LOCK_CONTENT', 'PUBLIC', '0', '抱歉，由于您的密码输入错误超过6次，为了您的帐号安全，系统已将您的帐号暂时锁定，一小时后自动解锁，届时您可正常登录。', 'Sorry, your account have tried to login with your password too many times, for your account security, the system has temporarily locked your account and it will unlocked automatically after one hour, then you can login normally.', '抱歉，由於您的密碼輸入次數過多，為了您的帳號安全，系統已將您的帳號暫時鎖定，一小時後自動解鎖，屆時您可正常登錄。');
INSERT INTO `ts_lang` VALUES ('12', 'NOTIFY_USER_LOCK_TITLE', 'PUBLIC', '0', '[ {site} ]抱歉，您的帐号暂时被锁定', '[{site}] Sorry, your account has been temporarily locked', '[ {site} ]抱歉，您的帳號暫時被鎖定');
INSERT INTO `ts_lang` VALUES ('13', 'NOTIFY_ATME_CONTENT', 'PUBLIC', '0', '您的好友 <a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a> 刚刚在以下内容中提到了你：<br /><table style=\"width:480px;background:#eee;padding:10px\"><tbody><tr><td style=\"width:50px;float:left;margin-right:10px\"><img src=\"{face}\"></td><td style=\"font-size:12px;width:420px\"><div style=\"padding:0;margin:0\"><a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a>:</div><div style=\"padding:0;margin:0\">{content}</div><div style=\"padding:0;margin:0\"><span style=\"float:right\"><a href=\"{feed_url}\" style=\"text-decoration:none;color:#3366cc\">收藏</a>｜<a href=\"{feed_url}\" style=\"text-decoration:none;color:#3366cc\">转发</a>｜<a href=\"{feed_url}\" style=\"text-decoration:none;color:#3366cc\">评论</a></span><span>{publish_time}   来自  网站</span></div></td></tr></tbody></table><br />', 'Your colleagues {name} just mentioned you in the following content: {content}.<a href=\"{site_url}\" target=\"_blank\">Go to the website>></a>', '您的好友 {name} 剛剛在以下內容中提到了你：{content}。<a href=\"{site_url}\" target=\"_blank\">去網站看看>></a>');
INSERT INTO `ts_lang` VALUES ('14', 'NOTIFY_ATME_TITLE', 'PUBLIC', '0', '[ {site} ]{name}刚刚提到了您', '[{site}] {name} just mentioned you', '[ {site} ] {name} 剛剛提到了您');
INSERT INTO `ts_lang` VALUES ('15', 'NOTIFY_COMMENT_CONTENT', 'PUBLIC', '0', '您收到了新的评论<br /><table style=\"width:480px;background:#eee;padding:10px\"><tbody><tr><td style=\"width:50px;float:left;margin-right:10px\"><img src=\"{face}\"></td><td style=\"font-size:12px;width:420px\"><div style=\"padding:0;margin:0\"><a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a>:{content} ({ctime})</div><div style=\"padding:0;margin:0\">{comment_type}{source_content}</div><div style=\"padding:0;margin:0\"><span style=\"float:right\"><a href=\"{sourceurl}\" style=\"text-decoration:none;color:#3366cc\">查看原微博</a>｜<a href=\"{sourceurl}\" style=\"text-decoration:none;color:#3366cc\">回复</a></span><span>{source_ctime}   来自  网站</span></div></td></tr></tbody></table><br />', 'You received a new comment {content}. <a href=\"{sourceurl}\" target=\'_blank\'>Go to the website>></a>.', '您收到了新的評論：{content}。<a href=\"{sourceurl}\" target=\'_blank\'>去網站看看>></a>');
INSERT INTO `ts_lang` VALUES ('16', 'NOTIFY_COMMENT_TITLE', 'PUBLIC', '0', '[ {site} ]您收到了新的评论', '[{site}] You received a new comment', '[ {site} ]您收到了新的評論');
INSERT INTO `ts_lang` VALUES ('17', 'NOTIFY_NEW_MESSAGE_CONTENT', 'PUBLIC', '0', '您收到1封新的私信<br /><table style=\"width:480px;background:#eee;padding:10px\"><tbody><tr><td style=\"width:50px;float:left;margin-right:10px\"><img src=\"{face}\"></td><td style=\"font-size:12px;width:420px\"><div style=\"padding:0;margin:0\"><a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a>:{content}</div><div style=\"padding:0;margin:0\"><span style=\"float:right\"><a href=\"{source_url}\" style=\"text-decoration:none;color:#3366cc\">共1条私信</a>｜<a href=\"{source_url}\" style=\"text-decoration:none;color:#3366cc\">回复</a></span><span>{ctime}</span></div></td></tr></tbody></table><br />', 'You received a new private message.{content}.<a href=\"{site_url}\" target=\"_blank\">Go to the website>></a>', '您收到1封新的私信：{content}。<a href=\"{site_url}\" target=\"_blank\">去網站看看>></a>');
INSERT INTO `ts_lang` VALUES ('18', 'NOTIFY_NEW_MESSAGE_TITLE', 'PUBLIC', '0', '[ {site} ]您收到1封新的私信', '[{site}]You received a new private message', '[{site} ]您收到1封新的私信');
INSERT INTO `ts_lang` VALUES ('19', 'NOTIFY_SHARE_USER_CONTENT', 'PUBLIC', '0', '您的好友 {name} 给您转发了内容：【{content}】快去<a href=\"{sourceurl}\" target=\"_blank\">看看</a>吧。', 'Your friend {name} shared [{content}] with you ,<a href=\"{sourceurl}\" target=\'_blank\'> go and see it</a>.', '您的好友 {name} 給您轉發了內容：【{content}】，快去<a href=\"{sourceurl}\" target=\'_blank\'>看看</a>吧。');
INSERT INTO `ts_lang` VALUES ('20', 'NOTIFY_SHARE_USER_TITLE', 'PUBLIC', '0', '[ {site} ]您收到1条新的转发', '[{site}] You received a new share', '[ {site} ]您收到1條新的轉發');
INSERT INTO `ts_lang` VALUES ('21', 'NOTIFY_REGISTER_INVATE_CONTENT', 'PUBLIC', '0', '您的好友 <a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a> 邀请您加入<a href=\"{site_url}\" style=\"text-decoration:none;color:#3366cc;\"> {site}</a><br /><br /><table style=\"background:#eee;padding:10px\"><tbody><tr><td style=\"width:50px;float:left;\"><img src=\"{face}\"></td><td style=\"font-size:12px;padding:0 0 0 10px;\">{content}</td></tr></tbody></table><br /><a href=\"{invateurl}\" target=\"_blank\" style=\"background:#0f8ca8;width:80px;display:block;padding:5px 10px;color:#fff;text-align:center;font-size:12px;text-decoration:none;\">接受邀请</a>', 'Your friend {name} invited you to join {site} , he/she say：{content} [<a href=\"{invateurl}\" target=\"_blank\">Accept Invitation</a>]', '您的好友 {name} 邀請您加入 {site}，TA說“{content}”【<a href=\"{invateurl}\" target=\"_blank\">接受邀請</a>】');
INSERT INTO `ts_lang` VALUES ('22', 'NOTIFY_REGISTER_INVATE_TITLE', 'PUBLIC', '0', '[ {site} ]您的好友 {name} 邀请您注册网站', '[{site}] Your friend {name} invited you register for the site', '[ {site} ]您的好友 {name} 邀請您註冊網站');
INSERT INTO `ts_lang` VALUES ('23', 'NOTIFY_REGISTER_INVATE_OK_CONTENT', 'PUBLIC', '0', '您的好友 <a href=\"{space_url}\" target=\'_blank\' style=\"text-decoration:none;color:#3366cc;\">{name}</a> 刚刚注册开通了帐号，系统已经自动为你们互相关注，快去<a href=\"{space_url}\" target=\"_blank\">TA的主页</a>看看吧。', ' You friend {name} has just opened the account, the system has automatically mutual concern for you, go and say hello with TA. <a href=\"{spaceurl}\" target=\"_blank\">Go to his/her home page >></a>', '您邀請的好友 {name} 剛剛註冊開通了帳號，系統已經自動為你們互相關注，快去跟TA打聲招呼吧。<a href=\"{spaceurl}\" target=\"_blank\">去TA的主頁看看>></a>');
INSERT INTO `ts_lang` VALUES ('24', 'NOTIFY_REGISTER_INVATE_OK_TITLE', 'PUBLIC', '0', '[ {site} ]您的好友 {name} 开通了帐号', '[{site}] Your friend {name} opened the account', '[ {site} ]您的好友 {name} 開通了帳號');
INSERT INTO `ts_lang` VALUES ('25', 'NOTIFY_TASK_TODO_CONTENT', 'PUBLIC', '0', '您的好友 {name} 给您指派了一条任务【{taskname}】，<a href=\"{taskurl}\" target=\"_blank\">点击这里查看详情</a>。', 'Your friend {name} assigned a task [{taskname}] to you, <a href=\"{taskurl}\" target=\"_blank\">click here for details</a>.', '您的好友{name}給您指派了一條任務【{taskname}】，<a href=\"{taskurl}\" target=\"_blank\">點擊這裡查看詳情</a>。');
INSERT INTO `ts_lang` VALUES ('26', 'NOTIFY_TASK_TODO_TITLE', 'PUBLIC', '0', '[{site}]您收到了一条新的指派任务', '[{site}] You received a new assigned task', '[{site}]您收到了一條新的指派任務');
INSERT INTO `ts_lang` VALUES ('27', 'NOTIFY_TASK_SHARE_CATEGORY_CONTENT', 'PUBLIC', '0', '您的好友{site}共享了一个任务分类【{taskcate}】给您，<a href=\"{mytaskurl}\" target=\"_blank\">点击这里查看详情</a>。', 'Your friend {name}  shared a task category [{taskcate}] with you . <a href=\"{mytaskurl}\" target=\"_blank\">Click here for details</>.', '您的好友{name}給您共享了一個任務分類【{taskcate}】，<a href=\"{mytaskurl}\" target=\"_blank\">點擊這裡查看詳情</a>。');
INSERT INTO `ts_lang` VALUES ('28', 'NOTIFY_TASK_SHARE_CATEGORY_TITLE', 'PUBLIC', '0', '[{site}]您的好友{name}共享了一个任务分类给您', '[{site}] Your colleague {name} shared a task category with you', '[{site}]您收到了一個任務分類的共享');
INSERT INTO `ts_lang` VALUES ('29', 'NOTIFY_ASK_ANSWEME_CONTENT', 'PUBLIC', '0', '您收到了新的回答：{content}', 'You received a new comment.{content}', '您收到了新的回答：{content}');
INSERT INTO `ts_lang` VALUES ('30', 'NOTIFY_ASK_ANSWEME_TITLE', 'PUBLIC', '0', '[ {site} ]您收到了新的回答', '[{site}] You received a new comment', '[ {site} ]您收到了新的回答');
INSERT INTO `ts_lang` VALUES ('31', 'NOTIFY_ASK_STATUS_CHANGE_CONTENT', 'PUBLIC', '0', '您发起的问题【{askname}】状态由【{oldstatus}】改成【{newstatus}】，点击这里<a href=\"{askurl}\" target=\"_blank\"> 查看详情</a>', 'The state of your question [{askname}] has  changed from [{oldstatus}] to [{newstatus}], <a href=\"{askurl}\" target=\"_blank\">click here for details</a>', '您發起的問題【{askname}】狀態由【{oldstatus}】改成【{newstatus}】，點擊這裡<a href=\"{askurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('32', 'NOTIFY_ASK_STATUS_CHANGE_TITLE', 'PUBLIC', '0', '[ {site} ]您发起的问题状态已改变', '[{site}] The state of your question has changed', '[ {site} ]您發起的問題狀態已改變');
INSERT INTO `ts_lang` VALUES ('33', 'NOTIFY_ASK_SETDIST_CONTENT', 'PUBLIC', '0', '您发起的问题【{askname}】被管理员设为精华知识，点击这里<a href=\"{asklink}\" target=\"_blank\">查看详情</a>', 'Your question [{askname}] is set as essence knowledge, <a href=\"{asklink}\" target=\"_blank\">click here for details</a>', '您發起的問題【{askname}】被管理員設為精華知識，點擊這裡<a href=\"{asklink}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('34', 'NOTIFY_ASK_SETDIST_TITLE', 'PUBLIC', '0', '[ {site} ]您发起的问题被管理员设为精华知识', '[{site}] Your question is set as essence knowledge', '[ {site} ]您發起的問題被管理員設為精華知識');
INSERT INTO `ts_lang` VALUES ('35', 'NOTIFY_ASK_ANSWER_SHARE_CONTENT', 'PUBLIC', '0', '您的好友 {name} 刚刚在以下内容中提到了你：{content}', 'Your friend {name} just mentioned you in the following content: {content}', '您的好友 {name} 剛剛在以下內容中@提到了你：{content}');
INSERT INTO `ts_lang` VALUES ('36', 'NOTIFY_ASK_ANSWER_SHARE_TITLE', 'PUBLIC', '0', '[ {site} ] {name} 刚刚提到了您', '[{site}] {name} just mentioned you', '[ {site} ] {name} 剛剛提到了您');
INSERT INTO `ts_lang` VALUES ('37', 'NOTIFY_ASK_COMMENT_CONTENT', 'PUBLIC', '0', '您收到了新的评论：{content}', 'You received a new comment : {content}', '您收到了新的評論：{content}');
INSERT INTO `ts_lang` VALUES ('38', 'NOTIFY_ASK_COMMENT_TITLE', 'PUBLIC', '0', '[ {site} ]您收到了新的评论', '[{site}] You received a new comment', '[ {site} ]您收到了新的評論');
INSERT INTO `ts_lang` VALUES ('39', 'NOTIFY_ASK_BEST_ANSWER_CONTENT', 'PUBLIC', '0', '您在问题【{asktitle}】下的回复被设为最佳答案，获得{gold}财富值，点击这里<a href=\"{askurl}\" target=\"_blank\">查看详情</a>', 'Your reply for question [{asktitle}] is set as the best answer and you can get  {gold} wealth, <a href=\"{askurl}\" target=\"_blank\">click here for details</a>', '您在問題【{asktitle}】下的回復被設為最佳答案，獲得{gold}財富值，點擊這裡<a href=\"{askurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('40', 'NOTIFY_ASK_BEST_ANSWER_TITLE', 'PUBLIC', '0', '[ {site} ]您的回复被设为最佳答案', '[{site}] Your reply is set as best answer', '[ {site} ]您的回復被設為最佳答案');
INSERT INTO `ts_lang` VALUES ('41', 'NOTIFY_ASK_INVATE_CONTENT', 'PUBLIC', '0', '{name}发起了一个问题【{asktitle}】，邀请您回答，点击这里<a href=\"{askurl}\" target=\"_blank\">查看详情</a>', '{name} submitted a question [{asktitle}] and invite you to answer, <a href=\"{askurl}\" target=\"_blank\">click here for details</a>.', '{name}發起了一個問題【{asktitle}】，邀請您回答，點擊這裡<a href=\"{askurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('42', 'NOTIFY_ASK_INVATE_TITLE', 'PUBLIC', '0', '[ {site} ]您收到了一个新问题', '[{site}] You received a new problem', '[ {site} ]您收到了一個新問題');
INSERT INTO `ts_lang` VALUES ('43', 'NOTIFY_SUPPORT_POST_OK_CONTENT', 'PUBLIC', '0', '您提交的问题【{questiontitle}】已经成功发布，点击这里<a href=\"{questionurl}\" target=\"_blank\">查看详情</a>', 'The question you submitted [{questiontitle}] has been successfully published, <a href=\"{questionurl}\" target=\"_blank\">click here for details</a>', '您提交的問題【{questiontitle}】已經成功發布，點擊這裡<a href=\"{questionurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('44', 'NOTIFY_SUPPORT_POST_OK_TITLE', 'PUBLIC', '0', '[{site}]您提交的问题已经成功发布', '[{Site name}]The question you submitted has been successfully published', '[{site}]您提交的問題已經成功發布');
INSERT INTO `ts_lang` VALUES ('45', 'NOTIFY_SUPPORT_POST_ERROR_CONTENT', 'PUBLIC', '0', '抱歉，您提交的问题【{questiontitle}】未能通过审核，点击这里<a href=\"{questionurl}\" target=\"_blank\">查看详情</a>', 'Sorry, the question you submitted [{questiontitle}] failed to pass the review, <a href=\"{questionurl}\" target=\"_blank\">click here for details</a>', '抱歉，您提交的問題【{questiontitle}】未能通過審核，點擊這裡<a href=\"{questionurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('46', 'NOTIFY_SUPPORT_POST_ERROR_TITLE', 'PUBLIC', '0', '[{site}]您提交的问题未能通过审核', '[{site}]Sorry, the question you submitted failed to pass the review', '[{site}]您提交的問題未能通過審核');
INSERT INTO `ts_lang` VALUES ('47', 'NOTIFY_SUPPORT_POST_RECIVE_CONTENT', 'PUBLIC', '0', '您收到了一个等待您审核的问题【{questiontitle}】，点击这里<a href=\"{questionurl}\" target=\"_blank\">查看详情</a>', 'You received a new question[{questiontitle}] waiting for you review, <a href=\"{questionurl}\" target=\"_blank\">click here for details</a>', '您收到了一個等待您審核的問題【{questiontitle}】，點擊這裡<a href=\"{questionurl}\" target=\"_blank\">查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('48', 'NOTIFY_SUPPORT_POST_RECIVE_TITLE', 'PUBLIC', '0', ' [{site}]您收到了一个等待您审核的问题', '[{site}]You received a new question， waiting for you review', ' [{site}]您收到了一個等待您審核的問題');
INSERT INTO `ts_lang` VALUES ('49', 'NOTIFY_SUPPORT_FEEDBACK_CONTENT', 'PUBLIC', '0', '以下反馈正等待您的处理：{feedback}，点击这里<a href=\"{detailurl}\" target=\"_blank\" >查看详情</a>', 'The following feedback are waiting for your handling : {feedback}，<a href=\"{detailurl}\" target=\"_blank\" > Click here for details</a>', '以下反饋正等待您的處理： {feedback}，點擊這裡<a href=\"{detailurl}\" target=\"_blank\" >查看詳情</a>');
INSERT INTO `ts_lang` VALUES ('50', 'NOTIFY_SUPPORT_FEEDBACK_TITLE', 'PUBLIC', '0', '[{site}]您收到一条新的反馈', '[{site}]You received a new feedback', '[{site}]您收到一條新的反饋');
INSERT INTO `ts_lang` VALUES ('53', 'TEST', 'PUBLIC', '0', '测试', 'test', '測試');
INSERT INTO `ts_lang` VALUES ('54', 'PUBLIC_ACCOUNT', 'PUBLIC', '0', '帐号', 'Account', '帳號');
INSERT INTO `ts_lang` VALUES ('55', 'PUBLIC_PASSWORD', 'PUBLIC', '0', '密码', 'Password', '密碼');
INSERT INTO `ts_lang` VALUES ('56', 'PUBLIC_EMAIL', 'PUBLIC', '0', '邮箱', 'Email', '郵箱');
INSERT INTO `ts_lang` VALUES ('57', 'PUBLIC_LOGIN_AUTOMATICALLY', 'PUBLIC', '0', '下次自动登录', 'Login automatically next time', '下次自動登錄');
INSERT INTO `ts_lang` VALUES ('58', 'FORGET_PASSWORD', 'PUBLIC', '0', '忘记密码', 'Forget Password', '忘記密碼');
INSERT INTO `ts_lang` VALUES ('59', 'PUBLIC_LOGIN', 'PUBLIC', '0', '登录', 'Login', '登錄');
INSERT INTO `ts_lang` VALUES ('60', 'PUBLIC_REGISTER', 'PUBLIC', '0', '注册', 'Register', '注冊');
INSERT INTO `ts_lang` VALUES ('61', 'PUBLIC_REGISTER_ACCOUNT', 'PUBLIC', '0', '注册帐号', 'Register Account', '注冊帳號');
INSERT INTO `ts_lang` VALUES ('62', 'PUBLIC_LOGIN_EMAIL', 'PUBLIC', '0', '登录邮箱', 'Login Account', '登錄郵箱');
INSERT INTO `ts_lang` VALUES ('63', 'PUBLIC_LOGIN_PASSWORD', 'PUBLIC', '0', '登录密码', 'Login Password', '登錄密碼');
INSERT INTO `ts_lang` VALUES ('64', 'PUBLIC_CONFIRM_PASSWORD', 'PUBLIC', '0', '确认密码', 'Confirm Password', '確認密碼');
INSERT INTO `ts_lang` VALUES ('65', 'PUBLIC_REAL_NAME', 'PUBLIC', '0', '昵称', 'Nickname', '暱稱');
INSERT INTO `ts_lang` VALUES ('66', 'PUBLIC_GENDER', 'PUBLIC', '0', '性别', 'Gender', '性別');
INSERT INTO `ts_lang` VALUES ('67', 'PUBLIC_MALE', 'PUBLIC', '0', '男', 'Male', '男');
INSERT INTO `ts_lang` VALUES ('68', 'PUBLIC_FEMALE', 'PUBLIC', '0', '女', 'Female', '女');
INSERT INTO `ts_lang` VALUES ('69', 'PUBLIC_PASSWORD_TIPS', 'PUBLIC', '0', '密码太短了，最少6位', 'Password is too short, at least {sum} bit. ', '密碼太短了，最少6位');
INSERT INTO `ts_lang` VALUES ('70', 'PUBLIC_REAL_NAME_TIPS', 'PUBLIC', '0', '请填写您的真实姓名，一经填写，无法修改', 'Please input your real name, once written ,it can’t be modified.', '請填寫您的真實姓名，一經填寫，無法修改');
INSERT INTO `ts_lang` VALUES ('71', 'PUBLIC_SERVICE_TERMS', 'PUBLIC', '0', '接受网站服务条款', 'Accept website service terms', '接受網站服務條款');
INSERT INTO `ts_lang` VALUES ('72', 'PUBLIC_REGISTER_NOW', 'PUBLIC', '0', '立即开通', 'Register Now', '立即開通');
INSERT INTO `ts_lang` VALUES ('73', 'PUBLIC_ACCOUNT_REGISTER_CONGRATULATIONS_TIPS', 'PUBLIC', '0', '恭喜您，已成功开通帐号', 'Congratulations, account successfully registered.', '恭喜您，已成功開通帳號');
INSERT INTO `ts_lang` VALUES ('74', 'PUBLIC_ACCOUNT_ACTIVATION_EMAIL_TIPS', 'PUBLIC', '0', '系统已将一封激活邮件发送至您的邮箱，请立即查收邮件激活帐号。', 'System has sent one activation email to your email address, check your email now to activate account.', '系統已將一封激活郵件發送至您的郵箱，請立即查收郵件激活帳號。');
INSERT INTO `ts_lang` VALUES ('75', 'PUBLIC_CHECK_EMAIL', 'PUBLIC', '0', '查收邮件', 'Check Email', '查收郵件');
INSERT INTO `ts_lang` VALUES ('76', 'PUBLIC_EMAIL_RESEND_TIPS', 'PUBLIC', '0', '没有收到邮件？', 'Didn’t receive an email? ', '沒有收到郵件？');
INSERT INTO `ts_lang` VALUES ('77', 'PUBLIC_EMAIL_RESEND_TIPSRE', 'PUBLIC', '0', '点此重发', 'Click here to resend', '點此重發');
INSERT INTO `ts_lang` VALUES ('78', 'PUBLIC_ACCOUNT_ACTIVATED_SUCCESSFULLY', 'PUBLIC', '0', '恭喜，帐号已成功激活', 'Congratulations，your account has been activated successfully', '恭喜，帳號已成功激活');
INSERT INTO `ts_lang` VALUES ('79', 'PUBLIC_UPLOAD_IMAGE', 'PUBLIC', '0', '上传头像', 'Upload Image', '上傳頭像');
INSERT INTO `ts_lang` VALUES ('80', 'PUBLIC_IMPROVE_PERSONAL_PROFILE', 'PUBLIC', '0', '完善个人资料', 'Improve Profile', '完善個人資料');
INSERT INTO `ts_lang` VALUES ('81', 'PUBLIC_FOLLOW_THE_PEOPLE_INTERESTED', 'PUBLIC', '0', '关注感兴趣的人', 'Follow the people interested', '關注感興趣的人');
INSERT INTO `ts_lang` VALUES ('82', 'PUBLIC_UPLOAD_PHOTO_TIPS', 'PUBLIC', '0', '支持JPG,JPGE,PNG,BMP格式，大小限制2M以内', 'Support JPG, JPGE, PNG, BMP format, size limit within 2 M', '支持JPG,JPGE,PNG,BMP格式，大小限制2M以內');
INSERT INTO `ts_lang` VALUES ('83', 'PUBLIC_GOTO_NEXT_STEP', 'PUBLIC', '0', '保存，进入下一步', 'Save and Continue', '保存，進入下一步');
INSERT INTO `ts_lang` VALUES ('84', 'PUBLIC_PROFILESET_DEPARTMENT', 'PUBLIC', '0', '部门', 'Department', '部門');
INSERT INTO `ts_lang` VALUES ('85', 'PUBLIC_PROFILESET_MOBILE', 'PUBLIC', '0', '手机', 'Mobile', '手機');
INSERT INTO `ts_lang` VALUES ('86', 'PUBLIC_PROFILESET_TEL', 'PUBLIC', '0', '座机', 'Tel', '座機');
INSERT INTO `ts_lang` VALUES ('87', 'PUBLIC_PROFILESET_WORK_DIRECTOR', 'PUBLIC', '0', '直接主管', 'Direct Manager', '直接主管');
INSERT INTO `ts_lang` VALUES ('88', 'PUBLIC_TITLE', 'PUBLIC', '0', '标题', 'Title', '標題 ');
INSERT INTO `ts_lang` VALUES ('89', 'PUBLIC_TAG', 'PUBLIC', '0', '标签', 'Tag', '標簽');
INSERT INTO `ts_lang` VALUES ('90', 'PUBLIC_PROFILESET_INTRO', 'PUBLIC', '0', '个人简介', 'Introduction', '個人簡介');
INSERT INTO `ts_lang` VALUES ('1412', 'PUBLIC_MOVES', 'PUBLIC', '0', '移动', 'Move', '移動');
INSERT INTO `ts_lang` VALUES ('92', 'PUBLIC_PROFILESET_WORK_PROJECT', 'PUBLIC', '0', '项目经验', 'Project Experience', '項目經驗');
INSERT INTO `ts_lang` VALUES ('93', 'PUBLIC_RECOMMEND_USERS', 'PUBLIC', '0', '推荐用户', 'Recommend Users', '推薦用戶');
INSERT INTO `ts_lang` VALUES ('94', 'PUBLIC_CHANGE_USERS', 'PUBLIC', '0', '换一换', 'Edit Avatar', '換一換');
INSERT INTO `ts_lang` VALUES ('95', 'PUBLIC_SELECT_ALL', 'PUBLIC', '0', '全选', 'Select All', '全選');
INSERT INTO `ts_lang` VALUES ('96', 'PUBLIC_GOTO_INDEX', 'PUBLIC', '0', '保存', 'Save', '保存');
INSERT INTO `ts_lang` VALUES ('1552', 'NOTIFY_WEIBA_APPLY_CONTENT', 'PUBLIC', '0', '{name}在\"{weiba_name}\"中提交了吧主申请，<a href=\"{source_url}\">点此查看</a>', '{name} submitted the main application in \"{weiba_name}\",<a href=\"{source_url}\"> Click here to view </a>', '{name}在\"{weiba_name}\"中提交了吧主申請，<a href=\"{source_url}\">點此查看</a>');
INSERT INTO `ts_lang` VALUES ('97', 'PUBLIC_MY_HOME', 'PUBLIC', '0', '我的首页', 'Home', '我的首頁');
INSERT INTO `ts_lang` VALUES ('98', 'PUBLIC_MY_MENTIONS', 'PUBLIC', '0', '提到我的', 'Mentions', '提到我的');
INSERT INTO `ts_lang` VALUES ('99', 'PUBLIC_MY_COMMENTS', 'PUBLIC', '0', '我的评论', 'Comments', '我的評論');
INSERT INTO `ts_lang` VALUES ('100', 'PUBLIC_MY_FAVORITES', 'PUBLIC', '0', '我的收藏', 'Favorites', '我的收藏');
INSERT INTO `ts_lang` VALUES ('101', 'PUBLIC_MY_PROFILE', 'PUBLIC', '0', '我的档案', 'Profile', '我的檔案');
INSERT INTO `ts_lang` VALUES ('102', 'PUBLIC_WEIBO_TIPS', 'PUBLIC', '0', '随时记录', 'What’s Up ?', '隨時記錄');
INSERT INTO `ts_lang` VALUES ('103', 'PUBLIC_WEIBO_CHARACTERS', 'PUBLIC', '0', '可以输入140个字', '140', '可以輸入140個字');
INSERT INTO `ts_lang` VALUES ('104', 'PUBLIC_SHARE_BUTTON', 'PUBLIC', '0', '发布', 'Share', '發布');
INSERT INTO `ts_lang` VALUES ('105', 'PUBLIC_SHAREING', 'PUBLIC', '0', '发布中', 'Sharing', '發布中');
INSERT INTO `ts_lang` VALUES ('106', 'PUBLIC_EMOTION', 'PUBLIC', '0', '常用表情', 'Emotion', '常用表情');
INSERT INTO `ts_lang` VALUES ('107', 'PUBLIC_ACTIVITY_STREAM', 'PUBLIC', '0', '我关注的', 'Activity Stream', '我關注的');
INSERT INTO `ts_lang` VALUES ('108', 'PUBLIC_ACTIVITY', 'PUBLIC', '0', '全站动态', 'All Activity', '全站動態');
INSERT INTO `ts_lang` VALUES ('109', 'PUBLIC_CREATE_GROUP', 'PUBLIC', '0', '创建分组', 'Create Group', '創建分組');
INSERT INTO `ts_lang` VALUES ('110', 'PUBLIC_MANAGE_GROUP', 'PUBLIC', '0', '管理分组', 'Manage Group', '管理分組');
INSERT INTO `ts_lang` VALUES ('111', 'PUBLIC_ALL_STREAM', 'PUBLIC', '0', '全部', 'All', '全部');
INSERT INTO `ts_lang` VALUES ('112', 'PUBLIC_ORIGINAL_STREAM', 'PUBLIC', '0', '原创', 'Original', '原創');
INSERT INTO `ts_lang` VALUES ('113', 'PUBLIC_SHARE_STREAM', 'PUBLIC', '0', '转发', 'Share', '轉發');
INSERT INTO `ts_lang` VALUES ('114', 'PUBLIC_IMAGE_STREAM', 'PUBLIC', '0', '图片', 'Image', '圖片');
INSERT INTO `ts_lang` VALUES ('115', 'PUBLIC_FILE_STREAM', 'PUBLIC', '0', '附件', 'File', '附件');
INSERT INTO `ts_lang` VALUES ('116', 'PUBLIC_STREAM_LIKE', 'PUBLIC', '0', '收藏', 'Favorite', '收藏');
INSERT INTO `ts_lang` VALUES ('117', 'PUBLIC_STREAM_COMMENT', 'PUBLIC', '0', '评论', 'Comment', '評論');
INSERT INTO `ts_lang` VALUES ('118', 'PUBLIC_STREAM_REPORT', 'PUBLIC', '0', '举报', 'Report', '舉報');
INSERT INTO `ts_lang` VALUES ('119', 'PUBLIC_STREAM_DELETE', 'PUBLIC', '0', '删除', 'Delete', '刪除');
INSERT INTO `ts_lang` VALUES ('120', 'PUBLIC_STREAM_REPLY', 'PUBLIC', '0', '回复', 'Reply', '回複');
INSERT INTO `ts_lang` VALUES ('121', 'PUBLIC_SHARETO_STREAM', 'PUBLIC', '0', '同时转发到我的微博', 'Share to my stream', '同時轉發到我的微博');
INSERT INTO `ts_lang` VALUES ('122', 'PUBLIC_SHARE_IMAGE', 'PUBLIC', '0', '分享图片', 'Share Picture', '分享圖片');
INSERT INTO `ts_lang` VALUES ('123', 'PUBLIC_SHARE_FILE', 'PUBLIC', '0', '分享文档', 'Share File', '分享文檔');
INSERT INTO `ts_lang` VALUES ('124', 'PUBLIC_SHARETO', 'PUBLIC', '0', '转发到', 'Share to ', '轉發到');
INSERT INTO `ts_lang` VALUES ('125', 'PUBLIC_MY_WEIBO', 'PUBLIC', '0', '我的微博', 'My Stream', '我的微博');
INSERT INTO `ts_lang` VALUES ('126', 'PUBLIC_MY_COLLEAGUE', 'PUBLIC', '0', '我的好友', 'My Friend', '我的好友');
INSERT INTO `ts_lang` VALUES ('127', 'PUBLIC_FOLLOWING', 'PUBLIC', '0', '关注', 'Followed', '關注');
INSERT INTO `ts_lang` VALUES ('128', 'PUBLIC_FOLLOWERS', 'PUBLIC', '0', '粉丝', 'Followers', '粉絲');
INSERT INTO `ts_lang` VALUES ('129', 'PUBLIC_WEIBO', 'PUBLIC', '0', '微博', 'Weibo', '微博');
INSERT INTO `ts_lang` VALUES ('130', 'PUBLIC_ADD_FOLLOW', 'PUBLIC', '0', '加关注', 'Plus Interest', '加關注');
INSERT INTO `ts_lang` VALUES ('131', 'PUBLIC_FOLLOW_EACH_OTHER', 'PUBLIC', '0', '互相关注', 'Mutual Concern', '互相關注');
INSERT INTO `ts_lang` VALUES ('132', 'PUBLIC_CANCEL_FOLOW', 'PUBLIC', '0', '取消关注', 'unfollow', '取消關注');
INSERT INTO `ts_lang` VALUES ('133', 'PUBLIC_FOLLOW_ING', 'PUBLIC', '0', '已关注', 'Followed', '已關注');
INSERT INTO `ts_lang` VALUES ('134', 'PUBLIC_DETAIL_PROFILE', 'PUBLIC', '0', '详细资料', 'Detail Information', '詳細資料');
INSERT INTO `ts_lang` VALUES ('135', 'PUBLIC_ANNOUNCEMENT', 'PUBLIC', '0', '公告', 'Announcement', '公告');
INSERT INTO `ts_lang` VALUES ('136', 'PUBLIC_INVITE_COLLEAGUE', 'PUBLIC', '0', '邀请好友', 'Invite Friends', '邀請好友');
INSERT INTO `ts_lang` VALUES ('137', 'PUBLIC_INVITE', 'PUBLIC', '0', '邀请', 'Invite', '邀請');
INSERT INTO `ts_lang` VALUES ('138', 'PUBLIC_INVITE_CONTENT', 'PUBLIC', '0', '内容', 'Content', '內容');
INSERT INTO `ts_lang` VALUES ('139', 'PUBLIC_SEND_INVITATION', 'PUBLIC', '0', '发送邀请', 'Send Invitation', '發送邀請');
INSERT INTO `ts_lang` VALUES ('140', 'PUBLIC_JUST', 'PUBLIC', '0', '刚刚', 'Just', '剛剛');
INSERT INTO `ts_lang` VALUES ('141', 'PUBLIC_LOADING', 'PUBLIC', '0', '加载中', 'Loading', '加載中');
INSERT INTO `ts_lang` VALUES ('142', 'PUBLIC_ADD', 'PUBLIC', '0', '添加', 'Add', '添加');
INSERT INTO `ts_lang` VALUES ('143', 'PUBLIC_MESSAGE', 'PUBLIC', '0', '消息', 'Message', '消息');
INSERT INTO `ts_lang` VALUES ('144', 'PUBLIC_MY_PRIVATE_MESSAGE', 'PUBLIC', '0', '我的私信', 'My Message', '我的私信');
INSERT INTO `ts_lang` VALUES ('145', 'PUBLIC_SEND_PRIVATE_MESSAGE', 'PUBLIC', '0', '发私信', 'Send Private Message', '發私信');
INSERT INTO `ts_lang` VALUES ('146', 'PUBLIC_SYSTEM_MESSAGE', 'PUBLIC', '0', '系统消息', 'System Message', '系統消息');
INSERT INTO `ts_lang` VALUES ('147', 'PUBLIC_SETTING', 'PUBLIC', '0', '设置', 'Configuration', '設置');
INSERT INTO `ts_lang` VALUES ('148', 'PUBLIC_PROFILESET_INDEX', 'PUBLIC', '0', '基本信息', 'Basic Info', '基本信息');
INSERT INTO `ts_lang` VALUES ('150', 'PUBLIC_PROFILESET_CONTACT', 'PUBLIC', '0', '联系方式', 'Contact', '聯系方式');
INSERT INTO `ts_lang` VALUES ('151', 'PUBLIC_SETTAG', 'PUBLIC', '0', '标签设置', 'Tag', '標簽設置');
INSERT INTO `ts_lang` VALUES ('152', 'PUBLIC_PRIVACY', 'PUBLIC', '0', '隐私设置', 'Privacy', '隱私設置');
INSERT INTO `ts_lang` VALUES ('153', 'PUBLIC_DOMAIN_NAME', 'PUBLIC', '0', '个性域名', 'Private URL', '個性域名');
INSERT INTO `ts_lang` VALUES ('154', 'PUBLIC_ACCOUNT_SECURITY', 'PUBLIC', '0', '帐号安全', 'Security', '帳號安全');
INSERT INTO `ts_lang` VALUES ('155', 'PUBLIC_BUSINESS_MANAGEMENT', 'PUBLIC', '0', '业务管理', 'Business Admin', '業務管理');
INSERT INTO `ts_lang` VALUES ('156', 'PUBLIC_SYSTEM_MANAGEMENT', 'PUBLIC', '0', '后台管理', 'Admin', '後台管理');
INSERT INTO `ts_lang` VALUES ('157', 'PUBLIC_LOGOUT', 'PUBLIC', '0', '退出', 'Logout', '退出');
INSERT INTO `ts_lang` VALUES ('158', 'PUBLIC_SYSTEM', 'PUBLIC', '0', '系统', 'System', '系統');
INSERT INTO `ts_lang` VALUES ('159', 'PUBLIC_SYSTEM_INFO', 'PUBLIC', '0', '系统信息', 'System Message', '系統信息');
INSERT INTO `ts_lang` VALUES ('1403', 'PUBLIC_PROFILESET_WORK', 'PUBLIC', '0', '工作信息', 'Job Info', '工作信息');
INSERT INTO `ts_lang` VALUES ('161', 'PUBLIC_WELCOME_TIPS', 'PUBLIC', '0', '欢迎使用ThinkSNS社会化沟通、协作、管理平台', 'Welcome to use ThinkSNS social communication, collaboration, and management platform', '歡迎使用ThinkSNS社會化溝通、協作、管理平台');
INSERT INTO `ts_lang` VALUES ('162', 'PUBLIC_SERVER_INFO', 'PUBLIC', '0', '服务器信息', 'Server Information', '服務器信息');
INSERT INTO `ts_lang` VALUES ('163', 'PUBLIC_CORE_VERSION', 'PUBLIC', '0', '核心版本', 'Core Version', '核心版本');
INSERT INTO `ts_lang` VALUES ('164', 'PUBLIC_SERVER_PHP', 'PUBLIC', '0', '服务器系统及PHP版本', 'Server system and PHP version', '服務器系統及PHP版本');
INSERT INTO `ts_lang` VALUES ('165', 'PUBLIC_UPLOAD_PERMISSION', 'PUBLIC', '0', '最大上传许可', 'Maximum Upload Permission', '最大上傳許可');
INSERT INTO `ts_lang` VALUES ('166', 'PUBLIC_MYSQL', 'PUBLIC', '0', 'MySQL版本', 'MySQL Version', 'MySQL版本');
INSERT INTO `ts_lang` VALUES ('167', 'PUBLIC_DATABASE_SIZE', 'PUBLIC', '0', '数据库大小', 'Database Size', '數據庫大小');
INSERT INTO `ts_lang` VALUES ('168', 'PUBLIC_DEV_TEAM', 'PUBLIC', '0', '开发团队', 'Development Team', '開發團隊');
INSERT INTO `ts_lang` VALUES ('169', 'PUBLIC_COPYRIGHT', 'PUBLIC', '0', '版权所有', '© ', '版權所有');
INSERT INTO `ts_lang` VALUES ('170', 'PUBLIC_COMPANY', 'PUBLIC', '0', '智士软件（北京）有限公司', 'ZhiShiSoft Co., Ltd.', '智士軟件（北京）有限公司');
INSERT INTO `ts_lang` VALUES ('171', 'PUBLIC_VISIT_CALCULATION', 'PUBLIC', '0', '访问统计', 'Visit Calculation', '訪問統計');
INSERT INTO `ts_lang` VALUES ('172', 'PUBLIC_MANAGEMENT_LOG', 'PUBLIC', '0', '管理日志', 'Management Log', '管理日志');
INSERT INTO `ts_lang` VALUES ('173', 'PUBLIC_SYSTEM_SETTING', 'PUBLIC', '0', '系统配置', 'System Setting', '系統配置');
INSERT INTO `ts_lang` VALUES ('174', 'PUBLIC_WEBSITE_SETTING', 'PUBLIC', '0', '站点配置', 'Website Setting', '站點配置');
INSERT INTO `ts_lang` VALUES ('175', 'PUBLIC_REGISTER_SETTING', 'PUBLIC', '0', '注册配置', 'Register Setting', '注冊配置');
INSERT INTO `ts_lang` VALUES ('176', 'PUBLIC_WEIBO_SETTING', 'PUBLIC', '0', '微博配置', 'Weibo Setting', '微博配置');
INSERT INTO `ts_lang` VALUES ('177', 'PUBLIC_NAVIGATION_SETTING', 'PUBLIC', '0', '导航配置', 'Navigation Setting', '導航配置');
INSERT INTO `ts_lang` VALUES ('178', 'PUBLIC_EMAIL_SETTING', 'PUBLIC', '0', '邮件配置', 'Email Setting', '郵件配置');
INSERT INTO `ts_lang` VALUES ('179', 'PUBLIC_FILE_SETTING', 'PUBLIC', '0', '附件配置', 'File Setting', '附件配置');
INSERT INTO `ts_lang` VALUES ('180', 'PUBLIC_FILTER_SETTING', 'PUBLIC', '0', '过滤配置', 'Filter Setting', '過濾配置');
INSERT INTO `ts_lang` VALUES ('181', 'PUBLIC_POINT_SETTING', 'PUBLIC', '0', '积分配置', 'Point Setting', '積分配置');
INSERT INTO `ts_lang` VALUES ('182', 'PUBLIC_AREA_SETTING', 'PUBLIC', '0', '地区配置', 'Area Setting', '地區配置');
INSERT INTO `ts_lang` VALUES ('183', 'PUBLIC_SYSTEM_TOOL', 'PUBLIC', '0', '系统工具', 'System Tool', '系統工具');
INSERT INTO `ts_lang` VALUES ('184', 'PUBLIC_CLEANCACHE', 'PUBLIC', '0', '缓存清理', 'Cleancache', '緩存清理');
INSERT INTO `ts_lang` VALUES ('185', 'PUBLIC_SCHEDULED_TASK', 'PUBLIC', '0', '计划任务', 'Scheduled Task', '計劃任務');
INSERT INTO `ts_lang` VALUES ('186', 'PUBLIC_USER', 'PUBLIC', '0', '用户', 'User', '用戶');
INSERT INTO `ts_lang` VALUES ('187', 'PUBLIC_USER_MANAGEMENT', 'PUBLIC', '0', '用户管理', 'User Management', '用戶管理');
INSERT INTO `ts_lang` VALUES ('188', 'PUBLIC_USER_GROUP_MANAGEMENT', 'PUBLIC', '0', '用户组管理', 'User Group Management', '用戶組管理');
INSERT INTO `ts_lang` VALUES ('189', 'PUBLIC_PROFILE_SETTING', 'PUBLIC', '0', '资料配置', 'Profile Setting', '資料配置');
INSERT INTO `ts_lang` VALUES ('190', 'PUBLIC_DEPARTMENT_MANAGEMENT', 'PUBLIC', '0', '部门管理', 'Department Management', '部門管理');
INSERT INTO `ts_lang` VALUES ('191', 'PUBLIC_CONTENT', 'PUBLIC', '0', '内容', 'Content', '內容');
INSERT INTO `ts_lang` VALUES ('192', 'PUBLIC_OPERATION_TOOL', 'PUBLIC', '0', '运营工具', 'Operation Tool', '運營工具');
INSERT INTO `ts_lang` VALUES ('193', 'PUBLIC_ANNOUNCEMENT_SETTING', 'PUBLIC', '0', '公告配置', 'Announcement Setting', '公告配置');
INSERT INTO `ts_lang` VALUES ('194', 'PUBLIC_INVITE_CALCULATION', 'PUBLIC', '0', '邀请统计', 'Invite Calculation', '邀請統計');
INSERT INTO `ts_lang` VALUES ('195', 'PUBLIC_FEEDBACK_MANAGEMENT', 'PUBLIC', '0', '意见反馈', 'Feedback Management', '意見反饋');
INSERT INTO `ts_lang` VALUES ('196', 'PUBLIC_CONTENT_MANAGEMENT', 'PUBLIC', '0', '内容管理', 'Content Management', '內容管理');
INSERT INTO `ts_lang` VALUES ('197', 'PUBLIC_WEIBO_MANAGEMENT', 'PUBLIC', '0', '微博管理', 'Weibo Management', '微博管理');
INSERT INTO `ts_lang` VALUES ('198', 'PUBLIC_COMMENT_MANAGEMENT', 'PUBLIC', '0', '评论管理', 'Comment Management', '評論管理');
INSERT INTO `ts_lang` VALUES ('199', 'PUBLIC_PRIVATE_MESSAGE_MANAGEMENT', 'PUBLIC', '0', '私信管理', 'Private Message Management', '私信管理');
INSERT INTO `ts_lang` VALUES ('200', 'PUBLIC_FILE_MANAGEMENT', 'PUBLIC', '0', '附件管理', 'File Management', '附件管理');
INSERT INTO `ts_lang` VALUES ('201', 'PUBLIC_REPORT_MANAGEMENT', 'PUBLIC', '0', '举报管理', 'Report Management', '舉報管理');
INSERT INTO `ts_lang` VALUES ('202', 'PUBLIC_TAG_MANAGEMENT', 'PUBLIC', '0', '标签管理', 'Tag Management', '標簽管理');
INSERT INTO `ts_lang` VALUES ('203', 'PUBLIC_EXPANSION', 'PUBLIC', '0', '扩展', 'Expansion', '擴展');
INSERT INTO `ts_lang` VALUES ('204', 'PUBLIC_APP_MANAGEMENT', 'PUBLIC', '0', '应用管理', 'App Management', '應用管理');
INSERT INTO `ts_lang` VALUES ('205', 'PUBLIC_INSTALLED_APPLIST', 'PUBLIC', '0', '已安装应用列表', 'Installed Applist', '已安裝應用列表');
INSERT INTO `ts_lang` VALUES ('206', 'PUBLIC_UNINSTALLED_APPLIST', 'PUBLIC', '0', '未安装应用列表', 'Uninstalled Applist', '未安裝應用列表');
INSERT INTO `ts_lang` VALUES ('207', 'PUBLIC_POINTS_SETTING', 'PUBLIC', '0', '积分节点配置', 'Point Setting', '積分節點配置');
INSERT INTO `ts_lang` VALUES ('208', 'PUBLIC_AUTHORITY_SETTING', 'PUBLIC', '0', '权限节点配置', 'Authority Setting', '權限節點配置');
INSERT INTO `ts_lang` VALUES ('209', 'PUBLIC_WEIBO_TEMPLATE_SETTING', 'PUBLIC', '0', '微博模板设置', 'Weibo Template Setting', '微博模板設置');
INSERT INTO `ts_lang` VALUES ('210', 'PUBLIC_APP_SETTING', 'PUBLIC', '0', '应用配置', 'App Setting', '應用配置');
INSERT INTO `ts_lang` VALUES ('211', 'PUBLIC_PLUGIN_MANAGEMENT', 'PUBLIC', '0', '插件管理', 'Plugin Management', '插件管理');
INSERT INTO `ts_lang` VALUES ('212', 'PUBLIC_PLUGIN_SETTING', 'PUBLIC', '0', '插件配置', 'Plugin Setting', '插件配置');
INSERT INTO `ts_lang` VALUES ('213', 'PUBLIC_REALNAME_TIPS', 'PUBLIC', '0', '请填写真实姓名，最少2个汉字长度', 'Please input real name, at least 2 characters', '請填寫真實姓名，最少2個漢字長度');
INSERT INTO `ts_lang` VALUES ('214', 'PUBLIC_PASSWORD_WEAK', 'PUBLIC', '0', '弱', 'Weak', '弱');
INSERT INTO `ts_lang` VALUES ('215', 'PUBLIC_PASSWORD_MIDDLE', 'PUBLIC', '0', '中', 'Middle', '中');
INSERT INTO `ts_lang` VALUES ('216', 'PUBLIC_PASSWORD_STRONG', 'PUBLIC', '0', '强', 'Strong', '強');
INSERT INTO `ts_lang` VALUES ('217', 'PUBLIC_CHOOSE_GENDER', 'PUBLIC', '0', '请选择性别', 'Please choose “Gender”', '請選擇性別');
INSERT INTO `ts_lang` VALUES ('218', 'PUBLIC_ACCEPT_SERVICE_TERMS', 'PUBLIC', '0', '接受本站服务条款，方可进行注册', 'Accept website service terms and continue', '接受本站服務條款，方可進行注冊');
INSERT INTO `ts_lang` VALUES ('219', 'PUBLIC_EMAIL_TIPS', 'PUBLIC', '0', '无效的Email地址', 'Invalid Email address', '無效的Email地址');
INSERT INTO `ts_lang` VALUES ('220', 'PUBLIC_INPUT_CONFIRM_PASSWORD', 'PUBLIC', '0', '请输入确认密码', 'Please input confirmed password', '請輸入確認密碼');
INSERT INTO `ts_lang` VALUES ('221', 'PUBLIC_WAIT_FOR_REVIEW', 'PUBLIC', '0', '恭喜，您已成功注册帐号，请等待管理员审核', 'Congratulations, you have registered successfully, please wait for reviewing.', '恭喜，您已成功注冊帳號，請等待管理員審核');
INSERT INTO `ts_lang` VALUES ('222', 'PUBLIC_WAIT_REVIEW_TIPS', 'PUBLIC', '0', '系统将通过邮件告知您审核结果。若长时间未收到邮件，请联系{link}进行处理。', 'System will inform you the review results by E-mail. If not received mail for a long time, please contact {link} for processing.', '系統將通過郵件告知您審核結果。若長時間未收到郵件，請聯系{link}進行處理。');
INSERT INTO `ts_lang` VALUES ('223', 'PBLIC_SAVE_IMAGE', 'PUBLIC', '0', '保存头像', 'Save Image', '保存頭像');
INSERT INTO `ts_lang` VALUES ('224', 'PUBLIC_TAGS_INPUT', 'PUBLIC', '0', '添加标签，用回车分隔', 'Add tags and separate with ”Enter”', '添加標簽，用回車分隔');
INSERT INTO `ts_lang` VALUES ('225', 'PUBLIC_MOBILE_INPUT', 'PUBLIC', '0', '请输入手机', 'Please input “Mobile”', '請輸入手機');
INSERT INTO `ts_lang` VALUES ('226', 'PUBLIC_TEL_INPUT', 'PUBLIC', '0', '请输入座机', 'Please input “Telephone”', '請輸入座機');
INSERT INTO `ts_lang` VALUES ('227', 'PUBLIC_TITLE_INPUT', 'PUBLIC', '0', '请输入职务', 'Please input “Title”', '請輸入職務');
INSERT INTO `ts_lang` VALUES ('228', 'PUBLIC_FEEDBACK', 'PUBLIC', '0', '意见反馈', 'Feedback', '意見反饋');
INSERT INTO `ts_lang` VALUES ('229', 'PUBLIC_FOLD', 'PUBLIC', '0', '收起', 'Fold', '收起');
INSERT INTO `ts_lang` VALUES ('230', 'PUBLIC_UNFOLD', 'PUBLIC', '0', '展开', 'Unfold', '展開');
INSERT INTO `ts_lang` VALUES ('234', 'PUBLIC_FOLLOWERS_REMIND', 'PUBLIC', '0', '查看粉丝', 'View', '查看粉絲');
INSERT INTO `ts_lang` VALUES ('235', 'PUBLIS_MESSAGE_REMIND', 'PUBLIC', '0', '查看消息', 'View', '查看消息');
INSERT INTO `ts_lang` VALUES ('236', 'PUBLIC_SEARCH_BY_NAME', 'PUBLIC', '0', '按名字查找', 'Search by name', '按名字查找');
INSERT INTO `ts_lang` VALUES ('237', 'SYSTEM_MANAGEMENT_CENTER', 'PUBLIC', '0', '管理中心', 'Manage', '管理中心');
INSERT INTO `ts_lang` VALUES ('238', 'SYSTEM_REFRESH', 'PUBLIC', '0', '刷新', 'Refresh', '刷新');
INSERT INTO `ts_lang` VALUES ('239', 'SYSTEM_BACK_TO_FRONT', 'PUBLIC', '0', '返回前台', 'Back to front', '返回前台');
INSERT INTO `ts_lang` VALUES ('240', 'PUBLIC_INDEX_INDEX', 'PUBLIC', '0', '我的首页-微博', 'Home-Weibo', '我的首頁-微博');
INSERT INTO `ts_lang` VALUES ('241', 'PUBLIC_MENTION_INDEX', 'PUBLIC', '0', '@提到我-微博', 'Mentions-Weibo', '@提到我-微博');
INSERT INTO `ts_lang` VALUES ('242', 'PUBLIC_COMMENT_INDEX', 'PUBLIC', '0', '我的评论', 'Comments', '我的評論');
INSERT INTO `ts_lang` VALUES ('243', 'PUBLIC_COLLECTION_INDEX', 'PUBLIC', '0', '我的收藏', 'Favorites', '我的收藏');
INSERT INTO `ts_lang` VALUES ('244', 'PUBLIC_PROFILE_INDEX', 'PUBLIC', '0', '我的微博', 'My Weibo', '我的微博');
INSERT INTO `ts_lang` VALUES ('245', 'PUBLIC_PROFILE_FOLLWER', 'PUBLIC', '0', '我的粉丝', 'Followers', '我的粉絲');
INSERT INTO `ts_lang` VALUES ('246', 'PUBLIC_PROFILE_FOLLOWING', 'PUBLIC', '0', '我的关注', 'Following', '我的關注');
INSERT INTO `ts_lang` VALUES ('247', 'PUBLIC_PROFILE_DATA', 'PUBLIC', '0', '我的档案', 'Profile', '我的檔案');
INSERT INTO `ts_lang` VALUES ('248', 'PUBLIC_PROFILE', 'PUBLIC', '0', '档案', 'Profile', '檔案');
INSERT INTO `ts_lang` VALUES ('249', 'PUBLIC_MESSAGE_INDEX', 'PUBLIC', '0', '我的私信', 'Private Message', '我的私信');
INSERT INTO `ts_lang` VALUES ('250', 'PUBLIC_MESSAGE_NOTIFY', 'PUBLIC', '0', '系统消息', 'System Message', '系統消息');
INSERT INTO `ts_lang` VALUES ('251', 'PUBLIC_ACCOUNT_INDEX', 'PUBLIC', '0', '个人设置', 'Personal Setting', '個人設置');
INSERT INTO `ts_lang` VALUES ('252', 'PUBLIC_MANAGE_INDEX', 'PUBLIC', '0', '首页-业务管理', 'Index-Business Admin', '首頁-業務管理');
INSERT INTO `ts_lang` VALUES ('254', 'PUBLIC_SUPPORT', 'PUBLIC', '0', '帮助中心', 'Support Center ', '幫助中心');
INSERT INTO `ts_lang` VALUES ('255', 'ADMIN_PUBLIC_LOGIN', 'PUBLIC', '0', '登录-后台管理', 'Login-System Management', '登錄-後台管理');
INSERT INTO `ts_lang` VALUES ('256', 'PUBLIC_APP_INEX', 'PUBLIC', '0', '添加应用', 'Add Application', '添加應用');
INSERT INTO `ts_lang` VALUES ('257', 'PUBLIC_SEARCH_INDEX', 'PUBLIC', '0', '搜索', 'Search', '搜索');
INSERT INTO `ts_lang` VALUES ('1376', 'NOTIFY_AUTHENTICATE_DOVERIFY_TITLE', 'PUBLIC', '0', '', '', '');
INSERT INTO `ts_lang` VALUES ('1375', 'NOTIFY_AUTHENTICATE_DOVERIFY_CONTENT', 'PUBLIC', '0', '管理员{act}了您的认证', '', '');
INSERT INTO `ts_lang` VALUES ('1374', 'NOTIFY_AUTHENTICATE_SUBMIT_TITLE', 'PUBLIC', '0', '[ {site} ]您已经成功提交认证申请，请等待管理员审核', '[ {site} ]You have successfully submitted the application for certification, please wait for an administrator to audit', '[ {site} ]您已經成功提交認證申請，請等待管理員審核');
INSERT INTO `ts_lang` VALUES ('1373', 'NOTIFY_AUTHENTICATE_SUBMIT_CONTENT', 'PUBLIC', '0', '您已经成功提交认证申请，请等待管理员审核，审核结果将通过系统消息告知您。', 'You have successfully submitted the application for verification, please wait for an administrator to audit, audit results through the system message to inform you.', '您已經成功提交認證申請，請等待管理員審核，審核結果將通過系統消息告知您。');
INSERT INTO `ts_lang` VALUES ('273', 'PUBLIC_LANGUAGE', 'PUBLIC', '0', '语言配置', 'Language Setting', '語言配置');
INSERT INTO `ts_lang` VALUES ('274', 'PUBLIC_DIYWIDGET', 'PUBLIC', '0', '自定义组件列表', 'DIY Widget List', '自定義組件列表');
INSERT INTO `ts_lang` VALUES ('275', 'PUBLIC_OPEN', 'PUBLIC', '0', '开启', 'Open', '開啓');
INSERT INTO `ts_lang` VALUES ('276', 'PUBLIC_CLOSE', 'PUBLIC', '0', '关闭', 'Close', '關閉');
INSERT INTO `ts_lang` VALUES ('277', 'PUBLIC_TYPE', 'PUBLIC', '0', '类型', 'Type', '類型');
INSERT INTO `ts_lang` VALUES ('278', 'PUBLIC_DESCRIPTION', 'PUBLIC', '0', '描述', 'Description', '描述');
INSERT INTO `ts_lang` VALUES ('279', 'PUBLIC_SUBMIT', 'PUBLIC', '0', '提交', 'Submit', '提交');
INSERT INTO `ts_lang` VALUES ('280', 'PUBLIC_BACKTO_HOMEPAGE', 'PUBLIC', '0', '返回我的首页', 'Back to homepage', '返回我的首頁');
INSERT INTO `ts_lang` VALUES ('281', 'PUBLIC_APPLIST', 'PUBLIC', '0', '应用列表', 'App List', '應用列表');
INSERT INTO `ts_lang` VALUES ('282', 'PUBLIC_APPUSE_CALCULATION', 'PUBLIC', '0', '共{num}人使用', 'All {num} users', '共{num}人使用');
INSERT INTO `ts_lang` VALUES ('283', 'PUBLIC_WEIBO_COMMENT_SETTING', 'PUBLIC', '0', '设置谁可以评论我的微博', 'Set who can comment my weibo', '設置誰可以評論我的微博');
INSERT INTO `ts_lang` VALUES ('284', 'PUBLIC_WEIBO_COMMENT_ALL', 'PUBLIC', '0', '所有人', 'All users', '所有人');
INSERT INTO `ts_lang` VALUES ('285', 'PUBLIC_WEIBO_COMMENT_FOLLOWING', 'PUBLIC', '0', '我关注的人', 'Following', '我關注的人');
INSERT INTO `ts_lang` VALUES ('286', 'PUBLIC_PRIVATE_MESSAGE', 'PUBLIC', '0', '私信', 'Private Message', '私信');
INSERT INTO `ts_lang` VALUES ('287', 'PUBLIC_PRIVATE_MESSAGE_SETTING', 'PUBLIC', '0', '设置谁可以给我发私信', 'Set who can send me private message', '設置誰可以給我發私信');
INSERT INTO `ts_lang` VALUES ('288', 'PUBLIC_NO_RECEIVE_SYSMAIL', 'PUBLIC', '0', '不接收系统邮件', 'Not to receive system message', '不接收系統郵件');
INSERT INTO `ts_lang` VALUES ('289', 'PUBLIC_ORIGINAL_PASSWORD', 'PUBLIC', '0', '原始密码', 'Original Password', '原始密碼');
INSERT INTO `ts_lang` VALUES ('290', 'PUBLIC_NEW_PASSWORD', 'PUBLIC', '0', '新密码', 'New Password', '新密碼');
INSERT INTO `ts_lang` VALUES ('291', 'PUBLIC_TIME_YEAR', 'PUBLIC', '0', '年份', 'Year', '年份');
INSERT INTO `ts_lang` VALUES ('292', 'PUBLIC_TIME_MONTH', 'PUBLIC', '0', '月份', 'Month', '月份');
INSERT INTO `ts_lang` VALUES ('293', 'PUBLIC_TIME_DATE', 'PUBLIC', '0', '日', 'Date', '日');
INSERT INTO `ts_lang` VALUES ('294', 'PUBLIC_COMMENT_OTHER', 'PUBLIC', '0', '评论{user}的{type}', 'Comment on {type} of {user}', '評論{user}的{type}');
INSERT INTO `ts_lang` VALUES ('295', 'PUBLIC_VIEW', 'PUBLIC', '0', '查看', 'View', '查看');
INSERT INTO `ts_lang` VALUES ('296', 'PUBLIC_RECEIVE_COMMENT', 'PUBLIC', '0', '收到的评论', 'Comments Received', '收到的評論');
INSERT INTO `ts_lang` VALUES ('297', 'PUBLIC_SEND_COMMENT', 'PUBLIC', '0', '发出的评论', 'Comments Sent', '發出的評論');
INSERT INTO `ts_lang` VALUES ('298', 'PUBLIC_COMMENT_CONFIRM_TIPS', 'PUBLIC', '0', '是否删除该评论？', 'Confirm to delete the comment?', '是否刪除該評論？');
INSERT INTO `ts_lang` VALUES ('299', 'PUBLIC_PEOPLE_REMARK', 'PUBLIC', '0', '备注', 'Remark', '備注');
INSERT INTO `ts_lang` VALUES ('300', 'PUBLIC_REMARK_SETTING', 'PUBLIC', '0', '设置备注', 'Set Remark', '設置備注');
INSERT INTO `ts_lang` VALUES ('301', 'PUBLIC_PEOPLE_GROUP_SETTING', 'PUBLIC', '0', '为{link}设置分组', 'Set a group for {link}', '爲{link}設置分組');
INSERT INTO `ts_lang` VALUES ('302', 'PUBLIC_EDIT_GROUP', 'PUBLIC', '0', '编辑分组', 'Modify Group', '編輯分組');
INSERT INTO `ts_lang` VALUES ('303', 'PUBLIC_EDIT', 'PUBLIC', '0', '编辑', 'Edit', '編輯');
INSERT INTO `ts_lang` VALUES ('304', 'PUBLIC_GROUPNAME_INPUT', 'PUBLIC', '0', '请输入分组名称', 'Please enter the group name', '請輸入分組名稱');
INSERT INTO `ts_lang` VALUES ('305', 'PUBLIC_GROUP_CHARACTER_LIMIT', 'PUBLIC', '0', '分组名称不能超过10个汉字长度', 'Group name can not be more than 10 characters', '分組名稱不能超過10個漢字長度');
INSERT INTO `ts_lang` VALUES ('306', 'PUBLIC_REMARK_CHARACTER_LIMIT', 'PUBLIC', '0', '备注不能超过10个字', 'Remark can not more than 10 characters', '備注不能超過10個字');
INSERT INTO `ts_lang` VALUES ('307', 'PUBLIC_SAVE_SUCCESS', 'PUBLIC', '0', '保存成功', 'Saved Successfully', '保存成功');
INSERT INTO `ts_lang` VALUES ('308', 'PUBLIC_GROUP_ALREADY_EXIST', 'PUBLIC', '0', '此分组名已存在', 'Group name already exists', '此分組名已存在');
INSERT INTO `ts_lang` VALUES ('309', 'PUBLIC_MANAGEMENT', 'PUBLIC', '0', '管理', 'Manage', '管理');
INSERT INTO `ts_lang` VALUES ('310', 'PUBLIC_NO_MENTIONS', 'PUBLIC', '0', '暂时没有提到我的', 'No Mentions', '暫時沒有提到我的');
INSERT INTO `ts_lang` VALUES ('311', 'PUBLIC_VIEW_DETAIL', 'PUBLIC', '0', '查看详情', 'Details', '查看詳情');
INSERT INTO `ts_lang` VALUES ('312', 'PUBLIC_BACK', 'PUBLIC', '0', '返回', 'Back', '返回');
INSERT INTO `ts_lang` VALUES ('313', 'PUBLIC_WITH_OTHER_DIALOGUE', 'PUBLIC', '0', '我和{user}的私信（共{num}条对话）', 'Dialogue between me with {user} (A total of {num} Article dialogue)', '我和{user}的私信（共{num}條對話）');
INSERT INTO `ts_lang` VALUES ('314', 'PUBLIC_MORE', 'PUBLIC', '0', '更多', 'More', '更多');
INSERT INTO `ts_lang` VALUES ('315', 'PUBLIC_NO_MORE_INFO', 'PUBLIC', '0', '暂无信息', 'No Information', '暫無信息');
INSERT INTO `ts_lang` VALUES ('316', 'PUBLIC_DIALOGUE_DELETE_CONFIRM', 'PUBLIC', '0', '确定要删除该对话所有记录吗？', 'Confirm to delete all records in the dialogue?', '確定要刪除該對話所有記錄嗎？');
INSERT INTO `ts_lang` VALUES ('317', 'PUBLIC_CHOOSE_PRIVATE', 'PUBLIC', '0', '请选择私信', 'Please select private message', '請選擇私信');
INSERT INTO `ts_lang` VALUES ('318', 'PUBLIC_SAVE_FAIL', 'PUBLIC', '0', '保存失败', 'Save Failed', '保存失敗');
INSERT INTO `ts_lang` VALUES ('319', 'PUBLIC_DELETE_SUCCESS', 'PUBLIC', '0', '删除成功', 'Delete successfully', '刪除成功');
INSERT INTO `ts_lang` VALUES ('320', 'PUBLIC_DELETE_FAIL', 'PUBLIC', '0', '删除失败', 'Delete failed', '刪除失敗');
INSERT INTO `ts_lang` VALUES ('321', 'PUBLIC_ME', 'PUBLIC', '0', '我', 'Me', '我');
INSERT INTO `ts_lang` VALUES ('322', 'PUBLIC_MESSAGE_DELETE_CONFIRM', 'PUBLIC', '0', '确定要删除该消息？', 'Confirm to delete the message?', '確定要刪除該消息？');
INSERT INTO `ts_lang` VALUES ('323', 'PUBLIC_RECEIVER', 'PUBLIC', '0', '收信人', 'Addressee', '收信人');
INSERT INTO `ts_lang` VALUES ('324', 'PUBLIC_SEND', 'PUBLIC', '0', '发送', 'Send', '發送');
INSERT INTO `ts_lang` VALUES ('325', 'PUBLIC_SEND_SUCCESS', 'PUBLIC', '0', '发送成功', 'Sent Successfully', '發送成功');
INSERT INTO `ts_lang` VALUES ('326', 'PUBLIC_RESET_PASSWORD', 'PUBLIC', '0', '重置密码', 'Reset Password', '重置密碼');
INSERT INTO `ts_lang` VALUES ('327', 'PUBLIC_PASSWORD_EMAIL_INPUT', 'PUBLIC', '0', '请输入您注册时用的EMAIL，我们会将该密码发送至该邮箱。', 'Please enter you register E-mail, and we will send the password', '請輸入您注冊時用的EMAIL，我們會將該密碼發送至該郵箱。');
INSERT INTO `ts_lang` VALUES ('328', 'PUBLIC_PASSWORD_EMAIL_TIPS', 'PUBLIC', '0', '提示：如果没有收到邮件，请联系客服寻求帮助', 'Tip: If you do not receive the email, please contact customer service for help', '提示：如果沒有收到郵件，請聯系客服尋求幫助');
INSERT INTO `ts_lang` VALUES ('329', 'PUBLIC_CONFIRM', 'PUBLIC', '0', '确定', 'OK', '確定');
INSERT INTO `ts_lang` VALUES ('330', 'PUBLIC_RESEND', 'PUBLIC', '0', '重新发送', 'Resend', '重新發送');
INSERT INTO `ts_lang` VALUES ('331', 'PUBLIC_RESEND_PASSWORD_MAIL', 'PUBLIC', '0', '已重新发送重置邮件', 'Resend message successfully', '已重新發送重置郵件');
INSERT INTO `ts_lang` VALUES ('332', 'PUBLIC_USER_LEVEL', 'PUBLIC', '0', '用户等级', 'User Level', '用戶等級');
INSERT INTO `ts_lang` VALUES ('333', 'PUBLIC_USER_POINTS_CALCULATION', 'PUBLIC', '0', '当前共有{num}点{experience}值', 'A total of {num} {experience} experience', '當前共有{num}點{experience}值');
INSERT INTO `ts_lang` VALUES ('334', 'PUBLI_USER_UPGRADE_TIPS', 'PUBLIC', '0', '距离下一级还差{num}点{experience}值', ' distance level difference {num} {experience} value ', '距離下一級還差{num}點{experience}值');
INSERT INTO `ts_lang` VALUES ('335', 'PUBLIC_CUSTOMER_SERVICE', 'PUBLIC', '0', '客服', 'Customer Service', '客服');
INSERT INTO `ts_lang` VALUES ('336', 'PUBLIC_NO_RELATE_DATA', 'PUBLIC', '0', '没有相关数据', 'No Related Data', '沒有相關數據');
INSERT INTO `ts_lang` VALUES ('337', 'PUBLIC_SEARCH_RESULT_CALCULATION', 'PUBLIC', '0', '共搜到{num}条结果', ' Found {num} results', '共搜到{num}條結果');
INSERT INTO `ts_lang` VALUES ('338', 'PUBLIC_ALL', 'PUBLIC', '0', '所有', 'All', '所有');
INSERT INTO `ts_lang` VALUES ('339', 'PUBLIC_ALL_WEBSITE', 'PUBLIC', '0', '全站', 'All', '全站');
INSERT INTO `ts_lang` VALUES ('340', 'PUBLIC_SEARCH', 'PUBLIC', '0', '搜', 'Search', '搜');
INSERT INTO `ts_lang` VALUES ('341', 'PUBLIC_CANCEL_FAVORITE', 'PUBLIC', '0', '取消收藏', 'Cancel Favorite', '取消收藏');
INSERT INTO `ts_lang` VALUES ('342', 'PUBLIC_ALREADY_FAVORITE', 'PUBLIC', '0', '已收藏', 'Favorited', '已收藏');
INSERT INTO `ts_lang` VALUES ('343', 'PUBLIC_INFO_ALREADY_DELETE_TIPS', 'PUBLIC', '0', '此信息已被删除〜', 'This information has been deleted ~', '此信息已被刪除〜');
INSERT INTO `ts_lang` VALUES ('344', 'PUBLIC_NO_RELATE_WEIBO', 'PUBLIC', '0', '暂时没有相关微博', 'No Related Weibo', '暫時沒有相關微博');
INSERT INTO `ts_lang` VALUES ('345', 'PUBLIC_MODIFY_PASSWORD', 'PUBLIC', '0', '修改密码', 'Change Password', '修改密碼');
INSERT INTO `ts_lang` VALUES ('346', 'PUBLIC_CHOOSE_COLLEAGUE', 'PUBLIC', '0', '选择好友', 'Select Friends', '選擇好友');
INSERT INTO `ts_lang` VALUES ('347', 'PUBLIC_ADD_COMMENT', 'PUBLIC', '0', '添加评论', 'Add Comment', '添加評論');
INSERT INTO `ts_lang` VALUES ('348', 'PUBLIC_INPUT_CHARACTER_LIMIT', 'PUBLIC', '0', '可以输入{num}个字', '{num} limited', '可以輸入{num}個字');
INSERT INTO `ts_lang` VALUES ('349', 'PUBLIC_IMAGE_SETTING', 'PUBLIC', '0', '头像设置', 'Image Setting', '頭像設置');
INSERT INTO `ts_lang` VALUES ('350', 'PUBLIC_SHORT_DOMAIN_TYPELIMIT', 'PUBLIC', '0', '仅限5个字符以上20个字符以内的英文/数字/下划线，以英文字母开头，不能含有特殊字符，一经设置，无法更改。', 'Limited to 5-20 characters, English / digital / underline, begin with the letters of the alphabet, can not contain special characters, once set, can not be changed.', '僅限5個字符以上20個字符以內的英文/數字/下劃線，以英文字母開頭，不能含有特殊字符，一經設置，無法更改。');
INSERT INTO `ts_lang` VALUES ('351', 'PUBLIC_SHORT_DOMAIN_CHARACTERLIMIT', 'PUBLIC', '0', '域名长度不能超过20个字符', 'The domain name length can not be more than 20 characters', '域名長度不能超過20個字符');
INSERT INTO `ts_lang` VALUES ('352', 'PUBLIC_DOMAIN_DISABLED', 'PUBLIC', '0', '该个性域名已被禁用', 'The domain has been disabled', '該個性域名已被禁用');
INSERT INTO `ts_lang` VALUES ('353', 'PUBLIC_DOMAIN_OCCUPIED', 'PUBLIC', '0', '此域名已经被使用', 'The domain name has been used', '此域名已經被使用');
INSERT INTO `ts_lang` VALUES ('354', 'PUBLIC_DOMAIN_SETTING_SUCCESS', 'PUBLIC', '0', '域名设置成功', 'Domain set successfully', '域名設置成功');
INSERT INTO `ts_lang` VALUES ('355', 'PUBLIC_PASSWORD_UNSIMILAR', 'PUBLIC', '0', '新密码与确认密码不一致', 'New password and confirm password do not match', '新密碼與確認密碼不一致');
INSERT INTO `ts_lang` VALUES ('356', 'PUBLIC_PASSWORD_SAME', 'PUBLIC', '0', '新密码与旧密码相同', 'New password and old password are the same', '新密碼與舊密碼相同');
INSERT INTO `ts_lang` VALUES ('357', 'PUBLIC_PASSWORD_MODIFY_FAIL', 'PUBLIC', '0', '密码修改失败', 'Password change failed', '密碼修改失敗');
INSERT INTO `ts_lang` VALUES ('358', 'PUBLIC_PASSWORD_MODIFY_SUCCESS', 'PUBLIC', '0', '密码修改成功', 'Password changed successfully', '密碼修改成功');
INSERT INTO `ts_lang` VALUES ('359', 'PUBLIC_ORIGINAL_PASSWORD_ERROR', 'PUBLIC', '0', '原始密码错误', 'Old password is incorrect', '原始密碼錯誤');
INSERT INTO `ts_lang` VALUES ('360', 'PUBLIC_RESET_ACCOUNT', 'PUBLIC', '0', '重置{user}帐号', ' reset the {user} account ', '重置{user}帳號');
INSERT INTO `ts_lang` VALUES ('361', 'PUBLIC_ADD_SUCCESS', 'PUBLIC', '0', '添加成功', 'Added Successfully', '添加成功');
INSERT INTO `ts_lang` VALUES ('362', 'PUBLIC_ADD_FAIL', 'PUBLIC', '0', '添加失败', 'Add failed', '添加失敗');
INSERT INTO `ts_lang` VALUES ('363', 'PUBLIC_INPUT_FEEDBACK', 'PUBLIC', '0', '请填写反馈内容', 'Please fill in the feedback content', '請填寫反饋內容');
INSERT INTO `ts_lang` VALUES ('364', 'PUBLIC_SUBMIT_FEEDBACK_SUCCESS', 'PUBLIC', '0', '提交成功，感谢您的反馈', 'Submitted successfully, thanks for your feedback', '提交成功，感謝您的反饋');
INSERT INTO `ts_lang` VALUES ('365', 'PUBLIC_USER_GROUP_EXIST', 'PUBLIC', '0', '您已经创建过这个分组了', 'You have already created this group', '您已經創建過這個分組了');
INSERT INTO `ts_lang` VALUES ('366', 'PUBLIC_OPERATE_GROUP_FAIL', 'PUBLIC', '0', '分组操作失败', 'Group operate failed', '分組操作失敗');
INSERT INTO `ts_lang` VALUES ('367', 'PUBLIC_FROUPNAME_NOEMPTY', 'PUBLIC', '0', '分组名称不能为空', 'Group name can not be empty', '分組名稱不能爲空');
INSERT INTO `ts_lang` VALUES ('368', 'PUBLIC_SAVE_GROUP_FAIL', 'PUBLIC', '0', '保存分组失败', 'Save group failed', '保存分組失敗');
INSERT INTO `ts_lang` VALUES ('369', 'PUBLIC_SAVE_GROUP_SUCCESS', 'PUBLIC', '0', '保存分组成功', 'Saved group successfully', '保存分組成功');
INSERT INTO `ts_lang` VALUES ('370', 'PUBLIC_WRONG_USER_INFO', 'PUBLIC', '0', '错误的用户信息', 'Incorrect user information', '錯誤的用戶信息');
INSERT INTO `ts_lang` VALUES ('371', 'PUBLIC_NO_FRONTPLATFORM_PERMISSION', 'PUBLIC', '0', '您没有前台管理权限', 'You don‘t have access to front platform management', '您沒有前台管理權限');
INSERT INTO `ts_lang` VALUES ('372', 'PUBLIC_NO_FRONTPLATFORM_PERMISSION_ADMIN', 'PUBLIC', '0', '您没有后台管理权限', ' You have no background administrative privileges', '您沒有後台管理權限');
INSERT INTO `ts_lang` VALUES ('373', 'PUBLIC_PRI_MESSAGE_NOEXIST', 'PUBLIC', '0', '私信不存在', 'Private message does not exist', '私信不存在');
INSERT INTO `ts_lang` VALUES ('374', 'PUBLIC_PRIVATE_MESSAGE_SEND_SUCCESS', 'PUBLIC', '0', '私信发送成功', 'Private message sent successfully', '私信發送成功');
INSERT INTO `ts_lang` VALUES ('375', 'PUBLIC_ADMIN_EDIT_PEIZHI', 'PUBLIC', '0', '保存修改编辑页面配置', 'Save edit page configuration', '保存修改編輯頁面配置');
INSERT INTO `ts_lang` VALUES ('376', 'PUBLIC_ADMIN_EDIT_EDTAIL_PEIZHI', 'PUBLIC', '0', '保存修改编辑详细数据', 'Save edit data', '保存修改編輯詳細數據');
INSERT INTO `ts_lang` VALUES ('377', 'PUBLIC_PRIVATE_MESSAGE_SEND_FAIL', 'PUBLIC', '0', '私信发送失败', 'Private message send failed', '私信發送失敗');
INSERT INTO `ts_lang` VALUES ('378', 'PUBLIC_EMAIL_TYPE_WRONG', 'PUBLIC', '0', '邮箱格式不正确', 'E-mail format is incorrect', '郵箱格式不正確');
INSERT INTO `ts_lang` VALUES ('379', 'PUBLIC_ACCOUNT_NOEXIST', 'PUBLIC', '0', '帐号不存在', 'Account does not exist', '帳號不存在');
INSERT INTO `ts_lang` VALUES ('380', 'PUBLIC_EMAIL_SENDTO_SUCCESS', 'PUBLIC', '0', '邮件已发送到', 'Message has been sent to xxx', '郵件已發送到');
INSERT INTO `ts_lang` VALUES ('381', 'PUBLIC_EMAIL_SENTO_FAIL', 'PUBLIC', '0', '邮件发送失败，请重试', 'E-mail failed to send, please try again', '郵件發送失敗，請重試');
INSERT INTO `ts_lang` VALUES ('382', 'PUBLIC_PASSWORD_RESET_SUCCESS', 'PUBLIC', '0', '密码重置成功', 'Password reset successfully', '密碼重置成功');
INSERT INTO `ts_lang` VALUES ('383', 'PUBLIC_PASSWORD_RESET_FAIL', 'PUBLIC', '0', '密码重置失败', 'Password reset failed', '密碼重置失敗');
INSERT INTO `ts_lang` VALUES ('384', 'PUBLIC_USER_NOEXIST', 'PUBLIC', '0', '不存在相关用户', 'Related user does not exist', '不存在相關用戶');
INSERT INTO `ts_lang` VALUES ('385', 'PUBLIC_REGISTER_FAIL', 'PUBLIC', '0', '注册失败', 'Registration failed', '注冊失敗');
INSERT INTO `ts_lang` VALUES ('386', 'PUBLIC_DEPARTMENT_REQUIRED', 'PUBLIC', '0', '必须选择部门', 'Department is required', '必須選擇部門');
INSERT INTO `ts_lang` VALUES ('387', 'PUBLIC_PROFILE_SAVE_FAIL', 'PUBLIC', '0', '资料保存失败', 'Data save failed', '資料保存失敗');
INSERT INTO `ts_lang` VALUES ('388', 'PUBLIC_TYPE_NOEMPTY', 'PUBLIC', '0', '类型和资源ID不能为空', 'Type and resource ID can not be empty', '類型和資源ID不能爲空');
INSERT INTO `ts_lang` VALUES ('389', 'PUBLIC_INFO_SHARE_FORBIDDEN', 'PUBLIC', '0', '此信息不可以被转发', 'This information can not be shared', '此信息不可以被轉發');
INSERT INTO `ts_lang` VALUES ('390', 'PUBLIC_INPUT_KEYWORD', 'PUBLIC', '0', '请输入关键字', 'Please enter a keyword', '請輸入關鍵字');
INSERT INTO `ts_lang` VALUES ('391', 'PUBLIC_PLUGIN_DIRECTORY_NOEXIST', 'PUBLIC', '0', '插件xxx的目录不存在', 'Plugin directory does not exist', '插件xxx的目錄不存在');
INSERT INTO `ts_lang` VALUES ('392', 'PUBLIC_PLUGIN_NOEXIST', 'PUBLIC', '0', '插件xxx不存在', 'Plugin does not exist', '插件xxx不存在');
INSERT INTO `ts_lang` VALUES ('393', 'PUBLIC_ID_NOEXIST', 'PUBLIC', '0', 'ID不能为空', 'ID can not be empty', 'ID不能爲空');
INSERT INTO `ts_lang` VALUES ('394', 'PUBLIC_DIRECTORY_NOEXIST', 'PUBLIC', '0', '{dir}目录不存在', 'The directory {dir} does not exist', '{dir}目錄不存在');
INSERT INTO `ts_lang` VALUES ('395', 'PUBLIC_DATA_UPGRADE_FAIL', 'PUBLIC', '0', '数据更新失败，可能未做任何修改', 'Data update failed, possibly without making any changes', '數據更新失敗，可能未做任何修改');
INSERT INTO `ts_lang` VALUES ('396', 'PUBLIC_APP_EXIST', 'PUBLIC', '0', '应用已经存在', 'Application already exists', '應用已經存在');
INSERT INTO `ts_lang` VALUES ('397', 'PUBLIC_UPLOAD_APPNAME', 'PUBLIC', '0', '请传入应用名称或者应用ID ', 'Please incoming application name or application ID', '請傳入應用名稱或者應用ID ');
INSERT INTO `ts_lang` VALUES ('398', 'PUBLIC_DATA_INSERT_FAIL', 'PUBLIC', '0', '数据插入失败', 'Data insert failed', '數據插入失敗');
INSERT INTO `ts_lang` VALUES ('399', 'PUBLIC_APP_NOEXIST', 'PUBLIC', '0', '应用不存在或未安装', 'Application does not exist or is not installed', '應用不存在或未安裝');
INSERT INTO `ts_lang` VALUES ('400', 'PUBLIC_POINTS_NOEXIST', 'PUBLIC', '0', '不存在的积分节点', 'Points node does not exist', '不存在的積分節點');
INSERT INTO `ts_lang` VALUES ('401', 'PUBLIC_ATTACHMENT_FAIL', 'PUBLIC', '0', '附件xxx失败', ' annex XXX failed', '附件xxx失敗');
INSERT INTO `ts_lang` VALUES ('402', 'PUBLIC_ATTACHMENT_ID_NOEXIST', 'PUBLIC', '0', '附件ID不能为空', 'Attachment ID can not be empty', '附件ID不能爲空');
INSERT INTO `ts_lang` VALUES ('403', 'PUBLIC_ATTACHMENT_UPLOAD_SUCCESS', 'PUBLIC', '0', '附件上传成功', 'Attachment uploaded successfully', '附件上傳成功');
INSERT INTO `ts_lang` VALUES ('404', 'PUBLIC_ATTACHMENT_UPLOAD_FAIL', 'PUBLIC', '0', '图片上传失败，请重试', 'Attachment upload failed', '圖片上傳失敗，請重試');
INSERT INTO `ts_lang` VALUES ('405', 'PUBLIC_IMAGESIZE_LIMIT', 'PUBLIC', '0', '图片大小不能超过2M', 'Image size can not exceed 2M', '圖片大小不能超過2M');
INSERT INTO `ts_lang` VALUES ('406', 'PUBLIC_IMAGETYPE_LIMIT', 'PUBLIC', '0', '图片类型不允许', 'Image type is not allowed', '圖片類型不允許');
INSERT INTO `ts_lang` VALUES ('407', 'PUBLIC_CATEGORYFORM_UNSIGN', 'PUBLIC', '0', '分类模型表未指定', 'Classification model table is not specified', '分類模型表未指定');
INSERT INTO `ts_lang` VALUES ('408', 'PUBLIC_DATA_MOVE_ERROR', 'PUBLIC', '0', '分类树移动的参数出错', 'Classification move parameters failed', '分類樹移動的參數出錯');
INSERT INTO `ts_lang` VALUES ('409', 'PUBLIC_RESOURCE_ERROR', 'PUBLIC', '0', '资源ID,资源所在表名,资源所在应用不能为空', ' Resources, the resources table, the resources application cannot be empty', '資源ID,資源所在表名,資源所在應用不能爲空');
INSERT INTO `ts_lang` VALUES ('410', 'PUBLIC_FAVORITE_ALREADY', 'PUBLIC', '0', '您已经收藏过了', 'You have already collected the information', '您已經收藏過了');
INSERT INTO `ts_lang` VALUES ('411', 'PUBLIC_FAVORITE_FAIL', 'PUBLIC', '0', '收藏失败,您可能已经收藏此资源', 'Favorite failed, you may have already collected this resource', '收藏失敗,您可能已經收藏此資源');
INSERT INTO `ts_lang` VALUES ('412', 'PUBLIC_CANCEL_FAVORITE_FAIL', 'PUBLIC', '0', '取消失败,您可能已经取消了该信息的收藏', 'Cancel failed, you may have cancelled the collection of information', '取消失敗,您可能已經取消了該信息的收藏');
INSERT INTO `ts_lang` VALUES ('413', 'PUBLIC_REGISTER_REQUIRED', 'PUBLIC', '0', '请先登录', 'Login Now', '請先登錄');
INSERT INTO `ts_lang` VALUES ('414', 'PUBLIC_COMMENT_CONTENT_REQUIRED', 'PUBLIC', '0', '评论内容不可为空', 'Comment content can not be empty', '評論內容不可爲空');
INSERT INTO `ts_lang` VALUES ('415', 'PUBLIC_MODIFY_USER_POINT', 'PUBLIC', '0', '系统修改用户积分', 'System modify user points', '系統修改用戶積分');
INSERT INTO `ts_lang` VALUES ('416', 'PUBLIC_SET_USER_POINT', 'PUBLIC', '0', '系统设定用户积分为', 'System set user points', '系統設定用戶積分爲');
INSERT INTO `ts_lang` VALUES ('417', 'PUBLIC_TOP_DEPARTMENT', 'PUBLIC', '0', '--顶级部门--', 'Top Department', '--頂級部門--');
INSERT INTO `ts_lang` VALUES ('418', 'PUBLIC_DEPARTMENT_ID_REQUIRED', 'PUBLIC', '0', '部门ID不能为空', 'Department ID can not be empty', '部門ID不能爲空');
INSERT INTO `ts_lang` VALUES ('419', 'PUBLIC_CACHE_FAIL', 'PUBLIC', '0', 'Feed缓存写入失败', 'Cache write failed', 'Feed緩存寫入失敗');
INSERT INTO `ts_lang` VALUES ('420', 'PUBLIC_INFO_GET_FAIL', 'PUBLIC', '0', '获取信息失败', 'Get information failed', '獲取信息失敗');
INSERT INTO `ts_lang` VALUES ('421', 'PUBLIC_WEIBO_ID_REQUIRED', 'PUBLIC', '0', '微博ID不能为空', 'Weibo ID can not be empty', '微博ID不能爲空');
INSERT INTO `ts_lang` VALUES ('422', 'PUBLIC_WEIBO_PUBLISH_SUCCESS', 'PUBLIC', '0', '微博发布成功', 'Post Successfully', '微博發布成功');
INSERT INTO `ts_lang` VALUES ('423', 'PUBLIC_UNGROUP', 'PUBLIC', '0', '未分组', 'Ungrouped', '未分組');
INSERT INTO `ts_lang` VALUES ('424', 'PUBLIC_WRONG_DATA', 'PUBLIC', '0', '错误的参数', 'Error parameters', '錯誤的參數');
INSERT INTO `ts_lang` VALUES ('425', 'PUBLIC_FOLLOWING_MYSELF_FORBIDDEN', 'PUBLIC', '0', '不能关注自己', 'Can not follow yourself', '不能關注自己');
INSERT INTO `ts_lang` VALUES ('426', 'PUBLIC_FOLLOWING_PEOPLE_NOEXIST', 'PUBLIC', '0', '被关注的用户不存在', 'Following user does not exist', '被關注的用戶不存在');
INSERT INTO `ts_lang` VALUES ('427', 'PUBLIC_ADD_FOLLOW_SUCCESS', 'PUBLIC', '0', '关注成功', 'Followed Successfully', '關注成功');
INSERT INTO `ts_lang` VALUES ('428', 'PUBLIC_ADD_FOLLOW_FAIL', 'PUBLIC', '0', '关注失败', 'Follow Failed', '關注失敗');
INSERT INTO `ts_lang` VALUES ('429', 'PUBLIC_EMAIL_SUFFIX_FORBIDDEN', 'PUBLIC', '0', '邮箱后缀不允许注册', 'E-mail suffix is not allowed to register', '郵箱後綴不允許注冊');
INSERT INTO `ts_lang` VALUES ('430', 'PUBLIC_INVITE_EMAIL_NOEMPTY', 'PUBLIC', '0', '邀请Email不能为空', 'Invitation Email can not be empty', '邀請Email不能爲空');
INSERT INTO `ts_lang` VALUES ('431', 'PUBLIC_SEND_INVITE_SUCCESS', 'PUBLIC', '0', '发送邀请成功', 'Sent invitation successfully', '發送邀請成功');
INSERT INTO `ts_lang` VALUES ('432', 'PUBLIC_SETTING_FILE', 'PUBLIC', '0', '配置文件：{file}不存在', 'configuration file :{file} not exist', '配置文件：{file}不存在');
INSERT INTO `ts_lang` VALUES ('433', 'PUBLIC_PERMISSION_POINT_NOEXIST', 'PUBLIC', '0', '权限节点不存在', 'Permissions node does not exist', '權限節點不存在');
INSERT INTO `ts_lang` VALUES ('434', 'PUBLIC_REGISTER_SUCCESS', 'PUBLIC', '0', '恭喜你成功注册，请激活你的帐号', 'Congratulations on your successful registration, please activate your account', '恭喜你成功注冊，請激活你的帳號');
INSERT INTO `ts_lang` VALUES ('435', 'PUBLIC_ACCOUNT_REVIEW_FAIL', 'PUBLIC', '0', '您的帐号未通过审核', 'Your account is not approved', '您的帳號未通過審核');
INSERT INTO `ts_lang` VALUES ('436', 'PUBLIC_RESET_PASSWORD-FAIL', 'PUBLIC', '0', '请重置您的登录密码', 'Please reset your login password', '請重置您的登錄密碼');
INSERT INTO `ts_lang` VALUES ('437', 'PUBLIC_RESET_PASSWORD-SUCCESS', 'PUBLIC', '0', '重置密码成功，请记住您的新密码', 'Reset the password successfully, remember your new password', '重置密碼成功，請記住您的新密碼');
INSERT INTO `ts_lang` VALUES ('438', 'PUBLIC_GET_NEW_COMMENT', 'PUBLIC', '0', '您收到了新的评论', 'You have received a new comment', '您收到了新的評論');
INSERT INTO `ts_lang` VALUES ('439', 'PUBLIC_YOUR_COLLEAGUE', 'PUBLIC', '0', '您的好友{user}', 'Your friend {user}', '您的好友{user}');
INSERT INTO `ts_lang` VALUES ('440', 'PUBLIC_GET_NEW_SHARE', 'PUBLIC', '0', '您收到了新的转发', 'You have received a new share', '您收到了新的轉發');
INSERT INTO `ts_lang` VALUES ('441', 'PUBLIC_INVITE_REGISTER', 'PUBLIC', '0', '邀请您注册网站', 'Invite you to register site', '邀請您注冊網站');
INSERT INTO `ts_lang` VALUES ('442', 'PUBLIC_GET_NEW_PRI_MESSAGE', 'PUBLIC', '0', '您收到了一条新私信', 'You have received a new private letter', '您收到了一條新私信');
INSERT INTO `ts_lang` VALUES ('443', 'PUBLIC_VERIFY_CODE_ERROR', 'PUBLIC', '0', '验证码错误', 'Verification code error', '驗證碼錯誤');
INSERT INTO `ts_lang` VALUES ('444', 'PUBLIC_REVIEW_FAIL', 'PUBLIC', '0', '审核失败', 'Review Failed', '審核失敗');
INSERT INTO `ts_lang` VALUES ('445', 'PUBLIC_CANCEL_REVIEW_SUCCESS', 'PUBLIC', '0', '取消审核成功', 'Canceled review successfully', '取消審核成功');
INSERT INTO `ts_lang` VALUES ('446', 'PUBLIC_REVIEW_SUCCESS', 'PUBLIC', '0', '审核成功', 'Review Successfully', '審核成功');
INSERT INTO `ts_lang` VALUES ('447', 'PUBLIC_ACCOUNT_INITIALIZE_SUCCESS', 'PUBLIC', '0', '帐号初始化成功', 'Account initialized successfully', '帳號初始化成功');
INSERT INTO `ts_lang` VALUES ('448', 'PUBLIC_ACCOUNT_INITIALIZE_FAIL', 'PUBLIC', '0', '帐号初始化失败', 'Account initialize failed ', '帳號初始化失敗');
INSERT INTO `ts_lang` VALUES ('449', 'PUBLIC_MODIFY_NO_REQUIRED', 'PUBLIC', '0', '不需要修改', 'Not need to modify', '不需要修改');
INSERT INTO `ts_lang` VALUES ('450', 'PUBLIC_USER_GROUP_EMPTY', 'PUBLIC', '0', '用户组或用户不能为空', 'User group or user can not be empty', '用戶組或用戶不能爲空');
INSERT INTO `ts_lang` VALUES ('451', 'PUBLIC_UID_INDEX_ILLEAGAL', 'PUBLIC', '0', 'UID参数值不合法', 'UID value is illegal', 'UID參數值不合法');
INSERT INTO `ts_lang` VALUES ('452', 'PUBLIC_GET_USERINFO_FAIL', 'PUBLIC', '0', '获取用户信息缓存失败', 'Get user information cache failed', '獲取用戶信息緩存失敗');
INSERT INTO `ts_lang` VALUES ('453', 'PUBLIC_GET_USERPROFILE_FAIL', 'PUBLIC', '0', '获取用户档案失败', 'Get user profile failed', '獲取用戶檔案失敗');
INSERT INTO `ts_lang` VALUES ('454', 'PUBLIC_ADD_USER_FAIL', 'PUBLIC', '0', '添加用户失败', 'Add users failed', '添加用戶失敗');
INSERT INTO `ts_lang` VALUES ('455', 'PUBLIC_DISABLE_ACCOUNT_FAIL', 'PUBLIC', '0', '禁用帐号失败', 'Disable user failed', '禁用帳號失敗');
INSERT INTO `ts_lang` VALUES ('456', 'PUBLIC_RECOVER_ACCOUNT_FAIL', 'PUBLIC', '0', '恢复帐号失败', 'Restore account failed', '恢複帳號失敗');
INSERT INTO `ts_lang` VALUES ('457', 'PUBLIC_ILLEGAL_TYPE_INDEX', 'PUBLIC', '0', '非法的type参数', 'Illegal Type', '非法的type參數');
INSERT INTO `ts_lang` VALUES ('458', 'PUBLIC_ACTIVATE_USER_FAIL', 'PUBLIC', '0', '激活用户失败', 'Activate user  failed', '激活用戶失敗');
INSERT INTO `ts_lang` VALUES ('459', 'PUBLIC_INSERT_INDEX_ILLEGAL', 'PUBLIC', '0', '传入ID参数不合法', 'Incoming ID parameter is illegal', '傳入ID參數不合法');
INSERT INTO `ts_lang` VALUES ('460', 'PUBLIC_FIELD_REQUIRED', 'PUBLIC', '0', '字段ID不可以为空', 'Field ID can not be empty', '字段ID不可以爲空');
INSERT INTO `ts_lang` VALUES ('461', 'PUBLIC_FIELD_DELETE_FAIL', 'PUBLIC', '0', '字段删除失败', 'Delete field failed', '字段刪除失敗');
INSERT INTO `ts_lang` VALUES ('462', 'PUBLIC_INPUT_FORM', 'PUBLIC', '0', '输入表单', 'Input Form', '輸入表單');
INSERT INTO `ts_lang` VALUES ('463', 'PUBLIC_SEVERAL_TEXTFIELD', 'PUBLIC', '0', '多行文本', ' multiple lines of text', '多行文本');
INSERT INTO `ts_lang` VALUES ('464', 'PUBLIC_DROPDOWN_MENU', 'PUBLIC', '0', '下拉菜单', 'Drop-down Menu', '下拉菜單');
INSERT INTO `ts_lang` VALUES ('465', 'PUBLIC_RADIO_BUTTON', 'PUBLIC', '0', '单选框', 'Radio Button', '單選框');
INSERT INTO `ts_lang` VALUES ('466', 'PUBLIC_CHECK_BOX', 'PUBLIC', '0', '复选框', 'Check Box', '複選框');
INSERT INTO `ts_lang` VALUES ('467', 'PUBLIC_TIME_SELECT', 'PUBLIC', '0', '时间选择', 'Time Select', '時間選擇');
INSERT INTO `ts_lang` VALUES ('468', 'PUBLIC_USER_SELECT', 'PUBLIC', '0', '用户选择', 'User Select', '用戶選擇');
INSERT INTO `ts_lang` VALUES ('469', 'PUBLIC_DEPARTMENT_SELECT', 'PUBLIC', '0', '部门选择', 'Department Select', '部門選擇');
INSERT INTO `ts_lang` VALUES ('470', 'PUBLIC_SAVE', 'PUBLIC', '0', '保存', 'Save', '保存');
INSERT INTO `ts_lang` VALUES ('471', 'PUBLIC_CANCEL', 'PUBLIC', '0', '取消', 'Cancel', '取消');
INSERT INTO `ts_lang` VALUES ('472', 'PUBLIC_SYSTEM_POINT_RULE', 'PUBLIC', '0', '积分规则', 'Point Rule', '積分規則');
INSERT INTO `ts_lang` VALUES ('473', 'PUBLIC_SYSTEM_POINT_TYPE', 'PUBLIC', '0', '积分类型', 'Point Type', '積分類型');
INSERT INTO `ts_lang` VALUES ('474', 'PUBLIC_SYSTEM_POINT_SETTING', 'PUBLIC', '0', '设置用户积分', 'Set user point', '設置用戶積分');
INSERT INTO `ts_lang` VALUES ('475', 'PUBLIC_SYSTEM_POINT_LEVEL', 'PUBLIC', '0', '积分等级', 'Point Level', '積分等級');
INSERT INTO `ts_lang` VALUES ('476', 'PUBLIC_SYSTEM_ADD_POINT_TYPE', 'PUBLIC', '0', '添加积分类型', 'Add Point Type', '添加積分類型');
INSERT INTO `ts_lang` VALUES ('477', 'PUBLIC_SYSTEM_EDIT_POINT_TYPE', 'PUBLIC', '0', '编辑积分类型', 'Edit Point Type', '編輯積分類型');
INSERT INTO `ts_lang` VALUES ('478', 'PUBLIC_NO_OPERATION_DATA', 'PUBLIC', '0', '没有要操作的数据', 'No data to operate', '沒有要操作的數據');
INSERT INTO `ts_lang` VALUES ('479', 'PUBLIC_SYSTEM_ASSIGNED_USERGROUP', 'PUBLIC', '0', '指定用户组', 'Specify user group', '指定用戶組');
INSERT INTO `ts_lang` VALUES ('480', 'PUBLIC_SYSTEM_ASSIGNED_ID', 'PUBLIC', '0', '指定ID', 'Specified ID', '指定ID');
INSERT INTO `ts_lang` VALUES ('481', 'PUBLIC_SYSTEM_POINT_CHANGE', 'PUBLIC', '0', '增减', 'Increase or decrease', '增減');
INSERT INTO `ts_lang` VALUES ('482', 'PUBLIC_SYSTEM_POINT_SET', 'PUBLIC', '0', '设定', 'Set', '設定');
INSERT INTO `ts_lang` VALUES ('483', 'PUBLIC_SYSTEM_NUMBER_NOEMPTY', 'PUBLIC', '0', '数量不能为空', 'Quantity can not be empty', '數量不能爲空');
INSERT INTO `ts_lang` VALUES ('484', 'PUBLIC_SYSTEM_SET_USER_POINT', 'PUBLIC', '0', '设置用户积分', 'Set the user points', '設置用戶積分');
INSERT INTO `ts_lang` VALUES ('485', 'PUBLIC_SYSTEM_SETTING_FAIL', 'PUBLIC', '0', '设置失败', 'Set Failed', '設置失敗');
INSERT INTO `ts_lang` VALUES ('486', 'PUBLIC_SYSTEM_USERGROUP_NOEXIST', 'PUBLIC', '0', '用户组不存在', 'User group does not exist', '用戶組不存在');
INSERT INTO `ts_lang` VALUES ('487', 'PUBLIC_SYSTEM_MODIFY_SUCCESS', 'PUBLIC', '0', '修改成功', 'Modified Successfully', '修改成功');
INSERT INTO `ts_lang` VALUES ('488', 'PUBLIC_SYSTEM_MOVE_FAIL', 'PUBLIC', '0', '移除失败', 'Remove Failed', '移除失敗');
INSERT INTO `ts_lang` VALUES ('489', 'PUBLIC_SYSTEM_MOVE_SUCCESS', 'PUBLIC', '0', '移除成功', 'Removed Successfully', '移除成功');
INSERT INTO `ts_lang` VALUES ('490', 'PUBLIC_REMARK_ADD_FAIL', 'PUBLIC', '0', '备注添加失败', 'Add remark failed', '備注添加失敗');
INSERT INTO `ts_lang` VALUES ('491', 'PUBLIC_LOGIN_SUCCESS', 'PUBLIC', '0', '登录成功', 'Login Successfully', '登錄成功');
INSERT INTO `ts_lang` VALUES ('492', 'PUBLIC_UPLOAD_IMAGE_TIPS', 'PUBLIC', '0', '支持jpg,jpge,gif,png,bmp格式，大小限制2M以内', 'Support jpg, jpge, gif, png, bmp format, size limit less than 2M', '支持jpg,jpge,gif,png,bmp格式，大小限制2M以內');
INSERT INTO `ts_lang` VALUES ('493', 'PUBLIC_COMPANY_EMAIL', 'PUBLIC', '0', '公司邮箱', 'Company E-mail', '公司郵箱');
INSERT INTO `ts_lang` VALUES ('494', 'PUBLIC_MAIL', 'PUBLIC', '0', '邮件', 'E-mail', '郵件');
INSERT INTO `ts_lang` VALUES ('495', 'PUBLIC_SYSTEM_MAIL', 'PUBLIC', '0', '接收系统邮件', 'Receive system mail', '接收系統郵件');
INSERT INTO `ts_lang` VALUES ('496', 'PUBLIC_PANEL_NOEXIST', 'PUBLIC', '0', '该插件无管理面板', 'The plug-in does not exist  management  panel', '該插件無管理面板');
INSERT INTO `ts_lang` VALUES ('497', 'PUBLIC_ALREADY_TOP', 'PUBLIC', '0', '已经是最前面了', 'It is already the top', '已經是最前面了');
INSERT INTO `ts_lang` VALUES ('498', 'PUBLIC_PLUGIN_NAME', 'PUBLIC', '0', '插件名称', 'Plugin Name', '插件名稱');
INSERT INTO `ts_lang` VALUES ('499', 'PUBLIC_ADMINISTRATOR_LOGIN', 'PUBLIC', '0', '管理员登录', 'Administrator Login', '管理員登錄');
INSERT INTO `ts_lang` VALUES ('500', 'PUBLIC_VERIFICATION_CODE', 'PUBLIC', '0', '验证码', 'Verification Code', '驗證碼');
INSERT INTO `ts_lang` VALUES ('501', 'PUBLIC_CHANGE_ONE', 'PUBLIC', '0', '换一张', 'Change One', '換一張');
INSERT INTO `ts_lang` VALUES ('502', 'PUBLIC_TRANSFER_USER', 'PUBLIC', '0', '将用户转移至下面的部门下', 'Transfer the user to the following departments', '將用戶轉移至下面的部門下');
INSERT INTO `ts_lang` VALUES ('503', 'PUBLIC_SELECT_USERGROUP', 'PUBLIC', '0', '请选择用户组', 'Please select the user group', '請選擇用戶組');
INSERT INTO `ts_lang` VALUES ('504', 'PUBLIC_HELLO', 'PUBLIC', '0', '您好', 'Hello', '您好');
INSERT INTO `ts_lang` VALUES ('505', 'PUBLIC_WELCOME', 'PUBLIC', '0', '欢迎使用ThinkSNS社会化沟通、协作、管理平台', 'Welcome to use ThinkSNS social communication, collaboration, and management platform', '歡迎使用ThinkSNS社會化溝通、協作、管理平台');
INSERT INTO `ts_lang` VALUES ('506', 'PUBLIC_FOLD_TIPS', 'PUBLIC', '0', '提示：点击标题可以折叠栏目', 'Tip: Click on the title can be folded part', '提示：點擊標題可以折疊欄目');
INSERT INTO `ts_lang` VALUES ('507', 'PUBLIC_USER＿INFORMATION', 'PUBLIC', '0', '用户信息', 'User Information', '用戶信息');
INSERT INTO `ts_lang` VALUES ('508', 'PUBLIC_TOTAL_REGISTERED_USERS', 'PUBLIC', '0', '总注册用户', 'Total registered users', '總注冊用戶');
INSERT INTO `ts_lang` VALUES ('509', 'PUBLIC_TOTAL_ACTIVE_USERS', 'PUBLIC', '0', '总活跃用户', 'Total Active Users', '總活躍用戶');
INSERT INTO `ts_lang` VALUES ('510', 'PUBLIC_LARGEST_ONLINE_YESTERDAY', 'PUBLIC', '0', '昨日最大在线量', 'Largest Online Yesterday', '昨日最大在線量');
INSERT INTO `ts_lang` VALUES ('511', 'PUBLIC_ONLINE_CURRENT', 'PUBLIC', '0', '当前在线用户数', 'Current Online Number', '當前在線用戶數');
INSERT INTO `ts_lang` VALUES ('512', 'PUBLIC_LARGEST_ONLINE_WEEK', 'PUBLIC', '0', '一周最大在线用户数', 'Largest Online One Week', '一周最大在線用戶數');
INSERT INTO `ts_lang` VALUES ('513', 'PUBLIC_ACCESS_INFORMATION', 'PUBLIC', '0', '访问信息', 'Access Information', '訪問信息');
INSERT INTO `ts_lang` VALUES ('514', 'PUBLIC_TIME', 'PUBLIC', '0', '时间', 'Time', '時間');
INSERT INTO `ts_lang` VALUES ('515', 'PUBLIC_PAGE_VIEWS', 'PUBLIC', '0', '浏览量', 'Page Views', '浏覽量');
INSERT INTO `ts_lang` VALUES ('516', 'PUBLIC_INDEPENDENT_VISITORS', 'PUBLIC', '0', '独立访客量', 'Independent Visitors', '獨立訪客量');
INSERT INTO `ts_lang` VALUES ('517', 'PUBLIC_PER_CAPITA_VIEWS', 'PUBLIC', '0', '人均浏览次数', 'Per Capita Views', '人均浏覽次數');
INSERT INTO `ts_lang` VALUES ('518', 'PUBLIC_ONE_WEEK_AVERAGE', 'PUBLIC', '0', '一周平均', 'One Week Average', '一周平均');
INSERT INTO `ts_lang` VALUES ('519', 'PUBLIC_SERVER_INFORMATION', 'PUBLIC', '0', '服务器信息', 'Server Information', '服務器信息');
INSERT INTO `ts_lang` VALUES ('520', 'PUBLIC_PAGE_CONFIGURATION', 'PUBLIC', '0', '[页面配置]', '[Page Configuration]', '[頁面配置]');
INSERT INTO `ts_lang` VALUES ('521', 'PUBLIC_EDIT_USER', 'PUBLIC', '0', '编辑用户', 'Edit User', '編輯用戶');
INSERT INTO `ts_lang` VALUES ('522', 'PUBLIC_INPUT_TEXT', 'PUBLIC', '0', '文本框:input_text', 'Text Box: input_text', '文本框:input_text');
INSERT INTO `ts_lang` VALUES ('523', 'PUBLIC_INPUT_PASSWORD', 'PUBLIC', '0', '密码框:input_password', 'Password Box: input_password', '密碼框:input_password');
INSERT INTO `ts_lang` VALUES ('524', 'PUBLIC_SELECT', 'PUBLIC', '0', '选择', 'Select', '選擇');
INSERT INTO `ts_lang` VALUES ('525', 'PUBLIC_RADIO', 'PUBLIC', '0', '单选框:radio', 'Radiobutton: radio', '單選框:radio');
INSERT INTO `ts_lang` VALUES ('526', 'PUBLIC_CHAECKBOX', 'PUBLIC', '0', '多选框:checkbox', 'Checkboxes: checkbox', '多選框:checkbox');
INSERT INTO `ts_lang` VALUES ('527', 'PUBLIC_DATE', 'PUBLIC', '0', '日期插件:date', 'Date Of Plug-ins: date', '日期插件:date');
INSERT INTO `ts_lang` VALUES ('528', 'PUBLIC_TEXTAREA', 'PUBLIC', '0', '文本域:textarea', 'Text Fields: textarea', '文本域:textarea');
INSERT INTO `ts_lang` VALUES ('529', 'PUBLIC_WORD', 'PUBLIC', '0', '纯文字:word', 'Text: word', '純文字:word');
INSERT INTO `ts_lang` VALUES ('530', 'PUBLIC_INPUT_HIDDEN', 'PUBLIC', '0', '隐藏值:input_hidden', 'Hidden Value: input_hidden', '隱藏值:input_hidden');
INSERT INTO `ts_lang` VALUES ('531', 'PUBLIC_INPUT_FILE', 'PUBLIC', '0', '附件上传:input_file', 'Attachment Upload: input_file', '附件上傳:input_file');
INSERT INTO `ts_lang` VALUES ('532', 'PUBLIC_ADVERTISEMENT_MANAGEMENT', 'PUBLIC', '0', '广告管理', 'Advertisement Management', '廣告管理');
INSERT INTO `ts_lang` VALUES ('533', 'PUBLIC_ADD_ADVERTISEMENT', 'PUBLIC', '0', '添加广告', 'Add Advertisement', '添加廣告');
INSERT INTO `ts_lang` VALUES ('534', 'PUBLIC_DELETE_ADVERTISEMENT', 'PUBLIC', '0', '删除广告', 'Delete Advertisement', '刪除廣告');
INSERT INTO `ts_lang` VALUES ('535', 'PUBLIC_IS_VALID', 'PUBLIC', '0', '是否有效', 'Is Valid', '是否有效');
INSERT INTO `ts_lang` VALUES ('536', 'PUBLIC_UPDATE_TIME', 'PUBLIC', '0', '更新时间', 'Updat Time', '更新時間');
INSERT INTO `ts_lang` VALUES ('537', 'PUBLIC_CREATE_TIME', 'PUBLIC', '0', '创建时间', 'Create Time', '創建時間');
INSERT INTO `ts_lang` VALUES ('538', 'PUBLIC_LOCATION', 'PUBLIC', '0', '位置', 'Location', '位置');
INSERT INTO `ts_lang` VALUES ('539', 'PUBLIC_LAST_SEVEN_DAYS', 'PUBLIC', '0', '最近7天', 'Last 7 Days', '最近7天');
INSERT INTO `ts_lang` VALUES ('540', 'PUBLIC_LAST_THIRTY_DAYS', 'PUBLIC', '0', '最近30天', 'Last 30 Days', '最近30天');
INSERT INTO `ts_lang` VALUES ('541', 'PUBLIC_TODAY', 'PUBLIC', '0', '今日', 'Today', '今日');
INSERT INTO `ts_lang` VALUES ('542', 'PUBLIC_YESTERDAY', 'PUBLIC', '0', '昨日', 'Yesterday', '昨日');
INSERT INTO `ts_lang` VALUES ('543', 'PUBLIC_THIS_MONTH', 'PUBLIC', '0', '本月', 'Month', '本月');
INSERT INTO `ts_lang` VALUES ('544', 'PUBLIC_STATISTICAL_TIME', 'PUBLIC', '0', '统计时间', 'Statistical Time', '統計時間');
INSERT INTO `ts_lang` VALUES ('545', 'PUBLIC_VIEWS_PV', 'PUBLIC', '0', '浏览量/PV', 'Views / PV', '浏覽量/PV');
INSERT INTO `ts_lang` VALUES ('546', 'PUBLIC_MAXIMUM_ONLINE_TIME', 'PUBLIC', '0', '最大在线量时间段', 'Maximum Online Time', '最大在線量時間段');
INSERT INTO `ts_lang` VALUES ('547', 'PUBLIC_MAXIMUM_ONLINE_SAME_TIME', 'PUBLIC', '0', '最大同时在线量', 'Maximum Online The Same Time', '最大同時在線量');
INSERT INTO `ts_lang` VALUES ('548', 'PUBLIC_TRANSFER_USER_SUBSECTOR', 'PUBLIC', '0', '请将部门用户及子部门移至新部门', 'Please transfer sector users and sub-sector to a new department', '請將部門用戶及子部門移至新部門');
INSERT INTO `ts_lang` VALUES ('549', 'PUBLIC_TRANSFER_SUBSECTOR_TIPS', 'PUBLIC', '0', '注：不能将子部门移动到当前部门或当前部门子部门下。', 'Note: The sub-sector can not be moved to the department or department sub-sector.', '注：不能將子部門移動到當前部門或當前部門子部門下。');
INSERT INTO `ts_lang` VALUES ('550', 'PUBLIC_DEPARTMENT_ORDER', 'PUBLIC', '0', '部门顺序', 'Department Order', '部門順序');
INSERT INTO `ts_lang` VALUES ('551', 'PUBLIC_DEPARTMENT_NAME', 'PUBLIC', '0', '部门名称', 'Department Name', '部門名稱');
INSERT INTO `ts_lang` VALUES ('552', 'PUBLIC_REMOVE_CONTENT', 'PUBLIC', '0', '删除举报', 'Remove Content', '刪除举报');
INSERT INTO `ts_lang` VALUES ('553', 'PUBLIC_REVOKE_REPORT', 'PUBLIC', '0', '撤销举报', 'Revoke Report', '撤銷舉報');
INSERT INTO `ts_lang` VALUES ('554', 'PUBLIC_REPORT_FRONT_WORDS', 'PUBLIC', '0', '被举报内容前140字', '140 words in front of the report content', '被舉報內容前140字');
INSERT INTO `ts_lang` VALUES ('555', 'PUBLIC_INFORMANTS_USER_NAME', 'PUBLIC', '0', '被举报人用户名', 'Informants User Name', '被舉報人用戶名');
INSERT INTO `ts_lang` VALUES ('556', 'PUBLIC_ORIGINAL_INFORMATION', 'PUBLIC', '0', '查看原信息', 'View Original Information', '查看原信息');
INSERT INTO `ts_lang` VALUES ('557', 'PUBLIC_SYSTEM_AUTOMATIC', 'PUBLIC', '0', '系统自动', 'System Automatically', '系統自動');
INSERT INTO `ts_lang` VALUES ('558', 'PUBLIC_REPORT_REASON', 'PUBLIC', '0', '举报理由', 'Report Reason', '舉報理由');
INSERT INTO `ts_lang` VALUES ('559', 'PUBLIC_OPERATION', 'PUBLIC', '0', '操作', 'Operation', '操作');
INSERT INTO `ts_lang` VALUES ('560', 'PUBLIC_SEARCH_REPORT', 'PUBLIC', '0', '搜索举报', 'Search Report', '搜索舉報');
INSERT INTO `ts_lang` VALUES ('561', 'PUBLIC_SEARCH_FINISHIED', 'PUBLIC', '0', '搜索完毕', 'Search Finished', '搜索完畢');
INSERT INTO `ts_lang` VALUES ('562', 'PUBLIC_WAIT_OPERATION', 'PUBLIC', '0', '等待操作', 'Wait Operation', '等待操作');
INSERT INTO `ts_lang` VALUES ('563', 'PUBLIC_COME_FROM', 'PUBLIC', '0', '来自', 'come from', '來自');
INSERT INTO `ts_lang` VALUES ('564', 'PUBLIC_MULTIPLE_TIPS', 'PUBLIC', '0', '多个时使用英文的“,”分割', 'Multiple use English\",\" segmentation', '多個時使用英文的“,”分割');
INSERT INTO `ts_lang` VALUES ('565', 'PUBLIC_INTEGRAL_CONFIGURATION', 'PUBLIC', '0', '不存在积分配置', 'Does not exist the integral configuration', '不存在積分配置');
INSERT INTO `ts_lang` VALUES ('566', 'PUBLIC_ACTION_ALIAS', 'PUBLIC', '0', '动作别名', 'Action Alias', '動作別名');
INSERT INTO `ts_lang` VALUES ('567', 'PUBLIC_ACTION_NAME', 'PUBLIC', '0', '动作名', 'Action Name', '動作名');
INSERT INTO `ts_lang` VALUES ('568', 'PUBLIC_LEVEL_NAME', 'PUBLIC', '0', '等级名称', 'Level Name', '等級名稱');
INSERT INTO `ts_lang` VALUES ('569', 'PUBLIC_LEVEL_ICON', 'PUBLIC', '0', '等级图标', 'Level Icon', '等級圖標');
INSERT INTO `ts_lang` VALUES ('570', 'PUBLIC_EXPERIENCE', 'PUBLIC', '0', '经验值', 'Experience', '經驗值');
INSERT INTO `ts_lang` VALUES ('571', 'PUBLIC_WEIBO_TYPE_SETTING', 'PUBLIC', '0', '设置开启的微博类型', 'Set the type of weibo', '設置開啓的微博類型');
INSERT INTO `ts_lang` VALUES ('572', 'PUBLIC_WEIBO_CONFIGURATION', 'PUBLIC', '0', '微博配置', 'Weibo Configuration', '微博配置');
INSERT INTO `ts_lang` VALUES ('573', 'PUBLIC_ACTION_APPLICATIONS_NOEMPTY', 'PUBLIC', '0', '动作名和应用不能为空', 'Action name and applications can not be empty', '動作名和應用不能爲空');
INSERT INTO `ts_lang` VALUES ('574', 'PUBLIC_APPLICATIONS_MODULE_RULES_NOEMPTY', 'PUBLIC', '0', '应用名称、权限模块、权限规则不能有空值', 'Application name,permission module,permission rules can not be null value', '應用名稱、權限模塊、權限規則不能有空值');
INSERT INTO `ts_lang` VALUES ('575', 'PUBLIC_ADD_TEMPLATE', 'PUBLIC', '0', '添加微博模板', 'Add Weibo Template', '添加微博模板');
INSERT INTO `ts_lang` VALUES ('576', 'PUBLIC_EDIT_TEMPLATE', 'PUBLIC', '0', '编辑微博模板', 'Edit Weibo Template', '編輯微博模板');
INSERT INTO `ts_lang` VALUES ('577', 'PUBLIC_APPLICATIONS_TYPE_TEMPLATE_NOEMPTY', 'PUBLIC', '0', '应用名称、微博类型、模板内容不能有空值', 'Application name, Weibo type, template content can not be empty', '應用名稱、微博類型、模板內容不能有空值');
INSERT INTO `ts_lang` VALUES ('578', 'PUBLIC_TEMPLATE_DELETE', 'PUBLIC', '0', '微博模板删除', 'Weibo template deleted', '微博模板刪除');
INSERT INTO `ts_lang` VALUES ('579', 'PUBLIC_SPECIFIED_MAILBOX_SUFFIX', 'PUBLIC', '0', '指定邮箱后缀', 'Specified Mailbox Suffix', '指定郵箱後綴');
INSERT INTO `ts_lang` VALUES ('580', 'PUBLIC_ANY_EMAIL_SUFFIX', 'PUBLIC', '0', '任何邮箱后缀', 'Any Mailbox Suffix', '任何郵箱後綴');
INSERT INTO `ts_lang` VALUES ('581', 'PUBLIC_CLOSE_REGISTRATION', 'PUBLIC', '0', '关闭注册', 'Close Registration', '關閉注冊');
INSERT INTO `ts_lang` VALUES ('582', 'PUBLIC_SPECIFIED_USER', 'PUBLIC', '0', '指定用户', 'Specified User', '指定用戶');
INSERT INTO `ts_lang` VALUES ('583', 'PUBLIC_ALL_USERS', 'PUBLIC', '0', '全部用户', 'All Users', '全部用戶');
INSERT INTO `ts_lang` VALUES ('584', 'PUBLIC_FOOTER_ARTICLE', 'PUBLIC', '0', '页脚文章', 'Footer Article', '頁腳文章');
INSERT INTO `ts_lang` VALUES ('585', 'PUBLIC_SAVE_MODIFIED', 'PUBLIC', '0', '保存修改', 'Save Modified', '保存修改');
INSERT INTO `ts_lang` VALUES ('586', 'PUBLIC_EDIT_ANNOUNCEMENT', 'PUBLIC', '0', '编辑公告', 'Edit Announcement', '編輯公告');
INSERT INTO `ts_lang` VALUES ('587', 'PUBLIC_NAVIGATION_LIST', 'PUBLIC', '0', '导航列表', 'Navigation list', '導航列表');
INSERT INTO `ts_lang` VALUES ('588', 'PUBLIC_SENSITIVE_SAVE_FAIL', 'PUBLIC', '0', '敏感词保存失败', 'Sensitive to the word save failed', '敏感詞保存失敗');
INSERT INTO `ts_lang` VALUES ('589', 'PUBLIC_ADD_NAVIGATION', 'PUBLIC', '0', '增加导航', 'Add Navigation', '增加導航');
INSERT INTO `ts_lang` VALUES ('590', 'PUBLIC_NEW_WINDOW', 'PUBLIC', '0', '新窗口', 'New Window', '新窗口');
INSERT INTO `ts_lang` VALUES ('591', 'PUBLIC_CURRENT_WINDOW', 'PUBLIC', '0', '当前窗口', 'Current Window', '當前窗口');
INSERT INTO `ts_lang` VALUES ('592', 'PUBLIC_PARENT_WINDOW', 'PUBLIC', '0', '父窗口', 'Parent Window', '父窗口');
INSERT INTO `ts_lang` VALUES ('593', 'PUBLIC_HEAD_NAVIGATION', 'PUBLIC', '0', '头部导航', 'Head Navigation', '頭部導航');
INSERT INTO `ts_lang` VALUES ('594', 'PUBLIC_BOTTOM_NAVIGATION', 'PUBLIC', '0', '底部导航', 'Bottom Navigation', '底部導航');
INSERT INTO `ts_lang` VALUES ('595', 'PUBLIC_INTERNAL_APPLICATION', 'PUBLIC', '0', '应用内部', 'Internal Application', '應用內部');
INSERT INTO `ts_lang` VALUES ('596', 'PUBLIC_ADD_SUBNAVIGATION', 'PUBLIC', '0', '添加子导航', 'Add Sub-navigation', '添加子導航');
INSERT INTO `ts_lang` VALUES ('597', 'PUBLIC_NAVIGATION_NAME_NOEWPTY', 'PUBLIC', '0', '导航名称不能为空', 'Navigation name can not be empty', '導航名稱不能爲空');
INSERT INTO `ts_lang` VALUES ('598', 'PUBLIC_APPLICATION_LOGO_NOEMPTY', 'PUBLIC', '0', '应用标志不能为空', 'Application logo can not be empty', '應用標志不能爲空');
INSERT INTO `ts_lang` VALUES ('599', 'PUBLIC_LINK_NOEMPTY', 'PUBLIC', '0', '链接地址不能为空', 'The link address can not be empty', '鏈接地址不能爲空');
INSERT INTO `ts_lang` VALUES ('600', 'PUBLIC_NAVIGATION_POSITION_NOEWPTY', 'PUBLIC', '0', '导航位置不能为空', 'Navigation position can not be empty', '導航位置不能爲空');
INSERT INTO `ts_lang` VALUES ('601', 'PUBLIC_APPLICATION_SORT_NOEMPTY', 'PUBLIC', '0', '应用排序不能为空', 'Application of sorting can not be empty', '應用排序不能爲空');
INSERT INTO `ts_lang` VALUES ('602', 'PUBLIC_PHP_FILE', 'PUBLIC', '0', 'PHP文件', 'PHP File', 'PHP文件');
INSERT INTO `ts_lang` VALUES ('603', 'PUBLIC_JAVASCRIPT_FILE', 'PUBLIC', '0', 'JavaScript文件', 'JavaScript File', 'JavaScript文件');
INSERT INTO `ts_lang` VALUES ('604', 'PUBLIC_ADD_LANGUAGE_CONFIGURATION', 'PUBLIC', '0', '添加语言配置', 'Add Language Configuration', '添加語言配置');
INSERT INTO `ts_lang` VALUES ('605', 'PUBLIC_EDIT_LANGUAGE_CONFIGURATION', 'PUBLIC', '0', '编辑语言配置', 'Edit Language Configuration', '編輯語言配置');
INSERT INTO `ts_lang` VALUES ('606', 'PUBLIC_LANGUAGE_CONFIGURATION_ALREADY_EXIST', 'PUBLIC', '0', '此条语言配置项已存在', 'This language configuration already exists', '此條語言配置項已存在');
INSERT INTO `ts_lang` VALUES ('607', 'PUBLIC_UPDATE_WIDGET', 'PUBLIC', '0', '更新Widget', 'Update Widget', '更新Widget');
INSERT INTO `ts_lang` VALUES ('608', 'PUBLIC_ORDINARY_WEIBO', 'PUBLIC', '0', '普通微博', 'Ordinary Weibo', '普通微博');
INSERT INTO `ts_lang` VALUES ('609', 'PUBLIC_SHARE_WEIBO', 'PUBLIC', '0', '转发微博', 'Share Weibo', '轉發微博');
INSERT INTO `ts_lang` VALUES ('610', 'PUBLIC_PICTURE_WEIBO', 'PUBLIC', '0', '图片微博', 'Picture Weibo', '圖片微博');
INSERT INTO `ts_lang` VALUES ('611', 'PUBLIC_ATTACHMENT_WEIBO', 'PUBLIC', '0', '附件微博', 'Attachment Weibo', '附件微博');
INSERT INTO `ts_lang` VALUES ('612', 'PUBLIC_DYNAMIC_MANAGEMENT', 'PUBLIC', '0', '动态管理', 'Dynamic Management', '動態管理');
INSERT INTO `ts_lang` VALUES ('613', 'PUBLIC_RECYCLE_BIN', 'PUBLIC', '0', '回收站', 'Recycle Bin', '回收站');
INSERT INTO `ts_lang` VALUES ('614', 'PUBLIC_REMOVE_COMPLETELY', 'PUBLIC', '0', '彻底删除', 'Removed Completely', '徹底刪除');
INSERT INTO `ts_lang` VALUES ('615', 'PUBLIC_RECOVER', 'PUBLIC', '0', '恢复', 'Recover', '恢複');
INSERT INTO `ts_lang` VALUES ('616', 'PUBLIC_RECOVERY_FAILED', 'PUBLIC', '0', '微博恢复失败', 'Weibo recover failed', '微博恢複失敗');
INSERT INTO `ts_lang` VALUES ('617', 'PUBLIC_RECOVERY_SUCCESS', 'PUBLIC', '0', '微博恢复成功', 'Weibo recovered successfully', '微博恢複成功');
INSERT INTO `ts_lang` VALUES ('618', 'PUBLIC_REMOVE_COMPLETELY_FAIL', 'PUBLIC', '0', '彻底删除失败', 'Remove completely Failed', '徹底刪除失敗');
INSERT INTO `ts_lang` VALUES ('619', 'PUBLIC_REMOVE_COMPLETELY_SUCCESS', 'PUBLIC', '0', '彻底删除成功', 'Deleted completely successfully', '徹底刪除成功');
INSERT INTO `ts_lang` VALUES ('620', 'PUBLIC_SEARCH_COMMENT', 'PUBLIC', '0', '搜索评论', 'Search Comment', '搜索評論');
INSERT INTO `ts_lang` VALUES ('621', 'PUBLIC_CONTENT_REPORT_REVOKE', 'PUBLIC', '0', '内容 - 举报管理 - 撤销举报', 'Content - Report Management - Revoke Report', '內容 - 舉報管理 - 撤銷舉報');
INSERT INTO `ts_lang` VALUES ('622', 'PUBLIC_CONTENT_REPORT_DELETE', 'PUBLIC', '0', '内容 - 举报管理 - 删除举报', 'Content - Report Management - Delete Content', '內容 - 舉報管理 - 刪除举报');
INSERT INTO `ts_lang` VALUES ('623', 'PUBLIC_DEPARTMENT_SETTING', 'PUBLIC', '0', '部门设置', 'Department Setting', '部門設置');
INSERT INTO `ts_lang` VALUES ('624', 'PUBLIC_DEPARTMENT_MODIFY_FAIL', 'PUBLIC', '0', '部门名称修改失败', 'Department name modify failed', '部門名稱修改失敗');
INSERT INTO `ts_lang` VALUES ('625', 'PUBLIC_SELECT_DEPARTMENT', 'PUBLIC', '0', '请选择部门', 'Please select department', '請選擇部門');
INSERT INTO `ts_lang` VALUES ('626', 'PUBLIC_DEPARTMENT_MODIFY_SUCCESS', 'PUBLIC', '0', '部门修改成功', 'Department modified successfully', '部門修改成功');
INSERT INTO `ts_lang` VALUES ('627', 'PUBLIC_TRANSFER_DEPARTMENT_FORBIDDEN', 'PUBLIC', '0', '不能移动到自己或自己的子部门下面', 'Can not be moved to their own or their sub-sector following', '不能移動到自己或自己的子部門下面');
INSERT INTO `ts_lang` VALUES ('628', 'PUBLIC_MOVE_DEPARTMENT_SUCCESS', 'PUBLIC', '0', '部门移动成功', 'Department moved successfully', '部門移動成功');
INSERT INTO `ts_lang` VALUES ('629', 'PUBLIC_TRANSFER_DEPARTMENT_FAIL', 'PUBLIC', '0', '部门移动失败', 'Department move failed', '部門移動失敗');
INSERT INTO `ts_lang` VALUES ('630', 'PUBLIC_RELATED_DEPARTMENT_NOEXIST', 'PUBLIC', '0', '不存在相关部门', 'Related department does not exist', '不存在相關部門');
INSERT INTO `ts_lang` VALUES ('631', 'PUBLIC_TRANSFER_SUBDEPARTMENT_FORBIDDEN', 'PUBLIC', '0', '子部门不能移动到自己或自己的子部门下面', 'The sub-sector can not be moved to their own or their sub-sector following', '子部門不能移動到自己或自己的子部門下面');
INSERT INTO `ts_lang` VALUES ('632', 'PUBLIC_FEEDBACK_CLASSIFICATION', 'PUBLIC', '0', '意见反馈分类', 'Feedback Classification', '意見反饋分類');
INSERT INTO `ts_lang` VALUES ('633', 'PUBLIC_ADD_FIELD_CLASSIFICATION', 'PUBLIC', '0', '添加字段分类', 'Add Field Classification', '添加字段分類');
INSERT INTO `ts_lang` VALUES ('634', 'PUBLIC_DISABLE_LIST', 'PUBLIC', '0', '禁用列表', 'Disabled List', '禁用列表');
INSERT INTO `ts_lang` VALUES ('635', 'PUBLIC_PENDING_LIST', 'PUBLIC', '0', '待审列表', 'Pending List', '待審列表');
INSERT INTO `ts_lang` VALUES ('636', 'PUBLIC_ADD_FIELD', 'PUBLIC', '0', '添加字段', 'Add Field', '添加字段');
INSERT INTO `ts_lang` VALUES ('637', 'PUBLIC_EDIT_FIELD', 'PUBLIC', '0', '编辑字段', 'Edit Field', '編輯字段');
INSERT INTO `ts_lang` VALUES ('638', 'PUBLIC_SEARCH_USER', 'PUBLIC', '0', '搜索用户', 'Search User', '搜索用戶');
INSERT INTO `ts_lang` VALUES ('639', 'PUBLIC_TRANSFER_DEPARTMENT', 'PUBLIC', '0', '转移部门', 'Transfer Department', '轉移部門');
INSERT INTO `ts_lang` VALUES ('640', 'PUBLIC_TRANSFER_USER_GROUP', 'PUBLIC', '0', '转移用户组', 'Transfer of user groups', '轉移用戶組');
INSERT INTO `ts_lang` VALUES ('641', 'PUBLIC_ACTIVATE_ACCOUNT', 'PUBLIC', '0', '激活帐号', 'Activate Account', '激活帳號');
INSERT INTO `ts_lang` VALUES ('642', 'PUBLIC_AUDIT_USER', 'PUBLIC', '0', '审核用户', 'Audit user', '審核用戶');
INSERT INTO `ts_lang` VALUES ('643', 'PUBLIC_RECOVER_ACCOUNT', 'PUBLIC', '0', '恢复帐号', 'Recover Account', '恢複帳號');
INSERT INTO `ts_lang` VALUES ('644', 'PUBLIC_EDIT_USER_GROUP', 'PUBLIC', '0', '编辑用户组', 'Edit User Groups', '編輯用戶組');
INSERT INTO `ts_lang` VALUES ('645', 'PUBLIC_ORGANIZATION', 'PUBLIC', '0', '组织', 'Organization', '組織');
INSERT INTO `ts_lang` VALUES ('646', 'PUBLIC_PERSONAL', 'PUBLIC', '0', '个人', 'Personal', '個人');
INSERT INTO `ts_lang` VALUES ('647', 'PUBLIC_NOT_ACTIVATED', 'PUBLIC', '0', '未激活', 'Not Activated', '未激活');
INSERT INTO `ts_lang` VALUES ('648', 'PUBLIC_SPECIAL', 'PUBLIC', '0', '特殊', 'Special', '特殊');
INSERT INTO `ts_lang` VALUES ('649', 'PUBLIC_ORDINARY', 'PUBLIC', '0', '普通', 'Ordinary', '普通');
INSERT INTO `ts_lang` VALUES ('650', 'PUBLIC_DELETE_USER_GROUP', 'PUBLIC', '0', '删除用户组', 'Delete User Group', '刪除用戶組');
INSERT INTO `ts_lang` VALUES ('651', 'PUBLIC_ADD_USER_GROUP', 'PUBLIC', '0', '添加用户组', 'Add User Group', '添加用戶組');
INSERT INTO `ts_lang` VALUES ('652', 'PUBLIC_GET_INFORMATION_FAIL', 'PUBLIC', '0', '获取用户信息失败', 'Get user information failed', '獲取用戶信息失敗');
INSERT INTO `ts_lang` VALUES ('653', 'PUBLIC_APPLICATION', 'PUBLIC', '0', '应用', 'Application', '應用');
INSERT INTO `ts_lang` VALUES ('654', 'PUBLIC_LEVEL', 'PUBLIC', '0', '等级', 'Level', '等級');
INSERT INTO `ts_lang` VALUES ('655', 'PUBLIC_USER_GROUP', 'PUBLIC', '0', '用户组', 'User Group', '用戶組');
INSERT INTO `ts_lang` VALUES ('656', 'PUBLIC_SELECT_APPLICATION', 'PUBLIC', '0', '请选择应用 ', 'Please select the application', '請選擇應用 ');
INSERT INTO `ts_lang` VALUES ('657', 'PUBLIC_CURRENT_USER_GROUP', 'PUBLIC', '0', '当前用户组', 'Current User Group', '當前用戶組');
INSERT INTO `ts_lang` VALUES ('658', 'PUBLIC_ADD_PLACE', 'PUBLIC', '0', '添加地区', 'Add Place', '添加地區');
INSERT INTO `ts_lang` VALUES ('659', 'PUBLIC_DELETE_PLACE', 'PUBLIC', '0', '删除地区', 'Delete Place', '刪除地區');
INSERT INTO `ts_lang` VALUES ('660', 'PUBLIC_PLACE_NAME', 'PUBLIC', '0', '地区名称', 'Place Name', '地區名稱');
INSERT INTO `ts_lang` VALUES ('661', 'PUBLIC_LOWER_LEVEL_MANAGEMENT', 'PUBLIC', '0', '下级管理', 'Lower Level Management', '下級管理');
INSERT INTO `ts_lang` VALUES ('662', 'PUBLIC_DELETE_PLACE_CONFIRM', 'PUBLIC', '0', '确定删除选中地区？', 'Make sure to delete the selected place?', '確定刪除選中地區？');
INSERT INTO `ts_lang` VALUES ('663', 'PUBLIC_SELECT_PLACE', 'PUBLIC', '0', '请选择地区', 'Please select a place', '請選擇地區');
INSERT INTO `ts_lang` VALUES ('664', 'PUBLIC_INPUT_NAME', 'PUBLIC', '0', '请输入名称', 'Please input a name', '請輸入名稱');
INSERT INTO `ts_lang` VALUES ('665', 'PUBLIC_NAME_MODIFY_FAIL', 'PUBLIC', '0', '名称无变化，未提交修改', 'The name did not change,changes did not submit', '名稱無變化，未提交修改');
INSERT INTO `ts_lang` VALUES ('666', 'PUBLIC_PERMISSION_GROUP_CONFIGURATION', 'PUBLIC', '0', '权限组配置', 'Permission Group Configuration', '權限組配置');
INSERT INTO `ts_lang` VALUES ('667', 'PUBLIC_MODSAVE_FILES', 'PUBLIC', '0', '分割存储:stringText', 'Partition Storage', '分割存儲:stringText');
INSERT INTO `ts_lang` VALUES ('668', 'PUBLIC_USER_FILSE', 'PUBLIC', '0', '用户选择:selectUser', 'User Selection', '用戶選擇:selectUser');
INSERT INTO `ts_lang` VALUES ('669', 'PUBLIC_DEPARENT_FILES', 'PUBLIC', '0', '部门选择:department', 'Department Selection', '部門選擇:department');
INSERT INTO `ts_lang` VALUES ('670', 'PUBLIC_IMAGE_FILES', 'PUBLIC', '0', '图片上传:input_image', 'Image Upload', '圖片上傳:input_image');
INSERT INTO `ts_lang` VALUES ('671', 'PUBLIC_EDITOR_FILES', 'PUBLIC', '0', '编辑器', 'Editor', '編輯器');
INSERT INTO `ts_lang` VALUES ('672', 'PUBLIC_ADMIN_TITLE', 'PUBLIC', '0', '名称', 'Name', '名稱');
INSERT INTO `ts_lang` VALUES ('673', 'PUBLIC_ADMIN_TYPE', 'PUBLIC', '0', '输入类型', 'Input Type', '輸入類型');
INSERT INTO `ts_lang` VALUES ('674', 'PUBLIC_ADMIN_MODIFY', 'PUBLIC', '0', '默认值格式', 'Default Value \\ Format', '默認值\\格式');
INSERT INTO `ts_lang` VALUES ('675', 'PUBLIC_ADMIN_INFO', 'PUBLIC', '0', '提示信息', 'Message', '提示信息');
INSERT INTO `ts_lang` VALUES ('676', 'PUBLIC_ADMIN_NOTHING', 'PUBLIC', '0', '验证事件', 'Verification Event', '驗證事件');
INSERT INTO `ts_lang` VALUES ('677', 'PUBLIC_PLEASE', 'PUBLIC', '0', '请先', 'Please', '請先');
INSERT INTO `ts_lang` VALUES ('678', 'PUBLIC_CHECH_IS', 'PUBLIC', '0', '提示：checkbox如果默认有多个值，请以“,”隔开。', 'Tip: Multiple default values​​ separated by \",\".', '提示：checkbox如果默認有多個值，請以“,”隔開。');
INSERT INTO `ts_lang` VALUES ('679', 'PUBLIC_NEW_EMAIL', 'PUBLIC', '0', '新', 'New', '新');
INSERT INTO `ts_lang` VALUES ('680', 'PUBLIC_OLD_EMAIL', 'PUBLIC', '0', '原始', 'Original', '原始');
INSERT INTO `ts_lang` VALUES ('681', 'PUBLIC_MODIFY_USER', 'PUBLIC', '0', '修改帐号', 'Modify Account', '修改帳號');
INSERT INTO `ts_lang` VALUES ('682', 'PUBLIC_BACKUP', 'PUBLIC', '0', '跳过', 'Skip', '跳過');
INSERT INTO `ts_lang` VALUES ('683', 'PUBLIC_INDEX', 'PUBLIC', '0', '首页', 'Home Page', '首頁');
INSERT INTO `ts_lang` VALUES ('684', 'PUBLIC_NO_MORE', 'PUBLIC', '0', '暂无', 'No', '暫無');
INSERT INTO `ts_lang` VALUES ('685', 'PUBLIC_VERSION_NUM', 'PUBLIC', '0', '版本号', 'Version Number', '版本號');
INSERT INTO `ts_lang` VALUES ('686', 'PUBLIC_PROSEN', 'PUBLIC', '0', '作者', 'Author', '作者');
INSERT INTO `ts_lang` VALUES ('687', 'PUBLIC_FORMANTS_USER_TIME', 'PUBLIC', '0', '举报时间', 'Report Time', '舉報時間');
INSERT INTO `ts_lang` VALUES ('688', 'PUBLIC_FORMANTS_USER_INFO', 'PUBLIC', '0', '被举报信息', 'Reported information', '被舉報信息');
INSERT INTO `ts_lang` VALUES ('689', 'PUBLIC_FORMANTS_USER_NAME', 'PUBLIC', '0', '举报人用户名', 'Informants User Name', '舉報人用戶名');
INSERT INTO `ts_lang` VALUES ('690', 'PUBLIC_STREAM_REPORT_ID', 'PUBLIC', '0', '举报人ID', 'Informants ID', '舉報人ID');
INSERT INTO `ts_lang` VALUES ('691', 'PUBLIC_STREAM_REPORT_UID', 'PUBLIC', '0', '举报人UID', 'Informants UID', '舉報人UID');
INSERT INTO `ts_lang` VALUES ('692', 'PUBLIC_HIDDEN', 'PUBLIC', '0', '隐藏', 'Hide', '隱藏');
INSERT INTO `ts_lang` VALUES ('693', 'PUBLIC_MAILTITLE_ADMIN', 'PUBLIC', '0', '消息配置', 'Message Configuration', '消息配置');
INSERT INTO `ts_lang` VALUES ('694', 'PUBLIC_SETTING_ADMIN', 'PUBLIC', '0', '权限配置', 'Permissions configuration', '權限配置');
INSERT INTO `ts_lang` VALUES ('695', 'PUBLIC_CONCENT_WEIBO', 'PUBLIC', '0', '评论微博', 'Comment Weibo', '評論微博');
INSERT INTO `ts_lang` VALUES ('696', 'PUBLIC_TITLE_ACCENT_SAVEEDIT', 'PUBLIC', '0', '保存修改编辑公告', 'Save Modified Announcement', '保存修改編輯公告');
INSERT INTO `ts_lang` VALUES ('697', 'PUBLIC_TITLE_FILES_SAVEEDIT', 'PUBLIC', '0', '保存修改编辑文章', 'Save Modified Article', '保存修改編輯文章');
INSERT INTO `ts_lang` VALUES ('698', 'PUBLIC_FOOTER_CONFIG', 'PUBLIC', '0', '页脚配置', 'Footer Configuration', '頁腳配置');
INSERT INTO `ts_lang` VALUES ('699', 'PUBLIC_INVITE_CONFIG', 'PUBLIC', '0', '邀请配置', 'Invitation Configuration', '邀請配置');
INSERT INTO `ts_lang` VALUES ('700', 'PUBLIC_DYNAMIC_SEARCH', 'PUBLIC', '0', '搜索动态', 'Search Dynamic', '搜索動態');
INSERT INTO `ts_lang` VALUES ('701', 'PUBLIC_DYNAMIC_DELETE', 'PUBLIC', '0', '删除动态', 'Delete Dynamic', '刪除動態');
INSERT INTO `ts_lang` VALUES ('702', 'PUBLIC_DYNAMIC', 'PUBLIC', '0', '动态', 'Dynamic', '動態');
INSERT INTO `ts_lang` VALUES ('703', 'PUBLIC_DELETE_COMMENT', 'PUBLIC', '0', '删除评论', 'Delete Comment', '刪除評論');
INSERT INTO `ts_lang` VALUES ('704', 'PUBLIC_MASSAGE_SEARCH', 'PUBLIC', '0', '搜索私信', 'Search Private Letter', '搜索私信');
INSERT INTO `ts_lang` VALUES ('705', 'PUBLIC_MASSAGE_DEL', 'PUBLIC', '0', '删除私信', 'Delete Private Letter', '刪除私信');
INSERT INTO `ts_lang` VALUES ('706', 'PUBLIC_FILE_STREAM_SEARCH', 'PUBLIC', '0', '搜索附件', 'Attachment Search', '搜索附件');
INSERT INTO `ts_lang` VALUES ('707', 'PUBLIC_FILE_STREAM_DEL', 'PUBLIC', '0', '删除附件', 'Attachment Delete', '刪除附件');
INSERT INTO `ts_lang` VALUES ('708', 'PUBLIC_SERVER_SOFT', 'PUBLIC', '0', '服务器软件', 'Server Software', '服務器軟件');
INSERT INTO `ts_lang` VALUES ('709', 'PUBLIC_INVITE_LIST_FILES', 'PUBLIC', '0', '{user}的邀请详情列表 (共{unm}个)', 'The invitation details list of {user} (all {unm})', '{user}的邀請詳情列表 (共{unm}個)');
INSERT INTO `ts_lang` VALUES ('710', 'PUBLIC_TAGS_ADD', 'PUBLIC', '0', '添加标签', 'Add Tag', '添加標簽');
INSERT INTO `ts_lang` VALUES ('711', 'PUBLIC_TAG_LIST', 'PUBLIC', '0', '标签列表', 'Tag List', '標簽列表');
INSERT INTO `ts_lang` VALUES ('712', 'PUBLIC_INVITE_LIST', 'PUBLIC', '0', '邀请列表', 'Invitation List', '邀請列表');
INSERT INTO `ts_lang` VALUES ('713', 'PUBLIC_MAIL_INLOCALHOST', 'PUBLIC', '0', '站内信', 'Letter', '站內信');
INSERT INTO `ts_lang` VALUES ('714', 'PUBLIC_DETAILS_LIST', 'PUBLIC', '0', '详情', 'Details', '詳情');
INSERT INTO `ts_lang` VALUES ('715', 'PUBLIC_MODIFY', 'PUBLIC', '0', '修改', 'Modify', '修改');
INSERT INTO `ts_lang` VALUES ('716', 'PUBLIC_FEEDBACK_CATEGORY_MANAGE', 'PUBLIC', '0', '意见反馈分类管理', 'Feedback Category Management', '意見反饋分類管理');
INSERT INTO `ts_lang` VALUES ('717', 'PUBLIC_FEEDBACK_ADD_TYPE', 'PUBLIC', '0', '添加反馈类型', 'Add Feedback Type', '添加反饋類型');
INSERT INTO `ts_lang` VALUES ('718', 'PUBLIC_FEEDBACK_EDIT_TYPE', 'PUBLIC', '0', '编辑反馈类型', 'Edit Feedback Type', '編輯反饋類型');
INSERT INTO `ts_lang` VALUES ('719', 'PUBLIC_MARK_PROCESSED', 'PUBLIC', '0', '标记已处理', 'Mark Processed', '標記已處理');
INSERT INTO `ts_lang` VALUES ('720', 'PUBLIC_FEEDBACK_MANAGE', 'PUBLIC', '0', '意见反馈管理', 'Feedback Management', '意見反饋管理');
INSERT INTO `ts_lang` VALUES ('721', 'PUBLIC_WAIT_PROCESSE', 'PUBLIC', '0', '待处理', 'Pending', '待處理');
INSERT INTO `ts_lang` VALUES ('722', 'PUBLIC_ALREADY_PROCESSED', 'PUBLIC', '0', '已处理', 'Processed', '已處理');
INSERT INTO `ts_lang` VALUES ('723', 'PUBLIC_FEEDBACK_LIST', 'PUBLIC', '0', '反馈列表', 'Feedback List', '反饋列表');
INSERT INTO `ts_lang` VALUES ('724', 'PUBLIC_FEEDBACK_TYPE', 'PUBLIC', '0', '反馈类型', 'Feedback Type', '反饋類型');
INSERT INTO `ts_lang` VALUES ('725', 'PUBLIC_SYSTEM_EDIT_DATA', 'PUBLIC', '0', '编辑数据', 'Edit Data', '編輯數據');
INSERT INTO `ts_lang` VALUES ('726', 'PUBLIC_SYSTEM_ADD_DATA', 'PUBLIC', '0', '添加新数据', 'Add New Data', '添加新數據');
INSERT INTO `ts_lang` VALUES ('727', 'PUBLIC_SYSTEM_KEYCN_IS', 'PUBLIC', '0', 'KEY和中文名不能为空', 'KEY and Chinese name can not be empty', 'KEY和中文名不能爲空');
INSERT INTO `ts_lang` VALUES ('728', 'PUBLIC_SYSTEM_LOG_REMOVE_DEL', 'PUBLIC', '0', '清理删除日志', 'Clean Up Deleted Logs', '清理刪除日志');
INSERT INTO `ts_lang` VALUES ('729', 'PUBLIC_SYSTEM_LOG_REMOVE_IS', 'PUBLIC', '0', '没有需要清理的日志', 'There is no log to be cleaned up', '沒有需要清理的日志');
INSERT INTO `ts_lang` VALUES ('730', 'PUBLIC_SYSTEM_LOG_REMOVE', 'PUBLIC', '0', '日志清理成功', 'Log cleaned up successfully', '日志清理成功');
INSERT INTO `ts_lang` VALUES ('731', 'PUBLIC_SCHEDULED_TASK_DELETE', 'PUBLIC', '0', '删除计划任务', 'Delete Schedule Task', '刪除計劃任務');
INSERT INTO `ts_lang` VALUES ('732', 'PUBLIC_SCHEDULED_TASK_LIST', 'PUBLIC', '0', '计划任务列表', 'Scheduled Task List', '計劃任務列表');
INSERT INTO `ts_lang` VALUES ('733', 'PUBLIC_SCHEDULED_TASK_CREATE', 'PUBLIC', '0', '新建计划任务', 'New Scheduled Task', '新建計劃任務');
INSERT INTO `ts_lang` VALUES ('734', 'PUBLIC_LOGS_REMOVE_SEX', 'PUBLIC', '0', '清除6个月前日志', 'Clear the logs before 6 months', '清除6個月前日志');
INSERT INTO `ts_lang` VALUES ('735', 'PUBLIC_LOGS_REMOVE_SET', 'PUBLIC', '0', '清除12个月前日志', 'Clear the logs before 12 months', '清除12個月前日志');
INSERT INTO `ts_lang` VALUES ('736', 'PUBLIC_LOGS_REMOVE_LOG', 'PUBLIC', '0', '重建归档表', 'Rebuild Archive Table', '重建歸檔表');
INSERT INTO `ts_lang` VALUES ('737', 'PUBLIC_SYSTEM_DELALL', 'PUBLIC', '0', '删除选中项', 'Delete Selected Item', '刪除選中項');
INSERT INTO `ts_lang` VALUES ('738', 'PUBLIC_SYSTEM_LOGLIST', 'PUBLIC', '0', '日志列表', 'Log List', '日志列表');
INSERT INTO `ts_lang` VALUES ('739', 'PUBLIC_SYSTEM_BACK', 'PUBLIC', '0', '后台', 'Background', '後台');
INSERT INTO `ts_lang` VALUES ('740', 'PUBLIC_USER_LOGS', 'PUBLIC', '0', '用户日志', 'User Log', '用戶日志');
INSERT INTO `ts_lang` VALUES ('741', 'PUBLIC_INVITE_TOP', 'PUBLIC', '0', '邀请排行', 'Invitation Ranking', '邀請排行');
INSERT INTO `ts_lang` VALUES ('742', 'PUBLIC_SYSTEM_DATA_SQL', 'PUBLIC', '0', '数据字典', 'Data Dictionary', '數據字典');
INSERT INTO `ts_lang` VALUES ('743', 'PUBLIC_SYSTEM_DATA_SQLLIST', 'PUBLIC', '0', '数据字典列表', 'Data Dictionary List', '數據字典列表');
INSERT INTO `ts_lang` VALUES ('744', 'PUBLIC_SCHEDULED_TASK_NEWCREATE', 'PUBLIC', '0', '计划任务', 'Scheduled Task', '計劃任務');
INSERT INTO `ts_lang` VALUES ('745', 'PUBLIC_SYSTEM_LOGSUM', 'PUBLIC', '0', '日志归档', 'Log Archive', '日志歸檔');
INSERT INTO `ts_lang` VALUES ('746', 'PUBLIC_SYSTEM_LOGSUM_SUCCESS', 'PUBLIC', '0', '日志归档成功', 'Log Archived Successfully', '日志歸檔成功');
INSERT INTO `ts_lang` VALUES ('747', 'PUBLIC_SYSTEM_LOGSUM_SUCCESS_IS', 'PUBLIC', '0', '没有需要归档的日志', 'There is no log to be archived', '沒有需要歸檔的日志');
INSERT INTO `ts_lang` VALUES ('748', 'PUBLIC_SYSTEM_SELECT', 'PUBLIC', '0', '请选择', ' Select', '請選擇');
INSERT INTO `ts_lang` VALUES ('749', 'PUBLIC_SYSTEM_CATEGORY_ISNOT', 'PUBLIC', '0', '分类不存在', 'Category does not exist', '分類不存在');
INSERT INTO `ts_lang` VALUES ('750', 'PUBLIC_SYSTEM_SONCATEGORY_ISNOT', 'PUBLIC', '0', '子分类不存在', 'Sub-classification does not exist', '子分類不存在');
INSERT INTO `ts_lang` VALUES ('751', 'PUBLIC_SYSTEM_PCATEGORY', 'PUBLIC', '0', '根分类', 'Root Classification', '根分類');
INSERT INTO `ts_lang` VALUES ('752', 'PUBLIC_SYSTEM_PARENT_SEE', 'PUBLIC', '0', '好友可见', 'Friends can be seen', '好友可見');
INSERT INTO `ts_lang` VALUES ('753', 'PUBLIC_SYSTEM_FOLLOWING_SEE', 'PUBLIC', '0', '互相关注可见', 'Concern can be seen each other', '互相關注可見');
INSERT INTO `ts_lang` VALUES ('754', 'PUBLIC_SYSTEM_FOLLW_SEE', 'PUBLIC', '0', '粉丝可见', 'Fans can be seen', '粉絲可見');
INSERT INTO `ts_lang` VALUES ('755', 'PUBLIC_SYSTEM_CATEGORY', 'PUBLIC', '0', '分类', 'Category', '分類');
INSERT INTO `ts_lang` VALUES ('756', 'PUBLIC_SYSTEM_ADD_CATEGORY', 'PUBLIC', '0', '添加分类', 'Add Category', '添加分類');
INSERT INTO `ts_lang` VALUES ('757', 'PUBLIC_SYSTEM_FIELD', 'PUBLIC', '0', '字段', 'Field', '字段');
INSERT INTO `ts_lang` VALUES ('758', 'PUBLIC_SYSTEM_FIELDLIST', 'PUBLIC', '0', '字段列表', 'Field List', '字段列表');
INSERT INTO `ts_lang` VALUES ('759', 'PUBLIC_SYSTEM_CATEGORYLIST', 'PUBLIC', '0', '分类列表', 'Category List', '分類列表');
INSERT INTO `ts_lang` VALUES ('760', 'PUBLIC_SYSTEM_NOUSE', 'PUBLIC', '0', '禁用', 'Disabled', '禁用');
INSERT INTO `ts_lang` VALUES ('761', 'PUBLIC_SYSTEMD_TRUE', 'PUBLIC', '0', '是', 'Yes', '是');
INSERT INTO `ts_lang` VALUES ('762', 'PUBLIC_SYSTEMD_FALSE', 'PUBLIC', '0', '否', 'No', '否');
INSERT INTO `ts_lang` VALUES ('763', 'PUBLIC_SYSTEMD_NOACCEPT', 'PUBLIC', '0', '不限', 'Not Limited', '不限');
INSERT INTO `ts_lang` VALUES ('764', 'PUBLIC_USER_LIST', 'PUBLIC', '0', '用户列表', 'User List', '用戶列表');
INSERT INTO `ts_lang` VALUES ('765', 'PUBLIC_AUDIT_USER_SUCCESS', 'PUBLIC', '0', '通过审核', 'Approved', '通過審核');
INSERT INTO `ts_lang` VALUES ('766', 'PUBLIC_AUDIT_USER_ERROR', 'PUBLIC', '0', '未过审核', 'Not Approved', '未過審核');
INSERT INTO `ts_lang` VALUES ('767', 'PUBLIC_EDITCATEOGRY', 'PUBLIC', '0', '编辑分类', 'Edit Category', '編輯分類');
INSERT INTO `ts_lang` VALUES ('768', 'PUBLIC_ADD_USER_INFO', 'PUBLIC', '0', '添加用户', 'Add User', '添加用戶');
INSERT INTO `ts_lang` VALUES ('769', 'PUBLIC_USERGROUP_ISNOT', 'PUBLIC', '0', '用户组不能为空', 'User group can not be empty', '用戶組不能爲空');
INSERT INTO `ts_lang` VALUES ('770', 'PUBLIC_SYSTEM_MAIL_ISNOT', 'PUBLIC', '0', '收信人不能为空', 'Addressee can not be empty', '收信人不能爲空');
INSERT INTO `ts_lang` VALUES ('771', 'PUBLIC_COMMENT_MAIL_REQUIRED', 'PUBLIC', '0', '内容不可为空', 'Content can not be empty', '內容不可爲空');
INSERT INTO `ts_lang` VALUES ('772', 'PUBLIC_COMMENT_MAIL_TITLE', 'PUBLIC', '0', '标题不可为空', 'Title can not be empty', '標題不可爲空');
INSERT INTO `ts_lang` VALUES ('773', 'PUBLIC_IMAGE_SAVE_IS', 'PUBLIC', '0', '确认保存头像', 'Confirm to save picture', '確認保存頭像');
INSERT INTO `ts_lang` VALUES ('774', 'PUBLIC_APP_REMOVE', 'PUBLIC', '0', '移除应用', 'Remove Application', '移除應用');
INSERT INTO `ts_lang` VALUES ('775', 'PUBLIC_INSTALLED_APP', 'PUBLIC', '0', '已安装', 'Installed', '已安裝');
INSERT INTO `ts_lang` VALUES ('776', 'PUBLIC_APP_STOP', 'PUBLIC', '0', '停止使用', 'Stop Using', '停止使用');
INSERT INTO `ts_lang` VALUES ('777', 'PUBLIC_FAVORITE_ISNOT', 'PUBLIC', '0', '暂时没有收藏', 'No Favorites', '暫時沒有收藏');
INSERT INTO `ts_lang` VALUES ('778', 'PUBLIC_COMMENTS_ISNOT', 'PUBLIC', '0', '暂时没有评论', 'No Comments', '暫時沒有評論');
INSERT INTO `ts_lang` VALUES ('779', 'PUBLIC_CREATE', 'PUBLIC', '0', '创建', 'Create', '創建');
INSERT INTO `ts_lang` VALUES ('780', 'PUBLIC_GROUPNAME_CREATE_TIPES', 'PUBLIC', '0', '最多只能创建10个分组', 'Can only create a maximum of 10 groups', '最多只能創建10個分組');
INSERT INTO `ts_lang` VALUES ('781', 'PUBLIC_GROUPNAME_NAME_TIPES', 'PUBLIC', '0', '分组名称字数不能超过10个汉字长度', 'Group name can not be more than 10 characters', '分組名稱字數不能超過10個漢字長度');
INSERT INTO `ts_lang` VALUES ('782', 'PUBLIC_GROUPNAME_EDIT', 'PUBLIC', '0', '修改分组', 'Modify Group', '修改分組');
INSERT INTO `ts_lang` VALUES ('783', 'PUBLIC_GROUPNAME_SET', 'PUBLIC', '0', '设置分组', 'Set Group', '設置分組');
INSERT INTO `ts_lang` VALUES ('784', 'PUBLIC_WATING', 'PUBLIC', '0', '加载中...', 'Loading ...', '加載中...');
INSERT INTO `ts_lang` VALUES ('785', 'PUBLIC_VIVER_TAOLK', 'PUBLIC', '0', '查看对话', 'View Dialogue', '查看對話');
INSERT INTO `ts_lang` VALUES ('786', 'PUBLIC_PROFILE_TAG', 'PUBLIC', '0', '个人标签', 'Individual Labels', '個人標簽');
INSERT INTO `ts_lang` VALUES ('787', 'PUBLIC_REPORTING_ORG', 'PUBLIC', '0', '汇报关系', 'Reporting Relationship', '匯報關係');
INSERT INTO `ts_lang` VALUES ('788', 'PUBLIC_SETING_SUCCESS', 'PUBLIC', '0', '设置成功', 'Set Successfully', '設置成功');
INSERT INTO `ts_lang` VALUES ('789', 'PUBLIC_CONFIG', 'PUBLIC', '0', '确认', 'OK', '確認');
INSERT INTO `ts_lang` VALUES ('790', 'PUBLIC_SYSTEM_FIND', 'PUBLIC', '0', '查询', 'Inquiry', '查詢');
INSERT INTO `ts_lang` VALUES ('791', 'PUBLIC_ADMIN_SETING', 'PUBLIC', '0', '字段配置', 'Field Configuration', '字段配置');
INSERT INTO `ts_lang` VALUES ('792', 'PUBLIC_ADMIN_OPRETING_SUCCESS', 'PUBLIC', '0', '操作成功', 'Operated Successfully', '操作成功');
INSERT INTO `ts_lang` VALUES ('793', 'PUBLIC_ADMIN_OPRETING_ERROR', 'PUBLIC', '0', '操作失败', 'Operate Failed', '操作失敗');
INSERT INTO `ts_lang` VALUES ('794', 'PUBLIC_SYSTEM_POINT_EDIT', 'PUBLIC', '0', '编辑积分等级', 'Edit Points Level', '編輯積分等級');
INSERT INTO `ts_lang` VALUES ('795', 'PUBLIC_SYSTEM_OPINT_EDIT', 'PUBLIC', '0', '编辑积分节点', 'Edit Points Node', '編輯積分節點');
INSERT INTO `ts_lang` VALUES ('796', 'PUBLIC_SYSTEM_OPINT_DELETE', 'PUBLIC', '0', '删除积分节点', 'Deleted Points Node', '刪除積分節點');
INSERT INTO `ts_lang` VALUES ('797', 'PUBLIC_SYSTEM_OPINT_ADD', 'PUBLIC', '0', '添加积分节点', 'Add Points Node', '添加積分節點');
INSERT INTO `ts_lang` VALUES ('798', 'PUBLIC_SYSTEM_ADMINJUR_ADD', 'PUBLIC', '0', '添加权限节点', 'Add Permissions Node', '添加權限節點');
INSERT INTO `ts_lang` VALUES ('799', 'PUBLIC_SYSTEM_ADMINJUR_DELETE', 'PUBLIC', '0', '删除权限节点', 'Remove Permissions Node', '刪除權限節點');
INSERT INTO `ts_lang` VALUES ('800', 'PUBLIC_SYSTEM_NORMAL_USER', 'PUBLIC', '0', '普通权限', 'Ordinary Privileges', '普通權限');
INSERT INTO `ts_lang` VALUES ('801', 'PUBLIC_SYSTEM_ADMIN_USER', 'PUBLIC', '0', '管理权限', 'Administrative Privileges', '管理權限');
INSERT INTO `ts_lang` VALUES ('802', 'PUBLIC_SYSTEM_APP_UNLODING', 'PUBLIC', '0', '卸载', 'Uninstall', '卸載');
INSERT INTO `ts_lang` VALUES ('803', 'PUBLIC_SYSTEM_APP_INSTALL', 'PUBLIC', '0', '安装', 'Install', '安裝');
INSERT INTO `ts_lang` VALUES ('804', 'PUBLIC_SYSTEM_APP_EDIT', 'PUBLIC', '0', '编辑应用', 'Edit Application', '編輯應用');
INSERT INTO `ts_lang` VALUES ('805', 'PUBLIC_SYSTEM_APP_SELECTINSTALL', 'PUBLIC', '0', '请先选择要安装的应用', 'Please select the application you want to install', '請先選擇要安裝的應用');
INSERT INTO `ts_lang` VALUES ('806', 'PUBLIC_SYSTEM_APP_INSTALLERROR', 'PUBLIC', '0', '安装失败，必填项不能为空', 'Install failed, required can not be empty', '安裝失敗，必填項不能爲空');
INSERT INTO `ts_lang` VALUES ('807', 'PUBLIC_SYSTEM_APP_FILEDS', 'PUBLIC', '0', '保存修改编辑后台应用配置', 'Save the modified background application configuration', '保存修改編輯後台應用配置');
INSERT INTO `ts_lang` VALUES ('808', 'PUBLIC_SYSTEM_APP_FILED', 'PUBLIC', '0', '保存修改编辑应用状态', 'Save the modified application state', '保存修改編輯應用狀態');
INSERT INTO `ts_lang` VALUES ('809', 'PUBLIC_TA_COMMENTS', 'PUBLIC', '0', '{user}的评论', '{user} comment', '{user}的評論');
INSERT INTO `ts_lang` VALUES ('810', 'PUBLIC_TA_FAVORITES', 'PUBLIC', '0', '{user}的收藏', '{user} favorite', '{user}的收藏');
INSERT INTO `ts_lang` VALUES ('811', 'PUBLIC_TA_PROFILE', 'PUBLIC', '0', '{user}的档案', '{user} profile', '{user}的檔案');
INSERT INTO `ts_lang` VALUES ('812', 'PUBLIC_TA_WEIBO', 'PUBLIC', '0', '{user}的微博', '{user} weibo ', '{user}的微博');
INSERT INTO `ts_lang` VALUES ('813', 'PUBLIC_TA_FOLLOWING', 'PUBLIC', '0', '{user}的关注', '{user} attention ', '{user}的關注');
INSERT INTO `ts_lang` VALUES ('814', 'PUBLIC_TA_FOLLWER', 'PUBLIC', '0', '{user}的粉丝', '{user} fans ', '{user}的粉絲');
INSERT INTO `ts_lang` VALUES ('815', 'PUBLIC_TA_PRIVATE_MESSAGE', 'PUBLIC', '0', '{user}的私信', '{user} private message', '{user}的私信');
INSERT INTO `ts_lang` VALUES ('816', 'PUBLIC_ATTACH_ISNULL', 'PUBLIC', '0', '附件不存在', 'Attachment does not exist', '附件不存在');
INSERT INTO `ts_lang` VALUES ('817', 'PUBLIC_TAG_TIPS', 'PUBLIC', '0', '按回车添加标签，多个标签用逗号分隔', 'Press Enter to add tags, multiple tags separated by commas', '按回車添加標簽，多個標簽用逗號分隔');
INSERT INTO `ts_lang` VALUES ('818', 'PUBLIC_TAG_NOEMPTY', 'PUBLIC', '0', '标签不能为空', 'Tags can not be empty', '標簽不能爲空');
INSERT INTO `ts_lang` VALUES ('819', 'PUBLIC_SHARE_SUCCESS', 'PUBLIC', '0', '发布成功', 'Publishing success', '發布成功');
INSERT INTO `ts_lang` VALUES ('820', 'PUBLIC_SENTWEIBO_ISNOT', 'PUBLIC', '0', '您没有微博发布权限', 'You do not have the permissions to publish weibo', '您沒有微博發布權限');
INSERT INTO `ts_lang` VALUES ('821', 'PUBLIC_SENTWEIBO_TO', 'PUBLIC', '0', '同时作为给 {link}的评论发布', 'as to {link} comment ', '同時作爲給 {link}的評論發布');
INSERT INTO `ts_lang` VALUES ('822', 'PUBLIC_CLICK_ADD', 'PUBLIC', '0', '点击添加', 'Click to add', '點擊添加');
INSERT INTO `ts_lang` VALUES ('823', 'PUBLIC_CLICK_CANCEL', 'PUBLIC', '0', '点击取消', 'Click to cancel', '點擊取消');
INSERT INTO `ts_lang` VALUES ('824', 'PUBLIC_CLICK_EDIT', 'PUBLIC', '0', '点击修改', 'Click to edit', '點擊修改');
INSERT INTO `ts_lang` VALUES ('825', 'PUBLIC_CLICK_SETING', 'PUBLIC', '0', '点击设置', 'Click to set', '點擊設置');
INSERT INTO `ts_lang` VALUES ('826', 'PUBLIC_REBUSINESS_MANAGEMENT', 'PUBLIC', '0', '返回业务管理', 'Back to business admin', '返回業務管理');
INSERT INTO `ts_lang` VALUES ('827', 'PUBLIC_INVITE_COLLEAGUE_ADD', 'PUBLIC', '0', '邀请好友加入', 'Invite your friends to join', '邀請好友加入');
INSERT INTO `ts_lang` VALUES ('828', 'PUBLIC_PLEASE_EMAIL_SNET', 'PUBLIC', '0', '请输入邮件地址...', 'Please enter the e-mail address ...', '請輸入郵件地址...');
INSERT INTO `ts_lang` VALUES ('829', 'PUBLIC_GROUP_NAME', 'PUBLIC', '0', '分组名称', 'Group', '分組名稱');
INSERT INTO `ts_lang` VALUES ('830', 'PUBLIC_ADD_GROUP_NAME_ERROR', 'PUBLIC', '0', '分组添加失败', 'Group add failed', '分組添加失敗');
INSERT INTO `ts_lang` VALUES ('831', 'PUBLIC_CRETAE_GROUP_MAX_TIPES', 'PUBLIC', '0', '一个用户最多只能创建{num}个分组', 'A user can only create {num} group', '一個用戶最多只能創建{num}個分組');
INSERT INTO `ts_lang` VALUES ('832', 'PUBLIC_LISTWEIBO_ISNULL', 'PUBLIC', '0', '您没有浏览微博信息权限', 'You do not have the permissions to browse the weibo information', '您沒有浏覽微博信息權限');
INSERT INTO `ts_lang` VALUES ('833', 'PUBLIC_SUCCESS_LOAD', 'PUBLIC', '0', '成功获取', 'Obtained Successfully', '成功獲取');
INSERT INTO `ts_lang` VALUES ('834', 'PUBLIC_WEIBOISNOTNEW', 'PUBLIC', '0', '没有更多微博', 'No More Weibo', '没有更多微博');
INSERT INTO `ts_lang` VALUES ('835', 'PUBLIC_LOADING_ID_ISNULL', 'PUBLIC', '0', '加载起始ID不能为空', 'Load the starting ID can not be empty', '加載起始ID不能爲空');
INSERT INTO `ts_lang` VALUES ('836', 'PUBLIC_UPDATE_SUCCESS', 'PUBLIC', '0', '更新成功', 'Updated successfully', '更新成功');
INSERT INTO `ts_lang` VALUES ('837', 'PUBLIC_DEPARTMENT_ALL', 'PUBLIC', '0', '所有部门', 'All Department', '所有部門');
INSERT INTO `ts_lang` VALUES ('838', 'PUBLIC_PLEASE_SELECT', 'PUBLIC', '0', '请选择', 'Please select', '請選擇');
INSERT INTO `ts_lang` VALUES ('839', 'PUBLIC_GO_BACK', 'PUBLIC', '0', '上一级', 'Up Level', '上一級');
INSERT INTO `ts_lang` VALUES ('840', 'PUBLIC_REPORTING_INFO', 'PUBLIC', '0', '您已经成功举报此信息', 'You have successfully reported this information', '您已經成功舉報此信息');
INSERT INTO `ts_lang` VALUES ('841', 'PUBLIC_REPORT_ERROR', 'PUBLIC', '0', '举报失败,请稍候再试', 'Report failed, please try again', '舉報失敗,請稍候再試');
INSERT INTO `ts_lang` VALUES ('842', 'PUBLIC_REPORT_TIPES', 'PUBLIC', '0', '不良信息是指含有色情、暴力、广告或其它骚扰你正常工作生活的内容。', 'Adverse information is information containing pornography, violence, advertising, or other harassment of the contents of your normal working life.', '不良信息是指含有色情、暴力、廣告或其它騷擾你正常工作生活的內容。');
INSERT INTO `ts_lang` VALUES ('843', 'PUBLIC_REPORT_INFO_IS', 'PUBLIC', '0', '你要举报的是以下信息', 'You want to report the following information', '你要舉報的是以下信息');
INSERT INTO `ts_lang` VALUES ('844', 'PUBLIC_REPORT_MORE', 'PUBLIC', '0', '你可以填写更多举报说明', 'You can fill in more reporting instructions', '你可以填寫更多舉報說明');
INSERT INTO `ts_lang` VALUES ('845', 'PUBLIC_SELECT_TIPES', 'PUBLIC', '0', '选填', 'Optional', '選填');
INSERT INTO `ts_lang` VALUES ('846', 'PUBLIC_PLEASE_USERINFO_SECRET', 'PUBLIC', '0', '请放心，你的隐私将会得到保护。', 'Please be assured that your privacy will be protected.', '請放心，你的隱私將會得到保護。');
INSERT INTO `ts_lang` VALUES ('847', 'PUBLIC_SEE_MORE', 'PUBLIC', '0', '查看更多', 'See More', '查看更多');
INSERT INTO `ts_lang` VALUES ('848', 'PUBLIC_SHARETO_USERINFO', 'PUBLIC', '0', '同时评论给原文作者', 'Comments to the original author', '同時評論給原文作者');
INSERT INTO `ts_lang` VALUES ('849', 'PUBLIC_CONCENT_ISNULL', 'PUBLIC', '0', '您没有此评论权限', 'You do not have comment permissions', '您沒有此評論權限');
INSERT INTO `ts_lang` VALUES ('850', 'PUBLIC_CONCENT_IS_ERROR', 'PUBLIC', '0', '评论失败', 'Comment Failed', '評論失敗');
INSERT INTO `ts_lang` VALUES ('851', 'PUBLIC_CONCENT_TIPES', 'PUBLIC', '0', '此用户进行了隐私设置，您无法对其微博进行评论', 'The user made privacy settings that you can not comment on its weibo', '此用戶進行了隱私設置，您無法對其微博進行評論');
INSERT INTO `ts_lang` VALUES ('852', 'PUBLIC_FAVORITE_SUCCESS', 'PUBLIC', '0', '收藏成功', 'Favorited Successfully', '收藏成功');
INSERT INTO `ts_lang` VALUES ('853', 'PUBLIC_EDLFAVORITE_ERROR', 'PUBLIC', '0', '取消收藏失败', 'Cancel favorite failed', '取消收藏失敗');
INSERT INTO `ts_lang` VALUES ('854', 'PUBLIC_CANCEL_ERROR', 'PUBLIC', '0', '取消成功', 'Canceled Successfully', '取消成功');
INSERT INTO `ts_lang` VALUES ('855', 'PUBLIC_ALL_CATEGORIES', 'PUBLIC', '0', '所有分类', 'All Categories', '所有分類');
INSERT INTO `ts_lang` VALUES ('856', 'PUBLIC_ADD_HUSER', 'PUBLIC', '0', '加入黑名单', 'Add to the blacklist', '加入黑名單');
INSERT INTO `ts_lang` VALUES ('857', 'PUBLIC_MOVE_HUSER', 'PUBLIC', '0', '移除黑名单', 'Remove Blacklist', '移除黑名單');
INSERT INTO `ts_lang` VALUES ('858', 'PUBLIC_HUSER_TIPES', 'PUBLIC', '0', '黑名单列表暂为空', 'Blacklist temporarily empty', '黑名單列表暫爲空');
INSERT INTO `ts_lang` VALUES ('859', 'PUBLIC_JIECHU', 'PUBLIC', '0', '解除', 'Remove', '解除');
INSERT INTO `ts_lang` VALUES ('860', 'PUBLIC_MOVE_USER_SUCCESS', 'PUBLIC', '0', '用户移除成功', 'User removed successfully', '用戶移除成功');
INSERT INTO `ts_lang` VALUES ('861', 'PUBLIC_USER_ID_ISNULL', 'PUBLIC', '0', '用户ID不存在', 'User ID does not exist', '用戶ID不存在');
INSERT INTO `ts_lang` VALUES ('862', 'PUBLIC_ADD_HUSER_USRE', 'PUBLIC', '0', '已将{user}加入黑名单', ' {user} is added to the blacklist', '已將{user}加入黑名單');
INSERT INTO `ts_lang` VALUES ('863', 'PUBLIC_CANCEL_AREA', 'PUBLIC', '0', '切换城市', 'Change City', '切換城市');
INSERT INTO `ts_lang` VALUES ('864', 'PUBLIC_SEE_MORE_NUM', 'PUBLIC', '0', '查看条数', 'View Article', '查看條數');
INSERT INTO `ts_lang` VALUES ('865', 'PUBLIC_APPNAME_TASK', 'PUBLIC', '0', '任务', 'Task', '任務');
INSERT INTO `ts_lang` VALUES ('921', 'PUBLIC_MENU_CONTACT', 'PUBLIC', '0', '我的通讯录', 'My Contacts', '我的通訊錄');
INSERT INTO `ts_lang` VALUES ('931', 'SSC_INDEX', 'SUPPORT', '0', '帮助中心首页', 'Support Center Index', '幫助中心首頁');
INSERT INTO `ts_lang` VALUES ('932', 'SSC_HOT_QUESTION', 'SUPPORT', '0', '热门问题', 'Hot Questions', '熱門問題');
INSERT INTO `ts_lang` VALUES ('933', 'SSC_HOT_KEYWORDS', 'SUPPORT', '0', '热门关键字', 'Hot Keywords', '熱門關鍵字');
INSERT INTO `ts_lang` VALUES ('934', 'SSC_SEARCH_PROBLEM', 'SUPPORT', '0', '搜索问题', 'Search Problem', '搜索問題');
INSERT INTO `ts_lang` VALUES ('935', 'SSC_SUBMIT_NEW_PROPOSAL', 'SUPPORT', '0', '提交新建议', 'Submit A New Proposal', '提交新建議');
INSERT INTO `ts_lang` VALUES ('936', 'SSC_ALL_QUESTIONS', 'SUPPORT', '0', '问题总数', 'ALL Questions', '問題總數');
INSERT INTO `ts_lang` VALUES ('937', 'SSC_ALL_BROWSE', 'SUPPORT', '0', '浏览总量', 'All Browse', '浏覽總量');
INSERT INTO `ts_lang` VALUES ('938', 'SSC_NUMBER', 'SUPPORT', '0', '条', '', '條');
INSERT INTO `ts_lang` VALUES ('939', 'SSC_ALL_CATEGORIES', 'SUPPORT', '0', '所有分类', 'All Categories', '所有分類');
INSERT INTO `ts_lang` VALUES ('940', 'SSC_LABEL', 'SUPPORT', '0', '标签', 'Label', '標簽');
INSERT INTO `ts_lang` VALUES ('941', 'SSC_CUSTOMER_SERVICE_PHONE', 'SUPPORT', '0', '客服电话', 'Customer Service Phone', '客服電話');
INSERT INTO `ts_lang` VALUES ('942', 'SSC_MONDAY_TO_SUNDAY', 'SUPPORT', '0', '周一至周日', 'Monday To Sunday', '周一至周日');
INSERT INTO `ts_lang` VALUES ('943', 'SSC_SUBMIT_FEEDBACK', 'SUPPORT', '0', '提交意见反馈', 'Submit Feedback', '提交意見反饋');
INSERT INTO `ts_lang` VALUES ('944', 'SSC_TYPE', 'SUPPORT', '0', '类型', 'Type', '類型');
INSERT INTO `ts_lang` VALUES ('945', 'SSC_CLASSIFICATION', 'SUPPORT', '0', '分类', 'Category', '分類');
INSERT INTO `ts_lang` VALUES ('946', 'SSC_CHOOSE_CATEGORY', 'SUPPORT', '0', '选择分类', 'Select Category', '選擇分類');
INSERT INTO `ts_lang` VALUES ('947', 'SSC_TITLE', 'SUPPORT', '0', '标题', 'Title', '標題');
INSERT INTO `ts_lang` VALUES ('948', 'SSC_DESCRIPTION', 'SUPPORT', '0', '描述', 'Description', '描述');
INSERT INTO `ts_lang` VALUES ('949', 'SSC_INPUT_CHARACTER_LIMIT', 'SUPPORT', '0', '还可以输入xxx个字符', 'can also enter the XXX character', '還可以輸入xxx個字符');
INSERT INTO `ts_lang` VALUES ('950', 'SSC_SUBMIT', 'SUPPORT', '0', '提交', 'Submit', '提交');
INSERT INTO `ts_lang` VALUES ('951', 'SSC_SELECT_CATEGORY', 'SUPPORT', '0', '请选择分类', 'Please select the category', '請選擇分類');
INSERT INTO `ts_lang` VALUES ('952', 'SSC_CONFIRM', 'SUPPORT', '0', '确定', 'OK', '確定');
INSERT INTO `ts_lang` VALUES ('953', 'SSC_CANCEL', 'SUPPORT', '0', '取消', 'Cancel', '取消');
INSERT INTO `ts_lang` VALUES ('954', 'SSC_TYPE_NOEMPTY', 'SUPPORT', '0', '类型不能为空', 'Type can not be empty', '類型不能爲空');
INSERT INTO `ts_lang` VALUES ('955', 'SSC_TITLE_NOEMPTY', 'SUPPORT', '0', '标题不能为空', 'Title can not be empty', '標題不能爲空');
INSERT INTO `ts_lang` VALUES ('956', 'SSC_DESCRIPTION_NOEMPTY', 'SUPPORT', '0', '描述不能为空', 'Description can not be empty', '描述不能爲空');
INSERT INTO `ts_lang` VALUES ('957', 'SSC_SUBMIT_FEEDBACK_SUCCESS', 'SUPPORT', '0', '提交成功，感谢您的反馈', 'Submitted successfully, thanks for your feedback', '提交成功，感謝您的反饋');
INSERT INTO `ts_lang` VALUES ('958', 'SSC_ALL', 'SUPPORT', '0', '全部', 'All', '全部');
INSERT INTO `ts_lang` VALUES ('959', 'SSC_MANAGEMENT', 'SUPPORT', '0', '帮助中心管理', 'Support Center Management', '幫助中心管理');
INSERT INTO `ts_lang` VALUES ('960', 'SSC_FEEDBACK_MANAGEMENT', 'SUPPORT', '0', '反馈管理', 'Feedback Management', '反饋管理');
INSERT INTO `ts_lang` VALUES ('961', 'SSC_WAIT_PROCESSE', 'SUPPORT', '0', '待处理', 'Pending', '待處理');
INSERT INTO `ts_lang` VALUES ('962', 'SSC_ALREADY_PROCESSED', 'SUPPORT', '0', '已处理', 'Processed', '已處理');
INSERT INTO `ts_lang` VALUES ('963', 'SSC_THE_AUTHOR', 'SUPPORT', '0', '提交人', 'The Author', '提交人');
INSERT INTO `ts_lang` VALUES ('964', 'SSC_SUBMITTED_TIME', 'SUPPORT', '0', '提交时间', 'Submitted Time', '提交時間');
INSERT INTO `ts_lang` VALUES ('965', 'SSC_OPERATE', 'SUPPORT', '0', '操作', 'Operation', '操作');
INSERT INTO `ts_lang` VALUES ('966', 'SSC_VIEW_DETAILS', 'SUPPORT', '0', '查看详情', 'View Details', '查看詳情');
INSERT INTO `ts_lang` VALUES ('967', 'SSC_MARK_PROCESSED', 'SUPPORT', '0', '标记已处理', 'Mark Processed', '標記已處理');
INSERT INTO `ts_lang` VALUES ('968', 'SSC_RECOVERY_UNHANDLED', 'SUPPORT', '0', '恢复未处理', 'Recovery Unhandled', '恢複未處理');
INSERT INTO `ts_lang` VALUES ('969', 'SSC_HANDLED_SUCCESS', 'SUPPORT', '0', '处理成功', 'Handled Successfully', '處理成功');
INSERT INTO `ts_lang` VALUES ('970', 'SSC_BACK_TO_LIST', 'SUPPORT', '0', '返回列表页', 'Back To List', '返回列表頁');
INSERT INTO `ts_lang` VALUES ('971', 'SSC_CURRENT_STATE', 'SUPPORT', '0', '当前状态', 'Current State', '當前狀態');
INSERT INTO `ts_lang` VALUES ('972', 'SSC_UNHANDLE', 'SUPPORT', '0', '未处理', 'Unhandle', '未處理');
INSERT INTO `ts_lang` VALUES ('973', 'SSC_TOP', 'SUPPORT', '0', '帮助排行榜', 'Support Top', '幫助排行榜');
INSERT INTO `ts_lang` VALUES ('974', 'SSC_SEARCH_SUPPORT_CENTER', 'SUPPORT', '0', '搜帮助中心', 'Search Support Center', '搜幫助中心');
INSERT INTO `ts_lang` VALUES ('975', 'SSC_NO_RELATE_DATA', 'PUBLIC', '0', '没有相关数据', 'No Related Data', '沒有相關數據');
INSERT INTO `ts_lang` VALUES ('976', 'SSC_CREATE_PROBLEM', 'SUPPORT', '0', '创建问题', 'Create Problem', '創建問題');
INSERT INTO `ts_lang` VALUES ('977', 'SSC_PROBLEM', 'SUPPORT', '0', '问题', 'Problem', '問題');
INSERT INTO `ts_lang` VALUES ('978', 'SSC_ANSWER', 'SUPPORT', '0', '答案', 'Answer', '答案');
INSERT INTO `ts_lang` VALUES ('979', 'SSC_ATTACHMENT', 'SUPPORT', '0', '附件', 'Attachment', '附件');
INSERT INTO `ts_lang` VALUES ('980', 'SSC_TAG_TIPS', 'SUPPORT', '0', '按回车添加标签，多个标签用逗号分隔', 'Press Enter to add tags, multiple tags separated by commas', '按回車添加標簽，多個標簽用逗號分隔');
INSERT INTO `ts_lang` VALUES ('981', 'SSC_FILE_LIMIT', 'SUPPORT', '0', '文件大小<{num}M，支持格式{sum}', 'File size <{num}M, support {sum}', '文件大小<{num}M，支持格式{sum}');
INSERT INTO `ts_lang` VALUES ('982', 'SSC_SAVE', 'SUPPORT', '0', '保存', 'Save', '保存');
INSERT INTO `ts_lang` VALUES ('983', 'SSC_REQUIRED', 'SUPPORT', '0', '加*项为必填项', 'With * is required', '加*項爲必填項');
INSERT INTO `ts_lang` VALUES ('984', 'SSC_TAG_NOEMPTY', 'SUPPORT', '0', '标签不能为空', 'Tag can not be empty', '標簽不能爲空');
INSERT INTO `ts_lang` VALUES ('985', 'SSC_TAG__ALREADY_EXIST', 'SUPPORT', '0', '标签已经存在', 'Tag already exists', '標簽已經存在');
INSERT INTO `ts_lang` VALUES ('986', 'SSC_TAG__ALREADY_UPLOAD', 'SUPPORT', '0', '此文件已经上传过了,请勿重复上传', 'This file has been uploaded,do not repeat the upload', '此文件已經上傳過了,請勿重複上傳');
INSERT INTO `ts_lang` VALUES ('987', 'SSC_FAIL_SIZE_WRONG', 'SUPPORT', '0', '上传文件大小不符，文件不能超过{num}MB', 'Upload file size does not match, can not exceed {num} MB', '上傳文件大小不符，文件不能超過{num}MB');
INSERT INTO `ts_lang` VALUES ('988', 'SSC_FAIL_TYPE_WRONG', 'SUPPORT', '0', '上传文件类型不允许', 'Upload file type does not allow', '上傳文件類型不允許');
INSERT INTO `ts_lang` VALUES ('989', 'SSC_CANCEL_UPLOAD', 'SUPPORT', '0', '取消上传', 'Cancel Upload', '取消上傳');
INSERT INTO `ts_lang` VALUES ('990', 'SSC_SUBMIT_SUCCESS', 'SUPPORT', '0', '发布成功', 'Posted successfully', '發布成功');
INSERT INTO `ts_lang` VALUES ('991', 'SSC_SAVE_SUCCESS', 'SUPPORT', '0', '保存成功', 'Saved Successfully', '保存成功');
INSERT INTO `ts_lang` VALUES ('992', 'SSC_PROBLEM_MANAGEMENT', 'SUPPORT', '0', '问题管理', 'Problem Management', '問題管理');
INSERT INTO `ts_lang` VALUES ('993', 'SSC_DRAFTS', 'SUPPORT', '0', '草稿箱', 'Drafts', '草稿箱');
INSERT INTO `ts_lang` VALUES ('994', 'SSC_WAIT_REVIEW', 'SUPPORT', '0', '等待审核', 'Reviewing', '等待審核');
INSERT INTO `ts_lang` VALUES ('995', 'SSC_RELEASE_PROBLEM', 'SUPPORT', '0', '成功发布', 'Posted', '成功發布');
INSERT INTO `ts_lang` VALUES ('996', 'SSC_REJECT_PROBLEM', 'SUPPORT', '0', '驳回问题', 'Rejected', '駁回問題');
INSERT INTO `ts_lang` VALUES ('997', 'SSC_FAILURE_PROBLEM', 'SUPPORT', '0', '失效问题', 'Failure', '失效問題');
INSERT INTO `ts_lang` VALUES ('998', 'SSC_SAVE_TIME', 'SUPPORT', '0', '保存时间', 'Saved Time', '保存時間');
INSERT INTO `ts_lang` VALUES ('999', 'SSC_EDIT', 'SUPPORT', '0', '编辑', 'Edit', '編輯');
INSERT INTO `ts_lang` VALUES ('1000', 'SSC_INHERITANCE_CREATE', 'SUPPORT', '0', '继承创建', 'Inherit Create', '繼承創建');
INSERT INTO `ts_lang` VALUES ('1001', 'SSC_FAILURE_SETTING', 'SUPPORT', '0', '设置失效', 'Set Failure', '設置失效');
INSERT INTO `ts_lang` VALUES ('1002', 'SSC_DELETE_PROBLEM', 'SUPPORT', '0', '删除问题', 'Delete', '刪除問題');
INSERT INTO `ts_lang` VALUES ('1003', 'SSC_REJECT', 'SUPPORT', '0', '驳回', 'Reject', '駁回');
INSERT INTO `ts_lang` VALUES ('1004', 'SSC_POST', 'SUPPORT', '0', '发布', 'Post', '發布');
INSERT INTO `ts_lang` VALUES ('1005', 'SSC_UPDATE', 'SUPPORT', '0', '更新', 'Update', '更新');
INSERT INTO `ts_lang` VALUES ('1006', 'SSC_SET_TOP', 'SUPPORT', '0', '设置TOP', 'Set TOP', '設置TOP');
INSERT INTO `ts_lang` VALUES ('1007', 'SSC_SET_EFFECT', 'SUPPORT', '0', '设置生效', 'Set Effect', '設置生效');
INSERT INTO `ts_lang` VALUES ('1008', 'SSC_PREVIOUS_PAGE', 'SUPPORT', '0', '上一页', 'Previous Page', '上一頁');
INSERT INTO `ts_lang` VALUES ('1009', 'SSC_NEXT_PAGE', 'SUPPORT', '0', '下一页', 'Next Page', '下一頁');
INSERT INTO `ts_lang` VALUES ('1010', 'SSC_UPDATE_SUCCESS', 'SUPPORT', '0', '更新成功', 'Updated Successfully', '更新成功');
INSERT INTO `ts_lang` VALUES ('1011', 'SSC_POST_FAIL', 'SUPPORT', '0', '发布失败', 'Post Failed', '發布失敗');
INSERT INTO `ts_lang` VALUES ('1012', 'SSC_FAILURE_SETING_CONFIRM', 'SUPPORT', '0', '确定要设置失效？', 'Confirm to set failure?', '確定要設置失效？');
INSERT INTO `ts_lang` VALUES ('1013', 'SSC_SET_SUCCESS', 'SUPPORT', '0', '设置成功', 'Set Successfully', '設置成功');
INSERT INTO `ts_lang` VALUES ('1014', 'SSC_SET_FAIL', 'SUPPORT', '0', '设置失败', 'Set Failed', '設置失敗');
INSERT INTO `ts_lang` VALUES ('1015', 'SSC_DELETE_CONFIRM', 'SUPPORT', '0', '确定要删除？', 'Confirm to delete?', '確定要刪除？');
INSERT INTO `ts_lang` VALUES ('1588', 'SSC_DELETE_SUCCESS', 'PUBLIC', '0', '删除成功', 'Delete successfully', '删除成功');
INSERT INTO `ts_lang` VALUES ('1017', 'SSC_DELETE_FAIL', 'SUPPORT', '0', '删除失败', 'Delete Failed', '刪除失敗');
INSERT INTO `ts_lang` VALUES ('1018', 'SSC_REJECT_CONFIRM', 'SUPPORT', '0', '确定要驳回？', 'Confirm to reject?', '確定要駁回？');
INSERT INTO `ts_lang` VALUES ('1019', 'SSC_REJECT_SUCCESS', 'SUPPORT', '0', '驳回成功', 'Rejected Successfully', '駁回成功');
INSERT INTO `ts_lang` VALUES ('1020', 'SSC_REJECT_FAIL', 'SUPPORT', '0', '驳回失败', 'Reject Failed', '駁回失敗');
INSERT INTO `ts_lang` VALUES ('1021', 'SSC_DETAILS', 'SUPPORT', '0', '详情', 'Details', '詳情');
INSERT INTO `ts_lang` VALUES ('1022', 'SSC_OPERATION_RECORDS', 'SUPPORT', '0', '操作记录', 'Operation Records', '操作記錄');
INSERT INTO `ts_lang` VALUES ('1023', 'SSC_COMMENT', 'SUPPORT', '0', '评论', 'Comment', '評論');
INSERT INTO `ts_lang` VALUES ('1024', 'SSC_SHARE', 'SUPPORT', '0', '转发', 'Share', '轉發');
INSERT INTO `ts_lang` VALUES ('1025', 'SSC_SHARE_SUCCESS', 'SUPPORT', '0', '转发成功', 'Shared Successfully', '轉發成功');
INSERT INTO `ts_lang` VALUES ('1026', 'SSC_SHARE_PROBLEM', 'SUPPORT', '0', '转发问题', 'Share Problem', '轉發問題');
INSERT INTO `ts_lang` VALUES ('1027', 'SSC_FAVORITE', 'SUPPORT', '0', '收藏', 'Favorite', '收藏');
INSERT INTO `ts_lang` VALUES ('1028', 'SSC_ALREADY_FAVORITED', 'SUPPORT', '0', '已收藏', 'Favorited', '已收藏');
INSERT INTO `ts_lang` VALUES ('1029', 'SSC_CANCEL_FAVORITE_CONFIRM', 'SUPPORT', '0', '确定取消收藏？', 'Confirm to cancel the collection?', '確定取消收藏？');
INSERT INTO `ts_lang` VALUES ('1030', 'SSC_OPERATE_SUCCESS', 'SUPPORT', '0', '操作成功', 'Operated Successfully', '操作成功');
INSERT INTO `ts_lang` VALUES ('1031', 'SSC_APPROVE', 'SUPPORT', '0', '顶', 'Approve', '頂');
INSERT INTO `ts_lang` VALUES ('1032', 'SSC_ALREADY_OPERATE', 'SUPPORT', '0', '您已经执行过该操作', 'You have already performed the operation', '您已經執行過該操作');
INSERT INTO `ts_lang` VALUES ('1033', 'SSC_VIEW_VERSION_HISTORY', 'SUPPORT', '0', '查看历史版本', 'Version History', '查看曆史版本');
INSERT INTO `ts_lang` VALUES ('1034', 'SSC_VERSION_INFORMATION', 'SUPPORT', '0', '版本信息', 'Version Information', '版本信息');
INSERT INTO `ts_lang` VALUES ('1035', 'SSC_LAST_MODIFIED', 'SUPPORT', '0', '最后修改时间', 'Last Modified Time', '最後修改時間');
INSERT INTO `ts_lang` VALUES ('1036', 'SSC_LAST_EDITOR', 'SUPPORT', '0', '最后编辑者', 'Last Editor', '最後編輯者');
INSERT INTO `ts_lang` VALUES ('1037', 'SSC_OPERATE_VERSION', 'SUPPORT', '0', '操作版本', 'Operate Version', '操作版本');
INSERT INTO `ts_lang` VALUES ('1038', 'SSC_BACK_TO_DETAILS', 'SUPPORT', '0', '返回详情页', 'Back To Details Page', '返回詳情頁');
INSERT INTO `ts_lang` VALUES ('1039', 'SSC_ALREADY_ACTIVATED', 'PUBLIC', '0', '已激活', 'Activated', '已激活');
INSERT INTO `ts_lang` VALUES ('1040', 'SSC_ALREADY_ACTIVATE', 'SUPPORT', '0', '激活', 'Activate', '激活');
INSERT INTO `ts_lang` VALUES ('1041', 'SSC_ALREADY_CREATE_PROBLEM', 'SUPPORT', '0', '创建了这个问题', 'created the problem', '創建了這個問題');
INSERT INTO `ts_lang` VALUES ('1042', 'SSC_ALREADY_EDIT_PROBLEM', 'SUPPORT', '0', '编辑了这个问题', ' edited the problem', '編輯了這個問題');
INSERT INTO `ts_lang` VALUES ('1043', 'SSC_ALREADY_SET_FAILURE', 'SUPPORT', '0', '失效了这个问题', 'set the problem failure', '失效了這個問題');
INSERT INTO `ts_lang` VALUES ('1044', 'SSC_ALREADY_SET_REJECT', 'SUPPORT', '0', '驳回了这个问题', ' dismissed the problem', '駁回了這個問題');
INSERT INTO `ts_lang` VALUES ('1045', 'SSC_HAVE_NO_PERMISSIONS', 'SUPPORT', '0', '您没有权限', 'You do not have permissions', '您沒有權限');
INSERT INTO `ts_lang` VALUES ('1046', 'SSC_BASIC_CONFIGURATION', 'SUPPORT', '0', '基本配置', 'Basic Configuration', '基本配置');
INSERT INTO `ts_lang` VALUES ('1047', 'SSC_BILINGUAL_SWITCH', 'SUPPORT', '0', '双语开关', 'Bilingual Switch', '雙語開關');
INSERT INTO `ts_lang` VALUES ('1048', 'SSC_COMMENTS_SWITCH', 'SUPPORT', '0', '评论开关', 'Comments Switch', '評論開關');
INSERT INTO `ts_lang` VALUES ('1049', 'SSC_EVALUATION_SWITCH', 'SUPPORT', '0', '评价开关', 'Evaluation Switch', '評價開關');
INSERT INTO `ts_lang` VALUES ('1050', 'SSC_RECOMMEND_SEARCH_WORDS', 'SUPPORT', '0', '推荐搜索词', 'Recommended Search Words', '推薦搜索詞');
INSERT INTO `ts_lang` VALUES ('1051', 'SSC_RECOMMEND_SEARCH_WORDS_TIPS', 'SUPPORT', '0', '多个推荐搜索词请用英文逗号分隔', 'Recommended search words in English separated by a comma', '多個推薦搜索詞請用英文逗號分隔');
INSERT INTO `ts_lang` VALUES ('1052', 'SSC_NOTIFACATION_SERVICE_SWITCH', 'SUPPORT', '0', '通知服务开关', 'Notification Service Switch', '通知服務開關');
INSERT INTO `ts_lang` VALUES ('1053', 'SSC_PROBLEM_AUDIT_SWITCH', 'SUPPORT', '0', '问题审核开关', 'Problem Audit Switch', '問題審核開關');
INSERT INTO `ts_lang` VALUES ('1528', 'PUBLIC_SENTTO', 'PUBLIC', '0', '发送给', 'send to', '發送給');
INSERT INTO `ts_lang` VALUES ('1529', 'PUBLIC_SUM_TO_TALL', 'PUBLIC', '0', '共{num}条对话', 'A total of {num} Article dialogue', '共{num}條對話');
INSERT INTO `ts_lang` VALUES ('1530', 'PUBLIC_METO', 'PUBLIC', '0', '我和', 'Dialogue between me with', '我和');
INSERT INTO `ts_lang` VALUES ('1055', 'SSC_OPEN', 'SUPPORT', '0', '开启', 'Open', '開啓');
INSERT INTO `ts_lang` VALUES ('1527', 'PUBLIC_SAY', 'PUBLIC', '0', '说', 'says', '說');
INSERT INTO `ts_lang` VALUES ('1057', 'SSC_COMMISSIONER_CONFIGURATION', 'SUPPORT', '0', '专员配置', 'Commissioner Configuration', '專員配置');
INSERT INTO `ts_lang` VALUES ('1058', 'SSC_SERVICES_COMMISSIONER', 'SUPPORT', '0', '服务专员', 'Services Commissioner', '服務專員');
INSERT INTO `ts_lang` VALUES ('1059', 'SSC_EXPERT', 'SUPPORT', '0', '专家', 'Expert', '專家');
INSERT INTO `ts_lang` VALUES ('1060', 'SSC_FEEDBACK_COMMISSIONER', 'SUPPORT', '0', 'Feedback专员', 'Feedback Commissioner', 'Feedback專員');
INSERT INTO `ts_lang` VALUES ('1061', 'SSC_PROBLEM_CLASSIFICATION＿CONFIGURATION', 'SUPPORT', '0', '问题分类配置', 'Problem Category Configuration ', '問題分類配置');
INSERT INTO `ts_lang` VALUES ('1062', 'SSC_ADD_FIRST＿CLASSIFICATION', 'SUPPORT', '0', '添加一级分类', 'Add First Category', '添加一級分類');
INSERT INTO `ts_lang` VALUES ('1063', 'SSC_ADD_SUBCATEGORIES', 'SUPPORT', '0', '添加子分类', 'Add Subcategories', '添加子分類');
INSERT INTO `ts_lang` VALUES ('1064', 'SSC_TRANSFER_CONFIGURATION', 'SUPPORT', '0', '转移内容到其他分类', 'Transfer Classification', '轉移內容到其他分類');
INSERT INTO `ts_lang` VALUES ('1065', 'SSC_CHINESE_CATEGORY_NAME', 'SUPPORT', '0', '中文分类名', 'Chinese Category Name', '中文分類名');
INSERT INTO `ts_lang` VALUES ('1066', 'SSC_ENGLISH_CATEGORY_NAME', 'SUPPORT', '0', '英文分类名', 'English Category Name', '英文分類名');
INSERT INTO `ts_lang` VALUES ('1067', 'SSC_CATEGORY_IMAGE', 'SUPPORT', '0', '分类图片', 'Category Image', '分類圖片');
INSERT INTO `ts_lang` VALUES ('1068', 'SSC_CHINESE_NAME_NOEMPTY', 'SUPPORT', '0', '中文名不能为空', 'Chinese name can not be empty', '中文名不能爲空');
INSERT INTO `ts_lang` VALUES ('1069', 'SSC_ENGLISH_NAME_NOEMPTY', 'SUPPORT', '0', '英文名不能为空', 'English name can not be empty', '英文名不能爲空');
INSERT INTO `ts_lang` VALUES ('1070', 'SSC_IMAGE_NOEMPTY', 'SUPPORT', '0', '图片不能为空', 'The pictures can not be empty', '圖片不能爲空');
INSERT INTO `ts_lang` VALUES ('1071', 'SSC_FAIL_FORMATE_WRONG', 'SUPPORT', '0', '上传文件格式不正确', 'Upload the file format is not correct', '上傳文件格式不正確');
INSERT INTO `ts_lang` VALUES ('1072', 'SSC_CHINESE_TAG_NOEMPTY', 'SUPPORT', '0', '中文标签不能为空', 'Chinese tag can not be empty', '中文標簽不能爲空');
INSERT INTO `ts_lang` VALUES ('1073', 'SSC_ENGLISH_TAG_NOEMPTY', 'SUPPORT', '0', '英文标签不能为空', 'English tag can not be empty', '英文標簽不能爲空');
INSERT INTO `ts_lang` VALUES ('1074', 'SSC_TRANSFER_SUCCESS', 'SUPPORT', '0', '数据转移成功', 'Transfered Successfully', '數據轉移成功');
INSERT INTO `ts_lang` VALUES ('1075', 'SSC_NOT_ALLOW_TRANSFER', 'SUPPORT', '0', '不能转移到当前分类', 'Can not be transferred to the current classification', '不能轉移到當前分類');
INSERT INTO `ts_lang` VALUES ('1076', 'SSC_TRANSFER_PROBLEM_FIRST', 'SUPPORT', '0', '请先转移问题', 'Please transfer the problem', '請先轉移問題');
INSERT INTO `ts_lang` VALUES ('1077', 'SSC_DELETE_SUBCATEGORIES_FIRST', 'SUPPORT', '0', '请先删除子级', 'Please delete the subcategories', '請先刪除子級');
INSERT INTO `ts_lang` VALUES ('1078', 'SSC_DELETE_CATEGORY_CONFIRM', 'SUPPORT', '0', '确定要删除选中的分类', 'Confirm to delete the selected classification', '確定要刪除選中的分類');
INSERT INTO `ts_lang` VALUES ('1079', 'SSC_FEEDBACK_TYPE＿CONFIGURATION', 'SUPPORT', '0', 'Feedback类型配置', 'Feedback Type Of Configuration', 'Feedback類型配置');
INSERT INTO `ts_lang` VALUES ('1080', 'SSC_ADD_FEEDBACK_TYPE', 'SUPPORT', '0', '添加Feedback类型', 'Add Feedback Type', '添加Feedback類型');
INSERT INTO `ts_lang` VALUES ('1081', 'SSC_MODIFY', 'SUPPORT', '0', '修改', 'Modify', '修改');
INSERT INTO `ts_lang` VALUES ('1082', 'SSC_SELECT_DELETE_CATEGORY', 'SUPPORT', '0', '请选择要删除的分类', 'Please select the classification you want to delete', '請選擇要刪除的分類');
INSERT INTO `ts_lang` VALUES ('1083', 'SSC_DELETE_SUBCATEGORIES', 'SUPPORT', '0', '请先删除子分类', 'Please delete the sub-categories', '請先刪除子分類');
INSERT INTO `ts_lang` VALUES ('1084', 'SSC_TRANSFER_CATEGORY_PROBLEM', 'SUPPORT', '0', '请先转移分类下的问题', 'Please transfer problem in category', '請先轉移分類下的問題');
INSERT INTO `ts_lang` VALUES ('1085', 'SSC_TRANSFER_SUBCATEGORIES_PROBLEM', 'SUPPORT', '0', '请先转移子分类下的问题', 'Please transfer problem in sub-categories', '請先轉移子分類下的問題');
INSERT INTO `ts_lang` VALUES ('1086', 'SSC_MANAGE_TIME', 'SUPPORT', '0', '处理时间', ' Processed Time', '處理時間');
INSERT INTO `ts_lang` VALUES ('1087', 'SSC_EDITCATEOGRY', 'SUPPORT', '0', '编辑分类', 'Edit Category', '編輯分類');
INSERT INTO `ts_lang` VALUES ('1088', 'SSC_ADD_CATEOGRY', 'SUPPORT', '0', '添加Feedback类型', 'Add Category ', '添加Feedback類型');
INSERT INTO `ts_lang` VALUES ('1089', 'PUBLIC_EDIT', 'PUBLIC', '1', '编辑', 'Edit', '編輯');
INSERT INTO `ts_lang` VALUES ('1090', 'PUBLIC_NOMOVE_UP', 'PUBLIC', '1', '不可以再上移了', 'Can no longer shift', '不可以再上移了');
INSERT INTO `ts_lang` VALUES ('1091', 'PUBLIC_NOMOVE_DOWN', 'PUBLIC', '1', '不可以再下移了', 'Can no longer down', '不可以再下移了');
INSERT INTO `ts_lang` VALUES ('1092', 'PUBLIC_TIME_ISNOT', 'PUBLIC', '1', '时间不正确', 'Time is incorrect', '時間不正確');
INSERT INTO `ts_lang` VALUES ('1093', 'PUBLIC_DELETE_NOTE_TIPS', 'PUBLIC', '1', '确定删除此条日志记录？', 'Make sure to delete the log record?', '確定刪除此條日志記錄？');
INSERT INTO `ts_lang` VALUES ('1094', 'PUBLIC_SELECT_DELETE_TIPS', 'PUBLIC', '1', '请先选择要操作的数据', 'Please select the operation data', '請先選擇要操作的數據');
INSERT INTO `ts_lang` VALUES ('1095', 'PUBLIC_DELETE_NOTE_SELECT_TIPS', 'PUBLIC', '1', '确定删除选中日志记录？', 'Make sure to delete the selected log?', '確定刪除選中日志記錄？');
INSERT INTO `ts_lang` VALUES ('1096', 'PUBLIC_FAVORITE_TIPS', 'PUBLIC', '1', '请选择要关注的对象', 'Please choose the user you want to pay attention', '請選擇要關注的對象');
INSERT INTO `ts_lang` VALUES ('1097', 'PUBLIC_APP_INEX', 'PUBLIC', '1', '添加应用', 'Add Application', '添加應用');
INSERT INTO `ts_lang` VALUES ('1098', 'PUBLIC_MOVER_APP_TIPTS', 'PUBLIC', '1', '确定移除{app}应用？', 'Confirm to remove application {app}?', '確定移除{app}應用？');
INSERT INTO `ts_lang` VALUES ('1099', 'PUBLIC_MOVER_APP', 'PUBLIC', '1', '移除应用', 'Remove Application', '移除應用');
INSERT INTO `ts_lang` VALUES ('1100', 'PUBLIC_UPDATE_TYPE_TIPS', 'PUBLIC', '1', '上传文件类型不支持', 'Upload file type not supported', '上傳文件類型不支持');
INSERT INTO `ts_lang` VALUES ('1101', 'PUBLIC_INVITE_COLLEAGUE', 'PUBLIC', '1', '邀请好友', 'Invite Friends', '邀請好友');
INSERT INTO `ts_lang` VALUES ('1102', 'PUBLIC_AT_FOLLOWING', 'PUBLIC', '1', '请输入@的人昵称', 'Please enter the @ name or mailbox', '請輸入@的人暱稱');
INSERT INTO `ts_lang` VALUES ('1805', 'NOTIFY_GIFT_SEND_CONTENT', 'PUBLIC', '0', '系统通知：有人赠送您礼物，快去看看吧', '', '');
INSERT INTO `ts_lang` VALUES ('1103', 'PUBLIC_ADD_PASSUSER_TIPES', 'PUBLIC', '1', '确定将用户加入黑名单？', 'Make sure to add the user to the blacklist?', '確定將用戶加入黑名單？');
INSERT INTO `ts_lang` VALUES ('1104', 'PUBLIC_MOVE_PASSUSER_TIPES', 'PUBLIC', '1', '移出黑名单', 'Remove From Blacklist', '移出黑名單');
INSERT INTO `ts_lang` VALUES ('1105', 'PUBLIC_ADD_PASSUDER', 'PUBLIC', '1', '加入黑名单', 'Add To Blacklist', '加入黑名單');
INSERT INTO `ts_lang` VALUES ('1106', 'PUBLIC_TIPES_ERROR', 'PUBLIC', '1', '参数错误', 'Parameter Error', '參數錯誤');
INSERT INTO `ts_lang` VALUES ('1107', 'PUBLIC_FAVORITED', 'PUBLIC', '1', '已收藏', 'Favorited', '已收藏');
INSERT INTO `ts_lang` VALUES ('1108', 'PUBLIC_DEL_FAVORITE', 'PUBLIC', '1', '取消收藏', 'Cancel Favorite', '取消收藏');
INSERT INTO `ts_lang` VALUES ('1109', 'PUBLIC_FAVORITE_SUCCESS', 'PUBLIC', '1', '收藏成功', 'Favorited Successfully', '收藏成功');
INSERT INTO `ts_lang` VALUES ('1110', 'PUBLIC_DEL_FAVORITE_TIPES', 'PUBLIC', '1', '确定取消收藏？', 'Make sure to cancel favorite?', '確定取消收藏？');
INSERT INTO `ts_lang` VALUES ('1111', 'PUBLIC_FAVORITE', 'PUBLIC', '1', '收藏', 'Favorite', '收藏');
INSERT INTO `ts_lang` VALUES ('1112', 'PUBLIC_RESAVE', 'PUBLIC', '1', '回复', 'Reply', '回複');
INSERT INTO `ts_lang` VALUES ('1113', 'PUBLIC_CONCENT_TIPES', 'PUBLIC', '1', '评论不能为空', 'Comment can not be empty', '評論不能爲空');
INSERT INTO `ts_lang` VALUES ('1114', 'PUBLIC_REPORT_END', 'PUBLIC', '1', '您已经成功举报此信息', 'You have successfully reported this information', '您已經成功舉報此信息');
INSERT INTO `ts_lang` VALUES ('1115', 'PUBLIC_SYSTEM_MAIL', 'PUBLIC', '1', '条新系统通知', ' new system notifications', '條新系統通知');
INSERT INTO `ts_lang` VALUES ('1116', 'PUBLIC_SYSTEM_TAME', 'PUBLIC', '1', '条新@提到我', ' new @ mentioned', '條新@提到我');
INSERT INTO `ts_lang` VALUES ('1117', 'PUBLIC_SYSTEM_CONCENT', 'PUBLIC', '1', '条新的评论', ' new comments', '條新的評論');
INSERT INTO `ts_lang` VALUES ('1118', 'PUBLIC_SYSTEM_PRIVATE_MAIL', 'PUBLIC', '1', '条新的私信', ' new private letters', '條新的私信');
INSERT INTO `ts_lang` VALUES ('1119', 'PUBLIC_SYSTEM_FOLLOWING', 'PUBLIC', '1', '位新粉丝', ' new fans', '位新粉絲');
INSERT INTO `ts_lang` VALUES ('1120', 'PUBLIC_CLOSE', 'PUBLIC', '1', '关闭', 'Close', '關閉');
INSERT INTO `ts_lang` VALUES ('1121', 'PUBLIC_FACEING', 'PUBLIC', '1', '常用表情', 'Emotion', '常用表情');
INSERT INTO `ts_lang` VALUES ('1122', 'PUBLIC_USER_ISNOT_TIPES', 'PUBLIC', '1', '此用户已经存在', 'The user already exists', '此用戶已經存在');
INSERT INTO `ts_lang` VALUES ('1123', 'PUBLIC_SELECT_USER_TIPES', 'PUBLIC', '1', '只能选择{user}个用户', 'can only choose {sum} users', '只能選擇{user}個用戶');
INSERT INTO `ts_lang` VALUES ('1124', 'PUBLIC_SEARCH_USER_TIPES', 'PUBLIC', '1', '没有查找到相关用户', 'No Related User', '沒有查找到相關用戶');
INSERT INTO `ts_lang` VALUES ('1125', 'PUBLIC_PLEASE_SEARCH_USER', 'PUBLIC', '1', '请输入查找的人昵称', 'Please enter the search name or E-mail', '請輸入查找的人暱稱');
INSERT INTO `ts_lang` VALUES ('1126', 'PUBLIC_CENTE_ISNULL', 'PUBLIC', '1', '发布内容不能为空', 'Published content can not be empty', '發布內容不能爲空');
INSERT INTO `ts_lang` VALUES ('1127', 'PUBLIC_SHARE_SUCCESS', 'PUBLIC', '1', '转发成功', 'Shared Successfully', '轉發成功');
INSERT INTO `ts_lang` VALUES ('1128', 'PUBLIC_INPUT_TIPES', 'PUBLIC', '1', '可以输入{sum}个字', '{sum} limited', '可以輸入{sum}個字');
INSERT INTO `ts_lang` VALUES ('1129', 'PUBLIC_PLEASE_INPUT_TIPES', 'PUBLIC', '1', '您还可以输入{sum}个字', '{sum} left', '您還可以輸入{sum}個字');
INSERT INTO `ts_lang` VALUES ('1130', 'PUBLIC_INPUT_ERROR_TIPES', 'PUBLIC', '1', '已经超过了{sum}个字', 'More than {sum} characters', '已經超過了{sum}個字');
INSERT INTO `ts_lang` VALUES ('1131', 'PUBLIC_ADMIN_OPRETING_SUCCESS', 'PUBLIC', '1', '操作成功', 'Operated Successfully', '操作成功');
INSERT INTO `ts_lang` VALUES ('1132', 'PUBLIC_ADMIN_OPRETING_TIPES', 'PUBLIC', '1', '您已经执行过该操作', 'You have already performed the operation', '您已經執行過該操作');
INSERT INTO `ts_lang` VALUES ('1133', 'PUBLIC_ADMIN_OPRETING_ERROR', 'PUBLIC', '1', '操作失败', 'Operate Failed', '操作失敗');
INSERT INTO `ts_lang` VALUES ('1134', 'PUBLIC_UPLOAD_TIPES_ERROR', 'PUBLIC', '1', '上传文件格式不正确', 'Upload file format is not correct', '上傳文件格式不正確');
INSERT INTO `ts_lang` VALUES ('1135', 'PUBLIC_UPLOAD_ISMAX', 'PUBLIC', '1', '最多只能上传4个附件', 'Can only upload up to 4 attachments', '最多只能上傳4個附件');
INSERT INTO `ts_lang` VALUES ('1136', 'PUBLIC_REMOVE_UPLOAD', 'PUBLIC', '1', '取消上传', 'Cancel Upload', '取消上傳');
INSERT INTO `ts_lang` VALUES ('1137', 'PUBLIC_UPLOAD_ISNOT_TIPIES', 'PUBLIC', '1', '此文件已经上传过了，请勿重复上传', 'This file has been uploaded, do not repeat the upload', '此文件已經上傳過了，請勿重複上傳');
INSERT INTO `ts_lang` VALUES ('1138', 'PUBLIC_LOADING', 'PUBLIC', '1', '加载中', 'Loading', '加載中');
INSERT INTO `ts_lang` VALUES ('1139', 'PUBLIC_NEXT', 'PUBLIC', '1', '下一页', 'Next Page', '下一頁');
INSERT INTO `ts_lang` VALUES ('1140', 'PUBLIC_ISNULL', 'PUBLIC', '1', '没有了', 'No', '沒有了');
INSERT INTO `ts_lang` VALUES ('1141', 'PUBLIC_WEIBO_NUM', 'PUBLIC', '1', '有{sum}条新微博', ' {sum} new weibo', '有{sum}條新微博');
INSERT INTO `ts_lang` VALUES ('1142', 'PUBLIC_SHARE_IMAGES', 'PUBLIC', '1', '分享图片', 'Share Pictures', '分享圖片');
INSERT INTO `ts_lang` VALUES ('1143', 'PUBLIC_SHARE_FILES', 'PUBLIC', '1', '分享文档', 'Share Documents', '分享文檔');
INSERT INTO `ts_lang` VALUES ('1144', 'PUBLIC_CONCENTING', 'PUBLIC', '1', '发布中...', 'Release ...', '發布中...');
INSERT INTO `ts_lang` VALUES ('1145', 'PUBLIC_DELETE_ERROR', 'PUBLIC', '1', '删除失败', 'Delete Failed', '刪除失敗');
INSERT INTO `ts_lang` VALUES ('1146', 'PUBLIC_DELETE_SUCCESS', 'PUBLIC', '1', '删除成功', 'Deleted Successfully', '刪除成功');
INSERT INTO `ts_lang` VALUES ('1147', 'PUBLIC_MOVE_WEIGET', 'PUBLIC', '1', '确定移除此组件？', 'Make sure to remove this component?', '確定移除此組件？');
INSERT INTO `ts_lang` VALUES ('1148', 'PUBLIC_ADD_WEIGET', 'PUBLIC', '1', '添加组件', 'Add Components', '添加組件');
INSERT INTO `ts_lang` VALUES ('1149', 'PUBLIC_DATA_LOADING', 'PUBLIC', '1', '数据加载中...', 'Data loading ...', '數據加載中...');
INSERT INTO `ts_lang` VALUES ('1150', 'PUBLIC_CEND_MAX', 'PUBLIC', '1', '最多发送给10个人', 'Send up to 10 people', '最多發送給10個人');
INSERT INTO `ts_lang` VALUES ('1151', 'PUBLIC_PARENTER_ISNOT', 'PUBLIC', '1', '您的好友列表中不存在该好友', 'This friend is not exist in your friends list', '您的好友列表中不存在該好友');
INSERT INTO `ts_lang` VALUES ('1152', 'PUBLIC_INPUT_PARENTER', 'PUBLIC', '1', '输入好友姓名(支持全拼输入)', 'Enter your friend’s name (support spelling input)', '輸入好友姓名(支持全拼輸入)');
INSERT INTO `ts_lang` VALUES ('1153', 'PUBLIC_EXPLORER_ISCTRL', 'PUBLIC', '1', '你的浏览器不支持脚本复制或你拒绝了浏览器安全确认，请尝试手动[Ctrl+C]复制。', 'Your browser does not support script copy or you refuse the confirmation of the safety of the browser, please try the manual [Ctrl + C] to copy.', '你的浏覽器不支持腳本複制或你拒絕了浏覽器安全確認，請嘗試手動[Ctrl+C]複制。');
INSERT INTO `ts_lang` VALUES ('1154', 'PUBLIC_EXPLORER_CTRL', 'PUBLIC', '1', '复制成功！请Ctrl+V键粘贴到要加入的页面。', 'Duplication successful! Please Ctrl + V to paste it into the page that you want to join.', '複制成功！請Ctrl+V鍵粘貼到要加入的頁面。');
INSERT INTO `ts_lang` VALUES ('1155', 'PUBLIC_MOVE_TOP', 'PUBLIC', '1', '返回顶部', 'Back to top', '返回頂部');
INSERT INTO `ts_lang` VALUES ('1156', 'PUBLIC_ACCONT_TIPES', 'PUBLIC', '1', '确定要做此项操作吗？', 'Make sure to do this operation?', '確定要做此項操作嗎？');
INSERT INTO `ts_lang` VALUES ('1157', 'PUBLIC_QUEDING', 'PUBLIC', '1', '确定', 'OK', '確定');
INSERT INTO `ts_lang` VALUES ('1158', 'PUBLIC_QUXIAO', 'PUBLIC', '1', '取消', 'Cancel', '取消');
INSERT INTO `ts_lang` VALUES ('1159', 'PUBLIC_SETPRIVATE_MAIL', 'PUBLIC', '1', '发私信', 'Send Private Message', '發私信');
INSERT INTO `ts_lang` VALUES ('1160', 'PUBLIC_DEPATEMENT_SELECT', 'PUBLIC', '1', '部门选择', 'Departments Selection', '部門選擇');
INSERT INTO `ts_lang` VALUES ('1161', 'PUBLIC_PUT', 'PUBLIC', '1', '收起', 'Away', '收起');
INSERT INTO `ts_lang` VALUES ('1162', 'PUBLIC_OPEN', 'PUBLIC', '1', '展开', 'Expand', '展開');
INSERT INTO `ts_lang` VALUES ('1163', 'PUBLIC_OPEN_MORE', 'PUBLIC', '1', '展开更多', 'Carry Out More', '展開更多');
INSERT INTO `ts_lang` VALUES ('1164', 'PUBLIC_EDIT_FOLLWING', 'PUBLIC', '1', '修改备注', 'Edit Remark', '修改備注');
INSERT INTO `ts_lang` VALUES ('1165', 'PUBLIC_CENTSUCCESS', 'PUBLIC', '1', '回复成功', 'Replied Successfully', '回複成功');
INSERT INTO `ts_lang` VALUES ('1166', 'PUBLIC_SHARE', 'PUBLIC', '1', '转发', 'Share', '轉發');
INSERT INTO `ts_lang` VALUES ('1167', 'PUBLIC_ADD_FOLLOWING', 'PUBLIC', '1', '加关注', 'Follow', '加關注');
INSERT INTO `ts_lang` VALUES ('1168', 'PUBLIC_ERROR_FOLLOWING', 'PUBLIC', '1', '取消关注', 'Unfollow', '取消關注');
INSERT INTO `ts_lang` VALUES ('1169', 'PUBLIC_FOLLOWING', 'PUBLIC', '1', '已关注', 'Followed', '已關注');
INSERT INTO `ts_lang` VALUES ('1170', 'PUBLIC_DUBLE_FOLLOWING', 'PUBLIC', '1', '互相关注', 'Mutual Concern', '互相關注');
INSERT INTO `ts_lang` VALUES ('1171', 'PUBLIC_SET_GROUP', 'PUBLIC', '1', '设置分组', 'Set Group', '設置分組');
INSERT INTO `ts_lang` VALUES ('1172', 'PUBLIC_FOLLOWING_SUCCESS', 'PUBLIC', '1', '关注成功', 'Concerned Successfully', '關注成功');
INSERT INTO `ts_lang` VALUES ('1173', 'PUBLIC_EDIT_GROUP', 'PUBLIC', '1', '修改分组', 'Modify Group', '修改分組');
INSERT INTO `ts_lang` VALUES ('1174', 'PUBLIC_CREATE_GROUP', 'PUBLIC', '1', '创建分组', 'Create Group', '創建分組');
INSERT INTO `ts_lang` VALUES ('1175', 'PUBLIC_TYPE_ISNOT', 'PUBLIC', '1', '格式不正确', 'Format is incorrect', '格式不正確');
INSERT INTO `ts_lang` VALUES ('1176', 'PUBLIC_SELECT_DEPARTMENT', 'PUBLIC', '1', '请选择部门', 'Please select department', '請選擇部門');
INSERT INTO `ts_lang` VALUES ('1177', 'PUBLIC_PASSWORD_TIPES_MIN', 'PUBLIC', '1', '密码太短了，最少{sum}位', 'Password is too short, at least {sum} bit', '密碼太短了，最少{sum}位');
INSERT INTO `ts_lang` VALUES ('1178', 'PUBLIC_PASSWORD_TIPES_MAX', 'PUBLIC', '1', '密码太长了，最多{sum}位', 'The password is too long, up to {sum} bit', '密碼太長了，最多{sum}位');
INSERT INTO `ts_lang` VALUES ('1179', 'PUBLIC_PLEASE_PASSWORD_ON', 'PUBLIC', '1', '请输入确认密码', 'Please enter the confirmed password', '請輸入確認密碼');
INSERT INTO `ts_lang` VALUES ('1180', 'PUBLIC_PASSWORD_ISDUBLE_NOT', 'PUBLIC', '1', '两次输入的密码不一致', 'The two passwords are inconsistent', '兩次輸入的密碼不一致');
INSERT INTO `ts_lang` VALUES ('1181', 'PUBLIC_PLEASE_PARENTER', 'PUBLIC', '1', '请选择好友', 'Please select friends', '擇好請選朋友');
INSERT INTO `ts_lang` VALUES ('1182', 'PUBLIC_ATTACH_UPLOADING_NOSENT', 'PUBLIC', '1', '附件正在上传中，请稍后再发布', 'The attachment is uploading, please release later', '附件正在上傳中，請稍後再發布');
INSERT INTO `ts_lang` VALUES ('1183', 'PUBLIC_TEXTARE_PLEASE', 'PUBLIC', '1', '#请在这里输入自定义话题#', '#Topic#', '#請在這裏輸入自定義話題#');
INSERT INTO `ts_lang` VALUES ('1184', 'PUBLIC_DELETE_THISNEWS', 'PUBLIC', '1', '确定删除这条信息？', 'Conform to delete?', '確定刪除這條信息？');
INSERT INTO `ts_lang` VALUES ('1185', 'PUBLIC_MANAGE_GROUP', 'PUBLIC', '1', '管理分组', 'Manage Group', '管理分組');
INSERT INTO `ts_lang` VALUES ('1186', 'PUBLIC_TYPENOT_ISNULL', 'PUBLIC', '1', '积分类型不能为空', 'Points type can not be empty', '積分類型不能爲空');
INSERT INTO `ts_lang` VALUES ('1187', 'PUBLIC_TYPENAME_ISNULL', 'PUBLIC', '1', '积分别名不能为空', 'Points alias can not be empty', '積分別名不能爲空');
INSERT INTO `ts_lang` VALUES ('1188', 'PUBLIC_NUMBER_ISNULL', 'PUBLIC', '1', '数量不能为空', 'Quantity can not be empty', '數量不能爲空');
INSERT INTO `ts_lang` VALUES ('1189', 'PUBLIC_PLEASE_SELECTDATA', 'PUBLIC', '1', '请选择要操作的数据', 'Please select the data to operate', '請選擇要操作的數據');
INSERT INTO `ts_lang` VALUES ('1190', 'PUBLIC_DEPARENT_ISNULL', 'PUBLIC', '1', '部门名称不能为空', 'Department name can not be empty', '部門名稱不能爲空');
INSERT INTO `ts_lang` VALUES ('1191', 'PUBLIC_DELETE_DEPARENT', 'PUBLIC', '1', '删除部门', 'Delete Department', '刪除部門');
INSERT INTO `ts_lang` VALUES ('1192', 'PUBLIC_SELECT_NEWDEPARENT', 'PUBLIC', '1', '请选择一个具体的新部门', 'Please select a specific department', '請選擇一個具體的新部門');
INSERT INTO `ts_lang` VALUES ('1193', 'PUBLIC_EDIT_NAME', 'PUBLIC', '1', '修改名称', 'Change Name', '修改名稱');
INSERT INTO `ts_lang` VALUES ('1194', 'PUBLIC_MOVE_NAME', 'PUBLIC', '1', '移动名称', 'Move Name', '移動名稱');
INSERT INTO `ts_lang` VALUES ('1195', 'PUBLIC_EDIT_NO', 'PUBLIC', '1', '未作修改', 'Unmodified', '未作修改');
INSERT INTO `ts_lang` VALUES ('1196', 'PUBLIC_SELECT_EDIT_GROUP', 'PUBLIC', '1', '请选择要操作的用户组', 'Please select the user group', '請選擇要操作的用戶組');
INSERT INTO `ts_lang` VALUES ('1197', 'PUBLIC_ADMIN_GROUP_IS', 'PUBLIC', '1', '普通组,管理组、游客不允许删除', 'General group, management group, visitors are not allowed to delete', '普通組,管理組、遊客不允許刪除');
INSERT INTO `ts_lang` VALUES ('1198', 'PUBLIC_DELETE_GROUP_TIPES', 'PUBLIC', '1', '确定删除所选用户组？', 'Make sure to delete the selected user group?', '確定刪除所選用戶組？');
INSERT INTO `ts_lang` VALUES ('1199', 'PUBLIC_PLEASE_SUERGROUPNAME', 'PUBLIC', '1', '请输入用户组名称', 'Please enter the group name', '請輸入用戶組名稱');
INSERT INTO `ts_lang` VALUES ('1200', 'PUBLIC_SELECT_TASK_TIPES', 'PUBLIC', '1', '请选择要删除的计划任务', 'Please select the scheduled tasks you want to delete', '請選擇要刪除的計劃任務');
INSERT INTO `ts_lang` VALUES ('1201', 'PUBLIC_DELETE_TASK', 'PUBLIC', '1', '确定要删除选中的计划任务？', 'Make sure to delete the selected scheduled tasks?', '確定要刪除選中的計劃任務？');
INSERT INTO `ts_lang` VALUES ('1202', 'PUBLIC_ADD_NOTE_TIPES', 'PUBLIC', '1', '确定要添加标记吗？', 'Make sure to add marks?', '確定要添加已標記嗎？');
INSERT INTO `ts_lang` VALUES ('1203', 'PUBLIC_ACCONTMENT', 'PUBLIC', '1', '公告', 'Announcement', '公告');
INSERT INTO `ts_lang` VALUES ('1204', 'PUBLIC_FOOTER_NOTE', 'PUBLIC', '1', '页脚文章', 'Footer Article', '頁腳文章');
INSERT INTO `ts_lang` VALUES ('1205', 'PUBLIC_SELECT_TITLE_TYPE', 'PUBLIC', '1', '请选择要{title}的{type}', ' Please select a {title} {type}', '請選擇要{title}的{type}');
INSERT INTO `ts_lang` VALUES ('1206', 'PUBLIC_PLEASE_DELETE_TITLE', 'PUBLIC', '1', '请选择要删除的{title}', 'Please select the {title} you want to delete', '請選擇要刪除的{title}');
INSERT INTO `ts_lang` VALUES ('1207', 'PUBLIC_ANSWER_DELETE_TITLE', 'PUBLIC', '1', '确定要删除选中的{title}？', 'Confirm to delete the selected {title}?', '確定要刪除選中的{title}？');
INSERT INTO `ts_lang` VALUES ('1208', 'PUBLIC_ANSWER_DELETE_CATEGORY', 'PUBLIC', '1', '确定要删除此分类吗？', 'Confirm to delete?', '確定要刪除此分類嗎？');
INSERT INTO `ts_lang` VALUES ('1209', 'PUBLIC_PLEASE_DELTER_TIPES', 'PUBLIC', '1', '请选择要删除的数据', 'Please select the data you want to delete', '請選擇要刪除的數據');
INSERT INTO `ts_lang` VALUES ('1210', 'PUBLIC_ANSWER_PLEASE_DELETE_TIPES', 'PUBLIC', '1', '确定要删除选中的数据？', 'Make sure to delete the selected data?', '確定要刪除選中的數據？');
INSERT INTO `ts_lang` VALUES ('1211', 'PUBLIC_ANSWER_DELETE', 'PUBLIC', '1', '确定要删除吗？', 'Make sure to delete?', '確定要刪除嗎？');
INSERT INTO `ts_lang` VALUES ('1212', 'PUBLIC_INNULL', 'PUBLIC', '1', '内容不能为空', 'Content can not be empty', '內容不能爲空');
INSERT INTO `ts_lang` VALUES ('1213', 'PUBLIC_TITLE_ISNULL', 'PUBLIC', '1', '标题不能为空', 'Title can not be empty', '標題不能爲空');
INSERT INTO `ts_lang` VALUES ('1214', 'PUBLIC_SET_WIDGET', 'PUBLIC', '1', '配置widget', 'Configure widget', '配置widget');
INSERT INTO `ts_lang` VALUES ('1215', 'PUBLIC_NAME_ISNULL', 'PUBLIC', '1', '名称不能为空', 'Name can not be empty', '名稱不能爲空');
INSERT INTO `ts_lang` VALUES ('1216', 'PUBLIC_KEY_ISNULL', 'PUBLIC', '1', '键值不能为空', 'Key can not be empty', '鍵值不能爲空');
INSERT INTO `ts_lang` VALUES ('1217', 'PUBLIC_TEST_MAIL_ERROR', 'PUBLIC', '1', '测试邮件发送失败', 'Test message send Failed', '測試郵件發送失敗');
INSERT INTO `ts_lang` VALUES ('1218', 'PUBLIC_TEST_MAIL_SUCCESS', 'PUBLIC', '1', '测试邮件发送成功', 'Test message sent successfully', '測試郵件發送成功');
INSERT INTO `ts_lang` VALUES ('1219', 'PUBLIC_ADMIN_OPINT_TIPES', 'PUBLIC', '1', '权限节点名称格式错误,只能包含英文数字或下划线', 'Permissions node name is malformed, can only contain English or number or underscore', '權限節點名稱格式錯誤,只能包含英文數字或下劃線');
INSERT INTO `ts_lang` VALUES ('1220', 'PUBLIC_ADMIN_APP_TIPES', 'PUBLIC', '1', '应用名称格式错误，只能包含英文数字或下划线', 'The application name is malformed, can only contain English or number or underscore', '應用名稱格式錯誤，只能包含英文數字或下劃線');
INSERT INTO `ts_lang` VALUES ('1221', 'PUBLIC_APP_WEIBO_ISNULL', 'PUBLIC', '1', '应用名称和微博类型不能为空', 'Application name and the type of Weibo can not be empty', '應用名稱和微博類型不能爲空');
INSERT INTO `ts_lang` VALUES ('1222', 'PUBLIC_GONGFU_ISNULL', 'PUBLIC', '1', '动作名称格式错误，只能包含英文数字或下划线', 'The action name is malformed, can only contain English or number or underscore', '動作名稱格式錯誤，只能包含英文數字或下劃線');
INSERT INTO `ts_lang` VALUES ('1223', 'PUBLIC_ANSWER_SELECT_OPINT', 'PUBLIC', '1', '确定要删除选中的权限节点', 'Make sure to delete the selected permissions node', '確定要刪除選中的權限節點');
INSERT INTO `ts_lang` VALUES ('1224', 'PUBLIC_PLEASE_DELETEOPINT', 'PUBLIC', '1', '请选择要删除的权限节点', 'Please select the permissions node you want to delete', '請選擇要刪除的權限節點');
INSERT INTO `ts_lang` VALUES ('1225', 'PUBLIC_ANSWER_INTEG0RL', 'PUBLIC', '1', '确定要删除选中的积分节点', 'Make sure to delete the selected points node', '確定要刪除選中的積分節點');
INSERT INTO `ts_lang` VALUES ('1226', 'PUBLIC_PLEASE_SELECT_INTEG0RL', 'PUBLIC', '1', '请选择要删除的积分节点', 'Please select the points node you want to remove', '請選擇要刪除的積分節點');
INSERT INTO `ts_lang` VALUES ('1227', 'PUBLIC_ANSER_DELETE_CATEGORY', 'PUBLIC', '1', '确定要删除该分类？', 'Make sure to delete the category?', '確定要刪除該分類？');
INSERT INTO `ts_lang` VALUES ('1228', 'PUBLIC_SELECT_DELETE_CATEGORY', 'PUBLIC', '1', '请选择要删除的分类', 'Please select the category you want to delete', '請選擇要刪除的分類');
INSERT INTO `ts_lang` VALUES ('1229', 'PUBLIC_PLEASE_DELETE_FIELD', 'PUBLIC', '1', '请选择要删除的字段', 'Please select the field you want to delete', '請選擇要刪除的字段');
INSERT INTO `ts_lang` VALUES ('1230', 'PUBLIC_ANSWER_DELETE_FIELD', 'PUBLIC', '1', '确定要删除该字段？', 'Make sure to delete the field?', '確定要刪除該字段？');
INSERT INTO `ts_lang` VALUES ('1231', 'PUBLIC_MOVE_USERGROUP', 'PUBLIC', '1', '转移用户组', 'Transfer User Group', '轉移用戶組');
INSERT INTO `ts_lang` VALUES ('1232', 'PUBLIC_PLEASE_SELECT_USER', 'PUBLIC', '1', '请选择用户', 'Please select the user', '請選擇用戶');
INSERT INTO `ts_lang` VALUES ('1233', 'PUBLIC_PLEASE_SELECT_USERGROUP', 'PUBLIC', '1', '请选择用户组', 'Please select the user group', '請選擇用戶組');
INSERT INTO `ts_lang` VALUES ('1234', 'PUBLIC_MOVE_DEPARTMENT', 'PUBLIC', '1', '转移部门', 'Transfer Department', '轉移部門');
INSERT INTO `ts_lang` VALUES ('1235', 'PUBLIC_PLEASE_SELECT_NUMBER', 'PUBLIC', '1', '请选择帐号', 'Please select the account', '請選擇帳號');
INSERT INTO `ts_lang` VALUES ('1236', 'PUBLIC_ANSWER_NUMBER', 'PUBLIC', '1', '确定要恢复选中帐号？', 'Confirm to recovery the selected account?', '確定要恢複選中帳號？');
INSERT INTO `ts_lang` VALUES ('1237', 'PUBLIC_ANSWER_BUMBER_NO', 'PUBLIC', '1', '确定要禁用选中帐号？', 'Make sure to disable the selected account?', '確定要禁用選中帳號？');
INSERT INTO `ts_lang` VALUES ('1238', 'PUBLIC_CLOSE_LOCALHOST_TIPES', 'PUBLIC', '1', '关闭站点后网站将无法访问，确定要关闭吗？', 'The site will not be able to visit after close, sure to close?', '關閉站點後網站將無法訪問，確定要關閉嗎？');
INSERT INTO `ts_lang` VALUES ('1239', 'PUBLIC_PLEASE_APP', 'PUBLIC', '1', '请先选择应用', 'Please select the application', '請先選擇應用');
INSERT INTO `ts_lang` VALUES ('1240', 'PUBLIC_APP_UPDATE_ISNULL', 'PUBLIC', '1', '应用排序不能为空', 'Application of sorting can not be empty', '應用排序不能爲空');
INSERT INTO `ts_lang` VALUES ('1241', 'PUBLIC_LEAD_ISNULL', 'PUBLIC', '1', '导航位置不能为空', 'Navigation position can not be empty', '導航位置不能爲空');
INSERT INTO `ts_lang` VALUES ('1242', 'PUBLIC_HREF_ISNULL', 'PUBLIC', '1', '链接地址不能为空', 'The link address can not be empty', '鏈接地址不能爲空');
INSERT INTO `ts_lang` VALUES ('1243', 'PUBLIC_APPIMG_ISNULL', 'PUBLIC', '1', '应用标志不能为空', 'Application logo can not be empty', '應用標志不能爲空');
INSERT INTO `ts_lang` VALUES ('1244', 'PUBLIC_LEADNAME_ISNULL', 'PUBLIC', '1', '导航名称不能为空', 'Navigation name can not be empty', '導航名稱不能爲空');
INSERT INTO `ts_lang` VALUES ('1245', 'PUBLIC_APPCENT_ISNULL', 'PUBLIC', '1', '应用前台入口不能为空', 'Application front entrance can not be empty', '應用前台入口不能爲空');
INSERT INTO `ts_lang` VALUES ('1246', 'PUBLIC_APPNAME_ISNULL', 'PUBLIC', '1', '应用名称不能为空', 'Application alias can not be empty', '應用名稱不能為空');
INSERT INTO `ts_lang` VALUES ('1247', 'PUBLIC_APP_USE', 'PUBLIC', '1', '立即使用', 'Immediately Use', '立即使用');
INSERT INTO `ts_lang` VALUES ('1248', 'PUBLIC_ANSWER_STOP_APP', 'PUBLIC', '1', '确定停止使用应用', 'Make sure to stop the use of application', '確定停止使用應用');
INSERT INTO `ts_lang` VALUES ('1249', 'PUBLIC_APP_STOP', 'PUBLIC', '1', '停止使用', 'Stop Using', '停止使用');
INSERT INTO `ts_lang` VALUES ('1250', 'PUBLIC_REMARK_CHARACTER_LIMIT', 'PUBLIC', '1', '备注不能超过10个字', 'Remark can not more than 10 characters', '備注不能超過10個字');
INSERT INTO `ts_lang` VALUES ('1251', 'PUBLIC_PRMARK_EDIT_ERROR', 'PUBLIC', '1', '备注修改失败', 'Modify remark failed', '備注修改失敗');
INSERT INTO `ts_lang` VALUES ('1252', 'PUBLIC_SET_PRMARK', 'PUBLIC', '1', '设置备注', 'Set Remark', '設置備注');
INSERT INTO `ts_lang` VALUES ('1253', 'PUBLIC_SETING_SUCCESS', 'PUBLIC', '1', '设置成功', 'Set Successfully', '設置成功');
INSERT INTO `ts_lang` VALUES ('1254', 'PUBLIC_SAVE_SUCCESS', 'PUBLIC', '1', '保存成功', 'Saved Successfully', '保存成功');
INSERT INTO `ts_lang` VALUES ('1255', 'PUBLIC_GROUP_NAME_MAX', 'PUBLIC', '1', '分组名称不能超过{num}个汉字长度', 'Group name cannot be more than {num} length of Chinese characters', '分組名稱不能超過{num}個漢字長度');
INSERT INTO `ts_lang` VALUES ('1256', 'PUBLIC_PLEASE_GROUP_NAME', 'PUBLIC', '1', '请输入分组名称', 'Please enter the group name', '請輸入分組名稱');
INSERT INTO `ts_lang` VALUES ('1257', 'PUBLIC_EDIT_GROUPNAME', 'PUBLIC', '1', '编辑分组', 'Edit Group', '編輯分組');
INSERT INTO `ts_lang` VALUES ('1258', 'PUBLIC_SAVE', 'PUBLIC', '1', '保存', 'Save', '保存');
INSERT INTO `ts_lang` VALUES ('1259', 'PUBLIC_SELECT_PLACE', 'PUBLIC', '1', '请选择地区', 'Please select a place', '請選擇地區');
INSERT INTO `ts_lang` VALUES ('1260', 'PUBLIC_SELECT_AREA', 'PUBLIC', '1', '选择城市', 'Select City', '選擇城市');
INSERT INTO `ts_lang` VALUES ('1320', 'PUBLIC_LANG_SET', 'PUBLIC', '0', '语言设置', 'Language Setting', '語言設置');
INSERT INTO `ts_lang` VALUES ('1321', 'PUBLIC_SHARE_FAILED', 'PUBLIC', '0', '转发失败', 'Share Failed', '轉發失敗');
INSERT INTO `ts_lang` VALUES ('1322', 'PUBLIC_SHARE_TOUSE_EMPTY', 'PUBLIC', '0', '转发接受人不能为空', 'Share to accept a person cannot be empty', '轉發接受人不能为空');
INSERT INTO `ts_lang` VALUES ('1323', 'PUBLIC_TAG_EXIST', 'PUBLIC', '0', '标签已经存在', 'Tag already exists', '標簽已經存在');
INSERT INTO `ts_lang` VALUES ('1324', 'PUBLIC_USER_EMPTY', 'PUBLIC', '0', '用户名不能为空', 'The user name cannot be empty', '用戶名不能爲空');
INSERT INTO `ts_lang` VALUES ('1325', 'PUBLIC_ACCOUNT_EMPTY', 'PUBLIC', '0', '帐号或密码不能为空', 'Account or password can not be empty', '帳號或密碼不能爲空');
INSERT INTO `ts_lang` VALUES ('1326', 'PUBLIC_PASSWORD_ERROR', 'PUBLIC', '0', '密码错误', 'Password error', '密碼錯誤');
INSERT INTO `ts_lang` VALUES ('1327', 'PUBLIC_ACCOUNT_LOCKED', 'PUBLIC', '0', '您的帐号已经被锁定，请稍后再登录', 'Your account has been locked, please login later', '您的帳號已經被鎖定，請稍後再登錄');
INSERT INTO `ts_lang` VALUES ('1328', 'PUBLIC_ACCOUNT_LOCK', 'PUBLIC', '0', '您输入的密码错误次数过多，帐号将被锁定1小时', 'You enter the wrong password too many times, your account will be locked for 1 hour', '您輸入的密碼錯誤次數過多，帳號將被鎖定1小時');
INSERT INTO `ts_lang` VALUES ('1329', 'PUBLIC_ACCOUNT_NOTEMPTY', 'PUBLIC', '0', '帐号不能为空', 'Account can not be empty', '帳號不能爲空');
INSERT INTO `ts_lang` VALUES ('1330', 'PUBLIC_ACCOUNT_REGISTERED', 'PUBLIC', '0', '该用户已注册', 'The user has registered', '該用戶已注冊');
INSERT INTO `ts_lang` VALUES ('1331', 'PUBLIC_EMAIL_REGISTER', 'PUBLIC', '0', '该Email已被注册', 'The Email is registered', '該Email已被注冊');
INSERT INTO `ts_lang` VALUES ('1332', 'PUBLIC_ACCOUNT_MORE', 'PUBLIC', '0', '用户名长度必须在{nums}个字符以上', 'User name length must be {nums} characters above', '用戶名長度必須在{nums}個字符以上');
INSERT INTO `ts_lang` VALUES ('1333', 'PUBLIC_ACCOUNT_USED', 'PUBLIC', '0', '该昵称已被使用', 'The nickname has been used', '該暱稱已被使用');
INSERT INTO `ts_lang` VALUES ('1334', 'PUBLI_USER_NOTEXSIT', 'PUBLIC', '0', '用户不存在', 'User does not exist', '用戶不存在');
INSERT INTO `ts_lang` VALUES ('1335', 'PUBLIC_INVATE_CODE_ERROR', 'PUBLIC', '0', '邀请码错误或已失效', 'To invite a code error or failure', '邀請碼錯誤或已失效');
INSERT INTO `ts_lang` VALUES ('1336', 'PUBLIC_EMAIL_NOT_RIGHT', 'PUBLIC', '0', 'Email不合法或已存在', 'Email is illegal or already exists', 'Email不合法或已存在');
INSERT INTO `ts_lang` VALUES ('1337', 'PUBLIC_UNMAE_NOT_RIGHT', 'PUBLIC', '0', '真实姓名不合法或已存在', 'Real name is not legal or already exists', '真實姓名不合法或已存在');
INSERT INTO `ts_lang` VALUES ('1338', 'PUBLIC_PASSWORD_NOT_RIGHT', 'PUBLIC', '0', '密码不合法', 'The password is invalid', '密碼不合法');
INSERT INTO `ts_lang` VALUES ('1339', 'PUBLIC_NUM_INPUT', 'PUBLIC', '0', '纯数字input输入', 'Pure digital input input', '純數字input輸入');
INSERT INTO `ts_lang` VALUES ('1340', 'PUBLIC_INPUT_SOME', 'PUBLIC', '0', '请输入{input}', 'please input {input}', '請輸入{input}');
INSERT INTO `ts_lang` VALUES ('1341', 'PUBLIC_SOME_NOT_RIGHT', 'PUBLIC', '0', '{input}格式不正确', '{input} Not in the correct format', '{input}格式不正確');
INSERT INTO `ts_lang` VALUES ('1342', 'PUBLIC_CONCENT_IS_OK', 'PUBLIC', '0', '评论成功', 'Comment on the success', '評論成功');
INSERT INTO `ts_lang` VALUES ('1343', 'PUBLIC_LOCAL_APP', 'PUBLIC', '0', '本地应用', 'Local application', '本地應用');
INSERT INTO `ts_lang` VALUES ('1344', 'PUBLIC_REMOTE_APP', 'PUBLIC', '0', '远程应用', 'Remote application', '遠程應用');
INSERT INTO `ts_lang` VALUES ('1345', 'PUBLIC_CLIENT_DOWN', 'PUBLIC', '0', '客户端下载', 'client', '客戶端下載');
INSERT INTO `ts_lang` VALUES ('1346', 'PUBLIC_SHARE_BUTTON', 'PUBLIC', '1', '发布', 'Share', '髮佈');
INSERT INTO `ts_lang` VALUES ('1348', 'PUBLIC_TIME_OUT', 'PUBLIC', '0', '登录超时，请重新登录', 'Login timeout, please login again', '登录超時，請重新登录');
INSERT INTO `ts_lang` VALUES ('1349', 'PUBLIC_PASSWORD_EMPTY', 'PUBLIC', '1', '密码不能为空', 'Password cannot be empty', '密碼不能為空');
INSERT INTO `ts_lang` VALUES ('1350', 'PUBLIC_EMAIL_EMPTY', 'PUBLIC', '1', 'Email不能为空', 'Email cannot be empty', 'Email不能爲空');
INSERT INTO `ts_lang` VALUES ('1351', 'PUBLIC_USER_EMPTY', 'PUBLIC', '1', '用户名不能为空', 'User name cannot be empty', '用戶名不能爲空');
INSERT INTO `ts_lang` VALUES ('1352', 'PUBLIC_DELETE_TAG_CONFIRM', 'PUBLIC', '1', '确定删除此标签？', 'Confirm to delete this tag?', '確定刪除此標簽？');
INSERT INTO `ts_lang` VALUES ('1353', 'SSC_ACTIVE_SUCCESS', 'SUPPORT', '1', '激活成功', 'Activation was successful', '激活成功');
INSERT INTO `ts_lang` VALUES ('1354', 'SSC_ACTIVE_ERROR', 'SUPPORT', '1', '激活失败', 'Activation fails', '激活失敗');
INSERT INTO `ts_lang` VALUES ('1355', 'PUBLIC_ADD_NAV', 'PUBLIC', '1', '添加到导航', 'Add to Navigation', '添加到導航');
INSERT INTO `ts_lang` VALUES ('1356', 'PUBLIC_ADD_NAV', 'PUBLIC', '0', '添加到导航', 'AddNav', '添加到導航');
INSERT INTO `ts_lang` VALUES ('1357', 'PUBLIC_REMOVE_NAV', 'PUBLIC', '1', '移出导航', 'Remove From Nav', '移出導航');
INSERT INTO `ts_lang` VALUES ('1358', 'PUBLIC_REMOVE_NAV', 'PUBLIC', '0', '移出导航', 'Remove From Nav', '移出導航');
INSERT INTO `ts_lang` VALUES ('1359', 'PUBLIC_APPNAME_ASK', 'PUBLIC', '0', '问答', 'Ask', '問答');
INSERT INTO `ts_lang` VALUES ('1360', 'PUBLIC_APPNAME_CONTACT', 'PUBLIC', '0', '通讯录', 'Contact', '通訊錄');
INSERT INTO `ts_lang` VALUES ('1361', 'PUBLIC_APPNAME_SUPPORT', 'PUBLIC', '0', '帮助中心', 'Support Center', '幫助中心');
INSERT INTO `ts_lang` VALUES ('1362', 'PUBLIC_MY', 'PUBLIC', '0', '我的', 'My', '我的');
INSERT INTO `ts_lang` VALUES ('1363', 'PUBLIC_APPNAME_BLOG', 'PUBLIC', '0', '博客', 'Blog', '博客');
INSERT INTO `ts_lang` VALUES ('1364', 'PUBLIC_CONFIRM_DO', 'PUBLIC', '1', '确定要{title}选中的{type}？', 'Confirm to do ?', '确定要{title}选中的{type}？');
INSERT INTO `ts_lang` VALUES ('1365', 'PUBLIC_SELECT_DATE', 'PUBLIC', '0', '请选择时间', 'Please select date', '請選擇時間');
INSERT INTO `ts_lang` VALUES ('1366', 'PUBLIC_INVATE_MESSAGE', 'PUBLIC', '0', 'Hi，我是 {uname}，我发现了一个很不错的网站，我在这里等你，快来加入吧。', 'Hi, I\'m {uname}, I found a very good website, I am here waiting for you, come join me.', 'Hi，我是 {uname}，我髮現了一個很不錯的網站，我在這裡等你，快來加入吧。');
INSERT INTO `ts_lang` VALUES ('1367', 'SSC_EDITCATEGORY', 'SUPPORT', '0', '编辑分类', 'edit category', '编辑分類');
INSERT INTO `ts_lang` VALUES ('1368', 'SSC_MOVEANSWER', 'SUPPORT', '0', '转移内容', 'Move Contents', '轉移內容');
INSERT INTO `ts_lang` VALUES ('1369', 'SSC_DELCATEGORY', 'SUPPORT', '0', '删除分类', 'delete category', '删除分类');
INSERT INTO `ts_lang` VALUES ('1370', 'SSC_REQUIRED', 'SUPPORT', '1', '加*项为必填项', 'With * is required', '加*項爲必填項');
INSERT INTO `ts_lang` VALUES ('1372', 'PUBLIC_SENTNOTIFY_MAIL_TO', 'PUBLIC', '0', '发送私信给', 'Send private message to', '發送私信給');
INSERT INTO `ts_lang` VALUES ('1377', 'PUBLIC_SENTWEIBO', 'PUBLIC', '0', '发微博', 'Post Weibo', '發微博');
INSERT INTO `ts_lang` VALUES ('1378', 'PUBLIC_LIST_NUM_WEIBO', 'PUBLIC', '0', '查看{num}条相关微博', 'View {num} weibo', '查看{num}條相關微博');
INSERT INTO `ts_lang` VALUES ('1379', 'PUBLIC_LIST_NUM_USER', 'PUBLIC', '0', '查看{num}位相关用户', 'View {num} related users', '查看{num}位相關用戶');
INSERT INTO `ts_lang` VALUES ('1380', 'PUBLIC_UPLOAD', 'PUBLIC', '0', '下载', 'Download', '下載');
INSERT INTO `ts_lang` VALUES ('1381', 'PUBLIC_POINT_NAME', 'PUBLIC', '0', '节点名称', 'Node name', '節點名稱');
INSERT INTO `ts_lang` VALUES ('1382', 'PUBLIC_POINT_DETAIL', 'PUBLIC', '0', '节点描述', 'Node description', '節點描述');
INSERT INTO `ts_lang` VALUES ('1383', 'PUBLIC_APP_TO', 'PUBLIC', '0', '所属应用', 'Belongs to the application', '所屬應用');
INSERT INTO `ts_lang` VALUES ('1384', 'PUBLIC_SEND_PRIVATE_MESSAGE', 'PUBLIC', '1', '发私信', 'Send Private Message ', '發私信');
INSERT INTO `ts_lang` VALUES ('1385', 'PUBLIC_SENTTYPE', 'PUBLIC', '0', '发送类型', 'Send Type', '發送類型');
INSERT INTO `ts_lang` VALUES ('1386', 'PUBLIC_SNED_EMAIL', 'PUBLIC', '0', '发送邮件', 'Send Mail', '發送郵件');
INSERT INTO `ts_lang` VALUES ('1387', 'PUBLIC_SEND_SYSTEM_MESSAGE', 'PUBLIC', '0', '发送系统消息', 'Send System Message', '發送系統消息');
INSERT INTO `ts_lang` VALUES ('1388', 'PUBLIC_EDIT_TPL', 'PUBLIC', '0', '编辑模板', 'Edit Template', '編輯模板');
INSERT INTO `ts_lang` VALUES ('1389', 'PUBLIC_TITLES', 'PUBLIC', '0', '标题', 'Title', '標題');
INSERT INTO `ts_lang` VALUES ('1390', 'PUBLIC_CONTINUE_SEND', 'PUBLIC', '0', '继续发送', 'Continue to send', '繼續發送');
INSERT INTO `ts_lang` VALUES ('1391', 'PUBLIC_SEBT_EMAIL_TIPES', 'PUBLIC', '0', '此次发送{num}条邮件，其中成功发送{sum}条', 'The send {num} article e-mail, which was successfully sent {sum}', '此次發送{num}條郵件，其中成功發送{sum}條');
INSERT INTO `ts_lang` VALUES ('1392', 'PUBLIC_STREAM_REPORT_PLEASE_SELECT', 'PUBLIC', '0', '请先选择一个举报', 'Please choose a Report', '請先選擇一個舉報');
INSERT INTO `ts_lang` VALUES ('1393', 'PUBLIC_DELETE_NUMBER_TIPES', 'PUBLIC', '1', '您将删除{num}条记录，确定继续？', 'You would remove {num} records, continue?', '您將刪除{num}條記錄，確定繼續？');
INSERT INTO `ts_lang` VALUES ('1394', 'PUBLIC_UNSET_TIPES', 'PUBLIC', '1', '您将撤销举报{num}条记录，确定继续？', 'You will withdraw the report {num} records, continue?', '您將撤銷舉報{num}條記錄，確定繼續？');
INSERT INTO `ts_lang` VALUES ('1395', 'PUBLIC_LAST', 'PUBLIC', '0', '已经是最后面了', 'Is the last face', '已經是最後面了');
INSERT INTO `ts_lang` VALUES ('1404', 'PUBLIC_PROFILESET_BIRTHDAY', 'PUBLIC', '0', '生日', 'Birthday', '生日');
INSERT INTO `ts_lang` VALUES ('1405', 'PUBLIC_BASIC_INFORMATION', 'PUBLIC', '0', '基本信息', 'Basic info', '基本信息');
INSERT INTO `ts_lang` VALUES ('1407', 'PUBLIC_MESSING_SENTTO', 'PUBLIC', '0', '邮件发送', 'Mail', '郵件發送');
INSERT INTO `ts_lang` VALUES ('1408', 'PUBLIC_MAIL_TPL_SET', 'PUBLIC', '0', '消息模板配置', 'Message Template Configuration', '消息模板配置');
INSERT INTO `ts_lang` VALUES ('1409', 'PUBLIC_SENT_EMAIL_TIPES_NUM', 'PUBLIC', '0', '此次发送{num}条邮件，其中成功发送{sum}条', 'The send {num} article e-mail, which was successfully sent {sum}', '此次發送{num}條郵件，其中成功發送{sum}條');
INSERT INTO `ts_lang` VALUES ('1410', 'PUBLIC_PROFILESET_WORK_POSITION', 'PUBLIC', '0', '职务', 'Work Position', '職務');
INSERT INTO `ts_lang` VALUES ('1411', 'PUBLIC_PROFILESET_WORK_EXPERIENCE', 'PUBLIC', '0', '工作经历', 'Work Experience', '工作經曆');
INSERT INTO `ts_lang` VALUES ('1413', 'PUBLIC_USERTITLESHEARE', 'PUBLIC', '0', '用户名片分享', 'User Card Share', '用戶名片分享');
INSERT INTO `ts_lang` VALUES ('1414', 'SSC_VERSIONUPDATE', 'SUPPORT', '0', '更新了这个问题的版本', 'Updated the version of this issue', '更新了這個問題的版本');
INSERT INTO `ts_lang` VALUES ('1415', 'SSC_SUBMIT_THISQUESTION', 'SUPPORT', '0', '发布了这个问题', 'Released this quesiton', '發布了這個問題');
INSERT INTO `ts_lang` VALUES ('1523', 'PUBLIC_NICKNAME_LIMIT', 'PUBLIC', '0', '昵称长度必须在{nums}个汉字之间', 'The nickname length must be in the {nums} characters', '暱稱長度必須在{nums}個漢字之間');
INSERT INTO `ts_lang` VALUES ('1416', 'PUBLIC_REPORT', 'PUBLIC', '1', '举报', 'Report', '舉報');
INSERT INTO `ts_lang` VALUES ('1480', 'PUBLIC_SEARCH_PAGE', 'PUBLIC', '0', '[搜索配置]', '[Search Setting]', '[搜索配置]');
INSERT INTO `ts_lang` VALUES ('1481', 'PUBLIC_HEIDDEN_TIPS', 'PUBLIC', '0', '是否隐藏', 'is hidden.', '是否隱藏');
INSERT INTO `ts_lang` VALUES ('1482', 'PUBLIC_CLICK_TIPES', 'PUBLIC', '0', '点击事件', 'Click event', '點擊事件');
INSERT INTO `ts_lang` VALUES ('1519', 'PUBLIC_DELETE', 'PUBLIC', '1', '删除', 'Delete', '删除');
INSERT INTO `ts_lang` VALUES ('1521', 'PUBLIC_SHOW', 'PUBLIC', '0', '显示', 'Show', '显示');
INSERT INTO `ts_lang` VALUES ('1522', 'PUBLIC_INPUT_NAME ', 'PUBLIC', '1', '请输入名称', 'Please input name', '请输入名称');
INSERT INTO `ts_lang` VALUES ('1524', 'PUBLIC_NICKNAME_TIPS', 'PUBLIC', '0', '昵称长度为{nums}个汉字，仅支持中英文，数字，下划线，不允许重名。', 'Nickname {nums} characters in length , only support in English, numbers, underscores , does not allow the same name .', '暱稱長度為{nums}個漢字，僅支持中英文，數字，下劃線，不允許重名。');
INSERT INTO `ts_lang` VALUES ('1525', 'PUBLIC_NICKNAME', 'PUBLIC', '0', '昵称', 'Nickname', '暱稱');
INSERT INTO `ts_lang` VALUES ('1526', 'PUBLIC_NICKNAME_RESERVED', 'PUBLIC', '0', '抱歉，该昵称不允许被使用', 'Sorry, the nickname is not allowed to be used', '抱歉，該暱稱不允許被使用');
INSERT INTO `ts_lang` VALUES ('1531', 'PUBLIC_ADD_SUCCESS', 'PUBLIC', '1', '添加成功', 'Added successfully', '添加成功');
INSERT INTO `ts_lang` VALUES ('1532', 'NOTIFY_AUTHENTICATE_DOVERIFY_OK_CONTENT', 'PUBLIC', '0', '恭喜，您已成功通过认证。', 'Congratulations, you have successfully passed the verification.', '恭喜，您已成功通過認證。');
INSERT INTO `ts_lang` VALUES ('1533', 'NOTIFY_AUTHENTICATE_DOVERIFY_OK_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜，您已成功通过认证', '[ {site} ]Congratulations, you have passed the certification successfully', '[ {site} ]恭喜，您已成功通過認證');
INSERT INTO `ts_lang` VALUES ('1534', 'NOTIFY_AUTHENTICATE_DOVERIFY_REJECT_CONTENT', 'PUBLIC', '0', '抱歉，您的认证申请被驳回。', 'Sorry, your verification application was rejected.', '抱歉，您的認證申請被駁回。');
INSERT INTO `ts_lang` VALUES ('1535', 'NOTIFY_AUTHENTICATE_DOVERIFY_REJECT_TITLE', 'PUBLIC', '0', '[ {site} ]抱歉，您的认证申请被驳回', '[ {site} ]Sorry, your verification application was rejected', '[ {site} ]抱歉，您的認證申請被駁回');
INSERT INTO `ts_lang` VALUES ('1536', 'NOTIFY_DEL_VERIFY_CONTENT', 'PUBLIC', '0', '您已经成功取消认证，您可在个人设置中重新申请认证。', 'You have successfully canceled the verification, you can re-apply for certification in personal settings.', '您已經成功取消認證，您可在個人設置中重新申請認證。');
INSERT INTO `ts_lang` VALUES ('1537', 'NOTIFY_DEL_VERIFY_TITLE', 'PUBLIC', '0', '[ {site} ]您已经成功取消认证', '[ {site} ]You have cancelled authenticate successfully', '[ {site} ]您已經成功取消認證');
INSERT INTO `ts_lang` VALUES ('1538', 'NOTIFY_REGISTER_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有新用户提交了注册申请，快去后台处理吧。', 'Tips : A new user have submitted the register application , deal with it now', '系統提醒：有新用戶提交了註冊申請，快去後台處理吧。');
INSERT INTO `ts_lang` VALUES ('1539', 'NOTIFY_REGISTER_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有新用户提交了注册申请，快去后台处理吧', '[ {site} ]Tips : A new user have submitted the register application , deal with it now', '[ {site} ]系統提醒：有新用戶提交了註冊申請，快去後台處理吧');
INSERT INTO `ts_lang` VALUES ('1540', 'NOTIFY_VERIFY_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有用户提交了新的认证申请，快去后台处理吧。', 'Tips : A user have  submitted new verify application , deal with it now', '系統提醒：有用戶提交了新的認證申請，快去後台處理吧。');
INSERT INTO `ts_lang` VALUES ('1541', 'NOTIFY_VERIFY_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有用户提交了新的认证申请，快去后台处理吧', '[ {site} ]Tips : A user have  submitted new verify application , deal with it now', '[ {site} ]系統提醒：有用戶提交了新的認證申請，快去後台處理吧');
INSERT INTO `ts_lang` VALUES ('1542', 'NOTIFY_DENOUCE_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有用户举报了内容，快去后台处理吧。', 'Tips : A user have reprotted some content , deal with it now', '系統提醒：有用戶舉報了內容，快去後台處理吧。');
INSERT INTO `ts_lang` VALUES ('1543', 'NOTIFY_DENOUCE_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有用户举报了内容，快去后台处理吧', '[ {site} ]Tips : A user have reprotted some content , deal with it now', '[ {site} ]系統提醒：有用戶舉報了內容，快去後台處理吧');
INSERT INTO `ts_lang` VALUES ('1544', 'NOTIFY_FEEDBACK_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有用户提交了新的反馈，快去后台处理吧。', 'Tips : A  user have submitted some  new suggestion , deal with it now', '系統提醒：有用戶提交了新的反饋，快去後台處理吧。');
INSERT INTO `ts_lang` VALUES ('1545', 'NOTIFY_FEEDBACK_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有用户提交了新的反馈，快去后台处理吧', '[ {site} ]Tips : A  user have submitted some  new suggestion , deal with it now', '[ {site} ]系統提醒：有用戶提交了新的反饋，快去後台處理吧');
INSERT INTO `ts_lang` VALUES ('1547', 'PUBLIC_APPNAME_WEIBA', 'PUBLIC', '0', '微吧', 'weiba', '微吧');
INSERT INTO `ts_lang` VALUES ('1548', 'PUBLIC_APPNAME_PAGE', 'PUBLIC', '0', '门户', 'Page', '門戶');
INSERT INTO `ts_lang` VALUES ('1569', 'NOTIFY_WEIBA_APPLY_OK_CONTENT', 'PUBLIC', '0', '{name}通过了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请', '{name} through you in <a href=\"{source_url}\"> {weiba_name} </ a> right main application', '{name}通过了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请');
INSERT INTO `ts_lang` VALUES ('1570', 'NOTIFY_WEIBA_APPLY_OK_TITLE', 'PUBLIC', '0', '[ {site} ]{name}通过了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请', '[ {site} ]{name} through you in <a href=\"{source_url}\"> {weiba_name} </ a> right main application', '[ {site} ]{name}通过了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请');
INSERT INTO `ts_lang` VALUES ('1571', 'NOTIFY_WEIBA_APPLY_REJECT_CONTENT', 'PUBLIC', '0', '{name}驳回了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请', '{name} rejected you in <a href=\"{source_url}\"> {weiba_name} </ a> right main application', '{name}驳回了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请');
INSERT INTO `ts_lang` VALUES ('1572', 'NOTIFY_WEIBA_APPLY_REJECT_TITLE', 'PUBLIC', '0', '[ {site} ]{name}驳回了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请', '[ {site} ]{name} rejected you in <a href=\"{source_url}\"> {weiba_name} </ a> right main application', '[ {site} ]{name}驳回了您在<a href=\"{source_url}\">{weiba_name}</a>的吧主申请');
INSERT INTO `ts_lang` VALUES ('1568', 'NOTIFY_WEIBA_APPLY_TITLE', 'PUBLIC', '0', '[ {site} ]{name}在\"{weiba_name}\"中提交了吧主申请', '[ {site} ]{name} submitted the main application in \"{weiba_name}\",<a href=\"{source_url}\"> Click here to view </a>', '[ {site} ]{name}在\"{weiba_name}\"中提交了吧主申請');
INSERT INTO `ts_lang` VALUES ('1573', 'PUBLIC_TASK', 'PUBLIC', '0', '任务', 'Task', '任務');
INSERT INTO `ts_lang` VALUES ('1574', 'PUBLIC_TASK_INFO', 'PUBLIC', '0', '任务管理', 'Task Manage', '任務管理');
INSERT INTO `ts_lang` VALUES ('1575', 'PUBLIC_TASK_LIST', 'PUBLIC', '0', '任务列表', 'Task List', '任務列表');
INSERT INTO `ts_lang` VALUES ('1576', 'PUBLIC_TASK_REWARD', 'PUBLIC', '0', '任务奖励', 'Task Reward', '任務獎勵');
INSERT INTO `ts_lang` VALUES ('1577', 'NOTIFY_ADD_USER_MEDAL_CONTENT', 'PUBLIC', '0', '恭喜，您已获得系统管理员颁发的\"{medal_name}\"勋章，获奖理由:{desc}，详情请点击：<a href=\"{medal_url}\" target=\"_blank\">我的勋章</a>', 'Congratulations, you have been issued by the system administrator {medal_name} Medal Awards reason: {desc} For details, please click: <a href=\"{medal_url}\" target=\"_blank\"> my medal </ a>', '恭喜，您已獲得系統管理員頒發的\"{medal_name}\"勳章，獲獎理由:{desc}，詳情請點擊：<a href=\"{medal_url}\" target=\"_blank\">我的勳章</a>');
INSERT INTO `ts_lang` VALUES ('1578', 'NOTIFY_ADD_USER_MEDAL_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜，您已获得系统管理员颁发的\"{medal_name}\"勋章', '[ {site} ]Congratulations, you have been issued by the system administrator {medal_name} ', '[ {site} ]恭喜，您已獲得系統管理員頒發的\"{medal_name}\"勳章');
INSERT INTO `ts_lang` VALUES ('1579', 'NOTIFY_WEIBA_POST_SET_CONTENT', 'PUBLIC', '0', '恭喜，您的帖子《{post_name}》已被管理员设置为“{typename}”，详情请点击：{post_url}', 'Congratulations, your posts {post_name} \"has been set by the administrator as\" {typename} For details, please click: {post_url}', '恭喜，您的帖子《{post_name}》已被管理員設置為“{typename}”，詳情請點擊：{post_url}');
INSERT INTO `ts_lang` VALUES ('1580', 'NOTIFY_WEIBA_POST_SET_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜，您的帖子《{post_name}》已被管理员设置为“{typename}”', '[ {site} ]Congratulations, your posts {post_name} \"has been set by the administrator as\" {typename}', '[ {site} ]恭喜，您的帖子《{post_name}》已被管理員設置為“{typename}”');
INSERT INTO `ts_lang` VALUES ('1581', 'NOTIFY_CHANNEL_ADD_FEED_CONTENT', 'PUBLIC', '0', '恭喜，您的微博“{feed_content}”已被系统管理员推荐至“{channel_name}”频道，详情请点击：{feed_url}', 'Congratulations your microblogging {feed_content} is recommended to \"{channel_name} channel by the system administrator, details click: {feed_url}', '恭喜，您的微博“{feed_content}”已被系統管理員推薦至“{channel_name}”頻道，詳情請點擊：{feed_url}');
INSERT INTO `ts_lang` VALUES ('1582', 'NOTIFY_CHANNEL_ADD_FEED_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜，您的微博“{feed_content}”已被系统管理员推荐至“{channel_name}”频道', '[ {site} ]Congratulations your microblogging {feed_content} is recommended to \"{channel_name} channel by the system administrator', '[ {site} ]恭喜，您的微博“{feed_content}”已被系統管理員推薦至“{channel_name}”頻道');
INSERT INTO `ts_lang` VALUES ('1583', 'NOTIFY_CHANNEL_AUDIT_CONTENT', 'PUBLIC', '0', '恭喜，您的微博“{feed_content}”已被系统管理员推荐至“{channel_name}”频道，详情请点击：{feed_url}', 'Congratulations your microblogging {feed_content} is recommended to \"{channel_name} channel by the system administrator, details click: {feed_url}', '恭喜，您的微博“{feed_content}”已被系統管理員推薦至“{channel_name}”頻道，詳情請點擊：{feed_url}');
INSERT INTO `ts_lang` VALUES ('1584', 'NOTIFY_CHANNEL_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]恭喜，您的微博“{feed_content}”已被系统管理员推荐至{channel_name}频道', '[ {site} ]Congratulations your microblogging {feed_content} is recommended to \"{channel_name} channel by the system administrator', '[ {site} ]恭喜，您的微博“{feed_content}”已被系統管理員推薦至{channel_name}頻道');
INSERT INTO `ts_lang` VALUES ('1585', 'PUBLIC_ATTACH_CONFIG', 'PUBLIC', '0', '附件配置', 'Upload configuration', '附件配置');
INSERT INTO `ts_lang` VALUES ('1586', 'PUBLIC_CLOUDIMAGE_CONFIG', 'PUBLIC', '0', '图片云配置', 'Photo upload configuration', '图片云配置');
INSERT INTO `ts_lang` VALUES ('1587', 'PUBLIC_CLOUDATTACH_CONFIG', 'PUBLIC', '0', '附件云配置', 'Attachment upload configuration', '附件云配置');
INSERT INTO `ts_lang` VALUES ('1589', 'NOTIFY_FEED_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有新微博需要审核，快去后台处理吧', '', '');
INSERT INTO `ts_lang` VALUES ('1590', 'NOTIFY_FEED_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有新微博需要审核，快去后台处理吧', '', '');
INSERT INTO `ts_lang` VALUES ('1591', 'NOTIFY_COMMENT_AUDIT_CONTENT', 'PUBLIC', '0', '系统提醒：有新评论需要审核，快去后台处理吧', 'Reminder: new comments system needs to be reviewed, go and background processing', '系統提醒：有新評論和意見需要審核，快去後台處理吧');
INSERT INTO `ts_lang` VALUES ('1592', 'NOTIFY_COMMENT_AUDIT_TITLE', 'PUBLIC', '0', '[ {site} ]系统提醒：有新评论需要审核，快去后台处理吧', '', '');
INSERT INTO `ts_lang` VALUES ('1593', 'NOTIFY_REGISTER_WELCOME_CONTENT', 'PUBLIC', '0', '欢迎注册！', '', '');
INSERT INTO `ts_lang` VALUES ('1594', 'NOTIFY_REGISTER_WELCOME_TITLE', 'PUBLIC', '0', '欢迎注册', '', '');
INSERT INTO `ts_lang` VALUES ('1600', 'NOTIFY_TIPOFF_DEAL_TITLE', 'TIPOFF', '0', '您有爆料需要处理', '', '');
INSERT INTO `ts_lang` VALUES ('1599', 'NOTIFY_TIPOFF_DEAL_CONTENT', 'TIPOFF', '0', '您有爆料需要处理，<a href=\"{source_url}\">点此查看</a>', '', '');
INSERT INTO `ts_lang` VALUES ('1597', 'NOTIFY_TIPOFF_BONUS_CONTENT', 'PUBLIC', '0', '您好，您发布的爆料{url}已获得由 {bonus_uname} 给你发的奖金{bonus}元，请您及时领奖', '', '');
INSERT INTO `ts_lang` VALUES ('1598', 'NOTIFY_TIPOFF_BONUS_TITLE', 'PUBLIC', '0', '恭喜您，您刚获得由  某某用户 给你发的奖金  100元 ，请您及时领奖', '', '');
INSERT INTO `ts_lang` VALUES ('1601', 'NOTIFY_TIPOFF_BONUS_CONTENT', 'TIPOFF', '0', '您好，您发布的爆料{url}已获得由 {bonus_uname} 给你发的奖金{bonus}元，请您及时领奖', '', '');
INSERT INTO `ts_lang` VALUES ('1602', 'NOTIFY_TIPOFF_BONUS_TITLE', 'TIPOFF', '0', '您好，您发布的爆料已获奖，请等待，请等待管理员发奖', '', '');
INSERT INTO `ts_lang` VALUES ('1603', 'PUBLIC_WORK_EXPERIENCE', 'PUBLIC', '0', '工作经验', 'Work experience', '工作經驗');
INSERT INTO `ts_lang` VALUES ('1604', 'PUBLIC_PROJECT_EXPERIENCE', 'PUBLIC', '0', '项目经验', 'Project experience', '項目經驗');
INSERT INTO `ts_lang` VALUES ('1605', 'PUBLIC_DIRECT_MANAGER', 'PUBLIC', '0', '直属管理者', 'Immediate managers', '直屬管理者');
INSERT INTO `ts_lang` VALUES ('1640', 'UPLOADING', 'PUBLIC', '0', '上传中...', 'Uploading..', '上傳中...');
INSERT INTO `ts_lang` VALUES ('1641', 'INPUT_FRIEND_NAME', 'PUBLIC', '0', '好友名称', 'Friend Name', '好友名稱');
INSERT INTO `ts_lang` VALUES ('1642', 'FRIEND_NOEXIST', 'PUBLIC', '0', '好友不存在', 'Friends does not exist', '好友不存在');
INSERT INTO `ts_lang` VALUES ('1643', 'YOU_CHOSE_ID', 'PUBLIC', '0', '请选择好友', 'Please select friends', '請選擇好友列表');
INSERT INTO `ts_lang` VALUES ('1644', 'VIEW_ALL_FRIENDS', 'PUBLIC', '0', '好友列表', 'Buddy List', '好友列表');
INSERT INTO `ts_lang` VALUES ('1645', 'DATA_LOADING', 'PUBLIC', '0', '数据加载中..', 'Loading ..', '數據加載中');
INSERT INTO `ts_lang` VALUES ('1646', 'CLOSE', 'PUBLIC', '0', '关闭', 'Close', '關閉');
INSERT INTO `ts_lang` VALUES ('1647', 'PRE_PAGE', 'PUBLIC', '0', '上一页', 'Previous', '上一頁');
INSERT INTO `ts_lang` VALUES ('1648', 'NEXT_PAGE', 'PUBLIC', '0', '下一页', 'Next', '下一頁');
INSERT INTO `ts_lang` VALUES ('1649', 'MUST_CHOOSE', 'PUBLIC', '0', '必须选择用户', 'Must select the user', '必須選擇用戶');
INSERT INTO `ts_lang` VALUES ('1650', 'DELETE', 'PUBLIC', '0', '删除', 'Delete', '刪除');
INSERT INTO `ts_lang` VALUES ('1651', 'UPLOAD_ATTACH_LOADING', 'PUBLIC', '0', '附件上传中', 'Uploading', '附件上傳中');
INSERT INTO `ts_lang` VALUES ('1652', 'PUBLIC_APPNAME_TIPOFF', 'PUBLIC', '0', '爆料', 'Tipoff', '爆料');
INSERT INTO `ts_lang` VALUES ('1653', 'PUBLIC_APPNAME_PHOTO', 'PUBLIC', '0', '相册', 'Photo', '相冊');
INSERT INTO `ts_lang` VALUES ('1654', 'PUBLIC_APPNAME_POSTER', 'PUBLIC', '0', '招贴', 'Poster', '招貼');
INSERT INTO `ts_lang` VALUES ('1655', 'PUBLIC_APPNAME_GIFT', 'PUBLIC', '0', '礼物', 'Gift', '禮物');
INSERT INTO `ts_lang` VALUES ('1656', 'PUBLIC_APPNAME_DEVELOP', 'PUBLIC', '0', '开发者', 'Develop', '開發者');
INSERT INTO `ts_lang` VALUES ('1657', 'PUBLIC_APPNAME_SITELIST', 'PUBLIC', '0', '站点列表', 'Site List', '站點列表');
INSERT INTO `ts_lang` VALUES ('1658', 'PUBLIC_APPNAME_SURVEY', 'PUBLIC', '0', '问卷调查', 'Surver', '問卷調查');
INSERT INTO `ts_lang` VALUES ('1659', 'PUBLIC_APPNAME_DOCUMENT', 'PUBLIC', '0', '文档', 'Document', '文檔');
INSERT INTO `ts_lang` VALUES ('1660', 'PUBLIC_APPNAME_PEOPLE', 'PUBLIC', '0', '找人', 'People', '找人');
INSERT INTO `ts_lang` VALUES ('1661', 'PUBLIC_APPNAME_CLUB', 'PUBLIC', '0', '俱乐部', 'Club', '俱樂部');
INSERT INTO `ts_lang` VALUES ('1663', '_CLASS_NOT_EXIST_', 'PUBLIC', '0', '找不到该类', 'Can not find the class', '找不到該類');
INSERT INTO `ts_lang` VALUES ('1664', 'CACHE', 'PUBLIC', '0', '缓存', 'Cache', '緩存');
INSERT INTO `ts_lang` VALUES ('1665', '_TEMPLATE_NOT_EXIST_', 'PUBLIC', '0', '模板不存在', 'Template does not exist', '模板不存在');
INSERT INTO `ts_lang` VALUES ('1666', 'LOGS', 'PUBLIC', '0', '日志列表', 'Logs', '日誌列表');
INSERT INTO `ts_lang` VALUES ('1667', 'PERMISSION', 'PUBLIC', '0', '权限', 'PERMISSION', '權限');
INSERT INTO `ts_lang` VALUES ('1668', 'XDATA', 'PUBLIC', '0', '数据缓存', 'Data', '數據控制類');
INSERT INTO `ts_lang` VALUES ('1669', 'APP', 'PUBLIC', '0', '应用', 'APP', '應用');
INSERT INTO `ts_lang` VALUES ('1670', 'EXPRESSION', 'PUBLIC', '0', '表达式', 'Expression', '表達試');
INSERT INTO `ts_lang` VALUES ('1671', 'FEEDTOPIC', 'PUBLIC', '0', '微博话题', 'Topic', '微博話題');
INSERT INTO `ts_lang` VALUES ('1672', 'USER', 'PUBLIC', '0', '用户', 'User', '用戶');
INSERT INTO `ts_lang` VALUES ('1673', 'CLOUDIMAGE', 'PUBLIC', '0', '云图片', 'CloudImage', '云圖片');
INSERT INTO `ts_lang` VALUES ('1674', 'ATTACH', 'PUBLIC', '0', '附件', 'Attach', '附件');
INSERT INTO `ts_lang` VALUES ('1675', 'LANG', 'PUBLIC', '0', '语言', 'Language', '語言');
INSERT INTO `ts_lang` VALUES ('1676', 'USERGROUPLINK', 'PUBLIC', '0', '用户组关联', 'GroupLink', '用戶組關聯');
INSERT INTO `ts_lang` VALUES ('1677', 'USERGROUP', 'PUBLIC', '0', '用户组', 'UserGroup', '用戶組');
INSERT INTO `ts_lang` VALUES ('1678', 'NAVI', 'PUBLIC', '0', '导航', 'Navigation', '導航');
INSERT INTO `ts_lang` VALUES ('1679', 'PASSPORT', 'PUBLIC', '0', '认证', 'Passport', '認證');
INSERT INTO `ts_lang` VALUES ('1680', 'REGISTER', 'PUBLIC', '0', '注册', 'Register', '註冊');
INSERT INTO `ts_lang` VALUES ('1681', 'USERAPP', 'PUBLIC', '0', '用户应用', 'User App', '用戶應用');
INSERT INTO `ts_lang` VALUES ('1682', 'USERDATA', 'PUBLIC', '0', '用户数据', 'User Data', '用戶數據');
INSERT INTO `ts_lang` VALUES ('1683', 'CREDIT', 'PUBLIC', '0', '账户信息', 'Credit', '帳戶信息');
INSERT INTO `ts_lang` VALUES ('1684', '_METHOD_NOT_EXIST_', 'PUBLIC', '0', '找不到方法', 'Unable to find methods', '找不到方法');
INSERT INTO `ts_lang` VALUES ('1685', '_OPERATION_SUCCESS_', 'PUBLIC', '0', '操作成功', 'Operation is successful', '操作成功');
INSERT INTO `ts_lang` VALUES ('1686', '_OPERATION_FAIL_', 'PUBLIC', '0', '操作失败', 'Operation fails', '操作失敗');
INSERT INTO `ts_lang` VALUES ('1687', 'ADDONDATA', 'PUBLIC', '0', '插件数据', 'Plug-in data', '插件數據');
INSERT INTO `ts_lang` VALUES ('1688', '_CACHE_TYPE_INVALID_', 'PUBLIC', '0', '缓存类型验证', 'Cache type validation', '緩存類型驗證');
INSERT INTO `ts_lang` VALUES ('1689', 'ADDON', 'PUBLIC', '0', '插件', 'Plug-in', '插件');
INSERT INTO `ts_lang` VALUES ('1690', '_NOT_SUPPERT_', 'PUBLIC', '0', '不支持', 'Does not support', '不支持');
INSERT INTO `ts_lang` VALUES ('1691', '_EXPRESS_ERROR_', 'PUBLIC', '0', '表达式错误', 'Expression error', '表達式錯誤');
INSERT INTO `ts_lang` VALUES ('1692', '_FIELDS_IS_EMPTYNOT_', 'PUBLIC', '0', '字段不能为空', 'The field can not be empty', '字段不能為空');
INSERT INTO `ts_lang` VALUES ('1693', '_MODEL_NOT_EXIST_', 'PUBLIC', '0', 'Model不存在', 'Model does not exist\r\n', 'Model不存在');
INSERT INTO `ts_lang` VALUES ('1694', '_DATA_TYPE_INVALID_', 'PUBLIC', '0', '数据类型验证', 'Data type validation', '數據類型驗證');
INSERT INTO `ts_lang` VALUES ('1695', '_OPERATION_WRONG_', 'PUBLIC', '0', '操作中..', 'Operation', '操作中..');
INSERT INTO `ts_lang` VALUES ('1696', '_TOKEN_ERROR_', 'PUBLIC', '0', '邀请码错误', 'Invitation code error', '邀請碼錯誤');
INSERT INTO `ts_lang` VALUES ('1697', '_MODULE_NOT_EXIST_', 'PUBLIC', '0', '模块不存在', 'Module does not exist', '模塊不存在');
INSERT INTO `ts_lang` VALUES ('1698', '_CACHE_WRITE_ERROR_', 'PUBLIC', '0', '缓存写入错误', 'Cache write error', '緩存寫入錯誤');
INSERT INTO `ts_lang` VALUES ('1699', '_TAGLIB_NOT_EXIST_', 'PUBLIC', '0', '标签类不存在', 'Label class does not exist\r\nLabel class does not exist\r\nLabel class does not exist', '標籤類不存在');
INSERT INTO `ts_lang` VALUES ('1700', '_XML_TAG_ERROR_', 'PUBLIC', '0', '标签错误', 'Label error', '標籤錯誤');
INSERT INTO `ts_lang` VALUES ('1809', 'PUBLIC_APPNAME_CHANNEL', 'PUBLIC', '0', '频道', 'Channel', '頻道');
INSERT INTO `ts_lang` VALUES ('1806', 'NOTIFY_GIFT_SEND_TITLE', 'PUBLIC', '0', '', '', '');
INSERT INTO `ts_lang` VALUES ('1706', 'PAGE', 'PAGE', '0', '页面', 'page', '/opt/lampp/htdocs/ts3/apps/page/Lib/Action/AdminAction.class.php');
INSERT INTO `ts_lang` VALUES ('1707', 'CANVAS', 'PAGE', '0', '画布', 'canvas', '/opt/lampp/htdocs/ts3/apps/page/Lib/Action/AdminAction.class.php');
INSERT INTO `ts_lang` VALUES ('1710', 'CATEGORYTREE', 'PEOPLE', '0', '分类', 'category', '/opt/lampp/htdocs/ts3/apps/people/Lib/Action/IndexAction.class.php');
INSERT INTO `ts_lang` VALUES ('1711', 'USERCATEGORY', 'PEOPLE', '0', '用户分类', 'usercategory', '/opt/lampp/htdocs/ts3/apps/people/Lib/Action/IndexAction.class.php');
INSERT INTO `ts_lang` VALUES ('1712', 'FOLLOW', 'PEOPLE', '0', '关注', 'follow', '/opt/lampp/htdocs/ts3/apps/people/Lib/Model/PeopleModel.class.php');
INSERT INTO `ts_lang` VALUES ('1713', 'AREA', 'PEOPLE', '0', '地区', 'area', '/opt/lampp/htdocs/ts3/apps/people/Lib/Widget/FilterWidget/FilterWidget.class.php');
INSERT INTO `ts_lang` VALUES ('1714', 'FEEDBACK', 'PUBLIC', '0', '信息反馈', 'Feedback', '信息反饋');
INSERT INTO `ts_lang` VALUES ('1715', 'NOTIFY', 'PUBLIC', '0', '通知', 'Notice', '通知');
INSERT INTO `ts_lang` VALUES ('1716', 'INVITE', 'PUBLIC', '0', '邀请', 'Invite', '邀請');
INSERT INTO `ts_lang` VALUES ('1717', 'PINYIN', 'PUBLIC', '0', '拼音', 'Pinyin', '拼音');
INSERT INTO `ts_lang` VALUES ('1718', 'TAG', 'PUBLIC', '0', '标签', 'Label', '標籤');
INSERT INTO `ts_lang` VALUES ('1719', 'RELATEDUSER', 'PUBLIC', '0', '可能感兴趣的人', 'May be of interest to people', '可能感興趣的人');
INSERT INTO `ts_lang` VALUES ('1720', 'FOLLOW', 'PUBLIC', '0', '关注', 'Attention', '關注');
INSERT INTO `ts_lang` VALUES ('1721', 'FEED', 'PUBLIC', '0', '微博', 'Microblogging', '微博');
INSERT INTO `ts_lang` VALUES ('1722', 'USERPROFILE', 'PUBLIC', '0', '资料配置', 'Data configuration', '資料配置');
INSERT INTO `ts_lang` VALUES ('1723', 'USERCATEGORY', 'PUBLIC', '0', '用户标签', 'User tags', '用戶標籤');
INSERT INTO `ts_lang` VALUES ('1724', 'AVATAR', 'PUBLIC', '0', '头像设置', 'Avatar is set', '頭像設置');
INSERT INTO `ts_lang` VALUES ('1725', 'USERPRIVACY', 'PUBLIC', '0', '隐私配置', 'Privacy configuration', '隱私配置');
INSERT INTO `ts_lang` VALUES ('1726', 'SOURCE', 'PUBLIC', '0', '源', 'Source', '源');
INSERT INTO `ts_lang` VALUES ('1727', 'SHARE', 'PUBLIC', '0', '转发', 'Forwarding', '轉發');
INSERT INTO `ts_lang` VALUES ('1728', 'MAIL', 'PUBLIC', '0', '邮箱', 'Mailbox', '郵箱');
INSERT INTO `ts_lang` VALUES ('1729', 'TIPS', 'PUBLIC', '0', '赞', 'Praise', '贊');
INSERT INTO `ts_lang` VALUES ('1730', 'CATEGORYTREE', 'PUBLIC', '0', '分类树', 'Classification tree', '分類樹');
INSERT INTO `ts_lang` VALUES ('1731', 'FRIEND', 'PUBLIC', '0', '朋友', 'Friend', '朋友');
INSERT INTO `ts_lang` VALUES ('1732', 'COLLECTION', 'PUBLIC', '0', '收藏', 'Favorite', '收藏');
INSERT INTO `ts_lang` VALUES ('1733', 'FOLLOWGROUP', 'PUBLIC', '0', '我关注的', 'We ', '我們關注一個');
INSERT INTO `ts_lang` VALUES ('1734', 'ATME', 'PUBLIC', '0', '@Me', '@Me', '@我');
INSERT INTO `ts_lang` VALUES ('1735', 'USERCOUNT', 'PUBLIC', '0', '用户统计', 'User Statistics', '用戶統計');
INSERT INTO `ts_lang` VALUES ('1736', 'MYOP', 'PUBLIC', '0', '漫游', 'Roam', '漫遊');
INSERT INTO `ts_lang` VALUES ('1737', 'MANAGE_APPS', 'PUBLIC', '0', '应用管理', 'Application Management', '應用管理');
INSERT INTO `ts_lang` VALUES ('1738', 'DEPARTMENT', 'PUBLIC', '0', '部门', 'Department', '部門');
INSERT INTO `ts_lang` VALUES ('1739', 'XARTICLE', 'PUBLIC', '0', '公告', 'Announcement', '公告');
INSERT INTO `ts_lang` VALUES ('1740', 'TEMPLATE', 'PUBLIC', '0', '模板', 'Template', '模板');
INSERT INTO `ts_lang` VALUES ('1741', 'TASK', 'PUBLIC', '0', '任务', 'Task', '任務');
INSERT INTO `ts_lang` VALUES ('1742', 'TASKCUSTOM', 'PUBLIC', '0', '副本任务', 'A copy of the task', '副本任務');
INSERT INTO `ts_lang` VALUES ('1743', 'COMMENT', 'PUBLIC', '0', '评论', 'Comment', '評論');
INSERT INTO `ts_lang` VALUES ('1744', 'MESSAGE', 'PUBLIC', '0', '私信', 'Personal communication', '個人通信');
INSERT INTO `ts_lang` VALUES ('1745', 'SEARCH', 'PUBLIC', '0', '搜索', 'Search', '搜索');
INSERT INTO `ts_lang` VALUES ('1748', 'PUBLIC_CENTSUCCESS', 'PUBLIC', '0', '回复成功', 'Back to success', '回復成功');
INSERT INTO `ts_lang` VALUES ('1749', 'PUBLIC_WEIBA', 'WEIBA', '0', '微吧', 'weiba', '/opt/lampp/htdocs/ts3/apps/weiba/Lib/Model/WeibaPostModel.class.php');
INSERT INTO `ts_lang` VALUES ('1753', 'CHANNELFOLLOW', 'CHANNEL', '0', '关注的频道', 'Focuses on the channel', '/opt/lampp/htdocs/ts3/apps/channel/Lib/Widget/TopMenuWidget/TopMenuWidget.class.php');
INSERT INTO `ts_lang` VALUES ('1757', 'SSC_CLOSE', 'ADMIN', '0', '关闭', 'close', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/ConfigAction.class.php');
INSERT INTO `ts_lang` VALUES ('1758', 'AREA', 'ADMIN', '0', '地区', 'area', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/ConfigAction.class.php');
INSERT INTO `ts_lang` VALUES ('1759', 'WIDGET', 'ADMIN', '0', '插件', 'widget', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/ConfigAction.class.php');
INSERT INTO `ts_lang` VALUES ('1760', 'ONLINE', 'ADMIN', '0', '在线', 'online', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/UserAction.class.php');
INSERT INTO `ts_lang` VALUES ('1761', 'USEROFFICIAL', 'ADMIN', '0', '官方用户', 'Official user', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/UserAction.class.php');
INSERT INTO `ts_lang` VALUES ('1762', 'MEDAL', 'ADMIN', '0', '勋章', 'medal', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/MedalAction.class.php');
INSERT INTO `ts_lang` VALUES ('1763', 'SYSTEM', 'ADMIN', '0', '系统配置', 'system', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/HomeAction.class.php');
INSERT INTO `ts_lang` VALUES ('1764', 'SSC_DELETE_FAIL', 'ADMIN', '0', '删除失败', 'Delete failed', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/HomeAction.class.php');
INSERT INTO `ts_lang` VALUES ('1765', 'SCHEDULE', 'ADMIN', '0', '目录', 'schedule', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/HomeAction.class.php');
INSERT INTO `ts_lang` VALUES ('1766', 'DENOUNCE', 'ADMIN', '0', '公告', 'denounce', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/ContentAction.class.php');
INSERT INTO `ts_lang` VALUES ('1767', 'FEEDTOPICADMIN', 'ADMIN', '0', '置顶', 'Micro-blog top', '/opt/lampp/htdocs/ts3/apps/admin/Lib/Action/ContentAction.class.php');
INSERT INTO `ts_lang` VALUES ('1768', 'EXTENDS', 'ADMIN', '0', '退出', 'quit', '/opt/lampp/htdocs/ts3/apps/admin/Tpl/default/Index/index.html');
INSERT INTO `ts_lang` VALUES ('1769', 'INDEX', 'ADMIN', '0', '首页', 'index', '/opt/lampp/htdocs/ts3/apps/admin/Tpl/default/Index/index.html');
INSERT INTO `ts_lang` VALUES ('1772', 'CLOUDATTACH', 'PUBLIC', '0', '云附件', 'CloudAttach', '云附件');
INSERT INTO `ts_lang` VALUES ('1773', 'USERBLACKLIST', 'PUBLIC', '0', '用户黑名单', 'User blacklist', '用戶黑名單');
INSERT INTO `ts_lang` VALUES ('1774', 'SHARE_TO_MY_WEIBO', 'PUBLIC', '0', '分享到我的微博', 'Share my weibo', '分享到我的微博');
INSERT INTO `ts_lang` VALUES ('1775', 'PUBLISH', 'PUBLIC', '0', '发布', 'Publish', '發佈');
INSERT INTO `ts_lang` VALUES ('1776', 'DENOUNCE', 'PUBLIC', '0', '举报', 'Denounce', '舉報');
INSERT INTO `ts_lang` VALUES ('1777', 'WIDGET', 'PUBLIC', '0', '组件', 'Widget', '組件');
INSERT INTO `ts_lang` VALUES ('1778', 'PUBLIC_SELECT_AREA', 'PUBLIC', '0', '选择地区', 'Select a region', '選擇地區');
INSERT INTO `ts_lang` VALUES ('1779', 'AREA', 'PUBLIC', '0', '地区', 'Area', '地區');
INSERT INTO `ts_lang` VALUES ('1780', 'PUBLIC_PLEASE_GROUP_NAME', 'PUBLIC', '0', '请填写分组名称', 'Please fill in a name for the group', '請填寫分組名稱');
INSERT INTO `ts_lang` VALUES ('1781', 'PUBLIC_EDIT_GROUPNAME', 'PUBLIC', '0', '编辑分组名称', 'Edit the group name', '編輯分組名稱');
INSERT INTO `ts_lang` VALUES ('1782', 'PUBLIC_QUXIAO', 'PUBLIC', '0', '取消', 'Cancel', '取消');
INSERT INTO `ts_lang` VALUES ('1783', 'PUBLIC_PRMARK_EDIT_ERROR', 'PUBLIC', '0', '编辑错误', 'Editorial errors', '編輯錯誤');
INSERT INTO `ts_lang` VALUES ('1784', 'PUBLIC_SET_PRMARK', 'PUBLIC', '0', '设置备注', 'Setting Remarks', '設置備註');
INSERT INTO `ts_lang` VALUES ('1785', 'LEFT', 'PUBLIC', '1', '左', 'Left', '左');
INSERT INTO `ts_lang` VALUES ('1786', 'FORMATBLOCK', 'PUBLIC', '1', '格式', 'Format', '格式');
INSERT INTO `ts_lang` VALUES ('1787', 'FONTNAME', 'PUBLIC', '1', '字体名称', 'Font Name', '字體名稱');
INSERT INTO `ts_lang` VALUES ('1788', 'FONTSIZE', 'PUBLIC', '1', '字体大小', 'Font Size', '字體大小');
INSERT INTO `ts_lang` VALUES ('1789', 'SPACESTYLE', 'PUBLIC', '0', '空间风格', 'Space style', '空間風格');
INSERT INTO `ts_lang` VALUES ('1790', 'SAVEPUBLISHCONFIG', 'PUBLIC', '0', '保存配置', 'Save the configuration', '保存配置');
INSERT INTO `ts_lang` VALUES ('1791', 'INVITERECORD', 'PUBLIC', '0', '邀请记录', 'Record of the invitation', '邀請記錄');
INSERT INTO `ts_lang` VALUES ('1792', 'SPACE', 'PUBLIC', '0', '空间', 'Space', '空間');
INSERT INTO `ts_lang` VALUES ('1793', 'LOGIN_PAGE_FEED', 'PUBLIC', '0', '动态', 'Dynamic', '動態');
INSERT INTO `ts_lang` VALUES ('1794', 'LOGIN_PAGE_USER', 'PUBLIC', '0', '用户', 'User', '用戶');
INSERT INTO `ts_lang` VALUES ('1795', 'LOGIN_PAGE_BANNER_DELETE', 'PUBLIC', '0', '删除banner图', 'Remove the banner Figure', '刪除banner圖');
INSERT INTO `ts_lang` VALUES ('1796', 'LOGIN_PAGE_BANNER_ADDIMAGE', 'PUBLIC', '0', '添加banner图', 'Add banner Figure', '添加banner圖');
INSERT INTO `ts_lang` VALUES ('1797', 'DIV', 'PUBLIC', '0', '层', 'DIV', '層');
INSERT INTO `ts_lang` VALUES ('1798', 'ADSPACE', 'PUBLIC', '0', '广告', 'Advertising', '廣告');
INSERT INTO `ts_lang` VALUES ('1799', 'VALIDATION', 'PUBLIC', '0', '验证', 'Validation', '驗證');
INSERT INTO `ts_lang` VALUES ('1800', 'VALIATION', 'PUBLIC', '0', '验证', 'Valiation', '驗證');
INSERT INTO `ts_lang` VALUES ('1801', 'NOTIFY_SITELIST_APPROVE_CONTENT', 'PUBLIC', '0', '您提交的站点已通过，快去后台看看', '', '');
INSERT INTO `ts_lang` VALUES ('1802', 'NOTIFY_SITELIST_APPROVE_TITLE', 'PUBLIC', '0', '', '', '');
INSERT INTO `ts_lang` VALUES ('1803', 'NOTIFY_SITELIST_DENY_CONTENT', 'PUBLIC', '0', '您提交的站点被拒绝', '', '');
INSERT INTO `ts_lang` VALUES ('1804', 'NOTIFY_SITELIST_DENY_TITLE', 'PUBLIC', '0', '', '', '');
INSERT INTO `ts_lang` VALUES ('1883', 'DIGG_MESSAGE_CONTENT', 'PUBLIC', '0', '{user} 赞了你的微博:<br/>{content} <a href=\"{sourceurl}&digg=1\" target=\'_blank\'>去看看>></a>', '', '');
INSERT INTO `ts_lang` VALUES ('1884', 'DIGG_MESSAGE_TITLE', 'PUBLIC', '0', '{user} 赞了你的微博', '', '');
