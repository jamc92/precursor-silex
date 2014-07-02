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

$app->match('/admin/articulos_etiquetas', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'id_articulo', 
		'id_etiqueta', 

    );

    $primary_key = "id";
	$rows = array();

    $find_sql = "SELECT * FROM `articulos_etiquetas`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){

		$rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];


    	}
    }

    return $app['twig']->render('backend/articulos_etiquetas/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('articulos_etiquetas_list');



$app->match('/admin/articulos_etiquetas/create', function () use ($app) {
    
    $initial_data = array(
		'id_articulo' => '', 
		'id_etiqueta' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('id_articulo', 'text', array('required' => true));
	$form = $form->add('id_etiqueta', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `articulos_etiquetas` (`id_articulo`, `id_etiqueta`) VALUES (?, ?)";
            $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_etiqueta']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'articulos_etiquetas created!',
                )
            );
            return $app->redirect($app['url_generator']->generate('articulos_etiquetas_list'));

        }
    }

    return $app['twig']->render('backend/articulos_etiquetas/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('articulos_etiquetas_create');



$app->match('/admin/articulos_etiquetas/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `articulos_etiquetas` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('articulos_etiquetas_list'));
    }

    
    $initial_data = array(
		'id_articulo' => $row_sql['id_articulo'], 
		'id_etiqueta' => $row_sql['id_etiqueta'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('id_articulo', 'text', array('required' => true));
	$form = $form->add('id_etiqueta', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `articulos_etiquetas` SET `id_articulo` = ?, `id_etiqueta` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_etiqueta'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'articulos_etiquetas edited!',
                )
            );
            return $app->redirect($app['url_generator']->generate('articulos_etiquetas_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/articulos_etiquetas/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('articulos_etiquetas_edit');



$app->match('/admin/articulos_etiquetas/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `articulos_etiquetas` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `articulos_etiquetas` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'articulos_etiquetas deleted!',
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

    return $app->redirect($app['url_generator']->generate('articulos_etiquetas_list'));

})
->bind('articulos_etiquetas_delete');






