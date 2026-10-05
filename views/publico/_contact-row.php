<?php
$esc = static fn($value) => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
$dataContacto = DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $contacto['data_registo'] ?? '');
?>
<tr>
    <td class="text-center"><input type="checkbox" class="checkbox-contacto" value="<?= (int) $contacto['publico_id'] ?>" aria-label="<?= $esc('Selecionar ' . $contacto['nome']) ?>"></td>
    <td><span class="contact-name"><?= $esc($contacto['nome']) ?></span></td>
    <td class="contact-email"><?= $esc($contacto['email']) ?></td>
    <td><?= $esc($contacto['gestor_nome'] ?? '—') ?></td>
    <td><span class="contact-tag" title="<?= $esc($contacto['canal_nome'] ?? '') ?>"><?= $esc($contacto['canal_nome'] ?? '—') ?></span></td>
    <td><span class="contact-tag contact-tag-list" title="<?= $esc($contacto['lista_nome'] ?? '') ?>"><?= $esc($contacto['lista_nome'] ?? '—') ?></span></td>
    <td class="contact-date">
        <?php if ($dataContacto): ?>
            <time datetime="<?= $dataContacto->format('Y-m-d\TH:i:s') ?>"><?= $dataContacto->format('d/m/Y') ?><span><?= $dataContacto->format('H:i') ?></span></time>
        <?php else: ?>
            <?= $esc($contacto['data_registo'] ?? '—') ?>
        <?php endif; ?>
    </td>
    <td class="text-center">
        <?php
        $tipoAcao = 'contacto';
        $idAcao = $contacto['publico_id'];
        $nomeAcao = $contacto['nome'];
        require view_path('partials/row-actions.php');
        ?>
    </td>
</tr>
