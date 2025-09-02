<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center my-10 gap-8 bg-light">

    <!-- Tabs -->
    <ul class="w-full max-w-2xl text-sm font-medium text-center rounded-lg shadow-sm flex overflow-hidden">
        <li class="w-full">
            <a href="#" data-tab="tab-estatisticas"
                class="tab-link inline-block w-full p-4 bg-primary text-white 
            hover:text-dark hover:bg-highlight/20 focus:outline-none">
                Estatísticas
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-campanhas"
                class="tab-link inline-block w-full p-4 bg-white text-neutral
                hover:text-dark hover:bg-highlight/20 focus:outline-none">
                Lista de Campanhas
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-nova-campanha"
                class="tab-link inline-block w-full p-4 bg-white text-neutral rounded-e-lg
                hover:text-dark hover:bg-highlight/20 focus:outline-none">
                Nova Campanha
            </a>
        </li>
    </ul>

    <!-- Estatísticas -->
    <section id="tab-estatisticas"
        class="tab-content w-full max-w-screen-xl px-6 py-8 bg-white rounded-lg shadow-md space-y-6">
        <h2 class="text-xl font-semibold text-dark">Estatísticas Gerais</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                <h3 class="text-2xl font-extrabold text-dark mb-4">Emails Entregues</h3>
                <p class="text-3xl font-bold text-primary"><?= $totalEntregues ?></p>
            </div>
            <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                <h3 class="text-2xl font-extrabold text-dark mb-4">Aberturas</h3>
                <p class="text-3xl font-bold text-primary"><?= $totalAberturas ?></p>
            </div>
            <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                <h3 class="text-2xl font-extrabold text-dark mb-4">Cliques</h3>
                <p class="text-3xl font-bold text-primary"><?= $totalCliques ?></p>
            </div>
        </div>
    </section>

    <!-- Lista de Campanhas -->
    <section id="tab-campanhas"
        class="hidden tab-content w-full max-w-7xl bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-dark">Campanhas de Email</h2>
        </div>

        <?php if (isset($campanhas) && count($campanhas) > 0): ?>
            <table class="w-full text-sm text-left text-dark border-collapse mb-10">
                <thead>
                    <tr class="border-b border-light bg-light">
                        <th class="py-2 px-3">Nome</th>
                        <th class="py-2 px-3">Assunto</th>
                        <th class="py-2 px-3">Data Criação</th>
                        <th class="py-2 px-3">Estado</th>
                        <th class="py-2 px-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campanhas as $campanha): ?>
                        <tr class="border-b border-light hover:bg-highlight/10 transition">
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['nome']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['assunto']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['data_criacao']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['estado']) ?></td>
                            <td class="py-2 px-3 text-right">
                                <a href="controllers/editar_campanha.php?id=<?= $campanha['campaign_id'] ?>"
                                    class="text-primary hover:underline">Ver</a>
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
        class="hidden tab-content w-full max-w-screen-xl mx-10 bg-white border rounded-lg shadow-md p-8">
        <h3 class="text-lg font-semibold text-dark mb-6">Nova Campanha de Email</h3>

        <form id="form-nova-campanha" method="POST" action="campanhas" class="w-full">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-dark mb-1">Nome da Campanha</label>
                <input type="text" name="nome" id="nome" required
                    class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-dark mb-1">Assunto do Email</label>
                <input type="text" name="assunto" id="assunto" required
                    class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <div class="mb-4">
                <label for="lista" class="block text-sm font-medium text-dark mb-1">Lista de Destinatários</label>
                <select name="lista" id="lista" required
                    class="cursor-pointer bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Selecione a lista</option>
                    <?php foreach ($listas as $lista): ?>
                        <option value="<?= htmlspecialchars($lista['lista_id']) ?>"><?= htmlspecialchars($lista['lista_nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-8">
                <label for="estado" class="block text-sm font-medium text-dark mb-1">Estado da Campanha</label>
                <select name="estado" id="estado" required
                    class="cursor-pointer bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="rascunho">Rascunho</option>
                    <option value="agendada">Agendada</option>
                    <option value="enviada">Enviada</option>
                </select>
            </div>

            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 min-w-0">
                    <label for="editor" class="block text-sm font-medium text-dark mb-1">Conteúdo HTML</label>
                    <textarea id="editor" name="html" rows="30"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 font-mono focus:outline-none focus:ring-2 focus:ring-primary/40"
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
                    <label class="block text-sm font-medium text-dark mb-1">Preview</label>
                    <iframe id="html-preview" class="w-full h-5/6 border border-light rounded bg-white"></iframe>
                </div>
            </div>
        </form>
    </section>

</main>

<script src="js/campanhas.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>