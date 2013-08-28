<?php
/**
 * 隱私模組
 * @author  liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.0
 */
class PrivacyAction extends Action {

    /**
     * 模組初始化
     * @return void
     */
    protected function _initialize() {

    }

    /**
     * 隱私設定頁面
     * @return void
     */
    public function index() {
        $this->display();
    }

    /**
     * 儲存隱私設定
     * @return void
     */
    public function doSavePrivacy() {
        $this->display();
    }
}
?>
