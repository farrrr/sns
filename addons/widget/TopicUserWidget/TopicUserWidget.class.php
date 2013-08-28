<?php
/**
 * 具有相同資料項的人Widget
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class TopicUserWidget extends Widget {

	/**
	 * 渲染話題人物頁面
	 * @param array $data 配置相關資料
     * @param integer topic_id 話題ID
     * @param integer type 話題人物類型 1:話題人物推薦   2:參與話題的人
     * @param integer limit 人物數量限制
	 * @return string 渲染頁面的HTML
	 */
	public function render($data) {
        $limit = isset($data['limit'])?intval($data['limit']):12;
		if($data['type']==1){
            $topic = model('FeedTopic')->where('topic_id='.$data['topic_id'])->find();
            $var['topic_user'] = array_slice(explode(',',$topic['topic_user']), 0, 12);
        }
        if($data['type']==2){
            $feedTopicId = getSubByKey(D('feed_topic_link')->where('topic_id='.$data['topic_id'])->order('feed_id desc')->field('feed_id')->findAll(),'feed_id');
            $map['feed_id'] = array('in', $feedTopicId);
            $topic_user = array_unique(getSubByKey(D('feed')->where($map)->field('uid')->order('feed_id desc')->findAll(),'uid'));
            $var['topic_user'] = array_slice($topic_user,0,$limit);
        }
        $var['user'] = model('User')->getUserInfoByUids($var['topic_user']);
        $var['follow_state'] = model('Follow')->getFollowStateByFids($this->mid, $var['topic_user']);
        $var['mid'] = $this->mid;
		$var = array_merge($var,$data);
		$content = $this->renderFile(dirname(__FILE__)."/topicUser.html", $var);

		return $content;
	}
}