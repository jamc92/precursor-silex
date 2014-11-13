<?php
/**
 * Controlador por Defecto
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 *
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Precursor\Application\Model\Articulo,
    Precursor\Application\Model\Categoria,
    Precursor\Application\Model\Opcion\Menu,
    Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    Symfony\Component\HttpFoundation\JsonResponse,
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
        $categoriaModelo = new Categoria($app['db']);
        $categorias = $categoriaModelo->getTodo(array(), array(), "WHERE id > 1");

        $articuloModelo = new Articulo($app['db']);
        $articulos = $articuloModelo->getTodo();
        
        $mesesIngles  = cal_info(0);
        $mesesEspanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        foreach ($articulos as $index => $articulo) {
            
            # Poner sólo la primera en mayúscula el título
//            $articulos[$index]['titulo']    = strtolower($articulo['titulo']);
//            $articulos[$index]['titulo'][0] = strtoupper($articulo['titulo'][0]);
            
            $fechaPublicacion = date('d-F-Y | h:m A', strtotime($articulo['fecha_pub']));
            $fechaPublicacion = str_replace('-', ' de ', $fechaPublicacion);
            $fechaPublicacion = str_replace($mesesIngles['months'], $mesesEspanol, $fechaPublicacion);
            
            $articulos[$index]['fecha_pub'] = $fechaPublicacion;
        }
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        return $app['twig']->render('frontend/index.html.twig', array(
            'articulos'  => $articulos,
            'categorias' => $categorias,
            'menu_items' => $menuItems
        ));
    }
    
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param Application $app
     * 
     * @return JsonResponse
     */
    public function categoriasAjax(Request $request, Application $app)
    {
        if ($request->isXmlHttpRequest() && 'POST' == $request->getMethod()) {
            $categoriaModelo = new Categoria($app['db']);
            $categorias = $categoriaModelo->getTodo();
            
            return $app['twig']->render('frontend/categorias-ajax.html.twig', array(
                'categorias' => $categorias
            ));
        }
    }
} 