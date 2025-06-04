<?php
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/../log_erros.txt'); // Caminho absoluto
error_reporting(E_ALL);

// Log para confirmar início do script
error_log("Início do script importar_gestores.php");

require_once(__DIR__ . '/../core/coreconfig.php');
require_once(__DIR__ . '/../models/publico.php');


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
    $gestor = $item['gestor_id'] ?? null;
    $lista = $item['listas_id'] ?? null;
    $canal = $item['canal_id'] ?? null;

    if (
        mb_strlen($nome) < 2 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
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
        'gestor_id' => $gestor,
        'canal_id' => $canal,
        'lista_id' => $lista,

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
