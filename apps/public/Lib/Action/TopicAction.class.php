<?php
class TopicAction extends Action{

    // 專題頁
    public function index()
    {
        //echo $_GET['domain'];exit;
        if($_GET['domain']){
            $map['domain'] = t($_GET['domain']);
            //echo $domain;exit;
            $data['search_key'] = model('FeedTopic')->where($map)->getField('topic_name');
        }else{
            $data['search_key'] = $this->__getSearchKey();
        }
        // 專題資訊
        if (false == $data['topics'] = model('FeedTopic')->getTopic($data['search_key'])) {
            if (null == $data['search_key']) {
                $this->error('此話題不存在');
            }
            $data['topics']['name'] = t($data['search_key']);
        }
        if($data['topics']['lock']==1){
            $this->error('該話題已被遮蔽');
        }
        if($data['topics']['pic']){
            $pic = D('attach')->where('attach_id='.$data['topics']['pic'])->find();
            //$data['topics']['pic'] = UPLOAD_URL.'/'.$pic['save_path'].$pic['save_name'];
            $pic_url = $pic['save_path'].$pic['save_name'];
            $data['topics']['pic'] = getImageUrl($pic_url);
        }
        $data['topic'] = $data['search_key'] ? $data['search_key'] : html_entity_decode($data['topics']['name'], ENT_QUOTES);
        $data['topic_id'] = $data['topics']['topic_id'] ? $data['topics']['topic_id'] : model('FeedTopic')->getTopicId($data['search_key']);
        $initHtml = '#'.$data['search_key'].'#';
        $this->assign('initHtml' , $initHtml);
        $this->assign ( $data );
        //seo
        $seo= model('Xdata')->get("admin_Config:seo_feed_topic");
        $replace['topicName'] = $data['topic'];
        $replace['topicNote'] = $data['topics']['note'];
        $replace['topicDes'] = $data['topics']['des'];
        if($lastTopic = D('feed_data')->where('feed_id='.D('feed_topic_link')->where('topic_id='.$data['topic_id'])->order('feed_topic_id desc')->limit(1)->getField('feed_id'))->getField('feed_content')){
            $replace['lastTopic'] = $lastTopic;
        }
        $replaces = array_keys($replace);
        foreach($replaces as &$v){
            $v = "{".$v."}";
        }
        $seo['title'] = str_replace($replaces,$replace,$seo['title']);
        $seo['keywords'] = str_replace($replaces,$replace,$seo['keywords']);
        $seo['des'] = str_replace($replaces,$replace,$seo['des']);
        !empty($seo['title']) && $this->setTitle($seo['title']);
        !empty($seo['keywords']) && $this->setKeywords($seo['keywords']);
        !empty($seo['des']) && $this->setDescription($seo['des']);
        $this->display();
    }

    private function __getSearchKey()
    {
        $key = '';
        // 為使搜索條件在分頁時也有效，將搜索條件記錄到SESSION中
        if(isset($_REQUEST ['k']) && !empty($_REQUEST['k'])) {
            if(t($_GET ['k'])) {
                $key = html_entity_decode(urldecode($_GET['k']), ENT_QUOTES);
            } else if(t($_POST['k'])) {
                $key = $_POST['k'];
            }
            $key = t($key);
            // 關鍵字不能超過200個字元
            if (mb_strlen ( $key, 'UTF8' ) > 200)
                $key = mb_substr ( $key, 0, 200, 'UTF8' );
            $_SESSION ['home_user_search_key'] = serialize ( $key );

        } else if (is_numeric ( $_GET [C ( 'VAR_PAGE' )] )) {
            $key = unserialize ( $_SESSION ['home_user_search_key'] );

        } else {
            //unset($_SESSION['home_user_search_key']);
        }
        $key = str_replace(array('%','\'','"','<','>'),'',$key);
        return trim ( $key );
    }

}
