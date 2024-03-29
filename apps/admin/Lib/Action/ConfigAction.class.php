<?php
/**
 * 後臺，系統配置控制器
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
// 載入後臺控制器
tsload ( APPS_PATH . '/admin/Lib/Action/AdministratorAction.class.php' );
class ConfigAction extends AdministratorAction {

    /**
     * 初始化，頁面標題，用於雙語
     */
    public function _initialize() {
        parent::_initialize ();
        $this->pageTitle ['site'] = L ( 'PUBLIC_WEBSITE_SETTING' );
        $this->pageTitle ['register'] = L ( 'PUBLIC_REGISTER_SETTING' );
        $this->pageTitle ['invite'] = L ( 'PUBLIC_INVITE_CONFIG' );
        $this->pageTitle ['announcement'] = L ( 'PUBLIC_ANNOUNCEMENT_SETTING' );
        $this->pageTitle ['email'] = L ( 'PUBLIC_EMAIL_SETTING' );
        $this->pageTitle ['audit'] = L ( 'PUBLIC_FILTER_SETTING' );
        $this->pageTitle ['footer'] = L ( 'PUBLIC_FOOTER_CONFIG' );
        $this->pageTitle ['feed'] = L ( 'PUBLIC_WEIBO_SETTING' );
        $this->pageTitle ['nav'] = L ( 'PUBLIC_NAVIGATION_SETTING' );
        $this->pageTitle ['footNav'] = L ( 'PUBLIC_NAVIGATION_SETTING' );
        $this->pageTitle ['navAdd'] = ($_GET ['id'] && ! $_GET ['type']) ? '編輯導航' : '增加導航';
        $this->pageTitle ['lang'] = L ( 'PUBLIC_LANGUAGE' );
        $this->pageTitle ['diylist'] = L ( 'PUBLIC_DIYWIDGET' );
        $this->pageTitle ['notify'] = L ( 'PUBLIC_MAILTITLE_ADMIN' );
        $this->pageTitle ['invite'] = '邀請配置';
        $this->pageTitle ['inviteEmail'] = '郵件邀請';
        $this->pageTitle ['inviteLink'] = '連結邀請';
        $this->pageTitle ['getInviteAdminList'] = '已邀請使用者列表';
        $this->pageTitle ['setSeo'] = 'SEO配置';
        $this->pageTitle ['editSeo'] = '編輯SEO';
        $this->pageTitle ['setUcenter'] = 'Ucenter配置';
        parent::_initialize ();
    }

    /**
     * 系統配置 - 站點配置
     */
    public function site() {
        $this->pageKeyList = array (
            'site_closed',
            'site_name',
            'site_slogan',
            'site_header_keywords',
            'site_header_description',
            'site_company',
            'site_footer',
            'site_logo',
            'login_bg',
            'site_closed_reason',
            'sys_domain',
            'sys_nickname',
            'sys_email',
            'home_page',
            'site_theme_name',
            'sys_version',
            'site_online_count',
            'site_rewrite_on',
            'site_analytics_code'
        );
        // 其他額外需要的資料,如checkbox 陣列,select選項組的key->value賦值
        $this->opt ['site_closed'] = $this->opt ['site_online_count'] = $this->opt ['site_rewrite_on'] = array (
            '1' => L ( 'PUBLIC_OPEN' ),
            '0' => L ( 'PUBLIC_CLOSE' )
        );
        $apps = model ( 'App' )->where ( 'status=1' )->findAll ();
        $this->opt ['home_page'] [0] = L ( 'PUBLIC_MY_HOME' );
        foreach ( $apps as $k => $v ) {
            $this->opt ['home_page'] [$v ['app_id']] = $v ['app_alias'];
        }

        require_once ADDON_PATH . '/library/io/Dir.class.php';
        $dirs = new Dir ( ADDON_PATH . '/theme' );
        $dirs = $dirs->toArray ();
        foreach ( $dirs as $v ) {
            $this->opt ['site_theme_name'] [$v ['filename']] = $v ['filename'];
        }

        $detailData = model ( 'Xdata' )->get ( $this->systemdata_list . ":" . $this->systemdata_key );
        if (isset ( $detailData ['site_analytics_code'] ) && ! empty ( $detailData ['site_analytics_code'] )) {
            $detailData ['site_analytics_code'] = base64_decode ( $detailData ['site_analytics_code'] );
        }

        $theme_name = C ( 'THEME_NAME' );
        if (isset ( $detailData ['site_theme_name'] ) && ! empty ( $theme_name )) {
            $detailData ['site_theme_name'] = $theme_name;
        }

        $logo = $GLOBALS ['ts'] ['site'] ['logo'];
        $filesShow ['site_logo'] = '<img src="' . $logo . '">';

        $this->assign ( 'filesShow', $filesShow );

        $this->onload [] = 'admin.siteConfigDefault(' . $detailData ['site_closed'] . ')';

        $this->displayConfig ( $detailData );
    }

    /**
     * 系統配置 - 註冊配置
     */
    public function register() {
        $this->pageKeyList = array (
            'register_type',
            'email_suffix',
            'captcha',
            'register_audit',
            'need_active',
            'photo_open',
            'need_photo',
            'tag_open',
            'tag_num',
            'interester_open',
            'interester_rule',
            'interester_recommend',
            'default_follow',
            'each_follow',
            'default_user_group',
            'welcome_email'
        );
        // 指定郵箱字尾，任何郵箱字尾，關閉註冊
        $this->opt ['register_type'] = array (
            'open' => '開放註冊',
            'invite' => '僅邀請註冊',
            'admin' => '僅管理員邀請註冊',
            'other' => '僅第三方帳號繫結'
        );
        // 開啟，關閉
        $this->opt ['register_audit'] = $this->opt ['captcha'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        // 是，否
        $this->opt ['need_active'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        $this->opt ['photo_open'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        $this->opt ['need_photo'] = array (
            1 => '是，強制上傳 ',
            0 => '否，可跳過 '
        );
        $this->opt ['tag_open'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        $this->opt ['interester_open'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        // $this->opt['interester_rule'] = array('area'=>'按地區匹配', 'tag'=>'按標籤匹配', 'face'=>'過濾無頭像使用者');
        $this->opt ['interester_rule'] = array (
            'area' => '按地區匹配',
            'tag' => '按標籤匹配'
        );
        $this->opt ['welcome_email'] = array (
            1 => L ( 'PUBLIC_OPEN' ),
            0 => L ( 'PUBLIC_CLOSE' )
        );
        // 使用者組資訊
        $this->opt ['default_user_group'] = model ( 'UserGroup' )->getHashUsergroup ();

        $detailData = model ( 'Xdata' )->get ( $this->systemdata_list . ":" . $this->systemdata_key );
        $this->onsubmit = 'admin.checkRegisterConfig(this)';
        $this->displayConfig ();
    }

    /**
     * * 邀請配置 **
     */
    /**
     * 初始化邀請Tab項目
     *
     * @return void
     */
    private function _initTabInvite() {
        // Tab選項
        $this->pageTab [] = array (
            'title' => '邀請配置',
            'tabHash' => 'invite',
            'url' => U ( 'admin/Config/invite' )
        );
        $this->pageTab [] = array (
            'title' => '郵件邀請',
            'tabHash' => 'inviteEmail',
            'url' => U ( 'admin/Config/inviteEmail' )
        );
        $this->pageTab [] = array (
            'title' => '連結邀請',
            'tabHash' => 'inviteLink',
            'url' => U ( 'admin/Config/inviteLink' )
        );
        $this->pageTab [] = array (
            'title' => '已邀請使用者列表',
            'tabHash' => 'getInviteAdminList',
            'url' => U ( 'admin/Config/getInviteAdminList' )
        );
    }

    /**
     * 系統配置 - 邀請配置
     *
     * @return void
     */
    public function invite() {
        $this->_initTabInvite ();
        $this->pageKeyList = array (
            'send_email_num',
            'send_link_num'
        );
        $this->displayConfig ();
    }

    /**
     * 郵件邀請 - 管理員
     *
     * @return void
     */
    public function inviteEmail() {
        $this->_initTabInvite ();
        // 獲取已邀請使用者資訊
        $inviteList = model ( 'Invite' )->getInviteUserList ( $this->mid, 'email', true );
        // 獲取邀請人資訊
        $uids = getSubByKey ( $inviteList ['data'], 'inviter_uid' );
        $userInfos = model ( 'User' )->getUserInfoByUids ( $uids );
        foreach ( $inviteList ['data'] as &$value ) {
            $value ['inviteInfo'] = $userInfos [$value ['inviter_uid']];
        }
        $this->assign ( 'inviteList', $inviteList );

        $this->display ( 'invite_email' );
    }

    /**
     * 管理員郵件邀請操作
     *
     * @return json 操作後的相關資料
     */
    public function doInvite() {
        $email = t ( $_POST ['email'] );
        $detial = ! isset ( $_POST ['detial'] ) ? L ( 'PUBLIC_INVATE_MESSAGE', array (
            'uname' => $GLOBALS ['ts'] ['user'] ['uname']
        ) ) : h ( $_POST ['detial'] ); // Hi，我是 {uname}，我發現了一個很不錯的網站，我在這裡等你，快來加入吧。
        $map ['inviter_uid'] = $this->mid;
        $map ['ctime'] = time ();
        // 發送郵件邀請
        $result = model ( 'Invite' )->doInvite ( $email, $detial, $this->mid, true );
        $this->ajaxReturn ( null, model ( 'Invite' )->getError (), $result );
    }

    /**
     * 連線邀請 - 管理員
     *
     * @return void
     */
    public function inviteLink() {
        $this->_initTabInvite ();
        // 獲取邀請碼資訊
        $codeList = model ( 'Invite' )->getAdminInviteCode ( 'link' );
        $this->assign ( 'codeList', $codeList );
        // 獲取已邀請使用者資訊
        $inviteList = model ( 'Invite' )->getInviteUserList ( $this->mid, 'link', true );
        $this->assign ( 'inviteList', $inviteList );

        $this->display ( 'invite_link' );
    }

    /**
     * 獲取邀請碼介面
     *
     * @return json 操作後的相關資料
     */
    public function getInviteCode() {
        $res = model ( 'Invite' )->createInviteCode ( $this->mid, 'link', 10, true );
        $result = array ();
        if ($res) {
            $result ['status'] = 1;
            $result ['info'] = '邀請碼獲取成功';
        } else {
            $result ['status'] = 0;
            $result ['info'] = '邀請碼獲取失敗';
        }

        exit ( json_encode ( $result ) );
    }

    /**
     * 已邀請使用者列表
     *
     * @return html 顯示已邀請使用者列表
     */
    public function getInviteAdminList() {
        $_REQUEST ['tabHash'] = 'getInviteAdminList';
        $this->_initTabInvite ();
        $this->allSelected = false;

        $this->searchKey = array (
            'invite_type'
        );
        $this->pageButton [] = array (
            'title' => '篩選列表',
            'onclick' => "admin.fold('search_form')"
        );
        $this->opt ['invite_type'] = array (
            '0' => '全部',
            '1' => '郵件邀請',
            '2' => '連結邀請'
        );

        $this->pageKeyList = array (
            'face',
            'receiver_uname',
            'receiver_email',
            'ctime',
            'invite_type',
            'invite_code',
            'inviter_uname'
        );
        $type = '';
        if ($_REQUEST ['dosearch'] == 1) {
            if (intval ( $_REQUEST ['invite_type'] ) == 1) {
                $type = 'email';
            } else if (intval ( $_REQUEST ['invite_type'] ) == 2) {
                $type = 'link';
            }
        }
        $listData = model ( 'Invite' )->getInviteAdminUserList ( $type );
        foreach ( $listData ['data'] as $key => &$value ) {
            $value ['face'] = '<img src="' . $value ['avatar_small'] . '" />';
            $receiverInfo = model ( 'User' )->getUserInfo ( $value ['receiver_uid'] );
            $value ['receiver_uname'] = $receiverInfo ['uname'];
            $value ['receiver_email'] = $receiverInfo ['email'];
            $value ['ctime'] = date ( 'Y-m-d H:i:s', $receiverInfo ['ctime'] );
            $value ['invite_type'] = $value ['type'] == 'link' ? '連結邀請' : '郵件邀請';
            $value ['invite_code'] = $value ['code'];
            $inviterInfo = model ( 'User' )->getUserInfo ( $value ['inviter_uid'] );
            $value ['inviter_uname'] = $inviterInfo ['uname'];
        }

        $this->displayList ( $listData );
    }

    /**
     * 公告配置
     */
    public function announcement($type = 1) {

        // 列表key值 DOACTION表示操作
        // $this->pageKeyList = array('id','title','uid','sort','DOACTION');
        $this->pageKeyList = array (
            'id',
            'title',
            'uid',
            'DOACTION'
        );
        $title = $type == 1 ? L ( 'PUBLIC_ANNOUNCEMENT' ) : L ( 'PUBLIC_FOOTER_ARTICLE' );
        // 列表分頁欄 按鈕
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_ADD' ) . $title,
            'onclick' => "location.href = '" . U ( 'admin/Config/addArticle', array (
                'type' => $type
            ) ) . "'"
        );
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_STREAM_DELETE' ) . $title,
            'onclick' => "admin.delArticle('',{$type})"
        );

        /* 資料的格式化 與listKey保持一致 */
        $map ['type'] = $type;
        $listData = model ( 'Xarticle' )->where ( $map )->order ( 'sort asc' )->findPage ( 20 );

        foreach ( $listData ['data'] as &$v ) {
            $uinfo = model ( 'User' )->getUserInfo ( $v ['uid'] );
            $v ['uid'] = $uinfo ['space_link'];
            // TODO 附件處理
            $v ['DOACTION'] = '<a href="' . U ( 'admin/Config/addArticle', array (
                'id' => $v ['id'],
                'type' => $type
            ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>
            <a href="javascript:admin.delArticle(' . $v ['id'] . ',' . $type . ')" >' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
        }

        $this->displayList ( $listData );
    }

    // 添加公告
    public function addArticle() {
        $type = (empty ( $_GET ['type'] ) || $_GET ['type'] == 1) ? 1 : 2;
        $title = $type == 1 ? L ( 'PUBLIC_ANNOUNCEMENT' ) : L ( 'PUBLIC_FOOTER_ARTICLE' );

        if (! empty ( $_GET ['id'] )) {
            $this->assign ( 'pageTitle', L ( 'PUBLIC_EDIT' ) . $title );
            $detail = model ( 'Xarticle' )->where ( 'id=' . intval ( $_GET ['id'] ) )->find ();
            $detail ['attach'] = str_replace ( '|', ',', $detail ['attach'] );
        } else {
            $this->assign ( 'pageTitle', L ( 'PUBLIC_ADD' ) . $title );
            $detail = array ();
        }
        $detail ['type'] = $type;
        $this->pageKeyList = array (
            'id',
            'title',
            'content',
            'attach',
            'type'
        );
        $this->savePostUrl = U ( 'admin/Config/doaddArticle' );
        $this->notEmpty = array (
            'title',
            'content'
        );
        $this->onsubmit = 'admin.checkAddArticle(this)';
        $this->displayConfig ( $detail );
    }
    // 添加公告
    public function doaddArticle() {
        $_POST ['type'] = 1;

        if (model ( 'Xarticle' )->saveArticle ( $_POST )) {
            $data ['title'] = t ( $_POST ['title'] );
            $data ['k'] = $_POST ['type'] == 1 ? L ( 'PUBLIC_TITLE_ACCENT_SAVEEDIT' ) : L ( 'PUBLIC_TITLE_FILES_SAVEEDIT' );
            LogRecord ( 'admin_content', 'addArticle', $data, true );
            $jumpUrl = $_POST ['type'] == 1 ? U ( 'admin/Config/announcement' ) : U ( 'admin/Config/footer' );
            $this->assign ( 'jumpUrl', $jumpUrl );
            $this->success ( L ( 'PUBLIC_ADMIN_OPRETING_SUCCESS' ) );
        } else {
            $this->error ( model ( 'Xarticle' )->getError () );
        }
    }
    // 刪除公告
    public function delArticle() {
        $title = $_POST ['type'] == 1 ? L ( 'PUBLIC_ANNOUNCEMENT' ) : L ( 'PUBLIC_FOOTER_ARTICLE' );
        $return = array (
            'status' => 1,
            'data' => $title . L ( 'PUBLIC_DELETE_SUCCESS' )
        );
        $id = $_POST ['id'];
        if ($res = model ( 'Xarticle' )->delArticle ( $id )) {
            if ($_POST ['type'] == 1) {
                LogRecord ( 'admin_content', 'delArticle', array (
                    'ids' => $id,
                    'k' => L ( 'PUBLIC_STREAM_DELETE' ) . $title
                ), true );
            } else {
                LogRecord ( 'admin_config', 'delFooter', array (
                    'ids' => $id,
                    'k' => L ( 'PUBLIC_STREAM_DELETE' ) . $title
                ), true );
            }
        } else {
            $error = model ( 'Xarticle' )->getError ();
            empty ( $error ) && $error = $title . L ( 'PUBLIC_DELETE_FAIL' );
            $return = array (
                'status' => 0,
                'data' => $error
            );
        }
        echo json_encode ( $return );
        exit ();
    }

    /**
     * 系統配置 - 郵件配置
     */
    public function email() {
        $this->pageKeyList = array (
            'email_sendtype',
            'email_host',
            'email_ssl',
            'email_port',
            'email_account',
            'email_password',
            'email_sender_name',
            'email_sender_email',
            'email_test'
        );

        $this->opt ['email_sendtype'] = array (
            'smtp' => 'smtp'
        );
        $this->opt ['email_ssl'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );

        $this->displayConfig ();
    }

    /**
     * 系統配置 - 附件配置
     */
    public function attach() {
        $this->pageTitle ['attach'] = L ( 'PUBLIC_ATTACH_CONFIG' );
        // Tab選項
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ATTACH_CONFIG' ),
            'tabHash' => 'attach',
            'url' => U ( 'admin/Config/attach' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDIMAGE_CONFIG' ),
            'tabHash' => 'cloudimage',
            'url' => U ( 'admin/Config/cloudimage' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDATTACH_CONFIG' ),
            'tabHash' => 'cloudattach',
            'url' => U ( 'admin/Config/cloudattach' )
        );

        $this->pageKeyList = array (
            'attach_path_rule',
            'attach_max_size',
            'attach_allow_extension'
        );
        $this->displayConfig ();
    }

    /**
     * 系統配置 - 附件配置 - 又拍雲圖片
     */
    public function cloudimage() {
        $this->pageTitle ['cloudimage'] = L ( 'PUBLIC_ATTACH_CONFIG' );
        // Tab選項
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ATTACH_CONFIG' ),
            'tabHash' => 'attach',
            'url' => U ( 'admin/Config/attach' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDIMAGE_CONFIG' ),
            'tabHash' => 'cloudimage',
            'url' => U ( 'admin/Config/cloudimage' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDATTACH_CONFIG' ),
            'tabHash' => 'cloudattach',
            'url' => U ( 'admin/Config/cloudattach' )
        );

        $this->opt ['cloud_image_open'] = array (
            '1' => L ( 'PUBLIC_OPEN' ),
            '0' => L ( 'PUBLIC_CLOSE' )
        );

        $this->pageKeyList = array (
            'cloud_image_open',
            'cloud_image_api_url',
            'cloud_image_bucket',
            'cloud_image_form_api_key',
            'cloud_image_prefix_urls',
            'cloud_image_admin',
            'cloud_image_password'
        );

        $this->displayConfig ();
    }

    /**
     * 系統配置 - 附件配置 - 又拍雲附件
     */
    public function cloudattach() {
        $this->pageTitle ['cloudattach'] = L ( 'PUBLIC_ATTACH_CONFIG' );
        // Tab選項
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ATTACH_CONFIG' ),
            'tabHash' => 'attach',
            'url' => U ( 'admin/Config/attach' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDIMAGE_CONFIG' ),
            'tabHash' => 'cloudimage',
            'url' => U ( 'admin/Config/cloudimage' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_CLOUDATTACH_CONFIG' ),
            'tabHash' => 'cloudattach',
            'url' => U ( 'admin/Config/cloudattach' )
        );

        $this->opt ['cloud_attach_open'] = array (
            '1' => L ( 'PUBLIC_OPEN' ),
            '0' => L ( 'PUBLIC_CLOSE' )
        );

        $this->pageKeyList = array (
            'cloud_attach_open',
            'cloud_attach_api_url',
            'cloud_attach_bucket',
            'cloud_attach_form_api_key',
            'cloud_attach_prefix_urls',
            'cloud_attach_admin',
            'cloud_attach_password'
        );
        $this->displayConfig ();
    }

    /**
     * 系統配置 - 過濾配置
     */
    public function audit() {
        $this->pageKeyList = array (
            'open',
            'keywords',
            'replace'
        );
        $this->opt ['open'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        $this->savePostUrl = U ( 'admin/Config/doaudit' );
        $detail = model ( 'Xdata' )->get ( $this->systemdata_list . ":" . $this->systemdata_key );
        $detail ['keywords'] = model ( 'Xdata' )->get ( 'keywordConfig' ); // 敏感詞的Key
        $this->displayConfig ( $detail );
    }

    /**
     * 儲存敏感詞設定，敏感詞單獨存放
     *
     * @return [type] [description]
     */
    public function doaudit() {
        // 存儲敏感詞
        $data = $_POST ['keywords'];
        if (model ( 'Xdata' )->put ( 'keywordConfig', $data )) {
            unset ( $_POST ['keywords'] );
            $this->saveConfigData ();
        } else {
            $this->error ( L ( 'PUBLIC_SENSITIVE_SAVE_FAIL' ) );
        }
    }

    /**
     * 系統配置 - 頂部導航配置 - 導航列表
     */
    public function nav() {
        $this->pageKeyList = array (
            'navi_id',
            'navi_name',
            'app_name',
            'url',
            'target',
            'status',
            'position',
            'guest',
            'is_app_navi',
            'parent_id',
            'order_sort',
            'DOACTION'
        );
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_ADD' ),
            'onclick' => "javascript:location.href='" . U ( 'admin/Config/navAdd', 'addtype=1&tabHash=type' ) . "'"
        );
        // Tab選項
        $this->pageTab [] = array (
            'title' => '頂部導航',
            'tabHash' => 'rule',
            'url' => U ( 'admin/Config/nav' )
        );
        $this->pageTab [] = array (
            'title' => '底部導航',
            'tabHash' => 'foot',
            'url' => U ( 'admin/Config/footNav' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ADD_NAVIGATION' ),
            'tabHash' => 'type',
            'url' => U ( 'admin/Config/navAdd' )
        );
        // 列表分頁欄按鈕
        $this->opt ['target'] = array (
            '_blank' => L ( 'PUBLIC_NEW_WINDOW' ),
            '_self' => L ( 'PUBLIC_CURRENT_WINDOW' ),
            '_parent' => L ( 'PUBLIC_PARENT_WINDOW' )
        );
        $this->opt ['position'] = array (
            '0' => L ( 'PUBLIC_HEAD_NAVIGATION' ),
            '1' => L ( 'PUBLIC_BOTTOM_NAVIGATION' ),
            '2' => L ( 'PUBLIC_INTERNAL_APPLICATION' )
        );
        $this->opt ['status'] = array (
            '0' => L ( 'PUBLIC_CLOSE' ),
            '1' => L ( 'PUBLIC_OPEN' )
        );
        $this->opt ['is_app_navi'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        // 資料的格式化與listKey保持一致
        $listData = model ( 'Navi' )->where ( 'position=0' )->order ( "order_sort asc" )->findPage ( 20 );

        $firstdata = array ();
        $seconddata = array ();
        foreach ( $listData ['data'] as $lv ) {
            if ($lv ['parent_id'] == '0') {
                $firstdata [] = $lv;
            } else {
                $seconddata [$lv ['parent_id']] [] = $lv;
            }
        }
        $finaldata = array ();
        foreach ( $firstdata as $fv ) {
            $finaldata [] = $fv;
            if ($seconddata [intval ( $fv ['navi_id'] )]) {
                foreach ( $seconddata [$fv ['navi_id']] as $sv ) {
                    $finaldata [] = $sv;
                }
            }
        }

        foreach ( $finaldata as &$v ) {
            $v ['target'] = $this->opt ['target'] [$v ['target']];
            $v ['status'] = $this->opt ['status'] [$v ['status']];
            $v ['position'] = $this->opt ['position'] [$v ['position']];
            $v ['is_app_navi'] = $this->opt ['is_app_navi'] [$v ['is_app_navi']];
            $v ['guest'] = $v ['guest'] = '0' ? L ( 'PUBLIC_SYSTEMD_FALSE' ) : L ( 'PUBLIC_SYSTEMD_TRUE' );
            $v ['url'] = str_replace ( '{website}', SITE_URL, $v ['url'] );
            $v ['parent_id'] && $v ['navi_name'] = '┗ ' . $v ['navi_name'];
            // $v['parent_id'] = $v['parent_id'] = '' ? $v['parent_id'] = '無' : $v['guest'] = '有';
            if ($v ['parent_id'] <= 0) {
                $v ['DOACTION'] = '<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id'],
                    'type' => 'son',
                    'tabHash' => 'type'
                ) ) . '" >' . L ( 'PUBLIC_ADD_SUBNAVIGATION' ) . '</a>&nbsp;-&nbsp;<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id']
                ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>&nbsp;-&nbsp;<a href="javascript:admin.delnav(\'' . $v ['navi_id'] . '\')">' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
            } else {
                $v ['DOACTION'] = '<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id'],
                    'tabHash' => 'type'
                ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>&nbsp;-&nbsp;<a href="javascript:admin.delnav(\'' . $v ['navi_id'] . '\')" >' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
            }
        }
        $listData ['data'] = $finaldata;
        $this->allSelected = false;
        $this->displayList ( $listData );
    }
    /**
     * 系統配置 - 底部導航配置 - 導航列表
     */
    public function footNav() {
        $this->pageKeyList = array (
            'navi_id',
            'navi_name',
            'app_name',
            'url',
            'target',
            'status',
            'position',
            'guest',
            'is_app_navi',
            'parent_id',
            'order_sort',
            'DOACTION'
        );
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_ADD' ),
            'onclick' => "javascript:location.href='" . U ( 'admin/Config/navAdd', 'addtype=2&tabHash=type' ) . "'"
        );
        // Tab選項
        $this->pageTab [] = array (
            'title' => '頂部導航',
            'tabHash' => 'rule',
            'url' => U ( 'admin/Config/nav' )
        );
        $this->pageTab [] = array (
            'title' => '底部導航',
            'tabHash' => 'foot',
            'url' => U ( 'admin/Config/footNav' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ADD_NAVIGATION' ),
            'tabHash' => 'type',
            'url' => U ( 'admin/Config/navAdd' )
        );
        // 列表分頁欄按鈕
        $this->opt ['target'] = array (
            '_blank' => L ( 'PUBLIC_NEW_WINDOW' ),
            '_self' => L ( 'PUBLIC_CURRENT_WINDOW' ),
            '_parent' => L ( 'PUBLIC_PARENT_WINDOW' )
        );
        $this->opt ['position'] = array (
            '0' => L ( 'PUBLIC_HEAD_NAVIGATION' ),
            '1' => L ( 'PUBLIC_BOTTOM_NAVIGATION' ),
            '2' => L ( 'PUBLIC_INTERNAL_APPLICATION' )
        );
        $this->opt ['status'] = array (
            '0' => L ( 'SSC_CLOSE' ),
            '1' => L ( 'PUBLIC_OPEN' )
        );
        $this->opt ['is_app_navi'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        // 資料的格式化與listKey保持一致
        $listData = model ( 'Navi' )->where ( 'position=1' )->order ( "order_sort asc" )->findPage ( 20 );

        $firstdata = array ();
        $seconddata = array ();
        foreach ( $listData ['data'] as $lv ) {
            if ($lv ['parent_id'] == '0') {
                $firstdata [] = $lv;
            } else {
                $seconddata [$lv ['parent_id']] [] = $lv;
            }
        }
        $finaldata = array ();
        foreach ( $firstdata as $fv ) {
            $finaldata [] = $fv;
            if ($seconddata [intval ( $fv ['navi_id'] )]) {
                foreach ( $seconddata [$fv ['navi_id']] as $sv ) {
                    $finaldata [] = $sv;
                }
            }
        }
        foreach ( $finaldata as &$v ) {
            $v ['target'] = $this->opt ['target'] [$v ['target']];
            $v ['status'] = $this->opt ['status'] [$v ['status']];
            $v ['position'] = $this->opt ['position'] [$v ['position']];
            $v ['is_app_navi'] = $this->opt ['is_app_navi'] [$v ['is_app_navi']];
            $v ['guest'] = $v ['guest'] = '0' ? L ( 'PUBLIC_SYSTEMD_FALSE' ) : L ( 'PUBLIC_SYSTEMD_TRUE' );
            $v ['url'] = str_replace ( '{website}', SITE_URL, $v ['url'] );
            $v ['parent_id'] && $v ['navi_name'] = '┗ ' . $v ['navi_name'];
            // $v['parent_id'] = $v['parent_id'] = '' ? $v['parent_id'] = '無' : $v['guest'] = '有';
            if ($v ['parent_id'] <= 0) {
                $v ['DOACTION'] = '<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id'],
                    'type' => 'son',
                    'tabHash' => 'type',
                    'addtype' => 2
                ) ) . '" >' . L ( 'PUBLIC_ADD_SUBNAVIGATION' ) . '</a>&nbsp;-&nbsp;<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id'],
                    'tabHash' => 'type',
                    'addtype' => 2
                ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>&nbsp;-&nbsp;<a href="javascript:admin.delnav(\'' . $v ['navi_id'] . '\')">' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
            } else {
                $v ['DOACTION'] = '<a href="' . U ( 'admin/Config/navAdd', array (
                    'id' => $v ['navi_id'],
                    'tabHash' => 'type',
                    'addtype' => 2
                ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>&nbsp;-&nbsp;<a href="javascript:admin.delnav(\'' . $v ['navi_id'] . '\')" >' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
            }
        }
        $listData ['data'] = $finaldata;
        $this->allSelected = false;
        $this->displayList ( $listData );
    }
    /**
     * 導航配置的添加和修改
     */
    public function doNav() {
        $map ['navi_name'] = t ( $_POST ['navi_name'] );
        $map ['app_name'] = t ( $_POST ['app_name'] );
        $map ['url'] = t ( $_POST ['url'] );
        $map ['target'] = t ( $_POST ['target'] );
        $map ['status'] = intval ( $_POST ['status'] );
        $map ['position'] = t ( $_POST ['position'] );
        $map ['guest'] = intval ( $_POST ['guest'] );
        $map ['is_app_navi'] = intval ( $_POST ['is_app_navi'] );
        $map ['order_sort'] = intval ( $_POST ['order_sort'] );
        $map ['navi_name'] = t ( $_POST ['navi_name'] );
        $map ['app_name'] = t ( $_POST ['app_name'] );
        $map ['url'] = t ( $_POST ['url'] );

        if ($map ['navi_name'] == '') {
            $this->error ( L ( 'PUBLIC_NAVIGATION_NAME_NOEWPTY' ) );
        }
        if ($map ['app_name'] == '') {
            $this->error ( '英文名稱不能為空' );
        }
        if ($map ['url'] == '') {
            $this->error ( L ( 'PUBLIC_LINK_NOEMPTY' ) );
        }
        if ($map ['position'] == '') {
            $this->error ( L ( 'PUBLIC_NAVIGATION_POSITION_NOEWPTY' ) );
        }
        if ($map ['order_sort'] == '') {
            $this->error ( L ( 'PUBLIC_APPLICATION_SORT_NOEMPTY' ) );
        }

        if ($_GET ['type']) {
            $map ['parent_id'] = intval ( $_GET ['id'] );
            $rel = model ( 'Navi' )->add ( $map );
        } else {
            if (! $_GET ['id']) {
                $map ['parent_id'] = 0;
                $rel = model ( 'Navi' )->add ( $map );
            } else {
                $rel = model ( 'Navi' )->where ( 'navi_id=' . $_GET ['id'] )->save ( $map );
            }
            $rel = true;
        }
        if ($rel) {
            // 清除導航快取
            model ( 'Navi' )->cleanCache ();

            $jumpstr = 'nav';
            $map ['position'] && $jumpstr = "footNav&tabHash=foot";
            $this->assign ( 'jumpUrl', U ( 'admin/Config/' . $jumpstr ) );
            $this->success ( L ( 'PUBLIC_ADMIN_OPRETING_SUCCESS' ) );
        } else {
            $this->error ( model ()->getError () );
        }
    }

    /**
     * 系統配置 - 導航配置 - 增加導航
     */
    public function navAdd() {
        $addtype = $_GET ['addtype'] ? $_GET ['addtype'] : 1;
        // 頂部導航
        if ($addtype == 1) {
            $defaultdata ['position'] = 0;
        } else {
            $defaultdata ['position'] = 1;
        }
        $this->pageKeyList = array (
            'navi_name',
            'app_name',
            'url',
            'target',
            'status',
            'position',
            'guest',
            'is_app_navi',
            'order_sort'
        );
        $this->pageTab [] = array (
            'title' => '頂部導航',
            'tabHash' => 'rule',
            'url' => U ( 'admin/Config/nav' )
        );
        $this->pageTab [] = array (
            'title' => '底部導航',
            'tabHash' => 'foot',
            'url' => U ( 'admin/Config/footNav' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_ADD_NAVIGATION' ),
            'tabHash' => 'type',
            'url' => U ( 'admin/Config/navAdd' )
        );

        $this->opt ['target'] = array (
            '_blank' => L ( 'PUBLIC_NEW_WINDOW' ),
            '_self' => L ( 'PUBLIC_CURRENT_WINDOW' ),
            '_parent' => L ( 'PUBLIC_PARENT_WINDOW' )
        );
        $this->opt ['position'] = array (
            '0' => L ( 'PUBLIC_HEAD_NAVIGATION' ),
            '1' => L ( 'PUBLIC_BOTTOM_NAVIGATION' ),
            '2' => L ( 'PUBLIC_INTERNAL_APPLICATION' )
        );
        $this->opt ['status'] = array (
            '0' => L ( 'PUBLIC_CLOSE' ),
            '1' => L ( 'PUBLIC_OPEN' )
        );
        $this->opt ['is_app_navi'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        $this->opt ['status'] = array (
            '0' => L ( 'PUBLIC_CLOSE' ),
            '1' => L ( 'PUBLIC_OPEN' )
        );
        $this->opt ['guest'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        $this->opt ['is_app_navi'] = array (
            '0' => L ( 'PUBLIC_SYSTEMD_FALSE' ),
            '1' => L ( 'PUBLIC_SYSTEMD_TRUE' )
        );
        $this->opt ['target'] = array (
            '_blank' => L ( 'PUBLIC_NEW_WINDOW' ),
            '_self' => L ( 'PUBLIC_CURRENT_WINDOW' ),
            '_parent' => L ( 'PUBLIC_PARENT_WINDOW' )
        );
        // $this->opt['position'] = array('0'=>L('PUBLIC_HEAD_NAVIGATION'),'1'=>L('PUBLIC_BOTTOM_NAVIGATION'),'2'=>L('PUBLIC_INTERNAL_APPLICATION'));
        $addtitle = $addtype == 1 ? L ( 'PUBLIC_HEAD_NAVIGATION' ) : L ( 'PUBLIC_BOTTOM_NAVIGATION' );
        $this->opt ['position'] = isset ( $_GET ['id'] ) ? array (
            $defaultdata ['position'] => $addtitle
        ) : array (
            '0' => L ( 'PUBLIC_HEAD_NAVIGATION' ),
            '1' => L ( 'PUBLIC_BOTTOM_NAVIGATION' ),
            '2' => L ( 'PUBLIC_INTERNAL_APPLICATION' )
        );
        $this->notEmpty = array (
            'navi_name',
            'app_name',
            'url',
            'position',
            'order_sort'
        );
        $this->onsubmit = 'admin.checkNavInfo(this)';

        if (! $_GET ['type']) {
            if (! empty ( $_GET ['id'] )) {
                $editnav = model ( 'Navi' )->where ( 'navi_id=' . $_GET ['id'] )->find ();
                $this->savePostUrl = U ( 'admin/Config/doNav&id=' . $_GET ['id'] );
                $this->displayConfig ( $editnav );
            } else {
                $this->savePostUrl = U ( 'admin/Config/doNav' );
                $this->displayConfig ( $defaultdata );
            }
        } else {
            $this->savePostUrl = U ( 'admin/Config/doNav&id=' . $_GET ['id'] . '&type=son' );
            $this->displayConfig ( $defaultdata );
        }
    }

    /**
     * 刪除導航操作
     */
    public function delNav() {
        $rel = model ( 'Navi' )->where ( 'navi_id=' . intval ( $_POST ['id'] ) . ' OR parent_id=' . intval ( $_POST ['id'] ) )->delete ();
        if ($rel) {
            // 清除導航快取
            model ( 'Navi' )->cleanCache ();
            $return = array (
                'status' => 1,
                'data' => L ( 'PUBLIC_DELETE_SUCCESS' )
            );
        } else {
            $error = model ( 'Navi' )->getError ();
            $return = array (
                'status' => 0,
                'data' => $error
            );
        }
        exit ( json_encode ( $return ) );
    }

    /**
     * 頁尾文章配置
     * 與公告資料存在同一張表中
     */
    public function footer() {
        $this->announcement ( 2 );
    }

    /**
     * 系統配置 - 微博配置
     */
    public function feed() {
        $this->pageKeyList = array (
            'weibo_nums',
            'weibo_type',
            'weibo_premission',
            'weibo_send_info',
            'weibo_default_topic',
            'weibo_at_me'
        );
        $this->opt ['weibo_type'] = array (
            'face' => '表情',
            'at' => '好友',
            'image' => L ( 'PUBLIC_IMAGE_STREAM' ),
            'video' => '視訊',
            'file' => L ( 'PUBLIC_FILE_STREAM' ),
            'topic' => '話題',
            'contribute' => '投稿'
        );
        $this->opt ['weibo_premission'] = array (
            'repost' => L ( 'PUBLIC_SHARE_WEIBO' ),
            'comment' => L ( 'PUBLIC_CONCENT_WEIBO' ),
            'audit' => '先審後發'
        );
        $this->opt ['weibo_at_me'] = array (
            0 => '全站使用者',
            1 => '關注使用者'
        );
        $this->displayConfig ();
    }

    /**
     * 系統配置 - 地區配置
     */
    public function area() {
        $this->pageTitle ['area'] = '地區配置';
        $_GET ['pid'] = intval ( $_GET ['pid'] );
        $treeData = model ( 'CategoryTree' )->setTable ( 'area' )->getNetworkList ();

        $this->displayTree ( $treeData, 'area', 3 );
    }

    /**
     * 添加地區頁面
     */
    public function addArea() {
        $this->assign ( 'pid', intval ( $_GET ['pid'] ) );
        $this->display ( 'editArea' );
    }

    /**
     * 編輯地區頁面
     */
    public function editArea() {
        $_GET ['area_id'] = intval ( $_GET ['area_id'] );
        $area = model ( 'Area' )->where ( 'area_id=' . $_GET ['area_id'] )->find ();
        $area ['area_id'] = $_GET ['area_id'];
        $this->assign ( 'area', $area );
        $this->display ();
    }

    /**
     * 添加地區操作
     */
    public function doAddArea() {
        $_POST ['title'] = t ( $_POST ['title'] );
        $_POST ['pid'] = intval ( $_POST ['pid'] );
        if (empty ( $_POST ['title'] )) {
            echo 0;
            return;
        }
        echo ($res = model ( 'Area' )->add ( $_POST )) ? $res : '0';
        model ( 'Area' )->remakeCityCache ();
    }

    /**
     * 編輯地區操作
     */
    public function doEditArea() {
        $_POST ['title'] = t ( $_POST ['title'] );
        $_POST ['area_id'] = intval ( $_POST ['area_id'] );
        if (empty ( $_POST ['title'] )) {
            echo 0;
            return;
        }
        echo model ( 'Area' )->where ( '`area_id`=' . $_POST ['area_id'] )->setField ( 'title', $_POST ['title'] ) ? '1' : '0';
        model ( 'Area' )->remakeCityCache ();
    }

    /**
     * 刪除地區操作
     */
    public function doDeleteArea() {
        $_POST ['ids'] = explode ( ',', t ( $_POST ['ids'] ) );
        if (empty ( $_POST ['ids'] )) {
            echo 0;
            return;
        }
        $map ['area_id'] = array (
            'IN',
            $_POST ['ids']
        );
        echo model ( 'Area' )->where ( $map )->delete () ? '1' : '0';
        model ( 'Area' )->remakeCityCache ();
    }

    /**
     * 系統配置 - 語言配置
     */
    public function lang() {
        $this->_listpk = 'lang_id';
        // 列表key值 DOACTION表示操作
        $pageKey = array (
            'lang_id',
            'key',
            'appname',
            'filetype'
        );
        $langType = model ( 'Lang' )->getLangType ();
        $pageKeyList = array_merge ( $pageKey, $langType );
        array_push ( $pageKeyList, 'DOACTION' );
        $this->pageKeyList = $pageKeyList;
        // 添加語言配置內容按鈕
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_ADD' ),
            'onclick' => "admin.updateLangContent(0)"
        );
        // 刪除語言配置內容按鈕
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_STREAM_DELETE' ),
            'onclick' => "admin.deleteLangContent(this)"
        );
        // 搜索key值 - 列表分頁欄 按鈕 搜索
        $this->searchKey = array (
            'key',
            'appname',
            'filetype',
            'content'
        );
        $this->opt ['filetype'] = array (
            0 => L ( 'PUBLIC_PHP_FILE' ),
            1 => L ( 'PUBLIC_JAVASCRIPT_FILE' )
        );
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_SEARCH_INDEX' ),
            'onclick' => "admin.fold('search_form')"
        );
        // 對比語言檔案
        $this->pageButton [] = array (
            'title' => '線上更新',
            'onclick' => "admin.compareLangFile(0)"
        );
        $listData = $this->_getLangContent ();
        $this->displayList ( $listData );
    }

    /**
     * 對比語言檔案
     */
    public function compareLangFile() {
        /* 下載檔案-begin */
        tsload ( ADDON_PATH . '/library/Update.class.php' );
        $updateClass = new Update ();

        $packageURL = C ( 'TS_UPDATE_SITE' ) . '/data/lang/langForLoadUpadte.zip';

        $updateClass->downloadFile ( $packageURL );
        $updateClass->unzipPackage ( 'langForLoadUpadte.zip', LANG_PATH, false );
        /* 下載檔案-end */

        $langFileArr = include LANG_PATH . '/langForLoadUpadte.php';
        $this->pageTitle [ACTION_NAME] = '對比語言檔案';
        $this->pageKeyList = array (
            'key',
            'appname',
            'filetype',
            'zh-cn',
            'en',
            'zh-tw',
            'DOACTION'
        );
        $this->pageButton [] = array (
            'title' => '同步',
            'onclick' => "admin.updateLang(this)"
        );
        $this->_listpk = 'lang_id';
        foreach ( $langFileArr as $key => $val ) {
            $keys = explode ( '-', $key ); // $keys[0]:key $keys[1]:appname $keys[2]:filetype
            $map ['key'] = $keys [0];
            $map ['appname'] = $keys [1];
            $map ['filetype'] = $keys [2];
            if ($res = model ( 'Lang' )->where ( $map )->find ()) {
                $val [0] = htmlspecialchars_decode ( $val [0], ENT_QUOTES );
                $val [1] = htmlspecialchars_decode ( $val [1], ENT_QUOTES );
                $val [2] = htmlspecialchars_decode ( $val [2], ENT_QUOTES );
                if (($val [0] != $res ['zh-cn']) || ($val [1] != $res ['en']) || ($val [2] != $res ['zh-tw'])) {
                    if ($val [0] != $res ['zh-cn']) {
                        $arr [$key] ['zh-cn'] = '<font color="red">' . t ( $res ['zh-cn'] ) . '</font>';
    } else {
        $arr [$key] ['zh-cn'] = t ( $res ['zh-cn'] );
    }
    if ($val [1] != $res ['en']) {
        $arr [$key] ['en'] = '<font color="red">' . t ( $res ['en'] ) . '</font>';
    } else {
        $arr [$key] ['en'] = $res ['en'];
    }
    if ($val [2] != $res ['zh-tw']) {
        $arr [$key] ['zh-tw'] = '<font color="red">' . t ( $res ['zh-tw'] ) . '</font>';
    } else {
        $arr [$key] ['zh-tw'] = $res ['zh-tw'];
    }
    $arr [$key] ['lang_id'] = $res ['key'] . '-' . $res ['appname'] . '-' . $res ['filetype'];
    $arr [$key] ['key'] = $res ['key'];
    $arr [$key] ['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.updateLang(\'' . $arr [$key] ['lang_id'] . '\')">修改</a>';
    }
    } else {
        $arr [$key] ['zh-cn'] = $val [0];
        $arr [$key] ['en'] = $val [1];
        $arr [$key] ['zh-tw'] = $val [2];
        $arr [$key] ['lang_id'] = $key;
        $arr [$key] ['key'] = $keys ['0'];
        $arr [$key] ['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.updateLang(\'' . $arr [$key] ['lang_id'] . '\')">添加</a>';
    }
    unset ( $map );
    }
    $listData ['data'] = $arr;
    $this->displayList ( $listData );
    }

    /**
     * 同步
     */
    public function updateLang() {
        $lang_id = explode ( ',', $_POST ['lang_id'] );
        $langFileArr = include LANG_PATH . '/langForLoadUpadte.php';
        foreach ( $lang_id as $k => $v ) {
            if ($langFileArr [$v]) {
                $keys = explode ( '-', $v );
                $map ['key'] = $keys [0];
                $map ['appname'] = $keys [1];
                $map ['filetype'] = $keys [2];
                $data ['zh-cn'] = htmlspecialchars_decode ( $langFileArr [$v] [0], ENT_QUOTES );
                $data ['en'] = htmlspecialchars_decode ( $langFileArr [$v] [1], ENT_QUOTES );
                $data ['zh-tw'] = htmlspecialchars_decode ( $langFileArr [$v] [2], ENT_QUOTES );
                if ($lang = model ( 'Lang' )->where ( $map )->find ()) {
                    model ( 'Lang' )->where ( $map )->save ( $data );
    } else {
        $data = array_merge ( $data, $map );
        model ( 'Lang' )->add ( $data );
    }
    }
    }
    $data ['status'] = 1;
    $data ['data'] = L ( 'PUBLIC_ADMIN_OPRETING_SUCCESS' );

    // 更新快取
    $cacheFile = C ( 'F_CACHE_PATH' ) . '/initSiteLang.lock.php';
    unlink ( $cacheFile );

    exit ( json_encode ( $data ) );
    }

    /**
     * 添加，編輯語言配置內容
     */
    public function updateLangContent() {
        $sid = intval ( $_GET ['sid'] );
        if ($sid == 0) {
            $this->pageTitle [ACTION_NAME] = L ( 'PUBLIC_ADD_LANGUAGE_CONFIGURATION' );
    } else {
        $this->pageTitle [ACTION_NAME] = L ( 'PUBLIC_EDIT_LANGUAGE_CONFIGURATION' );
        // 獲取內容
        $detail = model ( 'Lang' )->getLangSetInfo ( $sid );
    }
    // 列表key值 DOACTION表示操作
    $pageKey = array (
        'key',
        'appname',
        'filetype'
    );
    $langType = model ( 'Lang' )->getLangType ();
    $pageKeyList = array_merge ( $pageKey, $langType );
    $this->pageKeyList = $pageKeyList;
    // 配置選項資料
    $this->opt ['filetype'] = array (
        0 => L ( 'PUBLIC_PHP_FILE' ),
                1 => L ( 'PUBLIC_JAVASCRIPT_FILE' )
            );
    // 表單連結設定
    $this->savePostUrl = U ( 'admin/Config/doUpdateLangContent' ) . '&sid=' . $sid;
    $this->displayConfig ( $detail );
    }

    /**
     * 編輯語言配置內容
     */
    public function doUpdateLangContent() {
        $sid = intval ( $_GET ['sid'] );
        $postData = $_POST;

        unset ( $postData ['systemdata_list'] );
        unset ( $postData ['systemdata_key'] );
        unset ( $postData ['pageTitle'] );
        $validkey = preg_match ( '/^[A-Z0-9_-]+$/i', $_POST ['key'] );
        if (! $validkey) {
            $this->error ( '語言KEY裡包含非法字元，請重新填寫！' );
    }
    $validappname = preg_match ( '/^[A-Z0-9_-]+$/i', $_POST ['appname'] );
    if (! $validappname) {
        $this->error ( '應用名稱裡包含非法字元，請重新填寫！' );
    }
    $result = model ( 'Lang' )->updateLangData ( $postData, $sid );
    $jumpUrl = U ( 'admin/Config/lang' );
    $this->assign ( 'jumpUrl', $jumpUrl );
    switch ($result) {
    case 0 :
        $this->error ( L ( 'PUBLIC_ADMIN_OPRETING_ERROR' ) );
        break;
    case 1 :
        $this->success ( L ( 'PUBLIC_ADMIN_OPRETING_SUCCESS' ) );
        break;
    case 2 :
        $this->error ( L ( 'PUBLIC_LANGUAGE_CONFIGURATION_ALREADY_EXIST' ) );
        break;
    }
    }

    /**
     * 刪除語言配置內容
     */
    public function deleteLangContent() {
        $ids = t ( $_POST ['lang_id'] );
        $id = explode ( ',', $ids );
        $result = model ( 'Lang' )->deleteLangData ( $id );
        if ($result === false) {
            $data ['status'] = 0;
            $data ['data'] = L ( 'PUBLIC_DELETE_FAIL' );
    } else {
        $data ['status'] = 1;
        $data ['data'] = L ( 'PUBLIC_DELETE_SUCCESS' );
    }
    exit ( json_encode ( $data ) );
    }

    /**
     * 獲取語言列表資料
     */
    private function _getLangContent() {
        $langType = model ( 'Lang' )->getLangType ();
        // 獲取查詢條件
        $map = $this->getSearchPost ();
        // 組裝查詢條件
        ! empty ( $map ['key'] ) && $_map ['key'] = array (
            'LIKE',
            '%' . $map ['key'] . '%'
        );
        ! empty ( $map ['appname'] ) && $_map ['appname'] = array (
            'LIKE',
            '%' . $map ['appname'] . '%'
        );
        isset ( $map ['filetype'] ) && $_map ['filetype'] = intval ( $map ['filetype'] );
        if (! empty ( $map ['content'] )) {
            $where ['_logic'] = 'OR';
            foreach ( $langType as $k ) {
                $where [$k] = array (
                    'LIKE',
                    '%' . t ( $map ['content'] ) . '%'
                );
    }
    $_map ['_complex'] = $where;
    }

    $listData = model ( 'Lang' )->getLangContent ( $_map );

    foreach ( $listData ['data'] as &$value ) {
        foreach ( $langType as &$v ) {
            $value [$v] = t ( $value [$v] );
    }
    $value ['filetype'] = ($value ['filetype'] == 1) ? L ( 'PUBLIC_JAVASCRIPT_FILE' ) : L ( 'PUBLIC_PHP_FILE' );
    $value ['DOACTION'] = '<a href="' . U ( 'admin/Config/updateLangContent', array (
        'sid' => $value ['lang_id']
    ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a><a href="javascript:void(0)" onclick="admin.deleteLangContent(' . $value ['lang_id'] . ')">' . L ( 'PUBLIC_STREAM_DELETE' ) . '</a>';
    }

    return $listData;
    }
    public function diylist() {
        $this->pageKeyList = array (
            'id',
            'desc',
            'widget_list',
            'DOACTION'
        );

        // 添加語言配置內容按鈕
        $this->pageButton [] = array (
            'title' => L ( 'PUBLIC_UPDATE_WIDGET' ),
                'onclick' => "admin.updateWidget()"
            );

        $this->allSelected = false;
        $data = model ( 'Widget' )->getDiyList ();

        foreach ( $data as &$v ) {
            $widget_list = unserialize ( $v ['widget_list'] );
            $v ['widget_list'] = '';
            foreach ( $widget_list as $vv ) {
                $v ['widget_list'] .= $vv ['appname'] . ':' . $vv ['name'] . 'Widget<br/>';
    }
    $v ['DOACTION'] = '<a href="javascript:admin.configWidget(' . $v ['id'] . ')">' . L ( 'PUBLIC_SETTING' ) . '</a>';
    }

    $listData ['data'] = $data;

    $this->displayList ( $listData );
    }

    /**
     * 系統配置 - 訊息配置
     */
    public function notify() {
        $type = isset ( $_GET ['type'] ) ? intval ( $_GET ['type'] ) : 1;
        // echo $type;exit;
        $this->pageTab [] = array (
            'title' => '使用者訊息配置',
            'tabHash' => 'notify_user',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 1
            ) )
        );
        $this->pageTab [] = array (
            'title' => '管理員訊息配置',
            'tabHash' => 'notify_admin',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 2
            ) )
        );
        $this->pageTab [] = array (
            'title' => '增加訊息節點',
            'tabHash' => 'addNotifytpl',
            'url' => U ( 'admin/Config/addNotifytpl' )
        );
        // $this->pageTab[] = array('title'=>L('PUBLIC_MESSING_SENTTO'),'tabHash'=>'notifyEmail','url'=>U('admin/Config/sendNotifyEmail'));
        // $d['nodeList'] = model('Notify')->getNodeList($type); 通過快取讀取列表，務必保留，以後會用到
        $d ['nodeList'] = D ( 'notify_node' )->where ( 'type=' . $type )->findAll ();
        $this->assign ( 'type', $type );
        $this->assign ( $d );
        $this->display ();
    }

    /**
     * 儲存訊息配置節點
     */
    public function saveNotifyNode() {
        model ( 'Notify' )->saveNodeList ( $_POST ['sendType'] );
        $this->assign ( 'jumpUrl', U ( 'admin/Config/notify', 'type=' . $_POST ['type'] . '&tabHash=' . $_POST ['tabhash'] ) );
        $this->success ();
    }

    /**
     * 訊息模板頁面
     */
    public function notifytpl() {
        $type = isset ( $_GET ['type'] ) ? intval ( $_GET ['type'] ) : 1;
        $this->pageTab [] = array (
            'title' => '使用者訊息配置',
            'tabHash' => 'notify_user',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 1
            ) )
        );
        $this->pageTab [] = array (
            'title' => '管理員訊息配置',
            'tabHash' => 'notify_admin',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 2
            ) )
        );
        $this->pageTab [] = array (
            'title' => '增加訊息節點',
            'tabHash' => 'addNotifytpl',
            'url' => U ( 'admin/Config/addNotifytpl' )
        );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_MAIL_TPL_SET' ),
                'tabHash' => 'notifytpl',
                'url' => '#'
            );

        $d ['langType'] = model ( 'Lang' )->getLangType ();
        $d ['nodeInfo'] = model ( 'Notify' )->getNode ( t ( $_REQUEST ['node'] ) );
        if(empty($d['nodeInfo'])){
            $this->error('參數出錯');
    }

    $new ['appname'] = strtoupper ( $d ['nodeInfo'] ['appname'] );
    $new ['filetype'] = 0;
    $map ['key'] = $d ['nodeInfo'] ['content_key'];
    if (! $d ['lang'] ['content'] = model ( 'Lang' )->where ( $map )->find ()) {
        $new ['key'] = $map ['key'];
        model ( 'Lang' )->add ( $new );
        $d ['lang'] ['content'] = $new;
    }

    $map ['key'] = $d ['nodeInfo'] ['title_key'];
    if (! $d ['lang'] ['title'] = model ( 'Lang' )->where ( $map )->find ()) {
        $new ['key'] = $map ['key'];
        model ( 'Lang' )->add ( $new );
        $d ['lang'] ['title'] = $new;
    }

    $this->assign ( 'type', $type );

    $this->assign ( $d );
    $this->display ();
    }

    /**
     * 增加節點頁面
     */
    public function addNotifytpl() {
        $this->pageTab [] = array (
            'title' => '使用者訊息配置',
            'tabHash' => 'notify_user',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 1
            ) )
        );
        $this->pageTab [] = array (
            'title' => '管理員訊息配置',
            'tabHash' => 'notify_admin',
            'url' => U ( 'admin/Config/notify', array (
                'type' => 2
            ) )
        );
        $this->pageTab [] = array (
            'title' => '增加訊息節點',
            'tabHash' => 'addNotifytpl',
            'url' => '#'
        );

        $this->display ();
    }
    function doAddNotifyTpl() {
        $dao = model ( 'Notify' );
        $data ['node'] = t ( $_POST ['node'] );
        $isExist = $dao->where ( $data )->getField ( 'id' );
        if ($isExist) {
            $this->error ( '節點已經存在！' );
    }

    $data ['nodeinfo'] = t ( $_POST ['nodeinfo'] );
    $data ['appname'] = strtolower ( t ( $_POST ['appname'] ) );
    $data ['content_key'] = t ( $_POST ['content_key'] );
    $data ['title_key'] = t ( $_POST ['title_key'] );
    $data ['send_email'] = intval ( $_POST ['send_email'] );
    $data ['send_message'] = intval ( $_POST ['send_message'] );
    $data ['type'] = intval ( $_POST ['type'] );
    $res = $dao->add ( $data );
    if ($res) {
        $new ['appname'] = strtoupper ( $data ['appname'] );
        $new ['filetype'] = 0;
        $new ['key'] = strtoupper($data ['content_key']);
        if (! model ( 'Lang' )->where ( $new )->find ()) {
            model ( 'Lang' )->add ( $new );
    }

    $new ['key'] = strtoupper($data ['title_key']);
    if (! model ( 'Lang' )->where ( $new )->find ()) {
        model ( 'Lang' )->add ( $new );
    }

    //更新快取
    $dao->cleanCache();

    $tabhash = $data ['type'] == 2 ? 'notify_admin' : 'notify_user';
    $this->assign ( 'jumpUrl', U ( 'admin/Config/notify', 'type=' . $_POST ['type'] . '&tabHash=' . $tabhash ) );
    $this->success ();
    } else {
        $this->error ( '節點增加失敗！' );
    }
    }
    /**
     * 刪除節點頁面
     */
    function delNotifyNode() {
        $map ['node'] = t ( $_GET ['node'] );
        $res = M ( 'notify_node' )->where ( $map )->delete ();
        if ($res) {
            // 刪除其它相關內容
            M ( 'notify_email' )->where ( $map )->delete ();
            M ( 'notify_message' )->where ( $map )->delete ();
            $this->success ();
    } else {
        $this->error ( '節點刪除失敗！' );
    }
    }

    /**
     * 儲存訊息模板操作
     */
    public function saveNotifyTpl() {
        model ( 'Notify' )->saveTpl ( $_POST );
        $this->assign ( 'jumpUrl', U ( 'admin/Config/notify', 'type=' . $_POST ['type'] . '&tabHash=' . $_POST ['tabhash'] ) );
        $this->success ();
    }

    /**
     * 發送訊息郵件頁面
     */
    public function sendNotifyEmail() {
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_MAILTITLE_ADMIN' ),
                'tabHash' => 'notify',
                'url' => U ( 'admin/Config/notify' )
            );
        $this->pageTab [] = array (
            'title' => L ( 'PUBLIC_MESSING_SENTTO' ),
                'tabHash' => 'notifyEmail',
                'url' => U ( 'admin/Config/sendNotifyEmail' )
            );
        $d = model ( 'Notify' )->sendEmailList ();
        $this->assign ( $d );
        $this->display ( 'sendNotifyEmail' );
    }

    /**
     * 發送訊息郵件操作
     */
    public function dosendEmail() {
        $d = model ( 'Notify' )->sendEmailList ();
        // "此次發送{$d['count']}條郵件，其中成功發送{$d['nums']}條。"
        exit ( L ( 'PUBLIC_SENT_EMAIL_TIPES_NUM', array (
            'num' => "{$d['count']}",
            'sum' => "{$d['nums']}"
        ) ) );
    }

    /**
     * SEO配置
     */
    public function setSeo() {
        $this->pageTab [] = array (
            'title' => 'SEO配置',
            'tabHash' => 'setSeo',
            'url' => U ( 'admin/Config/setSeo' )
        );

        $this->pageKeyList = array (
            'name',
            'title',
            'keywords',
            'des',
            'DOACTION'
        );
        $keys = array (
            'login',
            'feed_topic',
            'feed_detail',
            'user_profile'
        );
        $names = array (
            '登入頁',
            '話題頁',
            '微博詳情頁',
            '個人主頁'
        );
        foreach ( $keys as $k => $v ) {
            $data = model ( 'Xdata' )->get ( "admin_Config:" . "seo_" . $v );
            $list [$k] ['name'] = $names [$k];
            $list [$k] ['title'] = $data ['title'];
            $list [$k] ['keywords'] = $data ['keywords'];
            $list [$k] ['des'] = $data ['des'];
            $list [$k] ['DOACTION'] = '<a href="' . U ( 'admin/Config/editSeo', array (
                'key' => $v,
                'name' => $names [$k],
                'tabHash' => 'editSeo'
            ) ) . '">' . L ( 'PUBLIC_EDIT' ) . '</a>';
    }
    $listData ['data'] = $list;
    $this->allSelected = false;
    $this->displayList ( $listData );
    }

    /**
     * 編輯SEO項
     */
    public function editSeo() {
        $key = $_GET ['key'];
        $name = $_GET ['name'];
        $this->systemdata_key = 'seo_' . $key;
        $this->pageTab [] = array (
            'title' => 'SEO設定',
            'tabHash' => 'setSeo',
            'url' => U ( 'admin/Config/setSeo' )
        );
        $this->pageTab [] = array (
            'title' => 'SEO編輯',
            'tabHash' => 'editSeo',
            'url' => U ( 'admin/Config/editSeo', array (
                'key' => $key,
                'name' => $name
            ) )
        );

        $this->pageKeyList = array (
            'key',
            'name',
            'title',
            'keywords',
            'des'
        );
        $data = model ( 'Xdata' )->get ( "admin_Config:" . $this->systemdata_key );
        $detail ['systemdata_key'] = $this->systemdata_key;
        $detail ['key'] = $key;
        $detail ['name'] = $name;
        $detail ['title'] = $data ['title'];
        $detail ['keywords'] = $data ['keywords'];
        $detail ['des'] = $data ['des'];
        switch ($key) {
        case 'feed_topic' :
            $detail ['note'] = '{topicName}:話題名稱，{topicNote}:話題註釋，{topicDes}:話題描述，{lastTopic}:最近一條話題';
            break;
        case 'feed_detail' :
            $detail ['note'] = '{content}:微博內容，{uname}:使用者昵稱';
            break;
        case 'user_profile' :
            $detail ['note'] = '{uname}:使用者昵稱，{lastFeed}:最後一條微博';
            break;
        default :
            $detail ['note'] = '';
            break;
    }
    $this->assign ( $detail );
    $this->display ();
    // $this->displayConfig($detail);
    }

    /**
     * 對比後臺選單配置檔案
     */
    public function updateAdminTab() {
        /* 下載檔案-begin */
        tsload ( ADDON_PATH . '/library/Update.class.php' );
        $updateClass = new Update ();

        $packageURL = C ( 'TS_UPDATE_SITE' ) . '/data/lang/system_config.zip';

        $updateClass->downloadFile ( $packageURL );
        $updateClass->unzipPackage ( 'system_config.zip', LANG_PATH, false );
        /* 下載檔案-end */

        $tabFileArr = include LANG_PATH . '/system_config.php';
        $this->pageTitle [ACTION_NAME] = '對比後臺選單配置';
        $this->pageKeyList = array (
            'key',
            'DOACTION'
        );
        $this->pageButton [] = array (
            'title' => '同步',
            'onclick' => "admin.updateAdminTab(this)"
        );
        $this->_listpk = 'tab_id';
        foreach ( $tabFileArr as $key => $val ) {
            $keys = explode ( '-', $key );
            $map ['list'] = $keys [1];
            $map ['key'] = $keys [0];

            if ($res = D ( 'system_config' )->where ( $map )->find ()) {
                $localTab = unserialize ( $res ['value'] );
                if (count ( $val ['key'] ) != count ( $localTab ['key'] )) {
                    $arr [$key] ['tab_id'] = $key;
                    $arr [$key] ['key'] = $keys [0];
                    $arr [$key] ['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.updateAdminTab(\'' . $key . '\')">修改</a>';
    } else {
        foreach ( $localTab as $k => $v ) {
            foreach ( $v as $k1 => $v1 ) {
                if (htmlspecialchars_decode ( $val [$k] [$k1], ENT_QUOTES ) != $v1) {
                    $arr [$key] ['tab_id'] = $key;
                    $arr [$key] ['key'] = $keys [0];
                    $arr [$key] ['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.updateAdminTab(\'' . $key . '\')">修改</a>';
    }
    }
    }
    }
    } else {
        $arr [$key] ['tab_id'] = $key;
        $arr [$key] ['key'] = $keys [0];
        $arr [$key] ['DOACTION'] = '<a href="javascript:void(0);" onclick="admin.updateAdminTab(\'' . $key . '\')">添加</a>';
    }
    unset ( $map );
    }
    // dump(111);exit;
    $listData ['data'] = $arr;
    $this->displayList ( $listData );
    }

    /**
     * 同步後臺選單配置
     */
    public function doUpdateAdminTab() {
        $tab_id = explode ( ',', $_REQUEST ['tab_id'] );
        $tabFileArr = include LANG_PATH . '/system_config.php';
        if ($tabFileArr) {
            foreach ( $tab_id as $k => $v ) {
                $keys = explode ( '-', $v );
                if ($tabFileArr [$v]) {
                    $map ['key'] = $keys [0];
                    $map ['list'] = $keys [1];
                    foreach ( $tabFileArr [$v] as $key => $val ) {
                        foreach ( $val as $a => $b ) {
                            $tabFileArr [$v] [$key] [$a] = htmlspecialchars_decode ( $b, ENT_QUOTES ); // 反轉義
    }
    }
    $data ['value'] = serialize ( $tabFileArr [$v] );
    if ($lang = D ( 'system_config' )->where ( $map )->find ()) {
        D ( 'system_config' )->where ( $map )->save ( $data );
    } else {
        $data = array_merge ( $data, $map );
        M ( 'system_config' )->add ( $data );
    }
    }
    }
    $data ['status'] = 1;
    $data ['data'] = L ( 'PUBLIC_ADMIN_OPRETING_SUCCESS' );
    } else {
        $data ['status'] = 0;
        $data ['data'] = L ( 'PUBLIC_ADMIN_OPRETING_ERROR' );
    }

    // 清空快取
    tsload ( ADDON_PATH . '/library/Update.class.php' );
    $updateClass = new Update ();
    $cacheDir = C ( 'F_CACHE_PATH' );
    $updateClass->rmdirr ( $cacheDir );

    exit ( json_encode ( $data ) );
    }

    /*
     * Ucenter同步配置
     */
    public function setUcenter() {
        // 讀取檔案
        if ($_POST) {
            if (! file_exists ( CONF_PATH . '/uc_config.inc.php' ))
                touch ( CONF_PATH . '/uc_config.inc.php' );
            if (! is_writable ( CONF_PATH . '/uc_config.inc.php' ))
                $this->error ( CONF_PATH . '/uc_config.inc.php 檔案不可寫' );
            if (isset ( $_POST ['ucenter_open'] ) && isset ( $_POST ['ucenter_config'] )) {
                $ucopen = intval ( $_POST ['ucenter_open'] );
                $content = "<?php
define('UC_SYNC', {$ucopen});
" . $_POST ['ucenter_config'];

file_put_contents ( CONF_PATH . '/uc_config.inc.php', $content );
    }
    $this->success ( '儲存成功' );
    }

    $config = file_get_contents ( CONF_PATH . '/uc_config.inc.php' );

    preg_match ( '/\'UC_SYNC\', ([0|1])/', $config, $match );
    $uc_open = intval ( $match [1] );
    $config = str_replace ( array (
        "<?php",
        "define('UC_SYNC', 0);",
        "define('UC_SYNC', 1);"
    ), '', $config );
    $config = trim ( $config );
    $this->pageKeyList = array (
        'ucenter_open',
        'ucenter_config'
    );
    $this->opt ['ucenter_open'] = array (
        1 => L ( 'PUBLIC_OPEN' ),
                0 => L ( 'PUBLIC_CLOSE' )
            );
    $data ['ucenter_open'] = $uc_open;
    $data ['ucenter_config'] = $config;
    $this->savePostUrl = U ( 'admin/Config/setUcenter' );
    $this->displayConfig ( $data );
    }
    }
