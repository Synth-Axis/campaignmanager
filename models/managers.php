<?php

require_once("dbconfig.php");

class Managers extends Base
{
    public function getAllManagers()
    {
        $query = $this->db->prepare("SELECT * FROM Gestor ORDER BY gestor_nome ASC");
        $query->execute();
        return $query->fetchAll();
    }

    public function criarGestor($nome, $canal_id)
    {
        $query = $this->db->prepare("
        INSERT INTO Gestor (gestor_nome, canal_id)
        VALUES (?, ?)
    ");

        $query->execute([$nome, $canal_id]);
        return $this->db->lastInsertId();
    }
}
