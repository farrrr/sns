<?php
include_once('qq/oauth.php');
include_once('qq/WeiboOAuth.php');
include_once('qq/txwboauth.php');
class qq{

    var $loginUrl;

    function getUrl($url){
        $o = new QqWeiboOAuth( QQ_KEY , QQ_SECRET  );

        $keys = $o->getRequestToken($url);

        // QQ 返回的oauth_token 的鍵名有問題，在此臨時修正
        $_temp['oauth_token'] = array_shift($keys);
        $keys = array_merge($_temp, $keys);

        $this->loginUrl = $o->getAuthorizeURL( $keys['oauth_token'] ,false , $url);
        $_SESSION['qq']['keys'] = $keys;
        return $this->loginUrl;
    }

    //使用者資料
    function userInfo(){
        $me = $this->doClient()->verify_credentials();
        $user['id']         = $me['data']['name'];
        $user['uname']       = $me['data']['nick'];
        $user['province']    = $me['data']['province_code'];
        $user['city']        = $me['data']['city_code'];
        $user['location']    = $me['data']['location'];
        $user['userface']    = $me['data']['head']."/120";
        $user['sex']         = ($me['data']['sex']=='1')?1:0;

        return $user;
    }

    private function doClient($opt){

        $oauth_token = ( $opt['oauth_token'] )? $opt['oauth_token']:$_SESSION['qq']['access_token']['oauth_token'];
        $oauth_token_secret = ( $opt['oauth_token_secret'] )? $opt['oauth_token_secret']:$_SESSION['qq']['access_token']['oauth_token_secret'];

        return new QqWeiboClient( QQ_KEY , QQ_SECRET ,  $oauth_token, $oauth_token_secret  );
    }

    function user($opt){
        return $this->doClient($opt)->user_info();
    }

    //驗證使用者
    function checkUser(){
        $o = new QqWeiboOAuth( QQ_KEY , QQ_SECRET , $_SESSION['qq']['keys']['oauth_token'] , $_SESSION['qq']['keys']['oauth_token_secret']  );
        $access_token = $o->getAccessToken(  $_REQUEST['oauth_verifier'] ) ;

        // QQ 返回的oauth_token 的鍵名有問題，在此臨時修正
        $_temp['oauth_token'] = array_shift($access_token);
        $access_token = array_merge($_temp, $access_token);

        $_SESSION['qq']['access_token'] = $access_token;
        $_SESSION['open_platform_type'] = 'qq';
    }

    //釋出一條微博
    function update($text,$opt){
        return $this->doClient($opt)->t_add($text);
    }

    //上傳一個照片，並釋出一條微博
    function upload($text,$opt,$pic){
        if(file_exists($pic)){
            return $this->doClient($opt)->t_add_pic($text,$pic);
        }else{
            return $this->doClient($opt)->t_add($text);
        }
    }

    function saveData($data){

        if(!$data['errcode']){
            return array("qqId"=>$data['data']['id']);
        }
        return array();
    }

    function transpond($transpondId,$reId=0,$content='',$opt){
        if($reId){
            $this->doClient($opt)->t_reply($reId,$content);
        }
        if($transpondId){
            $result = $this->doClient($opt)->t_re_add($transpondId,$content);
        }
        return $result;
    }
}
