<?php
class ToolAction extends Action {
    /**
     * 官方伺服器生成語言同步檔案
     *
     * @return void
     */
    public function createLangPHPFile() {
        set_time_limit ( 0 );
        // 判斷資料夾路徑是否存在
        if (! file_exists ( LANG_PATH )) {
            mkdir ( LANG_PATH, 0777 );
        }
        $data = model ( 'Lang' )->order ( 'lang_id asc' )->findAll ();
        $fileName = LANG_PATH . '/langForLoadUpadte.php';
        // 許可權處理
        $fp = fopen ( $fileName, 'w+' );
        $fileData = "<?php\n";
        $fileData .= "return array(\n";
        foreach ( $data as $val ) {
            if (empty ( $val ['key'] ) || empty ( $val ['appname'] ) || empty ( $val ['filetype'] )) {
                continue;
            }
            $val ['zh-cn'] = htmlspecialchars ( $val ['zh-cn'], ENT_QUOTES );
            $val ['en'] = htmlspecialchars ( $val ['en'], ENT_QUOTES );
            $val ['zh-tw'] = htmlspecialchars ( $val ['zh-tw'], ENT_QUOTES );
            $content [] = "'{$val['key']}-{$val['appname']}-{$val['filetype']}'=>array(0=>'{$val['zh-cn']}',1=>'{$val['en']}',2=>'{$val['zh-tw']}',)";
        }
        $fileData .= implode ( ",\n", $content );
        $fileData .= "\n);";
        fwrite ( $fp, $fileData );
        fclose ( $fp );
        unset ( $fileData );
        unset ( $content );
        @chmod ( $fileName, 0775 );

        tsload ( ADDON_PATH . '/library/Update.class.php' );
        $updateClass = new Update ();

        $res = $updateClass->zipPackage ( $fileName, LANG_PATH, 'langForLoadUpadte', LANG_PATH );
        unlink ( $fileName );

        echo $res;
    }
    /**
     * 官方伺服器生成後臺頁面配置資訊
     *
     * @return [type] void
     */
    public function createSystemConfigPHPFile() {
        set_time_limit ( 0 );
        // 判斷資料夾路徑是否存在
        if (! file_exists ( LANG_PATH )) {
            mkdir ( LANG_PATH, 0777 );
        }
        $data = D ( 'system_config' )->findAll ();

        $fileName = LANG_PATH . '/system_config.php';
        // 許可權處理
        $fp = fopen ( $fileName, 'w+' );
        $fileData = "<?php\n";
        $fileData .= "return array(\n";
        foreach ( $data as $val ) {
            $val ['value'] = unserialize ( $val ['value'] );
            $arr = 'array(';
            if ($val ['value'] ['key']) {
                $arr .= '\'key\'=>array(';
                foreach ( $val ['value'] ['key'] as $k0 => $v0 ) {
                    $arr .= '\'' . $k0 . '\'=>\'' . htmlspecialchars ( $v0, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_name']) {
                $arr .= '\'key_name\'=>array(';
                foreach ( $val ['value'] ['key_name'] as $k1 => $v1 ) {
                    $arr .= '\'' . $k1 . '\'=>\'' . htmlspecialchars ( $v1, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_hidden']) {
                $arr .= '\'key_hidden\'=>array(';
                foreach ( $val ['value'] ['key_hidden'] as $k2 => $v2 ) {
                    $arr .= '\'' . $k2 . '\'=>\'' . htmlspecialchars ( $v2, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_type']) {
                $arr .= '\'key_type\'=>array(';
                foreach ( $val ['value'] ['key_type'] as $k3 => $v3 ) {
                    $arr .= '\'' . $k3 . '\'=>\'' . htmlspecialchars ( $v3, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_default']) {
                $arr .= '\'key_default\'=>array(';
                foreach ( $val ['value'] ['key_default'] as $k4 => $v4 ) {
                    $arr .= '\'' . $k4 . '\'=>\'' . htmlspecialchars ( $v4, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_tishi']) {
                $arr .= '\'key_tishi\'=>array(';
                foreach ( $val ['value'] ['key_tishi'] as $k5 => $v5 ) {
                    $arr .= '\'' . $k5 . '\'=>\'' . htmlspecialchars ( $v5, ENT_QUOTES ) . '\',';
                }
                $arr .= '),';
            }
            if ($val ['value'] ['key_javascript']) {
                $arr .= '\'key_javascript\'=>array(';
                foreach ( $val ['value'] ['key_javascript'] as $k6 => $v6 ) {
                    $arr .= '\'' . $k6 . '\'=>\'' . htmlspecialchars ( $v6, ENT_QUOTES ) . '\',';
                }
                $arr .= ')';
            }
            $arr .= ')';
            if (empty ( $val ['key'] ) || empty ( $val ['list'] ) || $arr == 'array()') {
                continue;
            }
            $content [] = "'{$val['key']}-{$val['list']}'=>" . $arr;
        }
        $fileData .= implode ( ",\n", $content );
        $fileData .= "\n);";
        fwrite ( $fp, $fileData );
        fclose ( $fp );
        unset ( $fileData );
        unset ( $content );
        @chmod ( $fileName, 0775 );

        tsload ( ADDON_PATH . '/library/Update.class.php' );
        $updateClass = new Update ();

        $res = $updateClass->zipPackage ( $fileName, LANG_PATH, 'system_config', LANG_PATH );
        unlink ( $fileName );

        echo $res;
    }

    // 獲取官方伺服器上應用的資訊給本地伺服器
    function downloadApp() {
        $map ['develop_id'] = intval ( $_GET ['develop_id'] );
        $dao = D ( 'develop', 'develop' );

        $info = $dao->getDetailDevelop ( $map ['develop_id'] );
        $info ['packageURL'] = getAttachUrl($info ['file'] ['filename']);
        $info['app_name'] = $info['package'];

        // 記錄下載數
        $dao->where ( $map )->setInc ( 'download_count' );

        echo json_encode ( $info );
    }

    /**
     * 自動獲取線上應用列表給本地伺服器
     * @return JSON 相關的JSON資料
     */
    public function getAppsOnLineInfo () {
        $type = t($_GET['t']);
        $id = intval($_GET['id']);
        if (empty($type) && !in_array($type, array('home', 'application', 'plugin', 'theme', 'detail'))) {
            $data = array();
    } else {
        $data = D('Develop', 'develop')->getAppStore($type, $id);
    }
    echo json_encode($data);
    }

    // 自動獲取升級包資訊給本地伺服器
    public function getVersionInfo() {
        $result = M('system_update')->field('id,title,version,package')->findAll();
        foreach ($result as $k=>$v){
            $list[$v['id']] = $v;
            unset($result[$k]);
    }
    echo json_encode ( $list );
    }

    /**
     * 驗證站點是否在官方伺服器上註冊
     * @return JSON 返回相關資料
     */
    public function checkedHost () {
        $host = t($_GET['h']);
        $result = D('DevelopRegistration', 'develop')->checked($host);
        $res = array();
        if ($result) {
            $res['status'] = 1;
            $res['info'] = '驗證通過';
    } else {
        $res['status'] = 0;
        $res['info'] = '驗證失敗';
    }

    exit(json_encode($res));
    }
    }
