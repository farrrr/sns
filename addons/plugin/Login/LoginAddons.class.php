<?php
class LoginAddons extends NormalAddons{
    protected $version = "3.0";
    protected $author  = "智士軟體";
    protected $site    = "http://www.thinksns.com";
    protected $info    = "支援新浪微博、騰訊微博、QQ、豆瓣、人人、百度、淘寶帳號登入";
    protected $pluginName = "第三方登入插件V3";
    protected $tsVersion = '3.0';

    public function getHooksInfo(){
        $hooks['list']=array('LoginHooks');
        return $hooks;
    }

    public function adminMenu(){
        return array('login_plugin_login'=>"同步登入管理");
    }

    public function start(){
        return true;
    }

    public function install(){
        return true;
    }

    public function uninstall(){
        return true;
    }
}
