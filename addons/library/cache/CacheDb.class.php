<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2012 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

defined('THINK_PATH') or exit();
/**
 * 資料庫方式快取驅動
 *    CREATE TABLE think_cache (
 *      cachekey varchar(255) NOT NULL,
 *      expire int(11) NOT NULL,
 *      data blob,
 *      datacrc int(32),
 *      UNIQUE KEY `cachekey` (`cachekey`)
 *    );
 * @category   Extend
 * @package  Extend
 * @subpackage  Driver.Cache
 * @author    liu21st <liu21st@gmail.com>
 */
class CacheDb extends Cache {

    /**
     * 架構函數
     * @param array $options 快取參數
     * @access public
     */
    public function __construct($options=array()) {
        if(empty($options)) {
            $options = array (
                'table'     =>  C('DATA_CACHE_TABLE'),
            );
        }
        $this->options  =   $options;
        $this->options['prefix']    =   isset($options['prefix'])?  $options['prefix']  :   C('DATA_CACHE_PREFIX');
        $this->options['length']    =   isset($options['length'])?  $options['length']  :   0;
        $this->options['expire']    =   isset($options['expire'])?  $options['expire']  :   C('DATA_CACHE_TIME');
        import('Db');
        $this->handler   = DB::getInstance();
    }

    /**
     * 讀取快取
     * @access public
     * @param string $name 快取變數名
     * @return mixed
     */
    public function get($name) {
        $name       =  $this->options['prefix'].addslashes($name);
        N('cache_read',1);
        $result     =  $this->handler->query('SELECT `data`,`datacrc` FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\' AND (`expire` =0 OR `expire`>'.time().') LIMIT 0,1');
        if(false !== $result ) {
            $result   =  $result[0];
            if(C('DATA_CACHE_CHECK')) {//開啟資料校驗
                if($result['datacrc'] != md5($result['data'])) {//校驗錯誤
                    return false;
                }
            }
            $content   =  $result['data'];
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
     * 寫入快取
     * @access public
     * @param string $name 快取變數名
     * @param mixed $value  存儲資料
     * @param integer $expire  有效時間（秒）
     * @return boolen
     */
    public function set($name, $value,$expire=null) {
        $data   =  serialize($value);
        $name   =  $this->options['prefix'].addslashes($name);
        N('cache_write',1);
        if( C('DATA_CACHE_COMPRESS') && function_exists('gzcompress')) {
            //資料壓縮
            $data   =   gzcompress($data,3);
    }
    if(C('DATA_CACHE_CHECK')) {//開啟資料校驗
        $crc  =  md5($data);
    }else {
        $crc  =  '';
    }
    if(is_null($expire)) {
        $expire  =  $this->options['expire'];
    }
    $expire     =   ($expire==0)?0: (time()+$expire) ;//快取有效期為0表示永久快取
    $result     =   $this->handler->query('select `cachekey` from `'.$this->options['table'].'` where `cachekey`=\''.$name.'\' limit 0,1');
    if(!empty($result) ) {
        //更新記錄
        $result  =  $this->handler->execute('UPDATE '.$this->options['table'].' SET data=\''.$data.'\' ,datacrc=\''.$crc.'\',expire='.$expire.' WHERE `cachekey`=\''.$name.'\'');
    }else {
        //新增記錄
        $result  =  $this->handler->execute('INSERT INTO '.$this->options['table'].' (`cachekey`,`data`,`datacrc`,`expire`) VALUES (\''.$name.'\',\''.$data.'\',\''.$crc.'\','.$expire.')');
    }
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
     * 刪除快取
     * @access public
     * @param string $name 快取變數名
     * @return boolen
     */
    public function rm($name) {
        $name  =  $this->options['prefix'].addslashes($name);
        return $this->handler->execute('DELETE FROM `'.$this->options['table'].'` WHERE `cachekey`=\''.$name.'\'');
    }

    /**
     * 清除快取
     * @access public
     * @return boolen
     */
    public function clear() {
        return $this->handler->execute('TRUNCATE TABLE `'.$this->options['table'].'`');
    }

    }
