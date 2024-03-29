<?php
/**
 * 具有相同資料項的人Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class SameUserWidget extends Widget {

	/**
	 * 渲染具有相同資料項的人頁面
	 * @param array $data 配置相關資料
	 * @return string 渲染頁面的HTML
	 */
	public function render($data) {
// 		$var['user'] = model('RelatedUser')->getRelatedUserByType($data['type'], $data['limit']);
		$content = $this->renderFile(dirname(__FILE__)."/sameUser.html", $data);
		return $content;
	}
	public function userlist(){
		$type = intval ( $_POST['type'] );
		$limit = intval ( $_POST['limit'] );
		$var['type'] = $type;
		$var['limit'] = $limit;
		$var['user'] = model('RelatedUser')->getRelatedUserByType($type , $limit);
		if ( $var['user'] ){
			$content = $this->renderFile(dirname(__FILE__)."/userlist.html", $var);
		} else {
			$content = '<p class="mb10">暫時還沒有相關推薦</p>';
		}
		exit($content);
	}
	/**
	 * 只獲取一個相同資料項的人
	 * @return string 人的列表
	 */
    public function getOneSameUser(){
    	for($i=0;$i<=20;$i++){
    		$oneSameUser = model('RelatedUser')->getRelatedUserByType(t($_POST['type']), 1); 
    		if($oneSameUser[0]['userInfo']['uid'] && !in_array($oneSameUser[0]['userInfo']['uid'],$_POST['user'])) {
    			$html = '<li id="user_'.$oneSameUser[0]['userInfo']['uid'].'" value="'.$oneSameUser[0]['userInfo']['uid'].'">
    					 <a href="'.$oneSameUser[0]['userInfo']['space_url'].'" class="face">
    					 <img src="'.$oneSameUser[0]['userInfo']['avatar_middle'].'" />
    					 <span>'.getshort($oneSameUser[0]['userInfo']['uname'],4).'</span></a>
    					 <div onclick="userReplace('.$oneSameUser[0]['userInfo']['uid'].','.t($_POST['type']).')">
    					 <a class="btn-cancel" href="'.U('public/Follow/doFollow',array('fid'=>$oneSameUser[0]['userInfo']['uid'])).'" event-args="uid='.$oneSameUser[0]['userInfo']['uid'].'&uname='.$oneSameUser[0]['userInfo']['uname'].'&following=0&follower=0&refer=" event-node="doFollow">
    					 <span><b class="ico-add-black"></b>關注</span></a></div></li>';
    			echo $html;
    			break;
    		}
    	}
    }
}