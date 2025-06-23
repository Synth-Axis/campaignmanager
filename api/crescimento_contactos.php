<?php
require_once("../models/publico.php");

header('Content-Type: application/json');

$model = new Publico();

// Receber número de dias com segurança
$periodos = [
    "Últimos 7 dias" => 7,
    "15 dias" => 15,
    "1 mês" => 30,
    "3 meses" => 90,
    "6 meses" => 180,
    "1 ano" => 365,
    "2 anos" => 730,
    "3 anos" => 1095
];

$label = $_GET['periodo'] ?? 'Últimos 7 dias';
$dias = $periodos[$label] ?? 7;

$dados = $model->getCrescimentoPorDia($dias);

echo json_encode($dados);
