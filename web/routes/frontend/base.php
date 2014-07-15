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

$app->match('/noticia/{id}', function($id) use($app) {

    $categorias = array();

    $categorias_sql = "SELECT * FROM `categoria` WHERE id > 1";
    $categoria_sql  = $app['db']->fetchAll($categorias_sql, array());

    foreach ($categoria_sql as $cat_key => $cat_value) {
        $categorias[$cat_key] = $cat_value;
    }

    $find_sql = "SELECT * FROM `articulo` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => '¡Artículo no encontrado!',
            )
        );
        return $app->redirect($app['url_generator']->generate('home'));
    }
    return $app['twig']->render('frontend/noticia.html.twig', array(
        "articulo" => $row_sql,
        'categorias' => $categorias,
    ));
})
->bind('noticia');