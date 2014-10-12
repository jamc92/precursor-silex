<?php

/**
 * Controlador de acciones de usuarios
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use OAuth\OAuth2\Service\Facebook,
    OAuth\OAuth2\Service\Google,
    OAuth\Common\Storage\Session,
    OAuth\Common\Consumer\Credentials,
    OAuth\ServiceFactory,
    OAuth\Common\Http\Exception\TokenResponseException,
    Precursor\Application\Model\Comentario,
    Precursor\Application\Model\Usuario as UsuarioModelo,
    Precursor\Options\SocialsCredentials,
    Silex\Application,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\Routing\Generator\UrlGenerator,
    Symfony\Component\Security\Core\User\User;

class Usuario
{
    
    /**
     * @param Application $app
     * @param Request $request
     * @param string $service
     * 
     * @return string
     */
    public function auth(Application $app, Request $request, $service)
    {
        // Construir los servicios
        $serviceFactory = new ServiceFactory();
        
        // Almacenamiento en session
        $storage = new Session();
        
        // Obtener credenciales
        $servicesCredentials = SocialsCredentials::getArrayCredentials();
        
        $url = $app['url_generator']->generate('auth_service', array('service' => $service), UrlGenerator::ABSOLUTE_URL);
        
        if ($service == 'facebook') {
            // Credenciales de facebook
            $facebookCredentials = new Credentials(
                $servicesCredentials['facebook']['key'],
                $servicesCredentials['facebook']['secret'],
                $url
            );
            
            // Servicio de auth de facebook
            $serviceAuth = $serviceFactory->createService('facebook', $facebookCredentials, $storage, array());
            
        } else if ($service == 'google') {
            // Credenciales de google
            $googleCredentials = new Credentials(
                $servicesCredentials['google']['key'],
                $servicesCredentials['google']['secret'],
                $url
            );
            
            // Servicio de auth de google
            $serviceAuth = $serviceFactory->createService('google', $googleCredentials, $storage, array('userinfo_email', 'userinfo_profile'));
        }
        
        if ($request->get('code') && $service == 'facebook') {
            try {
                // Callback de facebook para obtener el access token
                $serviceAuth->requestAccessToken($request->get('code'));
            } catch (TokenResponseException $ex) {
                
            }

            // Se adquiere la informacion del usuario
            $usuario = json_decode($serviceAuth->request('/me'), true);
            
            $usuario['service'] = 'facebook';
            
            return $app['twig']->render('frontend/signup.html.twig', array(
                'usuario' => $usuario
            ));
        } else if ($request->get('code') && $service == 'google') {
            
            try {
                // Callback de google para obtener el access token
                $serviceAuth->requestAccessToken($request->get('code'));
            } catch (TokenResponseException $ex) {
                
            }
        
            // Se adquiere la informacion del usuario
            $usuario = json_decode($serviceAuth->request('https://www.googleapis.com/oauth2/v1/userinfo'), true);
            
            $usuario['service'] = 'google';
            
            return $app['twig']->render('frontend/signup.html.twig', array(
                'usuario' => $usuario
            ));
        } else {
            return "<script> location.href = '{$serviceAuth->getAuthorizationUri()}'; </script>";
        }
        
    }

    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return mixed
     */
    public function login(Application $app, Request $request)
    {
        
        $error_msg = "";
        if ($app['security.last_error']($request) == "Bad credentials") {
            $error_msg = "Usuario o contraseña incorrectos.";
        }
        
        return $app['twig']->render('frontend/login.html.twig', array(
            'error' => $error_msg,
            'last_username' => $app['session']->get('_security.last_username'),
        ));
    }
    
    /**
     * Función para registrarse como usuario
     * @param Application $app
     * @param Request $request
     * 
     * @return mixed
     */
    public function signup(Application $app, Request $request)
    {
        if ("POST" == $request->getMethod()) {
            
            // Se adquiere el encoder
            $encoder = $app['security.encoder.digest'];
            
            $data = $request->get('Usuario');

            $usuarioModelo  = new UsuarioModelo($app['db']);

            if ($usuarioModelo->existe(null, $data['correo'])) {
                return new JsonResponse(array(
                    'mensaje' => 'Correo electrónico ya existe.',
                    'campo'   => 'alias'
                ), 400);
            } else if ($usuarioModelo->existe($data['alias'])) {
                return new JsonResponse(array(
                    'mensaje' => 'Alias no disponible.',
                    'campo'   => 'alias'
                ), 400);
            } else {
                $user = new User($data['correo'], '');

                // codificar la clave
                $clave = $encoder->encodePassword($data['clave'], $user->getSalt());

                $filasAfectadas = $usuarioModelo->guardarUsuario($data['nombre'], $data['correo'], $data['alias'], $clave);

                if ($filasAfectadas == 1) {
                    return new JsonResponse('El registro fue exitoso.');
                } else {
                    return new JsonResponse('Ocurrió un problema en el servidor y no se pudo registrar el usuario. Intente más tarde.', 500);
                }
            }
        } else {
            return new JsonResponse('Methodo no permitido.', 400);
        }
    }
    

}
