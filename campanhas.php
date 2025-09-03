<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require __DIR__ . '/models/dbconfig.php';
require __DIR__ . '/controllers/campanhas_mkt.php';

$base = new Base(); // ligação via dbconfig
$pdo = $base->db;

$ctrl = new CampanhaController($pdo);
$acao = $_GET['acao'] ?? 'index';

switch ($acao) {
    case 'ver':
        $ctrl->ver();
        break;
    case 'criar':
        $ctrl->criar();
        break;
    case 'guardar':
        $ctrl->guardar();
        break;
    case 'editar':
        $ctrl->editar();
        break;
    case 'atualizar':
        $ctrl->atualizar();
        break;
    case 'remover':
        $ctrl->remover();
        break;
    default:
        $ctrl->index();
        break;
}
