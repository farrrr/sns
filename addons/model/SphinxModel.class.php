<?php
/**
 * 搜索模型 - 業務邏輯模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class SphinxModel {

    private $tableName = 'user';
    private $host = 'dev.zhishisoft.com';
    private $port = 9306;
    private $sdb;       // 例項

    /**
     * 初始化方法，連結搜索引擎資料庫
     * sphinx1.10開始支援mysql協議
     */
    public function __construct(){
        $connection = array(
            'dbms'=>'mysql',
            'hostname'=>(C('SEARCHD_HOST') ? C('SEARCHD_HOST') : $this->host),
            'hostport'=>(C('SEARCHD_PORT') ? C('SEARCHD_PORT') : $this->port)
        );
        $this->sdb = new Db($connection);
    }

    /**
     * 重置資料庫連結
     * @param string $host 主機地址IP
     * @param integer $port 埠號
     * @return object 搜索模型物件
     */
    public function connect($host, $port = 9306) {
        $connection = array(
            'dbms'=>'mysql',
            'hostname'=>$host,
            'hostport'=>$port
        );
        $this->sdb = new Db($connection);
        return $this;
    }

    /**
     * 直接搜索sphinx，結果未處理
     * @param string $query SQL查詢語句
     * @return array 查詢出的相應結果
     */
    public function query($query) {
        $list = $this->sdb->query($query);
        return $list;
    }

    /**
     * 執行搜素，結果有處理
     * @param string $query SQL查詢語句
     * @param integer $limit 結果集數目，默認為20
     * @return array 查詢出的相應結果
     */
    public function search($query, $limit = 20) {
        // 搜索值為空，返回結果
        if(empty($query)) {
            return false;
        }

        $query .=" limit ".$this->getLimit($limit);     // limit處理
        $datas  =   $this->sdb->query($query);          // 執行SphinxQL查詢

        if(!$datas) {
            return false;
        }
        // 獲取關鍵詞資訊
        $metas = $this->sdb->query("SHOW META");
        if(!$metas) {
            return false;
        }
        // 處理資料
        foreach($metas as $v) {
            if($v['Variable_name'] == 'total_found') {
                $data['count'] = $v['Value'];
            }
            if($v['Variable_name'] == 'time') {
                $data['time'] = $v['Value'];
            }
            if(is_numeric($k = str_replace(array('keyword','[',']'), '', $v['Variable_name']))) {
                $data['matchwords'][$k]['keyword'] = $v['Value'];
                $data['keywords'][] = $v['Value'];
            }
            if(is_numeric($k = str_replace(array('docs','[',']'), '', $v['Variable_name']))) {
                $data['matchwords'][$k]['docs'] = $v['Value'];
            }
            if(is_numeric($k = str_replace(array('hits','[',']'), '', $v['Variable_name']))) {
                $data['matchwords'][$k]['hits'] = $v['Value'];
            }
        }
        $p = new Page($data['count'], $limit);
        $data['totalPages'] = $p->totalPages;
        $data['html'] = $p->show();
        $data['data'] = $datas;

        return $data;
    }

    /**
     * 獲取分頁數，默認為1
     * @return integer 分頁數
     */
    public function getPage() {
        return !empty($_GET[C('VAR_PAGE')]) && ($_GET[C('VAR_PAGE')] > 0) ? intval($_GET[C('VAR_PAGE')]) : 1;
    }

    /**
     * 獲取limit查詢條件
     * @param integer $limit 結果集數目，默認為20
     * @return string limit查詢條件
     */
    public function getLimit($limit = 20) {
        $nowPage = $this->getPage();
        $now = intval(abs($nowPage - 1) * $limit);
        return  $now.','.$limit;
    }
}
