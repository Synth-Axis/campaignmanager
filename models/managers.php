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
				gestor
		");

        $query->execute();

        return $query->fetchAll();
    }
}
