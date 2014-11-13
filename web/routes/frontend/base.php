<?php

require_once __DIR__ . '/usuario.php';

// Ruta de la pagina de inicio de El Precursor
$app->match('/', 'Precursor\\Application\\Controller\\Frontend\\Base::index')
    ->bind('home');

$app->match('/noticia/{id}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::ver')
    ->assert('idArticulo', '\d+')
    ->bind('noticia');

$app->match('/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Frontend\\Comentario::verAjax')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_ajax');

$app->match('/comentar', 'Precursor\\Application\\Controller\\Frontend\\Comentario::guardarComentario')
    ->bind('guardar_comentario')
    ->method('POST');

$app->match('/busqueda', 'Precursor\\Application\\Controller\\Frontend\\Noticia::busqueda')
    ->bind('busqueda')
    ->method('GET');

$app->match('/categoria/{idCategoria}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::categoria')
    ->assert('idCateoria', '\d+')
    ->bind('articulos_categoria')
    ->method('GET');

$app->match('/categorias.json', 'Precursor\\Application\\Controller\\Frontend\\Base::categoriasAjax')
    ->bind('categoria_list_ajax')
    ->method('POST');

$app->match('/etiqueta/{idEtiqueta}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::etiqueta')
    ->assert('idEtiqueta', '\d+')
    ->bind('articulos_etiqueta')
    ->method('GET');

$app->match('/suscriptor', 'Precursor\\Application\\Controller\\Frontend\\Suscriptor::registrar')
    ->bind('registrar_suscriptor')
    ->method('POST');

$app->match('/noticia/{idArticulo}/imprimir', 'Precursor\\Application\\Controller\\Frontend\\Noticia::imprimir')
    ->bind('imprimir_noticia');

$app->match('/noticia/{idArticulo}/pdf', 'Precursor\\Application\\Controller\\Frontend\\Noticia::pdf')
    ->bind('noticia_pdf');

# visor de logs
$app->get('/elprecursor.log', function() {
    $log = file_get_contents(__DIR__ . '/../../elprecursor.log');
    return $log;
});
