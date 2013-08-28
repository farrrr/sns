<?php
/**
 * ManageAction 使用者管理的應用模組
 * @version TS3.0
 */
class ManageAction extends Action
{
    private $appList;

    /**
     * 模組初始化,獲取當前使用者管理的應用
     * @return void
     */
    public function _initialize() {
        $this->appList = model('App')->getManageApp($this->mid);

        if(empty($this->appList)){
            $this->error(L('PUBLIC_NO_FRONTPLATFORM_PERMISSION'));
        }
    }

    /**
     * 展示使用者管理的應用
     * @return void
     */
    public function index() {

        $this->assign('appList',$this->appList);
        $this->setTitle( L('PUBLIC_MANAGE_INDEX') );
        $this->display();
    }
}
