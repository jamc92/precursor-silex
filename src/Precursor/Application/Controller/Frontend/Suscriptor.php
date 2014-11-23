<?php
/**
 * Controlador de Suscripor
 *
 * @author     Javier Madrid <javiermadrid19@gmail.com>
 * @author     Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Frontend;

use Precursor\SendMail,
    Precursor\Application\Model\Categoria,
    Precursor\Application\Model\Suscriptor as SuscriptorModel,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse,
    Symfony\Component\HttpFoundation\JsonResponse;



class Suscriptor
{

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function registrar(Request $request, Application $app)
    {
        $correo = $request->get('correo');
        $categorias = array();

        if (!$correo){
            return new JsonResponse(array(
                'mensaje' => "Correo vacío"
            ));
        } else {

            $suscriptorModelo = new SuscriptorModel($app['db']);

            if ($suscriptorModelo->existeCorreo($correo)){
                return new JsonResponse(array(
                    'mensaje' => "Correo existe"
                ));
            } else {

                $filasAfectadas = $suscriptorModelo->guardar($correo, $categorias);

                if ($filasAfectadas == 1) {
                    $sendMail = new SendMail(array(), $app['swiftmailer.transport']);
                    
                    # Asunto para el usuario
                    $asunto = "Registro de suscriptor - El Precursor";
                    
                    # Para el usuario registrado
                    $mensajeUsuario  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
                    $mensajeUsuario .= "<p>Usted se ha registrado exitosamente como suscriptor en El Precursor.</p>";
                    $mensajeUsuario .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\">";
                    $mensajeUsuario .= "<p>Recibiras noticias de nosotros pronto.</p>";
                    $mensajeUsuario .= "</div>";
                    $mensajeUsuario .= '</div>';
                    
                    $resultUsuario = $sendMail->setMessage($asunto, 'info@precursor.esy.es', array($correo), $mensajeUsuario)
                             ->send();
                    
                    return new JsonResponse(array(
                        'mensaje' => "Usuario suscrito"
                    ));
                } else{
                    return new JsonResponse(array(
                        'mensaje' => "Usuario no suscrito"
                    ));
                }
            }
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function categoriasAjax(Request $request, Application $app)
    {
        if ($request->isXmlHttpRequest() && 'POST' == $request->getMethod()) {
            $categoriaModelo = new Categoria($app['db']);
            $categorias = $categoriaModelo->getTodo(array('id', 'nombre'), array(), 'WHERE id > 1');

            return $app['twig']->render('frontend/categorias-ajax.html.twig', array(
                'categorias' => $categorias
            ));
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function guardarCategorias(Request $request, Application $app)
    {
        if ($request->isXmlHttpRequest() && 'POST' == $request->getMethod()) {
            $correo = $request->get('correo');
            $categorias = $request->get('categorias');

            if (empty($categorias)) {
                $categorias = array('Todas' => 1);
            }

            $suscriptorModelo = new SuscriptorModel($app['db']);

            if ($suscriptorModelo->existeCorreo($correo)) {
                $filasAfectadas = $suscriptorModelo->guardar($correo, $categorias);

                if ($filasAfectadas == 1) {
                    $sendMail = new SendMail(array(), $app['swiftmailer.transport']);
                    
                    # Asunto para el usuario
                    $asunto = "Registro de suscriptor - El Precursor";
                    
                    # Administrador
                    $mensajeAdmin  = '<div style="margin:auto;position: relative;background: #FFF;border-top: 2px solid #00C0EF;margin-bottom: 20px;border-radius: 3px;width: 90%;box-shadow: 0px 1px 3px rgba(0, 0, 0, 0.1);padding: 20px 30px">';
                    $mensajeAdmin .= "<p>Se ha registrado un suscriptor nuevo en El Precursor.</p>";
                    $mensajeAdmin .= "<p>Detalles del suscriptor:</p>";
                    $mensajeAdmin .= "<div style=\"background-color: #F0F7FD;margin: 0px 0px 20px;padding: 15px 30px 15px 15px;border-left: 5px solid #D0E3F0;\"><b>Correo:</b> $correo. <b>Categorias:</b> " . implode(', ', array_values($categorias)) . "</div>";
                    $mensajeAdmin .= '</div>';
                    
                    $resultAdmin = $sendMail->setMessage($asunto, 'info@precursor.esy.es', array('info@precursor.esy.es' => 'El Precursor'), $mensajeAdmin)
                             ->send();
                    
                    return new JsonResponse('Se guardo categorías', 200);
                } else {
                    return new JsonResponse('No se guardo', 202);
                }
            } else {
                return new JsonResponse('No existe correo de usuario', 202);
            }
        }
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @param $id
     */
    public function eliminar(Request $request, Application $app, $id)
    {
        $suscriptorModelo = new SuscriptorModel($app['db']);
    }

} 