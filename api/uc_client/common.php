<?php
/* 字元、陣列串編碼轉換 */
function ts_change_charset($fContents,$from='UTF8',$to='GBK'){
    $from   =  strtoupper($from)=='UTF8'? 'utf-8':$from;
    $to     =  strtoupper($to)=='UTF8'? 'utf-8':$to;
    if( strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents)) ){
        //如果編碼相同或者非字元串標量則不轉換
        return $fContents;
    }
    if(is_string($fContents) ) {
        if(function_exists('mb_convert_encoding')){
            return mb_convert_encoding ($fContents, $to, $from);
        }elseif(function_exists('iconv')){
            return iconv($from,$to,$fContents);
        }else{
            return $fContents;
        }
    }else{
        return $fContents;
    }
}

function ts_auto_charset($content){
    return ts_change_charset($content, 'UTF8', UC_DBCHARSET);
}

function uc_auto_charset($content){
    return ts_change_charset($content, UC_DBCHARSET, 'UTF8');
}

//添加ThinkSNS與UCenter的使用者對映
function ts_add_ucenter_user_ref($uid,$uc_uid,$uc_username='',$uc_email=''){
    $uc_ref_data = array(
        'uid' => $uid,
        'uc_uid' => $uc_uid,
        'uc_username'  => $uc_username,
        'uc_email'  => $uc_email,
    );
    // M('ucenter_user_link')->add($uc_ref_data);
    $result = $GLOBALS['tsdb']->query("INSERT INTO ".TS_DBTABLEPRE."ucenter_user_link (uid,uc_uid,uc_username,uc_email) VALUES ('{$uid}','{$uc_uid}','{$uc_username}','{$uc_email}');");
    if($result){
        return $uc_ref_data;
    }else{
        return false;
    }
}

//更新ThinkSNS與UCenter的使用者對映
function ts_update_ucenter_user_ref($uid,$uc_uid,$uc_username=''){
    $uid         && $map['uid']                 = intval($uid);
    $uc_uid      && $map['uc_uid']              = intval($uc_uid);
    if(empty($uc_username))return;
    foreach($map as $k=>$v){
        $where .= "AND {$k}='{$v}'";
    }
    $result = $GLOBALS['tsdb']->query("UPDATE ".TS_DBTABLEPRE."ucenter_user_link SET  uc_username='{$uc_username}' WHERE 1=1 ".$where);
    return $result;
    // M('ucenter_user_link')->where($map)->save($uc_ref_data);
}

//獲取ThinkSNS與UCenter的使用者對映
function ts_get_ucenter_user_ref($uid='',$uc_uid='',$uc_username=''){
    $uid && $map['uid']                 = intval($uid);
    $uc_uid && $map['uc_uid']           = intval($uc_uid);
    $uc_username && $map['uc_username'] = $uc_username;
    if(!$map) return;
    foreach($map as $k=>$v){
        $where .= "AND {$k}='{$v}'";
    }
    $result = $GLOBALS['tsdb']->fetch_first("SELECT * FROM ".TS_DBTABLEPRE."ucenter_user_link WHERE 1=1 ".$where);
    return $result;
    // return M('ucenter_user_link')->where($map)->find();
}

//獲取ThinkSNS使用者資訊
function ts_get_user($uid){
    $uid = intval($uid);
    $result = $GLOBALS['tsdb']->fetch_first("SELECT uid,uname,email,login,is_active FROM ".TS_DBTABLEPRE."user WHERE uid='{$uid}'");
    return $result;
}

//獲取ThinkSNS站點Key（用於區別同一臺機器安裝的兩個TS系統）
function ts_get_site_key(){
    global $tsconfig;
    return md5($tsconfig['SECURE_KEY'].$tsconfig['SECURE_CODE'].$tsconfig['COOKIE_PREFIX']);
}

