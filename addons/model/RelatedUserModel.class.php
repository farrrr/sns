<?php
/**
 * 感興趣的人模型 - 業務邏輯模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class RelatedUserModel extends Model {

    private $_uid = 0;                  // 查詢使用者ID
    private $_exclude_uids = array();   // 排除使用者ID陣列
    private $_user_model;               // 使用者模型物件
    private $_user_follow;              // 使用者關注物件
    private $_error = '';               // 存儲最後的錯誤資訊
    private $user_sql_where = ' and is_active=1 and is_audit=1 and is_init=1 ';  //過濾掉未啟用，未稽覈，未初始化的使用者

    /**
     * 初始化
     */
    public function _initialize() {
        $this->setUid($GLOBALS['ts']['mid']);
        $this->_user_model = model('User');
        $this->_user_follow = model('Follow');
    }

    /**
     * 設定關聯使用者
     * @param integer $uid 使用者ID
     * @return void
     */
    public function setUid($uid) {
        $uid = intval($uid);
        $uid < 0 && $uid = $GLOBALS['ts']['mid'];
        $this->_uid = $uid;
    }

    /**
     * 獲取最後的錯誤資訊
     * @return string 最後的錯誤資訊
     */
    public function getLastError() {
        return $this->_error;
    }

    /**
     * 可能感興趣的人
     * @example
     * 1.一週以內新註冊的使用者，最新註冊使用者推薦
     * 2.好友的好友推薦，XX（我關注的人）也關注了TA
     * 3.有共同好友的使用者推薦，A，B（我關注的人）都關注了TA
     * 4.有共同好友的使用者推薦，有X個共同好友（我與這個人又XX個共同關組） - 未完成
     * 5.職業資訊推薦，TA跟你的職業資訊相同
     * 6.地區資訊推薦，TA與你在同一個地方，只實現三級匹配
     * 7.隨機推薦
     * @param integer $show 顯示個數，默認為4
     * @param integer $limit 查詢快取個數，默認為100
     * @return array 可能感興趣的人陣列
     */
    public function getRelatedUser($show = 4, $limit = 100) {
        // 獲取100個使用者的快取
        $relatedUseInfo = model('Cache')->get('related_user_'.$GLOBALS['ts']['mid']);
        if(empty($relatedUseInfo)) {
            //過濾掉當前顯示的使用者
            if ( $show == 1 ){
                $nowrelated = $_SESSION['now_related_'.$GLOBALS['ts']['mid']];
                $this->_getExcludeUids( $nowrelated );
            }
            // 添加查詢使用者ID
            $this->_getExcludeUids(array($this->_uid));
            $fids = $this->_user_follow->where('uid='.$this->_uid)->getAsFieldArray('fid');
            //過濾掉未啟用，未稽覈，未初始化的使用者
            $notin = model('User')->where('is_active=0 or is_audit=0 or is_init=0')->field('uid')->findAll();
            $notinids = getSubByKey(  $notin , 'uid');
            $this->_getExcludeUids($notinids);
            $this->_getExcludeUids($fids);
            // 使用者關聯資訊
            $relatedUseInfo = array();
            // 獲取使用者權重
            $weightsNum['following'] = 6;
            $weightsNum['friend'] = 5;
            $weightsNum['city'] = 4;
            $weightsNum['tag'] = 3;
            $weightsNum['new'] = 2;
            $weightsNum['random'] = 1;
            // 權重比例
            $weightsSum = array_sum($weightsNum);
            // 好友的共同好友
            $nums = ceil($limit * $weightsNum['following'] / $weightsSum);
            $relatedUseInfo = $this->_getRelatedUserFromFollowing($nums);
            // 關注的人
            $nums = ceil($limit * $weightsNum['friend'] / $weightsSum);
            $data = $this->_getRelatedUserFromFriend($nums,$limit);
            !empty($data) && $relatedUseInfo = array_merge($relatedUseInfo, $data);
            // 城市相同
            $nums = ceil($limit * $weightsNum['city'] / $weightsSum);
            $data = $this->_getRelatedUserFromCity($nums,$limit);
            !empty($data) && $relatedUseInfo = array_merge($relatedUseInfo, $data);
            // 工作相同
            $nums = ceil($limit * $weightsNum['tag'] / $weightsSum);
            $data = $this->_getRelatedUserFromTag($nums,$limit);
            !empty($data) && $relatedUseInfo = array_merge($relatedUseInfo, $data);
            // 新註冊使用者
            $nums = ceil($limit * $weightsNum['new'] / $weightsSum);
            $data = $this->_getRelatedUserFromNew($nums,$limit);
            !empty($data) && $relatedUseInfo = array_merge($relatedUseInfo, $data);
            // 隨機使用者
            $nums = $limit - count($relatedUseInfo);
            $data = $this->_getRelatedUserFromRandom($nums,$limit);
            !empty($data) && $relatedUseInfo = array_merge($relatedUseInfo, $data);
            // 添加快取
            model('Cache')->set('related_user_'.$GLOBALS['ts']['mid'], $relatedUseInfo, 24 * 60 * 60);
        }

        srand((float)microtime() * 1000000);
        shuffle($relatedUseInfo);
        $relatedUseInfo = array_slice($relatedUseInfo, 0, $show);
        $nowshow = getSubByKey( getSubByKey( $relatedUseInfo , 'userInfo' ) , 'uid' );
        //將當前顯示的使用者存入SESSION
        if ( $show == 1 ){
            $sessionshow = array_merge( $_SESSION['now_related_'.$GLOBALS['ts']['mid']] , $nowshow );
            $_SESSION['now_related_'.$GLOBALS['ts']['mid']] = $sessionshow;
        } else {
            $_SESSION['now_related_'.$GLOBALS['ts']['mid']] = $nowshow;
        }
        return $relatedUseInfo;
        }

        /**
         * 獲取指定類型的關聯使用者
         * @param string $type 類型字元串
         * @param integer $limit 顯示個數
         * @return array 指定類型的關聯使用者
         */
        public function getRelatedUserByType($type, $limit){
            // 添加查詢使用者ID
            $this->_getExcludeUids(array($this->_uid));
            //過濾掉未啟用，未稽覈，未初始化的使用者
            $notin = model('User')->where('is_active=0 or is_audit=0 or is_init=0')->field('uid')->findAll();
            $notinids = getSubByKey(  $notin , 'uid');
            $this->_getExcludeUids($notinids);
            $fids = $this->_user_follow->where('uid='.$this->_uid)->getAsFieldArray('fid');
            $this->_getExcludeUids($fids);
            // 使用者關聯資訊
            $relatedUseInfo = array();
            for($i = 0; $i < $limit; $i++) {
                switch($type) {
                case 1:
                    $data = $this->_getRelatedUserFromNew();
                    break;
                    case 2;
                    $data = $this->_getRelatedUserFromFriend();
                    break;
                case 3:
                    $data = $this->_getRelatedUserFromCity();
                    break;
                case 4:
                    $data = $this->_getRelatedUserFromTag();
                    break;
                case 5:
                    $data = $this->_getRelatedUserFromRecommend();
                    break;
        }
        $relatedUseInfo = array_merge($relatedUseInfo, $data);
        }

        return $relatedUseInfo;
        }

        /**
         * 註冊使用者推薦
         * @param integer $limit 查詢使用者個數，默認為20
         * @return array 推薦使用者ID陣列
         */
        public function getRelatedUserWithLogin($limit = 20) {
            $this->_getExcludeUids(array($this->_uid));
            $fids = $this->_user_follow->where('uid='.$this->_uid)->getAsFieldArray('fid');
            !empty($fids) && $this->_getExcludeUids($fids);
            // 使用者ID
            $relatedUids = array();
            for($i = 0; $i < $limit; $i++) {
                $random = rand(1, 4);
                switch($random) {
                case 1:
                    $data = $this->_getRelatedUserFromNew();
                    break;
                case 2:
                    $data = $this->_getRelatedUserFromCity();
                    break;
                case 3:
                    $data = $this->_getRelatedUserFromTag();
                    break;
                case 4:
                    $data = $this->_getRelatedUserFromRandom();
                    break;
        }
        $relatedUids = array_merge($relatedUids, $data);
        }
        // 使用者結果集合
        $result = array();
        foreach($relatedUids as $value) {
            $result[] = $value['userInfo']['uid'];
        }
        return $result;
        }

        /**
         * 設定排除使用者ID
         * @param array $uids 排除使用者ID陣列
         * @return array 排除使用者ID
         */
        private function _getExcludeUids($uids = array()) {
            if(!empty($uids)) {
                $this->_exclude_uids = array_merge($this->_exclude_uids, $uids);
                $this->_exclude_uids = array_filter($this->_exclude_uids);
                $this->_exclude_uids = array_unique($this->_exclude_uids);
        }
        if(empty($this->_exclude_uids)){
            $this->_exclude_uids = array(0);
        }
        }

        /**
         * 新註冊使用者推薦
         * @param integer $limit 查詢個數，默認為1
         * @return array 新註冊使用者資訊
         */
        private function _getRelatedUserFromNew($num = 1 , $limit = 100) {
            $time = time() - mktime(0, 0, 0, 0, 7, 0) + mktime(0,0,0,0,0,0);
            // 隨機查詢語句
            $limit = $limit * 10;
            $sql = "SELECT `uid` FROM `{$this->tablePrefix}user` WHERE `ctime` > $time AND `uid` NOT IN (".implode(',', $this->_exclude_uids).") LIMIT {$limit}";
            $data = D()->query($sql);
            $data = getSubByKey($data, 'uid');
            $data && $data = $this->_data_array_rand( $data , $num );
            if(empty($data)) {
                return array();
        }
        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);
        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        $data[$key]['info']['msg'] = '最新註冊使用者推薦';
        $data[$key]['info']['extendMsg'] = '';
        }

        return $data;
        }

        /**
         * 好友的好友使用者推薦
         * @param integer $limit 查詢個數，默認為1
         * @return array 好友的好友使用者資訊
         */
        private function _getRelatedUserFromFriend($num = 1,$limit = 100) {
            // 獲取我的好友
            $friendUids = $this->_user_follow->getFriendsData($this->_uid);
            $friendUids = getSubByKey($friendUids, 'fid');
            if(empty($friendUids)) {
                return array();
        }
        // 獲取好友關注的使用者
        $limit = $limit * 10;
        // 獲取好友關注的使用者 TODO 待過濾不合格的使用者
        $sql = "SELECT `uid`, `fid` FROM `{$this->tablePrefix}user_follow` WHERE `uid` IN (".implode(',', $friendUids).") AND fid NOT IN (".implode(',', $this->_exclude_uids).") LIMIT {$limit}";
        $friendData = D()->query($sql);
        $data = getSubByKey($friendData, 'fid');
        $data = array_unique($data);
        $data && $data = $this->_data_array_rand( $data , $num );

        if(empty($data)) {
            return array();
        }
        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);
        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        // 獲取相關使用者
        $relatedUids = array();
        foreach($friendData as $val) {
            if($val['fid'] == $value) {
                $relatedUids[] = $val['uid'];
        }
        }
        $relatedInfos = $this->_user_model->getUserInfoByUids($relatedUids);
        $relatedInfos = getSubByKey($relatedInfos, 'uname');
        $data[$key]['info']['msg'] = $relatedInfos[0].'也關注了TA';
        $data[$key]['extendMsg'] = '';
        }

        return $data;
        }

        /**
         * 獲取有共同好友的使用者推薦
         * @param integer $limit 查詢個人，默認為1
         * @return array 有共同好友的使用者推薦
         */
        public function _getRelatedUserFromFollowing($limit = 2) {
            // 獲取使用者的關組使用者
            $followFids = $this->_user_follow->where('uid='.$this->_uid)->getAsFieldArray('fid');
            // 獲取關注使用者所關注的使用者
            if(empty($followFids)) {
                return array();
        }
        // 獲取有共同好友的使用者推薦  TODO 待過濾不合格的使用者
        $sql = "SELECT `uid`, `fid` FROM `{$this->tablePrefix}user_follow` WHERE `uid` IN (".implode(',', $followFids).") AND fid NOT IN (".implode(',', $this->_exclude_uids).")";
        $followData = D()->query($sql);
        $fids = getSubByKey($followData, 'fid');
        $count = array_count_values($fids);
        // 推薦使用者篩選
        foreach($count as $key => $value) {
            if($value < 2) {
                unset($count[$key]);
        }
        }
        if(empty($count)) {
            return array();
        }
        if(count($count) > $limit) {
            $data = $this->_data_array_rand($count, $limit);
            count($data) == 1 && $data = array($data);
        } else {
            $data = array_keys($count);
        }
        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);
        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        // 獲取相關使用者
        $relatedUids = array();
        foreach($followData as $val) {
            if($val['fid'] == $value) {
                $relatedUids[] = $val['uid'];
        }
        }
        $relatedInfos = $this->_user_model->getUserInfoByUids($relatedUids);
        $relatedInfos = getSubByKey($relatedInfos, 'space_link_no');
        $relatedInfos = array_slice($relatedInfos, 0, 2);
        $data[$key]['info']['msg'] = '好友的共同好友推薦';
        $data[$key]['info']['extendMsg'] = implode('，', $relatedInfos).'也關注了TA';
        }

        return $data;
        }

        /**
         * 獲取相同的使用者標籤使用者
         * @param integer $limit 查詢個數，默認為1
         * @return array 相同的使用者標籤使用者資料
         */
        private function _getRelatedUserFromTag($num = 1 , $limit = 100) {

            // 獲取使用者的標籤資訊
            $maps['app'] = 'public';
            $maps['table'] = 'user';
            $maps['row_id'] = $this->_uid;
            $tagInfo = D('app_tag')->where($maps)->findAll();
            $tagIds = getSubByKey($tagInfo, 'tag_id');
            if(empty($tagIds)) {
                return array();
        }

        // 獲取具有相同標籤資訊的使用者
        $limit = $limit * 10;
        $sql = "SELECT `row_id`, `tag_id` FROM `{$this->tablePrefix}app_tag` AS a WHERE `tag_id` IN (".implode(',', $tagIds).") AND `row_id` NOT IN (".implode(',', $this->_exclude_uids).") group by `row_id` LIMIT {$limit}";
        $tagData = D()->query($sql);
        // Tag Hash陣列
        $tagHash = array();
        foreach($tagData as $tag) {
            $tagHash[$tag['row_id']] = $tag['tag_id'];
        }
        $data = getSubByKey($tagData, 'row_id');
        $data = array_unique($data);
        $data && $data = $this->_data_array_rand( $data , $num );
        if(empty($data)) {
            return array();
        }

        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);

        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        // 獲取標籤資訊
        $tag_id = 0;
        foreach($tagData as $val) {
            if($val['row_id'] == $value) {
                $tag_id = $tagHash[$val['row_id']];
                break;
        }
        }
        $tagName = model('Tag')->where('tag_id='.$tag_id)->getField('name');
        $data[$key]['info']['msg'] = 'TA跟你的標籤資訊相同';
        $data[$key]['info']['extendMsg'] = '你們都選擇了'.$tagName;
        $data[$key]['uid'] = $value;

        }

        return $data;
        }

        /**
         * 獲取相同地區的使用者
         * @param integer $limit 查詢個數，默認為1
         * @return array 相同地區的使用者資料
         */
        private function _getRelatedUserFromCity($num = 1 , $limit = 100) {
            // 獲取使用者的區資訊
            $areaInfo = $this->_user_model->field('city, area')->where('uid='.$this->_uid)->find();
            $areaId = $areaInfo['area'];
            // 獲取地區資訊
            if(empty($areaId)) {
                return array();
        }
        // 獲取相同地區的使用者
        $limit = $limit * 10;
        $sql = "SELECT `uid` FROM `{$this->tablePrefix}user` AS a WHERE `area` = {$areaId} AND `uid` NOT IN (".implode(',', $this->_exclude_uids).") ".$this->user_sql_where." LIMIT {$limit}";
        $data = D()->query($sql);
        $data = getSubByKey($data, 'uid');
        $data && $data = $this->_data_array_rand( $data , $num );
        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);
        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        // 獲取地區資訊
        $map['area_id'] = array('IN', array($areaInfo['city'], $areaInfo['area']));
        $areaName = model('Area')->where($map)->getAsFieldArray('title');
        $areaName = implode(' ', $areaName);
        $data[$key]['info']['msg'] = 'TA與你在同一個地方';
        $data[$key]['info']['extendMsg'] = '你們都在'.$areaName;
        $data[$key]['uid'] = $value;
        }

        return $data;
        }

        /**
         * 獲取後臺推薦使用者
         * @param integer $limit 查詢個數，默認為1
         * @return array 相同的使用者標籤使用者資料
         */
        private function _getRelatedUserFromRecommend($num = 1,$limit = 100) {

            // 獲取使用者的區資訊
            $RegisterConfig = model('Xdata')->get('admin_Config:register');
            $recommendUids = $RegisterConfig['interester_recommend'];
            // 獲取地區資訊
            if(empty($recommendUids)) {
                return array();
        }
        // 獲取相同地區的使用者
        $limit = $limit * 10;
        $sql = "SELECT `uid` FROM `{$this->tablePrefix}user` WHERE `uid` IN (".$recommendUids.")
            AND `uid` NOT IN (".implode(',', $this->_exclude_uids).") ".$this->user_sql_where." LIMIT {$limit}";
        $data = D()->query($sql);
        //return $data;exit;
        $data = getSubByKey($data, 'uid');
        $data & $data = $this->_data_array_rand( $data , $num );
        // 使用者基本資訊
        $userInfos = $this->_user_model->getUserInfoByUids($data);
        // 使用者關注狀態
        $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
        // 設定去除使用者
        $this->_getExcludeUids($data);
        foreach($data as $key => $value) {
            if ( !$userInfos[$value] ){
                unset( $data[$key] );
                continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        $data[$key]['info']['msg'] = '後臺推薦使用者';
        $data[$key]['uid'] = $value;
        }

        return $data;
        }

        /**
         * 獲取隨機使用者
         * @param integer $limit 查詢個數，默認為1
         * @return array 隨機使用者資訊
         */
        private function _getRelatedUserFromRandom($num = 1 , $limit = 100 ) {
            // 獲取隨機使用者
            $limit = $limit * 10;
            $sql = "SELECT `uid` FROM `{$this->tablePrefix}user` AS a WHERE `uid` NOT IN (".implode(',', $this->_exclude_uids).") LIMIT {$limit}";
            $data = D()->query($sql);
            $data = getSubByKey($data, 'uid');
            $data && $this->_data_array_rand( $data , $num );
            // 使用者基本資訊
            $userInfos = $this->_user_model->getUserInfoByUids($data);
            // 使用者關注狀態
            $userStates = $this->_user_follow->getFollowStateByFids($this->_uid, $data);
            // 設定去除使用者
            $this->_getExcludeUids($data);
            foreach($data as $key => $value) {
                if ( !$userInfos[$value] ){
                    unset( $data[$key] );
                    continue;
        }
        $data[$key] = array('userInfo'=>$userInfos[$value]);
        $data[$key]['followState'] = $userStates[$value];
        $data[$key]['info']['msg'] = '系統推薦';
        $data[$key]['info']['extendMsg'] = '';
        }

        return $data;
        }
        private function _data_array_rand($data, $num){
            shuffle($data);
            return array_slice($data, 0, $num);
        }
        }
