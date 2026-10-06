<?php

use PHPMailer\PHPMailer\PHPMailer;

class EmailService
{
    public static function createMailer(): PHPMailer
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = ENV['PHPMAILER_HOST'];
        $mail->SMTPAuth = true;
        $mail->AuthType = 'LOGIN';
        $mail->Username = ENV['PHPMAILER_USERNAME'];
        $mail->Password = ENV['PHPMAILER_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = ENV['PHPMAILER_PORT'];
        $mail->setFrom(ENV['PHPMAILER_FROM_EMAIL'], ENV['PHPMAILER_FROM_NAME']);
        return $mail;
    }

    public function send(string $to, string $subject, string $body): bool
    {
        try {
            $mail = self::createMailer();
            $mail->addAddress($to);
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $body;
            return $mail->send();
        } catch (Exception $e) {
            error_log('Erro ao enviar email: ' . $e->getMessage());
            return false;
        }
    }
}
