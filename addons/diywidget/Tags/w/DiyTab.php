<?php
/**
 * tab標籤模組
 * @author Stream
 *
 */
class DiyTab extends TagsAbstract {
    /**
     * 是否是封閉的標籤
     * @var unknown_type
     */
    static $TAG_CLOSED = false;

    public $config = array();
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
        return dirname(__FILE__).'/DiyTab/'.$file.'.html';
    }

    /**
     * 這裡返回的是模板中需要渲染的變數
     */
    public function replace() {
        $time = str_replace(array(' ','.'),'',microtime());
        $var['data'] = $this->attr['content'];
        $parseTag = model    ( 'ParseTag' );
        foreach($var['data'] as &$value){
            $temp = $this->replaceContent($value->comment);
            $temp = str_replace('[@]','&',$temp);
            $value->comment = $parseTag->parse($temp);
        }
        $var['event'] = $this->attr['event'];

        $var['tabId'] = "i".substr($this->sign,0,5).$time;
        return $var;
    }
}
?>
