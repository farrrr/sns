<?php
if (!defined('SITE_PATH')) exit();

header('Content-Type: text/html; charset=utf-8');

$sql_file  = APPS_PATH.'/weiba/Appinfo/install.sql';
//執行sql檔案
$res = D('')->executeSqlFile($sql_file);
if(!empty($res)){//錯誤
    echo $res['error_code'];
    echo '<br />';
    echo $res['error_sql'];
    //清除已匯入的資料
    include_once(APPS_PATH.'/weiba/uninstall.php');
    exit;
}
