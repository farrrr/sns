<?php
// +----------------------------------------------------------------------
// | ThinkSNS
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://www.thinksns.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: Daniel Yang <desheng.young@gmail.com>
// +----------------------------------------------------------------------
//

/**
 +------------------------------------------------------------------------------
 * ScheduleModel 計劃任務服務
 * 實現任務的定時執行
 * 為各種任務的定期執行提供支援
 +------------------------------------------------------------------------------
 * @category    addons
 * @package     addons
 * @subpackage  services
 * @author      Daniel Yang <desheng.young@gmail.com>
 * @version     $Id$
 +------------------------------------------------------------------------------
 */

class ScheduleModel extends Model {
    private $MONTH_ARRAY    = array('Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec');
    private $WEEK_ARRAY     = array('Mon','Tue','Wed','Thu','Fri','Sat','Sun');

    private $model;
    private $schedule       = array();
    private $scheduleList   = array();

    //  task_to_run     要執行任務的url
    //  schedule_type   計劃類型：NOCE/MINUTE/HOURLY/DAILY/WEEKLY/MONTHLY
    //  modifier        計劃頻率
    //  dirlist         指定周或月的一天
    //  month           指定年的一個月
    //  start_datetime  計劃生效日期
    //  end_datetime    計劃過期日期
    //  last_run_time   上次運行時間

    //判斷一個schedule是否有效
    public function isValidSchedule($schedule = '') {

        if( empty($schedule) ) {
            $schedule = $this->schedule;
        }
        //task_to_run / schedule_type / start_datetime 必須
        if( empty($schedule['task_to_run']) || empty($schedule['schedule_type']) || empty($schedule['start_datetime']) ) {
            return false;
        }
        switch( strtoupper($schedule['schedule_type']) ) {
        case 'ONCE':
            return $this->_checkONCE($schedule);

        case 'MINUTE':
            return $this->_checkMINUTE($schedule);

        case 'HOURLY':
            return $this->_checkHOURLY($schedule);

        case 'DAILY':
            return $this->_checkDAILY($schedule);

        case 'WEEKLY':
            return $this->_checkWEEKLY($schedule);

        case 'MONTHLY':
            return $this->_checkMONTHLY($schedule);

        default:
            return false;
        }
    }

    //執行計劃任務列表
    public function runScheduleList($scheduleList) {
        //dump($scheduleList);
        foreach( $scheduleList as $key => $schedule ) {
            $date = $this->calculateNextRunTime($schedule);
            if( $date != false && strtotime($date) <= strtotime(date('Y-m-d H:i:s')) ) {
                $this->runSchedule($schedule);
            }else {
                continue;
            }
        }
        return true;
    }

    //執行任務計劃
    public function runSchedule($schedule) {
        //解析task類型, 並運行task
        $task_to_run = explode('/',$schedule['task_to_run']);

        if($task_to_run[0] == 'addons'){
            //組裝執行程式碼 - 執行addons下的model
            $str = "model({$task_to_run[1]})->{$task_to_run[2]}(" . $this->fill_params($task_to_run['params']) . ');';
            eval($str);
        }else
            if($task_to_run['type'] == 'model'){
                //組裝執行程式碼
                $str = "D({$task_to_run[1]}, {$task_to_run[0]})->{$task_to_run[2]}(" . $this->fill_params($task_to_run['params']) . ');';
                eval($str);
            }else if($task_to_run['type'] == 'url') {
                //;
            }

        if(strtoupper($schedule['schedule_type']) == 'ONCE') {
            //ONCE類型的計劃任務，將end_datetime設定為當前時間
            $schedule['end_datetime'] = date('Y-m-d H:i:s');
        }else {
            //非ONCE類型的計劃任務， 防止由程式執行導致的啟動時間的漂移
            if(in_array($schedule['schedule_type'], array('MINUTE', 'HOURLY'))) {
                //將last_run_time設定為當前時間（秒數設為0）
                $schedule['last_run_time'] = date('Y-m-d H:i:s',$this->setSecondToZero());
            }else {
                //將last_run_time設定為當前日期+預定時間
                $now_date = date('Y-m-d');
                $fixed_time = date('H:i:s', strtotime($schedule['start_datetime']));
                $schedule['last_run_time'] = $now_date . ' ' . $fixed_time;
            }
        }
        $this->saveSchedule($schedule);
        $str_log = "schedule_id = {$schedule['id']} 的任務已運行。";
        if(C('APP_DEBUG')){
            $str_log  .= "任務url為: {$schedule['task_to_run']} ，任務描述為: {$schedule['info']} 。";
        }
        $this->_log($str_log);
    }

