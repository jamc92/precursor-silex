<?php
/**
 * Rutas de las acciones de noticias
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/noticia/{id}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::ver')
    ->assert('idArticulo', '\d+')
    ->bind('noticia');

$app->match('/busqueda', 'Precursor\\Application\\Controller\\Frontend\\Noticia::busqueda')
    ->bind('busqueda')
    ->method('GET');

$app->match('/categoria/{idCategoria}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::categoria')
    ->assert('idCateoria', '\d+')
    ->bind('articulos_categoria')
    ->method('GET');

$app->match('/etiqueta/{idEtiqueta}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::etiqueta')
    ->assert('idEtiqueta', '\d+')
    ->bind('articulos_etiqueta')
    ->method('GET');

$app->match('/noticia/{idArticulo}/imprimir', 'Precursor\\Application\\Controller\\Frontend\\Noticia::imprimir')
    ->bind('imprimir_noticia');

$app->match('/noticia/{idArticulo}/pdf', 'Precursor\\Application\\Controller\\Frontend\\Noticia::pdf')
    ->bind('noticia_pdf');