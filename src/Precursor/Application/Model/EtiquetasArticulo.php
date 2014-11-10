<?php
/**
 * Modelo de Etiquetas de Artículos
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class EtiquetasArticulo extends Model
{

    /**
     * @param Connection $db Objeto de la conección de doctrine con la base de datos
     */
    public function __construct(Connection $db)
    {
	parent::__construct($db, 'articulos_etiquetas');
    }
    
    /**
     * 
     * @param int $idArticulo Id del artículo
     * @param array $fields   Campos a seleccionar
     * 
     * @return array Arreglo de las etiquetas del artículo
     */
    public function getEtiquetasArticulo($idArticulo, array $fields = array())
    {
        if (empty($fields)) {
            $fields = array(
                'articulos_etiquetas.*',
                'etiqueta.nombre as etiqueta'
            );
        }
        $join = array('etiqueta', 'id_etiqueta', 'etiqueta.id', '=');
        return $this->_selectFields($fields, $join, 'WHERE id_articulo = ?', array($idArticulo));
    }

    /**
     * @param int $idArticulo Id del artículo
     * @param int $idEtiqueta Id de la etiqueta
     *
     * @return int Filas afectadas
     */
    public function guardar($idArticulo, $idEtiqueta)
    {
        $data = array(
            'id_articulo' => $idArticulo,
            'id_etiqueta' => $idEtiqueta
        );
        
        // Consultar si existe
        $etiqueta = $this->_selectFields(array('id'), array(), 'WHERE id_articulo = ? AND id_etiqueta = ?', array($idArticulo, $idEtiqueta));
                
        if (isset($etiqueta[0]) && !empty($etiqueta)) {
            return 0;
        } else {
            return $this->_insert($data);
        }
    }
    
    /**
     * @param int $id Id de la etiqueta de artículo
     * 
     * @return int Filas afectadas
     */
    public function eliminar($id)
    {
        return $this->_delete(array('id' => $id));
    }

}