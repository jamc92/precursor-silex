<?php

$app->match('/', function () use ($app) {

    $categorias = array();

    $categorias_sql = "SELECT * FROM `categoria` WHERE id > 1";
    $categoria_sql  = $app['db']->fetchAll($categorias_sql, array());

    foreach ($categoria_sql as $cat_key => $cat_value) {
        $categorias[$cat_key] = $cat_value;
    }

    $articulos = array();

    $articulos_sql = "SELECT * FROM `articulo`";
    $articulo_sql  = $app['db']->fetchAll($articulos_sql, array());

    foreach ($articulo_sql as $art_key => $art_value) {
        $articulos[$art_key] = $art_value;
    }

    return $app['twig']->render('frontend/index.html.twig', array(
        'categorias' => $categorias,
        'articulos' => $articulos
    ));
        
})
->bind('home');