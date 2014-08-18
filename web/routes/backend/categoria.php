<?php

$app->match('/admin/categoria', 'Precursor\\Application\\Controller\\Backend\\Categoria::ver')
    ->bind('categoria_list');

$app->match('/admin/categoria/create', 'Precursor\\Application\\Controller\\Backend\\Categoria::agregar')
    ->bind('categoria_create');

$app->match('/admin/categoria/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\Categoria::editar')
    ->assert('id', '\d+')
    ->bind('categoria_edit');

$app->match('/admin/categoria/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\Categoria::eliminar')
    ->assert('id', '\d+')
    ->bind('categoria_delete');
