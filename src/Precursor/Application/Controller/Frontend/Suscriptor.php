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

use Precursor\Application\Model\Categoria,
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

                if ($filasAfectadas == 1){
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