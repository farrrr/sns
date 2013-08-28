<?php
/**
 * 關注控制器
 * @author chenweichuan <chenweichuan@zhishisoft.com>
 * @version TS3.0
 */
class FollowAction extends Action {

    private $_follow_model = null;         // 關注模型物件欄位

    /**
     * 初始化控制器，例項化關注模型物件
     */
    protected function _initialize() {
        $this->_follow_model = model('Follow');
    }

    /**
     * 添加關注操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function doFollow() {
        // 安全過濾
        $fid = t($_POST['fid']);
        $res = $this->_follow_model->doFollow($this->mid, intval($fid));
        $this->ajaxReturn($res, $this->_follow_model->getError(), false !== $res);
    }

    /**
     * 取消關注操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function unFollow() {
        // 安全過濾
        $fid = t($_POST['fid']);
        $res = $this->_follow_model->unFollow($this->mid, intval($fid));
        $this->ajaxReturn($res, $this->_follow_model->getError(), false !== $res);
    }

    /**
     * 批量添加關注操作
     * @return json 返回操作後的JSON資訊資料
     */
    public function bulkDoFollow() {
        // 安全過濾
        $res = $this->_follow_model->bulkDoFollow($this->mid, t($_POST['fids']));
        $this->ajaxReturn($res, $this->_follow_model->getError(), false !== $res);
    }
}
