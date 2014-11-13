<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Auditoria
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor\Application\Controller\Backend;

use Doctrine\DBAL\Driver\Connection,
    Precursor\Application\Model\Auditoria as AuditoriaModelo,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request;

class Auditoria
{

    /**
     * @var Auditoria
     */
    static $_instance;

    /**
     * @return Auditoria
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    /**
     * 
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app)
    {
        $auditoriaModelo = new AuditoriaModelo($app['db']);
        
        if ('GET' === $request->getMethod()) {
            $auditorias = $auditoriaModelo->getTodo();
            
            return $app['twig']->render('backend/auditoria/list.html.twig', array(
                'auditorias' => $auditorias
            ));
        } 
        if ('POST' === $request->getMethod() && $request->isXmlHttpRequest()) {
            $id = $request->get('id');
            
            $auditoria = $auditoriaModelo->getTodo(array('*'), $id);
            
            return $app['twig']->render('backend/auditoria/detail.html.twig', array(
                'auditoria' => $auditoria[0]
            ));
        }
    }

    /**
     * 
     * @param Connection $db
     * @param string $tipoTransaccion
     * @param string $modelo
     * @param string $descripcion
     * @param string $resultadoTransaccion
     * @param string $traza
     * 
     * @return int
     */
    public function guardar(Connection $db, $tipoTransaccion, $modelo, $descripcion, $resultadoTransaccion, $traza = '')
    {
        $ipMaquina = $_SERVER['REMOTE_ADDR'];
        $fechaHora = date('Y-m-d H:m:s');
        
        $auditoriaModelo = new AuditoriaModelo($db);
        
        if (isset($app['user']['id'])) {
            $idUsuario = $app['user']['id'];
        } else {
            $idUsuario = 1;
        }
        
        return $auditoriaModelo->guardar($idUsuario, $traza, $descripcion, $ipMaquina, $modelo, $resultadoTransaccion, $tipoTransaccion, $fechaHora);
    }

}
