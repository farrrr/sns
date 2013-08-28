<?php
/**
 * 地區模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AreaModel extends Model {

    protected $tableName = 'area';

    /**
     * 當指定pid時，查詢該父地區的所有子地區；否則查詢所有地區
     * @param integer $pid 父地區ID
     * @return array 相應地區列表
     */
    public function getAreaList($pid = -1) {
        $map = array();
        $pid != -1 && $map['pid'] = $pid;
        $data = $this->where($map)->order('`area_id` ASC')->findAll();
        return $data;
    }

    /**
     * 獲取地區的樹形結構 - 目前為兩級結構 - TODO
     * @param integer $pid 地區的父級ID
     * @return array 指定父級ID的樹形結構
     */
    public function getAreaTree($pid) {
        $output = array();
        $list = $this->getAreaList();
        // 獲取省級
        foreach($list as $k1 => $p) {
            if($p['pid'] == 0) {
                // 獲取當前省的市
                $city = array();
                foreach($list as $k2 => $c) {
                    if($c['pid'] == $p['area_id']) {
                        $city[] = array($c['area_id'] => $c['title']);
                        unset($list[$k2]);
                    }
                }
                $output['provinces'][] = array(
                    'id' => $p['area_id'],
                    'name' => $p['title'],
                    'citys' => $city,
                );
                unset($list[$k1], $city);
            }
        }
        unset($list);
        return $output;
    }

    /**
     * 獲取指定地區ID下的地區資訊
     * @param integer $id 地區ID
     * @return array 指定地區ID下的地區資訊
     */
    public function getAreaById($id) {
        $result = array();
        if(!empty($id)) {
            $map['area_id'] = $id;
            $result = $this->where($map)->find();
        }

        return $result;
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
        $list = S('city');
        if(empty($list)) {
            set_time_limit(0);
            $list = $this->_MakeTree($pid);
            S('city', $list);
        }

        return $list;
    }

    /**
     * 清除地區資料PHP檔案
     * @return void
     */
    public function remakeCityCache() {
        S('city', null);
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
                $id = $value['area_id'];
                $list[$id]['id'] = $value['area_id'];
                $list[$id]['pid'] = $value['pid'];
                $list[$id]['title'] = $value['title'];
                $list[$id]['level'] = $level;
                $list[$id]['child'] = $this->_MakeTree($value['area_id'], $level + 1);
            }
        }

        return $list;
    }
}
