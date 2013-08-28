<?php
/**
 * 視訊模組
 * @author Stream
 *
 */
class DiyVideo extends TagsAbstract {
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
        return dirname(__FILE__).'/DiyVideo/'.$file.'.html';
    }

    /**
     * 這裡返回的是模板中需要渲染的變數
     */
    public function replace() {
        $var['video'] = str_replace('[@]','&',$this->attr['video']);

        $var['width'] = $this->attr['width'];
        $var['height'] = $this->attr['height'];
        return $var;
    }
}
?>
