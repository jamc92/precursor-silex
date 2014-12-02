<?php

use \PDOException,
    Doctrine\DBAL\DBALException,
    Precursor\Application\Model\Opcion\Menu,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request;

$app->error(function (PDOException $PDOException) use($app) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return $app['twig']->render('errors/pdo.html.twig', array(
            'error' => $PDOException->getMessage(),
            'code'  => $PDOException->getCode()
        ));
    }
});

$app->error(function (DBALException $DBALException, $code) use($app) {
    // Guardar la traza en base de datos
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/db.html.twig
        return $app['twig']->render('errors/dbal.html.twig', array(
            'error' => $DBALException->getMessage(),
            'code'  => $DBALException->getCode()
        ));
    }
});

$app->error(function (\LogicException $logicException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Aqui ira la vista error/500.html.twig
        return $app['twig']->render('errors/500.html.twig', array(
            'error' => $logicException->getMessage(),
            'code'  => $logicException->getCode()
        ));
    }
});

$app->error(function (AccessDeniedHttpException $accessDeniedHttpException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        $menuModelo = new Menu($app['db']);
        $menuItems = $menuModelo->getItems();
        
        // Respuesta en frontend
        $uriActual = $_SERVER['REQUEST_URI'];
        
        return $app['twig']->render('errors/403.html.twig', array(
            'uri'        => $uriActual,
            'menu_items' => $menuItems
        ));
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

$app->error(function (MethodNotAllowedHttpException $methodNotAllowedHttpException, $code) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Metodo no permitido 405.html.twig
        $menuModelo = new Menu($app['db']);
        $menuItems = $menuModelo->getItems();
        
        // Respuesta en frontend
        $uriActual = $_SERVER['REQUEST_URI'];
        
        return $app['twig']->render('errors/405.html.twig', array(
            'uri'        => $uriActual,
            'menu_items' => $menuItems
        ));
    }
});

$app->error(function (Twig_Error_Loader $twigError) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return $app['twig']->render('errors/500.html.twig', array(
            'error' => $twigError->getMessage(),
            'code'  => $twigError->getCode()
        ));
    }
});

$app->error(function (Twig_Error_Runtime $twigErrorRuntime) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return $app['twig']->render('errors/500.html.twig', array(
            'error' => $twigErrorRuntime->getMessage(),
            'code'  => $twigErrorRuntime->getCode()
        ));
    }
});

$app->error(function(\Swift_TransportException $swift_TransportException) use($app) {
    if ($app['debug']) {
        return;
    } else {
        // Ocurrio un error en el servido 500.html.twig
        return $app['twig']->render('errors/500.html.twig', array(
            'error' => $swift_TransportException->getMessage(),
            'code'  => $swift_TransportException->getCode()
        ));
    }
});