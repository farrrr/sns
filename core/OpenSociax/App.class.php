<?php
/**
 * ThinkSNS App基類
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class App
{
    /**
     * App初始化
     * @access public
     * @return void
     */
    static public function init() {
        // 設定錯誤和異常處理
        set_error_handler(array('App','appError'));
        set_exception_handler(array('App','appException'));
        // Session初始化
        if(!session_id())
            session_start();
        // 時區檢查
        date_default_timezone_set('Asia/Taipei');
        // 模版檢查
    }

    /**
     * 運行控制器
     * @access public
     * @return void
     */
    static public function run() {

        App::init();

        //檢查伺服器是否開啟了zlib拓展
        //if(extension_loaded('zlib') && function_exists('ob_gzhandler')){
        //ob_start('ob_gzhandler');
        //}

        //API控制器
        if(APP_NAME=='api'){
            App::execApi();

            //Widget控制器
        }elseif(APP_NAME=='widget'){
            App::execWidget();

            //Plugin控制器
        }elseif(APP_NAME=='plugin'){
            App::execPlugin();

            //APP控制器
        }else{
            App::execApp();
        }

        if(C('LOG_RECORD')){
            Log::save();
        }

        //輸出buffer中的內容，即壓縮後的css檔案
        //if(extension_loaded('zlib') && function_exists('ob_gzhandler')){
        //ob_end_flush();
        //}
        return ;
    }

    /**
     * 執行App控制器
     * @access public
     * @return void
     */
    static public function execApp() {

        //防止CSRF
        if(strtoupper($_SERVER['REQUEST_METHOD'])=='POST' && stripos($_SERVER['HTTP_REFERER'], SITE_URL) !== 0 && $_SERVER['HTTP_USER_AGENT'] !== 'Shockwave Flash') {
            die('illegal request.');
        }

        // 使用手持裝置時, 對使用者的訪問默認跳轉至移動版, 除非使用者指定訪問普通版
        //if (APP_NAME!='wap' && $_SESSION['wap_to_normal'] != '1' && cookie('wap_to_normal') != '1' && $_REQUEST['wap_to_normal'] != '1') {
        // 根據各應用的配置來判斷是否存在手機版訪問配置檔案

        // if (MODULE_NAME == 'Public' && ACTION_NAME == 'tryOtherLogin')
        //     ;
        // else if (MODULE_NAME == 'Widget' && ACTION_NAME == 'addonsRequest')
        //     ;
        // // else if (isiPhone() || isAndroid()) // iOS和Android跳轉至3G版
        // //     U('w3g/Index/index', '', true);
        // else
        //if (isMobile() && !isiPad() && model( 'App' )->isAppNameOpen('wap') ) // 其他手機跳轉至WAP版
        //U('wap/Index/index', '', true);
        //}

        // 載入所有插件
        if(C('APP_PLUGIN_ON')) {
            tsload(CORE_LIB_PATH.'/addons.class.php');
            tsload(CORE_LIB_PATH.'/addons/Hooks.class.php');
            tsload(CORE_LIB_PATH.'/addons/AbstractAddons.class.php');
            tsload(CORE_LIB_PATH.'/addons/NormalAddons.class.php');
            tsload(CORE_LIB_PATH.'/addons/SimpleAddons.class.php');
            tsload(CORE_LIB_PATH.'/addons/TagsAbstract.class.php');
            Addons::loadAllValidAddons();
        }

        //創建Action控制器例項
        $className =  MODULE_NAME.'Action';
        tsload(APP_ACTION_PATH.'/'.$className.'.class.php');

        if(!class_exists($className)) {

            $className  =   'EmptyAction';
            tsload(APP_ACTION_PATH.'/EmptyAction.class.php');
            if(!class_exists($className)){
                throw_exception( L('_MODULE_NOT_EXIST_').' '.MODULE_NAME );
            }
        }

        $module =   new $className();

        //異常處理
        if(!$module) {
            // 模組不存在 拋出異常
            throw_exception( L('_MODULE_NOT_EXIST_').' '.MODULE_NAME );
        }

        //獲取當前操作名
        $action =   ACTION_NAME;

        //執行當前操作
        call_user_func(array(&$module,$action));

        //執行計劃任務
        model('Schedule')->run();
        return ;
    }

    /**
     * 執行Api控制器
     * @access public
     * @return void
     */
    static public function execApi() {
        include_once (ADDON_PATH.'/api/'.MODULE_NAME.'Api.class.php');
        $className = MODULE_NAME.'Api';
        $module = new $className;
        $action = ACTION_NAME;
        //執行當前操作
        $data = call_user_func(array(&$module,$action));
        $format = (in_array( $_REQUEST['format'] ,array('xml','json','php','test') ) ) ?$_REQUEST['format']:'json';
        if($format=='json'){
            exit(json_encode($data));
        }elseif ($format=='xml'){

        }elseif($format=='php'){
            //輸出php格式
            exit(var_export($data));
        }elseif($format=='test'){
            //測試輸出
            dump($data);
            exit;
        }
        return ;
    }

    /**
     * 執行Widget控制器
     * @access public
     * @return void
     */
    static public function execWidget() {

        //防止CSRF
        if(strtoupper($_SERVER['REQUEST_METHOD'])=='POST' && stripos($_SERVER['HTTP_REFERER'], SITE_URL)!==0) {
            die('illegal request.');
        }

        //include_once (ADDON_PATH.'/widget/'.MODULE_NAME.'Widget/'.MODULE_NAME.'Widget.class.php');
        //$className = MODULE_NAME.'Widget';

        if(file_exists(ADDON_PATH.'/widget/'.MODULE_NAME.'Widget/'.MODULE_NAME.'Widget.class.php')){
            tsload(ADDON_PATH.'/widget/'.MODULE_NAME.'Widget/'.MODULE_NAME.'Widget.class.php');
        }else{

            if(file_exists(APP_PATH.'/Lib/Widget/'.MODULE_NAME.'Widget/'.MODULE_NAME.'Widget.class.php')){
                tsload(APP_PATH.'/Lib/Widget/'.MODULE_NAME.'Widget/'.MODULE_NAME.'Widget.class.php');
            }
        }
        $className = MODULE_NAME.'Widget';

        $module =   new $className();

        //異常處理
        if(!$module) {
            // 模組不存在 拋出異常
            throw_exception( L('_MODULE_NOT_EXIST_').MODULE_NAME );
    }

    //獲取當前操作名
    $action =   ACTION_NAME;

    //執行當前操作
    if($rs = call_user_func(array(&$module,$action))){
        echo $rs;
    }
    return ;
    }

    /**
     * app異常處理
     * @access public
     * @return void
     */
    static public function appException($e) {
        die('system_error:'.$e->__toString());
    }

    /**
     * 自定義錯誤處理
     * @access public
     * @param int $errno 錯誤類型
     * @param string $errstr 錯誤資訊
     * @param string $errfile 錯誤檔案
     * @param int $errline 錯誤行數
     * @return void
     */
    static public function appError($errno, $errstr, $errfile, $errline) {
        switch ($errno) {
        case E_ERROR:
        case E_USER_ERROR:
            $errorStr = "[$errno] $errstr ".basename($errfile)." 第 $errline 行.";
            //if(C('LOG_RECORD')) Log::write($errorStr,Log::ERR);
            echo $errorStr;
            break;
        case E_STRICT:
        case E_USER_WARNING:
        case E_USER_NOTICE:
        default:
            $errorStr = "[$errno] $errstr ".basename($errfile)." 第 $errline 行.";
            //Log::record($errorStr,Log::NOTICE);
            break;
    }
    }

    };//類定義結束
