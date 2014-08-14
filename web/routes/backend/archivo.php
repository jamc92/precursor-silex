<?php

$app->match('/admin/archivo', 'Precursor\\Application\\Controller\\Archivo::ver')
    ->bind('archivo_list');

$app->match('/admin/archivo/syntax', 'Precursor\\Application\\Controller\\Archivo::sintaxis')
    ->bind('archivo_syntax');

$app->match('/admin/archivo/create', 'Precursor\\Application\\Controller\\Archivo::agregar')
    ->bind('archivo_create')
    ->method("GET|POST|PUT");

$app->match('/admin/archivo/edit/{nombre}', 'Precursor\\Application\\Controller\\Archivo::editar')
    ->bind('archivo_edit');

$app->match('/admin/archivo/delete/{nombre}', 'Precursor\\Application\\Controller\\Archivo::eliminar')
    ->bind('archivo_delete');

$app->match('/admin/archivo/protected', 'Precursor\\Application\\Controller\\Archivo::ver')
    ->bind('archivo_protected_list');

$app->match('/admin/archivo/protected/edit/{nombre}', 'Precursor\\Application\\Controller\\Archivo::editar')
    ->bind('archivo_protected_edit');

$app->match('/admin/archivo/protected/delete/{nombre}', 'Precursor\\Application\\Controller\\Archivo::eliminar')
    ->bind('archivo_protected_delete');