    //組裝參數
    private function fill_params($params = '') {
        $result = '';
        if(is_array($params)) {
            $flag = true;
            foreach ($params as $k => $v) {
                if($flag == true) {
                    $result = $result . $this->format_params($v);
                    $flag = false;
                }else {
                    $result = $result . ',' . $this->format_params($v);
                }
            }
        }else {
            $result = $params;
        }
        return $result;
    }

    //格式化參數
    private function format_params($params) {
        if(is_array($params)) {
            $result = 'Array(';
            foreach ($params as $k => $v) {
                $result = $result . "'$k'=>'$v',";
            }
            $result .= ')';
            return $result;
        }else {
            return '\'' . $params . '\'';
        }
    }

    //儲存一條任務計劃到資料庫
    //@return bool
    public function addSchedule($schedule = '') {
        if(empty($schedule)) {
            $schedule = $this->schedule;
        }

        //儲存到資料庫
        if( $this->isValidSchedule($schedule) ) {
            $schedule['start_datetime'] = date('Y-m-d H:i:s', $this->setSecondToZero($schedule['start_datetime']));
            $res = $this->add($schedule);
            $this->cleanCache();
            return $res;
        }else {
            return false;
        }
    }

    //更新一條任務計劃
    public function saveSchedule($schedule = '') {
        if(empty($schedule)) {
            $schedule = $this->schedule;
        }
        //更新到資料庫
        if( $this->isValidSchedule($schedule) ) {

            $data['last_run_time'] = $schedule['last_run_time'];
            $map['id'] = $schedule['id'];
            $res = $this->where($map)->save($data);
            $this->cleanCache();
            return $res;
        }else {
            return false;
        }
    }

    //查詢資料庫，獲取所有的計劃任務（包括過期的）
    //@return array()
    public function getScheduleList() {
        $this->scheduleList = S ( 'getScheduleList' );
        if ($this->scheduleList === false) {
            $this->scheduleList = $this->order ( 'id' )->findAll ();
            S ( 'getScheduleList', $this->scheduleList, 604800 ); // 快取一週
        }
        return $this->scheduleList;
    }

    //計算一個sechedule的下次執行時間
    //@return: 'Y-m-d H:i:s'
    public function calculateNextRunTime($schedule) {
        //已過期
        if( (strtotime($schedule['end_datetime'])>0) && (strtotime($schedule['end_datetime']) < strtotime(date('Y-m-d H:i:s'))) ) {
            return false;
        }
        //還未啟動
        if( strtotime($schedule['start_datetime']) > strtotime(date('Y-m-d H:i:s')) ) {
            return false;
        }
        //已執行
        if( strtotime($schedule['last_run_time']) > strtotime(date('Y-m-d H:i:s')) ) {
            return false;
        }

        switch( strtoupper($schedule['schedule_type']) ) {
        case 'ONCE':
            $datetime =  $this->_calculateONCE($schedule);
            break;
        case 'MINUTE':
            $datetime =  $this->_calculateMINUTE($schedule);
            break;
        case 'HOURLY':
            $datetime =  $this->_calculateHOURLY($schedule);
            break;
        case 'DAILY':
            $datetime =  $this->_calculateDAILY($schedule);
            break;
        case 'WEEKLY':
            $datetime =  $this->_calculateWEEKLY($schedule);
            break;
        case 'MONTHLY':
            $datetime =  $this->_calculateMONTHLY($schedule);
            break;
        default:
            return false;
        }
        return date('Y-m-d H:i:s', $datetime);
    }

    /*
     * Setter & Getter
     */
    public function getLogPath() {
        $logPath = DATA_PATH.'/schedule';
        if(!is_dir($logPath))
            @mkdir($logPath,0777);
        return $logPath;
    }

    public function getSchedule() {
        return $this->schedule;
    }

    public function setSchedule($schedule) {
        if( $this->isValidSchedule($schedule) ) {
            $this->schedule = $schedule;
            return  true;
        }else {
            return false;
        }
    }

    public function setTaskToRun($task_to_run) {
        $this->schedule['task_to_run'] = $task_to_run;
    }

    public function setScheduleType($schedule_type) {
        $this->schedule['schedule_type'] = $schedule_type;
    }

    public function setModifier($modifier) {
        $this->schedule['modifier'] = $modifier;
    }

