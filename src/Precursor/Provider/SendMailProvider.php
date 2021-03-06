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
                $transport = \Swift_SmtpTransport::newInstance($options['host'], $options['port'], $options['security'])
                        ->setUsername($options['username'])
                        ->setPassword($options['password']);
            } catch (\Swift_TransportException $ste) {
                throw $ste;
            }
            return $transport;
        });
        
        if (isset($app['sendmail.transport']) && is_callable($app['sendmail.transport'])) {
            $transport = $app['sendmail.transport'];
        } else {
            $transport = $app['sendmail.default_transport'];
            $app['sendmail.transport'] = $app['sendmail.default_transport'];
        }
        
        if (isset($app['sendmail.mailer']) && is_callable($app['sendmail.mailer'])) {
            $mailer = $app['sendmail.mailer'];
        } else {
            # Default Mailer
            $mailer = $app->share(function() use($transport) {
                try {
                    $mailer = \Swift_Mailer::newInstance($transport);
                } catch (\Swift_SwiftException $sse) {
                    throw $sse;
                }
                return $mailer;
            });
            $app['sendmail.mailer'] = $mailer;
        }
        
    }

}
