<?php
/**
 * Description of AccessRules.php.
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Options
 */

namespace Precursor\Options;


class AccessRules
{

    /**
     * Get the access rules
     *
     * @return array
     */
    static function getAccessRules()
    {
        return array(
            array('^/login$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
            array('^/admin/perfil', 'ROLE_SUPER_ADMIN'),
            array('^/admin/usuario', 'ROLE_SUPER_ADMIN'),
            array('^/admin/opcion', 'ROLE_SUPER_ADMIN'),
            array('^/admin', array('ROLE_SUPER_ADMIN', 'ROLE_ADMIN')),
            array('^/admin', 'ROLE_USER')
        );
    }
} 