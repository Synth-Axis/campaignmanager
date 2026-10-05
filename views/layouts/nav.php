<?php
$currentUser = current_user();

?>

<nav class="app-header sticky top-0 z-50" aria-label="Navegação principal">
    <div class="max-w-screen-2xl mx-auto min-h-[79px] px-6 flex items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <?php if (!empty($_SESSION['user_id'])): ?>
                <button type="button" data-navigation-toggle aria-controls="drawer-navigation" aria-expanded="false"
                    aria-label="Abrir menu de navegação" class="xl:hidden w-11 h-11 flex items-center justify-center rounded-xl border border-line bg-field text-ink">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            <?php endif; ?>
            <a href="<?= !empty($_SESSION['user_id']) ? '/home-center' : '/login' ?>" class="flex items-center gap-3 rounded-lg">
                <span class="flex w-10 h-10 rounded-xl bg-primary items-center justify-center text-white" aria-hidden="true">
                    <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                        <rect x="4" y="4" width="6" height="6" rx="1.5" />
                        <rect x="14" y="4" width="6" height="6" rx="1.5" />
                        <rect x="4" y="14" width="6" height="6" rx="1.5" />
                        <rect x="14" y="14" width="6" height="6" rx="1.5" />
                    </svg>
                </span>
                <span>
                    <span class="block text-lg font-semibold tracking-tight text-ink">App Center</span>
                    <span class="block text-xs text-neutral">Lynx</span>
                </span>
            </a>
        </div>
        <div class="flex items-center gap-4">
            <button type="button" data-theme-toggle role="switch" aria-checked="false" aria-label="Modo escuro" class="theme-toggle">
                <svg class="theme-icon-sun w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="12" cy="12" r="4" />
                    <path stroke-linecap="round" d="M12 2v2m0 16v2M2 12h2m16 0h2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
                </svg>
                <svg class="theme-icon-moon w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.5 13.1A8.5 8.5 0 0 1 10.9 3.5a8.5 8.5 0 1 0 9.6 9.6Z" />
                </svg>
                <span data-theme-label>Claro</span>
                <span class="theme-switch-track" aria-hidden="true"><span class="theme-switch-thumb"></span></span>
            </button>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <span class="text-sm text-neutral">Olá, <?= htmlspecialchars($currentUser['nome'] ?? 'Utilizador') ?></span>
                <a href="/logout" class="text-sm font-medium px-3 py-2.5 rounded-lg text-negative hover:bg-danger/10">Sair</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<?php if (!empty($_SESSION['user_id'])): ?>
    <button type="button" data-navigation-backdrop hidden aria-label="Fechar menu de navegação"
        class="fixed inset-0 bg-black/50 z-40 xl:hidden"></button>
    <aside
        id="drawer-navigation"
        aria-label="Menu de aplicações"
        class="app-sidebar">


        <div class="py-2 overflow-y-auto">
            <ul class="space-y-1 font-medium">
                <li>
                    <a href="/home-center"
                        class="mt-2 flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-ink" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 21">
                            <path d="M16.975 11H10V4.025a1 1 0 0 0-1.066-.998 8.5 8.5 0 1 0 9.039 9.039.999.999 0 0 0-1-1.066h.002Z" />
                            <path d="M12.5 0c-.157 0-.311.01-.565.027A1 1 0 0 0 11 1.02V10h8.975a1 1 0 0 0 1-.935c.013-.188.028-.374.028-.565A8.51 8.51 0 0 0 12.5 0Z" />
                        </svg>
                        <span class="ms-3">Home</span>
                    </a>
                </li>

                <li>
                    <a href="/campanhas"
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-ink"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M2.25 6A2.25 2.25 0 0 1 4.5 3.75h15A2.25 2.25 0 0 1 21.75 6v12a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 2.25 18V6Zm1.5 0v.658l8.25 5.313 8.25-5.313V6h-16.5Zm0 2.592V18h16.5V8.592l-8.065 5.195a.75.75 0 0 1-.87 0L3.75 8.592Z" />
                        </svg>
                        <span class="ml-3">Campanhas</span>
                    </a>
                </li>

                <li>
                    <a href="/pm-home"
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-ink"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M18 8a6 6 0 1 0-11.473 2.412l-4.26 4.26a1 1 0 0 0 0 1.414l1.647 1.647a1 1 0 0 0 1.414 0l.793-.793.793.793a1 1 0 0 0 1.414 0l1.647-1.647a1 1 0 0 0 0-1.414l-.293-.293.293-.293a6 6 0 0 0 8.824-5.386Zm-6 4a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z" />
                        </svg>
                        <span class="ml-3">Password Manager</span>
                    </a>
                </li>

                <li>
                    <a href="/publico"
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="shrink-0 w-5 h-5 text-neutral transition duration-75 group-hover:text-ink" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Público</span>
                    </a>
                </li>

                <li>
                    <a href="/campanhas.php"
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="shrink-0 w-5 h-5 text-neutral transition duration-75 group-hover:text-ink" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                            <path d="M14 2a3.963 3.963 0 0 0-1.4.267 6.439 6.439 0 0 1-1.331 6.638A4 4 0 1 0 14 2Zm1 9h-1.264A6.957 6.957 0 0 1 15 15v2a2.97 2.97 0 0 1-.184 1H19a1 1 0 0 0 1-1v-1a5.006 5.006 0 0 0-5-5ZM6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Z" />
                        </svg>
                        <span class="flex-1 ms-3 whitespace-nowrap">Acções de Campanhas</span>
                    </a>
                </li>


                <li>
                    <a href="#"
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="shrink-0 w-5 h-5 text-neutral transition duration-75 group-hover:text-ink" aria-hidden="true"
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
                        class="flex items-center p-2 rounded-lg text-ink hover:bg-highlight/20 group">
                        <svg class="w-5 h-5 text-neutral transition duration-75 group-hover:text-ink"
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