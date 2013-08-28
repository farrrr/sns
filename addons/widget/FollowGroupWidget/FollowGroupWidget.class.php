<?php
/**
 * 調整關注使用者分組Widget
 * @example {:W('FollowGroup', array('uid'=>$_following['uid'], 'fid'=>$_following['fid'], 'follow_group_status' => $follow_group_status)}
 * @author jason <yangjs17@yeah.net> 
 * @version TS3.0 
 */
class FollowGroupWidget extends Widget {

    /**
     * 渲染關注使用者分組調整模板
     * @example
     * $data['uid'] integer 使用者ID
     * $data['fid'] integer 關注使用者ID
     * $data['follow_group_status'] array 指定關注使用者的分組資訊
     * @data['tpl'] string 模板欄位
     * @param array $data 配置的相關資訊
     * @return string 渲染後的模板資料
     */
	public function render($data) {
        // 選擇模板
        $template = empty($data['tpl']) ? 'FollowGroup' : t($data['tpl']);
        // 關注使用者分類資訊
		$followGroupStatus = $data['follow_group_status'];
        // 組裝資料
		foreach($followGroupStatus as $key => $value) {
			$value['gid'] != 0 && $value['title'] = (strlen($value['title']) + mb_strlen($value['title'], 'UTF8')) / 2 > 4 ? getShort($value['title'], 2) : $value['title'];
			$data['status'] .= $value['title'].',';
			if(!empty($followGroupStatus[$key + 1]) && (strlen($data['status']) + mb_strlen($data['status'], 'UTF8')) / 2 >= 6) {
				$data['status'] .= '...,';
                break;
            }
		}
        $data['status'] = substr($data['status'], 0, -1);
        $title = getSubByKey($followGroupStatus, 'title');       // 用於存儲原始資料
        $data['title'] = implode(',', $title);

        $content = $this->renderFile(dirname(__FILE__) . '/'.$template.'.html', $data);

        return $content;
    }
    
    /**
     * 渲染添加分組頁面
     */
    public function addgroup(){
    	return  $content = $this->renderFile(dirname(__FILE__) . '/addgroup.html');
    }
    
    /**
     * 添加分組
     * @return  array 添加分組狀態和提示資訊
     */
    public function doaddGroup(){
        // 驗證是否超出個數
        $count = model('FollowGroup')->where('uid='.$GLOBALS['ts']['mid'])->count();
        if($count >= 10) {
            $return = array('status'=>0,'data'=>'最多只能創建10個分組');
            exit(json_encode($return));
        }

    	$groupname   = t($_POST['groupname']); 
    	$followGroup = model('FollowGroup')->getGroupList($GLOBALS['ts']['mid']);
    	foreach($followGroup as $v){
    		if($v['title'] === $groupname){
    			$return = array('status'=>0,'data'=>L('PUBLIC_USER_GROUP_EXIST'));
    			exit(json_encode($return));
    		}
    	}
    	// 插入資料
    	$res = model('FollowGroup')->setGroup($GLOBALS['ts']['mid'],$groupname);
    	if($res == 0){
    		$return = array('status'=>0,'data'=>L('PUBLIC_ADD_GROUP_NAME_ERROR'));
    	}else{
    		$return = array('status'=>1,'data'=>$res);
    	}
    	exit(json_encode($return));
    } 
    
    /**
     * 渲染編輯分組頁面
     */
    public function editgroup(){
    	$followGroupDao = model('FollowGroup');
		$var['group_list'] = $followGroupDao->getGroupList($GLOBALS['ts']['mid']);
		
		return  $content = $this->renderFile(dirname(__FILE__) . '/editgroup.html',$var);
		
    }
    
    /**
     * 驗證分組個數
     * @return  mixed 驗證分組狀態和提示資訊
     */
    public function checkGroup(){
        $map['uid'] = $this->mid;
        $nums = model('FollowGroup')->where($map)->count();
        if($nums >= 10){
            $return = array('data'=>L('PUBLIC_CRETAE_GROUP_MAX_TIPES',array('num'=>10)),'status'=>0);
            echo json_encode($return);exit();
        }
        echo 1;
    }
}