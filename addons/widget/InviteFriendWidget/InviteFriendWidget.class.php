<?php
/**
 * 邀請好友
 * @example {:W('InviteFriend')}
 * @version TS3.0
 */
class InviteFriendWidget extends Widget{

	/**
	 * 渲染邀請好友頁面
	 */
	public function render($data){

		//渲染模版
		$content = $this->renderFile(dirname(__FILE__)."/content.html",$var);

		unset($var,$data);

		//輸出資料
		return $content;
    }
}
?>