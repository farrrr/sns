<?php
/**
 * 找人模型 - 業務邏輯模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class PeopleModel extends model
{
    /**
     * 通過條件查詢相應的使用者資訊
     * @param array $data 相應的查詢條件
     * @param string $type 查詢類型
     * @return array 相應的使用者資訊
     */
    public function getPeople($data, $type)
    {
        // 設定查詢條件
        $list = array();
        switch($type) {
        case 'tag':
            $list = model('UserCategory')->getUidsByCid($data['cid'], $authenticate ,$limit = 30);
            break;
        case 'area':
            $list = $this->_getFilterData($data);
            break;
        case 'verify':
            $list = $this->_getVerifyData($data);
            break;
        case 'official':
            $list = $this->_getOfficialData($data);
            break;
        }
        // 獲取使用者ID
        $uids = getSubByKey($list['data'], 'uid');
        // 使用者資料資訊組裝
        $list['data'] = $this->getUserInfos($uids, $list['data']);

        return $list;
    }

    /**
     * 獲取篩選使用者資料列表
     * @param array $data 篩選相關條件
     * @param string $field 欄位資料
     * @param string $order 排序資料
     * @param integer $page 分頁個數
     * @return array 篩選使用者資料列表
     */
    private function _getFilterData($data, $field = 'u.uid', $order = 'u.uid DESC', $page = 30)
    {
        // 設定查詢條件
        $map['u.is_init'] = 1;
        $map['u.is_del'] = 0;
        // 設定表名
        $table = '`'.C('DB_PREFIX').'user` AS u';
        if(!empty($data['cid'])) {
            $tagInfo = model('UserCategory')->where('user_category_id='.intval($data['cid']))->find();

            if($tagInfo['pid'] == 0){
                $tags = model('UserCategory')->where('pid='.$tagInfo['user_category_id'])->findAll();

                foreach($tags as $k=>$v){
                    $tag_id = D('tag')->where(array('name'=>t($v['title'])))->getField('tag_id');
                    if($tag_id){
                        $tagId[] = $tag_id;
                        unset($tag_id);
                    }
                }
                $maps['tag_id'] = array('in',$tagId);
            }else{
                $tagId = D('tag')->where(array('name'=>t($tagInfo['title'])))->getField('tag_id');
                $maps['tag_id'] = $tagId;
            }
            //dump($tagId);exit;
            $maps['app'] = 'public';
            $maps['table'] = 'user';
            $tag_user = D('app_tag')->where($maps)->findAll();
            $map['uid'] = array('in',getSubByKey($tag_user,'row_id'));
            // $table .= ' LEFT JOIN `'.C('DB_PREFIX').'user_category_link` AS c ON u.uid = c.uid';
            // // 若是第一級 TODO
            // $categoryInfo = model('UserCategory')->where('user_category_id='.intval($data['cid']))->find();
            // if($categoryInfo['pid'] == 0) {
            //  $cids[] = intval($data['cid']);
            //  $childCids = model('UserCategory')->where('pid='.intval($data['cid']))->getAsFieldArray('user_category_id');
            //  $cids = array_merge($cids, $childCids);
            //  $map['c.user_category_id'] = array('IN', $cids);
            // } else {
            //  $map['c.user_category_id'] = intval($data['cid']);
            // }
        }
        if(!empty($data['verify'])) {
            $map['v.verified'] = 1;
            $table .= ' LEFT JOIN `'.C('DB_PREFIX').'user_verified` AS v ON u.uid = v.uid';
            $data['verify'] == 1 && $map['v.id'] = array('EXP', 'IS NOT NULL');
            $data['verify'] == 2 && $map['v.id'] = array('EXP', 'IS NULL');
        }
        // 搜索地區條件判斷
        $pid1 = model('Area')->where('area_id='.$data['area'])->getField('pid');
        $level = 1;
        if($pid1 != 0){
            $level = $level +1;
            $pid2 = model('Area')->where('area_id='.$pid1)->getField('pid');
            if($pid2 != 0){
                $level = $level +1;
            }
        }
        switch ($level) {
        case '1':
            !empty($data['area']) && $map['province'] = intval($data['area']);
            break;
        case '2':
            !empty($data['area']) && $map['city'] = intval($data['area']);
            break;
        case '3':
            !empty($data['area']) && $map['area'] = intval($data['area']);
            break;

        default:
            # code...
            break;
        }

        !empty($data['sex']) && $map['sex'] = intval($data['sex']);

        $list = D()->table($table)->field($field)->where($map)->order($order)->findPage($page);

        return $list;
    }

    /**
     * 獲取篩選認證用資料列表
     * @param array $data 篩選相關條件
     * @param string $field 欄位資料
     * @param string $order 排序資料
     * @param integer $page 分頁個數
     * @return array 篩選認證用資料列表
     */
    public function _getVerifyData($data, $field = 'u.uid, v.info', $order = 'u.uid DESC', $page = 30)
    {
        // 設定表明
        $table = '`'.C('DB_PREFIX').'user_verified` AS v LEFT JOIN `'.C('DB_PREFIX').'user` AS u ON u.uid = v.uid';
        if($data['cid']){
            if($data['pid']){
                $maps['user_verified_category_id'] = array('in', getSubByKey(D('user_verified_category')->where('user_verified_category_id='.$data['cid'])->findAll(),'user_verified_category_id'));
                $maps['verified'] = 1;
                if($data['uids']){
                    $maps['uid'] = array('EXP', 'NOT IN ('.$data['uids'].')');  //排除置頂使用者
                }
                $map['u.uid'] = array('in', getSubByKey(D('user_verified')->where($maps)->field('uid')->findAll(),'uid'));
            }else{
                $map['u.uid'] = array('in', getSubByKey(D('user_verified')->where('verified=1 AND usergroup_id='.$data['cid'])->field('uid')->findAll(),'uid'));
            }
        }else{
            $map['u.uid'] = array('in', getSubByKey(D('user_verified')->where('verified=1')->field('uid')->findAll(),'uid'));
        }
        // 查詢資料
        $list = D()->table($table)->where($map)->order($order)->findPage($page);
        return $list;
    }

    /**
     * 獲取篩選官方使用者資料列表
     * @param array $data 篩選相關條件
     * @param string $field 欄位資料
     * @param string $order 排序資料
     * @param integer $page 分頁個數
     * @return array 篩選官方使用者資料列表
     */
    private function _getOfficialData($data, $field = 'u.uid, o.info', $order = 'u.uid DESC', $page = 30)
    {
        // 設定表明
        $table = '`'.C('DB_PREFIX').'user_official` AS o LEFT JOIN `'.C('DB_PREFIX').'user` AS u ON u.uid = o.uid';
        // 設定查詢條件
        $map['u.is_init'] = 1;
        $map['u.is_del'] = 0;
        // 排除使用者
        if(!empty($data['uids'])) {
            $map['u.uid'] = array('EXP', 'NOT IN ('.$data['uids'].')');
        }
        if(!empty($data['cid'])) {
            $map['o.user_official_category_id'] = intval($data['cid']);
        }
        // 查詢資料
        $list = D()->table($table)->where($map)->order($order)->findPage($page);

        return $list;
    }

    /**
     * 獲取使用者相關資訊
     * @param array $uids 使用者ID陣列
     * @return array 使用者相關陣列
     */
    public function getUserInfos($uids, $data)
    {
        // 獲取使用者基本資訊
        $userInfos = model('User')->getUserInfoByUids($uids);
        // 獲取使用者統計資料
        $userDataInfo = model('UserData')->getUserKeyDataByUids('follower_count',$uids);
        // 獲取關注資訊
        $followStatusInfo = model('Follow')->getFollowStateByFids($GLOBALS['ts']['mid'], $uids);
        // 獲取使用者組資訊
        $userGroupInfo = model('UserGroupLink')->getUserGroupData($uids);
        // 組裝資料
        foreach($data as &$value) {
            $value = array_merge($value, $userInfos[$value['uid']]);
            $value['user_data'] = $userDataInfo[$value['uid']];
            $value['follow_state'] = $followStatusInfo[$value['uid']];
            $value['user_group'] = $userGroupInfo[$value['uid']];
}

return $data;
}

/**
 * 獲取指定使用者的相關資訊
 * @param array $uids 指定使用者ID陣列
 * @param string $type 指定類型
 * @param integer $limit 顯示資料，默認為3
 * @return array 指定使用者的相關資訊
 */
public function getTopUserInfos($uids, $type, $limit = 3)
{
    if(empty($uids)) {
        return array();
}
// 整理成陣列
$uids = is_array($uids) ? $uids : explode(',', $uids);
// 獲取相關使用者資訊
$map['u.uid'] = array('IN', $uids);
$map['u.is_init'] = 1;
$map['u.is_del'] = 0;
switch($type) {
case 'verify':
    $map['v.verified'] = 1;
    $data = D()->table('`'.C('DB_PREFIX').'user_verified` AS v LEFT JOIN `'.C('DB_PREFIX').'user` AS u ON u.uid = v.uid')->where($map)->group('u.uid')->limit($limit)->findAll();
    break;
case 'official':
    $data = D()->table('`'.C('DB_PREFIX').'user_official` AS v LEFT JOIN `'.C('DB_PREFIX').'user` AS u ON u.uid = v.uid')->where($map)->group('u.uid')->limit($limit)->findAll();
    break;
}
$list = $this->getUserInfos($uids, $data);

return $list;
}
}
