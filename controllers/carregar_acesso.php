<?php

require_once __DIR__ . '/../models/acessos.php';

// chave para desencriptação
$chave = 'chave-super-secreta';

function decifrarSenha($dados, $chave)
{
    if (!$dados) return '';
    $dados = base64_decode($dados);
    $iv = substr($dados, 0, 16);
    $cifrado = substr($dados, 16);
    return openssl_decrypt($cifrado, 'aes-256-cbc', $chave, 0, $iv);
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'ID inválido']);
    exit;
}

$id = (int) $_GET['id'];
$model = new Acesso($db);
$acesso = $model->procurarPorId($id);

if (!$acesso) {
    http_response_code(404);
    echo json_encode(['erro' => 'Registo não encontrado']);
    exit;
}

$acesso['senha'] = decifrarSenha($acesso['senha_criptografada'], $chave);
unset($acesso['senha_criptografada']);

header('Content-Type: application/json');
echo json_encode([
    'id' => $acesso['id'],
    'nome_servico' => $acesso['nome_servico'],
    'url_acesso' => $acesso['url_acesso'],
    'username' => $acesso['username'],
    'senha' => $acesso['senha'],
    'notas' => $acesso['notas']
]);
