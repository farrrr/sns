<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
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
 * 影象操作類庫
 +------------------------------------------------------------------------------
 * @category   ORG
 * @package  ORG
 * @subpackage  Util
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 +------------------------------------------------------------------------------
 */
class Image
{//類定義開始

    /**
     +----------------------------------------------------------
     * 取得影象資訊
     *
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image 影象檔名
     +----------------------------------------------------------
     * @return mixed
     +----------------------------------------------------------
     */
    static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if( $imageInfo!== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"=>$imageInfo[0],
                "height"=>$imageInfo[1],
                "type"=>$imageType,
                "size"=>$imageSize,
                "mime"=>$imageInfo['mime']
            );
            return $info;
        }else {
            return false;
        }
    }

    /**
     +----------------------------------------------------------
     * 顯示伺服器影象檔案
     * 支援URL方式
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $imgFile 影象檔名
     * @param string $text 文字字元串
     * @param string $width 影象寬度
     * @param string $height 影象高度
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function showImg($imgFile,$text='',$width=80,$height=30) {
        //獲取影象檔案資訊
        $info = Image::getImageInfo($imgFile);
        if($info !== false) {
            $createFun  =   str_replace('/','createfrom',$info['mime']);
            $im = $createFun($imgFile);
            if($im) {
                $ImageFun= str_replace('/','',$info['mime']);
                if(!empty($text)) {
                    $tc  = imagecolorallocate($im, 0, 0, 0);
                    imagestring($im, 3, 5, 5, $text, $tc);
                }
                if($info['type']=='png' || $info['type']=='gif') {
                    imagealphablending($im, false);//取消默認的混色模式
                    imagesavealpha($im,true);//設定儲存完整的 alpha 通道資訊
                }
                header("Content-type: ".$info['mime']);
                $ImageFun($im);
                imagedestroy($im);
                return ;
            }
        }
        //獲取或者創建影象檔案失敗則生成空白PNG圖片
        $im  = imagecreatetruecolor($width, $height);
        $bgc = imagecolorallocate($im, 255, 255, 255);
        $tc  = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 150, 30, $bgc);
        imagestring($im, 4, 5, 5, "NO PIC", $tc);
        Image::output($im);
        return ;
    }

    /**
     * 切割圖片
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image  原圖
     * @param string $cutfile 切割後的圖片
     * @param int $cutWidth
     * @param int $cutHeight
     +----------------------------------------------------------
     * @return void
     */
    static function cut($image,$filename,$maxWidth='',$maxHeight=''){

        // 獲取原圖資訊
        $info  = Image::getImageInfo($image);
        //dump($image);
        if($info !== false) {
            $srcWidth  = $info['width'];
            $srcHeight = $info['height'];
            $pathinfo = pathinfo($image);
            $type =  $pathinfo['extension'];
            $type = empty($type)?$info['type']:$type;
            $type   =   strtolower($type);
            $interlace  =  $interlace? 1:0;
            unset($info);
            // 載入原圖
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
            $srcImg     = $createFun($image);

            //創建縮圖
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($maxWidth, $maxHeight);
            else
                $thumbImg = imagecreate($maxWidth, $maxHeight);

            // 新建PNG縮圖通道透明處理
            if('png'==$type) {
                imagealphablending($thumbImg, false);//取消默認的混色模式
                imagesavealpha($thumbImg,true);//設定儲存完整的 alpha 通道資訊
            }elseif('gif'==$type) {
                // 新建GIF縮圖預處理，保證透明效果不失效
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一個綠色
                imagecolortransparent($thumbImg,$background_color);  //  設定為透明色，若註釋掉該行則輸出綠色的圖
            }

            // 計算縮放比例
            if(($maxWidth/$maxHeight)>=($srcWidth/$srcHeight)){
                //寬不變,截高，從中間擷取 y=
                $width  =   $srcWidth;
                $height =   $srcWidth*($maxHeight/$maxWidth);
                $x      =   0;
                $y      =   ($srcHeight-$height)*0.5;
            }else{
                //高不變,截寬，從中間擷取，x=
                $width  =   $srcHeight*($maxWidth/$maxHeight);
                $height =   $srcHeight;
                $x      =   ($srcWidth-$width)*0.5;
                $y      =   0;
            }
            // 復製圖片
            if(function_exists("ImageCopyResampled")){
                ImageCopyResampled($thumbImg, $srcImg, 0, 0, $x, $y, $maxWidth, $maxHeight, $width,$height);
            }else{
                ImageCopyResized($thumbImg, $srcImg, 0, 0, $x, $y, $maxWidth, $maxHeight,  $width,$height);
            }
            ImageDestroy($srcImg);

            // 對jpeg圖形設定隔行掃描
            if('jpg'==$type || 'jpeg'==$type)   imageinterlace($thumbImg,$interlace);

            // 生成圖片
            //$imageFun = 'image'.($type=='jpg'?'jpeg':$type);
            $imageFun   =   'imagepng';
            $filename  = empty($filename)? substr($image,0,strrpos($image, '.')).$suffix.'.'.$type : $filename;

            $imageFun($thumbImg,$filename);
            ImageDestroy($thumbImg);
            return $filename;
        }
        return false;
    }
    /**

     +----------------------------------------------------------
     * 生成縮圖
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image  原圖
     * @param string $type 影象格式
     * @param string $thumbname 縮圖檔名
     * @param string $maxWidth  寬度
     * @param string $maxHeight  高度
     * @param string $position 縮圖儲存目錄
     * @param boolean $interlace 啟用隔行掃描
     +----------------------------------------------------------
     * @return void
     +----------------------------------------------------------
     */
    static function thumb($image,$thumbname,$type='',$maxWidth=200,$maxHeight='auto',$interlace=true)
    {
        // 獲取原圖資訊
        $info  = Image::getImageInfo($image);
        if($info !== false) {
            $srcWidth  = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type)?$info['type']:$type;
            $type = strtolower($type);
            $interlace  =  $interlace? 1:0;
            unset($info);
            if( $maxHeight=='auto' ){
                $scale = $maxWidth/$srcWidth;
            }else{
                $scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // 計算縮放比例
            }
            if($scale>=1) {
                // 超過原圖大小不再縮略
                $width   =  $srcWidth;
                $height  =  $srcHeight;
            }else{
                // 縮圖尺寸
                $width  = (int)($srcWidth*$scale);
                $height = (int)($srcHeight*$scale);
            }

            // 載入原圖
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
            $srcImg     = $createFun($image);

            //創建縮圖
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            // 新建PNG縮圖通道透明處理
            if('png'==$type) {
                imagealphablending($thumbImg, false);//取消默認的混色模式
                imagesavealpha($thumbImg,true);//設定儲存完整的 alpha 通道資訊
            }elseif('gif'==$type) {
                // 新建GIF縮圖預處理，保證透明效果不失效
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一個綠色
                imagecolortransparent($thumbImg,$background_color);  //  設定為透明色，若註釋掉該行則輸出綠色的圖
            }

            // 復製圖片
            if(function_exists("ImageCopyResampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
            /*if('gif'==$type || 'png'==$type) {
                //imagealphablending($thumbImg, false);//取消默認的混色模式
                //imagesavealpha($thumbImg,true);//設定儲存完整的 alpha 通道資訊
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一個綠色
                imagecolortransparent($thumbImg,$background_color);  //  設定為透明色，若註釋掉該行則輸出綠色的圖
            }*/

            // 對jpeg圖形設定隔行掃描
            if('jpg'==$type || 'jpeg'==$type)   imageinterlace($thumbImg,$interlace);

            //$gray=ImageColorAllocate($thumbImg,255,0,0);
            //ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
            // 生成圖片
            $imageFun = 'image'.($type=='jpg'?'jpeg':$type);
            $imageFun($thumbImg,$thumbname);
            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $thumbname;
        }
        return false;
    }

    /**
     +----------------------------------------------------------
     * 根據給定的字元串生成影象
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $string  字元串
     * @param string $size  影象大小 width,height 或者 array(width,height)
     * @param string $font  字型資訊 fontface,fontsize 或者 array(fontface,fontsize)
     * @param string $type 影象格式 默認PNG
     * @param integer $disturb 是否干擾 1 點干擾 2 線干擾 3 複合干擾 0 無干擾
     * @param bool $border  是否加邊框 array(color)
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function buildString($string,$rgb=array(),$filename='',$type='png',$disturb=1,$border=true) {
        if(is_string($size))        $size   =   explode(',',$size);
        $width  =   $size[0];
        $height =   $size[1];
        if(is_string($font))        $font   =   explode(',',$font);
        $fontface   =   $font[0];
        $fontsize       =   $font[1];
        $length     =   strlen($string);
        $width = ($length*9+10)>$width?$length*9+10:$width;
        $height =   22;
        if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
            $im = @imagecreatetruecolor($width,$height);
        }else {
            $im = @imagecreate($width,$height);
        }
        if(empty($rgb)) {
            $color = imagecolorallocate($im, 102, 104, 104);
        }else{
            $color = imagecolorallocate($im, $rgb[0], $rgb[1], $rgb[2]);
        }
        $backColor = imagecolorallocate($im, 255,255,255);    //背景色（隨機）
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //邊框色
        $pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //點顏色

        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        @imagestring($im, 5, 5, 3, $string, $color);
        if(!empty($disturb)) {
            // 添加干擾
            if($disturb=1 || $disturb=3) {
                for($i=0;$i<25;$i++){
                    imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pointColor);
                }
            }elseif($disturb=2 || $disturb=3){
                for($i=0;$i<10;$i++){
                    imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$pointColor);
                }
            }
        }
        Image::output($im,$type,$filename);
    }

    /**
     +----------------------------------------------------------
     * 生成影象驗證碼
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $length  位數
     * @param string $mode  類型
     * @param string $type 影象格式
     * @param string $width  寬度
     * @param string $height  高度
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function buildImageVerify($length=4,$mode=1,$type='png',$width=48,$height=22,$verifyName='verify')
    {
        tsload(ADDON_PATH.'/library/String.class.php');
        $randval = String::rand_string($length,$mode);
        //轉換成大寫字母.
        $_SESSION[$verifyName]= md5(strtoupper($randval));
        $width = ($length*10+10)>$width?$length*10+10:$width;
        if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
            $im = @imagecreatetruecolor($width,$height);
        }else {
            $im = @imagecreate($width,$height);
        }
        $r = Array(225,255,255,223);
        $g = Array(225,236,237,255);
        $b = Array(225,236,166,125);
        $key = mt_rand(0,3);

        $backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]);    //背景色（隨機）
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //邊框色
        $pointColor = imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));                 //點顏色

        @imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        $stringColor = imagecolorallocate($im,mt_rand(0,200),mt_rand(0,120),mt_rand(0,120));
        // 干擾
        for($i=0;$i<10;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
        }
        for($i=0;$i<25;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$pointColor);
        }
        for($i=0;$i<$length;$i++) {
            imagestring($im,5,$i*10+5,mt_rand(1,8),$randval{$i}, $stringColor);
        }
        //        @imagestring($im, 5, 5, 3, $randval, $stringColor);
        Image::output($im,$type);
    }

    // 中文驗證碼
    static function GBVerify($length=4,$type='png',$width=180,$height=50,$fontface='simhei.ttf',$verifyName='verify') {
        tsload(ADDON_PATH.'/library/String.class.php');
        $code = String::rand_string($length,4);
        $width = ($length*45)>$width?$length*45:$width;
        $_SESSION[$verifyName]= md5($code);
        $im=imagecreatetruecolor($width,$height);
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //邊框色
        $bkcolor=imagecolorallocate($im,250,250,250);
        imagefill($im,0,0,$bkcolor);
        @imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        // 干擾
        for($i=0;$i<15;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
        }
        for($i=0;$i<255;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
        }
        if(!is_file($fontface)) {
            $fontface = dirname(__FILE__)."/".$fontface;
        }
        for($i=0;$i<$length;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,120),mt_rand(0,120),mt_rand(0,120)); //這樣保證隨機出來的顏色較深。
            $codex= String::msubstr($code,$i,1);
            imagettftext($im,mt_rand(16,20),mt_rand(-60,60),40*$i+20,mt_rand(30,35),$fontcolor,$fontface,$codex);
        }
        Image::output($im,$type);
    }

    /**
     +----------------------------------------------------------
     * 把影象轉換成字元顯示
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $image  要顯示的影象
     * @param string $type  影象類型，默認自動獲取
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function showASCIIImg($image,$string='',$type='')
    {
        $info  = Image::getImageInfo($image);
        if($info !== false) {
            $type = empty($type)?$info['type']:$type;
            unset($info);
            // 載入原圖
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);
            $im     = $createFun($image);
            $dx = imagesx($im);
            $dy = imagesy($im);
            $i  =   0;
            $out   =  '<span style="padding:0px;margin:0;line-height:100%;font-size:1px;">';
            set_time_limit(0);
            for($y = 0; $y < $dy; $y++) {
                for($x=0; $x < $dx; $x++) {
                    $col = imagecolorat($im, $x, $y);
                    $rgb = imagecolorsforindex($im,$col);
                    $str   =   empty($string)?'*':$string[$i++];
                    $out .= sprintf('<span style="margin:0px;color:#%02x%02x%02x">'.$str.'</span>',$rgb['red'],$rgb['green'],$rgb['blue']);
                }
                $out .= "<br>\n";
            }
            $out .=  '</span>';
            imagedestroy($im);
            return $out;
        }
        return false;
    }

    /**
     +----------------------------------------------------------
     * 生成高階影象驗證碼
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $type 影象格式
     * @param string $width  寬度
     * @param string $height  高度
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function showAdvVerify($type='png',$width=180,$height=40,$verifyName='verifyCode')
    {
        $rand   =   range('a','z');
        shuffle($rand);
        $verifyCode =   array_slice($rand,0,10);
        $letter = implode(" ",$verifyCode);
        $_SESSION[$verifyName] = $verifyCode;
        $im = imagecreate($width,$height);
        $r = array(225,255,255,223);
        $g = array(225,236,237,255);
        $b = array(225,236,166,125);
        $key = mt_rand(0,3);
        $backColor = imagecolorallocate($im, $r[$key],$g[$key],$b[$key]);
        $borderColor = imagecolorallocate($im, 100, 100, 100);                    //邊框色
        imagefilledrectangle($im, 0, 0, $width - 1, $height - 1, $backColor);
        imagerectangle($im, 0, 0, $width-1, $height-1, $borderColor);
        $numberColor = imagecolorallocate($im, 255,rand(0,100), rand(0,100));
        $stringColor = imagecolorallocate($im, rand(0,100), rand(0,100), 255);
        // 添加干擾
        /*
        for($i=0;$i<10;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagearc($im,mt_rand(-10,$width),mt_rand(-10,$height),mt_rand(30,300),mt_rand(20,200),55,44,$fontcolor);
        }
        for($i=0;$i<255;$i++){
            $fontcolor=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
            imagesetpixel($im,mt_rand(0,$width),mt_rand(0,$height),$fontcolor);
        }*/
        imagestring($im, 5, 5, 1, "0 1 2 3 4 5 6 7 8 9", $numberColor);
        imagestring($im, 5, 5, 20, $letter, $stringColor);
        Image::output($im,$type);
    }

    /**
     +----------------------------------------------------------
     * 生成UPC-A條形碼
     +----------------------------------------------------------
     * @static
     +----------------------------------------------------------
     * @param string $type 影象格式
     * @param string $type 影象格式
     * @param string $lw  單元寬度
     * @param string $hi   條碼高度
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    static function UPCA($code,$type='png',$lw=2,$hi=100) {
        static $Lencode = array('0001101','0011001','0010011','0111101','0100011',
            '0110001','0101111','0111011','0110111','0001011');
        static $Rencode = array('1110010','1100110','1101100','1000010','1011100',
            '1001110','1010000','1000100','1001000','1110100');
        $ends = '101';
        $center = '01010';
        /* UPC-A Must be 11 digits, we compute the checksum. */
        if ( strlen($code) != 11 ) { die("UPC-A Must be 11 digits."); }
        /* Compute the EAN-13 Checksum digit */
        $ncode = '0'.$code;
        $even = 0; $odd = 0;
        for ($x=0;$x<12;$x++) {
            if ($x % 2) { $odd += $ncode[$x]; } else { $even += $ncode[$x]; }
        }
        $code.=(10 - (($odd * 3 + $even) % 10)) % 10;
        /* Create the bar encoding using a binary string */
        $bars=$ends;
        $bars.=$Lencode[$code[0]];
        for($x=1;$x<6;$x++) {
            $bars.=$Lencode[$code[$x]];
        }
        $bars.=$center;
        for($x=6;$x<12;$x++) {
            $bars.=$Rencode[$code[$x]];
        }
        $bars.=$ends;
        /* Generate the Barcode Image */
        if ( $type!='gif' && function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($lw*95+30,$hi+30);
        }else {
            $im = imagecreate($lw*95+30,$hi+30);
        }
        $fg = ImageColorAllocate($im, 0, 0, 0);
        $bg = ImageColorAllocate($im, 255, 255, 255);
        ImageFilledRectangle($im, 0, 0, $lw*95+30, $hi+30, $bg);
        $shift=10;
        for ($x=0;$x<strlen($bars);$x++) {
            if (($x<10) || ($x>=45 && $x<50) || ($x >=85)) { $sh=10; } else { $sh=0; }
                if ($bars[$x] == '1') { $color = $fg; } else { $color = $bg; }
                    ImageFilledRectangle($im, ($x*$lw)+15,5,($x+1)*$lw+14,$hi+5+$sh,$color);
        }
        /* Add the Human Readable Label */
        ImageString($im,4,5,$hi-5,$code[0],$fg);
        for ($x=0;$x<5;$x++) {
            ImageString($im,5,$lw*(13+$x*6)+15,$hi+5,$code[$x+1],$fg);
            ImageString($im,5,$lw*(53+$x*6)+15,$hi+5,$code[$x+6],$fg);
        }
        ImageString($im,4,$lw*95+17,$hi-5,$code[11],$fg);
        /* Output the Header and Content. */
        Image::output($im,$type);
    }

    static function output($im,$type='png',$filename='')
    {
        header("Content-type: image/".$type);
        $ImageFun='image'.$type;
        if(empty($filename)) {
            $ImageFun($im);
        }else{
            $ImageFun($im,$filename);
        }
        imagedestroy($im);
    }


}//類定義結束

