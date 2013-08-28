<?php
/**
 * 編輯器
 * @example W('Editor',array('width'=>300,'height'=>'200','contentName'=>'mycontent','value'=>'默認的值'))
 * @author jason
 * @version TS3.0
 */
class EditorWidget extends Widget{

	private static $rand = 1;

    /**
     * @param integer width 編輯器的寬度
     * @param integer height 編輯器的高度
     * @param string contentName 編輯器的表單名
     * @param string value 預設值
     */
	public function render($data){
		$var = array();    
	    $var['contentName'] = 'content';
	    $var['width'] = '100%';
	    !empty($data) && $var = array_merge($var,$data);
	    $content = $this->renderFile(dirname(__FILE__).'/default.html',$var);
	    unset($var,$data);
	    // 輸出資料
	    return $content;
  }
}