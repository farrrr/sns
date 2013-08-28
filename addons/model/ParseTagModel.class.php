<?php
/**
 * 解析標籤類
 * @author Stream
 *
 */
class ParseTagModel {
    private $left = "<";
    private $right = ">";
    private $sgin = "";
    /**
     * 輸入的內容
     * @var string
     */
    private $content;

    private $replace = true;

    private $scope = false;

    /**
     * 解析出來的Tags
     * @var array
     */
    private static $tags = array ();
    private $fileObject = null;

    /**
     +----------------------------------------------------------
     * 架構函數
     +----------------------------------------------------------
     * @author melec製作
     * @access public
     +----------------------------------------------------------
     */
    public function __construct() {
        //TODO 掃描目錄


        $icopath = ADDON_PATH . DIRECTORY_SEPARATOR . "diywidget" . DIRECTORY_SEPARATOR . "Tags";
        if (! is_dir ( $icopath )) {
            return $this;
        }
        if (empty ( self::$tags )) {
            self::$tags = self::traversalDir ( $icopath );
        }
    }
    /**
     * 解析並得到替換後的內容
     * 解析整個字元串中的標籤
     * 用於預覽功能
     * @param $content string
     */
    public function parse($content, $replace = true, $scope = false) {
        $this->replace = $replace;
        $this->scope = $scope;
        foreach ( self::$tags as $tagName => $path ) {
            $content = $this->pregReplaceTag ( $tagName, $path, $content, $replace );
        }
        return $content;
    }
    /**
     * 解析已有的模組
     * @param unknown_type $content
     * @param unknown_type $cacheOpen
     * @return mixed
     */
    public function parseId($content, $cacheOpen = true) {
        $preg = "/\[widget:(\w+)\]/siU";
        preg_match_all ( $preg, $content, $pluginIds );
        $pluginIds = array_merge ( array_filter ( $pluginIds ) );
        $pluginIds = array_combine ( $pluginIds [0], $pluginIds [1] );
        $data = model ( 'DiyWidget' )->getTagInofs ( $pluginIds );
        foreach ( $pluginIds as $key => $value ) {
            $widgetKey = $value;
            $tagInfo = $data [$value];
            if (isset ( $tagInfo ['attr'] ['cache_timeout'] )) {
                $cacheTime = $tagInfo ['attr'] ['cache_timeout'];
            } else {
                $cacheTime = $cacheOpen;
            }
            if ($cacheOpen && $cacheTime != 0 && C('MEMCACHED_ON')) {
                $cache = service ( 'Cache', array ("type" => "memcache" ) );
                if (! $value = $cache->getWidget($widgetKey)) {
                    $attrSet = $tagInfo ['attr'];
                    $tagName = $tagInfo ['tagName'];
                    $value = $tagInfo ['content'];
                    $path = self::$tags [strtolower ( $tagName )];
                    list ( $dir, $file ) = explode ( ':', $tagName );
                    $file = ucfirst ( $file );
                    require_once $path;
                    $fileObjct = new $file ();
                    if ($fileObjct->getTagStatus () || ! empty ( $value )) {
                        $value = $fileObjct->replaceTag ( $attrSet, $value, $tagInfo );
                    } else {
                        $value = $fileObjct->replaceTag ( $attrSet, '', $tagInfo );
                    }
                    $cache->setWidget($widgetKey,$value,$cacheTime);
                }
            } else {
                $attrSet = $tagInfo ['attr'];
                $tagName = $tagInfo ['tagName'];
                $value = $tagInfo ['content'];
                if(strpos($tagInfo['attr']['style'],'custom') != 0 && empty($value)) continue;
                $path = self::$tags [strtolower ( $tagName )];
                list ( $dir, $file ) = explode ( ':', $tagName );
                $file = ucfirst ( $file );
                if(!is_file($path)) continue;
                require_once $path;
                $fileObjct = new $file ();
                if ($fileObjct->getTagStatus () || ! empty ( $value )) {
                    $value = $fileObjct->replaceTag ( $attrSet, $value, $tagInfo );
                } else {
                    $value = $fileObjct->replaceTag ( $attrSet, '', $tagInfo );
                }
            }

            $content = str_replace ( $key, $value, $content );
        }
        return $content;
    }
    /**
     * 查找模組在資料庫中是否存在
     * @param unknown_type $sign
     */
    public function getTagInfo($sign) {
        return model ( 'DiyWidget' )->getTagInfo ( $sign );
    }

    public function setContent($content, $sign) {
        $save ['content'] = $content;
        $map ['pluginId'] = $sign;
        return model ( 'DiyWidget' )->where ( $map )->save ( $save );
    }

    public function getTplContent($tpl, $tagName, $sign) {
        if (strpos ( $tpl, 'custom' ) !== false) {
            return model ( 'DiyWidget' )->getTemplateByPluginId ( $sign );
        } else {
            list ( $dir, $file ) = explode ( ':', $tagName );
            $file = ucfirst ( $file );

            require_once self::$tags [strtolower ( $tagName )];
            $this->fileObject = new $file ();
            ob_start ();
            ob_implicit_flush ( 0 );
            include $this->fileObject->getTemplateFile ( $tpl );
            $content = ob_get_clean ();
            return $content;
        }

    }

