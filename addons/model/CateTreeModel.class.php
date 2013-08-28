<?php
/**
 * 無限級分類控制模型 - 業務邏輯模型
 * @example
 * 說明：利用此分類模型，需要指定資料表，相關模型欄位
 * 必須屬性：
 * table：表名
 * id：主鍵（欄位名）
 * name：分類名稱（欄位名）
 * pid：上級節點ID（欄位名）
 * sort：排序（欄位名）
 * 示例：
 * $ctree = model('CateTree', $table);      表名必須指定
 * $ctree = $ctree->setField($param);       不指定欄位名，將使用默認欄位
 * $data = $ctree->getTree();               獲取樹形結構
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class CateTreeModel {

    private static $tree = array();                 // 樹
    private static $hash = array();                 // 樹的HASH表
    public $cacheKey = '';                          // 快取Key
    private $cacheKeyPrefix  = 'CateTree_';         // 快取字首
    private $_table = '';                           // 默認表
    private $defaultField = array(
        'id' => 'id',       // 主鍵欄位名
        'pid' => 'pid',     // 上級節點ID欄位名
        'name' => 'name',   // 分類名稱欄位名
        'sort' => 'sort',   // 排序欄位名
    );
    private $tempHashData = array();                // 臨時Hash資料

    /**
     * 初始化無限極樹形模型物件
     * @param string $table 資源表名
     * @return void
     */
    public function __construct($table = '') {
        if(empty($table)) {
            $this->error = L('PUBLIC_CATEGORYFORM_UNSIGN');         // 分類模型表未指定
            return false;
        }
        $this->_table = C('DB_PREFIX').$table;
        $this->cacheKey = $this->cacheKeyPrefix.$table;
    }

    /**
     * 設定樹形資料快取Key
     * @param string $key 使用者自定義Key值
     * @return object 無限樹形模型物件
     */
    public function setCacheKey($key) {
        $this->cacheKey = $this->cacheKeyPrefix.$key;
        return $this;
    }

    /**
     * 設定欄位值
     * @param array $field 使用者自定義欄位值
     * @return object 無限樹形模型物件
     */
    public function setField($field = array()) {
        if(empty($field)) {
            return $this;
        }
        foreach($field as $k => $v) {
            $this->defaultField[$k] = $v;
        }

        return $this;
    }

    /**
     * 獲取一個樹形結構資料
     * @param integer $rootId 根目錄ID
     * @return array 樹形結構
     */
    public function getTree($rootId = '0') {
        // 減少快取讀取次數
        if(isset(self::$tree[$this->cacheKey])) {
            return self::$tree[$this->cacheKey][$rootId];
        }
        if($tree = model('Cache')->get($this->cacheKey)) {
            // 快取命中
            self::$tree[$this->cacheKey] = $tree;
            return $tree[$rootId];
        }
        // 重建樹及hash快取
        $this->createTree();
        return self::$tree[$this->cacheKey][$rootId];
    }

    /**
     * 獲取所有分類的以主鍵為Key的Hash陣列
     * @return arrat 分類的Hash陣列
     */
    public function getAllHash() {
        $hash = model('Cache')->get($this->cacheKey.'_hash');
        if(!empty($hash)) {
            // 快取命中
            self::$hash[$this->cacheKey] = $hash;
            return $hash;
        }
        // 重建樹及Hash快取
        $this->createTree();
        return self::$hash[$this->cacheKey];
    }

    /**
     * 獲取指定分類ID下的所有子集ID
     * @param integer $id 分類ID
     * @return array 指定分類ID下的所有子集ID
     */
    public function getChildHash($id) {
        $data = $this->getTree($id);
        $this->_getChildHash($data);
        $r = $this->tempHashData;
        $this->tempHashData = array();
        return $r;
    }

    /**
     * 遞迴方法獲取樹形結構下的所有子集ID
     * @param array $data 子集資料
     * @return void
     */
    private function _getChildHash($data) {
        $this->tempHashData[] = $data[$this->defaultField['id']];
        if(!empty($data['_child'])) {
            foreach($data['_child'] as $v) {
                $this->_getChildHash($v);
            }
        }
    }

    /**
     * 移動一個樹形節點，即更改一個節點的父節點，移動規則：父節點不能移動到自己的子樹下面
     * @param integer $id 節點ID
     * @param integer $newPid 新的父節點ID
     * @param integer $newSort 新的排序值
     * @param array $data 表示其他需要儲存的欄位，由實現此model的類完成
     * @return boolean 是否移動成功
     */
    public function moveTree($id, $newPid, $newSort='255', $data = array()) {
        // 參數驗證
        if(!$id || (!$newPid && $newPid!=0)){
            $this->error  = L('PUBLIC_DATA_MOVE_ERROR');            // 分類樹移動的參數出錯
            return false;
        }
        // 更新資料庫資訊
        $map[$this->defaultField['id']] = $id;
        $data[$this->defaultField['pid']] = $newPid;
        $data[$this->defaultField['sort']] = $newSort;

        if(D('')->table($this->_table)->where($map)->save($data)){
            // TODO:可優化成更新快取而不是清除快取
            $this->cleanCache();
        }

        return true;
    }

    /**
     * 批量移動樹的節點，移動規則：父節點不能移動到自己的子樹下面
     * ids，newPids的Key值必須對應
     * @param array $ids 節點ID陣列
     * @param array $newPids 新的父節點ID陣列
     * @param array $newSorts 新的排序值陣列
     * @return boolean 是否移動成功
     */
    public function moveTreeByArr($ids,$newPids,$newSorts = array()) {
        foreach($ids as $k => $v) {
            $sort = isset($newSorts[$k]) && !empty($newSorts[$k]) ? $newSorts[$k] : 255;
            $this->moveTree($v, $newPids[$k], $sort, false);
        }
        $this->cleanCache();

        return true;
    }

    /**
     * 清除樹形結構的快取，不允許刪除單個子樹，由於一棵樹是快取在一個快取裡面
     * @return boolean 是否清除成功
     */
    public function cleanCache() {
        unset(self::$tree[$this->cacheKey]);
        unset(self::$tree[$this->cacheKey.'_hash']);
        self::$tree = array();
        model('Cache')->rm($this->cacheKey);
        model('Cache')->rm($this->cacheKey.'_hash');
        return true;
}

