<?php
function h($valor)
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

function reais($valor)
{
    return 'R$ ' . number_format($valor, 2, ',', '.');
}
?>
<!doctype html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= h($telas[$tela]) ?> | PDV</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4 mb-5">
    <nav class="nav nav-pills gap-2 border-bottom pb-3 mb-4" aria-label="Menu do PDV">
        <?php foreach ($telas as $chave => $rotulo): ?>
            <a class="nav-link <?= $tela === $chave ? 'active' : '' ?>" href="index.php?tela=<?= h($chave) ?>"><?= h($rotulo) ?></a>
        <?php endforeach; ?>
    </nav>
    <h1 class="text-center mb-4"><?= h($telas[$tela]) ?></h1>
    <?php if ($mensagem): ?><p class="alert alert-success" role="status"><?= h($mensagem) ?></p><?php endif; ?>
    <?php if ($erro): ?><p class="alert alert-danger" role="alert"><?= h($erro) ?></p><?php endif; ?>
