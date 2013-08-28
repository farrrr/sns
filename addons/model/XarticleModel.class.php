<?php
/**
 * 內建文章模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class XarticleModel extends Model {

    protected $tableName = 'x_article';
    protected $fields = array('id','title','uid','mtime','sort','content','attach','type');

    /**
     * 儲存公告資料
     * @param array $data 公告相關資料
     * @return boolean|integer 若成功返回公告ID，失敗返回false
     */
    public function saveArticle($data) {
        // 處理資料
        $add['uid'] = $save['uid'] = $GLOBALS['ts']['mid'];
        $add['title'] = $save['title'] = t($data['title']);
        $add['content'] = $save['content'] = h($data['content']);
        $add['attach'] = $save['attach'] = trim(t($data['attach_ids']), '|');   // 附件ID
        $add['mtime'] = $save['mtime'] = time();
        $add['type'] = $save['type'] = intval($data['type']);

        if(empty($add['title'])) {
            $this->error = L('PUBLIC_COMMENT_MAIL_TITLE');          // 標題不可為空
            return false;
        }
        if(empty($add['content'])){
            $this->error = L('PUBLIC_COMMENT_MAIL_REQUIRED');       // 內容不可為空
            return false;
        }

        if(!empty($data['id'])) {
            // 編輯操作
            $map['id'] = $data['id'];
            return $this->where($map)->save($save);
        } else {
            // 添加操作
            if($id = $this->add($add)) {
                $edit['sort'] = $id;
                return $this->where('id='.$id)->save($edit);
            }
        }
    }

    /**
     * 刪除指定公告操作
     * @param integer $id 公告ID
     * @return integer 0表示刪除失敗，1表示刪除成功
     */
    public function delArticle($id) {
        if(empty($id)) {
            $this->error = L('PUBLIC_ID_NOEXIST');          // ID不能為空
            return false;
        }
        $map['id'] = is_array($id) ? array('IN', $id) : intval($id);
        return $this->where($map)->delete();
    }
}
