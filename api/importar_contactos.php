<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../models/publico.php");
require_once("../core/dbconfig.php");

// Permitir apenas POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['erro' => 'Método não permitido']);
    exit;
}

// Verificar Content-Type
$contentType = $_SERVER["CONTENT_TYPE"] ?? '';
if (stripos($contentType, 'application/json') === false) {
    http_response_code(400);
    echo json_encode(['erro' => 'Content-Type deve ser application/json']);
    exit;
}

// Ler e decodificar JSON
$input = json_decode(file_get_contents("php://input"), true);
if (!is_array($input)) {
    http_response_code(400);
    echo json_encode(['erro' => 'JSON inválido']);
    exit;
}

$modelPublico = new Publico();
$resultados = [];

foreach ($input as $item) {
    $nome = trim($item['nome'] ?? '');
    $email = trim($item['email'] ?? '');
    $agente = $item['agente_id'] ?? null;
    $gestor = $item['gestor_id'] ?? null;
    $lista = $item['lista_id'] ?? null;
    $canal = $item['canal_id'] ?? null;

    if (
        mb_strlen($nome) < 2 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) ||
        empty($agente)
    ) {
        $resultados[] = [
            'email' => $email,
            'sucesso' => false,
            'mensagem' => 'Dados inválidos ou incompletos'
        ];
        continue;
    }

    // Verificar duplicado
    if ($modelPublico->findPublicoByEmail($email)) {
        $resultados[] = [
            'email' => $email,
            'sucesso' => false,
            'mensagem' => 'Email já registado'
        ];
        continue;
    }

    // Registar contacto
    $modelPublico->RegisterPublico([
        'nome' => $nome,
        'email' => $email,
        'agente_id' => $agente,
        'gestor_id' => $gestor,
        'lista_id' => $lista,
        'canal_id' => $canal,
    ]);

    $resultados[] = [
        'email' => $email,
        'sucesso' => true,
        'mensagem' => 'Registado com sucesso'
    ];
}

header('Content-Type: application/json');
echo json_encode(['resultados' => $resultados]);
exit;
