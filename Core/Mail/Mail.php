<?php

namespace ES\Core\Mail;

use PHPMailer\PHPMailer\PHPMailer;

require '../vendor/autoload.php';

class Mail
{

    private $_mail;

    public function __construct()
    {


        //Create a new PHPMailer instance
        $this->_mail = new PHPMailer(true);
        // Set PHPMailer to use the sendmail transport
        $this->_mail->isSendmail();
        $this->_mail->CharSet='UTF-8';
        $this->_mail->SMTPDebug = 0;                            // Enable verbose debug output
        $this->_mail->isSMTP();                                 // Set mailer to use SMTP
        $this->_mail->Host = SMTP_HOST;                         // Specify main and backup SMTP servers
        $this->_mail->SMTPAuth = true;                          // Enable SMTP authentication
        $this->_mail->Username = SMTP_USER_MAIL;                // SMTP username
        if(null!==SMTP_USER_PASSWORD) {
            $this->_mail->Password = SMTP_USER_PASSWORD;        // SMTP password
            $this->_mail->SMTPSecure = 'tls';                       // Enable TLS encryption, `ssl` also accepted
        }
        $this->_mail->Port = SMTP_PORT;
        $this->_mail->setFrom(SMTP_USER_MAIL, SMTP_USER_NAME);
    }

    public function send($to,$subject,$body):bool
    {
        try {

            $this->_mail->isHTML(true);                                  // Set email format to HTML
            $this->_mail->addAddress($to);
            $this->_mail->Subject = $subject;
            $this->_mail->Body =$body;
            return $this->_mail->send();
        }
        catch (Exception $e) {
            echo 'Message could not be sent. Mailer Error: ', $this->_mail->ErrorInfo;
            return false;
        }
    }




}