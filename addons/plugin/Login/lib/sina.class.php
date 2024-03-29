<?php
//使用V2版本的客戶端,支援Oauth2.0
include_once( 'sina/saetv2.ex.class.php');
class sina{

    var $loginUrl;
    private $_sina_akey;
    private $_sina_skey;
    private $_oauth;

    public function __construct() {
        $this->_sina_akey = SINA_WB_AKEY;
        $this->_sina_skey = SINA_WB_SKEY;
        $this->_oauth = new SaeTOAuthV2( $this->_sina_akey , $this->_sina_skey );
    }

    public function getUrl($call_back = null) {
        if ( empty($this->_sina_akey) || empty($this->_sina_skey) )
            return false;
        if (is_null($call_back)) {
            $call_back = Addons::createAddonShow('Login','no_register_display',array('type'=>'sina','do'=>"bind"));
        }
        $this->loginUrl = $this->_oauth->getAuthorizeURL( $call_back );
        return $this->loginUrl;
    }

    //獲取token資訊
    public function getTokenInfo($access_token){
        return $this->_oauth->getTokenInfo( $access_token ) ;
    }

    //使用者資料
    public function userInfo($opt){
        $sinauid = $this->doClient($opt)->get_uid();
        $me = $this->doClient($opt)->show_user_by_id($sinauid['uid']);
        $user['id']          = $me['id'];
        $user['uname']       = $me['name'];
        $user['province']    = $me['province'];
        $user['city']        = $me['city'];
        $user['location']    = $me['location'];
        $user['userface']    = str_replace(  $user['id'].'/50/' , $user['id'].'/180/' ,$me['profile_image_url'] );
        $user['sex']         = ($me['gender']=='m')?1:0;
        return $user;
    }

    private function doClient($opt){
        if(isset($_SESSION['sina']['access_token'])){
            $access_token = $_SESSION['sina']['access_token']['oauth_token'];
            $refresh_token = $_SESSION['sina']['access_token']['oauth_token_secret'];
        }else{
            $access_token = $opt["oauth_token"];
            $refresh_token = $opt["oauth_token_secret"];
        }
        return new SaeTClientV2( $this->_sina_akey , $this->_sina_skey , $access_token , $refresh_token);
    }

    //驗證使用者
    public function checkUser(){
        if (isset($_REQUEST['code'])) {
            $keys = array();
            $keys['code'] = $_REQUEST['code'];
            $keys['redirect_uri'] = U('public/Widget/displayAddons',array('type'=>$_REQUEST['type'],'addon'=>'Login','hook'=>'no_register_display'));
            try {
                $token = $this->_oauth->getAccessToken( 'code', $keys ) ;
            } catch (OAuthException $e) {
                $token = null;
            }
        }else{
            $keys = array();
            $keys['refresh_token'] = $_REQUEST['code'];
            try {
                $token = $this->_oauth->getAccessToken( 'token', $keys ) ;
            } catch (OAuthException $e) {
                $token = null;
            }
        }
        if ($token) {
            setcookie( 'weibojs_'.$this->_oauth->client_id, http_build_query($token) );
            $_SESSION['sina']['access_token']['oauth_token'] = $token['access_token'];
            $_SESSION['sina']['access_token']['oauth_token_secret'] = $token['refresh_token'];
            $_SESSION['sina']['expire'] = time()+$token['expires_in'];
            $_SESSION['sina']['uid'] = $token['uid'];
            $_SESSION['open_platform_type'] = 'sina';
            return $token;
        }else{
            return false;
        }
    }

    //釋出一條微博
    public function update($text,$opt){
        return $this->doClient($opt)->update($text);
    }

    //上傳一個照片，並釋出一條微博
    public function upload($text,$opt,$pic){
        if(!file_exists($pic)){
            return $this->doClient($opt)->update($text);
        }else{
            return $this->doClient($opt)->upload($text,$pic);
        }
    }

    //轉發一條微博
    public function transpond($transpondId,$reId,$content='',$opt=null){
        if($reId){
            $this->doClient($opt)->send_comment($reId,$content);
        }

        if($transpondId){
            $result = $this->doClient($opt)->repost($transpondId,$content);
        }
    }

    //儲存資料
    public function saveData($data){
        if(isset($data['id'])){
            return array("sinaId"=>$data['id']);
        }
        return array();
    }
}
