<?php require view_path('layouts/start.php'); ?>

<main class="min-h-screen flex flex-col items-center mt-8 gap-8 px-8 lg:px-10 bg-light">

    <!-- Tabs -->
    <ul class="w-full max-w-2xl text-sm font-medium text-center rounded-lg shadow-sm flex overflow-hidden border">
        <li class="w-full">
            <a href="#" data-tab="tab-visaogeral"
                class="tab-link  inline-block w-full p-4 bg-surface text-neutral border-r border-line rounded-s-lg hover:text-ink hover:bg-highlight/20 focus:outline-none ">
                Visão Geral
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-novocontacto"
                class="tab-link inline-block w-full p-4 bg-surface text-neutral border-r border-line hover:text-ink hover:bg-highlight/20 focus:outline-none ">
                Contactos
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-segmentos"
                class="tab-link inline-block w-full p-4 bg-surface text-neutral border-r border-line hover:text-ink hover:bg-highlight/20 focus:outline-none ">
                Segmentos
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-listas"
                class="tab-link inline-block w-full p-4 bg-surface text-neutral rounded-e-lg hover:text-ink hover:bg-highlight/20 focus:outline-none ">
                Listas
            </a>
        </li>
    </ul>

    <?php require view_path('publico/_overview.php'); ?>

    <?php require view_path('publico/_contactos.php'); ?>

    <?php require view_path('publico/_segmentos.php'); ?>

    <?php require view_path('publico/_listas.php'); ?>

    <?php require view_path('publico/_modals.php'); ?>

    <script>
        const dadosGraficoCrescimento = <?= json_encode($dadosGrafico) ?>;
    </script>
</main>



<?php require view_path('layouts/footer.php'); ?>
