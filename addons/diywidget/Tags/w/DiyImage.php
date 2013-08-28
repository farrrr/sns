<?php
/**
 * 圖片模組
 * @author Stream
 *
 */
class DiyImage extends TagsAbstract {
    /**
     * 是否是封閉的標籤
     * @var unknown_type
     */
    static $TAG_CLOSED = false;

    public $config = array();

    public function __construct(){

    }
    public function getTagStatus(){
        return self::$TAG_CLOSED;
    }
    /**
     * 返回模板檔案路徑
     */
    public function getTemplateFile($tpl = "") {
        //返回需要渲染的模板
        $file = $this->attr ['style'];
        if(!empty($tpl)){
            $file = $tpl;
        }
        return dirname(__FILE__).'/DiyImage/'.$file.'.html';
    }

    /**
     * 這裡返回的是模板中需要渲染的變數
     */
    public function replace() {
        $time = str_replace(array(' ','.'),'',microtime());
        $var['image'] = $this->attr['image_list'];
        $var['effect'] = $this->attr['effect']; //動畫可選 'none','scrollx', 'scrolly', 'fade'
        $var['autoPlay'] = $this->attr['autoPlay'];//是否自動播放
        $var['autoPlayInterval'] = $this->attr['autoPlayInterval']; //自動播放間隔時間
        $var['width'] = $this->attr['width'];
        $var['height'] = $this->attr['height'];

        foreach($var['image'] as &$value){
            $value->path = getImageUrl( $value->path );
            $value->url = str_replace('[@]','&',$value->url);
        }
        $var['imgId'] = "i".substr($this->sign,0,5).$time;
        $var['imgPanel'] = "i".substr($this->sign,0,6).$time;
        $var['imgNav']   = "i".substr($this->sign,0,7).$time;
        return $var;
    }
}
?>
