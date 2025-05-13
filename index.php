<?php

define("ENV", parse_ini_file(".env"));
define("ROOT", "/");

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$url_parts = explode("/", trim($path, "/"));

$controller = !empty($url_parts[0]) ? $url_parts[0] : "home";
$id = $url_parts[1] ?? null;

$controller_file = "controllers/" . $controller . ".php";

if (file_exists($controller_file)) {
    require($controller_file);
} else {
    http_response_code(404);
    echo "Erro 404: O controlador '$controller' não foi encontrado.";
}
