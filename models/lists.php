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

    public function criarLista($nome)
    {
        $stmt = $this->db->prepare("INSERT INTO listas (lista_nome) VALUES (:nome)");
        $stmt->bindParam(':nome', $nome);
        $stmt->execute();

        return $this->db->lastInsertId(); // Adiciona isto
    }

    public function atualizarNomeLista($id, $novoNome)
    {
        $query = $this->db->prepare("
            UPDATE listas
            SET lista_nome = ?
            WHERE lista_id = ?
        ");
        $query->execute([$novoNome, $id]);
    }

    public function apagarLista($id)
    {
        $stmt = $this->db->prepare("DELETE FROM listas WHERE lista_id = ?");
        $stmt->execute([$id]);
    }
}
