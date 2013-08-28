<?php
class CloudImageModel {

    // 上傳檔案的最大值
    public $maxSize = 2048000;

    // 允許上傳的檔案字尾,留空不作字尾檢查
    public $allowExts = '';

    // 允許上傳的檔案類型,留空不做檢查
    public $allowTypes = '';

    // 使用對上傳圖片進行縮圖處理
    public $thumb   =  false;

    // 縮圖最大寬度
    public $thumbMaxWidth = 1024;

    // 縮圖最大高度
    public $thumbMaxHeight = 2048;

    // 上傳檔案儲存路徑
    public $customPath = '';
    public $savePath = '';
    public $saveName = '';

    // 上傳檔案Hash規則函數名,可以是 md5_file sha1_file 等
    public $hashType = 'md5_file';

    public $autoCheck = true;

    // 錯誤資訊
    private $error = '';

    // 上傳成功的檔案資訊
    private $uploadFileInfo ;

    // 又拍雲配置
    private $config;

    //建構函式
    public function __construct() {
        $this->config = $this->getConfig();
    }

    //獲取又拍雲配置
    public function getConfig(){
        $config = model('Xdata')->get('admin_Config:cloudimage');
        return $config;
    }

    //是否開啟Upyun
    public function isOpen(){
        return intval($this->config['cloud_image_open']);
    }

    //獲取上傳地址
    public function getUploadUrl(){
        return $this->config['cloud_image_api_url'].'/'.$this->config['cloud_image_bucket'].'/';
    }

    //獲取上傳地址
    public function getImageUrl($filename,$width,$height,$cut){
        $filename = str_replace('//','/','/'.trim($filename));
        $cloud_image_prefix_urls = trim($this->config['cloud_image_prefix_urls']);
        $cloud_image_prefix_urls = explode(',', $cloud_image_prefix_urls);
        $cloud_image_prefix_urls = array_filter($cloud_image_prefix_urls);
        $cloud_image_prefix_url  = $cloud_image_prefix_urls[array_rand($cloud_image_prefix_urls)];
        $cliud_image_prefix_url  = trim($cloud_image_prefix_url);
        if($width && $height){
            return $cloud_image_prefix_url.$filename.'!'.$width.'x'.$height.(($cut)?'.cut':'').'.jpg';
        }else{
            return $cloud_image_prefix_url.$filename;
        }
    }

    //獲取上傳圖片資訊
    public function getImageInfo($filename){
        $image_url = $this->getImageUrl($filename);
        $exif = file_get_contents($image_url.'!exif');
        if(!$exif){
            return false;
        }else{
            return json_decode($exif);
        }
    }

    public function getFileInfo($filename){
        //上傳到雲伺服器
        $config = $this->getConfig();
        tsload(ADDON_PATH.'/library/upyun.class.php');
        $cloud = new UpYun($config['cloud_image_bucket'],$config['cloud_image_admin'],$config['cloud_image_password']);
        $cloud->setTimeout(60);
        $res = $cloud->getFileInfo($filename);
        return $res;
    }

    //獲取附件URL字首
    public function getUrlPrefix(){
        return $this->config['cloud_image_url'];
    }

    //獲取表單API相關資訊
    public function getPolicydoc(){
        $policydoc = array(
            "bucket"     =>  $this->config['cloud_image_bucket'],
            "expiration" =>  time()+600, //1分鐘超時
            "save-key"   =>  "/{year}/{mon}{day}/{random}.{suffix}",
            "allow-file-type" => "jpg,jpeg,gif,png",
            "content-length-range" => '0,5120000'    //最大5M
        );
        return $policydoc;
    }

    //獲取policy
    public function getPolicy(){
        $policy = base64_encode(json_encode($this->getPolicydoc()));
        return $policy;
    }

    //獲取signature
    public function getSignature(){
        $signature = md5($this->getPolicy().'&'.$this->config['cloud_image_form_api_key']);
        return $signature;
    }

