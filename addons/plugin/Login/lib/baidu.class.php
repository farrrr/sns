<?php
//使用V2版本的客戶端,支援Oauth2.0
require_once('baidu/Baidu.php');
class baidu{
    var $loginUrl;
    var $baidu;

    function getUrl($redirectUri){
        $baidu = new BaiduAPI(BAIDU_KEY, BAIDU_SECRET, $redirectUri, new BaiduCookieStore(BAIDU_KEY));
        $loginUrl = $baidu->getLoginUrl();
        return $loginUrl;
    }

    //使用者資料
    function userInfo(){
        $baidu = new BaiduAPI(BAIDU_KEY, BAIDU_SECRET);
        $user = $baidu->getLoggedInUser();
        if($user){
            $apiClient = $baidu->getBaiduApiClientService();
            $profile = $apiClient->api('/rest/2.0/passport/users/getInfo');
        }
        $user['id']         =  $user['uid'];
        $user['uname']       = $user['uname'];
        $user['province']    = 0;
        $user['city']        = 0;
        $user['location']    = '';
        $user['userface']    = $profile['portrait'];
        $user['sex']         = $profile['sex'];
        return $user;
    }

    //驗證使用者
    function checkUser(){
        $baidu = new BaiduAPI(BAIDU_KEY, BAIDU_SECRET);
        $access_token = $baidu->getAccessToken();
        $refresh_token = $baidu->getRefreshToken();
        $user = $baidu->getLoggedInUser();
        if($user){
            $_SESSION['baidu']['access_token']['oauth_token'] = $access_token;
            $_SESSION['baidu']['access_token']['oauth_token_secret'] = $refresh_token;
            $_SESSION['baidu']['isSync'] = 0;
            $_SESSION['baidu']['uid'] = $user['uid'];
            $_SESSION['open_platform_type'] = 'baidu';
            return $user;
        }else{
            return false;
        }
    }

    //釋出一條微博
    public function update($text,$opt){
        return true;
    }

    //上傳一個照片，並釋出一條微博
    public function upload($text,$opt,$pic){
        return true;
    }

    //轉發一條微博
    public function transpond($transpondId,$reId,$content='',$opt=null){
        return true;
    }

    //儲存資料
    public function saveData($data){
        return true;
    }
}
