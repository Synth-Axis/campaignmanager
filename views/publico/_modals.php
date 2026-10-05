<!-- Modal Nova Lista -->
    <div id="modal-nova-lista" class="modal hidden fixed inset-0 flex items-center justify-center z-50 bg-black/50">
        <div class="modal-content bg-surface border border-line rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-ink">Criar Nova Lista</h3>
            <form method="POST" action="" id="form-nova-lista">
                <input type="hidden" name="action" value="nova_lista">
                <div>
                    <label for="nova_lista_nome" class="block text-sm font-medium text-ink">Nome da Lista</label>
                    <input type="text" name="nova_lista_nome" id="nova_lista_nome" required
                        class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
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
        <div class="modal-content bg-surface border border-line rounded-lg shadow-lg w-full max-w-md p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-ink">Editar Lista</h3>
            <form method="POST" id="form-editar-lista">
                <input type="hidden" name="action" value="editar_lista">
                <input type="hidden" name="lista_id" id="editar_lista_id">
                <div>
                    <label for="editar_lista_nome" class="block text-sm font-medium text-ink">Novo nome</label>
                    <input type="text" name="lista_nome" id="editar_lista_nome" required
                        class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
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
        <div class="modal-content bg-surface border border-line rounded-lg shadow-lg w-full max-w-4xl p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-ink">Editar Contacto</h3>
            <form method="POST" id="form-editar-contacto">
                <input type="hidden" name="action" value="editar_contacto">
                <input type="hidden" name="contacto_id" id="editar_contacto_id">
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="editar_nome" class="block text-sm font-medium text-ink">Nome</label>
                        <input type="text" name="nome" id="editar_nome" required
                            class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label for="editar_email" class="block text-sm font-medium text-ink">Email</label>
                        <input type="email" name="email" id="editar_email" required
                            class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
                    </div>
                    <div>
                        <label for="editar_gestor" class="block text-sm font-medium text-ink">Gestor</label>
                        <select name="gestor" id="editar_gestor"
                            class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($gestores as $gestor): ?>
                                <option value="<?= $gestor['gestor_id'] ?>"><?= $gestor['gestor_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="editar_lista" class="block text-sm font-medium text-ink">Lista</label>
                        <select name="lista" id="editar_lista"
                            class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
                            <?php foreach ($listas as $lista): ?>
                                <option value="<?= $lista['lista_id'] ?>"><?= $lista['lista_nome'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label for="editar_canal" class="block text-sm font-medium text-ink">Canal</label>
                        <select name="canal" id="editar_canal"
                            class="mt-1 block w-full p-2 border border-control rounded-md bg-surface text-ink focus:outline-none focus:ring-2 focus:ring-primary/30">
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
        <div class="modal-content bg-surface border border-line rounded-lg shadow-lg w-full max-w-sm p-6 space-y-4 relative">
            <h3 class="text-lg font-medium text-ink">Confirmar Apagar</h3>
            <p id="texto-confirmacao" class="text-sm text-neutral">Tem a certeza que deseja apagar esta lista?</p>
            <div class="flex justify-end gap-2 mt-4">
                <button id="cancelar-apagar" class="px-4 py-2 bg-light text-ink rounded hover:bg-highlight/20 cursor-pointer">Cancelar</button>
                <button id="confirmar-apagar" class="px-4 py-2 bg-danger text-white rounded hover:bg-danger/90 cursor-pointer">Apagar</button>
            </div>
        </div>
    </div>
