<?php
/**
 * 1. 核心升級 + 應用升級
 * 2. 誇版本升級 (即: 資料庫的誇版本升級)
 */
error_reporting(E_ERROR);
header('Content-type: text/html; charset=UTF-8');

/*** 增加統計程式碼 ***/

define('SITE_PATH','');
$_REQUEST = array_merge($_GET,$_POST);
$sitehost = h($_REQUEST['site']);
$siteip = h($_SERVER['REMOTE_ADDR']);
$version = h($_REQUEST['version']);

$config = require 'config.inc.php';
$dbconfig = array();

$dbconfig['DB_TYPE'] = $config['DB_TYPE'];
$dbconfig['DB_HOST'] = $config['DB_HOST'];
$dbconfig['DB_NAME'] = $config['DB_NAME'];
$dbconfig['DB_USER'] = $config['DB_USER'];
$dbconfig['DB_PWD']  = $config['DB_PWD'];
$dbconfig['DB_PORT'] = $config['DB_PORT'];
$dbconfig['DB_PREFIX'] = $config['DB_PREFIX'];
$dbconfig['DB_CHARSET'] = $config['DB_CHARSET'];

//例項化資料庫
$db = new Db($dbconfig);
$result = $db->query("SELECT * FROM ts_onlive_site WHERE siteip='".$siteip."';");
if(!$result) {
    $add_result = $db->execute('INSERT INTO ts_onlive_site (`sitehost`,`siteip`,`sitedata`,`version`) VALUES ("'.$sitehost.'","'.$siteip.'","", "'.$version.'");');
}

$result = array();
// 模擬資料
$result['info'] = 'ThinkSNS V3 預覽版於2013年1月21日釋出，<a href="http://demo.thinksns.com/t3/">檢視詳情</a>';

// 輸出結果
switch(strtolower($_REQUEST['output_format'])) {
case 'json':
    echo json_encode($result);
    break;
case 'php':
    dump($result);
    break;
default:
    echo serialize($result);
}

exit();

/**
 * 獲取應用的最新版本的資訊 (包括"核心")
 *
 * 注意: 本函數不檢查版本號的有效性, 請在呼叫本函數前檢查.
 *
 * @param string $app             應用名
 * @param string $now_version     應用當前版本的版本號
 * @param string $lastest_version 應用最新版本的版本號
 * @return array
 * <code>
 * array(
 *     'error'                  => '0',     // 無錯誤
 *     'error_message'          => '',      // 無錯誤資訊
 *     'has_update'             => int,     // 0: 無更新 1:有更新
 *     'version'                => string,  // 版本號 [僅核心(Core)時有效]
 *     'current_version_number' => int,     // 當前版本的版本號
 *     'lastest_version_number' => int,     // 最新版本的版本號
 *     'download_url'           => string,  // 下載地址
 *     'changelog'              => text,    // ChangeLog
 * )
 * </code>
 */
function getLastestVersionInfo($app, $current_version, $lastest_version)
{
    if ($current_version >= $lastest_version)
        return array(
            'error'                     => '0',
            'error_message'             => '',
            'has_update'                => '0',
            'current_version_number'    => $current_version,
            'lastest_version_number'    => $lastest_version,);

    global $versions;
    // 下載地址
    $var_download_url = $app . '_download_url_' . $lastest_version;
    global $$var_download_url;
    $info = array(
        'error'                     => '0',
        'error_message'             => '',
        'has_update'                => '1',
        'current_version_number'    => $current_version,
        'lastest_version_number'    => $lastest_version,
        'download_url'              => $$var_download_url);
    // 版本名稱 (核心升級時必須, 如: ThinkSNS 2.1 Build 10992)
    if ($app == 'core') {
        $var_core_version = 'core_version_' . $lastest_version;
        global $$var_core_version;
        $info['lastest_version']  = $$var_core_version;
    }
    // changelog
    $info['changelog'] = '';
    foreach ($versions[$app] as $version_no) {
        if ($current_version >= $version_no)
            continue ;

        $var_changelog = $app . '_changelog_' . $version_no;
        global $$var_changelog;
        $info['changelog'] .= $$var_changelog;
    }
    // 版本列表
    $info['version_number_list'] = $versions[$app];

    return $info;
}

