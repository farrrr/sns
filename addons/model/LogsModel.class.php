<?php
/**
 * 日誌模型 - 資料物件模型
 * @example
 * load($type)                      鏈式指定類型
 * action($action)                  鏈式指定行為
 * record($content, $isAdminLog)    記錄日誌
 * get($map,$limit=30, $table)      獲取日誌
 * cleanLogs($m)                    清理m個月之前的日誌
 * getMenuList()                    獲取所有許可權節點列表
 * logsArchive()                    歸檔1個月之前的日誌
 * dellogs($id, $date)              刪除某張表中的某條記錄
 * getMenuList($app)                獲取應用下的日志節點
 * 請直接使用函數庫中的LogRecord($type, $action, $data, $isAdmin);進行日誌存儲
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class LogsModel extends Model {

    protected $tableName = 'x_logs';
    protected $fields = array('id','uid','uname','app_name','group','action','data','ctime','url','isAdmin','ip','keyword');

    public $option;             // 日誌配置欄位
    public $keyword;            // 日誌關鍵字

    /**
     * 鏈式指定日誌類型
     * @param string $type 日誌類型，用“_”進行分割
     * @return object 日誌模型物件
     */
    public function load($type) {
        $type = explode('_', $type, 2);
        $this->option['app'] = $type[0];
        $this->option['group'] = $type[1];
        return $this;
    }

    /**
     * 鏈式指定日志行為
     * @param string $type 行為欄位
     * @return object 日誌模型物件
     */
    public function action($type) {
        $this->option['action'] = $type;
        return $this;
    }

    /**
     * 記錄日誌
     * @param string $content 日誌內容
     * @param integer $isAdminLog 是否是管理員日誌，默認為1
     * @return mix 添加失敗返回false，添加成功返回日誌ID
     */
    public function record($content, $isAdminLog) {
        $this->parseKeyWord($content);
        $user = $GLOBALS['ts']['user'];
        $data['uid'] = $user['uid'];                // TODO:臨時寫死
        $data['uname'] = $user['uname'];        // TODO:臨時寫死
        $data['app_name'] = $this->option['app'];
        $data['group'] = $this->option['group'];
        $data['action'] = $this->option['action'];
        $data['data'] = serialize( $content );
        $data['ctime'] = time();
        $data['url'] = $_SERVER['REQUEST_URI'];
        $data['isAdmin'] = intval( $isAdminLog );
        $data['ip'] = get_client_ip();
        $data['keyword'] = ($this->keyword) ? implode(' ',$this->keyword) : '' ;
        return $this->add($data);
    }

    /**
     * 將數值轉換為字元串
     * @param string $content 日誌內容
     * @return void
     */
    private function parseKeyWord($content) {
        if(is_array($content)) {
            foreach($content as $key => $value) {
                !in_array($value, $this->keyword) && $this->parseKeyWord($value);
            }
        } else {
            $this->keyword[] = $content;
        }
    }

    /**
     * 獲取指定日誌的列表資訊
     * @param array $map 查詢條件
     * @param integer $limit 結果集數目，默認為30
     * @param string $table 指定日誌表，默認為系統日誌表
     * @return array 指定日誌的列表資訊
     */
    public function get($map, $limit = 30, $table = false) {
        $table = $table ? $this->tablePrefix.'x_logs_'.$table : $this->tablePrefix.'x_logs';
        $list = $this->table($table)->where($map)->order('id DESC')->findPage($limit);
        foreach($list['data'] as $key => $v) {
            $tempData = $this->__paseTemplate($v);
            $list['data'][$key]['data'] = $tempData['data'];
            $list['data'][$key]['type_info'] = $tempData['info'];
        }

        return $list;
    }

    /**
     * 獲取指定應用下所有許可權節點列表
     * @param string $app 應用名稱
     * @return array 指定應用下所有許可權節點列表
     */
    public function getMenuList($app){
        $logsXml = SITE_PATH.'/apps/'.$app.'/Conf/logs.xml';
        if(!file_exists($logsXml)) {
            $this->error = L('PUBLIC_SETTING_FILE', array('file'=>$logsXml));           // 配置檔案：{file}不存在
            return false;
        }
        $xml = simplexml_load_file($logsXml);
        if($xml->group) {
            foreach($xml->group as $k=>$v) {
                unset($rule);
                foreach($v->action as $kk => $vv) {
                    $rule[(string)$vv['type']] = (string)$vv['info'];
                }

                $data['_group'][(string)$v['name']] = array(
                    'info'=>(string)$v['info'],
                    '_rule'=>$rule
                );
            }
        } else {
            foreach($xml->action as $kk => $vv) {
                $data['_rule'][(string)$vv['type']] = (string)$vv['info'];
            }
        }

        return $data;
    }

    /**
     * 清除日誌資料，刪除幾個月前的日誌資訊
     * @param integer $m 月數，刪除幾個月前的日誌資訊
     * @return mix 刪除失敗返回false，刪除成功返回1
     */
    public function cleanLogs($m) {
        $m = intval($m);
        if($m == 0) {
            return false;
        }
        // 獲取日誌表列表
        $tableList = D('')->query("SHOW TABLE STATUS LIKE '".$this->tablePrefix."x_logs_%'");
        $todayInfo = getDate(time());
        $diff = getDate(mktime(0,0,0,$todayInfo['mon'] - $m,1,$todayInfo['year']));

        foreach($tableList as $k => $value) {
            $table = explode('_',$value['Name']);
            if($table[3] == $diff['year']) {
                if($table[4] <= $diff['mon']) {
                    $dropTables[] = $value['Name'];
                }
            } else if($table[3] < $diff['year']) {
                $dropTables[] = $value['Name'];
            }
        }

        if($dropTables) {
            return D('')->query("DROP TABLE ".implode(',',$dropTables));
        } else {
            return false;
        }
    }

    /**
     * 重建日誌歸檔，重建後的日誌只存在歸檔表中，主日誌表不在有該日誌資訊
     * @return boolean 是否重建成功
     */
    public function logsArchive() {
        $logsTableName = $this->tablePrefix.'x_logs';
        $today = getDate(time());
        // 上個月底的時間
        $dayBeforeTime = strtotime(date('Y-m-t 23:59:59', strtotime('-1 month')));
        // 主表是否存在31天前的日誌
        if($this->where('ctime<='.$dayBeforeTime)->count()) {
            // 搜索下有多少個月的資料需要歸檔
            $findDate = D('')->query("SELECT DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m') AS lists FROM {$logsTableName} WHERE ctime <= ".$dayBeforeTime." GROUP BY DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m')");
            // 每個月歸檔
            foreach($findDate as $value) {
                $dataInfo = explode('-', $value['lists']);
                // 歸檔表的名稱
                $archiveTableName = $logsTableName.'_'.$dataInfo[0].'_'.$dataInfo[1];
                // 先創建表
                if(D('')->query("DESC $archiveTableName") == false) {
                    D('')->query("CREATE TABLE $archiveTableName LIKE $logsTableName");
        }
        $querySql = "DATE_FORMAT(FROM_UNIXTIME(ctime),'%Y-%m') = '".$value[lists]."'";
        $result = D('')->query("INSERT INTO $archiveTableName SELECT * FROM $logsTableName WHERE $querySql");
        }
        $this->where($querySql)->delete();
        return true;
        } else {
            return false;
        }
        }

        /**
         * 刪除指定的日誌記錄資訊
         * @param integer $id 日誌ID
         * @param string $date 時間欄位
         * @return mix 刪除失敗返回false，刪除成功返回刪除的日誌ID
         */
        public function dellogs($id, $date = '') {
            $logsTableName = $this->tablePrefix.'x_logs'.(empty($date) ? "" : "_".$date);
            $map['id'] = is_array($id) ? array('IN', $id) : $id;
            return D('')->table($logsTableName)->where($map)->delete();
        }

        /**
         * 渲染日誌模板變數
         * @param array $_data 日誌相關資料
         * @return array 渲染後的日誌模板變數
         */
        protected function __paseTemplate($_data) {
            $app = $_data['app_name'];
            $var = unserialize($_data['data']);
            $logFile = SITE_PATH.'/apps/'.$app.'/Conf/logs.xml';
            if(!file_exists($logFile)) {
                $this->error = L('PUBLIC_SETTING_FILE', array('file'=>$logFile));           // 配置檔案：{file}不存在
                return false;
        }
        $content = fetch($logFile, $var,'UTF8','text/xml');

        $dom = new domDocument;
        $dom->loadXml($content);
        unset($content);

        $s = simplexml_import_dom($dom);

        if($_data['group']) {
            $result = $s->xpath("//root/group[@name='".$_data['group']."']/action[@type='".$_data['action']."']");
        } else {
            $result = $s->xpath("//root/action[@type='".$_data['action']."']");
        }
        // 異常情況
        $return = array('info'=>L('PUBLIC_PERMISSION_POINT_NOEXIST'),'data'=>L('PUBLIC_PERMISSION_POINT_NOEXIST'));         // 許可權節點不存在，許可權節點不存在

        if($result) {
            $return['info'] = (string)$result[0]['info'];
            $return['data'] = trim((string)$result[0]);
        }

        return $return;
        }
        }
