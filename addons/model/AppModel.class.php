<?php
/**
 * 應用模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AppModel extends Model {
    protected $tableName = 'app';
    protected $fields = array (
        0 => 'app_id',
        1 => 'app_name',
        2 => 'app_alias',
        3 => 'description',
        4 => 'status',
        5 => 'host_type',
        6 => 'app_entry',
        7 => 'icon_url',
        8 => 'large_icon_url',
        9 => 'admin_entry',
        10 => 'statistics_entry',
        11 => 'display_order',
        12 => 'ctime',
        13 => 'version',
        14 => 'api_key',
        15 => 'secure_key',
        16 => 'company_name',
        17 => 'has_mobile',
        18 => 'child_menu',
        19 => 'add_front_top',
        20 => 'add_front_applist',
        '_pk' => 'app_id'
    );
    public static $defaultApp = array (); // 默認應用欄位
    public $_host_type = array (); // 應用類型欄位

    /**
     * 初始化 - 用於雙語處理
     *
     * @return void
     */
    public function _initialize() {
        $this->_host_type = array (
            0 => L ( 'PUBLIC_LOCAL_APP' ),
            1 => L ( 'PUBLIC_REMOTE_APP' )
        ); // 本地應用，遠端應用
    }

    /**
     * 獲取應用列表 - 分頁型
     *
     * @param array $map
     *          查詢條件
     * @param integer $limit
     *          每頁顯示的結果數
     * @param string $order
     *          排序條件
     * @return array 應用列表分頁資料
     */
    public function getAppByPage($map, $limit = 10, $order = 'app_id DESC') {
        $list = $this->where ( $map )->field ( 'app_id' )->order ( $order )->findPage ( $limit );
        $list ['data'] = $this->getInfoByList ( $list ['data'], true );

        return $list;
    }

    /**
     * 獲取指定使用者的應用列表 - 不分頁型
     *
     * @param integer $uid
     *          使用者UID
     * @param integer $inweb
     *          是否為網頁端，默認為1
     * @return array 指定使用者的應用列表
     */
    public function getUserApp($uid, $inweb = 1) {
        $uid = empty ( $uid ) ? $_SESSION ['mid'] : $uid;
        $table = $this->tablePrefix . 'user_app AS a LEFT JOIN ' . $this->tablePrefix . 'app AS b ON a.app_id = b.app_id';
        $map ['a.uid'] = $uid;
        $map ['b.status'] = 1;
        $map ['a.inweb'] = intval ( $inweb );
        $list = $this->table ( $table )->where ( $map )->findAll ();

        return $list;
    }

    /**
     * 獲取指定使用者的應用列表 - 分頁型
     *
     * @param integer $uid
     *          使用者UID
     * @param integer $limit
     *          分頁的結果集數目，默認為10
     * @param integer $inweb
     *          是否為網頁端，默認為1
     * @return array 指定使用者的應用列表
     */
    public function getUserAppByPage($uid, $limit = 10, $inweb = 1) {
        $uid = empty ( $uid ) ? $_SESSION ['mid'] : $uid;
        $map ['a.uid'] = $uid;
        $map ['a.inweb'] = intval ( $inweb );
        $map ['b.status'] = 1;
        $table = $this->tablePrefix . 'user_app AS a LEFT JOIN ' . $this->tablePrefix . 'app AS b ON a.app_id = b.app_id';
        $list = $this->table ( $table )->where ( $map )->findPage ( $limit );
        $list ['data'] = $this->getInfoByList ( $list ['data'], true );

        return $list;
    }

    /**
     * 獲取指定使用者在前臺可管理的應用列表
     *
     * @param integer $uid
     *          使用者UID
     * @return array 獲取指定使用者有管理許可權的應用列表
     */
    public function getManageApp($uid) {
        // 靜態快取
        if ($list = static_cache ( 'manage_app_' . $uid )) {
            return $list;
        }
        // 指定使用者的許可權
        $rules = model ( 'Permission' )->loadRule ( $uid );
        // 管理許可權節點
        $manageApp = D ( 'permission_node' )->where ( "rule='manage'" )->field ( 'appname' )->getAsFieldArray ( 'appname' );
        // 獲取相應的應用列表
        if (! empty ( $manageApp )) {
            $apps = array ();
            foreach ( $manageApp as $v ) {
                if ($rules [$v] ['admin'] ['manage']) {
                    $apps [] = $v;
                }
            }

            if (empty ( $apps )) {
                $list = array ();
            } else {
                $map ['_string'] = " app_name IN ('" . implode ( "','", $apps ) . "')";
                $list = $this->getAppList ( $map );
            }
        }

        empty ( $list ) && $list = array ();

        static_cache ( 'manage_app_' . $uid, $list );

        return $list;
    }

    /**
     * 獲取所有應用列表 - 不分頁型
     *
     * @param array $map
     *          查詢條件
     * @param string $limit
     *          顯示結果集數目，默認不設定
     * @return array 應用列表資料
     */
    public function getAppList($map = array(), $limit = '') {
        $list = static_cache ( 'get_app_list' );
        if ($list == false) {
            $listorder = $this->where ( $map )->field ( 'app_id' )->order ( 'app_id DESC' );

            // 根據條件獲取相應結果集
            if (! $limit) {
                $list = $listorder->limit ( $limit )->findAll ();
            } else {
                $list = $listorder->findAll ();
            }
            static_cache ( 'get_app_list', $list );
        }

        // 組裝資料
        if (! empty ( $list )) {
            foreach ( $list as $k => $v ) {
                $list [$k] = $this->getAppById ( $v ['app_id'] );
            }
        }

        return $list;
    }

    /**
     * 批量獲取應用資訊
     *
     * @param array $list
     *          應用列表陣列，其中必須包含app_id欄位值
     * @param boolean $used
     *          是否獲取應用的使用情況，默認false
     * @return array 應用資訊陣列
     */
    public function getInfoByList($list, $used = false) {
        $r = array ();
        if (empty ( $list ))
            return $r;
        foreach ( $list as $v ) {
            $r [] = $this->getAppById ( $v ['app_id'], $used );
        }

        return $r;
    }

    /**
     * 獲取已經安裝應用的Hash陣列
     *
     * @param string $hashKey
     *          Hash中的Key值，默認為app_id
     * @param string $hashValue
     *          Hash中的Value值，默認為app_alias
     * @param array $map
     *          查詢條件
     * @return array 安裝應用的資訊
     */
    public function getAppHash($hashKey = 'app_id', $hashValue = 'app_alias', $map = array()) {
        $list = $this->getAppList ( $map );
        $r = array ();
        foreach ( $list as $v ) {
            $r [$v [$hashKey]] = $v [$hashValue];
        }

        return $r;
    }

    /**
     * 通過應用名稱，獲取應用的資訊
     *
     * @param string $appname
     *          應用名稱
     * @return array 應用的相應資訊
     */
    public function getAppByName($appname) {
        // 驗證資料的正確性
        if (empty ( $appname ))
            return array ();
        // 判斷靜態快取是否存在
        $info = static_cache ( 'app_Appinfo_' . $appname );
        if (empty ( $info )) {
            // 判斷快取是否存在
            $info = model ( 'Cache' )->get ( 'Appinfo_' . $appname );
            if (empty ( $info )) {
                $map ['app_name'] = $appname;
                $info = $this->where ( $map )->find ();
                // 資料格式化
                if ($info ['host_type'] == "0") {
                    // 本地應用
                    $info ['app_entry'] = U ( $info ['app_name'] . '/' . $info ['app_entry'] );
                    $info ['icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app.png';
                    $info ['large_icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app_large.png';
                    $info ['small_icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app_small.png';
                    $info ['iphone_icon'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_iphone.png';
                    $info ['android_icon'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_android.png';
                }
                // 設定靜態快取
                static_cache ( 'app_Appinfo_' . $appname, $info );
                // 設定快取
                model ( 'Cache' )->set ( 'Appinfo_' . $appname, $info );
                // 刪除應用Hask表快取 - APP名稱與ID的快取
                model ( 'Cache' )->rm ( 'AppHash_NameID' );
            }
        }

        // 是否獲取應用的使用情況
        // $used && $info ['used'] = model ( 'UserApp' )->getUsed ( $app_id );
        return $info;
    }

    /**
     * 根據應用ID為應用做快取，快取KEY為app_Appinfo_[應用ID]，Appinfo_[應用ID]
     *
     * @param integer $app_id
     *          應用ID
     * @param boolean $used
     *          是否獲取應用的使用情況，默認false
     * @return array 返回指定應用的相關資訊
     */
    public function getAppById($app_id, $used = false) {
        // 驗證資料的正確性
        if (empty ( $app_id ))
            return array ();
        // 判斷靜態快取是否存在
        $info = static_cache ( 'app_Appinfo_' . $app_id );
        if (empty ( $info )) {
            // 判斷快取是否存在
            $info = model ( 'Cache' )->get ( 'Appinfo_' . $app_id );
            if (empty ( $info )) {
                $map ['app_id'] = $app_id;
                $info = $this->where ( $map )->find ();
                // 資料格式化
                if ($info ['host_type'] == "0") {
                    // 本地應用
                    $info ['app_entry'] = U ( $info ['app_name'] . '/' . $info ['app_entry'] );
                    $info ['icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app.png';
                    $info ['large_icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app_large.png';
                    $info ['small_icon_url'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_app_small.png';
                    $info ['iphone_icon'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_iphone.png';
                    $info ['android_icon'] = SITE_URL . '/apps/' . $info ['app_name'] . '/Appinfo/icon_android.png';
                }
                // 設定靜態快取
                static_cache ( 'app_Appinfo_' . $app_id, $info );
                // 設定快取
                model ( 'Cache' )->set ( 'Appinfo_' . $app_id, $info );
                // 刪除應用Hask表快取 - APP名稱與ID的快取
                model ( 'Cache' )->rm ( 'AppHash_NameID' );
            }
        }

        // 是否獲取應用的使用情況
        // $used && $info ['used'] = model ( 'UserApp' )->getUsed ( $app_id );

        return $info;
    }

    /**
     * 獲取系統默認配置應用列表
     *
     * @return array 系統默認應用列表
     */
    public function getDefaultApp() {
        // 獲取靜態快取
        $list = static_cache ( 'app_defaultapp' );
        if (! empty ( $list )) {
            return $list;
        }
        // 獲取快取
        $list = model ( 'Cache' )->get ( 'defaultApp' );
        if (empty ( $list )) {
            $map ['status'] = 1;
            $list = $this->where ( $map )->field ( 'app_id' )->findAll ();
            if (empty ( $list )) {
                $list = array ();
            } else {
                $list = $this->getInfoByList ( $list );
            }
            model ( 'Cache' )->set ( 'defaultApp', $list );
        }

        static_cache ( 'app_defaultapp', $list );

        return $list;
    }

    /**
     * 清除快取
     *
     * @param array $ids
     *          應用ID陣列
     * @return boolean 是否清除快取
     */
    public function cleanCache($ids) {
        // 清空所有快取
        if (empty ( $ids )) {
            $list = $this->field ( 'app_id' )->findAll ();
            foreach ( $list as $l ) {
                model ( 'Cache' )->rm ( 'Appinfo_' . $l ['app_id'] );
            }
        }
        if (! is_array ( $ids )) {
            model ( 'Cache' )->rm ( 'Appinfo_' . $ids );
        }
        foreach ( $ids as $v ) {
            model ( 'UserApp' )->cleanUsed ( $v );
            model ( 'Cache' )->rm ( 'Appinfo_' . $v );
        }
        model ( 'Cache' )->rm ( 'AppHash_NameID' );
        model ( 'Cache' )->rm ( 'defaultApp' );
        return true;
    }

    /**
     * 獲取應用的配置列表
     *
     * @return array 應用的配置列表資訊
     */
    public function getConfigList() {
        $map ['admin_entry'] = array (
            'NEQ',
            ''
        );
        $r = array ();
        $list = $this->getAppList ( $map );
        if (! empty ( $list )) {
            foreach ( $list as $v ) {
                $r [$v ['app_alias']] = U ( $v ['admin_entry'] );
            }
        }

        return $r;
    }

    /**
     * 獲取未安裝應用列表
     *
     * @return array 未安裝應用列表
     */
    public function getUninstallList() {
        $uninstalled = array ();

        $installed = $this->field ( 'app_id' )->order ( 'app_id DESC' )->findAll ();
        foreach ( $installed as $k => $v ) {
            $installed [$k] = $this->getAppById ( $v ['app_id'] );
        }
        $installed = getSubByKey ( $installed, 'app_name' );
        // 默認應用，不能安裝解除安裝
        $installed = empty ( $installed ) ? C ( 'DEFAULT_APPS' ) : array_merge ( $installed, C ( 'DEFAULT_APPS' ) );

        require_once ADDON_PATH . '/library/io/Dir.class.php';
        $dirs = new Dir ( APPS_PATH );
        $dirs = $dirs->toArray ();
        foreach ( $dirs as $v ) {
            if ($v ['isDir'] && ! in_array ( $v ['filename'], $installed )) {
                if ($info = $this->__getAppInfo ( $v ['filename'] )) {
                    $uninstalled [] = $info;
                }
            }
        }

        return $uninstalled;
    }

    /**
     * 獲取應用資訊
     *
     * @param string $path_name
     *          應用路徑名稱
     * @param boolean $using_lowercase
     *          返回鍵值為大寫還是小寫，默認為小寫
     * @return array 指定應用的相關資訊
     */
    public function __getAppInfo($path_name, $using_lowercase = true) {
        $filename = APPS_PATH . '/' . $path_name . '/Appinfo/info.php';

        if (is_file ( $filename )) {
            $info = include_once $filename;

            $info ['HOST_TYPE_ALIAS'] = $this->_host_type [$info ['HOST_TYPE']];
            $info ['APP_ALIAS'] = $info ['NAME'];
            $info ['PATH_NAME'] = $path_name;
            $info ['APP_NAME'] = $path_name;
            return $using_lowercase ? array_change_key_case ( $info ) : array_change_key_case ( $info, CASE_UPPER );
        } else {
            return false;
        }
    }

    /**
     * 儲存應用資訊資料
     *
     * @param array $data
     *          應用相關資料
     * @return boolean 是否儲存成功
     */
    public function saveApp($data) {
        foreach ( $data as $k => &$v ) {
            $v = ($k == 'description') ? htmlspecialchars ( $v ) : t ( $v );
        }

        if ($data ['host_type'] == 0 && ! is_dir ( APPS_PATH . '/' . $data ['app_name'] )) {
            return L ( 'PUBLIC_DIRECTORY_NOEXIST', array (
                'dir' => $data ['app_name']
            ) ); // {dir}目錄不存在
        }

        if (! empty ( $data ['app_id'] )) {
            // 更新應用資料操作
            $map = array ();
            $map ['app_id'] = $data ['app_id'];
            unset ( $data ['app_id'] );
            if ($this->where ( $map )->save ( $data )) {
                $this->cleanCache ( $map ['app_id'] );
                return true;
            } else {
                return L ( 'PUBLIC_DATA_UPGRADE_FAIL' ); // 資料更新失敗，可能未做任何修改
            }
        } else {
            // 清除快取
            F ( '_xdata_lget_pageKey', null );
            F ( '_xdata_lget_searchPageKey', null );
            // 新增加應用操作
            if ($this->isAppNameExist ( $data ['app_name'] )) {
                return L ( 'PUBLIC_APP_EXIST' ); // 應用已經存在
            }

            $oldInfo = $this->__getAppInfo ( $data ['app_name'] );
            // 固定資料內容處理
            empty ( $oldInfo ['child_menu'] ) && $oldInfo ['child_menu'] = array ();
            $data ['child_menu'] = serialize ( $oldInfo ['child_menu'] );
            $data ['has_mobile'] = intval ( $oldInfo ['has_mobile'] );

            $install_script = APPS_PATH . '/' . $data ['app_name'] . '/Appinfo/install.php';
            if (file_exists ( $install_script )) {
                include_once $install_script;
            }

            // 判斷是否需要自動補充導航的語言KEY：PUBLIC_APPNAME_應用名
            $lang ['key'] = 'PUBLIC_APPNAME_' . strtoupper ( $data ['app_name'] );
            $lang ['appname'] = 'PUBLIC';
            $lang ['filetype'] = 0;
            $lang ['zh-cn'] = $oldInfo ['name'];
            $lang ['en'] = ucfirst ( $data ['app_name'] );
            $lang ['zh-tw'] = $oldInfo ['name'];
            $res = model ( 'Lang' )->updateLangData ( $lang );

            // 清空語言快取
            if ($res == 2) {
                model ( 'Lang' )->createCacheFile ( $lang ['appname'], $lang ['filetype'] );
            }

            $data ['ctime'] = time ();
            // 為便於排序，將order設定為ID
            unset ( $data ['app_id'] );

            if ($res = $this->add ( $data )) {
                // 成功入庫之後執行的操作
                $GLOBALS ['appid'] = $res;
                $install_script = APPS_PATH . '/' . $data ['app_name'] . '/Appinfo/afterInstall.php';
                if (file_exists ( $install_script )) {
                    include_once $install_script;
                }

                $this->where ( '`app_id`=' . $res )->setField ( 'display_order', $res );
                return true;
            } else {
                return L ( 'PUBLIC_DATA_INSERT_FAIL' ); // 資料插入失敗
            }
        }
    }

    /**
     * 判斷指定應用是否已經安裝
     *
     * @param string $app_name
     *          應用名稱
     * @param integer $app_id
     *          應用ID
     * @return boolean 指定應用是否安裝
     */
    public function isAppNameExist($app_name = '', $app_id = '') {
        // 參數判斷
        if (empty ( $app_name ) && empty ( $app_id )) {
            $this->error = L ( 'PUBLIC_WRONG_DATA' ); // 錯誤的參數
            return false;
        }
        // 默認應用
        if (in_array ( $app_name, C ( 'DEFAULT_APPS' ) )) {
            return true;
        }
        // 使用者自定義安裝應用
        $list = $this->getAppList ();
        foreach ( $list as $v ) {
            if (! empty ( $app_name ) && ($v ['app_name'] == $app_name)) {
                return true;
            }
            if (! empty ( $app_id ) && ($v ['app_id'] == $app_id)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 判斷指定應用是否已經開啟
     *
     * @param string $app_name
     *          應用名稱
     * @param string $app_id
     *          應用ID
     * @return boolean 指定應用是否可用
     */
    public function isAppNameOpen($app_name = '', $app_id = '') {
        // 參數判斷
        if (empty ( $app_name ) && empty ( $app_id )) {
            $this->error = L ( 'PUBLIC_WRONG_DATA' ); // 錯誤的參數
            return false;
}
// 默認應用
if (in_array ( $app_name, C ( 'DEFAULT_APPS' ) )) {
    return true;
}
// 使用者自定義安裝應用
$list = $this->getAppList ();
foreach ( $list as $v ) {
    if ($v ['status'] == 0) {
        continue;
}
if (! empty ( $app_name ) && ($v ['app_name'] == $app_name)) {
    return true;
}
if (! empty ( $app_id ) && ($v ['app_id'] == $app_id)) {
    return true;
}
}

return false;
}

/**
 * 後臺解除安裝指定應用
 *
 * @param integer $app_id
 *          應用ID
 * @return boolean 是否解除安裝成功
 */
public function uninstall($app_id) {
    $map = array ();
    $map ['app_id'] = $app_id;
    $appinfo = $this->where ( $map )->find ();
    if (empty ( $appinfo )) {
        return L ( 'PUBLIC_APP_NOEXIST' ); // 應用不存在或未安裝
}
if ($this->where ( $map )->delete ()) {
    $uninstall_script = APPS_PATH . '/' . $appinfo ['app_name'] . '/Appinfo/uninstall.php';
    if (is_file ( $uninstall_script )) {
        include_once $uninstall_script;
}
// 刪除使用者應用表中的資料
$umap ['app_id'] = $app_id;
model ( 'UserApp' )->where ( $umap )->delete ();
// 刪除歷史搜索資料
$sm ['int01'] = $app_id;
D ( '' )->table ( $this->tablePrefix . 'search' )->where ( $sm )->delete ();

$this->cleanCache ( $app_id );
return true;
} else {
    return L ( 'PUBLIC_ADMIN_OPRETING_ERROR' ); // 操作失敗
}
}
}
