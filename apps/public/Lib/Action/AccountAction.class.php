<?php
/**
 * 賬號設定控制器
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class AccountAction extends Action 
{
	private $_profile_model;			// 使用者檔案模型物件欄位
	
	/**
	 * 控制器初始化，例項化使用者檔案模型物件
	 * @return void
	 */
	protected function _initialize() {

		$this->_profile_model = model('UserProfile');
		// 從資料庫讀取
		$profile_category_list = $this->_profile_model->getCategoryList();
		
		$tab_list[] = array('field_key'=>'index','field_name'=>L('PUBLIC_PROFILESET_INDEX'));				// 基本資料
		$tab_list[] = array('field_key'=>'tag','field_name'=>L('PUBLIC_PROFILE_TAG'));				// 基本資料
		$tab_lists = $profile_category_list;

		foreach($tab_lists as $v) {
			$tab_list[] = $v;			// 後臺添加的資料配置分類
		}
		$tab_list[] = array('field_key'=>'avatar','field_name'=>L('PUBLIC_IMAGE_SETTING'));				// 頭像設定
		$tab_list[] = array('field_key'=>'domain','field_name'=>L('PUBLIC_DOMAIN_NAME'));				// 個性域名
		$tab_list[] = array('field_key'=>'authenticate','field_name'=>'申請認證');	// 申請認證
		$tab_list_preference[] = array('field_key'=>'privacy','field_name'=>L('PUBLIC_PRIVACY'));					// 隱私設定
		$tab_list_preference[] = array('field_key'=>'notify','field_name'=>'通知設定');					// 通知設定
		$tab_list_preference[] = array('field_key'=>'blacklist','field_name'=>'黑名單');					// 黑名單
		$tab_list_security[] = array('field_key'=>'security','field_name'=>L('PUBLIC_ACCOUNT_SECURITY'));		// 帳號安全	
		
		//插件增加選單
		$tab_list_security[] = array('field_key'=>'bind','field_name'=>'帳號繫結');		// 帳號繫結
		
		$this->assign('tab_list',$tab_list);
		$this->assign('tab_list_preference',$tab_list_preference);
		$this->assign('tab_list_security',$tab_list_security);
	}

	/**
	 * 基本設定頁面
	 */
	public function index()
	{
		$this->appCssList[] = 'account.css';
		$user_info = model('User')->getUserInfo($this->mid);
		$data = $this->_getUserProfile();
		$data['langType'] = model('Lang')->getLangType();
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user_info['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$this->assign('user_info', $user_info);
		$this->assign($data);
		$this->setTitle( L('PUBLIC_PROFILESET_INDEX') );			// 個人設定
		$this->setKeywords( L('PUBLIC_PROFILESET_INDEX') );
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user_info['category'].$user_info['location'].','.implode(',', $user_tag[$this->mid]).','.$user_info['intro']));
		$this->display();
	}

	/**
	 * 擴展資訊設定頁面
	 * @param string $extend 擴展類目名稱(為插件準備)
	 */
	public function _empty($extend) {
		$cid = D('user_profile_setting')->where("field_key='".ACTION_NAME."'")->getField('field_id');
		$data = $this->_getUserProfile();
		$data['cid'] = $cid;
		$this->assign($data);
		$this->display('extend');
	}

	/**
	 * 獲取登入使用者的檔案資訊
	 * @return 登入使用者的檔案資訊
	 */
	private function _getUserProfile() {
		$data['user_profile'] = $this->_profile_model->getUserProfile($this->mid);
		$data['user_profile_setting'] = $this->_profile_model->getUserProfileSettingTree();

		return $data;
	}

	/**
	 * 儲存基本資訊操作
	 * @return json 返回操作後的JSON資訊資料
	 */
	public function doSaveProfile() {
		$res = true;
		// 儲存使用者表資訊
		if(!empty($_POST['sex'])) {
			$save['sex']  = 1 == intval($_POST['sex']) ? 1 : 2;
		//	$save['lang'] = t($_POST['lang']);
			$save['intro'] = t($_POST['intro']);
			// 添加地區資訊
			$save['location'] = t($_POST['city_names']);
			$cityIds = t($_POST['city_ids']);
			$cityIds = explode(',', $cityIds);
			if(!$cityIds[0] || !$cityIds[1] || !$cityIds[2]) $this->error('請選擇完整地區');
			isset($cityIds[0]) && $save['province'] = intval($cityIds[0]);
			isset($cityIds[1]) && $save['city'] = intval($cityIds[1]);
			isset($cityIds[2]) && $save['area'] = intval($cityIds[2]);
			// 修改使用者昵稱
			$uname = t($_POST['uname']);
			$oldName = t($_POST['old_name']);
			$save['uname'] = filter_keyword($uname);
			$res = model('Register')->isValidName($uname, $oldName);
			if(!$res) {
				$error = model('Register')->getLastError();
				return $this->ajaxReturn(null, model('Register')->getLastError(), $res);		
			}
			//如果包含中文將中文翻譯成拼音
			if ( preg_match('/[\x7f-\xff]+/', $save['uname'] ) ){
				//昵稱和呢稱拼音儲存到搜索欄位
				$save['search_key'] = $save['uname'].' '.model('PinYin')->Pinyin( $save['uname'] );
			} else {
				$save['search_key'] = $save['uname'];
			}
			$res = model('User')->where("`uid`={$this->mid}")->save($save);
			$res && model('User')->cleanCache($this->mid);	
			$user_feeds = model('Feed')->where('uid='.$this->mid)->field('feed_id')->findAll();
			if($user_feeds){
				$feed_ids = getSubByKey($user_feeds, 'feed_id');
				model('Feed')->cleanCache($feed_ids,$this->mid);
			}
		}
		// 儲存使用者資料配置欄位
		(false !== $res) && $res = $this->_profile_model->saveUserProfile($this->mid, $_POST);
		// 儲存使用者標籤資訊
		$tagIds = t($_REQUEST['user_tags']);
		!empty($tagIds) && $tagIds = explode(',', $tagIds);
		$rowId = intval($this->mid);
		if(!empty($rowId)) {
			$registerConfig = model('Xdata')->get('admin_Config:register');
			if(count($tagIds) > $registerConfig['tag_num']) {
				return $this->ajaxReturn(null, '最多只能設定'.$registerConfig['tag_num'].'個標籤', false);
			}
			model('Tag')->setAppName('public')->setAppTable('user')->updateTagData($rowId, $tagIds);
		}
		$result = $this->ajaxReturn(null, $this->_profile_model->getError(), $res);
		return $this->ajaxReturn(null, $this->_profile_model->getError(), $res);
	}

	/**
	 * 頭像設定頁面
	 */
	public function avatar() {	
		model('User')->cleanCache($this->mid);
		$user_info = model('User')->getUserInfo($this->mid);
		$this->assign('user_info', $user_info);

		$this->setTitle( L('PUBLIC_IMAGE_SETTING') );			// 個人設定
		$this->setKeywords( L('PUBLIC_IMAGE_SETTING') );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user_info['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user_info['category'].$user_info['location'].','.implode(',', $user_tag[$this->mid]).','.$user_info['intro']));
		$this->display();
	}

	/**
	 * 儲存登入使用者的頭像設定操作
	 * @return json 返回操作後的JSON資訊資料
	 */
	public function doSaveAvatar() {
        $dAvatar = model('Avatar');
        $dAvatar->init($this->mid); 			// 初始化Model使用者id
        // 安全過濾
        $step = t($_GET['step']);
        if('upload' == $step) {
            $result = $dAvatar->upload();
        } else if('save' == $step) {
            $result = $dAvatar->dosave();
        }
        model('User')->cleanCache($this->mid);
        $user_feeds = model('Feed')->where('uid='.$this->mid)->field('feed_id')->findAll();
		if($user_feeds){
			$feed_ids = getSubByKey($user_feeds, 'feed_id');
			model('Feed')->cleanCache($feed_ids,$this->mid);
		}
    	$this->ajaxReturn($result['data'], $result['info'], $result['status']);
	}

	/**
	 * 儲存登入使用者的頭像設定操作，Flash上傳
	 * @return string 操作後的反饋資訊
	 */
	public function doSaveUploadAvatar() {
		$data['big'] = base64_decode($_POST['png1']);
		$data['middle'] = base64_decode($_POST['png2']);
		$data['small'] = base64_decode($_POST['png3']);
		if(empty($data['big']) || empty($data['middle']) || empty($data['small'])) {
			exit('error='.L('PUBLIC_ATTACHMENT_UPLOAD_FAIL'));						// 圖片上傳失敗，請重試
		}
		if(model('Avatar')->init($this->mid)->saveUploadAvatar($data, $this->user)) {
			exit('success='.L('PUBLIC_ATTACHMENT_UPLOAD_SUCCESS'));					// 附件上傳成功
		} else {
			exit('error='.L('PUBLIC_ATTACHMENT_UPLOAD_FAIL'));						// 圖片上傳失敗，請重試
		}	
	}

	/**
	 * 標籤設定頁面
	 */
	public function tag() {
		$registerConfig = model('Xdata')->get('admin_Config:register');
		$this->assign('tag_num',$registerConfig['tag_num']);
		$this->display();
	}
	
	/**
	 * 隱私設定頁面
	 */
	public function privacy() {
    	$user_privacy = D('UserPrivacy')->getUserSet($this->mid);
    	$this->assign('user_privacy', $user_privacy);

    	$user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( L('PUBLIC_PRIVACY') );			
		$this->setKeywords( L('PUBLIC_PRIVACY') );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
    	$this->display();
	}

	/**
	 * 儲存登入使用者隱私設定操作
	 * @return json 返回操作後的JSON資訊資料
	 */
	public function doSavePrivacy() {
		//dump($_POST);exit;
		$res = model('UserPrivacy')->dosave($this->mid, $_POST);
    	$this->ajaxReturn(null, model('UserPrivacy')->getError(), $res);
	}

	/**
	 * 個性域名設定頁面
	 */
	public function domain() {
    	// 是否啟用個性化域名
    	$user = model('User')->getUserInfo($this->mid);
    	$data['user_domain'] = $user['domain'];
    	$this->assign($data);

    	$this->setTitle( L('PUBLIC_DOMAIN_NAME') );			// 個人設定
		$this->setKeywords( L('PUBLIC_DOMAIN_NAME') );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
    	$this->display();
	}

	/**
	 * 儲存使用者個性域名操作
	 * @return json 返回操作後的JSON資訊資料
	 */
	public function doSaveDomain() {
		$domain = t($_POST['domain']);
		// 驗證資訊
		if(strlen($domain) < 5) {
			$this->ajaxReturn(null, '域名長度不能少於5個字元', 0);			// 僅限5個字元以上20個字元以內的英文/數字/下劃線，以英文字母開頭，不能含有特殊字元，一經設定，無法更改。
		}
		if(strlen($domain) > 20) {
			$this->ajaxReturn(null, L('PUBLIC_SHORT_DOMAIN_CHARACTERLIMIT'), 0);	// 域名長度不能超過20個字元
		}
		if(!ereg('^[a-zA-Z][_a-zA-Z0-9]+$', $domain)) {
			$this->ajaxReturn(null, '僅限於英文/數字/下劃線，以英文字母開頭，不能含有特殊字元', 0);			// 僅限5個字元以上20個字元以內的英文/數字/下劃線，以英文字母開頭，不能含有特殊字元，一經設定，無法更改。
		}
		
		$keywordConfig = model('Xdata')->get('keywordConfig');
		$keywordConfig =explode(",", $keywordConfig);
		if(!empty($keywordConfig) && in_array($domain, $keywordConfig)) {
			$this->ajaxReturn(null, L('PUBLIC_DOMAIN_DISABLED'), 0);				// 該個性域名已被禁用
		}
		
		// 預留域名使用
		$sysDomin = model('Xdata')->getConfig('sys_domain', 'site');
		$sysDomin = explode(",", $sysDomin);
		if(!empty($sysDomin) && in_array($domain, $sysDomin)){
			$this->ajaxReturn(null, L('PUBLIC_DOMAIN_DISABLED'), 0);				// 該個性域名已被禁用
		}
		
		if(model('User')->where("uid!={$this->mid} AND domain='{$domain}'")->count()) {
			$this->ajaxReturn(null, L('PUBLIC_DOMAIN_OCCUPIED'), 0);				// 此域名已經被使用
		} else {
			$user_info = model('User')->getUserInfo($this->mid);
			!$user_info['domian'] && model('User')->setField('domain', "$domain", 'uid='.$this->mid);
			model('User')->cleanCache($this->mid);
			$this->ajaxReturn(null, L('PUBLIC_DOMAIN_SETTING_SUCCESS'), 1);			// 域名設定成功
		}
	}

	/**
	 * 賬號安全設定頁面
	 */
	public function security() {
		$user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( L('PUBLIC_ACCOUNT_SECURITY') );			
		$this->setKeywords( L('PUBLIC_ACCOUNT_SECURITY') );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
		$this->display();
	}

	/**
	 * 修改登入使用者賬號密碼操作
	 * @return json 返回操作後的JSON資訊資料
	 */
    public function doModifyPassword() {
    	$_POST['password'] = t($_POST['password']);
    	$_POST['repassword'] = t($_POST['repassword']);
    	// 驗證資訊
    	if($_POST['password'] != $_POST['repassword']) {
    		$this->error(L('PUBLIC_PASSWORD_UNSIMILAR'));			// 新密碼與確認密碼不一致
    	}
    	if(strlen($_POST['password']) < 6) {
			$this->error('密碼太短了，最少6位');				
		}
		if(strlen($_POST['password']) > 15) {
			$this->error('密碼太長了，最多15位');				
		}
		if($_POST['password'] == $_POST['oldpassword']) {
			$this->error(L('PUBLIC_PASSWORD_SAME'));				// 新密碼與舊密碼相同
		}

    	$user_model = model('User');
    	$map['uid'] = $this->mid;
    	$user_info = $user_model->where($map)->find();

    	if($user_info['password'] == $user_model->encryptPassword($_POST['oldpassword'], $user_info['login_salt'])) {
			$data['login_salt'] = rand(11111, 99999);
			$data['password'] = $user_model->encryptPassword($_POST['password'], $data['login_salt']);
			$res = $user_model->where("`uid`={$this->mid}")->save($data);
    		$info = $res ? L('PUBLIC_PASSWORD_MODIFY_SUCCESS') : L('PUBLIC_PASSWORD_MODIFY_FAIL');			// 密碼修改成功，密碼修改失敗
    	} else {
    		$info = L('PUBLIC_ORIGINAL_PASSWORD_ERROR');			// 原始密碼錯誤
    	}
    	return $this->ajaxReturn(null, $info, $res);
    }

    /**
     * 申請認證
     * @return void
     */
    public function authenticate(){
    	$auType = model('UserGroup')->where('is_authenticate=1')->findall();
    	$this->assign('auType', $auType);
    	$verifyInfo = D('user_verified')->where('uid='.$this->mid)->find();
    	if($verifyInfo['attach_id']){
			  $a = explode('|', $verifyInfo['attach_id']);
			  foreach($a as $key=>$val){
			  	if($val !== "") {
			  		$attachInfo = D('attach')->where("attach_id=$a[$key]")->find();
			  		$verifyInfo['attachment'] .= $attachInfo['name'].'&nbsp;<a href="'.getImageUrl($attachInfo['save_path'].$attachInfo['save_name']).'" target="_blank">下載</a><br />';
			  	}
			  }
		}
		// 獲取認證分類資訊
		if(!empty($verifyInfo['user_verified_category_id'])) {
			$verifyInfo['category']['title'] = D('user_verified_category')->where('user_verified_category_id='.$verifyInfo['user_verified_category_id'])->getField('title');
		}

		switch ($verifyInfo['verified']) {
			case '1':
				$status = '<i class="ico-ok"></i>已認證 <a href="javascript:void(0);" onclick="delverify()">登出認證</a>';
				break;
			case '0':
				$status = '<i class="ico-wait"></i>已提交認證，等待稽覈';
				break;
			case '-1':
				// 安全過濾
				$type = t($_GET['type']);
				if($type == 'edit'){
					$status = '<i class="ico-no"></i>未通過認證，請修改資料後重新提交';
					$this->assign('edit',1);
					$verifyInfo['attachIds'] = str_replace('|', ',', substr($verifyInfo['attach_id'],1,strlen($verifyInfo['attach_id'])-2));
				}else{
					$status = '<i class="ico-no"></i>未通過認證，請修改資料後重新提交 <a href="'.U('public/Account/authenticate',array('type'=>'edit')).'">修改認證資料</a>';
				}
				break;
			default:
				//$verifyInfo['usergroup_id'] = 5;
				$status = '未認證';
				break;
		}
		//附件限制
		$attach = model('Xdata')->get("admin_Config:attachimage");
		$imageArr = array('gif','jpg','jpeg','png','bmp');
		foreach($imageArr as $v){
			if(strstr($attach['attach_allow_extension'],$v)){
				$imageAllow[] = $v;
			}
		}
		$attachOption['attach_allow_extension'] = implode(', ', $imageAllow);
		$attachOption['attach_max_size'] = $attach['attach_max_size'];
		$this->assign('attachOption',$attachOption);

		// 獲取認證分類
		$category = D('user_verified_category')->findAll();
		foreach($category as $k=>$v){
			$option[$v['pid']] .= '<option ';
			if($verifyInfo['user_verified_category_id']==$v['user_verified_category_id']){
				$option[$v['pid']] .= 'selected';
			}
			$option[$v['pid']] .= ' value="'.$v['user_verified_category_id'].'">'.$v['title'].'</option>';
		}
		//dump($option);exit;
		$this->assign('option', json_encode($option));
		$this->assign('options', $option);
		$this->assign('category', $category);
    	$this->assign('status' , $status);
    	$this->assign('verifyInfo' , $verifyInfo);
    	//dump($verifyInfo);exit;

    	$user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( '申請認證' );			
		$this->setKeywords( '申請認證' );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
    	$this->display();
    }

    /**
     * 提交申請認證
     * @return void
     */
    public function doAuthenticate(){
    	$verifyInfo = D('user_verified')->where('uid='.$this->mid)->find();      
        $data['usergroup_id'] = intval($_POST['usergroup_id']);
        if(!$data['usergroup_id']) $data['usergroup_id'] = 5;
       	$data['company'] = t($_POST['company']);	
        $data['realname'] = t($_POST['realname']);
        $data['idcard'] = t($_POST['idcard']);
        $data['phone'] = t($_POST['phone']);
        $data['reason'] = t($_POST['reason']);
        //$data['info'] = t($_POST['info']);
        $data['attach_id'] = t($_POST['attach_ids']);
        if(D('user_verified_category')->where('pid='.$data['usergroup_id'])->find()){
        	$data['user_verified_category_id'] = intval($_POST['verifiedCategory']);
    	}else{
    		$data['user_verified_category_id'] = 0;
    	}
        $Regx1 = '/^[0-9]*$/';
        $Regx2 = '/^[A-Za-z0-9]*$/';
        $Regx3 = '/^[A-Za-z|\x{4e00}-\x{9fa5}]+$/u';

        if($data['usergroup_id'] == 6){
        	if(strlen($data['company'])==0){
        		//$this->error('企業名稱不能為空');
        		echo '企業名稱不能為空';exit;
        	}
        	if(strlen($data['realname'])==0){
        		//$this->error('法人姓名不能為空');	
        		echo '法人姓名不能為空';exit;
        	}
        	if(strlen($data['idcard'])==0){
        		//$this->error('營業執照號不能為空');	
        		echo '營業執照號不能為空';exit;
        	}
        	if(strlen($data['phone'])==0){
        		//$this->error('聯繫方式不能為空');
        		echo '聯繫方式不能為空';exit;	
        	}
        	if(strlen($data['reason'])==0){
        		//$this->error('認證理由不能為空');
        		echo '認證理由不能為空';exit;	
        	}
        	if(preg_match($Regx3, $data['realname'])==0 || strlen($data['realname'])>30){
                echo '請輸入正確的法人姓名';exit;
            }  
        	// if(strlen($data['info'])==0){
        	// 	$this->error('認證資料不能為空');	
        	// }
        	if(preg_match($Regx2, $data['idcard'])==0){
        		//$this->error('請輸入正確的營業執照號');	
        		echo '請輸入正確的營業執照號';exit;	
        	}
        	
        }else{
        	if(strlen($data['realname'])==0){
        		//$this->error('真實姓名不能為空');	
        		echo '真實姓名不能為空';exit;
        	}
        	if(strlen($data['idcard'])==0){
        		//$this->error('身份證號碼不能為空');	
        		echo '身份證號碼不能為空';exit;

        	}
        	if(strlen($data['phone'])==0){
        		//$this->error('手機號碼不能為空');	
        		echo '手機號碼不能為空';exit;
        	}
        	if(strlen($data['reason'])==0){
        		//$this->error('認證理由不能為空');	
        		echo '認證理由不能為空';exit;
        	}
        	// if(strlen($data['info'])==0){
        	// 	$this->error('認證資料不能為空');	
        	// }
        	if(preg_match($Regx3, $data['realname'])==0 || strlen($data['realname'])>30){
                //$this->error('請輸入正確的姓名格式');
                echo '請輸入正確的姓名格式';exit;
            }  
        	if(preg_match($Regx2, $data['idcard'])==0 || preg_match($Regx1, substr($data['idcard'],0,17))==0 || strlen($data['idcard'])!==18){
        		//$this->error('請輸入正確的身份證號碼');	
        		echo '請輸入正確的身份證號碼';exit;
        	}
        	if(strlen($data['phone']) !== 11 || preg_match($Regx1, $data['phone'])==0){
                //$this->error('請輸入正確的手機號碼格式');
                echo '請輸入正確的手機號碼格式';exit;
            }
        }
        preg_match_all('/./us', $data['reason'], $matchs);   //一個漢字也為一個字元
        if(count($matchs[0])>140){
        	//$this->error('認證理由不能超過140個字元');	
        	echo '認證理由不能超過140個字元';exit;
        }
        // preg_match_all('/./us', $data['info'], $match);   //一個漢字也為一個字元
        // if(count($match[0])>140){
        // 	$this->error('認證資料不能超過140個字元');	
        // }       
    	if($verifyInfo){
    		$data['verified'] = 0;
    		$res = D('user_verified')->where('uid='.$verifyInfo['uid'])->save($data);
    	}else{
    		$data['uid'] = $this->mid; 
    		$res = D('user_verified')->add($data);
    	}
        if($res){
        	//echo '1';
        	model('Notify')->sendNotify($this->mid,'public_account_doAuthenticate');
        	$touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
			foreach($touid as $k=>$v){
				model('Notify')->sendNotify($v['uid'], 'verify_audit');
			}
        	//return $this->ajaxReturn(null, '申請成功，請等待稽覈', 1);
        	echo '1';
        }else{
        	//$this->error("申請失敗");
        	echo '申請失敗';exit;
        }
    }

    /**
     * 登出認證
     * @return bool 操作是否成功  1:成功   0:失敗
     */	
    public function delverify(){
    	$verified_group_id = D('user_verified')->where('uid='.$this->mid)->getField('usergroup_id');
    	$res = D('user_verified')->where('uid='.$this->mid)->delete();
    	$res2 = D('user_group_link')->where('uid='.$this->mid.' and user_group_id='.$verified_group_id)->delete();
    	if($res && $res2){
    		//清除許可權組 使用者組快取
    		model('Cache')->rm('perm_user_'.$this->mid);
    		model('Cache')->rm('user_group_'.$this->mid);
    		model('Notify')->sendNotify($this->mid,'public_account_delverify');
    		echo 1;
    	}else{
    		echo 0;
    	}
    }

    /**
     * 黑名單設定
     * @return void
     */
    public function blacklist(){

    	$user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( '黑名單' );			
		$this->setKeywords( '黑名單' );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
    	$this->display();
    }

    /**
     * 通知設定
     * @return void
     */
    public function notify(){
    	$user_privacy = D('UserPrivacy')->getUserSet($this->mid);
    	$this->assign('user_privacy', $user_privacy);

    	$user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( '通知設定' );			
		$this->setKeywords( '通知設定' );
		// 獲取使用者職業資訊
		$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
		$userCateArray = array();
		if(!empty($userCategory)) {
			foreach($userCategory as $value) {
				$user['category'] .= '<a href="#" class="link btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
			}
		}
		$user_tag = model('Tag')->setAppName('User')->setAppTable('user')->getAppTags(array($this->mid));
		$this->setDescription(t($user['category'].$user['location'].','.implode(',', $user_tag[$this->mid]).','.$user['intro']));
    	$this->display();
    }

    /**
     * 修改使用者身份
     */
    public function editUserCategory(){
    	$this->assign('mid', $this->mid);
    	$this->display();
    }

    /**
     * 執行修改使用者身份操作
     */
    public function doEditUserCategory(){
    	$userCategoryIds = t($_POST['user_category_ids']);
		empty($userCategoryIds) && exit($this->error('請至少選擇一個職業資訊'));
		$userCategoryIds = explode(',', $userCategoryIds);
		$userCategoryIds = array_filter($userCategoryIds);
		$userCategoryIds = array_unique($userCategoryIds);
		$result = model('UserCategory')->updateRelateUser($this->mid, $userCategoryIds);
		if($result) {
			// 獲取使用者身份資訊
			$userCategory = model('UserCategory')->getRelatedUserInfo($this->mid);
			$userCateArray = array();
			if(!empty($userCategory)) {
				foreach($userCategory as $value) {
					$category .= '<a href="#" class="btn-cancel"><span>'.$value['title'].'</span></a>&nbsp;&nbsp;';
				}
			}
			$this->ajaxReturn($category, L('PUBLIC_SAVE_SUCCESS'), $result);
		} else {
			$this->ajaxReturn(null, '職業資訊儲存失敗', $result);
		}
    }

    /**
     * 帳號繫結
     */
    public function bind(){
 		// 郵箱繫結
 		// 	$user = M('user')->where('uid='.$this->mid)->field('email')->find();
		// $replace = substr($user['email'],2,-3);
		// for ($i=1;$i<=strlen($replace);$i++){
		// 	$replacestring.='*';
		// }
		// $data['email'] = str_replace(  $replace, $replacestring ,$user['email'] );
        
        // 站外帳號繫結
        $bindData = array();
        Addons::hook('account_bind_after',array('bindInfo'=>&$bindData));
        $data['bind']  = $bindData;
   	    $this->assign($data);
   	    $user = model('User')->getUserInfo($this->mid);
    	$this->setTitle( '帳號繫結' );			
		$this->setKeywords( '帳號繫結' );
		$this->setDescription(t(implode(',', getSubByKey($data['bind'],'name'))));
   	    $this->display();
    }
}