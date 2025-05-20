<?php

require("models/users.php");
require("core/basefunctions.php");
require("core/CSRF.php");

$message = "";
$code = "";
$email = "";
$modelUsers = new Users();

if (!isset($_SESSION["csrf_token"])) {
    generateCSRFToken();
}

if (isset($_POST["send"])){
    if (!isset($_POST["csrf_token"]) || $_POST["csrf_token"] !== $_SESSION["csrf_token"]) {
        die("CSRF token validation failed.");
    }

    foreach($_POST as $key => $value){
        $_POST[ $key ] = htmlspecialchars(strip_tags(trim($value)));
    }

    $nome = $_POST["nome"] ?? "";
    $email = $_POST["email"] ?? "";


    if (
        !empty($_POST["nome"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        !empty($_POST["passwordCheck"]) &&
        mb_strlen($_POST["nome"]) >= 2 &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 255 &&
        mb_strlen($_POST["passwordCheck"]) >= 8 &&
        mb_strlen($_POST["passwordCheck"]) <= 255 &&
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
        $_POST["password"] === $_POST["passwordCheck"]
    ){
        $userEmail = $modelUsers->findUserByEmail($_POST["email"]);

        if( empty( $userEmail )){
            $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $modelUsers->RegisterUser( $_POST );
            header("Location: login");
        }
        $message = "O email já se encontra registado";

    }
    else if($_POST["password"] !== $_POST["passwordCheck"]){
        $nome = retainFormData($_POST["nome"]);
        $message = "As passwords não são iguais";
        $email = retainFormData($_POST["email"]);
    }
    else {
        $message = "Todos os campos são obrigatórios";
        $nome = retainFormData($_POST["nome"]);
        $email = retainFormData($_POST["email"]);
    }

    generateCSRFToken();
}

function retainFormData($formData) {
    $formData = htmlspecialchars(strip_tags(trim($formData)));
    return $formData;
}

require ("views/register.php");