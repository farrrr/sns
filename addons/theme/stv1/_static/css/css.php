<?php
/* 
	load: css, js 靜態檔案 
	啟用 gz壓縮、快取處理、過期處理、檔案合併等優化操作
*/
error_reporting(0);
if(extension_loaded('zlib')){
	//檢查伺服器是否開啟了zlib拓展
	ob_start('ob_gzhandler');
}

$gettype = 'css';
$allowed_content_types	=	array('css');
$getfiles	= explode(',', strip_tags($_GET['f']));
// $offset = 60 * 60 * 24 * 7; //過期7天
$offset = 1;


if($gettype=='css'){
	$content_type	=	'text/css';
}elseif($gettype=='js'){
	$content_type	=	'application/x-javascript';
}

header ("content-type: ".$content_type."; charset: utf-8");		//注意修改到你的編碼
// header ( "cache-control: must-revalidate" );
header ( "cache-control: max-age=".$offset );
header( "Last-Modified: " . gmdate ( "D, d M Y H:i:s", time () ) . "GMT");
header ( "Pragma: max-age=".$offset );
header ( "Expires:" . gmdate ( "D, d M Y H:i:s", time () + $offset ) . " GMT" );
set_cache_limit($offset);

ob_start("compress");

function compress($buffer) {//去除檔案中的註釋
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	return $buffer;
}

function set_cache_limit($second=1){
	$second=intval($second); 
	if($second==0) {
		return;
	}
	$etag=time()."||".base64_encode( $_SERVER['REQUEST_URI'] );
	
	if(!isset($_SERVER['HTTP_IF_NONE_MATCH'])){
		header("Etag:$etag",true,200);	
		return;
	}else{
		$id = $_SERVER['HTTP_IF_NONE_MATCH'];
	}

	list( $time , $uri ) = explode ( "||" , $id );

	if($time < (time()-$second))
	{//過期了，發送新tag
		header("Etag:$etag",true,200);
	}else
	{//未過期，發送舊tag
		header("Etag:$id",true,304);	    
		exit(-1);
	}
}

foreach($getfiles as $file){
	$fileType = strtolower( substr($file, strrpos($file, '.') + 1 ) );
	if(in_array($fileType, $allowed_content_types)){
		//包含你的全部css文件
		readfile(basename($file));
	}else{
		echo 'not allowed file type:'.$file;
	}
}

//輸出buffer中的內容，即壓縮後的css檔案
if(extension_loaded('zlib')){
	ob_end_flush();
}