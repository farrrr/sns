<?php
/**
 * 微吧模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class WeibaModel    extends Model {

    protected $tableName = 'weiba';
    protected $error = '';
    protected $fields = array(
        0 =>'weiba_id',1=>'weiba_name',2=>'uid',3=>'ctime',4=>'logo',5=>'intro',
        6=>'who_can_post',7=>'who_can_reply',8=>'follower_count',9=>'thread_count',10=>'admin_uid',11=>'recommend',12=>'status',
        13=>'api_key',14=>'domain',15=>'province',16=>'city',17=>'area',18=>'reg_ip',
        19=>'is_del',20=>'notify','_autoinc'=>true,'_pk'=>'weiba_id'
    );

    /**
     * 獲取微吧列表，後臺可以根據條件查詢
     * @param integer $limit 結果集數目，默認為20
     * @param array $map 查詢條件
     * @return array 微吧列表資訊
     */
    public function getWeibaList($limit = 20, $map = array()) {
        if(isset($_POST)) {
            //搜索時用到
            $_POST['weiba_id'] && $map['weiba_id']=intval($_POST['weiba_id']);
            $_POST['weiba_name'] && $map['weiba_name']=array('like','%'.$_POST['weiba_name'].'%');
            $_POST['uid'] && $map['uid']=intval($_POST['uid']);
            $_POST['admin_uid'] && $map['admin_uid']=intval($_POST['admin_uid']);
            $_POST['recommend'] && $map['recommend']=$_POST['recommend']==1?1:0;
        }
        $map['is_del'] = 0;
        // 查詢資料
        $list = $this->where($map)->order('follower_count desc,thread_count desc')->findPage($limit);

        // 資料組裝
        foreach($list['data'] as $k => $v) {
            $list['data'][$k]['weiba_name'] = '<a target="_blank" href="'.U('weiba/Index/detail',array('weiba_id'=>$v['weiba_id'])).'">'.$v['weiba_name'].'</a>';
            $list['data'][$k]['logo'] &&  $list['data'][$k]['logo'] = '<img src="'.getImageUrlByAttachId($v['logo']).'" width="50" height="50">';
            $create_uid = model('User')->getUserInfoByUids($v['uid']);
            $list['data'][$k]['uid'] = $create_uid[$v['uid']]['space_link'];
            $list['data'][$k]['ctime'] = friendlyDate($v['ctime']);
            $admin_uid = model('User')->getUserInfoByUids($v['admin_uid']);
            $list['data'][$k]['admin_uid'] = $admin_uid[$v['admin_uid']]['space_link'];
            $list['data'][$k]['follower_count/thread_count'] = $v['follower_count'].'/'.$v['thread_count'];
            $isrecommend = $v['recommend']?'取消推薦':'推薦到首頁';
            $list['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.recommend('.$v['weiba_id'].','.$v['recommend'].');">'.$isrecommend.'</a>|<a href="'.U('weiba/Admin/editWeiba',array('weiba_id'=>$v['weiba_id'],'tabHash'=>'editWeiba')).'">編輯</a>|<a onclick="admin.delWeiba('.$v['weiba_id'].');" href="javascript:void(0)">解散</a>';
        }
        return $list;
    }

    /**
     * 獲取微吧的Hash陣列
     * @param string $k Hash陣列的Key值欄位
     * @param string $v Hash陣列的Value值欄位
     * @return array 使用者組的Hash陣列
     */
    public function getHashWeiba($k = 'weiba_id', $v = 'weiba_name') {
        $list = $this->findAll();
        $r = array();
        foreach($list as $lv) {
            $r[$lv['weiba_id']] = $lv[$v];
        }

        return $r;
    }

    /**
     * 獲取帖子列表，後臺可以根據條件查詢
     * @param integer $limit 結果集數目，默認為20
     * @param array $map 查詢條件
     * @return array 微吧列表資訊
     */
    public function getPostList($limit = 20, $map = array()) {
        if(isset($_POST)) {
            //搜索時用到
            $_POST['post_id'] && $map['post_id']=intval($_POST['post_id']);
            $_POST['title'] && $map['title']=array('like','%'.$_POST['title'].'%');
            $_POST['post_uid'] && $map['post_uid']=intval($_POST['post_uid']);
            $_POST['recommend'] && $map['recommend']=$_POST['recommend']==1?1:0;
            $_POST['digest'] && $map['digest']=$_POST['digest']==1?1:0;
            $_POST['top'] && $map['top']=$_POST['top']==1?1:0;
            $_POST['weiba_id'] && $map['weiba_id']=intval($_POST['weiba_id']);
        }
        // 查詢資料
        $map['weiba_id'] = array('in',getSubByKey(D('weiba')->where('is_del=0')->findAll(),'weiba_id'));
        $list = D('weiba_post')->where($map)->order('last_reply_time desc,post_time desc')->findPage($limit);

        // 資料組裝
        foreach($list['data'] as $k => $v) {
            $list['data'][$k]['title'] = '<a target="_blank" href="'.U('weiba/Index/postDetail',array('post_id'=>$v['post_id'])).'">'.$v['title'].'</a>';
            $author = model('User')->getUserInfoByUids($v['post_uid']);
            $list['data'][$k]['post_uid'] = $author[$v['post_uid']]['space_link'];
            $list['data'][$k]['post_time'] = friendlyDate($v['post_time']);
            $list['data'][$k]['last_reply_time'] = friendlyDate($v['last_reply_time']);
            $list['data'][$k]['read_count/reply_count'] = $v['read_count'].'/'.$v['reply_count'];
            $list['data'][$k]['weiba_id'] = $this->where('weiba_id='.$v['weiba_id'])->getField('weiba_name');
            if($v['is_del'] == 0){
                $isRecommend = $v['recommend']?'取消推薦':'推薦到首頁';
                $isDigest = $v['digest']?'取消精華':'設為精華';
                $isGlobalTop = $v['top']==2?'取消全局置頂':'設為全局置頂';
                $isLocalTop = $v['top']==1?'取消吧內建頂':'設為吧內建頂';
                $list['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.setPost('.$v['post_id'].',1,'.$v['recommend'].');">'.$isRecommend.'</a>|<a href="javascript:void(0)" onclick="admin.setPost('.$v['post_id'].',2,'.$v['digest'].')">'.$isDigest.'</a>|<a href="javascript:void(0)" onclick="admin.setPost('.$v['post_id'].',3,'.$v['top'].',2)">'.$isGlobalTop.'</a>|<a href="javascript:void(0)" onclick="admin.setPost('.$v['post_id'].',3,'.$v['top'].',1)">'.$isLocalTop.'</a>|<a href="'.U('weiba/Admin/editPost',array('post_id'=>$v['post_id'],'tabHash'=>'editPost')).'">編輯</a>|<a href="javascript:void(0)" onclick="admin.doStorey('.$v['post_id'].')">調整回覆樓層</a>|<a href="javascript:void(0)" onclick="admin.delPost('.$v['post_id'].')">刪除</a>';
            }else{
                $list['data'][$k]['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.recoverPost('.$v['post_id'].')">還原</a>|<a href="javascript:void(0)" onclick="admin.deletePost('.$v['post_id'].')">徹底刪除</a>';
            }
        }
        return $list;
    }

    /**
     * 根據微吧ID獲取微吧資訊
     * @param integer $weiba_id 微吧ID
     * @return array 微吧資訊
     */
    public function getWeibaById($weiba_id){
        $weiba = $this->where('weiba_id='.$weiba_id)->find();
        if($weiba['logo']){
            $weiba['pic_url'] = getImageUrlByAttachId($weiba['logo']);
        }

        return $weiba;
    }

    /**
     * 關注微吧
     * @param integer uid 使用者UID
     * @param integer weiba_id 微吧ID
     * @return integer 新添加的資料ID
     */
    public function doFollowWeiba($uid,$weiba_id){
        $data['weiba_id'] = $weiba_id;
        $data['follower_uid'] = $uid;
        if(D('weiba_follow')->where($data)->find()){
            $this->error='您已關注該微吧';
            return false;
        }else{
            $res = D('weiba_follow')->add($data);
            if($res){
                D('weiba')->where('weiba_id='.$weiba_id)->setInc('follower_count');

                //添加積分
                model('Credit')->setUserCredit($uid,'follow_weiba');

                return true;
            }else{
                $this->error='關註失敗';
                return false;
            }
        }
    }

    /**
     * 取消關注微吧
     * @param integer uid 使用者UID
     * @param integer weiba_id 微吧ID
     * @return integer 新添加的資料ID
     */
    public function unFollowWeiba($uid,$weiba_id){
        $data['weiba_id'] = $weiba_id;
        $data['follower_uid'] = $uid;
        if(D('weiba_follow')->where($data)->find()){
            $res = D('weiba_follow')->where($data)->delete();
            if($res){
                D('weiba')->where('weiba_id='.$weiba_id)->setDec('follower_count');
                D('weiba_apply')->where($data)->delete();

                //添加積分
                model('Credit')->setUserCredit($uid,'unfollow_weiba');

                return true;
            }else{
                $this->error='關註失敗';
                return false;
            }
        }else{
            $this->error='您尚未關注該微吧';
            return false;
        }
    }

    /**
     * 判斷是否關注某個微吧
     * @param integer uid 使用者UID
     * @param integer weiba_id 微吧ID
     * @return boolean 是否已關注
     */
    public function getFollowStateByWeibaid($uid, $weiba_id){
        if(empty($weiba_id)) {
            return 0;
        }
        $follow_data = D('weiba_follow')->where(" ( follower_uid = '{$uid}' AND weiba_id = '{$weiba_id}' ) ")->find();
        if($follow_data){
            return 1;
        }else{
            return 0;
        }
    }

    /**
     * 批量獲取微吧關注狀態
     * @param integer uid 使用者UID
     * @param array weiba_ids 微吧ID
     * @return [type] [description]
     */
    public function getFollowStateByWeibaids($uid, $weiba_ids){
        $_weibaids = is_array($weiba_ids) ? implode(',', $weiba_ids) : $weiba_ids;
        if(empty($_weibaids)) {
            return array();
        }
        $follow_data = D('weiba_follow')->where(" ( follower_uid = '{$uid}' AND weiba_id IN({$_weibaids}) ) ")->findAll();

        $follow_states = $this->_formatFollowState($uid, $weiba_ids, $follow_data);
        return $follow_states[$uid];
    }

    /**
     * 格式化，使用者的關注資料
     * @param integer $uid 使用者ID
     * @param array $fids 使用者ID陣列
     * @param array $follow_data 關注狀態資料
     * @return array 格式化後的使用者關注狀態資料
     */
    private function _formatFollowState($uid, $weiba_ids, $follow_data) {
        !is_array($weiba_ids) && $fids = explode(',', $weiba_ids);
        foreach($weiba_ids as $weiba_ids) {
            $follow_states[$uid][$weiba_ids] = array('following'=>0);
        }
        foreach($follow_data as $r_v) {
            if($r_v['follower_uid'] == $uid) {
                $follow_states[$r_v['follower_uid']][$r_v['weiba_id']]['following'] = 1;
            }
        }

        return $follow_states;
    }

    /**
     * 獲取微吧列表
     * @param integer limit 每頁顯示條數
     * @param integer page 第幾頁
     * @return array 微吧列表
     */
    public function get_weibas_forapi($since_id=0,$max_id=0,$limit=20, $page=1, $uid){
        $limit = intval($limit);
        $page = intval($page);
        $where = "is_del=0";
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND weiba_id > {$since_id}";
            !empty($max_id) && $where .= " AND weiba_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $weibaList = $this->where($where)->limit("{$start},{$end}")->order('weiba_id desc')->findAll();
        foreach($weibaList as $k=>$v){
            if($v['logo']){
                $weibaList[$k]['logo_url'] = getImageUrlByAttachId($v['logo']);
            }
            if(D('weiba_follow')->where('follower_uid='.$uid.' AND weiba_id='.$v['weiba_id'])->find()){
                $weibaList[$k]['followstate'] = 1;
            }else{
                $weibaList[$k]['followstate'] = 0;
            }
        }
        return $weibaList;
    }

    /**
     * 獲取帖子列表
     * @param integer limit 每頁顯示條數
     * @param integer page 第幾頁
     * @param integer weiba_id 所屬微吧ID(可選)
     * @return array 帖子列表
     */
    public function get_posts_forapi($limit=20, $page=1, $weiba_id=null){
        $limit = intval($limit);
        $page = intval($page);
        $start = ($page - 1) * $limit;
        $end = $limit;
        if($weiba_id){
            $map['weiba_id'] = $weiba_id;
        }
        $map['is_del'] = 0;
        $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->order('top desc,post_time desc')->findAll();
        foreach($postList as $k=>$v){
            $postList[$k]['author_info'] = model('User')->getUserInfo($v['post_uid']);
            if(D('weiba_favorite')->where('post_id='.$v['post_id'].' AND uid='.$GLOBALS['ts']['mid'])->find()){
                $postList[$k]['favorite'] = 1;
            }else{
                $postList[$k]['favorite'] = 0;
            }
        }
        return $postList;
    }

    /**
     * 獲取我的帖子
     * @param integer limit 每頁顯示條數
     * @param integer page 第幾頁
     * @param uid 使用者UID
     * @param varchar type 類型
     * @return array 帖子列表
     */
    public function myWeibaForApi($limit,$page,$uid,$type){
        $map['is_del'] = 0;
        $limit = intval($limit);
        $page = intval($page);
        $start = ($page - 1) * $limit;
        $end = $limit;
        switch ($type) {
        case 'myPost':
            $map['post_uid'] = $uid;
            $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->order('post_time desc')->findAll();
            break;
        case 'myReply':
            $myreply = D('weiba_reply')->where('uid='.$uid)->order('ctime desc')->field('post_id')->findAll();
            $map['post_id'] = array('in',array_unique(getSubByKey($myreply, 'post_id')));
            $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->order('last_reply_time desc')->findAll();
            break;
        case 'myFollow':
            $myFollow = D('weiba_follow')->where('follower_uid='.$uid)->findAll();
            $map['weiba_id'] = array('in',getSubByKey($myFollow, 'weiba_id'));
            $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->order('top desc,post_time desc')->findAll();
            break;
        case 'myFavorite':
            $myFavorite = D('weiba_favorite')->where('uid='.$uid)->order('id desc')->findAll();
            $map['post_id'] = array('in',getSubByKey($myFavorite,'post_id'));
            $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->findAll();
        }
        foreach($postList as $k=>$v){
            $postList[$k]['author_info'] = model('User')->getUserInfo($v['post_uid']);
            if(D('weiba_favorite')->where('post_id='.$v['post_id'].' AND uid='.$uid)->find()){
                $postList[$k]['favorite'] = 1;
            }else{
                $postList[$k]['favorite'] = 0;
            }
        }
        return $postList;
    }

    /**
     * 搜索微吧
     * @param varchar keyword 搜索關鍵字
     * @param integer limit 每頁顯示條數
     * @param integer page 第幾頁
     * @param integer uid 使用者UID
     * @return array 微吧列表
     */
    public function searchWeibaForApi($keyword,$limit,$page,$uid){
        $limit = intval($limit);
        $page = intval($page);
        $start = ($page - 1) * $limit;
        $end = $limit;
        $map['is_del'] = 0;
        $where['weiba_name'] = array('like','%'.$keyword.'%');
        $where['intro'] = array('like','%'.$keyword.'%');
        $where['_logic'] = 'or';
        $map['_complex'] = $where;
        $weibaList = D('weiba')->where($map)->limit("{$start},{$end}")->order('follower_count desc,thread_count desc')->findAll();
        if($weibaList){
            foreach($weibaList as $k=>$v){
                if($v['logo']){
                    $weibaList[$k]['logo_url'] = getImageUrlByAttachId($v['logo']);
}
if(D('weiba_follow')->where('follower_uid='.$uid.' AND weiba_id='.$v['weiba_id'])->find()){
    $weibaList[$k]['followstate'] = 1;
}else{
    $weibaList[$k]['followstate'] = 0;
}
}
return $weibaList;
}else{
    return array();
}
}

/**
 * 搜索帖子
 * @param varchar keyword 搜索關鍵字
 * @param integer limit 每頁顯示條數
 * @param integer page 第幾頁
 * @return array 帖子列表
 */
public function searchPostForApi($keyword,$limit,$page){
    $limit = intval($limit);
    $page = intval($page);
    $start = ($page - 1) * $limit;
    $end = $limit;
    $map['is_del'] = 0;
    $where['title'] = array('like','%'.$keyword.'%');
    $where['content'] = array('like','%'.$keyword.'%');
    $where['_logic'] = 'or';
    $map['_complex'] = $where;
    $postList = D('weiba_post')->where($map)->limit("{$start},{$end}")->order('post_time desc')->findAll();
    if($postList){
        foreach($postList as $k=>$v){
            $postList[$k]['weiba'] = D('weiba')->where('weiba_id='.$v['weiba_id'])->getField('weiba_name');
            foreach($postList as $k=>$v){
                $postList[$k]['author_info'] = model('User')->getUserInfo($v['post_uid']);
}
}
return $postList;
}else{
    return array();
}
}

}
