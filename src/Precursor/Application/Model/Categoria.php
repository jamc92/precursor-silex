<?php
/**
 * Modelo de Categorías
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Categoria extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'categoria');
    }
    
    /**
     * @param array $fields Campos que se desean del registro
     * 
     * @return array Arreglo de usuarios
     */
    public function getCategorias(array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'categoria.*',
                'c.nombre as categoria'
            );
        }
        $join = array('categoria c', 'categoria.id_categoria', 'c.id', '=');
        return $this->getTodo($fields, $join);
    }
    
    /**
     * @param int $id_categoria Id de la categoría
     * @param string $nombre    Nombre de la categoría
     * 
     * @return int Filas afectadas
     */
    public function guardar($id_categoria = 1, $nombre)
    {
        $data = array(
            'id_categoria' => $id_categoria,
            'nombre'       => $nombre,
            'creado'       => date('Y:m:d H:m:s')
        );
        return $this->_insert($data);
    }

    /**
     * 
     * @param int $id           Id de la categoría
     * @param int $id_categoria Id de la categoría superior
     * @param string $nombre    Nombre de la categoría
     * 
     * @return int Filas afectadas
     */
    public function modificar($id, $id_categoria = 1, $nombre)
    {
        $data = array(
            'id_categoria' => $id_categoria,
            'nombre'       => $nombre
        );
        return $this->_update($data, array('id' => $id));
    }
    
    /**
     * @param int $id Id de la categoría
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }
    
}
