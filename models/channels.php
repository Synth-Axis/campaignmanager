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
				canal
            ORDER BY nome ASC
		");

        $query->execute();

        return $query->fetchAll();
    }

    public function criarCanal($nome)
    {
        $query = $this->db->prepare("
        INSERT INTO canal (nome)
        VALUES (?)
    ");

        $query->execute([$nome]);

        return $this->db->lastInsertId();
    }
}
