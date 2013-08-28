<?php

tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
/**
 * 後臺應用管理
 *
 * @author jason
 */
class AppsAction extends AdministratorAction
{
    public $pageTitle = array(
        'index'         => '已安裝應用列表',
        'install'       => '待安裝應用列表',
        'onLineApp'     => '線上應用',
        'setCreditNode' => '積分節點設定',
        'setPermNode'   => '許可權節點設定',
        'setFeedNode'   => '微博模板設定',
    );

    private $appStatus = array('0'=>'關閉','1'=>'開啟');    //應用狀態
    private $host_type_alias = array(0=>'本地應用',1=>'遠端應用');  //託管狀態
    private $RemoteAppURL = '';
    public function _initialize(){
        $this->pageTitle['index'] = L('PUBLIC_INSTALLED_APPLIST');
        $this->pageTitle['install'] = L('PUBLIC_UNINSTALLED_APPLIST');
        $this->pageTitle['setCreditNode'] = L('PUBLIC_POINTS_SETTING');
        $this->pageTitle['setPermNode'] = L('PUBLIC_AUTHORITY_SETTING');
        $this->pageTitle['setFeedNode'] = L('PUBLIC_WEIBO_TEMPLATE_SETTING');
        $this->appStatus[0] = L('PUBLIC_CLOSE');
        $this->appStatus[1] = L('PUBLIC_OPEN');
        $this->host_type_alias[0] = L('PUBLIC_LOCAL_APP');
        $this->host_type_alias[1] = L('PUBLIC_REMOTE_APP');
        $this->RemoteAppURL = C('TS_UPDATE_SITE');
        parent::_initialize();
    }

    //已安裝的應用
    public function index(){

        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('app_id','icon_url','app_name','app_alias','status','DOACTION');

        $listData = model('App')->findPage(20);

        $inNav = $this->navList;


        foreach($listData['data'] as &$v){

            // $v['icon_url'] = empty($v['icon_url']) ? '<img src="'.APPS_URL.'/'.$v['app_name'].'/Appinfo/icon_app.png" >' : "<img src='{$v['icon_url']}'>";
            $v['icon_url'] = '<img src="'.APPS_URL.'/'.$v['app_name'].'/Appinfo/icon_app.png" >';
            !empty($v['author_homepage_url']) && $v['author_name'] = "<a href='{$v['author_homepage_url']}'>{$v['author_name']}</a>";
            $v['DOACTION'] = "<a href='".U('admin/Apps/preinstall',array('app_name'=>$v['app_name']))."'>".L('PUBLIC_EDIT')."</a>&nbsp;&nbsp;";
            $v['DOACTION'] .= $v['status'] == 0
                ? "<a href='javascript:admin.setAppStatus({$v['app_id']},1)'>".L('PUBLIC_OPEN')."</a>&nbsp;&nbsp;"
                : "<a href='javascript:admin.setAppStatus({$v['app_id']},0)'>".L('PUBLIC_CLOSE')."</a>&nbsp;&nbsp;";
            $v['DOACTION'] .= "<a href='".U('admin/Apps/uninstall',array('app_id'=>$v['app_id']))."'>".L('PUBLIC_SYSTEM_APP_UNLODING')."</a>";

            if(!empty($v['admin_entry'])){
                $name = L('PUBLIC_APPNAME_'.strtoupper($v['app_name']));
                $v['DOACTION'] .= in_array($v['app_name'],array_keys($inNav)) ?
                    ' <a href="javascript:;" onclick="admin.appnav(this,\''.$name.'\',\''.$v['admin_entry'].'\')" add="1">'.L('PUBLIC_REMOVE_NAV').'</a>'
                    :' <a href="javascript:;" onclick="admin.appnav(this,\''.$name.'\',\''.$v['admin_entry'].'\')" add="0">'.L('PUBLIC_ADD_NAV').'</a>';
            }
            $v['status'] = $this->appStatus[$v['status']];  //語義化

        }

        $this->pageButton[] = array('title'=>L('PUBLIC_OPEN'),'onclick'=>"admin.setAppStatus('', 1)");
        $this->pageButton[] = array('title'=>L('PUBLIC_CLOSE'),'onclick'=>"admin.setAppStatus('', 0)");

        $this->_listpk = 'app_id';
        $this->displayList($listData);
    }

