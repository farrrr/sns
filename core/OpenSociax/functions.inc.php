<?php
/**
 * Cookie 設定、獲取、清除 (支援陣列或物件直接設定) 2009-07-9
 * 1 獲取cookie: cookie('name')
 * 2 清空當前設定字首的所有cookie: cookie(null)
 * 3 刪除指定字首所有cookie: cookie(null,'think_') | 注：字首將不區分大小寫
 * 4 設定cookie: cookie('name','value') | 指定儲存時間: cookie('name','value',3600)
 * 5 刪除cookie: cookie('name',null)
 * $option 可用設定prefix,expire,path,domain
 * 支援陣列形式:cookie('name','value',array('expire'=>1,'prefix'=>'think_'))
 * 支援query形式字元串:cookie('name','value','prefix=tp_&expire=10000')
 * 2010-1-17 去掉自動序列化操作，相容其他語言程式。
 */
function cookie($name,$value='',$option=null) {
    // 默認設定
    $config = array(
        'prefix' => C('COOKIE_PREFIX'), // cookie 名稱字首
        'expire' => C('COOKIE_EXPIRE'), // cookie 儲存時間
        'path'   => C('COOKIE_PATH'),   // cookie 儲存路徑
        'domain' => C('COOKIE_DOMAIN'), // cookie 有效域名
    );

    // 參數設定(會覆蓋黙認設定)
    if (!empty($option)) {
        if (is_numeric($option)) {
            $option = array('expire'=>$option);
        }else if( is_string($option) ) {
            parse_str($option,$option);
        }
        $config =   array_merge($config,array_change_key_case($option));
    }

    // 清除指定字首的所有cookie
    if (is_null($name)) {
        if (empty($_COOKIE)) return;
        // 要刪除的cookie字首，不指定則刪除config設定的指定字首
        $prefix = empty($value)? $config['prefix'] : $value;
        if (!empty($prefix))// 如果字首為空字元串將不作處理直接返回
        {
            foreach($_COOKIE as $key=>$val) {
                if (0 === stripos($key,$prefix)){
                    //todo:https判斷
                    setcookie($_COOKIE[$key],'',time()-3600,$config['path'],$config['domain'],false,true);
                    unset($_COOKIE[$key]);
                }
            }
        }
        return;
    }
    $name = $config['prefix'].$name;

    if (''===$value){
        //return isset($_COOKIE[$name]) ? unserialize($_COOKIE[$name]) : null;// 獲取指定Cookie
        return isset($_COOKIE[$name]) ? ($_COOKIE[$name]) : null;// 獲取指定Cookie
    }else {
        if (is_null($value)) {
            setcookie($name,'',time()-3600,$config['path'],$config['domain']);
            unset($_COOKIE[$name]);// 刪除指定cookie
        }else {
            // 設定cookie
            $expire = !empty($config['expire'])? time()+ intval($config['expire']):0;
            //setcookie($name,serialize($value),$expire,$config['path'],$config['domain']);
            setcookie($name,($value),$expire,$config['path'],$config['domain']);
            //$_COOKIE[$name] = ($value);
        }
    }
}

/**
 * 獲取站點唯一金鑰，用於區分同域名下的多個站點
 * @return string
 */
function getSiteKey(){
    return md5(C('SECURE_KEY').C('SECURE_CODE').C('COOKIE_PREFIX'));
}

/**
 * 是否AJAX請求
 * @return bool
 */
function isAjax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
            return true;
    }
    if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')]))
        return true;
    return false;
}

/**
 * 字元串命名風格轉換
 * type
 * =0 將Java風格轉換為C的風格
 * =1 將C風格轉換為Java的風格
 * @param string $name 字元串
 * @param integer $type 轉換類型
 * @return string
 */
function parse_name($name,$type=0) {
    if($type) {
        return ucfirst(preg_replace("/_([a-zA-Z])/e", "strtoupper('\\1')", $name));
    }else{
        $name = preg_replace("/[A-Z]/", "_\\0", $name);
        return strtolower(trim($name, "_"));
    }
}

/**
 * 優化格式的列印輸出
 * @param string $var 變數
 * @param bool $return 是否return
 * @return mixed
 */
function dump($var, $return=false) {
    ob_start();
    var_dump($var);
    $output = ob_get_clean();
    if(!extension_loaded('xdebug')) {
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        $output = '<pre style="text-align:left">'. htmlspecialchars($output, ENT_QUOTES). '</pre>';
    }
    if (!$return) {
        echo '<pre style="text-align:left">';
        echo($output);
        echo '</pre>';
    }else
        return $output;
}

/**
 * 自定義異常處理
 * @param string $msg 異常訊息
 * @param string $type 異常類型
 * @return string
 */
function throw_exception($msg,$type='') {
    header("Content-Type:text/html; charset=UTF8");
    if(defined('IS_CGI') && IS_CGI)   exit($msg);
    if(class_exists($type,false))
        throw new $type($msg,$code,true);
    else
        die($msg); // 異常類型不存在則輸出錯誤資訊字串
}

/**
 * 系統自動載入ThinkPHP基類庫和當前項目的model和Action物件
 * 並且支援配置自動載入路徑
 * @param string $name 物件類名
 * @return void
 */
function halt($text) {
    return dump($text);
}

/**
 * 區分大小寫的檔案存在判斷
 * @param string $filename 檔案明
 * @return bool
 */
function file_exists_case($filename) {
    if(is_file($filename)) {
        if(IS_WIN && C('APP_FILE_CASE')) {
            if(basename(realpath($filename)) != basename($filename))
                return false;
        }
        return true;
    }
    return false;
}

/**
 * 根據PHP各種類型變數生成唯一標識號
 * @param mixed $mix 輸入變數
 * @return string 輸出唯一編號
 */
function to_guid_string($mix) {
    if(is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    }elseif(is_resource($mix)){
        $mix = get_resource_type($mix).strval($mix);
    }else{
        $mix = serialize($mix);
    }
    return md5($mix);
}

/**
 * 取得物件例項 支援呼叫類的靜態方法
 * @param string $name 類名
 * @param string $method 方法
 * @param string $args 參數
 * @return object 物件例項
 */
function get_instance_of($name,$method='',$args=array()) {
    static $_instance = array();
    $identify   =   empty($args)?$name.$method:$name.$method.to_guid_string($args);
    if (!isset($_instance[$identify])) {
        if(class_exists($name)){
            $o = new $name();
            if(method_exists($o,$method)){
                if(!empty($args)) {
                    $_instance[$identify] = call_user_func_array(array(&$o, $method), $args);
                }else {
                    $_instance[$identify] = $o->$method();
                }
            }
            else
                $_instance[$identify] = $o;
        }
        else
            halt(L('_CLASS_NOT_EXIST_').':'.$name);
    }
    return $_instance[$identify];
}

/**
 * 自動載入類
 * @param string $name 類名
 * @return void
 */
function __autoload($name) {
    // 檢查是否存在別名定義
    if(import($name)) return ;
    // 自動載入當前項目的Actioon類和Model類
    if(substr($name,-5)=="Model") {
        import(APP_LIB_PATH.'Model/'.ucfirst($name).'.class.php');
    }elseif(substr($name,-6)=="Action"){
        import(APP_LIB_PATH.'Action/'.ucfirst($name).'.class.php');
    }else {
        // 根據自動載入路徑設定進行嘗試搜索
        if(C('APP_AUTOLOAD_PATH')) {
            $paths  =   explode(',',C('APP_AUTOLOAD_PATH'));
            foreach ($paths as $path){
                if(import($path.'/'.$name.'.class.php')) {
                    // 如果載入類成功則返回
                    return ;
                }
            }
        }
    }
    return ;
}

/**
 * 匯入類庫
 * @param string $name 類名
 * @return bool
 */
function import($filename) {
    static $_importFiles = array();
    if (!isset($_importFiles[$filename])) {
        if(file_exists($filename)){
            require $filename;
            $_importFiles[$filename] = true;
        }else{
            $file = explode('.', $filename);
            if(file_exists(APPS_PATH.'/'.$file[0].'/Lib/'.$file[1].'/'.$file[2].'.class.php')){
                require APPS_PATH.'/'.$file[0].'/Lib/'.$file[1].'/'.$file[2].'.class.php';
                $_importFiles[$filename] = true;
            }else{
                $_importFiles[$filename] = false;
            }
        }
    }
    return $_importFiles[$filename];
}

/**
 * C函數用於讀取/設定系統配置
 * @param string name 配置名稱
 * @param string value 值
 * @return mixed 配置值|設定狀態
 */
function C($name=null,$value=null) {
    global $ts;
    // 無參數時獲取所有
    if(empty($name)) return $ts['_config'];
    // 優先執行設定獲取或賦值
    if (is_string($name))
    {
        if (!strpos($name,'.')) {
            $name = strtolower($name);
            if (is_null($value))
                return isset($ts['_config'][$name])? $ts['_config'][$name] : null;
            $ts['_config'][$name] = $value;
            return;
        }
        // 二維陣列設定和獲取支援
        $name = explode('.',$name);
        $name[0]   = strtolower($name[0]);
        if (is_null($value))
            return isset($ts['_config'][$name[0]][$name[1]]) ? $ts['_config'][$name[0]][$name[1]] : null;
        $ts['_config'][$name[0]][$name[1]] = $value;
        return;
    }
    // 批量設定
    if(is_array($name))
        return $ts['_config'] = array_merge((array)$ts['_config'],array_change_key_case($name));
    return null;// 避免非法參數
}

//D函數的別名
function M($name='',$app='@') {
    return D($name,$app);
}

/**
 * D函數用於例項化Model
 * @param string name Model名稱
 * @param string app Model所在項目
 * @return object
 */
