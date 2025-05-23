<?php

require("models/publico.php");
require("models/channels.php");
require("models/lists.php");
require("models/managers.php");
require("core/basefunctions.php");
require("core/CSRF.php");

$message = "";
$email = "";
$modelPublico = new Publico();
$modelChannels = new Channels();
$modelLists = new Lists();
$modelManagers = new Managers();
$channels = $modelChannels->getAllChannels();
$listas = $modelLists->getAllLists();
$gestores = $modelManagers->getAllManagers();

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
    $agente = $_POST["agente"] ?? "";
    $gestor = $_POST["gestor"] ?? "";
    $lista = $_POST["lista"] ?? "";
    $canal = $_POST["canal"] ?? "";

    if (
        !empty($_POST["nome"]) &&
        !empty($_POST["email"]) &&
        !empty($_POST["agente"]) &&
        mb_strlen($_POST["nome"]) >= 2 &&
        mb_strlen($_POST["agente"]) >= 2 &&
        filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)
    ){
        $userEmail = $modelPublico->findPublicoByEmail($_POST["email"]);

        if( empty( $userEmail )){
            $modelPublico->RegisterPublico([
                "nome" => $_POST["nome"],
                "email" => $_POST["email"],
                "agente_id" => $_POST["agente"],
                "gestor_id" => $_POST["gestor"],
                "canal_id" => $_POST["canal"],
                "lista_id" => $_POST["lista"],
            ]);
            $_SESSION['message'] = "Contacto registado com sucesso";
            header("Location: login");
            exit;
        }
        $message = "O email já se encontra registado";

    }
    else {
        $message = "Todos os campos são obrigatórios";
        $nome = retainFormData($_POST["nome"]);
        $email = retainFormData($_POST["email"]);
    }

    generateCSRFToken();
}

require ("views/publico.php");