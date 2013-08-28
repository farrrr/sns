<?php
/**
 * 模板管理模型 - 資料物件模型
 * @author daniel <desheng.young@gmail.com>
 * @version TS3.0
 */
class TemplateModel extends Model
{
    protected $tableName = 'template';

    /**
     * 獲取模板列表
     * @param array $map 查詢條件
     * @param string $order 排序，默認'name ASC,tpl_id ASC'
     * @param integer $limit 一次查詢條數，默認30
     * @return array 模板列表
     */
    public function getTemplate($map = array(), $order = 'tpl_id DESC', $limit = 30)
    {
        $list = $this->where($map)->order($order)->findPage($limit);
        return $list;
    }

    /**
     * 通過模板ID獲取模板資訊
     * @param integet $tplId 模板ID
     * @return array 模板ID獲取模板資訊
     */
    public function getTemplateById($tplId)
    {
        if(empty($tplId)) {
            return array();
        }
        $map['tpl_id'] = $tplId;
        $data = $this->where($map)->find();
        return $data;
    }

    /**
     * 按照模板名稱查找模板
     * @param string $name 模板名稱
     * @return array 模板名稱查找模板
     */
    public function getTemplateByName($name)
    {
        if(empty($name)) {
            return array();
        }
        $map['name'] = $name;
        $data = $this->where($name)->find();
        return $data;
    }

    /**
     * 添加模版操作
     * @param array $data 模板相關資料
     * @return boolean 是否添加成功
     */
    public function addTemplate($data)
    {
        if(empty($data)) {
            return false;
        }
        $data['ctime'] = empty($data['ctime']) ? time() : $data['ctime'];
        $result = $this->add($data);
        return (boolean)$result;
    }

    /**
     * 編輯模板操作
     * @param integer $tplId 模板ID
     * @param array $data 模板相關資料
     * @return boolean 是否添加成功
     */
    public function upTemplate($tplId, $data)
    {
        if(empty($tplId) || empty($data)) {
            return false;
        }
        $map['tpl_id'] = $tplId;
        $result = $this->where($map)->save($data);
        return (boolean)$result;
    }

    /**
     * 刪除指定模板ID的模板資料
     * @param array|integer $tplIds 模板ID陣列，也可以是單個模板ID
     * @return boolean 是否刪除成功
     */
    public function delTemplate($tplIds)
    {
        $tplIds = is_array($tplIds) ? $tplIds : explode(',', $tplIds);
        if(empty($tplIds)) {
            return false;
        }
        $map['tpl_id'] = array('IN', $tplIds);
        $result = $this->where($map)->delete();
        return (boolean)$result;
    }

    /**
     * 刪除模板
     * @param string $where 可以是模板ID：template_ids或模板名稱：names多個ID或名稱是陣列形式，也可用“,”分隔
     * @return boolean 是否刪除成功
     */
    public function deleteTemplate($where)
    {
        if(empty($where)) {
            return false;
        }

        $where = is_array($where) ? $where : explode(',', $where);
        if(is_numeric($where[0])) {
            $map['tpl_id'] = array('IN', $where);
        } else if (is_string($where[0])) {
            $map['name'] = array('IN', $where);
        }
        if(empty($map)) {
            return false;
        }
        $result = $this->where($map)->delete();
        return (boolean)$result;
    }

    /**
     * 解析模板（將模板中變數替換成資料）
     * @param string $tpl_name 模板名稱
     * @param array $data 模板中的變數和資料
     * @param boolean $auto_record 是否添加模板記錄
     * @return boolean|string 模板解析的結果
     */
    public function parseTemplate($tpl_name, $data, $auto_record = null)
    {
        $map['name'] = $tpl_name;
        $template = $this->where($map)->find();
        if(!$template) {
            return false;
        }

        $auto_record = isset($auto_record) ? $auto_record : $template['is_cache'];
        $title = '';
        $body = '';

        // 標題模板
        if(!empty($template['title'])) {
            $keys = array_keys($data['title']);
            $values = array_values($data['title']);
            foreach($keys as $k => $v) {
                $keys[$k] = '{'.$v.'}';
            }
            $template['title'] = str_replace($keys, $values, $template['title']);
            unset($keys, $values);
        }
        // 內容模板
        if ( !empty($template['body']) ) {
            $keys   = array_keys($data['body']);
            $values = array_values($data['body']);
            foreach($keys as $k => $v) {
                $keys[$k] = '{'.$v.'}';
        }
        $template['body'] = str_replace($keys, $values, $template['body']);
        unset($keys, $values);
        }

        //自動添加模板記錄
        if ($auto_record) {
            $record_data['uid']         = isset($data['uid']) ? $data['uid'] : $_SESSION['mid'];
            $record_data['tpl_name']    = $template['name'];
            $record_data['tpl_alias']   = $template['alias'];
            $record_data['type']        = $template['type'];
            $record_data['type2']       = $template['type2'];
            $record_data['ctime']       = time();
            unset($template['tpl_id']);
            $record_data['data']        = serialize($template);
            return $this->addTemplateRecord($record_data);
        }else {
            return $template;
        }
        }

        /**
         * 添加模板記錄
         * @param array $data 模板的各種參數
         * @return boolean 是否添加成功
         */
        public function addTemplateRecord($data)
        {
            $result = D('template_record')->add($data);
            return (boolean)$result;
        }

        /**
         * 查詢模板記錄
         * @param array $map 查詢條件
         * @param string $order 結果排序，默認'tpl_record_id DESC'
         * @param integer $limit 查詢條數，默認30
         * @return array 模板記錄
         */
        public function getTemplateRecordByMap($map = array(), $order = 'tpl_record_id DESC', $limit = 30)
        {
            $result = D('template_record')->where($map)->order($order)->findPage($limit);
            foreach($result['data'] as $key => $val) {
                $result['data'][$key]['data'] = unserialize($val['data']);
        }

        return $result;
        }
        }
