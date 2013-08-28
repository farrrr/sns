<?php
/**
 * 使用者身份模型 - 資料物件模型
 * @zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserCategoryModel extends Model {

    protected $tableName = 'user_category';
    protected $fields = array(0=>'user_category_id',1=>'title',2=>'pid');

    /**
     * 當指定pid時，查詢該父使用者身份的所有子使用者身份；否則查詢所有使用者身份
     * @param integer $pid 父使用者身份ID
     * @return array 相應使用者身份列表
     */
    public function getUserCategoryListr($pid = -1) {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $data = $this->where($map)->order('`user_category_id` ASC')->findAll();
        return $data;
    }

    /**
     * 清除使用者身份快取
     * @return void
     */
    public function remakeUserCategoryCache() {
        model('Cache')->rm('UserCategoryTree');
    }


    /**
     * 獲取指定父身份的樹形結構
     * @param integer $pid 父身份ID
     * @return array 指定樹形結構
     */
    public function getNetworkList($pid = '0') {
        // 子身份樹形結構
        if($pid != 0) {
            return $this->_MakeTree($pid);
        }
        // 全部身份樹形結構
        $list = model('Cache')->get('UserCategoryTree');
        if(empty($list)) {
            set_time_limit(0);
            $list = $this->_MakeTree($pid);
            model('Cache')->set('UserCategoryTree', $list);
        }

        return $list;
    }

    /**
     * 遞迴形成樹形結構
     * @param integer $pid 父級ID
     * @param integer $level 等級
     * @return array 樹形結構
     */
    private function _MakeTree($pid, $level = '0') {
        $result = $this->where('pid='.$pid)->order('sort ASC')->findAll();
        if($result) {
            foreach($result as $key => $value) {
                $id = $value['user_category_id'];
                $list[$id]['id'] = $value['user_category_id'];
                $list[$id]['pid'] = $value['pid'];
                $list[$id]['title'] = $value['title'];
                $list[$id]['level'] = $level;
                $list[$id]['child'] = $this->_MakeTree($value['user_category_id'], $level + 1);
            }
        }

        return $list;
    }

    /**
     * 添加使用者與使用者身份的關聯資訊
     * @param integer $uid 使用者ID
     * @param integer $cid 使用者身份ID
     * @return boolean 是否添加成功
     */
    public function addRelatedUser($uid, $cid) {
        $map['uid'] = $add['uid'] = $uid;
        $map['user_category_id'] = $add['user_category_id'] = $cid;
        $count = D('user_category_link')->where($map)->count();
        if($count > 0) {
            return false;
        }
        $result = D('user_category_link')->add($add);
        return (boolean)$result;
    }

    /**
     * 刪除使用者與使用者身份的關聯資訊
     * @param integer $uid 使用者ID
     * @param integer $cid 使用者身份ID
     * @return boolean 是否刪除成功
     */
    public function deleteRelatedUser($uid, $cid) {
        $map['uid'] = $uid;
        $map['user_category_id'] = $cid;
        $count = D('user_category_link')->where($map)->count();
        if($count < 1) {
            return false;
        }
        $result = D('user_category_link')->where($map)->delete();
        return (boolean)$result;
    }

    /**
     * 更改使用者與使用者身份的關聯資訊
     * @param integer $uid 使用者ID
     * @param array $cids 使用者身份ID陣列
     * @return boolean 是否修改成功
     */
    public function updateRelateUser($uid, $cids) {
        $map['uid'] = $uid;
        // 刪除原有的資料
        D('user_category_link')->where($map)->delete();
        // 添加新的身份關聯資料
        $add['uid'] = $uid;
        foreach($cids as $value) {
            $add['user_category_id'] = $value;
            D('user_category_link')->add($add);
        }

        return true;
    }

    /**
     * 獲取指定使用者的身份資訊
     */
    public function getRelatedUserInfo($uid) {
        $map['ucl.uid'] = $uid;
        $data = D('')->table('`'.$this->tablePrefix.'user_category` AS uc LEFT JOIN `'.$this->tablePrefix.'user_category_link` AS ucl ON uc.user_category_id = ucl.user_category_id')
            ->field('uc.*')
            ->where($map)
            ->findAll();

        return $data;
    }

    /**
     * 獲取身份的雜湊列表
     * @param array $map 查詢條件
     * @return array 身份的雜湊列表陣列
     */
    public function getAllHash($map) {
        $data = $this->where($map)->getHashList('user_category_id', 'title');
        return $data;
    }

    /**
     * 獲取指定分類下的使用者ID
     * @param integer $cid 分類ID
     * @param integer $isAuthenticate 是否是認證使用者，1表示是，0表示不是
     * @param integer $limit  每頁顯示多少個
     * @return array 指定分類下的使用者ID
     */
    public function getUidsByCid($cid, $isAuthenticate,$limit = 20) {
        if($isAuthenticate == 1) {
            // 由於認證使用者的使用者組為5 - 將不會被改變
            $map['a.user_category_id'] = intval($cid);
            $map['b.user_group_id'] = 5;
            $data = D('')->table('`'.$this->tablePrefix.'user_category_link` AS a LEFT JOIN `'.$this->tablePrefix.'user_group_link` AS b ON a.uid = b.uid')
                ->field('a.uid')
                ->where($map)
                ->findPage();
        } else {
            $umap['is_active'] = 1;
            $umap['is_audit'] = 1;
            $umap['is_init'] = 1;
            if($cid == 0) {
                //              $count = D('')->table($this->tablePrefix.'user_category_link')->count(array(), 'DISTINCT `uid`');
                //              $data = D('')->table($this->tablePrefix.'user_category_link')->field('DISTINCT `uid`')->findPage(20, $count);
                $data = model( 'User' )->where($umap)->field('uid')->order('last_post_time DESC,last_login_time DESC')->findPage($limit);
                return $data;
            } else {
                // 按分類查找
                // $pid = $this->where('user_category_id='.$cid)->getField('pid');
                // if($pid == 0) {
                //  $cids = $this->where('pid='.$cid)->getAsFieldArray('user_category_id');
                //  $map['user_category_id'] = array('IN', $cids);
                // } else {
                //  $map['user_category_id'] = intval($cid);
                // }
                // $uids = D('')->table($this->tablePrefix.'user_category_link')->field('`uid`')->where($map)->findAll();

                // $umap['uid'] = array( 'in' , getSubByKey( $uids , 'uid') );
                // $data = model('User')->where($umap)->field('uid')->order('last_post_time DESC,last_login_time DESC')->findPage($limit);
                // return $data;

                // 按標籤查找
                $pid = M('UserCategory')->where('user_category_id='.$cid)->getField('pid');
                if($pid == 0) {
                    $cids = M('UserCategory')->where('pid='.$cid)->getAsFieldArray('user_category_id');

                    $cmap['user_category_id'] = array('IN', $cids);

                    $title = M('UserCategory')->where($cmap)->findAll();

                    foreach ($title as $key => $value) {
                        $amap['name'] = array('LIKE',$value['title']);
                        $tag = M('tag')->where($amap)->getField('tag_id');
                        if($tag){
                            $tag_id[] = $tag;
            }
            }
            $tmap['tag_id'] = array('IN',$tag_id);
            } else {
                $cmap['user_category_id'] = intval($cid);
                $title = M('UserCategory')->where($cmap)->find();
                $amap['name'] = array('LIKE',$title['title']);
                $tag_id[] = M('tag')->where($amap)->getField('tag_id');
                $tmap['tag_id'] = array('IN',$tag_id);

            }
            $uids = M('app_tag')->field('`row_id`')->where($tmap)->findAll();
            $umap['uid'] = array( 'in' , getSubByKey( $uids , 'row_id') );
            $data = model('User')->where($umap)->field('uid')->order('last_post_time DESC,last_login_time DESC')->findPage($limit);
            return $data;

            }
            }
            $ordermap['uid'] = array( 'in' , getSubByKey($data['data'] , 'uid') );
            $uiddata = model('User')->where($ordermap)->field('uid')->order('last_post_time DESC,last_login_time DESC')->findAll();
            $data['data'] = $uiddata;
            return $data;
            }

            /**
             * 獲取所有身份分類ID
             * @return array 所有身份分類ID
             */
            public function getAllUserCategoryIds() {
                $data = $this->getAsFieldArray('user_category_id');
                return $data;
            }
            }
