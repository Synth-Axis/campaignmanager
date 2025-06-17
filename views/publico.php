<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center mt-10 gap-8">
    <!-- Mobile Tabs -->
    <div class="sm:hidden">
        <label for="tabs" class="sr-only">Selecionar secção</label>
        <select id="tabs" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            <option value="tab-visaogeral">Visão Geral</option>
            <option value="tab-novocontacto">Novo Contacto</option>
            <option value="tab-segmentos">Segmentos</option>
            <option value="tab-listas">Listas</option>
        </select>
    </div>

    <!-- Desktop Tabs -->
    <ul class="w-full max-w-2xl hidden text-sm font-medium text-center text-gray-500 rounded-lg shadow-sm sm:flex dark:divide-gray-700 dark:text-gray-400">
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-visaogeral"
                class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 rounded-s-lg hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Visão Geral
            </a>
        </li>
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-novocontacto"
                class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Contactos
            </a>
        </li>
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-segmentos"
                class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-r border-gray-200 dark:border-gray-700 hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Segmentos
            </a>
        </li>
        <li class="w-full focus-within:z-10">
            <a href="#" data-tab="tab-listas"
                class="tab-link inline-block w-full p-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 border-gray-200 dark:border-gray-700 rounded-e-lg hover:text-gray-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700">
                Listas
            </a>
        </li>
    </ul>

    <!-- TAB: Visão Geral -->
    <section id="tab-visaogeral" class="hidden w-full max-w-2xl p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Visão Geral</h2>
        <p class="text-gray-700 dark:text-gray-300">
            Aqui pode ver um resumo geral das suas campanhas, contactos e atividades recentes.
        </p>
        <div class="grid grid-cols-2 gap-4">
            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">Campanhas Ativas</h3>
                <p class="text-gray-600 dark:text-gray-300">Total: 5 campanhas em curso.</p>
            </div>
            <div class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <h3 class="text-lg font-medium text-gray-800 dark:text-white">Novos Contactos</h3>
                <p class="text-gray-600 dark:text-gray-300">Últimos 7 dias: 23 registos.</p>
            </div>
        </div>
    </section>

    <!-- TAB: Contactos -->
    <section id="tab-novocontacto" class="hidden w-full max-w-full p-0 px-50">

        <!-- Tabs secundários de contactos, visual compacto -->
        <div class="w-full flex justify-center -mt-4 mb-8">
            <ul class="flex max-w-lg w-full text-xs font-medium text-center text-gray-500 dark:text-gray-400 bg-transparent rounded-md shadow-none gap-4" id="contactos-subtabs">
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-todos-contactos"
                        class="contact-tab-link block w-full py-2 px-1 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md hover:text-blue-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700 transition-all">
                        Todos os Contactos
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-inserir-contacto"
                        class="contact-tab-link block w-full py-2 px-1 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md hover:text-blue-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700 transition-all">
                        Inserir Contacto
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-importar-ficheiro"
                        class="contact-tab-link block w-full py-2 px-1 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 rounded-md hover:text-blue-700 hover:bg-gray-50 dark:hover:text-white dark:hover:bg-gray-700 transition-all">
                        Importar por Ficheiro
                    </a>
                </li>
            </ul>
        </div>

        <!-- Todos os Contactos -->
        <div id="tab-todos-contactos" class="contact-tab-content hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6 mb-10 space-y-4 w-full mx-auto">


            <!-- Linha de título + pesquisa -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Todos os Contactos</h5>
                <div class="relative w-full md:w-72">
                    <input
                        type="text"
                        id="pesquisar-contactos"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-gray-300 rounded-lg bg-gray-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Pesquisar contacto..." />
                    <span class="absolute left-3 top-2.5 text-gray-400 pointer-events-none">
                        <!-- Lupa SVG para dark/light mode -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
            </div>
            <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-600">
                        <th class="py-2 px-3">ID</th>
                        <th class="py-2 px-3">Nome</th>
                        <th class="py-2 px-3">Email</th>
                        <th class="py-2 px-3">Gestor</th>
                        <th class="py-2 px-3">Canal</th>
                        <th class="py-2 px-3">Lista</th>
                        <th class="py-2 px-3">Data Registo</th>
                        <th class="py-2 px-3 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody id="tabela-contactos">
                    <?php foreach ($contactos as $contacto): ?>
                        <tr class="border-b border-gray-100 dark:border-gray-700">
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['publico_id']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['nome']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['email']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['gestor_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['canal_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['lista_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['data_registo'] ?? '') ?></td>
                            <td class="py-2 px-3 text-right"></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div id="paginacao-contactos" class="flex justify-center mt-4"></div>
        </div>

        <!-- Inserir Contacto -->
        <form id="tab-inserir-contacto" method="POST" action="novo-registo" class="max-w-4xl mx-auto contact-tab-content hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-8 space-y-6">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Novo registo</h5>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                <div>
                    <label for="nome" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>" placeholder="Insira o nome"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="name@empresa.com"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div>
                    <label for="agente" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Agente</label>
                    <input type="text" name="agente" id="agente" value="<?= htmlspecialchars($agente ?? '') ?>" placeholder="Nome do agente"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
                <div class="col-span-2 md:col-span-1">
                    <label for="gestor" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gestor</label>
                    <select name="gestor" id="gestor"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="" <?= empty($gestor_id) ? 'selected' : '' ?>>Selecione um gestor</option>
                        <?php foreach ($gestores as $gestor): ?>
                            <option value="<?= htmlspecialchars($gestor['gestor_id']) ?>" <?= (isset($gestor_id) && $gestor_id == $gestor['gestor_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($gestor['gestor_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="lista" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Lista</label>
                    <select name="lista" id="lista"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="" <?= empty($lista_id) ? 'selected' : '' ?>>Selecione a lista</option>
                        <?php foreach ($listas as $lista): ?>
                            <option value="<?= htmlspecialchars($lista['lista_id']) ?>" <?= (isset($lista_id) && $lista_id == $lista['lista_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lista['lista_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="canal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Canal</label>
                    <select name="canal" id="canal"
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="" <?= empty($canal_id) ? 'selected' : '' ?>>Selecione o canal</option>
                        <?php foreach ($channels as $canal): ?>
                            <option value="<?= htmlspecialchars($canal['canal_id']) ?>" <?= (isset($canal_id) && $canal_id == $canal['canal_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($canal['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <input type="hidden" name="csrf_token" value="<?= $_SESSION["csrf_token"] ?>">
            <div class="col-span-2 mt-3">
                <button type="submit" name="send"
                    class="w-full px-5 py-2.5 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                    Submeter
                </button>
            </div>
            <div class="h-5 flex justify-center items-center">
                <?php if (!empty($_SESSION['message'])): ?>
                    <div><?= $_SESSION['message'] ?></div>
                    <?php unset($_SESSION['message']); ?>
                <?php endif; ?>
            </div>
        </form>

        <!-- Torna o container mais largo -->
        <div id="tab-importar-ficheiro"
            class="contact-tab-content hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow p-6 space-y-4 w-full max-w-4xl mx-auto">
            <h5 class="text-lg font-semibold text-gray-900 dark:text-white">Importar Contactos por Ficheiro</h5>
            <form id="form-importar-ficheiro" method="POST" action="/api/importar_publico.php" enctype="multipart/form-data" class="space-y-4">
                <input
                    type="file"
                    name="ficheiro"
                    accept=".csv,.xlsx"
                    required
                    class="p-2 block max-w-4xl text-sm text-gray-900 dark:text-white border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:bg-gray-700 dark:border-gray-600">
                <button type="submit" class="px-5 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">Importar</button>
            </form>
        </div>
    </section>

    <!-- TAB: Segmentos -->
    <section id="tab-segmentos" class="hidden w-full max-w-2xl p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-4">
        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Segmentos</h2>
        <p class="text-gray-700 dark:text-gray-300">
            Esta secção permite gerir os segmentos de público para campanhas mais direcionadas.
        </p>
        <ul class="list-disc pl-6 text-gray-700 dark:text-gray-300">
            <li>Segmento A - Clientes com mais de 3 produtos</li>
            <li>Segmento B - Novos contactos nos últimos 30 dias</li>
            <li>Segmento C - Campanhas anteriores sem resposta</li>
        </ul>
    </section>

    <!-- TAB: Listas -->
    <section id="tab-listas" class="hidden w-full max-w-2xl p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Listas</h2>
            <a href="#" data-modal="nova-lista" class="abrir-modal px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none">Nova Lista</a>
        </div>
        <p class="text-gray-700 dark:text-gray-300">
            Abaixo encontra-se a lista de grupos de contactos prontos para envio de campanhas.
        </p>
        <table class="w-full text-sm text-left text-gray-700 dark:text-gray-300 border-collapse">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-600">
                    <th scope="col" class="py-2 px-3">Nome da Lista</th>
                    <th scope="col" class="py-2 px-3 text-right">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listas as $lista): ?>
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 px-3"><?= htmlspecialchars($lista['lista_nome']) ?></td>
                        <td class="py-2 px-3 text-right">
                            <select onchange="handleListaAcao(this)" class="select-acao-lista bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white text-sm rounded-lg p-1 cursor-pointer" data-id="<?= $lista['lista_id'] ?>" data-nome="<?= htmlspecialchars($lista['lista_nome']) ?>">
                                <option selected disabled>Ações</option>
                                <option value="editar">Editar</option>
                                <option value="apagar">Apagar</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Modal Listas wrapper -->
    <div id="modal-nova-lista" class="modal hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-content bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-600 rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">

            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Criar Nova Lista</h3>

            <form method="POST" action="" id="form-nova-lista">
                <input type="hidden" name="action" value="nova_lista">
                <div>
                    <label for="nova_lista_nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome da Lista</label>
                    <input type="text" name="nova_lista_nome" id="nova_lista_nome" required
                        class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                        Criar
                    </button>
                </div>
            </form>

        </div>
    </div>

    <!-- Modal Editar Lista -->
    <div id="modal-editar-lista" class="modal hidden fixed inset-0 flex items-center justify-center z-50">
        <div class="modal-content bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-600 rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Editar Lista</h3>

            <form method="POST" id="form-editar-lista">
                <input type="hidden" name="action" value="editar_lista">
                <input type="hidden" name="lista_id" id="editar_lista_id">
                <div>
                    <label for="editar_lista_nome" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Novo nome</label>
                    <input type="text" name="lista_nome" id="editar_lista_nome" required
                        class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 cursor-pointer">
                        Alterar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alerta Custom -->
    <div id="alerta-custom" class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden transition-opacity duration-300 opacity-0 text-center">
        <span id="alerta-mensagem"></span>
    </div>

    <!-- Modal de Confirmação Apagar -->
    <div id="modal-confirmar-apagar" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
        <div class="modal-content bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-600 rounded-lg shadow-lg w-full max-w-sm p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Confirmar Apagar</h3>
            <p class="text-sm text-gray-600 dark:text-gray-300">Tem a certeza que deseja apagar esta lista?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button id="cancelar-apagar" class="px-4 py-2 bg-gray-300 text-gray-800 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-white">Cancelar</button>
                <button id="confirmar-apagar" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">Apagar</button>
            </div>
        </div>
    </div>



</main>


<?php require __DIR__ . '/partials/footer.php'; ?>