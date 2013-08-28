<?php
/**
 * 首頁控制器
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class IndexAction extends Action {

    /**
     * 我的首頁 - 微博頁面
     * @return void
     */
    public function index(){
        // 安全過濾
        $d['type'] = t($_GET['type']) ? t($_GET['type']) : 'following';
        $d['feed_type'] = t($_GET['feed_type']) ? t($_GET['feed_type']) : '';
        $d['feed_key'] = t($_GET['feed_key']) ? t($_GET['feed_key']) : '';
        // 關注的人
        if($d['type'] === 'following') {
            $d['groupname'] = L('PUBLIC_ACTIVITY_STREAM');          // 我關注的
            $d['followGroup'] = model('FollowGroup')->getGroupList($this->mid);
            foreach($d['followGroup'] as $v) {
                if($v['follow_group_id'] == t($_REQUEST['fgid'])) {
                    $d['groupname'] = $v['title'];
                    break;
                }
            }
        }
        // 判斷頻道是否開啟
        $isChannelOpen = model('App')->isAppNameOpen('channel');
        $this->assign('isChannelOpen', $isChannelOpen);
        // 關注的頻道
        if($isChannelOpen && $d['type'] === 'channel') {
            $d['channelname'] = '我關注的頻道';
            $d['channelGroup'] = D('ChannelFollow', 'channel')->getFollowList($this->mid);
            foreach($d['channelGroup'] as $v) {
                if($v['channel_category_id'] == t($_REQUEST['fgid'])) {
                    $d['channelname'] = $v['title'];
                    break;
                }
            }
        }
        $this->assign($d);
        // 設定默認話題
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $initHtml = $weiboSet['weibo_default_topic'];       // 微博框默認話題
        if($initHtml){
            $initHtml = '#'.$initHtml.'#';
        }
        $this->assign('initHtml' , $initHtml);

        $title = empty($weiboSet['weibo_send_info']) ? '隨時記錄' : $weiboSet['weibo_send_info'];
        $this->assign('title', $title);
        // 設定標題與關鍵字資訊
        switch($d['type']) {
        case 'all':
            $this->setTitle('全站動態');
            $this->setKeywords('全站動態');
            break;
        case 'channel':
            $this->setTitle('我關注的頻道');
            $this->setKeywords('我關注的頻道');
            break;
        default:
            $this->setTitle(L('PUBLIC_INDEX_INDEX'));
            $this->setKeywords(L('PUBLIC_INDEX_INDEX'));
        }

        $this->display();
    }

    public function loginWithoutInit(){
        $this->index();
    }

    /**
     * 我的微博頁面
     */
    public function myFeed() {
        // 獲取使用者統計數目
        $userData = model('UserData')->getUserData($this->mid);
        $this->assign('feedCount', $userData['weibo_count']);
        // 微博過濾內容
        $feedType = t($_GET['feed_type']);
        $this->assign('feedType', $feedType);
        // 是否有返回按鈕
        $this->assign('isReturn', 1);
        $this->setTitle('我的微博');
        $this->setKeywords('我的微博');
        $this->display();
    }

    /**
     * 我的關注頁面
     */
    public function following() {
        // 獲取關組分組ID
        $gid = intval($_GET['gid']);
        $this->assign('gid', $gid);
        // 獲取指定使用者的關注分組
        $groupList = model('FollowGroup')->getGroupList($this->mid);
        // 獲取使用者ID
        switch($gid) {
        case 0:
            $followGroupList = model('Follow')->getFollowingsList($this->mid);
            break;
        case -1:
            $followGroupList = model('Follow')->getFriendsList($this->mid);
            break;
        case -2:
            $followGroupList = model('FollowGroup')->getDefaultGroupByPage($this->mid);
            break;
        default:
            $followGroupList = model('FollowGroup')->getUsersByGroupPage($this->mid, $gid);
        }
        $fids = getSubByKey($followGroupList['data'], 'fid');
        // 獲取使用者資訊
        $followUserInfo = model('User')->getUserInfoByUids($fids);
        // 獲取使用者的統計數目
        $userData = model('UserData')->getUserDataByUids($fids);
        // 獲取使用者使用者組資訊
        $userGroupData = model('UserGroupLink')->getUserGroupData($fids);
        $this->assign('userGroupData',$userGroupData);
        // 獲取使用者的最後微博資料
        //$lastFeedData = model('Feed')->getLastFeed($fids);
        // 獲取使用者的關注資訊狀態值
        $followState = model('Follow')->getFollowStateByFids($this->mid, $fids);
        // 獲取使用者的備註資訊
        $remarkInfo = model('Follow')->getRemarkHash($this->mid);
        // 獲取使用者標籤
        $this->_assignUserTag($fids);
        // 關注分組資訊
        $followGroupStatus = model('FollowGroup')->getGroupStatusByFids($this->mid, $fids);
        $this->assign('followGroupStatus', $followGroupStatus);
        // 組裝資料
        foreach($followGroupList['data'] as $key => $value) {
            $followGroupList['data'][$key] = $followUserInfo[$value['fid']];
            $followGroupList['data'][$key] = array_merge($followGroupList['data'][$key], $userData[$value['fid']]);
            $followGroupList['data'][$key] = array_merge($followGroupList['data'][$key], array('feedInfo'=>$lastFeedData[$value['fid']]));
            $followGroupList['data'][$key] = array_merge($followGroupList['data'][$key], array('followState'=>$followState[$value['fid']]));
            $followGroupList['data'][$key] = array_merge($followGroupList['data'][$key], array('remark'=>$remarkInfo[$value['fid']]));
        }
        $this->assign($followGroupList);
        // 獲取登入使用者的所有分組
        $userGroupList = model('FollowGroup')->getGroupList($this->mid);
        $userGroupListFormat = array();
        foreach($userGroupList as $value) {
            $userGroupListFormat[] = array('gid'=>$value['follow_group_id'], 'title'=>$value['title']);
        }
        $groupList = array(array('gid'=>0, 'title'=>'全部'), array('gid'=>-1, 'title'=>'相互關注'), array('gid'=>-2, 'title'=>'未分組'));
        !empty($userGroupListFormat) && $groupList = array_merge($groupList, $userGroupListFormat);
        $this->assign('groupList', $groupList);
        // 前5個的分組ID
        $this->assign('topGroup', array_slice(getSubByKey($groupList, 'gid'), 0, 3));
        foreach($groupList as $value) {
            if($value['gid'] == $gid) {
                $this->assign('gTitle', $value['title']);
                break;
            }
        }
        // 關注人數
        $midData = model('UserData')->getUserData($this->mid);
        $this->assign('followingCount', $midData['following_count']);
        // 顯示的分類個數
        $this->assign('groupNums', 3);
        // 是否有返回按鈕
        $this->assign('isReturn', 1);

        $userInfo = model('User')->getUserInfo($this->mid);
        $lastFeed = model('Feed')->getLastFeed(array($fids[0]));
        $this->setTitle('我的關注');
        $this->setKeywords($userInfo['uname'].'的關注');
        $this->display();
    }

    /**
     * 我的粉絲頁面
     */
    public function follower() {
        // 清空新粉絲提醒數字
        if($this->uid == $this->mid){
            $udata = model('UserData')->getUserData($this->mid);
            $udata['new_folower_count'] > 0 && model('UserData')->setKeyValue($this->mid,'new_folower_count',0);
        }
        // 獲取使用者的粉絲列表
        $followerList = model('Follow')->getFollowerList($this->mid, 20);
        $fids = getSubByKey($followerList['data'], 'fid');
        // 獲取使用者資訊
        $followerUserInfo = model('User')->getUserInfoByUids($fids);
        // 獲取使用者統計數目
        $userData = model('UserData')->getUserDataByUids($fids);
        // 獲取使用者標籤
        $this->_assignUserTag($fids);
        // 獲取使用者使用者組資訊
        $userGroupData = model('UserGroupLink')->getUserGroupData($fids);
        $this->assign('userGroupData',$userGroupData);
        // 獲取使用者的最後微博資料
        //$lastFeedData = model('Feed')->getLastFeed($fids);
        // 獲取使用者的關注資訊狀態
        $followState = model('Follow')->getFollowStateByFids($this->mid, $fids);
        // 組裝資料
        foreach($followerList['data'] as $key => $value) {
            $followerList['data'][$key] = array_merge($followerList['data'][$key], $followerUserInfo[$value['fid']]);
            $followerList['data'][$key] = array_merge($followerList['data'][$key], $userData[$value['fid']]);
            $followerList['data'][$key] = array_merge($followerList['data'][$key], array('feedInfo'=>$lastFeedData[$value['fid']]));
            $followerList['data'][$key] = array_merge($followerList['data'][$key], array('followState'=>$followState[$value['fid']]));
        }
        $this->assign($followerList);
        // 是否有返回按鈕
        $this->assign('isReturn', 1);
        // 粉絲人數
        $midData = model('UserData')->getUserData($this->mid);
        $this->assign('followerCount', $midData['follower_count']);

        $userInfo = model('User')->getUserInfo($this->mid);
        $lastFeed = model('Feed')->getLastFeed(array($fids[0]));
        $this->setTitle('我的粉絲');
        $this->setKeywords($userInfo['uname'].'的粉絲');
        $this->display();
    }

    /**
     * 意見反饋頁面
     */
    public function feedback() {
        $feedbacktype = model('Feedback')->getFeedBackType();
        $this->assign('type', $feedbacktype);
        $this->display();
    }

    /**
     * 獲取驗證碼圖片操作
     */
    public function verify() {
        tsload(ADDON_PATH.'/library/Image.class.php');
        tsload(ADDON_PATH.'/library/String.class.php');
        Image::buildImageVerify();
    }

    /**
     * 獲取指定使用者小名片所需要的資料
     * @return string 指定使用者小名片所需要的資料
     */
    public function showFaceCard() {
        if(empty($_REQUEST['uid'])) {
            exit(L('PUBLIC_WRONG_USER_INFO'));          // 錯誤的使用者資訊
        }

        $this->assign('follow_group_status', model('FollowGroup')->getGroupStatus($GLOBALS['ts']['mid'], $GLOBALS['ts']['uid']));
        $this->assign('remarkHash', model('Follow')->getRemarkHash($GLOBALS['ts']['mid']));

        $uid = intval($_REQUEST['uid']);
        $data['userInfo'] = model('User')->getUserInfo($uid);
        $data['userInfo']['groupData'] = model('UserGroupLink')->getUserGroupData($uid);   //獲取使用者組資訊
        $data['user_tag'] = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags($uid);
        $data['user_tag'] = empty($data['user_tag']) ? '' : implode('、',$data['user_tag']);
        $data['follow_state'] = model('Follow')->getFollowState($this->mid, $uid);

        $depart = model('Department')->getAllHash();
        $data['department'] = isset($depart[$data['userInfo']['department_id']]) ? $depart[$data['userInfo']['department_id']] : '';

        $count = model('UserData')->getUserData($uid);
        if(empty($count)) {
            $count = array('following_count'=>0,'follower_count'=>0,'feed_count'=>0,'favorite_count'=>0,'unread_atme'=>0,'weibo_count'=>0);
        }
        $data['count_info'] = $count;

        // 使用者欄位資訊
        $profileSetting = D('UserProfileSetting')->where('type=2')->getHashList('field_id');
        $profile = model('UserProfile')->getUserProfile($uid);
        $data['profile'] = array();
        foreach($profile as $k=>$v) {
            if(isset($profileSetting[$k])) {
                $data['profile'][$profileSetting[$k]['field_key']] = array('name'=>$profileSetting[$k]['field_name'],'value'=>$v['field_data']);
            }
        }

        // 判斷隱私
        if($this->uid != $this->mid) {
            $UserPrivacy = model('UserPrivacy')->getPrivacy($this->mid, $this->uid);
            $this->assign('UserPrivacy', $UserPrivacy);
        }
        //判斷使用者是否已認證
        $isverify = D('user_verified')->where('verified=1 AND uid='.$uid)->find();
        if($isverify){
            $this->assign('verifyInfo',$isverify['info']);
        }
        $this->assign($data);
        $this->display();
    }

    /**
     * 公告詳細頁面
     */
    public function announcement() {
        $map['type'] = 1;
        $map['id'] = intval($_GET['id']);
        $d['announcement'] = model('Xarticle')->where($map)->find();
        // 組裝附件資訊
        $attachIds = explode('|', $d['announcement']['attach']);
        $attachInfo = model('Attach')->getAttachByIds($attachIds);
        $d['announcement']['attachInfo'] = $attachInfo;
        $this->assign($d);
        $this->display();
    }

    /**
     * 公告列表頁面
     */
    public function announcementList() {
        $map['type'] = 1;
        $list = model('Xarticle')->where($map)->findPage(20);
        // 獲取附件類型
        $attachIds = array();
        foreach($list['data'] as &$value) {
            $value['hasAttach'] = !empty($value['attach']) ? true : false;
        }

        $this->assign($list);
        $this->display();
    }

    /**
     * 自動提取標籤操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function getTags() {
        $text = t($_REQUEST['text']);
        $format = !empty($_REQUEST['format']) ? t($_REQUEST['format']) : 'string';
        $limit = !empty($_REQUEST['limit']) ? intval($_REQUEST['limit']) : '3';
        $tagX = model("Tag");
        $tagX->setText($text);      // 設定text
        $result = $tagX->getTop($limit,$format);  // 獲取前10個標籤
        exit($result);
    }

    /**
     * 根據指定應用和表獲取指定使用者的標籤,同個人空間中使用者標籤
     * @param array uids 使用者uid陣列
     * @return void
     */
    private function _assignUserTag($uids) {
        $user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags($uids);
        $this->assign('user_tag', $user_tag);
    }

    /**
     * 彈窗釋出微博
     * @return void
     */
    public function sendFeedBox()
    {
        $initHtml = t($_REQUEST['initHtml']);
        if(!empty($initHtml)) {
            $data['initHtml'] = $initHtml;
    }
    //投稿資料處理
    $channelID = h($_REQUEST['channelID']);
    if(!empty($channelID)){
        $data['channelID'] = $channelID;
        $data['type'] = 'submission';
    }

    $this->assign($data);
    $this->display();
    }
    public function scoredetail(){
        $list = model('Credit')->getLevel();
        $this->assign( 'list' , $list );
        $this->display();
    }
    }
