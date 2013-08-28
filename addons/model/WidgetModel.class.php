<?php
/**
 * 自定義Widget模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version 1.0
 */
class WidgetModel extends Model {

    protected $tableName = 'widget';
    protected $fields = array(0=>'id',1=>'name',2=>'desc',3=>'attrs',4=>'diyattrs',5=>'appname','_autoinc'=>true,'_pk'=>'id');

    /**
     * 獲取自定義Widget列表 - 未分頁型
     * @return array 自定義Widget列表資訊
     */
    public function getDiyList() {
        $list = $this->table($this->tablePrefix.'Widget_diy')->findAll();
        return $list;
    }

    /**
     * 獲取所有可用的Widget列表 - 未分頁型
     * @return array 所有可用的Widget列表資訊
     */
    public function getWidgetList() {
        $list = $this->table($this->tablePrefix.'widget')->findAll();
        return $list;
    }

    /**
     * 獲取指定自定義Widget下的Diy資料
     * @param integer $id 自定義Widget下的DiyID
     * @return 自定義Widget下的Diy資料
     */
    public function getDiyWidgetById($id) {
        $id = intval($id);
        if(empty($id)) {
            return false;
        }
        if(($info = model('Cache')->get('Diy_Widget_'.$id)) === false) {
            $map['id'] = $id;
            $info = $this->table($this->tablePrefix.'widget_diy')->where($map)->find();
            model('Cache')->set('Diy_Widget_'.$id,$info);
        }

        return $info;
    }

