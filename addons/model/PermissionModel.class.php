<?php
/**
 * 許可權模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class PermissionModel {

    static protected $permission = array();         // 當前使用者所具有的許可權列表

    /**
     * 驗證許可權
     * @param string $action 動作節點
     * @return boolean 是否具有該動作節點的許可權
     */
    public function check($action) {
        // 驗證時載入當前應用 - 模組的許可權
        if(empty($this->option['app']) || empty($this->option['module'])) {
            return false;
        }
        // 判斷是否為擴展應用
        if(!in_array($this->option['app'], array('core'))) {
            // 判斷應用是否關閉
            $isOpen = model('App')->isAppNameOpen(strtolower($this->option['app']));
            if(!$isOpen) {
                return false;
            }
        }

        $permission = $this->loadRule($GLOBALS['ts']['mid']);

        if(isset($permission[$this->option['app']][$this->option['module']][$action])) {
            return true;
        }

        return false;
    }

    /**
     * 設定需要載入許可權的 應用 - 模組
     * @param string $type 應用 - 模組
     * @return object 配置後的許可權物件
     */
    public function load($type) {
        $type = explode('_', $type, 2);
        $this->option['app'] = $type[0];
        $this->option['module'] = $type[1];

        return $this;
    }

    /**
     * 設定自定義組資訊
     * @param string $group 自定義使用者組名稱，例如：群組內成員必須設定為member，否則按其當前使用者組許可權判斷
     * @return object 配置後的許可權物件
     */
    public function group($group = false) {
        $this->option['group'] = $group;
        return $this;
    }

    /**
     * 獲取指定使用者的許可權集合
     * @param integer $uid 使用者ID
     * @return array 指定使用者的許可權集合
     */
    public function loadRule($uid) {
        if(empty($uid)) {
            return false;
        }

        if(empty(self::$permission[$uid])) {
            $permission = model('Cache')->get('perm_user_'.$uid);
            if(!$permission) {
                $userGroupids = model('UserGroupLink')->getUserGroup($uid);
                $userGroup = model('UserGroup')->getUserGroup($userGroupids[$uid]);
                $permission = array();
                // 先處理應用內的使用者組
                if(!empty($this->option['group'])) {
                    $permission = $this->getGroupPermission($this->option['app'].'_'.$this->option['group']);
                }
                foreach($userGroup as $k => $v) {
                    if($v['user_group_type'] == 1) {
                        // 特殊組
                        $permission = $this->getGroupPermission($v['user_group_id']);
                        break;
                    }
                    $_p = $this->getGroupPermission($v['user_group_id']);
                    // 追加到已有許可權中
                    foreach($_p as $app => $models) {
                        foreach($models as $model => $d) {
                            if(isset($permission[$app][$model])) {
                                $permission[$app][$model] = array_merge($permission[$app][$model], $d);
                            } else {
                                $permission[$app][$model] = $d;
                            }
                        }
                    }
                }
                model('Cache')->set('perm_user_'.$uid, $permission, 600);
            }
            self::$permission[$uid] = $permission;
        }

        return self::$permission[$uid];
    }

    /**
     * 清除許可權快取
     * @param string $key 許可權相關Key值
     * @return boolean 是否清除快取成功
     */
    public function cleanCache($key = '') {
        if(empty($key)) {
            $groupList = model('UserGroup')->getHashUsergroup();
            foreach($groupList as $k => $v) {
                model('Cache')->rm('perm_'.$k);
            }
        } else {
            model('Cache')->rm('perm_'.$key);
        }

        return true;
    }

    /**
     * 獲取許可權節點列表，供後臺使用
     * @param integer $gid 使用者組ID
     * @param string $app 應用名稱欄位
     * @param string $appgroup 應用中使用者組欄位
     * @return array 許可權節點列表
     */
    public function getRuleList($gid, $app, $appgroup) {
        // $permissionFile = ADDON_PATH.'/lang/zh-cn/permission.xml';
        // $xml = simplexml_load_file( $permissionFile );
        // 許可權節點獲取
        $permData = D('permission_node')->order('module DESC')->findAll();
        $appHash = $permNode = $appGroup = array();

        foreach($permData as $v) {
            $permNode[$v['appname']][$v['module']][] = $v;
            $appHash[$v['appname']] = $v['appinfo'];
        }
        // 應用內部的許可權組
        $appGroupData = D('permission_group')->findAll();
        foreach($appGroupData as $v) {
            $appGroup[$v['appname']][$v['appgroup']] = $v['appgroup_name'] ;
        }

        if(!empty($app) && !empty($appgroup)) {
            // 取出應用下的許可權設定
            foreach($permNode as $a => $v) {
                if($a == $app) {
                    // 對應的APP下面
                    foreach($appGroup[$a] as $group => $groupname) {
                        if($group == $appgroup) {
                            // 所查的組許可權
                            $groupInfo = array('user_group_name'=>$groupname, 'user_group_id'=>$app.'_'.$group);
                        }
                    }

                    $permission[$a]['info'] = $appHash[$a];

                    foreach($v as $rules) {
                        foreach($rules as $ruls) {
                            $permission[$a]['module'][$rule['module']]['info'] = $rule['module'];
                            $permission[$a]['module'][$rule['module']]['rule'][$rule['rule']] = $rule['ruleinfo'];
                        }
                    }
                    break;
                }
            }
            $grouppermission = $this->getGroupPermission($app.'_'.$appgroup);
        } else {
            $groupInfo = model('UserGroup')->getUserGroup($gid);
            foreach($permNode as $a => $v) {
                $permission[$a]['info'] = $appHash[$a];
                foreach($v as $rules){
                    foreach($rules as $rule){
                        $permission[$a]['module'][$rule['module']]['info'] = $rule['module'];
                        $permission[$a]['module'][$rule['module']]['rule'][$rule['rule']] = $rule['ruleinfo'];
        }
        }
        }
        $grouppermission = $this->getGroupPermission($gid);
        }

        return array('groupInfo'=>$groupInfo,'permission'=>$permission,'grouppermission'=>$grouppermission);
        }

        /**
         * 獲取指定使用者組的許可權
         * @param string $key 使用者組ID或者特殊應用下面的appname_appgroupname
         * @return array 指定使用者組的許可權資訊
         */
        public function getGroupPermission($key) {
            static $permissionCache;
            if(!$permissionCache[$key]) {
                $cacheRule = model('Cache')->get('perm_'.$key);
                if(!$cacheRule) {
                    $cacheRule = model('Xdata')->get('permission:'.$key);
                    model('Cache')->set('perm_'.$key , $cacheRule );
        }
        $permissionCache[$key] = $cacheRule;
        }

        return $permissionCache[$key];
        }

        /**
         * 設定指定使用者組的許可權資訊
         * @param string $key 使用者組ID或者特殊應用下面的appname_appgroupname
         * @param array $data 相關許可權資訊
         * @return void
         */
        public function setGroupPermission($key, $data) {
            model('Xdata')->put('permission:'.$key, $data);
            model('Cache')->set('perm_'.$key, $data);
            $userIds = D('user_group_link')->where('user_group_id='.$key)->field('uid')->findAll();
            foreach($userIds as $v){
                model('Cache')->rm('perm_user_'.$v['uid']);
        }
        }
        }
