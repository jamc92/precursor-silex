<?php

/**
 * Extension para imprimir los estilos personalizados de la aplicacion
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Extension
 */

namespace Precursor\Extension;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model\Opcion\CustomStyles as CustomStylesModelo;

class CustomStyles
{

    /**
     * Imprimee la etiqueta style con los CSS personalizados
     * 
     * @param \Doctrine\DBAL\Connection $conn
     */
    public function rawHtmlStyles(Connection $conn)
    {
        $csmodelo = new CustomStylesModelo($conn);
        
        $styles = $csmodelo->getStyles();
        
        echo '<style id="custom_styles">' . $styles . '</style>';
    }

}
