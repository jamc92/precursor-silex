<?php
/**
 * Extensión Twig para Widgets de la aplicación
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor;

use Precursor\Extension\Paginador;

class WidgetExtension extends \Twig_Extension
{

    /**
     * @return string Nombre de la extensión
     */
    public function getName()
    {
        return 'precursor_wigdet';
    }

    /**
     * @return array Funciones de la extensión
     */
    public function getFunctions()
    {
        $paginador = new Paginador();
        return array(
            new \Twig_SimpleFunction('precursor_widget', array($this, 'getWidget')),
            new \Twig_SimpleFunction('paginador_noticias', array($paginador, 'getPaginador'))
        );
    }

    /**
     * @param string $name Nombre del widget
     * @return string Nombre del widget
     */
    public function getWidget($name = "")
    {
        return "Widget $name";
    }

}