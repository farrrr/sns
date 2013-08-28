<?php
/**
 * 微博釋出框
 * @example {:W('SendWeibo',array('send_type'=>'repost_weibo','oldUid'=>$oldInfo['source_user_info']['uid'],'space_link'=>$oldInfo['source_user_info']['space_link'],'sid'=>$shareInfo['sid'],'app_name'=>$shareInfo['appname'],'stype'=>$shareInfo['stable'],'initHtml'=>$shareInfo['initHTML'],'curid'=>$shareInfo['curid'],'curtable'=>$shareInfo['curtable'],'cancomment'=>$shareInfo['cancomment']))}
 * @author jason
 * @version TS3.0
 */
class SendWeiboWidget extends Widget {
	
	private static $rand = 1;
    
    /**
     * 渲染微博釋出框模板
     * @example
     * $data['send_type'] string 微博發送類型
     * $data['app_name'] string 釋出微博所在的應用名稱
     * $data['initHtml'] string 釋出微博框中的默認內容
     * $data['cancomment'] integer 是否可以評論 
     *$data['channelID']  釋出到某個頻道的id
     * @param array $data 釋出微博框的配置參數
     * @return string 渲染後的模板內容
     */
	public function render($data) {
		$var = array();
		//頻道id
		$var['channelID'] = $data['channelID'];
		
		$var['initHtml'] = '';
		$var['post_event'] ='post_feed';
		$var['cancomment'] = 0;
		is_array($data) && $var = array_merge($var,$data);
		!$var['send_type'] && $var['send_type'] = 'send_weibo';
		$weiboSet = model('Xdata')->get('admin_Config:feed');
		$var['initNums'] = $weiboSet['weibo_nums'];
		$var['weibo_type'] = $weiboSet['weibo_type'];
		$var['weibo_premission'] = $weiboSet['weibo_premission'];
		!$var['type'] && $var['type'] = 'post';
		!$var['app_name'] && $var['app_name'] = 'public';
		!$var['prompt'] && $var['prompt'] = '轉發成功';
		$var['time'] = $_SERVER['REQUEST_TIME'];
		$var['topicHtml'] = t($data['topicHtml']).' ';//空格 控制話題提示
		// 獲取安裝的應用列表
		$var['hasChannel'] = model('App')->isAppNameOpen('channel');
		// 許可權控制
		$type = array('face', 'at', 'image', 'video', 'file', 'topic', 'contribute');
		foreach($type as $value) {
			!isset($var['actions'][$value]) && $var['actions'][$value] = true; 
		}
	    // 渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/SendWeibo.html", $var);
	
		self::$rand++;
		unset($var, $data);
        // 輸出資料
		return $content;
    }
}