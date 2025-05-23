<?php

require_once("dbconfig.php");

class Lists extends Base
{

    public function getAllLists()
    {
        $query = $this->db->prepare("
			SELECT 
				*
			FROM 
				Listas
		");

        $query->execute();

        return $query->fetchAll();
    }
}