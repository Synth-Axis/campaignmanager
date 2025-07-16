<?php
require_once("dbconfig.php");

class Acesso extends Base
{
    public function listarTodos()
    {
        $stmt = $this->db->query("SELECT * FROM acessos ORDER BY nome_servico ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function procurarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM acessos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function inserir($dados)
    {
        $stmt = $this->db->prepare("
            INSERT INTO acessos (nome_servico, url_acesso, username, email, senha_criptografada, notas)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $dados['nome_servico'],
            $dados['url_acesso'],
            $dados['username'],
            $dados['email'],
            $dados['senha_criptografada'],
            $dados['notas']
        ]);
    }

    public function atualizar($id, $dados)
    {
        $stmt = $this->db->prepare("
            UPDATE acessos
            SET nome_servico = ?, url_acesso = ?, username = ?, email = ?, senha_criptografada = ?, notas = ?, atualizado_em = NOW()
            WHERE id = ?
        ");
        return $stmt->execute([
            $dados['nome_servico'],
            $dados['url_acesso'],
            $dados['username'],
            $dados['email'],
            $dados['senha_criptografada'],
            $dados['notas'],
            $id
        ]);
    }

    public function apagar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM acessos WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
