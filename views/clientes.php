<h2 class="h4"><?= $cliente ? 'Editar cliente' : 'Cadastrar cliente' ?></h2>
<form method="post" action="index.php?tela=clientes">
    <?php require __DIR__ . '/token.php'; ?>
    <input type="hidden" name="acao" value="salvar">
    <input type="hidden" name="id" value="<?= h($cliente['id'] ?? 0) ?>">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome</label>
        <input type="text" id="nome" name="nome" class="form-control" maxlength="100" value="<?= h($cliente['nome'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" id="email" name="email" class="form-control" maxlength="120" value="<?= h($cliente['email'] ?? '') ?>" required>
    </div>
    <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" id="telefone" name="telefone" class="form-control" maxlength="25" value="<?= h($cliente['telefone'] ?? '') ?>" required>
    </div>
    <button class="btn btn-success">Salvar cliente</button>
    <?php if ($cliente): ?><a class="btn btn-secondary" href="index.php?tela=clientes">Cancelar edição</a><?php endif; ?>
</form>

<hr>
<h2 class="h4">Clientes cadastrados</h2>
<?php if (!$clientes): ?><p>Nenhum cliente cadastrado.</p><?php endif; ?>
<div class="table-responsive">
<table class="table align-middle">
    <thead><tr><th>Nome</th><th>E-mail</th><th>Telefone</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($clientes as $linha): ?>
        <tr>
            <td><?= h($linha['nome']) ?></td>
            <td><?= h($linha['email']) ?></td>
            <td><?= h($linha['telefone']) ?></td>
            <td>
                <a class="btn btn-warning btn-sm" href="index.php?tela=clientes&amp;editar=<?= h($linha['id']) ?>">Editar</a>
                <form class="d-inline" method="post" action="index.php?tela=clientes" onsubmit="return confirm('Excluir este cliente?')">
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
