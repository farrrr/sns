<?php
/**
 * 使用者關注模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version 1.0
 */
class FollowModel extends Model {

    protected $tableName = 'user_follow';
    protected $fields = array(0=>'follow_id',1=>'uid',2=>'fid',3=>'remark',4=>'ctime','_autoinc'=>true,'_pk'=>'follow_id');

    /**
     * 獲取關注查詢SQL語句，具體使用不清楚
     * @param integer $uid 使用者ID
     * @return string 關注查詢SQL語句
     */
    public function getFollowingSql($uid) {
        return "SELECT `fid` FROM {$this->tablePrefix}user_follow WHERE `uid` = '{$uid}'";
    }

    /**
     * 獲取指定使用者的備註列表
     * @param integer $uid 使用者ID
     * @return array 指定使用者的備註列表
     */
    public function getRemarkHash($uid) {
        if(empty($uid)) {
            return false;
        }
        if($list = static_cache('follow_remark_'.$uid)) {
            return $list;
        }

        $map['uid'] = $uid;
        $map['remark'] = array('NEQ', '');
        $list = $this->where($map)->getHashList('fid', 'remark');
        static_cache('follow_remark_'.$uid, $list);

        return $list;
    }

    /**
     * 添加關注 (關注使用者)
     * @example
     * null：參數錯誤
     * 11：已關注
     * 12：關注成功(且為單向關注)
     * 13：關注成功(且為互粉)
     * @param integer $uid 發起操作的使用者ID
     * @param integer $fid 被關注的使用者ID或被關注的話題ID
     * @return boolean 是否關注成功
     */
    public function doFollow($uid, $fid) {
        if ( intval( $uid ) <= 0 || $fid <= 0 ){
            $this->error= L('PUBLIC_WRONG_DATA');           // 錯誤的參數
            return false;
        }

        if ($uid == $fid){
            $this->error=L('PUBLIC_FOLLOWING_MYSELF_FORBIDDEN');        // 不能關注自己
            return false;
        }

        if (!model('User')->find($fid)){
            $this->error=L('PUBLIC_FOLLOWING_PEOPLE_NOEXIST');          // 被關注的使用者不存在
            return false;
        }

        if (model('UserPrivacy')->isInBlackList($uid,$fid)) {
            $this->error='根據對方設定，您無法關注TA';
            return false;
        }else if(model('UserPrivacy')->isInBlackList($fid,$uid)){
            $this->error='您已把對方加入黑名單';
            return false;
        }
        //維護感興趣的人的快取
        model('Cache')->set('related_user_'.$uid, '' , 24 * 60 * 60);
        // 獲取雙方的關注關係
        $follow_state = $this->getFollowState($uid, $fid);
        // 未關注狀態
        if(0 == $follow_state['following']) {
            // 添加關注
            $map['uid']  = $uid;
            $map['fid']  = $fid;
            $map['ctime'] = time();
            $result = $this->add($map);
            // 通知和微博
/*          model('Notify')->send($fid, 'user_follow', '', $uid);
model('Feed')->put('user_follow', array('fid'=>$fid), $uid);*/
            if($result) {
                $maps['key'] = 'email';
                $maps['uid'] = $fid;
                $isEmail = D('user_privacy')->where($map)->field('value')->find();
                if($isEmail['value'] === 0){
                    $userInfo = model('User')->getUserInfo($fid);
                    model('Mail')->send_email($userInfo['email'],'您增加了一個新粉絲','content');
                }
                $this->error = L('PUBLIC_ADD_FOLLOW_SUCCESS');          // 關注成功
                $this->_updateFollowCount($uid, $fid, true);            // 更新統計
                $follow_state['following'] = 1;
                return $follow_state;
            } else {
                $this->error = L('PUBLIC_ADD_FOLLOW_FAIL');             // 關註失敗
                return false;
            }
        } else {
            $this->error = L('PUBLIC_FOLLOW_ING');                      // 已關注
            return false;
        }
    }

