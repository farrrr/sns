<?php
/*
 * OpenSociax 核心流程控制檔案
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version ST1.0
 */

if (!defined('SITE_PATH')) exit();

// 重塑Session (必須位於session_start()之前)
if (isset($_POST['PHPSESSID'])) {
    session_id($_POST['PHPSESSID']);
}

/*  全局配置  */
session_start();        //TODO 臨時先放這裡

//記錄開始運行時間
$GLOBALS['_beginTime'] = microtime(TRUE);

// 記錄內存初始使用
define('MEMORY_LIMIT_ON',function_exists('memory_get_usage'));

//參數處理 If already slashed, strip.
if (get_magic_quotes_gpc()) {
    $_GET    = stripslashes_deep( $_GET    );
    $_POST   = stripslashes_deep( $_POST   );
    $_COOKIE = stripslashes_deep( $_COOKIE );
}

//參數處理 控制不合規格的參數
check_gpc($_GET);
check_gpc($_POST);
//check_gpc($_COOKIE);

//解析關鍵參數 todo:參數過濾 preg_match("/^([a-zA-Z_\/0-9]+)$/i", $ts, $url);
$_REQUEST   =   array_merge($_GET,$_POST);

//載入全局配置
tsconfig(include CORE_LIB_PATH.'/convention.php');
tsconfig(include CONF_PATH.'/config.inc.php');
tsconfig(include CONF_PATH.'/access.inc.php');
tsconfig(include CONF_PATH.'/router.inc.php');

if(!isset($_REQUEST['app']) && !isset($_REQUEST['mod']) && !isset($_REQUEST['act'])){
    $ts['_app'] = 'public';
    $ts['_mod'] = 'Passport';
    $ts['_act'] = 'login';
}else{
    $ts['_app'] = isset($_REQUEST['app']) && !empty($_REQUEST['app'])?$_REQUEST['app']:tsconfig('DEFAULT_APP');
    $ts['_mod'] = isset($_REQUEST['mod']) && !empty($_REQUEST['mod'])?$_REQUEST['mod']:tsconfig('DEFAULT_MODULE');
    $ts['_act'] = isset($_REQUEST['act']) && !empty($_REQUEST['act'])?$_REQUEST['act']:tsconfig('DEFAULT_ACTION');
}
$ts['_widget_appname'] = isset($_REQUEST['widget_appname']) && !empty($_REQUEST['widget_appname'])  ? $_REQUEST['widget_appname'] :'';

//APP的常量定義
tsdefine('APP_NAME'     , $ts['_app']);
tsdefine('TRUE_APPNAME',!empty($ts['_widget_appname']) ? $ts['_widget_appname']:APP_NAME);
tsdefine('MODULE_NAME'  , $ts['_mod']);
tsdefine('ACTION_NAME'  , $ts['_act']);

//新增一些CODE常量.用於簡化判斷操作
tsdefine('MODULE_CODE'  , $ts['_app'].'/'.$ts['_mod']);
tsdefine('ACTION_CODE'  , $ts['_app'].'/'.$ts['_mod'].'/'.$ts['_act']);
tsdefine('APP_RUN_PATH' , CORE_RUN_PATH.'/~'.TRUE_APPNAME);

//載入擴展函數庫
tsload(CORE_LIB_PATH.'/functions.inc.php');

//如果有UC的配置載入配置
tsload(CONF_PATH.'/uc_config.inc.php');
tsdefine('UC_SYNC', 0);
//tsload(CORE_LIB_PATH.'/extend.inc.php');

//安全防護功能
if(file_exists(DATA_PATH.'/iswaf/config.php')){
    require(DATA_PATH.'/iswaf/config.php');
    define('iswaf_database',DATA_PATH.'/iswaf/');
    require(ADDON_PATH.'/library/iswaf/iswaf.php');
}


/*  應用配置  */
//載入應用配置
tsdefine('APP_PATH'         , APPS_PATH.'/'.TRUE_APPNAME);
tsdefine('APP_URL'          , SITE_URL.'/apps/'.TRUE_APPNAME);
tsdefine('APP_COMMON_PATH'  , APP_PATH.'/Common');
tsdefine('APP_CONFIG_PATH'  , APP_PATH.'/Conf');
tsdefine('APP_LIB_PATH'     , APP_PATH.'/Lib');
tsdefine('APP_ACTION_PATH'  , APP_LIB_PATH.'/Action');
tsdefine('APP_MODEL_PATH'   , APP_LIB_PATH.'/Model');
tsdefine('APP_WIDGET_PATH'  , APP_LIB_PATH.'/Widget');
tsdefine('APP_API_PATH'     , APP_LIB_PATH.'/Api');

//定義語言快取檔案路徑常量
tsdefine('LANG_PATH', DATA_PATH.'/lang');
tsdefine('LANG_URL', DATA_URL.'/lang');

//默認風格包名稱
if(C('THEME_NAME')){
    tsdefine('THEME_NAME'       , C('THEME_NAME'));
}else{
    tsdefine('THEME_NAME'       , 'stv1');
}


//默認靜態檔案、模版檔案目錄
tsdefine('THEME_PATH'       , ADDON_PATH.'/theme/'.THEME_NAME);
tsdefine('THEME_URL'        , ADDON_URL.'/theme/'.THEME_NAME);
tsdefine('THEME_PUBLIC_PATH', THEME_PATH.'/_static');
tsdefine('THEME_PUBLIC_URL' , THEME_URL.'/_static');
tsdefine('APP_PUBLIC_PATH'  , APP_PATH.'/_static');
tsdefine('APP_PUBLIC_URL'   , APP_URL.'/_static');
tsdefine('APP_TPL_PATH'     , APP_PATH.'/Tpl/default');
tsdefine('APP_TPL_URL'      , APP_URL.'/Tpl/default');

tsdefine('CANVAS_PATH'      , SITE_PATH.'/config/canvas/');

//設定語言包
setLang();

//載入應用函數庫
if(file_exists(APP_COMMON_PATH.'/common.php')) {
    tsload(APP_COMMON_PATH.'/common.php');
}

//合併應用配置
if(file_exists(APP_CONFIG_PATH.'/config.php')) {
    tsconfig(include APP_CONFIG_PATH.'/config.php');
}

//根據應用配置重定義以下常量
if(C('THEME_NAME')){
    tsdefine('THEME_NAME',      C('THEME_NAME'));
}

//根據應用配置重定義以下常量
if(C('APP_TPL_PATH')){
    tsdefine('APP_TPL_PATH',    C('APP_TPL_PATH'));
}

//如果是部署模式、則如下定義
if(C('DEPLOY_STATIC')){
    tsdefine('THEME_PUBLIC_URL' , PUBLIC_URL.'/'.THEME_NAME);
    tsdefine('APP_PUBLIC_URL'   , THEME_PUBLIC_URL.'/'.TRUE_APPNAME);
}

//載入插件鉤子
//$ts['_config']['hooks']   =   array('app_init'=>array('check_access','check_access2'));

//根據應用配置資訊. 重置一些常量
tsload(CORE_LIB_PATH.'/Think.class.php');
tsload(CORE_LIB_PATH.'/App.class.php');
//tsload(CORE_LIB_PATH.'/Action.class.php');
//tsload(CORE_LIB_PATH.'/Model.class.php');
//tsload(CORE_LIB_PATH.'/DB.class.php');
//tsload(CORE_LIB_PATH.'/Widget.class.php');
//tsload(CORE_LIB_PATH.'/Api.class.php');
//tsload(CORE_LIB_PATH.'/Page.class.php');
?>
