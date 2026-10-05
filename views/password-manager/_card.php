<?php
$nomeServico = $acesso['nome_servico'] ?? '';
$username = trim($acesso['username'] ?? '');
$url = trim($acesso['url_acesso'] ?? '');
$urlValida = filter_var($url, FILTER_VALIDATE_URL)
    && in_array(strtolower(parse_url($url, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true);
$dataOriginal = $acesso['atualizado_em'] ?? '';
$dataAtualizacao = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $dataOriginal);
$dataFormatada = $dataAtualizacao ? $dataAtualizacao->format('d/m/Y \à\s H:i') : $dataOriginal;
?>
<article class="access-card">
    <div class="flex items-start gap-3">
        <span class="access-card-icon" aria-hidden="true">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="8" cy="8" r="4" /><path d="m11 11 9 9m-3-3 3-3m-6 0 3-3" />
            </svg>
        </span>
        <div class="min-w-0 flex-1">
            <h3 class="text-lg font-semibold leading-6 text-ink break-words"><?= htmlspecialchars($nomeServico, ENT_QUOTES, 'UTF-8') ?></h3>
            <?php if ($urlValida): ?>
                <a href="<?= htmlspecialchars($url, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-1.5 mt-2 text-sm text-accent hover:underline"
                    aria-label="<?= htmlspecialchars('Aceder ao site de ' . $nomeServico . ' (abre numa nova tab)', ENT_QUOTES, 'UTF-8') ?>">
                    Aceder ao site
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M14 4h6v6m0-6L10 14M10 4H5a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-5" />
                    </svg>
                </a>
            <?php else: ?>
                <p class="mt-2 text-sm text-neutral">Sem site associado</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="access-card-username">
        <p class="text-xs font-medium text-neutral mb-1">Username</p>
        <p class="text-sm text-ink break-words"><?= htmlspecialchars($username !== '' ? $username : 'Não definido', ENT_QUOTES, 'UTF-8') ?></p>
    </div>

    <div class="mt-auto space-y-4">
        <div class="grid grid-cols-2 gap-2">
            <button type="button" class="btnCopiarSenha access-card-action access-card-action-primary"
                data-id="<?= (int) $acesso['id'] ?>"
                aria-label="<?= htmlspecialchars('Copiar senha de ' . $nomeServico, ENT_QUOTES, 'UTF-8') ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <rect x="8" y="8" width="12" height="12" rx="2" /><path d="M16 8V4a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v11a1 1 0 0 0 1 1h4" />
                </svg>
                Copiar senha
            </button>
            <button type="button" class="btnEditarAcesso access-card-action"
                data-id="<?= (int) $acesso['id'] ?>"
                aria-label="<?= htmlspecialchars('Editar ' . $nomeServico, ENT_QUOTES, 'UTF-8') ?>">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="m15 5 4 4M4 20l4-1L20 7a2.8 2.8 0 0 0-4-4L4 15z" />
                </svg>
                Editar
            </button>
        </div>
        <p class="flex items-start gap-2 text-xs leading-5 text-neutral border-t border-line pt-3">
            <svg class="shrink-0 mt-0.5" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" />
            </svg>
            <span>Atualizado em <time datetime="<?= htmlspecialchars($dataAtualizacao ? $dataAtualizacao->format('Y-m-d\TH:i:s') : '', ENT_QUOTES, 'UTF-8') ?>"><?= htmlspecialchars($dataFormatada, ENT_QUOTES, 'UTF-8') ?></time></span>
        </p>
    </div>
</article>
