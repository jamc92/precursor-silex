<?php
/**
 * Modelo de Comentarios
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
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
     * @param array $fields Campos a obtener de la tabla
     * 
     * @return array Arreglo de comentarios
     */
    public function getComentarios(array $fields = array(), array $criteria = array())
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
        return $this->getTodo($fields, $join, '', $criteria);
    }
    
    /**
     * @param int $idArticulo Id del artículo
     * 
     * @return array Comentarios del artículo
     */
    public function getComentariosArticulo($idArticulo)
    {
        return $this->getComentarios(array(), array('id_articulo' => $idArticulo));
    }
    
    /**
     * @param int $idArticulo   Id del articulo
     * @param int $idAutor      Id del usuario actual logueado
     * @param string $asunto    Asunto del comentario
     * @param string $contenido Contenido del comentario
     * 
     * @return int Filas afectadas
     */
    public function guardar($idArticulo, $idAutor, $asunto, $contenido)
    {
        $data = array(
            'id_articulo' => $idArticulo,
            'id_autor'    => $idAutor,
            'asunto'      => $asunto,
            'contenido'   => $contenido,
            'fecha'       => date('Y-m-d H:m:s')
        );
        return $this->_insert($data);
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