<?php require view_path('layouts/start.php'); ?>

<main class="min-h-screen flex flex-col items-center my-10 gap-8 bg-light">


    <!-- Tabs -->
    <ul class="campaign-tabs w-full max-w-2xl text-sm font-medium text-center rounded-lg shadow-sm flex overflow-hidden"
        role="tablist" aria-label="Secções de campanhas">
        <li role="presentation">
            <a href="#tab-estatisticas" id="trigger-estatisticas" data-tab="tab-estatisticas"
                role="tab" aria-selected="true" aria-controls="tab-estatisticas" tabindex="0"
                class="tab-link campaign-tab">
                Estatísticas
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-campanhas" id="trigger-campanhas" data-tab="tab-campanhas"
                role="tab" aria-selected="false" aria-controls="tab-campanhas" tabindex="-1"
                class="tab-link campaign-tab">
                Lista de Campanhas
            </a>
        </li>
        <li role="presentation">
            <a href="#tab-nova-campanha" id="trigger-nova-campanha" data-tab="tab-nova-campanha"
                role="tab" aria-selected="false" aria-controls="tab-nova-campanha" tabindex="-1"
                class="tab-link campaign-tab">
                Nova Campanha
            </a>
        </li>
    </ul>

    <!-- Estatísticas -->
    <section id="tab-estatisticas"
        role="tabpanel" aria-labelledby="trigger-estatisticas" tabindex="0"
        class="tab-content p-6 w-full max-w-6xl mx-auto bg-surface rounded-lg shadow-md space-y-6">
        <h2 class="text-xl font-semibold text-ink">Estatísticas Gerais</h2>

        <div class="grid grid-cols-3 gap-8">
            <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                <h3 class="text-2xl font-extrabold text-ink mb-4">Emails Entregues</h3>
                <p class="text-3xl font-bold text-accent"><?= $totalEntregues ?></p>
            </div>
            <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                <h3 class="text-2xl font-extrabold text-ink mb-4">Aberturas</h3>
                <p class="text-3xl font-bold text-accent"><?= $totalAberturas ?></p>
            </div>
            <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                <h3 class="text-2xl font-extrabold text-ink mb-4">Cliques</h3>
                <p class="text-3xl font-bold text-accent"><?= $totalCliques ?></p>
            </div>
        </div>
    </section>

    <!-- Lista de Campanhas -->
    <section id="tab-campanhas"
        role="tabpanel" aria-labelledby="trigger-campanhas" tabindex="0"
        class="hidden tab-content w-full max-w-6xl bg-surface rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-ink">Campanhas de Email</h2>
        </div>

        <?php if (isset($campanhas) && count($campanhas) > 0): ?>
            <table class="w-full text-sm text-left text-ink border-collapse mb-10">
                <thead>
                    <tr class="border-b border-line bg-light">
                        <th class="py-2 px-3">Nome</th>
                        <th class="py-2 px-3">Assunto</th>
                        <th class="py-2 px-3">Data Criação</th>
                        <th class="py-2 px-3">Estado</th>
                        <th class="py-2 px-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campanhas as $campanha): ?>
                        <tr class="border-b border-line hover:bg-highlight/10 transition">
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['nome']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['assunto']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['data_criacao']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['estado']) ?></td>
                            <td class="py-2 px-3 text-center">
                                <a href="/campanhas/editar?id=<?= $campanha['campaign_id'] ?>"
                                    class="table-action-link" aria-label="<?= htmlspecialchars('Ver ' . $campanha['nome'], ENT_QUOTES, 'UTF-8') ?>">
                                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" /><circle cx="12" cy="12" r="3" /></svg>
                                    Ver
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-neutral mb-8">Ainda não existem campanhas criadas.</div>
        <?php endif; ?>
    </section>

    <!-- Nova Campanha -->
    <section id="tab-nova-campanha"
        role="tabpanel" aria-labelledby="trigger-nova-campanha" tabindex="0"
        class="hidden tab-content w-full max-w-6xl bg-surface border rounded-lg shadow-md p-8">
        <h3 class="text-lg font-semibold text-ink mb-6">Nova Campanha de Email</h3>

        <form id="form-nova-campanha" method="POST" action="campanhas" class="w-full">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-ink mb-1">Nome da Campanha</label>
                <input type="text" name="nome" id="nome" required
                    class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-ink mb-1">Assunto do Email</label>
                <input type="text" name="assunto" id="assunto" required
                    class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <div class="mb-4">
                <label for="lista" class="block text-sm font-medium text-ink mb-1">Lista de Destinatários</label>
                <select name="lista" id="lista" required
                    class="cursor-pointer bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Selecione a lista</option>
                    <?php foreach ($listas as $lista): ?>
                        <option value="<?= htmlspecialchars($lista['lista_id']) ?>"><?= htmlspecialchars($lista['lista_nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-8">
                <label for="estado" class="block text-sm font-medium text-ink mb-1">Estado da Campanha</label>
                <select name="estado" id="estado" required
                    class="cursor-pointer bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="rascunho">Rascunho</option>
                    <option value="agendada">Agendada</option>
                    <option value="enviada">Enviada</option>
                </select>
            </div>

            <div class="flex flex-row gap-8">
                <div class="flex-1 min-w-0">
                    <label for="editor" class="block text-sm font-medium text-ink mb-1">Conteúdo HTML</label>
                    <textarea id="editor" name="html" rows="30"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 font-mono focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Cole ou edite aqui o HTML do email"></textarea>

                    <div class="flex flex-wrap gap-3 mt-3">
                        <button type="button" id="preview-btn"
                            class="cursor-pointer px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">
                            Ver Preview
                        </button>
                        <button type="submit" name="action" value="gravar"
                            class="cursor-pointer px-5 py-2 text-white bg-primary rounded hover:bg-primary/90 font-medium">
                            Guardar Campanha
                        </button>
                        <button type="submit" name="action" value="enviar"
                            class="cursor-pointer px-5 py-2 text-white bg-success rounded hover:bg-success/90 font-medium">
                            Enviar Agora
                        </button>
                    </div>
                </div>

                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-ink mb-1">Preview</label>
                    <iframe id="html-preview" class="w-full h-5/6 border border-line rounded bg-surface"></iframe>
                </div>
            </div>
        </form>
    </section>

</main>


<?php require view_path('layouts/footer.php'); ?>
