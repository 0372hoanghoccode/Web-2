<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/phpmailer/src/Exception.php';
require 'phpmailer/phpmailer/src/PHPMailer.php';
require 'phpmailer/phpmailer/src/SMTP.php';

class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        try {
            $this->mail->isSMTP();                               
            $this->mail->Host = 'smtp.gmail.com';   
            $this->mail->SMTPAuth = true;   
            $this->mail->Username = 'fahasa.bookstore.official@gmail.com';
            $this->mail->Password = 'nugp clfs ikkq gyiv';
            $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $this->mail->Port = 587;

            $this->mail->setFrom('fahasa.bookstore.official@gmail.com', 'Fahasa Bookstore');
        } catch (Exception $e) {
            echo "Mailer Error: " . $this->mail->ErrorInfo;
        }
    }

    public function sendMail($to, $subject, $body)
    {
        try {
            $this->mail->addAddress($to);

            $this->mail->isHTML(true);                         
            $this->mail->Subject = $subject;   
            $this->mail->Body    = $body; 

            $this->mail->send();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}