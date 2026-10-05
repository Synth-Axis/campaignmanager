<?php

require dirname(__DIR__) . '/app/Core/bootstrap.php';
$db = (new Database())->db;
$database = $db->query('SELECT DATABASE()')->fetchColumn();
$tables = $db->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);
$required = ['users', 'user_tokens', 'publico', 'acessos', 'campaigns', 'campanhas', 'canal', 'gestor', 'listas', 'segmentos', 'publico_segmentos', 'tipo_de_campanhas', 'tracking_campanha'];
$missing = array_diff($required, $tables);
if ($missing) {
    throw new RuntimeException('Faltam tabelas na base configurada (' . $database . '): ' . implode(', ', $missing));
}
// Exercise the query that failed at login without authenticating or changing data.
(new Users())->findUserByEmail('schema-check-' . bin2hex(random_bytes(8)) . '@example.invalid');
echo 'Database checks passed: ' . $database . '; required tables and login query available.' . PHP_EOL;
