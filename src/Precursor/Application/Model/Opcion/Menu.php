<?php

/**
 * Description of Menu
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Opcion
 */

namespace Precursor\Application\Model\Opcion;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model\Opcion,
    \stdClass;

class Menu extends Opcion {

    /**
     * @var int $_id
     */
    protected $_id;

    /**
     * @var string $_nombre 
     */
    protected $_nombre;
    
    /**
     * @var array $_content
     */
    protected $_items;

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    public function __construct(Connection $db) {
        parent::__construct($db);
        
        $this->_id     = 1;
        $this->_nombre = 'menu';
        
        $opcion  = $this->getOpcion($this->_id, $this->_nombre);
        
        if (!empty($opcion)) {
            $this->_items = json_decode($opcion['valor']);
        }
        
    }
    
    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

    /**
     * @param string $items JSON de los items del menú
     * 
     * @return int Filas afectadas
     */
    public function guardar($items)
    {
        return parent::guardar('js', $this->_nombre, $items);
    }
    
    /**
     * @param string $items JSON de los items del menú
     * 
     * @return int Filas afectadas
     */
    public function modificar($items)
    {
        return parent::modificar($this->_id, 'js', $this->_nombre, $items);
    }

}
