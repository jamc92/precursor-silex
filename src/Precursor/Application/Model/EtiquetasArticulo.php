<?php
/**
 * Description of EtiquetasArticulo.php.
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
        return $this->_insert($data);
    }

} 
