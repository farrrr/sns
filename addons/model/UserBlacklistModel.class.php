<?php
/**
 * 黑名單模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserBlacklistModel extends Model {

    protected $tableName = 'user_blacklist';
    protected $fields = array(0 =>'uid',1=>'fid',2=>'ctime');
    static $blackHash = array();

    /**
     * 獲取指定使用者的黑名單列表
     * @param integer $uid 使用者UID
     * @return array 指定使用者的黑名單列表
     */
    public function getUserBlackList($uid) {
        $uid = intval($uid);
        if(empty($uid)) {
            $this->error = '使用者ID不能為空';
        }
        if(isset(self::$blackHash[$uid])) {
            return self::$blackHash[$uid];
        }
        if(($list = model('Cache')->get('u_blacklist_'.$uid)) == false) {
            $map['uid'] = $uid;
            $list = $this->where($map)->getHashList('fid');
            model('Cache')->set('u_blacklist_'.$uid, $list);
        }
        self::$blackHash[$uid] = $list;

        return $list;
    }

    /**
     * 指定使用者添加黑名單
     * @param integer $uid 指定使用者UID
     * @param integer $fid 黑名單使用者UID
     * @return boolean 是否添加成功
     */
    public function addUser($uid, $fid) {
        $uid = intval($uid);
        $fid = intval($fid);
        if(empty($uid) || empty($fid)) {
            $this->error = '使用者ID不能為空';
            return false;
        }
        $blackList = $this->getUserBlackList($uid);
        if(isset($blackList[$fid])) {
            $this->error = '使用者已經在黑名單中了';
            return false;
        }
        $blackList[$fid] = array('uid'=>$uid, 'fid'=>$fid, 'ctime'=>time());
        if($this->add($blackList[$fid])) {
            model('Follow')->unFollow($uid, $fid);
            model('Follow')->unFollow($fid, $uid);
            model('Cache')->set('u_blacklist_'.$uid,$blackList);
            return true;
        }
        return false;
    }

    /**
     * 指定使用者取消黑名單
     * @param integer $uid 指定使用者UID
     * @param integer $fid 黑名單使用者UID
     * @return boolean 是否移除成功
     */
    public function removeUser($uid, $fid) {
        $uid = intval($uid);
        $fid = intval($fid);
        if(empty($uid) || empty($fid)) {
            $this->error = '使用者ID不能為空';
            return false;
        }
        $blackList = $this->getUserBlackList($uid);
        if(!isset($blackList[$fid])) {
            $this->error = '使用者不在黑名單中了';
            return false;
        }
        unset($blackList[$fid]);
        $map['uid'] = $uid;
        $map['fid'] = $fid;
        if($this->where($map)->limit(1)->delete()) {
            model('Cache')->set('u_blacklist_'.$uid,$blackList);
            return true;
        }
        return false;
    }

    /**
     * 清除使用者的黑名單快取資訊
     * @param array $uids 使用者UID陣列
     * @return boolean 快取是否清除成功
     */
    public function cleanCache($uids) {
        $uids = is_array($uids) ? $uids : explode(',', $uids);
        $cache = model('Cache');
        foreach($uids as $uid) {
            $cache->rm('u_blacklist_'.$uid);
        }

        return true;
    }
}
