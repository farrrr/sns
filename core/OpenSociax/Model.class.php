<?php
define('HAS_ONE',1);
define('BELONGS_TO',2);
define('HAS_MANY',3);
define('MANY_TO_MANY',4);
/**
 * ThinkPHP Model模型類
 * 實現了ORM和ActiveRecords模式
 * @author    liu21st <liu21st@gmail.com>
 * @version   $Id$
 */
class Model extends Think
{
    // 操作狀態
    const MODEL_INSERT          =   1;  //  插入模型資料
    const MODEL_UPDATE          =   2;  //  更新模型資料
    const MODEL_BOTH            =   3;  //  包含上面兩種方式
    const MUST_VALIDATE         =   1;  //  必須驗證
    const EXISTS_VAILIDATE      =   0;  //  表單存在欄位則驗證
    const VALUE_VAILIDATE       =   2;  //  表單值不為空則驗證
    // 當前使用的擴展模型
    private $_extModel =  null;
    // 當前資料庫操作物件
    protected $db = null;
    // 主鍵名稱
    protected $pk  = 'id';
    // 資料表字首
    protected $tablePrefix  =   '';
    // 資料表字尾
    protected $tableSuffix   =  '';
    // 模型名稱
    protected $name = '';
    // 資料庫名稱
    protected $dbName  = '';
    // 資料表名（不包含表字首）
    protected $tableName = '';
    // 實際資料表名（包含表字首）
    protected $trueTableName ='';
    // 最近錯誤資訊
    protected $error = '';
    // 欄位資訊
    protected $fields = array();
    // 資料資訊
    protected $data =   array();
    // 查詢表示式參數
    protected $options  =   array();
    protected $_validate       = array();  // 自動驗證定義
    protected $_auto           = array();  // 自動完成定義
    protected $_map           = array();  // 欄位對映定義
    // 是否自動檢測資料表欄位資訊
    protected $autoCheckFields   =   true;
    protected $forceCheckFields   =   false;

    /**
     * 架構函數
     * 取得DB類的例項物件 欄位檢查
     * @param string $name 模型名稱
     * @access public
     */
    public function __construct($name='')
    {
        // 模型初始化
        $this->_initialize();
        // 獲取模型名稱
        if(!empty($name)) {
            $this->name   =  $name;
        }elseif(empty($this->name)){
            $this->name =   $this->getModelName();
        }
        // 資料庫初始化操作
        // 獲取資料庫操作物件
        // 當前模型有獨立的資料庫連線資訊
        $this->db = Db::getInstance(empty($this->connection)?'':$this->connection);

        // 設定表字首
        $this->tablePrefix = $this->tablePrefix?$this->tablePrefix:C('DB_PREFIX');
        $this->tableSuffix = $this->tableSuffix?$this->tableSuffix:C('DB_SUFFIX');
        // 欄位檢測
        if(!empty($this->name) && $this->autoCheckFields)    $this->_checkTableInfo();
        //TODO  臨時強制要求
        if(!empty($this->tableName) && empty($this->fields)) throw_exception('開發階段,請為你的model填寫fields!');
    }

    /**
     * 自動檢測資料表資訊
     * @access protected
     * @return void
     */
    protected function _checkTableInfo() {
        // 如果不是Model類 自動記錄資料表資訊
        // 只在第一次執行記錄
        if(empty($this->fields)) {
            //是否強制檢查欄位配置
            if($this->forceCheckFields)
                throw_exception($class.L('_FIELDS_IS_EMPTYNOT_'));

            // 如果資料表欄位沒有定義則自動獲取
            if(C('DB_FIELDS_CACHE')) {
                $this->fields = S('_fields_'.$this->name);
                if(!$this->fields)   $this->flush();
            }else{
                // 每次都會讀取資料表資訊
                $this->flush();
            }
        }
    }

    /**
     * 獲取欄位資訊並快取
     * @access public
     * @return void
     */
    public function flush() {
        // 快取不存在則查詢資料表資訊
        $fields =   $this->db->getFields($this->getTableName());
        $this->fields   =   array_keys($fields);
        $this->fields['_autoinc'] = false;
        foreach ($fields as $key=>$val){
            // 記錄欄位類型
            $type[$key]    =   $val['type'];
            if($val['primary']) {
                $this->fields['_pk'] = $key;
                if($val['autoinc']) $this->fields['_autoinc']   =   true;
            }
        }
        // 記錄欄位類型資訊
        if(C('DB_FIELDTYPE_CHECK'))   $this->fields['_type'] =  $type;

        // 2008-3-7 增加快取開關控制
        if(C('DB_FIELDS_CACHE'))
            // 永久快取資料表資訊
            S('_fields_'.$this->name,$this->fields);
    }

