<?php
/**
 * Controlador de Perfiles
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Perfil
{

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $perfilModelo = new \Precursor\Application\Model\Perfil($app['db']);
        $perfiles = $perfilModelo->getTodo();
        
        $perfilesImportantes = $perfilModelo->getPerfilesImportantes();
        foreach ($perfiles as $i => $perfil) {
            if (in_array($perfil['id'], $perfilesImportantes)) {
                $perfiles[$i]['importante'] = true;
            } else {
                $perfiles[$i]['importante'] = false;
            }
        }

        return $app['twig']->render('backend/perfil/list.html.twig', array(
            'perfiles' => $perfiles
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return RedirectResponse
     */
    public function agregar(Request $request, Application $app)
    {
        $initial_data = array(
            'nombre' => ''
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $perfilModelo = new \Precursor\Application\Model\Perfil($app['db']);
                if ($perfilModelo->existe($data['nombre'])) {
                    $app['session']->getFlashBag()->add(
                        'warning', array(
                            'message' => '¡Perfil ya existe!',
                        )
                    );
                } else {
                    $filasAfectadas = $perfilModelo->guardar($data['nombre']);
                    
                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'success', array(
                                'message' => '¡Perfil creado!',
                            )
                        );
                    }
                }
                return $app->redirect($app['url_generator']->generate('perfil_list'));
            }
        }

        return $app['twig']->render('backend/perfil/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        $perfilModelo = new \Precursor\Application\Model\Perfil($app['db']);
        
        $perfilesImportantes = $perfilModelo->getPerfilesImportantes();
        
        if (in_array($id, $perfilesImportantes)) {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Perfil no puede ser editado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('perfil_list'));
        }
        
        $perfil = $perfilModelo->getPorId($id);

        if (!empty($perfil)) {
            
            $initial_data = array(
                'nombre' => $perfil['nombre'],
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('nombre', 'text', array('required' => true));

            $form = $form->getForm();

            if ("POST" == $request->getMethod()) {

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    if ($perfilModelo->existe($data['nombre'])) {
                        $app['session']->getFlashBag()->add(
                            'warning', array(
                                'message' => '¡Perfil ya existe!',
                            )
                        );
                    } else {
                        $filasAfectadas = $perfilModelo->modificar($id, $data['nombre']);

                        if ($filasAfectadas == 1) {
                            $app['session']->getFlashBag()->add(
                                'info', array(
                                    'message' => '¡Perfil editado!',
                                )
                            );
                        }
                    }
                    return $app->redirect($app['url_generator']->generate('perfil_edit', array("id" => $id)));
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Perfil no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('perfil_list'));
        }

        return $app['twig']->render('backend/perfil/edit.html.twig', array(
            "form" => $form->createView(),
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
        $perfilModelo = new \Precursor\Application\Model\Perfil($app['db']);
        $perfil = $perfilModelo->getPorId($id);

        if (!empty($perfil)) {
            $perfilesImportantes = $perfilModelo->getPerfilesImportantes();
            if (in_array($id, $perfilesImportantes)) {
                $app['session']->getFlashBag()->add(
                    'warning', array(
                        'message' => '¡Perfil importante! No puede ser eliminado.',
                    )
                );
            } else {
                $filasAfectadas = $perfilModelo->eliminar($id);
                
                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'info', array(
                            'message' => '¡Perfil eliminado!',
                        )
                    );
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Perfil no encontrado!',
                )
            );
        }
        return $app->redirect($app['url_generator']->generate('perfil_list'));
    }
    
} 