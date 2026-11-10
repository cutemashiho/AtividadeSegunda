<?php
session_start();
date_default_timezone_set('America/Cuiaba');

require __DIR__ . '/../models/Conexao.php';
require __DIR__ . '/../models/ProdutoDAO.php';
require __DIR__ . '/../models/ClienteDAO.php';
require __DIR__ . '/../models/Carrinho.php';
require __DIR__ . '/../models/VendaDAO.php';
require __DIR__ . '/../models/RelatorioDAO.php';
require __DIR__ . '/../controllers/funcoes.php';

$telas = [
    'produtos' => 'Produtos',
    'clientes' => 'Clientes',
    'carrinho' => 'Carrinho de Vendas',
    'relatorios' => 'Relatórios de Vendas'
];
$tela = $_GET['tela'] ?? 'produtos';
if (!is_string($tela) || !isset($telas[$tela])) {
    http_response_code(404);
    exit('Tela não encontrada.');
}

if (!isset($_SESSION['token'])) {
    $_SESSION['token'] = bin2hex(random_bytes(16));
}

$mensagem = $_SESSION['mensagem'] ?? '';
$erro = $_SESSION['erro'] ?? '';
unset($_SESSION['mensagem'], $_SESSION['erro']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !hash_equals($_SESSION['token'], texto($_POST, 'token'))) {
    http_response_code(403);
    exit('Formulário inválido. Reabra a tela e tente novamente.');
}

try {
    require __DIR__ . '/../controllers/' . $tela . '.php';
} catch (DomainException $problema) {
    $erro = $problema->getMessage();
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        voltar($tela, '', $erro, $retorno ?? []);
    }
    $view = 'erro';
} catch (PDOException $problema) {
    error_log($problema->getMessage());
    http_response_code(500);
    $erro = 'Falha no banco de dados. Confira a extensão PDO SQLite e a permissão de escrita na pasta do projeto.';
    $view = 'erro';
}

require __DIR__ . '/../views/cabecalho.php';
require __DIR__ . '/../views/' . $view . '.php';
require __DIR__ . '/../views/rodape.php';