    public function setDirlist($dirlist) {
        $this->schedule['dirlist'] = $dirlist;
    }

    public function setMonth($month) {
        $this->schedule['month'] = $month;
    }

    public function setStartDateTime($start_datetime) {
        $this->schedule['start_datetime'] = $start_datetime;
    }

    public function setEndDateTime($end_datetime) {
        $this->schedule['end_datetime'] = $end_datetime;
    }

    public function setLastRunTime($last_run_time) {
        $this->schedule['last_run_time'] = $last_run_time;
    }

    //根據計劃頻率檢查一個schedule是否合法

    protected function _checkONCE($schedule) {
        if( !empty($schedule['start_datetime']) ) {
            return (bool)strtotime($schedule['start_datetime']);
        }else {
            return false;
        }
    }

    protected function _checkMINUTE($schedule) {
        if( !empty($schedule['modifier']) && is_numeric($schedule['modifier']) ) {
            return ( ($schedule['modifier'] >= 1) && ($schedule['modifier'] <= 1439) );
        }

        return true;
    }

    protected function _checkHOURLY($schedule) {
        if( !empty($schedule['modifier']) ) {
            return ( is_numeric($schedule['modifier']) && ($schedule['modifier'] >= 1) && ($schedule['modifier'] <= 23) );
        }

        return true;
    }

    protected function _checkDAILY($schedule) {
        if( !empty($schedule['modifier']) ) {
            return ( is_numeric($schedule['modifier']) && ($schedule['modifier'] >= 1) && ($schedule['modifier'] <= 365) );
        }

        return true;
    }

    protected function _checkWEEKLY($schedule) {
        $flag = true;
        if( !empty($schedule['modifier']) ) {
            if( !is_numeric($schedule['modifier']) ) {
                return false;
            }
            $flag = ($schedule['modifier'] >= 1) && ($schedule['modifier'] <= 52);
        }
        if( ($flag != false) && !empty($schedule['dirlist']) ) {
            if($schedule['dirlist'] == '*') {
                return true;
            }else {
                $dirlist = explode(',', str_replace(' ', '',$schedule['dirlist']));
                foreach($dirlist as $v) {
                    $flag = $flag && in_array($v, $this->WEEK_ARRAY);
                    if($flag == false) {
                        //                      dump($v);
                        return false;
                    }//End if
                }//End foreach
            }//End else
        }
        return $flag;
    }

    protected function _checkMONTHLY($schedule) {
        // modifier為LASTDAY時month必須，否則可選
        // modifier為（FIRST,SECOND,THIRD,FOURTH,LAST）之一時：dirlist必須在MON～SUN、*中
        // modifier為1～12時dirlist可選. 1～31和空為有效值（默認是1）
        if( !empty($schedule['modifier'])) {
            //modifier為LASTDAY時month必須，否則可選
            if( strtoupper($schedule['modifier']) == 'LASTDAY' ) {
                if(empty($schedule['month'])) {
                    return false;
                }
            }else if( in_array(strtoupper($schedule['modifier']),array('FIRST','SECOND','THIRD','FOURTH','LAST')) ) {
                //modifier為FIRST,SECOND,THIRD,FOURTH,LAST之一時，dirlist必須在MON～SUN、*中
                if($schedule['dirlist'] == '*') {
                    ;
                }else {
                    $flag = true;
                    $dirlist = explode(',', str_replace(' ', '',$schedule['dirlist']));
                    foreach($dirlist as $v) {
                        $flag = $flag && in_array($v, $this->WEEK_ARRAY);
                        if($flag == false) {
                            //                          dump($v);
                            return false;
                        }//End if
                    }//End foreach
                }//End if...else
            }elseif ( is_numeric($schedule['modifier']) && ($schedule['modifier'] >= 1) && ($schedule['modifier'] <= 12) ) {
                //modifier為1～12時dirlist可選. 空、1～31為有效值（‘空’默認是1）
                if( !empty($schedule['dirlist']) ) {
                    $flag = true;
                    $dirlist = explode(',', str_replace(' ', '',$schedule['dirlist']));
                    foreach($dirlist as $v) {
                        $flag = $flag && (is_numeric($v) && ($v >= 1) && ($v <= 31));
                        if($flag == false) {
                            //                          dump($v);
                            return false;
                        }
                    }//End foreach
                }
                return true;
            }else {
                //modifier錯誤
                return false;
            }

            //month的有效值為JAN～DEC和*(每個月).默認為*
            if( !empty($schedule['month']) ) {
                if($schedule['month'] == '*') {
                    return true;
                }else {
                    $flag = true;
                    $month = explode(',', str_replace(' ', '', $schedule['month']));
                    foreach($month as $v) {
                        $flag = $flag && in_array($v, $this->MONTH_ARRAY);
                        if($flag == false) {
                            return false;
                        }
                    }//End foreach
                }//End if...else
            }

        }else {
            //modifier必須
            return false;
        }
        return true;
    }

