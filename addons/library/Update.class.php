<?php
// +----------------------------------------------------------------------
// | ThinkSNS
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 水上鐵 <476974493@qq.com>
// +----------------------------------------------------------------------
// $Id$

/**
 +------------------------------------------------------------------------------
 * 功能升級類
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Net
 * @author    水上鐵 <476974493@qq.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Update
{//類定義開始

    // 下載到本地的目錄
    public $downloadPath;



    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     */
    public function __construct($downloadPath='')
    {
        if(!empty($downloadPath)) {
            $this->downloadPath = $downloadPath;
        }else{
            $this->downloadPath = DATA_PATH . '/update/download/';
        }
    }

    function downloadFile($packageURL){
        if (! is_dir ( $this->downloadPath ))
            $res = mkdir ( $this->downloadPath, 0777, true );

        $file = fopen ( $packageURL, "rb" );
        if (! $file) {
            return  0;
        }

        $newfname = $this->downloadPath . basename ( $packageURL );
        $newf = fopen ( $newfname, "wb" );
        if (! $newf) {
            return  - 1;
        }

        while ( ! feof ( $file ) ) {
            $data = fread ( $file, 1024 * 8 ); // 默認獲取8K
            fwrite ( $newf, $data, 1024 * 8 );
            ob_flush ();
            flush ();
        }
        fclose ( $file );
        fclose ( $newf );

        return 1;
    }

    function unzipPackage($packageName, $targetDir='', $cleanFile=true) {
        if(empty($targetDir)){
            $targetDir = $this->downloadPath.'unzip';
        }
        if($cleanFile){
            $this->rmdirr ( $targetDir );
        }
        $package = $this->downloadPath . $packageName;

        require_once ADDON_PATH . '/library/pclzip-2-8-2/pclzip.lib.php';
        $archive = new PclZip ( $package );
        $res = $archive->extract ( PCLZIP_OPT_PATH, $targetDir, PCLZIP_OPT_REPLACE_NEWER );

        if ($res) {
            return 1;
        } else {
            return $archive->errorInfo ( true );
        }
    }
    /**
     * 壓縮檔案成zip格式
     * package  string|array 需要壓縮的檔案路徑，多個則放到陣列裡
     * zipFile  string  壓縮檔案的存在路徑
     * zipName  string  壓縮檔名，不需要帶字尾.zip
     * @return void
     */
    //
    function zipPackage($package, $zipDir, $zipName, $removePath='') {
        if (! is_dir ( $zipDir ))
            @mkdir ( $zipDir, 0777, true );

        $zipName = $zipName.'.zip';
        if(empty($removePath)){
            $removePath = SITE_PATH;
        }

        require_once ADDON_PATH . '/library/pclzip-2-8-2/pclzip.lib.php';
        $archive = new PclZip ( $zipDir.'/'.$zipName );

        $res = $archive->create ( $package, PCLZIP_OPT_REMOVE_PATH, $removePath);
        if ($res) {
            return true;
        } else {
            return $archive->errorInfo ( true );
        }
    }

    //遞迴覆蓋程式碼檔案
    function overWrittenFile($destination = '', $source = '',  $res=array()) {
        if (empty ( $source ))
            $source = $this->downloadPath.'unzip';
        if (empty ( $destination ))
            $destination = SITE_PATH;

        if(!is_dir($destination)){
            @mkdir($destination,0777,true);
        }

        $handle = dir ( $source );
        while ( $entry = $handle->read () ) {
            if (($entry != ".") && ($entry != "..")) {
                $file = $source . "/" . $entry;
                if (is_dir ( $file )) {
                    $res = $this->overWrittenFile ( $destination . "/" . $entry, $file, $res );
                } else {
                    $result = copy ( $file, $destination . "/" . $entry );
                    if (!$result) {
                        $res ['error'] [] = $file;
                    }
                }
            }
        }

        return $res;
    }

    //刪除多層目錄
    function rmdirr($dirname) {
        if (! file_exists ( $dirname )) {
            return false;
        }
        if (is_file ( $dirname ) || is_link ( $dirname )) {
            return unlink ( $dirname );
        }
        $dir = dir ( $dirname );
        if ($dir) {
            while ( false !== $entry = $dir->read () ) {
                if ($entry == '.' || $entry == '..') {
                    continue;
                }
                $this->rmdirr ( $dirname . DIRECTORY_SEPARATOR . $entry );
            }
        }
        $dir->close ();
        return rmdir ( $dirname );
    }

}//類定義結束
?>
