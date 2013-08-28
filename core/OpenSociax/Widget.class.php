<?php
/**
 * ThinkSNS Widget類 抽象類
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>,liu21st <liu21st@gmail.com>
 * @version TS3.0 only
 */
abstract class Widget {

    // 使用的模板引擎 每個Widget可以單獨配置不受系統影響
    protected $template = '';
    protected $attr = array ();
    protected $cacheChecked = false;
    protected $mid;
    protected $uid;
    protected $user;
    protected $site;

    /**
     * 渲染輸出 render方法是Widget唯一的介面
     * 使用字元串返回 不能有任何輸出
     * @access public
     * @param mixed $data  要渲染的資料
     * @return string
     */
    abstract public function render($data);

    /**
     * 架構函數,處理核心變數
     * 使用字元串返回 不能有任何輸出
     * @access public
     * @return void
     */
    public function __construct(){

        //當前登入者uid
        $GLOBALS['ts']['mid'] = $this->mid =    intval($_SESSION['mid']);

        //當前訪問物件的uid
        $GLOBALS['ts']['uid'] = $this->uid =    intval($_REQUEST['uid']==0?$this->mid:$_REQUEST['uid']);

        // 賦值當前訪問者使用者
        $GLOBALS['ts']['user'] = $this->user = model('User')->getUserInfo($this->mid);
        if($this->mid != $this->uid){
            $GLOBALS['ts']['_user'] = model('User')->getUserInfo($this->uid);
        }else{
            $GLOBALS['ts']['_user'] = $GLOBALS['ts']['user'];
        }

        //當前使用者的所有已添加的應用
        $GLOBALS['ts']['_userApp']  = $userApp = model('UserApp')->getUserApp($this->uid);
        //當前使用者的統計資料
        $GLOBALS['ts']['_userData'] = $userData = model('UserData')->getUserData($this->uid);

        $this->site = D('Xdata')->get('admin_Config:site');
        $this->site['logo'] = getSiteLogo($this->site['site_logo']);
        $GLOBALS['ts']['site'] = $this->site;

        //語言包判斷
        if( TRUE_APPNAME != 'public' && APP_NAME != TRUE_APPNAME){
            addLang(TRUE_APPNAME);
        }
        Addons::hook('core_filter_init_widget');
    }

    /**
     * 渲染模板輸出 供render方法內部呼叫
     * @access public
     * @param string $templateFile  模板檔案
     * @param mixed $var  模板變數
     * @param string $charset  模板編碼
     * @return string
     */
    protected function renderFile($templateFile = '', $var = '', $charset = 'utf-8') {
        $var['ts'] = $GLOBALS['ts'];
        if (! file_exists_case ( $templateFile )) {
            // 自動定位模板檔案
            // $name = substr ( get_class ( $this ), 0, - 6 );
            // $filename = empty ( $templateFile ) ? $name : $templateFile;
            // $templateFile =   'widget/' . $name . '/' . $filename . C ( 'TMPL_TEMPLATE_SUFFIX' );
            // if (! file_exists_case ( $templateFile ))
            throw_exception ( L ( '_WIDGET_TEMPLATE_NOT_EXIST_' ) . '[' . $templateFile . ']' );
        }

        $template = $this->template ? $this->template : strtolower ( C ( 'TMPL_ENGINE_TYPE' ) ? C ( 'TMPL_ENGINE_TYPE' ) : 'php' );

        $content = fetch($templateFile,$var,$charset);

        return $content;
    }
}
?>
