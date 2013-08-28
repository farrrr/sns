<?php
/**
 * 後臺框架基類
 *
 *
 * @author jason
 */
class AdministratorAction extends Action {

    /**
     * 頁面欄位列表
     *
     * @var array
     */
    protected $pageKeyList = array();

    /**
     * 針對搜索 或者 頁面欄位的額外屬性
     *
     * @var array
     */
    protected $opt = array();

    /**
     * 搜索的欄位
     *
     * @var array
     */
    protected $searchKey = array();

    /**
     * 頁面欄位配置存在system_data表中的頁面唯一key值
     *
     * @var string
     */
    protected $pageKey = '';

    /**
     * 頁面搜索配置存在system_data表中的頁面唯一key值
     *
     * @var string
     */
    protected $searchPageKey = '';

    /**
     * 默認的配置頁面儲存地址
     *
     * @var string
     */
    protected $savePostUrl = '';

    /**
     * 搜索提交地址
     *
     * @var string
     */

    protected $searchPostUrl = '';

    /**
     * 配置頁面的值在system_data表中的對應list值
     *
     * @var string
     */
    protected $systemdata_list = '';

    /**
     * 配置頁面的值在system_data表中對應的key值
     *
     * @var string
     */
    protected $systemdata_key = '';

    /**
     * 列表頁的TAB切換項
     * 例子 : $this->pageTab[] = array('title'=>'邀請列表','tabHash'=>'list','url'=>U('admin/Home/invatecount'));
     * @var array
     */
    protected $pageTab = array();

    /**
     * 列表頁在分頁欄的按鈕
     * 例子：$this->pageButton[] = array('title'=>'搜索','onclick'=>"admin.fold('search_form')");
     * @var array
     */
    protected $pageButton = array();
    /**
     * 列表頁是否有全選項
     *
     * @var bool
     */
    protected $allSelected = true;

    /**
     * 列表中的主鍵欄位
     *
     * @var unknown_type
     */
    protected $_listpk = 'id';
    /**
     * 頁面載入時需要執行的JS列表 （直接函數名）
     * 如：$onload[] = "admin.test()";
     *
     */
    protected $onload = array();

    /**
     * 提交時候需要進行的驗證js函數
     */
    protected $onsubmit = '';

    /**
     * 不能為空的欄位
     */
    protected $notEmpty = array();

    protected $navList = array();

    protected $submitAlias = '儲存';

    public function _initialize()
    {
        if(!model('Passport')->checkAdminLogin()){
            redirect(U('admin/Public/login'));
        }
        $this->systemdata_list = APP_NAME.'_'.MODULE_NAME;
        $this->systemdata_key  = ACTION_NAME;
        $this->pageKey = APP_NAME.'_'.MODULE_NAME.'_'.ACTION_NAME;
        $this->searchPageKey = 'S_'.APP_NAME.'_'.MODULE_NAME.'_'.ACTION_NAME;
        $this->savePostUrl = U('admin/Index/saveConfigData');
        $this->searchPostUrl = U(APP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME);
        $this->submitAlias = L('PUBLIC_SAVE');
        $this->assign('isAdmin',1);
        $this->onload[] = 'admin.bindTrOn()';
        $this->getSearchPost(); //默認初始化post查詢

        if(!CheckPermission('core_admin','admin_login')){
            $this->assign('jumpUrl',SITE_URL);
            $this->error(L('PUBLIC_NO_FRONTPLATFORM_PERMISSION_ADMIN'));
        }
        $this->navList = model('Xdata')->get('admin_nav:top');
    }

    /**
     * 初始化查詢時post值
     *
     */
    public function getSearchPost(){

        $init = empty($_POST) ? true : false;

        if(!empty($_POST)){
            $_SESSION['admin_init_post'][$this->searchPageKey] = $_POST;
        }else{
            $_POST = $_SESSION['admin_init_post'][$this->searchPageKey];
        }

        //去除其他頁面的session資料
        foreach($_SESSION['admin_init_post'] as $k=>$v){
            if( $k != $this->searchPageKey ){
                unset($_SESSION['admin_init_post'][$k]);
            }else{
                if($init && intval($_REQUEST['p']) == 0){
                    unset($_POST);
                    unset($_SESSION['admin_init_post'][$k]);
                }
            }
        }

        return $_POST;
    }

    public function setSearchPost($data){
        $_SESSION['admin_init_post'][$this->searchPageKey] = $data;
    }


    /**
     * 顯示配置詳細頁面
     *
     */
    public function displayConfig($detailData = false){

        //頁面Key配置儲存的值
        $this->_assignPageKeyData($detailData);

        $this->display(THEME_PATH.'/admin_config.html');
    }