if(!function_exists('ImageCreateFrombmp')){
    function ImageCreateFrombmp($filename)
    {

        $buf=@file_get_contents($filename);

        if(strlen($buf)<54) return false;

        $file_header=unpack("sbfType/LbfSize/sbfReserved1/sbfReserved2/LbfOffBits",substr($buf,0,14));
        if($file_header["bfType"]!=19778) return false;
        $info_header=unpack("LbiSize/lbiWidth/lbiHeight/sbiPlanes/sbiBitCountLbiCompression/LbiSizeImage/lbiXPelsPerMeter/lbiYPelsPerMeter/LbiClrUsed/LbiClrImportant",substr($buf,14,40));
        if($info_header["biBitCountLbiCompression"]==2) return false;
        $line_len=round($info_header["biWidth"]*$info_header["biBitCountLbiCompression"]/8);
        $x=$line_len%4;
        if($x>0) $line_len+=4-$x;

        $img=imagecreatetruecolor($info_header["biWidth"],$info_header["biHeight"]);
        switch($info_header["biBitCountLbiCompression"]){
        case 4:
            $colorset=unpack("L*",substr($buf,54,64));
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    if($x%2)
                        $colors[]=$colorset[(ord($buf[$y_pos+($x+1)/2])&0xf)+1];
                    else
                        $colors[]=$colorset[((ord($buf[$y_pos+$x/2+1])>>4)&0xf)+1];
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 8:
            $colorset=unpack("L*",substr($buf,54,1024));
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $colors[]=$colorset[ord($buf[$y_pos+$x])+1];
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 16:
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $i=$x*2;
                    $color=ord($buf[$y_pos+$i])|(ord($buf[$y_pos+$i+1])<<8);
                    $colors[]=imagecolorallocate($img,(($color>>10)&0x1f)*0xff/0x1f,(($color>>5)&0x1f)*0xff/0x1f,($color&0x1f)*0xff/0x1f);
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        case 24:
            for($y=0;$y<$info_header["biHeight"];$y++){
                $colors=array();
                $y_pos=$y*$line_len+$file_header["bfOffBits"];
                for($x=0;$x<$info_header["biWidth"];$x++){
                    $i=$x*3;
                    $colors[]=imagecolorallocate($img,ord($buf[$y_pos+$i+2]),ord($buf[$y_pos+$i+1]),ord($buf[$y_pos+$i]));
                }
                imagesetstyle($img,$colors);
                imageline($img,0,$info_header["biHeight"]-$y-1,$info_header["biWidth"],$info_header["biHeight"]-$y-1,IMG_COLOR_STYLED);
            }
            break;
        default:
            return false;
            break;
        }
        return $img;
    }
    function imagebmp(&$im, $filename = '', $bit = 8, $compression = 0)
    {
        if (!in_array($bit, array(1, 4, 8, 16, 24, 32)))
        {
            $bit = 8;

        }
        else if ($bit == 32) // todo:32 bit
        {
            $bit = 24;
        }

        $bits = pow(2, $bit);

        // 調整調色盤
        imagetruecolortopalette($im, true, $bits);
        $width = imagesx($im);
        $height = imagesy($im);
        $colors_num = imagecolorstotal($im);

        if ($bit <= 8)
        {
            // 顏色索引
            $rgb_quad = '';
            for ($i = 0; $i < $colors_num; $i ++)
            {
                $colors = imagecolorsforindex($im, $i);
                $rgb_quad .= chr($colors['blue']) . chr($colors['green']) . chr($colors['red']) . "\0";
            }

            // 點陣圖資料
            $bmp_data = '';

            // 非壓縮
            if ($compression == 0 || $bit < 8)
            {
                if (!in_array($bit, array(1, 4, 8)))
                {
                    $bit = 8;
                }

                $compression = 0;

                // 每行位元組數必須為4的倍數，補齊。


                $extra = '';
                $padding = 4 - ceil($width / (8 / $bit)) % 4;
                if ($padding % 4 != 0)
                {
                    $extra = str_repeat("\0", $padding);
                }

                for ($j = $height - 1; $j >= 0; $j --)
                {
                    $i = 0;
                    while ($i < $width)
                    {
                        $bin = 0;
                        $limit = $width - $i < 8 / $bit ? (8 / $bit - $width + $i) * $bit : 0;

                        for ($k = 8 - $bit; $k >= $limit; $k -= $bit)
                        {
                            $index = imagecolorat($im, $i, $j);
                            $bin |= $index << $k;
                            $i ++;
                        }

                        $bmp_data .= chr($bin);
                    }

                    $bmp_data .= $extra;
                }
            }
            // RLE8 壓縮
            else if ($compression == 1 && $bit == 8)
            {
                for ($j = $height - 1; $j >= 0; $j --)
                {
                    $last_index = "\0";
                    $same_num   = 0;
                    for ($i = 0; $i <= $width; $i ++)
                    {
                        $index = imagecolorat($im, $i, $j);
                        if ($index !== $last_index || $same_num > 255)
                        {
                            if ($same_num != 0)
                            {
                                $bmp_data .= chr($same_num) . chr($last_index);
                            }

                            $last_index = $index;
                            $same_num = 1;
                        }
                        else
                        {
                            $same_num ++;
                        }
                    }

                    $bmp_data .= "\0\0";
                }

                $bmp_data .= "\0\1";
            }

            $size_quad = strlen($rgb_quad);
            $size_data = strlen($bmp_data);
        }
        else
        {
            // 每行位元組數必須為4的倍數，補齊。
            $extra = '';
            $padding = 4 - ($width * ($bit / 8)) % 4;
            if ($padding % 4 != 0)
            {
                $extra = str_repeat("\0", $padding);
        }

        // 點陣圖資料
        $bmp_data = '';

        for ($j = $height - 1; $j >= 0; $j --)
        {
            for ($i = 0; $i < $width; $i ++)
            {
                $index = imagecolorat($im, $i, $j);
                $colors = imagecolorsforindex($im, $index);

                if ($bit == 16)
                {
                    $bin = 0 << $bit;

                    $bin |= ($colors['red'] >> 3) << 10;
                    $bin |= ($colors['green'] >> 3) << 5;
                    $bin |= $colors['blue'] >> 3;

                    $bmp_data .= pack("v", $bin);
        }
        else
        {
            $bmp_data .= pack("c*", $colors['blue'], $colors['green'], $colors['red']);
        }

        // todo: 32bit;
        }

        $bmp_data .= $extra;
        }

        $size_quad = 0;
        $size_data = strlen($bmp_data);
        $colors_num = 0;
        }

        // 點陣圖檔案頭
        $file_header = "BM" . pack("V3", 54 + $size_quad + $size_data, 0, 54 + $size_quad);

        // 點陣圖資訊頭
        $info_header = pack("V3v2V*", 0x28, $width, $height, 1, $bit, $compression, $size_data, 0, 0, $colors_num, 0);
        // 寫入檔案
        if ($filename != '')
        {
            $fp = fopen($filename, "wb");

            fwrite($fp, $file_header);
            fwrite($fp, $info_header);
            fwrite($fp, $rgb_quad);
            fwrite($fp, $bmp_data);
            fclose($fp);

            return 1;
        }

        // 瀏覽器輸出
        header("Content-Type: image/bmp");
        echo $file_header . $info_header;
        echo $rgb_quad;
        echo $bmp_data;

        return 1;
        }
        }
?>
