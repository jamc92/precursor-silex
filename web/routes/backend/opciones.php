<?php

$app->match('/admin/menu', 'Precursor\\Application\\Controller\\Backend\\Opcion::verMenu')
    ->bind('menu_index');

$app->match('/admin/menu/guardar', 'Precursor\\Application\\Controller\\Backend\\Opcion::guardarMenu')
    ->method('POST')
    ->bind('menu_guardar');

$app->match('/admin/custom-styles', 'Precursor\\Application\\Controller\\Backend\\Opcion::customStyles')
    ->bind('custom_styles')
    ->method('GET|POST');

$app->match('/admin/envio-correos', 'Precursor\\Application\\Controller\\Backend\\Opcion::verCorreos')
    ->bind('envio_correos');