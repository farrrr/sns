<?php
/**
 * 我的評論控制器
 * @author jason <yangjs17@yeah.net> 
 * @version TS3.0
 */
class CommentAction extends Action {
		
	/**
	 * 我的評論頁面
	 */
	public function index() {
		// 安全過濾
		$type = t($_GET['type']);
		if($type == 'send') {	
			$keyword = '發出';
			$map['uid'] = $this->uid;	
		} else {
			// 微博配置
			$weiboSet = model('Xdata')->get('admin_Config:feed');
			$this->assign('weibo_premission', $weiboSet['weibo_premission']);
			$keyword = '收到';
			//獲取未讀評論的條數
			$this->assign('unread_comment_count',model('UserData')->where('uid='.$this->mid." and `key`='unread_comment'")->getField('value'));
			// 收到的
			$map['_string'] = " (to_uid = '{$this->uid}' OR app_uid = '{$this->uid}') AND uid !=".$this->uid;
		}

		$d['tab'] = model('Comment')->getTab($map);
		foreach ($d['tab'] as $key=>$vo){
			if($key=='feed'){
				$d['tabHash']['feed'] = L('PUBLIC_WEIBO');
			}else{
				// 微吧
				strtolower($key) === 'weiba_post' && $key = 'weiba';
				
				$langKey = 'PUBLIC_APPNAME_' . strtoupper ( $key );
				$lang = L($langKey);
				if($lang==$langKey){
					$d['tabHash'][$key] = ucfirst ( $key );
				}else{
					$d['tabHash'][$key] = $lang;
				}
			}
		}
		$this->assign($d);	

		// 安全過濾
		$t = t($_GET['t']);
		!empty($t) && $map['table'] = $t;
		$list = model('Comment')->setAppName(t($_GET['app_name']))->getCommentList($map,'comment_id DESC',null,true);
		foreach($list['data'] as $k=>$v){
			if($v['sourceInfo']['app']=='weiba'){
				$list['data'][$k]['sourceInfo']['source_body'] = str_replace($v['sourceInfo']['row_id'], $v['comment_id'], $v['sourceInfo']['source_body']);
			}
		}
		model('UserCount')->resetUserCount($this->mid, 'unread_comment',  0);
		$this->assign('list', $list);
		// dump($list);exit;
		$this->setTitle($keyword.'的評論');					// 我的評論
		$userInfo = model('User')->getUserInfo($this->mid);
		$this->setKeywords($userInfo['uname'].$keyword.'的評論');
		$this->display();
	}

	/**
	 * 我的評論中，回覆彈窗頁面
	 */
	public function reply() {
		$var = $_GET;
		foreach ($var as $k => $v) {
			$var[$k] = h($v);
		}
		$var['initNums'] = model('Xdata')->getConfig('weibo_nums', 'feed');
		$var['commentInfo'] = model('Comment')->getCommentInfo(intval($var['comment_id']), false);
		$var['canrepost']  = $var['commentInfo']['table'] == 'feed'  ? 1 : 0;
		$var['cancomment'] = 1;
		// 獲取原作者資訊
		$rowData = model('Feed')->get(intval($var['commentInfo']['row_id']));
		$appRowData = model('Feed')->get(intval($rowData['app_row_id']));
		$var['user_info'] = $appRowData['user_info'];
		// 微博類型
		$var['feedtype'] = $rowData['type'];
		// $var['cancomment_old'] = ($var['commentInfo']['uid'] != $var['commentInfo']['app_uid'] && $var['commentInfo']['app_uid'] != $this->uid) ? 1 : 0;
		$var['initHtml'] = L('PUBLIC_STREAM_REPLY').'@'.$var['commentInfo']['user_info']['uname'].' ：';		// 回覆

		$this->assign($var);
		$this->display();
	}
}