<?php
/**
 * 部門模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class DepartmentModel extends Model {

    const FIELD_ID = 34;                    // 部門的欄位ID
    const FIELD_KEY = 'department';         // 部門的欄位KEY

    protected $tableName = 'department';
    protected $fields = array(0=>'department_id',1=>'title',2=>'parent_dept_id',3=>'display_order',4=>'ctime');

    protected $treeDo;                      // 分類樹模型

    /**
     * 初始化方法，生成部門的樹形物件模型
     * @return void
     */
    public function _initialize() {
        $field = array('id'=>'department_id','name'=>'title','pid'=>'parent_dept_id','sort'=>'display_order');
        tsload(ADDON_PATH.'/model/CateTreeModel');
        $this->treeDo = new CateTreeModel('department');
        $this->treeDo->setField($field);
    }

    /**
     * 獲取部門資訊的樹形結構
     * @param integer $pid 父級ID，默認為0
     * @return array 部門資訊的樹形結構
     */
    public function getDepartment($pid = 0) {
        $data = $this->treeDo->getTree($pid);
        return $data;
    }

    /**
     * 獲取部門全部分類的Hash陣列
     * @return array 部門分類的Hash陣列
     */
    public function getAllHash() {
        return $this->treeDo->getAllHash();
    }

    /**
     * 獲取指定子樹的部門Hash陣列
     * @param integer $pid 父級ID
     * @param integer $sid 資源節點ID
     * @param integer $nosid 是否包含資源節點ID，默認為0
     * @return array 指定子樹的部門Hash陣列
     */
    public function getHashDepartment($pid = 0, $sid = '', $nosid = 0) {
        $treeHash   =$this->treeDo->getAllHash();
        $pid ==0 && $optHash[0] = L('PUBLIC_TOP_DEPARTMENT') ;          // 頂級部門
        foreach($treeHash as $k => $v) {
            if($nosid == 1 && !empty($sid) && $k == $sid) {
                continue;
            }
            $v['parent_dept_id'] == $pid && $optHash[$k] = $v['title'];
        }
        return $optHash;
    }

    /**
     * 清除部門快取
     * @return void
     */
    public function cleanCache() {
        return $this->treeDo->cleanCache();
    }

    /**
     * 添加一個部門資訊
     * @param array $data 新部門相關資訊
     * @return boolean 是否添加成功
     */
    public function addDepart($data) {
        if(empty($data)) {
            return false;
        }
        if(empty($data['title'])) {
            return false;
        }
        // 添加部門操作
        $add['title'] = t($data['title']);
        $add['parent_dept_id'] = $this->_getParent_dept($data);
        $add['display_order'] = isset($data['display_order'])  && !empty($data['display_order'])? $data['display_order'] : 255;
        $add['ctime'] = time();

        if($this->add($add)) {
            $this->cleanCache();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 獲取指定分類的上級ID
     * @param array $data 指定分類的相關資料
     * @return integer 指定分類的上級ID
     */
    public function _getParent_dept($data) {
        $pid = $data['parent_dept_id'];
        if(!empty($data['_parent_dept_id'])) {
            $level = count($data['_parent_dept_id']);
            $_P = $data['_parent_dept_id'][$level-2] ?  $data['_parent_dept_id'][$level - 2] : $pid;
            $pid = $data['_parent_dept_id'][$level-1] > 0 ? $data['_parent_dept_id'][$level - 1] : $_P;
        }

        return intval($pid);
    }

    /**
     * 刪除指定部門操作
     * @param integer $id 指定分類ID
     * @param integer $pid 父級分類ID
     * @return boolean 是否刪除成功
     */
    public function delDepart($id, $pid = 0) {
        if(empty($id)) {
            return false;
        }
        // 如果子部門不是移動到頂級下面，則判斷是否移動到自己子集下面
        if($pid != 0) {
            $data = $this->treeDo->getChildHash($id);
            if(in_array($pid, $data)) {
                return false;
            }
        }
        $map = array();
        $map['department_id'] = $id;
        // 獲取子節點
        $tree = $this->treeDo->getTree($id);
        // 移動子節點
        if(!empty($tree['_child'])) {
            foreach ($tree['_child'] as $c) {
                $this->moveDepart($c['department_id'],$pid);
            }
        }
        // 移動當前部門使用者到新的部門
        $oldTreeName = $this->getTreeName($id);
        $newTreeName = $this->getTreeName($pid);
        $this->editUserProfile($oldTreeName,$newTreeName);
        // 修改當前部門下使用者的部門關聯表資訊
        $ids = $this->getTreeId($pid);
        $this->updateUserDepart(implode('|', $newTreeName)."|",$ids);

        if($this->where($map)->delete()){
            $this->editUserProfile();
            // 移動子集
            $upmap = array();
            $upmap['parent_dept_id'] = $id;
            $save['parent_dept_id']  = $pid;
            $this->where($upmap)->save($save);

            $fieldids = D('user_profile_setting')->where("form_type='selectDepart'")->field('field_id')->findAll();
            $fids = getSubByKey( $fieldids , 'field_id' );
            $profilemap['field_data'] = $id;
            $profilemap['field_id'] = array( 'in' , $fids );
            D('user_profile')->setField('field_data',$pid,$profilemap);

            $this->cleanCache();
            return true;
        } else {
            return false;
        }
    }

    /**
     * 移動部門，將某個部門移動到新部門下面
     * @param integer $id 預移動部門ID
     * @param integer $pid 移動到的父級ID
     * @return boolean 是否移動成功
     */
    public function moveDepart($id, $pid = 0) {
        if(empty($id)) {
            $this->error = L('PUBLIC_DEPARTMENT_ID_REQUIRED');          // 部門ID不能為空
            return false;
        }
        // 如果子部門不是移動到頂級下面，則判斷是否移動到自己子集下面
        if($pid != 0) {
            $data = $this->treeDo->getChildHash($id);
            if(in_array($pid, $data)) {
                return false;
            }
        }
        $map = array();
        $map['department_id'] = $id;
        $save['parent_dept_id']  = $pid;
        $oldTreeName = $this->getTreeName($id);

        if($this->where($map)->save($save)) {
            $curName = $oldTreeName[count($oldTreeName) - 1];
            $newtreeName = array();
            if($pid != 0) {
                $newTreeName = $this->getTreeName($pid);
            }
            $newTreeName[] = $curName;
            $this->cleanCache();
            // 替換使用者欄位表的冗餘資料
            $this->editUserProfile($oldTreeName, $newTreeName);
            // 更新部門關聯表資料
            $ids = $this->getTreeIdBySql($id);
            $this->updateUserDepart(implode('|', $newTreeName)."|",$ids);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 獲取多個使用者的部門資訊
     * @param array $uids 使用者ID陣列
     * @return array 多個使用者的部門資訊
     */
    public function getUserDepart($uids) {
        !is_array($uids) && $uids = explode(',', $uids);
        if(empty($uids)) {
            return array();
        }
        $map['uid'] = array('IN', $uids);
        $map['field_id'] = self::FIELD_ID;
        return D('user_profile')->where($map)->getHashList('uid', 'field_data');
    }

    /**
     * 獲取指定使用者的部門ID
     * @param integer $uid 使用者ID
     * @return integer 指定使用者的部門ID
     */
    public function getUserDepartId($uid) {
        $department = $this->getUserDepart($uid);
        $d = explode('|', trim($department[$uid], '|'));
        $map['a.title'] = end($d);
        $map['b.uid'] = $uid;

        $departmentInfo = $this->table($this->tablePrefix.'department AS a LEFT JOIN '.$this->tablePrefix.'user_department AS b ON a.department_id = b.department_id')
            ->where($map)
            ->find();

        return $departmentInfo['department_id'];
    }

    /**
     * 更新部門為departMentTree的使用者的關聯表資訊
     * @param string $treeName 樹結構的名稱
     * @param array $departmentIds 部門ID陣列
     * @return void
     */
    public function updateUserDepart($treeName, $departmentIds) {
        if(empty($treeName) && empty($departmentIds)) {
            return false;
        }
        $sql = "DELETE FROM {$this->tablePrefix}user_department
            WHERE uid IN (
                SELECT uid FROM {$this->tablePrefix}user_profile WHERE field_id ='".self::FIELD_ID."' AND field_data = '{$treeName}')";

        $this->query($sql);

        foreach($departmentIds as $id) {
            $sql = "INSERT INTO {$this->tablePrefix}user_department
                SELECT uid,{$id} AS department_id FROM {$this->tablePrefix}user_profile WHERE field_id ='".self::FIELD_ID."' AND field_data = '{$treeName}' ";

            $this->query($sql);
        }
    }

    /**
     * 更新指定使用者的部門資訊
     * @param integer $uid 使用者ID
     * @param integer $newDepartmentId 新的部門ID
     * @return void
     */
    public function updateUserDepartById($uid, $newDepartmentId) {
        if(empty($uid) || empty($newDepartmentId)) {
            return false;
        }
        $departmentTree = $this->getTreeName($newDepartmentId);
        $departmentIds = $this->getTreeId($newDepartmentId);
        if(empty($departmentIds)) {
            return false;
        }
        // TODO:後期事務處理
        $up['uid'] = $uid;
        $up['field_id'] = self::FIELD_ID;
        if(D('user_profile')->where($up)->count() > 0) {
            $sp['field_data'] = implode("|", $departmentTree)."|";
            D('user_profile')->where($up)->save($sp);
        } else {
            $up['field_data'] = implode("|", $departmentTree)."|";
            D('user_profile')->add($up);
        }

        // 修改使用者部門關聯表資料
        $ud['uid'] = $uid;
        D('user_department')->where($ud)->delete();
        $sql = "INSERT INTO {$this->tablePrefix}user_department (uid,department_id) VALUES ";

        foreach($departmentIds as $department_id) {
            $sql .= "({$uid},{$department_id}),";
        }

        return $this->query(trim($sql,','));
    }

    /**
     * 根據部門ID獲取該部門的路徑
     * @param integer $id 部門ID
     * @param array $names 部門名稱
     * @return array 返回部門路徑名稱陣列
     */
    public function getTreeName($id, $names = array()) {
        return array_reverse($this->_getTreeName($id, $names));
    }

    /**
     * 遞迴方法獲取父級部門名稱
     * @param integer $id 部門ID
     * @param array $names 部門名稱
     * @return array 返回部門路徑名稱陣列
     */
    private function _getTreeName($id,$names){
        $data = $this->treeDo->getTree($id);
        $names[] = $data['title'];
        if($data['parent_dept_id'] !=0){
            return $this->_getTreeName($data['parent_dept_id'],$names);
        }
        return $names;
    }

    /**
     * 獲取從頂級到該級的父親節點陣列
     * @param integer $id 當前節點的ID
     * @param array $ids 附加的節點ID
     * @return array 從頂級到該級的父親節點陣列
     */
    public function getTreeId($id, $ids = array()) {
        $ids[] = $id;
        $data = $this->treeDo->getTree($id);
        if($data['parent_dept_id'] == 0) {
            return $ids;
}

return $this->getTreeId($data['parent_dept_id'],$ids);
}

/**
 * 獲取從頂級到該級的父親節點陣列，查詢資料庫
 * @param integer $id 當前節點的ID
 * @param array $ids 附加的節點ID
 * @return array 從頂級到該級的父親節點陣列
 */
public function getTreeIdBySql($id,$ids=array()){
    $ids[] = $id;
    $map['department_id'] = $id;
    $data = $this->where($map)->find();
    if($data['parent_dept_id'] == 0){
        return $ids;
}

return $this->getTreeIdBySql($data['parent_dept_id'], $ids);
}

/**
 * 批量修改部門名字，需要傳入舊部門名稱Tree和新名稱Tree陣列
 * @param array $oldTreeName 舊部門名稱Tree
 * @param array $newTreeName 新部門名稱Tree
 * @return mix 修改失敗返回false，修改成功返回1
 */
public function  editUserProfile($oldTreeName, $newTreeName) {
    $old = implode('|', $oldTreeName)."|";
    $new = implode('|',$newTreeName)."|";
    $sql = "UPDATE `{$this->tablePrefix}user_profile` SET field_data = REPLACE(field_data,'{$old}','{$new}') WHERE field_id = ".self::FIELD_ID;

    return $this->query($sql);
}

/**
 * 測試使用
 * @return string 插入資料的SQL語句
 */
public function initDepartMent() {
    // 將使用者放到第一個部門下
    $data = $this->treeDo->getTree(0);
    $departName = $data['_child'][0]['title'].'|';
    $departId =$data['_child'][0]['department_id'];
    $sql = "INSERT INTO {$this->tablePrefix}user_profile SELECT uid,".self::FIELD_ID." AS field_id,'{$departName}' AS field_data,0 AS privacy FROM {$this->tablePrefix}user";
    echo $sql;
    $this->query($sql);
    $sql = "INSERT INTO {$this->tablePrefix}user_department SELECT uid,{$departId} AS department_id FROM {$this->tablePrefix}user";
    $this->query($sql);
    echo $sql;
}
}
