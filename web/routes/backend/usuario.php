<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/usuario', function () use ($app) {

            $table_columns = array(
                'id',
                'perfil',
                'nombre',
                'correo',
                'alias',
                #'clave', 
                'creado',
                'modificado',
            );

            $primary_key = "id";
            $rows = array();

            $find_sql = "SELECT `usuario`.*, `perfil`.`nombre` as perfil FROM `usuario` INNER JOIN `perfil` ON id_perfil = `perfil`.`id`";
            $rows_sql = $app['db']->fetchAll($find_sql, array());

            foreach ($rows_sql as $row_key => $row_sql) {
                for ($i = 0; $i < count($table_columns); $i++) {

                    $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
                }
            }

            return $app['twig']->render('backend/usuario/list.html.twig', array(
                        "table_columns" => $table_columns,
                        "primary_key" => $primary_key,
                        "rows" => $rows
            ));
        })
        ->bind('usuario_list');



$app->match('/admin/usuario/create', function () use ($app) {

            $find_sql = "SELECT * FROM `perfil`";
            $rows_sql = $app['db']->fetchAll($find_sql, array());

            foreach ($rows_sql as $row_key => $row_sql) {
                $options[$row_sql['id']] = $row_sql['nombre'];
            }

            $initial_data = array(
                'id_perfil' => '',
                'nombre' => '',
                'correo' => '',
                'alias' => '',
                'clave' => '',
                'creado' => '',
                'modificado' => '',
            );

            $form = $app['form.factory']->createBuilder('form', $initial_data);

            $form = $form->add('id_perfil', 'choice', array(
                'choices' => $options,
                'required' => true
            ));
            $form = $form->add('nombre', 'text', array('required' => true));
            $form = $form->add('correo', 'email', array('required' => true));
            $form = $form->add('alias', 'text', array('required' => true));
            $form = $form->add('clave', 'password', array('required' => true));


            $form = $form->getForm();

            if ("POST" == $app['request']->getMethod()) {

                $form->handleRequest($app["request"]);

                if ($form->isValid()) {
                    $data = $form->getData();

                    $update_query = "INSERT INTO `usuario` (`id_perfil`, `nombre`, `correo`, `alias`, `clave`, `creado`, `modificado`) VALUES (?, ?, ?, ?, MD5(?), NOW(), NOW())";
                    $app['db']->executeUpdate($update_query, array($data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $data['clave']));


                    $app['session']->getFlashBag()->add(
                            'success', array(
                        'message' => '¡Usuario creado!',
                            )
                    );
                    return $app->redirect($app['url_generator']->generate('usuario_list'));
                }
            }

            return $app['twig']->render('backend/usuario/create.html.twig', array(
                        "form" => $form->createView()
            ));
        })
        ->bind('usuario_create');



$app->match('/admin/usuario/edit/{id}', function ($id) use ($app) {

            $find_sql = "SELECT * FROM `usuario` WHERE `id` = ?";
            $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

            if (!$row_sql) {
                $app['session']->getFlashBag()->add(
                        'warning', array(
                    'message' => '¡Usuario no encontrado!',
                        )
                );
                return $app->redirect($app['url_generator']->generate('usuario_list'));
            }

            $find_sql = "SELECT * FROM `perfil`";
            $rows_sql = $app['db']->fetchAll($find_sql, array());

            foreach ($rows_sql as $row_key => $row_sql2) {
                $options[$row_sql2['id']] = $row_sql2['nombre'];
            }


            $initial_data = array(
                'nombre' => $row_sql['nombre'],
                'correo' => $row_sql['correo'],
                'alias' => $row_sql['alias'],
            );


            $form = $app['form.factory']->createBuilder('form', $initial_data);


            $form = $form->add('id_perfil', 'choice', array(
                'choices' => $options,
                'data' => $row_sql['id_perfil'],
                'required' => true
            ));
            $form = $form->add('nombre', 'text', array('required' => true));
            $form = $form->add('correo', 'text', array('required' => true));
            $form = $form->add('alias', 'text', array('required' => true));
            $form = $form->add('nueva_clave', 'password', array('required' => false));

            $form = $form->getForm();

            if ("POST" == $app['request']->getMethod()) {

                $form->handleRequest($app["request"]);

                if ($form->isValid()) {
                    $data = $form->getData();

                    if (!empty($data['nueva_clave'])) {
                        $update_query = "UPDATE `usuario` SET `id_perfil` = ?, `nombre` = ?, `correo` = ?, `alias` = ?, `clave` = ? WHERE `id` = ?";
                        $app['db']->executeUpdate($update_query, array($data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $data['nueva_clave'], $id));
                    } else {
                        $update_query = "UPDATE `usuario` SET `id_perfil` = ?, `nombre` = ?, `correo` = ?, `alias` = ? WHERE `id` = ?";
                        $app['db']->executeUpdate($update_query, array($data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $id));
                    }

                    $app['session']->getFlashBag()->add(
                            'info', array(
                        'message' => '¡Usuario editado!',
                            )
                    );
                    return $app->redirect($app['url_generator']->generate('usuario_edit', array("id" => $id)));
                }
            }

            return $app['twig']->render('backend/usuario/edit.html.twig', array(
                        "form" => $form->createView(),
                        "id" => $id
            ));
        })
        ->bind('usuario_edit');



$app->match('/admin/usuario/delete/{id}', function ($id) use ($app) {

            $find_sql = "SELECT * FROM `usuario` WHERE `id` = ?";
            $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

            if ($row_sql) {
                $delete_query = "DELETE FROM `usuario` WHERE `id` = ?";
                $app['db']->executeUpdate($delete_query, array($id));

                $app['session']->getFlashBag()->add(
                        'info', array(
                    'message' => 'Usuario eliminado!',
                        )
                );
            } else {
                $app['session']->getFlashBag()->add(
                        'warning', array(
                    'message' => '¡Usuario no encontrado!',
                        )
                );
            }

            return $app->redirect($app['url_generator']->generate('usuario_list'));
        })
        ->bind('usuario_delete');