    /*
     * 根據計劃頻率計算一個schedule的下次執行時間
     */
    protected function _calculateONCE($schedule) {
        return $this->_getStartDateTime($schedule);
    }

    protected function _calculateMINUTE($schedule) {
        //獲取計劃頻率
        $modifier = empty($schedule['modifier']) ? 1 : $schedule['modifier'];

        //當last_run_time不為空且大於start_datetime時，以last_run_time為基準時間。否則，以start_datetime為基準時間.
        if( !empty($schedule['last_run_time']) && (strtotime($schedule['last_run_time']) > strtotime($schedule['start_datetime']))) {
            $date = is_string($schedule['last_run_time']) ? strtotime($schedule['last_run_time']) : $schedule['last_run_time'];
        }else {
            $date = $this->_getStartDateTime($schedule);
        }

        return mktime(date('H',$date),date('i',$date) + $modifier,date('s',$date),date('m',$date),date('d',$date),date('Y',$date));
    }

    protected function _calculateHOURLY($schedule) {
        //獲取計劃頻率
        $modifier = empty($schedule['modifier']) ? 1 : $schedule['modifier'];

        //當last_run_time不為空時，根據last_run_time計算下次運行時間。否則，根據start_datetime計算.
        if( !empty($schedule['last_run_time']) && (strtotime($schedule['last_run_time']) > strtotime($schedule['start_datetime']))) {
            $date = is_string($schedule['last_run_time']) ? strtotime($schedule['last_run_time']) : $schedule['last_run_time'];
        }else {
            $date = $this->_getStartDateTime($schedule);
        }

        return mktime(date('H',$date) + $modifier,date('i',$date),date('s',$date),date('m',$date),date('d',$date),date('Y',$date));
    }

    protected function _calculateDAILY($schedule) {
        //獲取計劃頻率
        $modifier = empty($schedule['modifier']) ? 1 : $schedule['modifier'];

        //當last_run_time不為空時，根據last_run_time計算下次運行時間。否則，根據start_datetime計算.
        if( !empty($schedule['last_run_time']) && (strtotime($schedule['last_run_time']) > strtotime($schedule['start_datetime']))) {
            $date = is_string($schedule['last_run_time']) ? strtotime($schedule['last_run_time']) : $schedule['last_run_time'];
        }else {
            $date = $this->_getStartDateTime($schedule);
        }

        return mktime(date('H',$date),date('i',$date),date('s',$date),date('m',$date),date('d',$date) + $modifier,date('Y',$date));
    }

    protected function _calculateWEEKLY($schedule) {
        //獲取計劃頻率
        $modifier = empty($schedule['modifier']) ? 1 : $schedule['modifier'];

        //當last_run_time不為空時，以last_run_time為基準時間。否則，根據start_datetime計算基準時間.
        if( !empty($schedule['last_run_time']) && (strtotime($schedule['last_run_time']) > strtotime($schedule['start_datetime'])) ) {
            $date = is_string($schedule['last_run_time']) ? strtotime($schedule['last_run_time']) : $schedule['last_run_time'];
            $base_time_type = 'last_run_time';
        }else {
            $date = $this->_getStartDateTime($schedule);
            $base_time_type = 'start_datetime';
        }

        //判斷當前日期是否符合週數要求
        //計算方法：((當前日期的週數 - 基準日期的週數) % modifier == 0)
        if( (($this->_getWeekID() - $this->_getWeekID($date)) % $schedule['modifier']) == 0 ) {
            //組裝dirlist陣列
            if(empty($schedule['dirlist'])) {
                //當dirlist為空時,默認為週一
                $schedule['dirlist'] = array('Mon');
            }elseif ($schedule['dirlist'] == '*') {
                //當dirlist==*時，每天執行
                $schedule['dirlist'] = $this->WEEK_ARRAY;
            }else {
                $schedule['dirlist'] = explode(',', str_replace(' ','',$schedule['dirlist']));
            }
            //判斷今天是否在dirlist中。
            if( in_array(date('D'), $schedule['dirlist']) ) {
                //判斷今天是否已經執行過當前計劃。如果否，根據基準時間計算執行時間（DATE為今天，TIME來自基準時間）
                if( ($base_time_type == 'last_run_time') && ( date('Y-m-d',$date) == date('Y-m-d')) ) {
                    ;
                }else {
                    return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d'),date('Y'));
                }
            }
        }
        //如果當前日期不符合週數或星期的要求、或今天已經執行過，返回明天的同一時間（保證該條計劃任務現在不被執行）
        return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d') + 1,date('Y'));
    }

