<?php
/**
 * 安裝頻道應用
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
if(!defined('SITE_PATH')) exit();
// 標頭檔案設定
header('Content-Type:text/html;charset=utf-8;');
// 安裝SQL檔案
$sql_file = APPS_PATH.'/page/Appinfo/install.sql';
// 執行sql檔案
$res = D('')->executeSqlFile($sql_file);
// 錯誤處理
if(!empty($res)) {
    echo $res['error_code'];
    echo '<br />';
    echo $res['error_sql'];
    // 清除已匯入的資料
    include_once(APPS_PATH.'/page/Appinfo/uninstall.php');
    exit;
}
