<?php

$app->match('/', function () use ($app) {

    return $app['twig']->render('frontend/index.html.twig', array());
        
})
->bind('home');