<?php

$app->match('/admin/etiquetas_articulo/{articulo_id}', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::ver')
    ->assert('articulo_id', '\d+')
    ->bind('etiquetas_articulo_list');

$app->match('/admin/etiquetas_articulo/{articulo_id}/create', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::agregar')
    ->assert('articulo_id', '\d+')
    ->bind('etiquetas_articulo_create');

$app->match('/admin/etiquetas_articulo/{articulo_id}/edit/{id}', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::editar')
    ->assert('articulo_id', '\d+')
    ->assert('id', '\d+')
    ->bind('etiquetas_articulo_edit');

$app->match('/admin/etiquetas_articulo/{articulo_id}/delete/{id}', 'Precursor\\Application\\Controller\\Backend\\EtiquetasArticulo::eliminar')
    ->assert('articulo_id', '\d+')
    ->assert('id', '\d+')
    ->bind('etiquetas_articulo_delete');