<?php
/**
 * Rutas de las acciones del usuario
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

$app->match('/check_user_login', 'Precursor\\Application\\Controller\\Frontend\\Usuario::checkUser')
    ->bind('check_user_login')
    ->method('POST');

$app->match('/login', 'Precursor\\Application\\Controller\\Frontend\\Usuario::login')
    ->bind('login');

$app->match('/signup', 'Precursor\\Application\\Controller\\Frontend\\Usuario::signup')
    ->bind('signup');

$app->match('/auth/{service}', 'Precursor\\Application\\Controller\\Frontend\\Usuario::auth')
    ->assert('service', '[a-z]+')
    ->bind('auth_service');

$app->match('/micuenta', 'Precursor\\Application\\Controller\\Frontend\\Usuario::miCuenta')
    ->bind('micuenta')
    ->method('GET|POST');