    /**
     * 直接解析某一個標籤
     */
    public function pregReplace($tag, $replace = true) {
        //取出tagName
        $this->replace = $replace;
        $tagName = substr ( $tag, 1, strpos ( $tag, ' ' ) - 1 );
        if (isset ( self::$tags [$tagName] )) {
            $path = self::$tags [$tagName];
            $content = $this->pregReplaceTag ( $tagName, $path, $tag, $replace );
        }
    }
    /**
     * 匹配內容並 編譯返回HTMl內容
     * @param unknown_type $tagName
     * @param unknown_type $path
     * @param unknown_type $content
     * @param unknown_type $replace
     * @return mixed
     */
    private function pregReplaceTag($tagName, $path, $content, $replace = true) {
        list ( $dir, $file ) = explode ( ':', $tagName );
        $file = ucfirst ( $file );
        include_once $path;
        $this->fileObject = new $file ();
        $fileObjcet = $this->fileObject;
        //先對封閉式的標籤進行過濾:<w:blog a="1" b="2" c="3" />這樣的形式
        //      if( !$this->scope && !$fileObjct->getTagStatus()) 楊德升修改
        if (! $this->scope && ! $this->fileObject->getTagStatus ()) {
            $preg = "/([\n\r\t\s]*)" . $this->left . $tagName . "\s+(.*)\/" . $this->right . "([\n\r\t\s]*)/siU";
        } else {
            $preg = "/([\n\r\t\s]*)" . $this->left . $tagName . "\s+(.*)" . $this->right . "(.*)" . $this->left . "\/" . $tagName . $this->right . "([\n\r\t\s]*)/siU";
        }
        if ($replace) {
            $content = preg_replace_callback ( $preg, array ($this, 'parseTagContent' ), $content );
        } else {
            preg_replace_callback ( $preg, array ($this, 'parseTagContent' ), $content );
        }
        return $content;
    }
    /**
     * 編譯並返回內容
     * @param unknown_type $source
     */
    private function parseTagContent($source) {
        //去掉所有空陣列成員
        foreach ( $source as &$value ) {
            $value = preg_replace ( "/^\s*\n/siU", "", $value );
            $value = rtrim ( $value );
        }
        $source = array_merge ( array_filter ( $source ) );
        $tagName = substr ( $source [0], 1, strpos ( $source [0], ' ' ) - 1 );
        //第一個是原始資料無需處理

        $tagInfo ['tagLib'] = sprintf ( '<%s %s/>', $tagName, $source [1] );
        //取出tagName
        if ($this->scope) {
            $source [0] = $this->left . $tagName . " " . $source [1] . "/" . $this->right;
    }
    //第二個是參數成員,需做陣列處理
    $temp = simplexml_load_string ( $source [0] );
    $temp = (( array ) $temp);
    $attrSet = $this->parseTagAttr ( $temp ['@attributes'] );
    //第三個是內容集
    $source = isset ( $source [2] ) ? $source [2] : null;
    $source = str_replace ( '[@]', '&', $source );

    $tagInfo ['tagInfo'] ['name'] = $tagName;
    $tagInfo ['tagInfo'] ['path'] = self::$tags [$tagName];
    //parseTag 檢查資料庫是否有該模組的記錄，如果沒有就添加
    $this->sign [$tagName] = $this->fileObject->parseTag ( $attrSet, $source, $tagInfo );
    if (isset ( $_POST ['customContent'] )) {
        $_POST ['customContent'] = str_replace ( '[@]', '&', $_POST ['customContent'] );
        $this->setContent ( $_POST ['customContent'], $this->sign [$tagName] );
    }
    if ($this->replace) {
        //replaceTag 返回編譯後的內容
        return $this->fileObject->replaceTag ( $attrSet, $source, $tagInfo );
    }
    return $this->sign [$tagName];
    }

    public function getSign($tagName) {
        if (isset ( $this->sign [$tagName] )) {
            return $this->sign [$tagName];
    } else {
        return false;
    }
    }
    private function parseTagAttr($attr) {
        $result = array ();
        foreach ( $attr as $key => $value ) {
            $temp = json_decode ( $value );
            $result [$key] = $temp ? $temp : $value;
    }
    return $result;
    }

    private static function traversalDir($path) {
        $result = array ();
        $file = new RecursiveIteratorIterator ( new RecursiveDirectoryIterator ( ($path) ) );
        $i = 0;
        foreach ( $file as $key => $value ) {
            if (! strpos ( $value->getPathname (), ".svn" ) && strpos ( $value->getFilename (), ".php" )) {
                $path = explode ( DIRECTORY_SEPARATOR, $value->getPath () );
                $temp_key = strtolower ( array_pop ( $path ) );
                list ( $temp_value ) = explode ( '.', $value->getFilename () );
                $result [$temp_key . ":" . strtolower ( $temp_value )] = $value->getPathname ();
    }
    }
    return $result;
    }
    //服務初始化
    public function init() {

    }

    //運行服務，系統服務自動運行
    public function run() {

    }
    }
?>
