<?php
/*
 * OpenSociax 核心入口檔案
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version ST1.0
 */

if (!defined('SITE_PATH')) exit();

ini_set('magic_quotes_runtime', 0);
date_default_timezone_set('UTC');

$time_include_start = microtime(TRUE);
$mem_include_start = memory_get_usage();

//設定全局變數ts
$ts['_debug']   =   false;      //偵錯模式
$ts['_define']  =   array();    //全局常量
$ts['_config']  =   array();    //全局配置
$ts['_access']  =   array();    //訪問配置
$ts['_router']  =   array();    //路由配置

tsdefine('IS_CGI',substr(PHP_SAPI, 0, 3)=='cgi' ? 1 : 0 );
tsdefine('IS_WIN',strstr(PHP_OS, 'WIN') ? 1 : 0 );
tsdefine('IS_HTTPS',0);

// 當前檔名
if(!defined('_PHP_FILE_')) {
    if(IS_CGI) {
        // CGI/FASTCGI模式下
        $_temp  = explode('.php',$_SERVER["PHP_SELF"]);
        define('_PHP_FILE_', rtrim(str_replace($_SERVER["HTTP_HOST"],'',$_temp[0].'.php'),'/'));
    }else {
        define('_PHP_FILE_', rtrim($_SERVER["SCRIPT_NAME"],'/'));
    }
}

// 網站URL根目錄
if(!defined('__ROOT__')) {
    $_root = dirname(_PHP_FILE_);
    define('__ROOT__',  (($_root=='/' || $_root=='\\')?'':rtrim($_root,'/')));
}

//基本常量定義
tsdefine('CORE_PATH'    ,   dirname(__FILE__));
tsdefine('THINK_PATH'   ,   CORE_PATH.'/ThinkPHP');

tsdefine('SITE_DOMAIN'  ,   strip_tags($_SERVER['HTTP_HOST']));
tsdefine('SITE_URL'     ,   (IS_HTTPS?'https:':'http:').'//'.SITE_DOMAIN.__ROOT__);

tsdefine('CONF_PATH'    ,   SITE_PATH.'/config');

tsdefine('APPS_PATH'    ,   SITE_PATH.'/apps');
tsdefine('APPS_URL'     ,   SITE_URL.'/apps');  # 應用內部圖示 等元素

tsdefine('ADDON_PATH'   ,   SITE_PATH.'/addons');
tsdefine('ADDON_URL'    ,   SITE_URL.'/addons');

tsdefine('DATA_PATH'    ,   SITE_PATH.'/data');
tsdefine('DATA_URL'     ,   SITE_URL.'/data');

tsdefine('UPLOAD_PATH'  ,   DATA_PATH.'/upload');
tsdefine('UPLOAD_URL'   ,   SITE_URL.'/data/upload');

tsdefine('PUBLIC_PATH'  ,   SITE_PATH.'/public');
tsdefine('PUBLIC_URL'   ,   SITE_URL.'/public');

//載入核心模式: 默認是OpenSociax. 也支援ThinkPHP
if(!defined('CORE_MODE'))   define('CORE_MODE','OpenSociax');

tsdefine('CORE_LIB_PATH'    ,   CORE_PATH.'/'.CORE_MODE);
tsdefine('CORE_RUN_PATH'    ,   SITE_PATH.'/_runtime');
tsdefine('LOG_PATH'         ,   CORE_RUN_PATH.'/logs/');

//註冊AUTOLOAD方法
if ( function_exists('spl_autoload_register') )
    spl_autoload_register('tsautoload');

//載入核心運行時檔案
if(file_exists(CORE_PATH.'/'.CORE_MODE.'Runtime.php') && !$ts['_debug']){
    include CORE_PATH.'/'.CORE_MODE.'Runtime.php';
}else{
    include CORE_LIB_PATH.'/'.CORE_MODE.'.php';
}

/* 核心方法 */

/**
 * 載入檔案 去重\快取.
 * @param string $filename 載入的檔名
 * @return boolean
 */
function tsload($filename) {

    static $_importFiles = array(); //已載入的檔案列表快取

    $key = strtolower($filename);

    if (!isset($_importFiles[$key])) {

        if (is_file($filename)) {

            require_once $filename;
            $_importFiles[$key] = true;
        } elseif(file_exists(CORE_LIB_PATH.'/'.$filename.'.class.php')) {

            require_once CORE_LIB_PATH.'/'.$filename.'.class.php';
            $_importFiles[$key] = true;
        } else {

            $_importFiles[$key] = false;
        }
    }
    return $_importFiles[$key];
}

/**
 * 系統自動載入函數
 * @param string $classname 物件類名
 * @return void
 */
