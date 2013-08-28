<?php
class ContributeHooks extends Hooks{
    public function weibo_send_box_tab(){
        $data = model('CategoryTree')->setTable('channel_category')->getCategoryList();
        $status = 1;
        if(empty($data)) {
            $status = 0;
            $data = '獲取資料失敗';
        }
        $this->assign('status' , $status);
        $this->assign('data' , $data);
        $this->display('weibo_send_box_tab');
    }
}
?>
