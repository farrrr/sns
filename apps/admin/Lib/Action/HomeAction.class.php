<?php
/**
 * 後臺，系統配置控制器
 * @author liuxiaoqing <liuxiaoqing@zhishisoft.com>
 * @version TS3.O
 */
tsload(APPS_PATH.'/admin/Lib/Action/AdministratorAction.class.php');

class HomeAction extends AdministratorAction {

    /**
     * 初始化，頁面標題，用於雙語
     */
    public function _initialize() {
        $this->pageTitle['logs'] = L('PUBLIC_MANAGEMENT_LOG');
        $this->pageTitle['logsArchive'] = L('PUBLIC_SYSTEM_LOGSUM');
        $this->pageTitle['schedule'] = L('PUBLIC_SCHEDULED_TASK');
        $this->pageTitle['newschedule'] = L('PUBLIC_SCHEDULED_TASK_NEWCREATE');
        $this->pageTitle['systemdata'] = L('PUBLIC_SYSTEM_DATA_SQL');
        $this->pageTitle['message'] = L('PUBLIC_SYSTEM_MESSAGE');
        $this->pageTitle['invatecount'] = L('PUBLIC_INVITE_CALCULATION');
        $this->pageTitle['invateTop'] = L('PUBLIC_INVITE_TOP');
        $this->pageTitle['tag'] = L('PUBLIC_TAG_MANAGEMENT');
        $this->pageTitle['addTag'] = L('PUBLIC_TAG_MANAGEMENT');
        $this->pageTitle['feedbackType'] = L('PUBLIC_FEEDBACK_CLASSIFICATION');
        $this->pageTitle['cacheConfig'] = '快取配置';
        parent::_initialize();
    }

    /**
     * 系統資訊 - 基本資訊
     */
    public function statistics()
    {
        // 插入統計資料
        $gradeInfo = model('System')->upgrade();

        $statistics = array();

        /**
         * 重要: 為了防止與應用別名重名，“伺服器資訊”、“使用者資訊”、“開發團隊”作為key前面有空格
         */

        // 伺服器資訊
        //$site_version = model('Xdata')->get('siteopt:site_system_version');
        $serverInfo[L('PUBLIC_CORE_VERSION')] = 'TS3.0';
        $serverInfo[L('PUBLIC_SERVER_PHP')]	= PHP_OS.' / PHP v'.PHP_VERSION;
        $serverInfo[L('PUBLIC_SERVER_SOFT')] = $_SERVER['SERVER_SOFTWARE'];
        $serverInfo[L('PUBLIC_UPLOAD_PERMISSION')] = (@ini_get('file_uploads')) ? ini_get('upload_max_filesize') : '<font color="red">no</font>';
        // 資料庫資訊
        $mysqlinfo = D('')->query("SELECT VERSION() AS version");
        $serverInfo[L('PUBLIC_MYSQL')] = $mysqlinfo[0]['version'] ;

        $t = D('')->query("SHOW TABLE STATUS LIKE '".C('DB_PREFIX')."%'");
        foreach($t as $k) {
            $dbsize += $k['Data_length'] + $k['Index_length'];
        }

        $umap['is_del'] = 0;
        $userInfo['totalUser'] = model('User')->where($umap)->count();					// 使用者總數
        $aumap['ctime'] = array('GT', time() - 24 * 3600 * 60);							// 2個月內登入過的使用者算活躍使用者
        $userInfo['activeUser'] = D('login_record')->where($aumap)->count();

        $ymap['day'] = date('Y-m-d', strtotime("-1 day"));
        $d = D('online_stats')->where($ymap)->find();
        $userInfo['yesterdayUser'] = $d['most_online'];

        $onmap['uid'] = array('GT', 0);
        $onmap['activeTime'] = array('GT', time() - 1800);
        $userInfo['onlineUser'] = count(D()->table(C('DB_PREFIX').'online')->where($onmap)->findAll());
        $onmap['uid'] = 0;
        $userInfo['onlineUser'] += count(D()->table(C('DB_PREFIX').'online')->where($onmap)->findAll());		// 加上遊客

        $ymap['day'] = array('GT', date('Y-m-d', strtotime("-7 day")));
        $d = D('online_stats')->where($ymap)->field('max(most_online) AS most_online')->find();
        $userInfo['weekAvg'] = $d['most_online'];

        $this->assign('userInfo', $userInfo);

        $ymap['day'] = array('GT', date('Y-m-d', strtotime("-7 day")));
        $d = D('online_stats')->where($ymap)->getHashList('day', '*');

        $visitCount = array();
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $visitCount['today'] = array('pv'=>$d[$today]['total_pageviews'],'pu'=>$d[$today]['total_users'],'guest'=>$d[$today]['total_guests']);
        $visitCount['yesterday'] = array('pv'=>$d[$yesterday]['total_pageviews'],'pu'=>$d[$yesterday]['total_users'],'guest'=>$d[$yesterday]['total_guests']);
        $apv = 0;
        $apu = 0;
        $agu = 0;
        foreach($d as $v) {
            $apv += $v['total_pageviews'];
            $apu += $v['total_users'];
            $agu += $v['total_guests'];
        }

        $visitCount['weekAvg'] = array('pv'=>ceil($apv/count($d)),'pu'=>ceil($apu/count($d)),'guest'=>ceil($agu/count($d)));
        $this->assign('visitCount', $visitCount);

        $serverInfo[L('PUBLIC_DATABASE_SIZE')] = byte_format($dbsize);
        $statistics[L('PUBLIC_SERVER_INFORMATION')] = $serverInfo;
        unset($serverInfo);

        // 開發團隊
        $statistics[L('PUBLIC_DEV_TEAM')] = array(
            L('PUBLIC_COPYRIGHT') => '<a href="http://www.zhishisoft.com" target="_blank">'.L('PUBLIC_COMPANY').'</a>',
        );

        $this->assign('statistics', $statistics);
        $this->display();
    }

