<!-- Segmentos -->
    <section id="tab-segmentos"
        class="hidden w-full max-w-screen-xl p-6 bg-surface border rounded-lg shadow-md space-y-6">
        <h2 class="text-xl font-semibold text-ink">Segmentos</h2>
        <p class="text-neutral">
            Consulte os segmentos e os contactos associados. Os segmentos automáticos acompanham as alterações aos contactos. Um contacto pode pertencer a vários segmentos.
        </p>
        <?php if ($erroSegmentos): ?>
            <p role="alert" class="text-negative"><?= htmlspecialchars($erroSegmentos, ENT_QUOTES, 'UTF-8') ?></p>
        <?php elseif (!$segmentos): ?>
            <p class="text-neutral">Ainda não existem segmentos registados.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-ink">
                    <caption class="sr-only">Segmentos e quantidade de contactos associados</caption>
                    <thead class="bg-light border-b border-line">
                        <tr>
                            <th scope="col" class="p-3">Segmento</th>
                            <th scope="col" class="p-3">Descrição</th>
                            <th scope="col" class="p-3 text-center">Contactos</th>
                            <th scope="col" class="p-3 text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($segmentos as $segmento): ?>
                            <tr class="border-b border-line">
                                <th scope="row" class="p-3 font-semibold"><?= htmlspecialchars($segmento['segmento_nome'], ENT_QUOTES, 'UTF-8') ?></th>
                                <td class="p-3 text-neutral"><?= htmlspecialchars($segmento['descricao'] ?? '', ENT_QUOTES, 'UTF-8') ?></td>
                                <td class="p-3 text-center font-semibold"><?= (int) $segmento['total_contactos'] ?></td>
                                <td class="p-3 text-center">
                                    <button type="button" class="ver-contactos-segmento table-action-link"
                                        data-segmento-id="<?= (int) $segmento['segmento_id'] ?>"
                                        data-segmento-nome="<?= htmlspecialchars($segmento['segmento_nome'], ENT_QUOTES, 'UTF-8') ?>"
                                        aria-label="<?= htmlspecialchars('Ver contactos de ' . $segmento['segmento_nome'], ENT_QUOTES, 'UTF-8') ?>"
                                        aria-expanded="false" aria-controls="contactos-segmento">
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7S2 12 2 12Z" /><circle cx="12" cy="12" r="3" /></svg>
                                        Ver contactos
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div id="contactos-segmento" class="hidden space-y-4 border-t border-line pt-6" aria-labelledby="nome-segmento-selecionado">
                <div class="flex justify-between items-center gap-4">
                    <h3 id="nome-segmento-selecionado" tabindex="-1" class="text-lg font-semibold text-ink"></h3>
                    <button id="fechar-contactos-segmento" type="button" class="px-4 py-2 rounded-lg bg-light text-ink border border-line hover:bg-highlight/20 cursor-pointer">Fechar</button>
                </div>
                <div class="max-w-md">
                    <label for="pesquisar-contactos-segmento" class="block mb-2 text-sm font-medium text-ink">Pesquisar neste segmento</label>
                    <input id="pesquisar-contactos-segmento" type="search" placeholder="Nome ou email…"
                        class="w-full bg-field border border-control text-ink rounded-lg p-3">
                </div>
                <p id="estado-contactos-segmento" role="status" aria-live="polite" class="text-sm text-neutral"></p>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-ink">
                        <caption class="sr-only">Contactos do segmento selecionado</caption>
                        <thead class="bg-light border-b border-line">
                            <tr>
                                <th scope="col" class="p-3">Nome</th>
                                <th scope="col" class="p-3">Email</th>
                                <th scope="col" class="p-3">Gestor</th>
                                <th scope="col" class="p-3">Canal</th>
                                <th scope="col" class="p-3">Lista</th>
                            </tr>
                        </thead>
                        <tbody id="tabela-contactos-segmento"></tbody>
                    </table>
                </div>
                <div id="paginacao-contactos-segmento" class="hidden flex justify-between items-center gap-4">
                    <button id="segmento-pagina-anterior" type="button" class="px-4 py-2 rounded-lg bg-light text-ink border border-line disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">Anterior</button>
                    <span id="segmento-pagina-atual" class="text-sm text-neutral"></span>
                    <button id="segmento-pagina-seguinte" type="button" class="px-4 py-2 rounded-lg bg-light text-ink border border-line disabled:opacity-50 disabled:cursor-not-allowed cursor-pointer">Seguinte</button>
                </div>
            </div>
        <?php endif; ?>
    </section>
