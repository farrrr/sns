<?php
/**
 * 郵件模型 - 資料物件模型
 * @author jason <yangjs17@yeah.net>
 * @version TS3.0
 */
class MailModel {

    // 允許發送郵件的類型
    public static $allowed = array('Register','unAudit','resetPass','resetPassOk','invateOpen','invate','atme','comment','reply');
    public $message;

    /**
     * 初始化方法，載入phpmailer，初始化默認參數
     * @return void
     */
    public function __construct() {
        tsload(ADDON_PATH.'/library/phpmailer/class.phpmailer.php');
        tsload(ADDON_PATH.'/library/phpmailer/class.pop3.php');
        tsload(ADDON_PATH.'/library/phpmailer/class.smtp.php');
        $emailset = model('Xdata')->get('admin_Config:email');
        $this->option = array(
            'email_sendtype'        => $emailset['email_sendtype'],
            'email_host'            => $emailset['email_host'],
            'email_port'            => $emailset['email_port'],
            'email_ssl'             => $emailset['email_ssl'],
            'email_account'         => $emailset['email_account'],
            'email_password'        => $emailset['email_password'],
            'email_sender_name'     => $emailset['email_sender_name'],
            'email_sender_email'    => $emailset['email_sender_email'],
            'email_reply_account'   => $emailset['email_sender_email']
        );
    }

    /**
     * 測試發送郵件
     * @param array $data 郵件相關內容資料
     * @return boolean 是否發送成功
     */
    public function test_email($data) {
        $this->option = $data;
        $this->option['email_reply_account'] = $this->option['email_sender_email'];
        return $this->send_email($data['sendto_email'],'測試郵件','這是一封測試郵件');
    }

    /**
     * 發送郵件
     * @param string $sendto_email 收件人的Email
     * @param string $subject 主題
     * @param string $body 正文
     * @param array $senderInfo 發件人資訊 array('email_sender_name'=>'發件人姓名', 'email_account'=>'發件人Email地址')
     * @return boolean 是否發送郵件成功
     */
    public function send_email( $sendto_email, $subject, $body, $senderInfo = '') {
        $mail = new PHPMailer();
        if(empty($senderInfo)) {
            $sender_name = $this->option['email_sender_name'];
            $sender_email = empty($this->option['email_sender_email']) ? $this->option['email_account'] : $this->option['email_sender_email'];
        } else {
            $sender_name = $senderInfo['email_sender_name'];
            $sender_email = $senderInfo['email_sender_email'];
        }

        if($this->option['email_sendtype'] =='smtp') {
            $mail->Mailer = "smtp";
            $mail->Host = $this->option['email_host'];  // sets GMAIL as the SMTP server
            $mail->Port = $this->option['email_port'];  // set the SMTP port

            if($this->option['email_ssl']) {
                $mail->SMTPSecure = "ssl";  // sets the prefix to the servier  tls,ssl
            }

            $mail->SMTPAuth = true;                      // turn on SMTP authentication
            $mail->Username = $this->option['email_account'];    // SMTP username
            $mail->Password = $this->option['email_password']; // SMTP password

        }

        $mail->Sender = $this->option['email_account'];             // 真正的發件郵箱

        $mail->SetFrom($sender_email, $sender_name, 0);             // 設定發件人資訊

        $mail->CharSet = "UTF-8";                                   // 這裡指定字符集！
        $mail->Encoding = "base64";

        if(is_array($sendto_email)) {
            foreach($sendto_email as $v){
                $mail->AddAddress($v);
            }
        } else {
            $mail->AddAddress($sendto_email);
        }

        //以HTML方式發送
        $mail->IsHTML(true);                // send as HTML
        $mail->Subject = $subject;      // 郵件主題
        $mail->Body = $body;            // 郵件內容
        $mail->AltBody = "text/html";
        $mail->SMTPDebug = false;

        $result = $mail->Send();

        $this->setMessage($mail->ErrorInfo);

        return $result;
    }

    public function setMessage ($message) {
        $this->message = $message;
    }
}
