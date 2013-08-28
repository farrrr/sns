<?php
//附件管理
class AttachAction extends Action{

    public function _initialize() {
        if(!$this->mid){
            echo "-1";  //沒有許可權，需要登入後上傳
        }
    }

    //相容舊路徑
    public function showdataimage(){
        $savepath   =   t ( $_REQUEST ['savepath'] );
        $savename   =   t ( $_REQUEST ['savename'] );
        //防止跨目錄
        $savepath   =   str_ireplace('..','',$savepath);
        $savename   =   str_ireplace('..','',$savename);

        list($first,$extension) = explode(".",$savename);
        $imagetype = array ('jpg', 'png', 'gif', 'bmp', 'jpeg' );
        if (! in_array ( strtolower($extension), $imagetype )) {
            return;
        } else {


            if( $_REQUEST['temp']==1 ){
                $source_image   =   UPLOAD_PATH . '/temp/'. $savename;
                header ( "Content-type: image/" . $extension);
                readfile( $source_image );
                exit;
            }

            $source_image   =   UPLOAD_PATH . '/' . $savepath . $savename;
            if(file_exists($source_image)){
                //加快取
                $offset = 60 * 60 * 24 * 7; //圖片過期7天
                header ( "cache-control: must-revalidate" );
                header ( "cache-control: max-age=".$offset );
                header( "Last-Modified: " . gmdate ( "D, d M Y H:i:s", time () ) . "GMT");
                header ( "Pragma: max-age=".$offset );
                header ( "Expires:" . gmdate ( "D, d M Y H:i:s", time () + $offset ) . " GMT" );
                $this->set_cache_limit($offset);
                header ( "Content-type: image/" . $extension);
                readfile( $source_image );
                //持久化
                $new_image_dir  =   './data/uploads/'. $savepath;
                if( !is_dir($new_image_dir) )
                    @mkdir($new_image_dir,0777,true);

                if(is_dir($new_image_dir))
                    @copy( $source_image, $new_image_dir.$savename );
            }
        }
    }

    function set_cache_limit($second=1)
    {
        $second=intval($second);
        if($second==0) {
            return;
        }

        $id = $_SERVER['HTTP_IF_NONE_MATCH'];
        $etag=time()."||".base64_encode( $_SERVER['REQUEST_URI'] );
        if( $id=='' )
        {//無tag，發送新tag
            header("Etag:$etag",true,200);
            return;
        }
        list( $time , $uri )=explode ( "||" , $id );
        if($time < (time()-$second))
        {//過期了，發送新tag
            header("Etag:$etag",true,200);
        }else
        {//未過期，發送舊tag
            header("Etag:$id",true,304);
            exit(-1);
        }
    }


    function image_file_download($file) {
        $size = image_get_info(file_create_path($file));
        if ($size) {
            $modified = filemtime(file_create_path($file));

            //apache only
            $request = getallheaders();

            if (isset($request['If-Modified-Since'])) {
                //remove information after the semicolon and form a timestamp
                $request_modified = explode(';', $request['If-Modified-Since']);
                $request_modified = strtotime($request_modified[0]);
            }

            // Compare the mtime on the request to the mtime of the image file
            if ($modified <= $request_modified) {
                header('HTTP/1.1 304 Not Modified');
                exit();
            }

            //enable caching on this url for proxy servers
            $headers = array('Content-Type: ' . $size['mime_type'],
                'Last-Modified: ' . gmdate('D, d M Y H:i:s', $modified) . ' GMT',
                'Cache-Control: public');

            return $headers;
        }
    }

    //下載輸出附件
    public function download(){
        $aid    =   intval($_REQUEST['id']);
        $uid    =   intval($_REQUEST['uid']);
        $attach =   model('Attach')->where("id={$aid} AND userId={$uid}")->find();
        //$attach   =   model('Xattach')->where("id='$aid'")->find();

        if(!$attach){
            $this->error(L('attach_noexist'));
        }
        //下載函數
        require_cache('./addons/library/Http.class.php');
        $file_path = UPLOAD_PATH . '/' .$attach['savepath'] . $attach['savename'];
        if(file_exists($file_path)) {
            $filename = iconv("utf-8",'gb2312',$attach['name']);
            Http::download($file_path, $filename);
        }else{
            $this->error(L('attach_noexist'));
        }
    }

    //ewebeditor控制項上傳
    public function ewebeditor(){

        //執行附件上傳操作
        $attach_type    =   'ewebeditor';

        $options['uid']         =   $this->mid;
        $options['allow_exts']  =   'jpg,jpeg,bmp,png,gif';
        $info   =   X('Xattach')->upload($attach_type,$options);

        if(is_array($info['info'])){
            $image_url  =   SITE_URL.'/data/uploads/'.$info['info'][0]['savepath'].$info['info'][0]['savename'];
        }

        //上傳成功
        if($info['status']==true){
            echo $image_url;
        }
    }

    //kissy編輯器上傳
    public function kissy(){

        //執行附件上傳操作
        $attach_type    =   'kissy';

        $options['uid']         =   $this->mid;
        $options['allow_exts']  =   'jpg,jpeg,bmp,png,gif';
        $info   =   X('Xattach')->upload($attach_type,$options);

        if(is_array($info['info'])){
            $image_url  =   SITE_URL.'/data/uploads/'.$info['info'][0]['savepath'].$info['info'][0]['savename'];
        }

        //上傳成功
        if($info['status']==true){
            echo '{"status": "0", "imgUrl": "' .$image_url. '"}';
        }else{
            echo '{"status": "1", "error": "'.$info['info'].'"}';
        }
    }

