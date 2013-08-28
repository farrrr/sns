<?php
/**
 * Key-Value存儲引擎模型 - 資料物件模型
 * Key-value存儲引擎，用MySQL模擬memcache等key-value資料庫寫法
 * 以後可以切換到其它成熟資料庫或amazon雲端計算平臺
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class XconfigModel extends Model {

    protected $tableName = 'system_config';
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
    public function pageKey_lput($listName = '', $listData = array()) {
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

        $cache_id = '_system_config_lget_'.$listName;

        F($cache_id, null); //刪除快取，不要使用主動創建發方式、因為listData不一定是listName的全部值

        return $result;
    }

    /**
     * 讀取資料list:key
     * @param string $key 要獲取的某個參數list:key；如果沒有:則認為，只有list沒有key
     * @return string 相應的list中的key值資料
     */
    public function pagekey_get($key) {
        $key = $this->_strip_key($key);

        $keys = explode(':', $key);
        static $_res = array();
        if(isset($_res[$key])) {
            return $_res[$key];
        }
        $list = $this->pagekey_lget($keys[0]);
        return $list ? $list[$keys[1]] : '';
    }

    /**
     * 讀取參數列表
     * @param string $list_name 參數列表list
     * @param boolean $nostatic 是否不使用靜態快取，默認為false
     * @return array 參數列表
     */
    public function pagekey_lget($list_name = '', $nostatic = false) {

        $list_name = $this->_strip_key($list_name);

        static $_res = array();
        if(isset($_res[$list_name]) && !$nostatic) {
            return $_res[$list_name];
        }

        $cache_id = '_system_config_lget_'.$list_name;

        if(($data = F($cache_id)) === false || $data == '' || empty($data)) {
            $data = array();
            $map['`list`'] = $list_name;

            $result = D('system_config')->order('id ASC')->where($map)->findAll();
            if($result) {
                foreach($result as $v) {
                    $data[$v['key']] = unserialize($v['value']);
                }
            }
            F($cache_id, $data);
        }
        //dump($data);exit;
        $_res[$list_name] = $data;
        return $_res[$list_name];
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
