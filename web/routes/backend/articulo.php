<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/articulo', function () use ($app) {
    
	$table_columns = array(
		'id', 
		'id_autor',
		'id_categoria', 
		'titulo', 
		'fecha_pub',
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

    // El autor del articulo debe ser el logueado
    #$id_autor = Asignar el usuario logueado
    $id_autor = 1;

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
		'id_autor' => $id_autor,
        'imagen' => '',
		'categoria' => '',
		'titulo' => '',
        'descripcion' => '',
		'contenido' => '',
		'fecha_publicacion' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

	$form = $form->add('categoria', 'choice', array(
            'choices' => $options_cat,
            'required' => false
    ));
    $form = $form->add('etiquetas', 'choice', array(
        'choices' => $options_etiq,
        'required' => false,
        "multiple" => true
    ));
    $form = $form->add('imagen', 'url', array('required' => true));
	$form = $form->add('titulo', 'text', array('required' => true));
    $form = $form->add('descripcion', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `articulo` (`id_autor`, `id_categoria`, `imagen`, `titulo`, `descripcion`, `contenido`, `fecha_pub`, `creado`) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
            $app['db']->executeUpdate($update_query, array($data['id_autor'], $data['categoria'], $data['imagen'], $data['titulo'], $data['descripcion'], $data['contenido']));

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
        "form" => $form->createView(),
    ));

})
->bind('articulo_create');

$app->match('/admin/articulo/edit/{id}', function ($id) use ($app) {

    // El autor del articulo debe ser el logueado
    #$id_autor = Asignar el usuario logueado
    $id_autor = 1;

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

    $find_sql = "SELECT * FROM `articulo` WHERE `id` = ?";
    $row_sql = $app['db']->fetchAssoc($find_sql, array($id));

    if(!$row_sql){
        $app['session']->getFlashBag()->add(
            'warning',
            array(
                'message' => '¡Artículo no encontrado!',
            )
        );        
        return $app->redirect($app['url_generator']->generate('articulo_list'));
    }

    
    $initial_data = array(
		'id_autor' => $id_autor,
		'categoria' => $row_sql['id_categoria'],
        'imagen' => $row_sql['imagen'],
		'titulo' => $row_sql['titulo'],
        'descripcion' => $row_sql['descripcion'],
        'contenido' => $row_sql['contenido'],
		'fecha_pub' => $row_sql['fecha_pub'], 
    );


    $form = $app['form.factory']->createBuilder('form', $initial_data);

    $form = $form->add('categoria', 'choice', array(
        'choices' => $options_cat,
        'required' => false
    ));
    $form = $form->add('etiquetas', 'choice', array(
        'choices' => $options_etiq,
        'required' => false,
        "multiple" => true
    ));
    $form = $form->add('imagen', 'url', array('required' => true));
	$form = $form->add('titulo', 'text', array('required' => true));
    $form = $form->add('descripcion', 'text', array('required' => true));
	$form = $form->add('contenido', 'textarea', array('required' => true));

    $form = $form->getForm();

    if("POST" == $app['request']->getMethod()){

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "UPDATE `articulo` SET `id_autor` = ?, `id_categoria` = ?, `imagen` = ?, `titulo` = ?, `descripcion` = ?, `contenido` = ? WHERE `id` = ?";
            $app['db']->executeUpdate($update_query, array($data['id_autor'], $data['categoria'], $data['imagen'],$data['titulo'], $data['descripcion'], $data['contenido'], $id));


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

