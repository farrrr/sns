<?php
/**
 * 使用者關注分組模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FollowGroupModel extends Model {
    const CACHE_PREFIX = "follow";

    protected $tableName = 'user_follow_group';
    protected $fields = array(0=>'follow_group_id',1=>'title',2=>'uid',3=>'ctime');

    /**
     * 獲取指定使用者所有的關組分組
     * @param integer $uid 使用者ID
     * @return array 指定使用者所有的關組分組
     */
    public function getGroupList($uid) {
        // if(!is_numeric($uid)) throw new ThinkException(L("arg_number_only"));
        if(false == ($follow_group_list = S(self::CACHE_PREFIX."list_".$uid))) {
            $follow_group_list = $this->where("uid={$uid}")->order('ctime ASC')->findAll();
            if(empty($follow_group_list)) {
                S(self::CACHE_PREFIX."list_".$uid,array());
            } else {
                S(self::CACHE_PREFIX."list_".$uid,$follow_group_list);
            }
        }
        return $follow_group_list;
    }
    /**
     * 獲取指定使用者指定關注使用者的所在分組資訊
     * @param integer $uid 使用者ID
     * @param integer $fid 關注使用者ID
     * @return array 關注使用者所在分組的資訊
     */
    public function getGroupStatus($uid, $fid) {
        $map['uid'] = intval($uid);
        $map['fid'] = intval($fid);
        $follow_id = D('UserFollow')->getField('follow_id', $map);
        if($follow_id){
            $follow_group_status = $this->field('link.follow_group_id AS gid,group.title')
                ->table("{$this->tablePrefix}user_follow_group_link AS link LEFT JOIN {$this->tablePrefix}{$this->tableName} AS `group` ON link.follow_group_id=group.follow_group_id AND link.uid=group.uid")
                ->where("link.follow_id={$follow_id} AND group.uid={$uid}")
                ->order('group.follow_group_id ASC')
                ->findAll();
            if(empty($follow_group_status)) {
                $follow_group_status[0] = array('gid'=>0,'title'=> L('PUBLIC_UNGROUP'));            // 未分組
            }
            return $follow_group_status;
        } else {
            return false;
        }
    }
    /**
     * 獲取指定使用者與多個指定關注使用者的所在分組資訊
     * @param integer $uid 使用者ID
     * @param string $fids 關注使用者ID，多個用“,”分割
     * @return array 指定使用者與多個指定關注使用者的所在分組資訊
     */
    public function getGroupStatusByFids($uid, $fids) {
        $follow_group_status = $this->field('link.follow_group_id AS gid,link.uid,link.fid,group.title')
            ->table("{$this->tablePrefix}user_follow_group_link AS link LEFT JOIN {$this->tablePrefix}{$this->tableName} AS `group` ON link.follow_group_id=group.follow_group_id AND link.uid=group.uid")
            ->where("link.uid={$uid} AND link.fid IN (" . implode(',', $fids) . ") AND group.uid={$uid}")
            ->order('group.follow_group_id ASC')
            ->findAll();
        $_follow_group_status = array();
        foreach($follow_group_status as $f_g_s_k => $f_g_s_v) {
            $_follow_group_status[$f_g_s_v['uid']][$f_g_s_v['fid']][] = $f_g_s_v;
        }
        foreach ($fids as $fid) {
            empty($_follow_group_status[$uid][$fid]) && $_follow_group_status[$uid][$fid][] = array('gid'=>0,'title'=> L('PUBLIC_UNGROUP'));            // 未分組
        }
        return $_follow_group_status[$uid];
    }
    /**
     * 設定好友的分組狀態
     * @param integer $uid 操作使用者ID
     * @param integer $fid 被操作使用者ID
     * @param integer $gid 關注分組ID
     * @param string $action 操作狀態類型，空、add、delete
     */
    public function setGroupStatus($uid, $fid, $gid, $action = null){
        S(self::CACHE_PREFIX."list_".$uid, null);
        S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}", null);
        $map = array('uid'=>intval($uid),'fid'=>intval($fid));
        $follow_id = D('UserFollow')->getField('follow_id',$map);
        $gid = $this->getField('follow_group_id', "uid={$map['uid']} AND follow_group_id={$gid}");
        if($follow_id && $gid) {
            $linkModel = D('UserFollowGroupLink');
            $data = array('follow_group_id'=>$gid,'follow_id'=>$follow_id,'fid'=>$map['fid'],'uid'=>$map['uid']);
            if($action == null) {
                $linkModel->where($data)->delete() || $linkModel->add($data);
            } else if($action == 'add') {
                $linkModel->where($data)->find() || $linkModel->add($data);
            } else if($action == 'delete') {
                $linkModel->where($data)->delete();
            }
        }
    }

    /**
     * 清除關注分組快取操作
     * @param integer $uid 使用者ID
     * @param integer $gid 關注分組ID
     * @return void
     */
    public function cleanCache($uid, $gid = '') {
        S(self::CACHE_PREFIX."list_".$uid, null);
        if(!empty($gid)) {
            S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}", null);
        }
    }
    /**
     * 添加，修改制定使用者的分組
     * @param integer $uid 使用者ID
     * @param string $title 分組名稱
     * @param integer $gid 關注分組ID
     * @return integer 是否添加或修改成功
     */
    public function setGroup($uid, $title, $gid = null) {
        S(self::CACHE_PREFIX."list_".$uid, null);
        S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}", null);
        $uid = intval($uid);
        $title = t($title);
        if($title === '') {
            return 0;
        }
        // 驗證分組是否存在
        $map = array('uid'=>$uid,'title'=>$title);
        $_gid = $this->getField('follow_group_id', $map);
        if(!$_gid) {
            if($gid == NULL) {
                $data = array('uid'=>$uid,'title'=>$title,'ctime'=>time());
                $gid = $this->add($data);
                return $gid;
            } else {
                $gid = intval($gid);
                if(!$gid) {
                    return 0;
                }
                $data = array('follow_group_id'=>$gid,'uid'=>$uid,'title'=>$title);
                $res = $this->save($data);
                return 1;
            }
        } else if($_gid == $gid) {
            return 1;
        } else {
            return 0;
        }
    }
    /**
     * 刪除指定使用者的指定關注分組
     * @param integer $uid 使用者ID
     * @param integer $gid 分組ID
     * @return integer 是否刪除成功
     */
    public function deleteGroup($uid, $gid) {
        S(self::CACHE_PREFIX."list_".$uid, null);
        S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}", null);
        $uid = intval($uid);
        $gid = intval($gid);
        $res = $this->where("uid={$uid} AND follow_group_id={$gid}")->delete();
        if($res) {
            // 清除相應分組資訊
            D('UserFollowGroupLink')->where("uid={$uid} AND follow_group_id={$gid}")->delete();
            return 1;
        } else {
            return 0;
        }
    }
    /**
     * 獲取指定使用者指定分組下的關注使用者ID
     * @param integer $uid 使用者ID
     * @param integer $gid 關注分組ID
     * @return array 指定使用者指定分組下的關注使用者ID
     */
    public function getUsersByGroup($uid, $gid) {
        $uid = intval($uid);
        $gid = intval($gid);
        if(($_fid = S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}")) == false) {
            $follow_group_id_sql = ($gid == 0) ? ' AND link.follow_group_id IS NULL' : " AND link.follow_group_id={$gid}";
            $fid = $this->field('follow.fid')
                ->table("{$this->tablePrefix}user_follow AS `follow` LEFT JOIN {$this->tablePrefix}user_follow_group_link AS link ON follow.follow_id=link.follow_id AND follow.uid=link.uid")
                ->where("follow.uid={$uid}".$follow_group_id_sql)
                ->findAll();
            foreach($fid as $v) {
                $_fid[] = $v['fid'];
        }
        S(self::CACHE_PREFIX."usergroup_{$uid}_{$gid}", $_fid);
        }
        return $_fid;
        }
        /**
         * 獲取指定使用者指定分組下的關注使用者ID - 分頁型
         * @param integer $uid 使用者ID
         * @param integer $gid 關注分組ID
         * @return array 指定使用者指定分組下的關注使用者ID
         */
        public function getUsersByGroupPage($uid, $gid) {
            $uid = intval($uid);
            $gid = intval($gid);
            // 全部使用者分組
            $follow_group_id_sql = ($gid == 0) ? ' AND link.follow_group_id IS NULL' : " AND link.follow_group_id={$gid}";
            // 獲取使用者ID
            $data = $this->field('follow.fid')
                ->table("{$this->tablePrefix}user_follow AS `follow` LEFT JOIN {$this->tablePrefix}user_follow_group_link AS link ON follow.follow_id=link.follow_id AND follow.uid=link.uid")
                ->where("follow.uid={$uid}".$follow_group_id_sql)
                ->order('follow.follow_id DESC')
                ->findPage();
            return $data;
        }
        /**
         * 獲取指定使用者的未分組使用者ID - 分頁型
         * @param integer $uid 使用者ID
         * @return array 指定使用者的未分組使用者ID
         */
        public function getDefaultGroupByPage($uid) {
            $uid = intval($uid);
            $data = $this->table('`'.$this->tablePrefix.'user_follow` AS a LEFT JOIN `'.$this->tablePrefix.'user_follow_group_link` AS b ON a.fid = b.fid')
                ->field('a.fid')
                ->where('a.uid = '.$uid.' AND b.fid IS NULL')
                ->order('a.follow_id DESC')
                ->findPage();
            return $data;
        }
        /**
         * 獲取指定使用者的未分組使用者ID - 所有
         * @param integer $uid 使用者ID
         * @return array 指定使用者的未分組使用者ID
         */
        public function getDefaultGroupByAll($uid) {
            $uid = intval($uid);
            $data = $this->table('`'.$this->tablePrefix.'user_follow` AS a LEFT JOIN `'.$this->tablePrefix.'user_follow_group_link` AS b ON a.fid = b.fid')
                ->field('a.fid')
                ->where('a.uid = '.$uid.' AND b.fid IS NULL')
                ->findAll();

            return $data;
        }
        }
