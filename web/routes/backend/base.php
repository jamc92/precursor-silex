<?php

require_once __DIR__.'/auth.php';
require_once __DIR__.'/articulo.php';
require_once __DIR__.'/articulos_etiquetas.php';
require_once __DIR__.'/categoria.php';
require_once __DIR__.'/comentario.php';
require_once __DIR__.'/etiqueta.php';
require_once __DIR__.'/etiquetas_articulo.php';
require_once __DIR__.'/imagen.php';
require_once __DIR__.'/opcion.php';
require_once __DIR__.'/perfil.php';
require_once __DIR__.'/usuario.php';

$app->match('/admin', function () use ($app) {

    return $app['twig']->render('ag_dashboard.html.twig', array());
        
})
->bind('admin');