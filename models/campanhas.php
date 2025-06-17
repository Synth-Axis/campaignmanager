<?php

require_once("dbconfig.php");

class Campaigns extends Base
{
    // Lista todas as campanhas
    public function getAllCampaigns()
    {
        $query = $this->db->prepare("
            SELECT 
                c.campaign_id,
                c.nome,
                c.assunto,
                c.lista_id,
                l.lista_nome,
                c.html,
                c.data_criacao
            FROM campaigns c
            LEFT JOIN listas l ON c.lista_id = l.lista_id
            ORDER BY c.data_criacao DESC
        ");
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Cria uma nova campanha
    public function createCampaign($data)
    {
        $query = $this->db->prepare("
            INSERT INTO campaigns
                (nome, assunto, lista_id, html, data_criacao)
                VALUES (?, ?, ?, ?, NOW())
        ");
        return $query->execute([
            $data['nome'],
            $data['assunto'],
            $data['lista_id'],
            $data['html']
        ]);
    }

    // Obter campanha por ID
    public function getCampaignById($id)
    {
        $query = $this->db->prepare("
            SELECT * FROM campaigns WHERE campaign_id = ?
        ");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    // (Opcional) Editar campanha
    public function updateCampaign($id, $data)
    {
        $query = $this->db->prepare("
            UPDATE campaigns
            SET nome = ?, assunto = ?, lista_id = ?, html = ?
            WHERE campaign_id = ?
        ");
        return $query->execute([
            $data['nome'],
            $data['assunto'],
            $data['lista_id'],
            $data['html'],
            $id
        ]);
    }

    // (Opcional) Apagar campanha
    public function deleteCampaign($id)
    {
        $query = $this->db->prepare("
            DELETE FROM campaigns WHERE campaign_id = ?
        ");
        return $query->execute([$id]);
    }
}
