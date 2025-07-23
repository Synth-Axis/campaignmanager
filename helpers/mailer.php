<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once(__DIR__ . '/../vendor/autoload.php');

function send_email($to, $subject, $body)
{
    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';

    try {
        $mail->isSMTP();
        $mail->Host       = ENV["PHPMAILER_HOST"];
        $mail->SMTPAuth   = true;
        $mail->AuthType   = 'LOGIN';
        $mail->Username   = ENV["PHPMAILER_USERNAME"];
        $mail->Password   = ENV["PHPMAILER_PASSWORD"];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = ENV["PHPMAILER_PORT"];

        $mail->setFrom('marketing.comunicacao@realvidaseguros.pt', 'Real Vida Seguros, SA');
        $mail->addAddress($to);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body    = $body;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar email: {$mail->ErrorInfo}");
        return false;
    }
}
