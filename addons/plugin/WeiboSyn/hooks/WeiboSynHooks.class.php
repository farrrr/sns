<?php
class WeiboSynHooks extends Hooks{
    //站點配置
    private static $validLogin = array(
        "sina"      => array("sina_wb_akey", "sina_wb_skey"),
        "qzone"     => array("qzone_key", "qzone_secret"),
        "qq"        => array("qq_key", "qq_secret"),
        "renren"    => array("renren_key", "renren_secret"),
        "douban"    => array("douban_key", "douban_secret"),
        "baidu"     => array("baidu_key", "baidu_secret"),
        "taobao"    => array("taobao_key", "taobao_secret"),
        //"facebook"  => array("facebook_key", "facebook_secret"),
        //"google"    => array("google_id", "google_key", "google_secret"),
        //"twitter"  => array("twitter_key", "twitter_secret"),
    );
    //可同步釋出動態的站點
    private static $validPublish = array('sina', 'qq', 'qzone', 'renren');
    //應用名稱
    private static $validAlias   = array(
        'sina'      => '新浪微博',
        'qzone'     => "QQ互聯",
        'qq'        => '騰訊微博',
        'renren'    => "人人網",
        'douban'    => "豆瓣",
        'baidu'     => "百度",
        'taobao'    => "淘寶網",
        //'facebook'  => "facebook",
        //'google'    => "google",
        //'twitter'  => "twitter",
    );
    //應用申請地址
    private static $validApply  = array(
        'sina'      => 'http://open.weibo.com/',
        'qzone'     => "http://connect.qq.com",
        'qq'        => 'http://open.t.qq.com/websites/',
        'renren'    => "http://developer.renren.com",
        'douban'    => "http://www.douban.com/service/apidoc/connect",
        'baidu'     => "http://developer.baidu.com",
        'taobao'    => "http://open.taobao.com",
        //'facebook'  => "http://developer.facebook.com",
        //'google'    => "https://code.google.com/apis/console/",
        //'twitter'  => "http://developer.facebook.com",
    );

    public function weibo_syn_middle_publish() {
        $sync = self::$validPublish;
        //TODO:增加快取
        $bind = unserialize((S('user_login_'.$this->mid)));
        if(false === $bind){
            $bind = D ( 'login' )->where ( 'uid=' . $this->mid )->findAll ();
            S('user_login_'.$this->mid,serialize($bind));
        }

        foreach ( $bind as $v ) {
            $login_bind [$v ['type']] = $v ['is_sync'];
        }
        //檢查可同步的平臺的key值是否可用
        $config = model('AddonData')->lget('login');
        $validSync = array();
        foreach($sync as $value){
            if(!in_array($value,$config['publish']) || empty($config[self::$validLogin[$value][0]]) || empty($config[self::$validLogin[$value][1]])){
                continue;
            }
            $validSync[] = $value;
        }
        $this->assign('htmlPath',$this->htmlPath);
        $this->assign('login_bind', $login_bind);
        $this->assign('sync', $validSync);
        $this->assign('alias', self::$validAlias);
        if(!empty($validSync)){
            $this->display('syn');
        }
    }
    public function weibo_ajax_bind_publish_weibo($param)
    {
        $type = $param['type'];
        $type = strtolower($type);
        // 展示"開始繫結"按鈕
        $map ['uid'] = $this->mid;
        $map ['type'] = $type;
        if (M ( 'login' )->where ( "uid={$this->mid} AND type='{$type}' AND oauth_token<>''" )->count ()) {
            M ( 'login' )->setField ( 'is_sync', 1, $map );
            S('user_login_'.$this->mid,null);
            echo '1';
            exit ();
        } else {
            //          session_start();
            //          $_SESSION ['weibo_bind_target_url'] = U ( 'home/User/index' );
            //          $this->_loadTypeLogin($type);
            $url = U('public/Account/bind');
            echo '<dl class="pop_sync"><dt></dt>您還未繫結' . $type . '帳號, 請點這裡<dd><a class="btn-att-green" href="' . $url . '">開始繫結</a></dd></dl>';
            exit ();
        }

    }

    //釋出框解除同步繫結
    public function weibo_unbind_publish_weibo()
    {
        $type = h($_POST['type']);
        echo M("login")->setField('is_sync',0,"uid={$this->mid} AND type='{$type}'" );
        S('user_login_'.$this->mid,null);
    }

    private function _loadTypeLogin($type,$config = array()){
        $config = empty($config)?model('AddonData')->lget('login'):$config;
        if(isset(self::$validLogin[$type])){
            foreach(self::$validLogin[$type] as $value){
                if(empty($config[$value])) {
                    throw new Exception(self::$validAlias[$type]."沒有設定Key,請勿異常操作");
                }
                !defined(strtoupper($value)) && define(strtoupper($value),$config[$value]);
            }
            include_once $this->path . "/lib/{$type}.class.php";
        }
    }
    /**
     * 同步內容管理
     */
    public function login_plugin_publish(){
        unset($_POST['unset']);
        $config = model('AddonData')->lget('login');
        $temp = array_flip($config['publish']);
        foreach(self::$validLogin as $key=>$value){
            if( in_array($key,self::$validPublish)){
                $item = array('hasKey'=>false,'checked'=>false);
                if(!empty($config[$value[0]]) && !empty($config[$value[1]])){
                    $item['hasKey']= true;
                }
                if(isset($temp[$key])){
                    $item['checked'] = true;
                }
                $data[$key] = $item;
            }
        }
        $this->assign('data',$data);
        $this->assign('alias',self::$validAlias);
        $this->display('sync_publish_admin');
    }
    /**
     * 儲存同步內容管理
     */
    public function savePublishConfig(){
        $temp = array();
        foreach($_POST['open'] as $value){
            $temp[] = h($value);
        }
        $data['publish'] = $temp;
        $_POST && $res = model('AddonData')->lput('login', $data);
        return $res;
    }
}
?>
