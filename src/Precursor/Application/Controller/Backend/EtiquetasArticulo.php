<?php
/**
 * Controlador de Etiquetas de Artículos
 * 
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Backend
 */

namespace Precursor\Application\Controller\Backend;

use Precursor\Application\Model\Etiqueta,
    Precursor\Application\Model\EtiquetasArticulo as EtiquetasArticuloModelo,
    Silex\Application,
    Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\RedirectResponse;

class EtiquetasArticulo
{

    /**
     * @param Request $request
     * @param Application $app
     * @param int $idArticulo
     * 
     * @return mixed
     */
    public function ver(Request $request, Application $app, $idArticulo)
    {
        $etiquetasArticuloModelo = new EtiquetasArticuloModelo($app['db']);
        $etiquetas = $etiquetasArticuloModelo->getEtiquetasArticulo($idArticulo);

        return $app['twig']->render('backend/etiquetas_articulo/list.html.twig', array(
            "idArticulo" => $idArticulo,
            "etiquetas"   => $etiquetas
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param $idArticulo
     * 
     * @return mixed|RedirectResponse
     */
    public function agregar(Request $request, Application $app, $idArticulo)
    {
        // Etiquetas
        $etiquetaModelo = new Etiqueta($app['db']);
        $etiquetas = $etiquetaModelo->getTodo();
        $options_etiq = array();

        foreach($etiquetas as $etiqueta) {
            $options_etiq[$etiqueta['id']] = $etiqueta['nombre'];
        }

        $initial_data = array(
            'id_articulo' => $idArticulo,
            'id_etiqueta' => '',
        );

        $form = $app['form.factory']->createBuilder('form', $initial_data);

        $form = $form->add('id_etiqueta', 'choice', array(
            'choices' => $options_etiq,
            'required' => true
        ));

        $form = $form->getForm();

        if ("POST" == $request->getMethod()) {

            $form->handleRequest($request);

            if ($form->isValid()) {
                $data = $form->getData();

                $etiquetasArticuloModelo = new EtiquetasArticuloModelo($app['db']);
                $filasAfectadas = $etiquetasArticuloModelo->guardar($data['id_articulo'], $data['id_etiqueta']);

                if ($filasAfectadas == 1) {
                    $app['session']->getFlashBag()->add(
                        'success', array(
                            'message' => '¡Etiqueta de Artículo creada!',
                        )
                    );
                } else {
                    $app['session']->getFlashBag()->add(
                        'warning', array(
                            'message' => '¡Etiqueta de Artículo ya está agregada!',
                        )
                    );
                }
                return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list', array('articulo_id' => $idArticulo)));
            }
        }

        return $app['twig']->render('backend/etiquetas_articulo/create.html.twig', array(
            "form" => $form->createView(),
            "idArticulo" => $idArticulo
        ));
    }

    /**
     * @param Request $request
     * @param Application $app
     * @param int $idArticulo
     * @param int $id
     * 
     * @return RedirectResponse
     */
    public function eliminar(Request $request, Application $app, $idArticulo, $id)
    {
        $etiquetasArticuloModelo = new EtiquetasArticuloModelo($app['db']);
        $etiquetaArticulo = $etiquetasArticuloModelo->getPorId($id);

        if (!empty($etiquetaArticulo)) {
            $filasAfectadas = $etiquetasArticuloModelo->eliminar($id);
            
            if ($filasAfectadas == 1) {
                $app['session']->getFlashBag()->add(
                    'info', array(
                        'message' => 'Etiqueta de Artículo eliminada!',
                    )
                );
            }   
        } else {
            $app['session']->getFlashBag()->add(
                'warning', array(
                    'message' => 'Etiqueta de Artículo no encontrada',
                )
            );
        }
        return $app->redirect($app['url_generator']->generate('etiquetas_articulo_list', array("articulo_id" => $idArticulo)));
    }

} 