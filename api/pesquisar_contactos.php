<?php

require_once("../models/publico.php");
header('Content-Type: application/json');

$termo = isset($_GET['q']) ? trim($_GET['q']) : '';
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$porPagina = 50;

$model = new Publico();

// 1. Vai buscar os registos desta página
$registos = $model->pesquisarPublico($termo, $page, $porPagina);

// 2. Conta o total (para paginar)
$total = $model->contarPublico($termo);

echo json_encode([
    'registos' => $registos,
    'total' => $total
]);
exit;
