<?php

$app->match('/admin/perfil', 'Precursor\\Application\\Controller\\Perfil::ver')
    ->bind('perfil_list');

$app->match('/admin/perfil/create', 'Precursor\\Application\\Controller\\Perfil::agregar')
    ->bind('perfil_create');

$app->match('/admin/perfil/edit/{id}', 'Precursor\\Application\\Controller\\Perfil::editar')
    ->assert('id', '\d+')
    ->bind('perfil_edit');

$app->match('/admin/perfil/delete/{id}', 'Precursor\\Application\\Controller\\Perfil::eliminar')
    ->assert('id', '\d+')
    ->bind('perfil_delete');






