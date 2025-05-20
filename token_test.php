<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("core/basefunctions.php");
require("models/users.php");

$model = new Users();

$userId = 2; // substitui pelo teu ID real, se necessário
$token = bin2hex(random_bytes(32));
$tokenHash = hash('sha256', $token);
$expiresAt = date("Y-m-d H:i:s", time() + 3600);

try {
    $model->storeRememberToken($userId, $tokenHash, $expiresAt);
    echo "✅ Inserido com sucesso!";
} catch (PDOException $e) {
    echo "❌ Erro ao guardar token: " . $e->getMessage();
}