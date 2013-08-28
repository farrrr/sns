<?php
/**
 * 關注使用者按鈕Widget
 * @example W('FollowBtn', array('fid'=>10000, 'uname'=>'uname', 'follow_state'=>$fallowState))
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FollowBtnWidget extends Widget {

	/**
     * 渲染關注按鈕模板
     * @example
     * $data['fid'] integer 目標使用者的ID
     * $data['uname'] string 目標使用者的昵稱
     * $data['follow_state'] array 當前使用者與目標使用者的關注狀態，array('following'=>1,'follower'=>0)
     * $data['isrefresh'] integer 操作成功後是否重新整理頁面
     * @param array $data 渲染的相關配置參數
     * @return string 渲染後的模板資料
	 */
	public function render($data) {
		$var = array();
		$var['type'] = 'normal';
		is_array($data) && $var = array_merge($var, $data);
		// 渲染模版
		$content = $this->renderFile(dirname(__FILE__) . "/{$var['type']}.html", $var);
		unset($var,$data);
		// 輸出資料
		return $content;
    }
}