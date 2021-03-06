<?php
/**
 * Controlador de Comentarios de Artículos
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 * 
 * 
 * @package Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Articulo,
    Precursor\Application\Model\Comentario as ComentarioModelo,
    Precursor\Application\Model\Usuario,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;
    
class Comentario
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $comentarioModelo = new ComentarioModelo($app['db']);
        $comentarios = $comentarioModelo->getComentarios();

        return $app['twig']->render('backend/comentario/list.html.twig', array(
            "comentarios" => $comentarios
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
        $alias = $app['security']->getToken()->getUser()->getUsername();

        $usuarioModelo = new Usuario($app['db']);
        $usuario = $usuarioModelo->getUsuarioPorAlias($alias);

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario['id'];
        
        $articuloModelo = new Articulo($app['db']);
        $articulos = $articuloModelo->getTodo();
        $articulosOpcion = array();
        
        foreach ($articulos as $articulo) {
            $articulosOpcion[$articulo['id']] = $articulo['titulo'];
        }
        
        $initial_data = array(
            'id_articulo' => '',
            'contenido'   => ''
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('id_articulo', 'choice', array(
            'choices' => $articulosOpcion,
            'required' => true
        ));
        $form = $form->add('contenido', 'textarea', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $comentarioModelo = new ComentarioModelo($app['db']);
                $filasAfectadas = $comentarioModelo->guardar($data['id_articulo'], $idAutor, $data['contenido']);
                $filasAfectadas = $comentarioModelo->guardar($data['id_articulo'], $idAutor, $data['contenido']);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success', array(
                            'message' => '¡Comentario creado!',
                        )
                    );
                }
                return $app->redirect($app['url_generator']->generate('comentario_list'));
            }
        }

        return $app['twig']->render('backend/comentario/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    public function aprobar(Request $request, Application $app, $id)
    {
        $comentarioModelo = new ComentarioModelo($app['db']);
        $comentario = $comentarioModelo->getPorId($id);

        $response = array();

        if (!empty($comentario)) {
            $filasAfectadas = $comentarioModelo->aprobar($id);

            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'success', array(
                        'message' => '¡Comentario aprobado!',
                    )
                );
            }
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Comentario no encontrado!',
                )
            );
        }
        return $app->redirect($app['url_generator']->generate('admin'));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $id
     * 
     * @return JsonResponse|RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $id)
    {
        $comentarioModelo = new ComentarioModelo($app['db']);
        $comentario = $comentarioModelo->getPorId($id);

        if ('GET' == $request->getMethod()) {
            if (!empty($comentario)) {
                $filasAfectadas = $comentarioModelo->eliminar($id);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'info', array(
                            'message' => '¡Comentario eliminado!',
                        )
                    );
                }
            } else {
                $app['session']->getFlashBag()->add(
                    'warning', array(
                        'message' => '¡Comentario no encontrado!',
                    )
                );
            }

            return $app->redirect($app['url_generator']->generate('comentario_list'));
        } elseif ('POST' == $request->getMethod()) {
            if (!empty($comentario)) {
                $filasAfectadas = $comentarioModelo->eliminar($id);

                if ($filasAfectadas == 1) {
                    $response = array(
                        'mensaje' => 'Comentario eliminado.'
                    );
                    $app['session']->getFlashBag()->add(
                        'info', array(
                            'message' => '¡Comentario eliminado!',
                        )
                    );
                }
            } else {
                $response = array(
                    'mensaje' => 'Comentario no encontrado.'
                );
            }
            return new JsonResponse($response);
        }
    }

} 