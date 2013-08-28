<?php
/**
 * ThinkPHP標籤庫TagLib解析基類
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 */
class TagLib
{//類定義開始

    /**
     * 標籤庫定義XML檔案
     * @var string
     * @access protected
     */
    protected $xml = '';

    /**
     * 標籤庫名稱
     * @var string
     * @access protected
     */
    protected $tagLib ='';

    /**
     * 標籤庫標籤列表
     * @var string
     * @access protected
     */
    protected $tagList = array();

    /**
     * 標籤庫分析陣列
     * @var string
     * @access protected
     */
    protected $parse = array();

    /**
     * 標籤庫是否有效
     * @var string
     * @access protected
     */
    protected $valid = false;

    /**
     * 當前模板物件
     * @var object
     * @access protected
     */
    protected $tpl;

    protected $comparison = array(' nheq '=>' !== ',' heq '=>' === ',' neq '=>' != ',' eq '=>' == ',' egt '=>' >= ',' gt '=>' > ',' elt '=>' <= ',' lt '=>' < ');

    /**
     * 架構函數
     * @access public
     */
    public function __construct()
    {
        $this->tagLib	=	strtolower(substr(get_class($this),6));
        $this->tpl		=	Template::getInstance();//ThinkTemplate::getInstance();
        $this->_initialize();
        $this->load();
    }

    /**
     * 初始化標籤庫的定義檔案
     * @return void
     */
    public function _initialize() {
        $this->xml = dirname(__FILE__).'/TagLib/'.$this->tagLib.'.xml';
    }

    /**
     * 載入模板檔案
     * @return void
     */
    public function load() {
        $array = (array)(simplexml_load_file($this->xml));
        if($array !== false) {
            $this->parse = $array;
            $this->valid = true;
        }else{
            $this->valid = false;
        }
    }

    /**
     * 分析TagLib檔案的資訊是否有效
     * 有效則轉換成陣列
     * @access public
     * @param mixed $name 資料
     * @param string $value  資料表名
     * @return string
     */
    public function valid()
    {
        return $this->valid;
    }

    /**
     * 獲取TagLib名稱
     * @access public
     * @return string
     */
    public function getTagLib()
    {
        return $this->tagLib;
    }

    /**
     * 獲取Tag列表
     * @access public
     * @return string
     */
    public function getTagList()
    {
        if(empty($this->tagList)) {
            $tags = $this->parse['tag'];
            $list = array();
            if(is_object($tags)) {
                $list[] =  array(
                    'name'=>$tags->name,
                    'content'=>$tags->bodycontent,
                    'nested'=>(!empty($tags->nested) && $tags->nested !='false') ?$tags->nested:0,
                    'attribute'=>isset($tags->attribute)?$tags->attribute:'',
                );
                if(isset($tags->alias)) {
                    $alias  =   explode(',',$tag->alias);
                    foreach ($alias as $tag){
                        $list[] =  array(
                            'name'=>$tag,
                            'content'=>$tags->bodycontent,
                            'nested'=>(!empty($tags->nested) && $tags->nested != 'false') ?$tags->nested:0,
                            'attribute'=>isset($tags->attribute)?$tags->attribute:'',
                        );
                    }
                }
            }else{
                foreach($tags as $tag) {
                    $tag = (array)$tag;
                    $list[] =  array(
                        'name'=>$tag['name'],
                        'content'=>$tag['bodycontent'],
                        'nested'=>(!empty($tag['nested']) && $tag['nested'] != 'false' )?$tag['nested']:0,
                        'attribute'=>isset($tag['attribute'])?$tag['attribute']:'',
                    );
                    if(isset($tag['alias'])) {
                        $alias  =   explode(',',$tag['alias']);
                        foreach ($alias as $tag1){
                            $list[] =  array(
                                'name'=>$tag1,
                                'content'=>$tag['bodycontent'],
                                'nested'=>(!empty($tag['nested']) && $tag['nested'] != 'false')?$tag['nested']:0,
                                'attribute'=>isset($tag['attribute'])?$tag['attribute']:'',
                            );
                        }
                    }
                }
            }
            $this->tagList = $list;
        }
        return $this->tagList;
    }

    /**
     * 獲取某個Tag屬性的資訊
     * @access public
     * @return string
     */
    public function getTagAttrList($tagName)
    {
        static $_tagCache   = array();
        $_tagCacheId        =   md5($this->tagLib.$tagName);
        if(isset($_tagCache[$_tagCacheId])) {
            return $_tagCache[$_tagCacheId];
        }
        $list = array();
        $tags = $this->parse['tag'];
        foreach($tags as $tag) {
            $tag = (array)$tag;
            if( strtolower($tag['name']) == strtolower($tagName)) {
                if(isset($tag['attribute'])) {
                    if(is_object($tag['attribute'])) {
                        // 只有一個屬性
                        $attr = $tag['attribute'];
                        $list[] = array(
                            'name'=>$attr->name,
                            'required'=>$attr->required
                        );
                    }else{
                        // 存在多個屬性
                        foreach($tag['attribute'] as $attr) {
                            $attr = (array)$attr;
                            $list[] = array(
                                'name'=>$attr['name'],
                                'required'=>$attr['required']
                            );
                        }
                    }
                }
            }
        }
        $_tagCache[$_tagCacheId]    =   $list;
        return $list;
    }

