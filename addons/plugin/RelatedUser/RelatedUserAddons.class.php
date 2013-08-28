<?php
class RelatedUserAddons extends NormalAddons{
    protected $version = "3.0";
    protected $author  = "智士軟體";
    protected $site    = "http://www.thinksns.com";
    protected $info    = '根據當前使用者推薦可能感興趣的人';
    protected $pluginName = '可能感興趣的人';
    protected $sqlfile = '暫無';
    protected $tsVersion = "3.0";

    public function getHooksInfo() {
        $hooks['list'] = array('RelatedUserHooks');
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
