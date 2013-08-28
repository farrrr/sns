<?php
/**
 * 註冊模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class RegisterModel extends Model {

    private $_config;                                                                   // 註冊配置欄位
    private $_user_model;                                                               // 使用者模型物件欄位
    private $_error;                                                                    // 錯誤資訊欄位
    private $_email_reg = '/[_a-zA-Z\d\-\.]+(@[_a-zA-Z\d\-\.]+\.[_a-zA-Z\d\-]+)+$/i';       // 郵箱正則規則
    private $_name_reg = "/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u";                         // 昵稱正則規則

    /**
     * 初始化操作，獲取註冊配置資訊；例項化使用者模型物件
     * @return void
     */
    public function __construct() {
        parent::__construct();
        $this->_config = model('Xdata')->get('admin_Config:register');
        $this->_user_model = model('User');
    }

    /**
     * 驗證邀請郵件內容的正確性
     * @param string $email 邀請郵箱的資訊
     * @param string $old_email 原始郵箱的資訊
     * @return boolean 是否驗證成功
     */
    public function isValidEmail_invite($email, $old_email = null) {
        $res = preg_match($this->_email_reg, $email, $matches) !== 0;
        if(!$res) {
            $this->_error = L('PUBLIC_EMAIL_TIPS');         // 無效的Email地址
        } else if(!empty($this->_config['email_suffix'])) {
            $res = in_array($matches['1'], explode(',', $this->_config['email_suffix']));
            // !$res && $this->_error =L('PUBLIC_EMAIL_SUFFIX_FORBIDDEN');          // 郵箱字尾不允許註冊
            !$res && $this->_error = '該郵箱字尾不允許註冊';            // 郵箱字尾不允許註冊
        }
        if($res && ($email != $old_email) && $this->_user_model->where('`email`="'.mysql_escape_string($email).'"')->find()) {
            $this->_error = L('PUBLIC_ACCOUNT_REGISTERED');         // 該使用者已註冊
            $res = false;
        }

        return (boolean)$res;
    }

    /**
     * 驗證郵箱內容的正確性
     * @param string $email 輸入郵箱的資訊
     * @param string $old_email 原始郵箱的資訊
     * @return boolean 是否驗證成功
     */
    public function isValidEmail($email, $old_email = null) {
        $res = preg_match($this->_email_reg, $email, $matches) !== 0;
        if(!$res) {
            $this->_error = L('PUBLIC_EMAIL_TIPS');         // 無效的Email地址
        } else if(!empty($this->_config['email_suffix'])) {
            $res = in_array($matches['1'], explode(',', $this->_config['email_suffix']));
            // !$res && $this->_error = $matches['1'].L('PUBLIC_EMAIL_SUFFIX_FORBIDDEN');               // 郵箱字尾不允許註冊
            !$res && $this->_error = '該郵箱字尾不允許註冊';                // 郵箱字尾不允許註冊
        }
        if($res && ($email != $old_email) && $this->_user_model->where('`email`="'.mysql_escape_string($email).'"')->find()) {
            $this->_error = L('PUBLIC_EMAIL_REGISTER');         // 該Email已被註冊
            $res = false;
        }

        return (boolean)$res;
    }

    /**
     * 驗證昵稱內容的正確性
     * @param string $name 輸入昵稱的資訊
     * @param string $old_name 原始昵稱的資訊
     * @return boolean 是否驗證成功
     */
    public function isValidName($name, $old_name = null) {
        // 默認不準使用的昵稱
        $protected_name = array('name', 'uname', 'admin', 'profile', 'space');
        $site_config = model('Xdata')->get('admin_Config:site');
        !empty($site_config['sys_nickname']) && $protected_name = array_merge($protected_name, explode(',', $site_config['sys_nickname']));
        $res = preg_match($this->_name_reg, $name) !== 0;
        if($res) {
            $length = get_str_length($name);
            $res = ($length >= 2 && $length <= 10);
        } else {
            $this->_error = '僅支援中英文，數字，下劃線';
            $res = false;
            return $res;
        }
        // 預保留昵稱
        if(in_array($name, $protected_name)) {
            $this->_error = L('PUBLIC_NICKNAME_RESERVED');              // 抱歉，該昵稱不允許被使用
            $res = false;
            return $res;
        }
        if(!$res) {
            $this->_error = L('PUBLIC_NICKNAME_LIMIT', array('nums'=>'2-10'));          // 昵稱長度必須在2-10個漢字之間
            return $res;
        }
        if(($name != $old_name) && $this->_user_model->where('`uname`="'.mysql_escape_string($name).'"')->find()) {
            $this->_error = L('PUBLIC_ACCOUNT_USED');               // 該使用者名已被使用
            $res = false;
        }
        // 敏感詞
        if (filter_keyword($name) !== $name) {
            $this->_error = '抱歉，該昵稱包含敏感詞不允許被使用';
            return false;
        }

        return $res;
    }

    /**
     * 驗證密碼內容的正確性
     * @param string $pwd 密碼資訊
     * @param string $repwd 確認密碼資訊
     * @return boolean 是否驗證成功
     */
    public function isValidPassword($pwd, $repwd) {
        $res = true;
        $length = strlen($pwd);
        if($length < 6) {
            $this->_error = L('PUBLIC_PASSWORD_TIPS');          // 密碼太短了，最少6位
            $res = false;
        } else if($pwd !== $repwd) {
            $this->_error = L('PUBLIC_PASSWORD_UNSIMILAR');     // 新密碼與確認密碼不一致
            $res = false;
        }

        return $res;
    }

    /**
     * 稽覈使用者
     * @param array $uids 使用者UID陣列
     * @param integer $type 類型，0表示取消稽覈，1表示通過稽覈
     * @return boolean 是否稽覈成功
     */
    public function audit($uids, $type=1) {
        // 處理資料
        !is_array($uids) && $uids = explode(',', $uids);
        $uids = array_unique(array_filter(array_map('intval', $uids)));
        // 稽覈指定使用者
        $map['uid'] = array('IN', $uids);
        $result = $this->_user_model->where($map)->setField('is_audit', $type);
        model('User')->cleanCache($uids);
        if(!$result) {
            $this->_error = L('PUBLIC_REVIEW_FAIL');        // 稽覈失敗
            return false;
        } else {
            if($type == 0) {
                $this->_error = L('PUBLIC_CANCEL_REVIEW_SUCCESS');      // 取消稽覈成功
                // 發送取消稽覈郵件
                foreach($uids as $touid) {
                    model('Notify')->sendNotify($touid, 'audit_error');
                }
                return true;
            }

            // 發送通過稽覈郵件
            foreach ($uids as $uid) {
                $this->sendActivationEmail($uid,'audit_ok');
            }
            $this->_error = L('PUBLIC_REVIEW_SUCCESS');     // 稽覈成功
            return true;
        }
    }

    /**
     * 給指定使用者發送啟用賬戶郵件
     * @param integer $uid 使用者UID
     * @param string $node 郵件模板類型
     * @return boolean 是否發送成功
     */
    public function sendActivationEmail($uid, $node ='register_active') {
        $map['uid'] = $uid;
        $user_info = $this->_user_model->where($map)->find();

        if(!$user_info) {
            $this->_error = L('PUBLI_USER_NOTEXSIT');           // 使用者不存在
            return false;
        } else if($user_info['is_audit']) {
            if($user_info['is_active'] == 1){
                $config['activeurl'] = $GLOBALS['ts']['site']['home_url'];
            }else{
                $code = $this->getActivationCode($user_info);
                $config['activeurl'] = U('public/Register/activate', array('uid'=>$uid, 'code'=>$code));
            }
            $config['name'] = $user_info['uname'];
            model('Notify')->sendNotify($uid, $node, $config);
            $this->_error = '發送成功';     // 系統已將一封啟用郵件發送至您的郵箱，請立即查收郵件啟用帳號
            return true;
        } else {
            $this->_error = !$user_info['is_audit'] ? L('PUBLIC_ACCOUNT_REVIEW_FAIL') : L('PUBLIC_ACCOUNT_ACTIVATED_SUCCESSFULLY');     // 您的帳號未通過稽覈，恭喜，帳號已成功啟用
            return false;
        }
    }

    /**
     * 啟用指定使用者
     * @param integer $uid 使用者UID
     * @param string $code 啟用碼
     * @return boolean 是否啟用成功
     */
    public function activate($uid, $code) {
        $map['uid'] = $uid;
        $user_info = $this->_user_model->where($map)->find();

        $res = ($code == $this->getActivationCode($user_info));
        if($res && !$user_info['is_active']) {
            $res = $this->_user_model->where($map)->save(array('is_active'=>1));
            $this->_user_model->cleanCache($uid);
        }

        if($res) {
            $this->_error = L('PUBLIC_ACCOUNT_ACTIVATED_SUCCESSFULLY');     // 恭喜，帳號已成功啟用
            return true;
        } else {
            $this->_error = L('PUBLIC_ACTIVATE_USER_FAIL');         // 啟用使用者失敗
            return false;
        }
    }

    /**
     * 獲取啟用碼
     * @param array $user_info 使用者的相關資訊
     * @return string 啟用碼
     */
    public function getActivationCode($user_info) {
        return md5($user_info['login'].$user_info['password'].$user_info['login_salt']);
    }

    /**
     * 初始化使用者賬號
     * @param integer $uid 使用者UID
     * @return boolean 是否成功初始化使用者賬號
     */
    public function initUser($uid) {
        $map['uid'] = $uid;
        $user_info  = $this->_user_model->where($map)->find();
        $user_info['is_active'] && $res = $this->_user_model->where($map)->save(array('is_init' => 1));
        // 清除使用者快取
        $this->_user_model->cleanCache($uid);

        if($res) {
            $this->_error = L('PUBLIC_ACCOUNT_INITIALIZE_SUCCESS');         // 帳號初始化成功
            return true;
        } else {
            $this->_error = L('PUBLIC_ACCOUNT_INITIALIZE_FAIL');            // 帳號初始化失敗
            return false;
        }
    }

    /**
     * 獲取最後的錯誤資訊
     * @return string 最後的錯誤資訊
     */
    public function getLastError() {
        return $this->_error;
    }

    /**
     * 修改指定使用者的註冊郵箱
     * @param integer $uid 使用者ID
     * @param string $email 郵箱地址
     * @return boolean 是否更改郵箱成功
     */
    public function changeRegisterEmail($uid, $email) {
        $map['uid'] = $uid;
        $data['login'] = $email;
        $data['email'] = $email;
        $res = $this->_user_model->where($map)->save($data);
        $res = (boolean)$res;
        if($res) {
            $this->_error = '更換郵箱成功';
            $this->_user_model->cleanCache($uid);
} else {
    $this->_error = '更換郵箱失敗';
}
return $res;
}

