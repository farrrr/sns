<?php
/**
 * 使用者檔案模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserProfileModel  extends Model {

    const DEPARTMENT_ID = 34;                       // 部門的欄位ID
    const DEPARTMENT_KEY = 'department';            // 部門的欄位KEY

    protected $tableName = 'user_profile';
    protected $fields = array(0=>'uid',1=>'field_id',2=>'field_data',3=>'privacy');

    public static $profileSetting = array();        // 靜態檔案配置欄位
    public static $sysProfile = array('intro', 'work_position', 'mobile', 'tel', 'work_director', 'department');            // 系統默認的欄位，使用者資料裡面必須有的

    /**
     * 獲取使用者的分類資訊列表
     * @return array 使用者的分類資訊列表
     */
    public function getCategoryList() {
        $map['field_type'] = 0;
        $category_list = $this->_getUserProfileSetting($map);

        return $category_list;
    }

    /**
     * 獲取使用者資料配置資訊 - 不分頁型
     * @param array $map 查詢條件
     * @param string $order 排序條件
     * @return array 使用者資料配置資訊
     */
    public function getUserProfileSetting($map = null, $order = 'field_key, display_order ASC') {
        $key = md5(implode("", $map).$order);
        if($setting = static_cache('profile_'.$key)) {
            return $setting;
        }
        $setting = $this->_getUserProfileSetting($map, $order);
        $setting = $this->_formatUserProfileSetting($setting);
        static_cache('profile_'.$key,$setting);

        return $setting;
    }

    /**
     * 獲取使用者資料配置資訊的樹形結構，已分類進行樹形分類
     * @param array $map 查詢條件
     * @param string $order 排序條件
     * @return array 使用者資料配置資訊的樹形結構，已分類進行樹形分類
     */
    public function getUserProfileSettingTree($map = null, $order = 'field_key, display_order ASC') {
        $setting = $this->_getUserProfileSetting($map, $order);
        $setting = $this->_makeUserProfileSettingTree($setting, 0);

        return $setting;
    }

    /**
     * 刪除指定的資料配置欄位
     * @param array $filed_ids 配置欄位ID陣列
     * @return boolean 是否刪除成功
     */
    public function deleteProfileSet($filed_ids) {
        // 驗證資料
        if(empty($filed_ids)) {
            $this->error = L('PUBLIC_FIELD_REQUIRED');          // 欄位ID不可以為空
            return false;
        }
        // 刪除配置欄位操作
        $ids = is_array($filed_ids) ? $filed_ids : explode(',', $filed_ids);
        $map['field_id'] = array('IN', $ids);
        $reslut = D('')->table(C('DB_PREFIX').'user_profile_setting')->where($map)->delete();
        if($reslut !== false) {
            return true;
        } else {
            $this->error = L('PUBLIC_FIELD_DELETE_FAIL');       // 欄位刪除失敗
            return false;
        }
    }

    /**
     * 獲取指定使用者的檔案資訊
     * @param integet $uid 使用者UID
     * @return array 指定使用者的檔案資訊
     */
    public function getUserProfile($uid) {
        // 驗證資料
        if(empty($uid)) {
            return false;
        }
        if(($data = model('Cache')->get('user_profile_'.$uid)) === false) {
            $map['uid'] = $uid;
            $profile = $this->where($map)->findAll();
            $profile = $this->_formatUserProfile($profile);
            $data = empty($profile[$uid])? array():$profile[$uid];
            model('Cache')->set('user_profile_'.$uid,$data);
        }

        return $data;
    }

    /**
     * 清除指定使用者的檔案快取
     * @param array $uids 使用者UID陣列
     * @return void
     */
    public function cleanCache($uids) {
        !is_array($uids) && $uids = explode(',', $uids);
        if(empty($uids)) {
            return false;
        }
        $cache = model('Cache');
        foreach($uids as $v) {
            $cache->rm('user_profile_'.$v);
        }
    }

    /**
     * 批量獲取多個使用者的檔案資訊
     * @param array $uids 使用者UID陣列
     * @param string $category 欄位類型，未使用
     * @return array 多個使用者的檔案資訊
     */
    public function getUserProfileByUids($uids, $category = null) {
        !is_array($uids) && $uids = explode(',', $uids);
        $cacheList = model('Cache')->getList('user_profile_', $uids);

        foreach($uids as $v) {
            !$cacheList[$v] && $cacheList[$v] = $this->getUserProfile($v);
        }

        return $cacheList;
    }

    /**
     * 獲取使用者配置資訊欄位資訊
     * @return array 使用者配置資訊欄位資訊
     */
    public function getUserProfileInputType() {
        $input_type = array(
            'input'=>L('PUBLIC_INPUT_FORM'),                    // 輸入表單
            'inputnums' =>L('PUBLIC_NUM_INPUT'),                // 純數字input輸入
            'textarea'=>L('PUBLIC_SEVERAL_TEXTFIELD'),          // 多行文字
            'select'=>L('PUBLIC_DROPDOWN_MENU'),                // 下拉選單
            'radio'=>L('PUBLIC_RADIO_BUTTON'),                  // 單選框
            'checkbox'=>L('PUBLIC_CHECK_BOX'),                  // 覈取方塊
            'date'=>L('PUBLIC_TIME_SELECT'),                    // 時間選擇
            'selectUser'=>L('PUBLIC_USER_SELECT'),              // 使用者選擇
            'selectDepart'=>L('PUBLIC_DEPARTMENT_SELECT'),      // 部門選擇
        );

        return $input_type;
    }

    /**
     * 儲存指定使用者的檔案資訊
     * @param integer $uid 使用者UID
     * @param array $data 使用者檔案資訊
     * @return boolean 是否儲存成功
     */
    public function saveUserProfile($uid, $data) {
        $field_ids = $delete_map = $save_data = array();
        $delete_map['uid'] = $uid;
        if ( isset( $_POST['cid'] ) ){
            $cmap['field_type'] = intval ( $_POST['cid'] );
            $setting = $this->getUserProfileSetting($cmap);
            foreach ( $setting as $sk=>$se ){
                if ( !isset( $data[$se['field_key']] )){
                    $data[$se['field_key']] = '';
                }
            }
        } else {
            $setting = $this->getUserProfileSetting();
        }

        foreach ($data as $d_k => $d_v) {
            is_array($d_v) && $d_v = implode('|', $d_v);
            $field_id = $setting[$d_k]['field_id'];
            if(isset($field_id)) {
                // 判斷欄位是否為必填
                // 部門資訊特殊處理
                if($d_k == self::DEPARTMENT_KEY) {
                    if($d_v == 0) {
                        continue;
                    } else {
                        model('Department')->updateUserDepartById($uid, $d_v);
                        //                      continue;
                    }
                }

                $d_v = t($d_v);
                if($setting[$d_k]['required'] > 0 && empty($d_v)) {
                    $this->error = L('PUBLIC_INPUT_SOME', array('input'=>$setting[$d_k]['field_name']));            // 請輸入{input}
                    return false;
                }

                if($setting[$d_k]['form_type'] =='inputnums' && !is_numeric($d_v) && $d_v) {
                    $this->error = L('PUBLIC_SOME_NOT_RIGHT', array('input'=>$setting[$d_k]['field_name']));        // {input}格式不正確
                    return false;
                }

                $field_ids[] = $field_id;
                $d_v = str_replace("'", "\\'", $d_v);
                $save_data[] = "{$uid}, {$field_id}, '".(('date' == $setting[$d_k]['form_type']) && !is_numeric($d_v) ? strtotime($d_v) : $d_v) . "'";
            }
        }
        if(empty($field_ids)) {
            return true;
        }

        $this->cleanCache($uid);

        $delete_map['field_id'] = array('IN', $field_ids);
        $sql = "INSERT INTO `".$this->tablePrefix."{$this->tableName}` (`uid`, `field_id`, `field_data`) VALUES (".implode('), (', $save_data).")";
        // 刪除歷史資料
        $this->where($delete_map)->limit(count($field_ids))->delete();
        // 插入新資料
        $res = $this->query($sql);

        $res = false !== $res;

        $this->error = $res ? L('PUBLIC_ADMIN_OPRETING_SUCCESS') : L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作成功，操作失敗

        return $res;
    }

    /**
     * 獲取彙報關係，由上級至下級
     * @param integer $uid 使用者UID
     * @param integer $level 顯示的層級值
     * @return array 彙報關係樹形結構
     */
    public function getUserWorkDirectorTree($uid, $level = 3) {
        // 由下級至上級
        $director_uid = $uid;
        $tree = array($director_uid);
        for($i = 1; $i < $level; $i ++) {
            $director_uid = $this->_getWorkDirector($director_uid);
            if($director_uid) {
                $tree[] = (int)$director_uid;
            } else {
                break;
            }
        }
        $tree = array_reverse($tree, true);

        return $tree;
    }

    /*** 私有方法 ***/
    /**
     * 獲取使用者資料欄位資訊
     * @param array $map 查詢條件
     * @param string $order 排序條件
     * @return array 使用者資料欄位資訊
     */
    private function _getUserProfileSetting($map = null, $order = 'display_order,field_id ASC') {
        $setting = D('UserProfileSetting')->where($map)->order($order)->findAll();
        return $setting;
    }

    /**
     * 格式化使用者資料欄位資訊
     * @param array $setting 使用者資料欄位資訊
     * @return array 格式化後的使用者資料欄位資訊
     */
    private function _formatUserProfileSetting($setting) {
        $_setting = array();
        foreach($setting as $s_v) {
            $_setting[$s_v['field_key']] = $s_v;
        }

        return $_setting;
    }

    /**
     * 生成使用者欄位配置的樹形結構，遞迴方法
     * @param array $setting 使用者欄位配置資訊
     * @param integer $parent_key 父級的Key值
     * @return array 使用者欄位配置的樹形結構
     */
    private function _makeUserProfileSettingTree($setting, $parent_key = 0) {
        $_setting = array();
        foreach ($setting as $s_k => $s_v) {
            if ($s_v['field_type'] == $parent_key) {
                unset($setting[$s_k]);
                $s_v['child'] = $this->_makeUserProfileSettingTree($setting, $s_v['field_id']);
                $_setting[$s_v['field_key']] = $s_v;
            }
        }

        return $_setting;
    }

    /**
     * 格式化使用者的檔案資料
     * @param array $profile 檔案資料
     * @return array 格式化後的使用者檔案資料
     */
    private function _formatUserProfile($profile) {
        $_profile = array();
        foreach($profile as $p_v) {
            $_profile[$p_v['uid']][$p_v['field_id']] = $p_v;
}

return $_profile;
}

