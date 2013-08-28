<?php
/**
 * 快取模型 - 業務邏輯模型
 * @example
 * setType($type)                       主動設定快取類型
 * set($key, $value, $expire = null)    設定快取key=>value，expire表示有效時間，null表示永久
 * get($key, $mutex = false)            獲取快取資料，支援mutex模式
 * getList($prefix, $key)               批量獲取指定字首下的多個key值的快取
 * rm($key)                             刪除快取
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
// 載入快取操作類
tsload(CORE_LIB_PATH.'/Cache.class.php');
class CacheModel {

    //public static $_cacheHash = array();  // 快取的靜態變數
    protected $handler;                     // 操作控制代碼
    protected $type = 'FILE';               // 快取類型，默認為檔案快取

    /**
     * 初始化快取模型物件，快取類型
     * @return void
     */
    public function __construct($type = '') {
        $type = model('Xdata')->get('cacheconfig:cachetype');
        if($type=='Memcache'){
            $setting = model('Xdata')->get('cacheconfig:cachesetting');
            $memhost = explode(':',$setting);
            C('MEMCACHE_HOST',$memhost[0]);
            C('MEMCACHE_PORT',$memhost[1]);
        }
        //$type = empty($type) ? C('DATA_CACHE_TYPE') : $type;
        !$type && $type = $this->type;
        $this->type = strtoupper($type);
        $this->handler = Cache::getInstance($type);
    }

    /**
     * 鏈式設定快取類型
     * @param string $type 快取類型
     * @return object 快取模型物件
     */
    public function setType($type) {
        $this->type = strtoupper($type);
        $this->handler = Cache::getInstance($type);
        return $this;
    }

    /**
     * 設定快取
     * @param string $key 快取Key值
     * @param mix $value 快取Value值
     * @param boolean 是否設定成功
     */
    public function set($key,$value,$expire = null) {
        // 接管過期時間設定，-1表示永遠不過期
        $value = array(
            'CacheData' => $value,
            'CacheMtime' => time(),
            'CacheExpire' => is_null($expire) ? '-1' : $expire
        );
        $key = C('DATA_CACHE_PREFIX').$key;
        return $this->handler->set($key,$value);
    }

    /**
     * 獲取快取操作，支援mutex模式
     * mutex使用注意
     * 1.設定快取（set）時，需要設定有效時間
     * 2.獲取快取（get）時，需要主動創建快取
     * @param string $_key 快取Key值
     * @param boolean $mutex 是否啟用mutex模式，默認為不啟用
     * @return mix 快取資料
     */
    public function get($_key, $mutex = false) {
        $key = C('DATA_CACHE_PREFIX').$_key;
        // 靜態快取
/*      if(isset(self::$_cacheHash[$key])){
            return self::$_cacheHash[$key];
}*/
        $sc = static_cache('cache_'.$key);
        if(!empty($sc)) {
            return $sc;
        }
        // 獲取快取資料
        $data = $this->handler->get($key);

        // 未設定快取
        if(!$data) {
            return false;
        }
        // mutex模式未開啟
        if (! $mutex) {
            if ($data ['CacheExpire'] < 0 || ($data ['CacheMtime'] + $data ['CacheExpire'] > time ())) {
                return $this->_returnData ( $data ['CacheData'], $key );
            } else {
                // 過期，清理原始快取
                $this->rm ( $_key );
                return false;
            }
        }
        // mutex模式開啟
        if(($data['CacheMtime'] + $data['CacheExpire']) <= time()) {
            //正常情況，有過期時間設定的mutex模式
            if($data['CacheExpire'] > 0) {
                $data['CacheMtime'] = time();
                $this->handler->set($key, $data);
                // 返回false，讓呼叫程式去主動更新快取
                static_cache('cache_'.$key, false);
                return false;
            } else {
                //異常情況，沒有設定有效期的時候，永久有效的時候
                if(!$data['CacheData']) {
                    $this->rm($_key);
                    return false;
                }
                return $this->_returnData($data['CacheData'], $key);
            }
        } else {
            return $this->_returnData($data['CacheData'], $key);
        }
    }

    /**
     * 刪除快取
     * @param string $_key 快取Key值
     * @return boolean 是否刪除成功
     */
    public function rm($_key) {
        $key  = C('DATA_CACHE_PREFIX').$_key;
        static_cache($key, false);
        return $this->handler->rm($key);
    }

    /**
     * 清除快取
     * @access public
     * @return boolen
     */
    public function clear() {
        return $this->handler->clear();
    }

    /**
     * 快取寫入次數
     * @return 獲取快取寫入次數
     */
    public function W() {
        return $this->handler->W();
    }

    /**
     * 快取讀取次數
     * @return 獲取快取的讀取次數
     */
    public function Q() {
        return $this->handler->Q();
    }

    /**
     * 根據某個字首，批量獲取多個快取
     * @param string $prefix 快取字首
     * @param string $key 快取Key值
     * @return mix 快取資料
     */
    public function getList($prefix, $key) {
        if($this->type == 'MEMCACHE') {
            // Memcache有批量獲取快取的介面
            $_data = $this->handler->getMulti($prefix, $key);
            foreach($_data as $k => $d) {
                $data[$k] = $this->_returnData($d['CacheData'], $key);
    }
    } else {
        foreach($key as $k) {
            $_k = $prefix.$k;
            $data[$k] = $this->get($_k);
    }
    }

    return $data;
    }

    /**
     * 返回快取資料操作，方法中，將資料快取到靜態快取中
     * @param mix $data 快取資料
     * @param string $key 快取Key值
     * @return mix 快取資料
     */
    private function _returnData($data, $key) {
        // TODO:可以在此對空值進行處理判斷
        static_cache('cache_'.$key,$data);

        return $data;
    }
    }
