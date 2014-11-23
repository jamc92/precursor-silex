<?php

/**
 * Clase para enviar correos con Swift Mailer
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace Precursor;

class SendMail
{
    
    /**
     * Objeto de Swift Mailer
     * 
     * @var \Swift_Mailer
     */
    protected $_mailer;
    
    /**
     * Transporte para el Swift Mailer
     * 
     * @var \Swift_SmtpTransport|\Swift_Transport_EsmtpTransport
     */
    protected $_transport;
    
    /**
     * Objeto de mensaje de Swift Mailer
     * 
     * @var \Swift_Mime_Message
     */
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
        return $this;
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
        return $this;
    }
    
    /**
     * Configura el objeto mensaje
     * 
     * @param string $subject
     * @param string $from
     * @param array $to
     * @param string $bodyHtml
     * @param array $cc
     * @param array $bcc
     */
    public function setMessage($subject, $from, array $to = array(), $bodyHtml, array $cc = array(), array $bcc = array())
    {
        $this->_message = \Swift_Message::newInstance($subject)
                ->setFrom($from)
                ->setTo($to)
                ->setCc($cc)
                ->setBcc($bcc)
                ->setBody($bodyHtml, 'text/html');
        return $this;
    }
    
    /**
     * Obtener el objeto del mensaje
     * 
     * @return \Swift_Mime_Message
     */
    public function getMessage()
    {
        return $this->_message;
    }
    
    /**
     * Funcion de enviar el correo
     * 
     * @param \Swift_Mime_Message $message
     * 
     * @return boolean
     */
    public function send($message = null)
    {
        $result = (is_object($message) && get_class($message) == '\\Swift_Mime_Message')
                ? $this->_mailer->send($message)
                : $this->_mailer->send($this->_message);
        return ($result > 0) ? true : false;
    }
}
