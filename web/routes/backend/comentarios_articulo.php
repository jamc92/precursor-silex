<?php

$app->match('/admin/comentarios_articulo/{articulo_id}', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::ver')
    ->assert('articulo_id', '\d+')
    ->bind('comentarios_articulo_list');

$app->match('/admin/comentarios_articulo/{articulo_id}/create', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::agregar')
    ->assert('articulo_id', '\d+')
    ->bind('cometarios_articulo_create');

$app->match('/admin/comentarios_articulo/{articulo_id}/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::eliminar')
    ->assert('articulo_id', '\d+')
    ->assert('id', '\d+')
    ->bind('comentarios_articulo_delete');