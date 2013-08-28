<?php
/**
 +------------------------------------------------------------------------------
 * ThinkPHP慣例配置檔案
 * 該檔案請不要修改，如果要覆蓋慣例配置的值，可在項目配置檔案中設定和慣例不符的配置項
 * 配置名稱大小寫任意，系統會統一轉換成小寫
 * 所有配置參數都可以在生效前動態改變
 +------------------------------------------------------------------------------
 * @category Think
 * @package  Common
 * @author   liu21st <liu21st@gmail.com>
 * @version  $Id$
 +------------------------------------------------------------------------------
 */
if (!defined('THINK_PATH')) exit();
return  array(

    /* 插件是否開啟 */
    'APP_DEBUG'             =>  false,      // 是否開啟偵錯模式
    'DEVELOP_MODE'          =>  true,      // 開發模式，開啟後臺表單的配置
    'APP_PLUGIN_ON'         =>  true,       // 是否開啟插件機制
    'DEFAULT_APPS'          =>  array('public','admin','home','page','wap'), //默認核心應用

    /* 項目設定 */
    'SITE_LOGO'             =>  'image/logo.png', //默認的站點logo
    'DEFAULT_GROUP_ID'      =>  3, //默認註冊之後的使用者組ID
    'APP_AUTOLOAD_PATH'     =>  CORE_LIB_PATH.','.CORE_LIB_PATH.'/addons/,'.CORE_LIB_PATH.'/Taglib/,'.ADDON_PATH.'/model/',

    /* Cookie設定 */
    'COOKIE_EXPIRE'         =>  3600,       // Coodie有效期
    'COOKIE_DOMAIN'         =>  '',         // Cookie有效域名
    'COOKIE_PATH'           =>  '/',            // Cookie路徑
    'COOKIE_PREFIX'         =>  'TSV3_',        // Cookie字首 避免衝突

    /* 默認設定 */
    'DEFAULT_APP'           =>  'public',       // 默認項目名稱，@表示當前項目
    'DEFAULT_MODULE'        =>  'Index',        // 默認模組名稱
    'DEFAULT_ACTION'        =>  'index',        // 默認操作名稱
    'DEFAULT_CHARSET'       =>  'utf-8',        // 默認輸出編碼
    'DEFAULT_TIMEZONE'      =>  'Asia/Taipei',          // 默認時區
    'DEFAULT_LANG'          =>  'zh-tw',        // 默認語言
    'DEFAULT_LANG_TYPE'     =>  array('zh-tw','zh-cn','en'), //默認支援的語言類型

    /* 資料庫設定 */
    'DB_TYPE'               =>  'mysql',     // 資料庫類型
    'DB_HOST'               =>  'localhost', // 伺服器地址
    'DB_NAME'               =>  '',          // 資料庫名
    'DB_USER'               =>  'root',      // 使用者名
    'DB_PWD'                =>  '',          // 密碼
    'DB_PORT'               =>  3306,        // 埠
    'DB_PREFIX'             =>  'ts_',    // 資料庫表字首
    'DB_SUFFIX'             =>  '',          // 資料庫表字尾
    'DB_FIELDTYPE_CHECK'    =>  false,       // 是否進行欄位類型檢查
    'DB_FIELDS_CACHE'       =>  true,        // 啟用欄位快取
    'DB_CHARSET'            =>  'utf8',      // 資料庫編碼默認採用utf8
    'DB_DEPLOY_TYPE'        =>  0,           // 資料庫部署方式:0 集中式(單一伺服器),1 分散式(主從伺服器)
    'DB_RW_SEPARATE'        =>  false,       // 資料庫讀寫是否分離 主從式有效

    /* 資料快取設定 */
    'DATA_CACHE_TIME'       =>  null,           // 資料快取有效期
    'DATA_CACHE_COMPRESS'   =>  true,           // 資料快取是否壓縮快取
    'DATA_CACHE_CHECK'      =>  true,           // 資料快取是否校驗快取
    'DATA_CACHE_TYPE'       =>  'File',         // 資料快取類型,支援:File|Memcache
    'DATA_CACHE_PATH'       =>  CORE_RUN_PATH.'/datacache', // 快取路徑設定 (僅對File方式快取有效)
    'DATA_CACHE_SUBDIR'     =>  true,           // 使用子目錄快取 (自動根據快取標識的雜湊創建子目錄)
    'DATA_PATH_LEVEL'       =>  2,              // 子目錄快取級別
    'DATA_CACHE_PREFIX'     =>  'TS_',          //快取字首
    'F_CACHE_PATH'          =>  CORE_RUN_PATH.'/filecache', //F函數快取的檔案目錄
    'MEMCACHE_HOST'         => '127.0.0.1:11211',   //Memcache 默認的主機，支援簡單的多機集群   如: "127.0.0.1:11211,127.0.0.2:11211"

    /* 錯誤設定 */
    'ERROR_MESSAGE'         =>  '您瀏覽的頁面暫時發生了錯誤！請稍後再試～',//錯誤顯示資訊,非偵錯模式有效
    'ERROR_PAGE'            =>  '', // 錯誤定向頁面

    /* 語言設定 */
    'LANG_SWITCH_ON'        =>  true,   // 默認關閉多語言包功能
    'LANG_AUTO_DETECT'      =>  true,   // 自動偵測語言 開啟多語言功能後有效

    /* 日誌設定 */
    'LOG_RECORD'            =>  false,   // 默認不記錄日誌
    'LOG_FILE_SIZE'         =>  2097152,    // 日志檔案大小限制
    'LOG_RECORD_LEVEL'      =>  array('EMERG','ALERT','CRIT','ERR'),// 允許記錄的日誌級別

    /* SESSION設定 */
    'SESSION_AUTO_START'    =>  true,    // 是否自動開啟Session
    'SESSION_NAME'          => 'PHPSESSION', // Session名稱
    //'SESSION_PATH'        => '',      // Session儲存路徑
    //'SESSION_CALLBACK'    => '',      // Session 物件反序列化時候的回撥函數

    /* 運行時間設定 */
    'SHOW_RUN_TIME'         =>  false,   // 運行時間顯示
    'SHOW_ADV_TIME'         =>  false,   // 顯示詳細的運行時間
    'SHOW_DB_TIMES'         =>  false,   // 顯示資料庫查詢和寫入次數
    'SHOW_CACHE_TIMES'      =>  false,   // 顯示快取操作次數
    'SHOW_USE_MEM'          =>  false,   // 顯示內存開銷
    'SHOW_PAGE_TRACE'       =>  false,   // 顯示頁面Trace資訊 由Trace檔案定義和Action操作賦值
    'SHOW_ERROR_MSG'        =>  true,    // 顯示錯誤資訊

    /* 模板引擎設定 */
    'TMPL_DENY_FUNC_LIST'   =>  'echo,exit',    // 模板引擎禁用函數
    'TMPL_PARSE_STRING'     =>  '',          // 模板引擎要自動替換的字元串，必須是陣列形式。
    'TMPL_L_DELIM'          =>  '{',            // 模板引擎普通標籤開始標記
    'TMPL_R_DELIM'          =>  '}',            // 模板引擎普通標簽結束標記
    'TMPL_VAR_IDENTIFY'     =>  'array',     // 模板變數識別。留空自動判斷,參數為'obj'則表示物件
    'TMPL_STRIP_SPACE'      =>  true,       // 是否去除模板檔案裡面的html空格與換行
    'TMPL_CACHE_ON'         =>  true,        // 是否開啟模板編譯快取,設為false則每次都會重新編譯
    'TMPL_CACHE_TIME'       =>  -1,         // 模板快取有效期 -1 為永久，(以數字為值，單位:秒)
    'TMPL_ACTION_ERROR'     =>  'Public:success', // 默認錯誤跳轉對應的模板檔案
    'TMPL_ACTION_SUCCESS'   =>  'Public:success', // 默認成功跳轉對應的模板檔案
    'TMPL_TRACE_FILE'       =>  THINK_PATH.'/Tpl/PageTrace.tpl.php',     // 頁面Trace的模板檔案
    'TMPL_EXCEPTION_FILE'   =>  THINK_PATH.'/Tpl/ThinkException.tpl.php',// 異常頁面的模板檔案
    'TMPL_FILE_DEPR'        =>  '/', //模板檔案MODULE_NAME與ACTION_NAME之間的分割符，只對項目分組部署有效
    'TMPL_CACHE_PATH'       =>  CORE_RUN_PATH.'/tplcache/', //模板檔案快取路徑

    /* Think模板引擎標籤庫相關設定 */
    'TAGLIB_BEGIN'          =>  '<',  // 標籤庫標籤開始標記
    'TAGLIB_END'            =>  '>',  // 標籤庫標簽結束標記
    'TAGLIB_LOAD'           =>  true, // 是否使用內建標籤庫之外的其它標籤庫，默認自動檢測
    'TAGLIB_BUILD_IN'       =>  'input,business', // 內建標籤庫名稱(標籤使用不必指定標籤庫名稱),以逗號分隔
    'TAGLIB_PRE_LOAD'       =>  'html',   // 需要額外載入的標籤庫(須指定標籤庫名稱)，多個以逗號分隔
    'TAG_NESTED_LEVEL'      =>  3,    // 標籤巢狀級別
    'TAG_EXTEND_PARSE'      =>  '',   // 指定對普通標籤進行擴展定義和解析的函數名稱。

    /* 表單令牌驗證 */
    'TOKEN_ON'              =>  false,      // 開啟令牌驗證
    'TOKEN_NAME'            =>  '__hash__', // 令牌驗證的表單隱藏欄位名稱
    'TOKEN_TYPE'            =>  'md5',      // 令牌驗證雜湊規則

    /* URL設定 */
    'URL_CASE_INSENSITIVE'  =>  true,   // URL地址是否不區分大小寫
    'URL_ROUTER_ON'         =>  false,   // 是否開啟URL路由
    'URL_DISPATCH_ON'       =>  false,  // 是否啟用Dispatcher
    'URL_HTML_SUFFIX'       =>  '',     // URL偽靜態字尾設定

    /* 系統變數名稱設定 */
    'VAR_APP'               =>  'app',  // 默認應用獲取變數
    'VAR_MODULE'            =>  'mod',  // 默認模組獲取變數
    'VAR_ACTION'            =>  'act',  // 默認操作獲取變數
    'VAR_ROUTER'            =>  'r',    // 默認路由獲取變數
    'VAR_PAGE'              =>  'p',    // 默認分頁跳轉變數
    'VAR_TEMPLATE'          =>  't',    // 默認模板切換變數
    'VAR_LANGUAGE'          =>  'l',    // 默認語言切換變數
    'VAR_AJAX_SUBMIT'       =>  'ajax', // 默認的AJAX提交變數
    'VAR_PATHINFO'          =>  's',    // PATHINFO 相容模式獲取變數

    /* URL設定 */
    'TS_UPDATE_URL'         =>  'http://up.thinksns.com/v3',   // thinksns v3版本線上升級的伺服器地址
    'TS_UPDATE_SITE'         => 'http://demo.thinksns.com/t3',   // thinksns v3版本線上升級的服務環境地址
);
?>
