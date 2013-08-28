<?php
// +----------------------------------------------------------------------
// | OpenSociax [ open your team ! ]
// +----------------------------------------------------------------------
// | Copyright (c) 2010 http://www.sociax.com.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: genixsoft.net <智士軟體>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 附件操作服務，參考XattachModel
 +------------------------------------------------------------------------------
 */

//載入上傳操作類
tsload(SITE_PATH."/addons/library/UploadFile.class.php");

class XattachModel {

    /**
     * 上傳附件
     *
     * @param string $attach_type   附件類型
     * @param array  $input_options 配置選項[不推薦修改, 默認使用後臺的配置]
     */
    public function upload($attach_type='attach',$input_options=array()) {
        $system_default = model('Xdata')->lget('attach');
        if ( empty($system_default['attach_path_rule']) || empty($system_default['attach_max_size']) || empty($system_default['attach_allow_extension']) ) {
            $data['attach_path_rule']        = 'Y/md/H/';
            $data['attach_max_size']         = '2'; // 默認2M
            $data['attach_allow_extension']  = 'jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf';
            model('Xdata')->lput('attach', $data);
            $system_default = $data;
        }

        //載入默認規則
        $default_options =  array();
        $default_options['custom_path'] =   date($system_default['attach_path_rule']);              //應用定義的上傳目錄規則：'Y/md/H/'
        $default_options['max_size']    =   floatval($system_default['attach_max_size'])*1000000;   //單位: 兆
        $default_options['allow_exts']  =   $system_default['attach_allow_extension'];              //'jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf'
        $default_options['allow_types'] =   '';
        $default_options['save_path']   =   UPLOAD_PATH.'/'.$default_options['custom_path'];
        $default_options['save_name']   =   '';
        $default_options['save_rule']   =   'uniqid';
        $default_options['save_to_db']  =   true;

        //定製化設這 覆蓋默認設定
        $options    =   array_merge($default_options,$input_options);

        //使用者ID
        if( intval($options['uid'])==0 )    $options['uid'] = $_SESSION['mid'];

        //初始化上傳參數
        $upload                 =   new UploadFile($options['max_size'],$options['allow_exts'],$options['allow_types']);
        //設定上傳路徑
        $upload->savePath       =   $options['save_path'];
        //啟用子目錄
        $upload->autoSub        =   false;
        //儲存的名字
        $upload->saveName       =   $options['save_name'];
        //默認檔名規則
        $upload->saveRule       =   $options['save_rule'];
        //是否縮圖
        $upload->thumb          =   false;

        //創建目錄
        mkdir($upload->savePath,0777,true);

        //執行上傳操作
        if(!$upload->upload()) {

            //上傳失敗，返回錯誤
            $return['status']   =   false;
            $return['info']     =   $upload->getErrorMsg();
            return  $return;

        }else{

            $upload_info    =   $upload->getUploadFileInfo();
            $xattach        =   M('Attach');
            //儲存資訊到附件表
            if($options['save_to_db']){
                foreach($upload_info as $u){
                    $map['attach_type'] =   $attach_type;
                    $map['userId']      =   $options['uid'];
                    $map['name']        =   $u['name'];
                    $map['type']        =   $u['type'];
                    $map['size']        =   $u['size'];
                    $map['extension']   =   strtolower($u['extension']);
                    $map['hash']        =   $u['hash'];
                    $map['savepath']    =   $options['custom_path'];
                    $map['savename']    =   $u['savename'];
                    $map['uploadTime']  =   time();
                    //$map['savedomain']=   C('ATTACH_SAVE_DOMAIN'); //如果做分散式存儲，需要寫方法來分配附件的伺服器domain
                    $aid        =   $xattach->add($map);
                    $map['id']  =   intval($aid);
                    $map['key'] =   $u['key'];
                    $infos[]    =   $map;
                    unset($map);
                }
            }else{
                /*foreach($upload_info as $k => $u){
                    $upload_info[$k]['savepath']    =   $options['custom_path'];
                }
                $infos  =   $upload_info;*/
                foreach($upload_info as $u){
                    $map['attach_type'] =   $attach_type;
                    $map['userId']      =   $options['uid'];
                    $map['name']        =   $u['name'];
                    $map['type']        =   $u['type'];
                    $map['size']        =   $u['size'];
                    $map['extension']   =   strtolower($u['extension']);
                    $map['hash']        =   $u['hash'];
                    $map['savepath']    =   $options['custom_path'];
                    $map['savename']    =   $u['savename'];
                    $map['uploadTime']  =   time();
                    //$map['savedomain']=   C('ATTACH_SAVE_DOMAIN'); //如果做分散式存儲，需要寫方法來分配附件的伺服器domain
                    $map['key'] =   $u['key'];
                    $infos[]    =   $map;
                    unset($map);
                }
            }
            //輸出資訊
            $return['status']   =   true;
            $return['info']     =   $infos;
            //上傳成功，返回資訊
            return  $return;
        }
    }

