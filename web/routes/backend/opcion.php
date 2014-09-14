<?php

$app->match('/admin/opcion', 'Precursor\Application\Controller\Backend\Opcion::ver')
    ->bind('opcion_list');

$app->match('/admin/opcion/create', 'Precursor\Application\Controller\Backend\Opcion::agregar')
    ->bind('opcion_create');

$app->match('/admin/opcion/edit/{id}', 'Precursor\Application\Controller\Backend\Opcion::editar')
    ->assert('id', '\d+')
    ->bind('opcion_edit');

$app->match('/admin/opcion/delete/{id}', 'Precursor\Application\Controller\Backend\Opcion::eliminar')
    ->assert('id', '\d+')
    ->bind('opcion_delete');