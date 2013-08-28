
<?php
/**
 * 線上統計模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class OnlineModel{

    private $today = 0;                     // 今日日期字元串
    private $todayTimestamp = 0;            // 今日0點的時間戳
    private $check_point = 0;               // 查詢線上起始時間點的時間戳
    private $check_step = 1200;             // 檢查線上使用者步長，10分鐘檢查一次
    private $stats_step = 1800;             // 統計線上使用者步長，30分鐘
    /**
     * 初始化方法，資料庫配置、連線初始化
     * @return void
     */
    public function __construct() {
        $dbconfig = array();
        $_config = C('ONLINE_DB');
        $dbconfig['DB_TYPE'] = isset($_config['DB_TYPE']) ? $_config['DB_TYPE'] : C('DB_TYPE');
        $dbconfig['DB_HOST'] = isset($_config['DB_HOST']) ? $_config['DB_HOST'] : C('DB_HOST');
        $dbconfig['DB_NAME'] = isset($_config['DB_NAME']) ? $_config['DB_NAME'] : C('DB_NAME');
        $dbconfig['DB_USER'] = isset($_config['DB_USER']) ? $_config['DB_USER'] : C('DB_USER');
        $dbconfig['DB_PWD'] = isset($_config['DB_PWD']) ? $_config['DB_PWD'] : C('DB_PWD');
        $dbconfig['DB_PORT'] = isset($_config['DB_PORT']) ? $_config['DB_PORT'] : C('DB_PORT');
        $dbconfig['DB_PREFIX'] = isset($_config['DB_PREFIX']) ? $_config['DB_PREFIX'] : C('DB_PREFIX');
        $dbconfig['DB_CHARSET'] = isset($_config['DB_CHARSET']) ? $_config['DB_CHARSET'] : C('DB_CHARSET');

        $db_pwd = $dbconfig['DB_PWD'];

        if($dbconfig['DB_ENCRYPT'] == 1) {
            if($db_pwd != '') {
                require_once(SITE_PATH.'/addons/library/CryptDES.php');
                $crypt = new CryptDES;
                $db_pwd = (string)$crypt->decrypt($db_pwd);
            }
        }
        // 重設Service的資料連線資訊
        $connection = array(
            'dbms' => $dbconfig['DB_TYPE'],
            'hostname' => $dbconfig['DB_HOST'],
            'hostport' => $dbconfig['DB_PORT'],
            'database' => $dbconfig['DB_NAME'],
            'username' => $dbconfig['DB_USER'],
            'password' => $db_pwd,
        );
        // 例項化Online資料庫連線
        $this->odb = new Db($connection);
        $this->today = date('Y-m-d');
        $this->todayTimestamp = strtotime(date('Y-m-d'));
    }

    /**
     * 獲取統計列表
     * @param string $where 查詢條件
     * @param integer $limit 結果集數目，默認為30
     * @return array 統計列表資料
     */
    public function getStatsList($where = '1',$limit = 30) {
        $p = $_REQUEST['p'] ? $_REQUEST['p'] : 1;
        $start = ($p - 1) * $limit;
        $sqlCount = "SELECT COUNT(1) as count FROM ".C('DB_PREFIX')."online_stats WHERE {$where} ";
        if($count = $this->odb->query($sqlCount)) {
            $count = $count[0]['count'];
        } else {
            $count = 0;
        }

        $sql = "SELECT * FROM ".C('DB_PREFIX')."online_stats WHERE {$where} LIMIT $start,$limit";

        $data = $this->odb->query($sql);

        $p = new Page($count, $limit);
        $output['count'] =$count;
        $output['totalPages'] = $p->totalPages;
        $output['totalRows'] = $p->totalRows;
        $output['nowPage'] = $p->nowPage;
        $output['html'] = $p->show();
        $output['data'] = $data;

        return $output;
    }

    /**
     * 執行統計
     * @return void
     */
    public function dostatus() {
        // 獲取最大ID
        $sql = 'SELECT MAX(id) AS max_id FROM '.C('DB_PREFIX').'online_logs WHERE statsed = 0';
        $max_id = $this->odb->query($sql);
        $max_id = @$max_id['0']['max_id'];
        if(empty($max_id)) {
            return false;
        } else {
            $sql = "UPDATE ".C('DB_PREFIX')."online_logs SET statsed = 1 WHERE id < {$max_id}";
            $this->odb->execute($sql);
        }
        // 開始統計
        // TODO:需要計劃任務支援移動上一日資料到備份表，現在在每次統計之後備份今天之前的資料到備份表
        // 從logs累計總的使用者數，總的遊客數到stats表
        $userDataSql = "SELECT COUNT(1) AS pv, COUNT(DISTINCT uid) AS pu, COUNT(ip) AS guestpu, `day`, isGuest
            FROM `".C('DB_PREFIX')."online_logs`
            WHERE id <= {$max_id}
            GROUP BY day, isGuest";

        $userData = $this->odb->query($userDataSql);

        if(!empty($userData)) {
            $upData = array();
            foreach($userData as $v) {
                if($v['isGuest'] == 0) {
                    // 註冊使用者
                    $upData[$v['day']]['total_users'] = $v['pu'];
                    $upData[$v['day']]['total_pageviews'] += $v['pv'];
                } else {
                    // 遊客
                    $upData[$v['day']]['total_guests'] = $v['guestpu'];
                    $upData[$v['day']]['total_pageviews'] += $v['pv'];
                }
            }
            foreach($upData as $k=>$v) {
                $sql = "SELECT id FROM ".C('DB_PREFIX')."online_stats WHERE day = '{$k}'";
                $issetRow  = $this->odb->query($sql);
                if(empty($issetRow)) {
                    $sql = "INSERT INTO ".C('DB_PREFIX')."online_stats (`day`,`total_users`,`total_guests`,`total_pageviews`)
                        VALUES ('{$k}','{$v['total_users']}','{$v['total_guests']}','{$v['total_pageviews']}')";
                } else {
                    $sql = " UPDATE ".C('DB_PREFIX')."online_stats
                        SET total_users = '{$v['total_users']}',
                        total_guests = '{$v['total_guests']}',
                        total_pageviews = {$v['total_pageviews']}
                        WHERE day = '{$k}'";
                }
                $this->odb->execute($sql);
            }
        }

        // 從online表統計線上使用者到most_onine_user表
        $this->checkOnline();
        // 將logs表中今天之前的的資料移動到bak表
        $sql = "INSERT INTO ".C('DB_PREFIX')."online_logs_bak SELECT * FROM `".C('DB_PREFIX')."online_logs` WHERE day <='".date('Y-m-d', strtotime('-1 day'))."'";
        $this->odb->execute($sql);
        // 刪除logs表中今天之前的資料刪除
        $sql = " DELETE FROM `".C('DB_PREFIX')."online_logs` WHERE day <='".date('Y-m-d', strtotime('-1 day'))."'";
        // 統計結束
        $this->odb->execute($sql);
    }

    /**
     * 線上使用者檢查及入庫
     * @return void
     */
    public function checkOnline() {
        $startTime = time() - $this->stats_step;
        $day = date('Y-m-d');
        // 今日統計資料
        $sql = "SELECT * FROM ".C('DB_PREFIX')."online_stats WHERE day ='{$day}'";
        $dayData =  $this->odb->query($sql);

        if(!empty($dayData)) {
            // 線上註冊使用者
            $sql = "SELECT COUNT(1) AS pu FROM ".C('DB_PREFIX')."online WHERE uid !=0 AND activeTime  >= {$startTime}";
            $onlineData = $this->odb->query($sql);

            $set = array();
            if($onlineData && $onlineData[0]['pu'] > 0 && $onlineData[0]['pu'] > $dayData[0]['most_online_users']) {
                $set[] = 'most_online_users = '.$onlineData[0]['pu'];
        }
        // 線上遊客
        $sql = "SELECT COUNT(ip) AS pu FROM ".C('DB_PREFIX')."online WHERE uid = 0 AND activeTime >= {$startTime}";
        $onlineGuestData = $this->odb->query($sql);
        if($onlineGuestData && $onlineGuestData[0]['pu'] > 0 && $onlineGuestData[0]['pu'] > $dayData[0]['most_online_guests']) {
            $set[] = ' most_online_guests = '.$onlineGuestData[0]['pu'];
        }
        $mostUser = intval($onlineData[0]['pu']) + intval($onlineGuestData[0]['pu']);
        if(empty($mostUser)) {
            $mostUser = 1;
        }
        if($mostUser > $dayData[0]['most_online']) {
            $set[] = ' most_online = '.$mostUser;
            $set[] = ' most_online_time  ='.time();
        }

        if(!empty($set)) {
            $sql = " UPDATE ".C('DB_PREFIX')."online_stats SET ".implode(',', $set)." WHERE day = '{$day}'";
            $this->odb->execute($sql);
        }
        }
        }

        /**
         * 獲取指定使用者最後操作的IP地址資訊
         * @param array $uids 指定使用者ID陣列
         * @return array 指定使用者最後操作的IP地址資訊
         */
        public function getLastOnlineInfo($uids) {
            $map['uid'] = array('IN', $uids);
            $data = D()->table(C('DB_PREFIX').'online')->where($map)->getHashList('uid', 'ip');
            return $data;
        }

        /**
         * 獲取指定使用者的操作日誌 - 分頁型
         * @param integer $uid 使用者ID
         * @param array $map 查詢條件
         * @param integer $count 結果集數目，默認為20
         * @param string $order 排序條件，默認為day DESC
         * @return array 指定使用者的操作日誌 - 分頁型
         */
        public function getUserOperatingList($uid, $map, $count = 20, $order = 'id DESC') {
            $map['uid'] = $uid;
            $data = D()->table(C('DB_PREFIX').'online_logs_bak')->where($map)->order($order)->findPage($count);
            return $data;
        }
        }
