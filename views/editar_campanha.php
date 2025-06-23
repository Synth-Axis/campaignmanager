<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center my-10 gap-8">
    <section class="w-full max-w-screen-2xl bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-8">
        <a href="/campanhas" class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300 mb-4">
            ← Voltar à Lista de Campanhas
        </a>
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Editar Campanha</h2>
        </div>

        <?php if (!empty($mensagem)): ?>
            <div class="p-4 mb-6 rounded text-sm <?= $mensagem_tipo === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="w-full">
            <input type="hidden" name="id" value="<?= htmlspecialchars($campanha['campaign_id']) ?>">

            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Nome da Campanha</label>
                <input type="text" name="nome" id="nome" required
                    value="<?= htmlspecialchars($campanha['nome']) ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Assunto do Email</label>
                <input type="text" name="assunto" id="assunto" required
                    value="<?= htmlspecialchars($campanha['assunto']) ?>"
                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>

            <div class="mb-4">
                <label for="lista" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Lista de Destinatários</label>
                <select name="lista" id="lista" required
                    class="cursor-pointer bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    <option value="">Selecione a lista</option>
                    <?php foreach ($listas as $lista): ?>
                        <option value="<?= htmlspecialchars($lista['lista_id']) ?>"
                            <?= $lista['lista_id'] == $campanha['lista_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lista['lista_nome']) ?>
                        </option>
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

            <div class="flex flex-col md:flex-row gap-8 mb-6">
                <div class="flex-1 min-w-0">
                    <label for="editor" class="block text-sm font-medium text-gray-900 dark:text-gray-300 mb-1">Conteúdo HTML</label>
                    <textarea id="editor" name="html" rows="20"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white font-mono"
                        placeholder="Cole ou edite aqui o HTML do email"><?= htmlspecialchars($campanha['html']) ?></textarea>

                    <div class="flex justify-start mt-2 flex-wrap gap-2">
                        <button type="button" id="preview-btn"
                            class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                            Ver Preview
                        </button>

                        <button type="submit" name="action" value="gravar"
                            class="cursor-pointer px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 font-medium">
                            Guardar Alterações
                        </button>

                        <button type="submit" name="action" value="enviar"
                            class="cursor-pointer px-5 py-2 text-white bg-green-600 rounded hover:bg-green-700 font-medium">
                            Enviar Novamente
                        </button>

                        <button type="submit" name="action" value="duplicar"
                            class="cursor-pointer px-5 py-2 text-white bg-gray-600 rounded hover:bg-gray-700 font-medium">
                            Duplicar Campanha
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

<script src="/js/campanhas.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>