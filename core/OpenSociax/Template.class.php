<?php
/**
 * ThinkPHP內建模板引擎類
 * 支援XML標籤和普通標籤的模板解析
 * 編譯型模板引擎 支援動態快取
 * @author liu21st <liu21st@gmail.com>
 * @version  $Id$
 */
class Template
{//類定義開始

    // 模板頁面中引入的標籤庫列表
    protected $tagLib          =  array();
    // 當前模板檔案
    protected $templateFile  =  '';
    // 模板變數
    public $tVar                 = array();
    public $config  =  array();
    private   $literal = array();

    /**
     * 取得模板例項物件
     * 靜態方法
     * @access public
     * @return ThinkTemplate
     */
    static public function  getInstance() {
        return get_instance_of(__CLASS__);
    }

    /**
     * 架構函數
     * @access public
     * @param array $config 模板引擎配置陣列
     */
    public function __construct() {
        $this->config['cache_path']         =   C('TMPL_CACHE_PATH');
        $this->config['template_suffix']    =   '.html';
        $this->config['cache_suffix']       =   '.php';
        $this->config['tmpl_cache']         =   C('TMPL_CACHE_ON');
        $this->config['cache_time']         =   C('TMPL_CACHE_TIME');
        $this->config['taglib_begin']       =   $this->stripPreg(C('TAGLIB_BEGIN'));
        $this->config['taglib_end']         =   $this->stripPreg(C('TAGLIB_END'));
        $this->config['tmpl_begin']         =   $this->stripPreg(C('TMPL_L_DELIM'));
        $this->config['tmpl_end']           =   $this->stripPreg(C('TMPL_R_DELIM'));
        $this->config['default_tmpl']       =   C('TMPL_FILE_NAME');
        $this->config['tag_level']          =   C('TAG_NESTED_LEVEL');
    }

    /**
     * 轉義處理
     * @return void
     */
    private function stripPreg($str) {
        $str = str_replace(array('{','}','(',')','|','[',']'),array('\{','\}','\(','\)','\|','\[','\]'),$str);
        return $str;
    }

    /**
     * 模板變數的獲取
     * @return mixed 模板變數值
     */
    public function get($name) {
        if(isset($this->tVar[$name]))
            return $this->tVar[$name];
        else
            return false;
    }

    /**
     * 模板變數的設定
     * @return void
     */
    public function set($name,$value) {
        $this->tVar[$name]= $value;
    }

    // 載入模板
    public function load($templateFile,$templateVar,$charset) {
        $this->tVar = $templateVar;
        $templateCacheFile  =  $this->loadTemplate($templateFile);
        // 模板陣列變數分解成為獨立變數
        extract($templateVar, EXTR_OVERWRITE);
        //載入模版快取檔案
        include $templateCacheFile;
    }

    /**
     * 載入主模板並快取
     * @access public
     * @param string $tmplTemplateFile 模板檔案
     * @param string $varPrefix  模板變數字首
     * @return string
     * @throws ThinkExecption
     */
    public function loadTemplate ($tmplTemplateFile='') {

        if(empty($tmplTemplateFile))    $tmplTemplateFile = $this->config['default_tmpl'];
        if(!is_file($tmplTemplateFile)){
            $tmplTemplateFile =  dirname($this->config['default_tmpl']).'/'.$tmplTemplateFile.$this->config['template_suffix'];
            if(!is_file($tmplTemplateFile))
                throw_exception(L('_TEMPLATE_NOT_EXIST_'));
        }
        $this->templateFile    =  $tmplTemplateFile;

        //根據模版檔名定位快取檔案
        $tmplCacheFile = $this->config['cache_path'].'/'.APP_NAME.'_'.tsmd5($tmplTemplateFile).$this->config['cache_suffix'];
        $tmplContent = '';
        // 檢查Cache檔案是否需要更新

        // 需要更新模版 讀出原模板內容
        $tmplContent = file_get_contents($tmplTemplateFile);
        //編譯模板內容
        $tmplContent = $this->compiler($tmplContent);
        // 檢測分組目錄
        if(!is_dir($this->config['cache_path']))
            mkdir($this->config['cache_path'],0777,true);
        //重寫Cache檔案
        if( false === file_put_contents($tmplCacheFile,trim($tmplContent)))
            throw_exception(L('_CACHE_WRITE_ERROR_'));

        return $tmplCacheFile;
    }

