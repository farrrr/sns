<?php
/**
 * 微博列表
 * @example {:W('FeedList',array('type'=>'space','feed_type'=>$feed_type,'feed_key'=>$feed_key,'loadnew'=>0,'gid'=>$gid))}
 * @author jason
 * @version TS3.0
 */
class FeedListWidget extends Widget {
	
	private static $rand = 1;
	private $limitnums   = 10;

    /**
     * @param string type 獲取哪類微博 following:我關注的 space：
     * @param string feed_type 微博類型
     * @param string feed_key 微博關鍵字
     * @param integer fgid 關注的分組id
     * @param integer gid 群組id
     * @param integer loadnew 是否載入更多 1:是  0:否
     */
	public function render($data) {
		$var = array();
		$var['loadmore'] = 1;
		$var['loadnew'] = 1;
		$var['tpl'] = 'FeedList.html';
		
 		is_array($data) && $var = array_merge($var, $data);
    
 		$weiboSet = model('Xdata')->get('admin_Config:feed');
        $var['initNums'] = $weiboSet['weibo_nums'];
        $var['weibo_type'] = $weiboSet['weibo_type'];
        $var['weibo_premission'] = $weiboSet['weibo_premission'];
        // 我關注的頻道
        $var['channel'] = M('channel_follow')->where('uid='.$this->mid)->count();
 
        // 查詢是否有話題ID
        if($var['topic_id']) {
        	$content = $this->getTopicData($var,'_FeedList.html');
        } else {
        	$content = $this->getData($var,'_FeedList.html');
        }
        // 檢視是否有更多資料
        if(empty($content['html'])) {
        	// 沒有更多的
        	$var['list'] = L('PUBLIC_WEIBOISNOTNEW');
        } else {
        	$var['list'] = $content['html'];
        	$var['lastId'] = $content['lastId'];
        	$var['firstId'] = $content['firstId'] ? $content['firstId'] : 0;
        	$var['pageHtml']	= $content['pageHtml'];
        }
	    $content['html'] = $this->renderFile(dirname(__FILE__)."/".$var['tpl'], $var); 
		self::$rand ++;
		unset($var, $data);
        //輸出資料
		return $content['html'];
    }
    /**
     * 顯示更多微博
     * @return  array 更多微博資訊、狀態和提示
     */
    public function loadMore() {
        // 獲取GET與POST資料
    	$_REQUEST = $_GET + $_POST;
        // 查詢是否有分頁
    	if(!empty($_REQUEST['p']) || intval($_REQUEST['load_count']) == 4) {
    		unset($_REQUEST['loadId']);
    		$this->limitnums = 40;
    	} else {
    		$return = array('status'=>-1,'msg'=>L('PUBLIC_LOADING_ID_ISNULL'));
            $_REQUEST['loadId'] = intval($_REQUEST['loadId']);
    		$this->limitnums = 10;
    	}
        // 查詢是否有話題ID
        if($_REQUEST['topic_id']) { 
            $content = $this->getTopicData($_REQUEST,'_FeedList.html');
        } else {
    	    $content = $this->getData($_REQUEST,'_FeedList.html');
        }
        // 檢視是否有更多資料
    	if(empty($content['html'])) {
            // 沒有更多的
    		$return = array('status'=>0,'msg'=>L('PUBLIC_WEIBOISNOTNEW'));
    	} else {
    		$return = array('status'=>1,'msg'=>L('PUBLIC_SUCCESS_LOAD'));
    		$return['html'] = $content['html'];
    		$return['loadId'] = $content['lastId'];
            $return['firstId'] = ( empty($_REQUEST['p']) && empty($_REQUEST['loadId']) ) ? $content['firstId'] : 0;
    		$return['pageHtml']	= $content['pageHtml'];
    	}
        exit(json_encode($return));
    }