    /**
     * 動態切換擴展模型
     * @access public
     * @param string $type 模型類型名稱
     * @param mixed $vars 要傳入擴展模型的屬性變數
     * @return Model
     */
    public function switchModel($type,$vars=array()) {
        $class = ucwords(strtolower($type)).'Model';
        if(!class_exists($class))
            throw_exception($class.L('_MODEL_NOT_EXIST_'));
        // 例項化擴展模型
        $this->_extModel   = new $class($this->name);
        if(!empty($vars)) {
            // 傳入當前模型的屬性到擴展模型
            foreach ($vars as $var)
                $this->_extModel->setProperty($var,$this->$var);
        }
        return $this->_extModel;
    }

    /**
     * 設定資料物件的值
     * @access public
     * @param string $name 名稱
     * @param mixed $value 值
     * @return void
     */
    public function __set($name,$value) {
        // 設定資料物件屬性
        $this->data[$name]  =   $value;
    }

    /**
     * 獲取資料物件的值
     * @access public
     * @param string $name 名稱
     * @return mixed
     */
    public function __get($name) {
        return isset($this->data[$name])?$this->data[$name]:null;
    }

    /**
     * 檢測資料物件的值
     * @access public
     * @param string $name 名稱
     * @return boolean
     */
    public function __isset($name) {
        return isset($this->data[$name]);
    }

    /**
     * 銷燬資料物件的值
     * @access public
     * @param string $name 名稱
     * @return void
     */
    public function __unset($name) {
        unset($this->data[$name]);
    }

    /**
     * 利用__call方法實現一些特殊的Model方法
     * @access public
     * @param string $method 方法名稱
     * @param array $args 呼叫參數
     * @return mixed
     */
    public function __call($method,$args) {
        if(in_array(strtolower($method),array('field','table','where','order','limit','page','having','group','lock','distinct'),true)) {
            // 連貫操作的實現
            $this->options[strtolower($method)] =   $args[0];
            return $this;
        }elseif(in_array(strtolower($method),array('count','sum','min','max','avg'),true)){
            // 統計查詢的實現
            $field =  isset($args[0])?$args[0]:'*';
            return $this->getField(strtoupper($method).'('.$field.') AS tp_'.$method);
        }elseif(strtolower(substr($method,0,5))=='getby') {
            // 根據某個欄位獲取記錄
            $field   =   parse_name(substr($method,5));
            $options['where'] =  $field.'=\''.$args[0].'\'';
            return $this->find($options);
        }else{
            throw_exception(__CLASS__.':'.$method.L('_METHOD_NOT_EXIST_'));
            return;
        }
    }
    // 回撥方法 初始化模型
    protected function _initialize() {}
        //     protected function getSourceInfo() {}

