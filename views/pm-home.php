<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen bg-light">
    <div class="p-6 w-full max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold my-10 text-dark text-center">Password Manager</h1>

        <div class="p-6 bg-white border rounded-xl shadow-md">
            <div class="mb-6">
                <div class="flex justify-end mb-4">
                    <button id="btnNovoAcesso"
                        class="cursor-pointer bg-primary text-white px-4 py-2 rounded-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        + Novo Acesso
                    </button>
                </div>

                <div class="flex justify-end">
                    <input type="text" id="pesquisaAcesso" placeholder="Pesquisar serviço..."
                        class="px-4 py-2 rounded-lg w-full md:w-1/3 bg-white text-dark border border-light
                        focus:outline-none focus:ring-2 focus:ring-primary/40" />
                </div>
            </div>

            <div id="listaAcessos" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php include __DIR__ . '/../controllers/pm-home-fragment.php'; ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="modalAcesso" class="fixed inset-0 bg-black/60 flex items-center justify-center hidden z-50">
        <div class="bg-white p-6 rounded-2xl shadow-2xl w-full max-w-lg border border-light">
            <h2 id="modalTitulo" class="text-xl text-dark font-semibold mb-4">Adicionar Acesso</h2>

            <form method="POST" id="formAcesso" class="space-y-3">
                <input type="hidden" name="id" id="acesso_id">

                <div>
                    <label for="nome_servico" class="text-sm text-dark mb-1 block">Nome do Serviço:</label>
                    <input type="text" name="nome_servico" id="nome_servico"
                        class="rounded-lg px-3 py-2 bg-white text-dark border border-light w-full
                        focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Nome do Serviço" required>
                </div>

                <div>
                    <label for="url_acesso" class="text-sm text-dark mb-1 block">URL:</label>
                    <input type="url" name="url_acesso" id="url_acesso"
                        class="rounded-lg px-3 py-2 bg-white text-dark border border-light w-full
                        focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="URL (opcional)">
                </div>

                <div>
                    <label for="username" class="text-sm text-dark mb-1 block">Username:</label>
                    <input type="text" name="username" id="username"
                        class="rounded-lg px-3 py-2 bg-white text-dark border border-light w-full
                        focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Username (opcional)">
                </div>

                <div>
                    <label for="senha" class="text-sm text-dark mb-1 block">Senha:</label>
                    <input type="password" name="senha" id="senha"
                        class="rounded-lg px-3 py-2 bg-white text-dark border border-light w-full
                        focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Senha">
                </div>

                <div>
                    <label for="notas" class="text-sm text-dark mb-1 block">Notas:</label>
                    <textarea name="notas" id="notas"
                        class="rounded-lg px-3 py-2 bg-white text-dark border border-light w-full
                           focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Notas"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-3">
                    <button id="btnFecharModal" type="button"
                        class="cursor-pointer px-4 py-2 bg-light text-dark rounded-lg hover:bg-highlight/20
                         focus:outline-none focus:ring-2 focus:ring-primary/20">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="cursor-pointer bg-primary hover:bg-primary/90 text-white px-4 py-2 rounded-lg
                         focus:outline-none focus:ring-2 focus:ring-primary/40">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Toast -->
    <div id="alertaSenha"
        class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 
              bg-success text-white px-6 py-3 rounded-xl shadow-lg hidden z-50 
              opacity-0 transition-opacity duration-300 text-lg font-semibold">
        Senha copiada
    </div>
</main>

<script src="js/pm.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>