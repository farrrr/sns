<?php
/**
 * 通常插件插件類
 * @author SamPeng <penglingjun@zhishisoft.com>
 * @version TS3.0
 */
tsload(CORE_LIB_PATH.'/addons/AbstractAddons.class.php');
abstract class NormalAddons extends AbstractAddons
{
    /**
     * 獲取該插件所需的鉤子列表
     * @param string $name 插件名稱
     * @return array 插件所需的鉤子列表
     */
    public function getHooksList($name)
    {
        $hooks = $this->getHooksInfo();
        $hooksBase = get_class_methods('Hooks');
        $list = array();
        // 生成插件列表
        foreach($hooks['list'] as $value) {
            $dirName = ADDON_PATH.DIRECTORY_SEPARATOR.'plugins';
            tsload($this->path.DIRECTORY_SEPARATOR.'hooks'.DIRECTORY_SEPARATOR.$value.'.class.php');
            $hook = array_diff(get_class_methods($value), $hooksBase);
            foreach($hook as $v) {
                $list[$v][$name][] = $value;
            }
        }
        // 排序
        foreach($hooks['sort'] as $key => $value) {
            if(isset($list[$name][$key])) {
                $temp = array();
                foreach($value as $v) {
                    $temp[] = $hooks['list'][$v];
                }
                $list[$name][$key] = $temp;
            }
        }

        return $list;
    }
}
