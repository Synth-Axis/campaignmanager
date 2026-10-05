<button type="button" class="row-action-trigger"
    data-row-actions="<?= htmlspecialchars($tipoAcao, ENT_QUOTES, 'UTF-8') ?>"
    data-id="<?= (int) $idAcao ?>"
    data-nome="<?= htmlspecialchars($nomeAcao, ENT_QUOTES, 'UTF-8') ?>"
    aria-label="<?= htmlspecialchars('Acções para ' . $nomeAcao, ENT_QUOTES, 'UTF-8') ?>"
    title="<?= htmlspecialchars('Acções para ' . $nomeAcao, ENT_QUOTES, 'UTF-8') ?>"
    aria-haspopup="menu" aria-expanded="false" aria-controls="row-actions-menu">
    <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
        <circle cx="5" cy="12" r="1.6" /><circle cx="12" cy="12" r="1.6" /><circle cx="19" cy="12" r="1.6" />
    </svg>
</button>
