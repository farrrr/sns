<?php
/**
 * 使用者身份選擇Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserCategoryWidget extends Widget {

	/**
	 * 模板渲染
	 * @param array $data 相關資料
	 * @return string 使用者身份選擇模板
	 */
	public function render($data) {
        // 設定模板
        $template = empty($data['tpl']) ? 'category' : t($data['tpl']);
        // 選擇模板資料
        switch($template) {
            case 'pop':
            case 'category':
                $var = $this->_login($data);
                break;
            case 'userCategory':
                $var = $this->_user($data);
                break;
        }
        // 渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$template.".html", $var);
        // 輸出資料
        return $content;
    }

    /**
     * 添加使用者與使用者身份的關聯資訊
     */
    public function addRelatedUser() {
    	$uid = intval($_POST['uid']);
    	$cid = intval($_POST['cid']);
    	$res = model('UserCategory')->addRelatedUser($uid, $cid);
    	$result['status'] = $res ? 1 : 0;
    	exit(json_encode($result));
    }

    /**
     * 刪除使用者與使用者身份的關聯資訊
     */
    public function deleteRelateUser() {
    	$uid = intval($_POST['uid']);
    	$cid = intval($_POST['cid']);
    	$res = model('UserCategory')->deleteRelatedUser($uid, $cid);
    	$result['status'] = $res ? 1 : 0;
    	exit(json_encode($result));
    }

    /**
     * 登入頁面，獲取選擇模板資料
     * @param array $data 參數資料
     * @return array 獲取的模板資料
     */
    private function _login($data) {
        // 獲取使用者分類資訊
        $uid = intval($data['uid']);
        $var['uid'] = $uid;
        $var['selected'] = model('UserCategory')->getRelatedUserInfo($uid);
        !empty($var['selected']) && $var['selectedIds'] = getSubByKey($var['selected'], 'user_category_id');
        $var['nums'] = count($var['selectedIds']);
        //$var['categoryTree'] = model('UserCategory')->getNetworkList();
        $var['categoryTree'] = model('CategoryTree')->setTable('user_category')->getNetworkList();
        foreach($var['categoryTree'] as $key => $value) {
            if(empty($value['child'])) {
                unset($var['categoryTree'][$key]);
            }
        }

        return $var;
    }

    /**
     * 人物分類頁面，獲取選擇模板資料
     * @param array $data 參數資料
     * @return array 獲取的模板資料
     */
    public function _user($data) {
        // 獲取跳轉連結
        !empty($data['url']) && $var['url'] = t($data['url']);
        // 獲取分類ID
        !empty($data['cid']) && $var['cid'] = intval($data['cid']);
        // 獲取使用者分類資訊
        $uid = intval($data['uid']);
        $var['uid'] = $uid;
        $var['selected'] = model('UserCategory')->getRelatedUserInfo($uid);
        $var['selectedIds'] = getSubByKey($var['selected'], 'user_category_id');
        $var['nums'] = count($var['selectedIds']);
        $var['categoryTree'] = model('UserCategory')->getNetworkList();
        foreach($var['categoryTree'] as $key => $value) {
            if(empty($value['child'])) {
                unset($var['categoryTree'][$key]);
            }
        }
        $aCids = getSubByKey($var['categoryTree'], 'id');
        if(!in_array($var['cid'], $aCids)) {
            $map['user_category_id'] = $var['cid'];
            $var['childCid'] = $var['cid'];
            $var['cid'] = model('UserCategory')->where($map)->getField('pid');
        }

        return $var;
    }
}