    /**
     * 編譯模板檔案內容
     * @access protected
     * @param mixed $tmplContent 模板內容
     * @return string
     */
    protected function compiler($tmplContent) {
        //模板解析
        $tmplContent = $this->parse($tmplContent);
        if(ini_get('short_open_tag'))
            // 開啟短標籤的情況要將<?標籤用echo方式輸出 否則無法正常輸出xml標識
            $tmplContent = preg_replace('/(<\?(?!php|=|$))/i', '<?php echo \'\\1\'; ?>'."\n", $tmplContent );
        // 還原被替換的Literal標籤
        $tmplContent = preg_replace('/<!--###literal(\d)###-->/eis',"\$this->restoreLiteral('\\1')",$tmplContent);
        // 添加安全程式碼
        $tmplContent  =  '<?php if (!defined(\'THINK_PATH\')) exit();?>'.$tmplContent;
        // if(C('TMPL_STRIP_SPACE')) {
        //     /* 去除html空格與換行 */
        //     $find     = array("~>\s+<~","~>(\s+\n|\r)~");
        //     $replace  = array("><",">");
        //     $tmplContent = preg_replace($find, $replace, $tmplContent);
        // }
        return trim($tmplContent);
    }

    /**
     * 模板解析入口
     * 支援普通標籤和TagLib解析 支援自定義標籤庫
     * @access public
     * @param string $content 要解析的模板內容
     * @return string
     */
    public function parse($content) {
        $begin = $this->config['taglib_begin'];
        $end   = $this->config['taglib_end'];
        // 首先替換literal標籤內容
        $content = preg_replace('/'.$begin.'literal'.$end.'(.*?)'.$begin.'\/literal'.$end.'/eis',"\$this->parseLiteral('\\1')",$content);

        // 獲取需要引入的標籤庫列表
        // 標籤庫只需要定義一次，允許引入多個一次
        // 一般放在檔案的最前面
        // 格式：<taglib name="html,mytag..." />
        // 當TAGLIB_LOAD配置為true時纔會進行檢測
        if(C('TAGLIB_LOAD')) {
            $this->getIncludeTagLib($content);
            if(!empty($this->tagLib)) {
                // 對匯入的TagLib進行解析
                $_taglibs = C('_taglibs_');
                foreach($this->tagLib as $tagLibName) {
                    // 內建標籤庫
                    if(!tsload(CORE_LIB_PATH.'/TagLib/TagLib'.ucwords(strtolower($tagLibName)).'.class.php')) {
                        // 擴展標籤庫
                        if($_taglibs && isset($_taglibs[$tagLibName])) {
                            tsload(CORE_LIB_PATH.'/TagLib/TagLib'.$_taglibs[$tagLibName].'.class.php');
                        }else{
                            throw_exception($tagLibName.L('_TAGLIB_NOT_EXIST_'));
                        }
                    }
                    $this->parseTagLib($tagLibName,$content);
                }
            }
        }
        // 預先載入的標籤庫 無需在每個模板中使用taglib標籤載入
        if(C('TAGLIB_PRE_LOAD')) {
            $tagLibs =  explode(',',C('TAGLIB_PRE_LOAD'));
            foreach ((array)$taglibs as $tag){
                $this->parseTagLib($tag,$content);
            }
        }
        // 內建標籤庫 無需使用taglib標籤匯入就可以使用
        $tagLibs =  explode(',',C('TAGLIB_BUILD_IN'));
        foreach ($tagLibs as $tag){
            $this->parseTagLib($tag,$content,true);
        }
        //解析普通模板標籤 {tagName:}
        $content = preg_replace('/('.$this->config['tmpl_begin'].')(\S.+?)('.$this->config['tmpl_end'].')/eis',"\$this->parseTag('\\2')",$content);
        return $content;
    }

    /**
     * 替換頁面中的literal標籤
     * @access public
     * @param string $content  模板內容
     * @return string|false
     */
    public function parseLiteral($content) {
        if(trim($content)=='')
            return '';
        $content = stripslashes($content);
        $i  =   count($this->literal);
        $parseStr   =   "<!--###literal{$i}###-->";
        $this->literal[$i]  = $content;
        return $parseStr;
    }

