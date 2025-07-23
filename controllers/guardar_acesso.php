<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../models/acessos.php';

$chave = 'chave-super-secreta';

function encriptarSenha($senha, $chave)
{
    if (empty($senha)) return null;
    $iv = openssl_random_pseudo_bytes(16);
    $cifrado = openssl_encrypt($senha, 'aes-256-cbc', $chave, 0, $iv);
    return base64_encode($iv . $cifrado);
}

$dados = [
    'nome_servico' => $_POST['nome_servico'] ?? null,
    'url_acesso' => $_POST['url_acesso'] ?? null,
    'username' => $_POST['username'] ?? null,
    'senha_criptografada' => encriptarSenha($_POST['senha'] ?? '', $chave),
    'notas' => $_POST['notas'] ?? null,
];


if (!isset($db)) {
    require_once __DIR__ . '/../models/dbconfig.php';
}

$model = new Acesso($db);

if (!empty($_POST['id'])) {
    $id = (int) $_POST['id'];
    $ok = $model->atualizar($id, $dados);
} else {
    $ok = $model->inserir($dados);
}


ob_clean();
header('Content-Type: application/json');
echo json_encode(['sucesso' => $ok]);
exit;
