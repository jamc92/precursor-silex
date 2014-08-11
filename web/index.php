<?php

# Autocargador del framework
require_once __DIR__ . '/../vendor/autoload.php';

use Silex\Application;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

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
                return new Precursor\Provider\UserProvider($app['db']);
            }),
        )
    ),
    'security.access_rules' => Precursor\Security\AccessRules::getAccessRules(),
    'security.encoder.digest' => $app->share(function($app) {
        return new MessageDigestPasswordEncoder('sha512');
    })
));
# Proveedor de doctrine para base de datos
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array(
        'db' => \Precursor\Doctrine\Options::getOptions()
    )
));
# Proveedor de archivos del Precursor
$app->register(new \Precursor\Provider\PrecursorFilesProvider());

if ($_SERVER['SERVER_NAME'] == "precursor.esy.es") {
    $app['asset_path'] = 'http://precursor.esy.es/web/resources';
    $app['upload_path'] = 'http://precursor.esy.es/web/resources/uploads';
} else {
    $app['asset_path'] = 'http://localhost/precursor-silex/web/resources';
    $app['upload_path'] = 'http://localhost/precursor-silex/web/resources/uploads';
}

<<<<<<< HEAD
if ($_SERVER['SERVER_NAME'] == "precursor.esy.es") {
    $app['asset_path'] = 'http://precursor.esy.es/web/resources';
    $app['upload_path'] = 'http://precursor.esy.es/web/resources/uploads';
} else {
    $app['asset_path'] = 'http://localhost/precursor-silex/web/resources';
    $app['upload_path'] = 'http://localhost/precursor-silex/web/resources/uploads';
}

=======
>>>>>>> 1.0
$app['upload_dir'] = __DIR__ . "/resources/uploads/";
$app['debug'] = true;

require_once __DIR__ . '/routes/backend/base.php';
require_once __DIR__ . '/routes/frontend/base.php';

$app->run();
