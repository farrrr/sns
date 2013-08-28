<?php
/**
 * 添加標籤 Widget
 * @author jason
 * @version TS3.0
 */
class StringTextWidget extends Widget{
	
	/**
	 * @param string inputname 文字框名稱
	 * @param string value 預設值
	 */
	public function render($data){
		
		$var = array();
		
		$var['tpl']  = 'default';

		is_array($data) && $var = array_merge($var,$data);
	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].".html",$var);
	
		unset($var,$data);

        //輸出資料
		return $content;
    }
}