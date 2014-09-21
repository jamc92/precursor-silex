<?php
/**
 * Controlador de Artículos o Noticias
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

class Noticia
{

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * 
     * @return RedirectResponse
     */
    public function ver(Request $request, Application $app, $id)
    {
        $categoriaModelo = new Categoria($app['db']);
        $categorias = $categoriaModel->getTodo(array(), array(), "WHERE id > 1");

        $articuloModel = new Articulo($app['db']);
        $articulo = $articuloModel->getPorId($id);

        if (empty($articulo)) {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => '¡Artículo no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('home'));
        }
        
        return $app['twig']->render('frontend/noticia.html.twig', array(
            "articulo" => $articulo,
            'categorias' => $categorias,
        ));
    }
    
} 
