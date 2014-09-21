<?php
/**
 * Modelo de Artículos o Noticias
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

            $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);
            $fields = array('etiqueta.id', 'etiqueta.nombre as etiqueta');
            $join = array('etiqueta', 'id_etiqueta', 'etiqueta.id', '=');
            $etiquetas = $etiquetasArticuloModelo->_selectFields($fields, $join, 'WHERE id_articulo = ?', array($id));

            if (!empty($etiquetas)) {
                foreach ($etiquetas as $etiqueta) {
                    $articulo['etiquetas'][$etiqueta['id']] = $etiqueta['etiqueta'];
                }
            } else {
                $articulo['etiquetas'] = array();
            }
            
        }

        return $articulo;
    }
    
    /**
     * @param array $fields Campos que se desean del registro
     * 
     * @return array Arreglo de artículos
     */
    public function getArticulos(array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'articulo.*',
                'categoria.nombre as categoria'
            );
        }
        $join = array('categoria', 'articulo.id_categoria', 'categoria.id', '=');
        $articulos = $this->getTodo($fields, $join);
        
        $comentarioModelo = new Comentario($this->_db);
        foreach ($articulos as $i => $articulo) {
            $comentarios = $comentarioModelo->getComentariosArticulo($articulo['id']);
            $articulos[$i]['comentarios'] = $comentarios;
        }
        return $articulos;
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

            $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);

            $etiquetaAgregadas = array();

            foreach ($etiquetas as $etiqueta) {
                $filasAfectadasEtiqueta = $etiquetasArticuloModelo->guardar($articulo[0]['id'], $etiqueta);

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
            $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);
            
            $etiquetasAgregadas = 0;
            
            foreach ($etiquetas as $etiqueta) {
                $etiquetasAgregadas += $etiquetasArticuloModelo->guardar($id, $etiqueta);
            }
            
            return array(
                'articulo'  => $filasAfectadas,
                'etiquetas' => $etiquetasAgregadas
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

        $comentarioModelo = new Comentario($this->_db);

        $filasAfectadas += $comentarioModelo->_delete(array('id_articulo' => $id));

        $etiquetasArticuloModelo = new EtiquetasArticulo($this->_db);

        $filasAfectadas += $etiquetasArticuloModelo->_delete(array('id_articulo' => $id));

        $filasAfectadas += $this->_delete(array('id' => $id));

        return $filasAfectadas;
    }

}
