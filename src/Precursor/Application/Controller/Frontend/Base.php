<?php
/**
 * Controlador por Defecto
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Precursor\Application\Model\Articulo,
    Precursor\Application\Model\Categoria,
    Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Base
{
    /**
     * @param Application $app
     *
     * @return mixed
     */
    public function index(Application $app)
    {
        $categoriaModel = new Categoria($app['db']);
        $categorias = $categoriaModel->getTodo(array(), array(), "WHERE id > 1");

        $articuloModel = new Articulo($app['db']);
        $articulos = $articuloModel->getTodo();

        foreach ($articulos as $index => $articulo) {
            $fechaPublicacion      = date('d-F-Y | h:m:s A', strtotime($articulo['fecha_pub']));
            $fechaPublicacion      = str_replace('-', ' de ', $fechaPublicacion);
            $articulos[$index]['fecha_pub'] = $fechaPublicacion;
        }

        return $app['twig']->render('frontend/index.html.twig', array(
            'categorias' => $categorias,
            'articulos' => $articulos
        ));
    }
} 