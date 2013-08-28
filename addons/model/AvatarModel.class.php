<?php
/**
 * 頭像模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AvatarModel {

    protected $_uid;            // 使用者UID欄位

    /**
     * 初始化模型，載入相應的檔案
     * @param integer $uid 使用者UID
     * @return object 頭像模型物件
     */
    public function __construct($uid) {
        if(!$uid)
            $uid = intval($_SESSION['mid']);
        $this->_uid = intval($uid);
        return $this;
    }

    /**
     * 初始化模型，載入相應的檔案
     * @param integer $uid 使用者UID
     * @return object 頭像模型物件
     */
    public function init($uid) {
        $this->_uid = intval($uid);
        return $this;
    }

    /**
     * 判斷使用者是否上傳頭像
     * @return array
     */
    public function hasAvatar() {
        $original_file_name = '/avatar'.$this->convertUidToPath($this->_uid).'/original.jpg';

        //頭像雲端儲存
        $cloud = model('CloudImage');
        if($cloud->isOpen()){
            $original_file_info = $cloud->getFileInfo($original_file_name);
            if($original_file_info){
                $filemtime = @intval($original_file_info['date']);
                $avatar = getImageUrl($original_file_name).'!small.avatar.jpg?v'.$filemtime;
            }

            //頭像本地存儲
        }elseif(file_exists(UPLOAD_PATH.$original_file_name)){
            $filemtime = @filemtime(UPLOAD_PATH.$original_file_name);
            $avatar = getImageUrl($original_file_name,50,50).'?v'.$filemtime;
        }
        return ($avatar)?true:false;
    }

    /**
     * 獲取當前登入使用者頭像
     * @return array 使用者的頭像連結
     */
    public function getUserAvatar() {
        $empty_url = THEME_URL.'/_static/image/noavatar';
        $avatar_url = array(
            'avatar_original'   => $empty_url.'/big.jpg',
            'avatar_big'        => $empty_url.'/big.jpg',
            'avatar_middle'     => $empty_url.'/middle.jpg',
            'avatar_small'      => $empty_url.'/small.jpg',
            'avatar_tiny'       => $empty_url.'/tiny.jpg'
        );

        $original_file_name = '/avatar'.$this->convertUidToPath($this->_uid).'/original.jpg';

        //頭像雲端儲存
        $cloud = model('CloudImage');
        if($cloud->isOpen()){
            $original_file_info = $cloud->getFileInfo($original_file_name);
            if($original_file_info){
                $filemtime = @intval($original_file_info['date']);
                $avatar_url['avatar_original'] = getImageUrl($original_file_name);
                $avatar_url['avatar_big'] = getImageUrl($original_file_name).'!big.avatar.jpg?v'.$filemtime;
                $avatar_url['avatar_middle'] = getImageUrl($original_file_name).'!middle.avatar.jpg?v'.$filemtime;
                $avatar_url['avatar_small'] = getImageUrl($original_file_name).'!small.avatar.jpg?v'.$filemtime;
                $avatar_url['avatar_tiny'] = getImageUrl($original_file_name).'!tiny.avatar.jpg?v'.$filemtime;
            }

            //頭像本地存儲
        }elseif(file_exists(UPLOAD_PATH.$original_file_name)){
            $filemtime = @filemtime(UPLOAD_PATH.$original_file_name);
            $avatar_url['avatar_original'] = getImageUrl($original_file_name);
            $avatar_url['avatar_big'] = getImageUrl($original_file_name,200,200).'?v'.$filemtime;
            $avatar_url['avatar_middle'] = getImageUrl($original_file_name,100,100).'?v'.$filemtime;
            $avatar_url['avatar_small'] = getImageUrl($original_file_name,50,50).'?v'.$filemtime;
            $avatar_url['avatar_tiny'] = getImageUrl($original_file_name,30,30).'?v'.$filemtime;
        }

        return $avatar_url;
    }

    /**
     * 儲存Flash提交的資料 - flash上傳
     * @param array $data 使用者頭像的相關資訊
     * @param array $oldUserInfo 貌似無用欄位，與此flash元件有關
     * @return boolean 是否儲存成功
     */
    public function saveUploadAvatar($data, $oldUserInfo) {
        $original_file_name = '/avatar'.$this->convertUidToPath($this->_uid).'/original.jpg';
        // Log::write(var_export($data,true));
        //如果是又拍上傳
        $cloud = model('CloudImage');
        if($cloud->isOpen()){
            @$cloud->deleteFile($original_file_name);
            //重新上傳新頭像原圖
            $imageAsString = $data['big'];
            $res = $cloud->writeFile($original_file_name,$imageAsString,true);
        }else{
            $res = file_put_contents(UPLOAD_PATH.$original_file_name, $data['big']);
            getThumbImage($original_file_name,200,200,true,true);
            getThumbImage($original_file_name,100,100,true,true);
            getThumbImage($original_file_name,50,50,true,true);
            getThumbImage($original_file_name,30,30,true,true);
        }

        if(!$res){
            return false;
        }else{
            // 清理使用者快取
            model('User')->cleanCache($this->_uid);
            return true;
        }
    }

    /**
     * 上傳頭像
     * @return array 上傳頭像操作資訊
     */
    public function upload($fromApi=false) {
        $data['attach_type'] = 'avatar';
        $data['upload_type'] = 'image';
        $info = model('Attach')->upload($data);
        //Log::write(var_export($info,true));
        if($info['status']){
            $data = $info['info'][0];
            $image_url = getImageUrl($data['save_path'].$data['save_name']);
            $image_info = getimagesize($image_url);
            //如果不支援獲取遠端圖片資訊，使用如下方法
            if(!$image_info){
                $cloud = model('CloudImage');
                if($cloud->isOpen()){
                    $cinfo = $cloud->getFileInfo($data['save_path'].$data['save_name']);
                    if($cinfo){
                        $cinfo = json_decode($cinfo);
                        $image_info[0] = $cinfo['width'];
                        $image_info[1] = $cinfo['height'];
                    }
                }else{
                    $image_info = getimagesize(UPLOAD_PATH.'/'.$data['save_path'].$data['save_name']);
                }
            }
            if($image_info){
                unset($return);
                $return['data']['picwidth'] = $image_info[0];
                $return['data']['picheight'] = $image_info[1];
                $return['data']['picurl']    = $data['save_path'].$data['save_name'];
                $return['data']['fullpicurl'] = getImageUrl($data['save_path'].$data['save_name']);
                $return['status'] = '1';
                // if($image_info[0] < 300){
                //  die(json_encode(array('status'=>0,'info'=>'請選擇一個較大的照片作為頭像，寬度不小於300px')));
                // }else{
                if($fromApi){
                    return $return;
                }else{
                    die(json_encode($return));
                }
                //}
            }else{
                die(json_encode(array('status'=>0,'info'=>'不是有效的圖片格式，請重新選擇照片上傳')));
            }
        }else{
            die(json_encode(array('status'=>0,'info'=>$info['info'])));
        }
    }

    /**
     * 儲存使用者頭像圖片 - 本地上傳
     * @return array 頭像圖片資訊
     */
    public function dosave($facedata=false) {
        //Log::write(var_export($facedata,true));
        if(!$facedata){
            $facedata = $_POST;
        }
        //header("Content-type: image/jpeg");
        //Log::write(var_export($facedata,true));
        $picWidth = intval($facedata['picwidth']); //原圖的寬度
        $scale = $picWidth/300; //縮放比例
        $x1 = intval($facedata['x1'])*$scale;       // 選擇區域左上角x軸座標
        $y1 = intval($facedata['y1'])*$scale;       // 選擇區域左上角y軸座標
        $x2 = intval($facedata['x2'])*$scale;       // 選擇區域右下角x軸座標
        $y2 = intval($facedata['y2'])*$scale;       // 選擇區域右下角x軸座標
        $w  = intval($facedata['w'])*$scale;        // 選擇區的寬度
        $h  = intval($facedata['h'])*$scale;        // 選擇區的高度

        $src = getImageUrl($facedata['picurl']);    // 圖片的路徑

        //原圖存儲地址
        $original_file_name = '/avatar'.$this->convertUidToPath($this->_uid).'/original.jpg';

        $filemtime = microtime(true);

        //如果是又拍上傳
        $cloud = model('CloudImage');
        if($cloud->isOpen()){
            //切割原圖
            require_once SITE_PATH.'/addons/library/phpthumb/ThumbLib.inc.php';
            $thumb = PhpThumbFactory::create($src);
            $res = $thumb->crop($x1, $y1, $w, $h);

            //獲取獲取縮圖後的資料
            if(!$res){
                die(json_encode(array('status'=>0,'info'=>'頭像切割失敗')));
            }
            @$cloud->deleteFile($original_file_name);
            //重新上傳新頭像原圖
            $imageAsString = $thumb->getImageAsString();
            $res = $cloud->writeFile($original_file_name,$imageAsString,true);
            if($res){
                unset($return);
                $return['data']['big']      = getImageUrl($original_file_name).'!big.avatar.jpg?v'.$filemtime;
                $return['data']['middle']   = getImageUrl($original_file_name).'!middle.avatar.jpg?v'.$filemtime;
                $return['data']['small']    = getImageUrl($original_file_name).'!small.avatar.jpg?v'.$filemtime;
                $return['data']['tiny']     = getImageUrl($original_file_name).'!tiny.avatar.jpg?v'.$filemtime;
                $return['status'] = 1;
                // 清理使用者快取
                model('User')->cleanCache($this->_uid);
            }else{
                $return['status'] = '0';
                $return['info']   = '切割頭像失敗';
            }
        }else{

            //切割原圖
            require_once SITE_PATH.'/addons/library/phpthumb/ThumbLib.inc.php';
            $thumb = PhpThumbFactory::create(UPLOAD_PATH.'/'.$facedata['picurl']);
            $res = $thumb->crop($x1, $y1, $w, $h);

            //獲取獲取縮圖後的資料
            if(!$res){
                die(json_encode(array('status'=>0,'info'=>'頭像切割失敗')));
            }

            if(!file_exists(UPLOAD_PATH.$original_file_name)) {
                $this->_createFolder(UPLOAD_PATH.'/avatar'.$this->convertUidToPath($this->_uid));
            }
            $thumb->save(UPLOAD_PATH.$original_file_name);
            unset($return);
            $return['data']['big']      = getImageUrl($original_file_name,200,200,true,true).'?v'.$filemtime;
            $return['data']['middle']   = getImageUrl($original_file_name,100,100,true,true).'?v'.$filemtime;
            $return['data']['small']    = getImageUrl($original_file_name,50,50,true,true).'?v'.$filemtime;
            $return['data']['tiny']     = getImageUrl($original_file_name,30,30,true,true).'?v'.$filemtime;
            $return['status'] = 1;
        }
        die(json_encode($return));
    }

    /**
     * 儲存使用者頭像圖片 - 本地上傳
     * @return array 頭像圖片資訊
     */
    public function saveRemoteAvatar($src,$uid) {

        //原圖存儲地址
        $original_file_name = '/avatar'.$this->convertUidToPath($uid).'/original.jpg';

        //儲存圖片到原圖
        $opts = array(
            'http'=>array(
                'method' => "GET",
                'timeout' => 3, //超時30秒
                'user_agent'=>"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)"
            )
        );
        $context = stream_context_create($opts);
        $imageData = file_get_contents($src, false, $context);

        $filemtime = microtime(true);

        //如果是又拍上傳
        $cloud = model('CloudImage');
        if($cloud->isOpen()){
            @$cloud->deleteFile($original_file_name);
            //重新上傳新頭像原圖
            $imageAsString = $imageData;
            $res = $cloud->writeFile($original_file_name,$imageAsString,true);
            if($res){
                unset($return);
                $return['data']['big']      = getImageUrl($original_file_name).'!big.avatar.jpg?v'.$filemtime;
                $return['data']['middle']   = getImageUrl($original_file_name).'!middle.avatar.jpg?v'.$filemtime;
                $return['data']['small']    = getImageUrl($original_file_name).'!small.avatar.jpg?v'.$filemtime;
                $return['data']['tiny']     = getImageUrl($original_file_name).'!tiny.avatar.jpg?v'.$filemtime;
                $return['status'] = 1;
                // 清理使用者快取
                model('User')->cleanCache($this->_uid);
            }else{
                $return['status'] = '0';
                $return['info']   = '上傳頭像失敗';
                return $return;
            }
            }else{

                if(!file_exists(UPLOAD_PATH.$original_file_name)) {
                    $this->_createFolder(UPLOAD_PATH.'/avatar'.$this->convertUidToPath($uid));
            }

            if(!file_put_contents(UPLOAD_PATH.$original_file_name, $imageData)){
                $return['status'] = '0';
                $return['info']   = '切割儲存失敗';
                return $return;
            }

            $return['data']['big']      = getImageUrl($original_file_name,200,200,true,true).'?v'.$filemtime;
            $return['data']['middle']   = getImageUrl($original_file_name,100,100,true,true).'?v'.$filemtime;
            $return['data']['small']    = getImageUrl($original_file_name,50,50,true,true).'?v'.$filemtime;
            $return['data']['tiny']     = getImageUrl($original_file_name,30,30,true,true).'?v'.$filemtime;
            $return['status'] = 1;
            }
            return $return;
            }

            /**
             * 將使用者的UID轉換為三級路徑
             * @param integer $uid 使用者UID
             * @return string 使用者路徑
             */
            public function convertUidToPath($uid) {
                // 靜態快取
                $sc = static_cache('avatar_uidpath_'.$uid);
                if(!empty($sc)) {
                    return $sc;
            }
            $md5 = md5($uid);
            $sc = '/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.substr($md5, 4, 2);
            static_cache('avatar_uidpath_'.$uid, $sc);
            return $sc;
            }

            /**
             * 創建多級檔案目錄
             * @param string $path 路徑名稱
             * @return void
             */
            private function _createFolder($path)
            {
                if(!is_dir($path)) {
                    $this->_createFolder(dirname($path));
                    mkdir($path, 0777, true);
            }
            }
            }
