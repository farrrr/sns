<?php
/**
 * DIY模組類
 * @author Stream
 *
 */
abstract class TagsAbstract {
    protected $templateFile = ''; //模板檔名
    protected $attr = array (); //屬性
    protected $value = ''; //如果是巢狀格式，則為內部資料
    protected $tmplCacheFile = ''; //快取檔案路徑
    protected $sign = '';

    /**
     * 參數賦值
     * @param unknown_type $attr
     * @param unknown_type $value
     */
    protected function init($attr, $value = '') {
        $this->attr = $attr;
        $this->templateFile = $this->getTemplateFile ();

        if (! empty ( $value )){
            $this->value = $value;
            $this->sign = tsmd5 ( json_encode($this->attr). $this->value );
        }else{
            $this->sign = tsmd5 ( json_encode($this->attr). $this->templateFile );
        }
        if(is_array($this->attr['head_link']) && !empty($this->attr['head_link'])){
            foreach($this->attr['head_link'] as &$value){
                $value->url = str_replace('[@]','&',$value->url);
            }
        }
        $this->tmplCacheFile = C ( 'TMPL_CACHE_PATH' ) .'/'.APP_NAME.'_'. $this->sign . C ( 'TMPL_CACHFILE_SUFFIX' );

    }
    /**
     * 編譯並返回內容
     * @param unknown_type $attr
     * @param unknown_type $value
     * @param unknown_type $tagInfo
     * @return Ambigous <void, mixed>|string
     */
    public function replaceTag($attr, $value = '', $tagInfo) {
        $this->init ( $attr,$value );
        //呼叫子類的replace方法把參數引入
        $var = $this->replace ();
        return fetch( $this->templateFile , $var);
    }
    /**
     * 儲存模組資料到資料庫
     * @param unknown_type $attr
     * @param unknown_type $value
     * @param unknown_type $tagInfo
     * @return string
     */
    public function parseTag($attr, $value = '', $tagInfo) {
        $this->init ( $attr,$value );
        $widgetDao = model ( 'DiyWidget' );
        $hasWidget = $widgetDao->checkHasWidget ( $this->sign );
        if ($hasWidget) {
            return $this->sign;
        }
        $map ['pluginId'] = $this->sign;
        $map ['tagLib'] = $tagInfo ['tagLib'];
        $map ['pageId'] = empty ( $attr ['pageId'] ) ? 0 : $attr ['pageId'];
        $map ['channelId'] = empty ( $attr ['channelId'] ) ? 0 : $attr ['channelId'];
        $map ['content'] = $value;
        $ext ['templatePath'] = $this->getTemplateFile ();
        $ext ['attr'] = serialize ( $attr );
        $ext ['tagInfo'] ['name'] = $tagInfo ['tagInfo'] ['name'];
        $map ['cacheTime'] = isset($attr['cacheTime'])?intval($attr['cacheTime']):0;
        $ext ['tagInfo'] ['path'] = $tagInfo ['tagInfo'] ['path'];
        $map ['ext'] = serialize ( $ext );
        $map ['cTime'] = time ();
        $map ['mTime'] = time ();
        $result =  model ( 'DiyWidget' )->add ( $map );
        return $this->sign;
    }

    protected function replaceContent($content){
        // 系統默認的特殊變數替換
        $replace =  array(
            '../Public'		=>	APP_PUBLIC_PATH,// 項目公共目錄
            '__PUBLIC__'	=>	WEB_PUBLIC_PATH,// 站點公共目錄
            '__TMPL__'		=>	APP_TMPL_PATH,  // 項目模板目錄
            '__ROOT__'		=>	__ROOT__,       // 當前網站地址
            '__APP__'		=>	__APP__,        // 當前項目地址
            '__URL__'		=>	__URL__,        // 當前模組地址
            '__ACTION__'	=>	__ACTION__,     // 當前操作地址
            '__SELF__'		=>	__SELF__,       // 當前頁面地址
            '__THEME__'		=>	__THEME__,		// 主題頁面地址
            '__UPLOAD__'	=>	__UPLOAD__,		// 上傳檔案地址
        );
        if(C('TOKEN_ON')) {
            if(strpos($content,'{__TOKEN__}')) {
                // 指定表單令牌隱藏域位置
                $replace['{__TOKEN__}'] =  $this->buildFormToken();
            }elseif(strpos($content,'{__NOTOKEN__}')){
                // 標記為不需要令牌驗證
                $replace['{__NOTOKEN__}'] =  '';
            }elseif(preg_match('/<\/form(\s*)>/is',$content,$match)) {
                // 智慧生成表單令牌隱藏域
                $replace[$match[0]] = $this->buildFormToken().$match[0];
            }
        }
        // 允許使用者自定義模板的字元串替換
        if(is_array(C('TMPL_PARSE_STRING')) )
            $replace =  array_merge($replace,C('TMPL_PARSE_STRING'));
        $content = str_replace(array_keys($replace),array_values($replace),$content);
        return $content;
    }

    /**
     * 解析
     */
    abstract function getTemplateFile($tpl = '');


    /**
     * 替換
     */
    abstract function replace();
}
?>
