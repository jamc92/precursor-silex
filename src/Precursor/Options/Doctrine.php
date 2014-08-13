<?php
/**
 * Description of Doctrine.php.
 *
 * @author     Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Options
 */

namespace Precursor\Options;


class Doctrine
{
    /**
     * Get the doctrine options
     *
     * @return array
     */
    static function getOptions()
    {
        return array(
            'driver'   => 'pdo_mysql',
            'dbname'   => 'precursorbd',
            'host'     => '127.0.0.1',
            'user'     => 'root',
            'password' => '',
            'charset'  => 'utf8',
        );
    }
} 