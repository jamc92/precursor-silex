<?php
/**
 * Extensión Twig para Widgets de la aplicación
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor;

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
        return array(
            new \Twig_SimpleFunction('precursor_widget', array($this, 'getWidget'))
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