<?php
/**
 * Pid型的樹形結構的分類模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class CategoryTreeModel extends Model
{
    private $_app;              // 分類對應的應用名稱
    private $_talbe;            // 分類對應的資料表名稱
    private $_model;            // 分類對應的模型操作物件
    private $_message;          // 提示資訊

    /**
     * 設定分類應用名稱
     * @param string $app 分類應用名稱
     * @return void
     */
    public function setApp($app)
    {
        $this->_app = strtolower($app);
        return $this;
    }

    /**
     * 獲取分類應用名稱
     * @return string 分類應用名稱
     */
    public function getApp()
    {
        return $this->_app;
    }

    /**
     * 設定分類表名
     * @param string $table 分類資料表名
     * @return void
     */
    public function setTable($table)
    {
        $this->_talbe = strtolower($table);
        $this->_model = D($this->_talbe);
        return $this;
    }

    /**
     * 獲取分類表名
     * @return string 分類表名
     */
    public function getTable()
    {
        return $this->_talbe;
    }

    /**
     * 設定提示資訊
     * @return void
     */
    public function setMessage($msg)
    {
        $this->_message = $msg;
    }

    /**
     * 獲取提示資訊
     * @return string 提示資訊
     */
    public function getMessage()
    {
        return $this->_message;
    }

    /**
     * 當指定pid時，查詢該父分類的所有子分類；否則查詢所有分類
     * @param integer $pid 父分類ID
     * @param string $field 顯示的欄位，默認為空
     * @return array 相應的分類列表
     */
    public function getCategoryList($pid = -1, $field = '')
    {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        empty($field) && $field = $this->_talbe.'_id, title, pid';
        $data = $this->_model->field($field)->where($map)->order('`sort` ASC')->findAll();

        return $data;
    }

    /**
     * 獲取指定分類ID下的分類資訊
     * @param integer $id 分類ID
     * @return array 指定分類ID下的分類資訊
     */
    public function getCategoryById($id)
    {
        $result = array();
        if(!empty($id)) {
            $map[$this->_talbe.'_id'] = $id;
            $result = $this->_model->where($map)->find();
            $result['id'] = $result[$this->_talbe.'_id'];
            unset($result[$this->_talbe.'_id']);
        }

        return $result;
    }

    /**
     * 獲取指定父分類的樹形結構
     * @return integer $pid 父分類ID
     * @return array 指定父分類的樹形結構
     */
    public function getNetworkList($pid = 0)
    {
        // 子分類樹形結構
        if($pid != 0) {
            return $this->_MakeTree($pid);
        }
        // 全部分類樹形結構
        $list = S('category_cache_'.$this->_talbe);
        if(!$list) {
            set_time_limit(0);
            $list = $this->_MakeTree($pid);
            S('category_cache_'.$this->_talbe, $list);
        }

        return $list;
    }

    /**
     * 遞迴形成樹形結構
     * @param integer $pid 父分類ID
     * @param integer $level 等級
     * @return array 樹形結構
     */
    private function _MakeTree($pid, $level = 0)
    {
        $result = $this->_model->where('pid='.$pid)->order('sort ASC')->findAll();
        if($result) {
            foreach($result as $key => $value) {
                $id = $value[$this->_talbe.'_id'];
                $list[$id]['id'] = $value[$this->_talbe.'_id'];
                $list[$id]['pid'] = $value['pid'];
                $list[$id]['title'] = $value['title'];
                $list[$id]['level'] = $level;
                $list[$id]['child'] = $this->_MakeTree($value[$this->_talbe.'_id'], $level + 1);
            }
        }

        return $list;
    }

    /**
     * 清除分類資料PHP檔案
     * @return void
     */
    public function remakeTreeCache()
    {
        S('category_cache_'.$this->_talbe, null);
    }

    /**
     * 移動分類操作
     * @param integer $id 移動分類ID
     * @param string $type 移動類型：上移(up)，下移(down)
     * @return boolean 是否移動成功
     */
    public function moveTreeCategory($id, $type)
    {
        // 判斷資料的正確性
        if(!is_numeric($id) || !in_array($type, array('up', 'down'))) {
            return false;
        }
        // 移動資料
        $pid = $this->_model->where($this->_talbe.'_id='.$id)->getField('pid');
        $data = $this->_model->field($this->_talbe.'_id, sort')->where('pid='.$pid)->order('sort ASC')->findAll();
        // 存儲前後值
        $before = $in = $after = array();
        $keys = getSubByKey($data, $this->_talbe.'_id');
        $key = array_search($id, $keys);
        $in = $data[$key];
        if($key == 0) {
            $after = $data[$key + 1];
        } else if($key == count($data)) {
            $before = $data[$key - 1];
        } else {
            $before = $data[$key - 1];
            $after = $data[$key + 1];
        }
        // 判斷類型
        $result = false;
        switch($type) {
        case 'up':
            if(!empty($before)) {
                $this->_model->where($this->_talbe.'_id='.$id)->setField('sort', $before['sort']);
                $this->_model->where($this->_talbe.'_id='.$before[$this->_talbe.'_id'])->setField('sort', $in['sort']);
                $result = true;
            }
            break;
        case 'down':
            if(!empty($after)) {
                $this->_model->where($this->_talbe.'_id='.$id)->setField('sort', $after['sort']);
                $this->_model->where($this->_talbe.'_id='.$after[$this->_talbe.'_id'])->setField('sort', $in['sort']);
                $result = true;
            }
            break;
        }

        $result && $this->remakeTreeCache();
        $result && model('Cache')->rm('UserCategoryTree');
        return $result;
    }

    /**
     * 更新排序欄位，僅僅用於重新整理歷史資料
     * @return void
     */
    public function updateSort()
    {
        set_time_limit(0);
        $pids = $this->_model->field('DISTINCT pid')->findAll();
        $pids = getSubByKey($pids, 'pid');
        foreach($pids as $pid) {
            $map['pid'] = $pid;
            $data = $this->_model->where($map)->order($this->_talbe.'_id ASC')->findAll();
            $sort = 1;
            foreach($data as $value) {
                $smap[$this->_talbe.'_id'] = $value[$this->_talbe.'_id'];
                $save['sort'] = $sort;
                $this->_model->where($smap)->save($save);
                $sort++;
            }
        }
    }

    /**
     * 添加子分類操作
     * @param integer $pid 父級分類ID
     * @param string $title 分類名稱
     * @param array $extra 插入資料時，帶入的相關資訊
     * @return boolean 添加分類是否成功
     */
    public function addTreeCategory($pid, $title, $extra = array())
    {
        // 判斷是否有重複的值
        $isExist = $this->_model->where("pid={$pid} AND title='{$title}'")->count();
        if($isExist != 0) {
            return false;
        }
        // 添加分類操作
        $data['title'] = $title;
        $data['pid'] = $pid;
        // 獲取排序值
        $map['pid'] = $pid;
        $maxSort = $this->_model->where($map)->order('sort DESC')->getField('sort');
        $data['sort'] = $maxSort + 1;
        // 添加額外資訊
        if(!empty($extra)) {
            $data = array_merge($data, $extra);
        }
        $result = $this->_model->add($data);
        // 清除快取
        $result && $this->remakeTreeCache();
        $result && model('Cache')->rm('UserCategoryTree');
        // 刪除city.php 檔案
        $result && model('Area')->remakeCityCache();

        return (boolean)$result;
    }

    /**
     * 更新分類資訊操作
     * @param integer $cid 分類ID
     * @param string $title 分類名稱
     * @param array $extra 插入資料時，帶入的相關資訊
     * @return boolean 更新分類是否成功
     */
    public function upTreeCategory($cid, $title, $extra = array())
    {
        // 判斷名稱是否重複
        $pid = $this->_model->where($this->_talbe.'_id='.$cid)->getField('pid');
        $isExist = $this->_model->where("pid={$pid} AND title='{$title}'")->count();
        if($isExist != 0 && empty($extra)) {
            return false;
        }
        // 更新分類操作
        $map[$this->_talbe.'_id'] = $cid;
        $data['title'] = $title;
        // 添加額外資訊
        if(!empty($extra)) {
            $data = array_merge($data, $extra);
        }
        $result = $this->_model->where($map)->save($data);
        // 清除快取
        $result && $this->remakeTreeCache();
        $result && model('Cache')->rm('UserCategoryTree');
        // 刪除city.php 檔案
        $result && model('Area')->remakeCityCache();
        return (boolean)$result;
    }

    /**
     * 刪除分類資訊操作
     * @param integer $cid 分類ID
     * @param string $_module 模型名稱，默認為null
     * @param string $_method 方法名稱，默認為null
     * @return boolean 刪除分類資訊是否成功
     */
    public function rmTreeCategory($cid, $_module = null, $_method = null)
    {
        if (empty($cid)) {
            return false;
        }
        // 判斷是否是包含子分類
        $isExist = $this->_model->where('pid='.$cid)->count();
        if($isExist != 0) {
            $this->setMessage('該分類下存在子分類，刪除分類失敗');
            return false;
        }
        // 刪除分類操作
        $map[$this->_talbe.'_id'] = $cid;
        $result = $this->_model->where($map)->delete();
        // 執行刪除後的關聯操作
        if(!empty($_module) && !empty($_method)) {
            if(empty($this->_app)) {
                model($_module)->$_method($cid);
            } else {
                D($_module, $this->_app)->$_method($cid);
            }
        }
        // 清除快取
        if($result) {
            $this->setMessage('刪除分類成功');
            $this->remakeTreeCache();
            model('Cache')->rm('UserCategoryTree');
        } else {
            $this->setMessage('刪除分類失敗');
        }

        return (boolean)$result;
    }

    /**
     * 獲取全部分類Hash陣列
     * @param integer $pid 父級分類ID
     * @return array 全部分類Hash陣列
     */
    public function getCategoryHash($pid = -1)
    {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $data = $this->_model->where($map)->order('sort ASC')->getHashList($this->_talbe.'_id', 'title');

        return $data;
    }

    /**
     * 獲取指定分類的資訊
     * @param integer $cid 分類ID
     * @return array 分類資訊
     */
    public function getCategoryInfo($cid)
    {
        $map[$this->_talbe.'_id'] = $cid;
        $data = $this->_model->where($map)->find();

        return $data;
    }

    /**
     * 存儲分類配置項操作
     * @param integer $cid 分類ID
     * @param array $extra 分類配置資料陣列
     * @return boolean 是否存儲成功
     */
    public function doSetCategoryConf($cid, $ext)
    {
        if(empty($cid) || !is_array($ext)) {
            return false;
        }
        $map[$this->_talbe.'_id'] = $cid;
        $data['ext'] = serialize($ext);
        $result = $this->_model->where($map)->save($data);

        return (boolean)$result;
    }

    /**
     * 獲取指定分類的相關配置資訊
     * @param integer $cid 分類ID
     * @return array 指定分類的相關配置資訊
     */
    public function getCatgoryConf($cid)
    {
        if(empty($cid)) {
            return array();
    }
    $category = $this->getCategoryById($cid);
    $extra = unserialize($category['ext']);

    return $extra;
    }

    /**
     * 判斷分類名稱是否重複
     * @param string $title 分類名稱
     * @return boolean 分類名稱是否重複
     */
    public function isTitleExist($title)
    {
        $map['title'] = t($title);
        $count = $this->_model->where($map)->count();
        $result = ($count == 0) ? false : true;
        return $result;
    }

    /**
     * 獲取詳細的分類Hash陣列 - 主要為了顯示ext中的內容
     * @param integer $pid 父級分類ID
     * @return array 詳細的分類Hash陣列 - 主要為了顯示ext中的內容
     */
    public function getCategoryAllHash ($pid = -1, $order = 'sort ASC') {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $list = $this->_model->where($map)->order($order)->getHashList($this->_talbe.'_id', '*');
        $data = array();
        foreach ($list as $key => $value) {
            if (!empty($value['ext'])) {
                $ext = unserialize($value['ext']);
                $value = array_merge($value, $ext);
    }
    $data[$key] = $value;
    }

    return $data;
    }
    }
