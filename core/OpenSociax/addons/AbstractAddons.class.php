<?php
/**
 * 插件機制介面
 * @author SamPeng <penglingjun@zhishisoft.com>
 * @version TS3.0
 */
interface AddonsInterface
{
    /**
     * 告之系統，該插件使用了哪些hooks以及排序等資訊
     * @return array()
     */
    public function getHooksInfo();
    /**
     * 插件初始化時需要的資料資訊。所以就不需要寫類的建構函式
     * Enter description here ...
     */
    public function start();
    /**
     * 該插件的基本資訊
     * 這個方法不需要使用者實現，將在下一層抽象中實現。
     * 使用者需要填寫幾個基本資訊作為該插件的屬性即可
     */
    public function getAddonInfo();

    /**
     * setUp
     * 啟動插件時的介面
     * @access public
     * @return void
     */
    public function install();

    /**
     * setDown
     * 解除安裝插件時的介面;
     * @access public
     * @return void
     */
    public function uninstall();

    /**
     * 顯示不同的管理面板或表單等操作的處理受理介面。默認$page為false.也就是隻顯示第一個管理面板頁面
     */
    public function adminMenu();
}

/**
 * 插件機制抽象類
 * @author SamPeng <penglingjun@zhishisoft.com>
 * @version TS3.0
 */
abstract class AbstractAddons implements AddonsInterface
{
    protected $version;         // 插件版本號
    protected $author;          // 作者
    protected $site;            // 網站
    protected $info;            // 插件描述資訊
    protected $pluginName;      // 插件名字
    protected $path;            // 插件路徑
    protected $url;             // 插件URL
    protected $tVar;	        // 模板變數
    protected $mid;             // 登入使用者ID
    protected $model;           // 插件資料模型物件

    /**
     * 初始化相關資訊
     * @return void
     */
    public function __construct()
    {
        $this->mid = @intval($_SESSION['mid']);
        $this->model = model("AddonData");
        $this->tVar = array();
        $this->start();
    }

    /**
     * 獲取URL地址
     * @return string URL地址
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * 設定URL地址
     * @param string $url URL地址
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * 獲取路徑地址
     * @return string 路徑地址
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param 設定路徑地址
     * @return void
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    abstract function getHooksList($name);

    /**
     * 獲取插件資訊
     * @return array 插件資訊
     */
    public function getAddonInfo()
    {
        $data['version'] = $this->version;
        $data['author'] = $this->author;
        $data['site'] = $this->site;
        $data['info'] = $this->info;
        $data['pluginName'] = $this->pluginName;
        $data['tsVersion'] = $this->tsVersion;
        return $data;
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
     * 獲取指定模板變數的值
     * @param string $name Key值
     * @return mixed 指定模板變數的值
     */
    protected function get($name)
    {
        $data = isset($this->tVar[$name]) ? $this->tVar[$name] : false;
        return $data;
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
        $templateFile = realpath($this->path.DIRECTORY_SEPARATOR."html".DIRECTORY_SEPARATOR.$templateFile.'.html');
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
        if(!$this->get('jumpUrl')) $this->assign("jumpUrl",$_SERVER["HTTP_REFERER"]);

        echo $this->fetch(THEME_PATH.'/success.html');
}else{
    //發生錯誤時候默認停留3秒
    $this->assign('waitSecond',"5");
    // 默認發生錯誤的話自動返回上頁
    if(!$this->get('jumpUrl'))  $this->assign('jumpUrl',"javascript:history.back(-1);");

    echo $this->fetch(THEME_PATH.'/success.html');
}
if(C('LOG_RECORD')) Log::save();
// 中止執行  避免出錯後繼續執行
exit ;
}
}
