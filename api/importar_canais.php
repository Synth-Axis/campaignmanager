<?php
require_once("../models/channels.php");
require_once("../core/dbconfig.php");

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
