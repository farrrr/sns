<?php
/**
 * 舉報模型 - 資料物件模型
 * @author JunStar <wangjuncheng@zhishisoft.com>
 * @version TS3.0
 */
class DenounceModel extends Model {

    protected $tableName = 'denounce';
    protected $fields = array(0=>'id',1=>'from',2=>'aid',3=>'state',4=>'uid',5=>'fuid',6=>'reason',7=>'content',8=>'ctime',9=>'source_url');

    /**
     * 獲取相應類型的舉報列表
     * @param array $map 查詢條件
     * @return array 相應類型的舉報列表
     */
    public function getFromList($map) {
        $list = $this->where( $map )->order('id DESC')->findPage();
        foreach($list['data'] as &$v) {
            $v['source_url'] = str_replace('[SITE_URL]', SITE_URL, $v['source_url']);
        }

        return $list;
    }

    /**
     * 徹底刪除舉報資訊
     * @param array $ids 被舉報的資源ID
     * @return mix 刪除失敗返回false，成功返回刪除的資源ID
     */
    public function deleteDenounce($ids, $state)
    {
        $weiboIds = $this->_getWeiboIdsByDenounce($ids);
        $weibo_map['feed_id'] = array('IN', $weiboIds);
        $weibo_set = model('Feed')->where($weibo_map)->save(array('is_del'=>1));
        if($state == 0) {
            $result = $this->where($this->_paramMaps($ids))->save(array('state'=>'1'));
        } else if($state == 1) {
            $result = $this->where($this->_paramMaps($ids))->delete();
        }

        return $result;
    }

    /**
     * 舉報內容，稽覈通過
     * @param array $ids 被舉報的資源ID
     * @return mix 稽覈失敗返回false，成功返回審核的資源ID
     */
    public function reviewDenounce($ids)
    {
        $weiboIds = $this->_getWeiboIdsByDenounce($ids);
        $weibo_map['feed_id'] = array('IN', $weiboIds);
        $weibo_set = model('Feed')->where($weibo_map)->save(array('is_del'=>0));
        // 刪除舉報資訊
        $result = $this->where($this->_paramMaps($ids))->delete();
        return $result;
    }

    /**
     * 添加舉報資訊
     * @param $id 舉報的資源ID
     * @param integer $uid 舉報使用者ID
     * @param string $content 舉報附加內容
     * @param string $type 舉報資源類型
     * @return mix 添加失敗返回false，成功返回新添加的舉報ID
     */
    public function autoDenounce($id, $uid, $content, $type = 'feed') {
        $map['from'] = 'weibo';
        $map['aid'] = $id;
        $map['uid'] = '0';
        $map['fuid'] = $uid;
        $map['content'] = $content;
        $map['reason'] = '';
        $map['ctime'] = time();
        $map['state'] = '1';
        $weibo_map['feed_id'] = $id;
        model('Feed')->where($weibo_map)->save(array('is_del'=>1));
        return $this->add($map);
    }

    /**
     * 獲取指定資源已經被舉報且進入回收站的資源ID
     * @param string $from 資源類型
     * @param string $type 是輸出陣列還是字元串，默認為字元串
     * @return array|string 回收站中的舉報資源ID
     */
    public function getIdsDenounce($from, $type = '') {
        $map['from'] = $from;
        $map['state'] = '1';
        $ids = getSubByKey($this->where( $map )->field('aid')->findAll(), 'aid');
        empty($type) && $ids = implode(',', $ids);
        return $ids;
    }

    /**
     * 獲取被舉報的微博ID
     * @param array $ids 舉報ID陣列
     * @return array 被舉報的微博ID
     */
    private function _getWeiboIdsByDenounce($ids){
        $data = $this->where($this->_paramMaps($ids))->field('aid')->findAll();
        $weibo_id = getSubByKey($data,'aid');
        return $weibo_id;
    }

    /**
     * 格式化，資源ID資料
     * @param string|array $ids 資源ID資料
     * @return array 格式化後的資源ID資料
     */
    private function _paramMaps($ids) {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        if(empty($ids)) {
            return false;
        }
        $map['id'] = array('IN', $ids);

        return $map;
    }
}
