<?php

/**
 * Controlador de acciones de usuarios
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Precursor\Application\Model\Comentario,
    Silex\Application,
    Silex\Controller,
    Silex\Route,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\JsonResponse;;

class Usuario
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function login(Request $request, Application $app)
    {
        
        $error_msg = "";
        if ($app['security.last_error']($request) == "Bad credentials") {
            $error_msg = "Usuario o contraseña incorrectos.";
        }

        return $app['twig']->render('backend/auth/login.html.twig', array(
            'error' => $error_msg,
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
    
    /**
     * Función para registrarse como usuario
     * @param Application $app
     * @param Request $request
     * @return mixed
     */
    public function signup(Application $app, Request $request)
    {
        
    }
    

}
