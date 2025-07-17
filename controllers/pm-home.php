<?php
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once __DIR__ . '/../models/acessos.php';

$acessoModel = new Acesso();
$mensagem = null;

// Chave para cifragem (pode vir de sessão ou .env num projeto real)
$chave = 'chave-super-secreta';

// Funções de cifragem
function cifrarSenha($senha, $chave)
{
    $iv = random_bytes(16);
    $cifrado = openssl_encrypt($senha, 'aes-256-cbc', $chave, 0, $iv);
    return base64_encode($iv . $cifrado);
}

function decifrarSenha($dados, $chave)
{
    $dados = base64_decode($dados);
    $iv = substr($dados, 0, 16);
    $cifrado = substr($dados, 16);
    return openssl_decrypt($cifrado, 'aes-256-cbc', $chave, 0, $iv);
}

// ⏺️ INSERIR OU ATUALIZAR
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'nome_servico' => $_POST['nome_servico'],
        'url_acesso' => $_POST['url_acesso'],
        'username' => $_POST['username'],
        'email' => $_POST['email'],
        'senha_criptografada' => cifrarSenha($_POST['senha'], $chave),
        'notas' => $_POST['notas']
    ];

    if (!empty($_POST['id'])) {
        $acessoModel->atualizar($_POST['id'], $dados);
        $mensagem = "Acesso atualizado com sucesso.";
    } else {
        $acessoModel->inserir($dados);
        $mensagem = "Acesso inserido com sucesso.";
    }

    // Evitar reenvio em refresh
    header("Location: pm-home.php?msg=" . urlencode($mensagem));
    exit;
}

// ⏹️ APAGAR (opcional via GET)
if (isset($_GET['apagar'])) {
    $id = (int) $_GET['apagar'];
    $acessoModel->apagar($id);
    header("Location: pm-home.php?msg=" . urlencode("Acesso apagado."));
    exit;
}

// 📄 LISTAR TUDO
$acessos = $acessoModel->listarTodos();

require_once __DIR__ . '/../views/pm-home.php';
