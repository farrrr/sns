<?php
// +----------------------------------------------------------------------
// | ThinkPHP
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 檔案上傳類
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class UploadFile
{//類定義開始

    // 上傳檔案的最大值
    public $maxSize = -1;

    // 是否支援多檔案上傳
    public $supportMulti = true;

    // 允許上傳的檔案字尾
    //  留空不作字尾檢查
    public $allowExts = array();

    // 允許上傳的檔案類型
    // 留空不做檢查
    public $allowTypes = array();

    // 使用對上傳圖片進行縮圖處理
    public $thumb   =  false;
    // 縮圖最大寬度
    public $thumbMaxWidth;
    // 縮圖最大高度
    public $thumbMaxHeight;
    // 縮圖字首
    public $thumbPrefix   =  'thumb_';
    public $thumbSuffix  =  '';
    // 縮圖儲存路徑
    public $thumbPath = '';
    // 縮圖檔名
    public $thumbFile       =   '';
    // 是否移除原圖
    public $thumbRemoveOrigin = false;
    // 壓縮圖片檔案上傳
    public $zipImages = false;
    // 啟用子目錄儲存檔案
    public $autoSub   =  false;
    // 子目錄創建方式 可以使用hash date
    public $subType   = 'hash';
    public $dateFormat = 'Ymd';
    public $hashLevel =  1; // hash的目錄層次
    // 上傳檔案儲存路徑
    public $savePath = '';
    public $saveName = '';
    public $autoCheck = true; // 是否自動檢查附件
    // 存在同名是否覆蓋
    public $uploadReplace = false;

    // 上傳檔案命名規則
    // 例如可以是 time uniqid com_create_guid 等
    // 必須是一個無需任何參數的函數名 可以使用自定義函數
    public $saveRule = '';

    // 上傳檔案Hash規則函數名
    // 例如可以是 md5_file sha1_file 等
    public $hashType = 'md5_file';

    // 錯誤資訊
    private $error = '';

    // 上傳成功的檔案資訊
    private $uploadFileInfo ;

    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct($maxSize='',$allowExts='',$allowTypes='',$savePath=UPLOAD_PATH,$saveRule='')
    {
        if(!empty($maxSize) && is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        }
        if(!empty($allowExts)) {
            if(is_array($allowExts)) {
                $this->allowExts = array_map('strtolower',$allowExts);
            }else {
                $this->allowExts = explode(',',strtolower($allowExts));
            }
        }
        if(!empty($allowTypes)) {
            if(is_array($allowTypes)) {
                $this->allowTypes = array_map('strtolower',$allowTypes);
            }else {
                $this->allowTypes = explode(',',strtolower($allowTypes));
            }
        }
        if(!empty($saveRule)) {
            $this->saveRule = $saveRule;
        }else{
            $this->saveRule =   C('UPLOAD_FILE_RULE');
        }
        $this->savePath = $savePath;
    }

    /**
     +----------------------------------------------------------
     * 上傳一個檔案
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param mixed $name 資料
     * @param string $value  資料表名
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    private function save($file)
    {
        $filename = $file['savepath'].$file['savename'];
        if(!$this->uploadReplace && is_file($filename)) {
            // 不覆蓋同名檔案
            $this->error    =   '檔案已經存在！'.$filename;
            return false;
        }
        $saveFileName = auto_charset($filename,'utf-8','gbk');
        if(!move_uploaded_file($file['tmp_name'], $saveFileName)) {
            $this->error = '檔案上傳儲存錯誤！';
            return false;
        }
        if($this->thumb) { //是否主動生成縮圖 不建議開啟
            getThumbImage($saveFileName,100,100);
            getThumbImage($saveFileName,300,300);
        }
        if($this->zipImags) {
            // TODO 對圖片壓縮包線上解壓

        }

        //由於很多時候，後臺不需要水印，所以需要水印的在應用中自己實現，參考如下程式碼
        //require_cache(SITE_PATH."/addons/library/WaterMark/WaterMark.class.php");
        //WaterMark::iswater($filename);

        return true;
    }

    /**
     +----------------------------------------------------------
     * 上傳檔案
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $savePath  上傳檔案儲存路徑
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    public function upload($savePath ='')
    {
        mkdir($savePath,0777,true);
        //如果不指定儲存檔名，則由系統默認
        if(empty($savePath)) {
            $savePath = $this->savePath;
        }
        // 檢查上傳目錄
        if(!is_dir($savePath)) {
            // 檢查目錄是否編碼後的
            if(is_dir(base64_decode($savePath))) {
                $savePath   =   base64_decode($savePath);
            }else{
                // 嘗試創建目錄
                if(!mkdir($savePath,0777,true)){
                    $this->error  =  '上傳目錄'.$savePath.'不存在';
                    return false;
                }
            }
        }else {
            if(!is_writeable($savePath)) {
                $this->error  =  '上傳目錄'.$savePath.'不可寫';
                return false;
            }
        }
        $fileInfo = array();
        $isUpload   = false;

        // 獲取上傳的檔案資訊
        // 對$_FILES陣列資訊處理
        $files   =   $this->dealFiles($_FILES);

        foreach($files as $key => $file) {
            //過濾無效的上傳
            if(!empty($file['name'])) {

                $file['key']        = $key;
                $file['extension']  = $this->getExt($file['name']);
                $file['savepath']   = $savePath;
                $file['savename']   = uniqid().'.'.$file['extension'];
                //$this->getSaveName($file);

                if($GLOBALS['fromMobile'] == true && empty($file['extension'])){//移動裝置上傳的無字尾的圖片，默認為jpg
                    $file['extension']  = 'jpg';
                    $file['savename']   = trim($file['savename'],'.').'.jpg';
                }else{
                    // 自動檢查附件
                    if($this->autoCheck) {
                        if(!$this->check($file))
                            return false;
                    }
                }

                //儲存上傳檔案
                if(!$this->save($file)) {
                    return false;
                }
                if(function_exists($this->hashType)) {
                    $fun =  $this->hashType;
                    $file['hash']   =  $fun(auto_charset($file['savepath'].$file['savename'],'utf-8','gbk'));
                }
                //上傳成功後儲存檔案資訊，供其它地方呼叫
                unset($file['tmp_name'],$file['error']);
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
     +----------------------------------------------------------
     * 轉換上傳檔案陣列變數為正確的方式
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $files  上傳的檔案變數
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
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
     +----------------------------------------------------------
     * 獲取錯誤程式碼資訊
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param string $errorNo  錯誤號碼
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     * @throws ThinkExecption
     +----------------------------------------------------------
     */
    protected function error($errorNo)
    {
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
     +----------------------------------------------------------
     * 根據上傳檔案命名規則取得儲存檔名
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 資料
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function getSaveName($filename)
    {

        if($this->saveName){
            return $this->saveName;
        }else{
            $rule = $this->saveRule;
            if(empty($rule)) {//沒有定義命名規則，則保持檔名不變
                $saveName = $filename['name'];
            }else {
                if(function_exists($rule)) {
                    //使用函數生成一個唯一檔案標識號
                    $saveName = $rule().".".$filename['extension'];
                }else {
                    //使用給定的檔名作為標識號
                    $saveName = $rule.".".$filename['extension'];
                }
            }
            if($this->autoSub) {
                // 使用子目錄儲存檔案
                $saveName   =  $this->getSubName($filename).'/'.$saveName;
            }
            return $saveName;
        }

    }

    /**
     +----------------------------------------------------------
     * 獲取子目錄的名稱
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $file  上傳的檔案資訊
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    private function getSubName($file)
    {
        switch($this->subType) {
        case 'date':
            $dir   =  date($this->dateFormat,time());
            break;
        case 'hash':
        default:
            $name = md5($file['savename']);
            $dir   =  '';
            for($i=0;$i<$this->hashLevel;$i++) {
                $dir   .=  $name{0}.'/';
            }
            break;
        }
        if(!is_dir($file['savepath'].$dir)) {
            mk_dir($file['savepath'].$dir);
        }
        return $dir;
    }

    /**
     +----------------------------------------------------------
     * 檢查上傳的檔案
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param array $file 檔案資訊
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
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
     +----------------------------------------------------------
     * 檢查上傳的檔案類型是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $type 資料
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkType($type)
    {
        if(!empty($this->allowTypes)) {
            return in_array(strtolower($type),$this->allowTypes);
        }
        return true;
    }


    /**
     +----------------------------------------------------------
     * 檢查上傳的檔案字尾是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $ext 字尾名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkExt($ext)
    {

        if(in_array($ext,array('php','php3','exe','sh','html','asp','aspx'))){
            $this->error    =   '不允許上傳可執行的指令碼檔案，如：php、exe、html字尾的檔案';
            return false;
        }

        if(!empty($this->allowExts)) {
            return in_array(strtolower($ext),$this->allowExts,true);
        }
        return true;
    }

    /**
     +----------------------------------------------------------
     * 檢查檔案大小是否合法
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param integer $size 資料
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
    private function checkSize($size)
    {
        return !($size > $this->maxSize) || (-1 == $this->maxSize);
}

    /**
     +----------------------------------------------------------
     * 檢查檔案是否非法提交
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 檔名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
private function checkUpload($filename)
{
    return is_uploaded_file($filename);
}

    /**
     +----------------------------------------------------------
     * 取得上傳檔案的字尾
     +----------------------------------------------------------
     * @access private
     +----------------------------------------------------------
     * @param string $filename 檔名
     +----------------------------------------------------------
     * @return boolean
     +----------------------------------------------------------
     */
private function getExt($filename)
{
    $pathinfo = pathinfo($filename);
    return $pathinfo['extension'];
}

    /**
     +----------------------------------------------------------
     * 取得上傳檔案的資訊
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
public function getUploadFileInfo()
{
    return $this->uploadFileInfo;
}

    /**
     +----------------------------------------------------------
     * 取得最後一次錯誤資訊
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
public function getErrorMsg()
{
    return $this->error;
}

}//類定義結束
?>
