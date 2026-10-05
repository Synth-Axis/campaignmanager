<?php

// Public addresses stay independent of physical controller names.
$routes = [];
$add = static function (array $paths, string $class, string $action, array $methods = ['GET'], bool $auth = true, bool $json = false, array $scripts = []) use (&$routes): void {
    $route = ['handler' => [$class, $action], 'methods' => $methods, 'auth' => $auth, 'json' => $json, 'scripts' => $scripts];
    foreach ($paths as $path) $routes[$path] = $route;
};
$add(['/', '/index.php', '/login', '/controllers/login.php'], AuthController::class, 'login', ['GET', 'POST'], false);
$add(['/register', '/controllers/register.php'], AuthController::class, 'register', ['GET', 'POST'], false);
$add(['/recuperar_password', '/controllers/recuperar_password.php'], AuthController::class, 'recuperarPassword', ['GET', 'POST'], false);
$add(['/redefinir_password', '/controllers/redefinir_password.php'], AuthController::class, 'redefinirPassword', ['GET', 'POST'], false);
$add(['/logout', '/controllers/logout.php'], AuthController::class, 'logout');
$add(['/home', '/home-center', '/app-center', '/controllers/home.php', '/controllers/home-center.php'], AppCenterController::class, 'index');
$contactScripts = ['https://cdn.jsdelivr.net/npm/chart.js', 'assets/js/row-actions.js', 'assets/js/contactos.js', 'assets/js/segmentos.js'];
$add(['/publico', '/controllers/publico.php'], PublicoController::class, 'index', ['GET', 'POST'], true, false, $contactScripts);
$emailScripts = ['https://unpkg.com/flowbite@2.3.0/dist/flowbite.min.js', 'assets/js/campanhas-email.js'];
$add(['/campanhas', '/controllers/campanhas.php'], CampanhasEmailController::class, 'index', ['GET', 'POST'], true, false, $emailScripts);
$add(['/campanhas/editar', '/editar_campanha', '/controllers/editar_campanha.php'], CampanhasEmailController::class, 'editar', ['GET', 'POST'], true, false, $emailScripts);
$add(['/campanhas.php', '/campanhas-conteudo'], CampanhasConteudoController::class, 'dispatch', ['GET', 'POST']);
$add(['/pm-home', '/pm-home.php', '/controllers/pm-home.php', '/password-manager'], PasswordManagerController::class, 'index', ['GET', 'POST'], true, false, ['assets/js/password-manager.js']);
$add(['/password-manager/cards', '/pm-home-fragment', '/controllers/pm-home-fragment.php'], PasswordManagerController::class, 'cards');
$add(['/password-manager/guardar', '/guardar_acesso', '/controllers/guardar_acesso.php'], PasswordManagerController::class, 'guardar', ['POST'], true, true);
$add(['/password-manager/carregar', '/carregar_acesso', '/controllers/carregar_acesso.php'], PasswordManagerController::class, 'carregar', ['GET'], true, true);
$add(['/password-manager/senha', '/desencriptar', '/controllers/desencriptar.php'], PasswordManagerController::class, 'senha', ['GET'], true, true);
foreach (['pesquisar_contactos' => 'pesquisarContactos', 'crescimento_contactos' => 'crescimentoContactos', 'get_contacto' => 'getContacto', 'contactos_segmento' => 'contactosSegmento'] as $file => $action) {
    $add(['/api/' . $file . '.php'], PublicoApiController::class, $action, ['GET'], true, true);
}
foreach (['importar_publico' => 'importarPublico', 'importar_gestores' => 'importarGestores', 'importar_canais' => 'importarCanais'] as $file => $action) {
    $add(['/api/' . $file . '.php'], PublicoApiController::class, $action, ['POST'], true, true);
}
$add(['/api/exportar_contactos.php'], PublicoApiController::class, 'exportarContactos', ['POST']);
foreach (['click', 'open', 'qrcode'] as $action) {
    $add(['/track/' . $action . '.php'], TrackingController::class, $action, ['GET'], false);
}
return $routes;
