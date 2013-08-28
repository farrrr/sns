<?php
/**
 * 使用者模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserModel extends Model {
    protected $tableName = 'user';
    protected $error = '';
    protected $fields = array (
        0 => 'uid',
        1 => 'login',
        2 => 'password',
        3 => 'login_salt',
        4 => 'uname',
        5 => 'email',
        6 => 'sex',
        7 => 'location',
        8 => 'is_audit',
        9 => 'is_active',
        10 => 'is_init',
        11 => 'ctime',
        12 => 'identity',
        13 => 'api_key',
        14 => 'domain',
        15 => 'province',
        16 => 'city',
        17 => 'area',
        18 => 'reg_ip',
        19 => 'lang',
        20 => 'timezone',
        21 => 'is_del',
        22 => 'first_letter',
        23 => 'intro',
        24 => 'last_login_time',
        25 => 'last_feed_id',
        26 => 'last_post_time',
        27 => 'search_key',
        28 => 'invite_code',
        '_autoinc' => true,
        '_pk' => 'uid'
    );

    /**
     * 獲取使用者列表，後臺可以根據使用者組查詢
     *
     * @param integer $limit
     *          結果集數目，默認為20
     * @param array $map
     *          查詢條件
     * @return array 使用者列表資訊
     */
    public function getUserList($limit = 20, $map = array(), $order = "uid DESC") {
        // 添加使用者表的查詢，用於關聯查詢
        // $table = $this->tablePrefix."user AS u";
        if (isset ( $_POST )) {
            $_POST ['uid'] && $map ['uid'] = intval ( $_POST ['uid'] );
            $_POST ['uname'] && $map ['uname'] = array('LIKE', '%'.t($_POST['uname']).'%');
            $_POST ['email'] && $map ['email'] = array('LIKE', '%'.t($_POST['email']).'%');
            isset ( $_POST ['is_audit'] ) && $map ['is_audit'] = intval ( $_POST ['is_audit'] );
            ! empty ( $_POST ['sex'] ) && $map ['sex'] = intval ( $_POST ['sex'] );

            // 註冊時間判斷，ctime為陣列格式
            if (! empty ( $_POST ['ctime'] )) {
                if (! empty ( $_POST ['ctime'] [0] ) && ! empty ( $_POST ['ctime'] [1] )) {
                    // 時間區間條件
                    $map ['ctime'] = array (
                        'BETWEEN',
                        array (
                            strtotime ( $_POST ['ctime'] [0] ),
                            strtotime ( $_POST ['ctime'] [1] )
                        )
                    );
                } else if (! empty ( $_POST ['ctime'] [0] )) {
                    // 時間大於條件
                    $map ['ctime'] = array (
                        'GT',
                        strtotime ( $_POST ['ctime'] [0] )
                    );
                } elseif (! empty ( $_POST ['ctime'] [1] )) {
                    // 時間小於條件
                    $map ['ctime'] = array (
                        'LT',
                        strtotime ( $_POST ['ctime'] [1] )
                    );
                }
            }

            // 使用者部門資訊過濾
            /*
             * if(!empty($_POST['department'])) { $table .= " left join {$this->tablePrefix}user_department d on u.uid = d.uid and d.department_id = '".intval($_POST['department'])."'"; }
             */

            // 使用者組資訊過濾
            if (! $_POST ['uid']) {
                if (! empty ( $_POST ['user_group'] )) {
                    if (is_array ( $_POST ['user_group'] )) {
                        $user_group_id = " user_group_id IN ('" . implode ( "','", $_POST ['user_group'] ) . "') ";
                    } else {
                        $user_group_id = " user_group_id = '{$_POST['user_group']}' ";
                    }
                    // 關聯查詢，使用者查詢出指定使用者組的使用者資訊
                    // $table .= ' LEFT JOIN (SELECT MAX(id), uid FROM `'.$this->tablePrefix.'user_group_link` WHERE '.$user_group_id.' GROUP BY uid) AS b ON u.uid = b.uid';
                    $group_uid = getSubByKey ( D ( 'user_group_link' )->where ( 'user_group_id=' . intval ( $_POST ['user_group'] ) )->findAll (), 'uid' );
                    $map ['uid'] = array (
                        'in',
                        $group_uid
                    );
                }

                // 使用者標簽過濾
                if (! empty ( $_POST ['user_category'] )) {
                    // $table .= ' LEFT JOIN '.$this->tablePrefix.'user_category_link AS l ON l.uid = u.uid WHERE user_category_id = '.intval($_POST['user_category']);
                    $title = D('user_category')->where('user_category_id='.intval($_POST['user_category']))->getField('title');
                    $tagId = D('tag')->where('name=\''.t($title).'\'')->getField('tag_id');
                    $a['app'] = 'public';
                    $a['table'] = 'user';
                    $a['tag_id'] = intval ($tagId);
                    $tag_uid = getSubByKey ( D ( 'app_tag' )->where ( $a )->findAll (), 'row_id' );
                    if ($group_uid) {
                        $map ['uid'] = array (
                            'in',
                            array_intersect ( $group_uid, $tag_uid )
                        );
                    } else {
                        $map ['uid'] = array (
                            'in',
                            $tag_uid
                        );
                    }
                }
            }
        }

        // 查詢資料
        $list = $this->where ( $map )->order ( $order )->findPage ( $limit );

        // 資料組裝
        $userGroupHash = array ();
        $uids = array ();
        foreach ( $list ['data'] as $k => $v ) {
            $userGroupHash [$v ['uid']] = array ();
            $uids [] = $v ['uid'];
            $list ['data'] [$k] ['user_group'] = &$userGroupHash [$v ['uid']];
        }
        $gmap ['uid'] = array (
            'IN',
            $uids
        );
        $userGroupLink = D ( 'user_group_link' )->where ( $gmap )->findAll ();
        foreach ( $userGroupLink as $v ) {
            $userGroupHash [$v ['uid']] [] = $v ['user_group_id'];
        }

        return $list;
    }

    /**
     * 獲取使用者列表資訊 - 未分頁型
     *
     * @param array $map
     *          查詢條件
     * @param integer $limit
     *          結果集數目，默認為20
     * @param string $field
     *          需要顯示的欄位，多個欄位之間使用“,”分割，默認顯示全部
     * @param string $order
     *          排序條件，默認uid DESC
     * @return array 使用者列表資訊
     */
    public function getList($map = array(), $limit = 20, $field = '*', $order = 'uid DESC') {
        $data = $this->where ( $map )->limit ( $limit )->field ( $field )->order ( $order )->findAll ();
        return $data;
    }

    /**
     * 獲取使用者列表資訊 - 分頁型
     *
     * @param array $map
     *          查詢條件
     * @param integer $limit
     *          結果集數目，默認為20
     * @param string $field
     *          需要顯示的欄位，多個欄位之間使用“,”分割，默認顯示全部
     * @param string $order
     *          排序條件，默認uid DESC
     * @return array 使用者列表資訊
     */
    public function getPage($map = array(), $limit = 20, $field = '*', $order = 'uid DESC') {
        $data = $this->where ( $map )->limit ( $limit )->field ( $field )->order ( $order )->findPage ();
        return $data;
    }

    /**
     * 獲取指定使用者的相關資訊
     *
     * @param integer $uid
     *          使用者UID
     * @return array 指定使用者的相關資訊
     */
    public function getUserInfo($uid) {
        $uid = intval ( $uid );
        if ($uid <= 0) {
            $this->error = L ( 'PUBLIC_UID_INDEX_ILLEAGAL' ); // UID參數值不合法
            return false;
        }
        if ($user = static_cache ( 'user_info_' . $uid )) {
            return $user;
        }
        // 查詢快取資料
        $user = model ( 'Cache' )->get ( 'ui_' . $uid );

        if (! $user) {
            $this->error = L ( 'PUBLIC_GET_USERINFO_FAIL' ); // 獲取使用者資訊快取失敗
            $map ['uid'] = $uid;
            $user = $this->_getUserInfo ( $map );
        }
        static_cache ( 'user_info_' . $uid, $user );

        return $user;
    }

    /**
     * 為@搜索提供使用者資訊
     *
     * @param integer $uid
     *          使用者UID
     * @return array 指定使用者的相關資訊
     */
    public function getUserInfoForSearch($uid, $field) {
        $uid = intval ( $uid );
        if ($uid <= 0) {
            $this->error = L ( 'PUBLIC_UID_INDEX_ILLEAGAL' ); // UID參數值不合法
            return false;
        }
        if ($user = static_cache ( 'user_info_search' . $uid )) {
            return $user;
        }
        // 查詢快取資料
        $user = model ( 'Cache' )->get ( 'ui_' . $uid );
        if (! $user) {
            $this->error = L ( 'PUBLIC_GET_USERINFO_FAIL' ); // 獲取使用者資訊快取失敗
            $map ['uid'] = $uid;
            $user = $this->_getUserInfo ( $map, $field );
        }
        static_cache ( 'user_info_search' . $uid, $user );

        return $user;
    }

    /**
     * 通過使用者昵稱查詢使用者相關資訊
     *
     * @param string $uname
     *          昵稱資訊
     * @return array 指定昵稱使用者的相關資訊
     */
    public function getUserInfoByName($uname, $map) {
        if (empty ( $uname )) {
            $this->error = L ( 'PUBLIC_USER_EMPTY' ); // 使用者名不能為空
            return false;
        }
        $map ['uname'] = t ( $uname );
        $data = $this->_getUserInfo ( $map );
        return $data;
    }

    /**
     * 通過郵箱查詢使用者相關資訊
     *
     * @param string $email
     *          使用者郵箱
     * @return array 指定昵稱使用者的相關資訊
     */
    public function getUserInfoByEmail($email, $map) {
        if (empty ( $email )) {
            $this->error = L ( 'PUBLIC_USER_EMPTY' ); // 使用者名不能為空
            return false;
        }
        $map ['email'] = t ( $email );
        $data = $this->_getUserInfo ( $map );
        return $data;
    }

    /**
     * 通過郵箱查詢使用者相關資訊
     *
     * @param string $email
     *          使用者郵箱
     * @return array 指定昵稱使用者的相關資訊
     */
    public function getUserInfoByDomain($domain, $map) {
        if (empty ( $domain )) {
            $this->error = L ( 'PUBLIC_USER_EMPTY' ); // 使用者名不能為空
            return false;
        }
        $map ['domain'] = t ( $domain );
        $data = $this->_getUserInfo ( $map );
        return $data;
    }

    /**
     * 根據UID批量獲取多個使用者的相關資訊
     *
     * @param array $uids
     *          使用者UID陣列
     * @return array 指定使用者的相關資訊
     */
    public function getUserInfoByUids($uids) {
        ! is_array ( $uids ) && $uids = explode ( ',', $uids );

        $cacheList = model ( 'Cache' )->getList ( 'ui_', $uids );
        foreach ( $uids as $v ) {
            ! $cacheList [$v] && $cacheList [$v] = $this->getUserInfo ( $v );
        }

        return $cacheList;
    }

    /**
     * 獲取指定使用者的檔案資訊
     *
     * @param integer $uid
     *          使用者UID
     * @param string $category
     *          檔案分類
     * @return array 指定使用者的檔案資訊
     */
    public function getUserProfile($uid, $category = '*') {
        $uid = intval ( $uid );
        if ($uid <= 0) {
            $this->error = L ( 'PUBLIC_UID_INDEX_ILLEAGAL' ); // UID參數值不合法
            return false;
        }
        // 設定檔案分類過濾
        $category != '*' && $map ['category'] = $category;
        // 查詢資料
        $profile = D ( 'UserProfile' )->where ( $map )->findAll ( $uid );
        if (! $profile) {
            $this->error = L ( 'PUBLIC_GET_USERPROFILE_FAIL' ); // 獲取使用者檔案失敗
            return false;
        } else {
            return $profile;
        }
    }

    /**
     * 獲取使用者檔案配置資訊
     *
     * @return array 使用者檔案配置資訊
     */
    public function getUserProfileSetting() {
        $profileSetting = D ( 'UserProfileSetting' )->findAll ();
        if (! $profileSetting) {
            $this->error = L ( 'PUBLIC_GET_USERPROFILE_FAIL' ); // 獲取使用者檔案失敗
            return false;
        }

        return $profileSetting;
    }

    /**
     * 添加使用者
     *
     * @param array|object $user
     *          新使用者的相關資訊|新使用者物件
     * @return boolean 是否添加成功
     */
    public function addUser($user) {
        // 驗證使用者名稱是否重複
        $map ['uname'] = t ( $user ['uname'] );
        $isExist = $this->where ( $map )->count ();
        if ($isExist > 0) {
            $this->error = '使用者昵稱已存在，請使用其他昵稱';
            return false;
        }
        if (is_object ( $user )) {
            $salt = rand ( 11111, 99999 );
            $user->login_salt = $salt;
            $user->login = $user->email;
            $user->ctime = time ();
            $user->reg_ip = get_client_ip ();
            $user->password = $this->encryptPassword ( $user->password, $salt );
        } else if (is_array ( $user )) {
            $salt = rand ( 11111, 99999 );
            $user ['login_salt'] = $salt;
            $user ['login'] = $user ['email'];
            $user ['ctime'] = time ();
            $user ['reg_ip'] = get_client_ip ();
            $user ['password'] = $this->encryptPassword ( $user ['password'], $salt );
        }
        // 添加昵稱拼音索引
        $user ['first_letter'] = getFirstLetter ( $user ['uname'] );
        // 如果包含中文將中文翻譯成拼音
        if (preg_match ( '/[\x7f-\xff]+/', $user ['uname'] )) {
            // 昵稱和呢稱拼音儲存到搜索欄位
            $user ['search_key'] = $user ['uname'] . ' ' . model ( 'PinYin' )->Pinyin ( $user ['uname'] );
        } else {
            $user ['search_key'] = $user ['uname'];
        }
        // 添加使用者操作
        $result = $this->add ( $user );
        if (! $result) {
            $this->error = L ( 'PUBLIC_ADD_USER_FAIL' ); // 添加使用者失敗
            return false;
        } else {
            // 添加部門關聯資訊
            model ( 'Department' )->updateUserDepartById ( $result, intval ( $_POST ['department_id'] ) );
            // 添加使用者組關聯資訊
            if (! empty ( $_POST ['user_group'] )) {
                model ( 'UserGroupLink' )->domoveUsergroup ( $result, implode ( ',', $_POST ['user_group'] ) );
            }
            // 添加使用者職業關聯資訊
            if (! empty ( $_POST ['user_category'] )) {
                model ( 'UserCategory' )->updateRelateUser ( $result, $_POST ['user_category'] );
            }
            return true;
        }
    }

    /**
     * 密碼加密處理
     *
     * @param string $password
     *          密碼
     * @param string $salt
     *          密碼附加參數，默認為11111
     * @return string 加密後的密碼
     */
    public function encryptPassword($password, $salt = '11111') {
        return md5 ( md5 ( $password ) . $salt );
    }

    /**
     * 禁用指定使用者賬號操作
     *
     * @param array $ids
     *          禁用的使用者ID陣列
     * @return boolean 是否禁用成功
     */
    public function deleteUsers($ids) {
        // 處理資料
        $uid_array = $this->_parseIds ( $ids );
        // 進行使用者假刪除
        $map ['uid'] = array (
            'IN',
            $uid_array
        );
        $save ['is_del'] = 1;
        $result = $this->where ( $map )->save ( $save );
        $this->cleanCache ( $uid_array );
        if (! $result) {
            $this->error = L ( 'PUBLIC_DISABLE_ACCOUNT_FAIL' ); // 禁用帳號失敗
            return false;
        } else {
            $this->deleteUserWeiBoData ( $uid_array );
            $this->dealUserAppData ( $uid_array );
            return true;
        }
    }
    /**
     * 徹底刪除指定使用者賬號操作
     *
     * @param array $ids
     *          徹底刪除的使用者ID陣列
     * @return boolean 是否徹底刪除成功
     */
    public function trueDeleteUsers($ids) {
        // 處理資料
        $uid_array = $this->_parseIds ( $ids );
        // 進行使用者假刪除
        $map ['uid'] = array (
            'IN',
            $uid_array
        );
        $result = $this->where ( $map )->delete ();
        $this->cleanCache ( $uid_array );
        if (! $result) {
            $this->error = L ( 'PUBLIC_REMOVE_COMPLETELY_FAIL' ); // 徹底刪除帳號失敗
            return false;
        } else {
            $this->trueDeleteUserCoreData ( $uid_array );
            return true;
        }
    }

    /**
     * 恢復指定使用者賬號操作
     *
     * @param array $ids
     *          恢復的使用者UID陣列
     * @return boolean 是否恢覆成功
     */
    public function rebackUsers($ids) {
        // 處理資料
        $uid_array = $this->_parseIds ( $ids );
        // 恢複使用者假刪除
        $map ['uid'] = array (
            'IN',
            $uid_array
        );
        $save ['is_del'] = 0;
        $result = $this->where ( $map )->save ( $save );
        $this->cleanCache ( $uid_array );
        if (! $result) {
            $this->error = L ( 'PUBLIC_RECOVER_ACCOUNT_FAIL' ); // 恢覆帳號失敗
            return false;
        } else {
            $this->rebackUserWeiBoData ( $uid_array );
            $this->dealUserAppData ( $uid_array, 'rebackUserAppData' );
            return true;
        }
    }

    /**
     * 改變使用者的啟用狀態
     *
     * @param array $ids
     *          使用者UID陣列
     * @param integer $type
     *          使用者的啟用狀態，0表示未啟用；1表示啟用
     * @return boolean 是否操作成功
     */
    public function activeUsers($ids, $type = 1) {
        // 類型參數僅僅只能為0或1
        if ($type != 1 && $type != 0) {
            $this->error = L ( 'PUBLIC_ILLEGAL_TYPE_INDEX' ); // 非法的type參數
            return false;
        }
        // 處理資料
        $uid_array = $this->_parseIds ( $ids );
        // 改變指定使用者的啟用狀態
        $map ['uid'] = array (
            'IN',
            $uid_array
        );
        $result = $this->where ( $map )->setField ( 'is_active', $type );
        $this->cleanCache ( $uid_array );

        if (! $result) {
            $this->error = L ( 'PUBLIC_ACTIVATE_USER_FAIL' ); // 啟用使用者失敗
            return false;
        } else {
            return true;
        }
    }

    /**
     * 刪除指定使用者的檔案資訊
     *
     * @param array $ids
     *          使用者UID陣列
     * @return boolean 是否刪除使用者檔案成功
     */
    public function deleteUserProfile($ids) {
        // 處理資料
        $uid_array = $this->_parseIds ( $ids );
        // 刪除指定使用者的檔案資訊
        $map ['uid'] = array (
            'IN',
            $uid_array
        );
        $result = D ( 'UserProfileSetting' )->where ( $map )->delete ();
        if (! $result) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 轉移指定使用者到指定部門
     *
     * @param array $uids
     *          使用者UID陣列
     * @param integer $user_department
     *          部門ID
     * @return boolean 是否轉移成功
     */
    public function domoveDepart($uids, $user_department) {
        // TODO:後期需要加入清理快取操作
        $uids = explode ( ',', $uids );
        foreach ( $uids as $uid ) {
            model ( 'Department' )->updateUserDepartById ( $uid, $user_department );
        }

        return true;
    }

    /**
     * 清除指定使用者UID的快取
     *
     * @param array $uids
     *          使用者UID陣列
     * @return boolean 是否清除快取成功
     */
    public function cleanCache($uids) {
        if (empty ( $uids )) {
            return false;
        }
        ! is_array ( $uids ) && $uids = explode ( ',', $uids );
        foreach ( $uids as $uid ) {
            model ( 'Cache' )->rm ( 'ui_' . $uid );
            static_cache ( 'user_info_' . $uid, false );

            $keys = model('Cache')->get('getUserDataByCache_keys_'.$uid);
            foreach ($keys as $k){
                model ( 'Cache' )->rm ( $k );
            }
            model('Cache')->rm('getUserDataByCache_keys_'.$uid);
        }

        return true;
    }

    /**
     * 獲取指定使用者所感興趣人的UID陣列
     *
     * @param integer $uid
     *          指定使用者UID
     * @param integer $num
     *          感興趣人的個數
     * @return array 感興趣人的UID陣列
     */
    public function relatedUser($uid, $num) {
        $user_info = $this->getUserInfo ( $uid );
        if (! $user_info || $num <= 0) {
            return false;
        }

        // $map['department_id'] = $user_info['department_id'];
        $map ['uid'] = array (
            'NEQ',
            $uid
        );
        $map ['is_active'] = 1;

        $uids = $this->where ( $map )->limit ( $num )->getAsFieldArray ( 'uid' );

        if (! $uids || count ( $uids ) < $num) {
            $limit = count ( $uids ) + $num + 1;
            $sql = "SELECT `uid` FROM {$this->tablePrefix}user
                WHERE uid >= ((SELECT MAX(uid) FROM {$this->tablePrefix}user) - (SELECT MIN(uid) FROM {$this->tablePrefix}user)) * RAND() + (SELECT MIN(uid) FROM {$this->tablePrefix}user)
                AND is_active = 1 LIMIT {$limit}";
            $random_uids = $this->query ( $sql );
            $random_uids = getSubByKey ( $random_uids, 'uid' );
            $uids = is_array ( $uids ) ? array_merge ( $uids, $random_uids ) : $random_uids;
        }
        // 去除可能由隨機產生的登入使用者UID
        unset ( $uids [array_search ( $uid, $uids )] );
        // 擷取感興趣人的個數
        $uids = array_slice ( $uids, 0, $num );
        // 批量獲取指定使用者資訊
        $related_users = $this->getUserInfoByUids ( $uids );

        return $related_users;
    }

    /**
     * 處理使用者UID資料為陣列形式
     *
     * @param mix $ids
     *          使用者UID
     * @return array 陣列形式的使用者UID
     */
    private function _parseIds($ids) {
        // 轉換數字ID和字元串形式ID串
        if (is_numeric ( $ids )) {
            $ids = array (
                $ids
            );
        } else if (is_string ( $ids )) {
            $ids = explode ( ',', $ids );
        }
        // 過濾、去重、去空
        if (is_array ( $ids )) {
            foreach ( $ids as $id ) {
                $id_array [] = intval ( $id );
            }
        }
        $id_array = array_unique ( array_filter ( $id_array ) );

        if (count ( $id_array ) == 0) {
            $this->error = L ( 'PUBLIC_INSERT_INDEX_ILLEGAL' ); // 傳入ID參數不合法
            return false;
        } else {
            return $id_array;
        }
    }

    /**
     * 獲取ts_user表的資料，帶快取功能
     *
     * @param array $map
     *          查詢條件
     * @return array 指定使用者的相關資訊
     */
    function getUserDataByCache($map, $field = "*"){
        $key = 'userData_';
        foreach ($map as $k=>$v){
            $key .= $k.$v;
        }
        if($field!='*'){
            $key .= '_'.str_replace(array("`",","," "), '', $field);
        }

        $user = model('Cache')->get($key);
        if($user==false){
            $user = $this->where ( $map )->field ( $field )->find ();
            model('Cache')->set($key, $user,86400);  //快取24小時
            //儲存key和uid的關係，以方便後面使用者資料變化時可以刪除這些快取
            if(isset($user['uid'])){
                $keys = model('Cache')->get('getUserDataByCache_keys_'.$user['uid']);
                $keys[$key] = $key;
                model('Cache')->set('getUserDataByCache_keys_'.$user['uid'], $keys);
            }
        }

        return $user;
    }
    /**
     * 獲取指定使用者的相關資訊
     *
     * @param array $map
     *          查詢條件
     * @return array 指定使用者的相關資訊
     */
    private function _getUserInfo($map, $field = "*") {
        $user = $this->getUserDataByCache($map, $field);
        unset ( $user ['password'] );
        if (! $user) {
            $this->error = L ( 'PUBLIC_GET_INFORMATION_FAIL' ); // 獲取使用者資訊失敗
            return false;
        } else {
            $uid = $user ['uid'];
            $user = array_merge ( $user, model ( 'Avatar' )->init ( $user ['uid'] )->getUserAvatar () );
            $user ['avatar_url'] = U ( 'public/Attach/avatar', array (
                'uid' => $user ["uid"]
            ) );
            $user ['space_url'] = ! empty ( $user ['domain'] ) ? U ( 'public/Profile/index', array (
                'uid' => $user ["domain"]
            ) ) : U ( 'public/Profile/index', array (
                'uid' => $user ["uid"]
            ) );
            $user ['space_link'] = "<a href='" . $user ['space_url'] . "' target='_blank' uid='{$user['uid']}' event-node='face_card'>" . $user ['uname'] . "</a>";
            $user ['space_link_no'] = "<a href='" . $user ['space_url'] . "' title='" . $user ['uname'] . "' target='_blank'>" . $user ['uname'] . "</a>";
            // 使用者勳章
            $user ['medals'] = model ( 'Medal' )->getMedalByUid ( $user ['uid'] );
            // 使用者認證圖示
            $groupIcon = array ();
            $userGroup = model ( 'UserGroupLink' )->getUserGroupData ( $uid );
            $user ['api_user_group'] = $userGroup [$uid];
            $user ['user_group'] = $userGroup [$uid];
            foreach ( $userGroup [$uid] as $value ) {
                $groupIcon [] = '<img title="' . $value ['user_group_name'] . '" src="' . $value ['user_group_icon_url'] . '" style="width:auto;height:auto;display:inline;cursor:pointer;" />';
            }
            $user ['group_icon'] = implode ( '&nbsp;', $groupIcon );

            model ( 'Cache' )->set ( 'ui_' . $uid, $user, 600 );
            static_cache ( 'user_info_' . $uid, $user );
            return $user;
        }
    }

    /**
     * * API使用 **
     */
    /**
     * 格式化API資料
     *
     * @param array $data
     *          API資料
     * @param integer $uid
     *          粉絲使用者UID
     * @param integer $mid
     *          登入使用者UID
     * @return array API輸出資料
     */
    public function formatForApi($data, $uid, $mid = '') {
        empty ( $mid ) && $mid = $GLOBALS ['ts'] ['mid'];
        $userInfo = model ( 'User' )->getUserInfo ( $uid );
        $data ['uname'] = $userInfo ['uname'];
        $data ['space_url'] = $userInfo ['space_url'];
        $data ['follow_state'] = model ( 'Follow' )->getFollowState ( $mid, $uid ); // 登入使用者與其粉絲之間的關注狀態
        $data ['profile'] = model ( 'UserProfile' )->getUserProfileForApi ( $uid );
        $data ['avatar_big'] = $userInfo ['avatar_big'];
        $data ['avatar_middle'] = $userInfo ['avatar_middle'];
        $data ['avatar_small'] = $userInfo ['avatar_small'];
        $count = model ( 'UserData' )->getUserData ( $uid );
        empty ( $count ['following_count'] ) && $count ['following_count'] = 0;
        empty ( $count ['follower_count'] ) && $count ['follower_count'] = 0;
        empty ( $count ['feed_count'] ) && $count ['feed_count'] = 0;
        empty ( $count ['favorite_count'] ) && $count ['favorite_count'] = 0;
        empty ( $count ['unread_atme'] ) && $count ['weibo_count'] = 0;
        $data ['count_info'] = $count;

        return $data;
    }

    /**
     * * 用於搜索引擎 **
     */
    /**
     * 搜索使用者
     *
     * @param string $key
     *          關鍵字
     * @param integer $follow
     *          關注狀態值
     * @param integer $limit
     *          結果集數目，默認為100
     * @param integer $max_id
     *          主鍵最大值
     * @param string $type
     *          類型
     * @param integer $noself
     *          搜索結果是否包含登入使用者，默認為0
     * @return array 使用者列表資料
     */
    public function searchUser($key = '', $follow = 0, $limit = 100, $max_id = '', $type = '', $noself = '0', $page, $atme) {
        // 判斷類型？
        switch ($type) {
        case '' :
            $where = " (search_key LIKE '%{$key}%')";
            // 過濾未啟用和未審核的使用者
            // if($atme == 'at') {
            $where .= " AND is_active=1 AND is_audit=1 AND is_init=1";
            // }
            if (! empty ( $max_id )) {
                $where .= " AND uid < " . intval ( $max_id );
            }
            if (! empty ( $noself )) {
                $where .= " AND uid !=" . intval ( $GLOBALS ['ts'] ['mid'] );
            }
            if ($follow == 1) {
                // 只選擇我關注的人
                $where .= " AND uid IN (SELECT fid FROM " . $this->tablePrefix . "user_follow WHERE uid = '{$GLOBALS['ts']['mid']}')";
            }
            if ($page) {
                // 分頁形式
                $nameUserlist = $this->where ( $where )->field ( 'uid' )->limit ( $limit )->order ( 'uid desc' )->findAll (); // 按使用者名搜
                if ($nameUserlist) {
                    $nameUserIdList = getSubByKey ( $nameUserlist, 'uid' );
                } else {
                    $nameUserIdList = array ();
                }

                $datas ['name'] = $key;
                $tagid = D ( 'tag' )->where ( $datas )->getField ( 'tag_id' );
                $maps ['app'] = 'public';
                $maps ['table'] = 'user';
                $maps ['tag_id'] = $tagid;
                $tagUserlist = D ( 'app_tag' )->where ( $maps )->field ( 'row_id as uid' )->order ( 'row_id desc' )->findAll (); // 按標籤搜
                if ($tagUserlist) {
                    $tagUserIdList = getSubByKey ( $tagUserlist, 'uid' );
                } else {
                    $tagUserIdList = array ();
                }

                $uidList = array_unique ( array_merge ( $tagUserIdList, $nameUserIdList ) );
                $data ['uid'] = array (
                    'in',
                    $uidList
                );
                $list = $this->where ( $data )->field ( 'uid' )->limit ( $limit )->order ( 'uname ASC' )->findpage ( $page );
            } else {
                // 未分頁形式
                $list ['data'] = $this->where ( $where )->field ( 'uid' )->limit ( $limit )->order ( 'uname ASC' )->findAll ();
            }
            break;
        }
        // 添加使用者資訊
        foreach ( $list ['data'] as &$v ) {
            $v = $this->getUserInfoForSearch ( $v ['uid'], 'uid,uname,sex,location,domain,search_key' );
        }

        return $list;
    }

    /**
     * 根據標示符(uid或uname或email或domain)獲取使用者資訊
     *
     * 首先檢查快取(快取ID: user_使用者uid / user_使用者uname), 然後查詢資料庫(並設定快取).
     *
     * @param string|int $identifier
     *          標示符內容
     * @param string $identifier_type
     *          標示符類型. (uid, uname, email, domain之一)
     */
    public function getUserByIdentifier($identifier, $identifier_type = 'uid') {
        if ($identifier_type == 'uid') {
            return $this->getUserInfo ( $identifier );
        } elseif ($identifier_type == 'uname') {
            return $this->getUserInfoByName ( $identifier );
        } elseif ($identifier_type == 'email') {
            return $this->getUserInfoByEmail ( $identifier );
        } elseif ($identifier_type == 'domain') {
            return $this->getUserInfoByDomain ( $identifier );
        }
    }

    /**
     * 獲取最後錯誤資訊
     *
     * @return string 最後錯誤資訊
     */
    public function getLastError() {
        return $this->error;
    }
    /**
     * 假刪除使用者微博資料
     *
     * @param int $uid
     *          使用者UID
     * @return BOOL
     */
    public function deleteUserWeiBoData($uid_array) {
        $map ['uid'] = array (
            'in',
            $uid_array
        );
        $map ['is_del'] = 0;
        $feed_id_list = model ( 'Feed' )->where ( $map )->field ( 'feed_id' )->findAll ();
        if (empty ( $feed_id_list ))
            return true; // 如果沒有可刪除的微博，直接返回

        $idArr = getSubByKey ( $feed_id_list, 'feed_id' );
        $return = model ( 'Feed' )->doEditFeed ( $idArr, 'delFeed', L ( 'PUBLIC_STREAM_DELETE' ) );
        return $return;
    }

    /**
     * 恢複使用者的微博資料
     *
     * @param int $uid
     *          使用者UID
     * @return BOOL
     */
    public function rebackUserWeiBoData($uid_array) {
        $map ['uid'] = array (
            'in',
            $uid_array
        );
        $map ['is_del'] = 1;
        $feed_id_list = model ( 'Feed' )->where ( $map )->field ( 'feed_id' )->findAll ();
        if (empty ( $feed_id_list ))
            return true; // 如果沒有可恢復的微博，直接返回

        $idArr = getSubByKey ( $feed_id_list, 'feed_id' );
        $return = model ( 'Feed' )->doEditFeed ( $idArr, 'feedRecover', L ( 'PUBLIC_RECOVER' ) );
        return $return;
    }

    /**
     * 徹底刪除使用者的微博資料
     *
     * @param int $uid
     *          使用者UID
     * @return BOOL
     */
    public function trueDeleteUserCoreData($uid_array) {
        $map ['uid'] = array (
            'in',
            $uid_array
        );

        //刪除微博
        $feed_id_list = model ( 'Feed' )->where ( $map )->field ( 'feed_id' )->findAll ();
        if (!empty ( $feed_id_list )){
            $idArr = getSubByKey ( $feed_id_list, 'feed_id' );
            $return = model ( 'Feed' )->doEditFeed ( $idArr, 'deleteFeed', L ( 'PUBLIC_STREAM_DELETE' ) );
        }
        unset($map);

        $tableStr = $this->_getUserField();
        $tableArr = explode('|', $tableStr);
        $uidStr = implode(',', $uid_array);
        $prefix = C('DB_PREFIX');
        foreach ($tableArr as $table){
            $vo = explode(':', $table);

            $sql = 'DELETE FROM '.$prefix.$vo[0].' WHERE '.$vo[1].' IN ('.$uidStr.')';
            $this->execute($sql);
        }
        return $return;
        }

        /**
         * 刪除或者恢複使用者應用資料
         *
         * @param array $uid_array
         *          使用者UID
         * @param string $type
         *          操作類型：deleteUserAppData 刪除資料
         *          rebackUserAppData 恢復資料
         *          trueDeleteUserAppData 徹底刪除資料
         * @return void
         */
        public function dealUserAppData($uid_array, $type = 'deleteUserAppData') {
            // 取全部APP資訊
            $appList = model ( 'App' )->where('status=1')->field ( 'app_name' )->findAll ();

            foreach ( $appList as $app ) {
                $appName = strtolower ( $app ['app_name'] );
                $className = ucfirst ( $appName );

                $dao = D ( $className . 'Protocol', $className, false );
                if (method_exists ( $dao, $type )) {
                    $dao->$type ( $uid_array );
        }
        unset ( $dao );
        }
        }
        private function _getUserField() {
            $str = 'user_follow:fid';  //特殊情況下的配置

            $dbName = C ( 'DB_NAME' );
            $sql = "SELECT TABLE_NAME,COLUMN_NAME FROM information_schema.`COLUMNS` WHERE TABLE_SCHEMA='$dbName' AND COLUMN_NAME LIKE '%uid%'";
            $list = M ()->query ( $sql );
            if (empty ( $list )) {
                $str .= '|atme:uid|attach:uid|blog:uid|blog_category:uid|channel:uid|channel_follow:uid|check_info:uid|collection:uid|comment:app_uid|comment:uid|comment:to_uid|credit_user:uid|denounce:uid|denounce:fuid|develop:uid|diy_page:uid|diy_widget:uid|event:uid|event_photo:uid|event_user:uid|feed:uid|feedback:uid|find_password:uid|invite_code:inviter_uid|invite_code:receiver_uid|login:uid|login:type_uid|login_logs:uid|login_record:uid|medal_user:uid|message_content:from_uid|message_list:from_uid|message_member:member_uid|notify_email:uid|notify_message:uid|online:uid|online_logs:uid|online_logs_bak:uid|poster:uid|sitelist_site:uid|survey_answer:uid|task_receive:uid|task_user:uid|template_record:uid|tipoff:uid|tipoff:bonus_uid|tipoff_log:uid|tips:uid|user:uid|user_app:uid|user_blacklist:uid|user_category_link:uid|user_change_style:uid|user_credit_history:uid|user_data:uid|user_department:uid|user_follow:uid|user_follow_group:uid|user_follow_group_link:uid|user_group_link:uid|user_official:uid|user_online:uid|user_privacy:uid|user_profile:uid|user_verified:uid|vote:uid|vote_user:uid|vtask:uid|vtask:bonus_uid|vtask_log:uid|weiba:uid|weiba:admin_uid|weiba_apply:follower_uid|weiba_apply:manager_uid|weiba_favorite:uid|weiba_favorite:post_uid|weiba_follow:follower_uid|weiba_log:uid|weiba_post:post_uid|weiba_post:last_reply_uid|weiba_reply:post_uid|weiba_reply:uid|weiba_reply:to_uid|x_article:uid|x_logs:uid';
        } else {
            $prefix = C('DB_PREFIX');
            foreach ( $list as $vo ) {
                $vo['TABLE_NAME'] = str_replace($prefix,'', $vo['TABLE_NAME']);
                $str .= '|' . $vo ['TABLE_NAME'] . ':' . $vo ['COLUMN_NAME'];
        }
        }

        return $str;
        }
        }
