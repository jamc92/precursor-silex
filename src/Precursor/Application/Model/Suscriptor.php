<?php
/**
 * Modelo de Suscriptor
 *
 * @author Javier Madrid <javiermadrid19@gmail.com>
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Suscriptor extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'suscriptor');
    }

    /**
     * @param string $correo Correo del suscriptor
     *
     * @return bool
     */
    public function existeCorreo($correo)
    {
        $suscriptor = $this->getTodo(array('*'), array(), 'WHERE correo = ?', array($correo));

        if (isset($suscriptor[0]) && !empty($suscriptor[0])) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * @param string $correo Correo del suscriptor
     * @param array $categorias Categorias de suscripcion
     *
     * @return int
     */
    public function guardar($correo, array $categorias = array())
    {
        $data = array(
            'correo'       => $correo,
            'categorias'   => json_encode($categorias),
            'creado'       => date('Y-m-d H:m:s'),
        );
        return $this->_insert($data);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }

} 