/*** 私有方法 ***/
/**
 * 雙陣列生成整棵樹，快取操作
 * @return void
 */
private function createTree() {
    // 從資料庫取樹的資料
    $data = $this->_getTreeData();
    if(empty($data)) {
        return array();
}
$tree = array();        // 臨時樹
$child = array();       // 所有節點的子節點
$hash = array();        // Hash快取陣列
foreach($data as $dv) {
    $hash[$dv[$this->defaultField['id']]] = $dv;
    $tree[$dv[$this->defaultField['id']]] = $dv;
    !isset($child[$dv[$this->defaultField['id']]]) && $child[$dv[$this->defaultField['id']]] = array();
    $tree[$dv[$this->defaultField['id']]]['_child'] = &$child[$dv[$this->defaultField['id']]];
    $child[$dv[$this->defaultField['pid']]][] = &$tree[$dv[$this->defaultField['id']]];
}
//整個樹，其根節點ID為0
$tree[0]['_child'] = $child[0];

self::$tree[$this->cacheKey] = $tree;
self::$hash[$this->cacheKey] = $hash;
// 生成快取
model('Cache')->set($this->cacheKey,$tree);
model('Cache')->set($this->cacheKey.'_hash',$hash);
}

/**
 * 獲取樹形資料，從資料庫中取出
 * @return array 所有分類的資料
 */
private function _getTreeData() {
    // 下面是從資料庫讀取的 分類很少會有大量資料
    $data = D('')->table($this->_table)->order($this->defaultField['sort'].' ASC')->findAll();
    return $data;
}

}
