<?php

/*
 * This file is part of the CRUD Admin Generator project.
 *
 * Author: Jon Segador <jonseg@gmail.com>
 * Web: http://crud-admin-generator.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../src/app.php';

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/categoria', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'categoria', 
		'nombre', 
		'creado', 
		'modificado',
    );

    $primary_key = "id";
	$rows = array();

    $find_sql = "SELECT `categoria`.*, c2.nombre as categoria FROM `categoria` INNER JOIN `categoria` c2 ON `categoria`.id_categoria = c2.id";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){

		$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];


    	}
    }

    return $app['twig']->render('backend/categoria/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('categoria_list');



$app->match('/admin/categoria/create', function () use ($app) {

    $find_sql = "SELECT * FROM `categoria`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());
    $options = array();

    foreach($rows_sql as $row_key => $row_sql) {
        $options[$row_sql['id']] = $row_sql['nombre'];
    }

    $initial_data = array(
        'categoria_superior' => '', 
        'nombre' => '', 
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

    $form = $form->add('categoria_superior', 'choice', array(
            'choices' => $options,
            'required' => false
        ));

	$form = $form->add('nombre', 'text', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `categoria` (`id_categoria`, `nombre`, `creado`, `modificado`) VALUES (?, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array($data['categoria_superior'], $data['nombre']));


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡Categoría creada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('categoria_list'));

        }
    }

    return $app['twig']->render('backend/categoria/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('categoria_create');



$app->match('/admin/categoria/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `categoria` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => '¡Categoría no encontrada!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('categoria_list'));
    }

    $find_sql = "SELECT * FROM `categoria`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql2) {
        if ($row_sql['id_categoria'] != $row_sql2['id_categoria']) {
            $options[$row_sql2['id']] = $row_sql2['nombre'];
        }
    }

    
    $initial_data = array(
		'nombre' => $row_sql['nombre'],
    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$form = $form->add('categoria_superior', 'choice', array(
            'choices' => $options,
            'data' => $row_sql['id_categoria'],
            'required' => false
        ));
	$form = $form->add('nombre', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `categoria` SET `id_categoria` = ?, `nombre` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['categoria_superior'], $data['nombre'], $id));            


            $app['session']->getFlashBag()->add(
                'info',
                array(
                    'message' => 'Categoría editada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('categoria_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/categoria/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('categoria_edit');



$app->match('/admin/categoria/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `categoria` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `categoria` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'info',
            array(
                'message' => '¡Categoría eliminada!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => 'Categoría no encontrada',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('categoria_list'));

})
->bind('categoria_delete');






