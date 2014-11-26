<?php

/**
 * Controlador del módulo de menú
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Opcion\CustomStyles,
    Precursor\Application\Model\Opcion\Menu,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

class Opcion
{
    
    
    public function customStyles(Application $app, Request $request)
    {
        $customStylesModelo = new CustomStyles($app['db']);
        
        $contenido = $customStylesModelo->getStyles();
        
        $initial_data = array(
            'contenido' => $contenido
        );
        
        $form = $app['form.factory']->createBuilder('form', $initial_data);
        
        $form = $form->add('contenido', 'textarea', array());
        
        $form = $form->getForm();
        
        if ('POST' == $request->getMethod()) {
            $data = $request->get('form');
            
            $filasAfectadas = 0;
            
            $customStyles = $customStylesModelo->getOpcion(null, 'custom_styles');
            
            if (!empty($customStyles) && ($customStyles['value'] !== $data['contenido'])) {

                $customStylesModelo->setId($customStyles['id']);

                $filasAfectadas = $customStylesModelo->modificar($data['contenido']);
            } elseif (!empty($data['contenido'])) {
                $filasAfectadas = $customStylesModelo->guardar($data['contenido']);
            }
            
            $message = ($filasAfectadas > 0) ? 'Exitoso' : 'Nada que actualizar';
            
            return new Response($message);
        }
        
        return $app['twig']->render('backend/custom_styles/index.html.twig', array(
            'form' => $form->createView()
        ));
    }

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
            return new Response('Menú actualizado');
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
