<?php require __DIR__ . '/../partials/auth.php'; ?>
<?php require __DIR__ . '/../partials/head.php'; ?>
<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="min-h-screen bg-light px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <a href="/campanhas.php" class="text-primary hover:underline">&larr; Voltar</a>

        <header class="mt-4 mb-6">
            <h1 class="text-3xl font-semibold text-dark"><?= htmlspecialchars($campanha['nome']) ?></h1>
            <p class="text-neutral mt-1">
                <?= htmlspecialchars($campanha['tipo_nome']) ?> • <?= (int)$campanha['ano'] ?>
            </p>
            <p class="text-dark/80 mt-1">
                <?php
                $d1 = $campanha['data_inicio'] ? date('d M Y', strtotime($campanha['data_inicio'])) : null;
                $d2 = $campanha['data_fim'] ? date('d M Y', strtotime($campanha['data_fim'])) : null;
                echo $d1 && $d2 ? "$d1 — $d2" : ($d1 ?: ($d2 ?: 'Data por definir'));
                ?>
            </p>
        </header>

        <section class="grid lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-white border border-light rounded-2xl overflow-hidden">
                <?php if ($campanha['caminho_imagem']): ?>
                    <img src="<?= htmlspecialchars($campanha['caminho_imagem']) ?>" alt="<?= htmlspecialchars($campanha['nome']) ?>"
                        class="w-full h-100 object-cover">
                <?php else: ?>
                    <div class="w-full h-100 bg-highlight/30 flex items-center justify-center text-neutral">Sem imagem</div>
                <?php endif; ?>
            </div>

            <aside class="bg-white border border-light rounded-2xl p-5 space-y-4">
                <?php if (!empty($campanha['descricao_curta'])): ?>
                    <p class="text-dark/80"><?= nl2br(htmlspecialchars($campanha['descricao_curta'])) ?></p>
                <?php else: ?>
                    <p class="text-neutral">Sem descrição.</p>
                <?php endif; ?>

                <div class="flex flex-col gap-2 pt-2">
                    <?php if (!empty($campanha['url_teams'])): ?>
                        <a href="<?= htmlspecialchars($campanha['url_teams']) ?>" target="_blank" rel="noopener"
                            class="bg-primary hover:bg-primary/90 text-white text-center px-4 py-2 rounded-lg">Abrir pasta no Teams</a>
                    <?php endif; ?>
                    <a href="/campanhas.php?acao=editar&id=<?= (int)$campanha['id'] ?>"
                        class="bg-dark hover:bg-dark/90 text-white text-center px-4 py-2 rounded-lg">Editar</a>
                </div>
            </aside>
        </section>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>