<?php
/**
 * 視訊模組
 * @author Stream
 *
 */
class VideoWidget extends Widget{
	public function render($data) {
	}
	  
    /**
    * 
    * 上傳視訊接受處理
    * 
    */
	public function paramUrl(){
	  	$link = t($_POST['url']);
	  	if(preg_match("/(youku.com|youtube.com|qq.com|ku6.com|sohu.com|sina.com.cn|tudou.com|yinyuetai.com)/i", $link, $hosts)) {
	  		$return['boolen'] = 1;
	  		$return['data']   = $link;
	  	}else{
	  		$return['boolen'] = 0;
	  		$return['message'] = '僅支援新浪播客、優酷網、土豆網、酷6網、搜狐等視訊釋出';
	  	}
	  	$flashinfo = model('Video')->_video_getflashinfo($link, $hosts[1]);
	  	
	  	$return['title'] = 1;
	  	if( !$flashinfo['title'] || json_encode($flashinfo['title']) == 'null'){
	  		$return['title'] = 0;
	  		$return['message'] = '僅支援新浪播客、優酷網、土豆網、酷6網、搜狐等視訊釋出';
	  	}
	  	$return['data'] = $flashinfo['title'].$return['data'];
	  	exit( json_encode($return) );
	}
}