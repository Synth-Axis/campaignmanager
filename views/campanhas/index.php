<?php require __DIR__ . '/../partials/auth.php'; ?>
<?php require __DIR__ . '/../partials/head.php'; ?>
<?php require __DIR__ . '/../partials/nav.php'; ?>

<main class="min-h-screen bg-light px-6 py-10">
    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-5xl font-semibold text-dark">Campanhas</h1>
            <a href="/campanhas.php?acao=criar"
                class="bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg">Nova</a>
        </div>

        <?php if (empty($agrupado)): ?>
            <p class="text-neutral">Sem campanhas.</p>
        <?php else: ?>
            <?php foreach ($agrupado as $ano => $porTipo): ?>
                <section class="mb-10">
                    <h2 class="text-xl font-semibold text-dark mb-4 flex items-center">
                        <span class="w-2 h-2 bg-success rounded-full mr-3"></span><?= htmlspecialchars($ano) ?>
                    </h2>

                    <?php foreach ($porTipo as $tipoNome => $rows): ?>
                        <div class="mb-6">
                            <h3 class="text-dark/80 font-medium mb-3"><?= htmlspecialchars($tipoNome ?: 'Sem tipo') ?></h3>

                            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
                                <?php foreach ($rows as $c): ?>
                                    <article class="bg-white border border-light rounded-2xl shadow-sm overflow-hidden hover:shadow-md transition">
                                        <?php if ($c['caminho_imagem']): ?>
                                            <img src="<?= htmlspecialchars($c['caminho_imagem']) ?>"
                                                alt="<?= htmlspecialchars($c['nome']) ?>"
                                                class="w-full h-60 object-cover object-top">
                                        <?php else: ?>
                                            <div class="w-full h-40 bg-highlight/30 flex items-center justify-center text-neutral">Sem imagem</div>
                                        <?php endif; ?>

                                        <div class="p-4 space-y-2">
                                            <h4 class="text-dark font-semibold"><?= htmlspecialchars($c['nome']) ?></h4>
                                            <p class="text-sm text-neutral">
                                                <?php
                                                $d1 = $c['data_inicio'] ? date('d M Y', strtotime($c['data_inicio'])) : null;
                                                $d2 = $c['data_fim'] ? date('d M Y', strtotime($c['data_fim'])) : null;
                                                echo $d1 && $d2 ? "$d1 — $d2" : ($d1 ?: ($d2 ?: 'Data por definir'));
                                                ?>
                                            </p>
                                            <?php if (!empty($c['descricao_curta'])): ?>
                                                <p class="text-sm text-dark/80"><?= htmlspecialchars($c['descricao_curta']) ?></p>
                                            <?php endif; ?>
                                        </div>

                                        <div class="px-4 pb-4 flex items-center gap-2">
                                            <a href="/campanhas.php?acao=ver&id=<?= (int)$c['id'] ?>"
                                                class="text-sm bg-dark text-white px-3 py-2 rounded-lg hover:bg-dark/90">Abrir</a>
                                            <?php if (!empty($c['url_teams'])): ?>
                                                <a href="<?= htmlspecialchars($c['url_teams']) ?>" target="_blank" rel="noopener"
                                                    class="text-sm bg-highlight hover:bg-highlight/70 text-dark px-3 py-2 rounded-lg">Abrir no Teams</a>
                                            <?php endif; ?>
                                        </div>
                                    </article>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</main>

<?php require __DIR__ . '/../partials/footer.php'; ?>