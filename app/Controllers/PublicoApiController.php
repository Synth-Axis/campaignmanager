<?php

class PublicoApiController
{
    public function pesquisarContactos(): void
    {
        header('Content-Type: application/json');

        $termo = isset($_GET['q']) ? trim($_GET['q']) : '';
        $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
        $porPagina = 50;

        $model = new Publico();

        // 1. Vai buscar os registos desta página
        $registos = $model->pesquisarPublico($termo, $page, $porPagina);

        // 2. Conta o total (para paginar)
        $total = $model->contarPublico($termo);

        echo json_encode([
            'registos' => $registos,
            'total' => $total
        ]);
        exit;
    }

    public function crescimentoContactos(): void
    {
        header('Content-Type: application/json');

        $model = new Publico();

        $periodos = [
            "Últimos 7 dias" => 7,
            "15 dias" => 15,
            "1 mês" => 30,
            "3 meses" => 90,
            "6 meses" => 180,
            "1 ano" => 365,
            "2 anos" => 730,
            "3 anos" => 1095
        ];

        $label = $_GET['periodo'] ?? 'Últimos 7 dias';
        $dias = $periodos[$label] ?? 7;

        $dados = $model->getCrescimentoPorDia($dias);

        echo json_encode($dados);
    }

    public function getContacto(): void
    {
        if (!isset($_GET['id'])) {
            http_response_code(400);
            echo json_encode(["erro" => "ID em falta"]);
            exit;
        }

        $id = (int) $_GET['id'];

        try {
            $pdo = (new Database())->db;
            $stmt = $pdo->prepare("
                SELECT
                    p.publico_id,
                    p.nome,
                    p.email,
                    p.gestor_id,
                    p.lista_id,
                    p.canal_id
                FROM publico p
                WHERE p.publico_id = :id
                LIMIT 1
            ");
            $stmt->execute([':id' => $id]);
            $contacto = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($contacto) {
                echo json_encode($contacto);
            } else {
                http_response_code(404);
                echo json_encode(["erro" => "Contacto não encontrado"]);
            }
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro no servidor"]);
        }
    }

    public function contactosSegmento(): void
    {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-store');

        session_write_close();

        $segmentoId = filter_input(INPUT_GET, 'segmento_id', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $pagina = filter_input(INPUT_GET, 'page', FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) ?: 1;
        $termo = $_GET['q'] ?? '';
        if (!$segmentoId || !is_string($termo)) {
            http_response_code(400);
            echo json_encode(['erro' => 'Pedido de segmento inválido.']);
            exit;
        }
        try {
            $model = new Segmentos();
            $segmento = $model->getSegmentoById($segmentoId);
            if (!$segmento) {
                http_response_code(404);
                echo json_encode(['erro' => 'O segmento já não está disponível.']);
                exit;
            }
            $resultado = $model->pesquisarContactos($segmentoId, trim($termo), $pagina);
            echo json_encode(['segmento' => $segmento] + $resultado, JSON_UNESCAPED_UNICODE);
        } catch (PDOException $e) {
            error_log('Erro ao consultar contactos do segmento: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['erro' => 'Não foi possível carregar os contactos. Tente novamente.']);
        }
    }

    public function importarPublico(): void
    {
        (new ContactosTransferService())->importarPublico();
    }

    public function importarGestores(): void
    {
        (new ContactosTransferService())->importarGestores();
    }

    public function importarCanais(): void
    {
        (new ContactosTransferService())->importarCanais();
    }

    public function exportarContactos(): void
    {
        (new ContactosTransferService())->exportarContactos();
    }
}
