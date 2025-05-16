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

    
}
