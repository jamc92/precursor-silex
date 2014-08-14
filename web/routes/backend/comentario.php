<?php

$app->match('/admin/comentario', 'Precursor\\Application\\Controller\\Comentario::ver')
->bind('comentario_list');

$app->match('/admin/comentario/create', 'Precursor\\Application\\Controller\\Comentario::agregar')
->bind('comentario_create');

$app->match('/admin/comentario/edit/{id}', 'Precursor\\Application\\Controller\\Comentario::editar')
    ->assert('id', '\d+')
    ->bind('comentario_edit');

$app->match('/admin/comentario/delete/{id}', 'Precursor\\Application\\Controller\\Comentario::eliminar')
    ->assert('id', '\d+')
    ->bind('comentario_delete');
