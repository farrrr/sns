<?php
/**
 * ThinkSNS Action控制器基類
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
abstract class Action
{//類定義開始

    // 當前Action名稱
    private     $name =  '';
    protected   $tVar =  array();
    protected   $trace = array();
    protected   $templateFile = '';
    protected   $appCssList = array();
    protected   $langJsList = array();

    protected   $site = array();
    protected   $user = array();
    protected   $app = array();
    protected   $mid = 0;
    protected   $uid = 0;

    /**
     * 架構函數 取得模板對
     * @access public
     */
    public function __construct() {
        $this->initSite();
        $this->initUser();
        $this->initApp();
        Addons::hook('core_filter_init_action');
        //控制器初始化
        if(method_exists($this,'_initialize'))
            $this->_initialize();
    }

    /**
     * 站點資訊初始化
     * @access private
     * @return void
     */
    private function initSite() {

        //載入站點配置全局變數
        $this->site = model('Xdata')->get('admin_Config:site');

        if($this->site['site_closed'] == 0 && APP_NAME !='admin'){
            //TODO  跳轉到站點關閉頁面
            $this->page404($this->site['site_closed_reason']); exit();
        }

        //檢查是否啟用rewrite
        if(isset($this->site['site_rewrite_on'])){
            C('URL_ROUTER_ON',($this->site['site_rewrite_on']==1));
        }

        //初始化語言包
        $cacheFile = C('F_CACHE_PATH').'/initSiteLang.lock.php';
        if(!file_exists($cacheFile)){
            model('Lang')->initSiteLang();
        }

        //LOGO處理
        $this->site['logo'] = getSiteLogo($this->site['site_logo']);

        //默認登入後首頁
        if(intval($this->site['home_page'])){
            $appInfo = model('App')->where('app_id='.intval($this->site['home_page']))->find();
            $this->site['home_url'] = U($appInfo['app_name'].'/'.$appInfo['app_entry']);
        }else{
            $this->site['home_url'] = U('public/Index/index');
        }

        //賦值給全局變數
        $GLOBALS['ts']['site'] = $this->site;

        //網站導航
        $GLOBALS['ts']['site_top_nav'] = model('Navi')->getTopNav();
        $GLOBALS['ts']['site_bottom_nav'] = model('Navi')->getBottomNav();
        $GLOBALS['ts']['site_bottom_child_nav'] = model('Navi')->getBottomChildNav($GLOBALS['ts']['site_bottom_nav']);

        //獲取可搜索的內容列表
        if(false===($searchSelect=S('SearchSelect'))){
            $searchSelect = D('SearchSelect')->findAll();
            S('SearchSelect',$searchSelect);
        }

        //網站所有的應用
        $GLOBALS['ts']['site_nav_apps'] = model('App')->getAppList(array('status'=>1,'add_front_top'=>1),9);

        //網站全局變數過濾插件
        Addons::hook('core_filter_init_site');

        $this->assign('site', $this->site);
        $this->assign('site_top_nav', $GLOBALS['ts']['site_top_nav']);
        $this->assign('site_bottom_nav', $GLOBALS['ts']['site_bottom_nav']);
        $this->assign('site_bottom_child_nav',$GLOBALS['ts']['site_bottom_child_nav']);
        $this->assign('site_nav_apps', $GLOBALS['ts']['site_nav_apps']);
        $this->assign('menuList', $searchSelect);

        return true;
    }

    /**
     * 應用資訊初始化
     *
     * @access private
     * @return void
     */
    private function initApp() {
        //是否為核心的應用
        if(in_array(APP_NAME,C('DEFAULT_APPS'))){
            return true;
        }

        //載入後臺已安裝應用列表
        $GLOBALS['ts']['app'] = $this->app = model('App')->getAppByName(APP_NAME);

        if(empty($this->app) || !$this->app){
            $this->error('此應用不存在');
            return false;
        }
        if($this->app['status'] == 0){
            $this->error('此應用已經關閉');
            return false;
        }
        Addons::hook('core_filter_init_app');
        return true;
    }

    /**
     * 使用者資訊初始化
     * @access private
     * @return void
     */
    private function initUser() {
        // 邀請跳轉
        if(isset($_GET['invite']) && APP_NAME.'/'.MODULE_NAME!='public/Register') {
            redirect(U('public/Register/index', array('invite'=>t($_GET['invite']))));exit();
        }
        // 驗證登陸
        if (model('Passport')->needLogin()) {
            if(defined('LOGIN_URL')){
                redirect(LOGIN_URL);
            }else{
                if(APP_NAME == 'admin' ){
                    if(MODULE_NAME != "Public" && !model('Passport')->checkAdminLogin()){
                        redirect(U('admin/Public/login'));exit();
                    }
                }else{
                    redirect(U('public/Passport/login'));exit();
                }
            }
        }

        //判斷登入有效期
        /*
        $activeTime  = cookie('ST_ACTIVE_TIME');
        if($activeTime < time() && APP_NAME != 'admin' && ACTION_NAME !='login'){
            unset($_SESSION['mid']);
            cookie('TSV3_LOGGED_USER',null);
            $this->assign('jumpUrl',U('public/Passport/login'));
            $this->error(L('PUBLIC_TIME_OUT'));exit();
        }else{
            cookie('TSV3_ACTIVE_TIME',time()+60*60*24);
        }*/

        //當前登入者uid
        $GLOBALS['ts']['mid'] = $this->mid = intval($_SESSION['mid']);

        //當前訪問物件的uid
        $GLOBALS['ts']['uid'] = $this->uid = intval($_REQUEST['uid'] == 0 ? $this->mid : $_REQUEST['uid']);

        // 驗證站點訪問許可權
        // 驗證應用訪問許可權

        // 獲取使用者基本資料
        $GLOBALS['ts']['user'] = !empty($this->mid) ? $this->user = model('User')->getUserInfo($this->mid) : array();

        if($this->mid != $this->uid) {
            $GLOBALS['ts']['_user'] = !empty($this->uid) ? model('User')->getUserInfo($this->uid) : array();
        } else {
            $GLOBALS['ts']['_user'] = $GLOBALS['ts']['user'];
        }

        // 未初始化
        $module_arr= array('Register'=>1,'Passport'=>1,'Account'=>1);
        if (0 < $this->mid && 0 == $this->user ['is_init'] && APP_NAME != 'admin' && ! isset ( $module_arr [MODULE_NAME] )) {
            // 註冊完成後就開啟此功能
            if ($this->user ['is_active'] == '0') {
                U ( 'public/Register/waitForActivation', 'uid=' . $this->mid, true );
            } else {
                $init_config = model ( 'Xdata' )->get ( 'admin_Config:register' );
                if ($init_config ['photo_open']) {
                    U ( 'public/Register/step2', '', true );
                }
                if ($init_config ['tag_open']) {
                    U ( 'public/Register/step3', '', true );
                }
                if ($init_config ['interester_open']) {
                    U ( 'public/Register/step4', '', true );
                }
                model ( 'Register' )->overUserInit ( $GLOBALS ['ts'] ['mid'] );
                U ( 'public/Register/index', '', true );
            }
        }

        //應用許可權判斷
        if(!empty($this->app) && $this->app['status'] == 0){
            $this->error('此應用已經關閉');
        }
        if($this->uid>0){
            //當前使用者的所有已添加的應用
            // $GLOBALS['ts']['_userApp']  = $userApp =  model('UserApp')->getUserApp($this->uid);
            //當前使用者的統計資料
            $GLOBALS['ts']['_userData'] = $userData = model('UserData')->getUserData($this->uid);
            $userCredit = model('Credit')->getUserCredit($this->uid);
            $this->assign('userCredit',$userCredit);
            $this->assign('_userData',$userData);
            $this->assign('_userApp',$userApp);
        }

        // 獲取當前Js語言包
        $this->langJsList = setLangJavsScript();

        $this->assign('mid', $this->mid);   //登入者
        $this->assign('uid', $this->uid);   //訪問物件
        $this->assign('user', $this->user); //當前登陸的人

        $this->assign('initNums',model('Xdata')->getConfig('weibo_nums','feed'));
        Addons::hook('core_filter_init_user');
        return true;
    }

    /**
     * 重設訪問物件的使用者資訊 主要用於重寫等地方
     * @return void
     */
    public function reinitUser($uid=''){
        if(empty($uid) || $this->mid == $uid){
            return true;
        }

        $GLOBALS['ts']['uid'] = $_REQUEST['uid']  = $this->uid =    $uid;
        $GLOBALS['ts']['_user'] = model('User')->getUserInfo($this->uid);
        //當前使用者的所有已添加的應用
        $GLOBALS['ts']['_userApp']  = $userApp = model('UserApp')->getUserApp($this->uid);
        //當前使用者的統計資料
        $GLOBALS['ts']['_userData'] = $userData = model('UserData')->getUserData($this->uid);
        $userCredit = model('Credit')->getUserCredit($this->uid);

        $this->assign('uid', $this->uid);   //訪問物件
        $this->assign('_userData',$userData);
        $this->assign('_userApp',$userApp);
        $this->assign('userCredit',$userCredit);
    }

    /**
     * 魔術方法 有不存在的操作的時候
     * @access public
     * @param string $method 方法名
     * @param array $parms
     * @return mix
     */
    public function __call($method,$parms) {
        if( 0 === strcasecmp($method,ACTION_NAME)) {
            // 檢查擴展操作方法
            $_action = C('_actions_');
            if($_action) {
                // 'module:action'=>'callback'
                if(isset($_action[MODULE_NAME.':'.ACTION_NAME])) {
                    $action  =  $_action[MODULE_NAME.':'.ACTION_NAME];
                }elseif(isset($_action[ACTION_NAME])){
                    // 'action'=>'callback'
                    $action  =  $_action[ACTION_NAME];
                }
                if(!empty($action)) {
                    call_user_func($action);
                    return ;
                }
            }
            // 如果定義了_empty操作 則呼叫
            if(method_exists($this,'_empty')) {
                $this->_empty($method,$parms);
            }else {
                // 檢查是否存在默認模版 如果有直接輸出模版
                $this->display();
            }
        }elseif(in_array(strtolower($method),array('ispost','isget','ishead','isdelete','isput'))){
            return strtolower($_SERVER['REQUEST_METHOD']) == strtolower(substr($method,2));
        }else{
            throw_exception(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
        }
    }

    /**
     * 模板Title
     * @access public
     * @param mixed $input 要
     * @return
     */
    public function setTitle($title = '') {
        Addons::hook('core_filter_set_title', $title);
        $this->assign('_title',$title);
    }

    /**
     * 模板keywords
     * @access public
     * @param mixed $input 要
     * @return
     */
    public function setKeywords($keywords = '') {
        $this->assign('_keywords',$keywords);
    }

    /**
     * 模板description
     * @access public
     * @param mixed $input 要
     * @return
     */
    public function setDescription($description = '') {
        $this->assign('_description',$description);
    }

    /**
     * 模板變數賦
     * @access protected
     * @param mixed $name 要顯示的模板變數
     * @param mixed $value 變數的
     * @return void
     */
    public function assign($name,$value='') {
        if(is_array($name)) {
            $this->tVar   =  array_merge($this->tVar,$name);
        }elseif(is_object($name)){
            foreach($name as $key =>$val)
                $this->tVar[$key] = $val;
        }else {
            $this->tVar[$name] = $value;
        }
    }

    /**
     * 魔術方法：註冊模版變數
     * @access protected
     * @param string $name 模版變數
     * @param mix $value 變數值
     * @return mixed
     */
    public function __set($name,$value) {
        $this->assign($name,$value);
    }

    /**
     * 取得模板顯示變數的值
     * @access protected
     * @param string $name 模板顯示變數
     * @return mixed
     */
    protected function get($name) {
        if(isset($this->tVar[$name]))
            return $this->tVar[$name];
        else
            return false;
    }

    /**
     * Trace變數賦值
     * @access protected
     * @param mixed $name 要顯示的模板變數
     * @param mixed $value 變數的值
     * @return void
     */
    protected function trace($name,$value='') {
        if(is_array($name))
            $this->trace   =  array_merge($this->trace,$name);
        else
            $this->trace[$name] = $value;
    }

    /**
     * 模板顯示
     * 呼叫內建的模板引擎顯示方法
     * @access protected
     * @param string $templateFile 指定要呼叫的模板檔案
     * 默認為空 由系統自動定位模板檔案
     * @param string $charset 輸出編碼
     * @param string $contentType 輸出類
     * @return voi
     */
    protected function display($templateFile='',$charset='utf-8',$contentType='text/html') {
        echo $this->fetch($templateFile,$charset,$contentType,true);
    }

    /**
     *  獲取輸出頁面內容
     * 呼叫內建的模板引擎fetch方法
     * @access protected
     * @param string $templateFile 指定要呼叫的模板檔案
     * 默認為空 由系統自動定位模板檔案
     * @param string $charset 輸出編碼
     * @param string $contentType 輸出類
     * @return strin
     */
    protected function fetch($templateFile='',$charset='utf-8',$contentType='text/html',$display=false) {
        $this->assign('appCssList',$this->appCssList);
        $this->assign('langJsList', $this->langJsList);
        Addons::hook('core_display_tpl', array('tpl'=>$templateFile,'vars'=>$this->tVar,'charset'=>$charset,'contentType'=>$contentType,'display'=>$display));
        return fetch($templateFile, $this->tVar, $charset, $contentType, $display);
    }

    /**
     * 操作錯誤跳轉的快捷方
     * @access protected
     * @param string $message 錯誤資訊
     * @param Boolean $ajax 是否為Ajax方
     * @return voi
     */
    protected function error($message,$ajax=false) {
        Addons::hook('core_filter_error_message', $message);
        $this->_dispatch_jump($message,0,$ajax);
    }

    protected function page404($message){
        $this->assign('site_closed',$this->site['site_closed']);
        $this->assign('message',$message);
        $this->display(THEME_PATH.'/page404.html');
    }
    /**
     * 操作成功跳轉的快捷方
     * @access protected
     * @param string $message 提示資訊
     * @param Boolean $ajax 是否為Ajax方
     * @return voi
     */
    protected function success($message,$ajax=false) {
        Addons::hook('core_filter_success_message', $message);
        $this->_dispatch_jump($message,1,$ajax);
    }

    /**
     * Ajax方式返回資料到客戶端
     * @access protected
     * @param mixed $data 要返回的資料
     * @param String $info 提示資訊
     * @param boolean $status 返回狀態
     * @param String $status ajax返回類型 JSON XML
     * @return void
     */
    protected function ajaxReturn($data,$info='',$status=1,$type='JSON') {
        // 保證AJAX返回後也能儲存日誌
        if(C('LOG_RECORD')) Log::save();
        $result  =  array();
        $result['status']  =  $status;
        $result['info'] =  $info;
        $result['data'] = $data;
        if(empty($type)) $type  =   C('DEFAULT_AJAX_RETURN');
        if(strtoupper($type)=='JSON') {
            // 返回JSON資料格式到客戶端 包含狀態資訊
            header("Content-Type:text/html; charset=utf-8");
            exit(json_encode($result));
        }elseif(strtoupper($type)=='XML'){
            // 返回xml格式資料
            header("Content-Type:text/xml; charset=utf-8");
            exit(xml_encode($result));
        }elseif(strtoupper($type)=='EVAL'){
            // 返回可執行的js指令碼
            header("Content-Type:text/html; charset=utf-8");
            exit($data);
        }else{
            // TODO 增加其它格式
        }
    }

    /**
     * Action跳轉(URL重定向） 支援指定模組和延時跳轉
     * @access protected
     * @param string $url 跳轉的URL表示式
     * @param array $params 其它URL參數
     * @param integer $delay 延時跳轉的時間 單位為秒
     * @param string $msg 跳轉提示資訊
     * @return void
     */
    protected function redirect($url,$params=array(),$delay=0,$msg='') {
        if(C('LOG_RECORD')) Log::save();
        $url    =   U($url,$params);
        redirect($url,$delay,$msg);
    }

    /**
     * 默認跳轉操作 支援錯誤導向和正確跳轉
     * 呼叫模板顯示 默認為public目錄下面的success頁面
     * 提示頁面為可配置 支援模板標籤
     * @param string $message 提示資訊
     * @param Boolean $status 狀態
     * @param Boolean $ajax 是否為Ajax方式
     * @access private
     * @return void
     */
    private function _dispatch_jump($message,$status=1,$ajax=false) {
        // 判斷是否為AJAX返回
        if($ajax || $this->isAjax()) {
            $data['jumpUrl'] = false;
            if($this->get('jumpUrl')){
                $data['jumpUrl'] = $this->get('jumpUrl');
}
$this->ajaxReturn($data,$message,$status);
}
// 提示標題
$this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
//如果設定了關閉視窗，則提示完畢後自動關閉視窗
if($this->get('closeWin'))    $this->assign('jumpUrl','javascript:window.close();');
$this->assign('status',$status);   // 狀態
empty($message) && ($message = $status==1?'操作成功':'操作失敗');
$this->assign('message',$message);// 提示資訊
//保證輸出不受靜態快取影響
C('HTML_CACHE_ON',false);
if($status) { //發送成功資訊
    // 成功操作後默認停留1秒
    if(!$this->get('waitSecond'))    $this->assign('waitSecond',"2");
    // 默認操作成功自動返回操作前頁面
    if(!$this->get('jumpUrl')) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);
    //sociax:2010-1-21
    //$this->display(C('TMPL_ACTION_SUCCESS'));
    $this->display(THEME_PATH.'/success.html');
}else{
    //發生錯誤時候默認停留3秒
    if(!$this->get('waitSecond'))    $this->assign('waitSecond',"5");
    // 默認發生錯誤的話自動返回上頁
    if(!$this->get('jumpUrl')) $this->assign('jumpUrl',"javascript:history.back(-1);");
    //sociax:2010-1-21
    //$this->display(C('TMPL_ACTION_ERROR'));

    $this->display(THEME_PATH.'/success.html');
}
if(C('LOG_RECORD')) Log::save();
// 中止執行  避免出錯後繼續執行
exit ;
}

/**
 * 是否AJAX請求
 * @access protected
 * @return bool
 */
protected function isAjax() {
    if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) ) {
        if('xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH']))
            return true;
}
if(!empty($_POST[C('VAR_AJAX_SUBMIT')]) || !empty($_GET[C('VAR_AJAX_SUBMIT')]))
    // 判斷Ajax方式提交
    return true;
return false;
}
};//類定義結束
