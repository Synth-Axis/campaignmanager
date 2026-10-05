<!-- Listas -->
    <section id="tab-listas"
        class="hidden w-full max-w-2xl p-6 bg-surface border rounded-lg shadow-md space-y-4">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-ink">Listas</h2>
            <a href="#" data-modal="nova-lista"
                class="abrir-modal px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:outline-none">
                Nova Lista
            </a>
        </div>
        <p class="text-neutral">
            Abaixo encontra-se a lista de grupos de contactos prontos para envio de campanhas.
        </p>

        <table class="w-full text-sm text-left text-ink border-collapse">
            <thead>
                <tr class="border-b border-line">
                    <th scope="col" class="py-2 px-3">Nome da Lista</th>
                    <th scope="col" class="py-2 px-3 text-center">Acções</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($listas as $lista): ?>
                    <tr class="border-b border-line hover:bg-highlight/10 transition">
                        <td class="py-2 px-3"><?= htmlspecialchars($lista['lista_nome']) ?></td>
                        <td class="py-2 px-3 text-center">
                            <?php
                            $tipoAcao = 'lista';
                            $idAcao = $lista['lista_id'];
                            $nomeAcao = $lista['lista_nome'];
                            require view_path('partials/row-actions.php');
                            ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>
