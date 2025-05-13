<?php require __DIR__ . '/partials/head.php'; ?>


<body class="text-light bg-dark">
    <main class="min-h-screen flex items-center justify-center bg-dark text-light">
        <form method="POST" action="login" class="space-y-6">
            <div class="w-full max-w-sm p-4 bg-white border border-gray-200 rounded-lg shadow-sm sm:p-6 md:p-8 dark:bg-gray-800 dark:border-gray-700">

                <h5 class="text-xl font-medium text-gray-900 dark:text-white">Entrar em RVS - Gestor de Campanhas</h5>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">O seu Email</label>
                    <input type="email" name="email" id="email" value="<?= $email ?>" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="name@company.com" required />
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">A sua password</label>
                    <input type="password" name="password" id="password" placeholder="••••••••" minlength="8" maxlength="255" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required />
                </div>
                <div class="flex items-start">
                    <div class="flex items-start">
                        <div class="flex items-center h-5">
                            <input id="remember" type="checkbox" value="" class="w-4 h-4 border border-gray-300 rounded-sm bg-gray-50 focus:ring-3 focus:ring-blue-300 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-blue-600 dark:ring-offset-gray-800 dark:focus:ring-offset-gray-800" required />
                        </div>
                        <label for="remember" class="ms-2 text-sm font-medium text-gray-900 dark:text-gray-300">Lembrar-me!</label>
                    </div>
                    <a href="#" class="ms-auto text-sm text-blue-700 hover:underline dark:text-blue-500">Perdeu a Password?</a>
                </div>
                <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
                <button type="submit" name="send" class="cursor-pointer w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Entrar na sua conta</button>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-300">
                    Não tem registo? <a href="#" class="text-blue-700 hover:underline dark:text-blue-500">
                        </br>Contacte a equipa de Marketing da RVS.
                    </a>
                </div>

            </div>
        </form>

        <?php require __DIR__ . '/partials/footer.php'; ?>