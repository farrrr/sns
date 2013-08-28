<?php
/**
 * 分享模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class ShareModel {

    /**
     * 分享到微博
     * @example
     * 需要傳入的$data值
     * sid：轉發的微博/資源ID
     * app_name：app名稱
     * content：轉發時的內容資訊，有時候會有某些標題的資源
     * body：轉發時，自定義寫入的內容
     * type：微博類型
     * comment：是否給原作者評論
     * @param array $data 分享的相關資料
     * @param string $from 是否發@給資源作者，默認為share
     * @param array $lessUids 去掉@使用者，默認為null
     * @return array 分享操作後，相關反饋資訊資料
     */
    public function shareFeed($data, $from = 'share', $lessUids = null)
    {
        // 返回的資料結果集
        $return = array('status'=>0,'data'=>L('PUBLIC_SHARE_FAILED'));          // 分享失敗
        // 驗證資料正確性
        if(empty($data['sid'])) {
            return $return;
        }
        // type是資源所在的表名
        $type = t($data['type']);
        // 當前產生微博所屬的應用
        $app = isset($data['app_name']) ? $data['app_name'] : APP_NAME;
        // 是否為介面形式
        $forApi = $data['forApi'] ? true : false;
        if(!$oldInfo = model('Source')->getSourceInfo($type, $data['sid'], $forApi, $data['app_name'])) {
            $return['data'] = L('PUBLIC_INFO_SHARE_FORBIDDEN');         // 此資訊不可以被分享
            return $return;
        }
        // 內容資料
        $d['content'] = isset($data['content']) ? str_replace(SITE_URL, '[SITE_URL]', $data['content']) : '';
        $d['body'] = str_replace(SITE_URL, '[SITE_URL]', $data['body']);

        $feedType = 'repost';       // 默認為普通的轉發格式
        if(!empty($oldInfo['feedtype']) && !in_array($oldInfo['feedtype'], array('post','postimage','postfile'))) {
            $feedType = $oldInfo['feedtype'];
        }

        if($app == 'weiba'){
            $feedType = 'weiba_repost';
        }

        if($app == 'tipoff'){
            $feedType = 'tipoff_repost';
        }

        $d['sourceInfo'] = !empty($oldInfo['sourceInfo']) ? $oldInfo['sourceInfo'] : $oldInfo;
        // 是否發送@上級節點
        $isOther = ($from == 'comment') ? false : true;
        // 獲取上個節點資源ID
        $d['curid'] = $data['curid'];
        // 獲取轉發原微博資訊
        if($oldInfo['app_row_id'] == 0) {
            $appId = $oldInfo['source_id'];
            $appTable = $oldInfo['source_table'];
        } else {
            $appId = $oldInfo['app_row_id'];
            $appTable = $oldInfo['app_row_table'];
        }

        $d['from'] = isset($data['from']) ? intval($data['from']) : 0;

        if($res = model('Feed')->put($GLOBALS['ts']['mid'], $app, $feedType, $d, $appId, $appTable, null, $lessUids, $isOther,1)) {
            if($data['comment'] != 0 && $oldInfo['uid'] != $data['comment_touid']) {
                // 發表評論
                $c['app'] = $app;
                $c['table'] = $appTable;
                $c['app_uid'] = $oldInfo['uid'];
                $c['content'] = !empty($d['body']) ? $d['body'] : $d['content'];
                $c['row_id'] = !empty($oldInfo['sourceInfo']) ? $oldInfo['sourceInfo']['source_id'] : $appId;
                $c['client_type'] = getVisitorClient();
                $notCount = $from == "share" ? true : false;
                $comment_id = model('Comment')->addComment($c, false, $notCount, $lessUids);
                // 同步到微吧
                if($app == 'weiba'){
                    $postDetail = D('weiba_post')->where('feed_id='.$c['row_id'])->find();
                    if($postDetail) {
                        $datas['weiba_id'] = $postDetail['weiba_id'];
                        $datas['post_id'] = $postDetail['post_id'];
                        $datas['post_uid'] = $postDetail['post_uid'];
                        // $datas['to_reply_id'] = $data['to_comment_id']?D('weiba_reply')->where('comment_id='.$data['to_comment_id'])->getField('reply_id'):0;
                        // $datas['to_uid'] = $data['to_uid'];
                        $datas['uid'] = $GLOBALS['ts']['mid'];
                        $datas['ctime'] = time();
                        $datas['content'] = $c['content'];
                        $datas['comment_id'] = $comment_id;
                        if(D('weiba_reply')->add($datas)) {
                            $map['last_reply_uid'] = $this->mid;
                            $map['last_reply_time'] = $datas['ctime'];
                            D('weiba_post')->where('post_id='.$datas['post_id'])->save($map);
                            // 回覆統計數加1
                            D('weiba_post')->where('post_id='.$datas['post_id'])->setInc('reply_count');
                        }
                    }
                }
            }
            //添加話題
            model('FeedTopic')->addTopic(html_entity_decode($d['body'], ENT_QUOTES), $res['feed_id'], $feedType);
            // 渲染資料
            $rdata = $res;          // 渲染完後的結果
            $rdata['feed_id'] = $res['feed_id'];
            $rdata['app_row_id'] = $data['sid'];
            $rdata['app_row_table'] = $data['type'];
            $rdata['app'] = $app;
            $rdata['is_repost'] = 1;
            switch ( $app ){
            case 'weiba':
                $rdata['from'] = getFromClient(0 , $app , '微吧');
                break;
            default:
                $rdata['from'] = getFromClient( $from , $app);
                break;
            }
            $return['data'] = $rdata;
            $return['status'] = 1;
            // 被分享內容“分享統計”數+1，同時可檢測出app,table,row_id 的有效性
            if(!$pk = D($data['type'], $data['app_name'])->getPk()) {
                $pk = $data['type'].'_id';
            }
            D($data['type'], $data['app_name'])->setInc('repost_count', "`{$pk}`={$data['sid']}", 1);
            if($data['curid'] != $data['sid'] && !empty($data['curid'])) {
                if(!$pk = D($data['curtable'])->getPk()) {
                    $pk = $data['curtable'].'_id';
                }
                D($data['curtable'])->setInc('repost_count', "`{$pk}`={$data['curid']}", 1);
                D($data['curtable'])->cleanCache($data['curid']);
            }
            D($data['type'],$data['app_name'])->cleanCache($data['sid']);
        } else {
            $return['data']=model('Feed')->getError();
        }

        return $return;
    }

    /**
     * 分享給同事
     * @example
     * 需要傳入的$data值
     * uid：同事使用者ID
     * sid：轉發的微博/資源ID
     * app_name：app名稱
     * content：轉發時的內容資訊，有時候會有某些標題的資源
     * body：轉發時，自定義寫入的內容
     * type：微博類型
     * comment：是否給原作者評論
     * @param array $data 分享的相關資料
     * @return array 分享操作後，相關反饋資訊資料
     */
    public function shareMessage($data)
    {
        $return = array('status'=>0,'data'=>L('PUBLIC_SHARE_FAILED'));          // 分享失敗
        $app = t($data['app_name']);
        $msg['to'] = trim($data['uids'], ',');
        if(empty($msg['to'])) {
            $return['data'] = L('PUBLIC_SHARE_TOUSE_EMPTY');                    // 分享接受人不能為空
            return $return;
        }
        if(!$oldInfo =model('Source')->getSourceInfo($data['type'], $data['sid'], false, $app)) {
            $return['data'] = L('PUBLIC_INFO_SHARE_FORBIDDEN');                 // 此資訊不可以被分享
            return $return;
        }
        $data['content'] = trim($data['content']);
        $content = empty($data['content']) ? "" : "“{$data['content']}”&nbsp;//&nbsp;";
        $content = parse_html($content);
        $message['to'] = $msg['to'];
        $message['content'] = $content.parse_html($oldInfo['source_content']).'&nbsp;&nbsp;<a href="'.$oldInfo['source_url'].'" target=\'_blank\'>檢視</a>';
        if(model('Message')->postMessage($message,$GLOBALS['ts']['_user']['uid'])){
            // $config['name'] = $GLOBALS['ts']['_user']['uname'];
            // $config['content'] = $content;
            // //$config['sourceurl'] = $oldInfo['source_url'];
            // $touids = explode(',', $msg['to']);
            // foreach($touids as $v) {
            //  model('Notify')->sendNotify($v, 'new_message', $config);
            // }

            $return = array('status'=>1,'data'=>L('PUBLIC_SHARE_SUCCESS'));         // 分享成功
        }
        return $return;
    }
}
