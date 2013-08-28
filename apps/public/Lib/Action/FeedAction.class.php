<?php
/**
 * 微博控制器
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class FeedAction extends Action {

    /**
     * 獲取表情操作
     * @return json 表情相關的JSON資料
     */
    public function getSmile() {
        exit(json_encode(model('Expression')->getAllExpression()));
    }
    /**
     * 返回好友分組列表
     */
    public function getFriendGroup(){
        $usergroupList = model('FollowGroup')->getGroupList($this->mid);
        $grouplist = array();
        foreach ( $usergroupList as $g ){
            $group['gid'] = $g['follow_group_id'];
            $group['title'] = $g['title'];
            $grouplist[] = $group;
        }
        //      //相互關注
        //      $mutualusers = model('Follow')->getFriendsData($this->mid);
        //未分組
        $nogroupusers = model('FollowGroup')->getDefaultGroupByAll($this->mid);
        //其他分組
        //      $groupusers = array();
        //      if( $grouplist ){
        //          foreach ( $grouplist as $v ){
        //              $groupinfo = model('FollowGroup')->getUsersByGroup( $this->mid , $v['gid'] );
        //              $groupusers['group'.$v['gid']] = $groupinfo;
        //          }
        //      }
        //      $groupusers['group-1'] = getSubByKey( $mutualusers , 'fid');
        $groupusers['group-2'] = getSubByKey( $nogroupusers , 'fid' );
        $groups = array(array('gid'=>-2, 'title'=>'未分組'));
        //關注列表
        $grouplist && $groups = array_merge( $groups , $grouplist);
        $users = array();
        foreach ($groupusers as &$gu){
            foreach ( $gu as $k=>$u){
                $gu[$k] = model('User')->getUserInfoForSearch( $u , 'uid,uname');
            }
        }
        $this->assign('groups' , $groups);
        $this->assign('groupusers' , $groupusers);
        $this->display();
    }
    public function changGroup(){
        $gid = intval( $_POST['gid'] );
        $groupinfo = model('FollowGroup')->getUsersByGroup( $this->mid , $gid );

        $groupuser = array();
        foreach ($groupinfo as $gu){
            $groupuser[] = model('User')->getUserInfoForSearch( $gu , 'uid,uname');
        }
        $res = '<ul id="group'.$gid.'">';
        foreach ( $groupuser as $u ){
            $res .= '<li onclick=\'core.at.insertUser("'.$u['uname'].'")\'><a href="javascript:void(0);"><img alt="'.$u['uname'].'" src="'.$u['avatar_small'].'">'.$u['uname'].'</a></li>';
        }
        $res .= '</ul>';
        exit($res);
    }
    /**
     * 釋出微博操作，用於AJAX
     * @return json 釋出微博後的結果資訊JSON資料
     */
    public function PostFeed()
    {
        // 返回資料格式
        $return = array('status'=>1, 'data'=>'');
        // 使用者發送內容
        $d['content'] = isset($_POST['content']) ? filter_keyword(h($_POST['content'])) : '';
        // 原始資料內容
        $d['body'] = filter_keyword($_POST['body']);
        // 安全過濾
        foreach($_POST as $key => $val) {
            $_POST[$key] = t($_POST[$key]);
        }
        $d['source_url'] = urldecode($_POST['source_url']);  //應用分享到微博，原資源連結
        // 濾掉話題兩端的空白
        $d['body'] = preg_replace("/#[\s]*([^#^\s][^#]*[^#^\s])[\s]*#/is",'#'.trim("\${1}").'#',$d['body']);
        // 附件資訊
        $d['attach_id'] = trim(t($_POST['attach_id']), "|");
        if ( !empty($d['attach_id']) ){
            $d['attach_id'] = explode('|', $d['attach_id']);
            array_map( 'intval' , $d['attach_id'] );
        }
        // 發送微博的類型
        $type = t($_POST['type']);
        // 所屬應用名稱
        $app = isset($_POST['app_name']) ? t($_POST['app_name']) : APP_NAME;            // 當前動態產生所屬的應用
        if(!$data = model('Feed')->put($this->uid, $app, $type, $d)) {
            $return = array('status'=>0,'data'=>model('Feed')->getError());
            exit(json_encode($return));
        }
        // 釋出郵件之後添加積分
        model ( 'Credit' )->setUserCredit ( $this->uid, 'add_weibo' );
        // 微博來源設定
        $data ['from'] = getFromClient ( $data ['from'], $data ['app'] );
        $this->assign ( $data );
        // 微博配置
        $weiboSet = model ( 'Xdata' )->get ( 'admin_Config:feed' );
        $this->assign ( 'weibo_premission', $weiboSet ['weibo_premission'] );
        $return ['data'] = $this->fetch ();
        // 微博ID
        $return ['feedId'] = $data ['feed_id'];
        $return ['is_audit'] = $data ['is_audit'];
        // 添加話題
        model ( 'FeedTopic' )->addTopic ( html_entity_decode ( $d ['body'], ENT_QUOTES ), $data ['feed_id'], $type );
        // 更新使用者最後發表的微博
        $last ['last_feed_id'] = $data ['feed_id'];
        $last ['last_post_time'] = $_SERVER ['REQUEST_TIME'];
        model ( 'User' )->where ( 'uid=' . $this->uid )->save ( $last );


        $isOpenChannel = model ( 'App' )->isAppNameOpen ( 'channel' );
        if (! $isOpenChannel) {
            exit ( json_encode ( $return ) );
        }
        // 添加微博到投稿資料中
        $channelId = t ( $_POST ['channel_id'] );

        // 繫結使用者
        $bindUserChannel = D ( 'Channel', 'channel' )->getCategoryByUserBind ( $this->mid );
        if (! empty ( $bindUserChannel )) {
            $channelId = array_merge ( $bindUserChannel, explode ( ',', $channelId ) );
            $channelId = array_filter ( $channelId );
            $channelId = array_unique ( $channelId );
            $channelId = implode ( ',', $channelId );
        }
        // 繫結話題
        $content = html_entity_decode ( $d ['body'], ENT_QUOTES );
        $content = str_replace ( "＃", "#", $content );
        preg_match_all ( "/#([^#]*[^#^\s][^#]*)#/is", $content, $topics );
        $topics = array_unique ( $topics [1] );
        foreach ( $topics as &$topic ) {
            $topic = trim ( preg_replace ( "/#/", '', t ( $topic ) ) );
        }
        $bindTopicChannel = D ( 'Channel', 'channel' )->getCategoryByTopicBind ( $topics );
        if (! empty ( $bindTopicChannel )) {
            $channelId = array_merge ( $bindTopicChannel, explode ( ',', $channelId ) );
            $channelId = array_filter ( $channelId );
            $channelId = array_unique ( $channelId );
            $channelId = implode ( ',', $channelId );
        }
        if (! empty ( $channelId )) {
            D ( 'Channel', 'channel' )->setChannel ( $data ['feed_id'], $channelId, false );
        }

        exit(json_encode($return));
    }

    /**
     * 分享/轉發微博操作，需要傳入POST的值
     * @return json 分享/轉發微博後的結果資訊JSON資料
     */
    public function shareFeed()
    {
        // 獲取傳入的值
        $post = $_POST;
        // 安全過濾
        foreach($post as $key => $val) {
            $post[$key] = t($post[$key]);
        }
        // 過濾內容值
        $post['body'] = filter_keyword($post['body']);

        // 判斷資源是否刪除
        if(empty($post['curid'])) {
            $map['feed_id'] = $post['sid'];
        } else {
            $map['feed_id'] = $post['curid'];
        }
        $map['is_del'] = 0;
        $isExist = model('Feed')->where($map)->count();
        if($isExist == 0) {
            $return['status'] = 0;
            $return['data'] = '內容已被刪除，轉發失敗';
            exit(json_encode($return));
        }

        // 進行分享操作
        $return = model('Share')->shareFeed($post, 'share');
        if($return['status'] == 1) {
            $app_name = $post['app_name'];

            // 添加積分
            if($app_name == 'public'){
                model('Credit')->setUserCredit($this->uid,'forward_weibo');
                //微博被轉發
                $suid =  model('Feed')->where($map)->getField('uid');
                model('Credit')->setUserCredit($suid,'forwarded_weibo');
            }
            if($app_name == 'weiba'){
                model('Credit')->setUserCredit($this->uid,'forward_topic');
                //微博被轉發
                $suid =  D('Feed')->where('feed_id='.$map['feed_id'])->getField('uid');
                model('Credit')->setUserCredit($suid,'forwarded_topic');
            }

            $this->assign($return['data']);
            // 微博配置
            $weiboSet = model('Xdata')->get('admin_Config:feed');
            $this->assign('weibo_premission', $weiboSet['weibo_premission']);
            $return['data'] =  $this->fetch('PostFeed');
        }
        exit(json_encode($return));
    }

    /**
     * 刪除微博操作，用於AJAX
     * @return json 刪除微博後的結果資訊JSON資料
     */
    public function removeFeed() {
        $return = array('status'=>0,'data'=>L('PUBLIC_DELETE_FAIL'));           // 刪除失敗
        $feed_id = intval($_POST['feed_id']);
        $feed = model('Feed')->getFeedInfo($feed_id);
        // 不存在時
        if(!$feed){
            exit(json_encode($return));
}
// 非作者時
if($feed['uid']!=$this->mid){
    // 沒有管理許可權不可以刪除
    if(!CheckPermission('core_admin','feed_del')){
        exit(json_encode($return));
}
// 是作者時
}else{
    // 沒有前臺許可權不可以刪除
    if(!CheckPermission('core_normal','feed_del')){
        exit(json_encode($return));
}
}
// 執行刪除操作
$return = model('Feed')->doEditFeed($feed_id, 'delFeed', '',$this->mid);
// 刪除失敗或刪除成功的訊息
$return['data'] = ($return['status'] == 0) ? L('PUBLIC_DELETE_FAIL') : L('PUBLIC_DELETE_SUCCESS');
// 批註：下面的程式碼應該挪到FeedModel中
// 刪除話題相關資訊
$return['status'] == 1 && model('FeedTopic')->deleteWeiboJoinTopic($feed_id);
// 刪除頻道關聯資訊
D('Channel', 'channel')->deleteChannelLink($feed_id);
// 刪除@資訊
model('Atme')->setAppName('Public')->setAppTable('feed')->deleteAtme(null, $feed_id, null);
//刪除話題資訊
$topics = D('feed_topic_link')->where('feed_id='.$feed_id)->field('topic_id')->findAll();
D('feed_topic_link')->where('feed_id='.$feed)->delete();
$tpmap['topic_id'] = array( 'in' , getSubByKey( $topics , 'topic_id' ) );
model('FeedTopic')->where($tpmap)->setDec('count');
exit(json_encode($return));
}

function addDigg(){
    $feed_id = intval($_POST['feed_id']);
    $res = model('FeedDigg')->addDigg($feed_id, $this->mid);
    if($res){
        echo 1;
}else{
    echo 0;
}

}
}
