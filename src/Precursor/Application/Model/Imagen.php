<?php
/**
 * Modelo de Imágenes
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Imagen extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    function __construct(Connection $db)
    {
        parent::__construct($db, 'imagen');
    }
    
    /**
     * @param array $fields Campos que se desean obtener
     * 
     * @return array Arreglo de las imagenes
     */
    public function getImagenes(array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'id', 'nombre', 'link', 'fuente_autor',
            );
        }
        return $this->_selectFields($fields);
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public function getImagenByUrl($url)
    {
        if (!is_null($url)){
            $imagen = $this->getTodo(array(), array(), 'WHERE link = ?', array($url));

            if (isset($imagen[0]) && !empty($imagen[0])){
                return $imagen[0];
            }
        }
    }

    /**
     * @param string $nombre Nombre de la imagen
     * @param string $link   URL de la imagen
     * @param string $imagen Puede ser la imagen misma en tipo MIME
     * @param string $fuente_autor Autor o fuente de la imagen
     * 
     * @return int Filas afectadas
     */
    public function guardar($nombre, $link, $fuente_autor, $imagen = '')
    {
        $data = array(
            'nombre' => $nombre,
            'link'   => $link,
            'fuente_autor' => $fuente_autor,
            'creado' => date('Y-m-d H:m:s')
        );
        
        if ($imagen != '' && !is_null($imagen)) {
            $data['imagen'] = $imagen;
        }
        
        return $this->_insert($data);
    }
    
    /**
     * @param int $id        Id de la imagen
     * @param string $nombre Nombre de la imagen
     * @param string $link   URL de la imagen
     * @param string $imagen Puede se la imagen misma en tipo MIME
     * @param string $fuente_autor Autor o fuente de la imagen
     * 
     * @return int Filas afectadas
     */
    public function modificar($id, $nombre, $link, $fuente_autor, $imagen = '')
    {
        $data = array(
            'nombre' => $nombre,
            'link'   => $link,
            'fuente_autor' => $fuente_autor,
            'modificado' => date('Y-m-d H:m:s')
        );
        
        if ($imagen != '' && !is_null($imagen)) {
            $data['imagen'] = $imagen;
        }
        
        return $this->_update($data, array('id' => $id));
    }
    
    /**
     * @param int $id Id de la imagen
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }
    
}