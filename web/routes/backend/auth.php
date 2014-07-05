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


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';

use Symfony\Component\Validator\Constraints as Assert,
    Symfony\Component\HttpFoundation\Request;

$app->get('/login', function (Request $request) use ($app) {
    return $app['twig']->render('backend/auth/login.html.twig', array(
    		'error'         => $app['security.last_error']($request),
        	'last_username' => $app['session']->get('_security.last_username'),
    	));
})
->bind('login');