<?php

class PasswordManagerController
{
    public function index(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save();
            header('Location: /pm-home?msg=' . urlencode('Acesso guardado com sucesso.'));
            return;
        }
        $model = new Acesso();
        if (isset($_GET['apagar'])) {
            $model->apagar((int) $_GET['apagar']);
            header('Location: /pm-home?msg=' . urlencode('Acesso apagado.'));
            return;
        }
        $acessos = $model->listarTodos();
        require view_path('password-manager/index.php');
    }

    public function cards(): void
    {
        $acessos = (new Acesso())->listarTodos();
        require view_path('password-manager/_cards.php');
    }

    private function save(): bool
    {
        $dados = [
            'nome_servico' => $_POST['nome_servico'] ?? null,
            'url_acesso' => $_POST['url_acesso'] ?? null,
            'username' => $_POST['username'] ?? null,
            'senha_criptografada' => (new PasswordCipher())->encrypt($_POST['senha'] ?? ''),
            'notas' => $_POST['notas'] ?? null,
        ];
        $model = new Acesso();
        return !empty($_POST['id']) ? $model->atualizar((int) $_POST['id'], $dados) : $model->inserir($dados);
    }

    public function guardar(): void
    {
        echo json_encode(['sucesso' => $this->save()]);
    }

    private function find(): ?array
    {
        if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
            http_response_code(400);
            return null;
        }
        $acesso = (new Acesso())->procurarPorId((int) $_GET['id']);
        if (!$acesso) {
            http_response_code(404);
            return null;
        }
        return $acesso;
    }

    public function carregar(): void
    {
        $acesso = $this->find();
        if (!$acesso) {
            echo json_encode(['erro' => http_response_code() === 400 ? 'ID inválido' : 'Registo não encontrado']);
            return;
        }
        echo json_encode([
            'id' => $acesso['id'],
            'nome_servico' => $acesso['nome_servico'],
            'url_acesso' => $acesso['url_acesso'],
            'username' => $acesso['username'],
            'senha' => (new PasswordCipher())->decrypt($acesso['senha_criptografada']),
            'notas' => $acesso['notas'],
        ]);
    }

    public function senha(): void
    {
        header('Content-Type: text/plain; charset=utf-8');
        $acesso = $this->find();
        if (!$acesso) {
            echo http_response_code() === 400 ? 'ID inválido' : 'Registo não encontrado';
            return;
        }
        echo (new PasswordCipher())->decrypt($acesso['senha_criptografada']);
    }
}
