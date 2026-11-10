<h2 class="h4"><?= $produto ? 'Editar produto' : 'Cadastrar produto' ?></h2>
<form method="post" action="index.php?tela=produtos" enctype="multipart/form-data">
    <?php require __DIR__ . '/token.php'; ?>
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="id" value="<?= h($produto['id'] ?? 0) ?>">
    <div class="row">
        <?php foreach (['cod' => 'Código', 'nome' => 'Nome', 'categoria' => 'Categoria'] as $campo => $rotulo): ?>
            <div class="col-md-4 mb-3">
                <label for="<?= h($campo) ?>" class="form-label"><?= h($rotulo) ?></label>
                <input type="text" id="<?= h($campo) ?>" name="<?= h($campo) ?>" class="form-control" maxlength="<?= $campo === 'cod' ? 30 : 100 ?>" value="<?= h($produto[$campo] ?? '') ?>" required>
            </div>
        <?php endforeach; ?>
        <div class="col-md-3 mb-3">
            <label for="validade" class="form-label">Validade</label>
            <input type="date" id="validade" name="validade" class="form-control" value="<?= h($produto['validade'] ?? '') ?>" required>
        </div>
        <div class="col-md-3 mb-3">
            <label for="quantidade" class="form-label">Quantidade</label>
            <input type="number" id="quantidade" name="quantidade" min="0" class="form-control" value="<?= h($produto['quantidade'] ?? '') ?>" required>
        </div>
        <?php foreach (['preco_unitario' => 'Preço unitário', 'preco_pacote' => 'Preço do pacote'] as $campo => $rotulo): ?>
            <div class="col-md-3 mb-3">
                <label for="<?= h($campo) ?>" class="form-label"><?= h($rotulo) ?></label>
                <input type="number" id="<?= h($campo) ?>" name="<?= h($campo) ?>" min="0" max="99999999.99" step="0.01" class="form-control" value="<?= h($produto[$campo] ?? '') ?>" required>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="mb-3">
        <label for="imagem" class="form-label">Imagem opcional (JPG ou PNG)</label>
        <input type="file" id="imagem" name="imagem" class="form-control" accept="image/jpeg,image/png">
    </div>
    <button class="btn btn-success">Salvar produto</button>
    <?php if ($produto): ?><a class="btn btn-secondary" href="index.php?tela=produtos">Cancelar edição</a><?php endif; ?>
</form>

<hr>
<h2 class="h4">Produtos cadastrados</h2>
<form method="get" class="d-flex gap-2 mb-3">
    <input type="hidden" name="tela" value="produtos">
    <input type="search" class="form-control" name="busca" aria-label="Buscar produto" placeholder="Nome ou código" value="<?= h($busca) ?>">
    <button class="btn btn-primary">Buscar</button>
</form>
<?php if (!$produtos): ?><p>Nenhum produto encontrado.</p><?php endif; ?>
<div class="table-responsive">
<table class="table align-middle">
    <thead><tr><th>Imagem</th><th>Código</th><th>Nome</th><th>Estoque</th><th>Preço unitário</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($produtos as $linha): ?>
        <tr>
            <td><?php if ($linha['imagem']): ?><img src="imagens/<?= h(rawurlencode(basename($linha['imagem']))) ?>" width="50" alt="<?= h($linha['nome']) ?>"><?php endif; ?></td>
            <td><?= h($linha['cod']) ?></td>
            <td><?= h($linha['nome']) ?></td>
            <td><?= h($linha['quantidade']) ?></td>
            <td><?= reais($linha['preco_unitario']) ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="index.php?tela=produtos&amp;editar=<?= h($linha['id']) ?>">Editar</a>
                <form class="d-inline" method="post" action="index.php?tela=produtos" onsubmit="return confirm('Excluir este produto?')">
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