    /**
     * 系統資訊 - 訪問統計
     */
    public function visitorCount() {
        model('Online')->dostatus();		// 執行統計 TODO 以後放入計劃任務中

        !$_GET['type'] && $_GET['type'] = 'today';
        switch($_GET['type']) {
        case 'today':
            $where = "day ='".date('Y-m-d')."'";
            break;
        case 'yesterday':
            $where = "day ='".date('Y-m-d',strtotime('-1 day'))."'";
            break;
        case 'week':
            $where = " day >= '".date('Y-m-d',strtotime('-7 day'))."'";
            break;
        case '30d':
            $where = " day >= '".date('Y-m-d',strtotime('-30 day'))."'";
            break;
        case 'month':
            $where = " day >= '".date('Y-m-01')."'";
            break;
        }

        $this->assign('type', $_GET['type']);
        if(!empty($_GET['start_day']) || !empty($_GET['end_day'])) {
            $where = '1';
            if(!empty($_GET['start_day'])) {
                $where .=" AND day > '{$_GET['start_day']}'";
            }
            if(!empty($_GET['end_day'])){
                $where .=" AND day < '{$_GET['end_day']}'";
            }
            $this->assign('type','');
        }

        $list = model('Online')->getStatsList($where);
        $this->assign($list);
        $this->display();
    }

    /**
     * 系統資訊 - 管理日誌 - 日誌列表
     */
    public function logs() {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('id','uid','uname','app_name','ip','data','ctime','isAdmin','type_info','DOACTION');
        // 搜索key值
        $this->searchKey = array('uname','app_name',array('ctime','ctime1'),'isAdmin','keyword');
        // 針對搜索的特殊選項
        $this->opt['isAdmin'] = array('0'=>L('PUBLIC_USER_LOGS'),'1'=>L('PUBLIC_MANAGEMENT_LOG'));
        $this->opt['app_name'] 	= array('0'=>L('PUBLIC_ALL_STREAM'),'admin'=>L('PUBLIC_SYSTEM_BACK'));	//TODO 從目錄讀取 或者應用表裡讀取
        // Tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_LOGLIST'),'tabHash'=>'list','url'=>U('admin/Home/logs'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_LOGSUM'),'tabHash'=>'down','url'=>U('admin/Home/logsArchive'));
        // 指定查詢的表尾
        $table = isset($_REQUEST['table']) ? t($_REQUEST['table']) : '';
        // 列表分頁欄按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_INDEX'),'onclick'=>"admin.fold('search_form')");
        $this->pageButton[] = array('title'=>L('PUBLIC_SYSTEM_DELALL'),'onclick'=>"admin.delselectLog('{$table}')");
        // 資料的格式化 與pageKeyList保持一致
        $listData = $this->_getLogsData($table);
        $this->displayList($listData);
    }

    /**
     * 系統資訊 - 管理日誌 - 日誌歸檔
     */
    public function logsArchive() {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('Name','Engine','Version','Rows','Data_length','Data_free','Create_time','Update_time','Collation','DOACTION');
        // Tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_LOGLIST'),'tabHash'=>'list','url'=>U('admin/Home/logs'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_LOGSUM'),'tabHash'=>'down','url'=>U('admin/Home/logsArchive'));
        // 列表分頁欄按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_LOGS_REMOVE_SEX'),'onclick'=>"admin.cleanLogs(6)");
        $this->pageButton[] = array('title'=>L('PUBLIC_LOGS_REMOVE_SET'),'onclick'=>"admin.cleanLogs(12)");
        $this->pageButton[] = array('title'=>L('PUBLIC_LOGS_REMOVE_LOG'),'onclick'=>"admin.logsArchive()");

        $data['data'] =  D('')->query("SHOW TABLE STATUS LIKE '".C('DB_PREFIX')."x_logs%'") ;

        foreach($data['data'] as &$v) {
            foreach($v as $vk => $vv) {
                $vk == 'Data_length' && $v[$vk] = byte_format($vv);
            }
            $date = ltrim(str_replace(C('DB_PREFIX').'x_logs', '', $v['Name']),'_');
            $upTime = D('')->query('SELECT max( ctime ) AS Update_time FROM `'.$v['Name'].'`');
            $v['Update_time'] = !empty($upTime[0]['Update_time']) ? date('Y-m-d H:i:s',$upTime[0]['Update_time']) : $v['Create_time'];
            $v['DOACTION'] = '<a href="'.U('admin/Home/logs',array('table'=>$date)).'">'.L('PUBLIC_VIEW').'</a>';
        }

        $this->allSelected = false;
        $this->displayList($data);
    }

