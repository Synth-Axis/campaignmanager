<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__ . "/../models/dbconfig.php");

$base = new Base();
$db = $base->db;

$campanha_id = $_GET['cid'] ?? null;
$email = $_GET['email'] ?? null;
$url = $_GET['url'] ?? null;

if (!$campanha_id || !$email || !$url || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: https://www.realvidaseguros.pt");
    exit;
}

if (!filter_var($url, FILTER_VALIDATE_URL)) {
    $url = "https://www.realvidaseguros.pt";
}

$ip = $_SERVER['REMOTE_ADDR'] ?? '';
$ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

// Obter o público_id com base no email
$stmt = $db->prepare("SELECT publico_id FROM publico WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$publico = $stmt->fetch(PDO::FETCH_ASSOC);

if ($publico && $publico['publico_id']) {
    $stmt = $db->prepare("
        UPDATE tracking_campanha
        SET clicado_em = NOW(), link_clicado = ?, ip = ?, user_agent = ?
        WHERE campanha_id = ? AND publico_id = ?
    ");
    $stmt->execute([$url, $ip, $ua, $campanha_id, $publico['publico_id']]);
}

// Redireciona para o URL original
header("Location: " . $url);
exit;
