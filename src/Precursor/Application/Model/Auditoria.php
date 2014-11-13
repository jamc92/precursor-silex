<?php

/**
 * Modelo de Auditoria. 
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Driver\Connection,
    Precursor\Application\Model;

class Auditoria extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    public function __construct(Connection $db)
    {
        parent::__construct($db, 'auditoria');
    }
    
    /**
     * @param array $fields
     * 
     * @return array
     */
    public function getTodo(array $fields = array(), $id = null)
    {
        $join = array('usuario', 'id_usuario', 'usuario.id', '=');
        $where = ($id) ? "WHERE auditoria.id = $id" : '';
        return parent::getTodo($fields, $join, $where);
    }
    
    /**
     * 
     * @param int $idUsuario               Id del usuario
     * @param string $traza                Traza del error si ocurre un error
     * @param string $descripcion          Descripcion de la acción
     * @param string $ipMaquina            Ip de la máquina del cliente
     * @param string $modelo               Modelo que hace la acción
     * @param string $resultadoTransaccion Resultado de la acción (EXITOSO, FALLIDO, ERROR)
     * @param string $tipoTransaccion      Tipo de acción en BD (INSERT, UPDATE, SELECT, DELETE)
     * @param string $fechaHora            Fecha de la acción
     * 
     * @return int
     */
    public function guardar($idUsuario, $traza, $descripcion, $ipMaquina, $modelo, $resultadoTransaccion, $tipoTransaccion, $fechaHora)
    {
        $data = array(
            'id_usuario' => $idUsuario,
            'traza' => $traza,
            'descripcion' => $descripcion,
            'ip_maquina' => $ipMaquina,
            'modelo' => $modelo,
            'resultado_transaccion' => $resultadoTransaccion,
            'tipo_transaccion' => $tipoTransaccion,
            'fecha_hora' => $fechaHora
        );
        return $this->_insert($data);
    }

}
