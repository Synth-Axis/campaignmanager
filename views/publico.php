
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

    <main class="min-h-screen flex items-center justify-center">
        <form method="POST" action="novo-registo" class="w-full max-w-2xl p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-6">
            <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Novo registo</h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>" placeholder="Insira o nome"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="name@empresa.com"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div>
                    <label for="agente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agente</label>
                    <input type="text" name="agente" id="agente" value="<?= htmlspecialchars($agente ?? '') ?>" placeholder="Nome do agente"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label for="gestor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gestor</label>
                    <select name="gestor" id="gestor"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <option value="" <?= empty($gestor_id) ? 'selected' : '' ?>>Selecione um gestor</option>

                        <?php foreach ($gestores as $gestor): ?>
                            <option value="<?= htmlspecialchars($gestor['gestor_id']) ?>" <?= (isset($gestor_id) && $gestor_id == $gestor['gestor_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($gestor['gestor_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="lista" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lista</label>
                    <select name="lista" id="lista"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">

                        <option value="" <?= empty($lista_id) ? 'selected' : '' ?>>Selecione a lista</option>

                        <?php foreach ($listas as $lista): ?>
                            <option value="<?= htmlspecialchars($lista['lista_id']) ?>" <?= (isset($lista_id) && $lista_id == $lista['lista_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lista['lista_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="canal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Canal</label>
                    <select name="canal" id="canal"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        
                        <option value="" <?= empty($canal_id) ? 'selected' : '' ?>>Selecione o canal</option>
                        
                        <?php foreach ($channels as $canal): ?>
                            <option value="<?= htmlspecialchars($canal['id']) ?>" <?= (isset($canal_id) && $canal_id == $canal['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($canal['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

            <div class="col-span-2 mt-3">
                <button type="submit" name="send"
                    class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                    Submeter
                </button>
            </div>

            <div class="h-5 flex justify-center items-center">
            <?php if (!empty($_SESSION['message'])): ?>
                <div><?= $_SESSION['message'] ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>
            </div>
        </form>
    </main>

<?php require __DIR__ . '/partials/footer.php'; ?>
