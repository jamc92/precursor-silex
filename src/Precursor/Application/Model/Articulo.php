<?php
/**
 * Description of Articulo.php.
 *
 * @author     Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Articulo extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'articulo');
    }

    /**
     * @param int $id Id del artículo
     *
     * @return array
     */
    public function getArticuloYEtiquetas($id)
    {
        $articulo = $this->getPorId($id);

        if (!empty($articulo)) {

            $etiquetasArticuloModel = new EtiquetasArticulo($this->_db);
            $fields = array('etiqueta.id', 'etiqueta.nombre as etiqueta');
            $join = array('etiqueta', 'id_etiqueta', 'etiqueta.id', '=');
            $etiquetas = $etiquetasArticuloModel->_selectFields($fields, $join, 'WHERE id_articulo = ?', array($id));

            foreach ($etiquetas as $etiqueta) {
                $articulo['etiquetas'][$etiqueta['id']] = $etiqueta['etiqueta'];
            }

        }

        return $articulo;
    }

    /**
     * @param int $idAutor        Id del usuario actual logueado
     * @param int $idCategoria    Id de la categoría del artículo
     * @param string $imagen      Url de la imagen del artículo
     * @param string $titulo      Título del artículo
     * @param string $descripcion Descripción del artículo
     * @param string $contenido   Contenido HTML del artículo
     * @param array $etiquetas    Arreglo de las estiquetas seleccionadas
     *
     * @return array|int Filas afectadas
     */
    public function guardar($idAutor, $idCategoria, $imagen, $titulo, $descripcion, $contenido, array $etiquetas = array())
    {
        $data = array(
            'id_autor'     => $idAutor,
            'id_categoria' => $idCategoria,
            'imagen'       => $imagen,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'contenido'    => $contenido,
            'fecha_pub'    => date('Y-m-d H:m:s'),
            'creado'       => date('Y-m-d H:m:s')
        );

        $filasAfectadas = $this->_insert($data);

        if ($filasAfectadas == 1 && !empty($etiquetas)) {
            $articulo = $this->getTodo(array('MAX(id) as id'));

            $etiquetasArticuloModel = new EtiquetasArticulo($this->_db);

            $etiquetaAgregadas = array();

            foreach ($etiquetas as $etiqueta) {
                $filasAfectadasEtiqueta = $etiquetasArticuloModel->guardar($articulo[0]['id'], $etiqueta);

                // Agregar la etiqueta si se inserto en la tabla, para el mensaje del usuario
                if ($filasAfectadasEtiqueta == 1) {
                    $etiquetaAgregadas[] = $etiqueta;
                }
            }

            return array(
                'articulo'  => $filasAfectadas,
                'etiquetas' => $etiquetaAgregadas
            );
        } else {
            return $filasAfectadas;
        }
    }

    /**
     * @param int $id             Id del artículo
     * @param int $idAutor        Id del usuario actual logueado
     * @param int $idCategoria    Id de la categoría del artículo
     * @param string $imagen      Url de la imagen del artículo
     * @param string $titulo      Título del artículo
     * @param string $descripcion Descripción del artículo
     * @param string $contenido   Contenido HTML del artículo
     * @param array $etiquetas    Arreglo de las estiquetas seleccionadas
     *
     * @return array|int Filas afectadas
     */
    public function modificar($id, $idAutor, $idCategoria, $imagen, $titulo, $descripcion, $contenido, array $etiquetas = array())
    {
        $data = array(
            'id_autor'     => $idAutor,
            'id_categoria' => $idCategoria,
            'imagen'       => $imagen,
            'titulo'       => $titulo,
            'descripcion'  => $descripcion,
            'contenido'    => $contenido,
        );

        $filasAfectadas = $this->_update($data, array('id' => $id));

        if (!empty($etiquetas)) {
            $etiquetasArticuloModel = new EtiquetasArticulo($this->_db);
            $etiquetasArticulo = $etiquetasArticuloModel->_selectFields(array('id_etiqueta'), array(), 'WHERE id_articulo = ?', array($id));

            $etiquetaAgregadas = array();

            foreach ($etiquetasArticulo as $etiqueta) {
                if (!in_array($etiqueta['id_etiqueta'], $etiquetas)) {
                    $filasAfectadasEtiqueta = $etiquetasArticuloModel->guardar($id, $etiqueta['id_etiqueta']);

                    // Agregar la etiqueta si se inserto en la tabla, para el mensaje del usuario
                    if ($filasAfectadasEtiqueta == 1) {
                        $etiquetaAgregadas[] = $etiqueta;
                    }
                }
            }
            return array(
                'articulo'  => $filasAfectadas,
                'etiquetas' => $etiquetaAgregadas
            );
        } else {
            return $filasAfectadas;
        }
    }

    /**
     * @param int $id Id del artículo
     *
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        $filasAfectadas = 0;

        $comentarioModel = new Comentario($this->_db);

        $filasAfectadas = $filasAfectadas + $comentarioModel->_delete(array('id_articulo' => $id));

        $etiquetasArticuloModel = new EtiquetasArticulo($this->_db);

        $filasAfectadas = $filasAfectadas + $etiquetasArticuloModel->_delete(array('id_articulo' => $id));


        $filasAfectadas = $filasAfectadas + $this->_delete(array('id' => $id));

        return $filasAfectadas;
    }

}
