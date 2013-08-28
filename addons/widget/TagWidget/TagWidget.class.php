<?php
/**
 * 標籤選擇
 * @example W('Tag', array('width'=>'500px', 'appname'=>'support','apptable'=>'support','row_id'=>0,'tpl'=>'show','tag_url'=>'aaa','name'=>'public'))
 * @author zhishisoft
 * @version TS3.0
 */
class TagWidget extends Widget{

	/**
	 * @param string width wigdet顯示寬度
     * @param string appname 資源對應的應用名稱
     * @param string apptable 資源對應的表
     * @param integer row_id 資源在資源所在表中的主鍵ID,為0表示該資源為新加資源，需要在資源添加,往sociax_app_tag表添加相關資料
     * @param string tpl 顯示模版，tag/show 默認是tag，如果為show表示只顯示標籤
     * @param string tag_url 標簽上的連結字首，為空表示標籤沒有連結，只針對tpl=show有效
     * @param string name 輸入框的input名稱,標籤的ID存儲的隱藏域名稱為 {name}_tags
	 */
	public function render($data)
    {
		$var = array();
		$var['width'] = "200px";
		$var['appname'] = 'public';
		$var['apptable'] = 'user';
		$var['row_id'] = 0;
		$var['tpl'] = 'tag';
        $var['tag_num'] = 10;
		is_array($data) && $var = array_merge($var,$data);
		// 清除快取
		model('Cache')->rm('temp_'.$var['apptable'].$GLOBALS['ts']['mid']);
		$var['add_url'] = U('widget/Tag/addTag',array('appname'=>$var['appname'],'apptable'=>$var['apptable'],'row_id'=>$var['row_id']));
		$var['delete_url'] = U('widget/Tag/deleteTag',array('appname'=>$var['appname'],'apptable'=>$var['apptable'],'row_id'=>$var['row_id']));
        // 獲取標籤
		$tags = model('Tag')->setAppName($var['appname'])->setAppTable($var['apptable'])->getAppTags($var['row_id']);
		$var['tags'] = $tags;
		// 以選中ID
		$var['tags_my'] = implode(',', array_flip($tags));

        // 獲取推薦標籤
        $uid = intval($data['uid']);
        $var['uid'] = $uid;
        $var['selected'] = model('UserCategory')->getRelatedUserInfo($uid);
        !empty($var['selected']) && $var['selectedIds'] = getSubByKey($var['selected'], 'user_category_id');
        $var['nums'] = count($tags);
        $var['categoryTree'] = model('CategoryTree')->setTable('user_category')->getNetworkList();
        foreach($var['categoryTree'] as $key => $value) {
            if(empty($value['child'])) {
                unset($var['categoryTree'][$key]);
            }
        }

		$content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].".html", $var);
		
		return $content;
	}

	/*
	 * 添加個人標籤
	 * @access public
	 * @return void
	 */
    public function addTag()
    {
    	// 獲取標籤內容
    	$_POST['name'] = t($_POST['name']);
    	// 判斷是否為空
    	if(empty($_POST['name'])) {
    		exit(json_encode(array('status'=>0,'info'=>L('PUBLIC_TAG_NOEMPTY'))));
    	}
    	// 其他相關參數
    	$appName = t($_REQUEST['appname']);
    	$appTable = t($_REQUEST['apptable']);
    	$row_id = intval($_REQUEST['row_id']);
    	$result = model('Tag')->setAppName($appName)->setAppTable($appTable)->addAppTags($row_id, t($_POST['name']));
		// 返回相關參數
		$return['info'] = model('Tag')->getError();
		$return['status'] = !empty($result) > 0 ? 1 : 0;
		$return['data'] = $result;
		exit(json_encode($return));
    }

	/*
	 * 刪除個人標籤
	 * @access public
	 * @return void
	 */
    public function deleteTag(){
    	$appName  = t($_REQUEST['appname']);
    	$appTable = t($_REQUEST['apptable']);
    	$row_id	  = intval($_REQUEST['row_id']);
    	$result = model('Tag')->setAppName($appName)->setAppTable($appTable)->deleteAppTag($row_id, t($_POST['tag_id']));
		
		$return['info'] 	= model('Tag')->getError();
		$return['status']	= $result;
		$return['data']	    = null;
		echo json_encode($return);exit();
    }

    /**
     * 獲取標籤的ID
     * @access public 
     * @return void
     */
    public function getTagId()
    {
    	// 獲取標簽名稱
    	$name = t($_POST['name']);
    	// 判斷標籤是否為空
    	if(empty($name)) {
    		$res['status'] = 0;
    		$res['info'] = L('PUBLIC_TAG_NOEMPTY');
    		exit(json_encode($res));
    	}
    	// 其他相關參數
    	$appName = t($_REQUEST['appname']);
    	$appTable = t($_REQUEST['apptable']);
    	// $rowId = intval($_REQUEST['row_id']);
    	$tagInfo = model('Tag')->setAppName($appName)->setAppTable($appTable)->getTagId($name);

    	if($tagInfo === false) {
    		$res['status'] = 0;
    		$res['info'] = '獲取標籤ID失敗';
    	} else {
    		$res['status'] = 1;
    		$res['info'] = '獲取標籤ID成功';
    		$res['data'] = $tagInfo;
    	}

    	exit(json_encode($res));
    }
}