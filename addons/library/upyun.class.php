<?php
class UpYun {
    public function version(){return '1.0.1';}
        private $bucketname;
    private $username;
    private $password;
    private $api_domain = 'v0.api.upyun.com';
    private $tmp_infos;
    public $timeout = 300;
    public $debug = false;
    private $content_md5 = null;
    private $file_secret = null;

    /**
     * 初始化 UpYun 存儲介面
     * @param $bucketname 空間名稱
     * @param $username 操作員名稱
     * @param $password 密碼
     * return UpYun object
     */
    public function __construct($bucketname, $username, $password) {
        $this->bucketname = $bucketname;
        $this->username = $username;
        $this->password = md5($password);
    }

    /**
     * 切換 API 介面的域名
     * @param $domain {默然 v0.api.upyun.com 自動識別, v1.api.upyun.com 電信, v2.api.upyun.com 聯通, v3.api.upyun.com 移動}
     * return null;
     */
    public function setApiDomain($domain){
        $this->api_domain = $domain;
    }

    /**
     * 設定連線超時時間
     * @param $time 秒
     * return null;
     */
    public function setTimeout($time){
        $this->timeout = $time;
    }

    /**
     * 設定待上傳檔案的 Content-MD5 值（如又拍雲服務端收到的檔案MD5值與使用者設定的不一致，將回報 406 Not Acceptable 錯誤）
     * @param $str （檔案 MD5 校驗碼）
     * return null;
     */
    public function setContentMD5($str){
        $this->content_md5 = $str;
    }
    /**
     * 連線簽名方法
     * @param $method 請求方式 {GET, POST, PUT, DELETE}
     * return 簽名字元串
     */
    private function sign($method, $uri, $date, $length){
        $sign = "{$method}&{$uri}&{$date}&{$length}&{$this->password}";
        return 'UpYun '.$this->username.':'.md5($sign);
    }

