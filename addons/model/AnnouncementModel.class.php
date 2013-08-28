<?php
/**
 * 公告模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class AnnouncementModel extends Model {

    protected $tableName = 'announcement';
    protected $fields = array('id', 'title', 'uid', 'mtime', 'sort', 'content', 'attach', '_pk'=>'id');

    /**
     * 儲存公告資料
     * @param array $data 公告所需資料，使用者UID、公告標題、公告內容、相關附件、創建時間
     * @return integer 返回成功的公告ID
     */
    public function saveAnnoun($data) {
        $add['uid'] = $save['uid'] = 1;     // TODO:UID臨時寫死
        $add['title'] = $save['title'] = t($data['title']);
        $add['content'] = $save['content'] = t($data['content']);   //TODO:編輯器可能不適宜用t函數
        $add['attach'] = $save['attach'] = t($data['attach']);
        $add['mtime'] = $save['mtime'] = time();

        // 儲存公告資料操作
        if(!empty($data['id'])) {
            // 編輯公告
            $map['id'] = $data['id'];
            return $this->where($map)->save($save);
        } else {
            // 添加公告
            if($id = $this->add($add)) {
                $edit['sort'] = $id;
                return $this->where('id='.$id)->save($edit);
            }
        }
    }

    /**
     * 刪除公告
     * @param integer $id 公告ID
     * @return integer 是否刪除成功
     */
    public function delannoun($id) {
        // 驗證資料正確性
        if(empty($id)) {
            $this->error = L('PUBLIC_WRONG_DATA');
            return false;
        }

        $map['id'] = is_array($id) ? array('IN', $id) : intval($id);
        return $this->where($map)->delete();
    }
}
