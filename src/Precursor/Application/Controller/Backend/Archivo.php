<?php
/**
 * Controlador de Archivos
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\File\Upload,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse;

class Archivo
{

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $archivo = $request->get('archivo');

        if (!empty($archivo)) {

            #$file    = fopen($archivo, 'r');
            #$content = fread($file, 8192);
            #fclose($file);
            $contenido = file_get_contents($archivo);

            return $app['twig']->render('backend/archivo/content.html.twig', array(
                'content' => $contenido
            ));
        } else {
            $explorer = $app['explorer'];

            if (strstr($request->getUri(), "protected") && $app['security']->isGranted('ROLE_SUPER_ADMIN')) {
                $files = array();
                $filesProtected = $explorer->getFilesProtected();
            } else {
                $files          = $explorer->getFiles();
                if ($app['security']->isGranted('ROLE_SUPER_ADMIN')) {
                    $filesProtected = $explorer->getFilesProtected();
                } else {
                    $filesProtected = array();
                }
            }

            return $app['twig']->render('backend/archivo/list.html.twig', array(
                'rows'           => $files,
                'rows_protected' => $filesProtected
            ));
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return string
     */
    public function agregar(Request $request, Application $app)
    {
        $initial_data = array(
            'nombre'    => ''
        );

        /* @var Symfony\Component\Form\FormFactory */
        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();
        
        if("POST" == $request->getMethod()) {
            if (!empty($_FILES)) {
                $upload = new Upload('\\Precursor\\File\\Upload\\Php', array(
                    'ignore_uploads' => true,
                    'upload_dir'     => dirname(dirname(__DIR__)) . "/DeveloperFile/"
                ));

                $result = $upload->file()->upload($_FILES['php']);

                die(json_encode(array('status' => $result['status'])));
            } else {
                $form->handleRequest($request);
                
                if ($form->isValid()) {
                    $data = $form->getData();
                    print_r($data);die;
                } else {
                    
                }
            }
        }

        return $app['twig']->render('backend/archivo/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param string $nombre
     * 
     * @return RedirectResponse
     */
    public function editar(Request $request, Application $app, $nombre)
    {
        $direccion = $app['request']->get('direccion');
        
        if (!empty($direccion)) {

            $contenido = file_get_contents($direccion . "/$nombre");
            #$contenido = fread($file, 8192);
            #fclose($file);

            $initial_data = array(
                'contenido' => $contenido,
                'nombre'    => $nombre
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('contenido', 'textarea', array());
            $form = $form->add('nombre', 'text', array('required' => true));

            $form = $form->getForm();

            if ($request->isXmlHttpRequest() && 'PUT' == $request->getMethod()) {
                $data = $request->get('form');
                
                if ($contenido !== $data['contenido']) {
                    $result = file_put_contents($direccion . "/$nombre", $data['contenido']);
                    
                    $message = ($result) ? 'Exitoso' : 'No escrito';
                    
                    return new Response($message);
                } else {
                    return new Response('Nada que actualizar');
                }
            }

            return $app['twig']->render('backend/archivo/edit.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
            return $app->redirect($app['url_generator']->generate('archivo_list'));
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param string $nombre
     * 
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $nombre)
    {
        $direccion = $app['request']->get('direccion');

        if (!empty($direccion)) {
            return new Response($direccion . "/$nombre");
        } else {
            return $app->redirect($app['url_generator']->generate('archivo_list'));
        }

    }

    /**
     * @param Request $request
     * 
     * @return string
     */
    public function sintaxis(Request $request)
    {
        $archivo = $request->get('archivo');

        if (!empty($archivo)) {

            if (function_exists('shell_exec'))
                $result = shell_exec("php -l $archivo");
            else
                $result = 'Función deshabilitada.';

            return $result;

        } else {
            return '';
        }
    }
    
}