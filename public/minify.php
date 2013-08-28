<?php
/*
    load: css, js 靜態檔案
    啟用 gz壓縮、快取處理、過期處理、檔案合併等優化操作
 */
if(extension_loaded('zlib')){
    //檢查伺服器是否開啟了zlib拓展
    ob_start('ob_gzhandler');
}

$allowed_content_types  =   array('js','css');

$getfiles   = explode(',', strip_tags($_GET['f']));

//解析參數
$gettype    = (isset($_GET['t']) && $_GET['t']=='css')?'css':'js';

if($gettype=='css'){
    $content_type   =   'text/css';
}elseif($gettype=='js'){
    $content_type   =   'application/x-javascript';
}else{
    die('not allowed content type');
}

header ("content-type: ".$content_type."; charset: utf-8");     //注意修改到你的編碼
header ("cache-control: must-revalidate");              //
header ("expires: " . gmdate ("D, d M Y H:i:s", time() + 60 * 60 * 24 * 7 ) . " GMT");  //過期時間

ob_start("compress");

function compress($buffer) {//去除檔案中的註釋
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    return $buffer;
}

foreach($getfiles as $file){
    $fileType = strtolower( substr($file, strrpos($file, '.') + 1 ) );
    if(in_array($fileType, $allowed_content_types)){
        //包含你的全部css文件
        include($file);
    }else{
        echo 'not allowed file type:'.$file;
    }
}

//輸出buffer中的內容，即壓縮後的css檔案
if(extension_loaded('zlib')){
    ob_end_flush();
}
?>