    /**
     * 儲存使用者自定義Widget下的Diy資料
     * @param integer $diyId 自定義Widget下的DiyID
     * @param integer $uid 使用者ID
     * @param array $targetList 目標Widget名稱列表，[應用名:Widget名稱]
     * @return boolean 是否儲存成功
     */
    public function saveUserWigdet($diyId, $uid, $targetList) {
        if(!$widget_list = $this->getUserWidget($diyId, $uid)) {
            return false;
        }
        $targetList = explode(',', trim($targetList));
        $targetList = array_unique($targetList);

        $wl = $wattr = array();
        foreach($widget_list['widget_list'] as $k => $v) {
            if(in_array($v['appname'].':'.$v['name'], $targetList)) {
                // 已經存在
                $wl[] = array('name'=>$v['name'], 'appname'=>$v['appname']);
                $wattr[$v['appname'].':'.$v['name']] = $widget_list['widget_diyatts'][$v['appname'].':'.$v['name']];
                $key = array_search($v['appname'].':'.$v['name'], $targetList);
                unset($targetList[$key]);
            }
        }

        foreach($targetList as $v) {
            if(empty($v)) {
                continue;
            }
            $info = $this->getWidget($v);
            $wl[] = array('name'=>$info['name'], 'appname'=>$info['appname']);
            $wattr[$info['appname'].':'.$info['name']] = unserialize($info['diyattrs']);
        }

        $map['diy_id'] = $diyId;
        $map['uid'] = $uid;
        $save['widget_list'] = serialize($wl);
        $save['widget_diyatts'] = serialize($wattr);
        if(D('')->table($this->tablePrefix.'widget_user')->where($map)->save($save)) {
            model('Cache')->rm('User_Widget_'.$diyId.'_'.$uid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 添加自定義Widget
     * @param array $add 自定義Widget相關資料
     * @return mix 添加失敗返回false，添加成功返回新的Widget的ID
     */
    public function addDiyWidget($add) {
        $add['desc'] = t($add['desc']);
        empty($add['widget_list']) && $add['widget_list'] = array();
        $add['widget_list'] = serialize($add['widget_list']);
        return D('widget_diy')->add($add);
    }

    /**
     * 自定義Widget排序
     * @param integer $id
     * @param integer $uid 使用者ID
     * @param string $target 目標Widget名稱，[應用名:Widget名稱]
     * @return void
     */
    public function dosort($id, $uid, $target) {
        $target = explode(',', $target);
        $s = array();
        foreach($target as $v) {
            $t = explode(':', $v);
            $s[] = array('name'=>$t[1], 'appname'=>$t[0]);
        }
        $save['widget_list'] = serialize($s);
        $map['uid'] = $uid;
        $map['diy_id'] = $id;
        D('')->table($this->tablePrefix.'widget_user')->where($map)->save($save);
        model('Cache')->rm('User_Widget_'.$id.'_'.$uid);
    }

    /**
     * 使用者主動更新某個位置的某個Widget屬性
     * @param integer $diyId 使用者自定義Widget的DiyID
     * @param integer $uid 使用者ID
     * @param string $target 目標Widget名稱，[應用名:Widget名稱]
     * @param array $data 更新的相關資料
     * @return boolean 是否更新成功
     */
    public function updateUserWidget($diyId, $uid, $target, $data) {
        if(!$widget_list = $this->getUserWidget($diyId, $uid)) {
            return false;
        }

        if(empty($widget_list['widget_diyatts'])) {
            $widget_list['widget_diyatts'][$target] = $data;
        } else {
            foreach($widget_list['widget_diyatts'] as $k => $v) {
                if($k == $target) {
                    $widget_list['widget_diyatts'][$k] = $data;
                }
            }
        }

        $save['widget_diyatts'] = serialize($widget_list['widget_diyatts']);
        $map['diy_id'] = $diyId;
        $map['uid'] = $uid;
        if(D('')->table($this->tablePrefix.'widget_user')->where($map)->save($save)) {
            model('Cache')->rm('User_Widget_'.$diyId.'_'.$uid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 從指定的Diy中刪除指定的Widget
     * @param integer $diyId 自定義Widget的DiyID
     * @param integer $uid 使用者ID
     * @param string $target 目標Widget名稱，[應用名:Widget名稱]
     * @return boolean 是否刪除成功
     */
    public function deleteUserWidget($diyId, $uid, $target) {
        if(!$widget_list = $this->getUserWidget($diyId,$uid)) {
            return false;
        }

        $wl = array();
        foreach($widget_list['widget_list'] as $k => $v) {
            if($v['appname'].':'.$v['name'] != $target) {
                $wl[] = array('name'=>$v['name'], 'appname'=>$v['appname']);
            }
        }

        $save['widget_list'] = serialize($wl);
        foreach($widget_list['widget_diyatts'] as $k => $v) {
            if($k == $target) {
                unset($widget_list['widget_diyatts'][$k]);
            }
        }

        $save['widget_diyatts'] = serialize($widget_list['widget_diyatts']);
        $map['diy_id'] = $diyId;
        $map['uid'] = $uid;

        if(D('')->table($this->tablePrefix.'widget_user')->where($map)->save($save)) {
            model('Cache')->rm('User_Widget_'.$diyId.'_'.$uid);
            return true;
        } else {
            return false;
        }
    }

    /**
     * 獲取指定Widget的具體內容
     * @param string $target 目標Widget名稱，[應用名:Widget名稱]
     * @return array 指定自定義Widget的具體內容
     */
    public function getWidget($target) {
        if(($info = model('Cache')->get('widget_'.$target)) === false) {
            $v = explode(":", $target);
            $map['appname'] = $v[0];
            $map['name'] = $v[1];
            $info = D('')->table($this->tablePrefix.'widget')->where($map)->find();
            model('Cache')->set('widget_'.$target,$info);
        }

        return $info;
    }

    /**
     * 獲取指定使用者指定自定義的Widget具體內容
     * @param integer $diyId 自定義Widget的DiyId
     * @param integer $uid 使用者ID
     * @return array 指定使用者指定自定義的Widget具體內容
     */
    public function getUserWidget($diyId, $uid) {
        $diyId = intval($diyId);
        $uid = intval($uid);

        if(empty($diyId) || empty($uid)) {
            return false;
        }

        if(($info  = model('Cache')->get('User_Widget_'.$diyId.'_'.$uid)) === false) {
            $map['diy_id'] = $diyId;
            $map['uid'] = $uid;
            if(!$info = $this->table($this->tablePrefix.'widget_user')->where($map)->find()) {
                // 不存在，則初始化
                $diyInfo = $this->getDiyWidgetById($diyId);
                $map['widget_list'] = $diyInfo['widget_list'];
                $info = $map;
                $info['widget_user_id'] = D('')->table($this->tablePrefix.'widget_user')->add($map);
            }

            $info['widget_list'] = unserialize($info['widget_list']);
            $info['widget_diyatts'] = empty($info['widget_diyatts']) ? array() : unserialize($info['widget_diyatts']);
            foreach($info['widget_list'] as &$v) {
                $attrs = $this->getWidget($v['appname'].':'.$v['name']);
                $attrDesc = unserialize($attrs['attrs']);
                $diy = unserialize($attrs['diyattrs']);
                empty($attrDesc) && $attrDesc = array();
                empty($diy) && $diy = array();      // 默認

                $userset = isset($info['widget_diyatts'][$v['appname'].':'.$v['name']]) ? $info['widget_diyatts'][$v['appname'].':'.$v['name']] : array();
                $attr = array_merge($diy,$userset);
                $v['attrs'] = $attrDesc;
                $v['diyattrs'] = $attr;
                $v['widget_desc'] = $attrs['desc'];
            }
            model('Cache')->set('User_Widget_'.$diyId.'_'.$uid,$info);
        }

        return $info;
    }

    /*** 後臺操作 ***/
    /**
     * 後臺配置單個Widget
     * @param integer $id 自定義Widget的DiyId
     * @param array $targetList 目標Widget名稱列表，[應用名:Widget名稱]
     * @return boolean 後臺配置單個Widget是否成功
     */
    public function configWidget($id, $targetList) {
        $targetList = explode(',', trim($targetList));
        $targetList = array_unique($targetList);

        $t = array();
        foreach($targetList as $v) {
            if(empty($v)) {
                continue;
            }
            $v = explode(':', $v);
            $t[] = array('name'=>$v[1],'appname'=>$v[0]);
        }
        $map['id'] = $id;
        $save['widget_list'] = serialize($t);
        if(D('')->table($this->tablePrefix.'widget_diy')->where($map)->save($save)) {
            model('Cache')->rm('Diy_Widget_'.$id);
            return true;
        }
        return false;
        }

        /**
         * 後臺更新Widget，包含核心Widget與應用Widget
         * @return void
         */
        public function updateWidget() {
            $this->_doupdate(ADDON_PATH.'/widget');         // 核心Widget
            $this->updateAppWidget();                       // 應用Wioget
        }

        /**
         * 更新應用下的Widget
         * @param string $app 應用名稱，默認為空，即更新所有的應用的Widget
         * @return void
         */
        public function updateAppWidget($app = '') {
            if(empty($app)) {
                // 更新全部應用Widget
                $appList = model('App')->getAppList();
                foreach($appList as $v) {
                    $this->_doupdate(APPS_PATH.'/'.$v['app_name'].'/Widget');
        }
        } else {
            // 更新指定應用Widget
            $this->_doupdate(APPS_PATH.'/'.$app.'/Widget');
        }
        }

        /**
         * 更新Widget操作
         * @param string $path Widget路徑
         * @return void
         */
        private function _doupdate($path) {
            tsload(ADDON_PATH.'/library/io/Dir.class.php');
            $dirs = new Dir($path);
            $dirs = $dirs->toArray();
            foreach($dirs as $info) {
                if(!file_exists($info['pathname'].'/info.php')) {
                    continue;
        }
        $data = include($info['pathname'].'/info.php');
        if(empty($data['name']) || empty($data['appname'])) {
            continue;
        }
        empty($data['attrs']) && $data['attrs'] = array();
        empty($data['diyattrs']) && $data['diyattrs'] = array();

        $attrs = serialize($data['attrs']);
        $diyattrs = serialize($data['diyattrs']);

        $sql = " REPLACE INTO ".$this->tablePrefix."widget (`name`,`desc`,`attrs`,`diyattrs`,`appname` )
            VALUES ('{$data['name']}','{$data['desc']}','{$attrs}','{$diyattrs}','{$data['appname']}') ";
        $this->query($sql);
        }
        }
        }

