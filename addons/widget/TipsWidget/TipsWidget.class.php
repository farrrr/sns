<?php
/**
 * 渲染頂操作頁面Widget
 * @example W('Tips',array('source_id'=>$source_id,'source_table'=>$source_table,'type'=>0,'display_text'=>'頂','count'=>10,'uid'=>11860))
 * @author zivss
 * @version TS3.0
 **/
class TipsWidget extends Widget {
    
    /**
     * @param integer source_id 資源ID
     * @param integer source_table 資源表
     * @param integer type 類型 0支援 1反對
     * @param string  display_text 顯示的字 如“頂”或“踩”
     * @param integer count 統計數目
     * @param integer uid 操作使用者UID 不填寫為登入使用者
     */
	public function render($data) {
		// 獲取所需的操作資料
		$var['sid'] = intval($data['source_id']);
		$var['stable'] = t($data['source_table']);
		$var['uid'] = empty($data['uid']) ? $GLOBALS['ts']['mid'] : intval($data['uid']);
		$var['type'] = intval($data['type']);
		$var['displayText'] = t($data['display_text']);
		$var['callback'] = t($data['callback']);

		// 獲取頂或踩的數目
		$var['count'] = model('Tips')->getSourceExec($var['sid'], $var['stable'], $var['type']);
		$var['whetherExec'] = model('Tips')->whetherExec($var['sid'], $var['stable'], $var['uid'], $var['type']);

		// 渲染頁面路徑
		$content = $this->renderFile(dirname(__FILE__)."/tips.html", $var);
		
		return $content;
	}

	/**
	 * 執行頂或踩的操作
	 * @return ajax傳送資訊 0（添加失敗）、1（添加成功）、2（已經添加）
	 */
	public function doExec() {
		$sid = intval($_POST['sid']);
		$stable = t($_POST['stable']);
		// $uid = $GLOBALS['ts']['mid'];
		$uid = intval($_POST['uid']);
		$type = intval($_POST['type']);

		$res = model('Tips')->doSourceExec($sid, $stable, $uid, $type);
		echo $res;
	}
}