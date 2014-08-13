<?php

use Precursor\File\Upload;

$app->match('/admin/archivo', function () use ($app) {

    $archivo = $app['request']->get('archivo');

    if (!empty($archivo)) {

        $file    = fopen($archivo, 'r');
        $content = fread($file, 8192);
        fclose($file);

        return $app['twig']->render('backend/archivo/content.html.twig', array(
            'content' => $content
        ));
    } else {
        $explorer = $app['explorer'];

        $files          = $explorer->getFiles();
        $filesProtected = $explorer->getFilesProtected();

        return $app['twig']->render('backend/archivo/list.html.twig', array(
            'rows'           => $files,
            'rows_protected' => $filesProtected
        ));
    }

})
->bind('archivo_list');

$app->match('/admin/archivo/syntax', function () use ($app) {
    $archivo = $app['request']->get('archivo');

    if (!empty($archivo)) {
        
        if (function_exists('shell_exec'))
            $result = shell_exec("php -l $archivo");
        else 
            $result = 'Función deshabilitada.';

        return $result;

    } else {
        return '';
    }

})
->bind('archivo_syntax');

$app->match('/admin/archivo/create', function () use ($app) {

    if("POST" == $app['request']->getMethod()){

        $upload = new Upload('\\Precursor\\File\\Upload\\Php', array('ignore_uploads' => true));

        $result = $upload->file()->upload($_FILES['php']);

        die(json_encode(array('status' => $result['status'])));

    }

    $initial_data = array(
        'contenido' => '',
        'nombre'    => ''
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

    $form = $form->add('contenido', 'textarea', array());
    $form = $form->add('nombre', 'text', array('required' => true));

    $form = $form->getForm();

    if ('PUT' == $app['request']->getMethod()) {
        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            print_r($data);
            die;
            return '';
        }

    }

    return $app['twig']->render('backend/archivo/create.html.twig', array(
        'form' => $form->createView()
    ));

})
->bind('archivo_create')
->method("GET|POST|PUT");

$app->match('/admin/archivo/edit/{nombre}', function ($nombre) use ($app) {

    $direccion = $app['request']->get('direccion');

    if (!empty($direccion)) {

    } else {
        return $app->redirect($app['url_generator']->generate('archivo_list'));
    }

})
->bind('archivo_edit');

$app->match('/admin/archivo/delete/{nombre}', function ($nombre) use ($app) {

})
->bind('archivo_delete');

$app->match('/admin/archivo/protected', function () use ($app) {

    $archivo = $app['request']->get('archivo');

    if (!empty($archivo)) {

        $file    = fopen($archivo, 'r');
        $content = fread($file, 8192);
        fclose($file);

        return $app['twig']->render('backend/archivo/content.html.twig', array(
            'content' => $content
        ));
    } else {
        $explorer = $app['explorer'];

        $filesProtected = $explorer->getFilesProtected();

        return $app['twig']->render('backend/archivo/list.html.twig', array(
            'rows_protected' => $filesProtected
        ));
    }

})
->bind('archivo_protected_list');

$app->match('/admin/archivo/protected/edit/{nombre}', function ($nombre) use ($app) {

    $direccion = $app['request']->get('direccion');

    if (!empty($direccion)) {

    } else {
        return $app->redirect($app['url_generator']->generate('archivo_list'));
    }

})
->bind('archivo_protected_edit');

$app->match('/admin/archivo/protected/delete/{nombre}', function ($nombre) use ($app) {

})
->bind('archivo_protected_delete');