<?php

use \PDOException,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\Process\Exception\LogicException,
    Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException,
    Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

$app->error(function (PDOException $PDOException, $code) {
    return new Response($PDOException->getMessage());
});

$app->error(function (LogicException $logicException, $code) {
    return new Response($logicException->getMessage());
});

$app->error(function (NotFoundHttpException $notFoundHttpException, $code) {
    return new Response($notFoundHttpException->getMessage());
});

$app->error(function (MethodNotAllowedHttpException $methodNotAllowedHttpException, $code) {
    return new Response($methodNotAllowedHttpException->getMessage());
});