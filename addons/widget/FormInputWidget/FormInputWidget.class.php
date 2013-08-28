<?php
/**
 * 表單輸入框 Widget
 * @example {:W('FormInput', array('type' =>'checkbox', 'name'=>'asd', 'default_value'=>'fefds', 'value' =>1564))}
 * @version TS3.0
 * $param array $data:
 * array(
 *     'type' => 輸入框類型
 *     'name' => 輸入框欄位名
 *     'default_value' => 輸入框預設值或者選項
 *     'value' => 使用者儲存的值
 * )
 */
class FormInputWidget extends Widget
{
	/**
	 * @param string type 輸入框類型
	 * @param string name 輸入框欄位名
	 * @param mixed default_value 輸入框預設值或者選項
	 * @param mixed value 使用者儲存的值
	 */
	public function render($data)
	{
		$var = array('type' => 'input');
		$var['canChange'] = 1;

		//is_array($data) && $var = array_merge($var, array_filter($data));
		
		is_array($data) && $var = array_merge($var, $data);
		
		if($var['name'] == 'department'){
			$var['value'] = str_replace('|', ' - ', trim($var['value'],'|'));
			$var['defaultId'] = model('Department')->getUserDepartId($GLOBALS['ts']['mid']);
			
		}

		!$var['value'] && $var['value'] = $var['default_value'];
		('date' == $var['type']) && is_numeric($var['value']) && ($var['value'] = date('Y-m-d', $var['value']));

		
		$content = $this->renderFile(dirname(__FILE__) . "/{$var['type']}.html", $var);

		unset($var,$data);

		//輸出資料
		return $content;
    }
}