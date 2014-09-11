<?php
/**
 * Description of Etiqueta.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Etiqueta extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'etiqueta');
    }

    /**
     * @param string $nombre Nombre de la etiqueta
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
     * @param int $id        Id de la etiqueta
     * @param string $nombre Nombre de la etiqueta
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
     * @param int $id Id de la etiqueta
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }
}
