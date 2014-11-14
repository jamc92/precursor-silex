<?php
/**
 * Controlador por Defecto
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\LogViewer\LogViewer,
    Precursor\Application\Model\Articulo,
    Precursor\Application\Model\Comentario,
    Silex\Application,
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
        $comentarioModelo = new Comentario($app['db']);

        $comentarios = $comentarioModelo->getComentarios(array(), "WHERE comentario.estatus = 'I'");


        $articuloModelo = new Articulo($app['db']);

        $articulos = $articuloModelo->getTodo('fecha_pub', "I");

        return $app['twig']->render('ag_dashboard.html.twig', array(
            'comentarios' => $comentarios,
            'articulos' => $articulos
        ));
    }

    
}