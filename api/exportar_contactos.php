<?php

require_once __DIR__ . "/../models/publico.php";
require_once __DIR__ . "/../vendor/autoload.php"; // para XLSX


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$model = new Publico();

$campos = $_POST['campos'] ?? [];
$formato = $_POST['formato'] ?? 'csv';
$todos = $_POST['todos'] ?? 0;
$idsSelecionados = $_POST['contactosSelecionados'] ?? [];

$dados = $todos
    ? $model->getAllPublico()
    : $model->getPublicoByIds($idsSelecionados);

// Limita os campos
$dadosFiltrados = array_map(function ($linha) use ($campos) {
    return array_intersect_key($linha, array_flip($campos));
}, $dados);

if ($formato === 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename=contactos.csv');
    $fp = fopen('php://output', 'w');
    fputcsv($fp, $campos);
    foreach ($dadosFiltrados as $linha) fputcsv($fp, $linha);
    fclose($fp);
} elseif ($formato === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray($campos, null, 'A1');
    $sheet->fromArray($dadosFiltrados, null, 'A2');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="contactos.xlsx"');
    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}
exit;
