<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex items-center justify-center">
    <form method="POST" action="login" class="w-full max-w-sm p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-6">
        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Entrar em RVS - Gestor de Campanhas</h5>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">O seu Email</label>
            <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="name@realvidaseguros.pt"
                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">A sua password</label>
            <input type="password" name="password" id="password" placeholder="" minlength="8" maxlength="255"
                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" />
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:bg-gray-700 dark:border-gray-600" />
                <label for="remember" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lembrar-me!</label>
            </div>
            <a href="/recuperar_password" class="text-sm text-blue-600 hover:underline dark:text-blue-400">Perdeu a Password?</a>
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <button type="submit" name="send"
            class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
            Entrar na sua conta
        </button>

        <div class="h-5 flex justify-center items-center">
            <?php if (!empty($message)): ?>
                <p class="text-sm font-medium text-red-600 dark:text-red-400 text-center">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
            Não tem registo?
            <a href="/register" class="text-blue-600 hover:underline dark:text-blue-400"><br>Efetue o registo aqui.</a>
        </div>
    </form>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>