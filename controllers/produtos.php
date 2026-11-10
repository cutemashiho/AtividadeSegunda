<?php
$dao = new ProdutoDAO();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = inteiro($_POST, 'id', 0);
    $acao = texto($_POST, 'acao');

    if ($acao === 'excluir') {
        if (!$id || !$dao->excluir($id)) {
            throw new DomainException('Produto não encontrado.');
        }
        voltar($tela, 'Produto excluído.');
    }

    $atual = $id ? $dao->buscar($id) : [];
    if ($acao !== 'salvar' || $id === false || ($id && !$atual)) {
        throw new DomainException('Cadastro inválido ou produto não encontrado.');
    }
    $retorno = $id ? ['editar' => $id] : [];
    $cod = texto($_POST, 'cod');
    $nome = texto($_POST, 'nome');
    $categoria = texto($_POST, 'categoria');
    $validade = texto($_POST, 'validade');
    $quantidade = inteiro($_POST, 'quantidade', 0);
    $unitario = texto($_POST, 'preco_unitario');
    $pacote = texto($_POST, 'preco_pacote');
    $formatoPreco = '/^\d{1,8}(\.\d{1,2})?$/D';

    if (!campoValido($cod, 30) || !campoValido($nome, 100) || !campoValido($categoria, 100) ||
        !dataValida($validade) || $quantidade === false ||
        !preg_match($formatoPreco, $unitario) || !preg_match($formatoPreco, $pacote)) {
        throw new DomainException('Confira os dados do produto, a validade, o estoque e os preços.');
    }

    $imagem = $atual['imagem'] ?? '';
    $arquivo = $_FILES['imagem'] ?? ['error' => UPLOAD_ERR_NO_FILE];
    $novoArquivo = '';
    if ($arquivo['error'] !== UPLOAD_ERR_NO_FILE) {
        if ($arquivo['error'] !== UPLOAD_ERR_OK || $arquivo['size'] > 2000000) {
            throw new DomainException('Envie uma imagem de até 2 MB.');
        }
        $tipos = [IMAGETYPE_JPEG => '.jpg', IMAGETYPE_PNG => '.png'];
        $dadosImagem = @getimagesize($arquivo['tmp_name']);
        $tipo = $dadosImagem[2] ?? 0;
        if (!isset($tipos[$tipo])) {
            throw new DomainException('Escolha uma imagem JPG ou PNG.');
        }
        $imagem = 'produto-' . bin2hex(random_bytes(8)) . $tipos[$tipo];
        $novoArquivo = __DIR__ . '/../public/imagens/' . $imagem;
        if (!move_uploaded_file($arquivo['tmp_name'], $novoArquivo)) {
            throw new DomainException('Falha ao salvar a imagem. Confira a permissão da pasta imagens.');
        }
    }

    try {
        $dao->salvar($id, $cod, $nome, $categoria, $validade, $quantidade, $unitario, $pacote, $imagem);
    } catch (PDOException $erroBanco) {
        if ($novoArquivo !== '') {
            unlink($novoArquivo);
        }
        throw $erroBanco;
    }
    voltar($tela, 'Produto salvo.');
}

$produto = [];
if (isset($_GET['editar'])) {
    $produto = $dao->buscar(inteiro($_GET, 'editar'));
    if (!$produto) {
        throw new DomainException('Produto não encontrado.');
    }
}
$busca = texto($_GET, 'busca');
$produtos = $dao->listar($busca);
$view = 'produtos';