    /**
     * 獲取日誌的分組情況
     */
    public function _getLogGroup() {
        $app = $_POST['app_name'];
        $data = model('Logs')->getMenuList($app);
        $this->assign('list', $data['_group']);
        $this->assign('def', $_POST['def']);
        $this->display();
    }

    /**
     * 清除日誌操作
     */
    public function _cleanLogs() {
        // TODO:驗證清理許可權
        $return = array('status'=>1,'data'=>'');
        if(model('Logs')->cleanLogs($_POST['m'])) {
            $return['data'] = L('PUBLIC_SYSTEM_LOG_REMOVE');
        } else {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_SYSTEM_LOG_REMOVE_IS');
        }

        LogRecord('admin_system', 'cleanlog', array('date'=>$_POST['m'], 'k'=>L('PUBLIC_SYSTEM_LOG_REMOVE_DEL')), true);
        exit(json_encode($return));
    }

    /**
     * 日誌歸檔
     */
    public function _logsArchive() {
        // TODO:驗證許可權
        $return = array('status'=>1,'data'=>'');
        if(model('Logs')->logsArchive()) {
            $return['data'] = L('PUBLIC_SYSTEM_LOGSUM_SUCCESS');
        } else {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_SYSTEM_LOGSUM_SUCCESS_IS');
        }

        LogRecord('admin_system', 'logsArchive', array('msg'=>$return['data'], 'k'=>L('PUBLIC_SYSTEM_LOGSUM')), true);
        exit(json_encode($return));
    }

    /**
     * 刪除日誌操作
     */
    public function _delLogs() {
        $return = array('status'=>1,'data'=>'');
        if(model('Logs')->dellogs($_POST['id'],t($_POST['table']))) {
            $return['data'] = L('SSC_DELETE_SUCCESS');
        } else {
            $return['status'] = 0;
            $return['data'] = L('SSC_DELETE_FAIL');
        }
        !is_array($_POST['id']) && $_POST['id'] = array($_POST['id']);

        LogRecord('admin_system', 'dellog', array('nums'=>count($_POST['id']), 'ids'=>implode(',',$_POST['id'])), true);
        exit(json_encode($return));
    }

    /**
     * 獲取日誌資料
     * @param string $table 日誌表名
     * @return array 日誌資料
     */
    private function _getLogsData($table = '') {
        // 條件過濾
        $map = $this->getSearchPost();
        !empty($map['app_name']) && $_map['app_name'] = t($map['app_name']);
        !empty($map['uname']) && $_map['uname'] = t($map['uname']);
        !empty($map['keyword']) && $_map['keyword'] = array('LIKE', "%".t($map['keyword'])."%");

        if(!empty($map['ctime'][0]) && !empty($map['ctime'][1])) {
            $_map['ctime'] = array('BETWEEN', array(strtotime($map['ctime'][0]),strtotime($map['ctime'][1])));
        } else {
            !empty($map['ctime'][0]) && $_map['ctime'] = array('GT', strtotime($map['ctime'][0]));
            !empty($map['ctime'][1]) && $_map['ctime'] = array('LT', strtotime($map['ctime'][1]));
        }

        if(!empty($map['group_action'])) {
            list($group,$action) = explode('-', $map['group_action']);
            $_map['group'] = $group;
            $_map['action'] = $action;
            $this->onload[] = "admin.selectLog('{$map['app_name']}','{$map['group_action']}')";
        }

        // TODO:下面的in也許會很慢，可能需要分情況
        (!empty($map['isAdmin']) && is_array($map['isAdmin'])) && $_map['isAdmin'] = array('IN', $map['isAdmin']);

        // 日誌歸檔表的查詢處理
        $this->searchPostUrl .= '&table='.$table;

        $listData = model('Logs')->get($_map, 20, $table);

        foreach($listData['data'] as &$v) {
            foreach($v as $vk => $vv) {
                if(!in_array($vk, $this->pageKeyList)) {
                    unset($vk);
                }
                $vk == 'app_name' && $v[$vk] = $this->opt['app_name'][$vv];
                $vk == 'ctime' && $v[$vk] = date('Y-m-d H:i:s',$vv);
                $vk == 'isAdmin' && $v[$vk] = $this->opt['isAdmin'][$vv];
            }
            $v['app_name'] .= '-'.$v['type_info'];
            $v['DOACTION'] = '<a href="javascript:void(0)" onclick="admin.dellog(\''.$v['id'].'\',\''.$table.'\')">'.L('PUBLIC_STREAM_DELETE').'</a>';
        }

        return $listData;
    }

