<?php
/**
 * 如果一個頁面上需要使用多個上傳的wigdet 請傳入參數 end(不同的值)用來區別
 * 如果有自己的上傳模板,請傳入tpl值用來區別
 * @author yKF48801
 *
 */
class UploadAttachWidget extends Widget{
    private  static $rand = 1;
	public function render($data){
		//默認參數
		$var['callback']	=	'attach_upload_success';
		$var['l_button']	=	'瀏 覽';
		$var['l_loading']	=	'正在上傳...';
		$var['l_succes']	=	'上傳完成.';
		//上傳類型，默認為附件
		if(!isset($data['type'])){
			$var['type']		=	'attach';
		}else{
			$var['type']	=   $data['type'];
		}
		//上傳個數限制.默認10個
		if(!isset($data['limit'])){
			$var['limit']	=   10;
		}else{
			$var['limit']	=   $data['limit'];
		}
		//獲取後臺配置，設定的大小不能大於後臺配置的上傳大小，也不能大於系統支援的大小
		if(!isset($data['allow_size']) || empty($data['allow_size'])){
			$attachopt = model('Xdata')->get('admin_Config:attach');
			$attachopt['allow_size'] = ($attachopt['attach_max_size'] <= ini_get("upload_max_filesize"))?$attachopt['attach_max_size']:intval(ini_get("upload_max_filesize"));
		}
		$data['allow_size'] = (isset($data['allow_size']) && $data['allow_size']<=$attachopt['allow_size'])?($data['allow_size'])."MB":$attachopt['allow_size'].'MB';
		//獲取後臺配置，設定的類型必須是後臺配置的類型中的
		if(!isset($data['allow_exts']) || empty($data['allow_exts'])){
			$data['allow_exts'] = $attachopt['attach_allow_extension'];
		}
		//編輯時.帶入已有附件的參數 以逗號分割的附件IDs或陣列
		$aids	=	$data['edit'];
		if(is_array($aids)){
			$var['editdata']	=	X('Xattach')->getAttach($aids);
		}
 		//合併參數
 		!isset($data['end']) && $data['end']='';
		//模版賦值
		$var	=	array_merge($var,$data);
		$var['rand']  = self::$rand;
		if($data['tpl'] == 'flash'){
		    //渲染模版
		    $content = $this->renderFile(dirname(__FILE__)."/FlashUploadAttach.html",$var);
		}else{
		    //渲染模版
		    $content = $this->renderFile(dirname(__FILE__)."/UploadAttach.html",$var);
		}
		self::$rand ++;

		unset($var,$data);
        //輸出資料
		return $content;
    }
}
?>