        /**
         * 對儲存到資料庫的資料進行處理
         * @access protected
         * @param mixed $data 要操作的資料
         * @return boolean
         */
        protected function _facade($data) {
            // 檢查非資料欄位
            if(!empty($this->fields)) {
                foreach ($data as $key=>$val){
                    if(!in_array($key,$this->fields,true)){
                        unset($data[$key]);
                    }elseif(C('DB_FIELDTYPE_CHECK') && is_scalar($val)) {
                        // 欄位類型檢查
                        $fieldType = strtolower($this->fields['_type'][$key]);
                        if(false !== strpos($fieldType,'int')) {
                            $data[$key]   =  intval($val);
                        }elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
                            $data[$key]   =  floatval($val);
                        }
                    }
                }
            }
            $this->_before_write($data);
            return $data;
        }

    // 寫入資料前的回撥方法 包括新增和更新
    protected function _before_write(&$data) {}

        /**
         * 新增資料
         * @access public
         * @param mixed $data 資料
         * @param array $options 表示式
         * @return mixed
         */
        public function add($data='',$options=array()) {
            if(empty($data)) {
                // 沒有傳遞資料，獲取當前資料物件的值
                if(!empty($this->data)) {
                    $data    =   $this->data;
                }else{
                    $this->error = L('_DATA_TYPE_INVALID_');
                    return false;
                }
            }
            // 分析表示式
            $options =  $this->_parseOptions($options);
            // 資料處理
            $data = $this->_facade($data);

            if(false === $this->_before_insert($data,$options)) {
                return false;
            }
            // 寫入資料到資料庫
            $result = $this->db->insert($data,$options);
            if(false !== $result ) {
                $insertId   =   $this->getLastInsID();
                if($insertId) {
                    // 自增主鍵返回插入ID
                    $data[$this->getPk()]  = $insertId;
                    $this->_after_insert($data,$options);
                    return $insertId;
                }
            }
            return $result;
        }
    // 插入資料前的回撥方法
    protected function _before_insert(&$data,$options) {}
        // 插入成功後的回撥方法
        protected function _after_insert($data,$options) {}

        /**
         * 通過Select方式添加記錄
         * @access public
         * @param string $fields 要插入的資料表欄位名
         * @param string $table 要插入的資料表名
         * @param array $options 表示式
         * @return boolean
         */
        public function selectAdd($fields='',$table='',$options=array()) {
            // 分析表示式
            $options =  $this->_parseOptions($options);
            // 寫入資料到資料庫
            if(false === $result = $this->db->selectInsert($fields?$fields:$options['field'],$table?$table:$this->getTableName(),$options)){
                // 資料庫插入操作失敗
                $this->error = L('_OPERATION_WRONG_');
                return false;
            }else {
                // 插入成功
                return $result;
            }
        }

    /**
     * 儲存資料
     * @access public
     * @param mixed $data 資料
     * @param array $options 表示式
     * @return boolean
     */
    public function save($data='',$options=array()) {
        if(empty($data)) {
            // 沒有傳遞資料，獲取當前資料物件的值
            if(!empty($this->data)) {
                $data    =   $this->data;
            }else{
                $this->error = L('_DATA_TYPE_INVALID_');
                return false;
            }
        }
        // 資料處理
        $data = $this->_facade($data);
        // 分析表示式
        $options =  $this->_parseOptions($options);
        if(false === $this->_before_update($data,$options)) {
            return false;
        }
        if(!isset($options['where']) ) {
            // 如果存在主鍵資料 則自動作為更新條件
            if(isset($data[$this->getPk()])) {
                $pk   =  $this->getPk();
                $options['where']  =  $pk.'=\''.$data[$pk].'\'';
                $pkValue = $data[$pk];
                unset($data[$pk]);
            }else{
                // 如果沒有任何更新條件則不執行
                $this->error = L('_OPERATION_WRONG_');
                return false;
            }
        }
        $result = $this->db->update($data,$options);
        if(false !== $result) {
            if(isset($pkValue)) $data[$pk]   =  $pkValue;
            $this->_after_update($data,$options);
        }
        return $result;
    }
    // 更新資料前的回撥方法
    protected function _before_update(&$data,$options) {}
        // 更新成功後的回撥方法
        protected function _after_update($data,$options) {}

        /**
         * 刪除資料
         * @access public
         * @param mixed $options 表示式
         * @return mixed
         */
        public function delete($options=array()) {
            if(empty($options) && empty($this->options)) {
                // 如果刪除條件為空 則刪除當前資料物件所對應的記錄
                if(!empty($this->data) && isset($this->data[$this->getPk()]))
                    return $this->delete($this->data[$this->getPk()]);
                else
                    return false;
            }
            if(is_numeric($options)  || is_string($options)) {
                // 根據主鍵刪除記錄
                $pk   =  $this->getPk();
                if(strpos($options,',')) {
                    $where  =  $pk.' IN ('.$options.')';
                }else{
                    $where  =  $pk.'=\''.$options.'\'';
                    $pkValue = $options;
                }
                $options =  array();
                $options['where'] =  $where;
            }
            // 分析表示式
            $options =  $this->_parseOptions($options);
            $result=    $this->db->delete($options);
            if(false !== $result) {
                $data = array();
                if(isset($pkValue)) $data[$pk]   =  $pkValue;
                $this->_after_delete($data,$options);
            }
            // 返回刪除記錄個數
            return $result;
        }
    // 刪除成功後的回撥方法
    protected function _after_delete($data,$options) {}

        /**
         * 查詢資料集
         * @access public
         * @param array $options 表示式參數
         * @return mixed
         */
        public function select($options=array()) {
            if(is_string($options) || is_numeric($options)) {
                // 根據主鍵查詢
                $where   =  $this->getPk().' IN ('.$options.')';
                $options =  array();
                $options['where'] =  $where;
            }
            // 分析表示式
            $options =  $this->_parseOptions($options);
            $resultSet = $this->db->select($options);
            if(false === $resultSet) {
                return false;
            }
            if(empty($resultSet)) { // 查詢結果為空
                return null;
            }
            $this->_after_select($resultSet,$options);
            return $resultSet;
        }
    // 查詢成功後的回撥方法
    protected function _after_select(&$resultSet,$options) {}

        public function findAll($options=array()) {

            return $this->select($options);
        }

    public function getAsFieldArray($field='*'){
        $options =  $this->_parseOptions(array());
        $resultSet = $this->db->select($options,'getAsFieldArray',$field);

        if(false === $resultSet) {
            return false;
        }
        if(empty($resultSet)) { // 查詢結果為空
            return null;
        }
        return $resultSet;
    }
    public function getHashList($hashKey='',$hashValue='*'){
        $options =  $this->_parseOptions(array());
        $resultSet = $this->db->select($options,'getHashList',$hashKey,$hashValue);
        if(false === $resultSet) {
            return false;
        }
        if(empty($resultSet)) { // 查詢結果為空
            return null;
        }
        return $resultSet;
    }
    /**
     * 分析表示式
     * @access private
     * @param array $options 表示式參數
     * @return array
     */
    private function _parseOptions($options) {
        if(is_array($options))
            $options =  array_merge($this->options,$options);
        // 查詢過後清空sql表示式組裝 避免影響下次查詢
        $this->options  =   array();
        if(!isset($options['table']))
            // 自動獲取表名
            $options['table'] =$this->getTableName();
        // 欄位類型驗證

        if(C('DB_FIELDTYPE_CHECK')) {
            if(isset($options['where']) && is_array($options['where'])) {
                // 對陣列查詢條件進行欄位類型檢查
                foreach ($options['where'] as $key=>$val){
                    if(in_array($key,$this->fields,true) && is_scalar($val)){
                        $fieldType = strtolower($this->fields['_type'][$key]);
                        if(false !== strpos($fieldType,'int')) {
                            $options['where'][$key]   =  intval($val);
                        }elseif(false !== strpos($fieldType,'float') || false !== strpos($fieldType,'double')){
                            $options['where'][$key]   =  floatval($val);
                        }
                    }
                }
            }
        }
        // 表示式過濾
        $this->_options_filter($options);
        return $options;
    }
    // 表示式過濾回撥方法
    protected function _options_filter(&$options) {}

        /**
         * 查詢資料
         * @access public
         * @param mixed $options 表示式參數
         * @return mixed
         */
        public function find($options=array()) {
            if(is_numeric($options) || is_string($options)) {
                $where  =  $this->getPk().'=\''.$options.'\'';
                $options = array();
                $options['where'] = $where;
            }
            // 總是查找一條記錄
            $options['limit'] = 1;
            // 分析表示式
            $options =  $this->_parseOptions($options);
            $resultSet = $this->db->select($options);
            if(false === $resultSet) {
                return false;
            }
            if(empty($resultSet)) {// 查詢結果為空
                return null;
            }
            $this->data = $resultSet[0];
            $this->_after_find($this->data,$options);
            return $this->data;
        }
    // 查詢成功的回撥方法
    protected function _after_find(&$result,$options) {}

        /**
         * 設定記錄的某個欄位值
         * 支援使用資料庫欄位和方法
         * @access public
         * @param string|array $field  欄位名
         * @param string|array $value  欄位值
         * @param mixed $condition  條件
         * @return boolean
         */
        public function setField($field,$value,$condition='') {
            if(empty($condition) && isset($this->options['where']))
                $condition   =  $this->options['where'];
            $options['where'] =  $condition;
            if(is_array($field)) {
                foreach ($field as $key=>$val)
                    $data[$val]    = $value[$key];
            }else{
                $data[$field]   =  $value;
            }
            return $this->save($data,$options);
        }

    /**
     * 欄位值增長
     * @access public
     * @param string $field  欄位名
     * @param mixed $condition  條件
     * @param integer $step  增長值
     * @return boolean
     */
    public function setInc($field,$condition='',$step=1) {
        return $this->setField($field,array('exp',$field.'+'.$step),$condition);
    }

    /**
     * 欄位值減少
     * @access public
     * @param string $field  欄位名
     * @param mixed $condition  條件
     * @param integer $step  減少值
     * @return boolean
     */
    public function setDec($field,$condition='',$step=1) {
        return $this->setField($field,array('exp',$field.'-'.$step),$condition);
    }

    /**
     * 獲取一條記錄的某個欄位值
     * @access public
     * @param string $field  欄位名
     * @param mixed $condition  查詢條件
     * @param string $spea  欄位資料間隔符號
     * @return mixed
     */
    public function getField($field,$condition='',$sepa=' ') {
        if(empty($condition) && isset($this->options['where']))
            $condition   =  $this->options['where'];
        $options['where'] =  $condition;
        $options['field']    =  $field;
        $options =  $this->_parseOptions($options);
        if(strpos($field,',')) { // 多欄位
            $resultSet = $this->db->select($options);
            if(!empty($resultSet)) {
                $field  =   explode(',',$field);
                $key =  array_shift($field);
                $cols   =   array();
                foreach ($resultSet as $result){
                    $name   = $result[$key];
                    $cols[$name] =  '';
                    foreach ($field as $val)
                        $cols[$name] .=  $result[$val].$sepa;
                    $cols[$name]  = substr($cols[$name],0,-strlen($sepa));
                }
                return $cols;
            }
        }else{   // 查找一條記錄
            $options['limit'] = 1;
            $result = $this->db->select($options);
            if(!empty($result)) {
                return reset($result[0]);
            }
        }
        return null;
    }

    /**
     * 創建資料物件 但不儲存到資料庫
     * @access public
     * @param mixed $data 創建資料
     * @param string $type 狀態
     * @return mixed
     */
    public function create($data='',$type='') {
        // 如果沒有傳值默認取POST資料
        if(empty($data)) {
            $data    =   $_POST;
        }elseif(is_object($data)){
            $data   =   get_object_vars($data);
        }elseif(!is_array($data)){
            $this->error = L('_DATA_TYPE_INVALID_');
            return false;
        }
        // 狀態
        $type = $type?$type:(!empty($data[$this->getPk()])?self::MODEL_UPDATE:self::MODEL_INSERT);

        // 表單令牌驗證
        if(C('TOKEN_ON') && !$this->autoCheckToken($data)) {
            $this->error = L('_TOKEN_ERROR_');
            return false;
        }
        // 資料自動驗證
        if(!$this->autoValidation($data,$type)) return false;

        // 檢查欄位對映
        if(!empty($this->_map)) {
            foreach ($this->_map as $key=>$val){
                if(isset($data[$key])) {
                    $data[$val] =   $data[$key];
                    unset($data[$key]);
                }
            }
        }
        // 驗證完成生成資料物件
        $vo   =  array();
        foreach ($this->fields as $key=>$name){
            if(substr($key,0,1)=='_') continue;
            $val = isset($data[$name])?$data[$name]:null;
            //保證賦值有效
            if(!is_null($val)){
                $vo[$name] = (MAGIC_QUOTES_GPC && is_string($val))?   stripslashes($val)  :  $val;
            }
        }
        // 創建完成對資料進行自動處理
        $this->autoOperation($vo,$type);
        // 賦值當前資料物件
        $this->data =   $vo;
        // 返回創建的資料以供其他呼叫
        return $vo;
    }

    // 自動錶單令牌驗證
    public function autoCheckToken($data) {
        $name   = C('TOKEN_NAME');
        if(isset($_SESSION[$name])) {
            // 當前需要令牌驗證
            if(empty($data[$name]) || $_SESSION[$name] != $data[$name]) {
                // 非法提交
                return false;
            }
            // 驗證完成銷燬session
            unset($_SESSION[$name]);
        }
        return true;
    }

    /**
     * 使用正則驗證資料
     * @access public
     * @param string $value  要驗證的資料
     * @param string $rule 驗證規則
     * @return boolean
     */
    public function regex($value,$rule) {
        $validate = array(
            'require'=> '/.+/',
            'email' => '/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/',
            'url' => '/^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/',
            'currency' => '/^\d+(\.\d+)?$/',
            'number' => '/\d+$/',
            'zip' => '/^[1-9]\d{5}$/',
            'integer' => '/^[-\+]?\d+$/',
            'double' => '/^[-\+]?\d+(\.\d+)?$/',
            'english' => '/^[A-Za-z]+$/',
        );
        // 檢查是否有內建的正規表示式
        if(isset($validate[strtolower($rule)]))
            $rule   =   $validate[strtolower($rule)];
        return preg_match($rule,$value)===1;
    }

    /**
     * 自動錶單處理
     * @access public
     * @param array $data 創建資料
     * @param string $type 創建類型
     * @return mixed
     */
    private function autoOperation(&$data,$type) {
        // 自動填充
        if(!empty($this->_auto)) {
            foreach ($this->_auto as $auto){
                // 填充因子定義格式
                // array('field','填充內容','填充條件','附加規則',[額外參數])
                if(empty($auto[2])) $auto[2] = self::MODEL_INSERT; // 默認為新增的時候自動填充
                if( $type == $auto[2] || $auto[2] == self::MODEL_BOTH) {
                    switch($auto[3]) {
                    case 'function':    //  使用函數進行填充 欄位的值作為參數
                    case 'callback': // 使用回撥方法
                        $args = isset($auto[4])?$auto[4]:array();
                        if(isset($data[$auto[0]])) {
                            array_unshift($args,$data[$auto[0]]);
                        }
                        if('function'==$auto[3]) {
                            $data[$auto[0]]  = call_user_func_array($auto[1], $args);
                        }else{
                            $data[$auto[0]]  =  call_user_func_array(array(&$this,$auto[1]), $args);
                        }
                        break;
                    case 'field':    // 用其它欄位的值進行填充
                        $data[$auto[0]] = $data[$auto[1]];
                        break;
                    case 'string':
                    default: // 默認作為字元串填充
                        $data[$auto[0]] = $auto[1];
                    }
                    if(false === $data[$auto[0]] )   unset($data[$auto[0]]);
                }
            }
        }
        return $data;
    }

    /**
     * 自動錶單驗證
     * @access public
     * @param array $data 創建資料
     * @param string $type 創建類型
     * @return boolean
     */
    private function autoValidation($data,$type) {
        // 屬性驗證
        if(!empty($this->_validate)) {
            // 如果設定了資料自動驗證
            // 則進行資料驗證
            // 重置驗證錯誤資訊
            foreach($this->_validate as $key=>$val) {
                // 驗證因子定義格式
                // array(field,rule,message,condition,type,when,params)
                // 判斷是否需要執行驗證
                if(empty($val[5]) || $val[5]== self::MODEL_BOTH || $val[5]== $type ) {
                    if(0==strpos($val[2],'{%') && strpos($val[2],'}'))
                        // 支援提示資訊的多語言 使用 {%語言定義} 方式
                        $val[2]  =  L(substr($val[2],2,-1));
                    $val[3]  =  isset($val[3])?$val[3]:self::EXISTS_VAILIDATE;
                    $val[4]  =  isset($val[4])?$val[4]:'regex';
                    // 判斷驗證條件
                    switch($val[3]) {
                    case self::MUST_VALIDATE:   // 必須驗證 不管表單是否有設定該欄位
                        if(false === $this->_validationField($data,$val)){
                            $this->error    =   $val[2];
                            return false;
                        }
                        break;
                    case self::VALUE_VAILIDATE:    // 值不為空的時候才驗證
                        if('' != trim($data[$val[0]])){
                            if(false === $this->_validationField($data,$val)){
                                $this->error    =   $val[2];
                                return false;
                            }
                        }
                        break;
                    default:    // 默認表單存在該欄位就驗證
                        if(isset($data[$val[0]])){
                            if(false === $this->_validationField($data,$val)){
                                $this->error    =   $val[2];
                                return false;
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * 根據驗證因子驗證欄位
     * @access public
     * @param array $data 創建資料
     * @param string $val 驗證規則
     * @return boolean
     */
    private function _validationField($data,$val) {
        switch($val[4]) {
        case 'function':// 使用函數進行驗證
        case 'callback':// 呼叫方法進行驗證
            $args = isset($val[6])?$val[6]:array();
            array_unshift($args,$data[$val[0]]);
            if('function'==$val[4]) {
                return call_user_func_array($val[1], $args);
            }else{
                return call_user_func_array(array(&$this, $val[1]), $args);
            }
        case 'confirm': // 驗證兩個欄位是否相同
            return $data[$val[0]] == $data[$val[1]];
        case 'in': // 驗證是否在某個陣列範圍之內
            return in_array($data[$val[0]] ,$val[1]);
        case 'equal': // 驗證是否等於某個值
            return $data[$val[0]] == $val[1];
        case 'unique': // 驗證某個值是否唯一
            if(is_string($val[0]) && strpos($val[0],','))
                $val[0]  =  explode(',',$val[0]);
            $map = array();
            if(is_array($val[0])) {
                // 支援多個欄位驗證
                foreach ($val[0] as $field)
                    $map[$field]   =  $data[$field];
            }else{
                $map[$val[0]] = $data[$val[0]];
            }
            if($this->where($map)->find())
                return false;
            break;
        case 'regex':
        default:    // 默認使用正則驗證 可以使用驗證類中定義的驗證名稱
            // 檢查附加規則
            return $this->regex($data[$val[0]],$val[1]);
        }
        return true;
    }

    /**
     * SQL查詢
     * @access public
     * @param mixed $sql  SQL指令
     * @return mixed
     */
    public function query($sql)
    {
        if(!empty($sql)) {
            if(strpos($sql,'__TABLE__'))
                $sql    =   str_replace('__TABLE__',$this->getTableName(),$sql);
            return $this->db->query($sql);
        }else{
            return false;
        }
    }

    /**
     * 執行SQL語句
     * @access public
     * @param string $sql  SQL指令
     * @return false | integer
     */
    public function execute($sql)
    {
        if(!empty($sql)) {
            if(strpos($sql,'__TABLE__'))
                $sql    =   str_replace('__TABLE__',$this->getTableName(),$sql);
            return $this->db->execute($sql);
        }else {
            return false;
        }
    }

    /**
     * 得到當前的資料物件名稱
     * @access public
     * @return string
     */
    public function getModelName()
    {
        if(empty($this->name))
            $this->name =   substr(get_class($this),0,-5);
        return $this->name;
    }

    /**
     * 得到完整的資料表名
     * @access public
     * @return string
     */
    public function getTableName()
    {
        if(empty($this->trueTableName)) {
            $tableName  = !empty($this->tablePrefix) ? $this->tablePrefix : '';
            if(!empty($this->tableName)) {
                $tableName .= $this->tableName;
            }else{
                $tableName .= parse_name($this->name);
            }
            $tableName .= !empty($this->tableSuffix) ? $this->tableSuffix : '';
            if(!empty($this->dbName))
                $tableName    =  $this->dbName.'.'.$tableName;
            $this->trueTableName    =   strtolower($tableName);
        }
        return $this->trueTableName;
    }

    /**
     * 啟動事務
     *
     * 開啟事務的同時先提交其他Sql,
     *
     * @access public
     * @return void
     */
    public function startTrans()
    {
        $this->commit();
        $this->db->startTrans();
        return ;
    }

    /**
     * 提交事務
     * @access public
     * @return boolean
     */
    public function commit()
    {
        return $this->db->commit();
    }

    /**
     * 事務回滾
     * @access public
     * @return boolean
     */
    public function rollback()
    {
        return $this->db->rollback();
    }

    /**
     * 返回模型的錯誤資訊
     * @access public
     * @return string
     */
    public function getError(){
        if(empty($this->error)){
            return $this->getDbError();
        }
        return $this->error;
    }

    /**
     * 返回資料庫的錯誤資訊
     * @access public
     * @return string
     */
    public function getDbError() {
        //return $this->db->getError();
        return $this->db->error();  //edit by yangjs
    }

    /**
     * 返回最後插入的ID
     * @access public
     * @return string
     */
    public function getLastInsID() {
        return $this->db->lastInsID;
    }

    /**
     * 返回最後執行的sql語句
     * @access public
     * @return string
     */
    public function getLastSql() {
        return $this->db->getLastSql();
    }

    /**
     * 獲取主鍵名稱
     * @access public
     * @return string
     */
    public function getPk() {
        return isset($this->fields['_pk'])?$this->fields['_pk']:$this->pk;
    }

    /**
     * 獲取資料表欄位資訊
     * @access public
     * @return array
     */
    public function getDbFields(){
        return $this->fields;
    }

    /**
     * 設定資料物件值
     * @access public
     * @param mixed $data 資料
     * @return Model
     */
    public function data($data){
        if(is_object($data)){
            $data   =   get_object_vars($data);
        }elseif(!is_array($data)){
            throw_exception(L('_DATA_TYPE_INVALID_'));
        }
        $this->data = $data;
        return $this;
    }

    /**
     * 查詢SQL組裝 join
     * @access public
     * @param mixed $join
     * @return Model
     */
    public function join($join) {
        if(is_array($join))
            $this->options['join'] =  $join;
        else
            $this->options['join'][]  =   $join;
        return $this;
    }

    /**
     * 設定模型的屬性值
     * @access public
     * @param string $name 名稱
     * @param mixed $value 值
     * @return Model
     */
    public function setProperty($name,$value) {
        if(property_exists($this,$name))
            $this->$name = $value;
        return $this;
    }

    /**
     * 統計滿足條件的記錄個數
     * @access public
     * @param mixed $condition  條件
     * @param string $field  要統計的欄位 默認為*
     * @return integer
     */
    public function count($options = array(),$field='1') {
        $fields = 'count('.$field.') as count';
        // 總是查找一條記錄
        $options['limit'] = 1;
        $options['field'] = $fields;
        // 分析表示式
        $options =  $this->_parseOptions($options);

        if($result = $this->db->select($options)) {
            return $result[0]['count'];
        }else{
            return false;
        }
    }

    /**
     * 分頁查詢資料
     * @access public
     * @param mixed $options 表示式參數
     * @param mixed $pageopt 分頁參數
     * @return mixed
     */
    public function findPage($pageopt,$count=false,$options=array()) {
        // 分析表示式
        $options =  $this->_parseOptions($options);
        // 如果沒有傳入總數，則自動根據條件進行統計
        if($count===false){
            // 查詢總數
            $count_options      =   $options;
            $count_options['limit'] = 1;
            $count_options['field'] = 'count(1) as count';
            // 去掉統計時的排序提高效率
            unset($count_options['order']);
            $result =   $this->db->select($count_options);

            $count  =   $result[0]['count'];
            unset($result);
            unset($count_options);
        }
        // 如果查詢總數大於0
        if($count > 0) {
            // 載入分頁類
            //import('ORG.Util.Page');
            // 解析分頁參數
            if( is_numeric($pageopt) ) {
                $pagesize   =   intval($pageopt);
            }else{
                $pagesize   =   intval(C('LIST_NUMBERS'));
            }

            $p  =   new Page($count,$pagesize);

            // 查詢資料
            $options['limit']   =   $p->firstRow.','.$p->listRows;
            $resultSet  =   $this->select($options);

            if($resultSet){
                $this->dataList = $resultSet;
            }else{
                $resultSet  =   '';
            }

            // 輸出控制
            $output['count']        =   $count;
            $output['totalPages']   =   $p->totalPages;
            $output['totalRows']    =   $p->totalRows;
            $output['nowPage']      =   $p->nowPage;
            $output['html']         =   $p->show();
            $output['data']         =   $resultSet;
            unset($resultSet);
            unset($p);
            unset($count);
        }else{
            $output['count']        =   0;
            $output['totalPages']   =   0;
            $output['totalRows']    =   0;
            $output['nowPage']      =   1;
            $output['html']         =   '';
            $output['data']         =   '';
        }
        // 輸出資料
        return $output;
    }

    /**
     * 通過SQL語句，分頁查詢資料
     * @access public
     * @param mixed $options 表示式參數
     * @param mixed $pageopt 分頁參數
     * @return mixed
     */
    public function findPageBySql($sql, $count = null, $pagesize = null) {
        //if ( strtoupper(substr($sql, 0, 6)) !== 'SELECT   ' ) return false;

        // 計算結果總數

        if ( !is_numeric($count)  || $count == null) {
            $count_sql              = explode(' FROM ', $sql);
            if (count($count_sql) != 2) return false;
            $count_sql              = 'SELECT count(*) AS count FROM ' . $count_sql[1];

            $count                  = $this->db->query($count_sql);
            $count                  = $count[0]['count'];
        }
        $count = intval($count);

        // 如果查詢總數大於0
        if ($count > 0) {
            // 解析分頁參數
            $pagesize               =   is_numeric($pagesize) ? intval($pagesize) : intval(C('LIST_NUMBERS'));
            $p                      =   new Page($count,$pagesize);
            // 查詢資料
            $limit                  =   $p->firstRow.','.$p->listRows;
            $resultSet              =   $this->query($sql . ' LIMIT ' . $limit);
            if($resultSet){
                $this->dataList = $resultSet;
            }else{
                $resultSet          =   '';
            }
            // 輸出控制
            $output['count']        =   $count;
            $output['totalPages']   =   $p->totalPages;
            $output['totalRows']    =   $p->totalRows;
            $output['nowPage']      =   $p->nowPage;
            $output['html']         =   $p->show();
            $output['data']         =   $resultSet;
            unset($resultSet);
            unset($p);
            unset($count);
        }else {
            $output['count']        =   0;
            $output['totalPages']   =   0;
            $output['totalRows']    =   0;
            $output['nowPage']      =   1;
            $output['html']         =   '';
            $output['data']         =   '';
        }

        return $output;
        }

        /**
         * 執行SQL檔案
         * @access public
         * @param string  $file 要執行的sql檔案路徑
         * @param boolean $stop 遇錯是否停止  默認為true
         * @param string  $db_charset 資料庫編碼 默認為utf-8
         * @return array
         */
        public function executeSqlFile($file,$stop = true,$db_charset = 'utf-8') {
            if (!is_readable($file)) {
                $error = array(
                    'error_code' => 'SQL檔案不可讀',
                    'error_sql'  => '',
                );
                return $error;
        }

        $fp = fopen($file, 'rb');
        $sql = fread($fp, filesize($file));
        fclose($fp);

        $sql = str_replace("\r", "\n", str_replace('`'.'ts_', '`'.$this->tablePrefix, $sql));

        foreach (explode(";\n", trim($sql)) as $query) {
            $query = trim($query);
            if($query) {
                if(substr($query, 0, 12) == 'CREATE TABLE') {
                    //預處理建表語句
                    $db_charset = (strpos($db_charset, '-') === FALSE) ? $db_charset : str_replace('-', '', $db_charset);
                    $type   = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $query));
                    $type   = in_array($type, array("MYISAM", "HEAP")) ? $type : "MYISAM";
                    $_temp_query = preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $query).
                        (mysql_get_server_info() > "4.1" ? " ENGINE=$type DEFAULT CHARSET=$db_charset" : " TYPE=$type");

                    $res = $this->execute($_temp_query);
        }else {
            $res = $this->execute($query);
        }
        if($res === false) {
            $error[] = array(
                'error_code' => $this->getDbError(),
                                'error_sql'  => $query,
                            );

            if($stop) return $error[0];
        }
        }
        }
        return $error;
        }

        /**
         * 清理快取
         * @access public
         * @param mixed $param
         * @return boolean
         */
        public function cleanCache($param){
            return true;
        }
        };
?>
