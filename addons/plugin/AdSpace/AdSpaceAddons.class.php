<?php
/**
 * 廣告位插件
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class AdSpaceAddons extends NormalAddons
{
    protected $version = '1.0';
    protected $author = '智士軟體';
    protected $site = 'http://www.thinksns.com';
    protected $info = '廣告位官方版';
    protected $pluginName = '廣告位 - 官方版';
    protected $tsVersion = '3.0';

    /**
     * 獲取該插件使用鉤子
     * @return array 鉤子資訊陣列
     */
    public function getHooksInfo()
    {
        $hooks['list'] = array('AdSpaceHooks');

        return $hooks;
    }

    /**
     * 插件後臺管理入口
     * @return array 管理相關資料
     */
    public function adminMenu()
    {
        $menu = array();
        $menu['config'] = '廣告位管理';
        $menu['addAdSpace'] = '添加廣告位';
        $page = isset($_GET['page']) ? t($_GET['page']) : 'addAdSpace';
        if ($page === 'editAdSpace') {
            unset($menu['addAdSpace']);
            $menu['editAdSpace'] = array('content'=>'編輯廣告位','param'=>array('id'=>intval($_GET['id'])));
        }

        return $menu;
    }

    public function start()
    {

    }

    /**
     * 插件安裝入口
     * @return boolean 是否安裝成功
     */
    public function install()
    {
        // 插入資料表
        $dbPrefix = C('DB_PREFIX');
        $sql = "CREATE TABLE `{$dbPrefix}ad` (
            `ad_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '廣告ID，主鍵',
            `title` varchar(255) DEFAULT NULL COMMENT '廣告標題',
            `place` tinyint(1) NOT NULL DEFAULT '0' COMMENT '廣告位置：0-中部；1-頭部；2-左下；3-右下；4-底部；5-右上；',
            `is_active` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否有效；0-無效；1-有效；',
            `is_closable` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否關閉，目前沒有使用。',
            `ctime` int(11) DEFAULT NULL COMMENT '創建時間',
            `mtime` int(11) DEFAULT NULL COMMENT '更新時間',
            `display_order` smallint(2) NOT NULL DEFAULT '0' COMMENT '排序值',
            `display_type` tinyint(1) unsigned DEFAULT '1' COMMENT '廣告類型：1 - HTML；2 - 程式碼；3 - 輪播',
            `content` text COMMENT '廣告位內容',
            PRIMARY KEY (`ad_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='廣告位表';";
        D()->execute($sql);
        return true;
    }

    /**
     * 插件解除安裝入口
     * @return boolean 是否解除安裝成功
     */
    public function uninstall()
    {
        // 解除安裝資料表
        $dbPrefix = C('DB_PREFIX');
        $sql = "DROP TABLE IF EXISTS `{$dbPrefix}ad`;";
        D()->execute($sql);
        return true;
    }
}
