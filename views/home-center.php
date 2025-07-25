<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<div class="flex items-center justify-center mt-24">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">

        <a href="/campanhas" class="group block rounded-2xl shadow-md bg-slate-800 bg-opacity-50 transition transform  hover:scale-105 p-6 border border-blue-600">

            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Campaign Manager</h2>
                    <p class="text-lg text-gray-500">CRM System</p>
                </div>
            </div>
        </a>


        <a href="/pm-home" class="group block rounded-2xl shadow-md bg-slate-800 bg-opacity-50 transition transform duration-300 hover:scale-105 p-6 border border-blue-600">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Password Manager</h2>
                    <p class="text-lg text-gray-500">Store and organize your passwords</p>
                </div>
            </div>
        </a>


        <a href="/publico" class="group block rounded-2xl shadow-md bg-slate-800 bg-opacity-50 transition transform duration-300 hover:scale-105 p-6 border border-blue-600">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Público</h2>
                    <p class="text-lg text-gray-500">Gestão de Contactos</p>
                </div>
            </div>
        </a>

        <a href="#" class="group block rounded-2xl shadow-md bg-slate-800 bg-opacity-50 transition transform duration-300 hover:scale-105 p-6 border border-blue-600">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Acções de Campanhas</h2>
                    <p class="text-lg text-gray-500">Posts e Redes Sociais</p>
                </div>
            </div>
        </a>

        <a href="#" class="group block rounded-2xl shadow-md bg-slate-800 bg-opacity-50 transition transform duration-300 hover:scale-105 p-6 border border-blue-600">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Acções da Carrinha</h2>
                    <p class="text-lg text-gray-500">Accões comerciais com a carrinha Real</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>