function D($name='', $app='@', $inclueCommonFunction=true) {
    static $_model = array();

    if(empty($name)) return new Model;
    if(empty($app) || $app=='@')   $app =  APP_NAME;

    $name = ucfirst($name);

    if(isset($_model[$app.$name]))
        return $_model[$app.$name];

    $OriClassName = $name;
    $className =  $name.'Model';

    //優先載入核心的 所以不要和核心的model重名
    if(file_exists(ADDON_PATH.'/model/'.$className.'.class.php')){
        tsload(ADDON_PATH.'/model/'.$className.'.class.php');
    }elseif(file_exists(APPS_PATH.'/'.$app.'/Lib/Model/'.$className.'.class.php')){
        $common = APPS_PATH.'/'.$app.'/Common/common.php';
        if(file_exists($common) && $inclueCommonFunction){
            tsload($common);
        }
        tsload(APPS_PATH.'/'.$app.'/Lib/Model/'.$className.'.class.php');
    }

    if(class_exists($className)) {

        $model = new $className();
    }else{
        $model  = new Model($name);
    }
    $_model[$app.$OriClassName] =  $model;
    return $model;
}

/**
 * A函數用於例項化Action
 * @param string name Action名稱
 * @param string app Model所在項目
 * @return object
 */
function A($name,$app='@') {
    static $_action = array();

    if(empty($app) || $app=='@')   $app =  APP_NAME;

    if(isset($_action[$app.$name]))
        return $_action[$app.$name];

    $OriClassName = $name;
    $className =  $name.'Action';
    tsload(APP_ACTION_PATH.'/'.$className.'.class.php');

    if(class_exists($className)) {
        $action = new $className();
        $_action[$app.$OriClassName] = $action;
        return $action;
    }else {
        return false;
    }
}

/**
 * L函數用於讀取/設定語言配置
 * @param string name 配置名稱
 * @param string value 值
 * @return mixed 配置值|設定狀態
 */
function L($key,$data = array()){
    $key = strtoupper($key);
    if(!isset($GLOBALS['_lang'][$key])){
        $notValveForKey = F('notValveForKey', '', DATA_PATH.'/develop');
        if($notValveForKey==false){
            $notValveForKey = array();
        }
        if(!isset($notValveForKey[$key])){
            $notValveForKey[$key] = '?app='.APP_NAME.'&mod='.MODULE_NAME.'&act='.ACTION_NAME;
        }
        F('notValveForKey', $notValveForKey, DATA_PATH.'/develop');

        return $key;
    }
    if(empty($data)){
        return $GLOBALS['_lang'][$key];
    }
    $replace = array_keys($data);
    foreach($replace as &$v){
        $v = "{".$v."}";
    }
    return str_replace($replace,$data,$GLOBALS['_lang'][$key]);
}

/**
 * 記錄和統計時間（微秒）和內存使用情況
 * 使用方法:
 * <code>
 * G('begin'); // 記錄開始標記位
 * // ... 區間運行程式碼
 * G('end'); // 記錄結束標籤位
 * echo G('begin','end',6); // 統計區間運行時間 精確到小數後6位
 * echo G('begin','end','m'); // 統計區間內存使用情況
 * 如果end標記位沒有定義，則會自動以當前作為標記位
 * 其中統計內存使用需要 MEMORY_LIMIT_ON 常量為true纔有效
 * </code>
 * @param string $start 開始標籤
 * @param string $end 結束標籤
 * @param integer|string $dec 小數位或者m
 * @return mixed
 */
function G($start,$end='',$dec=4) {
    static $_info       =   array();
    static $_mem        =   array();
    if(is_float($end)) { // 記錄時間
        $_info[$start]  =   $end;
    }elseif(!empty($end)){ // 統計時間和內存使用
        if(!isset($_info[$end])) $_info[$end]       =  microtime(TRUE);
        if(MEMORY_LIMIT_ON && $dec=='m'){
            if(!isset($_mem[$end])) $_mem[$end]     =  memory_get_usage();
            return number_format(($_mem[$end]-$_mem[$start])/1024);
        }else{
            return number_format(($_info[$end]-$_info[$start]),$dec);
        }

    }else{ // 記錄時間和內存使用
        $_info[$start]  =  microtime(TRUE);
        if(MEMORY_LIMIT_ON) $_mem[$start]           =  memory_get_usage();
    }
}

/**
 * 設定和獲取統計資料
 * 使用方法:
 * <code>
 * N('db',1); // 記錄資料庫操作次數
 * N('read',1); // 記錄讀取次數
 * echo N('db'); // 獲取當前頁面資料庫的所有操作次數
 * echo N('read'); // 獲取當前頁面讀取次數
 * </code>
 * @param string $key 標識位置
 * @param integer $step 步進值
 * @return mixed
 */
function N($key, $step=0,$save=false) {
    static $_num    = array();
    if (!isset($_num[$key])) {
        $_num[$key] = (false !== $save)? S('N_'.$key) :  0;
    }
    if (empty($step))
        return $_num[$key];
    else
        $_num[$key] = $_num[$key] + (int) $step;
    if(false !== $save){ // 儲存結果
        S('N_'.$key,$_num[$key],$save);
    }
}

/**
 * 用於判斷檔案字尾是否是圖片
 * @param string file 檔案路徑，通常是$_FILES['file']['tmp_name']
 * @return bool
 */
function is_image_file($file){
    $fileextname = strtolower(substr(strrchr(rtrim(basename($file),'?'),"."),1,4));
    if(in_array($fileextname,array('jpg','jpeg','gif','png','bmp'))){
        return true;
    }else{
        return false;
    }
}

/**
 * 用於判斷檔案字尾是否是PHP、EXE類的可執行檔案
 * @param string file 檔案路徑
 * @return bool
 */
function is_notsafe_file($file){
    $fileextname = strtolower(substr(strrchr(rtrim(basename($file),'?'), "."),1,4));
    if(in_array($fileextname,array('php','php3','php4','php5','exe','sh'))){
        return true;
    }else{
        return false;
    }
}

/**
 * t函數用於過濾標籤，輸出沒有html的乾淨的文字
 * @param string text 文字內容
 * @return string 處理後內容
 */
function t($text){
    $text = nl2br($text);
    $text = real_strip_tags($text);
    $text = addslashes($text);
    $text = trim($text);
    return $text;
}

/**
 * h函數用於過濾不安全的html標籤，輸出安全的html
 * @param string $text 待過濾的字元串
 * @param string $type 保留的標籤格式
 * @return string 處理後內容
 */
function h($text, $type = 'html'){
    // 無標籤格式
    $text_tags  = '';
    //只保留連結
    $link_tags  = '<a>';
    //只保留圖片
    $image_tags = '<img>';
    //只存在字型樣式
    $font_tags  = '<i><b><u><s><em><strong><font><big><small><sup><sub><bdo><h1><h2><h3><h4><h5><h6>';
    //標題摘要基本格式
    $base_tags  = $font_tags.'<p><br><hr><a><img><map><area><pre><code><q><blockquote><acronym><cite><ins><del><center><strike>';
    //相容Form格式
    $form_tags  = $base_tags.'<form><input><textarea><button><select><optgroup><option><label><fieldset><legend>';
    //內容等允許HTML的格式
    $html_tags  = $base_tags.'<meta><ul><ol><li><dl><dd><dt><table><caption><td><th><tr><thead><tbody><tfoot><col><colgroup><div><span><object><embed><param>';
    //專題等全HTML格式
    $all_tags   = $form_tags.$html_tags.'<!DOCTYPE><html><head><title><body><base><basefont><script><noscript><applet><object><param><style><frame><frameset><noframes><iframe>';
    //過濾標籤
    $text = real_strip_tags($text, ${$type.'_tags'});
    // 過濾攻擊程式碼
    if($type != 'all') {
        // 過濾危險的屬性，如：過濾on事件lang js
        while(preg_match('/(<[^><]+)(ondblclick|onclick|onload|onerror|unload|onmouseover|onmouseup|onmouseout|onmousedown|onkeydown|onkeypress|onkeyup|onblur|onchange|onfocus|action|background|codebase|dynsrc|lowsrc)([^><]*)/i',$text,$mat)){
            $text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
        }
        while(preg_match('/(<[^><]+)(window\.|javascript:|js:|about:|file:|document\.|vbs:|cookie)([^><]*)/i',$text,$mat)){
            $text = str_ireplace($mat[0], $mat[1].$mat[3], $text);
        }
    }
    return $text;
}

/**
 * U函數用於生成URL地址
 * @param string $url ThinkSNS特有URL標識符
 * @param array $params URL附加參數
 * @param bool $redirect 是否自動跳轉到生成的URL
 * @return string 輸出URL
 */
function U($url,$params=false,$redirect=false) {

    //普通模式
    if(false==strpos($url,'/')){
        $url    .='//';
    }

    //填充默認參數
    $urls   =   explode('/',$url);
    $app    =   isset($urls[0]) && !empty($urls[0]) ? $urls[0] : APP_NAME;
    $mod    =   isset($urls[1]) && !empty($urls[1]) ? ucfirst($urls[1]) : 'Index';
    $act    =   isset($urls[2]) && !empty($urls[2]) ? $urls[2] : 'index';

    //組合默認路徑
    $site_url   =   SITE_URL.'/index.php?app='.$app.'&mod='.$mod.'&act='.$act;

    //填充附加參數
    if($params){
        if(is_array($params)){
            $params =   http_build_query($params);
            $params =   urldecode($params);
        }
        $params     =   str_replace('&amp;','&',$params);
        $site_url   .=  '&'.$params;
    }

    //開啟路由和Rewrite
    if(C('URL_ROUTER_ON')){

        //載入路由
        $router_ruler   =   C('router');
        $router_key     =   $app.'/'.$mod.'/'.$act;

        //路由命中
        if(isset($router_ruler[$router_key])){

            //填充路由參數
            if(false==strpos($router_ruler[$router_key],'://')){
                $site_url   =   SITE_URL.'/'.$router_ruler[$router_key];
            }else{
                $site_url   =   $router_ruler[$router_key];
            }

            //填充附加參數
            if($params){

                //解析替換URL中的參數
                parse_str($params,$r);
                foreach($r as $k=>$v){
                    if(strpos($site_url,'['.$k.']')){
                        $site_url   =   str_replace('['.$k.']',$v,$site_url);
                    }else{
                        $lr[$k] =   $v;
                    }
                }

                //填充剩餘參數
                if(isset($lr) && is_array($lr) && count($lr)>0){
                    $site_url   .=  '?'.http_build_query($lr);
                }

            }
        }
    }

    //輸出地址或跳轉
    if($redirect){
        redirect($site_url);
    }else{
        return $site_url;
    }
}

