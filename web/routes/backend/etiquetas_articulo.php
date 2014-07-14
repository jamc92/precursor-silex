<?php

use Symfony\Component\Validator\Constraints as Assert;

$app->match('/admin/etiquetas_articulo/{articulo_id}', function ($articulo_id) use ($app) {

})
->bind('etiquetas_articulo_list');

$app->match('/admin/etiquetas_articulo/{articulo_id}/create', function ($articulo_id) use ($app) {

    $initial_data = array(
        'id_articulo' => $articulo_id,
        'id_etiqueta' => '',
    );

    $form = $app['form.factory']->createBuilder('form', $initial_data);

    $form = $form->add('id_etiqueta', 'text', array('required' => true));

    $form = $form->getForm();

    if ("POST" == $app['request']->getMethod()) {

        $form->handleRequest($app["request"]);

        if ($form->isValid()) {
            $data = $form->getData();

            $update_query = "INSERT INTO `articulos_etiquetas` (`id_articulo`, `id_etiqueta`) VALUES (?, ?)";
            $app['db']->executeUpdate($update_query, array($data['id_articulo'], $data['id_etiqueta']));


            $app['session']->getFlashBag()->add(
                'success', array(
                    'message' => '¡Etiqueta de Artículo creada!',
                )
            );
            return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list'));
        }
    }

    return $app['twig']->render('backend/etiquetas_articulo/create.html.twig', array(
        "form" => $form->createView()
    ));
})
->bind('etiquetas_articulo_create');