    protected function _calculateMONTHLY($schedule) {
        //當last_run_time不為空時，以last_run_time為基準時間。否則，根據start_datetime計算基準時間.
        if( !empty($schedule['last_run_time']) && (strtotime($schedule['last_run_time']) > strtotime($schedule['start_datetime'])) ) {
            $date = is_string($schedule['last_run_time']) ? strtotime($schedule['last_run_time']) : $schedule['last_run_time'];
            $base_time_type = 'last_run_time';
        }else {
            $date = $this->_getStartDateTime($schedule);
            $base_time_type = 'start_datetime';
        }

        //設定month陣列
        if( empty($schedule['month']) || $schedule['month'] == '*') {
            $schedule['month'] = $this->MONTH_ARRAY;
        }else {
            $schedule['month'] = explode(',', str_replace(' ','',$schedule['month']));
        }

        //modifier為LASTDAY時
        if( strtoupper($schedule['modifier']) == 'LASTDAY' ) {

            //判斷月份是否符合要求、且當前日期為月的最後一天
            if( in_array(date('M'), $schedule['month']) && $this->_isLastDayOfMonth() ) {
                //判斷今天是否已經執行過當前計劃。如果否，根據基準時間計算執行時間（DATE為今天，TIME來自基準時間）
                if( ($base_time_type == 'last_run_time') && ( date('Y-m-d',$date) == date('Y-m-d')) ) {
                    ;
                }else {
                    return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d'),date('Y'));
                }
            }
            //modifier為FIRST,SECOND,THIRD,FOURTH,LAST之一時
        }elseif ( in_array(strtoupper($schedule['modifier']),array('FIRST','SECOND','THIRD','FOURTH','LAST')) ) {
            //判斷當前月份是否符合要求
            if( in_array(date('M'), $schedule['month']) ) {
                //設定dirlist陣列(星期)
                if ($schedule['dirlist'] == '*') {
                    $schedule['dirlist'] = $this->WEEK_ARRAY;
                }else {
                    $schedule['dirlist'] = explode(',', str_replace(' ','',$schedule['dirlist']));
                }

                //判斷星期是否符合要求
                if( in_array(date('D'), $schedule['dirlist']) ) {
                    //判斷第x個是否符合要求
                    if($this->_isDayIDOfMonth($schedule['modifier'])) {
                        //判斷今天是否已經執行過當前計劃。如果否，根據基準時間計算執行時間（DATE為今天，TIME來自基準時間）
                        if( ($base_time_type == 'last_run_time') && ( date('Y-m-d',$date) == date('Y-m-d')) ) {
                            ;
                        }else {
                            return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d'),date('Y'));
                        }
                    }
                }
            }
            //modifier為1～12時
        }elseif ( is_numeric($schedule['modifier']) ) {
            //判斷當前月份是否符合要求
            if( ($this->_getMonthDif($date) % $schedule['modifier']) == 0 ) {
                //組裝dirlist陣列
                if(empty($schedule['dirlist'])) {
                    $schedule['dirlist'] = array('1');
                } else{
                    $schedule['dirlist'] = explode(',', str_replace(' ','',$schedule['dirlist']));
                }

                //判斷當期日期是否符合要求
                if( in_array(date('d'),$schedule['dirlist']) || in_array(date('j'),$schedule['dirlist']) ) {
                    //判斷今天是否已經執行過當前計劃。如果否，根據基準時間計算執行時間（DATE為今天，TIME來自基準時間）
                    if( ($base_time_type == 'last_run_time') && ( date('Y-m-d',$date) == date('Y-m-d')) ) {
                        ;
                    }else {
                        return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d'),date('Y'));
                    }
                }
            }
        }

        //如果當前日期不符合月份/星期/日期的要求、或今天已經執行過，返回明天的同一時間（保證該條計劃任務現在不被執行）
        return mktime(date('H',$date),date('i',$date),date('s',$date),date('m'),date('d') + 1,date('Y'));
    }

