<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<div class="flex items-center justify-center mt-50">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
        <!-- Campaign Manager -->
        <a href="/campanhas" class="group block rounded-2xl shadow-md bg-gray-800 transition p-6">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Campaign Manager</h2>
                    <p class="text-lg text-gray-500">CRM System</p>
                </div>
            </div>
        </a>

        <!-- Password Manager -->
        <a href="/pm-home" class="group block rounded-2xl shadow-md bg-gray-800 transition p-6">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Password Manager</h2>
                    <p class="text-lg text-gray-500">Store and organize your passwords</p>
                </div>
            </div>
        </a>

        <!-- In development -->
        <a href="/publico" class="group block rounded-2xl shadow-md bg-gray-800 transition p-6">
            <div class="flex items-center space-x-4">
                <div>
                    <h2 class="text-3xl font-semibold text-white mb-4 transition group-hover:text-blue-500">Público</h2>
                    <p class="text-lg text-gray-500">Gestão de Contactos</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php require __DIR__ . '/partials/footer.php'; ?>