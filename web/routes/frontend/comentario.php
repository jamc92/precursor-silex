<?php
/**
 * Rutas de las acciones del comentario
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Frontend\\Comentario::verAjax')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_ajax');

$app->match('/comentar', 'Precursor\\Application\\Controller\\Frontend\\Comentario::guardarComentario')
    ->bind('guardar_comentario')
    ->method('POST');