    /**
     * 寫入檔案
     * @access public
     * @param string $filename  檔案相對路徑
     * @param string $filecontent  檔案資料
     * @return bool
     */
    public function writeFile($filename,$filecontent){
        //上傳到雲伺服器
        $config = $this->getConfig();
        tsload(ADDON_PATH.'/library/upyun.class.php');
        $cloud = new UpYun($config['cloud_image_bucket'],$config['cloud_image_admin'],$config['cloud_image_password']);
        $cloud->setTimeout(60);
        $res = $cloud->writeFile($filename,$filecontent,true);
        if(!$res){
            $this->error = '上傳到雲伺服器失敗！';
            return false;
        }else{
            return true;
        }
    }

    /**
     * 刪除檔案
     * @access public
     * @param string $filename  檔案相對路徑
     * @return bool
     */
    public function deleteFile($filename){
        //上傳到雲伺服器
        $config = $this->getConfig();
        tsload(ADDON_PATH.'/library/upyun.class.php');
        $cloud = new UpYun($config['cloud_image_bucket'],$config['cloud_image_admin'],$config['cloud_image_password']);
        $cloud->setTimeout(60);
        $res = $cloud->deleteFile($filename);
        if(!$res){
            $this->error = '刪除雲伺服器檔案失敗！';
            return false;
        }else{
            return true;
        }
    }

    /**
     * 上傳檔案
     * @access public
     * @param string $savePath  上傳檔案儲存路徑
     * @return string
     * @throws ThinkExecption
     */
    public function upload($savePath ='') {

        if(!$this->isOpen()){
            $this->error  = '沒有開啟雲圖片功能';
            return false;
        }

        $fileInfo = array();
        $isUpload   = false;

        // 獲取上傳的檔案資訊,對$_FILES陣列資訊處理
        $files   =   $this->dealFiles($_FILES);
        foreach($files as $key => $file) {
            //過濾無效的上傳
            if(!empty($file['name'])) {

                $file['key']        =  $key;
                $file['extension']  = $this->getExt($file['name']);

                if($this->savePath){
                    $file['savepath']  = $this->savePath;
                }else{
                    $file['savepath']  = $this->customPath;
                }

                if($this->saveName){
                    $file['savename'] = $this->saveName;
                }else{
                    $file['savename'] = uniqid().".".$file['extension'];
                }

                //移動裝置上傳的無字尾的圖片，默認為jpg
                if($GLOBALS['fromMobile'] == true && empty($file['extension'])){
                    $file['extension']  = 'jpg';
                    $file['savename']   = trim($file['savename'],'.').'.jpg';
                }elseif($this->autoCheck) {
                    if(!$this->check($file))
                        return false;
                }

                //計算hash
                if(function_exists($this->hashType)) {
                    $fun =  $this->hashType;
                    $file['hash']   =  $fun($file['tmp_name']);
                }

                //上傳到雲伺服器
                $config = $this->getConfig();
                tsload(ADDON_PATH.'/library/upyun.class.php');
                $cloud = new UpYun($config['cloud_image_bucket'],$config['cloud_image_admin'],$config['cloud_image_password']);
                $cloud->setTimeout(60);

                $file_content = file_get_contents($file['tmp_name']);
                $res = $cloud->writeFile('/'.$file['savepath'].$file['savename'],$file_content,true);
                if(!$res){
                    $this->error = '上傳到雲伺服器失敗！';
                    return false;
                }

                //上傳成功後儲存檔案資訊，供其它地方呼叫
                unset($file['tmp_name'],$file['error'],$file_content);

                $fileInfo[] = $file;
                $isUpload   = true;
            }
        }

        if($isUpload) {
            $this->uploadFileInfo = $fileInfo;
            return true;
        }else {
            $this->error  = '上傳出錯！檔案不符合上傳要求。';
            return false;
        }
    }

    /**
     * 轉換上傳檔案陣列變數為正確的方式
     * @access private
     * @param array $files  上傳的檔案變數
     * @return array
     */
    private function dealFiles($files) {
        $fileArray = array();
        foreach ($files as $file){
            if(is_array($file['name'])) {
                $keys = array_keys($file);
                $count    =   count($file['name']);
                for ($i=0; $i<$count; $i++) {
                    foreach ($keys as $key) {
                        $fileArray[$i][$key] = $file[$key][$i];
                    }
                }
            }else{
                $fileArray   =   $files;
            }
            break;
        }
        return $fileArray;
    }

