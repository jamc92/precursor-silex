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
	
	function __construct(Connection $db)
	{
		parent::__construct($db, 'articulos_etiquetas');
	}
	
} 
