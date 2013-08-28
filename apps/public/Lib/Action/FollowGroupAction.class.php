<?php
/**
 * 關注分組控制器
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FollowGroupAction extends Action {

    /**
     * 分組選擇資料載入操作
     * @param string $type 彈窗類型，box、list
     * @return [type]       [description]
     */
    public function selector($type = 'box') {
        $fid = intval($_REQUEST['fid']);
        isset($_REQUEST['isrefresh'])&&$this->assign('isrefresh',intval($_REQUEST['isrefresh']));

        $followGroupDao = D('FollowGroup');
        $group_list = $followGroupDao->getGroupList($this->mid);
        $f_group_status = $followGroupDao->getGroupStatus($this->mid, $fid);

        if($type == 'list') {
            // TODO:未完成？
            //foreach($group_list as &$v){
            //   $v['title'] = (strlen($v['title'])+mb_strlen($v['title'],'UTF8'))/2>6?getShort($v['title'],3):$v['title'];
            //}
        }

        $this->assign('fuserInfo', model('User')->getUserInfo($fid));
        $this->assign('fid', $fid);
        $this->assign('group_list', $group_list);
        $this->assign('f_group_status', $f_group_status);
    }

    /**
     * 分組選擇頁面，下拉式
     */
    public function selectorList() {
        $this->selector('list');
        $this->display();
    }

    /**
     * 分組選擇頁面，彈窗式
     */
    public function selectorBox() {
        $this->selector();
        $this->display();
    }

    /**
     * 設定指定好友的關注分組狀態
     * @return json 返回操作後的JSON資訊資料
     */
    public function setFollowGroup() {
        $gid = intval($_REQUEST['gid']);
        $fid = intval($_REQUEST['fid']);
        $add = t($_REQUEST['add']);
        exit(json_encode($this->_setFollowGroup($gid, $fid, $add)));
    }

    /**
     * 設定指定好友的關注分組狀態 - 多個分組
     * @return json 返回操作後的JSON資訊資料
     */
    public function setFollowGroups()
    {
        $gids = t($_REQUEST['gids']);
        $fid = intval($_REQUEST['fid']);
        $add = t($_REQUEST['add']);
        if(!$add){
            D('UserFollowGroupLink')->where('uid='.$this->mid.' and fid='.$fid)->delete();
        }
        if(empty($gids) || empty($fid)) {
            $res['status'] = 0;
            $res['info'] = '儲存失敗';
        } else {
            $gids = explode(',', $gids);
            foreach($gids as $gid) {
                $gid = intval( $gid );
                $this->_setFollowGroup($gid, $fid, $add);
            }
            $res['status'] = 1;
            $res['info'] = '儲存成功';
        }
        exit(json_encode($res));
    }

    /**
     * 設定指定使用者的分組
     * @param integer $gid 分組ID
     * @param integer $fid 使用者ID
     * @param string $action 操作狀態類型，空、add、delete
     */
    private function _setFollowGroup($gid, $fid, $add) {
        $followGroupDao = D('FollowGroup');
        $followGroupDao->setGroupStatus($this->mid, $fid, $gid, $add);
        $follow_group_status = $followGroupDao->getGroupStatus($this->mid, $fid);
        foreach($follow_group_status as $k => $v) {
            $v['gid'] != 0 && $v['title'] = (strlen($v['title']) + mb_strlen($v['title'], 'UTF8')) / 2 > 4 ? getShort($v['title'], 2) : $v['title'];
            $_follow_group_status .= $v['title'].',';
            if(!empty($follow_group_status[$k + 1]) && (strlen($_follow_group_status) + mb_strlen($_follow_group_status, 'UTF8')) / 2 >= 6) {
                $_follow_group_status .= '...,';
                break;
            }
        }
        $_follow_group_status = substr($_follow_group_status, 0, -1);
        S("weibo_followlist_".$this->mid, null);
        $result['title'] = $_follow_group_status;
        $title = getSubByKey($follow_group_status, 'title');       // 用於存儲原始資料
        $result['oldTitle'] = implode(',', $title);

        return $result;
    }

    /**
     * 添加關注分組操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function saveGroup() {
        $follow_group_id = intval($_REQUEST['gid']);
        if(!empty($follow_group_id)) {
            $save['title'] = t($_REQUEST['title']);
            if($save['title'] === '') {
                $this->ajaxReturn('', L('PUBLIC_FROUPNAME_NOEMPTY'), 0);            // 分組名稱不能為空
            }
            // 判斷使用者分組名稱是否存在
            $group_list = model('FollowGroup')->getGroupList($this->mid);
            foreach($group_list as $v) {
                if($v['title'] === $save['title']) {
                    $this->ajaxReturn('', L('PUBLIC_SAVE_GROUP_FAIL'), 0);          // 儲存分組失敗
                }
            }

            if(D('')->table(C('DB_PREFIX').'user_follow_group')->where("follow_group_id={$follow_group_id}")->save($save)) {
                // 清理快取
                model('FollowGroup')->cleanCache($GLOBALS['ts']['mid'], $follow_group_id);
                $this->ajaxReturn('', L('PUBLIC_SAVE_GROUP_SUCCESS'), 1);           // 儲存分組成功
            }
        } else {
            $this->ajaxReturn('', L('PUBLIC_SAVE_GROUP_FAIL'), 0);                  // 儲存分組失敗
        }
    }

    /**
     * 設定關注分組Tab頁面
     */
    public function setGroupTab(){
        if(is_numeric($_REQUEST['gid'])) {
            $gid = intval($_REQUEST['gid']);
            $title = D('FollowGroup')->getField('title', "follow_group_id={$gid}");
            $this->assign('gid', $gid);
            $this->assign('title', $title);
        }

        $this->display();
    }

    /**
     * 儲存使用者備註操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function saveRemark() {
        $r = array('status'=>0,'data'=>L('PUBLIC_REMARK_ADD_FAIL'));            // 備註添加失敗
        // 設定備註
        if(!empty($_POST['fid'])) {
            $map['uid'] = $GLOBALS['ts']['mid'];
            $map['fid'] = intval($_POST['fid']);
            $save['remark'] = t($_POST['remark']);
            // 默認全部編輯正確
            D('')->table(C('DB_PREFIX').'user_follow')->where($map)->save($save);
            $r = array('status'=>1,'data'=>$save['remark']);
        }
        exit(json_encode($r));
    }

    /**
     * 設定使用者關注分組、修改關注分組操作
     */
    public function setGroup() {
        $title = trim(t($_REQUEST['title']));
        if($title === '') {
            $this->error(L('PUBLIC_FROUPNAME_NOEMPTY'));            // 分組名稱不能為空
}
if(!$_REQUEST['gid']) {
    $res = D('FollowGroup')->setGroup($this->mid, $title);
    $gid = $res;
} else {
    $gid = intval($_REQUEST['gid']);
    $res = D('FollowGroup')->setGroup($this->mid, $title, $gid);
}

if(!empty($_REQUEST['fid']) && !empty($gid)) {
    $fid = intval($_REQUEST['fid']);
    $this->_setFollowGroup($gid, $fid, 'add');
}
S("weibo_followlist_".$this->mid, null);

if($res) {
    $this->success($res);
} else {
    $error = !$_REQUEST['gid'] ? L('PUBLIC_USER_GROUP_EXIST'):L('PUBLIC_OPERATE_GROUP_FAIL');           // 您已經創建過這個分組了，分組操作失敗
    $this->error($error);
}
}

/**
 * 刪除指定使用者的指定關注分組
 * @return json 是否刪除成功
 */
public function deleteGroup(){
    $gid = intval($_REQUEST['gid']);
    if(empty($gid)) {
        $msg['status'] = 0;
        $msg['info'] = '刪除失敗';
        exit(json_encode($msg));
}
$res = D('FollowGroup')->deleteGroup($this->mid, $gid);
if($res) {
    $msg['status'] = 1;
    $msg['info'] = '刪除成功';
    exit(json_encode($msg));
} else {
    $msg['status'] = 0;
    $msg['info'] = '刪除失敗';
    exit(json_encode($msg));
}
}
}
