<?php

if (!defined('SYSPATH'))
    exit("No direct script access allowed!");

class CL_Mail {

    private static $instance = NULL;
    
    /**
     * @var PHPMailer
     */
    private $mail = NULL;
    
    private function __construct($mail) {
        $this->mail = $mail;
    }

    /**
     * @return CL_Mail
     */
    public static function get_instance() {
        if (self::$instance === NULL) {
            self::$instance = new CL_Mail(self::establish());
        }
        return self::$instance;
    }
    
    private static function establish() {
        require_once BASEPATH . 'application/libraries/phpmailer/class.phpmailer.php';
        $config = CL_Config::get_instance();
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Mailer = $config->get_value('mail', 'mailer');
        $mail->Host = $config->get_value('mail', 'host');
        $mail->Port = $config->get_value('mail', 'port');
        $mail->SMTPAuth = $config->get_value('mail', 'smtpauth');
        $mail->Username = $config->get_value('mail', 'username');
        $mail->Password = $config->get_value('mail', 'password');
        return $mail;
    }
    
    public function add_address($email) {
        $this->mail->AddAddress($email);
    }
    
    public function clear_addresses() {
        $this->mail->ClearAddresses();
    }
    
    public function send($subject, $body, $from, $fromName) {
        $this->mail->From = $from;
        $this->mail->FromName = $fromName;
        $this->mail->Subject = $subject;
        $this->mail->Body = $body;
        return $this->mail->Send();
    }
    
    public function get_error() {
        return $this->mail->ErrorInfo;
    }
    
    public function add_pdf_attachement($string, $filename) {
        $this->mail->AddStringAttachment($string, $filename, 'base64', 'application/pdf');
    }
}

/* End of file CL_Mail.php */
/* Location: /system/libraries/CL_Mail.php */