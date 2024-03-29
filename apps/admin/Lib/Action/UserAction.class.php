<?php
/**
 * 後臺，使用者管理控制器
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
// 載入後臺控制器
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');
class UserAction extends AdministratorAction {

    public $pageTitle = array();

    /**
     * 初始化，初始化頁面表頭資訊，用於雙語
     */
    public function _initialize() {
        $this->pageTitle['index'] = L('PUBLIC_USER_MANAGEMENT');
        $this->pageTitle['pending'] = L('PUBLIC_PENDING_LIST');
        $this->pageTitle['profile'] = L('PUBLIC_PROFILE_SETTING');
        $this->pageTitle['profileCategory'] = L('PUBLIC_PROFILE_SETTING');
        $this->pageTitle['dellist'] = L('PUBLIC_DISABLE_LIST');
        $this->pageTitle['online'] = '線上使用者列表';
        $this->pageTitle['addUser'] = L('PUBLIC_ADD_USER_INFO');
        $this->pageTitle['editUser'] = L('PUBLIC_EDIT_USER');
        $this->pageTitle['addProfileField'] = L('PUBLIC_ADD_FIELD');
        $this->pageTitle['editProfileField'] = L('PUBLIC_EDIT_FIELD');
        $this->pageTitle['addProfileCategory'] = L('PUBLIC_ADD_FIELD_CLASSIFICATION');
        $this->pageTitle['editProfileCategory'] = L('PUBLIC_EDITCATEOGRY');
        $this->pageTitle['verify'] = '待認證使用者';
        $this->pageTitle['verifyGroup'] = '待認證企業';
        $this->pageTitle['verified'] = '已認證使用者';
        $this->pageTitle['verifiedGroup'] = '已認證企業';
        $this->pageTitle['addVerify'] = '添加認證';
        $this->pageTitle['category'] = '推薦標籤';
        $this->pageTitle['verifyCategory'] = '認證分類';
        $this->pageTitle['verifyConfig'] = '認證配置';
        $this->pageTitle['official'] = '官方使用者配置';
        $this->pageTitle['officialCategory'] = '官方使用者分類';
        $this->pageTitle['officialList'] = '官方使用者列表';
        $this->pageTitle['officialAddUser'] = '添加官方使用者';
        $this->pageTitle['findPeopleConfig'] = '全局配置';

        parent::_initialize();
    }

    /**
     * 使用者管理 - 使用者列表
     */
    public function index()
    {
        $_REQUEST['tabHash'] = 'index';
        // 初始化使用者列表管理選單
        $this->_initUserListAdminMenu('index');
        // 資料的格式化與listKey保持一致
        $listData = $this->_getUserList('20', $map, 'index');
        // 列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_USER'),'onclick'=>"admin.fold('search_form')");
        $this->pageButton[] = array('title'=>L('PUBLIC_TRANSFER_USER_GROUP'),'onclick'=>"admin.changeUserGroup()");
        // 轉移使用者部門，如果需要請將下面的註釋開啟
        // $this->pageButton[] = array('title'=>L('PUBLIC_TRANSFER_DEPARTMENT'),'onclick'=>"admin.changeUserDepartment()");
        $this->displayList($listData);
    }

    /**
     * 使用者管理 - 待審列表
     */
    public function pending() {
        $_REQUEST['tabHash'] = 'pending';
        // 初始化稽覈列表管理選單
        $this->_initUserListAdminMenu('pending');
        // 資料的格式化與listKey保持一致
        $listData = $this->_getUserList(20, array('is_audit' => 0,'is_del'=>'0'), 'pending');
        // 列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_USER'),'onclick'=>"admin.fold('search_form')");
        $this->pageButton[] = array('title'=>L('PUBLIC_AUDIT_USER_SUCCESS'),'onclick'=>"admin.auditUser('',1)");

        $this->displayList($listData);
    }

    /**
     * 使用者管理 - 禁用列表
     */
    public function dellist() {
        $_REQUEST['tabHash'] = 'dellist';
        // 初始化禁用列表管理選單
        $this->_initUserListAdminMenu('dellist');
        // 資料的格式化與listKey保持一致
        $listData = $this->_getUserList(20, array('is_del'=>'1'), 'dellist');
        // 列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_USER'),'onclick'=>"admin.fold('search_form')");
        $this->pageButton[] = array('title'=>L('PUBLIC_RECOVER_ACCOUNT'),'onclick'=>"admin.rebackUser()");

        $this->displayList($listData);
    }

    /**
     * 使用者管理 - 線上使用者列表
     */
    public function online() {
        $_REQUEST['tabHash'] = 'online';
        // tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_USER_LIST'),'tabHash'=>'index','url'=>U('admin/User/index'));
        $this->pageTab[] = array('title'=>L('PUBLIC_PENDING_LIST'),'tabHash'=>'pending','url'=>U('admin/User/pending'));
        $this->pageTab[] = array('title'=>L('PUBLIC_DISABLE_LIST'),'tabHash'=>'dellist','url'=>U('admin/User/dellist'));
        // $this->pageTab[] = array('title'=>'線上使用者列表','tabHash'=>'online','url'=>U('admin/User/online'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_USER_INFO'),'tabHash'=>'addUser','url'=>U('admin/User/addUser'));
        // 搜索選項的key值
        $this->searchKey = array('uid','uname','email','sex','user_group',array('ctime','ctime1'));
        // 針對搜索的特殊選項
        $this->opt['sex'] = array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT'),'1'=>L('PUBLIC_MALE'),'2'=>L('PUBLIC_FEMALE'));
        $this->opt['identity'] = array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT'),'1'=>L('PUBLIC_PERSONAL'),'2'=>L('PUBLIC_ORGANIZATION'));
        $this->opt['user_group'] = array_merge(array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT')),model('UserGroup')->getHashUsergroup());
        // 列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_USER'),'onclick'=>"admin.fold('search_form')");

        $this->opt['user_group'] = array_merge(array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT')),model('UserGroup')->getHashUsergroup());

        $this->pageKeyList = array('uid','uname','user_group','location','ctime','last_operating_ip');

        $listData = $this->_getUserOnlineList(20, $map);

        $this->displayList($listData);
    }

    /**
     * 使用者管理 - 檢視IP列表
     */
    public function viewIP() {
        $_REQUEST['tabHash'] = 'viewIP';
        $uid = intval($_REQUEST['uid']);
        $userInfo = model('User')->getUserInfo($uid);
        $this->pageTitle['viewIP'] = '檢視IP - 使用者：'.$userInfo['uname'].'（'.$userInfo['email'].'）';
        // tab選項
        $this->pageTab[] = array('title'=>'檢視IP','tabHash'=>'viewIP','url'=>U('admin/User/viewIP', array('tabHash'=>'viewIP','uid'=>$uid)));
        $this->pageTab[] = array('title'=>'登入日誌','tabHash'=>'loginLog','url'=>U('admin/User/loginLog', array('tabHash'=>'loginLog','uid'=>$uid)));
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('id','day','action','ip','DOACTION');
        // 獲取相關資料
        $listData = model('Online')->getUserOperatingList($uid);
        foreach($listData['data'] as $k => $v) {
            // $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.disableIP(\''.$v['ip'].'\')">禁用IP</a>';
        }

        $this->displayList($listData);
    }

    /**
     * 使用者管理 - 登入日誌
     */
    public function loginLog() {
        $_REQUEST['tabHash'] = 'loginLog';
        $uid = intval($_REQUEST['uid']);
        $userInfo = model('User')->getUserInfo($uid);
        $this->pageTitle['loginLog'] = '登入日誌 - 使用者：'.$userInfo['uname'].'（'.$userInfo['email'].'）';
        // tab選項
        $this->pageTab[] = array('title'=>'檢視IP','tabHash'=>'viewIP','url'=>U('admin/User/viewIP', array('tabHash'=>'viewIP','uid'=>$uid)));
        $this->pageTab[] = array('title'=>'登入日誌','tabHash'=>'loginLog','url'=>U('admin/User/loginLog', array('tabHash'=>'loginLog','uid'=>$uid)));
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('login_logs_id','ip','ctime','DOACTION');
        // 獲取相關資料
        $map['uid'] = $uid;
        $listData = D('login_logs')->where($map)->findPage(20);
        foreach($listData['data'] as $k => $v) {
            $listData['data'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            // $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.disableIP(\''.$v['ip'].'\')">禁用IP</a>';
        }

        $this->displayList($listData);
    }

    /**
     * 獲取線上使用者列表資料
     */
    private function _getUserOnlineList($limit, $map) {
        // 設定列表主鍵
        $this->_listpk = 'uid';
        // 取使用者列表
        $listData = model('User')->getUserList($limit, $map);
        $uids = getSubByKey($listData['data'], 'uid');
        $ipData = D('Online')->getLastOnlineInfo($uids);
        $ipKey = array_keys($ipData);
        // 資料格式化
        foreach($listData['data'] as $k => $v) {
            $listData['data'][$k]['uname'] = '<a href="'.U('admin/User/editUser',array('tabHash'=>'editUser','uid'=>$v['uid'])).'">'.$v['uname'].'</a> ('.$v['email'].')';
            $listData['data'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            // 使用者組資料
            if(!empty($v['user_group'])) {
                $group = array();
                foreach($v['user_group'] as $gid) {
                    $group[] = $this->opt['user_group'][$gid];
                }
                $listData['data'][$k]['user_group'] = implode('<br/>', $group);
            } else {
                $listData['data'][$k]['user_group'] = '';
            }
            $this->opt['user_group'][$v['user_group_id']];
            // 最後操作IP
            $listData['data'][$k]['last_operating_ip'] = empty($ipData) ? $v['reg_ip'] : (in_array($v['uid'], $ipKey) ? $ipData[$v['uid']] : $v['reg_ip']);
        }

        return $listData;
    }

    /**
     * 初始化使用者列表管理選單
     * @param string $type 列表類型，index、pending、dellist
     */
    private function _initUserListAdminMenu($type) {
        // tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_USER_LIST'),'tabHash'=>'index','url'=>U('admin/User/index'));
        $this->pageTab[] = array('title'=>L('PUBLIC_PENDING_LIST'),'tabHash'=>'pending','url'=>U('admin/User/pending'));
        $this->pageTab[] = array('title'=>L('PUBLIC_DISABLE_LIST'),'tabHash'=>'dellist','url'=>U('admin/User/dellist'));
        // $this->pageTab[] = array('title'=>'線上使用者列表','tabHash'=>'online','url'=>U('admin/User/online'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_USER_INFO'),'tabHash'=>'addUser','url'=>U('admin/User/addUser'));
        // 搜索選項的key值
        // $this->searchKey = array('uid','uname','email','sex','department','user_group',array('ctime','ctime1'));
        $this->searchKey = array('uid','uname','email','sex','user_group','user_category',array('ctime','ctime1'));
        // 針對搜索的特殊選項
        $this->opt['sex'] = array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT'),'1'=>L('PUBLIC_MALE'),'2'=>L('PUBLIC_FEMALE'));
        $this->opt['identity'] = array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT'),'1'=>L('PUBLIC_PERSONAL'),'2'=>L('PUBLIC_ORGANIZATION'));
        //$this->opt['user_group'] = array_merge(array('0'=>L('PUBLIC_SYSTEMD_NOACCEPT')),model('UserGroup')->getHashUsergroup());
        $this->opt['user_group'] = model('UserGroup')->getHashUsergroup();
        $this->opt['user_group'][0] = L('PUBLIC_SYSTEMD_NOACCEPT');
        $map['pid'] = array('NEQ', 0);
        $categoryList = model('UserCategory')->getAllHash($map);
        $categoryList[0] = L('PUBLIC_SYSTEMD_NOACCEPT');
        ksort($categoryList);
        $this->opt['user_category'] = $categoryList;
        //$this->opt['department_id'] = model('Department')->getHashDepartment();

        // 列表key值 DOACTION表示操作
        switch(strtolower($type)) {
        case 'index':
        case 'dellist':
            $this->pageKeyList = array('uid','uname','user_group','location','is_audit','is_active','is_init','ctime','reg_ip','DOACTION');
            break;
        case 'pending':
            $this->pageKeyList = array('uid','uname','location','ctime','reg_ip','DOACTION');
            break;
        }

/*		if(!empty($_POST['_parent_dept_id'])) {
            $this->onload[] = "admin.departDefault('".implode(',', $_POST['_parent_dept_id'])."','form_user_department')";
}*/
    }

    /**
     * 解析使用者列表資料
     * @param integer $limit 結果集數目，默認為20
     * @param array $map 查詢條件
     * @param string $type 格式化資料類型，index、pending、dellist
     * @return array 解析後的使用者列表資料
     */
    private function _getUserList($limit = 20, $map = array(), $type = 'index') {
        // 設定列表主鍵
        $this->_listpk = 'uid';
        // 取使用者列表
        $listData = model('User')->getUserList($limit, $map);
        //dump($listData);exit;
        // 資料格式化
        foreach($listData['data'] as $k => $v) {
            // 獲取使用者身份資訊
            $userTag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags($v['uid']);
            $userTagString = '';
            $userTagArray = array();
            if(!empty($userTag)) {
                $userTagString .= '<p>';
                foreach($userTag as $value) {
                    $userTagArray[] = '<span style="color:blue;cursor:auto;">'.$value.'</span>';
                }
                $userTagString .= implode('&nbsp;', $userTagArray).'</p>';
            }
            //獲取使用者組資訊
            $userGroupInfo = model('UserGroupLink')->getUserGroupData($v['uid']);
            foreach($userGroupInfo[$v['uid']] as $val){
                $userGroupIcon[$v['uid']] .= '<img style="width:auto;height:auto;display:inline;cursor:pointer;vertical-align:-2px;" src="'.$val['user_group_icon_url'].'" title="'.$val['user_group_name'].'" />&nbsp';
            }
            $listData['data'][$k]['uname'] = '<a href="'.U('admin/User/editUser',array('tabHash'=>'editUser','uid'=>$v['uid'])).'">'.$v['uname'].'</a>'.$userGroupIcon[$v['uid']].' <br/>'.$v['email'].' '.$userTagString;
            $listData['data'][$k]['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            // 遮蔽部門資訊，若要開啟將下面的註釋開啟
/*			$department = model('Department')->getUserDepart($v['uid']);
$listData['data'][$k]['department'] = str_replace('|', ' - ',trim($department[$v['uid']],'|'));*/
            $listData['data'][$k]['identity'] = ($v['identity'] == 1) ? L('PUBLIC_PERSONAL') : L('PUBLIC_ORGANIZATION');
            switch(strtolower($type)) {
            case 'index':
            case 'dellist':
                // 列表資料
                $listData['data'][$k]['is_active'] = ($v['is_active'] == 1) ? '<span style="color:blue;cursor:auto;">'.L('SSC_ALREADY_ACTIVATED').'</span>' : '<a href="javascript:void(0)" onclick="admin.activeUser(\''.$v['uid'].'\',1)" style="color:red">'.L('PUBLIC_NOT_ACTIVATED').'</a>';
                $listData['data'][$k]['is_audit'] = ($v['is_audit'] == 1) ? '<span style="color:blue;cursor:auto;">'.L('PUBLIC_AUDIT_USER_SUCCESS').'</span>' : '<a href="javascript:void(0)" onclick="admin.auditUser(\''.$v['uid'].'\',1)" style="color:red">'.L('PUBLIC_AUDIT_USER_ERROR').'</a>';
                $listData['data'][$k]['is_init'] = ($v['is_init'] == 1) ? '<span style="cursor:auto;">'.L('PUBLIC_SYSTEMD_TRUE').'</span>' : '<span style="cursor:auto;">'.L('PUBLIC_SYSTEMD_FALSE').'</span>';
                // 使用者組資料
                if(!empty($v['user_group'])) {
                    $group = array();
                    foreach($v['user_group'] as $gid) {
                        $group[] = $this->opt['user_group'][$gid];
                    }
                    $listData['data'][$k]['user_group'] = implode('<br/>', $group);
                } else {
                    $listData['data'][$k]['user_group'] = '';
                }
                $this->opt['user_group'][$v['user_group_id']];
                // 操作資料
                $listData['data'][$k]['DOACTION'] = '<a href="'.U('admin/User/editUser',array('tabHash'=>'editUser','uid'=>$v['uid'])).'">'.L('PUBLIC_EDIT').'</a> - ';
                $listData['data'][$k]['DOACTION'] .= $v['is_del'] == 1 ? '<a href="javascript:void(0)" onclick="admin.rebackUser(\''.$v['uid'].'\')">'.L('PUBLIC_RECOVER').'</a> - ' : '<a href="javascript:void(0)" onclick="admin.delUser(\''.$v['uid'].'\')">'.L('PUBLIC_SYSTEM_NOUSE').'</a> - ';
                $listData['data'][$k]['DOACTION'] .= '<a href="javascript:void(0)" onclick="admin.trueDelUser(\''.$v['uid'].'\')">'.L('PUBLIC_REMOVE_COMPLETELY').'</a>';
                // $listData['data'][$k]['DOACTION'] .= '<a href="'.U('admin/User/viewIP',array('tabHash'=>'viewIP','uid'=>$v['uid'])).'">檢視IP</a>';
                break;
            case 'pending':
                // 操作資料
                $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.auditUser(\''.$v['uid'].'\', 1)">'.L('PUBLIC_AUDIT_USER_SUCCESS').'</a>';
                break;
            }
        }
        return $listData;
    }

    /**
     * 使用者管理 - 添加使用者
     */
    public function addUser() {
        // 初始化使用者列表管理選單
        $this->_initUserListAdminMenu();
        //註冊配置(添加使用者頁隱藏稽覈按鈕)
        $regInfo = model('Xdata')->get('admin_Config:register');
        $this->pageKeyList = array('email','uname','password','sex');
        if($regInfo['register_audit'] == 1){
            $this->pageKeyList = array_merge($this->pageKeyList,array('is_audit'));
            $this->opt['is_audit'] = array('1'=>'是','2'=>'否');
        }
        if($regInfo['need_active'] == 1){
            $this->pageKeyList = array_merge($this->pageKeyList,array('is_active'));
            $this->opt['is_active'] = array('1'=>'是','2'=>'否');
        }
        $this->pageKeyList = array_merge($this->pageKeyList,array('user_group'));
        // 列表key值 DOACTION表示操作
        //$this->pageKeyList = array('email','uname','password','sex','is_audit','is_active','identity','user_group','user_category');  //身份欄位預留
        // $this->pageKeyList = array('email','uname','password','sex','is_audit','is_active','user_group','user_category');
        $this->opt['type'] = array('2'=>L('PUBLIC_SYSTEM_FIELD'));
        // 欄位選項配置
        $this->opt['sex'] = array('1'=>L('PUBLIC_MALE'),'2'=>L('PUBLIC_FEMALE'));
        //$this->opt['identity'] = array('1'=>L('PUBLIC_PERSONAL'),'2'=>L('PUBLIC_ORGANIZATION'));
        // $group = D('user_group')->where('is_authenticate=0')->findAll();
        // foreach($group as $k=>$v){
        // 	$unverifyGroup[$v['user_group_id']] = $v['user_group_name'];
        // }
        // $this->opt['user_group'] = $unverifyGroup;
        $this->opt['user_group'] = model('UserGroup')->getHashUsergroup();
        $map['pid'] = array('NEQ', 0);
        $this->opt['user_category'] = model('UserCategory')->getAllHash($map);
        // 表單URL設定
        $this->savePostUrl = U('admin/User/doAddUser');
        $this->notEmpty = array('email','uname','password','user_group');
        $this->onsubmit = 'admin.addUserSubmitCheck(this)';

        $this->displayConfig();
    }

    /**
     * 添加新使用者操作
     */
    public function doAddUser() {
        $user = model('User');
        $map = $user->create();
        // 稽覈與啟用修改
        $map['is_active'] = ($map['is_active'] == 2) ? 0 : 1;
        $map['is_audit'] = ($map['is_audit'] == 2) ? 0 : 1;
        // 檢查map返回值，有表單驗證
        $result = $user->addUser($map);
        if($result) {
            $this->assign('jumpUrl', U('admin/User/index'));
            $this->success(L('PUBLIC_ADD_SUCCESS'));
        } else {
            $this->error($user->getLastError());
        }
    }

    /**
     * 編輯使用者頁面
     */
    public function editUser(){
        // 初始化使用者列表管理選單
        $this->_initUserListAdminMenu();
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('uid','email','uname','password','sex','user_group');
        $this->opt['type'] = array('2'=>L('PUBLIC_SYSTEM_FIELD'));
        // 欄位選項配置
        $this->opt['sex'] = array('1'=>L('PUBLIC_MALE'),'2'=>L('PUBLIC_FEMALE'));
        //$this->opt['identity'] = array('1'=>L('PUBLIC_PERSONAL'),'2'=>L('PUBLIC_ORGANIZATION'));
        // $user_department = model('Department')->getAllHash(0);
        $this->opt['user_group'] = model('UserGroup')->getHashUsergroup();

        $this->opt['is_active'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));

        //獲取使用者資料
        $uid = intval($_REQUEST['uid']);
        $userInfo  = model('User')->getUserInfo($uid);

        unset($userInfo['password']);
        //獲取使用者組資訊
        $userInfo['user_group'] = model('UserGroupLink')->getUserGroup($uid);
        $userInfo['user_group'] = $userInfo['user_group'][$uid];
        $map['pid'] = array('neq',0);
        $this->opt['user_category'] = model('UserCategory')->getAllHash($map);
        $userInfo['user_category'] = getSubByKey(model('UserCategory')->getRelatedUserInfo($uid),'user_category_id');

        if(!$userInfo){
            $this->error(L('PUBLIC_GET_INFORMATION_FAIL'));
        }

        //使用者部門
/*		$depart = model('Department')->getUserDepart($uid);
        $userInfo['department_show'] = str_replace('|', ' - ',trim($depart[$uid],'|'));
$userInfo['department_id']	 = 0;*/

        $this->assign('pageTitle',L('PUBLIC_EDIT_USER'));
        $this->savePostUrl = U('admin/User/doUpdateUser');

        // $this->notEmpty = array('email','uname','department_id');
        $this->notEmpty = array('email','uname','user_group');
        $this->onsubmit = 'admin.checkUser(this)';

        $this->displayConfig($userInfo);
    }

    /**
     * 更新使用者資訊
     */
    public function doUpdateUser() {
        $uid = intval($_POST['uid']);
        $user = model('User');
        // 驗證使用者名稱是否重複
        $oldUname = $user->where('uid='.$uid)->getField('uname');
        $vmap['uname'] = t($_POST['uname']);
        if($oldUname != $vmap['uname']) {
            $isExist = $user->where($vmap)->count();
            if($isExist > 0) {
                $this->error('使用者昵稱已存在，請使用其他昵稱');
                return false;
            }
        }

        $map = $user->create();
        $map['login'] = t($_POST['email']);
        unset($map['password']);
        // 生成新密碼
        if(isset($_POST['password']) && !empty($_POST['password'])) {
            $map['login_salt'] = rand(11111,99999);
            $map['password'] = md5(md5($_POST['password']).$map['login_salt']);
        }
        $map['first_letter'] = getFirstLetter($map['uname']);

        // 檢查map返回值，有表單驗證
        $result = $user->where("uid=".$uid )->save($map);
        if(count($_POST['user_group']) == 0){
            $this->error('使用者組不能為空');
        }
        $r = model('UserGroupLink')->domoveUsergroup($uid,implode(',',$_POST['user_group']));

        // 更改部門
        /*
        if(!empty($_POST['department_id'])){
            model('Department')->updateUserDepartById($uid,intval($_POST['department_id']));
        }*/
        //更改職業資訊
        //model('UserCategory')->updateRelateUser($uid, $_POST['user_category']);

        if($result || $r) {
            model('User')->cleanCache($uid);
            // 清除許可權快取
            model('Cache')->rm('perm_user_'.$uid);
            // 儲存使用者組的資訊
            $this->assign('jumpUrl', U('admin/User/editUser',array('uid'=>$uid,'tabHash'=>'editUser')));
            $this->success(L('PUBLIC_SYSTEM_MODIFY_SUCCESS'));
        } else {
            $this->error(L('PUBLIC_ADMIN_OPRETING_ERROR'));
        }
    }

    /*
     * 新增資料欄位/分類
     * @access public
     *
     */
    public function doActiveUser(){
        if(empty($_POST['id'])){
            $return['status'] = 0;
            $return['data']   = '';
            echo json_encode($return);exit();
        }
        //設定啟用狀態id可以是多個，類型只能是0或1
        $result = model('User')->activeUsers($_POST['id'],$_POST['type']);
        if(!$result){
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_ERROR');
        }else{
            $return['status'] = 1;
            $return['data']   = L('PUBLIC_ADMIN_OPRETING_SUCCESS');
        }
        echo json_encode($return);exit();
    }

    public function doAuditUser(){
        if(empty($_POST['id'])){
            $return['status'] = 0;
            $return['data']   = '';
            echo json_encode($return);exit();
        }
        //設定啟用狀態id可以是多個，類型只能是0或1
        $result = model('Register')->audit($_POST['id'],$_POST['type']);
        if(!$result){
            $return['status'] = 0;
            $return['data'] = model('Register')->getLastError();
        }else{
            $return['status'] = 1;
            $return['data']   = model('Register')->getLastError();
        }
        echo json_encode($return);exit();
    }

    /**
     * 使用者賬號禁用操作
     * @return json 操作後的JSON資料
     */
    public function doDeleteUser() {
        if(empty($_POST['id'])) {
            $return['status'] = 0;
            $return['data'] = '';
            exit(json_encode($return));
        }

        $result = model('User')->deleteUsers(intval($_POST['id']));
        if(!$result) {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_ERROR');				// 操作失敗
        } else {
            // 關聯刪除使用者其他資訊，執行刪除使用者插件.
            $return['status'] = 1;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_SUCCESS');			// 操作成功
        }
        exit(json_encode($return));
    }
    /**
     * 徹底刪除使用者賬號操作
     * @return json 操作後的JSON資料
     */
    public function doTrueDeleteUser() {
        if(empty($_POST['id'])) {
            $return['status'] = 0;
            $return['data'] = '';
            exit(json_encode($return));
        }

        $result = model('User')->trueDeleteUsers(intval($_POST['id']));
        if(!$result) {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_REMOVE_COMPLETELY_FAIL');				// 操作失敗
        } else {
            // 關聯刪除使用者其他資訊，執行刪除使用者插件.
            $return['status'] = 1;
            $return['data'] = L('PUBLIC_REMOVE_COMPLETELY_SUCCESS');			// 操作成功
        }
        exit(json_encode($return));
    }
    /**
     * 使用者賬號恢復操作
     * @return json 操作後的JSON資料
     */
    public function doRebackUser() {
        if(empty($_POST['id'])) {
            $return['status'] = 0;
            $return['data'] = '';
            exit(json_encode($return));
        }

        $result = model('User')->rebackUsers($_POST['id']);
        if(!$result) {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_ERROR');				// 操作失敗
        } else {
            //關聯刪除使用者其他資訊，執行刪除使用者插件.
            $return['status'] = 1;
            $return['data'] = L('PUBLIC_ADMIN_OPRETING_SUCCESS');			// 操作成功
        }
        exit(json_encode($return));
    }

    /*
     * 使用者資料配置
     * @access public
     */
    public function profile(){

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));


        //欄位列表key值 DOACTION表示操作
        $this->pageKeyList = array('field_id','field_key','field_name','field_type','visiable','editable','required','DOACTION');

        //列表批量操作按鈕ed
        $this->pageButton[] = array('title'=>L('PUBLIC_ADD_FIELD'),'onclick'=>"location.href='".U('admin/User/addProfileField',array('tabHash'=>'addField'))."'");

        $map=array();

        /*資料的格式化 與listKey保持一致 */

        //取使用者列表
        $listData = D('UserProfile')->table(C('DB_PREFIX').'user_profile_setting')
            ->where($map)
            ->order('type,field_type,display_order asc')
            ->findPage(100);
        //dump($listData);exit;
        //資料格式化
        foreach($listData['data'] as $k=>$v){
            if($v['type']==1){
                $type[$v['field_id']]	=	$v;
                $listData['data'][$k]['type'] = '<b>'.L('PUBLIC_SYSTEM_CATEGORY').'</b>';
            }else{
                $listData['data'][$k]['field_type'] = $type[$v['field_type']]['field_name'];
                $listData['data'][$k]['type'] = L('PUBLIC_SYSTEM_FIELD');
            }
            $listData['data'][$k]['visiable'] = $listData['data'][$k]['visiable']==1?L('PUBLIC_SYSTEMD_TRUE'):L('PUBLIC_SYSTEMD_FALSE');
            $listData['data'][$k]['editable'] = $listData['data'][$k]['editable']==1?L('PUBLIC_SYSTEMD_TRUE'):L('PUBLIC_SYSTEMD_FALSE');
            $listData['data'][$k]['required'] = $listData['data'][$k]['required']==1?L('PUBLIC_SYSTEMD_TRUE'):L('PUBLIC_SYSTEMD_FALSE');
            //操作按鈕
            $listData['data'][$k]['DOACTION'] = '<a href="'.U('admin/User/editProfileField',array('tabHash'=>'editField','id'=>$v['field_id'])).'">'.L('PUBLIC_EDIT').'</a> '
                .($v['is_system']==1? '':' -  <a href="javascript:void(0)" onclick="admin.delProfileField(\''.$v['field_id'].'\',1)">'.L('PUBLIC_STREAM_DELETE').'</a>');

            //如果只顯示欄位.刪除資料
            if( $field_type != 1 && $v['type']==1) {
                unset($listData['data'][$k]);
            }
        }

        //$this->_listpk = 'field_id';
        $this->allSelected = false;
        $this->displayList($listData);
    }

    /*
     * 使用者資料分類配置
     * @access public
     */
    public function profileCategory(){

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));

        //分類列表key值 DOACTION表示操作
        $this->pageKeyList = array('field_id','field_key','field_name','DOACTION');

        //列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'onclick'=>"location.href='".U('admin/User/addProfileCategory',array('tabHash'=>'addCateogry'))."'");
        //$this->pageButton[] = array('title'=>'刪除選中','onclick'=>"admin.delProfileField()");

        $map=array();
        $map['type'] = 1;

        /*資料的格式化 與listKey保持一致 */

        //取使用者列表
        $listData = D('UserProfile')->table(C('DB_PREFIX').'user_profile_setting')
            ->where($map)
            ->order('type,field_type,display_order asc')
            ->findPage(100);

        //資料格式化
        foreach($listData['data'] as $k=>$v){
            if($v['type']==1){
                $type[$v['field_id']]	=	$v;
                $listData['data'][$k]['type'] = '<b>'.L('PUBLIC_SYSTEM_CATEGORY').'</b>';
            }else{
                $listData['data'][$k]['field_type'] = $type[$v['field_type']]['field_name'];
                $listData['data'][$k]['type'] = L('PUBLIC_SYSTEM_FIELD');
            }

            //操作按鈕

            $listData['data'][$k]['DOACTION'] = '<a href="'.U('admin/User/editProfileCategory',array('tabHash'=>'addProfileCategory','id'=>$v['field_id'])).'">'.L('PUBLIC_EDIT').'</a> '
                .($v['is_system']==1?' ':' - <a href="javascript:void(0)" onclick="admin.delProfileField(\''.$v['field_id'].'\',0)">'.L('PUBLIC_STREAM_DELETE').'</a>');
        }

        //$this->_listpk = 'field_id';
        $this->allSelected = false;
        $this->displayList($listData);
    }

    /*
     * 新增資料欄位/分類
     * @access public
     *
     */
    public function editProfileCategory(){

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));


        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('field_id','type','field_key','field_name','field_type');
        $this->opt['type'] = array('1'=>L('PUBLIC_SYSTEM_CATEGORY'));

        //獲取配置資訊
        $id = intval($_REQUEST['id']);
        $setting  = D('UserProfileSetting')->where("type=1")->find($id);
        if(!$setting){
            $this->error(L('PUBLIC_INFO_GET_FAIL'));
        }

        $this->savePostUrl = U('admin/User/doSaveProfileField');

        $this->notEmpty = array('field_key','field_name');
        $this->onsubmit = 'admin.checkProfile(this)';

        $this->displayConfig($setting);
    }

    /*
     * 新增資料欄位/分類
     * @access public
     *
     */
    public function addProfileField($edit=false){

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $edit && $this->pageTab[] = array('title'=>L('PUBLIC_EDIT_FIELD'),'tabHash'=>'editField','url'=>U('admin/User/editProfileField',array('id'=>$_REQUEST['id'])));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));

        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('field_id','type','field_key','field_name','field_type','visiable','editable','required','privacy','form_type','form_default_value','validation','tips');
        $this->opt['type'] = array('2'=>L('PUBLIC_SYSTEM_FIELD'));

        //獲取欄位分類列表
        $category = D('UserProfileSetting')->where("type=1")->findAll();
        foreach($category as $c){
            $cate_array[$c['field_id']] = $c['field_name'];
        }

        //欄位選項配置
        $this->opt['field_type'] = $cate_array;
        $this->opt['visiable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['editable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['required'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['privacy'] = array('0'=>L('PUBLIC_WEIBO_COMMENT_ALL'),'1'=>L('PUBLIC_SYSTEM_PARENT_SEE'),'2'=>L('PUBLIC_SYSTEM_FOLLOWING_SEE'),'3'=>L('PUBLIC_SYSTEM_FOLLW_SEE'));
        $this->opt['form_type'] = model('UserProfile')->getUserProfileInputType();

        $detail = !empty($_GET['id']) ? D('UserProfileSetting')->where("field_id='{$_GET['id']}'")->find() : array();
        $this->savePostUrl = !empty($detail) ?  U('admin/User/doSaveProfileField') :  U('admin/User/doAddProfileField');

        $this->notEmpty = array('field_key','field_name','field_type');
        $this->onsubmit = 'admin.checkProfile(this)';
        $this->displayConfig($detail);
    }

    public function editProfileField(){
        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $edit && $this->pageTab[] = array('title'=>L('PUBLIC_EDIT_FIELD'),'tabHash'=>'editField','url'=>U('admin/User/editProfileField',array('id'=>$_REQUEST['id'])));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));

        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('field_id','type','field_key','field_name','field_type','visiable','editable','required','privacy','form_type','form_default_value','validation','tips');
        $this->opt['type'] = array('2'=>L('PUBLIC_SYSTEM_FIELD'));

        //獲取欄位分類列表
        $category = D('UserProfileSetting')->where("type=1")->findAll();
        foreach($category as $c){
            $cate_array[$c['field_id']] = $c['field_name'];
        }

        //欄位選項配置
        $this->opt['field_type'] = $cate_array;
        $this->opt['visiable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['editable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['required'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['privacy'] = array('0'=>L('PUBLIC_WEIBO_COMMENT_ALL'),'1'=>L('PUBLIC_SYSTEM_PARENT_SEE'),'2'=>L('PUBLIC_SYSTEM_FOLLOWING_SEE'),'3'=>L('PUBLIC_SYSTEM_FOLLW_SEE'));
        $this->opt['form_type'] = model('UserProfile')->getUserProfileInputType();

        $detail = !empty($_GET['id']) ? D('UserProfileSetting')->where("field_id='{$_GET['id']}'")->find() : array();
        $this->savePostUrl = !empty($detail) ?  U('admin/User/doSaveProfileField') :  U('admin/User/doAddProfileField');

        $this->notEmpty = array('field_key','field_name','field_type');
        $this->onsubmit = 'admin.checkProfile(this)';
        $this->displayConfig($detail);
        // $this->addProfileField(true);
    }
    /*
     * 新增資料欄位/分類
     * @access public
     *
     */
    public function addProfileCategory(){

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_FIELDLIST'),'tabHash'=>'profile','url'=>U('admin/User/profile'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_CATEGORYLIST'),'tabHash'=>'category','url'=>U('admin/User/profileCategory'));
        $this->pageTab[] = array('title'=>L('PUBLIC_ADD_FIELD'),'tabHash'=>'addField','url'=>U('admin/User/addProfileField'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'tabHash'=>'addCateogry','url'=>U('admin/User/addProfileCategory'));


        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('type','field_key','field_name','field_type');
        $this->opt['type'] = array('1'=>L('PUBLIC_SYSTEM_CATEGORY'));

        //欄位選項配置
        $this->opt['field_type'] = array('0'=>L('PUBLIC_SYSTEM_PCATEGORY'));
        $this->opt['visiable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['editable'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['required'] = array('1'=>L('PUBLIC_SYSTEMD_TRUE'),'0'=>L('PUBLIC_SYSTEMD_FALSE'));
        $this->opt['privacy'] = array('0'=>L('PUBLIC_WEIBO_COMMENT_ALL'),'1'=>L('PUBLIC_SYSTEM_PARENT_SEE'),'2'=>L('PUBLIC_SYSTEM_FOLLOWING_SEE'),'3'=>L('PUBLIC_SYSTEM_FOLLW_SEE'));
        $this->opt['form_type'] = model('UserProfile')->getUserProfileInputType();

        $this->savePostUrl = U('admin/User/doAddProfileField');

        $detail = !empty($_GET['id']) ? D('UserProfileSetting')->where("field_id='{$_GET['id']}'")->find() : array();

        $this->notEmpty = array('field_key','field_name');
        $this->onsubmit = 'admin.checkProfile(this)';

        $this->displayConfig($detail);
    }

    /*
     * 添加資料欄位/分類
     * @access public
     *
     */
    public function doAddProfileField(){
        //dump($_REQUEST);exit;
        $profile = D('UserProfileSetting');
        $map = $profile->create();
        //檢查map返回值.有表單驗證.
        $result = $profile->add($map);
        if($result){
            $jumpUrl = $_POST['type'] == 1 ? U('admin/User/profileCategory',array('tabHash'=>'category')):U('admin/User/profile');
            $this->assign('jumpUrl',$jumpUrl);
            $this->success(L('PUBLIC_ADD_SUCCESS'));
        }else{
            $this->error(L('PUBLIC_ADD_FAIL'));
        }
    }

    /*
     * 儲存資料欄位/分類
     * @access public
     *
     */
    public function doSaveProfileField(){
        $profile = D('UserProfileSetting');
        $map = $profile->create();
        $field_id = intval($_POST['field_id']);

        $jumpUrl = $_POST['type'] == 1 ? U('admin/User/profileCategory',array('tabHash'=>'category')):U('admin/User/profile');
        //檢查map返回值.有表單驗證.
        $result = $profile->where("field_id=".$field_id )->save($map);
        if($result){

            $this->assign('jumpUrl',$jumpUrl);
            $this->success(L('PUBLIC_SYSTEM_MODIFY_SUCCESS'));
        }else{
            $this->error(L('PUBLIC_ADMIN_OPRETING_ERROR'));
        }
    }

    /*
     * 刪除資料欄位/分類
     * @access public
     *
     */
    public function doDeleteProfileField(){
        if(empty($_POST['id'])){
            $return['status'] = 0;
            $return['data']   = '';
            echo json_encode($return);exit();
        }
        if(D('UserProfileSetting')->where('field_type='.intval($_POST['id']))->find()){
            $return['status'] = 0;
            $return['data'] = '刪除失敗，該分類下欄位不為空！';
        }else{
            $result = model('UserProfile')->deleteProfileSet($_POST['id']);
            if(!$result){
                $return['status'] = 0;
                $return['data'] = L('PUBLIC_DELETE_FAIL');
            }else{
                //關聯刪除使用者其他資訊.執行刪除使用者插件.
                $return['status'] = 1;
                $return['data']   = L('PUBLIC_DELETE_SUCCESS');
            }
        }
        echo json_encode($return);exit();
    }

    /*
     * 資料配置預覽
     * @access public
     *
     */


    /**
     * 轉移使用者組
     * Enter description here ...
     */
    public function moveDepartment(){
        $this->display();
    }

    public function domoveDepart(){
        $return = array('status'=>'0','data'=>L('PUBLIC_ADMIN_OPRETING_ERROR'));
        if(!empty($_POST['uid']) && !empty($_POST['topid'])){
            if($res = model('User')->domoveDepart($_POST['uid'],$_POST['topid'])){
                $return = array('status'=>1,'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
                //TODO 記錄日誌
            }else{
                $return['data'] = model('User')->getError();
            }
        }
        echo json_encode($return);exit();
    }

    public function moveGroup(){
        $this->assign('user_group', model('UserGroup')->getHashUsergroup());
        $this->display();
    }

    public function domoveUsergroup(){
        $return = array('status'=>'0','data'=>L('PUBLIC_ADMIN_OPRETING_ERROR'));
        if(!empty($_POST['uid']) && !empty($_POST['user_group_id'])){
            if($res = model('UserGroupLink')->domoveUsergroup($_POST['uid'],$_POST['user_group_id'])){
                $return = array('status'=>1,'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
                //TODO 記錄日誌
            }else{
                $return['data'] = model('UserGroup')->getError();
            }
        }
        echo json_encode($return);exit();
    }

    /**
     * 初始化使用者認證選單
     */
    public function _initVerifyAdminMenu(){
        // tab選項
        $this->pageTab[] = array('title'=>'認證分類', 'tabHash'=>'verifyCategory', 'url'=>U('admin/User/verifyCategory'));
        $this->pageTab[] = array('title'=>'置頂使用者','tabHash'=>'config','url'=>U('admin/User/verifyConfig'));
        $this->pageTab[] = array('title'=>'添加認證使用者','tabHash'=>'addverify','url'=>U('admin/User/addVerify'));
        $this->pageTab[] = array('title'=>'待認證使用者','tabHash'=>'verify','url'=>U('admin/User/verify'));
        $this->pageTab[] = array('title'=>'待認證企業','tabHash'=>'verifyGroup','url'=>U('admin/User/verifyGroup'));
        $this->pageTab[] = array('title'=>'已認證使用者','tabHash'=>'verified','url'=>U('admin/User/verified'));
        $this->pageTab[] = array('title'=>'已認證企業','tabHash'=>'verifiedGroup','url'=>U('admin/User/verifiedGroup'));
    }

    /**
     * 獲取待認證使用者列表
     * @return void
     */
    public function verify() {
        $this->_initVerifyAdminMenu();
        $this->pageButton[] = array('title'=>'駁回認證','onclick'=>"admin.verify('',-1)");

        $this->pageKeyList = array('uname','usergroup_id','category','realname','idcard','phone','reason','info','attachment','DOACTION');
        $listData = D('user_verified')->where('verified=0 and usergroup_id!=6')->findpage(20);
        // 獲取認證分類的Hash陣列
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($listData['data'] as $k=>$v){
            $userinfo = model('user')->getUserInfo($listData['data'][$k]['uid']);
            $listData['data'][$k]['uname'] = $userinfo['uname'];
            $listData['data'][$k]['usergroup_id'] = D('user_group')->where('user_group_id='.$v['usergroup_id'])->getField('user_group_name');
            if($listData['data'][$k]['attach_id']){
                $a = explode('|', $listData['data'][$k]['attach_id']);
                $listData['data'][$k]['attachment'] = "";
                foreach($a as $key=>$val){
                    if($val !== ""){
                        $listData['data'][$k]['attachment'] .= D('attach')->where("attach_id=$a[$key]")->getField('name').'&nbsp;<a href="'.U('widget/Upload/down',array('attach_id'=>$a[$key])).'" target="_blank">下載</a><br />';
                    }
                }
                unset($a);
            }
            $listData['data'][$k]['category'] = $categoryHash[$v['user_verified_category_id']];
            $listData['data'][$k]['reason'] = str_replace(array("\n", "\r"), array('', ''), t($listData['data'][$k]['reason']));
            $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',1,0,\''.$listData['data'][$k]['reason'].'\')">通過</a> - ';
            $listData['data'][$k]['DOACTION'] .= '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',-1)">駁回</a>';

        }
        $this->displayList($listData);
    }

    /**
     * 獲取待認證企業列表
     * @return void
     */
    public function verifyGroup() {
        $this->_initVerifyAdminMenu();
        $this->pageButton[] = array('title'=>'駁回認證','onclick'=>"admin.verify('',-1,6)");

        $this->pageKeyList = array('uname','usergroup_id','category','company','realname','idcard','phone','reason','info','attachment','DOACTION');
        $listData = D('user_verified')->where('verified=0 and usergroup_id=6')->findpage(20);
        // 獲取認證分類的Hash陣列
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($listData['data'] as $k=>$v){
            $userinfo = model('user')->getUserInfo($listData['data'][$k]['uid']);
            $listData['data'][$k]['uname'] = $userinfo['uname'];
            $listData['data'][$k]['usergroup_id'] = D('user_group')->where('user_group_id='.$v['usergroup_id'])->getField('user_group_name');
            if($listData['data'][$k]['attach_id']){
                $a = explode('|', $listData['data'][$k]['attach_id']);
                $listData['data'][$k]['attachment'] = "";
                foreach($a as $key=>$val){
                    if($val !== ""){
                        $listData['data'][$k]['attachment'] .= D('attach')->where("attach_id=$a[$key]")->getField('name').'&nbsp;<a href="'.U('widget/Upload/down',array('attach_id'=>$a[$key])).'" target="_blank">下載</a><br />';
                    }
                }
                unset($a);
            }
            $listData['data'][$k]['category'] = $categoryHash[$v['user_verified_category_id']];
            $listData['data'][$k]['reason'] = str_replace(array("\n", "\r"), array('', ''), $listData['data'][$k]['reason']);
            $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',1,0,\''.$listData['data'][$k]['reason'].'\')">通過</a> - ';
            $listData['data'][$k]['DOACTION'] .= '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',-1)">駁回</a>';
        }
        $this->displayList($listData);
    }

    /**
     * 獲取已認證使用者列表
     * @return void
     */
    public function verified() {
        $this->_initVerifyAdminMenu();
        $this->pageButton[] = array('title'=>'駁回認證','onclick'=>"admin.verify('',-1)");

        $this->pageKeyList = array('uname','usergroup_id','category','realname','idcard','phone','reason','info','attachment','DOACTION');
        $listData = D('user_verified')->where('verified=1 and usergroup_id!=6')->order('id DESC')->findpage(20);
        // 獲取認證分類的Hash陣列
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($listData['data'] as $k=>$v){
            $userinfo = model('user')->getUserInfo($listData['data'][$k]['uid']);
            $listData['data'][$k]['uname'] = $userinfo['uname'];
            $listData['data'][$k]['usergroup_id'] = D('user_group')->where('user_group_id='.$v['usergroup_id'])->getField('user_group_name');
            if($listData['data'][$k]['attach_id']){
                $a = explode('|', $listData['data'][$k]['attach_id']);
                $listData['data'][$k]['attachment'] = "";
                foreach($a as $key=>$val){
                    if($val !== ""){
                        $listData['data'][$k]['attachment'] .= D('attach')->where("attach_id=$a[$key]")->getField('name').'&nbsp;<a href="'.U('widget/Upload/down',array('attach_id'=>$a[$key])).'" target="_blank">下載</a><br />';
                    }
                }
                unset($a);
            }
            $listData['data'][$k]['category'] = $categoryHash[$v['user_verified_category_id']];
            $listData['data'][$k]['DOACTION'] = '<a href="'.U('admin/User/editVerify',array('tabHash'=>'verified','id'=>$v['id'])).'">編輯</a> - ';
            $listData['data'][$k]['DOACTION'] .= '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',-1)">駁回</a>';
        }
        $this->displayList($listData);
    }

    /**
     * 獲取已認證企業列表
     * @return void
     */
    public function verifiedGroup() {
        $this->_initVerifyAdminMenu();
        $this->pageButton[] = array('title'=>'駁回認證','onclick'=>"admin.verify('',-1,6)");

        $this->pageKeyList = array('uname','usergroup_id','category','company','realname','idcard','phone','reason','info','attachment','DOACTION');
        $listData = D('user_verified')->where('verified=1 and usergroup_id=6')->order('id DESC')->findpage(20);
        // 獲取認證分類的Hash陣列
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($listData['data'] as $k=>$v){
            $userinfo = model('user')->getUserInfo($listData['data'][$k]['uid']);
            $listData['data'][$k]['uname'] = $userinfo['uname'];
            $listData['data'][$k]['usergroup_id'] = D('user_group')->where('user_group_id='.$v['usergroup_id'])->getField('user_group_name');
            if($listData['data'][$k]['attach_id']){
                $a = explode('|', $listData['data'][$k]['attach_id']);
                $listData['data'][$k]['attachment'] = "";
                foreach($a as $key=>$val){
                    if($val !== ""){
                        $listData['data'][$k]['attachment'] .= D('attach')->where("attach_id=$a[$key]")->getField('name').'&nbsp;<a href="'.U('widget/Upload/down',array('attach_id'=>$a[$key])).'" target="_blank">下載</a><br />';
                    }
                }
                unset($a);
            }
            $listData['data'][$k]['category'] = $categoryHash[$v['user_verified_category_id']];
            $listData['data'][$k]['DOACTION'] = '<a href="'.U('admin/User/editVerify',array('tabHash'=>'verifiedGroup','id'=>$v['id'])).'">編輯</a> - ';
            $listData['data'][$k]['DOACTION'] .= '<a href="javascript:void(0)" onclick="admin.verify('.$v['id'].',-1)">駁回</a>';
        }
        $this->displayList($listData);
    }

    /**
     * 執行認證
     * @return json 返回操作後的JSON資訊資料
     */
    public function doVerify(){
        $status = intval($_POST['status']);
        $id = $_POST['id'];
        if(is_array($id)){
            $map['id'] = array('in',$id);
        }else{
            $map['id'] = $id;
        }
        $datas['verified'] = $status;
        if($_POST['info']){
            $datas['info'] = t($_POST['info']);
        }
        $res = D('user_verified')->where($map)->save($datas);
        if($res){
            $return['status'] = 1;
            if($status == 1){
                $return['data']   = "認證成功";
                //$data['content'] = '';
                if(is_array($id)){
                    foreach($id as $k=>$v){
                        $user_group = D('user_verified')->where('id='.$v)->find();
                        $maps['uid'] = $user_group['uid'];
                        $maps['user_group_id'] = $user_group['usergroup_id'];
                        $exist = D('user_group_link')->where($maps)->find();
                        if ( $exist ){
                            continue;
                        }
                        D('user_group_link')->add($maps);
                        // 清除使用者組快取
                        model ( 'Cache' )->rm ('user_group_'.$user_group['uid']);
                        // 清除許可權快取
                        model('Cache')->rm('perm_user_'.$user_group['uid']);
                        model('Notify')->sendNotify($user_group['uid'],'admin_user_doverify_ok');
                        unset($user_group);
                        unset($maps);
                    }
                }else{
                    $user_group = D('user_verified')->where('id='.$id)->find();
                    $maps['uid'] = $user_group['uid'];
                    $maps['user_group_id'] = $user_group['usergroup_id'];
                    $exist = D('user_group_link')->where($maps)->find();
                    if ( !$exist ){
                        D('user_group_link')->add($maps);
                        // 清除使用者組快取
                        model ( 'Cache' )->rm ('user_group_'.$user_group['uid']);
                        // 清除許可權快取
                        model('Cache')->rm('perm_user_'.$user_group['uid']);
                        model('Notify')->sendNotify($user_group['uid'],'admin_user_doverify_ok');
                    }
                }
            }
            if($status == -1){
                $return['data']	  = "駁回成功";
                //$data['act'] = '駁回';
                if(is_array($id)){
                    foreach($id as $k=>$v){
                        $user_group = D('user_verified')->where('id='.$v)->find();
                        $maps['uid'] = $user_group['uid'];
                        $maps['user_group_id'] = $user_group['usergroup_id'];
                        D('user_group_link')->where($maps)->delete();
                        // 清除使用者組快取
                        model ( 'Cache' )->rm ('user_group_'.$user_group['uid']);
                        // 清除許可權快取
                        model('Cache')->rm('perm_user_'.$user_group['uid']);
                        model('Notify')->sendNotify($user_group['uid'],'admin_user_doverify_reject');
                        unset($user_group);
                        unset($maps);
                    }
                }else{
                    $user_group = D('user_verified')->where('id='.$id)->find();
                    $maps['uid'] = $user_group['uid'];
                    $maps['user_group_id'] = $user_group['usergroup_id'];
                    D('user_group_link')->where($maps)->delete();
                    // 清除使用者組快取
                    model ( 'Cache' )->rm ('user_group_'.$user_group['uid']);
                    // 清除許可權快取
                    model('Cache')->rm('perm_user_'.$user_group['uid']);
                    model('Notify')->sendNotify($user_group['uid'],'admin_user_doverify_reject');
                }
            }

        }else{
            $return['status'] = 0;
            $return['data']   = "認證失敗";
        }
        echo json_encode($return);exit();
    }

    /**
     * 添加認證使用者或認證企業
     * @return void
     */
    public function addVerify() {
        $this->_initVerifyAdminMenu();
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('uname','usergroup_id','user_verified_category_id','company','realname','idcard','phone','reason','info','attach');
        // 欄位選項配置
        $auType = model('UserGroup')->where('is_authenticate=1')->select();
        foreach($auType as $k=>$v){
            $this->opt['usergroup_id'][$v['user_group_id']] = $v['user_group_name'];
        }
        // 認證分類配置
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($categoryHash as $key => $value) {
            $this->opt['user_verified_category_id'][$key] = $value;
        }
        // 表單URL設定
        $this->savePostUrl = U('admin/User/doAddVerify');
        $this->notEmpty = array('uname','usergroup_id','company','realname','idcard','phone','reason','info');
        $this->onload[] = "admin.addVerifyConfig(5)";
        //$this->onsubmit = 'admin.addVerifySubmitCheck(this)';

        $this->displayConfig();
    }

    /**
     * 執行添加認證
     * @return void
     */
    public function doAddVerify(){
        $data['uid'] = $_POST['uname'];
        $result = D('user_verified')->where('uid='.$data['uid'])->find();
        if($result){
            if($result['verified'] == 1){
                $this->error('該使用者已通過認證');
            }else{
                D('user_verified')->where('uid='.$data['uid'])->delete();
            }
        }

        $data['usergroup_id'] = intval($_POST['usergroup_id']);
        if($_POST['company']){
            $data['company'] = t($_POST['company']);
        }
        $data['realname'] = t($_POST['realname']);
        $data['idcard'] = t($_POST['idcard']);
        $data['phone'] = t($_POST['phone']);
        $data['reason'] = t($_POST['reason']);
        $data['info'] = t($_POST['info']);
        //	$data['attachment'] = t($_POST['attach']);
        $data['attach_id'] = t($_POST['attach_ids']);
        $data['user_verified_category_id'] = intval($_POST['user_verified_category_id']);
        $Regx1 = '/^[0-9]*$/';
        $Regx2 = '/^[A-Za-z0-9]*$/';
        $Regx3 = '/^[A-Za-z|\x{4e00}-\x{9fa5}]+$/u';
        if($data['usergroup_id'] == 6){
            if(strlen($data['company'])==0){
                $this->error('企業名稱不能為空');
            }
            if(strlen($data['realname'])==0){
                $this->error('法人姓名不能為空');
            }
            if(strlen($data['idcard'])==0){
                $this->error('營業執照號不能為空');
            }
            if(strlen($data['phone'])==0){
                $this->error('聯繫方式不能為空');
            }
            if(strlen($data['reason'])==0){
                $this->error('認證理由不能為空');
            }
            if(strlen($data['info'])==0){
                $this->error('認證資料不能為空');
            }
            if(preg_match($Regx2, $data['idcard'])==0){
                $this->error('請輸入正確的營業執照號');
            }

        }else{
            if(strlen($data['realname'])==0){
                $this->error('真實姓名不能為空');
            }
            if(strlen($data['idcard'])==0){
                $this->error('身份證號碼不能為空');
            }
            if(strlen($data['phone'])==0){
                $this->error('手機號碼不能為空');
            }
            if(strlen($data['reason'])==0){
                $this->error('認證理由不能為空');
            }
            if(strlen($data['info'])==0){
                $this->error('認證資料不能為空');
            }
            if(preg_match($Regx3, $data['realname'])==0 || strlen($data['realname'])>30){
                $this->error('請輸入正確的姓名格式');
            }
            if(preg_match($Regx2, $data['idcard'])==0 || preg_match($Regx1, substr($data['idcard'],0,17))==0 || strlen($data['idcard'])!==18){
                $this->error('請輸入正確的身份證號碼');
            }
            if(strlen($data['phone']) !== 11 || preg_match($Regx1, $data['phone'])==0){
                $this->error('請輸入正確的手機號碼格式');
            }
        }
        // preg_match_all('/./us', $data['reason'], $matchs);   //一個漢字也為一個字元
        // if(count($matchs[0])>140){
        // 	$this->error('認證理由不能超過140個字元');
        // }
        // preg_match_all('/./us', $data['info'], $match);   //一個漢字也為一個字元
        // if(count($match[0])>140){
        // 	$this->error('認證資料不能超過140個字元');
        // }
        $data['verified'] = 1;
        $res = D('user_verified')->add($data);
        $map['uid'] = $_POST['uname'];
        $map['user_group_id'] = intval($_POST['usergroup_id']);
        $res2 = D('user_group_link')->add($map);
        // 清除使用者組快取
        model ( 'Cache' )->rm ('user_group_'.$map['uid']);
        // 清除許可權快取
        model('Cache')->rm('perm_user_'.$map['uid']);
        if($res && $res2){
            $this->success('添加認證成功');
        }else{
            $this->error('認證失敗');
        }
    }

    /**
     * 通過時編輯認證資料
     * @return  void
     */
    public function editVerifyInfo(){
        $this->assign('id',intval($_GET['id']));
        $this->assign('status',intval($_GET['status']));
        $this->assign('info',t($_GET['info']));
        $this->display();
    }

    /**
     * 編輯認證資料
     * @return void
     */
    public function editVerify(){
        $this->_initVerifyAdminMenu();

        $this->pageKeyList = array('uid','uname','usergroup_id','user_verified_category_id','company','realname','idcard','phone','reason','info','attach');

        $id = intval($_REQUEST['id']);
        $verifyInfo  = D('user_verified')->where('id='.$id)->find();
        $userinfo = model('user')->getUserInfo($verifyInfo['uid']);
        $verifyInfo['uname'] = $userinfo['uname'];
        // 認證分類配置
        $categoryHash = model('CategoryTree')->setTable('user_verified_category')->getCategoryHash();
        foreach($categoryHash as $key => $value) {
            $this->opt['user_verified_category_id'][$key] = $value;
        }
        // 認證組
        $auType = model('UserGroup')->where('is_authenticate=1')->select();
        foreach($auType as $k=>$v){
            $this->opt['usergroup_id'][$v['user_group_id']] = $v['user_group_name'];
        }

        $verifyInfo['attach']=str_replace('|',',',substr($verifyInfo['attach_id'],1,strlen($verifyInfo['attach_id'])-2));

        $this->savePostUrl = U('admin/User/doEditVerify');
        $this->onsubmit = 'admin.editVerifySubmitCheck(this)';
        $this->notEmpty = array('usergroup_id','company','realname','idcard','phone','reason','info');
        $this->onload[] = "admin.addVerifyConfig({$verifyInfo['usergroup_id']})";
        $this->displayConfig($verifyInfo);
    }

    /**
     * 執行編輯認證資料
     * @return void
     */
    public function doEditVerify(){
        $uid = intval($_POST['uid']);
        $old_group_id = D('user_verified')->where('uid='.$uid)->getField('usergroup_id');
        $data['usergroup_id'] = intval($_POST['usergroup_id']);
        if($data['usergroup_id'] == 6){
            $data['company'] = t($_POST['company']);
        }
        $data['realname'] = t($_POST['realname']);
        $data['idcard'] = t($_POST['idcard']);
        $data['phone'] = t($_POST['phone']);
        $data['reason'] = t($_POST['reason']);
        $data['info'] = t($_POST['info']);
        $data['attach_id'] = t($_POST['attach_ids']);
        $data['user_verified_category_id'] = intval($_POST['user_verified_category_id']);
        //dump($data);exit;
        $res = D('user_verified')->where('uid='.$uid)->save($data);
        if($old_group_id != $data['usergroup_id']){
            D('user_group_link')->where('uid='.$uid.' and user_group_id='.$old_group_id)->setField('user_group_id',$data['usergroup_id']);
        }
        // 清除使用者組快取
        model ( 'Cache' )->rm ('user_group_'.$uid);
        // 清除許可權快取
        model('Cache')->rm('perm_user_'.$uid);
        if($res){
            $this->success('編輯成功');
        }else{
            $this->error('編輯失敗');
        }
    }

    public function getVerifyCategory(){
        $category = D('user_verified_category')->where('pid='.intval($_POST['value']))->findAll();
        foreach($category as $k=>$v){
            $option .= '<option ';
            // if(intval($_POST['category_id'])==$v['user_verified_category_id']){
            // 	$option[$v['pid']] .= 'selected';
            // }
            $option .= ' value="'.$v['user_verified_category_id'].'">'.$v['title'].'</option>';
        }
        echo $option;
    }

    /**
     * 推薦標籤 - 列表顯示
     */
    public function category()
    {
        $_GET['pid'] = intval($_GET['pid']);
        $treeData = model('CategoryTree')->setTable('user_category')->getNetworkList();
        // 配置刪除關聯資訊
        $this->displayTree($treeData, 'user_category', 2, '', '', 10);
    }

    /**
     * 認證分類展示頁面
     * @return void
     */
    public function verifyCategory()
    {
        // 初始化Tab資訊
        $this->_initVerifyAdminMenu();
        // 分類相關資料
        //$_GET['pid'] = intval($_GET['pid']);
        //$treeData = model('CategoryTree')->setTable('user_verified_category')->getNetworkList();

        //$this->displayTree($treeData, 'user_verified_category');

        //分類列表key值 DOACTION表示操作
        $this->pageKeyList = array('user_verified_category_id','title','pCategory','DOACTION');

        //列表批量操作按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SYSTEM_ADD_CATEGORY'),'onclick'=>"admin.addVerifyCategory()");

        //取使用者列表
        $listData = D('user_verified_category')->findpage(20);
        //資料格式化
        foreach($listData['data'] as $k=>$v){
            $listData['data'][$k]['pCategory'] = model('UserGroup')->where('is_authenticate=1 AND user_group_id='.$v['pid'])->getField('user_group_name');

            //操作按鈕

            $listData['data'][$k]['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.editVerifyCategory('.$v['user_verified_category_id'].')">'.L('PUBLIC_EDIT').'</a> '
                .($v['is_system']==1?' ':' - <a href="javascript:void(0)" onclick="admin.delVerifyCategory('.$v['user_verified_category_id'].')">'.L('PUBLIC_STREAM_DELETE').'</a>');
        }

        //$this->_listpk = 'field_id';
        $this->allSelected = false;
        $this->displayList($listData);
    }

    /**
     * 添加認證分類
     * @return void
     */
    public function addVerifyCategory()
    {
        $vType = model('UserGroup')->where('is_authenticate=1')->findAll();
        $this->assign('vType',$vType);
        $this->display('editVerifyCategory');
    }

    /**
     * 編輯認證分類
     * @return void
     */
    public function editVerifyCategory()
    {
        $vType = model('UserGroup')->where('is_authenticate=1')->findAll();
        $this->assign('vType',$vType);
        $user_verified_category_id = intval($_GET['user_verified_category_id']);
        $cateInfo = D('user_verified_category')->where('user_verified_category_id='.$user_verified_category_id)->find();
        $this->assign('cateInfo',$cateInfo);
        $this->display('editVerifyCategory');
    }

    /**
     * 執行添加認證分類
     */
    public function doAddVerifyCategory(){
        $data['pid'] = intval($_POST['pid']);
        $data['title'] = t($_POST['title']);
        if(D('user_verified_category')->where($data)->find()){
            $return['status'] = 0;
            $return['data'] = '此分類已存在';
        }else{
            if(D('user_verified_category')->add($data)){
                $return['status'] = 1;
                $return['data'] = '添加成功';
            }else{
                $return['status'] = 0;
                $return['data'] = '添加失敗';
            }
        }
        echo json_encode($return);exit();
    }

    /**
     * 執行編輯認證分類
     */
    public function doEditVerifyCategory(){
        $data['pid'] = intval($_POST['pid']);
        $data['title'] = t($_POST['title']);
        $user_verified_category_id = intval($_POST['user_verified_category_id']);
        if(D('user_verified_category')->where($data)->find()){
            $return['status'] = 0;
            $return['data'] = '此分類已存在';
        }else{
            $old_pid = D('user_verified_category')->where('user_verified_category_id='.$user_verified_category_id)->getField('pid');
            if(D('user_verified_category')->where('user_verified_category_id='.$user_verified_category_id)->save($data) !== false){
                if($old_pid != $data['pid']){
                    D('user_verified')->where('user_verified_category_id='.$user_verified_category_id)->setField('usergroup_id',$data['pid']);
                    $datas['uid'] = array('in', getSubByKey(D('user_verified')->where('user_verified_category_id='.$user_verified_category_id)->field('uid')->findAll(),'uid'));
                    $datas['user_group_id'] = $old_pid;
                    D('user_group_link')->where($datas)->setField('user_group_id',$data['pid']);
                }
                $return['status'] = 1;
                $return['data'] = '編輯成功';
            }else{
                $return['status'] = 0;
                $return['data'] = '編輯失敗';
            }
        }
        echo json_encode($return);exit();
    }

    /**
     * 刪除認證分類
     */
    public function delVerifyCategory(){
        $user_verified_category_id = intval($_POST['user_verified_category_id']);
        if(D('user_verified_category')->where('user_verified_category_id='.$user_verified_category_id)->delete()){
            $return['status'] = 1;
            $return['data'] = '刪除成功';
        }else{
            $return['status'] = 0;
            $return['data'] = '刪除失敗';
        }
        echo json_encode($return);exit();
    }

    /**
     * 認證使用者基本配置
     * @return void
     */
    public function verifyConfig()
    {
        // 配置使用者基本資訊
        $this->_initVerifyAdminMenu();
        // 配置使用者存儲基本欄位
        $this->pageKeyList = array('top_user');
        // 顯示配置列表
        $this->displayConfig();
    }

    /**
     * 找人全局
     */
    public function findPeopleConfig(){
        // tab選項
        $this->pageTab[] = array('title'=>'找人配置','tabHash'=>'findPeopleConfig','url'=>U('admin/User/findPeopleConfig'));
        // 配置使用者存儲基本欄位
        $this->pageKeyList = array('findPeople');
        $findtype['tag'] = '按標籤';
        $findtype['area'] = '按地區';
        $findtype['verify'] = '認證使用者';
        $findtype['official'] = '官方推薦';
        $this->opt['findPeople'] = $findtype;
        // 顯示配置列表
        $this->displayConfig();
    }

    /**
     * 官方使用者配置
     * @return void
     */
    public function official()
    {
        // 初始化
        $this->_officialInit();
        // 配置使用者存儲基本欄位
        $this->pageKeyList = array('top_user');
        // 顯示配置列表
        $this->displayConfig();
    }

    /*** 官方使用者 ***/

    /**
     * 官方使用者分類
     * @return void
     */
    public function officialCategory()
    {
        // 初始化
        $this->_officialInit();
        // 獲取分類資訊
        $_GET['pid'] = intval($_GET['pid']);
        $treeData = model('CategoryTree')->setTable('user_official_category')->getNetworkList();
        // 刪除分類關聯資訊
        $delParam['module'] = 'UserOfficial';
        $delParam['method'] = 'deleteAssociatedData';
        $this->displayTree($treeData, 'user_official_category', 1, $delParam);
    }

    /**
     * 官方使用者列表
     */
    public function officialList()
    {
        // 設定列表主鍵
        $this->_listpk = 'official_id';
        // 初始化
        $this->_officialInit();
        // 列表批量操作按鈕
        $this->pageButton[] = array('title'=>'移除','onclick'=>"admin.removeOfficialUser()");
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('official_id','uid','uname','title','info','DOACTION');
        // 獲取使用者列表
        $listData = model('UserOfficial')->getUserOfficialList();
        // 組裝資料
        foreach($listData['data'] as &$value) {
            $user_category = model('CategoryTree')->setTable('user_official_category')->getCategoryById($value['user_official_category_id']);
            $value['title'] = $user_category['title'];
            $value['DOACTION'] = '<a href="javascript:;" onclick="admin.removeOfficialUser('.$value['official_id'].')">移除</a>';
        }

        $this->displayList($listData);
    }

    /**
     * 添加官方使用者介面
     * @return void
     */
    public function officialAddUser()
    {
        $_REQUEST['tabHash'] = 'officialAddUser';
        // 初始化
        $this->_officialInit();
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('uids', 'category','info');
        // 欄位選項配置
        $this->opt['category'] = model('CategoryTree')->setTable('user_official_category')->getCategoryHash();
        // 表單URL設定
        $this->savePostUrl = U('admin/User/doOfficialAddUser');
        $this->notEmpty = array('uids','category');

        $this->displayConfig();
    }

    /**
     * 添加官方使用者操作
     * @return void
     */
    public function doOfficialAddUser()
    {
        //dump($_REQUEST);exit;
        if(empty($_REQUEST['uids']) || empty($_REQUEST['category'])) {
            $this->error('請添加使用者');
            return false;
        }
        $uids = t($_REQUEST['uids']);
        $cid = intval($_REQUEST['category']);
        $info = t($_REQUEST['info']);
        $result = model('UserOfficial')->addOfficialUser($uids, $cid, $info);
        // 添加後跳轉
        if($result) {
            $this->assign('jumpUrl', U('admin/User/officialAddUser'));
            $this->success('操作成功');
        } else {
            $this->error('操作失敗');
        }
        }

        /**
         * 移除官方使用者操作
         * @return json 操作後返回的JSON資料
         */
        public function doRemoveOfficialUser()
        {
            $ids = t($_POST['id']);
            $res = array();
            if(empty($ids)) {
                $res['status'] = 0;
                $res['data'] = '請選擇使用者';
        }else{
            // 刪除操作
            $result = model('UserOfficial')->removeUserOfficial($ids);
            // 返回結果集
            if($result) {
                $res['status'] = 1;
                $res['data'] = '操作成功';
        } else {
            $res['status'] = 0;
            $res['data'] = '操作失敗';
        }
        }
        exit(json_encode($res));
        }

        /**
         * 初始化官方使用者Tab標籤選項
         * @return void
         */
        private function _officialInit()
        {
            $this->pageTab[] = array('title'=>'推薦分類','tabHash'=>'officialCategory','url'=>U('admin/User/officialCategory'));
            $this->pageTab[] = array('title'=>'置頂使用者','tabHash'=>'official','url'=>U('admin/User/official'));
            $this->pageTab[] = array('title'=>'添加推薦使用者','tabHash'=>'officialAddUser','url'=>U('admin/User/officialAddUser'));
            $this->pageTab[] = array('title'=>'已推薦使用者','tabHash'=>'officialList','url'=>U('admin/User/officialList'));
        }
        }
