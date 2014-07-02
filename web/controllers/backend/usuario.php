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

$app->match('/admin/usuario', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'id_perfil', 
		'nombre', 
		'correo', 
		'alias', 
		'clave', 
		'creado', 
		'modificado', 

    );

    $primary_key = "id";
	$rows = array();

    $find_sql = "SELECT * FROM `usuario`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){

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
    
    $initial_data = array(
		'id' => '', 
		'id_perfil' => '', 
		'nombre' => '', 
		'correo' => '', 
		'alias' => '', 
		'clave' => '', 
		'creado' => '', 
		'modificado' => '', 

    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);



	$form = $form->add('id', 'text', array('required' => true));
	$form = $form->add('id_perfil', 'text', array('required' => true));
	$form = $form->add('nombre', 'text', array('required' => true));
	$form = $form->add('correo', 'text', array('required' => true));
	$form = $form->add('alias', 'text', array('required' => true));
	$form = $form->add('clave', 'password', array('required' => true));
	$form = $form->add('creado', 'text', array('required' => true));
	$form = $form->add('modificado', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `usuario` (`id`, `id_perfil`, `nombre`, `correo`, `alias`, `clave`, `creado`, `modificado`) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array($data['id'], $data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $data['clave']));


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'usuario created!',
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

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => 'Row not found!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('usuario_list'));
    }

    
    $initial_data = array(
		'id' => $row_sql['id'], 
		'id_perfil' => $row_sql['id_perfil'], 
		'nombre' => $row_sql['nombre'], 
		'correo' => $row_sql['correo'], 
		'alias' => $row_sql['alias'], 
		'clave' => $row_sql['clave'], 
		'creado' => $row_sql['creado'], 
		'modificado' => $row_sql['modificado'], 

    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);


	$form = $form->add('id', 'text', array('required' => true));
	$form = $form->add('id_perfil', 'text', array('required' => true));
	$form = $form->add('nombre', 'text', array('required' => true));
	$form = $form->add('correo', 'text', array('required' => true));
	$form = $form->add('alias', 'text', array('required' => true));
	$form = $form->add('clave', 'text', array('required' => true));
	$form = $form->add('creado', 'text', array('required' => true));
	$form = $form->add('modificado', 'text', array('required' => true));


    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `usuario` SET `id` = ?, `id_perfil` = ?, `nombre` = ?, `correo` = ?, `alias` = ?, `clave` = ?, `creado` = ?, `modificado` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['id'], $data['id_perfil'], $data['nombre'], $data['correo'], $data['alias'], $data['clave'], $data['creado'], $data['modificado'], $id));            


            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => 'usuario edited!',
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

    if($row_sql){
        $delete_query = "DELETE FROM `usuario` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => 'usuario deleted!',
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

    return $app->redirect($app['url_generator']->generate('usuario_list'));

})
->bind('usuario_delete');






