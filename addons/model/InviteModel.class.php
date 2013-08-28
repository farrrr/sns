<?php
/**
 * 邀請模型 - 資料物件模型
 * @author nonant
 * @version TS3.0
 */
class InviteModel extends Model
{
    protected $tableName = 'invite_code';           // 邀請碼資料表
    private $_email_reg = '/[_a-zA-Z\d\-\.]+(@[_a-zA-Z\d\-\.]+\.[_a-zA-Z\d\-]+)+$/i';           // 郵件正則規則

    /**
     * 生成邀請碼
     * @param integer $uid 使用者ID
     * @param string $type 邀請碼類型
     * @param integer $num 邀請碼數量，默認為5
     * @param boolean $isAdmin 是否為管理員邀請操作，默認為false
     * @return boolean|string 成功返回邀請碼，失敗返回false
     */
    public function createInviteCode($uid, $type, $num = 5, $isAdmin = false)
    {
        $adminVal = $isAdmin ? 1 : 0;
        // 驗證資料
        if(empty($uid) || empty($num) || empty($type)) {
            return false;
        }
        // 邀請碼陣列
        $inviteCodes = array();
        // 生成邀請碼清單
        $codes = array();
        for($i = 1; $i <= $num; $i++) {
            $inviteCode = tsmd5($uid.microtime(true).rand(1111,9999).$i);
            $inviteCodes[] = $inviteCode;
            $codes[] = "($uid, '$inviteCode', 0, '$type', $adminVal)";
        }
        // 插入資料庫
        if(!empty($codes)) {
            $sql = "INSERT INTO {$this->tablePrefix}{$this->tableName} (`inviter_uid`, `code`, `is_used`, `type`, `is_admin`) VALUES ".implode(',', $codes);
            $this->execute($sql);
            return $inviteCodes;
        }

        return false;
    }

    /**
     * 獲取指定使用者的邀請碼列表 - 連結邀請使用
     * @param integer $uid 使用者ID
     * @param string $type 邀請碼類型
     * @return array 指定使用者的邀請碼列表
     */
    public function getInviteCode($uid, $type)
    {
        $map['inviter_uid'] = $uid;
        $map['type'] = $type;
        $map['is_admin'] = 0;
        $result = $this->where($map)->findAll();
        // 如果資料表中沒有資訊，將初始化添加邀請碼
        if(empty($result)) {
            $conf = model('Xdata')->get('admin_Config:invite');
            $this->createInviteCode($uid, $type, $conf['send_link_num']);
        }
        $map['is_used'] = 0;
        $list = $this->where($map)->findAll();

        return $list;
    }

    /**
     * 獲取後臺邀請碼列表
     * @param string $type 邀請碼類型
     * @return array 後臺邀請碼列表
     */
    public function getAdminInviteCode($type)
    {
        $map['type'] = $type;
        $map['is_admin'] = 1;
        $map['is_used'] = 0;
        $list = $this->where($map)->findPage();

        return $list;
    }

    /**
     * 設定指定驗證碼已被使用
     * @param string $code 驗證碼
     * @param array $receiverInfo 邀請人使用者資訊
     * @return boolean 設定是否成功
     */
    public function setInviteCodeUsed($code, $receiverInfo)
    {
        $map['code'] = $code;
        $data['is_used'] = 1;
        $data['receiver_uid'] = $receiverInfo['uid'];
        $data['receiver_email'] = $receiverInfo['email'];
        $data['ctime'] = time();
        $result = $this->where($map)->save($data);
        return (boolean)$result;
    }

    /**
     * 獲取指定邀請碼的相關資訊
     * @param string $code 邀請碼
     * @return array 指定邀請碼的相關資訊
     */
    public function getInviteCodeInfo($code)
    {
        $map['code'] = $code;
        $result = $this->where($map)->find();
        return $result;
    }

    /**
     * 獲取指定使用者可用的邀請碼個數
     * @param integer $uid 使用者ID
     * @param string $type 邀請碼類型，email或者link
     * @return integer 指定使用者可用的邀請碼個數
     */
    public function getAvailableCodeCount($uid, $type)
    {
        $map['inviter_uid'] = $uid;
        $type == 'link' && $map['is_used'] = 0;
        $map['type'] = $type;
        $map['is_admin'] = 0;
        $count = $this->where($map)->count();
        if($type == 'email') {
            $conf = model('Xdata')->get('admin_Config:invite');
            $count = $conf['send_email_num'] - $count;
            $count < 0 && $count = 0;
        }
        return $count;
    }

