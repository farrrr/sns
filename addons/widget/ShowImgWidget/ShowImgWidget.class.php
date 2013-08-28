<?php
/**
 * 圖片輪播JS
 * @example {:W('showImg')}
 * @author jason
 * @version TS3.0
 */
class ShowImgWidget extends Widget{
	
	private static $rand = 0;

	/**
	 * @param string width 圖片寬度
	 * @param string height 圖片高度
	 * @param string tpl 要渲染的頁面
	 */
	public function render($data){
		$var = array();
		
		$var['id']     = 'scroll'.self::$rand;		
		$var['width']  = '100%';
		$var['height'] = '100%';
		$var['tpl']    = 'default';

		is_array($data) && $var = array_merge($var,$data);

	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].".html",$var);
	
		unset($var,$data);

		self::$rand ++;
        //輸出資料
		return $content;
    }
}