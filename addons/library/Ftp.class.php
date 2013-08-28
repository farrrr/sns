<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2009 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: pengyong <i@pengyong.info>
// +----------------------------------------------------------------------

class Ftp {

    //FTP 連線資源
    private $link;

    //FTP連線時間
    public $link_time;

    //錯誤程式碼
    private $err_code = 0;

    //傳送模式{文字模式:FTP_ASCII, 二進位制模式:FTP_BINARY}
    public $mode = FTP_BINARY;

    /**
        初始化類

    **/
    public function start($data)
    {
        if(empty($data['port'])) $data['port'] ='21';
        if(empty($data['pasv'])) $data['pasv'] =false;
        if(empty($data['ssl'])) $data['ssl'] = false;
        if(empty($data['timeout'])) $data['timeout'] = 30;
        return $this->connect($data['server'],$data['username'],$data['password'],$data['port'],$data['pasv'],$data['ssl'],$data['timeout']);
    }

    /**
     * 連線FTP伺服器
     * @param string $host    　　 伺服器地址
     * @param string $username　　　使用者名
     * @param string $password　　　密碼
     * @param integer $port　　　　   伺服器埠，預設值為21
     * @param boolean $pasv        是否開啟被動模式
     * @param boolean $ssl　　　　 　是否使用SSL連線
     * @param integer $timeout     超時時間　
     */
    public function connect($host, $username = '', $password = '', $port = '21', $pasv = false, $ssl = false, $timeout = 30) {
        $start = time();
        if ($ssl) {
            if (!$this->link = @ftp_ssl_connect($host, $port, $timeout)) {
                $this->err_code = 1;
                return false;
            }
        } else {
            if (!$this->link = @ftp_connect($host, $port, $timeout)) {
                $this->err_code = 1;
                return false;
            }
        }

        if (@ftp_login($this->link, $username, $password)) {
            if ($pasv)
                ftp_pasv($this->link, true);
            $this->link_time = time() - $start;
            return true;
        } else {
            $this->err_code = 1;
            return false;
        }
        register_shutdown_function(array(&$this, 'close'));
    }

    /**
     * 創建資料夾
     * @param string $dirname 目錄名，
     */
    public function mkdir($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $dirname = $this->ck_dirname($dirname);
        $nowdir = '/';
        foreach ($dirname as $v) {
            if ($v && !$this->chdir($nowdir . $v)) {
                if ($nowdir)
                    $this->chdir($nowdir);
                @ftp_mkdir($this->link, $v);
            }
            if ($v)
                $nowdir .= $v . '/';
        }
        return true;
    }

    /**
     * 上傳檔案
     * @param string $remote 遠端存放地址
     * @param string $local 本地存放地址
     */
    public function put($remote, $local) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $dirname = pathinfo($remote, PATHINFO_DIRNAME);
        if (!$this->chdir($dirname)) {
            $this->mkdir($dirname);
        }
        if (@ftp_put($this->link, $remote, $local, $this->mode)) {
            return true;
        } else {
            $this->err_code = 7;
            return false;
        }
    }

    /**
     * 刪除資料夾
     * @param string $dirname  目錄地址
     * @param boolean $enforce 強制刪除
     */
    public function rmdir($dirname, $enforce = false) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        $list = $this->nlist($dirname);
        if ($list && $enforce) {
            $this->chdir($dirname);
            foreach ($list as $v) {
                $this->f_delete($v);
            }
        } elseif ($list && !$enforce) {
            $this->err_code = 3;
            return false;
        }
        @ftp_rmdir($this->link, $dirname);
        return true;
    }

    /**
     * 刪除指定檔案
     * @param string $filename 檔名
     */
    public function delete($filename) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        if (@ftp_delete($this->link, $filename)) {
            return true;
        } else {
            $this->err_code = 4;
            return false;
        }
    }

    /**
     * 返回給定目錄的檔案列表
     * @param string $dirname  目錄地址
     * @return array 檔案列表資料
     */
    public function nlist($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
        }
        if ($list = @ftp_nlist($this->link, $dirname)) {
            return $list;
    } else {
        $this->err_code = 5;
        return false;
    }
    }

    /**
     * 在 FTP 伺服器上改變當前目錄
     * @param string $dirname 修改伺服器上當前目錄
     */
    public function chdir($dirname) {
        if (!$this->link) {
            $this->err_code = 2;
            return false;
    }
    if (@ftp_chdir($this->link, $dirname)) {
        return true;
    } else {
        $this->err_code = 6;
        return false;
    }
    }

    /**
     * 獲取錯誤資訊
     */
    public function get_error() {
        if (!$this->err_code)
            return false;
        $err_msg = array(
            '1' => 'Server can not connect',
            '2' => 'Not connect to server',
            '3' => 'Can not delete non-empty folder',
            '4' => 'Can not delete file',
            '5' => 'Can not get file list',
            '6' => 'Can not change the current directory on the server',
            '7' => 'Can not upload files'
        );
        return $err_msg[$this->err_code];
    }

    /**
     * 檢測目錄名
     * @param string $url 目錄
     * @return 由 / 分開的返回陣列
     */
    private function ck_dirname($url) {
        $url = str_replace('', '/', $url);
        $urls = explode('/', $url);
        return $urls;
    }

    /**
     * 關閉FTP連線
     */

    public function close() {
        return @ftp_close($this->link);
    }

    }
