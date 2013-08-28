<?php
/**
 * Key-Value存儲引擎模型 - 資料物件模型
 * Key-value存儲引擎，用MySQL模擬memcache等key-value資料庫寫法
 * 以後可以切換到其它成熟資料庫或amazon雲端計算平臺
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class XdataModel extends Model {

    protected $tableName = 'system_data';
    protected $fields = array(0=>'id',1=>'uid',2=>'list',3=>'key',4=>'value',5=>'mtime','_autoinc'=>true,'_pk'=>'id');

    protected $list_name = 'global';            // 默認列表名

    //鍵值白名單，主要用於獲取和設定配置檔案某個
    protected $whiteList = array('site'=>'');

    /**
     * 寫入參數列表
     * @param string $listName 參數列表list
     * @param array $listData 存入的資料，形式為key=>value
     * @return boolean 是否寫入成功
     */
    public function lput($listName = '', $listData = array()) {
        // 初始化list_name
        $listName = $this->_strip_key($listName);
        $result = false;
        // 格式化資料
        if(is_array($listData)) {
            $insert_sql .=  "REPLACE INTO __TABLE__ (`list`,`key`,`value`,`mtime`) VALUES ";
            foreach($listData as $key => $data) {
                $insert_sql .= " ('$listName','$key','".serialize($data)."','".date('Y-m-d H:i:s')."') ,";
            }
            $insert_sql = rtrim($insert_sql,',');
            // 插入資料列表
            $result = $this->execute($insert_sql);
        }

        $cache_id = '_xdata_lget_'.$listName;

        F($cache_id, null); //刪除快取，不要使用主動創建發方式、因為listData不一定是listName的全部值

        return $result;
    }

    /**
     * 讀取參數列表
     * @param string $list_name 參數列表list
     * @param boolean $nostatic 是否不使用靜態快取，默認為false
     * @return array 參數列表
     */
    public function lget($list_name = '', $nostatic = false) {
        $list_name = $this->_strip_key($list_name);

        static $_res = array();
        if(isset($_res[$list_name]) && !$nostatic) {
            return $_res[$list_name];
        }

        $cache_id = '_xdata_lget_'.$list_name;

        if(($data = F($cache_id)) === false || $data =='') {
            $data = array();
            $map['`list`'] = $list_name;
            $result = $this->order('id ASC')->where($map)->findAll();

            if($result) {
                foreach($result as $v) {
                    $data[$v['key']] = unserialize($v['value']);
                }
            }
            F($cache_id, $data);
        }

        $_res[$list_name] = $data;
        return $_res[$list_name];
    }

    /**
     * 寫入單個資料
     * @param string $key 要存儲的參數list:key
     * @param string $value 要存儲的參數的值
     * @param boolean $replace false為插入新參數，ture為更新已有參數，默認為true
     * @return boolean 是否寫入成功
     */
    public function put($key, $value = '', $replace = true) {
        $key = $this->_strip_key($key);
        $keys = explode(':', $key);
        $data = serialize($value);

        if($replace) {
            $insert_sql = "REPLACE INTO __TABLE__ ";
        } else {
            $insert_sql = "INSERT INTO __TABLE__ ";
        }

        $insert_sql .= "(`list`,`key`,`value`,`mtime`) VALUES ('$keys[0]','$keys[1]','$data','".date('Y-m-d H:i:s')."')";
        $result = $this->execute($insert_sql);

        $cache_id = '_xdata_lget_'.$keys[0];

        F($cache_id, null);

        return $result;
    }

    /**
     * 讀取資料list:key
     * @param string $key 要獲取的某個參數list:key；如果沒有:則認為，只有list沒有key
     * @return string 相應的list中的key值資料
     */
    public function get($key) {
        $key = $this->_strip_key($key);
        $keys = explode(':', $key);
        static $_res = array();
        if(isset($_res[$key])) {
            return $_res[$key];
        }

        $list = $this->lget($keys[0]);

        return $list ? $list[$keys[1]] : '';
    }

    /**
     * 傳入的key參數為直接要獲取的的key值
     * @param string $key 在配置中的key值 [必須]
     * @param string $systemKey 在system_data表中的key值 [必須]
     * @param string $systemList 在system_data表中的list值 默認為Config
     * @return string 獲取key對應的值
     */
    public function getConfig($key, $systemKey, $systemList = 'admin_Config') {
        if(empty($systemKey)) {
            return false;
        }
        $_key = $systemList.':'.$systemKey;
        $data = $this->get($_key);
        return $data[$key];
    }

    /**
     * 存儲單個資料，將原來的save修改為saveKey
     * @param string $key 要存儲的參數list:key
     * @param string $value 要存儲的參數的值
     * @return boolean 是否存儲成功
     */
    public function saveKey($key, $value = '') {
        $result = $this->put($key, $value, true);
        return $result;
    }

    /**
     * 批量讀取資料，非必要
     * @param string $listName 參數列表list
     * @param array|object $keys 參數鍵key
     * @return array 通過list與key批量獲取的資料
     */
    public function getAll($listName, $keys) {
        // 用於獲取list下所有資料
        if($key) {
            $keysArray = $this->_parse_keys($keys);
            $map['`key`'] = array('IN', $keysArray);
}

$map['`list`'] = $listName;
$result = $this->where($map)->findAll();

if(!$result) {
    return false;
} else {
    foreach($result as $v) {
        $datas[$v['list']][$v['key']] = unserialize($v['value']);
}
}

return $datas;
}

/**
 * 解析過濾輸入
 * @param string|object|array $input 輸入的資料
 * @return array 解析過濾後的輸入資料
 */
protected function _parse_keys($input = '') {
    $output =   '';
    if(is_array($input) || is_object($input)) {
        foreach($input as $v) {
            $output[] = $this->_strip_key($v);
}
} else if(is_string($input)) {
    $output[] = $this->_strip_key($input);
} else {
    // 異常處理
}

return $output;
}

/**
 * 過濾key值
 * @param string $key 只允許格式，數字字母下劃線，list:key不允許出現html程式碼和這些符號 ' " & * % ^ $ ? ->
 * @return string 過濾後的key值
 */
protected function _strip_key($key = '') {
    if($key == '') {
        return $this->list_name;
} else {
    return preg_replace('/([^0-9a-zA-Z\_\:])/', '', $key);
}
}
}
