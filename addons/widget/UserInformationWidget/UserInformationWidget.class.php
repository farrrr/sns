<?php
/**
 * 使用者資訊顯示Widget
 * @author guolee226@gmail.com
 * @version TS3.0
 */
class UserInformationWidget extends Widget {

	/**
	 * 模板渲染
	 * @param array $data 相關資料
	 * @return string 使用者身份選擇模板
	 */
	public function render($data) {
		$var['uid'] = intval($data['uid']);
		$var['tpl'] = t($data['tpl']);
		// 是否有返回首頁的連結
		$var['isReturn'] = $data['isReturn'] ? true : false;
		// 獲取使用者資訊
		$var['userInfo'] = model('User')->getUserInfo($var['uid']);
		// 獲取使用者使用者組資訊
		$userGids = model('UserGroupLink')->getUserGroup($var['uid']);
		$userGroupData = model('UserGroup')->getUserGroupByGids($userGids[$var['uid']]);
		foreach($userGroupData as $key => $value) {
			if($value['user_group_icon'] == -1) {
				unset($userGroupData[$key]);
				continue;
			}
			$userGroupData[$key]['user_group_icon_url'] = THEME_PUBLIC_URL.'/image/usergroup/'.$value['user_group_icon'];
		}
		$var['userGroupData'] = $userGroupData;
		// 獲取相關的統計數目
		$var['userData'] = model('UserData')->getUserData();
		foreach($var['userData'] as &$value) {
			$value = $this->limitedNumbers($value, 99999);
		}
		// 獲取使用者積分資訊
		$var['userCredit'] = model('Credit')->getUserCredit($var['uid']);
		// Tab選中類型
		$var['current'] = '';
		strtolower(ACTION_NAME) == 'myfeed' && strtolower(MODULE_NAME) == 'index' && $var['current'] = 'myfeed';
		strtolower(ACTION_NAME) == 'following' && strtolower(MODULE_NAME) == 'index' && $var['current'] = 'following';
		strtolower(ACTION_NAME) == 'follower' && strtolower(MODULE_NAME) == 'index' && $var['current'] = 'follower';
		strtolower(ACTION_NAME) == 'index' && strtolower(MODULE_NAME) == 'collection' && $var['current'] = 'collection';
		// 使用者分類資訊
		$map['app'] = 'public';
		$map['table'] = 'user';
		$map['row_id'] = $var['uid'];
		$var['userTags'] = D()->table(C('DB_PREFIX').'app_tag AS a LEFT JOIN '.C('DB_PREFIX').'tag AS b ON a.tag_id = b.tag_id')->where($map)->findAll();
		// 獲取關注狀態
		$GLOBALS['ts']['mid'] != $var['uid'] && $var['follow_state'] = model('Follow')->getFollowState($GLOBALS['ts']['mid'], $var['uid']);

        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].".html", $var);
        // 輸出資料
        return $content;
    }

    /**
     * 將統計資料限定指定的數目
     * @param integer $nums 指定的數目
     * @param integer $limit 限定的數目
     */
    private function limitedNumbers($nums, $limit = 99999) {
    	$nums > $limit && $nums = $limit.'+';
    	return $nums;
    }
}