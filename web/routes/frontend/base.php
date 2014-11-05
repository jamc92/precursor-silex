<?php

$app->match('/', 'Precursor\\Application\\Controller\\Frontend\\Base::index')
    ->bind('home');

$app->match('/login', 'Precursor\\Application\\Controller\\Frontend\\Usuario::login')
    ->bind('login');

$app->match('/signup', 'Precursor\\Application\\Controller\\Frontend\\Usuario::signup')
    ->bind('signup');

$app->match('/auth/{service}', 'Precursor\\Application\\Controller\\Frontend\\Usuario::auth')
    ->assert('service', '[a-z]+')
    ->bind('auth_service');

$app->match('/noticia/{id}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::ver')
    ->assert('idArticulo', '\d+')
    ->bind('noticia');

$app->match('/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Frontend\\Comentario::verAjax')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_ajax');

$app->match('/comentar', 'Precursor\\Application\\Controller\\Frontend\\Comentario::guardarComentario')
    ->bind('guardar_comentario')
    ->method('POST');

$app->match('/busqueda', 'Precursor\\Application\\Controller\\Frontend\\Noticia::busqueda')
    ->bind('busqueda')
    ->method('GET');

$app->match('/categoria/{idCategoria}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::categoria')
    ->assert('idCateoria', '\d+')
    ->bind('articulos_categoria')
    ->method('GET');

$app->match('/etiqueta/{idEtiqueta}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::etiqueta')
    ->assert('idEtiqueta', '\d+')
    ->bind('articulos_etiqueta')
    ->method('GET');
