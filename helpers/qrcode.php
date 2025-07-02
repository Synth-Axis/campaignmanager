<?php

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;


function gerarQrCodeBase64($email)
{
    $url = "https://www.realvidaseguros.pt/eventos/admissaoConviteEvento?listid=88&contact=" . urlencode($email);

    $qr = Builder::create()
        ->writer(new PngWriter())
        ->data($url)
        ->encoding(new Encoding('UTF-8'))
        ->size(400)
        ->margin(10)
        ->build();

    return 'data:image/png;base64,' . base64_encode($qr->getString());
}
