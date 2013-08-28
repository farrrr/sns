<?php
/**
 * 使用者統計資料模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserDataModel extends Model {

    protected $tableName = 'user_data';
    protected $fields = array(0=>'id',1=>'uid',2=>'key',3=>'value',4=>'mtime');
    protected $uid = '';

    /**
     * 初始化方法，設定默認使用者資訊
     * @return void
     */
    public function _initialize() {
        $this->uid = $GLOBALS['ts']['mid'];
    }

    /**
     * 設定使用者UID
     * @param integer $uid 使用者UID
     * @return object 使用者統計資料物件
     */
    public function setUid($uid) {
        $this->uid = $uid;
        return $this;
    }

    /**
     * 更新某個使用者的指定Key值的統計數目
     * Key值：
     * feed_count：微博總數
     * weibo_count：微博數
     * favorite_count：收藏數
     * following_count：關注數
     * follower_count：粉絲數
     * unread_comment：評論未讀數
     * unread_atme：@Me未讀數
     * @param string $key Key值
     * @param integer $nums 更新的數目
     * @param boolean $add 是否添加數目，默認為true
     * @param integer $uid 使用者UID
     * @return array 返回更新後的資料
     */
    public function updateKey($key, $nums, $add = true, $uid = '') {
        if($nums == 0) {
            $this->error = L('PUBLIC_MODIFY_NO_REQUIRED');          // 不需要修改
            return false;
        }
        // 若更新數目小於0，則默認為減少數目
        $nums < 0 && $add = false;
        $key = t($key);
        // 獲取當前設定使用者的統計數目
        $data = $this->getUserData($uid);
        if(empty($data) || !$data) {
            $data = array();
            $data[$key] = $nums;
        } else {
            $data[$key] = $add ? ($data[$key] + abs($nums)) :($data[$key] - abs($nums));
        }

        $data[$key] < 0 && $data[$key] = 0;

        $map['uid'] = empty($uid) ? $this->uid : $uid;
        $map['key'] = $key;
        $this->where($map)->limit(1)->delete();
        $map['value'] = $data[$key];
        $map['mtime'] = date('Y-m-d H:i:s');
        $this->add($map);
        model('Cache')->rm('UserData_'.$map['uid']);

        return $data;
    }

    /**
     * 設定指定使用者指定Key值的統計數目
     * @param integer $uid 使用者UID
     * @param string $key Key值
     * @param integer $value 設定的統計數值
     * @return void
     */
    public function setKeyValue($uid, $key, $value) {
        $map['uid'] = $uid;
        $map['key'] = $key;
        $this->where($map)->delete();
        $map['value'] = intval($value);
        $this->add($map);
        // 清掉該使用者的快取
        model('Cache')->rm('UserData_'.$uid);
    }

    /**
     * 獲取指定使用者的統計資料
     * @param integer $uid 使用者UID
     * @return array 指定使用者的統計資料
     */
    public function getUserData($uid = '') {
        // 默認為設定的使用者
        if(empty($uid)) {
            $uid = $this->uid;
        }
        if(($data = model('Cache')->get('UserData_'.$uid)) === false || count($data) == 1) {
            $map['uid'] = $uid;
            $data = array();
            $list = $this->where($map)->findAll();
            if(!empty($list)) {
                foreach($list as $v) {
                    $data[$v['key']] = (int)$v['value'];
                }
            }
            model('Cache')->set('UserData_'.$uid, $data, 60);
        }

        return $data;
    }

    /**
     * 批量獲取多個使用者的統計數目
     * @param array $uids 使用者UID陣列
     * @return array 多個使用者的統計數目
     */
    public function getUserDataByUids($uids) {
        $return = $notCache = array();
        $data = model('Cache')->getList('UserData_', $uids);
        // 判斷是否存在沒有快取的資料
        foreach($uids as $k => $v) {
            if(!isset($data[$v]) || empty($data[$v])) {
                $notCache[] = $v;
            }
        }
        // 如果存在沒有快取的資料，獲取快取資料，在進行排序
        if(!empty($notCache)) {
            foreach($notCache as $v) {
                $data[$v] = $this->getUserData($v);
            }
            // 重新排序
            foreach($uids as $v){
                $return[$v] = $data[$v];
            }
            return $return;
        } else {
            return $data;
        }
    }
    public function getUserKeyDataByUids( $key = "weibo_count" , $uids){
        $map['uid'] = array( 'in' , $uids );
        $map['key'] = $key;
        $list = $this->where($map)->field("uid,value")->findAll();
        $rearray = array();
        foreach ( $list as $v ){
            $rearray[$v['uid']][$key] = $v['value'];
        }
        return $rearray;
    }
    /**
     * 手動統計更新使用者資料，微博、關注、粉絲、收藏
     * @return void
     */
    public function updateUserData() {
        set_time_limit(0);
        // 總微博數目和未假刪除的微博數目
        $sql = 'SELECT uid, count(feed_id) as total, SUM(is_del) as delSum FROM '.C('DB_PREFIX').'feed GROUP BY uid';
        $list = M()->query($sql);
        foreach ($list as $vo){
            $res[$vo['uid']]['feed_count'] = intval($vo['total']);
            $res[$vo['uid']]['weibo_count'] = $res[$vo['uid']]['feed_count'] - intval($vo['delSum']);
}
// 收藏數目
$sql = 'SELECT uid, count(collection_id) as total FROM '.C('DB_PREFIX').'collection GROUP BY uid';
$list = M()->query($sql);
foreach ($list as $vo){
    $res[$vo['uid']]['favorite_count'] = intval($vo['total']);
}
// 關注數目
$sql = 'SELECT uid, count(follow_id) as total FROM '.C('DB_PREFIX').'user_follow GROUP BY uid';
$list = M()->query($sql);
foreach ($list as $vo){
    $res[$vo['uid']]['following_count'] = intval($vo['total']);
}
// 粉絲數目
$sql = 'SELECT fid, count(follow_id) as total FROM '.C('DB_PREFIX').'user_follow GROUP BY fid';
$list = M()->query($sql);
foreach ($list as $vo){
    $res[$vo['fid']]['follower_count'] = intval($vo['total']);
}

$uids = array_keys($res);
if(empty($uids)){
    return false;
}

$map['uid'] = array('in', $uids);
$map['key'] = array('in', array('feed_count', 'weibo_count', 'favorite_count', 'following_count', 'follower_count'));
$this->where($map)->delete();

foreach($res as $uid=>$vo){
    $data['uid'] = $uid;
    foreach($vo as $key=>$val){
        $data['key'] = $key;
        $data['value'] = intval($val);
        $this->add($data);
}

// 清掉該使用者的快取
model('Cache')->rm('UserData_'.$uid);
}


}
}
