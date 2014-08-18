<?php

$app->match('/admin/imagen', 'Precursor\\Application\\Controller\\Backend\\Imagen::ver')
    ->bind('imagen_list');

$app->match('/admin/imagenJson', 'Precursor\\Application\\Controller\\Backend\\Imagen::verJson')
    ->bind('imagen_list_json');

$app->match('/admin/imagen/create', 'Precursor\\Application\\Controller\\Backend\\Imagen::agregar')
    ->bind('imagen_create');

$app->match('/admin/imagen/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\Imagen::editar')
    ->assert('id', '\d+')
    ->bind('imagen_edit');

$app->match('/admin/imagen/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\Imagen::eliminar')
    ->assert('id', '\d+')
    ->bind('imagen_delete');