    public function bulkDoFollow($uid, $fids) {
        $follow_states = $this->getFollowStateByFids($uid, $fids);
        $data  = array();
        $_fids = array();
        foreach ($follow_states as $f_s_k => $f_s_v) {
            // 未關注
            if (0 == $f_s_v['following']) {
                // 關注的欄位資料
                $data[]  = "({$uid}, {$f_s_k},".time().")";
                $_fids[] = $f_s_k;
                $follow_states[$f_s_k]['following'] = 1;
                // 通知和微博
/*              model('Notify')->send($fid, 'user_follow', '', $uid);
model('Feed')->put('user_follow', array('fid'=>$fid), $uid);*/
            } else {
                unset($follow_states[$f_s_k]);
            }
        }
        if (!empty($data)) {
            $sql = "INSERT INTO {$this->tablePrefix}{$this->tableName}(`uid`,`fid`,`ctime`) VALUES" . implode(',', $data);
            $res = $this->execute($sql);
            if ($res) {
                // 關注成功
                $this->error=L('PUBLIC_ADD_FOLLOW_SUCCESS');

                // 更新統計
                $this->_updateFollowCount($uid, $_fids, true);

                return $follow_states;
            } else {
                $this->error = L('PUBLIC_ADD_FOLLOW_FAIL');
                return false;
            }
        } else {
            // 全部已關注
            $this->error = L('PUBLIC_FOLLOW_ING');
            return false;
        }
    }

    /**
     * 雙向關注使用者操作
     * @param integer $uid 使用者ID
     * @param array $fids 需關注使用者ID陣列
     * @return boolean 是否雙向關注成功
     */
    public function eachDoFollow($uid, $fids)
    {
        // 獲取使用者關組狀態
        $followStates = $this->getFollowStateByFids($uid, $fids);
        $data = array();
        $_following = array();
        $_follower = array();

        foreach($followStates as $key => $value) {
            if(0 == $value['following']) {
                $data[] = "({$uid}, {$key}, ".time().")";
                $_following[] = $key;
            }
            if(0 == $value['follower']) {
                $data[] = "({$key}, {$uid}, ".time().")";
                $_follower[] = $key;
            }
        }
        // 處理資料結果
        if(!empty($data)) {
            $sql = "INSERT INTO {$this->tablePrefix}{$this->tableName}(`uid`,`fid`,`ctime`) VALUES ".implode(',', $data);
            $res = $this->execute($sql);
            if($res) {
                // 關注成功
                $this->error = L('PUBLIC_ADD_FOLLOW_SUCCESS');

                //被關注人的關注人數+1
                foreach ( $_follower as $fo){
                    model('UserData')->setUid($fo)->updateKey('following_count', 1, true);
                }
                // 更新被關注人的粉絲數統計
                $this->_updateFollowCount($uid, $_following, true);
                //更新關注人的粉絲數
                model('UserData')->setUid($uid)->updateKey('follower_count', count($_follower), true);
                return true;
            } else {
                $this->error = L('PUBLIC_ADD_FOLLOW_FAIL');
                return false;
            }
        } else {
            // 已經全部關注
            $this->error = L('PUBLIC_FOLLOW_ING');
            return false;
        }
    }

    /**
     * 取消關注（關注使用者 / 關注話題）
     * @example
     * 00：取消失敗
     * 01：取消成功
     * @param integer $uid 發起操作的使用者ID
     * @param integer $fid 被取消關注的使用者ID或被取消關注的話題ID
     * @return boolean 是否取消關注成功
     */
    public function unFollow($uid, $fid) {
        $map['uid'] = $uid;
        $map['fid'] = $fid;
        // 獲取雙方的關注關係
        $follow_state = $this->getFollowState($uid, $fid);
        if(1 == $follow_state['following']) {
            // 已關注
            // 清除對該使用者的分組，再刪除關注
            if((false !== D('UserFollowGroupLink')->where($map)->delete()) && $this->where($map)->delete()) {
                //D('UserFollowGroupLink')->where($map)->delete();
                $this->error = L('PUBLIC_ADMIN_OPRETING_SUCCESS');          // 操作成功
                $this->_updateFollowCount($uid, $fid, false);               // 更新統計
                $follow_state['following'] = 0;
                return $follow_state;
            } else {
                $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');            // 操作失敗
                return false;
            }
        } else {
            // 未關注
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');                // 操作失敗
            return false;
        }
    }

