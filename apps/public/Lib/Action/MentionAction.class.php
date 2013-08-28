<?php
/**
 * MentionAction 提到我的
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class MentionAction extends Action {

    /**
     * 提到我的微博頁面
     */
    public function index()
    {
        //獲取未讀@Me的條數
        $this->assign('unread_atme_count',model('UserData')->where('uid='.$this->mid." and `key`='unread_atme'")->getField('value'));
        // 拼裝查詢條件
        $map['uid'] = $this->mid;

        $d['tab'] = model('Atme')->getTab(null);
        foreach ($d['tab'] as $key=>$vo){
            if($key=='feed'){
                $d['tabHash']['feed'] = L('PUBLIC_WEIBO');
            }elseif($key=='comment'){
                $d['tabHash']['comment'] = L('PUBLIC_STREAM_COMMENT');
            }else{
                $langKey = 'PUBLIC_APPNAME_' . strtoupper ( $key );
                $lang = L($langKey);
                if($lang==$langKey){
                    $d['tabHash'][$key] = ucfirst ( $key );
                }else{
                    $d['tabHash'][$key] = $lang;
                }
            }
        }
        $this->assign($d);

        !empty($_GET['t']) && $map['table'] = t($_GET['t']);

        // 設定應用名稱與表名稱
        $app_name = isset($_GET['app_name']) ? t($_GET['app_name']) : 'public';
        // $app_table = isset($_GET['app_table']) ? t($_GET['app_table']) : '';
        // 獲取@Me微博列表
        $at_list = model('Atme')->setAppName($app_name)->setAppTable($app_table)->getAtmeList($map);

        // 贊功能
        $feed_ids = getSubByKey ( $at_list ['data'], 'feed_id' );
        $diggArr = model ( 'FeedDigg' )->checkIsDigg ( $feed_ids, $GLOBALS ['ts'] ['mid'] );
        $this->assign ( 'diggArr', $diggArr );

        // dump($at_list);exit;
        // 添加Widget參數資料
        foreach($at_list['data'] as &$val) {
            if($val['source_table'] == 'comment') {
                $val['widget_sid'] = $val['sourceInfo']['source_id'];
                $val['widget_style'] = $val['sourceInfo']['source_table'];
                $val['widget_sapp'] = $val['sourceInfo']['app'];
                $val['widget_suid'] = $val['sourceInfo']['uid'];
                $val['widget_share_sid'] = $val['sourceInfo']['source_id'];
            } else if($val['is_repost'] == 1) {
                $val['widget_sid'] = $val['source_id'];
                $val['widget_stype'] = $val['source_table'];
                $val['widget_sapp'] = $val['app'];
                $val['widget_suid'] = $val['uid'];
                $val['widget_share_sid'] = $val['app_row_id'];
                $val['widget_curid'] = $val['source_id'];
                $val['widget_curtable'] = $val['source_table'];
            } else {
                $val['widget_sid'] = $val['source_id'];
                $val['widget_stype'] = $val['source_table'];
                $val['widget_sapp'] = $val['app'];
                $val['widget_suid'] = $val['uid'];
                $val['widget_share_sid'] = $val['source_id'];
            }
            // 獲取轉發與評論數目
            if($val['source_table'] != 'comment') {
                $feedInfo = model('Feed')->get($val['widget_sid']);
                $val['repost_count'] = $feedInfo['repost_count'];
                $val['comment_count'] = $feedInfo['comment_count'];
            }
            //解析資料成網頁端顯示格式(@xxx  加連結)
            $val['source_content'] = parse_html($val['source_content']);
        }
        // 獲取微博設定
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $this->assign($weiboSet);
        // 使用者@Me未讀數目重置
        //model('UserCount')->resetUserCount($this->mid, 'unread_atme',  0);
        $this->setTitle(L('PUBLIC_MENTION_INDEX'));
        $userInfo = model('User')->getUserInfo($this->mid);
        $this->setKeywords('@提到'.$userInfo['uname'].'的訊息');
        $this->assign($at_list);
        $this->display();
    }

    /**
     * @某個人的彈窗
     */
    public function at() {
        $uid = t($_GET['touid']);
        if(!empty($uid)) {
            $userInfo = model('User')->getUserInfo($uid);
            if(!empty($userInfo)){
                $d['initHtml'] = '@'.$userInfo['uname'].' ';
            }
        }
        $this->assign($d);
        $this->display();
    }
}
