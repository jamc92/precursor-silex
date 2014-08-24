<?php
/**
 * Description of Noticia.php
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @package Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Noticia
{

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return RedirectResponse
     */
    public function ver(Request $request, Application $app, $id)
    {
        $categorias = array();

        $categorias_sql = "SELECT * FROM `categoria` WHERE id > 1";
        $categoria_sql  = $app['db']->fetchAll($categorias_sql, array());

        foreach ($categoria_sql as $cat_key => $cat_value) {
            $categorias[$cat_key] = $cat_value;
        }

        $find_sql = "SELECT * FROM `articulo` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if(!$row_sql){
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => '¡Artículo no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('home'));
        }
        return $app['twig']->render('frontend/noticia.html.twig', array(
            "articulo" => $row_sql,
            'categorias' => $categorias,
        ));
    }
} 