<?php
/**
 * 投稿插件
 * @author Stream
 *
 */
class ContributeAddons extends NormalAddons{
    protected $version = '3.0';
    protected $author = 'thinksns';
    protected $site = 'http://www.thinksns.com';
    protected $info = '向頻道管理員投稿';
    protected $pluginName = '微博投稿';
    protected $sqlfile = '暫無';
    protected $tsVersion = "3.0";
    public function getHooksInfo() {
        $hooks['list'] = array('ContributeHooks');
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
    }


}
?>
