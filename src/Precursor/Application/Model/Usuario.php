<?php
/**
 * Modelo de Usuarios
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
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
            'perfil.nombre as perfil'
        );
        $join = array('perfil', 'id_perfil', 'perfil.id', '=');
        $usuario = $this->getTodo($fields, $join, 'WHERE alias = ?', array($alias));
        
        if (isset($usuario[0]) && !empty($usuario[0])) {
            return $usuario[0];
        }
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
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        $filasAfectadas = 0;
        
        $comentarioModelo = new Comentario($this->_db);
        
        $filasAfectadas += $comentarioModelo->_delete(array('id_autor' => $id));
        
        $articuloModelo = new Articulo($this->_db);
        
        $filasAfectadas += $articuloModelo->_update(array('id_autor' => ''), array('id_autor' => $id));
        
        $filasAfectadas += $this->_delete(array('id' => $id));
        
        return $filasAfectadas;
    }
    
}