<?php
/**
 * Controlador por Defecto
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

class Base
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function index(Request $request, Application $app)
    {
        return $app['twig']->render('ag_dashboard.html.twig', array());
    }
    
}