    //直接直接儲存檔案到附件表
    public function addFile($attach_type='attach',$attach_file) {

        //獲取系統配置
        $system_default = model('Xdata')->lget('attach');
        if ( empty($system_default['attach_path_rule']) || empty($system_default['attach_max_size']) || empty($system_default['attach_allow_extension']) ) {
            $data['attach_path_rule']        = 'Y/md/H/';
            $data['attach_max_size']         = '2'; // 默認2M
            $data['attach_allow_extension']  = 'jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf';
            model('Xdata')->lput('attach', $data);
            $system_default = $data;
        }

        //獲取檔案資訊
        if(!file_exists($attach_file)) {

            //上傳失敗，返回錯誤
            $return['status']   =   false;
            $return['info']     =   '原始檔案不存在';
            return  $return;

        }else{

            //讀取附件大小
            $file['size']   =   filesize($attach_file);
            $file['hash']   =   md5_file($attach_file);

            //讀取附件名稱、字尾名
            if(function_exists('pathinfo')){
                $fileinfo   =   pathinfo($attach_file);
                $file['name']       =   $fileinfo['basename'];
                $file['extension']  =   $fileinfo['extension'];
            }else{
                $file['name']   =   basename($attach_file);
                preg_match('|\.(\w+)$|', $file['name'], $file['extension']);
            }

            //讀取附件類型
            if(function_exists('mime_content_type')){
                $file['type']   =   mime_content_type($attach_file);
            }elseif(in_array(strtolower($file['extension']),array('jpeg','jpg','gif','png','bmp'))){
                $file['type']   =   'image/'.$file['extension'];
            }else{
                $file['type']   =   'text/'.$file['extension'];
            }
        }

        //載入默認規則
        $options =  array();
        $options['custom_path'] =   date($system_default['attach_path_rule']);
        $options['save_path']   =   UPLOAD_PATH.'/'.$options['custom_path'];
        $options['save_name']   =   uniqid().".".$file['extension'];
        $options['save_to_db']  =   true;

        //使用者ID
        if( intval($options['uid'])==0 )    $options['uid'] = intval($_SESSION['mid']);

        //創建目錄
        mkdir($options['save_path'],0777,true);

        //執行上傳操作
        if(!copy($attach_file,$options['save_path'].'/'.$options['save_name'])) {

            //上傳失敗，返回錯誤
            $return['status']   =   false;
            $return['info']     =   '檔案轉移失敗';
            return  $return;

        }else{

            //儲存資訊到附件表
            $map['attach_type'] =   $attach_type;
            $map['userId']      =   $options['uid'];
            $map['name']        =   $file['name'];
            $map['type']        =   $file['type'];
            $map['size']        =   $file['size'];
            $map['extension']   =   strtolower($file['extension']);
            $map['hash']        =   $file['hash'];
            $map['savepath']    =   $options['custom_path'];
            $map['savename']    =   $options['save_name'];
            $map['uploadTime']  =   time();
            //$map['savedomain']=   C('ATTACH_SAVE_DOMAIN'); //如果做分散式存儲，需要寫方法來分配附件的伺服器domain
            $aid        =   M('Attach')->add($map);
            $map['id']  =   intval($aid);
            $map['key'] =   0;

            //上傳成功，輸出資訊
            $return['status']   =   true;
            $return['info']     =   $map;
            return  $return;

        }
    }

    /**
     * 下載附件
     *
     * @param int $aid 附件ID 為空時使用$_REQUEST['id']作為附件ID
     */
    public function download($aid) {
        if(intval($aid) == 0){
            $aid    =   intval($_REQUEST['id']);
        }
        $attach =   model('Attach')->field('savepath,savename,name')->where("id='$aid'")->find();
        if(!$attach){
            $this->error('附件不存在或已被刪除！');
        }
        //下載函數
        tsload('./addons/library/Http.class.php');
        $file_path = UPLOAD_PATH . '/' .$attach['savepath'] . $attach['savename'];
        if(file_exists($file_path)) {
            $filename = strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') ? iconv('utf-8', 'gbk', $attach['name']) : preg_replace('/\s/', '_', $attach['name']);
            Http::download($file_path, $filename);
        }else{
            $this->error('附件不存在或已被刪除！');
        }
        }

        /* 後臺管理相關方法 */

        //返回失敗資訊
        public function error($info,$return=false){
            $output['status']   =   false;
            $output['info']     =   $info;
            if($return){
                return $output;
        }else{
            echo json_encode($output);
        }
        }

        //返回成功資訊
        public function success($info,$return=false){
            $output['status']   =   true;
            $output['info']     =   $info;
            if($return){
                return $output;
        }else{
            echo json_encode($output);
        }
        }

        //返回附件資料
        public function getAttach($attachId,$field="*") {
            if( !$attachId || !isset($attachId) ) return false;

            if(!is_array($attachId)){
                $attachId   =   explode(',',$attachId);
        }
        foreach($attachId as $v){
            $attachIds[]    =   intval($v);
        }

        $map['attach_id']   =   array('in', array_map( 'intval' , $attachIds  ) );
        $data = M('Attach')->where($map)->field($field)->findAll();

        return $data;
        }

        //運行服務
        public function run(){
        }

        //啟動服務，未編碼
        public function _start(){
            return true;
        }

        //停止服務，未編碼
        public function _stop(){
            return true;
        }

        //解除安裝服務，未編碼
        public function _install(){
            return true;
        }

        //解除安裝服務，未編碼
        public function _uninstall(){
            return true;
        }
        }
?>
