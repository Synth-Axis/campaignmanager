<?php

use PHPMailer\PHPMailer\PHPMailer;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class CampanhasEmailController
{
    public function index(): void
    {
        $modelCampaigns = new CampanhasEmail();
        $modelLists = new Lists();
        $modelPublico = new Publico();
        $mensagem = "";
        $mensagem_tipo = "";

        $currentUser = current_user();

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
                header("Location: /campanhas");
                exit;
            }

            if ($acao === 'gravar') {
                $ok = $modelCampaigns->createCampaign([
                    'nome'     => $nome,
                    'assunto'  => $assunto,
                    'lista_id' => $lista_id,
                    'html'     => $html,
                    'estado'   => 'rascunho',
                ]);

                $_SESSION['message'] = $ok ? "Campanha guardada como rascunho." : "Erro ao guardar campanha.";
                $_SESSION['message_type'] = $ok ? "success" : "error";
                header("Location: /campanhas");
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

                header("Location: /campanhas");
                exit;
            }
        }

        require view_path('campanhas-email/index.php');
    }

    public function editar(): void
    {
        $modelCampaigns = new CampanhasEmail();
        $modelLists = new Lists();
        $modelPublico = new Publico();

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
            $acao = $_POST['action'] ?? '';
            $emails_teste = $_POST['emails_teste'] ?? '';
            $nome     = trim($_POST['nome'] ?? "");
            $assunto  = trim($_POST['assunto'] ?? "");
            $lista_id = $_POST['lista'] ?? null;
            $html     = $_POST['html'] ?? "";
            $html     = preg_replace('/^\xEF\xBB\xBF/', '', $html);
            $html     = html_entity_decode($html, ENT_QUOTES | ENT_HTML5, 'UTF-8');

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
                } elseif ($acao === 'enviar' || $acao === 'enviar_teste') {
                    $emails = [];

                    if ($acao === 'enviar') {
                        $modelCampaigns->updateCampaign($id, [
                            'nome'     => $nome,
                            'assunto'  => $assunto,
                            'lista_id' => $lista_id,
                            'html'     => $html,
                            'estado'   => 'enviada'
                        ]);

                        $emails = $modelPublico->getAllEmailsByListId($lista_id);
                    } else {
                        if (!$nome || !$assunto || !$html) {
                            $mensagem = "Para enviar um teste, preencha o nome, assunto e conteúdo da campanha.";
                            $mensagem_tipo = "error";
                        } elseif (!$emails_teste) {
                            $mensagem = "Indique pelo menos um email de teste.";
                            $mensagem_tipo = "error";
                        } else {
                            $emailsArray = array_map('trim', explode(',', $emails_teste));
                            $emailsInvalidos = [];
                            $emails = [];

                            foreach ($emailsArray as $em) {
                                $publico = $modelPublico->findPublicoByEmail($em);
                                if ($publico) {
                                    $emails[] = [
                                        'email' => $publico['email'],
                                        'nome'  => $publico['nome'] ?? 'Destinatário'
                                    ];
                                } else {
                                    $emailsInvalidos[] = $em;
                                }
                            }

                            if (!empty($emailsInvalidos)) {
                                $mensagem = "Os seguintes emails de teste não existem na base de dados: " .
                                    htmlspecialchars(implode(', ', $emailsInvalidos), ENT_QUOTES, 'UTF-8');
                                $mensagem_tipo = "error";
                            }

                            if (!empty($emails)) {
                                $modelCampaigns->updateEstado($id, "enviada");
                            }
                        }
                    }

                    $sucesso = 0;
                    $erro = 0;

                    if (!empty($emails)) {
                        foreach ($emails as $destinatario) {
                            $email = $destinatario['email'];
                            $nomeDest = $destinatario['nome'] ?? '';

                            $publico = $modelPublico->findPublicoByEmail($email);
                            if (!$publico) {
                                continue;
                            }

                            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
                            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? null;
                            $modelCampaigns->registarEnvioTracking($id, $publico['publico_id'], $ip, $userAgent);

                            $trackingId = $modelCampaigns->getLastTrackingId();

                            $qr = Builder::create()
                                ->writer(new PngWriter())
                                ->data("https://lynx.com/eventos/admissaoConviteEvento?listid=88&contact=" . urlencode($email))
                                ->encoding(new Encoding('UTF-8'))
                                ->size(400)
                                ->margin(10)
                                ->build();

                            $imageBinary = $qr->getString();
                            $modelPublico->updateQrCodeByEmail($email, 'data:image/png;base64,' . base64_encode($imageBinary));

                            // Email setup
                            $mail = EmailService::createMailer();

                            try {
                                $mail->addAddress($email);
                                $mail->isHTML(true);
                                $mail->Subject = $assunto;

                                $mail->addStringEmbeddedImage($imageBinary, 'qrcodeCid', 'qrcode.png', 'base64', 'image/png');

                                $clickBase = "https://lynx.com/eventos/responderConviteEvento?listid=88&contact=$email&resposta=Não";
                                $clickTrack = ENV['ADDRESS'] . "/track/click.php?tid=$trackingId&cid=$id&pid={$publico['publico_id']}&url=" . urlencode($clickBase);

                                $htmlPersonalizado = str_replace(
                                    ['{click_url}', '{qr_code}', '{email}', '{firstname}', '{cid}', '{tracking_id}', '{publico_id}'],
                                    [
                                        $clickTrack,
                                        '<img src="cid:qrcodeCid" width="300" height="300" style="display:block; margin:auto;" />',
                                        htmlspecialchars($email),
                                        htmlspecialchars($nomeDest),
                                        $id,
                                        $trackingId,
                                        $publico['publico_id']
                                    ],
                                    $html
                                );
                                $mail->Body = $htmlPersonalizado;
                                $mail->send();

                                $sucesso++;
                            } catch (Exception $e) {
                                error_log("Erro ao enviar email para $email: " . $mail->ErrorInfo);
                                $erro++;
                            }
                        }

                        $mensagem = "Campanha enviada. Sucesso: $sucesso" . ($erro > 0 ? " | Falhas: $erro" : "");
                        $mensagem_tipo = $erro > 0 ? "error" : "success";
                    }
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
                        header("Location: /campanhas");
                        exit;
                    } else {
                        $mensagem = "Erro ao duplicar a campanha.";
                        $mensagem_tipo = "error";
                    }
                }

                $campanha = $modelCampaigns->getCampaignById($id);
            }
        }

        require view_path('campanhas-email/editar.php');
    }
}
