<?php
/**
 * Controlador de Artículos o Noticias
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage API
 */

namespace Precursor\Application\Controller\API;

use Precursor\Application\Model\Articulo,
    Precursor\Application\Model\Categoria,
    Symfony\Component\HttpFoundation\Request,
    Silex\Application,
    Symfony\Component\HttpFoundation\JsonResponse,
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
    public function verNoticiasJson(Request $request, Application $app)
    {
        $categoriaModelo = new Categoria($app['db']);
        $categorias = $categoriaModelo->getTodo(array(), array(), "WHERE id > 1");

        $articuloModelo = new Articulo($app['db']);
        $articulos = $articuloModelo->getTodo();
        
        $mesesIngles  = cal_info(0);
        $mesesEspanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        foreach ($articulos as $index => $articulo) {
            
            # Poner sólo la primera en mayúscula el título
            $articulos[$index]['titulo']    = strtolower($articulo['titulo']);
            $articulos[$index]['titulo'][0] = strtoupper($articulo['titulo'][0]);
            
            $fechaPublicacion = date('d-F-Y | h:m A', strtotime($articulo['fecha_pub']));
            $fechaPublicacion = str_replace('-', ' de ', $fechaPublicacion);
            $fechaPublicacion = str_replace($mesesIngles['months'], $mesesEspanol, $fechaPublicacion);
            
            $articulos[$index]['fecha_pub'] = $fechaPublicacion;
        }
        
        return new JsonResponse (array(
            "articulos"   => $articulos,
            'categorias' => $categorias
        ));
    }

} 
