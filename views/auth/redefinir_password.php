<?php require view_path('layouts/start.php'); ?>
<?php $token = $_GET['token'] ?? ''; ?>

<main class="auth-page flex items-center justify-center">
    <form method="POST" action="redefinir_password" class="auth-card bg-surface border border-line space-y-6">
        <h5 class="text-xl font-semibold text-ink">Nova Password</h5>

        <input type="hidden" name="token" value="<?= htmlspecialchars($token) ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-ink">Nova password</label>
            <input type="password" name="password" id="password" required minlength="8"
                class="block w-full p-2.5 text-sm rounded-lg border border-control bg-field text-ink focus:ring-primary/30 focus:border-primary" />
        </div>

        <button type="submit" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-lg focus:outline-none focus:ring-4 focus:ring-primary/30">
            Redefinir Password
        </button>
    </form>
</main>

<?php require view_path('layouts/footer.php'); ?>
