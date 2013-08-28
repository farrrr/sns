<?php
/**
 * 收藏模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class CollectionModel extends Model {

    protected $tableName = 'collection';
    protected $fields = array(0=>'collection_id',1=>'uid',2=>'source_id',3=>'source_table_name',4=>'source_app',5=>'ctime');

    /**
     * 添加收藏記錄
     * @param array $data 收藏相關資料
     * @return boolean 是否收藏成功
     */
    public function addCollection($data) {
        // 驗證資料
        if(empty($data['source_id']) || empty($data['source_table_name']) || empty($data['source_app'])) {
            $this->error = L('PUBLIC_RESOURCE_ERROR');          // 資源ID,資源所在表名,資源所在應用不能為空
            return false;
        }
        // 判斷是否已收藏
        $isExist = $this->getCollection($data['source_id'], $data['source_table_name']);
        if(!empty($isExist)) {
            $this->error = L('PUBLIC_FAVORITE_ALREADY');        // 您已經收藏過了
            return false;
        }

        $data['uid'] = !$data['uid'] ? $GLOBALS['ts']['mid'] : $data['uid'];
        if ( !$data['uid'] ){
            $this->error = '未登入收藏失敗';        // 收藏失敗
            return false;
        }
        $data['source_id'] = intval($data['source_id']);
        $data['source_table_name'] = t($data['source_table_name']);
        $data['source_app'] = t($data['source_app']);
        $data['ctime'] = time();
        if($data['collection_id'] = $this->add($data)) {
            // 生成快取
            model('Cache')->set('collect_'.$data['uid'].'_'.$data['source_table_name'].'_'.$data['source_id'], $data);
            model('Cache')->rm('coll_count_'.$data['source_table_name'].'_'.$data['source_id']);
            //添加積分
            model('Credit')->setUserCredit($data['uid'],'collect_weibo');
            $uid = model('Feed')->where('feed_id='.$data['source_id'])->getField('uid');
            model('Credit')->setUserCredit($uid,'collected_weibo');

            // 收藏數加1
            model('UserData')->updateKey('favorite_count', 1);
            return true;
        } else {
            $this->error = L('PUBLIC_FAVORITE_FAIL');       // 收藏失敗,您可能已經收藏此資源
            return false;
        }
    }

    /**
     * 返回指定資源的收藏數目
     * @param integer $sid 資源ID
     * @param string $stable 資源表名
     * @return integer 指定資源的收藏數目
     */
    public function getCollectionCount($sid, $stable) {
        if(($count = model('Cache')->get('coll_count_'.$stable.'_'.$sid)) === false) {
            $map['source_id'] = $sid;
            $map['source_table_name'] = $stable;
            $count = $this->where($map)->count();
            model('Cache')->set('coll_count_'.$stable.'_'.$sid, $count);
        }

        return $count;
    }

    /**
     * 獲取收藏列表
     * @param array $map 查詢條件
     * @param integer $limit 結果集顯示數目，默認為20
     * @param string $order 排序條件，默認為ctime DESC
     * @return array 收藏列表資料
     */
    public function getCollectionList($map, $limit = 20, $order = 'ctime DESC') {
        $list = $this->where($map)->order($order)->findPage($limit);
        foreach($list['data'] as &$v) {
            $sourceInfo = model('Source')->getSourceInfo($v['source_table_name'], $v['source_id'], false, $v['source_app']);
            $publish_time = array('publish_time'=>$sourceInfo['ctime']);
            switch($v['source_table_name']) {
            case 'feed':
                $data = model('Feed')->get($v['source_id']);
                $sourceData = array('source_data'=>$data);
                break;
            default:
                $sourceData = array('source_data'=>null);
                break;
            }
            $v = array_merge($sourceInfo, $v, $publish_time, $sourceData);
        }

        return $list;
    }

    /**
     * 獲取收藏的種類，用於收藏的Tab
     * @param array $map 查詢條件
     * @return array 收藏種類與其資源數目
     */
    public function getCollTab($map) {
        $list = $this->field('COUNT(1) AS `nums`, `source_table_name`')->where($map)->group('source_table_name')->getHashList('source_table_name', 'nums');
        return $list;
    }

    /**
     * 獲取指定收藏的資訊
     * @param integer $sid 資源ID
     * @param string $stable 資源表名稱
     * @param integer $uid 使用者UID
     * @return array 指定收藏的資訊
     */
    public function getCollection($sid, $stable, $uid = '') {
        // 驗證資料
        if(empty($sid) || empty($stable)) {
            $this->error = L('PUBLIC_WRONG_DATA');      // 錯誤的參數
            return false;
        }

        empty($uid) && $uid = $GLOBALS['ts']['mid'];
        // 獲取收藏資訊
        if(($cache = model('Cache')->get('collect_'.$uid.'_'.$stable.'_'.$sid) ) === false) {
            $map['source_table_name'] = $stable;
            $map['source_id'] = $sid;
            $map['uid'] = $uid;
            $cache = $this->where($map)->find();
            model('Cache')->set('collect_'.$uid.'_'.$stable.'_'.$sid,$cache);
        }

        return $cache;
    }

    /**
     * 取消收藏
     * @param integer $sid 資源ID
     * @param string $stable 資源表名稱
     * @param integer $uid 使用者UID
     * @return boolean 是否取消收藏成功
     */
    public function delCollection($sid, $stable, $uid = '') {
        // 驗證資料
        if(empty($sid) || empty($stable)) {
            $this->error = L('PUBLIC_WRONG_DATA');      // 錯誤的參數
            return false;
        }

        $uid = empty($uid) ? $GLOBALS['ts']['mid'] : $uid;
        $map['uid'] = $uid;
        $map['source_id'] = $sid;
        $map['source_table_name'] = $stable;
        // 取消收藏操作
        if($this->where( $map )->delete()){
            // 設定快取
            model('Cache')->set('collect_'.$uid.'_'.$stable.'_'.$sid, '');
            model('Cache')->rm('coll_count_'.$stable.'_'.$sid);
            // 收藏數減1
            model('UserData')->updateKey('favorite_count', -1);
            return true;
        } else {
            $this->error = L('PUBLIC_CANCEL_FAVORITE_FAIL');        // 取消失敗,您可能已經取消了該資訊的收藏
            return false;
        }
        }

        /*** API使用 ***/
        /**
         * 獲取收藏列表，API使用
         * @param integer $uid 使用者UID
         * @param integer $since_id 主鍵起始ID，默認為0
         * @param integer $max_id 主鍵最大ID，默認為0
         * @param integer $limit 每頁結果集數目，默認為20
         * @param integer $page 頁數，默認為1
         * @return array 收藏列表資料
         */
        public function getCollectionForApi($uid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1) {
            $since_id = intval($since_id);
            $max_id = intval($max_id);
            $limit = intval($limit);
            $page = intval($page);
            $where = " uid = {$uid} ";
            if(!empty($since_id) || !empty($max_id)) {
                !empty($since_id) && $where .= " AND collection_id > {$since_id}";
                !empty($max_id) && $where .= " AND collection_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $list = $this->where($where)->limit("$start, $end")->order('collection_id DESC')->findAll();
        foreach($list as &$v) {
            $sourceInfo = model('Source')->getSourceInfo($v['source_table_name'], $v['source_id'], true, $v['source_app']);
            $v = array_merge($sourceInfo, $v);
        }

        return $list;
        }

        /**
         * 獲取動態（微博）收藏列表，API使用
         * @param integer $uid 使用者UID
         * @param integer $since_id 主鍵起始ID，默認為0
         * @param integer $max_id 主鍵最大ID，默認為0
         * @param integer $limit 每頁結果集數目，默認為20
         * @param integer $page 頁數，默認為1
         * @return array 收藏列表資料
         */
        public function getCollectionFeedForApi($uid, $since_id = 0, $max_id = 0, $limit = 20, $page = 1) {
            $since_id = intval($since_id);
            $max_id = intval($max_id);
            $limit = intval($limit);
            $page = intval($page);
            $where = " uid = {$uid} AND source_table_name ='feed' ";
            if(!empty($since_id) || !empty($max_id)) {
                !empty($since_id) && $where .= " AND source_id > {$since_id}";
                !empty($max_id) && $where .= " AND source_id < {$max_id}";
        }
        $start = ($page - 1) * $limit;
        $end = $limit;
        $feed_ids = $this->where($where)->limit("$start, $end")->order('collection_id DESC')->field('source_id')->getAsFieldArray('source_id');
        $list = model('Feed')->formatFeed($feed_ids, true);

        return $list;
        }

        }
