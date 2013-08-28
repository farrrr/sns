<?php
/**
 * 微博模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FeedModel extends Model {

    protected $tableName = 'feed';
    protected $fields = array('feed_id','uid','type','app','app_row_id','app_row_table','publish_time','is_del','from','comment_count','repost_count','comment_all_count','digg_count','is_repost','is_audit','_pk'=>'feed_id');

    public $templateFile = '';          // 模板檔案

    /**
     * 添加微博
     * @param integer $uid 操作使用者ID
     * @param string $app 微博應用類型，默認為public
     * @param string $type 微博類型，
     * @param array $data 微博相關資料
     * @param integer $app_id 應用資源ID，默認為0
     * @param string $app_table 應用資源表名，默認為feed
     * @param array  $extUid 額外使用者ID，默認為null
     * @param array $lessUids 去除的使用者ID，默認為null
     * @param boolean $isAtMe 是否為進行發送，默認為true
     * @return mix 添加失敗返回false，成功返回新的微博ID
     */
    public function put($uid, $app = 'public', $type = '', $data = array(), $app_id = 0, $app_table = 'feed', $extUid = null, $lessUids = null, $isAtMe = true, $is_repost = 0) {
        // 判斷資料的正確性
        if(!$uid || $type == '') {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');
            return false;
        }
        if ( strpos( $type , 'postvideo' ) !== false ){
            $type = 'postvideo';
        }
        //微博類型合法性驗證 - 臨時解決方案
        if ( !in_array( $type , array('post','repost','postvideo','postfile','postimage','weiba_post','weiba_repost') )){
            $type = 'post';
        }
        //應用類型驗證 用於分享框 - 臨時解決方案
        if ( !in_array( $app , array('public','weiba','tipoff') ) ){
            $app = 'public';
            $type = 'post';
            $app_table = 'feed';
        }

        $app_table = strtolower($app_table);
        // 添加feed表記錄
        $data['uid'] = $uid;
        $data['app'] = $app;
        $data['type'] = $type;
        $data['app_row_id'] = $app_id;
        $data['app_row_table'] = $app_table;
        $data['publish_time'] = time();
        $data['from'] = isset($data['from']) ? intval($data['from']) : getVisitorClient();
        $data['is_del'] = $data['comment_count'] = $data['repost_count'] = 0;
        $data['is_repost'] = $is_repost;
        //判斷是否先審後發
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $weibo_premission = $weiboSet['weibo_premission'];
        if(in_array('audit',$weibo_premission) || CheckPermission('core_normal','feed_audit')){
            $data['is_audit'] = 0;
        }else{
            $data['is_audit'] = 1;
        }
        // 微博內容處理
        if(Addons::requireHooks('weibo_publish_content')){
            Addons::hook("weibo_publish_content",array(&$data));
        }else{
            // 拼裝資料，如果是評論再轉發、回複評論等情況，需要額外疊加對話資料
            $data['body'] = str_replace(SITE_URL, '[SITE_URL]', preg_html($data['body']));
            // 獲取使用者發送的內容，僅僅以//進行分割
            $scream = explode('//', $data['body']);
            // 擷取內容資訊為微博內容字數 - 重點
            $feedConf = model('Xdata')->get('admin_Config:feed');
            $feedNums = $feedConf['weibo_nums'];
            $body = array();
            foreach($scream as $value) {
                $tbody[] = $value;
                $bodyStr = implode('//', $tbody);
                if(get_str_length(ltrim($bodyStr)) > $feedNums) {
                    break;
                }
                $body[] = $value;
                unset($bodyStr);
            }
            $data['body'] = implode('//', $body);
            // 獲取使用者釋出內容
            $data['content'] = trim($scream[0]);
        }

        //分享到微博的應用資源，加入原資源連結
        $data['body'] .= $data['source_url'];
        $data['content'] .= $data['source_url'];

        // 微博類型插件鉤子
        // if($type){
        //  $addonsData = array();
        //  Addons::hook("weibo_type",array("typeId"=>$type,"typeData"=>$type_data,"result"=>&$addonsData));
        //  $data = array_merge($data,$addonsData);
        // }
        if( $type == 'postvideo' ){
            $typedata = model('Video')->_weiboTypePublish( $_POST['videourl'] );
            if ( $typedata && $typedata['flashvar'] && $typedata['flashimg'] ){
                $data = array_merge( $data , $typedata );
            } else {
                $data['type'] = 'post';
            }

        }
        // 添加微博資訊
        $feed_id =  $this->data($data)->add();
        if(!$feed_id) return false;
        if(!$data['is_audit']){
            $touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
            foreach($touid as $k=>$v){
                model('Notify')->sendNotify($v['uid'], 'feed_audit');
            }
        }
        // 目前處理方案格式化資料
        $data['content'] = str_replace(chr(31), '', $data['content']);
        $data['body'] = str_replace(chr(31), '', $data['body']);
        // 添加關聯資料
        $feed_data = D('FeedData')->data(array('feed_id'=>$feed_id,'feed_data'=>serialize($data),'client_ip'=>get_client_ip(),'feed_content'=>$data['body']))->add();

        // 添加微博成功後
        if($feed_id && $feed_data) {
            //微博釋出成功後的鉤子
            //Addons::hook("weibo_publish_after",array('weibo_id'=>$feed_id,'post'=>$data));

            // 發送通知訊息 - 重點 - 需要簡化把上節點的資訊去掉.
            if($data['is_repost'] == 1) {
                // 轉發微博
                $isAtMe && $content = $data['content'];                                 // 內容使用者
                $extUid[] = $data['sourceInfo']['transpond_data']['uid'];               // 資源作者使用者
                if($isAtMe && !empty($data['curid'])) {
                    // 上節點使用者
                    $appRowData = $this->get($data['curid']);
                    $extUid[] = $appRowData['uid'];
                }
            } else {
                // 其他微博
                $content = $data['content'];
                //更新最近@的人
                model( 'Atme' )->updateRecentAt( $content );                                // 內容使用者
            }
            // 發送@訊息
            model('Atme')->setAppName('Public')->setAppTable('feed')->addAtme($content, $feed_id, $extUid, $lessUids);

            $data['client_ip'] = get_client_ip();
            $data['feed_id'] = $feed_id;
            $data['feed_data'] = serialize($data);
            // 主動創建渲染後的快取
            $return = $this->setFeedCache($data);
            $return['user_info'] = model('User')->getUserInfo($uid);
            $return['GroupData'] = model('UserGroupLink')->getUserGroupData($uid);   //獲取使用者組資訊
            $return['feed_id'] = $feed_id;
            $return['app_row_id'] = $data['app_row_id'];
            $return['is_audit'] = $data['is_audit'];
            // 統計數修改
            model('UserData')->setUid($uid)->updateKey('feed_count', 1);
            // if($app =='public'){ //TODO 微博驗證條件
            model('UserData')->setUid($uid)->updateKey('weibo_count', 1);
            // }
            if(!$return) {
                $this->error = L('PUBLIC_CACHE_FAIL');              // Feed快取寫入失敗
            }
            return $return;
        } else {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');        // 操作失敗
            return false;
        }
    }

    /**
     * 獲取指定微博的資訊
     * @param integer $feed_id 微博ID
     * @return mix 獲取失敗返回false，成功返回微博資訊
     */
    public function get($feed_id) {
        $feed_list = $this->getFeeds(array($feed_id));
        if(!$feed_list) {
            $this->error = L('PUBLIC_INFO_GET_FAIL');           // 獲取資訊失敗
            return false;
        } else {
            return $feed_list[0];
        }
    }

    /**
     * 獲取指定微博的資訊，用於資源模型輸出???
     * @param integer $id 微博ID
     * @param boolean $forApi 是否提供API資料，默認為false
     * @return array 指定微博資料
     */
    public function getFeedInfo($id, $forApi = false) {
        $data = model( 'Cache' )->get( 'feed_info_'.$id );
        if ( $data !== false ){
            return $data;
        }

        $map['a.feed_id'] = $id;

        // //過濾已刪除的微博 wap 版收藏
        // if($forApi){
        //  $map['a.is_del'] = 0;
        // }

        $data = $this->where($map)
            ->table("{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id ")
            ->find();

        $fd = unserialize($data['feed_data']);

        $userInfo = model('User')->getUserInfo($data['uid']);
        $data['ctime'] = date('Y-m-d H:i',$data['publish_time']);
        $data['content'] = $forApi ? parseForApi($fd['body']):$fd['body'];
        $data['uname'] = $userInfo['uname'];
        $data['user_group'] = $userInfo['api_user_group'];
        $data['avatar_big'] = $userInfo['avatar_big'];
        $data['avatar_middle'] = $userInfo['avatar_middle'];
        $data['avatar_small']  = $userInfo['avatar_small'];
        unset($data['feed_data']);

        // 微博轉發
        if($data['type'] == 'repost'){
            $data['transpond_id'] = $data['app_row_id'];
            $data['transpond_data'] = $this->getFeedInfo($data['transpond_id'], $forApi);
        }

        // 附件處理
        if(!empty($fd['attach_id'])) {
            $data['has_attach'] = 1;
            $attach = model('Attach')->getAttachByIds($fd['attach_id']);
            foreach($attach as $ak => $av) {
                $_attach = array(
                    'attach_id'   => $av['attach_id'],
                    'attach_name' => $av['name'],
                    'attach_url'  => getImageUrl($av['save_path'].$av['save_name']),
                    'extension'   => $av['extension'],
                    'size'        => $av['size']
                );
                if($data['type'] == 'postimage') {
                    $_attach['attach_small'] = getImageUrl($av['save_path'].$av['save_name'], 100, 100, true);
                    $_attach['attach_middle'] = getImageUrl($av['save_path'].$av['save_name'], 550);
                }
                $data['attach'][] = $_attach;
            }
        } else {
            $data['has_attach'] = 0;
        }

        if( $data['type'] == 'postvideo' ){
            $data['host'] = $fd['host'];
            $data['flashvar'] = $fd['flashvar'];
            $data['source'] = $fd['source'];
            $data['flashimg'] = $fd['flashimg'];
            $data['title'] = $fd['title'];
        }

        $data['feedType'] = $data['type'];

        // 是否收藏微博
        if($forApi) {
            $data['iscoll'] = model('Collection')->getCollection($data['feed_id'],'feed');
            if(empty($data['iscoll'])) {
                $data['iscoll']['colled'] = 0;
            } else {
                $data['iscoll']['colled'] = 1;
            }
        }

        // 微博詳細資訊
        $feedInfo = $this->get($id);
        $data['source_body'] = $feedInfo['body'];
        $data['api_source'] = $feedInfo['api_source'];
        //一分鐘快取
        model( 'Cache' )->set( 'feed_info_'.$id , $data , 60);
        if($forApi){
            $data['content'] = real_strip_tags($data['content']);
            unset($data['is_del'],$data['is_audit'],$data['from_data'],$data['app_row_table'],$data['app_row_id']);
            unset($data['source_body']);
        }
        return $data;
    }

    /**
     * 獲取微博列表
     * @param array $map 查詢條件
     * @param integer $limit 結果集數目，默認為10
     * @return array 微博列表資料
     */
    public function getList($map, $limit = 10 , $order = 'publish_time DESC') {
        $feedlist = $this->field('feed_id')->where($map)->order($order)->findPage($limit);
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);
        return $feedlist;
    }

    /**
     * 獲取指定使用者所關注人的所有微博，默認為當前登入使用者
     * @param string $where 查詢條件
     * @param integer $limit 結果集數目，默認為10
     * @param integer $uid 指定使用者ID，默認為空
     * @param integer $fgid 關組組ID，默認為空
     * @return array 指定使用者所關注人的所有微博，默認為當前登入使用者
     */
    public function getFollowingFeed($where = '', $limit = 10, $uid = '', $fgid = '') {
        $buid = empty($uid) ? $_SESSION['mid'] : $uid;
        $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}user_follow AS b ON a.uid=b.fid AND b.uid = {$buid}";
        // 加上自己的資訊，若不需要遮蔽下語句
        $_where = !empty($where) ? "(a.uid = '{$buid}' OR b.uid = '{$buid}') AND ($where)" : "(a.uid = '{$buid}' OR b.uid = '{$buid}')";
        // 若填寫了關注分組
        if(!empty($fgid)) {
            $table .=" LEFT JOIN {$this->tablePrefix}user_follow_group_link AS c ON a.uid = c.fid AND c.uid ='{$buid}' ";
            $_where .= " AND c.follow_group_id = ".intval($fgid);
        }

        $feedlist = $this->table($table)->where($_where)->field('a.feed_id')->order('a.publish_time DESC')->findPage($limit);
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);

        return $feedlist;
    }

    /**
     * 獲取指定使用者收藏的微博列表，默認為當前登入使用者
     * @param array $map 查詢條件
     * @param integer $limit 結果集數目，默認為10
     * @param integer $uid 指定使用者ID，默認為空
     * @return array 指定使用者收藏的微博列表，默認為當前登入使用者
     */
    public function getCollectionFeed($map, $limit = 10, $uid = '') {
        $map['uid'] = empty($uid) ? $_SESSION['mid'] : $uid;
        $map['source_table_name'] = 'feed';
        $table = "{$this->tablePrefix}collection";
        $feedlist = $this->table($table)->where($map)->field('source_id AS feed_id')->order('source_id DESC')->findPage($limit);
        $feed_ids = getSubByKey($feedlist['data'],'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);

        return $feedlist;
    }

    /**
     * 獲取指定使用者所關注人的微博列表
     * @param array $map 查詢條件
     * @param integer $uid 使用者ID
     * @param string $app 應用名稱
     * @param integer $type 應用類型
     * @param integer $limit 結果集數目，默認為10
     * @return array 指定使用者所關注人的微博列表
     */
    public function getFollowingList($map,$uid, $app, $type, $limit = 10) {
        // 讀取列表
        $map['_string'] = "uid IN (SELECT fid FROM {$this->tablePrefix}user_follow WHERE uid={$uid}) OR uid={$uid}";
        !empty($app) && $map['app'] = $app;
        !empty($type) && $map['type'] = $type;
        if ( $map['type'] == 'post' ){
            unset($map['type']);
            $map['is_repost'] = 0;
        }
        $feedlist = $this->field('feed_id')->where($map)->order("publish_time DESC")->findPage($limit);
        if(!$feedlist) {
            $this->error = L('PUBLIC_INFO_GET_FAIL');           // 獲取資訊失敗
            return false;
        }
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);

        return $feedlist;
    }

    /**
     * 檢視指定使用者的微博列表
     * @param array $map 查詢條件
     * @param integer $uid 使用者ID
     * @param string $app 應用類型
     * @param string $type 微博類型
     * @param integer $limit 結果集數目，默認為10
     * @return array 指定使用者的微博列表資料
     */
    public function getUserList($map, $uid, $app, $type, $limit = 10) {
        if(!$uid) {
            $this->error = L('PUBLIC_WRONG_DATA');              // 獲取資訊失敗
            return false;
        }
        !empty($app) && $map['app'] = $app;
        !empty($type) && $map['type'] = $type;
        if( $map['type'] == 'post' ){
            unset($map['type']);
            $map['is_repost'] = 0;
        }
        $map['uid'] = $uid;
        $list = $this->getList($map, $limit);

        return $list;
    }

    /**
     * 獲取指定使用者的最後一條微博資料
     * @param array $uids 使用者ID
     * @return array 指定使用者的最後一條微博資料
     */
    public function  getLastFeed($uids) {
        if(empty($uids)) {
            return false;
        }

        !is_array($uids) && $uids = explode(',', $uids);
        $map['uid'] = array('IN', $uids);
        $map['is_del'] = 0;
        $feeds = $this->where($map)->field('MAX(feed_id) AS feed_id,uid')->group('uid')->getAsFieldArray('feed_id');
        $feedlist = $this->getFeeds($feeds);
        $r = array();
        foreach($feedlist as $v) {
            if(!empty($v['sourceInfo'])) {
                $r[$v['uid']] = array('feed_id'=>$v['feed_id'],'title'=>getShort(t($v['sourceInfo']['shareHtml']), 30, '...'));
            } else {
                $t = unserialize($v['feed_data']);
                $r[$v['uid']] = array('feed_id'=>$v['feed_id'],'title'=>getShort(t($t['body']), 30, '...'));
            }
        }

        return $r;
    }

    /**
     * 獲取給定微博ID的微博資訊
     * @param array $feed_ids 微博ID陣列
     * @return array 給定微博ID的微博資訊
     */
    public function getFeeds($feed_ids) {
        $feedlist = array();
        $feed_ids = array_filter(array_unique($feed_ids));

        // 獲取資料
        if(count($feed_ids) > 0) {
            $cacheList = model('Cache')->getList('fd_', $feed_ids);
        } else {
            return false;
        }

        // 按照傳入ID順序進行排序
        foreach($feed_ids as $key => $v) {
            if($cacheList[$v]) {
                $feedlist[$key] = $cacheList[$v];
            } else {
                $feed = $this->setFeedCache(array(), $v);
                $feedlist[$key] = $feed[$v];
            }
        }
        return $feedlist;
    }

    /**
     * 清除指定使用者指定微博的列表快取
     * @param array $feed_ids 微博ID陣列，默認為空
     * @param integer $uid 使用者ID，默認為空
     * @return void
     */
    public function cleanCache($feed_ids = array(), $uid = '') {
        if(!empty($uid)) {
            model('Cache')->rm('fd_foli_'.$uid);
            model('Cache')->rm('fd_uli_'.$uid);
        }
        if(empty($feed_ids)) {
            return true;
        }
        if(is_array($feed_ids)) {
            foreach($feed_ids as $v) {
                model('Cache')->rm('fd_'.$v);
                model('Cache')->rm('feed_info_'.$v);
            }
        } else {
            model('Cache')->rm('fd_'.$feed_ids);
            model('Cache')->rm('feed_info_'.$feed_ids);
        }
    }

    /**
     * 更新指定微博的快取
     * @param array $feed_ids 微博ID陣列，默認為空
     * @param string $type 操作類型，默認為update
     * @return bool true
     */
    public function updateFeedCache($feed_ids, $type = 'update') {
        if($type == 'update') {
            $this->getFeeds($feed_ids);
        } else {
            foreach($feed_ids as $v) {
                model('Cache')->rm('fd_'.$v);
            }
        }

        return true;
    }

    /**
     * 生成指定微博的快取
     * @param array $value 微博相關資料
     * @param array $feed_id 微博ID陣列
     */
    private function setFeedCache($value = array(), $feed_id = array()) {
        if(!empty($feed_id)) {
            !is_array($feed_id) && $feed_id = explode(',', $feed_id);
            $map['a.feed_id'] = array('IN', $feed_id);
            $list = $this->where($map)
                ->field('a.*,b.client_ip,b.feed_data')
                ->table("{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id")
                ->findAll();

            $r = array();
            foreach($list as &$v) {
                // 格式化資料模板
                $parseData = $this->__paseTemplate($v);
                $v['info'] = $parseData['info'];
                $v['title'] = $parseData['title'];
                $v['body'] = $parseData['body'];
                $v['api_source'] = $parseData['api_source'];
                $v['actions'] = $parseData['actions'];
                $v['user_info'] = $parseData['userInfo'];
                $v['GroupData'] = model('UserGroupLink')->getUserGroupData($v['uid']);
                model('Cache')->set('fd_'.$v['feed_id'], $v);           // 1分鐘快取
                $r[$v['feed_id']] = $v;
            }

            return $r;
        } else {
            // 格式化資料模板
            $parseData = $this->__paseTemplate($value);
            $value['info'] = $parseData['info'];
            $value['title'] = $parseData['title'];
            $value['body'] = $parseData['body'];
            $value['api_source'] = $parseData['api_source'];
            $value['actions'] = $parseData['actions'];
            $value['user_info'] = $parseData['userInfo'];
            $value['GroupData'] = model('UserGroupLink')->getUserGroupData($value['uid']);
            model('Cache')->set('fd_'.$value['feed_id'], $value);       // 1分鐘快取
            return $value;
        }
    }

    /**
     * 解析微博模板標籤
     * @param array $_data 微博的原始資料
     * @return array 解析微博模板後的微博資料
     */
    private function __paseTemplate($_data) {
        // 獲取作者資訊
        $user = model('User')->getUserInfo($_data['uid']);
        // 處理資料
        $_data['data'] = unserialize($_data['feed_data']);
        // 模版變數賦值
        $var = $_data['data'];
        if(!empty($var['attach_id'])) {
            $var['attachInfo'] = model('Attach')->getAttachByIds($var['attach_id']);
            foreach($var['attachInfo'] as $ak => $av) {
                $_attach = array(
                    'attach_id'   => $av['attach_id'],
                    'attach_name' => $av['name'],
                    'attach_url'  => getImageUrl($av['save_path'].$av['save_name']),
                    'extension'   => $av['extension'],
                    'size'        => $av['size']
                );
                if($_data['type'] == 'postimage') {
                    $_attach['attach_small'] = getImageUrl($av['save_path'].$av['save_name'], 100, 100, true);
                    $_attach['attach_middle'] = getImageUrl($av['save_path'].$av['save_name'], 550);
                }
                $var['attachInfo'][$ak] = $_attach;
            }
        }
        if ( $_data['type'] == 'postvideo' && !$var['flashimg']){
            $var['flashimg'] = '__THEME__/image/video.png';
        }
        $var['uid'] = $_data['uid'];
        $var["actor"] = "<a href='{$user['space_url']}' class='name' event-node='face_card' uid='{$user['uid']}'>{$user['uname']}</a>";
        $var["actor_uid"] = $user['uid'];
        $var["actor_uname"] = $user['uname'];
        $var['feedid'] = $_data['feed_id'];
        //微吧類型微博用到
        // $var["actor_groupData"] = model('UserGroupLink')->getUserGroupData($user['uid']);
        //需要獲取資源資訊的微博：所有類型的微博，只要有資源資訊就獲取資源資訊並賦值模版變數，交給模版解析處理
        if(!empty($_data['app_row_id'])) {
            empty($_data['app_row_table']) && $_data['app_row_table'] = 'feed';
            $var['sourceInfo'] = model('Source')->getSourceInfo($_data['app_row_table'], $_data['app_row_id'], false, $_data['app']);
            $var['sourceInfo']['groupData'] = model('UserGroupLink')->getUserGroupData($var['sourceInfo']['source_user_info']['uid']);
        }
        // 解析Feed模版
        $feed_template_file = APPS_PATH.'/'.$_data['app'].'/Conf/'.$_data['type'].'.feed.php';
        if(!file_exists($feed_template_file)){
            $feed_template_file = APPS_PATH.'/public/Conf/post.feed.php';
        }
        $feed_xml_content = fetch($feed_template_file, $var);
        $s = simplexml_load_string($feed_xml_content);
        if(!$s){
            return false;
        }
        $result = $s->xpath("//feed[@type='".t($_data['type'])."']");
        $actions = (array) $result[0]->feedAttr;
        //輸出模版解析後資訊
        $return["userInfo"]  = $user;
        $return["actor_groupData"] = $var["actor_groupData"];
        $return['title'] = trim((string) $result[0]->title);
        $return['body'] =  trim((string) $result[0]->body);
        // $return['sbody'] = trim((string) $result[0]->sbody);
        $return['info'] =  trim((string) $result[0]['info']);
        //$return['title'] =  parse_html($return['title']);
        $return['body']  =  parse_html($return['body']);
        $return['api_source'] = $var['sourceInfo'];
        // $return['sbody'] =  parse_html($return['sbody']);
        $return['actions'] = $actions['@attributes'];
        //驗證轉發的原資訊是否存在
        if(!$this->_notDel($_data['app'],$_data['type'],$_data['app_row_id'])) {
            $return['body'] = L('PUBLIC_INFO_ALREADY_DELETE_TIPS');             // 此資訊已被刪除〜
        }
        return $return;
    }

    /**
     * 判斷資源是否已被刪除
     * @param string $app 應用名稱
     * @param string $feedtype 動態類型
     * @param integer $app_row_id 資源ID
     * @return boolean 資源是否存在
     */
    private function _notDel($app, $feedtype, $app_row_id) {
        // TODO:該方法為完成？
        // 非轉發的內容，不需要驗證
        if(empty($app_row_id)){
            return true;
        }
        return true;
    }

    /**
     * 獲取所有微博節點列表 - 預留後臺檢視、編輯微博模板檔案
     * @param boolean $ignore 從微博設定裡面獲取，默認為false
     * @return array 所有微博節點列表
     */
    public function getNodeList($ignore = false) {
        if(false===($feedNodeList=S('FeedNodeList'))){
            //應用列表
            $apps = C('DEFAULT_APPS');
            $appList = model('App')->getAppList();
            foreach($appList as $app){
                $apps[] = $app['app_name'];
            }
            //獲得所有feed配置檔案
            require_once ADDON_PATH.'/library/io/Dir.class.php';
            $dirs = new Dir(SITE_PATH,'*.feed.php');
            foreach($apps as $app){
                $app_config_path = SITE_PATH.'/apps/'.$app.'/Conf/';
                $dirs->listFile($app_config_path,'*.feed.php');
                $files = $dirs->toArray();
                if(is_array($files) && count($files)>0){
                    foreach($files as $file){
                        $feed_file['app'] = $app;
                        $feed_file['filename']= $file['filename'];
                        $feed_file['pathname']= $file['pathname'];
                        $feed_file['mtime']= $file['mtime'];
                        $feedNodeList[] = $feed_file;
                    }
                }
            }
            S('FeedNodeList',$feedNodeList);
        }
        return $feedNodeList;
        // $xml = simplexml_load_file( $this->_getFeedXml() );
        // $feed = $xml->feedlist->feed;
        // $list = array();
        // foreach($feed as $key => $v) {
        //  $app = (string)$v['app'];
        //  $type = (string)$v['type'];
        //  $list[$app][] = array(
        //      'app'=>$app,
        //      'type'=>$type,
        //      'info'=>(string)$v['info']
        //  );
        // }
        // return $list;
    }

    /**
     * 獲取微博模板的XML檔案路徑
     * @param boolean $set 是否重新生成微博模板XML檔案
     * @return string 微博模板的XML檔案路徑
     */
    public function _getFeedXml($set = false) {
        if($set || !file_exists(SITE_PATH.'/config/feeds.xml')) {
            $data = D('feed_node')->findAll();
            $xml = "<?xml version='1.0' encoding='UTF-8'?>
                <root>
                <feedlist>";
            foreach($data as $v) {
                $xml.="
                    <feed app='{$v['appname']}' type='{$v['nodetype']}' info='{$v['nodeinfo']}'>
                    ".htmlspecialchars_decode($v['xml'])."
                    </feed>";
            }
            $xml .= "</feedlist>
                </root>";

            file_put_contents(SITE_PATH.'/config/feeds.xml', $xml);
            chmod(SITE_PATH.'/config/feeds.xml', 0666);
        }

        return SITE_PATH.'/config/feeds.xml';
    }

    /**
     * 微博操作，徹底刪除、假刪除、回覆
     * @param integer $feed_id 微博ID
     * @param string $type 微博操作類型，deleteFeed：徹底刪除，delFeed：假刪除，feedRecover：恢復
     * @param string $title 日誌內容，目前沒沒有該功能
     * @param string $uid 刪除微博的使用者ID（區別超級管理員）
     * @return array 微博操作後的結果資訊陣列
     */
    public function doEditFeed($feed_id, $type, $title ,$uid = null) {
        $return = array('status'=>'0');
        if(empty($feed_id)) {
            //$return['data'] = '微博ID不能為空！';
        } else {
            $map['feed_id'] = is_array($feed_id) ? array('IN', $feed_id) : intval($feed_id);
            $save['is_del'] = $type =='delFeed' ? 1 : 0;

            if($type == 'deleteFeed') {
                $feedArr = is_array($feed_id) ? $feed_id : explode(',', $feed_id);
                // 取消微博收藏
                foreach ($feedArr as $sid) {
                    $feed = $this->where('feed_id='.$sid)->find();
                    model('Collection')->delCollection($sid, 'feed', $feed['uid']);
                }
                // 徹底刪除微博
                $res = $this->where($map)->delete();
                // 刪除微博相關資訊
                if($res) {
                    $this->_deleteFeedAttach($feed_id, 'deleteAttach');
                }
            } else {
                $ids = !is_array($feed_id) ? array($feed_id) : $feed_id;
                $feedList = $this->getFeeds($ids);
                $res = $this->where($map)->save($save);
                if($type == 'feedRecover'){
                    // 添加微博數
                    foreach($feedList as $v) {
                        model('UserData')->setUid($v['user_info']['uid'])->updateKey('feed_count', 1);
                        model('UserData')->setUid($v['user_info']['uid'])->updateKey('weibo_count', 1);
                    }
                    $this->_deleteFeedAttach($ids, 'recoverAttach');
                } else {
                    // 減少微博數
                    foreach($feedList as $v) {
                        model('UserData')->setUid($v['user_info']['uid'])->updateKey('feed_count', -1);
                        model('UserData')->setUid($v['user_info']['uid'])->updateKey('weibo_count', -1);
                    }
                    $this->_deleteFeedAttach($ids, 'delAttach');
                    // 刪除頻道相應微博
                    $channelMap['feed_id'] = array('IN', $ids);
                    D('channel')->where($channelMap)->delete();
                }
                $this->cleanCache($ids);        // 刪除微博快取資訊
                // 資源微博快取相關微博
                $sids = $this->where('app_row_id='.$feed_id)->getAsFieldArray('feed_id');
                $this->cleanCache($sids);
            }
            // 刪除評論資訊
            $cmap['app'] = 'Public';
            $cmap['table'] = 'feed';
            $cmap['row_id'] = is_array($feed_id) ? array('IN', $feed_id) : intval($feed_id);
            $commentIds = model('Comment')->where($cmap)->getAsFieldArray('comment_id');
            model('Comment')->setAppName('Public')->setAppTable('feed')->deleteComment($commentIds);
            if($res) {
                // TODO:是否記錄日誌，以及後期快取處理
                $return = array('status'=>1);
                //添加積分
                model('Credit')->setUserCredit($uid,'delete_weibo');
            }
        }

        return $return;
    }

    /**
     * 刪除微博相關附件資料
     * @param array $feedIds 微博ID陣列
     * @param string $type 刪除附件類型
     * @return void
     */
    private function _deleteFeedAttach($feedIds, $type)
    {
        // 查詢微博內是否存在附件
        $feeddata = $this->getFeeds($feedIds);
        $feedDataInfo = getSubByKey($feeddata, 'feed_data');
        $attachIds = array();
        foreach($feedDataInfo as $value) {
            $value = unserialize($value);
            !empty($value['attach_id']) && $attachIds = array_merge($attachIds, $value['attach_id']);
        }
        array_filter($attachIds);
        array_unique($attachIds);
        !empty($attachIds) && model('Attach')->doEditAttach($attachIds, $type, '');
    }

    /**
     * 稽覈通過微博
     * @param integer $feed_id 微博ID
     * @return array 微博操作後的結果資訊陣列
     */
    public function doAuditFeed($feed_id){
        $return = array('status'=>'0');
        if(empty($feed_id)) {
            $return['data'] = '請選擇微博！';
        } else {
            $map['feed_id'] = is_array($feed_id) ? array('IN', $feed_id) : intval($feed_id);
            $save['is_audit'] = 1;
            $res = $this->where($map)->save($save);
            if($res) {
                $return = array('status'=>1);
            }

            //更新快取
            $this->cleanCache($feed_id);
        }
        return $return;
    }

    /*** 搜索引擎使用 ***/
    /**
     * 搜索微博
     * @param string $key 關鍵字
     * @param string $type 搜索類型，following、all、space
     * @param integer $loadId 載入微博ID，從此微博ID開始搜索
     * @param integer $limit 結果集數目
     * @param boolean $forApi 是否返回API資料，默認為false
     * @return array 搜索後的微博資料
     */
    public function searchFeed($key, $type, $loadId, $limit, $forApi = false,$feed_type) {
        switch($type){
        case 'following':
            $buid = $GLOBALS['ts']['uid'];
            $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}user_follow AS b ON a.uid=b.fid AND b.uid = {$buid} LEFT JOIN {$this->tablePrefix}feed_data AS c ON a.feed_id = c.feed_id";
            $where = !empty($loadId) ? " a.is_del = 0 AND a.is_audit = 1 AND a.feed_id <'{$loadId}'" : "a.is_del = 0 AND a.is_audit = 1";
            $where .= " AND (a.uid = '{$buid}' OR b.uid = '{$buid}' )";
            $where .= " AND c.feed_data LIKE '%".t($key)."%'";
            $feedlist = $this->table($table)->where($where)->field('a.feed_id')->order('a.publish_time DESC')->findPage($limit);
            break;
        case 'all':
            $map['a.is_del'] = 0;
            $map['a.is_audit'] = 1;
            !empty($loadId) && $map['a.feed_id'] = array('LT', intval($loadId));
            $map['b.feed_content'] = array('LIKE', '%'.t($key).'%');
            if($feed_type){
                $map['a.type'] = $feed_type;
                if ( $map['a.type'] == 'post' ){
                    unset($map['a.type']);
                    $map['a.is_repost'] = 0;
                }
            }
            $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id";
            $feedlist = $this->table($table)->field('a.feed_id')->where($map)->order('a.publish_time DESC')->findPage($limit);
            break;
        case 'space':
            $map['a.is_del'] = 0;
            $map['a.is_audit'] = 1;
            !empty($loadId) && $map['a.feed_id'] = array('LT', intval($loadId));
            $map['b.feed_content'] = array('LIKE', '%'.t($key).'%');
            if($feed_type){
                $map['a.type'] = $feed_type;
                if ( $map['a.type'] == 'post' ){
                    unset($map['a.type']);
                    $map['a.is_repost'] = 0;
                }
            }
            $map['a.uid'] = $GLOBALS['ts']['uid'];
            $table = " {$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id";
            $feedlist = $this->table($table)->field('a.feed_id')->where($map)->order('a.publish_time DESC')->findPage($limit);
            break;
        case 'topic':
            $map['a.is_del'] = 0;
            $map['a.is_audit'] = 1;
            !empty($loadId) && $map['a.feed_id'] = array('LT', intval($loadId));
            $map['b.feed_content'] = array('LIKE', '%#'.t($key).'#%');
            if($feed_type){
                $map['a.type'] = $feed_type;
                if ( $map['a.type'] == 'post' ){
                    unset($map['a.type']);
                    $map['a.is_repost'] = 0;
                }
            }
            $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id";
            $feedlist = $this->table($table)->field('a.feed_id')->where($map)->order('a.publish_time DESC')->findPage($limit);
            break;
        }
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        if($forApi) {
            return $this->formatFeed($feed_ids, true);
        }
        $feedlist['data'] = $this->getFeeds($feed_ids);

        return $feedlist;
    }

    /**
     * 資料庫搜索微博
     * @param string $key 關鍵字
     * @param string $type 微博類型，post、repost、postimage、postfile
     * @param integer $limit 結果集數目
     * @param boolean $forApi 是否返回API資料，默認為false
     * @return array 搜索後的微博資料
     */
    public function searchFeeds($key, $feed_type, $limit, $Stime, $Etime) {
        $map['a.is_del'] = 0;
        $map['a.is_audit'] = 1;
        $map['b.feed_content'] = array('LIKE', '%'.t($key).'%');
        if($feed_type){
            $map['a.type'] = $feed_type;
            if ( $map['a.type'] == 'post' ){
                unset($map['a.type']);
                $map['a.is_repost'] = 0;
            }
        }
        if($Stime && $Etime){
            $map['a.publish_time'] = array('between',array($Stime,$Etime));
        }
        $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}feed_data AS b ON a.feed_id = b.feed_id";
        $feedlist = $this->table($table)->field('a.feed_id')->where($map)->order('a.publish_time DESC')->findPage($limit);
        //return D()->getLastSql();exit;
        $feed_ids = getSubByKey($feedlist['data'], 'feed_id');
        $feedlist['data'] = $this->getFeeds($feed_ids);
        foreach($feedlist['data'] as &$v) {
            switch ( $v['app'] ){
            case 'weiba':
                $v['from'] = getFromClient(0 , $v['app'] , '微吧');
                break;
            default:
                $v['from'] = getFromClient( $v['from'] , $v['app']);
                break;
            }
            !isset($uids[$v['uid']]) && $v['uid'] != $GLOBALS['ts']['mid'] && $uids[] = $v['uid'];
        }
        return $feedlist;
    }

    /*** API使用 ***/
    /**
     * 獲取全站最新的微博
     * @param string $type 微博類型,原創post,轉發repost,圖片postimage,附件postfile,視訊postvideo
     * @param integer $since_id 微博ID，從此微博ID開始，默認為0
     * @param integer $max_id 最大微博ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 分頁數，默認為1
     * @return array 全站最新的微博
     */
    public function public_timeline($type, $since_id = 0, $max_id = 0 ,$limit = 20 ,$page = 1) {
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);
        $where = " is_del = 0 ";
        //動態類型
        if(in_array($type,array('post','repost','postimage','postfile','postvideo'))){
            $where .= " AND type='$type' ";
        }
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND feed_id > {$since_id}";
            !empty($max_id) && $where .= " AND feed_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $feed_ids = $this->where($where)->field('feed_id')->limit("{$start},{$end}")->order('feed_id DESC')->getAsFieldArray('feed_id');
        return $this->formatFeed($feed_ids,true);
    }

    /**
     * 獲取登入使用者所關注人的最新微博
     * @param string $type 微博類型,原創post,轉發repost,圖片postimage,附件postfile,視訊postvideo
     * @param integer $mid 使用者ID
     * @param integer $since_id 微博ID，從此微博ID開始，默認為0
     * @param integer $max_id 最大微博ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 分頁數，默認為1
     * @return array 登入使用者所關注人的最新微博
     */
    public function friends_timeline($type, $mid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1) {
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);
        $where = " a.is_del = 0 ";
        //動態類型
        if(in_array($type,array('post','repost','postimage','postfile','postvideo'))){
            $where .= " AND a.type='$type' ";
        }
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND a.feed_id > {$since_id}";
            !empty($max_id) && $where .= " AND a.feed_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $table = "{$this->tablePrefix}feed AS a LEFT JOIN {$this->tablePrefix}user_follow AS b ON a.uid=b.fid AND b.uid = {$mid}";
        // 加上自己的資訊，若不需要此資料，請遮蔽下面語句
        $where = "(a.uid = '{$mid}' OR b.uid = '{$mid}') AND ($where)";
        $feed_ids = $this->where($where)->table($table)->field('a.feed_id')->limit("{$start},{$end}")->order('a.feed_id DESC')->getAsFieldArray('feed_id');

        return $this->formatFeed($feed_ids, true);
    }

    /**
     * 獲取指定使用者釋出的微博列表
     * @param string $type 微博類型,原創post,轉發repost,圖片postimage,附件postfile,視訊postvideo
     * @param integer $user_id 指定使用者ID
     * @param string $user_name 指定使用者名稱
     * @param integer $since_id 微博ID，從此微博ID開始，默認為0
     * @param integer $max_id 最大微博ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 分頁數，默認為1
     * @return array 指定使用者釋出的微博列表
     */
    public function user_timeline($type, $user_id , $user_name , $since_id = 0 , $max_id = 0 , $limit = 20 , $page = 1) {
        if(empty($user_id) && empty($user_name)) {
            return array();
        }
        if(empty($user_id)) {
            $user_info = model('User')->getUserInfoByName($user_name);
            $user_id = $user_info['uid'];
        }
        if(empty($user_id)) {
            return array();
        }
        $where = "uid = '{$user_id}' AND is_del = 0 ";
        //動態類型
        if(in_array($type,array('post','repost','postimage','postfile','postvideo'))){
            $where .= " AND type='$type' ";
        }
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);

        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND feed_id > {$since_id}";
            !empty($max_id) && $where .= " AND feed_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;

        $feed_ids = $this->field('feed_id')->where($where)->field('feed_id')->limit("{$start},{$end}")->order('publish_time DESC')->getAsFieldArray('feed_id');

        return $this->formatFeed($feed_ids, true);
    }

    /**
     * 獲取某條微博的被轉發列表
     * @param string $row_id 被轉發微博ID
     * @param integer $since_id 微博ID，從此微博ID開始，默認為0
     * @param integer $max_id 最大微博ID，默認為0
     * @param integer $limit 結果集數目，默認為20
     * @param integer $page 分頁數，默認為1
     * @return array 全站最新的微博
     */
    public function repost_timeline($row_id, $since_id = 0, $max_id = 0 ,$limit = 20 ,$page = 1) {
        $row_id = intval($row_id);
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $limit = intval($limit);
        $page = intval($page);
        if($row_id<=0){
            return false;
        }

        $where = " is_del = 0 AND type='repost' AND app_row_id=$row_id ";
        if(!empty($since_id) || !empty($max_id)) {
            !empty($since_id) && $where .= " AND feed_id > {$since_id}";
            !empty($max_id) && $where .= " AND feed_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $feed_ids = $this->where($where)->field('feed_id')->limit("{$start},{$end}")->order('feed_id DESC')->getAsFieldArray('feed_id');
        return $this->formatFeed($feed_ids,true);
        }

        /**
         * 格式化微博資料
         * @param array $feed_ids 微博ID陣列
         * @param boolean $forApi 是否為API資料，默認為false
         * @return array 格式化後的微博資料
         */
        public function formatFeed($feed_ids, $forApi = false) {
            if(empty($feed_ids)) {
                return array();
        } else {
            if(count($feed_ids) > 0 ) {
                $r = array();
                foreach($feed_ids as $feed_id) {
                    $r[] = $this->getFeedInfo($feed_id, $forApi);
        }
        return $r;
        } else {
            return array();
        }
        }
        }

        /**
         * 同步到微博
         * @param string content 內容
         * @param integer uid 釋出者uid
         * @param mixed attach_ids 附件ID
         * @return integer feed_id 微博ID
         */
        public function syncToFeed($content,$uid,$attach_ids,$from) {
            $d['content'] = '';
            $d['body'] = $content.'&nbsp;';
            $d['from'] = 0; //TODO
            if($attach_ids){
                $type = 'postimage';
                $d['attach_id'] = $attach_ids;
        }else{
            $type = 'post';
        }
        $feed = model('Feed')->put($uid, 'public', $type, $d, '', 'feed');
        return $feed['feed_id'];
        }

        /**
         * 分享到微博
         * @param string content 內容
         * @param integer uid 分享者uid
         * @param mixed attach_ids 附件ID
         * @return integer feed_id 微博ID
         */
        public function shareToFeed($content,$uid,$attach_ids,$from) {
            $d['content'] = '';
            $d['body'] = $content.'&nbsp;';
            $d['from'] = 0; //TODO
            if($attach_ids){
                $type = 'postimage';
                $d['attach_id'] = $attach_ids;
        }else{
            $type = 'post';
        }
        $feed = model('Feed')->put($uid, 'public', $type, $d, '', 'feed');
        return $feed['feed_id'];
        }
        }
