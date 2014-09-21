<?php
/**
 * Controlador de Perfiles
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Perfil
{

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
            'creado',
            'modificado',
        );

        $primary_key = "id";
        $rows = array();

        $find_sql = "SELECT * FROM `perfil`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        foreach ($rows_sql as $row_key => $row_sql) {
            for ($i = 0; $i < count($table_columns); $i++) {

                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
            }
        }

        return $app['twig']->render('backend/perfil/list.html.twig', array(
            "table_columns" => $table_columns,
            "primary_key" => $primary_key,
            "rows" => $rows
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @return RedirectResponse
     */
    public function agregar(Request $request, Application $app)
    {
        $initial_data = array(
            'id' => '',
            'nombre' => '',
            'creado' => '',
            'modificado' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "INSERT INTO `perfil` (`id`, `nombre`, `creado`, `modificado`) VALUES (null, ?, NOW(), NOW())";
                $app['db']->executeUpdate($update_query, array($data['nombre']));


                $app['session']->getFlashBag()->add(
                    'success', array(
                        'message' => '¡Perfil creado!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('perfil_list'));
            }
        }

        return $app['twig']->render('backend/perfil/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        $find_sql = "SELECT * FROM `perfil` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if (!$row_sql) {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Perfil no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('perfil_list'));
        }


        $initial_data = array(
            'id' => $row_sql['id'],
            'nombre' => $row_sql['nombre'],
            'creado' => $row_sql['creado'],
            'modificado' => $row_sql['modificado'],
        );


        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('nombre', 'text', array('required' => true));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "UPDATE `perfil` SET `nombre` = ? WHERE `id` = ?";
                $app['db']->executeUpdate($update_query, array($data['nombre'], $id));


                $app['session']->getFlashBag()->add(
                    'info', array(
                        'message' => '¡Perfil editado!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('perfil_edit', array("id" => $id)));
            }
        }

        return $app['twig']->render('backend/perfil/edit.html.twig', array(
            "form" => $form->createView(),
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
        $find_sql = "SELECT * FROM `perfil` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if ($row_sql) {
            $delete_query = "DELETE FROM `perfil` WHERE `id` = ?";
            $app['db']->executeUpdate($delete_query, array($id));

            $app['session']->getFlashBag()->add(
                'info', array(
                    'message' => '¡Perfil eliminado!',
                )
            );
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => '¡Perfil no encontrado!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('perfil_list'));
    }
    
} 