<?php

define('APP_ROOT', dirname(__DIR__, 2));
define('ROOT', '/');
date_default_timezone_set('Europe/Lisbon');
require_once __DIR__ . '/Configuration.php';
define('ENV', Configuration::load(APP_ROOT));
$production = ENV['APP_ENV'] === 'production';
ini_set('display_errors', $production ? '0' : '1');
ini_set('log_errors', '1');
ini_set('error_log', $production ? '/proc/self/fd/2' : APP_ROOT . '/storage/logs/php-errors.log');

require_once APP_ROOT . '/vendor/autoload.php';
spl_autoload_register(function (string $class): void {
    foreach (['Controllers', 'Models', 'Services', 'Core'] as $folder) {
        $file = APP_ROOT . '/app/' . $folder . '/' . $class . '.php';
        if (is_file($file)) {
            require_once $file;
            return;
        }
    }
});
require_once __DIR__ . '/functions.php';

if (PHP_SAPI !== 'cli' && parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) !== '/health' && session_status() === PHP_SESSION_NONE) {
    ini_set('session.use_strict_mode', '1');
    session_set_cookie_params([
        'path' => '/',
        'secure' => $production || (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}
if (session_status() === PHP_SESSION_ACTIVE && !isset($_SESSION['csrf_token'])) {
    generateCSRFToken();
}
