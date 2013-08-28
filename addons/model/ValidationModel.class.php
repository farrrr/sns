<?php
/**
 * 驗證模型 - 業務邏輯模型，沒有資料表暫時待定
 * @example
 * 驗證服務（ValidationService）主要用於邀請註冊、修改帳號等需要使用者驗證的操作。
 * @author daniel <desheng.young@gmail.com>
 * @version TS3.0
 */
class ValidationModel extends Model {

    protected $tableName = 'validation';
    protected $fields = array(0=>'validation_id',1=>'type',2=>'from_uid',3=>'to_user',4=>'data',5=>'code',6=>'target_url',7=>'is_active',8=>'ctime','_autoinc'=>true,'_pk'=>'validation_id');

    /**
     * 添加驗證
     * @param integer $from_uid 驗證的來源
     * @param string $to_user 驗證的目的地
     * @param string $target_url 進行驗證的url地址
     * @param string $type 驗證類型
     * @param string $data 附加資料
     * @return boolean 是否添加認證成功
     */
    public function addValidation($from_uid, $to_user, $target_url, $type = '', $data = '') {
        $validation['type'] = $type;
        $validation['from_uid'] = $from_uid;
        $validation['to_user'] = $to_user;
        $validation['data'] = $data;
        $validation['is_active'] = 1;
        $validation['ctime'] = time();
        $vid = model('Validation')->add($validation);

        if($vid) {
            $validation_code = $this->__generateCode($vid);
            $target_url = $target_url."&validationid=$vid&validationcode=$validation_code";
            $res = model('Validation')->where("`validation_id`=$vid")->setField(array('code','target_url'), array($validation_code, $target_url));
            if($res) {
                return $target_url;
            } else {
                return false;
            }
        } else {
            return $vid;
        }
    }

    /**
     * 分發驗證
     * @param integer $id 驗證的ID（為0或留空時，自動從$_REQUEST獲取）
     * @param string $validation_code 驗證碼（為0或留空時，自動從$_REQUEST獲取）
     * @return void
     */
    public function dispatchValidation($id = 0, $validation_code = 0) {
        if(!$this->getValidation($id, $validation_code)) {
            redirect(SITE_URL, 5, L('PUBLIC_INVATE_CODE_ERROR'));           // 邀請碼錯誤或已失效
        }
    }

    /**
     * 取消邀請，即設定驗證為失效
     * @param integer $id 驗證的ID（為0或留空時，自動從$_REQUEST獲取）
     * @return boolean 是否取消成功
     */
    public function unsetValidation($id = 0) {
        $where = $id != 0 ? "`validation_id`=$id" : '`validation_id`='.intval($_REQUEST['validationid']).' AND `code`="'.h($_REQUEST['validationcode']).'"';
        return model('Validation')->where($where)->setField('is_active', 0);
    }

    /**
     * 獲取邀請詳細
     * @param integer $id 驗證的ID（為0或留空時，自動從$_REQUEST獲取）
     * @param string $code 驗證碼（為0或留空時，自動從$_REQUEST獲取）
     * @return mix 獲取失敗返回false，成功後返回邀請詳細
     */
    public function getValidation($id = 0, $code = 0) {
        if($id == 0 && $code == 0 && !empty($_REQUEST['validationid']) && !empty($_REQUEST['validationcode'])) {
            $where = '`validation_id`='.intval($_REQUEST['validationid']).' AND `code`="'.h($_REQUEST['validationcode']).'" AND `is_active`=1';
        } else if($id != 0) {
            $id = intval($id);
            $where = $code == 0 ? "`validation_id`=$id AND `is_active`=1" : "`validation_id`=$id AND `is_active`=1 AND `code`='$code'";
        } else if($code != 0) {
            $where = $id == 0 ? '`code`="'.h($code).'" AND `is_active`=1' : "`validation_id`=$id AND `is_active`=1 AND `code`='$code'";
        } else {
            return false;
        }

        return model('Validation')->field('validation_id AS validationid, type,from_uid,to_user,data,code,target_url,is_active,ctime')->where($where)->find();
    }

    /**
     * 判斷給定的驗證ID和驗證碼是否合法
     * @param integer $id 驗證的ID（為0或留空時，自動從$_REQUEST獲取）
     * @param string $code 驗證碼（為0或留空時，自動從$_REQUEST獲取）
     * @return boolean 驗證ID與驗證碼是否合法
     */
    public function isValidValidationCode($id = 0, $code = 0) {
        if(($id == 0 && $code != 0) || ($id != 0 && $code == 0)) {
            return false;
        }

        if($id == 0 && $code == 0) {
            $id = intval($_REQUEST['validationid']);
            $code = h($_REQUEST['validationcode']);
    }

    return model('Valiation')->where("`validation_id`=$id AND `code`='$code' AND `is_active`=1")->find();
    }

    /**
     * 檢查Email地址是否合法
     * @param string $email 郵件地址
     * @return boolean 郵件地址是否合法
     */
    public function isValidEmail($email) {
        return preg_match("/[_a-zA-Z\d\-\.]+@[_a-zA-Z\d\-]+(\.[_a-zA-Z\d\-]+)+$/i", $email) !== 0;
    }

    /**
     * 驗證Email是否可用
     * @param string $email 郵件地址
     * @param integer $uid 邀請人的使用者ID
     * @return boolean 郵件地址是否可用
     */
    public function isEmailAvailable($email, $uid = false) {
        // Email格式錯誤
        if(!$this->isValidEmail($email)) {
            return false;
    } else if($res = model('User')->field('`uid`')->where("`email`='{$email}'")->find()) {
        // 邀請資料已經存在
        if($uid) {
            if($res['uid'] != intval($uid)) {
                return false;
    }
    } else {
        return false;
    }
    }

    return true;
    }

    /**
     * 生成唯一的驗證碼
     * @param integer $id 驗證的ID（為0或留空時，自動從$_REQUEST獲取）
     * @return string 驗證碼
     */
    private function __generateCode($id) {
        return md5($id.'thinksns#^!@*#%^!@#');
    }
    }
