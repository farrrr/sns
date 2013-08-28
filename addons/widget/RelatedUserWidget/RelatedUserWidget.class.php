<?php
/**
 * 可能感興趣的人Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class RelatedUserWidget extends Widget {

	/**
	 * 渲染可能感興趣的人頁面
	 * @param array $data 配置相關資料
	 * @return string 渲染頁面的HTML
	 */
	public function render($data) {
		//$var = $this->_getRelatedUser($data);
		$var = $data;
		// 使用者ID
		$var['uid'] = isset($data['uid']) ? intval($data['uid']) : $GLOBALS['ts']['mid']; 
		// 顯示相關人數
		$var['limit'] = isset($data['limit']) ? intval($data['limit']) : 4;
		// 標題資訊
		$var['title'] = isset($data['title']) ? t($data['title']) : '推薦關注';
		$content = $this->renderFile(dirname(__FILE__)."/relatedUser.html", $var);

		return $content;
	}

	/**
	 * 換一換資料處理
	 * @return json 渲染頁面所需的JSON資料
	 */
	public function changeRelate() {
		$data['uid'] = intval($_POST['uid']);
		$data['limit'] = intval($_POST['limit']);
		$var = $this->_getRelatedUser($data);
		$content = $this->renderFile(dirname(__FILE__)."/_relatedUser.html", $var);
		exit(json_encode($content));
	}

	/**
	 * 獲取使用者的相關資料
	 * @param array $data 配置相關資料
	 * @return array 顯示所需資料
	 */
	private function _getRelatedUser($data) {
		// 使用者ID
		$var['uid'] = isset($data['uid']) ? intval($data['uid']) : $GLOBALS['ts']['mid']; 
		// 顯示相關人數
		$var['limit'] = isset($data['limit']) ? intval($data['limit']) : 4;
		// 標題資訊
		$var['title'] = isset($data['title']) ? t($data['title']) : '推薦關注';
		// 相關使用者資訊
		$var['user'] = model('RelatedUser')->getRelatedUser($var['limit']);

		return $var;
	}
}