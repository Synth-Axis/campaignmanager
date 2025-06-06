<?php

require_once("dbconfig.php");

class Publico extends Base
{

    public function getAllPublico()
    {
        $query = $this->db->prepare("
        SELECT 
            p.nome,
            p.email,
            g.gestor_nome,
            c.nome AS canal_nome,
            l.lista_nome
        FROM publico p
        LEFT JOIN gestor g ON p.gestor_id = g.gestor_id
        LEFT JOIN canal c ON p.canal_id = c.canal_id
        LEFT JOIN listas l ON p.lista_id = l.lista_id
        ORDER BY p.nome ASC
    ");

        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
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

        $query->execute(
            [
                $formData["nome"],
                $formData["email"],
                $formData["gestor_id"],
                $formData["canal_id"],
                $formData["lista_id"]
            ]
        );
        return $this->db->lastInsertId();
    }
}