    /**
     * 連線處理邏輯
     * @param $method 請求方式 {GET, POST, PUT, DELETE}
     * @param $uri 請求地址
     * @param $datas 如果是 POST 上傳檔案，傳遞檔案內容 或 檔案IO資料流
     * @param $output_file 如果是 GET 下載檔案，可傳遞檔案IO資料流
     * return 請求返回字元串，失敗返回 null （開啟 debug 狀態下遇到錯誤將中止程式執行）
     */
    private function HttpAction($method, $uri, $datas, $output_file = null){
        unset($this->tmp_infos);
        $uri = "/{$this->bucketname}{$uri}";
        $process = curl_init("http://{$this->api_domain}{$uri}");
        $headers = array('Expect:');
        if($datas == 'folder:true'){
            $headers[] = $datas;
            $datas = null;
        }
        $length = @strlen($datas);
        if($method == 'PUT' || $method == 'POST'){
            if($this->auto_mkdir == true){
                $headers[] = 'mkdir: true';
            }
            $method = 'POST';
            curl_setopt($process, CURLOPT_POST, 1);
            if($datas){
                $headers[] = 'Content-Type: ';
                if($this->content_md5 != null)$headers[] = 'Content-MD5: '.$this->content_md5;
                $this->content_md5 = null;
                if($this->file_secret != null)$headers[] = 'Content-Secret: '.$this->file_secret;
                $this->file_secret = null;
                if(is_resource($datas)){
                    fseek($datas, 0, SEEK_END);
                    $length = ftell($datas);
                    fseek($datas, 0);
                    $headers[] = 'Content-Length: '.$length;
                    curl_setopt($process, CURLOPT_INFILE, $datas);
                    curl_setopt($process, CURLOPT_INFILESIZE, $length);
                }
                else curl_setopt($process, CURLOPT_POSTFIELDS, $datas);
            }else curl_setopt($process, CURLOPT_POSTFIELDS, "");
        }
        curl_setopt($process, CURLOPT_CUSTOMREQUEST, $method);

        $date = gmdate('D, d M Y H:i:s \G\M\T');
        $headers[] = "Date: {$date}";
        $headers[] = 'Authorization: '.$this->sign($method, $uri, $date, $length);

        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 1);  /// 獲取 header
        curl_setopt($process, CURLOPT_TIMEOUT, $this->timeout);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        if($method == 'HEAD')curl_setopt($process, CURLOPT_NOBODY, true);
        if(is_resource($output_file)){
            curl_setopt($process, CURLOPT_HEADER, 0);
            curl_setopt($process, CURLOPT_FILE, $output_file);
        }
        $r = curl_exec($process);
        $rc = curl_getinfo($process, CURLINFO_HTTP_CODE);
        $r_offset = curl_getinfo($process, CURLINFO_HEADER_SIZE);
        if($rc != 200 && $method != 'HEAD'){
            if($this->debug)
                throw new Exception($r, $rc);
            return null;
        }
        curl_close($process);
        $r_headers = explode("\n", substr($r, 0 , $r_offset)."]");
        foreach($r_headers as $hl){
            $hl = trim($hl);
            if(substr($hl, 0, 7) == 'x-upyun'){
                if(!isset($this->tmp_infos))$this->tmp_infos = array();
                list($k, $v) = explode(':', $hl);
                if(in_array(substr($k,8,5), array('width','heigh','frame')))
                    $this->tmp_infos[trim($k)] = intval($v);
                else
                    $this->tmp_infos[trim($k)] = trim($v);
            }
        }
        if($rc != 200 && $method == 'HEAD')return null;
        return substr($r, $r_offset, strlen($r));
    }

    /**
     * 獲取總體空間的佔用資訊
     * return 空間佔用量，失敗返回 null
     */
    public function getBucketUsage(){
        return $this->getFolderUsage('/');
    }

    /**
     * 獲取某個子目錄的佔用資訊
     * @param $path 目標路徑
     * return 空間佔用量，失敗返回 null
     */
    public function getFolderUsage($path){
        $r = $this->HttpAction('GET', "{$path}?usage", null);
        if($r == '')return null;
        return floatval($r);
    }

    /**
     * 設定待上傳檔案的 訪問金鑰（注意：僅支援圖片空！，設定金鑰後，無法根據原檔案URL直接訪問，需帶 URL 後面加上 （縮圖間隔標誌符+金鑰） 進行訪問）
     * 如縮圖間隔標誌符為 ! ，金鑰為 bac，上傳檔案路徑為 /folder/test.jpg ，那麼該圖片的對外訪問地址為： http://空間域名/folder/test.jpg!bac
     * @param $str （檔案 MD5 校驗碼）
     * return null;
     */
    public function setFileSecret($str){
        $this->file_secret = $str;
    }

    /**
     * 上傳檔案
     * @param $file 檔案路徑（包含檔名）
     * @param $datas 檔案內容 或 檔案IO資料流
     * @param $auto_mkdir=false 是否自動創建父級目錄
     * return true or false
     */
    public function writeFile($file, $datas, $auto_mkdir=false){
        $this->auto_mkdir = $auto_mkdir;
        $r = $this->HttpAction('PUT', $file, $datas);
        return !is_null($r);
    }

    /**
     * 獲取上傳檔案後的資訊（僅圖片空間有返回資料）
     * @param $key 資訊欄位名（x-upyun-width、x-upyun-height、x-upyun-frames、x-upyun-file-type）
     * return value or NULL
     */
    public function getWritedFileInfo($key){
        if(!isset($this->tmp_infos))return NULL;
        return $this->tmp_infos[$key];
    }

    /**
     * 讀取檔案
     * @param $file 檔案路徑（包含檔名）
     * @param $output_file 可傳遞檔案IO資料流（默認為 null，結果返迴檔案內容，如設定檔案資料流，將返回 true or false）
     * return 檔案內容 或 null
     */
    public function readFile($file, $output_file = null){
        return $this->HttpAction('GET', $file, null, $output_file);
    }

    /**
     * 獲取檔案資訊
     * @param $file 檔案路徑（包含檔名）
     * return array('type'=> file | folder, 'size'=> file size, 'date'=> unix time) 或 null
     */
    public function getFileInfo($file){
        $r = $this->HttpAction('HEAD', $file, null);
        if(is_null($r))return null;
        return array('type'=> $this->tmp_infos['x-upyun-file-type'], 'size'=> @intval($this->tmp_infos['x-upyun-file-size']), 'date'=> @intval($this->tmp_infos['x-upyun-file-date']));
    }

    /**
     * 讀取目錄列表
     * @param $path 目錄路徑
     * return array 陣列 或 null
     */
    public function readDir($path){
        $r = $this->HttpAction('GET', $path, null);
        if(is_null($r))return null;
        $rs = explode("\n", $r);
        $returns = array();
        foreach($rs as $r){
            $r = trim($r);
            $l = new stdclass;
            @list($l->name, $l->type, $l->size, $l->time) = explode("\t", $r);
            if(!empty($l->time)){
                $l->type = ($l->type == 'N' ? 'file':'folder');
                $l->size = intval($l->size);
                $l->time = intval($l->time);
                $returns[] = $l;
}
}
return $returns;
}

/**
 * 刪除檔案
 * @param $file 檔案路徑（包含檔名）
 * return true or false
 */
public function deleteFile($file){
    $r = $this->HttpAction('DELETE', $file, null);
    return !is_null($r);
}

/**
 * 創建目錄
 * @param $path 目錄路徑
 * @param $auto_mkdir=false 是否自動創建父級目錄
 * return true or false
 */
public function mkDir($path, $auto_mkdir=false){
    $this->auto_mkdir = $auto_mkdir;
    $r = $this->HttpAction('PUT', $path, 'folder:true');
    return !is_null($r);
}

/**
 * 刪除目錄
 * @param $path 目錄路徑
 * return true or false
 */
public function rmDir($dir){
    $r = $this->HttpAction('DELETE', $dir, null);
    return !is_null($r);
}
}