    /**
     * TagLib標籤屬性分析 返回標籤屬性陣列
     * @access public
     * @param string $tagStr 標籤內容
     * @return array
     */
    public function parseXmlAttr($attr,$tag)
    {
        //XML解析安全過濾
        $attr = str_replace(array('&','THEME_PATH','APP_TPL_PATH'),array('___',THEME_PATH,APP_TPL_PATH), $attr);
        $xml =  '<tpl><tag '.$attr.' /></tpl>';
        $xml = simplexml_load_string($xml);
        if(!$xml) {
            throw_exception(L('_XML_TAG_ERROR_').' : '.$attr);
        }
        $xml = (array)($xml->tag->attributes());
        $array = array_change_key_case($xml['@attributes']);
        $attrs  = $this->getTagAttrList($tag);
        foreach($attrs as $val) {
            $name   = strtolower($val['name']);
            if( !isset($array[$name])) {
                $array[$name] = '';
            }else{
                $array[$name] = str_replace('___','&',$array[$name]);
            }
        }
        return $array;
    }

    /**
     * 解析條件表示式
     * @access public
     * @param string $condition 表示式標籤內容
     * @return array
     */
    public function parseCondition($condition) {
        $condition = str_ireplace(array_keys($this->comparison),array_values($this->comparison),$condition);
        $condition = preg_replace('/\$(\w+):(\w+)\s/is','$\\1->\\2 ',$condition);
        switch(strtolower(C('TMPL_VAR_IDENTIFY'))) {
        case 'array': // 識別為陣列
            $condition = preg_replace('/\$(\w+)\.(\w+)\s/is','$\\1["\\2"] ',$condition);
            break;
        case 'obj':  // 識別為物件
            $condition = preg_replace('/\$(\w+)\.(\w+)\s/is','$\\1->\\2 ',$condition);
            break;
        default:  // 自動判斷陣列或物件 只支援二維
            $condition = preg_replace('/\$(\w+)\.(\w+)\s/is','(is_array($\\1)?$\\1["\\2"]:$\\1->\\2) ',$condition);
        }
        return $condition;
    }

    /**
     * 自動識別構建變數
     * @access public
     * @param string $name 變數描述
     * @return string
     */
    public function autoBuildVar($name) {
        if('Think.' == substr($name,0,6)){
            // 特殊變數
            return $this->parseThinkVar($name);
        }elseif(strpos($name,'.')) {
            $vars = explode('.',$name);
            $var  =  array_shift($vars);
            switch(strtolower(C('TMPL_VAR_IDENTIFY'))) {
            case 'array': // 識別為陣列
                $name = '$'.$var;
                foreach ($vars as $key=>$val){
                    if(0===strpos($val,'$')) {
                        $name .= '["{'.$val.'}"]';
        }else{
            $name .= '["'.$val.'"]';
        }
        }
        break;
case 'obj':  // 識別為物件
    $name = '$'.$var;
    foreach ($vars as $key=>$val)
        $name .= '->'.$val;
    break;
default:  // 自動判斷陣列或物件 只支援二維
    $name = 'is_array($'.$var.')?$'.$var.'["'.$vars[0].'"]:$'.$var.'->'.$vars[0];
        }
        }elseif(strpos($name,':')){
            // 額外的物件方式支援
            $name   =   '$'.str_replace(':','->',$name);
        }elseif(!defined($name)) {
            $name = '$'.$name;
        }
        return $name;
        }

        /**
         * 用於標籤屬性裡面的特殊模板變數解析
         * 格式 以 Think. 打頭的變數屬於特殊模板變數
         * @access public
         * @param string $varStr  變數字元串
         * @return string
         */
        public function parseThinkVar($varStr){
            $vars = explode('.',$varStr);
            $vars[1] = strtoupper(trim($vars[1]));
            $parseStr = '';
            if(count($vars)>=3){
                $vars[2] = trim($vars[2]);
                switch($vars[1]){
                case 'SERVER':    $parseStr = '$_SERVER[\''.$vars[2].'\']';break;
                case 'GET':         $parseStr = '$_GET[\''.$vars[2].'\']';break;
                case 'POST':       $parseStr = '$_POST[\''.$vars[2].'\']';break;
                case 'COOKIE':    $parseStr = '$_COOKIE[\''.$vars[2].'\']';break;
                case 'SESSION':   $parseStr = '$_SESSION[\''.$vars[2].'\']';break;
                case 'ENV':         $parseStr = '$_ENV[\''.$vars[2].'\']';break;
                case 'REQUEST':  $parseStr = '$_REQUEST[\''.$vars[2].'\']';break;
                case 'CONST':     $parseStr = strtoupper($vars[2]);break;
                case 'LANG':       $parseStr = 'L("'.$vars[2].'")';break;
                case 'CONFIG':    $parseStr = 'C("'.$vars[2].'")';break;
        }
        }else if(count($vars)==2){
            switch($vars[1]){
            case 'NOW':       $parseStr = "date('Y-m-d g:i a',time())";break;
            case 'VERSION':  $parseStr = 'THINK_VERSION';break;
            case 'TEMPLATE':$parseStr = 'C("TMPL_FILE_NAME")';break;
            case 'LDELIM':    $parseStr = 'C("TMPL_L_DELIM")';break;
            case 'RDELIM':    $parseStr = 'C("TMPL_R_DELIM")';break;
            default:  if(defined($vars[1])) $parseStr = $vars[1];
        }
        }
        return $parseStr;
        }

        }//類定義結束
?>
