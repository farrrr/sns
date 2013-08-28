<?php
/**
 * 話題模型
 * @version TS3.0
 */
class FeedTopicModel extends Model {

    var $tableName = 'feed_topic';

    //添加話題
    public function addTopic( $content, $feedId = false, $type ){
        $content = str_replace("＃", "#", $content);
        preg_match_all("/#([^#]*[^#^\s][^#]*)#/is",$content,$arr);
        $arr = array_unique($arr[1]);
        foreach($arr as $v){
            return $this->addKey($v, $feedId, $type);
        }
    }

    //添加話題
    private function addKey($key, $feedId, $type){
        //$map['name'] = trim(t(mStr(preg_replace("/#/",'',trim($key)),150,'utf-8',false)));
        $map['topic_name'] = trim(preg_replace("/#/",'',t($key)));
        if( $topic = $this->where($map)->find() ){
            $this->setInc('count',$map);
            if($topic['recommend']==1){
                model( 'Cache' )->rm('feed_topic_recommend'); //清除快取
            }
            if($feedId) {
                $this->addFeedJoinTopic($map['topic_name'], $feedId, $type, true);
            }
        }else{
            $map['count'] = 1;
            $map['ctime'] = time();
            $topicId = $this->add($map);
            if($feedId) {
                $this->addFeedJoinTopic($topicId, $feedId, $type);
            }
            return $topicId;
        }
    }

    //添加微博與話題的關聯
    private function addFeedJoinTopic($topicNameOrId, $feedId, $type, $isExist = false) {
        if($isExist) {
            $map['topic_name'] = $topicNameOrId;
            $topicId = $this->where($map)->getField('topic_id');
        } else {
            $topicId = $topicNameOrId;
        }

        $add['feed_id'] = $feedId;
        $add['topic_id'] = $topicId;
        if(is_null($type)) {
            $add['type'] = 0;
        } else {
            $add['type'] = $type;
        }
        //  $add['transpond_id'] = $data['transpond_id'];

        D('feed_topic_link')->add($add);
    }

    //刪除微博與話題關聯
    public function deleteWeiboJoinTopic($feedId) {
        $del['feed_id'] = $feedId;
        if($topic_id = D('feed_topic_link')->where($del)->getField('topic_id')){
            D('feed_topic_link')->where($del)->delete();
            D('feed_topic')->where('topic_id='.$topic_id)->setDec('count');
            if(D('feed_topic')->where('topic_id='.$topic_id)->getField('recommend')==1){
                model( 'Cache' )->rm('feed_topic_recommend'); //清除快取
            }
        }
    }

    // 獲取話題詳細資訊
    public function getTopic($topic_name = null)
    {
        if ($topic_name) {
            $topic_name = $topic_name;
            $map['topic_id'] = $this->getTopicId($topic_name);
        } else {
            return false;
        }
        //$map['isdel'] = 0;
        $topic = $this->where($map)->find();
        if ($topic) {
            $topic['topic_name'] = $topic_name ? t($topic_name) : D('Topic', 'weibo')->getField('topic_name', "topic_id={$topic['topic_id']}");
        }
        return $topic;
    }
    /**
     *返回與話題相關的微博ID
     */
    public function getFeedIdByTopic( $topic ){
        $sql = "select b.feed_id as fid from {$this->tablePrefix}feed_topic a inner join {$this->tablePrefix}feed_topic_link b on a.topic_id=b.topic_id where a.topic_name ='".$topic."'";
        $feeds = $this->query($sql);
        return getSubByKey( $feeds, 'fid' );
    }
    /**
     * 獲取給定話題名的話題ID
     * @param string $name 話題名
     * @return int 話題ID
     */
    public function getTopicId($topic_name)
    {
        $map['topic_name'] = t(preg_replace("/#/",'',$topic_name));
        if (empty($map['topic_name'])) return 0;
        $info = $this->where($map)->find();
        if ($info['topic_id']) {
            return $info['topic_id'];
        } else {
            $map['count'] = 0;
            $map['ctime'] = time();
            return $this->add($map);
        }
    }
}
