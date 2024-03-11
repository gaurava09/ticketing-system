<?php
class Phpmailer_library
{
    public function __construct()
    {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    /*
    //phpmailer v5.2
    public function load()
    {
        require_once(APPPATH."third_party/PHPMailer/PHPMailerAutoload.php");
        $objMail = new PHPMailer;
        return $objMail;
    }*/

    //phpmailer v5.2
    public function load()
    {
        require_once(APPPATH.'third_party/PHPMailer-6.2/src/PHPMailer.php');
        require_once(APPPATH.'third_party/PHPMailer-6.2/src/SMTP.php');
        require_once(APPPATH.'third_party/PHPMailer-6.2/src/Exception.php');

        $objMail = new PHPMailer\PHPMailer\PHPMailer();
        return $objMail;
    }

}

?>