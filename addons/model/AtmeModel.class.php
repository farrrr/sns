<?php
/**
 * @Me模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AtmeModel extends Model {

    protected $tableNmae = 'atme';
    protected $fields = array(0=>'atme_id',1=>'app',2=>'table',3=>'row_id',4=>'uid','_pk'=>'atme_id');

    private $_app = null;                       // 所屬應用
    private $_app_table = null;                 // 所屬資源表
    private $_app_pk_field = null;              // 應用主鍵欄位
    private $_at_regex = "/@(.+?)([\s|:]|$)/is";//"/@{uid=([^}]*)}/";    // @正則規則
    private $_at_field = 'uid';                 // @的資源欄位

    /**
     * 設定所屬應用
     * @param string $app 應用名稱
     * @return object @物件
     */
    public function setAppName($app) {
        $this->_app = $app;
        return $this;
    }

    /**
     * 設定相關內容所存儲的資源表
     * @param string $app_table 資料表名
     * @return object @物件
     */
    public function setAppTable($app_table) {
        $this->_app_table = $app_table;
        return $this;
    }

    /**
     * 設定@的相關正則規則
     * @param string $regex 正則規則
     * @return object @物件
     */
    public function setAtRegex($regex) {
        $this->_at_regex = $regex;
        return $this;
    }

    /**
     * 設定@的資源欄位
     * @param string $field @的資源欄位
     * @return object @物件
     */
    public function setAtField($field) {
        $this->_at_field = $field;
        return $this;
    }

    /**
     * 獲取@Me列表 - 分頁型
     * @param array $map 查詢條件
     * @param string $order 排序條件，默認為atme_id DESC
     * @param integer $limit 結果集顯示個數，默認為20
     * @return array @Me列表資訊
     */
    public function getAtmeList($map = null, $order = 'atme_id DESC', $limit = 20) {
        !$map['app'] && $this->_app && ($map['app'] = $this->_app);
        !$map['table'] && $this->_app_table && ($map['table'] = $this->_app_table);
        $data = $this->where($map)->order($order)->findPage($limit);
        // 格式化資料
        foreach($data['data'] as &$v) {
            $v = model('Source')->getSourceInfo($v['table'], $v['row_id'], false, $v['app']);
        }
        // 重置@Me的未讀數
        $map['uid'] && model('UserCount')->resetUserCount($map['uid'], 'unread_atme', 0);

        return $data;
    }

    /**
     * 添加@Me資料
     * @param string $content @Me的相關內容
     * @param integer $row_id 資源ID
     * @param array $extra_uids 額外@使用者ID
     * @param array $less_uids 去除@使用者ID
     * @return integer 添加成功後的@ID
     */
    public function addAtme($content, $row_id, $extra_uids = null, $less_uids = null) {
        // 去除重複，空值與自己
        $extra_uids = array_diff($extra_uids, array($GLOBALS['ts']['mid']));
        $extra_uids = array_unique($extra_uids);
        $extra_uids = array_filter($extra_uids);

        $less_uids[] = $GLOBALS['ts']['mid'];
        $less_uids = array_unique($less_uids);
        $less_uids = array_filter($less_uids);
        // 獲取@使用者的UID陣列
        $uids = $this->getUids($content, $extra_uids, $row_id, $less_uids);
        // 添加@資訊
        $result = $this->_saveAtme($uids, $row_id);

        return $result;
    }

    /**
     * 更新最近@的人
     * @param string $content 原創微博內容
     */
    public function updateRecentAt( $content ){
        // 獲取@使用者的UID陣列
        preg_match_all($this->_at_regex, $content, $matches);
        $unames = $matches[1];
        if ( $unames[0] ){
            $map = "uname in ('".implode("','",$unames)."') AND uid!=".$GLOBALS['ts']['mid'].' AND `is_audit`=1 AND `is_init`=1 AND `is_active`=1';
            $ulist = model('User')->where($map)->field('uid')->findall();
            $matchuids = getSubByKey($ulist,'uid');
            $userdata = model( 'UserData' );
            $value = $userdata->where('uid='.$GLOBALS['ts']['mid']." and `key`='user_recentat'")->field('value')->find();
            if ( $value ){
                $atdata = getSubByKey( unserialize( $value['value'] ) , 'uid');
                $atdata && $matchuids = array_merge( $matchuids , $atdata);
                $matchuids = array_unique( $matchuids );
                $matchuids = array_slice( $matchuids , 0 , 10 );
                $users = model( 'User' )->getUserInfoByUids( $matchuids );
                foreach ( $users as $v){
                    if ( !$v['uid'] ){
                        continue;
                    }
                    $udata[] = array('uid'=>$v['uid'],'uname'=>$v['uname'],'avatar_small'=>$v['avatar_small'],'search_key'=>$v['search_key']);
                }
                //更新userdata表裡面的最近@的人的資訊
                $userdata->setField('value' , serialize( $udata ) , "`key`='user_recentat' AND uid=".$GLOBALS['ts']['mid']);
            } else {
                $matchuids = array_slice( $matchuids , 0 , 10 );
                $users = model( 'User' )->getUserInfoByUids( $matchuids );
                foreach ( $users as $v){
                    if ( !$v['uid'] ){
                        continue;
                    }
                    $udata[] = array('uid'=>$v['uid'],'uname'=>$v['uname'],'avatar_small'=>$v['avatar_small'],'search_key'=>$v['search_key']);
                }
                $data['uid'] = $GLOBALS['ts']['mid'];
                $data['key'] = 'user_recentat';
                $data['value'] = serialize( $udata );
                $data['mtime'] = time();
                $userdata->add($data);
            }
        }
    }

    /**
     * 更新最近@的人
     * @param string $content 原創微博內容
     */
    public function updateRecentAtForApi( $content ,$row_id){
        // 獲取@使用者的UID陣列
        preg_match_all($this->_at_regex, $content, $matches);
        $unames = $matches[1];
        if ( $unames[0] ){
            $map = "uname in ('".implode("','",$unames)."') AND uid!=".$GLOBALS['ts']['mid'].' AND `is_audit`=1 AND `is_init`=1 AND `is_active`=1';
            $ulist = model('User')->where($map)->field('uid')->findall();
            foreach ($ulist as $key => $value) {
                // dump($content);dump($row_id);
                // $this->addAtme($content,$row_id,$value['uid']);
                model('Atme')->setAppName('Public')->setAppTable('feed')->addAtme($content, $row_id, $value['uid']);
            }
            $matchuids = getSubByKey($ulist,'uid');
            $userdata = model( 'UserData' );
            $value = $userdata->where('uid='.$GLOBALS['ts']['mid']." and `key`='user_recentat'")->field('value')->find();
            if ( $value ){
                $atdata = getSubByKey( unserialize( $value['value'] ) , 'uid');
                $atdata && $matchuids = array_merge( $matchuids , $atdata);
                $matchuids = array_unique( $matchuids );
                $matchuids = array_slice( $matchuids , 0 , 10 );
                $users = model( 'User' )->getUserInfoByUids( $matchuids );
                foreach ( $users as $v){
                    if ( !$v['uid'] ){
                        continue;
                    }
                    $udata[] = array('uid'=>$v['uid'],'uname'=>$v['uname'],'avatar_small'=>$v['avatar_small'],'search_key'=>$v['search_key']);
                }
                //更新userdata表裡面的最近@的人的資訊
                $userdata->setField('value' , serialize( $udata ) , "`key`='user_recentat' AND uid=".$GLOBALS['ts']['mid']);
            } else {
                $matchuids = array_slice( $matchuids , 0 , 10 );
                $users = model( 'User' )->getUserInfoByUids( $matchuids );
                foreach ( $users as $v){
                    if ( !$v['uid'] ){
                        continue;
                    }
                    $udata[] = array('uid'=>$v['uid'],'uname'=>$v['uname'],'avatar_small'=>$v['avatar_small'],'search_key'=>$v['search_key']);
                }
                $data['uid'] = $GLOBALS['ts']['mid'];
                $data['key'] = 'user_recentat';
                $data['value'] = serialize( $udata );
                $data['mtime'] = time();
                $userdata->add($data);
            }
        }
    }

    /**
     * 獲取@內容中的@使用者
     * @param string $content @Me的相關內容
     * @param array $extra_uids 額外@使用者UID
     * @param integer $row_id 資源ID
     * @param array $less_uids 去除@使用者ID
     * @return array 使用者UID陣列
     */
    public function getUids($content, $extra_uids = null, $row_id, $less_uids = null) {
        // 正則匹配內容
        preg_match_all($this->_at_regex, $content, $matches);
        $unames = $matches[1];
        $map = "uname in ('".implode("','",$unames)."')";
        $ulist = model('User')->where($map)->field('uid')->findall();
        $matchuids = getSubByKey($ulist,'uid');
        // 如果內容匹配中沒有使用者
        if(empty($matchuids) && !empty($extra_uids)) {
            // 去除@使用者ID
            if(!empty($less_uids)) {
                foreach($less_uids as $k => $v) {
                    if(in_array($v, $extra_uids)) {
                        unset($extra_uids[$k]);
                    }
                }
            }
            return is_array($extra_uids) ? $extra_uids : array($extra_uids);
        }
        // 如果匹配內容中存在使用者
        $suid = array();
        foreach($matchuids as $v) {
            !in_array($v, $suid) && $suid[] = (int)$v;
        }
        // 去除@使用者ID
        if(!empty($less_uids)) {
            foreach($suid as $k => $v) {
                if(in_array($v, $less_uids)) {
                    unset($suid[$k]);
                }
            }
        }
        // 發郵件流程
        $author = model('User')->getUserInfo($GLOBALS['ts']['mid']);
        $content = model('Source')->getSourceInfo($this->_app_table, $row_id, false, $this->_app);
        $config['content'] = parse_html($content['source_content']);
        $config['publish_time'] = date('Y-m-d H:i:s',$content['ctime']);
        $config['feed_url'] = $content['source_url'];
        $config['name'] = $author['uname'];
        $config['space_url'] = $author['space_url'];
        $config['face'] = $author['avatar_small'];
        foreach($suid as $u_v) {
            model('Notify')->sendNotify($u_v, 'atme', $config);
        }
/*      if(in_array('atme',MailModel::$allowed)){
            foreach($suid as $u_v){
                $v = array('table'=>$this->_app_table,'app'=>$this->_app,'row_id'=>$row_id,'uid'=>$u_v);
                $toUser = model('User')->getUserInfo($u_v);
                $map['key'] = 'email';
                $map['uid'] = $u_v;
                $isEmail = D('user_privacy')->where($map)->field('value')->find();
                if($isEmail['value'] == 0){
                    $content = model('Source')->getSourceInfo($v['table'],$v['row_id'],false,$v['app']);
                    //model('Mail')->send_email($toUser['email'],'atme','',array('content'=>parse_html($content['source_content'])));
                    model('Mail')->send_email($toUser['email'],$author['uname'].'在微博中@提到了您',parse_html($content['source_content']));
                }
                unset($map);
                unset($isEmail);
            }
}*/

        return array_unique(array_filter(array_merge($suid, (array)$extra_uids)));
    }

    /**
     * 刪除@Me資料
     * @param string $content @Me的相關內容
     * @param integer $row_id 資源ID
     * @param array $extra_uids 額外@使用者UID
     * @return boolean 是否刪除成功
     */
    public function deleteAtme($content, $row_id, $extra_uids = null) {
        $uids = $this->getUids($content, $extra_uids);
        $result = $this->_deleteAtme($uids, $row_id);
        return $result;
    }

    /**
     * 添加@Me資訊操作
     * @param array $uids 使用者UID陣列
     * @param integer $row_id 資源ID
     * @return integer 添加成功後的@ID
     */
    private function _saveAtme($uids, $row_id) {
        foreach ($uids as $u_v) {
            // 去除自己@自己的資料
            if($u_v == $GLOBALS['ts']['mid']) {
                continue;
            }
            $data[] = "('{$this->_app}', '{$this->_app_table}', {$row_id}, {$u_v})";
            // 更新@Me的未讀數目
            model('UserCount')->updateUserCount($u_v, 'unread_atme', 1);
        }
        !empty($data) && $res = $this->query('INSERT INTO '.$this->tablePrefix.'atme (`app`, `table`, `row_id`, `uid`) VALUES '.implode(',', $data));

        return $res;
    }

    /**
     * 刪除@Me資訊操作
     * @param array $uids 使用者UID陣列
     * @param integer $row_id 資源ID
     * @return boolean 是否刪除成功
     */
    private function _deleteAtme($uids, $row_id) {
        $result = false;
        if(!empty($uids)) {
            $map['table'] = $this->_app_table;
            $map['row_id'] = $row_id;
            $map['uid'] = array('IN', $uids);
            $res = $this->where($map)->delete();
            $res !== false && $result = true;
        } else {
            $map['table'] = $this->_app_table;
            $map['row_id'] = $row_id;
            $res = $this->where($map)->delete();
            $res !== false && $result = true;
        }

        return $result;
        }

        /*** API使用方法 ***/
        // 所有的@資訊
        public function mentions($mid , $since_id = 0 , $max_id = 0 , $limit = 20 , $page = 1, $table=null) {
            $since_id = intval($since_id);
            $max_id = intval($max_id);
            $limit = intval($limit);
            $page = intval($page);
            $where = "uid = '{$mid}'";
            if($table){
                $where .= " AND `table`='{$table}' ";
        }
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND atme_id > {$since_id}";
            !empty($max_id) && $where .= " AND atme_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $list = $this->where($where)->limit("$start, $end")->order('atme_id DESC')->findAll();

        foreach($list as $k => $v) {
            $list[$k] = model('Source')->getSourceInfo($v['table'], $v['row_id'], true, $v['app']);
            $list[$k]['atme_id'] = $v['atme_id'];
            //評論的內容
            $list[$k]['content'] = $list[$k]['source_content'];
            unset($list[$k]['source_content']);
        }

        return $list;
        }

        // 動態的@資訊
        public function mentions_feed($mid , $since_id = 0 , $max_id = 0 , $limit = 20 , $page = 1) {
            $since_id = intval($since_id);
            $max_id = intval($max_id);
            $limit = intval($limit);
            $page = intval($page);
            $where = "uid = '{$mid}' AND `table`='feed' ";
            if(!empty($since_id) ) {
                !empty($since_id) && $where .= " AND row_id > {$since_id}";
        }
        if(!empty($max_id)){
            !empty($max_id) && $where .= " AND row_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $feed_ids = $this->where($where)->limit("$start, $end")->order('atme_id DESC')->field('row_id')->getAsFieldArray('row_id');
        $data = model('Feed')->formatFeed($feed_ids,true);
        return $data;
        }
        /**
         * 獲取動態的種類，用於動態的Tab
         * @param array $map 查詢條件
         * @return array 評論種類與其資源數目
         */
        public function getTab($map) {
            $list = $this->field('COUNT(1) AS `nums`, `table`')->where($map)->group('`table`')->getHashList('table', 'nums');
            return $list;
        }
        }
