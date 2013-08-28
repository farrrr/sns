<?php
/**
 * UserCountAction 使用者統計模組
 * @version TS3.0
 */
class UserCountAction extends Action
{
    /**
     * 使用者的通知統計數目
     * @return mix 通知統計狀態和數目
     */
    public function getUnreadCount()
    {
        $count = model('UserCount')->getUnreadCount($this->mid);
        $data['status'] = 1;
        $data['data'] = $count;
        echo json_encode($data);
    }
}
