<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

class TrackingController
{
    public function click(): void
    {
        $base = new Database();
        $db = $base->db;

        $tracking_id = $_GET['tid'] ?? null;
        $campanha_id = $_GET['cid'] ?? null;
        $publico_id = $_GET['pid'] ?? null;
        $url = $_GET['url'] ?? null;

        if (
            is_numeric($tracking_id) &&
            is_numeric($campanha_id) &&
            is_numeric($publico_id) &&
            filter_var($url, FILTER_VALIDATE_URL)
        ) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $stmt = $db->prepare("
                UPDATE tracking_campanha
                SET clicado_em = NOW(), link_clicado = ?, ip = ?, user_agent = ?
                WHERE tracking_id = ? AND campanha_id = ? AND publico_id = ?
            ");
            $stmt->execute([$url, $ip, $ua, $tracking_id, $campanha_id, $publico_id]);

            header("Location: " . $url);
            exit;
        }

        header("Location: https://lynx.com");
        exit;
    }

    public function open(): void
    {
        $base = new Database();
        $db = $base->db;

        $tracking_id = $_GET['tid'] ?? null;
        $campanha_id = $_GET['cid'] ?? null;
        $publico_id = $_GET['pid'] ?? null;

        if (is_numeric($tracking_id) && is_numeric($campanha_id) && is_numeric($publico_id)) {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '';
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

            $stmt = $db->prepare("
                UPDATE tracking_campanha
                SET aberto_em = NOW(), ip = ?, user_agent = ?
                WHERE tracking_id = ? AND campanha_id = ? AND publico_id = ?
            ");
            $stmt->execute([$ip, $ua, $tracking_id, $campanha_id, $publico_id]);
        }

        header("Content-Type: image/gif");
        echo base64_decode("R0lGODlhAQABAIABAP///wAAACwAAAAAAQABAAACAkQBADs=");
        exit;
    }

    public function qrcode(): void
    {
        $email = $_GET['email'] ?? 'email@desconhecido.pt';
        $url = "https://lynx.com/eventos/admissaoConviteEvento?listid=88&contact=" . urlencode($email);

        header('Content-Type: image/png');

        echo Builder::create()
            ->writer(new PngWriter())
            ->data($url)
            ->encoding(new Encoding('UTF-8'))
            ->size(400)
            ->margin(10)
            ->build()
            ->getString();
    }
}
