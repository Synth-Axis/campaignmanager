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
                c.estado,
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
                (nome, assunto, lista_id, html, estado, data_criacao)
                VALUES (?, ?, ?, ?, ?, NOW())
        ");
        return $query->execute([
            $data['nome'],
            $data['assunto'],
            $data['lista_id'],
            $data['html'],
            $data['estado'] ?? 'rascunho',
        ]);
    }

    // Obter campanha por ID
    public function getCampaignById($id)
    {
        $query = $this->db->prepare("
            SELECT 
                c.*,
                l.lista_nome
            FROM campaigns c
            LEFT JOIN listas l ON c.lista_id = l.lista_id
            WHERE c.campaign_id = ?
        ");
        $query->execute([$id]);
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function getLastTrackingId()
    {
        return $this->db->lastInsertId();
    }

    // Editar campanha completa (inclui estado)
    public function updateCampaign($id, $data)
    {
        $query = $this->db->prepare("
        UPDATE campaigns
        SET nome = ?, assunto = ?, lista_id = ?, html = ?, estado = ?
        WHERE campaign_id = ?
    ");
        return $query->execute([
            $data['nome'],
            $data['assunto'],
            $data['lista_id'],
            $data['html'],
            $data['estado'],
            $id
        ]);
    }

    // Atualizar apenas o estado da campanha
    public function updateEstado($id, $estado)
    {
        $query = $this->db->prepare("
            UPDATE campaigns
            SET estado = ?
            WHERE campaign_id = ?
        ");
        return $query->execute([$estado, $id]);
    }

    // Apagar campanha
    public function deleteCampaign($id)
    {
        $query = $this->db->prepare("
            DELETE FROM campaigns WHERE campaign_id = ?
        ");
        return $query->execute([$id]);
    }

    //Tracking
    public function registarEnvioTracking($campanhaId, $publicoId, $ip = null, $userAgent = null)
    {
        $stmt = $this->db->prepare("
        INSERT INTO tracking_campanha 
            (campanha_id, publico_id, entregue_em, ip, user_agent)
        VALUES 
            (:campanha_id, :publico_id, NOW(), :ip, :user_agent)
    ");
        return $stmt->execute([
            ':campanha_id' => $campanhaId,
            ':publico_id'  => $publicoId,
            ':ip'          => $ip,
            ':user_agent'  => $userAgent
        ]);
    }
    public function getTotalEntregues()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tracking_campanha WHERE entregue_em IS NOT NULL");
        return $stmt->fetchColumn();
    }

    public function getTotalAberturas()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tracking_campanha WHERE aberto_em IS NOT NULL");
        return $stmt->fetchColumn();
    }

    public function getTotalCliques()
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM tracking_campanha WHERE clicado_em IS NOT NULL");
        return $stmt->fetchColumn();
    }
}
