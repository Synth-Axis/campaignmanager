<?php
require __DIR__ . '/../vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;

$email = $_GET['email'] ?? 'email@desconhecido.pt';
$url = "https://www.realvidaseguros.pt/eventos/admissaoConviteEvento?listid=88&contact=" . urlencode($email);

header('Content-Type: image/png');

echo Builder::create()
    ->writer(new PngWriter())
    ->data($url)
    ->encoding(new Encoding('UTF-8'))
    ->size(400)
    ->margin(10)
    ->build()
    ->getString();
