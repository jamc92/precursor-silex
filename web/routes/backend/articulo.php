<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/articulo', 'Precursor\\Application\\Controller\\Articulo::ver')
    ->bind('articulo_list');

$app->match('/admin/articulo/create', 'Precursor\\Application\\Controller\\Articulo::agregar')
    ->bind('articulo_create');

$app->match('/admin/articulo/edit/{id}', 'Precursor\\Application\\Controller\\Articulo::editar')
    ->assert('id', '\d+')
    ->bind('articulo_edit');

$app->match('/admin/articulo/delete/{id}', 'Precursor\\Application\\Controller\\Articulo::eliminar')
    ->assert('id', '\d+')
    ->bind('articulo_delete');
