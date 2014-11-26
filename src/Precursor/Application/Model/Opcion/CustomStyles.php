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
     * @return string
     */
    public function getStyles()
    {
        return $this->_styles;
    }

    /**
     * @param string $styles CSS de los estilos personalizados de la aplicacion
     * 
     * @return int Filas afectadas
     */
    public function guardar($styles)
    {
        $filasAfectadas = parent::guardar('css', $this->_nombre, $styles);

        if ($filasAfectadas == 1) {
            Auditoria::getInstance()->guardar($this->_db, 'INSERT', 'CustomStyles', "Guardar estilos personalizados. Extra: $styles", 'EXITOSO');
        } else {
            Auditoria::getInstance()->guardar($this->_db, 'INSERT', 'CustomStyles', "Guardar estilos personalizados. Extra: $styles", 'FALLIDO');
        }

        return $filasAfectadas;
    }
    
    /**
     * @param string $styles JSON de los items del menú
     * 
     * @return int Filas afectadas
     */
    public function modificar($styles)
    {
        return parent::modificar($this->_id, 'css', $this->_nombre, $styles);
    }
}
