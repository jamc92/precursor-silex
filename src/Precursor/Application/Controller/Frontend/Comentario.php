<?php
/**
 * Description of Comentario
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Frontend
 */

namespace Precursor\Application\Controller\Frontend;

use Precursor\Application\Model\Comentario as ComentarioModelo,
    Precursor\Application\Model\Usuario,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\JsonResponse;

class Comentario
{

    /**
     * @param Application $app
     * @param int $idArticulo
     *
     * @return JsonResponse
     */
    public function verAjax(Application $app, $idArticulo)
    {
        $comentarioModelo = new ComentarioModelo($app['db']);
        $comentarios = $comentarioModelo->getComentariosArticulo($idArticulo);

        $mesesIngles  = cal_info(0);
        $mesesEspanol = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

        foreach ($comentarios as $index => $comentario) {

            $fecha = date('d-F-Y | h:m:s A', strtotime($comentario['fecha']));
            $fecha = str_replace('-', ' de ', $fecha);
            $fecha = str_replace($mesesIngles['months'], $mesesEspanol, $fecha);
            
            $comentarios[$index]['fecha'] = $fecha;
        }

        return new JsonResponse($comentarios);
    }

    /**
     * @param Request $request
     * @param Application $app
     *
     * @return JsonResponse
     */
    public function guardarComentario(Request $request, Application $app)
    {
        $usuarioLogueado = $app['security']->getToken()->getUser();

        if (is_object($usuarioLogueado)) {
            $usuarioModelo = new Usuario($app['db']);
            $usuario = $usuarioModelo->getUsuarioPorAlias($usuarioLogueado->getUsername());

            unset($usuario['clave']);

            $comentarioModelo = new ComentarioModelo($app['db']);

            $comentario = $request->get('comentario');

            if (isset($comentario['idArticulo']) && isset($comentario['contenido'])) {

                $filasAfectadas = $comentarioModelo->guardar($comentario['idArticulo'], $usuario['id'], $comentario['contenido']);

                if ($filasAfectadas == 1) {
                    $response = array('mensaje' => 'Comentario guardado.');
                } else {
                    $response = array('mensaje' => 'Comentario no guardado.');
                }

                $response['estatus'] = 200;
                $response['usuario'] = $usuario;
            }
        } else {
            $response = array(
                'estatus' => 403,
                'mensaje' => 'Usuario no logueado.'
            );
        }

        return new JsonResponse($response);
    }

}
