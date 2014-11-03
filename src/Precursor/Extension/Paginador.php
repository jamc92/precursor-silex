<?php

/**
 * Paginador de los artículos o de las noticias
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
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
    protected function getNumeroPaginas($porPagina = 12, $numArticulos)
    {
        $esPar = $numArticulos % $porPagina;
        if ($numArticulos > 0 && ($numArticulos > $porPagina && $esPar == 0)) {
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
    public function getPaginador($porPagina = 12, array $articulos = array(), Request $request) {
        $numeroPaginas = $this->getNumeroPaginas($porPagina, count($noticias));
        
    }

}
