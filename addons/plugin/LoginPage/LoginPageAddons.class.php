<?php
class LoginPageAddons extends NormalAddons{
    protected $version = "3.0";
    protected $author  = "智士軟體";
    protected $site    = "http://www.thinksns.com";
    protected $info    = "一個開放式的登入前首頁插件，可以在插件中配置首頁資訊，參考此插件可以實現各種類型的首頁。";
    protected $pluginName = "經典登入頁插件";
    protected $tsVersion = '3.0';

    public function getHooksInfo()
    {
        $hooks['list']=array('LoginPageHooks');
        return $hooks;
    }

    /**
     * 該插件的管理介面的處理邏輯。
     * 如果return false,則該插件沒有管理介面。
     * 這個介面的主要作用是，該插件在管理介面時的初始化處理
     * @param string $page
     */
    public function adminMenu()
    {
        return array(
            'login_page_logo'=>"登入頁logo配置",
            'login_page_banner'=>'Banner圖片配置',
            'login_page_feed'=>'動態模組配置',
            'login_page_user'=>'使用者模組配置',
        );
    }

    public function start()
    {
        return true;
    }

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }
}
