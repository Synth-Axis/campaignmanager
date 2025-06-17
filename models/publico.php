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
                g.gestor_nome,
                c.nome AS canal_nome,
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

    public function getAllEmailsByListId($lista_id)
    {
        $query = $this->db->prepare("SELECT email FROM Publico WHERE lista_id = ?");
        $query->execute([$lista_id]);
        return array_column($query->fetchAll(PDO::FETCH_ASSOC), 'email');
    }
}
