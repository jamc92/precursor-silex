<?php
/**
 * Controlador de los Comentarios de un artículo
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @package Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Comentario,
    Precursor\Application\Model\Usuario,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request;

class ComentariosArticulo
{
    
    /**
     * @param Request $request
     * @param Application $app
     * @param int $idArticulo
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app, $idArticulo)
    {
        $comentarioModelo = new Comentario($app['db']);
        $comentarios = $comentarioModelo->getComentariosArticulo($idArticulo);

        return $app['twig']->render('backend/comentarios_articulo/list.html.twig', array(
            'comentarios' => $comentarios,
            'idArticulo'  => $idArticulo
        ));
    }
    
    /**
     * 
     * @param Request $request
     * @param Application $app
     * @param int $idArticulo
     * 
     * @return mixed
     */
    public function agregar(Request $request, Application $app, $idArticulo)
    {
        $alias = $app['security']->getToken()->getUser()->getUsername();

        $usuarioModelo = new Usuario($app['db']);
        $usuario = $usuarioModelo->getUsuarioPorAlias($alias);

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario['id'];
        
        $initial_data = array(
            'contenido'   => ''
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('contenido', 'textarea', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $comentarioModelo = new \Precursor\Application\Model\Comentario($app['db']);
                $filasAfectadas = $comentarioModelo->guardar($idArticulo, $idAutor, $data['contenido']);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success', array(
                            'message' => '¡Comentario creado!',
                        )
                    );
                }
                return $app->redirect($app['url_generator']->generate('comentarios_articulo_list', array('idArticulo' => $idArticulo)));
            }
        }

        return $app['twig']->render('backend/comentarios_articulo/create.html.twig', array(
            "form"       => $form->createView(),
            'idArticulo' => $idArticulo
        ));
    }
    
    /**
     * 
     * @param Request $request
     * @param Application $app
     * @param int $idArticulo
     * @param int $id
     * 
     * @return mixed
     */
    public function eliminar(Request $request, Application $app, $idArticulo, $id)
    {
        $comentarioModelo = new Comentario($app['db']);
        $comentario = $comentarioModelo->getPorId($id);
        
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
        
        return $app->redirect($app['url_generator']->generate('comentarios_articulo_list', array('idArticulo' => $idArticulo)));
    }
    
}