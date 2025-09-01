<?php
require_once __DIR__ . '/../models/acessos.php';
$model = new Acesso();
$acessos = $model->listarTodos();

foreach ($acessos as $acesso): ?>
    <div class="rounded-xl shadow p-4 bg-white border border-light text-dark hover:bg-highlight/10 transition">

        <h3 class="text-xl font-semibold mb-1">
            <?= htmlspecialchars($acesso['nome_servico']) ?>
        </h3>

        <?php if (!empty($acesso['url_acesso'])): ?>
            <a href="<?= htmlspecialchars($acesso['url_acesso']) ?>" target="_blank"
                class="text-sm text-primary hover:underline">
                Aceder ao site
            </a>
        <?php endif; ?>

        <?php if (!empty($acesso['username'])): ?>
            <p class="text-sm mt-2 text-neutral font-bold">
                Username:<br>
                <span class="font-normal"><?= htmlspecialchars($acesso['username']) ?></span>
            </p>
        <?php endif; ?>

        <div class="mt-4 flex gap-4">
            <button class="cursor-pointer btnCopiarSenha text-sm text-success hover:underline font-bold"
                data-id="<?= $acesso['id'] ?>">
                Copiar senha
            </button>
            <button class="cursor-pointer btnEditarAcesso text-sm text-dark hover:underline"
                data-id="<?= $acesso['id'] ?>">
                Editar
            </button>
        </div>

        <p class="text-sm mt-2 text-neutral font-bold">
            Data da última atualização:
            <span class="font-normal"><?= htmlspecialchars($acesso['atualizado_em']) ?></span>
        </p>
    </div>
<?php endforeach; ?>