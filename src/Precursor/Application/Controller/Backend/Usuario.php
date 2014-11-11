<?php
/**
 * Controlador de Usuarios
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Perfil,
    Precursor\Application\Model\Usuario as UsuarioModelo,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Usuario
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $usuarioModelo = new UsuarioModelo($app['db']);
        $usuarios = $usuarioModelo->getUsuarios();

        return $app['twig']->render('backend/usuario/list.html.twig', array(
            "usuarios" => $usuarios
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
        // Se adquiere el encoder
        $encoder = $app['security.encoder.digest'];
        // Se adquiere el token
        $token = $app['security']->getToken();
        // Se adquiere el usuario
        if (is_object($token)) {
            $user = $token->getUser();
        }

        $perfilModelo = new Perfil($app['db']);
        $perfiles = $perfilModelo->getTodo();
        
        foreach ($perfiles as $perfil) {
            $options[$perfil['id']] = $perfil['nombre'];
        }
        
        if ($app['user']['perfil'] === 'ROLE_EDITOR') {
            unset($options[1]);
        }

        $initial_data = array(
            'id_perfil' => '',
            'nombre' => '',
            'correo' => '',
            'alias' => '',
            'clave' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('id_perfil', 'choice', array(
            'choices' => $options,
            'required' => true
        ));
        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('correo', 'email', array('required' => true));
        $form = $form->add('alias', 'text', array('required' => true));
        $form = $form->add('clave', 'password', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                // codificar la clave
                $clave = $encoder->encodePassword($data['clave'], $user->getSalt());
                
                $usuarioModelo = new UsuarioModelo($app['db']);
                $filasAfectadas = $usuarioModelo->guardar($data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $clave);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success', array(
                            'message' => '¡Usuario creado!',
                        )
                    );
                }

                return $app->redirect($app['url_generator']->generate('usuario_list'));
            }
        }

        return $app['twig']->render('backend/usuario/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * 
     * @return mixed|RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        // Se adquiere el encoder
        $encoder = $app['security.encoder.digest'];
        // Se adquiere el token
        $token = $app['security']->getToken();
        // Se adquiere el usuario
        if (is_object($token)) {
            $user = $token->getUser();
        }

        $usuarioModelo = new UsuarioModelo($app['db']);
        $usuario = $usuarioModelo->getPorId($id);

        if (!empty($usuario)) {
            $perfilModelo = new Perfil($app['db']);
            $perfiles = $perfilModelo->getTodo();

            foreach ($perfiles as $perfil) {
                $options[$perfil['id']] = $perfil['nombre'];
            }

            $initial_data = array(
                'nombre' => $usuario['nombre'],
                'correo' => $usuario['correo'],
                'alias'  => $usuario['alias'],
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('id_perfil', 'choice', array(
                'choices'  => $options,
                'data'     => $usuario['id_perfil'],
                'required' => true
            ));
            $form = $form->add('nombre', 'text', array('required' => true));
            $form = $form->add('correo', 'text', array('required' => true));
            $form = $form->add('alias', 'text', array('required' => true));
            $form = $form->add('nueva_clave', 'password', array('required' => false));

            $form = $form->getForm();

            if ("POST" == $request->getMethod()) {

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    if (!empty($data['nueva_clave'])) {
                        // codificar la clave
                        $nueva_clave = $encoder->encodePassword($data['nueva_clave'], $user->getSalt());
                        $filasAfectadas = $usuarioModelo->modificar($id, $data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $nueva_clave);
                    } else {
                        $filasAfectadas = $usuarioModelo->modificar($id, $data['id_perfil'], $data['nombre'], $data['correo'], $data['alias']);
                    }

                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'info', array(
                                'message' => '¡Usuario editado!',
                            )
                        );
                    }

                    return $app->redirect($app['url_generator']->generate('usuario_edit', array("id" => $id)));
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Usuario no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('usuario_list'));
        }

        return $app['twig']->render('backend/usuario/edit.html.twig', array(
            "form" => $form->createView(),
            "id" => $id
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * 
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $id)
    {
        $usuarioModelo = new UsuarioModelo($app['db']);
        $usuario = $usuarioModelo->getPorId($id);

        if (!empty($usuario)) {
            $filasAfectadas = $usuarioModelo->eliminar($id);
            
            if ($filasAfectadas >= 1) {
                $app['session']->getFlashBag()->add(
                    'info', array(
                        'message' => 'Usuario eliminado!',
                    )
                );
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Usuario no encontrado!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('usuario_list'));
    }

} 