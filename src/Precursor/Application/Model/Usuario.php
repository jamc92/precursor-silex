<?php
/**
 * Modelo de Usuarios
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @author Javier Madrid <javiermadrid19@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Usuario extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'usuario');
    }
    
    /**
     * @param array $fields Campos que se desean del registro
     * 
     * @return array Arreglo de usuarios
     */
    public function getUsuarios(array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'usuario.*',
                'perfil.nombre as perfil'
            );
        }
        $join = array('perfil', 'id_perfil', 'perfil.id', '=');
        return $this->getTodo($fields, $join);
    }
    
    /**
     * @param string $alias Alias de usuario
     * 
     * @return array Arreglo del usuario
     */
    public function getUsuarioPorAlias($alias)
    {
        $fields = array(
            'usuario.id',
            'usuario.alias',
            'usuario.nombre',
            'usuario.correo',
            'usuario.clave',
            'perfil.nombre as perfil'
        );
        $join = array('perfil', 'id_perfil', 'perfil.id', '=');
        $usuario = $this->getTodo($fields, $join, 'WHERE alias = ? OR correo = ?', array($alias,$alias));
        
        if (isset($usuario[0]) && !empty($usuario[0])) {
            return $usuario[0];
        }
    }
    
    /**
     * @param string $alias  Alias del usuario
     * @param string $correo Correo electrónico del usuario
     * 
     * @return boolean
     */
    public function existe($alias, $correo = null)
    {
        if ($alias && !$correo) {
            $where = "WHERE alias = '$alias'";
        } else if (!$alias && $correo) {
            $where = "WHERE correo = '$correo'";
        } else if ($alias && $correo) {
            $where = "WHERE alias = '$alias' AND correo = '$correo'";
        }
        
        $usuario = $this->getTodo(array(), array(), $where);
        
        return (isset($usuario[0]) && !empty($usuario[0])) ? true : false;
    }

    /**
     * @param int $idPerfil  Id del perfil del usuario
     * @param string $nombre Nombre del usuario
     * @param string $correo Correo electrónico del usuario
     * @param string $alias  Alias del usuario
     * @param string $clave  Clave encriptada del usuario
     * 
     * @return int Filas afectadas
     */
    public function guardar($idPerfil, $nombre, $correo, $alias, $clave)
    {
        $data = array(
            'id_perfil' => $idPerfil,
            'nombre'    => $nombre,
            'correo'    => $correo,
            'alias'     => $alias,
            'clave'     => $clave
        );
        return $this->_insert($data);
    }
    
    /**
     * @param string $nombre Nombre del usuario
     * @param string $correo Correo electrónico del usuario
     * @param string $alias  Alias del usuario
     * @param string $clave  Clave encriptada del usuario
     * @param string $estatus Estatus del usuario
     * 
     * @return int Filas afectadas
     */
    public function guardarUsuario($nombre, $correo, $alias, $clave, $estatus = 'A')
    {   
        $data = array(
            'id_perfil' => 3,
            'nombre'    => $nombre,
            'correo'    => $correo,
            'alias'     => $alias,
            'clave'     => $clave,
            'estatus'   => $estatus,
            'creado'    => date('Y-m-d H:m:s')
        );
        return $this->_insert($data);
    }
    
    /**
     * @param int $id        Id del usuario
     * @param int $idPerfil  Id del perfil del usuario
     * @param string $nombre Nombre del usuario
     * @param string $correo Correo electrónico del usuario
     * @param string $alias  Alias del usuario
     * @param string $clave  Clave encriptada del usuario
     * 
     * @return int Filas afectadas
     */
    public function modificar($id, $idPerfil, $nombre, $correo, $alias, $clave)
    {
        $data = array(
            'id_perfil' => $idPerfil,
            'nombre'    => $nombre,
            'correo'    => $correo,
            'alias'     => $alias
        );

        if ($clave != "" && !is_null($clave)) {
            $data['clave'] = $clave;
        }
        
        return $this->_update($data, array('id' => $id));
    }

    /**
     * @param int $id Id del usuario
     * @param boolean $eliminar Determina si se elimina el usuario si es true. Si es false sólo cambia el estatus
     * 
     * @return int Filas afectadas
     *
     * @throws \Exception $ex
     */
    public function eliminar($id, $eliminar = false)
    {
        return ($eliminar === true) ? $this->_delete(array('id' => $id)) : $this->_update(array('estatus' => 'I'), array('id' => $id));
    }
    
}