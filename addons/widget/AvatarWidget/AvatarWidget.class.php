<?php
/**
 * 頭像上傳元件
 * @example {:W('Avatar',array('avatar'=>$user_info,'defaultImg'=>$user_info['avatar_big'],'callback'=>'gotoStep3'))}
 * @version TS3.0
 */
class AvatarWidget extends Widget {

    /**
     * @param array avatar 使用者資訊
     * @param string defaultImg 頭像地址
     * @param string callback 回撥方法
     */
    public function render($data) {
        $var['password']   = time();
        $var['defaultImg'] = 'noavatar/big.jpg';
        $var['uploadUrl']  = urlencode(U('public/Account/doSaveUploadAvatar'));
        // 獲取附件配置資訊
        $attachConf = model('Xdata')->get('admin_Config:attach');
        $var['attach_max_size'] = $attachConf['attach_max_size'];

        is_array($data) && $var = array_merge($var,$data);

        $content = $this->renderFile(dirname(__FILE__)."/default.html", $var);

        return $content;
    }

    /**
     * 輸出新頭像
     */
    public function getflashHtml(){
        $password   = time();
        $userinfo   = model('User')->getUserInfo($GLOBALS['ts']['mid']);
        $defaultImg = $userinfo['avatar_big'];
        $uploadUrl  = urlencode(U('public/Account/doSaveUploadAvatar'));
        echo ' <embed src="'.THEME_PUBLIC_URL.'/image/face.swf" quality="high" wmode="opaque"
            FlashVars="uploadServerUrl='.$uploadUrl.'&defaultImg='.$defaultImg.'"
            pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash"
            type="application/x-shockwave-flash" width="610" height="560"></embed>';
        exit();
    }
}
