<?php
/**
 * Controlador de acciones de logs
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\LogViewer\LogViewer,
    Precursor\Application\Model\Comentario,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response;

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

        return $app['twig']->render('log/list.html.twig', array(
            'clients' => $viewer->getClients(),
            'logs' => $viewer->getLogs($clientSlug),
            'clientSlug' => $clientSlug,
            'logSlug' => $logSlug,
            'log' => $log,
            'logLevels' => Monolog\Logger::getLevels(),
            'minLogLevel' => 0
        ));
    }
    
}