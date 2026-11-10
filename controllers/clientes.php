<?php
$dao = new ClienteDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = inteiro($_POST, 'id', 0);
    $acao = texto($_POST, 'acao');

    if ($acao === 'excluir') {
        if (!$id || !$dao->excluir($id)) {
            throw new DomainException('Cliente não encontrado.');
        }
        voltar($tela, 'Cliente excluído.');
    }

    if ($acao !== 'salvar' || $id === false || ($id && !$dao->buscar($id))) {
        throw new DomainException('Cadastro inválido ou cliente não encontrado.');
    }
    $retorno = $id ? ['editar' => $id] : [];
    $nome = texto($_POST, 'nome');
    $email = texto($_POST, 'email');
    $telefone = texto($_POST, 'telefone');
    if (!campoValido($nome, 100) || !campoValido($email, 120) ||
        !filter_var($email, FILTER_VALIDATE_EMAIL) || !campoValido($telefone, 25)) {
        throw new DomainException('Preencha o nome, um e-mail válido e o telefone.');
    }

    if ($id) {
        $dao->atualizar($id, $nome, $email, $telefone);
    } else {
        $dao->inserir($nome, $email, $telefone);
    }
    voltar($tela, 'Cliente salvo.');
}

$cliente = [];
if (isset($_GET['editar'])) {
    $cliente = $dao->buscar(inteiro($_GET, 'editar'));
    if (!$cliente) {
        throw new DomainException('Cliente não encontrado.');
    }
}
$clientes = $dao->listar();
$view = 'clientes';
