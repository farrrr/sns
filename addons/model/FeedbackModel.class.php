<?php
/**
 * 意見反饋模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class FeedbackModel extends Model {

    protected $tableName = 'feedback';
    protected $fields = array(0=>'id',1=>'feedbacktype',2=>'feedback',3=>'uid',4=>'ctime',5=>'mtime',6=>'type','_autoinc'=>true,'_pk'=>'id');

    /**
     * 修改指定的意見反饋
     * @param integer $id 意見反饋資源ID
     * @return void
     */
    public function savefeedback($id) {
        $map['id'] = intval($id);
        $save['type'] = '10';
        $this->where($map)->save($save);
    }

    /**
     * 獲取意見反饋類型的Hash陣列
     * @return array 意見反饋類型的Hash陣列
     */
    public function getFeedBackType() {
        $data = D('')->table($this->tablePrefix.'feedback_type')->getHashList('type_id','type_name');
        return $data;
    }
}
