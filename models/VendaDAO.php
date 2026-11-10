<?php
class VendaDAO
{
    public function registrar($cliente, $carrinho)
    {
        $db = Conexao::abrir();
        $db->exec('BEGIN IMMEDIATE');

        try {
            $data = date('Y-m-d');
            $venda = $db->prepare('INSERT INTO vendas (cliente_nome, data_venda) VALUES (?, ?)');
            $venda->execute([$cliente, $data]);
            $vendaId = $db->lastInsertId();

            $produto = $db->prepare('SELECT nome, quantidade, preco_unitario FROM produtos WHERE id = ?');
            $estoque = $db->prepare('UPDATE produtos SET quantidade = quantidade - ? WHERE id = ?');
            $item = $db->prepare('INSERT INTO itens_venda (venda_id, produto_nome, quantidade, preco_unitario) VALUES (?, ?, ?, ?)');

            foreach ($carrinho as $id => $quantidade) {
                $produto->execute([$id]);
                $dados = $produto->fetch();
                if (!$dados || $dados['quantidade'] < $quantidade) {
                    throw new DomainException('Um produto foi removido ou está sem estoque suficiente. Revise o carrinho.');
                }

                $nome = $dados['nome'];
                $preco = $dados['preco_unitario'];
                $item->execute([$vendaId, $nome, $quantidade, $preco]);
                $estoque->execute([$quantidade, $id]);
            }

            $total = $db->prepare(
                'UPDATE vendas SET total = (SELECT ROUND(SUM(quantidade * preco_unitario), 2) FROM itens_venda WHERE venda_id = ?) WHERE id = ?'
            );
            $total->execute([$vendaId, $vendaId]);
            $db->exec('COMMIT');
            return $vendaId;
        } catch (Throwable $erro) {
            $db->exec('ROLLBACK');
            throw $erro;
        }
    }

    public function buscar($id)
    {
        $stmt = Conexao::abrir()->prepare('SELECT * FROM vendas WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function itens($id)
    {
        $stmt = Conexao::abrir()->prepare('SELECT * FROM itens_venda WHERE venda_id = ? ORDER BY id');
        $stmt->execute([$id]);
        return $stmt->fetchAll();
    }
}
