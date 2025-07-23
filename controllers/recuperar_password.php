<?php

require("core/basefunctions.php");
require("core/CSRF.php");
require("models/users.php");
$userModel = new Users();

require_once(__DIR__ . '/../helpers/mailer.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $csrf = $_POST['csrf_token'];

    if ($csrf !== $_SESSION['csrf_token']) {
        $message = "Token CSRF inválido.";
        return;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Email inválido.";
        return;
    }

    $user = $userModel->findUserByEmail($email);

    if (!$user) {
        $message = "Email não encontrado.";
        return;
    }

    $token = bin2hex(random_bytes(32));
    $userModel->storeResetToken($user['user_id'], $token);

    $resetLink = ENV["ADDRESS"] . "/redefinir_password?token=$token";
    $subject = "Recuperação de Palavra-passe";
    $body = "Clique neste link para redefinir a sua palavra-passe:<br><a href='$resetLink'>$resetLink</a>";

    send_email($email, $subject, $body);

    $message = "Foi enviado um e-mail com o link de recuperação.";
}

require("views/recuperar_password.php");
