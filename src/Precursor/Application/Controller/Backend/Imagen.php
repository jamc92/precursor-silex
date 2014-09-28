<?php
/**
 * Controlador de Imágenes
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Imagen as ImagenModelo,
    Precursor\File\Upload,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Imagen
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {   
        $imagenModelo = new ImagenModelo($app['db']);
        $imagenes = $imagenModelo->getImagenes();

        return $app['twig']->render('backend/imagen/list.html.twig', array(
            "imagenes" => $imagenes
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function verJson(Request $request, Application $app)
    {
        $imagenModelo = new ImagenModelo($app['db']);
        $imagenes = $imagenModelo->getImagenes();

        return $app['twig']->render('backend/imagen/listJson.html.twig', array(
            'imagenes' => $imagenes
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
        if("POST" == $request->getMethod()) {
            if (isset($_FILES['image'])) {
                
                $upload = new Upload('\\Precursor\\File\\Upload\\Image', array('upload_dir' => $app['upload_dir'], 'ignore_uploads' => false));

                $result = $upload->file()->upload($_FILES['image']);

                if (isset($result['vars']['imagen'])) {
                    $vars = $result['vars'];

                    $nombre = $vars['imagen'];
                    $link = "$app[upload_path]/$vars[folder]/$vars[imagen]";

                    $imagenModelo = new ImagenModelo($app['db']);
                    $filasAfectadas = $imagenModelo->guardar($nombre, $link);
                }
                die(json_encode(array('result' => $result['result'])));
                
            } else {
                die(json_encode(array('result' => 'Ninguna imagen')));
            }
        }

        return $app['twig']->render('backend/imagen/create.html.twig', array());
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
        $imagenModelo = new ImagenModelo($app['db']);
        $imagen = $imagenModelo->getPorId($id);

        if (!empty($imagen)) {
            $initial_data = array(
                'nombre' => $imagen['nombre'],
                'link' => $imagen['link'],
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('nombre', 'text', array('required' => true));
            #$form = $form->add('descripcion', 'textarea', array('required' => false));

            $form = $form->getForm();

            if("POST" == $request->getMethod()){

                $form->handleRequest($request);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $filasAfectadas = $imagenModelo->modificar($id, $data['nombre'], $data['link']);
                    
                    if ($filasAfectadas == 1) {
                        $app['session']->getFlashBag()->add(
                            'success',
                            array(
                                'message' => '¡Imagen modificada!',
                            )
                        );
                    }
                    return $app->redirect($app['url_generator']->generate('imagen_edit', array("id" => $id)));
                }
            }
        } else {
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡imagen no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('imagen_list'));
        }

        return $app['twig']->render('backend/imagen/edit.html.twig', array(
            "form" => $form->createView(),
            "imagen" => $imagen,
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
        $imagenModelo = new ImagenModelo($app['db']);
        $imagen = $imagenModelo->getPorId($id);

        if(!empty($imagen)){

            // Eliminar archivos
            $search_str = $app['upload_path'] . "/";
            $file = $app['upload_dir'] . str_replace($search_str, '', $imagen['link']);

            if (!unlink($file)) {
                $app['session']->getFlashBag()->add(
                    'danger',
                    array(
                        'message' => "¡Imagen <$file> no pudo ser eliminada eliminada!",
                    )
                );
            }

            $filasAfectadas = $imagenModelo->eliminar($id);
            
            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Imagen eliminada!',
                    )
                );
            }
        }
        else{
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡Imagen no encontrada!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('imagen_list'));
    }
    
}