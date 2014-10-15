<?php

$app->match('/admin/menu', 'Precursor\\Application\\Controller\\Backend\\Menu::index')
    ->bind('menu_index');

$app->match('/admin/menu/guardar', 'Precursor\\Application\\Controller\\Backend\\Menu::guardar')
    ->method('POST')
    ->bind('menu_guardar');