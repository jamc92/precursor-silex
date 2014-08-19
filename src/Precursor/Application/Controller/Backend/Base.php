<?php
/**
 * Description of Base.php
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @package Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request,
    Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class Base
{

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function index(Request $request, Application $app)
    {
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

    /**
     * @param Request $request
     * @param Application $app
     * @return Response
     */
    public function checkUser(Request $request, Application $app)
    {
        if (isset($_SESSION['_sf2_attributes']['_security_default'])) {
            $roles = unserialize($_SESSION['_sf2_attributes']['_security_default'])->getUser()->getRoles();
            if (in_array('ROLE_SUPER_ADMIN', $roles) || in_array('ROLE_ADMIN', $roles))
                return new Response($app['url_generator']->generate('admin'));
            else
                return new Response($app['url_generator']->generate('home'));
        } else {
            return new Response($app['url_generator']->generate('login'));
        }
    }
} 