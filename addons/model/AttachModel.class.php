<?php
/**
 * 附件模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
// 載入上傳操作類
require_once SITE_PATH.'/addons/library/UploadFile.class.php';

class AttachModel extends Model {

    protected $tableName = 'attach';
    protected $fields = array(0 => 'attach_id',1=>'app_name',2=>'table',3=>'row_id',
        4=>'attach_type',5=>'uid',6=>'ctime',7=>'name',8=>'type',
        9=>'size',10=>'extension',11=>'hash',12=>'private',13=>'is_del',14=>'save_path',
        15=>'save_name',16=>'save_domain',17=>'from'
    );

    /**
     * 通過附件ID獲取附件資料 - 不分頁型
     * @param array $ids 附件ID陣列
     * @param string $field 附件資料顯示欄位，默認為顯示全部
     * @return array 相關附件資料
     */
    public function getAttachByIds($ids, $field = '*') {
        if(empty($ids)) {
            return false;
        }
        !is_array($ids) && $ids = explode(',', $ids);
        $map['attach_id'] = array('IN', $ids);
        $data = $this->where($map)->field($field)->findAll();

        return $data;
    }

    /**
     * 通過單個附件ID獲取其附件資訊
     * @param integer $id 附件ID
     * @return array 指定附件ID的附件資訊
     */
    public function getAttachById($id) {
        if(empty($id)) {
            return false;
        }
        // 獲取靜態快取
        $sc = static_cache('attach_infoHash_'.$id);
        if(!empty($sc)) {
            return $sc;
        }
        // 獲取快取
        $sc = model('Cache')->get('Attach_'.$id);
        if(empty($sc)) {
            $map['attach_id'] = $id;
            $sc = $this->where($map)->find();
            empty($sc) && $sc = array();
            model('Cache')->set('Attach_'.$id, $sc, 3600);
        }
        static_cache('attach_infoHash_'.$id, $sc);

        return $sc;
    }

    /**
     * 獲取附件列表 - 分頁型
     * @param array $map 查詢條件
     * @param string $field 顯示欄位
     * @param string $order 排序條件，默認為id DESC
     * @param integer $limit 結果集個數，默認為20
     * @return array 附件列表資料
     */
    public function getAttachList($map, $field = '*', $order = 'id DESC', $limit = 20) {
        !isset($map['is_del']) && ($map['is_del'] = 0);
        $list = $this->where($map)->field($field)->order($order)->findPage($limit);
        return $list;
    }

    /**
     * 刪除附件資訊，提供假刪除功能
     * @param integer $id 附件ID
     * @param string $type 操作類型，若為delAttach則進行假刪除操作，deleteAttach則進行徹底刪除操作
     * @param string $title ???
     * @return array 返回操作結果資訊
     */
    public function doEditAttach($id, $type, $title) {
        $return = array('status'=>'0','data'=>L('PUBLIC_ADMIN_OPRETING_ERROR'));        // 操作失敗
        if(empty($id)) {
            $return['data'] = L('PUBLIC_ATTACHMENT_ID_NOEXIST');            // 附件ID不能為空
        } else {
            $map['attach_id'] = is_array($id) ? array('IN', $id) : intval($id);
            $save['is_del'] = ($type == 'delAttach') ? 1 : 0;       //TODO:1 為使用者uid 臨時為1
            if($type == 'deleteAttach') {
                // 徹底刪除操作
                $res = D('Attach')->where($map)->delete();
                // TODO:刪除附件檔案
            } else {
                // 假刪除或者恢復操作
                $res = D('Attach')->where($map)->save($save);
            }
            if($res) {
                //TODO:是否記錄日誌，以及後期快取處理
                $return = array('status'=>1,'data'=>L('PUBLIC_ADMIN_OPRETING_SUCCESS'));        // 操作成功
            }
        }

        return $return;
    }

    /**
     * 獲取所有附件的副檔名
     * @return array 副檔名陣列
     */
    public function getAllExtensions() {
        $res = $this->field('`extension`')->group('`extension`')->findAll();
        return getSubByKey($res, 'extension');
    }

    /**
     * 上傳附件
     * @param array $data 附件相關資訊
     * @param array $input_options 配置選項[不推薦修改, 默認使用後臺的配置]
     * @param boolean $thumb 是否啟用縮圖
     * @return array 上傳的附件的資訊
     */
    public function upload($data = null, $input_options = null, $thumb = false) {
        $system_default = model('Xdata')->get('admin_Config:attach');
        if(empty($system_default['attach_path_rule']) || empty($system_default['attach_max_size']) || empty($system_default['attach_allow_extension'])) {
            $system_default['attach_path_rule'] = 'Y/md/H/';
            $system_default['attach_max_size'] = '2';       // 默認2M
            $system_default['attach_allow_extension'] = 'jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf';
            model('Xdata')->put('admin_Config:attach', $system_default);
        }
        // 載入默認規則
        $default_options = array();
        $default_options['custom_path'] = date($system_default['attach_path_rule']);                    // 應用定義的上傳目錄規則：'Y/md/H/'
        $default_options['max_size'] = floatval($system_default['attach_max_size']) * 1024 * 1024;      // 單位: 兆
        $default_options['allow_exts'] = $system_default['attach_allow_extension'];                     // 'jpg,gif,png,jpeg,bmp,zip,rar,doc,xls,ppt,docx,xlsx,pptx,pdf'
        $default_options['save_path'] = UPLOAD_PATH.'/'.$default_options['custom_path'];
        $default_options['save_name'] = ''; //指定儲存的附件名.默認系統自動生成
        $default_options['save_to_db'] = true;

        // 定製化設這，覆蓋默認設定
        $options = is_array($input_options) ? array_merge($default_options,$input_options) : $default_options;
        //雲圖片
        if($data['upload_type']=='image'){
            $cloud = model('CloudImage');
            if($cloud->isOpen()){
                return $this->cloudImageUpload($options);
            }else{
                return $this->localUpload($options);
            }
        }

        //雲附件
        else{
            //if($data['upload_type']=='file'){
            $cloud = model('CloudAttach');
            if($cloud->isOpen()){
                return $this->cloudAttachUpload($options);
            }else{
                return $this->localUpload($options);
            }
        }
    }

    private function cloudImageUpload($options){

        $upload = model('CloudImage');
        $upload->maxSize = $options['max_size'];
        $upload->allowExts = $options['allow_exts'];
        $upload->customPath = $options['custom_path'];
        $upload->saveName = $options['save_name'];

        // 執行上傳操作
        if(!$upload->upload()) {
            // 上傳失敗，返回錯誤
            $return['status'] = false;
            $return['info'] = $upload->getErrorMsg();
            return $return;
        } else {
            $upload_info = $upload->getUploadFileInfo();
            // 儲存資訊到附件表
            $data = $this->saveInfo($upload_info, $options);
            // 輸出資訊
            $return['status'] = true;
            $return['info']   = $data;
            // 上傳成功，返回資訊
            return $return;
        }
    }

    private function cloudAttachUpload($options){

        $upload = model('CloudAttach');
        $upload->maxSize = $options['max_size'];
        $upload->allowExts = $options['allow_exts'];
        $upload->customPath = $options['custom_path'];
        $upload->saveName = $options['save_name'];

        // 執行上傳操作
        if(!$upload->upload()) {
            // 上傳失敗，返回錯誤
            $return['status'] = false;
            $return['info'] = $upload->getErrorMsg();
            return $return;
        } else {
            $upload_info = $upload->getUploadFileInfo();
            // 儲存資訊到附件表
            $data = $this->saveInfo($upload_info, $options);
            // 輸出資訊
            $return['status'] = true;
            $return['info']   = $data;
            // 上傳成功，返回資訊
            return $return;
        }
    }

    private function localUpload($options){
        // 初始化上傳參數
        $upload = new UploadFile($options['max_size'], $options['allow_exts'], $options['allow_types']);
        // 設定上傳路徑
        $upload->savePath = $options['save_path'];
        // 啟用子目錄
        $upload->autoSub = false;
        // 儲存的名字
        $upload->saveName = $options['save_name'];
        // 默認檔名規則
        $upload->saveRule = $options['save_rule'];
        // 是否縮圖
        $upload->thumb = $thumb;

        // 創建目錄
        mkdir($upload->save_path, 0777, true);

        // 執行上傳操作
        if(!$upload->upload()) {
            // 上傳失敗，返回錯誤
            $return['status'] = false;
            $return['info'] = $upload->getErrorMsg();
            return $return;
        } else {
            $upload_info = $upload->getUploadFileInfo();
            // 儲存資訊到附件表
            $data = $this->saveInfo($upload_info, $options);
            // 輸出資訊
            $return['status'] = true;
            $return['info']   = $data;
            // 上傳成功，返回資訊
            return $return;
        }
        }

        private function saveInfo($upload_info,$options){
            $data = array(
                'table' => t($data['table']),
            'row_id' => t($data['row_id']),
            'app_name' => t($data['app_name']),
            'attach_type' => t($options['attach_type']),
            'uid' =>  (int) $data['uid'] ? $data['uid'] : $GLOBALS['ts']['mid'],
            'ctime' => time(),
            'private' => $data['private'] > 0 ? 1 : 0,
            'is_del' => 0,
            'from' => isset($data['from']) ? intval($data['from']) : getVisitorClient(),
        );
            if($options['save_to_db']) {
                foreach($upload_info as $u) {
                    $name = t($u['name']);
                    $data['name'] = $name ? $name : $u['savename'];
                    $data['type'] = $u['type'];
                    $data['size'] = $u['size'];
                    $data['extension'] = strtolower($u['extension']);
                    $data['hash'] = $u['hash'];
                    $data['save_path'] = $options['custom_path'];
                    $data['save_name'] = $u['savename'];
                    //$data['save_domain'] = C('ATTACH_SAVE_DOMAIN');   //如果做分散式存儲，需要寫方法來分配附件的伺服器domain
                    $aid = $this->add($data);
                    $data['attach_id'] = intval($aid);
                    $data['key'] = $u['key'];
                    $data['size'] = byte_format($data['size']);
                    $infos[] = $data;
        }
        } else {
            foreach($upload_info as $u) {
                $name = t($u['name']);
                $data['name'] = $name ? $name : $u['savename'];
                $data['type'] = $u['type'];
                $data['size'] = byte_format($u['size']);
                $data['extension'] = strtolower($u['extension']);
                $data['hash'] = $u['hash'];
                $data['save_path'] = $options['custom_path'];
                $data['save_name'] = $u['savename'];
                //$data['save_domain'] = C('ATTACH_SAVE_DOMAIN');   //如果做分散式存儲，需要寫方法來分配附件的伺服器domain
                $data['key'] = $u['key'];
                $infos[] = $data;
        }
        }
        return $infos;
        }

        public function saveAttach($file){
            # code...
        }
        }
