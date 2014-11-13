<?php

/**
 * Description of Menu
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Opcion
 */

namespace Precursor\Application\Model\Opcion;

use Doctrine\DBAL\Connection,
    Precursor\Application\Controller\Backend\Auditoria,
    Precursor\Application\Model\Opcion,
    \stdClass;

class Menu extends Opcion
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
     * @var array $_content
     */
    protected $_items;

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     * @param int $id        Id de la opción
     */
    public function __construct(Connection $db, $id = null) {
        parent::__construct($db);
        
        $this->_id     = $id;
        $this->_nombre = 'menu';
        
        $opcion  = $this->getOpcion($this->_id, $this->_nombre);
        
        if (!empty($opcion)) {
            $this->_items = json_decode($opcion['valor']);
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
        $filasAfectadas = parent::guardar('js', $this->_nombre, json_encode($items));

        $auditoriaController = new Auditoria($this->_db);

        if ($filasAfectadas == 1) {
            $auditoriaController->guardar($this->_db, 'INSERT', 'Menu', 'Guardar items de menú. Extra: ' . json_encode($items), 'EXITOSO');
            #Auditoria::getInstance()->guardar();
        } else {
            $auditoriaController->guardar($this->_db, 'INSERT', 'Menu', 'Guardar items de menú fallido. Extra: ' . json_encode($items), 'FALLIDO');
            #Auditoria::getInstance()->guardar($this->_db, 'INSERT', 'Menu', 'Guardar items de menú fallido. Extra: ' . json_encode($items), 'FALLIDO');
        }

        return $filasAfectadas;
    }
    
    /**
     * @param string $items JSON de los items del menú
     * 
     * @return int Filas afectadas
     */
    public function modificar($items)
    {
        return parent::modificar($this->_id, 'js', $this->_nombre, json_encode($items));
    }

}
