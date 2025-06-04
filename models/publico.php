<?php

require_once("dbconfig.php");

class Publico extends Base
{

    public function getAllPublico()
    {

        $query = $this->db->prepare("
			SELECT 
				*
			FROM 
				Publico
		");

        $query->execute();

        return $query->fetchAll();
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
                (nome, email, gestor_id, canal_id, listas_id)
                VALUES(?, ?, ?, ?, ?)
            ");

        $query->execute(
            [
                $formData["nome"],
                $formData["email"],
                $formData["gestor_id"],
                $formData["canal_id"],
                $formData["listas_id"]
            ]
        );
        return $this->db->lastInsertId();
    }
}