/**
 * 獲取指定使用者的直接領導的UID
 * @param integer $uid 使用者UID
 * @return integer 指定使用者的直接領導的UID
 */
private function _getWorkDirector($uid) {
    $user_profile = $this->getUserProfileByUids($uid);
    $user_profile_setting = $this->getUserProfileSetting();
    $field_id = $user_profile_setting['work_director']['field_id'];
    $director_uid = $user_profile[$uid][$field_id]['field_data'];

    return $director_uid;
}

/*** API使用 ***/
/**
 * 獲取指定使用者的檔案資訊，API使用
 * @param integer $uid 使用者UID
 * @return array 指定使用者的檔案資訊
 */
public function getUserProfileForApi($uid){
    $r = array();
    // 使用者欄位資訊
    $profileSetting = D('UserProfileSetting')->where('type=2')->getHashList('field_id');
    $profile = $this->getUserProfile($uid);

    foreach($profile as $k => $v) {
        if(isset($profileSetting[$k])) {
            $r[$profileSetting[$k]['field_key']] = array('name'=>$profileSetting[$k]['field_name'],'value'=>$v['field_data']);
}
}

$r['department']['value'] && $r['department']['value'] = trim($r['department']['value'],'|');

if($r['work_director']['value']) {
    $work_director = model('User')->getUserInfo($r['work_director']['value']);
    $r['work_director']['value'] = $work_director['uname'];
}

return $r;
}
}