    /**
     * 顯示最新微博
     * @return  array 最新微博資訊、狀態和提示
     */
    public function loadNew() {
    	$return = array('status'=>-1,'msg'=>'');
        $_REQUEST['maxId'] = intval($_REQUEST['maxId']);
    	if(empty($_REQUEST['maxId'])){
    		echo json_encode($return);exit();
    	}
    	$content = $this->getData($_REQUEST,'_FeedList.html');
    	if(empty($content['html'])){//沒有最新的
    		$return = array('status'=>0,'msg'=>L('PUBLIC_WEIBOISNOTNEW'));
    	}else{
    		$return = array('status'=>1,'msg'=>L('PUBLIC_SUCCESS_LOAD'));
    		$return['html'] = $content['html'];
    		$return['maxId'] = intval($content['firstId']);
            $return['count'] = intval($content['count']);
    	}
    	echo json_encode($return);exit();
    }
    
    /**
     * 獲取微博資料，渲染微博顯示頁面
     * @param array $var 微博資料相關參數
     * @param string $tpl 渲染的模板
     * @return array 獲取微博相關模板資料
     */
    private function getData($var, $tpl = 'FeedList.html') {
    	$var['feed_key'] = t($var['feed_key']);
        $var['cancomment'] = isset($var['cancomment']) ? $var['cancomment'] : 1;
        //$var['cancomment_old_type'] = array('post','repost','postimage','postfile');
        $var['cancomment_old_type'] = array('post','repost','postimage','postfile','weiba_post','weiba_repost');
        // 獲取微博配置
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $var = array_merge($var, $weiboSet);
    	$var['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
    	$map = $list = array();
    	$type = $var['new'] ? 'new'.$var['type'] : $var['type'];	// 最新的微博與默認微博類型一一對應

    	switch($type) {
    		case 'following':// 我關注的
    			if(!empty($var['feed_key'])){
    				//關鍵字匹配 採用搜索引擎相容函數搜索 後期可能會擴展為搜索引擎
    				$list = model('Feed')->searchFeed($var['feed_key'],'following',$var['loadId'],$this->limitnums);
    			}else{
    				$where =' (a.is_audit=1 OR a.is_audit=0 AND a.uid='.$GLOBALS['ts']['mid'].') AND a.is_del = 0 ';
    				if($var['loadId'] > 0){ //非第一次
    					$where .=" AND a.feed_id < '".intval($var['loadId'])."'";
    				}
    				if(!empty($var['feed_type'])){
    					if ( $var['feed_type'] == 'post' ){
    						$where .=" AND a.is_repost = 0";
    					} else {
    						$where .=" AND a.type = '".t($var['feed_type'])."'";
    					}
    				}
    				$list =  model('Feed')->getFollowingFeed($where,$this->limitnums,'',$var['fgid']);
    			}
    			break;
    		case 'all'://所有的 --正在發生的
    			if(!empty($var['feed_key'])){
    				//關鍵字匹配 採用搜索引擎相容函數搜索 後期可能會擴展為搜索引擎
    				$list = model('Feed')->searchFeed($var['feed_key'],'all',$var['loadId'],$this->limitnums);
    			}else{
                    $where =' (is_audit=1 OR is_audit=0 AND uid='.$GLOBALS['ts']['mid'].') AND is_del = 0 ';
    				if($var['loadId'] > 0){ //非第一次
    					$where .=" AND feed_id < '".intval($var['loadId'])."'";
    				}
    				if(!empty($var['feed_type'])){
    					if ( $var['feed_type'] == 'post' ){
    						$where .=" AND is_repost = 0";
    					} else {
    						$where .=" AND type = '".t($var['feed_type'])."'";
    					}
    				}
    				$list = model('Feed')->getList($where,$this->limitnums);
    			}
    			break;
    		case 'newfollowing'://關注的人的最新微博
    			$where = ' a.is_del = 0 and a.is_audit = 1 and a.uid != '.$GLOBALS['ts']['uid'];
    			if($var['maxId'] > 0){
    				$where .=" AND a.feed_id > '".intval($var['maxId'])."'";
    				$list = model('Feed')->getFollowingFeed($where);
                    $content['count'] = $list['count'];
    			}		
    			break;
    		case 'newall':	//所有人最新微博 -- 正在發生的
    			if($var['maxId'] > 0){
    				$map['feed_id'] = array('gt',intval($var['maxId']));
    				$map['is_del'] = 0;
                    $map['is_audit'] = 1;
                    $map['uid']   = array('neq',$GLOBALS['ts']['uid']);
    				$list = model('Feed')->getList($map);    
                    $content['count'] = $list['count'];
    			}
    			break;
    		case 'space':	//使用者個人空間
    			if(!empty($var['feed_key'])){
    				//關鍵字匹配 採用搜索引擎相容函數搜索 後期可能會擴展為搜索引擎
    				$list = model('Feed')->searchFeed($var['feed_key'],'space',$var['loadId'],$this->limitnums,'',$var['feed_type']);
    			}else{
	    			if($var['loadId']>0){
	    				$map['feed_id'] = array('lt',intval($var['loadId']));
	    			}
	    			$map['is_del'] = 0;
                    if($GLOBALS['ts']['mid'] != $GLOBALS['ts']['uid']) $map['is_audit'] = 1;
    				$list = model('Feed')->getUserList($map,$GLOBALS['ts']['uid'],  $var['feedApp'], $var['feed_type'],$this->limitnums);
    			}
    			break;
            case 'channel':
                $where = ' (c.is_audit=1 OR c.is_audit=0) AND c.is_del = 0 ';
                if($var['loadId'] > 0) { //非第一次
                    $where .= " AND c.feed_id < '".intval($var['loadId'])."'";
                }
                if(!empty($var['feed_type'])) {
                    $where .= " AND c.type = '".t($var['feed_type'])."'";
                }

                $list = D('ChannelFollow', 'channel')->getFollowingFeed($where, $this->limitnums, '' ,$var['fgid']);
                break;
    	}
    	// 分頁的設定
        isset($list['html']) && $var['html'] = $list['html'];
    	if(!empty($list['data'])) {
    		$content['firstId'] = $var['firstId'] = $list['data'][0]['feed_id'];
    		$content['lastId'] = $var['lastId'] = $list['data'][(count($list['data'])-1)]['feed_id'];
            $var['data'] = $list['data'];

            //贊功能
            $feed_ids = getSubByKey($var['data'],'feed_id');
            $var['diggArr'] = model('FeedDigg')->checkIsDigg($feed_ids, $GLOBALS['ts']['mid']);
            
            $uids = array();
            foreach($var['data'] as &$v) {
            	switch ( $v['app'] ){
            		case 'weiba':
            			$v['from'] = getFromClient(0 , $v['app'] , '微吧');
            			break;
                    case 'tipoff':
                    $v['from'] = getFromClient(0 , $v['app'] , '爆料');
                    break;
            		default:
            			$v['from'] = getFromClient( $v['from'] , $v['app']);
            			break;
            	}
            	!isset($uids[$v['uid']]) && $v['uid'] != $GLOBALS['ts']['mid'] && $uids[] = $v['uid'];
            }
            if(!empty($uids)) {
            	$map = array();
            	$map['uid'] = $GLOBALS['ts']['mid'];
            	$map['fid'] = array('in',$uids);
            	$var['followUids'] = model('Follow')->where($map)->getAsFieldArray('fid');
            } else {
            	$var['followUids'] = array();
            }
    	}
    	$content['pageHtml'] = $list['html'];
	    // 渲染模版
	    $content['html'] = $this->renderFile(dirname(__FILE__)."/".$tpl, $var);
      
	    return $content;
    }

    /**
     * 獲取話題微博資料，渲染微博顯示頁面
     * @param array $var 微博資料相關參數
     * @param string $tpl 渲染的模板
     * @return array 獲取微博相關模板資料
     */
    private function getTopicData($var,$tpl='FeedList.html') {
        $var['cancomment'] = isset($var['cancomment']) ? $var['cancomment'] : 1;
        //$var['cancomment_old_type'] = array('post','repost','postimage','postfile');
        $var['cancomment_old_type'] = array('post','repost','postimage','postfile','weiba_post','weiba_repost');
        $weiboSet = model('Xdata')->get('admin_Config:feed');
        $var = array_merge($var,$weiboSet);
        $var['remarkHash'] = model('Follow')->getRemarkHash($GLOBALS['ts']['mid']);
        $map = $list = array();
        $type = $var['new'] ? 'new'.$var['type'] : $var['type'];    //最新的微博與默認微博類型一一對應

        if($var['loadId'] > 0){ //非第一次
            $topics['topic_id'] = $var['topic_id'];
            $topics['feed_id'] = array('lt',intval($var['loadId']));
            $map['feed_id'] = array('in',getSubByKey(D('feed_topic_link')->where($topics)->field('feed_id')->select(),'feed_id'));
        }else{
            $map['feed_id'] = array('in',getSubByKey(D('feed_topic_link')->where('topic_id='.$var['topic_id'])->field('feed_id')->select(),'feed_id'));
        }
        if(!empty($var['feed_type'])){
            $map['type'] = t($var['feed_type']);
        }
        //$map['is_del'] = 0;
        $map['_string'] = ' (is_audit=1 OR is_audit=0 AND uid='.$GLOBALS['ts']['mid'].') AND is_del = 0 ';
        $list = model('Feed')->getList($map,$this->limitnums);
        //分頁的設定
        isset($list['html']) && $var['html'] = $list['html'];
        
        if(!empty($list['data'])){
            $content['firstId'] = $var['firstId'] = $list['data'][0]['feed_id'];
            $content['lastId']  = $var['lastId'] = $list['data'][(count($list['data'])-1)]['feed_id'];
            $var['data'] = $list['data'];
            
            //贊功能
            $feed_ids = getSubByKey($var['data'],'feed_id');
            $var['diggArr'] = model('FeedDigg')->checkIsDigg($feed_ids, $GLOBALS['ts']['mid']);
            
            $uids = array();
            foreach($var['data'] as &$v){
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
            if(!empty($uids)){
                $map = array();
                $map['uid'] = $GLOBALS['ts']['mid'];
                $map['fid'] = array('in',$uids);
                $var['followUids'] = model('Follow')->where($map)->getAsFieldArray('fid');
            }else{
                $var['followUids'] = array();
            }
        }

        $content['pageHtml'] = $list['html'];
       
        //渲染模版
        $content['html'] = $this->renderFile(dirname(__FILE__)."/".$tpl,$var);
      
        return $content;
    }

    /**
     * 獲取微吧帖子資料
     * @param  [varname] [description]
     */
    public function getPostDetail() {
        $post_id = intval($_POST['post_id']);
        $post_detail = D('weiba_post')->where('is_del=0 and post_id='.$post_id)->find();
        if($post_detail && D('weiba')->where('is_del=0 and weiba_id='.$post_detail['weiba_id'])->find()){
            $post_detail['post_url'] = U('weiba/Index/postDetail',array('post_id'=>$post_id));
            $author = model('User')->getUserInfo($post_detail['post_uid']);
            $post_detail['author'] = $author['space_link'];
            $post_detail['post_time'] = friendlyDate($post_detail['post_time']);
            $post_detail['from_weiba'] = D('weiba')->where('weiba_id='.$post_detail['weiba_id'])->getField('weiba_name');
            $post_detail['weiba_url'] = U('weiba/Index/detail',array('weiba_id'=>$post_detail['weiba_id']));
            return json_encode($post_detail);
        }else{
            echo 0;
        }
    }

    public function getTipoffDetail(){
        $tipoff_id = intval($_POST['tipoff_id']);
        $tipoff_detail = D('tipoff')->where('deleted=0 and archived=0 and tipoff_id='.$tipoff_id)->find();
        if($tipoff_detail){
            $tipoff_detail['tipoff_url'] = U('tipoff/Index/detail',array('id'=>$tipoff_id));
            $author = model('User')->getUserInfo($tipoff_detail['uid']);
            $tipoff_detail['author'] = $author['space_link'];
            $tipoff_detail['publish_time'] = friendlyDate($tipoff_detail['publish_time']);
            $tipoff_detail['from_category'] = D('tipoff_category')->where('tipoff_category_id='.$tipoff_detail['category_id'])->getField('title');
            $tipoff_detail['category_url'] = U('tipoff/Index/index',array('cid'=>$tipoff_detail['category_id']));
            return json_encode($tipoff_detail);
        }else{
            echo 0;
        }
    }
    
}