<?php
/**
 * 個人空間資料統計
 * @example {:W('MySpaceCount')}
 * @version TS3.0 
 */
class MySpaceCountWidget extends Widget{

	/**
	 * 渲染空間資料統計模板
	 */
	public function render($data){

		$content = $this->renderFile(dirname(__FILE__)."/content.html",$var);

		unset($var,$data);

		return $content;
    }
}
?>