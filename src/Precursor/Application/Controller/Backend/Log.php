<?php
/**
 * Controlador de acciones de logs
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Monolog\Logger,
    Precursor\LogViewer\LogViewer,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request;

class Log
{

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return mixed
     */
    public function index(Request $request, Application $app)
    {
        $viewer = new LogViewer();
        $log = $viewer->getLog('elprecursor', 'log');
        
        if($log === false) {
            return $app->redirect($app['url_generator']->generate('client', array('clientSlug' => $clientSlug)));
        }

        return $app['twig']->render('backend/log/list.html.twig', array(
            'clients' => $viewer->getClients(),
            'logs' => $viewer->getLogs('elprecursor'),
            'clientSlug' => $clientSlug,
            'logSlug' => 'log',
            'log' => $log,
            'logLevels' => Logger::getLevels(),
            'minLogLevel' => 0
        ));
    }
    
}