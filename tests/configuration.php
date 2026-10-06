<?php

require dirname(__DIR__) . '/app/Core/Configuration.php';

$keys = ['APP_ENV', 'DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'MYSQLHOST', 'MYSQLPORT', 'MYSQLDATABASE', 'MYSQLUSER', 'MYSQLPASSWORD', 'ADDRESS', 'RAILWAY_PUBLIC_DOMAIN', 'PHPMAILER_HOST', 'PHPMAILER_USERNAME', 'PHPMAILER_PASSWORD', 'PHPMAILER_PORT', 'PHPMAILER_FROM_EMAIL', 'PHPMAILER_FROM_NAME'];
$original = [];
foreach ($keys as $key) {
    $original[$key] = getenv($key);
    putenv($key);
}
$directory = dirname(__DIR__) . '/.cache/config-' . bin2hex(random_bytes(6));
mkdir($directory, 0775, true);
$checks = 0;
$assert = static function (bool $condition, string $message) use (&$checks): void {
    if (!$condition) throw new RuntimeException($message);
    $checks++;
};

try {
    file_put_contents($directory . '/.ENV', "DB_HOST=localhost\nDB_NAME=local_demo\nDB_USER=local_user\nDB_PASSWORD=\"null; # fictional\"\nADDRESS=http://localhost\nPHPMAILER_PASSWORD=\"off\"\n");
    $local = Configuration::load($directory);
    $assert($local['DB_NAME'] === 'local_demo', 'Local configuration changed');
    $assert($local['DB_PASSWORD'] === 'null; # fictional', 'Local password was interpreted instead of preserved');
    $assert($local['PHPMAILER_PASSWORD'] === 'off', 'SMTP password was converted to a boolean');
    $assert($local['DB_PORT'] === '3306', 'Default local port changed');

    foreach (['MYSQLHOST' => 'mysql.railway.internal', 'MYSQLPORT' => '44312', 'MYSQLDATABASE' => 'railway', 'MYSQLUSER' => 'railway_user', 'MYSQLPASSWORD' => 'fictional#; password', 'APP_ENV' => 'production', 'RAILWAY_PUBLIC_DOMAIN' => 'demo.up.railway.app'] as $key => $value) putenv($key . '=' . $value);
    $remote = Configuration::load($directory);
    foreach (['DB_HOST' => 'mysql.railway.internal', 'DB_PORT' => '44312', 'DB_NAME' => 'railway', 'DB_USER' => 'railway_user', 'DB_PASSWORD' => 'fictional#; password', 'APP_ENV' => 'production', 'ADDRESS' => 'https://demo.up.railway.app'] as $key => $expected) {
        $assert($remote[$key] === $expected, 'Runtime variable did not override local configuration: ' . $key);
    }
    putenv('DB_PASSWORD=');
    putenv('DB_HOST=explicit.internal');
    putenv('ADDRESS=https://custom.example.com/');
    putenv('PHPMAILER_FROM_EMAIL=demo@example.com');
    $explicit = Configuration::load($directory);
    $assert($explicit['DB_PASSWORD'] === '', 'Empty runtime password must override other values');
    $assert($explicit['DB_HOST'] === 'explicit.internal', 'DB variables must override MYSQL aliases');
    $assert($explicit['ADDRESS'] === 'https://custom.example.com', 'Custom public URL was not preserved');
    $assert($explicit['PHPMAILER_FROM_EMAIL'] === 'demo@example.com', 'Runtime sender was not configured');

    $withoutFile = Configuration::load($directory . '/missing');
    $assert($withoutFile['DB_NAME'] === 'railway', 'A deployment must work without an .env file');
    $assert($withoutFile['DB_PASSWORD'] === '', 'Missing local file must not change runtime values');
    echo "Configuration checks passed: $checks\n";
} finally {
    unlink($directory . '/.ENV');
    rmdir($directory);
    foreach ($original as $key => $value) putenv($value === false ? $key : $key . '=' . $value);
}
