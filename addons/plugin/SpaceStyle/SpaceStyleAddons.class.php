<?php
/**
 * Ts插件 - 換膚插件
 * @author 陳偉川 <258396027@qq.com>
 * @version TS3.0
 */
class SpaceStyleAddons extends NormalAddons
{
    protected $version = '2.0';
    protected $author = '智士軟體';
    protected $site = 'http://www.thinksns.com';
    protected $info = '使用者自定義風格官方優化版';
    protected $pluginName = '空間換膚 - 官方優化版';
    protected $tsVersion = "3.0";

    /**
     * 獲得該插件使用了哪些鉤子聚合類，哪些鉤子是需要進行排序的
     * @return void
     */
    public function getHooksInfo()
    {
        $hooks['list'] = array('SpaceStyleHooks');
        return $hooks;
    }

    /**
     * 後臺管理入口
     * @return array 管理相關資料
     */
    public function adminMenu()
    {
        $menu = array('config' => '面板管理');
        return $menu;
    }

    public function start()
    {

    }

    /**
     * 安裝插件
     * @return boolean 是否安裝成功
     */
    public function install()
    {
        // 插入資料表
        $db_prefix = C('DB_PREFIX');
        $sql = "CREATE TABLE IF NOT EXISTS `{$db_prefix}user_change_style` (
            `uid` int(11) unsigned NOT NULL,
            `classname` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
            `background` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
            UNIQUE KEY `uid` (`uid`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;";
        D()->execute($sql);
        return true;
    }

    /**
     * 解除安裝插件
     * @return boolean 是否解除安裝成功
     */
    public function uninstall()
    {
        // 解除安裝資料表
        $db_prefix = C('DB_PREFIX');
        $sql = "DROP TABLE `{$db_prefix}user_change_style`;";
        D()->execute($sql);
        // 解除安裝Xdata資料
        $sql = "DELETE FROM `{$db_prefix}system_data` WHERE `list` = 'addons' AND `key` = 'default_style';";
        D()->execute($sql);
        return true;
    }
}
