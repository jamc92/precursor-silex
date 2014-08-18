<?php
/**
 * Description of Opcion.php
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @package Controller
 */

namespace Precursor\Application\Controller\Backend;

use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Silex\Application;

class Opcion {

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $table_columns = array(
            'id',
            'tipo',
            'nombre',
        );

        $primary_key = "id";
        $rows = array();

        $find_sql = "SELECT * FROM `opcion`";
        $rows_sql = $app['db']->fetchAll($find_sql, array());

        foreach($rows_sql as $row_key => $row_sql){
            for($i = 0; $i < count($table_columns); $i++){

                $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];

            }
        }

        return $app['twig']->render('backend/opcion/list.html.twig', array(
            "table_columns" => $table_columns,
            "primary_key" => $primary_key,
            "rows" => $rows
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
        $initial_data = array(
            'tipo' => '',
            'nombre' => '',
            'valor' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('tipo', 'choice', array(
            'choices' => array(
                'text'      => 'String',
                'number'    => 'Int|Float',
                'color'     => 'Color',
                'email'     => 'Email',
                'date'      => 'Date',
                'datetime'  => 'Datetime',
                'html'      => 'HTML',
                //'php'       => 'PHP',
                'js'      => 'JS|JSON',
                //'array-php' => 'Array'
            ),
            'required' => true
        ));
        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('valor', 'hidden', array('required' => true));

        $form = $form->getForm();

        if("POST" == $request->getMethod()){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "INSERT INTO `opcion` (`tipo`, `nombre`, `valor`, `creado`) VALUES (?, ?, ?, NOW())";
                $app['db']->executeUpdate($update_query, array($data['tipo'], $data['nombre'], $data['valor']));

                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Opción creada con éxito!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('opcion_list'));

            }
        }

        return $app['twig']->render('backend/opcion/create.html.twig', array(
            "form" => $form->createView()
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $id
     * @return mixed|RedirectResponse
     */
    public function editar(Request $request, Application $app, $id)
    {
        $find_sql = "SELECT * FROM `opcion` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if(!$row_sql){
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡opcion no encontrado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('opcion_list'));
        }


        $initial_data = array(
            'tipo' => $row_sql['tipo'],
            'nombre' => $row_sql['nombre'],
            'valor' => $row_sql['valor'],
        );


        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('tipo', 'choice', array(
            'choices' => array(
                'text'      => 'String',
                'number'    => 'Int|Float',
                'color'     => 'Color',
                'email'     => 'Email',
                'date'      => 'Date',
                'datetime'  => 'Datetime',
                'html'      => 'HTML',
                //'php'       => 'PHP',
                'js'      => 'JSON',
                //'array-php' => 'Array'
            ),
            'required' => true
        ));
        $form = $form->add('nombre', 'text', array('required' => true));
        $form = $form->add('valor', 'hidden', array('required' => true));

        $form = $form->getForm();

        if("POST" == $request->getMethod()){

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $update_query = "UPDATE `opcion` SET `tipo` = ?, `nombre` = ?, `valor` = ? WHERE `id` = ?";
                $app['db']->executeUpdate($update_query, array($data['tipo'], $data['nombre'], $data['valor'], $id));


                $app['session']->getFlashBag()->add(
                    'success',
                    array(
                        'message' => '¡Opción modificada con éxito!',
                    )
                );
                return $app->redirect($app['url_generator']->generate('opcion_edit', array("id" => $id)));

            }
        }

        return $app['twig']->render('backend/opcion/edit.html.twig', array(
            "form"  => $form->createView(),
            "id"    => $id,
            "tipo"  => $row_sql['tipo'],
            "valor" => json_encode($row_sql['valor'])
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
        $find_sql = "SELECT * FROM `opcion` WHERE `id` = ?";
        $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

        if($row_sql){
            $delete_query = "DELETE FROM `opcion` WHERE `id` = ?";
            $app['db']->executeUpdate($delete_query, array($id));

            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡Opción eliminada con éxito!',
                )
            );
        }
        else{
            $app['session']->getFlashBag()->add(
                'danger',
                array(
                    'message' => '¡Opción no encontrada!',
                )
            );
        }

        return $app->redirect($app['url_generator']->generate('opcion_list'));
    }

} 