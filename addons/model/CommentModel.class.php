<?php
/**
 * 評論模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class CommentModel extends Model {

    protected $tableName = 'comment';
    protected $fields = array('comment_id','app','table','row_id','app_uid','uid','content','to_comment_id','to_uid','data','ctime','is_del','client_type','is_audit','storey');

    private $_app = null;                                                   // 所屬應用
    private $_app_table = null;                                             // 所屬資源表
    private $_app_pk_field = null;                                          // 應用主鍵欄位

    // private static $infoList = array();

    /**
     * 設定所屬應用
     * @param string $app 應用名稱
     * @return object 評論物件
     */
    public function setAppName($app) {
        $this->_app = $app;
        return $this;
    }

    /**
     * 設定相關內容所存儲的資源表
     * @param string $app_table 資料表名
     * @return object 評論物件
     */
    public function setAppTable($app_table) {
        $this->_app_table = $app_table;
        return $this;
    }

    /**
     * 獲取評論的種類，用於評論的Tab
     * @param array $map 查詢條件
     * @return array 評論種類與其資源數目
     */
    public function getTab($map) {
        $list = $this->field('COUNT(1) AS `nums`, `table`')->where($map)->group('`table`')->getHashList('table', 'nums');
        return $list;
    }

    /**
     * 獲取評論列表，已在後臺被使用
     * @param array $map 查詢條件
     * @param string $order 排序條件，默認為comment_id ASC
     * @param integer $limit 結果集數目，默認為10
     * @param boolean $isReply 是否顯示回覆資訊
     * @return array 評論列表資訊
     */
    public function getCommentList($map = null, $order = 'comment_id ASC', $limit = 10, $isReply = false) {
        !$map['app'] && $this->_app && ($map['app'] = $this->_app);
        !$map['table'] && $this->_app_table && ($map['table'] = $this->_app_table);
        !isset($map['is_del']) && ($map['is_del'] = 0);
        $data = $this->where($map)->order($order)->findPage($limit);
        // dump($data);exit;
        // TODO:後續優化
        foreach($data['data'] as &$v) {
            if(!empty($v['to_comment_id']) && $isReply) {
                $replyInfo = model('Comment')->setAppName($map['app'])->setAppTable($map['table'])->getCommentInfo(intval($v['to_comment_id']), false);
                $v['replyInfo'] = '//@{uid='.$replyInfo['user_info']['uid'].'|'.$replyInfo['user_info']['uname'].'}：'.$replyInfo['content'];
            }
            $v['user_info'] = model('User')->getUserInfo($v['uid']);
            $groupData = static_cache( 'groupdata'.$v['uid'] );
            if ( !$groupData ){
                $groupData = model('UserGroupLink')->getUserGroupData($v['uid']);
                if ( !$groupData ){
                    $groupData = 1;
                }
                static_cache( 'groupdata'.$v['uid'] , $groupData );
            }
            $v['user_info']['groupData'] = $groupData;   //獲取使用者組資訊
            $v['content'] = parse_html($v['content'].$v['replyInfo']);
            $v['sourceInfo'] = model('Source')->getSourceInfo($v['table'], $v['row_id'], false, $v['app']);
            //$v['data'] = unserialize($v['data']);
        }
        return $data;
    }

    /**
     * 獲取評論資訊
     * @param integer $id 評論ID
     * @param boolean $source 是否顯示資源資訊，默認為true
     * @return array 獲取評論資訊
     */
    public function getCommentInfo($id, $source = true) {
        if(empty($id)) {
            $this->error = L('PUBLIC_WRONG_DATA');        // 錯誤的參數
            return false;
        }
        if($info = static_cache('comment_info_'.$id)) {
            return $info;
        }
        $map['comment_id'] = $id;
        $info = $this->where($map)->find();
        $info['user_info'] = model('User')->getUserInfo($info['uid']);
        //$info['content'] = parse_html($info['content']);
        $info['content'] = $info['content'];  // 2012/12/7修改
        $source && $info['sourceInfo'] = model('Source')->getSourceInfo($info['table'],$info['row_id'],false,$info['app']);

        static_cache('comment_info_'.$id,$info);

        return $info;
    }

    /**
     * 添加評論操作
     * @param array $data 評論資料
     * @param boolean $forApi 是否用於API，默認為false
     * @param boolean $notCount 是否統計到未讀評論
     * @param array $lessUids 除去@使用者ID
     * @return boolean 是否添加評論成功
     */
    public function addComment($data, $forApi = false, $notCount = false, $lessUids = null) {
        // 判斷使用者是否登入
        if(!$GLOBALS['ts']['mid']){
            $this->error = L('PUBLIC_REGISTER_REQUIRED');         // 請先登入
            return false;
        }
        // 設定評論絕對樓層
        //$data['data']['storey'] = $this->getStorey($data['row_id'], $data['app'], $data['table']);
        // 檢測資料安全性
        $add = $this->_escapeData($data);
        if($add['content'] === '') {
            $this->error = L('PUBLIC_COMMENT_CONTENT_REQUIRED');        // 評論內容不可為空
            return false;
        }
        $add['is_del'] = 0;

        //判斷是否先審後發
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $weibo_premission = $weiboSet['weibo_premission'];
        if(in_array('audit',$weibo_premission) || CheckPermission('core_normal','feed_audit')){
            $add['is_audit'] = 0;
        }else{
            $add['is_audit'] = 1;
        }
        if($res = $this->add($add)) {
            //添加樓層資訊
            $storeyCount = $this->where('row_id='.$data['row_id'].' and comment_id<'.$res)->count();
            $this->where('comment_id='.$res)->setField('storey',$storeyCount+1);
            if(!$add['is_audit']){
                $touid = D('user_group_link')->where('user_group_id=1')->field('uid')->findAll();
                foreach($touid as $k=>$v){
                    model('Notify')->sendNotify($v['uid'], 'comment_audit');
                }
            }
            // 獲取排除@使用者ID
            $lessUids[] = intval($data['app_uid']);
            !empty($data['to_uid']) && $lessUids[] = intval($data['to_uid']);
            // 獲取使用者發送的內容，僅僅以//進行分割
            $scream = explode('//', $data['content']);
            model('Atme')->setAppName('Public')->setAppTable('comment')->addAtme(trim($scream[0]), $res, null, $lessUids);
            // 被評論內容的“評論統計數”加1，同時可檢測出app，table，row_id的有效性
            $pk = D($add['table'])->getPk();
            D($add['table'])->setInc('comment_count', "`{$pk}`={$add['row_id']}", 1);
            //相容舊版本app
            D($add['table'])->setInc('commentCount', "`{$pk}`={$add['row_id']}", 1);
            D($add['table'])->setInc('comment_all_count', "`{$pk}`={$add['row_id']}", 1);
            // 給應用UID添加一個未讀的評論數 原作者
            if($GLOBALS['ts']['mid'] != $add['app_uid'] && $add['app_uid'] != '') {
                !$notCount && model('UserData')->updateKey('unread_comment', 1, true, $add['app_uid']);
            }
            // 回覆發送提示資訊
            if(!empty($add['to_uid']) && $add['to_uid'] != $GLOBALS['ts']['mid']) {
                !$notCount && model('UserData')->updateKey('unread_comment', 1, true, $add['to_uid']);
            }
            // 加積分操作
            if($add['table'] =='feed'){
                model('Credit')->setUserCredit($GLOBALS['ts']['mid'], 'comment_weibo');
                model('Credit')->setUserCredit($data['app_uid'], 'commented_weibo');
                model('Feed')->cleanCache($add['row_id']);
            }
            // 發郵件
            if($add['to_uid'] != $GLOBALS['ts']['mid'] || $add['app_uid'] != $GLOBALS['ts']['mid'] && $add['app_uid'] != '') {
                $author = model('User')->getUserInfo($GLOBALS['ts']['mid']);
                $config['name'] = $author['uname'];
                $config['space_url'] = $author['space_url'];
                $config['face'] = $author['avatar_small'];
                $sourceInfo = model('Source')->getSourceInfo($add['table'], $add['row_id'], $forApi, $add['app']);
                $config['content'] = parse_html($add['content']);
                $config['ctime'] = date('Y-m-d H:i:s',time());
                $config['sourceurl'] = $sourceInfo['source_url'];
                $config['source_content'] = parse_html($sourceInfo['source_content']);
                $config['source_ctime'] = date('Y-m-d H:i:s',$sourceInfo['ctime']);
                if(!empty($add['to_uid'])) {
                    // 回覆
                    $config['comment_type'] = '回覆 我 的評論:';
                    model('Notify')->sendNotify($add['to_uid'], 'comment', $config);

                } else {
                    // 評論
                    $config['comment_type'] = '評論 我 的微博:';
                    if(!empty($add['app_uid'])) {
                        model('Notify')->sendNotify($add['app_uid'], 'comment', $config);
                    }
                }
            }
        }

        $this->error = $res ? L('PUBLIC_CONCENT_IS_OK') : L('PUBLIC_CONCENT_IS_ERROR');         // 評論成功，評論失敗

        return $res;
    }

    /**
     * 將指定使用者的評論，全部設定為已讀
     * @param integer $uid 使用者UID
     */
    public function setUnreadCountToZero($uid) {
        // TODO:更新全局統計表
    }

    /**
     * 獲取指定使用者的評論，未讀評論數
     * @param integer $uid 使用者UID
     */
    public function getUnreadCount($uid) {
        // TODO:查詢全局統計表
    }

    /**
     * 刪除評論
     * @param array $app_name 評論所屬應用   積分加減時用到
     * @param array $ids 評論ID陣列
     * @param integer $uid 使用者UID
     * @return boolean 是否刪除評論成功
     */
    public function deleteComment($ids, $uid = null, $app_name = 'public') {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        $map = array();
        $map['comment_id'] = array('IN', $ids);
        $comments = $this->field('comment_id, app,`table`, row_id, app_uid, uid')->where($map)->findAll();
        if(empty($comments)) {
            return false;
        }
        // 刪除@資訊
        foreach($comments as $value) {
            model('Atme')->setAppName('Public')->setAppTable('comment')->deleteAtme(null, $value['comment_id'], null);
        }

        // 應用回撥，減少應用的評論計數
        // 已優化: 先統計出哪篇應用需要減幾, 然後再減. 這樣可以有效減少資料庫操作次數
        $_comments = array();
        // 統計各table、row_id對應的評論
        foreach($comments as $c_k => $c_v) {
            // 如果此條評論不屬於指定使用者[釋出者或被評論的內容的作者]，則不可操作  《=有管理許可權的也可以做
/*              if (isset($uid) && !in_array($uid, array($c_v['app_uid'], $c_v['uid']))) {
                unset($comments[$c_k]);
                    continue;
}*/
            $_comments[$c_v['table']][$c_v['row_id']][] = $c_v['comment_id'];

            //同步刪除微吧評論
            if($c_v['app'] == 'weiba'){
                $post_id = D('weiba_reply')->where('comment_id='.$c_v['comment_id'])->getField('post_id');
                D('weiba_post')->where('post_id='.$post_id)->setDec('reply_count');
                D('weiba_reply')->where('comment_id='.$c_v['comment_id'])->delete();
            }
        }
        // 刪除評論：先刪除評論，在處理統計
        $map = array();
        $map['comment_id'] = array('IN', getSubByKey($comments, 'comment_id'));
        $data = array('is_del' => 1);
        $res = $this->where($map)->save($data);

        if($res) {
            // 更新統計數目
            foreach($_comments as $_c_k => $_c_v) {
                foreach($_c_v as $_c_v_k => $_c_v_v) {
                    // 應用表格“評論統計”統一使用comment_count欄位名
                    $field = D($_c_k)->getPK();
                    if(empty($field)){
                        $field = $_c_k.'_id';
                    }
                    D($_c_k)->setDec('comment_count', "`{$field}`={$_c_v_k}", count($_c_v_v));
                    //相容舊app評論
                    D($_c_k)->setDec('commentCount', "`{$field}`={$_c_v_k}", count($_c_v_v));
                    //dump(D($_c_k)->getLastSql());
                    if ($app_name == 'feed') {
                        model($_c_k)->cleanCache($_c_v_k);
                    }
                }
            }

            //添加積分
            if($app_name == 'weiba'){
                model('Credit')->setUserCredit($uid,'delete_topic_comment');
            }
            if($app_name == 'public'){
                model('Credit')->setUserCredit($uid,'delete_weibo_comment');
            }
        }

        $this->error = $res != false ? L('PUBLIC_ADMIN_OPRETING_SUCCESS') : L('PUBLIC_ADMIN_OPRETING_ERROR');       // 操作成功，操作失敗

        return $res;
    }

    /**
     * 評論處理方法，包含徹底刪除、假刪除與恢復功能
     * @param integer $id 評論ID
     * @param string $type 操作類型，delComment假刪除、deleteComment徹底刪除、commentRecover恢復
     * @param string $title 提示語言所附加的內容
     * @return array 評論處理後，返回的陣列操作資訊
     */
    public function doEditComment($id, $type, $title) {
        $return = array('status'=>'0', 'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));           // 操作成功
        if(empty($id)) {
            $return['data'] = L('PUBLIC_WRONG_DATA');            // 錯誤的參數
        } else {
            $map['comment_id'] = is_array($id) ? array('IN',$id) : intval($id);
            $save['is_del'] = $type =='delComment' ? 1 : 0;
            if($type == 'deleteComment') {
                $res = $this->where($map)->delete();
            } else {
                if($type == 'commentRecover') {
                    $res = $this->commentRecover($id);
                } else {
                    $res = $this->deleteComment($id);
                }
            }
            if($res != false) {
                empty($title) && $title = L('PUBLIC_CONCENT_IS_OK');
                $return = array('status'=>1, 'data'=>$title);          // 評論成功
            }
        }

        return $return;
    }

    /**
     * 評論恢復操作
     * @param integer $id 評論ID
     * @return boolean 評論是否恢覆成功
     */
    public function commentRecover($id) {
        if(empty($id)) {
            return false;
        }
        $map['comment_id'] = $id;
        $comment = $this->field('comment_id, app,`table`, row_id, app_uid, uid')->where($map)->find();
        $save['is_del'] = 0;
        if($this->where($map)->save($save)) {
            D($comment['table'])->setInc('comment_count', "`".$comment['table']."_id`=".$comment['row_id']);
            // 刪除微博快取
            switch($comment['table']) {
            case 'feed':
                $feedIds = $this->where($map)->getAsFieldArray('row_id');
                model('Feed')->cleanCache($feedIds);
                break;
            }
            return true;
        }

        return false;
    }

    /**
     * 稽覈通過評論
     * @param integer $comment_id 評論ID
     * @return array 評論操作後的結果資訊陣列
     */
    public function doAuditComment($comment_id){
        $return = array('status'=>'0');
        if(empty($comment_id)) {
            $return['data'] = '請選擇評論！';
        } else {
            $map['comment_id'] = is_array($comment_id) ? array('IN', $comment_id) : intval($comment_id);
            $save['is_audit'] = 1;
            $res = $this->where($map)->save($save);
            if($res) {
                $return = array('status'=>1);
            }
        }
        return $return;
    }

    /**
     * 檢測資料安全性
     * @param array $data 待檢測的資料
     * @return array 驗證後的資料
     */
    private function _escapeData($data) {
        $add['app'] = !$data['app'] ? $this->_app : $data['app'];
        $add['table'] = !$data['table'] ? $this->_app_table : $data['table'];
        $add['row_id'] = intval($data['row_id']);
        $add['app_uid'] = intval($data['app_uid']);
        $add['uid'] = $GLOBALS['ts']['mid'];
        $add['content'] = preg_html($data['content']);
        $add['to_comment_id'] = intval($data['to_comment_id']);
        $add['to_uid'] = intval($data['to_uid']);
        $add['data'] = serialize($data['data']);
        $add['ctime'] = $_SERVER['REQUEST_TIME'];
        $add['client_type'] = isset($data['client_type']) ? intval($data['client_type']) : getVisitorClient();

        return $add;
}

/*** API使用 ***/
/**
 * 獲取評論列表，API使用
 * @param string $where 查詢條件
 * @param integer $since_id 主鍵起始ID，默認為0
 * @param integer $max_id 主鍵最大ID，默認為0
 * @param integer $limit 每頁結果集數目，默認為20
 * @param integer $page 頁數，默認為1
 * @param boolean $source 是否獲取資源資訊，默認為false
 * @return array 評論列表資料
 */
public function getCommentListForApi($where='', $since_id = 0, $max_id = 0, $limit = 20 , $page = 1 ,$source = false) {
    $since_id = intval($since_id);
    $max_id = intval($max_id);
    $limit = intval($limit);
    $page = intval($page);
    $where = empty($where) ?  " is_del = 0 " : $where.' AND is_del=0';
    if(!empty($since_id) || !empty($max_id)) {
        !empty($since_id) && $where .= " AND comment_id > {$since_id}";
        !empty($max_id) && $where .= " AND comment_id < {$max_id}";
}
$start = ($page - 1) * $limit;
$end = $limit;
$data = $this->where($where)->order('comment_id DESC')->limit("$start, $end")->findAll();
foreach($data as &$v) {
    $v['user_info'] = model('User')->getUserInfo($v['uid']);
    $v['content'] = parseForApi($v['content']);
    $v['ctime'] = date('Y-m-d H:i', $v['ctime']);
    $source && $v['sourceInfo'] = model('Source')->getSourceInfo($v['table'], $v['row_id'], true, $v['app']);
}

return $data;
}

/**
 * 設定資源評論的絕對樓層
 * @param integer $rowId 資源ID
 * @param string $app 應用名稱
 * @param string $table 資源表名稱
 * @param boolean $inc 是否自增，默認為true
 * @return integer 樓層ID
 */
public function getStorey($rowId, $app, $table, $inc = true)
{
    $map[$table.'_id'] = $rowId;
    $data = model(ucfirst($table))->where($map)->getField('comment_all_count');
    $inc && $data++;
    return $data;
}
}
