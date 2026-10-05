<!-- Contactos -->
    <section id="tab-novocontacto" class="hidden w-full max-w-full p-0">

        <!-- Subtabs -->
        <div class="w-full flex justify-center -mt-4 mb-8">
            <ul id="contactos-subtabs"
                class="flex max-w-lg w-full text-xs font-medium text-center bg-transparent rounded-md shadow-none gap-4">
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-todos-contactos"
                        class="contact-tab-link block w-full py-2 px-1 text-neutral border shadow-md rounded-md hover:text-ink hover:bg-highlight/20 transition">
                        Todos os Contactos
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-inserir-contacto"
                        class="contact-tab-link block w-full py-2 px-1 text-neutral border shadow-md rounded-md hover:text-ink hover:bg-highlight/20 transition">
                        Inserir Contacto
                    </a>
                </li>
                <li class="flex-1">
                    <a href="#" data-contacttab="tab-importar-ficheiro"
                        class="contact-tab-link block w-full py-2 px-1 text-neutral border shadow-md rounded-md hover:text-ink hover:bg-highlight/20 transition">
                        Importar por Ficheiro
                    </a>
                </li>
            </ul>
        </div>

        <!-- Todos os Contactos -->
        <div id="tab-todos-contactos"
            class="contact-tab-content hidden rounded-xl p-6 mb-10 space-y-5 w-full max-w-7xl mx-auto bg-surface border shadow-sm">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h2 class="text-xl font-semibold text-ink">Todos os Contactos</h2>
                    <p id="total-contactos" class="mt-1 text-sm text-neutral"><?= (int) $totalContactos ?> contactos</p>
                </div>
                <div class="relative w-80 max-w-full">
                    <label for="pesquisar-contactos" class="sr-only">Pesquisar contactos por nome ou email</label>
                    <input type="search" id="pesquisar-contactos"
                        class="w-full pl-10 pr-3 py-2 text-sm border border-control rounded-lg bg-field text-ink focus:outline-none focus:ring-2 focus:ring-primary/40"
                        placeholder="Pesquisar por nome ou email…" />
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-neutral pointer-events-none" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
                        </svg>
                    </span>
                </div>
            </div>

            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <p id="estado-selecao-contactos" class="text-sm text-neutral" role="status">Seleciona contactos para exportar.</p>
                    <button type="button" id="limpar-selecao-contactos" class="hidden min-h-11 text-sm text-accent hover:underline">Limpar seleção</button>
                </div>
                <button type="button" id="toggle-exportar-contactos" aria-expanded="false" aria-controls="form-exportar-contactos"
                    class="inline-flex items-center gap-2 min-h-11 px-4 py-2 border border-line rounded-lg bg-field text-ink text-sm hover:bg-highlight/20">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 3v12m-4-4 4 4 4-4M4 16v4h16v-4" /></svg>
                    Exportar contactos
                </button>
            </div>

            <form method="POST" action="/api/exportar_contactos.php" id="form-exportar-contactos"
                class="hidden rounded-lg bg-field border border-line p-4 space-y-4">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <label for="formato-exportacao" class="text-sm font-medium text-ink">Formato</label>
                        <select id="formato-exportacao" name="formato" class="border border-control rounded-lg px-3 py-2 text-sm text-ink bg-surface">
                            <option value="csv">CSV</option>
                            <option value="xlsx">XLSX</option>
                        </select>
                    </div>
                    <input type="hidden" name="todos" id="exportar-todos" value="1">
                    <button type="submit" id="btn-exportar-contactos" class="min-h-11 bg-primary text-white text-sm px-4 py-2 rounded-lg hover:bg-primary-hover">Exportar todos</button>
                </div>
                <fieldset>
                    <legend class="text-sm font-medium text-ink mb-2">Campos a incluir</legend>
                    <div class="flex items-center gap-x-5 gap-y-3 flex-wrap">
                        <?php foreach (['publico_id' => 'ID', 'nome' => 'Nome', 'email' => 'Email', 'gestor' => 'Gestor', 'canal' => 'Canal', 'lista' => 'Lista', 'data_registo' => 'Data Registo'] as $campo => $rotulo): ?>
                            <label class="inline-flex items-center gap-2 text-sm text-neutral cursor-pointer">
                                <input type="checkbox" name="campos[]" value="<?= $campo ?>" <?= $campo !== 'publico_id' ? 'checked' : '' ?>> <?= $rotulo ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </fieldset>
                <p class="text-xs text-neutral">Sem seleção, são exportados todos os contactos, incluindo os que não aparecem na pesquisa.</p>
            </form>

            <div class="contacts-table-scroll" tabindex="0" role="region" aria-label="Tabela de contactos">
            <table class="contacts-table text-left text-ink">
                <caption class="sr-only">Contactos, listas, gestores e ações disponíveis</caption>
                <thead>
                    <tr>
                        <th scope="col" class="text-center"><input type="checkbox" id="selecionar-todos" aria-label="Selecionar os contactos desta página"></th>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Gestor</th>
                        <th scope="col">Canal</th>
                        <th scope="col">Lista</th>
                        <th scope="col">Registado em</th>
                        <th scope="col" class="text-center">Acções</th>
                    </tr>
                </thead>
                <tbody id="tabela-contactos">
                    <?php foreach ($contactos as $contacto): ?>
                        <?php require view_path('publico/_contact-row.php'); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
            </div>

            <div class="flex items-center justify-between gap-4">
                <p id="intervalo-contactos" class="text-sm text-neutral" role="status"></p>
                <div id="paginacao-contactos"></div>
            </div>
        </div>

        <!-- Inserir Contacto -->
        <form id="tab-inserir-contacto" method="POST" action=""
            class="max-w-4xl mx-auto contact-tab-content hidden rounded-lg shadow-md p-8 space-y-6 bg-surface border ">
            <h5 class="text-lg font-semibold text-ink">Novo registo</h5>

            <div class="grid grid-cols-2 gap-y-6 gap-x-10">
                <div>
                    <label for="nome" class="block mb-2 text-sm font-medium text-ink">Nome</label>
                    <input type="text" name="nome" id="nome" value="<?= htmlspecialchars($nome ?? '') ?>" placeholder="Insira o nome"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-ink">Email</label>
                    <input type="email" name="email" id="email" value="<?= htmlspecialchars($email ?? '') ?>" placeholder="name@empresa.com"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                </div>

                <div class="col-span-1">
                    <label for="gestor" class="block mb-2 text-sm font-medium text-ink">Gestor</label>
                    <select name="gestor" id="gestor"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <option value="" <?= empty($gestor_id) ? 'selected' : '' ?>>Selecione um gestor</option>
                        <?php foreach ($gestores as $gestor): ?>
                            <option value="<?= htmlspecialchars($gestor['gestor_id']) ?>" <?= (isset($gestor_id) && $gestor_id == $gestor['gestor_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($gestor['gestor_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="lista" class="block mb-2 text-sm font-medium text-ink">Lista</label>
                    <select name="lista" id="lista"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
                        <option value="" <?= empty($lista_id) ? 'selected' : '' ?>>Selecione a lista</option>
                        <?php foreach ($listas as $lista): ?>
                            <option value="<?= htmlspecialchars($lista['lista_id']) ?>" <?= (isset($lista_id) && $lista_id == $lista['lista_id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($lista['lista_nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="canal" class="block mb-2 text-sm font-medium text-ink">Canal</label>
                    <select name="canal" id="canal"
                        class="bg-surface border border-control text-ink text-sm rounded-lg w-full p-2.5 focus:outline-none focus:ring-2 focus:ring-primary/40">
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
                    <div class="<?= ($_SESSION['message_type'] ?? 'success') === 'success' ? 'text-positive' : 'text-negative' ?>">
                        <?= $_SESSION['message'] ?>
                    </div>
                    <?php unset($_SESSION['message'], $_SESSION['message_type']); ?>
                <?php endif; ?>
            </div>
        </form>

        <!-- Importar por ficheiro -->
        <div id="tab-importar-ficheiro"
            class="contact-tab-content hidden rounded-lg shadow-md p-6 space-y-4 w-full max-w-4xl mx-auto bg-surface border">
            <h5 class="text-lg font-semibold text-ink">Importar Contactos por Ficheiro</h5>

            <form id="form-importar-ficheiro" method="POST" action="/api/importar_publico.php" enctype="multipart/form-data" class="space-y-4">
                <label for="ficheiro"
                    class="block max-w-2xs p-2 bg-surface border border-line rounded-lg cursor-pointer text-neutral text-sm text-center hover:bg-highlight/20 transition">
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
