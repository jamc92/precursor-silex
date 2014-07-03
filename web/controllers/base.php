<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../src/app.php';

require_once __DIR__.'/backend/auth.php';
require_once __DIR__.'/backend/articulo.php';
require_once __DIR__.'/backend/articulos_etiquetas.php';
require_once __DIR__.'/backend/categoria.php';
require_once __DIR__.'/backend/comentario.php';
require_once __DIR__.'/backend/etiqueta.php';
require_once __DIR__.'/backend/perfil.php';
require_once __DIR__.'/backend/usuario.php';



$app->match('/admin', function () use ($app) {

    return $app['twig']->render('ag_dashboard.html.twig', array());
        
})
->bind('admin');


$app->run();
