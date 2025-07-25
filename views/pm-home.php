<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main>
    <div class="min-h-screen p-6 w-full max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold my-10 text-white text-center">Password Manager</h1>

        <div class="p-6">
            <div class="mb-6">
                <div class="flex justify-end mb-4">
                    <button id="btnNovoAcesso" class="cursor-pointer bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        + Novo Acesso
                    </button>
                </div>

                <div class="flex justify-end">
                    <input type="text" id="pesquisaAcesso" placeholder="Pesquisar serviço..."
                        class="px-4 py-2 rounded-lg w-full md:w-1/3 bg-white text-gray-800 focus:outline-none focus:ring-1 focus:ring-blue-500" />
                </div>
            </div>

            <div id="listaAcessos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php include __DIR__ . '/../controllers/pm-home-fragment.php'; ?>
            </div>
        </div>
    </div>

    <div id="modalAcesso" class="fixed inset-0 bg-opacity-90 flex items-center justify-center hidden z-50">
        <div class="bg-slate-800 p-6 rounded-2xl shadow max-w-lg w-full">
            <h2 id="modalTitulo" class="text-xl text-white font-semibold mb-4">Adicionar Acesso</h2>
            <form method="POST" id="formAcesso">
                <input type="hidden" name="id" id="acesso_id">
                <div class="grid gap-3">
                    <div class="flex flex-col">
                        <label for="nome_servico" class="text-gray-300 mb-1">Nome do Serviço:</label>
                        <input type="text" name="nome_servico" id="nome_servico" class="rounded px-3 py-2 bg-gray-400 text-gray-800 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Nome do Serviço" required>
                    </div>
                    <div class="flex flex-col">
                        <label for="url_acesso" class="text-gray-300 mb-1">URL:</label>
                        <input type="url" name="url_acesso" id="url_acesso" class="rounded px-3 py-2 bg-gray-100 text-gray-800 focus:outline-none focus:ring focus:ring-blue-400" placeholder="URL (opcional)">
                    </div>
                    <div class="flex flex-col">
                        <label for="username" class="text-gray-300 mb-1">Username:</label>
                        <input type="text" name="username" id="username" class="rounded px-3 py-2 bg-gray-100 text-gray-800 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Username (opcional)">
                    </div>
                    <div class="flex flex-col">
                        <label for="senha" class="text-gray-300 mb-1">Senha:</label>
                        <input type="password" name="senha" id="senha" class="rounded px-3 py-2 bg-gray-100 text-gray-800 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Senha">
                    </div>
                    <div class="flex flex-col">
                        <label for="notas" class="text-gray-300 mb-1">Notas:</label>
                        <textarea name="notas" id="notas" class="rounded px-3 py-2 bg-gray-100 text-gray-800 focus:outline-none focus:ring focus:ring-blue-400" placeholder="Notas"></textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-4 mt-6">
                    <button id="btnFecharModal"
                        type="button"
                        class="cursor-pointer bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 hover:text-gray-600">
                        Cancelar
                    </button>
                    <button type="submit" class="cursor-pointer bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded">Guardar</button>
                </div>
            </form>
        </div>
    </div>
    <div id="alertaSenha" class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
     bg-green-500 text-white px-6 py-3 rounded-xl shadow-lg hidden z-50 
     opacity-0 transition-opacity duration-300 text-lg font-semibold">
        Senha copiada
    </div>
</main>
<script src="js/pm.js"></script>

<?php require __DIR__ . '/partials/footer.php'; ?>