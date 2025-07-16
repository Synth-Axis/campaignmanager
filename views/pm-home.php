<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<div class="min-h-screen bg-slate-600 p-6w-full max-w-7xl mx-auto">
    <h1 class="text-3xl font-bold my-10 text-white text-center">Password Manager</h1>

    <div class="p-6">
        <div class="flex justify-end items-center mb-6">
            <button onclick="abrirModalNovo()" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">+ Novo Acesso</button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($acessos as $acesso): ?>
                <div class="rounded-2xl shadow p-4 dark:bg-gray-900 text-white">
                    <h3 class="text-xl font-semibold"><?= htmlspecialchars($acesso['nome_servico']) ?></h3>

                    <?php if (!empty($acesso['url_acesso'])): ?>
                        <a href="<?= htmlspecialchars($acesso['url_acesso']) ?>" target="_blank" class="text-sm text-blue-400 hover:underline">
                            Aceder ao site
                        </a>
                    <?php endif; ?>

                    <?php if (!empty($acesso['email']) || !empty($acesso['username'])): ?>
                        <p class="text-sm mt-2 text-gray-300"> Username:</br>
                            <?= htmlspecialchars($acesso['email'] ?: $acesso['username']) ?>
                        </p>
                    <?php endif; ?>

                    <div class="mt-4 flex gap-4">
                        <button onclick="copiarSenha('<?= $acesso['id'] ?>')" class="text-sm text-green-400 hover:underline">Copiar senha</button>
                        <button onclick="editarAcesso(<?= $acesso['id'] ?>)" class="text-sm text-yellow-500 hover:underline">Editar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="modalAcesso" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-2xl shadow max-w-lg w-full">
        <h2 id="modalTitulo" class="text-xl font-semibold mb-4">Adicionar Acesso</h2>
        <form method="POST" action="guardar_acesso.php">
            <input type="hidden" name="id" id="acesso_id">
            <div class="grid gap-4">
                <input type="text" name="nome_servico" id="nome_servico" class="input" placeholder="Nome do Serviço" required>
                <input type="url" name="url_acesso" id="url_acesso" class="input" placeholder="URL (opcional)">
                <input type="text" name="username" id="username" class="input" placeholder="Username (opcional)">
                <input type="email" name="email" id="email" class="input" placeholder="Email (opcional)">
                <input type="password" name="senha" id="senha" class="input" placeholder="Senha" required>
                <textarea name="notas" id="notas" class="input" placeholder="Notas"></textarea>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="fecharModal()" class="text-gray-600">Cancelar</button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Guardar</button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>