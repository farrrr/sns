<?php
/**
 * ThinkPHP系統基類
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 */
class Think
{
    private static $_instance = array();

    /**
     * 自動變數設定
     * @access public
     * @param $name 屬性名稱
     * @param $value  屬性值
     */
    public function __set($name ,$value) {
        if(property_exists($this,$name))
            $this->$name = $value;
    }

    /**
     * 自動變數獲取
     * @access public
     * @param $name 屬性名稱
     * @return mixed
     */
    public function __get($name) {
        return isset($this->$name)?$this->$name:null;
    }

    /**
     * 系統自動載入ThinkPHP類庫
     * 並且支援配置自動載入路徑
     * @param string $classname 物件類名
     * @return void
     */
    public static function autoload($classname) {
        // 檢查是否存在別名定義
        if(tsload($classname)) return ;
        // 自動載入當前項目的Actioon類和Model類
        if(substr($classname,-5)=="Model") {
            tsload(APP_MODEL_PATH.'/'.$classname.'.class.php');
        }elseif(substr($classname,-6)=="Action"){
            tsload(APP_ACTION_PATH.'/'.$classname.'.class.php');
        }else {
            // 根據自動載入路徑設定進行嘗試搜索
            if(tsconfig('APP_AUTOLOAD_PATH')) {
                $paths  =   explode(',',tsconfig('APP_AUTOLOAD_PATH'));
                foreach ($paths as $path){
                    if(tsload($path.'/'.$classname.'.class.php'))
                        // 如果載入類成功則返回
                        return ;
                }
            }
        }
        return ;
    }

    /**
     * 取得物件例項 支援呼叫類的靜態方法
     * @param string $class 物件類名
     * @param string $method 類的靜態方法名
     * @return object
     */
    static public function instance($class,$method='') {
        $identify   =   $class.$method;
        if(!isset(self::$_instance[$identify])) {
            if(class_exists($class)){
                $o = new $class();
                if(!empty($method) && method_exists($o,$method))
                    self::$_instance[$identify] = call_user_func_array(array(&$o, $method));
                else
                    self::$_instance[$identify] = $o;
            }
            else
                halt(L('_CLASS_NOT_EXIST_').' = '.$class.' = '.$method);
        }
        return self::$_instance[$identify];
    }

}//類定義結束
?>
