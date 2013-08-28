<?php
// +----------------------------------------------------------------------
// | ThinkPHP 簡潔模式資料庫中間層實現類 只支援mysql
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: liuxiaoqing <liuxiaoqing@zhishisoft.com>
// +----------------------------------------------------------------------
//
class SimpleDB
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
            echo('not support mysql');
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
                echo(mysql_error());
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
                echo($this->error());
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
                echo($this->error());
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
            echo($this->error());
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
            echo($this->error());
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
?>
