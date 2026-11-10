<?php
class ProdutoDAO
{
    public function listar($busca = '')
    {
        $termo = '%' . $busca . '%';
        $stmt = Conexao::abrir()->prepare('SELECT * FROM produtos WHERE nome LIKE ? OR cod LIKE ? ORDER BY nome, id');
        $stmt->execute([$termo, $termo]);
        return $stmt->fetchAll();
    }

    public function buscar($id)
    {
        $stmt = Conexao::abrir()->prepare('SELECT * FROM produtos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function salvar($id, $cod, $nome, $categoria, $validade, $quantidade, $unitario, $pacote, $imagem)
    {
        $db = Conexao::abrir();
        if ($id) {
            $stmt = $db->prepare('UPDATE produtos SET cod = ?, nome = ?, categoria = ?, validade = ?, quantidade = ?, preco_unitario = ?, preco_pacote = ?, imagem = ? WHERE id = ?');
            $stmt->execute([$cod, $nome, $categoria, $validade, $quantidade, $unitario, $pacote, $imagem, $id]);
        } else {
            $stmt = $db->prepare('INSERT INTO produtos (cod, nome, categoria, validade, quantidade, preco_unitario, preco_pacote, imagem) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$cod, $nome, $categoria, $validade, $quantidade, $unitario, $pacote, $imagem]);
        }
    }

    public function excluir($id)
    {
        $stmt = Conexao::abrir()->prepare('DELETE FROM produtos WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}
