<!-- Visão Geral -->
    <section id="tab-visaogeral"
        class="tab-content w-full max-w-screen-xl px-6 py-8 bg-surface rounded-lg shadow-md space-y-6 border">
        <h2 class="text-xl font-semibold text-ink">Visão Geral</h2>

        <div class="grid grid-cols-5 gap-6">
            <div class="space-y-6 col-span-2">
                <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                    <h3 class="text-2xl font-extrabold text-ink tracking-tight mb-2">Resumo</h3>
                    <p class="text-3xl font-bold text-accent"><?= $totalContactos ?></p>
                    <p class="text-sm text-neutral">Todos contactos</p>
                    <p class="text-xs mt-2 text-neutral">Novos hoje: <?= $novosHoje ?></p>
                </div>

                <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                    <h3 class="text-2xl font-extrabold text-ink tracking-tight mb-2">Crescimento recente</h3>
                    <p class="text-sm text-ink/80">Últimos 30 dias</p>
                    <p class="text-sm mt-1 text-neutral">Novos contactos: <?= $contactosUltimos30Dias ?></p>
                    <p class="text-sm text-neutral">Desinscritos: 0</p>
                </div>

                <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                    <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                        <h3 class="text-2xl font-extrabold text-ink tracking-tight self-start">
                            Desempenho<br>do público
                        </h3>
                        <select class="rounded-md self-start text-sm bg-surface border border-control px-2 py-1 text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
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

            <div class="col-span-3">
                <div class="p-6 bg-light rounded-lg shadow-sm border border-line">
                    <div class="flex flex-wrap justify-between items-start gap-3 mb-3">
                        <h3 class="text-2xl font-extrabold text-ink tracking-tight self-start mb-2">
                            Crescimento de contactos
                        </h3>
                        <select id="filtro-periodo"
                            class="rounded-md self-start text-sm bg-surface border border-control px-2 py-1 text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
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
