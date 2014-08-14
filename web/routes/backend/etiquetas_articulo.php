<?php

$app->match('/admin/etiquetas_articulo/{articulo_id}', 'Precursor\\Application\\Controller\\EtiquetasArticulo::ver')
    ->assert('articulo_id', '\d+')
    ->bind('etiquetas_articulo_list');

$app->match('/admin/etiquetas_articulo/{articulo_id}/create', 'Precursor\\Application\\Controller\\EtiquetasArticulo::agregar')
    ->assert('articulo_id', '\d+')
    ->bind('etiquetas_articulo_create');

$app->match('/admin/etiquetas_articulo/{articulo_id}/edit/{id}', 'Precursor\\Application\\Controller\\EtiquetasArticulo::editar')
    ->assert('articulo_id', '\d+')
    ->assert('id', '\d+')
    ->bind('etiquetas_articulo_edit');

$app->match('/admin/etiquetas_articulo/{articulo_id}/delete/{id}', 'Precursor\\Application\\Controller\\EtiquetasArticulo::eliminar')
    ->assert('articulo_id', '\d+')
    ->assert('id', '\d+')
    ->bind('etiquetas_articulo_delete');