    /**
     * 系統工具 - 計劃任務 - 計劃任務列表
     */
    public function schedule() {
        $this->pageKeyList = array('id','method','schedule_type','modifier','dirlist','month','start_datetime','end_datetime','last_run_time','info');
        $this->pageTab[] = array('title'=>L('PUBLIC_SCHEDULED_TASK_LIST'),'tabHash'=>'list','url'=>U('admin/Home/schedule'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SCHEDULED_TASK_CREATE'),'tabHash'=>'new','url'=>U('admin/Home/newschedule'));
        $this->pageButton[] = array('title'=>L('PUBLIC_SCHEDULED_TASK_DELETE'),'onclick'=>"admin.delschedule()");

        $list = model('Schedule')->getScheduleList();
        $listdata['data'] = array();
        foreach($list as $k => $v) {
            $list[$k]['method'] = $v['task_to_run'];
            $listdata['data'][] = $list[$k];
        }
        $this->displayList($listdata);
    }

    /**
     * 系統工具 - 計劃任務 - 新建計劃任務
     */
    public function newschedule() {
        $this->pageKeyList = array('task_to_run','schedule_type','modifier','dirlist','month','start_datetime','end_datetime','info');
        $this->opt['schedule_type'] = array('ONCE'=>'只執行一次','MINUTE'=>'每分鐘','HOURLY'=>'每小時','DAILY'=>'每小時','WEEKLY'=>'每週','MONTHLY'=>'每月');
        // 計劃任務儲存地址
        $this->savePostUrl = U('admin/Home/saveschedule');
        $this->displayConfig(array());
    }

    /**
     * 儲存計劃任務操作
     */
    public function saveschedule() {
        $res = model('Schedule')->addSchedule($_POST);
        if($res) {
            // TODO:記錄日誌
            $this->assign('jumpUrl', U('admin/Home/schedule'));
            $this->success(L('PUBLIC_SAVE_SUCCESS'));
        } else {
            $this->error(L('PUBLIC_SAVE_FAIL'));
        }
    }

    /**
     * 刪除計劃任務操作
     */
    public function doDeleteSchedule() {
        $return = array('status'=>1,'data'=>L('PUBLIC_DELETE_SUCCESS'));
        $ids = is_array($_POST ['id']) ? $_POST['id'] : array(intval($_POST['id']));
        $map['id'] = array('IN', $ids);
        $res = model('Schedule')->where($map)->delete();
        if($res) {
            //TODO:記錄日誌
        } else {
            $return['status'] = 0;
            $return['data'] = L('PUBLIC_DELETE_FAIL');
        }
        exit(json_encode($return));
    }

    /**
     * 資源統計 ？？？ 未完成
     */
    public function sourcesCount() {

    }

    /**
     * 資料字典
     */
    public function systemdata(){
        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('name','key','value','DOACTION');

        //tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_DATA_SQLLIST'),'tabHash'=>'list','url'=>U('admin/Home/addsystemdata'));
        $this->pageTab[] = array('title'=>L('PUBLIC_SYSTEM_ADD_DATA'),'tabHash'=>'add','url'=>U('admin/Home/addsystemdata'));

        /*資料的格式化 與listKey保持一致 */
        $data = model('Xdata')->lget('dict');
        foreach($data as $k=>&$v){
            $v['key']		= $k;
            $v['DOACTION'] = '<a href="'.U('admin/Home/addsystemdata',array('key'=>$v['key'])).'">'.L('PUBLIC_EDIT').'</a>
                <a href="javascript:admin.delsystemdata(\''.$v['key'].'\')" >'.L('PUBLIC_STREAM_DELETE').'</a>';
        }

        $this->allSelected = false;

        $this->displayList(array('data'=>$data));
    }

    //添加編輯資料
    public function addsystemdata(){
        if(!empty($_GET['key'])){
            $this->assign('pageTitle',L('PUBLIC_SYSTEM_EDIT_DATA'));
            $map['key']  = t($_GET['key']);
            $map['list'] = 'dict';
            $detail = model('Xdata')->where($map)->find();
            $d 		= unserialize($detail['value']);
            $detail['name']  = $d['name'];	//中文名
            $detail['value'] = $d['value'];	//內容
        }else{
            $this->assign('pageTitle',L('PUBLIC_SYSTEM_ADD_DATA'));
            $detail = array();
        }
        $this->pageKeyList = array('id','name','key','value');
        $this->savePostUrl = U('admin/Home/doaddsystemdata');
        $this->displayConfig($detail);
    }
    //儲存資料
    public function doaddsystemdata(){
        if(empty($_POST['key']) || empty($_POST['name'])){
            $this->error(L('PUBLIC_SYSTEM_KEYCN_IS'));exit();
        }

        //DAN TENG
        $s['value'] = serialize(array('name'=>$_POST['name'],'value'=>$_POST['value']));
        $s['list']  = 'dict';
        $s['mtime'] = date('Y-m-d H:i:s');
        $s['key']   = t($_POST['key']);
        if(!empty($_POST['id'])){
            $m['id'] = t($_POST['id']);
            $res     = model('Xdata')->where($m)->save($s);
        }else{
            $res     = model('Xdata')->add($s);
        }

        F('_xdata_lget_dict',null);

        if($res == true){
            //TODO  記錄日誌
            $this->assign('jumpUrl',U('admin/Home/systemdata'));
            $this->success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
        }else{
            $this->error(model('Xdata')->getError());
        }
    }
    //刪除資料
    public function deladdsystemdata(){
        $return = array('status'=>1,'data'=>L('PUBLIC_DELETE_SUCCESS'));
        if(empty($_POST['key'])){
            $return = array('status'=>0,'data'=>L('PUBLIC_ID_NOEXIST'));
            echo json_encode($return);exit();
        }
        $map['key']  = t($_POST['key']);
        $map['list'] = 'dict';
        if($res = model('Xdata')->where($map)->delete()){
            F('_xdata_lget_dict',null);
            //TODO 記錄日誌
        }else{
            $error = model('Xdata')->getError();
            empty($error) && $error = L('SSC_DELETE_FAIL');
            $return = array('status'=>0,'data'=>$error);
        }
        echo json_encode($return);exit();
    }

    /**
     * 運營工具 - 意見反饋 - 意見反饋列表
     */
    public function feedback() {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('id','feedbacktype','feedback','uid','cTime','type','DOACTION');

        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_LIST'),'tabHash'=>'list','url'=>U('admin/Home/feedback'));
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_TYPE'),'tabHash'=>'type','url'=>U('admin/Home/feedbackType'));

        $this->pageButton[] = array('title'=>L('PUBLIC_ALREADY_PROCESSED'),'onclick'=>"location.href = '".U('admin/Home/feedback',array('type'=>'true'))."'");
        $this->pageButton[] = array('title'=>L('PUBLIC_WAIT_PROCESSE'),'onclick'=>"location.href = '".U('admin/Home/feedback',array('type'=>'false'))."'");
        // 列表分頁欄 按鈕
        $this->allSelected = false;
        $this->assign('pageTitle', L('PUBLIC_FEEDBACK_MANAGE'));
        // 資料的格式化 與listKey保持一致
        if($_GET['type']) {
            if($_GET['type'] == "true"){
                $listData = model('Feedback')->where("type = 1")->order("cTime desc")->findPage(20);
            } else {
                $listData = model('Feedback')->where("type = 0")->order("cTime desc")->findPage(20);
            }
        } else {
            $listData = model('Feedback')->order("cTime desc")->findPage(20);
        }

        foreach($listData['data'] as &$v) {
            // TODO:附件處理
            $userInfo = model('User')->getUserInfo($v['uid']);
            $feedbacktype = model('Feedback')->getFeedBackType();
            $v['feedbacktype'] = $feedbacktype[$v['feedbacktype']];
            $v['cTime'] = friendlyDate($v['cTime']);
            $v['uid'] = $userInfo['space_link'];
            if($v['type'] != 1) {
                $v['type'] = L('PUBLIC_WAIT_PROCESSE');
                $v['DOACTION'] = '<a href="'.U('admin/Home/feedback_list',array('id'=>$v['id'])).'">'.L('PUBLIC_VIEW').'</a><a href="'.U('admin/Home/delfeedback',array('id'=>$v['id'])).'" >'.L('PUBLIC_MARK_PROCESSED').'</a>';
            } else {
                $v['type'] = L('PUBLIC_ALREADY_PROCESSED');
                $v['DOACTION'] = '<a href="'.U('admin/Home/feedback_list',array('id'=>$v['id'])).'">'.L('PUBLIC_VIEW').'</a>';
            }
        }

        $this->allSelected = false;
        $this->displayList($listData);
    }

    /**
     * 運營工具 - 意見反饋 - 意見反饋類型
     */
    public function feedbackType() {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('type_id','type_name','DOACTION');
        // 列表分頁欄 按鈕
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_LIST'),'tabHash'=>'list','url'=>U('admin/Home/feedback'));
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_TYPE'),'tabHash'=>'type','url'=>U('admin/Home/feedbackType'));

        $this->pageButton[] = array('title'=>L('PUBLIC_FEEDBACK_ADD_TYPE'),'onclick'=>"location.href = '".U('admin/Home/addFeedbackType',array('tabHash'=>'type'))."'");

        $this->assign('pageTitle', L('PUBLIC_FEEDBACK_CATEGORY_MANAGE'));
        // 資料的格式化與listKey保持一致
        $listData = D('')->table(C('DB_PREFIX').'feedback_type')->findPage(20);

        foreach($listData['data'] as &$v) {
            //TODO:附件處理
            $v['DOACTION'] = '<a href="'.U('admin/Home/addfeedbackType',array('type_id'=>$v['type_id'],'tabHash'=>'type')).'">'.L('PUBLIC_MODIFY').'</a><a href="'.U('admin/Home/delFeedbackType',array('type_id'=>$v['type_id'])).'" >'.L('PUBLIC_STREAM_DELETE').'</a>';
        }

        $this->allSelected = false;
        $this->displayList($listData);
    }

    public function feedback_list(){
        if(!empty($_GET['id'])){
            $detail =  model('Feedback')->where('id='.intval($_GET['id']))->find();
            $feedbacktype =  model('Feedback')->getFeedBackType();
            $detail['feedbacktype'] = $feedbacktype[$detail['feedbacktype']];
        }else{
            $detail = array();
        }
        $this->pageKeyList = array('feedbacktype','uid','feedback','cTme');
        $this->savePostUrl = U('admin/Home/delfeedback',array('id'=>$_GET['id']));
        $this->submitAlias = L('PUBLIC_MARK_PROCESSED');

        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_LIST'),'tabHash'=>'list','url'=>U('admin/Home/feedback'));
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_TYPE'),'tabHash'=>'type','url'=>U('admin/Home/feedbackType'));

        $this->assign('pageTitle' , L('PUBLIC_DETAILS_LIST'));

        $this->displayConfig($detail);
    }

    //添加反饋類型
    public function addFeedbackType(){
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_LIST'),'tabHash'=>'list','url'=>U('admin/Home/feedback'));
        $this->pageTab[] = array('title'=>L('PUBLIC_FEEDBACK_TYPE'),'tabHash'=>'type','url'=>U('admin/Home/feedbackType'));
        if(!empty($_GET['type_id'])){
            $this->assign('pageTitle',L('PUBLIC_FEEDBACK_EDIT_TYPE'));
            $detail = D('')->table(C('DB_PREFIX').'feedback_type')->where('type_id='.intval($_GET['type_id']))->find();
            $this->pageKey .='_edit';
        }else{
            $this->assign('pageTitle',L('PUBLIC_FEEDBACK_ADD_TYPE'));
            $detail = array();
        }

        $this->pageKeyList = array('type_id','type_name');
        $this->savePostUrl = U('admin/Home/doaddFeedbackType');
        $this->displayConfig($detail);
    }
    //添加分類
    public function doaddFeedbackType(){

        if(!empty($_POST['type_id'])){
            //save $res
            $add['type_name'] = t($_POST['type_name']);
            if($add['type_name'] == ''){
                $this->error(L('PUBLIC_ADMIN_OPRETING_ERROR'));
            }else{
                $res = D('')->table(C('DB_PREFIX').'feedback_type')->where("type_id = ".$_POST['type_id'])->save($add);
            }
        }else{
            //add $res
            $add['type_name'] = t($_POST['type_name']);
            if($add['type_name'] == ''){
                $this->error(L('PUBLIC_ADMIN_OPRETING_ERROR'));
            }else{
                $res = D('')->table(C('DB_PREFIX').'feedback_type')->add($add);
            }
        }

        if($res){
            $this->assign('jumpUrl',U('admin/Home/feedbackType',array('tabHash'=>'type')));
            $this->success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
        }else{
            $this->error(L('PUBLIC_DATA_UPGRADE_FAIL'));
        }
    }
    //刪除公告
    public function delFeedbackType(){
        $map['type_id']  =  $_GET['type_id'];
        $res = D('')->table(C('DB_PREFIX').'feedback_type')->where($map)->delete();

        if($res){
            $this->assign('jumpUrl',U('admin/Home/feedbackType',array('tabHash'=>'type')));
            $this->success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
        }else{
            $this->error(model()->getError());
        }

    }

    public function delFeedback(){
        $map['id']  =  $_GET['id'];
        $add['type']  =  1;
        $add['mTime']  =  time();

        $res = model('Feedback')->where($map)->save($add);

        if($res){
            $this->assign('jumpUrl',U('admin/Home/feedback'));
            $this->success(L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
        }else{
            $this->error(model()->getError());
        }

    }


    public function message(){
        //$this->pageKeyList = array('user_group_id','type','content');
        $this->pageKeyList = array('user_group_id','content'); 	//現在後臺只支援發送系統訊息
        $this->opt['type'] = array('0'=>L('PUBLIC_MAIL_INLOCALHOST'),'1'=>'Email');
        $groupHash = model('UserGroup')->getHashUsergroup();
        $this->opt['user_group_id'] = array_merge(array(0=>L('PUBLIC_ALL_USERS')),$groupHash);
        $this->savePostUrl = U('admin/Home/dosendmsg');
        $this->notEmpty = array('content');
        $this->onsubmit = 'admin.checkMessage(this)';
        $this->displayConfig();
    }

    public function dosendmsg(){
        $checkContent = str_replace('&nbsp;', '', $_POST['content']);
        $checkContent = str_replace('<br />', '', $checkContent);
        $checkContent = str_replace('<p>', '', $checkContent);
        $checkContent = str_replace('</p>', '', $checkContent);
        $checkContents = preg_replace('/<img(.*?)src=/i','img',$checkContent);
        $checkContents = preg_replace('/<embed(.*?)src=/i','img',$checkContents);
        if(strlen(t($checkContents))==0) $this->error('系統資訊內容不能為空');
        $this->assign('jumpUrl',U('admin/Home/message'));
        if(model('Notify')->sendSysMessae($_POST['user_group_id'],h($_POST['content']))){
            $this->success();
        }
        $this->error();
    }

    /**
     * 邀請列表展示
     * @return void
     */
    public function invatecount()
    {
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('invite_record_id','receiver_uid','inviter_uid','is_audit','is_active','is_init','ctime','recived_email');
        // 搜索key值
        $this->searchKey = array('inviter_uid','receiver_uid');
        // tab選項
        $this->pageTab[] = array('title'=>L('PUBLIC_INVITE_LIST'),'tabHash'=>'list','url'=>U('admin/Home/invatecount'));
        $this->pageTab[] = array('title'=>L('PUBLIC_INVITE_TOP'),'tabHash'=>'top','url'=>U('admin/Home/invateTop'));
        // 列表分頁欄 按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_INDEX'),'onclick'=>"admin.fold('search_form')");
        // 資料的格式化 與listKey保持一致
        $map = array();
        !empty($_POST['inviter_uid']) && $map['inviter_uid'] = intval($_POST['inviter_uid']);
        !empty($_POST['receiver_uid']) && $map['receiver_uid'] = intval($_POST['receiver_uid']);
        $listData = model('Invite')->getPage($map,20);
        foreach($listData['data'] as &$v){
            $v['invite_record_id'] = $v['invite_code_id'];
            $inviterInfo = model('User')->getUserInfo($v['inviter_uid']);
            $receiverInfo = model('User')->getUserInfo($v['receiver_uid']);
            $v['inviter_uid'] = $inviterInfo['space_link'];
            $v['receiver_uid'] = ($receiverInfo['is_audit'] == 0 || $receiverInfo['is_active'] == 0 || $receiverInfo['is_init'] == 0) ? $receiverInfo['uname'] : $receiverInfo['space_link'];
            $v['is_audit'] = $receiverInfo['is_audit'] == 1 ? '已稽覈' : '未稽覈';
            $v['is_active'] = $receiverInfo['is_active'] == 1 ? '已啟用' : '未啟用';
            $v['is_init'] = $receiverInfo['is_init'] == 1 ? '已初始化' : '未初始化';
            $v['ctime'] = date('Y-m-d H:i:s', $v['ctime']);
            $v['register_time'] = date('Y-m-d H:i:s',$v['register_time']);
            $v['recived_email'] = $v['receiver_email'];
        }

        $this->allSelected = false;
        $this->displayList($listData);
    }

    /**
     * 邀請排行榜展示
     * @return void
     */
    public function invateTop()
    {
        $this->pageKeyList = array('sort','inviter_uid','nums','DOACTION');
        // 搜索key值
        $this->searchKey = array('inviter_uid');
        $_REQUEST['tabHash'] ='top';
        $this->pageTab[] = array('title'=>L('PUBLIC_INVITE_LIST'),'tabHash'=>'list','url'=>U('admin/Home/invatecount'));
        $this->pageTab[] = array('title'=>L('PUBLIC_INVITE_TOP'),'tabHash'=>'top','url'=>U('admin/Home/invateTop'));
        //列表分頁欄 按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_INDEX'),'onclick'=>"admin.fold('search_form')");
        $_POST = $this->getSearchPost();
        $uids =	empty($_POST['inviter_uid']) ? '' : explode(",",$_POST['inviter_uid']);
        $where = !empty($uids) ? " inviter_uid in ('".implode("','", $uids)."')" :'';
        $listData = model('Invite')->getTopPage($where,20);
        $s = intval($_REQUEST['p']) * 20 + 1;
        foreach($listData['data'] as &$v){
            $inviterInfo = model('User')->getUserInfo($v['inviter_uid']);
            $v['sort'] = $s;
            $v['DOACTION'] = '<a href="'.U('admin/Home/invateDetail',array('inviter_uid'=>$v['inviter_uid'])).'">'.L('PUBLIC_VIEW_DETAIL').'</a>';
            $v['inviter_uid'] = $inviterInfo['space_link'];
            $s ++;
        }

        $this->allSelected = false;
        $this->displayList($listData);
    }

    /**
     * 邀請檢視詳情展示
     * @return void
     */
    public function invateDetail()
    {
        // 判斷參數是否正確
        if(empty($_GET['inviter_uid'])) {
            exit($this->error(L('PUBLIC_WRONG_USER_INFO')));
        }
        // 列表key值 DOACTION表示操作
        $this->pageKeyList = array('invite_record_id','receiver_uid','is_audit','is_active','is_init','ctime','recived_email');
        $this->pageButton[] = array('title'=>L('PUBLIC_BACK'),'onclick'=>"window.location.href='".U('admin/Home/invateTop')."'");
        $map['inviter_uid'] = intval($_GET['inviter_uid']);
        // 獲取相關資料
        $listData = model('Invite')->getPage($map,20);
        foreach($listData['data'] as &$v) {
            $v['invite_record_id'] = $v['invite_code_id'];
            $inviterInfo = model('User')->getUserInfo($v['receiver_uid']);
            $v['receiver_uid'] = ($inviterInfo['is_audit'] == 0 || $inviterInfo['is_active'] == 0 || $inviterInfo['is_init'] == 0) ? $inviterInfo['uname'] : $inviterInfo['space_link'];
            $v['is_audit'] = $inviterInfo['is_audit'] == 1 ? '已稽覈' : '未稽覈';
            $v['is_active'] = $inviterInfo['is_active'] == 1 ? '已啟用' : '未啟用';
            $v['is_init'] = $inviterInfo['is_init'] == 1 ? '已初始化' : '未初始化';
            $v['ctime'] = date('Y-m-d H:i:s',$v['ctime']);
            $v['register_time'] = date('Y-m-d H:i:s',$v['register_time']);
            $v['recived_email'] = $v['receiver_email'];
        }
        $inviterInfo_uid = model('User')->getUserInfo($_GET['inviter_uid']);
        $this->assign('pageTitle', $inviterInfo_uid['uname']."的邀請詳情列表 (共{$listData['count']}個)");

        $this->allSelected = false;
        $this->displayList($listData);
    }

    public function tag()
    {
        //列表key值 DOACTION表示操作
        $this->pageKeyList = array('tag_id','table','name','DOACTION');
        //搜索key值
        $this->searchKey = array('name','table');
        $this->opt['table'] = model('Tag')->getTableHash();

        //列表分頁欄 按鈕
        $this->pageButton[] = array('title'=>L('PUBLIC_SEARCH_INDEX'),'onclick'=>"admin.fold('search_form')");

        /*資料的格式化 與listKey保持一致 */
        $map = array();
        !empty($_POST['name']) && $map['b.name'] = array('like', "%" . t($_POST['name']) . "%");
        !empty($_POST['table']) && $map['_string'] = "`table` = '".t($_POST['table'])."'";

        $listData = model('Tag')->getAppTagList($map);

        foreach($listData['data'] as &$v){
            $v['DOACTION'] = '<a href="javascript:;" onclick="admin.delTag(this,'.$v['tag_id'].',\''.$v['table'].'\','.$v['row_id'].')">'.L('PUBLIC_STREAM_DELETE').'</a>';
        }

        $this->allSelected = false;
        $this->displayList($listData);
    }

    public function deltag(){

        $map['tag_id']   = intval($_REQUEST['tag_id']);
        $map['_string']  = "`table` = '".t($_REQUEST['table'])."'";
        $map['row_id']   = intval($_REQUEST['row_id']);
        $return = array('status'=>0,'data'=>L('PUBLIC_ADMIN_OPRETING_ERROR'));
        if($map['tag_id'] > 0 &&  D('')->table(C('DB_PREFIX').'app_tag')->where($map)->delete()){
            $return = array('status'=>1,'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));
        }
        echo json_encode($return);
    }

    public function  addNav(){
        $appname = t($_REQUEST['appname']);
        $url = t($_REQUEST['url']);
        if(is_array($this->navList)){

            $this->navList[$appname] = $url;
        }else{

            $this->navList = array($appname=>$url);
        }
        model('Xdata')->put('admin_nav:top',$this->navList);
    }

    public function removeNav(){
        $appname = t($_REQUEST['appname']);
        if(is_array($this->navList)){
            unset($this->navList[$appname]);
        }else{
            $this->navList = array();
        }
        model('Xdata')->put('admin_nav:top',$this->navList);
    }

    //快取配置
    public function cacheConfig(){
        if ( $_POST ){
            $cachetype = t ( $_POST['cachetype'] );

            //已測試通過
            if($cachetype=='Memcache' && !extension_loaded('memcache')){
                $this->error('無法啟用該服務，伺服器沒有安裝Memcache擴展。');
            }

            //已測試通過
            if($cachetype=='APC' && !function_exists('apc_cache_info')){
                $this->error('無法啟用該服務，伺服器沒有安裝APC擴展。');
            }

            //已測試通過
            if($cachetype=='Xcache' && !function_exists('xcache_info')){
                $this->error('無法啟用該服務，伺服器沒有安裝Xcache擴展。');
            }

            //沒環境測試
            if($cachetype=='Redis' && !extension_loaded('Redis')){
                $this->error('無法啟用該服務，伺服器沒有安裝Redis擴展。');
            }

            //沒環境測試
            if($cachetype=='WinCache' && !function_exists('wincache_ucache_info')){
                $this->error('無法啟用該服務，伺服器沒有安裝WinCache擴展。');
            }

            //貌似不靠譜還沒搞定
            if($cachetype=='Eaccelerator' && !function_exists('eaccelerator_get')){
                $this->error('無法啟用該服務，伺服器沒有安裝eAccelerator擴展。');
            }

            model('Xdata')->saveKey('cacheconfig:cachetype' , $cachetype);
            model('Xdata')->saveKey('cacheconfig:cachesetting' , $cachesetting);
            $this->success( '儲存成功' );
            }

            $this->pageKeyList = array( 'cachetype','cachesetting', 'status' );
            $this->opt['cachetype'] = array(
                'File'=>'檔案快取',
                //'Db'=>'資料庫快取',
                'Xcache'=>'Xcache',
                'APC'=>'APC',
                'Memcache'=>'Memcache',
                //'Redis'=>'Redis',
                //'WinCache'=>'WinCache',
                //'Eaccelerator'=>'Eaccelerator',
            );

            model('Cache')->set('testCacheStatus', '123456789');
            $status = model('Cache')->get('testCacheStatus');
            model('Cache')->rm('testCacheStatus');
            $this->opt['status'] = $status=='123456789' ? array('正常') : array('不正常');

            $data['cachetype'] = model('Xdata')->get('cacheconfig:cachetype');
            !$data['cachetype'] && $data['cachetype'] = 'file';
            $this->savePostUrl = U('admin/Home/cacheConfig');
            $this->displayConfig($data);

            }
            }
