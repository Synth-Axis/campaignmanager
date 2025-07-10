<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../models/dbconfig.php");

$base = new Base();
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
