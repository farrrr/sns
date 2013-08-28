<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();
/**
 * Xcache快取驅動
 * @category   Extend
 * @package  Extend
 * @subpackage  Driver.Cache
 * @author    liu21st <liu21st@gmail.com>
 */
class CacheXcache extends Cache {

    /**
     * 架構函數
     * @param array $options 快取參數
     * @access public
     */
    public function __construct($options=array()) {
        if ( !function_exists('xcache_info') ) {
            throw_exception(L('_NOT_SUPPERT_').':Xcache');
        }
        $this->options['expire']    =   isset($options['expire'])?$options['expire']:C('DATA_CACHE_TIME');
        $this->options['prefix']    =   isset($options['prefix'])?$options['prefix']:C('DATA_CACHE_PREFIX');
        $this->options['length']    =   isset($options['length'])?$options['length']:0;
    }

    /**
     * 讀取快取
     * @access public
     * @param string $name 快取變數名
     * @return mixed
     */
    public function get($name) {
        N('cache_read',1);
        $name   =   $this->options['prefix'].$name;
        if (xcache_isset($name)) {
            return xcache_get($name);
        }
        return false;
    }

    /**
     * 寫入快取
     * @access public
     * @param string $name 快取變數名
     * @param mixed $value  存儲資料
     * @param integer $expire  有效時間（秒）
     * @return boolen
     */
    public function set($name, $value,$expire=null) {
        N('cache_write',1);
        if(is_null($expire)) {
            $expire = $this->options['expire'] ;
        }
        $name   =   $this->options['prefix'].$name;
        if(xcache_set($name, $value, $expire)) {
            if($this->options['length']>0) {
                // 記錄快取佇列
                $this->queue($name);
            }
            return true;
        }
        return false;
    }

    /**
     * 刪除快取
     * @access public
     * @param string $name 快取變數名
     * @return boolen
     */
    public function rm($name) {
        return xcache_unset($this->options['prefix'].$name);
    }

    /**
     * 清除快取
     * @access public
     * @return boolen
     */
    public function clear() {
        return xcache_clear_cache(XC_TYPE_VAR, 0);
    }
}
