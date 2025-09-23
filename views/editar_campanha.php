<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center my-10 gap-8 bg-light">
    <section class="w-full max-w-6xl bg-white border border-light rounded-lg shadow p-8">

        <!-- Voltar -->
        <a href="/campanhas"
            class="inline-flex items-center px-4 py-2 bg-light text-dark rounded hover:bg-highlight/20 mb-4 transition">
            ← Voltar à Lista de Campanhas
        </a>

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-semibold text-dark">Editar Campanha</h2>
        </div>

        <!-- Mensagens -->
        <?php if (!empty($mensagem)): ?>
            <div class="p-4 mb-6 rounded text-sm <?= $mensagem_tipo === 'success'
                                                        ? 'bg-success/10 text-success'
                                                        : 'bg-danger/10 text-danger' ?>">
                <?= htmlspecialchars($mensagem) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="w-full">
            <input type="hidden" name="id" value="<?= htmlspecialchars($campanha['campaign_id']) ?>">

            <!-- Nome -->
            <div class="mb-4">
                <label for="nome" class="block text-sm font-medium text-dark mb-1">Nome da Campanha</label>
                <input type="text" name="nome" id="nome" required
                    value="<?= htmlspecialchars($campanha['nome']) ?>"
                    class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <!-- Assunto -->
            <div class="mb-4">
                <label for="assunto" class="block text-sm font-medium text-dark mb-1">Assunto do Email</label>
                <input type="text" name="assunto" id="assunto" required
                    value="<?= htmlspecialchars($campanha['assunto']) ?>"
                    class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
            </div>

            <!-- Lista -->
            <div class="mb-4">
                <label for="lista" class="block text-sm font-medium text-dark mb-1">Lista de Destinatários</label>
                <select name="lista" id="lista" required
                    class="cursor-pointer bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="">Selecione a lista</option>
                    <?php foreach ($listas as $lista): ?>
                        <option value="<?= htmlspecialchars($lista['lista_id']) ?>"
                            <?= $lista['lista_id'] == $campanha['lista_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($lista['lista_nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Estado -->
            <div class="mb-8">
                <label for="estado" class="block text-sm font-medium text-dark mb-1">Estado da Campanha</label>
                <select name="estado" id="estado" required
                    class="cursor-pointer bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                    <option value="rascunho" <?= $campanha['estado'] === 'rascunho' ? 'selected' : '' ?>>Rascunho</option>
                    <option value="agendada" <?= $campanha['estado'] === 'agendada' ? 'selected' : '' ?>>Agendada</option>
                    <option value="enviada" <?= $campanha['estado'] === 'enviada' ? 'selected' : '' ?>>Enviada</option>
                </select>
            </div>

            <!-- Editor -->
            <div class="flex flex-col md:flex-row gap-8 mb-6">
                <div class="flex-1 min-w-0">
                    <label for="editor" class="block text-sm font-medium text-dark mb-1">Conteúdo HTML</label>
                    <textarea id="editor" name="html" rows="30"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 font-mono focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Cole ou edite aqui o HTML do email"><?= htmlspecialchars($campanha['html']) ?></textarea>

                    <div class="flex flex-wrap gap-3 mt-3">
                        <button type="button" id="preview-btn"
                            class="cursor-pointer px-4 py-2 bg-primary text-white rounded hover:bg-primary/90">
                            Ver Preview
                        </button>

                        <button type="button"
                            class="cursor-pointer px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700"
                            onclick="document.getElementById('modal-envio-teste').classList.remove('hidden')">
                            Enviar Email de Teste
                        </button>

                        <button type="submit" name="action" value="gravar"
                            class="cursor-pointer px-5 py-2 text-white bg-primary rounded hover:bg-primary/90 font-medium">
                            Guardar Alterações
                        </button>

                        <button type="submit" name="action" value="enviar"
                            class="cursor-pointer px-5 py-2 text-white bg-success rounded hover:bg-success/90 font-medium">
                            Enviar Novamente
                        </button>

                        <button type="submit" name="action" value="duplicar"
                            class="cursor-pointer px-5 py-2 text-white bg-dark rounded hover:bg-dark/90 font-medium">
                            Duplicar Campanha
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

    <!-- Modal envio teste -->
    <div id="modal-envio-teste" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md relative">
            <h2 class="text-xl font-semibold mb-4 text-dark">Enviar Email de Teste</h2>

            <form method="POST">
                <input type="hidden" name="action" value="enviar_teste">
                <input type="hidden" name="nome" value="<?= htmlspecialchars($campanha['nome']) ?>">
                <input type="hidden" name="assunto" value="<?= htmlspecialchars($campanha['assunto']) ?>">
                <input type="hidden" name="html" value="<?= htmlspecialchars($campanha['html']) ?>">

                <label for="emails_teste" class="block mb-2 text-dark">Emails de teste</label>
                <input type="text" name="emails_teste" id="emails_teste"
                    class="cursor-pointer w-full border border-light rounded p-2 mb-4 text-dark focus:outline-none focus:ring-2 focus:ring-primary/40"
                    placeholder="ex: nome@email.pt, outro@teste.com" required>

                <div class="flex justify-end gap-3">
                    <button type="button" onclick="document.getElementById('modal-envio-teste').classList.add('hidden')"
                        class="cursor-pointer px-4 py-2 bg-light text-dark rounded hover:bg-highlight/20">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="cursor-pointer px-4 py-2 bg-purple-600 text-white rounded hover:bg-purple-700">
                        Enviar Teste
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>

<script src="/js/campanhas.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>