    /**
     * 還原被替換的literal標籤
     * @access public
     * @param string $tag  literal標籤序號
     * @return string|false
     */
    public function restoreLiteral($tag) {
        // 還原literal標籤
        $parseStr   =  $this->literal[$tag];
        // 銷燬literal記錄
        unset($this->literal[$tag]);
        return $parseStr;
    }

    /**
     * 搜索模板頁面中包含的TagLib庫
     * 並返回列表
     * @access public
     * @param string $content  模板內容
     * @return string|false
     */
    public function getIncludeTagLib(&$content) {
        //搜索是否有TagLib標籤
        $find = preg_match('/'.$this->config['taglib_begin'].'taglib\s(.+?)(\s*?)\/'.$this->config['taglib_end'].'\W/is',$content,$matches);
        if($find) {
            //替換TagLib標籤
            $content = str_replace($matches[0],'',$content);
            //解析TagLib標籤
            $tagLibs = $matches[1];
            $xml =  '<tpl><tag '.$tagLibs.' /></tpl>';
            $xml = simplexml_load_string($xml);
            if(!$xml)
                throw_exception(L('_XML_TAG_ERROR_'));
            $xml = (array)($xml->tag->attributes());
            $array = array_change_key_case($xml['@attributes']);
            $this->tagLib = explode(',',$array['name']);
        }
        return;
    }

    /**
     * TagLib庫解析
     * @access public
     * @param string $tagLib 要解析的標籤庫
     * @param string $content 要解析的模板內容
     * @param boolen $hide 是否隱藏標籤庫字首
     * @return string
     */
    public function parseTagLib($tagLib,&$content,$hide=false) {
        $begin = $this->config['taglib_begin'];
        $end   = $this->config['taglib_end'];
        $tLib =  get_instance_of('TagLibCx');
        if($tLib->valid()) {
            //如果標籤庫有效則取出支援標籤列表
            $tagList =  $tLib->getTagList();
            //遍歷標籤列表進行模板標籤解析
            foreach($tagList as $tag) {
                // 實際要解析的標簽名稱
                if( !$hide)
                    $startTag = $tagLib.':'.$tag['name'];
                else
                    $startTag = $tag['name'];
                // 檢查可巢狀標籤以及巢狀級別
                if($tag['nested'] && $this->config['tag_level']>1)
                    $level   =   $this->config['tag_level'];
                else
                    $level   =   1;
                $endTag = $startTag;
                if(false !== stripos($content,C('TAGLIB_BEGIN').$startTag)) {
                    if(empty($tag['attribute'])){
                        // 無屬性標籤
                        if($tag['content'] !='empty'){
                            for($i=0;$i<$level;$i++)
                                $content = preg_replace('/'.$begin.$startTag.'(\s*?)'.$end.'(.*?)'.$begin.'\/'.$endTag.'(\s*?)'.$end.'/eis',"\$this->parseXmlTag('".$tagLib."','".$tag['name']."','\\1','\\2')",$content);
                        }else{
                            $content = preg_replace('/'.$begin.$startTag.'(\s*?)\/(\s*?)'.$end.'/eis',"\$this->parseXmlTag('".$tagLib."','".$tag['name']."','\\1','')",$content);
                        }
                    }elseif($tag['content'] !='empty') {//閉合標籤解析
                        for($i=0;$i<$level;$i++)
                            $content = preg_replace('/'.$begin.$startTag.'\s(.*?)'.$end.'(.+?)'.$begin.'\/'.$endTag.'(\s*?)'.$end.'/eis',"\$this->parseXmlTag('".$tagLib."','".$tag['name']."','\\1','\\2')",$content);
                    }else {//開放標籤解析
                        // 開始標籤必須有一個空格
                        $content = preg_replace('/'.$begin.$startTag.'\s(.*?)\/(\s*?)'.$end.'/eis',"\$this->parseXmlTag('".$tagLib."','".$tag['name']."','\\1','')",$content);
                    }
                }
            }
        }
    }

