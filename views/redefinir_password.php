<?php require __DIR__ . '/partials/head.php'; ?>
<?php $token = $_GET['token'] ?? ''; ?>

<main class="min-h-screen flex items-center justify-center">
    <form method="POST" action="/controllers/redefinir_password.php" class="w-full max-w-sm p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-6">
        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Nova Password</h5>

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nova password</label>
            <input type="password" name="password" id="password" required minlength="8"
                class="block w-full p-2.5 text-sm rounded-lg border border-gray-300 bg-gray-50 text-gray-900 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
        </div>

        <button type="submit" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
            Redefinir Password
        </button>
    </form>
</main>

<?php require __DIR__ . '/partials/footer.php'; ?>