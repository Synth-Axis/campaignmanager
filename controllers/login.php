<?php

require("core/basefunctions.php");
require("core/CSRF.php");

$message = "";
$email = "";

if (!isset($_SESSION["csrf_token"])) {
    generateCSRFToken();
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
        require("models/utilizadores.php");
        $model = new Utilizadores();

        $currentUser = $model->findUtilizadorByEmail($_POST["email"]);

        if (!empty($currentUser)) {
            $userPassword = $currentUser["password"];
            if (password_verify($_POST["password"], $userPassword)) {
                $_SESSION["user_id"] = $currentUser["user_id"];

                if ($currentUser["user_type"] === "admin") {
                    header("Location: /adminheader");
                } else {
                    header("Location: /");
                }
            } else {
                $message = "Password is not valid";
                $email = retainFormData($_POST["email"]);
            }
        } else {
            $message = "Email is not registered";
        }
    } else {
        $message = "Please fill the form correctly!";
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
