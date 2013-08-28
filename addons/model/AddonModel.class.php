<?php
/**
 * 插件模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AddonModel extends Model {

    protected $tableName = 'addons';
    protected $fields = array(0=>'addonId',1=>'name',2=>'pluginName',3=>'author',4=>'info',5=>'version',6=>'status',7=>'lastupdate',8=>'site',9=>'tsVersion');

    private $valid = array();               // 已安裝插件
    private $invalid = array();             // 待安裝插件
    private $fileAddons = array();          // 插件物件

    /**
     * 獲取所有插件列表
     * @return array 所有插件列表
     */
    public function getAddonAllList() {
        $this->_getFileAddons();
        // 獲取資料庫中的所有插件
        $databaseAddons = $this->findAll();
        $this->_validAddons($databaseAddons);

        $this->_invalidAddons();
        $result['valid']['data']   = $this->valid;
        $result['valid']['name']   = "已安裝插件";
        $result['invalid']['data'] = $this->invalid;
        $result['invalid']['name'] = "待安裝插件";
        return $result;
    }

    /**
     * 重置所有已安裝插件列表快取
     * @return array 最新的插件列表
     */
    public function resetAddonCache() {
        if(empty($this->fileAddons)) {
            $this->_getFileAddons();
        }
        $addonList = $this->getAddonsValid();

        $addonCache = array();
        foreach($addonList as $key => $value) {
            if(isset($this->fileAddons[$value['name']])) {
                $addonCache = $this->_createAddonsCacheData($value['name'],$addonCache);
            }
        }
        $res = S('system_addons_list',$addonCache);

        return $addonCache;
    }

    /**
     * 獲取已安裝的插件列表
     * @return array 已安裝的插件列表
     */
    public function getAddonsValid() {
        $map['status'] = '1';
        return $this->where($map)->findAll();
    }

    /**
     * 獲取未安裝的插件列表
     * @return array 未安裝的插件列表
     */
    public function getAddonsInvalid() {
        // TODO:待完成
    }

    /**
     * 通過插件ID停止插件
     * @param integer $id 插件ID
     * @return boolean 插件是否停止
     */
    public function stopAddonsById($id) {
        if(empty($id)) {
            return false;
        }
        // 將資料庫中標示該插件停止
        $result = $this->_stopAddons('addonId', intval($id));
        return $result ? true : false;
    }

    /**
     * 通過插件名稱停止插件
     * @param string $name 插件名稱
     * @return boolean 插件是否停止
     */
    public function stopAddonsByName($name) {
        if(empty($name)) {
            return false;
        }
        // 將資料庫中標示該插件停止
        $result = $this->_stopAddons('name', $name);
        return $result ? true : false;
    }

    /**
     * 通過插件ID獲取插件物件
     * @param integer $id 插件ID
     * @return object 指定插件物件
     */
    public function getAddonObj($id) {
        $data = $this->getAddon($id);
        if($data) {
            $this->_getFileAddons();
            return $this->fileAddons[$data['name']];
        }

        return false;
    }

    /**
     * 停止插件
     * @param string $field 查詢插件的Key值
     * @param string $value 查詢插件的Value值
     * @return boolean 插件是否停止
     */
    private function _stopAddons($field, $value) {
        // 將資料庫中標示該插件停止
        $map[$field] = $value;
        if($filed != 'name') {
            $addon = $this->where($map)->find();
            $name = $addon['name'];
        } else {
            $name = $value;
        }
        $save['status'] = '0';
        $result = $this->where($map)->save($save);
        if($result) {
            $addonCacheList = $this->resetAddonCache();
            S('system_addons_list', $addonCacheList);
        }

        return $result ? true : false;
    }

    /**
     * 通過插件名稱啟動插件
     * @param string $name 插件名稱
     * @return boolean 插件是否啟動
     */
    public function startAddons($name) {
        // 先檢視該插件是否安裝
        $map['name'] = t($name);
        $addon = $this->where($map)->find();
        // 裝載快取列表
        $this->_getFileAddons();
        if(!isset($this->fileAddons[$name])) {
            return false;
            // throw new ThinkException("插件".$name."的目錄不存在");
        }
        // 如果安裝後啟用的，設定插件啟動
        if($addon && $addon['status'] == 0) {
            $save['status'] = '1';
            $result = $this->where($map)->save($save) ? true : false;
        } else if($addon && $addon['status'] == 1) {
            $result = false;
        } else {
            $addonObject = $this->fileAddons[$name];
            $add = $addonObject->getAddonInfo();
            $add['name'] = $name;
            $add['status'] = '1';
            if($this->add($add) && $addonObject->install()) {
                $result = true;
            } else {
                $result = false;
            }
        }

        if($result) {
            $addonCacheList = $this->resetAddonCache();
            S('system_addons_list', $addonCacheList);
        }

        return $result;
    }

    /**
     * 通過插件名稱解除安裝插件
     * @param string $name 插件名稱
     * @return boolean 插件是否解除安裝成功
     */
    public function uninstallAddons($name) {
        if(empty($name)) {
            return false;
        }
        $this->_getFileAddons();
        if(!isset($this->fileAddons[$name])) {
            return false;
            // throw new ThinkException("插件".$name."不存在");
        }
        $addonObject = $this->fileAddons[$name];
        $addonObject->uninstall();

        $map['name'] = $name;
        $result = $this->where($map)->delete() ? true : false;
        if($result) {
            $addonCacheList = $this->resetAddonCache();
            S('system_addons_list', $addonCacheList);
        }

        return $result;
    }

    /**
     * 獲取指定插件資訊
     * @param integer $id 插件ID
     * @param integer $status 插件狀態
     * @return array 指定插件資訊
     */
    public function getAddon($id, $status = 1) {
        $map['addonId'] = intval($id);
        $status = intval($status);
        $map['status'] = "$status";
        return $this->where($map)->find();
    }

    /**
     * 獲取所有插件管理面板所需資料
     * @return array 所有插件管理面板所需資料
     */
    public function getAddonsAdmin() {
        $valid = $this->getAddonsValid();
        $this->_getFileAddons();
        if(empty($valid)) {
            return array();
        }
        $data = array();
        foreach($valid as $value) {
            $obj = isset($this->fileAddons[$value['name']]) ? $this->fileAddons[$value['name']] : null;
            if($obj && $obj->adminMenu()) {
                $data[] = array($value['pluginName'], $value['addonId']);
            }
        }

        return $data;
    }

    /**
     * 創建插件快取資料
     * @param string $name 插件名稱
     * @param array $addonList 插件列表
     * @return array 返回插件列表
     */
    private function _createAddonsCacheData($name, $addonList) {
        $list = $this->fileAddons[$name]->getHooksList($name);
        // 合併鉤子快取列表
        if(empty($addonList)) {
            $addonList = $list;
        } else {
            $result = array();
            $addonListKey = array_keys($addonList);
            $listKey = array_keys($list);
            $addonList = array_merge_recursive($addonList,$list);
        }

        return $addonList;
    }

    /**
     * 驗證已安裝的插件
     * @param array $databaseAddons 插件列表資料
     * @return void
     */
    private function _validAddons($databaseAddons) {
        if(empty($databaseAddons)) {
            return;
        }
        foreach($databaseAddons as $value) {
            if($value['status'] == 1) {
                $this->valid[] = $value;
            } else {
                $this->invalid[] = $value;
            }
            if(isset($this->fileAddons[$value['name']])) {
                unset($this->fileAddons[$value['name']]);
            }
        }
    }

    /**
     * 驗證未安裝的插件
     * @return void
     */
    private function _invalidAddons() {
        // 獲取未啟用的插件
        foreach($this->fileAddons as $key => $value) {
            $data = $value->getAddonInfo();
            $data['status'] = 0;
            $data['name'] = $key;
            $this->invalid[] = $data;
}
}