/**
 * URL跳轉函數
 * @param string $url ThinkSNS特有URL標識符
 * @param integer $time 跳轉延時(秒)
 * @param string $msg 提示語
 * @return void
 */
function redirect($url,$time=0,$msg='') {
    //多行URL地址支援
    $url = str_replace(array("\n", "\r"), '', $url);
    if(empty($msg))
        $msg    =   "系統將在{$time}秒之後自動跳轉到{$url}！";
    if (!headers_sent()) {
        // redirect
        if(0===$time) {
            header("Location: ".$url);
        }else {
            header("Content-type: text/html; charset=utf-8");
            header("refresh:{$time};url={$url}");
            echo($msg);
        }
        exit();
    }else {
        $str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
        if($time!=0)
            $str   .=   $msg;
        exit($str);
    }
}

/**
 * 用來對應用快取資訊的讀、寫、刪除
 *
 * $expire = null/0 表示永久快取，否則為快取有效期
 */
function S($name,$value='',$expire=null) {

    static $_cache = array();   //減少快取讀取

    $cache = model('Cache');

    //$name = C('DATA_CACHE_PREFIX').$name;

    if('' !== $value) {

        if(is_null($value)) {
            // 刪除快取
            $result =   $cache->rm($name);
            if($result)   unset($_cache[$name]);
            return $result;
        }else{
            // 快取資料
            $cache->set($name,$value,$expire);
            $_cache[$name]     =   $value;
        }
        return true;
    }
    if(isset($_cache[$name]))
        return $_cache[$name];
    // 獲取快取資料
    $value      =  $cache->get($name);
    $_cache[$name]     =   $value;
    return $value;
}

/**
 * 檔案快取,多用來快取配置資訊
 *
 */
function F($name,$value='',$path=false) {
    static $_cache = array();
    if(!$path) {
        $path   =   C('F_CACHE_PATH');
    }
    if(!is_dir($path)) {
        mkdir($path,0777,true);
    }
    $filename   =   $path.'/'.$name.'.php';
    if('' !== $value) {
        if(is_null($value)) {
            // 刪除快取
            return unlink($filename);
        }else{
            // 快取資料
            $dir   =  dirname($filename);
            // 目錄不存在則創建
            if(!is_dir($dir))  mkdir($dir,0777,true);
            return @file_put_contents($filename,"<?php\nreturn ".var_export($value,true).";\n?>");
        }
    }
    if(isset($_cache[$name])) return $_cache[$name];
    // 獲取快取資料
    if(is_file($filename)) {
        $value   =  include $filename;
        $_cache[$name]   =   $value;
    }else{
        $value  =   false;
    }
    return $value;
}

function W($name,$data=array(),$return=false) {
    $class = $name.'Widget';
    if(file_exists(APP_WIDGET_PATH.'/'.$class.'/'.$class.'.class.php')){
        tsload(APP_WIDGET_PATH.'/'.$class.'/'.$class.'.class.php');
    }elseif(!empty($data['widget_appname']) && file_exists(APPS_PATH.'/'.$data['widget_appname'].'/Lib/Widget/'.$class.'/'.$class.'.class.php')){
        addLang($data['widget_appname']);
        tsload(APPS_PATH.'/'.$data['widget_appname'].'/Lib/Widget/'.$class.'/'.$class.'.class.php');
    }else{
        tsload(ADDON_PATH.'/widget/'.$class.'/'.$class.'.class.php');
    }
    if(!class_exists($class))
        throw_exception(L('_CLASS_NOT_EXIST_').':'.$class);
    $widget     =   new $class();
    $content    =   $widget->render($data);
    if($return)
        return $content;
    else
        echo $content;
}

// 例項化服務
function api($name,$params=array()) {
    static $_api = array();
    if(isset($_api[$name])){
        return $_api[$name];
    }
    $OriClassName = $name;
    $className = $name.'Api';
    require_once(ADDON_PATH.'/api/'.$name.'Api.class.php');
    if(class_exists($className)) {
        $api = new $className(true);
        $_api[$OriClassName] = $api;
        return $api;
    }else {
        return false;
    }
}

// 例項化服務
function service($name,$params=array()) {
    return X($name,$params,'service');
}

// 例項化服務
function widget($name,$params=array(),$return=false) {
    return X($name,$params,'widget');
}

// 例項化model
function model($name,$params=array()) {
    return X($name,$params,'model');
}

// 呼叫介面服務
function X($name,$params=array(),$domain='model') {
    static $_service = array();

    $app =  C('DEFAULT_APP');

    $domain = ucfirst($domain);

    if(isset($_service[$domain.'_'.$app.'_'.$name]))
        return $_service[$domain.'_'.$app.'_'.$name];

    $class = $name.$domain;
    if(file_exists(APP_LIB_PATH.$domain.'/'.$class.'.class.php')){
        tsload(APP_LIB_PATH.$domain.'/'.$class.'.class.php');
    }else{
        tsload(ADDON_PATH.'/'.strtolower($domain).'/'.$class.'.class.php',true);
    }
    //服務不可用時 記錄日誌 或 拋出異常
    if(class_exists($class)){
        $obj   =  new $class($params);
        $_service[$domain.'_'.$app.'_'.$name] =  $obj;
        return $obj;
    }else{
        throw_exception(L('_CLASS_NOT_EXIST_').':'.$class);
    }
}

// 渲染模板
//$charset 不能是UTF8 否則IE下會亂碼
function fetch($templateFile='',$tvar=array(),$charset='utf-8',$contentType='text/html',$display=false) {
    //注入全局變數ts
    global  $ts;
    $tvar['ts'] = $ts;
    //$GLOBALS['_viewStartTime'] = microtime(TRUE);

    if(null===$templateFile)
        // 使用null參數作為模版名直接返回不做任何輸出
        return ;

    if(empty($charset))  $charset = C('DEFAULT_CHARSET');

    // 網頁字元編碼
    header("Content-Type:".$contentType."; charset=".$charset);

    header("Cache-control: private");  //支援頁面回跳

    //頁面快取
    ob_start();
    ob_implicit_flush(0);

    // 模版名為空.
    if(''==$templateFile){
        $templateFile   =   APP_TPL_PATH.'/'.MODULE_NAME.'/'.ACTION_NAME.'.html';

        // 模版名為ACTION_NAME
    }elseif(file_exists(APP_TPL_PATH.'/'.MODULE_NAME.'/'.$templateFile.'.html')) {
        $templateFile   =   APP_TPL_PATH.'/'.MODULE_NAME.'/'.$templateFile.'.html';

        // 模版是絕對路徑
    }elseif(file_exists($templateFile)){

        // 模版不存在
    }else{
        throw_exception(L('_TEMPLATE_NOT_EXIST_').'['.$templateFile.']');
    }

    //模版快取檔案
    $templateCacheFile  =   C('TMPL_CACHE_PATH').'/'.APP_NAME.'_'.tsmd5($templateFile).'.php';

    //載入模版快取
    if(!$ts['_debug'] && file_exists($templateCacheFile)) {
        //if(1==2){ //TODO  開發
        extract($tvar, EXTR_OVERWRITE);

        //載入模版快取檔案
        include $templateCacheFile;

        //重新編譯
    }else{

        tshook('tpl_compile',array('templateFile',$templateFile));

        // 快取無效 重新編譯
        tsload(CORE_LIB_PATH.'/Template.class.php');
        tsload(CORE_LIB_PATH.'/TagLib.class.php');
        tsload(CORE_LIB_PATH.'/TagLib/TagLibCx.class.php');

        $tpl    =   Template::getInstance();
        // 編譯並載入模板檔案
        $tpl->load($templateFile,$tvar,$charset);
    }

    // 獲取並清空快取
    $content = ob_get_clean();

    // 模板內容替換
    $replace =  array(
        '__ROOT__'      =>  SITE_URL,           // 當前網站地址
        '__UPLOAD__'    =>  UPLOAD_URL,         // 上傳檔案地址
        //'__PUBLIC__'    =>  PUBLIC_URL,       // 公共靜態地址
        '__PUBLIC__'    =>  THEME_PUBLIC_URL,   // 公共靜態地址
        '__THEME__'     =>  THEME_PUBLIC_URL,   // 主題靜態地址
        '__APP__'       =>  APP_PUBLIC_URL,     // 應用靜態地址
        '__URL__'       =>  __ROOT__.'/index.php?app='.APP_NAME.'&mod='.MODULE_NAME,
    );

    if(C('TOKEN_ON')) {
        if(strpos($content,'{__TOKEN__}')) {
            // 指定表單令牌隱藏域位置
            $replace['{__TOKEN__}'] =  $this->buildFormToken();
        }elseif(strpos($content,'{__NOTOKEN__}')){
            // 標記為不需要令牌驗證
            $replace['{__NOTOKEN__}'] =  '';
        }elseif(preg_match('/<\/form(\s*)>/is',$content,$match)) {
            // 智慧生成表單令牌隱藏域
            $replace[$match[0]] = $this->buildFormToken().$match[0];
        }
    }

    // 允許使用者自定義模板的字元串替換
    if(is_array(C('TMPL_PARSE_STRING')) )
        $replace =  array_merge($replace,C('TMPL_PARSE_STRING'));

    $content = str_replace(array_keys($replace),array_values($replace),$content);

    // 佈局模板解析
    //$content = $this->layout($content,$charset,$contentType);
    // 輸出模板檔案
    if($display)
        echo $content;
    else
        return $content;
}

