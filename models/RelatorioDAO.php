<?php
class RelatorioDAO
{
    public function listar()
    {
        return Conexao::abrir()->query('SELECT * FROM relatorios ORDER BY id DESC')->fetchAll();
    }

    public function buscar($id)
    {
        $stmt = Conexao::abrir()->prepare('SELECT * FROM relatorios WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function salvar($id, $titulo, $inicio, $fim)
    {
        $db = Conexao::abrir();
        if ($id) {
            $stmt = $db->prepare('UPDATE relatorios SET titulo = ?, inicio = ?, fim = ? WHERE id = ?');
            $stmt->execute([$titulo, $inicio, $fim, $id]);
        } else {
            $stmt = $db->prepare('INSERT INTO relatorios (titulo, inicio, fim) VALUES (?, ?, ?)');
            $stmt->execute([$titulo, $inicio, $fim]);
        }
    }

    public function excluir($id)
    {
        $stmt = Conexao::abrir()->prepare('DELETE FROM relatorios WHERE id = ?');
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }

    public function vendasPorPeriodo($inicio, $fim)
    {
        $stmt = Conexao::abrir()->prepare(
            'SELECT * FROM vendas WHERE data_venda BETWEEN ? AND ? ORDER BY data_venda DESC, id DESC'
        );
        $stmt->execute([$inicio, $fim]);
        return $stmt->fetchAll();
    }
}