    /**
     * 顯示列表頁面
     */
    public function displayList($listData=array()){
        //搜索部分設定
        if(!empty($this->searchKey)){
            $searchKeyData = model('Xconfig')->pagekey_get('searchPageKey:'.$this->searchPageKey);
            $this->assign('searchKeyData',$searchKeyData);
            $this->assign('searchKeyList',$this->searchKey);
        }
        $this->assign('searchPageKey',$this->searchPageKey);

        $this->assign('searchPostUrl',$this->searchPostUrl);
        $this->assign('searchData',$this->getSearchPost());
        //頁面key配置儲存的資料
        $this->_assignPageKeyData();

        //頁面資料
        $this->assign('listData',$listData);
        $this->assign('pageButton',$this->pageButton);
        $this->assign('_listpk',$this->_listpk);
        $this->assign('allSelected',$this->allSelected);
        $this->display(THEME_PATH.'/admin_list.html');
    }

    /**
     *
     *  顯示分類頁面
     *
     */
    public function displayCateTree($tree = array()){

        //資料儲存動作提交的地址
        $this->onload[] = "admin.bindCatetree()";
        //頁面Key配置儲存的值
        $pageKeyData = model('Xconfig')->pagekey_get('pageKey:'.$this->pageKey);

        $this->assign('pageKeyData',$pageKeyData);

        $this->assign('tree',$tree['_child']);

        $this->display(THEME_PATH.'/admin_catetree.html');
    }

    /**
     * 現實分類頁面
     * @param array $tree 樹形結構資料
     * @param string $stable 資源表明
     * @param integer $level 子分類添加層級數目，默認為0（無限極）
     * @param array $delParam 刪除關聯資料模型參數，app、module、method
     * @param array $extra 附加配置資訊欄位，欄位間使用|分割，欄位的屬性用-分割。例：attach|type-是-否|is_audit
     * @return string HTML頁面資料
     */
    public function displayTree($tree = array(), $stable = null, $level = 0, $delParam = null, $extra = '', $limit = 0)
    {
        $this->assign('stable', $stable);
        if(!isset($delParam['module']) || !isset($delParam['method'])) {
            $delParam = null;
        }
        $this->assign('delParam', $delParam);
        $this->assign('tree', $tree);
        $this->assign('level', $level);
        $this->assign('extra', $extra);
        $this->assign('limit', $limit);
        $this->display(THEME_PATH.'/admin_tree.html');
    }

    private function _assignPageKeyData($detailData = false){

        $pageKeyData = model('Xconfig')->pagekey_get('pageKey:'.$this->pageKey);

        $this->assign('pageKeyData',$pageKeyData);


        if($detailData === false){
            $detailData = model('Xdata')->get($this->systemdata_list.":".$this->systemdata_key);
        }

        $this->assign('detailData',$detailData);
    }
    /*
     * *
     * 儲存頁面配置資訊
     *
     */
    public function savePageConfig(){

        //TODO 儲存許可權判斷
        $key = t($_POST['pageKey']);
        $title = t($_POST['pageTitle']);
        unset($_POST['pageKey'],$_POST['pageTitle']);
        if(!isset($_POST['key'])){
            $this->error();exit();
        }
        // 儲存成KEY=>VALUE形式
        $keyArr = $_POST['key'];
        foreach($_POST as &$v){
            $v = $this->setKVArr($v,$keyArr);
        }
        $data[$key]  = $_POST;

        if(model('Xconfig')->pageKey_lput('pageKey',$data)){
            LogRecord('admin_config','editPagekey',array('name'=>$title,'k1'=>L('PUBLIC_ADMIN_EDIT_PEIZHI')),true);
            $this->success();
        }else{
            $this->error();
        }
    }
    /**
     * 修正資料格式 -- 僅開發階段使用
     * Enter description here ...
     */
    public function createData(){
        $sql = "select * from ".C('DB_PREFIX')."system_data where list = 'pageKey' or list = 'searchPageKey'";
        $list = D('')->query($sql);
        foreach($list as $v){
            $v['value'] = unserialize($v['value']);
            $keyArr = $v['value']['key'];
            foreach($v['value'] as &$vv){
                $vv = $this->setKVArr($vv, $keyArr);
            }
            $map = array();
            $map['id'] = $v['id'];
            unset($v['id']);
            $v['value'] = serialize($v['value']);
            $save = $v;
            D('system_data')->where($map)->save($v);
            echo $v['list'],':',$v['key'],' is OK!<br/>';
        }
    }
    //設定陣列key=》value形式
    private function setKVArr($arr,$keyList){
        $r = array();
        foreach($arr as $k=>$v){
            $key = is_array($keyList[$k]) ? $keyList[$k][0] : $keyList[$k];
            $r[$key] = $v;
        }
        return $r;
    }

    public function saveSearchConfig(){
        $key = $_POST['searchPageKey'];
        $title = $_POST['pageTitle'];
        unset($_POST['searchPageKey'],$_POST['pageTitle']);
        // 儲存成KEY=>VALUE形式
        $keyArr = $_POST['key'];
        foreach($_POST as &$v){
            $v = $this->setKVArr($v,$keyArr);
        }
        $data[$key]  = $_POST;

        if(model('Xconfig')->pageKey_lput('searchPageKey',$data)){
            LogRecord('admin_config','editSearchPagekey',array('name'=>$title,'k1'=>L('PUBLIC_ADMIN_EDIT_PEIZHI')),true);
            $this->success();
        }else{
            $this->error();
        }
    }

