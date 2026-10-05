<?php

// Only used by the isolated PHP test server. Never an application entry point.
if (PHP_SAPI !== 'cli-server') {
    http_response_code(404);
    exit;
}
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$root = dirname(__DIR__);
if (str_starts_with($path, '/assets/') || str_starts_with($path, '/dist/') || in_array($path, ['/favicon.svg', '/index1.html'], true)) return false;

// A fictional store exercises the password handlers without reading real credentials.
class Acesso
{
    private function all(): array
    {
        $file = dirname(__DIR__) . '/.cache/fixture-accesses.json';
        return is_file($file) ? json_decode(file_get_contents($file), true) : [];
    }
    public function listarTodos(): array { return array_values($this->all()); }
    public function procurarPorId($id) { return $this->all()[$id] ?? false; }
    public function inserir($data): bool
    {
        $all = $this->all();
        $id = count($all) + 1;
        $all[$id] = ['id' => $id, 'atualizado_em' => '2026-10-05 14:00:00'] + $data;
        file_put_contents(dirname(__DIR__) . '/.cache/fixture-accesses.json', json_encode($all));
        return true;
    }
    public function atualizar($id, $data): bool
    {
        $all = $this->all();
        $all[$id] = array_merge($all[$id], $data);
        file_put_contents(dirname(__DIR__) . '/.cache/fixture-accesses.json', json_encode($all));
        return true;
    }
    public function apagar($id): bool
    {
        $all = $this->all();
        unset($all[$id]);
        file_put_contents(dirname(__DIR__) . '/.cache/fixture-accesses.json', json_encode($all));
        return true;
    }
}

require $root . '/app/Core/bootstrap.php';
if (($_SERVER['HTTP_X_TEST_AUTH'] ?? '') === '1') {
    $_SESSION['user_id'] = 1;
    $_SESSION['user'] = ['user_id' => 1, 'nome' => 'Utilizador de teste', 'user_type' => 'admin'];
} else {
    $_SESSION = [];
}
generateCSRFToken();
(new Router(require $root . '/config/routes.php'))->dispatch($_SERVER['REQUEST_URI'], $_SERVER['REQUEST_METHOD']);