//添加帳號到TS
function ts_add_user($uc_user){
    // $login_salt = rand(11111, 99999);
    // $map['uname'] = $uc_user[1];
    // $map['sex'] = 1;
    // $map['login_salt'] = $login_salt;
    // $map['password'] = md5(md5($uc_user[2]).$login_salt);
    // $map['login'] = $map['email'] = $uc_user[3];
    // $map['reg_ip'] = get_client_ip();
    // $map['ctime'] = time();
    // $map['is_audit'] = 1;
    // $map['is_active'] = 1;
    // $map['first_letter'] = getFirstLetter($uname);
    // //如果包含中文將中文翻譯成拼音
    // if ( preg_match('/[\x7f-\xff]+/', $map['uname'] ) ){
    //  //昵稱和呢稱拼音儲存到搜索欄位
    //  $map['search_key'] = $map['uname'].' '.model('PinYin')->Pinyin( $map['uname'] );
    // } else {
    //  $map['search_key'] = $map['uname'];
    // }
    // $ts_uid = model('User')->add($map);
    // if(!$ts_uid){
    //  $this->error = '本地使用者註冊失敗，請聯繫管理員';
    //  return false;
    // }

    // //寫入關聯表
    // $result = ts_add_ucenter_user_ref($ts_uid,$uc_user[0],$uc_user[1],$uc_user[3]);
    // if(!$result){
    //  $this->error = '使用者不存在或密碼錯誤';
    //  return false;
    // }

    // // 添加至默認的使用者組
    // $registerConfig = model('Xdata')->get('admin_Config:register');
    // $userGroup = empty($registerConfig['default_user_group']) ? C('DEFAULT_GROUP_ID') : $registerConfig['default_user_group'];
    // model('UserGroupLink')->domoveUsergroup($ts_uid, implode(',', $userGroup));

    // // 添加雙向關注使用者
    // $eachFollow = $registerConfig['each_follow'];
    // if(!empty($eachFollow)) {
    //  model('Follow')->eachDoFollow($ts_uid, $eachFollow);
    // }

    // // 添加默認關注使用者
    // $defaultFollow = $registerConfig['default_follow'];
    // $defaultFollow = array_diff(explode(',', $defaultFollow), explode(',', $eachFollow));
    // if(!empty($defaultFollow)) {
    //  model('Follow')->bulkDoFollow($ts_uid, $defaultFollow);
    // }
}

//同步登入ThinkSNS
function ts_synclogin($user){
    session_start();
    $uid = $user['uid'];
    // 註冊session
    $_SESSION['mid'] = intval($uid);
    $_SESSION['SITE_KEY'] = ts_get_site_key();

    // 更新登陸時間
    // model('User')->setField('last_login_time', $_SERVER['REQUEST_TIME'], 'uid='.$uid );

    // 記錄登陸日誌，首次登陸判斷
    // empty($this->rel) && $this->rel  = D('')->table(C('DB_PREFIX').'login_record')->where("uid = ".$uid)->getField('login_record_id');

    // $map['ip'] = get_client_ip();
    // $map['ctime'] = time();
    // $map['locktime'] = 0;

    // if($this->rel) {
    //  D('')->table(C('DB_PREFIX').'login_record')->where("uid = ".$uid)->save($map);
    // } else {
    //  $map['uid'] = $uid;
    //  D('')->table(C('DB_PREFIX').'login_record')->add($map);
    // }

    return true;
}

//同步退出ThinkSNS
function ts_synclogout(){
    session_start();
    unset($_SESSION['mid'],$_SESSION['SITE_KEY']); // 登出session
    _setcookie('TSV3_LOGGED_USER', NULL);   // 登出cookie
}

//note 使用該函數前需要 require_once $this->appdir.'./uc_client/uc_config.inc.php';
function _setcookie($var, $value, $life = 0, $prefix = 1) {
    global $cookiepre, $cookiedomain, $cookiepath, $timestamp, $_SERVER;
    setcookie(($prefix ? $cookiepre : '').$var, $value,
        $life ? $timestamp + $life : 0, $cookiepath,
        $cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function _authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;

    $key = md5($key ? $key : UC_KEY);
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
}

for($j = $i = 0; $i < 256; $i++) {
    $j = ($j + $box[$i] + $rndkey[$i]) % 256;
    $tmp = $box[$i];
    $box[$i] = $box[$j];
    $box[$j] = $tmp;
}

for($a = $j = $i = 0; $i < $string_length; $i++) {
    $a = ($a + 1) % 256;
    $j = ($j + $box[$a]) % 256;
    $tmp = $box[$a];
    $box[$a] = $box[$j];
    $box[$j] = $tmp;
    $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
}

if($operation == 'DECODE') {
    if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
        return substr($result, 26);
} else {
    return '';
}
} else {
    return $keyc.str_replace('=', '', base64_encode($result));
}

}

