<?php
/**
 * 官方使用者分類模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class UserOfficialCategoryModel extends Model
{
    protected $tableName = 'user_official_category';

    /**
     * 當指定pid時，查詢該父分類的所有子分類；否則查詢所有分類
     * @param integer $pid 父分類ID
     * @return array 相應的分類列表
     */
    public function getCategoryList($pid = -1)
    {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $data = $this->where($map)->order('`official_category_id` ASC')->findAll();
        return $data;
    }

    /**
     * 判斷名稱是否重複
     * @param string $title 名稱
     * @return boolean 是否重複
     */
    public function isTitleExist($title)
    {
        $map['title'] = t($title);
        $count = $this->where($map)->count();
        $result = ($count == 0) ? false : true;
        return $result;
    }

    /**
     * 獲取指定分類的資訊
     * @param integer $cid 分類ID
     * @return array 分類資訊
     */
    public function getCategoryInfo($cid)
    {
        $map['official_category_id'] = $cid;
        $data = $this->where($map)->find();
        return $data;
    }

    /**
     * 獲取指定父地區的樹形結構
     * @param integer $pid 父地區ID
     * @return array 指定樹形結構
     */
    public function getNetworkList($pid = '0') {
        // 子地區樹形結構
        if($pid != 0) {
            return $this->_MakeTree($pid);
        }
        // 全部地區樹形結構
        $list = S('official');
        if(!$list) {
            set_time_limit(0);
            $list = $this->_MakeTree($pid);
            S('official', $list);
        }

        return $list;
    }

    /**
     * 清除地區資料PHP檔案
     * @return void
     */
    public function remakeOfficialCache() {
        S('official', null);
    }

    /**
     * 遞迴形成樹形結構
     * @param integer $pid 父級ID
     * @param integer $level 等級
     * @return array 樹形結構
     */
    private function _MakeTree($pid, $level = '0') {
        $result = $this->where('pid='.$pid)->findAll();
        if($result) {
            foreach($result as $key => $value) {
                $id = $value['official_category_id'];
                $list[$id]['id'] = $value['official_category_id'];
                $list[$id]['pid'] = $value['pid'];
                $list[$id]['title'] = $value['title'];
                $list[$id]['level'] = $level;
                $list[$id]['child'] = $this->_MakeTree($value['official_category_id'], $level + 1);
            }
        }

        return $list;
    }

    /**
     * 獲取分類的Hash陣列
     */
    public function getCategoryHash($pid = -1)
    {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $data = $this->where($map)->getHashList('official_category_id', 'title');
        return $data;
    }
}
