<?php

use \PDOException,
    Doctrine\DBAL\DBALException,
    Precursor\Application\Model\Opcion\Menu,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Process\Exception\LogicException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request;

$app->error(function (PDOException $PDOException, $code) use($app) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return new Response(/*$PDOException->getMessage()*/);
    }
});

$app->error(function (DBALException $DBALException, $code) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return new Response($DBALException->getMessage());
    }
});

$app->error(function (LogicException $logicException, $code) {
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/500.html.twig
        return new Response($logicException->getMessage());
    }
});

$app->error(function (NotFoundHttpException $notFoundHttpException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        $menuModelo = new Menu($app['db']);
        $menuItems = $menuModelo->getItems();
        
        // Respuesta en frontend
        $uriActual = $_SERVER['REQUEST_URI'];
        
        return $app['twig']->render('errors/404.html.twig', array(
            'uri'        => $uriActual,
            'menu_items' => $menuItems
        ));
    }
});

$app->error(function (MethodNotAllowedHttpException $methodNotAllowedHttpException, $code) {
    if ($app['debug']) {
        return;
    } else {
        // Metodo no permitido 405.html.twig
        return new Response($methodNotAllowedHttpException->getMessage());
    }
});

$app->error(function (Twig_Error_Loader $twigError) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return new Response($twigError->getMessage());
    }
});

$app->error(function(\Swift_TransportException $swift_TransportException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return new Response('Ocurrió un error: '. $swift_TransportException->getMessage());
    }
});