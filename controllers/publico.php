<?php

/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug_post.txt', date('Y-m-d H:i:s') . "\n" . print_r($_POST, true), FILE_APPEND); */

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log(print_r($_POST, true));
}

require("models/users.php");
require("models/publico.php");
require("models/channels.php");
require("models/lists.php");
require("models/managers.php");
require("core/basefunctions.php");
require("core/CSRF.php");

$model = new Users();
$modelPublico = new Publico();
$modelChannels = new Channels();
$modelLists = new Lists();
$modelManagers = new Managers();


$totalContactos = $modelPublico->contarPublico();
$contactosUltimos30Dias = $modelPublico->contarNovosUltimos30Dias();
$novosHoje = $modelPublico->contarNovosHoje();
$dadosGrafico = $modelPublico->getCrescimentoPorDia(30);

if (isset($_SESSION["user_id"])) {
    $currentUser = $model->findUserById($_SESSION["user_id"]);
}

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
    $gestor = $_POST["gestor"] !== "" ? (int)$_POST["gestor"] : null;
    $lista  = $_POST["lista"]  !== "" ? (int)$_POST["lista"]  : null;
    $canal  = $_POST["canal"]  !== "" ? (int)$_POST["canal"]  : null;

    if (!empty($nome) && !empty($email) && mb_strlen($nome) >= 2 && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        if (empty($modelPublico->findPublicoByEmail($email))) {
            $modelPublico->RegisterPublico([
                "nome"      => $nome,
                "email"     => $email,
                "gestor_id" => $gestor,
                "canal_id"  => $canal,
                "lista_id"  => $lista,
            ]);
            $_SESSION['message'] = "Contacto registado com sucesso";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "O email já se encontra registado";
            $_SESSION['message_type'] = "error";
        }
    } else {
        $_SESSION['message'] = "Todos os campos são obrigatórios";
        $_SESSION['message_type'] = "error";
    }

    header("Location: publico");
    exit;
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'apagar_contacto') {
    $contactoId = $_POST['contacto_id'] ?? null;
    if ($contactoId) {
        $modelPublico->apagarPublico($contactoId);
        header("Location: publico");
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'editar_contacto') {

    $dados = [
        'publico_id' => $_POST['contacto_id'] ?? null,
        'nome' => $_POST['nome'] ?? '',
        'email' => $_POST['email'] ?? '',
        'gestor_id' => $_POST['gestor'] ?? null,
        'canal_id' => $_POST['canal'] ?? null,
        'lista_id' => $_POST['lista'] ?? null,
    ];

    try {
        $ok = $modelPublico->updatePublico($dados);
        echo json_encode(['success' => $ok]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'erro' => $e->getMessage()]);
    }
    exit;
}

if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    require("views/publico.php");
    exit;
}
