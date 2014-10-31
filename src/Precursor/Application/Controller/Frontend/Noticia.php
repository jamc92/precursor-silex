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
    Precursor\Application\Model\Opcion\Menu,
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
        $categorias = $categoriaModelo->getTodo(array(), array(), "WHERE id > 1");

        $articuloModel = new Articulo($app['db']);
        $articulo = $articuloModel->getArticuloYEtiquetas($id);
        
        $mesesIngles  = cal_info(0);
        $mesesEspanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        if (empty($articulo)) {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => '¡Artículo no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('home'));
        } else {
            $fechaPublicacion = date('d-F-Y | h:m A', strtotime($articulo['fecha_pub']));
            $fechaPublicacion = str_replace('-', ' de ', $fechaPublicacion);
            $fechaPublicacion = str_replace($mesesIngles['months'], $mesesEspanol, $fechaPublicacion);
            
            $articulo['fecha_pub'] = $fechaPublicacion;
        }
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        return $app['twig']->render('frontend/noticia.html.twig', array(
            "articulo"   => $articulo,
            'categorias' => $categorias,
            'menu_items' => $menuItems
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return RedirectResponse
     */
    public function busqueda(Request $request, Application $app)
    {
        $busqueda = $request->get('busqueda');

        $articuloModel = new Articulo($app['db']);

        $result = $articuloModel->getArticuloBy($busqueda);

        var_dump($result);

        return "";
    }

} 