// 輸出模版
function display($templateFile='',$tvar=array(),$charset='UTF8',$contentType='text/html') {
    fetch($templateFile,$tvar,$charset,$contentType,true);
}

function mk_dir($dir, $mode = 0755){
    if (is_dir($dir) || @mkdir($dir,$mode)) return true;
    if (!mk_dir(dirname($dir),$mode)) return false;
    return @mkdir($dir,$mode);
}

/**
 * 位元組格式化 把位元組數格式為 B K M G T 描述的大小
 * @return string
 */
function byte_format($size, $dec=2) {
    $a = array("B", "KB", "MB", "GB", "TB", "PB");
    $pos = 0;
    while ($size >= 1024) {
        $size /= 1024;
        $pos++;
    }
    return round($size,$dec)." ".$a[$pos];
}

/**
 * 獲取客戶端IP地址
 */
function get_client_ip($type = 0) {
    $type       =  $type ? 1 : 0;
    static $ip  =   NULL;
    if ($ip !== NULL) return $ip[$type];
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $arr    =   explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
        $pos    =   array_search('unknown',$arr);
        if(false !== $pos) unset($arr[$pos]);
        $ip     =   trim($arr[0]);
    }elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ip     =   $_SERVER['HTTP_CLIENT_IP'];
    }elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ip     =   $_SERVER['REMOTE_ADDR'];
    }
    // IP地址合法驗證
    $long = sprintf("%u",ip2long($ip));
    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
    return $ip[$type];
}

/**
 * 記錄日誌
 * Enter description here ...
 * @param unknown_type $app_group
 * @param unknown_type $action
 * @param unknown_type $data
 * @param unknown_type $isAdmin 是否管理員日誌
 */
function LogRecord($app_group,$action,$data,$isAdmin=false){
    static $log = null;
    if($log == null){
        $log = model('Logs');
    }
    return $log->load($app_group)->action($action)->record($data,$isAdmin);
}

/**
 * 驗證許可權方法
 * @param string $load 應用 - 模組 欄位
 * @param string $action 許可權節點欄位
 * @param unknown_type $group 是否指定應用內部使用者組
 */
function CheckPermission($load = '', $action = '', $group = ''){
    if(empty($load) || empty($action)) {
        return false;
    }
    $Permission = model('Permission')->load($load);
    if(!empty($group)){
        return $Permission->group($group)->check($action);
    }

    return $Permission->check($action);
}
/**
 * 微吧管理許可權判斷
 * @param int $id 微吧id
 * @param string $action 動作
 * @param int $uid 使用者uid
 * @return boolean
 */
function CheckWeibaPermission( $weiba_admin , $id , $action , $uid){
    !$uid && $uid = $GLOBALS['ts']['mid'];
    //超級管理員判斷
    if ( CheckPermission('core_admin','admin_login') ){
        return true;
    }
    if ( $action ){
        //使用者組許可權判斷
        if ( CheckPermission( 'weiba_admin' , $action ) ){
            return true;
        }
    }
    //吧主判斷
    if ( !$weiba_admin && $id ){
        $map['weiba_id'] = $id;
        $map['level'] = array('in','2,3');
        $weiba_admin = D('weiba_follow')->where($map)->order('level desc')->field('follower_uid,level')->findAll();
        $weiba_admin = getSubByKey( $weiba_admin , 'follower_uid' );
    }
    return in_array( $uid , $weiba_admin);
}
function CheckTaskSwitch(){
    $taskswitch = model('Xdata')->get('task_config:task_switch');
    !$taskswitch && $taskswitch = 1;

    return $taskswitch == 1;
}
//獲取當前使用者的前臺管理許可權
function manageList($uid){
    $list = model('App')->getManageApp($uid);
    return $list;
}

/**
 * 取一個二維陣列中的每個陣列的固定的鍵知道的值來形成一個新的一維陣列
 * @param $pArray 一個二維陣列
 * @param $pKey 陣列的鍵的名稱
 * @return 返回新的一維陣列
 */
function getSubByKey($pArray, $pKey="", $pCondition=""){
    $result = array();
    if(is_array($pArray)){
        foreach($pArray as $temp_array){
            if(is_object($temp_array)){
                $temp_array = (array) $temp_array;
            }
            if((""!=$pCondition && $temp_array[$pCondition[0]]==$pCondition[1]) || ""==$pCondition) {
                $result[] = (""==$pKey) ? $temp_array : isset($temp_array[$pKey]) ? $temp_array[$pKey] : "";
            }
        }
        return $result;
    }else{
        return false;
    }
}

/**
 * 獲取字元串的長度
 *
 * 計算時, 漢字或全形字元佔1個長度, 英文字元佔0.5個長度
 *
 * @param string  $str
 * @param boolean $filter 是否過濾html標籤
 * @return int 字元串的長度
 */
function get_str_length($str, $filter = false){
    if ($filter) {
        $str = html_entity_decode($str, ENT_QUOTES);
        $str = strip_tags($str);
    }
    return (strlen($str) + mb_strlen($str, 'UTF8')) / 4;
}

function getShort($str, $length = 40, $ext = '') {
    $str    =   htmlspecialchars($str);
    $str    =   strip_tags($str);
    $str    =   htmlspecialchars_decode($str);
    $strlenth   =   0;
    $out        =   '';
    preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/", $str, $match);
    foreach($match[0] as $v){
        preg_match("/[\xe0-\xef][\x80-\xbf]{2}/",$v, $matchs);
        if(!empty($matchs[0])){
            $strlenth   +=  1;
        }elseif(is_numeric($v)){
            //$strlenth +=  0.545;  // 字元畫素寬度比例 漢字為1
            $strlenth   +=  0.5;    // 字元位元組長度比例 漢字為1
        }else{
            //$strlenth +=  0.475;  // 字元畫素寬度比例 漢字為1
            $strlenth   +=  0.5;    // 字元位元組長度比例 漢字為1
        }

        if ($strlenth > $length) {
            $output .= $ext;
            break;
        }

        $output .=  $v;
    }
    return $output;
}

/**
 * 檢查字元串是否是UTF8編碼
 * @param string $string 字元串
 * @return Boolean
 */
if(!function_exists('is_utf8')){
    function is_utf8($string) {
        return preg_match('%^(?:
            [\x09\x0A\x0D\x20-\x7E]            # ASCII
            | [\xC2-\xDF][\x80-\xBF]             # non-overlong 2-byte
            |  \xE0[\xA0-\xBF][\x80-\xBF]        # excluding overlongs
            | [\xE1-\xEC\xEE\xEF][\x80-\xBF]{2}  # straight 3-byte
            |  \xED[\x80-\x9F][\x80-\xBF]        # excluding surrogates
            |  \xF0[\x90-\xBF][\x80-\xBF]{2}     # planes 1-3
            | [\xF1-\xF3][\x80-\xBF]{3}          # planes 4-15
            |  \xF4[\x80-\x8F][\x80-\xBF]{2}     # plane 16
        )*$%xs', $string);
    }
}

// 自動轉換字符集 支援陣列轉換
function auto_charset($fContents,$from,$to){
    $from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to       =  strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
        //如果編碼相同或者非字元串標量則不轉換
        return $fContents;
    }
    if(is_string($fContents) ) {
        if(function_exists('iconv')){
            return iconv($from,$to,$fContents);
        }else{
            return $fContents;
        }
    }
    elseif(is_array($fContents)){
        foreach ( $fContents as $key => $val ) {
            $_key =     auto_charset($key,$from,$to);
            $fContents[$_key] = auto_charset($val,$from,$to);
            if($key != $_key )
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else{
        return $fContents;
    }
}

/**
 * 友好的時間顯示
 *
 * @param int    $sTime 待顯示的時間
 * @param string $type  類型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 */
function friendlyDate($sTime,$type = 'normal',$alt = 'false') {
    if (!$sTime)
        return '';
    //sTime=源時間，cTime=當前時間，dTime=時間差
    $cTime      =   time();
    $dTime      =   $cTime - $sTime;
    $dDay       =   intval(date("z",$cTime)) - intval(date("z",$sTime));
    //$dDay     =   intval($dTime/3600/24);
    $dYear      =   intval(date("Y",$cTime)) - intval(date("Y",$sTime));
    //normal：n秒前，n分鐘前，n小時前，日期
    if($type=='normal'){
        if( $dTime < 60 ){
            if($dTime < 10){
                return '剛剛';    //by yangjs
            }else{
                return intval(floor($dTime / 10) * 10)."秒前";
            }
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分鐘前";
            //今天的資料.年份相同.日期相同.
        }elseif( $dYear==0 && $dDay == 0  ){
            //return intval($dTime/3600)."小時前";
            return '今天'.date('H:i',$sTime);
        }elseif($dYear==0){
            return date("m月d日 H:i",$sTime);
        }else{
            return date("Y-m-d H:i",$sTime);
        }
    }elseif($type=='mohu'){
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分鐘前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小時前";
        }elseif( $dDay > 0 && $dDay<=7 ){
            return intval($dDay)."天前";
        }elseif( $dDay > 7 &&  $dDay <= 30 ){
            return intval($dDay/7) . '周前';
        }elseif( $dDay > 30 ){
            return intval($dDay/30) . '個月前';
        }
        //full: Y-m-d , H:i:s
    }elseif($type=='full'){
        return date("Y-m-d , H:i:s",$sTime);
    }elseif($type=='ymd'){
        return date("Y-m-d",$sTime);
    }else{
        if( $dTime < 60 ){
            return $dTime."秒前";
        }elseif( $dTime < 3600 ){
            return intval($dTime/60)."分鐘前";
        }elseif( $dTime >= 3600 && $dDay == 0  ){
            return intval($dTime/3600)."小時前";
        }elseif($dYear==0){
            return date("Y-m-d H:i:s",$sTime);
        }else{
            return date("Y-m-d H:i:s",$sTime);
        }
    }
}

