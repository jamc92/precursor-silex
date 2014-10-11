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

class Usuario extends Controller
{

    function __construct() {
        parent::__construct(new Route());
    }
    
    public function signup(Application $app, Request $request)
    {
        return 'asdasdsa';
    }

}
