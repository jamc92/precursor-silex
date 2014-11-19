<?php
/**
 * Rutas de las acciones del suscriptor
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/suscriptor', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::registrar')
    ->bind('suscriptor_registrar')
    ->method('POST');

$app->match('/categorias.json', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::categoriasAjax')
    ->bind('suscriptor_categorias_list_ajax')
    ->method('POST');

$app->match('/agregar-categorias', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::guardarCategorias')
    ->bind('suscriptor_guardar_categorias')
    ->method('POST');