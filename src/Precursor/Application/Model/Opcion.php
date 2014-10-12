<?php
/**
 * Modelo de Opciones
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Opcion extends Model
{
    
    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'opcion');
    }
    
    /**
     * @param int $id        Id de la opción
     * @param string $nombre Nombre de la opción
     * @return array Arreglo de la opción
     */
    public function getOpcion($id, $nombre = null) {
        if (!is_null($id) && !is_null($nombre)) {
            $result = $this->getTodo(array(), array(), "WHERE id = $id AND nombre = '$nombre'");
        } else if (is_null($nombre)) {
            return $this->getPorId($id);
        } else if (is_null($id) && !is_null($nombre)) {
            $result = $this->getTodo(array(), array(), "WHERE nombre = '$nombre'");
        }
        if (isset($result[0]) && !empty($result[0])) {
            return $result[0];
        }
    }
    
    /**
     * @param string $tipo   Tipo de opción
     * @param string $nombre Nombre de la opción
     * @param string $valor  Valor de la opción
     * 
     * @return int Filas afectadas
     */
    public function guardar($tipo, $nombre, $valor)
    {
        $data = array(
            'tipo'   => $tipo,
            'nombre' => $nombre,
            'valor'  => $valor,
            'creado' => date('Y-m-d H:m:s')
        );
        return $this->_insert($data);
    }

    /**
     * @param int $id        Id de la opción
     * @param string $tipo   Tipo de opción
     * @param string $nombre Nombre de la opción
     * @param string $valor  Valor de la opción
     * 
     * @return int Filas afectadas
     */
    public function modificar($id, $tipo, $nombre, $valor)
    {
        $data = array(
            'tipo'   => $tipo,
            'nombre' => $nombre,
            'valor'  => $valor
        );
        return $this->_update($data, array('id' => $id));
    }
    
    /**
     * @param int $id Id de la opción
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }
    
}