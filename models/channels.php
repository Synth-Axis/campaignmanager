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
				agentee
		");

        $query->execute();

        return $query->fetchAll();
    }
}
