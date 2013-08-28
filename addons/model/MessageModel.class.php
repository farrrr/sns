<?php
/**
 * 私信發送模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class MessageModel extends Model {

    const ONE_ON_ONE_CHAT  = 1;         // 1對1聊天
    const MULTIPLAYER_CHAT = 2;         // 多人聊天
    const SYSTEM_NOTIFY    = 3;         // 系統私信

    protected $tableName = 'message_content';
    protected $fields = array(0=>'message_id', 1=>'list_id', 2=>'from_uid', 3=>'content', 4=>'is_del', 5=>'mtime', '_pk'=>'message_id');

    private $reversible_type = array();

    /**
     * 初始化方法，
     * @return void
     */
    public function _initialize() {
        $this->reversible_type = array(self::ONE_ON_ONE_CHAT, self::MULTIPLAYER_CHAT);
    }

    /**
     * 獲取訊息列表 - 分頁型
     * @param array $map 查詢條件
     * @param string $field 顯示欄位，默認為*
     * @param string $order 排序條件，默認為message_id DESC
     * @param integer $limit 結果集數目，默認為20
     * @return array 訊息列表資訊
     */
    public function getMessageByMap($map = array(), $field = '*', $order = 'message_id DESC', $limit = 20) {
        $list = $this->where($map)->field($field)->order($order)->findPage($limit);
        return $list;
    }

    /**
     * 獲取私信列表 - 分頁型
     * @param integer $uid 使用者UID
     * @param integer $type 私信類型，1表示一對一私信，2表示多人聊天，默認為1
     * @return array 私信列表資訊
     */
    public function getMessageListByUid($uid, $type = 1) {
        $uid = intval($uid);
        $type = is_array($type) ? ' IN ('.implode(',', $type).')' : "={$type}";
        $list = D('message_member')->Table("`{$this->tablePrefix}message_member` AS `mb`")
            ->join("`{$this->tablePrefix}message_list` AS `li` ON `mb`.`list_id`=`li`.`list_id`")
            ->where("`mb`.`member_uid`={$uid} AND `li`.`type`{$type} AND `mb`.`is_del` = 0 AND mb.message_num > 0")
            ->order('`mb`.`new` DESC,`mb`.`list_ctime` DESC')
            ->findPage();

        $this->_parseMessageList($list['data'], $uid); // 引用

        return $list;
    }

    /**
     * 格式化，私信列表資料
     * @param array $list 私信列表資料
     * @param integer $current_uid ???
     * @return array 返回格式化後的私信列表資料
     */
    private function _parseMessageList(&$list, $current_uid) {
        foreach ($list as &$v) {
            $v['last_message'] = unserialize($v['last_message']);
            $v['last_message']['to_uid'] = $this->_parseToUidByMinMax($v['min_max'], $v['last_message']['from_uid']);
            $v['last_message']['user_info'] = model('User')->getUserInfo($v['last_message']['from_uid']);
            $v['to_user_info'] = model('User')->getUserInfoByUids($v['last_message']['to_uid']);
        }
    }

    /**
     * 獲取私信詳細內容
     * @param integer $uid 使用者UID
     * @param integer $id 私信ID
     * @param boolean $show_cascade 是否獲取回話內容
     * @return array 私信詳細內容
     */
    public function getDetailById($uid, $id, $show_cascade = true) {
        $uid = intval($uid);
        $id = intval($id);
        if($show_cascade) {
            // 驗證該使用者是否為該私信成員
            if(!$this->isMember($id, $uid, false)) {
                return false;
            }
            $map['list_id'] = $id;
            $map['is_del'] = 0;
            $res = D('message_content')->where($map)->order('message_id DESC')->findAll();
            foreach($res as $r_k => $r_v) {
                $res[$r_k]['user_info'] = model('User')->getUserInfo($r_v['from_uid']);
            }
            return $res;
        } else {
            // `mb`.`member`={$uid} 可驗證當前使用者是否依然為該私信成員
            return D('message_content')->Table("`{$this->tablePrefix}message_content` AS `ct`")
                ->join("`{$this->tablePrefix}message_member` AS `mb` ON `ct`.`list_id`=`mb`.`list_id` AND `ct`.`from_uid`=`mb`.`member_uid`")
                ->where("`mb`.`member_uid`={$uid} AND `ct`.`message_id`={$id} AND `ct`.`is_del`=0")
                ->find();
        }
    }

    /**
     * 獲取所有私信內容的列表
     * @param array $map 查詢條件
     * @param integer $limit 結果集數目，默認為20
     * @param string $order 排序條件，默認為a.message_id DESC
     * @return [type]         [description]
     */
    public function getDetailList($map,$limit=20,$order = 'a.message_id DESC') {
        $field = 'a.*, a.from_uid AS fuid, b.type, b.min_max';
        $table = '`'.$this->tablePrefix.'message_content` AS a LEFT JOIN `'.$this->tablePrefix.'message_list` AS b ON a.list_id = b.list_id LEFT JOIN `'.$this->tablePrefix.'message_member` AS c ON a.list_id = c.list_id';
        $list = $this->table($table)->where($map)->field($field)->group('a.message_id')->order($order)->findPage($limit);
        return $list;
    }

    /**
     * 獲取指定私信列表中的私信內容
     * @param integer $list_id 私信列表ID
     * @param integer $uid 使用者ID
     * @param integer $since_id 最早會話ID
     * @param integer $max_id 最新會話ID
     * @param integer $count 舊會話載入條數，默認為20
     * @return array 指定私信列表中的私信內容
     */
    public function getMessageByListId($list_id, $uid, $since_id = null, $max_id = null, $count = 20) {
        $list_id = intval($list_id);
        $uid = intval($uid);
        $since_id = intval($since_id);
        $max_id = intval($max_id);
        $count = intval($count);

        // 驗證該使用者是否為該私信成員
        if(!$list_id || !$uid || !$messageInfo = $this->isMember($list_id, $uid, false)) {
            return false;
        }

        $where = "`list_id`={$list_id} AND `is_del`=0";
        if($since_id > 0) {
            $where .= " AND `message_id` > {$since_id}";
            $max_id > 0 && $where .= " AND `message_id` < {$max_id}";
            $limit = intval($count) + 1;
        } else {
            $max_id > 0 && $where .= " AND `message_id` < {$max_id}";
            // 多查詢一條驗證，是否還有後續資訊
            $limit = intval($count) + 1;
        }
        $res['data']  = D('message_content')->where($where)->order('message_id DESC')->limit($limit)->findAll();

        foreach($res['data'] as $r_d_k => $r_d_v) {
            $res['data'][$r_d_k]['user_info'] = model('User')->getUserInfo($r_d_v['from_uid']);
        }
        $res['count'] = count($res['data']);

        if($since_id > 0) {
            $res['since_id'] = $res['data'][0]['message_id'];
            $res['max_id'] = $res['data'][$res['count'] - 1]['message_id'];
            if($res['count'] < $limit){
                $res['max_id'] = 0;
            }
        } else {
            $res['since_id'] = $res['data'][0]['message_id'];
            // 結果數等於查詢數，則說明還有後續message
            if($res['count'] == $limit) {
                // 去除結果的最後一條
                array_pop($res['data']);
                // 計數減一
                $res['count']--;
                // 取最後一條結果message_id
                $res['max_id'] = $res['data'][$res['count'] - 1]['message_id'];
            } else if($res['count'] < $limit) {
                // 取最後一條結果message_id設定為0，表示結束
                $res['max_id'] = 0;
            }
        }

        return $res;
    }

    /**
     * 獲取指定使用者未讀的私信數目
     * @param integer $uid 使用者ID
     * @param integer $type 私信類型，1表示一對一私信，2表示多人聊天，默認為1
     * @return integer 指定使用者未讀的私信數目
     */
    public function getUnreadMessageCount($uid, $type) {
        $map['a.member_uid'] = intval($uid);
        $map['a.new'] = array('EQ', 2);
        $type && $map['b.type'] = array('IN', $type);
        $table = $this->tablePrefix.'message_member AS a LEFT JOIN '.$this->tablePrefix.'message_list AS b ON a.list_id = b.list_id';
        $unread = $this->table($table)->where($map)->count();
        return intval($unread);
    }

    /**
     * 發送私信
     * @param array $data 私信資訊，包括to接受物件、title私信標題、content私信正文
     * @param integer $from_uid 發送私信的使用者ID
     * @param boolean $send_email 是否同時發送郵件，默認為false
     * @return boolean 是否發送成功
     */
    public function postMessage($data, $from_uid,$send_email = false) {
        $from_uid = intval($from_uid);
        $data['to'] = is_array($data['to']) ? $data['to'] : explode(',', $data['to']);
        $data['member'] = array_filter(array_merge(array($from_uid), $data['to']));     // 私信成員
        $data['mtime']  = time();       // 發起時間

        if($data['type'] != self::SYSTEM_NOTIFY && $from_uid > 1){
            // 判斷接受者能否接受私信
            foreach($data['to'] as $v) {
                $privacy = model('UserPrivacy')->getPrivacy($from_uid,$v);
                $userInfo = model('User')->getUserInfo($v);
                if($privacy['message'] == 1) {
                    $this->error = '根據對方的隱私設定，您無法給TA發送私信';           // 私信發送失敗
                    return false;
                }
            }
        }

        // 添加或更新私信list
        if(false == $data['list_id'] = $this->_addMessageList($data, $from_uid)) {
            $this->error = L('PUBLIC_PRIVATE_MESSAGE_SEND_FAIL');                   // 私信發送失敗
            return false;
        }

        // 存儲私信成員
        if(false === $this->_addMessageMember($data, $from_uid)) {
            $this->error = L('PUBLIC_PRIVATE_MESSAGE_SEND_FAIL');                   // 私信發送失敗
            return false;
        }

        // 存儲內容
        if(false == $this->_addMessage($data, $from_uid)) {
            $this->error = L('PUBLIC_PRIVATE_MESSAGE_SEND_FAIL');                  // 私信發送失敗
            return false;
        }

        $author = model('User')->getUserInfo($from_uid);
        $config['name'] = $author['uname'];
        $config['space_url'] = $author['space_url'];
        $config['face'] = $author['avatar_small'];
        $config['content'] = $data['content'];
        $config['ctime'] = date('Y-m-d H:i:s',$data['mtime']);
        $config['source_url'] = U('public/Message/index');
        foreach($data['to'] as $uid) {
            model('Notify')->sendNotify($uid, 'new_message', $config);
        }

        return $data['list_id'];
    }

    /**
     * 回覆私信
     * @param integer $list_id 回覆的私信list_id
     * @param string $content 回覆內容
     * @param integer $from_uid 回覆者ID
     * @return mix 回覆失敗返回false，回覆成功返回本條新回覆的message_id
     */
    public function replyMessage($list_id, $content, $from_uid) {
        $list_id = intval($list_id);
        $from_uid = intval($from_uid);
        $time = time();

        // 獲取當前私信列表list的type、min_max
        $list_map['list_id'] = $list_id;
        $list_info = D('message_list')->field('type,member_num,min_max')->where($list_map)->find();
        if(!in_array($list_info['type'], $this->reversible_type)) {
            return false;
        } else if (!$this->isMember($list_id, $from_uid, false)) {
            return false;
        }

        // 添加新記錄
        $data['list_id'] = $list_id;
        $data['content'] = $content;
        $data['mtime'] = $time;
        $new_message_id = $this->_addMessage($data, $from_uid);
        unset($data);

        if(!$new_message_id) {
            return false;
        } else {
            $list_data['list_id'] = $list_id;
            $list_data['last_message'] = serialize(array('from_uid'=>$from_uid, 'content'=>t($content)));
            if(1 == $list_info['type']) {
                // 一對一
                $list_data['member_num'] = 2;
                // 重置最新記錄
                D('message_list')->save($list_data);
                // 重置其他成員資訊
                if($list_info['member_num'] < 2) {
                    $member_data = array(
                        'list_id' => $list_id,
                        'member' => array_diff(explode('_', $list_info['min_max']), array($from_uid)),
                        'mtime' => $time
                    );
                    $this->_addMessageMember($member_data, $from_uid);
                } else {
                    // 重置其他成員資訊
                    $member_data['new'] = 2;
                    $member_data['message_num'] = array('exp', '`message_num`+1');
                    $member_data['list_ctime'] = $time;
                    D('message_member')->where("`list_id`={$list_id} AND `member_uid`!={$from_uid}")->save($member_data);
                }
            } else {
                // 多人
                // 重置最新記錄
                D('message_list')->save($list_data);
                // 重置其他成員資訊
                $member_data['new'] = 2;
                $member_data['message_num'] = array('exp', '`message_num`+1');
                $member_data['list_ctime'] = $time;
                D('message_member')->where("`list_id`={$list_id} AND `member_uid`!={$from_uid}")->save($member_data);
            }
            // 重置回覆者的成員資訊
            $from_data['message_num'] = array('exp', '`message_num`+1');
            $from_data['ctime'] = $time;
            $from_data['list_ctime'] = $time;
            D('message_member')->where("`list_id`={$list_id} AND `member_uid`={$from_uid}")->save($from_data);
            unset($from_data);
        }

        return $new_message_id;
    }

    /**
     * 設定指定使用者指定私信為已讀
     * @param array $list_ids 私信列表ID陣列
     * @param [type] $member_uid 成員使用者ID
     * @param integer val 要設定的值
     * @return boolean 是否設定成功
     */
    public function setMessageIsRead($list_ids = null, $member_uid, $val=0) {
        if(!$member_uid) {
            return false;
        }
        if(!empty($list_ids)) {
            !is_array($list_ids) && $list_ids = explode(',', $list_ids);
            $map['list_id'] = array('IN', $list_ids);
        }else{
            $map['new'] = 2;
        }
        $map['member_uid'] = intval($member_uid);
        return false !== D('message_member')->where($map)->setField('new', $val);
    }

    /**
     * 設定指定使用者所有的私信為已讀
     * @param integer $member_uid 使用者ID
     * @return boolean 是否設定成功
     */
    public function setAllIsRead($member_uid) {
        $member_uid = intval($member_uid);
        if($member_uid <= 0) {
            return false;
        }

        $map['member_uid'] = $member_uid;
        return $this->where($map)->setField('new', 0);
    }

    /**
     * 指定使用者刪除指定的私信列表
     * @param integer $member_uid 使用者ID
     * @param array $list_ids 私信列表ID
     * @return boolean 是否刪除成功
     */
    public function deleteMessageByListId($member_uid, $list_ids) {
        if(!$list_ids || !$member_uid) {
            return false;
        }

        $member_map['list_id'] = array('IN', $list_ids);
        $member_map['member_uid'] = intval($member_uid);
        $save['message_num'] = 0;
        if(false == D('')->table($this->tablePrefix.'message_member')->where($member_map)->save($save)) {
            $this->error = L('PUBLIC_ADMIN_OPRETING_ERROR');               // 操作失敗
            return false;
        }

        return true;
    }

    /**
     * 直接刪除私信列表，管理員操作
     * @param array $list_ids 私信列表ID陣列
     * @return boolean 是否刪除成功
     */
    public function deleteMessageList($list_ids) {
        if(!$list_ids) {
            return false;
        }
        $map['list_id'] = array('IN', $list_ids);
        return false !== D('message_content')->where($map)->delete()
            && false !== D('message_member')->where($map)->delete()
            && false !== D('message_list')->where($map)->delete();
    }

    /**
     * 指定使用者刪除指定會話
     * @param integer $member_uid 使用者ID
     * @param array $message_ids 會話ID陣列
     * @return boolean 是否刪除成功
     */
    public function deleteSessionById($member_uid, $message_ids) {
        $message_ids = intval($message_ids);
        $member_uid  = intval($member_uid);
        if(!$message_ids || !$member_uid) {
            return false;
        }
        $where = "`message_id`={$message_ids}";
        $list = D('message_content')->field('`list_id`')->where($where)->find();
        if(false === D('message_content')->where($where." AND `is_del` > 0 AND `is_del`!={$member_uid}")->delete()
            || false === D('message_content')->setField('is_del', $member_uid, $where.' AND `is_del`=0')) {
                return false;
            } else {
                $member_map['list_id'] = $list['list_id'];
                $member_map['member_uid'] = $member_uid;
                $res = D('message_member')->setDec('message_num', $member_map);
            }

        return $res;
    }

    /**
     * 直接刪除會話操作，管理員操作
     * @param array $message_ids 會話ID陣列
     * @return boolean 是否刪除成功
     */
    public function deleteSessionByAdmin($message_ids) {
        $message_ids = intval($message_ids);
        if(!$message_ids) {
            return false;
        }
        $content_map['message_id'] = $message_ids;
        $list = D('message_content')->field('`list_id`')->where($content_map)->find();
        if(false == D('message_content')->where($content_map)->delete()) {
            return false;
        } else {
            $member_map['list_id'] = $list['list_id'];
            $res = D('message_member')->setDec('message_num', $member_map);
        }

        return $res;
    }

    /**
     * 獲取指定私信列表中的成員資訊
     * @param integer $list_id 私信列表ID
     * @param string $field 私信成員表中的欄位
     * @return array 指定私信列表中的成員資訊
     */
    public function getMessageMembers($list_id, $field = null) {
        $list_id = intval($list_id);
        static $_members = array();

        if(!isset($_members[$list_id])) {
            $_members[$list_id] = D('message_member')->field($field)->where("`list_id`={$list_id}")->findAll();
            foreach ($_members[$list_id] as $_m_l_k => $_m_l_v) {
                $_members[$list_id][$_m_l_k]['user_info'] = model('User')->getUserInfo($_m_l_v['member_uid']);
            }
        }

        return $_members[$list_id];
    }

    /**
     * 驗證指定使用者是否是指定私信列表的成員
     * @param integer $list_id 私信列表ID
     * @param integer $uid 使用者ID
     * @param boolean $show_detail 是否顯示詳細，默認為false
     * @return array 如果是成員返回相關資訊，不是則返回空陣列
     */
    public function isMember($list_id, $uid, $show_detail = false) {
        $list_id = intval($list_id);
        $uid = intval($uid);
        $show_detail = $show_detail ? 1 : 0;
        static $_is_member = array();

        if (!isset($_is_member[$list_id][$uid][$show_detail])) {
            $map['list_id']    = $list_id;
            $map['member_uid'] = $uid;
            if ($show_detail) {
                $_is_member[$list_id][$uid][$show_detail] = D('message_member')->where($map)->find();
            } else {
                $_is_member[$list_id][$uid][$show_detail] = D('message_member')->getField('member_uid', $map);
            }
        }

        return $_is_member[$list_id][$uid][$show_detail];
    }

    /**
     * 添加新的私信列表
     * @param array $data 私信列表相關資料
     * @param integer $from_uid 釋出人ID
     * @return mix 添加失敗返回false，成功返回新的私信列表ID
     */
    private function _addMessageList($data, $from_uid) {
        if (!$data['content'] || !is_array($data['member']) || !$from_uid) {
            return false;
        }

        $list['from_uid'] = $from_uid;
        $list['title'] = $data['title'] ? t($data['title']) : t(getShort($data['content'], 20));
        $list['member_num'] = count($data['member']);
        $list['type'] = is_numeric($data['type']) ? $data['type'] : (2 == $list['member_num'] ? 1 : 2);
        $list['min_max'] = $this->_getUidMinMax($data['member']);
        $list['mtime'] = $data['mtime'];
        $list['last_message'] = serialize(array(
            'from_uid' => $from_uid,
            'content' => h($data['content'])
        ));

        $list_map['type'] = $list['type'];
        $list_map['min_max'] = $list['min_max'];
        if(1 == $list['type'] && $list_id = D('message_list')->getField('list_id', $list_map)) {
            $list_map['list_id'] = $list_id;
            $_list['member_num'] = $list['member_num'];
            $_list['last_message'] = $list['last_message'];
            false === D('message_list')->where($list_map)->data($_list)->save() && $list_id = false;
        } else {
            $list_id = D('message_list')->data($list)->add();
        }

        return $list_id;
    }

    /**
     * 添加私信列表的成員
     * @param array $data 添加私信成員相關資訊；私信列表ID：list_id，私信成員ID陣列：member，當前時間：mtime
     * @param integer $from_uid 釋出人ID
     * @return mix 添加成功返回新的私信成員表ID，添加失敗返回false
     */
    private function _addMessageMember($data, $from_uid) {
        if(!$data['list_id'] || !is_array($data['member']) || !$from_uid) {
            return false;
        }

        $member['list_id'] = $data['list_id'];
        $member['list_ctime'] = $data['mtime'];

        foreach($data['member'] as $k => $m) {
            $map['list_id'] = $data['list_id'];
            $map['member_uid'] = $m;
            $memberInfo = D('')->table($this->tablePrefix.'message_member')->where($map)->find();
            if(!empty($memberInfo)) {
                $member['ctime'] = $memberInfo['ctime'];
                $member['new'] = $m == $from_uid ? $memberInfo['new'] : 2;
                $member['message_num'] = $memberInfo['message_num'] + 1;
                D('')->table($this->tablePrefix.'message_member')->where($map)->save($member);
            } else {
                $member['ctime'] = $m == $from_uid ? time() : 0;
                $member['new'] = $m == $from_uid ? 0 : 2;
                $member['message_num'] = 1;
                $member['member_uid'] = $m;
                D('')->table($this->tablePrefix.'message_member')->add($member);
            }
        }
    }

    /**
     * 添加會話
     * @param array $data 會話相關資料
     * @param integer $from_uid 釋出人ID
     * @return mix 添加失敗返回false，添加成功返回新的會話ID
     */
    private function _addMessage($data, $from_uid) {
        if (!$data['list_id'] || !$data['content'] || !$from_uid) {
            return false;
        }
        $message['list_id']  = $data['list_id'];
        $message['from_uid'] = $from_uid;
        $message['content']  = $data['content'];
        $message['is_del']   = 0;
        $message['mtime']    = $data['mtime'];
        return D('message_content')->data($message)->add();
    }

    /**
     * 輸出從小到大用“_”連線的字元串
     * @param array $uids 使用者ID陣列
     * @return string 從小到大用“_”連線的字元串
     */
    private function _getUidMinMax($uids) {
        sort($uids);
        return implode('_', $uids);
    }

    /**
     * 格式化使用者陣列，去除指定使用者
     * @param string $min_max_uids 從小到大用“_”的使用者ID字元串
     * @param integer $from_uid 指定使用者ID
     * @return array 使用者陣列，去除指定使用者
     */
    private function _parseToUidByMinMax($min_max_uids, $from_uid) {
        $min_max_uids = explode('_', $min_max_uids);
        // 去除當前使用者UID
        return array_values(array_diff($min_max_uids, array($from_uid)));
    }

    /**
     * 編輯會話，徹底刪除，假刪除，恢復
     * @param integer $message_id 會話ID
     * @param string $type 操作類型，徹底刪除：deleteMessage，假刪除：delMessage，恢復：其他字元串
     * @param string $title 日誌內容，功能待完成
     * @return array 返回操作後的資訊資料
     */
    public function doEditMessage($message_id,$type,$title){
        $return = array('status'=>'0','data'=>L('PUBLIC_ADMIN_OPRETING_ERROR'));            // 操作失敗
        if(empty($message_id)) {
            $return['data'] = L('PUBLIC_WRONG_DATA');           // 錯誤的參數
        } else {
            $map['message_id'] = is_array($message_id) ? array('IN', $message_id) : intval($message_id);
            $save['is_del'] = $type =='delMessage' ? 1 : 0;
            if($type == 'deleteMessage') {
                // 徹底刪除操作
                $res = $this->where($map)->delete();
            } else {
                // 刪除或者恢復
                $res = $this->where($map)->save($save);
                $this->_afterDeleteMessage($message_id);
            }
            if($res) {
                // TODO:是否記錄日誌,以及後期快取處理
                $return = array('status'=>1,'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
            }
        }

        return $return;
    }

    /**
     * 刪除私信後的資料處理操作
     * @param integer|array $message_id 刪除的私信ID
     * @return void
     */
    private function _afterDeleteMessage($message_id)
    {
        if(empty($message_id)) {
            return false;
        }
        // 獲取刪除私信陣列
        $message_id = is_array($message_id) ? $message_id : explode(',', $message_id);
        $map['message_id'] = array('IN', $message_id);
        $list_id = $this->where($map)->findAll();
        $list_id = getSubByKey($list_id, 'list_id');
        $list_id = array_filter($list_id);
        $list_id = array_unique($list_id);
        foreach($list_id as $value) {
            // 重新整理資料
            $count = $this->where('list_id='.$value.' AND is_del=0')->count();
            D('message_member')->where('list_id='.$value)->setField('message_num', $count);
            // 更新最後的資料
            $last_message = $this->where('list_id='.$value.' AND is_del=0')->order('message_id DESC')->find();
            $last_message = serialize($last_message);
            D('message_list')->where('list_id='.$value)->setField('last_message', $last_message);
        }
    }

    /**
     * 獲取指定私信列表，指定結果集的最早會話ID，用於動態載入
     * @param integer $list_id 私信列表ID
     * @param integer $nums 結果集數目
     * @return integer 最早會話ID
     */
    public function getSinceMessageId($list_id, $nums) {
        $map['list_id'] = $list_id;
        $map['is_del'] = 0;
        $info = $this->where($map)->order('message_id DESC')->field('message_id')->limit($nums)->findAll();
        if($nums > 0) {
            return intval($info[$nums - 1]['message_id'] - 1);
        } else {
            return 0;
        }
    }

    /*** API使用 ***/
    /**
     * 私信列表，API專用
     * @param integer $uid 使用者ID
     * @param string $type all:全部訊息,is_read:閱讀過的,is_unread:為閱讀  默認'all'
     * @param integer $since_id 範圍起始ID，默認0
     * @param integer $max_id 範圍結束ID，默認0
     * @param integer $count 單頁讀取條數，默認20
     * @param integer $page 頁碼，默認1
     * @param string $order 排序，默認以訊息ID倒敘排列
     * @return array 私信列表資料
     */
    public function getMessageListByUidForAPI($uid, $type = 1, $since_id = 0, $max_id = 0, $count = 20, $page = 1, $order = '`mb`.`new` DESC,`mb`.`list_id` DESC') {
        $uid = intval($uid);
        $type = is_array($type) ? ' IN ('.implode(',', $type).')' : "={$type}";
        $where = "`mb`.`member_uid`={$uid} AND `li`.`type`{$type} AND mb.is_del = 0";
        if($since_id) {
            $where .= "  AND `li`.`list_id`>{$since_id}";
        }
        if($max_id) {
            $where .= "  AND `li`.`list_id`<{$max_id}";
        }
        $limit = ($page - 1) * $count.','.$count;
        $list = D('message_member')->table("`{$this->tablePrefix}message_member` AS `mb`")
            ->join("`{$this->tablePrefix}message_list` AS `li` ON `mb`.`list_id`=`li`.`list_id`")
            ->where($where)
            ->order('`mb`.`new` DESC,`mb`.`list_id` DESC')
            ->limit($limit)
            ->findAll();
        $this->_parseMessageList($list, $uid); // 引用
        foreach ($list as &$_l) {
            $_l['from_uid'] = $_l['last_message']['from_uid'];
            $_l['content']  = $_l['last_message']['content'];
            $_l['mtime']    = $_l['list_ctime'];
        }

        return $list;
        }

        /**
         * 未讀私信列表，API專用
         * @param integer $uid 使用者ID
         * @param string $type all:全部訊息,is_read:閱讀過的,is_unread:為閱讀  默認'all'
         * @param integer $since_id 範圍起始ID，默認0
         * @param integer $max_id 範圍結束ID，默認0
         * @param integer $count 單頁讀取條數，默認20
         * @param integer $page 頁碼，默認1
         * @param string $order 排序，默認以訊息ID倒敘排列
         * @return array 未讀私信列表資料
         */
        public function getMessageListByUidForAPIUnread($uid, $type = 1, $since_id = 0, $max_id = 0, $count = 20, $page = 1, $order = '`mb`.`new` DESC,`mb`.`list_id` DESC') {
            $uid = intval($uid);
            $type = is_array($type) ? ' IN ('.implode(',', $type).')' : "={$type}";
            if($since_id) {
                $since_id = " `li`.`list_id`>{$since_id}";
        }
        if($max_id) {
            $max_id = " `li`.`list_id`<{$max_id}";
        }
        $limit = ($page - 1) * $count.','.$count;
        $list = D('message_member')->table("`{$this->tablePrefix}message_member` AS `mb`")
            ->join("`{$this->tablePrefix}message_list` AS `li` ON `mb`.`list_id`=`li`.`list_id`")
            ->where("`mb`.`member_uid`={$uid} AND `li`.`type`{$type} AND `mb`.`new`> 0 AND mb.is_del=0")
            ->order('`mb`.`list_id` DESC')
            ->limit($limit)
            ->findAll();
        $this->_parseMessageList($list, $uid); // 引用
        foreach($list as &$_l) {
            $_l['from_uid'] = $_l['last_message']['from_uid'];
            $_l['content'] = $_l['last_message']['content'];
            $_l['mtime'] = $_l['list_ctime'];
        }

        return $list;
        }

        /**
         * 獲取使用者的最後一條私信，API專用
         * @param integer 使用者UID
         * @return array 使用者的最後一條私信資料
         */
        public function getLastMessageByUidForAPI($uid) {
            $sql = "SELECT a.*,b.* FROM {$this->tablePrefix}message_member AS a LEFT JOIN {$this->tablePrefix}message_list AS b ON a.list_id = b.list_id
                WHERE a.member_uid = {$uid} AND a.new > 0 ORDER BY a.list_ctime DESC";
            $data = $this->findPageBySql($sql);

            return $data;
        }

        }
