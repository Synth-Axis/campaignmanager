<?php
require_once("../models/dbconfig.php");

$campanha_id = $_GET['cid'] ?? null;
$email = $_GET['email'] ?? null;
$url = $_GET['url'] ?? null;

if ($campanha_id && $email && $url) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $stmt = $db->prepare("
        INSERT IGNORE INTO campanha_estatisticas 
        (campanha_id, email, estado, ip, user_agent, url)
        VALUES (?, ?, 'clicado', ?, ?, ?)
    ");
    $stmt->execute([$campanha_id, $email, $ip, $ua, $url]);

    // Redireciona para o destino
    header("Location: " . $url);
    exit;
}

// fallback: se não tiver dados válidos
header("Location: https://www.realvidaseguros.pt");
exit;