    /**
     * 解析標籤庫的標籤
     * 需要呼叫對應的標籤庫檔案解析類
     * @access public
     * @param string $tagLib  標籤庫名稱
     * @param string $tag  標簽名
     * @param string $attr  標籤屬性
     * @param string $content  標籤內容
     * @return string|false
     */
    public function parseXmlTag($tagLib,$tag,$attr,$content) {
        //if (MAGIC_QUOTES_GPC) {
        $attr = stripslashes($attr);
        $content = stripslashes($content);
        //}
        if(ini_get('magic_quotes_sybase'))
            $attr =  str_replace('\"','\'',$attr);
        $tLib =  get_instance_of('TagLibCx');
        if($tLib->valid()) {
            $parse = '_'.$tag;
            $content = trim($content);
            return $tLib->$parse($attr,$content);
        }
    }

    /**
     * 模板標籤解析
     * 格式： {TagName:args [|content] }
     * @access public
     * @param string $tagStr 標籤內容
     * @return string
     */
    public function parseTag($tagStr) {
        //if (MAGIC_QUOTES_GPC) {
        $tagStr = stripslashes($tagStr);
        //}
        //還原非模板標籤
        if(preg_match('/^[\s|\d]/is',$tagStr))
            //過濾空格和數字打頭的標籤
            return C('TMPL_L_DELIM') . $tagStr .C('TMPL_R_DELIM');
        $flag =  substr($tagStr,0,1);
        $name   = substr($tagStr,1);
        if('$' == $flag){
            //解析模板變數 格式 {$varName}
            return $this->parseVar($name);
        }elseif(':' == $flag){
            // 輸出某個函數的結果
            return  '<?php echo '.$name.';?>';
        }elseif('~' == $flag){
            // 執行某個函數
            return  '<?php '.$name.';?>';
        }elseif('&' == $flag){
            // 輸出配置參數
            return '<?php echo C("'.$name.'");?>';
        }elseif('%' == $flag){
            // 輸出語言變數
            return '<?php echo L("'.$name.'");?>';
        }elseif('@' == $flag){
            // 輸出SESSION變數
            if(strpos($name,'.')) {
                $array   =  explode('.',$name);
                return '<?php echo $_SESSION["'.$array[0].'"]["'.$array[1].'"];?>';
            }else{
                return '<?php echo $_SESSION["'.$name.'"];?>';
            }
        }elseif('#' == $flag){
            // 輸出COOKIE變數
            if(strpos($name,'.')) {
                $array   =  explode('.',$name);
                return '<?php echo $_COOKIE["'.$array[0].'"]["'.$array[1].'"];?>';
            }else{
                return '<?php echo $_COOKIE["'.$name.'"];?>';
            }
        }elseif('.' == $flag){
            // 輸出GET變數
            return '<?php echo $_GET["'.$name.'"];?>';
        }elseif('^' == $flag){
            // 輸出POST變數
            return '<?php echo $_POST["'.$name.'"];?>';
        }elseif('*' == $flag){
            // 輸出常量
            return '<?php echo constant("'.$name.'");?>';
        }

        $tagStr = trim($tagStr);
        if(substr($tagStr,0,2)=='//' || (substr($tagStr,0,2)=='/*' && substr($tagStr,-2)=='*/'))
            //註釋標籤
            return '';

        //解析其它標籤
        //統一標籤格式 {TagName:args [|content]}
        $pos =  strpos($tagStr,':');
        $tag  =  substr($tagStr,0,$pos);
        $args = trim(substr($tagStr,$pos+1));

        //解析標籤內容
        if(!empty($args)) {
            $tag  =  strtolower($tag);
            switch($tag){
            case 'include':
                return $this->parseInclude($args);
                break;
            case 'load':
                return $this->parseLoad($args);
                break;
                //這裡擴展其它標籤
                //…………
            default:
                if(C('TAG_EXTEND_PARSE')) {
                    $method = C('TAG_EXTEND_PARSE');
                    if(array_key_exists($tag,$method))
                        return $method[$tag]($args);
                }
            }
        }
        return C('TMPL_L_DELIM') . $tagStr .C('TMPL_R_DELIM');
    }


    /**
     * 載入js或者css檔案
     * {load:__PUBLIC__/Js/Think/ThinkAjax.js} 載入js檔案
     * {load:__PUBLIC__/Css/style.css} 載入css檔案
     * @access public
     * @param string $params  參數
     * @return string
     */
    public function parseLoad($str) {
        $type       = strtolower(substr(strrchr($str, '.'),1));
        $parseStr = '';
        if($type=='js') {
            $parseStr .= '<script type="text/javascript" src="'.$str.'"></script>';
        }elseif($type=='css') {
            $parseStr .= '<link rel="stylesheet" type="text/css" href="'.$str.'" />';
        }
        return $parseStr;
    }

