<?php require view_path('layouts/start.php'); ?>

<main class="app-center bg-light px-8 py-10">
    <div class="w-full max-w-5xl mx-auto">
        <div class="mb-8">
            <p class="text-sm font-medium text-accent mb-2">Área de trabalho</p>
            <h1 class="text-3xl font-semibold tracking-tight text-ink">As suas aplicações</h1>
            <p class="text-neutral mt-3">Tudo o que precisa para gerir o seu dia a dia.</p>
        </div>
        <div class="grid grid-cols-2 gap-5">

        <!-- Card -->
        <a href="/campanhas"
            class="app-card group relative block p-7 cursor-pointer focus:ring-4 focus:ring-primary/30">
            <div>
                <h2 class="text-xl font-semibold text-ink mb-2">Campaign Manager</h2>
                <p class="text-sm text-neutral">Gestão de campanhas e comunicação.</p>
                <span class="inline-flex items-center gap-2 mt-6 text-sm font-medium text-accent">Abrir aplicação <span aria-hidden="true">&rarr;</span></span>
            </div>
        </a>

        <a href="/pm-home"
            class="app-card group relative block p-7 cursor-pointer focus:ring-4 focus:ring-primary/30">
            <div>
                <h2 class="text-xl font-semibold text-ink mb-2">Password Manager</h2>
                <p class="text-sm text-neutral">Guarde e organize os seus acessos.</p>
                <span class="inline-flex items-center gap-2 mt-6 text-sm font-medium text-accent">Abrir aplicação <span aria-hidden="true">&rarr;</span></span>
            </div>
        </a>

        <a href="/publico"
            class="app-card group relative block p-7 cursor-pointer focus:ring-4 focus:ring-primary/30">
            <div>
                <h2 class="text-xl font-semibold text-ink mb-2">Público</h2>
                <p class="text-sm text-neutral">Contactos, listas e segmentos num só lugar.</p>
                <span class="inline-flex items-center gap-2 mt-6 text-sm font-medium text-accent">Abrir aplicação <span aria-hidden="true">&rarr;</span></span>
            </div>
        </a>

        <a href="/campanhas.php"
            class="app-card group relative block p-7 cursor-pointer focus:ring-4 focus:ring-primary/30">
            <div>
                <h2 class="text-xl font-semibold text-ink mb-2">Acções de Campanhas</h2>
                <p class="text-sm text-neutral">Conteúdos e publicações para redes sociais.</p>
                <span class="inline-flex items-center gap-2 mt-6 text-sm font-medium text-accent">Abrir aplicação <span aria-hidden="true">&rarr;</span></span>
            </div>
        </a>
        </div>
    </div>
</main>

<?php require view_path('layouts/footer.php'); ?>
