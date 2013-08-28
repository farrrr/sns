<?php
/**
 * 頻道關注模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class ChannelFollowModel extends Model
{
    protected $tableName = 'channel_follow';

    /**
     * 獲取指定分類的關注數目
     * @param integer $cid 頻道分類ID
     * @return integer 指定分類的關注數目
     */
    public function getFollowingCount($cid)
    {
        !empty($cid) && $map['channel_category_id'] = intval($cid);
        $count = $this->where($map)->count();

        return $count;
    }

    /**
     * 更新頻道的關注狀態
     * @param integer $uid 關注使用者ID
     * @param integer $cid 頻道分類ID
     * @param string $type 更新頻道操作，add or del
     * @return boolean 更新頻道關注狀態是否成功
     */
    public function upFollow($uid, $cid, $type)
    {
        // 驗證資料的正確性
        if(empty($uid) || empty($cid)) {
            return false;
        }
        $result = false;
        // 更新狀態修改
        switch($type) {
        case 'add':
            // 驗證是否已經添加關注
            $map['uid'] = $uid;
            $map['channel_category_id'] = $cid;
            $isExist = $this->where($map)->count();
            if($isExist == 0) {
                $data['uid'] = $uid;
                $data['channel_category_id'] = $cid;
                $result = $this->add($data);
                $result = (boolean)$result;
            }
            break;
        case 'del':
            $map['uid'] = $uid;
            $map['channel_category_id'] = $cid;
            $result = $this->where($map)->delete();
            $result = (boolean)$result;
            break;
        }

        return $result;
    }

    /**
     * 獲取指定使用者與指定頻道分類的關注狀態
     * @param integer $uid 使用者ID
     * @param integer $cid 頻道分類ID
     * @return boolean 返回是否關注
     */
    public function getFollowStatus($uid, $cid)
    {
        $map['uid'] = $uid;
        $map['channel_category_id'] = $cid;
        $count = $this->where($map)->count();
        $result = ($count == 0) ? false : true;

        return $result;
    }

    /**
     * 獲取指定使用者的關注列表
     * @param integer $uid 指定使用者ID
     * @return array 指定使用者的關注列表
     */
    public function getFollowList($uid)
    {
        if(empty($uid)) {
            return array();
        }
        $map['f.uid'] = $uid;
        $list = D()->table("`".C('DB_PREFIX')."channel_follow` AS f LEFT JOIN `".C('DB_PREFIX')."channel_category` AS c ON f.channel_category_id=c.channel_category_id")
            ->field('c.`channel_category_id`, c.`title`')
            ->where($map)
            ->findAll();

        return $list;
    }

    /**
     * 獲取指定使用者所關注頻道的所有微博，默認為當前登入使用者
     * @param string $where 查詢條件
     * @param integer $limit 結果集數目，默認為10
     * @param integer $uid 指定使用者ID，默認為空
     * @param integer $fgid 關注頻道ID，默認為空
     * @return array 指定使用者所關注頻道的所有微博，默認為當前登入使用者
     */
    public function getFollowingFeed($where = '', $limit = 10, $uid = '', $fgid = '')
    {
        $buid = empty($uid) ? $GLOBALS['ts']['mid'] : $uid;
        $where .= " AND b.uid=".$buid;
        $where .= " AND a.status=1";
        $table = "`{$this->tablePrefix}channel` AS a LEFT JOIN `{$this->tablePrefix}channel_follow` AS b ON a.channel_category_id = b.channel_category_id LEFT JOIN `{$this->tablePrefix}feed` AS c ON a.feed_id = c.feed_id";
        !empty($fgid) && $where .= ' AND b.channel_category_id = '.$fgid;
        $feedList = D()->table($table)->field('a.feed_id')->where($where)->order('c.publish_time DESC')->findPage($limit);
        $feedIds = getSubByKey($feedList['data'], 'feed_id');
        $feedList['data'] = model('Feed')->getFeeds($feedIds);

        return $feedList;
    }
}
