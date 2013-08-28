<?php
/**
 * 搜索使用者資料
 * @example W('SearchUser', array('name' => 'selectUser', 'uids' => array(10000,14983),'follow'=>0,'max'=>10,'editable'=>0))
 * @author jason
 * @version TS3.0
 */
class SearchUserWidget extends Widget{
	
	private static $rand = 1;
	/**
	 * @param string name 存儲uid的input名稱
	 * @param mixed uids 已選擇的使用者id集合
	 * @param integer follow 是否只能選擇已關注的人（0表示可以選擇全部使用者，1表示只能選擇已關注的人）
	 * @param integer max 最多可選擇的使用者個數
	 * @param integer editable 是否可修改選擇的結果，如果為0則不能選擇使用者，不能刪除已經選擇的使用者
	 */
	public function render($data){
		$var = array();
		$var['follow'] = 0;
		$var['max'] = 0; //最多可以選擇使用者個數 為0表示不限制
		$var['editable'] = 1;
		$var['noself'] = 1; //默認不包括自己
		is_array($data) && $var = array_merge($var,$data);
		$var['rand'] = self::$rand;
		//默認參數
		if(isset($var['uids'])){
			$var['uids'] = $uids = is_array($var['uids']) ? $var['uids'] : explode(',',$var['uids']);
			foreach($uids as $v){
				!empty($v) && $var['userList'][] = model('User')->getUserInfo($v);
			}
		}
	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/SearchUser.html",$var);
	
		self::$rand ++;

		unset($var,$data);
        //輸出資料
		return $content;
    }

    /**
     * 搜索使用者 
     * @return array 搜索狀態及使用者列表資料
     */
    public function search(){
    	$key = t($_REQUEST['key']);
    	$follow = intval($_REQUEST['follow']);
    	$noself = intval($_REQUEST['noself']);
    	// @Me添加
    	$type = t($_REQUEST['type']);
    	if( $type == 'at') {
    		$feedConf = model('Xdata')->get('admin_Config:feed');
    		$follow = $feedConf['weibo_at_me'];
    		$noself = 1;
    	}
    	$list = model('User')->searchUser($key,$follow,10,'','',$noself,'',$type);
    	foreach ( $list['data'] as $k=>&$v ){
    		$user = $v;
    		$v = array();
    		$v['uid'] = $user['uid'];
    		$v['uname'] = $user['uname'];
    		$v['avatar_small'] = $user['avatar_small'];
    		$v['search_key'] = $user['search_key'];
    	}
    	$data = $list['data'];
    	$msg = array('status'=>1,'data'=>$data);
    	exit(json_encode($msg));
    }
    /**
     * 搜索最近@的人
     * @return array 搜索狀態及使用者列表資料
     */
    public function searchAt(){
    	$users = model('UserData')->where("`key`='user_recentat' and uid=".$GLOBALS['ts']['mid'])->getField('value');
    	$data = unserialize($users);
    	$msg = array('data'=>$data);
    	exit(json_encode($msg));
    }
}