<?php

use Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\HttpFoundation\Request;

$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('backend/auth/login.html.twig', array(
    		'error'         => $app['security.last_error']($request),
        	'last_username' => $app['session']->get('_security.last_username'),
    	));
})
->bind('login');