<!DOCTYPE html>
<html lang="pt-PT">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#F5F7FA" id="app-theme-color">
    <script src="<?= asset_url('assets/js/theme.js') ?>"></script>
    <link rel="stylesheet" href="<?= asset_url('dist/output.css') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="icon" type="image/svg+xml" href="<?= asset_url('favicon.svg') ?>">
    <title>Lynx App Center</title>
<?php foreach (page_scripts() as $script): ?>
    <script defer src="<?= htmlspecialchars($script, ENT_QUOTES, 'UTF-8') ?>"></script>
<?php endforeach; ?>
</head>

<body class="app-layout bg-light min-h-screen font-sans <?= !empty($_SESSION['user_id']) ? 'has-sidebar' : '' ?>">
    <section class="device-notice" aria-labelledby="device-notice-title">
        <div class="w-full max-w-sm rounded-2xl bg-surface border border-line p-7 text-center shadow-sm">
            <div class="mx-auto mb-6 w-20 h-20 flex items-center justify-center rounded-2xl bg-highlight/30 text-accent" aria-hidden="true">
                <svg class="w-12 h-12" viewBox="0 0 48 48" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="4" y="8" width="29" height="22" rx="3" />
                    <path d="M14 38h12M20 30v8" />
                    <rect x="29" y="17" width="15" height="23" rx="3" />
                    <path d="M35 36h3" />
                </svg>
            </div>
            <p class="mb-3 text-sm font-semibold text-accent">Lynx</p>
            <h1 id="device-notice-title" class="text-2xl font-semibold tracking-tight text-ink">Disponível em tablet e desktop</h1>
            <p class="mt-4 text-sm leading-relaxed text-neutral">Devido às funcionalidades disponíveis, a LynxApp não suporta a utilização em telemóveis.</p>
            <p class="mt-3 text-sm leading-relaxed text-neutral">Para continuar, abra a aplicação num tablet ou computador.</p>
        </div>
    </section>
