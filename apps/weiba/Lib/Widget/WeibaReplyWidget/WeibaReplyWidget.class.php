<?php
/**
 * 評論釋出/顯示框
 * @example W('Comment',array('tpl'=>'detail','row_id'=>72,'order'=>'DESC','app_uid'=>'14983','cancomment'=>1,'cancomment_old'=>0,'showlist'=>1,'canrepost'=>1))
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class WeibaReplyWidget extends Widget{

    private static $rand = 1;

    /**
     * @param string tpl 顯示模版 默認為comment，一般使用detail表示詳細資源頁面的評論
     * @param integer weiba_id 微吧ID
     * @param integer post_id 帖子ID
     * @param integer post_uid 帖子釋出者
     * @param integer feed_id 對應的微博ID
     * @param integer limit 每頁顯示條數
     * @param string order 回覆排列順序，默認ASC
     * @param boolean addtoend 新回覆是否添加到尾部 0：否，1：是
     */
    public function render($data){
        $var = array();
        //默認配置資料
        $var['cancomment']  = 1;  //是否可以評論
        // $var['canrepost']   = 1;  //是否允許轉發
        // $var['cancomment_old'] = 1; //是否可以評論給原作者
        $var['showlist'] = 1;         // 默認顯示原評論列表
        $var['tpl']      = 'detail'; // 顯示模板
        $var['app_name'] = 'weiba';
        $var['table']    = 'weiba_post';
        $var['limit']    = 10;
        $var['order']    = 'ASC';
        $var['initNums'] = model('Xdata')->getConfig('weibo_nums','feed');
        $map['weiba_id'] = $data['weiba_id'];
        $map['level'] = array('gt',1);
        $var['weiba_admin'] = getSubByKey(D('weiba_follow')->where($map)->findAll(),'follower_uid');
        $_REQUEST['p'] = $_GET['p'] ? $_GET['p'] : $_POST['p'];
        empty($data) && $data = $_POST;
        is_array($data) && $var = array_merge($var,$data);

        if($var['showlist'] ==1){ //默認只取出前10條
            $map = array();
            $map['post_id']  = intval($var['post_id']);   //必須存在
            if(!empty($map['post_id'])){
                //分頁形式資料
                $var['list'] = D('WeibaReply','weiba')->getReplyList($map,'ctime '.$var['order'],$var['limit']);
                //dump($var['list']);exit;
            }
        }//渲染模版
        $content = $this->renderFile(dirname(__FILE__)."/".$var['tpl'].'.html',$var);
        self::$rand ++;
        $ajax = $var['isAjax'];
        unset($var,$data);
        //輸出資料
        $return = array('status'=>1,'data'=>$content);

        return $ajax==1 ? json_encode($return) : $return['data'];
    }

    /**
     * 添加帖子回覆的操作
     * @return array 評論添加狀態和提示資訊
     */
    public function addReply(){
        //   echo $_POST['post_id'];exit;
        if( !$this->mid || !CheckPermission('weiba_normal','weiba_reply')){
            return;
        }
        $return = array('status'=>0,'data'=>L('PUBLIC_CONCENT_IS_ERROR'));
        $data['weiba_id'] = intval($_POST['weiba_id']);
        $data['post_id'] = intval($_POST['post_id']);
        $data['post_uid'] = intval($_POST['post_uid']);
        $data['to_reply_id'] = intval($_POST['to_reply_id']);
        $data['to_uid'] = intval($_POST['to_uid']);
        $data['uid'] = $this->mid;
        $data['ctime'] = time();
        $data['content'] = preg_html(h($_POST['content']));
        if($data['reply_id'] = D('weiba_reply')->add($data)){

            //添加積分
            model('Credit')->setUserCredit(intval($_POST['post_uid']),'comment_topic');
            model('Credit')->setUserCredit($data['to_uid'],'commented_topic');

            $map['last_reply_uid'] = $this->mid;
            $map['last_reply_time'] = $data['ctime'];
            D('weiba_post')->where('post_id='.$data['post_id'])->save($map);
            D('weiba_post')->where('post_id='.$data['post_id'])->setInc('reply_count'); //回覆統計數加1
            D('weiba_post')->where('post_id='.$data['post_id'])->setInc('reply_all_count'); //總回覆統計數加1
            //同步到微博評論
            //$feed_id = intval($_POST['feed_id']);
            $datas['app'] = 'weiba';
            $datas['table'] = 'feed';
            $datas['content'] = preg_html($data['content']);
            $datas['app_uid'] = intval($_POST['post_uid']);
            $datas['row_id'] = intval($_POST['feed_id']);
            $datas['to_comment_id'] = $data['to_reply_id']?D('weiba_reply')->where('reply_id='.$data['to_reply_id'])->getField('comment_id'):0;
            $datas['to_uid'] = intval($_POST['to_uid']);
            $datas['uid'] = $this->mid;
            $datas['ctime'] = time();
            $datas['client_type'] = getVisitorClient();
            if($comment_id = model('Comment')->addComment($datas)){
                $data1['comment_id'] = $comment_id;
                $data1['storey'] = model('Comment')->where('comment_id='.$comment_id)->getField('storey');
                D('weiba_reply')->where('reply_id='.$data['reply_id'])->save($data1);
                // 給應用UID添加一個未讀的評論數
                if($GLOBALS['ts']['mid'] != $datas['app_uid'] && $datas['app_uid'] != '') {
                    !$notCount && model('UserData')->updateKey('unread_comment', 1, true, $datas['app_uid']);
                }
                model('Feed')->cleanCache($datas['row_id']);
            }
            //轉發到我的微博
            if($_POST['ifShareFeed'] == 1) {
                $commentInfo  = model('Source')->getSourceInfo($datas['table'], $datas['row_id'], false, $datas['app']);
                $oldInfo = isset($commentInfo['sourceInfo']) ? $commentInfo['sourceInfo'] : $commentInfo;
                // 根據評論的物件獲取原來的內容
                $s['sid'] = $data['post_id'];
                $s['app_name'] = 'weiba';
                if($commentInfo['feedtype'] == 'post' &&$commentInfo['feedtype'] == 'weiba_post') {   //加入微吧類型，2012/11/15
                    if(empty($data['to_comment_id'])) {
                        $s['body'] = $data['content'];
                    } else {
                        $replyInfo = model('Comment')->setAppName($data['app'])->setAppTable($data['table'])->getCommentInfo(intval($data['to_comment_id']), false);
                        //$replyScream = '//@'.$replyInfo['user_info']['uname'].' ：';
                        $s['body'] = $data['content'].$replyScream.$replyInfo['content'];
                    }
                } else {
                    //$scream = '//@'.$commentInfo['source_user_info']['uname'].'：'.$commentInfo['source_content'];
                    if(empty($data['to_comment_id'])) {
                        $s['body'] = $data['content'].$scream;
                    } else {
                        $replyInfo = model('Comment')->setAppName($data['app'])->setAppTable($data['table'])->getCommentInfo(intval($data['to_comment_id']), false);
                        //$replyScream = '//@'.$replyInfo['user_info']['uname'].' ：';
                        $s['body'] = $data['content'].$replyScream.$replyInfo['content'].$scream;
                    }
                }
                $s['type']      = 'weiba_post';
                $s['comment']   = $data['comment_old'];
                // 去掉回複使用者@
                $lessUids = array();
                if(!empty($data['to_uid'])) {
                    $lessUids[] = $data['to_uid'];
                }
                // 如果為原創微博，不給原創使用者發送@資訊
                if($oldInfo['feedtype'] == 'post' && empty($data['to_uid'])) {
                    $lessUids[] = $oldInfo['uid'];
                }
                model('Share')->shareFeed($s,'comment', $lessUids);
            }
            $data['feed_id'] = $datas['row_id'];
            $data['comment_id'] = $comment_id;
            $data['storey'] = $data1['storey'];
            $return['status'] = 1 ;
            $return['data'] = $this->parseReply($data);
        }
        echo json_encode($return);exit();
    }

    /**
     * 刪除回覆(在微博評論刪除中同步刪除微吧回覆)
     * @return bool true or false
     */
    public function delReply(){
        if ( !CheckPermission('core_admin','comment_del') ){
            if ( !CheckPermission('weiba_normal','weiba_del_reply') ){
                return false;
    }
        }
            $reply_id = $_POST['reply_id'];
            $app_name = $_POST['widget_appname'];
            if(!empty($reply_id)){
                $comment_id = D('weiba_reply')->where('reply_id='.$reply_id)->getField('comment_id');
                model('Comment')->deleteComment($comment_id,'',$app_name);
                model('Credit')->setUserCredit($this->mid,'delete_topic_comment');
                return 1;
            }
            return false;
            }

            /**
             * 渲染評論頁面 在addcomment方法中呼叫
             */
            public function parseReply($data){
                $data['userInfo'] = model('User')->getUserInfo($GLOBALS['ts']['uid']);
                $data['userInfo']['groupData'] = model('UserGroupLink')->getUserGroupData($GLOBALS['ts']['uid']);   //獲取使用者組資訊
                $data['content'] = preg_html($data['content']);
                $data['content'] = parse_html($data['content']);
                return $this->renderFile(dirname(__FILE__)."/_parseComment.html",$data);
            }

            /**
             * 評論帖子回覆
             */
            public function reply_reply(){
                if ( !CheckPermission('weiba_normal','weiba_reply') ){
                    return false;
            }
            $var = $_GET;

            $var['initNums'] = model('Xdata')->getConfig('weibo_nums', 'feed');
            $var['commentInfo'] = model('Comment')->getCommentInfo($var['comment_id'], false);
            $var['canrepost']  = $var['commentInfo']['table'] == 'feed'  ? 1 : 0;
            $var['cancomment'] = 1;

            // 獲取原作者資訊
            $rowData = model('Feed')->get(intval($var['commentInfo']['row_id']));
            $appRowData = model('Feed')->get($rowData['app_row_id']);
            $var['user_info'] = $appRowData['user_info'];
            // 微博類型
            $var['feedtype'] = $rowData['type'];
            // $var['cancomment_old'] = ($var['commentInfo']['uid'] != $var['commentInfo']['app_uid'] && $var['commentInfo']['app_uid'] != $this->uid) ? 1 : 0;
            $var['initHtml'] = L('PUBLIC_STREAM_REPLY').'@'.$var['commentInfo']['user_info']['uname'].' ：';   // 回覆
            //dump($var);exit;
            return $this->renderFile(dirname(__FILE__)."/reply_reply.html",$var);
            }

            }
