<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ContactosTransferService
{
    public function importarPublico(): void
    {
        $modelPublico = new Publico();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['erro' => 'Método não permitido']);
            exit;
        }

        // Se for um upload de ficheiro (formulário HTML)
        if (!empty($_FILES['ficheiro']['tmp_name'])) {
            $ficheiro = $_FILES['ficheiro']['tmp_name'];
            $handle = fopen($ficheiro, 'r');

            if ($handle === false) {
                http_response_code(400);
                echo json_encode(['erro' => 'Erro ao abrir o ficheiro']);
                exit;
            }

            fgetcsv($handle); // Ignora cabeçalho
            $resultados = [];

            while (($dados = fgetcsv($handle, 1000, ",")) !== false) {
                list($nome, $email, $gestor, $canal, $lista) = array_map('trim', $dados);

                if (mb_strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $resultados[] = ['email' => $email, 'sucesso' => false, 'mensagem' => 'Dados inválidos ou incompletos'];
                    continue;
                }

                if ($modelPublico->findPublicoByEmail($email)) {
                    $resultados[] = ['email' => $email, 'sucesso' => false, 'mensagem' => 'Email já registado'];
                    continue;
                }

                $modelPublico->RegisterPublico([
                    'nome' => $nome,
                    'email' => $email,
                    'gestor_id' => $gestor,
                    'canal_id' => $canal,
                    'lista_id' => $lista,
                ]);

                $resultados[] = ['email' => $email, 'sucesso' => true, 'mensagem' => 'Registado com sucesso'];
            }

            fclose($handle);
            header('Content-Type: application/json');
            echo json_encode(['resultados' => $resultados]);
            exit;
        }

        // Caso contrário, trata como JSON (POSTMAN ou API)
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
        if (stripos($contentType, 'application/json') === false) {
            http_response_code(400);
            echo json_encode(['erro' => 'Content-Type deve ser application/json']);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(['erro' => 'JSON inválido']);
            exit;
        }

        $resultados = [];

        foreach ($input as $item) {
            $nome = trim($item['nome'] ?? '');
            $email = trim($item['email'] ?? '');
            $gestor = $item['gestor_id'] ?? null;
            $lista = $item['lista_id'] ?? null;
            $canal = $item['canal_id'] ?? null;

            if (mb_strlen($nome) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $resultados[] = ['email' => $email, 'sucesso' => false, 'mensagem' => 'Dados inválidos ou incompletos'];
                continue;
            }

            if ($modelPublico->findPublicoByEmail($email)) {
                $resultados[] = ['email' => $email, 'sucesso' => false, 'mensagem' => 'Email já registado'];
                continue;
            }

            $modelPublico->RegisterPublico([
                'nome' => $nome,
                'email' => $email,
                'gestor_id' => $gestor,
                'canal_id' => $canal,
                'lista_id' => $lista,
            ]);

            $resultados[] = ['email' => $email, 'sucesso' => true, 'mensagem' => 'Registado com sucesso'];
        }

        header('Content-Type: application/json');
        echo json_encode(['resultados' => $resultados]);
        exit;
    }

    public function importarGestores(): void
    {
        // Log para confirmar início do script
        error_log("Início do script importar_gestores.php");
        header('Content-Type: application/json');

        // Validar método
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido"]);
            error_log("Método não permitido: " . $_SERVER["REQUEST_METHOD"]);
            exit;
        }

        // Validar Content-Type
        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
        if (stripos($contentType, 'application/json') === false) {
            http_response_code(400);
            echo json_encode(["erro" => "Content-Type deve ser application/json"]);
            error_log("Content-Type inválido: " . $contentType);
            exit;
        }

        // Ler e validar JSON
        $rawInput = file_get_contents("php://input");
        $input = json_decode($rawInput, true);

        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido"]);
            error_log("JSON inválido: " . $rawInput);
            exit;
        }

        // Instanciar modelo
        try {
            $modelGestor = new Managers();
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["erro" => "Erro ao ligar à base de dados"]);
            error_log("Erro PDO: " . $e->getMessage());
            exit;
        }

        // Processar inserções
        $resultados = [];

        foreach ($input as $index => $item) {
            $nome = trim($item["gestor_nome"] ?? '');
            $canal_id = $item["canal_id"] ?? null;

            if ($nome !== '' && is_numeric($canal_id)) {
                try {
                    $id = $modelGestor->criarGestor($nome, $canal_id);
                    $resultados[] = [
                        "gestor_id" => $id,
                        "gestor_nome" => $nome,
                        "canal_id" => $canal_id
                    ];
                } catch (Exception $e) {
                    http_response_code(500);
                    error_log("Erro ao inserir gestor na posição $index: " . $e->getMessage());
                    echo json_encode(["erro" => "Erro ao inserir gestor '$nome'"]);
                    exit;
                }
            } else {
                error_log("Dados inválidos na posição $index: " . json_encode($item));
            }
        }

        echo json_encode($resultados);
    }

    public function importarCanais(): void
    {
        // Log para confirmar início do script
        error_log("Início do script importar_gestores.php");
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            http_response_code(405);
            echo json_encode(["erro" => "Método não permitido"]);
            exit;
        }

        $contentType = $_SERVER["CONTENT_TYPE"] ?? '';
        if (stripos($contentType, 'application/json') === false) {
            http_response_code(400);
            echo json_encode(["erro" => "Content-Type deve ser application/json"]);
            exit;
        }

        $input = json_decode(file_get_contents("php://input"), true);
        if (!is_array($input)) {
            http_response_code(400);
            echo json_encode(["erro" => "JSON inválido"]);
            exit;
        }

        $modelCanal = new Channels();
        $resultados = [];

        foreach ($input as $item) {
            $nome = trim($item["nome"] ?? '');
            if ($nome !== '') {
                $id = $modelCanal->criarCanal($nome);
                $resultados[] = ["nome" => $nome, "canal_id" => $id];
            }
        }

        echo json_encode($resultados);
    }

    public function exportarContactos(): void
    {
        // para XLSX
        $model = new Publico();

        $campos = $_POST['campos'] ?? [];
        $formato = $_POST['formato'] ?? 'csv';
        $idsSelecionados = $_POST['contactosSelecionados'] ?? [];
        $todos = ($_POST['todos'] ?? '0') === '1' || empty($_POST['contactosSelecionados']);


        $dados = $todos
            ? $model->getAllPublico()
            : $model->getPublicoByIds($idsSelecionados);

        // Limita os campos
        $dadosFiltrados = array_map(function ($linha) use ($campos) {
            return array_intersect_key($linha, array_flip($campos));
        }, $dados);

        if ($formato === 'csv') {
            header('Content-Type: text/csv; charset=UTF-8');
            header('Content-Disposition: attachment; filename=contactos.csv');

            // Escreve BOM no início para Excel interpretar como UTF-8
            echo "\xEF\xBB\xBF";

            $fp = fopen('php://output', 'w');
            fputcsv($fp, $campos);

            foreach ($dadosFiltrados as $linha) {
                // Força UTF-8 para cada campo (caso venham com outro encoding)
                $utf8Linha = array_map(function ($valor) {
                    return mb_convert_encoding($valor, 'UTF-8', 'auto');
                }, $linha);
                fputcsv($fp, $utf8Linha);
            }

            fclose($fp);
        } elseif ($formato === 'xlsx') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Cabeçalhos
            $sheet->fromArray($campos, null, 'A1');

            // Dados com encoding forçado para UTF-8
            $utf8Data = array_map(function ($linha) {
                return array_map(function ($valor) {
                    return mb_convert_encoding($valor, 'UTF-8', 'auto');
                }, $linha);
            }, $dadosFiltrados);

            $sheet->fromArray($utf8Data, null, 'A2');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="contactos.xlsx"');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }
        exit;
    }
}
