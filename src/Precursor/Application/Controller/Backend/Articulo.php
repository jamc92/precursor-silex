<?php
/**
 * Controlador de Articulos
 *
 * @author     Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Categoria,
    Precursor\Application\Model\Etiqueta,
    Precursor\Application\Model\Usuario,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

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
        $articuloModel = new \Precursor\Application\Model\Articulo($app['db']);
        $articulos = $articuloModel->getArticulos();

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
        $usuario = $usuarioModel->getTodo(array('id'), array(), "WHERE alias = ?", array($alias));

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario[0]['id'];

        // Categorías
        $categoriaModel = new Categoria($app['db']);
        $categorias = $categoriaModel->getTodo();
        $categoriasOpcion = array();

        foreach ($categorias as $categoria) {
            $categoriasOpcion[$categoria['id']] = $categoria['nombre'];
        }

        // Etiquetas
        $etiquetaModel = new Etiqueta($app['db']);
        $etiquetas = $etiquetaModel->getTodo();
        $etiquetasOpcion = array();

        foreach ($etiquetas as $etiqueta) {
            $etiquetasOpcion[$etiqueta['id']] = $etiqueta['nombre'];
        }

        $initial_data = array(
            'id_autor'          => $idAutor,
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

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $articuloModel = new \Precursor\Application\Model\Articulo($app['db']);
                $filasAfectadas = $articuloModel->guardar($idAutor, $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido'], $data['etiquetas']);

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

                } elseif (is_int($filasAfectadas) && $filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success',
                        array(
                            'message' => "¡Artículo creado!",
                        )
                    );
                } else {
                    $app['session']->getFlashBag()->add(
                        'danger',
                        array(
                            'message' => "¡Artículo no creado!",
                        )
                    );
                }
                return $app->redirect($app['url_generator']->generate('articulo_list'));
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

        $usuarioModel = new Usuario($app['db']);
        $usuario = $usuarioModel->getTodo(array('id'), array(), "WHERE alias = ?", array($alias));

        // El autor del articulo debe ser el logueado
        $idAutor = $usuario[0]['id'];

        // Categorías
        $categoriaModel = new Categoria($app['db']);
        $categorias = $categoriaModel->getTodo();
        $categoriasOpcion = array();

        foreach ($categorias as $categoria) {
            $categoriasOpcion[$categoria['id']] = $categoria['nombre'];
        }

        // Etiquetas
        $etiquetaModel = new Etiqueta($app['db']);
        $etiquetas = $etiquetaModel->getTodo();
        $etiquetasOpcion = array();

        foreach ($etiquetas as $etiqueta) {
            $etiquetasOpcion[$etiqueta['id']] = $etiqueta['nombre'];
        }

        $articuloModel = new \Precursor\Application\Model\Articulo($app['db']);
        $articulo = $articuloModel->getArticuloYEtiquetas($id);

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
            'id_autor'    => $idAutor,
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
                
                $filasAfectadas = $articuloModel->modificar($id, $idAutor, $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido'], $data['etiquetas']);

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

        $articuloModel = new \Precursor\Application\Model\Articulo($app['db']);
        $articulo = $articuloModel->getPorId($id);

        if (is_array($articulo) && !empty($articulo)) {

            $filasAfectadas = $articuloModel->eliminar($id);

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