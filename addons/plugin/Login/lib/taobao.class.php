<?php
//TODO 授權重新整理
class taobao{

    var $loginUrl;

    function getUrl($redirect_uri){
        if(!$redirect_uri)
            $redirect_uri = Addons::createAddonShow('Login','no_register_display',array('type'=>'taobao','do'=>"bind"));
        $_SESSION['state'] = md5(uniqid(rand(), TRUE));
        $this->loginUrl = 'https://oauth.taobao.com/authorize?'.'client_id='.TAOBAO_KEY.'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&state=' . $_SESSION['state'] ;
        return $this->loginUrl;
    }

    //使用者資料
    function userInfo(){
        if($_SESSION['taobao']['uid']){
            $user['id']         = $_SESSION['taobao']['uid'];
            $user['uname']      = urldecode($_SESSION['taobao']['uname']);
            $user['province']   = 0;
            $user['city']       = 0;
            $user['location']   = '';
            $user['userface']   = $_SESSION['taobao']['userface'];
            $user['sex']        = ($_SESSION['taobao']['sex']=='1')?1:0;
            return $user;
        }else{
            //用介面獲取資料
            return false;
        }
    }

    //驗證使用者
    function checkUser(){
        if($_REQUEST['code']){
            $redirect_uri = Addons::createAddonShow('Login','no_register_display',array('type'=>'taobao','do'=>"bind"));
            $url = 'https://oauth.taobao.com/token';
            $field = 'grant_type=authorization_code&client_id='.TAOBAO_KEY.'&code='.$_REQUEST['code'].'&client_secret='.TAOBAO_SECRET.'&redirect_uri='.urlencode($redirect_uri);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $field);

            $result = curl_exec($ch);
            $res = json_decode($result,TRUE);

            if($res['taobao_user_id']){
                $_SESSION['taobao']['access_token']['oauth_token'] = $res['access_token'];
                $_SESSION['taobao']['access_token']['oauth_token_secret'] = $res['refresh_token'];
                $_SESSION['taobao']['isSync'] = 1;
                $_SESSION['taobao']['uid'] = $res['taobao_user_id'];
                $_SESSION['taobao']['uname'] = $res['taobao_user_nick'];
                $_SESSION['taobao']['userface'] = '';
                $_SESSION['open_platform_type'] = 'taobao';
                return $res;
            }else{
                return false;
            }
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
