<?php
/**
 * ThinkSNS安裝檔案，修改自pbdigg。
 */
error_reporting(0);
session_start();
define('THINKSNS_INSTALL', TRUE);
define('THINKSNS_ROOT', str_replace('\\', '/', substr(dirname(__FILE__), 0, -7)));

$_TSVERSION = '3.0';

include 'install_function.php';
include 'install_lang.php';

$timestamp = time();
$ip = getip();
$installfile = 't_thinksns_com.sql';
$thinksns_config_file = 'config.inc.php';
$_SESSION['thinksns_install'] = $timestamp;

// 判斷是否安裝過
header('Content-Type: text/html; charset=utf-8');
if(file_exists('install.lock'))
{
    exit($i_message['install_lock']);
}
if(!is_readable($installfile))
{
    exit($i_message['install_dbFile_error']);
}
$quit = false;
$msg = $alert = $link = $sql = $allownext = '';

$PHP_SELF = addslashes(htmlspecialchars($_SERVER['PHP_SELF'] ? $_SERVER['PHP_SELF'] : $_SERVER['SCRIPT_NAME']));
set_magic_quotes_runtime(0);
if(!get_magic_quotes_gpc())
{
    addS($_POST);
    addS($_GET);
}
@extract($_POST);
@extract($_GET);
?>
<html>
<head>
<title><?php echo $i_message['install_title']; ?></title>
<meta http-equiv=Content-Type content="text/html; charset=utf-8">
<link href="images/style.css" rel="stylesheet" type="text/css" />
<body>
<div id='content'>
<div id='pageheader'>
    <div id="logo"><img src="images/thinksns.gif" width="260" height="80" border="0" alt="ThinkSNS" /></div>
    <div id="version" class="rightheader">Version <?php echo $_TSVERSION; ?></div>
</div>
<div id='innercontent'>
    <h1>ThinkSNS <?php echo $_TSVERSION, ' ', $i_message['install_wizard']; ?></h1>
