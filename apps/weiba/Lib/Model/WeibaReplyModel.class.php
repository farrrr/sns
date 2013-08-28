<?php
/**
 * 微吧模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class WeibaReplyModel extends Model {

    protected $tableName = 'weiba_reply';
    protected $error = '';
    protected $fields = array(
        0 =>'reply_id',1=>'weiba_id',2=>'post_id',3=>'post_uid',4=>'uid',5=>'ctime',
        6=>'content',7=>'is_del',8=>'comment_id',9=>'storey','_autoinc'=>true,'_pk'=>'post_id'
    );

    /**
     * 獲取回覆列表
     * @param array $map 查詢條件
     * @param string $order 排序條件，默認為comment_id ASC
     * @param integer $limit 結果集數目，默認為10
     * @return array 評論列表資訊
     */
    public function getReplyList($map = null, $order = 'reply_id desc', $limit = 10) {
        !isset($map['is_del']) && ($map['is_del'] = 0);
        $data = $this->where($map)->order($order)->findPage($limit);
        // // TODO:後續優化
        foreach($data['data'] as &$v) {
            $v['user_info'] = model('User')->getUserInfo($v['uid']);
            $v['user_info']['groupData'] = model('UserGroupLink')->getUserGroupData($v['uid']);   //獲取使用者組資訊
            $v['content'] = parse_html(h(htmlspecialchars($v['content'])));
            //$v['sourceInfo'] = model('Source')->getSourceInfo($v['table'], $v['row_id'], false, $v['app']);
        }
        return $data;
    }

    /**
     * 獲取回覆列表forapi
     * @param array $map 查詢條件
     * @param string $order 排序條件，默認為comment_id ASC
     * @param integer $limit 結果集數目，默認為10
     * @return array 評論列表資訊
     */
    public function getReplyListForApi($map=null, $order='reply_id desc', $limit=20, $page=1) {
        !isset($map['is_del']) && ($map['is_del'] = 0);
        $limit = intval($limit);
        $page = intval($page);
        $start = ($page - 1) * $limit;
        $end = $limit;
        $data = $this->where($map)->limit("{$start},{$end}")->order($order)->findAll();
        // TODO:後續優化
        foreach($data as $k=>$v) {
            $data[$k]['author_info'] = model('User')->getUserInfo($v['uid']);
        }
        return $data;
    }

    /**
     * 添加帖子評論forApi
     * @param integer post_id 帖子ID
     * @param integer content 帖子內容
     * @param integer uid 評論者UID
     * @return boolean 是否評論成功
     */
    public function addReplyForApi($post_id, $content, $uid){
        $post_detail = D('weiba_post')->where('post_id='.$post_id)->find();
        $data['weiba_id'] = intval($post_detail['weiba_id']);
        $data['post_id'] = $post_id;
        $data['post_uid'] = intval($post_detail['post_uid']);
        $data['uid'] = $uid;
        $data['ctime'] = time();
        $data['content'] = preg_html(h($content));
        if($data['reply_id'] = D('weiba_reply')->add($data)){
            $map['last_reply_uid'] = $data['uid'];
            $map['last_reply_time'] = $data['ctime'];
            D('weiba_post')->where('post_id='.$data['post_id'])->save($map);
            D('weiba_post')->where('post_id='.$data['post_id'])->setInc('reply_count'); //回覆統計數加1
            //同步到微博評論
            //$feed_id = intval($_POST['feed_id']);
            $datas['app'] = 'weiba';
            $datas['table'] = 'feed';
            $datas['row_id'] = intval($post_detail['feed_id']);
            $datas['app_uid'] = intval($post_detail['post_uid']);
            //$datas['to_comment_id'] = $data['to_reply_id']?D('weiba_reply')->where('reply_id='.$data['to_reply_id'])->getField('comment_id'):0;
            //$datas['to_uid'] = intval($_POST['to_uid']);
            $datas['uid'] = $data['uid'];
            $datas['content'] = preg_html($data['content']);
            $datas['ctime'] = $data['ctime'];
            $datas['client_type'] = getVisitorClient();
            // 設定評論絕對樓層
            // $data['data']['storey'] = model('Comment')->getStorey($datas['row_id'], $datas['app'], $datas['table']);
            // $datas['data'] = serialize($data['data']);
            if($comment_id = model('Comment')->addComment($datas)){
                $data1['comment_id'] = $comment_id;
                $data1['storey'] = model('Comment')->where('comment_id='.$comment_id)->getField('storey');
                D('weiba_reply')->where('reply_id='.$data['reply_id'])->save($data1);
                // 被評論內容的“評論統計數”加1，同時可檢測出app，table，row_id的有效性
                D('feed')->where('feed_id='.$datas['row_id'])->setInc('comment_count');
                // 給應用UID添加一個未讀的評論數
                if($GLOBALS['ts']['mid'] != $datas['app_uid'] && $datas['app_uid'] != '') {
                    !$notCount && model('UserData')->updateKey('unread_comment', 1, true, $datas['app_uid']);
                }
                model('Feed')->cleanCache($datas['row_id']);
            }
            return true;
        }else{
            return false;
        }

    }

    /**
     * 添加評論回覆forApi
     * @param integer reply_id 評論ID
     * @param integer content 回覆內容
     * @param integer uid 回覆者UID
     * @return boolean 是否回覆成功
     */
    public function addReplyToCommentForApi($reply_id, $content, $uid){
        $reply_detail = $this->where('reply_id='.$reply_id)->find();
        $data['weiba_id'] = intval($reply_detail['weiba_id']);
        $data['post_id'] = intval($reply_detail['post_id']);
        $data['post_uid'] = intval($reply_detail['post_uid']);
        $data['to_reply_id'] = $reply_id;
        $data['to_uid'] = intval($reply_detail['uid']);
        $data['uid'] = $uid;
        $data['ctime'] = time();
        $data['content'] = preg_html(h($content));
        if($data['reply_id'] = D('weiba_reply')->add($data)){
            $map['last_reply_uid'] = $data['uid'];
            $map['last_reply_time'] = $data['ctime'];
            D('weiba_post')->where('post_id='.$data['post_id'])->save($map);
            D('weiba_post')->where('post_id='.$data['post_id'])->setInc('reply_count'); //回覆統計數加1
            //同步到微博評論
            //$feed_id = intval($_POST['feed_id']);
            $datas['app'] = 'weiba';
            $datas['table'] = 'feed';
            $datas['row_id'] = D('weiba_post')->where('post_id='.$data['post_id'])->getField('feed_id');
            $datas['app_uid'] = intval($data['post_uid']);
            $datas['to_comment_id'] = intval($reply_detail['comment_id']);
            $datas['to_uid'] = $data['to_uid'];
            $datas['uid'] = $data['uid'];
            $datas['content'] = preg_html($data['content']);
            $datas['ctime'] = $data['ctime'];
            $datas['client_type'] = getVisitorClient();
            if($comment_id = D('comment')->add($datas)){
                D('weiba_reply')->where('reply_id='.$data['reply_id'])->setField('comment_id',$comment_id);
                // 被評論內容的“評論統計數”加1，同時可檢測出app，table，row_id的有效性
                D('feed')->where('feed_id='.$datas['row_id'])->setInc('comment_count');
                // 給應用UID添加一個未讀的評論數
                if($GLOBALS['ts']['mid'] != $datas['app_uid'] && $datas['app_uid'] != '') {
                    !$notCount && model('UserData')->updateKey('unread_comment', 1, true, $datas['app_uid']);
    }
    model('Feed')->cleanCache($datas['row_id']);
    }
    return true;
    }else{
        return false;
    }
    }

    /**
     * 刪除評論forapi
     * @param reply_id 評論ID
     * @return boolean 是否回覆成功
     */
    public function delReplyForApi($reply_id){
        $comment_id = $this->where('reply_id='.$reply_id)->getField('comment_id');
        //echo $comment_id;exit;
        D('comment')->where('comment_id='.$comment_id)->delete();
        return $this->where('reply_id='.$reply_id)->delete();
    }

    }
