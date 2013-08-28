<?php
/**
 * 使用者隱私模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserPrivacyModel extends Model {

    protected $tableName = 'user_privacy';
    protected $fields = array(0=>'uid',1=>'key',2=>'value');

    /**
     * 獲取指定使用者的隱私設定
     * @param integer $uid 使用者UID
     * @return array 指定使用者的隱私設定資訊
     */
    public function getUserSet($uid) {
        $set = $this->_defaultSet();

        $userPrivacy = $this->where('uid='.$uid)->field('`key`,`value`')->findAll();
        if($userPrivacy) {
            foreach($userPrivacy as $k => $v) {
                $set[$v['key']] = $v['value'];
            }
        }

        return $set;
    }

    /**
     * 儲存指定使用者的隱私配置
     * @param integer $uid 使用者UID
     * @param array $data 隱私配置相關資料
     * @return boolean 是否儲存成功
     */
    public function dosave($uid, $data) {
        // 驗證資料
        if(empty($uid)) {
            return false;
        }
        $map = array();
        $map['uid'] = $uid;
        $this->where($map)->delete();
        foreach($data as $key=>$value) {
            $key = t( $key );
            $value = intval( $value );
            $sql[] = "($uid,'{$key}',{$value})";
        }
        $sql = "INSERT INTO {$this->tablePrefix}user_privacy (uid,`key`,`value`) VALUES ".implode(',', $sql);
        $res = $this->query($sql);

        $this->error = L('PUBLIC_SAVE_SUCCESS');            // 儲存成功

        return true;
    }

    /**
     * 獲取A使用者針對B使用者的隱私設定情況
     * @param integer $mid B使用者UID
     * @param integer $uid A使用者UID
     * @return integer 隱私狀態，0表示不限制；1表示限制，不可以發送
     */
    public function getPrivacy($mid, $uid) {
        $data  = $this->getUserSet($uid);
        // $mid為0表示系統
        if($mid != $uid && $mid != 0) {
            if($this->isInBlackList($mid, $uid)){
                $data['comment_weibo'] = 1;
                $data['message'] = 1;
                $data['space'] = 1;
            }else{
                $followState = model('Follow')->getFollowState($uid, $mid);
                if($data['comment_weibo'] != 0 && $followState['following'] == 1) {
                    $data['comment_weibo'] = 0;
                }
                if($data['message'] != 0 && $followState['following'] == 1) {
                    $data['message'] = 0;
                }
                if($data['space'] != 0 && $followState['following'] == 1) {
                    $data['space'] = 0;
                }
            }
        }

        return $data;
    }

    /**
     * 系統的默認使用者隱私設定配置
     * @return array 默認隱私配置陣列
     */
    private function _defaultSet() {
        return array(
            'comment_weibo' => 0,       // 所有人
            'message' => 0,             // 所有人
            'space' => 0,               // 所有人
            //'email' => 0,             // 接收系統郵件
            'atme_email' => 0,              // 接收系統郵件
            'comment_email' => 0,               // 接收系統郵件
            'message_email' => 0,               // 接收系統郵件


        );
    }

    /**
     * 判斷使用者是否是黑名單關係
     * @param  integer  $mid B使用者UID
     * @param  integer  $uid A使用者UID
     * @return array
     */
    function isInBlackList($mid,$uid){
        $uid = intval($uid);
        $mid = intval($mid);
        $result = D('user_blacklist')->where("uid=$uid AND fid=$mid")->find();
        return  $result;
    }
}