    /**
     * 獲取錯誤程式碼資訊
     * @access public
     * @param string $errorNo  錯誤號碼
     * @return void
     * @throws ThinkExecption
     */
    protected function error($errorNo) {
        switch($errorNo) {
        case 1:
            $size = ini_get("upload_max_filesize");
            if( strpos($size,'M')!==false || strpos($size,'m')!==false ) {
                $size = intval($size)*1024;
                $size = byte_format( $size );
            }
            //edit by  yangjs
            if(isset($this->maxSize) && !empty($this->maxSize)){
                $size = byte_format($this->maxSize);
            }
            $this->error = '上傳檔案大小不符，檔案不能超過 '.$size;
            break;
        case 2:
            $size = ini_get("upload_max_filesize");
            if( strpos($size,'M')!==false || strpos($size,'m')!==false ) {
                $size = intval($size)*1024;
                $size = byte_format( $size );
            }
            //edit by  yangjs
            if(isset($this->maxSize) && !empty($this->maxSize)){
                $size = byte_format($this->maxSize);
            }
            $this->error = '上傳檔案大小不符，檔案不能超過 '.$size;
            break;
        case 3:
            $this->error = '檔案只有部分被上傳';
            break;
        case 4:
            $this->error = '沒有檔案被上傳';
            break;
        case 6:
            $this->error = '找不到臨時資料夾';
            break;
        case 7:
            $this->error = '檔案寫入失敗';
            break;
        default:
            $this->error = '未知上傳錯誤！';
        }
        return ;
    }

    /**
     * 檢查上傳的檔案
     * @access private
     * @param array $file 檔案資訊
     * @return boolean
     */
    private function check($file) {
        if($file['error']!== 0) {
            //檔案上傳失敗
            //捕獲錯誤程式碼
            $this->error($file['error']);
            return false;
        }
        //檔案上傳成功，進行自定義規則檢查
        //檢查檔案大小
        if(!$this->checkSize($file['size'])) {
            $this->error = '上傳檔案大小不符,檔案不能超過 '.byte_format($this->maxSize);
            return false;
        }

        //檢查檔案Mime類型
        if(!$this->checkType($file['type'])) {
            $this->error = '上傳檔案MIME類型不允許！';
            return false;
        }
        //檢查檔案類型
        if(!$this->checkExt($file['extension'])) {
            $this->error ='上傳檔案類型不允許';
            return false;
        }

        //檢查是否合法上傳
        if(!$this->checkUpload($file['tmp_name'])) {
            $this->error = '非法上傳檔案！';
            return false;
        }

        return true;
    }

    /**
     * 檢查上傳的檔案類型是否合法
     * @access private
     * @param string $type 資料
     * @return boolean
     */
    private function checkType($type) {
        if(!empty($this->allowTypes)) {
            if(!is_array($this->allowTypes)){
                $this->allowTypes = explode(',',$this->allowTypes);
            }
            return in_array(strtolower($type),$this->allowTypes);
        }
        return true;
    }


    /**
     * 檢查上傳的檔案字尾是否合法
     * @access private
     * @param string $ext 字尾名
     * @return boolean
     */
    private function checkExt($ext) {
        if(!empty($this->allowExts)) {
            if(!is_array($this->allowExts)){
                $this->allowExts = explode(',',$this->allowExts);
            }
            return in_array(strtolower($ext),$this->allowExts,true);
        }
        return true;
    }

    /**
     * 檢查檔案大小是否合法
     * @access private
     * @param integer $size 資料
     * @return boolean
     */
    private function checkSize($size) {
        return !($size > $this->maxSize) || (-1 == $this->maxSize);
    }

    /**
     * 檢查檔案是否非法提交
     * @access private
     * @param string $filename 檔名
     * @return boolean
     */
    private function checkUpload($filename) {
        return is_uploaded_file($filename);
    }

    /**
     * 取得上傳檔案的字尾
     * @access private
     * @param string $filename 檔名
     * @return boolean
     */
    private function getExt($filename) {
        $pathinfo = pathinfo($filename);
        return $pathinfo['extension'];
    }

    /**
     * 取得上傳檔案的資訊
     * @access public
     * @return array
     */
    public function getUploadFileInfo() {
        return $this->uploadFileInfo;
    }

    /**
     * 取得最後一次錯誤資訊
     * @access public
     * @return string
     */
    public function getErrorMsg() {
        return $this->error;
    }
    }
