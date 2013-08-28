<?php
/**
 * 換膚插件鉤子
 * @author 陳偉川 <258396027@qq.com>
 * @version TS3.0
 */
class SpaceStyleHooks extends Hooks
{
    public static $defaultStyle = array();          // 默認樣式

    /**
     * 站點頭部鉤子，載入換膚插件所需樣式
     * @param array $param 相關參數
     * @return void
     */
    public function public_head($param)
    {
        // 載入換膚插件基本樣式
        echo '<link href="'.$this->htmlPath.'/html/base.css" rel="stylesheet" type="text/css" />';
        // 載入後臺設定的默認樣式
        $default = $this->model('SpaceStyle')->getDefaultStyle();
        echo '<link href="'.$this->htmlPath.'/themes/'.$default.'/style.css" rel="stylesheet" type="text/css" />';
        // 載入使用者個性配置
        $param['uid'] = !$param['uid'] ? $this->mid : $param['uid'];
        $style_data = model( 'Cache' )->get( 'user_space_style_'.$param['uid'] );
        if ( !$style_data ){
            $style_data = $this->model('SpaceStyle')->getStyle($param['uid']);
            model( 'Cache' )->set( 'user_space_style_'.$param['uid'] , $style_data );
        }
        // 驗證是否存在使用者自定義配置
        if(!$style_data) {
            return false;
        }
        // 樣式名
        $classname = $style_data['classname'];
        // 背景圖
        $background = $style_data['background'];
        // 載入基本風格
        if('' !== $classname) {
            $class_url = $this->htmlPath.'/themes/'.$classname.'/style.css';
        }
        echo '<link href="'.$class_url.'" rel="stylesheet" type="text/css" id="change_skin" />';
        // 載入自定義背景
        $background['image'] && $background['image'] = "url('".SITE_URL."/".$background['image']."')";
        $background_CSS = array();
        foreach($background as $key => $value) {
            $value && $background_CSS[$key] = "background-{$key}:{$value};";
        }
        if(!empty($background_CSS)) {
            echo '<style id="change_background">#body_page{'.implode('', $background_CSS).'}</style>';
        }
    }

    /**
     * 主頁右上方鉤子，載入換膚插件按鈕
     * @return void
     */
    public function home_index_right_top()
    {
        $this->display('changeStyleBtn');
    }

    /**
     * 換膚操作浮視窗顯示
     * @return void
     */
    public function changeStyleBox()
    {
        // 獲取使用者面板資料
        $style_data = $this->model('SpaceStyle')->getStyle($this->mid);
        $this->assign('styleData', $style_data);
        // 載入默認樣式
        $default = $style_data['classname'];
        if(empty($default)) {
            // 載入後臺設定的默認樣式
            $default = $this->model('SpaceStyle')->getDefaultStyle();
        }
        $this->assign('default', $default);
        // 載入自定義背景圖片
        $pic = '';
        if(!empty($style_data['background']['image'])) {
            $pic = SITE_URL.'/'.$style_data['background']['image'];
        }
        $this->assign('pic', $pic);
        // 獲取默認面板資料
        $defaultStyle = model('Cache')->get('plugin_space_style');
        if(empty($defaultStyle)) {
            $this->getDefaultStyle();
            $defaultStyle = array();
            foreach(self::$defaultStyle as $value) {
                $styleConf = include(ADDON_PATH.'/plugin/SpaceStyle/themes/'.$value.'/config.php');
                $data[$value]['name'] = $styleConf['name'];
                $data[$value]['thumb_url'] = ADDON_URL.'/plugin/SpaceStyle/themes/'.$value.'/thumb.png';
                $defaultStyle = array_merge($defaultStyle, $data);
            }
            model('Cache')->set('plugin_space_style', $defaultStyle);
        }
        $this->assign('defaultStyle', $defaultStyle);
        $this->display('changeStyleBox');
    }

    /**
     * 獲取系統默認面板
     * @return void
     */
    public function getDefaultStyle()
    {
        $dirname = ADDON_PATH.'/plugin/SpaceStyle/themes';
        $handle = opendir($dirname);
        while(false !== ($file = readdir($handle))) {
            if($file != '.' && $file != '..' && $file != '.svn') {
                self::$defaultStyle[$file] = $file;
            }
        }
    }

