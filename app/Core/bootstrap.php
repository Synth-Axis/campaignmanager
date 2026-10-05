<?php

define('APP_ROOT', dirname(__DIR__, 2));
define('ROOT', '/');
date_default_timezone_set('Europe/Lisbon');
define('ENV', parse_ini_file(APP_ROOT . '/.env') ?: []);

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

ini_set('log_errors', '1');
ini_set('error_log', APP_ROOT . '/storage/logs/php-errors.log');
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (session_status() === PHP_SESSION_ACTIVE && !isset($_SESSION['csrf_token'])) {
    generateCSRFToken();
}
