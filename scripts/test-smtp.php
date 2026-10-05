<?php

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
require dirname(__DIR__) . '/app/Core/bootstrap.php';

$recipient = $argv[1] ?? '';
if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
    fwrite(STDERR, "Utilização: php scripts/test-smtp.php destinatario@example.com\n");
    exit(1);
}
$ok = (new EmailService())->send($recipient, 'Teste SMTP LynxApp', 'A configuração de email está operacional.');
echo $ok ? "Email enviado.\n" : "Falha no envio. Consulte storage/logs/php-errors.log.\n";
exit($ok ? 0 : 1);
