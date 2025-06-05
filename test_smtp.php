<?php


define("ENV", parse_ini_file(__DIR__ . "/.env"));

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

require_once __DIR__ . '/vendor/autoload.php';

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host       = 'smtp-relay.brevo.com';
    $mail->SMTPAuth   = true;
    $mail->AuthType   = 'LOGIN';
    $mail->Username   = '8ee30b002@smtp-brevo.com'; // <-- substitui aqui
    $mail->Password   = 'GQXKfrgZn7RYN21C';      // <-- substitui aqui
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;

    $mail->setFrom('marketing.comunicacao@realvidaseguros.pt', 'Real Vida');
    $mail->addAddress('alexandre.garcia@realvidaseguros.pt');  // teu email para teste

    $mail->isHTML(true);
    $mail->Subject = 'Teste SMTP com Brevo';
    $mail->Body    = 'Se estás a ler isto, funcionou!';

    $mail->send();
    echo '✅ Email enviado!';
} catch (Exception $e) {
    echo "❌ Erro: {$mail->ErrorInfo}";
}
