<?php

require_once("dbconfig.php");

class publico extends Base
{
    public function getAllpublico()
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
        FROM publico p
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

    public function findpublicoByEmail($email)
    {
        $query = $this->db->prepare("
			SELECT 
                *
			FROM 
				publico
            WHERE
                email = ?
		");

        $query->execute([$email]);

        return $query->fetch();
    }

    public function Registerpublico($formData)
    {

        $query = $this->db->prepare("
            INSERT INTO publico
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

    public function pesquisarpublico($termo, $page = 1, $limit = 10000)
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
        FROM publico p
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
    public function contarpublico($termo = "")
    {
        $sql = "SELECT COUNT(*) FROM publico p";
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
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM publico WHERE data_registo >= CURDATE() - INTERVAL 30 DAY");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function getAllEmailsByListId($lista_id)
    {
        $query = $this->db->prepare("SELECT email, nome FROM publico WHERE lista_id = ?");
        $query->execute([$lista_id]);
        return $query->fetchAll(PDO::FETCH_ASSOC); // devolve arrays com 'email' e 'nome'
    }

    public function getCrescimentoPorDia($dias)
    {
        $inicio = new DateTime();
        $inicio->modify("-$dias days");
        $hoje = new DateTime();

        $diasIntervalo = [];
        $periodo = new DatePeriod($inicio, new DateInterval('P1D'), $hoje->modify('+1 day'));
        foreach ($periodo as $data) {
            $diasIntervalo[$data->format('Y-m-d')] = 0;
        }

        $stmt = $this->db->prepare("
        SELECT DATE(data_registo) as dia, COUNT(*) as total
        FROM publico
        WHERE data_registo >= CURDATE() - INTERVAL :dias DAY
        GROUP BY DATE(data_registo)
    ");
        $stmt->bindValue(":dias", $dias, PDO::PARAM_INT);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $diasIntervalo[$row['dia']] = (int) $row['total'];
        }

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
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM publico WHERE DATE(data_registo) = CURDATE()");
        $stmt->execute();
        return (int) $stmt->fetchColumn();
    }

    public function updatepublico($data)
    {
        $query = $this->db->prepare("
        UPDATE publico
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

    public function apagarpublico($id)
    {
        $id = (int)$id;
        $sql = "DELETE FROM publico WHERE publico_id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }

    public function getpublicoByIds($ids)
    {
        if (empty($ids)) return [];
        $in = str_repeat('?,', count($ids) - 1) . '?';
        $sql = "
            SELECT 
                p.publico_id,
                p.nome,
                p.email,
                g.gestor_nome AS gestor,
                c.nome AS canal,
                l.lista_nome AS lista,
                p.data_registo
            FROM publico p
            LEFT JOIN gestor g ON p.gestor_id = g.gestor_id
            LEFT JOIN canal c ON p.canal_id = c.canal_id
            LEFT JOIN listas l ON p.lista_id = l.lista_id
            WHERE p.publico_id IN ($in)
            ORDER BY p.nome ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateQrCodeByEmail($email, $qrCodeBase64)
    {
        $stmt = $this->db->prepare("UPDATE publico SET qr_code = :qrcode WHERE email = :email");
        return $stmt->execute([
            ':qrcode' => $qrCodeBase64,
            ':email' => $email
        ]);
    }
}