    //待安裝的應用
    public function install(){
        $this->pageKeyList = array('icon_url','app_name','app_alias','description','host_type_alias','company_name','DOACTION');

        $listData['data']= model('App')->getUninstallList();

        foreach($listData['data'] as &$v){
            $v['host_type_alias'] = $this->host_type_alias[$v['host_type']];
            !empty($v['author_homepage_url']) && $v['author_name'] = "<a href='{$v['author_homepage_url']}'>{$v['author_name']}</a>";
            $v['icon_url'] = empty($v['icon_url']) ? '<img src="'.APPS_URL.'/'.$v['app_name'].'/Appinfo/icon_app.png" >' : "<img src='{$v['icon_url']}'>";
            $v['DOACTION'] =  "<a href='".U('admin/Apps/preinstall',array('app_name'=>$v['app_name'],'install'=>1))."'>".L('PUBLIC_SYSTEM_APP_INSTALL')."</a>";
        }
        $this->allSelected = false;
        $this->displayList($listData);
    }

    //安裝 編輯 應用
    public function preinstall($app_name='', $install=''){
        if(empty($app_name)){
            $app_name = t($_GET['app_name']);
        }
        if(empty($install)){
            $install = t($_GET['install']);
        }
        if(empty($app_name))
            $this->error(L('PUBLIC_SYSTEM_APP_SELECTINSTALL'));

        $this->pageKeyList = array('app_id','app_name','app_alias','app_entry','description','status','host_type','icon_url','large_icon_url',
            'admin_entry','statistics_entry','company_name','display_order','version','api_key','secure_key','add_front_top','add_front_applist'
        );

        if(!empty($install)){
            $this->submitAlias = L('PUBLIC_SYSTEM_APP_INSTALL');
            $info = model('App')->__getAppInfo($app_name);
            $this->assign('pageTitle',L('PUBLIC_SYSTEM_APP_INSTALL'));
            !empty($info['admin_entry']) && $this->pageKeyList[] = 'add_tonav';
        }else{
            $map['app_name']  = $app_name;
            $info = model('App')->where($map)->find();
            $this->assign('pageTitle',L('PUBLIC_SYSTEM_APP_EDIT'));
        }

        $this->savePostUrl = U('admin/Apps/saveApp');
        $this->opt['status'] = $this->appStatus;
        $this->opt['host_type'] = $this->host_type_alias;
        $this->opt['add_tonav'] = array(0=>L('PUBLIC_SYSTEMD_FALSE'),1=>L('PUBLIC_SYSTEMD_TRUE'));
        $this->opt['add_front_top'] = array(0=>L('PUBLIC_SYSTEMD_FALSE'),1=>L('PUBLIC_SYSTEMD_TRUE'));
        $this->opt['add_front_applist'] = array(0=>L('PUBLIC_SYSTEMD_FALSE'),1=>L('PUBLIC_SYSTEMD_TRUE'));
        $this->notEmpty = array('app_name','app_alias','app_entry');
        $this->onsubmit = 'admin.checkAppInfo(this)';
        $this->displayConfig($info);
    }


    //安裝儲存應用
    public function saveApp(){
        //app_id 為空為安裝 ，否則為修改
        if(empty($_POST['app_name']) || empty($_POST['app_alias']) || empty($_POST['app_entry'])){
            $this->error(L('PUBLIC_SYSTEM_APP_INSTALLERROR'));
        }


        $status = model('App')->saveApp($_POST);

        if($status === true){
            $log['app_name']  = $_POST['app_name'];
            if(!empty($_POST['app_id'])){
                $log['app_id']    = $_POST['app_id'];
            }
            $log['app_alias'] = $_POST['app_alias'];
            $log['k']         = L('PUBLIC_SYSTEM_APP_FILEDS');
            LogRecord('admin_extends', 'appManage', $log,true);

            if(!empty($_POST['admin_entry']) && $_POST['add_tonav'] == 1){
                $this->navList[$_POST['app_name']] = $_POST['admin_entry'];
                model('Xdata')->put('admin_nav:top',$this->navList);
            }
            if($_POST['app_name']=='tipoff') model('UserGroup')->cleanCache();
            $this->assign('jumpUrl',!empty($_POST['app_id']) ? U('admin/Apps/index'): U('admin/Apps/install'));
            $this->success();
        }else{
            $this->error($status);
        }
    }

    //解除安裝應用
    public function uninstall(){
        $app_id = intval($_GET['app_id']);
        if(empty($app_id)){
            $this->error(L('PUBLIC_ID_NOEXIST'));
        }
        $appInfo = model('App')->getAppById($app_id);
        $status = model('App')->uninstall($app_id);
        if($status === true){
            unset($this->navList[$appInfo['app_name']]);
            model('Xdata')->put('admin_nav:top',$this->navList);
            LogRecord('admin_extends', 'appUninstall', array('app_id'=>$app_id,'k1'=>L('PUBLIC_SYSTEM_APP_UNLODING')),true);
            $this->success();
        }else{
            $this->error($status);
        }
    }

