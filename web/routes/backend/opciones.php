<?php

$app->match('/admin/menu', 'Precursor\\Application\\Controller\\Backend\\Opcion::verMenu')
    ->bind('menu_index');

$app->match('/admin/menu/guardar', 'Precursor\\Application\\Controller\\Backend\\Opcion::guardarMenu')
    ->method('POST')
    ->bind('menu_guardar');

$app->match('/admin/correos', 'Precursor\\Application\\Controller\\Backend\\Opcion::verCorreos')
    ->bind('correos_ver');

$app->match('/admin/correos/guardar', 'Precursor\\Application\\Controller\\Backend\\Opcion::guardarCorreos')
    ->method('POST')
    ->bind('correos_guardar');