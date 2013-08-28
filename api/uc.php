<?php
error_reporting(0);

// ThinkSNS系統根目錄路徑
define('SITE_PATH', dirname(dirname(__FILE__)));

// ThinkSNS與UCenter整合的必要方法
require_once SITE_PATH.'/api/uc_client/common.php';

define('IN_DISCUZ', TRUE);

define('UC_CLIENT_VERSION', '1.5.0');   //note UCenter 版本標識
define('UC_CLIENT_RELEASE', '20081031');

define('API_DELETEUSER', 1);            //note 使用者刪除 API 介面開關
define('API_RENAMEUSER', 1);            //note 使用者改名 API 介面開關
define('API_GETTAG', 0);                //note 獲取標籤 API 介面開關
define('API_SYNLOGIN', 1);              //note 同步登入 API 介面開關
define('API_SYNLOGOUT', 1);             //note 同步登出 API 介面開關
define('API_UPDATEPW', 1);              //note 更改使用者密碼 開關
define('API_UPDATEBADWORDS', 0);        //note 更新關鍵字列表 開關
define('API_UPDATEHOSTS', 1);           //note 更新域名解析快取 開關
define('API_UPDATEAPPS', 1);            //note 更新應用列表 開關
define('API_UPDATECLIENT', 1);          //note 更新客戶端快取 開關
define('API_UPDATECREDIT', 0);          //note 更新使用者積分 開關
define('API_GETCREDITSETTINGS', 0);     //note 向 UCenter 提供積分設定 開關
define('API_GETCREDIT', 0);             //note 獲取使用者的某項積分 開關
define('API_UPDATECREDITSETTINGS', 0);  //note 更新應用積分設定 開關

define('API_RETURN_SUCCEED', '1');
define('API_RETURN_FAILED', '-1');
define('API_RETURN_FORBIDDEN', '-2');

define('DISCUZ_ROOT', SITE_PATH.'/api/');
define('THINK_PATH', SITE_PATH.'/core/ThinkPHP');

set_magic_quotes_runtime(0);
defined('MAGIC_QUOTES_GPC') || define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());

require_once SITE_PATH.'/config/uc_config.inc.php';

//載入ThinkSNS配置
$tsconfig1 = require_once SITE_PATH.'/config/config.inc.php';
$tsconfig2 = require_once SITE_PATH.'/core/OpenSociax/convention.php';
$tsconfig = array_merge($tsconfig2,$tsconfig1);

$cookiepre = $tsconfig['COOKIE_PREFIX'];
$cookiedomain = $tsconfig['COOKIE_DOMAIN'];
$cookiepath = $tsconfig['COOKIE_PATH'];

$_DCACHE = $get = $post = array();

$code = @$_GET['code'];
parse_str(_authcode($code, 'DECODE', UC_KEY), $get);
if(MAGIC_QUOTES_GPC) {
    $get = _stripslashes($get);
}
//時間戳驗證
$timestamp = time();
// if($timestamp - $get['time'] > 3600) {
//  exit('Authracation has expiried');
// }
if(empty($get)) {
    exit('Invalid Request');
}
$action = $get['action'];
require_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
$post = xml_unserialize(file_get_contents('php://input'));

// 偵錯用-寫log
// $log_message = "============================ \n "
//              .date('Y-m-d H:i:s')." \n ".$_SERVER['REQUEST_URI']
//              ." \n ".var_export($get,true)." \n ".var_export($post,true)." \n ";

// $log_file = DISCUZ_ROOT."/uc_log.txt";
// $result = error_log($log_message,3,$log_file);

if(UC_SYNC==0){
    exit(API_RETURN_FAILED);
}

if(in_array($get['action'], array('test','face','deleteuser', 'renameuser', 'synlogin', 'synlogout', 'updatepw', 'updatebadwords', 'updatehosts', 'updateapps', 'updateclient', 'updatecredit', 'getcreditsettings', 'updatecreditsettings'))) {
    require_once DISCUZ_ROOT.'./uc_client/lib/db.class.php';
    //UC的資料庫連線
    $GLOBALS['db'] = new ucclient_db;
    $GLOBALS['db']->connect(UC_DBHOST, UC_DBUSER, UC_DBPW, UC_DBNAME, UC_DBCONNECT, true, UC_DBCHARSET);
    $GLOBALS['tablepre'] = UC_DBTABLEPRE;
    //TS的資料庫連線
    $GLOBALS['tsdb'] = new ucclient_db;
    $GLOBALS['tsdb']->connect($tsconfig['DB_HOST'], $tsconfig['DB_USER'], $tsconfig['DB_PWD'], $tsconfig['DB_NAME'], UC_DBCONNECT, true, $tsconfig['DB_CHARSET']);
    define('TS_DBTABLEPRE', $tsconfig['DB_PREFIX']);
    $GLOBALS['tstablepre'] = TS_DBTABLEPRE;
    //執行UC動作
    $uc_note = new uc_note();
    exit($uc_note->$get['action']($get, $post));
} else {
    exit(API_RETURN_FAILED);
}
