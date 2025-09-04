<?php
require_once __DIR__ . '/../models/campanhas_mkt.php';

class CampanhaController
{
    private Campanhas_mkt $model;
    public function __construct(PDO $db)
    {
        $this->model = new Campanhas_mkt();
    }

    public function index(): void
    {
        $ano = isset($_GET['ano']) ? (int)$_GET['ano'] : null;
        $agrupado = $this->model->tudoAgrupadoPorAnoETipo($ano);
        $tipos = $this->model->todosTipos();
        include __DIR__ . '/../views/campanhas/index.php';
    }

    public function ver(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $campanha = $this->model->obterPorId($id);
        if (!$campanha) {
            http_response_code(404);
            echo "Campanha não encontrada";
            return;
        }
        include __DIR__ . '/../views/campanhas/ver.php';
    }

    public function criar(): void
    {
        $tipos = $this->model->todosTipos();
        include __DIR__ . '/../views/campanhas/form.php';
    }

    public function guardar(): void
    {
        $caminhoImagem = null;
        if (!empty($_FILES['imagem']['name'])) {
            $dir = __DIR__ . '/../public/uploads/campanhas/';
            if (!is_dir($dir)) mkdir($dir, 0775, true);
            $fname = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['imagem']['name']));
            $dest = $dir . $fname;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $dest)) {
                $caminhoImagem = '/public/uploads/campanhas/' . $fname;
            }
        }

        $data = [
            'tipo_id'         => (int)$_POST['tipo_id'],
            'ano'             => (int)$_POST['ano'],
            'nome'            => trim($_POST['nome']),
            'slug'            => self::slugify($_POST['nome']),
            'descricao_curta' => trim($_POST['descricao_curta'] ?? ''),
            'data_inicio'     => $_POST['data_inicio'] ?: null,
            'data_fim'        => $_POST['data_fim'] ?: null,
            'caminho_imagem'  => $caminhoImagem,
            'url_teams'       => trim($_POST['url_teams'] ?? ''),
            'criado_por'      => $_SESSION['user_id'] ?? null,
        ];
        $id = $this->model->criar($data);
        header("Location: /campanhas.php?acao=ver&id={$id}");
    }

    public function editar(): void
    {
        $id = (int)($_GET['id'] ?? 0);
        $campanha = $this->model->obterPorId($id);
        if (!$campanha) {
            http_response_code(404);
            echo "Campanha não encontrada";
            return;
        }
        $tipos = $this->model->todosTipos();
        include __DIR__ . '/../views/campanhas/form.php';
    }

    public function atualizar(): void
    {
        $id = (int)$_POST['id'];
        $existente = $this->model->obterPorId($id);
        if (!$existente) {
            http_response_code(404);
            echo "Campanha não encontrada";
            return;
        }

        $caminhoImagem = $existente['caminho_imagem'];
        if (!empty($_FILES['imagem']['name'])) {
            $dir = __DIR__ . '/../public/uploads/campanhas/';
            if (!is_dir($dir)) mkdir($dir, 0775, true);
            $fname = time() . '_' . preg_replace('/\s+/', '_', basename($_FILES['imagem']['name']));
            $dest = $dir . $fname;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $dest)) {
                $caminhoImagem = '/uploads/campanhas/' . $fname;
            }
        }

        $data = [
            'tipo_id'         => (int)$_POST['tipo_id'],
            'ano'             => (int)$_POST['ano'],
            'nome'            => trim($_POST['nome']),
            'slug'            => self::slugify($_POST['nome']),
            'descricao_curta' => trim($_POST['descricao_curta'] ?? ''),
            'data_inicio'     => $_POST['data_inicio'] ?: null,
            'data_fim'        => $_POST['data_fim'] ?: null,
            'caminho_imagem'  => $caminhoImagem,
            'url_teams'       => trim($_POST['url_teams'] ?? ''),
        ];
        $this->model->atualizar($id, $data);
        header("Location: /campanhas.php?acao=ver&id={$id}");
    }

    public function remover(): void
    {
        $id = (int)($_POST['id'] ?? 0);
        $this->model->apagar($id);
        header("Location: /campanhas.php");
    }

    private static function slugify(string $txt): string
    {
        $txt = iconv('UTF-8', 'ASCII//TRANSLIT', $txt);
        $txt = preg_replace('~[^\\pL\\d]+~u', '-', $txt);
        $txt = trim($txt, '-');
        $txt = strtolower($txt);
        $txt = preg_replace('~[^-a-z0-9]+~', '', $txt);
        return $txt ?: uniqid('campanha-');
    }
}
