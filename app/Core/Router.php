<?php

class Router
{
    public function __construct(private array $routes) {}

    public function dispatch(string $uri, string $method): void
    {
        $path = '/' . trim(parse_url($uri, PHP_URL_PATH) ?: '/', '/');
        $route = $this->routes[$path] ?? null;
        if (!$route) {
            http_response_code(404);
            echo 'Página não encontrada.';
            return;
        }
        if (!in_array($method, $route['methods'], true)) {
            http_response_code(405);
            header('Allow: ' . implode(', ', $route['methods']));
            return;
        }
        $authJson = $route['json'] || str_starts_with($path, '/api/') || $route['handler'][1] === 'cards';
        if ($route['auth'] && !Auth::check($authJson)) return;

        $GLOBALS['page_scripts'] = array_map(
            static fn(string $script): string => str_starts_with($script, 'https://') ? $script : asset_url($script),
            $route['scripts']
        );
        if ($route['json']) header('Content-Type: application/json; charset=utf-8');
        [$class, $action] = $route['handler'];
        (new $class())->$action();
    }
}
