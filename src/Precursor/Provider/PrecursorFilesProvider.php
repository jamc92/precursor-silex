<?php
/**
 * Proveedor del Explorador de Archivos
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Provider
 */

namespace Precursor\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface,
    Precursor\File\Explorer;

class PrecursorFilesProvider implements ServiceProviderInterface
{

    /**
     * Registers services on the given app.
     *
     * This method should only be used to configure services and parameters.
     * It should not get services.
     *
     * @param Application $app An Application instance
     */
    public function register(Application $app)
    {
        $app['explorer.default_options'] = array(
            'folders.public' => array(
                dirname(__DIR__) . "/DeveloperFile/"
            ),
            'folders.protected' => array(
                dirname(__DIR__) . "/",
                dirname(__DIR__) . "/File/",
                dirname(__DIR__) . "/Options/",
                dirname(__DIR__) . "/Provider/",
            ),
            'files.public' => array(),
            'files.protected' => array(),
        );

        $app['explorer.options'] = $app->share(function($app) {

        });

        $app['explorer'] = $app->share(function($app) {

            $explorer = new Explorer();
            if (isset($app['explorer.options']) && ( isset($app['explorer.options']['folders.public']) && isset($app['explorer.options']['folders.protected']) )) {
                $explorer->setFolders($app['explorer.options']['folders.public']);
                $explorer->setFoldersProtected($app['explorer.options']['folders.protected']);
            } else {
                $explorer->setFolders($app['explorer.default_options']['folders.public']);
                $explorer->setFoldersProtected($app['explorer.default_options']['folders.protected']);
            }

            return $explorer;
        });

        $app['explorer.files.public'] = $app->share(function() use($app) {
            #return $app['explorer']->listFiles();
        });

        $app['explorer.files.protected'] = $app->share(function() use($app) {
            #return $app['explorer']->listFilesProtected();
        });

    }

    /**
     * Bootstraps the application.
     *
     * This method is called after all services are registered
     * and should be used for "dynamic" configuration (whenever
     * a service must be requested).
     */
    public function boot(Application $app)
    {
        // TODO: Implement boot() method.
    }
    
}