
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("models/campanhas.php");
require("models/lists.php");
require("models/publico.php");
require("models/users.php");
require("core/basefunctions.php");
require_once(__DIR__ . '/../helpers/mailer.php');

$modelCampaigns = new Campaigns();
$modelLists = new Lists();
$modelPublico = new Publico();
$modelUsers = new Users();     // Instancia o model de contactos
$mensagem = "";
$mensagem_tipo = "";

$currentUser = null;
if (isset($_SESSION["user_id"])) {
    $currentUser = $modelUsers->findUserById($_SESSION["user_id"]);
}

$listas = $modelLists->getAllLists();
$campanhas = $modelCampaigns->getAllCampaigns();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Valida e guarda a nova campanha
    $nome = trim($_POST['nome'] ?? "");
    $assunto = trim($_POST['assunto'] ?? "");
    $lista_id = $_POST['lista'] ?? null;
    $html = $_POST['html'] ?? "";

    if ($nome && $assunto && $lista_id && $html) {
        // Guarda primeiro a campanha
        $ok = $modelCampaigns->createCampaign([
            'nome' => $nome,
            'assunto' => $assunto,
            'lista_id' => $lista_id,
            'html' => $html,
        ]);
        if ($ok) {
            // Envia os emails para todos os contactos da lista
            $emails = $modelPublico->getAllEmailsByListId($lista_id);
            $total = count($emails);
            $sucesso = 0;
            $erro = 0;

            foreach ($emails as $destinatario) {
                if (send_email($destinatario, $assunto, $html)) {
                    $sucesso++;
                } else {
                    $erro++;
                }
            }

            // (Opcional) podes guardar as estatísticas de envio na tabela campanhas

            $mensagem = "Campanha criada e enviada com sucesso! Emails enviados: $sucesso";
            if ($erro > 0) $mensagem .= " | Falhas: $erro";
            $mensagem_tipo = $erro > 0 ? "error" : "success";
            header("Location: campanhas"); // reload clean
            exit;
        } else {
            $mensagem = "Erro ao guardar a campanha.";
            $mensagem_tipo = "error";
        }
    } else {
        $mensagem = "Todos os campos são obrigatórios.";
        $mensagem_tipo = "error";
    }
}

require("views/campanhas.php");
