<?php
/**
 * 黑名單 widget
 * @example {:W('Blacklist',array('tpl'=>'btn', 'fid'=>10001))}
 * @author Jason
 */
class BlacklistWidget extends Widget {
	
    /**
     * @param  integer tpl 模板名稱(分list和btn)
     * @param integer fid 目標使用者ID(tpl=btn時必須)
     */
	public function render($data) {
		
		$var['tpl'] = 'list';

		is_array($data) && $var = array_merge($var,$data);
		
		$var['blackList'] = model('UserBlacklist')->getUserBlackList($GLOBALS['ts']['mid']);
		
		if($var['tpl'] == 'btn'){
			if($var['fid'] == $GLOBALS['ts']['mid']){
				return '';
			}
			return $this->btn($var);
		}
	
		foreach($var['blackList'] as $k=>$v){
			$var['blackList'][$k]['userInfo'] = model('User')->getUserInfo($k);
		}
		$content = $this->renderFile (dirname(__FILE__)."/".$var['tpl'].'.html', $var );
		
		return $content;
	}
	
	/**
	 * 渲染按鈕模板
	 * @param  integer tpl 模板名稱
	 * @param integer fid 目標使用者ID
	 */
	private function btn($var){
		
		$var['doaction'] = isset($var['blackList'][$var['fid']]) ? 'remove' : 'add';
		
		$content = $this->renderFile (dirname(__FILE__)."/".$var['tpl'].'.html', $var );
		
		return $content;
		
	}
	
	/**
	 * 加入黑名單
	 * @return array 加入黑名單狀態和提示
	 */
	public function addUser(){
		$r = array('data'=>'請選擇使用者','status'=>'0');
		if(!empty($_POST['fid'])){
			if($res = model('UserBlacklist')->addUser($GLOBALS['ts']['mid'],t($_POST['fid']))){
				$finfo = model('User')->getUserInfo($_POST['fid']);
				$r['data'] = L('PUBLIC_ADD_HUSER_USRE',array('user'=>$finfo['uname']));
				$r['status'] = 1;
			}else{
				$r['data'] = model('UserBlacklist')->getError();
			} 			
		}
		echo json_encode($r);exit();
	}
	
	/**
	 * 移出黑名單
	 * @return  array 移出黑名單狀態和提示
	 */
	public function removeUser(){
		$r = array('data'=>L('PUBLIC_USER_ID_ISNULL'),'status'=>'0');
		if(!empty($_POST['fid'])){
			if($res = model('UserBlacklist')->removeUser($GLOBALS['ts']['mid'],t($_POST['fid']))){
				$r['data'] = L('PUBLIC_MOVE_USER_SUCCESS');
				$r['status'] = 1;
			}else{
				$r['data'] = model('UserBlacklist')->getError();
			}
		}
		echo json_encode($r);exit();
	}

}