    /**
     * 儲存配置頁面詳細資料
     * @return void
     */
    public function saveConfigData() {
        if(empty($_POST['systemdata_list']) || empty($_POST['systemdata_key'])){
            $this->error(L('PUBLIC_SAVE_FAIL'));            // 儲存失敗
        }
        $key = t($_POST['systemdata_list']).":".t($_POST['systemdata_key']);
        $title = t($_POST['pageTitle']);
        unset($_POST['systemdata_list'], $_POST['systemdata_key'], $_POST['pageTitle']);
        //rewrite驗證.
        if(isset($_POST['site_rewrite_on']) && $_POST['site_rewrite_on']==1){
            $rewrite_test_content = file_get_contents(SITE_URL.'/rewrite');
            if($rewrite_test_content!='thinksns'){
                $this->error('伺服器設定不支援Rewrite，請檢查配置');
            }
        }
        if(isset($_POST['site_analytics_code'])){
            $_POST['site_analytics_code'] = base64_encode($_POST['site_analytics_code']);
        }
        if(isset($_POST['site_theme_name']) && $_POST['site_theme_name']!=C('THEME_NAME')){
            $res = $this->_switchTheme( t($_POST['site_theme_name']) );
        }
        if ( $key == 'admin_Config:attach' ){
            $exts = explode( ',' , $_POST['attach_allow_extension'] );
            $objext = array('gif','png','jpeg','zip','rar','doc','xls','ppt','docx','xlsx','pptx','pdf','jpg');
            $_POST['attach_allow_extension'] = implode( ',' , array_intersect($exts, $objext) );
        }
        $result = model('Xdata')->put($key,$_POST);
        LogRecord('admin_config', 'editDetail', array('name'=>$title, 'k1'=>L('PUBLIC_ADMIN_EDIT_EDTAIL_PEIZHI')), true);       // 儲存修改編輯詳細資料

        if($res===false){
            $this->error(L('PUBLIC_SWITCH_THEME_FAIL'));            // 寫config.inc.php檔案失敗
        }else if($result) {
            $this->success();
        } else {
            $this->error(L('PUBLIC_SAVE_FAIL'));            // 儲存失敗
        }
    }

    /********************************
     *                              *
     *          許可權設定            *
     *                              *
     ********************************/

    public function permissionset(){

        if( (empty($_GET['appname']) || empty($_GET['appgroup'])) && (empty($_GET['gid']) ) ){
            $this->error(L('PUBLIC_SYSTEM_USERGROUP_NOEXIST'));
        }

        $ruleList = model('Permission')->getRuleList($_GET['gid'],$_GET['appname'],$_GET['appgroup']);

        $this->assign('moduleHash',array('normal'=>L('PUBLIC_SYSTEM_NORMAL_USER'),'admin'=>L('PUBLIC_SYSTEM_ADMIN_USER')));
        $this->assign($ruleList);
        $this->display('admin_permissionset');
    }

    public function permissionsave(){
        $data = isset($_POST['per']) ? $_POST['per'] : array();
        model('Permission')->setGroupPermission($_POST['user_group_id'],$data);
        $this->success(L('PUBLIC_SYSTEM_MODIFY_SUCCESS'));
    }

    public function display($templateFile='',$charset='utf-8',$contentType='text/html'){
        $this->assign('systemdata_list',$this->systemdata_list);
        $this->assign('systemdata_key',$this->systemdata_key);
        $this->assign('opt',$this->opt);    //分類列表選項
        $this->assign('onsubmit',$this->onsubmit);
        $this->assign('onload',$this->onload);
        //資料儲存動作提交的地址
        $this->assign('savePostUrl',$this->savePostUrl);
        $this->assign('pageKeyList',$this->pageKeyList);
        $this->assign('pageKey',$this->pageKey);
        $this->assign('notEmpty',$this->notEmpty);
        // 頁面標題
        $this->pageTitle[ACTION_NAME] && $this->assign('pageTitle',$this->pageTitle[ACTION_NAME]);
        // 頁面標題
        $this->assign('pageTab',$this->pageTab);
        $this->assign('submitAlias',$this->submitAlias);
        parent::display($templateFile,$charset,$contentType);
    }

    private function _switchTheme($themeName=''){
        if(empty($themeName)){
            $themeName= THEME_NAME;
        }
        $file = SITE_PATH.'/config/config.inc.php';
        if(!is_writable($file)){
            return false;
        }
        $content = file_get_contents($file);
        $pos = strpos($content, 'THEME_NAME');
        if($pos===false){
            $content = str_replace('return array(','return array(
                \'THEME_NAME\' => \''.$themeName.'\', ', $content);
        }else{
            $content = preg_replace('/\'THEME_NAME\'\s*=>\s*\'([0-9a-zA-Z_]+)\'/','\'THEME_NAME\' => \''.$themeName.'\'' , $content);
        }

        return file_put_contents($file, $content);
    }
}
