<h2 class="h4">Adicionar produto</h2>
<form method="post" action="index.php?tela=carrinho" class="row g-3 align-items-end">
    <?php require __DIR__ . '/token.php'; ?>
    <div class="col-md-7">
        <label for="produto" class="form-label">Produto</label>
        <select id="produto" name="id" class="form-select" required>
            <option value="">Selecione</option>
            <?php foreach ($produtos as $produto): ?>
                <option value="<?= h($produto['id']) ?>" <?= $produto['quantidade'] < 1 ? 'disabled' : '' ?>>
                    <?= h($produto['cod']) ?> · <?= h($produto['nome']) ?> · <?= reais($produto['preco_unitario']) ?> (estoque <?= h($produto['quantidade']) ?>)
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label for="quantidade" class="form-label">Quantidade</label>
        <input type="number" id="quantidade" name="quantidade" class="form-control" min="1" value="1" required>
    </div>
    <div class="col-md-2"><button class="btn btn-primary" name="acao" value="adicionar">Adicionar</button></div>
</form>

<hr>
<h2 class="h4">Itens do carrinho</h2>
<?php if (!$itens): ?><p>O carrinho está vazio.</p><?php endif; ?>
<div class="table-responsive">
<table class="table align-middle">
    <thead><tr><th>Produto</th><th>Preço unitário</th><th>Subtotal</th><th>Quantidade e ações</th></tr></thead>
    <tbody>
    <?php foreach ($itens as $id => $item): ?>
        <tr>
            <td><?= h($item['produto']['nome'] ?? 'Produto removido do cadastro') ?></td>
            <td><?= $item['produto'] ? reais($item['produto']['preco_unitario']) : '—' ?></td>
            <td><?= $item['produto'] ? reais($item['subtotal']) : '—' ?></td>
            <td>
                <form method="post" action="index.php?tela=carrinho" class="d-flex gap-2 flex-wrap">
                    <?php require __DIR__ . '/token.php'; ?>
                    <input type="hidden" name="id" value="<?= h($id) ?>">
                    <input type="number" name="quantidade" aria-label="Quantidade do item" min="1" class="form-control form-control-sm w-auto" value="<?= h($item['quantidade']) ?>" required <?= $item['produto'] ? '' : 'disabled' ?>>
                    <button class="btn btn-warning btn-sm" name="acao" value="atualizar" <?= $item['produto'] ? '' : 'disabled' ?>>Atualizar</button>
                    <button class="btn btn-danger btn-sm" name="acao" value="excluir" formnovalidate>Remover</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</div>
<p class="fw-bold">Total do carrinho: <?= reais($total) ?></p>

<?php if ($itens): ?>
    <form method="post" action="index.php?tela=carrinho">
        <?php require __DIR__ . '/token.php'; ?>
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente da venda</label>
            <select id="cliente" name="cliente" class="form-select">
                <option value="0">Consumidor final</option>
                <?php foreach ($clientes as $cliente): ?>
                    <option value="<?= h($cliente['id']) ?>"><?= h($cliente['nome']) ?> · #<?= h($cliente['id']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button class="btn btn-success" name="acao" value="finalizar">Finalizar venda</button>
    </form>
<?php endif; ?>
