<?php
/**
 * Description of PrecursorFilesProvider.php.
 *
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 *
 * @subpackage Options
 */

namespace Precursor\Options;


class Explorer
{

    /**
     * @return array
     */
    static function getFolders()
    {
        return array(
            'folders.public'    => self::getPublicFolders(),
            'folders.protected' => self::getProtectedFolders()
        );
    }

    /**
     * @return array
     */
    static function getPublicFolders()
    {
        return array(
            dirname(__DIR__) . "/DeveloperFile/"
        );
    }

    /**
     * @return array
     */
    static function getProtectedFolders()
    {
        return array(
            dirname(__DIR__) . "/",
            dirname(__DIR__) . "/Application",
            dirname(__DIR__) . "/File/",
            dirname(__DIR__) . "/Options/",
            dirname(__DIR__) . "/Provider/",
        );
    }
}