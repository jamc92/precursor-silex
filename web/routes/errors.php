<?php

use \PDOException,
    Doctrine\DBAL\DBALException,
    Precursor\Application\Model\Opcion\Menu,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Process\Exception\LogicException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException,
    Symfony\Component\HttpFoundation\Request;

$app->error(function (PDOException $PDOException, $code) {
    return new Response($PDOException->getMessage());
});

$app->error(function (DBALException $DBALException, $code) {
    return new Response($DBALException->getMessage());
});

$app->error(function (LogicException $logicException, $code) {
    return new Response($logicException->getMessage());
});

$app->error(function (NotFoundHttpException $notFoundHttpException) use($app) {
    if (!$app['debug']) {
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
    return new Response($methodNotAllowedHttpException->getMessage());
});

$app->error(function (Twig_Error_Loader $twigError) {
    return new Response($twigError->getMessage());
});