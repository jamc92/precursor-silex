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
        $etiqueta = $this->_select("SELECT id FROM $this->_table", array(), 'WHERE id_articulo = ? AND id_etiqueta = ?', array($idArticulo, $idEtiqueta));
        
        if (!empty($etiqueta)) {
            return 0;
        } else {
            return $this->_insert($data);
        }
    }
    
    /**
     * @param int $idArticulo Id de Articulo
     * 
     * @return array Arreglo de etiquetas
     */
    public function getEtiquetasArticulo($idArticulo)
    {
        return $this->_selectFields(array('id_etiqueta'), array(), 'WHERE id_articulo = ?', array($idArticulo));
    }

}