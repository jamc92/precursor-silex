<?php

/**
 * Description of Menu
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Opcion
 */

namespace Precursor\Application\Model\Opcion;

use Precursor\Application\Model\Opcion;

class UsuarioCorreo extends Opcion
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
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     * @param int $id        Id de la opción
     */
    public function __construct(Connection $db, $id = null)
    {
        parent::__construct($db);

        $this->_id     = $id;
        $this->_nombre = 'usuario_correo';
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
} 