<?php
/**
 * Description of PrecursorFilesProvider.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Provider
 */

namespace Precursor\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface,
    Precursor\File\Explorer;


class PrecursorFilesProvider implements ServiceProviderInterface {

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
                dirname(__DIR__) . "/Doctrine/",
                dirname(__DIR__) . "/File/",
                dirname(__DIR__) . "/Provider/",
                dirname(__DIR__) . "/Security/",
            ),
            'files.public' => array(),
            'files.protected' => array(),
        );

        $app['explorer'] = $app->share(function($app) {

            $explorer = new Explorer();
            $explorer->setFolders($app['explorer.default_options']['folders.public']);
            $explorer->setFoldersProtected($app['explorer.default_options']['folders.protected']);

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