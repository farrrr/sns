<?php
/**
 * 支援、反對模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class TipsModel extends Model {

    protected $tableName = 'tips';
    protected $fields = array('id', 'source_id', 'source_table', 'uid', 'type', 'ctime', 'ip');

    private $ip;        // 存儲客戶端IP

    /**
     * 初始化資料，獲取客戶端使用者的IP地址
     * @return void
     */
    protected function _initialize() {
        $this->ip = get_client_ip();
    }

    /**
     * 增加對資源的操作資訊資料
     * @param integer $sid 資源ID
     * @param string $stable 資源表
     * @param integer $uid 操作人UID，默認為登入使用者
     * @param integer $type 類型：0（支援）、1（反對）
     * @return integer 返回操作狀態，0（添加失敗）、1（添加成功）、2（已經添加）
     */
    public function doSourceExec($sid, $stable, $uid, $type) {
        $isExist = $this->whetherExec($sid, $stable, $uid, $type);
        if($isExist) {
            $data['source_id'] = $sid;
            $data['source_table'] = $stable;
            $data['uid'] = $uid;
            $data['type'] = $type;
            $data['ctime'] = time();
            $data['ip'] = $this->ip;

            $res = $this->data($data)->add();
            $res = ($res === false) ? 0 : 1;
            return $res;
        } else {
            return 2;
        }
    }

    /**
     * 刪除指定的資源資訊資料
     * @param integer $sid 資源ID
     * @param string $stable 資源表
     * @return boolean 是否刪除成功
     */
    public function delSourceExec($sid, $stable) {
        $map['source_id'] = $sid;
        $map['source_table'] = $stable;
        $res = $this->where($map)->delete();
        $res = ($res === false) ? false : true;
        return $res;
    }

    /**
     * 獲取指定資源的資訊資料
     * @param integer $sid 資源ID
     * @param string $stable 資源表
     * @param integer $type 類型
     * @return integer 返回相應的資源統計數目
     */
    public function getSourceExec($sid, $stable, $type) {
        $map['source_id'] = $sid;
        $map['source_table'] = $stable;
        $map['type'] = $type;
        $count = $this->where($map)->count();
        return $count;
    }

    /**
     * 判斷是否能進行操作
     * 每個使用者對每條資源只能進行一次支援或者反對操作。
     * 如果uid=0或者uid<1(遊客)，則每個IP只能對每條資源進行一次支援或者反對操作
     * @param integer $sid 資源ID
     * @param string $stable 資源表
     * @param integer $uid 操作使用者UID
     * @param integer $type 類型
     * @return boolean 判斷該使用者是否操作過
     */
    public function whetherExec($sid, $stable, $uid, $type) {
        $map['source_id'] = $sid;
        $map['source_table'] = $stable;
        $map['type'] = $type;

        if($uid < 1) {
            $map['ip'] = $this->ip;
        } else {
            $map['uid'] = $uid;
        }

        $count = $this->where($map)->count();
        $res = ($count > 0) ? false : true;

        return $res;
    }
}
