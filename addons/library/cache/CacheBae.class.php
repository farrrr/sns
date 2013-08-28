<?php
class CacheBae extends Cache {

    static $_cache;
    private $_handler;

    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct($options='') {
        if(!empty($options)) {
            $this->options =  $options;
        }
        $this->options['expire'] = isset($options['expire'])?$options['expire']:C('DATA_CACHE_TIME');
        $this->options['length']  =  isset($options['length'])?$options['length']:0;
        $this->options['queque']  =  'bae';
        $this->init();
    }

    /**
     +----------------------------------------------------------
     * 初始化檢查
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    private function init() {
        $this->_handler = new BaeMemcache();
        $this->connected = true;
    }

    /**
     +----------------------------------------------------------
     * 是否連線
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    private function isConnected() {
        return $this->connected;
    }
    /**
     +----------------------------------------------------------
     * 讀取快取
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 快取變數名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    public function get($name) {
        N('cache_read',1);
        $content = $this->_handler->get($name);
        if(false !== $content ){
            if(C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
                $content = substr($content,0,-1);  //remvoe \0 in the end
            }
            if(C('DATA_CACHE_CHECK')) {//開啟資料校驗
                $check  =  substr($content,0, 32);
                $content   =  substr($content,32);
                if($check != md5($content)) {//校驗錯誤
                    return false;
                }
            }
            if(C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
                //啟用資料壓縮
                $content   =   gzuncompress($content);
            }
            $content    =   unserialize($content);
            return $content;
        }
        else {
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 寫入快取
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 快取變數名
     * @param mixed $value  存儲資料
     * @param int $expire  有效時間 0為永久
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function set($name,$value,$expire=null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire =  $this->options['expire'];
    }
    $data   =   serialize($value);
    if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
        //資料壓縮
        //    $data   =   gzcompress($data,3);
        $data =  gzencode($data) . "\0";
    }
    if(C('DATA_CACHE_CHECK')) {//開啟資料校驗
        $check  =  md5($data);
    }else {
        $check  =  '';
    }
    $data = $check.$data;
    $result =  $this->_handler->set($name,$data,0,intval($expire));
    if($result) {
        if($this->options['length']>0) {
            // 記錄快取佇列
            $this->queue($name);
    }
    return true;
    }else {
        return false;
    }
    }

    /**
     +----------------------------------------------------------
     * 刪除快取
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $name 快取變數名
     +----------------------------------------------------------
     * @return boolen
     +----------------------------------------------------------
     */
    public function rm($name) {
        return $this->_handler->delete($name);
    }
    static function queueSet($name,$value)
    {
        $h = new BaeMemcache();
        if ( $h->set($name,$value) ){
            self::$_cache = array($name => $value);
    }
    }
    static function queueGet($name)
    {
        if(isset(self::$_cache[$name]))
            return self::$_cache[$name];
        $h = new BaeMemcache();
        $r = $h->get($name);
        if ( false === $r ){
            return false;
    }
    self::$_cache[$name] = $r;
    return $r;
    }

    }
