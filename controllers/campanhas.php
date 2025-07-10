
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

$totalEntregues = $modelCampaigns->getTotalEntregues();
$totalAberturas = $modelCampaigns->getTotalAberturas();
$totalCliques   = $modelCampaigns->getTotalCliques();

$listas = $modelLists->getAllLists();
$campanhas = $modelCampaigns->getAllCampaigns();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao     = $_POST['action'] ?? '';
    $nome     = trim($_POST['nome'] ?? "");
    $assunto  = trim($_POST['assunto'] ?? "");
    $lista_id = $_POST['lista'] ?? null;
    $html     = $_POST['html'] ?? "";

    if (!$nome || !$assunto || !$lista_id || !$html) {
        $_SESSION['message'] = "Todos os campos são obrigatórios.";
        $_SESSION['message_type'] = "error";
        header("Location: campanhas");
        exit;
    }

    if ($acao === 'gravar') {
        // Só guarda com estado "rascunho"
        $ok = $modelCampaigns->createCampaign([
            'nome'     => $nome,
            'assunto'  => $assunto,
            'lista_id' => $lista_id,
            'html'     => $html,
            'estado'   => 'rascunho',
        ]);

        $_SESSION['message'] = $ok ? "Campanha guardada como rascunho." : "Erro ao guardar campanha.";
        $_SESSION['message_type'] = $ok ? "success" : "error";
        header("Location: campanhas");
        exit;
    } elseif ($acao === 'enviar') {
        // Guarda com estado "enviada" e envia emails
        $ok = $modelCampaigns->createCampaign([
            'nome'     => $nome,
            'assunto'  => $assunto,
            'lista_id' => $lista_id,
            'html'     => $html,
            'estado'   => 'enviada',
        ]);

        if ($ok) {
            $emails  = $modelPublico->getAllEmailsByListId($lista_id);
            $sucesso = 0;
            $erro    = 0;

            foreach ($emails as $destinatario) {
                if (send_email($destinatario, $assunto, $html)) {
                    $sucesso++;
                } else {
                    $erro++;
                }
            }

            $_SESSION['message'] = "Campanha enviada. Sucesso: $sucesso" . ($erro > 0 ? " | Falhas: $erro" : "");
            $_SESSION['message_type'] = $erro > 0 ? "error" : "success";
        } else {
            $_SESSION['message'] = "Erro ao guardar e enviar a campanha.";
            $_SESSION['message_type'] = "error";
        }

        header("Location: campanhas");
        exit;
    }
}

require("views/campanhas.php");
