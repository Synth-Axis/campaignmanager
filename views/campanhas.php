<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center my-10 gap-8">

    <!-- TABS PRINCIPAIS -->
    <ul class="w-full max-w-2xl text-sm font-medium text-center text-gray-500 rounded-lg shadow-sm flex dark:divide-gray-700 dark:text-gray-400 mb-8">
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-campanhas" class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-blue-700 font-semibold bg-blue-50 dark:bg-gray-700 border-r border-gray-200 dark:border-gray-700 rounded-s-lg hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Lista de Campanhas
            </a>
        </li>
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-nova-campanha" class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-e-lg hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Nova Campanha
            </a>
        </li>
    </ul>

    <!-- TAB: Campanhas -->
    <section id="tab-campanhas" class="tab-content w-full max-w-7xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Campanhas de Email</h2>
        </div>
        <?php if (isset($campanhas) && count($campanhas) > 0): ?>
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 border-collapse mb-10">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <th class="py-2 px-3">Nome</th>
                        <th class="py-2 px-3">Assunto</th>
                        <th class="py-2 px-3">Data Criação</th>
                        <th class="py-2 px-3">Estado</th>
                        <th class="py-2 px-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($campanhas as $campanha): ?>
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['nome']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['assunto']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['data_criacao']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($campanha['estado']) ?></td>
                            <td class="py-2 px-3 text-right">
                                <a href="controllers/editar_campanha.php?id=<?= $campanha['campaign_id'] ?>" class="text-blue-600 hover:underline">Ver</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="text-gray-500 dark:text-gray-400 mb-8">Ainda não existem campanhas criadas.</div>
        <?php endif; ?>
    </section>

    <!-- TAB: Nova Campanha -->
    <section id="tab-nova-campanha" class="hidden tab-content w-full max-w-7xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-8">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Nova Campanha de Email</h3>
        <form id="form-nova-campanha" method="POST" action="campanhas" class="w-full">
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Nome da Campanha</label>
                <input type="text" name="nome" id="nome" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Assunto do Email</label>
                <input type="text" name="assunto" id="assunto" required
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div class="mb-4">
                <label for="lista" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Lista de Destinatários</label>
                <select name="lista" id="lista" required
                    class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Selecione a lista</option>
                    <?php foreach ($listas as $lista): ?>
                        <option value="<?= htmlspecialchars($lista['lista_id']) ?>"><?= htmlspecialchars($lista['lista_nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-8">
                <label for="estado" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Estado da Campanha</label>
                <select name="estado" id="estado" required
                    class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="rascunho">Rascunho</option>
                    <option value="agendada">Agendada</option>
                    <option value="enviada">Enviada</option>
                </select>
            </div>
            <!-- Zona Editor + Preview lado a lado -->
            <div class="flex flex-col md:flex-row gap-8">
                <div class="flex-1 min-w-0">
                    <label for="editor" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Conteúdo HTML</label>
                    <textarea id="editor" name="html" rows="30"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                        placeholder="Cole ou edite aqui o HTML do email"></textarea>
                    <div class="flex justify-start mt-2">
                        <button type="button" id="preview-btn"
                            class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700 mr-2">
                            Ver Preview
                        </button>
                        <button type="submit" name="action" value="gravar"
                            class="cursor-pointer px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 font-medium">
                            Guardar Campanha
                        </button>
                        <button type="submit" name="action" value="enviar"
                            class="cursor-pointer ml-2 px-5 py-2 text-white bg-green-600 rounded hover:bg-green-700 font-medium">
                            Enviar Agora
                        </button>
                    </div>

                </div>

                <div class="flex-1 min-w-0">
                    <label class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Preview</label>
                    <iframe id="html-preview" class="w-full h-180 border rounded bg-white" style="background: #fff"></iframe>
                </div>
            </div>
        </form>
    </section>


</main>
<script src="js/campanhas.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>