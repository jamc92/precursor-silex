<?php
/**
 * Controlador de Opciones
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Opcion
{

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $opcionModelo = new \Precursor\Application\Model\Opcion($app['db']);
        $opciones = $opcionModelo->getTodo();
        
        return $app['twig']->render('backend/opcion/list.html.twig', array(
            "opciones" => $opciones
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed|RedirectResponse
     */
    public function agregar(Request $request, Application $app)
    {
        $initial_data = array(
            'tipo' => '',
            'nombre' => '',
            'valor' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('tipo', 'choice', array(
            'choices' => array(
                'text'      => 'String',
                'number'    => 'Int|Float',
                'color'     => 'Color',
                'email'     => 'Email',
                'date'      => 'Date',
                'datetime'  => 'Datetime',
                'html'      => 'HTML',
                //'php'       => 'PHP',
                'js'      => 'JS|JSON',
                //'array-php' => 'Array'
            ),
            'required' => true
        ));
        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('valor', 'hidden', array('required' => true));

        $form = $form->getForm();

        if("POST" == $request->getMethod()){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $opcionModelo = new \Precursor\Application\Model\Opcion($app['db']);
                $filasAfectadas = $opcionModelo->guardar($data['tipo'], $data['nombre'], $data['valor']);
                
                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success',
                        array(
                            'message' => '¡Opción creada con éxito!',
                        )
                    );
                }
                return $app->redirect($app['url_generator']->generate('opcion_list'));
            }
        }

        return $app['twig']->render('backend/opcion/create.html.twig', array(
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
        $opcionModelo = new \Precursor\Application\Model\Opcion($app['db']);
        $opcion = $opcionModelo->getPorId($id);

        if (!empty($opcion)) {
            $initial_data = array(
                'nombre' => $opcion['nombre'],
                'valor'  => $opcion['valor']
            );


            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('tipo', 'choice', array(
                'choices' => array(
                    'text'      => 'String',
                    'number'    => 'Int|Float',
                    'color'     => 'Color',
                    'email'     => 'Email',
                    'date'      => 'Date',
                    'datetime'  => 'Datetime',
                    'html'      => 'HTML',
                    //'php'       => 'PHP',
                    'js'      => 'JSON',
                    //'array-php' => 'Array'
                ),
                'data'     => $opcion['tipo'],
                'required' => true
            ));
            $form = $form->add('nombre', 'text', array('required' => true));
            $form = $form->add('valor', 'hidden', array('required' => true));

            $form = $form->getForm();

            if("POST" == $request->getMethod()){

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $filasAfectadas = $opcionModelo->modificar($id, $data['tipo'], $data['nombre'], $data['valor']);

                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'success',
                            array(
                                'message' => '¡Opción modificada con éxito!',
                            )
                        );
                    }
                    return $app->redirect($app['url_generator']->generate('opcion_edit', array("id" => $id)));
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡Opción no encontrada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('opcion_list'));
        }

        return $app['twig']->render('backend/opcion/edit.html.twig', array(
            "form"  => $form->createView(),
            "tipo"  => $opcion['tipo'],
            "valor" => json_encode($opcion['valor'])
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $id)
    {
        $opcionModelo = new \Precursor\Application\Model\Opcion($app['db']);
        $opcion = $opcionModelo->getPorId($id);

        if(!empty($row_sql)) {
            $filasAfectadas = $opcionModelo->eliminar($id);
            
            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Opción eliminada con éxito!',
                    )
                );
            }
        }
        else{
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡Opción no encontrada!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('opcion_list'));
    }

} 