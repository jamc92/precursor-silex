<?php

/**
 * Description of SocialsCredentials
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 * @subpackage Options
 */

namespace Precursor\Options;

class SocialsCredentials {

    /**
     * @return array Arreglo de las credenciales de Google y Facebook
     */
    static function getArrayCredentials()
    {
        return array(
            'facebook' => array(
                'key'    => '267322913391926',
                'secret' => '8a645ba0807f6aa1e55b2a7b0449b08c'
            ),
            'google'   => array(
                'key'    => '810355613901-s5hug1ol9sqb8vkvalrlrhemlq7d8kp1.apps.googleusercontent.com',
                'secret' => 'rIfyO89aj5ZC0P-WOGqijuCe',
            ),
        );
    }

}