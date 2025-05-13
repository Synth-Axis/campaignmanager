<?php

require_once("dbconfig.php");

class Utilizadores extends Base
{

    public function getAllUtilizadores()
    {

        $query = $this->db->prepare("
			SELECT 
				*
			FROM 
				agente
		");

        $query->execute();

        return $query->fetchAll();
    }

    public function findUtilizadorByEmail($email)
    {
        $query = $this->db->prepare("
			SELECT 
                *
			FROM 
				users
            WHERE
                email = ?
		");

        $query->execute([$email]);

        return $query->fetch();
    }
}
