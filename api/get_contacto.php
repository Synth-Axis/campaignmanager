<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once("../models/dbconfig.php");

if (!isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode(["erro" => "ID em falta"]);
    exit;
}

$id = (int) $_GET['id'];

try {
    $pdo = (new Base())->db;
    $stmt = $pdo->prepare("
        SELECT 
            p.publico_id,
            p.nome,
            p.email,
            p.gestor_id,
            p.lista_id,
            p.canal_id
        FROM Publico p
        WHERE p.publico_id = :id
        LIMIT 1
    ");
    $stmt->execute([':id' => $id]);
    $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($contacto) {
        echo json_encode($contacto);
    } else {
        http_response_code(404);
        echo json_encode(["erro" => "Contacto não encontrado"]);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erro" => "Erro no servidor"]);
}
