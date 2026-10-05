<?php require view_path('layouts/start.php'); ?>

<main class="auth-page flex items-center justify-center">
    <form method="POST" action="login" class="auth-card bg-surface border border-line space-y-6">
        <h1 class="text-xl font-semibold text-ink">Entrar na LynxApp</h1>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-ink">O seu Email</label>
            <input type="email" name="email" id="email" value="<?= $email ?>" placeholder="LynxApp@lynx.com"
                class="block w-full p-2.5 text-sm rounded-lg border border-control bg-field text-ink focus:ring-primary/30 focus:border-primary" />
        </div>

        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-ink">A sua password</label>
            <input type="password" name="password" id="password" placeholder="" minlength="8" maxlength="255"
                class="block w-full p-2.5 text-sm rounded-lg border border-control bg-field text-ink focus:ring-primary/30 focus:border-primary" />
        </div>

        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center">
                <input id="remember" name="remember" type="checkbox" class="w-4 h-4 text-accent bg-field border-control rounded focus:ring-primary/30" />
                <label for="remember" class="ml-2 text-sm font-medium text-ink">Lembrar-me!</label>
            </div>
            <a href="/recuperar_password" class="text-sm text-accent hover:underline">Perdeu a Password?</a>
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <button type="submit" name="send"
            class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-lg focus:outline-none focus:ring-4 focus:ring-primary/30">
            Entrar na sua conta
        </button>

        <?php if (!empty($message)): ?>
            <p role="alert" class="text-sm font-medium text-negative text-center">
                <?= htmlspecialchars($message) ?>
            </p>
        <?php endif; ?>

        <div class="text-sm font-medium text-neutral">
            Não tem registo?
            <a href="/register" class="text-accent hover:underline"><br>Efetue o registo aqui.</a>
        </div>
    </form>
</main>

<?php require view_path('layouts/footer.php'); ?>