/**
 *
 * 正則替換和過濾內容
 *
 * @param  $html
 * @author jason
 */
function preg_html($html){
    $p = array("/<[a|A][^>]+(topic=\"true\")+[^>]*+>#([^<]+)#<\/[a|A]>/",
        "/<[a|A][^>]+(data=\")+([^\"]+)\"[^>]*+>[^<]*+<\/[a|A]>/",
        "/<[img|IMG][^>]+(src=\")+([^\"]+)\"[^>]*+>/");
    $t = array('topic{data=$2}','$2','img{data=$2}');
    $html = preg_replace($p, $t, $html);
    $html   = strip_tags($html,"<br/>");
    return $html;
}

//解析資料成網頁端顯示格式
function parse_html($html){
    $html = htmlspecialchars_decode($html);
    //以下三個過濾是舊版相容方法-可遮蔽
    $html = preg_replace("/img{data=([^}]*)}/"," ", $html);
    $html = preg_replace("/topic{data=([^}]*)}/",'<a href="$1" topic="true">#$1#</a>', $html);
    $html = preg_replace_callback("/@{uid=([^}]*)}/", "_parse_at_by_uid", $html);
    //連結替換
    $html = str_replace('[SITE_URL]',SITE_URL,$html);
    //外網連結地址處理
    //$html = preg_replace_callback('/((?:https?|ftp):\/\/(?:www\.)?(?:[a-zA-Z0-9][a-zA-Z0-9\-]*\.)?[a-zA-Z0-9][a-zA-Z0-9\-]*(?:\.[a-zA-Z0-9]+)+(?:\:[0-9]*)?(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’,，。]*)?)/u', '_parse_url', $html);
    //表情處理
    $html = preg_replace_callback("/(\[.+?\])/is",_parse_expression,$html);
    //話題處理
    $html = str_replace("＃", "#", $html);
    $html = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is",_parse_theme,$html);
    //@提到某人處理
    $html = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", "_parse_at_by_uname",$html);

    return $html;
}

//解析成api顯示格式
function parseForApi($html){
    $html = h($html);
    //以下三個過濾是舊版相容方法-可遮蔽
    $html = preg_replace_callback("/img{data=([^}]*)}/",'_parse_img_forapi', $html);
    $html = preg_replace_callback("/@{uid=([^}]*)}/", '_parse_wap_at_by_uname', $html);
    $html = preg_replace("/topic{data=([^}]*)}/",'#$1#', $html);
    $html = str_replace(array('[SITE_URL]','&nbsp;'),array(SITE_URL,' '),$html);
    //@提到某人處理
    $html = preg_replace_callback("/@([\w\x{2e80}-\x{9fff}\-]+)/u", "_parse_wap_at_by_uname",$html);
    //敏感詞過濾
    return $html;
}

/**
 * 格式化微博,替換話題
 * @param string  $content 待格式化的內容
 * @param boolean $url     是否替換URL
 * @return string
 */
function format($content,$url=false){
    return $content;
}

function replaceTheme($content){
    $content = str_replace("＃", "#", $content);
    $content = preg_replace_callback("/#([^#]*[^#^\s][^#]*)#/is",_parse_theme,$content);
    return $content;
}

function replaceUrl($content){
    //$content = preg_replace_callback('/((?:https?|ftp):\/\/(?:[a-zA-Z0-9][a-zA-Z0-9\-]*)*(?:\/[^\x{2e80}-\x{9fff}\s<\'\"“”‘’,，。]*)?)/u', '_parse_url', $content);
    $content = str_replace('[SITE_URL]', SITE_URL, $content);
    $content = preg_replace_callback('/((?:https?|mailto|ftp):\/\/([^\x{2e80}-\x{9fff}\s<\'\"“”‘’，。}]*)?)/u', '_parse_url', $content);
    return $content;
}


/**
 * 表情替換 [格式化微博與格式化評論專用]
 * @param array $data
 */
function _parse_expression($data) {
    if(preg_match("/#.+#/i",$data[0])) {
        return $data[0];
    }
    $allexpression = model('Expression')->getAllExpression();
    $info = $allexpression[$data[0]];
    if($info) {
        return preg_replace("/\[.+?\]/i","<img src='".__THEME__."/image/expression/miniblog/".$info['filename']."' />",$data[0]);
    }else {
        return $data[0];
    }
}

/**
 * 格式化微博,替換連結地址
 * @param string $url
 */
function _parse_url($url){
    $str = '<div class="url">';
    if ( preg_match("/(youku.com|youtube.com|ku6.com|sohu.com|mofile.com|sina.com.cn|tudou.com|yinyuetai.com)/i", $url[0] , $hosts) ){
        $str .= '<a href="'.$url[0].'" target="_blank" event-node="show_url_detail" class="ico-url-video"></a>';
    } else if ( strpos( $url[0] , 'taobao.com') ){
        $str .= '<a href="'.$url[0].'" target="_blank" event-node="show_url_detail" class="ico-url-taobao"></a>';
    } else {
        $str .= '<a href="'.$url[0].'" target="_blank" event-node="show_url_detail" class="ico-url-web"></a>';
    }
    $str .= '<div class="url-detail" style="display:none;">'.$url[0].'</div></div>';
    return $str;
}

/**
 * 話題替換 [格式化微博專用]
 * @param array $data
 * @return string
 */
function _parse_theme($data){
    //如果話題被鎖定，則不帶連結
    if(!model('FeedTopic')->where(array('name'=>$data[1]))->getField('lock')){
        return "<a href=".U('public/Topic/index',array('k'=>urlencode($data[1]))).">".$data[0]."</a>";
    }else{
        return $data[0];
    }
}

/**
 * 根據使用者昵稱獲取使用者ID [格式化微博與格式化評論專用]
 * @param array $name
 * @return string
 */
function _parse_at_by_uname($name) {
    $info = static_cache( 'user_info_uname_'.$name[1]);
    if ( !$info){
        $info = model( 'User')->getUserInfoByName($name[1]);
        if ( !$info ){
            $info = 1;
        }
        static_cache( 'user_info_uname_'.$name[1] , $info);
    }
    if ( $info && $info['is_active'] && $info['is_audit'] && $info['is_init'] ) {
        return '<a href="'.$info['space_url'].'" uid="'.$info['uid'].'" event-node="face_card" target="_blank">'.$name[0]."</a>";
    }else {
        return $name[0];
    }
}

/**
 * 解析at成web端顯示格式
 */
function _parse_at_by_uid($result){
    $_userInfo = explode("|",$result[1]);
    $userInfo = model('User')->getUserInfo($_userInfo[0]);
    return '<a uid="'.$userInfo['uid'].'" event-node="face_card" data="@{uid='.$userInfo['uid'].'|'.$userInfo['uname'].'}"
        href="'.$userInfo['space_url'].'">@'.$userInfo['uname'].'</a>';
}

function _parse_wap_at_by_uname($name) {
    $info = static_cache( 'user_info_uname_'.$name[1]);
    if ( !$info){
        $info = model( 'User')->getUserInfoByName($name[1]);
        if ( !$info ){
            $info = 1;
        }
        static_cache( 'user_info_uname_'.$name[1] , $info);
    }
    if ( $info && $info['is_active'] && $info['is_audit'] && $info['is_init'] ) {
        return '<a href="'.U('wap/Index/weibo',array('uid'=>$info['uid'])).'" >'.$name[0]."</a>";
    }else {
        return $name[0];
    }
}

/**
 * 解析at成api顯示格式
 */
function _parse_at_forapi($html){
    $_userInfo = explode("|",$html[1]);
    return "@".$_userInfo[1];
}

/**
 * 解析圖片成api格式
 */
function _parse_img_forapi($html){
    $basename = basename($html[1]);
    return "[".substr($basename,0, strpos($basename, "."))."]";
}

/**
 * 敏感詞過濾
 */
function filter_keyword($html){
    static $audit  =null;
    static $auditSet = null;
    if($audit == null){ //第一次
        $audit = model('Xdata')->get('keywordConfig');
        $audit = explode(',',$audit);
        $auditSet =  model('Xdata')->get('admin_Config:audit');
    }
    // 不需要替換
    if(empty($audit) || $auditSet['open'] == '0'){
        return $html;
    }
    return str_replace($audit, $auditSet['replace'], $html);
}

//檔名
/**
 * 獲取縮圖
 * @param unknown_type $filename 原圖路勁、url
 * @param unknown_type $width 寬度
 * @param unknown_type $height 高
 * @param unknown_type $cut 是否切割 默認不切割
 * @return string
 */
