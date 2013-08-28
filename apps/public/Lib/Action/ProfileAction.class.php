<?php
/**
 * ProfileAction 個人檔案模組
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class ProfileAction extends Action {
    /**
     * _initialize 模組初始化
     *
     * @return void
     */
    protected function _initialize() {
        // 短域名判斷
        if (! isset ( $_GET ['uid'] ) || empty ( $_GET ['uid'] )) {
            $this->uid = $this->mid;
        } elseif (is_numeric ( $_GET ['uid'] )) {
            $this->uid = intval ( $_GET ['uid'] );
        } else {
            $map ['domain'] = t ( $_GET ['uid'] );
            $this->uid = model ( 'User' )->where ( $map )->getField ( 'uid' );
        }
        $this->assign ( 'uid', $this->uid );
    }

    /**
     * 隱私設定
     */
    public function privacy($uid) {
        if ($this->mid != $uid) {
            $privacy = model ( 'UserPrivacy' )->getPrivacy ( $this->mid, $uid );
            return $privacy;
        } else {
            return true;
        }
    }

    /**
     * 個人檔案展示頁面
     */
    public function index() {
        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        // 使用者為空，則跳轉使用者不存在
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }
        // 個人空間頭部
        $this->_top ();
        $this->_tab_menu();

        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $this->_sidebar ();
            // 載入微博篩選資訊
            $d ['feed_type'] = t ( $_REQUEST ['feed_type'] ) ? t ( $_REQUEST ['feed_type'] ) : '';
            $d ['feed_key'] = t ( $_REQUEST ['feed_key'] ) ? t ( $_REQUEST ['feed_key'] ) : '';
            $this->assign ( $d );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }

        // 添加積分
        model ( 'Credit' )->setUserCredit ( $this->uid, 'space_access' );

        $this->assign ( 'userPrivacy', $userPrivacy );
        // seo
        $seo = model ( 'Xdata' )->get ( "admin_Config:seo_user_profile" );
        $replace ['uname'] = $user_info ['uname'];
        if ($feed_id = model ( 'Feed' )->where ( 'uid=' . $this->uid )->order ( 'publish_time desc' )->limit ( 1 )->getField ( 'feed_id' )) {
            $replace ['lastFeed'] = D ( 'feed_data' )->where ( 'feed_id=' . $feed_id )->getField ( 'feed_content' );
        }
        $replaces = array_keys ( $replace );
        foreach ( $replaces as &$v ) {
            $v = "{" . $v . "}";
        }
        $seo ['title'] = str_replace ( $replaces, $replace, $seo ['title'] );
        $seo ['keywords'] = str_replace ( $replaces, $replace, $seo ['keywords'] );
        $seo ['des'] = str_replace ( $replaces, $replace, $seo ['des'] );
        ! empty ( $seo ['title'] ) && $this->setTitle ( $seo ['title'] );
        ! empty ( $seo ['keywords'] ) && $this->setKeywords ( $seo ['keywords'] );
        ! empty ( $seo ['des'] ) && $this->setDescription ( $seo ['des'] );
        $this->display ();
    }
    function appList() {
        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        // 使用者為空，則跳轉使用者不存在
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }
        // 個人空間頭部
        $this->_top ();
        $this->_assignUserInfo ( $this->uid );

        $appArr = $this->_tab_menu();
        $type = t ( $_GET ['type'] );
        if (! isset ( $appArr [$type] )) {
            $this->error ( '參數出錯！！' );
        }
        $this->assign('type', $type);
        $className = ucfirst ( $type ) . 'Protocol';
        $content = D ( $className, $type )->profileContent ( $this->uid );
        if(empty($content)){
            $content = '暫無內容';
        }
        $this->assign ( 'content', $content );
        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $this->_sidebar ();
            // 檔案類型
            $ProfileType = model ( 'UserProfile' )->getCategoryList ();
            $this->assign ( 'ProfileType', $ProfileType );
            // 個人資料
            $this->_assignUserProfile ( $this->uid );
            // 獲取使用者職業資訊
            $userCategory = model ( 'UserCategory' )->getRelatedUserInfo ( $this->uid );
            if (! empty ( $userCategory )) {
                foreach ( $userCategory as $value ) {
                    $user_category .= '<a href="#" class="link btn-cancel"><span>' . $value ['title'] . '</span></a>&nbsp;&nbsp;';
                }
            }
            $this->assign ( 'user_category', $user_category );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }
        $this->assign ( 'userPrivacy', $userPrivacy );
        $this->setTitle ( $user_info ['uname'] . '的'.L ( 'PUBLIC_APPNAME_' . $type ) );
        $this->setKeywords ( $user_info ['uname'] . '的'.L ( 'PUBLIC_APPNAME_' . $type ) );
        $user_tag = model ( 'Tag' )->setAppName ( 'User' )->setAppTable ( 'user' )->getAppTags ( array (
            $this->uid
        ) );
        $this->setDescription ( t ( $user_category . $user_info ['location'] . ',' . implode ( ',', $user_tag [$this->uid] ) . ',' . $user_info ['intro'] ) );


        $this->display ();
    }

    /**
     * 獲取指定應用的資訊
     *
     * @return void
     */
    public function appprofile() {
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }

        $d ['widgetName'] = ucfirst ( t ( $_GET ['appname'] ) ) . 'Profile';
        foreach ( $_GET as $k => $v ) {
            $d ['widgetAttr'] [$k] = t ( $v );
        }
        $d ['widgetAttr'] ['widget_appname'] = t ( $_GET ['appname'] );
        $this->assign ( $d );

        $this->_assignUserInfo ( array (
            $this->uid
        ) );
        ($this->mid != $this->uid) && $this->_assignFollowState ( $this->uid );
        $this->display ();
    }

    /**
     * 獲取使用者詳細資料
     *
     * @return void
     */
    public function data() {
        if (! CheckPermission ( 'core_normal', 'read_data' ) && $this->uid != $this->mid) {
            $this->error ( '對不起，您沒有許可權瀏覽該內容!' );
        }
        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        // 使用者為空，則跳轉使用者不存在
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }
        // 個人空間頭部
        $this->_top ();
        $this->_tab_menu();
        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $this->_sidebar ();
            // 檔案類型
            $ProfileType = model ( 'UserProfile' )->getCategoryList ();
            $this->assign ( 'ProfileType', $ProfileType );
            // 個人資料
            $this->_assignUserProfile ( $this->uid );
            // 獲取使用者職業資訊
            $userCategory = model ( 'UserCategory' )->getRelatedUserInfo ( $this->uid );
            if (! empty ( $userCategory )) {
                foreach ( $userCategory as $value ) {
                    $user_category .= '<a href="#" class="link btn-cancel"><span>' . $value ['title'] . '</span></a>&nbsp;&nbsp;';
                }
            }
            $this->assign ( 'user_category', $user_category );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }
        $this->assign ( 'userPrivacy', $userPrivacy );

        $this->setTitle ( $user_info ['uname'] . '的資料' );
        $this->setKeywords ( $user_info ['uname'] . '的資料' );
        $user_tag = model ( 'Tag' )->setAppName ( 'User' )->setAppTable ( 'user' )->getAppTags ( array (
            $this->uid
        ) );
        $this->setDescription ( t ( $user_category . $user_info ['location'] . ',' . implode ( ',', $user_tag [$this->uid] ) . ',' . $user_info ['intro'] ) );
        $this->display ();
    }

    /**
     * 獲取指定使用者的某條動態
     *
     * @return void
     */
    public function feed() {

        $feed_id = intval ( $_GET ['feed_id'] );

        if (empty ( $feed_id )) {
            $this->error ( L ( 'PUBLIC_INFO_ALREADY_DELETE_TIPS' ) );
        }

        //獲取微博資訊
        $feedInfo = model ( 'Feed' )->get ( $feed_id );

        if (!$feedInfo){
            $this->error ( '該微博不存在或已被刪除' );
            exit();
        }

        if ($feedInfo ['is_audit'] == '0' && $feedInfo ['uid'] != $this->mid) {
            $this->error ( '此微博正在稽覈' );
            exit();
        }

        if ($feedInfo ['is_del'] == '1') {
            $this->error ( L ( 'PUBLIC_NO_RELATE_WEIBO' ) );
            exit();
        }

        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $feedInfo['uid'] );

        // 個人空間頭部
        $this->_top ();

        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $this->_sidebar ();
            $weiboSet = model ( 'Xdata' )->get ( 'admin_Config:feed' );
            $a ['initNums'] = $weiboSet ['weibo_nums'];
            $a ['weibo_type'] = $weiboSet ['weibo_type'];
            $a ['weibo_premission'] = $weiboSet ['weibo_premission'];
            $this->assign ( $a );
            switch ($feedInfo ['app']) {
            case 'weiba' :
                $feedInfo ['from'] = getFromClient ( 0, $feedInfo ['app'], '微吧' );
                break;
            default :
                $feedInfo ['from'] = getFromClient ( $from, $feedInfo ['app'] );
                break;
            }
            // $feedInfo['from'] = getFromClient( $feedInfo['from'] , $feedInfo['app']);
            $this->assign ( 'feedInfo', $feedInfo );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }
        // seo
        $feedContent = unserialize ( $feedInfo ['feed_data'] );
        $seo = model ( 'Xdata' )->get ( "admin_Config:seo_feed_detail" );
        $replace ['content'] = $feedContent ['content'];
        $replace ['uname'] = $feedInfo ['user_info'] ['uname'];
        $replaces = array_keys ( $replace );
        foreach ( $replaces as &$v ) {
            $v = "{" . $v . "}";
        }
        $seo ['title'] = str_replace ( $replaces, $replace, $seo ['title'] );
        $seo ['keywords'] = str_replace ( $replaces, $replace, $seo ['keywords'] );
        $seo ['des'] = str_replace ( $replaces, $replace, $seo ['des'] );
        ! empty ( $seo ['title'] ) && $this->setTitle ( $seo ['title'] );
        ! empty ( $seo ['keywords'] ) && $this->setKeywords ( $seo ['keywords'] );
        ! empty ( $seo ['des'] ) && $this->setDescription ( $seo ['des'] );
        $this->assign ( 'userPrivacy', $userPrivacy );
        // 贊功能
        $diggArr = model ( 'FeedDigg' )->checkIsDigg ( $feed_id, $this->mid );
        $this->assign ( 'diggArr', $diggArr );

        $this->display ();
    }

    /**
     * 獲取使用者關注列表
     *
     * @return void
     */
    public function following() {
        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        // 使用者為空，則跳轉使用者不存在
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }
        // 個人空間頭部
        $this->_top ();
        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $following_list = model ( 'Follow' )->getFollowingList ( $this->uid, t ( $_GET ['gid'] ), 20 );
            $fids = getSubByKey ( $following_list ['data'], 'fid' );
            if ($fids) {
                $uids = array_merge ( $fids, array (
                    $this->uid
                ) );
            } else {
                $uids = array (
                    $this->uid
                );
            }
            // 獲取使用者組資訊
            $userGroupData = model ( 'UserGroupLink' )->getUserGroupData ( $uids );
            $this->assign ( 'userGroupData', $userGroupData );
            $this->_assignFollowState ( $uids );
            $this->_assignUserInfo ( $uids );
            $this->_assignUserProfile ( $uids );
            $this->_assignUserTag ( $uids );
            $this->_assignUserCount ( $fids );
            // 關注分組
            ($this->mid == $this->uid) && $this->_assignFollowGroup ( $fids );
            $this->assign ( 'following_list', $following_list );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }
        $this->assign ( 'userPrivacy', $userPrivacy );

        $this->setTitle ( L ( 'PUBLIC_TA_FOLLOWING', array (
            'user' => $GLOBALS ['ts'] ['_user'] ['uname']
        ) ) );
        $this->setKeywords ( L ( 'PUBLIC_TA_FOLLOWING', array (
            'user' => $GLOBALS ['ts'] ['_user'] ['uname']
        ) ) );
        $this->display ();
    }

    /**
     * 獲取使用者粉絲列表
     *
     * @return void
     */
    public function follower() {
        // 獲取使用者資訊
        $user_info = model ( 'User' )->getUserInfo ( $this->uid );
        // 使用者為空，則跳轉使用者不存在
        if (empty ( $user_info )) {
            $this->error ( L ( 'PUBLIC_USER_NOEXIST' ) );
        }
        // 個人空間頭部
        $this->_top ();
        // 判斷隱私設定
        $userPrivacy = $this->privacy ( $this->uid );
        if ($userPrivacy ['space'] !== 1) {
            $follower_list = model ( 'Follow' )->getFollowerList ( $this->uid, 20 );
            $fids = getSubByKey ( $follower_list ['data'], 'fid' );
            if ($fids) {
                $uids = array_merge ( $fids, array (
                    $this->uid
                ) );
            } else {
                $uids = array (
                    $this->uid
                );
            }
            // 獲取使用者使用者組資訊
            $userGroupData = model ( 'UserGroupLink' )->getUserGroupData ( $uids );
            $this->assign ( 'userGroupData', $userGroupData );
            $this->_assignFollowState ( $uids );
            $this->_assignUserInfo ( $uids );
            $this->_assignUserProfile ( $uids );
            $this->_assignUserTag ( $uids );
            $this->_assignUserCount ( $fids );
            // 更新檢視粉絲時間
            if ($this->uid == $this->mid) {
                $t = time () - intval ( $GLOBALS ['ts'] ['_userData'] ['view_follower_time'] ); // 避免伺服器時間不一致
                model ( 'UserData' )->setUid ( $this->mid )->updateKey ( 'view_follower_time', $t, true );
            }
            $this->assign ( 'follower_list', $follower_list );
        } else {
            $this->_assignUserInfo ( $this->uid );
        }
        $this->assign ( 'userPrivacy', $userPrivacy );

        $this->setTitle ( L ( 'PUBLIC_TA_FOLLWER', array (
            'user' => $GLOBALS ['ts'] ['_user'] ['uname']
        ) ) );
        $this->setKeywords ( L ( 'PUBLIC_TA_FOLLWER', array (
            'user' => $GLOBALS ['ts'] ['_user'] ['uname']
        ) ) );
        $this->display ();
    }

    /**
     * 批量獲取使用者的相關資訊載入
     *
     * @param string|array $uids
     *          使用者ID
     */
    private function _assignUserInfo($uids) {
        ! is_array ( $uids ) && $uids = explode ( ',', $uids );
        $user_info = model ( 'User' )->getUserInfoByUids ( $uids );
        $this->assign ( 'user_info', $user_info );
        // dump($user_info);exit;
    }

    /**
     * 獲取使用者的檔案資訊和資料配置資訊
     *
     * @param
     *          mix uids 使用者uid
     * @return void
     */
    private function _assignUserProfile($uids) {
        $data ['user_profile'] = model ( 'UserProfile' )->getUserProfileByUids ( $uids );
        $data ['user_profile_setting'] = model ( 'UserProfile' )->getUserProfileSetting ( array (
            'visiable' => 1
        ) );
        // 使用者選擇處理 uid->uname
        foreach ( $data ['user_profile_setting'] as $k => $v ) {
            if ($v ['form_type'] == 'selectUser') {
                $field_ids [] = $v ['field_id'];
            }
            if ($v ['form_type'] == 'selectDepart') {
                $field_departs [] = $v ['field_id'];
            }
        }
        foreach ( $data ['user_profile'] as $ku => &$uprofile ) {
            foreach ( $uprofile as $key => $val ) {
                if (in_array ( $val ['field_id'], $field_ids )) {
                    $user_info = model ( 'User' )->getUserInfo ( $val ['field_data'] );
                    $uprofile [$key] ['field_data'] = $user_info ['uname'];
                }
                if (in_array ( $val ['field_id'], $field_departs )) {
                    $depart_info = model ( 'Department' )->getDepartment ( $val ['field_data'] );
                    $uprofile [$key] ['field_data'] = $depart_info ['title'];
                }
            }
        }
        $this->assign ( $data );
    }

    /**
     * 根據指定應用和表獲取指定使用者的標籤
     *
     * @param
     *          array uids 使用者uid陣列
     * @return void
     */
    private function _assignUserTag($uids) {
        $user_tag = model ( 'Tag' )->setAppName ( 'User' )->setAppTable ( 'user' )->getAppTags ( $uids );
        $this->assign ( 'user_tag', $user_tag );
    }

    /**
     * 批量獲取多個使用者的統計數目
     *
     * @param array $uids
     *          使用者uid陣列
     * @return void
     */
    private function _assignUserCount($uids) {
        $user_count = model ( 'UserData' )->getUserDataByUids ( $uids );
        $this->assign ( 'user_count', $user_count );
    }

    /**
     * 批量獲取使用者uid與一群人fids的彼此關注狀態
     *
     * @param array $fids
     *          使用者uid陣列
     * @return void
     */
    private function _assignFollowState($fids = null) {
        // 批量獲取與當前登入使用者之間的關注狀態
        $follow_state = model ( 'Follow' )->getFollowStateByFids ( $this->mid, $fids );
        $this->assign ( 'follow_state', $follow_state );
        // dump($follow_state);exit;
    }

    /**
     * 獲取使用者最後一條微博資料
     *
     * @param
     *          mix uids 使用者uid
     * @param
     *          void
     */
    private function _assignUserLastFeed($uids) {
        return true; // 目前不需要這個功能
        $last_feed = model ( 'Feed' )->getLastFeed ( $uids );
        $this->assign ( 'last_feed', $last_feed );
    }

    /**
     * 調整分組列表
     *
     * @param array $fids
     *          指定使用者關注的使用者列表
     * @return void
     */
    private function _assignFollowGroup($fids) {
        $follow_group_list = model ( 'FollowGroup' )->getGroupList ( $this->mid );
        // 調整分組列表
        if (! empty ( $follow_group_list )) {
            $group_count = count ( $follow_group_list );
            for($i = 0; $i < $group_count; $i ++) {
                if ($follow_group_list [$i] ['follow_group_id'] != $data ['gid']) {
                    $follow_group_list [$i] ['title'] = (strlen ( $follow_group_list [$i] ['title'] ) + mb_strlen ( $follow_group_list [$i] ['title'], 'UTF8' )) / 2 > 8 ? getShort ( $follow_group_list [$i] ['title'], 3 ) . '...' : $follow_group_list [$i] ['title'];
                }
                if ($i < 2) {
                    $data ['follow_group_list_1'] [] = $follow_group_list [$i];
                } else {
                    if ($follow_group_list [$i] ['follow_group_id'] == $data ['gid']) {
                        $data ['follow_group_list_1'] [2] = $follow_group_list [$i];
                        continue;
                    }
                    $data ['follow_group_list_2'] [] = $follow_group_list [$i];
                }
            }
            if (empty ( $data ['follow_group_list_1'] [2] ) && ! empty ( $data ['follow_group_list_2'] [0] )) {
                $data ['follow_group_list_1'] [2] = $data ['follow_group_list_2'] [0];
                unset ( $data ['follow_group_list_2'] [0] );
            }
        }

        $data ['follow_group_status'] = model ( 'FollowGroup' )->getGroupStatusByFids ( $this->mid, $fids );

        $this->assign ( $data );
    }

    /**
     * 個人主頁頭部資料
     *
     * @return void
     */
    public function _top() {
        // 獲取使用者組資訊
        $userGroupData = model ( 'UserGroupLink' )->getUserGroupData ( $this->uid );
        $this->assign ( 'userGroupData', $userGroupData );
        // 獲取使用者積分資訊
        $userCredit = model ( 'Credit' )->getUserCredit ( $this->uid );
        $this->assign ( 'userCredit', $userCredit );
        // 載入使用者關注資訊
        ($this->mid != $this->uid) && $this->_assignFollowState ( $this->uid );
        // 獲取使用者統計資訊
        $userData = model ( 'UserData' )->getUserData ( $this->uid );
        $this->assign ( 'userData', $userData );
    }
    /**
     * 個人主頁標籤導航
     *
     * @return void
     */
    public function _tab_menu() {
        // 取全部APP資訊
        $appList = model ( 'App' )->where ( 'status=1' )->field ( 'app_name' )->findAll ();
        foreach ( $appList as $app ) {
            $appName = strtolower ( $app ['app_name'] );
            $className = ucfirst ( $appName );

            $dao = D ( $className . 'Protocol', strtolower($className), false );
            if (method_exists ( $dao, 'profileContent' )) {
                $appArr [$appName] = L ( 'PUBLIC_APPNAME_' . $appName );
    }
    unset ( $dao );
    }
    $this->assign ( 'appArr', $appArr );

    return $appArr;
    }

    /**
     * 個人主頁右側
     *
     * @return void
     */
    public function _sidebar() {
        // 判斷使用者是否已認證
        $isverify = D ( 'user_verified' )->where ( 'verified=1 AND uid=' . $this->uid )->find ();
        if ($isverify) {
            $this->assign ( 'verifyInfo', $isverify ['info'] );
        }
        // 載入使用者標籤資訊
        $this->_assignUserTag ( array (
            $this->uid
        ) );
        // 載入關注列表
        $sidebar_following_list = model ( 'Follow' )->getFollowingList ( $this->uid, null, 12 );
        $this->assign ( 'sidebar_following_list', $sidebar_following_list );
        // dump($sidebar_following_list);exit;
        // 載入粉絲列表
        $sidebar_follower_list = model ( 'Follow' )->getFollowerList ( $this->uid, 12 );
        $this->assign ( 'sidebar_follower_list', $sidebar_follower_list );
        // 載入使用者資訊
        $uids = array (
            $this->uid
        );

        $followingfids = getSubByKey ( $sidebar_following_list ['data'], 'fid' );
        $followingfids && $uids = array_merge ( $uids, $followingfids );

        $followerfids = getSubByKey ( $sidebar_follower_list ['data'], 'fid' );
        $followerfids && $uids = array_merge ( $uids, $followerfids );

        $this->_assignUserInfo ( $uids );
        }
        }