function _stripslashes($string) {
    if(is_array($string)) {
        foreach($string as $key => $val) {
            $string[$key] = _stripslashes($val);
}
} else {
    $string = stripslashes($string);
}
return $string;
}

class uc_note {

    var $dbconfig = '';
    var $db = '';
    var $tablepre = '';
    var $appdir = '';

    function _serialize($arr, $htmlon = 0) {
        if(!function_exists('xml_serialize')) {
            include_once DISCUZ_ROOT.'./uc_client/lib/xml.class.php';
}
return xml_serialize($arr, $htmlon);
}

function uc_note() {
    $this->appdir = substr(dirname(__FILE__), 0, -3);
    $this->dbconfig = $this->appdir.'./uc_client/uc_config.inc.php';
    $this->db = $GLOBALS['db'];
    $this->tablepre = $GLOBALS['tablepre'];
}

//UC通訊測試
function test($get, $post) {
    return API_RETURN_SUCCEED;
}

//UC同步更新頭像到TS - 尚未同步
function face($get){
    if($get['type'] !== "face"){
        $uc_uid = $get['uid'];
        $uc_user_ref = ts_get_ucenter_user_ref('',$uc_uid);
        $user = M('user')->where("uid={$uc_user_ref['uid']}")->find();
        if($user) {
            echo $user['uid'];
                /*cookie('LOGGED_USER',jiami('thinksns.'.$user['uid']),(3600*2))*/;
}
}else{
    $data = 'http://dev.thinksns.com/ts/2.0/public/themes/classic2';
    $face = str_replace("THEME_URL", $data, getUserFace( $get['uid']));
    $data = 'http://dev.thinksns.com/ts/2.0';
    $face = str_replace("SITE_URL", $data, $face);
    echo $face;
}
}

//UC同步刪除使用者 - 尚未同步
function deleteuser($get, $post) {
    $uids = $get['ids'];
    !API_DELETEUSER && exit(API_RETURN_FORBIDDEN);
    return API_RETURN_SUCCEED;
}

//UC同步修改TS使用者名 - 已解決GBK問題
function renameuser($get, $post) {
    if(!API_RENAMEUSER) {
        return API_RETURN_FORBIDDEN;
}
$uc_uid = $get['uid'];
//$usernameold = $get['oldusername'];
$usernamenew = uc_auto_charset($get['newusername']);
ts_update_ucenter_user_ref('',$uc_uid,$usernamenew);
return API_RETURN_SUCCEED;
}

//獲取標籤 - 未實現
function gettag($get, $post) {
    $name = $get['id'];
    if(!API_GETTAG) {
        return API_RETURN_FORBIDDEN;
}

$return = array();
return $this->_serialize($return, 1);
}

//UC同步登入TS - 已解決GBK問題
function synlogin($get, $post) {
    if(!API_SYNLOGIN){
        return API_RETURN_FORBIDDEN;
}
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');

$uc_uid      = $get['uid'];
$uc_uname    = uc_auto_charset($get['username']);
$uc_password = $get['password'];
$uc_user_ref = ts_get_ucenter_user_ref('',$uc_uid);
$user = ts_get_user($uc_user_ref['uid']);
if($user) {
    //檢查是否啟用，未啟用使用者不自動登入
    if ($user['is_active'] == 0) {
        exit;
}
if($uc_uname != $uc_user_ref['uc_username']){
    ts_update_ucenter_user_ref($uc_user_ref['uid'],$uc_uid,$uc_uname);
}
//登入到TS系統
$user['login_from_dz'] = true;
$result = ts_synclogin($user);
}
}

//UC同步退出TS - 已解決
function synlogout($get, $post) {
    if(!API_SYNLOGOUT) {
        return API_RETURN_FORBIDDEN;
}

//note 同步登出 API 介面
header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
ts_synclogout();
}

//UC同步更新TS密碼 - 未實現 實際傳回密碼並不是md5值
function updatepw($get, $post) {
    if(!API_UPDATEPW) {
        return API_RETURN_FORBIDDEN;
}
// $uc_username = uc_auto_charset($get['username']);
// $password     = $get['password'];
// $uc_user_ref = ts_get_ucenter_user_ref('','',$uc_username);
return API_RETURN_SUCCEED;
}

//UC同步更新敏感詞 - 尚未同步
function updatebadwords($get, $post) {
    if(!API_UPDATEBADWORDS) {
        return API_RETURN_FORBIDDEN;
}
$cachefile = $this->appdir.'./uc_client/data/cache/badwords.php';
$fp = fopen($cachefile, 'w');
$data = array();
if(is_array($post)) {
    foreach($post as $k => $v) {
        $data['findpattern'][$k] = $v['findpattern'];
        $data['replace'][$k] = $v['replacement'];
}
}
$s = "<?php\r\n";
$s .= '$_CACHE[\'badwords\'] = '.var_export($data, TRUE).";\r\n";
fwrite($fp, $s);
fclose($fp);
return API_RETURN_SUCCEED;
}

//尚未同步
function updatehosts($get, $post) {
    if(!API_UPDATEHOSTS) {
        return API_RETURN_FORBIDDEN;
}
$cachefile = $this->appdir.'./uc_client/data/cache/hosts.php';
$fp = fopen($cachefile, 'w');
$s = "<?php\r\n";
$s .= '$_CACHE[\'hosts\'] = '.var_export($post, TRUE).";\r\n";
fwrite($fp, $s);
fclose($fp);
return API_RETURN_SUCCEED;
}

//尚未同步
function updateapps($get, $post) {
    if(!API_UPDATEAPPS) {
        return API_RETURN_FORBIDDEN;
}
$UC_API = $post['UC_API'];

//note 寫 app 快取檔案
$cachefile = $this->appdir.'./uc_client/data/cache/apps.php';
$fp = fopen($cachefile, 'w');
$s = "<?php\r\n";
$s .= '$_CACHE[\'apps\'] = '.var_export($post, TRUE).";\r\n";
fwrite($fp, $s);
fclose($fp);

//note 寫配置檔案
if(is_writeable($this->appdir.'./uc_client/uc_config.inc.php')) {
    $configfile = trim(file_get_contents($this->appdir.'./uc_client/uc_config.inc.php'));
    $configfile = substr($configfile, -2) == '?>' ? substr($configfile, 0, -2) : $configfile;
    $configfile = preg_replace("/define\('UC_API',\s*'.*?'\);/i", "define('UC_API', '$UC_API');", $configfile);
    if($fp = @fopen($this->appdir.'./uc_client/uc_config.inc.php', 'w')) {
        @fwrite($fp, trim($configfile));
        @fclose($fp);
}
}

return API_RETURN_SUCCEED;
}

//尚未同步
function updateclient($get, $post) {
    if(!API_UPDATECLIENT) {
        return API_RETURN_FORBIDDEN;
}
$cachefile = $this->appdir.'./uc_client/data/cache/settings.php';
$fp = fopen($cachefile, 'w');
$s = "<?php\r\n";
$s .= '$_CACHE[\'settings\'] = '.var_export($post, TRUE).";\r\n";
fwrite($fp, $s);
fclose($fp);
return API_RETURN_SUCCEED;
}

//積分同步 - 尚未同步
function updatecredit($get, $post) {
    if(!API_UPDATECREDIT) {
        return API_RETURN_FORBIDDEN;
}
$credit = $get['credit'];
$amount = $get['amount'];
$uid = $get['uid'];
return API_RETURN_SUCCEED;
}

//積分同步 - 尚未同步
function getcredit($get, $post) {
    if(!API_GETCREDIT) {
        return API_RETURN_FORBIDDEN;
}
}

//積分同步 - 尚未同步
function getcreditsettings($get, $post) {
    if(!API_GETCREDITSETTINGS) {
        return API_RETURN_FORBIDDEN;
}
$credits = array();
return $this->_serialize($credits);
}

//積分同步 - 尚未同步
function updatecreditsettings($get, $post) {
    if(!API_UPDATECREDITSETTINGS) {
        return API_RETURN_FORBIDDEN;
}
return API_RETURN_SUCCEED;
}
}