    /**
     * 獲取指定使用者的關注與粉絲數
     * @param array $uids 使用者ID陣列
     * @return array 指定使用者的關注與粉絲數
     */
    public function getFollowCount($uids) {
        $count = array();
        foreach($uids as $u_v) {
            $count[$u_v] = array('following'=>0,'follower'=>0);
        }

        $following_map['uid'] = $follower_map['fid'] = array('IN', $uids);
        // 關注數
        $following = $this->field('COUNT(1) AS `count`,`uid`')->where($following_map)->group('`uid`')->findAll();
        foreach($following as $v) {
            $count[$v['uid']]['following'] = $v['count'];
        }
        // 粉絲數
        $follower = $this->field('COUNT(1) AS `count`,`fid`')->where($follower_map)->group('`fid`')->findAll();
        foreach ($follower as $v) {
            $count[$v['fid']]['follower'] = $v['count'];
        }

        return $count;
    }

    /**
     * 獲取指定使用者的關注列表 分頁
     * @param integer $uid 使用者ID
     * @param integer $gid 關注組ID，默認為空
     * @param integer $limit 結果集數目，默認為10
     * @return array 指定使用者的關注列表
     */
    public function getFollowingList($uid, $gid = null, $limit = 10) {
        $limit = intval($limit) > 0 ? $limit : 10;
        if(is_numeric($gid)) {
            // 關組分組
            if($gid == 0) {
                $list = $this->table("{$this->tablePrefix}{$this->tableName} AS follow LEFT JOIN {$this->tablePrefix}user_follow_group_link AS link ON link.follow_id = follow.follow_id")
                    ->field('follow.*')
                    ->where("follow.uid={$uid} AND link.follow_id IS NULL")
                    ->order('follow.uid DESC')
                    ->findPage($limit);
            } else {
                $list = $this->field('follow.*')
                    ->table("{$this->tablePrefix}user_follow_group_link AS link LEFT JOIN {$this->tablePrefix}{$this->tableName} AS follow ON link.follow_id=follow.follow_id AND link.uid=follow.uid")
                    ->where("follow.uid={$uid} AND link.follow_group_id={$gid}")
                    ->order('follow.uid DESC')
                    ->findPage($limit);
            }
        } else {
            // 沒有指定關組分組的列表
            $list = $this->where("`uid`={$uid}")->order('`follow_id` DESC')->findPage($limit);
        }

        return $list;
    }

    /**
     * 獲取指定使用者的關注列表  不分頁
     * @param integer $uid 使用者ID
     * @param integer $gid 關注組ID，默認為空
     * @param integer $limit 結果集數目，默認為10
     * @return array 指定使用者的關注列表
     */
    public function getFollowingListAll($uid, $gid = null) {
        if(is_numeric($gid)) {
            // 關組分組
            if($gid == 0) {
                $list = $this->table("{$this->tablePrefix}{$this->tableName} AS follow LEFT JOIN {$this->tablePrefix}user_follow_group_link AS link ON link.follow_id = follow.follow_id")
                    ->field('follow.*')
                    ->where("follow.uid={$uid} AND link.follow_id IS NULL")
                    ->order('follow.uid DESC')
                    ->findAll();
            } else {
                $list = $this->field('follow.*')
                    ->table("{$this->tablePrefix}user_follow_group_link AS link LEFT JOIN {$this->tablePrefix}{$this->tableName} AS follow ON link.follow_id=follow.follow_id AND link.uid=follow.uid")
                    ->where("follow.uid={$uid} AND link.follow_group_id={$gid}")
                    ->order('follow.uid DESC')
                    ->findAll();
            }
        } else {
            // 沒有指定關組分組的列表
            $list = $this->where("`uid`={$uid}")->order('`follow_id` DESC')->findAll();
        }

        return $list;
    }


    /**
     * 獲取指定使用者的粉絲列表
     * @param integer $uid 使用者ID
     * @param integer $limit 結果集數目，默認為10
     * @return array 指定使用者的粉絲列表
     */
    public function getFollowerList($uid, $limit = 10) {
        $limit = intval($limit) > 0 ? $limit : 10;
        // 粉絲列表
        $list = $this->where("`fid`={$uid}")->order('`follow_id` DESC')->findPage($limit);
        $fids = getSubByKey($list['data'], 'uid');
        // 格式化資料
        foreach($list['data'] as $key => $value) {
            $uid = $value['uid'];
            $fid = $value['fid'];
            $list['data'][$key]['uid'] = $fid;
            $list['data'][$key]['fid'] = $uid;
        }

        return $list;
    }

