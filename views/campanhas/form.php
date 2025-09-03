<?php
$isEdit = isset($campanha);
$acao = $isEdit ? '/campanhas.php?acao=atualizar' : '/campanhas.php?acao=guardar';
?>
<?php require __DIR__ . '/../partials/auth.php'; ?>
<?php require __DIR__ . '/../partials/head.php'; ?>
<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="min-h-screen bg-light px-6 py-10">
    <div class="max-w-3xl mx-auto">
        <a href="/campanhas.php" class="text-primary hover:underline">&larr; Voltar</a>
        <h1 class="text-2xl font-semibold text-dark mt-4 mb-6">
            <?= $isEdit ? 'Editar Campanha' : 'Nova Campanha' ?>
        </h1>

        <form action="<?= $acao ?>" method="post" enctype="multipart/form-data"
            class="bg-white border border-light rounded-2xl p-6 space-y-5">
            <?php if ($isEdit): ?>
                <input type="hidden" name="id" value="<?= (int)$campanha['id'] ?>">
            <?php endif; ?>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm text-neutral mb-1">Nome</label>
                    <input name="nome" required value="<?= htmlspecialchars($campanha['nome'] ?? '') ?>"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>

                <div>
                    <label class="block text-sm text-neutral mb-1">Ano</label>
                    <input name="ano" type="number" min="2000" max="2100" required
                        value="<?= htmlspecialchars($campanha['ano'] ?? date('Y')) ?>"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>

                <div>
                    <label class="block text-sm text-neutral mb-1">Tipo</label>
                    <select name="tipo_id" required
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                        <option value="" disabled <?= empty($campanha['tipo_id']) ? 'selected' : '' ?>>Selecionar…</option>
                        <?php foreach ($tipos as $t): ?>
                            <option value="<?= (int)$t['id'] ?>"
                                <?= (!empty($campanha['tipo_id']) && $campanha['tipo_id'] == $t['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label class="block text-sm text-neutral mb-1">Link do Teams</label>
                    <input name="url_teams" type="url" placeholder="https://..."
                        value="<?= htmlspecialchars($campanha['url_teams'] ?? '') ?>"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>

                <div>
                    <label class="block text-sm text-neutral mb-1">Início</label>
                    <input name="data_inicio" type="date" value="<?= htmlspecialchars($campanha['data_inicio'] ?? '') ?>"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>
                <div>
                    <label class="block text-sm text-neutral mb-1">Fim</label>
                    <input name="data_fim" type="date" value="<?= htmlspecialchars($campanha['data_fim'] ?? '') ?>"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>
            </div>

            <div>
                <label class="block text-sm text-neutral mb-1">Descrição curta</label>
                <textarea name="descricao_curta" rows="3"
                    class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30"><?= htmlspecialchars($campanha['descricao_curta'] ?? '') ?></textarea>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 items-center">
                <div>
                    <label class="block text-sm text-neutral mb-1">Imagem da campanha</label>
                    <input type="file" name="imagem" accept="image/*"
                        class="w-full bg-light border border-light rounded-lg px-3 py-2 focus:ring-2 focus:ring-primary/30">
                </div>
                <?php if (!empty($campanha['caminho_imagem'])): ?>
                    <img src="<?= htmlspecialchars($campanha['caminho_imagem']) ?>" class="h-28 object-cover rounded-lg border border-light">
                <?php endif; ?>
            </div>

            <div class="flex gap-3">
                <button class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg">
                    <?= $isEdit ? 'Guardar Alterações' : 'Criar' ?>
                </button>

                <?php if ($isEdit): ?>
                    <form action="/campanhas.php?acao=remover" method="post" onsubmit="return confirm('Apagar esta campanha?');">
                        <input type="hidden" name="id" value="<?= (int)$campanha['id'] ?>">
                        <button class="bg-danger hover:bg-danger/90 text-white px-4 py-2 rounded-lg" type="submit">Apagar</button>
                    </form>
                <?php endif; ?>
            </div>
        </form>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>