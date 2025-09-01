<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<div class="bg-light w-full flex justify-center items-center py-24">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 gap-8 w-full max-w-4xl px-6">

        <!-- Card -->
        <a href="/campanhas"
            class="group relative block rounded-2xl bg-white p-6 shadow-md cursor-pointer
          transform transition duration-400 ease-in-out
          hover:-translate-y-1 hover:scale-105 hover:shadow-xl hover:bg-primary
          focus:outline-none focus:ring-4 focus:ring-primary/40">
            <div>
                <h2 class="text-3xl font-semibold text-dark mb-3 group-hover:text-white">Campaign Manager</h2>
                <p class="text-lg text-neutral group-hover:text-light">CRM System</p>
            </div>
        </a>

        <a href="/pm-home"
            class="group relative block rounded-2xl bg-white p-6 shadow-md cursor-pointer
          transform transition duration-400 ease-in-out
          hover:-translate-y-1 hover:scale-105 hover:shadow-xl hover:bg-primary
          focus:outline-none focus:ring-4 focus:ring-primary/40">
            <div>
                <h2 class="text-3xl font-semibold text-dark mb-3 group-hover:text-light">Password Manager</h2>
                <p class="text-lg text-neutral group-hover:text-light">Store and organize your passwords</p>
            </div>
        </a>

        <a href="/publico"
            class="group relative block rounded-2xl bg-white p-6 shadow-md cursor-pointer
          transform transition duration-400 ease-in-out
          hover:-translate-y-1 hover:scale-105 hover:shadow-xl hover:bg-primary
          focus:outline-none focus:ring-4 focus:ring-primary/40">
            <div>
                <h2 class="text-3xl font-semibold text-dark mb-3 group-hover:text-light">Público</h2>
                <p class="text-lg text-neutral group-hover:text-light">Gestão de Contactos</p>
            </div>
        </a>

        <a href="#"
            class="group relative block rounded-2xl bg-white p-6 shadow-md cursor-pointer
          transform transition duration-400 ease-in-out
          hover:-translate-y-1 hover:scale-105 hover:shadow-xl hover:bg-primary
          focus:outline-none focus:ring-4 focus:ring-primary/40">
            <div>
                <h2 class="text-3xl font-semibold text-dark mb-3 group-hover:text-light">Acções de Campanhas</h2>
                <p class="text-lg text-neutral group-hover:text-light">Posts e Redes Sociais</p>
            </div>
        </a>

        <a href="#"
            class="group relative block rounded-2xl bg-white p-6 shadow-md cursor-pointer
          transform transition duration-400 ease-in-out
          hover:-translate-y-1 hover:scale-105 hover:shadow-xl hover:bg-primary
          focus:outline-none focus:ring-4 focus:ring-primary/40">
            <div>
                <h2 class="text-3xl font-semibold text-dark mb-3 group-hover:text-light">Acções da Carrinha</h2>
                <p class="text-lg text-neutral group-hover:text-light">Acções comerciais com a carrinha Real</p>
            </div>
        </a>

    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>