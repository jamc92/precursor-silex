<?php
/**
 * Description of WidgetExtension.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor;

class WidgetExtension extends \Twig_Extension
{

    public function getName()
    {
        return 'precursor_wigdet';
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('precursor_widget', array($this, 'getWidget'))
        );
    }

    public function getWidget($name = "")
    {
        return "Widget $name";
    }

} 