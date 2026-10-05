<?php if (!$acessos): ?>
    <p class="col-span-full py-8 text-center text-neutral">Ainda não existem acessos guardados. Adicione o primeiro em “Novo Acesso”.</p>
<?php endif;
foreach ($acessos as $acesso) {
    require view_path('password-manager/_card.php');
}
