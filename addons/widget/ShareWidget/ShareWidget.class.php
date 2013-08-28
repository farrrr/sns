<?php
/**
 * 微博釋出框
 * @example W('Share',array('sid'=>14983,'stable'=>'contact','appname'=>'contact','nums'=>10,'initHTML'=>'這裡是默認的話')) 
 * @author jason
 * @version TS3.0
 */
class ShareWidget extends Widget{
	
	/**
     * @param integer sid 資源ID,如分享小名片就是對應使用者的使用者ID，分享微博就是微博的ID
     * @param string stable 資源所在的表，如小名片就是contact表，微博就是feed表
     * @param string appname 資源所在的應用
     * @param integer nums 該資源被分享的次數
     * @param string initHTML 默認的內容 
	 */
	public function render($data){
		$var = array();
		$var['appname'] = 'public';
		$var['cancomment'] = intval(CheckPermission('core_normal','feed_comment'));
		$var['feed_type'] = 'repost';
		is_array($data) && $var = array_merge($var,$data);

	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/Share.html",$var);
	
		
		unset($var,$data);
        //輸出資料
		return $content;
    }
}