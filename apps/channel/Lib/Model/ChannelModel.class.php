<?php
/**
 * 頻道分類模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class ChannelModel extends Model
{
    protected $tableName = 'channel';

    /**
     * 獲取資源列表
     * @param array $map 查詢條件
     * @return array 獲取資源列表
     */
    public function getChannelList($map)
    {
        // 獲取資源分頁結構
        $data = $this->field('DISTINCT `feed_id`, `status`')->where($map)->order('`feed_channel_link_id` DESC')->findPage();
        // 獲取微博ID
        $feedIds = getSubByKey($data['data'], 'feed_id');
        // 獲取微博分類頻道資訊
        $cmap['c.feed_id'] = array('IN', $feedIds);
        $categoryInfo = D()->table('`'.$this->tablePrefix.'channel` AS c LEFT JOIN `'.$this->tablePrefix.'channel_category` AS cc ON cc.channel_category_id = c.channel_category_id')
            ->field('c.`feed_id`, c.`status`, cc.channel_category_id, cc.`title`')
            ->where($cmap)
            ->findAll();
        $categoryInfos = array();
        foreach($categoryInfo as $val) {
            $categoryInfos[$val['feed_id']][] = $val;
        }
        // 獲取微博資訊
        $feedInfo = model('Feed')->getFeeds($feedIds);
        $feedInfos = array();
        foreach($feedInfo as $val) {
            $feedInfos[$val['feed_id']] = $val;
        }
        // 組裝資訊
        foreach($data['data'] as &$value) {
            $value['uid'] = $feedInfos[$value['feed_id']]['user_info']['uid'];
            $value['uname'] = $feedInfos[$value['feed_id']]['user_info']['uname'];
            $value['content'] = $feedInfos[$value['feed_id']]['body'];
            $value['categoryInfo'] = $categoryInfos[$value['feed_id']];
        }

        return $data;
    }

    /**
     * 刪除指定資源資訊
     * @param array $rowId 資源ID陣列
     * @return boolean 是否刪除成功
     */
    public function cancelRecommended($rowId)
    {
        $map['feed_id'] = array('IN', $rowId);
        $result = $this->where($map)->delete();
        return (boolean)$result;
    }

    /**
     * 稽覈資源操作
     * @return array $rowId 資源ID陣列
     * @return boolean 是否稽覈成功
     */
    public function auditChannelList($rowId)
    {
        $map['feed_id'] = array('IN', $rowId);
        $save['status'] = 1;
        $result = $this->where($map)->save($save);
        return (boolean)$result;
    }

    /**
     * 獲取指定分類的記錄數目
     * @param integer $cid 頻道分類ID
     * @return integer 指定分類的記錄數目
     */
    public function getChannelCount($cid)
    {
        !empty($cid) && $map['channel_category_id'] = $cid;
        $map['status'] = 1;
        $count = $this->where($map)->count();

        return $count;
    }

    /**
     * 獲取指定頻道分類下的列表資料
     * @param integer $cid 分類ID
     * @return array 指定頻道分類下的列表資料
     */
    public function getChannelFindPage($cid)
    {
        !empty($cid) && $map['channel_category_id'] = $cid;
        $map['status'] = 1;
        $data = $this->where($map)->field('feed_id')->order('feed_channel_link_id DESC')->findPage(40);

        return $data;
    }

    /**
     * 獲取指定分類下，使用者貢獻最高的使用者陣列
     * @param integer $cid 頻道分類ID
     * @param integer $limit 結果集數目，默認為10
     * @return array 使用者貢獻最高陣列
     */
    public function getTopList($cid, $limit = 10)
    {
        // 驗證資料
        if(empty($cid)) {
            return array();
        }
        // 獲取排行榜資料
        $map['channel_category_id'] = $cid;
        $map['status'] = 1;
        $map['uid'] = array('EXP', 'IS NOT NULL');
        $data = D('channel')->field('`uid`, COUNT(`uid`) AS `count`')->where($map)->group('uid')->order('`count` DESC')->limit($limit)->findAll();
        // 獲取使用者資訊
        $userModel = model('User');
        foreach($data as &$value) {
            // 已使用快取，單個獲取
            $userInfo = $userModel->getUserInfo($value['uid']);
            !empty($userInfo) && $value = array_merge($value, $userInfo);
        }

        return $data;
    }

    /**
     * 獲取指定微博已經加入的頻道分類
     * @param integer $feedId 微博ID
     * @return array 已加入頻道的分類陣列
     */
    public function getSelectedChannels($feedId)
    {
        $map['feed_id'] = $feedId;
        $data = $this->where($map)->getAsFieldArray('channel_category_id');
        return $data;
    }

    /**
     * 添加頻道與微博的關聯資訊
     * @param integer $sourceId 微博ID
     * @param array $channelIds 頻道分類ID陣列
     * @param boolean $isAdmin 是否需要稽覈，默認為true
     * @return boolean 是否添加成功
     */
    public function setChannel($feedId, $channelIds, $isAdmin = true)
    {
        // 格式化資料
        !is_array($channelIds) && $channelIds = explode(',', $channelIds);
        // 檢驗資料
        if(empty($feedId)) {
            return false;
        }
        // 刪除微博的全部關聯
        $map['feed_id'] = $feedId;
        $res = $this->where($map)->delete();
        // 刪除成功
        if($res !== false) {
            $data['feed_id'] = $feedId;
            // 獲取圖片的高度與寬度
            $feedInfo = model('Feed')->get($feedId);
            if($feedInfo['type'] == 'postimage') {
                $feedData = unserialize($feedInfo['feed_data']);
                $imageAttachId = is_array($feedData['attach_id']) ? $feedData['attach_id'][0] : $feedData['attach_id'];
                $attach = model('Attach')->getAttachById($imageAttachId);
                $imageInfo = getImageInfo($attach['save_path'].$attach['save_name']);
                if($imageInfo !== false) {
                    $data['width'] = ceil($imageInfo[0]);
                    $data['height'] = ceil($imageInfo[1]);
                }
            }
            // 使用者UID
            $data['uid'] = $feedInfo['uid'];
            // 獲取後臺配置資料
            $channelConf = model('Xdata')->get('channel_Admin:index');
            $isAudit = ($channelConf['is_audit'] == 1) ? false : true;
            foreach($channelIds as $channelId) {
                $data['channel_category_id'] = $channelId;
                if($isAdmin) {
                    $data['status'] = 1;
                } else {
                    if($isAudit) {
                        $data['status'] = 0;
                    } else {
                        $data['status'] = 1;
                    }
                }
                $this->add($data);
            }
            return true;
        }

        return false;
    }

    /**
     * 獲取指定頻道分類下的相關資料 - 分頁資料
     * @param integer $cid 頻道分類ID
     * @return array 指定頻道分類下的相關資料
     */
    public function getDataWithCid($cid, $loadId, $limit = 20)
    {
        $map['status'] = $countmap['status'] = 1;
        !empty($cid) && $map['channel_category_id'] = $countmap['channel_category_id'] = $cid;
        $result = $this->where($countmap)->order('feed_channel_link_id DESC')->findPage($limit * 4);

        if($_REQUEST['newload']) {
            $loadId = $result['data'][0]['feed_channel_link_id'] - 1;
        }
        !empty($loadId) && $map['feed_channel_link_id'] = array('LT', $loadId);

        $data = $this->where($map)->order('feed_channel_link_id DESC')->limit($limit)->findAll();
        // 設定指定的寬高
        $data = $this->_formatImageSize($data);
        $result['data'] = $data;

        return $result;
    }

    /**
     * 格式化圖片的大小，使瀑布流圖片顯示正常
     * @param array $data 頻道資料陣列，包含寬高資料
     * @param integer $width 格式化後的寬度，默認225px
     * @return array 格式化寬高後的資料
     */
    private function _formatImageSize($data, $width = 225)
    {
        if(empty($data)) {
            return array();
        }
        foreach($data as &$value) {
            $value['height'] = ceil($width * $value['height'] / $value['width']);
            $value['width'] = $width;
        }

        return $data;
    }

    /**
     * 刪除微博與頻道的關聯
     * @param integer $feedId 微博ID
     * @return boolean 是否刪除成功
     */
    public function deleteChannelLink($feedId)
    {
        // 判斷參數
        if(empty($feedId)) {
            return false;
        }
        // 刪除資料
        $map['feed_id'] = intval($feedId);
        $result = $this->where($map)->delete();
        return (boolean)$result;
    }

    /**
     * 獲取指定使用者所繫結頻道分類的陣列
     * @param integer $uid 使用者ID
     * @return array 指定使用者所繫結頻道分類的陣列
     */
    public function getCategoryByUserBind($uid)
    {
        $extraHash = D('channel_category')->where('ext IS NOT NULL')->getHashList('channel_category_id', 'ext');
        $data = array();
        foreach($extraHash as $key => $val) {
            $extra = unserialize($val);
            if(!empty($extra['user_bind'])) {
                in_array($uid, explode(',', $extra['user_bind'])) && $data[] = $key;
}
}

return $data;
}

/**
 * 獲取指定話題所繫結頻道分類的陣列
 * @param array $topics 話題名稱陣列
 * @return array 指定話題所繫結頻道分類的陣列
 */
public function getCategoryByTopicBind($topics)
{
    $extraHash = D('channel_category')->where('ext IS NOT NULL')->getHashList('channel_category_id', 'ext');
    $data = array();
    foreach($extraHash as $key => $val) {
        $extra = unserialize($val);
        if(!empty($extra['topic_bind'])) {
            foreach($topics as $value) {
                in_array($value, explode(',', $extra['topic_bind'])) && $data[] = $key;
}
}
}
$data = array_unique($data);

return $data;
}

/**
 * 刪除分類關聯資訊
 * @param integer $cid 分類ID
 * @return boolean 是否刪除成功
 */
public function deleteAssociatedData ($cid) {
    if (empty($cid)) {
        return false;
}
// 刪除頻道分類下的資料
$map['channel_category_id'] = $cid;
$this->where($map)->delete();
// 刪除頻道關注下的資料
D('ChannelFollow', 'channel')->where($map)->delete();

return true;
}
}
