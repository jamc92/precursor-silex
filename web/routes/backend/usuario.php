<?php

$app->match('/admin/usuario', 'Precursor\\Application\\Controller\\Backend\\Usuario::ver')
    ->bind('usuario_list');

$app->match('/admin/usuario/create', 'Precursor\\Application\\Controller\\Backend\\Usuario::agregar')
    ->bind('usuario_create');

$app->match('/admin/usuario/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\Usuario::editar')
    ->assert('id', '\d+')
    ->bind('usuario_edit');

$app->match('/admin/usuario/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\Usuario::eliminar')
    ->assert('id', '\d+')
    ->bind('usuario_delete');