<?php

require_once __DIR__ . '/archivo.php';
require_once __DIR__.'/articulo.php';
require_once __DIR__.'/categoria.php';
require_once __DIR__.'/comentario.php';
require_once __DIR__.'/comentarios_articulo.php';
require_once __DIR__.'/etiqueta.php';
require_once __DIR__.'/etiquetas_articulo.php';
require_once __DIR__.'/imagen.php';
require_once __DIR__.'/opcion.php';
require_once __DIR__.'/perfil.php';
require_once __DIR__.'/usuario.php';

$app->match('/admin', 'Precursor\\Application\\Controller\\Backend\\Base::index')->bind('admin');

$app->match('/login', 'Precursor\\Application\\Controller\\Backend\\Base::login')->bind('login');

$app->match('/checkUser', 'Precursor\\Application\\Controller\\Backend\\Base::checkUser')->bind('checkUser');