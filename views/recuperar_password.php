<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex items-center justify-center">
    <form method="POST" action="/recuperar_password" class="w-full max-w-sm p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-6">
        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Recuperar Acesso</h5>

        <p class="text-sm text-gray-500 dark:text-gray-300">
            Insira o seu e-mail para receber um link de recuperação de palavra-passe.
        </p>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">O seu Email</label>
            <input type="email" name="email" id="email" value="<?= $email ?? '' ?>" placeholder="name@realvidaseguros.pt"
                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" required />
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <button type="submit" name="recover"
            class="cursor-pointer w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
            Enviar link de recuperação
        </button>

        <div class="h-5 flex justify-center items-center">
            <?php if (!empty($message)): ?>
                <?php
                $isSuccess = str_contains(strtolower($message), 'enviado') || str_contains(strtolower($message), 'sucesso');
                $colorClass = $isSuccess ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400';
                ?>
                <p class="text-sm font-medium <?= $colorClass ?> text-center">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
            Já tem acesso?
            <a href="/login" class="text-blue-600 hover:underline dark:text-blue-400">Voltar ao login</a>
        </div>
    </form>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>