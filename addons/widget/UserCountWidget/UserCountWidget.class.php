<?php
/**
 * 使用者統計Widget
 * @version TS3.0
 */
	class UserCountWidget extends Widget{
		
		public function render($data)
		{
			$content = '';
			return $content;
		}
		
		/**
		 * 獲取指定使用者的通知統計數目
		 */
		public function getUnreadCount()
		{
			
			$count = model('UserCount')->getUnreadCount($GLOBALS['ts']['mid']);
			$data['status'] = 1;
			$data['data'] = $count;
			echo json_encode($data);
		}
	}
?>