// 瀏覽器友好的輸出
function dump($var, $echo=true,$label=null, $strict=true)
{
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre style="text-align:left">'.$label.htmlspecialchars($output,ENT_QUOTES).'</pre>';
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre style="text-align:left">'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}


function h($text) {
    //過濾標籤
    $text   =   nl2br($text);
    $text   =   htmlspecialchars_decode($text);
    $text   =   strip_tags($text);
    $text   =   str_ireplace(array("\r\t","\n\l","\r","\n","\t","\l","'",'&nbsp;','&amp;'),'',$text);
    $text   =   str_ireplace(array(chr('0001'),chr('0002'),chr('0003'),chr('0004'),chr('0005'),chr('0006'),chr('0007'),chr('0008')),'',$text);
    $text   =   str_ireplace(array(chr('0009'),chr('0010'),chr('0011'),chr('0012'),chr('0013'),chr('0014'),chr('0015'),chr('0016')),'',$text);
    $text   =   str_ireplace(array(chr('0017'),chr('0018'),chr('0019'),chr('0020'),chr('0021'),chr('0022'),chr('0023'),chr('0024')),'',$text);
    $text   =   str_ireplace(array(chr('0025'),chr('0026'),chr('0027'),chr('0028'),chr('0029'),chr('0030'),chr('0031'),chr('0032')),'',$text);
    return $text;
}

/**
 +------------------------------------------------------------------------------
 * ThinkPHP 簡潔模式資料庫中間層實現類
 * 只支援mysql
 +------------------------------------------------------------------------------
 */
class Db
{

    static private $_instance   = null;
    // 是否顯示偵錯資訊 如果啟用會在日志檔案記錄sql語句
    public $debug               = false;
    // 是否使用永久連線
    protected $pconnect         = false;
    // 當前SQL指令
    protected $queryStr         = '';
    // 最後插入ID
    protected $lastInsID        = null;
    // 返回或者影響記錄數
    protected $numRows          = 0;
    // 返回欄位數
    protected $numCols          = 0;
    // 事務指令數
    protected $transTimes       = 0;
    // 錯誤資訊
    protected $error            = '';
    // 當前連線ID
    protected $linkID           =   null;
    // 當前查詢ID
    protected $queryID          = null;
    // 是否已經連線資料庫
    protected $connected        = false;
    // 資料庫連線參數配置
    protected $config           = '';
    // SQL 執行時間記錄
    protected $beginTime;
    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $config 資料庫配置陣列
     +----------------------------------------------------------
     */
    public function __construct($config=''){
        if ( !extension_loaded('mysql') ) {
            throw_exception('not support mysql');
        }
        $this->config   =   $this->parseConfig($config);
    }

    /**
     +----------------------------------------------------------
     * 連線資料庫方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function connect() {
        if(!$this->connected) {
            $config =   $this->config;
            // 處理不帶埠號的socket連線情況
            $host = $config['hostname'].($config['hostport']?":{$config['hostport']}":'');
            if($this->pconnect) {
                $this->linkID = mysql_pconnect( $host, $config['username'], $config['password']);
            }else{
                $this->linkID = mysql_connect( $host, $config['username'], $config['password'],true);
            }
            if ( !$this->linkID || (!empty($config['database']) && !mysql_select_db($config['database'], $this->linkID)) ) {
                throw_exception(mysql_error());
            }
            $dbVersion = mysql_get_server_info($this->linkID);
            if ($dbVersion >= "4.1") {
                //使用UTF8存取資料庫 需要mysql 4.1.0以上支援
                mysql_query("SET NAMES 'UTF8'", $this->linkID);
            }
            //設定 sql_model
            if($dbVersion >'5.0.1'){
                mysql_query("SET sql_mode=''",$this->linkID);
            }
            // 標記連線成功
            $this->connected    =   true;
            // 登出資料庫連線配置資訊
            unset($this->config);
        }
    }

    /**
     +----------------------------------------------------------
     * 釋放查詢結果
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function free() {
        mysql_free_result($this->queryID);
        $this->queryID = 0;
    }

    /**
     +----------------------------------------------------------
     * 執行查詢 主要針對 SELECT, SHOW 等指令
     * 返回資料集
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  sql指令
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function query($str='') {
        $this->connect();
        if ( !$this->linkID ) return false;
        if ( $str != '' ) $this->queryStr = $str;
        //釋放前次的查詢結果
        if ( $this->queryID ) {    $this->free();    }
        $this->Q(1);
        $this->queryID = mysql_query($this->queryStr, $this->linkID);
        $this->debug();
        if ( !$this->queryID ) {
            if ( $this->debug )
                throw_exception($this->error());
            else
                return false;
        } else {
            $this->numRows = mysql_num_rows($this->queryID);
            return $this->getAll();
        }
    }

    /**
     +----------------------------------------------------------
     * 執行語句 針對 INSERT, UPDATE 以及DELETE
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  sql指令
     +----------------------------------------------------------
     * @return integer
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function execute($str='') {
        $this->connect();
        if ( !$this->linkID ) return false;
        if ( $str != '' ) $this->queryStr = $str;
        //釋放前次的查詢結果
        if ( $this->queryID ) {    $this->free();    }
        $this->W(1);
        $result =   mysql_query($this->queryStr, $this->linkID) ;
        $this->debug();
        if ( false === $result) {
            if ( $this->debug )
                throw_exception($this->error());
            else
                return false;
        } else {
            $this->numRows = mysql_affected_rows($this->linkID);
            $this->lastInsID = mysql_insert_id($this->linkID);
            return $this->numRows;
        }
    }


    /**
     +----------------------------------------------------------
     * 獲得所有的查詢資料
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function getAll() {
        if ( !$this->queryID ) {
            throw_exception($this->error());
            return false;
        }
        //返回資料集
        $result = array();
        if($this->numRows >0) {
            while($row = mysql_fetch_assoc($this->queryID)){
                $result[]   =   $row;
            }
            mysql_data_seek($this->queryID,0);
        }
        return $result;
    }

    /**
     +----------------------------------------------------------
     * 關閉資料庫
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function close() {
        if (!empty($this->queryID))
            mysql_free_result($this->queryID);
        if ($this->linkID && !mysql_close($this->linkID)){
            throw_exception($this->error());
        }
        $this->linkID = 0;
    }

    /**
     +----------------------------------------------------------
     * 資料庫錯誤資訊
     * 並顯示當前的SQL語句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function error() {
        $this->error = mysql_error($this->linkID);
        if($this->queryStr!=''){
            $this->error .= "\n [ SQL語句 ] : ".$this->queryStr;
        }
        return $this->error;
    }

    /**
     +----------------------------------------------------------
     * SQL指令安全過濾
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $str  SQL字元串
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function escape_string($str) {
        return mysql_escape_string($str);
    }

   /**
     +----------------------------------------------------------
     * 析構方法
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
    */
    public function __destruct()
    {
        // 關閉連線
        $this->close();
    }

    /**
     +----------------------------------------------------------
     * 取得資料庫類例項
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @return mixed 返回資料庫驅動類
     +----------------------------------------------------------
     */
    public static function getInstance($db_config='')
    {
        if ( self::$_instance==null ){
            self::$_instance = new Db($db_config);
        }
        return self::$_instance;
    }

    /**
     +----------------------------------------------------------
     * 分析資料庫配置資訊，支援陣列和DSN
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param mixed $db_config 資料庫配置資訊
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function parseConfig($_db_config='') {
        // 如果配置為空，讀取配置檔案設定
        $db_config = array (
            'dbms'      =>   $_db_config['DB_TYPE'],
            'username'  =>   $_db_config['DB_USER'],
            'password'  =>   $_db_config['DB_PWD'],
            'hostname'  =>   $_db_config['DB_HOST'],
            'hostport'  =>   $_db_config['DB_PORT'],
            'database'  =>   $_db_config['DB_NAME'],
            'dsn'       =>   $_db_config['DB_DSN'],
            'params'    =>   $_db_config['DB_PARAMS'],
        );
        return $db_config;
    }

    /**
     +----------------------------------------------------------
     * 資料庫偵錯 記錄當前SQL
     +----------------------------------------------------------
     * @access protected
     +----------------------------------------------------------
     */
    protected function debug() {
        // 記錄操作結束時間
        if ( $this->debug )    {
            $runtime    =   number_format(microtime(TRUE) - $this->beginTime, 6);
            Log::record(" RunTime:".$runtime."s SQL = ".$this->queryStr,Log::SQL);
        }
    }

    /**
     +----------------------------------------------------------
     * 查詢次數更新或者查詢
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $times
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function Q($times='') {
        static $_times = 0;
        if(empty($times)) {
            return $_times;
        }else{
            $_times++;
            // 記錄開始執行時間
            $this->beginTime = microtime(TRUE);
        }
    }

    /**
     +----------------------------------------------------------
     * 寫入次數更新或者查詢
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $times
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    public function W($times='') {
        static $_times = 0;
        if(empty($times)) {
            return $_times;
        }else{
            $_times++;
            // 記錄開始執行時間
            $this->beginTime = microtime(TRUE);
        }
    }

    /**
     +----------------------------------------------------------
     * 獲取最近一次查詢的sql語句
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public function getLastSql() {
        return $this->queryStr;
    }

}//類定義結束
