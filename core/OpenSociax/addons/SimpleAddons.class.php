<?php
/**
 * 標準插件抽象。該插件具備插件的標準行為。
 * 獲取資訊，以及該插件擁有的管理操作
 * @author sampeng
 *
 */
tsload(CORE_LIB_PATH.'/addons/AbstractAddons.class.php');
abstract class SimpleAddons extends AbstractAddons
{
    private $name;
    private $hooklist= array();
    /**
     * getHooksList
     * 獲取該插件的所有鉤子列表
     * @access public
     * @return void
     */
    public function getHooksList($name)
    {
        $this->name = $name;
        $this->getHooksInfo();
        return $this->hooklist;
    }

    //管理面板
    public function adminMenu(){
        return array();
    }

    //註冊hook位該執行的方法
    public function apply($hook,$method)
    {
        $this->hooklist[$hook][$this->name][] = $method;
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