<?php
if (!$v)
{
?>
<div class="botBorder">
<p><span class='red'><?php echo $i_message['install_warning'];?></span></p>
</div>
<div class="botBorder">
<?php echo $i_message['install_intro'];?>
</div>
<form method="post" action="install.php?v=1">
<p class="center"><input type="submit" class="submit" value="<?php echo $i_message['install_start'];?>" /></p>
</form>
<?php
}
elseif ($v == '1')
{
?>
<h2><?php echo $i_message['install_license_title'];?></h2>
<p>
<textarea class="textarea" readonly="readonly" cols="50">
<?php echo $i_message['install_license'];?>
</textarea>
</p>
<form action="install.php?v=2" method="post">
<p><label><input type="checkbox" name="agree" value="1" onClick="if(this.checked==true){this.form.next.disabled=''}else{this.form.next.disabled='true'}" checked="checked" /><?php echo $i_message['install_agree'];?></label></p>
<p class="center"><input type="submit" style="width:200px;" class="submit" name="next" value="<?php echo $i_message['install_next'];?>" /></p>
</form>
<?php
}
elseif ($v == '2')
{
    if ($agree == 'no')
    {
        echo '<script>alert('.$i_message['install_disagree_license'].');history.go(-1)</script>';
    }
    $dirarray = array (
        'data',
        '_runtime',
        'install',
        'config',
    );
    $writeable = array();
    foreach ($dirarray as $key => $dir)
    {
        if (writable($dir))
        {
            $writeable[$key] = $dir.result(1, 0);
        }
        else
        {
            $writeable[$key] = $dir.result(0, 0);
            $quit = TRUE;
        }
    }
?>
<div class="shade">
<div class="settingHead"><?php echo $i_message['install_env'];?></div>
<h5><?php echo $i_message['php_os'];?></h5>
<p><?php echo PHP_OS;result(1, 1);?></p>
<h5><?php echo $i_message['php_version'];?></h5>
<p>
<?php
    echo PHP_VERSION;
    if (PHP_VERSION < '5.2.0')
    {
        result(0, 1);
        $quit = TRUE;
    }
    else
    {
        result(1, 1);
    }
?></p>
<h5><?php echo $i_message['php_memory'];?></h5>
<p>
<?php
    echo $i_message['support'],'/',@ini_get('memory_limit');
    if ((int)@ini_get('memory_limit') < (int)'32M')
    {
        result(0, 1);
        $quit = TRUE;
    }
    else
    {
        result(1, 1);
    }
?></p>

<h5><?php echo $i_message['php_session'];?></h5>
<p>
<?php
    $session_path = @ini_get('session.save_path');
    if(!isset($_SESSION['thinksns_install'])){
        echo '<span class="red">'.$i_message['php_session_error'].': '.$session_path.'</span>';
        result(0, 1);
        $quit = TRUE;
    }
    else
    {
        echo $i_message['support'];
        result(1, 1);
    }
?></p>

<h5><?php echo $i_message['file_upload'];?></h5>
<p>
<?php
    if (@ini_get('file_uploads'))
    {
        echo $i_message['support'],'/',@ini_get('upload_max_filesize');
    }
    else
    {
        echo '<span class="red">'.$i_message['unsupport'].'</span>';
    }
    result(1, 1);
?></p>
<h5><?php echo $i_message['php_extention'];?></h5>
<p>
<?php
    if (extension_loaded('mysql'))
    {
        echo 'mysql:'.$i_message['support'];
        result(1, 1);
    }
    else
    {
        echo '<span class="red">'.$i_message['php_extention_unload_mysql'].'</span>';
        result(0, 1);
        $quit = TRUE;
    }
?></p>
<p>
<?php
    if (extension_loaded('gd'))
    {
        echo 'gd:'.$i_message['support'];
        result(1, 1);
    }
    else
    {
        echo '<span class="red">'.$i_message['php_extention_unload_gd'].'</span>';
        result(0, 1);
        $quit = TRUE;
    }
?></p>
<p>
<?php
    if (extension_loaded('curl'))
    {
        echo 'curl:'.$i_message['support'];
        result(1, 1);
    }
    else
    {
        echo '<span class="red">'.$i_message['php_extention_unload_curl'].'</span>';
        result(0, 1);
        $quit = TRUE;
    }
?></p>
<p>
<?php
    if (extension_loaded('mbstring'))
    {
        echo 'mbstring:'.$i_message['support'];
        result(1, 1);
    }
    else
    {
        echo '<span class="red">'.$i_message['php_extention_unload_mbstring'].'</span>';
        result(0, 1);
        $quit = TRUE;
    }
?></p>



<h5><?php echo $i_message['mysql'];?></h5>
<p>
<?php
    if (function_exists('mysql_connect'))
    {
        echo $i_message['support'];
        result(1, 1);
    }
    else
    {
        echo '<span class="red">'.$i_message['mysql_unsupport'].'</span>';
        result(0, 1);
        $quit = TRUE;
    }
?></p>


</div>
<div class="shade">
<div class="settingHead"><?php echo $i_message['dirmod'];?></div>
<?php
    foreach ($writeable as $value)
    {
        echo '<p>'.$value.'</p>';
    }

    if (is_writable(THINKSNS_ROOT.'/config/'.$thinksns_config_file))
    {
        echo '<p>'.$thinksns_config_file.result(1, 0).'</p>';
    }
    else
    {
        echo '<p>'.$thinksns_config_file.result(0, 0).'</p>';
        $quit = TRUE;
    }
?>

</div>
<p class="center">
    <form method="post" action='install.php?v=3'>
    <input style="width:200px;" type="submit" class="submit" name="next" value="<?php echo $i_message['install_next'];?>" <?php if($quit) echo "disabled=\"disabled\"";?>>
    </form>
</p>
<?php
}
elseif ($v == '3')
{
?>
<!-- <h2><?php echo $i_message['install_setting'];?></h2> -->
<form method="post" action="install.php?v=4" id="install" onSubmit="return check(this);">
<div class="shade">
<div class="settingHead"><?php echo $i_message['install_mysql'];?></div>

<h5><?php echo $i_message['install_mysql_host'];?></h5>
<p><?php echo $i_message['install_mysql_host_intro'];?></p>
<p><input type="text" name="db_host" value="localhost" size="40" class='input' /></p>

<h5><?php echo $i_message['install_mysql_username'];?></h5>
<p><input type="text" name="db_username" value="root" size="40" class='input' /></p>

<h5><?php echo $i_message['install_mysql_password'];?></h5>
<p><input type="password" name="db_password" value="" size="40" class='input' /></p>

<h5><?php echo $i_message['install_mysql_name'];?></h5>
<p><input type="text" name="db_name" value="thinksns_3_0" size="40" class='input' />
</p>

<h5><?php echo $i_message['install_mysql_prefix'];?></h5>
<p><?php echo $i_message['install_mysql_prefix_intro'];?></p>
<p><input type="text" name="db_prefix" value="ts_" size="40" class='input' /></p>

<h5><?php echo $i_message['site_url'];?></h5>
<p><?php echo $i_message['site_url_intro'];?></p>
<p><input type="text" name="site_url" value="<?php echo "http://".$_SERVER['HTTP_HOST'].rtrim(str_replace('\\', '/', dirname(dirname($_SERVER['SCRIPT_NAME']))),'/');?>" size="40" class='input' /></p>

</div>

<div class="shade">
<div class="settingHead"><?php echo $i_message['founder'];?></div>

<h5><?php echo $i_message['auto_increment'];?></h5>
<p><input type="text" name="first_user_id" value="1" size="40" class='input' /></p>

<h5><?php echo $i_message['install_founder_email'];?></h5>
<p><input type="text" name="email" value="admin@admin.com" size="40" class='input' /></p>

<h5><?php echo $i_message['install_founder_password'];?></h5>
<p><input type="password" name="password" value="" size="40" class='input' /></p>

<h5><?php echo $i_message['install_founder_rpassword'];?></h5>
<p><input type="password" name="rpassword" value="" size="40" class='input' /></p>


</div>
<div class="center">
    <input type="button" class="submit" name="prev" value="<?php echo $i_message['install_prev'];?>" onClick="history.go(-1)">&nbsp;
    <input type="submit" class="submit" name="next" value="<?php echo $i_message['install_next'];?>">
</form>
</div>
    <script type="text/javascript" language="javascript">
    function check(obj)
    {
        if (!obj.db_host.value)
        {
            alert('<?php echo $i_message['install_mysql_host_empty'];?>');
            obj.db_host.focus();
            return false;
}
else if (!obj.db_username.value)
{
    alert('<?php echo $i_message['install_mysql_username_empty'];?>');
    obj.db_username.focus();
    return false;
}
else if (!obj.db_name.value)
{
    alert('<?php echo $i_message['install_mysql_name_empty'];?>');
    obj.db_name.focus();
    return false;
}
else if (obj.password.value.length < 6)
{
    alert('<?php echo $i_message['install_founder_password_length'];?>');
    obj.password.focus();
    return false;
}
else if (obj.password.value != obj.rpassword.value)
{
    alert('<?php echo $i_message['install_founder_rpassword_error'];?>');
    obj.rpassword.focus();
    return false;
}
else if (!obj.email.value)
{
    alert('<?php echo $i_message['install_founder_email_empty'];?>');
    obj.email.focus();
    return false;
}
return true;
}
</script>
<?php
}
elseif ($v == '4')
{
    if(empty($db_host) || empty($db_username) || empty($db_name) || empty($db_prefix))
    {
        $msg .= '<p>'.$i_message['mysql_invalid_configure'].'<p>';
        $quit = TRUE;
    }
    elseif (!@mysql_connect($db_host, $db_username, $db_password))
    {
        $msg .= '<p>'.mysql_error().'</p>';
        $quit = TRUE;
    }
    if(strstr($db_prefix, '.'))
    {
        $msg .= '<p>'.$i_message['mysql_invalid_prefix'].'</p>';
        $quit = TRUE;
    }

    if (strlen($password) < 6)
    {
        $msg .= '<p>'.$i_message['founder_invalid_password'].'</p>';
        $quit = TRUE;
    }
    elseif ($password != $rpassword)
    {
        $msg .= '<p>'.$i_message['founder_invalid_rpassword'].'</p>';
        $quit = TRUE;
    }
    elseif (!preg_match('/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,3}$/i', $email))
    {
        $msg .= '<p>'.$i_message['founder_invalid_email'].'</p>';
        $quit = TRUE;
    }
    else
    {
        $forbiddencharacter = array ("\\","&"," ","'","\"","/","*",",","<",">","\r","\t","\n","#","$","(",")","%","@","+","?",";","^");
        foreach ($forbiddencharacter as $value)
        {
            if (strpos($username, $value) !== FALSE)
            {
                $msg .= '<p>'.$i_message['forbidden_character'].'</p>';
                $quit = TRUE;
                break;
            }
        }
    }

    if ($quit)
    {
        $allownext = 'disabled="disabled"';
?>
        <div class="error"><?php echo $i_message['error'];?></div>
<?php
        echo $msg;
    }
    else
    {

        $config_file_content    =   array();
        $config_file_content['db_host']         =   $db_host;
        $config_file_content['db_name']         =   $db_name;
        $config_file_content['db_username']     =   $db_username;
        $config_file_content['db_password']     =   $db_password;
        $config_file_content['db_prefix']       =   $db_prefix;
        $config_file_content['db_pconnect']     =   0;
        $config_file_content['db_charset']      =   'utf8';
        $config_file_content['dbType']          =   'MySQL';

        $default_manager_account    =   array();
        $default_manager_account['email']       =   $email;
        $default_manager_account['password']    =   md5(md5($password).'11111');

        $_SESSION['config_file_content']        =   $config_file_content;
        $_SESSION['default_manager_account']    =   $default_manager_account;
        $_SESSION['first_user_id']              =   $first_user_id;
        $_SESSION['site_url']                   =   $site_url;
    }
?>
    <div class="botBorder">
        <p><?php echo $i_message['install_founder_name'], ': ', $email?></p>
        <p><?php echo $i_message['install_founder_password'], ': ', $password;?></p>
    </div>
    <div class="botBorder">

<?php
    //寫配置檔案
    $randkey = uniqid(rand());
    $fp = fopen(THINKSNS_ROOT.'/config/'.$thinksns_config_file, 'wb');
    $configfilecontent = <<<EOT
<?php
if (!defined('SITE_PATH')) exit();

return array(
    // 資料庫常用配置
    'DB_TYPE'           =>  'mysql',            // 資料庫類型

    'DB_HOST'           =>  '$db_host',         // 資料庫伺服器地址
    'DB_NAME'           =>  '$db_name',         // 資料庫名
    'DB_USER'           =>  '$db_username',     // 資料庫使用者名
    'DB_PWD'            =>  '$db_password',     // 資料庫密碼

    'DB_PORT'           =>  3306,               // 資料庫埠
    'DB_PREFIX'         =>  '$db_prefix',       // 資料庫表字首（因為漫遊的原因，資料庫表字首必須寫在本檔案）
    'DB_CHARSET'        =>  'utf8',             // 資料庫編碼
    'SECURE_CODE'       =>  '$randkey', // 資料加密金鑰
    'COOKIE_PREFIX'     =>  'T3_',  // 資料加密金鑰
);
EOT;
    $configfilecontent = str_replace('SECURE_TEST','SECURE'.rand(10000,20000),$configfilecontent);
    chmod(THINKSNS_ROOT.'/config/'.$thinksns_config_file, 0777);
    $result_1   =   fwrite($fp, trim($configfilecontent));
    @fclose($fp);

    if($result_1 && file_exists(THINKSNS_ROOT.'/config/'.$thinksns_config_file)){
?>
    <p><?php echo $i_message['config_log_success']; ?></p>
<?php
    }else{
?>
    <p><?php echo $i_message['config_read_failed']; $quit = TRUE;?></p>
<?php
    }

    $dir   =  THINKSNS_ROOT.'/data/iswaf';
    // 目錄不存在則創建
    if(!is_dir($dir))  mkdir($dir,0777,true);

    $iswafKey = iswaf_create_key($site_url);
    $iswafConfig = array(
        'iswaf_database' => $dir.'/',
        'iswaf_connenct_key' => $iswafKey,
        'iswaf_status' => 1,
        'defences'=>array(
            'callback_xss'=>'On',
            'upload'=>'On',
            'inject'=>'On',
            'filemode'=>'On',
            'webshell'=>'On',
            'server_args'=>'On',
            'webserver'=>'On',
            'hotfixs'=>'On',
        )
    );
    file_put_contents($dir.'/config.php',"<?php\nreturn ".var_export($iswafConfig,true).";\n?>");
    $res = file_get_contents('http://www.fanghuyun.com/api.php?do=tsreg&IDKey='.$iswafKey.'&url='.$site_url);
?>
    </div>
    <div class="center">
        <form method="post" action="install.php?v=5">
        <input type="button" class="submit" name="prev" value="<?php echo $i_message['install_prev'];?>" onClick="history.go(-1)">&nbsp;
        <input type="submit" class="submit" name="next" value="<?php echo $i_message['install_next'];?>" <?php echo $allownext;?> >
        </form>
    </div>
<?php
}
elseif ($v == '5')
{
    $db_config  =   $_SESSION['config_file_content'];

    if (!$db_config['db_host'] && !$db_config['db_name'])
    {
        $msg .= '<p>'.$i_message['configure_read_failed'].'</p>';
        $quit = TRUE;
    }
    else
    {
        mysql_connect($db_config['db_host'], $db_config['db_username'], $db_config['db_password']);
        $sqlv = mysql_get_server_info();
        if($sqlv < '4.1')
        {
            $msg .= '<p>'.$i_message['mysql_version_402'].'</p>';
            $quit = TRUE;
        }
        else
        {
            $db_charset =   $db_config['db_charset'];
            $db_charset = (strpos($db_charset, '-') === FALSE) ? $db_charset : str_replace('-', '', $db_charset);

            mysql_query(" CREATE DATABASE IF NOT EXISTS `{$db_config['db_name']}` DEFAULT CHARACTER SET $db_charset ");

            if (mysql_errno())
            {
                $errormsg = mysql_error();
                $msg .= '<p>'.($errormsg ? $errormsg : $i_message['database_errno']).'</p>';
                $quit = TRUE;
            }
            else
            {
                mysql_select_db($db_config['db_name']);
            }

            //判斷是否有用同樣的資料庫字首安裝過
            $re     =   mysql_query("SELECT COUNT(1) FROM {$db_config['db_prefix']}user");
            $link   =   @mysql_fetch_row($re);

            if( intval($link[0]) > 0 )
            {
                $thinksns_rebuild   =   true;
                $msg .= '<p>'.$i_message['thinksns_rebuild'].'</p>';
                $alert = ' onclick="return confirm(\''.$i_message['thinksns_rebuild'].'\');"';
            }
        }
    }

    if ($quit)
    {
        $allownext = 'disabled="disabled"';
?>
<div class="error"><?php echo $i_message['error'];?></div>
<?php
        echo $msg;
    }
    else
    {
?>
<div class="botBorder">
<?php
        if($thinksns_rebuild){
?>
<p style="color:red;font-size:16px;"><?php echo $i_message['thinksns_rebuild'];?></p>
<?php
        }
?>
<p><?php echo $i_message['mysql_import_data'];?></p>
</div>
<?php
    }
?>
<div class="center">
    <form method="post" action="install.php?v=6">
    <input type="button" class="submit" name="prev" value="<?php echo $i_message['install_prev'];?>" onClick="history.go(-1)">&nbsp;
    <input type="submit" class="submit" name="next" value="<?php echo $i_message['install_next'];?>" <?php echo $allownext,$alert?>>
    </form>
</div>
<?php
}
elseif ($v == '6')
{
    $db_config  =   $_SESSION['config_file_content'];

    mysql_connect($db_config['db_host'], $db_config['db_username'], $db_config['db_password']);
    if (mysql_get_server_info() > '5.0')
    {
        mysql_query("SET sql_mode = ''");
    }
    $db_config['db_charset'] = (strpos($db_config['db_charset'], '-') === FALSE) ? $db_config['db_charset'] : str_replace('-', '', $db_config['db_charset']);
    mysql_query("SET character_set_connection={$db_config['db_charset']}, character_set_results={$db_config['db_charset']}, character_set_client=binary");
    mysql_select_db($db_config['db_name']);
    $tablenum = 0;

    $fp = fopen($installfile, 'rb');
    $sql = fread($fp, filesize($installfile));
    fclose($fp);
?>
<div class="botBorder">
<h4><?php echo $i_message['import_processing'];?></h4>
<div style="overflow-y:scroll;height:100px;width:715px;padding:5px;border:1px solid #ccc;">
<?php
    $db_charset =   $db_config['db_charset'];
    $db_prefix  =   $db_config['db_prefix'];
    $sql = str_replace("\r", "\n", str_replace('`'.'ts_', '`'.$db_prefix, $sql));
    foreach(explode(";\n", trim($sql)) as $query)
    {
        $query = trim($query);
        if($query) {
            if(substr($query, 0, 12) == 'CREATE TABLE')
            {
                $name = preg_replace("/CREATE TABLE ([A-Z ]*)`([a-z0-9_]+)` .*/is", "\\2", $query);
                echo '<p>'.$i_message['create_table'].' '.$name.' ... <span class="blue">OK</span></p>';
                @mysql_query(createtable($query, $db_charset));
                $tablenum ++;
            }
            else
            {
                @mysql_query($query);
            }
        }
    }
?>
</div>
</div>
<div class="botBorder">
<h4><?php echo $i_message['create_founder'];?></h4>

<?php
    //設定網站使用者起始ID
    if(intval($_SESSION['first_user_id'])>0){
        $admin_id   =   intval($_SESSION['first_user_id']);
        $sql0   =   "ALTER TABLE `{$db_config['db_prefix']}user` AUTO_INCREMENT=".$admin_id.";";
        if( mysql_query($sql0) ){
            echo '<p>'.$i_message['set_auto_increment_success'].'... <span class="blue">OK..'.$admin_id.'</span></p>';
        } else {
            echo '<p>'.$i_message['set_auto_increment_error'].'... <span class="red">ERROR</span></p>';
            $admin_id   =   1;
        }
    }else{
        $admin_id   =   1;
    }
    //添加管理員
    $siteFounder    =   $_SESSION['default_manager_account'];

    $sql1 = "INSERT INTO `{$db_config['db_prefix']}user` VALUES (".$admin_id.", '".$siteFounder['email']."', '".$siteFounder['password']."', '11111', '管理員', '".$siteFounder['email']."', '1', '北京市 北京市 海淀區', '1', '1', '1', ".time().", '1', '', '', '110000', '110100', '110108', '127.0.0.1', 'zh-tw', 'Asia/Taipei', '0', 'G', '', 0, 0, 0, '管理員 guanliyuan', '');";

    if( mysql_query($sql1) ){
        echo '<p>'.$i_message['create_founderpower_success'].'... <span class="blue">OK</span></p>';
    } else {
        echo '<p>'.$i_message['create_founderpower_error'].'... <span class="red">ERROR</span></p>';
        $quit   =   true;
    }

    //將管理員加入“管理員”使用者組
    $sql_user_group = "INSERT INTO `{$db_config['db_prefix']}user_group_link` (`id`,`uid`,`user_group_id`) VALUES ('1', ".$admin_id.",'1');";
    if( mysql_query($sql_user_group) ){

    } else {
        $quit   =   true;
    }

    //將管理員設定為默認關注的使用者
    // $sql_auto_friend = "REPLACE INTO `{$db_config['db_prefix']}system_data` (`list`,`key`,`value`) VALUES ('register', 'register_auto_friend', '".serialize($admin_id)."');";
    // if( mysql_query($sql_auto_friend) ){

    // } else {
    //  $quit   =   true;
    // }

    if(!$quit){
        //鎖定安裝
        fopen('install.lock', 'w');
        @unlink('../index.html');
    }else{
        echo '請重新安裝';
    }
?>
</div>
<div class="botBorder">
<h4><?php echo $i_message['install_success'];?></h4>
<?php echo $i_message['install_success_intro'];?>
</div>
<iframe src="<?php echo $_SESSION['site_url'];?>/cleancache.php?all" height="0" width="0" style="display: none;"></iframe>
<?php
}
?>
</div>
<div class='copyright'>ThinkSNS <?php echo $_TSVERSION; ?> &#169; copyright 2008-<?php echo date('Y') ?> www.ThinkSNS.com All Rights Reserved</div>
</div>
<div style="display:none;">
<script src="http://s79.cnzz.com/stat.php?id=1702264&web_id=1702264" language="JavaScript" charset="gb2312"></script>
</div>
</body>
</html>
