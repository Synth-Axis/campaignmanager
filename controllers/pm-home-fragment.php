<?php
require_once __DIR__ . '/../models/acessos.php';
$model = new Acesso();
$acessos = $model->listarTodos();

foreach ($acessos as $acesso): ?>
    <div class="rounded-xl shadow p-4 dark:bg-gray-900 text-white">
        <h3 class="text-xl font-semibold"><?= htmlspecialchars($acesso['nome_servico']) ?></h3>

        <?php if (!empty($acesso['url_acesso'])): ?>
            <a href="<?= htmlspecialchars($acesso['url_acesso']) ?>" target="_blank" class="text-sm text-blue-400 hover:underline">
                Aceder ao site
            </a>
        <?php endif; ?>

        <?php if (!empty($acesso['username'])): ?>
            <p class="text-sm mt-2 text-gray-300 font-bold">Username:</br>
                <span class="font-normal"><?= $acesso['username'] ?></span>
            </p>
        <?php endif; ?>

        <div class="mt-4 flex gap-4">
            <button class="cursor-pointer btnCopiarSenha text-sm text-green-400 hover:underline font-bold" data-id="<?= $acesso['id'] ?>">Copiar senha</button>
            <button class="cursor-pointer btnEditarAcesso text-sm text-yellow-500 hover:underline" data-id="<?= $acesso['id'] ?>">Editar</button>
        </div>
        <p class="text-sm mt-2 text-gray-300 font-bold">Data da ultima atualização: <span class="font-normal"><?= $acesso['atualizado_em'] ?></span></p>
    </div>
<?php endforeach; ?>