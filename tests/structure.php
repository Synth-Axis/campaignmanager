<?php

require dirname(__DIR__) . '/app/Core/bootstrap.php';
$checks = 0;
$assert = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) throw new RuntimeException($message);
    $checks++;
};
$routes = require APP_ROOT . '/config/routes.php';
foreach ($routes as $path => $route) {
    [$class, $action] = $route['handler'];
    $assert(class_exists($class), 'Missing controller for ' . $path);
    $assert((new ReflectionMethod($class, $action))->isPublic(), 'Invalid handler for ' . $path);
    foreach ($route['scripts'] as $script) {
        if (!str_starts_with($script, 'https://')) $assert(is_file(APP_ROOT . '/' . $script), 'Missing script: ' . $script);
    }
}
foreach ([['/home', '/home-center'], ['/home-center', '/app-center'], ['/pm-home', '/password-manager'], ['/controllers/carregar_acesso.php', '/password-manager/carregar'], ['/controllers/editar_campanha.php', '/campanhas/editar'], ['/campanhas.php', '/campanhas-conteudo']] as [$old, $new]) {
    $assert($routes[$old] === $routes[$new], 'Broken compatibility alias: ' . $old);
}
$views = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(APP_ROOT . '/views'));
foreach ($views as $file) {
    if (!$file->isFile() || $file->getExtension() !== 'php') continue;
    preg_match_all("/view_path\('([^']+)'\)/", file_get_contents($file->getPathname()), $matches);
    foreach ($matches[1] as $view) $assert(is_file(view_path($view)), 'Missing view: ' . $view);
}
$cipher = new PasswordCipher();
foreach (['Senha fictícia € 2026', '0', str_repeat('x', 300)] as $password) {
    $iv = random_bytes(16);
    $legacy = base64_encode($iv . openssl_encrypt($password, 'aes-256-cbc', 'chave-super-secreta', 0, $iv));
    $assert($cipher->decrypt($legacy) === $password, 'Existing password format changed');
    $stored = base64_decode($cipher->encrypt($password));
    $assert(openssl_decrypt(substr($stored, 16), 'aes-256-cbc', 'chave-super-secreta', 0, substr($stored, 0, 16)) === $password, 'New format is incompatible');
}
$assert($cipher->encrypt('') === null && $cipher->decrypt(null) === '', 'Empty password behavior changed');
$assert($cipher->decrypt('invalid!') === '', 'Malformed password should not break rendering');

$_SESSION = [];
$_COOKIE = [];
ob_start();
(new Router($routes))->dispatch('/api/pesquisar_contactos.php', 'GET');
$response = json_decode(ob_get_clean(), true);
$assert(http_response_code() === 401 && isset($response['erro']), 'API authentication should return JSON');
ob_start();
(new Router($routes))->dispatch('/password-manager/guardar', 'GET');
ob_end_clean();
$assert(http_response_code() === 405, 'Save must only accept POST');
ob_start();
(new Router($routes))->dispatch('/missing-route', 'GET');
ob_end_clean();
$assert(http_response_code() === 404, 'Missing route should return 404');
echo "Structure checks passed: $checks\n";
