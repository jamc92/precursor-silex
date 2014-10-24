<?php

$app->match('/api/noticias', 'Precursor\\Application\\Controller\\API\\Noticia::verNoticiasJson')
    ->bind('api_noticias');

$app->match('/api/noticia/{id}', 'Precursor\\Application\\Controller\\Frontend\\Noticia::ver')
    ->assert('idArticulo', '\d+')
    ->bind('api_noticia');

$app->match('/comentarios_articulo/{idArticulo}', 'Precursor\\Application\\Controller\\Frontend\\Comentario::verAjax')
    ->assert('idArticulo', '\d+')
    ->bind('comentarios_articulo_ajax');
