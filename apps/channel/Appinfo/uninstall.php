<?php
/**
 * 解除安裝頻道應用
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if(!defined('SITE_PATH')) exit();
// 資料庫表字首
$db_prefix = C('DB_PREFIX');
// 解除安裝資料SQL陣列
$sql = array(
    // Channel資料
    "DROP TABLE IF EXISTS `{$db_prefix}channel_category`;",
    "DROP TABLE IF EXISTS `{$db_prefix}channel`;",
    "DROP TABLE IF EXISTS `{$db_prefix}channel_follow`;",
    "DELETE FROM `{$db_prefix}lang` WHERE `key` = 'PUBLIC_APPNAME_CHANNEL';",
);
// 刪除分類檔案
model('CategoryTree')->setTable('channel_category')->remakeTreeCache();
// 執行SQL
foreach($sql as $v) {
    D('')->execute($v);
}
