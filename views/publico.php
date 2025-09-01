<?php require __DIR__ . '/partials/auth.php'; ?>
<?php require __DIR__ . '/partials/head.php'; ?>
<?php require __DIR__ . '/partials/nav.php'; ?>

<main class="min-h-screen flex flex-col items-center mt-10 gap-8 px-10 bg-light">

    <!-- Select (mobile) -->
    <div class="sm:hidden w-full max-w-2xl">
        <label for="tabs" class="sr-only">Selecionar secção</label>
        <select id="tabs"
            class="bg-white border border-light text-dark text-sm rounded-lg block w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/30">
            <option value="tab-visaogeral">Visão Geral</option>
            <option value="tab-novocontacto">Novo Contacto</option>
            <option value="tab-segmentos">Segmentos</option>
            <option value="tab-listas">Listas</option>
        </select>
    </div>

    <!-- Tabs (desktop) -->
    <ul class="w-full max-w-2xl hidden text-sm font-medium text-center rounded-lg shadow-sm sm:flex overflow-hidden">
        <li class="w-full">
            <a href="#" data-tab="tab-visaogeral"
                class="tab-link inline-block w-full p-4 bg-white text-neutral border-r border-light rounded-s-lg hover:text-dark hover:bg-highlight/20 focus:outline-none focus:ring-2 focus:ring-primary/30">
                Visão Geral
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-novocontacto"
                class="tab-link inline-block w-full p-4 bg-white text-neutral border-r border-light hover:text-dark hover:bg-highlight/20 focus:outline-none focus:ring-2 focus:ring-primary/30">
                Contactos
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-segmentos"
                class="tab-link inline-block w-full p-4 bg-white text-neutral border-r border-light hover:text-dark hover:bg-highlight/20 focus:outline-none focus:ring-2 focus:ring-primary/30">
                Segmentos
            </a>
        </li>
        <li class="w-full">
            <a href="#" data-tab="tab-listas"
                class="tab-link inline-block w-full p-4 bg-white text-neutral rounded-e-lg hover:text-dark hover:bg-highlight/20 focus:outline-none focus:ring-2 focus:ring-primary/30">
                Listas
            </a>
        </li>
    </ul>

    <!-- Visão Geral -->
    <section id="tab-visaogeral"
        class="tab-content w-full max-w-screen-xl px-6 py-8 bg-white border border-primary rounded-lg shadow space-y-6">
        <h2 class="text-xl font-semibold text-dark">Visão Geral</h2>

        <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
            <div class="space-y-6 col-span-1 md:col-span-2 lg:col-span-2">
                <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                    <h3 class="text-2xl font-extrabold text-dark tracking-tight mb-2">Resumo</h3>
                    <p class="text-3xl font-bold text-primary"><?= $totalContactos ?></p>
                    <p class="text-sm text-neutral">Todos contactos</p>
                    <p class="text-xs mt-2 text-neutral">Novos hoje: <?= $novosHoje ?></p>
                </div>

                <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                    <h3 class="text-2xl font-extrabold text-dark tracking-tight mb-2">Crescimento recente</h3>
                    <p class="text-sm text-dark/80">Últimos 30 dias</p>
                    <p class="text-sm mt-1 text-neutral">Novos contactos: <?= $contactosUltimos30Dias ?></p>
                    <p class="text-sm text-neutral">Desinscritos: 0</p>
                </div>

                <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                    <div class="flex justify-between items-stretch mb-2 min-h-[4rem]">
                        <h3 class="text-2xl font-extrabold text-dark tracking-tight self-start">
                            Desempenho</br>do público
                        </h3>
                        <select class="rounded-md self-start text-sm bg-white border border-light px-2 py-1 text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <option>Últimos 7 dias</option>
                            <option>15 dias</option>
                            <option>1 mês</option>
                            <option>3 meses</option>
                            <option>6 meses</option>
                            <option>1 ano</option>
                            <option>2 anos</option>
                            <option>3 anos</option>
                        </select>
                    </div>
                    <p class="text-sm text-neutral">Avg. taxa aberta: 0.00%</p>
                    <p class="text-sm text-neutral">Avg. taxa de cliques: 0.00%</p>
                </div>
            </div>

            <div class="col-span-1 md:col-span-3 lg:col-span-3">
                <div class="p-6 bg-light rounded-lg shadow-sm border border-light">
                    <div class="flex justify-between items-stretch mb-2 min-h-[4rem]">
                        <h3 class="text-2xl font-extrabold text-dark tracking-tight self-start mb-2">
                            Crescimento de contactos
                        </h3>
                        <select id="filtro-periodo"
                            class="rounded-md self-start text-sm bg-white border border-light px-2 py-1 text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <option>Últimos 7 dias</option>
                            <option>15 dias</option>
                            <option>1 mês</option>
                            <option>3 meses</option>
                            <option>6 meses</option>
                            <option>1 ano</option>
                            <option>2 anos</option>
                            <option>3 anos</option>
                        </select>
                    </div>
                    <canvas id="grafico-crescimento" class="w-full h-64"></canvas>
                </div>
            </div>
        </div>
    </section>

    <!-- Contactos -->
    <section id="tab-novocontacto" class="hidden w-full max-w-full p-0">

        <!-- Subtabs -->
        <div class="w-full flex justify-center -mt-4 mb-8">
            <ul id="contactos-subtabs"
                class="flex max-w-lg w-full text-xs font-medium text-center bg-transparent rounded-md shadow-none gap-4">
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-todos-contactos"
                        class="contact-tab-link block w-full py-2 px-1 bg-white text-neutral rounded-md hover:text-dark hover:bg-highlight/20 transition">
                        Todos os Contactos
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-inserir-contacto"
                        class="contact-tab-link block w-full py-2 px-1 bg-white text-neutral rounded-md hover:text-dark hover:bg-highlight/20 transition">
                        Inserir Contacto
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-importar-ficheiro"
                        class="contact-tab-link block w-full py-2 px-1 bg-white text-neutral rounded-md hover:text-dark hover:bg-highlight/20 transition">
                        Importar por Ficheiro
                    </a>
                </li>
            </ul>
        </div>

        <!-- Todos os Contactos -->
        <div id="tab-todos-contactos"
            class="contact-tab-content hidden rounded-lg shadow p-6 mb-10 space-y-4 w-full max-w-7xl mx-auto bg-white border border-primary">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-2 mb-6">
                <h5 class="text-lg font-semibold text-dark">Todos os Contactos</h5>
                <div class="relative w-full md:w-72">
                    <input type="text" id="pesquisar-contactos"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-light rounded-lg bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Pesquisar contacto..." />
                    <span class="absolute left-3 top-2.5 text-neutral pointer-events-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
            </div>

            <form method="POST" action="/api/exportar_contactos.php" id="form-exportar-contactos">
                <div class="flex items-center gap-4 mb-4 flex-wrap">
                    <label class="text-sm text-dark">Exportar como:</label>
                    <select name="formato" class="border border-light rounded px-2 py-1 text-dark bg-white focus:outline-none focus:ring-2 focus:ring-primary/30">
                        <option value="csv">CSV</option>
                        <option value="xlsx">XLSX</option>
                    </select>

                    <label class="text-sm text-dark">Campos:</label>
                    <?php foreach (['publico_id' => 'ID', 'nome' => 'Nome', 'email' => 'Email', 'gestor' => 'Gestor', 'canal' => 'Canal', 'lista' => 'Lista', 'data_registo' => 'Data Registo'] as $campo => $rotulo): ?>
                        <label class="text-sm mr-2 text-dark">
                            <input type="checkbox" name="campos[]" value="<?= $campo ?>" checked> <?= $rotulo ?>
                        </label>
                    <?php endforeach; ?>

                    <input type="hidden" name="todos" id="exportar-todos" value="0">
                    <button type="submit" class="cursor-pointer bg-primary text-white px-4 py-2 rounded hover:bg-primary/90">Exportar</button>
                </div>
            </form>

            <table class="w-full text-sm text-left text-dark border-collapse">
                <thead>
                    <tr class="border-b border-light bg-light">
                        <th class="py-2 px-3"><input type="checkbox" id="selecionar-todos"></th>
                        <th class="py-2 px-3">ID</th>
                        <th class="py-2 px-3">Nome</th>
                        <th class="py-2 px-3">Email</th>
                        <th class="py-2 px-3">Gestor</th>
                        <th class="py-2 px-3">Canal</th>
                        <th class="py-2 px-3">Lista</th>
                        <th class="py-2 px-3">Data Registo</th>
                        <th class="py-2 px-3 text-right">Acções</th>
                    </tr>
                </thead>
                <tbody id="tabela-contactos">
                    <?php foreach ($contactos as $contacto): ?>
                        <tr class="border-b border-light hover:bg-highlight/10 transition">
                            <td class="py-2 px-3">
                                <input type="checkbox" class="checkbox-contacto" name="contactosSelecionados[]" value="<?= $contacto['publico_id'] ?>">
                            </td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['publico_id']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['nome']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['email']) ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['gestor_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['canal_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['lista_nome'] ?? '') ?></td>
                            <td class="py-2 px-3"><?= htmlspecialchars($contacto['data_registo'] ?? '') ?></td>
                            <td class="py-2 px-3 text-right">
                                <select onchange="handleAcaoContacto(this)"
                                    class="select-acao-contacto bg-white border border-light text-dark text-sm rounded-lg p-1 cursor-pointer"
                                    data-id="<?= $contacto['publico_id'] ?>"
                                    data-nome="<?= htmlspecialchars($contacto['nome']) ?>"
                                    data-email="<?= htmlspecialchars($contacto['email']) ?>"
                                    data-gestor="<?= htmlspecialchars($contacto['gestor_nome'] ?? '') ?>"
                                    data-canal="<?= htmlspecialchars($contacto['canal_nome'] ?? '') ?>"
                                    data-lista="<?= htmlspecialchars($contacto['lista_nome'] ?? '') ?>">
                                    <option selected disabled>Acções</option>
                                    <option value="editar">Editar</option>
                                    <option value="apagar">Apagar</option>
                                </select>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <div id="paginacao-contactos" class="flex justify-center mt-4"></div>
        </div>

        <!-- Inserir Contacto -->
        <form id="tab-inserir-contacto" method="POST" action=""
            class="max-w-4xl mx-auto contact-tab-content hidden rounded-lg shadow p-8 space-y-6 bg-white border border-primary">
            <h5 class="text-lg font-semibold text-dark">Novo registo</h5>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-10">
                <div>
                    <label for="nome" class="block mb-2 text-sm font-medium text-dark">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>" placeholder="Insira o nome"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-dark">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="name@empresa.com"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>

                <div class="col-span-2 md:col-span-1">
                    <label for="gestor" class="block mb-2 text-sm font-medium text-dark">Gestor</label>
                    <select name="gestor" id="gestor"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <option value="" <?= empty($gestor_id) ? 'selected' : '' ?>>Selecione um gestor</option>
                        <?php foreach ($gestores as $gestor): ?>
                            <option value="<?= htmlspecialchars($gestor['gestor_id']) ?>" <?= (isset($gestor_id) && $gestor_id == $gestor['gestor_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($gestor['gestor_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="lista" class="block mb-2 text-sm font-medium text-dark">Lista</label>
                    <select name="lista" id="lista"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <option value="" <?= empty($lista_id) ? 'selected' : '' ?>>Selecione a lista</option>
                        <?php foreach ($listas as $lista): ?>
                            <option value="<?= htmlspecialchars($lista['lista_id']) ?>" <?= (isset($lista_id) && $lista_id == $lista['lista_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lista['lista_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="canal" class="block mb-2 text-sm font-medium text-dark">Canal</label>
                    <select name="canal" id="canal"
                        class="bg-white border border-light text-dark text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
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

            <div class="col-span-2 mt-10 flex justify-center">
                <button type="submit" name="send"
                    class="cursor-pointer w-full max-w-xs px-5 py-2.5 text-sm font-medium text-white bg-primary hover:bg-primary/90 rounded-lg focus:outline-none focus:ring-4 focus:ring-primary/30">
                    Submeter
                </button>
            </div>

            <div class="h-5 flex justify-center items-center">
                <?php if (!empty($_SESSION['message'])): ?>
                    <div class="<?= ($_SESSION['message_type'] ?? 'success') === 'success' ? 'text-success' : 'text-danger' ?>">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            </div>
        </form>

        <!-- Importar por ficheiro -->
        <div id="tab-importar-ficheiro"
            class="contact-tab-content hidden rounded-lg shadow p-6 space-y-4 w-full max-w-4xl mx-auto bg-white border border-primary">
            <h5 class="text-lg font-semibold text-dark">Importar Contactos por Ficheiro</h5>

            <form id="form-importar-ficheiro" method="POST" action="/api/importar_publico.php" enctype="multipart/form-data" class="space-y-4">
                <label for="ficheiro"
                    class="block max-w-2xs p-2 bg-white border border-light rounded-lg cursor-pointer text-neutral text-sm text-center hover:bg-highlight/20 transition">
                    <span id="ficheiro-label-text">Escolher o ficheiro CSV/Excel</span>
                    <input
                        type="file"
                        id="ficheiro"
                        name="ficheiro"
                        accept=".csv,.xlsx"
                        required
                        class="hidden"
                        onchange="document.getElementById('ficheiro-label-text').innerText = this.files[0]?.name || 'Escolher o ficheiro CSV/Excel'">
                </label>

                <button type="submit"
                    class="cursor-pointer px-5 py-2 text-white bg-primary rounded hover:bg-primary/90">
                    Importar
                </button>
            </form>
        </div>
    </section>

    <!-- Segmentos -->
    <section id="tab-segmentos"
        class="hidden w-full max-w-2xl p-6 bg-white border border-primary rounded-lg shadow space-y-4">
        <h2 class="text-xl font-semibold text-dark">Segmentos</h2>
        <p class="text-neutral">
            Esta secção permite gerir os segmentos de público para campanhas mais direcionadas.
        </p>
        <ul class="list-disc pl-6 text-neutral">
            <li>Segmento A - Clientes com mais de 3 produtos</li>
            <li>Segmento B - Novos contactos nos últimos 30 dias</li>
            <li>Segmento C - Campanhas anteriores sem resposta</li>
        </ul>
    </section>

    <!-- Listas -->
    <section id="tab-listas"
        class="hidden w-full max-w-2xl p-6 bg-white border border-primary rounded-lg shadow space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-dark">Listas</h2>
            <a href="#" data-modal="nova-lista"
                class="abrir-modal px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:outline-none">
                Nova Lista
            </a>
        </div>
        <p class="text-neutral">
            Abaixo encontra-se a lista de grupos de contactos prontos para envio de campanhas.
        </p>

        <table class="w-full text-sm text-left text-dark border-collapse">
            <thead>
                <tr class="border-b border-light">
                    <th scope="col" class="py-2 px-3">Nome da Lista</th>
                    <th scope="col" class="py-2 px-3 text-right">Acções</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listas as $lista): ?>
                    <tr class="border-b border-light hover:bg-highlight/10 transition">
                        <td class="py-2 px-3"><?= htmlspecialchars($lista['lista_nome']) ?></td>
                        <td class="py-2 px-3 text-right">
                            <select onchange="handleListaAcao(this)"
                                class="cursor-pointer select-acao-lista bg-white border border-light text-dark text-sm rounded-lg p-1"
                                data-id="<?= $lista['lista_id'] ?>"
                                data-nome="<?= htmlspecialchars($lista['lista_nome']) ?>">
                                <option selected disabled>Acções</option>
                                <option value="editar">Editar</option>
                                <option value="apagar">Apagar</option>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

    <!-- Modal Nova Lista -->
    <div id="modal-nova-lista" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
        <div class="modal-content bg-white border border-light rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-dark">Criar Nova Lista</h3>
            <form method="POST" action="" id="form-nova-lista">
                <input type="hidden" name="action" value="nova_lista">
                <div>
                    <label for="nova_lista_nome" class="block text-sm font-medium text-dark">Nome da Lista</label>
                    <input type="text" name="nova_lista_nome" id="nova_lista_nome" required
                        class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 cursor-pointer">
                        Criar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Lista -->
    <div id="modal-editar-lista" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
        <div class="modal-content bg-white border border-light rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-dark">Editar Lista</h3>
            <form method="POST" id="form-editar-lista">
                <input type="hidden" name="action" value="editar_lista">
                <input type="hidden" name="lista_id" id="editar_lista_id">
                <div>
                    <label for="editar_lista_nome" class="block text-sm font-medium text-dark">Novo nome</label>
                    <input type="text" name="lista_nome" id="editar_lista_nome" required
                        class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 cursor-pointer">
                        Alterar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Editar Contacto -->
    <div id="modal-editar-contacto" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
        <div class="modal-content bg-white border border-light rounded-lg shadow-lg w-full max-w-4xl p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-dark">Editar Contacto</h3>
            <form method="POST" id="form-editar-contacto">
                <input type="hidden" name="action" value="editar_contacto">
                <input type="hidden" name="contacto_id" id="editar_contacto_id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="editar_nome" class="block text-sm font-medium text-dark">Nome</label>
                        <input type="text" name="nome" id="editar_nome" required
                            class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label for="editar_email" class="block text-sm font-medium text-dark">Email</label>
                        <input type="email" name="email" id="editar_email" required
                            class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label for="editar_gestor" class="block text-sm font-medium text-dark">Gestor</label>
                        <select name="gestor" id="editar_gestor"
                            class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($gestores as $gestor): ?>
                                <option value="<?= $gestor['gestor_id'] ?>"><?= $gestor['gestor_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="editar_lista" class="block text-sm font-medium text-dark">Lista</label>
                        <select name="lista" id="editar_lista"
                            class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($listas as $lista): ?>
                                <option value="<?= $lista['lista_id'] ?>"><?= $lista['lista_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="editar_canal" class="block text-sm font-medium text-dark">Canal</label>
                        <select name="canal" id="editar_canal"
                            class="mt-1 block w-full p-2 border border-light rounded-md bg-white text-dark focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($channels as $canal): ?>
                                <option value="<?= $canal['canal_id'] ?>"><?= $canal['nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="submit"
                        class="px-4 py-2 bg-primary text-white rounded hover:bg-primary/90 cursor-pointer">
                        Guardar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Alert/Toast -->
    <div id="alerta-custom"
        class="fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 bg-success text-white px-6 py-3 rounded-lg shadow-lg z-50 hidden transition-opacity duration-300 opacity-0 text-center">
        <span id="alerta-mensagem"></span>
    </div>

    <!-- Modal Confirmar Apagar -->
    <div id="modal-confirmar-apagar" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
        <div class="modal-content bg-white border border-light rounded-lg shadow-lg w-full max-w-sm p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-dark">Confirmar Apagar</h3>
            <p id="texto-confirmacao" class="text-sm text-neutral">Tem a certeza que deseja apagar esta lista?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button id="cancelar-apagar" class="px-4 py-2 bg-light text-dark rounded hover:bg-highlight/20 cursor-pointer">Cancelar</button>
                <button id="confirmar-apagar" class="px-4 py-2 bg-danger text-white rounded hover:bg-danger/90 cursor-pointer">Apagar</button>
            </div>
        </div>
    </div>

    <script>
        const dadosGraficoCrescimento = <?= json_encode($dadosGrafico) ?>;
    </script>
</main>

<script src="js/index.js"></script>
<?php require __DIR__ . '/partials/footer.php'; ?>