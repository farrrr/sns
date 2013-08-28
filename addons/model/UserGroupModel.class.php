<?php
/**
 * 使用者組模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserGroupModel extends Model {

    protected $tableName = 'user_group';
    protected $fields = array(0=>'user_group_id',1=>'user_group_name',2=>'ctime',3=>'user_group_icon',4=>'user_group_type',5=>'app_name',6=>'is_authenticate');

    /**
     * 添加或修改使用者組資訊
     * @param array $d 相關使用者組資訊
     * @return integer 相關使用者組ID
     */
    public function addUsergroup($d) {

        $data['ctime'] = time();
        !empty($d['user_group_name']) && $data['user_group_name'] = t($d['user_group_name']);
        !empty($d['user_group_icon']) && $data['user_group_icon'] = t($d['user_group_icon']);
        isset($d['user_group_type']) && $data['user_group_type'] = intval($d['user_group_type']);
        isset($d['is_authenticate']) && $data['is_authenticate'] = intval($d['is_authenticate']);
        //dump($data);exit;
        if(!empty($d['user_group_id'])) {
            // 修改使用者組
            $amap['user_group_id'] = $d['user_group_id'];
            $res = $this->where($amap)->save($data);
        } else {
            // 添加使用者組
            $res = $this->add($data);
        }
        // 清除相關快取
        $this->cleanCache();

        return $res;
    }

    /**
     * 刪除指定的使用者組
     * @param integer $gid 使用者組ID
     * @return boolean 是否刪除成功
     */
    public function delUsergroup($gid) {
        // 驗證資料
        if(empty($gid)) {
            $this->error = L('PUBLIC_USERGROUP_ISNOT');         // 使用者組不能為空
            return false;
        }
        // 系統默認的使用者組不能進行刪除
        if(!is_array($gid) && $gid <= 3) {
            return false;
        }
        if(is_array($gid)) {
            foreach($gid as $v) {
                if($v <= 3) {
                    return false;
                }
            }
        }
        // 刪除指定使用者組
        $map = array();
        $map['user_group_id'] = is_array($gid) ? array('IN', $gid) : intval($gid);
        if($this->where($map)->delete()) {
            // TODO:後續操作
            D('user_group_link')->where('user_group_id='.$gid)->delete();  //刪除使用者關聯
            D('user_verified')->where('usergroup_id='.$gid)->delete();  //刪除認證使用者表資料
            $this->cleanCache();
            return true;
        }

        return false;
    }

    /**
     * 返回使用者組資訊
     * @param integer $gid 使用者組ID，默認為空字元串 - 顯示全部使用者組資訊
     * @return array 使用者組資訊
     */
    public function getUserGroup($gid = '') {
        if(($data = model('Cache')->get('AllUserGroup')) == false) {
            $list = $this->findAll();
            foreach($list as $k => $v) {
                $data[$v['user_group_id']] = $v;
            }
            model('Cache')->set('AllUserGroup', $data);
        }
        if(empty($gid)){
            // 返回全部使用者組
            return $data;
        } else {
            // 返回指定的使用者組
            if(is_array($gid)){
                $r = array();
                foreach($gid as $v){
                    $r[$v] = $data[$v];
                }
                return $r;
            } else {
                return $data[$gid];
            }
        }
    }

    /**
     * 獲取使用者組的Hash陣列
     * @param string $k Hash陣列的Key值欄位
     * @param string $v Hash陣列的Value值欄位
     * @return array 使用者組的Hash陣列
     */
    public function getHashUsergroup($k = 'user_group_id', $v = 'user_group_name') {
        $list = $this->getUserGroup();
        $r = array();
        foreach($list as $lv) {
            $r[$lv[$k]] = $lv[$v];
        }

        return $r;
    }

    /**
     * 清除使用者組快取
     * @return void
     */
    public function cleanCache($param) {
        model('Cache')->rm('AllUserGroup');
    }

    /**
     * 通過指定使用者組ID獲取使用者組資訊
     * @param string|array $gids 使用者組ID
     * @return array 指定使用者組ID獲取使用者組資訊
     */
    public function getUserGroupByGids($gids) {
        $data = static_cache( 'UserGroupByGid'.implode( ',' , $gids ) );
        if ( $data ){
            return $data;
        }
        if(empty($gids)) {
            return false;
        }
        !is_array($gids) && $gids = explode(',', $gids);

        $map['user_group_id'] = array('IN', $gids);
        $data = $this->where($map)->findAll();
        static_cache( 'UserGroupByGid'.implode( ',' , $gids ) , $data );
        return $data;
    }
    /**
     * 判斷使用者是否是管理員
     * @param unknown_type $uid
     */
    public function isAdmin( $uid ){
        $res = model( 'UserGroupLink' )->where('uid='.$uid.' and user_group_id=1')->getField('uid');
        return $res;
}
/**
 * 返回所以使用者組 id為key值
 */
public function getAllGroup(){
    $list = $this->findAll();
    $idkeylist = array();
    foreach ( $list as $v ){
        $idkeylist[$v['user_group_id']] = $v['user_group_name'];
}
return $idkeylist;
}

/**
 * 獲取指定使用者的使用者組圖示
 *
 * @param int $uid 使用者ID
 * @return string  返回使用者組圖示的img標籤
 */
public function getUserGroupIcon($uid) {
    $user_group      = $this->getAllUserGroup();
    $user_group_link = $this->getAllUserGroupLink();
    $user_group_link = $user_group_link[$uid];

    $html = '';

    foreach ($user_group_link as $v) {
        if ($user_group[$v['user_group_id']]['icon'])
            $html .= "<img class='ts_icon' src=".THEME_URL."/images/".$user_group[$v['user_group_id']]['icon']." title=".$user_group[$v['user_group_id']]['title'].">";
}

return $html;
}

/**
 * 相容2.8版塊函數
 * 按照查詢條件獲取使用者組
 *
 * @param array  $map   查詢條件
 * @param string $field 欄位 默認*
 * @param string $order 排序 默認 以使用者組ID升序排列
 * @return array 使用者組資訊
 */
public function getUserGroupByMap($map = '', $field = '*', $order = 'user_group_id ASC') {
    return $this->field($field)->where($map)->order($order)->findAll();
}
}
