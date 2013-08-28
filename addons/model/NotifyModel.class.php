<?php
/**
 * 訊息通知節點模型 - 資料物件模型
 * @example
 * 使用Demo：
 * model('Notify')->sendNotify(14983,'register_active',array('siteName'=>'SOCIAX','name'=>'yangjs'));
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class NotifyModel extends Model {

    protected $tableName = 'notify_node';
    protected $fields = array(0=>'id',1=>'node',2=>'nodeinfo',3=>'appname',4=>'content_key',5=>'title_key',6=>'send_email',7=>'send_message',8=>'type');

    protected $_config = array();           // 配置欄位

    /**
     * 初始化方法，獲取站點名稱、系統郵箱、找回密碼的URL
     * @return void
     */
    public function _initialize() {
        $site = empty($GLOBALS['ts']['site']) ? model('Xdata')->get('admin_Config:site') : $GLOBALS['ts']['site'];
        $this->_config['site'] = $site['site_name'];
        $this->_config['site_url'] = SITE_URL;
        $this->_config['kfemail'] = 'mailto:'.$site['sys_email'];
        $this->_config['findpass'] = U('public/Passport/findPassword');
    }

    /**
     * 獲取節點列表
     * @return array 節點列表資料
     */
    public function getNodeList() {
        // 快取處理
        if($list = static_cache('notify_node')) {
            return $list;
        }
        if(($list = model('Cache')->get('notify_node')) == false) {
            $list = $this->getHashList('node', '*');
            model('Cache')->set('notify_node', $list);
        }
        static_cache('notify_node', $list);

        return $list;
    }

    /**
     * 儲存節點配置
     * @param array $data 節點修改資訊
     * @return boolean 是否儲存成功
     */
    public function saveNodeList($data) {
        foreach($data as $k => $v) {
            $m = $s = array();
            $m['node'] = $k;
            $s['send_email'] = intval($v['send_email']);
            $s['send_message'] = intval($v['send_message']);
            $this->where($m)->save($s);
        }

        $this->cleanCache();
        return true;
    }

    /**
     * 清除訊息節點快取
     * @return void
     */
    public function cleanCache() {
        model('Cache')->rm('notify_node');
        //更新語言包
        model('Lang')->initSiteLang();
    }

    /**
     * 儲存模版設定
     * @param array $data 模板資料
     * @return boolean 是否儲存成功
     */
    public function saveTpl($data) {
        foreach($data['lang'] as $k => $v) {
            if(empty($k)) continue;

            $m['key'] = $k;
            model('Lang')->where($m)->save($v);
        }
        $this->cleanCache();
        return true;
    }

    /**
     * 分組返回指定使用者的系統訊息列表
     * @param integer $uid 使用者ID
     * @return array 分組返回指定使用者的系統訊息列表
     */
    public function getMessageList($uid) {
        $map['uid'] = $uid;
        $field = 'MAX(id) AS id, appname';
        if(!$list['group'] = D('')->table($this->tablePrefix.'notify_message')->where($map)->field($field)->group('appname')->findAll()) {
            return array();
        }
        $map['is_read'] = 0;
        $field = 'COUNT(id) AS nums,appname';
        $list['groupCount'] = D('')->table($this->tablePrefix.'notify_message')->where($map)->field($field)->group('appname')->findAll();
        foreach($list['group'] as $v) {
            $list['appInfo'][$v['appname']] = model('App')->getAppByName($v['appname']);
            $idHash[] = $v['id'];
        }
        $m['id'] = array('IN', $idHash);
        $list['messageInfo'] = D('')->table($this->tablePrefix.'notify_message')->where($m)->getHashList('id', '*');
        return $list;
    }

    /**
     * 獲取指定應用指定使用者下的系統訊息列表
     * @param string $app 應用Key值
     * @param integer $uid 使用者ID
     * @return array 指定應用指定使用者下的系統訊息列表
     */
    public function getMessageDetail($app, $uid) {
        $map['appname'] = $app;
        $map['uid'] = $uid;
        $list = D('')->table($this->tablePrefix.'notify_message')->where($map)->order('id DESC')->findPage(20);
        return $list;
    }

    /**
     * 更改指定使用者的訊息從未讀為已讀
     * @param integer $uid 使用者ID
     * @param string $appname 應用Key值
     * @return mix 更改失敗返回false，更改成功返回訊息ID
     */
    public function setRead($uid, $appname = '') {
        $map['uid'] = $uid;
        !empty($appname) && $map['appname'] = $appname;
        $s['is_read'] = 1;
        return D('')->table($this->tablePrefix.'notify_message')->where($map)->save($s);
    }

    /**
     * 獲取指定使用者未讀訊息的總數
     * @param integer $uid 使用者ID
     * @return integer 指定使用者未讀訊息的總數
     */
    public function getUnreadCount($uid){
        $map['uid'] = $uid;
        $map['is_read'] = 0;
        return D('')->table($this->tablePrefix.'notify_message')->where($map)->count();
    }

    /**
     * 發送訊息入口，對已註冊使用者發送的訊息都可以通過此函數
     * @param array $toUid 接收訊息的使用者ID陣列
     * @param string $node 節點Key值
     * @param array $config 配置資料
     * @param intval $from 訊息來源使用者的UID
     * @return void
     */
    public function sendNotify($toUid, $node, $config, $from) {
        empty($config) && $config = array();
        $config = array_merge($this->_config,$config);

        $nodeInfo = $this->getNode($node);
        if(!$nodeInfo) {
            return false;
        }

        !is_array($toUid) && $toUid = explode(',', $toUid);
        $userInfo = model('User')->getUserInfoByUids($toUid);

        $data['node'] = $node;
        $data['appname'] = $nodeInfo['appname'];
        $data['title'] = L($nodeInfo['title_key'], $config);
        $data['body'] = L($nodeInfo['content_key'], $config);
        foreach($userInfo as $v) {
            $data['uid'] = $v['uid'];
            !empty($nodeInfo['send_message']) && $this->sendMessage($data);
            $data['email'] = $v['email'];
            if(!empty($nodeInfo['send_email'])){
                if(in_array($node,array('atme','comment','new_message'))){
                    $map['key'] = $node.'_email';
                    $map['uid'] = $v['uid'];
                    $isEmail = D('user_privacy')->where($map)->getField('value');
                    $isEmail==0 && $this->sendEmail($data);
                }else{
                    $this->sendEmail($data);
                }
            }
        }
    }

    //TS2的相容方法 同 sendNotify
    public function send($toUid, $node, $config, $from) {
        return $this->sendNotify($toUid, $node, $config, $from);
    }

    /**
     * 獲取指定節點資訊
     * @param string $node 節點Key值
     * @return array 指定節點資訊
     */
    public function getNode($node) {
        $list = $this->getNodeList();
        return $list[$node];
    }

    /**
     * 獲取指定節點的詳細資訊
     * @param string $node 節點Key值
     * @param array $config 配置資料
     * @return array 指定節點的詳細資訊
     */
    public function getDataByNode($node, $config) {
        empty($config) && $config = array();
        $config = array_merge($this->_config, $config);
        $nodeInfo = $this->getNode($node);
        $d['title'] = L($nodeInfo['title_key'], $config);
        $d['body'] = L($nodeInfo['content_key'], $config);
        return $d;
    }

    /**
     * 發送郵件，添加到訊息佇列資料表中
     * @param array $data 訊息的相關資料
     * @return mix 添加失敗返回false，添加成功返回新資料的ID
     */
    public function sendEmail($data) {
        if(empty($data['email'])) {
            // TODO:郵箱格式驗證
            return false;
        }
        // TODO:使用者隱私設定判斷
        $s['uid'] = intval($data['uid']);
        $s['node'] = t($data['node']);
        $s['email'] = t($data['email']);
        $s['appname'] = t($data['appname']);
        $s['is_send'] = $s['sendtime'] = 0;
        $s['title'] = t($data['title']);
        $body = html_entity_decode($data['body']);
        $site = model('Xdata')->get('admin_Config:site');
        $s['body']= '<style>a.email_btn,a.email_btn:link,a.email_btn:visited{background:#0F8CA8;padding:5px 10px;color:#fff;width:80px;text-align:center;}</style><div style="width:540px;border:#0F8CA8 solid 2px;margin:0 auto"><div style="color:#bbb;background:#0f8ca8;padding:5px;overflow:hidden;zoom:1"><div style="float:right;height:15px;line-height:15px;padding:10px 0;display:none">2012年07月15日</div>
            <div style="float:left;overflow:hidden;position:relative"><a><img style="border:0 none" src="'.$GLOBALS['ts']['site']['logo'].'"></a></div></div>
            <div style="background:#fff;padding:20px;min-height:300px;position:relative">       <div style="font-size:14px;">
            <p style="padding:0 0 20px;margin:0;font-size:12px">'.$body.'</p>
            </div></div><div style="background:#fff;">
            <div style="text-align:center;height:18px;line-height:18px;color:#999;padding:6px 0;font-size:12px">若不想再收到此類郵件，請點選<a href="'.U('public/Account/notify').'" style="text-decoration:none;color:#3366cc">設定</a></div>
            <div style="line-height:18px;text-align:center"><p style="color:#999;font-size:12px">'.$site['site_footer'].'</p></div>
            </div></div>';
        $s['ctime'] = time();
        model('Mail')->send_email($s['email'],$s['title'],$s['body']);
        return D('')->table($this->tablePrefix.'notify_email')->add($s);
    }

    /**
     * 發送系統訊息，給指定使用者
     * @param array $data 發送系統訊息相關資料
     * @return mix 發送失敗返回false，發送成功返回新的訊息ID
     */
    public function sendMessage($data) {
        if(empty($data['uid'])) {
            return false;
        }
        $s['uid'] = intval($data['uid']);
        $s['node'] = t($data['node']);
        $s['appname'] = t($data['appname']);
        $s['is_read'] = 0;
        $s['title'] = t($data['title']);
        $s['body'] = h($data['body']);
        $s['ctime'] = time();
        return D('')->table($this->tablePrefix.'notify_message')->add($s);
    }

    /**
     * 刪除通知
     * @param integer $id 通知ID
     * @return mix 刪除失敗返回false，刪除成功返回刪除的通知ID
     */
    public function deleteNotify($id) {
        $map['uid'] = $GLOBALS['ts']['mid'];        // 僅僅只能刪除登入使用者自己的通知
        $map['id'] = intval($id);
        return D('')->table($this->tablePrefix.'notify_message')->where($map)->delete();
    }

    /**
     * 發送郵件佇列中的資料，每次執行默認發送10封郵件
     * @param integer $sendNums 發送郵件的個數，默認為10
     * @return array 返回取出的資料個數與實際發送郵件的資料個數
     */
    public function sendEmailList($sendNums = 10) {
        set_time_limit(0);
        $mail = model('Mail');
        $find['is_send'] = 0;
        $list = D('')->table($this->tablePrefix.'notify_email')->where($find)->limit($sendNums)->order('id ASC')->findAll();
        $r['count'] = count($list);
        $r['nums'] = 0;
        ob_start();
        foreach($list as $v) {
            $map['id'] = $v['id'];
            $save['is_send'] = 1;
            $save['sendtime'] = time();
            D('')->table($this->tablePrefix.'notify_email')->where($map)->save($save);
            $r['nums']++;
            $mail->send_email($v['email'], $v['title'], $v['body']);
            // TODO:現在默認為全部發送成功，如果後期發送失敗要修改回來的話再根據$v['id']修改is_send
        }
        ob_end_clean();
        return $r;
    }

    /**
     * 發送系統訊息，給使用者組或全站使用者
     * @param array $user_group 使用者組ID
     * @param string $content 發送資訊內容
     * @return boolean 是否發送成功
     */
    public function sendSysMessae($user_group, $content) {
        set_time_limit(0);
        $ctime = time();
        $user_group = intval($user_group);
        if(!empty($user_group)) {
            // 判斷組中是否存在使用者
            $m['user_group_id'] = $user_group;
            $c = model('UserGroupLink')->where($m)->count();
            if($c > 0) {
                // 針對使用者組
                $sql = "INSERT INTO ".$this->tablePrefix."notify_message (`uid`,`node`,`appname`,`title`,`body`,`ctime`,`is_read`)
                    SELECT uid,'sys_notify','public','','{$content}','{$ctime}','0'
                    FROM ".$this->tablePrefix."user_group_link WHERE user_group_id = {$user_group} ";
            } else {
                return true;
            }
        } else {
            // 全站使用者
            $sql = "INSERT INTO ".$this->tablePrefix."notify_message (`uid`,`node`,`appname`,`title`,`body`,`ctime`,`is_read`)
                SELECT uid,'sys_notify','public','','{$content}','{$ctime}','0'
                FROM ".$this->tablePrefix."user WHERE is_del=0 ";
        }

        D('')->query($sql);
        return true;
    }

    /*** API使用 ***/
    /**
     * 返回指定使用者的未讀訊息列表，沒有since_id 和 max_id 的時候返回未讀訊息
     * @param integer $uid 使用者ID
     * @param integer $since_id 開始的元件ID，默認為0
     * @param integer $max_id 最大主鍵ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @return array 指定使用者的未讀訊息列表
     */
    public function getUnreadListForApi($uid, $since_id = 0, $max_id = 0, $limit = 20) {
        // 未讀訊息
        $map['uid'] = $uid;
        $map['is_read'] = 0;
        $list = D('')->table($this->tablePrefix.'notify_message')->where($map)->order('id DESC')->findAll();
        $count = count($list);

        if(!empty($since_id) || empty($max_id)) {
            $where =' uid = '.$uid;
            if(!empty($since_id)) {
                $where .= ' AND id > '.$since_id;
            }
            if(!empty($max_id)) {
                $where .= ' AND id < '.$max_id;
            }
            $list = D('')->table($this->tablePrefix.'notify_message')->where($where)->order('id DESC')->limit($limit)->findAll();
        }

        return array('list'=>$list,'count'=>$count);
    }


    /**
     * 系統對使用者發送通知
     * @param string|int|array $receive 接收人ID 多個時以英文的","分割或傳入陣列
     * @param string           $type    通知類型, 必須與模版的類型相同, 使用下劃線分割應用.
     *                                  如$type = "weibo_follow"定位至/apps/weibo/Language/cn/notify.php的"weibo_follow"
     * @param array            $data
     * @return void
     */
    public function sendIn( $receive , $type , $data  ) {
        return $this->__put( $receive , $type , $data  , 0 , true );
    }

        /**
     +----------------------------------------------------------
     * Description 通知發送處理
     +----------------------------------------------------------
     * @author Nonant nonant@thinksns.com
     +----------------------------------------------------------
     * @param $type    通知類型
     * @param $receive 通知接收者的使用者ID,類型可為 數字、字元串、陣列
     * @param $title   通知標題
     * @param $body    通知內容
     * @param $from    通知發送者UID
     * @param $system  是否為系統通知
     +----------------------------------------------------------
     * @return Boolen
     +----------------------------------------------------------
     * Create at  2010-9-13 下午04:24:53
     +----------------------------------------------------------
         */
    private function __put($receive,$type,$data,$from=0,$system=false) {
        global $ts;
        $receive = $this->_parseUser( $receive ); if(!$receive) return false;
        $from = ( $system==false &&  $from==0 ) ? $ts['user']['uid'] : $from ;
        $data      = addslashes(serialize( $data ));
        $time       = time();

        //優化大批量發送通知，講資料切割處理，每次插入100條
        $receive    =   array_chunk($receive, 100)  ;
        foreach ($receive as $receive_chunck){

            foreach ($receive_chunck as $k=>$v){
                if($v==$from) continue;
                $sqlArr[] = "($from,$v,'$type','$data',$time)";
}

if( $sqlArr ){
    $sql = "INSERT INTO ".C('DB_PREFIX')."notify (`from`,`receive`,`type`,`data`,`ctime`) values ".implode(',',$sqlArr);
    $result[] = M('Notify')->execute($sql);

}

unset($sql,$sqlArr,$receive_chunck);
}

return $result;
}

//解析傳入的使用者ID
private function _parseUser($touid){
    if( is_numeric($touid) ){
        $sendto[] = $touid;
}elseif ( is_array($touid) ){
    $sendto = $touid;
}elseif (strpos($touid,',') !== false){
    $touid = array_unique(explode(',',$touid));
    foreach ($touid as $key=>$value){
        $sendto[] = $value;
}
}else{
    $sendto = false;
}
return $sendto;
}


}
