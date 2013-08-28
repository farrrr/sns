<?php
/**
 * 多語言模型 - 資料物件模型
 * @author zivss <guolee226@gmail.com>
 * @version TS3.0
 */
class LangModel extends Model {

    protected $tableName = 'lang';
    protected $fields = array(0=>'lang_id',1=>'key',2=>'appname',3=>'filetype',4=>'zh-cn',5=>'en',6=>'zh-tw','_autoinc'=>true,'_pk'=>'id');

    protected $langType = array();          // 默認的語言類型設定

    /**
     * 初始化方法，設定默認語言
     * @return void
     */
    public function _initialize() {
        empty($this->langType) && $this->langType = C('DEFAULT_LANG_TYPE');
    }

    /**
     * 獲取當前系統的語設定
     * @return string 當前系統的語設定
     */
    public function getLangType() {
        // 每次直接從這裡獲取
        return $this->langType;
    }

    /**
     * 獲取語言配置內容列表
     * @param array $map 查詢條件
     * @return array 語言配置內容列表
     */
    public function getLangContent($map) {
        empty($map) && $map = array();
        $data = $this->where($map)->findPage(20);
        return $data;
    }

    /**
     * 獲取單條語言配置內容
     * @param integer $sid 語言資源ID
     * @return array 單條語言配置內容
     */
    public function getLangSetInfo($sid) {
        $data = $this->where('lang_id='.$sid)->find();
        return $data;
    }

    /**
     * 更改語言配置內容
     * @param array $data 語言配置內容
     * @param integer $sid 語言資源ID
     * @return integer 是否更改成功，1表示成功；0表示失敗
     */
    public function updateLangData($data, $sid) {
        $addData['key'] = strtoupper(t($data['key']));
        $addData['appname'] = strtoupper(t($data['appname']));
        $addData['filetype'] = $data['filetype'];
        $fields = $this->getLangType();
        foreach($fields as $value) {
            $addData[$value] = $data[$value];
        }

        if($sid == 0) {
            // 判斷重複
            $map['key'] = $data['key'];
            $map['appname'] = $data['appname'];
            $map['filetype'] = $data['filetype'];
            $count = $this->where($map)->count();
            if($count > 0) {
                return 2;
            }
            $result = $this->add($addData);
        } else {
            $result = $this->where('lang_id='.$sid)->save($addData);
        }
        // 更新快取檔案
        $this->createCacheFile($addData['appname'], $addData['filetype']);
        $result = ($result === false) ? 0 : 1;

        return $result;
    }

    /**
     * 刪除指定的語言配置內容
     * @param integer $sid 語言資源ID
     * @return mix 刪除失敗返回false，刪除成功返回刪除的語言資源ID
     */
    public function deleteLangData($sid) {
        $map['lang_id'] = array('IN', $sid);
        $result = $this->where($map)->delete();
        return $result;
    }

    /**
     * 創建語言快取檔案
     * @param string $app 應用名稱
     * @param boolean $isJs 是否是Js檔案
     * @return void
     */
    public function createCacheFile($app, $isJs) {
        set_time_limit(0);
        // 判斷資料夾路徑是否存在
        if(!file_exists(LANG_PATH)) {
            mkdir(LANG_PATH, 0777);
        }

        $map['appname'] = $app;
        $map['filetype'] = $isJs;
        $fields = $this->getLangType();
        $data = $this->where($map)->findAll();

        if($isJs) {
            $this->_getJavaScriptFile($app, $fields, $data);
        } else {
            $this->_getPhpFile($app, $fields, $data);
        }
    }

    /**
     * 寫入PHP語言檔案
     * @param string $app 應用名稱
     * @param array $fields 語言類型欄位
     * @param array $data 語言的相關資料
     * @return void
     */
    private function _getPhpFile($app, $fields, $data) {
        $app = strtolower($app);
        foreach($fields as $value) {
            $fileName = LANG_PATH.'/'.$app.'_'.$value.'.php';
            // 許可權處理
            $fp = fopen($fileName, 'w+');
            $fileData = "<?php\n";
            $fileData .= "return array(\n";
            foreach($data as $val) {
                $val[$value] = str_replace("'", "‘", $val[$value]);         // 處理掉單引號
                $content[] = "'{$val['key']}'=>'{$val[$value]}'";
        }
        $fileData .= implode(",\n", $content);
        $fileData .= "\n);";
        fwrite($fp, $fileData);
        fclose($fp);
        unset($fileData);
        unset($content);
        @chmod($fileName,0775);
        }
        }

        /**
         * 寫入JavaScript語言檔案
         * @param string $app 應用名稱
         * @param array $fields 語言類型欄位
         * @param array $data 語言的相關資料
         * @return void
         */
        private function _getJavaScriptFile($app, $fields, $data) {
            $app = strtolower($app);
            foreach($fields as $value) {
                $fileName = LANG_PATH.'/'.$app.'_'.$value.'.js';
                $fp = fopen($fileName, 'w+');
                $fileData = "";
                foreach($data as $val) {
                    $val[$value] = str_replace("'", "‘", $val[$value]);         // 處理掉單引號
                    $content[] = "LANG['{$val['key']}']='{$val[$value]}';";
        }
        $fileData .= implode("\n", $content);
        fwrite($fp, $fileData);
        fclose($fp);
        unset($fileData);
        unset($content);
        @chmod($fileName,0775);
        }
        }

        /**
         * 初始化整站的語言包
         * @return void
         */
        public function initSiteLang(){
            $dirArray = array();
            //取apps目錄下的應用包名
            if (false != ($handle = opendir ( APPS_PATH ))) {
                while ( false !== ($file = readdir ( $handle )) ) {
                    if ($file != "." && $file != ".."&&!strpos($file,".")) {
                        $dirArray[]=$file;
        }
        }
        //關閉控制代碼
        closedir ( $handle );
        }
        if(empty($dirArray)){
            $dirArray = C('DEFAULT_APPS');
        }

        foreach ($dirArray as $app){
            $this->createCacheFile($app, 0);
            $this->createCacheFile($app, 1);
        }

        F('initSiteLang.lock', time());
        }
        }
