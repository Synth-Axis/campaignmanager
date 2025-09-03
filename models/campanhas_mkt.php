<?php
require_once("dbconfig.php");

class Campanhas_mkt extends Base
{

    /** Lista tudo agrupado por Ano -> Tipo */
    public function tudoAgrupadoPorAnoETipo(?int $ano = null): array
    {
        $params = [];
        $sql = "SELECT c.*, t.nome AS tipo_nome
                FROM campanhas c
                LEFT JOIN tipo_de_campanhas t ON t.id = c.tipo_id ";
        if ($ano) {
            $sql .= "WHERE c.ano = :a ";
            $params[':a'] = $ano;
        }
        $sql .= "ORDER BY c.ano DESC, t.nome ASC, c.data_inicio DESC, c.nome ASC";
        $st = $this->db->prepare($sql);
        $st->execute($params);
        $rows = $st->fetchAll(PDO::FETCH_ASSOC);

        $out = [];
        foreach ($rows as $r) {
            $y = (int)$r['ano'];
            $t = $r['tipo_nome'];
            $out[$y][$t][] = $r;
        }
        return $out;
    }

    public function obterPorId(int $id): ?array
    {
        $st = $this->db->prepare(
            "SELECT c.*, t.nome AS tipo_nome, t.slug AS tipo_slug
             FROM campanhas c
             JOIN tipo_de_campanhas t ON t.id = c.tipo_id
             WHERE c.id = :id"
        );
        $st->execute([':id' => $id]);
        $row = $st->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function criar(array $d): int
    {
        $st = $this->db->prepare(
            "INSERT INTO campanhas
            (tipo_id, ano, nome, slug, descricao_curta, data_inicio, data_fim, caminho_imagem, url_teams, criado_por)
            VALUES (:tipo_id,:ano,:nome,:slug,:descricao_curta,:data_inicio,:data_fim,:caminho_imagem,:url_teams,:criado_por)"
        );
        $st->execute([
            ':tipo_id'        => $d['tipo_id'],
            ':ano'            => $d['ano'],
            ':nome'           => $d['nome'],
            ':slug'           => $d['slug'],
            ':descricao_curta' => $d['descricao_curta'] ?? null,
            ':data_inicio'    => $d['data_inicio'] ?: null,
            ':data_fim'       => $d['data_fim'] ?: null,
            ':caminho_imagem' => $d['caminho_imagem'] ?? null,
            ':url_teams'      => $d['url_teams'] ?? null,
            ':criado_por'     => $d['criado_por'] ?? null,
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function atualizar(int $id, array $d): bool
    {
        $st = $this->db->prepare(
            "UPDATE campanhas SET
              tipo_id=:tipo_id, ano=:ano, nome=:nome, slug=:slug,
              descricao_curta=:descricao_curta, data_inicio=:data_inicio, data_fim=:data_fim,
              caminho_imagem=:caminho_imagem, url_teams=:url_teams
             WHERE id=:id"
        );
        return $st->execute([
            ':id'             => $id,
            ':tipo_id'        => $d['tipo_id'],
            ':ano'            => $d['ano'],
            ':nome'           => $d['nome'],
            ':slug'           => $d['slug'],
            ':descricao_curta' => $d['descricao_curta'] ?? null,
            ':data_inicio'    => $d['data_inicio'] ?: null,
            ':data_fim'       => $d['data_fim'] ?: null,
            ':caminho_imagem' => $d['caminho_imagem'] ?? null,
            ':url_teams'      => $d['url_teams'] ?? null,
        ]);
    }

    public function apagar(int $id): bool
    {
        $st = $this->db->prepare("DELETE FROM campanhas WHERE id=:id");
        return $st->execute([':id' => $id]);
    }

    public function todosTipos(): array
    {
        return $this->db->query("SELECT id, nome FROM tipo_de_campanhas ORDER BY nome ASC")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
}
