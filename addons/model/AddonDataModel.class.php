<?php
/**
 * 插件資料模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
// 載入Key-Value存儲引擎模型 - 資料物件模型
tsload(ADDON_PATH . '/model/XdataModel.class.php');
class AddonDataModel extends XdataModel {

    const PREFIX = "addons:";           // 字首

    protected $list_name = 'addons';    // 存儲的list名稱

    /**
     * 寫入插件參數列表
     * @param string $addonName 插件名稱
     * @param array $data 插件相關資料
     * @return boolean 是否寫入成功
     */
    public function lputAddons($addonName, $data = array()) {
        return parent::lput(self::PREFIX.$addonName, $data);
    }

    /**
     * 讀取插件參數列表
     * @param string $addonName 插件名稱
     * @return array 插件參數列表
     */
    public function lgetAddons($addonName){
        return parent::lget(self::PREFIX.$addonName);
    }

    /**
     * 寫入單個插件資料
     * @param string $key 要存儲的參數list:key
     * @param string $value 要存儲的參數的值
     * @param boolean $replace false為插入新參數，ture為更新已有參數，默認為true
     * @return boolean 是否寫入成功
     */
    public function putAddons($key, $value = '', $replace = false) {
        return parent::put(self::PREFIX.$key, $value, $replace);
    }

    /**
     * 讀取插件資料list:key
     * @param string $key 要獲取的某個參數list:key；如過沒有:則認為，只有list沒有key
     * @return string 相應的list中的key值資料
     */
    public function getAddons($key) {
        return parent::get(self::PREFIX.$key);
    }

    /**
     * 批量讀取插件資料，非必要
     * @param string $listName 插件參數列表list
     * @param array|object $keys 參數鍵key
     * @return array 通過list與key批量獲取的資料
     */
    public function getAllAddons($listName, $keys) {
        return parent::getAll(self::PREFIX.$listName, $keys);
    }
}
