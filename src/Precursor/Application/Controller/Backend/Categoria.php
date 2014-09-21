<?php
/**
 * Controlador de Categorías
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Categoria
{

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $categoriaModelo = new \Precursor\Application\Model\Categoria($app['db']);
        $categorias = $categoriaModelo->getCategorias();

        return $app['twig']->render('backend/categoria/list.html.twig', array(
            "categorias" => $categorias
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function agregar(Request $request, Application $app)
    {
        $categoriaModelo = new \Precursor\Application\Model\Categoria($app['db']);
        $categorias = $categoriaModelo->getTodo();
        
        $options = array();

        foreach($categorias as $categoria) {
            $options[$categoria['id']] = $categoria['nombre'];
        }

        $initial_data = array(
            'categoria_superior' => '',
            'nombre' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('categoria_superior', 'choice', array(
            'choices' => $options,
            'required' => true
        ));

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if("POST" == $request->getMethod()){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $filasAfectadas = $categoriaModelo->guardar($data['categoria_superior'], $data['nombre']);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success',
                        array(
                            'message' => '¡Categoría creada!',
                        )
                    );
                }
                
                return $app->redirect($app['url_generator']->generate('categoria_list'));
            }
        }

        return $app['twig']->render('backend/categoria/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return mixed|RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        $categoriaModelo = new \Precursor\Application\Model\Categoria($app['db']);
        
        $categoria = $categoriaModelo->getPorId($id);
        
        if (!empty($categoria)) {
            $categorias = $categoriaModelo->getTodo();

            $options = array();

            foreach($categorias as $categoria) {
                $options[$categoria['id']] = $categoria['nombre'];
            }

            $initial_data = array(
                'nombre' => $categoria['nombre'],
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('categoria_superior', 'choice', array(
                'choices' => $options,
                'data' => $categoria['id_categoria'],
                'required' => false
            ));
            $form = $form->add('nombre', 'text', array('required' => true));

            $form = $form->getForm();

            if("POST" == $request->getMethod()){

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $filasAfectadas = $categoriaModelo->modificar($id, $data['categoria_superior'], $data['nombre']);
                    
                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'info',
                            array(
                                'message' => 'Categoría editada!',
                            )
                        );
                    }
                    
                    return $app->redirect($app['url_generator']->generate('categoria_edit', array("id" => $id)));
                }
            }

            return $app['twig']->render('backend/categoria/edit.html.twig', array(
                "form" => $form->createView(),
                "id" => $id
            ));
        } else {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => '¡Categoría no encontrada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('categoria_list'));
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $id)
    {
        $categoriaModelo = new \Precursor\Application\Model\Categoria($app['db']);
        
        $categoria = $categoriaModelo->getPorId($id);
        
        if (!empty($categoria)) {
            $filasAfectadas = $categoriaModelo->eliminar($id);
            
            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'info',
                    array(
                        'message' => '¡Categoría eliminada!',
                    )
                );
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => 'Categoría no encontrada',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('categoria_list'));
    }

} 