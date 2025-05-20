<?php require __DIR__ . '/partials/head.php'; ?>

<main class="min-h-screen flex items-center justify-center">
    <form method="POST" action="register" class="w-full max-w-sm p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-6">
        <h5 class="text-xl font-semibold text-gray-900 dark:text-white">Criar uma conta</h5>

        <div>
            <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">O seu nome</label>
            <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>"class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="insira o seu nome">
        </div>
        <div>
            <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">O seu email</label>
            <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" >
        </div>
        <div>
            <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">A sua password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
        </div>
        <div>
            <label for="passwordCheck" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Confirmar password</label>
            <input type="password" name="passwordCheck" id="passwordCheck" placeholder="••••••••" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
        </div>
        
        <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
        <button type="submit" name="send" class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">Criar conta</button>
        <div class="h-5 flex justify-center items-center">
            <?php if (!empty($message)): ?>
                <p class="text-sm font-medium text-red-600 dark:text-red-400 text-center">
                    <?= htmlspecialchars($message) ?>
                </p>
            <?php endif; ?>
        </div>
        <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
            Já tem uma conta?
            <a href="/login" class="text-blue-600 hover:underline dark:text-blue-400"><br>Entre na sua conta aqui.</a>
        </div>
    </form>
</main>


