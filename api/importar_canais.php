<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../log_erros.txt'); // Caminho absoluto
error_reporting(E_ALL);

// Log para confirmar início do script
error_log("Início do script importar_gestores.php");

require_once(__DIR__ . '/../core/coreconfig.php');
require_once(__DIR__ . '/../models/channels.php');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    exit;
}

$contentType = $_SERVER["CONTENT_TYPE"] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(400);
    echo json_encode(["erro" => "Content-Type deve ser application/json"]);
    exit;
}

$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(["erro" => "JSON inválido"]);
    exit;
}

$modelCanal = new Channels();
$resultados = [];

foreach ($input as $item) {
    $nome = trim($item["nome"] ?? '');
    if ($nome !== '') {
        $id = $modelCanal->criarCanal($nome);
        $resultados[] = ["nome" => $nome, "canal_id" => $id];
    }
}

echo json_encode($resultados);
