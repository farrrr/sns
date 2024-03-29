<?php
/**
 * 微吧管理日誌控制器
 * @author
 * @version TS3.0
 */
class LogAction extends Action {

    /**
     * 初始化，驗證當前登入使用者許可權
     */
    public function _initialize() {

        if(!$_REQUEST['weiba_id']) $this->error('參數錯誤!');

    }

    /**
     * 微吧管理首頁-修改微吧資訊
     * @return void
     */
    public function index() {
        $weiba_id = intval($_GET['weiba_id']);
        $this->assign('weiba_id',$weiba_id);
        $weiba_detail = D('weiba')->where('weiba_id='.$weiba_id)->find();
        if($weiba_detail['logo']){
            $weiba_detail['logo_url'] = getImageUrlByAttachId($weiba_detail['logo']);
        }
        $this->assign('weiba_detail',$weiba_detail);
        $this->display();
    }

    /**
     * 執行編輯微吧
     * @return void
     */
    public function doWeibaEdit(){
        $weiba_id = intval($_POST['weiba_id']);
        $this->assign('weiba_id',$weiba_id);
        $data['weiba_name'] = t($_POST['weiba_name']);
        $data['intro'] = t($_POST['intro']);
        $data['logo'] = t($_POST['logo']);
        $data['who_can_post'] = t($_POST['who_can_post']);
        if(strlen($data['weiba_name']) == 0){
            $this->error('微吧名稱不能為空');
        }
        if(strlen($data['logo']) == 0){
            $this->error('logo不能為空');
        }
        if(strlen($data['intro']) == 0){
            $this->error('簡介不能為空');
        }
        $res = D('weiba')->where('weiba_id='.$weiba_id)->save($data);
        if($res !== false){
            $this->success('儲存成功');
        }else{
            $this->error('儲存失敗');
        }
    }

    /**
     * 微吧成員管理
     * @return void
     */
    public function member(){
        $weiba_id = intval($_GET['weiba_id']);
        $this->assign('weiba_id',$weiba_id);
        $weiba_member = D('weiba_follow')->where('weiba_id='.$weiba_id)->order('level desc')->findAll();
        $uids = getSubByKey($weiba_member, 'follower_uid');
        $user_info = model('User')->getUserInfoByUids($uids);
        $this->assign('user_info', $user_info);
        $this->assign('weiba_member', $weiba_member);
        $this->assign('weiba_super_admin',D('weiba_follow')->where('level=2 and weiba_id='.$weiba_id)->getField('follower_uid'));
        $this->display();
    }

    /**
     * 設定微吧成員等級
     * @return void
     */
    public function editLevel(){
        $map['weiba_id'] = intval($_POST['weiba_id']);
        $map['follower_uid'] = intval($_POST['follower_uid']);
        $targetLevel = intval($_POST['targetLevel']);
        $result = D('weiba_follow')->where($map)->setField('level',$targetLevel);
        if(!$result){
            $return['status'] = 0;
            $return['data'] = '設定失敗';
        }else{
            $return['status'] = 1;
            $return['data']   = '設定成功';
        }
        echo json_encode($return);exit();
    }

    /**
     * 移出成員
     * @return void
     */
    public function moveOut(){
        $map['weiba_id'] = intval($_POST['weiba_id']);
        $map['follower_uid'] = intval($_POST['follower_uid']);
        $result = D('weiba_follow')->where($map)->delete();
        if(!$result){
            $return['status'] = 0;
            $return['data'] = '移出失敗';
    }else{
        D('weiba')->where('weiba_id='.$map['weiba_id'])->setDec('follower_count');
        $return['status'] = 1;
        $return['data']   = '移出成功';
    }
    echo json_encode($return);exit();
    }

    /**
     * 公告釋出
     * @return void
     */
    public function notify(){
        $weiba_id = intval($_GET['weiba_id']);
        $this->assign('weiba_id',$weiba_id);
        $notify = D('weiba')->where('weiba_id='.$weiba_id)->getField('notify');
        $this->assign('notify',$notify);
        $this->display();
    }

    /**
     * 修改公告
     * @return void
     */
    public function doNotify(){
        $weiba_id = intval($_POST['weiba_id']);
        $this->assign('weiba_id',$weiba_id);
        $notify = t($_POST['notify']);
        preg_match_all('/./us', $notify, $match);
        if(count($match[0])>200){     //漢字和字母都為一個字
            $this->error('公告內容不能超過200個字');
    }
    $data['notify'] = $notify;
    $res = D('weiba')->where('weiba_id='.$weiba_id)->save($data);
    if($res !== false){
        $this->success('儲存成功');
    }else{
        $this->error('儲存失敗');
    }
    }

    }
