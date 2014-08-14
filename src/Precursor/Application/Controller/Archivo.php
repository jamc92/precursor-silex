<?php
/**
 * Description of Base.php
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @package Controller
 */

namespace Precursor\Application\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application,
    Precursor\File\Upload;

class Archivo {

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $archivo = $request->get('archivo');

        if (!empty($archivo)) {

            $file    = fopen($archivo, 'r');
            $content = fread($file, 8192);
            fclose($file);

            return $app['twig']->render('backend/archivo/content.html.twig', array(
                'content' => $content
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
     * @return string
     */
    public function agregar(Request $request, Application $app)
    {
        if("POST" == $request->getMethod()){

            $upload = new Upload('\\Precursor\\File\\Upload\\Php', array(
                'ignore_uploads' => true,
                'upload_dir'     => dirname(dirname(__DIR__)) . "/DeveloperFile/"
            ));

            $result = $upload->file()->upload($_FILES['php']);

            die(json_encode(array('status' => $result['status'])));

        }

        $initial_data = array(
            'contenido' => '',
            'nombre'    => ''
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('contenido', 'textarea', array());
        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if ('PUT' == $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                print_r($data);
                die;
                return '';
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
     * @return RedirectResponse
     */
    public function editar(Request $request, Application $app, $nombre)
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
     * @param Application $app
     * @param string $nombre
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