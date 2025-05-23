<?php

require_once("dbconfig.php");

class Managers extends Base
{

    public function getAllManagers()
    {
        $query = $this->db->prepare("
			SELECT 
				*
			FROM 
				Gestor
            ORDER BY gestor_nome ASC
		");

        $query->execute();

        return $query->fetchAll();
    }
}
