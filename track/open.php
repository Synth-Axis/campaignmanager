<?php
require_once("../models/dbconfig.php");

$campanha_id = $_GET['cid'] ?? null;
$email = $_GET['email'] ?? null;

if ($campanha_id && $email) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';

    $stmt = $db->prepare("
        INSERT IGNORE INTO campanha_estatisticas 
        (campanha_id, email, estado, ip, user_agent)
        VALUES (?, ?, 'aberto', ?, ?)
    ");
    $stmt->execute([$campanha_id, $email, $ip, $ua]);
}

// Retorna uma imagem transparente de 1x1
header("Content-Type: image/gif");
echo base64_decode("R0lGODlhAQABAIABAP///wAAACwAAAAAAQABAAACAkQBADs=");
exit;
