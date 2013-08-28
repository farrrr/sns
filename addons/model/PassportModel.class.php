<?php
/**
 * 通行證模型 - 業務邏輯模型
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class PassportModel {

    protected $error = null;        // 錯誤資訊
    protected $success = null;      // 成功資訊
    protected $rel = array();       // 判斷是否是第一次登入

    /**
     * 返回最後的錯誤資訊
     * @return string 最後的錯誤資訊
     */
    public function getError() {
        return $this->error;
    }

    /**
     * 返回最後的錯誤資訊
     * @return string 最後的錯誤資訊
     */
    public function getSuccess() {
        return $this->success;
    }

    /**
     * 驗證後臺登入
     * @return boolean 是否已經登入後臺
     */
    public function checkAdminLogin() {
        if($_SESSION['adminLogin']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 登入後臺
     * @return boolean 登入後臺是否成功
     */
    public function adminLogin() {
        if(is_numeric($_POST['uid'])){
            $map['uid'] = intval($_POST['uid']);
        }else{
            $map['email'] = t($_POST['email']);
        }
        $login = M('User')->where($map)->find();
        if($this->loginLocal($login['login'], $_POST['password'])) {
            $GLOBALS['ts']['mid'] = $_SESSION['adminLogin'] = intval($_SESSION['mid']);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 退出後臺
     * @return void
     */
    public function adminLogout() {
        unset($_SESSION['adminLogin']);
        session_destroy($_SESSION['adminLogin']);
    }

    /**
     * 驗證使用者是否需要登入
     * @return boolean 登陸成功是返回true, 否則返回false
     */
    public function needLogin() {
        // 驗證本地系統登入
        if($this->isLogged()) {
            return false;
        } else {
            // 匿名訪問控制
            $acl = C('access');
            return !($acl[APP_NAME.'/'.MODULE_NAME.'/'.ACTION_NAME] === true
                || $acl[APP_NAME.'/'.MODULE_NAME.'/*'] === true
                || $acl[APP_NAME.'/*/*'] === true);

            //ACL判斷
            if(MODULE_CODE != 'public/Passport' && MODULE_CODE != 'public/Register'){
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * 驗證使用者是否已登入
     * 按照session -> cookie的順序檢查是否登陸
     * @return boolean 登陸成功是返回true, 否則返回false
                 */
                public function isLogged() {
                    // 驗證本地系統登入
                    if(intval($_SESSION['mid']) > 0 && $_SESSION['SITE_KEY']==getSiteKey()) {
                        return true;
                    } else if($uid = $this->getCookieUid()) {
                        return $this->_recordLogin($uid);
                    } else {
                        unset($_SESSION['mid']);
                        unset($_SESSION['SITE_KEY']);
                        return false;
                    }
                }

            /**
             * 根據標示符（email或uid）和未加密的密碼獲取本地使用者（密碼為null時不參與驗證）
             * @param string $login 標示符內容（為數字時：標示符類型為uid，其他：標示符類型為email）
             * @param string|boolean $password 未加密的密碼
             * @return array|boolean 成功獲取使用者資料時返回使用者資訊陣列，否則返回false
                 */
                public function getLocalUser($login, $password) {
                    $login = t($login);
                    if(empty($login) || empty($password)) {
                        $this->error = L('PUBLIC_ACCOUNT_EMPTY');           // 帳號或密碼不能為空
                        return false;
                    }

                    if($this->isValidEmail($login)){
                        $map = "(login = '{$login}' or email='{$login}') AND is_del=0";
                    }else{
                        $map = "(login = '{$login}' or uname='{$login}') AND is_del=0";
                    }

                    if(!$user = model('User')->where($map)->find()) {
                        $this->error = L('PUBLIC_ACCOUNT_NOEXIST');         // 帳號不存在
                        return false;
                    }

                    $uid  = $user['uid'];
                    // 記錄登陸日誌，首次登陸判斷
                    $this->rel = D('LoginRecord')->where("uid = ".$uid)->field('locktime')->find();

                    $login_error_time = cookie('login_error_time');

                    if($this->rel['locktime'] > time()) {
                        $this->error = L('PUBLIC_ACCOUNT_LOCKED');          // 您的帳號已經被鎖定，請稍後再登入
                        return false;
                    }

                    if($password && md5(md5($password).$user['login_salt']) != $user['password']) {
                        $login_error_time = intval($login_error_time) + 1;
                        cookie('login_error_time', $login_error_time);

                        $this->error = '密碼輸入錯誤，您還可以輸入'.(6 - $login_error_time).'次';           // 密碼錯誤

                        if($login_error_time >=6) {
                            // 記錄鎖定賬號時間
                            $save['locktime'] = time() + 60 * 60;
                            $save['ip'] = get_client_ip();
                            $save['ctime'] = time();
                            $m['uid'] = $save['uid'] = $uid;

                            $this->error = L('PUBLIC_ACCOUNT_LOCK');        // 您輸入的密碼錯誤次數過多，帳號將被鎖定1小時
                            // 發送鎖定通知
                            model('Notify')->sendNotify($uid, 'user_lock');

                            cookie('login_error_time', null);

                            if(empty($this->rel)) {
                                D('')->table(C('DB_PREFIX').'login_record')->add($save);
                            } else {
                                D('')->table(C('DB_PREFIX').'login_record')->where($m)->save($save);
                            }
                        }
                        return false;
                    } else {
                        $logData['uid'] = $uid;
                        $logData['ip'] = get_client_ip();
                        $logData['ctime'] = time();
                        D('')->table(C('DB_PREFIX').'login_logs')->add($logData);
                        return $user;
                    }
                }

            /**
             * 使用本地帳號登陸（密碼為null時不參與驗證）
             * @param string $login 登入名稱，郵箱或使用者名
             * @param string $password 密碼
             * @param boolean $is_remember_me 是否記錄登入狀態，默認為false
             * @return boolean 是否登入成功
                 */
                public function loginLocal($login, $password = null, $is_remember_me = false) {
                    $res = false;
                    if(UC_SYNC){
                        $res = $this->ucLogin($login, $password, $is_remember_me);
                        if($res){
                            return true;
                        }
                    }

                    $user = $this->getLocalUser($login, $password);
                    return $user['uid']>0 ? $this->_recordLogin($user['uid'], $is_remember_me) : false;
                }

            /**
             * 使用本地帳號登陸，無密碼
             * @param string $login 登入名稱，郵箱或使用者名
             * @param boolean $is_remember_me 是否記錄登入狀態，默認為false
             * @return boolean 是否登入成功
                 */
                public function loginLocalWhitoutPassword($login, $is_remember_me = false) {
                    $login = t($login);
                    if(empty($login)) {
                        $this->error = L('PUBLIC_ACCOUNT_NOTEMPTY');            // 帳號不能為空
                        return false;
                    }

                    $user = D('User')->where(array('login'=>$login))->find();

                    if(!$user) {
                        $this->error = L('PUBLIC_ACCOUNT_NOEXIST');             // 帳號不存在
                        return false;
                    }

                    return $user['uid']>0 ? $this->_recordLogin($user['uid'], $is_remeber_me) : false;
                }

            /**
             * 設定登入狀態、記錄登入日誌
             * @param integer $uid 使用者ID
             * @param boolean $is_remember_me 是否記錄登入狀態，默認為false
             * @return boolean 操作是否成功
                 */
                private function _recordLogin($uid, $is_remember_me = false) {

                    // 註冊cookie
                    if(!$this->getCookieUid() && $is_remember_me ) {
                        $expire = 3600 * 24 * 30;
                        cookie('TSV3_LOGGED_USER', $this->jiami(C('SECURE_CODE').".{$uid}"), $expire);
                    }

                    // 記住活躍時間
                    cookie('TSV3_ACTIVE_TIME',time() + 60 * 30);
                    cookie('login_error_time', null);

                    // 更新登陸時間
                    model('User')->setField('last_login_time', $_SERVER['REQUEST_TIME'], 'uid='.$uid );

                    // 記錄登陸日誌，首次登陸判斷
                    empty($this->rel) && $this->rel = D('')->table(C('DB_PREFIX').'login_record')->where("uid = ".$uid)->getField('login_record_id');

                    //添加積分
                    model('Credit')->setUserCredit($uid,'user_login');

                    // 註冊session
                    $_SESSION['mid'] = intval($uid);
                    $_SESSION['SITE_KEY']=getSiteKey();

                    $inviterInfo = model('User')->getUserInfo($uid);

                    $map['ip'] = get_client_ip();
                    $map['ctime'] = time();
                    $map['locktime'] = 0;

                    $this->success = '登入成功，努力載入中。。';

                    if($this->rel) {
                        D('')->table(C('DB_PREFIX').'login_record')->where("uid = ".$uid)->save($map);
                    } else {
                        $map['uid'] = $uid;
                        D('')->table(C('DB_PREFIX').'login_record')->add($map);
                    }

                    return true;
                }

            /**
             * 登出本地登入
             * @return void
                 */
                public function logoutLocal() {
                    unset($_SESSION['mid'],$_SESSION['SITE_KEY']); // 登出session
                    cookie('TSV3_LOGGED_USER', NULL);   // 登出cookie
                    //UC同步退出
                    if(UC_SYNC){
                        echo $this->ucLogout();
                    }
                }

            /**
             * 獲取cookie中記錄的使用者ID
             * @return integer cookie中記錄的使用者ID
                 */
                public function getCookieUid() {
                    static $cookie_uid = null;
                    if(isset($cookie_uid) && $cookie_uid !== null) {
                        return $cookie_uid;
                    }

                    $cookie = cookie('TSV3_LOGGED_USER');

                    $cookie = explode(".", $this->jiemi($cookie));

                    $cookie_uid = ($cookie[0] != C('SECURE_CODE')) ? false : $cookie[1];

                    return $cookie_uid;
                }

            /**
             * 判斷email地址是否合法
             * @param string $email 郵件地址
             * @return boolean 郵件地址是否合法
                 */
                public function isValidEmail($email) {
                    return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
                }

            /**
             * 加密函數
             * @param string $txt 需加密的字元串
             * @param string $key 加密金鑰，默認讀取SECURE_CODE配置
             * @return string 加密後的字元串
                 */
                private function jiami($txt, $key = null) {
                    empty($key) && $key = C('SECURE_CODE');
                    //有mcrypt擴展時
                    if(function_exists('mcrypt_module_open')){
                        return desencrypt($txt, $key);
                    }
                    //無mcrypt擴展時
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
                    $nh = rand(0, 64);
                    $ch = $chars[$nh];
                    $mdKey = md5($key.$ch);
                    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
                    $txt = base64_encode($txt);
                    $tmp = '';
                    $i = 0;
                    $j = 0;
                    $k = 0;
                    for($i = 0; $i < strlen($txt); $i++) {
                        $k = $k == strlen($mdKey) ? 0 : $k;
                        $j = ($nh + strpos($chars, $txt [$i]) + ord($mdKey[$k++])) % 64;
                        $tmp .= $chars[$j];
                    }
                    return $ch.$tmp;
                }

            /**
             * 解密函數
             * @param string $txt 待解密的字元串
             * @param string $key 解密金鑰，默認讀取SECURE_CODE配置
             * @return string 解密後的字元串
                 */
                private function jiemi($txt, $key = null) {
                    empty($key) && $key = C('SECURE_CODE');
                    //有mcrypt擴展時
                    if(function_exists('mcrypt_module_open')){
                        return desdecrypt($txt, $key);
                    }
                    //無mcrypt擴展時
                    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-=_";
                    $ch = $txt[0];
                    $nh = strpos($chars, $ch);
                    $mdKey = md5($key.$ch);
                    $mdKey = substr($mdKey, $nh % 8, $nh % 8 + 7);
                    $txt = substr($txt, 1);
                    $tmp = '';
                    $i = 0;
                    $j = 0;
                    $k = 0;
                    for($i = 0; $i < strlen($txt); $i++) {
                        $k = $k == strlen($mdKey) ? 0 : $k;
                        $j = strpos($chars, $txt[$i]) - $nh - ord($mdKey[$k++]);
                        while($j < 0) {
                            $j += 64;
                        }
                        $tmp .= $chars[$j];
                    }
                    return base64_decode($tmp);
                }

            /**
             * UC登入或者註冊
             * @param string $username
             * @param string $password
             * @param string $is_remember_me 是否記住登入
             * @return bool
                 */
                private function ucLogin($username, $password, $is_remember_me) {

                    //載入UC客戶端SDK
                    include_once SITE_PATH.'/api/uc_client/client.php';

                    //1. 獲取UC資訊.
                    if($this->isValidEmail($username)){
                        $use_email = true;
                        $uc_login_type = 2;
                    }else{
                        $use_email = false;
                        $uc_login_type = 0;
                    }

                    $uc_user = uc_user_login($username,$password,$uc_login_type);

                    //2. 已經同步過的直接登入
                    $uc_user_ref = ts_get_ucenter_user_ref('',$uc_user['0'],'');

                    if($uc_user_ref['uid'] && $uc_user_ref['uc_uid'] && $uc_user[0] > 0 ){
                        //登入本地帳號
                        $result = $uc_user_ref['uid']>0 ? $this->_recordLogin($uc_user_ref['uid'], $is_remeber_me) : false;
                        if($result){
                            $this->success .= uc_user_synlogin($uc_user[0]);
                            return true;
                        }else{
                            $this->error = '登入失敗，請重試';
                            return false;
                        }
                    }

                    //3. 關聯表無、獲取本地帳號資訊.
                    $ts_user = $this->getLocalUser($username,$password);


                    // 偵錯用-寫log
                    $log_message = "============================ \n "
                        .date('Y-m-d H:i:s')." \n ".$_SERVER['REQUEST_URI']." \n "
                        .var_export($uc_user,true)." \n "
                        .var_export($ts_user,true)." \n "
                        .var_export($uc_user_ref,true)." \n ";

                    $log_file = SITE_PATH."/ts_uc_log.txt";
                    $result = error_log($log_message,3,$log_file);

                    //4. 關聯表無、UC有、本地有的
                    if( $uc_user[0] > 0 && $ts_user['uid'] > 0 ){
                        $result = ts_add_ucenter_user_ref($ts_user['uid'],$uc_user[0],$uc_user[1],$uc_user[3]);
                        if(!$result){
                            $this->error = '使用者不存在或密碼錯誤';
                            return false;
                        }
                        //登入本地帳號
                        $result = $this->_recordLogin($ts_user['uid'], $is_remeber_me);
                        if($result){
                            $this->success .= uc_user_synlogin($uc_user[0]);
                            return true;
                        }else{
                            $this->error = '登入失敗，請重試';
                            return false;
                        }
                    }

                    //5. 關聯表無、UC有、本地無的
                    if( $uc_user[0] > 0 && !$ts_user['uid'] ){
                        //寫入本地系統
                        $login_salt = rand(11111, 99999);
                        $map['uname'] = $uc_user[1];
                        $map['sex'] = 1;
                        $map['login_salt'] = $login_salt;
                        $map['password'] = md5(md5($uc_user[2]).$login_salt);
                        $map['login'] = $map['email'] = $uc_user[3];
                        $map['reg_ip'] = get_client_ip();
                        $map['ctime'] = time();
                        $map['is_audit'] = 1;
                        $map['is_active'] = 1;
                        $map['first_letter'] = getFirstLetter($uname);
                        //如果包含中文將中文翻譯成拼音
                        if ( preg_match('/[\x7f-\xff]+/', $map['uname'] ) ){
                            //昵稱和呢稱拼音儲存到搜索欄位
                            $map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin( $map['uname'] );
                        } else {
                            $map['search_key'] = $map['uname'];
                        }
                        $ts_uid = model('User')->add($map);
                        if(!$ts_uid){
                            $this->error = '本地使用者註冊失敗，請聯繫管理員';
                            return false;
                        }

                        //寫入關聯表
                        $result = ts_add_ucenter_user_ref($ts_uid,$uc_user[0],$uc_user[1],$uc_user[3]);
                        if(!$result){
                            $this->error = '使用者不存在或密碼錯誤';
                            return false;
                        }

                        // 添加至默認的使用者組
                        $registerConfig = model('Xdata')->get('admin_Config:register');
                        $userGroup = empty($registerConfig['default_user_group']) ? C('DEFAULT_GROUP_ID') : $registerConfig['default_user_group'];
                        model('UserGroupLink')->domoveUsergroup($ts_uid, implode(',', $userGroup));

                        // 添加雙向關注使用者
                        $eachFollow = $registerConfig['each_follow'];
                        if(!empty($eachFollow)) {
                            model('Follow')->eachDoFollow($ts_uid, $eachFollow);
                        }

                        // 添加默認關注使用者
                        $defaultFollow = $registerConfig['default_follow'];
                        $defaultFollow = array_diff(explode(',', $defaultFollow), explode(',', $eachFollow));
                        if(!empty($defaultFollow)) {
                            model('Follow')->bulkDoFollow($ts_uid, $defaultFollow);
                        }

                        //登入本地帳號
                        $result = $this->_recordLogin($ts_uid, $is_remeber_me);
                        if($result){
                            $this->success .= uc_user_synlogin($uc_user[0]);
                            return true;
                    }else{
                        $this->error = '登入失敗，請重試';
                        return false;
                    }
                    }

                    //6. 關聯表無、UC無、本地有
                    if( $uc_user[0] < 0 && $ts_user['uid'] > 0 ){
                        //寫入UC
                        $uc_uid = uc_user_register($ts_user['uname'], $password, $ts_user['email'],'','', get_client_ip());
                        if($uc_uid > 0 ){
                            $this->error = 'UC帳號註冊失敗，請聯繫管理員';
                            return false;
                    }
                    //寫入關聯表
                    $result = ts_add_ucenter_user_ref($ts_user['uid'],$uc_uid,$ts_user['uname'],$ts_user['email']);
                    if(!$result){
                        $this->error = '使用者不存在或密碼錯誤';
                        return false;
                    }
                    //登入本地帳號
                    $result = $this->_recordLogin($ts_user['uid'], $is_remeber_me);
                    if($result){
                        $this->success .= uc_user_synlogin($uc_uid);
                        return true;
                    }else{
                        $this->error = '登入失敗，請重試';
                        return false;
                    }
                    }

                    //7. 關聯表無、UC無、本地無的
                    $this->error = '使用者不存在';
                    return false;
                    }

                    /**
                     * UC登出登入
                     * @param int $uid
                     * @return string 退出登入的返回資訊
                 */
                private function ucLogout($uid){
                    include_once SITE_PATH.'/api/uc_client/client.php';
                    return uc_user_synlogout();
                    }
                    }
