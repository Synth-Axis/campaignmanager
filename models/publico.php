<?php

require_once("dbconfig.php");

class Publico extends Base
{


    public function getAllPublico()
    {
        try {
            $query = $this->db->prepare("
        SELECT 
            p.publico_id,
            p.nome,
            p.email,
            p.gestor_id,
            g.gestor_nome,
            p.canal_id,
            c.nome AS canal_nome,
            p.lista_id,
            l.lista_nome,
            p.data_registo
        FROM Publico p
        LEFT JOIN gestor g ON p.gestor_id = g.gestor_id
        LEFT JOIN canal c ON p.canal_id = c.canal_id
        LEFT JOIN listas l ON p.lista_id = l.lista_id
        ORDER BY p.nome ASC
    ");
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Erro PDO: " . $e->getMessage());
        }
    }

    public function findPublicoByEmail($email)
    {
        $query = $this->db->prepare("
			SELECT 
                *
			FROM 
				Publico
            WHERE
                email = ?
		");

        $query->execute([$email]);

        return $query->fetch();
    }

    public function RegisterPublico($formData)
    {

        $query = $this->db->prepare("
            INSERT INTO Publico
                (nome, email, gestor_id, canal_id, lista_id)
                VALUES(?, ?, ?, ?, ?)
            ");

        $query->execute([
            $formData["nome"],
            $formData["email"],
            $formData["gestor_id"],
            $formData["canal_id"],
            $formData["lista_id"]
        ]);
        return $this->db->lastInsertId();
    }

    public function pesquisarPublico($termo, $page = 1, $limit = 10000)
    {
        $offset = ($page - 1) * $limit;
        $sqlBase = "
        SELECT 
            p.publico_id,
            p.nome,
            p.email,
            g.gestor_nome,
            c.nome AS canal_nome,
            l.lista_nome,
            p.data_registo
        FROM Publico p
        LEFT JOIN gestor g ON p.gestor_id = g.gestor_id
        LEFT JOIN canal c ON p.canal_id = c.canal_id
        LEFT JOIN listas l ON p.lista_id = l.lista_id
    ";
        $params = [];
        if (trim($termo) !== "") {
            $sqlBase .= " WHERE p.nome LIKE :termo OR p.email LIKE :termo ";
            $params[':termo'] = '%' . $termo . '%';
        }
        $sqlBase .= " ORDER BY p.nome ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sqlBase);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, PDO::PARAM_STR);
        }
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function contarPublico($termo = "")
    {
        $sql = "SELECT COUNT(*) FROM Publico p";
        $params = [];
        if (trim($termo) !== "") {
            $sql .= " WHERE p.nome LIKE :termo OR p.email LIKE :termo";
            $params[':termo'] = '%' . $termo . '%';
        }
        $stmt = $this->db->prepare($sql);
        foreach ($params as $key => &$value) {
            $stmt->bindParam($key, $value, PDO::PARAM_STR);
        }
        $stmt->execute();
        return (int)$stmt->fetchColumn();
    }

    public function contarNovosUltimos30Dias()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Publico WHERE data_registo >= CURDATE() - INTERVAL 30 DAY");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAllEmailsByListId($lista_id)
    {
        $query = $this->db->prepare("SELECT email FROM Publico WHERE lista_id = ?");
        $query->execute([$lista_id]);
        return array_column($query->fetchAll(PDO::FETCH_ASSOC), 'email');
    }

    public function getCrescimentoPorDia($dias)
    {
        $inicio = new DateTime();
        $inicio->modify("-$dias days");
        $hoje = new DateTime();

        // Inicializa o array com todos os dias
        $diasIntervalo = [];
        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $hoje->modify('+1 day'));
        foreach ($periodo as $data) {
            $diasIntervalo[$data->format('Y-m-d')] = 0;
        }

        // Vai buscar os reais da base de dados
        $stmt = $this->db->prepare("
        SELECT DATE(data_registo) as dia, COUNT(*) as total
        FROM Publico
        WHERE data_registo >= CURDATE() - INTERVAL :dias DAY
        GROUP BY DATE(data_registo)
    ");
        $stmt->bindValue(":dias", $dias, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $diasIntervalo[$row['dia']] = (int) $row['total'];
        }

        // Reconvertendo em array final
        $resultado = [];
        foreach ($diasIntervalo as $dia => $total) {
            $resultado[] = [
                'dia' => $dia,
                'total' => $total
            ];
        }

        return $resultado;
    }

    public function contarNovosHoje()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM Publico WHERE DATE(data_registo) = CURDATE()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function updatePublico($data)
    {
        $query = $this->db->prepare("
        UPDATE Publico
        SET nome = :nome,
            email = :email,
            gestor_id = :gestor_id,
            canal_id = :canal_id,
            lista_id = :lista_id
        WHERE publico_id = :publico_id
    ");
        return $query->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':gestor_id' => $data['gestor_id'],
            ':canal_id' => $data['canal_id'],
            ':lista_id' => $data['lista_id'],
            ':publico_id' => $data['publico_id']
        ]);
    }

    public function apagarPublico($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM publico WHERE publico_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}
