<?php
/**
 * Controlador de Articulos
 *
 * @author     Ramón Serrano <ramon.calle.88@gmail.com>
 * @author     Javier Madrid <javiermadrid19@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Articulo as ArticuloModelo,
    Precursor\Application\Model\Categoria,
    Precursor\Application\Model\Etiqueta,
    Precursor\Application\Model\Usuario,
    Silex\Application,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Articulo
{

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed|RedirectResponse
     */
    public function ver(Request $request, Application $app)
    {
        $articuloModelo = new ArticuloModelo($app['db']);
        $articulos = $articuloModelo->getArticulos();
        
        return $app['twig']->render('backend/articulo/list.html.twig', array(
            "articulos" => $articulos
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed
     */
    public function agregar(Request $request, Application $app)
    {
        $alias = $app['security']->getToken()->getUser()->getUsername();

        $usuarioModel = new Usuario($app['db']);
        $usuario = $usuarioModel->getUsuarioPorAlias($alias);

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario['id'];

        // Categorías
        $categoriaModelo = new Categoria($app['db']);
        $categorias = $categoriaModelo->getTodo();
        $categoriasOpcion = array();

        foreach ($categorias as $categoria) {
            $categoriasOpcion[$categoria['id']] = $categoria['nombre'];
        }

        // Etiquetas
        $etiquetaModelo = new Etiqueta($app['db']);
        $etiquetas = $etiquetaModelo->getTodo();
        $etiquetasOpcion = array();

        foreach ($etiquetas as $etiqueta) {
            $etiquetasOpcion[$etiqueta['id']] = $etiqueta['nombre'];
        }

        $initial_data = array(
            'imagen'            => '',
            'categoria'         => '',
            'titulo'            => '',
            'descripcion'       => '',
            'contenido'         => '',
            'fecha_publicacion' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('categoria', 'choice', array(
            'choices'  => $categoriasOpcion,
            'required' => true
        ));
        $form = $form->add('etiquetas', 'choice', array(
            'choices'  => $etiquetasOpcion,
            'required' => false,
            "multiple" => true
        ));
        $form = $form->add('imagen', 'url', array('required' => true));
        $form = $form->add('titulo', 'text', array('required' => true));
        $form = $form->add('descripcion', 'text', array('required' => true));
        $form = $form->add('contenido', 'textarea', array('required' => true));

        $form = $form->getForm();

        if ($request->isXmlHttpRequest()) {
             $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $articuloModelo = new ArticuloModelo($app['db']);

                $accion = $request->get('accion');

                if ($accion == 'borrador'){
                    $filasAfectadas = $articuloModelo->guardar($idAutor, $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido'], $data['etiquetas'], 'B');
                } elseif ($accion == 'publicado') {
                    $filasAfectadas = $articuloModelo->guardar($idAutor, $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido'], $data['etiquetas'], 'A');
                }
                
                if (is_array($filasAfectadas)) {
                    if (!empty($filasAfectadas['etiquetas'])) {
                        $filasAfectadasEtiqueta = count($filasAfectadas['etiquetas']);

                        $etiquetasAgregadas = array();

                        foreach ($filasAfectadas['etiquetas'] as $etiqueta) {
                            $etiquetasAgregadas[] = $etiquetasOpcion[$etiqueta];
                        }

                        $message = "¡Artículo creado! Etiquetas agregadas: $filasAfectadasEtiqueta - " . implode(', ', $etiquetasAgregadas) . ".";

                    } else {
                        $message = "¡Artículo creado!";
                    }

                    $app['session']->getFlashBag()->add(
                        'success',
                        array(
                            'message' => $message,
                        )
                    );
                    
                    return new JsonResponse('Articulo creado', 200);

                } else {
                    return new JsonResponse('Articulo no guardado', 202);
                }
            } else {
                
                print_r($form->getErrors());
                
                return new JsonResponse('No todos los campos fueron completados', 202);
            }
        }

        return $app['twig']->render('backend/articulo/create.html.twig', array(
            "form" => $form->createView(),
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
        $alias = $app['security']->getToken()->getUser()->getUsername();

        $usuarioModelo = new Usuario($app['db']);
        $usuario = $usuarioModelo->getUsuarioPorAlias($alias);

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario['id'];

        // Categorías
        $categoriaModelo = new Categoria($app['db']);
        $categorias = $categoriaModelo->getTodo();
        $categoriasOpcion = array();

        foreach ($categorias as $categoria) {
            $categoriasOpcion[$categoria['id']] = $categoria['nombre'];
        }

        // Etiquetas
        $etiquetaModelo = new Etiqueta($app['db']);
        $etiquetas = $etiquetaModelo->getTodo();
        $etiquetasOpcion = array();

        foreach ($etiquetas as $etiqueta) {
            $etiquetasOpcion[$etiqueta['id']] = $etiqueta['nombre'];
        }

        $articuloModelo = new ArticuloModelo($app['db']);
        $articulo = $articuloModelo->getArticuloYEtiquetas($id);

        if (!$articulo) {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => '¡Artículo no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('articulo_list'));
        }

        $initial_data = array(
            'categoria'   => $articulo['id_categoria'],
            'etiquetas'   => $articulo['etiquetas'],
            'imagen'      => $articulo['imagen'],
            'titulo'      => $articulo['titulo'],
            'descripcion' => $articulo['descripcion'],
            'contenido'   => $articulo['contenido'],
            'fecha_pub'   => $articulo['fecha_pub'],
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('categoria', 'choice', array(
            'choices'  => $categoriasOpcion,
            'required' => true
        ));
        $form = $form->add('etiquetas', 'choice', array(
            'choices'  => $etiquetasOpcion,
            'required' => false,
            "multiple" => true
        ));
        $form = $form->add('imagen', 'url', array('required' => true));
        $form = $form->add('titulo', 'text', array('required' => true));
        $form = $form->add('descripcion', 'text', array('required' => true));
        $form = $form->add('contenido', 'textarea', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $app['request']->getMethod()) {

            $form->handleRequest($app["request"]);

            if ($form->isValid()) {
                $data = $form->getData();
                
                $filasAfectadas = $articuloModelo->modificar($id, $idAutor, $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido'], $data['etiquetas']);

                if (is_array($filasAfectadas) || (is_int($filasAfectadas) && $filasAfectadas == 1)) {
                    $app['session']->getFlashBag()->add(
                        'info',
                        array(
                            'message' => "¡Artículo modificado!",
                        )
                    );
                } else {
                    $app['session']->getFlashBag()->add(
                        'danger',
                        array(
                            'message' => "¡Artículo no modificado!",
                        )
                    );
                }

                return $app->redirect($app['url_generator']->generate('articulo_edit', array("id" => $id)));
            }
        }

        return $app['twig']->render('backend/articulo/edit.html.twig', array(
            "form"       => $form->createView(),
            "imagen_src" => $articulo['imagen'],
            "id"         => $id
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

        $articuloModelo = new ArticuloModelo($app['db']);
        $articulo = $articuloModelo->getPorId($id);

        if (is_array($articulo) && !empty($articulo)) {

            $filasAfectadas = $articuloModelo->eliminar($id);

            if ($filasAfectadas > 0) {
                $app['session']->getFlashBag()->add(
                    'info',
                    array(
                        'message' => '¡Artículo Eliminado!',
                    )
                );
            }

        } else {
            $app['session']->getFlashBag()->add(
                'warning',
                array(
                    'message' => 'Artículo no encontrado',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('articulo_list'));
    }

}