    //上傳附件
    public function ajaxUpload(){
        //執行附件上傳操作
        $d['type_name'] = 11;
        D('feedback_type')->add($d);
        $attach_type    =   t($_REQUEST['type']);

        $options['uid']         =   $this->mid;

        //加密傳輸這個欄位,防止客戶端亂設定.
        $options['allow_exts']  =   t(jiemi($_REQUEST['exts']));
        $options['allow_size']  =   t(jiemi($_REQUEST['size']));
        $jiamiData = jiemi(t($_REQUEST['token']));
        list($options['allow_exts'], $options['need_review'], $fid) = explode("||", $jiamiData);
        $options['limit']       =   intval(jiemi($_REQUEST['limit']));
        $options['now_pageCount'] = intval($_REQUEST['now_pageCount']);

        $data['upload_type'] = $attach_type;

        $info   =   model('Attach')->upload($data,$options);
        //上傳成功
        echo json_encode($info);
    }

    // 圖片擷取
    public function thumbImage() {

        // 過濾成本地圖片地址
        $src_arr    =   explode("?",$_POST['bigImage']);
        $src        =   $src_arr[0];
        $src        =   str_ireplace(SITE_URL,'.','http://'.$_SERVER['HTTP_HOST'].$src);

        // 獲取源圖的副檔名寬高
        list($sr_w, $sr_h, $sr_type, $sr_attr) = @getimagesize($src);
        if($sr_type){
            //獲取字尾名
            $ext = image_type_to_extension($sr_type,false);
        } else {
            echo "-1";
        }

        // 獲取相關資料
        $txt_left   =   (float) $_POST['txt_left'];
        $txt_top    =   (float) $_POST['txt_top'];
        $txt_width  =   (float) $_POST['txt_width'];
        $txt_height =   (float) $_POST['txt_height'];
        $zoom       =   (float) $_POST['txt_Zoom'];

        // 頭像大方快的寬高
        $targ_w     =   120;
        $targ_h     =   120;

        // 頭像小方塊的寬高
        $small_w    =   45;
        $small_h    =   45;

        // 生成頭像目錄
        $face_path  =   SITE_PATH.'/data/userface/'.$this->mid;
        $face_url   =   SITE_URL.'/data/userface/'.$this->mid;
        if(!file_exists($face_path)){
            mkdir($face_path,0777,true);
            chmod($face_path,0777);
        }
        // 生成頭像名稱
        $middle_name    =   $face_path.'/middle_face.jpg';      // 中圖
        $small_name     =   $face_path.'/small_face.jpg';       // 小圖

        // 生成原圖拷貝
        $func   =   ($ext != 'jpg')?'imagecreatefrom'.$ext:'imagecreatefromjpeg';
        $img_r  =   call_user_func($func,$src);

        //計算原圖截圖座標，以源圖左上角為座標原點
        $dx1    =   $txt_left;
        $dy1    =   $txt_top;
        $dx2    =   $dx1+$targ_w;
        $dy2    =   $dy1+$targ_h;

        $sx1    =   0;
        $sy1    =   0;
        $sx2    =   $txt_width;
        $sy2    =   $txt_height;

        //計算拷貝區域座標，以源圖左上角為座標原點
        $smx1   =   $dx1>$sx1 ? $dx1 : $sx1;
        $smy1   =   $dy1>$sy1 ? $dy1 : $sy1;
        $smx2   =   $dx2>$sx2 ? $sx2 : $dx2;
        $smy2   =   $dy2>$sy2 ? $sy2 : $dy2;

        $src_x  =   $smx1/$zoom;
        $src_y  =   $smy1/$zoom;

        $src_w  =   ($smx2-$smx1)/$zoom;
        $src_h  =   ($smy2-$smy1)/$zoom;

        //計算拷貝區域座標，以目標圖左上角為座標原點，座標原點平移到 $dx1,$dy1
        $dst_x  =   $smx1-$dx1;
        $dst_y  =   $smy1-$dy1;

        $dst_w  =   $smx2-$smx1;
        $dst_h  =   $smy2-$smy1;

        //dump($smx1.','.$smy1.','.$smx2.','.$smy2.','.$src_w.','.$src_h.'|'.$dmx1.','.$dmy1.','.$dmx2.','.$dmy2.','.$dst_w.','.$dst_h);

        // 開始切割大方塊頭像
        $dst_r  =   ImageCreateTrueColor( $targ_w, $targ_h );
        $back   =   ImageColorAllocate( $dst_r, 255, 255, 255 );
        ImageFilledRectangle( $dst_r, 0, 0, $targ_w, $targ_h, $back );
        ImageCopyResampled( $dst_r, $img_r, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h );

        ImagePNG($dst_r,$middle_name);  // 生成中圖
        chmod($middle_name,0777);

        // 開始切割大方塊頭像成小方塊頭像
        $sdst_r =   ImageCreateTrueColor( $small_w, $small_h );
        ImageCopyResampled( $sdst_r, $dst_r, 0, 0, 0, 0, $small_w, $small_h, $targ_w, $targ_h );

        ImagePNG($sdst_r,$small_name);  // 生成小圖
        chmod($small_name,0777);

        ImageDestroy($dst_r);
        ImageDestroy($sdst_r);
        ImageDestroy($img_r);

        $output =   array( 'big' => $face_url.'/middle_face.jpg', 'small' => $face_url.'/small_face.jpg' );

        echo json_encode($output);
    }

    //刪除附件
    public function deleteAttach($aid,$uid){
        $map['uid'] =   $uid;
        $map['id']  =   $aid;

        //執行附件刪除操作
        $result =   model('Xattach')->where($map)->limit(1)->delete();
        //上傳成功
        echo $result;
    }
}
?>
