<?php

/**
 * Description of SendMailProvider
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Provider
 */

namespace Precursor\Provider;

use Silex\Application,
    Silex\ServiceProviderInterface;

class SendMailProvider implements ServiceProviderInterface
{

    public function boot(Application $app) {}

    public function register(Application $app)
    {
        # Default Options
        $app['sendmail.default_options'] = array(
            'host'     => 'localhost',
            'port'     => 25,
            'security' => null,
            'username' => 'user',
            'password' => 'pass'
        );
        
        if (isset($app['sendmail.options'])) {
            $options = array_merge($app['sendmail.default_options'], $app['sendmail.options']);
        } else {
            $options = $app['sendmail.default_options'];
        }
        
        # Default Transport
        $app['sendmail.default_transport'] = $app->share(function() use($options) {
            try {
                $transport = \Swift_SmtpTransport::newInstance($options['host'], $options['port'], $options['security']);
                $transport->setUsername($options['username'])->setPassword($options['password']);
            } catch (\Swift_TransportException $ste) {
                throw $ste;
            }
            return $transport;
        });
        
        if (isset($app['sendmail.transport']) && is_callable($app['sendmail.transport'])) {
            $transport = $app['sendmail.transport'];
        } else {
            $transport = $app['sendmail.default_transport'];
        }
        
        # Default Message
        $app['sendmail.default_message'] = $app->share(function($asunto, $from, array $to = array(), $message) {
            try {
                $message = \Swift_Message::newInstance($asunto)
                        ->setFrom($from)
                        ->setTo($to)
                        ->setBody($message, 'text/html');
            } catch (\Swift_SwiftException $sse) {
                throw $sse;
            }
            return $message;
        });
        
        if (isset($app['sendmail.message']) && is_callable($app['sendmail.message'])) {
            $message = $app['sendmail.message'];
        } else {
            $message = $app['sendmail.default_message'];
        }
        
        # Default Mailer
        $app['sendmail.default_mailer'] = $app->share(function() use($transport) {
            try {
                $mailer = \Swift_Mailer::newInstance($transport);
            } catch (\Swift_SwiftException $sse) {
                throw $sse;
            }
            return $mailer;
        });
        
        if ($app['sendmail.mailer'] && is_callable($app['sendmail.mailer'])) {
            $mailer = $app['sendmail.mailer'];
        } else {
            $mailer = $app['sendmail.default_mailer'];
        }
        
        # Default send
        $app['sendmail.default_send'] = $app->share(function() use($mailer, $message) {
            return $mailer->send($message);
        });
        
    }

}
