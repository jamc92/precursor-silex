<?php
/**
 * Modelo de Perfiles
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Perfil extends Model
{
    
    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'perfil');
    }
    
    /**
     * @return array Ids de los Perfiles importantes
     */
    public function getPerfilesImportantes()
    {
        return array(1, 2, 3);
    }
    
    /**
     * @param string $nombre Perfil
     * @return boolean
     */
    public function existe($nombre)
    {
        $perfil = $this->_selectFields(array(), array(), 'WHERE nombre = ?', array($nombre));
        if (isset($perfil[0]) && !empty($perfil[0])) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * @param string $nombre Perfil
     * @return int Filas afectadas
     */
    public function guardar($nombre)
    {
        $data = array(
            'nombre' => $nombre,
            'creado' => date('Y-m-d H:m:s')
        );
        return $this->_insert($data);
    }
    
    /**
     * @param int $id Id del perfil
     * @param string $nombre Perfil
     * @return int Filas afectadas
     */
    public function modificar($id, $nombre)
    {
        $data = array(
            'nombre' => $nombre
        );
        return $this->_update($data, array('id' => $id));
    }
    
    /**
     * @param int $id Id del perfil
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }

}