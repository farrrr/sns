<?php 
/** 
 * 備註 Widget
 * @example W('Remark',array('uid'=>1000,'remark'=>'TS3.0','showonly'=>0))
 * @version TS3.0
 */
class RemarkWidget extends Widget{
	private  static $rand = 1;
	/**
	 * @param integer uid 目標使用者的UID
	 * @param string remark 使用者已經被設定的備註名稱
	 * @param integer showonly 是否只顯示已有的備註
	 */
	public function render($data){
		
		$var = array();
		$var['uid'] = $GLOBALS['ts']['uid'];
		$var['remark'] = '';
		is_array($data) && $var = array_merge($var,$data);
		if($var['uid'] == $GLOBALS['ts']['mid']){
			return '';
		}
		$var['rand'] = self::$rand;
	    //渲染模版
	    $content = $this->renderFile(dirname(__FILE__)."/remark.html",$var);
	
		self::$rand ++;

		unset($var,$data);
        //輸出資料
		return $content;
	}
	
	/**
	 * 渲染備註編輯彈框
	 * @return string 修改後的備註內容
	 */
	public function edit(){
		$var = $_REQUEST;
		$content = $this->renderFile(dirname(__FILE__)."/edit.html",$var);
		return $content;
	}
}	
?>