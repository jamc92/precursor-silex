<?php
/**
 * Description of Imagen.php
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @package Controller
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application,
    Precursor\File\Upload;

class Imagen {

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $table_columns = array(
            'id',
            'nombre',
            'link',
        );

        $primary_key = "id";
        $rows = array();

        $find_sql = "SELECT * FROM `imagen`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        foreach($rows_sql as $row_key => $row_sql){
            for($i = 0; $i < count($table_columns); $i++){
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('backend/imagen/list.html.twig', array(
            "table_columns" => $table_columns,
            "primary_key" => $primary_key,
            "rows" => $rows
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function verJson(Request $request, Application $app)
    {

        $table_columns = array(
            'id',
            'nombre',
            'link',
        );

        $imagenes = array();

        $find_sql = "SELECT * FROM `imagen`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());
        foreach($rows_sql as $row_key => $row_sql){
            for($i = 0; $i < count($table_columns); $i++){
                $imagenes[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('backend/imagen/listJson.html.twig', array(
            'imagenes' => $imagenes
        ));

    }

    /**
     * @param Request $request
     * @param Application $app
     * @return mixed
     */
    public function agregar(Request $request, Application $app)
    {
        if("POST" == $request->getMethod()){

            $upload = new Upload('\\Precursor\\File\\Upload\\Image', array('upload_dir' => $app['upload_dir'], 'ignore_uploads' => false));

            $result = $upload->file()->upload($_FILES['image']);

            if (isset($result['vars']['imagen'])) {
                $vars = $result['vars'];

                $nombreImagen = $vars['imagen'];
                $linkImagen = "$app[upload_path]/$vars[folder]/$vars[imagen]";

                $update_query = "INSERT INTO `imagen` (`nombre`, `link`, `creado`) VALUES (?, ?, NOW())";
                $app['db']->executeUpdate($update_query, array($nombreImagen, $linkImagen));
            }

            die(json_encode(array('status' => $result['status'])));

        }

        return $app['twig']->render('backend/imagen/create.html.twig', array());
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return mixed|RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        $find_sql = "SELECT * FROM `imagen` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if(!$row_sql){
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡imagen no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('imagen_list'));
        }

        $initial_data = array(
            'nombre' => $row_sql['nombre'],
            'link' => $row_sql['link'],
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('descripcion', 'textarea', array('required' => false));

        $form = $form->getForm();

        if("POST" == $request->getMethod()){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "UPDATE `imagen` SET `nombre` = ?, `link` = ? WHERE `id` = ?";
                $app['db']->executeUpdate($update_query, array($data['nombre'], $data['link'], $id));

                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Imagen modificada!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('imagen_edit', array("id" => $id)));

            }
        }

        return $app['twig']->render('backend/imagen/edit.html.twig', array(
            "form" => $form->createView(),
            "imagen" => $row_sql,
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
        $find_sql = "SELECT * FROM `imagen` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if($row_sql){

            // Eliminar archivos
            $search_str = $app['upload_path'] . "/";
            $file = $app['upload_dir'] .str_replace($search_str, '', $row_sql['link']);

            if (!unlink($file)) {
                $app['session']->getFlashBag()->add(
                    'danger',
                    array(
                        'message' => "¡Imagen <$file> no pudo ser eliminada eliminada!",
                    )
                );
            }

            // Eliminar los archivos

            $delete_query = "DELETE FROM `imagen` WHERE `id` = ?";
            $app['db']->executeUpdate($delete_query, array($id));

            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡Imagen eliminada!',
                )
            );
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