    /**
     * 儲存樣式
     * @return json 相應的Json資料
     */
    public function saveStyle()
    {
        $change_style_model = $this->model('SpaceStyle');
        $res = $change_style_model->saveStyle($this->mid, $_POST);

        $ajax_return = array(
            'data' => '',
            'info' => $change_style_model->getLastError(),
            'status' => false !== $res
        );

        model( 'Cache' )->set( 'user_space_style_'.$this->mid , null);
        exit(json_encode($ajax_return));
    }

    /**
     * 刪除臨時圖片
     * @return void
     */
    public function delImage()
    {
        $dir_path = SITE_PATH.'/data/upload/background'.$this->convertUidToPath($this->mid);
        $imagePath = $dir_path.'/'.basename($_POST['imagePath']);
        if(unlink($imagePath)) {
            echo 1;
        }
    }

    /**
     * 儲存臨時圖片
     * @return void
     */
    public function saveImageTemp()
    {
        $imageInfo = getimagesize($_FILES['pic']['tmp_name']);
        $filesize = abs(filesize($_FILES['pic']['tmp_name']));
        $result = array();
        if($filesize > 1024*1024*2 || $_FILES['pic']['error'] > 0) {
            $result['status'] = 0;
            $result['info'] = '上傳檔案不能大於2MB';
        } else {
            $imageType = strtolower(substr($_FILES['pic']['name'],strrpos($_FILES['pic']['name'],'.')+1));
            if($imageType == "jpeg") {
                $imageType ='jpg';
            }
            if(!in_array($imageType,array('jpg','png','gif','bmp'))){
                $result['status'] = 0;
                $result['info'] = '不是有效的圖片類型';
                exit(json_encode($result));
            }
            $dir_path = 'data/upload/background'.$this->convertUidToPath($this->mid);
            $savePath = SITE_PATH.'/'.$dir_path;
            if(!file_exists($savePath)) {
                mkdir($savePath, 0777, true);
            }
            $filename = md5($_FILES['pic']['tmp_name'].$this->mid ).'.'.$imageType;
            $moveUploadRes = move_uploaded_file($_FILES['pic']['tmp_name'], $savePath.'/'.$filename);
            $result['status'] = 1;
            $result['info'] = $dir_path.'/'.$filename;
        }

        exit(json_encode($result));
    }

    /**
     * 將使用者的UID轉換為三級路徑
     * @param integer $uid 使用者UID
     * @return string 使用者路徑
     */
    public function convertUidToPath($uid)
    {
        // 靜態快取
        $sc = static_cache('avatar_uidpath_'.$uid);
        if(!empty($sc)) {
            return $sc;
}
$md5 = md5($uid);
$sc = '/'.substr($md5, 0, 2).'/'.substr($md5, 2, 2).'/'.substr($md5, 4, 2);
static_cache('avatar_uidpath_'.$uid, $sc);

return $sc;
}

/**
 * 換膚插件，後臺管理
 * @return void
 */
public function config()
{
    $default = $this->model('SpaceStyle')->getDefaultStyle();
    $this->assign('default', $default);
    // 獲取默認面板資料
    $defaultStyle = model('Cache')->get('plugin_space_style');
    if(empty($defaultStyle)) {
        $this->getDefaultStyle();
        $defaultStyle = array();
        foreach(self::$defaultStyle as $value) {
            $styleConf = include(ADDON_PATH.'/plugin/SpaceStyle/themes/'.$value.'/config.php');
            $data[$value]['name'] = $styleConf['name'];
            $data[$value]['thumb_url'] = ADDON_URL.'/plugin/SpaceStyle/themes/'.$value.'/thumb.png';
            $defaultStyle = array_merge($defaultStyle, $data);
}
model('Cache')->set('plugin_space_style', $defaultStyle);
}
$this->assign('defaultStyle', $defaultStyle);
$this->display('config');
}

/**
 * 儲存後臺配置資料
 * @return void
 */
public function saveConfig()
{
    $default = t($_REQUEST['default']);
    model('AddonData')->putAddons('default_style', $default, true);
}
}
