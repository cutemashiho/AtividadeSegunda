<?php
$carrinho = new Carrinho();
$produtoDAO = new ProdutoDAO();
$clienteDAO = new ClienteDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = texto($_POST, 'acao');
    $id = inteiro($_POST, 'id');

    if ($acao === 'excluir' && $id) {
        $carrinho->excluir($id);
        voltar($tela, 'Item removido.');
    }

    if ($acao === 'adicionar' || $acao === 'atualizar') {
        $quantidade = inteiro($_POST, 'quantidade');
        $produto = $id ? $produtoDAO->buscar($id) : null;
        if (!$produto || !$quantidade) {
            throw new DomainException('Selecione um produto e informe uma quantidade inteira maior que zero.');
        }
        if ($acao === 'adicionar') {
            $carrinho->adicionar($id, $quantidade, $produto['quantidade']);
        } else {
            if (!isset($carrinho->listar()[$id])) {
                throw new DomainException('O item não está no carrinho.');
            }
            $carrinho->atualizar($id, $quantidade, $produto['quantidade']);
        }
        voltar($tela, 'Carrinho atualizado.');
    }

    if ($acao === 'finalizar') {
        $itens = $carrinho->listar();
        if (!$itens) {
            throw new DomainException('Adicione pelo menos um item antes de finalizar.');
        }
        $idCliente = inteiro($_POST, 'cliente', 0);
        $cliente = $idCliente ? $clienteDAO->buscar($idCliente) : null;
        if ($idCliente === false || ($idCliente && !$cliente)) {
            throw new DomainException('Selecione um cliente válido.');
        }

        $vendaDAO = new VendaDAO();
        $numero = $vendaDAO->registrar($cliente['nome'] ?? 'Consumidor final', $itens);
        $carrinho->limpar();
        voltar('relatorios', 'Venda registrada com sucesso.', '', ['venda' => $numero]);
    }

    throw new DomainException('Operação inválida.');
}

$produtos = $produtoDAO->listar();
$clientes = $clienteDAO->listar();
$itens = [];
$total = 0;
foreach ($carrinho->listar() as $id => $quantidade) {
    $produto = $produtoDAO->buscar($id);
    $subtotal = $produto ? $produto['preco_unitario'] * $quantidade : 0;
    $itens[$id] = ['produto' => $produto, 'quantidade' => $quantidade, 'subtotal' => $subtotal];
    $total += $subtotal;
}
$view = 'carrinho';
