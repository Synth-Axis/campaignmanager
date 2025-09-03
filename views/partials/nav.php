<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$currentUser = $_SESSION['user'] ?? null;

$currentPage = basename(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
$isHomeCenter = in_array($currentPage, ['home-center', 'home-center.php']);
$isLogin = in_array($currentPage, ['login', 'login.php']);
?>

<nav class="border-b border-light bg-light/95 backdrop-blur-xs sticky top-0 z-50">
    <div class="max-w-screen-2xl mx-auto flex items-center justify-between">
        <!-- Left group (removido o botão do menu) -->
        <div class="<?= $isLogin ? 'w-1/3 hidden' : 'w-1/3' ?>"></div>

        <!-- Center title -->
        <div class="flex justify-center">
            <h1 class="text-6xl font-bold bg-text-gradient bg-clip-text text-transparent text-center whitespace-nowrap p-14">Marketing - App Center</h1>
        </div>

        <!-- Right group (auth actions) -->
        <div class="w-1/3 flex justify-end items-center space-x-4 <?= empty($_SESSION['user_id']) ? 'invisible' : '' ?>">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <span class="text-dark">Olá, <?= htmlspecialchars($currentUser['nome'] ?? 'Utilizador') ?></span>
                <a href="/logout">
                    <button
                        class="cursor-pointer text-white bg-danger hover:bg-danger/90 focus:ring-4 focus:outline-none focus:ring-danger/30 font-medium rounded-lg text-sm px-4 py-2">
                        Sair
                    </button>
                </a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if (!empty($_SESSION['user_id'])): ?>
    <!-- Sidebar fixa e sempre visível -->
    <aside
        id="drawer-navigation"
        class="fixed top-8 left-5 z-50 h-[calc(100vh-4rem)] bg-white p-4 overflow-y-auto w-64 border-r rounded-lg border shadow-md">


        <div class="py-2 overflow-y-auto">
            <ul class="space-y-1 font-medium">
                <li>
                    <a href="/home-center"
                        class="mt-2 flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-dark" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Home</span>
                    </a>
                </li>

                <li>
                    <a href="/campanhas"
                        class="flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-dark"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.25 6A2.25 2.25 0 0 1 4.5 3.75h15A2.25 2.25 0 0 1 21.75 6v12a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 18V6Zm1.5 0v.658l8.25 5.313 8.25-5.313V6h-16.5Zm0 2.592V18h16.5V8.592l-8.065 5.195a.75.75 0 0 1-.87 0L3.75 8.592Z" />
                        </svg>
                        <span class="ml-3">Campanhas</span>
                    </a>
                </li>

                <li>
                    <a href="/pm-home"
                        class="flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-dark"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 8a6 6 0 1 0-11.473 2.412l-4.26 4.26a1 1 0 0 0 0 1.414l1.647 1.647a1 1 0 0 0 1.414 0l.793-.793.793.793a1 1 0 0 0 1.414 0l1.647-1.647a1 1 0 0 0 0-1.414l-.293-.293.293-.293a6 6 0 0 0 8.824-5.386Zm-6 4a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" />
                        </svg>
                        <span class="ml-3">Password Manager</span>
                    </a>
                </li>

                <li>
                    <a href="/publico"
                        class="flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="shrink-0 w-5 h-5 text-neutral transition duration-75 group-hover:text-dark" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Público</span>
                    </a>
                </li>

                <li>
                    <a href="#"
                        class="flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="shrink-0 w-5 h-5 text-neutral transition duration-75 group-hover:text-dark" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M5 5V.13a2.96 2.96 0 0 0-1.293.749L.879 3.707A2.96 2.96 0 0 0 .13 5H5Z" />
                            <path d="M6.737 11.061a2.961 2.961 0 0 1 .81-1.515l6.117-6.116A4.839 4.839 0 0 1 16 2.141V2a1.97 1.97 0 0 0-1.933-2H7v5a2 2 0 0 1-2 2H0v11a1 1 0 0 0 1.933 2h12.134A1 1 0 0 0 16 18v-3.093l-1.546 1.546c-.413.413-.94.695-1.513.81l-3.4.679a2.947 2.947 0 0 1-1.85-.227 2.96 2.96 0 0 1-1.635-3.257l.681-3.397Z" />
                            <path d="M8.961 16a.93.93 0 0 0 .189-.019l3.4-.679a.961.961 0 0 0 .49-.263l6.118-6.117a2.884 2.884 0 0 0-4.079-4.078l-6.117 6.117a.96.96 0 0 0-.263.491l-.679 3.4A.961.961 0 0 0 8.961 16Zm7.477-9.8a.958.958 0 0 1 .68-.281.961.961 0 0 1 .682 1.644l-.315.315-1.36-1.36.313-.318Zm-5.911 5.911 4.236-4.236 1.359 1.359-4.236 4.237-1.7.339.341-1.699Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Relatórios</span>
                    </a>
                </li>

                <li>
                    <a href="/exportacoes"
                        class="flex items-center p-2 rounded-lg text-dark hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-dark"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 16.5l4.5-4.5h-3V3h-3v9H7.5l4.5 4.5ZM4.5 18h15v1.5h-15V18Z" />
                        </svg>
                        <span class="ml-3">Exportações</span>
                    </a>
                </li>
            </ul>
        </div>

    </aside>
<?php endif; ?>