    /**
     * 模板變數解析,支援使用函數
     * 格式： {$varname|function1|function2=arg1,arg2}
     * @access public
     * @param string $varStr 變數資料
     * @return string
     */
    public function parseVar($varStr) {
        $varStr = trim($varStr);
        static $_varParseList = array();
        //如果已經解析過該變數字串，則直接返回變數值
        if(isset($_varParseList[$varStr])) return $_varParseList[$varStr];
        $parseStr ='';
        $varExists = true;
        if(!empty($varStr)){
            $varArray = explode('|',$varStr);
            //取得變數名稱
            $var = array_shift($varArray);
            //非法變數過濾 不允許在變數裡面使用 ->
            //TODO：還需要繼續完善
            if(preg_match('/->/is',$var))
                return '';
            if('Think.' == substr($var,0,6)){
                // 所有以Think.打頭的以特殊變數對待 無需模板賦值就可以輸出
                $name = $this->parseThinkVar($var);
            }
            elseif( false !== strpos($var,'.')) {
                //支援 {$var.property}
                $vars = explode('.',$var);
                $var  =  array_shift($vars);
                switch(strtolower(C('TMPL_VAR_IDENTIFY'))) {
                case 'array': // 識別為陣列
                    $name = '$'.$var;
                    foreach ($vars as $key=>$val)
                        $name .= '["'.$val.'"]';
                    break;
                case 'obj':  // 識別為物件
                    $name = '$'.$var;
                    foreach ($vars as $key=>$val)
                        $name .= '->'.$val;
                    break;
                default:  // 自動判斷陣列或物件 只支援二維
                    $name = 'is_array($'.$var.')?$'.$var.'["'.$vars[0].'"]:$'.$var.'->'.$vars[0];
                }
            }
            elseif(false !==strpos($var,':')){
                //支援 {$var:property} 方式輸出物件的屬性
                $vars = explode(':',$var);
                $var  =  str_replace(':','->',$var);
                $name = "$".$var;
                $var  = $vars[0];
            }
            elseif(false !== strpos($var,'[')) {
                //支援 {$var['key']} 方式輸出陣列
                $name = "$".$var;
                preg_match('/(.+?)\[(.+?)\]/is',$var,$match);
                $var = $match[1];
            }
            else {
                $name = "$$var";
            }
            //對變數使用函數
            if(count($varArray)>0)
                $name = $this->parseVarFunction($name,$varArray);
            $parseStr = '<?php echo ('.$name.'); ?>';
        }
        $_varParseList[$varStr] = $parseStr;
        return $parseStr;
    }

    /**
     * 對模板變數使用函數
     * 格式 {$varname|function1|function2=arg1,arg2}
     * @access public
     * @param string $name 變數名
     * @param array $varArray  函數列表
     * @return string
     */
    public function parseVarFunction($name,$varArray) {
        //對變數使用函數
        $length = count($varArray);
        //取得模板禁止使用函數列表
        $template_deny_funs = explode(',',C('TMPL_DENY_FUNC_LIST'));
        for($i=0;$i<$length ;$i++ ){
            if (0===stripos($varArray[$i],'default='))
                $args = explode('=',$varArray[$i],2);
            else
                $args = explode('=',$varArray[$i]);
            //模板函數過濾
            $args[0] = trim($args[0]);
            switch(strtolower($args[0])) {
            case 'default':  // 特殊模板函數
                $name   = '('.$name.')?('.$name.'):'.$args[1];
                break;
            default:  // 通用模板函數
                if(!in_array($args[0],$template_deny_funs)){
                    if(isset($args[1])){
                        if(strstr($args[1],'###')){
                            $args[1] = str_replace('###',$name,$args[1]);
                            $name = "$args[0]($args[1])";
                        }else{
                            $name = "$args[0]($name,$args[1])";
                        }
                    }else if(!empty($args[0])){
                        $name = "$args[0]($name)";
                    }
                }
            }
        }
        return $name;
    }

