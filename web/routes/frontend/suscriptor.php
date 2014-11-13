<?php
/**
 * Rutas de las acciones del suscriptor
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/suscriptor', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::registrar')
    ->bind('registrar_suscriptor')
    ->method('POST');

$app->match('/categorias.json', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::categoriasAjax')
    ->bind('categoria_list_ajax')
    ->method('POST');