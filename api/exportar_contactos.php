<?php

require_once __DIR__ . "/../models/publico.php";
require_once __DIR__ . "/../vendor/autoload.php"; // para XLSX


use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$model = new Publico();

$campos = $_POST['campos'] ?? [];
$formato = $_POST['formato'] ?? 'csv';
$idsSelecionados = $_POST['contactosSelecionados'] ?? [];
$todos = ($_POST['todos'] ?? '0') === '1' || empty($_POST['contactosSelecionados']);
file_put_contents("debug_query.txt", "Todos: $todos\nTotal IDs: " . count($idsSelecionados) . "\n", FILE_APPEND);


$dados = $todos
    ? $model->getAllPublico()
    : $model->getPublicoByIds($idsSelecionados);


file_put_contents("debug_query.txt", "Total dados: " . count($dados) . "\n", FILE_APPEND);

// Limita os campos
$dadosFiltrados = array_map(function ($linha) use ($campos) {
    return array_intersect_key($linha, array_flip($campos));
}, $dados);

if ($formato === 'csv') {
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename=contactos.csv');

    // Escreve BOM no início para Excel interpretar como UTF-8
    echo "\xEF\xBB\xBF";

    $fp = fopen('php://output', 'w');
    fputcsv($fp, $campos);

    foreach ($dadosFiltrados as $linha) {
        // Força UTF-8 para cada campo (caso venham com outro encoding)
        $utf8Linha = array_map(function ($valor) {
            return mb_convert_encoding($valor, 'UTF-8', 'auto');
        }, $linha);
        fputcsv($fp, $utf8Linha);
    }

    fclose($fp);
} elseif ($formato === 'xlsx') {
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    // Cabeçalhos
    $sheet->fromArray($campos, null, 'A1');

    // Dados com encoding forçado para UTF-8
    $utf8Data = array_map(function ($linha) {
        return array_map(function ($valor) {
            return mb_convert_encoding($valor, 'UTF-8', 'auto');
        }, $linha);
    }, $dadosFiltrados);

    $sheet->fromArray($utf8Data, null, 'A2');

    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment; filename="contactos.xlsx"');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
}
exit;
