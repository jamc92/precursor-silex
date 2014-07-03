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

$app->match('/admin/perfil', function () use ($app) {
    
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

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){

		$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];


    	}
    }

    return $app['twig']->render('backend/perfil/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('perfil_list');



$app->match('/admin/perfil/create', function () use ($app) {
    
    $initial_data = array(
		'id' => '', 
		'nombre' => '', 
		'creado' => '', 
		'modificado' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$form = $form->add('nombre', 'text', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `perfil` (`id`, `nombre`, `creado`, `modificado`) VALUES (null, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array( $data['nombre']));


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'perfil created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('perfil_list'));

        }
    }

    return $app['twig']->render('backend/perfil/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('perfil_create');



$app->match('/admin/perfil/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `perfil` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
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

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `perfil` SET `nombre` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array( $data['nombre'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'perfil edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('perfil_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/perfil/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('perfil_edit');



$app->match('/admin/perfil/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `perfil` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `perfil` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'perfil deleted!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('perfil_list'));

})
->bind('perfil_delete');






