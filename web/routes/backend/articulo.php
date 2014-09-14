<?php

$app->match('/admin/articulo', 'Precursor\\Application\\Controller\\Backend\\Articulo::ver')
    ->bind('articulo_list');

$app->match('/admin/articulo/create', 'Precursor\\Application\\Controller\\Backend\\Articulo::agregar')
    ->bind('articulo_create');

$app->match('/admin/articulo/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\Articulo::editar')
    ->assert('id', '\d+')
    ->bind('articulo_edit');

$app->match('/admin/articulo/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\Articulo::eliminar')
    ->assert('id', '\d+')
    ->bind('articulo_delete');