    /**
     * 獲取使用者uid與使用者fid的關注狀態，已uid為主
     * @param integer $uid 使用者ID
     * @param integer $fid 使用者ID
     * @return integer 使用者關注狀態，格式為array('following'=>1,'follower'=>1)
     */
    public function getFollowState($uid, $fid) {
        $follow_state = $this->getFollowStateByFids($uid, $fid);
        return $follow_state[$fid];
    }

    /**
     * 批量獲取使用者uid與一群人fids的彼此關注狀態
     * @param integer $uid 使用者ID
     * @param array $fids 使用者ID陣列
     * @return array 使用者uid與一群人fids的彼此關注狀態
     */
    public function getFollowStateByFids($uid, $fids) {
        array_map( 'intval' , $fids);
        $_fids = is_array($fids) ? implode(',', $fids) : $fids;
        if(empty($_fids)) {
            return array();
        }
        $follow_data = $this->where(" ( uid = '{$uid}' AND fid IN({$_fids}) ) OR ( uid IN({$_fids}) and fid = '{$uid}')")->findAll();
        $follow_states = $this->_formatFollowState($uid, $fids, $follow_data);
        return $follow_states[$uid];
    }

    /**
     * 獲取朋友列表資料 - 分頁
     * @param integer $uid 使用者ID
     * @return array 朋友列表資料
     */
    public function getFriendsList($uid) {
        $data = D()->table('`'.$this->tablePrefix.'user_follow` AS a LEFT JOIN `'.$this->tablePrefix.'user_follow` AS b ON a.uid = b.fid AND b.uid = a.fid')
            ->field('a.fid')
            ->where('a.uid = '.$uid.' AND b.uid IS NOT NULL')
            ->order('a.follow_id DESC')
            ->findPage();

        return $data;
    }

    /**
     * 獲取朋友列表資料 - 不分頁
     * @param integer $uid 使用者ID
     * @return array 朋友列表資料
     */
    public function getFriendsData($uid) {
        $data = D()->table('`'.$this->tablePrefix.'user_follow` AS a LEFT JOIN `'.$this->tablePrefix.'user_follow` AS b ON a.uid = b.fid AND b.uid = a.fid')
            ->field('a.fid')
            ->where('a.uid = '.$uid.' AND b.uid IS NOT NULL')
            ->findAll();

        return $data;
    }

    /**
     * 獲取所有關注使用者資料
     * @param integer $uid 使用者ID
     * @return array 所有關注使用者資料
     */
    public function getFollowingsList($uid) {
        $data = $this->field('fid')->where('uid='.$uid)->order('follow_id DESC')->findPage();
        return $data;
    }

    /**
     * 格式化，使用者的關注資料
     * @param integer $uid 使用者ID
     * @param array $fids 使用者ID陣列
     * @param array $follow_data 關注狀態資料
     * @return array 格式化後的使用者關注狀態資料
     */
    private function _formatFollowState($uid, $fids, $follow_data) {
        !is_array($fids) && $fids = explode(',', $fids);
        foreach($fids as $fid) {
            $follow_states[$uid][$fid] = array('following'=>0,'follower'=>0);
        }
        foreach($follow_data as $r_v) {
            if($r_v['uid'] == $uid) {
                $follow_states[$r_v['uid']][$r_v['fid']]['following'] = 1;
            } else if($r_v['fid'] == $uid) {
                $follow_states[$r_v['fid']][$r_v['uid']]['follower'] = 1;
            }
        }

        return $follow_states;
    }

    /**
     * 更新關注數目
     * @param integer $uid 操作使用者ID
     * @param array $fids 被操作使用者ID陣列
     * @param boolean $inc 是否為加資料，默認為true
     * @return void
     */
    private function _updateFollowCount($uid, $fids, $inc = true) {
        !is_array($fids) && $fids = explode(',', $fids);
        $data_model = model('UserData');
        // 添加關注數
        $data_model->setUid($uid)->updateKey('following_count', count($fids), $inc);
        foreach($fids as $f_v) {
            // 添加粉絲數
            $data_model->setUid($f_v)->updateKey('follower_count', 1, $inc);
            $data_model->setUid($f_v)->updateKey('new_folower_count', 1, $inc);
        }
    }

