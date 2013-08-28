<?php
/**
 * 使用者應用關聯模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class UserAppModel extends Model {

    protected $tableName = 'user_app';
    protected $fields = array (0=>'user_app_id',1=>'app_id',2=>'uid',3=>'display_order',4=>'ctime',5=>'type',6=>'oauth_token',7=>'oauth_token_secret',8=>'inweb');

    /**
     * 獲取使用者可用的應用列表
     * @param integer $uid 使用者UID
     * @param integer $inweb 是否是Web端，默認為1
     * @return array 使用者可用的應用列表資料
     */
    public function getUserApp($uid, $inweb = 1) {
        // 默認應用
        if($appList = static_cache('userApp_uapp_'.$uid.'_'.$inweb)) {
            return $appList;
        }

        if(($appList = model('Cache')->get('userApp_uapp_'.$uid.'_'.$inweb)) === false) {
            $appList = array();
            //$return = model('App')->getDefaultApp();
            $imap['a.uid'] = $uid;
            $imap['a.inweb'] = intval($inweb);
            $imap['b.status'] = 1;
            $table = $this->tablePrefix.'user_app AS a LEFT JOIN '.$this->tablePrefix.'app AS b ON a.app_id = b.app_id';
            if($list = $this->table($table)->where($imap)->field('a.app_id')->order('a.display_order ASC')->getAsFieldArray('app_id')) {
                foreach($list as $v) {
                    $appList[] = model('App')->getAppById($v);
                }
            }
/*          if(!empty($return)){
                $appList = empty($appList) ? $return :array_merge($return,$appList);
}*/
            model('Cache')->set('userApp_uapp_'.$uid.'_'.$inweb, $appList, 120);
        }

        static_cache('userApp_uapp_'.$uid.'_'.$inweb, $appList);

        return $appList;
    }

    /**
     * 獲取指定使用者所安裝的應用ID陣列
     * @param integer $uid 使用者UID
     * @param integer $inweb 是否是Web端，默認為1
     * @return array 指定使用者安裝的應用ID陣列
     */
    public function getUserAppIds($uid, $inweb = 1) {
        if(empty($uid)) {
            $this->error = L('PUBLIC_USER_EMPTY');          // 使用者名不能為空
            return false;
        }
        $list = $this->getUserApp($uid, $inweb);
        $r = array();
        foreach($list as $v) {
            $r[] = $v['app_id'];
        }

        return $r;
    }

    /**
     * 獲取一個指定應用的使用情況
     * @param integer $appId 應用ID
     * @return array 指定應用的使用情況
     */
    public function getUsed($appId) {
        if(($used = model('Cache')->get('AppUsed_'.$appId)) === false) {
            $map['app_id'] = $appId;
            $used = $this->where($map)->field('COUNT(DISTINCT uid) AS `count`')->find();
            $used = intval($used['count']);
            model('Cache')->set('AppUsed_'.$appId, $used);
        }

        return $used;
    }

    /**
     * 清除指定應用使用情況的快取
     * @param integer $appId 應用ID
     * @return void
     */
    public function cleanUsed($appId) {
        model('Cache')->rm('AppUsed_'.$appId);
    }

    /**
     * 指定使用者解除安裝指定應用
     * @param integer $uid 使用者UID
     * @param integer $appId 應用ID
     * @param integer $inweb 是否是Web端，默認為1
     * @return boolean 是否解除安裝成功
     */
    public function uninstall($uid, $appId, $inweb = 1) {
        if(empty($uid) || empty($appId)) {
            $this->error = L('PUBLIC_WRONG_DATA');          // 錯誤的參數
            return false;
        }
        // 驗證使用者是否已經安裝了該應用
        $inweb = intval($inweb);
        $uid = intval($uid);
        $appId = intval($appId);
        $ids = $this->getUserAppIds($uid,$inweb);
        if(!in_array($appId, $ids)) {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失敗
            return false;
        }
        $map['uid'] = $uid;
        $map['app_id'] = $appId;
        $map['inweb'] = $inweb;
        $this->updateUserApp($uid, $appId, false);
        if($this->where($map)->limit(1)->delete()) {
            return true;
        } else {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失敗
            return false;
        }
    }

    /**
     * 指定使用者安裝指定應用
     * @param integer $uid 使用者UID
     * @param integer $appId 應用ID
     * @param integer $inweb 是否是Web端，默認為1
     * @return boolean 是否安裝成功
     */
    public function install($uid, $appId, $inweb = 1) {
        if(empty($uid) || empty($appId)) {
            $this->error = L('PUBLIC_WRONG_DATA');          // 錯誤的參數
            return false;
    }
        // 驗證使用者是否已經安裝了該應用
        $inweb = intval($inweb);
        $uid = intval($uid);
        $appId = intval($appId);
        $ids = $this->getUserAppIds($uid, $inweb);
        if(in_array($appId, $ids)) {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失敗
            return false;
        }
        $map['uid'] = $uid;
        $map['app_id'] = $appId;
        $map['ctime'] = time();
        $map['inweb'] = $inweb;
        $map['display_order'] = 255;
        if($this->add($map)) {
            $this->updateUserApp($uid, $appId);
            return true;
        } else {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失敗
            return false;
        }
        }

        /**
         * 更新使用者安裝/解除安裝應用的快取資訊
         * @param integer $uid 使用者UID
         * @param integer $appId 應用ID
         * @param boolean $install 是否是安裝資訊，默認為true
         * @return boolean 是否更新成功
         */
        public function updateUserApp($uid, $appId, $install = true) {
            if(empty($appId) || empty($uid)) {
                $this->error = L('PUBLIC_WRONG_DATA');          // 錯誤的參數
                return false;
        }
        $this->cleanUsed($appId);
        $this->cleanCache($uid);
        return true;
        }

        /**
         * 清除指定使用者的應用資訊快取
         * @param integer $uids 使用者UID
         * @return boolean 是否清除成功
         */
        public function cleanCache($uids) {
            !is_array($uids) && $uids = explode(',', $uids);
            foreach($uids as $uid) {
                model('Cache')->rm('userApp_uapp_'.$uid.'_0');
                model('Cache')->rm('userApp_uapp_'.$uid.'_1');
        }
        return true;
        }
        }