function getThumbImage($filename,$width=100,$height='auto',$cut=false,$replace=false){
    $filename  = str_ireplace(UPLOAD_URL,'',$filename); //將URL轉化為本地地址
    $info      = pathinfo($filename);
    $oldFile   = $info['dirname'].DIRECTORY_SEPARATOR.$info['filename'].'.'.$info['extension'];
    $thumbFile = $info['dirname'].DIRECTORY_SEPARATOR.$info['filename'].'_'.$width.'_'.$height.'.'.$info['extension'];

    $oldFile = str_replace('\\','/', $oldFile);
    $thumbFile = str_replace('\\','/',$thumbFile);

    $filename   = '/'.ltrim($filename,'/');
    $oldFile    = '/'.ltrim($oldFile,'/');
    $thumbFile  = '/'.ltrim($thumbFile,'/');

    //原圖不存在直接返回
    if(!file_exists(UPLOAD_PATH.$oldFile)){
        @unlink(UPLOAD_PATH.$thumbFile);
        $info['src']    = $oldFile;
        $info['width']  = intval($width);
        $info['height'] = intval($height);
        return $info;
        //縮圖已存在並且 replace替換為false
    }elseif(file_exists(UPLOAD_PATH.$thumbFile) && !$replace){
        $imageinfo      = getimagesize(UPLOAD_PATH.$thumbFile);
        $info['src']    = $thumbFile;
        $info['width']  = intval($imageinfo[0]);
        $info['height'] = intval($imageinfo[1]);
        return $info;
        //執行縮圖操作
    }else{
        $oldimageinfo     = getimagesize(UPLOAD_PATH.$oldFile);
        $old_image_width  = intval($oldimageinfo[0]);
        $old_image_height = intval($oldimageinfo[1]);
        if($old_image_width<=$width && $old_image_height<=$height){
            @unlink(UPLOAD_PATH.$thumbFile);
            @copy(UPLOAD_PATH.$oldFile,UPLOAD_PATH.$thumbFile);
            $info['src']    = $thumbFile;
            $info['width']  = $old_image_width;
            $info['height'] = $old_image_height;
            return $info;
        }else{
            tsload( ADDON_PATH.'/library/Image.class.php' );
            //生成縮圖
            if($cut){
                Image::cut(UPLOAD_PATH.$filename, UPLOAD_PATH.$thumbFile, $width, $height);
            }else{
                Image::thumb(UPLOAD_PATH.$filename, UPLOAD_PATH.$thumbFile, '', $width, $height);
            }
            //縮圖不存在
            if(!file_exists(UPLOAD_PATH.$thumbFile)){
                $thumbFile = $oldFile;
            }
            $info = Image::getImageInfo(UPLOAD_PATH.$thumbFile);
            $info['src'] = $thumbFile;
            return $info;
        }
    }
}

//獲取圖片資訊 - 相容雲
function getImageInfo($file){
    $cloud = model('CloudImage');
    if($cloud->isOpen()){
        $imageInfo = getimagesize($cloud->getImageUrl($file));
    }else{
        $imageInfo = getimagesize(UPLOAD_PATH.'/'.$file);
    }
    return $imageInfo;
}

//獲取圖片地址 - 相容雲
function getImageUrl($file,$width='0',$height='auto',$cut=false,$replace=false){
    $cloud = model('CloudImage');
    if($cloud->isOpen()){
        $imageUrl = $cloud->getImageUrl($file,$width,$height,$cut);
    }else{
        if($width>0){
            $thumbInfo = getThumbImage($file,$width,$height,$cut,$replace);
            $imageUrl = UPLOAD_URL.'/'.ltrim($thumbInfo['src'],'/');
        }else{
            $imageUrl = UPLOAD_URL.'/'.ltrim($file,'/');
        }
    }
    return $imageUrl;
}

//儲存遠端圖片
function saveImageToLocal($url){
    if(strncasecmp($url,'http',4)!=0){
        return false;
    }
    $opts = array(
        'http'=>array(
            'method' => "GET",
            'timeout' => 30, //超時30秒
            'user_agent'=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
        )
    );
    $context = stream_context_create($opts);
    $file_content = file_get_contents($url, false, $context);
    $file_path = date('/Y/md/H/');
    @mkdir(UPLOAD_PATH.$file_path,0777,true);
    $i = pathinfo($url);
    if(!in_array($i['extension'],array('jpg','jpeg','gif','png'))){
        $i['extension'] = 'jpg';
    }
    $file_name = uniqid().'.'.$i['extension'];

    //又拍雲端儲存
    $cloud = model('CloudImage');
    if($cloud->isOpen()){
        $res = $cloud->writeFile($file_path.$file_name,$file_content);
    }else{
        //本地存儲
        $res = file_put_contents(UPLOAD_PATH.$file_path.$file_name, $file_content);
    }

    if($res){
        return $file_path.$file_name;
    }else{
        return false;
    }
}

function getImageUrlByAttachId($attachid){
    if($attachInfo = model('Attach')->getAttachById($attachid)){
        return getImageUrl($attachInfo['save_path'].$attachInfo['save_name']);
    }else{
        return false;
    }
}
//獲取附件地址 - 相容雲
function getAttachUrl($filename){
    //雲端
    $cloud = model('CloudAttach');
    if($cloud->isOpen()){
        return  $cloud->getFileUrl($filename);
    }
    //本地
    if (file_exists ( UPLOAD_PATH . '/' . $filename )) {
        return UPLOAD_URL . '/' . $filename;
    } else {
        return '';
    }
}

function getAttachUrlByAttachId($attachid){
    if($attachInfo = model('Attach')->getAttachById($attachid)){
        return getAttachUrl($attachInfo['save_path'].$attachInfo['save_name']);
    }else{
        return false;
    }
}

function getSiteLogo($logoid = ''){
    if(empty($logoid)){
        $logoid = $GLOBALS['ts']['site']['site_logo'];
    }
    if($logoInfo = model('Attach')->getAttachById($logoid)){
        $logo = getImageUrl($logoInfo['save_path'].$logoInfo['save_name']);
    }else{
        $logo = THEME_PUBLIC_URL.'/'.C('site_logo');
    }
    return $logo;
}

//獲取當前訪問者的客戶端類型
function getVisitorClient(){
    //客戶端類型，0：網站；1：手機版；2：Android；3：iPhone；3：iPad；3：win.Phone
    return '0';
}

//獲取一條微博的來源資訊
function getFromClient($type=0, $app='public', $app_name){
    if ( $app != 'public' ){
        return '來自<a href="'.U($app).'" target="_blank">'.$app_name."</a>";
    }
    $type = intval($type);
    $client_type = array(
        0 => '來自網站',
        1 => '來自手機版',
        2 => '來自Android客戶端',
        3 => '來自iPhone客戶端',
        4 => '來自iPad客戶端',
        5 => '來自win.Phone客戶端',
    );

    //在列表中的
    if(in_array($type, array_keys( $client_type ))){
        return $client_type[$type];
    }else{
        return $client_type[0];
    }
}

/**
 * DES加密函數
 *
 * @param string $input
 * @param string $key
 */
function desencrypt($input,$key) {

    //使用新版的加密方式
    tsload(ADDON_PATH.'/library/DES_MOBILE.php');
    $desc = new DES_MOBILE();
    return $desc->setKey($key)->encrypt($input);
}

/**
 * DES解密函數
 *
 * @param string $input
 * @param string $key
 */
function desdecrypt($encrypted,$key) {
    //使用新版的加密方式
    tsload(ADDON_PATH.'/library/DES_MOBILE.php');
    $desc = new DES_MOBILE();
    return $desc->setKey($key)->decrypt($encrypted);
}


function getOAuthToken($uid){
    return md5( $uid . uniqid() );
}

function getOAuthTokenSecret(){
    return md5( time() . uniqid() );
}

// 獲取字串首字母
function getFirstLetter($s0) {
    $firstchar_ord = ord(strtoupper($s0{0}));
    if($firstchar_ord >= 65 and $firstchar_ord <= 91) return strtoupper($s0{0});
    if($firstchar_ord >= 48 and $firstchar_ord <= 57) return '#';
    $s = iconv("UTF-8", "gb2312", $s0);
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc>=-20319 and $asc<=-20284) return "A";
    if($asc>=-20283 and $asc<=-19776) return "B";
    if($asc>=-19775 and $asc<=-19219) return "C";
    if($asc>=-19218 and $asc<=-18711) return "D";
    if($asc>=-18710 and $asc<=-18527) return "E";
    if($asc>=-18526 and $asc<=-18240) return "F";
    if($asc>=-18239 and $asc<=-17923) return "G";
    if($asc>=-17922 and $asc<=-17418) return "H";
    if($asc>=-17417 and $asc<=-16475) return "J";
    if($asc>=-16474 and $asc<=-16213) return "K";
    if($asc>=-16212 and $asc<=-15641) return "L";
    if($asc>=-15640 and $asc<=-15166) return "M";
    if($asc>=-15165 and $asc<=-14923) return "N";
    if($asc>=-14922 and $asc<=-14915) return "O";
    if($asc>=-14914 and $asc<=-14631) return "P";
    if($asc>=-14630 and $asc<=-14150) return "Q";
    if($asc>=-14149 and $asc<=-14091) return "R";
    if($asc>=-14090 and $asc<=-13319) return "S";
    if($asc>=-13318 and $asc<=-12839) return "T";
    if($asc>=-12838 and $asc<=-12557) return "W";
    if($asc>=-12556 and $asc<=-11848) return "X";
    if($asc>=-11847 and $asc<=-11056) return "Y";
    if($asc>=-11055 and $asc<=-10247) return "Z";
    return '#';
}

// 區間偵錯開始
function debug_start($label=''){
    $GLOBALS[$label]['_beginTime'] = microtime(TRUE);
    $GLOBALS[$label]['_beginMem'] = memory_get_usage();
}

// 區間偵錯結束，顯示指定標記到當前位置的偵錯
function debug_end($label=''){
    $GLOBALS[$label]['_endTime'] = microtime(TRUE);
    $log =  'Process '.$label.': Times '.number_format($GLOBALS[$label]['_endTime']-$GLOBALS[$label]['_beginTime'],6).'s ';
    $GLOBALS[$label]['_endMem'] = memory_get_usage();
    $log .= ' Memories '.number_format(($GLOBALS[$label]['_endMem']-$GLOBALS[$label]['_beginMem'])/1024).' k';
    $GLOBALS['logs'][$label] = $log;
}

