<?php

/**
 * Paginador de los artículos o de las noticias
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 *
 * 
 * @subpackage Extension
 */

namespace Precursor\Extension;

use Symfony\Component\HttpFoundation\Request;

class Paginador
{

    /**
     * Obtener el número de páginas para una cantidad de productos
     * @param int $porPagina
     * @param int $numArticulos
     * 
     * @return int Número de páginas
     */
    protected function getNumeroPaginas($numArticulos, $porPagina = 12)
    {
        if ($numArticulos > 0 && $porPagina > 0) {
            return $numArticulos/$porPagina;
        } else {
            return 0;
        }
    }
    
    /**
     * Paginador HTML
     * @param int $porPagina Cantidad de artículos por página
     * @param array $articulos Arreglo de los artículos a paginar
     * @param Request $request
     * 
     * @return string Html del paginador
     */
    public function getPaginador(array $articulos = array(), $porPagina = 9, Request $request)
    {
        $html = '';
        $numeroPaginas = $this->getNumeroPaginas(count($articulos), $porPagina);

        //Se obtiene el numero de pagina
        $numPage = $request->get('page');

        if ( is_null($numPage)) {
            $numPage = 1;
        }

        if ( $numeroPaginas != 1 ) {
            $html .= '<li class="previous"> <a href="#">&laquo;</a></li>';
        }

        if ($numPage > 1) {
            for ($i = 1; $i <= $numeroPaginas; $i++ ) {
                $html .= '<li> <a href="#">' . $i . '</a> </li>';
            }
        }

        if ($numPage != $numeroPaginas && $numPage < $numeroPaginas) {
            $html .= '<li class="next"> <a href="#">&raquo;</a></li>';
        }

        echo $html;
    }

}
