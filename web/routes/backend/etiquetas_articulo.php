<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/etiquetas_articulo/{articulo_id}', function ($articulo_id) use ($app) {

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
})
->bind('etiquetas_articulo_list');

$app->match('/admin/etiquetas_articulo/{articulo_id}/create', function ($articulo_id) use ($app) {

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

    if ("POST" == $app['request']->getMethod()) {

        $form->handleRequest($app["request"]);

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
})
->bind('etiquetas_articulo_create');

$app->match('/admin/etiquetas_articulo/{articulo_id}/edit/{id}', function ($articulo_id, $id) use ($app) {

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

    if ("POST" == $app['request']->getMethod()) {

        $form->handleRequest($app["request"]);

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
})
->bind('etiquetas_articulo_edit');

$app->match('/admin/etiquetas_articulo/{articulo_id}/delete/{id}', function ($articulo_id, $id) use ($app) {

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
})
->bind('etiquetas_articulo_delete');