// 全站語言設定 - PHP
function setLang() {
    // 獲取當前系統的語言
    $lang = getLang();
    // 設定全站語言變數
    if(!isset($GLOBALS['_lang'])) {
        $GLOBALS['_lang'] = array();
        $_lang = array();
        if(file_exists(LANG_PATH.'/public_'.$lang.'.php')) {
            $_lang = include(LANG_PATH.'/public_'.$lang.'.php');
            $GLOBALS['_lang'] = array_merge($GLOBALS['_lang'], $_lang);
        }
        $removeApps = array('api', 'widget', 'public');
        if(!in_array(TRUE_APPNAME, $removeApps)) {
            if(file_exists(LANG_PATH.'/'.strtolower(TRUE_APPNAME).'_'.$lang.'.php')) {
                $_lang = include(LANG_PATH.'/'.strtolower(TRUE_APPNAME).'_'.$lang.'.php');
                $GLOBALS['_lang'] = array_merge($GLOBALS['_lang'], $_lang);
            }
        }
    }
}

//主動添加語言包
function addLang($appname){
    static $langHash = array();
    if(isset($langHash[$appname])){
        return true;
    }
    $langHash[$appname] = 1;
    $lang = getLang();
    if(file_exists(LANG_PATH.'/'.$appname.'_'.$lang.'.php')){
        $_lang = include(LANG_PATH.'/'.$appname.'_'.$lang.'.php');
        empty($_lang) && $_lang = array();
        $GLOBALS['_lang'] = array_merge($GLOBALS['_lang'], $_lang);
        return true;
    }
    return false;
}

// 全站語言設定 - JavaScript
function setLangJavsScript() {
    // 獲取當前系統的語言
    $lang = getLang();
    // 獲取相應要載入的JavaScript語言包路徑
    $langJsList = array();
    if(file_exists(LANG_PATH.'/public_'.$lang.'.js')) {
        $langJsList[] = LANG_URL.'/public_'.$lang.'.js';
    }
    $removeApps = array('api', 'widget', 'public');
    if(!in_array(TRUE_APPNAME, $removeApps)) {
        if(file_exists(LANG_PATH.'/'.strtolower(TRUE_APPNAME).'_'.$lang.'.js')) {
            $langJsList[] = LANG_URL.'/'.strtolower(TRUE_APPNAME).'_'.$lang.'.js';
        }
    }

    return $langJsList;
}

// 獲取站點所使用的語言
function getLang() {
    $defaultLang = 'zh-tw';
    $cLang = cookie('lang');
    $lang = '';
    // 判斷是否已經登入
    if(isset($_SESSION['mid']) && $_SESSION['mid']>0){
        $userInfo = model('User')->getUserInfo($_SESSION['mid']);
        if(isset($userInfo['lang'])){
            return $userInfo['lang'];
        }else{
            return '';
        }
    }
    // 是否存在cookie值，如果存在顯示默認的cookie語言值
    if(is_null($cLang)) {
        // 手機端直接返回默認語言
        if(!isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            return $defaultLang;
        }
        // 判斷作業系統的語言狀態
        $accept_language = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
        $accept_language = strtolower($accept_language);
        $accept_language_array = explode(',', $accept_language);
        $lang = array_shift($accept_language_array);
        // 獲取默認語言
        $fields = model('Lang')->getLangType();
        $lang = in_array($lang, $fields) ? $lang : $defaultLang;
        cookie('lang', $lang);
    } else {
        $lang = $cLang;
    }

    return $lang;
}

function ShowNavMenu($apps){
    $html = '';
    foreach($apps as $app){
        $child_menu = unserialize($app['child_menu']);
        if(empty($child_menu)){
            continue;
        }
        foreach($child_menu as $k=>$cm){

            if($k == $app['app_name']){
                //我的XXX
                $title = L('PUBLIC_MY').L('PUBLIC_APPNAME_'.strtoupper($k));
                $url = U($cm['url']);
            }else{
                //其他導航 一般不會有其他導航
                $title = L($k);
                //地址直接是cm值
                $url = U($cm);
            }

            $html .="<dd><a href='{$url}'>{$title}</a></dd>";
        }
    }
    return $html;
}

function showNavProfile($apps){
    $html = '';
    foreach($apps as $app){

        $child_menu = unserialize($app['child_menu']);

        if(empty($child_menu)){
            continue;
        }
        foreach($child_menu as $k=>$cm){

            if($k == $app['app_name'] && $cm['public'] == 1){
                //我的XXX 只會顯示這類資料
                $title = "<img width='16' src='{$app['icon_url']}'> ".L('PUBLIC_APPNAME_'.strtoupper($k));
                $url   = U('public/Profile/appprofile',array('appname'=>$k));
                $html .="<dd class='profile_{$app['app_name']}'><a href='{$url}'>{$title}</a></dd>";
            }
        }
    }
    return $html;
}

/**
 * 是否能進行邀請
 * @param integer $uid 使用者ID
 */
function isInvite(){
    $config = model('Xdata')->get('admin_Config:register');
    $result = false;
    if(in_array($config['register_type'], array('open', 'invite'))) {
        $result = true;
    }

    return $result;
}

/**
 * 傳統形式顯示無限極分類樹
 * @param array $data 樹形結構資料
 * @param string $stable 所操作的資料表
 * @param integer $left 樣式偏移
 * @param array $delParam 刪除關聯資訊參數，app、module、method
 * @param integer $level 添加子分類層級，默認為0，則可以添加無限子分類
 * @param integer $times 用於記錄遞迴層級的次數，默認為1，呼叫函數時，不需要傳入值。
 * @param integer $limit 分類限制字數。
 * @return string 樹形結構的HTML資料
 */
function showTreeCategory($data, $stable, $left, $delParam, $level = 0, $ext = '', $times = 1, $limit = 0) {
    $html = '<ul class="sort">';
    foreach($data as $val) {
        // 判斷是否有符號
        $isFold = empty($val['child']) ? false : true;
        $html .= '<li id="'.$stable.'_'.$val['id'].'" class="underline" style="padding-left:'.$left.'px;"><div class="c1">';
        if($isFold) {
            $html .= '<a href="javascript:;" onclick="admin.foldCategory('.$val['id'].')"><img id="img_'.$val['id'].'" src="'.__THEME__.'/admin/image/on.png" /></a>';
        }
        $html .= '<span>'.$val['title'].'</span></div><div class="c2">';
        if($level == 0 || $times < $level) {
            $html .= '<a href="javascript:;" onclick="admin.addTreeCategory('.$val['id'].', \''.$stable.'\', '.$limit.');">添加子分類</a>&nbsp;-&nbsp;';
        }
        $html .= '<a href="javascript:;" onclick="admin.upTreeCategory('.$val['id'].', \''.$stable.'\', '.$limit.');">編輯</a>&nbsp;-&nbsp;';
        if(empty($delParam)) {
            $html .= '<a href="javascript:;" onclick="admin.rmTreeCategory('.$val['id'].', \''.$stable.'\');">刪除</a>';
        } else {
            $html .= '<a href="javascript:;" onclick="admin.rmTreeCategory('.$val['id'].', \''.$stable.'\', \''.$delParam['app'].'\', \''.$delParam['module'].'\', \''.$delParam['method'].'\');">刪除</a>';
        }
        $ext !== '' && $html .= '&nbsp;-&nbsp;<a href="'.U('admin/Public/setCategoryConf', array('cid'=>$val['id'], 'stable'=>$stable)).'&'.$ext.'">分類配置</a>';
        $html .= '</div><div class="c3">';
        $html .= '<a href="javascript:;" onclick="admin.moveTreeCategory('.$val['id'].', \'up\', \''.$stable.'\')" class="ico_top mr5"></a>';
        $html .= '<a href="javascript:;" onclick="admin.moveTreeCategory('.$val['id'].', \'down\', \''.$stable.'\')" class="ico_btm"></a>';
        $html .= '</div></li>';
        if(!empty($val['child'])) {
            $html .= '<li id="sub_'.$val['id'].'" style="display:none;">';
            $html .= showTreeCategory($val['child'], $stable, $left + 15, $delParam, $level, $ext, $times + 1, $limit);
            $html .= '</li>';
        }
    }
    $html .= '</ul>';

    return $html;
}

/**
 * 格式化分類配置頁面參數為字元串
 * @param array $ext 配置頁面相關參數
 * @param array $defExt 預設值HASH陣列
 * @return string 格式化後的字元串
 */
function encodeCategoryExtra($ext, $defExt)
{
    $data = array();
    $i = 1;
    foreach ($ext as $key => $val) {
        if (is_array($val)) {
            $data['ext_'.$i] = $key;
            $data['arg_'.$i] = implode('-', $val);
            $data['def_'.$i] = $defExt[$key];
        } else {
            $data['ext_'.$i] = $val;
        }
        $i++;
    }
    // 處理資料
    $result = array();
    foreach ($data as $k => $v) {
        $result[] = $k.'='.$v;
    }

    return implode('&', $result);
}

/**
 * 返回解析空間地址
 * @param integer $uid 使用者ID
 * @param string $class 樣式類
 * @param string $target 是否進行跳轉
 * @param string $text 標籤內的相關內容
 * @param boolen $icon 是否顯示使用者組圖示，默認為true
 * @return string 解析空間地址HTML
 */
