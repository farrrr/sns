<?php
error_reporting(0); //設定錯誤級別
define('SITE_PATH',dirname(__FILE__));

//獲取參數
$url    =   $_GET['url'];

//初步驗證合法性
$result =   preg_match('/^[a-zA-Z0-9]+$/',$url,$match);
if(!$result)    die('error01,wrong parameters!');

$url_id =   getDncodeNum($url);
if($url_id<=0) die('error02,wrong parameters!');

//載入資料庫查詢類
require(SITE_PATH.'/addons/library/SimpleDB.class.php');

//引入資料庫配置
$db_config  =   require(SITE_PATH.'/config/config.inc.php');
$db =   new SimpleDB($db_config);

//查詢短網址記錄 - 有條件的，此處可用memcached做一層快取
$result =   $db->query("SELECT * FROM ".$db_config['DB_PREFIX']."url where id='".$url_id."' limit 1");

//狀態為1的 跳轉到正確地址
if($result[0]['status']==1){
    header('location:'.$result[0]['url']);
}else{
    die('error03,wrong parameters!');
}

//本地化 URL解碼方法 將字母轉換成數字ID
function getDncodeNum($num){
    //編碼符號集一定要與加密的相同
    $index = "HwpGAejoUOPr6DbKBlvRILmsq4z7X3TCtky8NVd5iWE0ga2MchSZxfn1Y9JQuF";
    $out    = 0;
    $len    = strlen($num) - 1;
    for ($t = 0; $t <= $len; $t++) {
        $out = $out + strpos($index, substr($num, $t, 1 )) * pow(62, $len - $t);
    }
    //去除偏移量
    $out    -= 10000;   //初始值設定成10000
    return intval($out);
}
?>
