<?php

$app->match('/admin/etiqueta', 'Precursor\\Application\\Controller\\Etiqueta::ver')
    ->bind('etiqueta_list');

$app->match('/admin/etiqueta/create', 'Precursor\\Application\\Controller\\Etiqueta::agregar')
    ->bind('etiqueta_create');

$app->match('/admin/etiqueta/edit/{id}', 'Precursor\\Application\\Controller\\Etiqueta::editar')
    ->assert('id', '\d+')
    ->bind('etiqueta_edit');

$app->match('/admin/etiqueta/delete/{id}', 'Precursor\\Application\\Controller\\Etiqueta::eliminar')
    ->assert('id', '\d+')
    ->bind('etiqueta_delete');
