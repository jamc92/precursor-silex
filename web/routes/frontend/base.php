<?php

require_once __DIR__ . '/comentario.php';
require_once __DIR__ . '/noticia.php';
require_once __DIR__ . '/suscriptor.php';
require_once __DIR__ . '/usuario.php';

// Ruta de la pagina de inicio de El Precursor
$app->match('/', 'Precursor\\Application\\Controller\\Frontend\\Base::index')
    ->bind('home');

# visor de logs
$app->get('/elprecursor.log', function() {
    $log = file_get_contents(__DIR__ . '/../../elprecursor.log');
    return $log;
});
