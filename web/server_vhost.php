<?php

# Autocargador del framework
require_once __DIR__ . '/../vendor/autoload.php';

use Precursor\WidgetExtension,
    Silex\Application,
    Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder,
    Precursor\Application\Model\Usuario;

# Objeto de la aplicacion Silex
$app = new Application();

# Provedor Twig para las vistas
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../web/views',
));
# Extension de Widgets
$app['twig'] = $app->share($app->extend('twig', function($twig, $app) {
    $twig->addExtension(new WidgetExtension());
    return $twig;
}));
# Proveedor de logs
$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.name'    => 'elprecursor',
    'monolog.logfile' => __DIR__ . '/elprecursor.log'
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
# Proveedor para el envío de correos
$app->register(new Silex\Provider\SwiftmailerServiceProvider(), array(
    'swiftmailer.options' => array(
        /*'host' => 'smtp.gmail.com',
        'port' => 465,
        'security' => 'ssl',
        'username' => 'ramon.calle.88@gmail.com',
        'password' => 'ramoncito.1'*/
        'host'     => 'mx1.hostinger.es',
        'port'     => 2525,
        'security' => null,
        'username' => 'info@precursor.esy.es',
        'password' => 'elprecursor'
    )
));
# Proveedor de seguridad de acceso a las url
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls'      => array(
        'login_path' => array(
            'pattern'   => '^/login$',
            'anonymous' => true
        ),
        'default'    => array(
            'pattern'   => '^/.*$',
            'anonymous' => true,
            'form'      => array(
                'login_path' => '/login',
                'check_path' => '/login_check',
            ),
            'logout'    => array(
                'logout_path'        => '/logout',
                'invalidate_session' => true
            ),
            'users'     => $app->share(function ($app) {
                return new Precursor\Provider\UserProvider($app['db']);
            }),
        )
    ),
    'security.access_rules'   => Precursor\Options\AccessRules::getAccessRules(),
    'security.encoder.digest' => $app->share(function ($app) {
        return new MessageDigestPasswordEncoder('sha512');
    })
));
# Proveedor de doctrine para base de datos
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'dbs.options' => array(
        'db' => Precursor\Options\Doctrine::getProductionOptions()
    )
));
# Proveedor de archivos del Precursor
$app->register(new Precursor\Provider\PrecursorFilesProvider(), array(
    'explorer.options' => array(
        'folders.public'    => Precursor\Options\Explorer::getPublicFolders(),
        'folders.protected' => Precursor\Options\Explorer::getProtectedFolders(),
    )
));
# Obtener el usuario en todo lugar
$app->before(function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $token = $app['security']->getToken();
    $app['user'] = null;

    if ($token && !$app['security.trust_resolver']->isAnonymous($token)) {
        $app['user']  = $token->getUser();
        
        $usuarioModel = new Usuario($app['db']);
        
        $usuario = $usuarioModel->getUsuarioPorAlias($app['user']->getUsername());
        
        $app['user'] = $usuario;
    }
});

$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$app['asset_path'] = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/resources";
$app['upload_path'] = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/resources/uploads";
$app['banner_path'] = "http://$_SERVER[SERVER_NAME]:$_SERVER[SERVER_PORT]/resources/img/secciones";
$app['upload_dir'] = __DIR__ . "/resources/uploads/";
$app['debug'] = true;

require_once __DIR__ . '/routes/api/base.php';
require_once __DIR__ . '/routes/backend/base.php';
require_once __DIR__ . '/routes/errors.php';
require_once __DIR__ . '/routes/frontend/base.php';

$app->run();