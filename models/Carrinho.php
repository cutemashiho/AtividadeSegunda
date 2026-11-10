<?php
class Carrinho
{
    public function listar()
    {
        return $_SESSION['carrinho'] ?? [];
    }

    public function adicionar($id, $quantidade, $estoque)
    {
        $atual = $this->listar()[$id] ?? 0;
        $this->atualizar($id, $atual + $quantidade, $estoque);
    }

    public function atualizar($id, $quantidade, $estoque)
    {
        if ($quantidade < 1 || $quantidade > $estoque) {
            throw new DomainException('Informe uma quantidade válida para o estoque disponível.');
        }
        $_SESSION['carrinho'][$id] = $quantidade;
    }

    public function excluir($id)
    {
        unset($_SESSION['carrinho'][$id]);
    }

    public function limpar()
    {
        unset($_SESSION['carrinho']);
    }
}
