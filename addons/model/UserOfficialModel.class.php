<?php
/**
 * 官方使用者模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserOfficialModel extends Model
{
    protected $tableName = 'user_official';
    protected $fields = array(0=>'uid',1=>'info',2=>'user_official_category_id');
    /**
     * 獲取指定官方使用者的資訊
     * @param array $uids 使用者ID
     * @return array 指定官方使用者的資訊
     */
    public function getUserOfficialInfo($uids) {
        if(empty($uids)) {
            return array();
        }
        $map['uid'] = array('IN', $uids);
        $data = $this->where($map)->getHashList('uid', 'info');

        return $data;
    }

    /**
     * 添加官方使用者資訊
     * @param array $uids 添加使用者ID陣列
     * @param integer $cid 官方使用者分類ID
     * @param string $info 相關資訊
     * @return boolean 是否添加成功
     */
    public function addOfficialUser($uids, $cid, $info)
    {
        $uids = is_array($uids) ? $uids : explode(',', $uids);
        if(empty($uids) || empty($cid)) {
            return false;
        }
        // 添加使用者資訊
        $data['user_official_category_id'] = $cid;
        $data['info'] = $info;
        foreach($uids as $uid) {
            // 判斷是否添加
            $map['user_official_category_id'] = $cid;
            $map['uid'] = $uid;
            $isExist = $this->where($map)->count();
            if($isExist == 0) {
                $data['uid'] = $uid;
                $this->add($data);
            }
        }

        return true;
    }

    /**
     * 獲取官方使用者列表
     * @return array 官方使用者列表
     */
    public function getUserOfficialList()
    {
        // 獲取列表
        $list = $this->where($map)->findPage();
        // 獲取使用者ID陣列
        $uids = getSubByKey($list['data'], 'uid');
        // 獲取使用者資訊
        $userInfos = model('User')->getUserInfoByUids($uids);
        // 獲取分類資訊
        $category = model('CategoryTree')->setTable('user_official_category')->getCategoryHash();
        foreach($list['data'] as &$value) {
            $value = array_merge($value, $userInfos[$value['uid']]);
            $value['title'] = $category[$value['user_official_category_id']];
        }

        return $list;
    }

    /**
     * 移除官方使用者
     * @param array $ids 官方使用者表主鍵ID
     * @return boolean 是否成功移除官方使用者
     */
    public function removeUserOfficial($ids)
    {
        // 格式化資料
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        // 驗證資料的正確性
        if(empty($ids)) {
            return false;
        }
        // 移除使用者
        $map['official_id'] = array('IN', $ids);
        $res = $this->where($map)->delete();

        return (boolean)$res;
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
        // 刪除官方使用者分類下的資料
        $map['user_official_category_id'] = $cid;
        $this->where($map)->delete();

        return true;
    }
}
