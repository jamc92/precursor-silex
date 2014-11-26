<?php

/**
 * Clase para los estilos css personalizados
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * 
 * @subpackage Opcion
 */

namespace Precursor\Application\Model\Opcion;

use Doctrine\DBAL\Connection,
    Precursor\Application\Controller\Backend\Auditoria,
    Precursor\Application\Model\Opcion;

class CustomStyles extends Opcion
{

    /**
     * @var int $_id
     */
    protected $_id;

    /**
     * @var string $_nombre 
     */
    protected $_nombre;
    
    /**
     * @var string $_styles
     */
    protected $_styles;
    
    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     * @param int $id        Id de la opción
     */
    public function __construct(Connection $db, $id = null)
    {
        parent::__construct($db);
        
        $this->_id     = $id;
        $this->_nombre = 'custom_styles';
        
        $opcion  = $this->getOpcion($this->_id, $this->_nombre);
        
        if (!empty($opcion)) {
            $this->_styles = $opcion['valor'];
        }
        
    }
    
    /**
     * @param int $id Id del menú
     */
    public function setId($id)
    {
        $this->_id = $id;
    }

    /**
     * @param int $nombre Nombre del menú
     */
    public function setNombre($nombre)
    {
        $this->_nombre = $nombre;
    }
    
    /**
     * @return array
     */
    public function getStyles()
    {
        return $this->_styles;
    }

}
