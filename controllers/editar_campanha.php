<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . '/../models/campanhas.php');
require_once(__DIR__ . '/../models/lists.php');
require_once(__DIR__ . '/../models/publico.php');
require_once(__DIR__ . '/../models/users.php');
require_once(__DIR__ . '/../core/basefunctions.php');
require_once(__DIR__ . '/../helpers/mailer.php');


$modelCampaigns = new Campaigns();
$modelLists = new Lists();
$modelPublico = new Publico();
$modelUsers = new Users();

$mensagem = "";
$mensagem_tipo = "";

$id = $_GET['id'] ?? null;
$campanha = null;
$listas = $modelLists->getAllLists();

if (!$id || !is_numeric($id)) {
    die("Campanha inválida.");
}

$campanha = $modelCampaigns->getCampaignById($id);

if (!$campanha) {
    die("Campanha não encontrada.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao     = $_POST['action'] ?? '';
    file_put_contents("debug_envio_teste.txt", print_r($_POST, true));
    $emails_teste = $_POST['emails_teste'] ?? '';
    $nome     = trim($_POST['nome'] ?? "");
    $assunto  = trim($_POST['assunto'] ?? "");
    $lista_id = $_POST['lista'] ?? null;
    $html     = $_POST['html'] ?? "";
    $estado   = $_POST['estado'] ?? "rascunho";

    if ($acao !== 'enviar_teste' && (!$nome || !$assunto || !$lista_id || !$html)) {
        $mensagem = "Todos os campos são obrigatórios.";
        $mensagem_tipo = "error";
    } else {
        if ($acao === 'gravar') {
            $ok = $modelCampaigns->updateCampaign($id, [
                'nome'     => $nome,
                'assunto'  => $assunto,
                'lista_id' => $lista_id,
                'html'     => $html,
                'estado'   => $estado
            ]);

            $mensagem = $ok ? "Campanha atualizada com sucesso." : "Erro ao atualizar.";
            $mensagem_tipo = $ok ? "success" : "error";
        } elseif ($acao === 'enviar') {
            // Atualiza antes de enviar
            $modelCampaigns->updateCampaign($id, [
                'nome'     => $nome,
                'assunto'  => $assunto,
                'lista_id' => $lista_id,
                'html'     => $html,
                'estado'   => 'enviada'
            ]);

            $emails   = $modelPublico->getAllEmailsByListId($lista_id);
            $sucesso = 0;
            $erro    = 0;

            foreach ($emails as $destinatario) {
                if (send_email($destinatario, $assunto, $html)) {
                    $sucesso++;
                } else {
                    $erro++;
                }
            }
        } elseif ($acao === 'enviar_teste') {
            if (!$nome || !$assunto || !$html) {
                $mensagem = "Para enviar um teste, preencha o nome, assunto e conteúdo da campanha.";
                $mensagem_tipo = "error";
            } elseif (!$emails_teste) {
                $mensagem = "Indique pelo menos um email de teste.";
                $mensagem_tipo = "error";
            } else {
                $emailsArray = array_map('trim', explode(',', $emails_teste));
                $sucesso = 0;
                $erro = 0;

                foreach ($emailsArray as $email) {
                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        if (send_email($email, $assunto . " [TESTE]", $html)) {
                            $sucesso++;
                        } else {
                            $erro++;
                        }
                    } else {
                        $erro++;
                    }
                }

                $mensagem = "Email de teste enviado. Sucesso: $sucesso" . ($erro > 0 ? " | Falhas: $erro" : "");
                $mensagem_tipo = $erro > 0 ? "error" : "success";
            }

            // Atualiza estado após envio
            $modelCampaigns->updateEstado($id, "enviada");

            $mensagem = "Campanha enviada. Sucesso: $sucesso" . ($erro > 0 ? " | Falhas: $erro" : "");
            $mensagem_tipo = $erro > 0 ? "error" : "success";
        } elseif ($acao === 'duplicar') {
            $novo_nome = $nome . " (Cópia)";

            $ok = $modelCampaigns->createCampaign([
                'nome'     => $novo_nome,
                'assunto'  => $assunto,
                'lista_id' => $lista_id,
                'html'     => $html,
                'estado'   => 'rascunho',
            ]);

            if ($ok) {
                $_SESSION['message'] = "Campanha duplicada com sucesso.";
                $_SESSION['message_type'] = "success";
                header("Location: campanhas");
                exit;
            } else {
                $mensagem = "Erro ao duplicar a campanha.";
                $mensagem_tipo = "error";
            }
        }

        $campanha = $modelCampaigns->getCampaignById($id); // Atualiza dados
    }
}

require_once(__DIR__ . '/../views/editar_campanha.php');
