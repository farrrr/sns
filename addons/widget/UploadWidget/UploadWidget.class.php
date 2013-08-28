<?php
/**
 * 檔案上傳
 * 主要由core.uploadFile完成前端ajaxpost功能
 * 要自定義回撥顯示則需要自定義回撥函數
 * @example {:W('Upload',array('callback'=>'callback','uploadType'=>'file','inputname'=>'inputname','urlquery'=>'a=aaa&b=bb','attachIds'=>'1,2,3,4'))}
 * @author jason
 * @version TS3.0
 */

class UploadWidget extends Widget{
    private  static $rand = 1;

    /**
     * @param string callback 上傳之後的回撥函數，不自定義就不要傳遞此參數
     * @param string uploadType 上傳類型，分file和image
     * @param string inputname 上傳元件的name
     * @param string urlquery 需要存儲在附件表的一些資訊，暫時可能用到的不多
     * @param mixed attachIds 已有附件ID，可以為空
     */
	public function render($data){
		$var = array();
        $var['callback']    = "''";
        $var['uploadType']  = 'file';
        $var['inputname']   = 'attach';
        $var['attachIds']   = '';
        $var['inForm']      =  1;
        $var['limit'] = empty($data['limit']) ? 0 : intval($data['limit']);

        is_array($data) && $var = array_merge($var,$data);

        $uploadType = in_array($var['uploadType'],array('image','file','cloudimage','cloudfile'))?t($var['uploadType']):'file';
        $uploadTemplate = $uploadType.'upload.html';

        if(!empty($var['attachIds'])){
            !is_array($var['attachIds']) && $var['attachIds'] = explode(',',$var['attachIds']);
            
            $attachInfo = model('Attach')->getAttachByIds($var['attachIds']);
            foreach($attachInfo as $v){
                if($var['uploadType'] == 'image'){   
                    $v['src']   = getImageUrl($v['save_path'].$v['save_name'],100,100,true);
                }
                $v['extension']  = strtolower($v['extension']);
                $var['attachInfo'][] = $v;
            }
         
            $var['attachIds'] = implode('|',$var['attachIds']);

        }

        //渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$uploadTemplate,$var);
        
        unset($var,$data);
        
        //輸出資料
        return $content;
    }

    /**
     * 附件上傳
     * @return array 上傳的附件的資訊
     */
    public function save(){

    	$data['attach_type'] = t($_REQUEST['attach_type']);
        $data['upload_type'] = $_REQUEST['upload_type']?t($_REQUEST['upload_type']):'file';

        $thumb  = intval($_REQUEST['thumb']);
        $width  = intval($_REQUEST['width']);
        $height = intval($_REQUEST['height']);
        $cut    = intval($_REQUEST['cut']);

        //Addons::hook('widget_upload_before_save', &$data);
        
        $option['attach_type'] = $data['attach_type'];
        $info = model('Attach')->upload($data, $option);
        //Addons::hook('widget_upload_after_save', &$info);

    	if($info['status']){
    		$data = $info['info'][0];
            if($thumb==1){
                $data['src'] = getImageUrl($data['save_path'].$data['save_name'],$width,$height,$cut);
            }else{
                $data['src'] = $data['save_path'].$data['save_name'];
            }
    		
    		$data['extension']  = strtolower($data['extension']);
    		$return = array('status'=>1,'data'=>$data);
    	}else{
    		$return = array('status'=>0,'data'=>$info['info']);
    	}
    	echo json_encode($return);exit();
    }
    
    /** 
     * 編輯器圖片上傳
     * @return array 上傳圖片的路徑及錯誤資訊
     */
    public function saveEditorImg(){
        $data['attach_type'] = 'editor_img';
        $data['upload_type'] = 'image'; //使用又拍雲時，必須指定類型為image
        $info = model('Attach')->upload($data);
        if($info['status']){
            $data           = $info['info'][0];
            $data['src']    = getImageUrl($data['save_path'].$data['save_name']);
            $return = array('error' => 0, 'url' => $data['src']);
        }else{
            $return = array('error'=>1,'message'=>$info['info']);
        }
        echo json_encode($return);exit();
    }
    
    /**
     * 編輯器檔案上傳
     * @return array 上傳檔案的資訊
     */
    public function saveEditorFile(){
        $data['attach_type'] = 'editor_file';
        $data['upload_type'] = 'file'; //使用又拍雲時，必須指定類型為file
        $info = model('Attach')->upload($data);
        if($info['status']){
            $data           = $info['info'][0];
            $data['src']    = getImageUrl($data['save_path'].$data['save_name'],100,100,true);
            $data['extension']  = strtolower($data['extension']);
            $return = array('status'=>1,'data'=>$data);
        }else{
            $return = array('status'=>0,'data'=>$info['info']);
        }
        echo json_encode($return);exit();
    }
    
    /**
     * 附件下載
     */
    public function down(){
    
   		$aid	=	intval($_GET['attach_id']);
		
		$attach	=	model('Attach')->getAttachById($aid);

		if(!$attach){
			die(L('PUBLIC_ATTACH_ISNULL'));
		}

        $filename = $attach['save_path'].$attach['save_name'];
        $realname = auto_charset ( $attach['name'], "UTF-8", 'GBK//IGNORE');

		//下載函數
		tsload(ADDON_PATH.'/library/Http.class.php');
        //從雲端下載
        $cloud = model('CloudAttach');
        if($cloud->isOpen()){
            $url = $cloud->getFileUrl($filename);
            redirect($url);
            //$content = $cloud->getFileContent($filename); //讀檔案下載
            //Http::download('', $realname, $content);
        //從本地下載
        }else{
    		if(file_exists(UPLOAD_PATH.'/'.$filename)) {
    			Http::download(UPLOAD_PATH.'/'.$filename, $realname);
    		}else{
    			echo L('PUBLIC_ATTACH_ISNULL');
    		}
        }
    }    
}
?>