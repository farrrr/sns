<?php
/**
 * ThinkSNS 日誌處理類
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id: Log.class.php 2425 2011-12-17 07:57:00Z liu21st $
 */
class Log {

    // 日誌級別 從上到下，由低到高
    const EMERG   = 'EMERG';  // 嚴重錯誤: 導致系統崩潰無法使用
    const ALERT    = 'ALERT';  // 警戒性錯誤: 必須被立即修改的錯誤
    const CRIT      = 'CRIT';  // 臨界值錯誤: 超過臨界值的錯誤，例如一天24小時，而輸入的是25小時這樣
    const ERR       = 'ERR';  // 一般錯誤: 一般性錯誤
    const WARN    = 'WARN';  // 警告性錯誤: 需要發出警告的錯誤
    const NOTICE  = 'NOTIC';  // 通知: 程式可以運行但是還不夠完美的錯誤
    const INFO     = 'INFO';  // 資訊: 程式輸出資訊
    const DEBUG   = 'DEBUG';  // 偵錯: 偵錯資訊
    const SQL       = 'SQL';  // SQL：SQL語句 注意只在偵錯模式開啟時有效

    // 日誌記錄方式
    const SYSTEM = 0;
    const MAIL      = 1;
    const TCP       = 2;
    const FILE       = 3;

    // 日誌資訊
    static $log =   array();

    // 日期格式
    static $format =  '[ c ]';

    /**
     * 記錄日誌 並且會過濾未經設定的級別
     * @static
     * @access public
     * @param string $message 日誌資訊
     * @param string $level  日誌級別
     * @param boolean $record  是否強制記錄
     * @return void
     */
    static function record($message,$level=self::ERR,$record=false) {
        if($record || strpos(C('LOG_RECORD_LEVEL'),$level)) {
            $now = date(self::$format);
            self::$log[] =   "{$now} ".$_SERVER['REQUEST_URI']." \n  {$level}: {$message}\r\n";
        }
    }

    /**
     * 日志儲存
     * @static
     * @access public
     * @param integer $type 日誌記錄方式
     * @param string $destination  寫入目標
     * @param string $extra 額外參數
     * @return void
     */
    static function save($type=self::FILE,$destination='',$extra='') {
        @mkdir(LOG_PATH,0777,true);
        if(empty($destination))
            $destination = LOG_PATH.date('y_m_d').".log";
        if(self::FILE == $type) { // 檔案方式記錄日誌資訊
            //檢測日志檔案大小，超過配置大小則備份日志檔案重新生成
            if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log(implode("",self::$log), $type,$destination ,$extra);
        // 儲存後清空日誌快取
        self::$log = array();
        //clearstatcache();
    }

    /**
     * 日誌直接寫入
     * @static
     * @access public
     * @param string $message 日誌資訊
     * @param string $level  日誌級別
     * @param integer $type 日誌記錄方式
     * @param string $destination  寫入目標
     * @param string $extra 額外參數
     * @return void
     */
    static function write($message,$level=self::ERR,$type=self::FILE,$destination='',$extra='') {
        @mkdir(LOG_PATH,0777,true);
        $now = date(self::$format);
        if(empty($destination))
            $destination = LOG_PATH.date('y_m_d').".log";
        if(self::FILE == $type) { // 檔案方式記錄日誌
            //檢測日志檔案大小，超過配置大小則備份日志檔案重新生成
            if(is_file($destination) && floor(C('LOG_FILE_SIZE')) <= filesize($destination) )
                rename($destination,dirname($destination).'/'.time().'-'.basename($destination));
        }
        error_log("{$now} ".$_SERVER['REQUEST_URI']." | {$level}: {$message}\r\n", $type,$destination,$extra );
        //clearstatcache();
    }
}
