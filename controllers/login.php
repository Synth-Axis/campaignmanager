<?php

require("core/basefunctions.php");
require("core/CSRF.php");
require("models/users.php");
$model = new Users();

$message = "";
$email = "";

if (!isset($_SESSION["csrf_token"])) {
    generateCSRFToken();
}

if (!isset($_SESSION["user_id"]) && isset($_COOKIE["remember_token"])) {
    $currentUser = $model->getUserByRememberToken($_COOKIE["remember_token"]);
    if ($currentUser) {
        $_SESSION["user_id"] = $currentUser["user_id"];
        header("Location: /");
        exit;
    }
}

if (isset($_POST["send"])) {
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("CSRF token validation failed.");
    }

    foreach ($_POST as $key => $value) {
        $_POST[$key] = htmlspecialchars(strip_tags(trim($value)));
    }

    if (
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 255 &&
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)
    ) {

        $currentUser = $model->findUserByEmail($_POST["email"]);

        if (!empty($currentUser)) {
            $userPassword = $currentUser["password"];
            if (password_verify($_POST["password"], $userPassword)) {
                $_SESSION["user_id"] = $currentUser["user_id"];

                if (!empty($_POST["remember"]) && $_POST["remember"] === "on") {
                    $token = bin2hex(random_bytes(32)); // 64 chars
                    $tokenHash = hash('sha256', $token);
                    $expiresAt = date("Y-m-d H:i:s", time() + 60 * 60 * 24 * 30); // 30 dias
                    $model->storeRememberToken($currentUser["user_id"], $tokenHash, $expiresAt);
                    setcookie("remember_token", $token, time() + 60 * 60 * 24 * 30, "/", "", false, true); // HttpOnly
                }
                if ($currentUser["user_type"] === "admin") {
                    header("Location: /");
                } else {
                    header("Location: /");
                }
                exit;
            } else {
                $message = "A password não é válida";
                $email = retainFormData($_POST["email"]);
            }
        } else {
            $message = "O email não está registado";
        }
    } else {
        $message = "Por favor preencha todos os campos!";
        $email = retainFormData($_POST["email"]);
    }
    generateCSRFToken();
}

function retainFormData($formData)
{
    $formData = htmlspecialchars(strip_tags(trim($formData)));
    return $formData;
}

require("views/login.php");
