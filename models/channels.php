<?php

require_once("dbconfig.php");

class Channels extends Base
{

    public function getAllChannels()
    {

        $query = $this->db->prepare("
			SELECT 
				*
			FROM 
				Canal
            ORDER BY nome ASC
		");

        $query->execute();

        return $query->fetchAll();
    }

    public function criarCanal($nome)
    {
        $query = $this->db->prepare("
        INSERT INTO Canal (nome)
        VALUES (?)
    ");

        $query->execute([$nome]);

        return $this->db->lastInsertId();
    }
}
