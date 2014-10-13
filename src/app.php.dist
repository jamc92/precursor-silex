<?php

# Autocargador del framework
require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application,
    Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

# Objeto de la aplicacion Silex
$app = new Application();

# Provedor Twig para las vistas
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../web/views',
));
# Proveedor de Formularios
$app->register(new Silex\Provider\FormServiceProvider());
# Proveedor de Traducciones
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
# Provedor para validaciones
$app->register(new Silex\Provider\ValidatorServiceProvider());
# Proveedor para las urls
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
# Proveedor para el uso de variables de sesion
$app->register(new Silex\Provider\SessionServiceProvider());
# Proveedor de seguridad de acceso a las url
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls' => array(
        'login_path' => array(
            'pattern' => '^/login$',
            'anonymous' => true
        ),
        'default' => array(
            'pattern' => '^/.*$',
            'anonymous' => true,
            'form' => array(
                'login_path' => '/login',
                'check_path' => '/login_check',
            ),
            'logout' => array(
                'logout_path' => '/logout',
                'invalidate_session' => false
            ),
            'users' => $app->share(function($app) {
                return new Precursor\UserProvider($app['db']);
            }),
        )
    ),
    'security.access_rules' => array(
        array('^/login$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
        array('^/admin/perfil', 'ROLE_SUPER_ADMIN'),
        array('^/admin/usuario', 'ROLE_SUPER_ADMIN'),
        array('^/admin', array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN')),
        array('^/admin', 'ROLE_USER')
    ),
    'security.encoder.digest' => $app->share(function($app) {
        return new MessageDigestPasswordEncoder('sha512');
    })
));
# Proveedor de doctrine para base de datos
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array(
        'db' => array(
            'driver' => 'pdo_mysql',
            'dbname' => 'precursorbd',
            'host' => 'localhost',
            'user' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ),
    )
));

$app['debug'] = true;

return $app;