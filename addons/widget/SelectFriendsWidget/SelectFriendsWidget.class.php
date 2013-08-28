<?php
/**
 * 選擇好友Widget
 */
class SelectFriendsWidget extends Widget{

	/**
	 * 選擇好友Widget
	 * 
	 * $data的參數:
	 * array(
	 * 	'name'(可選)	=> '表單的name', // 默認為"fri_ids"
	 * )
	 * 
	 * @see Widget::render()
	 */
	public function render($data){

		$data['name'] || $data['name']= 'fri_ids';

        $content = $this->renderFile(dirname(__FILE__) . '/SelectFriends.html',$data);

        return $content;

    }

}
?>