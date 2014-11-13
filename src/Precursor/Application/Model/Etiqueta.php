<?php
/**
 * Modelo de Etiquetas
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model,
    Precursor\Application\Controller\Backend\Auditoria;

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
     * 
     * @return int Filas afectadas
     */
    public function guardar($nombre)
    {
        $data = array(
            'nombre' => $nombre,
            'creado' => date('Y-m-d H:m:s')
        );
        
        $filasAfectadas = $this->_insert($data);
        
        if ($filasAfectadas == 1) {
            Auditoria::getInstance()->guardar($this->_db, 'INSERT', 'Etiqueta', 'Guardar etiqueta. Nombre de etiqueta: ' . $nombre, 'EXITOSO');
        } else {
            Auditoria::getInstance()->guardar($this->_db, 'INSERT', 'Etiqueta', 'Guardar etiqueta. Nombre de etiqueta: ' . $nombre, 'FALLIDO');
        }
        
        return $filasAfectadas;
    }

    /**
     * @param int $id Id de la etiqueta
     * @param string $nombre Nombre de la etiqueta
     * 
     * @return int Filas afectadas
     */
    public function modificar($id, $nombre)
    {
        $data = array(
            'nombre' => $nombre
        );
        
        $filasAfectadas = $this->_update($data, array('id' => $id));
                
        if ($filasAfectadas == 1) {
            Auditoria::getInstance()->guardar($this->_db, 'UPDATE', 'Etiqueta', 'Modificar etiqueta. Nombre de etiqueta: ' . $nombre, 'EXITOSO');
        }
        
        return $filasAfectadas;
    }
    
    /**
     * @param int $id Id de la etiqueta
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        
        $filasAfectadas = $this->_delete(array('id' => $id));
        
        if ($filasAfectadas == 1) {
            Auditoria::getInstance()->guardar($this->_db, 'DELETE', 'Etiqueta', 'Guardar etiqueta. Nombre de etiqueta: ', 'EXITOSO');
        } else {
            Auditoria::getInstance()->guardar($this->_db, 'DELETE', 'Etiqueta', 'Guardar etiqueta. Nombre de etiqueta: ', 'FALLIDO');
        }
        
        return $filasAfectadas;
    }
    
}