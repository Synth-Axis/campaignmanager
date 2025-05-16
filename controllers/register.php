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

    if (
        !empty($_POST["email"]) &&
        !empty($_POST["password"]) &&
        mb_strlen($_POST["password"]) >= 8 &&
        mb_strlen($_POST["password"]) <= 255 &&
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL) &&
        $_POST["password"] === $_POST["passwordCheck"]
    ){
        $userEmail = $modelUsers->findUserByEmail($_POST["email"]);

        if( empty( $userEmail )){
            $_POST["password"] = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $modelUsers->RegisterUser( $_POST );
            header("Location: login");
        }
        $message = "Email already exists";

    }
    else if($_POST["password"] !== $_POST["passwordCheck"]){
        $message = "Passwords do not match";
        $email = retainFormData($_POST["email"]);
    }
    else {
        $message = "All fields are mandatory";
        $email = retainFormData($_POST["email"]);
    }

    generateCSRFToken();
}

function retainFormData($formData) {
    $formData = htmlspecialchars(strip_tags(trim($formData)));
    return $formData;
}

require ("views/register.php");