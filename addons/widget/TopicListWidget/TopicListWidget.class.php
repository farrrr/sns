<?php
/**
 * 不同類型的話題列表
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class TopicListWidget extends Widget {

	/**
	 * 渲染話題列表頁面
	 * @param array $data 配置相關資料
     * @param integer type 話題類型 1:推薦話題   2:精華話題
     * @param integer limit 列表條數
	 * @return string 渲染頁面的HTML
	 */
	public function render($data) {
		if($data['type']==1){
			$map['recommend'] = 1;
			$map['lock'] = 0;
			$list = model( 'Cache' )->get('feed_topic_recommend');
			if ( !$list ){
				$list = model('FeedTopic')->where($map)->order('count desc')->limit($data['limit'])->findAll();
				!$list && $list=1;
				model( 'Cache' )->set( 'feed_topic_recommend' , $list , 86400 );
			}
            $var['topic_list'] = $list;
            $var['title'] = "推薦話題";
        }
        // if($data['type']==2){
        //     $var['topic_list'] = model('FeedTopic')->where('essence=1')->limit($data['limit'])->findAll();
        //     $var['title'] = "精華話題";
        // }
		$var = array_merge($var,$data);
		$content = $this->renderFile(dirname(__FILE__)."/topicList.html", $var);

		return $content;
	}
	/**
	 * 搜索話題 用於釋出微博發表框
	 */
	public function searchTopic(){
		$key = trim ( t ( $_REQUEST['key'] ) );
		$feedtopicDao = model('FeedTopic');
// 		if ( $key ){
			$data = $feedtopicDao->where("topic_name like '%".$key."%' and recommend=1")->field('topic_id,topic_name')->limit(10)->findAll();
// 		} else {
// 			$data = $feedtopicDao->where('recommend=1')->field('topic_id,topic_name')->order('count desc')->limit(10)->findAll();
// 		}
		exit( json_encode($data) );
	}
}