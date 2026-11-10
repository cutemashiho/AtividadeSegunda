<?php
class ClienteDAO
{
    public function listar()
    {
        return Conexao::abrir()->query('SELECT * FROM clientes ORDER BY nome, id')->fetchAll();
    }

    public function buscar($id)
    {
        $stmt = Conexao::abrir()->prepare('SELECT * FROM clientes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function inserir($nome, $email, $telefone)
    {
        $stmt = Conexao::abrir()->prepare('INSERT INTO clientes (nome, email, telefone) VALUES (?, ?, ?)');
        $stmt->execute([$nome, $email, $telefone]);
    }

    public function atualizar($id, $nome, $email, $telefone)
    {
        $stmt = Conexao::abrir()->prepare('UPDATE clientes SET nome = ?, email = ?, telefone = ? WHERE id = ?');
        $stmt->execute([$nome, $email, $telefone, $id]);
    }

    public function excluir($id)
    {
        $stmt = Conexao::abrir()->prepare('DELETE FROM clientes WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
