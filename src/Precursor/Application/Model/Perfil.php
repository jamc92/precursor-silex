<?php
/**
 * Description of Perfil.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @subpackage Model
 */

namespace Precursor\Application\Model;

use Doctrine\DBAL\Connection,
    Precursor\Application\Model;

class Perfil extends Model
{
	
	function __construct(Connection $db)
	{
		parent::__construct($db, 'perfil');
	}

} 
