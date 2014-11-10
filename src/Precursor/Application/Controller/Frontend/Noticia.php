<?php
/**
 * Controlador de Artículos o Noticias
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
    Symfony\Component\HttpFoundation\RedirectResponse;
use Precursor\Application\Model\Etiqueta;
use Precursor\Application\Model\Imagen;

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

            $imagenModel = new Imagen($app['db']);

            $articulo['imagen'] = $imagenModel->getImagenByUrl($articulo['imagen']);
            
            $articulo['fecha_pub'] = $fechaPublicacion;
        }
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        return $app['twig']->render('frontend/noticia.html.twig', array(
            'articulo'   => $articulo,
            'categorias' => $categorias,
            'menu_items' => $menuItems
        ));
    }

    public function imprimir(Request $request, Application $app, $idArticulo)
    {
        $articuloModel = new Articulo($app['db']);
        $articulo = $articuloModel->getArticuloYEtiquetas($idArticulo);

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

            $imagenModel = new Imagen($app['db']);

            $articulo['imagen'] = $imagenModel->getImagenByUrl($articulo['imagen']);

            $articulo['fecha_pub'] = $fechaPublicacion;
        }

        return $app['twig']->render('frontend/imprimir.html.twig', array(
            "articulo"   => $articulo
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

        $articulos = $articuloModel->getArticuloBy($busqueda);
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();

        return $app['twig']->render('frontend/busqueda.html.twig', array(
            'articulos' => $articulos,
            'menu_items' => $menuItems
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $idCategoria
     *
     * @return mixed
     */
    public function categoria(Request $request, Application $app, $idCategoria)
    {
        $articuloModel = new Articulo($app['db']);

        $articulosCategoria = $articuloModel->getArticulosByCategoria($idCategoria);

        $categoriaModel = new Categoria($app['db']);

        $categoria = $categoriaModel->getPorId($idCategoria);

        $menuModelo = new Menu($app['db']);

        $menuItems = $menuModelo->getItems();

        return $app['twig']->render('frontend/articulos_categoria.html.twig', array(
            'articulosCategoria' => $articulosCategoria,
            'categoria' => $categoria,
            'menu_items' => $menuItems
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $idEtiqueta  ID de la etiqueta del articulo
     *
     * @return mixed
     */
    public function etiqueta(Request $request, Application $app, $idEtiqueta)
    {
        $articuloModel = new Articulo($app['db']);

        $articulosEtiqueta = $articuloModel->getArticulosByEtiqueta($idEtiqueta);

        $etiquetaModel = new Etiqueta($app['db']);

        $etiqueta = $etiquetaModel->getPorId($idEtiqueta);

        $menuModelo = new Menu($app['db']);

        $menuItems = $menuModelo->getItems();

        return $app['twig']->render('frontend/articulos_etiqueta.html.twig', array(
            'articulosEtiqueta' => $articulosEtiqueta,
            'etiqueta' => $etiqueta,
            'menu_items' => $menuItems
        ));
    }
} 
