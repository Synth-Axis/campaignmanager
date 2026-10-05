<?php
class Segmentos extends Database
{
    private function condicaoAssociacao()
    {
        // Segmentos com regras refletem os dados atuais; os restantes mantêm as associações manuais.
        return "(
            EXISTS (
                SELECT 1 FROM segmento_regras r
                WHERE r.segmento_id = s.segmento_id AND (
                    (r.tipo = 'lista' AND p.lista_id = r.valor)
                    OR (r.tipo = 'canal' AND p.canal_id = r.valor)
                    OR (r.tipo = 'dias_registo' AND p.data_registo >= DATE_SUB(NOW(), INTERVAL r.valor DAY))
                )
            )
            OR (
                NOT EXISTS (SELECT 1 FROM segmento_regras r WHERE r.segmento_id = s.segmento_id)
                AND EXISTS (
                    SELECT 1 FROM publico_segmentos ps
                    WHERE ps.segmento_id = s.segmento_id AND ps.publico_id = p.publico_id
                )
            )
        )";
    }

    public function getAllSegmentos()
    {
        $associacao = $this->condicaoAssociacao();
        return $this->db->query("
            SELECT s.segmento_id, s.segmento_nome, s.descricao,
                   (SELECT COUNT(*) FROM publico p WHERE $associacao) AS total_contactos
            FROM segmentos s
            ORDER BY s.segmento_nome ASC
        ")->fetchAll();
    }

    public function getSegmentoById($id)
    {
        $query = $this->db->prepare('SELECT segmento_id, segmento_nome FROM segmentos WHERE segmento_id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }

    public function pesquisarContactos($segmentoId, $termo = '', $pagina = 1)
    {
        $porPagina = 50;
        $where = 'WHERE s.segmento_id = :segmento_id AND ' . $this->condicaoAssociacao();
        $params = [':segmento_id' => (int) $segmentoId];
        if ($termo !== '') {
            $where .= ' AND (p.nome LIKE :nome OR p.email LIKE :email)';
            $params[':nome'] = '%' . $termo . '%';
            $params[':email'] = '%' . $termo . '%';
        }

        $count = $this->db->prepare("
            SELECT COUNT(*) FROM publico p CROSS JOIN segmentos s $where
        ");
        $count->execute($params);
        $total = (int) $count->fetchColumn();
        $totalPaginas = max(1, (int) ceil($total / $porPagina));
        $pagina = min(max(1, (int) $pagina), $totalPaginas);

        $query = $this->db->prepare("
            SELECT p.publico_id, p.nome, p.email, g.gestor_nome, c.nome AS canal_nome, l.lista_nome
            FROM publico p CROSS JOIN segmentos s
            LEFT JOIN gestor g ON g.gestor_id = p.gestor_id
            LEFT JOIN canal c ON c.canal_id = p.canal_id
            LEFT JOIN listas l ON l.lista_id = p.lista_id
            $where
            ORDER BY p.nome ASC, p.publico_id ASC
            LIMIT :limite OFFSET :offset
        ");
        foreach ($params as $key => $value) {
            $query->bindValue($key, $value, is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $query->bindValue(':limite', $porPagina, PDO::PARAM_INT);
        $query->bindValue(':offset', ($pagina - 1) * $porPagina, PDO::PARAM_INT);
        $query->execute();

        return [
            'registos' => $query->fetchAll(),
            'total' => $total,
            'pagina' => $pagina,
            'total_paginas' => $totalPaginas,
            'por_pagina' => $porPagina,
        ];
    }
}
