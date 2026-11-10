<?php if ($venda): ?>
    <section class="border rounded p-3 mb-4">
        <h2 class="h4">Venda #<?= h($venda['id']) ?></h2>
        <p><?= h(date('d/m/Y', strtotime($venda['data_venda']))) ?> · <?= h($venda['cliente_nome']) ?></p>
        <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Produto</th><th>Quantidade</th><th>Preço unitário</th><th>Subtotal</th></tr></thead>
            <tbody>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= h($item['produto_nome']) ?></td>
                    <td><?= h($item['quantidade']) ?></td>
                    <td><?= reais($item['preco_unitario']) ?></td>
                    <td><?= reais($item['quantidade'] * $item['preco_unitario']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p class="fw-bold">Total da venda: <?= reais($venda['total']) ?></p>
    </section>
<?php endif; ?>

<?php if ($resultado): ?>
    <section class="border rounded p-3 mb-4">
        <h2 class="h4"><?= h($resultado['titulo']) ?></h2>
        <p>Período de <?= h(date('d/m/Y', strtotime($resultado['inicio']))) ?> a <?= h(date('d/m/Y', strtotime($resultado['fim']))) ?></p>
        <?php if (!$vendas): ?><p>Nenhuma venda neste período.</p><?php endif; ?>
        <div class="table-responsive">
        <table class="table">
            <thead><tr><th>Venda</th><th>Data</th><th>Cliente</th><th>Total</th></tr></thead>
            <tbody>
            <?php foreach ($vendas as $linha): ?>
                <tr>
                    <td><a href="index.php?tela=relatorios&amp;ver=<?= h($resultado['id']) ?>&amp;venda=<?= h($linha['id']) ?>">#<?= h($linha['id']) ?></a></td>
                    <td><?= h(date('d/m/Y', strtotime($linha['data_venda']))) ?></td>
                    <td><?= h($linha['cliente_nome']) ?></td>
                    <td><?= reais($linha['total']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <p class="fw-bold mb-0">Vendas no período: <?= count($vendas) ?> · Total: <?= reais($total) ?></p>
    </section>
<?php endif; ?>

<h2 class="h4"><?= $relatorio ? 'Editar relatório' : 'Criar relatório por período' ?></h2>
<form method="post" action="index.php?tela=relatorios">
    <?php require __DIR__ . '/token.php'; ?>
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="id" value="<?= h($relatorio['id'] ?? 0) ?>">
    <div class="mb-3">
        <label for="titulo" class="form-label">Título do relatório</label>
        <input type="text" id="titulo" name="titulo" class="form-control" maxlength="100" value="<?= h($relatorio['titulo'] ?? '') ?>" required>
    </div>
    <div class="row mb-3">
        <div class="col-sm-6">
            <label for="inicio" class="form-label">Data inicial</label>
            <input type="date" id="inicio" name="inicio" class="form-control" value="<?= h($relatorio['inicio'] ?? date('Y-m-d')) ?>" required>
        </div>
        <div class="col-sm-6">
            <label for="fim" class="form-label">Data final</label>
            <input type="date" id="fim" name="fim" class="form-control" value="<?= h($relatorio['fim'] ?? date('Y-m-d')) ?>" required>
        </div>
    </div>
    <button class="btn btn-success">Salvar relatório</button>
    <?php if ($relatorio): ?><a class="btn btn-secondary" href="index.php?tela=relatorios">Cancelar edição</a><?php endif; ?>
</form>

<hr>
<h2 class="h4">Relatórios salvos</h2>
<?php if (!$relatorios): ?><p>Nenhum relatório salvo.</p><?php endif; ?>
<div class="table-responsive">
<table class="table align-middle">
    <thead><tr><th>Título</th><th>Início</th><th>Fim</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($relatorios as $linha): ?>
        <tr>
            <td><?= h($linha['titulo']) ?></td>
            <td><?= h(date('d/m/Y', strtotime($linha['inicio']))) ?></td>
            <td><?= h(date('d/m/Y', strtotime($linha['fim']))) ?></td>
            <td>
                <a class="btn btn-primary btn-sm" href="index.php?tela=relatorios&amp;ver=<?= h($linha['id']) ?>">Consultar</a>
                <a class="btn btn-warning btn-sm" href="index.php?tela=relatorios&amp;editar=<?= h($linha['id']) ?>">Editar</a>
                <form method="post" action="index.php?tela=relatorios" class="d-inline" onsubmit="return confirm('Excluir este relatório salvo?')">
                    <?php require __DIR__ . '/token.php'; ?>
                    <input type="hidden" name="id" value="<?= h($linha['id']) ?>">
                    <button class="btn btn-danger btn-sm" name="acao" value="excluir">Excluir</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
