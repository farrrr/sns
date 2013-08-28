<?php
/**
 * 廣告位鉤子
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class AdSpaceHooks extends Hooks
{
    /**
     * 顯示廣告位鉤子
     * @param array $param 鉤子相關參數
     * @return void
     */
    public function show_ad_space($param)
    {
        // 獲取位置廣告資訊
        $place = t($param['place']);
        $placeInfo = $this->_getPlaceKey($place);
        $data = $this->model('AdSpace')->getAdSpaceByPlace($placeInfo['id']);
        foreach($data as &$value) {
            if($value['display_type'] == 3) {
                $value['content'] = unserialize($value['content']);
                // 獲取附件圖片地址
                foreach($value['content'] as &$val) {
                    $attachInfo = model('Attach')->getAttachById($val['banner']);
                    $val['bannerpic'] = getImageUrl($attachInfo['save_path'].$attachInfo['save_name']);
                }
            }
        }
        $this->assign('data', $data);
        // 設定寬度
        $width = intval($placeInfo['width']);
        $this->assign('width', $width);
        // 設定距離頂端距離
        $top = intval($placeInfo['top']);
        $this->assign('top', $top);
        $this->display('showAdSpace');
    }

    /**
     * 廣告位插件
     * @return void
     */
    public function config()
    {
        // 位置陣列
        $placeArr = $this->_getPlaceData();
        $placeArray = array();
        foreach($placeArr as $value) {
            $placeArray[$value['id']] = $value['name'];
        }
        $this->assign('place_array', $placeArray);
        // 列表資料
        $list = $this->model('AdSpace')->getAdSpaceList();
        $this->assign('list', $list);

        $this->display('config');
    }

    /**
     * 添加廣告位頁面
     * @return void
     */
    public function addAdSpace()
    {
        // 位置陣列
        $placeArr = $this->_getPlaceData();
        $this->assign('placeArr', $placeArr);
        // 是否可編輯
        $this->assign('editPage', false);
        $this->display('addAdSpace');
    }

    /**
     * 添加廣告位操作
     * @return void
     */
    public function doAddAdSpace()
    {
        // 組裝資料
        $data['title'] = t($_POST['title']);
        $data['place'] = intval($_POST['place']);
        $data['is_active'] = intval($_POST['is_active']);
        $data['ctime'] = time();
        $data['display_type'] = intval($_POST['display_type']);
        switch($data['display_type']) {
        case 1:
            $data['content'] = $_POST['html_form'];
            break;
        case 2:
            $data['content'] = $_POST['code_form'];
            break;
        case 3:
            $picData = array();
            for($i = 0; $i < count($_POST['banner']); $i++) {
                $picData[] = array('banner'=>$_POST['banner'][$i], 'bannerurl'=>$_POST['bannerurl'][$i]);
            }
            $data['content'] = serialize($picData);
            break;
        }
        $res = $this->model('AdSpace')->doAddAdSpace($data);

        return false;
    }

    /**
     * 刪除廣告位操作
     * @return json 是否刪除成功
     */
    public function doDelAdSpace()
    {
        $result = array();
        $ids = t($_POST['ids']);
        if(empty($ids)) {
            $result['status'] = 0;
            $result['info'] = '參數不能為空';
            exit(json_encode($result));
        }
        $res = $this->model('AdSpace')->doDelAdSpace($ids);
        if($res) {
            $result['status'] = 1;
            $result['info'] = '刪除成功';
        } else {
            $result['status'] = 0;
            $result['info'] = '刪除失敗';
        }
        exit(json_encode($result));
    }

    /**
     * 編輯廣告位頁面
     * @return void
     */
    public function editAdSpace()
    {
        // 位置陣列
        $placeArr = $this->_getPlaceData();
        $this->assign('placeArr', $placeArr);
        // 獲取廣告位資訊
        $id = intval($_GET['id']);
        $data = $this->model('AdSpace')->getAdSpace($id);
        // 輪播圖片內容解析
        if($data['display_type'] == 3) {
            $data['content'] = unserialize($data['content']);
            foreach($data['content'] as &$value) {
                $attachInfo = model('Attach')->getAttachById($value['banner']);
                $value['bannerpic'] = getImageUrl($attachInfo['save_path'].$attachInfo['save_name']);
            }
        }
        $this->assign('data', $data);
        $this->assign('editPage', true);

        $this->display('addAdSpace');
    }

    /**
     * 編輯廣告位操作
     * @return void
     */
    public function doEditAdSpace()
    {
        // 資料組裝
        $id = intval($_POST['ad_id']);
        $data['title'] = t($_POST['title']);
        $data['place'] = intval($_POST['place']);
        $data['is_active'] = intval($_POST['is_active']);
        $data['mtime'] = time();
        $data['display_type'] = intval($_POST['display_type']);
        switch($data['display_type']) {
        case 1:
            $data['content'] = $_POST['html_form'];
            break;
        case 2:
            $data['content'] = $_POST['code_form'];
            break;
        case 3:
            $picData = array();
            for($i = 0; $i < count($_POST['banner']); $i++) {
                $picData[] = array('banner'=>$_POST['banner'][$i], 'bannerurl'=>$_POST['bannerurl'][$i]);
            }
            $data['content'] = serialize($picData);
            break;
        }
        $res = $this->model('AdSpace')->doEditAdSpace($id, $data);

        return false;
        }

        /**
         * 移動廣告位操作
         * @return void
         */
        public function doMvAdSpace()
        {
            $result = array();
            $id = intval($_POST['id']);
            $baseId = intval($_POST['baseId']);
            if($id <= 0 || $baseId <= 0) {
                $result['status'] = 0;
                $result['info'] = '參數錯誤';
                exit(json_encode($result));
        }
        $res = $this->model('AdSpace')->doMvAdSpace($id, $baseId);
        if($res) {
            $result['status'] = 1;
            $result['info'] = '操作成功';
        } else {
            $result['status'] = 0;
            $result['info'] = '操作失敗';
        }

        exit(json_encode($result));
        }

        /**
         * 獲取廣告位配置資訊
         * @return array 廣告位配置資訊
         */
        private function _getPlaceData()
        {
            $data = include(ADDON_PATH.'/plugin/AdSpace/config/config.php');
            return $data;
        }

        /**
         * 通過鍵值獲取相應的ID
         * @return integer 對應鍵值的ID
         */
        private function _getPlaceKey($key)
        {
            $data = $this->_getPlaceData();
            return $data[$key];
        }
        }
