<?php

$app->match('/', 'Precursor\\Application\\Controller\\Frontend\\Base::index')
    ->bind('home');

$app->match('/noticia/{id}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::ver')
    ->assert('idArticulo', '\d+')
    ->bind('noticia');

$app->match('/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Frontend\\Comentario::verAjax')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_ajax');

$app->match('/comentar', 'Precursor\\Application\\Controller\\Frontend\\Comentario::guardarComentario')
    ->bind('guardar_comentario')
    ->method('POST');