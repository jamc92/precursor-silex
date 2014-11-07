<?php
/**
 * Modelo de Comentarios
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 *
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Comentario extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'comentario');
    }
    
    /**
     * @param array $fields   Campos a obtener de la tabla
     * @param string $where   Condiciones where
     * @param array $criteria Arreglo para criterios de condición
     * 
     * @return array Arreglo de comentarios
     */
    public function getComentarios(array $fields = array(), $where = "", array $criteria = array())
    {
        if (empty($fields)) {
            $fields = array(
                'comentario.*',
                'articulo.titulo as articulo',
                'usuario.nombre as usuario'
            );
        }
        $join = array(
            array('articulo', 'usuario'),
            array('id_articulo', 'comentario.id_autor'),
            array('articulo.id', 'usuario.id'),
            array('=', '=')
        );
        return $this->getTodo($fields, $join, $where, $criteria);
    }
    
    /**
     * @param int $idArticulo Id del artículo
     * @param string $estatus Estatus del comentario
     * 
     * @return array Comentarios del artículo
     */
    public function getComentariosArticulo($idArticulo, $estatus = 'A')
    {
        return $this->getComentarios(array(), 'WHERE id_articulo = ? AND comentario.estatus = ?', array($idArticulo, $estatus));
    }

    /**
     * @param int $idArticulo   Id del articulo
     * @param int $idAutor      Id del usuario actual logueado
     * @param string $contenido Contenido del comentario
     * @param string $estatus Estatus del comentario
     * 
     * @return int Filas afectadas
     */
    public function guardar($idArticulo, $idAutor, $contenido, $estatus = 'I')
    {
        $data = array(
            'id_articulo' => $idArticulo,
            'id_autor'    => $idAutor,
            'contenido'   => $contenido,
            'fecha'       => date('Y-m-d H:m:s'),
            'estatus'     => $estatus
        );
        return $this->_insert($data);
    }

    public function aprobar($id)
    {
        return $this->_update(array('estatus' => 'A'), array('id' => $id));
    }

    /**
     * @param int $id Id del articulo
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }
    
}