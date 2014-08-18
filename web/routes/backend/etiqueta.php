<?php

$app->match('/admin/etiqueta', 'Precursor\\Application\\Controller\\Backend\\Etiqueta::ver')
    ->bind('etiqueta_list');

$app->match('/admin/etiqueta/create', 'Precursor\\Application\\Controller\\Backend\\Etiqueta::agregar')
    ->bind('etiqueta_create');

$app->match('/admin/etiqueta/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\Etiqueta::editar')
    ->assert('id', '\d+')
    ->bind('etiqueta_edit');

$app->match('/admin/etiqueta/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\Etiqueta::eliminar')
    ->assert('id', '\d+')
    ->bind('etiqueta_delete');
