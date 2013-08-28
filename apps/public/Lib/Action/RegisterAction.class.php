<?php
/**
 * RegisterAction 註冊模組
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class RegisterAction extends Action
{
    private $_config;                   // 註冊配置資訊欄位
    private $_register_model;           // 註冊模型欄位
    private $_user_model;               // 使用者模型欄位
    private $_invite;                   // 是否是邀請註冊
    private $_invite_code;              // 邀請碼

    /**
     * 模組初始化，獲取註冊配置資訊、使用者模型物件、註冊模型物件、邀請註冊與站點頭部資訊設定
     * @return void
     */
    protected function _initialize()
    {
        $this->_invite = false;
        // 未啟用與未稽覈使用者
        if($this->mid > 0 && !in_array(ACTION_NAME, array('changeActivationEmail', 'activate', 'isEmailAvailable'))) {
            $GLOBALS['ts']['user']['is_audit'] == 0 && ACTION_NAME != 'waitForAudit' && U('public/Register/waitForAudit', array('uid'=>$this->mid), true);
            $GLOBALS['ts']['user']['is_audit'] == 1 && $GLOBALS['ts']['user']['is_active'] == 0 && ACTION_NAME != 'waitForActivation' && U('public/Register/waitForActivation', array('uid'=>$this->mid), true);
        }
        // 登入後，將不顯示註冊頁面
        $this->mid > 0 && $GLOBALS['ts']['user']['is_init'] == 1 && redirect($GLOBALS['ts']['site']['home_url']);

        $this->_config = model('Xdata')->get('admin_Config:register');
        $this->_user_model = model('User');
        $this->_register_model = model('Register');
        $this->setTitle(L('PUBLIC_REGISTER'));
    }
    public function code(){
        if (md5(strtoupper($_POST['verify'])) == $_SESSION['verify']) {
            echo 1;
        }else{
            echo 0;
        }
    }
    /**
     * 默認註冊頁面 - 註冊表單頁面
     * @return void
     */
    public function index()
    {
        $this->appCssList[] = 'login.css';
        // 驗證是否有鑰匙 - 邀請註冊問題
        if(empty($this->mid)) {
            if((isset($_GET['invite']) || $this->_config['register_type'] != 'open') && !in_array(ACTION_NAME, array('isEmailAvailable', 'isUnameAvailable', 'doStep1'))) {
                // 提示資訊語言
                $messageHash = array('invite'=>'抱歉，本站目前僅支援邀請註冊。', 'admin'=>'抱歉，本站目前僅支援管理員邀請註冊。');
                $message = $messageHash[$this->_config['register_type']];
                if(!isset($_GET['invite'])) {
                    $this->error($message);
                }
                $inviteCode = t($_GET['invite']);
                $status = model('Invite')->checkInviteCode($inviteCode, $this->_config['register_type']);
                if($status == 1) {
                    $this->_invite = true;
                    $this->_invite_code = $inviteCode;
                } else if($status == 2) {
                    $this->error('抱歉，該邀請碼已使用。');
                } else {
                    $this->error($message);
                }
            }
        }
        // 若是邀請註冊，獲取邀請人相關資訊
        if($this->_invite) {
            $inviteInfo = model('Invite')->getInviterInfoByCode($this->_invite_code);
            $this->assign('inviteInfo', $inviteInfo);
        }
        $this->assign('is_invite', $this->_invite);
        $this->assign('invite_code', $this->_invite_code);
        $this->assign('config', $this->_config);
        $this->assign('invate_key', t($_GET['key']));
        $this->assign('invate_uid', t($_GET['uid']));
        $this->setTitle('填寫註冊資訊');
        $this->setKeywords('填寫註冊資訊');
        $this->display();
    }

    /**
     * 第三方帳號整合 - 繫結本地帳號
     * @return void
     */
    public function doBindStep1(){

        $email = t($_POST['email']);
        $password = trim($_POST['password']);

        $user = model('Passport')->getLocalUser($email,$password);
        if(isset($user['uid']) && $user['uid']>0 ) {

            //註冊來源-第三方帳號繫結
            if(isset($_POST['other_type'])){
                $other['type'] = t($_POST['other_type']);
                $other['type_uid'] = t($_POST['other_uid']);
                $other['oauth_token'] = t($_POST['oauth_token']);
                $other['oauth_token_secret'] = t($_POST['oauth_token_secret']);
                $other['uid'] = $user['uid'];
                D('Login')->add($other);
            }else{
                $this->error('繫結失敗，第三方資訊不正確');
            }

            //判斷是否需要稽覈
            D('Passport')->loginLocal($email,$password);
            $this->assign('jumpUrl', U('public/Passport/login'));
            $this->success('恭喜您，繫結成功');
        } else {
            $this->error('繫結失敗，請確認帳號密碼正確');           // 註冊失敗
        }
    }

    /**
     * 第三方帳號整合 - 註冊新賬號
     * @return void
     */
    public function doOtherStep1(){

        $email = t($_POST['email']);
        $uname = t($_POST['uname']);
        $sex = 1 == $_POST['sex'] ? 1 : 2;

        if(!$this->_register_model->isValidName($uname)) {
            $this->error($this->_register_model->getLastError());
        }

        if(!$this->_register_model->isValidEmail($email)) {
            $this->error($this->_register_model->getLastError());
        }

        $login_salt = rand(11111, 99999);

        //如果繫結帳號不允許繫結密碼，則生成隨機生成密碼，以後可以通過找回密碼修改
        if(!isset($_POST['password'])){
            $password = md5(uniqid());
        }else{
            $password = trim($_POST['password']);
            $repassword = trim($_POST['repassword']);
            if(!$this->_register_model->isValidPassword($password, $repassword)){
                $this->error($this->_register_model->getLastError());
            }
        }

        $map['uname'] = $uname;
        $map['sex'] = $sex;
        $map['login_salt'] = $login_salt;
        $map['password'] = md5(md5($password).$login_salt);
        $map['login'] = $map['email'] = $email;
        $map['reg_ip'] = get_client_ip();
        $map['ctime'] = time();

        // 添加地區資訊
        $map['location'] = t($_POST['city_names']);
        $cityIds = t($_POST['city_ids']);
        $cityIds = explode(',', $cityIds);
        isset($cityIds[0]) && $map['province'] = intval($cityIds[0]);
        isset($cityIds[1]) && $map['city'] = intval($cityIds[1]);
        isset($cityIds[2]) && $map['area'] = intval($cityIds[2]);

        // 審核狀態： 0-需要稽覈；1-通過稽覈
        $map['is_audit'] = $this->_config['register_audit'] ? 0 : 1;
        $map['is_active'] = $this->_config['need_active'] ? 0 : 1;
        $map['first_letter'] = getFirstLetter($uname);

        //如果包含中文將中文翻譯成拼音
        if ( preg_match('/[\x7f-\xff]+/', $map['uname'] ) ){
            //昵稱和呢稱拼音儲存到搜索欄位
            $map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin( $map['uname'] );
        } else {
            $map['search_key'] = $map['uname'];
        }

        $uid = $this->_user_model->add($map);
        if($uid) {

            //儲存頭像
            if($_POST['avatar']==1)
                model('Avatar')->saveRemoteAvatar(t($_POST['other_face']),$uid);

            // 添加積分
            model('Credit')->setUserCredit($uid,'init_default');

            // 添加至默認的使用者組
            $userGroup = model('Xdata')->get('admin_Config:register');
            $userGroup = empty($userGroup['default_user_group']) ? C('DEFAULT_GROUP_ID') : $userGroup['default_user_group'];
            model('UserGroupLink')->domoveUsergroup($uid, implode(',', $userGroup));

            //註冊來源-第三方帳號繫結
            if(isset($_POST['other_type'])){
                $other['type'] = t($_POST['other_type']);
                $other['type_uid'] = t($_POST['other_uid']);
                $other['oauth_token'] = t($_POST['oauth_token']);
                $other['oauth_token_secret'] = t($_POST['oauth_token_secret']);
                $other['uid'] = $uid;
                D('login')->add($other);
            }

            //判斷是否需要稽覈
            if($this->_config['register_audit']) {
                $this->redirect('public/Register/waitForAudit', array('uid' => $uid));
            } else {
                if($this->_config['need_active']){
                    $this->_register_model->sendActivationEmail($uid);
                    $this->redirect('public/Register/waitForActivation', array('uid' => $uid));
                }else{
                    D('Passport')->loginLocal($email,$password);
                    $this->assign('jumpUrl', U('public/Passport/login'));
                    $this->success('恭喜您，註冊成功');
                }
            }

        } else {
            $this->error(L('PUBLIC_REGISTER_FAIL'));            // 註冊失敗
        }
    }

    /**
     * 註冊流程 - 執行第一步驟
     * @return void
     */
    public function doStep1(){

        $invite = t($_POST['invate']);
        $inviteCode = t($_POST['invate_key']);
        $email = t($_POST['email']);
        $uname = t($_POST['uname']);
        $sex = 1 == $_POST['sex'] ? 1 : 2;
        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);

        //檢查驗證碼
        if (md5(strtoupper($_POST['verify'])) != $_SESSION['verify']) {
            $this->error('驗證碼錯誤');
        }

        if(!$this->_register_model->isValidName($uname)) {
            $this->error($this->_register_model->getLastError());
        }

        if(!$this->_register_model->isValidEmail($email)) {
            $this->error($this->_register_model->getLastError());
        }

        if(!$this->_register_model->isValidPassword($password, $repassword)){
            $this->error($this->_register_model->getLastError());
        }

        // if (!$_POST['accept_service']) {
        //  $this->error(L('PUBLIC_ACCEPT_SERVICE_TERMS'));
        // }

        $login_salt = rand(11111, 99999);
        $map['uname'] = $uname;
        $map['sex'] = $sex;
        $map['login_salt'] = $login_salt;
        $map['password'] = md5(md5($password).$login_salt);
        $map['login'] = $map['email'] = $email;
        $map['reg_ip'] = get_client_ip();
        $map['ctime'] = time();

        // 添加地區資訊
        $map['location'] = t($_POST['city_names']);
        $cityIds = t($_POST['city_ids']);
        $cityIds = explode(',', $cityIds);
        isset($cityIds[0]) && $map['province'] = intval($cityIds[0]);
        isset($cityIds[1]) && $map['city'] = intval($cityIds[1]);
        isset($cityIds[2]) && $map['area'] = intval($cityIds[2]);
        // 審核狀態： 0-需要稽覈；1-通過稽覈
        $map['is_audit'] = $this->_config['register_audit'] ? 0 : 1;
        $map['is_active'] = $this->_config['need_active'] ? 0 : 1;
        $map['first_letter'] = getFirstLetter($uname);
        //如果包含中文將中文翻譯成拼音
        if ( preg_match('/[\x7f-\xff]+/', $map['uname'] ) ){
            //昵稱和呢稱拼音儲存到搜索欄位
            $map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin( $map['uname'] );
        } else {
            $map['search_key'] = $map['uname'];
        }
        $uid = $this->_user_model->add($map);
        if($uid) {
            // 添加積分
            model('Credit')->setUserCredit($uid,'init_default');
            // 如果是邀請註冊，則邀請碼失效
            if($invite) {
                $receiverInfo = model('User')->getUserInfo($uid);
                // 驗證碼使用
                model('Invite')->setInviteCodeUsed($inviteCode, $receiverInfo);
                // 添加使用者邀請碼欄位
                model('User')->where('uid='.$uid)->setField('invite_code', $inviteCode);
                //給邀請人獎勵
            }

            // 添加至默認的使用者組
            $userGroup = model('Xdata')->get('admin_Config:register');
            $userGroup = empty($userGroup['default_user_group']) ? C('DEFAULT_GROUP_ID') : $userGroup['default_user_group'];
            model('UserGroupLink')->domoveUsergroup($uid, implode(',', $userGroup));

            //註冊來源-第三方帳號繫結
            if(isset($_POST['other_type'])){
                $other['type'] = t($_POST['other_type']);
                $other['type_uid'] = t($_POST['other_uid']);
                $other['oauth_token'] = t($_POST['oauth_token']);
                $other['oauth_token_secret'] = t($_POST['oauth_token_secret']);
                $other['uid'] = $uid;
                D('login')->add($other);
            }

            //判斷是否需要稽覈
            if($this->_config['register_audit']) {
                $this->redirect('public/Register/waitForAudit', array('uid' => $uid));
            } else {
                if($this->_config['need_active']){
                    $this->_register_model->sendActivationEmail($uid);
                    $this->redirect('public/Register/waitForActivation', array('uid' => $uid));
                }else{
                    D('Passport')->loginLocal($email,$password);
                    $this->assign('jumpUrl', U('public/Passport/login'));
                    $this->success('恭喜您，註冊成功');
                }
            }

        } else {
            $this->error(L('PUBLIC_REGISTER_FAIL'));            // 註冊失敗
        }
    }

    /**
     * 等待稽覈頁面
     * @return void
     */
    public function waitForAudit() {
        $user_info = $this->_user_model->where("uid={$this->uid}")->find();
        $email  =   model('Xdata')->getConfig('sys_email','site');
        if (!$user_info || $user_info['is_audit']) {
            $this->redirect('public/Passport/login');
        }
        $touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
        foreach($touid as $k=>$v){
            model('Notify')->sendNotify($v['uid'], 'register_audit');
        }
        $this->assign('email',$email);
        $this->setTitle('帳號等待稽覈');
        $this->setKeywords('帳號等待稽覈');
        $this->display();
    }

    /**
     * 等待啟用頁面
     */
    public function waitForActivation() {
        $this->appCssList[] = 'login.css';
        $user_info = $this->_user_model->where("uid={$this->uid}")->find();
        // 判斷使用者資訊是否存在
        if($user_info) {
            if($user_info['is_audit'] == '0') {
                // 稽覈
                exit(U('public/Register/waitForAudit', array('uid'=>$this->uid), true));
            } else if($user_info['is_active'] == '1') {
                // 啟用
                exit(U('public/Register/step2',array(),true));
            }
        } else {
            // 註冊
            $this->redirect('public/Passport/login');
        }

        $email_site = 'http://mail.'.preg_replace('/[^@]+@/', '', $user_info['email']);

        $this->assign('email_site', $email_site);
        $this->assign('email', $user_info['email']);
        $this->assign('config', $this->_config);
        $this->setTitle('等待啟用帳號');
        $this->setKeywords('等待啟用帳號');
        $this->display();
    }

    /**
     * 發送啟用郵件
     * @return void
     */
    public function resendActivationEmail() {
        $res = $this->_register_model->sendActivationEmail($this->uid);
        $this->ajaxReturn(null, $this->_register_model->getLastError(), $res);
    }

    /**
     * 修改啟用郵箱
     */
    public function changeActivationEmail() {
        $email = t($_POST['email']);
        // 驗證郵箱是否為空
        if (!$email) {
            $this->ajaxReturn(null, '郵箱不能為空！', 0);
        }
        // 驗證郵箱格式
        $checkEmail = $this->_register_model->isValidEmail($email);
        if (!$checkEmail) {
            $this->ajaxReturn(null, $this->_register_model->getLastError(), 0);
        }
        $res = $this->_register_model->changeRegisterEmail($this->uid, $email);
        $res && $this->_register_model->sendActivationEmail($this->uid);
        $this->ajaxReturn(null, $this->_register_model->getLastError(), $res);
    }

    /**
     * 通過連結啟用帳號
     * @return void
     */
    public function activate() {
        $user_info = $this->_user_model->getUserInfo($this->uid);

        $this->assign('user',$user_info);

        if (!$user_info || $user_info['is_active']) {
            $this->redirect('public/Passport/login');
        }

        $active = $this->_register_model->activate($this->uid, t($_GET['code']));

        if ($active) {
            // 登陸
            model('Passport')->loginLocalWhitoutPassword($user_info['email']);
            $this->setTitle('成功啟用帳號');
            $this->setKeywords('成功啟用帳號');
            // 跳轉下一步
            $this->assign('jumpUrl', U('public/Register/step2'));
            $this->success($this->_register_model->getLastError());
        } else {
            $this->redirect('public/Passport/login');
            $this->error($this->_register_model->getLastError());
        }
    }

    /**
     * 第二步註冊
     * @return void
     */
    public function step2() {
        // 未登入
        empty($_SESSION['mid']) && $this->redirect('public/Passport/login');
        $user = $this->_user_model->getUserInfo($this->mid);
        $this->assign('user_info', $user);
        //如果已經同步過頭像,不需要強制執行這一步
        if(model('Avatar')->hasAvatar()){
            $this->assign('need_photo',0);
        }else{
            $this->assign('need_photo',$this->_config['need_photo']);
        }
        $this->assign('tag_open',$this->_config['tag_open']);
        $this->assign('interester_open',$this->_config['interester_open']);
        $this->setTitle('上傳站內頭像');
        $this->setKeywords('上傳站內頭像');
        $this->display();
    }

    /**
     * 註冊流程 - 第三步驟
     * 設定個人標籤
     */
    public function step3() {
        // 未登入
        empty($_SESSION['mid']) && $this->redirect('public/Passport/login');
        $this->appCssList[] = 'login.css';
        //$this->_config['tag_num'] = $this->_config['tag_num']?$this->_config['tag_num']:10;
        $this->assign('tag_num',$this->_config['tag_num']);
        $this->assign('interester_open',$this->_config['interester_open']);
        $this->setTitle('設定個人標籤');
        $this->setKeywords('設定個人標籤');
        $this->display();
    }

    /**
     * 註冊流程 - 執行第三步驟
     * 添加標籤
     */
    public function doStep3() {
        $tagIds = t($_REQUEST['user_tags']);
        !empty($tagIds) && $tagIds = explode(',', $tagIds);
        $rowId = intval($_REQUEST['user_row_id']);
        if(!empty($rowId)) {
            if(count($tagIds) > 10) {
                return $this->ajaxReturn(null, '最多只能設定10個標籤', false);
            }
            model('Tag')->setAppName('public')->setAppTable('user')->updateTagData($rowId, $tagIds);
        }
        echo 1;
    }

    /**
     * 註冊流程 - 第四步驟
     */
    public function step4() {
        // 未登入
        empty($_SESSION['mid']) && $this->redirect('public/Passport/login');
        $this->appCssList[] = 'login.css';

        //dump($this->_config);exit;
        //按推薦使用者
        $related_recommend_user = model('RelatedUser')->getRelatedUserByType(5,8);
        $this->assign('related_recommend_user',$related_recommend_user);
        //按標籤
        if(in_array('tag', $this->_config['interester_rule'])){
            $related_tag_user = model('RelatedUser')->getRelatedUserByType(4,8);
            $this->assign('related_tag_user',$related_tag_user);
        }
        //按地區
        if(in_array('area', $this->_config['interester_rule'])){
            $related_city_user = model('RelatedUser')->getRelatedUserByType(3,8);
            $this->assign('related_city_user',$related_city_user);
        }
        $userInfo = model('User')->getUserInfo($this->mid);
        $location = explode(' ', $userInfo['location']);
        $this->assign('location',$location[0]);
        $this->setTitle('關注感興趣的人');
        $this->setKeywords('關注感興趣的人');
        $this->display();
    }

    /**
     * 獲取推薦使用者
     * @return void
     */
    public function getRelatedUser() {
        $type = intval($_POST['type']);
        $related_user = model('RelatedUser')->getRelatedUserByType($type,8);
        $html = '';
        foreach($related_user as $k=>$v){
            $html .= '<li><div style="position:relative;width:80px;height:80px"><div class="selected"><i class="ico-ok-mark"></i></div>
                <a event-node="bulkDoFollowData" value="'.$v['userInfo']['uid'].'" class="face_part" href="javascript:void(0);">
                <img src="'.$v['userInfo']['avatar_big'].'" /></a></div><span class="name">'.$v['userInfo']['uname'].'</span></li>';
        }
        echo $html;
    }

    /**
     * 註冊流程 - 執行第四步驟
     */
    public function doStep4() {
        set_time_limit(0);
        // 初始化完成
        $this->_register_model->overUserInit($this->mid);
        // 添加雙向關注使用者
        $eachFollow = $this->_config['each_follow'];
        if(!empty($eachFollow)) {
            model('Follow')->eachDoFollow($this->mid, $eachFollow);
        }
        // 添加默認關注使用者
        $defaultFollow = $this->_config['default_follow'];
        $defaultFollow = array_diff(explode(',', $defaultFollow), explode(',', $eachFollow));
        if(!empty($defaultFollow)) {
            model('Follow')->bulkDoFollow($this->mid, $defaultFollow);
        }
        redirect($GLOBALS['ts']['site']['home_url']);
        //$this->redirect($GLOBALS['ts']['site']['home_url_str']);
    }

    /**
     * 驗證郵箱是否已被使用
     */
    public function isEmailAvailable() {
        $email = t($_POST['email']);
        $result = $this->_register_model->isValidEmail($email);
        $this->ajaxReturn(null, $this->_register_model->getLastError(), $result);
}

/**
 * 驗證邀請郵件
 */
public function isEmailAvailable_invite() {
    $email = t($_POST['email']);
    if(empty($email)) {
        exit($this->ajaxReturn(null, '', 1));
}
$result = $this->_register_model->isValidEmail_invite($email);
$this->ajaxReturn(null, $this->_register_model->getLastError(), $result);
}

/**
 * 驗證昵稱是否已被使用
 */
public function isUnameAvailable() {
    $uname = t($_POST['uname']);
    $oldName = t($_POST['old_name']);
    $result = $this->_register_model->isValidName($uname, $oldName);
    $this->ajaxReturn(null, $this->_register_model->getLastError(), $result);
}

/**
 * 添加使用者關注資訊
 */
public function bulkDoFollow() {
    $res = model('Follow')->bulkDoFollow($this->mid, t($_POST['fids']));
    $this->ajaxReturn($res, model('Follow')->getError(), false !== $res);
}

/**
 *  設定使用者為已初始化
 */
public function doAuditUser(){
    $this->_register_model->overUserInit($this->mid);
    echo 1;
}
}
