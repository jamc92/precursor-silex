<?php

$app->match('/admin/etiquetas_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::ver')
    ->assert('idArticulo', '\d+')
    ->bind('etiquetas_articulo_list');

$app->match('/admin/etiquetas_articulo/{idArticulo}/create', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::agregar')
    ->assert('idArticulo', '\d+')
    ->bind('etiquetas_articulo_create');

$app->match('/admin/etiquetas_articulo/{idArticulo}/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::eliminar')
    ->assert('idArticulo', '\d+')
    ->assert('id', '\d+')
    ->bind('etiquetas_articulo_delete');