<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Phpmailer_lib {

    function __construct() {
        log_message('Debug', 'PHPMailer class is loaded.');
    }

    function send_email($subject = "Email subject", $message = "Email message", $recipient = array()) {
        require_once APPPATH . 'third_party/phpmailer/src/Exception.php';
        require_once APPPATH . 'third_party/phpmailer/src/PHPMailer.php';
        require_once APPPATH . 'third_party/phpmailer/src/SMTP.php';

        //is valid email?
        $rcp = "";
        foreach ($recipient as $val) {
            if (filter_var($val[0], FILTER_VALIDATE_EMAIL)) {
                $rcp .= $val[0] . " - " . $val[1] . ",";
            }
        }

        //no valid email
        if ($rcp == "") {
            return false;
        }

        $mail = new PHPMailer(true);
        try {
            /* Server settings */
            $mail->SMTPDebug = 0;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'Username';                         // SMTP username
            $mail->Password = 'Password';                         // SMTP password
            $mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
            $mail->Port = 587;                                    // TCP port to connect to
            $mail->setFrom('admin@email.com', 'Admin');

            /* Add a recipient */
            foreach ($recipient as $val) {
                if (filter_var($val[0], FILTER_VALIDATE_EMAIL)) {
                    $mail->addAddress($val[0], $val[1]);
                }
            }

            /* template email */
            $template = <<<EOD
<html>
    <head>
        <title>title</title>
        <style>
            body {
                font-family: Tahoma, Geneva, sans-serif;
            }
        </style>
    </head>	
    <body style="background-color:#F0EEEE;">
        {$message}
    </body>
</html>
EOD;

            /* Content */
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body = $template;

            $mail->send();
            echo 'Message has been sent';
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }

}
