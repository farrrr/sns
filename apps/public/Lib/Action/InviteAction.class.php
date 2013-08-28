<?php
/**
 * 邀請控制器
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class InviteAction extends Action {

    private $_invite_model;
    private $_invite_config;
    private $_register_config;

    public function _initialize() {
        // 獲取後臺配置
        $this->_register_config = model('Xdata')->get('admin_Config:register');
        $registerType = $this->_register_config['register_type'];
        if(!in_array($registerType, array('open', 'invite'))) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                exit($this->ajaxReturn(null, '您沒有邀請許可權', 0));
            } else {
                exit(redirect(U('public/Index/index')));
            }
        }
        $this->_invite_model = model('Invite');
    }

    /**
     * 邀請頁面 - 頁面
     * @return void
     */
    public function invite()
    {
        if( !CheckPermission('core_normal','invite_user') ){
            $this->error('對不起，您沒有許可權進行該操作！');
        }
        // 獲取選中類型
        $type = isset($_GET['type']) ? t($_GET['type']) : 'email';
        $this->assign('type', $type);
        // 獲取不同列表的相關資料
        switch($type) {
        case 'email':
            $this->_getInviteEmail();
            break;
        case 'link':
            $this->_getInviteLink();
            break;
        }
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->assign('invite', $userInfo);
        $this->assign('config', model('Xdata')->get('admin_Config:register'));
        // 獲取後臺積分配置
        $creditRule = model('Credit')->getCreditRules();
        foreach ($creditRule as $v) {
            if ($v['name'] === 'core_code') {
                $applyCredit = abs($v['score']);
                break;
            }
        }
        $this->assign('applyCredit', $applyCredit);
        // 後臺配置邀請數目
        $inviteConf = model('Xdata')->get('admin_Config:invite');
        $this->assign('emailNum', $inviteConf['send_email_num']);

        $this->display();
    }

    /**
     * 郵箱邀請相關資料
     * @return void
     */
    private function _getInviteEmail()
    {
        // 獲取郵箱字尾
        $config = model('Xdata')->get('admin_Config:register');
        $this->assign('emailSuffix', $config['email_suffix']);
        // 獲取已邀請使用者資訊
        $inviteList = $this->_invite_model->getInviteUserList($this->mid, 'email');
        $this->assign('inviteList', $inviteList);
        // 獲取有多少可用的邀請碼
        $count = $this->_invite_model->getAvailableCodeCount($this->mid, 'email');
        $this->assign('count', $count);
    }

    /**
     * 連結邀請相關資料
     * @return void
     */
    private function _getInviteLink()
    {
        // 獲取邀請碼列表
        $codeList = $this->_invite_model->getInviteCode($this->mid, 'link');
        $this->assign('codeList', $codeList);
        // 獲取已邀請使用者資訊
        $inviteList = $this->_invite_model->getInviteUserList($this->mid, 'link');
        $this->assign('inviteList', $inviteList);
        // 獲取有多少可用的邀請碼
        $count = $this->_invite_model->getAvailableCodeCount($this->mid, 'link');
        $this->assign('count', $count);
    }

    /**
     * 邀請頁面 - 彈窗
     * @return void
     */
    public function inviteBox()
    {
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->assign('invite', $userInfo);
        $this->assign('config', model('Xdata')->get('admin_Config:register'));
        $this->display();
    }

    /**
     * 邀請操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function doInvite()
    {
        if( !CheckPermission('core_normal','invite_user') ){
            return false;
        }
        $email = t($_POST['email']);
        $detial = !isset($_POST['detial']) ? L('PUBLIC_INVATE_MESSAGE',array('uname'=>$GLOBALS['ts']['user']['uname'])) : h($_POST['detial']);          // Hi，我是 {uname}，我發現了一個很不錯的網站，我在這裡等你，快來加入吧。
        $map['inviter_uid'] = $this->mid;
        $map['ctime'] = time();
        // 發送郵件邀請
        $result = model('Invite')->doInvite($email, $detial, $this->mid);
        $this->ajaxReturn(null, model('Invite')->getError(), $result);
    }

    /**
     * 驗證郵箱地址是否可用
     * @return json 驗證後的相關資料
     */
    public function checkInviteEmail()
    {
        $email = t($_POST['email']);
        $result = model('Register')->isValidEmail($email);
        $this->ajaxReturn(null, model('Register')->getLastError(), $result);
    }

    /**
     * 獲取邀請碼介面
     * @return json 操作後的相關資料
     */
    public function applyInviteCode()
    {
        // 獲取相關資料
        $uid = intval($_POST['uid']);
        $type = t($_POST['type']);
        $result = $this->_invite_model->applyInviteCode($uid, $type);
        $res = array();
        if($result) {
            $res['status'] = true;
            $res['info'] = '邀請碼領取成功';
        } else {
            $res['status'] = false;
            $res['info'] = '邀請碼領取失敗';
        }

        exit(json_encode($res));
    }
}
