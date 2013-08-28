<?php
class WeiboSynAddons extends NormalAddons{
    protected $version = '3.0';
    protected $author = 'thinksns';
    protected $site = 'http://www.thinksns.com';
    protected $info = '同步微博動態到新浪，騰訊微博等';
    protected $pluginName = '微博動態同步';
    protected $sqlfile = '暫無';
    protected $tsVersion = "3.0";

    public function getHooksInfo() {
        $hooks['list'] = array('WeiboSynHooks');
        return $hooks;
    }
    public function start() {
    }

    public function install() {
        return true;
    }

    public function uninstall() {
        return true;
    }

    public function adminMenu() {
        return array('login_plugin_publish'=>'同步釋出管理');
    }
}
?>
