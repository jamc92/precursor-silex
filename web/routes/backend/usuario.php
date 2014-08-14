<?php

$app->match('/admin/usuario', 'Precursor\\Application\\Controller\\Usuario::ver')
    ->bind('usuario_list');

$app->match('/admin/usuario/create', 'Precursor\\Application\\Controller\\Usuario::agregar')
    ->bind('usuario_create');

$app->match('/admin/usuario/edit/{id}', 'Precursor\\Application\\Controller\\Usuario::editar')
    ->assert('id', '\d+')
    ->bind('usuario_edit');

$app->match('/admin/usuario/delete/{id}', 'Precursor\\Application\\Controller\\Usuario::eliminar')
    ->assert('id', '\d+')
    ->bind('usuario_delete');