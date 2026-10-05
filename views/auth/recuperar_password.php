<?php require view_path('layouts/start.php'); ?>

<main class="auth-page flex items-center justify-center">
    <form method="POST" action="/recuperar_password" class="auth-card bg-surface border border-line space-y-6">
        <h5 class="text-xl font-semibold text-ink">Recuperar Acesso</h5>

        <p class="text-sm text-neutral">
            Insira o seu e-mail para receber um link de recuperação de palavra-passe.
        </p>

        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-ink">O seu Email</label>
            <input type="email" name="email" id="email" value="<?= $email ?? '' ?>" placeholder="LynxApp@lynx.com"
                class="block w-full p-2.5 text-sm rounded-lg border border-control bg-field text-ink focus:ring-primary/30 focus:border-primary" required />
        </div>

        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">

        <button type="submit" name="recover"
            class="cursor-pointer w-full px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary-hover rounded-lg focus:outline-none focus:ring-4 focus:ring-primary/30">
            Enviar link de recuperação
        </button>

        <div class="h-5 flex justify-center items-center">
            <?php if (!empty($message)): ?>
                <?php
                $isSuccess = str_contains(strtolower($message), 'enviado') || str_contains(strtolower($message), 'sucesso');
                $colorClass = $isSuccess ? 'text-positive ' : 'text-negative ';
                ?>
                <p class="text-sm font-medium <?= $colorClass ?> text-center">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="text-sm font-medium text-neutral">
            Já tem acesso?
            <a href="/login" class="text-accent hover:underline">Voltar ao login</a>
        </div>
    </form>
</main>

<?php require view_path('layouts/footer.php'); ?>