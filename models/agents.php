<?php

require_once("dbconfig.php");

class Agents extends Base
{

    public function getAllAgents()
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
