<?php
/**
 * Description of Base.php
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @package Controller
 */

namespace Precursor\Application\Controller;

use Symfony\Component\HttpFoundation\Request,
    Silex\Application;

class Base
{

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function index(Request $request, Application $app) {
        return $app['twig']->render('ag_dashboard.html.twig', array());
    }

    /**
     * @param Request $request
     * @param Application $app
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
} 