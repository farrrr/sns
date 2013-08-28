<?php
/**
 * 廣告位插件模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class AdSpaceModel extends Model
{
    protected $tableName = 'ad';
    protected $_error;

    /**
     * 添加廣告位資料
     * @param array $data 廣告位相關資料
     * @return boolean 是否插入成功
     */
    public function doAddAdSpace($data)
    {
        $data['display_order'] = $this->count();
        $res = $this->add($data);

        return (boolean)$res;
    }

    /**
     * 獲取廣告位列表資料
     * @return array 廣告位列表資料
     */
    public function getAdSpaceList()
    {
        $data = $this->order('display_order DESC')->findPage(20);

        return $data;
    }

    /**
     * 刪除廣告位操作
     * @param string|array $ids 廣告位ID
     * @return boolean 是否刪除廣告位成功
     */
    public function doDelAdSpace($ids)
    {
        $ids = is_array($ids) ? $ids : explode(',', $ids);
        if(empty($ids)) {
            return false;
        }
        $map['ad_id'] = array('IN', $ids);
        $res = $this->where($map)->delete();

        return (boolean)$res;
    }

    /**
     * 獲取指定ID的廣告位資訊
     * @param integer $id 廣告位ID
     * @return array 指定ID的廣告位資訊
     */
    public function getAdSpace($id)
    {
        if(empty($id)) {
            return array();
        }
        $map['ad_id'] = $id;
        $data = $this->where($map)->find();
        return $data;
    }

    /**
     * 編輯廣告位操作
     * @param integer $id 廣告位ID
     * @param array $data 廣告位相關資料
     * @return boolean 是否編輯成功
     */
    public function doEditAdSpace($id, $data)
    {
        if(empty($id)) {
            return false;
        }
        $map['ad_id'] = $id;
        $res = $this->where($map)->save($data);

        return (boolean)$res;
    }

    /**
     * 移動廣告位操作
     * @param integer $id 廣告位ID - A
     * @param integer $baseId 廣告位ID - B
     * @return boolean 是否移動成功
     */
    public function doMvAdSpace($id, $baseId)
    {
        $map['ad_id'] = array('IN', array($id, $baseId));
        $order = $this->where($map)->getHashList('ad_id', 'display_order');
        if(count($order) < 2) {
            return false;
        }
        $this->where('`ad_id`='.$id)->setField('display_order', $order[$baseId]);
        $this->where('`ad_id`='.$baseId)->setField('display_order', $order[$id]);

        return true;
    }

    /**
     * 通過位置ID獲取相應的廣告資訊
     * @param integer $place 位置ID
     * @return array 位置ID獲取相應的廣告資訊
     */
    public function getAdSpaceByPlace($place)
    {
        if(empty($place)) {
            return array();
        }
        // 獲取資訊
        $map['place'] = $place;
        $map['is_active'] = 1;
        $data = $this->where($map)->order('display_order DESC')->findAll();

        return $data;
    }
}
