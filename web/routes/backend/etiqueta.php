<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/etiqueta', function () use ($app) {

    $table_columns = array(
        'id',
        'nombre',
        'creado',
        'modificado',
    );

    $primary_key = "id";
    $rows = array();

    $find_sql = "SELECT * FROM `etiqueta`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach ($rows_sql as $row_key => $row_sql) {
        for ($i = 0; $i < count($table_columns); $i++) {

            $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
        }
    }

    return $app['twig']->render('backend/etiqueta/list.html.twig', array(
                "table_columns" => $table_columns,
                "primary_key" => $primary_key,
                "rows" => $rows
    ));
})
->bind('etiqueta_list');



$app->match('/admin/etiqueta/create', function () use ($app) {

    $initial_data = array(
        'nombre' => '',
        'creado' => '',
        'modificado' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



    $form = $form->add('nombre', 'text', array('required' => true));

    $form = $form->getForm();

    if ("POST" == $app['request']->getMethod()) {

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `etiqueta` (`id`, `nombre`, `creado`, `modificado`) VALUES (null, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array($data['nombre']));


            $app['session']->getFlashBag()->add(
                    'success', array(
                'message' => '¡Etiqueta creada!',
                    )
            );
            return $app->redirect($app['url_generator']->generate('etiqueta_list'));
        }
    }

    return $app['twig']->render('backend/etiqueta/create.html.twig', array(
                "form" => $form->createView()
    ));
})
->bind('etiqueta_create');



$app->match('/admin/etiqueta/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `etiqueta` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if (!$row_sql) {
        $app['session']->getFlashBag()->add(
                'warning', array(
            'message' => '¡Etiqueta No encontrada!',
                )
        );
        return $app->redirect($app['url_generator']->generate('etiqueta_list'));
    }


    $initial_data = array(
        'nombre' => $row_sql['nombre'],
        'creado' => $row_sql['creado'],
        'modificado' => $row_sql['modificado'],
    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


    $form = $form->add('nombre', 'text', array('required' => true));

    $form = $form->getForm();

    if ("POST" == $app['request']->getMethod()) {

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `etiqueta` SET `nombre` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['nombre'], $id));


            $app['session']->getFlashBag()->add(
                    'info', array(
                'message' => '¡Etiqueta editada!',
                    )
            );
            return $app->redirect($app['url_generator']->generate('etiqueta_edit', array("id" => $id)));
        }
    }

    return $app['twig']->render('backend/etiqueta/edit.html.twig', array(
                "form" => $form->createView(),
                "id" => $id
    ));
})
->bind('etiqueta_edit');



$app->match('/admin/etiqueta/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `etiqueta` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if ($row_sql) {
        $delete_query = "DELETE FROM `etiqueta` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
                'info', array(
            'message' => '¡Etiqueta eliminada!',
                )
        );
    } else {
        $app['session']->getFlashBag()->add(
                'warning', array(
            'message' => '¡Etiqueta no encontrada!',
                )
        );
    }

    return $app->redirect($app['url_generator']->generate('etiqueta_list'));
})
->bind('etiqueta_delete');






