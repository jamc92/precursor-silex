<?php
/**
 * Controlador de Etiquetas
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Etiqueta
{

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {        
        $etiquetaModelo = new \Precursor\Application\Model\Etiqueta($app['db']);
        $etiquetas = $etiquetaModelo->getTodo();

        return $app['twig']->render('backend/etiqueta/list.html.twig', array(
            "etiquetas" => $etiquetas
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed|RedirectResponse
     */
    public function agregar(Request $request, Application $app)
    {
        $initial_data = array(
            'nombre'     => '',
            'creado'     => '',
            'modificado' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $etiquetaModelo = new \Precursor\Application\Model\Etiqueta($app['db']);
                $filasAfectadas = $etiquetaModelo->guardar($data['nombre']);
                
                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success', array(
                            'message' => '¡Etiqueta creada!',
                        )
                    );
                }
                
                return $app->redirect($app['url_generator']->generate('etiqueta_list'));
            }
        }

        return $app['twig']->render('backend/etiqueta/create.html.twig', array(
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
        $etiquetaModelo = new \Precursor\Application\Model\Etiqueta($app['db']);
        $etiqueta = $etiquetaModelo->getPorId($id);

        if (!empty($etiqueta)) {
            
            $initial_data = array(
                'nombre'     => $etiqueta['nombre'],
                'creado'     => $etiqueta['creado'],
                'modificado' => $etiqueta['modificado'],
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('nombre', 'text', array('required' => true));

            $form = $form->getForm();

            if ("POST" == $request->getMethod()) {

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $filasAfectadas = $etiquetaModelo->modificar($id, $data['nombre']);

                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'info', array(
                                'message' => '¡Etiqueta editada!',
                            )
                        );
                    }
                    return $app->redirect($app['url_generator']->generate('etiqueta_edit', array("id" => $id)));
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Etiqueta No encontrada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('etiqueta_list'));
        }

        return $app['twig']->render('backend/etiqueta/edit.html.twig', array(
            "form" => $form->createView(),
            "id" => $id
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
        $etiquetaModelo = new \Precursor\Application\Model\Etiqueta($app['db']);
        $etiqueta = $etiquetaModelo->getPorId($id);

        if (!empty($etiqueta)) {
            $filasAfectadas = $etiquetaModelo->eliminar($id);
            
            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'info', array(
                        'message' => '¡Etiqueta eliminada!',
                    )
                );
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Etiqueta no encontrada!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('etiqueta_list'));
    }
    
}