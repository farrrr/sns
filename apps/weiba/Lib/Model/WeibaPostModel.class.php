<?php
/**
 * 微吧模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class WeibaPostModel extends Model {

    protected $tableName = 'weiba_post';
    protected $error = '';
    protected $fields = array(
        0 =>'post_id',1=>'weiba_id',2=>'post_uid',3=>'title',4=>'content',5=>'post_time',
        6=>'reply_count',7=>'read_count',8=>'last_reply_uid',9=>'last_reply_time',10=>'digest',11=>'top',12=>'lock',
        13=>'api_key',14=>'domain',15=>'province',16=>'city',17=>'area',18=>'reg_ip',
        19=>'is_del',20=>'feed_id',21=>'reply_all_count','_autoinc'=>true,'_pk'=>'post_id'
    );

    /**
     * 發帖同步到微博
     * @param integer post_id 帖子ID
     * @param string title 帖子標題
     * @param string content 帖子內容
     * @param integer uid 釋出者uid
     * @return integer feed_id 微博ID
     */
    public function syncToFeed($post_id,$title,$content,$uid) {
        $d['content'] = '';
        $d['body'] = '【'.$title.'】'.getShort($content,100).'&nbsp;';
        $feed = model('Feed')->put($uid, 'weiba', 'weiba_post', $d, $post_id, 'weiba_post');
        return $feed['feed_id'];
    }

    /**
     * 發表帖子forapi
     * @param integer weiba_id 微吧ID
     * @param varchar title 帖子標題
     * @param varchar content 帖子內容
     * @param integer user_id 帖子作者
     */
    public function createPostForApi($weiba_id,$title,$content,$uid){
        $data['weiba_id'] = intval($weiba_id);
        $data['title'] = t($title);
        $data['content'] = h($content);
        $data['post_uid'] = intval($uid);
        $data['post_time'] = time();
        $data['last_reply_time'] = $data['post_time'];
        $res = D('weiba_post')->add($data);
        if($res){
            D('weiba')->where('weiba_id='.$data['weiba_id'])->setInc('thread_count');
            //同步到微博
            $feed_id = $this->syncToFeed($res,$data['title'],$data['content'],$data['post_uid']);
            D('weiba_post')->where('post_id='.$res)->setField('feed_id',$feed_id);
            return true;
        }else{
            return false;
        }
    }

    /**
     * 收藏帖子
     * @param integer post_id 帖子ID
     */
    public function favoriteForApi($post_id){
        $postDetail = D('weiba_post')->where('post_id='.intval($post_id))->find();
        $data['post_id'] = intval($post_id);
        $data['weiba_id'] = $postDetail['weiba_id'];
        $data['post_uid'] = $postDetail['post_uid'];
        $data['uid'] = $GLOBALS['ts']['mid'];
        $data['favorite_time'] = time();
        if(D('weiba_favorite')->add($data)){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 取消收藏帖子
     * @param integer post_id 帖子ID
     */
    public function unfavoriteForApi($post_id){
        $map['post_id'] = intval($post_id);
        $map['uid'] = $GLOBALS['ts']['mid'];
        if(D('weiba_favorite')->where($map)->delete()){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 為feed提供應用資料來源資訊 - 與模板weiba_post.feed.php配合使用
     * @param integer row_id 帖子ID
     * @param bool _forApi 提供給API的資料
     */
    public function getSourceInfo($row_id, $_forApi = false){
        $info =  $this->find($row_id);
        if(!$info) return false;
        $info['source_user_info'] = model('User')->getUserInfo($info['post_uid']);
        $info['source_user'] = $info['post_uid'] == $GLOBALS['ts']['mid'] ? L('PUBLIC_ME'): $info['source_user_info']['space_link'];            // 我
        $info['source_type'] = L('PUBLIC_WEIBA');
        $info['source_title'] = $forApi ? parseForApi($info['source_user_info']['space_link']) : $info['source_user_info']['space_link'];   //微博title暫時為空
        $info['source_url'] = U('weiba/Index/postDetail', array('post_id'=>$row_id));
        $info['ctime'] = $info['post_time'];
        $feed = D('feed_data')->field('feed_id,feed_content')->find($info['feed_id']);
        $info['source_content'] = $feed['feed_content'];
        $info['app_row_table'] = 'weiba_post';
        $info['app_row_id'] = $info['post_id'];
        return $info;
    }
}
