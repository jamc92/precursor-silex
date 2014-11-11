<?php

/**
 * Controlador del módulo de menú
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Opcion\Menu,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

class Opcion
{

    /**
     * @param Application $app
     * @param Request $request
     * 
     * @return mixed
     */
    public function verMenu(Application $app, Request $request)
    {
        $menuModelo = new Menu($app['db']);

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

    /**
     * @param Application $app
     * @param Request $request
     * 
     * @return Response
     */
    public function guardarMenu(Application $app, Request $request)
    {
        $menuModelo = new Menu($app['db']);

        $itemsActual = $menuModelo->getItems();

        $items = $request->get('items');

        $filasAfectadas = 0;

        if (!empty($itemsActual) && !empty($items)) {
            $menu = $menuModelo->getOpcion(null, 'menu');

            $menuModelo->setId($menu['id']);

            $filasAfectadas = $menuModelo->modificar($items);
        } else if (!empty($items)) {
            $filasAfectadas = $menuModelo->guardar($items);
        }

        if ($filasAfectadas) {
            return new Response('Guardado exitosamente.');
        } else {
            return new Response('No se actualizó el menú.');
        }
    }

    /**
     * @param Application $app
     * @param Request $request
     *
     * @return mixed
     */
    public function verCorreos(Application $app, Request $request)
    {
        $menuModelo = new Menu($app['db']);

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

}
