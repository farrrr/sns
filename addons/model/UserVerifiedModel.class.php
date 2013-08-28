<?php
/**
 * 使用者認證模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserVerifiedModel extends Model {

    protected $tableName = 'user_verified';
    protected $fields = array('id', 'uid', 'usergroup_id', 'user_verified_category_id', 'company', 'realname', 'idcard', 'phone', 'info', 'verified', 'attach_id');

    /**
     * 獲取指定使用者的認證資訊
     * @param array $uids 使用者ID
     * @return array 指定使用者的認證資訊
     */
    public function getUserVerifiedInfo($uids) {
        if(empty($uids)) {
            return array();
        }
        $map['uid'] = array('IN', $uids);
        $map['verified'] = 1;
        $data = $this->where($map)->getHashList('uid', 'info');
        return $data;
    }
}
