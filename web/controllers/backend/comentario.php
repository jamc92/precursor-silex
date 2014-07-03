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

$app->match('/admin/comentario', function () use ($app) {
    
	$table_columns = array(
		'id_articulo', 
		'id_autor', 
		'asunto', 
		'contenido', 
		'fecha', 

    );

    $primary_key = "id_articulo";
	$rows = array();

    $find_sql = "SELECT * FROM `comentario`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){

		$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];


    	}
    }

    return $app['twig']->render('backend/comentario/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('comentario_list');



$app->match('/admin/comentario/create', function () use ($app) {
    
    $initial_data = array(
		'id_articulo' => '', 
		'id_autor' => '', 
		'asunto' => '', 
		'contenido' => '', 
		'fecha' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('id_articulo', 'text', array('required' => true));
	$form = $form->add('id_autor', 'text', array('required' => true));
	$form = $form->add('asunto', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));
	$form = $form->add('fecha', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `comentario` (`id_articulo`, `id_autor`, `asunto`, `contenido`, `fecha`) VALUES (?, ?, ?, ?, ?)";
            $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_autor'], $data['asunto'], $data['contenido'], $data['fecha']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡Comentario creado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('comentario_list'));

        }
    }

    return $app['twig']->render('backend/comentario/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('comentario_create');



$app->match('/admin/comentario/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `comentario` WHERE `id_articulo` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => 'Comentario no encontrado',
            )
        );        
        return $app->redirect($app['url_generator']->generate('comentario_list'));
    }

    
    $initial_data = array(
		'id_articulo' => $row_sql['id_articulo'], 
		'id_autor' => $row_sql['id_autor'], 
		'asunto' => $row_sql['asunto'], 
		'contenido' => $row_sql['contenido'], 
		'fecha' => $row_sql['fecha'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('id_articulo', 'text', array('required' => true));
	$form = $form->add('id_autor', 'text', array('required' => true));
	$form = $form->add('asunto', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));
	$form = $form->add('fecha', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `comentario` SET `id_articulo` = ?, `id_autor` = ?, `asunto` = ?, `contenido` = ?, `fecha` = ? WHERE `id_articulo` = ?";
            $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_autor'], $data['asunto'], $data['contenido'], $data['fecha'], $id));            


            $app['session']->getFlashBag()->add(
                'info',
                array(
                    'message' => '¡Comentario editado',
                )
            );
            return $app->redirect($app['url_generator']->generate('comentario_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/comentario/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('comentario_edit');



$app->match('/admin/comentario/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `comentario` WHERE `id_articulo` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `comentario` WHERE `id_articulo` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'info',
            array(
                'message' => '¡Comentario eliminado!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => '¡Comentario no encontrado!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('comentario_list'));

})
->bind('comentario_delete');






