<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EnvioCorreo
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor;

class EnvioCorreo
{
    
    protected $_mailer;
    
    protected $_transport;
    
    protected $_message;

    /**
     * @param array $options
     * @param $transport
     */
    public function __construct(array $options = array(), $transport = null)
    {
        if (!is_null($transport) && (get_class($transport) == 'Swift_SmtpTransport' || get_class($transport) == 'Swift_Transport_EsmtpTransport')) {
            $this->_transport = $transport;
        } else {
            $this->setTransport($options);
        }
        
        $this->setMailer();
    }
    
    /**
     * Configuracion del transporte de SMTP
     * 
     * @param array $options
     * 
     * @throws \Swift_TransportException
     */
    public function setTransport(array $options = array())
    {
        $defaultOptions = array(
            'host'     => 'localhost',
            'port'     => 25,
            'security' => null,
            'username' => 'user',
            'password' => 'pass'
        );
        
        $options = array_merge($defaultOptions, $options);
        
        try {
            $transport = \Swift_SmtpTransport::newInstance($options['host'], $options['port'], $options['security'])
                    ->setUsername($options['username'])
                    ->setPassword($options['password']);
        } catch (\Swift_TransportException $ste) {
            throw $ste;
        }
    }
    
    /**
     * Configura el objeto SwiftMailer
     * 
     * @throws \Swift_SwiftException
     */
    public function setMailer()
    {
        try {
            $this->_mailer = \Swift_Mailer::newInstance($this->_transport);
        } catch (\Swift_SwiftException $sse) {
            throw $sse;
        }
    }
}