function tsautoload($classname) {

    // 檢查是否存在別名定義
    if(tsload($classname)) return ;

    // 自動載入當前項目的Actioon類和Model類
    if(substr($classname,-5)=="Model") {
        if(!tsload(ADDON_PATH.'/model/'.$classname.'.class.php'))
            tsload(APP_LIB_PATH.'/Model/'.$classname.'.class.php');

    }elseif(substr($classname,-6)=="Action"){
        tsload(APP_LIB_PATH.'/Action/'.$classname.'.class.php');

    }elseif(substr($classname,-6)=="Widget"){
        if(!tsload(ADDON_PATH.'/widget/'.$classname.'.class.php'))
            tsload(APP_LIB_PATH.'/Widget/'.$classname.'.class.php');

    }elseif(substr($classname,-6)=="Addons"){
        if(!tsload(ADDON_PATH.'/plugin/'.$classname.'.class.php'))
            tsload(APP_LIB_PATH.'/Plugin/'.$classname.'.class.php');

    }else{
        $paths = array(ADDON_PATH.'/library');
        foreach ($paths as $path){
            if(tsload($path.'/'.$classname.'.class.php'))
                // 如果載入類成功則返回
                return ;
        }
    }
    return ;
}

/**
 * 定義常量,判斷是否未定義.
 *
 * @param string $name 常量名
 * @param string $value 常量值
 * @return string $str 返回常量的值
 */
function tsdefine($name,$value) {
    global $ts;
    //定義未定義的常量
    if(!defined($name)){
        //定義新常量
        define($name,$value);
    }else{
        //返回已定義的值
        $value = constant($name);
    }
    //快取已定義常量列表
    $ts['_define'][$name] = $value;
    return $value;
}

/**
 * 返回16位md5值
 *
 * @param string $str 字元串
 * @return string $str 返回16位的字元串
 */
function tsmd5($str) {
    return substr(md5($str),8,16);
}

/**
 * 載入配置 修改自ThinkPHP:C函數 為了不與tp衝突
 *
 * @param string $name 配置名/檔名.
 * @param string|array|object $value 配置賦值
 * @return void|null
 */
function tsconfig($name=null,$value=null) {
    global $ts;
    // 無參數時獲取所有
    if(empty($name)) return $ts['_config'];
    // 優先執行設定獲取或賦值
    if (is_string($name))
    {
        if (!strpos($name,'.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($ts['_config'][$name])? $ts['_config'][$name] : null;
            $ts['_config'][$name] = $value;
            return;
        }
        // 二維陣列設定和獲取支援
        $name = explode('.',$name);
        $name[0]   = strtolower($name[0]);
        if (is_null($value))
            return isset($ts['_config'][$name[0]][$name[1]]) ? $ts['_config'][$name[0]][$name[1]] : null;
        $ts['_config'][$name[0]][$name[1]] = $value;
        return;
    }
    // 批量設定
    if(is_array($name))
        return $ts['_config'] = array_merge((array)$ts['_config'],array_change_key_case($name));
    return null;// 避免非法參數
}

/**
 * 執行鉤子方法
 *
 * @param string $name 鉤子方法名.
 * @param array $params 鉤子參數陣列.
 * @return array|string Stripped array (or string in the callback).
 */
function tshook($name,$params=array()) {
    global $ts;
    $hooks  =   $ts['_config']['hooks'][$name];
    if($hooks) {
        foreach ($hooks as $call){
            if(is_callable($call))
                $result = call_user_func_array($call,$params);
        }
        return $result;
    }
    return false;
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 * @param array|string $value The array or string to be striped.
 * @return array|string Stripped array (or string in the callback).
 */
function stripslashes_deep($value) {
    if ( is_array($value) ) {
        $value = array_map('stripslashes_deep', $value);
    } elseif ( is_object($value) ) {
        $vars = get_object_vars( $value );
        foreach ($vars as $key=>$data) {
            $value->{$key} = stripslashes_deep( $data );
        }
    } else {
        $value = stripslashes($value);
    }
    return $value;
}

/**
 * GPC參數過濾
 * @param array|string $value The array or string to be striped.
 * @return array|string Stripped array (or string in the callback).
 */
function check_gpc($value=array()) {
    if(!is_array($value)) return;
    foreach ($value as $key=>$data) {
        //對get、post的key值做限制,只允許數字、字母、及部分符號_-[]#@~
        if(!preg_match('/^[a-zA-z0-9,_\-#!@~\[\]]+$/i',$key)){
            die('wrong_parameter: not safe get/post/cookie key.');
        }
        //如果key值為app\mod\act,value只允許數字、字母下劃線
        if( ($key=='app' || $key=='mod' || $key=='act') && !empty($data) ){
            if(!preg_match('/^[a-zA-z0-9_]+$/i',$data)){
                die('wrong_parameter: not safe app/mod/act value.');
            }
        }
    }
}

//全站靜態快取,替換之前每個model類中使用的靜態快取
//類似於s和f函數的使用
function static_cache($cache_id,$value=null,$clean = false){
    static $cacheHash = array();
    if($clean){ //清空快取 其實是清不了的 程式執行結束纔會自動清理
        unset($cacheHash);
        $cacheHash = array(0);
        return $cacheHash;
    }
    if(empty($cache_id)){
        return false;
    }
    if($value === null){
        //獲取快取資料
        return isset($cacheHash[$cache_id]) ? $cacheHash[$cache_id] : false;
    }else{
        //設定快取資料
        $cacheHash[$cache_id] = $value;
        return $cacheHash[$cache_id];
    }
}
?>
