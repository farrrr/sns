<?php
/**
 * 使用者組關聯模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserGroupLinkModel extends Model {

    protected $tableName = 'user_group_link';
    protected $fields = array(0 =>'id',1=>'uid',2=>'user_group_id');

    /**
     * 轉移使用者的使用者組
     * @param string $uids 使用者UID，多個用“，”分割
     * @param string $user_group_id 使用者組ID，多個用“，”分割
     * @return boolean 是否轉移成功
     */
    public function domoveUsergroup($uids, $user_group_id) {
        // 驗證資料
        if(empty($uids) && empty($user_group_id)) {
            $this->error = L('PUBLIC_USER_GROUP_EMPTY');            // 使用者組或使用者不能為空
            return false;
        }
        $uids = explode(',', $uids);
        $user_group_id = explode(',', $user_group_id);
        $uids = array_unique(array_filter($uids));
        $user_group_id = array_unique(array_filter($user_group_id));
        if(!$uids || !$user_group_id) {
            return false;
        }
        $map['uid'] = array('IN', $uids);
        $this->where($map)->delete();
        foreach($uids as $v) {
            $save = array();
            $save['uid'] = $v;
            foreach($user_group_id as $gv){
                $save['user_group_id'] = $gv;
                $this->add($save);
            }
            // 清除許可權快取
            model('Cache')->rm('perm_user_'.$v);
            model ( 'Cache' )->rm ( 'user_group_' . $v );
        }
        model('User')->cleanCache($uids);

        return true;
    }

    /**
     * 獲取使用者的使用者組資訊
     * @param array $uids 使用者UID陣列
     * @return array 使用者的使用者組資訊
     */
    public function getUserGroup($uids) {
        $uids = !is_array($uids) ? explode(',', $uids) : $uids;
        $uids = array_unique(array_filter($uids));
        if(!$uids) return false;

        $return = array();
        foreach ($uids as $uid){
            $return[$uid] = model ( 'Cache' )->get ( 'user_group_' . $uid);
            if($return[$uid]==false){
                $map['uid'] = $uid;
                $list = $this->where($map)->findAll();
                $return[$uid] = getSubByKey($list, 'user_group_id');
                model ( 'Cache' )->set ( 'user_group_' . $uid, $return[$uid]);
            }
        }
        return $return;
    }

    /**
     * 獲取使用者所在使用者組詳細資訊
     * @param array $uids 使用者UID陣列
     * @return array 使用者的使用者組詳細資訊
     */
    public function getUserGroupData($uids){
        $uids = !is_array($uids) ? explode(',', $uids) : $uids;
        $uids = array_unique(array_filter($uids));
        if(!$uids) return false;
        $userGids = $this->getUserGroup($uids);
        //return $userGids;exit;
        $uresult = array();
        foreach ( $userGids as $ug){
            if ( $uresult ){
                $ug && $uresult = array_merge( $uresult , $ug );
            } else {
                $uresult = $ug;
            }
        }
        //把所有使用者組資訊查詢出來
        $ugresult = model('UserGroup')->getUserGroupByGids(array_unique($uresult));
        $groupresult = array();
        foreach ( $ugresult as $ur ){
            $groupresult[$ur['user_group_id']] = $ur;
        }
        foreach($userGids as $k=>$v){
            $ugroup = array();
            foreach ( $userGids[$k] as $userg){
                $ugroup[] = $groupresult[$userg];
            }
            $userGroupData[$k] = $ugroup;
            foreach($userGroupData[$k] as $key => $value) {
                if(isset($value['user_group_icon']) && $value['user_group_icon'] == -1) {
                    unset($userGroupData[$k][$key]);
                    continue;
                }
                $userGroupData[$k][$key]['user_group_icon_url'] = THEME_PUBLIC_URL.'/image/usergroup/'.$value['user_group_icon'];
            }
        }
        return $userGroupData;
    }
}