    //許可權節點設定
    public function setPermNode(){
        $this->pageKeyList = array('id','appname','appinfo','module','rule','ruleinfo','DOACTION');
        //列表分頁欄 按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_ADD'),'onclick'=>"location.href = '".U('admin/Apps/editPermNode')."'");
        $this->pageButton[] = array('title'=>L('PUBLIC_STREAM_DELETE'),'onclick'=>"admin.delPermNode()");
        $listData = D('permission_node')->findPage(10);
        foreach($listData['data'] as &$v){
            $v['DOACTION'] =  "<a href='".U('admin/Apps/editPermNode',array('id'=>$v['id']))."'>".L('PUBLIC_EDIT')."</a> |
                <a href='javascript:void(0)' onclick='admin.delPermNode(".$v['id'].")'>".L('PUBLIC_STREAM_DELETE')."</a>";
        }
        $this->displayList($listData);
    }

    public function editPermNode(){
        if(!empty($_GET['id'])){
            $info = D('permission_node')->find(intval($_GET['id']));
            $this->assign('pageTitle','編輯許可權節點');
        }else{
            $this->submitAlias = L('PUBLIC_ADD');
            $this->assign('pageTitle',L('PUBLIC_SYSTEM_ADMINJUR_ADD'));
        }

        $this->pageKeyList = array('id','appname','appinfo','module','rule','ruleinfo');

        $this->opt['module'] = array('normal'=>L('PUBLIC_SYSTEM_NORMAL_USER'),'admin'=>L('PUBLIC_SYSTEM_ADMIN_USER'));

        $this->savePostUrl = U('admin/Apps/savePermNode');

        $this->notEmpty = array('appname','module','rule');
        $this->onsubmit = 'admin.checkPermNode(this)';

        $this->displayConfig($info);
    }

    public function savePermNode(){
        if(!empty($_POST)){
            $data['appname']  = t($_POST['appname']);
            $data['appinfo']  = t($_POST['appinfo']);
            $data['module']   = t($_POST['module']);
            $data['rule']     = t($_POST['rule']);
            $data['ruleinfo'] = t($_POST['ruleinfo']);

            if(empty($data['appname']) ||  empty($data['module']) || empty($data['rule'])  ){
                $this->error(L('PUBLIC_APPLICATIONS_MODULE_RULES_NOEMPTY'));exit();
            }
            if(!empty($_POST['id'])){
                $map['id'] = intval($_POST['id']);
                $act ='editPermNode';
                $res = D('permission_node')->where($map)->save($data);
            }else{

                $act = 'addPermNode';
                $res = D('permission_node')->add($data);

            }
            LogRecord('admin_extends',$act,$data,true);
        }

        if($res){
            $this->assign('jumpUrl',U('admin/Apps/setPermNode'));
            $this->success();
        }else{
            $this->error();
        }
    }

    public function delPermNode(){
        $return = array('status'=>1,'data'=>L('PUBLIC_DELETE_SUCCESS'));
        $id = $_POST['id'];
        $map['id'] = is_array($id) ? array('in',$id):$id;
        if($data = D('permission_node')->where($map)->findAll()){
            if(D('permission_node')->where($map)->delete()){
                $d['log'] = var_export($data,true);
                $d['k'] = L('PUBLIC_SYSTEM_ADMINJUR_DELETE');
                LogRecord('admin_extends','delPermNode',$d,true);
                echo json_encode($return);exit();
            }
        }
        $return['status'] = 0;
        $return['data']   = L('PUBLIC_DELETE_FAIL');
        echo json_encode($return);exit();
    }

    //動態節點設定
    public function setAppStatus(){
        $app_id = $_POST['app_id'];
        $map['app_id'] = is_array($app_id) ? array('in',$app_id):intval($app_id);
        $data['status'] = intval($_POST['status']);
        if($data = D('App')->where($map)->save($data)){

            // 設定快取
            $appname = D('App')->where($map)->getField('app_name');
            model ( 'Cache' )->set ( 'Appinfo_' . $appname, null );
            model('App')->cleanCache();
            $return['status'] = 1;
            $return['data']   = '設定成功！';
            echo json_encode($return);exit();
        }else{
            $return['status'] = 0;
            $return['data']   = '設定失敗！';
            echo json_encode($return);exit();
        }
    }