/**
 * 指定使用者初始化完成
 * @param integer $uid 使用者ID
 * @return boolean 是否初始化成功
 */
public function overUserInit($uid) {
    $map['uid'] = $uid;
    $data['is_init'] = 1;
    $res = $this->_user_model->where($map)->save($data);
    $res = (boolean)$res;
    if($res) {
        // 獲取使用者資訊
        $receiverInfo = model('User')->getUserInfo($uid);
        // 獲取發起邀請使用者ID
        $inviteUid = model('Invite')->where("code='{$receiverInfo['invite_code']}'")->getField('inviter_uid');
        // 邀請人積分操作
        model('Credit')->setUserCredit($inviteUid, 'invite_friend');
        // 相互關注操作
        model('Follow')->doFollow($uid, intval($inviteUid));
        model('Follow')->doFollow(intval($inviteUid), $uid);
        // 清除使用者快取
        $this->_user_model->cleanCache($uid);
        // 發送通知
        $config['name'] = $receiverInfo['uname'];
        $config['space_url'] = $receiverInfo['space_url'];
        model('Notify')->sendNotify($inviteUid, 'register_invate_ok', $config);
        $registerConfig = model('Xdata')->get('admin_Config:register');
        if($registerConfig['welcome_email']){
            model('Notify')->sendNotify($uid, 'register_welcome', $config);
}
}
// 清除使用者快取
$this->_user_model->cleanCache($uid);
return $res;
}
}
