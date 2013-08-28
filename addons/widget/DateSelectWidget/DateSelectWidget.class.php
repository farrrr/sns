<?php
/**
 * 日期選擇
 * @example 呼叫方法：W('DateSelect',array('name'=>'dateSelect','class'=>'s-txt','id'=>'dateSelectId','value'=>'','dtype'=>'full'))
 * @author  jason <yangjs17@yeah.net> 
 * @version TS3.0
 */
class DateSelectWidget extends Widget {
	
	private static $rand = 1;

	/**
	 * @param string name 元件input的名稱
	 * @param string class 元件input的樣式
	 * @param integer id 元件input的ID
	 * @param string value 默認的值
	 * @param string dtype 時間類型
	 */
	public function render($data) {
		$var = array();
	
		is_array($data) && $var = array_merge($var,$data);
		
	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/default.html",$var);
		
        return $content;
    }
}