<?php

require_once __DIR__ . '/archivo.php';
require_once __DIR__ . '/articulo.php';
require_once __DIR__ . '/auditoria.php';
require_once __DIR__ . '/categoria.php';
require_once __DIR__ . '/comentario.php';
require_once __DIR__ . '/comentarios_articulo.php';
require_once __DIR__ . '/etiqueta.php';
require_once __DIR__ . '/etiquetas_articulo.php';
require_once __DIR__ . '/imagen.php';
require_once __DIR__ . '/opciones.php';
require_once __DIR__ . '/perfil.php';
require_once __DIR__ . '/usuario.php';

$app->match('/admin', 'Precursor\\Application\\Controller\\Backend\\Base::index')
    ->bind('admin');

$app->match('/admin/pruebas', 'Precursor\\Application\\Controller\\Backend\\Base::pruebas')
    ->bind('pruebas');

$app->match('/admin/update/{id}', 'Precursor\\Application\\Controller\\Backend\\Comentario::aprobar')
    ->assert('id', '\d+')
    ->bind('aprobar_comentario');

$app->match('/admin/logs', 'Precursor\\Application\\Controller\\Backend\\Base::logs')
    ->bind('logs');