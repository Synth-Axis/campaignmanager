<?php

require("models/users.php");
$model = new Users();

if (isset($_SESSION["user_id"])) {
    $currentUser = $model->findUserById($_SESSION["user_id"]);
}

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

$contactos = $modelPublico->getAllPublico();

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
    ) {
        $userEmail = $modelPublico->findPublicoByEmail($_POST["email"]);

        if (empty($userEmail)) {
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
    } else {
        $message = "Todos os campos são obrigatórios";
        $nome = retainFormData($_POST["nome"]);
        $email = retainFormData($_POST["email"]);
    }

    generateCSRFToken();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'nova_lista') {
    $nomeLista = trim($_POST['nova_lista_nome']);
    if (!empty($nomeLista)) {
        $id = $modelLists->criarLista($nomeLista);

        if (
            isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
        ) {
            header('Content-Type: application/json');
            echo json_encode(['id' => $id, 'nome' => $nomeLista]);
            exit;
        }

        header("Location: publico");
        exit;
    }

    if (
        isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest'
    ) {
        http_response_code(400);
        header('Content-Type: application/json');
        echo json_encode(['erro' => 'Nome da lista não pode estar vazio']);
        exit;
    }
}

// Apenas inclui a view se não for um pedido AJAX
if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    require("views/publico.php");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'editar_lista') {
    $listaId = $_POST['lista_id'] ?? null;
    $novoNome = trim($_POST['lista_nome'] ?? '');

    if ($listaId && $novoNome !== '') {
        $modelLists->atualizarNomeLista($listaId, $novoNome);
        header("Location: publico");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'apagar_lista') {
    $listaId = $_POST['lista_id'] ?? null;
    if ($listaId) {
        $modelLists->apagarLista($listaId);
        header("Location: publico");
        exit;
    }
}
