<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../log_erros.txt'); // Caminho absoluto
error_reporting(E_ALL);

// Log para confirmar início do script
error_log("Início do script importar_gestores.php");

require_once(__DIR__ . '/../core/coreconfig.php');
require_once(__DIR__ . "/../models/managers.php");

header('Content-Type: application/json');

// Validar método
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["erro" => "Método não permitido"]);
    error_log("Método não permitido: " . $_SERVER["REQUEST_METHOD"]);
    exit;
}

// Validar Content-Type
$contentType = $_SERVER["CONTENT_TYPE"] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(400);
    echo json_encode(["erro" => "Content-Type deve ser application/json"]);
    error_log("Content-Type inválido: " . $contentType);
    exit;
}

// Ler e validar JSON
$rawInput = file_get_contents("php://input");
$input = json_decode($rawInput, true);

if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(["erro" => "JSON inválido"]);
    error_log("JSON inválido: " . $rawInput);
    exit;
}

// Instanciar modelo
try {
    $modelGestor = new Managers();
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro ao ligar à base de dados"]);
    error_log("Erro PDO: " . $e->getMessage());
    exit;
}

// Processar inserções
$resultados = [];

foreach ($input as $index => $item) {
    $nome = trim($item["gestor_nome"] ?? '');
    $canal_id = $item["canal_id"] ?? null;

    if ($nome !== '' && is_numeric($canal_id)) {
        try {
            $id = $modelGestor->criarGestor($nome, $canal_id);
            $resultados[] = [
                "gestor_id" => $id,
                "gestor_nome" => $nome,
                "canal_id" => $canal_id
            ];
        } catch (Exception $e) {
            http_response_code(500);
            error_log("Erro ao inserir gestor na posição $index: " . $e->getMessage());
            echo json_encode(["erro" => "Erro ao inserir gestor '$nome'"]);
            exit;
        }
    } else {
        error_log("Dados inválidos na posição $index: " . json_encode($item));
    }
}

echo json_encode($resultados);
