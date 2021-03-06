<?php

/**
 * Controlador de acciones de usuarios
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use OAuth\Common\Storage\Session,
    OAuth\Common\Consumer\Credentials,
    OAuth\ServiceFactory,
    OAuth\Common\Http\Exception\TokenResponseException,
    Precursor\Application\Model\Opcion\Menu,
    Precursor\Application\Model\Usuario as UsuarioModelo,
    Precursor\Options\SocialsCredentials,
    Precursor\SendMail,
    Silex\Application,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
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
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        if ($request->get('code') && $service == 'facebook') {
            try {
                // Callback de facebook para obtener el access token
                $serviceAuth->requestAccessToken($request->get('code'));
            } catch (TokenResponseException $ex) {
                
            }

            // Se adquiere la informacion del usuario
            $usuario = json_decode($serviceAuth->request('/me'), true);
            
            $usuario['service'] = 'facebook';
            
            return $app['twig']->render('frontend/usuario/signup.html.twig', array(
                'menu_items' => $menuItems,
                'usuario'    => $usuario
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
            
            return $app['twig']->render('frontend/usuario/signup.html.twig', array(
                'menu_items' => $menuItems,
                'usuario'    => $usuario
            ));
        } else {
            return "<script> location.href = '{$serviceAuth->getAuthorizationUri()}'; </script>";
        }
        
    }

    /**
     * @param Application $app
     * @param Request $request
     * 
     * @return mixed
     */
    public function login(Application $app, Request $request)
    {
        
        $error_msg = "";
        if ($app['security.last_error']($request) == "Bad credentials") {
            $error_msg = "Usuario o contraseña incorrectos.";
        }
        
        $menuModelo = new Menu($app['db']);
        
        $menuItems = $menuModelo->getItems();
        
        return $app['twig']->render('frontend/usuario/login.html.twig', array(
            'error'         => $error_msg,
            'last_username' => $app['session']->get('_security.last_username'),
            'menu_items'    => $menuItems
        ));
    }
    
    /**
     * @param Application $app
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function checkUser(Application $app, Request $request) {
        if ($request->isXmlHttpRequest() && $request->isMethod("POST")) {
            if (is_array($app['user']) && !empty($app['user'])) {

                $user = $app['user'];
                unset($user['clave']);
                
                if ($user['perfil'] == 'ROLE_USER') {
                    $url = $app['url_generator']->generate('home');
                } else {
                    $url = $app['url_generator']->generate('admin');
                }
                
                return new JsonResponse(array(
                    'mensaje' => 'Usuario logueado',
                    'alias'   => $user,
                    'url'     => $url
                ), 202);
            } else {
                return new JsonResponse(array(
                    'mensaje' => 'Usuario no logueado',
                ), 200);
            }
        }
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

                $filasAfectadas = $usuarioModelo->guardarUsuario("$data[nombre] $data[apellido]", $data['correo'], $data['alias'], $clave);

                if ($filasAfectadas == 1) {
                    # Asunto para administrador y usuario
                    $asunto = "Registro de usuario - El Precursor";
                    
                    # Administrador
                    $mensajeAdmin  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
                    $mensajeAdmin .= "<p>Se ha registrado un usuario nuevo en El Precursor.</p>";
                    $mensajeAdmin .= "<p>Detalles del usuario:</p>";
                    $mensajeAdmin .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\"><b>Nombre:</b> $data[nombre] $data[apellido] <a href=\"mailto:$data[correo]\">$data[correo]</a> </div>";
                    $mensajeAdmin .= '</div>';
                    
                    # Para el usuario registrado
                    $mensajeUsuario  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
                    $mensajeUsuario .= "<p>Usted se ha registrado exitosamente en El Precursor.</p>";
                    $mensajeUsuario .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\">";
                    $mensajeUsuario .= "<p>Confirme su registro haciendo click en el siguiente link <a href=\"#\">Link</a>.</p>";
                    $mensajeUsuario .= "</div>";
                    $mensajeUsuario .= '</div>';
                    
                    $sendMail = new SendMail(array(
                        'host'     => 'mx1.hostinger.es',
                        'port'     => 2525,
                        'security' => null,
                        'username' => 'info@precursor.esy.es',
                        'password' => 'elprecursor'
                    ), $app['swiftmailer.transport']);
                    
                    $resultAdmin = $sendMail->setMessage($asunto, 'info@precursor.esy.es', array('info@precursor.esy.es' => 'El Precursor'), $mensajeAdmin)
                             ->send();
                    
                    $resultUsuario = $sendMail->setMessage($asunto, 'info@precursor.esy.es', array($data['correo'] => $data['nombre']), $mensajeUsuario)
                             ->send();
                                        
                    # Transporte SMTP/Gmail con ssl
                    /*$transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com', 465, 'ssl')
                            ->setUsername("cufmelprecursor@gmail.com")
                            ->setPassword("elprecursor");*/    
                    
                    if ($resultAdmin && $resultUsuario) {
                        return new JsonResponse('El registro fue exitoso. Se ha enviado un mensaje nuevo a su cuenta de correo para confirmar la cuenta.');
                    } else {
                        $usuarioModelo->eliminar($usuarioModelo->id, true);

                        return new JsonResponse('Ocurrió un error al tratar de enviar el mensaje a su cuenta de correo para confirmar la cuenta. <button type="button" class="btn btn-primary" onclick="$(\'form.form-signup\').submit();">Enviar datos nuevamente</button>', 202);
                    }
                } else {
                    return new JsonResponse('Ocurrió un problema en el servidor y no se pudo registrar el usuario. Intente más tarde.', 500);
                }
            }
        } else {
            return new JsonResponse('Methodo no permitido.', 400);
        }
    }
    
    /**
     * @param Request $request
     * @param Application $app
     * 
     * @return Response|JsonResponse
     */
    public function miCuenta(Request $request, Application $app)
    {
        if (is_array($app['user'])) {
            if ($request->isXmlHttpRequest() && $request->isMethod('POST')) {
                $nombre = $request->get('nombre');
                $correo = $request->get('correo');
                $alias  = $request->get('alias');
                $clave  = $request->get('clave');
                
                $usuarioModelo = new UsuarioModelo($app['db']);
                
                if ($clave) {
                    //$filasAfectadas = $usuarioModelo->modificar($app['user']['id'], $app['user']['id_perfil'], $nombre, $correo, $alias, $app['user']['clave']);
                } else {
                    $filasAfectadas = $usuarioModelo->modificar($app['user']['id'], $app['user']['id_perfil'], $nombre, $correo, $alias);
                }
                
                if ($filasAfectadas == 1) {
                    return new JsonResponse('Actualizada mi cuenta', 200);
                } else {
                    return new JsonResponse('Nada que actualizar', 202);
                }
            } elseif ($request->isXmlHttpRequest() && $request->isMethod('GET')) {
                return $app['twig']->render('frontend/usuario/micuenta.html.twig');
            }
        } else {
            return new Response('Forbiden', 403);
        }
    }

}
