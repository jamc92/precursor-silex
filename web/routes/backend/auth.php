<?php

use Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\HttpFoundation\Request;

$app->get('/login', function (Request $request) use ($app) {

    $error_msg = "";
    if ($app['security.last_error']($request) == "Bad credentials") {
        $error_msg = "Usuario o contraseña incorrectos.";
    }

    return $app['twig']->render('backend/auth/login.html.twig', array(
    		'error'         => $error_msg,
        	'last_username' => $app['session']->get('_security.last_username'),
    	));
})
->bind('login');