function getUserSpace($uid, $class, $target, $text, $icon = true)
{
    // 2.8轉移
    // 靜態變數
    static $_userinfo = array();
    // 判斷是否有快取
    if(!isset($_userinfo[$uid])) {
        $_userinfo[$uid] = model('User')->getUserInfo($uid);
    }
    // 配置相關參數
    empty($target) && $target = '_self';
    empty($text) && $text = $_userinfo[$uid]['uname'];
    // 判斷是否存在替換資訊
    preg_match('|{(.*?)}|isU', $text, $match);
    if($match) {
        if($match[1] == 'uname') {
            $text = str_replace('{uname}', $_userinfo[$uid]['uname'], $text);
            //empty($class) && $class = 'username';  //2013/2/28  wanghaiquan
            empty($class) && $class = 'name';
        } else {
            preg_match("/{uavatar}|{uavatar\\=(.*?)}/e", $text, $face_type);
            switch ($face_type[1]) {
            case 'b':
                $userface = 'big';
                break;
            case 'm':
                $userface = 'middle';
                break;
            default:
                $userface = 'small';
                break;
            }
            $face = $_userinfo[$uid]['avatar_'.$userface];
            $text = '<img src="'.$face.'" />';
            empty($class) && $class = 'userface';
            $icon = false;
        }
    }
    // 組裝返回資訊
    $user_space_info = '<a event-node="face_card" uid="'.$uid.'" href="'.$_userinfo[$uid]['space_url'].'" class="'.$class.'" target="'.$target.'">'.$text.'</a>';
    // 使用者認證圖示資訊
    if($icon) {
        $group_icon = array();
        $user_group = static_cache( 'usergrouplink_'.$uid );
        if ( !$user_group ){
            $user_group = model('UserGroupLink')->getUserGroupData($uid);
            static_cache( 'usergrouplink_'.$uid , $user_group );
        }
        if(!empty($user_group)) {
            foreach($user_group[$uid] as $value) {
                $group_icon[] = '<img title="'.$value['user_group_name'].'" src="'.$value['user_group_icon_url'].'" class="space-group-icon" />';
            }
            $user_space_info .= '&nbsp;'.implode('&nbsp;', $group_icon);
        }
    }

    return $user_space_info;
}

/**
 * 檢查是否是以手機瀏覽器進入(IN_MOBILE)
 */
function isMobile() {
    $mobile = array();
    static $mobilebrowser_list ='Mobile|iPhone|Android|WAP|NetFront|JAVA|OperasMini|UCWEB|WindowssCE|Symbian|Series|webOS|SonyEricsson|Sony|BlackBerry|Cellphone|dopod|Nokia|samsung|PalmSource|Xphone|Xda|Smartphone|PIEPlus|MEIZU|MIDP|CLDC';
    //note 獲取手機瀏覽器
    if(preg_match("/$mobilebrowser_list/i", $_SERVER['HTTP_USER_AGENT'], $mobile)) {
        return true;
    }else{
        if(preg_match('/(mozilla|chrome|safari|opera|m3gate|winwap|openwave)/i', $_SERVER['HTTP_USER_AGENT'])) {
            return false;
        }else{
            if($_GET['mobile'] === 'yes') {
                return true;
            }else{
                return false;
            }
        }
    }
}

function isiPhone()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== false;
}

function isiPad()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'iPad') !== false;
}

function isiOS()
{
    return isiPhone() || isiPad();
}

function isAndroid()
{
    return strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false;
}

/**
 * 獲取使用者瀏覽器型號。新加瀏覽器，修改程式碼，增加特徵字元串.把IE加到12.0 可以使用5-10年了.
 */
function getBrowser(){
    if (strpos($_SERVER['HTTP_USER_AGENT'], 'Maxthon')) {
        $browser = 'Maxthon';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 12.0')) {
        $browser = 'IE12.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 11.0')) {
        $browser = 'IE11.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 10.0')) {
        $browser = 'IE10.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 9.0')) {
        $browser = 'IE9.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.0')) {
        $browser = 'IE8.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.0')) {
        $browser = 'IE7.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.0')) {
        $browser = 'IE6.0';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'NetCaptor')) {
        $browser = 'NetCaptor';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Netscape')) {
        $browser = 'Netscape';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Lynx')) {
        $browser = 'Lynx';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Opera')) {
        $browser = 'Opera';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Chrome')) {
        $browser = 'Google';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Firefox')) {
        $browser = 'Firefox';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'Safari')) {
        $browser = 'Safari';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'iphone') || strpos($_SERVER['HTTP_USER_AGENT'], 'ipod')) {
        $browser = 'iphone';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'ipad')) {
        $browser = 'iphone';
    } elseif(strpos($_SERVER['HTTP_USER_AGENT'], 'android')) {
        $browser = 'android';
    } else {
        $browser = 'other';
    }
    return $browser;
}


/* TS2.X的相容方法 */
function safe($text){
    return h($text);
}

function text($text){
    return t($text);
}

function real_strip_tags($str, $allowable_tags="") {
    $str = stripslashes(htmlspecialchars_decode($str));
    return strip_tags($str, $allowable_tags);
}

function getUserFace($uid,$size){
    $userinfo = model('User')->getUserInfo($uid);
    switch ($size) {
    case 'b':
        $userface = $userinfo['avatar_big'];
        break;
    case 'm':
        $userface = $userinfo['avatar_middle'];
        break;
    default:
        $userface = $userinfo['avatar_small'];
        break;
    }
    return $userface;
}

function getUserName($uid){
    $userinfo = model('User')->getUserInfo($uid);
    return $userinfo['uname'];
}

function keyWordFilter($text){
    return filter_keyword($text);
}

function getFollowState($uid,$fid,$type=0) {
    if ($uid <= 0 || $fid <= 0)
        return 'unfollow';
    if ($uid==$fid)
        return 'unfollow';
    if (M('user_follow')->where("(uid=$uid AND fid=$fid) OR (uid=$fid AND fid=$uid)")->count() == 2) {
        return 'eachfollow';
    }else if ( M('user_follow')->where("uid=$uid AND fid=$fid")->count()) {
        return 'havefollow';
    }else {
        return 'unfollow';
    }
}

function matchImages($content = '') {
    $src = array ();
    preg_match_all ( '/<img.*src=(.*)[>|\\s]/iU', $content, $src );
    if (count ( $src [1] ) > 0) {
        foreach ( $src [1] as $v ) {
            $images [] = trim ( $v, "\"'" ); //刪除首尾的引號 ' "
        }
        return $images;
    } else {
        return false;
    }
}

function matchReplaceImages($content = ''){
    $image = preg_replace_callback('/<img.*src=(.*)[>|\\s]/iU',"matchReplaceImagesOnce",$content);
    return $image;
}

function matchReplaceImagesOnce($matches){
    $matches[1] = str_replace('"','',$matches[1]);
    return sprintf("<a class='thickbox'  href='%s'>%s</a>",$matches[1],$matches[0]);
}

//加密函數
function jiami($txt, $key = null) {
    if (empty ( $key ))
        $key = C ( 'SECURE_CODE' );
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $nh = rand ( 0, 64 );
    $ch = $chars [$nh];
    $mdKey = md5 ( $key . $ch );
    $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
    $txt = base64_encode ( $txt );
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for($i = 0; $i < strlen ( $txt ); $i ++) {
        $k = $k == strlen ( $mdKey ) ? 0 : $k;
        $j = ($nh + strpos ( $chars, $txt [$i] ) + ord ( $mdKey [$k ++] )) % 64;
        $tmp .= $chars [$j];
    }
    return $ch . $tmp;
}

//解密函數
function jiemi($txt, $key = null) {
    if (empty ( $key ))
        $key = C ( 'SECURE_CODE' );
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
    $ch = $txt [0];
    $nh = strpos ( $chars, $ch );
    $mdKey = md5 ( $key . $ch );
    $mdKey = substr ( $mdKey, $nh % 8, $nh % 8 + 7 );
    $txt = substr ( $txt, 1 );
    $tmp = '';
    $i = 0;
    $j = 0;
    $k = 0;
    for($i = 0; $i < strlen ( $txt ); $i ++) {
        $k = $k == strlen ( $mdKey ) ? 0 : $k;
        $j = strpos ( $chars, $txt [$i] ) - $nh - ord ( $mdKey [$k ++] );
        while ( $j < 0 )
            $j += 64;
        $tmp .= $chars [$j];
    }
    return base64_decode ( $tmp );
}


//******************************************************************************
// 轉移應用添加函數
/**
 +----------------------------------------------------------
 * 字元串擷取，支援中文和其它編碼
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要轉換的字元串
 * @param string $length 擷取長度
 * @param string $charset 編碼格式
 * @param string $suffix 截斷顯示字元
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
    function mStr($str, $length, $charset="utf-8", $suffix=true){
        return msubstr($str, 0, $length, $charset, $suffix);
    }
/**
 +----------------------------------------------------------
 * 字元串擷取，支援中文和其它編碼
 +----------------------------------------------------------
 * @static
 * @access public
 +----------------------------------------------------------
 * @param string $str 需要轉換的字元串
 * @param string $start 開始位置
 * @param string $length 擷取長度
 * @param string $charset 編碼格式
 * @param string $suffix 截斷顯示字元
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
    function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
        if(function_exists("mb_substr"))
            $slice = mb_substr($str, $start, $length, $charset);
        elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    if($suffix && $str != $slice) return $slice."...";
    return $slice;
}
// // 獲取給定使用者的使用者組圖示
// function getUserGroupIcon($uid){
//     static $_var = array();
//     if (!isset($_var[$uid]))
//         $_var[$uid] = model('UserGroup')->getUserGroupIcon($uid);

//     return $_var[$uid];
// }

/**
 * 檢查Email地址是否合法
 *
 * @return boolean
 */
    function isValidEmail($email) {
        return preg_match("/^[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
}
// 發送常用http header資訊
function send_http_header($type='utf8'){
    //utf8,html,wml,xml,圖片、文件類型 等常用header
    switch($type){
    case 'utf8':
        header("Content-type: text/html; charset=utf-8");
        break;
    case 'xml':
        header("Content-type: text/xml; charset=utf-8");
        break;
    }
}
/**
 * 判斷作者
 * @param unknown_type $dao
 * @param unknown_type $field
 * @param unknown_type $id
 * @param unknown_type $user
 * @return boolean
 */
    function CheckAuthorPermission( $dao , $id , $field='id' , $getfield='uid'){
        $map[$field] = $id;
        $value = $dao->where($map)->getField($getfield);
        return $value == $GLOBALS['ts']['mid'];
}
