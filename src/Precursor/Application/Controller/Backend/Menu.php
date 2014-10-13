<?php

/**
 * Controlador del módulo de menú
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Opcion\Menu as MenuModelo,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request;

class Menu
{
    
    /**
     * @param Application $app
     * @param Request $request
     * 
     * @return mixed
     */
    public function index(Application $app, Request $request)
    {
        $menuModelo = new MenuModelo($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        if (!empty($menuItems)) {
            return $app['twig']->render('backend/menu/index.html.twig', array(
                'menu_items' => $menuItems,
                'esEjemplo'  => false
            ));
        } else {
            // Mostrar ejemplo de menú
            
            $menu = '[{"id":1,"link":"' . $app['url_generator']->generate('home') . '","texto":"Inicio","sad":[{"id":2,"link":"http://asasdas","texto":"Item 1"}]},{"id":3,"link":"http://asasdas","texto":"Item 2","children":[{"id":4,"link":"http://asasdas","texto":"Item 3"},{"id":5,"link":"http://asasdas","texto":"Item 4"}]}]';
            
            $menuItems = json_decode($menu);
            
            return $app['twig']->render('backend/menu/index.html.twig', array(
                'menu_items' => $menuItems,
                'esEjemplo'  => true
            ));
        }
    }
    
    public function guardar(Application $app, Request $request)
    {
        $menuModelo = new MenuModelo($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        if (!empty($menuItems)) {
            
        }
        
        $items = $request->get('items');
        
    }
    
}