    /**
     * 檢驗驗證碼是否可用
     * @param string $code 驗證碼
     * @param string $type 註冊類型
     * @return integer 邀請碼使用情況，0：邀請碼不存在，1：邀請碼可用，2：邀請碼已被使用
     */
    public function checkInviteCode($code, $type)
    {
        $map['code'] = $code;
        $type == 'admin' && $map['is_admin'] = 1;
        $isUsed = $this->where($map)->getField('is_used');
        $result = 0;
        if(!is_null($isUsed)) {
            $result = ($isUsed === '0') ? 1 : 2;
        }
        return $result;
    }

    /**
     * 通過邀請碼獲取邀請人相關資訊
     * @param string $code 邀請碼
     * @return array 獲取邀請人相關資訊
     */
    public function getInviterInfoByCode($code)
    {
        if(empty($code)) {
            return array();
        }
        $inviterUid = $this->where("code='{$code}'")->getField('inviter_uid');
        $inviterInfo = model('User')->getUserInfo($inviterUid);

        return $inviterInfo;
    }

    /**
     * 獲取指定使用者所邀請的使用者列表
     * @param integer $uid 使用者ID
     * @param array $type 邀請類型
     * @param boolean $isAdmin 是否為管理員操作，默認為false
     * @return array 指定使用者所邀請的使用者列表
     */
    public function getInviteUserList($uid, $type, $isAdmin = false)
    {
        $map['c.inviter_uid'] = $uid;
        $map['c.is_used'] = 1;
        !empty($type) && $map['c.type'] = $type;
        $map['c.is_admin'] = $isAdmin ? 1 : 0;
        $map['u.is_init'] = 1;
        $list = D()->table('`'.$this->tablePrefix.'invite_code` AS c LEFT JOIN `'.$this->tablePrefix.'user` AS u ON c.receiver_uid = u.uid')
            ->field('c.*')
            ->where($map)
            ->order('invite_code_id DESC')
            ->findPage(20);
        $uids = getSubByKey($list['data'], 'receiver_uid');
        $userInfos = model('User')->getUserInfoByUids($uids);
        foreach($list['data'] as &$value) {
            $value = array_merge($value, $userInfos[$value['receiver_uid']]);
        }

        return $list;
    }

    /**
     * 獲取指定使用者所邀請的使用者列表
     * @param array $type 邀請類型
     * @param boolean $isAdmin 是否為管理員操作，默認為false
     * @return array 指定使用者所邀請的使用者列表
     */
    public function getInviteAdminUserList($type)
    {
        $map['c.is_used'] = 1;
        !empty($type) && $map['c.type'] = $type;
        $map['c.is_admin'] = 1;
        $map['u.is_init'] = 1;
        $list = D()->table('`'.C('DB_PREFIX').'invite_code` AS c LEFT JOIN `'.C('DB_PREFIX').'user` AS u ON c.receiver_uid = u.uid')
            ->field('c.*')
            ->where($map)
            ->order('invite_code_id DESC')
            ->findPage(20);
        $uids = getSubByKey($list['data'], 'receiver_uid');
        $userInfos = model('User')->getUserInfoByUids($uids);
        foreach($list['data'] as &$value) {
            $value = array_merge($value, $userInfos[$value['receiver_uid']]);
        }

        return $list;
    }

