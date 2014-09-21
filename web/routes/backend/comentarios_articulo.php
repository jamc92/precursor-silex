<?php

$app->match('/admin/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::ver')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_list');

$app->match('/admin/comentarios_articulo/{idArticulo}/create', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::agregar')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_create');

$app->match('/admin/comentarios_articulo/{idArticulo}/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\ComentariosArticulo::eliminar')
    ->assert('idArticulo', '\d+')
    ->assert('id', '\d+')
    ->bind('comentarios_articulo_delete');