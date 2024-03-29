<?php
/**
 * PassportAction 通行證模組
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class PassportAction extends Action
{
    var $passport;

    /**
     * 模組初始化
     * @return void
     */
    protected function _initialize() {
        $this->passport = model('Passport');
    }

    /**
     * 通行證首頁
     * @return void
     */
    public function index() {
        // 如果設定了登入前的默認應用
        // U('welcome','',true);
        // 如果沒設定
        $this->login();
    }

    /**
     * 默認登入頁
     * @return void
     */
    public function login(){
        // 添加樣式
        $this->appCssList[] = 'login.css';
        if(model('Passport')->isLogged()){
            U('public/Index/index','',true);
        }

        // 獲取郵箱字尾
        $registerConf = model('Xdata')->get('admin_Config:register');
        $this->assign('emailSuffix', explode(',', $registerConf['email_suffix']));
        $this->assign( 'register_type' , $registerConf['register_type']);
        $data= model('Xdata')->get("admin_Config:seo_login");
        !empty($data['title']) && $this->setTitle($data['title']);
        !empty($data['keywords']) && $this->setKeywords($data['keywords']);
        !empty($data['des'] ) && $this->setDescription ( $data ['des'] );

        $login_bg = getImageUrlByAttachId( $this->site ['login_bg'] );
        if(empty($login_bg))
            $login_bg = APP_PUBLIC_URL . '/image/body-bg2.jpg';

        $this->assign('login_bg', $login_bg);

        $this->display('login');
    }

    /**
     * 快速登入
     */
    public function quickLogin(){
        $registerConf = model('Xdata')->get('admin_Config:register');
        $this->assign( 'register_type' , $registerConf['register_type']);
        $this->display();
    }

    /**
     * 使用者登入
     * @return void
     */
    public function doLogin() {
        $login      = t($_POST['login_email']);
        $password   = trim($_POST['login_password']);
        $remember   = intval($_POST['login_remember']);

        $result     = $this->passport->loginLocal($login,$password,$remember);
        if(!$result){
            $status = 0;
            $info   = $this->passport->getError();
            $data   = 0;
        }else{
            $status = 1;
            $info   = $this->passport->getSuccess();
            $data   = ($GLOBALS['ts']['site']['home_url'])?$GLOBALS['ts']['site']['home_url']:0;
        }
        $this->ajaxReturn($data,$info,$status);
    }

    /**
     * 登出登入
     * @return void
     */
    public function logout() {
        $this->passport->logoutLocal();
        $this->assign('jumpUrl',U('public/Passport/login'));
        $this->success('退出成功！');
    }

    /**
     * 找回密碼頁面
     * @return void
     */
    public function findPassword() {

        // 添加樣式
        $this->appCssList[] = 'login.css';

        $this->display();
    }

    /**
     * 通過安全問題找回密碼
     * @return void
     */
    public function doFindPasswordByQuestions() {
        $this->display();
    }

    /**
     * 通過Email找回密碼
     */
    public function doFindPasswordByEmail() {
        $_POST["email"] = t($_POST["email"]);
        if(!$this->_isEmailString($_POST['email'])) {
            $this->error(L('PUBLIC_EMAIL_TYPE_WRONG'));
        }

        $user = model("User")->where('`email`="'.$_POST["email"].'"')->find();
        if(!$user) {
            $this->error('找不到該郵箱註冊資訊');
        }

        $result = $this->_sendPasswordEmail($user);
        if($result){
            $this->success('發送成功，請注意查收郵件');
        }else{
            $this->error('操作失敗，請重試');
        }
    }

    /**
     * 找回密碼頁面
     */
    private function _sendPasswordEmail($user) {
        if($user['uid']) {
            $this->appCssList[] = 'login.css';      // 添加樣式
            $code = md5($user["uid"].'+'.$user["password"].'+'.rand(1111,9999));
            $config['reseturl'] = U('public/Passport/resetPassword', array('code'=>$code));
            //設定舊的code過期
            D('FindPassword')->where('uid='.$user["uid"])->setField('is_used',1);
            //添加新的修改密碼code
            $add['uid'] = $user['uid'];
            $add['email'] = $user['email'];
            $add['code'] = $code;
            $add['is_used'] = 0;
            $result = D('FindPassword')->add($add);
            if($result){
                model('Notify')->sendNotify($user['uid'], 'password_reset', $config);
                return true;
            }else{
                return false;
            }
        }
    }

    public function doFindPasswordByEmailAgain(){
        $_POST["email"] = t($_POST["email"]);
        $user = model("User")->where('`email`="'.$_POST["email"].'"')->find();
        if(!$user) {
            $this->error('找不到該郵箱註冊資訊');
        }

        $result = $this->_sendPasswordEmail($user);
        if($result){
            $this->success('發送成功，請注意查收郵件');
        }else{
            $this->error('操作失敗，請重試');
        }
    }

    /**
     * 通過手機簡訊找回密碼
     * @return void
     */
    public function doFindPasswordBySMS() {
        $this->display();
    }

    /**
     * 重置密碼頁面
     * @return void
     */
    public function resetPassword() {
        $code = t($_GET['code']);
        $this->_checkResetPasswordCode($code);
        $this->assign('code', $code);
        $this->display();
    }

    /**
     * 執行重置密碼操作
     * @return void
     */
    public function doResetPassword() {
        $code = t($_POST['code']);
        $user_info = $this->_checkResetPasswordCode($code);

        $password = trim($_POST['password']);
        $repassword = trim($_POST['repassword']);
        if(!model('Register')->isValidPassword($password, $repassword)){
            $this->error(model('Register')->getLastError());
}

$map['uid'] = $user_info['uid'];
$data['login_salt'] = rand(10000,99999);
$data['password']   = md5(md5($password) . $data['login_salt']);
$res = model('User')->where($map)->save($data);
if ($res) {
    D('find_password')->where('uid='.$user_info['uid'])->setField('is_used',1);
    model('User')->cleanCache($user_info['uid']);
    $this->assign('jumpUrl', U('public/Passport/login'));
    $config['newpass'] = $password;
    model('Notify')->sendNotify($user_info['uid'],'password_setok',$config);
    //$mail = model('Mail')->sendSysEmail($user_info['email'],'resetPassOk',array(),array('newpass'=>$password));
    $this->success(L('PUBLIC_PASSWORD_RESET_SUCCESS'));
} else {
    $this->error(L('PUBLIC_PASSWORD_RESET_FAIL'));
}
}

/**
 * 檢查重置密碼的驗證碼操作
 * @return void
 */
private function _checkResetPasswordCode($code) {
    $map['code'] = $code;
    $map['is_used'] = 0;
    $uid = D('find_password')->where($map)->getField('uid');
    if(!$uid){
        $this->assign('jumpUrl',U('public/Passport/findPassword'));
        $this->error('重置密碼連結已失效，請重新找回');
}
$user_info = model('User')->where("`uid`={$uid}")->find();

if (!$user_info) {
    $this->redirect = U('public/Passport/login');
}

return $user_info;
}

/*
 * 驗證安全郵箱
 * @return void
 */
public function doCheckEmail() {
    $email = t($_POST['email']);
    if($this->_isEmailString($email)){
        die(1);
}else{
    die(0);
}
}

/*
 * 正則匹配，驗證郵箱格式
 * @return integer 1=成功 ""=失敗
 */
private function _isEmailString($email) {
    return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
}
}
?>
