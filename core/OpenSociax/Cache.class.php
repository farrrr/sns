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

/**
 * 快取管理類
 * @category   Think
 * @package  Think
 * @subpackage  Core
 * @author    liu21st <liu21st@gmail.com>
 */
class Cache {

    /**
     * 操作控制代碼
     * @var string
     * @access protected
     */
    protected $handler    ;

    /**
     * 快取連線參數
     * @var integer
     * @access protected
     */
    protected $options = array();

    /**
     * 連線快取
     * @access public
     * @param string $type 快取類型
     * @param array $options  配置陣列
     * @return object
     */
    public function connect($type='',$options=array()) {
        if(empty($type))  $type = C('DATA_CACHE_TYPE');
        $type  = strtolower(trim($type));
        $class = 'Cache'.ucwords($type);
        tsload(ADDON_PATH.'/library/cache/'.$class.'.class.php');
        if(class_exists($class))
            $cache = new $class($options);
        else
            throw_exception(L('_CACHE_TYPE_INVALID_').':'.$type);
        return $cache;
    }

    public function __get($name) {
        return $this->get($name);
    }

    public function __set($name,$value) {
        return $this->set($name,$value);
    }

    public function __unset($name) {
        $this->rm($name);
    }
    public function setOptions($name,$value) {
        $this->options[$name]   =   $value;
    }

    public function getOptions($name) {
        return $this->options[$name];
    }

    /**
     * 取得快取類例項
     * @static
     * @access public
     * @return mixed
     */
    static function getInstance() {
        $param = func_get_args();
        return get_instance_of(__CLASS__,'connect',$param);
    }

    /**
     * 佇列快取
     * @access protected
     * @param string $key 佇列名
     * @return mixed
     */
    //
    protected function queue($key) {
        static $_handler = array(
            'file'  =>  array('F','F'),
            'xcache'=>  array('xcache_get','xcache_set'),
            'apc'   =>  array('apc_fetch','apc_store'),
        );
        $queue  =  isset($this->options['queue'])?$this->options['queue']:'file';
        $fun    =  isset($_handler[$queue])?$_handler[$queue]:$_handler['file'];
        $queue_name=isset($this->options['queue_name'])?$this->options['queue_name']:'think_queue';
        $value  =  $fun[0]($queue_name);
        if(!$value) {
            $value   =  array();
        }
        // 進列
        if(false===array_search($key, $value))  array_push($value,$key);
        if(count($value) > $this->options['length']) {
            // 出列
            $key =  array_shift($value);
            // 刪除快取
            $this->rm($key);
            if(APP_DEUBG){
                //偵錯模式下，記錄出列次數
                N($queue_name.'_out_times',1,true);
            }
        }
        return $fun[1]($queue_name,$value);
    }

    public function __call($method,$args){
        //呼叫快取類型自己的方法
        if(method_exists($this->handler, $method)){
            return call_user_func_array(array($this->handler,$method), $args);
        }else{
            throw_exception(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }
}
