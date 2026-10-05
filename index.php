<?php

require_once __DIR__ . '/app/Core/bootstrap.php';
$router = new Router(require __DIR__ . '/config/routes.php');
$router->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
