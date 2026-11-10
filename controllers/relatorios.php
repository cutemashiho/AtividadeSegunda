<?php
$dao = new RelatorioDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = inteiro($_POST, 'id', 0);
    $acao = texto($_POST, 'acao');

    if ($acao === 'excluir') {
        if (!$id || !$dao->excluir($id)) {
            throw new DomainException('Relatório não encontrado.');
        }
        voltar($tela, 'Relatório excluído.');
    }

    if ($acao !== 'salvar' || $id === false || ($id && !$dao->buscar($id))) {
        throw new DomainException('Dados inválidos ou relatório não encontrado.');
    }
    $retorno = $id ? ['editar' => $id] : [];
    $titulo = texto($_POST, 'titulo');
    $inicio = texto($_POST, 'inicio');
    $fim = texto($_POST, 'fim');
    if (!campoValido($titulo, 100) || !dataValida($inicio) || !dataValida($fim) || $inicio > $fim) {
        throw new DomainException('Informe o título e um período válido. A data inicial deve vir antes da final ou ser igual a ela.');
    }
    $dao->salvar($id, $titulo, $inicio, $fim);
    voltar($tela, 'Relatório salvo.');
}

$relatorio = [];
$resultado = null;
$venda = null;
if (isset($_GET['editar'])) {
    $relatorio = $dao->buscar(inteiro($_GET, 'editar'));
    if (!$relatorio) {
        throw new DomainException('Relatório não encontrado.');
    }
}
if (isset($_GET['ver'])) {
    $resultado = $dao->buscar(inteiro($_GET, 'ver'));
    if (!$resultado) {
        throw new DomainException('Relatório não encontrado.');
    }
    $vendas = $dao->vendasPorPeriodo($resultado['inicio'], $resultado['fim']);
    $total = array_sum(array_column($vendas, 'total'));
}
if (isset($_GET['venda'])) {
    $vendaDAO = new VendaDAO();
    $venda = $vendaDAO->buscar(inteiro($_GET, 'venda'));
    if (!$venda) {
        throw new DomainException('Venda não encontrada.');
    }
    $itens = $vendaDAO->itens($venda['id']);
}
$relatorios = $dao->listar();
$view = 'relatorios';
