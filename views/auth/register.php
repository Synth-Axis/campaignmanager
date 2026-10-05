<?php require view_path('layouts/start.php'); ?>

<main class="auth-page flex items-center justify-center">
    <form method="POST" action="register" class="auth-card bg-surface border border-line space-y-6">
        <h5 class="text-xl font-semibold text-ink">Criar uma conta</h5>

        <div>
            <label for="nome" class="block mb-2 text-sm font-medium text-ink">O seu nome</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>" class="bg-field border border-control text-ink text-sm rounded-lg focus:ring-primary/30 focus:border-primary block w-full p-2.5" placeholder="insira o seu nome">
        </div>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-ink">O seu email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" class="bg-field border border-control text-ink text-sm rounded-lg focus:ring-primary/30 focus:border-primary block w-full p-2.5" placeholder="name@company.com">
        </div>
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-ink">A sua password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-field border border-control text-ink text-sm rounded-lg focus:ring-primary/30 focus:border-primary block w-full p-2.5">
        </div>
        <div>
            <label for="passwordCheck" class="block mb-2 text-sm font-medium text-ink">Confirmar password</label>
            <input type="password" name="passwordCheck" id="passwordCheck" placeholder="••••••••" class="bg-field border border-control text-ink text-sm rounded-lg focus:ring-primary/30 focus:border-primary block w-full p-2.5">
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
        <button type="submit" name="send" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-lg focus:outline-none focus:ring-4 focus:ring-primary/30">Criar conta</button>
        <div class="h-5 flex justify-center items-center">
            <?php if (!empty($message)): ?>
                <p class="text-sm font-medium text-negative text-center">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="text-sm font-medium text-neutral">
            Já tem uma conta?
            <a href="/login" class="text-accent hover:underline"><br>Entre na sua conta aqui.</a>
        </div>
    </form>
</main>

<?php require view_path('layouts/footer.php'); ?>