/**
 * 設定所有插件物件
 * @return void
 */
private function _getFileAddons() {
    if(!empty($this->fileAddons)) {
        return $this->fileAddons;
}
// 獲取資料夾下面的所有插件
$dirName = ADDON_PATH.'/plugin/';
$dir = dir($dirName);
$fileAddons = array();
while(false !== $entry = $dir->read()) {
    if($entry == '.' || $entry == '..' || $entry == ".svn") {
        continue;
}
$path = $dirName.'/'.$entry;
$addonsFile = $path.'/'.$entry.'Addons.class.php';
tsload(CORE_PATH.'/OpenSociax/addons/AbstractAddons.class.php');
tsload(CORE_PATH.'/OpenSociax/addons/NormalAddons.class.php');
tsload(CORE_PATH.'/OpenSociax/addons/SimpleAddons.class.php');
if(file_exists($addonsFile)) {
    tsload($addonsFile);
    $class = $entry . 'Addons';
    $fileAddons[$entry] = new $class();
    $fileAddons[$entry]->setPath($path);
}
}

$this->fileAddons = $fileAddons;
}

/**
 * 獲取後臺所有插件URL
 * @return array 後臺所有插件URL
 */
public function getAddonsAdminUrl() {

    $addons = $this->getAddonsAdmin();
    $r = array();
    foreach($addons as $value) {
        $r[$value[0]] = U('admin/Addons/admin', array('pluginid'=>$value[1]));
}

return $r;
}
}
