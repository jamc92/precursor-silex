<?php
/**
 * Description of ExtensionRegister.php.
 * 
 * @author Ramon Serrano <ramon.calle.88@gmail.com>
 * @package
 * @subpackage
 */

namespace Precursor\File\Upload;


interface ExtensionRegister {

    /**
     * Register the allowed extensions
     *
     * @param $allowedExtensions
     *
     * @return mixed
     */
    function registerExtensions($allowedExtensions);

} 