<?php
/**
 * Description of Base.php
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @package Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Base
{

    public function index(Request $request, Application $app)
    {

        $categorias = array();

        $categorias_sql = "SELECT * FROM `categoria` WHERE id > 1";
        $categoria_sql = $app['db']->fetchAll($categorias_sql, array());

        foreach ($categoria_sql as $cat_key => $cat_value) {
            $categorias[$cat_key] = $cat_value;
        }

        $articulos = array();

        $articulos_sql = "SELECT * FROM `articulo`";
        $articulo_sql = $app['db']->fetchAll($articulos_sql, array());

        foreach ($articulo_sql as $art_key => $art_value) {
            $articulos[$art_key] = $art_value;
        }

        return $app['twig']->render('frontend/index.html.twig', array(
            'categorias' => $categorias,
            'articulos' => $articulos
        ));
    }

} 