<?php
require("core/basefunctions.php");
require("core/CSRF.php");
require_once(__DIR__ . '/../models/user.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf_token'] ?? '';

    if ($csrf !== $_SESSION['csrf_token']) {
        die("CSRF inválido.");
    }

    if (strlen($password) < 8) {
        die("Password demasiado curta.");
    }

    $model = new Users();
    $user = $model->findByToken($token);

    if (!$user) {
        die("Token inválido ou expirado.");
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);
    $model->updatePassword($user['gestor_id'], $hash);

    header("Location: /login?redefinido=1");
    exit;
}

require __DIR__ . '/../views/redefinir_password.php';
