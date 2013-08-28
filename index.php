<?php
error_reporting(E_ERROR);

/** ///偵錯、找錯時請去掉///前空格
ini_set('display_errors',true);
error_reporting(E_ALL);
set_time_limit(0);
**/

//安裝檢查開始：如果您已安裝過ThinkSNS，可以刪除本段程式碼
if(is_dir('install') && !file_exists('install/install.lock')){
    header("Content-type: text/html; charset=utf-8");
    die ("<div style='border:2px solid green; background:#f1f1f1; padding:20px;margin:20px;width:800px;font-weight:bold;color:green;text-align:center;'>"
        ."<h1>您尚未安裝ThinkSNS系統，<a href='install/install.php'>請點選進入安裝頁面</a></h1>"
        ."</div> <br /><br />");
}
//安裝檢查結束

//網站根路徑設定
define('SITE_PATH', dirname(__FILE__));
// define ( "GZIP_ENABLE", function_exists ( 'ob_gzhandler' ) );
// ob_start ( GZIP_ENABLE ? 'ob_gzhandler' : null );

//載入核心檔案
require(SITE_PATH.'/core/core.php');

if(isset($_GET['debug'])){
    C('APP_DEBUG', true);
    C('SHOW_RUN_TIME', true);
    C('SHOW_ADV_TIME', true);
    C('SHOW_DB_TIMES', true);
    C('SHOW_CACHE_TIMES', true);
    C('SHOW_USE_MEM', true);
    C('LOG_RECORD', true);
    C('LOG_RECORD_LEVEL',  array (
        'EMERG',
        'ALERT',
        'CRIT',
        'ERR',
        'SQL'
    ));
}

$time_run_start = microtime(TRUE);
$mem_run_start = memory_get_usage();

//例項化一個網站應用例項
$App = new App();
$App->run();

$mem_run_end = memory_get_usage();
$time_run_end = microtime(TRUE);

if(C('APP_DEBUG')){
    //資料庫查詢資訊
    echo '<div align="left">';
    //快取使用情況
    //print_r(Cache::$log);
    echo '<hr>';
    echo ' Memories: '."<br/>";
    echo 'ToTal: ',number_format(($mem_run_end - $mem_include_start)/1024),'k',"<br/>";
    echo 'Include:',number_format(($mem_run_start - $mem_include_start)/1024),'k',"<br/>";
    echo 'Run:',number_format(($mem_run_end - $mem_run_start)/1024),'k<br/><hr/>';
    echo 'Time:<br/>';
    echo 'ToTal: ',$time_run_end - $time_include_start,"s<br/>";
    echo 'Include:',$time_run_start - $time_include_start,'s',"<br/>";
    echo 'Run:',$time_run_end - $time_run_start,'s<br/><br/>';
    echo '<hr>';
    $log = Log::$log;
    foreach($log as $l){
        $l = explode('SQL:', $l);
        $l = $l[1];
        $l = str_replace(array('RunTime:', 's SQL ='), ' ', $l);
        echo $l.'<br/>';
    }
    $files = get_included_files();
    dump($files);
    echo '</div>';
}
