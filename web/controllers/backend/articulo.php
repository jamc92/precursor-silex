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

$app->match('/admin/articulo', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'id_autor', 
		'id_categoria', 
		'titulo', 
		'contenido', 
		'fecha_pub', 
		'creado', 
		'modificado', 
    );

    $primary_key = "id";
	$rows = array();

    $find_sql = "SELECT * FROM `articulo`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++) {
            $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
    	}
    }

    return $app['twig']->render('backend/articulo/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('articulo_list');



$app->match('/admin/articulo/create', function () use ($app) {
    
    // Categorías
    $find_sql = "SELECT * FROM `categoria`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());
    $options_cat = array();

    foreach($rows_sql as $row_key => $row_sql) {
        $options_cat[$row_sql['id']] = $row_sql['nombre'];
    }

    // Etiquetas
    $find_sql = "SELECT * FROM `etiqueta`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());
    $options_etiq = array();

    foreach($rows_sql as $row_key => $row_sql) {
        $options_etiq[$row_sql['id']] = $row_sql['nombre'];
    }

    $initial_data = array(
		'id_autor' => '', 
		'categoria' => '', 
		'titulo' => '', 
		'contenido' => '', 
		'fecha_publicacion' => '', 
		'creado' => '', 
		'modificado' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);


    // El autor del articulo debe ser el logueado
	
	$form = $form->add('categoria', 'choice', array(
            'choices' => $options_cat,
            'required' => false
        ));
	$form = $form->add('titulo', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));
	$form = $form->add('fecha_publicacion', 'text', array('required' => true));
	
    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `articulo` (`id_autor`, `id_categoria`, `titulo`, `contenido`, `fecha_pub`, `creado`, `modificado`) VALUES (?, ?, ?, ?, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array($data['id_autor'], $data['categoria'], $data['titulo'], $data['contenido'], $data['fecha_publicacion']));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡Artículo creado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('articulo_list'));

        }
    }

    return $app['twig']->render('backend/articulo/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('articulo_create');



$app->match('/admin/articulo/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `articulo` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => '¡Etiqueta no encontrada!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('articulo_list'));
    }

    
    $initial_data = array(
		'id_autor' => $row_sql['id_autor'], 
		'id_categoria' => $row_sql['id_categoria'], 
		'titulo' => $row_sql['titulo'], 
		'contenido' => $row_sql['contenido'], 
		'fecha_pub' => $row_sql['fecha_pub'], 
		'creado' => $row_sql['creado'], 
		'modificado' => $row_sql['modificado'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('id_autor', 'text', array('required' => true));
	$form = $form->add('id_categoria', 'text', array('required' => true));
	$form = $form->add('titulo', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));
	$form = $form->add('fecha_pub', 'text', array('required' => true));
	$form = $form->add('creado', 'text', array('required' => true));
	$form = $form->add('modificado', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `articulo` SET `id_autor` = ?, `id_categoria` = ?, `titulo` = ?, `contenido` = ?, `fecha_pub` = ?, `creado` = ?, `modificado` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['id_autor'], $data['id_categoria'], $data['titulo'], $data['contenido'], $data['fecha_pub'], $data['creado'], $data['modificado'], $id));            


            $app['session']->getFlashBag()->add(
                'info',
                array(
                    'message' => '¡Artículo Editado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('articulo_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/articulo/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('articulo_edit');




$app->match('/admin/articulo/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `articulo` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `articulo` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'info',
            array(
                'message' => '¡Artículo Eliminado!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => 'Artículo no encontrado',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('articulo_list'));

})
->bind('articulo_delete');