    /**
     * 特殊模板變數解析
     * 格式 以 $Think. 打頭的變數屬於特殊模板變數
     * @access public
     * @param string $varStr  變數字元串
     * @return string
     */
    public function parseThinkVar($varStr) {
        $vars = explode('.',$varStr);
        $vars[1] = strtoupper(trim($vars[1]));
        $parseStr = '';
        if(count($vars)>=3){
            $vars[2] = trim($vars[2]);
            switch($vars[1]){
            case 'SERVER':
                $parseStr = '$_SERVER[\''.strtoupper($vars[2]).'\']';break;
            case 'GET':
                $parseStr = '$_GET[\''.$vars[2].'\']';break;
            case 'POST':
                $parseStr = '$_POST[\''.$vars[2].'\']';break;
            case 'COOKIE':
                if(isset($vars[3])) {
                    $parseStr = '$_COOKIE[\''.$vars[2].'\'][\''.$vars[3].'\']';
                }else{
                    $parseStr = '$_COOKIE[\''.$vars[2].'\']';
                }break;
            case 'SESSION':
                if(isset($vars[3])) {
                    $parseStr = '$_SESSION[\''.$vars[2].'\'][\''.$vars[3].'\']';
                }else{
                    $parseStr = '$_SESSION[\''.$vars[2].'\']';
                }
                break;
            case 'ENV':
                $parseStr = '$_ENV[\''.$vars[2].'\']';break;
            case 'REQUEST':
                $parseStr = '$_REQUEST[\''.$vars[2].'\']';break;
            case 'CONST':
                $parseStr = strtoupper($vars[2]);break;
            case 'LANG':
                $parseStr = 'L("'.$vars[2].'")';break;
            case 'CONFIG':
                if(isset($vars[3])) {
                    $vars[2] .= '.'.$vars[3];
                }
                $parseStr = 'C("'.$vars[2].'")';break;
            default:break;
            }
        }else if(count($vars)==2){
            switch($vars[1]){
            case 'NOW':
                $parseStr = "date('Y-m-d g:i a',time())";
                break;
            case 'VERSION':
                $parseStr = 'THINK_VERSION';
                break;
            case 'TEMPLATE':
                $parseStr = 'C("TMPL_FILE_NAME")';
                break;
            case 'LDELIM':
                $parseStr = 'C("TMPL_L_DELIM")';
                break;
            case 'RDELIM':
                $parseStr = 'C("TMPL_R_DELIM")';
                break;
            default:
                if(defined($vars[1]))
                    $parseStr = $vars[1];
        }
            }
            return $parseStr;
            }

            /**
             * 載入公共模板並快取 和當前模板在同一路徑，否則使用相對路徑
             * @access public
             * @param string $tmplPublicName  公共模板檔名
             * @return string
             */
            public function parseInclude($tmplPublicName) {

                //thinksns修改: 2009-5-28 可以匯入風格模板目錄下的模板檔案，寫的比較死，以後再優化
                if(substr($tmplPublicName,0,9)=='__THEME__'){
                    $tmplTemplateFile   =   THEME_PATH.'/'.substr($tmplPublicName,10).'.html';
                    $parseStr = file_get_contents($tmplTemplateFile);
                    return $this->parse($parseStr);
            }
            //thinksns修改:結束

            if(substr($tmplPublicName,0,1)=='$'){
                //支援載入變數檔名
                $tmplPublicName = $this->get(substr($tmplPublicName,1));
            }



            if(is_file($tmplPublicName)) {
                // 直接包含檔案
                $parseStr = file_get_contents($tmplPublicName);
            }else {
                $tmplPublicName = trim($tmplPublicName);
                if(strpos($tmplPublicName,'@')){
                    // 引入其它模組的操作模板
                    $tmplTemplateFile   =   dirname(dirname(dirname($this->templateFile))).'/'.str_replace(array('@',':'),'/',$tmplPublicName);
            }elseif(strpos($tmplPublicName,':')){
                // 引入其它模組的操作模板
                $tmplTemplateFile   =   dirname(dirname($this->templateFile)).'/'.str_replace(':','/',$tmplPublicName);
            }else{
                // 默認匯入當前模組下面的模板
                $tmplTemplateFile = dirname($this->templateFile).'/'.$tmplPublicName;
            }
            $tmplTemplateFile .=  $this->config['template_suffix'];
            $parseStr = file_get_contents($tmplTemplateFile);
            }
            //再次對包含檔案進行模板分析
            return $this->parse($parseStr);
            }

            }//類定義結束
?>
