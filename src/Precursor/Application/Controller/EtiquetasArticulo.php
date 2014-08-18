<?php
/**
 * Description of EtiquetasArticulo.php
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @package Controller
 */

namespace Precursor\Application\Controller;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class EtiquetasArticulo {

    /**
     * @param Request $request
     * @param Application $app
     * @param int $articulo_id
     * @return mixed
     */
    public function ver(Request $request, Application $app, $articulo_id)
    {
        $table_columns = array(
            'id',
            'id_etiqueta',
        );

        $primary_key = "id";
        $rows = array();

        $find_sql = "SELECT * FROM `articulos_etiquetas`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        foreach ($rows_sql as $row_key => $row_sql) {
            for ($i = 0; $i < count($table_columns); $i++) {
                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('backend/etiquetas_articulo/list.html.twig', array(
            "table_columns" => $table_columns,
            "primary_key" => $primary_key,
            "articulo_id" => $articulo_id,
            "rows" => $rows
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $articulo_id
     * @return mixed|RedirectResponse
     */
    public function agregar(Request $request, Application $app, $articulo_id)
    {
        // Etiquetas
        $find_sql = "SELECT * FROM `etiqueta`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());
        $options_etiq = array();

        foreach($rows_sql as $row_key => $row_sql) {
            $options_etiq[$row_sql['id']] = $row_sql['nombre'];
        }

        $initial_data = array(
            'id_articulo' => $articulo_id,
            'id_etiqueta' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('id_etiqueta', 'choice', array(
            'choices' => $options_etiq,
            'required' => true
        ));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "INSERT INTO `articulos_etiquetas` (`id_articulo`, `id_etiqueta`) VALUES (?, ?)";
                $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_etiqueta']));


                $app['session']->getFlashBag()->add(
                    'success', array(
                        'message' => '¡Etiqueta de Artículo creada!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list', array('articulo_id' => $articulo_id)));
            }
        }

        return $app['twig']->render('backend/etiquetas_articulo/create.html.twig', array(
            "form" => $form->createView(),
            "articulo_id" => $articulo_id
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $articulo_id
     * @param int $id
     * @return mixed|RedirectResponse
     */
    public function editar(Request $request, Application $app, $articulo_id, $id)
    {
        // Etiquetas
        $find_sql = "SELECT * FROM `etiqueta`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());
        $options_etiq = array();

        foreach($rows_sql as $row_key => $row_sql) {
            $options_etiq[$row_sql['id']] = $row_sql['nombre'];
        }

        $find_sql = "SELECT * FROM `articulos_etiquetas` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if (!$row_sql) {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => 'Etiqueta de Artículo no encontrada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list', array('articulo_id' => $articulo_id)));
        }


        $initial_data = array(
            'id_articulo' => $row_sql['id_articulo'],
            'id_etiqueta' => $row_sql['id_etiqueta'],
        );


        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('id_etiqueta', 'choice', array(
            'choices' => $options_etiq,
            'required' => true
        ));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "UPDATE `articulos_etiquetas` SET `id_articulo` = ?, `id_etiqueta` = ? WHERE `id` = ?";
                $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_etiqueta'], $id));


                $app['session']->getFlashBag()->add(
                    'info', array(
                        'message' => 'Etiqueta de Artículo editada!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('etiquetas_articulo_edit', array('articulo_id' => $articulo_id, "id" => $id)));
            }
        }

        return $app['twig']->render('backend/etiquetas_articulo/edit.html.twig', array(
            "form" => $form->createView(),
            "articulo_id" => $articulo_id,
            "id" => $id
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $articulo_id
     * @param int $id
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $articulo_id, $id)
    {
        $find_sql = "SELECT * FROM `articulos_etiquetas` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if ($row_sql) {
            $delete_query = "DELETE FROM `articulos_etiquetas` WHERE `id` = ?";
            $app['db']->executeUpdate($delete_query, array($id));

            $app['session']->getFlashBag()->add(
                'info', array(
                    'message' => 'Etiqueta de Artículo eliminada!',
                )
            );
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => 'Etiqueta de Artículo no encontrada',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list', array("articulo_id" => $articulo_id)));
    }

} 