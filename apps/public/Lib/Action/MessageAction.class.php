<?php
/**
 * MessageAction 訊息模組
 * @version TS3.0
 */
class MessageAction extends Action
{
    /**
     * 模組初始化
     * @return void
     */
    function _initialize(){

    }

    /**
     * 私信列表
     * @return void
     */
    public function index() {
        $dao = model('Message');
        $list = $dao->getMessageListByUid($this->mid, array(MessageModel::ONE_ON_ONE_CHAT, MessageModel::MULTIPLAYER_CHAT));
        $this->assign($list);
        // 設定資訊已讀(在右上角提示去掉),
        model('Message')->setMessageIsRead(t($POST['id']), $this->mid, 1);
        $this->setTitle( L('PUBLIC_MESSAGE_INDEX') );
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->setKeywords($userInfo['uname'].'的私信');
        $this->display('list');
    }

    /**
     * 系統通知
     * @return void
     */
    public function notify() {
        //$list = model('Notify')->getMessageList($this->mid);     //2012/12/27
        $list = D('notify_message')->where('uid='.$this->mid)->order('ctime desc')->findpage(20);
        foreach($list['data'] as $k=>$v){
            if($appname !='public'){
                $list['data'][$k]['app'] = model('App')->getAppByName($v['appname']);
            }
        }
        model('Notify')->setRead($this->mid);
        $this->assign('list',$list);

        $this->setTitle( L('PUBLIC_MESSAGE_NOTIFY') );
        $this->setKeywords( L('PUBLIC_MESSAGE_NOTIFY') );
        $this->display('mynotify');
    }

    /**
     * 獲取指定應用指定使用者下的訊息列表
     * @return void
     */
    public function notifyDetail() {
        $appname = t($_REQUEST['appname']);
        //設定為已讀
        //model('Notify')->setRead($this->mid,$appname);
        $this->assign('appname',$appname);
        if($appname !='public'){
            $appinfo = model('App')->getAppByName($appname);
            $this->assign('appinfo',$appinfo);
        }
        $list = model('Notify')->getMessageDetail($appname,$this->mid);
        $this->assign($list);
        $this->display();
    }

    /**
     * 刪除私信
     * @return void
     */
    public function delnotify() {
        model('Notify')->deleteNotify(t($_REQUEST['id']));
    }

    /**
     * 私信詳情
     * @return void
     */
    public function detail()
    {
        $message = model('Message')->isMember(t($_GET['id']), $this->mid, true);

        // 驗證資料
        if(empty($message)) {
            $this->error(L('PUBLIC_PRI_MESSAGE_NOEXIST'));
        }
        $message['member'] = model('Message')->getMessageMembers(t($_GET['id']), 'member_uid');
        $message['to'] = array();
        // 添加發送使用者ID
        foreach($message['member'] as $v) {
            $this->mid != $v['member_uid'] && $message['to'][] = $v;
        }
        // 設定資訊已讀(私信列表頁去掉new標識)
        model('Message')->setMessageIsRead(t($_GET['id']), $this->mid, 0);
        $message['since_id'] = model('Message')->getSinceMessageId($message['list_id'],$message['message_num']);

        $this->assign('message', $message);
        $this->assign('type', intval($_GET['type']));

        $this->setTitle('與'.$message['to'][0]['user_info']['uname'].'的私信對話');
        $this->setKeywords('與'.$message['to'][0]['user_info']['uname'].'的私信對話');
        $this->display();
    }

    /**
     * 獲取指定私信列表中的私信內容
     * @return void
     */
    public function loadMessage() {
        $message = model('Message')->getMessageByListId(intval($_POST['list_id']), $this->mid, intval($_POST['since_id']), intval($_POST['max_id']));
        $this->assign('message', $message);
        $this->assign('type', intval($_POST['type']));
        $message['data'] = $message['data'] ? $this->fetch() : null;
        echo json_encode($message);
    }

    /**
     * 發送私信彈窗
     * @return void
     */
    public function post() {
        $touid = t($_GET['touid']);
        $max = $_REQUEST['max'] ? intval($_REQUEST['max']) : 10;
        $this->assign('max',$max);
        $this->assign('touid',$touid);
        // 是否能夠編輯使用者
        $editable = intval($_REQUEST['editable']) == 0 ? 0 : 1;
        $this->assign('editable', $editable);

        $this->display();
    }

    /**
     * 發送私信
     * @return void
     */
    public function doPost() {
        $return = array('data'=>L('PUBLIC_SEND_SUCCESS'),'status'=>1);
        if (empty($_POST['to']) || !CheckPermission('core_normal','send_message')) {
            $return['data']=L('PUBLIC_SYSTEM_MAIL_ISNOT');
            $return['status'] = 0;
            echo json_encode($return);exit();
        }
        if(trim(t($_POST['content'])) == ''){
            $return['data'] = L('PUBLIC_COMMENT_MAIL_REQUIRED');
            $return['status'] = 0;
            echo json_encode($return);exit();
        }
        $_POST['to'] = trim(t($_POST['to']),',');
        $to_num = explode(',', $_POST['to']);
        if( sizeof($to_num)>10 ){
            $return['data'] = '';
            $return['status'] = 0;
            echo json_encode($return);exit();
        }
        !in_array($_POST['type'], array(MessageModel::ONE_ON_ONE_CHAT, MessageModel::MULTIPLAYER_CHAT)) && $_POST['type'] = null;
        $_POST['content'] = h($_POST['content']);
        $res = model('Message')->postMessage($_POST, $this->mid);
        if ($res) {
            echo json_encode($return);exit();
        }else {
            $return['status'] = 0;
            $return['data']   = model('Message')->getError();;
            echo json_encode($return);exit();
        }

    }

    /**
     * 回覆私信
     * @return void
     */
    public function doReply() {
        $UserPrivacy = model('UserPrivacy')->getPrivacy($this->mid, intval($_POST['to']));
        if($UserPrivacy['message'] != 0){
            echo json_encode(array('status'=>0,'data'=>'根據對方的隱私設定，您無法給TA發送私信'));
            exit;
}
$_POST['reply_content'] = t($_POST['reply_content']);
$_POST['id']  = intval($_POST['id'] );

if ( !$_POST['id'] || empty($_POST['reply_content']) ) {
    echo json_encode(array('status'=>0,'data'=>L('PUBLIC_COMMENT_MAIL_REQUIRED')));
    exit;
}

$res = model('Message')->replyMessage( $_POST['id'], $_POST['reply_content'], $this->mid );
if ($res) {
    echo json_encode(array('status'=>1,'data'=>L('PUBLIC_PRIVATE_MESSAGE_SEND_SUCCESS')));
}else {
    echo json_encode(array('status'=>0,'data'=>L('PUBLIC_PRIVATE_MESSAGE_SEND_FAIL')));
}
exit();
}

/**
 * 設定指定私信為已讀
 * @return integer 1=成功 0=失敗
 */
public function doSetIsRead() {
    $res = model('Message')->setMessageIsRead(t($_POST['ids']), $this->mid);
    if ($res) echo 1;
    else      echo 0;
}

/**
 * 刪除私信
 * @return integer 1=成功 0=失敗
 */
public function doDelete() {
    $res = model('Message')->deleteMessageByListId($this->mid, t($_POST['ids']));
    if ($res) echo 1;
    else      echo 0;
}

/**
 * 刪除使用者指定私信會話
 * @return integer 1=成功 0=失敗
 */
public function doDeleteSession() {
    $res = model('Message')->deleteSessionById($this->mid, t($_POST['ids']));
    if ($res) echo 1;
    else      echo 0;
}
}
