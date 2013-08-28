<?php
/**
 * 頻道前臺管理控制器
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class ManageAction extends Action
{
    /**
     * 頻道管理彈窗
     * @return void
     */
    public function getAdminBox()
    {
        // 獲取微博ID
        $data['feedId'] = intval($_REQUEST['feed_id']);
        // 頻道分類ID
        $data['channelId'] = empty($_REQUEST['channel_id']) ? 0 : intval($_REQUEST['channel_id']);
        // 獲取全部頻道列表
        $data['categoryList'] = model('CategoryTree')->setTable('channel_category')->getCategoryList();
        // 獲取該微博已經選中的頻道
        $data['selectedChannels'] = D('Channel', 'channel')->getSelectedChannels($data['feedId']);
        // 是否有動態效果
        $data['clear'] = empty($_REQUEST['clear']) ? 0 : intval($_REQUEST['clear']);

        $this->assign($data);
        $this->display('manageBox');
    }

    /**
     * 添加微博進入頻道
     * @return json 操作後的相關資訊資料
     */
    public function doAddChannel()
    {
        // 微博ID
        $feedId = intval($_POST['feedId']);
        // 判斷資源是否刪除
        $fmap['feed_id'] = $feedId;
        $fmap['is_del'] = 0;
        $isExist = model('Feed')->where($fmap)->count();
        if($isExist == 0) {
            $return['status'] = 0;
            $return['info'] = '內容已被刪除，推薦失敗';
            exit(json_encode($return));
        }
        // 頻道ID陣列
        $channelIds = t($_POST['data']);
        $channelIds = explode(',', $channelIds);
        $channelIds = array_filter($channelIds);
        $channelIds = array_unique($channelIds);
        if(empty($feedId)) {
            $res['status'] = 0;
            $res['info'] = '推薦失敗';
            exit(json_encode($res));
        }
        // 添加微博進入頻道
        $result = D('Channel', 'channel')->setChannel($feedId, $channelIds);
        if($result) {
            if(!empty($channelIds)) {
                $config['feed_content'] = getShort(D('feed_data')->where('feed_id='.$feedId)->getField('feed_content'),10);
                $map['channel_category_id'] = array('in',$channelIds);
                $config['channel_name'] = implode(',',getSubByKey(D('channel_category')->where($map)->field('title')->findAll(),'title'));
                $uid = D('feed')->where('feed_id='.$feedId)->getField('uid');
                $config['feed_url'] = '<a target="_blank" href="'.U('channel/Index/index',array('cid'=>$channelIds[0])).'">點此檢視</a>';
                model('Notify')->sendNotify($uid, 'channel_add_feed', $config);
                //添加積分
                model('Credit')->setUserCredit($this->mid,'recommend_to_channel');
            }
            if(empty($channelIds)){
                //添加積分
                model('Credit')->setUserCredit($this->mid,'unrecommend_to_channel');
            }
            $res['status'] = 1;
            $res['info'] = '推薦成功';
        } else {
            $res['status'] = 0;
            $res['info'] = '推薦失敗';
        }
        exit(json_encode($res));
    }
}