    /**
     * 郵件邀請註冊
     * @param array $email 被邀請人郵箱陣列
     * @param string $detail 邀請相關資訊
     * @param integer $uid 邀請人ID
     * @param boolean $isAdmin 是否為管理員邀請操作，默認為false
     * @return boolean 是否發送邀請成功
     */
    public function doInvite($email, $detail, $uid, $isAdmin = false)
    {
        // 判斷是否能進行邀請
        if(!$isAdmin) {
            $count = $this->getAvailableCodeCount($uid, 'email');
            // 扣除積分
            if($count == 0) {
                $stauts = $this->applyInviteCode($uid, 'email');
                if(!$stauts) {
                    $this->error = '財富值不足夠，不能進行邀請';
                    return false;
                }
            }
        }
        // 格式化資料
        $email = is_array($email) ? $email : explode(',', $email);
        // 獲取邀請人相關資料
        $userInfo = model('User')->getUserInfo($uid);
        // 設定配置資訊
        $config['name'] = $userInfo['uname'];
        $config['space_url'] = $userInfo['space_url'];
        $config['face'] = $userInfo['avatar_small'];
        $config['content'] = $detail;
        // 歷史郵箱資料
        $oldEmail = null;
        // 是否有可用郵箱
        $isEmail = false;
        // 郵箱內容驗證
        foreach($email as $k => $v) {
            // 若郵箱為空
            if(empty($v)) {
                unset($email[$k]);
                continue;
            }
            // 郵箱驗證
            $res = preg_match($this->_email_reg, $v, $matches) !== 0;
            if(!$res) {
                $this->error = L('PUBLIC_EMAIL_TIPS');              // 無效的Email地址
                return false;
            } else {
                $registerConf = model('Xdata')->get('admin_Config:register');
                if(!empty($registerConf['email_suffix'])) {
                    $res = in_array($matches[1], explode(',', $registerConf['email_suffix']));
                    if(!$res) {
                        $this->error = $matches['1'].L('PUBLIC_EMAIL_SUFFIX_FORBIDDEN');            // 郵箱字尾不允許註冊
                        return false;
                    }
                }
            }
            if($res && ($v != $oldEmail) && model('User')->where('`email`="'.mysql_escape_string($v).'"')->find()) {
                $this->error = L('PUBLIC_ACCOUNT_REGISTERED');          // 該使用者已註冊
                return false;
            }
            $isEmail = true;
        }

        if(!$isEmail) {
            $this->error = L('PUBLIC_INVITE_EMAIL_NOEMPTY');            // 邀請Email不能為空
            return false;
        }

        if(!$res) {
            $this->error = '';
            return false;
        }
        // 發送邀請郵件
        foreach($email as $k => $v) {
            $codes = $this->createInviteCode($uid, 'email', 1, $isAdmin);
            $key = $codes[0];
            $config['invateurl'] = SITE_URL.'/index.php?invite='.$key;
            $data = model('Notify')->getDataByNode('register_invate', $config);
            $notify['uid'] = 0;
            $notify['node'] = 'register_invate';
            $notify['email'] = $v;
            $notify['title'] = $data['title'];
            $notify['body'] = $data['body'];
            $notify['appname'] = 'public';
            model('Notify')->sendEmail($notify);
        }

        $this->error = L('PUBLIC_SEND_INVITE_SUCCESS');             // 發送邀請成功
        return true;
    }

    /**
     * 普通使用者獲取邀請碼操作
     * @param integer $uid 使用者ID
     * @param string $type 邀請碼類型
     * @return boolean 是否獲取邀請碼成功
     */
    public function applyInviteCode($uid, $type)
    {
        // 獲取後臺積分配置
        $creditRule = model('Credit')->getSetData();
        $applyCredit = abs($creditRule['core']['code']['score']);
        // 更新積分
        $userCredit = model('Credit')->getUserCredit($uid);
        $score = $userCredit['credit']['score']['value'];
        if($score < $applyCredit) {
            return false;
    } else {
        // 添加邀請碼操作
        $type == 'link' && $result = $this->createInviteCode($uid, $type, 1);
        // 扣除積分操作
        if($result || $type == 'email') {
            model('Credit')->setUserCredit($uid, 'core_code');
            return true;
    } else {
        return false;
    }
    }
    }

    /**
     * 獲取邀請結果列表，用於後臺 - 分頁型
     * @param array $map 查詢條件
     * @param integer $pageNums 結果集數目，默認為10
     * @return array 邀請結果列表
     */
    public function getPage($map = array(), $pageNums = 10)
    {
        $map['is_used'] = 1;
        $list = $this->where($map)->order('ctime DESC')->findPage($pageNums);

        return $list;
    }

    /**
     * 獲取邀請排行資訊
     * @param string $where 查詢條件
     * @param integer $pageNums 結果集數目，默認為20
     * @return array 邀請排行資訊
     */
    public function getTopPage($where = '', $pageNums = '20')
    {
        if(empty($where)) {
            $where = ' WHERE is_used = 1 ';
    } else {
        $where = ' WHERE is_used = 1 AND '.$where;
    }
    $sql = "SELECT inviter_uid, COUNT(receiver_uid) AS nums FROM ".$this->tablePrefix.$this->tableName." {$where} GROUP BY inviter_uid ";
    $count = $this->query("SELECT COUNT(1) AS nums FROM ({$sql}) a ");
    $count = $count[0]['nums'];
    $sql .=" ORDER BY COUNT(inviter_uid) DESC ";
    $list = $this->findPageBySql($sql, $count, $pageNums);

    return $list;
    }

    }
