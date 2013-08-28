<?php
/**
 * 鉤子抽象類
 * @author SamPeng <penglingjun@zhishisoft.com>
 * @version TS3.0
 */
abstract class Hooks
{
    protected $mid;                // 登入使用者ID
    protected $model;              // 插件資料模型物件
    protected $tVar;               // 模板變數
    protected $path;               // 插件路徑
    protected $htmlPath;           // 插件HTML路徑

    /**
     * 初始化相關資訊
     */
    public function __construct()
    {
        $this->mid = $_SESSION['mid'];
        $this->model = model('AddonData');
        $this->tVar = array();
    }

    /**
     * 設定該插件的路徑，不能進行重寫
     * @param string $path 路徑地址
     * @param boolean $html 是否為HTML路徑，默認為false
     * @return void
     */
    public final function setPath($path, $html = false)
    {
        if($html) {
            $this->htmlPath = $path;
        } else {
            $this->path = $path;
        }
    }

    /**
     * 將資料渲染到HTML頁面，設定模板變數的值
     * @param string $name Key值
     * @param string $value Value值
     * @return void
     */
    protected function assign($name, $value = '')
    {
        $this->tVar[$name] = $value;
    }

    /**
     * 渲染HTML頁面
     * @param string $templateFile 模板檔案路徑
     * @param string $charset 字符集，默認為UTF8
     * @param string $contentType 內容類型，默認為text/html
     * @return string HTML頁面資料
     */
    public function fetch($templateFile = '', $charset = 'utf-8', $contentType = 'text/html')
    {
        if(!is_file($templateFile)) {
            $templateFile = realpath($this->path.DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR.$templateFile.'.html');
        }

        // 獲取當前Js語言包
        $this->langJsList = setLangJavsScript();
        $this->assign('langJsList', $this->langJsList);

        return fetch($templateFile, $this->tVar, $charset, $contentType, false);
    }

    /**
     * 顯示指定HTML頁面
     * @param string $templateFile 模板檔案路徑
     * @param string $charset 字符集，默認為UTF8
     * @param string $contentType 內容類型，默認為text/html
     * @return string HTML頁面資料
     */
    public function display($templateFile = '', $charset = 'utf-8', $contentType = 'text/html')
    {
        echo $this->fetch($templateFile, $charset, $contentType);
    }

    /**
     * 錯誤提示方法
     * @param string $message 提示資訊
     * @return void
     */
    protected function error($message)
    {
        $message = $message ? $message : '操作失敗';
        $this->_dispatch_jump($message, 0);
    }

    /**
     * 成功提示方法
     * @param string $message 提示資訊
     * @return void
     */
    protected function success($message)
    {
        $message = $message ? $message : '操作成功';
        $this->_dispatch_jump($message, 1);
    }

    /**
     * 跳轉操作
     * @param string $message 提示資訊
     * @param integer $status 狀態。1表示成功，0表示失敗
     * @return void
     */
    private function _dispatch_jump($message, $status = 1)
    {
        // 跳轉時不展示廣告
        unset($GLOBALS['ts']['ad']);

        // 提示標題
        $this->assign('msgTitle',$status? L('_OPERATION_SUCCESS_') : L('_OPERATION_FAIL_'));
        $this->assign('status',$status);   // 狀態
        $this->assign('message',$message);// 提示資訊
        //保證輸出不受靜態快取影響
        C('HTML_CACHE_ON',false);
        if($status) { //發送成功資訊
            // 成功操作後默認停留1秒
            $this->assign('waitSecond',"1");
            // 默認操作成功自動返回操作前頁面
            //if(!$this->get('jumpUrl'))
            $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);

            echo $this->fetch(THEME_PATH.'/success.html');
    }else{
        //發生錯誤時候默認停留3秒
        $this->assign('waitSecond',"5");
        // 默認發生錯誤的話自動返回上頁
        //if(!$this->get('jumpUrl'))
        $this->assign('jumpUrl',"javascript:history.back(-1);");

        echo $this->fetch(THEME_PATH.'/success.html');
    }
    if(C('LOG_RECORD')) Log::save();
    // 中止執行  避免出錯後繼續執行
    exit ;
    }

    /**
     * 獲取插件目錄下的Model模型檔案
     * @param string $name Model名稱
     * @param string $class 類名字尾，默認為Model
     * @return object 返回一個模型物件
     */
    protected function model($name, $class = "Model")
    {
        $className = ucfirst($name).$class;
        tsload($this->path.DIRECTORY_SEPARATOR.$className.'.class.php');
        return new $className();
    }
    }