    //獲取開始時間
    //@return timestamp
    protected function _getStartDateTime($schedule) {
        if( !empty($schedule['start_datetime']) ) {
            return strtotime($schedule['start_datetime']);
        }else {
            return false;
        }
    }

    //判斷當前日期是否為當前月的最後一天
    protected function _isLastDayOfMonth($date = '') {
        if (empty($date)) {
            $date = strtotime(date('Y-m-d H:i:s'));
        }
        $date = is_string($date) ? strtotime($date) : $date;
        return ( date('m',$date) != date('m',mktime(date('H',$date),date('i',$date),date('s',$date),date('m',$date),date('d',$date) + 1,date('Y',$date))) );
    }

    //判斷當前日期是否為當前月的第x個星期x
    protected function _isDayIDOfMonth($key, $date = '') {
        if (empty($date)) {
            $date = strtotime(date('Y-m-d H:i:s'));
        }
        $date = is_string($date) ? strtotime($date) : $date;

        $index = 0;
        switch( strtoupper($key) ) {
        case 'FIRST':
            $index = 1;
            break;
        case 'SECOND':
            $index = 2;
            break;
        case 'THIRD':
            $index = 3;
            break;
        case 'FOURTH':
            $index = 4;
            break;
        case 'LAST':
            $index = 0;
            break;
        default:
            return false;
        }
        if($index != 0) {
            return ((date('m',$date) == date('m',mktime(date('H',$date),date('i',$date),date('s',$date),date('m',$date),date('d',$date) - (7 * ($index-1)),date('Y',$date)))) &&
                (date('m',$date) != date('m',mktime(date('H',$date),date('i',$date),date('s',$date),date('m',$date),date('d',$date) - (7 * ($index)),date('Y',$date)))));
        }else {
            return (date('m',$date) != date('m',mktime(date('H',$date),date('i',$date),date('s',$date),date('m',$date),date('d',$date) + 7,date('Y',$date))));
        }
    }

    //返回自2007年01月01日來的週數
    protected function _getWeekID($date = '') {
        $date_base = strtotime('2007-01-01');//2007-01-01為週一，定為第一週
        //輸入日期為空時，使用當前時間
        if(empty($date)) {
            $date = strtotime(date('Y-m-d'));
        }else {
            $date = is_string($date) ? strtotime($date) : $date;
        }
        return (int)floor(($date - $date_base)/3600/24/7) + 1;
        }

        //返回自2007年01月01日來的月數
        protected function _getMonthDif($date1, $date2 = '') {
            $date1 = is_string($date1) ? strtotime($date1) : $date1;
            $date2 = empty($date2) ? date('Y-m-d') : $date2;
            $date2 = is_string($date2) ? strtotime($date2) : $date2;

            return ((date('Y',$date2) - date('Y',$date1)) * 12 + (date('n',$date2) - date('n',$date1)) );
        }

        //日志檔案
        protected function _log($str) {
            $filename = $this->getLogPath() . '/schedule_' . date('Y-m-d') . '.log';

            $str = '[' . date('Y-m-d H:i:s') . '] ' . $str;
            $str .= "\r\n";

            $handle = fopen($filename, 'a');
            fwrite($handle, $str);
            fclose($handle);
        }

        //將給定時間的秒數置為0; 參數為空時，使用當前時間
        protected function setSecondToZero($date_time = NULL) {
            if(empty($date_time)) {
                $date_time = date('Y-m-d H:i:s');
        }
        $date_time = is_string($date_time) ? strtotime($date_time) : $date_time;
        return mktime(date('H', $date_time),
                      date('i', $date_time),
                      0,
                      date('m', $date_time),
                      date('d', $date_time),
                      date('Y', $date_time));
    }

    //繼承實現父類函數
    public function run() {
        //鎖定自動執行 修正一下
        $lockfile = $this->getLogPath() . '/schedule.lock';
        //鎖定未過期 - 返回
        if( file_exists($lockfile) && ( (filemtime($lockfile))+60 > $_SERVER['REQUEST_TIME'] )){
            return ;
    } else {
        //重新生成鎖檔案
        touch($lockfile);
    }

    //忽略中斷\忽略過期
    set_time_limit(0);
    ignore_user_abort(true);

    //執行計劃任務
    $this->runScheduleList($this->getScheduleList());

    //解除鎖定
    unlink($lockfile);
    return ;
    }
    /**
     * 清除快取
     * @return void
     */
    public function cleanCache() {
        S ( 'getScheduleList', null );
    }
    }
?>