    /*** API使用 ***/
    /**
     * 獲取指定使用者粉絲列表，API使用
     * @param integer $mid 當前登入使用者ID
     * @param integer $uid 指定使用者ID
     * @param integer $since_id 主鍵起始ID，默認為0
     * @param integer $max_id 主鍵最大ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 頁數ID，默認為1
     * @return array 指定使用者的粉絲列表資料
     */
    public function getFollowerListForApi($mid, $uid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1) {
        $uid = intval($uid);
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);
        $where = " fid = '{$uid}'";
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND follow_id > {$since_id}";
            !empty($max_id) && $where .= " AND follow_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $list = $this->where($where)->limit("{$start},{$end}")->order('follow_id DESC')->findAll();
        if(empty($list)) {
            return array();
        } else {
            $r = array();
            foreach($list as $key => $value) {
                $uid = $value['uid'];
                $fid = $value['fid'];
                $r[$key] = model('User')->formatForApi($value, $uid, $mid);
                unset($r[$key]['fid']);
            }
            return $r;
        }
    }

    /**
     * 獲取指定使用者關注列表，API使用
     * @param integer $mid 當前登入使用者ID
     * @param integer $uid 指定使用者ID
     * @param integer $since_id 主鍵起始ID，默認為0
     * @param integer $max_id 主鍵最大ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 頁數ID，默認為1
     * @return array 指定使用者的關注列表資料
     */
    public function getFollowingListForApi($mid, $uid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1) {
        $uid = intval($uid);
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);
        $where = " uid = '{$uid}'";
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND follow_id > {$since_id}";
            !empty($max_id) && $where .= " AND follow_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $list = $this->where($where)->limit("{$start},{$end}")->order('follow_id DESC')->findAll();
        if(empty($list)) {
            return array();
        } else {
            $r = array();
            foreach($list as $key => $value) {
                $uid = $value['fid'];
                $value['uid'] = $uid;
                $r[$key] = model('User')->formatForApi($value, $uid, $mid);
                unset($r[$key]['fid']);
            }
            return $r;
        }
        }

        /**
         * 獲取指定使用者的朋友列表，API專用
         * @param integer $mid 當前登入使用者ID
         * @param integer $uid 指定使用者ID
         * @param integer $since_id 主鍵起始ID，默認為0
         * @param integer $max_id 主鍵最大ID，默認為0
         * @param integer $limit 結果集數目，默認為20
         * @param integer $page 頁數ID，默認為1
         * @return array 指定使用者的朋友列表
         */
        public function getFriendsForApi($mid, $uid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1)
        {
            $uid = intval($uid);
            $since_id = intval($since_id);
            $max_id = intval($max_id);
            $limit = intval($limit);
            $page = intval($page);
            $where = " a.uid = '{$uid}' AND b.uid IS NOT NULL";
            if(!empty($since_id) || !empty($max_id)) {
                !empty($since_id) && $where .= " AND a.follow_id > {$since_id}";
                !empty($max_id) && $where .= " AND a.follow_id > {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $list = D()->table('`'.$this->tablePrefix.'user_follow` AS a LEFT JOIN `'.$this->tablePrefix.'user_follow` AS b ON a.uid = b.fid AND b.uid = a.fid')
            ->field('a.fid, a.follow_id')
            ->where($where)
            ->limit("{$start}, {$end}")
            ->order('a.follow_id DESC')
            ->findAll();

        if(empty($list)) {
            return array();
        } else {
            $r = array();
            foreach($list as $key => $value) {
                $uid = $value['fid'];
                $value['uid'] = $uid;
                $r[$key] = model('User')->formatForApi($value, $uid, $mid);
                unset($r[$key]['fid']);
        }
        return $r;
        }
        }


        // ***************************************************ts2.XX  應用移動新增函數
        function getfollowList($uid){
            $list= $this->field('fid')->where("uid=$uid AND type=0")->findall();
            return $list;
        }
        }
