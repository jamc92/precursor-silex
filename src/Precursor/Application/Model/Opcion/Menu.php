<?php

/**
 * Description of Menu
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
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
    }
    
    /**
     * @param int $id        Id de la opcion, para este caso del menu
     * @param string $nombre Nombre de la opcion, para este caso del menu
     */
    public function setMenu($id, $nombre = null)
    {
        $opcion  = $this->getOpcion($id, $nombre);
        
        if (!empty($opcion)) {
            $this->_items = json_decode($opcion['valor']);
        }
        
        $this->_id     = $id;
        $this->_nombre = $nombre;
    }
    
    /**
     * @return array
     */
    public function getItems()
    {
        return $this->_items;
    }

	/**
	 * @param string $texto        Texto que muestra el item
	 * @param string $link         Url relativo o específico item
	 * @param boolean $dropdown    Dropdown o no
	 * @param array $dropdownItems Dropdown items
	 */
    public function guardarItem($texto, $link, $dropdown = false, array $dropdownItems = array()) {
        if ((!is_null($texto) && !is_null($link))) {
            $itemMenu = new stdClass();
            
            $itemMenu->texto    = $texto;
            $itemMenu->link     = $link;
            
            if ($dropdown && !empty($dropdownItems)) {
                $itemMenu->itemSubMenu = array();
                
                foreach ($dropdownItems as $count => $item) {
                    if (in_array('texto', $item) && in_array('link', $item)) {
                        $itemMenu->itemSubMenu[$count] = new stdClass();
                        
                        $itemMenu->itemSubMenu[$count]->texto = $item['texto'];
                        $itemMenu->itemSubMenu[$count]->link  = $item['link'];
                    }
                }
                
                $itemMenu->dropdown = $dropdown;
            }
        }
        
        $this->_items[] = $itemMenu;
        
        $filasAfectadas = $this->modificar($this->_id, 'js', $this->_nombre, $this->_items);
    }

}
