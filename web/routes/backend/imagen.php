<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/imagen', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'nombre', 
		'link',
    );

    $primary_key = "id";
	$rows = array();

    $find_sql = "SELECT * FROM `imagen`";
    $rows_sql = $app['db']->fetchAll($find_sql, array());

    foreach($rows_sql as $row_key => $row_sql){
    	for($i = 0; $i < count($table_columns); $i++){
		    $rows[$row_key][$table_columns[$i]] = $row_sql[$table_columns[$i]];
    	}
    }

    return $app['twig']->render('backend/imagen/list.html.twig', array(
    	"table_columns" => $table_columns,
        "primary_key" => $primary_key,
    	"rows" => $rows
    ));
        
})
->bind('imagen_list');

$app->match('/admin/imagen/create', function () use ($app) {
    
    $initial_data = array(
		'nombre' => '', 
		'link' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$form = $form->add('nombre', 'text', array('required' => true));
	$form = $form->add('link', 'textarea', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `imagen` (`nombre`, `link`, `creado`) VALUES (?, ?, NOW()";
            $app['db']->executeUpdate($update_query, array($data['nombre'], $data['link']));

            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡imagen creado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('imagen_list'));
        }
    }

    return $app['twig']->render('backend/imagen/create.html.twig', array(
        "form" => $form->createView()
    ));
        
})
->bind('imagen_create');

$app->match('/admin/imagen/edit/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `imagen` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => '¡imagen no encontrado!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('imagen_list'));
    }
    
    $initial_data = array(
		'nombre' => $row_sql['nombre'], 
		'link' => $row_sql['link'],
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$form = $form->add('nombre', 'text', array('required' => true));
	$form = $form->add('link', 'textarea', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `imagen` SET `nombre` = ?, `link` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['nombre'], $data['link'], $id));

            $app['session']->getFlashBag()->add(
                'success',
                array(
                    'message' => '¡imagen modificado!',
                )
            );
            return $app->redirect($app['url_generator']->generate('imagen_edit', array("id" => $id)));

        }
    }

    return $app['twig']->render('backend/imagen/edit.html.twig', array(
        "form" => $form->createView(),
        "id" => $id
    ));
        
})
->bind('imagen_edit');

$app->match('/admin/imagen/delete/{id}', function ($id) use ($app) {

    $find_sql = "SELECT * FROM `imagen` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if($row_sql){
        $delete_query = "DELETE FROM `imagen` WHERE `id` = ?";
        $app['db']->executeUpdate($delete_query, array($id));

        $app['session']->getFlashBag()->add(
            'success',
            array(
                'message' => '¡imagen eliminado!',
            )
        );
    }
    else{
        $app['session']->getFlashBag()->add(
            'danger',
            array(
                'message' => '¡imagen no encontrado!',
            )
        );  
    }

    return $app->redirect($app['url_generator']->generate('imagen_list'));

})
->bind('imagen_delete');