    //線上應用
/*  function onLineApp(){
        $url = $this->RemoteAppURL.'/index.php?app=public&mod=Tool&act=getAppsOnLineInfo';
        $list = file_get_contents($url);
        $list = json_decode($list, true);

        $loadlist = Model('App')->field('app_name,version')->findAll();
        foreach ($loadlist as $v){
            $appVersion[$v['app_name']] = $v['version'];
            $appName[$v['app_name']] = $v['app_name'];
        }
        unset($loadlist);

        foreach ($list as $k=>&$v){
            if(in_array($v['app_name'], $appName)){
                if($v['version']==$appVersion[$v['app_name']]){
                    unset($list[$k]);  //已安裝並且版本號一樣不再顯示
                }else{
                    $v['isUpdate'] = true; //安裝過的應用有更新版本
                }
            }else{
                $v['isUpdate'] = false; //未安裝過的應用
            }
        }

        $dirIsWritable = is_writable(APPS_PATH);
        $type = array('1'=>'模板','2'=>'插件','3'=>'應用'); //插件類型,1:模板面板;2:插件;3:應用

        foreach ($list as &$vo){
            $vo['title'] = '<a href="'.$this->RemoteAppURL.'/index.php?app=develop&mod=Index&act=detail&id='.$vo['develop_id'].'" target="_blank">'.$vo['title'].'</a>';
            $vo['type'] = $type[$vo['type']];
            $vo['user'] = '<a href="'.$this->RemoteAppURL.'/index.php?app=public&mod=Profile&act=index&uid='.$vo['uid'].'" target="_blank">'.$vo['user'].'</a>';
            unset($vo['uid']);
            $vo['option'] = '<a href="'.$this->RemoteAppURL.'/index.php?app=develop&mod=Index&act=download&id='.$vo['develop_id'].'">下載</a> ';

            if($dirIsWritable){
                if($vo['isUpdate']){
                    $vo['option'] .= '<a href="'.U('admin/Apps/downloadAndInstall', array('develop_id'=>$vo['develop_id'])).'">一鍵更新</a>';
                }else{
                    $vo['option'] .= '<a href="'.U('admin/Apps/downloadAndInstall', array('develop_id'=>$vo['develop_id'])).'">一鍵安裝</a>';
                }
            }

        }
        //dump($list);

        $this->pageKeyList = array('title','type','download_count','user','option');

        $listData['data']= $list;

        $this->allSelected = false;
        $this->displayList($listData);
}*/

    /**
     * 線上應用
     * @return void
     */
    public function onLineApp () {
        // 獲取站點域名
        $host = str_replace('http://', '', SITE_URL);
        // 驗證站點是否登陸
        $iframeUrl = $this->RemoteAppURL.'/index.php?app=develop&mod=Public&act=getAppStoreHome&h='.base64_encode($host);
        $this->assign('iframeUrl', $iframeUrl);
        $this->display();
}

/**
 * 一鍵安裝介面
 * @return void
 */
public function downloadAndInstall () {
    header("content-Type: text/html; charset=utf-8");
    // 獲取下載地址
    $develop_id = intval($_GET['develop_id']);
    $url = $this->RemoteAppURL.'/index.php?app=public&mod=Tool&act=downloadApp&develop_id='.$develop_id;
    $info = file_get_contents($url);
    $info = json_decode($info, true);
    // 載入下載類
    tsload(ADDON_PATH.'/library/Update.class.php');
    $updateClass = new Update();
    //從伺服器端下載應用到本地
    $res = $updateClass->downloadFile($info['packageURL']);
    if ($res != 1) {
        $this->error('下載應用失敗，請確認網路是否正常');
        exit;
}
// 壓縮
$package = explode('/', $info['file']['filename']);
$packageName = array_pop($package);
$targetDir = $updateClass->downloadPath.'unzip';
// 創建目錄unzip
if (!is_dir($targetDir)) {
    @mkdir($targetDir, 0777);
}
$res = $updateClass->unzipPackage($packageName, $targetDir);
if ($res != 1) {
    $this->error('下載應用解壓失敗');
    exit;
}
// 覆蓋程式碼
switch ($info['type']) {
case 3:
    // 應用
    $res = $updateClass->overWrittenFile(APPS_PATH);
    break;
case 2:
    // 插件
    $res = $updateClass->overWrittenFile(ADDON_PATH.'/plugin');
    break;
case 1:
    // 面板
    $res = $updateClass->overWrittenFile(ADDON_PATH.'/theme');
    break;
}
// 安裝
switch ($info['type']) {
case 3:
    // 應用
    U('admin/Apps/preinstall', array('app_name'=>$info['app_name'],'install'=>1), true);
    break;
case 2:
    // 插件
    U('admin/Addons/index', array('install'=>1), true);
    break;
case 1:
    U('admin/Apps/onLineApp', true);
    break;
}
}
}
